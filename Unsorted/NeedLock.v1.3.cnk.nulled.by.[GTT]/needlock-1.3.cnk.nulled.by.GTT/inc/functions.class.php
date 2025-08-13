<?php

 /*
+--------------------------------------------------------------------------
|   > $$FUNCTIONS.CLASS.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

class functions {


 function load_words($current_lang_array, $area, $lang_type) {
    global $DIRS,$INFO;

        $needed_lang_file = $DIRS['LANGS'].$lang_type."/".$area.$INFO['PHP_EXT'];
	$default_lang_file = $DIRS['LANGS'].$INFO['DEFAULT_LANG']."/".$area.$INFO['PHP_EXT'];

	if ( file_exists($needed_lang_file) ) {
           require ( $needed_lang_file );
        } elseif ( file_exists($default_lang_file) ) {
           require ( $default_lang_file );
	} else {
           $this->Error("Langfile load error: needed file [ {$needed_lang_file} ] doesn't exists; default file [ {$default_lang_file} ] doesn't exists");
	}

        foreach ($lang as $k => $v)
        {
        	$current_lang_array[$k] = stripslashes($v);
        }

        unset($lang);

        return $current_lang_array;
 }

function getServerLoad() {

  if ( file_exists('/proc/loadavg') ) {
    if ( $fh = @fopen('/proc/loadavg', 'r' ) ) {
       $line = @fread( $fh, 30 );
       @fclose( $fh );
       $server_load = explode( " ", $line );
       return "{$server_load[0]} {$server_load[1]} {$server_load[2]} {$server_load[3]} {$server_load[4]}";
    }
  } else {
    return "<strong>N/A</strong>";
  }

}

function serverLoad() {

 if ( file_exists('/proc/loadavg') ) {
     if ( $fh = @fopen( '/proc/loadavg', 'r' ) ) {
	$data = @fread( $fh, 6 );
	@fclose( $fh );

	$load_avg = explode( " ", $data );
	$needsecure->server_load = trim($load_avg[0]);

        if ($needsecure->server_load > 3) {
           $this->Error("Sorry, server too busy. Try visit us later.");
        } else {
	    return true;
	  }
     }
 } else {
     return false;
   }

}

function checkBann($id="",$email="",$in_ip="") {
  global $INFO,$needsecure;

  if ( (!empty($id)) or (!empty($email)) ) {

        $ips = array();

        if ( file_exists("{$needsecure->dirs['TOP']}ip.ban") ) {
                $fh = @fopen("{$needsecure->dirs['TOP']}ip.ban","r");
                $line = @fgets($fh);
                $line = preg_replace("#^\|#","",$line);
		$line = preg_replace("#\|$#","",$line);
           if ( strlen( chop($line) ) > 1 ) {
		$ips = explode("|",chop($line));
	   }
                @fclose($fh);
           if ( count($ips) > 0 ) {
                foreach ($ips as $ip) {
	                $ip = preg_replace( "/\*/", '.*' , $ip );
	                if (preg_match( "/$ip/", $in_ip )) {
	                        $this->Error("Sorry, but You was banned by system administrator [ by ip ]");
	                }
                }
	   }
        }
  }

  if ( (!empty($id)) or (!empty($email)) ) {

     if ( file_exists("{$needsecure->dirs['TOP']}id_name_email.ban") ) {
        $fh = @fopen("{$needsecure->dirs['TOP']}id_name_email.ban","r");
        $i = 0;
        while ( $line = @fgets($fh) ) {
           $lineArr = explode("|",chop($line));
	   $banData[$i]['id'] = $lineArr[0];
	   $banData[$i]['email'] = $lineArr[1];
	   $i++;
        }
        @fclose($fh);

        // member_id?
        if ( !empty($id) ) {
           for ( $i=0; $i<count($banData); $i++ ) {
             if ( preg_match( "/$id/", $banData[$i]['id'] ) ) {
                $this->Error("Sorry, but You was banned by system administrator [ by id ]");
	     }
	   }
        }

        // member_email?
        if ( !empty($email) ) {
           for ( $i=0; $i<count($banData); $i++ ) {
             if ( preg_match( "/$email/", $banData[$i]['email'] ) ) {
                $this->Error("Sorry, but You was banned by system administrator [ by email ]");
	     }
	   }
        }

     }

  }

}

 function parse_incoming() {
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
    $return = array();

	if(is_array($HTTP_GET_VARS)) {
	  while( list($k, $v) = each($HTTP_GET_VARS) ) {
		if( is_array($HTTP_GET_VARS[$k]) ) {
		  while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) ) {
			$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
		  }
		} else {
		   $return[$k] = $this->clean_value($v);
		  }
	  }
	}

    // Overwrite GET data with post data

	if(is_array($HTTP_POST_VARS)) {
	  while( list($k, $v) = each($HTTP_POST_VARS) ) {
		if ( is_array($HTTP_POST_VARS[$k]) ) {
		  while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) ) {
			$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
		  }
		} else {
		   $return[$k] = $this->clean_value($v);
		  }
	  }
	}

	// Sort out the accessing IP

	$return['IP_ADDRESS'] = $this->select_var(
	                                            array(
							  1 => $_SERVER['REMOTE_ADDR'],
							  2 => $HTTP_X_FORWARDED_FOR,
							  3 => $HTTP_PROXY_USER,
							  4 => $REMOTE_ADDR
							  )
						 );

	// Make sure we take a valid IP address

	$return['IP_ADDRESS'] = preg_replace( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return['IP_ADDRESS'] );

	$return['request_method'] = ( $_SERVER['REQUEST_METHOD'] != "" ) ? strtolower($_SERVER['REQUEST_METHOD']) : strtolower($REQUEST_METHOD);

  return $return;
}


