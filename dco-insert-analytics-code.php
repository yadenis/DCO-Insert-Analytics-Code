<?php
/**
 * DCO Insert Analytics Code
 *
 * @package DCO_Insert_Analytics_Code
 * @author Denis Yanchevskiy
 * @copyright 2016-2018
 * @license GPLv2+
 *
 * @since 1.0.0
 *
 * Plugin Name: DCO Insert Analytics Code
 * Description: Allows you to insert analytics code before &lt;/head&gt; or after &lt;body&gt; or before &lt;/body&gt;
 * Version: 1.1.3
 * Author: Denis Yanchevskiy
 * Author URI: http://denisco.pro
 * License: GPLv2 or later
 * Text Domain: dco-insert-analytics-code
 */

defined( 'ABSPATH' ) || die;

define( 'DCO_IAC__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DCO_IAC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DCO_IAC__PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once DCO_IAC__PLUGIN_DIR . 'class-dco-iac-base.php';
require_once DCO_IAC__PLUGIN_DIR . 'class-dco-iac.php';
if ( is_admin() ) {
	require_once DCO_IAC__PLUGIN_DIR . 'class-dco-iac-admin.php';
}
