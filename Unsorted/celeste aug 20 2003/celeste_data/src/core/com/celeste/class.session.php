<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

class celesteSession {


	function celesteSession($sessID = 'CES', $readonly = 0) {
		session_save_path(DATA_PATH.'/session/');
		session_name($sessID);
    //session_cache_limiter('private');
		session_start();
	}

	function get($sess_name) {
		return (isset($_SESSION[$sess_name]) ? $_SESSION[$sess_name] : null);
	}

	function set($sess_name, $sess_value) {
		$_SESSION[$sess_name] = $sess_value;
	}

	function isregistered($sess_name) {
		return isset($_SESSION[$sess_name]);
	}

	function register($sess_name) {
		return session_register($sess_name);
	}

	function delete($sess_name) {
		session_unset($sess_name);
	}

	function destroy() {
		return session_destroy();
	}

	function sid() {
		return SID;
	}
	
	function close() {
		session_write_close();
	}
}

?>