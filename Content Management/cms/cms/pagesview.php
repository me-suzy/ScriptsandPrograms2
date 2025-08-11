<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: pagesview.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  View one page contents
// ----------------------------------------------------------------------

session_start();

?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	 if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	$key = 1;
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$tkey = "" . $key . "";
$strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if ($rs->RecordCount()	== 0 ) {
    $strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey;
    $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    if ($rs->RecordCount()  == 0 ) {
	    header("Location:page-1.html");
    }
}

// get the field contents
$x_id = @$rs->fields["id"];
$x_title = @$rs->fields["title"];
$x_content = @$rs->fields["content"];
$x_views = @$rs->fields["views"];
$x_parent_id = @$rs->fields["parent_id"];
$x_status = @$rs->fields["status"];
$x_lang = @$rs->fields["lang"];
$x_to_sell = @$rs->fields["to_sell"];
$x_price = @$rs->fields["price"];
$x_mode = @$rs->fields["mode"];
$x_mode_ext = @$rs->fields["mode_ext"];
$x_privilege = @$rs->fields["privilege"];
$x_modified = @$rs->fields["modified"];
$x_create = @$rs->fields["create"];
$x_user_id = @$rs->fields["user_id"];

// Stop email address harvisting robots
$x_content = str_replace("@", "<script language='JavaScript'>document.write('@');</script>", $x_content);


// update page views counter
$x_views++;
$strsql = "UPDATE `pages` SET `views`=$x_views WHERE `id`=" . $x_id . " AND `lang`='" . $lang . "'";
$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

// get the parent page id and title
$x_parent_title = '';
if ($x_parent_id && $x_parent_id != $key){
  $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_parent_id . " AND `lang`='" . $lang . "'";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  if ($rs->RecordCount()) {
      $x_parent_title = @$rs->fields["title"];
  }
}

$path = 'http://'.$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
$path = str_replace("pagesview.php", "pagesprint.php", $path);
?>
<?php include_once ("header.php") ?>
<script language="JavaScript">
function ShowHide(oObj)
{
    if(oObj.style.display == 'none')
    {
	oObj.style.display = '';
    }
    else
    {
	oObj.style.display = 'none';
    }
}
</script>
<table width="98%" border="0" cellspacing="3" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	    <tr>
		<td class="linksBlock">
		<?php if (@$_SESSION["status"] == "login"){ ?>
		<a href="pagesedit.php?key=<?php echo $key; ?>"><img src="cmsimages/edit.gif" width="20" height="18" border=0 alt="<?php echo LBL_EDIT_LINK; ?>"></a>
		<?php } ?>
		<?php if (@$cart_mode == 1){ ?>
		<a href="pagescart.php"><img src="cmsimages/cart.gif" width="25" height="19" border="0" alt="<?php echo LBL_CART; ?>"></a>
		<?php } ?>
		<a href="print-<?php echo $key; ?>.html" target="_blank"><img src="cmsimages/print.gif" width="19" height="19" border="0" alt="<?php echo LBL_PRINT; ?>"></a>
		<a href="mailto:?subject=<?php echo TELL_SUBJECT; ?>&amp;body=<?php echo TELL_BODY."\n\nhttp://".@$_SERVER[HTTP_HOST] . @$_SERVER[REQUEST_URI]?>">
			  <img src="cmsimages/email.gif" width="17" height="19" border="0" alt="<?php echo LBL_TELL; ?>"></a>
			  <A href="javascript:window.external.AddFavorite('<?php echo "http://" . @$_SERVER[HTTP_HOST] . @$_SERVER[REQUEST_URI] ; ?>','<?php echo SITE_TITLE; ?>')">
	      <img src="cmsimages/fav.gif" width="17" height="17" border="0" alt="<?php echo LBL_FAV; ?>"></a>
	      <a href="pagespdf.php?key=<?php echo $key; ?>" target="_blank"><img src="cmsimages/pdf.gif" width="16" height="16" border="0" alt="<?php echo LBL_PDF; ?>"></a>
	      <?php if ($x_parent_title){ ?>
	      <img src="cmsimages/up.gif" width="15" height="15" border="0" alt=""> <a href="page-<?php echo $x_parent_id; ?>.html"><?php echo LBL_BACK . " " . $x_parent_title; ?></a>
	      <?php } ?>
	      <font size=-2><?php
		   if($view_last_update_at == 1){ echo " " . LAST_UPDATED_AT . " " . date($view_date_format, $x_modified); }
		   if($view_last_update_by == 1){ echo " " . LAST_UPDATED_BY . " " . ucfirst(strtolower($x_user_id)); }
	      ?></font>
	      </td>
	      <td class="linksBlock" nowrap align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="page-<?php echo $key; ?>-<?php echo $langparam; ?>.html"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      </td>
	  </tr>
<tr><td class="pageTitle" colspan="2"><?php echo $x_title; ?>&nbsp;</td></tr>
<tr>
	<td class="bodyBlock" colspan="2">
	<?php
		//echo str_replace(chr(10), "<br>" ,@$x_content . "")
		echo @$x_content;
	?>&nbsp;</td>
</tr>
<?php if($x_to_sell==1){ ?>
		  <tr>
			<td colspan="2" class="bodyBlock">
			<b><?php echo LBL_PRICE; ?>:</b> <?php echo sprintf("%01.2f", @$x_price); ?> USD.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="pagescart.php?key=<?php echo $key; ?>&action=add">
			<img src="cmsimages/cart.gif" width="25" height="19" border="0" alt="<?php echo LBL_CART_ADD; ?>">
			<?php echo LBL_CART_ADD; ?></a>
			&nbsp;</td>
		  </tr>
<?php } ?>
<tr>
	      <td class="linksBlock" colspan="2">
