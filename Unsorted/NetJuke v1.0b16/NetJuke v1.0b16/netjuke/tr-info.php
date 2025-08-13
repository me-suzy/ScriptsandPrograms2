<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-tr-info.php");

$sql = " SELECT tr.id, tr.name, tr.ar_id, ar.name "
     . "      , tr.al_id, al.name, tr.ge_id, ge.name "
     . "      , tr.size, tr.time, tr.track_number "
     . "      , tr.bit_rate, tr.sample_rate, tr.kind "
     . "      , tr.location, tr.dl_cnt "
     . "      , tr.img_src, ar.img_src, al.img_src "
     . "      , tr.comments, tr.lyrics "
     . " FROM netjuke_tracks tr, netjuke_artists ar "
     . "    , netjuke_albums al, netjuke_genres ge "
     . " WHERE tr.id = " . abs($_REQUEST['id'])
     . "   AND tr.ar_id = ar.id"
     . "   AND tr.al_id = al.id"
     . "   AND tr.ge_id = ge.id";

$dbrs = $dbconn->Execute($sql);

if ($dbrs->RecordCount() != 1) {

  javascript("alert('".TRINFO_NOID."'); self.close();");

}
    
$time = myTimeFormat($dbrs->fields[9]);

$file_size = myFilesizeFormat($dbrs->fields[8]);

$file_name = split("/",$dbrs->fields[14]);

$tr_img = check_image($dbrs->fields[16]);
$ar_img = check_image($dbrs->fields[17]);
$al_img = check_image($dbrs->fields[18]);

if (strlen($dbrs->fields[19]) > 0) {
  $comments = true;
} else {
  $comments_lnk = '';
}

if (strlen($dbrs->fields[20]) > 0) {
  $lyrics = true;
} else {
  $lyrics_lnk = '';
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  TRINFO_HEADER ?>: <?php echo format_for_display($dbrs->fields[1])?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>
	<div align=center>

		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
			<tr>
				<td align=left class="header" colspan=2><b><?php echo  TRINFO_HEADER ?></b></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content"><?php echo  TRINFO_FORM_TR ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo format_for_display($dbrs->fields[1])?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content"><?php echo  TRINFO_FORM_AR ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo format_for_display($dbrs->fields[3])?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_AL ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo format_for_display($dbrs->fields[5])?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_GE ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo format_for_display($dbrs->fields[7])?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_TI ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $time?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_TN ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $dbrs->fields[10]?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_BR ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $dbrs->fields[11]?> kbps</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_SR ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $dbrs->fields[12]?> kHz</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_FK ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $dbrs->fields[13]?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_FS ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $file_size?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_FN ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo format_for_display(rawurldecode($file_name[count($file_name) - 1]))?></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRINFO_FORM_LC ?></td>
				<td width="70%" align=left valign=top class="content"><?php echo $dbrs->fields[15]?></td>
			</tr>
		</table>

<?php if ( ($comments == true) || ($lyrics == true) ) { ?>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>

  <?php if ($comments == true) { ?>

				<td width="50%" align="center" class="content">
				    <a href="<?php echo  WEB_PATH.'/text-view.php?type=comments&id='.$dbrs->fields[0]; ?>" target="NetJukeComments" onClick="window.open('','NetJukeComments','width=400,height=575,top=0,left=520,menubar=no,scrollbars=yes,resizable=yes');" title="TRINFO_FORM_CM"><b><?php echo  TRINFO_FORM_CM ?></b></a><br>
				</td>

  <?php } ?>

  <?php if ($lyrics == true) { ?>

				<td width="50%" align="center" class="content">
				    <a href="<?php echo  WEB_PATH.'/text-view.php?type=lyrics&id='.$dbrs->fields[0]; ?>" target="NetJukeLyrics" onClick="window.open('','NetJukeLyrics','width=400,height=575,top=25,left=545,menubar=no,scrollbars=yes,resizable=yes');" title="TRINFO_FORM_LY"><b><?php echo  TRINFO_FORM_LY ?></b></a><br>
				</td>

  <?php } ?>

			</tr>
		</table>

<?php } ?>

<?php if ( (strlen($tr_img) > 0) || (strlen($ar_img) > 0) || (strlen($al_img) > 0) ) { ?>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>

  <?php if (strlen($tr_img) > 0) { ?>

				<td width="" align="center" class="content">
				    <a href="<?php echo  $tr_img ?>" target="NetJukeImage" onClick="window.open('','NetJukeImage','width=550,height=550,top=50,left=50,menubar=no,scrollbars=yes,resizable=yes');" title="<?php echo  format_for_display($dbrs->fields[1]) ?>"><img src="<?php echo  $tr_img ?>" alt="<?php echo  format_for_display($dbrs->fields[1]) ?>" width="100" height="100" hspace="5" vspace="2" border="1"></a><br><?php echo  TRINFO_IMG_TR ?>
				</td>

  <?php } ?>

  <?php if (strlen($ar_img) > 0) { ?>

				<td width="" align="center" class="content">
				    <a href="<?php echo  $ar_img ?>" target="NetJukeImage" onClick="window.open('','NetJukeImage','width=550,height=550,top=50,left=50,menubar=no,scrollbars=yes,resizable=yes');" title="<?php echo  format_for_display($dbrs->fields[3]) ?>"><img src="<?php echo  $ar_img ?>" alt="<?php echo  format_for_display($dbrs->fields[3]) ?>" width="100" height="100" hspace="5" vspace="2" border="1"></a><br><?php echo  TRINFO_IMG_AR ?>
				</td>

  <?php } ?>

  <?php if (strlen($al_img) > 0) { ?>

				<td width="" align="center" class="content">
				    <a href="<?php echo  $al_img ?>" target="NetJukeImage" onClick="window.open('','NetJukeImage','width=550,height=550,top=50,left=50,menubar=no,scrollbars=yes,resizable=yes');" title="<?php echo  format_for_display($dbrs->fields[5]) ?>"><img src="<?php echo  $al_img ?>" alt="<?php echo  format_for_display($dbrs->fields[5]) ?>" width="100" height="100" hspace="5" vspace="2" border="1"></a><br><?php echo  TRINFO_IMG_AL ?>
				</td>

  <?php } ?>

			</tr>
		</table>

<?php } ?>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="" align="center" class="content"><a href="<?php echo WEB_PATH.'/play.php?do=play&val='.$_REQUEST['id']?>" title="<?php echo  TRINFO_PLAY_HELP ?>"><b><?php echo  TRINFO_PLAY ?></b></a></td>

<?php if (($NETJUKE_SESSION_VARS["gr_id"] < 3) && ($do != 'edit')) { ?>

				<td width="" align="center" class="content"><a href="<?php echo WEB_PATH?>/admin/tr-edit.php?do=edit&tr_id=<?php echo $_REQUEST['id']?>" title="<?php echo  TRINFO_EDIT_HELP ?>"><b><?php echo  TRINFO_EDIT ?></b></a></td>

<?php } ?>

				<td width="" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  TRINFO_CLOSEWIN_HELP ?>"><b><?php echo  TRINFO_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>
</BODY>
</HTML>
