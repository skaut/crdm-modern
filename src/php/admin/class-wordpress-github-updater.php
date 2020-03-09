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
 * Allows for updating a package (e.g. a theme) from GitHub. Releases (tags) on Github are used as versions with the zip file taken from the release as the asset with the name `wp-slug.version.zip`. The updater hooks into the standard WordPress update functionality so for the user it is as though the package were hosted on wordpress.org.
 */
class WordPress_Github_Updater {
	/**
	 * Error message: "The package $s is not available for updating.".
	 *
	 * @var string
	 */
	private static $err_msg_not_available = 'The package $s is not available for updating.';

	/**
	 * Error message: "The GitHub API request for package $s failed.".
	 *
	 * @var string
	 */
	private static $err_msg_request_failed = 'The GitHub API request for package $s failed.';

	/**
	 * Error message: "Error message:".
	 *
	 * @var string
	 */
	private static $err_msg_error_message = 'Error message:';

	/**
	 * Error message: "The GitHub API response for package $s is invalid.".
	 *
	 * @var string
	 */
	private static $err_msg_response_invalid = 'The GitHub API response for package $s is invalid.';

	/**
	 * Error message: "The latest version of package $s does not contain an update zip file.".
	 *
	 * @var string
	 */
	private static $err_msg_no_zip = 'The latest version of package $s does not contain an update zip file.';

	/**
	 * The WordPress slug of the package.
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
	 * The name of the package to be used in error messages.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Sets the internationalized error messages.
	 *
	 * By default, the updater prints the error messages in English only. If you want to internationalize them, call this function in the form `Wordpress_Github_Updater::set_error_messages_i10n( __( "..." ), __( "..." ), ... )`.
	 *
	 * @param string $not_available Error message: "The package $s is not available for updating".
	 * @param string $request_failed Error message: "The GitHub API request for package $s failed.".
	 * @param string $error_message Error message: "Error message:".
	 * @param string $response_invalid Error message: "The GitHub API response for package $s is invalid.".
	 *
	 * @return void
	 */
	public static function set_error_messages_i10n( $not_available, $request_failed, $error_message, $response_invalid ) {
		self::$err_msg_not_available    = $not_available;
		self::$err_msg_request_failed   = $request_failed;
		self::$err_msg_error_message    = $error_message;
		self::$err_msg_response_invalid = $response_invalid;
	}

	/**
	 * Registers a new package (theme) to be auto-updated.
	 *
	 * @param string $wp_slug The WordPress slug of the package.
	 * @param string $gh_slug The GitHub slug of the project in the form `user/repo`.
	 * @param string $name The name of the package to be used in error messages.
	 * @param string $type Resource type. Accepts `theme`.
	 *
	 * @throws \Exception Invalid type.
	 */
	public function __construct( $wp_slug, $gh_slug, $name, $type ) {
		$this->wp_slug = $wp_slug;
		$this->gh_slug = $gh_slug;
		$this->name    = $name;
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
			// TODO: Error handling with esc_html.
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
	 * Checks the transient for the current package.
	 *
	 * @param object $transient The WordPress update data.
	 *
	 * @return void
	 *
	 * @throws \Exception The package $s is not available for updating.
	 */
	private function check_transient( $transient ) {
		if ( empty( $transient->checked ) || empty( $transient->checked[ $this->wp_slug ] ) ) {
			throw new \Exception( sprintf( self::$err_msg_not_available, $this->name ) );
		}
	}

	/**
	 * Makes the actual request to the GitHub API
	 *
	 * @return mixed The API response.
	 *
	 * @throws \Exception The GitHub API request for package $s failed.
	 */
	private function github_request() {
		$raw_response = wp_remote_get( 'https://api.github.com/repos/' . $this->gh_slug . '/releases/latest' );
		if ( is_wp_error( $raw_response ) ) {
			throw new \Exception( sprintf( self::$err_msg_request_failed, $this->name ) . ' ' . self::$err_msg_error_message . ' ' . $raw_response->get_error_message() );
		}
		if ( wp_remote_retrieve_response_code( $raw_response ) !== 200 || ! isset( $raw_response['body'] ) ) {
			throw new \Exception( sprintf( self::$err_msg_request_failed, $this->name ) );
		}
		$response = json_decode( $raw_response['body'] );
		$this->check_response_fields( $response );
		return $response;
	}

	/**
	 * Checks the GitHub response for the required fields.
	 *
	 * @param mixed $response The GitHub response.
	 *
	 * @return void
	 *
	 * @throws \Exception The GitHub API response for package $s is invalid.
	 */
	private function check_response_fields( $response ) {
		if ( ! isset( $response->tag_name ) || ! isset( $response->html_url ) || ! isset( $response->assets ) ) {
			throw new \Exception( sprintf( self::$err_msg_response_invalid, $this->name ) );
		}
		foreach ( $response->assets as $asset ) {
			if ( ! isset( $asset->name ) || ! isset( $asset->browser_download_url ) ) {
				throw new \Exception( sprintf( self::$err_msg_response_invalid, $this->name ) );
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
	 * @throws \Exception The latest version of package $s does not contain an update zip file.
	 */
	private function get_zip_url( $response, $version ) {
		$zip_url = null;
		foreach ( $response->assets as $asset ) {
			if ( $asset->name === $this->wp_slug . '.' . $version . '.zip' ) {
				$zip_url = $asset->browser_download_url;
			}
		}
		if ( is_null( $zip_url ) ) {
			throw new \Exception( sprintf( self::$err_msg_no_zip, $this->name ) );
		}
		return $zip_url;
	}
}
