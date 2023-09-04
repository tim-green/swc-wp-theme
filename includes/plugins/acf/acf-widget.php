<?php

/**
 * A custom ACF widget.
 */
class ACF_Custom_Widget extends WP_Widget {

    /**
    * Register widget with WordPress.
    */
    function __construct() {
        parent::__construct(
            'acf_custom_widget', // Base ID
            __('ACF Custom Widget', 'text_domain'), // Name
            array( 'description' => __( 'A custom ACF widget', 'text_domain' ), 'classname' => 'acf-custom-widget' ) // Args
        );
    }

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( !empty($instance['title']) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }

        echo get_field('title', 'widget_' . $args['widget_id']);


        // BEGIN ACF CODE
        if ( get_field('content') ) {
            echo '<div class="content">'
                . get_field('content') . 
            '</div>';
        }
        // END ACF CODE


        echo $args['after_widget'];
    }

    /**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
    public function form( $instance ) {
        if ( isset($instance['title']) ) {
            $title = $instance['title'];
        }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php
    }

    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class ACF_Custom_Widget

// register ACF_Custom_Widget widget
add_action( 'widgets_init', function(){
  register_widget( 'ACF_Custom_Widget' );
});
