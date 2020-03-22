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

class Fixed_Image_Control extends \WP_Customize_Image_Control {
    public function __construct($manager, $id, $args = array()) {
	parent::__construct($manager, $id, $args);
    }

    public function to_json() {
	parent::to_json();

	if ( isset( $this->json['attachment'] ) ) {
	    return;
	}
	$value = $this->value();
	if ( ! $value ) {
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
