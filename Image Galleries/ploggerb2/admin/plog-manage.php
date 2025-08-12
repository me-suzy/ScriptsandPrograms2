<?php

require("plog-globals.php");
require_once("../plog-load_config.php"); 					// load configuration variables from database
require_once("plog-admin-functions.php");
error_reporting(E_ERROR);
global $inHead;

$inHead = '<script type="text/javascript" src="js/plogger.js"></script>';

function generate_pagination_view_menu() {
	
	$java = 'document.location.href = \''.$_SERVER["PHP_SELF"].'?level='.$_REQUEST["level"].
	'&amp;id='.$_REQUEST["id"].'&amp;entries_per_page=\'+this.options[this.selectedIndex].value';
	
	$possible_values = array("5"=>5, "10"=>10, "20"=>20, "50"=>50);
	$output.= 'Entries per page <select onchange="'.$java.'" name="entries_per_page">';
	
	foreach ($possible_values as $key => $value)
		if ($_SESSION['entries_per_page'] == $key)
			$output .= "<option value=\"$value\" selected>$key</option>";
		else
			$output .= "<option value=\"$value\">$key</option>";
			
	$output.= '</select>';
	
	return $output;				

}

function generate_move_menu($level) {
	global $TABLE_PREFIX;
	if ($level != "collections" and $level != "comments"){
  	
		if ($level == "albums") $parent = "collections";
		if ($level == "pictures") $parent = "albums";
		$output .=  '<input class="submit" type="submit" name="action" value="Move Checked To"/>';
  	
		if ($level == "pictures") {
			$albums = get_albums();
			$output .= generate_albums_menu($albums);
		} else {
			$output .=  '<select name="group_id">';
			$collections = get_collections();
			foreach($collections as $collection) {
				$output .= '<option value="'.$collection["id"].'">'.$collection["name"];
				$output .=  '</option>';
			}
			$output .=  '</select>';
		}
			
		return $output;
	}
}

function generate_albums_menu($albums) {
	$output .=  '<select name="group_id">';
	foreach($albums as $album_id => $album) {
		
            if ($_REQUEST["albums_menu"] == $album_id || $_REQUEST["new_album_name"] == $album['album_name']) 
						$selected = " selected"; else $selected = "";
						
						$output .= "<option value=\"".$album_id."\"$selected>".$album['collection_name']." : ".$album['album_name']."" ;
            $output .= "</option>";
        }
	
	$output .=  "</select>";
	
	return $output;
}

function generate_breadcrumb_admin($level, $id){
	global $TABLE_PREFIX;
	switch ($level){
		case 'collections':
		  $breadcrumbs = '<b>Collections</b>';
			
			break;
		case 'albums':
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$collection_name = $row["name"];
			
			$breadcrumbs = '<a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . "<b>$collection_name</b>";
			
			break;
		case 'pictures':
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."albums` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$album_link = $row["name"];
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$row["parent_id"]."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$collection_link = '<a href="'.$_SERVER["PHP_SELF"].'?level=albums&amp;id='.$row["id"].'">'.$row["name"].'</a>';
			
			$breadcrumbs = '<a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . $collection_link . ' &raquo; ' . '<b>'.						$album_link.'</b>';
			
			break;
		case 'comments':
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$picture_link = '<b>'.$row["path"].'</b>';
			$album_id = $row["parent_album"];
			$collection_id = $row["parent_collection"];
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."albums` WHERE `id`='".$album_id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$album_link = '<a href="'.$_SERVER["PHP_SELF"].'?level=pictures&amp;id='.$album_id.'">'.$row["name"].'</a>';
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$collection_id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$collection_link = '<a href="'.$_SERVER["PHP_SELF"].'?level=albums&amp;id='.$collection_id.'">'.$row									["name"].'</a>';
			
			$breadcrumbs = '<a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . $collection_link . ' &raquo; '
			.$album_link. ' &raquo; '.$picture_link . ' &raquo;' . " Comments";
			
			break;
		default:
			$breadcrumbs = '<b>Collections</b>';
	}
	
	return '<div id="breadcrumb_links">'.$breadcrumbs.'</div>';
}


