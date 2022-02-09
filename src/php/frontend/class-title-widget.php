<?php
/**
 * Contains the Title_Widget class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Frontend;

/**
 * A widget containing the site logo, title and tagline.
 */
class Title_Widget extends \WP_Widget {
	/**
	 * Registers the widget with WordPress.
	 *
	 * @return void
	 */
	public static function register() {
		register_widget( '\\CrdmModern\\Frontend\\Title_Widget' );
		add_action( 'wp_enqueue_scripts', array( '\\CrdmModern\\Frontend\\Title_Widget', 'enqueue' ), 105 );
	}

	/**
	 * Enqueues all the styles for the widget.
	 *
	 * @return void
	 */
	public static function enqueue() {
		$css = new \GeneratePress_Pro_CSS();

		$preset      = \CrdmModern\Admin\Customizer\Preset_Registry::get_instance()->default_preset();
		$gp_settings = $preset->get_current_values( 'generate_settings' );

		$site_title_family   = generate_get_font_family_css( 'font_site_title', 'generate_settings', generate_get_default_fonts() );
		$site_tagline_family = generate_get_font_family_css( 'font_site_tagline', 'generate_settings', generate_get_default_fonts() );

		$css->set_selector( '.crdm-modern-title-widget-image' );
		$css->add_property( 'width', strval( absint( $gp_settings['logo_width'] ) ), false, 'px' );

		$css->set_selector( '.crdm-modern-title-widget-title' );
		$css->add_property( 'margin-left', strval( absint( $gp_settings['logo_width'] ) + 20 ), false, 'px' );
		$css->set_selector( '.crdm-modern-title-widget-tagline' );
		$css->add_property( 'margin-left', strval( absint( $gp_settings['logo_width'] ) + 20 ), false, 'px' );

		$css->set_selector( '.crdm-modern-title-widget-title' );
		$css->add_property( 'font-family', 'inherit' !== $gp_settings['font_site_title'] ? $site_title_family : null );
		$css->add_property( 'font-size', strval( absint( $gp_settings['site_title_font_size'] ) ), false, 'px' );
		$css->add_property( 'font-weight', strval( absint( $gp_settings['site_title_font_weight'] ) ), false );

		$css->set_selector( '.crdm-modern-title-widget-tagline' );
		$css->add_property( 'font-family', 'inherit' !== $gp_settings['font_site_tagline'] ? $site_tagline_family : null );
		$css->add_property( 'font-size', strval( absint( $gp_settings['site_tagline_font_size'] ) ), false, 'px' );
		$css->add_property( 'font-weight', strval( absint( $gp_settings['site_tagline_font_weight'] ) ), false );

		$output = $css->css_output();
		if ( '' !== $output ) {
			wp_add_inline_style( 'crdm_modern_inline', $output );
		}
	}

	/**
	 * Title_Widget class constructor
	 */
	public function __construct() {
		parent::__construct( 'crdm_modern_title_widget', __( 'Title Widget', 'crdm-modern' ), array( 'description' => __( 'A widget containing the site logo, title and tagline.', 'crdm-modern' ) ) );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @param array<string, string> $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array<mixed, mixed>   $instance @unused-param Unused.
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.ShortVariable)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function widget( $args, $instance ) {
		$gp_settings = wp_parse_args(
			get_option( 'generate_settings', array() ),
			generate_get_defaults()
		);
		$title       = get_option( 'blogname', '' );
		$tagline     = get_option( 'blogdescription', '' );

		$logo_url = null;
		if ( function_exists( 'the_custom_logo' ) && null !== get_theme_mod( 'custom_logo' ) ) {
			$logo_url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		}
		if ( isset( $logo_url ) ) {
			$logo_url = $logo_url[0];
		} else {
			$logo_url = $gp_settings['logo'];
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo( $args['before_widget'] );
		echo( '<img class="crdm-modern-title-widget-image" src="' );
		echo( esc_url( $logo_url ) );
		echo( '">' );
		echo( '<div class="crdm-modern-title-widget-text">' );
		echo( '<div class="crdm-modern-title-widget-title">' );
		echo( esc_html( $title ) );
		echo( '</div>' );
		echo( '<div class="crdm-modern-title-widget-tagline">' );
		echo( esc_html( $tagline ) );
		echo( '</div></div>' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo( $args['after_widget'] );
	}
}
