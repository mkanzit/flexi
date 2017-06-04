<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="site">
	<header class="site-header" role="banner">

		<div class="hero-zone-featured-image">
			<?php if( !is_front_page() && !is_search() && !is_404() ): ?>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail(the_ID(), 'flexiauto-single-image', array('alt' => 'hero-image')); ?>
				</a>
			<?php elseif( is_search() ): ?>
					<a class="search" href="<?php the_permalink(); ?>">
						<img src="<?php print TPL_DIR ?>/assets/images/coffee.jpg" alt="Search page">
					</a>
			<?php elseif( is_404()  ): ?>
					<a class="error" href="<?php the_permalink(); ?>">
						<img src="<?php print TPL_DIR ?>/assets/images/error.jpg" alt="Error page">
					</a>
			<?php else: ?>
				<a href="<?php the_permalink(); ?>">
					<img src="<?php print TPL_DIR ?>/assets/images/header.jpg" alt="Global header">
				</a>
			<?php endif; ?>
		</div>

		<div class="header-top">
			<div class="site-branding">
				<a href="<?php the_permalink(get_option( 'page_on_front' )); ?>">
					<img src="<?php the_field('logo', get_option( 'page_on_front' )); ?>" alt="Flexi AUTO">
				</a>
			</div>

			<div class="menu-ext mobile-only">
				<span class="open-search"></span>
				<div class="lang-menu">
					<?php dynamic_sidebar('language');?>
				</div>
				<div class="hamburger">
					<div class="pipe1"></div>
					<div class="pipe2"></div>
					<div class="pipe3"></div>
				</div>
			</div>

			<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>">
				<?php wp_nav_menu( array(
					'theme_location' => 'top',
					'menu_id'        => 'top-menu',
					'menu_class'     => 'menu clearfix',
				) ); ?>
				<span class="open-search desktop-only"></span>
				<div class="lang-menu desktop-only">
					<?php dynamic_sidebar('language');?>
				</div>
			</nav>
		</div>

		<?php if( is_page() && !is_page_template('search.php') ): ?>
			<div class="single-page-title">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</div>
		<?php endif; ?>

		<?php if( is_search()): ?>
			<div class="single-page-title">
				<h1 class="page-title"><?php _e('Rechechre', 'flexiauto'); ?></h1>
			</div>
		<?php endif; ?>

		<div class="fixed-links">
			<?php if ( has_nav_menu( 'quicklinks' ) ) : ?>
				<nav class="quicklinks-navigation" role="navigation">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'quicklinks',
							'menu_id'        => 'quicklinks',
							'menu_class'     => 'menu menu-quicklinks clearfix',
						) );
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>
		</div>

		<div class="search-area">
			<span class="close-search"></span>
			<?php get_search_form(); ?>
		</div>

	</header>

	<div class="site-content-contain">
		<div id="content" class="site-content">
