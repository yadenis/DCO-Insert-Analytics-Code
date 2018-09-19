<?php

defined( 'ABSPATH' ) or die;

class DCO_IAC_Base {

	protected $options = array();

	protected function init_hooks() {
		$this->get_options();

		if ( ! apply_filters( 'dco_iac_disable_do_shortcode', false ) ) {
			add_filter( 'dco_iac_insert_before_head', 'do_shortcode' );
			add_filter( 'dco_iac_insert_before_body', 'do_shortcode' );
			add_filter( 'dco_iac_insert_after_body', 'do_shortcode' );
		}
	}

	protected function get_options() {
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

}
