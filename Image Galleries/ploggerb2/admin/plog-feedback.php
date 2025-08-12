<?php

require("plog-globals.php");
require_once("../plog-load_config.php"); 					// load configuration variables from database
require_once("../plog-functions.php");
require_once("plog-admin-functions.php");
error_reporting(E_ALL);


global $inHead;

$inHead = '<script type="text/javascript" src="js/plogger.js"></script>';
		
	
function generate_pagination_view_menu() {
	
	$java = 'document.location.href = \''.$_SERVER["PHP_SELF"].'?'.
	'&amp;entries_per_page=\'+this.options[this.selectedIndex].value';
	
	$possible_values = array("5"=>5, "10"=>10, "20"=>20, "50"=>50);
	$output= 'Entries per page <select onchange="'.$java.'" name="entries_per_page">';
	
	foreach ($possible_values as $key => $value)
		if ($_SESSION['entries_per_page'] == $key)
			$output .= "<option value=\"$value\" selected>$key</option>";
		else
			$output .= "<option value=\"$value\">$key</option>";
			
	$output.= '</select>';
	
	return $output;				

}

$level = "comments";

$output = '<h1>Manage Feedback</h1>';


// here we will determine if we need to perform a move or delete action.
if (isset($_REQUEST["action"])) {
	$num_items = 0;
	
	if ($_REQUEST["action"]== "Delete Checked") {
		// perform the delete function on the selected items
		
		if (isset($_REQUEST["Selected"])) {
			foreach($_REQUEST["Selected"] as $del_id) {
				// lets build the query string
				$del_id = intval($del_id);
				
				$query = "DELETE FROM ".$TABLE_PREFIX."comments WHERE `id`= '$del_id'";
				$result = run_query($query);
				
				$num_items++;
			}
			
			$output .= "<p class=\"actions\">You have deleted $num_items entry(s) successfully.</p>";
		}
		else{
			$output .= "<p class=\"errors\">Nothing selected to delete!</p>";
		}
	}
	else if ($_REQUEST["action"] == "edit-comment") {
		// show the edit form
		$comment_id = intval($_REQUEST["pid"]);
		$sql = "SELECT * FROM ".$TABLE_PREFIX."comments c WHERE c.id = '$comment_id'";
		$result = run_query($sql);
		$comment = mysql_fetch_assoc($result);
		$output .= '<form class="edit" action="'.$_SERVER["PHP_SELF"].'" method="post"><table>';

		$output .= '<tr><td>Author:<br/><input size="30" name="author" id="author" value="'.SmartStripSlashes($comment['author']).'"></td>
				    <td>Email:<br/><input size="30" name="email" id="email" value="'.SmartStripSlashes($comment['email']).'"></td>
					<td>Website:<br/><input size="30" name="url" id="url" value="'.SmartStripSlashes($comment['url']).'"></td></tr>
					<tr><td colspan="3">Comment:<br/> <textarea cols="70" rows="4" name="comment" id="comment">'.
					SmartStripSlashes($comment['comment']).'</textarea></td></tr></table>';
					
		$output .= '<input type="hidden" name="pid" value="'.$comment['id'].'"><input type="hidden" 
					name="action" value="update-comment"><button class="submit" type="submit">Update</button>';
		
		$output .= '</form>';
		
	}

	else if ($_POST["action"] == "update-comment") {
		// update comment in database
		$result = update_comment($_POST["pid"],$_POST["author"],$_POST["email"],$_POST["url"],$_POST["comment"]);
		if (isset($result['errors'])) {
			$output .= '<p class="errors">' . $result['errors'] . '</p>';
		} elseif (isset($result['output'])) {
			$output .= '<p class="actions">' . $result['output'] . '</p>';

		}
	}

}

$output .= '<form id="contentList" action="'.$_SERVER["PHP_SELF"].'" method="get">';


$allowedCommentKeys = array("unix_date", "author", "email", "url", "comment");


// lets iterate through all the content and build a table
// set the default level if nothing is specified

// handle pagination
// lets determine the limit filter based on current page and number of results per page
if (!isset($_REQUEST["page"])) $_REQUEST["page"] = "1"; // we're on the first page

if (isset($_REQUEST['entries_per_page'])) $_SESSION['entries_per_page'] = $_REQUEST['entries_per_page'];

if (!isset($_SESSION['entries_per_page'])) $_SESSION['entries_per_page'] = 20;


#$url = "&amp;entries_per_page=$_SESSION[entries_per_page]&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]";
$url = "?entries_per_page=$_SESSION[entries_per_page]";

$first_item = ($_REQUEST['page'] - 1) * $_SESSION['entries_per_page'];
$limit = "LIMIT $first_item, $_SESSION[entries_per_page]";

