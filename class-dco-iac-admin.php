<?php
/**
 * Admin functions: DCO_IAC_Admin class
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
 * Class with admin functions.
 *
 * @since 1.0.0
 *
 * @see DCO_IAC_Base
 */
class DCO_IAC_Admin extends DCO_IAC_Base {

	/**
	 * An array of plugin settings sections.
	 *
	 * @since 1.1.0
	 * @var array $sections Plugin settings sections.
	 */
	protected $sections;

	/**
	 * Provides the html-markup of settings fields.
	 *
	 * A magic method for the `add_settings_field` callback.
	 *
	 * @see DCO_IAC_Admin::register_settings()
	 *
	 * @param string $name The name of the method being called.
	 * @param array  $arguments An enumerated array containing the parameters passed to the $name'ed method.
	 * @return boolean|void Returns false if provided an incorrect field name.
	 */
	public function __call( $name, $arguments ) {
		$name_array = explode( '_', $name );
		if ( count( $name_array ) < 3 ) {
			return false;
		}

		$option_name = $name_array[0] . '_' . $name_array[1];
		if ( 'render' === $name_array[2] ) {
			?>
			<textarea rows="10" style="width:100%;" name="dco_iac[<?php echo esc_attr( $option_name ); ?>]" <?php disabled( has_filter( 'dco_iac_get_options' ) ); // Disable the setting field if settings have been overridden programmatically. ?>><?php echo esc_textarea( $this->get_option( $option_name ) ); ?></textarea>
			<?php
		}

		if ( 'show' === $name_array[2] ) {
			?>
			<select name="dco_iac[<?php echo esc_attr( $option_name ); ?>_show]" <?php disabled( has_filter( 'dco_iac_get_options' ) ); // Disable the setting field if settings have been overridden programmatically. ?>>
				<option value="0" <?php selected( $this->get_option( $option_name . '_show' ), '0' ); ?>><?php esc_html_e( 'All Users', 'dco-insert-analytics-code' ); ?></option>
				<option value="1" <?php selected( $this->get_option( $option_name . '_show' ), '1' ); ?>><?php esc_html_e( 'Not Logged Users', 'dco-insert-analytics-code' ); ?></option>
				<option value="2" <?php selected( $this->get_option( $option_name . '_show' ), '2' ); ?>><?php esc_html_e( 'Logged Users', 'dco-insert-analytics-code' ); ?></option>
				<option value="3" <?php selected( $this->get_option( $option_name . '_show' ), '3' ); ?>><?php esc_html_e( 'Nobody', 'dco-insert-analytics-code' ); ?></option>
			</select>
			<?php
		}
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->sections = array(
			/* translators: Before </head> */
			'before_head' => __( 'Before', 'dco-insert-analytics-code' ) . ' ' . esc_html( '</head>' ),
			/* translators: After <body> */
			'after_body'  => __( 'After', 'dco-insert-analytics-code' ) . ' ' . esc_html( '<body>' ),
			/* translators: Before </body> */
			'before_body' => __( 'Before', 'dco-insert-analytics-code' ) . ' ' . esc_html( '</body>' ),
		);

		add_action( 'init', array( $this, 'init_hooks' ) );
	}

	/**
	 * Initializes admin hooks for:
	 * - registering plugin settings fields
	 * - adding plugin settings page to the admin menu
	 * - adding additional actions to the plugin on the plugins page in the admin panel
	 *
	 * @since 1.0.0
	 */
	public function init_hooks() {
		parent::init_hooks();

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'create_menu' ) );

		add_filter( 'plugin_action_links_' . DCO_IAC__PLUGIN_BASENAME, array( $this, 'register_plugin_links' ) );
	}

	/**
	 * Adds additional actions for the plugin on the plugins page in the admin panel.
	 *
	 * @param array $actions An array of plugin action links.
	 * @return array $actions An array of plugin action links with additional plugin actions.
	 */
	public function register_plugin_links( $actions ) {
		array_unshift(
			$actions,
			sprintf(
				'<a href="%1$s" title="%2$s">%3$s</a>',
				self_admin_url( 'options-general.php?page=dco-insert-analytics-code' ),
				esc_attr__( 'Manage your codes', 'dco-insert-analytics-code' ),
				esc_html__( 'Settings', 'dco-insert-analytics-code' )
			)
		);

		return $actions;
	}

	/**
	 * Adds an options page to the settings section in the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function create_menu() {
		add_options_page( __( 'DCO Insert Analytics Code', 'dco-insert-analytics-code' ), __( 'DCO Insert Analytics Code', 'dco-insert-analytics-code' ), 'manage_options', 'dco-insert-analytics-code', array( $this, 'render' ) );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		register_setting( 'dco_iac', 'dco_iac' );

		foreach ( $this->sections as $key => $title ) {
			$section_key = 'dco_iac_' . $key;

			add_settings_section(
				$section_key,
				$title,
				'',
				'dco_iac'
			);

			add_settings_field(
				$key,
				__( 'Code', 'dco-insert-analytics-code' ),
				array( $this, $key . '_render' ),
				'dco_iac',
				$section_key
			);

			add_settings_field(
				$key . '_show',
				__( 'Show Code', 'dco-insert-analytics-code' ),
				array( $this, $key . '_show_render' ),
				'dco_iac',
				$section_key
			);
		}
	}

	/**
	 * Outputs the plugin settings page markup.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'DCO Insert Analytics Code', 'dco-insert-analytics-code' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'dco_iac' );
				do_settings_sections( 'dco_iac' );
				submit_button( null, 'primary', 'submit', true, disabled( has_filter( 'dco_iac_get_options' ) /* Disable the setting field if settings have been overridden programmatically. */, true, false ) );
				?>
			</form>
		</div>
		<?php
	}

}

$GLOBALS['dco_iac_admin'] = new DCO_IAC_Admin();