function clean_key($key) {

  if ($key == "") {
    return "";
  }

	$key = preg_replace( "/\.\./"       , ""  , $key );
    $key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    $key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
   return $key;
}

function clean_value($val) {

  if ($val == "") {
    return "";
  }

    $val = str_replace( "&#032;"       , " "             , $val );
    $val = str_replace( "&"            , "&amp;"         , $val );
    $val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    $val = str_replace( "-->"          , "--&#62;"       , $val );
    $val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    $val = str_replace( ">"            , "&gt;"          , $val );
    $val = str_replace( "<"            , "&lt;"          , $val );
    $val = str_replace( "\""           , "&quot;"        , $val );
    $val = preg_replace( "/\|/"        , "&#124;"        , $val );
    $val = preg_replace( "/\n/"        , "<br>"          , $val );
    $val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    $val = preg_replace( "/\r/"        , ""              , $val );
    $val = str_replace( "!"            , "&#33;"         , $val );
    $val = str_replace( "'"            , "&#39;"         , $val );
    $val = stripslashes($val);
    $val = preg_replace( "/\\\/"       , "&#092;"        , $val );
  return $val;
}

function select_var($array) {

    	if ( !is_array($array) ) return -1;

    	ksort($array);

    	$chosen = -1;

    	foreach ($array as $k => $v)
    	{
    		if (isset($v))
    		{
    			$chosen = $v;
    			break;
    		}
    	}

    	return $chosen;
}


function is_number($number="") {

  if ($number == "") return -1;

    if ( preg_match( "/^([0-9]+)$/", $number ) ) {
      return $number;
    } else {
       return "";
      }
}

function my_uniqid() {
       srand((float) microtime() * 1000000);
         return uniqid(rand());
}

function integer($x,$y) {
     if(empty($x) or empty($y)) return false;
      if(($x % $y) == 0) return ($x/$y);
       else return ((int)($x/$y) + 1);
}

function decode($str) {
     $str = preg_replace("/'/","&#39;",$str);
     $str = preg_replace('/"/',"&#34;",$str);
     $str = preg_replace("/</","&#60;",$str);
     $str = preg_replace("/>/","&#62;",$str);
     return $str;
}

function encode($str) {
     $str = preg_replace("/&#39;/","'",$str);
     $str = preg_replace("/&#34;/",'"',$str);
     $str = preg_replace("/&#60;/","<",$str);
     $str = preg_replace("/&#62;/",">",$str);
     return $str;
}

function wrap($wln, $str) {
      $strln = strlen($str);
      $parts = $this->integer($strln, $wln);
       $Tmp = ""; $ri = 0;
        for( $i=0; $i < $parts; $i++ ) {
          $Tmp .= substr($str, $ri, $wln) . " ";
         $ri = $ri + $wln;
        }
      return $Tmp;
}


