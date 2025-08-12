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
function approve_go()
{
	this.location="comments_approve.php?id=<?php echo "$id";?>";
}

function delete_go()
{
	this.location="comments_delete.php?id=<?php echo "$id";?>";
}

function cancel_go()
{
		this.location="comments_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" onload="document.addform.text.focus()">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Comments: View/Edit Comments:<span class="titletext0blue"> 
      Edit Comment</span></td>
  </tr>
</table>
<br>
<br>
<?php 

$query="SELECT * FROM ".$db_table_prefix."comments WHERE idComm=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row


//assign variables
$added_by=$row["added_by"];
$text=$row["text"];
$date=$row["date"];
$date=date('j. F Y / G:i',"$date");
$approval=$row["approval"];

?>
<form name="addform" method="post" action="comments_insert.php">
  <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
  <span class="maintext"><strong> </strong></span>
  <table width="515" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="80" class="maintext"><strong>Added by:</strong></td>
      <td ><input name="added_by" type="text" class="formfields" id="added_by" value="<?php echo "$added_by";?>" size="30" maxlength="255" readonly>
      </td>
    </tr>
    <tr> 
      <td class="maintext"><strong>Comment:</strong></td>
      <td> <textarea name="text" class="formfields" id="textarea" style="width:400; height:200"><?php echo "$text";?></textarea>
        <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> 
      </td>
    </tr>
    <tr> 
      <td valign="top" class="maintext"><strong>Date/Time:<br>
        </strong></td>
      <td class="maintext"><strong> </strong> <input name="date" type="text" class="formfields" id="date" size="30" maxlength="255" value="<?php echo "$date";?>" readonly>
         
        </td>
    </tr>
  </table>
  <span class="maintext">
  <?php 
  if ($approval==0) {echo '<br><img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<b>Info:</b>&nbsp;This comment is not yet approved.<br>';}
  ?>
  
  <br>
  <br>
  <br>
</span>
  <input type="submit" name="submit" value="Save comment -&gt;" style="width: 100px; height: 26px;" class="formfields2">
<?php   if ($approval==0) {echo '
&nbsp;&nbsp;&nbsp;&nbsp;
<input name="approve" type="button" class="formfields2" id="approve" style="width: 110px; height: 26px;" value="Approve comment ->" onClick="approve_go()">
';}
?>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="delete" type="button" class="formfields2" id="delete" style="width: 90px; height: 26px;" value="Delete comment" onClick="delete_go()">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="delete2" type="button" class="formfields2" id="delete2" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">
  <br>
  </form>
</body>
</html>
