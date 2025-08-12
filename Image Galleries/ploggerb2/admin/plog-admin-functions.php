<?php
require_once($config['basedir'] . "/plog-functions.php");
require_once($config['basedir'] . "/lib/exifer1_4/exif.php");
function add_picture($album_id,$tmpname,$filename,$caption) {
	global $TABLE_PREFIX;
	global $config;

	
	$filename_parts = explode(".",strrev($filename),2);
	$filename_base = strrev($filename_parts[1]);
	$filename_ext = strrev($filename_parts[0]);

	$result = array(
		'output' => '',
		'picture_id' => false,
	);

	$i = 0;

	$unique_filename_base = strtolower(sanitize_filename($filename_base));

	// now get the name of the collection

	$sql = "SELECT c.path AS collection_path, c.id AS collection_id,
			a.path AS album_path, a.id AS album_id
			FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."collections c
			WHERE c.id = a.parent_id AND a.id = '$album_id'";

	$sql_result = run_query($sql);
	$albumdata = mysql_fetch_assoc($sql_result);

	// this shouldn't happen in normal cases
	if (empty($albumdata)) {
		$result['errors'] .= 'No such album!';
		return $result;
	}

	$dest_album_name = SmartStripSlashes($albumdata["album_path"]);
	$dest_collection_name = SmartStripSlashes($albumdata["collection_path"]);

	$create_path = $dest_collection_name."/".$dest_album_name;

	while (is_file('images/'.$create_path."/".$unique_filename_base . "." . $filename_ext)){
		$unique_filename_base = $filename_base . " (" . ++$i .")";
	}

	$final_filename = $unique_filename_base . "." . $filename_ext;

	// final fully qualified file name
	$final_fqfn = $config["basedir"].'images/'.$create_path.'/'.$final_filename;

	if (!makeDirs($config['basedir'].'images/'.$create_path, 0777)) {
		$result['errors'] .= 'Could not create directory '.$create_path.'!';
		return $result;
	};

	// cannot use move_uploaded_file here, because plog-import uses the same function and 
	// and doesn't deal with uploaded files
	//if (!move_uploaded_file($tmpname,$final_fqfn)) {
	if (!rename($tmpname,$final_fqfn)) {
		$result['errors'] .= 'Could not move file! ' . $tmpname .' to '.$final_fqfn;
		return $result;
	};

	// Get the EXIF data.
	$exif_raw = read_exif_data_raw($final_fqfn);
	$exif = array();

	$exif["date_taken"] = (isset($exif_raw["IFD0"]["DateTime"])) ? trim($exif_raw["IFD0"]["DateTime"]) : '';
	$exif["camera"] = (isset($exif_raw["IFD0"]["Make"]) && isset($exif_raw["IFD0"]["Model"])) ? trim($exif_raw["IFD0"]["Make"]) . " " . trim($exif_raw["IFD0"]["Model"]) : '';
	$exif["shutter_speed"] = (isset($exif_raw["SubIFD"]["ExposureTime"])) ? $exif_raw["SubIFD"]["ExposureTime"] : '';
	$exif["focal_length"] = (isset($exif_raw["SubIFD"]["FocalLength"])) ? $exif_raw["SubIFD"]["FocalLength"] : '';
	$exif["flash"] = (isset($exif_raw["SubIFD"]["Flash"])) ? $exif_raw["SubIFD"]["Flash"] : '';
	$exif["aperture"] = (isset($exif_raw["SubIFD"]["FNumber"])) ? $exif_raw["SubIFD"]["FNumber"] : '';

	$picture_path = $create_path . "/" . $final_filename;

	$query = "INSERT INTO `".$TABLE_PREFIX."pictures`
		(`parent_collection`,
		`parent_album`,
		`path`,
		`date_modified`,
		`date_submitted`,
		`allow_comments`,
		`EXIF_date_taken`,
		`EXIF_camera`,
		`EXIF_shutterspeed`,
		`EXIF_focallength`,
		`EXIF_flash`,
		`EXIF_aperture`,
		`caption`)
		VALUES
          ('".$albumdata['collection_id']."',
           '".$albumdata['album_id']."','".mysql_escape_string($picture_path)."',
           NOW(),
           NOW(),
           1,
           '".mysql_escape_string($exif["date_taken"])."',
           '".mysql_escape_string($exif["camera"])."',
           '".mysql_escape_string($exif["shutter_speed"])."',
           '".mysql_escape_string($exif["focal_length"])."',
           '".mysql_escape_string($exif["flash"])."',
           '".mysql_escape_string($exif["aperture"])."',
           '".mysql_escape_string($caption)."')";
	$sql_result = run_query($query);

	$result['output'] .= 'Your photo ('.$filename.') was uploaded successfully.';
	$result['picture_id'] = mysql_insert_id();
	
	// let's generate the thumbnail and the large thumbnail right away.
	// this way, the user won't see any latency from the thumbnail generation
	// when viewing the gallery for the first time
	// this also helps with the image pre-loading problem introduced
	// by a javascript slideshow.
	
	$thumbpath = generate_thumb($picture_path, $result['picture_id'],'small');
	$thumbpath = generate_thumb($picture_path, $result['picture_id'],'large');
	
	return $result;
};

