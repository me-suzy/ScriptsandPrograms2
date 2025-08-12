<?php
class ClientUpdate {
	
	var $s_server_message = "";
	var $error = 0;
	var $errorMessage = "";
	/**
	* returns the server URL for the update. this URL must be defined in "./file_list.txt"
	* @return string $s_server_filename http url to the update server of not existant an empty string is returned
	*/
	function getServerProperties() {
		$a_server = array();
		if (file_exists("./file_list.txt")) {
			$p_client_file = fopen("./file_list.txt", "r") ;
			$row = trim(chop(fgets($p_client_file, 1024)));
			$a_zeile = explode(";", $row);
			if (isset($a_zeile[1])) $a_server['url'] = $a_zeile[1];
			if (isset($a_zeile[0])) $a_server['system_code'] = $a_zeile[0];
			if (isset($a_zeile[2])) $a_server['version'] = $a_zeile[2];
			if (isset($a_zeile[3])) $a_server['download_cycle'] = $a_zeile[3];
			if (isset($a_zeile[4])) $a_server['security'] = $a_zeile[4];
			fclose($p_client_file);
			return $a_server;
		} else return $a_server;
	}// end func getServerURL
	
	/**
	* returns the server properties for the last download an an assoziative array
	* @return string $arrProperties properties of the last download
	*/
	function getLastDownload() {
		$arrProperties = array();
		if (file_exists("./last_download.log")) {
			$p_client_file = fopen("./last_download.log", "r") ;
			$row = trim(chop(fgets($p_client_file, 1024)));
			$a_zeile = explode(";", $row);
			if (isset($a_zeile[0])) $arrProperties['last_download_time'] = $a_zeile[0];
			return $arrProperties;
		} else return $arrProperties;
	}// end func getServerURL
	
	/**
	* checks wether the actual version of the updater is up to date
	* compares the md5 filecode of each updater file with the md5 code in the 'updater_list.txt' file
	*
	*/
	function isNewUpdaterVersion() {
		$a_files = $this->getFileList("updater_list.txt");
		for ($i=0; $i<count($a_files); $i++) {
			if ($s_filehash = $this->getHashForFile("online_update/".$a_files[$i]['filepath'])) {
				if (trim($s_filehash) != trim($a_files[$i]['filehash'])) {
					$i = count($a_files);
					return true;
				}
			} else {
				$i = count($a_files);
				return true;
			}
		}
		return false;
	}// end func isNewUpdaterVersion
	
	/**
	* overwrites the old file listfile with the new one from the server.
	* @param string $s_serverurl url where the new listfile is located
	* @return boolean $b_success returns 'true' if file is updated 'false' if not
	*/
	function getUpdaterListFile($s_serverurl) {
		$b_success = true;
		$this->s_server_message = "";
		if (file_exists("./updater_list_tmp.txt")) unlink("./updater_list_tmp.txt");
		if (file_exists("./updater_list.txt")) copy("./updater_list.txt", "./updater_list_tmp.txt");
		if ($server_file = @fopen($s_serverurl."?mode=get_list&system_code=000_client", "r")) {
			$p_tempfile = fopen("./updater_list_tmp.txt", "w") ;
			while (!feof($server_file) and $b_success) {
			    $buffer = chop(fgets($server_file, 1024));
		    	if ((trim($buffer) == "<?project does not exist!?>") OR (trim($buffer) == "<?file does not exist!?>")) {
					$b_success = false;
					$this->s_server_message = chop(fgets($server_file, 1024));
				}
				else fputs($p_tempfile, $buffer."\n");
			}
			fclose($server_file);
			fclose($p_tempfile);
		} else {
			$b_success = false;
			$this->s_server_message = "Can not open server list file!";
		}
		if ($b_success) {
			if (file_exists("./updater_list.txt")) unlink("./updater_list.txt");
			rename("./updater_list_tmp.txt", "./updater_list.txt");
			@chmod ("./updater_list.txt", 0777);
		} else {
			if (file_exists("./updater_list_tmp.txt")) unlink("./updater_list_tmp.txt");
		}
		return $b_success;
	}// end func getUpdaterListFile
	
