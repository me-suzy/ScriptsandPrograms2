<?php

   	/*=====================================================================
	// $Id: gacl_manager.php,v 1.6 2005/08/01 14:55:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    // --- GET / POST -----------------------------------------------
    if (isset ($_SERVER['QUERY_STRING'])) {
	    $query       = urldecode($_SERVER['QUERY_STRING']);
    	parse_str ($query);
    }

    if (isset ($PHPSESSID))  session_id($PHPSESSID);
    if (!isset ($use_group) && isset($_REQUEST['use_group'])) $use_group = $_REQUEST['use_group'];
    if (!isset ($use_group)) $use_group = "";
    if (!isset ($use_user)  && isset($_REQUEST['use_user']))  $use_user  = $_REQUEST['use_user'];
    if (!isset ($use_user)) $use_user = "";
    if (!isset ($action)  && isset($_REQUEST['action']))  $action = $_REQUEST['action'];
    if (!isset ($action)) $action= "";
    if (!isset ($referrer)) $referrer = "groupmanager.php";
    if (isset ($_REQUEST['referrer']))
		$referrer = $_REQUEST['referrer'];
    $successor = "";
    if (isset ($_REQUEST['successor'])) $successor = $_REQUEST['successor'];

    include ("inc/pre_include_standard.inc.php");

    if (!isset($submit)) $submit = "";

	// --- security -------------------------------------------------
    /*if (!is_superadmin($user_id)) {
		logMsg (__FILE__." Line ".__LINE__.": Security problem (UserID $user_id)");
    	die ("this action is not allowed");
    }*/
    
    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);

    // --- pagestats ------------------------------------------------
    set_page_stats($user_id, "add_new_user_to_group");

	// --- Header ---------------------------------------------------
    include ("inc/header.inc.php");
    ?>

    <script language='javascript'>
    
        var children = new Array();
        
		function close_me (referrer) {
	        document.location=referrer; //"groupmanager.php";
            window.close();
        }
        
        function memberBoxClicked (box) {
            // enable / disable default group
            eval ("var memberbox = document.formular.member_"+box);
            var i = 0;
            while (i < document.formular.default_group.length) {
                if (document.formular.default_group[i].value == box) {
                    if (memberbox.checked) 
                        document.formular.default_group[i].disabled = false;
                    else
                        document.formular.default_group[i].disabled = true;                    
                    break;    
                }
                i++;
            }
            // autocheck children
            if (memberbox.checked) {
                // !!! variable i already set (and that's fine)
                value = document.formular.default_group[i].value;
                eval ("mychilds = children['"+value+"'];");    
                j=0;
                alert (mychilds);
                while (j < mychilds.length) {
                    //alert (mychilds[j]);
                    eval ("var box = document.formular.member_"+mychilds[j]);
                    box.checked = true;
                    j++;
                }   
            }   
        }
        
        function defaultBoxClicked (box) {
            eval ("var memberbox = document.formular.member_"+box);
            var i = 0;
            while (i < document.formular.default_group.length) {
                eval ("var current_box = document.formular.member_"+document.formular.default_group[i].value);
                if (document.formular.default_group[i].value == box) 
                    memberbox.disabled = true;
                else
                    current_box.disabled = false;
               
                i++;
            }
        }    
    </script>

    <?php

    switch ($action) {
    	case "add_user":       $headline = translate ('manage_groups_add_user');     break;
        case "add_user2":      $headline = translate ('manage_groups_add_user');     break;
    	case "add_group":      $headline = translate ('manage_groups_add_grp');      break;
    	case "add_group2":     $headline = translate ('manage_groups_add_grp');      break;
        /*case "edit_user":      $headline = translate ('manage_groups_edit_user');    break;
        case "edit_user2":     $headline = translate ('manage_groups_edit_user');    break;
        */
        case "edit_groups":       $headline = translate ('edit groups');       break;
        case "edit_groups2":      $headline = translate ('edit groups');       break;
        case "edit_group":        $headline = translate ('manage_groups_rn_grp');       break;
        case "edit_group2":       $headline = translate ('manage_groups_rn_grp');       break;
    	case "show_members":      $headline = translate ('show_members');                 break;
        case "delete_group":      $headline = translate ('manage_groups_delete_grp1');     break;
        case "delete_group2":     $headline = translate ('manage_groups_delete_grp2');     break;
        case "delete_group3":     $headline = translate ('manage_groups_delete_grp3');     break;
        case "edit_permissions":  $headline = translate ('add_permissions');               break;
        case "edit_permissions2": $headline = translate ('add_permissions');               break;
        case "delete_permissions":$headline = translate ('delete_permissions');            break;
        default: die ("unknown"); $headline = translate ("unknown"); break;
    }
    
    include ("modules/common/headline.php");

    function return_to_form ($msg) {

    	echo "<br><br>";
        //echo get_from_texte ($msg, $_SESSION['language']);
        echo translate ($msg);
        ?>
             <a href='#' onClick='javascript:history.back();'><?=translate ("back")?></a>
        <?php
        die ("</body></html>");
    }

    if ($action == "delete_group" && $use_group == get_main_group($user_id)) {
        logMsg ("user (id ".$user_id.") trying to delete his own group");
        //echo "Es ist nicht möglich, dass der angemeldete Benutzer seine eigene Gruppe löscht";
        echo "You cannot delete your own group";
        die ("</body></html>");
   	}


	// === Add New User / Edit User =================================
    if ($action == "add_user2") {

    	logMsg ("gacl_manager - add_user2");

        if (!$gacl_api->acl_check('Usermanager', 'Add User', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);
        
        // login empty ?
        if (trim ($_REQUEST['new_user_login']) == "")
        	return_to_form ("new_user_empty_login");

        // password empty?
        if (trim ($_REQUEST['new_user_password']) == "")
        	return_to_form ("new_user_empty_pass");

        // login gibts schon ?
        $user_res_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."users WHERE login='".$_REQUEST['new_user_login']."'";
		logMsg ($user_res_query);
        $user_res = mysql_query ($user_res_query);
        echo mysql_error();
		logDBError (__FILE__, __LINE__, mysql_error());

		$user_row = mysql_fetch_array ($user_res);
        if ($user_row[0] > 0)
        	return_to_form ("login_exists");
        // pass und pass2 verschieden?
        if ($_REQUEST['new_user_password'] <> $_REQUEST['new_user_password2'])
        	return_to_form ("passwords_differ");
        // auto_id != "" und exisitert schon:
        $new_id = null;

        if (trim($_REQUEST['auto_id']) <> "") {
            $new_id = trim($_REQUEST['auto_id']);
        	if (!settype ($new_id, "integer")) return_to_form("type casting failed");

            $id_res_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."users WHERE id='$new_id'";
            logMsg ($id_res_query);
			$id_res = mysql_query ($id_res_query);
			logDBError (__FILE__, __LINE__, mysql_error());

			$id_row = mysql_fetch_array ($id_res);
	        if ($id_row[0] > 0)
	        	return_to_form ("id exists");
	        if (!is_int($new_id))
	        	return_to_form("given id is not an integer");
	        if ($new_id <= 1)
	        	return_to_form("given id is too small");
        }


        // --- Everything fine, go ahead --------------------------------
        
        $group_value_res = mysql_query ("SELECT value FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id='".$use_group."'");
        $group_value_row = mysql_fetch_array ($group_value_res);
        logDBError (__FILE__, __LINE__, mysql_error());

        // erst mal den User eintragen:
        if ($new_id != null && $new_id <> "") {
        	$user_res_query = "INSERT INTO ".$db_name.".".TABLE_PREFIX."users (id, login, password, grp, vorname, nachname) VALUES
        				('$new_id',
        				 '".$_REQUEST['new_user_login']."',
        				 '".md5($_REQUEST['new_user_password'])."',
                         '".$group_value_row['value']."',
                         '".$_REQUEST['new_user_vorname']."',
                         '".$_REQUEST['new_user_name']."')";
            logMsg ($user_res_query);
	        $user_res = mysql_query ($user_res_query);
        }
        else {
        	$user_res_query = "INSERT INTO ".$db_name.".".TABLE_PREFIX."users (login, password, grp, vorname, nachname) VALUES
        				('".$_REQUEST['new_user_login']."',
        				 '".md5($_REQUEST['new_user_password'])."',
                         '".$group_value_row['value']."',
                         '".$_REQUEST['new_user_vorname']."',
                         '".$_REQUEST['new_user_name']."')";
	        $user_res = mysql_query ($user_res_query);
            logMsg ($user_res_query);
        }
        logDBError (__FILE__, __LINE__, mysql_error());
        if (mysql_error() <> "") die (mysql_error());
        $inserted_user_id = mysql_insert_id ();

        // Add to gacl
        $user_count_res = mysql_query ("SELECT COUNT(*) FROM ".$db_name.".users");
        $user_count_row = mysql_fetch_array ($user_count_res);
        logDBError (__FILE__, __LINE__, mysql_error());
        // 1: Person
        $new_aro_object = $gacl_api->add_object ('Person', $_REQUEST['new_user_login'], $inserted_user_id, 
                                $user_count_row[0],
                                false, 'aro');
        
        // find out group_id for group $use_group:
        if (!$gacl_api->add_group_object($use_group, 'Person', $inserted_user_id, 'aro')) {
            echo "adding object to group failed.";    
        }

		// Eintrag in Tabelle apps_member
		$query = "INSERT INTO ".$db_name.".".TABLE_PREFIX."apps_member (user, app) VALUES ('$inserted_user_id','2')";
        logMsg ($query);
        mysql_query ($query);
       	logDBError (__FILE__, __LINE__, mysql_error());

		// Eintrag in Tabelle user_details
		$query = "INSERT INTO ".$db_name.".".TABLE_PREFIX."user_details (user_id, skin, sprache)
			VALUES ('".$inserted_user_id."','3','2')";
        logMsg ($query);
        mysql_query ($query);
      	logDBError (__FILE__, __LINE__, mysql_error());

		// Confirmation
		?>
        	<br><br>
             <a href='#' onClick='javascript:close_me("<?=$referrer?>");'><?=translate ("user_added")?></a>
        <?php
    	die ("</body><html>");
    }
    else if ($action == "edit_user2") {
        die ("deprecated - edit_user2");

    	logMsg ("manage_groups - edit_user2");
    	// Prüfung: Login leer?
        if (trim ($_REQUEST['new_user_login']) == "")
        	return_to_form ("new_user_empty_login");
    	// Prüfung: Login schon existent?
    	$res_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."users
    						 WHERE login='".$_REQUEST['new_user_login']."
    						 AND id != ".$_REQUEST['use_user']."'";
    	logMsg ($res_query);
    	$res = mysql_query ($res_query);
      	logDBError (__FILE__, __LINE__, mysql_error());

		$row = mysql_fetch_array ($res);
		if ($row[0] > 0)
        	return_to_form ("login_exists");

        $new_id = null;

        if (trim($_REQUEST['auto_id']) <> "") {
            $new_id = trim($_REQUEST['auto_id']);
        	if (!settype ($new_id, "integer")) return_to_form("type casting failed");

            $user_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."users WHERE id='$new_id'
									AND id != '".$_REQUEST['use_user']."'";
			logMsg ($user_query);
			$id_res = mysql_query ($user_query);
			logDBError (__FILE__, __LINE__, mysql_error());

			$id_row = mysql_fetch_array ($id_res);
	        if ($id_row[0] > 0)
	        	return_to_form ("id exists");
	        if (!is_int($new_id))
	        	return_to_form("given id is not an integer");
	        if ($new_id <= 1)
	        	return_to_form("given id is too small");
        }

        // Alles passt, also updaten
		$update_query = "UPDATE ".TABLE_PREFIX."users SET
        				login   ='".$_REQUEST['new_user_login']."',
        				vorname ='".$_REQUEST['new_user_vorname']."',
        				nachname='".$_REQUEST['new_user_name']."'
        			  WHERE id='".$_REQUEST['use_user']."'";
        logMsg ($update_query);
        mysql_query ($update_query);
      	logDBError (__FILE__, __LINE__, mysql_error());

      	if ($user_id == $_REQUEST['use_user']) {
	      	$_SESSION['login'] = $_REQUEST['new_user_login'];
	        if ($new_id != null) {
    	     	$_SESSION['user_id'] = $_REQUEST['user_id'];
        	}
    	}

        if ($new_id != null) {
			$query = "UPDATE admin set user='$new_id' WHERE user='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE apps_member set user='$new_id' WHERE user='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE bookmarks set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE change_requests set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE companies set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE contacts set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE emailkonten set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE folders set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE history set user_id='$new_id' WHERE user_id='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE history_temp set user_id='$new_id' WHERE user_id='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());
            
			$query = "UPDATE intern_news set anleger='$new_id' WHERE anleger='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE intern_news set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE membership set user='$new_id' WHERE user='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE notes set anleger='$new_id' WHERE anleger='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE notes set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE projects set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE quicklinks set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE rel_user_modules set user='$new_id' WHERE user='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE rel_user_modules set user='$new_id' WHERE user='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE talks set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());
            
			$query = "UPDATE termine set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE termine3_0 set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE timesheet set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE todos set owner='$new_id' WHERE owner='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE user_details set user_id='$new_id' WHERE user_id='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE useronline set user_id='$new_id' WHERE user_id='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

			$query = "UPDATE users set id='$new_id' WHERE id='".$_REQUEST['use_user']."'";
			logMsg ($query);
        	mysql_query ($query);
	      	logDBError (__FILE__, __LINE__, mysql_error());

    	}

        ?>
        	<br><br>
             <a href='#' onClick='javascript:close_me("<?= $referrer?>");'><?=translate ("user_changed")?></a>
        <?php
    	die ("</body><html>");
    }
    else if ($action == "edit_group2") {
    	logMsg ("gacl_manager - edit_group2");
    	
    	if (!$gacl_api->acl_check("Groupmanager", 'Edit Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);
    	
    	// group_name empty?
    	if (trim ($_REQUEST['new_group_alias'] == "")) 
    	    return_to_form ("provide_group_name");		 // alias gibts schon?

    	// Prüfung ob es Gruppe schon gibt...
    	$groups_res_query = "SELECT COUNT(*) FROM groups
    						 WHERE name='".$_REQUEST['new_group_alias']."
    						 AND id != ".$_REQUEST['use_group']."'";
        logMsg ($groups_res_query);
        $res = mysql_query ($groups_res_query);
      	logDBError (__FILE__, __LINE__, mysql_error());

		$row = mysql_fetch_array ($res);
		if ($row[0] > 0)
        	return_to_form ("group_exists");
        
        // find out value for group $use_group:
        $group_id_res = mysql_query ("SELECT value FROM gacl_aro_groups WHERE id='".$use_group."'");
        $group_id_row = mysql_fetch_array ($group_id_res);
        logDBError (__FILE__, __LINE__, mysql_error());

        // fine, go ahead
		$update_groups_query = "UPDATE groups SET
                        name ='".$_REQUEST['new_group_alias']."',
                        alias='".$_REQUEST['new_group_alias']."'
        			  WHERE id='".$group_id_row['value']."'";
        echo $update_groups_query;
        logMsg ($update_groups_query);
        mysql_query ($update_groups_query);
      	logDBError (__FILE__, __LINE__, mysql_error());
      	
      	// phpgacl
      	//function edit_group($group_id, $value=NULL, $name=NULL, $parent_id=NULL, $group_type='ARO') {
        if (!$gacl_api->edit_group ($use_group,
                                    null,
      	                            $_REQUEST['new_group_alias'])) {
      	                            /*,
      	                            $gacl_api->get_group_parent_id($group_id_row['id']), 
      	                            'aro')) {*/
      	    echo "Updating Group in phpgacl failed.";    
      	}    
      	
        ?>
        	<br><br>
             <a href='#' onClick='javascript:close_me("<?= $referrer?>");'><?=translate ("groupname_changed")?></a>
        <?php
    	die ("</body><html>");
    }
    else if ($action == "add_group2") {
    	/*logMsg ("gacl_manager - add_group2");
    	
    	if (!$gacl_api->acl_check('Groupmanager', 'Add Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);
    	// group_name empty?
    	if (trim ($_REQUEST['new_group_alias'] == "")) return_to_form ("provide_group_name");		 // alias gibts schon?
		
		// name exists
		$groups_count_query = "SELECT COUNT(*) FROM groups WHERE alias='".$_REQUEST['new_group_alias']."'";
		logMsg ($groups_count_query);
        $grp_res = mysql_query ($groups_count_query);
		logDBError (__FILE__, __LINE__, mysql_error());

		$grp_row = mysql_fetch_array ($grp_res);
        if ($grp_row[0] > 0) return_to_form ("group_name_exists");

		$insert_groups_query = "INSERT INTO ".TABLE_PREFIX."groups (alias,name) VALUES ('".$_REQUEST['new_group_alias']."','".$_REQUEST['new_group_alias']."')";
        logMsg ($insert_groups_query);
		mysql_query ($insert_groups_query);
        logDBError (__FILE__, __LINE__, mysql_error());

		$inserted_grp_id = mysql_insert_id ();

		mysql_query ("INSERT INTO ".TABLE_PREFIX."admin (user,grp) VALUES ('$use_user','$inserted_grp_id')");
      	logDBError (__FILE__, __LINE__, mysql_error());

        // no double entries
        $del_from_memebership_query = "DELETE FROM membership WHERE user=$use_user
        				AND grp=".$inserted_grp_id;
        logMsg ($del_from_memebership_query);
        mysql_query ($del_from_memebership_query);

        $insert_membership_query = "INSERT INTO ".TABLE_PREFIX."membership (user,grp)
					VALUES ('$use_user','$inserted_grp_id')";
		logMsg ($insert_membership_query);
		mysql_query ($insert_membership_query);
        logDBError (__FILE__, __LINE__, mysql_error());

    	if (mysql_error() <> "") die (mysql_error());

      	// phpgacl
        // find out group_id for group $use_group:
        $group_id_res = mysql_query ("SELECT id FROM gacl_aro_groups WHERE value='".$use_group."'");
        $group_id_row = mysql_fetch_array ($group_id_res);
        logDBError (__FILE__, __LINE__, mysql_error());
        // add new group to groups parent
        //function add_group($value, $name, $parent_id=0, $group_type='ARO') {
        $new_group_id = $gacl_api->add_group (
            $inserted_grp_id,
            $_REQUEST['new_group_alias'], 
            $_REQUEST['use_group'], 
            'ARO');
        // find out aro name
        //$name_res = mysql_query ("SELECT name FROM gacl_aro WHERE value='$use_user'");
        //$name_row = mysql_fetch_array ($name_res);

        // Add user to this group
    	//function add_group_object($group_id, $object_section_value, $object_value, $group_type='ARO') {
        /*if (!$gacl_api->add_group_object (
                                $new_group_id, 
                                'Person', 
                                $use_user, 
                                'ARO')) {
            echo "phpgacl: Adding object failed.";                            
        } * /                  
        
		?>
        	<br><br>
             <a href='#' onClick='javascript:close_me("groupmanager.php");'><?=translate ("group_added")?></a>
        <?php
    	die ("</body><html>");*/
    }
    else if ($action == "delete_group") {
        
    	logMsg ("manage_groups - delete_group");

        if (!$gacl_api->acl_check('Groupmanager', 'Delete Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        $grp_value = get_value_for_gacl_aro_group ($use_group);

    ?>
        <br><br>
    	<form action='gacl_manager.php' name='formular' method='post'>
	    <input type=hidden name=use_group  value='<?=$use_group?>'>
	    <input type=hidden name=action     value='delete_group2'>
	    <input type=hidden name=referrer   value='<?=$referrer?>'>

	    <table width='400' align=center border=0>
	    <tr>
	         <td colspan=2>
	            <b>Do you really want to delete this Group:</b>
	         </td>
	    </tr>
	    <tr>
	         <TD colspan=2><hr></td>
	    </tr>
	    <tr>
	         <td><b>Group:</b></td>
	         <td><?=show_group_alias($grp_value)?></td>
	    </tr>
        <tr>
        	<td><b># <?=translate ("contacts")?>:</b></td>
         	<td>
         	<?php

                $cnt_res_query = "SELECT COUNT(1) FROM contacts WHERE
         								grp=$grp_value";
         	    logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		$cnt_row = mysql_fetch_array ($cnt_res);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo $cnt_row[0];
         	?>
        	</td>
    	</tr>
        <tr>
        	<td><b># <?=translate ("companies")?>:</b></td>
         	<td>
         	<?php
	            $cnt_res_query = "SELECT COUNT(1) FROM companies WHERE
         								grp=$grp_value";
         	    logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		$cnt_row = mysql_fetch_array ($cnt_res);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo $cnt_row[0];
         	?>
        	</td>
    	</tr>
        <tr>
        	<td><b># <?=translate ("members")?>:</b></td>
         	<td>
         	<?php
            	$cnt_res_query = "SELECT DISTINCT user FROM membership WHERE
         								grp=$grp_value";
         	    logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo mysql_num_rows($cnt_res);
         	?>
        	</td>
    	</tr>
        <tr>
        	<td><b># <?=translate("emails")?>:</b></td>
         	<td>
         	<?php
                $cnt_res_query = "SELECT COUNT(1) FROM emails WHERE
         								grp=$grp_value";
         	    logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		$cnt_row = mysql_fetch_array ($cnt_res);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo $cnt_row[0];
         	?>
        	</td>
    	</tr>
         <tr>
        	<td><b># <?=translate ("follow-ups")?>:</b></td>
         	<td>
         	<?php
                $cnt_res_query = "SELECT COUNT(1) FROM talks WHERE
         								grp=$grp_value";
         	    logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		$cnt_row = mysql_fetch_array ($cnt_res);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo $cnt_row[0];
         	?>
        	</td>
    	</tr>
         <tr>
        	<td><b># <?=translate ("notes")?>:</b></td>
         	<td>
         	<?php
         		$cnt_res_query = "SELECT COUNT(1) FROM notes WHERE
         								grp=$grp_value";
         		logMsg ($cnt_res_query);
         		$cnt_res = mysql_query ($cnt_res_query);
         		$cnt_row = mysql_fetch_array ($cnt_res);
         		logDBError (__FILE__, __LINE__, mysql_error());
         		echo $cnt_row[0];
         	?>
        	</td>
    	</tr>
        <tr>
        	<TD colspan=2><hr></td>
    	</tr>
	    <tr>
	         <TD colspan=2>
	             <input type=submit class=buttonstyle name=submit value='<?=translate ("delete")?>'>
	             &nbsp;
	             <input type=button class=buttonstyle name=stop onClick='window.close();' value='<?=translate ("cancel")?>'>
	         </td>
	    </tr>
	    </table>
	    </form>
    <?php
	    die ("</body><html>");
    }
    else if ($action == "delete_group2") {
        
    	logMsg ("manage_groups - delete_group2");
    	
        if (!$gacl_api->acl_check('Groupmanager', 'Delete Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        $grp_value = get_value_for_gacl_aro_group ($use_group);
	
	?>
		<br><br>
        <form action='gacl_manager.php' name='formular' method='post'>
          <input type=hidden name=use_group  value='<?=$use_group?>'>
          <input type=hidden name=action     value='delete_group3'>
          <input type=hidden name=referrer   value='<?=$referrer?>'>

      	<table width='400' align=center border=0>
        <tr>
               <td colspan=2>
                  <b>You have to specify a successor of the groups entries.
                  </b>
               </td>
          </tr>
          <tr>
              <td colspan=2><hr></td>
          </tr>
          <tr>
              <td>Successor</td>
              <td>
                  <select name="successor">
            	  <?php
            	  	  $successor_query = "SELECT * FROM groups
                      					       WHERE id > 1 AND id != $grp_value
                      					       ORDER by alias, name";
                      logMsg ($successor_query);
                      $grp_res = mysql_query ($successor_query);
					  logDBError (__FILE__, __LINE__, mysql_error());
                      while ($grp_row = mysql_fetch_array ($grp_res)) {
                          $show_name = $grp_row['alias'];
                          if ($show_name == "")
                              $show_name = $grp_row['name'];
                          $sel = " selected";
                          echo "<option value='".$grp_row['id']."' $sel>".$show_name."</option>\n";
                      }
                  ?>
                  </select>
              </td>
          </tr>
          <tr>
              <td colspan=2><hr></td>
          </tr>
          <tr>
               <TD colspan=2>
                   <input type=submit class=buttonstyle name=submit value='<?=translate ("delete")?>'>
                   &nbsp;
                   <input type=button class=buttonstyle name=stop onClick='window.close();' value='<?=translate ("cancel")?>'>
               </td>
          </tr>
       </table>

<?php
		    die ("</body><html>");
	}
	else if ($action == "delete_group3") {
	    
        if (!$gacl_api->acl_check('Groupmanager', 'Delete Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

    	logMsg ("manage_groups - delete_group3");
        $grp_value = get_value_for_gacl_aro_group ($use_group);

    	if ($successor == "") {
    	    logMsg (__FILE__." (".__LINE__.") : no group selected");
        	die ("no group selected");
    	}
		// Delete Adminentries
		$del_query = "DELETE FROM admin WHERE grp = $grp_value";
		logMsg ($del_query);
        mysql_query ($del_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update change requests
		$upd_query = "UPDATE change_requests SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update companies and companies_additional
		$upd_query = "UPDATE companies SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE companies_additional SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update contacts and companies_additional
		$upd_query = "UPDATE contacts SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE contacts_additional SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update cr_cat_liste
		$upd_query = "UPDATE cr_cat_liste SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update cr_prio_liste
		$upd_query = "UPDATE cr_prio_liste SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
        logDBError (__FILE__, __LINE__, mysql_error());

        // Update docs
		/*$upd_query = "UPDATE docs SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
        logDBError (__FILE__, __LINE__, mysql_error());

        // Update documents
		$upd_query = "UPDATE documents SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());
        */
        
        // Update emails
		$upd_query = "UPDATE emails SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update group_liste
		$upd_query = "UPDATE group_liste SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Delete Group
		$upd_query = "DELETE FROM groups WHERE id='$grp_value'";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Update imorts
		$upd_query = "UPDATE import_companies SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE import_contacts SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Intern News
		$upd_query = "UPDATE intern_news SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Leads_liste
		$upd_query = "UPDATE leads_liste SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Delete Memberships
        //mysql_query ("DELETE FROM membership WHERE grp='$grp_value'");
 		//logDBError (__FILE__, __LINE__, mysql_error());
		$upd_query = "UPDATE membership SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Notes
		$upd_query = "UPDATE notes SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Projects
		$upd_query = "UPDATE projects SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Quicklinks
		$upd_query = "UPDATE quicklinks SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Talks
		$upd_query = "UPDATE talks SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // Templates
		/*$upd_query = "UPDATE templates SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());
        */
        
		// Termine
		/*$upd_query = "UPDATE termine SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());
        */
        
		$upd_query = "UPDATE timesheet SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE todo_stati SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        //mysql_query ("UPDATE user_details SET main_group=$successor WHERE main_group=$grp_value");
 		//logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE useronline SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

		$upd_query = "UPDATE users SET grp=$successor WHERE grp=$grp_value";
		logMsg ($upd_query);
        mysql_query ($upd_query);
 		logDBError (__FILE__, __LINE__, mysql_error());

        // phpgacl
        // find out group_id for group $use_group:
        //$group_id_res = mysql_query ("SELECT id FROM gacl_aro_groups WHERE value='".$use_group."'");
        //$group_id_row = mysql_fetch_array ($group_id_res);
        //logDBError (__FILE__, __LINE__, mysql_error());

        if (!$gacl_api->del_group ($use_group, FALSE)) {
            echo "PHPgacl: deleting group failed.";    
        }    

	    ?>
        	<br><br>
             <a href='#' onClick='javascript:close_me("<?=$referrer?>");'><?=translate ("group_deleted")?></a>
        <?php
    	die ("</body><html>");
	}
	else if ($action == "show_members") {
    	logMsg ("gacl_manager - show members");
    	
        if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        ?>
        <table border=0>
        <tr>
            <th>Login</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Action</th>
        </tr>
        <?php
            $query = '
        		SELECT	b.section_value,b.value,b.name AS b_name,c.name AS c_name, 
        		        users.vorname, users.nachname
        		FROM	gacl_groups_aro_map a
        		INNER JOIN	gacl_aro b ON b.id=a.aro_id
        		INNER JOIN	gacl_aro_sections c ON c.value=b.section_value
        		LEFT  JOIN  users ON users.id=b.value
        		WHERE   a.group_id='.$use_group.'
        		ORDER BY c.name, b.name';

        	$gacl_res = mysql_query ($query);
        	echo mysql_error();
        	
    		while ($row = mysql_fetch_array ($gacl_res)) {
    			list($section_value, $value, $name, $section) = $row;
    			echo "<tr>";
    			echo "<td>".$name."</td>";
    			echo "<td>".$row['vorname']."</td>";
    			echo "<td>".$row['nachname']."</td>";
    			echo "<td><a href=''>Add User</a></td>";
    			echo "</tr>\n";
    		}
        		

        ?>
        </table>
        <?php
    	die ("</body><html>");
    }
	else if ($action == "edit_groups2") {

        if (!$gacl_api->acl_check('Groupmanager', 'Edit Group', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        $query = '
			SELECT		a.id, a.name, a.value, count(b.aro_id)
			FROM		gacl_aro_groups a
			LEFT JOIN	gacl_groups_aro_map b ON b.group_id=a.id
			GROUP BY	a.id,a.name,a.value';
		$grp_res = mysql_query ($query);
		echo mysql_error();
		
	    //function get_group_objects($group_id, $group_type='ARO', $option='NO_RECURSE') {
        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
        foreach ($formatted_groups AS $key => $value) {
            // membership
            $tmp = "member_".$key;
            if ($_REQUEST[$tmp] == "on") { // add to group (if not already member)
            	//function add_group_object($group_id, $object_section_value, $object_value, $group_type='ARO') {
                if (!$gacl_api->add_group_object (
                                $key, 
                                'Person', 
                                $_REQUEST['use_user'], 
                                'ARO')) {
                    echo "phpgacl: Adding object failed.";                            
                }
            }
            else { //remove from group (if member at all)
                //function del_group_object($group_id, $object_section_value, $object_value, $group_type='ARO') {
                if (!$gacl_api->del_group_object (
                                $key, 
                                'Person', 
                                $_REQUEST['use_user'], 
                                'ARO')) {
                    echo "phpgacl: Adding object failed.";                            
                }
            }        
        }    
        die ($_REQUEST['default_group']);
    ?>
        <br><br>
        <a href='#' onClick='javascript:close_me("<?= $referrer?>");'><?=translate ("groups changed")?></a>
<?php
    	die ("</body><html>");
    }
    else if ($action == "edit_permissions") {
        
        if (!$gacl_api->acl_check($section, 'Edit Permissions', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        require_once (EASY_FRAMEWORK_DIR."/classes/widgets/multiselect.class.php");
    ?>
    
      <form action='gacl_manager.php' name=formular>
      <input type=hidden name='action'    value='edit_permissions2'>
      <input type=hidden name='section'   value='<?=$section?>'>
      <input type=hidden name='return_to' value='<?=$_REQUEST['return_to']?>'>
      
      <table border=0>
      <tr>
        <td><b>Actions to choose:</b></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><b>Choosen Actions:</b></td>
        <td><b>allow / deny:</b></td>
        <td><b>for</b></td>
        <td><b>Group <u>or</u> User:</b></td>
      </tr>
      <tr>
        <td colspan=3>
            
    
        <?php
            $options = "";
            $sections_res = mysql_query ("
                SELECT * FROM ".TABLE_PREFIX."gacl_aco 
                WHERE section_value='".$_REQUEST['section']."'
                ORDER BY order_value");
            while ($sections_row = mysql_fetch_array($sections_res))
                $options .= "<option value='".$sections_row['value']."'>".$sections_row['value']."</option>\n";
            
            $multiselect = new easy_multiselect($options);
            $multiselect->setFormularName    ("form");
            $multiselect->setLeftSelectName  ("actions");
            $multiselect->setRightSelectName ("use_actions");
            $multiselect->setLeftSelectStyle ("width:200px");    
            $multiselect->setRightSelectStyle("width:200px");    
            echo $multiselect->getJavascript(); 

            echo $multiselect->toString();
    
        ?>
        </td>
        <td>
            <input type=radio name='allow' value='1' checked>Allow<br>
            <input type=radio name='allow' value='0'>Deny<br>            
        </td>
        <td>&nbsp;</td>
        <td valign=top>
            <select name='use_users[]' multiple size=4>
        <?php
            $my_query = "
                SELECT firstname, lastname, ".TABLE_PREFIX."users.id 
                FROM ".TABLE_PREFIX."gacl_aro 
                LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."gacl_aro.value=".TABLE_PREFIX."users.id
                WHERE section_value='Person'";
            //echo "***".$my_query;    
            $users_res = mysql_query ($my_query);
           
            while ($users_row = mysql_fetch_array($users_res)) {
                echo "<option value='".$users_row['id']."'>".$users_row['firstname']." ".$users_row['lastname']."</option>\n";
            }     
        ?>
            </select><?=mysql_error();?>
            <b>OR</b>
            <select name='use_groups[]' multiple size=4>
        <?php
            $groups = $gacl_api->format_groups($gacl_api->sort_groups('ARO')); 
            foreach ($groups AS $key => $value) 
                echo "<option value='$key'>".$value."</option>\n";
        ?>    
            </select>
        </td>
      </tr>
      <tr>
        <td colspan=6>
          <input type=submit name=submit value='Add'>
        </td>
      </tr>
      </table>
      </form>
    
            
    <?php    
    	die ("</body><html>");
    }
    else if ($action == "edit_permissions2") {

        if (!$gacl_api->acl_check($section, 'Edit Permissions', 'Person', $_SESSION['user_id']))
            die ("Security alert in ".__FILE__);

        $selected_aco_array = array ();
        $use_actions        = $_REQUEST['use_actions'];
        foreach ($use_actions AS $key => $action)
            $selected_aco_array[$section][] = $action;
        
        $selected_aro_array = array ();
        $use_users          = null;
        if (isset ($_REQUEST['use_users'])) 
            $use_users = $_REQUEST['use_users'];
        if (is_array ($use_users)) {
            foreach ($use_users AS $key => $user)
                $selected_aro_array['Person'][] = $user;
        }

        (isset ($_REQUEST['use_groups'])) ? $use_groups = $_REQUEST['use_groups'] : $use_groups = '';
        
        if (!$gacl_api->add_acl(
            $selected_aco_array, 
            $selected_aro_array, 
            $use_groups, 
            NULL, 
            NULL, 
            $_REQUEST['allow'], 
            1, 
            NULL, 
            NULL, "user")) {
			echo 'ERROR adding ACL, possible conflict or error found...<br />' . "\n";
		}

    ?>
        <br><br>
        <a href='#' onClick='javascript:close_me("<?=$_REQUEST['return_to']?>");'><?=translate ("permissions changed")?></a>
<?php
    	die ("</body><html>");
    }
    if ($action == "delete_permissions") {
        
        // validate if user is allowed to edit permissions in this section
        if (!$gacl_api->acl_check($_REQUEST['section'],'Edit Permissions','Person',$_SESSION['user_id'])) {
            die ("security problem in ".__FILE__);
        }
        
        // the user should not be allowed to delete all entries to a given
        // section
        $counter = 0;
        $acl_res = mysql_query ("SELECT id FROM ".TABLE_PREFIX."gacl_acl ORDER BY id");
        while ($acl_row = mysql_fetch_array ($acl_res)) {
            $tmp = "acl_".$acl_row['id'];
            if (isset ($_REQUEST[$tmp]) && $_REQUEST[$tmp] == "on") {
                $counter++;
            }        
        }    
        if ($counter >= $_REQUEST['acl_count']) {
            die ('please do not delete all permissions in this section');    
        }    

        $acl_res = mysql_query ("SELECT id FROM ".TABLE_PREFIX."gacl_acl ORDER BY id");
        while ($acl_row = mysql_fetch_array ($acl_res)) {
            $tmp = "acl_".$acl_row['id'];
            if (isset ($_REQUEST[$tmp]) && $_REQUEST[$tmp] == "on") {
                if (!$gacl_api->del_acl ($acl_row['id'])) {
                    echo "error deleting acl";    
                }
            }        
        }    
        
    ?>
        <br><br>
        <a href='#' onClick='javascript:close_me("<?=$_REQUEST['return_to']?>");'><?=translate ("permissions deleted")?></a>
    <?php
    	die ("</body><html>");
    }



    if ($action == "add_user")
    	$new_action = "add_user2";
    else if ($action == "add_group")
    	$new_action = "add_group2";
	else if ($action == "edit_user")
		$new_action = "edit_user2";
	else if ($action == "edit_group")
    	$new_action = "edit_group2";
    else $new_action = "";

   	logMsg ("manage_groups (for action ".$action.")");


    if ($action == "edit_groups") {
    ?>

        <form action='gacl_manager.php' name='formular' method='post'>
        <input type=hidden name=action    value='edit_groups2'>
        <input type=hidden name=use_user  value='<?=$use_user?>'>
        <input type=hidden name=referrer  value='<?=$referrer?>'>

    	<table border=0>
        <tr>
    	    <th>Group</th>
    	    <th>Member?</th>
    	    <th>Default Group</th>
    	</tr>
        <?php
        
            $query = '
    			SELECT		a.id, a.name, a.value, count(b.aro_id)
    			FROM		".TABLE_PREFIX."gacl_aro_groups a
    			LEFT JOIN	".TABLE_PREFIX."gacl_groups_aro_map b ON b.group_id=a.id
    			GROUP BY	a.id,a.name,a.value';
    		$grp_res = mysql_query ($query);
    		echo mysql_error();
    		
    		$def_group        = get_id_for_gacl_aro_group(get_main_group ($_SESSION['user_id']));
    		
            $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
            
            $entries          = array ();
            $i                = 0;
            foreach ($formatted_groups AS $key => $value) {
                $entries[$i]['value'] = $value;
                $entries[$i]['key'] = $key;
                $entries[$i]['level'] = substr_count($value, "&rarr;");
                
                $checked     = "";
                $def_checked = "";
                $elements = $gacl_api->get_group_objects($key);
                //var_dump ($elements);
                //echo $use_user."/".$elements[0];
                if (@in_array ($use_user, $elements['Person'])) $checked     = "checked";
                if ($def_group == $key)
                    $def_checked = "checked";
                else
                    if ($checked != "checked") $def_checked = "disabled";
                echo "<tr>";
                echo "<td><b>$level.$value</b></td>";
                echo "<td><input type='checkbox' name='member_".$key."' $checked onClick='javascript:memberBoxClicked(".$key.");'></td>";
                echo "<td><input type='radio'    name='default_group'   value='".$key."' $def_checked onClick='javascript:defaultBoxClicked(".$key.");'></td>";
                echo "</tr>\n";
                $i++;
           }    
           
        ?>
        <tr>
            <td colspan=6><hr></td>
        </tr>
        <tr>
            <td colspan=6>
                <input type=reset name=reset value=reset>&nbsp;
                <input type=submit name=submit value=submit>
            </td>
        </tr>
    	</table>
        
        <script language='javascript'>
    <?php        
        
        for ($i=0; $i < count ($entries); $i++) {
            $level = $entries[$i]['level'];
            $j=$i + 1;
            $k=0;
            //echo "i: ".$i.", level: $level <br>";
            echo "children['".$entries[$i]['key']."'] = new Array();\n";
            while ($j < count ($entries)) {
                if ($entries[$j]['level'] > $level)
                    echo "children['".$entries[$i]['key']."'][".$k."] = ".$entries[$j]['key'].";\n"; 
                else
                    break;    
                $k++;
                $j++;
            }     
        }    
        echo "</script>\n";
    	die ("</body><html>");
    }
         
  ?>

    <br><br>
    <form action='gacl_manager.php' name='formular' method='post'>
    <input type=hidden name=action    value='<?=$new_action?>'>
    <input type=hidden name=use_group value='<?=$use_group?>'>
    <input type=hidden name=use_user  value='<?=$use_user?>'>
    <input type=hidden name=referrer  value='<?=$referrer?>'>

    <?php
    $grp_res_query = "SELECT alias, name FROM ".TABLE_PREFIX."groups WHERE id='$use_group'";
    logMsg ($grp_res_query);
    $grp_res = mysql_query ($grp_res_query);
    $grp_row = mysql_fetch_array ($grp_res);
    logDBError (__FILE__, __LINE__, mysql_error());

    $show_name = $grp_row['alias'];
    if (trim($show_name) == "") $show_name = $grp_row['name'];
	if ($action == "add_group" || $action == "edit_group") {
	    //$grp_res = mysql_query ("SELECT value FROM gacl_aro_groups WHERE id='$use_group'");
	    //$grp_row = mysql_fetch_array ($grp_res);
	    $grp_value = get_value_for_gacl_aro_group ($use_group);
	?>
		<table width='350' align=center border=0>
		<?php if ($action == "add_group") { ?>
	    <tr>
    	     <td colspan=2>Adding new group below <?=show_group_alias($grp_value)?></td>

    	</tr>
    	<tr><td colspan=2><hr></td></tr>
    	<?php } ?>
    	<?php if ($action == "add_group") { ?>
        	<tr>
        	     <td><b><?=translate ("group")?>:</b></td>
            	 <td><input type=text name='new_group_alias' value='' size=20></td>
        	</tr>
        	<tr><td colspan=2><hr></td></tr>
        	<tr>
             <TD colspan=2 align=left>
        	     <input type=submit class=buttonstyle name=submit value='<?=translate ("add")?>'>
             </td>
        	</tr>
    	<?php } else { ?>
        	<tr>
        	     <td><b><?=translate ("group")?>:</b></td>
            	 <td><input type=text name='new_group_alias' value='<?=show_group_alias($grp_value)?>' size=20></td>
    	    </tr>
        	<tr><td colspan=2><hr></td></tr>
            <tr>
             <TD colspan=2 align=left>
        	     <input type=submit class=buttonstyle name=submit value='<?=translate ("edit")?>'>
             </td>
        	</tr>
    	<?php } ?>
		</table>
	<?php
		die ("</body></html>");
	}

    $user_res_query = "SELECT * FROM ".TABLE_PREFIX."users WHERE id='$use_user'";
    logMsg ($user_res_query);
    $user_res = mysql_query ($user_res_query);
    logDBError (__FILE__, __LINE__, mysql_error());
    $user_row = mysql_fetch_array ($user_res);

    ?>
	<table width='400' align=center border=0>
    <tr>
         <td><b><?=translate ("group")?>:</b></td>
         <td>
            <select name='use_group'>
            <?php
                $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
                foreach ($formatted_groups AS $key => $value)
                    echo "<option value='$key'>$value</option>\n";
            ?>
            </select>
         </td>
    </tr>
    <?php if ($use_user != "") { ?>
    <tr>
         <td><b>ID:</b></td>
         <td><b><?=$use_user?></b></td>
    </tr>

    <?php } ?>
    <tr>
         <TD colspan=2><hr></td>
    </tr>
    <tr>
         <td><b><?=translate ("first name")?>:</b></td>
         <td><input type='text' name='new_user_vorname' value='<?=$user_row['vorname']?>'></td>
    </tr>
    <tr>
         <td><b><?=translate ("last name")?>:</b></td>
         <td><input type='text' name='new_user_name' value='<?=$user_row['nachname']?>'></td>
    </tr>
    <tr>
         <td><b>Login</b>:</td>
         <td><input type='text' name='new_user_login' value='<?=$user_row['login']?>'></td>
    </tr>
    <?php if ($use_user == "") { ?>
    <tr>
         <td><b><?=translate ("new password")?>:</b></td>
         <td><input type='password' name='new_user_password'></td>
    </tr>
    <tr>
         <td><b><?=translate ("new password again")?>:</b></td>
         <td><input type='password' name='new_user_password2'></td>
    </tr>
    <?php } ?>

    <tr>
         <TD colspan=2><hr></td>
    </tr>
    <?php if ($use_user == "") { ?>
	<tr>
         <TD colspan=2>
    	     <input type=submit class=buttonstyle name=submit value='<?=translate ("add")?>'>
         </td>
    </tr>
    <?php } else { ?>
	<tr>
         <TD colspan=2>
    	     <input type=submit class=buttonstyle name=submit value='<?=translate ("edit")?>'>
         </td>
    </tr>
    <?php } ?>
    </table>
    </form>

</body>
</html>