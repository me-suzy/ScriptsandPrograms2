<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.php,v 1.5 2002/08/27 10:39:47 eppner Exp $
 *
 */

include_once ($cfg["path"]["contenido"] . $cfg["path"]["classes"] . 'class.inuse.php');

class DB_Contenido extends DB_Sql {

  var $Host;
  var $Database;
  var $User;
  var $Password;

  var $Halt_On_Error = "report";

  //Konstruktor
  function DB_Contenido()
  {
      global $contenido_host, $contenido_database, $contenido_user, $contenido_password;

      $this -> Host = $contenido_host;
      $this -> Database = $contenido_database;
      $this -> User = $contenido_user;
      $this -> Password = $contenido_password;
  }

  function haltmsg($msg) {
    error_log($msg);
  }
  
  function copyResultToArray ()
  {
  		$values = array();
  		
  		$metadata = $this->metadata();
		
		if (!is_array($metadata))
		{
			return false;
		}
		
		foreach ($metadata as $entry)
		{
			$values[$entry['name']] = $this->f($entry['name']);
		}
		
		return $values;
  }
}

class Contenido_CT_Sql extends CT_Sql {
	
  
  
  var $database_class = "DB_Contenido";          ## Which database to connect...
  var $database_table = ""; ## and find our session data in this table.


  function Contenido_CT_Sql ()
  {
  	global $cfg;
  	$this->database_table = $cfg["tab"]["phplib_active_sessions"];

  }
  	
}

class Contenido_Session extends Session {

  var $classname = "Contenido_Session";

  var $cookiename     = "contenido";        ## defaults to classname
  var $magic          = "123Hocuspocus";    ## ID seed
  var $mode           = "get";              ## We propagate session IDs with cookies
  var $fallback_mode  = "cookie";
  var $lifetime       = 0;                  ## 0 = do session cookies, else minutes
  var $that_class     = "Contenido_CT_Sql"; ## name of data storage container
  var $gc_probability = 5;
  
  
  function delete ()
  {
  	$col = new InUseCollection;
	$col->removeSessionMarks($this->id);	
	
	parent::delete();
  }
}

class Contenido_Frontend_Session extends Session {

  var $classname = "Contenido_Frontend_Session";

  var $cookiename     = "sid";              ## defaults to classname
  var $magic          = "Phillipip";        ## ID seed
  var $mode           = "cookie";           ## We propagate session IDs with cookies
  var $fallback_mode  = "cookie";
  var $lifetime       = 0;                  ## 0 = do session cookies, else minutes
  var $that_class     = "Contenido_CT_Sql"; ## name of data storage container
  var $gc_probability = 5;
  
  function Contenido_Frontend_Session ()
  {
  	global $load_lang, $load_client;
  	
  	$this->cookiename = "sid_".$load_client."_".$load_lang;
  }
}

class Contenido_Auth extends Auth {
  var $classname      = "Contenido_Auth";

  var $lifetime       =  15;

  var $database_class = "DB_Contenido";
  var $database_table = "con_phplib_auth_user";

  function auth_loginform() {
    global $sess;
    global $_PHPLIB;
    global $cfgPathImg;

    include($_PHPLIB["libdir"] . "loginform.ihtml");
  }

function auth_validatelogin() {
    global $username, $password;
    if(isset($username)) {
        $this->auth["uname"]=$username;     ## This provides access for "loginform.ihtml"
    }else if ($this->nobody){                      ##  provides for "default login cancel"
        $uid = $this->auth["uname"] = $this->auth["uid"] = "nobody";
        return $uid;
    }
    $uid = false;


    $this->db->query(sprintf("select user_id, perms ".
                             "        from %s ".
                             "       where username = '%s' ".
                             "         and password = '%s'",
                          $this->database_table,
                          addslashes($username),
                          addslashes($password)));

    while($this->db->next_record()) {
      $uid = $this->db->f("user_id");
      $this->auth["perm"] = $this->db->f("perms");
    }
    return $uid;
  }
}

