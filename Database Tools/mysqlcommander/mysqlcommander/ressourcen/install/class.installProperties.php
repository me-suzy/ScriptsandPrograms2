<?php 
/**
* class holding installation properties for applications
*
* class can compare its own properties with the properties in the configuration file for the application
* only properties their own properties will be compared
*
* @author Niels Hoffmann <niels.hoffmann@freenet.de>
* @version 1.2.0; 2003/06/21; 19:00:00
*/
class installProperties {
	
	/**
	* mode of the installer
	* values can be "private" (no access an all fields), "protected" (access on not protected fields), "public" (access an all fields)
	* if a user and mode file exists it's mode will be precedent
	* @access public
	*/
	var $s_mode = "private";
	/**
	* name of the file which will store the configuration variables
	* only the name, without extension
	* @access public
	*/
	var $s_conf_file = "";
	/**
	* relative path to settings file
	* @access public
	*/
	var $s_settings_path = "";
	/**
	* relative path to the file which stores the configuration variables
	* @access public
	*/
	var $s_conf_file_path = "";
	/**
	* array of the variables (and their properties) for the configuration
	* @access private
	*/
	var $a_variables = array();
	/**
	* array of the possible variable values for the configuration
	* @access private
	*/
	var $a_variables_values = array();
	/**
	* array of free additional lines of code
	* @access private
	*/
	var $a_free_code = array();
	/**
	* defines wether the settings file is used or not
	* @access private
	*/
	var $bln_used_settings_file = false;
	/**
	* defines the username
	* @access private
	*/
	var $s_user = "default";
	/**
	* defines the password
	* @access private
	*/
	var $s_password = "default";
	/**
	* defines wether the login was correct
	* @access private
	*/
	var $bln_correct_login = false;
	
	/**
	* Initializes the object
	* @param string $s_conf_file name to the file which stores the configuration variables
	* @param string $s_conf_file_path relative path to the file which stores the configuration variables
	* @param string $s_settings_path relative path to the settings file
	*/
	function installProperties($s_conf_file, $s_conf_file_path = "./", $s_settings_path = "./", $s_user = "default", $s_password = "default") {
		$this->s_conf_file = $s_conf_file;
		$this->s_conf_file_path = $s_conf_file_path;
		$this->s_settings_path = $s_settings_path;
		if (file_exists($this->s_settings_path.'instsettings.inc.php')) {
			$arrData = file($this->s_settings_path.'instsettings.inc.php');
			for ($i=0; $i<count($arrData); $i++) {
				$arrValues = explode("|", $arrData[$i]);
				if ($arrValues[0] == "default_mode") $this->s_mode = trim($arrValues[1]);
				if ($s_user AND ($s_user != "default")) {
					$this->s_user = $s_user;
					if (strlen($s_password) != 32) $this->s_password = md5($s_password);
					else  $this->s_password = $s_password;
					if ( ($arrValues[0] == "user") AND ($this->s_user == $arrValues[1]) AND ($this->s_password == md5($arrValues[2])) ) {
						$this->bln_correct_login = true;
						if (trim($arrValues[3]) == "private") $this->s_mode = "private";
						if (trim($arrValues[3]) == "protected") $this->s_mode = "protected";
						if (trim($arrValues[3]) == "public") $this->s_mode = "public";
					}
				} else $this->bln_correct_login = true;
			}
			$this->bln_used_settings_file = true;
		} else $this->bln_correct_login = true;
	} // end func installItem

	/**
	* return true if the login were correct, else false;
	* @return boolean $blnLgon 
	*/	
	function correctLogin() {
	 return $this->bln_correct_login;
	} // end func correctLogin

	/**
	* returns a string with the relative path and filename to the configiuration file
	* @return string $s_conf_file relative path and filename to the configiuration file
	*/
	function getConfFileName() {
		return $this->s_conf_file;
	} // end func getConfFileName
	
	/**
	* sets the mode for the installer
	* if a user and mode file exists it's mode will be precedent
	* @param $strMode string values can be "private" (no access), "protected" (access on not protected fields), "public" (access an all fields)
	*/
	function setMode($strMode) {
		if (!$this->bln_used_settings_file) {
			if ($strMode == "private") $this->s_mode = "private";
			elseif ($strMode == "protected") $this->s_mode = "protected";
			elseif ($strMode == "public") $this->s_mode = "public";
		}
	} // end func setMode

