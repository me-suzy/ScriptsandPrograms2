<?php
/**************************************************************************
    FILENAME        :   index.php
    PURPOSE OF FILE :   Main file, fetches pages
    LAST UPDATED    :   22 November 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
$bit = "./";
include_once("common.php");
$error = false;
$logout = false;

if (isset($_GET['ex'])) $extra = $_GET['ex']; else $extra = "";
$templateinfo = ( isset($check['theme_id']) ) ? change_theme_dir($check['theme_id']) : change_theme_dir();

$tpl->assign("templateinfo", $templateinfo);
$islogged = false;
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = "";
$message = "";

if ($action == 'logout') 
{
    $islogged = false;
    $Auth->logout();
    $tempcss = change_theme_dir();
    $loggedout = true;
    $panel = 'N';
    $tpl->assign('adminpanel', $panel);
    $server = $_SERVER['PHP_SELF'];
    show_message("You have been loged out. Please visit again soon.");
    echo("<script>window.location=\"$server\"</script>");
    exit;
}
	/********************************************Begin Initilization of page*****************************************/
    require_once("menu.php");
    $tpl->assign('title',$config['troopname'] . ' Website - ' . $config['troop_description']);
	$tpl->assign('logout',$logout);
	$tpl->assign('islogout', '0');

    if (isset($check["uname"]) && $check['uname'] != "Guest" && $check['level'] != -1) {
        $tpl->assign('name',$check["uname"]);
        $level = $check["level"];
        $panel = 'N';
        if (($level == 4 || $level == 3 || $level == 2 || $level == 1 || $level == 0) && !$config['disablesite']) 
        {
            $panel = 'Y';
        }
        elseif ($config['disablesite'] && $level == 1 || $level == 0)
        {
            $panel = 'Y';
        }
	} 
    else 
    {
	    $islogged = false;
	    $panel = 'N';
	}
    
	$userdisp = '';
	if (isset($loggedout)) 
    {
	 $tpl->assign('islogout', '1');
	 $islogged = false;
	}
    
if (!$config['disablesite']) 
{   
    if ($extra != "nomenu") 
    {
        $userdisp = "Welcome <strong>Guest</strong><br />";
        if ($config['register'] == 1)
        {
            $userdisp .= "<a href=\"index.php?page=register\" class=\"top\">Please Register</a> or ";
        }
        $userdisp .= "<a href=\"index.php?page=logon\" class=\"top\">Login</a>";
       
        $uname = $check["uname"];
        if ($check["uname"] != "Guest" && $check['level'] != -1) 
        {
            $islogged = true;
            $userdisp = "Welcome <strong>$uname</strong> - <a href=\"index.php?action=logout\" class=\"top\">Logout</a>";
            
            $lastlogged = $check['prevlogin'];
            
            
            
            $sql = $data->select_query("pms", "WHERE type=1 AND newpm=1 AND touser='{$check["uname"]}'");
            if ($data->num_rows($sql) && $_GET['page'] != "pmmain")
            {
                $tpl->assign("newpm", 1);
            }
            else
            {
                $tpl->assign("newpm", 0);
            }
        }
        $inarticle = false;
            
        //Advert Code
        $tpl->assign('adcode', $config['adcode']);
    }
    if (!$error) 
    {
        $show = 'yes';
    }
           
	/********************************************End Initilization of page*****************************************/

	/********************************************Start Content Generation*****************************************/
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = "";
    
    if (!$data->num_rows($data->select_query("allowedpages", "WHERE page = '$page'")))
    {
        $page = "";
    }
    
	$dataC = false;
	$dbpage = false;
	if (isset($_GET['pagenum'])) $pagenum = $_GET['pagenum']; else $pagenum = 0;
    $filetouse = "";
    //Get Auth Data for user
    $authsql = $data->select_query("auth", "WHERE page='$page'");
    $authtemp = $data->fetch_array($authsql);
    if (isset($authtemp['level'])) $auths = unserialize($authtemp['level']);
    
    $usergroup = $check['team'];
    if (!isset($usergroup) || $usergroup == '')
        $usergroup = "Guest";
    
    if (isset($auths)) $userauth = isset($auths[$usergroup]) ? $auths[$usergroup] : 1;
    if (!$data->num_rows($authsql))
        $userauth=1;
        
    if ($userauth == 1)
    {
        $filetouse = get_spec($page);
        if ($filetouse)
        {
            $dataC = true;
            
            location($page, $check["uid"]);
            $highlight = isset($_GET['highlight']) ? unserialize(stripslashes(html_entity_decode($_GET['highlight']))): '';
            if (!empty($highlight) && isset($highlight))
            {
                $terms_rx = search_rx_escape_terms($highlight);
        
                $filetouse = search_highlight($filetouse, $terms_rx);
            }
        }
        elseif (file_exists($page . $phpex)) 
        {
            include($page . $phpex);
        } 
        elseif ($page == '' || !isset($page)) 
        {
            include('welcome' . $phpex);	
        } 

       
           
        if ($pagenum == 0) 
        {
            $pagenum = 1;
        }
    
        if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage")
        {
            $dataC = true;
            $filetouse = get_temp($pagename, $pagenum);
        } 
        elseif (isset($pagename) && $pagename == "frontpage")
        {
            $dataC = true;
            $filetouse = $content;
        }

        if ($filetouse == "")
        {
            $filetouse = $pagename;
        }

        if ((!isset($filetouse) || $filetouse == "") && (!isset($pagename) || $pagename == ""))
        {
            $dataC = true;
            $filetouse = "No Content";
        }
    } 
    else 
    {
        if ($check['uname'] != "Guest")
        {
           error_message("You don't have the required permisions to see this page");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage");
        }
    }

/********************************************End Content Generation*****************************************/
} 
else 
{
	$show = false;
    $message = "";
	$message = "Sorry, the site has been disabled. Only the site administrator can reenable it.<br /><br />The reason for the site being disabled is:<br /> ";
	$message .= $config['disablereason'];
    
    $userdisp = 'Welcome Guest - Unfortunatly the site is currently disabled';
    
    $islogged = false;
    $uname = $check["uname"];
    if ($uname) 
    {
        $islogged = true;
        $userdisp = "Welcome $uname - <a href=\"index.php?action=logout\" class=\"top\">Logout</a>";
        $tpl->assign('loggedin','true');
    }
}

if($message != "")
{
        $filetouse = "";
}

$tpl->assign("pagename", $page);
$tpl->assign('config', $config);
$tpl->assign('show', $show);
$tpl->assign('message', $message);
$tpl->assign('error', $error);
$tpl->assign('debug', $debug);
$tpl->assign('adminpanel', $panel);
$tpl->assign('extra', $extra);
$tpl->assign('content', $filetouse);
$tpl->assign('dataC', $dataC);
$tpl->assign("photopath", $config["photopath"] . "/");
$tpl->assign('userdisp', $userdisp);
$tpl->assign('islogged', $islogged);
$tpl->assign('adminpanel', $panel);
$tpl->assign('usersname', $check['uname']);
$tpl->assign('uname', $check['uname']);
$tpl->assign("timeoffset", getuseroffset($check['uname']));
if (isset($patrolpoints)) $tpl->assign('patrolpoints', $patrolpoints);
if (isset($percentage)) $tpl->assign('percenttotal', $percentage);

include("page_footer.php");
//Compile page

$tpl->display('index.tpl');

$error = false;
$loggedout = false;
?>