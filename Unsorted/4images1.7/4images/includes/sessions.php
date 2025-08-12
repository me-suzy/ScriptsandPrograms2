<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: sessions.php                                         *
 *        Copyright: (C) 2002 Jan Sorgalla                                *
 *            Email: jan@4homepages.de                                    *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.7                                                  *
 *                                                                        *
 *    Never released without support from: Nicky (http://www.nicky.net)   *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/
if (!defined('ROOT_PATH')) {
  die("Security violation");
}

// Start Configuration
define('SESSION_NAME', 'sessionid');

$user_table_fields = array(
  "user_id" => "user_id",
  "user_level" => "user_level",
  "user_name" => "user_name",
  "user_password" => "user_password",
  "user_email" => "user_email",
  "user_showemail" => "user_showemail",
  "user_allowemails" => "user_allowemails",
  "user_invisible" => "user_invisible",
  "user_joindate" => "user_joindate",
  "user_activationkey" => "user_activationkey",
  "user_lastaction" => "user_lastaction",
  "user_location" => "user_location",
  "user_lastvisit" => "user_lastvisit",
  "user_comments" => "user_comments",
  "user_homepage" => "user_homepage",
  "user_icq" => "user_icq"
);
// End Configuration

function get_user_table_field($add, $user_field) {
  global $user_table_fields;
  return (!empty($user_table_fields[$user_field])) ? $add.$user_table_fields[$user_field] : "";
}

class Session {

  var $session_id;
  var $user_ip;
  var $user_location;
  var $current_time;
  var $session_timeout;
  var $mode = "get";
  var $session_info = array();
  var $user_info = array();

  function Session() {
    global $config;
    $this->session_timeout = $config['session_timeout'] * 60;
    $this->user_ip = $this->get_user_ip();
    $this->user_location = $this->get_user_location();
    $this->current_time = time();
    $this->demand_session();
  }

  function set_cookie_data($name, $value, $permanent = 1) {
    $cookie_expire = ($permanent) ? $this->current_time + 60 * 60 * 24 * 365 : 0;
    $cookie_name = COOKIE_NAME.$name;
    setcookie($cookie_name, $value, $cookie_expire, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE);
  }

  function read_cookie_data($name) {
    global $HTTP_COOKIE_VARS;
    $cookie_name = COOKIE_NAME.$name;
    return (isset($HTTP_COOKIE_VARS[$cookie_name])) ? $HTTP_COOKIE_VARS[$cookie_name] : false;
  }

  function get_session_id() {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    if ($this->session_id = $this->read_cookie_data("sid")) {
      $this->mode = "cookie";
    }
    else {
      if (isset($HTTP_GET_VARS[SESSION_NAME])) {
        $this->session_id = $HTTP_GET_VARS[SESSION_NAME];
      }
      elseif (isset($HTTP_POST_VARS[SESSION_NAME])) {
        $this->session_id = $HTTP_POST_VARS[SESSION_NAME];
      }
      else {
        $this->session_id = false;
      }
    }
  }

  function demand_session() {
    $this->get_session_id();
    if (!$this->load_session_info()) {
      $this->delete_old_sessions();
      $user_id = ($this->read_cookie_data("userid")) ? $this->read_cookie_data("userid") : GUEST;
      $this->start_session($user_id);
    }
    else {
      $this->user_info = $this->load_user_info($this->session_info['session_user_id']);
      $update_cutoff = ($this->user_info['user_id'] != GUEST) ? $this->current_time - $this->user_info['user_lastaction'] : $this->current_time - $this->session_info['session_lastaction'];
      if ($update_cutoff > 60) {
        $this->update_session();
        $this->delete_old_sessions();
      }
    }
  }

  function start_session($user_id = GUEST, $login_process = 0) {
    global $site_db;

    $this->user_info = $this->load_user_info($user_id);
    if ($this->user_info['user_id'] != GUEST && !$login_process) {
      if ($this->read_cookie_data("userpass") == $this->user_info['user_password'] && $this->user_info['user_level'] > USER_AWAITING) {
        $this->set_cookie_data("userpass", $this->user_info['user_password']);
      }
      else {
        $this->set_cookie_data("userpass", "", 0);
        $this->user_info = $this->load_user_info(GUEST);
      }
    }
    $this->session_id = $this->generate_session_id();
    $sql = "INSERT INTO ".SESSIONS_TABLE." 
            (session_id, session_user_id, session_lastaction, session_location, session_ip) 
            VALUES 
            ('$this->session_id', ".$this->user_info['user_id'].", $this->current_time, '$this->user_location', '$this->user_ip')";
    $site_db->query($sql);
    $this->session_info['session_user_id'] = $this->user_info['user_id'];
    $this->session_info['session_lastaction'] = $this->current_time;
    $this->session_info['session_location'] = $this->user_location;
    $this->session_info['session_ip'] = $this->user_ip;

    if ($this->user_info['user_id'] != GUEST) {
      $this->user_info['user_lastvisit'] = (!empty($this->user_info['user_lastaction'])) ? $this->user_info['user_lastaction'] : $this->current_time;
      $sql = "UPDATE ".USERS_TABLE." 
              SET ".get_user_table_field("", "user_lastaction")." = $this->current_time, ".get_user_table_field("", "user_location")." = '$this->user_location', ".get_user_table_field("", "user_lastvisit")." = ".$this->user_info['user_lastvisit']." 
              WHERE ".get_user_table_field("", "user_id")." = ".$this->user_info['user_id'];
      $site_db->query($sql);
    }
    $this->set_cookie_data("sid", $this->session_id, 0);
    $this->set_cookie_data("lastvisit", $this->user_info['user_lastvisit']);
    $this->set_cookie_data("userid", $this->user_info['user_id']);
    return true;
  }

  function login($user_name = "", $user_password = "", $auto_login = 0, $set_auto_login = 1) {
    global $site_db, $user_table_fields;

    if (empty($user_name) || empty($user_password)) {
      return false;
    }
    $sql = "SELECT ".get_user_table_field("", "user_id").get_user_table_field(", ", "user_level").get_user_table_field(", ", "user_name").get_user_table_field(", ", "user_password").get_user_table_field(", ", "user_lastaction")." 
            FROM ".USERS_TABLE." 
            WHERE ".get_user_table_field("", "user_name")." = '$user_name' AND ".get_user_table_field("", "user_level")." <> ".USER_AWAITING;
    $row = $site_db->query_firstrow($sql);
    
    $user_id = (isset($row[$user_table_fields['user_id']])) ? $row[$user_table_fields['user_id']] : GUEST;
    $user_password = md5($user_password);
    if ($user_id != GUEST) {
      if ($row[$user_table_fields['user_password']] == $user_password) { 
        $ip_sql = ($this->mode == "get") ? " AND session_ip = '$this->user_ip'" : "";
        $sql = "DELETE FROM ".SESSIONS_TABLE." 
                WHERE session_id = '$this->session_id'";
        $site_db->query($sql);
        if ($set_auto_login) {
          $this->set_cookie_data("userpass", ($auto_login) ? $user_password : "");
        }
        $this->start_session($user_id, 1);
        return true;
      }
    }
    return false;
  }

  function logout($user_id) {
    global $site_db;
    $sql = "DELETE FROM ".SESSIONS_TABLE." 
            WHERE session_id = '$this->session_id' OR session_user_id = $user_id";
    $site_db->query($sql);
    $this->set_cookie_data("userpass", "", 0);
    $this->set_cookie_data("userid", GUEST);
    return true;
  }

  function delete_old_sessions() {
    global $site_db;
    $expiry_time = $this->current_time - $this->session_timeout;
    $sql = "DELETE FROM ".SESSIONS_TABLE." 
            WHERE session_lastaction < $expiry_time";
    $site_db->query($sql);

    $sql = "SELECT session_id 
            FROM ".SESSIONS_TABLE;
    $result = $site_db->query($sql);
    if ($result) {
      $session_ids_sql = "";
      while ($row = $site_db->fetch_array($result)) {
        $session_ids_sql .= (($session_ids_sql != "") ? ", " : "") . "'".$row['session_id']."'";
      }
    }
    if (!empty($session_ids_sql)) {
      $sql = "DELETE FROM ".SESSIONVARS_TABLE." 
              WHERE session_id NOT IN ($session_ids_sql)";
      $site_db->query($sql);
    }
    return true;
  }

  function update_session() {
    global $site_db;
    $ip_sql = ($this->mode == "get") ? " AND session_ip = '$this->user_ip'" : "";
    $sql = "UPDATE ".SESSIONS_TABLE." 
            SET session_lastaction = $this->current_time, session_location = '$this->user_location' 
            WHERE session_id = '$this->session_id' 
            $ip_sql";
    $site_db->query($sql);
    if ($this->user_info['user_id'] != GUEST) {
      $sql = "UPDATE ".USERS_TABLE." 
              SET ".get_user_table_field("", "user_lastaction")." = $this->current_time, ".get_user_table_field("", "user_location")." = '$this->user_location' 
              WHERE ".get_user_table_field("", "user_id")." = ".$this->user_info['user_id'];
      $site_db->query($sql);
    }
    return;
  }

  function generate_session_id() {
    global $site_db;
    $sid = md5(uniqid(microtime()));
    $i = 0;
    while ($i == 0) {
      $sql = "SELECT session_id 
              FROM ".SESSIONS_TABLE." 
              WHERE session_id = '$sid'";
      if ($site_db->is_empty($sql)) {
        $i = 1;
      }
      else {
        $i = 0;
        $sid = md5(uniqid(microtime()));
      }
    }
    return $sid;
  }

  function return_session_info() {
    return $this->session_info;
  }

  function return_user_info() {
    return $this->user_info;
  }
  
  function freeze() {
    return;
  }

  function load_session_info() {
    global $site_db;
    if (!$this->session_id) {
      return false;
    }
    $ip_sql = ($this->mode == "get") ? " AND session_ip = '$this->user_ip'" : "";
    $this->session_info = array();
    $sql = "SELECT session_id, session_lastaction, session_location, session_ip, session_user_id 
            FROM ".SESSIONS_TABLE." 
            WHERE session_id = '$this->session_id'
            $ip_sql";
    $this->session_info = $site_db->query_firstrow($sql);
    if (!isset($this->session_info['session_user_id'])) {
      return false;
    }
    else {
      $sql = "SELECT sessionvars_name, sessionvars_value 
              FROM ".SESSIONVARS_TABLE." 
              WHERE session_id = '$this->session_id'";
      $result = $site_db->query($sql);
      while ($row = $site_db->fetch_array($result)) {
        $this->session_info[$row['sessionvars_name']] = $row['sessionvars_value'];
      }
      return $this->session_info;
    }
  }

  function load_user_info($user_id = GUEST) {
    global $site_db, $user_table_fields;

    if ($user_id != GUEST) {
      $sql = "SELECT u.*, l.* 
              FROM ".USERS_TABLE." u, ".LIGHTBOXES_TABLE." l 
              WHERE ".get_user_table_field("u.", "user_id")." = $user_id AND l.user_id = ".get_user_table_field("u.", "user_id");
      $user_info = $site_db->query_firstrow($sql);
      if (!$user_info) {
        $sql = "SELECT * 
                FROM ".USERS_TABLE." 
                WHERE ".get_user_table_field("", "user_id")." = $user_id";
        $user_info = $site_db->query_firstrow($sql);
        if ($user_info) {
          $lightbox_id = get_random_key(LIGHTBOXES_TABLE, "lightbox_id");
          $sql = "INSERT INTO ".LIGHTBOXES_TABLE." 
                  (lightbox_id, user_id, lightbox_lastaction, lightbox_image_ids) 
                  VALUES 
                  ('$lightbox_id', ".$user_info[$user_table_fields['user_id']].", $this->current_time, '')";
          $site_db->query($sql);
          $user_info['lightbox_lastaction'] = $this->current_time;
          $user_info['lightbox_image_ids'] = "";
        }
      }
    }
    if (empty($user_info[$user_table_fields['user_id']])) {
      $user_info = array();
      $user_info['user_id'] = GUEST;
      $user_info['user_level'] = GUEST;
      $user_info['user_lastaction'] = $this->current_time;
      $user_info['user_lastvisit'] = ($this->read_cookie_data("lastvisit")) ? $this->read_cookie_data("lastvisit") : $this->current_time;
    }
    foreach ($user_table_fields as $key => $val) {
      if (isset($user_info[$val])) {
        $user_info[$key] = $user_info[$val];
      }
      elseif (!isset($user_info[$key])) {
        $user_info[$key] = "";
      }
    }
    return $user_info;
  }

  function set_session_var($var_name, $value) {
    global $site_db;
    $sql = "SELECT session_id 
            FROM ".SESSIONVARS_TABLE." 
            WHERE sessionvars_name = '$var_name' AND session_id = '$this->session_id'";
    if ($site_db->is_empty($sql)) {
      $sql = "INSERT INTO ".SESSIONVARS_TABLE." 
              (session_id, sessionvars_name, sessionvars_value) 
              VALUES 
              ('$this->session_id', '$var_name', '$value')";
      $site_db->query($sql);
    }
    else {
      $sql = "UPDATE ".SESSIONVARS_TABLE." 
              SET sessionvars_value = '$value' 
              WHERE sessionvars_name = '$var_name' AND session_id = '$this->session_id'";
      $site_db->query($sql);
    }
    $this->session_info[$var_name] = $value;
    return true;
  }

  function get_session_var($var_name) {
    global $site_db;
    if (isset($this->session_info[$var_name])) {
      return $this->session_info[$var_name];
    }
    else {
      $sql = "SELECT sessionvars_value 
              FROM ".SESSIONVARS_TABLE." 
              WHERE sessionvars_name = '$var_name' AND session_id = '$this->session_id'";
      $value = $site_db->query_firstrow($sql);
      if ($value) {
        $this->session_info[$var_name] = $value['sessionvars_value'];
        return $value['sessionvars_value'];
      }
      else {
        return "";
      }
    }
  }

  function drop_session_var($var_name) {
    global $site_db;
    $sql = "DELETE FROM ".SESSIONVARS_TABLE." 
            WHERE sessionvars_name = '$var_name' AND session_id = '$this->session_id'";
    return ($site_db->query($sql)) ? 1 : 0;
  }

  function get_user_ip() {
    global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;
    $ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR'])) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ((!empty($HTTP_ENV_VARS['REMOTE_ADDR'])) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
    $ip = preg_replace("/[^\.0-9]+/", "", $ip);
    return substr($ip, 0, 50);
  }

  function get_user_location() {
    global $self_url;
    return (defined("IN_CP")) ? "Control Panel" : preg_replace(array("/([?|&])action=[^?|&]*/", "/([?|&])mode=[^?|&]*/", "/([?|&])phpinfo=[^?|&]*/", "/([?|&])printstats=[^?|&]*/", "/[?|&]".URL_ID."=[^?|&]*/", "/[?|&]l=[^?|&]*/", "/[&?]+$/"), array("", "", "", "", "", "", ""), addslashes($self_url));
  }

  function url($url, $amp = "&amp;") {
    global $l;
    $dummy_array = explode("#", $url);
    $url = $dummy_array[0];

    if ($this->mode == "get" && !preg_match("/".SESSION_NAME."=/i", $url)) {
      $url .= preg_match("/\?/", $url) ? "$amp" : "?";
      $url .= SESSION_NAME."=".$this->session_id;
    }

    if (!empty($l)) {
      $url .= preg_match("/\?/", $url) ? "$amp" : "?";
      $url .= "l=".$l;
    }

    $url .= (isset($dummy_array[1])) ? "#".$dummy_array[1] : "";
    return $url;
  }
} //end of class

//-----------------------------------------------------
//--- Start Session -----------------------------------
//-----------------------------------------------------
define('COOKIE_NAME', '4images_');
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', '0');

$site_sess = new Session();

// Get Userinfo
$session_info = $site_sess->return_session_info();
$user_info = $site_sess->return_user_info();

//-----------------------------------------------------
//--- Get User Caches ---------------------------------
//-----------------------------------------------------
$num_total_online = 0;
$num_visible_online = 0;
$num_invisible_online = 0;
$num_registered_online = 0;
$num_guests_online = 0;
$user_online_list = "";
$prev_user_ids = array();
$prev_session_ips = array();

if (defined("GET_USER_ONLINE") && ($config['display_whosonline'] == 1 || $user_info['user_level'] == ADMIN)) {
  $time_out = time() - 300;
  $sql = "SELECT ".get_user_table_field("u.", "user_id").get_user_table_field(", u.", "user_level").get_user_table_field(", u.", "user_name").get_user_table_field(", u.", "user_invisible").", s.session_user_id, s.session_lastaction, s.session_ip 
	  FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s 
	  WHERE ".get_user_table_field("u.", "user_id")." = s.session_user_id AND (s.session_lastaction >= $time_out OR ".get_user_table_field("u.", "user_lastaction")." >= $time_out) 
	  ORDER BY ".get_user_table_field("u.", "user_id")." ASC, s.session_ip ASC";
  $result = $site_db->query($sql);
  while ($row = $site_db->fetch_array($result)) {
    if ($row['session_user_id'] != GUEST) {
      if (!isset($prev_user_ids[$row['session_user_id']])) {
        $is_invisible = (isset($row[$user_table_fields['user_invisible']]) && $row[$user_table_fields['user_invisible']] == 1) ? 1 : 0;
        $invisibleuser = ($is_invisible) ? "*" : "";
        $username = (isset($row[$user_table_fields['user_level']]) && $row[$user_table_fields['user_level']] == ADMIN && $config['highlight_admin'] == 1) ? sprintf("<b>%s</b>", $row[$user_table_fields['user_name']]) : $row[$user_table_fields['user_name']];
        if (!$is_invisible || $user_info['user_level'] == ADMIN) {
          $user_online_list .= ($user_online_list != "") ? ", " : "";
          $user_profile_link = (!empty($url_show_profile)) ? preg_replace("/{user_id}/", $row['session_user_id'], $url_show_profile) : ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$row['session_user_id'];
          $user_online_list .= "<a href=\"".$site_sess->url($user_profile_link)."\">".$username."</a>".$invisibleuser;
          $num_visible_online++;
        }
        $num_registered_online++;
      }
      $prev_user_ids[$row['session_user_id']] = 1;
    }
    else {
      if (!isset($prev_session_ips[$row['session_ip']])) {
        $num_guests_online++;
      }
    }
    $prev_session_ips[$row['session_ip']] = 1;
  }
  $num_total_online = $num_registered_online + $num_guests_online;
  $num_invisible_online = $num_registered_online - $num_visible_online;

  $site_template->register_vars(array(
    "num_total_online" => $num_total_online,
    "num_invisible_online" => $num_invisible_online,
    "num_registered_online" => $num_registered_online,
    "num_guests_online" => $num_guests_online,
    "user_online_list" => $user_online_list
  ));
  $whos_online = $site_template->parse_template("whos_online");
  $site_template->register_vars("whos_online", $whos_online);
  unset($whos_online);
  unset($prev_user_ids);
  unset($prev_session_ips);
}
?>