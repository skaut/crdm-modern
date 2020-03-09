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
	 * @param object $transient The WordPress update data.
	 *
	 * @return object The updated update data with the injected values.
	 */
	public function update_theme( $transient ) {
		try {
			$this->check_transient( $transient );
			$response = $this->github_request();
			$version  = ltrim( $response->tag_name, 'v' );
			if ( version_compare( $version, $transient->checked[ $this->wp_slug ], '<=' ) ) {
				return $transient;
			}
			$zip_url = $this->get_zip_url( $response, $version );
		} catch ( \Exception $e ) {
			// TODO: Error handling.
			return $transient;
		}

		$transient->response[ $this->wp_slug ] = array(
			'theme'       => $this->wp_slug,
			'new_version' => $version,
			'url'         => $response->html_url,
			'package'     => $zip_url,
		);
		return $transient;
	}

	/**
	 * Checks the transient for the current resource.
	 *
	 * @param object $transient The WordPress update data.
	 *
	 * @return void
	 *
	 * @throws \Exception The resource not present in `$transient`.
	 */
	private function check_transient( $transient ) {
		if ( empty( $transient->checked ) || empty( $transient->checked[ $this->wp_slug ] ) ) {
			throw new \Exception(); // TODO: Message.
		}
	}

	/**
	 * Makes the actual request to the GitHub API
	 *
	 * @return mixed The API response.
	 *
	 * @throws \Exception The request failed.
	 */
	private function github_request() {
		$raw_response = wp_remote_get( 'https://api.github.com/repos/' . $this->gh_slug . '/releases/latest' );
		if ( is_wp_error( $raw_response ) || wp_remote_retrieve_response_code( $raw_response ) !== 200 || ! isset( $raw_response['body'] ) ) {
			throw new \Exception(); // TODO: Message.
		}
		$response = json_decode( $raw_response['body'] );
		self::check_response_fields( $response );
		return $response;
	}

	/**
	 * Checks the GitHub response for the required fields.
	 *
	 * @param mixed $response The GitHub response.
	 *
	 * @return void
	 *
	 * @throws \Exception Required fields missing.
	 */
	private static function check_response_fields( $response ) {
		if ( ! isset( $response->tag_name ) || ! isset( $response->html_url ) || ! isset( $response->assets ) ) {
			throw new \Exception(); // TODO: Message.
		}
		foreach ( $response->assets as $asset ) {
			if ( ! isset( $asset->name ) || ! isset( $asset->browser_download_url ) ) {
				throw new \Exception(); // TODO: Message.
			}
		}
	}

	/**
	 * Returns the update zip archive url.
	 *
	 * @param mixed  $response The GitHub response.
	 * @param string $version The update version.
	 *
	 * @return string The update zip archive url.
	 *
	 * @throws \Exception No update archive.
	 */
	private function get_zip_url( $response, $version ) {
		$zip_url = null;
		foreach ( $response->assets as $asset ) {
			if ( $asset->name === $this->wp_slug . '.' . $version . '.zip' ) {
				$zip_url = $asset->browser_download_url;
			}
		}
		if ( is_null( $zip_url ) ) {
			throw new \Exception(); // TODO: Message.
		}
		return $zip_url;
	}
}
