<?php /**
 * @author Bill Minozzi
 * @copyright 2016
 */
 
if (is_admin()) {
    if (isset($_GET['page'])) {
        $page = strip_tags($_GET['page']);
        if ($page == 'stop-bad-bots' or $page == 'sbb_my-custom-submenu-page') 
        {
            add_filter('contextual_help', 'stopbadbots_contextual_help', 10, 3);
            function stopbadbots_contextual_help($contextual_help, $screen_id, $screen)
            {
                $myhelp = '<br>'. __("Stop Bad Bots from stealing you.","stopbadbots");
                $myhelp .= '<br />'.__("Read the StartUp guide at Stop Bad Bots Settings page.","stopbadbots");
                $myhelp .= '<br />';
                $myhelp .= __('Visit the','stopbadbots');
                $myhelp .= '&nbsp<a href="http://stopbadbots.com" target="_blank">';
                $myhelp .= __('plugin site','stopbadbots');
                $myhelp .= '</a>&nbsp;';
                $myhelp .= __('for more details, video and online guide.','stopbadbots');
                $screen->add_help_tab(array(
                    'id' => 'sbb-overview-tab',
                    'title' => __('Overview', 'stopbadbots'),
                    'content' => '<p>' . $myhelp . '</p>',
                    ));
                return $contextual_help;
            }
        }
    }
}

function sbb_add_menu_items() {
    $sbb_table_page =  add_submenu_page(
        'stop-bad-bots', // $parent_slug
        'Bad Bots Table', // string $page_title
        'Bad Bots Table', // string $menu_title
        'manage_options', // string $capability
        'sbb_my-custom-submenu-page',
        'sbb_render_list_page' );
     add_action( "load-$sbb_table_page", 'stopbadbots_screen_options' );   
}
function stopbadbots_screen_options() {
	global $sbb_table_page;
	$screen = get_current_screen();
    if(trim($screen->id) != 'stop-bad-bots_page_sbb_my-custom-submenu-page')
    		return;
	$args = array(
		'label' => __('Bots per page', 'stopbadbots'),
		'default' => 10,
		'option' => 'stopbadbots_per_page'
	);
	add_screen_option( 'per_page', $args );
}
function stopbadbots_set_screen_options($status, $option, $value) {
	if ( 'stopbadbots_per_page' == $option ) 
      return $value;
}
function sbb_alertme($userAgentOri)
{
    global $stopbadbotsserver, $sbb_found, $sbb_admin_email, $stopbadbotsip;
     
    $subject = __("Detected Bot on ","stopbadbots").$stopbadbotsserver;
    
    $message[] = __("Bot was detected and blocked.","stopbadbots");
    $message[] = "";
    $message[] = __('Date','stopbadbots')."..............: " . date("F j, Y, g:i a");
    $message[] = __('User Agent','stopbadbots')."........: " . $userAgentOri;
    $message[] = __('Robot IP Address','stopbadbots')."..: " . $stopbadbotsip;
    $message[] = __('String Found:','stopbadbots')."...... " . $sbb_found;
    $message[] = "";
    $message[] = __('eMail sent by Stop Bad Bots Plugin.','stopbadbots');
    $message[] = __('You can stop emails at the Notifications Settings Tab.','stopbadbots');
    $message[] = __('Dashboard => Stop Bad Bots => Stop Bad Bots.','stopbadbots');
    
    $msg = join("\n", $message);
 
    wp_mail($sbb_admin_email, $subject, $msg);
    
    return;
    
}

