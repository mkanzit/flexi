<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo $unique_id; ?>" class="hide">
		<span class="screen-reader-text"><?php echo _e( 'Search for:', 'label', 'flexiauto' ); ?></span>
	</label>
	<div class="form-item">
		<input type="search" id="<?php echo $unique_id; ?>" class="search-field" name="s" />
	</div>
	<div class="form-action">
		<button type="submit" class="btn btn-submit"><?php _e('Search', 'flexiauto'); ?></button>
	</div>
</form>
