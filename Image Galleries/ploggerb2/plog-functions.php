<?php
function connect_db() {
	if (!PLOGGER_DB_HOST) {
		die("Please run _install.php to set up Plogger.  If you are upgrading from a previous version, please run _upgrade.php.");
	};
	global $PLOGGER_DBH;
	$PLOGGER_DBH = mysql_connect(PLOGGER_DB_HOST, PLOGGER_DB_USER, PLOGGER_DB_PW)
		or die ("Plogger cannot connect to the database because: " . mysql_error());
	mysql_select_db(PLOGGER_DB_NAME);
}

function run_query($query) {
	global $PLOGGER_DBH;
	$result = mysql_query($query,$PLOGGER_DBH) or die(mysql_error($PLOGGER_DBH) . "<br /><br />" . $query);
	return $result;
}

function generate_thumb($path, $prefix, $type = 'small') {
	global $config;
	require_once("lib/phpthumb/phpthumb.class.php"); 	//PHP thumb class

	// for relative paths assume that they are relative to images directory,
	// otherwise just use the given pat
	if (substr($path,0,1) != '/') {
		$source_file_name = $config['basedir'] . 'images/' . $path;
	} else {
		$source_file_name = $path;
	}
	
	// the file might have been deleted and since phpThumb dies in that case
	// try to do something sensible so that the rest of the images can still be seen

	// there is a problem in safe mode - if the script and picture file are owned by
	// different users, then the file can not be read.
	if (!is_readable($source_file_name)) {
		return false;
	};
	
	$imgdata = @getimagesize($source_file_name);

	if (!$imgdata) {
		// unknown image format, bail out
		return false;
	}

	// XXX: food for thought - maybe we can return URL to some kind of error image 
	// if this function fails?

	$base_filename = sanitize_filename(basename($path));

	global $thumbnail_config;
	$thumb_config = $thumbnail_config[$type];

	$prefix = $thumb_config['filename_prefix'] . $prefix . "-";

	$thumbpath = $config['basedir'] . 'thumbs/'.$prefix.$base_filename;
	$thumburl = $config['baseurl'] . 'thumbs/'.$prefix.$base_filename;


	// check to see if the file exists before creating the object
	// if the user has changed their thumbnail size in the configuration
	// then we need to refresh the thumbnail cache.
	list($width, $height, $type, $attr) = @getimagesize($thumbpath);

	if (!file_exists($thumbpath) ||
		($thumb_config['filename_prefix'] == '' && !$config['square_thumbs'] && $thumb_config['size'] != $height) ||
		($thumb_config['filename_prefix'] == '' && !$config['square_thumbs'] && $width == $height) ||
		($thumb_config['filename_prefix'] == '' && $config['square_thumbs'] && $width != $height) ||
		($thumb_config['filename_prefix'] != '' && $thumb_config['size'] != $width) ||
		($thumb_config['filename_prefix'] == '' && $config['square_thumbs'] && $thumb_config['size'] != $height && $thumb_config['size'] != $width)) {

		$phpThumb = new phpThumb();
		
		// set data
		$phpThumb->src = $source_file_name;
		$phpThumb->w = $thumb_config['size'];
		$phpThumb->q = $config['compression'];

		// set zoom crop flag to get image squared off
	
		if ($thumb_config['filename_prefix'] == '' && $config['square_thumbs']) {
			$phpThumb->zc = 1;
			$phpThumb->h = $thumb_config['size'];
		}

		$phpThumb->config_use_exif_thumbnail_for_speed = false;
		
		// Set image height instead of width if not using square thumbs
		if (!$config['square_thumbs']) {
			$phpThumb->h = $thumb_config['size'];
			$phpThumb->w = '';
		}

		// set options (see phpThumb.config.php)
		// here you must preface each option with "config_"

		// Set error handling (optional)
		$phpThumb->config_error_die_on_error = false;

		// generate & output thumbnail
		if ($phpThumb->GenerateThumbnail()) {
			$phpThumb->RenderToFile($thumbpath);
		}
		else {
			// do something with debug/error messages
			die('Failed: '.implode("\n", $phpThumb->debugmessages));
		}
	}
	return $thumburl;
}