function sbb_findip()
{
    $ip = '';
		$headers = array(
            'HTTP_CLIENT_IP',        // Bill
            'HTTP_X_REAL_IP',        // Bill
            'HTTP_X_FORWARDED',      // Bill
            'HTTP_FORWARDED_FOR',    // Bill 
            'HTTP_FORWARDED',        // Bill
            'HTTP_X_CLUSTER_CLIENT_IP', //Bill
			'HTTP_CF_CONNECTING_IP', // CloudFlare
			'HTTP_X_FORWARDED_FOR',  // Squid and most other forward and reverse proxies
			'REMOTE_ADDR',           // Default source of remote IP
		);
		for ( $x = 0; $x < 8; $x++ ) {
			foreach ( $headers as $header ) {
				if ( ! isset( $_SERVER[$header] ) ) {
					continue;
				}
				$ip = trim( $_SERVER[$header] );
				if ( empty( $ip ) ) {
					continue;
				}
				if ( false !== ( $comma_index = strpos( $_SERVER[$header], ',' ) ) ) {
					$ip = substr( $ip, 0, $comma_index );
				}
    			// First run through. Only accept an IP not in the reserved or private range.
				if($ip == '127.0.0.1')
                       {
                        $ip='';
                         continue;
                       }
				if ( 0 === $x ) {
					$ip = filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE );
				} else {
					$ip = filter_var( $ip, FILTER_VALIDATE_IP );
				}
				if ( ! empty( $ip ) ) {
					break;
				}
			}
			if ( ! empty( $ip ) ) {
				break;
			}
		}
    if (!empty($ip))
        return $ip;
    else
        return 'unknow';
}

$stopbadbotsip = sbb_findip();

function sbb_plugin_was_activated()
{
    
    global $wp_sbb_blacklist;
     add_option('sbb_was_activated', '1');
     update_option('sbb_was_activated', '1');
     $stopbadbots_installed = trim(get_option( 'stopbadbots_installed',''));
     if(empty($stopbadbots_installed)){
        add_option( 'stopbadbots_installed', time() );
        update_option( 'stopbadbots_installed', time() );
     }
    // require_once (STOPBADBOTSPATH . "functions/aBots.php");
    sbb_create_db();
    sbb_fill_db_froma($wp_sbb_blacklist);
    sbb_upgrade_db();
}
function sbb_fill_db_froma()
{
    global $wpdb, $wp_filesystem;
    $table_name = $wpdb->prefix . "sbb_blacklist";
    $charset_collate = $wpdb->get_charset_collate();
    $botsfile = STOPBADBOTSPATH . 'assets/bots.txt';
    $botshandle = @fopen($botsfile, "r");

    if ($botshandle) {
        $delete = "delete from ".$table_name." WHERE botblocked < 1";
        $wpdb->query($delete);  
        
        while (($botsbuffer = fgets($botshandle, 4096)) !== false) {
           
             $asplit = explode(',', $botsbuffer);
             
             
             if(count($asplit) < 3)
               continue;
               
             $botnickname = trim($asplit['0']);
             $botname = trim($asplit['1']);
             $newbotflag = trim($asplit['2']);
             
             if($newbotflag == 'C')
                 $botflag = '6';
             else
                 $botflag = '3';
                 
            
             $results9 = $wpdb->get_results("SELECT * FROM $table_name where botnickname = '$botnickname' limit 1");
             if (count($results9) > 0 or empty($botnickname))
                continue;    

             $query = "INSERT INTO ".$table_name.
                 " (botnickname, botname, botstate, botflag)
                  VALUES ('"
                 .$botnickname.
                 "', '".
                 $botname .
                 "', 
                 'Enabled', '"
                 .$botflag . "')";
             $r = $wpdb->get_results($query); 
             
      } // End Loop   
        
        
          if (!feof($botshandle)) {
            // echo "Error: unexpected fgets() fail\n";
            return false;
          }
          
    } // end open
    
    
    fclose($botshandle);
    
} // end Function

function sbb_create_db()
{
    global $wpdb;
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "sbb_blacklist";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `botnickname` varchar(30) NOT NULL,
        `botname` text NOT NULL,
        `boturl` text NOT NULL,
        `botip` varchar(100) NOT NULL,
        `botobs` text NOT NULL,
        `botstate` varchar(10) NOT NULL,
        `botblocked` mediumint(9) NOT NULL,
        `botdate` timestamp NOT NULL,
        `botflag` varchar(1) NOT NULL,
        `botua` text NOT NULL,
    UNIQUE (`id`),
    UNIQUE (`botnickname`)
    ) $charset_collate;";
    // KEY `botnickname` (`botnickname`)
    dbDelta($sql);
}


