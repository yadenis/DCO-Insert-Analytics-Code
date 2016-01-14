<?php

class DCO_IAC_Admin extends DCO_IAC_Base {

    public function __construct() {
        add_action('init', array($this, 'init_hooks'));
    }

    public function init_hooks() {
        parent::init_hooks();

        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'create_menu'));

        //Additional links on the plugin page
        add_filter('plugin_row_meta', array($this, 'register_plugin_links'), 10, 2);
    }

    public function register_plugin_links($links, $file) {
        if ($file == DCO_IAC__PLUGIN_BASENAME) {
            $links[] = '<a href="https://github.com/Denis-co/DCO-Insert-Analytics-Code">' . __('GitHub', 'dco-iac') . '</a>';
        }

        return $links;
    }

    public function create_menu() {
        add_options_page(__('DCO Insert Analytics Code', 'dco-iac'), __('DCO Insert Analytics Code', 'dco-iac'), 'manage_options', 'dco_insert_code_analytics', array($this, 'render'));
    }

    public function register_settings() {
        register_setting('dco_iac', 'dco_iac');

        add_settings_section(
                'dco_iac_general', '', '', 'dco_iac'
        );

        add_settings_field(
                'before_head', __('Before &lt;/head&gt;', 'dco-iac'), array($this, 'before_head_render'), 'dco_iac', 'dco_iac_general'
        );

        add_settings_field(
                'after_body', __('After &lt;body&gt;', 'dco-iac'), array($this, 'after_body_render'), 'dco_iac', 'dco_iac_general'
        );

        add_settings_field(
                'before_body', __('Before &lt;/body&gt;', 'dco-iac'), array($this, 'before_body_render'), 'dco_iac', 'dco_iac_general'
        );
    }

    public function before_head_render() {
        ?>
        <textarea rows="10" style="width:100%;" name="dco_iac[before_head]" <?php disabled(has_filter('dco_iac_get_options')) ?>><?php echo $this->options['before_head']; ?></textarea>
        <?php
    }

    public function after_body_render() {
        ?>
        <textarea rows="10" style="width:100%;" name="dco_iac[after_body]" <?php disabled(has_filter('dco_iac_get_options')) ?>><?php echo $this->options['after_body']; ?></textarea>
        <?php
    }

    public function before_body_render() {
        ?>
        <textarea rows="10" style="width:100%;" name="dco_iac[before_body]" <?php disabled(has_filter('dco_iac_get_options')) ?>><?php echo $this->options['before_body']; ?></textarea>
        <?php
    }

    function render() {
        ?>
        <div class="wrap">
            <h1><?php _e('DCO Insert Analytics Code', 'dco-iac'); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('dco_iac');
                do_settings_sections('dco_iac');
                submit_button(null, 'primary', 'submit', true, disabled(has_filter('dco_iac_get_options'), true, false));
                ?>
            </form>
        </div>
        <?php
    }

}

$dco_iac_admin = new DCO_IAC_Admin();
