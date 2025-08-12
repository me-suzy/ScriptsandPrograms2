<? 
include ("auth.php");
?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Mike - CMS Script</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="./style.css" rel="stylesheet" type="text/css">
</head>

<body background="../img/int1_back.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="970" border="0" align="center" class="hoofdtabel">
  <tr>
    <td>
	  <table width="970" border="0" cellpadding="0" cellspacing="0" background="../img/int1_topback.gif">
        <tr> 
          <td> <a href="./index.php">YOUR LOGO HERE</a></td>
          <td align="right" valign="top"><br>
		    <a href="./index.php?page=config" class="titel2"><u>Configure Homepage</u></a>&nbsp;&nbsp;&nbsp;
            <a href="./index.php?page=news" class="titel2"><u>Page - Admin</u></a>&nbsp;&nbsp;&nbsp; <br>
            <br><div align="right">
<?
 
	if($_GET['page']=="news") echo "<a href=\"item_list.php\" class=\"titel2\" target=\"links\">View all the pages</a> | <a href=\"add_item1.php\" class=\"titel2\" target=\"rechts\">Add a page</a>";

?>
</div></td>
        </tr>
      </table>
<?
if($_GET['page']=="news"){
?>
	  <table width="970" border="0">
  		<tr>
   		 <td align="left" valign="top"><iframe src="item_list.php" width="485" height="360" id="links" name="links" class="iframe" scrolling="auto" frameborder="0"></iframe></td>
   		 <td rowspan="2" valign="top" align="right"><iframe src="about:blank" width="485" height="586" id="rechts" name="rechts" class="iframe" scrolling="auto" frameborder="0"></iframe></td>
		 </tr>
		 <tr>
		 <td valign="top" align="left">
		 <iframe src="item_legend.php" width="485" height="220" id="onder" name="onder" class="iframe" scrolling="auto" frameborder="0"></iframe>
		 </td>
		 </tr>
	  </table>
<?
}
if($_GET['page']=="config"){
?>
	  <table width="970" border="0">
  		<tr>
   		 <td align="left" valign="top"><iframe src="config_pages.php" width="485" height="360" id="links" name="links" class="iframe" scrolling="auto" frameborder="0"></iframe></td>
   		 <td rowspan="2" valign="top" align="right"><iframe src="about:blank" width="485" height="586" id="rechts" name="rechts" class="iframe" scrolling="auto" frameborder="0"></iframe></td>
		 </tr>
		 <tr>
		 <td valign="top" align="left">
		 <iframe src="about:blank" width="485" height="220" id="onder" name="onder" class="iframe" scrolling="auto" frameborder="0"></iframe>
		 </td>
		 </tr>
	  </table>
<?
}
?>
	</td>
  </tr>
</table>
</body>
</html>