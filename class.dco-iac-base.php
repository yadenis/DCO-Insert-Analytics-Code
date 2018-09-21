<?php

defined( 'ABSPATH' ) or die;

class DCO_IAC_Base {

	private $options = array();

	public function __construct() {
		$this->set_options();
	}

	public function init_hooks() {
		if ( ! apply_filters( 'dco_iac_disable_do_shortcode', false ) ) {
			add_filter( 'dco_iac_insert_before_head', 'do_shortcode' );
			add_filter( 'dco_iac_insert_before_body', 'do_shortcode' );
			add_filter( 'dco_iac_insert_after_body', 'do_shortcode' );
		}
	}

	public function set_options() {
		$default = array(
			'before_head' => '',
			'before_head_show' => '',
			'after_body' => '',
			'after_body_show' => '',
			'before_body' => '',
			'before_body_show' => ''
		);

		$options = get_option( 'dco_iac' );
		if ( is_array( $options ) ) {
			$options = array_map( 'trim', $options );
		}

		$this->options = apply_filters( 'dco_iac_get_options', wp_parse_args( $options, $default ), $options, $default );
	}

	public function get_options() {
		return $this->options;
	}

	public function get_option( $name ) {
		if ( isset( $this->options[$name] ) ) {
			return $this->options[$name];
		}

		return false;
	}

}