	/**
	* overwrites the old file listfile with the new one from the server.
	* @param string $s_serverurl url where the new listfile is located
	* @return boolean $b_success returns 'true' if file is updated 'false' if not
	*/
	function getNewListFile($s_serverurl, $s_system_code) {
		$b_success = true;
		$this->s_server_message = "";
		if (file_exists("./file_list_tmp.txt")) unlink("./file_list_tmp.txt");
		if (file_exists("./file_list.txt")) copy("./file_list.txt", "./file_list_tmp.txt");
		if ($server_file = @fopen($s_serverurl."?mode=get_list&system_code=".$s_system_code."", "r")) {
			$p_tempfile = fopen("./file_list_tmp.txt", "w") ;
			while (!feof($server_file) and $b_success) {
			    $buffer = chop(fgets($server_file, 1024));
		    	if ((trim($buffer) == "<?project does not exist!?>") OR (trim($buffer) == "<?file does not exist!?>")) {
					$b_success = false;
					$this->s_server_message = chop(fgets($server_file, 1024));
				}
				else fputs($p_tempfile, $buffer."\n");
			}
			fclose($server_file);
			fclose($p_tempfile);
		} else {
			$b_success = false;
			$this->s_server_message = "Can not open server list file!";
		}
		if ($b_success) {
			unlink("./file_list.txt");
			rename("./file_list_tmp.txt", "./file_list.txt");
			@chmod ("./file_list.txt", 0777);
		} else {
			if (file_exists("./file_list_tmp.txt")) unlink("./file_list_tmp.txt");
		}
		return $b_success;
	}// end func getNewListFile

	/**
	* overwrites the old readme file with the new one from the server.
	* @param string $s_serverurl url where the new listfile is located
	* @return boolean $b_success returns 'true' if file is updated 'false' if not
	*/
	function getNewReadmeFile($s_serverurl, $s_system_code) {
		$b_success = true;
		$this->s_server_message = "";
		if (file_exists("./readme_tmp.txt")) unlink("./readme_tmp.txt");
		if (file_exists("./readme.txt")) copy("./readme.txt", "./readme_tmp.txt");
		if ($server_file = @fopen($s_serverurl."?mode=get_readme&system_code=".$s_system_code."", "r")) {
			$p_tempfile = fopen("./readme_tmp.txt", "w") ;
			while (!feof($server_file) and $b_success) {
			    $buffer = chop(fgets($server_file, 1024));
		    	if ((trim($buffer) == "<?project does not exist!?>") OR (trim($buffer) == "<?file does not exist!?>")) {
					$b_success = false;
					$this->s_server_message = chop(fgets($server_file, 1024));
				}
				else fputs($p_tempfile, $buffer."\n");
			}
			fclose($server_file);
			fclose($p_tempfile);
		} else {
			$b_success = false;
			$this->s_server_message = "Can not open server list file!";
		}
		if ($b_success) {
			if (file_exists("./readme.txt")) unlink("./readme.txt");
			rename("./readme_tmp.txt", "./readme.txt");
			@chmod ("./readme.txt", 0777);
		} else {
			if (file_exists("./readme_tmp.txt")) unlink("./readme_tmp.txt");
		}
		return $b_success;
	}// end func getNewReadmeFile

	
	/**
	* gets the whole list of files for the updater himself
	*/
	function getUpdaterFileList($s_filename = "./updater_list.txt") {
		$a_files = array();
		if (file_exists($s_filename)) {
			$a_rows = file($s_filename);
			for ($i=1; $i<count($a_rows); $i++) {
				$counter = count($a_files);
				if (trim($a_rows[$i]) != "") {
					$a_zeile = explode(";", $a_rows[$i]);
					$a_files[$counter]['filecode'] = $a_zeile[0];
					$a_files[$counter]['filepath'] = $a_zeile[1];
					$a_files[$counter]['filehash'] = $a_zeile[2];
					$a_files[$counter]['filesize'] = 0;
					if (isset($a_zeile[3])) $a_files[$counter]['filesize'] = $a_zeile[3];
				}
			}
		}
		return $a_files;
	}// end func getUpdaterFileList

