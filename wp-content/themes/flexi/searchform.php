<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="row">
		<label for="<?php echo $unique_id; ?>" class="hide">
			<span class="screen-reader-text"><?php echo _e( 'Search for:', 'label', 'flexiauto' ); ?></span>
		</label>
		<div class="form-item col-sm-9 col-xs-12">
			<input type="search" id="<?php echo $unique_id; ?>" class="search-field" name="s" />
		</div>
		<div class="form-action col-sm-3 col-xs-12">
			<button type="submit" class="btn btn-submit-invert"><?php _e('Search', 'flexiauto'); ?></button>
		</div>
	</div>
</form>