function get_picture_by_id($id) {
	global $TABLE_PREFIX;
	global $config;
	$id = intval($id);
	$sql = "SELECT p.*,a.path AS album_path,c.path AS collection_path
			FROM `".$TABLE_PREFIX."pictures` p, `".$TABLE_PREFIX."albums` a,`".$TABLE_PREFIX."collections` c
			WHERE p.parent_album = a.id AND p.parent_collection = c.id AND p.id = '$id'";
	$resultPicture = run_query($sql);
	$picdata = mysql_fetch_assoc($resultPicture);
	if (is_array($picdata)) {
		// eventually I want to get rid of the full path in pictures tables to avoid useless data duplication
		// the following is a temporary solution so I don't have to break all the functionality at once
		$picdata['url'] = $config['baseurl'].'images/'.$picdata['collection_path'].'/'.$picdata['album_path'].'/'.basename($picdata['path']);
	};
	return $picdata;
}

function get_collection_by_name($name) {
	global $TABLE_PREFIX;
	$name = mysql_real_escape_string($name);
	$sql = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE name = '$name'";
	$result = run_query($sql);
	$collection = mysql_fetch_assoc($result);
	return $collection;
}

function get_albums() {
	global $TABLE_PREFIX;
	$albums = array();
	$sql = "SELECT `".$TABLE_PREFIX."albums`.id AS album_id,
                                 `".$TABLE_PREFIX."albums`.name AS album_name,
                                 `".$TABLE_PREFIX."collections`.id AS collection_id,
                                 `".$TABLE_PREFIX."collections`.name AS collection_name
                                 FROM `".$TABLE_PREFIX."albums`
                         LEFT JOIN `".$TABLE_PREFIX."collections` ON `".$TABLE_PREFIX."albums`.parent_id = `".$TABLE_PREFIX."collections`.id
                         ORDER BY `".$TABLE_PREFIX."collections`.name ASC, `".$TABLE_PREFIX."albums`.name ASC";

	$result = run_query($sql);
	while ($row = mysql_fetch_assoc($result)){
		$albums[$row["album_id"]] = $row;
	};
	return $albums;
}

function get_collections() {
	global $TABLE_PREFIX;
	$sqlCollection = "SELECT * FROM `".$TABLE_PREFIX."collections` ORDER BY `name` ASC";
	$resultCollection = run_query($sqlCollection);
	$collections = array();
	while ($collection = mysql_fetch_assoc($resultCollection)){
		$collections[$collection["id"]] = $collection;
	};
	return $collections;
}

//SmartAddSlashes
function SmartAddSlashes($str){
	if (get_magic_quotes_gpc()){
		return $str;

	}
	else{
		return addslashes($str);
	
	} //if magic_quotes_gpc

}//SmartAddSlashes



//SmartStripSlashes
function SmartStripSlashes($str){
	if (get_magic_quotes_gpc()){
		return stripslashes($str);
	
	}else{
		return $str;
	
	}//if magic_quotes_gpc

}//SmartStripSlashes

// this tries hard to figure out level and object id from textual path to a resource, used 
// mostly if mod_rewrite is in use
function resolve_path($str = "") {
	global $TABLE_PREFIX;
	$rv = array();
	$path_parts = explode("/",$str);

	$levels = array("collection","album","picture");

	$current_level = "";

	$names = array();


	foreach($levels as $key => $level) {
		if (isset($path_parts[$key])) {
			$names[$level] = mysql_real_escape_string(urldecode(SmartStripSlashes($path_parts[$key])));
			$current_level = $level;
		};
	};

	if (!empty($names["collection"])) {
		 $sql = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE path = '" . $names["collection"] . "'";
		 $result = run_query($sql);
		 $collection = mysql_fetch_assoc($result);
		 // no such collection, return
		 if (empty($collection)) return $rv;
		 // what if there are multiple collections with same names? I hope there aren't .. this would
		 // suck. But here is an idea, we shouldn't allow the user to enter similar names
		 $rv = array("level" => "collection","id" => $collection["id"]);
	};

	if (!empty($names["album"])) {
		$sql = "SELECT * FROM `".$TABLE_PREFIX."albums`
				WHERE path = '" . $names["album"] . "' AND parent_id = " . $collection["id"];
		$result = run_query($sql);
		$album = mysql_fetch_assoc($result);
		// no such album, fall back to collection
		if (empty($album)) return $rv;

		// try to detect slideshow. Downside is that you cannot have a picture with that name
		if ($names["picture"] == "slideshow") return array("level" => "slideshow","id" => $album["id"]);
		
		$rv = array("level" => "album","id" => $album["id"]);
	};

	if (!empty($names["picture"])) {
		$sql = "SELECT * FROM `".$TABLE_PREFIX."pictures`
				WHERE caption = '" . $names["picture"] . "' AND parent_album = " . $album["id"];
		$result = run_query($sql);
		$picture = mysql_fetch_assoc($result);
		// no such caption, perhaps we have better luck with path?
		if (!$picture) {
			$filepath = join("/",$names);
			$sql = "SELECT * FROM `".$TABLE_PREFIX."pictures`
					WHERE path = '" . $filepath . "' AND parent_album = " . $album["id"];
			$result = run_query($sql);
			$picture = mysql_fetch_assoc($result);
		};

		// no dice, fall back to album
		if (!$picture) return $rv;

		$rv = array("level" => "picture", "id" => $picture["id"]);
	};
	return $rv;
}