<?php
if($key != 1 || $homeLinks == 1){
  $strsql = "SELECT * FROM `pages` WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND `parent_id`=" . $key . " AND `lang`='" . $lang . "' ORDER BY `order`";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  while (!$rs->EOF) {
      $link_title = @$rs->fields["title"];
      $link_id = @$rs->fields["id"];
      if ($key != $link_id){
		echo "\n<img src=\"cmsimages/dot.gif\" width=16 height=16 alt=\"\"> <a href=\"page-$link_id.html\">$link_title</a><br>";
      }
	  $rs->MoveNext();
  }
}
?>
</td></tr>
<?php if($x_mode > 0){ ?>
<script language="JavaScript">
<!-- start Javascript
function  checkForm(frm) {
      if(frm.x_alias.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_alias.focus();
	      return false;
      }

      if(frm.x_email.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_email.focus();
	      return false;
      }
<?
if ($x_mode == 4){
    $frmFields = split("\|",$x_mode_ext);
    $i = 0;
    foreach ($frmFields as $field) {
	if($field != '') echo "if(frm.frmEmail$i.value == ''){alert('".INVALID_MSG."'); frm.frmEmail$i.focus(); return false; }";
	$i++;
    }
}
?>
      if(frm.x_content.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_content.focus();
	      return false;
      }

      return true;
}

// end JavaScript -->
</script>
		  <tr>
			<td colspan="2" class="bodyBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
			<a href="javascript:ShowHide(Add_Form)"><?php echo INTERAVTIVE_ADD; ?></a>
			<img src="cmsimages/inbox.png" width="48" height="48">
			<span id=Add_Form style="DISPLAY: <?php if($x_mode == 2 || $x_mode == 3){ echo 'none'; } ?>">
			<form action="entryadd.php" method="POST" name="entryadd" onSubmit="return checkForm(this);"><center>
			<input type="hidden" name="key" value="<?php echo $key; ?>">
			<input type="hidden" name="x_mode" value="<?php echo $x_mode; ?>">
			<table border=0>
			       <tr>
				   <td class="linksBlock"><?php echo ENTRY_NAME; ?></td>
				   <td><input type="text" name="x_alias" size=30></td>
			       </tr>
			       <tr>
				   <td class="linksBlock"><?php echo ENTRY_EMAIL; ?></td>
				   <td><input type="text" name="x_email" size=30></td>
			       </tr>
<?
if ($x_mode == 4){
    $frmFields = split("\|",$x_mode_ext);
    $i = 0;
    foreach ($frmFields as $field) {
	if($field != '') echo "<tr><td class=\"linksBlock\">$field</td><td><input name=\"frmEmail$i\" size=30></td></tr>";
	$i++;
    }
}
?>
			       <tr>
				   <td colspan="2" class="linksBlock"><?php echo ENTRY_CONTENT; ?></td>
			       </tr><tr>
				   <td colspan="2"><textarea name="x_content" cols=60 rows=5></textarea></td>
			       </tr>
			       <tr>
				   <td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo ENTRY_ADD; ?>"></td>
			       </tr>
			</table></center></form>
			</span>
			<?php
			  if(($x_mode == 1 && @$_SESSION["status"] == "login") || $x_mode > 1){
			?>
			<table width=100% align="center" border=0 cellspacing="2" cellpadding="2">
			<?php
			      if($x_mode == 3 && @$_SESSION["status"] != "login"){ $extra = " AND status=1"; }else{ $extra = ''; }
			      $strsql = "SELECT * FROM `interactive` WHERE page_id=$key $extra ORDER BY id";
			      $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
			      while (!$rs->EOF) {
				    $x_id      = @$rs->fields["id"];
				    $x_alias   = @$rs->fields["alias"];
				    $x_email   = @$rs->fields["email"];
				    $x_content = @$rs->fields["content"];
				    $x_status  = @$rs->fields["status"];

				    if(DIRECTION == "RTL"){ $x_align = "left"; }else{ $x_align = "right"; }
				    $x_content = str_replace("\n","<br>",$x_content);

				    echo "<tr><td class=entryHeader><a href=\"mailto: $x_email\">$x_alias</a> ";
				    if(@$_SESSION["status"] == "login"){
					echo "[ <a href=entrydel.php?key=$x_id&back_id=$key onClick=\"return confirm('".ENTRY_DEL_MSG."')\">".ENTRY_DEL."</a> ";
					if($x_mode == 3){
					    if($x_status == 0){
					       echo "| <a href=entrystatus.php?key=$x_id&back_id=$key&status=1>".ENTRY_SHOW."</a> ";
					    }else{
					       echo "| <a href=entrystatus.php?key=$x_id&back_id=$key&status=0>".ENTRY_HIDE."</a> ";
					    }
					}
					echo "]";
				    }
				    echo "</td></tr>";
				    echo "<tr><td class=entryBody>$x_content</td></tr>";

				    $rs->MoveNext();
			      }
			?>
			</table>
			<?php  } ?>
			&nbsp;</td>
		  </tr>
<?php } ?>
<?php  if($viewCounter == 1 || @$_SESSION["status"] == "login"){ ?>
<tr>
	<td width="100%">&nbsp;</td>
	<td class="linksBlock" nowrap><br><img src="cmsimages/num.gif" width="20" height="23" border="0" alt=""> <?php echo LBL_VIEWS1 . "<b>" . $x_views . "</b>" . LBL_VIEWS2; ?></td>
</tr>
<?php  } ?>
</table>
<?php
	$db->Close();
	include_once ("footer.php");
?>
