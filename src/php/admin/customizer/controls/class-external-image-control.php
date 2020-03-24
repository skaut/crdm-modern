<?php
/**
 * Contains the Fixed_Image_Control class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Controls;

if ( ! class_exists( 'WP_Customize_Image_Control' ) ) {
	return;
}

/**
 * A WordPress Customizer control like WP_Customize_Image_Control, but also displaying images that are not in WordPress Media.
 */
class External_Image_Control extends \WP_Customize_Image_Control {
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		if ( isset( $this->json['attachment'] ) ) {
			return;
		}
		$value = $this->value();
		if ( empty( $value ) ) {
			return;
		}
		$type                     = in_array( substr( $value, -3 ), array( 'jpg', 'png', 'gif', 'bmp' ), true ) ? 'image' : 'document';
		$this->json['attachment'] = array(
			'id'    => 1,
			'url'   => $value,
			'type'  => $type,
			'icon'  => wp_mime_type_icon( $type ),
			'title' => wp_basename( $value ),
		);
		if ( 'image' === $type ) {
			$this->json['attachment']['sizes'] = array(
				'full' => array( 'url' => $value ),
			);
		}
	}
}