function sbb_upgrade_db()
{
    global $wpdb;
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "sbb_blacklist";

    if(! stopbadbots_tablexist($table_name))
       return;

    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'botblocked'";
 
    $wpdb->query($query);
    if(empty($wpdb->num_rows)) { 
      $alter = "ALTER TABLE " . $table_name . " ADD botblocked mediumint(9) NOT NULL"; 
       ob_start();
       $wpdb->query($alter);
       ob_end_clean(); 
       
    }
    // Upgrade to new names
    //$stopbadbots_option_name[0] = 'stop_bad_bots_active';
    $stopbadbots_option_name[1] = 'my_blacklist';
    $stopbadbots_option_name[2] = 'my_email_to';
    $stopbadbots_option_name[3] = 'my_radio_report_all_visits';   
    for ($i = 1; $i < 4; $i++)
    {
     $stopbadbots_option = get_site_option($stopbadbots_option_name[$i]);
     $stopbadbots_new_name = 'stopbadbots_'.$stopbadbots_option_name[$i];
     add_site_option($stopbadbots_new_name,$stopbadbots_option);
     // update_site_option();
     delete_option( $stopbadbots_option_name[$i] );
     // For site options in Multisite
     delete_site_option( $stopbadbots_option_name[$i] );
    }   
}
function sbbmoreone($userAgentOri){
           global $sbb_found, $wpdb;
           require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
           $table_name = $wpdb->prefix . "sbb_blacklist"; 
           $query = "UPDATE " . $table_name . " SET botblocked = botblocked+1 WHERE botnickname = '".$sbb_found."'";
           $wpdb->query($query);
        }
function sbb_plugin_act_message() {
                echo '<div class="updated"><p>';
                $sbb_msg = '<img src="'.STOPBADBOTSURL.'/images/infox350.png" />';
                $sbb_msg .= '<h2>';
                $sbb_msg .= __('Stop Bad Bots Plugin was activated!','stopbadbots');
                $sbb_msg .= '</h2>';
                
                $sbb_msg .= '<h3>';
                $sbb_msg .= __('For details and help, take a look at Stop Bad Bots at your left menu','stopbadbots');
                $sbb_msg .= '<br />';
          
                
                $sbb_msg .= '  <a class="button button-primary" href="admin.php?page=stop-bad-bots">';
                $sbb_msg .= __('or click here','stopbadbots');
                $sbb_msg .= '</a>';
               // $sbb_msg .=  $sbb_url;
                echo $sbb_msg;
                echo "</p></h3></div>";
}
if(is_admin())
{
   if(get_option('sbb_was_activated', '0') == '1')
   {
     add_action( 'admin_notices', 'sbb_plugin_act_message' );
     $r =  update_option('sbb_was_activated', '0'); 
     if ( ! $r )
        add_option('sbb_was_activated', '0');
   } 
}
////////////////////
function sbb_add_admin_menu(  ) { 
	add_submenu_page( 
    'stop-bad-bots', // $parent_slug 
    'Bad Bots Table', // string $page_title
    'Add Bad Bot to Table', // string $menu_title 
    'manage_options', 
    'Add New Bad Bot', // Page Title
    'sbb_options_page' );
}
function sbb_settings_init(  ) { 
	register_setting( 'pluginPage', 'sbb_settings' );
	add_settings_section(
		'sbb_pluginPage_section', 
		__( 'Add new bad bot to the bad bots Table.', 'stopbadbots' ), 
		'sbb_settings_section_callback', 
		'pluginPage'
	);
	add_settings_field( 
		'sbb_text_field_0', 
		__( 'Bad Bot Nickname:', 'stopbadbots' ), 
		'sbb_text_field_0_render', 
		'pluginPage', 
		'sbb_pluginPage_section' 
	);
}
function sbb_text_field_0_render(  ) { 
	$options = get_option( 'sbb_settings' );
	?>
	<input type='text' name='sbb_settings[sbb_input_nickname]' value='<?php // echo $options['sbb_input_nickname']; ?>'>
	<?php
}
function sbb_settings_section_callback(  ) {
     
echo __( "In addiction to default system table, you can add one or more string to the default table.","stopbadbots"); 
echo '<br />';

echo __( "Example: SpiderBot (no case sensitive)","stopbadbots"); 
echo '&nbsp;';
echo __( "Just a piece of the name is enough.","stopbadbots");
echo '&nbsp;';
echo __( 'For example, if you put "bot" will block all bots with the string bot at user agent name.','stopbadbots'); 
echo '&nbsp;';
echo __( "Attention: In this case, you will block also google bot because their name is GoogleBot.","stopbadbots"); 
echo '<br />';
echo '<b>'; 
echo __('Do not use special characters.','stopbadbots');
echo '</b>'; 
echo '<br />';

echo __( "Add one bad bot each time. The table don't accept duplicate nicknames.", 'stopbadbots' );
}
function stopbadbots_admin_notice__success() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Bot included at table!', 'stopbadbots' ); ?></p>
    </div>
    <?php
}
function stopbadbots_admin_notice__fail() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Fail to include bot! Check bot nickname and remember Duplicates are not allowed. ', 'stopbadbots' ); ?></p>
    </div>
    <?php
}
function sbb_options_page(  ) { 
	?>
	<form action='options.php' method='post'>
		<h1>Stop Bad Bots Plugin</h1>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
      <?php sbb_update_db(); ?>     
	</form>
	<?php
}
function sbb_update_db(){
     Global $wpdb;   
     $table_name = $wpdb->prefix . "sbb_blacklist";
     
     if(! stopbadbots_tablexist($table_name))
       return;
     
     $options = get_option( 'sbb_settings' );
  if(isset($options['sbb_input_nickname']))
  {
     $nickname = $options['sbb_input_nickname'];
     $query = "INSERT INTO $table_name (botnickname,botname,botstate,botflag,botdate) VALUES ('$nickname','$nickname','Enabled', '1' , now())";
         if( ! empty ($nickname))
             $r =  $wpdb->query($query);
         else $r = false;
       if($r)
         stopbadbots_admin_notice__success();
       else
         stopbadbots_admin_notice__fail();
         // clear sbb_input_nickname
         unset($options['sbb_input_nickname']);
         update_option( 'sbb_settings', $options );
  }   
  return;
}

