<?
ob_start();
include("conn.php");

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
      <td width="74%" align = "right"><a href="catitems.php">modify menu Categories</a></td>
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
      <td width="74%" align = "right"><a href="newsitems.php">News Items</a></td>
      <td width="1%" align = "right">&nbsp;</td>
      <td width="1%" align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="23%" align = "right" bgcolor="#EAFDEB"><a href="galleryitems.php">Gallery 
        Items</a></td>
      <td width="1%" align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
    <tr bgcolor="#ECFCFF">
      <td align = "left"><a href="mainf.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>&gt;&gt;main 
        menu</strong></font></a></td>
      <td align = "right">&nbsp;</td>
      <td align = "right" bgcolor="#FFFFFF">&nbsp;</td>
      <td align = "right" bgcolor="#EAFDEB"><a href="help.html"><font color="#006633"><strong>Help 
        how to use</strong></font></a> </td>
      <td align = "right" bgcolor="#EAFDEB">&nbsp;</td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="9%" valign="top"><u>Add Menu</u></td>
      <td width="91%" bgcolor="#F3F3F3"><strong>Click add menu </strong></td>
    </tr>
    <tr> 
      <td height="34" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Menu Name</font></strong> 
        - This is the name of the new page you are going to add</td>
    </tr>
    <tr> 
      <td height="50" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Page Type</font> </strong>- 
        <strong>Content: </strong>Use for ordinary text pages, <strong>News: </strong>Use 
        for adding a news page, <strong>Gallery: </strong> For creating an image 
        libary: <strong>Contact: </strong>Add a contact page</td>
    </tr>
    <tr> 
      <td height="30" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Show on Menu</font> 
        </strong>- Enable or disable a page</td>
    </tr>
    <tr> 
      <td height="30" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Show News Summary</font></strong><font color="#0066CC">&nbsp; 
        </font>- All you show a selected news summary on the page you are adding 
        (only if a news page is added)</td>
    </tr>
    <tr> 
      <td height="29" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Show Poll No</font></strong>. 
        - to be developed</td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Place Menu</font></strong> 
        - Where do you want the menu to be placed on the list of menus </td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Menu Category</font></strong> 
        - This is the section of the menu you want the link to be placed</td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="27" valign="top"><u>Categories</u></td>
      <td bgcolor="#F3F3F3"><strong>Click modify menu Categories</strong></td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3">A category is the name of the section a group of links 
        comes under. e.g <u>printers</u>, <u>scanners</u>, <u>monitor</u> may 
        come under a category called <strong>Computers</strong></td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td bgcolor="#F3F3F3"><strong><font color="#0066CC">Category Placement</font></strong>. 
        this is where the menu links will be placed</td>
    </tr>
    <tr> 
      <td height="27" valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="27" valign="top"><u><strong>News</strong></u></td>
      <td bgcolor="#F3F3F3">When you add a news page a story is added from the 
        <strong>List items</strong> page</td>
    </tr>
    <tr>
      <td height="27" valign="top"><u><strong>Gallery</strong></u></td>
      <td bgcolor="#F3F3F3">When you add a gallery page images are added from 
        the <strong>gallery items</strong> page</td>
    </tr>
  </table>
<p>&nbsp;</p></center>
</body>
</html>
