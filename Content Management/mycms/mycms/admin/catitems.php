<?
ob_start();
include("conn.php");

$sql = "SELECT * FROM category ORDER BY position ASC";
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
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td width="74%" align = "right" bgcolor="#ECFCFF"><a href="mainf.php">Main 
        Window</a></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB"><a href="#" onClick="window.open('addcat.php', 'newWnd', 'width=700,height=350,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">Add category</a></td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
      <!--<td width="75">Placement</td> -->
    </tr>
  </table>  
  <table width="75%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="2"></td>
    </tr>
  </table>

  
<br>
  <table width="75%" border="0" cellpadding="0" cellspacing="1" class=catTbl2 mm_noconvert="TRUE">
    <tr bgcolor="#FFFFFF">
      <td width="25%" height="23" >Category Name</td>
      <td width="25%" class=cat>Placement</td>
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
$placement = stripslashes($result["placement"]);

?>
    <tr>
      <td width="25%" bgcolor="#E7FCFE">
        <?=$name?>
      </td>
      <td width="25%" bgcolor="#F9F0CC">
        <?=$placement?>
      </td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('editcat.php?id=<?=$id?>', 'newWnd', 'width=740,height=350,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">edit category</a></td>
      <td width="25%" bgcolor="#FFFFFF"> <a href="#" onClick="window.open('delcat.php?id=<?=$id?>&name=<?=$name?>', 'newWnd', 'width=300,height=150,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">delete category</a></td>
    </tr>
    <?
}
?>
  </table>
</center>
</body>
</html>
