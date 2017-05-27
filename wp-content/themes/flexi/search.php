<?php get_header(); ?>

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
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
			?>
						<div class="result-item">
							<div class="vid col-md-4 col-sm-4 col-xs-12">
								<?php print get_the_post_thumbnail(get_the_ID(), 'flexiauto-article-image', array('alt' => 'search-image')); ?>
							</div>
							<div class="desc col-md-6 col-sm-6 col-xs-12">
								<h3 class="block-title"><?php the_title() ?></h3>
								<p><?php postExcerpt(get_field('description', get_the_ID())); ?></p>
								<a class="btn btn-default" href="<?php the_permalink(); ?>"><?php _e('Savoir plus', 'flexiauto'); ?></a>
							</div>
						</div>
			<?php
					endwhile;
			?>

			<?php else : ?>
				<p><?php _e( 'Sorry, but nothing matched your search terms.', 'flexiauto' ); ?></p>
			<?php
				get_search_form();
				endif;
			?>
		</div>

		<?php if( is_paged() ): ?>
			<div class="block-pagination">
				<?php the_posts_pagination( array(
						'mid_size' => 2,
						'prev_text' => _e( 'Back', 'flexiauto' ),
						'next_text' => _e( 'Onward', 'flexiauto' ),
				) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer();
