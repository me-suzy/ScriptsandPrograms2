<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-filter.php");

switch ($_REQUEST['do']) {
  case 'list.artists':
    $do = $_REQUEST['do'];
    $col_title = FILTER_ARTISTS;
    $img_sql = ', ar.img_src ';
    $play_type = 'ar';
    $filter_lnk = WEB_PATH.'/filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=';
    $filter_help = FILTER_FILTER_ARTISTS_HELP;
    break;
  case 'list.albums':
    $do = $_REQUEST['do'];
    $col_title = FILTER_ALBUMS;
    $img_sql = ', al.img_src ';
    $play_type = 'al';
    $filter_lnk = WEB_PATH.'/filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=';
    $filter_help = FILTER_FILTER_ALBUMS_HELP;
    break;
  default:
    $do = 'list.genres';
    $col_title = FILTER_GENRES;
    $img_sql = ', ge.img_src ';
    $play_type = 'ge';
}

$col_prefix = substr($do,5,2);

if (substr($_REQUEST['search_do'],0,5) == "list.") {

  // TRACK LISTING
  $clause = $_REQUEST['col']." = ".$_REQUEST['val'];

} elseif ($_REQUEST['search_do'] == "search.adv") {

  // ADVANCED SEARCH
  $clause = obfuscate_undo($_REQUEST['clause']);

} else {

  // SIMPLE SEARCH
  $clause = "lower(".$_REQUEST['col'].") like '%".strtolower($_REQUEST['val'])."%'";

}

$sql = " select tr.".$col_prefix."_id, ".$col_prefix.".name, count(tr.id)".$img_sql
     . " from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al, netjuke_genres ge"
     . " where ".$clause
     . " and ar.id = tr.ar_id and al.id = tr.al_id and ge.id = tr.ge_id "
     . " group by tr.".$col_prefix."_id, ".$col_prefix.".name".$img_sql
     . " order by upper(".$col_prefix.".name)";

$dbrs = $dbconn->Execute($sql);

$cnt = 1;
   
$rows = $dbrs->RecordCount();
   
while (!$dbrs->EOF) {

  if (strlen($dbrs->fields[3]) > 0) $img_icn = image_icon($dbrs->fields[3]);
  
  if ($_REQUEST['search_do'] == "search.adv") {
    // ADVANCED SEARCH
    $html_link = "search.php?do=".$_REQUEST['search_do']."&clause=".$_REQUEST['clause']."&sort=".$col_prefix."&filter=".$col_prefix."_id=".$dbrs->fields[0];
  } else {
    // SIMPLE SEARCH AND TRACK LISTING
    $html_link = "search.php?do=".$_REQUEST['search_do']."&col=".$_REQUEST['col']."&val=".$_REQUEST['val']."&sort=".$col_prefix."&filter=".$col_prefix."_id=".$dbrs->fields[0];
  }
  
  if ($play_type != 'ge') {
    $filter_btn = "&nbsp;<a href='".$filter_lnk.$dbrs->fields[0]."' target='_self' title='".$filter_help."'><img src='".$ICONS['info']."' width=8 height=8 border=0 valign=absmiddle alt='".$filter_help."'></a>";
  }
    
  $html .= "<tr><td width='5%' class='content' align=right valign=top>".$cnt."</td>"
        .  "<td width='90%' class='content' align=left valign=top>"
        .  "<a href='./play.php?do=play_all&type=".$play_type."&id=".$dbrs->fields[0]."' title=\"".FILTER_PLAY_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 1))."\"><img alt='".FILTER_PLAY_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 1))."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
        .  " ".$filter_btn
        .  " <a href='".$html_link."' target='NetjukeMain' title='".FILTER_LIST_ALL_HELP." ".substr($col_title,0,(strlen($col_title) - 1))."'>".format_for_display($dbrs->fields[1])."</a>&nbsp;".$img_icn."</td>"
        .  "<td width='5%' class='content' align=center valign=top>".$dbrs->fields[2]."</td></tr>\n";
  
  $cnt++;
      
  $dbrs->MoveNext();

}

$temp_vals = explode('?',$_SERVER['HTTP_REFERER']);
$pathinfo = explode('/',$temp_vals[0]);
$refscript = array_pop($pathinfo);

if (    ($refscript == 'alphabet.php')
     || ($refscript == 'filter.php') ) {
  $back_btn = '<td align="center" class="content"><a href="javascript:self.history.go(-1);" title="'.FILTER_BACKBTN_HELP.'"><b>'. FILTER_BACKBTN .'</b></a></td>';
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  FILTER_HEADER ?>: <?php echo $col_title?>: <?php echo $rows?> <?php echo  FILTER_FOUND ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>
	<div align=center>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
			<tr>
				<td width="90%" class="header" align=left colspan=2><b><?php echo  FILTER_HEADER ?>: <?php echo $col_title?>: <?php echo $rows?> <?php echo  FILTER_FOUND ?></b></td>
				<td width="10%" class="header"><b><?php echo  FILTER_TRACKS ?></b></td>
			</tr>
			<?php echo $html?>
		</table>
		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<?php echo  $back_btn ?>
				<td align="center" class="content"><a href="#PageTop" title="<?php echo  FILTER_PAGETOP_HELP ?>"><b><?php echo  FILTER_PAGETOP ?></b></a></td>
				<td align="center" class="content"><a href="javascript:window.close();" title="<?php echo  FILTER_CLOSEWIN_HELP ?>"><b><?php echo  FILTER_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>
</BODY>
</HTML>
