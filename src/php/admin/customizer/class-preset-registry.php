<?php
/**
 * Contains the Preset_Registry class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer;

/**
 * Contains all the available presets.
 */
class Preset_Registry {
	/**
	 * The registered presets
	 *
	 * @var array
	 */
	public $presets;

	/**
	 * The id of the default preset.
	 *
	 * @var string
	 */
	private $default;

	/**
	 * Preset_Registry class constructor
	 *
	 * @param array  $presets A associative array of presets.
	 * @param string $default The id of the default preset.
	 */
	public function __construct( $presets, $default ) {
		$this->presets = $presets;
		$this->default = $default;
	}

	/**
	 * Returns an associative array where each item contains the flattened settings of a preset
	 *
	 * @return array A list of flattened preset settings.
	 */
	public function flat_settings() {
		$ret = array();
		foreach ( $this->presets as $id => $preset ) {
			$ret[ $id ] = $preset->flat_settings();
		}
		return $ret;
	}

	/**
	 * The default registry.
	 *
	 * @var Preset_Registry
	 */
	private static $instance = null;

	/**
	 * Returns the default registry. See the singleton pattern for more detail.
	 *
	 * @return Preset_Registry The default registry.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Preset_Registry(
				array(
					'blue' => new Preset(
						__( 'Blue', 'crdm-modern' ),
						'presets/blue.png',
						array(
							'generate_settings'            => array(
								// Site Identity.
								'logo_width'                             => '100',
								'inline_logo_site_branding'              => true,
								// Layout.
								'content_layout_setting'                 => 'one-container',
								'header_layout_setting'                  => 'contained-header',
								'nav_layout_setting'                     => 'contained-nav',
								'footer_widget_setting'                  => '3',
								// Colors.
								'site_title_color'                       => '#00395e',
								'site_tagline_color'                     => '#00395e',
								'navigation_background_color'            => '#007bc2',
								'navigation_background_hover_color'      => '#00395e',
								'navigation_background_current_color'    => '#00395e',
								'subnavigation_background_color'         => '#00395e',
								'subnavigation_background_hover_color'   => '#007bc2',
								'subnavigation_background_current_color' => '#007bc2',
								'sidebar_widget_title_color'             => '#007bc2',
								'footer_widget_background_color'         => '#007bc2',
								'footer_widget_text_color'               => '#ffffff',
								'footer_widget_link_color'               => '#ffffff',
								'footer_widget_title_color'              => '#ffffff',
								// Typography.
								'font_body'                              => 'Source Sans Pro',
								'body_font_weight'                       => '600',
								// TODO: Fix Customizer UI not updating for font sizes.
								'body_font_size'                         => '15',
								'font_site_title'                        => 'Raleway',
								'site_title_font_weight'                 => '900',
								'font_site_tagline'                      => 'Raleway',
								'site_tagline_font_size'                 => '25',
								'navigation_font_weight'                 => 'bold',
								'navigation_font_transform'              => 'uppercase',
								'navigation_font_size'                   => '17',
								'heading_2_weight'                       => '700',
								'heading_2_font_size'                    => '19',
								'widget_title_font_size'                 => '18',
								'widget_title_separator'                 => '5',
								'widget_content_font_size'               => '15',
							),
							'generate_spacing_settings'    => array(
								// Layout.
								'menu_item' => '25',
							),
							'generate_blog_settings'       => array(
								// Layout.
								'excerpt_length'       => '25',
								'read_more'            => __( 'Read more', 'crdm-modern' ) . ' >',
								'author'               => false,
								'categories'           => false,
								'tags'                 => false,
								'comments'             => false,
								'post_image_position'  => 'post-image-above-header',
								'post_image_alignment' => 'post-image-aligned-left',
								'post_image_width'     => '300',
								'post_image_height'    => '200',
							),
							'generate_background_settings' => array(
								// Background.
								// TODO: Fix Customizer UI not updating for images.
								'body_image'      => get_stylesheet_directory_uri() . '/frontend/background.jpg',
								'body_size'       => 'cover',
								'body_attachment' => 'fixed',
							),
							// Typography.
							// TODO: Fix Customizer UI not updating for font variants.
							'font_body_variants'           => array( '600', '600italic', '700', '700italic' ), // This needs to be consistent across all places with the same font.
							'font_site_title_variants'     => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
							'font_site_tagline_variants'   => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
						)
					),
				),
				'blue'
			);
		}
		return self::$instance;
	}
}
