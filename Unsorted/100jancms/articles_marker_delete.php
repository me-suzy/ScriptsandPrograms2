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


        //check if marker is in use
			//get marker name
			$query="SELECT * FROM ".$db_table_prefix."articles_marker WHERE idMark=".$todelete[$i];
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

	    	$marker=$row["marker"];

				//check if marker is in use
				$query2="SELECT * FROM ".$db_table_prefix."articles_items WHERE marker='".$marker."'"; 
				$result2=mysql_query($query2);
				$num=mysql_numrows($result2); //how many rows			


			if ($num>0) {
				$text_bad.='<span class="red">Error:</span> Marker <b>"'.$marker.'"</b> is in use and could not be deleted! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>';
				}
			else {

				$text_good=$text_good.'<span class="maintext"><strong>Status:</strong> Marker <b>"'.$marker.'"</b> has been deleted!</span>&nbsp;&nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absmiddle"><br>';

				
				//delete user privileges
					//load current user privileges
					$query3="SELECT * FROM ".$db_table_prefix."users";
					$result3=mysql_query($query3);
					$num3=mysql_numrows($result3); //how many rows
										
					for ($i3=0;$i3<$num3;$i3++) {

						$user_id=mysql_result($result3,$i3,"idUsers");
						$user_privileges=mysql_result($result3,$i3,"user_privileges");

						$new_user_privileges=str_replace("ARTICLES[".$marker."], ","",$user_privileges);
						$new_user_privileges=str_replace("COMMENTS[".$marker."], ","",$new_user_privileges);

							//save new privileges
							$query4 = "UPDATE ".$db_table_prefix."users SET user_privileges='".$new_user_privileges."' WHERE idUsers='".$user_id."'";
							mysql_query($query4);
					
					}


			// delete marker
			$query_del="DELETE FROM ".$db_table_prefix."articles_marker WHERE idMark=".$todelete[$i]; 
			mysql_query($query_del);

				
			}

//end of deleting
		$i++;
    } 

}
else 
{ 
    // no checkbox has been selected 
	echo "none selected"; 
} 

//ending message
if ($text_bad=="") {
		$message = '
		<span class="maintext"><strong>Status:</strong> Markers(s) has been deleted!</span>&nbsp;&nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absmiddle">
		<meta http-equiv="Refresh" content="3; url=articles_marker_search.php">
		';
	
		}

		else {
//			if ($text_bad!=="") {$text_good="<br>".$text_good;}
	
		$message=$text_bad.$text_good.'
		<br>
		<br>
		<br>
		<input type="button" name="cancel_button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Back" onClick="cancel_go()">
		<script language="JavaScript" type="text/JavaScript">
		document.all.cancel_button.focus();
		</script>
		';

		}

$action = "true";


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
	this.location="articles_marker_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Markers: <span class="titletext0blue"> 
      Delete Marker(s)</span></td>
  </tr>
</table>
<br>
<br>
<?php 

$text1='
<form name="delform" action="articles_marker_delete.php" method="post">
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

if (isset($id)) {
$text2="<input type=hidden name=todelete[0] value=".$id.">".$text2;
}
//pass array to this same page -  end


$text3='<span class="maintext">Are you sure you want to DELETE the following marker(s)? <span class="maintext2"></span><br>
<span class="maintext"><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<strong>Warning:</strong>&nbsp;&nbsp;This operation can not be reverted!</span>
<br><br><hr width="75%" size="1" noshade color="000000" align="left">';

//echo all items - start
if (count($todelete) > 0) {



$todelete=array_reverse ($todelete); //reverse input array

$text4="";
for ($i=0;$i<count($todelete);$i++) {

			//marker
			$query="SELECT * FROM ".$db_table_prefix."articles_marker WHERE idMark=".$todelete[$i]." ORDER BY marker"; 
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

			//assign variables
			$item_display_1=$row["marker"];
			$text4="<span class='maintext2'>-&nbsp;&nbsp;\"".$item_display_1."\"</span><br>".$text4;
			}
}
if ($id<>"")
{
			//marker
			$query="SELECT * FROM ".$db_table_prefix."articles_marker WHERE idMark=".$id." ORDER BY marker"; 
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row

			//assign variables
			$item_display_1=$row["marker"];
			$text4="<span class='maintext2'>-&nbsp;&nbsp;\"".$item_display_1."\"</span><br>".$text4;

}			
//echo all items - end

$text5='<hr width="75%" size="1" noshade color="000000" align="left"><br><span class="maintext">Proceed?</span>
<br><br>

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
