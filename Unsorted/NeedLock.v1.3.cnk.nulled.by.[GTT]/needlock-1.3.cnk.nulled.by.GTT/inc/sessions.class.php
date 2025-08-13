<?php

/*
+--------------------------------------------------------------------------
|   > $$SESSIONS.CLASS.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

class sessions {

    var $ip_address = 0;
    var $user_agent = "";
    var $time_now   = 0;
    var $session_id = 0;
    var $session_dead_id = 0;
    var $session_user_id = 0;
    var $session_user_pass = "";
    var $member            = array();

    function authMember() {
        global $needsecure,$std,$DB, $INFO, $HTTP_USER_AGENT;

        $std->serverLoad();

        $this->member = array( 'id' => 0, 'password' => "", 'name' => "" );

        $this->ip_address = $needsecure->input['IP_ADDRESS'];
        $this->user_agent = substr($_SERVER["HTTP_USER_AGENT"],0,50);
        $this->time_now   = time();

	/*
         @ Wipe expired sessions
	 @ some non standart for flat DB without SQL math operations :)
	*/

        $DB->query("SELECT id,last_activity FROM ns_sessions");
        if ( $DB->get_num_rows() > 0 ) {
            while ( $res = $DB->fetch_row() ) {
               if ( ($res['last_activity'] + $INFO['SESS_EXPIRE']) <= $this->time_now ) {
                  $dead_sessions[] = $res['id'];
	       } else {
                  next;
	       }
	    }
	}
	for ($i=0;$i<count($dead_sessions);$i++) {
            $DB->query("DELETE FROM ns_sessions WHERE id='{$dead_sessions[$i]}'");
	}

        // Well, lets start auth member ...

        if ( $needsecure->input['act'] == 'reg' or $needsecure->input['act'] == '' ) {
                $this->no_false = TRUE;
		return $this->member;
        }

	$std->checkBann('','',$needsecure->input['IP_ADDRESS']);




        $cookie = array();
        $cookie['session_id']   = $std->my_getcookie('member_session_id');
        $cookie['member_id']    = $std->my_getcookie('member_id');
        $cookie['pass_hash']    = $std->my_getcookie('member_pass_hash');
	$cookie['lang_id']      = $std->my_getcookie('member_lang_id');


        if ( !empty($cookie['session_id']) ) {
        	$this->get_session($cookie['session_id']);
        } elseif ( !empty($needsecure->input['s']) ) {
        	$this->get_session($needsecure->input['s']);
        } else {
        	$this->session_id = 0;
        }

        if ( ($this->session_id != 0) and ( ! empty($this->session_id) ) ) {

	   if ( ($this->session_user_id != 0) and ( ! empty($this->session_user_id) ) ) {

  	      $this->loadMember($this->session_user_id);

	      if ( (! $this->member['id']) or ($this->member['id'] == 0) ) {
		 $this->unloadMember();
		 $this->updateGuestSession();
	      } else {
		 $this->updateMemberSession();
	      }
	   } else {
	      $this->updateGuestSession();
	   }

	} else {

  	   if ($cookie['member_id'] != "" and $cookie['pass_hash'] != "") {
	       $this->loadMember($cookie['member_id']);

	       if ( (! $this->member['id']) or ($this->member['id'] == 0) ) {
		  $this->unloadMember();
		  $this->createGuestSession();
	       } else {
		  if ($this->member['password'] == $cookie['pass_hash']) {
		     $this->createMemberSession();
		  } else {
		     $this->unloadMember();
		     $this->createGuestSession();
		  }
	       }
	   } else {
	       $this->createGuestSession();
	   }
        }

        if (! $this->member['id']) {
            $this->member['id'] = 0;
            $this->member['name'] = "";
	    $this->member['password'] = "";
	}

        if ($this->member['id']) {
           if ( ! $needsecure->input['last_activity'] ) {
	      $needsecure->input['last_activity'] = $this->time_now;
           }

 	      $DB->query("UPDATE ns_sessions SET last_activity='".$needsecure->input['last_activity']."' WHERE id='".$this->session_id."'");

        }

        $std->my_setcookie("member_session_id", $this->session_id, -1 );

        $this->no_false = FALSE;
	return $this->member;

    }

    function loadMember($member_id=0) {
    	global $needsecure, $DB, $std, $INFO;

     	if ($member_id != 0) {

            $DB->query("SELECT
              id,name,password,regdate,expire,email,realname,
	      lang,member_ip,member_browser,member_referrer,
	      last_login,count_visits,access_dirs,
	      suspended,approved,authcode
	      FROM ns_members
	      WHERE id='{$member_id}'");

            if ( $DB->get_num_rows() ) {
            	$this->member = $DB->fetch_row();
            }

            if ( ($this->member['id'] == 0) or (empty($this->member['id'])) ) {
		$this->unloadMember();
            }
	}

	unset($member_id);
    }

    /*
      @ Unload member, delete it's cookies, null it's data
    */
    function unloadMember() {
       global $std;

       $std->my_setcookie( "session_id" , "0", -1  );
       $std->my_setcookie( "member_id"  , "0", -1  );
       $std->my_setcookie( "pass_hash"  , "0", -1  );
       $std->my_setcookie( "lang_id"    , "0", -1  );

       $this->member['id']       = 0;
       $this->member['name']     = "";
       $this->member['password'] = "";
       $this->member['lang']     = "";
       $this->member['realname'] = "";
       $this->member['email']    = "";

    }

    function updateMemberSession() {
        global $needsecure, $DB;

        if ( (empty($this->session_id)) or ($this->session_id == 0) ) {
            $this->createMemberSession();
            return;
        }

        if (empty($this->member['id'])) {
            $this->unloadMember();
            $this->createGuestSession();
            return;
        }

        $DB->query("UPDATE ns_sessions
	          SET member_id='{$this->member['id']}',
                  member_name='{$this->member['name']}',
		  member_ip='{$this->ip_address}',
		  member_browser='{$this->user_agent}',
		  last_activity='{$this->time_now}'
		  WHERE id='".$this->session_id."'");
    }

    function updateGuestSession() {
        global $needsecure, $DB, $INFO;

        if ( (empty($this->session_id)) or ($this->session_id == 0) ) {
           $this->createGuestSession();
           return;
        }

        $DB->query("UPDATE ns_sessions
	           SET member_name='',member_id='0'
                   WHERE id='{$this->session_id}'");
    }

    function get_session($session_id="") {
        global $DB, $INFO, $std;

        $result = array();

        $query = "";

	$session_id = preg_replace("/([^a-zA-Z0-9])/", "", $session_id);

        if ( !empty($session_id) ) {

	   $DB->query("SELECT id, member_id
	              FROM ns_sessions
		      WHERE id='{$session_id}' and member_ip='{$this->ip_address}'");

	   if ($DB->get_num_rows() != 1) {
              $this->session_dead_id   = $session_id;
	      $this->session_id        = 0;
              $this->session_user_id   = 0;
              return;
	   } else {
	      $result = $DB->fetch_row();

	      if ($result['id'] == "") {
		  $this->session_dead_id   = $session_id;
		  $this->session_id        = 0;
		  $this->session_user_id   = 0;
		  unset($result);
		  return;
	      } else {
		  $this->session_id        = $result['id'];
		  $this->session_user_id   = $result['member_id'];
    		  unset($result);
		  return;
	      }
	   }
	}
    }

    function createMemberSession() {
        global $needsecure, $DB, $INFO, $std;

        if ($this->member['id']) {

	   $DB->query( "DELETE FROM ns_sessions WHERE member_id='{$this->member['id']}'");

	   $this->session_id  = md5( uniqid(microtime()) );

	   $DB->query("INSERT INTO ns_sessions
	               (id, member_id, member_name, member_ip, member_browser, last_activity)
		       VALUES
		       ('{$this->session_id}','{$this->member['id']}','{$this->member['name']}','{$this->ip_address}','{$this->user_agent}','{$this->time_now}')");
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

	$DB->query( "DELETE FROM ns_sessions WHERE member_ip='".$this->ip_address."'".$extra);

	$this->session_id  = md5( uniqid(microtime()) );

	$DB->query("INSERT INTO ns_sessions
	            (id, member_id, member_name, member_ip, member_browser, last_activity)
	            VALUES ('{$this->session_id}','0','','{$this->ip_address}','{$this->user_agent}','{$this->time_now}')");
    }

} // End of class sessions;

?>