	/**
	* sets the default mode for the installer
	* will be saved in 'instsettings.inc.php'
	* @param $strMode string values can be "private" (no access), "protected" (access on not protected fields), "public" (access an all fields)
	*/
	function setDefaultMode($strMode) {
		$arrData = array();
		$strMode == "";
		if ($strMode == "private") $strMode = "default_mode|private";
		elseif ($strMode == "protected") $strMode = "default_mode|protected";
		elseif ($strMode == "public") $strMode = "default_mode|public";
		if (file_exists($this->s_settings_path.'instsettings.inc.php')) {
			$arrData = file($this->s_settings_path.'instsettings.inc.php');
			for ($i=0; $i<count($arrData); $i++) {
				$arrData[$i] = chop($arrData[$i]);
				$arrValues = explode("|", $arrData[$i]);
				if ($arrValues[0] == "default_mode" AND $strMode) $arrData[$i] = $strMode;
			}
		} elseif ($strMode) $arrData[0] = $strMode;
		if (count($arrData) > 0) {
			$fp = fopen($this->s_settings_path.'instsettings.inc.php', 'w+');
			for ($i=0; $i<count($arrData); $i++) {
				if (trim($arrData[$i]) != "") fwrite($fp, $arrData[$i]."\n");
			}
			fclose($fp);
		}
	} // end func setDefaultMode
	
	/**
	* adds an user to the installer or overwrites the existing one
	* will be saved in 'instsettings.inc.php'
	* @param $strUsername string name of the user, if username exists it will be overwritten
	* @param $strPassword string password of the user
	* @param $strMode string values can be "private" (no access), "protected" (access on not protected fields), "public" (access an all fields)
	*/
	function addUser($strUsername, $strPassword, $strMode = "private") {
		$arrData = array();
		if ($strMode == "private") $strMode = "private";
		elseif ($strMode == "protected") $strMode = "protected";
		elseif ($strMode == "public") $strMode = "public";
		else $strMode == "private";
		$blnWritten = false;
		if (file_exists($this->s_settings_path.'instsettings.inc.php')) {
			$arrData = file($this->s_settings_path.'instsettings.inc.php');
			for ($i=0; $i<count($arrData); $i++) {
				$arrData[$i] = chop($arrData[$i]);
				$arrValues = explode("|", $arrData[$i]);
				if ($arrValues[0] == "user" AND ($strUsername == $arrValues[1])) {
					$arrData[$i] = "user|".$strUsername."|".$strPassword."|".$strMode;
					$blnWritten = true;
				}
			}
			if (!$blnWritten) $arrData[$i] = "user|".$strUsername."|".$strPassword."|".$strMode;
		} else $arrData[0] = "user|".$strUsername."|".$strPassword."|".$strMode;

		if (count($arrData) > 0) {
			$fp = fopen($this->s_settings_path.'instsettings.inc.php', 'w+');
			for ($i=0; $i<count($arrData); $i++) {
				//echo "|".$arrData[$i]."|<br>";
				if (trim($arrData[$i]) != "") fwrite($fp, $arrData[$i]."\n");
			}
			fclose($fp);
		}
	} // end func addUser

	/**
	* removes all user from the installer
	* will be saved in 'instsettings.inc.php'
	*/
	function removeAllUser() {
		$arrData = array();
		if (file_exists($this->s_settings_path.'instsettings.inc.php')) {
			$arrData = file($this->s_settings_path.'instsettings.inc.php');
			for ($i=0; $i<count($arrData); $i++) {
				$arrData[$i] = chop($arrData[$i]);
				$arrValues = explode("|", $arrData[$i]);
				if ($arrValues[0] == "user") {
					$arrData[$i] = "";
				}
			}
		}
		if (count($arrData) > 0) {
			$fp = fopen($this->s_settings_path.'instsettings.inc.php', 'w+');
			for ($i=0; $i<count($arrData); $i++) {
				if (trim($arrData[$i]) != "") fwrite($fp, $arrData[$i]."\n");
			}
			fclose($fp);
		}
	} // end func removeAllUser
	
	/**
	* returns an array of all variable names
	* @return array $a_varnames array of all variable names
	*/
	function getVarnames() {
		$a_varnames = array();
		foreach ($this->a_variables as $key => $value) $a_varnames[] = $key;
		return $a_varnames;
	} // end func getConfFileName
	
	/**
	* sets the value for a variable
	* @param string $s_varname name of the variable
	* @param string $s_varvalue value of the variable
	*/
	function setVariableValue($s_varname, $s_varvalue) {
		if ($this->s_mode == "private") 
			$s_varvalue = $this->a_variables[$s_varname]['value'];
		elseif ( ($this->s_mode == "protected") AND ($this->a_variables[$s_varname]['mode'] == "protected") )
			 $s_varvalue = $this->a_variables[$s_varname]['value'];
		if (isset($this->a_variables[$s_varname]) ) {
			if ($this->a_variables[$s_varname]['typ'] == "boolean") {
				if ($s_varvalue) $this->a_variables[$s_varname]['value'] = "1";
				else  $this->a_variables[$s_varname]['value'] = "0";
			}
			else $this->a_variables[$s_varname]['value'] = $s_varvalue;
		}
	} // end func setVariable

