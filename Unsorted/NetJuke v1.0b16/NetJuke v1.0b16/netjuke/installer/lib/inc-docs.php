<?php

switch ($_REQUEST['do']) {
  case ACCEPT_UPGRADE:
    $doc_file = "../docs/UPGRADING.txt";
    $btn = DO_UPGRADE;
    break;
  default:
    $doc_file = "../docs/INSTALL.txt";
    $btn = DO_INSTALL;
}

?>
<HTML>
<HEAD>
	<TITLE>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 2: Installation Notes</TITLE>
	<link rel="Stylesheet" href="./lib/styles.css" type="text/css">
</HEAD>
<BODY BGCOLOR='#FFFFFF' TEXT='#000000' LINK='#0000FF' ALINK='#333333' VLINK='#9900CC'>
<a name="PageTop"></a>

<div align=center>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<form action='<?php echo  $_SERVER['PHP_SELF'] ?>' method=get>
<tr>
  <td class='header' align=left nowrap>
    <B>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 2: Installation / Upgrade Notes</B>
  </td>
</tr>
<tr>
  <td class='content' align=left>
    <br>
    <div align=center>
      <input type='submit' name='do' value='<?php echo  $btn ?>' class='btn_off' tabindex='1'>
      &nbsp;
      <input type='submit' name='do' value='License' class='btn_off' tabindex='2'>
      &nbsp;
      <input type='button' name='cancel' value='Cancel' class='btn_off' tabindex='3' onclick="if ( confirm('Are you sure you want to exit this installer?') ) window.close();">
    </div>
    <blockquote><pre style="font-size: 11px;"><?php if (file_exists($doc_file)) { readfile($doc_file); } else { echo "- Sorry, cannot locate the documentation.\n- Please make sure the ./docs/* directory exists.";} ?></pre></blockquote>
      <div align=center>
      <input type='submit' name='do' value='<?php echo  $btn ?>' class='btn_off' tabindex='1'>
      &nbsp;
      <input type='submit' name='do' value='License' class='btn_off' tabindex='2'>
      &nbsp;
      <input type='button' name='cancel' value='Cancel' class='btn_off' tabindex='3' onclick="if ( confirm('Are you sure you want to exit this installer?') ) window.close();">
    </div>
    <br>
  </td>
</tr>
</form>
</table>

</div>

</body>
</html>
