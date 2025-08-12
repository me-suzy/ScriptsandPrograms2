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
$arr = array  (  
    '0' => 'ADMIN',
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

<script type="text/javascript" src="checkform.js"></script>

<script language="JavaScript" type="text/JavaScript">
function delete_go()
{
	this.location="articles_cat_delete.php?id=<?php echo "$id";?>";
}

function cancel_go()
{
		this.location="articles_cat_search.php";
}
</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.editform.category.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Categories: <span class="titletext0blue">Edit 
      Category</span></td>
  </tr>
</table>
<br>
<br>
<?php

$query="SELECT * FROM ".$db_table_prefix."articles_category WHERE idCat=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row


//assign variables
$id=$row["idCat"];
$category=$row["category"];
$category=htmlspecialchars($category ,ENT_QUOTES);

?>
<form action="articles_cat_insert.php" method=post enctype="multipart/form-data" name="editform" onSubmit="return checkform(editform);">
  <input name="action" type="hidden" id="action" value="edit">
    <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
  <br>
  <strong>Category:</strong><br>
  <input name="category" type="text" class="formfields" id="category" value="<?php echo "$category";?>" size="40" maxlength="255" title="Restriction:
- quotes are not allowed"  alt="anything" emsg="Category">
  <img src="images/app/asterix.jpg" width="9" height="8"><br>
  <br>
  <br>
  <br>
<input type="submit" name="submit" value="Save category -&gt;" style="width: 100px; height: 26px;" class="formfields2">
&nbsp;
  <input name="delete_button" type="button" class="formfields2" id="delete_button" style="width: 90px; height: 26px;" value="Delete category" onClick="delete_go()">
&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">
        
</form>
<br>
<br>
<br>
</body>
</html>
