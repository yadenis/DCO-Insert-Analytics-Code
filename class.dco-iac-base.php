<?php

class DCO_IAC_Base {

    protected $options = array();

    protected function init_hooks() {
        $this->get_options();
        add_action('admin_init', array($this, 'load_language'));
    }

    protected function get_options() {
        $default = array(
            'before_head' => '',
            'after_body' => '',
            'before_body' => ''
        );

        $options = get_option('dco_iac');
        if (is_array($options)) {
            $options = array_map('trim', $options);
        }

        $this->options = apply_filters('dco_iac_get_options', wp_parse_args($options, $default), $options, $default);
    }

    public function load_language() {
        load_plugin_textdomain('dco-iac', false, plugin_basename(DCO_IAC__PLUGIN_DIR) . '/languages');
    }

}