function update_picture($id,$caption,$allow_comments) {
	global $TABLE_PREFIX;
	$id = intval($id);
	$caption = mysql_real_escape_string($caption);
	$allow_comments = intval($allow_comments);
	$query = "UPDATE ".$TABLE_PREFIX."pictures SET caption = '$caption', allow_comments = '$allow_comments' WHERE id='$id'";
	$result = mysql_query($query);
	if ($result) 
		return array('output' => 'You have successfully modified the selected picture.');
	else
		return array('errors' => mysql_error());
}
		
function delete_picture($del_id) {
	global $TABLE_PREFIX;
	global $config;
	$del_id = intval($del_id);
	global $thumbnail_config;
	$picture = get_picture_by_id($del_id);
	if ($picture) {
		
		$query = "DELETE FROM ".$TABLE_PREFIX."pictures WHERE `id`= '" . $picture['id'] . "'";
		run_query($query);
		
		// delete all comments for the picture
		$query = "DELETE FROM ".$TABLE_PREFIX."comments WHERE `parent_id`= '" . $picture['id'] . "'";
		run_query($query);

		// make sure that the file is actually located inside our images directory
		$full_path = realpath($config['basedir'] . 'images/' . $picture['path']);
		// also check whether this image is in the correct folder
		$relative_path = substr($full_path,0,strlen($config['basedir']));
		$basename = basename($picture['path']);
		if ($relative_path == $config['basedir']) {
			foreach($thumbnail_config as $tkey => $tval) {
				$thumbpath = $config['basedir'].'thumbs/'.$tval['filename_prefix'].$picture['id'].'-'.$basename;
				if (file_exists($thumbpath) && is_writable($thumbpath)) {
					//print "deleting $thumbpath<br>";
					@chmod($thumbpath, 0777);
					unlink($thumbpath);
				};
			};
			if (is_file($full_path)) {
				// print "deleting $full_path<br>";
				@chmod($full_path, 0777);
				
				if (!unlink($full_path))
					 return array('errors' => 'Could not physically delete file from disk!');
			};
		} else {
			return array('errors' => 'Picture has invalid path, ignoring delete request');
		};
	} else {
		return array('errors' => 'There is no picture with id ' . $del_id);
	};
};

