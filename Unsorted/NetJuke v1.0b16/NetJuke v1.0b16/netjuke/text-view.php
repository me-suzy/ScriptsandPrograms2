<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-text-view.php");

switch ($_REQUEST['type']) {
  case 'lyrics':
    $col = $_REQUEST['type'];
    $header = TXTVIEW_HEADER_LYRICS;
    break;
  default:
    $col = 'comments';
    $header = TXTVIEW_HEADER_COMMENTS;
}

$sql = ' SELECT tr.'.$col.', tr.name, ar.name '
     . ' FROM netjuke_tracks tr, netjuke_artists ar '
     . ' WHERE tr.id = '. abs($_REQUEST['id'])
     . ' AND ar.id = tr.ar_id ';

$dbrs = $dbconn->Execute($sql);

if ($dbrs->RecordCount() != 1) {

  javascript("alert('".TXTVIEW_NOID."'); self.close();");

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
  <tr>
    <td class='header' nowrap><B><?php echo  $header ?></B></td>
  </tr>
  <tr>
    <td width="100%" align=left wrap="virtual" class="content">
      <pre class="content"><?php echo  rawurldecode($dbrs->fields[0]) ?></pre>
    </td>
  </tr>
  </table>

</div>
		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="50%" align="center" class="content"><a href="#PageTop" title="<?php echo  TXTVIEW_PAGETOP_HELP ?>"><b><?php echo  TXTVIEW_PAGETOP ?></b></a></td>
				<td width="50%" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  TXTVIEW_CLOSEWIN_HELP ?>"><b><?php echo  TXTVIEW_CLOSEWIN ?></b></a></td>
			</tr>
		</table>

	<a name="PageBot"></a>
</BODY>
</HTML>

<?php

  include (INTERFACE_FOOTER);

  exit;

?>
