<?php
/*
Plugin Name: WP HTML Mail
Plugin URI: http://wp-html-mail.com/
Description: Create your own professional email design for all your outgoing WordPress emails
Version: 2.5
Text Domain: wp-html-mail
Domain Path: /translations
Author: Hannes Etzelstorfer
Author URI: http://etzelstorfer.com/en/
License: GPLv2 or later
*/

/*  Copyright 2017 Hannes Etzelstorfer (email : hannes@etzelstorfer.com) */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'HAET_MAIL_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_MAIL_URL', plugin_dir_url(__FILE__) );


require HAET_MAIL_PATH . 'includes/class-haet-mail.php';
require HAET_MAIL_PATH . 'includes/class-haet-sender-plugin.php';

load_plugin_textdomain('wp-html-mail', false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );



//Actions and Filters	
if ( class_exists("Haet_Mail") ) {
    if ( version_compare(PHP_VERSION, '5.6') < 0 ) {
        add_action( 'admin_notices', 'haet_mail_php_update_notice' );
    }else{
        if(is_plugin_active( 'wpmandrill/wpmandrill.php' ))
            add_filter( 'mandrill_payload', array(Haet_Mail(), 'style_mail_wpmandrill') );
        else
            add_filter( 'wp_mail',array(Haet_Mail(), 'style_mail'),12,1);

        add_action( 'admin_menu', array(Haet_Mail(), 'admin_page'),20);
        add_action( 'admin_enqueue_scripts', array(Haet_Mail(), 'admin_page_scripts_and_styles'));

        add_action( 'wp_ajax_haet_mail_send_test', array(Haet_Mail(), 'send_test') );
    }
}


function haet_mail_php_update_notice() {
    ?>
    <div class="update-nag">
        <p><?php _e( '<strong>Warning:</strong> To use WP HTML Mail please update your PHP version to 5.6 or higher in your hosting admin panel.', 'wp-html-mail' ); ?></p>
    </div>
    <?php
}



// delete_option('haet_mail_theme_options');
// delete_option('haet_mail_options');
// delete_option('haet_mail_plugin_options');

// RESET PREVIEW EMAIL
// 
// $plugin_options = get_option('haet_mail_plugin_options');
// unset( $plugin_options['woocommerce']['preview_mail'] );            
// update_option('haet_mail_plugin_options', $plugin_options);

	