function add_collection($collection_name, $description) {
	global $config;
	global $TABLE_PREFIX;
	$output = $errors = "";
	$id = 0;
	$collection_name = trim(SmartStripSlashes($collection_name));
	if (empty($collection_name)) {
		return array("errors" => "Please enter a valid name for the collection");
	};

	// do not allow collections with duplicate names, otherwise mod_rewritten links will start
	// to behave weird.
	$collection_exists = get_collection_by_name($collection_name);
	if ($collection_exists) {
		return array("errors" => 'New collection could not be created, because there already is one named `'.$collection_exists['name'].'`!');
	}

	$collection_folder = strtolower(sanitize_filename($collection_name));
	// first try to create the directory, and only if that succeeds, then insert
	// a new row into collections table, otherwise the collection will not be usable
	// anyway
	$create_path = $config["basedir"] . "/images/".$collection_folder;

	// create directory
	if (!makeDirs($create_path, 0777)) {
		$errors .= "Could not create directory $create_path!</p>";
	} else {
		$sql_name = mysql_real_escape_string($collection_name);
		$description = mysql_real_escape_string($description);
		$collection_folder = mysql_real_escape_string($collection_folder);
		$query = "INSERT INTO ".$TABLE_PREFIX."collections  (`name`,`description`,`id`,`path`) VALUES ('$sql_name', '$description', '', '$collection_folder')";
		$result = run_query($query);
		$id = mysql_insert_id();

		$output .= 'You have successfully created the collection <b>'.$collection_name.'.</b>';    
	};

	// caller can check the value of id, if it is zero, then collection creation failed
	// errors and output are separate, because this way the caller can format the return value
	// as it needs
	$result = array(
		"output" => $output,
		"errors" => $errors,
		"id" => $id,
	);
	return $result;

}

function update_collection($collection_id,$name,$description,$thumbnail_id = 0) {
	global $TABLE_PREFIX;
	global $config;

	$errors = $output = "";
	
	$name = trim(SmartStripSlashes($name));
	if (empty($name)) {
		return array("errors" => "Please enter a valid name for the collection");
	};

	$target_name = strtolower(sanitize_filename($name));
	

	$errors = $output = "";

	$collection_id = intval($collection_id);
	$thumbnail_id = intval($thumbnail_id);

	$name = mysql_real_escape_string($name);
	$description = mysql_real_escape_string($description);

	// rename the directory
	// first, get the collection name of our source collection
	$sql = "SELECT c.path as collection_path,name
			FROM ".$TABLE_PREFIX."collections c
			WHERE c.id = '$collection_id'";

	$result = run_query($sql);
	$row = mysql_fetch_assoc($result);
	
	// do not allow collections with duplicate names, otherwise mod_rewritten links will start
	// to behave weird.
	$collection_exists = get_collection_by_name($name);
	if ($row["name"] != $name && $collection_exists) {
		return array("errors" => 'Collection `' . $row['name'] . '` could not be renamed to `'.$name.'`, because there is another collection with that name');
	}

	$source_collection_name = SmartStripSlashes($row["collection_path"]);
	$source_path = $config["basedir"] . "images/".$source_collection_name;
	$target_path = $config["basedir"] . "images/".$target_name;
	
	// perform the rename on the directory
	if (!rename($source_path, $target_path)) {
		return array("errors" => "Error renaming directory! ($source_path to $target_path)");
	};

	$target_name = mysql_real_escape_string($target_name);

	$query = "UPDATE ".$TABLE_PREFIX."collections SET name = '$name', path = '$target_name', description = '$description', thumbnail_id = '$thumbnail_id' WHERE id='$collection_id'";
	$result = mysql_query($query);
	if (!$result) {
		return array("errors" => mysql_error());
	};


	$output = 'You have successfully modified the selected collection.';

	// update the path field for all pictures within that collection
	// now we need to update the database paths of all pictures within source album
	$sql = "SELECT p.id AS id,p.path AS path, c.name AS collection_name, a.path AS album_path
		FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."pictures p, ".$TABLE_PREFIX."collections c
		WHERE p.parent_album = a.id AND p.parent_collection = c.id AND p.parent_collection = '$collection_id'";

	$result = run_query($sql);

	while($row = mysql_fetch_assoc($result)) {

		$filename = basename(SmartStripSlashes($row['path']));
		$album_path = $row['album_path'];

		$new_path = $target_name."/".$album_path."/".$filename;

		// update database
		$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = '$new_path' WHERE id = '$row[id]'";
		mysql_query($sql) or ($output .= mysql_error());
	}

	return array(
		"errors" => $errors,
		"output" => $output,
	);
}

