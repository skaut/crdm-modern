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
	 * @var array<string, mixed>
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
	 * Returns a list of all the option names in the preset.
	 *
	 * @return string[] A list of options.
	 */
	public function options() {
		return $this->list_by_type( 'option' );
	}

	/**
	 * Returns a list of all the theme mod names in the preset.
	 *
	 * @return string[] A list of theme mods.
	 */
	public function theme_mods() {
		return $this->list_by_type( 'theme_mod' );
	}

	/**
	 * Lists all the settings field IDs of a particular type.
	 *
	 * @param string $type The type of the settings field. Accepts `option`, `theme_mod`.
	 * @return string[] The list
	 */
	private function list_by_type( $type ) {
		$ret = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $type === $setting['type'] ) {
				$ret[] = strval( $id );
			}
		}
		return $ret;
	}

	/**
	 * Gets the extended values of a settings field.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array<string, mixed> The settings extended values.
	 */
	private function extends_values( string $name ) {
		if ( is_null( $this->settings[ $name ]['extends'] ) ) {
			return array();
		}
		$functions = array_values( array_filter( $this->settings[ $name ]['extends'], 'function_exists' ) );
		if ( empty( $functions ) ) {
			return array();
		}
		return array_merge( ...array_map( 'call_user_func', $functions ) );
	}

	/**
	 * Settings getter
	 *
	 * Returns the current values of a particular settings field, including extended values.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array<int|string, mixed> The settings current values.
	 */
	public function get_current_values( string $name ) {
		return wp_parse_args(
			get_option( $name, array() ),
			$this->get_template_defaults( $name )
		);
	}

	/**
	 * Settings getter
	 *
	 * Returns the default values of a particular settings field, including extended values.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return mixed The settings default values.
	 */
	public function get_template_defaults( string $name ) {
		$stylesheet_defaults = $this->get_stylesheet_defaults( $name );
		if ( 'option' === $this->settings[ $name ]['type'] ) {
			if ( is_null( $stylesheet_defaults ) ) {
				return $this->extends_values( $name );
			}
			return array_merge( $this->extends_values( $name ), $this->get_stylesheet_defaults( $name ) );
		} else {
			if ( is_null( $stylesheet_defaults ) ) {
				return $this->settings[ $name ]['extends'];
			}
			return $stylesheet_defaults;
		}
	}

	/**
	 * Settings getter
	 *
	 * Returns the default values of a particular settings field, excluding extended values.
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array{header_image: string, header_image_height: string, header_image_top: string, header_image_right: string, header_image_min_width: string, primary_navigation_spacing: string, primary_navigation_shadow: string, sidebar_widget_separator_width: string, sidebar_widget_separator_spacing: string, sidebar_widget_separator_color: string, excerpt_border_color: string, excerpt_hover_background_color: string, excerpt_hover_text_color: string, read_more_color: string, read_more_hover_color: string, blog_font_weight: string, featured_post_count: string, blog_font_size: int, blog_font_transform: string} The settings default values.
	 */
	public function get_stylesheet_defaults( string $name ) {
		return $this->settings[ $name ]['default_values'];
	}

	/**
	 * Settings getter
	 *
	 * Returns the default values of all settings fields, including extended values.
	 *
	 * @return mixed The defautl values.
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public function get_all_template_defaults() {
		$ret = array();
		foreach ( $this->settings as $id => $_ ) {
			$ret[ $id ] = $this->get_template_defaults( $id );
		}
		return $ret;
	}

	/**
	 * Adds a settings field.
	 *
	 * @param string               $name The name of the settings field.
	 * @param array<string, mixed> $args {
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
			$args['extends'] = null;
		}
		if ( ! isset( $args['default_values'] ) ) {
			$args['default_values'] = null;
		}
		$this->settings[ $name ] = $args;
		return $this;
	}
}

