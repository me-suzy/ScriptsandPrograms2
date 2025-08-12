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
include '../../restrict_access.php';

//configuration file
include '../../config_inc.php'; 
 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../cms_style.css" rel="stylesheet" type="text/css">

<script>
function _CloseOnEsc() {
  if (event.keyCode == 27) {

    parent.window.close();
    return;
  }
}

function Init() {
  document.body.onkeypress = _CloseOnEsc;
}

function Get_URL2() {
if (parent.txtFileName.selectedIndex>-1) {
do_delete=confirm("Delete image \""+parent.txtFileName[parent.txtFileName.selectedIndex].value+"\"?");
if (do_delete) {
  this.location="image_delete.php?image="+parent.txtFileName[parent.txtFileName.selectedIndex].value;
  parent.imageFrame.location="image_selected.php";
  }
}
else {alert("Select image to delete!");}
}
</script>

</head>

<body leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" scroll="no" onLoad="Init()" class="maintext">
<table width="200" height="130" border="0" cellpadding="0" cellspacing="5" class="maintext">
  <tr>
<form action="image_upload_insert.php" method="post" enctype="multipart/form-data" name="insertform" >
      <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintext">
          <tr align="left" valign="top"> 
            <td width="50%">Upload image:<br>
              <input name="file_up" type="file" class="maintextms" id="file_up2" maxlength="255" align="absmiddle" style="width: 30px">
              <input type="submit" name="Submit" value="Upload &gt;" class="maintextms" align="absmiddle"> 
              <br> <br>
            </td>
            <td width="50%">Delete image:<br> 
              <input name="delete_image" type="button" class="maintextms" id="delete_image" onClick="Get_URL2()" value="Delete &gt;"></td>
          </tr>
        </table> </td>
</form>
  </tr>
</table>
</body>
</html>