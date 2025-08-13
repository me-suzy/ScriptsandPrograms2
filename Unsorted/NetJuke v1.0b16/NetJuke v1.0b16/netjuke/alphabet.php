<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-alphabet.php");

switch ($_REQUEST['do']) {
  case "alpha.artists":
    $table_1 = "netjuke_artists ar";
    $col = "ar";
    $col_title = ALPHA_ARTISTS.": ".$_REQUEST['val'];
    $filter_lnk = WEB_PATH.'/filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=';
    $filter_help = ALPHA_FILTER_ARTISTS_HELP;
    break;
  default:
    // $_REQUEST['do'] = "alpha.albums";
    $table_1 = "netjuke_albums al";
    $col = "al";
    $col_title = ALPHA_ALBUMS.": ".$_REQUEST['val'];
    $filter_lnk = WEB_PATH.'/filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=';
    $filter_help = ALPHA_FILTER_ALBUMS_HELP;
}

$sql = " select ".$col.".id, ".$col.".name, ".$col.".img_src, ".$col.".track_cnt "
     . " from ".$table_1
     . " where lower(".$col.".name) like '".strtolower($_REQUEST['val'])."%' "
     . " and track_cnt > 0 "
     . " order by upper(".$col.".name) ";

$dbrs = $dbconn->Execute($sql);

$cnt = 1;
   
$rows = $dbrs->RecordCount();
   
while (!$dbrs->EOF) {

  $html .= "<tr><td width='5%' class='content' align=right valign=top>".$cnt."</td>"
        .  "<td width='90%' class='content' align=left valign=top>"
        .  "<a href='./play.php?do=play_all&type=".$col."&id=".$dbrs->fields[0]."' title=\"".ALPHA_PLAY_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 4))."\"><img alt='".ALPHA_PLAY_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 4))."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
        .  "&nbsp;<a href='".$filter_lnk.$dbrs->fields[0]."' target='_self' title='".$filter_help."'><img src='".$ICONS['info']."' width=8 height=8 border=0 valign=absmiddle alt='".$filter_help."'></a>"
        .  "&nbsp;<a href='search.php?do=list.tracks&col=".$col."_id&val=".$dbrs->fields[0]."&sort=".$col
        .  "' target='NetjukeMain'  title='".ALPHA_LIST_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 4))."'>".format_for_display($dbrs->fields[1])."</a>"
        .  "</td><td width='5%' class='content' align=center valign=top>".$dbrs->fields[3]."</td></tr>\n";

  $cnt++;
      
  $dbrs->MoveNext();

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  ALPHA_HEADER_1 ?>: <?php echo $col_title?>: <?php echo $rows?> <?php echo  ALPHA_FOUND ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>
	<div align=center>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
			<tr>
				<td width="95%" class="header" align=left colspan=2><b><?php echo  ALPHA_HEADER_1 ?>: <?php echo $col_title?>: <?php echo $rows?> <?php echo  ALPHA_FOUND ?></b></td>
				<td width="5%" class="header" align=center><b><?php echo  ALPHA_HEADER_2 ?></b></td>
			</tr>
			<?php echo  $html ?>
		</table>
		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="50%" align="center" class="content"><a href="#PageTop" title="<?php echo  ALPHA_PAGETOP_HELP ?>"><b><?php echo  ALPHA_PAGETOP ?></b></a></td>
				<td width="50%" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  ALPHA_CLOSEWIN_HELP ?>"><b><?php echo  ALPHA_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>
</BODY>
</HTML>
