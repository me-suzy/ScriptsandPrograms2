<?
ob_start();
include("conn.php");

$sql = "SELECT * FROM menu WHERE type = 'news'  and deleted='0' ORDER BY position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$sql2 = "SELECT * FROM menu WHERE type = 'competition'  and deleted='0' ORDER BY position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());



$sql3 = "SELECT * FROM menu WHERE type = 'interview'  and deleted='0' ORDER BY position ASC";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());
//type = 'news' OR type = 'competition' OR type = 'interview'

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
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td width="74%" align = "right" bgcolor="#ECFCFF"><a href="mainf.php">Main 
        Window</a></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB">Add Menu</td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
      <!--<td width="75">Placement</td> -->
    </tr>
  </table>  
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="2"></td>
    </tr>
  </table>
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td width="74%" align = "right"><b>List Items</b></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB"><a href="galleryitems.php">Gallery 
        Items</a></td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
  </table><br>
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#EAFDEB"> 
        <div align="center">Modules</div></td>
    </tr>
  </table>
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#ECFCFF" class = "lstName">&gt; Add Poll</td>
    </tr>
  </table><br>
  <table width="75%" border="0" cellpadding="0" cellspacing="1" class=catTbl2 mm_noconvert="TRUE">
    <tr bgcolor="#FFFFFF"> 
      <td width="25%" height="23" >Menu Name</td>
      <td width="25%" class=cat>Page Type</td>
      <td width="75">Option 1</td>
      <td width="25%">Option 2</td>
    </tr>
  </table>
<br>


  <table width="75%" border="0" cellpadding="1" cellspacing="1" class=catTbl mm_noconvert="TRUE">
    <?
while($result = mysql_fetch_array($query)) {
$id = stripslashes($result["id"]);
$name = stripslashes($result["name"]);
$type = stripslashes($result["type"]);
$cat = stripslashes($result["cat"]);
$placement = stripslashes($result["placement"]);
$poll = stripslashes($result["poll"]);
$news = stripslashes($result["news"]);
$enabled = stripslashes($result["enabled"]);

?>
    <tr> 
      <td width="25%" bgcolor="#E7FCFE"> 
        <?=$name?>
      </td>
      <td width="25%" bgcolor="#F9F0CC"> 
        <?=$type?>
      </td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=view&action=view', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">view/Add 
        </a></td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=modify&action=show', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">modify/Add/delete 
        </a></td>
    </tr>
    <?
}
?>

    <?
while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$name = stripslashes($result["name"]);
$type = stripslashes($result["type"]);
?>

   <tr> 
      <td width="25%" bgcolor="#E7FCFE"> 
        <?=$name?>
      </td>
      <td width="25%" bgcolor="#F9F0CC"> 
        <?=$type?>
      </td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=view&action=view', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">view/Add 
        </a></td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=modify&action=show', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">modify/Add/delete 
        </a></td>
    </tr>
    <?
}
?>


    <?
while($result = mysql_fetch_array($query3)) {
$id = stripslashes($result["id"]);
$name = stripslashes($result["name"]);
$type = stripslashes($result["type"]);
?>

   <tr> 
      <td width="25%" bgcolor="#E7FCFE"> 
        <?=$name?>
      </td>
      <td width="25%" bgcolor="#F9F0CC"> 
        <?=$type?>
      </td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=view&action=view', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">view/Add 
        </a></td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('modifynews.php?id=<?=$id?>&name=<?=$name?>&select=modify&action=show', 'newWnd', 'width=740,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">modify/Add/delete 
        </a></td>
    </tr>
    <?
}
?>

  </table>
</center>
</body>
</html>
