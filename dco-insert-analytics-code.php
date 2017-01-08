<?php

/*
  Plugin Name: DCO insert analytics code
  Plugin URI: https://github.com/Denis-co/DCO-Insert-Analytics-Code
  Description: Allows you to insert analytics code before </head> or after <body> or before </body>
  Version: 1.1.0
  Author: Denis co.
  Author URI: http://denisco.pro
  License: GPLv2 or later
  Text Domain: dco-insert-analytics-code
  Domain Path: /languages
 */

defined('ABSPATH') or die;

define('DCO_IAC__PLUGIN_URL', plugin_dir_url(__FILE__));
define('DCO_IAC__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DCO_IAC__PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once( DCO_IAC__PLUGIN_DIR . 'class.dco-iac-base.php' );
require_once( DCO_IAC__PLUGIN_DIR . 'class.dco-iac.php' );
if (is_admin()) {
    require_once( DCO_IAC__PLUGIN_DIR . 'class.dco-iac-admin.php' );
}
