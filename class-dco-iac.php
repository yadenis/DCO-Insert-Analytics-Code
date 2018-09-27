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
	 * @param string $option_name The option name.
	 * @return bool $show Returns true if the code needs to be displayed or false, if not.
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

		$before_head = $this->get_option( 'before_head' );
		if ( ! empty( $before_head ) && $this->check_show( 'before_head' ) ) {
			add_action( 'wp_head', array( $this, 'insert_before_head' ), 99 );
		}

		$after_body = $this->get_option( 'after_body' );
		if ( ! empty( $after_body ) && $this->check_show( 'after_body' ) ) {
			add_filter( 'template_include', array( $this, 'insert_after_body' ), 99 );
		}

		$before_body = $this->get_option( 'before_body' );
		if ( ! empty( $before_body ) && $this->check_show( 'before_body' ) ) {
			add_action( 'wp_footer', array( $this, 'insert_before_body' ), 99 );
		}
	}

	/**
	 * Inserts the code before the head tag.
	 *
	 * @since 1.0.0
	 */
	public function insert_before_head() {
		/**
		 * Filters the `before_head` code output.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code The `before_head` code output.
		 */
		// phpcs:disable
		echo apply_filters( 'dco_iac_insert_before_head', $this->get_option( 'before_head' ) . "\n" );
		// phpcs:enable
	}

	/**
	 * Inserts the code after the body tag.
	 *
	 * @since 1.0.0
	 *
	 * {@see 'template_include'}
	 *
	 * @param string $template The path of the template to include.
	 * @return string $template The path of the template to include.
	 */
	public function insert_after_body( $template ) {
		ob_start( array( $this, 'insert_after_body_code' ) );

		return $template;
	}

	/**
	 * Inserts the code before the body tag.
	 *
	 * @since 1.0.0
	 */
	public function insert_before_body() {
		/**
		 * Filters the `before_body` code output.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code The `before_body` code output.
		 */
		// phpcs:disable
		echo apply_filters( 'dco_iac_insert_before_body', $this->get_option( 'before_body' ) . "\n" );
		// phpcs:enable
	}

	/**
	 * A helper function for the `insert_after_body` function (callback for ob_start).
	 *
	 * @see DCO_IAC::insert_after_body()
	 *
	 * @since 1.0.0
	 *
	 * @param string $buffer The output template html.
	 * @return string $buffer Outputs the template html with the inserted code.
	 */
	public function insert_after_body_code( $buffer ) {
		/**
		 * Filters the `after_body` code output.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code The `after_body` code output.
		 */
		$buffer = preg_replace( '@<body[^>]*>@', '$0' . apply_filters( 'dco_iac_insert_after_body', "\n" . $this->get_option( 'after_body' ) ), $buffer );

		return $buffer;
	}

}

$GLOBALS['dco_iac'] = new DCO_IAC();
