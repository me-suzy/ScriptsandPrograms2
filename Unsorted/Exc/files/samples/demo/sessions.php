<?php

function session_handler_open($save_path,$sess_name) {
    return true;
}

function session_handler_close() {
    return true;
}

function session_handler_read($id) {
    global $sesspath;

    $fh = @fopen($sesspath.$id,"rb");
    if( $fh === false ) return false;

    $sess_data = fread($fh,filesize($sesspath.$id));
    @fclose($fh);

    return (string)$sess_data;
}

function session_handler_write($id,$sess_data) {
    global $sesspath;

    $fh = @fopen($sesspath.$id,"wb");
    if( $fh === false ) return false;

    fwrite($fh,$sess_data);
    fclose($fh);

    return true;
}

function session_handler_destroy($id) {
    global $sesspath;

    @unlink($sesspath.$id);
    return true;
}

function session_handler_gc($maxlifetime) {
    return true;
}

function session_handlers_register() {
   session_set_save_handler("session_handler_open",
			    "session_handler_close",
			    "session_handler_read",
			    "session_handler_write",
			    "session_handler_destroy",
			    "session_handler_gc");
}

function DeleteOldSessions($timeout) {
    global $sesspath,$uploaddir;

    $fh = @fopen($sesspath.'info',"ab+");
    if( $fh === false ) return false;

    fseek($fh,0);
    $now = time();

    $data = unserialize(fread($fh,filesize($sesspath.'info')));

    $newdata = array();
    if( is_array($data) > 0 ) {
      foreach($data as $key => $value ) {
        if( $now-$value > $timeout ) {
          @unlink($sesspath.$key);
          @unlink($uploaddir.$key);
        } else {
          $newdata[$key] = $value;
        }
      }
    }

    ftruncate($fh,0);
    fwrite($fh,serialize($newdata));
    fclose($fh);
}

function UpdateCurrentSession($id) {
    global $sesspath;

    $fh = fopen($sesspath.'info',"ab+");
    if( $fh === false ) return false;

    fseek($fh,0);
    $data = unserialize(fread($fh,filesize($sesspath.'info')));
    $data[$id] = time();
    ftruncate($fh,0);
    fwrite($fh,serialize($data));
    fclose($fh);
}

session_handlers_register();

DeleteOldSessions($sesstimeout*60);
session_start();
UpdateCurrentSession(session_id());

if( get_cfg_var('register_globals') ) {
	// register_globals enabled - must be used session_register()
	if(!session_is_registered('session')) {
		$session = array();
		session_register('session');
	}

} else {
	if( !isset($HTTP_SESSION_VARS['session']) ) {
		$HTTP_SESSION_VARS['session'] = array();
	}
	$session = &$HTTP_SESSION_VARS['session'];
}

?>