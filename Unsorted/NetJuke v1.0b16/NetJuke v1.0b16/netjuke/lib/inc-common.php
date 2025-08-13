<?php 


##################################################
##################################################

// START PROCEDURAL BLOCK

##################################################
##################################################

GLOBAL $dbconn, $NETJUKE_SESSION_VARS, $ICONS;

##################################################

// DEFINE IDEAL PATH TO NETJUKE PREFERENCE FILE

define( 'PATH_TO_PREFS', '/etc/inc-prefs.php' );

##################################################

// DEFINE FS AND WEB PATHS TO NETJUKE ROOT

DefineNetjukePaths();

##################################################

// LOCATE, LOAD AND VERIFY NETJUKE PREFERENCE FILE

LoadPrefsFile();

##################################################

// LOAD LANGUAGE PREFERENCE FILE.
// INCLUDE APPROPRIATE LANGUAGE PACK.

LoadLanguagePack();

##################################################

// INITIALIZE GLOBAL DATABASE CONNECTION

$dbconn = InitDbConn();
  
##################################################

// INITIALIZE GLOBAL RANDOM SEED

srand((double)microtime()*1000000);

##################################################

// INITIALIZE NETJUKE SESSION

netjuke_session('start');

##################################################

// LOGIN IF REQUESTED

if (isset($_REQUEST['netjuke_login'])) {

  netjuke_login($_REQUEST['netjuke_login'],$_REQUEST['netjuke_password']);

}  elseif (isset($_REQUEST['netjuke_logout'])) {

  netjuke_session('delete');

}  elseif (isset($_REQUEST['netjuke_redir'])) {

  header ("Location: ".WEB_PATH."/index.php?null=".time());

}

##################################################

// PERFORM SECURITY CHECK

SecurityCheck();

##################################################

# CHECK FOR ENVIRONMENT

LoadEnvironment();

##################################################

# define site wide time limit based onsafe mode status

if (ini_get("safe_mode") == 0) {
  define("TIME_LIMIT",43200);
  set_time_limit(TIME_LIMIT);
}

##################################################
##################################################

// START OF FUNCTION BLOCK

##################################################
##################################################

function DefineNetjukePaths() {

  // define filesystem path
  
  $netjuke_str = '/netjuke|';

  // use two pre-defined variables in case the platform doesn't have one...
  if (isset($_SERVER['PATH_TRANSLATED'])) {
    $path_info = $_SERVER['PATH_TRANSLATED'];
  } else {
    $path_info = $_SERVER['SCRIPT_FILENAME'];
  }
  
  // translate windows \\ to unix /, and kill potential //
  $path_info = separatorCleanup($path_info);
  
  $temp_vals = explode('/',$path_info);
  $unused = array_pop($temp_vals); // drop filename
  $path_info = implode('/',$temp_vals);
  
  // check if netjuke is installed at root (ya never know!)
  if ($path_info == '/') {
     $path_info = ''; // if so - set path to null
  }
  
  $temp_path_info = $path_info.'|';
      
  $pop_cnt = 0;
  
  if (stristr($temp_path_info, $netjuke_str)) {
  
    // This works when the directory is still called netjuke.
    // This can potentially speed up the app on some systems.
    
    $pos = strpos($temp_path_info, $netjuke_str);
  
    $path_info = substr(  $path_info, 0, $pos + (strlen($netjuke_str) - 1)  );
   
    unset ( $pos );
  
  } else {
  
    // This works when app is installed at root or in dir other than netjuke.
    
    $temp_vals = explode('/',$path_info);
    
    if ($temp_vals[count($temp_vals) - 1] == 'admin') {
    
      $unused = array_pop($temp_vals);
   
      $pop_cnt++;
    
    } elseif (!@file_exists($path_info.PATH_TO_PREFS)) {
      
      while (!@file_exists($path_info.PATH_TO_PREFS)) {
    
        if ($pop_cnt >= 25) break;  // failsafe in case of infinite loop...
    
        $unused = array_pop($temp_vals);
    
        $pop_cnt++;
    
      }
    
    }
    
    $path_info = implode('/',$temp_vals);
  
  }
  
  $path_info = separatorCleanup($path_info);

  define( 'FS_PATH', $path_info );
  
  // define web path
  
  DefineWebPath($pop_cnt);
  
  unset( $temp_val, $path_info, $temp_path_info, $temp_vals, $unused, $cnt );

}

##################################################

function DefineWebPath($pop_cnt = 0) {

  $proto = 'http';
  if ($_SERVER['HTTPS'] == 'on') $proto .= 's';
  $server_info = $proto."://".$_SERVER['HTTP_HOST'];
  
  // use two pre-defined variables in case the platform doesn't have one...
  if (isset($_SERVER['PHP_SELF'])) {
    $path_info = $_SERVER['PHP_SELF'];
  } else {
    $path_info = $_SERVER['SCRIPT_NAME'];
  }
  
  $temp_vals = explode('/',$path_info);
  $unused = array_pop($temp_vals); // drop filename
  
  $cnt = 0;
  
  while ($cnt < $pop_cnt) {
  
    $unused = array_pop($temp_vals);
    
    $cnt++;
  
  }
  
  $path_info = implode('/',$temp_vals);
  
  // check if netjuke is installed at root 
  if ($path_info == '/') {
     $path_info = ''; // if so - set path to null to avoid //
  }
  
  $path_info = separatorCleanup($path_info);

  define( 'WEB_PATH_FROM_ROOT', $path_info );
  define( 'WEB_PATH', $server_info.WEB_PATH_FROM_ROOT );

}

##################################################

