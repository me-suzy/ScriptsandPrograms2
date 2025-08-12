<?php

	/*=====================================================================
    // $Id: install.php,v 1.7 2005/08/01 14:55:13 carsten Exp $
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
        
        $file  = file_get_contents($filename);
		$file  = replace_sql ($file);
		$stmts = explode ($sep, $file);
		echo "<br><h3>$comment:</h3>";
		$cnt   = 0;
		foreach ($stmts AS $key => $stmt) {
			if (trim($stmt) != '') {
				echo ".";
				$res = mysql_query ($stmt);
				echo "<b>".mysql_error()."</b>";
				if (mysql_error() != '') {
				    echo ":<br><pre>".$stmt."</pre><br>";    
				}    
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
	$tables = mysql_list_tables($db_name);
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
	}

?>