<?php

// Code by Mike Johnson -- mike@solanosystems.com October 23rd, 2004.
// This is the main administrative interface code.  To change the look of the interface, change ../css/admin.css.

// The initial tab is UPLOAD function.

require("plog-globals.php");
require_once("../plog-load_config.php");
include("plog-admin-functions.php");

function generate_albums_menu($albums) {
	$output =  "<select name=\"albums_menu\">";
	foreach($albums as $album_id => $album) {

		if ($_REQUEST["albums_menu"] == $album_id || $_REQUEST["new_album_name"] == $album['album_name']) 
						$selected = " selected"; else $selected = "";
						
						$output .= "<option value=\"".$album_id."\"$selected>".$album['collection_name']." : ".$album['album_name']."" ;
            $output .= "</option>";
    }
	
	$output .=  "</select>";
	
	return $output;
}

function generate_collections_menu() {
	$collections = get_collections();
    $output = "<select name=\"collections_menu\">";
    foreach($collections as $collection) {
        $output .= "<option value=\"".$collection['id']."\">".$collection['name']."" ;
        $output .=  "</option>";
    }
	
	$output .= "</select>";
	
	return $output;
}

// Check if update has been clicked, handle erroneous conditions, or upload
if (isset($_REQUEST["upload"])){
	foreach($_REQUEST as $key => $val) $_REQUEST[$key] = stripslashes($val);
	
	$filename_parts = explode(".",strrev($_FILES["userfile"]["name"]),2);
	$filename_ext = strtolower(strrev($filename_parts[0]));
	
	$allowed_exts = array("jpg", "bmp", "gif", "png");

	
    if ($_FILES["userfile"]["name"] == ""){
        $output .= '<p class="errors">No file name specified!</p>';
    }
    else if (!in_array($filename_ext, $allowed_exts))
    {
    	$output .= '<p class="errors">Not a valid image file!</p>';
    }
    else if ($_FILES['userfile']['size'] == 0)
    {
    	$output .= '<p class="errors">File does not exist!</p>';
    }
    else if (!isset($_REQUEST["destination_radio"])){
        $output .= '<p class="errors">No destination album specified!</p>';    
    }
    else {
        if ($_REQUEST["destination_radio"] == "new" && $_REQUEST["new_album_name"] == ""){
            $output .= '<p class="errors">New album name not specified!</p>';
        }
	    else {
			
				if ($_REQUEST["destination_radio"] == "new"){
					// Create the new album
					$result = add_album(mysql_escape_string($_REQUEST["new_album_name"]), NULL, $_REQUEST["collections_menu"]);
					$album_id = $result["id"];
				}
				else
				{
					$album_id = $_REQUEST["albums_menu"];
				}
					
				$result = add_picture($album_id,$_FILES["userfile"]["tmp_name"],$_FILES["userfile"]["name"],$_REQUEST["caption"]);
				$output .= '<p class="actions">'.$result["output"].'</p>';
			
		}
    }
}

$output .= '
	<h1>Upload Photos</h1>
	<form id="uploadForm" action="'.$_SERVER["PHP_SELF"].'" method="post" enctype="multipart/form-data">
	  <div class="box-1">
      <h2><b>Step 1:</b> Choose a Photo to Upload</h2>
      &nbsp;<input name="userfile" type="file"/><br />
			Picture Caption (optional): <input name="caption" size="60"/><br />
			</div>';

$albums = get_albums();
$output .=  '
      <div class="box-2">
      <h2><b>Step 2: </b>Choose a Destination Album</h2>
      <table><tr valign="middle"><td width="20"><input onClick="var k=document.getElementsByName(\'albums_menu\');k[0].focus()"
      type="radio" name="destination_radio" value="existing" CHECKED>
      </td><td>Existing Album</td></tr></table>
	  '.generate_albums_menu($albums).'
			<h3>OR</h3>
      <table><tr valign="middle"><td width="20"><input onClick="var k=document.getElementsByName(\'new_album_name\');k[0].focus()" 
      type="radio" name="destination_radio" value="new"></td>
      <td>Create a New Album</td></tr></table>
      <table><tr valign="middle"><td>New Album Name:</td>
      <td><input type="text" name="new_album_name"></td><td>In collection:</td><td>
		'.generate_collections_menu().'
      </td></tr></table></div>
	  <div class="box-3">
	  <h2><b>Step 3:</b> Upload the Photo</h2>
      <input class="submit" class="submit" type="submit" name="upload" value="Upload" />
  </div>
</form>';

global $TABLE_PREFIX;

$output_error = '<h1>Upload Photos</h1><p class="actions">Before you can begin uploading photos to your gallery, you must
create at least <b>one collection</b> AND <b>one album</b> within that collection.  Move over to 
the <a href="plog-manage.php">"Manage"</a> tab to begin creating your organizational structure</p>';

$num_albums = count_albums();


if ($num_albums > 0)
	 display($output, "upload");
else
	 display($output_error, "upload");

?>
