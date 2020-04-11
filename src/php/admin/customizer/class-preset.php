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
	 */
	public function get_all() {
		$ret = array();
		foreach ( $this->settings as $id => $setting ) {
			$ret[ $id ] = array_merge( $setting['extends'], $setting['values'] );
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
		return array_merge( $this->settings[ $name ]['extends'], $this->settings[ $name ]['values'] );
	}

	/**
	 * Adds a settings field.
	 *
	 * @param string $name The name of the settings field.
	 * @param array  $args {
	 *     The setting field arguments.
	 *
	 *     @type string $type The type of the settings field. Accepts `option`, `theme_mod`.
	 *     @type bool   $imploded Whether the values are stored in the database imploded. Only used for theme mods. Default `false`.
	 *     @type array  $extends Original values to extend. Only used for options. Default `array()`.
	 *     @type array  $values The settings field values.
	 * }
	 *
	 * @return $this
	 */
	public function add_settings_field( $name, $args ) {
		if ( ! isset( $args['type'] ) ) {
			return $this;
		}
		if ( $args['type'] == 'theme_mod' && ! isset( $args['imploded'] ) ) {
			$args['imploded'] = false;
		}
		if ( ! isset( $args['extends'] ) ) {
			$args['extends'] = array();
		}
		if ( ! isset( $args['values'] ) ) {
			return $this;
		}
		$this->settings[ $name ] = $args;
		return $this;
	}
}

