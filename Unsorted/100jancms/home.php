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

<script language="JavaScript" type="text/JavaScript">
function logout_go()
{
	top.location="logout.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" BORDER-BOTTOM:"#00599b 1px solid">
  <tr> 
    <td class="titletext0">100jan<span class="titletext0">CMS </span><span class="titletext0blue">Articles 
      Control</span></td>
  </tr>
</table>
<?php 
//loged user full name
$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row
//assign variables
$full_name=$row["full_name"];
$user_privileges=$row["user_privileges"];

//loading ARTICLES privileges for current user
//****************************************************************************
		    //load all markers
			$query2="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
			$result2=mysql_query($query2);
			$num2=mysql_numrows($result2); //how many rows

$brojac=0;
for ($i=0;$i<$num2;$i++) {

			$marker=mysql_result($result2,$i,"marker");

//list allowed markers
if (substr_count($user_privileges, "ARTICLES[$marker]")<>"0") {
if ($brojac>0) {$to_query_a=$to_query_a." or marker='".$marker."'";} else {$to_query_a=" AND (marker='".$marker."'";}
$brojac++;
	}
}
//closing )
$to_query_a=$to_query_a.")";

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



//count expired articles
$now_date=time();
$exp_date=(60*60*24);
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE ($now_date > (date + ($exp_date * expire))) AND expire <> 0 ".$to_query_a." ORDER BY priority DESC, date DESC, idArtc DESC";
$result=mysql_query($query);
$num_exp=mysql_numrows($result); //how many rows

//count comments for approval
$query="SELECT * FROM ".$db_table_prefix."comments WHERE approval=0 ".$to_query_c;
$result=mysql_query($query);
$num_cfa=mysql_numrows($result); //how many rows



?>
<br>
<br>
<br>

  Welcome <strong> <?php echo "$full_name";?></strong>.</span><br>
  <span class="maintext">
  <br>
<img src="images/app/i16.gif" width="16" height="16" align="absbottom"><strong> Status:</strong> 
&nbsp;You are loged in. Select an action from the left menu.</span><span class="maintext"><strong><br>
  </strong></span>
  
<?php
//check for informations to show
if ($num_exp>0) {$isinfo=1;}
if ($num_cfa>0) {$isinfo=1;}

//open table
if ($isinfo==1) {
echo ' 
<!-- main table: -->	  
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr>
    <td width="50" align="left" valign="top"><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<b>Info:</b></td>
    <td align="left" valign="top">

<!-- spacer table: -->	  
	<table height="2" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
	
<!-- data table: -->	  
	    <table border="0" cellpadding="0" cellspacing="0" class="maintext">

';}

//output info
if ($num_exp>0) {
echo '
        <tr>
          <td valign="middle">&#8226; There is currently <strong>'.$num_exp.'</strong> expired article(s).</td>
        </tr>
';}

//output info
if ($num_cfa>0) {
echo '
        <tr>
          <td valign="middle">&#8226; There is currently <strong>'.$num_cfa.'</strong> comment(s) vaiting for approval.</td>
        </tr>
';}

//close table
if ($isinfo==1) {
echo '
      </table>
    </td>
  </tr>
</table>
';}

?>
<br>
<br>
<br>
<br>

<input type="button" name="logout_button" value="Logout ..." style="width: 70px; height: 23px;" class="formfields2" onClick="logout_go()">
<br>
  <br>
  <br>
<br>
  <br>
  </span>
</body>
</html>