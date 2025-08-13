<?php

/*
+--------------------------------------------------------------------------
|   > $$A_SESSIONS.CLASS.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

class sessions {

    var $ip_address = 0;
    var $user_agent = "";
    var $time_now   = 0;
    var $session_id = 0;
    var $session_dead_id = 0;
    var $session_admin_id = 0;
    var $session_admin_pass = "";
    var $admin            = array();

    function auth() {
        global $needsecure,$std,$DB, $INFO, $HTTP_USER_AGENT;

        $this->admin = array( 'id' => 0, 'password' => "", 'name' => "", 'level' => 0 );

        $this->ip_address = $needsecure->input['IP_ADDRESS'];
        $this->user_agent = substr($_SERVER["HTTP_USER_AGENT"],0,50);
        $this->time_now   = time();
	    $this->admin['ip_address'] = $this->ip_address;

	    /*
         @ Wipe expired sessions
	     @ some non standart for flat DB without SQL math operations :)
	    */

        $DB->query("SELECT id,last_activity FROM ns_admin_sessions");
        if ( $DB->get_num_rows() > 0 ) {
            while ( $res = $DB->fetch_row() ) {
               if ( ($res['last_activity'] + $INFO['SESS_EXPIRE']) < $this->time_now ) {
                  $dead_sessions[] = $res['id'];
	           }
	       }
	    }

        for ($i=0;$i<count($dead_sessions);$i++) {
            $DB->query("DELETE FROM ns_admin_sessions WHERE id='{$dead_sessions[$i]}'");
	    }

        // Well, lets start auth admin ...

	    if ( $needsecure->input['act'] == '' ) {
                $this->no_false = TRUE;
		        return $this->admin;
        }

        $cookie = array();
        $cookie['asession_id']   = $std->my_getcookie('asession_id');
        $cookie['admin_id']      = $std->my_getcookie('admin_id');
        $cookie['apass_hash']    = $std->my_getcookie('apass_hash');
	$cookie['alang_id']      = $std->my_getcookie('alang_id');

        if (! empty($cookie['asession_id']) ) {
        	$this->get_session($cookie['asession_id']);
        } elseif (! empty($needsecure->input['s']) ) {
        	$this->get_session($needsecure->input['s']);
        } else {
        	$this->session_id = 0;
        }

        if ( ($this->session_id != 0) and ( ! empty($this->session_id) ) ) {

	   if ( ($this->session_admin_id != 0) and ( ! empty($this->session_admin_id) ) ) {

  	      $this->loadAdmin($this->session_admin_id);

	      if ( (! $this->admin['id']) or ($this->admin['id'] == 0) ) {
		 $this->unloadAdmin();
		 $this->updateGuestSession();
	      } else {
		 $this->updateAdminSession();
	      }
	   } else {
	      $this->updateGuestSession();
	   }

	} else {

  	   if ($cookie['admin_id'] != "" and $cookie['apass_hash'] != "") {
	       $this->loadAdmin($cookie['admin_id']);
	       if ( (! $this->admin['id']) or ($this->admin['id'] == 0) ) {
		  $this->unloadAdmin();
		  $this->createGuestSession();
	       } else {
		  if ($this->admin['password'] == $cookie['apass_hash']) {
		     $this->createAdminSession();
		  } else {
		     $this->unloadAdmin();
		     $this->createGuestSession();
		  }
	       }
	   } else {
	       $this->createGuestSession();
	   }
        }

        if (! $this->admin['id']) {
            $this->admin['id']          = 0;
            $this->admin['name']        = "";
	    $this->admin['password']    = "";
	    $this->admin['level']       = 0;
	    $this->admin['lang']        = "";
	}

        $std->my_setcookie("asession_id", $this->session_id, -1);

        $this->admin['ip_address'] = $this->ip_address;
        $this->no_false = FALSE;
	    return $this->admin;

    }

    function loadAdmin($admin_id=0) {
    	global $needsecure, $DB, $std, $INFO;

     	if ($admin_id != 0) {

            $DB->query("SELECT id,name,password,email,level,lang FROM ns_admins WHERE id='{$admin_id}'");

            if ( $DB->get_num_rows() ) {
            	$this->admin = $DB->fetch_row();
            }

            if ( ($this->admin['id'] == 0) or (empty($this->admin['id'])) ) {
		$this->unloadAdmin();
            }
	}

	unset($admin_id);
    }

    function unloadAdmin() {
       global $std;

       $std->my_setcookie( "asession_id" , "0", -1  );
       $std->my_setcookie( "admin_id"    , "0", -1  );
       $std->my_setcookie( "apass_hash"  , "0", -1  );
       $std->my_setcookie( "alang_id"    , "0", -1  );

       $this->admin['id']       = 0;
       $this->admin['name']     = "";
       $this->admin['password'] = "";
       $this->admin['level']    = 0;
       $this->admin['lang']     = "";

    }

    function updateAdminSession() {
        global $needsecure, $DB;

        if ( (empty($this->session_id)) or ($this->session_id == 0) ) {
            $this->createAdminSession();
            return;
        }

        if (empty($this->admin['id'])) {
            $this->unloadAdmin();
            $this->createGuestSession();
            return;
        }

        $DB->query("UPDATE ns_admin_sessions
	          SET
		  admin_id='{$this->admin['id']}',
                  admin_name='{$this->admin['name']}',
		  admin_level='{$this->admin['level']}',
		  admin_ip='{$this->ip_address}',
		  last_activity='{$this->time_now}'
		  WHERE id='".$this->session_id."'");
    }

    function updateGuestSession() {
        global $needsecure, $DB, $INFO;

        if ( (empty($this->session_id)) or ($this->session_id == 0) ) {
           $this->createGuestSession();
           return;
        }

        $DB->query("UPDATE ns_admin_sessions
	           SET admin_id='0',admin_name='',admin_level='',admin_ip='{$this->ip_address}',last_activity='{$this->time_now}'
                   WHERE id='{$this->session_id}'");
    }

    function get_session($session_id="") {
        global $DB, $INFO, $std;

        $result = array();

        $query = "";

        $session_id = preg_replace("/([^a-zA-Z0-9])/", "", $session_id);

        if ( !empty($session_id) ) {

	   $DB->query("SELECT id, admin_id, admin_name, admin_level, admin_ip
	              FROM ns_admin_sessions
		      WHERE id='{$session_id}' and admin_ip='{$this->ip_address}'");

	   if ($DB->get_num_rows() != 1) {
              $this->session_dead_id   = $session_id;
	      $this->session_id        = 0;
              $this->session_admin_id   = 0;
              return;
	   } else {
	      $result = $DB->fetch_row();

	      if ($result['id'] == "") {
		  $this->session_dead_id   = $session_id;
		  $this->session_id        = 0;
		  $this->session_admin_id   = 0;
		  unset($result);
		  return;
	      } else {
		  $this->session_id        = $result['id'];
		  $this->session_admin_id   = $result['admin_id'];
    		  unset($result);
		  return;
	      }
	   }
	}
    }

    function createAdminSession() {
        global $needsecure, $DB, $INFO, $std;

        if ($this->admin['id']) {

	   $DB->query("DELETE FROM ns_admin_sessions WHERE admin_id='{$this->admin['id']}'");

	   $this->session_id  = md5( uniqid(microtime()) );

	   $DB->query("INSERT INTO ns_admin_sessions
	               (id, admin_id, admin_name, admin_level, admin_ip, last_activity)
		       VALUES
		       ('{$this->session_id}','{$this->admin['id']}','{$this->admin['name']}','{$this->admin['level']}','{$this->ip_address}','{$this->time_now}')");
	} else {
	   $this->createGuestSession();
	}
    }

    function createGuestSession() {
        global $needsecure, $DB, $INFO, $std;


	if ( ($this->session_dead_id != 0) and ( ! empty($this->session_dead_id) ) ) {
	   $extra = " or id='".$this->session_dead_id."'";
	} else {
	   $extra = "";
	}

	$DB->query( "DELETE FROM ns_admin_sessions WHERE admin_ip='".$this->ip_address."'".$extra);

	$this->session_id  = md5( uniqid(microtime()) );

	$DB->query("INSERT INTO ns_admin_sessions
	            (id, admin_id, admin_name, admin_level, admin_ip,last_activity)
	            VALUES ('{$this->session_id}','0','','0','{$this->ip_address}','{$this->time_now}')");

    }

} // End of class sessions;

?>