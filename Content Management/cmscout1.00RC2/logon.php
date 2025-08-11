<?php
/**************************************************************************
    FILENAME        :   logon.php
    PURPOSE OF FILE :   Checks users logon credientials and sends user to correct place
    LAST UPDATED    :   11 October 2005
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
// Start Code
if($_POST['Login'] == "Login")
{
    include_once ("common.php");

    $query = $_SERVER['QUERY_STRING'];
    $redirectpage = str_replace("redirect=", "", $query);

    $uname = strip_tags($_POST['username']);
	$pass = md5(strip_tags($_POST['password']));
	$detail = $Auth->authenticate($uname, $pass);
    
	if ($detail==0 || $detail['uname'] == "Guest")
	{
        show_message("Error: There is a error with your username or password. Please try again.");
        echo "<script>window.location='index.php'</script>";
    }
	else 
	{
        if ($redirectpage != "" && $_GET['redirect'] != "register" && $_GET['redirect'] != "forgot")
        {
            header("Location: index.php?page=$redirectpage");
        }
        else
        {
            header("Location: index.php");
        }
    }
    exit;
}
else
{
    $action = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }  
    
    if ($_POST['relogon'] == "Login")
    {
    	$query = $_SERVER['QUERY_STRING'];
        $redirectpage = str_replace("page=logon&redirect=", "", $query);
        
        $uname = strip_tags($_POST['username2']);
	    $pass = md5(strip_tags($_POST['password2']));
        $detail = $Auth->authenticate($uname, $pass);
        
        if ($detail==0 || $detail['uname'] == "Guest")
        {
            show_message("Error: There is a error with your username or password. Please try again.");
            echo "<script>window.location='index.php'</script>";
        }
        else 
        {
            header("Location: index.php?page=$redirectpage");
        }
        exit;
    }
    
    
    $tpl->assign("action", $action);
    $dbpage = true;
    $pagename="logon";
}
?>