function check_db_sbb_blacklist()
{
     Global $wpdb;   
     $table_name = $wpdb->prefix . "sbb_blacklist";

     if(! stopbadbots_tablexist($table_name))
       return;
       
     $res = $wpdb->get_col("DESC {$table_name}", 0);
     $num_files = count($res); 
     if($num_files < 11)
     {
       $query =  'ALTER TABLE  `'.$table_name.'` 
       ADD  `botdate` TIMESTAMP NOT NULL,
       ADD  `botflag` VARCHAR( 1 ) NOT NULL,
       ADD  `botua` TEXT NOT NULL' ;
       $r =  $wpdb->query($query);
     }
      //delete +5Bot
      $query =  "DELETE FROM `".$table_name."` 
       WHERE botnickname = '+5Bot' LIMIT 1";
      $r =  $wpdb->query($query);
      
      $query =  "DELETE FROM  `".$table_name."` 
       WHERE  `botua` LIKE  '%wordpress%'";
      $r =  $wpdb->query($query); 
}      
      
      
function upload_new_bots()
{
     Global $wpdb;   
     $table_name = $wpdb->prefix . "sbb_blacklist";
     $query = 'select * from '.$table_name. ' where botflag = "2" or botflag = "1" ';
     $result = $wpdb->get_row($query);
     if(! $result)
       return;
     $id = $result->id;
     $ua = $result->botua;
     $ip = $result->botip;
     $date = $result->botdate;
     $nickname = $result->botnickname;
     $myarray = array( 
     'ua' => $ua,
     'ip' => $ip,
     'date' => $date,
     'nickname' => $nickname,
     'version' => STOPBADBOTSVERSION,
     );     
                    $url = "http://stopbadbots.com/api/httpapi.php";
                    $response = wp_remote_post( $url, array(
                    	'method' => 'POST',
                    	'timeout' => 45,
                    	'redirection' => 5,
                    	'httpversion' => '1.0',
                    	'blocking' => true,
                    	'headers' => array(),
                    	'body' => $myarray,
                    	'cookies' => array()
                        )
                    );
                     if ( is_wp_error( $response ) ) 
                        {
                         // $error_message = $response->get_error_message();
                         // echo "Something went wrong: $error_message";
                        }
                        else
                        {
                             $botflag = '4';
                             
                             if( !empty($ua) and !empty($ip))
                                $botglag = '6';
                                
                             $query = "update ".$table_name. " set botflag = '".$botflag."' WHERE id ='".$id."'";
                             $result = $wpdb->query($query);
                        }
}
function sbb_get_ua()
{
   $ua = strip_tags(trim($_SERVER['HTTP_USER_AGENT']));
   $ua = sbb_clear_extra($ua); 
   return $ua;
}

