<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_zip.php,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Returns all files from the ZIP file if their extension matches the
// $extension array (do not include the dot in this array)
function zip_getfiles($zipfile, $extensions) {
	if (!is_array($extensions)) {
		$extensions = array($extensions);
	}

	$zip = new ZIP_Extract($zipfile);
	if (!is_array($list = $zip->listContent())) {
		return false;
	}

	foreach ($list as $key => $value) {
		if (array_contains(getextension($value['filename']), $extensions) and $value['folder'] == 0) {
			$zip->extractByIndex($value['index']);
			$out[$value['stored_filename']] = $GLOBALS[$value['stored_filename']];
		}
	}
	return $out;
}

// ############################################################################
// Extracts a given ZIP file
class ZIP_Extract {
	var $zipname = '';
	var $zip_fd = 0;

	function ZIP_Extract($p_zipname) {
		$this->zipname = $p_zipname;
		$this->zip_fd = 0;
		return;
	}

	function listContent() {
		$v_result = 1;
		if (!$this->privCheckFormat()) {
			return false;
		}

		$p_list = array();
		if (($v_result = $this->privList($p_list)) != 1) {
			unset($p_list);
			return false;
		}

		return $p_list;
	}

	function extractByIndex($p_index) {
		$v_result = 1;
		if (!$this->privCheckFormat()) {
			return false;
		}

		$v_options = array();
		$v_path = './';
		$v_remove_path = '';
		$v_remove_all_path = false;

		$v_size = func_num_args();
		if ($v_size > 1) {
			$v_arg_list = &func_get_args();
			array_shift($v_arg_list);
			$v_size--;
			if ((is_integer($v_arg_list[0])) and ($v_arg_list[0] > 77000)) {
				$v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
													array(
														ZIP_OPT_PATH => 'optional',
														ZIP_OPT_REMOVE_PATH => 'optional',
														ZIP_OPT_REMOVE_ALL_PATH => 'optional',
														ZIP_OPT_ADD_PATH => 'optional',
														ZIP_CB_PRE_EXTRACT => 'optional',
														ZIP_CB_POST_EXTRACT => 'optional',
														ZIP_OPT_SET_CHMOD => 'optional',
													));
				if ($v_result != 1) {
					return false;
				}

				if (isset($v_options[ZIP_OPT_PATH])) {
					$v_path = $v_options[ZIP_OPT_PATH];
				}
				if (isset($v_options[ZIP_OPT_REMOVE_PATH])) {
					$v_remove_path = $v_options[ZIP_OPT_REMOVE_PATH];
				}
				if (isset($v_options[ZIP_OPT_REMOVE_ALL_PATH])) {
					$v_remove_all_path = $v_options[ZIP_OPT_REMOVE_ALL_PATH];
				}
				if (isset($v_options[ZIP_OPT_ADD_PATH])) {
					if ((strlen($v_path) > 0) and (substr($v_path, -1) != '/')) {
						$v_path .= '/';
					}
					$v_path .= $v_options[ZIP_OPT_ADD_PATH];
				}
			} else {
				$v_path = $v_arg_list[0];
				if ($v_size == 2) {
					$v_remove_path = $v_arg_list[1];
				} elseif ($v_size > 2) {
					return false;
				}
			}
		}

		if (is_string($p_index) or is_integer($p_index)) {
			if (($v_result = $this->privExtractByIndex($p_list, (is_integer($p_index) ? "$p_index" : $p_index), $v_path, $v_remove_path, $v_remove_all_path, $v_options)) != 1) {
				return false;
			}
		} else {
			return false;
		}

