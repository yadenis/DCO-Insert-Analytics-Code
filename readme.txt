=== DCO Insert Analytics Code ===
Contributors: denisco
Tags: analytics, metrika, yandex metrica, google analytics
Requires at least: 4.6
Tested up to: 5.0
Requires PHP: 5.3
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to insert analytics code before &lt;/head&gt; or after &lt;body&gt; or before &lt;/body&gt;

== Description ==
DCO Insert Analytics Code is a WordPress plugin is intended for insert analytics code(or any custom code) before &lt;/head&gt; or after &lt;body&gt; or before &lt;/body&gt;

= Usage =
After installation and activation, you can insert the necessary code to the respective fields on the plugin settings page.

= Settings =
* Before &lt;/head&gt; code
* After &lt;body&gt; code
* Before &lt;/body&gt; code

= Filters list =
**dco_iac_get_options**

Filter for hardcoding override plugin settings. You won't be able to edit them on the settings page anymore when using this filter.

**dco_iac_insert_before_head**

Filter to change the code is inserted before &lt;/head&gt;

**dco_iac_insert_before_head_show**

Filter to change show the code is inserted before &lt;/head&gt;

**dco_iac_insert_after_body**

Filter to change the code is inserted after &lt;body&gt;

**dco_iac_insert_after_body_show**

Filter to change show the code is inserted after &lt;body&gt;

**dco_iac_insert_before_body**

Filter to change the code is inserted before &lt;/body&gt;

**dco_iac_insert_before_body_show**

Filter to change show the code is inserted before &lt;/body&gt;

**dco_iac_disable_do_shortcode**

Filter to disable shortcode processing in inserted codes

= Examples of using filters =
**Hardcoding override plugin settings**

	/*
	* $current - current plugin settings
	*
	* $options - plugin settings from database
	*
	* $default - default plugin settings
	*/

	function custom_get_options($current, $options, $default) {
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

	add_filter('dco_iac_get_options', 'custom_get_options', 10, 3);

**Change before &lt;/head&gt; code**

	/*
	* $code - value from "before </head>" setting
	*/

	function custom_before_head_code( $code ) {
		return $code . '<!-- before <head> -->' . "\n";
	}

	add_filter( 'dco_iac_insert_before_head', 'custom_before_head_code' );

**Change before &lt;/body&gt; code show**

	/*
	* $value - value from "before </body> show" setting
	*/

	function custom_before_head_code( $value ) {
		return '2';
	}

	add_filter( 'dco_iac_insert_before_body_show', 'custom_before_body_show' );

**Disable shortcode processing in insert codes**

	add_filter('dco_iac_disable_do_shortcode', '__return_true');


== Installation ==
1. Upload `dco-insert-analytics-code` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I put the code on the plugin settings page, but it does not appear on the site. What could be the reason? =

* For correct work plugin your theme must support [wp_head action hook](https://developer.wordpress.org/reference/functions/wp_head/) and [wp_footer action hook](https://developer.wordpress.org/reference/functions/wp_footer/).
* If you are using plugins for caching you need to clear the cache to apply the changes.

== Screenshots ==

1. Settings page
2. Example page

== Changelog ==

= 1.1.3 =
* Add settings link to Plugins page
* Correct plugin description

= 1.1.2 =
* Constant `DCO_IAC_DO_SHORTCODE` replaced with `dco_iac_disable_do_shortcode` filter. Use `add_filter('dco_iac_disable_do_shortcode', '__return_true');` to disable shortcodes support.

= 1.1.1 =
* Added feature to hide the code
* Added shortcodes support (add constant `define('DCO_IAC_DO_SHORTCODE', false);` to wp-config.php for disable)

= 1.1.0 =
* Fixed Text Domain
* Added the ability to adjust the show code for logged / not logged users
* Restricted direct access to plugin files

= 1.0.0 =
* Initial Release