	/**
	* gets the whole list of files for the update
	*/
	function getFileList($s_filename = "./file_list.txt") {
		$a_files = array();
		if (file_exists($s_filename)) {
			$a_rows = file($s_filename);
			for ($i=1; $i<count($a_rows); $i++) {
				$counter = count($a_files);
				if (trim($a_rows[$i]) != "") {
					$a_zeile = explode(";", $a_rows[$i]);
					$a_files[$counter]['filecode'] = $a_zeile[0];
					$a_files[$counter]['filepath'] = $a_zeile[1];
					$a_files[$counter]['filehash'] = $a_zeile[2];
					$a_files[$counter]['filesize'] = 0;
					if (isset($a_zeile[3])) $a_files[$counter]['filesize'] = $a_zeile[3];
				}
			}
		}
		return $a_files;
	}// end func getFileList

	/**
	* gets all files to update with differencing hashcodes
	* @param string $strTargetPath path to the download directory for the files
	*/
	function getFileListForUpdate($strTargetPath = "../") {
		$arrFiles = $this->getFileList();
		$arrUpdateFiles = array();
		for ($i=0; $i<count($arrFiles); $i++) {
			if ($arrFiles[$i]['filehash'] != trim($this->getHashForFile($arrFiles[$i]['filepath'], $strTargetPath)) )
				$arrUpdateFiles[] = $arrFiles[$i];
		}
		return $arrUpdateFiles;
	}// end func getFileListForUpdate

	/**
	* downloads all  files that differ to the ones on the server
	* take attention tha you run the method getNewListFile() before
	* @param string $strTargetPath path to the download directory for the files
	*/
	function getAllFilesForUpdate($strTargetPath = "../", $strUsername = "", $strPassword = "") {
		$p_logfile = fopen ("./update.log", "w+");
		$a_serverproperties =  $this->getServerProperties();
		$arrFiles = $this->getFileListForUpdate($strTargetPath);
		for ($i=0; $i<count($arrFiles); $i++) {
			$s_success = $this->downloadFile($a_serverproperties['url'], $a_serverproperties['system_code'], $arrFiles[$i], $strTargetPath);
			fputs($p_logfile, "updating " . $arrFiles[$i]['filepath']." ... ");
			if ($s_success) {
				fputs($p_logfile, "done!\n");
			} else {
				fputs($p_logfile, " file not available!\n");
			}
		}
		fclose($p_logfile);
		$p_logfile = fopen ("./last_download.log", "w+");
		fputs($p_logfile, time());
		fclose($p_logfile);
		return $arrFiles;
	}// end func getAllFilesForUpdate



	/**
	* downloads all  files that differ to the ones on the server
	* @param string $strTargetPath path to the download directory for the files
	*/
	function getCycleUpdate($strTargetPath = "../", $strUsername = "", $strPassword = "") {
		$arrFiles = array();
		$arrServerProps = $this->getServerProperties();
		$arrDownloadProps = $this->getLastDownload();
		$this->getNewListFile($arrServerProps['url'], $arrServerProps['system_code']);
		if (isset($arrServerProps['download_cycle']) AND $arrServerProps['download_cycle'] == 0) {
			$arrFiles = $this->getAllFilesForUpdate($strTargetPath);
		} elseif (isset($arrServerProps['download_cycle']) AND ( time()-$arrServerProps['download_cycle'] > $arrDownloadProps['last_download_time'] ) ) {
			$arrFiles = $this->getAllFilesForUpdate($strTargetPath);
		}
		return $arrFiles;
	}// end func getCycleUpdate
	
