<?php /**
 * @author William Sergio Minossi
 * @copyright 2017
 */
$memory['limit'] = (int)ini_get('memory_limit');
if ($memory['limit'] > 9999999)
    $memory['limit'] = ($memory['limit'] / 1024) / 1024;
$memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage
    () / 1024 / 1024, 0) : 0;
// $memory['percent'] = round ($memory['usage'] / $memory['limit'] * 100, 0);
if (!is_numeric($memory['usage'])) {
    $sbb_memory = 'Unable to Check!';
    return;
}
if (!is_numeric($memory['limit'])) {
    $sbb_memory = 'Unable to Check!';
    return;
}
if (!is_numeric($memory['usage'])) {
    $sbb_memory = 'Unable to Check!';
    return;
}
if ($memory['usage'] < 1) {
    $sbb_memory = 'Unable to Check!';
    return;
}
$msg_type = 'notok';
if (defined("WP_MEMORY_LIMIT")) {
    $memory['wp_limit'] = trim(WP_MEMORY_LIMIT);
    $wplimit = $memory['wp_limit'];
    $wplimit = substr($wplimit, 0, strlen($wplimit) - 1);
    $memory['wp_limit'] = $wplimit;
} else
    $memory['wp_limit'] = 'Not defined!';
if (!is_numeric($memory['wp_limit'])) {
    $sbb_memory = 'Unable to Check!';
    return;
}
$sbb_memory = '<big><br />';
$mb = 'MB';
$sbb_memory .= '<strong>';
$sbb_memory .= 'Current memory WordPress Limit: ' . $memory['wp_limit'] . $mb .
    '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;   Your usage: ' . $memory['usage'] .
    'MB &nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;   Total Server Memory: ' . $memory['limit'] .
    'MB';
$sbb_memory .= '</strong>';

if (!function_exists('memory_status_plugin')) {
    function memory_status_plugin()
    {
        $r = false;
        if (defined("WP_MEMORY_LIMIT")) {
            $wplimit = trim(WP_MEMORY_LIMIT);
            $wplimit = substr($wplimit, 0, strlen($wplimit) - 1);
            if ($wplimit >= 128)
                $r = true;
        }
        return $r;
    }
}

$sbb_memory .= '<br /><br /> ';
if (!memory_status_plugin()) {
    $perc = $memory['usage'] / $memory['wp_limit'];
    if ($perc > .7)
        $sbb_memory .= '<span style="color:red;">';
    $sbb_memory .= '<strong>We suggest increase WordPress Memory Limit to 128M or more.</strong>';
    if ($perc > .7)
        $sbb_memory .= '</span>';
    $sbb_memory .= '<br />';
    if ($memory['limit'] < 128) {
        if ($memory['usage'] > 100)
            $sbb_memory .= '<strong>We suggest increase your Server Memory to 256M or more.</strong>';
        else
            $sbb_memory .= '<strong>We suggest increase your Server Memory to 128M or more.</strong>';
        $sbb_memory .= '<br />';
    }
    $memory_limit = (int)$memory['limit'];
}
$sbb_memory .= '<br />';
$sbb_memory .= "To increase the WordPress memory limit, add this info to your file wp-config.php (located at root folder of your server)   
    <br />
    (just copy and paste)
    <br />    <br />
<strong>    
define('WP_MEMORY_LIMIT', '128M');
</strong>
    <br />    <br />
    before this row:
    <br />
    /* That's all, stop editing! Happy blogging. */
    <br />
    <br />
    If you need more, just replace 128 with the new memory limit.
    <br /> 
    To increase your total server memory, request it to your hosting company.
    <br />   <br />
    <hr />
    <br />    
<strong>    How to Tell if Your Site Needs a Shot of more Memory:</strong>
        <br />    <br />
    If your site is behaving slowly, or pages fail to load, you 
    get random white screens of death or 500 
    internal server error you may need more memory. 
Several things consume memory, such as WordPress itself, the plugins installed, the 
theme you're using and the site content.
     <br />  
Basically, the more content and features you add to your site, 
the bigger your memory limit has to be.
if you're only running a small 
site with basic functions without a Page Builder and Theme 
Options (for example the native Twenty Sixteen). However, once 
you use a Premium WordPress theme and you start encountering 
unexpected issues, it may be time to adjust your memory limit 
to meet the standards for a modern WordPress installation.
     <br /> <br />    
    Increase the WP Memory Limit is a standard practice in 
WordPress and you find instructions also in the official 
WordPress documentation (Increasing memory allocated to PHP).
    <br /><br />
Here is the link:    
<br /> ";
$sbb_memory .= '<a href="https://codex.wordpress.org/Editing_wp-config.php" target="_blank">https://codex.wordpress.org/Editing_wp-config.php</a>';
$sbb_memory .= '</big>';
