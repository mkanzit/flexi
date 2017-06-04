<?php get_header('2'); ?>

<div class="container">
	<div class="row">
		<header class="page-header">
			<?php if ( have_posts() ) : ?>
				<h3 class="page-title"><?php printf( __( 'Résultats pour : %s', 'flexiauto' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
			<?php else : ?>
				<h3 class="page-title"><?php _e( 'J\'ai rien trouvé !!!', 'flexiauto' ); ?></h3>
			<?php endif; ?>
		</header>

		<div class="search-results block-spaced clearfix">
			<?php if ( have_posts() ) : ?>
				<ol>
					<?php while ( have_posts() ) : the_post(); ?>
						<li class="result-item col-sm-12">
							<h3 class="block-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
						</li>
					<?php endwhile; ?>
				</ol>
			<?php
				else :
					get_search_form();
				endif;
			?>
		</div>


		<div class="block-pagination block-spaced">
			<?php wp_pagenavi(); ?>
		</div>

	</div>
</div>

<?php get_footer();