	/**
	* download the file for the update and replaces it in the file system
	* @param string $s_serverurl url where the new listfile is located
	* @return boolean $b_success returns 'true' if file is updated 'false' if not
	*/
	function downloadFile($s_serverurl, $s_system_code, $a_file, $s_rel_path = "../", $strUsername = "", $strPassword = "") {
		$this->error = 0;
		$this->errorMessage = "";
		$b_success = true;
		//checken, ob es nicht eine Datei des Updaters selber ist
		if ( ($s_system_code != "000_client") AND strstr($a_file['filepath'], "online_update")) {
			$this->error = 0;
			$this->errorMessage = "File of the Updater himself!";
			$b_success = false;
		}
		if ($b_success) {
			if (file_exists($s_rel_path.$a_file['filepath'].".tmp")) unlink($s_rel_path.$a_file['filepath'].".tmp");
			if ($server_file = @fopen($s_serverurl."?mode=get_file&system_code=".$s_system_code."&username=".$strUsername."&password=".$strPassword."&file_code=".$a_file['filecode'], "rb")) {
				$tmp_dir = dirname($s_rel_path.$a_file['filepath'].".tmp");
				$a_dirs = explode("/", $tmp_dir);
				$s_dirs = "";
				for ($i=0; $i<count($a_dirs); $i++) {
					$s_dirs .= $a_dirs[$i]."/";
					if (!is_dir($s_dirs))  {
						mkdir($s_dirs, 0777);
						chmod($s_dirs, 0777);
					}
				}
				$tmp_filename = basename($s_rel_path.$a_file['filepath'].".tmp");
				$p_tempfile = fopen($tmp_dir."/".$tmp_filename, "wb") ;
				while (!feof($server_file) AND $b_success) {
					$buffer = fread ($server_file, 1024);
					if (substr(trim($buffer), 0, strlen("<?file does not exist!?>")) == "<?file does not exist!?>") $b_success = false;
				    elseif (substr(trim($buffer), 0, strlen("<?secure access denied!?>")) == "<?secure access denied!?>") {
						$b_success = false;
						$this->error = 1;
						$this->errorMessage = "Username or Password incorrect!";
					} else fwrite($p_tempfile,$buffer);
				}
				fclose($server_file);
				fclose($p_tempfile);
				@chmod ($tmp_dir."/".$tmp_filename, 0777);
			} else $b_success = false;
			if (!$b_success) {
				if (file_exists($s_rel_path.$a_file['filepath'].".tmp")) unlink($s_rel_path.$a_file['filepath'].".tmp");
			} else {
				if (file_exists($s_rel_path.$a_file['filepath'])) unlink($s_rel_path.$a_file['filepath']);
				rename($s_rel_path.$a_file['filepath'].".tmp", $s_rel_path.$a_file['filepath']);
				$p_logfile = fopen ("./last_download.log", "w+");
				fputs($p_logfile, time());
				fclose($p_logfile);
			}
		} // if ($b_success)
		return $b_success;
	}// end func downloadFile

	/**
	* returns the md5 hashcode for the given file content
	* @param string $s_file path to the file for the hash
	* @return strinh $s_hash hashcode for the file content. returns false if file does not exist
	*/
	function getHashForFile($s_file, $s_rel_path = "../") {
		if (file_exists($s_rel_path.$s_file)) {
			//$s_hash = md5_file($s_file);
			$fd = fopen($s_rel_path.$s_file, 'rb');
			$fileContents = fread($fd, filesize($s_rel_path.$s_file));
			fclose ($fd);
			$s_hash = md5($fileContents);
			return $s_hash;
		} else return false;
	}// end func getHashForFile

	/**
	* returns the requested info-line in 'project_info.php'
	* @param string $path 	relative path to project
	* @param string $info	which info is required
	* @return sring	$s_info	project information
	*/
	function getProjectInfo($path, $info) {
		$s_info = "";
		if (file_exists($path."./project_info.php")) {
			$a_infos = file($path."./project_info.php");
			for ($i=0; $i<count($a_infos); $i++) {
				$a_zeile = explode(";", $a_infos[$i]);
				if (trim($a_zeile[0]) == trim($info)) $s_info = $a_zeile[1];
			}
		}
		return $s_info;
	}
}

?>
