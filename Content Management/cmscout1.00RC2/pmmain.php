<?php
/**************************************************************************
    FILENAME        :   pmmain.php
    PURPOSE OF FILE :   Manages the private messenger
    LAST UPDATED    :   12 August 2005
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($_GET['action'])) $action = $_GET['action'];
$pagenum = 1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$editit = false;
$reply = false;
$sendit= false;
switch($action)
{
    case "sentbox": 
        include("pm/sentbox.php");
        break;
    case "savebox": 
        include("pm/savebox.php");
        break;
    case "drafts": include("pm/drafts.php");
        break;
    case "readpm":
        include("pm/readpm.php");
        break;
    case "typepm":
        include("pm/typepm.php");
        break;
    case "send":
        $sendit= true;
        include("pm/typepm.php");
        break;
    case "edit":
        $editit = true;
        include("pm/typepm.php");
        break;    
    case "reply":
        $reply = true;
        include("pm/typepm.php");
        break;
    case "save":
        $pid = $_GET['id'];
        $sql = $data->update_query("pms", "type=3", "id=$pid", "", "", false);
        if($sql)
        {
            show_message("You message has been saved");
            echo("<script>window.location = 'index.php?page=pmmain';</script>");
            exit;
        }
        break;
    case "delete":
        $pid = $_GET['id'];
        $sql = $data->delete_query("pms", "id=$pid", "", "", false);
        $oldpage = $_GET['old'];
        if($sql)
        {
            show_message("The message has been deleted");
            switch ($oldpage)
            {
                case "Inbox":
                    echo("<script>window.location = 'index.php?page=pmmain';</script>");
                    break;
                case "Sentbox":
                    echo("<script>window.location = 'index.php?page=pmmain&action=sentbox';</script>");
                    break;
                case "Savebox":
                    echo("<script>window.location = 'index.php?page=pmmain&action=savebox';</script>");
                    break;
                case "Drafts":
                    echo("<script>window.location = 'index.php?page=pmmain&action=drafts';</script>");
                    break;
                case "readpm":
                    echo("<script>window.location = 'index.php?page=pmmain';</script>");
                    break;
                default:
                    echo("<script>window.location = 'index.php?page=pmmain';</script>");
                    break;
            }
            exit;
        }
        break;
    default: include("pm/inbox.php");
}

$tpl->assign("username", $check['uname']);
$tpl->assign("userauths", $userauths);
$tpl->assign('editFormAction', $editFormAction);  
$dbpage = true;
$pagename = "pm";
?>