<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : files.class.php                             |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 28/07/2004 16:00                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

class files {

	
	/**
	* @return files
	* @param string $base_dir
	* @desc Constructor
	*/
	function files($base_dir = '') {

		$this -> base_dir = $base_dir;
	}

	/**
	* @return array
	* @param string $dir
	* @param bool $list
	* @desc Returns the files and folders in a directory if $list is false
	*       Returns an array with the files and directorys if $list is true
	*/
	function getDir ($dir = './', $list = false) {

		if ($dir == '')
		$dir = $this -> base_dir;

		$dir_content['dirs']  = array();
		$dir_content['files'] = array();
		$dir_list             = array();

		if (is_dir($dir)) {

			if ($dh = @opendir($dir)) {

				while (($file = readdir($dh)) !== false) {

					if ($file != '.' and $file != '..') {

						if (!$list) {

							if (is_dir($dir . '/' . $file)) {

								array_push($dir_content['dirs'], $file);
							} else {

								array_push($dir_content['files'], $file);
							}
						} else {

							array_push($dir_list, $file);
						}
					}
				}
				if (!$list) {

					return $dir_content;
				} else {

					return $dir_list;
				}
				@closedir($dh);

			} else {

				return false;
			}
		} else {

			return false;
		}
	}

	/**
	* @return array
	* @param string $file
	* @desc Get the name and extension of a file
	*/
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
	* @param string $file
	* @desc Get the file / directory permision
	*/
	function getAttributes ($file) {

		return substr(sprintf("%o", fileperms($file)), -3);
	}

	/**
	* @return int
	* @param string $file
	* @desc Return the size of a file or directory
	*/
	function getSize($file) {

		$size = 0;

		if (is_dir($file)) {

			$continut = $this -> getDir($file, true);
			foreach($continut as $part) {

				$size += $this -> getSize($file . '/' . $part);
			}
		} else {

			$size = @filesize($file);
		}

		return $size;

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

	/**
	* @return string
	* @param string $time
	* @param string $time_str
	* @desc Format timestamp
	*/
	function getDates ($time, $time_str = "d.m.y H:i:s") {

		return date($time_str, $time);
	}

	/**
	* @return bool
	* @param string $dir
	* @desc Deletes a file or a directory
	*/
	function delDIR ($dir) {

		$handle = opendir($dir);
		$success = false;
		while (false!==($dc = readdir($handle))) {

			if($dc != "." && $dc != "..") {

				if(is_dir($dir . '/' . $dc)) {
					@chmod($dir . '/' . $dc, 0777);
					$this -> delDIR($dir . '/' . $dc);
				} else {
					@chmod($dir . '/' . $dc, 0777);
					unlink($dir . '/' . $dc);
				}
			}
		}

		closedir($handle);
		if( @rmdir($dir) ) {
			$success = true;
		}
		return $success;
	}

	/**
	* @return bool
	* @param string $source
	* @param string $destination
	* @param bool $overwrite
	* @desc Copy a file or directory
	*/
	function doCopy ($source, $destination, $overwrite = true) {

		if (is_dir($source)) {

			$curr_dir = $this -> getDir($source, true);

			if(!file_exists($destination)) {

				if(!@mkdir($destination, 0777)) {

					return false;
				}
			}

			$dc = $this -> getDir($source, true);

			foreach ($dc as $f) {

				$this -> doCopy($source . '/' . $f, $destination . '/' . $f, $overwrite);
			}
			return true;

		} else {

			if (!$overwrite) {

				if(file_exists($destination)) {

					return true;
				}
			}
			if (@copy($source, $destination)) {

				return true;
			} else {

				return false;
			}
		}
	}

	/**
	* @return bool / array
	* @param array $source
	* @param array $to
	* @desc CHMOD files or directorys
	*/
	function doCHMOD($source, $to) {

		$cmerr = array();
		$cs = count($source);
		$tc = count($to);
		if ($to[count($to)-1] == '%all%') {

			array_pop($to);
		}

		if (is_array($source)) {

			if ($cs != $tc) {

				for ($i=$tc-1; $i < $cs; $i++) {

					$to[$i] = $to[$tc-2];
				}
			}
			$chmodto = $to;

			for ($i=0; $i<$cs; $i++) {

				if (!@chmod($source[$i], octdec($chmodto[$i]))) {

					array_push($cmerr, $source);
				}
			}

		} else {

			if (!chmod($source, octdec($to))) {

				array_push($cmerr, $source);
			}
		}

		return count($cmerr) ? $cmerr : true;
	}

	/**
	* @return void
	* @param string $oldname
	* @param string $newname
	* @desc rename a file / directory (uses doCHMOD to have rename access)
	*/
	function doRename ($oldname, $newname) {

		$old_perm = $this -> getAttributes($oldname);
		$this -> doCHMOD($oldname, 0777);
		@rename($oldname, $newname);
		$this -> doCHMOD($newname, octdec($old_perm));

	}

	/**
	* @return string
	* @param string $file
	* @param bool $plain
	* @desc Show the content of a file
	*/
	function showFile($file, $plain = false) {

		if ($plain && @file_exists($file)) {
		
			$fp = fopen($file, 'r');
			$continut = fread($fp, $this -> getSize($file));
			fclose($fp);
			return $continut;
		}

		if(@file_exists($file)) {

//			if (function_exists('mime_content_type')) {
//				
//				$mimes = array('text/plain', 'text/html');
//				if (in_array(mime_content_type($file), $mimes)) {
//
//					$continut = highlight_file($file, true);
//					return $continut;
//				}
//			}

			$ext = $this -> getFilenameParts($file);
			$ext = $ext['ext'];
			switch ($ext) {

				case 'txt'  :
				case 'html' :
				case 'htm'  :
				case 'tpl'  :
				case 'php'  :
				case 'php3' :
				case 'pl'   :
				case 'cgi'  :
				case 'asp'  :
				case 'css'  :
				case 'ini'  :
				case 'sql'  :
				case 'xml'  :
					$continut = highlight_file($file, true);
				break;
				case 'gif'  :
				case 'jpg'  :
				case 'jpeg' :
				case 'png'  :
				case 'bmp'  :
					$continut = '<img src="' . $file . '" alt="' . $file . '">';
					break;
				case 'swf'  :
					$continut = '
								<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" align="middle">
						            <param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="' . $file . '" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="transparent" />
									<param name="menu" value=false />
									<embed src="' . $file . '" quality="high" bgcolor="transparent" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
								</object>
								';
					break;
				default     :
					$continut = 'Can not display file !';
			}
			return $continut;
		} else {

			return 'File not found !';
		}
	}
	
	function archive ($mode, $file, $arch_type, $compr_level = 3) {

		$supp_arch_types = array('bzip');
		if (!in_array($arch_type, $supp_arch_types)) {

			return false;
		}
		if ($mode == 'read') {

			
		}
	}

}


?>