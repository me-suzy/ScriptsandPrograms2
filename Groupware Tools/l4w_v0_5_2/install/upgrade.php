<?php

	/*=====================================================================
    // $Id: upgrade.php,v 1.4 2005/07/28 14:49:57 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/

    //===============================================================
    // Functions
    //===============================================================
    function replace_sql ($str) {
        global $version;
        
        $str = str_replace ('###TABLE_PREFIX###', TABLE_PREFIX, $str);    
        $str = str_replace ('###VERSION###', $version, $str);    
        $str = str_replace (array ('&lt;','&gt;','&amp;','\r','\n'), array ('<','>','&','',''), $str);    
        //$str = str_replace ('&gt;', '<', $str);    
        
        return $str;
    }    
    
    function executeFile ($filename, $comment, $sep = ";") {
        
        $last_inserted_id = null;
        $var_array        = null;
        
        list ($name, $versionFromIniFile) = getInstalledApplication("../");
        
        $version = explode (".", $versionFromIniFile);
        $file  = file_get_contents($filename);
		$file  = replace_sql ($file);
		$stmts = explode ($sep, $file);
		echo "<br><h3>$comment:</h3>";
		$cnt   = 0;
		foreach ($stmts AS $stmt) {
			if (trim($stmt) != '') {
				// stmt start with var?
				//echo "+".substr (trim($stmt),0,4)."+".trim($stmt)."-<br>";
				if (substr (trim($stmt),0,4) == "var ") {
					$tmp = substr (trim ($stmt),4);
					$tmp = explode ("=", $tmp);
					switch ($tmp[1]) {
						case 'mysql_inserted_id':
							$value = $last_inserted_id;
							break;
						default:
							die (__LINE__);
							break;
					}
					$var_array[$tmp[0]] = $value;
					echo "<br>";
					var_dump($var_array);	
					echo "<br>";
					continue;
				}	
				
				// calculate hash
				$stmt_hash = strlen($stmt)."-".md5($stmt);
				// if stmt exists break and continue
				$exists_query = "
					SELECT COUNT(*) FROM ".TABLE_PREFIX."update_app_stmts 
					WHERE
						version_main   = $version[0] AND
						version_sub    = $version[1] AND
						version_detail = $version[2] AND
						stmt = '".$stmt_hash."'

				";
				$ex_res = mysql_query ($exists_query);
				echo "<b>".mysql_error()."</b>";
				if (mysql_error() != '') {
				    echo ":<br><pre>".$exists_query."</pre><br>";    
				}
				$ex_row = mysql_fetch_array($ex_res);
				if ($ex_row[0] > 0) {
					echo "<font color='green'><b>Stmt has been executed before... skipped (no error):</b></font><br>'<font size=1>".$stmt."'<br></font>\n";
					continue;	
				}	
								
				// alter existing variables 
				if (!is_null ($var_array)) {
					foreach ($var_array AS $key => $value) {
						$stmt = str_replace ('###'.$key.'###', $value, $stmt);	
					}	
				}
					
				echo ".";
				echo "Executing: ".$stmt."<br>";
				$res = mysql_query ($stmt);
				echo "<b>".mysql_error()."</b>";
				if (mysql_error() != '') {
				    echo ":<br><pre>".$stmt."</pre><br>";    
				}    
				else {
				    // remember query
                    $update_query = "
    					INSERT INTO ".TABLE_PREFIX."update_app_stmts 
    						(tstamp, version_main, version_sub, version_detail, stmt) 
    					VALUES (
    						now(),
    						$version[0],
    						$version[1],
    						$version[2],
    						'$stmt_hash'
    					)";
                    $res = mysql_query ($update_query);
    				echo "<b>".mysql_error()."</b>";
	    			if (mysql_error() != '') {
		    		    echo ":<br><pre>".$update_query."</pre><br>";    
			    	}
				}    
				$last_inserted_id = mysql_insert_id();
			    $cnt++;
			    if ($cnt%40 == 0) echo "<br>";
			}
		}
		echo "<br>";
    
    }    

    //===============================================================
    // Test installation
    //===============================================================
    $config_file = "../config/config.inc.php";
    if (!file_exists($config_file)) {
            die ("<br><br><center><i><b>".$config_file."</i> does not exist!</b>
              <br><br>Please read the install notes, edit <i>config.inc.php.default</i> and rename it to <i>$config_file</i>");
    }

    // --- basic includes -------------------------------------------
	include ($config_file);
    include ("../connect_database.php");
    include ("../inc/functions.inc.php");

	$info_only = false;
	
    // --- check connection -----------------------------------------
    $check_connection = mysql_select_db ($db_name, $db);
    if (!$check_connection) {
        die ("<br><br><center><i><b>Could not connect to database!</b>
        <br><br>Please read the install notes and edit <i>config.inc.php</i> appropriately");
    	die();
    }

	// --- check if database is set up already -----------------------
	/*$tables = mysql_list_tables($db_name);
	$table_array = array();
	while ($row = mysql_fetch_row($tables)) {
    	$table_array[] = $row[0];
	}
	mysql_free_result($tables);
	// guess: if those tables exists, programm is installed already
	if (
		in_array(TABLE_PREFIX."access_options", $table_array) &&
		in_array(TABLE_PREFIX."events", $table_array) &&
		in_array(TABLE_PREFIX."gacl_aro_groups", $table_array) &&
		in_array(TABLE_PREFIX."transitions", $table_array) &&
		in_array(TABLE_PREFIX."user_details", $table_array) 
	) {
		echo "<br><center><font color='red'>";
		echo "Programm (Database) already installed!";
    	echo "</font>";
    	$info_only = true;
	}*/

?>