function LoadPrefsFile() {

  if (!@file_exists(FS_PATH.PATH_TO_PREFS)) {
    
    echo <<<____EOS
      <div style="font-family: sans-serif;">
        <b style="color: #FF0000;">PREFERENCE FILE ERROR</b>
        <br><br>
        You need to run the <a href="./installer/installer.php">netjuke-installer</a> (or use an existing copy of the generated preference file) to activate this copy.
        <br><br>
        View the provided <a href="./docs/INSTALL.txt" target="_blank">INSTALL.txt</a> file for more info.
        <br><br>
        You can download the netjuke, netjuke-installer, and optional netjuke-toolkit from the following web sites.
        <br><br>
        <li type=square>Preferred Site: <a href="http://netjuke.sourceforge.net/" target="_blank">http://netjuke.sourceforge.net/</a></li>
        <li type=square>Official Site: <a href="http://netjuke.artekopia.org/" target="_blank">http://netjuke.artekopia.org/</a></li>
        <li type=square>Alternate Site: <a href="http://netjuke.tekartists.com/" target="_blank">http://netjuke.tekartists.com/</a></li>
        <br><br>
        Thank you for trying and / or using the Artekopia Netjuke
      </div>
____EOS;

    exit;

  }

  require_once(FS_PATH.PATH_TO_PREFS);
  
  if (headers_sent()) {
  
    echo <<<____EOS
      <div style="font-family: sans-serif;">
        <b style="color: #FF0000;">PREFERENCE FILE ERROR</b>
        <br><br>
        You must cleanup the generated preference file for the Netjuke to function properly.
        <br><br>
        Just open the file in any text editor, and remove ANYTHING before the top &lt;? and after the bottom ?&gt;.
        <br><br>
        Sorry, but <i>some</i> browsers just don't want to play fair when saving the text file...
        <br><br>
        View the provided <a href="./docs/INSTALL.txt" target="_blank">INSTALL.txt</a> file for more info.
        <br><br>
        Thank you for trying and / or using the Artekopia Netjuke
      </div>
____EOS;
  
    exit;
  
  }

}

##################################################

function LoadLanguagePack() {

  require_once(FS_PATH.'/etc/locale/'.LANG_PACK.'/inc-langprefs.php');
  
  require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-common.php");

}

##################################################

function InitDbConn() {

  GLOBAL $dbconn;
  
  require_once(FS_PATH."/lib/adodb/adodb.inc.php");
  
  $dbconn = &ADONewConnection(DB_TYPE);
  
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
  
  # connect using PConnect or Connect and print error if any.
  if ($persistent == 1) {
    if ( !$dbconn->PConnect(DB_HOST,DB_USER,obfuscate_undo(DB_PASS),DB_NAME) ) {
      alert( COMMON_ERR_DBCONN . " (\$dbconn->PConnect)" );
    }
  } else {
    if ( !$dbconn->Connect(DB_HOST,DB_USER,obfuscate_undo(DB_PASS),DB_NAME) ) {
      alert( COMMON_ERR_DBCONN . " (\$dbconn->Connect)" );
    }
  }
  
  return $dbconn;

}

##################################################