class Contenido_Default_Auth extends Contenido_Auth {

  var $classname = "Contenido_Default_Auth";
  var $lifetime       =  1;

  function auth_loginform() {

    global $sess;
    global $_PHPLIB;
    global $cfgPathImg;

    include($_PHPLIB["libdir"] . "defloginform.ihtml");
  }

  var $nobody    = true;
}

class Contenido_Challenge_Auth extends Auth {
  var $classname      = "Contenido_Challenge_Auth";

  var $lifetime       =  1;

  var $magic          = "Simsalabim";  ## Challenge seed
  var $database_class = "DB_Contenido";
  var $database_table = "con_phplib_auth_user";

  function auth_loginform() {
    global $sess;
    global $challenge;
    global $_PHPLIB;
    global $cfgPathImg;

    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");

    include($_PHPLIB["libdir"] . "crloginform.ihtml");
  }

  function auth_validatelogin() {
    global $username, $password, $challenge, $response;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }

    # Sanity check: If the user presses "reload", don't allow a login with the data
    # again. Instead, prompt again.
    if ($timestamp < (time() - 60*15))
    {
        return false;
    }
    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if ($password != $pass) {
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }

    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  }
}

##
## Contenido_Challenge_Crypt_Auth: Keep passwords in md5 hashes rather
##                           than cleartext in database
## Author: Jim Zajkowski <jim@jimz.com>

class Contenido_Challenge_Crypt_Auth extends Auth {

  var $classname      = "Contenido_Challenge_Crypt_Auth";
  var $lifetime       =  15;
  var $magic          = "Frrobo123xxica";  ## Challenge seed
  var $database_class = "DB_Contenido";
  var $database_table = "";
  var $group_table = "";
  var $member_table = "";

  function Contenido_Challenge_Crypt_Auth ()
  {
  	global $cfg;
  	$this->database_table = $cfg["tab"]["phplib_auth_user_md5"];
	$this->group_table = $cfg["tab"]["groups"];
	$this->member_table = $cfg["tab"]["groupmembers"];
  }
  
  function auth_loginform() {

    global $sess;
    global $challenge;
    global $_PHPLIB;
    global $cfg;

    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");

    include ($cfg["path"]["contenido"] . 'main.loginform.php');
    
  }

  function auth_loglogin($uid)
  {
        global $cfg, $client, $lang, $auth, $sess, $saveLoginTime;
        
        $perm = new Contenido_Perm;
        
        $storeLoginTime = "true";
        $lastentry = $this->db->nextid($cfg["tab"]["actionlog"]);

        $timestamp = date("Y-m-d H:i:s");
        $idcatart = "0";

        /* Extract clients */
        $sql = "SELECT idclient FROM ".$cfg["tab"]["clients"]." ORDER BY idclient ASC";
        $this->db->query($sql);
        
        $clients = array();
        
        while ($this->db->next_record())
        {
        	array_push ($clients, $this->db->f("idclient"));
        }

		$found = 0;
		
		foreach ($clients as $key=>$value)
		{
			/* Extract languages */
	        $sql = "SELECT idlang FROM ".$cfg["tab"]["clients_lang"]." WHERE idclient = '".$value."' ORDER BY idlang";
	        $this->db->query($sql);
	        
	        while ($this->db->next_record())
	        {
		        $qlang = $this->db->f("idlang");  
	
	        	if ($perm->have_perm_client_lang($value, $qlang) && $found == 0)
	        	{
		        	$client = $value;
	        		$lang = $qlang;
	        		$found = 1;
	        	}
	        }
		}

        
        
        if (isset($idcat) && isset($idart))
        {
            $sql = "SELECT idcatart
                        FROM
                       ". $cfg["tab"]["cat_art"] ."
                    WHERE
                        idcat = $idcat AND
                        idart = $idart";
    
            $this->db->query($sql);
    
            $this->db->next_record();
            $idcatart = $this->db->f("idcatart");
    
        }
   
        if (!is_numeric($client)) { return; }
        if (!is_numeric($lang)) { return;  }

		$idaction = $perm->getIDForAction("login");
		
        $sql = "INSERT INTO
                    ". $cfg["tab"]["actionlog"]."
                SET
                    idlog = $lastentry,
                    user_id = '" . $uid . "',
                    idclient = $client,
                    idlang = $lang,
                    idaction = $idaction,
                    idcatart = $idcatart,
                    logtimestamp = '$timestamp'";
                    
        $this->db->query($sql);
        
        $sess->register("saveLoginTime");
        
        $saveLoginTime = true;
        
    }

