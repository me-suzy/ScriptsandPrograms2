<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : files.class.php                             |
// |   begin                : 28 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 29/07/2004 16:00                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


class ftp {

	function ftp($host, $user, $pass, $port = 21, $timeout = 300) {

		$this -> host    = $host;
		$this -> user    = $user;
		$this -> pass    = $pass;
		$this -> port    = $port;
		$this -> timeout = $timeout;
	}

	function connect() {

		$this -> conn_id = @ftp_connect($this -> host, $this -> port, $this -> timeout);
		if (!$this -> conn_id) {

			return false;
		} else {

			return true;
		}
	}
	
	function login() {
	
		$this -> login = @ftp_login($this -> conn_id, $this -> user, $this -> pass);
		if (!$this -> login) {

			return false;
		} else {

			return true;
		}
	}
	
	function disconect() {

		$this -> disconect = ftp_close($this -> conn_id);
		if (!$this -> disconect) {

			return false;
		} else {

			return true;
		}
	}
	
	function getDIR ($dir = '/', $recursive = false) {


		$dir_list = ftp_rawlist($this -> conn_id, $dir, $recursive);
		if (is_array($dir_list)) {

			$dir_content['dirs'] = array();
			$dir_content['files'] = array();
			foreach ($dir_list as $file) {

				$file = preg_split("([\x20]+)", $file);
				if ($file[0][0] == 'd' || $file[0][0] == 'l') {

					if ($file[8] != '.' && $file[8] != '..')
					array_push($dir_content['dirs'], $file);
				} else {

					array_push($dir_content['files'], $file);
				}
			}
			$dir_list = $dir_content;
		} else {

			return false;
		}
		
		return $dir_list;
	}

	function getFilenameParts ($file) {

		$extPos = str_lpos($file, ".", 0);
		if ($extPos != -1) {
			$ext    = substr($file, $extPos + 1);
			$name   = substr($file, 0, $extPos);
		} else {

			return array('name' => $file, 'ext' => '');
		}
		
		return array('name' => $name, 'ext' => $ext);
	}

	/**
	* @return string
	* @param int $size
	* @param int $round
	* @desc Get the size of a file in bytes, Kb, Mb, Gb ...
	*/
	function convertSize($size, $round = 2) {

		(int)$size;
		(int)$round;
		$size_type     = array(0 => 'b', 1 => 'Kb', 2 => 'Mb', 3 => 'Gb');
		$size_type_pos = 0;

		while ($size >= 1024) {

			$size /= 1024;
			$size_type_pos++;
		}
		if (!array_key_exists($size_type_pos, $size_type)) {

			$size_end = '???';
		} else {

			$size_end = $size_type[$size_type_pos];
		}
		return round($size, $round) . ' ' . $size_end;
	}


	function makeDir ($dir) {

		if (!ftp_mkdir($this -> conn_id, $dir)) {

			return false;
		} else {

			return true;
		}
		
	}
}


?>