	/**
	* sets the properties for a variable
	* if variablename not exist it will be created
	* @param string $s_varname name of the variable
	* @param string $s_varvalue value of the variable
	* @param string $s_description description of the variable
	*/
	function setVariable($s_varname, $s_varvalue, $s_description = "", $s_typ = "", $s_mode = "") {
		if (!isset($s_typ) or !$s_typ) $s_typ = "string";
		if (!isset($s_mode) or !$s_mode) $s_mode = "public";
		$a_new_var = array ("name" => $s_varname, "value" => $s_varvalue, "descr" => $s_description, "typ" => $s_typ, "mode" => $s_mode);
		$this->a_variables[$s_varname] = $a_new_var;
	} // end func setVariable

	/**
	* displays a seperator
	*/
	function setSeperator() {
		$s_string = "seperator_xxx_".md5(microtime());
		$a_new_var = array ("name" => $s_string, "value" => "", "descr" => "", "typ" => "sep", "protect"=>"unprotected");
		$this->a_variables[$s_string] = $a_new_var;
	} // end func setSeperator

	/**
	* displays a headline
	*/
	function setHeadline($s_text) {
		$s_string = "headline_xxx_".md5(microtime());
		$a_new_var = array ("name" => $s_string, "value" => $s_text, "descr" => "", "typ" => "head", "protect"=>"unprotected");
		$this->a_variables[$s_string] = $a_new_var;
	} // end func setHeadline

	/**
	* displays a comment
	*/
	function setComment($s_text) {
		$s_string = "comment_xxx_".md5(microtime());
		$a_new_var = array ("name" => $s_string, "value" => $s_text, "descr" => "", "typ" => "comment", "protect"=>"unprotected");
		$this->a_variables[$s_string] = $a_new_var;
	} // end func setComment

	/**
	* returns an assoziative array for the given variable
	* fields are: name, value and descr
	* @param string $s_varname name of the variable
	* @return array $a_variable assoziative array of the given variable
	*/
	function getVariableArray($s_varname) {
		if (isset($this->a_variables[$s_varname])) return $this->a_variables[$s_varname];
		else return false;
	} // end func getVariableArray

	/**
	* adds a new possible value for a variable
	* @param string $s_varname name of the variable
	* @param string $s_varvalue value to add for the variable
	*/
	function addVariableValue($s_varname, $s_varvalue) {
		if (isset($this->a_variables_values[$s_varname]) ) array_push ($this->a_variables_values[$s_varname], $s_varvalue);
		else $this->a_variables_values[$s_varname] = array($s_varvalue);
	} // end func addVariableValue

	/**
	* returns an array of the possible values for the given variable
	* @param string $s_varname name of the variable
	* @return array $a_variablevalues array of the possible values for the given variable
	*/
	function getVariableValues($s_varname) {
		if (isset($this->a_variables_values[$s_varname]) AND ( count($this->a_variables_values[$s_varname]) > 0) ) return $this->a_variables_values[$s_varname];
		else return array();
	} // end func getVariableValues
	
	
	/*adds a new line of code, which will be atached after all virable definitions in the config file
	* @param string $s_code string of the php code to add
	*/
	function addCodeLine($s_code) {
		$this->a_free_code[] = $s_code;
	} // end func addCodeLine
	
