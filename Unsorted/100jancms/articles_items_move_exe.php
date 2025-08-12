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
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';


//receive posted data
$Cookie_1=$_POST["Cookie_1"];
$date2=$_POST["date2"];
$new_priority=$_POST["new_priority"];
$pos=$_POST["pos"];

?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed;
}
</style>

<script language="JavaScript" type="text/JavaScript">
function DeleteCookie(cookieName,cookieValue,nMinutes) {
 var today = new Date();
 var expire = new Date();
 if (nMinutes==null || nMinutes==0) nMinutes=1;
 expire.setTime(today.getTime() - 60000*nMinutes);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

function cancel_go()
{
	this.location="articles_items_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" >
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles:<span class="titletext0blue"> 
      Move Article</span></td>
  </tr>
</table>
<br>
<br>
<?php 
if ($pos=="plus") {$moved_date=$date2+1;}
if ($pos=="minus") {$moved_date=$date2-1;}

//update data
$query = "UPDATE ".$db_table_prefix."articles_items SET date='$moved_date', priority='".$new_priority."' WHERE idArtc='".$Cookie_1."'";
mysql_query($query);

echo '
<span class="maintext"><strong>&nbsp;&nbsp;Status:</strong> Article has been moved!</span>&nbsp;&nbsp;<img src="images/app/all_good.jpg" width="16" height="16">
<meta http-equiv="Refresh" content="3; url=articles_items_search.php">
';

?>


<script language="JavaScript" type="text/JavaScript">
//clear cookies
DeleteCookie("move_source","cookie expired",100);
DeleteCookie("move_target","cookie expired",100);
</script>

</body>
</html>
