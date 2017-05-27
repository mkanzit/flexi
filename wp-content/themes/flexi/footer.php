		</div><!-- #content -->

		<footer class="site-footer">
			<div class="bottom-menus">
				<div class="container">
					<div class="row">
						<?php
							if ( has_nav_menu( 'bottom' ) ) : ?>
								<nav class="footer-navigation" role="navigation">
									<?php
										wp_nav_menu( array(
											'theme_location' => 'bottom',
											'menu_id'        => 'footer-menu',
											'menu_class'     => 'menu menu-footer-nav clearfix',
										) );
									?>
								</nav><!-- .social-navigation -->
							<?php endif;
						?>
						<hr>
						<?php if ( has_nav_menu( 'findus' ) ) : ?>
							<nav class="footer-navigation" role="navigation">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'findus',
										'menu_id'        => 'footer-menu',
										'menu_class'     => 'menu menu-contact clearfix',
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>
					</div>
				</div><!-- .container -->
			</div>
			<div class="footer-links">
				<div class="container">
					<div class="row">
						<?php if ( has_nav_menu( 'links' ) ) : ?>
							<nav class="footer-navigation" role="navigation">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'links',
										'menu_id'        => 'footer-legal',
										'menu_class'     => 'menu menu-legal clearfix',
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if(get_field('notice')): ?>
				<div class="footer-notice">
					<div class="container">
						<?php the_field('notice'); ?>
					</div>
				</div>
			<?php endif; ?>
		</footer>

		<span id="top-link-block" class="hidden">
			<a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
				<i class="glyphicon glyphicon-arrow-up"></i>
			</a>
		</span><!-- /top-link-block -->


	</div><!-- .site-content-contain -->
</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>
