<?php
/**
 * Contains the Preset_Customize_Control class.
 *
 * @package crdm-modern
 */

declare( strict_types = 1 );

namespace CrdmModern\Admin\Customizer\Controls;

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * A WP_Customize_Control for choosing a preset.
 *
 * Allows for choosing a preset for the whole page.
 */
class Preset_Customize_Control extends \WP_Customize_Control {
	/**
	 * Control's type
	 *
	 * @var string
	 *
	 * @inheritDoc
	 */
	public $type = 'crdm_modern_preset';

	/**
	 * Available presets
	 *
	 * @var array
	 */
	private $presets;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @since 3.4.0
	 *
	 * @param \WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string                $id      Control ID.
	 * @param array                 $args    {
	 *     Optional. Arguments to override class property defaults.
	 *
	 *     @type int                  $instance_number Order in which this instance was created in relation
	 *                                                 to other instances.
	 *     @type WP_Customize_Manager $manager         Customizer bootstrap instance.
	 *     @type string               $id              Control ID.
	 *     @type array                $settings        All settings tied to the control. If undefined, `$id` will
	 *                                                 be used.
	 *     @type string               $setting         The primary setting for the control (if there is one).
	 *                                                 Default 'default'.
	 *     @type int                  $priority        Order priority to load the control. Default 10.
	 *     @type string               $section         Section the control belongs to. Default empty.
	 *     @type string               $label           Label for the control. Default empty.
	 *     @type string               $description     Description for the control. Default empty.
	 *     @type array                $choices         List of choices for 'radio' or 'select' type controls, where
	 *                                                 values are the keys, and labels are the values.
	 *                                                 Default empty array.
	 *     @type array                $input_attrs     List of custom input attributes for control output, where
	 *                                                 attribute names are the keys and values are the values. Not
	 *                                                 used for 'checkbox', 'radio', 'select', 'textarea', or
	 *                                                 'dropdown-pages' control types. Default empty array.
	 *     @type array                $json            Deprecated. Use WP_Customize_Control::json() instead.
	 *     @type string               $type            Control type. Core controls include 'text', 'checkbox',
	 *                                                 'textarea', 'radio', 'select', and 'dropdown-pages'. Additional
	 *                                                 input types such as 'email', 'url', 'number', 'hidden', and
	 *                                                 'date' are supported implicitly. Default 'text'.
	 * }
	 * @param array                 $presets The available presets. Default [].
	 *
	 * @inheritDoc
	 *
	 * @SuppressWarnings(PHPMD.ShortVariable)
	 */
	public function __construct( \WP_Customize_Manager $manager, string $id, array $args, array $presets = array() ) {
		parent::__construct( $manager, $id, $args );
		$this->presets = $presets;
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @inheritDoc
	 */
	public function enqueue() {
		\CrdmModern\enqueue_script( 'crdm_modern_preset_customize_control', 'admin/js/preset_customize_control.min.js', array( 'jquery', 'customize-preview' ) );
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Control::to_json().
	 *
	 * @inheritDoc
	 */
	protected function content_template() {
		?>
<b>Hello</b>
		<?php
	}
}