function delete_collection($del_id) {
	global $TABLE_PREFIX;
	global $config;
	$sql = "SELECT c.name AS collection_name, c.path AS collection_path, c.id AS collection_id
		FROM ".$TABLE_PREFIX."collections c
		WHERE c.id = '$del_id'";

	$result = run_query($sql);
	$collection = mysql_fetch_assoc($result);

	if (!$collection) {
		return array('errors' => 'No such collection');
	};

	// first delete all albums registered with this album
	$sql = 'SELECT * FROM '.$TABLE_PREFIX.'albums WHERE parent_id = ' . $collection['collection_id'];
	$result = run_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		delete_album($row['id']);
	};
			
	// XXX: un-register collection
	$query = "DELETE FROM ".$TABLE_PREFIX."collections WHERE `id`= '" . $collection['collection_id'] . "'";
	run_query($query);

	// finally try to delete the directory itself. It will succeed, if there are no files left inside it ..
	// if there are then .. how did they get there? Probably not through plogger and in this case do we 
	// really want to delete those?
	$source_collection_name = $collection["collection_path"];

	$collection_directory = realpath($config['basedir'] . 'images/'.$source_collection_name);
	$relative_path = substr($collection_directory,0,strlen($config['basedir']));
	$collection_path = explode('/',substr($collection_directory,strlen($config['basedir'])));
	// it needs to have 2 parts - images and collection name, if it doesn't, then there is something
	// wrong with collection name and it's probably not safe to try to delete the directory
	if ($relative_path == $config['basedir'] && sizeof($collection_path) == 2) {
		@chmod($collection_directory,0777);
		$delete_result = rmdir($collection_directory);
		if (!$delete_result) {
			return array('errors' => 'Collection directory still contains files after all albums have been deleted.');
		};
		
	} else {
		return array('errors' => 'Collection has invalid path, not deleting directory');
	};
	return array();
}

function add_album($album_name, $description, $pid) {
	global $config;
	global $TABLE_PREFIX;
	$output = $errors = "";
	$id = 0;
	$album_name = trim(SmartStripSlashes($album_name));
	if (empty($album_name)) {
		return array("errors" => "Please enter a valid name for the album");
	};
	// get the parent collection name
	$query = "SELECT c.path as collection_path FROM ". $TABLE_PREFIX."collections c WHERE id = '$pid'";

	$result = run_query($query);
	$row = mysql_fetch_assoc($result);

	// this shouldn't happen
	if (empty($row)) {
		return array("errors" => "No such collection");
	};

	$album_folder = strtolower(sanitize_filename($album_name));

	// first try to create the directory to hold the images, if that fails, then the album
	// will be unusable anyway
	$create_path = $config["basedir"] . "/images/".$row["collection_path"]."/".$album_folder;

	if (!makeDirs($create_path, 0777)) {
		$errors .= "Could not create directory $path!";
	} else {
		$sql_name = mysql_real_escape_string($album_name);
		$description = mysql_real_escape_string($description);
		$album_folder = mysql_real_escape_string($album_folder);
		$query = "INSERT INTO ".$TABLE_PREFIX."albums (`name`,`description`,`parent_id`,`path`) VALUES ('$sql_name', '$description', '$pid','$album_folder')";
		$result = run_query($query);
		$id = mysql_insert_id();

		$output .= 'You have successfully created the album <b>'.$album_name.'.</b>';
	};
	// caller can check the value of id, if it is zero, then album creation failed
	// errors and output are separate, because this way the caller can format the return value
	// as it needs
	$result = array(
		"output" => $output,
		"errors" => $errors,
		"id" => $id,
	);
	return $result;
}

