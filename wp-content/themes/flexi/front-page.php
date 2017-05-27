<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

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

		<!-- Contact section START -->
		<section class="block-contact-us block-spaced">
			<div class="container">
				<div class="row">
					<h1 class="section-title">Contact</h1>
					<?php if(have_rows('contact_infos')): ?>
					<?php 	while(have_rows('contact_infos')): the_row(); ?>
						<div class="block-contact-us col-md-4 col-sm-4 col-xs-12">
							<div class="vid">
								<img src="<?php the_sub_field('vid'); ?>" alt="Illustration">
							</div>
							<div class="content">
								<h2 class="block-title"><?php the_sub_field('titre') ?></h2>
								<?php the_sub_field('contenu') ?>
							</div>
							<a class="btn btn-default" href="<?php the_sub_field('savoir_plus') ?>"><?php _e('Savoir plus', 'flexiauto') ?></a>
						</div>
					<?php 	endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<!-- Contact section END -->

		<!-- Contact section START -->
		<section class="block-themes block-spaced">
			<div class="container">
				<div class="row">
					<h1 class="section-title">Thèmes principaux</h1>
					<?php if(have_rows('themes')): ?>
					<?php 	while(have_rows('themes')): the_row(); ?>
						<div class="block-theme col-md-3 col-sm-6 col-xs-12">
							<div class="vid">
								<img src="<?php the_sub_field('vid'); ?>" alt="Illustration">
							</div>
							<div class="content">
								<h2 class="block-title"><?php the_sub_field('titre') ?></h2>
								<?php the_sub_field('contenu') ?>
							</div>
							<a class="btn btn-default" href="<?php the_sub_field('savoir_plus') ?>">Savoir plus</a>
						</div>
					<?php 	endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<!-- Contact section END -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
