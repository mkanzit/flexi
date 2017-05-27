<?php /* Page template */ ?>
<?php get_header('2'); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="container">

      <!-- Intro section START -->
      <section class="block-page-introduction block-spaced">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <?php the_field('texte_introduction') ?>
            </div>
          </div>
        </div>
      </section>
      <!-- Intro section END -->

      <!-- Content section START -->
      <section class="block-page-section block-spaced">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <?php the_content(); ?>
                </div>
              </div>
            </div>
          </section>
      <!-- Content section END -->
    </div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>