function netjuke_session ($do) {

  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;
  
  // test existence and support for cookies
  
  if (    (strlen($_COOKIE['NETJUKE_SESSION_ID']) != 32)
       && ($_REQUEST['098f6bcd'] != 1) ) {
    
    if (!@headers_sent()) {

      $real_query_string = array();

      foreach ($_REQUEST as $key => $val) {
        $real_query_string[] = rawurlencode(trim($key)).'='.rawurlencode(trim($val));
      }

      @setcookie ('NETJUKE_SESSION_ID', md5( microtime() . encode_security_key(SECURITY_KEY) ));

      @header('Location:'.$_SERVER['PHP_SELF'].'?098f6bcd=1&'.implode('&',$real_query_string));

    }

  }
  
  if (strlen($_COOKIE['NETJUKE_SESSION_ID']) == 32) {

    // This works if the user-agent ACCEPTS COOKIES.
    // Most secure and reliable.
    $NETJUKE_SESSION_VARS['session_id'] = $_COOKIE['NETJUKE_SESSION_ID'];

  } else {

    // Generate session id for user-agents REFUSING OR NOT SUPPORTING
    // COOKIES. The challenge is to generate an id that the server can
    // reproduce 99% of the times, but is hard enough to figure out from
    // remote.
    // Less secure or reliable but makes sure that we have a sessions id.
    // This is important with spiders and tools like curl, wget, etc. 
    $NETJUKE_SESSION_VARS['session_id'] = md5( SECURITY_KEY
                                             . $_SERVER['REMOTE_ADDR']
                                             . $_ENV['Path']
                                             . $_SERVER["HTTP_USER_AGENT"]
                                             . $_ENV['PATH'] );

  }
  
  switch ($do) {

    case "start":
      netjuke_session('select');
      if (count($NETJUKE_SESSION_VARS) == 1) {
        netjuke_session('insert');
        netjuke_session('select');
      }
      break;

    case "insert":
      $NETJUKE_SESSION_VARS['remote_addr'] = $_SERVER['REMOTE_ADDR'];
      $NETJUKE_SESSION_VARS["gr_id"] = 4; // assumes anonymous @ session start
      $NETJUKE_SESSION_VARS['created'] = date("Y-m-d H:i:s");
      $NETJUKE_SESSION_VARS['updated'] = date("Y-m-d H:i:s");
      $cols = $vals = array();
      foreach ($NETJUKE_SESSION_VARS as $key => $value) {
        if ( ($key == 'name') || ($key == 'nickname') ) $value = raw_to_db($value);
        array_push($cols, $key);
        array_push($vals, $value);
      }
      $sql = "insert into netjuke_sessions (".join(",",$cols).") values ('".join("','",$vals)."')";
      $dbconn->Execute($sql);
      break;

    case "update":
      $NETJUKE_SESSION_VARS['updated'] = date("Y-m-d H:i:s");
      $update = array();
      foreach ($NETJUKE_SESSION_VARS as $key => $value) {
        if ( ($key == 'name') || ($key == 'nickname') ) $value = raw_to_db($value);
        array_push($update, $key." = '".$value."'");
      }
      $sql = "update netjuke_sessions set ".join(",",$update)." where session_id = '".$NETJUKE_SESSION_VARS['session_id']."'";
      $dbconn->Execute($sql);
      break;

    case "delete":
      $sql = "delete from netjuke_sessions where session_id = '".$NETJUKE_SESSION_VARS['session_id']."'";
      $dbconn->Execute($sql);
      $NETJUKE_SESSION_VARS = "";
      @setcookie ('NETJUKE_SESSION_ID', 0, time() - 30);
      # purges the netjuke_sessions table of all sessions that are
      # older than (now - 24hrs), and that have not been handled by
      # the above netjuke_session('delete').
      $dbconn->Execute( " delete from netjuke_sessions where updated < '".date("Y-m-d H:i:s",(time() - 7200))."' " );
      header ("Location: ".WEB_PATH."/login.php?netjuke_redir=1\n\n");
      break;

    default:
      $sql = "select * from netjuke_sessions where session_id = '".$NETJUKE_SESSION_VARS['session_id']."'";
      $dbrs = $dbconn->Execute($sql);
      if ($dbrs->RecordCount() == 1) {
        // We found an entry for the session_id string
        $fields = $dbrs->GetRowAssoc();
        foreach ($fields as $name => $value) {
          $NETJUKE_SESSION_VARS[strtolower($name)] = $value;
        }
      } elseif (substr($_SERVER['SCRIPT_FILENAME'],-8) == 'play.php') {
        // We didn't find a matching session_id,
        // but we are dealing with the player.
        $sql = "select session_id from netjuke_sessions where remote_addr = '".$_SERVER['REMOTE_ADDR']."'";
        $dbrs = $dbconn->SelectLimit($sql,1);
        if ($dbrs->RecordCount() > 0) {
          // We found a matching remote address. Could be 2 diff users behind
          // the same firewall, but this is handled in play.php. Avoids 
          // having one session_id per track launched when the netjuke is
          // running in "Protect Media" mode.
          $NETJUKE_SESSION_VARS['session_id'] = $dbrs->fields[0];
          $NETJUKE_SESSION_VARS['remote_addr'] = $_SERVER['REMOTE_ADDR'];
        } else {
          $NETJUKE_SESSION_VARS['session_id'] = 0;
        }
      } else {
        // We didn't find a matching session_id.
        // We are NOT dealing with the player.
        $NETJUKE_SESSION_VARS['session_id'] = 0;
      }
      
  }

  //echo $sql;

}

##################################################

function netjuke_login($login_email,$login_password) {

  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;
  
  $dbrs = $dbconn->Execute( " SELECT name, gr_id, nickname "
                           ." FROM netjuke_users "
                           ." WHERE email = '".$login_email."' "
                           ." AND password = '".md5($login_password)."' ");
  
  if ($dbrs->RecordCount() === 1) {
  
    if (    (abs(substr(SECURITY_MODE,2,1)) == 2)
         && (abs($dbrs->fields[1]) != 1) ) {
    
       alert (COMMON_ACCESS_DENIED_1);
    
       exit;

    } else {

      $NETJUKE_SESSION_VARS["email"]     = $login_email;
      $NETJUKE_SESSION_VARS["name"]      = db_to_raw($dbrs->fields[0]);
      $NETJUKE_SESSION_VARS["gr_id"]     = $dbrs->fields[1];
      $NETJUKE_SESSION_VARS["nickname"]  = db_to_raw($dbrs->fields[2]);
      
      $NETJUKE_SESSION_VARS["bgcolor"] = '';
      
      netjuke_session('update');
      
      $dbconn->Execute("update netjuke_users set login_cnt = login_cnt + 1 where email = '".$login_email."'");
  
      if (substr($_SERVER['SCRIPT_NAME'],-9) == 'login.php') {
        header('Location: ./index.php');
      }
    
    }
  
  } else {

    alert (COMMON_ACCESS_DENIED_2);
    
    exit;
    
  }

  $dbrs->Close();

}

##################################################

function SecurityCheck() {

  GLOBAL $NETJUKE_SESSION_VARS;

  switch (abs(substr(SECURITY_MODE,0,1))) {
  
    case 1:
  
      if ($NETJUKE_SESSION_VARS["email"] == "") {
  
        if (    (substr($_SERVER['SCRIPT_NAME'],-9) != 'login.php')
             && (substr($_SERVER['SCRIPT_NAME'],-11) != 'account.php')
             && (substr($_SERVER['SCRIPT_NAME'],-11) != 'inc-css.php')
             && (substr($_SERVER['SCRIPT_NAME'],-15) != 'inc-jscript.php') ) {
          
          if (    (substr($_SERVER['SCRIPT_NAME'],-8) == 'play.php')
               && ($_REQUEST['do'] == 'dispatch') ) {
               
             break;
  
          } else {
          
             header ("Location: ".WEB_PATH."/login.php");
             exit;
          
          }
        
        } elseif (    (abs(substr(SECURITY_MODE,2,1)) > 0)
                   && (substr($_SERVER['SCRIPT_NAME'],-11) == 'account.php')
                   && ($_REQUEST['do'] != 'login') ) {
          
           header ("Location: ".WEB_PATH."/login.php");
           exit;
          
        }
  
      }
  
      break;
  
    default:
    
      if ( (PRIVATE == true) && ($NETJUKE_SESSION_VARS["email"] == "") ) {
          
           header ("Location: ".WEB_PATH."/login.php");
           exit;
      
      }
  
  }

}

