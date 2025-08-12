<?
ob_start();
include("conn.php");

if($action == "delete"){

$sql3 = "delete FROM adverts where aid = '$aid' ";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());

}




$sql = "SELECT * FROM adverts where pid = '$pid' ";
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


</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<center><br>
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td width="74%" align = "right"><a href="mainf.php">Back to Main</a></td>
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
  <table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#ECFCFF"> 
      <td align = "right" bgcolor="#FEF0D8">List Items</td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB">Gallery Items</td>
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
      <td align = "left" bgcolor="#FFFFFF">&nbsp;</td>
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
      <td width="85%" bgcolor="#ECFCFF" class = "lstName"><strong>Page Name</strong> <? echo $name; ?></td>
      <td width="15%" bgcolor="#ECFCFF" class = "lstName"><a href="#" onClick="window.open('add_advert.php?pid=<?=$pid?>&name=<?=$name?>', 'newWnd', 'width=700,height=550,toolbar=0,status=0,scrollbars=1,resizable=0');return false;">Add Sponsor</a></td>
    </tr>
  </table>
  <p>&nbsp;</p><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="9%">&nbsp;</td>
      <td width="91%">
	  
	  <?
	  if($pid == 1) {
	 
	  echo "<img src='sitelayout1.gif' width='268' height='299'>";
	  }else{
	 echo "<img src='sitelayout2.gif' width='268' height='299'>";
	  
	  }
	  
	  
	  ?>
	  
	  </td>
	  
	  
	  
    </tr>
  </table>
  <br>
  <table width="90%" border="0" cellpadding="1" cellspacing="1" >
    <tr> 
      <td width="9%" align="left" nowrap bgcolor="#FFFFFF"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="31%" align="left" bgcolor="#999999"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Image 
        Info</strong></font></td>
      <td width="31%" align="left" bgcolor="#999999">Add Type</td>
      <td width="29%" align="left" bgcolor="#999999"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Delete</strong></font></td>
    </tr>
    <?
while($result = mysql_fetch_array($query)) {
$aid = stripslashes($result["aid"]);
$iinfo = stripslashes($result["pshow"]);
$adtype = stripslashes($result["adtype"]);

switch ($adtype) {

case '0':
 $stype = "site2";
break;

case '2':
 $stype = "site3";
break;

case '1':
 $stype = "site1";
break;



}
?>
    <tr> 
      <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;</td>
      <td align="left" bgcolor="#F0F0F0"><font color="#000000"><? echo $iinfo; ?></font></td>
      <td align="left" bgcolor="#F0F0F0"><? echo $stype; ?></td>
      <td align="left" bgcolor="#F0F0F0"><a href="sponsors.php?aid=<?=$aid?>&action=delete&pid=<?=$pid?>&name=<?=$name?>">Delete</a></td>
    </tr>
    <?
	}
	?>
  </table>

  </center>
</body>
</html>
