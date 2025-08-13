
<?php $doc_file = "../docs/LICENSE.txt"; ?>

<HTML>
<HEAD>
	<TITLE>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 1: License Agreement.</TITLE>
	<link rel="Stylesheet" href="./lib/styles.css" type="text/css">
</HEAD>
<BODY BGCOLOR='#FFFFFF' TEXT='#000000' LINK='#0000FF' ALINK='#333333' VLINK='#9900CC'>
<a name="PageTop"></a>

<div align=center>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<form action='<?php echo  $_SERVER['PHP_SELF'] ?>' method=get>
<tr>
  <td class='header' align=left nowrap>
    <B>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 1: License Agreement</B>
  </td>
</tr>
<tr>
  <td class='content' nowrap align=left>
    <blockquote><pre style="font-size: 11px;"><br><?php if (file_exists($doc_file)) { readfile($doc_file); } else { echo "\n\n\n- Sorry, cannot locate the license.\n- Please make sure the ./docs/* directory exists.\n\n\n";} ?></pre></blockquote>
    <div align=center>
      <input type='submit' name='do' value='<?php echo  ACCEPT_INSTALL ?>' class='btn_off' tabindex='1'>
      &nbsp;
      <input type='submit' name='do' value='<?php echo  ACCEPT_UPGRADE ?>' class='btn_off' tabindex='2'>
      &nbsp;
      <input type='button' name='cancel' value='Refuse' class='btn_off' tabindex='3' onclick="top.location.href='http://www.opensource.org/docs/definition.html';">
      &nbsp;
      <input type='button' name='cancel' value='Cancel' class='btn_off' tabindex='4' onclick="if ( confirm('Are you sure you want to exit this installer?') ) window.close();">
    </div>
    <br>
    <br>
  </td>
</tr>
</form>
</table>

</div>

</body>
</html>
