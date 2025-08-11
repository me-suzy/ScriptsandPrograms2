<?php
/**************************************************************************
    FILENAME        :   addevent.php
    PURPOSE OF FILE :   Add a users event to the database
    LAST UPDATED    :   08 June 2005
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
location("Adding a notice", $check["uid"]);
/********************************************Check if user is allowed*****************************************/
if (isset($check["uname"])) {
 $tpl->assign('name',$check["uname"]);
}

$message = "";
$uname = $check["uname"];
if (!$error) {
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
	$submit = $_POST['Submit'];
	if ($submit == "Submit") 
    {        
        if ($_POST['summary'] == '')
        {
            error_message("You need to enter a title for the calender event");
            exit;
        }
        if ($_POST['sdate'] == '')
        {
            error_message("You need to supply a start date");
            exit;
        }
        
        if (!validdate($_POST['sdate']))
        {
            error_message("The start date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender");
            exit;
        }
        if ($_POST['edate'] == '')
        {
           $_POST['edate'] = $_POST['sdate'];
        }   
        elseif (!validdate($_POST['edate']))
        {
            error_message("The end date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender");
            exit;
        }     
        if ($_POST['is_there_details'] == 1 && $_POST['story'] == '')
        {
            error_message("You indicated that you want to add extra information, but you didn't.");
            exit;
        }
        $insert = sprintf("'', %s, %s, %s, %s", 
    	                    safesql($_POST['summary'], "text"),
		                    safesql($_POST['sdate'], "date"),
		                    safesql($_POST['edate'], "date"),
		                    safesql($_POST['is_there_details'], "int"));
		
        if ($config['confirmevent'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) 
        {
            $allow = 0;
            $extra = "The administrator first needs to publish your event before it will be visible on the website";
            publish_mail($check['uname'], "Event",$_POST['summary']);
        }
        else $allow = 1;
		$sql = $data->insert_query("calendar_items", "$insert, '{$check['uname']}' , $allow", "Calendar Admin", "Insert item $summary");
		$detail = safesql($_POST['story'], "text", false);
		if ($_POST['is_there_details'] == 1) 
        {
				$calsql = $data->select_query("calendar_items", "WHERE summary = '{$_POST['summary']}'");
				$items = $data->fetch_array($calsql);
				$itemid = $items['id'];
				$data->insert_query("calendar_detail", "$itemid, $detail", "", "", false);
		}
        echo "<script> alert('Your event has been added.$extra'); window.location = 'index.php?page=mythings';</script>\n";
        exit;
	}
}

$tpl->assign("isedit", "adv");

$dbpage = true;
$pagename = "addevent";
?> 