##################################################

function LoadEnvironment() {

  GLOBAL $dbconn, $NETJUKE_SESSION_VARS, $ICONS;

  if (    (strlen($NETJUKE_SESSION_VARS["bgcolor"]) != 6) 
       || (strlen(USER_THEMES)  != 1) ) {
  
    $NETJUKE_SESSION_VARS["bgcolor"]       = DEFAULT_BGCOLOR;
    $NETJUKE_SESSION_VARS["text"]          = DEFAULT_TEXT;
    $NETJUKE_SESSION_VARS["link"]          = DEFAULT_LINK;
    $NETJUKE_SESSION_VARS["alink"]         = DEFAULT_ALINK;
    $NETJUKE_SESSION_VARS["vlink"]         = DEFAULT_VLINK;
    $NETJUKE_SESSION_VARS["td_border"]     = DEFAULT_TD_BORDER;
    $NETJUKE_SESSION_VARS["td_header"]     = DEFAULT_TD_HEADER;
    $NETJUKE_SESSION_VARS["td_header_fc"]  = DEFAULT_TD_HEADER_FC;
    $NETJUKE_SESSION_VARS["td_content"]    = DEFAULT_TD_CONTENT;
    $NETJUKE_SESSION_VARS["font_face"]     = DEFAULT_FONT_FACE;
    $NETJUKE_SESSION_VARS["font_size"]     = DEFAULT_FONT_SIZE;
    $NETJUKE_SESSION_VARS["inv_icn"]       = INV_ICN;
  
    if (    ($NETJUKE_SESSION_VARS["email"] != "")
         && (USER_THEMES != 'f') ) {
      
      $from = "netjuke_userprefs";
      $where = "us_email = '".$NETJUKE_SESSION_VARS["email"]. "'";
    
      $dbrs = $dbconn->Execute( " SELECT bgcolor, text, link"
                              . " , alink, vlink, td_border"
                              . " , td_header, td_header_fc, td_content"
                              . " , font_face, font_size, inv_icn"
                              . " FROM ".$from
                              . " WHERE ".$where );
  
      $NETJUKE_SESSION_VARS["bgcolor"] = $dbrs->fields[0];
      $NETJUKE_SESSION_VARS["text"] = $dbrs->fields[1];
      $NETJUKE_SESSION_VARS["link"] = $dbrs->fields[2];
      $NETJUKE_SESSION_VARS["alink"] = $dbrs->fields[3];
      $NETJUKE_SESSION_VARS["vlink"] = $dbrs->fields[4];
      $NETJUKE_SESSION_VARS["td_border"] = $dbrs->fields[5];
      $NETJUKE_SESSION_VARS["td_header"] = $dbrs->fields[6];
      $NETJUKE_SESSION_VARS["td_header_fc"]  = $dbrs->fields[7];
      $NETJUKE_SESSION_VARS["td_content"] = $dbrs->fields[8];
      $NETJUKE_SESSION_VARS["font_face"] = $dbrs->fields[9];
      $NETJUKE_SESSION_VARS["font_size"] = $dbrs->fields[10];
      $NETJUKE_SESSION_VARS["inv_icn"] = $dbrs->fields[11];
  
    }
    
    netjuke_session('update');
  
  }
  
  $ICONS = array( 'play'   => ''
                , 'dload'   => ''
                , 'info'   => ''
                , 'filter' => ''
                , 'image'  => ''
                , 'artist' => ''
                , 'album'  => ''
                , 'genre'  => '' );
  
  foreach ($ICONS as $key => $value) {
  
    if ($NETJUKE_SESSION_VARS["inv_icn"] == 't') {
  
      $ICONS[$key] = WEB_PATH.str_replace("//","/",'/etc/locale/'.strtolower(LANG_PACK).'/icon.' . $key . '.inv.gif');
  
    } else {
  
      $ICONS[$key] = WEB_PATH.str_replace("//","/",'/etc/locale/'.strtolower(LANG_PACK).'/icon.' . $key . '.gif');
  
    }
  
  }

}

##################################################

function alphabet($type = "artists") {

   if ($type == 'artists') {
     $type_help = COMMON_ALPHABET_AR_HELP;
   } else {
     $type == 'albums';
     $type_help = COMMON_ALPHABET_AL_HELP;
   }
   
   $html = "\n";
   
   $html .= "<table border=0 cellpadding=0 cellspacing=0>\n";
     
   $html .= "  <tr>\n";

   $html .= "    <td align=center valign=middle nowrap>\n";

   $html .= "<pre>";
   
   // split the rows on |
   
   $rows = explode('|',ALPHA_ARRAY);
   
   $row_total = count($rows);
   
   $row_cnt = 0;
   
   foreach ($rows as $cells) {
   
     // split the groups of chars on ;
   
     $clusters = explode(';',$cells);
     
     foreach ($clusters as $this_cluster) {
   
       // split individual chars on ,

       $chars = explode(',', $this_cluster);

       $html .= "&nbsp;";

       foreach ($chars as $this_char) {

         $html .= "<a href=\"alphabet.php?do=alpha.${type}&val=${this_char}\" target=\"NetjukeRemote\" onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"${type_help} ${this_char}\">${this_char}</a>";

       }

     }

     if ($row_cnt < $row_total) $html .= "\n";
     
     $row_cnt++;
    
   }

   $html .= "</pre>\n";
   
   $html .= "    </td>\n";
   
   $html .= "  </tr>\n";
   
   $html .= "</table>\n";
   
   $html .= "\n";
   
   return $html;

}

