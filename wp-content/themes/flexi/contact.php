<?php /* Template Name: Contact */ ?>
<?php get_header('2'); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="container">
      <div class="page-content">
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

        <!-- Contact section Start -->
        <section class="block-contact block-spaced">
          <h1 class="section-title"><?php _e('Formulaire de contact', 'flexiauto'); ?></h1>
          <?php print do_shortcode('[contact-form-7 id="4" title="Contact" html_class="form-validate contact-form"]'); ?>
        </section>
        <!-- Contact section END -->
      </div>
    </div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