function update_album($album_id,$name,$description,$thumbnail_id = 0) {
	global $TABLE_PREFIX;
	global $config;

	$errors = $output = "";

	$target_name = strtolower(sanitize_filename($name));

	$album_id = intval($album_id);
	$thumbnail_id = intval($thumbnail_id);
	$name = mysql_real_escape_string($name);
	$description = mysql_real_escape_string($description);
	

	 // first, get the album name and collection name of our source album
	$sql = "SELECT c.path AS collection_path, a.path AS album_path
			FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."collections c
			WHERE c.id = a.parent_id AND a.id = '$album_id'";

	$result = run_query($sql);
	$row = mysql_fetch_assoc($result);

	$source_album_name = $row["album_path"];
	$source_collection_name = $row["collection_path"];     


	$source_path = $config['basedir'] . "images/".$source_collection_name."/".$source_album_name;
	$target_path = $config['basedir'] . "images/".$source_collection_name."/".$target_name;

	// perform the rename on the directory
	if (!rename($source_path, $target_path))
	{
		return array(
			"errors" => "Error renaming directory! ($source_path to $target_path)",
		);
	};

	$target_name = mysql_real_escape_string($target_name);

	// proceed only if rename succeeded
	$query = "UPDATE ".$TABLE_PREFIX."albums SET
			name = '$name',
			description = '$description',
			thumbnail_id = '$thumbnail_id',
			path = '$target_name'
		 WHERE id='$album_id'";

	$result = mysql_query($query);
	if (!$result) {
		return array("errors" => mysql_error());
	};


	$output .= 'You have successfully modified the selected album.';

	// update the path field for all pictures within that album
	$sql = "SELECT p.path AS path, p.id AS id,c.name AS collection_name, a.name AS album_name
			FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."pictures p, ".$TABLE_PREFIX."collections c
			WHERE p.parent_album = a.id AND p.parent_collection = c.id AND p.parent_album = '$album_id'";

	$result = run_query($sql);

	while($row = mysql_fetch_assoc($result)) {

		$filename = basename($row['path']);
		$new_path = $source_collection_name."/".$target_name."/".$filename;

		// update database
		$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = '$new_path' WHERE id = '$row[id]'";
		mysql_query($sql) or ($errors .= mysql_error());
	}

	return array(
		"errors" => $errors,
		"output" => $output,
	);
}

function delete_album($del_id) {
	global $TABLE_PREFIX;
	global $config;
	$sql = "SELECT c.name AS collection_name, a.name AS album_name, a.id AS album_id, c.path AS collection_path, a.path AS album_path
		FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."collections c
		WHERE c.id = a.parent_id AND a.id = '$del_id'";

	$result = run_query($sql);
	$album = mysql_fetch_assoc($result);

	if (!$album) {
		return array('errors' => 'No such album');
	};

	// first delete all pictures registered with this album
	$sql = 'SELECT * FROM '.$TABLE_PREFIX.'pictures WHERE parent_album = ' . $album['album_id'];
	$result = run_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		delete_picture($row['id']);
	};
			
	// XXX: un-register album
	$query = "DELETE FROM ".$TABLE_PREFIX."albums WHERE `id`= '" . $album['album_id'] . "'";
	run_query($query);

	// finally try to delete the directory itself. It will succeed, if there are no files left inside it ..
	// if there are then .. how did they get there? Probably not through plogger and in this case do we 
	// really want to delete those?
	$source_album_name = $album["album_path"];
	$source_collection_name = $album["collection_path"];

	$album_directory = realpath($config['basedir'] . 'images/'.$source_collection_name."/".$source_album_name);
	$relative_path = substr($album_directory,0,strlen($config['basedir']));
	$album_path = explode('/',substr($album_directory,strlen($config['basedir'])));
	// it needs to have 3 parts - images, collection name and album name, if it doesn't, then there is something
	// wrong with either collectio or album name and it's probably not safe to try to delete the directory
	if ($relative_path == $config['basedir'] && sizeof($album_path) == 3) {
		@chmod($album_directory,0777);
		$delete_result = rmdir($album_directory);
		if (!$delete_result) {
			return array('errors' => 'Album directory still contains files after all pictures have been deleted.');
		};
		
	} else {
		return array('errors' => 'Album has invalid path, not deleting directory');
	};
	return array();
}


