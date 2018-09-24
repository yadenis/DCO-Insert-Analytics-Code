<?php
/**
 * Basic functions: DCO_IAC_Base class
 * 
 * @package DCOIAC
 * @author Denis Yanchevskiy
 * @copyright 2016-2018
 * @license GPLv2+
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Class with basic functions
 * 
 * @since 1.0.0
 */
class DCO_IAC_Base {

	/**
	 * Array of plugin options
	 *
	 * @since 1.0.0
	 * @var array
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
	 * Initialize basic hooks
	 * 
	 * @since 1.1.2
	 */
	public function init_hooks() {
		if ( ! apply_filters( 'dco_iac_disable_do_shortcode', false ) ) {
			add_filter( 'dco_iac_insert_before_head', 'do_shortcode' );
			add_filter( 'dco_iac_insert_before_body', 'do_shortcode' );
			add_filter( 'dco_iac_insert_after_body', 'do_shortcode' );
		}
	}

	/**
	 * Set plugin options to the `$options` property from the database
	 * 
	 * @since 1.0.0
	 */
	public function set_options() {
		$default = array(
			'before_head'      => '',
			'before_head_show' => '',
			'after_body'       => '',
			'after_body_show'  => '',
			'before_body'      => '',
			'before_body_show' => '',
		);

		$options = get_option( 'dco_iac' );
		if ( is_array( $options ) ) {
			$options = array_map( 'trim', $options );
		}

		$this->options = apply_filters( 'dco_iac_get_options', wp_parse_args( $options, $default ), $options, $default );
	}

	/**
	 * Get plugin options
	 * 
	 * @since 1.2.0
	 * 
	 * @return array Array of plugin options
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Get plugin option
	 * 
	 * @since 1.2.0
	 * 
	 * @param string $name Option name.
	 * @return mixed|false Return the value of the option if it is found, false if the option does not exist.
	 */
	public function get_option( $name ) {
		if ( isset( $this->options[ $name ] ) ) {
			return $this->options[ $name ];
		}

		return false;
	}

}
