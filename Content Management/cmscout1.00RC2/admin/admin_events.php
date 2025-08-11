<?php
/**************************************************************************
    FILENAME        :   admin_events.php
    PURPOSE OF FILE :   Manages events on the calender
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
if( !empty($getmodules) )
{
	$module['Troop Content Management']['Events'] = "events";
    $permision['Events'] = 2;
	return;
}

if ($level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$action = $_GET['action'];
if (!isset($action)) {$action = "";}
 
$id = $_GET['id'];
 
if ($action == "edit") 
{
	$calsql = $data->select_query("calendar_items", "WHERE id = $id");
	$calsqldetail = $data->select_query("calendar_detail", "WHERE id = $id");
	$items = $data->fetch_array($calsql);
	$numdetail = $data->num_rows($calsqldetail);
	$detail = $data->fetch_array($calsqldetail);
	$tpl->assign('detail', $detail['detail']);
	$submit = $_POST['Submit'];
	if ($submit == "Update Item")
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
        if ($_POST['edate'] == '')
        {
           $_POST['edate'] = $_POST['sdate'];
        }        
        if ($_POST['is_there_details'] == 1 && $_POST['story'] == '')
        {
            error_message("You indicated that you want to add extra information, but you didn't.");
            exit;
        }
        
        $summary = safesql($_POST['summary'], "text");
	    $startdate = safesql($_POST['sdate'], "date");
	    $enddate = safesql($_POST['edate'], "date");
		$isdetail = safesql($_POST['is_there_details'], "int");
		$detail = safesql($_POST['editor'], "text", false);
		
		$sql = $data->update_query("calendar_items", "summary = $summary, startdate = $startdate, enddate = $enddate, detail = $isdetail", "id = $id", "Calendar Admin", "Updated item $itemid");
		
		if ($isdetail == 1) 
        {
			if ($numdetail == 1) 
            {
				$sql2 = $data->update_query("calendar_detail", "detail = $detail", "id = $id", "", "", false);
			} 
            else 
            {
				$sql2 = $data->insert_query("calendar_detail", "$id, $detail", "", "", false);
			}
            if ($sql && $sql2)
            {
                echo "<script> alert('Event updated'); window.location = '$pagename';</script>\n";
                exit; 
            }
		}
        else
        {
            if ($sql)
            {
                echo "<script> alert('Event updated'); window.location = '$pagename';</script>\n";
                exit; 
            }
        }
		$action = '';
	}
} 
elseif ($action == "add") 
{
	$submit = $_POST['Submit'];
	if ($submit == "Add Item") 
    {       
        $insert = sprintf("'', %s, %s, %s, %s", 
    	                    safesql($_POST['summary'], "text"),
		                    safesql($_POST['sdate'], "date"),
		                    safesql($_POST['edate'], "date"),
		                    safesql($_POST['is_there_details'], "int"));
		
		$sql = $data->insert_query("calendar_items", "$insert, '{$check['uname']}' , 1", "Calendar Admin", "Insert item $summary");
		$detail = safesql($_POST['editor'], "text");
		if ($_POST['is_there_details'] == 1) 
        {
				$calsql = $data->select_query("calendar_items", "WHERE summary = '{$_POST['summary']}'");
				$items = $data->fetch_array($calsql);
				$itemid = $items['id'];
				$sql2 = $data->insert_query("calendar_detail", "$itemid, $detail", "", "", false);
                if ($sql && $sql2)
                {
                    echo "<script> alert('Event Added'); window.location = '$pagename';</script>\n";
                    exit; 
                }
		}
        else
        {
            if ($sql)
            {
                echo "<script> alert('Event Added'); window.location = '$pagename';</script>\n";
                exit; 
            }
        }
		$action = "";
	}
} 
elseif ($action == "delete") 
{
    $sql = $data->delete_query("calendar_items", "id = $id", "Calendar Admin", "Deleted item $id");
    $sql2 = $data->delete_query("calendar_detail", "id =  $id", "", "", false);
    if ($sql) {
        echo "<script> alert('Event deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
}
elseif ($action == 'publish') {
	$sqlq = $data->update_query("calendar_items", "allowed = 1", "id = $id", "Calendar Admin", "Published $id");
    header("Location: $pagename");
}
elseif ($action == 'unpublish') {
	$sqlq = $data->update_query("calendar_items", "allowed = 0", "id=$id", "Calendar Admin", "Unpublished $id");
    header("Location: $pagename");
}


if (!$action) 
{
	$calsql = $data->select_query("calendar_items");
	$numitems = $data->num_rows($calsql);
	$items = array();
	while ($items[] = $data->fetch_array($calsql));
}

$date = getdate();
$tpl->assign("year", $date['year']);
$tpl->assign('events', $items);
$tpl->assign('numevents', $numitems);
$tpl->assign("editor", true);
$tpl->assign('action', $action);
$filetouse = "admin_events.tpl";
?>