function add_comment($parent_id,$author,$email,$url,$comment) {
	global $TABLE_PREFIX;
	global $config;

	if (empty($config["allow_comments"])) {
		return array("errors" => "Comments disabled");
	};

	if (empty($author) || empty($email)) {
		return array("errors" => "Your comment did not post!  Please fill the required fields.");
	};

	$ip = $_SERVER["REMOTE_ADDR"];
	$host = gethostbyaddr($ip);

	// I want to use the original unescaped values later - to send the email
	$sql_author = mysql_real_escape_string($author);
	$sql_email = mysql_real_escape_string($email);
	$sql_url = mysql_real_escape_string($url);
	$sql_comment = mysql_real_escape_string($comment);
	$sql_ip = mysql_real_escape_string($ip);

	$parent_id = intval($parent_id);

	$result = array();

	$picdata = get_picture_by_id($parent_id);
	if (empty($picdata)) {
		return array("errors" => "Could not post comment - no such picture");
	};

	if (empty($picdata["allow_comments"]))
	{
		return array("errors" => "Comments disabled");
	};

	// right now all comments will be approved, spam protection can be implemented later
	$query = "INSERT INTO ".$TABLE_PREFIX."comments SET 
			author='$sql_author', 
			email='$sql_email', 
			url='$sql_url', 
			date=NOW(),
			comment='$sql_comment',
			parent_id='$parent_id', 
			approved = 1, 
			ip = '$ip'
	";

	$result = mysql_query($query);
	if (!$result) {
		return array("errors" => "Could not post comment " . mysql_error());
	};
	// XXX: admin e-mail address should be validated
	if ($config["comments_notify"] && $config["admin_email"]) {
		// create and send notify mail message
		$msg = "New comment posted for picture " . basename($picdata['path']) . "\n\n";
		$msg .= "Author: $author (IP: $ip, $host)\n";
		$msg .= "E-mail: $email\n";
		$msg .= "URI: $url\n\n";
		$msg .= "Comment:\n$comment\n\n";
		$msg .= "You can see all the comments for this picture here:\n";
		$picurl = generate_url("picture",$parent_id);
		$msg .= $picurl;
		mail($config['admin_email'],$config['gallery_name'] . ': new comment from '.$author,$msg,"From: $email");
	};
	return array("result" => "Comment added.");
}

function update_comment($id,$author,$email,$url,$comment) {
	global $TABLE_PREFIX;
	$id = intval($id);
	$author = mysql_real_escape_string($author);
	$email = mysql_real_escape_string($email);
	$url = mysql_real_escape_string($url);
	$comment = mysql_real_escape_string($comment);

	$query = "UPDATE ".$TABLE_PREFIX."comments SET author = '$author', comment = '$comment',
			url = '$url', email = '$email' WHERE id='$id'";
	$result = mysql_query($query);
	if ($result)
		return array('output' => 'You have successfully modified the selected comment.');
	else
		return array('errors' => mysql_error());
}

function count_albums($parent_id = 0) {
	global $TABLE_PREFIX;
	if (!$parent_id)
		$numquery = "SELECT COUNT(*) AS `num_albums` FROM `".$TABLE_PREFIX."albums`";
	else
		$numquery = "SELECT COUNT(*) AS `num_albums` FROM `".$TABLE_PREFIX."albums` WHERE parent_id = '$parent_id'";
		
	$numresult = run_query($numquery);
	$num_albums = mysql_result($numresult, 'num_albums');
	return $num_albums;
}

