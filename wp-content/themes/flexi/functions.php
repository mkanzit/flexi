<?php

	// Error reporting level
	error_reporting(E_ALL);

	// Template path shorthand
	define('TPL_DIR', get_template_directory_uri());

	// Twenty Seventeen only works in WordPress 4.7 or later.
	if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
		require get_template_directory() . '/inc/back-compat.php';
		return;
	}

	/**
		* Sets up theme defaults and registers
		* support for various WordPress features.
	***/
	function flexiauto_setup() {
		/* Make theme available for translation. */
		load_theme_textdomain( 'flexiauto' );
		// load_theme_textdomain( 'flexiauto', TPL_DIR . '/languages' );

		/* Let WordPress manage the document title. */
		add_theme_support( 'title-tag' );

		/* Enable support for Post Thumbnails on posts and pages. */
		add_theme_support( 'post-thumbnails' );

		/* Enable support for image sizes. */
		add_image_size( 'flexiauto-featured-image', 1920, 1070, true );
		add_image_size( 'flexiauto-single-image', 1920, 540, true );
		add_image_size( 'flexiauto-article-image', 530, 300, true );

		/* This theme uses wp_nav_menu() in these locations. */
		register_nav_menus( array(
			'top'    => __( 'Top Menu', 'flexiauto' ),
			'bottom'    => __( 'Bottom Menu', 'flexiauto' ),
			'findus' => __( 'Find us Menu', 'flexiauto' ),
			// 'links' => __( 'Bottom links Menu', 'flexiauto' ),
			'quicklinks' => __( 'Quick links Menu', 'flexiauto' ),
			'languges' => __( 'Languages Menu', 'flexiauto' ),
		) );

		/* Register language switcher sidebar */
		register_sidebar(array(
											'name'          => __( 'language', 'quadrimex' ),
											'id'            => 'language',
											'description'   => __( 'widget des langues.', 'flexiauto' ),
											'before_widget' => '<ul class="lang">',
											'after_widget'  => '</ul>',
											));

		/*
		* Switch default core markup for search form,
		* comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/* Enable support for Post Formats. */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		) );

		/*
		* This theme styles the visual editor to resemble the theme style,
		* specifically font, colors, and column width.
		*/
		add_editor_style(
			array( 'assets/css/editor-style.css', flexiauto_fonts_url())
		);
	}
	add_action( 'after_setup_theme', 'flexiauto_setup' );


	/* Enqueue scripts and styles. */
	function flexiauto_scripts_loader() {
		/* Load custom styles */
		wp_enqueue_style('reset', TPL_DIR . '/assets/css/vendor/reset.css');
		wp_enqueue_style('bootstrap-styles', TPL_DIR . '/assets/css/vendor/bootstrap.min.css');
		wp_enqueue_style('flexi-styles', TPL_DIR . '/assets/css/flexi.min.css');

		/* Load custom scripts */
		wp_deregister_script('jquery');
		wp_register_script('jquery', TPL_DIR . '/assets/js/vendor/jquery.min.js', array(), false, true);
		wp_enqueue_script('jquery');

		wp_enqueue_script('bootstrap-scripts', TPL_DIR . '/assets/js/vendor/bootstrap.min.js', array(), false, true);
		wp_enqueue_script('nicescroll', TPL_DIR . '/assets/js/vendor/jquery.nicescroll.min.js', array(), false, true);
		wp_enqueue_script('jquery-validate', TPL_DIR . '/assets/js/vendor/jquery.validate.min.js', array(), false, true);
		wp_enqueue_script('match-height', TPL_DIR . '/assets/js/vendor/jquery.matchHeight.min.js', array(), false, true);
		wp_enqueue_script('flexi-scripts', TPL_DIR . '/assets/js/flexi.min.js', array(), false, true);

	}
	add_action('wp_enqueue_scripts', 'flexiauto_scripts_loader' );


	/* Register custom fonts.	*/
	function flexiauto_fonts_url() {
		$fonts_url = '';

		$roboto = _x( 'on', 'Roboto font: on or off', 'flexiauto' );

		if ( 'off' !== $roboto ) {
			$font_families = array();

			$font_families[] = 'Roboto:300,400,500,700,900';

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}

	/* Add preconnect for Google Fonts. */
	function flexiauto_resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'flexiauto-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}

		return $urls;
	}
	add_filter( 'wp_resource_hints', 'flexiauto_resource_hints', 10, 2 );


	/* Retrieve posts */
	function getPosts($postType, $ppp=-1){
		$args = array(
			'post_type' => $postType,
			'post_status' => 'publish',
			'orderby '=> 'menu_order',
			'order'   => 'asc',
			'posts_per_page' => $ppp
			);
		$res = new WP_Query($args);
		return $res->posts;
	}

	/* Create post excerpt */
	function postExcerpt($content) {
		$clean = strip_tags($content);
		if (strlen($clean) > 120){
			$excerpt = substr($clean, 0, strpos($clean, ' ', 120));
			print $excerpt . ' ...';
		}
	}