	/**
	* writes the configuration file into the file system
	* @return boolean $b_correct returns 'true' or 'false' wetehr the function went correct or not.
	*/	
	function storeConfigFile() {
		if ($this->s_conf_file != "") {
			$s_filename = $this->s_conf_file_path . $this->s_conf_file . ".php";
			//@chmod ($inst_path, 0777);
			$i_fd = @fopen ($s_filename, "w");
			if ($i_fd) {
				fputs ($i_fd, "<?php \n");
				$a_varnames = $this->getVarnames();
				for ($i=0; $i<count($a_varnames); $i++) {
					$a_variable = $this->getVariableArray($a_varnames[$i]);
					if ($a_variable) {
						if ($a_variable['typ'] != "head" and $a_variable['typ'] != "sep") {
							if ($a_variable['typ'] == "text") {
								$a_variable['value'] = str_replace("\r\n", "<br>", $a_variable['value']);
								$a_variable['value'] = str_replace("\n", "<br>", $a_variable['value']);
								$a_variable['value'] = str_replace("\r", "<br>", $a_variable['value']);
							}
							$a_variable['value'] = str_replace("\\\"", "'", $a_variable['value']);
							$a_variable['value'] = str_replace("\\", "", $a_variable['value']);
							if ($a_variable['typ'] == "boolean") {
								if ($a_variable['value']) fputs ($i_fd, "\$" . $a_variable['name'] . " = true;\n");
								else  fputs ($i_fd, "\$" . $a_variable['name'] . " = false;\n");
							}
							else fputs ($i_fd, "\$" . $a_variable['name'] . " = \"" . $a_variable['value'] . "\";\n");
						}
					}
				}
				fputs ($i_fd, "\n");
				if (count($this->a_free_code) > 0) {
					for ($i=0; $i<count($this->a_free_code); $i++) {
						fputs ($i_fd, $this->a_free_code[$i]."\n");
					}
				}
				fputs ($i_fd, "?>\n");
				fclose ($i_fd);
			} else return false;
			//@chmod ($inst_path, 0755);
		} else return false;
		return true;
	}// end func storeConfigFile
	
	/**
	* writes the configuration file into the file system as a PHP class
	* @return boolean $b_correct returns 'true' or 'false' wetehr the function went correct or not.
	*/	
	function storeClassConfigFile() {
		if ($this->s_conf_file != "") {
			$s_filename = $this->s_conf_file_path . "class." . $this->s_conf_file . ".php";
			//@chmod ($inst_path, 0777);
			$i_fd = @fopen ($s_filename, "w");
			if ($i_fd) {
				$a_varnames = $this->getVarnames();
				fputs ($i_fd, "<?php \n");
				fputs ($i_fd, "class " . $this->s_conf_file . " {\n");
				for ($i=0; $i<count($a_varnames); $i++) {
					$a_variable = $this->getVariableArray($a_varnames[$i]);
					if ($a_variable) {
						if ($a_variable['typ'] != "head" and $a_variable['typ'] != "sep") 
							fputs ($i_fd, "\tvar \$" . $a_variable['name'] . " = \"\";\n");
					}
				}
				fputs ($i_fd, "\n");
				fputs ($i_fd, "\tfunction " . $this->s_conf_file . " () {\n");
				for ($i=0; $i<count($a_varnames); $i++) {
					$a_variable = $this->getVariableArray($a_varnames[$i]);
					if ($a_variable) {
						if ($a_variable['typ'] != "head" and $a_variable['typ'] != "sep") {
							if ($a_variable['typ'] == "text") {
								$a_variable['value'] = str_replace("\r\n", "<br>", $a_variable['value']);
								$a_variable['value'] = str_replace("\n", "<br>", $a_variable['value']);
								$a_variable['value'] = str_replace("\r", "<br>", $a_variable['value']);
							}
							$a_variable['value'] = str_replace("\\\"", "'", $a_variable['value']);
							$a_variable['value'] = str_replace("\\", "", $a_variable['value']);
							if ($a_variable['typ'] == "boolean") {
								if ($a_variable['value']) fputs ($i_fd, "\t\t\$this->" . $a_variable['name'] . " = true;\n");
								else  fputs ($i_fd, "\t\t\$this->" . $a_variable['name'] . " = false;\n");
							} else fputs ($i_fd, "\t\t\$this->" . $a_variable['name'] . " = \"" . $a_variable['value'] . "\";\n");
						}
					}
				}
				fputs ($i_fd, "\t}// end func " . $this->s_conf_file . "\n\n");
				fputs ($i_fd, "} // end class " . $this->s_conf_file . "\n\n");
				if (count($this->a_free_code) > 0) {
					for ($i=0; $i<count($this->a_free_code); $i++) {
						fputs ($i_fd, $this->a_free_code[$i]."\n");
					}
				}
				fputs ($i_fd, "?>\n");
				fclose ($i_fd);
			} else return false;
			//@chmod ($inst_path, 0755);
		} else return false;
		return true;
	}// end func storeConfigFile
	
