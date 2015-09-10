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
	//register_widget( 'Pilau_Widget_In_This_Section' );
	//register_widget( 'Pilau_Widget_Call_To_Action' );

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
	public function __construct() {
		parent::__construct(
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
	public function form( $instance ) {
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
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['content'] = strip_tags( $new_instance['content'] );
		return $instance;
	}

	/**
	 * Display
	 */
	public function widget( $args, $instance ) {
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


/**
 * In The Section widget
 *
 * @since	0.1
 */
class Pilau_Widget_In_This_Section extends WP_Widget {

	/**
	 * Initialise
	 */
	public function __construct() {
		parent::__construct(
			'pilau-in-this-section',
			__( 'In This Section' ),
			array(
				'classname'		=> 'pilau-widget-in-this-section',
				'description'	=> __( 'Sub-navigation links.' )
			)
		);
	}

	/**
	 * Admin form
	 */
	public function form( $instance ) {
		$defaults = array(
			'levels'		=> 3
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<div class="pilau-widget-field">
			<label for="<?php echo $this->get_field_id( 'levels' ); ?>"><?php _e( 'Levels' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'levels' ); ?>" name="<?php echo $this->get_field_name( 'levels' ); ?>">
				<?php for ( $i = 1; $i <=4; $i++ ) { ?>
					<option value="<?php echo $i; ?>"<?php selected( $instance['levels'], $i ); ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</div>
		<?php

	}

	/**
	 * Update
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['levels'] = (int) $new_instance['levels'];
		return $instance;
	}

	/**
	 * Display
	 */
	public function widget( $args, $instance ) {
		if ( is_null( PILAU_PAGE_ID_TOP_LEVEL ) ) {
			$title = __( 'Top-level pages' );
		} else {
			$title = __( 'In this section' );
		}
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$output = wp_list_pages( array(
			'sort_column'		=> 'menu_order',
			'sort_order'		=> 'ASC',
			'depth'				=> $instance['levels'],
			'child_of'			=> PILAU_PAGE_ID_TOP_LEVEL,
			'title_li'			=> '',
			'post_status'		=> 'publish',
			'echo'				=> false,
			'walker'			=> new Pilau_Subpages_Walker
		));
		echo '<ul class="pilau-subnav-pages-list">' . $output . '</ul>';
		//echo '<pre>'; print_r( $output ); echo '</pre>'; exit;

		echo $args['after_widget'];
	}

}


/**
 * Call To Action widget
 *
 * @since	0.1
 */
class Pilau_Widget_Call_To_Action extends WP_Widget {

	/**
	 * Initialise
	 */
	public function __construct() {
		parent::__construct(
			'pilau-call-to-action',
			__( 'Call To Action' ),
			array(
				'classname'		=> 'pilau-widget-call-to-action',
				'description'	=> __( 'Highlight a specific post.' )
			)
		);
	}

	/**
	 * Admin form
	 */
	public function form( $instance ) {
		$defaults = array(
			'more_text'		=> __( 'Read more' )
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<div class="pilau-widget-field">
			<label for="<?php echo $this->get_field_id( 'post_id' ); ?>"><?php _e( 'Post' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>">
				<?php
				// Get post types
				$post_types = array_merge( array( 'page', 'post' ), get_post_types( array( '_builtin' => false, 'public' => true ) ) );
				foreach	( $post_types as $post_type ) {
					// Get posts
					$posts = get_posts( array(
						'post_type'			=> $post_type,
						'posts_per_page'	=> -1,
						'orderby'			=> 'title',
						'order'				=> 'ASC',
						'post_status'		=> 'publish'
					));
					if ( $posts ) {
						$pt_labels = get_post_type_labels( get_post_type_object( $post_type ) );
						echo '<optgroup label="' . $pt_labels->name . '">';
						foreach ( $posts as $the_post ) {
							echo '<option value="' . $the_post->ID . '"' . selected( $the_post->ID, $instance['post_id'] ) . '>' . get_the_title( $the_post ) . '</option>';
						}
						echo '</optgroup>';
					}
				}
				?>
			</select>
		</div>
		<div class="pilau-widget-field">
			<label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'More button text' ) ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'more_text' ); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" value="<?php echo $instance['more_text']; ?>">
		</div>
		<?php

	}

	/**
	 * Update
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['post_id'] = (int) $new_instance['post_id'];
		$instance['more_text'] = esc_html( $new_instance['more_text'] );
		return $instance;
	}

	/**
	 * Display
	 */
	public function widget( $args, $instance ) {
		if ( $the_post = get_post( $instance['post_id'] ) ) {
			echo $args['before_widget'];
			echo '<a href="' . get_the_permalink( $the_post->ID ) . '" class="link-block">';
			if ( has_post_thumbnail( $the_post->ID ) ) {
				echo '<figure class="image">' . get_the_post_thumbnail( $the_post->ID ) . '</figure>';
			}
			echo '<div class="text">';
			echo $args['before_title'] . get_the_title( $the_post ) . $args['after_title'];
			echo '<p class="more button">' . $instance['more_text'] . '</p>';
			echo '</div>';
			echo '</a>';
			echo $args['after_widget'];
		}
	}

}