// lets generate the pagination menu as well
$recordCount = "SELECT count(*) AS num_comments FROM ".$TABLE_PREFIX."comments";
$totalRowsResult = mysql_query($recordCount);
$num_comments = mysql_result($totalRowsResult,"num_comments");

$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$pagination_menu = generate_pagination('plog-feedback.php'.$url,$page,$num_comments,$_SESSION['entries_per_page']);

$query = "SELECT *, UNIX_TIMESTAMP(`date`) AS `unix_date` from ".$TABLE_PREFIX."$level ORDER BY `id` DESC $limit";
$result = run_query($query);

$empty = 0;
if ($result) {
	if (mysql_num_rows($result) == 0) {
	 $output.= '<p class="actions">You have no user comments on your gallery.</p>';
	 $empty = 1;
	}
	$counter = 0;
	while($row = mysql_fetch_assoc($result)) {
		// if we're on our first iteration, dump the header
		if ($counter == 0) {
			$output .= '<table><tr><td>You have <b>'.$num_comments.'</b> user comment(s).</td>';
			
			// output view entries pagination control
			$output .= '<td align="right">'.generate_pagination_view_menu().'</td></tr></table>';
			
			$output .= '<table cellpadding="4"><tr class="header"><td></td><td width="65">thumb</td>';
		
			foreach ($row as $name => $value) {
				if (in_array($name, $allowedCommentKeys)) $output .= "<td>".$name."</td>";
			}
			
			$output .= '<td>Actions</td></tr>';
		}
		
		if ($counter%2 == 0) $table_row_color = "color-1";
		else $table_row_color = "color-2";
		
		// start a new table row (alternating colors)
		$output .= "<tr class=\"$table_row_color\">";
		
		// give the row a checkbox
		$output .= '<td width="15"><input type="CHECKBOX" name="Selected[]" VALUE="'.$row["id"].'"></td>';
		
		// give the row a thumbnail, we need to look up the parent picture for the comment
		$picture = get_picture_by_id($row["parent_id"]);

		$thumbpath = generate_thumb($picture["path"],$picture["id"],'small');

		// generate XHTML with thumbnail and link to picture view.
		$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.$picture["caption"].'" alt="'.$picture["caption"].'" />';
		$target = 'plog-thumbpopup.php?src='.$picture["id"];;
		$java = "javascript:this.ThumbPreviewPopup('$target')";
		
		$output .= '<td><a href="'.$java.'">'.$imgtag.'</a></td>';
		
		
		foreach($row as $key => $value) {
			$value = htmlspecialchars($value);
			$value = SmartStripSlashes($value);
			if ($key == "email") {
				$output .= "<td><a href=\"mailto:$value\">$value</a></td>";
			}
			else if ($key == "url") {
				$output .= "<td><a href=\"$value\">$value</a></td>";
			}
			else if ($key == "unix_date") {
				$output .= '<td>'.date($config["date_format"], $value).'</td>';
			}
			else if ($key == "allow_comments") {
				if ($value) $output .= "<td>Yes</td>";
				else $output .= "<td>No</td>";
			}
			//else if ($key == "ip") {
			//	$output .= "<td>" . @gethostbyaddr($value) . "</td>";
			//}

			else {
				if (in_array($key, $allowedCommentKeys))
						$output .= "<td>$value</td>";
			}
		}
		
		// $output .= our actions panel
		$query = "?action=edit-comment&amp;pid=$row[id]";

		
		$output .= '<td width="50"><a href="'.$_SERVER["PHP_SELF"]."$query&amp;entries_per_page=$_SESSION[entries_per_page]".
		'"><img src="../graphics/edit.gif" alt="Edit" title="Edit"></a><a href="'.$_SERVER["PHP_SELF"]."?action=Delete+Checked&amp;Selected[]=$row[id]".'" 
		onClick="return confirm(\'Are you sure you want to delete this item?\');"><img src="../graphics/x.gif" alt="Delete" 					title="Delete"></a></td>';

		
		$output .= "</tr>";
		$counter++;
	}
	
	$output .= '<tr class="header"><td colspan="9"></td></tr></table>';
}

if (!$empty)
	$output .= '
		<table><tr><td><a href="#" onclick="checkAll(document.getElementById(\'contentList\')); return false; ">Invert Checkbox Selection</a></td><td align="right">'.$pagination_menu.'</td></tr></table>'.
		'<input type="hidden" name="level" value="'.$level.'" />
		<input class="submit" type="submit" name="action" onClick="return confirm(\'Are you sure you want to delete selected items?\');" 
		value="Delete Checked"></form>';

display($output, "feedback");

?>
