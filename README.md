# DCO Insert Analytics Code

DCO Insert Analytics Code is a Wordpress plugin is intended for insert analytics code(or any custom code) before &lt;/head&gt; or after &lt;body&gt; or before &lt;/body&gt;

# Version
1.0.0

#Usage
After installation and activation, you can insert the necessary code to the respective fields on the plugin settings page.

#Filters list
##dco_iac_get_options
Filter for hardcoding override plugin settings. *You won't be able to edit them on the settings page anymore when using this filter.*
##dco_iac_insert_before_head
Filter to change the code is inserted before &lt;/head&gt;
##dco_iac_insert_after_body
Filter to change the code is inserted before &lt;body&gt;
##dco_iac_insert_before_body
Filter to change the code is inserted before &lt;/body&gt;

#Examples of using filters
##Hardcoding override plugin settings
```php
function custom_get_options($current, $options, $default) {
    $array = array(
        'before_head' => '<!-- before head -->',
        'after_body'  => '<!-- after body -->',
        'before_body' => '<!-- before body -->'
    );

    return $array;
}

add_filter('dco_iac_get_options', 'custom_get_options', 10, 3);

/*
* $current - current plugin settings
*
* $options - plugin settings from database
*
* $default - default plugin settings
*/
```

##Change before &lt;/head&gt; code
```php
function custom_before_head_code( $code ) {
    return $code . '<!-- before head -->' . "\n";
}

add_filter( 'dco_iac_insert_before_head', 'custom_before_head_code' );

/*
* $code - value from "before </head>" setting
*/
```

#Changelog
##1.0.0
- Initial Release