function count_pictures($parent_id = 0) {
	global $TABLE_PREFIX;
	if (!$parent_id)
		$numquery = "SELECT COUNT(*) AS `num_pics` FROM `".$TABLE_PREFIX."pictures`";
	else
		$numquery = "SELECT COUNT(*) AS `num_pics` FROM `".$TABLE_PREFIX."pictures` WHERE parent_album = '$parent_id'";
		
	$numresult = run_query($numquery);
	$num_pics = mysql_result($numresult, 'num_pics');
	return $num_pics;
}

function count_comments($parent_id = 0) {
	global $TABLE_PREFIX;
	if (!$parent_id)
		$numquery = "SELECT COUNT(*) AS `num_comments` FROM `".$TABLE_PREFIX."comments`";
	else
		$numquery = "SELECT COUNT(*) AS `num_comments` FROM `".$TABLE_PREFIX."comments` WHERE parent_id = '$parent_id'";
		
	$numresult = run_query($numquery);
	$num_comments = mysql_result($numresult, 'num_comments');
	return $num_comments;
}


function makeDirs($strPath, $mode = 0777) //creates directory tree recursively
{
   return is_dir($strPath) or ( makeDirs(dirname($strPath), $mode) and mkdir($strPath, $mode) );
}

// 
function configure_mod_rewrite($enable = false) {
	$cfg = "";
	$placeholder_start = "# BEGIN Plogger";
	$placeholder_end = "# END Plogger";
	$thisfile =  "/admin/" . basename(__FILE__);
	$adm = strpos($_SERVER["PHP_SELF"],"/admin");
	$rewritebase = substr($_SERVER["PHP_SELF"],0,$adm);
	if ($enable) {
		$cfg .= "\n";
		if (empty($rewritebase))
		{
			$rewritebase = "/";
		};
		$cfg .= "<IfModule mod_rewrite.c>\n";
		$cfg .= "RewriteEngine on\n";
		$cfg .= "RewriteBase $rewritebase\n";
		$cfg .= "RewriteCond %{REQUEST_FILENAME} -d [OR]\n";
		$cfg .= "RewriteCond %{REQUEST_FILENAME} -f\n";
		$cfg .= "RewriteRule ^.*$ - [S=2]\n";
		$cfg .= "RewriteRule feed/$ plog-rss.php?path=%{REQUEST_URI} [L]\n";
		$cfg .= "RewriteRule ^.*$ index.php?path=%{REQUEST_URI} [L]\n";
		$cfg .= "</IfModule>\n";
	};	
	// read the file
	global $config;
	$fpath = $config["basedir"] . ".htaccess"; 
	$htaccess_lines = @file($fpath);

	$output = "";
	$configuration_placed = false;
	$between_placeholders = false;
	foreach($htaccess_lines as $line) {
		$tline = trim($line);
		if ($placeholder_start == $tline) {
			$between_placeholders = true;
			$output .= $line . $cfg;
			$configuration_placed = true;
			continue;
		}
		if ($placeholder_end == $tline) {
			$between_placeholders = false;
			$output .= $line;
			continue;
		}
		if ($between_placeholders) continue;

		$output .= $line;
	};

	// no placeholders? append to the end
	if (!$configuration_placed) {
		$output .= $placeholder_start . "\n" . $cfg . $placeholder_end . "\n";
 	};

	$fh = @fopen($fpath,"w");
	// write changes out if the file can be opened.
	// XXX: perhaps plog-options.php should check whether settings can be written and warn the user if not?
	$success = false;
	if ($fh) {
		$success = true;
		fwrite($fh,$output);
		fclose($fh);
	};
	return $success;
}

// makes sure that argument does not contain characters that cannot be allowed, like . or /, which
// could be used to point to directory or file names outside the Plogger directory
function is_valid_directory($str) 
{
	// allow only alfanumeric characters, hyphen, [, ], dot, apostrophe  and space in collection names
	return !preg_match("/[^\w|\.|'|\-|\[|\] ]/",$str);
}


?>