if (!isset($_REQUEST["level"]) or $_REQUEST["level"] == '') $level = "collections";	 
else $level = $_REQUEST['level'];

$output = '<h1>Manage Content</h1>';

global $config;


// here we will determine if we need to perform a move or delete action.
if (isset($_REQUEST["action"])) {
	$num_items = 0;

	$action_result = array();

	if ($_REQUEST["action"]== "Delete Checked") {
		// perform the delete function on the selected items
		
		if (isset($_REQUEST["Selected"])) {
			foreach($_REQUEST["Selected"] as $del_id) {
				// lets build the query string
				if ($level == "pictures") {
					$rv = delete_picture($del_id);
				}
				if ($level == "collections") {
					$rv = delete_collection($del_id);
				}
				if ($level == "albums") {
					$rv = delete_album($del_id);
				}

				if (isset($rv['errors'])) {
					$output .= '<p class="errors">' . $rv['errors'] . '</p>';
				} else {
					$num_items++;
				};
			}
			
			$output .= "<p class=\"actions\">You have deleted $num_items entry(s) successfully.</p>";
		}
		else{
			$output .= "<p class=\"errors\">Nothing selected to delete!</p>";
		}
	}
	else if ($_REQUEST["action"] == "Move Checked To") {
		if ($level == "albums") $parent = "parent_id";
		if ($level == "pictures") $parent = "parent_album";
		
		// perform the move function on the selected items
		$pid = $_REQUEST["group_id"];
		
		if (isset($_REQUEST["Selected"])) {
			foreach($_REQUEST["Selected"] as $mov_id) {
				
				// if we are using pictures we need to update the parent_collection as well
				if ($level == "pictures") {
					 // lets build the query string
					 $mov_id = intval($mov_id);
					$query = "UPDATE ".$TABLE_PREFIX."$level SET `$parent` = '$pid' WHERE `id`='$mov_id'";
					$result = run_query($query);
					$num_items++;
				
					 // we need the parent_id from the album we're changing to
					 $query = "SELECT * FROM ".$TABLE_PREFIX."albums WHERE `id` = '$pid'";
					 $result = run_query($query);
					 $row = mysql_fetch_assoc($result);
					 $new_collection = $row['parent_id'];
					 
					 // set the new parent id
					 $query = "UPDATE ".$TABLE_PREFIX."$level SET `parent_collection` = '$new_collection' WHERE `id`='$mov_id'";
					 $result = run_query($query);
					 
					 // move picture to new location
					 // we need to query to get collection names and album names to find new directory path
					 
					 $sql = "SELECT p.path as path, c.path as collection_path, a.path as album_path
							FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."pictures p, ".$TABLE_PREFIX."collections c 
							WHERE p.parent_album = a.id AND p.parent_collection = c.id AND p.id = '$mov_id'";
					
					 $result = run_query($sql);
					 $row = mysql_fetch_assoc($result);
					 
					 $filename = basename($row['path']);
					 $directory = $row['collection_path']."/".$row['album_path']."/";
					 $new_path = $directory.$filename;
					 
					 if (!rename($config['basedir']."images/".$row['path'], $config['basedir']."images/".$new_path))
						$output .= "<p class=\"errors\">Error moving file! ($row[path] to $new_path)</p>";
						
					$new_path = mysql_real_escape_string($new_path);
					// update database
					$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = '$new_path' WHERE id = '$mov_id'";
					mysql_query($sql) or ($output .= "<p class=\"errors\">".mysql_error()."</p>");
				}
				else if ($level == "albums") {
					// if we are moving entire albums then we need to rename the folder
					// $pid is our target collection id, $mov_id is our source album
					
					// rename the directory
					// first, get the album name and collection name of our source album
					$sql = "SELECT c.path as collection_path, a.path as album_path
							FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."collections c 
							WHERE c.id = a.parent_id AND a.id = '$mov_id'";
					
					$result = run_query($sql);
					$row = mysql_fetch_assoc($result);
					
					$source_album_name = SmartStripSlashes($row["album_path"]);
					$source_collection_name = SmartStripSlashes($row["collection_path"]);
					
					// next, get the collection name of our destination collection
					$sql = "SELECT c.path as collection_path
							FROM ".$TABLE_PREFIX."collections c 
							WHERE c.id = '$pid'";
					
					$result = run_query($sql);
					$row = mysql_fetch_assoc($result);
					
					$target_collection_name = SmartStripSlashes($row["collection_path"]);
					
					$source_path = $config['basedir']."images/".$source_collection_name."/".$source_album_name;
					$target_path = $config['basedir']."images/".$target_collection_name."/".$source_album_name;
					
					// attempt to make new album directory in target collection
					@mkdir($target_path, 0775);
					
					//if (!rename($source_path, $target_path))
					//	$output .= '<p class="errors">Could not rename directory!</p>';
					
					// now we need to update the database paths of all pictures within source album
					$sql = "SELECT p.path as path, c.name as collection_name, a.name as album_name
							FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."pictures p, ".$TABLE_PREFIX."collections c 
							WHERE p.parent_album = a.id AND p.parent_collection = c.id AND p.parent_album = '$mov_id'";
					
					$result = run_query($sql);
					
					while($row = mysql_fetch_assoc($result)) {
					 
						$filename = basename($row['path']);
						
						$old_path = $source_path."/".$filename;
						$new_path = $target_path."/".$filename;
						 
						if (!rename($old_path, $new_path))
							$output .= "<p class=\"errors\">Error moving file! ($old_path to $new_path)</p>";
						
						// $output .= "<p class=\"actions\">Updating database: $row[path] -> $new_path</p>";	
						// update database
						$row['path'] = mysql_real_escape_string($row['path']);
						
						$sql = "UPDATE ".$TABLE_PREFIX."pictures SET parent_collection = '$pid' WHERE path = '$row[path]'";
						mysql_query($sql) or ($output .= "<p class=\"errors\">".mysql_error()."</p>");
						
						$path_insert = mysql_real_escape_string($target_collection_name."/".$source_album_name."/".$filename);
						$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = '$path_insert' WHERE path = '$row[path]'";
						mysql_query($sql) or ($output .= "<p class=\"errors\">".mysql_error()."</p>");
						
						
					}
					
					// update the parent id of the moved album
					$query = "UPDATE ".$TABLE_PREFIX."albums SET `parent_id` = '$pid' WHERE `id`='$mov_id'";
					$result = run_query($query);
					$num_items++;

					
				
				}
					  
			}
			
			$output .= "<p class=\"actions\">You have moved $num_items entry(s) successfully.</p>";
		}
		else{
			$output .= "<p class=\"errors\">Nothing selected to move!</p>";
		}
	}
	else if ($_REQUEST["action"] == "edit-picture") {
		// show the edit form
		$sql = "SELECT * FROM ".$TABLE_PREFIX."pictures p WHERE p.id = '" . $_REQUEST["pid"] . "'";
		$result = run_query($sql);
		$photo = mysql_fetch_assoc($result);
		if ($photo['allow_comments'] == 1) $state = "checked"; else $state = "";
		
		$output .= '<form class="edit" action="'.$_SERVER["PHP_SELF"].'" method="post">';
		
		
		$output .= 'Caption:<br/><input size="80" name="caption" id="caption" value="'.$photo['caption'].'">
				    Allow Comments? <input type="checkbox" id="allow_comments" name="allow_comments" value="1"'." $state>";
					
		$output .= '<input type="hidden" name="level" value="'.$_REQUEST['level'].'"><input type="hidden" 
					name="id" value="'.$photo['parent_album'].'"><input type="hidden" 
					name="pid" value="'.$photo['id'].'"><input type="hidden" 
					name="action" value="update-picture"><button class="submit" type="submit">Update</button>';
		
		$output .= '</form>';
		
	}
	else if ($_REQUEST["action"] == "edit-album") {
		// show the edit form
		
		$output .= '<form class="edit" action="'.$_SERVER["PHP_SELF"].'" method="post">';
		
		$sql = "SELECT * FROM ".$TABLE_PREFIX."albums a WHERE a.id = '" . $_REQUEST["pid"] . "'";
		$result = run_query($sql);
		$album = mysql_fetch_assoc($result);
					
		$sql = "SELECT id,caption,path FROM ".$TABLE_PREFIX."pictures p
			WHERE p.parent_album = '" . $_REQUEST["pid"] . "'";
				
		$images = "<option value='0'>automatic</option>";
		$result = run_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$selected = ($row["id"] == $album["thumbnail_id"]) ? " selected" : "";
			$images .= "<option value='" . $row["id"] . "'" . $selected . ">";
			$images .= !empty($row["caption"]) ? $row["caption"] : basename($row["path"]);
			$images .= "</option>\n";
		};
		

		$output .= 'Name:<br/><input size="30" name="name" id="name" value="'.$album['name'].'"><br/>
				    Description:<br/><input size="80" name="description" id="description" value="'.$album['description'].'"><br/>
				    Thumbnail:<br/><select name="thumbnail_id" id="thumbnail_id">' . $images . '</select>';
					
		$output .= '<input type="hidden" name="level" value="'.$_REQUEST['level'].'"><input type="hidden" 
					name="pid" value="'.$_REQUEST['pid'].'"><input type="hidden" 
					name="id" value="'.$_REQUEST['id'].'"><input type="hidden" 
					name="action" value="update-album"><tr><td><button class="submit" type="submit">Update</button>';
		
		$output .= '</form>';
		
	}
	else if ($_REQUEST["action"] == "edit-collection") {
		// show the edit form
		$output .= '<form class="edit" action="'.$_SERVER["PHP_SELF"].'" method="post">';
		
		$sql = "SELECT * FROM ".$TABLE_PREFIX."collections c WHERE c.id = '" . $_REQUEST["pid"] . "'";
		$result = run_query($sql);
		$collection = mysql_fetch_assoc($result);
		
		$sql = "SELECT p.id AS id,caption,p.path AS path,a.name AS album_name FROM ".$TABLE_PREFIX."pictures p
			LEFT JOIN " . $TABLE_PREFIX . "albums AS a ON p.parent_album = a.id
			WHERE p.parent_collection = '" . $_REQUEST["pid"] . "' ORDER BY a.name,p.date_submitted";
				
		$images = "<option value='0'>automatic</option>";
		$result = run_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$selected = ($row["id"] == $collection["thumbnail_id"]) ? " selected" : "";
			$images .= "<option value='" . $row["id"] . "'" . $selected . ">";
			$images .= $row["album_name"] . " : ";
			$images .= !empty($row["caption"]) ? $row["caption"] : basename($row["path"]);
			$images .= "</option>\n";
		};


		$output .= 'Name:<br/><input size="30" name="name" id="name" value="'.$collection['name'].'"><br/>
				    Description:<br/><input size="80" name="description" id="description" value="'.$collection['description'].'"><br/>
				    Thumbnail:<br/><select name="thumbnail_id" id="thumbnail_id">' . $images . "</select>";
					
		$output .= '<input type="hidden" name="level" value="'.$_REQUEST['level'].'"><input type="hidden" 
					name="pid" value="'.$_REQUEST['pid'].'"><input type="hidden" 
					name="id" value="'.$_REQUEST['id'].'"><input type="hidden" 
					name="action" value="update-collection"><button class="submit" type="submit">Update</button>';
		
		$output .= '</form>';
		
	}
	else if ($_REQUEST["action"] == "edit-comment") {
		// show the edit form
		$comment_id = intval($_GET["pid"]);
		$sql = "SELECT * FROM ".$TABLE_PREFIX."comments c WHERE c.id = '$comment_id'";
		$result = run_query($sql);
		$comment = mysql_fetch_assoc($result);
		$output .= '<form class="edit" action="'.$_SERVER["PHP_SELF"].'" method="post"><table>';

		$output .= '<tr><td>Author:<br/><input size="30" name="author" id="author" value="'.SmartStripSlashes($comment['author']).'"></td>
				    <td>Email:<br/><input size="30" name="email" id="email" value="'.SmartStripSlashes($comment['email']).'"></td>
					<td>Website:<br/><input size="30" name="url" id="url" value="'.SmartStripSlashes($comment['url']).'"></td></tr>
					<tr><td colspan="3">Comment:<br/> <textarea cols="70" rows="4" name="comment" id="comment">'.
					SmartStripSlashes($comment['comment']).'</textarea></td></tr></table>';
					
		$output .= '<input type="hidden" name="level" value="'.$_REQUEST['level'].'"><input type="hidden" 
					name="pid" value="'.$comment['id'].'"><input type="hidden" 
					name="id" value="'.$_REQUEST['id'].'"><input type="hidden" 
					name="action" value="update-comment"><button class="submit" type="submit">Update</button>';
		
		$output .= '</form>';
		
	}
	else if ($_POST['action'] == 'update-picture') {
		$action_result = update_picture($_POST['pid'],$_POST['caption'],$_POST['allow_comments']);
	}
	else if ($_POST['action'] == 'update-album') {
		$action_result = update_album($_POST['pid'],$_POST['name'],$_POST['description'],$_POST['thumbnail_id']);
	}
	else if ($_POST["action"] == "update-collection") {
		$action_result = update_collection($_POST["pid"],$_POST["name"],$_POST["description"],$_POST["thumbnail_id"]);
	}
	else if ($_POST["action"] == "update-comment") {
		$action_result = update_comment($_POST["pid"],$_POST["author"],$_POST["email"],$_POST["url"],$_POST["comment"]);
	}
	else if ($_POST["action"] == "add-collection") {
		$action_result = add_collection($_POST["name"],$_POST["description"]);
	}
	else if ($_POST["action"] == "add-album") {
		$action_result = add_album($_POST["name"],$_POST["description"],$_POST["id"]);
	}

	if (!empty($action_result['errors'])) {
		$output .= '<p class="errors">' . $action_result['errors'] . '</p>';
	} elseif (!empty($action_result['output'])) {
		$output .= '<p class="actions">' . $action_result['output'] . '</p>';
	};
}

