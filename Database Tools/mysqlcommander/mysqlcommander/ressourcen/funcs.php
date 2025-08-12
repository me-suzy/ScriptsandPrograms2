<?php 
Class Funcs {

	function Funcs() {
		$this->config = $GLOBALS["config"];
		$this->strLineFeedCode = $this->config->strLineFeedCode;
		$this->strCarriageReturnCode = $this->config->strCarriageReturnCode;
	}

	function get_databases() {
		global $config, $db;
		$selected_dbase = array();
		$dbase = $config->dbase;
		$dbase_ex = $config->dbase_ex;
		if ($dbase[0]=="") {
			$conn = $db->connection;
			$all_dbs = mysql_list_dbs($conn);
			$i = 0;
			while ($i < mysql_num_rows ($all_dbs)) {
			    $selected_dbase[$i] = mysql_tablename ($all_dbs, $i);
			    //echo "&nbsp;&nbsp;$selected_dbase[$i]<BR>\n";
			    $i++;
			}
		} else {
			for ($i=0; $i<count($dbase); $i++) {
				if ($dbase[$i]!="") $selected_dbase[] = $dbase[$i];
			}
		}
		if ($dbase_ex[0] != "") {
			for ($i=0; $i<count($dbase_ex); $i++) {
				if ($dbase_ex[$i]!="") $hdbase_ex[] = $dbase_ex[$i];
			}
			$dbase_ex = $hdbase_ex;
			for ($i=0; $i<count($selected_dbase); $i++) {
				if (!in_array($selected_dbase[$i], $dbase_ex)) $hselected_dbase[] = $selected_dbase[$i];
			}
			$selected_dbase = $hselected_dbase;
		}
		return $selected_dbase;
	}

	function get_tables($database) {
		$tb_names = array();
		$result = mysql_list_tables($database);
	    $number = @mysql_num_rows($result); 
		$i = 0;
		while ($i < $number) { 
	    	$tb_names[$i] = mysql_tablename ($result, $i);
		    //echo $tb_names[$i]."<br>\n";
			$i++; 
		}
		return $tb_names;
	}

	function get_table_structure($database, $table) {
		$tb_names = array();
		$result = mysql_list_tables($database);
	    $number = @mysql_num_rows($result); 
		$i = 0;
		while ($i < $number) { 
	    	$tb_names[$i] = mysql_tablename ($result, $i);
		    //echo $tb_names[$i]."<br>\n";
			$i++; 
		}
		return $tb_names;
	}

	function get_create_files($database, $strRelPath = "") {
		$help = array();
		$directory = $this->config->data_path.$database;
		if ($strRelPath) $directory = $strRelPath.$database;
		if (is_dir($directory)) {
			$d = dir($directory);
			while($entry=$d->read()) {
				if (substr($entry,0,1)<>".") {
					if (substr($entry,0,7)=="create_") {
					    $help[]=$entry;
					}
				}
			}
			$d->close();
		} // if is_dir
		return $help;
	}

	function get_files($database, $strRelPath = "") {
		$help = array();
		$directory = $this->config->data_path.$database;
		if ($strRelPath) $directory = $strRelPath.$database;
		if (is_dir($directory)) {
			$d = dir($directory);
			while($entry=$d->read()) {
				if (substr($entry,0,1)<>".") {
					if ( (substr($entry,0,7)<>"create_") AND ((substr($entry,-3)=="txt") OR (substr($entry,-2)=="gz")) ) {
					    $help[]=$entry;
					}
				}
			}
			$d->close();
		} // if is_dir
		return $help;
	}

	function getBlobFilesForTable($database, $tablename, $strRelPath = "") {
		$help = array();
		$directory = $this->config->data_path.$database . "/blobs";
		if ($strRelPath) $directory = $strRelPath.$database;
		if (@is_dir($directory)) {
			$d = dir($directory);
			while($entry=$d->read()) {
				if (substr($entry,0,1)<>".") {
					if (substr($entry,0,strlen($tablename)) == $tablename) {
					    $help[]=$entry;
					}
				}
			}
			$d->close();
		} // if is_dir
		return $help;
	}

	function get_all_files($database, $strRelPath = "") {
		$help = array();
		$directory = $this->config->data_path.$database;
		if ($strRelPath) $directory = $strRelPath.$database;
		if (is_dir($directory)) {
			$d = dir($directory);
			while($entry=$d->read()) {
				if (substr($entry,0,1)<>".") {
					$help[]=$entry;
				}
			}
			$d->close();
		} // if is_dir
		return $help;
	}

	function writeLog($dbs, $table, $success = true, $action = "default", $logText = "", $strLogpath = "") {
		if (!file_exists($strLogpath)) { 
			@mkdir($strLogpath, 0777); 
			@chmod($strLogpath, 0777);
		}	
		$strTime = date("H:i:s");
		$strDate = date("Y-m-d");
		if ($TXT=@fopen($strLogpath."/action.log", "a")) {
			if ($success) $strLogText = $strDate. " " . $strTime . " | " . $dbs . " | " . $table . " | " . $action . " | 1 | " . $logText;
			else $strLogText = $strDate. " " . $strTime . " | " . $dbs . " | " . $table . " | " . $action . " | 0 | " . $logText;
			fputs($TXT,$strLogText."\n");
			fclose($TXT);
			@chmod ($strLogpath."/action.log", 0666);
		}
	}
	
	function backup_def($dbs, $table, $superscribe, $strRelPath = "", $blnVersions = false) {
		global $db;
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$strFileVersion_time = date("His");
		$strFileVersion_date = date("Ymd");
		$res['email'] = "";
		$success = 0;
		if ($blnVersions) {
			if (!file_exists($strDatapath."/".$strFileVersion_date)) { @mkdir($strDatapath."/".$strFileVersion_date, 0777); @chmod($strDatapath."/".$strFileVersion_date, 0777);}
			$dateitest = $strDatapath."/".$strFileVersion_date."/"."create_".$table."_".$strFileVersion_time.".txt";   // Daten liegen in Verzeichnis "data"
		} else $dateitest = $strDatapath."/create_".$table.".txt";   // Daten liegen in Verzeichnis "data"
		if ((file_exists($dateitest)) and ($superscribe != "yes")) {
			$this->writeLog($dbs, $table, false, "backup_def", $this->text("Datei \"$dateitest\" existiert und wird nicht &uuml;berschrieben", "File \"$dateitest\" exists and wont be overwriten !"), $strDatapath);
			$res['error'] = $this->text("Datei \"$dateitest\" existiert und wird nicht &uuml;berschrieben", "File \"$dateitest\" exists and wont be overwriten !");
		} else {
			$conn = $db->connection;
			$sql = "SHOW CREATE TABLE `$dbs`.`$table`";
			$result = @mysql_query($sql, $conn);
			if (!$result) {
				$sql = "DESCRIBE `$dbs`.`$table`";
				$result = mysql_query($sql, $conn);
				if ($result) {
					$fieldnum=0;
					while ($row=mysql_fetch_row($result)) {
						$fieldnum++;
					}
					$result = mysql_query($sql, $conn);
					$sqltext = "CREATE TABLE `$table` \n(\n";
					$i=0;
					$sqlende = "";
					while ($row=mysql_fetch_row($result)) { 
					    $name  = $row[0];
						$type  = " ".$row[1];
						if ($row[2] == "") {$null = " NOT NULL";} else {$null = " NULL";}
						if ($row[4] == "") {$default = "";} else {$default = " DEFAULT '".$row[4]."'";}
						if ($row[5] == "") {$extra = "";} else {$extra = " ".$row[5];}
						$sqltext .= "\t".$name . $type . $null . $default . $extra;
						$i++;
						if ($i<$fieldnum) $sqltext .= ", \n";
					}  // while
					
					unset($pri_key);
					unset($mul_key);
					unset($mul_index_key);
					unset($uni_key);
					unset($uni_index_key);
					unset($full_key);
					unset($full_index_key);
					$sql = "SHOW KEYS FROM `$dbs`.`$table`";
					$key_result = mysql_query($sql, $conn);
					while ($row=mysql_fetch_row($key_result)) {
						$non_unique = $row[1];
						$key_name = $row[2];
						$column_name = $row[4];
						$fulltext = $row[9];
						if (ereg("PRIMARY", $key_name)) {
							$pri_key[] = $column_name;
							$pri_index_key[] = $key_name;
						} elseif ($non_unique) {
							if ($fulltext=="FULLTEXT") {
								$full_key[] = $column_name;
								$full_index_key[] = $key_name;
							} else {
								$mul_key[] =  $column_name;
								$mul_index_key[] = $key_name;
							}
						}
						elseif ((!$non_unique) and (!ereg("PRIMARY", $key_name))) {
							$uni_key[] =  $column_name;
							$uni_index_key[] = $key_name;
						}
					}
					
					// add primary key
					if (count($pri_key)>0) {
						$pri_text = " PRIMARY KEY (";
						for ($i=0; $i<count($pri_key); $i++) {
							$pri_text .= $pri_key[$i];
							if (($i+1)<count($pri_key)) $pri_text .= ", ";
						}
						$pri_text .= ")";
						$sqltext .= ", \n\t".trim($pri_text);
					}
					
					// add index
					if (count($mul_key)>0) {
						$mul_text = " KEY (";
						for ($i=0; $i<count($mul_key); $i++) {
							$mul_text .= $mul_key[$i];
							if (($i+1)<count($mul_key)) $mul_text .= ", ";
						}
						$mul_text .= ")";
						$sqltext .= ", \n\t".trim($mul_text);
					}
					
					// add fulltext
					if (count($full_key)>0) {
						$full_text = " FULLTEXT KEY (";
						for ($i=0; $i<count($full_key); $i++) {
							$full_text .= $full_key[$i];
							if (($i+1)<count($full_key)) $full_text .= ", ";
						}
						$full_text .= ")";
						$sqltext .= ", \n\t".trim($full_text);
					}
					
					// add unique
					if (count($uni_key)>0) {
						$uni_text = " UNIQUE KEY (";
						for ($i=0; $i<count($uni_key); $i++) {
							$uni_text .= $uni_key[$i];
							if (($i+1)<count($uni_key)) $uni_text .= ", ";
						}
						$uni_text .= ")";
						$sqltext .= ", \n\t".trim($uni_text);
					}
					$sqltext .= "\n)";
					$res['generated'] = "Generated: ".$sqltext;
				}
			} else {
				$row=mysql_fetch_row($result);
				$sqltext = $row[1];
				$res['generated'] = "Generated: ".$sqltext;
			}
			if (!file_exists($strDatapath)) { @mkdir($strDatapath, 0777); @chmod($strDatapath, 0777); }
			if (file_exists($strDatapath)) {
				if (!file_exists($strDatapath)) {
					mkdir($strDatapath, 0777);
					chmod($strDatapath, 0777);
					//echo "Create directory \"data/".$dbs."\"<br>";
				}
				if (isset($sqltext) AND $sqltext != "") {
					//echo "Open file ...<br>";
					if ($TXT=@fopen($dateitest, "w")) {
						//echo "Write data ...<br>";
						fputs($TXT,$sqltext);
						fclose($TXT);
						@chmod ($dateitest, 0777);
						$success = 1;
						$res['email'] = $dateitest;
						$this->writeLog($dbs, $table, true, "backup_def", $this->text("Datei \"$dateitest\" geschrieben", "File \"$dateitest\" written"), $strDatapath);
						//echo "Ready !<br><br>";
					} else {
						$success = 0;
						$this->writeLog($dbs, $table, false, "backup_def", $this->text("Keine Schreibrechte auf dem Verzeichnis.<br>PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben.", "No write access on the directory. PHP must have write permissions for the directory."), $strDatapath);
						$res['error'] = $this->text("Keine Schreibrechte auf dem Verzeichnis.<br>PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben.", "No write access on the directory. PHP must have write permissions for the directory.");
					}
				}  // if sqltext != ""
			} else {
				$success = 0;
				$res['error'] = $this->text("Keine Schreibrechte auf dem Verzeichnis. PHP kann das Verzeichnis \"".$strDatapath."\" nicht anlegen.<br>Schaue im Manual nach Abhilfe.", "No write access on the directory. Not possible to create the directory \"".$strDatapath."\".<br>Look in the manual for help.");
				$this->writeLog($dbs, $table, false, "backup_def", $this->text("Keine Schreibrechte auf dem Verzeichnis. PHP kann das Verzeichnis \"".$strDatapath."\" nicht anlegen.<br>Schaue im Manual nach Abhilfe.", "No write access on the directory. Not possible to create the directory \"".$strDatapath."\".<br>Look in the manual for help."), $strDatapath);
			}
		} //if fileexists
		$res['success'] = $success;
		return $res;
	}

	function backup_content($dbs, $table, $seperator, $superscribe, $gzipping, $blob_as_file, $strRelPath = "", $blnVersions = false, $strWhere = "") {
		global $db;
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$strWhere = trim($strWhere);
		$strFileVersion_time = date("His");
		$strFileVersion_date = date("Ymd");
		$res['email'] = "";
		$conn = $db->connection;
		$success = 0;
		//echo "Processing table $table ...<br>";
		if (!file_exists($strDatapath)) { @mkdir($strDatapath, 0777); @chmod($strDatapath, 0777); }
		if (file_exists($strDatapath)) {
			if (!file_exists($strDatapath)) {
				mkdir($strDatapath, 0777);
				chmod($strDatapath, 0777);
				//echo "Create directory \"data/".$dbs."\"<br>";
			}
			if ($blnVersions) {
				if (!file_exists($strDatapath."/".$strFileVersion_date)) { @mkdir($strDatapath."/".$strFileVersion_date, 0777); @chmod($strDatapath."/".$strFileVersion_date, 0777);}
				$dateitest = $strDatapath."/".$strFileVersion_date."/".$table."_".$strFileVersion_time.".txt";
			} else $dateitest = $strDatapath."/".$table.".txt";   // Data in folder "data"
			if ($gzipping=="yes") $dateitest .= ".gz";
			if ((file_exists($dateitest)) and ($superscribe != "yes")) {
				$this->writeLog($dbs, $table, false, "backup_content", $this->text("Datei \"$dateitest\" existiert und wird nicht &uuml;berschrieben", "File \"$dateitest\" exists and wont be overwriten !"), $strDatapath);
				$res['error'] = $this->text("Datei \"$dateitest\" existiert und wird nicht &uuml;berschrieben", "File \"$dateitest\" exists and wont be overwriten !");
			} else {
				$sql = "select count(*) from `$dbs`.`$table`";
				if ($strWhere != "") $sql .= " WHERE ".$strWhere;
				$anzahl_res = mysql_query($sql, $conn);
				if ($anzahl_res) {
					$row = mysql_fetch_row($anzahl_res);
					$number = $row[0];
				}
				//ermitteln der felder in der tabelle und deren typen
				$sql = "DESCRIBE `$dbs`.`$table`";
				$describe_res = mysql_query($sql, $conn);
				$fieldprops = array();
				while ($row = mysql_fetch_assoc($describe_res)) {
					$fieldprops[] = $row;
				}
				////////////////////////////////////////////////////
				if ($number > 0) {
					if ($seperator == "tab") $seperator = "\t";
					$sql = "SELECT * from `$dbs`.`$table`";
					if ($strWhere != "") $sql .= " WHERE ".$strWhere;
					$down_res = mysql_query($sql);
					$fields = mysql_num_fields($down_res);
					//echo "Open file ...<br>";
					if ($gzipping=="yes") {
						$TXT=@gzopen($dateitest, "wb9");
						$gzip_string = "(gzipping)";
					} else {
						$TXT=@fopen($dateitest, "w");
						$gzip_string = "";
					}
					if ($TXT) {
						$i = 1;
						$a_blobfiles = $this->getBlobFilesForTable($dbs, $table, $strRelPath);
						if (!$blnVersions) {
							for ($k=0; $k<count($a_blobfiles); $k++)
								if (file_exists($strDatapath."/blobs/" . $a_blobfiles[$k]) ) {
									@chmod($strDatapath."/blobs/" . $a_blobfiles[$k], 0777);
									@unlink($strDatapath."/blobs/" . $a_blobfiles[$k]);
								}
						}
						////////////////////////////////////////////////////
						//echo "Write data ...<br>";
						while ($row = mysql_fetch_assoc($down_res)) {
							for ($j=0; $j<count($fieldprops); $j++) {
								// entscheiden, obs ein blobfeld ist oder nicht
								if (($blob_as_file=="no") or (!eregi("blob", $fieldprops[$j]['Type']))) {
									$row[$fieldprops[$j]['Field']] = str_replace("\r", $this->strCarriageReturnCode, $row[$fieldprops[$j]['Field']]);
									$row[$fieldprops[$j]['Field']] = str_replace("\n", $this->strLineFeedCode, $row[$fieldprops[$j]['Field']]);
									//$row[$j-1] = ereg_replace("\"", "'", $row[$j-1]);
									$row[$fieldprops[$j]['Field']] = addslashes($row[$fieldprops[$j]['Field']]);
									$fieldvalue = $row[$fieldprops[$j]['Field']];
								} else {
									if ($row[$fieldprops[$j]['Field']] != "") {
										// verzeichnis für blobs wird erzeugt und alle tabellendateien, die blobs repräsentieren werden gelöscht.
										if (!file_exists($strDatapath."/blobs/")) {
											mkdir($strDatapath."/blobs/", 0777);
											chmod($strDatapath."/blobs/", 0777);
										}
										if ($blnVersions) $blobfilename = $strDatapath."/blobs/".$table."_".$fieldprops[$j]['Field']."_".$strFileVersion."_".uniqid (rand());
										else $blobfilename = $strDatapath."/blobs/".$table."_".$fieldprops[$j]['Field']."_".uniqid (rand());
										$blobfile=fopen($blobfilename, "wb");
										fputs($blobfile, $row[$fieldprops[$j]['Field']]);
										fclose($blobfile);
										$fieldvalue = $blobfilename;
									} else $fieldvalue = "";
								}
								if ($gzipping=="yes") gzwrite($TXT,$fieldvalue); else fputs($TXT,$fieldvalue);
								if (($j+1)==$fields) {
									if ($i != $number) if ($gzipping=="yes") gzwrite($TXT, "\n"); else fputs($TXT, "\n");
								} else {
									if ($gzipping=="yes") gzwrite($TXT, $seperator); else fputs($TXT, $seperator);
								}
							}
							$i++;
						}
						if ($gzipping=="yes") gzclose($TXT); else fclose($TXT);
						@chmod ($dateitest, 0777);
						$i--;
						$success = 1;
						$res['email'] = $dateitest;
						$this->writeLog($dbs, $table, true, "backup_content", $this->text($i." von ".$number." Datens&auml;tzen bearbeitet ! ", $i." datasets from ".$number." processed ! ").$gzip_string, $strDatapath);
						$res['result'] = $this->text($i." von ".$number." Datens&auml;tzen bearbeitet ! ", $i." datasets from ".$number." processed ! ").$gzip_string;
					} else {
						$success = 0;
						$this->writeLog($dbs, $table, false, "backup_content", $this->text("Keine Schreibrechte auf dem Verzeichnis. PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben.", "No write access on the directory. PHP must have write permissions for the directory."), $strDatapath);
						$res['error'] = $this->text("Keine Schreibrechte auf dem Verzeichnis.<br>PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben.", "No write access on the directory. PHP must have write permissions for the directory.");
					}
				} else { // if $number
					//echo "<font class=black>No dataset in table $table !</font><br>";
					$this->writeLog($dbs, $table, false, "backup_content", $this->text("Keine Datens&auml;tze in der Tabelle !", "No dataset in table ".$table." !"), $strDatapath);
					$res['error'] = $this->text("Keine Datens&auml;tze in der Tabelle !", "No dataset in table ".$table." !");
				}  // if number > 0
			} //if fileexists
		} else {
			$success = 0;
			$this->writeLog($dbs, $table, false, "backup_content", $this->text("Keine Schreibrechte auf dem Verzeichnis. PHP kann das Verzeichnis \"".$strDatapath."\" nicht anlegen.<br>Schaue im Manual nach Abhilfe.", "No write access on the directory. Not possible to create the directory \"".$strDatapath."\".<br>Look in the manual for help."), $strDatapath);
			$res['error'] = $this->text("Keine Schreibrechte auf dem Verzeichnis. PHP kann das Verzeichnis \"".$strDatapath."\" nicht anlegen.<br>Schaue im Manual nach Abhilfe.", "No write access on the directory. Not possible to create the directory \"".$strDatapath."\".<br>Look in the manual for help.");
		}
		$res['success'] = $success;
		return $res;
	}

	function backup_bigtable($dbs, $table, $seperator, $superscribe, $aktuell_set_count, $sets_per_file, $file_number, $resstring, $strRelPath = "") {
		global $db;
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$conn = $db->connection;
		//echo "Processing table $table ...<br>";
		if (!file_exists($strDatapath)) { mkdir($strDatapath, 0777); chmod($strDatapath, 0777); }
		if (!file_exists($strDatapath)) {
			mkdir($strDatapath, 0777);
			chmod($strDatapath, 0777);
		}
		$file_number = substr("000".$file_number, -3);
		$dateitest = $strDatapath."/".$table.".".$file_number.".txt";   // Data in folder "data"
		if ((file_exists($dateitest)) and ($superscribe != "yes")) {
			$this->writeLog($dbs, $table, false, "backup_bigtable", $this->text("Datei \"$dateitest\" existiert und wird nicht überschrieben", "File \"$dateitest\" exists and wont be overwriten !"), $strDatapath);
			$resstring .= $this->text("Datei \"$dateitest\" existiert und wird nicht überschrieben<br>", "File \"$dateitest\" exists and wont be overwriten !<br>");
			$success=0;
		} else {
			$sql = "select count(*) from `$dbs`.`$table`";
			$anzahl_res = mysql_query($sql, $conn);
			if ($anzahl_res) {
				$row = mysql_fetch_row($anzahl_res);
				$number = $row[0];
			}
			//ermitteln der felder in der tabelle und deren typen
			$sql = "DESCRIBE `$dbs`.`$table`";
			$describe_res = mysql_query($sql, $conn);
			$fieldprops = array();
			while ($row = mysql_fetch_assoc($describe_res)) {
				$fieldprops[] = $row;
			}
			if ($number > 0) {
				if ($seperator == "tab") $seperator = "\t";
				$down_res = mysql_query("SELECT * from `$dbs`.`$table` LIMIT ".$aktuell_set_count.", ".$sets_per_file);
				$fields = mysql_num_fields($down_res);
				//echo "Open file ...<br>";
				$TXT=@fopen($dateitest, "w");
				if ($TXT) {
					$i = 1;
					if ($aktuell_set_count==0) {
						$a_blobfiles = $this->getBlobFilesForTable($dbs, $table, $strRelPath);
						for ($k=0; $k<count($a_blobfiles); $k++)
							if (file_exists($strDatapath."/blobs/" . $a_blobfiles[$k]) ) {
								@chmod($strDatapath."/blobs/" . $a_blobfiles[$k], 0777);
								@unlink($strDatapath."/blobs/" . $a_blobfiles[$k]);
							}
					}
					////////////////////////////////////////////////////
					//echo "Write data ...<br>";
					while ($row = mysql_fetch_assoc($down_res)) {
						for ($j=0; $j<count($fieldprops); $j++) {
							// entscheiden, obs ein blobfeld ist oder nicht
							if (!eregi("blob", $fieldprops[$j]['Type'])) {
								$row[$fieldprops[$j]['Field']] = str_replace("\r", $this->strCarriageReturnCode, $row[$fieldprops[$j]['Field']]);
								$row[$fieldprops[$j]['Field']] = str_replace("\n", $this->strLineFeedCode, $row[$fieldprops[$j]['Field']]);
								$row[$fieldprops[$j]['Field']] = addslashes($row[$fieldprops[$j]['Field']]);
								$fieldvalue = $row[$fieldprops[$j]['Field']];
							} else {
								if ($row[$fieldprops[$j]['Field']] != "") {
									// verzeichnis für blobs wird erzeugt und alle tabellendateien, die blobs repräsentieren werden gelöscht.
									if (!file_exists($strDatapath."/blobs/")) {
										mkdir($strDatapath."/blobs/", 0777);
										chmod($strDatapath."/blobs/", 0777);
									}
									$blobfilename = $strDatapath."/blobs/".$table."_".$fieldprops[$j]['Field']."_".uniqid (rand());
									$blobfile=fopen($blobfilename, "wb");
									fputs($blobfile, $row[$fieldprops[$j]['Field']]);
									fclose($blobfile);
									$fieldvalue = $blobfilename;
								} else $fieldvalue = "";
							}
							fputs($TXT,$fieldvalue);
							if (($j+1)==$fields) {
								if ($i != $number) fputs($TXT, "\n");
							} else {
								fputs($TXT, $seperator);
							}
						}
						$i++;
					}
					fclose($TXT);
					@chmod ($dateitest, 0777);
					$i--;
					$success = 1;
					$this->writeLog($dbs, $table, true, "backup_bigtable", $this->text($i." von ".$number." Datens&auml;tzen bearbeitet ! ", $i." datasets from ".$number." processed ! "), $strDatapath);
				} else {
					$success = 0;
					$resstring  .= $this->text("Keine Schreibrechte auf dem Verzeichnis.<br>PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben.<br>Schaue im Manual nach Abhilfe.", "No write access on the directory. PHP must have write permissions for the directory.<br>");
					$this->writeLog($dbs, $table, false, "backup_bigtable", $this->text("Keine Schreibrechte auf dem Verzeichnis.<br>PHP muss f&uuml;r das Verzeichnis \"".$strDatapath."\" Schreibrechte haben. Schaue im Manual nach Abhilfe.", "No write access on the directory. PHP must have write permissions for the directory"), $strDatapath);
				}
			} else { // if $number
				$this->writeLog($dbs, $table, false, "backup_bigtable", $this->text("Keine Datens&auml;tze in der Tabelle ".$table."!", "No dataset in table ".$table."!"), $strDatapath);
				$resstring .= $this->text("Keine Datens&auml;tze in der Tabelle ".$table."!<br>", "No dataset in table ".$table." !<br>");
			}  // if number > 0
		} //if fileexists
		if ($success==1) $resstring .= $dateitest.$this->text(" erfolgreich geschrieben<br>", " written successful<br>");
		return $resstring;
	}

	function merge_files($dbs, $table, $superscribe, $file_number, $resstring, $strRelPath = "") {
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$dateitestnew = $strDatapath."/".$table.".txt";   // Data in folder "data"
		
		if ((file_exists($dateitestnew)) and ($superscribe != "yes")) {
			$this->writeLog($dbs, $table, false, "merge_files", $this->text("Datei \"$dateitestnew\" existiert und wird nicht überschrieben", "File \"$dateitestnew\" exists and wont be overwriten !"), $strDatapath);
			$resstring .= $this->text("Datei \"$dateitestnew\" existiert und wird nicht überschrieben<br>", "File \"$dateitestnew\" exists and wont be overwriten !<br>");
		} else {
			$newfile=@fopen($dateitestnew, "w");
			
			for ($i=1; $i<=$file_number; $i++) {
				$akt_file_number = substr("000".$i, -3);
				$dateitest = $strDatapath."/".$table.".".$akt_file_number.".txt";   // Data in folder "data"
				
				$smallfile=join("",@file($dateitest));
				$smallfile = ereg_replace("\r\r\n", "\r\n", $smallfile);
				//@unlink($dateitest);
				$ok = @fputs($newfile, $smallfile);
			}
			fclose($newfile);
			@chmod ($dateitestnew, 0777);
			$resstring .= "<strong>".$dateitestnew.$this->text(" erfolgreich geschrieben<br>", " written successful<br>")."</strong><br>";
			$this->writeLog($dbs, $table, true, "merge_files", $dateitestnew.$this->text(" erfolgreich geschrieben", " written successful"), $strDatapath);
		}
		return $resstring;
	}

	function restore_def($dbs, $files, $superscribe, $strRelPath = "") {
		global $db;
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$table = "";
		$conn = $db->connection;
		$success = 0;
		$res['text'] = "";
		$res['error'] = "";
		$datei = $files;
		if (substr($files,-4)==".txt") $tb_files = substr($files,0,strlen($files)-4);
		$dateitest = $strDatapath."/".$datei;   // Daten liegen in Verzeichnis "data"
		$table = substr($tb_files, 7);
		if (file_exists($dateitest)) {
			//echo "<font class=black>Open file \"$datei\" ...</font><br>";
			$TXT=fopen($dateitest, "r");
			$sql = "";
			while(feof($TXT)==0) {
				$zeile=chop(fgets($TXT, 24000));
				$sql .= $zeile;
			}
			if (substr($sql, -1) == ";") $sql = substr($sql,0,strlen($sql)-1);
			$res['generated'] = "<b>Generated:</b> ".$sql;
			fclose($TXT);
			
			trim($sql);
			$help = trim(substr($sql, 13, strlen($sql) -13));
			$tb_files = trim(substr($help,0,strpos($help, "(")));
			//echo "Suche table: ",$tb_files,"<br>";
			
			if ($superscribe == "yes") {
				$res['text'] .= "<br>".$this->text("L&ouml;sche Tabelle ".$tb_files." ... ", "Delete table $tb_files ... ");
				$sql_drop = str_replace("``", "`", "DROP TABLE `$tb_files`");
				$drop_res = mysql_query($sql_drop);
				if ($drop_res) {
					$res['text'] .= $this->text("Tabelle gel&ouml;scht !", "Table deleted !");
				} else {
					$res['text'] .= $this->text("Tabelle existierte nicht", "Table doesn't exist");
				}
			}
	
			$write_res = mysql_query($sql);
			if ($write_res) {
				$success = 1;
				$this->writeLog($dbs, $table, true, "restore_def", $this->text("Tabelle erfolgreich erstellt !", "Table successful created !"), $strDatapath);
				$res['text'] .= "<br>".$this->text("Tabelle erfolgreich erstellt !", "Table successful created !");
			} else {
				$this->writeLog($dbs, $table, false, "restore_def", $this->text("Fehler: ", "Error: ").mysql_errno().": ".mysql_error(), $strDatapath);
				$res['error'] .= "<br>".$this->text("Fehler: ", "Error: ").mysql_errno().": ".mysql_error();
			}
		} else {
			$this->writeLog($dbs, $table, false, "restore_def", $this->text("Datei \"$datei\" existiert nicht !", "File \"$datei\" doesn't exist !"), $strDatapath);
			$res['error'] = $this->text("Datei \"$datei\" existiert nicht !", "File \"$datei\" doesn't exist !");
		}// if file_exists
		$res['success'] = $success;
		return $res;
	}

	function restore_content($dbs, $files, $seperator, $superscribe, $strRelPath = "") {
		global $db;
		$strDatapath = $this->config->data_path.$dbs;
		if ($strRelPath) $strDatapath = $strRelPath.$dbs;
		$conn = $db->connection;
		$success = 0; $error = false;
		$res['text'] = "";
		$res['error'] = "";
		if (substr($files, -7) == ".txt.gz") {
			$tb_files = substr($files,0,strlen($files)-7);
			$gzipping = true;
		} elseif (substr($files, -4) == ".txt") {
			$tb_files = substr($files,0,strlen($files)-4);
			$gzipping = false;
		}
		$table = $tb_files;
		if (ereg("\.", $tb_files)) {
			$tb_files = substr($tb_files,0,strlen($tb_files)-4);
		}
		$sql_number = "select count(*) from `".$dbs."`.`".$tb_files."`";
		$anzahl_res = mysql_query($sql_number, $conn);
		if ($anzahl_res) {
			$row = mysql_fetch_row($anzahl_res);
			$number = $row[0];
			if ($number > 0) {
				if ($superscribe == "yes") {
					$res['text'] .= "<br>".$this->text("L&ouml;sche Daten in Tabelle <b>$tb_files</b> ...", "Delete data in table <b>$tb_files</b> ...");
					$sql = "delete from " . $tb_files;
					//echo $sql."<br>";
					$del_res = mysql_query($sql);
					if (!$del_res) {
						$res['error'] .= "<br>".$this->text("Fehler: ", "Error: ").$sql."<br>".mysql_error();
					}
				}  // if $superscribe
			}  // if $number
			$num = 0;
			$dateitest = $strDatapath."/".$files;   // Daten liegen in Verzeichnis "data"
			if (file_exists($dateitest)) {
				//ermitteln der felder in der tabelle und deren typen
				$sql_help = "DESCRIBE `$tb_files`";
				$result_help = mysql_query($sql_help, $conn);
				if ($result_help) {
					$fieldnum=0;
					while ($row=mysql_fetch_assoc($result_help)) {
						$fieldprops[] = $row;
						$fieldnum++;
					}
				}
				//////////////////////////////////////////////
				$res['text'].= "<br>".$this->text("&Ouml;ffne Datei ", "Open file ").$dateitest." ...";
				if ($seperator == "tab") $seperator = "\t";
				if ($gzipping) $txt=gzopen($dateitest, "r"); else $txt=fopen($dateitest, "r");
				$res['text'].= "<br>".$this->text("Schreibe Daten in die Tabelle ...", "Write data into table...");
				$number_rows = 0;
				if ($gzipping) {
					// gzipping
					while(gzeof($txt)==false) {
						$zeile=chop(gzgets($txt, 2560000));
						if ($zeile != "") {  // falls die Zeile leer ist soll nicht geschrieben werden
							$number_rows++;
						 	$text = explode($seperator,$zeile);
							$sql = "INSERT into `" . $tb_files . "` values(";
							for ($i=0; $i<$fieldnum; $i++) {
								//$text[$i] = ereg_replace( "\'", "'", $text[$i]); alt
								//$text[$i] = ereg_replace( "'", "\'", $text[$i]); alt
								//$text[$i] = ereg_replace( "\'", "'", $text[$i]);
								//$text[$i] = ereg_replace( "\"", "'", $text[$i]);
								// entscheiden, obs ein blobfeld ist oder nicht
								if (eregi("blob", $fieldprops[$i]['Type'])) {
									$blobfilename = trim($text[$i]);
									if (trim($blobfilename) != "") {
										$blobfilename = $strDatapath . "/blobs/" . basename($blobfilename);
										@$fd = fopen ($blobfilename, "rb");
										if ($fd) {
											$blob_value = fread ($fd, filesize ($blobfilename));
											fclose ($fd);
											$sql=$sql."\"".addslashes($blob_value)."\", ";
										} else {
											$sql=$sql."\"\", ";
										}
									} else {
										$sql=$sql."\"\", ";
									}
								} else {
									$text[$i] = str_replace( $this->strCarriageReturnCode, "\r", $text[$i]);
									$text[$i] = str_replace( $this->strLineFeedCode, "\n", $text[$i]);
									$sql=$sql."\"".trim($text[$i])."\", ";
								}
							}
							$sql = substr($sql,0,strlen($sql)-2);  // delete last two chars
							$sql=$sql.")";
							$write_res = mysql_query($sql);
							if (!$write_res) {
								$this->writeLog($dbs, $table, false, "restore_content", $this->text("Fehler: ", "Error: ").$sql."<br>".mysql_error(), $strDatapath);
								$res['error'].= "<br>".$this->text("Fehler: ", "Error: ").$sql."<br>".mysql_error();
								$error = true;
							}
						}
					}
				} else {
					// not gzipping
					while(feof($txt)==0) {
						$zeile=chop(fgets($txt, 2560000));
						if ($zeile != "") {  // falls die Zeile leer ist soll nicht geschrieben werden
							$number_rows++;
						 	$text = explode($seperator,$zeile);
							$sql = "INSERT into `" . $tb_files . "` values(";
							for ($i=0; $i<$fieldnum; $i++) {
								//$text[$i] = ereg_replace( "\'", "'", $text[$i]); alt
								//$text[$i] = ereg_replace( "'", "\'", $text[$i]); alt
								//$text[$i] = ereg_replace( "\'", "'", $text[$i]);
								//$text[$i] = ereg_replace( "\"", "'", $text[$i]);
								if (eregi("blob", $fieldprops[$i]['Type'])) {
									$blobfilename = trim($text[$i]);
									if (trim($blobfilename) != "") {
										$blobfilename = $strDatapath . "/blobs/" . basename($blobfilename);
										@$fd = fopen ($blobfilename, "rb");
										if ($fd) {
											$blob_value = fread ($fd, filesize ($blobfilename));
											fclose ($fd);
											$sql=$sql."\"".addslashes($blob_value)."\", ";
										} else {
											$sql=$sql."\"\", ";
										}
									} else {
										$sql=$sql."\"\", ";
									}
								} else {
									$text[$i] = str_replace( $this->strCarriageReturnCode, "\r", $text[$i]);
									$text[$i] = str_replace( $this->strLineFeedCode, "\n", $text[$i]);
									$sql=$sql."\"".trim($text[$i])."\", ";
								}
							}
							$sql = substr($sql,0,strlen($sql)-2);  // delete last two chars
							$sql=$sql.")";
							$write_res = mysql_query($sql);
							//echo $sql . "<br>";
							if (!$write_res) {
								$this->writeLog($dbs, $table, false, "restore_content", $this->text("Fehler: ", "Error: ").$sql."<br>".mysql_error(), $strDatapath);
								$res['error'].= "<br>".$this->text("Fehler: ", "Error: ").$sql."<br>".mysql_error();
								$error = true;
							}
						}
					}
				}
				if ($gzipping) gzclose($txt); else fclose($txt);
				$anzahl_res = mysql_query($sql_number, $conn);
				$row = mysql_fetch_row($anzahl_res);
				$number_after = $row[0];
				if (!$error) {
					if (($superscribe) and ($number_after >= $number_rows)) $success = 1;
					elseif ($number_after == $number_rows) $success = 1;
					if ($success) $this->writeLog($dbs, $table, true, "restore_content", $this->text($number_rows." Datensätze in die Tabelle geschrieben ...", "Wrote ".$number_rows." datasets into table..."), $strDatapath);
				}
				$res['number'] = $number;
				$res['number_after'] = $number_after;
				$res['number_rows'] = $number_rows;
			} else {
				$this->writeLog($dbs, $table, false, "restore_content", $this->text("Datei \"$files\" existiert nicht !", "File \"$files\" doesn't exist !"), $strDatapath);
				$res['error'].= "<br>".$this->text("Datei \"$files\" existiert nicht !", "File \"$files\" doesn't exist !");
			}// if file_exists
		} else {
			$this->writeLog($dbs, $table, false, "restore_content", $this->text("Tabelle \"$tb_files\" existiert nicht !", "Table \"$tb_files\" doesn't exist !"), $strDatapath);
			$res['error'].= "<br>".$this->text("Tabelle \"$tb_files\" existiert nicht !", "Table \"$tb_files\" doesn't exist !");
		} // if anzahl_res
		$res['success'] = $success;
		return $res;
	}

	function text($ger="", $eng="") {
		if ($this->config->language=="german") return $ger;
		if ($this->config->language=="english") return $eng;
	}

	function popup($keyword) {
		return "&nbsp;&nbsp;<a href=\"javascript:popup('".$keyword."');\"><img src='img/popup.gif' width=20 height=21 border=0 align='absmiddle'></a>";
	}

	// Liefert aktuelles Datum (Format: DD. Mmmmmmm YYYY)
	function akt_datum() {
		$datum=getdate();
		$monat=array('Januar','Februar','M&auml;rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
		$dat=$datum["mday"].". ".$monat[$datum["mon"]-1]." ".$datum["year"];
		return $dat;
	}

	/**
	* converts the filesize in kB or MB
	* @access		public
	* @param	integer $bytes filesize in bytes
	* @return	string $size	string of the filesize
	*/
	function get_size($bytes) {
		$xx = $this->PMA_formatByteDown($bytes, 3, 1);
		return $xx[0]." ".$xx[1];
	}

    /**
     * Formats $value to byte view
     *
     * @param    double   the value to format
     * @param    integer  the sensitiveness
     * @param    integer  the number of decimals to retain
     *
     * @return   array    the formatted value and its unit
     *
     * @access  public
     *
     * @author   staybyte
     * @version  1.2 - 18 July 2002
     */
    function PMA_formatByteDown($value, $limes = 6, $comma = 0) {
		global $lang;
        $dh           = pow(10, $comma);
        $li           = pow(10, $limes);
        $return_value = $value;
        $unit         = $lang->byteUnits[0];
		
        for ( $d = 6, $ex = 15; $d >= 1; $d--, $ex-=3 ) {
            if (isset($lang->byteUnits[$d]) && $value >= $li * pow(10, $ex)) {
                $value = round($value / ( pow(1024, $d) / $dh) ) /$dh;
                $unit = $lang->byteUnits[$d];
                break 1;
            } // end if
        } // end for
		
        if ($unit != $lang->byteUnits[0]) {
            $return_value = number_format($value, $comma, $lang->number_decimal_separator, $lang->number_thousands_separator);
        } else {
            $return_value = number_format($value, 0, $lang->number_decimal_separator, $lang->number_thousands_separator);
        }
		
        return array($return_value, $unit);
    } // end of the 'PMA_formatByteDown' function

}

// ######################################################

/**
* class for date/time functions
*
* @author Oliver Kührig <oliver@kuehrig.de>
* @version 1.0.0; 2002/11/15
*/
Class DateFuncs {
	
	/**
	* time format (long-version)
	* @var	string
	*/
	var $s_fmt_time_long = "%th:%tm:%ts";
	
	/**
	* time format (short-version)
	* @var	string
	*/
	var $s_fmt_time_short = "%th:%tm";
	
	/**
	* mysqldate format
	* @var	string
	*/
	var $s_fmt_mysql_date = "%dY-%dm-%dd";
	
	/**
	* weekday format (long-version)
	* @var	string
	*/
	var $s_fmt_weekday_long = "%dW";
	
	/**
	* weekday format (short-version)
	* @var	string
	*/
	var $s_fmt_weekday_short = "%dw";
	
	/**
	* Class Constructor; sets several vars
	*/
	function DateFuncs() {
		global $lang;
		
		$this->s_fmt_mysql_datetime = $this->s_fmt_mysql_date." ".$this->s_fmt_time_long;
		$this->s_fmt_date_long = $lang->dateformat_long;
		$this->s_fmt_date_short = $lang->dateformat_short;
		
		$this->a_month = $lang->month;
		$this->a_weekday = $lang->day_of_week;
		$this->a_weekday_short = $lang->day_of_week_short;
		$this->a_getdate = getdate();
	}

	/**
	* sets actual format
	* 
	* @param	string	formatstring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function set_format($fmt) {
		$this->s_fmt = $fmt;
	}

	/**
	* sets dateinput in several formats
	* 
	* @param	string	formatstring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function set_inputdate($input="") {
		$this->s_input = $input;
		
		if (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$", $this->s_input, $parts)) {
			//MySQL-Format short
			$i_timestamp = mktime(0, 0, 0, $parts[2], $parts[3], $parts[1]);
			if ($i_timestamp > 0) $this->a_getdate = getdate($i_timestamp); else $this->a_getdate = getdate();
			
		} elseif (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$", $this->s_input, $parts)) {
			//MySQL-Format long
			$i_timestamp = mktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);
			if ($i_timestamp > 0) $this->a_getdate = getdate($i_timestamp); else $this->a_getdate = getdate();
			
		} elseif (ereg("^([0-9]{1,10})$", $this->s_input, $parts) and ($this->s_input<2147468400)) {
			//Timestamp
			$this->a_getdate = getdate($this->s_input);
		} else {
			//actual date
			$this->a_getdate = getdate();
		}
	}

	/**
	* returns converted formatstring
	* 
	* @param	string	formatstring
	* 
	* @return	string	converted datestring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function out() {
		$this->convert_format();
		return $this->s_fmt;
	}

	/**
	* converts the actual formatstring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function convert_format() {
		$this->s_fmt = str_replace("%dd", substr("0".$this->a_getdate["mday"], -2), $this->s_fmt);
		$this->s_fmt = str_replace("%dm", substr("0".$this->a_getdate["mon"], -2), $this->s_fmt);
		$this->s_fmt = str_replace("%dM", $this->a_month[$this->a_getdate["mon"]-1], $this->s_fmt);
		$this->s_fmt = str_replace("%dy", substr($this->a_getdate["year"], 2, 2), $this->s_fmt);
		$this->s_fmt = str_replace("%dY", $this->a_getdate["year"], $this->s_fmt);
		$this->s_fmt = str_replace("%dw", $this->a_weekday_short[$this->a_getdate["wday"]], $this->s_fmt);
		$this->s_fmt = str_replace("%dW", $this->a_weekday[$this->a_getdate["wday"]], $this->s_fmt);
		$this->s_fmt = str_replace("%th", substr("0".$this->a_getdate["hours"], -2), $this->s_fmt);
		$this->s_fmt = str_replace("%tm", substr("0".$this->a_getdate["minutes"], -2), $this->s_fmt);
		$this->s_fmt = str_replace("%ts", substr("0".$this->a_getdate["seconds"], -2), $this->s_fmt);
	}

	/**
	* returns mysqldate
	* 
	* @param	boolean	true, if long-version is required
	* 
	* @return	string	converted datestring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function get_mysqldate($long = true) {
		if ($long) $this->set_format($this->s_fmt_mysql_datetime);
		else $this->set_format($this->s_fmt_mysql_date);
		return $this->out();
	}

	/**
	* returns weekday
	* 
	* @param	boolean	true, if long-version is required
	* 
	* @return	string	converted datestring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function get_weekday($long = true) {
		if ($long) $this->set_format($this->s_fmt_weekday_long);
		else $this->set_format($this->s_fmt_weekday_short);
		return $this->out();
	}

	/**
	* returns date
	* 
	* @param	boolean	true, if long-version is required
	* 
	* @return	string	converted datestring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function get_date($long = true) {
		if ($long) $this->set_format($this->s_fmt_date_long);
		else $this->set_format($this->s_fmt_date_short);
		return $this->out();
	}

	/**
	* returns time
	* 
	* @param	boolean	true, if long-version is required
	* 
	* @return	string	converted datestring
	* 
	* @access  public
	* 
	* @author Oliver Kührig <oliver@kuehrig.de>
	* @version  1.0 - 15 November 2002
	*/
	function get_time($long = true) {
		if ($long) $this->set_format($this->s_fmt_time_long);
		else $this->set_format($this->s_fmt_time_short);
		return $this->out();
	}

}

?>