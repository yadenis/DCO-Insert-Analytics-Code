<?php

class DCO_IAC extends DCO_IAC_Base {

    public function __construct() {
        add_action('init', array($this, 'init_hooks'));
    }

    public function init_hooks() {
        parent::init_hooks();

        if (!empty($this->options['before_head'])) {
            add_action('wp_head', array($this, 'insert_before_head'), 99);
        }

        if (!empty($this->options['after_body'])) {
            add_filter('template_include', array($this, 'insert_after_body'), 99);
        }

        if (!empty($this->options['before_body'])) {
            add_action('wp_footer', array($this, 'insert_before_body'), 99);
        }
    }

    /**
     * Insert code before </head>
     */
    public function insert_before_head() {
        echo apply_filters('dco_iac_insert_before_head', $this->options['before_head'] . "\n");
    }

    /**
     * Insert code after <body>
     */
    public function insert_after_body($template) {
        ob_start(array($this, 'insert_after_body_code'));

        return $template;
    }

    /**
     * Insert code before </body>
     */
    public function insert_before_body() {
        echo apply_filters('dco_iac_insert_before_body', $this->options['before_body'] . "\n");
    }

    /**
     * Helper function for "insert_after_body"
     */
    function insert_after_body_code($buffer) {
        $buffer = preg_replace('@<body[^>]*>@', '$0' . apply_filters('dco_iac_insert_after_body', "\n" . $this->options['after_body']), $buffer);

        return $buffer;
    }

}

$dco_iac = new DCO_IAC();