$output .= '<form id="contentList" action="'.$_SERVER["PHP_SELF"].'" method="post">';

// here we will generate a "add collection/album" header
if ($level == "collections") {
	 $output .= '<div class="box-3"><h2 class="add">Create a Collection: </h2><label for="name">Name </label><br/><input name="name" id="name">
	 <br/><label for="description">Description </label><br/><input name="description" id="description" size="50">
	 <input name="action" type="hidden" value="add-collection">
	 <input class="submit" type="submit" value="Add Collection">
	 </div>';
	 }
else if ($level == "albums") {
	$output .= '<div class="box-3"><h2 class="add">Create an Album: </h2><label for="name">Name </label><br/><input name="name" id="name">
	 <br/><label for="description">Description </label><br/><input name="description" id="description" size="50">
	 <input name="action" type="hidden" value="add-album">
	 <input class="submit" type="submit" value="Add Album"></div>';
	 }	 


// this is our defined list of allowed fields for each table
$allowedPictureKeys = array("path", "caption", "allow_comments");
$allowedAlbumKeys = array("name", "description");
$allowedCollectionKeys = array("name", "description");
$allowedCommentKeys = array("date", "author", "email", "url", "comment");


// lets iterate through all the content and build a table
// set the default level if nothing is specified

// handle pagination
// lets determine the limit filter based on current page and number of results per page
if (!isset($_REQUEST["page"])) $_REQUEST["page"] = "1"; // we're on the first page