function clean_email($email = "") {

    $email = preg_replace( "#[\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/]#", "", $email );

    if ( preg_match( "/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email) ) {
      return $email;
    } else {
       return FALSE;
      }
}

function validate_date($date="") {

  if ( preg_match( "/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/" , $date ) ) {
     return $date;
  } else {
     return false;
  }

}

function make_password() {
	$pass = "";
	$chars = array(
		"1","2","3","4","5","6","7","8","9","0",
		"a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
		"k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
		"u","U","v","V","w","W","x","X","y","Y","z","Z"
	);

	$count = count($chars) - 1;

	srand((double)microtime()*1000000);

	for($i = 0; $i < 8; $i++) {
	  $pass .= $chars[ rand(0, $count) ];
	}
   return($pass);
}


function my_setcookie($name, $value = "", $sticky = 1) {
     global $INFO;

     if ($sticky == 1) {
       $expires = time() + 60*60;
     }

     $INFO['cookie_domain'] = $INFO['cookie_domain'] == "" ? ""  : $INFO['cookie_domain'];
     $INFO['cookie_path']   = $INFO['cookie_path']   == "" ? "/" : $INFO['cookie_path'];

     $name = $INFO['cookie_id'].$name;

      setcookie($name, $value, $expires, $INFO['cookie_path'], $INFO['cookie_domain']);

}

function my_getcookie($name) {
    global $INFO, $HTTP_COOKIE_VARS;

    if(isset($HTTP_COOKIE_VARS[$INFO['cookie_id'].$name])) {
      return urldecode($HTTP_COOKIE_VARS[$INFO['cookie_id'].$name]);
    } else {
    	return FALSE;
      }
}

function Error($err_msg) {
     global $needsecure,$tpl;

     $tpl->load_file("error.tpl","main");
     $tpl->set_var("page_title",$INFO['SITE_NAME']);
     $tpl->set_var("page_sub_title","Error");
     $tpl->set_var("words_error_page_text",$needsecure->words['error_page_text']);
     $tpl->set_var("words_go_back",$needsecure->words['go_back']);
     $tpl->set_var("words_contact_webmaster",$needsecure->words['contact_webmaster']);
     $tpl->set_var("err_msg",$err_msg);
     $tpl->pparse("main",true);

     exit;

}

function redirectPage($url,$initial_msg) {
     global $needsecure,$INFO,$tpl;

     $tpl->load_file("redirect.tpl","main");
     $tpl->set_var("page_title",$INFO['SITE_NAME']);
     $tpl->set_var("page_sub_title",$needsecure->words['redirect_page_subtitle']);
     $tpl->set_var("redirect_link_text",$needsecure->words['redirect_link_text']);
     $tpl->set_var("url",$url);
     $tpl->set_var("initial_msg",$initial_msg);
     $tpl->pparse("main",true);

     exit;

}

function saveConfig($new_info) {
     global $needsecure, $INFO, $DIRS;

$INF = '';

$NEW_INFO_FH = $INFO;

foreach ( $new_info as $new_info_key => $new_info_val ) {
  $NEW_INFO_FH[ $new_info_key ] = $new_info_val;
}

$FH = @fopen( $DIRS['TOP'] . 'inf.php' , "w" );


$INF .= "<?php

";

foreach ( $NEW_INFO_FH as $info_key => $info_val ) {
$INF .= "\$INFO[\"".$info_key."\"]\t\t=\t\"".$info_val."\";\n";
}

$INF .= "

?>
";

@fputs( $FH, $INF );
@fclose($FH);

}

function writeAdminLog($action) {
   global $needsecure,$DB;

     $log['id'] = time();
     $log['ctime'] = date('Y-m-d H:i:s');
     $log['admin_id'] = $needsecure->admin['id'];
     $log['admin_name'] = $needsecure->admin['name'];
     $log['admin_level'] = $needsecure->admin['level'];
     $log['admin_email'] = $needsecure->admin['email'];
     $log['admin_action'] = $action;
     $log['admin_ip'] = $needsecure->admin['ip_address'];

     $DB->query("INSERT INTO ns_admin_logs (id,ctime,admin_id,admin_name,admin_level,admin_email,admin_action,admin_ip)
                 VALUES
                 ('{$log['id']}', '{$log['ctime']}', '{$log['admin_id']}', '{$log['admin_name']}', '{$log['admin_level']}', '{$log['admin_email']}', '{$log['admin_action']}', '{$log['admin_ip']}')");

}


}


?>