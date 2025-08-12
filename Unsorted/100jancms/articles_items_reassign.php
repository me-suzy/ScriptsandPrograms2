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
$todelete=$_POST["todelete"];
$del=$_POST["del"];
$marker=$_POST["marker"];



//RE-ASSIGN MARKER
if ($del == "true") 
	{


$i=0;
if (count($todelete) > 0) 
{ 
    foreach($todelete as $name_element) 
    { 


        //reassign marker
		$query = "UPDATE ".$db_table_prefix."articles_items SET marker='".$marker."' WHERE idArtc=".$todelete[$i]; 
		mysql_query($query);


		$i++;
    } 
//echo "done";
}
else 
{ 
    // no checkbox has been selected 
	echo "none selected"; 
} 

//ending message
$message = '
		<span class="maintext"><strong>&nbsp;&nbsp;Status:</strong> Marker has been reassigned!</span>&nbsp;&nbsp;<img src="images/app/all_good.jpg" width="16" height="16">
		';
		$action = "true";
		echo'<meta http-equiv="Refresh" content="3; url=articles_items_search.php">' ;
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
	this.location="articles_items_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles: <span class="titletext0blue"> 
      Reassign Marker</span></td>
  </tr>
</table>
<br>
<br>
<?php 

$text1='
<form name="delform" action="articles_items_reassign.php" method="post">
<input type=hidden name=id value="'.$id.'">
<input type=hidden name=del value="true">';

//pass array to this same page - start
$text2="";
if (count($todelete) > 0) {
for ($i=0;$i<count($todelete);$i++) {
$text2="<input type=hidden name=todelete[".$i."] value=".$todelete[$i].">
".$text2;
}
}

if (!empty($id)) {
$text2="<input type=hidden name=todelete[0] value=".$id.">".$text2;
}
//pass array to this same page -  end


$text3='<span class="maintext">Are you sure you want to REASSIGN MARKER for the following article(s) to selected? <span class="maintext2"></span><br>
<span class="maintext"><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<strong>Warning:</strong>&nbsp;&nbsp;This operation can not be reverted!</span>
<br><br><hr width="75%" size="1" noshade color="000000" align="left">
<b>Marker:</b><br>
';

//list all markers =================================================================

$text_m='<select name="marker" class="formfields" id="marker" style="width:247">';

		    //load user_privileges for current user
			$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //u kom sam redu
			$user_privileges=$row["user_privileges"];


			//load all markers
			$query="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
			$result=mysql_query($query);
			$num=mysql_numrows($result); //koliko ima redova
			
//loop
$i=0;
while ($i < $num) {

			$marker=mysql_result($result,$i,"marker");
			

//display allowed markers

if (substr_count($user_privileges, "ARTICLES[$marker]")<>"0") {
$text_m.="<option value=\"".$marker."\" >".$marker."</option>";
}

++$i;
}
//end loop

$text_m.='</select><br><br>';

//==================================================================================


//echo all items - start
if (count($todelete) > 0) {

$todelete=array_reverse ($todelete); //reverse input array

$text4="";
for ($i=0;$i<count($todelete);$i++) {

			//article title
			$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$todelete[$i]." ORDER BY priority ASC, date ASC"; 
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

			//
			$item_display_1=$row["title"];
			$item_display_2=$row["date"];
			$item_display_3=$row["marker"];			
				$item_display_2=date('j. F Y / H:i',"$item_display_2");			
			$text4="<span class='maintext2'>-&nbsp;&nbsp;\"".$item_display_1."\"</span>&nbsp;(".$item_display_2.") &nbsp;&nbsp;<i>(".$item_display_3.")</i><br>".$text4;
			}
}
			
//echo all items - end

$text5='<hr width="75%" size="1" noshade color="000000" align="left"><br><span class="maintext">Proceed?</span>
<br><br>

<input name="delete_button" type="submit" class="formfields2" id="delete_button" style="width: 75px; height: 26px;" value="OK ->">
&nbsp;&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">

<script language="JavaScript" type="text/JavaScript">
document.delform.marker.focus();
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
	echo "$text_m";	
	echo "$text4";
	echo "$text5";	
	}
	else
	{
    // no checkbox has been selected 
	echo 'none selected';
	}
	
}
	else 
	{
echo "$message";
}
?>

<br>
<br>
<br>

</body>
</html>
