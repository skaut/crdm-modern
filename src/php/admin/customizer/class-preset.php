<?php
/**
 * Contains the Preset class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer;

/**
 * A preset
 *
 * Contains all the data about a preset.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Preset {
	/**
	 * Translated preset name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Preset thumbnail image relative to the admin directory.
	 *
	 * @var string
	 */
	public $image;

	/**
	 * Preset settings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Preset class constructor
	 *
	 * @param string $name Translated preset name.
	 * @param string $image The preset thumbnail image relative to the admin directory.
	 */
	public function __construct( string $name, string $image ) {
		$this->name     = $name;
		$this->image    = $image;
		$this->settings = array();
	}

	/**
	 * Settings getter
	 *
	 * Returns the value of all settings of the preset.
	 *
	 * @return array The settings values.
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public function get_all() {
		$ret = array();
		foreach ( $this->settings as $id => $_ ) {
			$ret[ $id ] = $this->get( $id );
		}
		return $ret;
	}

	/**
	 * Settings getter
	 *
	 * Returns the value of a particular settings field.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array The settings values.
	 */
	public function get( string $name ) {
		$ret = $this->settings[ $name ]['default_values'];
		if ( ! is_array( $ret ) ) {
			$ret = array();
		}
		if ( 'option' === $this->settings[ $name ]['type'] ) {
			$ret = array_merge( $this->settings[ $name ]['extends'], $ret );
		}
		return $ret;
	}

	/**
	 * Returns a list of all the theme mod names in the preset.
	 *
	 * @return string[] A list of theme mods.
	 */
	public function theme_mods() {
		$ret = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( 'theme_mod' === $setting['type'] ) {
				$ret[] = $id;
			}
		}
		return $ret;
	}

	/**
	 * Gets the extended values of a settings field.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array The settings extended values.
	 */
	private function extends_values( string $name ) {
		return array_merge( ...array_map( 'call_user_func', $this->settings[ $name ]['extends'] ) );
	}

	/**
	 * Settings getter
	 *
	 * Returns the current values of a particular settings field, including extended values.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array The settings current values.
	 */
	public function get_current_values( string $name ) {
		return wp_parse_args(
			get_option( $name, array() ),
			array_merge( $this->extends_values( $name ), $this->get_stylesheet_defaults( $name ) )
		);
	}

	/**
	 * Settings getter
	 *
	 * Returns the default values of a particular settings field, excluding extended values.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return mixed The settings default values.
	 */
	public function get_stylesheet_defaults( string $name ) {
		return $this->settings[ $name ]['default_values'];
	}

	/**
	 * Adds a settings field.
	 *
	 * @param string $name The name of the settings field.
	 * @param array  $args {
	 *     The setting field arguments.
	 *
	 *     @type string   $type The type of the settings field. Accepts `option`, `theme_mod`.
	 *     @type string[] $extends Original values to extend expressed as a list of function names used to get the values. Only used for options. Default `array()`.
	 *     @type array    $default_values The settings field default values. Default `array()`.
	 * }
	 *
	 * @return $this
	 */
	public function add_settings_field( $name, $args ) {
		if ( ! isset( $args['type'] ) ) {
			return $this;
		}
		if ( ! isset( $args['extends'] ) ) {
			$args['extends'] = array();
		}
		if ( ! isset( $args['default_values'] ) ) {
			$args['default_values'] = null;
		}
		$this->settings[ $name ] = $args;
		return $this;
	}
}

