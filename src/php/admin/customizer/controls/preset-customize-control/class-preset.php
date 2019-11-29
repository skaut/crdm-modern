<?php
/**
 * Contains the Preset class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Controls\Preset_Customize_Control;

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
	 * Preset class constructor
	 *
	 * @param string $id The preset id.
	 * @param string $name Translated preset name.
	 * @param string $image The preset thumbnail image relative to the admin directory.
	 */
	public function __construct( string $id, string $name, string $image ) {
		$this->id    = $id;
		$this->name  = $name;
		$this->image = $image;
	}
}

