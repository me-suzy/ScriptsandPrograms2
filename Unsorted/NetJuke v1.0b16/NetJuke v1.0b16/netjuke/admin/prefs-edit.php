<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_prefs-edit.php");
  
$pref_file = FS_PATH.'/etc/inc-prefs.php';
  
if ($_REQUEST['do'] == 'save') {

  # define if we can use persistent connections
  # for the selected database type
  switch (strtolower(DB_TYPE)) {
    case "postgres":
      if (ini_get("pgsql.allow_persistent") == 1) $persistent = 1;
      break;
    case "postgres7":
      if (ini_get("pgsql.allow_persistent") == 1) $persistent = 1;
      break;
    case "mysql":
      if (ini_get("mysql.allow_persistent") == 1) $persistent = 1;
      break;
  }

  if (strlen($_REQUEST['db_passField']) > 0) {
    $db_passwd = $_REQUEST['db_passField'];
  } else {
    $db_passwd = obfuscate_undo(DB_PASS);
  }
  
  $safe_dbconn = &ADONewConnection(DB_TYPE);

  # connect using PConnect or Connect and print error if any.
  if ($persistent == 1) {
    if ( !$safe_dbconn->PConnect($_REQUEST['db_hostField'],$_REQUEST['db_userField'],$db_passwd,$_REQUEST['db_nameField']) ) {
      alert( ADMPREF_ERR_DBCONN . " (\$dbconn->PConnect)" );
    }
  } else {
    if ( !$safe_dbconn->Connect($_REQUEST['db_hostField'],$_REQUEST['db_userField'],$db_passwd,$_REQUEST['db_nameField']) ) {
      alert( ADMPREF_ERR_DBCONN . " (\$dbconn->Connect)" );
    }
  }
  
  $safe_dbconn->Close();
  
  $radio_plist = separatorCleanup(separatorCleanup($_REQUEST['radio_plistField']));

  if (    ($_REQUEST['radio_typeField'] != "")
       && ($_REQUEST['radio_plistField'] != "") ) {

    $temp = explode('/', $radio_plist);
    $unused = array_pop($temp);
    $radio_plist_dir = implode('/',$temp);
    
    if ( (!@is_writeable($radio_plist_dir)) || (!@touch($radio_plist)) ) {
      alert( ADMPREF_ERR_RADIOPLIST );
    }
    
    unset($temp, $radio_plist_dir);
  
  }
  
  $jukebox_plist = separatorCleanup($_REQUEST['jukebox_plistField']);

  if (    ($_REQUEST['jukebox_playerField'] != "")
       && ($_REQUEST['jukebox_plistField'] != "") ) {

    $temp = explode('/', $jukebox_plist);
    $unused = array_pop($temp);
    $jukebox_plist_dir = separatorCleanup(implode('/',$temp));
    
    if ( (!@is_writeable($jukebox_plist_dir)) || (!@touch($jukebox_plist)) ) {
      alert( ADMPREF_ERR_JUKEBOXPLIST );
    }
    
    $jukebox_player_pid = $jukebox_plist_dir.'/player.pid';
    
    unset($temp, $jukebox_plist_dir);
  
  }

  if (    ($_REQUEST['jukebox_playerField'] != "")
       && ($_REQUEST['jukebox_player_pathField'] != "") ) {
  
    $jukebox_player_path = separatorCleanup($_REQUEST['jukebox_player_pathField']);
    
    if (!@file_exists($jukebox_player_path)) {
      alert( ADMPREF_ERR_JUKEBOXPLAYERPATH );
    }
  
  }

  if (strlen($_REQUEST['sec_keyField']) >= 30) {
    // generate a new security key based on new user input
    $sec_key = encode_security_key($_REQUEST['sec_keyField']);
  } else {
    // take advantage of the save to update the current security key
    $sec_key = encode_security_key(SECURITY_KEY);
  }
  
  if ($_REQUEST['user_themesField'] == '') $_REQUEST['user_themesField'] = 'f';

  if ($_REQUEST['inv_icnField'] == '') $_REQUEST['inv_icnField'] = 'f';

  if ($_REQUEST['enable_commField'] == '') $_REQUEST['enable_commField'] = 'f';

  if ($_REQUEST['enable_dloadField'] == '') $_REQUEST['enable_dloadField'] = 'f';

  if ($_REQUEST['protect_mediaField'] == '') $_REQUEST['protect_mediaField'] = 'f';

  if ($_REQUEST['real_onlyField'] == '') $_REQUEST['real_onlyField'] = 'f';
  
  $prefs .= "<"."?php\r\n";

  $prefs .= "\r\n";
  $prefs .= "// ".ADMPREF_FILEINFO_1.": ".date("Y-m-d @ H:i:s")." ".ADMPREF_FILEINFO_2." ".$_SERVER['REMOTE_ADDR']."\r\n";
  $prefs .= "// ".ADMPREF_FILEINFO_3.": ".WEB_PATH."/etc/inc-prefs.php\r\n";
  $prefs .= "\r\n";
  $prefs .= "define(  \"SECURITY_MODE\", ".$_REQUEST['sec_modeField']."  );\r\n";
  $prefs .= "define(  \"SECURITY_KEY\", \"".$sec_key."\"  );\r\n";
  $prefs .= "define(  \"DB_TYPE\", \"".$_REQUEST['db_typeField']."\"  );\r\n";
  $prefs .= "define(  \"DB_HOST\", \"".$_REQUEST['db_hostField']."\"  );\r\n";
  $prefs .= "define(  \"DB_USER\", \"".$_REQUEST['db_userField']."\"  );\r\n";
  $prefs .= "define(  \"DB_PASS\", \"".obfuscate_apply($db_passwd)."\"  );\r\n";
  $prefs .= "define(  \"DB_NAME\", \"".$_REQUEST['db_nameField']."\"  );\r\n";
  $prefs .= "define(  \"STREAM_SRVR\", \"".separatorCleanup($_REQUEST['stream_srvrField'])."\"  );\r\n";
  $prefs .= "define(  \"MUSIC_DIR\", \"".separatorCleanup($_REQUEST['music_dirField'])."\"  );\r\n";
  $prefs .= "define(  \"SUPPORTED_FORMATS\", \"mp3,mp2,ogg,wma,ra\"  );\r\n";
  $prefs .= "define(  \"PROTECT_MEDIA\", \"".$_REQUEST['protect_mediaField']."\"  );\r\n";
  $prefs .= "define(  \"REAL_ONLY\", \"".$_REQUEST['real_onlyField']."\"  );\r\n";
  $prefs .= "define(  \"RADIO_TYPE\", \"".$_REQUEST['radio_typeField']."\"  );\r\n";
  $prefs .= "define(  \"RADIO_URL\", \"".separatorCleanup($_REQUEST['radio_urlField'])."\"  );\r\n";
  $prefs .= "define(  \"RADIO_PLIST\", \"".$radio_plist."\"  );\r\n";
  $prefs .= "define(  \"JUKEBOX_PLAYER\", \"".$_REQUEST['jukebox_playerField']."\"  );\r\n";
  $prefs .= "define(  \"JUKEBOX_PLAYER_PATH\", \"".separatorCleanup($_REQUEST['jukebox_player_pathField'])."\"  );\r\n";
  $prefs .= "define(  \"JUKEBOX_PLAYER_PID\", \"".$jukebox_player_pid."\"  );\r\n";
  $prefs .= "define(  \"JUKEBOX_PLIST\", \"".$jukebox_plist."\"  );\r\n";
  $prefs .= "define(  \"DATA_DIR_IMPORT\", \"var/data/import\"  );\r\n";
  $prefs .= "define(  \"DATA_DIR_BACKUP\", \"var/data/backup\"  );\r\n";
  $prefs .= "define(  \"ARTWORK_DIR\", \"var/artwork/\"  );\r\n";
  $prefs .= "define(  \"INTERFACE_HEADER\", \"".FS_PATH."/lib/inc-header.php\"  );\r\n";
  $prefs .= "define(  \"INTERFACE_FOOTER\", \"".FS_PATH."/lib/inc-footer.php\"  );\r\n";
  $prefs .= "define(  \"CUSTOM_HEADER\", \"".separatorCleanup($_REQUEST['custom_headerField'])."\"  );\r\n";
  $prefs .= "define(  \"CUSTOM_FOOTER\", \"".separatorCleanup($_REQUEST['custom_footerField'])."\"  );\r\n";
  $prefs .= "define(  \"RES_PER_PAGE\", ".abs($_REQUEST['res_per_pageField'])."  );\r\n";
  $prefs .= "define(  \"LANG_PACK\", \"".$_REQUEST['lang_packField']."\"  );\r\n";
  $prefs .= "define(  \"USER_THEMES\", \"".$_REQUEST['user_themesField']."\"  );\r\n";
  $prefs .= "define(  \"INV_ICN\", \"".$_REQUEST['inv_icnField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_BGCOLOR\", \"".$_REQUEST['bgcolorField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_TEXT\", \"".$_REQUEST['textField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_LINK\", \"".$_REQUEST['linkField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_ALINK\", \"".$_REQUEST['alinkField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_VLINK\", \"".$_REQUEST['vlinkField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_TD_BORDER\", \"".$_REQUEST['td_borderField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_TD_HEADER\", \"".$_REQUEST['td_headerField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_TD_HEADER_FC\", \"".$_REQUEST['td_header_fcField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_TD_CONTENT\", \"".$_REQUEST['td_contentField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_FONT_FACE\", \"".$_REQUEST['font_faceField']."\"  );\r\n";
  $prefs .= "define(  \"DEFAULT_FONT_SIZE\", \"".$_REQUEST['font_sizeField']."\"  );\r\n";
  $prefs .= "define(  \"ENABLE_COMMUNITY\", \"".$_REQUEST['enable_commField']."\"  );\r\n";
  $prefs .= "define(  \"ENABLE_DOWNLOAD\", \"".$_REQUEST['enable_dloadField']."\"  );\r\n";
  
  $prefs .=  "\r\n?".">";
  
  if ($_REQUEST['save_to_fileField'] == 1) {
    
    if (@is_writable($pref_file)) {
    
      $fp = fopen($pref_file,'w');
    
      fwrite($fp,$prefs);
    
      fclose($fp);
    
      if ($_REQUEST['user_themesField'] == 'f') {
    
        $NETJUKE_SESSION_VARS["inv_icn"] = $_REQUEST['inv_icnField'];

        $NETJUKE_SESSION_VARS["bgcolor"] = $_REQUEST['bgcolorField'];
        $NETJUKE_SESSION_VARS["text"] = $_REQUEST['textField'];
        $NETJUKE_SESSION_VARS["link"] = $_REQUEST['linkField'];
        $NETJUKE_SESSION_VARS["alink"] = $_REQUEST['alinkField'];
        $NETJUKE_SESSION_VARS["vlink"] = $_REQUEST['vlinkField'];
        $NETJUKE_SESSION_VARS["td_border"] = $_REQUEST['td_borderField'];
        $NETJUKE_SESSION_VARS["td_header"] = $_REQUEST['td_headerField'];
        $NETJUKE_SESSION_VARS["td_header_fc"] = $_REQUEST['td_header_fcField'];
        $NETJUKE_SESSION_VARS["td_content"] = $_REQUEST['td_contentField'];
        $NETJUKE_SESSION_VARS["font_face"] = $_REQUEST['font_faceField'];
        $NETJUKE_SESSION_VARS["font_size"] = $_REQUEST['font_sizeField'];
      
        netjuke_session('update');
    
      } elseif ($_REQUEST['user_themesField'] == 't') {
    
        $NETJUKE_SESSION_VARS["bgcolor"] = '';
      
        netjuke_session('update');
      
      }
    
    } else {
    
      alert(ADMPREF_DENIED_1);
      
      exit;
    
    }
  
    header("Location: ".WEB_PATH."/admin/prefs-edit.php");
  
  } else {
   
    header( "Content-Disposition: attachment; filename=inc-prefs.txt" ); 
    header("Content-type: text/plain");
    echo $prefs;
 
  }

} else {
 
  $section = "sysadmin";
  include (INTERFACE_HEADER);
  
  if (@is_writable($pref_file)) $write_check = 'checked';
  
  if (strtolower(substr(USER_THEMES,0,1)) == 't') $user_themesChecked = 'CHECKED';
  
  if (strtolower(substr(INV_ICN,0,1)) == 't') $inv_icnChecked = 'CHECKED';
  
  if (strtolower(substr(ENABLE_COMMUNITY,0,1)) == 't') $enable_commChecked = 'CHECKED';
  
  if (strtolower(substr(ENABLE_DOWNLOAD,0,1)) == 't') $enable_dloadChecked = 'CHECKED';
  
  if (strtolower(substr(REAL_ONLY,0,1)) == 't') $real_onlyChecked = 'CHECKED';
  
  if (strtolower(substr(PROTECT_MEDIA,0,1)) == 't') $protect_mediaChecked = 'CHECKED';
  
  // 1.0b16 upgrade
  if (!defined('JUKEBOX_PLAYER')) define('JUKEBOX_PLAYER','');
  if (!defined('JUKEBOX_PLAYER_PATH')) define('JUKEBOX_PLAYER_PATH','');
  if (!defined('JUKEBOX_PLAYER_PID')) define('JUKEBOX_PLAYER_PATH',FS_PATH.'/var/data/jukebox/player.pid');
  if (!defined('JUKEBOX_PLIST')) define('JUKEBOX_PLIST',FS_PATH.'/var/data/jukebox/playlist.m3u');

?>

<script language='Javascript'>

  function security_note() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_SAVETOFILE_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function security_def() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_SECMODE_HELP_1_1 . ADMPREF_FORMS_SECMODE_HELP_1_2 ?>";
  
     alert(myalert);
  
  }

  function community_features() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_ENABLECOMM_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function download_features() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_ENABLEDLOAD_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function protect_media() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_PROTECTMEDIA_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function real_only() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_REALONLY_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function radio_help() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_RADIO_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function jukebox_help() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_JUKEBOX_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function display_trcounts() {
  
     var myalert = "";
     
     myalert += "<?php echo  ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_1 ?>";
  
     alert(myalert);
  
  }

  function checkForm () {

    var msg = '';

    if (    (document.prefsForm.sec_keyField.value.length > 0)
         && (document.prefsForm.sec_keyField.value.length < 30) ) {
      msg = msg + '- <?php echo  ADMPREF_CHECKFORM_SECKEY ?>.\n';  
    }
    if (document.prefsForm.db_nameField.value.length == 0) {
      msg = msg + '- <?php echo  ADMPREF_CHECKFORM_DBNAME ?>.\n';  
    }
    if (document.prefsForm.stream_srvrField.value.length == 0) {
      msg = msg + '- <?php echo  ADMPREF_CHECKFORM_STREAM ?>.\n';  
    }
    if (document.prefsForm.bgcolorField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_BGCOLOR ?>.\n';  
    }
    if (document.prefsForm.font_faceField.value.length == 0) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_FONTFACE ?>.\n';  
    }
    if (document.prefsForm.font_sizeField.value.length < 1) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_FONTSIZE ?>.\n';  
    }
    if (document.prefsForm.textField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_TEXT ?>.\n';  
    }
    if (document.prefsForm.linkField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_LINK ?>.\n';  
    }
    if (document.prefsForm.alinkField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_ALINK ?>.\n';  
    }
    if (document.prefsForm.vlinkField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_VLINK ?>.\n';  
    }
    if (document.prefsForm.td_borderField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_BORDER ?>.\n';  
    }
    if (document.prefsForm.td_headerField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_HEADER ?>.\n';  
    }
    if (document.prefsForm.td_header_fcField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_HEADERFC ?>.\n';  
    }
    if (document.prefsForm.td_contentField.value.length < 6) {
    msg = msg + '- <?php echo  ADMPREF_CHECKFORM_CONTENT ?>.\n';  
    }

    if (msg == '') {

      return (true);

    } else {

      alert(msg);

      return (false);

    }

  }
</script>
<div align=center>
<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='prefsForm' target="_self" onSubmit='return checkForm ();'>
<input type=hidden name='do' value='save'>
<tr>
  <td class='header' nowrap><B><?php echo  ADMPREF_HEADER_1 ?></B></td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td width="30%" align=right nowrap><?php echo  ADMPREF_FORMS_SAVETOFILE ?></td>
      <td width="70%" align=left nowrap>
        <input type="checkbox" name="save_to_fileField" value="1" <?php echo $write_check?>> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
        &nbsp; (<a href="javascript:security_note();"><?php echo  ADMPREF_FORMS_SAVETOFILE_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td width="30%" align=right nowrap><?php echo  ADMPREF_FORMS_SECMODE ?></td>
      <td width="70%" align=left nowrap>
        <?php echo sec_mode_menu("sec_modeField",SECURITY_MODE,"")?>
        &nbsp; (<a href="javascript:security_def();"><?php echo  ADMPREF_FORMS_SECMODE_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_SECKEY ?></td>
      <td align=left nowrap>
        <input type=text name='sec_keyField' size='50' maxlength='500' value='' class=input_content>
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_LANGPACK ?></td>
      <td align=left nowrap><?php echo  lang_pack_menu("lang_packField", LANG_PACK) ?></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_DBTYPE ?></td>
      <td align=left nowrap><?php echo db_type_menu("db_typeField",DB_TYPE)?></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_DBHOST ?></td>
      <td align=left nowrap><input type=text name='db_hostField' size='50' maxlength='256' value='<?php echo DB_HOST?>' class=input_content></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_DBUSER ?></td>
      <td align=left nowrap><input type=text name='db_userField' size='50' maxlength='256' value='<?php echo DB_USER?>' class=input_content></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_DBPASS ?></td>
      <td align=left nowrap><input type=password name='db_passField' size='50' maxlength='256' value='' class=input_content></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_DBNAME ?></td>
      <td align=left nowrap><input type=text name='db_nameField' size='50' maxlength='256' value='<?php echo DB_NAME?>' class=input_content></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_STREAM ?></td>
      <td align=left nowrap><input type=text name='stream_srvrField' size='50' maxlength='500' value='<?php echo STREAM_SRVR?>' class=input_content title="eg: http://your.host.dom/path/to/your/music"></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_MUSICDIR ?></td>
      <td align=left nowrap><input type=text name='music_dirField' size='50' maxlength='500' value='<?php echo MUSIC_DIR?>' class=input_content title="eg: /path/to/your/music"></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>
        <input type=submit value='<?php echo  ADMPREF_FORMS_BTN_SAVE ?>' class='btn_content'>
        <input type=reset value='<?php echo  ADMPREF_FORMS_BTN_RESET ?>' class='btn_content'>
      </td>
    </tr>
  </table>
</tr>
</table>

<br>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  ADMPREF_HEADER_2 ?></B></td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_RESPERPAGE_1 ?></td>
      <td align=left nowrap><input type=text name='res_per_pageField' size='3' maxlength='3' value='<?php echo RES_PER_PAGE?>' class=input_content> <?php echo  ADMPREF_FORMS_RESPERPAGE_2 ?></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_ENABLECOMM ?></td>
      <td align=left nowrap>
        <input type=checkbox name='enable_commField' value='t' <?php echo $enable_commChecked?>> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
        &nbsp; (<a href="javascript:community_features();"><?php echo  ADMPREF_FORMS_ENABLECOMM_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_ENABLEDLOAD ?></td>
      <td align=left nowrap>
        <input type=checkbox name='enable_dloadField' value='t' <?php echo $enable_dloadChecked?>> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
        &nbsp; (<a href="javascript:download_features();"><?php echo  ADMPREF_FORMS_ENABLEDLOAD_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_PROTECTMEDIA ?></td>
      <td align=left nowrap>
        <input type=checkbox name='protect_mediaField' value='t' <?php echo $protect_mediaChecked?> onChange="if (document.prefsForm.real_onlyField.checked == true) document.prefsForm.protect_mediaField.checked = true;"> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
        &nbsp; (<a href="javascript:protect_media();"><?php echo  ADMPREF_FORMS_PROTECTMEDIA_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_REALONLY ?></td>
      <td align=left nowrap>
        <input type=checkbox name='real_onlyField' value='t' <?php echo $real_onlyChecked?> onChange="if (document.prefsForm.real_onlyField.checked == true) document.prefsForm.protect_mediaField.checked = true;"> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
        &nbsp; (<a href="javascript:real_only();"><?php echo  ADMPREF_FORMS_REALONLY_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>
        <input type=submit value='<?php echo  ADMPREF_FORMS_BTN_SAVE ?>' class='btn_content'>
        <input type=reset value='<?php echo  ADMPREF_FORMS_BTN_RESET ?>' class='btn_content'>
      </td>
    </tr>
  </table>
</tr>
</table>

<br>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  ADMPREF_HEADER_5 ?></B></td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_JUKEBOXPLAYER ?></td>
      <td align=left nowrap>
        <?php echo jukebox_player_menu("jukebox_playerField",JUKEBOX_PLAYER)?>
        &nbsp; (<a href="javascript:jukebox_help();"><?php echo  ADMPREF_FORMS_JUKEBOX_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_JUKEBOXPLAYERPATH ?></td>
      <td align=left nowrap><input type=text name='jukebox_player_pathField' size='50' maxlength='256' value='<?php echo JUKEBOX_PLAYER_PATH?>' class=input_content></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_JUKEBOXPLIST ?></td>
      <td align=left nowrap><input type=text name='jukebox_plistField' size='50' maxlength='256' value='<?php echo JUKEBOX_PLIST?>' class=input_content></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>
        <input type=submit value='<?php echo  ADMPREF_FORMS_BTN_SAVE ?>' class='btn_content'>
        <input type=reset value='<?php echo  ADMPREF_FORMS_BTN_RESET ?>' class='btn_content'>
      </td>
    </tr>
  </table>
</tr>
</table>

<br>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  ADMPREF_HEADER_3 ?></B></td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_RADIOTYPE ?></td>
      <td align=left nowrap>
        <?php echo radio_type_menu("radio_typeField",RADIO_TYPE)?>
        &nbsp; (<a href="javascript:radio_help();"><?php echo  ADMPREF_FORMS_RADIO_HELP_2 ?></a>)
      </td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_RADIOPLIST ?></td>
      <td align=left nowrap><input type=text name='radio_plistField' size='50' maxlength='256' value='<?php echo RADIO_PLIST?>' class=input_content></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_RADIOURL ?></td>
      <td align=left nowrap><input type=text name='radio_urlField' size='50' maxlength='256' value='<?php echo RADIO_URL?>' class=input_content></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>
        <input type=submit value='<?php echo  ADMPREF_FORMS_BTN_SAVE ?>' class='btn_content'>
        <input type=reset value='<?php echo  ADMPREF_FORMS_BTN_RESET ?>' class='btn_content'>
      </td>
    </tr>
  </table>
</tr>
</table>

<br>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  ADMPREF_HEADER_4 ?></B></td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_HTMLHEAD ?></td>
      <td align=left nowrap><input type=text name='custom_headerField' size='50' maxlength='256' value='<?php echo CUSTOM_HEADER?>' class=input_content title="/path/file.html  -or-  http://your.host.dom/path/file.html"></td>
    </tr>
    <tr>
      <td align=right nowrap><?php echo  ADMPREF_FORMS_HTMLFOOT ?></td>
      <td align=left nowrap><input type=text name='custom_footerField' size='50' maxlength='256' value='<?php echo CUSTOM_FOOTER?>' class=input_content title="/path/file.html  -or-  http://your.host.dom/path/file.html"></td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>

        <table width='400' border=0 cellspacing=1 cellpadding=3>
        <tr>
          <td align=left wrap="virtual" colspan=3>
            <?php echo  ADMPREF_CAPTION ?>
            <BR>
            <BR>
            <div align=center><A HREF="<?php echo  WEB_PATH ?>/palette.php" target="NetJukePalette" onClick="window.open('','NetJukePalette','width=640,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');"><B><?php echo  ADMPREF_PALETTE ?></B></A></div>
            <BR>
          </td>
        </tr>
        <tr>
          <td width="40%" align=right valign=top nowrap><?php echo  ADMPREF_FORMS_THEMES ?></td>
          <td width="60%" align=left valign=top colspan=2>
          	<input type=checkbox name='user_themesField' value='t' <?php echo $user_themesChecked?> title="<?php echo  ADMPREF_FORMS_THEMES_HELP ?>"> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
          </td>
        </tr>
        <tr>
          <td width="40%" align=right valign=top nowrap><?php echo  ADMPREF_FORMS_INVICN ?></td>
          <td width="60%" align=left valign=top colspan=2>
          	<input type=checkbox name='inv_icnField' value='t' <?php echo $inv_icnChecked?> title="<?php echo  ADMPREF_FORMS_INVICN_HELP ?>"> <?php echo  ADMPREF_FORMS_CAPT_ENABLED ?>
          </td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_FONTFACE ?></td>
          <td align=left nowrap colspan=2><input type=text name='font_faceField' size='25' maxlength='80' value='<?php echo DEFAULT_FONT_FACE?>' class=input_content></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_FONTSIZE ?></td>
          <td align=left nowrap colspan=2><input type=text name='font_sizeField' size='2' maxlength='2' value='<?php echo DEFAULT_FONT_SIZE?>' class=input_content></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_BGCOLOR ?></td>
          <td align=left nowrap><input type=text name='bgcolorField' size='6' maxlength='6' value='<?php echo DEFAULT_BGCOLOR?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_BGCOLOR?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_TEXT ?></td>
          <td align=left nowrap><input type=text name='textField' size='6' maxlength='6' value='<?php echo DEFAULT_TEXT?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_TEXT?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_LINK ?></td>
          <td align=left nowrap><input type=text name='linkField' size='6' maxlength='6' value='<?php echo DEFAULT_LINK?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_LINK?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_ALINK ?></td>
          <td align=left nowrap><input type=text name='alinkField' size='6' maxlength='6' value='<?php echo DEFAULT_ALINK?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_ALINK?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_VLINK ?></td>
          <td align=left nowrap><input type=text name='vlinkField' size='6' maxlength='6' value='<?php echo DEFAULT_VLINK?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_VLINK?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_BORDER ?></td>
          <td align=left nowrap><input type=text name='td_borderField' size='6' maxlength='6' value='<?php echo DEFAULT_TD_BORDER?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_TD_BORDER?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_HEADER ?></td>
          <td align=left nowrap><input type=text name='td_headerField' size='6' maxlength='6' value='<?php echo DEFAULT_TD_HEADER?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_TD_HEADER?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_HEADERFC ?></td>
          <td align=left nowrap><input type=text name='td_header_fcField' size='6' maxlength='6' value='<?php echo DEFAULT_TD_HEADER_FC?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_TD_HEADER_FC?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        <tr>
          <td align=right nowrap><?php echo  ADMPREF_FORMS_CONTENT ?></td>
          <td align=left nowrap><input type=text name='td_contentField' size='6' maxlength='6' value='<?php echo DEFAULT_TD_CONTENT?>' class=input_content></td>
          <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo DEFAULT_TD_CONTENT?>'>&nbsp;</TD></TR></TABLE></td>
        </tr>
        </table>

      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center nowrap colspan=2>
        <input type=submit value='<?php echo  ADMPREF_FORMS_BTN_SAVE ?>' class='btn_content'>
        <input type=reset value='<?php echo  ADMPREF_FORMS_BTN_RESET ?>' class='btn_content'>
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    </table>
  </td>
</tr>
</form>
</table>
</div>

<?php

  include (INTERFACE_FOOTER);

}

exit;


function sec_mode_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content>";
  
  $values = array("0.0","0.1","0.2","1.0","1.1","1.2");
  
  foreach ($values as $value) {
  
    if ($value == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

function db_type_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content>";
  
  $values = array( "mysql"=>"MySQL", "postgres"=>"Postgres 6.x", "postgres7"=>"Postgres 7.x" );
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

function lang_pack_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content>";
  
  $values = array( "en"=>ADMPREF_FORMS_LANGPACK_CAPTION_1
                 , "fr"=>ADMPREF_FORMS_LANGPACK_CAPTION_2
                 , "de"=>ADMPREF_FORMS_LANGPACK_CAPTION_3
                 , "ca"=>ADMPREF_FORMS_LANGPACK_CAPTION_4
                 , "es"=>ADMPREF_FORMS_LANGPACK_CAPTION_5 );
  
  asort($values);
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value </option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

function radio_type_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content>";
  
  $values = array( ""=>ADMPREF_FORMS_RADIOTYPE_CAPTION_1, "QTSS4"=>ADMPREF_FORMS_RADIOTYPE_CAPTION_2, "text"=>ADMPREF_FORMS_RADIOTYPE_CAPTION_3 );
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

function jukebox_player_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content>";
  
  $players = array('mpg123','mpg321','ogg123','winamp');
  
  $values = array( ""=>ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION
                 , 'mpg123'=>'mpg123 (osx, linux, etc.)'
                 , 'mpg321'=>'mpg321 (linux, etc.)'
                 , 'ogg123'=>'ogg123 (linux, etc.)'
                 , 'winamp'=>'winamp (windows)' );
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

?>
