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
	 * Returns the defaults preset
	 *
	 * @return Preset The default preset.
	 */
	public function default_preset() {
		return $this->presets[ $this->default ];
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
					'blue'  => new Preset(
						__( 'Blue', 'crdm-modern' ),
						'presets/blue.png',
						array(
							'crdm_modern'                  => array(
								'header_image'                     => get_stylesheet_directory_uri() . '/frontend/images/tee-pee.png',
								'header_image_height'              => '220',
								'header_image_top'                 => '13',
								'header_image_right'               => '100',
								'header_image_min_width'           => '950',
								'primary_navigation_spacing'       => '40',
								'primary_navigation_shadow'        => '2px 4px 5px rgba(0, 0, 0, 0.4)',
								'sidebar_widget_separator_width'   => '1',
								'sidebar_widget_separator_spacing' => '20',
								'sidebar_widget_separator_color'   => '#007bc2',
								'excerpt_border_color'             => '#007bc2',
								'excerpt_hover_background_color'   => '#007bc2',
								'excerpt_hover_text_color'         => '#ffffff',
								// Featured posts.
								'featured_post_count'              => '2',
								// Read more link.
								'read_more_color'                  => '#007bc2',
								'read_more_hover_color'            => '#ffffff',
								// Blog typography.
								'blog_font_size'                   => 15,
							),
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
								'link_color'                             => '#007bc2',
								'site_title_color'                       => '#00395e',
								'site_tagline_color'                     => '#00395e',
								'navigation_background_color'            => '#007bc2',
								'navigation_background_hover_color'      => '#00395e',
								'navigation_background_current_color'    => '#00395e',
								'subnavigation_background_color'         => '#00395e',
								'subnavigation_background_hover_color'   => '#007bc2',
								'subnavigation_background_current_color' => '#007bc2',
								'sidebar_widget_title_color'             => '#007bc2',
								'sidebar_widget_link_color'              => '#3a3a3a',
								'sidebar_widget_link_hover_color'        => '#007bc2',
								'blog_post_title_hover_color'            => '#ffffff',
								'entry_meta_link_color'                  => '#00395e',
								'entry_meta_link_color_hover'            => '#ffffff',
								'footer_widget_background_color'         => '#007bc2',
								'footer_widget_text_color'               => '#ffffff',
								'footer_widget_link_color'               => '#ffffff',
								// Typography.
								'font_body'                              => 'Source Sans Pro',
								'body_font_weight'                       => '600',
								'body_font_size'                         => '15',
								'font_site_title'                        => 'Raleway',
								'site_title_font_weight'                 => '900',
								'font_site_tagline'                      => 'Raleway',
								'site_tagline_font_weight'               => '400',
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
								'menu_item'                 => '25',
								'content_element_separator' => '0.5',
							),
							'generate_blog_settings'       => array(
								// Layout.
								'excerpt_length'             => '20',
								'read_more'                  => __( 'Read more >', 'crdm-modern' ),
								'author'                     => false,
								'categories'                 => false,
								'tags'                       => false,
								'comments'                   => false,
								'single_categories'          => false,
								'single_tags'                => false,
								'single_post_navigation'     => false,
								'post_image_position'        => 'post-image-above-header',
								'post_image_alignment'       => 'post-image-aligned-left',
								'post_image_width'           => '300',
								'post_image_height'          => '200',
								'single_post_image_position' => 'below-title',
								'page_post_image_position'   => 'below-title',
							),
							'generate_background_settings' => array(
								// Background.
								'body_image'      => get_stylesheet_directory_uri() . '/frontend/images/background.jpg',
								'body_size'       => 'cover',
								'body_attachment' => 'fixed',
							),
							// Typography.
							'font_body_variants'           => array( '600', '600italic', '700', '700italic' ), // This needs to be consistent across all places with the same font.
							'font_site_title_variants'     => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
							'font_site_tagline_variants'   => array( 'regular', '900' ), // This needs to be consistent across all places with the same font.
						)
					),
					'green' => new Preset(
						__( 'Green', 'crdm-modern' ),
						'presets/blue.png',
						array(
							'crdm_modern'                  => array(
								'header_image'                     => get_stylesheet_directory_uri() . '/frontend/images/tee-pee.png',
								'header_image_height'              => '220',
								'header_image_top'                 => '13',
								'header_image_right'               => '100',
								'header_image_min_width'           => '950',
								'primary_navigation_spacing'       => '40',
								'primary_navigation_shadow'        => '2px 4px 5px rgba(0, 0, 0, 0.4)',
								'sidebar_widget_separator_width'   => '1',
								'sidebar_widget_separator_spacing' => '20',
								'sidebar_widget_separator_color'   => '#009b67',
								'excerpt_border_color'             => '#009b67',
								'excerpt_hover_background_color'   => '#009b67',
								'excerpt_hover_text_color'         => '#ffffff',
								// Featured posts.
								'featured_post_count'              => '2',
								// Read more link.
								'read_more_color'                  => '#009b67',
								'read_more_hover_color'            => '#ffffff',
								// Blog typography.
								'blog_font_size'                   => 15,
							),
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
								'link_color'                             => '#009b67',
								'site_title_color'                       => '#004f35',
								'site_tagline_color'                     => '#004f35',
								'navigation_background_color'            => '#009b67',
								'navigation_background_hover_color'      => '#004f35',
								'navigation_background_current_color'    => '#004f35',
								'subnavigation_background_color'         => '#004f35',
								'subnavigation_background_hover_color'   => '#009b67',
								'subnavigation_background_current_color' => '#009b67',
								'sidebar_widget_title_color'             => '#009b67',
								'sidebar_widget_link_color'              => '#3a3a3a',
								'sidebar_widget_link_hover_color'        => '#009b67',
								'blog_post_title_hover_color'            => '#ffffff',
								'entry_meta_link_color'                  => '#004f35',
								'entry_meta_link_color_hover'            => '#ffffff',
								'footer_widget_background_color'         => '#009b67',
								'footer_widget_text_color'               => '#ffffff',
								'footer_widget_link_color'               => '#ffffff',
								// Typography.
								'font_body'                              => 'Source Sans Pro',
								'body_font_weight'                       => '600',
								'body_font_size'                         => '15',
								'font_site_title'                        => 'Raleway',
								'site_title_font_weight'                 => '900',
								'font_site_tagline'                      => 'Raleway',
								'site_tagline_font_weight'               => '400',
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
								'menu_item'                 => '25',
								'content_element_separator' => '0.5',
							),
							'generate_blog_settings'       => array(
								// Layout.
								'excerpt_length'             => '20',
								'read_more'                  => __( 'Read more >', 'crdm-modern' ),
								'author'                     => false,
								'categories'                 => false,
								'tags'                       => false,
								'comments'                   => false,
								'single_categories'          => false,
								'single_tags'                => false,
								'single_post_navigation'     => false,
								'post_image_position'        => 'post-image-above-header',
								'post_image_alignment'       => 'post-image-aligned-left',
								'post_image_width'           => '300',
								'post_image_height'          => '200',
								'single_post_image_position' => 'below-title',
								'page_post_image_position'   => 'below-title',
							),
							'generate_background_settings' => array(
								// Background.
								'body_image'      => get_stylesheet_directory_uri() . '/frontend/images/background.jpg',
								'body_size'       => 'cover',
								'body_attachment' => 'fixed',
							),
							// Typography.
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
