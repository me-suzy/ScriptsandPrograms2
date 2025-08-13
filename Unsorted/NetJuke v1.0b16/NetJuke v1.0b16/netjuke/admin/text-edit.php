<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_text-edit.php");

switch ($_REQUEST['type']) {
  case 'lyrics':
    $col = $_REQUEST['type'];
    $header = TXTEDIT_HEADER_LYRICS;
    break;
  default:
    $col = 'comments';
    $header = TXTEDIT_HEADER_COMMENTS;
}

if ($_REQUEST['do'] == 'save') {

  $sql = 'update netjuke_tracks '
       . ' set '.$col.' = \''.$_REQUEST['text'].'\''
       . ' where id = '.$_REQUEST['id'];

  $dbconn->Execute($sql);

}

$sql = ' SELECT tr.'.$col.', tr.name, ar.name '
     . ' FROM netjuke_tracks tr, netjuke_artists ar '
     . ' WHERE tr.id = '. abs($_REQUEST['id'])
     . ' AND ar.id = tr.ar_id ';

$dbrs = $dbconn->Execute($sql);

if ($dbrs->RecordCount() != 1) {

  javascript("alert('".TXTEDIT_NOID."'); self.close();");

}

$header .= ': '.format_for_display($dbrs->fields[1]).' ('.format_for_display($dbrs->fields[2]).')';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  $header ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

<div align=center>

  <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
  <form action="" method="post" target="_self">
  <input type="hidden" name="do" value="save">
  <input type="hidden" name="type" value="<?php echo  $_REQUEST['type'] ?>">
  <input type="hidden" name="id" value="<?php echo  $_REQUEST['id'] ?>">
  <tr>
    <td class='header' nowrap><B><?php echo  $header ?></B></td>
  </tr>
  <tr>
    <td width="100%" align=center wrap="virtual" class="content">
      <textarea name="text" rows="35" cols="55" tabindex="1"><?php echo  rawurldecode($dbrs->fields[0]) ?></textarea>
    </td>
  </tr>
  <tr>
    <td width="100%" align=center wrap="virtual" class="content">
      <input type=submit name="btn_update" value='<?php echo  TXTEDIT_FORM_BTN_SAVE ?>' class='btn_content'>
      <input type=reset value='<?php echo  TXTEDIT_FORM_BTN_RESET ?>' class='btn_content'>
    </td>
  </tr>
  </form>
  </table>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="50%" align="center" class="content"><a href="#PageTop" title="<?php echo  TXTEDIT_PAGETOP_HELP ?>"><b><?php echo  TXTEDIT_PAGETOP ?></b></a></td>
				<td width="50%" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  TXTEDIT_CLOSEWIN_HELP ?>"><b><?php echo  TXTEDIT_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
</div>

	<a name="PageBot"></a>
</BODY>
</HTML>

<?php

  include (INTERFACE_FOOTER);

  exit;

?>
