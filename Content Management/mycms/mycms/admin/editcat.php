<?
ob_start();
include("conn.php");
require_once("cms.php");

$sql2 = "SELECT * FROM category WHERE id ='$id'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$name = stripslashes($result["name"]);
$placement = stripslashes($result["placement"]);
$position = stripslashes($result["position"]);

}



$sql3 = "SELECT * FROM places";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());

?>

<HTML>
<HEAD>
<TITLE>Content Management </TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 



</head>
<body onunload="opener.location=('catitems.php')">
<center>
<table width="90%" border="0" >
 <tr>
 <td width="100%" align = "left">
<form name="input" action="editcatpost.php" method="post" enctype="multipart/form-data">

<table width="100%" border="0"  cellSpacing= "1" class=catTbl mm_noconvert="TRUE">
 <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Category Name</td>
 <td width="75%" align = "left"><input type="text" name="name" style="background-color:#FFFFD7" size= "45" value = "<?=$name?>"></td>
  </tr>



  <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Category Placement</td>
 <td width="75%" align = "left"> <select name='placement'>
 <?
global $cat;
 while($result = mysql_fetch_array($query3)) {
           $place = stripslashes($result["placement"]);

           if($place == $placement) {
    ?>
    <option selected><?=$place?></option>

 <?
           }else {
  ?>
   <option><?=$place?></option>
   <?
 }
           }
   ?>
   </select>
 </td>
  </tr>

 <?
 menupos3($id);
 ?> 
  


 <tr bgcolor='#FFFFFF'>
 <td width='100%' colspan = '2' height = '30' align = 'center'><input type='submit' value='Update'></td>
  </tr>


</table>
<input type="hidden" name="position" value = "<?=$position?>">
 <input type="hidden" name="id" value = "<?=$id?>">
 <input type="hidden" name="nold" value = "<?=$name?>">
 </form>
 &nbsp; All links in this category will be modified according to choices made
</center>
</body>
</html>



