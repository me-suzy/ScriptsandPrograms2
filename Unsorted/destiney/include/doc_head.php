<?php

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title> .: $site_title :.</title>
<meta name="keywords" content="rated,rate,amatuer,pictures,blonde,brunette,redhead,babes,dudes,members,mysql,php">
<meta name="description" content="$site_title is an amatuer image rating site.  Members upload their images and have them rated by other site visitors.">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="$base_url/favicon.ico">
<script language="javascript" type="text/javascript">
var now = new Date(), x;
now.setTime(now.getTime() + 30 * 24 * 60 * 60 * 1000);
now = now.toGMTString();
x = document.cookie.toLowerCase().indexOf("bookmark");
if(x == -1){
document.cookie = 'bookmark = This cookie is good for one year.; expires=' + now + ';';
if(parseInt(navigator.appVersion, 10) >= 4 && navigator.appName.toLowerCase().indexOf("explorer") > -1)
window.external.AddFavorite('$base_url/', document.title);
}
</script>
EOF;

?>