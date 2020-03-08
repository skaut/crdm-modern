<?php
/**
 * Contains the WordPress_Github_Updater class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Update;

/**
 * Checks for updates from GitHub.
 *
 * Allows for updating a resource (e.g. a theme) from GitHub. Releases (tags) on Github are used as versions with the zip file taken from the release as the asset with the name `wp-slug.version.zip`. The updater hooks into the standard WordPress update functionality so for the user it is as though the resource were hosted on wordpress.org.
 */
class WordPress_Github_Updater {
	/**
	 * The WordPress slug of the resource.
	 *
	 * @var string
	 */
	private $wp_slug;

	/**
	 * The GitHub slug of the project in the form `user/repo`.
	 *
	 * @var string
	 */
	private $gh_slug;

	/**
	 * Registers a new resource (theme) to be auto-updated.
	 *
	 * @param string $wp_slug The WordPress slug of the resource.
	 * @param string $gh_slug The GitHub slug of the project in the form `user/repo`.
	 * @param string $type Resource type. Accepts `theme`.
	 *
	 * @throws \Exception Invalid type.
	 */
	public function __construct( $wp_slug, $gh_slug, $type ) {
		$this->wp_slug = $wp_slug;
		$this->gh_slug = $gh_slug;
		switch ( $type ) {
			case 'theme':
				add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_theme' ) );
				break;
			default:
				throw new \Exception( 'Wordpress_Github_Updater called with invalid type!' );
		}
	}

	/**
	 * Injects the necessary data into the WordPress update-checking logic.
	 *
	 * @param array $transient {
	 *     The WordPress update data.
	 *
	 *     @type int   $last_checked
	 *     @type array $checked
	 *     @type array $response
	 *     @type array $translations
	 * }
	 *
	 * @return array {
	 *     The update data with the injected values.
	 *
	 *     @type int   $last_checked
	 *     @type array $checked
	 *     @type array $response
	 *     @type array $translations
	 * }
	 */
	public function update_theme( $transient ) {
		if ( empty( $transient['checked'] ) || empty( $transient['checked'][ $this->wp_slug ] ) ) {
			return $transient; // TODO: Error reporting.
		}

		$raw_response = wp_remote_get( 'https://api.github.com/repos/' . $this->gh_slug . '/releases/latest' );
		if ( is_wp_error( $raw_response ) || wp_remote_retrieve_response_code( $raw_response ) !== 200 || ! isset( $raw_response['body'] ) ) {
			return $transient; // TODO: Error reporting.
		}
		$response = json_decode( $raw_response['body'] );
		if ( ! isset( $response->tag_name ) || ! isset( $response->html_url ) ) {
			return $transient; // TODO: Error reporting.
		}

		$version = ltrim( $response->tag_name, 'v' );
		if ( version_compare( $version, $transient['checked'][ $this->wp_slug ], '<=' ) ) {
			return $transient;
		}

		$zip_url = null;
		foreach ( $response->assets as $asset ) {
			if ( ! isset( $asset->name ) || ! isset( $asset->browser_download_url ) ) {
				return $transient; // TODO: Error reporting.
			}
			if ( $asset->name === $this->wp_slug . '.' . $version . '.zip' ) {
				$zip_url = $asset->browser_download_url;
			}
		}
		if ( is_null( $zip_url ) ) {
			return $transient; // TODO: Error reporting.
		}

		$transient['response'][ $this->wp_slug ] = array(
			'theme'       => $this->wp_slug,
			'new_version' => $version,
			'url'         => $response->html_url,
			'package'     => $zip_url,
		);
		return $transient;
	}
}