if (isset($_REQUEST['entries_per_page'])) 
	$_SESSION['entries_per_page'] = $_REQUEST['entries_per_page'];
else
	$_SESSION['entries_per_page'] = 20;


// determine the filtering conditional based on the level and id number
if ($level == "albums" or $level == "comments"){
	$cond = "WHERE `parent_id` = '$_REQUEST[id]'";
}
else if ($level == "pictures"){
	$cond = "WHERE `parent_album` = '$_REQUEST[id]'";
}

$url = "?entries_per_page=$_SESSION[entries_per_page]&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]";

$first_item = ($_REQUEST['page'] - 1) * $_SESSION['entries_per_page'];
$limit = "LIMIT $first_item, $_SESSION[entries_per_page]";

// lets generate the pagination menu as well
$recordCount = "SELECT COUNT(*) AS num_items FROM ".$TABLE_PREFIX."$level $cond";
$totalRowsResult = mysql_query($recordCount);
$totalRows = mysql_result($totalRowsResult,'num_items');

$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$pagination_menu = generate_pagination('plog-manage.php'.$url,$page,$totalRows,$_SESSION['entries_per_page']);

$query = "SELECT * FROM ".$TABLE_PREFIX."$level $cond $limit";
$result = run_query($query);

if ($result) {
	if (mysql_num_rows($result) == 0) {
   $output .= generate_breadcrumb_admin($_REQUEST["level"], $_REQUEST["id"]);
	 $output.= '<p class="actions">This table is empty.</p>';
	}
	while($row = mysql_fetch_assoc($result)) {
		// if we're on our first iteration, dump the header
		if ($counter == 0) {
			$output .= '<table><tr><td>'
			.generate_breadcrumb_admin($_REQUEST["level"], $_REQUEST["id"]).'</td>';
			
			// output view entries pagination control
			$output .= '<td align="right">'.generate_pagination_view_menu().'</td></tr></table>';
			
			if ($level == "pictures"){
				$output .= '<table cellpadding="4"><tr class="header"><td></td><td width="65">Thumb</td>';
			}
			else{
				$output .= '<table cellpadding="4"><tr class="header"><td></td>';
			}
			
			foreach ($row as $name => $value) {
				// check to see if this is allowed
				$value = SmartStripSlashes($value);
				if (($level == "albums" && in_array($name, $allowedAlbumKeys)) ||
					  ($level == "pictures" && in_array($name, $allowedPictureKeys)) ||
						($level == "collections" && in_array($name, $allowedCollectionKeys)) ||
							($level == "comments" && in_array($name, $allowedCommentKeys))) {
										if ($level == "pictures" && $name == 'path') $name = 'filename'; 
										$output .= "<td>".ucfirst($name)."</td>";
							}
			}
			
			$output .= '<td>Actions</td></tr>';
		}
		
		if ($counter%2 == 0) $table_row_color = "color-1";
		else $table_row_color = "color-2";
		
		// start a new table row (alternating colors)
		$output .= "<tr class=\"$table_row_color\">";
		
		// give the row a checkbox
		$output .= '<td width="15"><input type="CHECKBOX" name="Selected[]" VALUE="'.$row["id"].'"></td>';
		
		//give the row a thumbnail if we're in pictures view
		if ($level == "pictures") {
		
			$thumbpath = generate_thumb($row["path"],$row["id"],'small');
			
			// generate XHTML with thumbnail and link to picture view.
			$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.$row["caption"].'" alt="'.$row["caption"].'" />';
			$target = 'plog-thumbpopup.php?src='.$row["id"];
			$java = "javascript:this.ThumbPreviewPopup('$target')";
			
			$output .= '<td><a href="'.$java.'">'.$imgtag.'</a></td>';
		}
		
		foreach($row as $key => $value) {
			$value = htmlspecialchars($value);
			if ($key == "name" || ($key == "path" && $level == "pictures")) {  // $output .= a link to the next level
				if ($level == "collections") {
					$num = count_albums($row['id']);
					$output .= "<td><a class=\"folder\" href=\"$_SERVER[PHP_SELF]?level=albums&amp;id=$row[id]\">
					<b>$value </b></a> &#8212; contains $num album(s)</td>";
				}
				else if ($level == "albums") {
					$num = count_pictures($row['id']);
					$output .= "<td><a class=\"folder\" href=\"$_SERVER[PHP_SELF]?level=pictures&amp;id=$row[id]\">
					<b>$value</b></a> &#8212; contains $num picture(s)</td>";
					
				}
				else if ($level == "pictures") {
					$output .= "<td><a class=\"folder\" href=\"$_SERVER[PHP_SELF]?level=comments&amp;id=$row[id]\">
					<b>".basename($value)."</b></a></td>";
					
				}
				else
					$output .= "<td>$value</td>";
			}
			else if ($key == "email") {
				$output .= "<td><a href=\"mailto:$value\">$value</a></td>";
			}
			else if ($key == "allow_comments") {
				if ($value) $output .= "<td>Yes</td>";
				else $output .= "<td>No</td>";
			}
			else {
				if (($level == "albums" && in_array($key, $allowedAlbumKeys)) ||
					  ($level == "pictures" && in_array($key, $allowedPictureKeys)) ||
						($level == "collections" && in_array($key, $allowedCollectionKeys)) ||
							($level == "comments" && in_array($key, $allowedCommentKeys)))
										$output .= "<td>".SmartStripSlashes($value)."</td>";
			}
		}
		
		// $output .= our actions panel
		if ($level == "pictures") $query = "?action=edit-picture&amp;pid=$row[id]&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]";
		else if ($level == "collections") $query = "?action=edit-collection&amp;pid=$row[id]&amp;
			level=$_REQUEST[level]&amp;id=$_REQUEST[id]";
		else if ($level == "albums") $query = "?action=edit-album&amp;pid=$row[id]&amp;
			&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]";
		else if ($level == "comments") $query = "?action=edit-comment&amp;pid=$row[id]&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]";

		
		$output .= '<td width="50"><a href="'.$_SERVER["PHP_SELF"]."$query&amp;entries_per_page=$_SESSION[entries_per_page]".
		'"><img style="display:inline" src="../graphics/edit.gif" alt="Edit" title="Edit"></a><a href="'.$_SERVER["PHP_SELF"]."?action=Delete+Checked&amp;Selected[]=$row[id]&amp;level=$_REQUEST[level]&amp;id=$_REQUEST[id]".'" 
		onClick="return confirm(\'Are you sure you want to delete this item?\');"><img style="display:inline" src="../graphics/x.gif" alt="Delete" 					title="Delete"></a></td>';

		
		$output .= "</tr>";
		$counter++;
	}
	
	$output .= '<tr class="header"><td colspan="7"></td></tr></table>';
}

$output .= '
	<table><tr><td><a href="#" onclick="checkAll(document.getElementById(\'contentList\')); return false; ">Invert Checkbox Selection</a></td><td align="right">'.$pagination_menu.'</td></tr></table>'.
	'<input type="hidden" name="level" value="'.$level.'" />
	<input type="hidden" name="id" value="'.$_REQUEST["id"].'" />
	<input class="submit" type="submit" name="action" onClick="return confirm(\'Are you sure you want to delete selected items?\');" 
	value="Delete Checked">
	'.generate_move_menu($level).'</form>';

display($output, "manage");
?>