function sbb_clear_extra($mystring)
{
    $mystring = str_replace('$','S;',$mystring);  
    $mystring = str_replace('{','!',$mystring); 
    $mystring = str_replace('shell','chell',$mystring); 
    $mystring = str_replace('curl','kurl',$mystring);
    $mystring = str_replace('<','&lt;',$mystring);
    return $mystring;
}

function sbb_complete_bot_data($nickname)
{
   Global $wpdb;
   if(empty($nickname))
     return;
     $table_name = $wpdb->prefix . "sbb_blacklist";
     $query = 'select * from '.$table_name. ' where botnickname =  "'.$nickname.'" and botflag <> "6" limit 1';
     $result = $wpdb->get_row($query);
     if(!$result)
       return;
       
     $id = $result->id;
     $uadb = $result->botua;
     $ipdb = $result->botip;
     
     if ( empty($uadb) and empty($ipdb))
     {}
     else
       return;
       

     $ua = sbb_get_ua();
     $ip = sbb_findip();
         
     
     $maybe = false;
     if( empty($uadb) and !empty($ua))
       $maybe = true;
       
     
     if(empty($ipdb) and !empty($ip))
       $maybe = true;
       
     if($maybe)
       {}
     else
        return;
 
     $ua = json_encode($ua);       
       
     $sql = "update ".$table_name." SET 
     botua = '".$ua."',
     botip = '".$ip."',
     botflag = '2'
     WHERE
     id = '".$id."'
     limit 1";
     $result = $wpdb->query($sql);
     return;
}


if(get_option('stop_bad_bots_network','') == 'yes')
       add_action( 'plugins_loaded', 'sbb_chk_update' ); 

function sbb_chk_update()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "sbb_blacklist";
    $last_checked = get_option('stopbadbots_last_checked', '0');

    if ($last_checked == '0') {
        if (!add_option('stopbadbots_last_checked', time()))
            update_option('stopbadbots_last_checked', time());

        return;

    } elseif (($last_checked + (3 * 24 * 3600)) > time()) { 
        // 3 days
        return;
    }
    ob_start();


    $myarray = array(
    'last_checked' => $last_checked,
    'version' => STOPBADBOTSVERSION,
    );
    
    
    $url = "http://stopbadbots.com/api/httpapi.php";
    //$bot_nickname = 'test';
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 15,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()));


    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        // echo "Something went wrong: $error_message";
        ob_end_clean();
        return;
    }

    $r = trim($response['body']);
    $r = json_decode($r, true);

    $q = count($r);
    for ($i = 0; $i < $q; $i++) {

        $botnickname = trim($r[$i]['botnickname']);
        $botname = trim($r[$i]['botname']);
        $botip = trim($r[$i]['botip']);
        $botua = trim($r[$i]['botua']);
        
        if(empty($botnickname) or empty($botname) or empty($botip) or empty($botua))
           continue;

        // delete
        if($botip == '-1')
        {
          $query = "DELETE FROM  ".$table_name." WHERE botnickname = '".$botnickname."' LIMIT 1";
          $ret = $wpdb->get_results($query);       
          continue;
        }
        else
        {
            
            $query = "select COUNT(*) from " . $table_name .
                " WHERE botnickname = '".$botnickname. "' LIMIT 1";
                
            if($wpdb->get_var($query) > 0)
              continue;
    
    
            $query = "INSERT INTO " . $table_name .
                " (botnickname, botname, botip, botua, botstate, botflag)
              VALUES ('" . $botnickname . "', '" . $botname . "', '" . $botip .
                "', '" . $botua . "', 'Enabled', '9')";
               
            $ret = $wpdb->get_results($query);
        }    

    }

    if (!add_option('stopbadbots_last_checked', time()))
        update_option('stopbadbots_last_checked', time());

    ob_end_clean();
} 

