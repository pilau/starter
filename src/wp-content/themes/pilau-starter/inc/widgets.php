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
	global $pilau_default_sidebar_args;

	// Sidebar args - useful if dropping widgets in manually outside standard sidebars
	$pilau_default_sidebar_args = array(
		'before_widget'		=> '<aside class="widget %s">',
		'after_widget'		=> '</aside>',
		'before_title'		=> '<h2 class="widget-title">',
		'after_title'		=> '</h2>',
	);

	// Default sidebar
	register_sidebar( array_merge( $pilau_default_sidebar_args, array(
		'id'				=> 'default-sidebar',
		'name'				=> __( 'Default' ),
	)));

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
	//register_widget( 'Pilau_Widget_Social_Sharing' );

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

		// Hook to enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );

	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts_styles() {
		pilau_upload_media_enqueue();
		wp_localize_script( 'pilau-upload-media', 'pilau_upload_media', array(
			'dialog_title__' . $this->get_field_id( 'image' )			=> __( 'Select image' ),
			'restrict_to_type__' . $this->get_field_id( 'image' )		=> 'image'
		));
	}

	/**
	 * Admin form
	 */
	public function form( $instance ) {
		$defaults = array(
			'image_featured_or_custom'	=> 'featured',
			'more_text'					=> __( 'Read more' )
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
						$pt_object = get_post_type_object( $post_type );
						echo '<optgroup label="' . $pt_object->labels->name . '">';
						foreach ( $posts as $the_post ) {
							echo '<option value="' . $the_post->ID . '"' . selected( $the_post->ID, $instance['post_id'] ) . '>' . get_the_title( $the_post ) . '</option>';
						}
						echo '</optgroup>';
					}
				}
				?>
			</select>
		</div>

		<fieldset class="pilau-widget-field">
			<legend><?php _e( 'Use featured image from post, or assign custom image?' ) ?></legend>
			<label class="field-inline" for="<?php echo $this->get_field_id( 'image_featured' ); ?>">
				<?php _e( 'Featured' ); ?>
				<input type="radio" id="<?php echo $this->get_field_id( 'image_featured' ); ?>" name="<?php echo $this->get_field_name( 'image_featured_or_custom' ); ?>" value="featured"<?php checked( $instance['image_featured_or_custom'], 'featured' ); ?>>
			</label>
			<label class="field-inline" for="<?php echo $this->get_field_id( 'image_custom' ); ?>">
				<?php _e( 'Custom' ); ?>
				<input type="radio" id="<?php echo $this->get_field_id( 'image_custom' ); ?>" name="<?php echo $this->get_field_name( 'image_featured_or_custom' ); ?>" value="custom"<?php checked( $instance['image_featured_or_custom'], 'custom' ); ?>>
			</label>
		</fieldset>

		<div class="pilau-widget-field">
			<?php
			pilau_upload_media_field(
				$this->get_field_name( 'image' ),
				$this->get_field_id( 'image' ),
				$instance['image'],
				__( 'Add custom image' )
			);
			?>
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
		$instance['image_featured_or_custom'] = in_array( $new_instance['image_featured_or_custom'], array( 'featured', 'custom' ) ) ? $new_instance['image_featured_or_custom'] : null;
		$instance['image'] = (int) $new_instance['image'];
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
			$image_markup = null;
			switch ( $instance['image_featured_or_custom'] ) {
				case 'featured': {
					if ( has_post_thumbnail( $the_post->ID ) ) {
						$image_markup = get_the_post_thumbnail( $the_post->ID );
					}
					break;
				}
				case 'custom': {
					if ( $instance['image'] ) {
						$image_markup = wp_get_attachment_image( $instance['image'], 'post-thumbnail' );
					}
					break;
				}
			}
			if ( $image_markup ) {
				echo '<figure class="image">' . $image_markup . '</figure>';
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


/**
 * Social sharing widget
 *
 * @since	0.1
 */
class Pilau_Widget_Social_Sharing extends WP_Widget {

	/**
	 * The available services
	 */
	protected $services = array( 'facebook', 'twitter', 'google', 'email', 'sharethis' );

	/**
	 * Initialise
	 */
	public function __construct() {
		parent::__construct(
			'pilau-social-sharing',
			__( 'Social sharing' ),
			array(
				'classname'		=> 'pilau-widget-social-sharing',
				'description'	=> __( 'Links to share on social media.' )
			)
		);
		if ( ! PILAU_PLUGIN_EXISTS_SHARETHIS ) {
			unset( $this->services[ array_search( 'email', $this->services ) ] );
			unset( $this->services[ array_search( 'sharethis', $this->services ) ] );
		}
	}

	/**
	 * Admin form
	 */
	public function form( $instance ) {
		$defaults = array_combine( $this->services, array_fill( 0, count( $this->services ), true ) );
		$instance = wp_parse_args( (array) $instance, $defaults );

		foreach ( $this->services as $service ) {
			?>
			<div class="pilau-widget-field">
				<label for="<?php echo $this->get_field_id( $service ); ?>" style="display: inline-block;margin-right:.5em;width:7em;"><?php echo ucfirst( $service ); ?></label>
				<input type="checkbox" id="<?php echo $this->get_field_id( $service ); ?>" name="<?php echo $this->get_field_name( $service ); ?>" value="1" <?php checked( $instance[ $service ] ); ?>>
			</div>
			<?php
		}

	}

	/**
	 * Update
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $this->services as $service ) {
			$instance[ $service ] = (boolean) $new_instance[ $service ];
		}
		return $instance;
	}

	/**
	 * Display
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		echo str_replace( ' class="', ' class="screen-reader-text ', $args['before_title'] ) . __( 'Social sharing' ) . $args['after_title'];
		pilau_share_links( false, array_keys( $instance, true ) );
		echo $args['after_widget'];
	}

}