  function auth_validatelogin() {

    global $username, $password, $challenge, $response, $formtimestamp;
    
    if (($formtimestamp + (60*15)) < time())
    {
    	return false;
    }
    
    if(isset($username)) {
        $this->auth["uname"]=$username;     ## This provides access for "loginform.ihtml"
    }else if ($this->nobody){                      ##  provides for "default login cancel"
        $uid = $this->auth["uname"] = $this->auth["uid"] = "nobody";
        return $uid;
    }
    $uid = false;

    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash
    }
    
    $this->db->query(sprintf("select A.group_id as group_id, A.perms as perms ".
                "from %s AS A, %s AS B where A.group_id = B.group_id AND B.user_id = '%s'",
                          $this->group_table,
                          $this->member_table,
                          $uid));

	if ($perm != "")
	{                          
		$gperm[] = $perm;
	}
	
    while ($this->db->next_record())
    {
    	$gperm[] = $this->db->f("perms");
    }
    
    if (is_array($gperm))
    {
    	$perm = implode(",",$gperm);
    }
    
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if (md5($password) != $pass) {       ## md5 hash for non-JavaScript browsers
        return false;
      } else {
        $this->auth["perm"] = $perm;
        $this->auth_loglogin($uid);
        return $uid;
      }
    }

    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      $this->auth_loglogin($uid);
      return $uid;
    }
  }
}

class Contenido_Frontend_Challenge_Crypt_Auth extends Auth {

  var $classname      = "Contenido_Frontend_Challenge_Crypt_Auth";
  var $lifetime       =  15;
  var $magic          = "Frrobo123xxica";  ## Challenge seed
  var $database_class = "DB_Contenido";
  var $database_table = "";
  var $nobody    = true;

  function Contenido_Frontend_Challenge_Crypt_Auth ()
  {
  	global $cfg;
  	$this->database_table = $cfg["tab"]["phplib_auth_user_md5"];

  }
  
   function auth_preauth()
  {
    global $username, $password, $challenge, $response;
    if(isset($username)) {
        $this->auth["uname"]=$username;     ## This provides access for "loginform.ihtml"
    }else if ($this->nobody){                      ##  provides for "default login cancel"
        $uid = $this->auth["uname"] = $this->auth["uid"] = "nobody";
        return $uid;
    }
    $uid = false;

    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));
                          
    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if (md5($password) != $pass) {       ## md5 hash for non-JavaScript browsers
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }

    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  	
  }
  
  function auth_loginform() {

    global $sess;
    global $challenge;
    global $_PHPLIB;
    global $cfgPathImg;

    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");

    include($cfgClient[$client]["htmlpath"]["frontend"]."front_crcloginform.inc.php");
    
  }

  function auth_validatelogin() {
    global $username, $password, $challenge, $response;
    if(isset($username)) {
        $this->auth["uname"]=$username;     ## This provides access for "loginform.ihtml"
    }else if ($this->nobody){                      ##  provides for "default login cancel"
        $uid = $this->auth["uname"] = $this->auth["uid"] = "nobody";
        return $uid;
    }
    $uid = false;

    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if (md5($password) != $pass) {       ## md5 hash for non-JavaScript browsers
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }

    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  }


}



?>