function generate_pagination($url, $current_page, $items_total, $items_on_page){
	$output = '';

	$num_pages = ceil($items_total / $items_on_page);

	// if adding arguments to mod_rewritten urls, then I need ? (question mark) before the arguments
	// otherwise I want &amp;
	$last = substr($url,-1);
	if ($last == "/")
	{
		//$url = substr($url,0,-1);
		$separator = "?";
	}
	else {
		$separator = "&amp;";
	};	

	if ($num_pages > 1){
			
		if ($current_page > 1){
			$output .= ' <a accesskey="," href="'.$url.$separator.'page='.($current_page - 1).'">&laquo;</a> ';
		}
			
		for ($i = 1; $i <= $num_pages; $i++){
			if ($i == $current_page){
				$output .= '<span class="page_link">['.$i.']</span>';
			}
			else{
				$output .= '<a href="'.$url.$separator.'page='.$i.'" class="page_link">'.$i.'</a> ';
			}
		}
		
		if ($current_page != $num_pages){
			$output .= ' <a accesskey="." href="'.$url.$separator.'page='.($current_page + 1).'">&raquo;</a> ';
		}
	}
		
	return $output;
}

// sanitize filename by replacing international characters with underscores
function sanitize_filename($str) 
{
	// allow only alfanumeric characters, hyphen, [, ], dot and apostrophe in file names
	// the rest will be replaced
	return preg_replace("/[^\w|\.|'|\-|\[|\]]/","_",$str);
}

function generate_url($level,$id,$name = ""){
	global $config;
	global $TABLE_PREFIX;
	if ($config["use_mod_rewrite"]){
		if ($level == "collection"){
			$query = "SELECT path FROM `".$TABLE_PREFIX."collections`  WHERE id='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			return $config["baseurl"].rawurlencode($row["path"]);
		} else if ($level == "album") {
			$query = "SELECT c.path AS collection_path,a.path AS album_path FROM `".$TABLE_PREFIX."albums` a LEFT JOIN `".$TABLE_PREFIX."collections` c ON (a.parent_id = c.id) WHERE a.id='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			return $config["baseurl"].rawurlencode($row["collection_path"]) . '/' . rawurlencode($row["album_path"]);
		} else if ($level == "picture") {
			$pic = get_picture_by_id($id);
			$album = $pic["parent_album"];
			return $config["baseurl"].$pic["path"];
		};
	} else {
		if ($level == "collection"){
			return $config['baseurl'].'index.php?level=collection&id='.$id;
		} else if ($level == "album") {
			return $config['baseurl'].'index.php?level=album&id='.$id;
		} else if ($level == "picture") {
			return $config['baseurl'].'index.php?level=picture&id='.$id;
		};
	}
}

// Begin basic Plogger API functions

// plogger_list_categories()
// This function will create a list of nested categorical links
// for use in sidebars

function plogger_list_categories($class) {
	
	// first select id and name for all collections
	$query = "SELECT * FROM ".$TABLE_PREFIX."collections";
	$result = run_query($query);
	
	$output = "<ul class=\"$class\">";
	
	// loop through each collection, output child albums
	while ($row = mysql_fetch_assoc($result)) {
		// output collection name
		$collection_link = '<a href="'.generate_url("collection",$row['id'], $row['name']).'">'.$row['name'].'</a>';
		$output .= "<li>$collection_link</li>";
		
		// loop through child albums
		$query = "SELECT * FROM ".$TABLE_PREFIX."albums WHERE parent_id = '$row[id]' ORDER BY name DESC";
		
		$output .= '<ul>';
		while ($albums = mysql_fetch_assoc($result)) {
			$album_link = '<a href="'.generate_url("albums",$albums['id'], $albums['name']).'">'.$albums['name'].'</a>';
			$output .= "<li>$album_link</li>";
		}
		
		$output .= '</ul>';
	}	
	
	$output .= '</ul>';
	
	echo $output;
}
?>
