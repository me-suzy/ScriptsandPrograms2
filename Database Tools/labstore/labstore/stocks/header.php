<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-control: private");
include 'config.php';
// taking care of magic quotes gpc
function stripslashes_recursive($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = stripslashes_recursive($val);
        }
        return $value;
    } else {
        return stripslashes($value);
    }
}

if (get_magic_quotes_gpc()) {
    // Recursively apply stripslashes() to all data
    $_GET = stripslashes_recursive($_GET);
    $_POST = stripslashes_recursive($_POST);
    $_COOKIE = stripslashes_recursive($_COOKIE);
    $_REQUEST = stripslashes_recursive($_REQUEST);
}

// used during mysql insertion
function add_slashes($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = add_slashes($val);
        }
        return $value;
    } else {
        return addslashes($value);
    }
}

function strip_slashes($value) {
    if (is_array($value)) {
        foreach ($value as $index => $val) {
            $value[$index] = strip_slashes($val);
        }
        return $value;
    } else {
        return stripslashes($value);
    }
}
$connection = mysql_connect($host, $user, $pass) or
die("ERROR: Could not connect to the MySQL server! Either it is down or the username/password used for connecting to it as specified in the config.php file are incorrect. If the MySQL server is running, it will respond with an error message (below)<br /><br />".mysql_error()."</p>"); 
$selected = mysql_select_db($db_name, $connection) or die("ERROR: Could not select the MySQL database! The MySQL account being used (as specified in the config.php file) may not have access privileges.<br /><br />".mysql_error()."</p>");
// Get client's IP address
if ($all_see_tables == "no" or $all_affect_items == "no")
{
if (empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
 {$IP = $_SERVER["REMOTE_ADDR"];}
  else {$IP = $_SERVER["HTTP_X_FORWARDED_FOR"];} 
$lh = gethostbyaddr($_SERVER['REMOTE_ADDR']);
// Test that the address is allowed
$test=$IP.".".$lh;
if(in_array($test, $allowed) || in_array($IP, $allowed))
 {$client = "allowed";}
 else {$client = "not_allowed";}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<title><?php echo ($mainsite_name.' - '.$parentsite_name); ?></title>
<style type="text/css" media="all">
/*<![CDATA[*/
@import "<?php echo ($parentsite_url); ?>style.css";
/*]]>*/
</style>
<meta name="Description" content="<?php echo ($meta_description); ?>" />
<meta name="Keywords" content="<?php echo ($meta_keywords); ?>" />
<meta name="Generator" content="<?php echo ($meta_generator); ?>" />
<script type="text/javascript">
//<![CDATA[
<!--//
function popitup(url)
{
window.name='main';
newwindow=window.open(url,'poppedfirst','height=400,width=400,scrollbars=yes,resizable=yes');
if (window.focus) {newwindow.focus()}
return false;
}
function confipop(url)
{
var agree = confirm ("Are you sure?");
if (agree)
 {
newwindow=window.open(url,'poppedfirst','height=200,width=200,scrollbars=no,resizable=no');
 if (window.focus) {newwindow.focus()}
 }
return false;
}
// -->
//]]>
</script>
<?php
// module specific extra for head element - comes from module specific php file
echo ($head_extra.'</head>
<body>
<div style="padding-left: 5px;">
<h1 class="onlyscreen">
<a href="'.$mainsite_url.'">'.$mainsite_name.'</a> - <a href="'.$parentsite_url.'">'.$parentsite_name.'</a></h1>'); 
$date = date("l, F j, Y");
// get log_status - if authentication enabled
// show logout
if ($enable_authentication === 1 or $enable_admin_authentication === 1)
{
 if (isset($_SESSION['logged_user_infos_ar']['username_user']))
 {$log_status = '<a href="'.$site_url.$dadabik_login_file.'?function=logout&amp;go_to=parent_front">Log out - '.$_SESSION['logged_user_infos_ar']['username_user'].'</a> || ';}
}
else
{
$log_status = '';
}
// part for header on modules' pages, e.g., proteins.php
// depending on activated modules (config.php)
$header_to_show = $log_status;
foreach ($modules_array as $key=>$value)
{
$header_to_show .= '<a href="'.$parentsite_url.'modules/'.$value[3].'" title="'.$value[2].'">';
if ($value[4] == $table){$header_to_show .= '<b>'.$value[1].'</b>';}
else {$header_to_show .= $value[1];}
$header_to_show .= '</a>/'; 
}
$header_to_show = substr($header_to_show, 0, -1); // remove last /
?>