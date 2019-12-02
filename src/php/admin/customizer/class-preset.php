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
	 * Preset id
	 *
	 * @var string
	 */
	public $id;

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
	 * @param string $id The preset id.
	 * @param string $name Translated preset name.
	 * @param string $image The preset thumbnail image relative to the admin directory.
	 * @param array  $settings The preset settings.
	 */
	public function __construct( string $id, string $name, string $image, array $settings ) {
		$this->id       = $id;
		$this->name     = $name;
		$this->image    = $image;
		$this->settings = $settings;
	}

	/**
	 * Checks whether a variable is an associative array.
	 *
	 * @param mixed $input A variable.
	 *
	 * @return bool True if input is an associative array.
	 */
	private static function is_assoc( $input ) {
		if ( ! is_array( $input ) ) {
			return false;
		}
		if ( array() === $input ) {
			return false;
		}
		if ( count( array_filter( array_keys( $input ), 'is_string' ) ) > 0 ) {
			return true;
		}
		return array_keys( $input ) !== range( 0, count( $input ) - 1 );
	}

	/**
	 * Transforms an nested associative array into a simple one
	 *
	 * For nested arrays, the function transforms tme into a list of keys in the form `key[innerKey]`.
	 *
	 * @param array $array A nested array.
	 *
	 * @return array A non-nested array with expanded values.
	 */
	private static function flatten_inner( array $array ) {
		$ret = array();
		foreach ( $array as $key => $value ) {
			if ( self::is_assoc( $value ) ) {
				foreach ( self::flatten_inner( $value ) as $key_inner => $value_inner ) {
					$ret[ '[' . $key . ']' . $key_inner ] = $value_inner;
				}
			} else {
				$ret[ '[' . $key . ']' ] = $value;
			}
		}
		return $ret;
	}

	/**
	 * Returns the flattened settings.
	 *
	 * Flattens the settings, transforming nested array into array with keys like `key[innerKey]`.
	 *
	 * @return array The flattened settings.
	 */
	public function flat_settings() {
		$ret = array();
		foreach ( $this->settings as $key => $value ) {
			if ( self::is_assoc( $value ) ) {
				foreach ( self::flatten_inner( $value ) as $key_inner => $value_inner ) {
					$ret[ $key . $key_inner ] = $value_inner;
				}
			} else {
				$ret[ $key ] = $value;
			}
		}
		return $ret;
	}
}

