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
  '1' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 




	//loged user full name
	$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
	$result=mysql_query($query);
	$row = mysql_fetch_array($result); //wich row

	$user_privileges=$row["user_privileges"];

//loading COMMENTS privileges for current user
//****************************************************************************
$query="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

$brojac=0;
for ($i=0;$i<$num;$i++) {

		$comments=mysql_result($result,$i,"marker");

//list allowed comments
if (substr_count($user_privileges, "COMMENTS["."$comments"."]")<>"0") { 
if ($brojac>0) {$to_query_c=$to_query_c." or marker='".$comments."'";} else {$to_query_c=" AND (marker='".$comments."'";}
$brojac++;
	}
}
//closing )
$to_query_c=$to_query_c.")";

//****************************************************************************

//count comments for approval
$query="SELECT * FROM ".$db_table_prefix."comments WHERE approval=0 ".$to_query_c;
$result=mysql_query($query);
$num_cfa=mysql_numrows($result); //how many rows

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
fixed
}
</style>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.searchform.submit.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Comments: View/Edit Comments: <span class="titletext0blue">Search 
      Comments</span></td>
  </tr>
</table>
<br>
<br>

<form name="searchform" action="comments_list.php" method=post >

  <b>Comments:</b><br>
  </strong></span><span class="maintext">
  <input name="c_state" type="radio" value="approved" align="absmiddle" <?php  if ($num_cfa==0) {echo "checked";}?> >
  Approved</span> <span class="maintext"><strong> 
  <input name="c_state" type="radio" align="absmiddle" value="for approval" <?php if ($num_cfa>0) {echo 'title="There is currently '.$num_cfa.' comment(s) vaiting for approval." checked';}?> >
  </strong>For approval</span>
  <br>
  <?php 
  if ($num_cfa>0) {
  echo '<img src="images/app/i16.gif" width="16" height="16" align="absmiddle">&nbsp;<strong>Info:</strong>&nbsp;There is currently <strong>'.$num_cfa.'</strong> comment(s) vaiting for approval.';
  }
  ?>
  <br>
  <br>
  <span class="maintext"><strong>Comment text:</strong></span> 
  <br>
    
  <input name="c_text" type="text" class="formfields" id="c_text" size="45">
    <br>
    <strong><span class="maintext">Marker:</span></strong><br>
    
  <select name="marker" class="formfields" id="marker" style="width:255">
    <option value=""></option>
  <?php 

//kill old session
unset($_SESSION["search_query_comments"]);



$query="SELECT marker FROM ".$db_table_prefix."articles_marker ORDER BY marker";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

$brojac=0;
for ($i=0;$i<$num;$i++) {

$comments=mysql_result($result,$i,"marker");

if (substr_count($user_privileges, "COMMENTS["."$comments"."]")<>"0") 
{ 
echo '
<option value="'.$comments.'">ARTICLES > '.$comments.'</option>
';

//to_query
if ($brojac>0) {$to_query=$to_query." or marker='".$comments."'";} else {$to_query=" marker='".$comments."'";}
$brojac++;
}

} //for


?>

	</select>
  <span class="maintext"><br>
  <br>
  Show 
  <select name="pagelimit" class="formfields" id="select">
    <option value="5">5</option>
    <option value="10">10</option>
    <option value="20" selected>20</option>
    <option value="30">30</option>
    <option value="40">40</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="200">200</option>
    <option value="300">300</option>
    <option value="500">500</option>	
    <option value="1000">1000</option>	
  </select>
  results per page.</span> 
<br>
<br>
<br>
<br>
    <input name="to_query" type="hidden" value="<?php echo "$to_query";?>">
    <input type="submit" name="submit" value="Search comments ->" style="width: 115px; height: 26px;" class="formfields2">

</form>

<br>
<br>
<br>

</body>
</html>
