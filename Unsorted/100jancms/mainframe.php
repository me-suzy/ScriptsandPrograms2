<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>100janCMS Articles Control</title>
<link REL = "SHORTCUT ICON" href="images/app/icon.ico">
<?php echo "$text_encoding"; ?>

<script language="JavaScript" type="text/JavaScript">
function DeleteCookie(cookieName,cookieValue,nMinutes) {
 var today = new Date();
 var expire = new Date();
 if (nMinutes==null || nMinutes==0) nMinutes=1;
 expire.setTime(today.getTime() - 60000*nMinutes);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

function cc() {
//clear cookies
DeleteCookie("move_source","cookie expired",100);
DeleteCookie("move_target","cookie expired",100);
}
</script>

</head>

<frameset cols="198,*" frameborder="NO" border="0" framespacing="0"  onUnload="cc();">
  <frame src="menu.php" name="leftFrame" scrolling="auto" noresize>
  <frame src="home.php" name="mainFrame">
</frameset>
<noframes><body>

</body></noframes>
</html>
