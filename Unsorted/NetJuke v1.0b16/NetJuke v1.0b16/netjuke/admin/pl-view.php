<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_pl-view.php");

if ($_REQUEST['do'] == 'radio') {

  $do_it = true;
  
  $plist = RADIO_PLIST;
  
  $title = PLVIEW_HEADER_RADIO;

} elseif ($_REQUEST['do'] == 'jukebox') {

  $do_it = true;
  
  $plist = JUKEBOX_PLIST;
  
  $title = PLVIEW_HEADER_JUKEBOX;

} else {
  
  $title = 'ERROR';

  $do_it = false;

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  $title ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

<div align=center>

  <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
  <tr>
    <td class='header' nowrap><B><?php echo  $title ?></B></td>
  </tr>
  <tr>
    <td width="100%" align=left wrap="virtual" class="content">
      <pre class="content"><?php if ($do_it == true) readfile($plist); ?></pre>
    </td>
  </tr>
  </table>

</div>
		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="50%" align="center" class="content"><a href="#PageTop" title="<?php echo  PLVIEW_PAGETOP_HELP ?>"><b><?php echo  PLVIEW_PAGETOP ?></b></a></td>
				<td width="50%" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  PLVIEW_CLOSEWIN_HELP ?>"><b><?php echo  PLVIEW_CLOSEWIN ?></b></a></td>
			</tr>
		</table>

	<a name="PageBot"></a>
</BODY>
</HTML>

<?php

  include (INTERFACE_FOOTER);

  exit;

?>
