<?php
/**
 * Public functions: DCO_IAC class
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
 * Class with public functions.
 *
 * @since 1.0.0
 *
 * @see DCO_IAC_Base
 */
class DCO_IAC extends DCO_IAC_Base {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'init', array( $this, 'init_hooks' ) );
	}

	/**
	 * Checks show insert code or not by option name.
	 *
	 * @since 1.1.1
	 *
	 * @param string $option_name Option name.
	 * @return bool Return true if the code needs to be displayed or false, if not.
	 */
	public function check_show( $option_name ) {
		$user_logged = is_user_logged_in();

		/**
		 * Filters the insert code display.
		 *
		 * @since 1.1.1
		 *
		 * @param $show Specifies for which group of users the code will be displayed.
		 *              Possible values:
		 *              - 0 — show for all users
		 *              - 1 — show for not logged users
		 *              - 2 — show for logged users
		 *              - 3 — show for nobody
		 */
		$show = apply_filters( 'dco_iac_insert_' . $option_name . '_show', $this->get_option( $option_name . '_show' ) );

		switch ( $show ) {
			case '1':
				return ! $user_logged;
			case '2':
				return $user_logged;
			case '3':
				return false;
			default:
				return true;
		}
	}

	/**
	 * Initializes hooks for insert codes show.
	 *
	 * @since 1.0.0
	 */
	public function init_hooks() {
		parent::init_hooks();

		if ( ! empty( $this->get_option( 'before_head' ) ) && $this->check_show( 'before_head' ) ) {
			add_action( 'wp_head', array( $this, 'insert_before_head' ), 99 );
		}

		if ( ! empty( $this->get_option( 'after_body' ) ) && $this->check_show( 'after_body' ) ) {
			add_filter( 'template_include', array( $this, 'insert_after_body' ), 99 );
		}

		if ( ! empty( $this->get_option( 'before_body' ) ) && $this->check_show( 'before_body' ) ) {
			add_action( 'wp_footer', array( $this, 'insert_before_body' ), 99 );
		}
	}

	/**
	 * Inserts code before head tag
	 *
	 * @since 1.0.0
	 */
	public function insert_before_head() {
		/**
		 * Filters `before_head` option value
		 *
		 * @since 1.0.0
		 *
		 * @param string $code `before_head` option value
		 */
		echo apply_filters( 'dco_iac_insert_before_head', $this->get_option( 'before_head' ) . "\n" );
	}

	/**
	 * Inserts code after body tag
	 *
	 * @since 1.0.0
	 *
	 * {@see 'template_include'}
	 *
	 * @param string $template The path of the template to include.
	 * return string The path of the template to include.
	 */
	public function insert_after_body( $template ) {
		ob_start( array( $this, 'insert_after_body_code' ) );

		return $template;
	}

	/**
	 * Inserts code before body tag
	 *
	 * @since 1.0.0
	 */
	public function insert_before_body() {
		/**
		 * Filters `before_body` option value
		 *
		 * @since 1.0.0
		 *
		 * @param string $code `before_body` option value
		 */
		echo apply_filters( 'dco_iac_insert_before_body', $this->get_option( 'before_body' ) . "\n" );
	}

	/**
	 * Helper function for `insert_after_body` function (callback for ob_start)
	 *
	 * @see DCO_IAC::insert_after_body()
	 *
	 * @since 1.0.0
	 *
	 * @param string $buffer Output template html.
	 * return string Output template html with inserted code.
	 */
	public function insert_after_body_code( $buffer ) {
		/**
		 * Filters `after_body` option value
		 *
		 * @since 1.0.0
		 *
		 * @param string $code `after_body` option value
		 */
		$buffer = preg_replace( '@<body[^>]*>@', '$0' . apply_filters( 'dco_iac_insert_after_body', "\n" . $this->get_option( 'after_body' ) ), $buffer );

		return $buffer;
	}

}

$GLOBALS['dco_iac'] = new DCO_IAC();
