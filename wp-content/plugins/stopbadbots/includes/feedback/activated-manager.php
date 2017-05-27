<?php Namespace StopBadBotsPlugin_activate{
 
    if( is_multisite())
       return;
       
if ( __NAMESPACE__ == 'BoatDealerPlugin_activate')
    {
     $BILLPRODUCT = 'BOATDEALERPLUGIN' ;
     $BILLPRODUCTNAME = 'Boat Dealer Plugin';
     $BILLPRODUCTSLANGUAGE = 'boatdealer' ; 
     $BILLPRODUCTPAGE = 'settings' ;
     $BILLCLASS = 'ACTIVATED_'.$BILLPRODUCT;
     $BILL_OPTIN = strtolower($BILLPRODUCT).'_optin'; 
     $PRODUCT_URL = BOATDEALERPLUGINURL; 
     $PRODUCTVERSION = BOATDEALERPLUGINVERSION; 
 } 
if ( __NAMESPACE__ == 'AntiHackerPlugin_activate')
    {
     $BILLPRODUCT = 'ANTIHACKERPLUGIN' ;
     $BILLPRODUCTNAME = 'Anti Hacker Plugin';
     $BILLPRODUCTSLANGUAGE = 'antihacker' ; 
     $BILLPRODUCTPAGE = 'anti-hacker' ;
     $BILLCLASS = 'ACTIVATED_'.$BILLPRODUCT;
     $BILL_OPTIN = strtolower($BILLPRODUCT).'_optin'; 
     $PRODUCT_URL = ANTIHACKERURL;
     $PRODUCTVERSION = ANTIHACKERVERSION; 
}
if ( __NAMESPACE__ == 'ReportAttacksPlugin_activate')
    { 
     $BILLPRODUCT = 'REPORTATTACKS' ;
     $BILLPRODUCTNAME = 'Report Attacks Plugin';
     $BILLPRODUCTSLANGUAGE = 'reportattacks' ; 
     $BILLPRODUCTPAGE = 'report-attacks' ;
     $BILLCLASS = 'ACTIVATED_'.$BILLPRODUCT;
     $BILL_OPTIN = strtolower($BILLPRODUCT).'_optin'; 
     $PRODUCT_URL = REPORTATTACKSURL;
     $PRODUCTVERSION = REPORTATTACKSVERSION; 
}
if ( __NAMESPACE__ == 'StopBadBotsPlugin_activate')
    { 
     $BILLPRODUCT = 'STOPBADBOTS' ;
     $BILLPRODUCTNAME = 'Stop Bad Bots Plugin';
     $BILLPRODUCTSLANGUAGE = 'stopbadbots' ; 
     $BILLPRODUCTPAGE = 'stop-bad-bots' ;
     $BILLCLASS = 'ACTIVATED_'.$BILLPRODUCT;
     $BILL_OPTIN = strtolower($BILLPRODUCT).'_optin'; 
     $PRODUCT_URL = STOPBADBOTSURL;
     $PRODUCTVERSION = STOPBADBOTSVERSION; 
}
        
if( isset( $_GET[ 'page' ] ) ) 
    {
       if(strip_tags($_GET[ 'page' ]) != $BILLPRODUCTPAGE)    
       return;
    }
else
   return;
   
  $host_name = trim(strip_tags($_SERVER['HTTP_HOST']));
  $host_name = strtolower($host_name);
  if(isset($_COOKIE[$BILLCLASS]))
     {
      $mycookie = strip_tags($_COOKIE[$BILLCLASS]);
      $pieces = explode("-", $mycookie);
      $cookie_domain = strip_tags(trim($pieces[1]));
      $activated = '';
          if(! empty($cookie_domain))
          {
            $pos = strpos($cookie_domain,$host_name);
            if( $pos !== false)
              $activated = strip_tags($pieces[0]);
          }
        if($activated == '0' or $activated == '1' )
        {
            if ( get_option( $BILL_OPTIN ) !== false ) {
                // The option already exists, so we just update it.
                update_option( $BILL_OPTIN, $activated );
            } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                add_option( $BILL_OPTIN, $activated );
            }
        }
       @setcookie($BILLCLASS, "", time() - 3600);  
     } // Cookie exist
     else
     {
           if ( get_option( $BILL_OPTIN ) !== false ) {
                $activated =  get_option($BILL_OPTIN, '') ;
            } 
     }
     
 // $activated = '';

  if(!isset($activated))
      $activated = '';  
     
  if($activated == '')
  {
    wp_register_script( $BILLCLASS,$PRODUCT_URL.'includes/feedback/activated-manager.js' , array( 'jquery' ), $PRODUCTVERSION, true );
    wp_enqueue_script( $BILLCLASS );
    wp_register_style( $BILLCLASS, $PRODUCT_URL. 'includes/feedback/feedback-plugin.css' );
    wp_enqueue_style( $BILLCLASS );
        
    $wpversion = get_bloginfo('version');
    $current_user = wp_get_current_user();
    $plugin = plugin_basename(__FILE__); 
    $email = $current_user->user_email;
    $username =  trim($current_user->user_firstname);
    $user = $current_user->user_login;
    $user_display = trim($current_user->display_name);
    if(empty($username))
       $username = $user;
    if(empty($username))
       $username = $user_display;
    $theme = wp_get_theme( );
    $themeversion = STOPBADBOTSVERSION; // $theme->version ;
    $memory['limit'] = (int) ini_get('memory_limit') ;	
    $memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 0) : 0;
    if (defined('WP_MEMORY_LIMIT')) 
        $memory['wplimit'] =  WP_MEMORY_LIMIT ;
    else
        $memory['wplimit'] = '';
        
  ?>
  <div class="<?php echo $BILLCLASS;?>"  style="display:block" >
              <div class="bill-vote-gravatar"><a href="http://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="Bill Minozzi" width="70" height="70"></a></div>
		    	<div class="bill-vote-message">
                 <h4>Hey  <?php echo strtoupper($username);?></h4>
                 <br />
                 <?php _e("Hi, my name is Bill Minozzi, and I am developer of",$BILLPRODUCTSLANGUAGE);
                 echo ' '.$BILLPRODUCTNAME.'.'; ?>
                 <br />
                 Please help us improve our plugin.
                 If you opt-in, some not sensitive data about your usage of the plugin
                 will be sent to us just one time. If you skip this, that's okay!
                 <?php echo ' '.$BILLPRODUCTNAME.''; ?>
                 will still work just fine. 
                 <br /><br />             
                 <strong><?php _e("Thank You!",$BILLPRODUCTSLANGUAGE);?></strong>
                 <br /><br /> 
                 <br /><br /> 			
		    			<a href="#" class="button button-primary <?php echo $BILLCLASS;?>-close-submit"><?php _e("Yes, Submit",$BILLPRODUCTSLANGUAGE);?></a>
                        <img alt="aux" src="/wp-admin/images/wpspin_light-2x.gif" id="imagewait" style="display:none" />
		    			<a href="#" class="button button-Secondary <?php echo $BILLCLASS;?>-close-dialog"><?php _e("Skip",$BILLPRODUCTSLANGUAGE);?></a>
                        <input type="hidden" id="version" name="version" value="<?php echo $themeversion;?>" />
		                <input type="hidden" id="email" name="email" value="<?php echo $email;?>" />
		                <input type="hidden" id="username" name="username" value="<?php echo $username;?>" />
		                <input type="hidden" id="produto" name="produto" value="<?php echo $BILLPRODUCTNAME;?>" />
		                <input type="hidden" id="wpversion" name="wpversion" value="<?php echo $wpversion;?>" />
		                <input type="hidden" id="limit" name="limit" value="<?php echo $memory['limit'];?>" />
		                <input type="hidden" id="wplimit" name="wplimit" value="<?php echo $memory['wplimit'];?>" />
   		                <input type="hidden" id="usage" name="usage" value="<?php echo $memory['usage'];?>" />
		                <input type="hidden" id="billclass" name="billclass" value="<?php echo $BILLCLASS;?>" />

                 <br /><br />
               </div>
    </div>
  <?php  
    if ( get_option( $BILL_OPTIN ) === false ) {
          add_option( $BILL_OPTIN, '0' );
    }
  } 

} // end Namespace
?>