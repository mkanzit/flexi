<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}



$stopbadbots_option_name[0] = 'stop_bad_bots_active';
$stopbadbots_option_name[1] = 'stopbadbots_my_blacklist';
$stopbadbots_option_name[2] = 'stopbadbots_my_email_to';
$stopbadbots_option_name[3] = 'stopbadbots_my_radio_report_all_visits';
$stopbadbots_option_name[4] = 'stopbadbots_version';
$stopbadbots_option_name[5] = 'stopbadbots_per_page';
$stopbadbots_option_name[6] = 'stop_bad_bots_network';
$stopbadbots_option_name[7] = 'stopbadbots_last_checked';





for ($i = 0; $i < 8; $i++)
{
 delete_option( $stopbadbots_option_name[$i] );
 // For site options in Multisite
 delete_site_option( $stopbadbots_option_name[$i] );    
}


// Drop a custom db table
global $wpdb;
$current_table = $wpdb->prefix . 'sbb_blacklist';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );

?>