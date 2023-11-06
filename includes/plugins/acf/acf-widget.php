<?php

/**
 * A custom ACF widget.
 */
// Register the custom widget
function register_custom_acf_widget() {
    register_widget('Custom_ACF_Widget');
}
add_action('widgets_init', 'register_custom_acf_widget');

// Create the custom widget class
class Custom_ACF_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'custom_acf_widget',
            'Custom ACF Widget',
            array('description' => 'A custom widget to display ACF field values')
        );
    }

    public function widget($args, $instance) {
        // Output the ACF field value here
        $acf_field_value = get_field('your_acf_field_name');
        echo $acf_field_value;

        // You can customize the widget's HTML output as needed
    }
}

