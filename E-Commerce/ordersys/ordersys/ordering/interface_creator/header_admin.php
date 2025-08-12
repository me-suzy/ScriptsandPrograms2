<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-control: private");
$admin_check = 1;
include ('check_login.php');
if ($enable_admin_authentication ===1 and $current_user_is_administrator ===0)
{
 // get asked for current url
 $_SERVER['FULL_URL'] = 'http';
 if($_SERVER['HTTPS']=='on'){$_SERVER['FULL_URL'] .=  's';}
 $_SERVER['FULL_URL'] .=  '://';
 if($_SERVER['SERVER_PORT']!='80'){$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];}
 else{$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];}
 if($_SERVER['QUERY_STRING']>' '){$_SERVER['FULL_URL'] .=  '?'.$_SERVER['QUERY_STRING'];}
 // end get url
 header ('Location: '.$site_url.$dadabik_login_file.'?function=check_admin_login&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
 die();
}
if (isset($_GET['table_name']))
{
$table_name_1 = $_GET['table_name'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" />
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-us" />
<title>
<?php
if($mainsite_name != '')
{echo ($mainsite_name.' - ');}
if($parentsite_name != '')
{echo ($parentsite_name.' - ');}
echo $site_name; 
?>  - Administration</title>
<link rel="stylesheet" href="../style.css" type="text/css" />
<meta name="Description" content="<?php echo ($meta_description); ?>" />
<meta name="Keywords" content="<?php echo ($meta_keywords); ?>" />
<meta name="Generator" content="<?php echo ($meta_generator); ?>" />
<script language="JavaScript" type="text/javascript">
//<![CDATA[
<!--
function popitup(url)
{
window.name='main';
newwindow=window.open(url,'poppedfirst','height=400,width=400,scrollbars=yes,resizable=yes');
if (window.focus) {newwindow.focus()}
return false;
}
function fill_cap(city_field){

   temp = 'document.contacts_form.'+city_field+'.value';

   city = eval(temp);
	cap=open("fill_cap.php?city="+escape(city),"schermo","toolbar=no,directories=no,menubar=no,width=170,height=250,resizable=yes");
}
function checkCR(evt) {

    var evt  = (evt) ? evt : ((event) ? event : null);

    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);

    if ((evt.keyCode == 13) && (node.type=="text")) {return false;}

  }

  document.onkeypress = checkCR;
//]]>
</script>
</head>
<body>
<h1 class="onlyscreen">
<?php
if($mainsite_name != '')
{echo ($mainsite_name.' - ');}
if($parentsite_name != '')
{echo ($parentsite_name.' - ');}
echo $site_name; 
?></h1>
<p><?php
if ($enable_authentication === 1 or $enable_admin_authentication === 1)
{
if (isset($_SESSION['logged_user_infos_ar']['username_user'])){echo ('<a href="'.$dadabik_login_file.'?function=logout">'.$login_messages_ar['logout'].' - '.$_SESSION['logged_user_infos_ar']['username_user'].'</a> | ');}
else {echo ('<a href="'.$dadabik_login_file.'">'.$login_messages_ar['login'].'</a> | ');}
}
if($parentsite_name != '')
{echo ('<a href="'.$parentsite_url.'">'.$parentsite_name.'</a> | ');}

echo ('<a href="'.$dadabik_main_file.'">'.$site_name.' - data browser</a> | <a href="admin.php">'.$site_name.' - administration home page</a> | <a href="help.htm" onclick="return popitup(\'help.htm\')">Administration help</a> | ');

if($mainsite_name != '')
{echo ('<a href="'.$mainsite_url.'">'.$mainsite_name.'</a>');}
?></p>