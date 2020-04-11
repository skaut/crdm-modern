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
	 *     @type array $values The settings field values.
	 *     @type array $extends Original values to extend. Default `array()`.
	 * }
	 *
	 * @return $this
	 */
	public function add_settings_field( $name, $args ) {
		if ( ! isset( $args['extends'] ) ) {
			$args['extends'] = array();
		}
		$this->settings[ $name ] = $args;
		return $this;
	}
}

