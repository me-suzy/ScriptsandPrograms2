<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

########################################

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-search-adv.php");

########################################

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  SEARCH_ADV_HEADER ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

	<div align=center>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
		<form action='<?php echo WEB_PATH?>/search.php' method=post name='searchForm' target="NetjukeMain">
        <input type=hidden name='do' value='search.adv'>
			<tr>
				<td align=left class="header" colspan=2><b><?php echo  SEARCH_ADV_HEADER ?></b></td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content">
				  <?php echo  SEARCH_ADV_FORM_COND ?>
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="radio" name="condition" value="and" CHECKED><?php echo  SEARCH_ADV_FORM_COND_AND ?>
				  <input type="radio" name="condition" value="or"><?php echo  SEARCH_ADV_FORM_COND_OR ?>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content">
				  <?php echo  SEARCH_ADV_FORM_TR ?>
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_name" value="" size="35" maxlength="100" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content">
				  <?php echo  SEARCH_ADV_FORM_AR ?>
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="ar_name" value="" size="35" maxlength="100" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap>
				  <?php echo  SEARCH_ADV_FORM_AL ?>
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="al_name" value="" size="35" maxlength="100" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap>
				  <?php echo  SEARCH_ADV_FORM_GE ?>
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="ge_name" value="" size="35" maxlength="100" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_TI ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_time_op').' '?>
				  <input type="text" name="tr_time" value="" size="4" maxlength="4" style="text-align: right;" class=input_content>
				  <?php echo  SEARCH_ADV_FORM_SECONDS ?>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_TN ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_track_number_op').' '?>
				  <input type="text" name="tr_track_number" value="" size="3" maxlength="3" style="text-align: right;" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_BR ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_bit_rate_op').' '.valueSelect('netjuke_tracks','tr_bit_rate','bit_rate',true)?> kbps
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_SR ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_sample_rate_op').' '.valueSelect('netjuke_tracks','tr_sample_rate','sample_rate',true)?> kHz
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_FK ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo valueSelect('netjuke_tracks','tr_kind','kind',true)?>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_FS ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_size_op').' '?>
				  <input type="text" name="tr_size" value="" size="12" maxlength="12" style="text-align: right;" class=input_content> bytes
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_FN ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_location" value="" size="35" maxlength="" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_CM ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_comments" value="" size="35" maxlength="1000" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_LY ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_lyrics" value="" size="35" maxlength="1000" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  SEARCH_ADV_FORM_LC ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <?php echo operatorSelect('tr_dl_cnt_op').' '?>
				  <input type="text" name="tr_dl_cnt" value="" size="12" maxlength="50" style="text-align: right;" class=input_content>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center valign=middle class="content">
				  <input type=submit name="btn_insert" value='<?php echo  SEARCH_ADV_FORM_BTN_SEARCH ?>' class='btn_content'>
				  <input type=reset value='<?php echo  SEARCH_ADV_FORM_BTN_RESET ?>' class='btn_content'>
				</td>
			</tr>
		</form>
		</table>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  SEARCH_ADV_CLOSEWIN_HELP ?>"><b><?php echo  SEARCH_ADV_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>

	<a name="PageBot"></a>
</BODY>
</HTML>

<?php

##################################################

function valueSelect($table='netjuke_tracks',$name='tr_bit_rate',$col='bit_rate',$empty_val=false,$onchange="") {

  global $dbconn;
  
  $dbrs = $dbconn->Execute("select $col from netjuke_tracks group by $col order by $col asc");

  $html = "<select name='$name' class=input_content onchange='$onchange'>";
  
  if ($empty_val == true) $html .= "<OPTION VALUE=''>".SEARCH_ADV_ANY_VALUE."</OPTION>";

  $rows = $dbrs->RecordCount();

  if ($rows > 0) {

    while (!$dbrs->EOF) {
      
      $html .= "<OPTION VALUE='".$dbrs->fields[0]."'>".$dbrs->fields[0]."</OPTION>";
      
      $dbrs->MoveNext();
    
    }
    
  }
  
  $dbrs->Close();

  $html .= "</SELECT>";

  return $html;

}

##################################################

function operatorSelect($name,$onchange="") {

  $vals = array('=','>','>=','<','<=');

  $html = "<select name='$name' class=input_content onchange='$onchange'>";

  foreach ($vals as $val) {
    
    $html .= "<OPTION VALUE='".$val."'>".$val."</OPTION>";
    
  }

  $html .= "</SELECT>";

  return $html;

}

##################################################

?>