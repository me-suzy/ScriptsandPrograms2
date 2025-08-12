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


//receive posted data
$id=$_GET["id"];
$todelete=$_POST["todelete"];
$del=$_POST["del"];



//DELETE
if ($del == "true")
	{


$i=0;
if (count($todelete) > 0) 
{ 
    foreach($todelete as $name_element) 
    { 

		$query="DELETE FROM ".$db_table_prefix."comments WHERE idComm=".$todelete[$i]; 
		mysql_query($query);

		$i++;
    } 
//echo "done";
}
else 
{ 
    //no checkbox has been selected 
	echo "none selected";
    exit; 
} 


$message = '
		<span class="maintext"><strong>&nbsp;&nbsp;Status:</strong> Comments(s) has been deleted!</span>&nbsp;&nbsp;<img src="images/app/all_good.jpg" width="16" height="16">
		';
		$action = "true";
		echo'<meta http-equiv="Refresh" content="3; url=comments_search.php">' ;
	}
	else 
	{			
//
	}	

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
function cancel_go()
{
	this.location="comments_search.php";
}
</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" >
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Comments: View/Edit Comments: <span class="titletext0blue"> 
      Delete Comment(s)</span></td>
  </tr>
</table>
<br>
<br>
<?php 

$text1='
<form name="delform" action="comments_delete.php" method="post">
<input type=hidden name=id value="'.$id.'">
<input type=hidden name=del value="true">';

//pass array to this same page - start
$text2="";
if (count($todelete) > 0) {
for ($i=0;$i<count($todelete);$i++) {
$text2="<input type=hidden name=todelete[".$i."] value=".$todelete[$i].">".$text2;
}
}

if ($id<>"") {
$text2="<input type=hidden name=todelete[0] value=".$id.">".$text2;
}
//pass array to this same page - end


$text3='<span class="maintext">Are you sure you want to delete the following comment(s)? <span class="maintext2"></span><br>
<span class="maintext"><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<strong>Warning:</strong>&nbsp;&nbsp;This operation can not be reverted!</span>
<br><br><hr width="75%" size="1" noshade color="000000" align="left">';

//echo all items - start
if (count($todelete) > 0) {

$text4="";
for ($i=0;$i<count($todelete);$i++) {

			//title
			$query="SELECT * FROM ".$db_table_prefix."comments WHERE idComm=".$todelete[$i]." ORDER BY date"; 
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

			//assign variables
			$item_display_1=$row["added_by"];
			$item_display_2=$row["date"];
				$item_display_2=date('j. F Y / G:i',"$item_display_2");						
			$text4="<span class='maintext2'>-&nbsp;&nbsp;\"".$item_display_1."\"</span> (".$item_display_2.")<br>".$text4;
			}
}
if ($id<>"")
{
			//title
			$query="SELECT * FROM ".$db_table_prefix."comments WHERE idComm=".$id." ORDER BY date"; 
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

			//assign variables
			$item_display_1=$row["added_by"];
			$item_display_2=$row["date"];
				$item_display_2=date('j. F Y / G:i',"$item_display_2");					
			$text4="<span class='maintext2'>-&nbsp;&nbsp;\"".$item_display_1."\"</span> (".$item_display_2.")<br>".$text4;

}			
//echo all items - end

$text5='<hr width="75%" size="1" noshade color="000000" align="left"><br><span class="maintext">Proceed?</span>
<br>
<br>
<input name="delete_button" type="submit" class="formfields2" id="delete_button" style="width: 75px; height: 26px;" value="OK ->">
&nbsp;&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">

<script language="JavaScript" type="text/JavaScript">
document.delform.cancel_button.focus();
</script>

</form>';




//DELETE OK

if ($action != "true")
{
	if ((count($todelete) > 0) OR ($id<>""))
	{
	echo "$text1";
	echo "$text2";
	echo "$text3";
	echo "$text4";
	echo "$text5";	
	}
	else
	{
    //no checkbox has been selected 
	echo 'none selected';
	}
	
}
	else 
	{
echo "$message";
}
?>
</body>
</html>
