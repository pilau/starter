<?php

/**
 * Widgets and sidebars
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'widgets_init', 'pilau_register_sidebars' );
/**
 * Register sidebars
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_register_sidebars() {

	/* Default sidebar */
	register_sidebar( array(
		'id'				=> 'default-sidebar',
		'name'				=> __( 'Default' ),
		'before_widget'		=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'		=> '</aside>',
		'before_title'		=> '<h2 class="widget-title">',
		'after_title'		=> '</h2>',
	));

}


add_action( 'widgets_init', 'pilau_register_widgets', 1 );
/**
 * Register / unregister widgets
 *
 * @since	0.1
 */
function pilau_register_widgets() {
	// List all widgets (set priority on action higher!)
	//$widgets = array_keys( $GLOBALS['wp_widget_factory']->widgets );
	//print '<pre>$widgets = ' . esc_html( var_export( $widgets, true ) ) . '</pre>';exit;

	/*
	 * Unregister some default widgets
	 */
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_RSS' );
	if ( ! PILAU_USE_TAGS || PILAU_HIDE_TAGS ) {
		unregister_widget( 'WP_Widget_Tag_Cloud' );
	}
	if ( ! PILAU_USE_CATEGORIES || PILAU_HIDE_CATEGORIES ) {
		unregister_widget( 'WP_Widget_Categories' );
	}
	if ( ! PILAU_USE_COMMENTS ) {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}
	//unregister_widget( 'WP_Widget_Search' );
	//unregister_widget( 'WP_Widget_Text' );
	//unregister_widget( 'WP_Widget_Pages' );
	//unregister_widget( 'WP_Widget_Calendar' );
	//unregister_widget( 'WP_Widget_Archives' );

	/*
	 * Register custom widgets
	 */
	//register_widget( 'Pilau_Widget_Example' );

}


if ( function_exists( 'slt_obfuscate_email' ) ) {
	add_filter( 'widget_text', 'pilau_widget_email_obfuscation' );
}
/**
 * Obfuscate emails in widgets
 *
 * @since	Pilau_Starter 0.1
 * @uses	pilau_obfuscate_email()
 */
function pilau_widget_email_obfuscation( $text ) {
	return preg_replace_callback( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', create_function( '$matches', 'return pilau_obfuscate_email( $matches[0] );' ), $text );
}


/**
 * Example widget
 *
 * @since	0.1
 */
class Pilau_Widget_Example extends WP_Widget {

	/**
	 * Initialise
	 */
	function Pilau_Widget_Example() {
		$this->WP_Widget(
			'pilau-example',
			'Example',
			array(
				'classname'		=> 'pilau-widget-example',
				'description'	=> 'Example widget'
			)
		);
	}

	/**
	 * Admin form
	 */
	function form( $instance ) {
		$defaults = array(
			'title'		=> __( 'An example' )
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<div class="pilau-widget-field">
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional)</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</div>
		<div class="pilau-widget-field">
			<label for="<?php echo $this->get_field_id( 'content' ); ?>">Content</label>
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo esc_textarea( $instance['content'] ); ?></textarea>
		</div>
		<?php

	}

	/**
	 * Update
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['content'] = strip_tags( $new_instance['content'] );
		return $instance;
	}

	/**
	 * Display
	 */
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}
		if ( $instance['content'] ) {
			echo '<p>' . esc_html( $instance['content'] ) . '</p>';
		}
		echo $args['after_widget'];
	}

}
