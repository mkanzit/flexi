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
      <?php if(have_rows('section')): ?>
				<?php while(have_rows('section')): the_row(); ?>
          <section class="block-page-section block-spaced">
            <div class="container">
              <h1 class="section-title"><?php the_sub_field('titre') ?></h1>
              <div class="row">
                <?php
                  $layout = get_sub_field('layout');
                  if( $layout == 'full' ):
                ?>
                    <div class="col-md-12 col-xs-12">
                      <?php the_sub_field('full') ?>
                    </div>
                <?php
                  elseif( $layout == 'twocol' ):
                ?>
                <?php if(have_rows('twocol')): ?>
					      <?php 	while(have_rows('twocol')): the_row(); ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php the_sub_field('editeur') ?>
                      </div>
                <?php   endwhile; ?>
                <?php endif; ?>
                <?php
                  else:
                ?>
                  <div class="product-gallery-container">
                    <?php if(have_rows('gallerie')): ?>
                      <?php while(have_rows('gallerie')): the_row(); ?>
                        <div class="block-produit-teaser">
                          <?php $elem = get_sub_field('element'); ?>
                          <div class="vid">
                            <?php $vid = get_field('visuel', $elem->ID); ?>
                            <img src="<?php print $vid['sizes']['flexiauto-article-image']; ?>" alt="<?php print $vid['alt']; ?>" title="<?php print $vid['title']; ?>">
                          </div>
                          <div class="desc">
                              <?php the_field('description', $elem->ID); ?>
                            <a class="btn btn-default" href="<?php the_permalink($elem->ID) ?>"><?php _e('Savoir plus', 'flexiauto') ?></a>
                          </div>
                        </div>
                      <?php endwhile; ?>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </section>
        <?php endwhile; ?>
      <?php endif; ?>
      <!-- Content section END -->
    </div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>