		return $p_list;
	}


	function privCheckFormat($p_level = 0) {
		if (!is_file($this->zipname)) {
			return false;
		} elseif (!is_readable($this->zipname)) {
			return false;
		}
		return true;
	}

	function privParseOptions(&$p_options_list, $p_size, &$v_result_list, $v_requested_options) {
		$v_result = 1;
		for ($i = 0; $i < $p_size; $i++) {
			if (!isset($v_requested_options[$p_options_list[$i]])) {
				return false;
			}

			switch ($p_options_list[$i]) {
				case ZIP_OPT_PATH :
				case ZIP_OPT_REMOVE_PATH :
				case ZIP_OPT_ADD_PATH :
					if (($i + 1) >= $p_size) {
						return false;
					}
					$v_result_list[$p_options_list[$i]] = strtr($p_options_list[$i+1], '\\', '/');
					$i++;
					break;

				case ZIP_OPT_REMOVE_ALL_PATH :
					$v_result_list[$p_options_list[$i]] = true;
					break;

				case ZIP_OPT_SET_CHMOD :
					if (($i + 1) >= $p_size) {
						return false;
					}
					$v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
					$i++;
					break;

				case ZIP_CB_PRE_EXTRACT :
				case ZIP_CB_POST_EXTRACT :
				case ZIP_CB_PRE_ADD :
				case ZIP_CB_POST_ADD :
					if (($i + 1) >= $p_size) {
						return false;
					}
					$v_function_name = $p_options_list[$i + 1];
					if (!function_exists($v_function_name)) {
						return false;
					}
					$v_result_list[$p_options_list[$i]] = $v_function_name;
					$i++;
					break;
				default :
					return false;
			}
		}

		for ($key = reset($v_requested_options); $key = key($v_requested_options); $key = next($v_requested_options)) {
			if ($v_requested_options[$key] == 'mandatory') {
				if (!isset($v_result_list[$key])) {
					return false;
				}
			}
		}
		return $v_result;
	}


	function privOpenFd($p_mode) {
		if ($this->zip_fd != 0) {
			return false;
		} elseif (($this->zip_fd = @fopen($this->zipname, $p_mode)) == 0) {
			return false;
		}
		return true;
	}

	function privCloseFd() {
		if ($this->zip_fd != 0) {
			@fclose($this->zip_fd);
		}
		$this->zip_fd = 0;
		return true;
	}


	function privList(&$p_list) {
		$v_result = 1;
		if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0) {
			return false;
		}
		$v_central_dir = array();
		if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1) {
			return $v_result;
		}
		@rewind($this->zip_fd);
		if (@fseek($this->zip_fd, $v_central_dir['offset'])) {
			return false;
		}
		for ($i = 0; $i < $v_central_dir['entries']; $i++) {
			if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1) {
				return $v_result;
			}
			$v_header['index'] = $i;
			$this->privConvertHeader2FileInfo($v_header, $p_list[$i]);
			unset($v_header);
		}
		$this->privCloseFd();
		return $v_result;
	}

	function privConvertHeader2FileInfo($p_header, &$p_info) {
		$p_info['filename'] = $p_header['filename'];
		$p_info['stored_filename'] = $p_header['stored_filename'];
		$p_info['size'] = $p_header['size'];
		$p_info['compressed_size'] = $p_header['compressed_size'];
		$p_info['mtime'] = $p_header['mtime'];
		$p_info['comment'] = $p_header['comment'];
		$p_info['folder'] = ($p_header['external'] == 0x41FF0010);
		$p_info['index'] = $p_header['index'];
		$p_info['status'] = $p_header['status'];
		return true;
	}

	function privExtract(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options) {
		$v_result = 1;
		if (empty($p_path) or ((substr($p_path, 0, 1) != '/') and (substr($p_path, 0, 3) != '../'))) {
			$p_path = './'.$p_path;
		}
		if (($p_path != './') and ($p_path != '/')) {
			while (substr($p_path, -1) == '/') {
				$p_path = substr($p_path, 0, strlen($p_path) - 1);
			}
		}
		if (!empty($p_remove_path) and (substr($p_remove_path, -1) != '/')) {
			$p_remove_path .= '/';
		}
		$p_remove_path_size = strlen($p_remove_path);

		if (($v_result = $this->privOpenFd('rb')) != 1) {
			return $v_result;
		}

		$v_central_dir = array();
		if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1) {
			$this->privCloseFd();
			return $v_result;
		}

		$v_pos_entry = $v_central_dir['offset'];
		for ($i = 0; $i<$v_central_dir['entries']; $i++) {
			@rewind($this->zip_fd);
			if (@fseek($this->zip_fd, $v_pos_entry)) {
				$this->privCloseFd();
				return false;
			}
			$v_header = array();
			if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1) {
				$this->privCloseFd();
				return $v_result;
			}

			$v_header['index'] = $i;
			$v_pos_entry = ftell($this->zip_fd);
			@rewind($this->zip_fd);
			if (@fseek($this->zip_fd, $v_header['offset'])) {
				$this->privCloseFd();
				return false;
			}
			if (($v_result = $this->privExtractFile($v_header, $p_path, $p_remove_path, $p_remove_all_path, $p_options)) != 1) {
				$this->privCloseFd();
				return $v_result;
			}
			if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$i])) != 1) {
				$this->privCloseFd();
				return $v_result;
			}
		}

		$this->privCloseFd();
		return $v_result;
	}

	function privExtractByIndex(&$p_file_list, $p_index, $p_path, $p_remove_path, $p_remove_all_path, &$p_options) {
		$v_result = 1;
		if (empty($p_path) or ((substr($p_path, 0, 1) != '/') and (substr($p_path, 0, 3) != '../'))) {
			$p_path = './'.$p_path;
		}
		if (($p_path != './') and ($p_path != '/')) {
			while (substr($p_path, -1) == '/') {
				$p_path = substr($p_path, 0, strlen($p_path) - 1);
			}
		}
		if (!empty($p_remove_path) and (substr($p_remove_path, -1) != '/')) {
			$p_remove_path .= '/';
		}
		$p_remove_path_size = strlen($p_remove_path);
		if (($v_result = $this->privOpenFd('rb')) != 1) {
			return $v_result;
		}

		$v_central_dir = array();
		if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1) {
			$this->privCloseFd();
			return $v_result;
		}

		$p_index = strtr($p_index, ' ', '');
		$v_index_list = explode(',', $p_index);
		$v_pos_entry = $v_central_dir['offset'];
		for ($i = 0, $j_start = 0, $v_nb_extracted = 0; ($i < $v_central_dir['entries']) and ($j_start < sizeof($v_index_list)); $i++) {
			@rewind($this->zip_fd);
			if (@fseek($this->zip_fd, $v_pos_entry)) {
				$this->privCloseFd();
				return false;
			}

			$v_header = array();
			if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1) {
				$this->privCloseFd();
				return $v_result;
			}

			$v_header['index'] = $i;
			$v_pos_entry = ftell($this->zip_fd);
			$v_extract = false;
			for ($j = $j_start; ($j < sizeof($v_index_list)) and (!$v_extract); $j++) {
				$v_item_list = explode('-', $v_index_list[$j]);
				$v_size_item_list = sizeof($v_item_list);
				if ($v_size_item_list == 1) {
					if ($i == $v_item_list[0]) {
						$v_extract = true;
					}
					if ($i >= $v_item_list[0]) {
						$j_start = $j + 1;
					}
				} elseif ($v_size_item_list == 2) {
					if (($i >= $v_item_list[0]) and ($i <= $v_item_list[1])) {
						$v_extract = true;
					}
					if ($i >= $v_item_list[1]) {
					$j_start = $j + 1;
					}
				}
				if ($v_item_list[0] > $i) {
					break;
				}
			}

			if ($v_extract) {
				@rewind($this->zip_fd);
				if (@fseek($this->zip_fd, $v_header['offset'])) {
					$this->privCloseFd();
					return false;
				}
				$v_header['filename'] = basename($v_header['filename']);
				if (isset($GLOBALS['un'])) {
					$v_header['filename'] = $GLOBALS['un'];
				}

				if (($v_result = $this->privExtractFile($v_header, $p_path, $p_remove_path, $p_remove_all_path, $p_options)) != 1) {
					$this->privCloseFd();
					return $v_result;
				}

				if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1) {
					$this->privCloseFd();
					return $v_result;
				}
			}
		}

		$this->privCloseFd();
		return $v_result;
	}

	function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options) {
		$v_result = 1;
		if (($v_result = $this->privReadFileHeader($v_header)) != 1) {
			return $v_result;
		}

		if ($p_remove_all_path) {
			$p_entry['filename'] = basename($p_entry['filename']);
		} elseif (!empty($p_remove_path)) {
			if ($this->PclZipUtilPathInclusion($p_remove_path, $p_entry['filename']) == 2) {
				$p_entry['status'] = 'filtered';
				return $v_result;
			}
			$p_remove_path_size = strlen($p_remove_path);
			if (substr($p_entry['filename'], 0, $p_remove_path_size) == $p_remove_path) {
				$p_entry['filename'] = substr($p_entry['filename'], $p_remove_path_size);
			}
		}

		if (!empty($p_path)) {
			$p_entry['filename'] = $p_path.'/'.$p_entry['filename'];
		}

		if (isset($p_options[ZIP_CB_PRE_EXTRACT])) {
			$v_local_header = array();
			$this->privConvertHeader2FileInfo($p_entry, $v_local_header);
			eval('$v_result = '.$p_options[ZIP_CB_PRE_EXTRACT].'(ZIP_CB_PRE_EXTRACT, $v_local_header);');
			if ($v_result == 0) {
				$p_entry['status'] = 'skipped';
			}
			$p_entry['filename'] = $v_local_header['filename'];
		}

		if ($p_entry['status'] == 'ok') {
			if (!($p_entry['external'] == 0x41FF0010)) {
				if ($p_entry['compressed_size'] == $p_entry['size']) {
					$GLOBALS[$p_entry['stored_filename']] = '';
					$v_size = $p_entry['compressed_size'];
					while ($v_size != 0) {
						$v_read_size = ($v_size < ZIP_READ_BLOCK_SIZE ? $v_size : ZIP_READ_BLOCK_SIZE);
						$v_buffer = fread($this->zip_fd, $v_read_size);
						$v_binary_data = pack('a'.$v_read_size, $v_buffer);
						$GLOBALS[$p_entry['stored_filename']] .= $v_binary_data;
						$v_size -= $v_read_size;
					}
				} else {
					if (($v_dest_file = @fopen($p_entry['filename'].'.gz', 'wb')) == 0) {
						$p_entry['status'] = 'write_error';
						return $v_result;
					}

					$v_binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($p_entry['compression']), Chr(0x00), time(), Chr(0x00), Chr(3));
					fwrite($v_dest_file, $v_binary_data, 10);
					$v_size = $p_entry['compressed_size'];
					while ($v_size != 0) {
						$v_read_size = ($v_size < ZIP_READ_BLOCK_SIZE ? $v_size : ZIP_READ_BLOCK_SIZE);
						$v_buffer = fread($this->zip_fd, $v_read_size);
						$v_binary_data = pack('a'.$v_read_size, $v_buffer);
						@fwrite($v_dest_file, $v_binary_data, $v_read_size);
						$v_size -= $v_read_size;
					}
					$v_binary_data = pack('VV', $p_entry['crc'], $p_entry['size']);
					fwrite($v_dest_file, $v_binary_data, 8);
					fclose($v_dest_file);
					if (($v_src_file = gzopen($p_entry['filename'].'.gz', 'rb')) == 0) {
						$p_entry['status'] = 'read_error';
						return $v_result;
					}

					$GLOBALS[$p_entry['stored_filename']] = '';
					$v_size = $p_entry['size'];
					while ($v_size != 0) {
						$v_read_size = ($v_size < ZIP_READ_BLOCK_SIZE ? $v_size : ZIP_READ_BLOCK_SIZE);
						$v_buffer = gzread($v_src_file, $v_read_size);
						$v_binary_data = pack('a'.$v_read_size, $v_buffer);
						$GLOBALS[$p_entry['stored_filename']] .= $v_binary_data;
						$v_size -= $v_read_size;
					}
					gzclose($v_src_file);
					@unlink($p_entry['filename'].'.gz');
				}
			}
		}

		if (isset($p_options[ZIP_CB_POST_EXTRACT])) {
			$v_local_header = array();
			$this->privConvertHeader2FileInfo($p_entry, $v_local_header);
			eval('$v_result = '.$p_options[ZIP_CB_POST_EXTRACT].'(ZIP_CB_POST_EXTRACT, $v_local_header);');
		}
		return $v_result;
	}

	function privReadFileHeader(&$p_header) {
		$v_result = 1;
		$v_binary_data = @fread($this->zip_fd, 4);
		$v_data = unpack('Vid', $v_binary_data);

		if ($v_data['id'] != 0x04034b50) {
			return false;
		}

		$v_binary_data = fread($this->zip_fd, 26);
		if (strlen($v_binary_data) != 26) {
			$p_header['filename'] = '';
			$p_header['status'] = 'invalid_header';
			return false;
		}

		$v_data = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $v_binary_data);
		$p_header['filename'] = fread($this->zip_fd, $v_data['filename_len']);
		if ($v_data['extra_len'] != 0) {
			$p_header['extra'] = fread($this->zip_fd, $v_data['extra_len']);
		} else {
			$p_header['extra'] = '';
		}
		$p_header['compression'] = $v_data['compression'];
		$p_header['size'] = $v_data['size'];
		$p_header['compressed_size'] = $v_data['compressed_size'];
		$p_header['crc'] = $v_data['crc'];
		$p_header['flag'] = $v_data['flag'];
		$p_header['mdate'] = $v_data['mdate'];
		$p_header['mtime'] = $v_data['mtime'];
		$p_header['mtime'] = time();
		$p_header['stored_filename'] = $p_header['filename'];
		$p_header['status'] = 'ok';
		return $v_result;
	}

	function privReadCentralFileHeader(&$p_header) {
		$v_binary_data = @fread($this->zip_fd, 4);
		$v_data = unpack('Vid', $v_binary_data);
		if ($v_data['id'] != 0x02014b50) {
			return false;
		}

		$v_binary_data = fread($this->zip_fd, 42);
		if (strlen($v_binary_data) != 42) {
			$p_header['filename'] = '';
			$p_header['status'] = 'invalid_header';
			return false;
		}

		$p_header = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $v_binary_data);
		if ($p_header['filename_len'] != 0) {
			$p_header['filename'] = fread($this->zip_fd, $p_header['filename_len']);
		} else {
			$p_header['filename'] = '';
		}

		if ($p_header['extra_len'] != 0) {
			$p_header['extra'] = fread($this->zip_fd, $p_header['extra_len']);
		} else {
			$p_header['extra'] = '';
		}

		if ($p_header['comment_len'] != 0) {
			$p_header['comment'] = fread($this->zip_fd, $p_header['comment_len']);
		} else {
			$p_header['comment'] = '';
		}
		$p_header['mtime'] = time();
		$p_header['stored_filename'] = $p_header['filename'];
		$p_header['status'] = 'ok';
		if (substr($p_header['filename'], -1) == '/') {
			$p_header['external'] = 0x41FF0010;
		}

		return true;
	}

	function privReadEndCentralDir(&$p_central_dir) {
		$v_size = filesize($this->zipname);
		@fseek($this->zip_fd, $v_size);
		if (@ftell($this->zip_fd) != $v_size) {
			return false;
		}

		$v_found = 0;
		if ($v_size > 26) {
			@fseek($this->zip_fd, $v_size-22);
			if (($v_pos = @ftell($this->zip_fd)) != ($v_size - 22)) {
				return false;
			}

			$v_binary_data = @fread($this->zip_fd, 4);
			$v_data = unpack('Vid', $v_binary_data);
			if ($v_data['id'] == 0x06054b50) {
				$v_found = 1;
			}

			$v_pos = ftell($this->zip_fd);
		}

		if (!$v_found) {
			$v_maximum_size = 65557;
			if ($v_maximum_size > $v_size) {
				$v_maximum_size = $v_size;
			}
			@fseek($this->zip_fd, $v_size-$v_maximum_size);
			if (@ftell($this->zip_fd) != ($v_size - $v_maximum_size)) {
				return false;
			}

			$v_pos = ftell($this->zip_fd);
			$v_bytes = 0x00000000;
			while ($v_pos < $v_size) {
				$v_byte = @fread($this->zip_fd, 1);
				$v_bytes = ($v_bytes << 8) | Ord($v_byte);
				$v_pos++;
				if ($v_bytes == 0x504b0506) {
					break;
				}
			}

			if ($v_pos == $v_size) {
				return false;
			}
		}

		$v_binary_data = fread($this->zip_fd, 18);
		if (strlen($v_binary_data) != 18) {
			return false;
		}

		$v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);
		if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {
			return false;
		}

		if ($v_data['comment_size'] != 0) {
			$p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
		} else {
			$p_central_dir['comment'] = '';
		}
		$p_central_dir['entries'] = $v_data['entries'];
		$p_central_dir['disk_entries'] = $v_data['disk_entries'];
		$p_central_dir['offset'] = $v_data['offset'];
		$p_central_dir['size'] = $v_data['size'];
		$p_central_dir['disk'] = $v_data['disk'];
		$p_central_dir['disk_start'] = $v_data['disk_start'];
		return true;
	}

	function PclZipUtilPathInclusion($p_dir, $p_path) {
		$v_result = 1;
		$v_list_dir = explode('/', $p_dir);
		$v_list_dir_size = sizeof($v_list_dir);
		$v_list_path = explode('/', $p_path);
		$v_list_path_size = sizeof($v_list_path);
		$i = 0;
		$j = 0;
		while (($i < $v_list_dir_size) and ($j < $v_list_path_size) and ($v_result)) {
			if (empty($v_list_dir[$i])) {
				$i++;
				continue;
			} elseif (empty($v_list_path[$j])) {
				$j++;
				continue;
			} elseif ($v_list_dir[$i] != $v_list_path[$j] and !empty($v_list_dir[$i]) and !empty($v_list_path[$j]))  {
				$v_result = 0;
			}
			$i++;
			$j++;
		}

		if ($v_result) {
			while (empty($v_list_path[$j]) and ($j < $v_list_path_size)) $j++;
			while (empty($v_list_dir[$i]) and ($i < $v_list_dir_size)) $i++;
			if (($i >= $v_list_dir_size) and ($j >= $v_list_path_size)) {
				$v_result = 2;
			} elseif ($i < $v_list_dir_size) {
				$v_result = 0;
			}
		}

		return $v_result;
	}
}

?>