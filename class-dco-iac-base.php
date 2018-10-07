<?php
/**
 * Basic functions: DCO_IAC_Base class
 *
 * @package DCO_Insert_Analytics_Code
 * @author Denis Yanchevskiy
 * @copyright 2016-2018
 * @license GPLv2+
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Class with basic functions.
 *
 * @since 1.0.0
 */
class DCO_IAC_Base {

	/**
	 * An array of plugin options.
	 *
	 * @since 1.0.0
	 * @var array $options Plugin options.
	 */
	private $options = array();

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->set_options();
	}

	/**
	 * Initializes filters for shortcode processing in insert codes.
	 *
	 * @since 1.1.2
	 */
	public function init_hooks() {
		/**
		 * Filters enable or disable shortcode processing in insert codes.
		 *
		 * @since 1.1.2
		 *
		 * @param bool $disable Disables shortcode processing if true. Default false.
		 */
		if ( ! apply_filters( 'dco_iac_disable_do_shortcode', false ) ) {
			add_filter( 'dco_iac_insert_before_head', 'do_shortcode' );
			add_filter( 'dco_iac_insert_before_body', 'do_shortcode' );
			add_filter( 'dco_iac_insert_after_body', 'do_shortcode' );
		}
	}

	/**
	 * Sets plugin options to the `$options` property from the database.
	 *
	 * @since 1.0.0
	 */
	public function set_options() {
		$default = array(
			'before_head'      => '',
			'before_head_show' => '0',
			'after_body'       => '',
			'after_body_show'  => '0',
			'before_body'      => '',
			'before_body_show' => '0',
		);

		$options = get_option( 'dco_iac' );
		if ( is_array( $options ) ) {
			// Clears empty typos.
			$options = array_map( 'trim', $options );
		}

		/**
		 * Filters plugin options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $current Current plugin options.
		 * @param array $options Plugin options from database.
		 * @param array $default Default plugin options.
		 */
		$this->options = apply_filters( 'dco_iac_get_options', wp_parse_args( $options, $default ), $options, $default );
	}

	/**
	 * Gets all plugin options.
	 *
	 * @since 1.2.0
	 *
	 * @return array $options An array of plugin options.
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Gets the plugin option by the name.
	 *
	 * @since 1.2.0
	 *
	 * @param string $name The option name.
	 * @return mixed|false Returns the value of the option if it is found, false if the option does not exist.
	 */
	public function get_option( $name ) {
		if ( isset( $this->options[ $name ] ) ) {
			return $this->options[ $name ];
		}

		return false;
	}

}
