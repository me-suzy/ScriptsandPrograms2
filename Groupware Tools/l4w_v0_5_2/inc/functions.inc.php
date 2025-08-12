<?php

   /**
    * $Id: functions.inc.php,v 1.60 2005/08/04 15:48:30 carsten Exp $
    *
    *
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

    $LANG = array();
    
	//===============================================================
	// General:
	//===============================================================

	function use_gz_compression () {
		global $user_id, $HTTP_SERVER_VARS;

		if (!isset ($user_id) || $user_id == "" || $user_id == null || $user_id == 0) return false;

		$query = "SELECT compression FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'";
		$res = mysql_query ($query);
		if (mysql_error() <> "") {
        	logDBError (__FILE__, __LINE__, mysql_error());
        	return false;
		}
		else {
			$row = mysql_fetch_array ($res);
			if ($row[0] == "true") {
				return true;
			}
		}
		return false;
	}

	if (use_gz_compression ()) {
		@ob_start ("ob_gzhandler");
	}

	function security_check_core () {
		global $logger, $logger;

        // maybe not logged in at all?
        if (!isset ($_SESSION['login'])) {
            $link  = "http://".$_SERVER['HTTP_HOST'];
		    $link .= dirname($_SERVER['PHP_SELF']);
		    $link .= "/main.php";
		    $logger->log ('session not set in '.__FILE__." (".__LINE__.")");
            echo "<script language='javascript'>window.location.href='".$link."';</script>";
            die("</body></html>");
        }
        
		$query   = "SELECT * FROM ".TABLE_PREFIX."users WHERE login='".$_SESSION['login']."'";
		$result  = mysql_query($query);
		logDBError(__FILE__, __LINE__, mysql_error(), true);
		$perm	 = mysql_fetch_array ($result);

		if (md5($_SESSION['passwort']) <> $perm['password']) {
			$logger->log ("Security_check_core failed for Login '".$_SESSION['login']."'",2);
			die ("Security_check_core failed for Login '".$_SESSION['login']."'");
		}
	}

	function admin_security_check () {
		//global $db;
		global $user_id, $group;
		security_check();
		if (!is_admin_of_own_group($user_id, $group)) die ("Admin Security alert!!! Program terminated");
	}

	function security_check_alert ($msg) {
		if (!security_check_core()) {
			?>
			  <script language=javascript>
				   alert ("Security alert!!! Program terminated\n<?=$msg?>");
			  </script>
			<?php
			exit ();
		}
		return true;
	}

	function is_superadmin ($use_user = null) {
		
		if ($use_user === null) 
			$use_user = $_SESSION['user_id'];
		$superadmins = explode ("|", SUPERADMIN_ARRAY);
		return in_array ($use_user, $superadmins);			
	}
			
	// page stats
	//function set_page_stats($user_id, $page) {
    function set_page_stats($page) {
	
	    if (strlen ($page) > 40)
	        $page = substr ($page, -40);
	    
	    $page  = mysql_escape_string ($page);
	    $today = date("Ymd");
	    
		$res = mysql_query ("
		    SELECT COUNT(*) FROM ".TABLE_PREFIX."page_stats
            WHERE 
                user='".$_SESSION['user_id']."' AND 
                page='$page' AND 
                day='$today'
            ");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		if ($row[0] == 0) { // new entry
            $insert_query = "INSERT INTO ".TABLE_PREFIX."page_stats (
			        user, page, day, month, year, counter
			    ) 
			    VALUES
				    ('".$_SESSION['user_id']."',
				    '$page',
				    '$today','','',1)";
		    
			mysql_query ($insert_query);
			logDBError (__FILE__, __LINE__, mysql_error(), $insert_query);
		}
		else { // update entry
			mysql_query (
			    "UPDATE ".TABLE_PREFIX."page_stats SET counter=counter+1
							WHERE user='".$_SESSION['user_id']."' AND page='$page' AND day='$today'");
			logDBError (__FILE__, __LINE__, mysql_error());
		}
	}

	function loadLanguageFile ($offset = '') {
	    global $LANG;

        $use_language = $_SESSION['language'];
		if ($use_language == 0) 
		    $use_language = 1;
		
		$lg_res = mysql_query("
		    SELECT filename FROM ".TABLE_PREFIX."languages
		    WHERE lang_id=$use_language");
		logDBError (__FILE__, __LINE__, mysql_error());
		$lg_row = mysql_fetch_array($lg_res);
		$LANG = parse_ini_file($offset."lang/lang_".$lg_row['filename'].".txt");
	}
	
	function addLanguageFile ($offset = '',$name = 'lang') {
	    global $LANG, $logger;
           
        $use_language = $_SESSION['language'];
		if ($use_language == 0) 
		    $use_language = 1;
		
		$lg_res = mysql_query("
		    SELECT filename FROM ".TABLE_PREFIX."languages 
		    WHERE lang_id=$use_language");
		logDBError (__FILE__, __LINE__, mysql_error());
		$lg_row = mysql_fetch_array($lg_res);
		
		$filename = $offset."lang/".$name."_".$lg_row['filename'].".txt";
		if (file_exists($filename)) {
    		$tmp    = parse_ini_file($filename);
            $LANG   = array_merge ($LANG, $tmp); 
        }
        else {
            $logger->log ('language file could not be loaded:', 1);
            $logger->log ($filename, 1);
	    }
	}

	function translate ($text, $args = null, $prevent_js = false) {
	    global $LANG;

        $translation = '';
        $translated  = true;

	    if (isset($LANG[$text])) {
	        if ($args == null)
    	        $translation = $LANG[$text];
	        else {
	            $ret = '';
	            $tmp = '';
	            for ($i=0; $i < count($args); $i++) {
	                $tmp .= '$args['.$i.'],'; 
	            }
	            $tmp = substr($tmp,0,-1);
	            eval ('$ret = sprintf ($LANG[$text], '.$tmp.');');
	            $translation = $ret;
	        }
	    }
	    else {
	        $translation = "~".$text."~";
	        $translated  = false;
	    }    
	    
	    if ($prevent_js) 
	        return $translation;
	    
	    //if (isset ()) {
	    
	        $query = "SELECT count(*) from ".TABLE_PREFIX."languages WHERE loaded_in_db != '0'";
	        $res   = mysql_query ($query);
	        $row   = mysql_fetch_array ($res);
	        
	        if (isset ($_SESSION['onlineedit']) && $_SESSION['onlineedit'] == "true" && $row[0] > 0) {
    	        ($translated) ? $mode = "edit" : $mode = "new";
	            $translation = $translation.
	                "&nbsp;<a href='javascript:languagemanager(\"$text\", \"$mode\");'><font size=1>&circ;</font></a>&nbsp;";
	        }
	    //}
	        
	    return $translation;
	}

	//====================================================================================
	// Groups and stuff:
	//====================================================================================

	function get_all_groups ($id) {
		global $db, $logger;

		$return_array = array();
		
		$res = mysql_query ("SELECT id FROM ".TABLE_PREFIX."gacl_aro WHERE value='$id'");
		$row = mysql_fetch_array ($res);
		$id  = $row['id'];
		
		$query = "SELECT value, name, id
                                FROM ".TABLE_PREFIX."gacl_groups_aro_map 
                                LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON group_id=id
                                WHERE aro_id = $id
                                ORDER BY name";
		$res = mysql_query ($query);
		//$logger->log ($query, 4);
		logDBError (__FILE__, __LINE__, mysql_error());
		
		while ($row = mysql_fetch_array ($res))
			$return_array[] = $row['id'];
			
		return $return_array;
	}



	// eigentlich isadmin_of_group ?
	function is_admin_of_own_group ($id, $group) {
		global $db;
		$user_res = mysql_query ("SELECT * FROM ".TABLE_PREFIX."admin WHERE user='$id' AND grp='$group'");
		logDBError (__FILE__, __LINE__, mysql_error());
		return (mysql_num_rows($user_res) > 0);
	}

	function get_main_group ($id) {
		global $db;
		//$my_groups = get_all_groups($id);
		$main_grp_res = mysql_query ("SELECT grp FROM ".TABLE_PREFIX."users WHERE id='$id'", $db);
		logDBError (__FILE__, __LINE__, mysql_error());
		$main_grp = mysql_fetch_array ($main_grp_res);
        // should not happen...
		//if (($main_grp[0] == 0) OR ($main_grp[0] == ""))
        //	die ("users group equals empty or equals 0");
        return $main_grp[0];
	}

	//====================================================================================
	// History Funktionen
	//====================================================================================

	function history_exists ($user_id, $contact_id, $objekt) {
		global $db;

		$res = mysql_query ("SELECT COUNT(*) FROM ".TABLE_PREFIX."history WHERE
							id='$contact_id' AND Objekt='$objekt'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		if ($row[0] > 0) return true;
		return false;
	}

	// Gibt jetzt zurueck, ob die Auswahl überhaupt angezeigt werden soll
	// siehe options_group_selection
	function echo_group_options ($user_id, $sel_group, $show_private = true) {
		global $db, $language;

		list ($anz, $options, $show) = options_group_selection ($user_id, $sel_group, $show_private);
		// just one entry => no need for 'all' option
		if ($anz == 1) {
		    return array ($options, $show);
		}
		if ($anz > 0) {
			$options .= "<option value='all'";
			if ($sel_group == "all")
				$options .= " selected";
			$options .= ">".translate ("all")."</option>\n";
		}
		return array ($options, $show);
	}

	function options_companies_selection ($user_id, $sel_id) {
        $query = "SELECT * FROM ".TABLE_PREFIX."companies WHERE "; 
		$valid_groups = get_all_groups($user_id);

		$where_query  = "";
		foreach ($valid_groups AS $key => $valid_group)
		    $where_query .= "companies.grp='$valid_group' OR ";

		$query .= "(".substr($where_query,0,-4).") ORDER BY name1";

		$found_me     = false;
            $show_link    = true;
		
		$options_text = "";
		$thisresult = mysql_query ($query);
        while ($thisrow = mysql_fetch_array($thisresult)) {
		    $tmp = $thisrow['name1'];
			if (strlen ($tmp) > 28) $tmp = substr ($tmp, 0, 25)."...";
			$options_text .= "\t\t\t<option value='".$thisrow['company_id']."'";
			if ($sel_id == $thisrow['company_id']) {
				$options_text .= " selected";
				$found_me = true;
			}
			$options_text .= ">$tmp</option>\n";
		}
	
		return array ($options_text, $found_me);
    }

	//==========================================================================
	// Helper functions

	function get_username_by_user_id ($user_id, $reverse = false) {
		$res = mysql_query ("SELECT firstname, lastname FROM ".TABLE_PREFIX."users WHERE id='$user_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		if ($reverse) {
			return $row['lastname'].", ".$row['firstname'];		    
		}
		return $row['firstname']." ".$row['lastname'];
	}

	function get_username_by_login ($login, $reverse = false) {
		$res = mysql_query ("SELECT firstname, lastname FROM ".TABLE_PREFIX."users WHERE login='$login'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		if ($reverse) {
			return $row['lastname'].", ".$row['firstname'];		    
		}
		return $row['firstname']." ".$row['lastname'];
	}

	function get_useremail_by_user_id ($user_id) {
		$res = mysql_query ("SELECT email FROM ".TABLE_PREFIX."users WHERE id='$user_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		return $row['email'];
	}

	function get_contactname_by_id ($contact_id) {
		$res = mysql_query ("SELECT vorname, nachname FROM ".TABLE_PREFIX."contacts WHERE contact_id='$contact_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		return $row['vorname']." ".$row['nachname'];
	}

	function get_companyname_by_id ($company_id) {
		$res = mysql_query ("SELECT name1, name2 FROM ".TABLE_PREFIX."companies
							 WHERE company_id='$company_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$row = mysql_fetch_array ($res);
		return trim($row['name1']." ".$row['name2']);
	}

	function convert_date_from_mysql ($datum) {
		if (strlen(trim($datum)) == 0) return "";
		$datum = explode (" ", $datum); // z.B. "2002-12-12 11:13" oder nur "2002-12-12";
		$datum = $datum[0]; 	        // jetzt nur noch "2002-12-12";
		$datum = explode ("-", $datum);
		return $datum[2].".".$datum[1].".".$datum[0];

	}

	// Skin handling:
	function get_skin_css_path () {
	    $query = "SELECT skin FROM ".TABLE_PREFIX."user_details WHERE user_id='".$_SESSION['user_id']."'";
		$skin_res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error());
		$skin_row = mysql_fetch_array ($skin_res);
		$css_path = "css/";
		if (($skin_row[0] <> "") AND ($skin_row[0] <> 0)) {
			$skin_res = mysql_query ("SELECT * FROM ".TABLE_PREFIX."skins WHERE id='".$skin_row[0]."'");
			logDBError (__FILE__, __LINE__, mysql_error());
			$skin_row = mysql_fetch_array ($skin_res);
			$css_path = $skin_row['css_path'];
		}
		return $css_path;
	}

	function get_skin_img_path () {
		$skin_res = mysql_query ("SELECT skin FROM ".TABLE_PREFIX."user_details WHERE user_id='".$_SESSION['user_id']."'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$skin_row = mysql_fetch_array ($skin_res);
		$img_path = "img/";
		if (($skin_row[0] <> "") AND ($skin_row[0] <> 0)) {
			$skin_res = mysql_query ("SELECT * FROM ".TABLE_PREFIX."skins WHERE id='".$skin_row[0]."'");
			logDBError (__FILE__, __LINE__, mysql_error());
			$skin_row = mysql_fetch_array ($skin_res);
			$img_path = $skin_row['img_path'];
		}
		return $img_path;
	}

	// tree functions

	function db_tree_to_js_tree ($table, $rootname, $rooturl, $roottarget, $extra, $parent_id) {
		global $user_id;
die (__FILE__);
		if ($rooturl == "")
			$rooturl = "null";
		else
			$rooturl = "'".$rooturl."'";
		if ($roottarget == "")
			$roottarget = "null";
		else
			$roottarget = "'".$roottarget."'";

		$ret = "['$rootname', $rooturl, $roottarget, $extra";
echo "SELECT * FROM ".TABLE_PREFIX."$table WHERE owner='$user_id' AND parent='$parent_id' ORDER BY is_dir";
		$res = mysql_query ("SELECT * FROM $table WHERE owner='$user_id' AND parent='$parent_id' ORDER BY is_dir");
		logDBError (__FILE__, __LINE__, mysql_error());
		while ($row = mysql_fetch_array ($res)) {
			  if ($row['is_dir'] == "false") {
				   if ($row['view_as'] == "new_window")
					   $ret .= "\n['&bull; ".$row['name']."', '".$row['url']."', '_new'],";
				   else
				   $ret .= "\n['&bull; ".$row['name']."', null, null],";
			  }
			  else {
				   $ret .= "\n".db_tree_to_js_tree ("bookmarks", " <img src=\'img/bookmark.gif\' border=0 align=middle> ".$row['name'], "", "", "", $row['id']);
				   $ret .= ",";
			  }
		}
		$ret = substr ($ret, 0,-1);
		$ret .= "]";
		return $ret;
	}


	function db_tree_to_js_tree_manage ($table, $rootname, $rooturl, $roottarget, $extra, $parent_id) {
		global $user_id;
die (__FILE__);
		if ($rooturl == "")
			$rooturl = "null";
		else
			$rooturl = "'".$rooturl."'";
		if ($roottarget == "")
			$roottarget = "null";
		else
			$roottarget = "'".$roottarget."'";

		$anz_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."$table WHERE parent='$parent_id' AND owner='$user_id' AND is_dir='false'";

		$anz_res = mysql_query ($anz_query);
		logDBError (__FILE__, __LINE__, mysql_error());
		$anz_row = mysql_fetch_array ($anz_res);
		$ret = "['$rootname (".$anz_row[0].")', $rooturl, $roottarget, $extra";

		$res = mysql_query ("SELECT * FROM ".TABLE_PREFIX."$table WHERE owner='$user_id' AND parent='$parent_id' AND is_dir='true' ORDER BY is_dir, name");
		logDBError (__FILE__, __LINE__, mysql_error());

		while ($row = mysql_fetch_array ($res)) {
			$ret .= "\n".db_tree_to_js_tree_manage ($table, " <img src=\'img/bookmark.gif\' border=0 align=middle> <b>".$row['name']."</b>", "javascript:set_aktuell(".$row['id'].",\'".$row['name']."\')", "", "", $row['id']);
			$ret .= ",";
		}
		$ret = substr ($ret, 0,-1);

		$ret .= "]";

		return $ret;
	}

    function user_with_no_group_to_js_tree () {
    	global $img_path;
		die (__FILE__.__LINE);
die (__FILE__);
        $show_name  = "Deaktivierte User";
		$ret  = "['<b>$show_name</b>', null, '', \n";

        $query = "SELECT id
			FROM ".TABLE_PREFIX."users
			LEFT JOIN ".TABLE_PREFIX."membership
			ON users.id=membership.user
			WHERE ISNULL(membership.user)";
		$res   = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error());
		
        if (mysql_num_rows($res) == 0) {
			$ret .= "['keine deaktivierten Benutzer gefunden',null, null]]";
        }
        else {
			while ($row = mysql_fetch_array ($res)) {
				$ret .= "['<img src=\'".$img_path."/user.gif\' align=top border=0>".get_username_by_user_id ($row['id'])."', null, null,\n";
        	    $ret .= "  ['[manage groups for this user]', 'manage_groups2.php?action=show_groups&use_user=".$row['id']."&referrer=groupstructure2.php', '_blank'],\n";
				$ret .= "  ['[edit this user]',              'manage_groups.php?action=edit_user&use_user=".$row['id']."&use_group=$start_group&referrer=groupstructure2.php', '_blank'],\n";
				$ret .= "  ['[delete this user]',            'manage_users.php?action=delete_user&use_user=".$row['id']."&referrer=groupstructure2.php', '_blank']\n";
				$ret .= "],\n";
        	}
	        $ret = substr ($ret, 0,-2);
			$ret .= "]";
        }


        $ret = substr ($ret, 0,-1);
		$ret .= "]";
		return $ret;

    }

	function groups_to_js_tree ($start_group) {
		global $user_id, $img_path;
die (__FILE__);
        if ($start_group == 0)
        	return user_with_no_group_to_js_tree();

		$name_query = "SELECT alias,name FROM ".TABLE_PREFIX."groups WHERE id='$start_group'";
		$name_res   = mysql_query ($name_query);
		logDBError (__FILE__, __LINE__, mysql_error());
		$name_row   = mysql_fetch_array ($name_res);
		$show_name  = $name_row['alias'];
		if ($show_name == "") $show_name = $name_row['name'];
		$ret  = "['<b>$show_name</b>', null, '', \n";
		$ret .= "['[".translate("add user to group", array ($show_name))."]',   'manage_groups.php?action=add_user&use_group=$start_group&referrer=groupstructure2.php', '_blank'],\n";
		$ret .= "['[".translate("rename group")."]', 'manage_groups.php?action=rename_group&use_group=$start_group&referrer=groupstructure2.php', '_blank'],\n";
		$ret .= "['[".translate("delete group")."]', 'manage_groups.php?action=delete_group&use_group=$start_group&group&referrer=groupstructure2.php', '_blank'],\n";

		$members = get_members_of_groups (array ($start_group));
		foreach ($members AS $key => $member) {
			$ret .= "['<img src=\'".$img_path."/user.gif\' align=top border=0>".get_username_by_user_id ($member)."', null, null,\n";
			$ret .= "['[".translate("new group below")."]',       'manage_groups.php?action=add_group&use_user=$member&referrer=groupstructure2.php', '_blank'],\n";
			$ret .= "['[".translate("manage users groups")."]',   'manage_groups2.php?action=show_groups&use_user=$member&referrer=groupstructure2.php', '_blank'],\n";
			$ret .= "['[".translate("edit user")."]',             'manage_groups.php?action=edit_user&use_user=$member&use_group=$start_group&referrer=groupstructure2.php', '_blank'],\n";
			$ret .= "['[".translate("delete user")."]',           'manage_users.php?action=delete_user&use_user=$member&referrer=groupstructure2.php', '_blank'],\n";

            $groups = get_all_groups ($member);
            foreach ($groups AS $key => $group) {
				if ($group != $start_group) {
			        $ret .= "['<b>".show_group_alias ($group)."</b>', 'javascript:set_start_group2(".$group.")', 'main'],\n";               		
			    }
			}

			
			$ret = substr ($ret, 0,-1);

			$ret .= "],\n";
		}
		$ret = substr ($ret, 0,-1);
		$ret .= "]";
		$ret = substr ($ret, 0,-1);
		$ret .= "]";
		return $ret;
	}

	function acl_groups_to_js_tree ($start_group) {
		global $user_id, $img_path;
die (__FILE__);
        if ($start_group == 0)
        	return user_with_no_group_to_js_tree();

		$name_query = "SELECT alias,name FROM ".TABLE_PREFIX."groups WHERE id='$start_group'";
		$name_res   = mysql_query ($name_query);
		logDBError (__FILE__, __LINE__, mysql_error());
		$name_row   = mysql_fetch_array ($name_res);
		$show_name  = $name_row['alias'];
		if ($show_name == "") $show_name = $name_row['name'];
		$ret  = "['<b>$show_name</b>', null, '', \n";
		$ret .= "['[".translate("add user to group", array ($show_name))."]',   'manage_groups.php?action=add_user&use_group=$start_group&referrer=groupmanager.php', '_blank'],\n";
		$ret .= "['[".translate("rename group")."]', 'manage_groups.php?action=rename_group&use_group=$start_group&referrer=groupmanager.php', '_blank'],\n";
		$ret .= "['[".translate("delete group")."]', 'manage_groups.php?action=delete_group&use_group=$start_group&group&referrer=groupmanager.php', '_blank'],\n";

		$members = get_members_of_groups (array ($start_group));
		foreach ($members AS $key => $member) {
			$ret .= "['<img src=\'".$img_path."/user.gif\' align=top border=0>".get_username_by_user_id ($member)."', null, null,\n";
			$ret .= "['[".translate("new group below")."]',       'manage_groups.php?action=add_group&use_user=$member&referrer=groupmanager.php', '_blank'],\n";
			$ret .= "['[".translate("manage users groups")."]',   'manage_groups2.php?action=show_groups&use_user=$member&referrer=groupmanager.php', '_blank'],\n";
			$ret .= "['[".translate("edit user")."]',             'manage_groups.php?action=edit_user&use_user=$member&use_group=$start_group&referrer=groupmanager.php', '_blank'],\n";
			$ret .= "['[".translate("delete user")."]',           'manage_users.php?action=delete_user&use_user=$member&referrer=groupmanager.php', '_blank'],\n";

            $groups = get_all_groups ($member);
            foreach ($groups AS $key => $group) {
				if ($group != $start_group) {
			        $ret .= "['<b>".show_group_alias ($group)."</b>', 'javascript:set_start_group2(".$group.")', 'main'],\n";               		
			    }
			}

			
			$ret = substr ($ret, 0,-1);

			$ret .= "],\n";
		}
		$ret = substr ($ret, 0,-1);
		$ret .= "]";
		$ret = substr ($ret, 0,-1);
		$ret .= "]";
		return $ret;
	}


	function telnr2identifier ($value) {
        $value = str_replace (array ("(",")"," ","\t","-","+", "/","\\", ",","|", "<", ">", "[", "]", "_"),
            				  array ("", "", "", "",  "", "",  "", "",   "", "",  "",  "",  "",  "",  ""),
            						  $value);
    	return $value;
    }

	function logDBError ($file, $line, $msg, $query = "") {
		global $logger, $logger;
		if (strlen ($msg) > 0) {
			$logger->log ("MySQL Error in ".$file." (".$line."):",1);
			$logger->log ($msg,1);
			if ($query != "") {
				$msg = "[User ID ".$_SESSION['user_id']."] ".$msg;
				$logger->log ($query, 1);
				//if (function_exists('debug_backtrace'))
				//    $logger->log (print_r (debug_backtrace(), true), 1);
				
			}
			/*if ($stop_exec) die ("
				Execution stopped because of serious error.
				The error has been logged. For further details, have a look
				in your logging outoupt.");
        	*/
		}
	}

    function logSpecial ($filename, $msg) {
        $fh = fopen ($filename, "ab");
        fwrite ($fh, $msg);
        fclose ($fh);   
    }

	function logMsg ($msg, $prio = 1) {
		global $logger, $user_id;
		if (strlen ($msg) > 0) {
			$msg = "[User ID $user_id] ".$msg;
			//$logger->log ($msg,$prio);
		}
	}
	
	function DBLogger ($ident, $msg,
	                   $predecessor_id = 0) {
	    
	    if (trim ($msg) == "") return;
	    // handle long messages
	    $maxlength = 200;
        if (strlen($msg) > $maxlength) {
            $pre_id = DBLogger ($ident, substr ($msg, 0, $maxlength), $predecessor_id);
            $pre_id = DBLogger ($ident, substr ($msg, $maxlength),$pre_id);    
            return $pre_id;
        }    
        $sql = "INSERT INTO ".TABLE_PREFIX."logtable (predecessor, category, ident, message)
                VALUES ('$predecessor_id',
                        '',
                        '".mysql_escape_string($ident)."', 
                        '".mysql_escape_string($msg)."')";
       //echo $sql;
	    mysql_query ($sql);
	    logDBError (__FILE__, __LINE__, mysql_error());
	    return mysql_insert_id();
	}
	
	function getDBLoggerEntry ($id) {
	    $sql = "SELECT message FROM ".TABLE_PREFIX."logtable 
	            WHERE id='$id'";    
        $res = mysql_query ($sql);
	    logDBError (__FILE__, __LINE__, mysql_error());
        $row  = mysql_fetch_array ($res);
        $msg  = $row['message'];
        $sql2 = "SELECT id, message FROM ".TABLE_PREFIX."logtable 
	            WHERE predecessor='$id'";    
        $res2 = mysql_query ($sql2);
	    logDBError (__FILE__, __LINE__, mysql_error());
        while ($row2 = mysql_fetch_array($res2)) {
            $msg .= getDBLoggerEntry ($row2['id']);    
        }
        return $msg;
	}    
	
	function deleteDBLoggerEntry ($id) {
	    $sql = "DELETE FROM ".TABLE_PREFIX."logtable 
	            WHERE id='$id'";    
        $res = mysql_query ($sql);
	    logDBError (__FILE__, __LINE__, mysql_error());
        $sql2 = "SELECT id FROM ".TABLE_PREFIX."logtable 
	            WHERE predecessor='$id'";    
        $res2 = mysql_query ($sql2);
	    logDBError (__FILE__, __LINE__, mysql_error());
        while ($row2 = mysql_fetch_array($res2)) {
            deleteDBLoggerEntry ($row2['id']);    
        }
	}    

	function insertStat ($user_id, $group, $typ, $entry = 0) {
	   $query = "INSERT INTO ".TABLE_PREFIX."stats (user, grp, timestamp, entry_type, 
	   						assigned_entry)
	                 VALUES ($user_id,$group,now(),'$typ','$entry')";
       //die ($query);
	   mysql_query ($query);
       logDBError (__FILE__, __LINE__, mysql_error(), $query);
	}

    function restricted_access (&$gacl, $section, $value, $page) {	

        global $img_path;
        
	    $access_for = '<b>'.translate ('access permitted for').'</b><br>';
        $users           = get_users_with_access ($gacl,$section,$value,'Person');
        foreach ($users AS $tmp =>$key) 
            $access_for .= get_username_by_user_id ($key)."<br>";
    
        if ($value != "Permissions") {
            if ($gacl->acl_check($section,'Permissions','Person',$_SESSION['user_id']))
                $access_for .= "<a href=\'acl_list.php?section_value=$section\'>Edit Permissions for this page</a>";    
	    }
	    
        $headline        = "<img src='".$img_path."admin.gif' align=top>&nbsp;&nbsp;";
    	$headline       .= "<span class='admin'>".$page."</span>";
        $headline_right .= '<a href="javascript:void(0);" 
                        onclick="return overlib(\''.$access_for.'\', STICKY, CAPTION,
                        \'&nbsp;'.$page.'\', LEFT);" onmouseout="nd();"><font color=red>[Restricted Acceess!]</font></a>';
        return array ($headline, $headline_right);
    }
    
    
    function access_option  ($key, $val, $selected, &$found) {
        ($selected == $key) ? $sel   = "selected" : $sel   = "";
        ($selected == $key) ? $found = true       : $found = false;
        return "<option value='$key' $sel>".translate($val, null, true)."</option>\n";         
    }
        
    function access_options ($selected = "-rwxrw----", $stop_when_found = false) {
    
        $options  = "";
        $found    = false;
        $cnt      = 0;
        $alt_value = '-rwx------';
        
	    $query = "
			SELECT * 
			FROM ".TABLE_PREFIX."access_options 
			WHERE mandator=".$_SESSION['mandator']."
			ORDER BY identifier";
	    $res   = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        while ($row = mysql_fetch_array ($res)) {
            $options .= access_option ($row['identifier'], $row['name'], $selected, $found);
            $alt_value = $row['identifier'];
            $cnt++;
        }    
        return array ($options, $cnt, $alt_value);
    }  
    
    function get_access_icon ($level) {
        $query = "SELECT icon FROM ".TABLE_PREFIX."access_options WHERE identifier='$level'";
        $res   = mysql_query ($query);   
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row   = mysql_fetch_array($res);
        return $row['icon'];        
    }      
    
    //function get_entries_for_primary_key ($table, $primary_col, $key) {
    function get_entries_for_primary_key ($table, $key_value_pairs) {
        assert ('$table != ""');
        $where = '';
        assert ('count($key_value_pairs) > 0');
        foreach ($key_value_pairs AS $key => $value)
            $where .= $key."='$value' AND ";
        $where = substr ($where,0,-4);
        $query = "SELECT * FROM ".TABLE_PREFIX."$table WHERE ".$where;
        //echo $query;
        $res = mysql_query ($query);   
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        return mysql_fetch_assoc ($res);
    }    
    
    function get_entries_for_key ($table, $key_value_pairs, $column) {
        assert ('$table != ""');
        $where = '';
        assert ('count($key_value_pairs) > 0');
        foreach ($key_value_pairs AS $key => $value)
            $where .= $key."='$value' AND ";
        $where = substr ($where,0,-4);
        $query = "
			SELECT $column 
			FROM ".TABLE_PREFIX."$table 
			WHERE ".$where."
			ORDER BY $column
		";
        //echo $query;
        $res = mysql_query ($query);   
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $arr = array ();
        while ($row = mysql_fetch_assoc ($res)) {
        	$arr[] = $row[$column];
        }	
        return $arr;
    }  
     
    function update_history ($type, $table, $id, $key_value_pairs, &$old_values, $omit = array()) {

        global $db_name;
        
        $new_values = get_entries_for_primary_key ($table, $key_value_pairs);
        $fields     = mysql_list_fields ($db_name, TABLE_PREFIX.$table);
        $columns    = mysql_num_fields($fields); 

        for ($i = 0; $i < $columns; $i++) { 
            $field = mysql_field_name($fields, $i); 
            
            if (!isset ($old_values[$field])) 
                $old_values[$field] = '';
            if ($new_values[$field] != $old_values[$field] && !in_array($field, $omit)) {
                //echo $field.": ".$new_values[$field]." != ".$old_values[$field]."<br>";
                $hist_query = "
                    INSERT INTO ".TABLE_PREFIX."history (
                        object_type, 
                        object_id, 
                        user_id, 
                        col, 
                        old_value,
                        new_value) 
                    VALUES
                      ('$type',
                       '$id',
                       '".$_SESSION['user_id']."', 
                       '$field', 
                       '".$old_values[$field]."',
                       '".$new_values[$field]."'
                    )";
                mysql_query ($hist_query);
                logDBError (__FILE__, __LINE__, mysql_error(), $hist_query);
            }
        }
    }    

    function update_history_array ($type, $table, $id, $key_value_pairs, &$old_values, $column, $omit = array()) {

        global $db_name;
        
		$new_values = get_entries_for_key ($table, $key_value_pairs, $column);
       	
       	// added
       	$added_elements = array_diff ($new_values,$old_values);
       	foreach ($added_elements AS $key => $element) {
			$hist_query = "
				INSERT INTO ".TABLE_PREFIX."history (
				    object_type, 
				    object_id, 
				    user_id, 
				    col, 
				    old_value,
				    new_value) 
				VALUES
				  ('$type',
				   '$id',
				   '".$_SESSION['user_id']."', 
				   '$column', 
				   '',
				   '$element'
				)";
			mysql_query ($hist_query);
			logDBError (__FILE__, __LINE__, mysql_error(), $hist_query);
       	}
       	// deleted
       	$deleted_elements = array_diff ($old_values, $new_values);
       	foreach ($deleted_elements AS $element) {
			$hist_query = "
				INSERT INTO ".TABLE_PREFIX."history (
				    object_type, 
				    object_id, 
				    user_id, 
				    col, 
				    old_value,
				    new_value) 
				VALUES
				  ('$type',
				   '$id',
				   '".$_SESSION['user_id']."', 
				   '$column', 
				   '$element',
				   ''
				)";
			mysql_query ($hist_query);
			logDBError (__FILE__, __LINE__, mysql_error(), $hist_query);
       	}
    }
      
          
    function get_workflow_options ($reference, $old_state) {
        $ret   = '';
        $query = "
            select distinct
                t.reference, user, isdefault, state_new, s.name AS name
                from ".TABLE_PREFIX."transitions t 
            LEFT JOIN ".TABLE_PREFIX."states s ON t.state_new=s.status
            WHERE
                t.reference='$reference' 
                and state_old=$old_state
                and s.reference='".$reference."'
				and t.mandator=".$_SESSION['mandator']."
                and (
                (grp=0 AND user=0) OR
                (user=".$_SESSION['user_id'].") OR
                (".get_all_groups_or_statement($_SESSION['user_id'], "t").")
                )	
                order by state_new
               ";
        //echo "***".$query;       
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);

        if (mysql_num_rows ($res) == 0) {
            //return "<option value='-1'>".translate ('undefined')."</option>\n";    
            return null; //"<option value='-1'>".translate ('undefined')."</option>\n";    
        }    

        /*$state_old_res = mysql_query ("SELECT name FROM states WHERE status=$old_state");
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $state_old_row = mysql_fetch_array ($state_old_res);
        $state_old_name = translate ($state_old_row['name']);*/

        while ($row = mysql_fetch_array ($res)) {
            //$text = $state_old_name." >>> ".translate($row['name']);
            $ret .= "<option value='".$row['state_new']."'>".translate($row['name'], null, true)."</option>\n";    
        }    
        return $ret;       
    }    
    
    function get_state_name ($type, $id) {
        $query = "
            select 
                name
            FROM ".TABLE_PREFIX."states
            WHERE
                reference='$type' AND status=$id AND mandator=".$_SESSION['mandator']."
               ";
        //die ($query);
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);

        if (mysql_num_rows ($res) == 0) {
            //$easy("Error trying to get status name for ($type,$id)",4);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            if ($id == -1) { // predefined state for "undefined"
            	$query = "
						INSERT INTO ".TABLE_PREFIX."states
							(reference, status, name, mandator)
						VALUES (
							'$type',
							'-1',
						    'undefined',
							".$_SESSION['mandator']."
						)
					";
				$res = mysql_query ($query);
				logDBError (__FILE__, __LINE__, mysql_error(), $query);
            }	
            return "undefined";
        }    
        $row = mysql_fetch_array($res);

        return translate ($row['name']);       
    }

    function get_state_options ($reference, $sel_id = '') {
    
        $ret   = '';
        $cnt   = 1;
        
        $query = "
            select 
                *
            FROM ".TABLE_PREFIX."states 
            WHERE reference='$reference' AND mandator=".$_SESSION['mandator']."
            ORDER BY name
            ";
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $ret .= "<option value='' selected>".translate ('all')."</option>\n";    
        while ($row = mysql_fetch_array ($res)) {
            $text = translate ($row['name']);
            ($row['status'] == $sel_id) ? $sel = "selected" : $sel = "";
            $ret .= "<option value='".$row['status']."' $sel>".$text."</option>\n";    
        	$cnt++;
        }
        return array ($ret, $cnt);
    }
    
    function module_enabled ($name) {
        $query = "SELECT enabled FROM ".TABLE_PREFIX."components WHERE module_name='$name'";
        $res   = mysql_query ($query);    
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row   = mysql_fetch_array ($res);
        if ((bool)$row['enabled']) return true;
        return false;
    }    
    
    function change_profile_allowed ($user_id = null) {
    	
    	if (is_null ($user_id)) $user_id = $_SESSION['user_id'];
    	
        $query = "SELECT may_change_profile 
				  FROM ".TABLE_PREFIX."user_details 
				  WHERE user_id=$user_id";
        $res   = mysql_query ($query);    
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row   = mysql_fetch_array ($res);
        if ((bool)$row['may_change_profile']) return true;
        return false;
    }    

    function get_all_groups_or_statement ($id, $table = "mi") {
    	$groups = get_all_groups ($id);
		$add_query = "(";
		$found = false;
		for ($i=0; $i < count($groups); $i++) {
			$add_query .= $table.".grp='$groups[$i]' OR ";
         	$found = true;
		}
		//if ($found)
			return substr ($add_query, 0, -4).")";
    	//else
		//	return " AND (1=2) ";
	}

	function get_group_alias ($group_id) {
		global $language;

		$group_res = mysql_query ("
		    SELECT name
            FROM ".TABLE_PREFIX."gacl_aro_groups 
            WHERE ".TABLE_PREFIX."gacl_aro_groups.id='$group_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$group_row = mysql_fetch_array ($group_res);
		if (isset ($group_row['alias']) && $group_row['alias'] != '') 
		    return $group_row['alias'];
    	return $group_row['name'];
	}
    
    function get_country_name ($id) {
        $query = "SELECT country FROM ".TABLE_PREFIX."countries WHERE id='$id'";      
        $res   = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row   = mysql_fetch_array ($res);
		return $row[0];
    }
        
    function get_history_count ($type, $id) {
        if (is_null($id)) return 0;
        if ($id == '')    return 0;
        $query = "
            SELECT COUNT(*) FROM ".TABLE_PREFIX."history
            WHERE object_type='$type' and object_id=$id";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array ($res);
		return $row[0];
    }    
    
    function get_reference_count ($type, $id) {
        if (is_null($id)) return 0;
        if ($id == '')    return 0;
        $query = "
            SELECT COUNT(*) FROM ".TABLE_PREFIX."refering
            WHERE from_object_type='$type' AND 
                  from_object_id=$id AND
                  to_object_type!='collection' AND
                  ref_type = 1";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array ($res);
		return $row[0];
    }    

    function get_collections_count ($type, $id) {
        if (is_null($id)) return 0;
        if ($id == '')    return 0;
        $query = "
            SELECT COUNT(*) FROM ".TABLE_PREFIX."refering
            WHERE from_object_type='$type' AND 
                  from_object_id=$id AND
                  to_object_type='collection'";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array ($res);
		return $row[0];
    }    

    function get_attachments_count ($type, $id) {
        if (is_null($id)) return 0;
        if ($id == '')    return 0;
        $query = "
            SELECT COUNT(*) FROM ".TABLE_PREFIX."refering
            WHERE from_object_type='$type' AND 
                  from_object_id=$id AND
                  ref_type=2";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array ($res);
		return $row[0];
    }    

    function get_history ($type, $id) {
        $query = "
            SELECT user_id, tstamp, col, old_value, new_value FROM ".TABLE_PREFIX."history
            WHERE object_type='$type' and object_id=$id
            ORDER BY tstamp DESC";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		return $res;
    }    

    function create_quicklink ($type, $id, $name, $link, $sticky = false) {
        
        // make sure quicklink does not exist yet
        $query = "
            SELECT COUNT(*) FROM ".TABLE_PREFIX."quicklinks 
            WHERE object_type='$type' AND object_id=$id
            ";
        $res = mysql_query ($query);    
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array ($res);
		if ($row[0] > 0)
		    return;
        
        $query = "  
                INSERT INTO ".TABLE_PREFIX."quicklinks (
                    object_type,
                    object_id,
                    owner, 
                    name,
                    link,
                    sticky
                ) VALUES (
                    '$type',
                    '$id',
                    '".$_SESSION['user_id']."',
                    '$name',
                    '$link',
                    '".(int)$sticky."'
                )";
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
    }    
    
    function get_quicklinks () {
        $query = "
            SELECT object_type, object_id, name, link, sticky, followup from ".TABLE_PREFIX."quicklinks 
            WHERE owner=".$_SESSION['user_id']."
            ORDER BY name";      
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		return $res;
    }    

    function get_memos ($type, $id) {
        $query = "
            SELECT 
            	object_type,
            	object_id,
            	description,
            	creator,
            	owner,
            	grp,
            	state, 
            	created,
            	last_changer,
            	last_change,
            	access_level
            FROM ".TABLE_PREFIX."refering 
            LEFT JOIN ".TABLE_PREFIX."metainfo ON refering.to_object_type=".TABLE_PREFIX."metainfo.object_type AND
                                  ".TABLE_PREFIX."refering.to_object_id=".TABLE_PREFIX."metainfo.object_id 
            WHERE from_object_type='contact' AND
            		  from_object_id=1 AND
            	    ref_type=2
            ORDER BY to_object_type; 
        ";       
        
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		return $res;
    }    

    function get_help_link ($img_path, $about) {
        $headline  = '';
        $headline .= "&nbsp;<a href='index.php?command=help&about=$about' target='_blank'>";
    	$headline .= "<img src='".$img_path."help.gif' border=0 align=top title='".translate ('click for help', null, true)."'></a>&nbsp;";

        return $headline;
    }

    function get_extern_help_link ($img_path, $page_id) {
        $headline  = '';
        $headline .= "&nbsp;<a href='http://217.172.179.216/evandor/html/index.php?id=$page_id' target='_blank'>";
    	$headline .= "<img src='".$img_path."help.gif' border=0 align=top title='".translate ('click for help', null, true)."'></a>&nbsp;";

        return $headline;
    }

    function get_search_link ($img_path, $about, $value = "") {
        $headline  = '';
        $headline .= '<input name="search" size=10 value="'.$value.'">&nbsp;';
        $headline .= "<a href='javascript:search (\"search_notes\");'>";
        $headline .= "<img src='".$img_path."search.gif' border=0 align=top title='".translate ('search entries', null, true)."'></a>&nbsp;";
        //$headline .= "<button name='search_button' type=submit value='search' style='border:0px;'>";
        //$headline .= "<img src='".$img_path."search.gif' border=0 align=top></a>&nbsp;";
        //$headline .= "</button>";
        
        
        return $headline;
    }

    function user_may_delete ($owner, $grp, $level, $use_user =  null) {
        //echo "Owner: ".$owner." - Group: ".$grp." - Level: ".$level."<br>";
        if (is_null($use_user)) $use_user = $_SESSION['user_id'];
        
        // user is owner?
        if ($owner == $use_user) 
            return true;
        
        // user has access to group?
        $groups = get_all_groups($use_user);
        if (in_array($grp, $groups)) {
            $flag = substr ($level, 6,1);
            if ($flag == "x") return true;   
        }   
        
        // deletable by all?
        $flag = substr ($level, 9,1);
        if ($flag == "x") return true;   
        
        return false;
    }    

    function user_may_edit ($owner, $grp, $level, $use_user = null) {

        if (is_null($use_user)) $use_user = $_SESSION['user_id'];

        // user is owner?
        if ($owner == $use_user) 
            return true;
        
        // user has access to group?
        $groups = get_all_groups($use_user);
        if (in_array($grp, $groups)) {
            $flag = substr ($level, 5,1);
            if ($flag == "w") return true;   
        }   
        
        // editable by all?
        $flag = substr ($level, 8,1);
        if ($flag == "w") return true;   
        
        return false;
    }    

    function user_may_read ($owner, $grp, $level, $use_user = null) {

        if (is_null($use_user)) $use_user = $_SESSION['user_id'];

        // user is owner?
        //if ($owner == $_SESSION['user_id']) 
        if ($owner == $use_user) 
            return true;
        
        // user has access to group?
        $groups = get_all_groups($use_user);
        if (in_array($grp, $groups)) {
            $flag = substr ($level, 4,1);
            if ($flag == "r") return true;   
        }   
        
        // readable by all?
        $flag = substr ($level, 7,1);
        if ($flag == "r") return true;   
        
        return false;
    }    
    	
    function set_defaults ($params = null) {
        if (is_null ($params)) $params = $_REQUEST;
        
        if (isset ($params['make_access_default']) && $params['make_access_default'] == "on" && isset($params['access'])) {
            $query = "
                UPDATE ".TABLE_PREFIX."user_details 
                SET default_access='".$params['access']."' 
                WHERE user_id=".$_SESSION['user_id'];
            $res = mysql_query ($query);
	       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
        }    
        if (isset ($params['make_group_default']) && $params['make_group_default'] == "on" && isset($params['use_group'])) {
            $query = "
                UPDATE ".TABLE_PREFIX."user_details 
                SET default_group='".$params['use_group']."' 
                WHERE user_id=".$_SESSION['user_id'];
            $res = mysql_query ($query);
	       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
        }    
    }

    function get_defaults () {
        $query = "SELECT default_group, default_access FROM ".TABLE_PREFIX."user_details WHERE user_id=".$_SESSION['user_id'];
        $res = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);        
       	$row = mysql_fetch_array($res);
       	
       	// default in user_details table may be 0, get something senseful
       	if ($row['default_group'] == 0) {
       	    $row['default_group'] = get_main_group ($_SESSION['user_id']);    
       	}    
       	
       	return array ($row['default_group'], $row['default_access']);
    }    
    
    function substitute ($str, $substitutes) {
		foreach ($substitutes AS $key => $val) {
    		$str = str_replace ($key, $val, $str);
		}
		return $str;
    } 	
    
    function getMandators () {
    	
    	$mandators = array ();
    	
    	if (isset ($_REQUEST['mandator'])) {
    		$mandators[0]['key']  = $_REQUEST['mandator'];
    		$mandators[0]['name'] = 'default';
    		return array(1, $mandators);    		
    	}
    	
    	$count = 0;
    	$query = "SELECT * FROM ".TABLE_PREFIX."mandator";
        $res   = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
		
		while ($row = mysql_fetch_array($res)) {
			$mandators[$count]['key']  = $row['mandator_id'];
    		$mandators[$count]['name'] = $row['name'];
			$count++;
		}	    	
    	return array ($count, $mandators);	
    }
    
    function getMandatorTreeRoot () {
    	$query = "
			SELECT tree_root FROM ".TABLE_PREFIX."mandator
			WHERE mandator_id=".$_SESSION['mandator']."
			";
        $res   = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array($res);
    	return $row[0];
    }
    
    function userMayUseMandator ($user_id, $mandator) {
    	$result = false;
    	
    	$query = "
			SELECT COUNT(*) FROM ".TABLE_PREFIX."user_mandator
			WHERE mandator_id=$mandator AND
                  user_id = $user_id
			";
        $res   = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array($res);
		if ($row[0] > 0) $result = true;
		
    	return $result;
    }	
    
    function getMandatorCustomCode ($identifier) {
        $result = '';
    	
    	$query = "
			SELECT $identifier FROM ".TABLE_PREFIX."mandator
			WHERE mandator_id=".$_SESSION['mandator']." 
			";
        $res   = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array($res);
        
        return $row[0];
    }    
    
    function getMandatorName ($mandator_id) {
    	
    	$query = "
			SELECT name FROM ".TABLE_PREFIX."mandator
			WHERE mandator_id=".$mandator_id." 
			";
        $res   = mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row = mysql_fetch_array($res);
        
        return $row['name'];
    }    

    // return options of Mandators the current user has access to
    function getMandatorsOptions ($use_user = null) {
        $ret   = '';
        $cnt   = 1;
        
        $query = "
            select distinct
                m.mandator_id, m.name
            FROM ".TABLE_PREFIX."user_mandator um
            LEFT JOIN ".TABLE_PREFIX."mandator m ON m.mandator_id = um.mandator_id
            WHERE user_id=".$_SESSION['user_id']."
            ";
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        while ($row = mysql_fetch_array ($res)) {
        	$sel = "";
        	if (!is_null ($use_user)) {
	            $sel_query = "
					SELECT count(*) 
					FROM ".TABLE_PREFIX."user_mandator 
					WHERE user_id=".$use_user." AND mandator_id=".$row['mandator_id'];
				$sel_res = mysql_query ($sel_query);
		        logDBError (__FILE__, __LINE__, mysql_error(), $query);
		        $sel_row = mysql_fetch_array($sel_res);
        		if ($sel_row[0] > 0)
        			$sel = "selected";
        	}
            $ret .= "<option value='".$row['mandator_id']."' $sel>".$row['name']."</option>\n";    
        	$cnt++;
        }
        return array ($ret, $cnt);
        
    }    
    
    function getInstalledApplication ($path = "") {
    	
    	$app_file = $path."app_".APPLICATION.".ini";
    	
    	if (file_exists($app_file)) {
            $app = parse_ini_file ($app_file);
            $name           = $app['name'];
            $version_main   = $app['version_main'];
            $version_sub    = $app['version_sub'];
            $version_detail = $app['version_detail'];    
        }    
        else {
            echo $app_file." does not exist!";
            die ();  
        }    
        
        return array ($name, $version_main.".".$version_sub.".".$version_detail);
    }    

    function copyDatagridDefinitionForm ($datagrid_name) {
		echo "<form action='index.php'>\n";
		echo "<input type='hidden' name='command' value='copy_from_dg'>\n";
		echo translate ('query empty')."&nbsp;";
		echo translate ('copy fields from').": ";
		echo "<select name='copy_columns_from_dg'>";
		$query = "
				SELECT distinct(d.datagrid_id), mandator_id FROM ".TABLE_PREFIX."datagrids d
				LEFT JOIN ".TABLE_PREFIX."datagrid_columns dgc ON d.datagrid_id=dgc.datagrid_id 
				WHERE mandator_id != '".$_SESSION['mandator']."' AND name='$datagrid_name'
			";	
		$dg_res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error());
		while ($dg_row = mysql_fetch_array($dg_res)) {
			echo "<option value='".$dg_row['datagrid_id']."'>".getMandatorName($dg_row['mandator_id'])."</option>\n";
		}
		echo "</select>&nbsp;";
		echo "<input type=submit name=submit_me value='".translate('copy', null, true)."'>";
		echo "</form>";
    }
    
    function getNavigationStyle ($use_user = null) {
        
        if (is_null ($use_user)) $use_user = $_SESSION['user_id'];

        $query = "
            select 
                navigation
            FROM ".TABLE_PREFIX."user_details 
            WHERE user_id=".$use_user."
            ";
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row = mysql_fetch_array ($res);
        
        return $row['navigation'];
    }    
    
    function getStateForNewObject ($reference) {
        
        assert ('$reference != ""');
        // find out default value for new object
        $query = "
            select 
                status
            FROM ".TABLE_PREFIX."states 
            WHERE startpoint='1' AND reference='$reference' AND mandator=".$_SESSION['mandator']."
            ";
        $res = mysql_query ($query);
        logDBError (__FILE__, __LINE__, mysql_error(), $query);
        
        // entry does not exist, so add with -1 (i.e. undefined)
        if (mysql_num_rows ($res) == 0) {
            $query = "
                INSERT INTO ".TABLE_PREFIX."states 
                    (mandator, reference, status, name, startpoint)
                VALUES (
                    ".$_SESSION['mandator'].",
                    '".$reference."',
                    '-1',
                    'undefined',
                    '1'
                )    
            ";
            $res = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            
            // add default transition from -1 to -1
            $query = "
                INSERT INTO ".TABLE_PREFIX."transitions 
					(mandator, reference, grp, user, state_old, state_new, name)
                VALUES (
                    ".$_SESSION['mandator'].",
                    '$reference',             
                    0,
                    0,
                    -1,             
                    -1,             
                    'entry keeps undefined state'
                    )";
            $res = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            
            return -1;
        }    
        
        $row = mysql_fetch_array ($res);
        
        return $row['status'];
    }    


?>