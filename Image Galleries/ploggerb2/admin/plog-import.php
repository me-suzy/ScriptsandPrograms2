<?php

// Code by Mike Johnson -- mike@solanosystems.com October 23rd, 2004.
// This is the main administrative interface code.  To change the look of the interface, change ../css/admin.css.


// The initial tab is UPLOAD function.

require("plog-globals.php");
require_once("../plog-load_config.php");
require_once("../plog-functions.php");
include("plog-admin-functions.php");

global $inHead;
global $TABLE_PREFIX;

$inHead = '<script type="text/javascript" src="js/plogger.js"></script>';

function generate_albums_menu($albums,$type = "multiple", $preselect) {
	$output = '';
	
	if ($type == "multiple")
		 $output .=  "<select name=\"destinations[]\">";
	else
		$output .=  "<select name=\"destination\">";
		
	foreach($albums as $album_id => $album_data) {
	    if ($preselect == $album_id) 
			$selected = " selected"; else $selected = "";
						
		$output .= "<option value=\"".$album_id."\"$selected>".$album_data['collection_name']." : ".$album_data['album_name']."" ;
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

function get_files($directory) {
   // Try to open the directory
   if($dir = opendir($directory)) {
       // Create an array for all files found
       $tmp = Array();

       // Add the files
       while($file = readdir($dir)) {
           // Make sure the file exists
           if($file != "." && $file != ".." && $file[0] != '.') {
               // If it's a directiry, list all files within it
               if(is_dir($directory . "/" . $file)) {
                   $tmp2 = get_files($directory . "/" . $file);
                   if(is_array($tmp2)) {
                       $tmp = array_merge($tmp, $tmp2);
                   }
               } else {
                   $filename = basename(stripslashes($file));
			
				   $filename_parts = explode(".",$filename,2);
				   $filename_ext = strtolower($filename_parts[1]);
					
				   if($filename_ext == "jpg" || $filename_ext == "gif" || $filename_ext == "bmp" || $filename_ext == "png")
                   		array_push($tmp, $directory . "/" . $file);
               }
           }
       }

       // Finish off the function
       closedir($dir);
       return $tmp;
   }
}

$output = '';


// Check if update has been clicked, handle erroneous conditions, or upload
if (isset($_POST["upload"])){

	$destinations = $_POST["destinations"];
	$captions = $_POST["captions"];
	$files = $_POST["files"];
	$selected = $_POST["Selected"];

	
	$counter = $imported = 0;

	global $config;
  	
	$files = get_files($config['basedir'] . 'uploads');

	if ($_POST["destination_radio"] == "new" && $_POST["new_album_name"] == ""){
            $output .= '<p class="errors">New album name not specified!</p>';
        }
	else {
		
		if ($_POST["destination_radio"] == "new"){
			// Create the new album
			$result = add_album($_POST["new_album_name"], NULL, $_POST["collections_menu"]);
			$album_id = $result["id"];
		}
		else
		{
			$album_id = $_POST["destination"];
		}

		if ($album_id) {
			foreach($files as $file) {
				$file_key = md5($file);
				if (in_array($file_key,$selected)) {
				
					$file_name = SmartStripSlashes($file);
					// fully qualified file name
					//$fqfn = $config["basedir"] . "uploads/".$file_name;
					$fqfn = $file;

					// attempt to chmod the pictures directory before moving them
					@chmod(dirname($fqfn), 0777);
					
					if (is_file($fqfn)) {
						$result = add_picture($album_id,$fqfn,basename($file_name),$captions[$file_key]);
						if ($result["picture_id"] != false) {
							$imported++;
							// delete thumbnail file if it exists
							$thumbpath = $config['basedir'] . 'thumbs/import-' . basename($file_name);
							if (is_file($thumbpath) && is_readable($thumbpath))
							{
								unlink($thumbpath);
							};
						};
					}
						
					$counter++;
				};
			
			}
			
			// get album name for display
			$sql = "SELECT name FROM ".$TABLE_PREFIX."albums WHERE id = $album_id";
			$result = run_query($sql);
			
			$row = mysql_fetch_assoc($result);
			 
			$output .= '<p class="actions">'.$imported.' picture(s) were successfully imported to album <b>'.$row['name'].'</b></p>';
		
			if ($imported == 0)
				$output .= '<p class="errors">Make sure to CHMOD 777 your newly created folders within the uploads directory or else Plogger cannot access them.  Plogger cannot CHMOD the directory for you while PHP is in safe mode.</p>';
		}
		else
			$output .= '<p class="errors">'.$result['output'].'</p>';

	}

	// read the list again, so any newly created directories show up
	$files = get_files($config['basedir'] . 'uploads');
  	
  	// build a list of unique directories from the filenames
  	$directories = array();
  	
  	foreach ($files as $file) {
  		 
  		 $dirname = dirname($file);
  		 
  		 if (!in_array($dirname, $directories))
  		 	$directories[md5($dirname)] = $dirname;
  	}  		 
  		
  	// here we will check which group of pictures we are editing, grouped by directory
  	if (count($directories) > 0) {
  		$output .= '<div class="actions">Would you like to import anything else?';
  		
  		$output .= '<ul>';			
  		
  		foreach ($directories as $dirkey => $group) {
  				 $output .= '<li><a class="folder" href="'.$_SERVER['PHP_SELF']."?directory=$dirkey".'">'.basename($group).'</a></li>';
  		}
		
  		$dirkey = md5($upload_directory);
		  $output .= '<li><a class="folder" href="'.$_SERVER['PHP_SELF']."?directory=$dirkey".'">All Pictures</a></li>';
  
  		$output .= '</ul></div>';
  		
  	}
	
}
  else {
  $output .= '
  	<h1>Import Photos</h1>';
  	

  	$upload_directory = $config['basedir'] . 'uploads';
  	if (!is_writable($upload_directory))
  		$output .= '<p class="errors">Your "Uploads" directory is NOT WRITABLE!  Use your FTP client to CHMOD the directory with the
  		proper permissions or your import may fail!</p>';
  
  	$files = get_files($upload_directory);
  	
  	// build a list of unique directories from the filenames
  	$directories = array();

  	foreach ($files as $file) {
  		 
  		 $dirname = dirname($file);

		 $dirkey = md5($dirname);
  		 
		// using md5 hashes for directory names allows for easier validation of given directory name
		// and also allows us to work with international characters in directory names
  		 if (!in_array($dirname, $directories))
  		 		$directories[md5($dirname)] = $dirname;
  	}

  	if (count($files) == 0) {
  		 $output .= '<div class="actions">No images found in the <b>/uploads/</b> directory.  
  		 To mass import pictures into your gallery, simply:<ul>
  		 <li><b>Open an FTP connection</b> to your website</li>
			 <li>Transfer photos you wish to publish to the <b>/uploads/</b> directory</li>  		 
			 <li>Optionally, you can create folders within that directory to import in groups</li></ul></div>';
  	}

  	// here we will check which group of pictures we are editing, grouped by directory
  	if (!isset($_GET["directory"]) && count($directories) > 0) {
  		$output .= '<div class="actions">Choose a directory you wish to import from:';
  		
  		$output .= '<ul>';			
  		
  		foreach ($directories as $dirkey => $group) {
  			$output .= '<li><a class="folder" href="'.$_SERVER['PHP_SELF']."?directory=$dirkey".'">'.basename($group).'</a></li>';
  		}
  
  		$dirkey = md5($upload_directory);
  		// $output .= '<li><a class="folder" href="'.$_SERVER['PHP_SELF'].'?directory='.$dirkey.'">All pictures</a></li>';
  		$output .= '</ul></div>';
  		
  	}
  	else {
		// real_directory is the full path
		// show_directory is what the user sees, it's relative so the directory structure of the server
		// is not exposed
		$show_directory = "uploads";
  		if (isset($_GET["directory"]) && isset($directories[$_GET["directory"]])) {
			$real_directory = $directories[$_GET["directory"]];
			$show_directory .= substr($real_directory,strlen($upload_directory));
		}
  		else {
			$real_directory = $upload_directory;
  		}
  		
		$files = get_files($real_directory);
  		
  		if (count($files) > 0) 
			$output .= '<p class="actions">You are currently looking at <b>'.count($files).'</b> image(s) within the <b>
			  				   	   '.$show_directory.'</b> directory';

  	
  		// check to make sure album is writable and readable, and issue warning
  		if (!is_writable($real_directory) || !is_readable($real_directory))
  			$output .= '<p class="actions">Warning: this directory does not have the proper permissions settings!  You must
  						CHMOD 777 on this directory using your FTP software or import may fail.';	
  		
  		
		$albums = get_albums();
	    for($i=0; $i<count($files); $i++)  
	    {  
	    		$file_key = md5($files[$i]);
			$relative_name = substr($files[$i],strlen($upload_directory)+1);
	  		if ($i == 0)
	  		$output.= '<form id="uploadForm" action="'.$_SERVER["PHP_SELF"].'" method="post" enctype="multipart/form-data">
	  							<table><tr class="header"><td></td><td>Thumbnail</td><td>Filename</td><td>Caption</td></tr>';
	  						
	  		// For each file within upload directory, list checkmark, thumbnail, caption box, album box
	  		if ($counter%2 == 0) $table_row_color = "color-1";
	  		else $table_row_color = "color-2";
	  		
	  		// start a new table row (alternating colors)
	  		$output .= "<tr class=\"$table_row_color\">";
	
			$thumbpath = generate_thumb($config['basedir'].'uploads/'.$relative_name,"import",'small');
	
	  			// generate XHTML with thumbnail and link to picture view.
	  			$imgtag = '<td><img class="photos" src="'.$thumbpath.'" /></td>';
	  			$output .= '<td width="15"><input type="CHECKBOX" name="Selected[]" VALUE="'.$file_key.'" checked></td>';
	  			$output .= $imgtag;
	  			//$output .= '<input name="files[]" type="hidden" value="'.$file_key.'">'
				$output .= '</td>';
	  			$output .= '<td>'.basename($files[$i]).'</td>';
	  			$output .= '<td><input size="100%" name="captions[' . $file_key . ']"></input></td>';
	  			$counter++;  
	    }  
	  
		if (count($files) != 0) {
		  	$output .= '</table><a href="#" onclick="checkAll(document.getElementById(\'uploadForm\')); return false; ">Invert Checkbox Selection</a>'; 
		    
		    // here we can preselect some default options based on the structure of the import directory
		    // if pictures are within one directory, simply place the name of the album within the
		    // create new album selector and allow user to pick collection.
		    // if two levels deep, preselect appropriate existing album and collection
		    // or place album name in new box
		    
		    // break up directory name into parts
		    $directory_parts = explode("/", $show_directory);
		    
		    // check if album exists
		    $collection_name = $directory_parts[2];
		    $album_name = $directory_parts[3];
	
		    
		    if (is_null($album_name)) // file is only one level deep, assume folder name is album name
		    	$sql = "SELECT id FROM ".$TABLE_PREFIX."albums WHERE name = '".$collection_name."'";
			else 
		    	$sql = "SELECT id FROM ".$TABLE_PREFIX."albums WHERE name = '".$album_name."'";
		    
		    
		    $result = run_query($sql);
			$row = mysql_fetch_assoc($result);
			
			if(!isset($row['id'])) { // album doesn't exist, place in new album box
				$existing = "";
				$new_album = "CHECKED";
				if (is_null($album_name))
					$new_album_name = $collection_name;
				else
					$new_album_name = $album_name;
			}
			else {
				$existing = "CHECKED";
				$new_album = "";
			}
		    
		    $output .=  '
		      <h1>Destination:</h1>
		      <table>
		      <tr><td>
		      <table><tr valign="middle"><td width="20"><input type="radio" name="destination_radio" 
		      value="existing" '.$existing.'></td><td>Existing Album</td></tr></table>
			  '.generate_albums_menu($albums,"single", $row['id']).'
			  <td><h3>OR</h3></td>
		      <td><table><tr valign="middle"><td width="20"><input onClick="var k=document.getElementsByName(\'new_album_name\');k[0].focus()" 
		      type="radio" name="destination_radio" 
		      value="new" '.$new_album.'></td><td>Create a New Album</td></table><table>
		      <tr valign="middle"><td width="120">New Album Name: </td><td width="160"><input type="text" 
		      name="new_album_name" value="'.$new_album_name.'"> 
		      <td width="90">In collection:</td><td>
				'.generate_collections_menu().'</td></tr></table></td><tr>
		      <td><br><input class="submit" type="submit" name="upload" value="Import" /></td></tr>
		      </table></div>';
		
		    $output .= '</form>';
		}
	}
}


$output_error = '<h1>Import</h1><p class="actions">Before you can begin importing photos to your gallery, you must
create at least <b>one collection</b> AND <b>one album</b> within that collection.  Move over to 
the <a href="plog-manage.php">"Manage"</a> tab to begin creating your organizational structure</p>';

$num_albums = count_albums();

if ($num_albums > 0)
	 display($output, "import");
else
	 display($output_error, "import");
	 
?>
