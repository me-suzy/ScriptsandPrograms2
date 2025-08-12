<?
ob_start();
include("conn.php");

$sql = "SELECT * FROM menu WHERE deleted='0' ORDER BY catposition ASC, position ASC ";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

?>
<HTML>
<HEAD>
<TITLE>Content Management System
</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<center><br>
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td width="74%" align = "right"><a href="catitems.php">Categories</a></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB"><a href="#" onClick="window.open('addmenu.php', 'newWnd', 'width=700,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">Add Menu</a></td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
      <!--<td width="75">Placement</td> -->
    </tr>
  </table>  
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="2"></td>
    </tr>
  </table>
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td align = "right" bgcolor="#FEF0D8"><a href="newsitems.php">List Items</a></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB"><a href="galleryitems.php">Gallery Items</a></td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
    <tr bgcolor="#ECFCFF"> 
      <td align = "right">&nbsp;</td>
      <td align = "right">&nbsp;</td>
      <td align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td align = "right" bgcolor="#EAFDEB"><a href="help.php"><font color="#006633"><strong>Help 
        how to use</strong></font></a> </td>
      <td align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
    <tr bgcolor="#ECFCFF"> 
      <td align = "left" bgcolor="#FFFFFF"><img src="memberss.jpg" width="29" height="18">&nbsp;<a href="javascript:window.close();">Logout</a></td>
      <td align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td align = "right" bgcolor="#EAFDEB">&nbsp;</td>
      <td align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
  </table>
  <br>
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#EAFDEB"> 
        <div align="center">Modules</div></td>
    </tr>
  </table>
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#ECFCFF" class = "lstName">&nbsp;</td>
    </tr>
  </table><br>
  <table width="90%" border="0" cellpadding="0" cellspacing="1" >
    <tr bgcolor="#FFFFFF"> 
      <td width="75" nowrap><div align="center">Menu Name</div></td>
      <td width="75"><div align="center">Page Type</div></td>
      <td width="75"><div align="center">Placement</div></td>
      <td width="75"><div align="center">Category</div></td>
	  <!--
      <td width="75"><div align="center">Show News</div></td>
      <td width="75" nowrap><div align="center">News Sum of</div></td>
	  
      <td width="75"><div align="center">Show Poll</div></td>
	  -->
      <td width="75"><div align="center">Enabled</div></td>
      <td width="75"><div align="center">Option 1</div></td>
      <td width="75"><div align="center">Option 2</div></td>
      <td width="75"><div align="center">Option 3</div></td>
	  <td width="75"><div align="center">Sponsors</div></td>
    </tr>
  </table>
<br>


  <table width="90%" border="0" cellpadding="1" cellspacing="1" class= "catTbl" mm_noconvert="TRUE">
    <?
while($result = mysql_fetch_array($query)) {
$id = stripslashes($result["id"]);
$name = stripslashes($result["name"]);
$type = stripslashes($result["type"]);
$cat = stripslashes($result["cat"]);
$placement = stripslashes($result["placement"]);
$poll = stripslashes($result["poll"]);
$news = stripslashes($result["news"]);
$snewpge = stripslashes($result["show_n_page"]);
$enabled = stripslashes($result["enabled"]);

?>
    <tr onMouseOver="this.bgColor='gold';" onMouseOut="this.bgColor='#FFFFFF';">
      <td width="75" bgcolor="#E7FCFE">
        <?=$name?>
      </td>
      <td width="75" bgcolor="#F9F0CC">
        <?=$type?>
      </td>
      <td width="75" bgcolor="#EAFDEB">
        <?=$placement?>
      </td>
      <td width="75" bgcolor="#EAFDEB">
        <b><?=$cat?></b>
      </td>
	  <!--
      <td width="75" bgcolor="#E7FCFE">
        <?=$news?>
      </td>
	  
	    <td width="75" bgcolor="#E7FCFE">
        <?=$snewpge?>
      </td>
	
      <td width="75" bgcolor="#E7FCFE">
        <?=$poll?>
      </td>
	  -->
      <td width="75" bgcolor="#E7FCFE">
        <?=$enabled?>
      </td>
      <td width="75" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('view.php?id=<?=$id?>&type=<?=$type?>', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">view 
        item</a></td>
      <td width="75" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('edit.php?id=<?=$id?>&type=<?=$type?>', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">edit 
        item</a></td> 
      <td width="75" bgcolor="#FFFFFF"><a href="#" onClick="window.open('delete.php?id=<?=$id?>&name=<?=$name?>&type=<?=$type?>', 'newWnd', 'width=300,height=150,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">delete item</a></td>
	      <td width="75" bgcolor="#FFFFFF"><a href="sponsors.php?pid=<?=$id?>&name=<?=$name?>">Sponsors</a></td>	  
	  
  </tr>
<?
}
?>
</table>
</center>
</body>
</html>
