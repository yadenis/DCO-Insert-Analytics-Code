<?php

class BaseTest extends WP_UnitTestCase {

	/*
	 * Test `set_options` method
	 */

	public function test_set_options() {
		// Default
		$default = array(
			'before_head' => '',
			'before_head_show' => '',
			'after_body' => '',
			'after_body_show' => '',
			'before_body' => '',
			'before_body_show' => ''
		);

		$GLOBALS['dco_iac']->set_options();

		$this->assertEquals( $default, $GLOBALS['dco_iac']->get_options(), 'Set default options failed' );

		// Read options from DB
		$example_options = array(
			'before_head' => 'before_head_test',
			'before_head_show' => '0',
			'after_body' => ' ',
			'after_body_show' => '',
			'before_body' => '',
			'before_body_show' => ''
		);

		update_option( 'dco_iac', $example_options );

		$GLOBALS['dco_iac']->set_options();
		
		// `set_options` method should trim values from DB
		$example_options['after_body'] = trim($example_options['after_body']);

		$this->assertEquals( $example_options, $GLOBALS['dco_iac']->get_options(), 'Read options from DB failed' );
	}
	
	/*
	 * Test `get_option` method
	 */

	public function test_get_option() {
		$example_options = array(
			'before_head' => 'before_head_test',
			'before_head_show' => '0',
			'after_body' => ' ',
			'after_body_show' => '',
			'before_body' => '',
			'before_body_show' => ''
		);

		update_option( 'dco_iac', $example_options );

		$GLOBALS['dco_iac']->set_options();
		
		// Test for option with right name
		$this->assertEquals($example_options['before_head'], $GLOBALS['dco_iac']->get_option('before_head'), 'get_option method get wrong value');
		
		// Test for option with wrong name
		$this->assertFalse($GLOBALS['dco_iac']->get_option('before_head1'), 'get_option dont return false for option with wrong name');
	}

	/*
	 * Test `dco_iac_get_options` filter
	 */

	public function test_dco_iac_get_options() {
		add_filter( 'dco_iac_get_options', array($this, 'custom_dco_iac_get_options'), 10, 3 );

		$GLOBALS['dco_iac']->set_options();

		$example_options = array(
			'before_head' => '<!-- before </head> -->',
			'before_head_show' => '0',
			'after_body' => '<!-- after <body> -->',
			'after_body_show' => '1',
			'before_body' => '<!-- before </body> -->',
			'before_body_show' => '2'
		);

		$this->assertEquals( $example_options, $GLOBALS['dco_iac']->get_options(), '`dco_iac_get_options` filter failed' );
	}

	function custom_dco_iac_get_options( $current, $options, $default ) {
		$array = array(
			'before_head' => '<!-- before </head> -->',
			'before_head_show' => '0',
			'after_body' => '<!-- after <body> -->',
			'after_body_show' => '1',
			'before_body' => '<!-- before </body> -->',
			'before_body_show' => '2'
		);

		return $array;
	}

	/*
	 * Test `dco_iac_disable_do_shortcode` filter
	 */

	public function test_dco_iac_disable_do_shortcode() {
		// Default
		$this->assertNotFalse( has_filter( 'dco_iac_insert_before_head', 'do_shortcode' ), 'Set default function `do_shortcode` for `dco_iac_insert_before_head` filter failed' );
		$this->assertNotFalse( has_filter( 'dco_iac_insert_before_body', 'do_shortcode' ), 'Set default function `do_shortcode` for `dco_iac_insert_before_body` filter failed' );
		$this->assertNotFalse( has_filter( 'dco_iac_insert_after_body', 'do_shortcode' ), 'Set default function `do_shortcode` for `dco_iac_insert_after_body` filter failed' );

		// reset filters
		remove_filter( 'dco_iac_insert_before_head', 'do_shortcode' );
		remove_filter( 'dco_iac_insert_before_body', 'do_shortcode' );
		remove_filter( 'dco_iac_insert_after_body', 'do_shortcode' );

		// With filter
		add_filter( 'dco_iac_disable_do_shortcode', '__return_true' );

		$obj = new DCO_IAC_Base();
		$obj->init_hooks();

		$this->assertFalse( has_filter( 'dco_iac_insert_before_head', 'do_shortcode' ), 'Disable default function `do_shortcode` for `dco_iac_insert_before_head` filter with `dco_iac_disable_do_shortcode` filter failed' );
		$this->assertFalse( has_filter( 'dco_iac_insert_before_body', 'do_shortcode' ), 'Disable default function `do_shortcode` for `dco_iac_insert_before_body` filter with `dco_iac_disable_do_shortcode` filter failed' );
		$this->assertFalse( has_filter( 'dco_iac_insert_after_body', 'do_shortcode' ), 'Disable default function `do_shortcode` for `dco_iac_insert_after_body` filter with `dco_iac_disable_do_shortcode` filter failed' );
	}

}