##################################################

function plistSelect ($user_email,$selected) {

  GLOBAL $dbconn;
  
  $dbrs = $dbconn->Execute("SELECT id, title FROM netjuke_plists WHERE us_email = '$user_email' order by upper(title) asc");

  $html = "<SELECT NAME='pl_id' class=input_content>";

  $html .= "<OPTION VALUE=''>".COMMON_PLISTSELECT_1."</OPTION>";

  $rows = $dbrs->RecordCount();

  if ($rows > 0) {

    $html .= "<OPTION VALUE=''>------------</OPTION>";

    while (!$dbrs->EOF) {
      
      if ($dbrs->fields[0] == $selected) {
         $indeed = "SELECTED";
      } else {
         $indeed = "";
      }
      
      $html .= "<OPTION VALUE='".$dbrs->fields[0]."' $indeed>".$dbrs->fields[1]."</OPTION>";
      
      $dbrs->MoveNext();
    
    }
    
  }
  
  $dbrs->Close();

  $html .= "</SELECT>";

  return $html;

}

##################################################

function get_pl_tracks($pl_id = 0) {

  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;
  
  $dbrs = $dbconn->Execute(" SELECT pt.tr_id
                             from netjuke_plists_tracks pt, netjuke_plists pl
                             where pt.pl_id = $pl_id
                             and pt.pl_id = pl.id
                             and (    pl.us_email = '".$NETJUKE_SESSION_VARS['email']."'
                                   or pl.shared_list = 't' )
                             order by pt.sequence ");
  
  $cnt = 0;
  
  $str = '';
  
  while (!$dbrs->EOF) {
  
    if ($cnt != 0) $str .= ",";
    
    $str .= abs($dbrs->fields[0]);
    
    $dbrs->MoveNext();
    
    $cnt++;
  
  }
      
  $dbrs->Close();
  
  return $str;

}

##################################################

function javascript ($code = "alert('Blah');") {
   
  print '
    <HTML>
    <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET .'">
    <SCRIPT LANGUAGE="Javascript">
    <!--
      '.$code.'
    //-->
    </SCRIPT>
    </HTML>
    ';

}

##################################################

function alert ($msg = "Blah") {
   
  echo '
    <HTML>
    <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET .'">
    <SCRIPT LANGUAGE="Javascript">
    <!--
      alert("'.$msg.'");
      self.history.go(-1);
    //-->
    </SCRIPT>
    </HTML>
  ';

  exit;

}

##################################################

function specialUrlEncode ($str = "") {
   
   $str = rawurlencode($str);
   $str = ereg_replace ('%2F','/',$str);
   $str = ereg_replace ('%3A',':',$str);
   $str = ereg_replace ('%40','@',$str);
   $str = ereg_replace ('%7E','~',$str);
   // the following is to eliminate any potential leftover php \
   $str = ereg_replace ('%5C','',$str);
   
   return $str;
   
}

##################################################

function separatorCleanup($path = '') {
   
  # translate windows \\ to unix /, kill potential //
  # and trash trailing /
  
  $proto_sep = '://';
  
  if (strstr($path,$proto_sep)) list($proto, $path) = split($proto_sep,$path);
  
  $path = db_to_raw($path);
  
  if (strstr($path,"\\")) $path = str_replace("\\","/",$path);
  
  $path = str_replace("//","/",$path);
  
  if (isset($proto)) $path = $proto.$proto_sep.$path;
  
  if (substr($path,-1) == '/') $path = rtrim($path,'/');
  
  return $path;

}

##################################################

function raw_to_db($str = "") {
   
   $str = str_replace("'","\\'",$str);
   return str_replace("\\\\","\\",$str);

}

##################################################

function db_to_raw($str = "") {
   
   return str_replace("\\'","'",$str);

}

##################################################

function obfuscate_apply($str = "") {
   
   # nothing much given we're in an open-source environment anyway...
   # Just enough to keep honest people honest. ;o)
   return rawurlencode(base64_encode($str));

}

##################################################

function obfuscate_undo($str = "") {
   
   # nothing much given we're in an open-source environment anyway...
   # Just enough to keep honest people honest. ;o)
   return base64_decode(rawurldecode($str));

}

##################################################

function format_for_display($str = "") {
   
   return htmlspecialchars($str);

}

##################################################

function pairSelect($table='netjuke_artists',$name='ar_id',$selected=0,$onchange="") {

  global $dbconn;
  
  $dbrs = $dbconn->Execute("SELECT id, name FROM $table where track_cnt > 0  or id = 1 order by upper(name) asc");

  $html = "<select name='$name' class=input_content onchange='$onchange'>";

  $rows = $dbrs->RecordCount();

  if ($rows > 0) {

    while (!$dbrs->EOF) {
      
      if ($dbrs->fields[0] == $selected) {
         $indeed = "SELECTED";
      } else {
         $indeed = "";
      }
      
      $html .= "<OPTION VALUE='".$dbrs->fields[0]."' $indeed>";
      $html .= substr(format_for_display($dbrs->fields[1]),0,32);
      if (strlen($dbrs->fields[1]) > 32) $html .= "...";
      $html .= "</OPTION>";
      
      $dbrs->MoveNext();
    
    }
    
  }
  
  $dbrs->Close();

  $html .= "</SELECT>";

  return $html;

}

##################################################

function check_image($file = "") {

  if (    ( strlen($file) > 0 )
       && (    (substr(strtolower($file),-4,4) == ".gif")
            || (substr(strtolower($file),-4,4) == ".jpg") )  )  {

    $fs_path = str_replace("//","/",FS_PATH."/".ARTWORK_DIR."/".$file);
    $web_path = str_replace(":/","://", str_replace("//","/",WEB_PATH."/".ARTWORK_DIR."/".$file) );
  
    if (@file_exists($fs_path)) {
      $file = $web_path;
    } else {
      $file = "";
    }
  
  } else {
  
    $file = "";
  
  }
  
  return $file;

}

##################################################

function image_icon($file = "") {
  
  GLOBAL $ICONS;
  
  $file = check_image($file);

  if (strlen($file) > 0) {
  
    $file = "<a href='".$file."' target=\"NetJukeImage\" onClick=\"window.open('','NetJukeImage','width=550,height=550,top=50,left=50,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".COMMON_CHECK_IMAGE_HELP."\"><img alt='".COMMON_CHECK_IMAGE_HELP."' src='".$ICONS['image']."' border=0 width=10 height=8 align=absmiddle hspace=0 vspace=0></a>";
  
  } else {
  
    $file = "";
  
  }
  
  return $file;

}

##################################################

function get_display_name($email) {

  GLOBAL $dbconn;

  $dbrs = $dbconn->SelectLimit(  " SELECT name, nickname "
                               . " from netjuke_users"
                               . " where email = '$email' "
                               , 1 );

  if ($dbrs->fields[1] != '') {
  
    $display_name = $dbrs->fields[1];
  
  } elseif ($dbrs->fields[0] != '') {
  
    $display_name = $dbrs->fields[0];
  
  } else {
  
    $display_name = str_replace("@","-at-",$email);
  
  }
  
  return $display_name;

}

##################################################

function getTimeSize() {

  GLOBAL $dbconn;

  $dbrs = $dbconn->Execute("select sum(time), sum(size) from netjuke_tracks");
  
  $time = myTimeFormat( $dbrs->fields[0] );
  $size = myFilesizeFormat( $dbrs->fields[1] );
  
  return array($time,$size);

}

##################################################

function getCount( $type = 'tr', $extra_clause = '' ) {

  GLOBAL $dbconn;
  
  $id = (int) $id;
  
  switch($type) {
    case 'ar':
      $table = 'netjuke_artists';
      break;
    case 'al':
      $table = 'netjuke_albums';
      break;
    case 'ge':
      $table = 'netjuke_genres';
      break;
    default:
      $table = 'netjuke_tracks';
      $type = 'tr';
  }
   
  $dbrs = $dbconn->Execute("select count(id) from ".$table." ".$extra_clause);
  $cnt = (int) $dbrs->fields[0];
  $dbrs->Close();
  
  return $cnt;

}

##################################################

function SpecialEditTB() {

  GLOBAL $NETJUKE_SESSION_VARS;
  
  $html = "";
  $colspan = 0;
  
  $batch_html = "";
  $batch_colspan = 0;
  
  $radio_html = "";
  $radio_colspan = 0;
  
  $row_html;
  
  $jukebox_html = "";
  $jukebox_colspan = 0;
  
  if ($NETJUKE_SESSION_VARS['gr_id'] <= 2) {

    // we have an admin or an editor
    
    if (defined('RADIO_TYPE')) {
    
      if ( (RADIO_TYPE != '') && (RADIO_PLIST != '') ) {
        
        if (@file_exists(RADIO_PLIST)) {
        
          // display radio edit tools
          
          $radio_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('radio', 'add', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_RADIOADD_HELP ."\">". COMMON_BEDIT_TB_RADIOADD ."</A></B></td>";
          $radio_colspan++;
        
          $radio_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('radio', 'replace', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_RADIOREP_HELP ."\">". COMMON_BEDIT_TB_RADIOREP ."</A></B></td>";
          $radio_colspan++;
        
          $radio_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"".WEB_PATH."/admin/pl-view.php?do=radio\" target=\"NetJukePlView\" onClick=\"window.open('','NetJukePlView','width=640,height=550,top=5,left=5,menubar=no,scrollbars=yes,resizable=yes');\" title=\"". COMMON_BEDIT_TB_RADIOPLVIEW_HELP ."\">". COMMON_BEDIT_TB_RADIOPLVIEW ."</A></B></td>";
          $radio_colspan++;
        
        }
      
      }
    
    }
    
    if (defined('JUKEBOX_PLAYER')) {

      if ( (JUKEBOX_PLAYER != '') && (JUKEBOX_PLIST != '') ) {
      
        if (@file_exists(JUKEBOX_PLIST)) {
      
          // display jukebox edit tools
        
          $jukebox_html .= "</tr><tr>";
        
          $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"".WEB_PATH."/admin/jukebox-edit.php?do=start\" title=\"". COMMON_BEDIT_TB_JUKEBOXSTART_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXSTART ."</A></B></td>";
          $jukebox_colspan++;
        
          $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"".WEB_PATH."/admin/jukebox-edit.php?do=stop\" title=\"". COMMON_BEDIT_TB_JUKEBOXSTOP_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXSTOP ."</A></B></td>";
          $jukebox_colspan++;
        
          if (JUKEBOX_PLAYER != 'ogg123') {
          
            // show both add & replace playlist options if not ogg123
            
            $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('jukebox', 'add', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_JUKEBOXADD_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXADD ."</A></B></td>";
            $jukebox_colspan++;
            $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('jukebox', 'replace', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_JUKEBOXREP_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXREP ."</A></B></td>";
            $jukebox_colspan++;
          
          } else {
          
            // if ogg123
            
            if ($radio_html == '') {
              // radio is off, colspan = 1
              $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('jukebox', 'replace', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_JUKEBOXREP_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXREP ."</A></B></td>";
              $jukebox_colspan++;
            } else {
              // radio is on, colspan = 2
              $jukebox_html .= "<td colspan=2 class=\"content\" align=center nowrap><B><A HREF=\"Javascript:TextPlistEdit('jukebox', 'replace', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_JUKEBOXREP_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXREP ."</A></B></td>";
              $jukebox_colspan++;
            }
          
          }
        
          $jukebox_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"".WEB_PATH."/admin/pl-view.php?do=jukebox\" target=\"NetJukePlView\" onClick=\"window.open('','NetJukePlView','width=640,height=550,top=5,left=5,menubar=no,scrollbars=yes,resizable=yes');\" title=\"". COMMON_BEDIT_TB_JUKEBOXPLVIEW_HELP ."\">". COMMON_BEDIT_TB_JUKEBOXPLVIEW ."</A></B></td>";
          $jukebox_colspan++;
        
        }
      
      }
    
    }
    
    if ( ($radio_html == '') && ($jukebox_html != '') ) { 
    
      // if we have radio off and jukebox on
      
      if (JUKEBOX_PLAYER != 'ogg123') {
        // replace feature is on so colspan = 3
        $batch_html .= "<td colspan=\"3\" class=\"content\" align=center nowrap><B><A HREF=\"Javascript:BatchEdit('edit', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_BATCHEDIT_HELP ."\">". COMMON_BEDIT_TB_BATCHEDIT ."</A></B></td>";
        $batch_colspan++;
      } else {
        // replace feature is off so colspan = 2
        $batch_html .= "<td colspan=\"2\" class=\"content\" align=center nowrap><B><A HREF=\"Javascript:BatchEdit('edit', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_BATCHEDIT_HELP ."\">". COMMON_BEDIT_TB_BATCHEDIT ."</A></B></td>";
        $batch_colspan++;
      }
      
      $batch_html .= "<td colspan=\"2\" class=\"content\" align=center nowrap><B><A HREF=\"Javascript:BatchEdit('del_tr', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_BATCHDEL_HELP ."\">". COMMON_BEDIT_TB_BATCHDEL ."</A></B></td>";
      $batch_colspan++;
    
    } else {
    
      // radio is on, no need for colspan hacking
      
      $batch_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:BatchEdit('edit', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_BATCHEDIT_HELP ."\">". COMMON_BEDIT_TB_BATCHEDIT ."</A></B></td>";
      $batch_colspan++;
      
      $batch_html .= "<td class=\"content\" align=center nowrap><B><A HREF=\"Javascript:BatchEdit('del_tr', document.playForm, 'val[]');\" title=\"". COMMON_BEDIT_TB_BATCHDEL_HELP ."\">". COMMON_BEDIT_TB_BATCHDEL ."</A></B></td>";
      $batch_colspan++;
    
    }
    
    $html = $batch_html.$radio_html.$row_html.$jukebox_html;
    
    if ($jukebox_html == '') {
      // if no jukebox, use batch + radio colspan
      $colspan = $batch_colspan + $radio_colspan;
    } else {
      // jukebox is on
      if (JUKEBOX_PLAYER != 'ogg123') {
        $colspan = $jukebox_colspan;
      } else {
        // cope with lack of replace feature with ogg
        $colspan = $jukebox_colspan + 1;
      }
    }
    
    return "
        <br>
        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
         <tr>
           <td class=\"header\" nowrap colspan=$colspan>
             <B>". COMMON_BEDIT_HEADER ."</B>
           </td>
         </tr>
         <tr>
         
         $html
         
      </tr>
      </table>
    ";
  
  } else {
  
    return "";
  
  }

}

##################################################

function SummaryHeader() {

  $tr_cnt = getCount('tr');
  $ar_cnt = getCount('ar',' where track_cnt > 0 ');
  $al_cnt = getCount('al',' where track_cnt > 0 ');
  $ge_cnt = getCount('ge',' where track_cnt > 0 ');
  list($ti_cnt, $sz_cnt) = getTimeSize();

  echo "

    <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <tr>
       <td width=\"25%\" align='left' valign='top' nowrap>

           <table width='95%' height=\"100%\" border=0 cellspacing=1 cellpadding=3 class=\"border\">
           <tr>
             <td class=\"header\" nowrap><B>". COMMON_SUMHEAD_QS_HEADER ."</B></td>
           </tr>
           <form action='search.php' method=get name='searchForm'>
           <input type=hidden name='do' value='search'>
           <tr>
             <td class=\"content\" align=center nowrap align=center>
               <select name='col' style=\"margin-bottom: 2px;\" class=input_content>
                 <option value='ar.name'>". COMMON_SUMHEAD_QS_OPT_AR ."</option>
                 <option value='tr.name'>". COMMON_SUMHEAD_QS_OPT_TR ."</option>
                 <option value='al.name'>". COMMON_SUMHEAD_QS_OPT_AL ."</option>
                 <option value='ge.name'>". COMMON_SUMHEAD_QS_OPT_GE ."</option>
                 <option value='tr.time'>". COMMON_SUMHEAD_QS_OPT_TI ."</option>
               </select>
               <br>
               <input type=text name='val' size='18' maxlength='50' value='' class=input_content>
               <input type=submit value='". COMMON_SUMHEAD_QS_BTN ."' class='btn_content'> 
             </td>
           </tr>
           </form>
           </table>

       </td>
       <td width=\"25%\" align='right' valign='top' nowrap>

           <table width='95%' height=\"100%\" border=0 cellspacing=1 cellpadding=3 class=\"border\">
           <tr>
             <td class=\"header\" nowrap colspan=3><B>". COMMON_SUMHEAD_SUMM_HEADER ."</B></td>
           </tr>
           <tr>
             <td class=\"content\" align=center nowrap>".$tr_cnt." ". COMMON_SUMHEAD_SUMM_TR ."</td>
             <td class=\"content\" align=center nowrap>".$ar_cnt." ". COMMON_SUMHEAD_SUMM_AR ."</td>
             <td class=\"content\" align=center nowrap>".$ti_cnt."</td>
           </tr>
           <tr>
             <td class=\"content\" align=center nowrap>".$al_cnt." ". COMMON_SUMHEAD_SUMM_AL ."</td>
             <td class=\"content\" align=center nowrap>".$ge_cnt." ". COMMON_SUMHEAD_SUMM_GE ."</td>
             <td class=\"content\" align=center nowrap>".$sz_cnt."</td>
            </td>
           </tr>
           </table>

       </td>
       <td width=\"25%\" align='right' valign='top'>

        <table width='90%' height=\"100%\" border=0 cellspacing=1 cellpadding=3 class=\"border\">
        <form>
        <tr>
          <td class=\"header\" nowrap>
            <table border=0 width=100% cellspacing=0 cellpadding=0><tr><td align=left class=\"header\" nowrap>
              ". COMMON_SUMHEAD_ALPHA_AR_HEADER ." A-Z (". $ar_cnt .")
            </td><td align=right>  
              <input type=button value='". COMMON_SUMHEAD_ALL_LINK ."'  onClick=\"window.open('alphabet.php?do=alpha.artists','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" class='btn_header' title=\"".  COMMON_SUMHEAD_ALL_TITLE ."\">
            </td></tr></table>
          </td>
        </tr>
        <tr>
          <td class=\"content\" align=\"center\">
            ". alphabet('artists') ."
          </td>
        </tr>
        </form>
        </table>
        
       </td>
       <td width=\"25%\" align='right' valign='top'>

        <table width='90%' height=\"100%\" border=0 cellspacing=1 cellpadding=3 class=\"border\">
        <form>
        <tr>
          <td class=\"header\" nowrap>
            <table border=0 width=100% cellspacing=0 cellpadding=0><tr><td align=left class=\"header\" nowrap>
              ". COMMON_SUMHEAD_ALPHA_AL_HEADER ." A-Z (". $al_cnt .")
            </td><td align=right>  
              <input type=button value='". COMMON_SUMHEAD_ALL_LINK ."'  onClick=\"window.open('alphabet.php?do=alpha.albums','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" class='btn_header' title=\"".  COMMON_SUMHEAD_ALL_TITLE ."\">
            </td></tr></table>
          </td>
        </tr>
        <tr>
          <td class=\"content\" align=\"center\">
            ". alphabet('albums') ."
          </td>
        </tr>
        </form>
        </table>
        
       </td>
     </tr>
    </table>
  
    <br>

  ";

}

##################################################

function myTimeFormat($numofsec = 0) {

  if ($numofsec > 0) {
    $min = (int) ($numofsec / 60);
    if ($min > 59) {
      $hr = (int) ($min / 60);
      $min = ( $min - ($hr * 60) );
      $hr_str = $hr . ":";
      $sec = ( $numofsec - ( ($hr * 3600) + ($min * 60) ) );
      if (strlen($min) == 1) $min = '0' . $min;
    } else {
      $hr_str = "";
      $sec = ( $numofsec - ($min * 60) );
    }
    if (strlen($sec) == 1) $sec = '0' . $sec;
    $time = $hr_str . $min . ':' . $sec; 
  } else {
    $time = 'N/A';
  }
  
  return $time;

}

##################################################

function myFilesizeFormat($sizeinbytes = 0) {

  if ($sizeinbytes >= 1073741824) {
    $file_size = round($sizeinbytes / 1073741824 * 100) / 100 . " GB";
  } elseif ($sizeinbytes >= 1048576) {
    $file_size = round($sizeinbytes / 1048576 * 100) / 100 . " MB";
  } elseif ($sizeinbytes >= 1024) {
    $file_size = round($sizeinbytes / 1024 * 100) / 100 . " KB";
  } else {
    $file_size = $sizeinbytes . "B";
  }
  
  return $file_size;

}

##################################################

function cust_str_shuffle($str) {
  
  $cnt = strlen($str);
  
  $pos = 0;
  
  $arr = array();
  
  while ($pos < $cnt) {
  
    $arr[] = substr($str,$pos,1);
    
    $pos++;
  
  }
  
  shuffle($arr);
  
  $newstr = implode('',$arr);
  
  return $newstr;
  
}

##################################################

function encode_security_key($str) {
  
  $str = cust_str_shuffle(obfuscate_apply($str));
  
  // make sure the key is at least 256 chars long
  if (strlen($str) < 256) $str = encode_security_key($str);
  
  // make sure the key is at max 512 chars long
  if (strlen($str) > 512)  {
    $pos = floor( ( (strlen($str) - 512) / 2 ) );
    $str = substr($str,$pos,512);
  }
  
  return $str;
  
}

##################################################

?>