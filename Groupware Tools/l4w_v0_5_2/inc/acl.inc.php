<?php

   /**
    * $Id: acl.inc.php,v 1.18 2005/08/01 14:55:12 carsten Exp $
    *
    * Common functions concerning users and rights for leads4web
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */
    
   /**
    * get array with all users of leads4web regardless of any access permissions
    *
    * Queries database for all leads4web users and return array with key user_id
    * and the users name as value 
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
	function get_all_users () {
        $query = '
			 SELECT value, name FROM '.TABLE_PREFIX.'gacl_aro 
			 LEFT JOIN '.TABLE_PREFIX.'users ON '.TABLE_PREFIX.'users.id=value
			 WHERE hidden=0 
			 ORDER BY section_value,order_value,name
			 ';
		$users_res = mysql_query ($query);
		echo mysql_error();
		
		$arr = array ();
		while($row = mysql_fetch_array ($users_res)) {
            $arr[$row['value']] = $row['name'];
        }
        return $arr;
	}

   /**
    * get all users for group
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
	function get_all_users_for_group ($group) {
        $query = '
            select aro.value, name
            from '.TABLE_PREFIX.'gacl_groups_aro_map gam
            left join '.TABLE_PREFIX.'gacl_aro aro ON gam.aro_id=aro.id
            left join '.TABLE_PREFIX.'users        ON '.TABLE_PREFIX.'users.id=aro.value
            WHERE gam.group_id='.$group;

		$users_res = mysql_query ($query);
		echo mysql_error();
		
		$arr = array ();
		while($row = mysql_fetch_array ($users_res)) {
            $arr[$row['value']] = $row['name'];
        }
        return $arr;
	}

   /**
    * get users with access
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
	function get_users_with_access (&$gacl, $aco_section_value, $aco_value, $aro_section_value) {
	    
	    $users      = get_all_users ();
        $access_for = array();
        foreach ($users AS $key => $value) {
            if ($gacl->acl_check($aco_section_value,$aco_value,$aro_section_value,$key))
                $access_for[] = $key;
        }
        return $access_for;
    }
    
   /**
    * get value for gacl_aro_group
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
    function get_value_for_gacl_aro_group ($id) {
        $grp_res = mysql_query ("SELECT value FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id='$id'");
	    $grp_row = mysql_fetch_array ($grp_res);
	    return $grp_row['value'];
    }

   /**
    * get id for gacl aro group
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
    function get_id_for_gacl_aro_group ($value) {

        $query = "SELECT id FROM ".TABLE_PREFIX."gacl_aro_groups WHERE value='$value'";
        $grp_res = mysql_query ($query);
	    $grp_row = mysql_fetch_array ($grp_res);
	    return $grp_row['id'];
    }

   /**
    * get group options
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
	function get_group_options (&$gacl_api, $sel_id, $show_all_option = false) {
        global $gacl_api;

        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');

        $options          = "";
        $cnt              = 0;
        $single_hit_id    = 0;

        if ($show_all_option)
            $options .= "<option value='all' selected>All</option>\n";                                
        foreach ($formatted_groups AS $key => $value) {            
            $elements = $gacl_api->get_group_objects($key);

            // show only if user belongs to group
            if (@in_array ($_SESSION['user_id'], $elements['Person'])) {
                
                // find out mandator
                $man_query = "
                    SELECT mandator_id from ".TABLE_PREFIX."gacl_aro_groups ag
                    LEFT JOIN ".TABLE_PREFIX."group_details gd ON ag.value = gd.id
                    WHERE ag.id=".$key;
                $man_res = mysql_query ($man_query);
                $man_row = mysql_fetch_array($man_res);
                //echo "ID: ".$man_row[0]."($key), ";
                if ($man_row[0] == $_SESSION['mandator']) {
                    //echo "Bingo";
                    ($sel_id == $key) ? $selected = "selected" : $selected = "";
                    $options .= "<option value='$key' $selected>".$value."</option>\n";   
                    $single_hit_id = $key;             
                    $cnt++;
                }
            }    
        }    
		return array ($options, $cnt, $single_hit_id);
	}

   /**
    * get owner options
    *
    * get all users who have access to a group the current user belongs to
    *
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.4.4
    */
	function get_owner_options ($sel_id, $show_all_option = false) {
        global $gacl_api;

        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
        $my_groups        = array ();
        $cnt              = 0;

        foreach ($formatted_groups AS $key => $value) {            
            $elements = $gacl_api->get_group_objects($key);
            if (@in_array ($_SESSION['user_id'], $elements['Person'])) {
                $my_groups[] = $key;
            }    
        }    

        // get all users (distinct) who are at least in one of these groups
        $options = '';
        if ($show_all_option)
            $options .= "<option value='all' selected>All</option>\n";     
        $users   = array ();
        foreach ($my_groups AS $key => $my_group) {
            $groups_users = get_all_users_for_group ($my_group);
            foreach ($groups_users AS $new_user => $name) {
                if (!in_array ($new_user, $users))
                    $users[$new_user] = $name;
                    $cnt++;
            }    
        }                                   
        foreach ($users AS $user => $name) {
            ($user == $sel_id) ? $sel = "selected" : $sel = "";
            $options .= "<option value='$user' $sel>".get_username_by_user_id($user)."</option>\n";     
        }
        
		return array ($options, $cnt);
	}

   /**
    * get carer options
    *
    * default implementation: 
    * get all users who have access to a group the current user belongs to provided
    * the current user is the owner. In the other case, just return one entry containing
    * the current carer.
    *
    * if there is an entry in table mandatory for "acl_inc_php" (for the current mandator),
    * than include the code found in "custom" directory  
    *
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.5.1
    */
	function get_carer_options ($sel_id) {
        global $gacl_api;

        // --- use custom implementation ? ----------------------------------------
        $custom = getMandatorCustomCode ('acl_inc_php');
        if ($custom != '') {
            assert ('substr_count ($custom, ".") == 0'); // security reasons
            $custom_path = "custom/".$custom."/get_carer_options.inc.php";
            include ($custom_path);    
            return array ($options, $cnt);
        }    

        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
        $my_groups        = array ();
        $cnt              = 0;

        // --- current user is not owner of entry? ---------------------------------
        if ($_SESSION['user_id'] != $sel_id) {
            $options .= "<option value='$sel_id' selected>".get_username_by_user_id($sel_id)."</option>\n";                         
    		return array ($options, 1);
        }    

        foreach ($formatted_groups AS $key => $value) {            
            $elements = $gacl_api->get_group_objects($key);
            if (@in_array ($_SESSION['user_id'], $elements['Person'])) {
                $my_groups[] = $key;
            }    
        }    

        // get all users (distinct) who are at least in one of these groups
        $options = '';
        $users   = array ();
        foreach ($my_groups AS $key => $my_group) {
            $groups_users = get_all_users_for_group ($my_group);
            foreach ($groups_users AS $new_user => $name) {
                if (!in_array ($new_user, $users))
                    $users[$new_user] = $name;
                    $cnt++;
            }    
        }                                   
        foreach ($users AS $user => $name) {
            ($user == $sel_id) ? $sel = "selected" : $sel = "";
            $options .= "<option value='$user' $sel>".get_username_by_user_id($user)."</option>\n";     
        }
        
		return array ($options, $cnt);
	}

   /**
    * get html for restricted access
    *
    * 
    * @access       public
    * @return       array 
    * @since        0.4.0
    * @version      0.5.1
    */
    function get_restricted_access_html (
		    	$section, 
		    	$view_permission, 
		    	$edit_permission, 
		    	$return_to, 
		    	$img_path, 
		    	$path_offset = '',
		    	$datagrid = '') {
        
        global $gacl_api;
            
        $access_for = '<b>'.translate ('access permitted for', null, true).'</b>:<br>';
        $users           = get_users_with_access ($gacl_api, $section, $view_permission, 'Person');

        foreach ($users AS $tmp =>$key) 
            $access_for .= get_username_by_user_id ($key)."<br>";
        $access_for .= '<b>'.translate ('may change permission', null, true).'</b>:<br>';
        $users           = get_users_with_access ($gacl_api, $section, $edit_permission, 'Person');
        foreach ($users AS $tmp =>$key) 
            $access_for .= get_username_by_user_id ($key)."<br>";
    
        $html = translate ('restricted access', null, true)."
                : 
                <a href='javascript:void(0);' 
                    onClick='return overlib(\"".$access_for."\", STICKY, CAPTION, \"".translate('restricted access', null, true)."\", RIGHT);' 
                    onMouseOut='nd();'><img src='".$img_path."eye.gif' border=0 align=middle title='".translate('show permissions', null, true)."'></a>&nbsp;
                ";
                
        if ($gacl_api->acl_check($section, $edit_permission, 'Person', $_SESSION['user_id'])) { 
            $html .= "
                <a href='".$path_offset."acl_list.php?section_value=".$section."&return_to=".$return_to;
            $html .= "'><img src='".$img_path."change.gif' border=0 align=middle title='".translate('edit permissions', null, true)."'></a>";
        }
           
        // !!! todo!         
        if ($gacl_api->acl_check($section, 'Edit Datagrid', 'Person', $_SESSION['user_id'])) { 
            $html .= "
                <a href='../../modules/datagrids/index.php?command=edit_datagrid";
            $html .= "&datagrid=".$datagrid;
            $html .= "'><img src='".$img_path."datagrid.gif' border=0 align=middle title='".translate('edit datagrid', null, true)."'></a>";
        }

        return $html;
    }        

?>