function sbbcrawlerDetect($userAgent)
{
    global $wpdb, $sbb_found, $stopbadbotsip, $userAgentOri;
    
    $foundit = strpos( $userAgent, 'wordpress');
    if($foundit !== false)
      return false;
    
    $current_table = $wpdb->prefix . 'sbb_blacklist';
    $result = $wpdb->get_results("SELECT botnickname, id FROM $current_table WHERE `botstate` LIKE 'Enabled' ");
    
    $sbb_found = ''; 
        
    foreach( $result as $results ) {
        
       $botnickname = trim($results->botnickname);
       
        if (strlen($botnickname) < 3)
            continue;
        if (stripos($userAgent, $botnickname) !== false)
              $sbb_found = $botnickname;
        }
    
       
    if (! empty($sbb_found))
         return true;
        
    ////////// / New        
        
        
    // not found
    $lookfor = array(
        'bot',
        'crawler',
        'spider',
        'link',
        'fetcher',
        'scanner',
        'grabber',
        'collector',
        'capture',
        'seo',
        '.com');

    $maybefoundbot = false;
    for ($i = 0; $i < count($lookfor); $i++) {
        $foundit = strpos($userAgent, strtolower($lookfor[$i]));
        if ($foundit !== false) {
            $maybefoundbot = true;
            break;
        }
    }


    if ($maybefoundbot == false)
        return false;

    // else have bot at ua


    $agentsok = array(
        'googlebot',
        'adsbot-google',
        'mediapartners-google',
        'slurp',
        'msnbot',
        'bingbot',
        'baidu',
        'CUBOT_NOTE',
        'yandex',
        'Seznam',
        'Voila',
        'Facebook',
        'Twitter',
        'LinkedIn',
        'ExaLead',
        'YellowPages',
        'Ichiro-Goo',
        'BaiduSpider',
        'Yeti_Naver',
        'Plukkie',
        'Vagabondo_WiseGuys',
        'Wikimedia',
        'EntireWeb',
        'Ezine',
        'ScoutJet',
        'ShopWiki',
        'Google-analytics.com',
        'windows nt',
        'yahoo',
        'live',
        'Tripadvisor');

    for ($i = 0; $i < count($agentsok); $i++) {
        $foundit = stripos($userAgent, $agentsok[$i]);
        if ($foundit !== false)
            return false;
    }

 
 
        // Bloqueia alguns nicknames
        $anickko = array(
            'silk',
            'LCC',
            'BLA',
            'SiteUptime.com',
            'Galaxy',
            'FDM');

        for ($i = 0; $i < count($anickko); $i++) {
            $foundit = stripos($sbb_found, $anickko[$i]);
            if ($foundit !== false)
                return false;
        }
        

    
      //Especificos
      $auako2 = array(
            'Ant',
            '2ip',
            'AHC',
            'git');

      for ($i = 0; $i < count($auako2); $i++) {
            if (trim($sbb_found) == trim($auako2[$i]))
                return false;
      }
        
    
    $nickname = (string )time();

    $myarray = array(
    'ua' => $userAgentOri,
    'botip' => $stopbadbotsip,
    'nickname' => $nickname,
    'version' => STOPBADBOTSVERSION,
    );

            
    
    if(empty($userAgentOri) or empty($stopbadbotsip) or empty($nickname) )
      return false;

    ob_start();
            
    $url = "http://stopbadbots.com/api/httpapi.php";
    
    $response = wp_remote_post($url, array(
    'method' => 'POST',
    'timeout' => 20,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'body' => $myarray,
    'cookies' => array())); 

    ob_end_clean();
    
    return false;
}

function stopbadbots_tablexist($table)
{
 global $wpdb;
 $table_name = $table;

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) 
           return true;
    else
    return false;
}

?>