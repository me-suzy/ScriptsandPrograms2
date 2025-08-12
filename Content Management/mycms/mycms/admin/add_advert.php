<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Add Advert</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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

<body>
 <form name='form1' method='post' action='add_advertpost.php?send=image&name=<?=$name?>' enctype='multipart/form-data' onSubmit='return tcheck(this)'>

  <table width='95%' border='1' cellpadding='0' cellspacing='0' bordercolor="#333333">
    <tr bgcolor='FFFFFF'> 
      <td align = 'left' bgcolor="#D1E6FC"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Upload 
        Image</b></font></td>
      <td align = 'left' valign="middle" bgcolor="#D1E6FC"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="radio" name="adtype" value="1">
        Adtype1 | 
        <input type="radio" name="adtype" value="0">
        Adtype2 | 
        <input type="radio" name="adtype" value="2">
        Adtype3</font></td>
    </tr>
    <tr bgcolor='#D1E6FC'> 
      <td width='20%' align = 'left'  class = 'leftform'><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Upload 
        Image</font></td>
      <td width='80%' align = 'left'><input type= 'file' name='img1'></td>
    </tr>
    <tr bgcolor='#D1E6FC'> 
      <td width='20%' align = 'left'  class = 'leftform'><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Link 
        Image to</font></td>
      <td align = 'left'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input name="link" type="text" id="link" size="40">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e.g http://www.yoursite.com/</font></td>
    </tr>
    <tr bgcolor='FFFFFF'> 
      <td colspan = '2' align = 'center' bgcolor="#D1E6FC"><input type="submit" name="Submit" value="Add Image &gt;&gt;"> 
        <input name="pid" type="hidden" id="pid" value="<?=$pid?>"> </td>
    </tr>
  </table>

</form>
<BR>
<form action="add_advertpost.php?send=flash&name=<?=$name?>" method="post" enctype="multipart/form-data" name="form2">
  <table width='95%' border='1' cellpadding='0' cellspacing='0' bordercolor="#333333" bgcolor="#F7F7F7">
    <tr bgcolor='FFFFFF'> 
      <td align = 'left' bgcolor="#CCCCCC"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Upload 
        Flash</b></font></td>
      <td align = 'left' bgcolor="#CCCCCC"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="radio" name="adtype" value="1">
        Adtype1 | 
        <input type="radio" name="adtype" value="0">
        Adtype2 | 
        <input type="radio" name="adtype" value="2">
        Adtype3</font> </td>
    </tr>
    <tr bgcolor='#CCCCCC'> 
      <td width='20%' align = 'left'  class = 'leftform'><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Upload 
        Image</font></td>
      <td width='80%' align = 'left'><input name='flashfile' type= 'file' id="flashfile"></td>
    </tr>
    <tr bgcolor='FFFFFF'> 
      <td colspan="2" align = 'center' bgcolor="#CCCCCC"  class = 'leftform'><input type="submit" name="Submit2" value="Add flash&gt;&gt;"> 
        <input name="pid" type="hidden" id="pid" value="<?=$pid?>"> </td>
    </tr>
  </table>
</form>
<BR>
<form name="form3" method="post" action="add_advertpost.php?send=code&name=<?=$name?>">
  <table width='95%' border='1' cellpadding='0' cellspacing='0' bordercolor="#333333" bgcolor="#F7F7F7">
    <tr bgcolor='FFFFFF'> 
      <td align = 'left' bgcolor="#ECE4D5"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Add 
        RAW code</b></font></td>
      <td align = 'left' bgcolor="#ECE4D5"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="radio" name="adtype" value="1">
        Adtype1 | 
        <input type="radio" name="adtype" value="0">
        Adtype2 | 
        <input type="radio" name="adtype" value="2">
        Adtype3</font> </td>
    </tr>
    <tr bgcolor='#CCFFFF'> 
      <td width='20%' align = 'left' bgcolor="#ECE4D5"  class = 'leftform'><font size="2" face="Verdana, Arial, Helvetica, sans-serif">code</font></td>
      <td width='80%' align = 'left' bgcolor="#ECE4D5"><textarea name="code" cols="36" rows="6" id="code"></textarea></td>
    </tr>
    <tr bgcolor='FFFFFF'> 
      <td colspan="2" align = 'center' bgcolor="#ECE4D5"  class = 'leftform'><input type="submit" name="Submit22" value="Add coding&gt;&gt;"> 
        <input name="pid" type="hidden" id="pid" value="<?=$pid?>"> </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
