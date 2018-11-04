<?php

class IACTest extends WP_UnitTestCase {
	
	/*
	 * Test `check_show` method
	 */

	public function test_check_show() {
		// set example data for tests
		$data = array(
			'before_head'		 => '<!-- before </head> -->',
			'before_head_show'	 => '0',
			'after_body'		 => '<!-- after <body> -->',
			'after_body_show'	 => '1',
			'before_body'		 => '<!-- before </body> -->',
			'before_body_show'	 => '2'
		);

		update_option( 'dco_iac', $data );

		$GLOBALS[ 'dco_iac' ]->set_options();

		// Test for not logged user
		$this->assertTrue( $GLOBALS[ 'dco_iac' ]->check_show( 'before_head' ), '`All Users` dont show for not logged users' );
		$this->assertTrue( $GLOBALS[ 'dco_iac' ]->check_show( 'after_body' ), '`Not Logged Users` dont show for not logged users' );
		$this->assertFalse( $GLOBALS[ 'dco_iac' ]->check_show( 'before_body' ), '`Logged Users` show for not logged users' );

		// Test for logged user
		wp_set_current_user( 1 );
		$this->assertTrue( $GLOBALS[ 'dco_iac' ]->check_show( 'before_head' ), '`All Users` dont show for not logged users' );
		$this->assertFalse( $GLOBALS[ 'dco_iac' ]->check_show( 'after_body' ), '`Not Logged Users` dont show for not logged users' );
		$this->assertTrue( $GLOBALS[ 'dco_iac' ]->check_show( 'before_body' ), '`Logged Users` show for not logged users' );

		// Test `Show for Nobody`
		$data[ 'before_head_show' ] = 3;
		update_option( 'dco_iac', $data );
		$GLOBALS[ 'dco_iac' ]->set_options();

		// Test for logged user
		$this->assertFalse( $GLOBALS[ 'dco_iac' ]->check_show( 'before_head' ), '`Nobody` show for logged users' );

		// Test for not logged user
		wp_set_current_user( 0 );
		$this->assertFalse( $GLOBALS[ 'dco_iac' ]->check_show( 'before_head' ), '`Nobody` show for not logged users' );
	}

	/*
	 * Test all insert codes
	 */
	
	public function test_insert_codes() {
		// set empty data for tests
		$data = array(
			'before_head'		 => '',
			'before_head_show'	 => '0',
			'after_body'		 => '',
			'after_body_show'	 => '0',
			'before_body'		 => '',
			'before_body_show'	 => '0'
		);

		update_option( 'dco_iac', $data );

		$GLOBALS[ 'dco_iac' ]->set_options();

		$this->assertFalse( has_action( 'wp_head', array($GLOBALS[ 'dco_iac' ], 'insert_before_head') ), '`insert_before_head` shows empty value' );
		$this->assertFalse( has_action( 'template_include', array($GLOBALS[ 'dco_iac' ], 'insert_after_body') ), '`insert_after_body` shows empty value' );
		$this->assertFalse( has_action( 'wp_footer', array($GLOBALS[ 'dco_iac' ], 'insert_before_body') ), '`insert_before_body` shows empty value' );

		// set example data for tests
		$data = array(
			'before_head'		 => 'before_head_test',
			'before_head_show'	 => '0',
			'after_body'		 => 'after_body_test',
			'after_body_show'	 => '0',
			'before_body'		 => 'before_body_test',
			'before_body_show'	 => '0'
		);

		update_option( 'dco_iac', $data );

		$GLOBALS[ 'dco_iac' ]->set_options();
		$GLOBALS[ 'dco_iac' ]->init_hooks();
		
		// check code in head
		ob_start();
		wp_head();
		$this->assertContains( 'before_head_test', ob_get_clean(), '`insert_before_head` shows a wrong value' );
		
		// check code in head with `dco_iac_insert_before_head` filter
		add_filter( 'dco_iac_insert_before_head', array( $this, 'custom_dco_iac_insert_before_head' ) );
		ob_start();
		wp_head();
		$this->assertContains( 'custom_header_code', ob_get_clean(), '`dco_iac_insert_before_head` filter doesnt work' );
		
		// check code in footer
		ob_start();
		wp_footer();
		$this->assertContains( 'before_body_test', ob_get_clean(), '`insert_before_body` shows a wrong value' );
		
		// check code in footer with `dco_iac_insert_before_body` filter
		add_filter( 'dco_iac_insert_before_body', array( $this, 'custom_dco_iac_insert_before_body' ) );
		ob_start();
		wp_footer();
		$this->assertContains( 'custom_footer_code', ob_get_clean(), '`dco_iac_insert_before_body` filter doesnt work' );
		
		// check that `template_include` filter has `insert_after_body` function
		$this->assertEquals( 99, has_filter( 'template_include', array($GLOBALS[ 'dco_iac' ], 'insert_after_body') ), '`insert_after_body` function doesnt attach to `template_include` filter' );
	}

	/*
	 * Test regular expression for `insert_after_body` code
	 */
	public function test_insert_after_body_regexp() {
		$test_html = "<html><head><meta charset='UTF-8'><title>Test page</title></head><body>Page content</body></html>";
		
		// set example data for tests
		$data = array(
			'before_head'		 => 'before_head_test',
			'before_head_show'	 => '0',
			'after_body'		 => 'after_body_test',
			'after_body_show'	 => '0',
			'before_body'		 => 'before_body_test',
			'before_body_show'	 => '0'
		);

		update_option( 'dco_iac', $data );
		
		$GLOBALS[ 'dco_iac' ]->set_options();
		
		$processing_html = $GLOBALS[ 'dco_iac' ]->insert_after_body_code( $test_html );
		
		$this->assertContains( "<body>\nafter_body_test", $processing_html );
		
		// check with `dco_iac_insert_after_body` filter
		add_filter( 'dco_iac_insert_after_body', array( $this, 'custom_dco_iac_insert_after_body' ) );
		$processing_html = $GLOBALS[ 'dco_iac' ]->insert_after_body_code( $test_html );
		$this->assertContains( "<body>\ncustom_body_code", $processing_html, '`dco_iac_insert_after_body` filter doesnt work' );
	}
	
	public function custom_dco_iac_insert_before_head( $code ) {
		return 'custom_header_code';
	}
	
	public function custom_dco_iac_insert_before_body( $code ) {
		return 'custom_footer_code';
	}
	
	public function custom_dco_iac_insert_after_body( $code ) {
		return "\n" . 'custom_body_code';
	}
}
