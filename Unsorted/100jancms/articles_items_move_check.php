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
$Cookie_1 = $_COOKIE["move_source"];
$Cookie_2 = $_COOKIE["move_target"];

if ($Cookie_1=="" or $Cookie_2=="" or $Cookie_1=="null" or $Cookie_2=="null" or $Cookie_1==$Cookie_2) {$error=1;} else {$error=0;} //are cookies available?

if ($error==0) {
//get source article
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$Cookie_1;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

if ($row<>"") {
	$title1=$row["title"];
	$date1=$row["date"];
	$date1_human=date('j. F Y / H:i',"$date1");
	$priority1=$row["priority"];	
	}	
	else {$error=1;}

//get target article
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$Cookie_2;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

if ($row<>"") {
	$title2=$row["title"];
	$date2=$row["date"];
	$date2_human=date('j. F Y / H:i',"$date2");
	$priority2=$row["priority"];
	}	
	else {$error=1;}

}

if ($error==0) {

$new_priority=$priority2; //set priority to be same as target

echo '
<form action="articles_items_move_exe.php" method="post" enctype="multipart/form-data" name="addform" >

<!-- pass values to exe page -->
<input name="Cookie_1" type="hidden" value="'.$Cookie_1.'">
<input name="date2" type="hidden" value="'.$date2.'">
<input name="new_priority" type="hidden" value="'.$new_priority.'">

<span class="maintext">Are you sure you want to MOVE the following article? <br>
Article time stamp will be permanently changed.
<span class="maintext2"></span><br>
<span class="maintext"><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<strong>Warning:</strong>&nbsp;&nbsp;This operation can not be reverted!</span>
<br><br><hr width="75%" size="1" noshade color="000000" align="left">
Moving article:<br>
<span class="maintext2"><b>- "'.$title1.'"</b></span> ('.$date1_human.')
<br>

  <select name="pos" class="formfields" id="pos"style="width:100">
    <option value="plus">in front of</option>
    <option value="minus">after</option>
  </select>
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
<span class="maintext2"><b>- "'.$title2.'"</b></span> ('.$date2_human.')
<br>
<hr width="75%" size="1" noshade color="000000" align="left"><br><span class="maintext">Proceed?</span>
<br><br>
<input name="submit" type="submit" class="formfields2" id="submit" style="width: 75px; height: 26px;" value="OK ->">
&nbsp;&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">
</form>
<script language="JavaScript" type="text/JavaScript">
document.addform.submit.focus();
</script>
';

}
else {
echo '
<span class="red">Error:</span>&nbsp;&nbsp;Unable to comply. Article has NOT beed moved. <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"> 
<br>
Possible reasons:<br>
- cookies were flushed<br>
- cookies expired<br>
- source and target article are the same<br>
- source or target article was deleted 
<br>
<br>
<br>
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Back" onClick="cancel_go()">
';
}	
?>


<script language="JavaScript" type="text/JavaScript">
//clear cookies
DeleteCookie("move_source","cookie expired",100);
DeleteCookie("move_target","cookie expired",100);
</script>

</body>
</html>
