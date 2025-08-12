<?php
/* $Id: class.user_phpcms.php,v 1.2.2.7 2004/07/16 09:01:34 bjmg Exp $ */
/*
   +----------------------------------------------------------------------+
   | phpCMS Content Management System - Version 1.2.0
   +----------------------------------------------------------------------+
   | phpCMS is Copyright (c) 2001-2003 by Michael Brauchl
   | and Contributing phpCMS Team Members
   +----------------------------------------------------------------------+
   | This program is free software; you can redistribute it and/or modify
   | it under the terms of the GNU General Public License as published by
   | the Free Software Foundation; either version 2 of the License, or
   | (at your option) any later version.
   |
   | This program is distributed in the hope that it will be useful, but
   | WITHOUT ANY WARRANTY; without even the implied warranty of
   | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   | General Public License for more details.
   |
   | You should have received a copy of the GNU General Public License
   | along with this program; if not, write to the Free Software
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston,
   | MA  02111-1307, USA.
   +----------------------------------------------------------------------+
   | Original Author: Michael Brauchl (mcyra)
   | Contributors:
   |    none
   +----------------------------------------------------------------------+
*/


//#######################################################################
// user klasse
//#######################################################################
// autor    Michael Brauchl
// datum    18.04.2001
// version  0.0.1
// lizenz   GPL
//#######################################################################
//
// diese klasse bietet eine interface zu den user-objekten von phpCMS.
//
// void new user
// =============
// legt ein neues user-objekt an. es ist nach der ausführung von
// "new user" noch kein user angelegt.
//
// STRING USERID    = eindeutige ID für jeden user
// MIXED GROUPS     = array aller verfügbaren gruppen
// MIXED STORE      = array zur ablage zusätzlicher objekte
// OBJECT data_container = data_container-klasse wrapper zur db
//
// VOID user(void)
// ===============
// legt einen neuen data_container an.
//
// VOID get_user_data(ID)
// ======================
// liest die daten des users mit der userid "ID"
// aus dem data_container. die daten werden dann im
// objekt USER abgelegt.
//
// STRING ID = userid länge: 128
//
// VOID save_user_data(ID)
// =======================
// speichert die daten aus dem objekt USER im
// data_container unter der userid "ID".
//
// STRING ID = userid länge: 128
//
// VOID delete_user(ID)
// ====================
// löscht den user mit der userid"ID" aus dem
// data_container.
//
// STRING ID = userid länge: 128
//
// MIXED search_user(FIELD, VALUE)
// ===============================
// durchsucht den gesamten data_container nach allen
// usern, bei denen das feld "FIELD" mit dem
// wert "VALUE" übereinstimmt.
// liefert ein array mit den userids aller
// gefundenen user zurück.
//
// VOID make_new_user(VOID)
// ========================
// legt eine einmalige, neue userid an.
// die userid wird im user-objekt abgelegt.
//
//#######################################################################

// include data_container-klasse
// derzeit noch hardcodiert - wird in phpCMS integriert.
//include ('D:/Programme/OpenSA/Apache/htdocs/parser/include/class.lib_data_file_phpcms.php');
// include error - library
// derzeit noch hardcodiert - wird in phpCMS integriert.
//include ('D:/Programme/OpenSA/Apache/htdocs/parser/include/class.lib_error_phpcms.php');
// set debug-mode

if(!defined("_USER_")) {
	define("_USER_", TRUE);
}
$GLOBALS['error_debug'] = false;

class user {
	var $USERID;
	var $VORNAME;
	var $NACHNAME;
	var $NICKNAME;
	var $EMAIL;
	var $PASSWORD;
	var $GROUPS;
	var $STORE;
	var $data_container;
	var $sortkey;

	function user($userdir) {
		debug_lines('user');
		$this->data_container = new data_container($userdir);
		$this->sortkey = "NICKNAME";
	}

	function get_user_data($ID) {
		debug_lines('get_user_data');
		$ID;
		if(!isset($ID)) {
			print_error('no user-id in call!', __FILE__, __LINE__);
		}
		$this->USERID   = $ID;
		$this->VORNAME  = $this->data_container->read_data($ID, 'VORNAME');
		$this->NACHNAME = $this->data_container->read_data($ID, 'NACHNAME');
		$this->NICKNAME = $this->data_container->read_data($ID, 'NICKNAME');
		$this->EMAIL    = $this->data_container->read_data($ID, 'EMAIL');
		$this->PASSWORD = $this->data_container->read_data($ID, 'PASSWORD');
		$this->GROUPS   = $this->data_container->read_data($ID, 'GROUPS');
		$this->STORE    = $this->data_container->read_data($ID, 'STORE');
	}

	function save_user_data($ID) {
		debug_lines('save_user_data');

		if(!isset($ID)) {
			print_error('no user-id in functioncall!', __FILE__, __LINE__);
		}
		//Take the Last Modification
		$this->STORE['LAST_MOD'] = time();
		$this->data_container->write_data($ID, 'VORNAME', $this->VORNAME);
		$this->data_container->write_data($ID, 'NACHNAME', $this->NACHNAME);
		$this->data_container->write_data($ID, 'NICKNAME', $this->NICKNAME);
		$this->data_container->write_data($ID, 'EMAIL', $this->EMAIL);
		$this->data_container->write_data($ID, 'PASSWORD', $this->PASSWORD);
		$this->data_container->write_data($ID, 'GROUPS', $this->GROUPS);
		$this->data_container->write_data($ID, 'STORE', $this->STORE);
	}

	function delete_user($ID) {
		debug_lines('delete_user');

		if(!isset($ID)) {
			print_error ('no user-id in functioncall!', __FILE__, __LINE__);
		}
		$this->data_container->delete_data($ID, 'VORNAME');
		$this->data_container->delete_data($ID, 'NACHNAME');
		$this->data_container->delete_data($ID, 'NICKNAME');
		$this->data_container->delete_data($ID, 'EMAIL');
		$this->data_container->delete_data($ID, 'PASSWORD');
		$this->data_container->delete_data($ID, 'GROUPS');
		$this->data_container->delete_data($ID, 'STORE');
	}

	function search_user($FIELD, $VALUE) {
		debug_lines('search_user');

		$return_array = Array();

		if(!isset($FIELD)) {
			print_error('no field in functioncall!', __FILE__, __LINE__);
		}

		if(!isset($VALUE)) {
			print_error('no value in functioncall!', __FILE__, __LINE__);
		}

		$return_array = $this->data_container->search_data($FIELD, $VALUE);

		if($return_array) {
			usort($return_array, Array($this, "user_sort_internal"));
		}
		return $return_array;
	}

	function make_new_user() {
		debug_lines('make_new_user');

		$this->USERID = md5(uniqid(rand()));

		//Take the Last Modification
		$this->STORE['CREATE_TIME'] = time();
	}

	function put_store($key, $val) {
		$this->STORE[$key] = $val;
	}

	function get_store($key) {
		if(isset($this->STORE[$key])) {
			return $this->STORE[$key];
		} else {
			return "";
		}
	}

	function get_var($key) {
		if(isset($this->$key)) {
			return $this->$key;
		} else {
			return '';
		}
	}

	function set_sortkey($key) {
		if(strlen($key) > 0) {
			$this->sortkey = $key;
		}
	}

	function user_sort_internal($a, $b) {
		$this->get_user_data($a);
		$mya=strtolower($this->get_var($this->sortkey));
		$this->get_user_data($b);
		$myb = strtolower($this->get_var($this->sortkey));
		return strcmp($mya, $myb);
	}
}

?>