<?php

namespace CrdmModern\Admin\Updater;

function register() {
	new WordpressGithubUpdater( 'crdm-modern', 'skaut/crdm-modern', 'theme' );
}

class WordpressGithubUpdater {
	public function __construct( $wp_slug, $gh_slug, $type ) {
		$this->wp_slug = $wp_slug;
		$this->gh_slug = $gh_slug;
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'pre_set_site_transient_update_themes' ) );
	}

	public function pre_set_site_transient_update_themes( $transient ) {
		if ( empty( $transient->checked ) || empty( $transient->checked[ $this->wp_slug ] ) ) {
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
		if ( version_compare( $version, $transient->checked[ $this->wp_slug ], '<=' ) ) {
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

		$transient->response[ $this->wp_slug ] = array(
			'theme'       => $this->wp_slug,
			'new_version' => $version,
			'url'         => $response->html_url,
			'package'     => $zip_url,
		);
		return $transient;
	}
}
