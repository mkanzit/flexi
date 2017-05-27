<?php /* Template Name: Sitemap */ ?>
<?php get_header('2'); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="container">
      <div class="page-content">
        <h1 class="page-title"><?php the_title();  ?></h1>
        <hr>
        <div class="page-content">
          <?php print do_shortcode('[wp_sitemap_page]') ?>
        </div>
      </div>
    </div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>

