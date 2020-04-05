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
	public $settings;

	/**
	 * Preset class constructor
	 *
	 * @param string $name Translated preset name.
	 * @param string $image The preset thumbnail image relative to the admin directory.
	 * @param array  $settings The preset settings.
	 */
	public function __construct( string $name, string $image, array $settings ) {
		$this->name     = $name;
		$this->image    = $image;
		$this->settings = $settings;
	}
}

