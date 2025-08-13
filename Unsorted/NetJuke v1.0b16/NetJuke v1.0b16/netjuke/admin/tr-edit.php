<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

########################################

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_tr-edit.php");

########################################


$tr_id = $_REQUEST['tr_id'];  // IMPORTANT!!!!!


if (($_REQUEST['do'] == 'delete') && (abs($tr_id) >= 0)) {

  // delete track

  // NOTE: $tr_id = $_REQUEST['tr_id'];
 
  $dbrs = $dbconn->Execute("select id, ar_id, al_id, ge_id from netjuke_tracks where id = ".abs($tr_id));
  
  if ($dbrs->RecordCount() != 0) {
  
    $track_cache = array();
  
    $track_cache['netjuke_artists'][$dbrs->fields[1]] = 1;
    $track_cache['netjuke_albums'][$dbrs->fields[2]] = 1;
    $track_cache['netjuke_genres'][$dbrs->fields[3]] = 1;
    
    track_cache_batch('decrement', $track_cache);
  
    $dbconn->Execute("delete from netjuke_plists_tracks where tr_id = ".$dbrs->fields[0]);
    $dbconn->Execute("delete from netjuke_tracks where id = ".$dbrs->fields[0]);
  
  }
  
  $dbrs->Close();
  
  javascript("alert('".TREDIT_DELETED_HELP."');self.close();");

} elseif (substr($_REQUEST['do'],0,7) == 'delete.') {

  // delete artist, album, genre

  // NOTE: $tr_id = $_REQUEST['tr_id'];
  
  $del_type = substr($_REQUEST['do'],-2,2);
  
  switch ($del_type) {
    case 'ar':
      $table = 'netjuke_artists';
      break;
    case 'al':
      $table = 'netjuke_albums';
      break;
    default:
      $del_type = 'ge';
      $table = 'netjuke_genres';
  }
  
  if ( abs($_REQUEST['id']) > 1  ) {
  
    $dbconn->Execute("update netjuke_tracks set ".$del_type."_id = 1 where ".$del_type."_id = ".abs($_REQUEST['id']));
  
    $dbconn->Execute("delete from ".$table." where id = ".abs($_REQUEST['id']));
    
    // increment the track cache for n/a by 1
    track_cache('reset', $table);
    track_cache('increment', $table, 1, 1);
    
    if (abs($tr_id) > 0) {
    
      header($_SERVER['PHP_SELF'].'?id='.$tr_id);
    
    } else {
    
      javascript('self.close();');
    
    }
  
  } else {
  
    alert(TREDIT_DELCHILD_HELP_1);
  
  }

} elseif ($_REQUEST['do'] == 'save') {

  // NOTE: $tr_id = $_REQUEST['tr_id'];
  
  $tr_name = $_REQUEST['tr_name'];
  $ar_id = $_REQUEST['ar_id'];
  $ar_name = $_REQUEST['ar_name'];
  $al_id = $_REQUEST['al_id'];
  $al_name = $_REQUEST['al_name'];
  $ge_id = $_REQUEST['ge_id'];
  $ge_name = $_REQUEST['ge_name'];
  $tr_size = $_REQUEST['tr_size'];
  $tr_time = $_REQUEST['tr_time'];
  $tr_track_number = $_REQUEST['tr_track_number'];
  $tr_bit_rate = $_REQUEST['tr_bit_rate'];
  $tr_sample_rate = $_REQUEST['tr_sample_rate'];
  $tr_kind = $_REQUEST['tr_kind'];
  $tr_location = separatorCleanup($_REQUEST['tr_location']);
  $tr_dl_cnt = $_REQUEST['tr_dl_cnt'];
  $tr_img = $_REQUEST['tr_img'];
  $ar_img = $_REQUEST['ar_img'];
  $al_img = $_REQUEST['al_img'];
  
  $track_cache_decr = array();
  $track_cache_incr = array();
   
// START ARTIST MAINTENANCE
   
   if ( (abs($_REQUEST['ar_new']) == 1) && ($ar_name != '') ) {
   
     // insert new ar.name if unique, and/or get ar.id
     
     list($ar_id,$errors) = Find_Id_TrEdit('netjuke_artists',$ar_name);
     
     // update it if required
     
     if ( (abs($ar_id) != 0) && ($errors == '') ) {
     
       if (strlen($_REQUEST['btn_insert']) < 1) {
       
         $sql = "update netjuke_tracks set ar_id = '".abs($ar_id)."' where id = ".abs($tr_id);
         
         if ($dbconn->Execute($sql) === false) {
  	       $local_errors .= "- ".TREDIT_ERROR_TR_T." (1) ($value): ".$dbconn->ErrorMsg()."\\n";
         }
       
       }   
     
     } else {

	   $local_errors .= $errors;
     
     }

   
   } elseif ( ($ar_id != $_REQUEST['ar_id_prev']) && (abs($ar_id) != 0) && (strlen($_REQUEST['btn_insert']) < 1) ) {
   
     // update tr.ar_id
     
     $sql = "update netjuke_tracks set ar_id = '".abs($ar_id)."' where id = ".abs($tr_id);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_TR_T." (2) ($tr_id,$ar_id): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   } elseif ($ar_name != '') {
   
     if ((abs($_REQUEST['ar_id_prev']) == 1) && (strtolower($ar_name) != 'n/a')) alert(TREDIT_DELCHILD_HELP_2); 
     
     // update ar.name
     $sql = "update netjuke_artists set name = '".raw_to_db($ar_name)."' where id = ".abs($_REQUEST['ar_id_prev']);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_AR_T." (".$_REQUEST['ar_id_prev'].",$ar_name): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   }
   
   // update related image
   Update_Image('ar', $ar_id, $ar_img);
   
   // update track cnt cache
   $track_cache_decr['netjuke_artists'][abs($_REQUEST['ar_id_prev'])] = 1;
   $track_cache_incr['netjuke_artists'][abs($ar_id)] = 1;

// END ARTIST MAINTENANCE

   
// START ALBUM MAINTENANCE

   if ( (abs($_REQUEST['al_new']) == 1) && ($al_name != '') ) {
   
     // insert new al.name if unique, and/or get al.id

     list($al_id,$errors) = Find_Id_TrEdit('netjuke_albums',$al_name);
     
     // update it if required
     
     if ( (abs($al_id) != 0) && ($errors == '') ) {
     
       if (strlen($_REQUEST['btn_insert']) < 1) {
     
         $sql = "update netjuke_tracks set al_id = '".abs($al_id)."' where id = ".abs($tr_id);
         
         if ($dbconn->Execute($sql) === false) {
  	     $local_errors .= "- ".TREDIT_ERROR_TR_T." (3) ($value): ".$dbconn->ErrorMsg()."\\n";
         }
       
       }
     
     } else {

	   $local_errors .= $errors;
     
     }

   
   } elseif ( ($al_id != $_REQUEST['al_id_prev']) && (abs($al_id) != 0) && (strlen($_REQUEST['btn_insert']) < 1) ) {
   
     // update tr.al_id
     
     $sql = "update netjuke_tracks set al_id = '".abs($al_id)."' where id = ".abs($tr_id);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_TR_T." (4) ($tr_id,$al_id): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   } elseif ($al_name != '') {
   
     if ((abs($_REQUEST['al_id_prev']) == 1) && (strtolower($al_name) != 'n/a')) alert(TREDIT_DELCHILD_HELP_2); 
   
     // update al.name
     $sql = "update netjuke_albums set name = '".raw_to_db($al_name)."' where id = ".abs($_REQUEST['al_id_prev']);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_AL_T." (".$_REQUEST['al_id_prev'].",$al_name): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   }
   
   // update related image
   Update_Image('al', $al_id, $al_img);
   
   // update track cnt cache
   $track_cache_decr['netjuke_albums'][abs($_REQUEST['al_id_prev'])] = 1;
   $track_cache_incr['netjuke_albums'][abs($al_id)] = 1;

// END ALBUM MAINTENANCE

   
// START GENRE MAINTENANCE

   if ( (abs($_REQUEST['ge_new']) == 1) && ($ge_name != '') ) {
   
     // insert new ge.name if unique, and/or get ge.id

     list($ge_id,$errors) = Find_Id_TrEdit('netjuke_genres',$ge_name);
     
     // update it if required
     
     if ( (abs($ge_id) != 0) && ($errors == '') ) {
     
       if (strlen($_REQUEST['btn_insert']) < 1) {
     
         $sql = "update netjuke_tracks set ge_id = '".abs($ge_id)."' where id = ".abs($tr_id);
         
         if ($dbconn->Execute($sql) === false) {
  	     $local_errors .= "- ".TREDIT_ERROR_TR_T." (5) ($value): ".$dbconn->ErrorMsg()."\\n";
         }
       
       }
     
     } else {

	   $local_errors .= $errors;
     
     }

   
   } elseif ( ($ge_id != $_REQUEST['ge_id_prev']) && (abs($ge_id) != 0) && (strlen($_REQUEST['btn_insert']) < 1) ) {
   
     // update tr.ge_id
     
     $sql = "update netjuke_tracks set ge_id = '".abs($ge_id)."' where id = ".abs($tr_id);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_TR_T." (6) ($tr_id,$ge_id): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   } elseif ($ge_name != '') {
   
     if ((abs($_REQUEST['ge_id_prev']) == 1) && (strtolower($ge_name) != 'n/a')) alert(TREDIT_DELCHILD_HELP_2); 
   
     // update ge.name
     $sql = "update netjuke_genres set name = '".raw_to_db($ge_name)."' where id = ".abs($_REQUEST['ge_id_prev']);
       
     if ($dbconn->Execute($sql) === false) {
	   $local_errors .= "- ".TREDIT_ERROR_GE_T." (".$_REQUEST['ge_id_prev'].",$ge_name): ".$dbconn->ErrorMsg()."\\n";
     }     
   
   }
   
   // update related image
   // Update_Image('ge', $ge_id, $ge_img);
   
   // update track cnt cache
   $track_cache_decr['netjuke_genres'][abs($_REQUEST['ge_id_prev'])] = 1;
   $track_cache_incr['netjuke_genres'][abs($ge_id)] = 1;

// END GENRE MAINTENANCE
   
// START TRACK MAINTENANCE

   if ($tr_name == '') {
     $local_errors .= "- ".TREDIT_ERROR_TR."\\n";
   }

   if ($tr_track_number == '') {
     $local_errors .= "- ".TREDIT_ERROR_TN."\\n";
   }

   if ($tr_bit_rate == '') {
     $local_errors .= "- ".TREDIT_ERROR_BR."\\n";
   }

   if ($tr_sample_rate == '') {
     $local_errors .= "- ".TREDIT_ERROR_SR."\\n";
   }

   if ($tr_kind == '') {
     $local_errors .= "- ".TREDIT_ERROR_FK."\\n";
   }

   if ($tr_location == '') {
     $local_errors .= "- ".TREDIT_ERROR_LC."\\n";
   }
   
   if ($local_errors == '') {
   
     if (strlen($_REQUEST['btn_insert']) < 1) {
     
       if (abs($dl_cnt_reset) == 1) $tr_dl_cnt = 0;
     
       $sql = " update netjuke_tracks set "
            . "   name         = '".raw_to_db($tr_name)."' "
            . " , time         = ".abs($tr_time)." "
            . " , track_number = ".abs($tr_track_number)." "
            . " , bit_rate     = ".abs($tr_bit_rate)." "
            . " , sample_rate  = ".abs($tr_sample_rate)." "
            . " , kind         = '".$tr_kind."' "
            . " , size         = ".abs($tr_size)." "
            . " , location     = '".specialUrlEncode($tr_location)."' "
            . " , dl_cnt       = ".abs($tr_dl_cnt)." "
            . " where id = ".abs($tr_id);
  
       if ($dbconn->Execute($sql) === false) {
	   
	     $local_errors .= "- ".TREDIT_ERROR_TR_T." (8 - update) ($sql): ".$dbconn->ErrorMsg()."\\n";
       
       } else {
       
         // decrement track cnt cache
         track_cache_batch('decrement', $track_cache_decr);
       
       }

     } else {
     
       $sql = " insert into netjuke_tracks "
            . " ( ar_id, al_id, ge_id, name, size, time, track_number, "
            . "   bit_rate, sample_rate, kind, location ) "
            . " values ( "
            . " $ar_id, $al_id, $ge_id, '".raw_to_db($tr_name)."', "
            . " ".abs($tr_size).", ".abs($tr_time).", ".abs($tr_track_number).", "
            . " ".abs($tr_bit_rate).", ".abs($tr_sample_rate).", '".$tr_kind."', "
            . " '".specialUrlEncode($tr_location)."' "
            . " ) ";
  
       if ($dbconn->Execute($sql) === false) {
	   
	     $local_errors .= "- ".TREDIT_ERROR_TR_T." (8 - insert) ($sql): ".$dbconn->ErrorMsg()."\\n";
       
       } else {
       
         $sql = " select id from netjuke_tracks "
              . " where location = '".specialUrlEncode($tr_location)."' "
              . " order by id desc ";
         
         $dbrs = $dbconn->Execute( $sql );
         
         # no need to loop, we only want the top record
         $tr_id_new = $dbrs->fields[0];
         
         if ($tr_id_new == $tr_id) {
         
           # if the new and old id are the same, then we
           # did not duplicate the record, did we? ;o)
           alert("- ".TREDIT_ERROR_TR_T);
         
         } else {
         
           $tr_id = $tr_id_new;
         
         }
       
       }
     
     }
   
   }
   
   if ($local_errors != '') alert($local_errors);
   
   // update related image
   Update_Image('tr', $tr_id, $tr_img);
   
   // increment track cnt cache
   track_cache_batch('increment', $track_cache_incr);
   
// END TRACK MAINTENANCE

   CacheCleanUp();

}

if (abs($tr_id) > 0) {

  $sql = " SELECT tr.id, tr.name, tr.ar_id, ar.name "
       . "      , tr.al_id, al.name, tr.ge_id, ge.name "
       . "      , tr.size, tr.time, tr.track_number "
       . "      , tr.bit_rate, tr.sample_rate, tr.kind "
       . "      , tr.location, tr.dl_cnt "
       . "      , tr.img_src, ar.img_src, al.img_src "
       . " FROM netjuke_tracks tr, netjuke_artists ar "
       . "    , netjuke_albums al, netjuke_genres ge "
       . " WHERE tr.id = " . abs($tr_id)
       . "   AND tr.ar_id = ar.id"
       . "   AND tr.al_id = al.id"
       . "   AND tr.ge_id = ge.id";
  
  $dbrs = $dbconn->Execute($sql);
  
  if ($dbrs->RecordCount() != 1) {
  
    javascript("alert('".TREDIT_ERROR_NORES."'); self.close();");
  
  }
  
  $tr_id = $dbrs->fields[0];
  $tr_name = $dbrs->fields[1];
  $ar_id = $dbrs->fields[2];
  $ar_name = $dbrs->fields[3];
  $al_id = $dbrs->fields[4];
  $al_name = $dbrs->fields[5];
  $ge_id = $dbrs->fields[6];
  $ge_name = $dbrs->fields[7];
  $tr_size = $dbrs->fields[8];
  $tr_time = $dbrs->fields[9];
  $tr_track_number = $dbrs->fields[10];
  $tr_bit_rate = $dbrs->fields[11];
  $tr_sample_rate = $dbrs->fields[12];
  $tr_kind = $dbrs->fields[13];
  $tr_location = separatorCleanup($dbrs->fields[14]);
  $tr_dl_cnt = $dbrs->fields[15];
  $tr_img = $dbrs->fields[16];
  $ar_img = $dbrs->fields[17];
  $al_img = $dbrs->fields[18];

}

// track image field and icon
$tr_img = eregi_replace( WEB_PATH.ARTWORK_DIR, '', $tr_img );
$tr_icn = image_icon($tr_img);

$ar_img = eregi_replace( WEB_PATH.ARTWORK_DIR, '', $ar_img );
$ar_icn = image_icon($ar_img);

$al_img = eregi_replace( WEB_PATH.ARTWORK_DIR, '', $al_img );
$al_icn = image_icon($al_img);

$time = myTimeFormat($tr_time);

$file_size = myFilesizeFormat($dbrs->fields[8]);

########################################

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  TREDIT_HEADER ?>: <?php echo format_for_display($dbrs->fields[1])?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

	<div align=center>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
			<tr>
				<td align=left class="header" colspan=2><b><?php echo  TREDIT_HEADER ?></b></td>
			</tr>
		<form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='trackForm'>
        <input type=hidden name='do' value='save'>
        <input type=hidden name='tr_id' value='<?php echo $tr_id?>'>
			<tr>
				<td width="30%" align=right valign=middle class="content"><?php echo  TREDIT_FORM_TR ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_name" value="<?php echo format_for_display($tr_name)?>" size="35" maxlength="100" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content">
				  <?php echo  TREDIT_FORM_AR ?>
				  <br>
				  <?php echo  TREDIT_FORM_NEW ?> <input type="checkbox" name="ar_new" value="1">
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type=hidden name='ar_id_prev' value='<?php echo $ar_id?>'>
				  <table width='100%' border=0 cellspacing=0 cellpadding=0><tr><td align=left>
				    <?php echo pairSelect('netjuke_artists','ar_id',$ar_id)?>
				  </td><td align=center>
				    <?php if (abs($tr_id) > 0) { ?>
				      <input type=button name="btn_del" value='<?php echo  TREDIT_FORM_BTN_DEL ?>' class='btn_content' onclick="if (confirm('<?php echo  TREDIT_FORM_BTN_DELCHILD_CONF ?>'))  top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=delete.ar&id=' + document.trackForm.ar_id.options[document.trackForm.ar_id.selectedIndex].value + '&tr_id=<?php echo abs($tr_id)?>'; ">
				    <?php } ?>
				  </td></tr><tr><td align=left>
				    <input type="text" name="ar_name" value="<?php echo format_for_display($ar_name)?>" size="35" maxlength="100" class=input_content>
				  </td></tr></table>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap>
				  <?php echo  TREDIT_FORM_AL ?>
				  <br>
				  <?php echo  TREDIT_FORM_NEW ?> <input type="checkbox" name="al_new" value="1">
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type=hidden name='al_id_prev' value='<?php echo $al_id?>'>
				  <table width='100%' border=0 cellspacing=0 cellpadding=0><tr><td align=left>
				    <?php echo pairSelect('netjuke_albums','al_id',$al_id)?>
				  </td><td align=center>
				    <?php if (abs($tr_id) > 0) { ?>
				      <input type=button name="btn_del" value='<?php echo  TREDIT_FORM_BTN_DEL ?>' class='btn_content' onclick="if (confirm('<?php echo  TREDIT_FORM_BTN_DELCHILD_CONF ?>'))  top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=delete.al&id=' + document.trackForm.al_id.options[document.trackForm.al_id.selectedIndex].value + '&tr_id=<?php echo abs($tr_id)?>'; ">
				    <?php } ?>
				  </td></tr><tr><td align=left>
				    <input type="text" name="al_name" value="<?php echo format_for_display($al_name)?>" size="35" maxlength="100" class=input_content>
				  </td></tr></table>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap>
				  <?php echo  TREDIT_FORM_GE ?>
				  <br>
				  <?php echo  TREDIT_FORM_NEW ?> <input type="checkbox" name="ge_new" value="1">
				</td>
				<td width="70%" align=left valign=middle class="content">
				  <input type=hidden name='ge_id_prev' value='<?php echo $ge_id?>'>
				  <table width='100%' border=0 cellspacing=0 cellpadding=0><tr><td align=left>
				    <?php echo pairSelect('netjuke_genres','ge_id',$ge_id)?>
				  </td><td align=center>
				    <?php if (abs($tr_id) > 0) { ?>
				      <input type=button name="btn_del" value='<?php echo  TREDIT_FORM_BTN_DEL ?>' class='btn_content' onclick="if (confirm('<?php echo  TREDIT_FORM_BTN_DELCHILD_CONF ?>'))  top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=delete.ge&id=' + document.trackForm.ge_id.options[document.trackForm.ge_id.selectedIndex].value + '&tr_id=<?php echo abs($tr_id)?>'; ">
				    <?php } ?>
				  </td></tr><tr><td align=left>
				    <input type="text" name="ge_name" value="<?php echo format_for_display($ge_name)?>" size="35" maxlength="100" class=input_content>
				  </td></tr></table>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_TI ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_time" value="<?php echo abs($tr_time)?>" size="4" maxlength="4" style="text-align: right;" class=input_content>
				  <?php echo  TREDIT_FORM_SECONDS." (".$time.")" ?>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_TN ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_track_number" value="<?php echo abs($tr_track_number)?>" size="3" maxlength="3" style="text-align: right;" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_BR ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_bit_rate" value="<?php echo abs($tr_bit_rate)?>" size="3" maxlength="3" style="text-align: right;" class=input_content> kbps
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_SR ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_sample_rate" value="<?php echo abs($tr_sample_rate)?>" size="6" maxlength="6" style="text-align: right;" class=input_content> kHz
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_FK ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_kind" value="<?php echo $tr_kind?>" size="30" maxlength="30" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_FS ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_size" value="<?php echo abs($tr_size)?>" size="12" maxlength="12" style="text-align: right;" class=input_content> bytes
				  (<?php echo $file_size?>)
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_FN ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_location" value="<?php echo format_for_display(rawurldecode($tr_location))?>" size="35" maxlength="" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_TR_IMG . '&nbsp;' . $tr_icn ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="tr_img" value="<?php echo  $tr_img ?>" size="35" maxlength="256" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_AR_IMG . '&nbsp;' . $ar_icn ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="ar_img" value="<?php echo  $ar_img ?>" size="35" maxlength="256" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_AL_IMG . '&nbsp;' . $al_icn ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <input type="text" name="al_img" value="<?php echo  $al_img ?>" size="35" maxlength="256" class=input_content>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=middle class="content" nowrap><?php echo  TREDIT_FORM_LC ?></td>
				<td width="70%" align=left valign=middle class="content">
				  <table width='100%' cellpadding=0 cellspacing=0><tr>
				  <td width='40%' valign=middle nowrap>&nbsp;<?php echo $tr_dl_cnt?><input type=hidden name='tr_dl_cnt' value='<?php echo $dbrs->fields[15]?>'></td>
				  <td width='60%' valign=middle nowrap><input type="checkbox" name="dl_cnt_reset" value="1"><?php echo  TREDIT_FORM_RESET ?></td>
				  </tr></table>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center valign=middle class="content">
				  <?php if (abs($tr_id) < 1) { ?>
				    <input type=submit name="btn_insert" value='<?php echo  TREDIT_FORM_BTN_SAVE ?>' class='btn_content'>
				  <?php } else { ?>
				    <input type=submit name="btn_update" value='<?php echo  TREDIT_FORM_BTN_SAVE ?>' class='btn_content'>
				    <input type=submit name="btn_insert" value='<?php echo  TREDIT_FORM_BTN_DUP ?>' class='btn_content'>
				    <input type=button name="btn_del" value='<?php echo  TREDIT_FORM_BTN_DEL ?>' class='btn_content' onclick="if (confirm('<?php echo  TREDIT_FORM_BTN_DEL_CONF ?>'))  top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=delete&tr_id=<?php echo abs($tr_id)?>'; ">
				    <input type=button name="btn_new" value='<?php echo  TREDIT_FORM_BTN_NEW ?>' class='btn_content' onclick="top.location.href = '<?php echo $_SERVER['PHP_SELF']?>'; ">
				  <?php } ?>
				  <input type=reset value='<?php echo  TREDIT_FORM_BTN_RESET ?>' class='btn_content'>
				</td>
			</tr>
		</form>
		</table>

		<?php if (abs($tr_id) > 0) { ?>
		
		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="50%" align="center" class="content">
				    <a href="<?php echo  WEB_PATH.'/admin/text-edit.php?type=comments&id='.$dbrs->fields[0]; ?>" target="NetJukeComments" onClick="window.open('','NetJukeComments','width=400,height=575,top=0,left=520,menubar=no,scrollbars=yes,resizable=yes');" title="TREDIT_FORM_CM"><b><?php echo  TREDIT_FORM_CM ?></b></a><br>
				</td>
				<td width="50%" align="center" class="content">
				    <a href="<?php echo  WEB_PATH.'/admin/text-edit.php?type=lyrics&id='.$dbrs->fields[0]; ?>" target="NetJukeLyrics" onClick="window.open('','NetJukeLyrics','width=400,height=575,top=25,left=545,menubar=no,scrollbars=yes,resizable=yes');" title="TREDIT_FORM_LY"><b><?php echo  TREDIT_FORM_LY ?></b></a><br>
				</td>
			</tr>
		</table>
		
		<?php } ?>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="" align="center" class="content"><a href="<?php echo WEB_PATH.'/play.php?do=play&val='.$tr_id?>" title="<?php echo  TREDIT_PLAY_HELP ?>"><b><?php echo  TREDIT_PLAY ?></b></a></td>
				<td width="" align="center" class="content"><a href="<?php echo WEB_PATH.'/tr-info.php?id='.$tr_id?>" title="<?php echo  TREDIT_VIEW_HELP ?>"><b><?php echo  TREDIT_VIEW ?></b></a></td>
				<td width="" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  TREDIT_CLOSEWIN_HELP ?>"><b><?php echo  TREDIT_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>

</BODY>
</HTML>

<?php

##################################################

function Find_Id_TrEdit($table,$value) {

   GLOBAL $dbconn;
   
   $value = raw_to_db($value);

   if (strlen($value) > 0) {

     $select_sql = "SELECT id FROM $table WHERE UPPER(name) = '".strtoupper($value)."'";
     $insert_sql = "INSERT INTO $table (name) VALUES ('$value')";

     $dbrs = $dbconn->Execute($select_sql);

     if ($dbrs->RecordCount() < 1) {
       if ($dbconn->Execute($insert_sql) === false) {
	     $errors .= "- ".TREDIT_ERROR_INSERT." $table ($value): ".$dbconn->ErrorMsg()."\\n";
       } else {
         $dbrs = $dbconn->Execute($select_sql);
       }
     }

     return array($dbrs->fields[0],$errors);

   } else {

     return array(0,$errors);

   }

}

##################################################

?>