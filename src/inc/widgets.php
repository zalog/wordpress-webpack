<?php

/**
 * Custom Widget - Example
 */
class widget_ex extends WP_Widget {

    // register
    public function __construct() {
        $widget_ops = array(
            'classname' => 'widget-ex',
        );
        parent::__construct( 'widget_ex', '-Widget Name', $widget_ops );
    }

    // front-end display
    public function widget( $args, $instance ) {

        echo $args['before_widget'];
            if ( ! empty( $instance['title'] ) ) echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
            echo 'Widget text';
        echo $args['after_widget'];

    }

    // back-end widget form
    public function form( $instance ) {
        $title = strip_tags( $instance['title'] );
        ?><p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
            <input type="text" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat">
        </p><?php
    }

    // sanitize form values on save
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} add_action( 'widgets_init', function() { register_widget( 'widget_ex' ); });
