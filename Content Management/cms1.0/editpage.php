<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
?>
<html>
<head>
<title>Edit Page</title>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
<?php
include ("connect.php");
include ("top-header.php");
$result = mysql_query("select text,heading,pageorder from pages where name='$var';");
$row = mysql_fetch_row($result);

$pagetext=$row[0];
$pageheading=$row[1];
$pageorder=$row[2];
if ($editor == "on") {
?>
<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "\htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 
<?php
}
?>
</head>
<body>
<?php
if (! $B1){?>
 
<form method="POST" action='editpage.php'>
<input type=hidden name='newvar' value='<?php echo $var;?>'>
<table border="1" align="center" bordercolor="#333333">
<tr>
    <td class="text-design2">Page Order</td>
	<td><input type="text" name="pageorder" value="<?php echo $pageorder;?>" size="3"></td>
</tr>
<tr>
    <td class="text-design2">Page Name</td>
	<td><input type="text" name="pagename" value="<?php echo $var;?>" size="40"></td>
</tr>
<tr>
    <td class="text-design2">Page Heading</td>
	<td><input type="text" name="pageheading" value="<?php echo $pageheading;?>" size="40"></td>
</tr>
 <tr>
    <td class="text-design2" colspan="2"> 
      <textarea name="pagetext" cols="60" rows="12" class="text-box1"><?php echo $pagetext;?></textarea>
		  <?php if ($editor == "on") { ?>
          <script language="JavaScript1.2" defer>
			editor_generate('pagetext');
		  </script>
		  <?php } ?>
		</td>
  </tr>
  </table>
   <p align="center"><input name="B1" type="submit" class="back-button" value="Edit Page Contents"></p>
	</form>
	  <?php }
	  else {
	  include ("connect.php");
	  $pagetext = ($_POST['pagetext']);
	  $pageheading = ($_POST['pageheading']);
	  $pagename = ($_POST['pagename']);
	  $pageorder = ($_POST['pageorder']);
	  if(! is_numeric($pageorder)){
	  $pageorder = 0;
	  }
	  
	  $thequery = "UPDATE pages set text='$pagetext',heading='$pageheading',name='$pagename',pageorder=$pageorder where name='$newvar' ";
	  //echo "<br>Query = " . $thequery;
	  $result = mysql_query ($thequery) or die(mysql_error());
	  print "<p class='headingcenter'>successfully edited</p><p class='headingcenter'><a href='editpage.php?var=$pagename'>Edit Again</a></p><p class='headingcenter'><a href='options.php?choice=view'>View Page List</a></p>";

	 }
	 ?>
	 <p class="headingcenter"><a href="options.php">Back to Options</a></p>
<?include ("footer.php");?>	   
