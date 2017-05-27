<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} ?>
<?php if( !isset( $options['survey_completed'] ) && !isset( $options['survey_dismissed'] ) && $tab != 'survey' ): ?>
	<div class="update-nag haet-survey-nag">
	    <p>
	    	<?php _e('Please help me to improve this plugin.','wp-html-mail'); ?>
	    	<?php _e('How do you like WP HTML Mail so far?','wp-html-mail'); ?>
	    	<span class="haet-star-rating">
	    	    <input type="hidden" class="" id="haet_survey_email_result" name="haet_survey_email_result" value="0">
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=1'; ?>" class="dashicons dashicons-star-empty" data-rating="1"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=2'; ?>" class="dashicons dashicons-star-empty" data-rating="2"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=3'; ?>" class="dashicons dashicons-star-empty" data-rating="3"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=4'; ?>" class="dashicons dashicons-star-empty" data-rating="4"></a>
	    	    <a href="<?php echo $this->get_tab_url('survey').'&rating=5'; ?>" class="dashicons dashicons-star-empty" data-rating="5"></a>
	    	</span>
	    </p>
	</div>
<?php endif; ?>


<h2 class="nav-tab-wrapper">
<?php
	foreach( $tabs as $el => $name ){
		$class = ( $el == $tab ) ? ' nav-tab-active' : '';
		echo '<a class="nav-tab'.$class.'" href="'.$this->get_tab_url($el).'">'.$name.'</a>';
	}
?>
</h2>
<textarea style="display:none;" id="haet_mailtemplate"><?php echo stripslashes(str_replace('\\&quot;','',$template)); ?></textarea>
	
<form method="post" id="haet_mail_form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php
	switch ($tab){
		case 'general':
			include('settings-general.php');
			break; 
		case 'header': 
			include('settings-header.php');
			break; 
		case 'content': 
			include('settings-content.php');
			break;
		case 'footer':
			include('settings-footer.php');
			break;
		case 'plugins':
			include('settings-plugins.php');
			break;
		case 'advanced':
			include('settings-advanced.php');
			break;
		case 'survey':
			include('settings-survey.php');
			break;
		default:
			$is_plugin_tab = false;
			if( isset($active_plugins[ $_GET['tab'] ] ) ){
				$active_plugins[ $_GET['tab'] ]['class']::settings_tab();
				$is_plugin_tab = true;
			}
		break; ?>
	<?php } //switch Tab ?>
	<?php if( $tab != 'survey' ): ?>
		<div class="submit">
			<input type="submit" name="update_haet_mailSettings" class="button-primary" value="<?php _e('Save and Preview', 'wp-html-mail') ?>" />
			<!--<input type="submit" name="reload_haet_mailtemplate" class="button-secondary" value="<?php _e('Discard changes and reload template', 'wp-html-mail') ?>" />-->
		</div>
	<?php endif; ?>
</form>

<?php if( $tab != 'survey' ): ?>
	<iframe id="mailtemplatepreview" style="width:800px; height:480px; border:1px solid #ccc;" ></iframe>
	<br>
<?php endif; ?>

<?php if( ( !isset($is_plugin_tab) || false===$is_plugin_tab ) && $tab != 'survey' ): ?>
	<div class="postbox haet-mail-send-test">
		<h3 class="hndle"><span><?php _e('Send a test mail','wp-html-mail'); ?></span></h3>
		<div style="" class="inside">
			<input id="haet_mail_test_address" required type="email" placeholder="you@example.org"> 
			<button class="button-secondary" id="haet_mail_test_submit"><?php _e('send test mail','wp-html-mail'); ?></button>
			<div id="haet_mail_test_sent" class="haet-mail-dialog" title="<?php _e('Email sent','wp-html-mail'); ?>">
				<p>
					<?php _e('Your message has been sent.','wp-html-mail'); ?>
				</p>
			</div>
		</div>
	</div>
<?php endif; ?>

		