	/**
	* returns the configuration file as a PHP class
	* @return the configuration file as a PHP class
	*/	
	function getClassConfigFile() {
		if ($this->s_conf_file != "") {
			$s_text = "";
			$s_filename = $this->s_conf_file_path . "class." . $this->s_conf_file . ".php";
			$a_varnames = $this->getVarnames();
			$s_text .= "<?php \n";
			$s_text .= "class " . $this->s_conf_file . " {\n";
			for ($i=0; $i<count($a_varnames); $i++) {
				$a_variable = $this->getVariableArray($a_varnames[$i]);
				if ($a_variable) {
					if ($a_variable['typ'] != "head" and $a_variable['typ'] != "sep") 
						$s_text .= "\tvar \$" . $a_variable['name'] . " = \"\";\n";
				}
			}
			$s_text .= "\n";
			$s_text .= "\tfunction " . $this->s_conf_file . " () {\n";
			for ($i=0; $i<count($a_varnames); $i++) {
				$a_variable = $this->getVariableArray($a_varnames[$i]);
				if ($a_variable) {
					if ($a_variable['typ'] != "head" and $a_variable['typ'] != "sep") {
						if ($a_variable['typ'] == "text") {
							$a_variable['value'] = str_replace("\r\n", "<br>", $a_variable['value']);
							$a_variable['value'] = str_replace("\n", "<br>", $a_variable['value']);
							$a_variable['value'] = str_replace("\r", "<br>", $a_variable['value']);
						}
						$a_variable['value'] = str_replace("\\\"", "'", $a_variable['value']);
						$a_variable['value'] = str_replace("\\", "", $a_variable['value']);
						if ($a_variable['typ'] == "boolean") {
							if ($a_variable['value']) $s_text .= "\t\t\$this->" . $a_variable['name'] . " = true;\n";
							else  $s_text .= "\t\t\$this->" . $a_variable['name'] . " = false;\n";
						} else $s_text .= "\t\t\$this->" . $a_variable['name'] . " = \"" . $a_variable['value'] . "\";\n";
					}
				}
			}
			$s_text .= "\t}// end func " . $this->s_conf_file . "\n\n";
			$s_text .= "} // end class " . $this->s_conf_file . "\n\n";
			if (count($this->a_free_code) > 0) {
				for ($i=0; $i<count($this->a_free_code); $i++) {
					$s_text .= $this->a_free_code[$i]."\n";
				}
			}
			$s_text .= "?>\n";
		} else return false;
		return $s_text;
	}// end func getConfigFile
	
	/**
	* compares the entries in the configuration file with the defined properties in this object
	* variables, which exist in both then the variable properties of the configuration file are set for these variables
	* @return boolean $b_correct returns 'true' or 'false' wetehr the function went correct or not.
	*/	
	function compareConfigFile() {
		//echo $this->s_conf_file_path.$this->s_conf_file.".php";
		if ( ($this->s_conf_file_path.$this->s_conf_file.".php" != "") AND (file_exists($this->s_conf_file_path.$this->s_conf_file.".php")) ){
			$a_file_data = file($this->s_conf_file_path.$this->s_conf_file.".php");
			for ($i=0; $i<count($a_file_data); $i++) {
				if (count($a_file_data[$i]) > 0) {
					if (substr($a_file_data[$i], 0, 1)=="$") {
						$a_dataarr = explode("=", $a_file_data[$i]);
						$s_variable_name = trim(substr($a_dataarr[0], 1));
						$s_variable_value = trim(str_replace("\"", "", str_replace(";", "", $a_dataarr[1])));
						if ($a_variable = $this->getVariableArray($s_variable_name)) {
							$this->setVariable($a_variable['name'], $s_variable_value, $a_variable['descr'], $a_variable['typ'], $a_variable['mode']);
						}
					}
				}
			}
		} else return false;
	}// end func compareConfigFile
	
	/**
	* 
	* 
	*/	
	function sqlQuery($dbase, $user, $pass, $server, $file) {
  		$conn = @mysql_connect($server,$user,$pass);
  		if (!$conn) {
  			echo "Verbindungsfehler<br>"; return false;
  		}
  		if (!mysql_select_db($dbase,$conn)) {
			$sql = "CREATE DATABASE ".$dbase."";
			$results = mysql_query($sql,$conn);
  			if (!$results) {
				echo "Fehler<br>".$sql; return false;
			}
			if (!mysql_select_db($dbase,$conn)) {
				echo "Datenbankfehler<br>"; return false;
			}
  		}
		$s_dump = "";
		if (file_exists($file)) {
			$a_file = file($file);
			for ($i=0; $i<count($a_file); $i++) {
				$sql = trim(($a_file[$i]));
				if ((strlen($sql)>0) and ($sql[0] != "#")) {
					$s_dump .= $sql;
		  		}
			}
			$a_dump = explode(";", $s_dump);
			foreach ($a_dump as $sql) {
				if (trim($sql) != "") {
					//echo $sql."<br>";
					$results = mysql_query($sql,$conn);
		  			if (!$results) {
						echo "Fehler<br>".$sql; return false;
					}
				}
			}
		}
		return true;
	}// end func sqlQuery
	
} // end class installProperties

?>
