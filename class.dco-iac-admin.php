<?php
defined('ABSPATH') or die;

class DCO_IAC_Admin extends DCO_IAC_Base {

    protected $sections;

    public function __call($name, $arguments) {
        $name_array = explode('_', $name);
        if (count($name_array) < 3) {
            return false;
        }

        $option_name = $name_array[0] . '_' . $name_array[1];
        if ($name_array[2] == 'render') {
            ?>
            <textarea rows="10" style="width:100%;" name="dco_iac[<?php echo $option_name; ?>]" <?php disabled(has_filter('dco_iac_get_options')) ?>><?php echo $this->options[$option_name]; ?></textarea>
            <?php
        }

        if ($name_array[2] == 'show') {
            ?>
            <select name="dco_iac[<?php echo $option_name; ?>_show]" <?php disabled(has_filter('dco_iac_get_options')) ?>>
                <option value="0" <?php selected($this->options[$option_name . '_show'], '0'); ?>><?php esc_html_e('All Users', 'dco-insert-analytics-code'); ?></option>
                <option value="1" <?php selected($this->options[$option_name . '_show'], '1'); ?>><?php esc_html_e('Not Logged Users', 'dco-insert-analytics-code'); ?></option>
                <option value="2" <?php selected($this->options[$option_name . '_show'], '2'); ?>><?php esc_html_e('Logged Users', 'dco-insert-analytics-code'); ?></option>
                <option value="3" <?php selected($this->options[$option_name . '_show'], '3'); ?>><?php esc_html_e('Nobody', 'dco-insert-analytics-code'); ?></option>
            </select>
            <?php
        }
    }

    public function __construct() {
        $this->sections = array(
            /* translators: Before </head> */
            'before_head' => __('Before', 'dco-insert-analytics-code') . ' ' . esc_html('</head>'),
            /* translators: After <body> */
            'after_body' => __('After', 'dco-insert-analytics-code') . ' ' . esc_html('<body>'),
            /* translators: Before </body> */
            'before_body' => __('Before', 'dco-insert-analytics-code') . ' ' . esc_html('</body>')
        );

        add_action('init', array($this, 'init_hooks'));
    }

    public function init_hooks() {
        parent::init_hooks();

        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'create_menu'));

        //Additional links on the plugin page
        add_filter('plugin_action_links_' . DCO_IAC__PLUGIN_BASENAME, array($this, 'register_plugin_links'));
    }

    public function register_plugin_links($links) {
        array_unshift($links, sprintf(
            '<a href="%1$s" title="%2$s">%3$s</a>', self_admin_url('options-general.php?page=dco-insert-analytics-code'), esc_attr__('Manage your codes', 'dco-insert-analytics-code'), esc_html__('Settings', 'dco-insert-analytics-code')
        ));

        return $links;
    }

    public function create_menu() {
        add_options_page(__('DCO Insert Analytics Code', 'dco-insert-analytics-code'), __('DCO Insert Analytics Code', 'dco-insert-analytics-code'), 'manage_options', 'dco-insert-analytics-code', array($this, 'render'));
    }

    public function register_settings() {
        register_setting('dco_iac', 'dco_iac');

        foreach ($this->sections as $key => $title) {
            $section_key = 'dco_iac_' . $key;

            add_settings_section(
                    $section_key, $title, '', 'dco_iac'
            );

            add_settings_field(
                    $key, __('Code', 'dco-insert-analytics-code'), array($this, $key . '_render'), 'dco_iac', $section_key
            );

            add_settings_field(
                    $key . '_show', __('Show Code', 'dco-insert-analytics-code'), array($this, $key . '_show_render'), 'dco_iac', $section_key
            );
        }
    }

    function render() {
        ?>
        <div class="wrap">
            <h1><?php _e('DCO Insert Analytics Code', 'dco-insert-analytics-code'); ?></h1>
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

$GLOBALS['dco_iac_admin'] = new DCO_IAC_Admin();
