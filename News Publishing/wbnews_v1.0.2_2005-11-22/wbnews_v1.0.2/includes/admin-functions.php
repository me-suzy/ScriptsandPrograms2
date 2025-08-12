<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  1st August 2005                        #||
||#     Filename: admin-functions.php                    #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package AdminCP
	@todo Complete section
*/

if (!defined('wbnews'))
	die('Hacking Attempt');

/**
    @param DB db - Database Class Object
    @return boolean
*/
function checkLogged($db)
{
    if (isset($_SESSION['wbnews-admin_login']))
    {
        if ($db->db_checkRows("SELECT u.userid, " . TBL_UGROUPS . ".usergroupid
                               FROM " . TBL_USERS . " as u
                               LEFT JOIN " . TBL_UGROUPS . " ON (u.usergroupid = " . TBL_UGROUPS . ".usergroupid)
                               WHERE u.userid = '".(int)$_SESSION['wbnews-admin_login']['userid']."'
                               AND " . TBL_UGROUPS . ".cancontrol = '1'
                               ") === true)
            return true;
    }
    
    return false;
}

/**
    Checks if the user/pass details validates, creates the session
    and returns either true/flase

    @param DB db        - Database Class Object
    @param String user  - Username
    @param String pass  - Password
    @param String salt  - The Password Salt
    @return boolean
*/
function loginAdminUser($db, $user, $pass, $salt)
{
    
    $user = addslashes(htmlentities($user));
    $pass = md5($pass . $salt);
    
    $checkLogin = $db->db_query("SELECT u.userid, " . TBL_UGROUPS . ".usergroupid, u.username
                                 FROM " . TBL_USERS . " as u
                                 LEFT JOIN " . TBL_UGROUPS . " ON (u.usergroupid = " . TBL_UGROUPS . ".usergroupid)
                                 WHERE u.username = '".$user."'
                                 AND u.password = '".$pass."'
                                 AND " . TBL_UGROUPS . ".cancontrol = '1'
                                 ");
    
    if ($db->db_numrows($checkLogin))
    {
        $user = $db->db_fetcharray($checkLogin);
        $_SESSION['wbnews-admin_login'] = $user;
        return true;
    }
    else
        return false;
}

/**
    Just Delete the Admin Session
    
    @return boolean
*/
function logoutAdminUser()
{
    unset($_SESSION['wbnews-admin_login']);
    if (isset($_SESSION['userid']))
        unset($_SESSION['userid']);
    return true;
}

/**
    Gets menu sections
    
    @param Object db    - Database Abstraction Class
    @param Object tpl   - Template Class
    @return String
*/
function getMenuSections($db, $tpl)
{
    $getMenuSections = $db->db_query("SELECT * 
                                      FROM " . TBL_MENUSECTIONS . "
                                      ");
    
    $template = trim($tpl->getTemplate('menu-sections'));
    $getMenu = false;
    if (preg_match("/{MENU}/is", $template))
        $getMenu = true;
    
    $contents = "";
    while ($sections = $db->db_fetcharray($getMenuSections))
    {
        if ($getMenu === true)
            $sections['MENU'] = getMenu($db, $tpl, $sections['sectionid']);
        $contents .= $tpl->replace($template, $sections);
    }
    
    return $contents;
}

/**
    Gets all the menus and organizers them appropriately, it can be
    used to get a specific menu section only

    @param Object db    - Database Abstraction Class
    @param Object tpl   - Template Class
    @param INT id       - (Optional) the id for that menu section
    @return String
*/
function getMenu($db, $tpl, $id = false)
{
    // get menu sections
    $getMenuSections = $db->db_query("SELECT name_id
                                      FROM " . TBL_MENUSECTIONS . "
                                      ");
                                      
    while ($rows = $db->db_fetcharray($getMenuSections))
        $sections[] = $rows;
    
    //get menu
    $getMenu = $db->db_query("SELECT s.name_id, m.*
                              FROM " . TBL_MENU . " as m, " . TBL_MENUSECTIONS . " as s
                              WHERE s.sectionid = m.sectionid
                              " . ($id != false ? "AND m.sectionid = '".(int)$id."'" : "") ."
                              ");
                              
    while ($rows = $db->db_fetcharray($getMenu))
        $menu[] = $rows;
    
    $contents = "";
    
    $numSections = sizeof($sections);
    $numMenu = sizeof($menu);
    
    // get all together correctly
    $menuTemplate = trim($tpl->getTemplate('menu'));
    for ($i = 0; $i < $numSections; $i++)
    {
        $code['menu']= "";
        for ($j = 0; $j < $numMenu; $j++)
            if ($menu[$j]['name_id'] == $sections[$i]['name_id'])
            {
                $menu[$j]['onclick'] = (!empty($menu[$j]['onclick']) ? ' onclick="'.$menu[$j]['onclick'].'"' : "");
                $code['menu'] .= $tpl->replace($menuTemplate, $menu[$j]);
            }
            
        $code['name_id'] = $sections[$i]['name_id'];
        $contents .= $tpl->replace($tpl->getTemplate('menu-container'), $code);
    }
    
    // return contents
    return $contents;
}

/**
    @param template tpl         - The Template class to call the redirect template and other template
    @param array array          - An Associate Array 
    @param String gotoURL       - The URL to go to for the redirect
    @return void
*/
function redirect($tpl, $array, $gotoURL)
{
    $replace = array(
                    "title" => $array['title'],
                    "message" => $array['message'],
                    "GOTO_URL" => $gotoURL
                    );
                    
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate("redirect"), $replace));
}

/**
    @return Array
*/
function timezone()
{
    $array["-12"] = "(GMT -12:00 hours) Eniwetok, Kwajalein";
    $array["-11"] = "(GMT -11:00 hours) Midway Island, Samoa";
    $array["-10"] = "(GMT -10:00 hours) Hawaii";
    $array["-9"] = "(GMT -9:00 hours) Alaska";
    $array["-8"] = "(GMT -8:00 hours) Pacific Time (US & Canada)";
    $array["-7"] = "(GMT -7:00 hours) Mountain Time (US & Canada)";
    $array["-6"] = "(GMT -6:00 hours) Central Time (US & Canada)";
    $array["-5"] = "(GMT -5:00 hours) Eastern Time (US & Canada)";
    $array["-4"] = "(GMT -4:00 hours) Atlantic Time (Canada), Caracas";
    $array["-3.5"] = "(GMT -3:30 hours) Newfoundland";
    $array["-3"] = "(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown";
    $array["-2"] = "(GMT -2:00 hours) Mid-Atlantic";
    $array["-1"] = "(GMT -1:00 hours) Azores, Cape Verde Islands";
    $array["+0"] = "(GMT) Western Europe Time, London, Lisbon";
    $array["+1"] = "(GMT +1:00 hours) CET(Central Europe Time)";
    $array["+2"] = "(GMT +2:00 hours) EET(Eastern Europe Time)";
    $array["+3"] = "(GMT +3:00 hours) Baghdad, Riyadh, Moscow";
    $array["+3.5"] = "(GMT +3:30 hours) Tehran";
    $array["+4"] = "(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi";
    $array["+4.5"] = "(GMT +4:30 hours) Kabul";
    $array["+5"] = "(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi";
    $array["+5.5"] = "(GMT +5:30 hours) Bombay, Calcutta, Madras";
    $array["+6"] = "(GMT +6:00 hours) Almaty, Dhaka, Colombo";
    $array["+7"] = "(GMT +7:00 hours) Bangkok, Hanoi, Jakarta";
    $array["+8"] = "(GMT +8:00 hours) Beijing, Perth, Singapore";
    $array["+9"] = "(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo";
    $array["+9.5"] = "(GMT +9:30 hours) Adelaide, Darwin";
    $array["+10"] = "(GMT +10:00 hours) AEST(Australian East Standard)";
    $array["+11"] = "(GMT +11:00 hours) Magadan, Solomon Islands";
    $array["+12"] = "(GMT +12:00 hours) Auckland, Wellington, Fiji";
    
    return $array;
}

/**
    @return Array
*/
function getThemes()
{
    global $dbclass;
    return $dbclass->db_fetchall("SELECT * FROM ".TBL_THEMES, "themeid", "title");
}

/**
    @return Array
*/
function usergroups()
{
    global $dbclass;
    return $dbclass->db_fetchall("SELECT * FROM ".TBL_UGROUPS, "usergroupid", "title");
}

/**
    Checks if a user has permission to do a certain task and returns boolean result

    @param Object db        - Database Abstraction Class
    @param String file      - The file name the user is accessing
    @param String action    - The action (if specified) the user is using on the file
    @return boolean
*/
function admin_permissions($db, $file, $action = '')
{
    //print_r($_SESSION['wbnews-admin_login']); Array ( [userid] => 1 [usergroupid] => 1 [username] => paul ) 
    
    $group = $db->db_fetcharray($db->db_query("SELECT * FROM " . TBL_UGROUPS . "
                                               WHERE usergroupid = '" . (int)$_SESSION['wbnews-admin_login']['usergroupid'] . "'
                                               "));
    switch ($file)
    {
        
    case PAGE_UPDATE:
        return ($group['checkupdate'] == 1 ? true : false);
    break;
    case PAGE_CONFIG:
        return ($group['canconfig'] == 1 ? true : false);
    break;
    case PAGE_DB:
        return ($group['canbackup'] == 1 ? true : false);
    break;
    case PAGE_NEWS:
        if ($action == "add")
            return ($group['addnews'] == 1 ? true : false);
        else if ($action == "modify")
            return ($group['modifynews'] == 1 ? true : false);
        else if ($action == "delete")
            return ($group['deletenews'] == 1 ? true : false);
        else
            return true;
    break;
    case PAGE_COMMENT:
        return ($group['editcomment'] == 1 ? true : false);
    break;
    case PAGE_CAT:
        if ($action == "add")
            return ($group['addcategory'] == 1 ? true : false);
        else if ($action == "modify")
            return ($group['modifycat'] == 1 ? true : false);
        else if ($action == "delete")
            return ($group['deletecat'] == 1 ? true : false);
        else
            return true;
    break;
    case PAGE_USER:
        if ($action == "add")
            return ($group['adduser'] == 1 ? true : false);
        else if ($action == "modify")
            return ($group['modifyuser'] == 1 ? true : false);
        else if ($action == "delete")
            return ($group['deleteuser'] == 1 ? true : false);	
        else
            return true;
    break;
    case PAGE_UGROUP:
        return ($group['usergroups'] == 1 ? true : false);
    break;
    case PAGE_THEME:
        if ($action == "add")
            return ($group['addtheme'] == 1 ? true : false);
        else if ($action == "modify")
            return ($group['modifytheme'] == 1 ? true : false);
        else if ($action == "delete")
            return ($group['deletetheme'] == 1 ? true : false);
        else
            return true;
    break;
    default:
        return true;
    break;
        
    }
}

function auto_parseurl($string)
{
    $string = preg_replace("#(^|[\n ])(http:\/\/|www\.)(\S+)#is", " [url=http://\\3]\\2\\3[/url]", $string);
    return $string;
}

?>
