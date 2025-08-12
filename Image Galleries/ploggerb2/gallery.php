<?php
#error_reporting(E_ALL);

include("plog-functions.php");
include("plog-globals.php");


global $inHead;
global $config;

$baseurl = $config["baseurl"];
$inHead = <<<EOT
	<link rel="stylesheet" type="text/css" href="${baseurl}css/gallery.css" />
	<script type="text/javascript" src="${baseurl}dynamics.js"></script>
EOT;

// process path here - is set if mod_rewrite is in use
if (!empty($_REQUEST["path"])) {
	// the followling line calculates the path in the album and excludes any subdirectories if 
	// Plogger is installed in one
	$path = join("/",array_diff(explode("/",$_SERVER["REQUEST_URI"]),explode("/",$_SERVER["PHP_SELF"])));
	$resolved_path = resolve_path($path);
	if (is_array($resolved_path)) {
		$_GET["level"] = $resolved_path["level"];
		$_GET["id"] = $resolved_path["id"];

		// get page number from url, if present
		$parts = parse_url($_SERVER["REQUEST_URI"]);
		if (isset($parts["query"])) {
			parse_str($parts["query"],$query_parts);
			if (!empty($query_parts["page"])) $_GET["page"] = $query_parts["page"];
		};
		$path = $parts["path"];
	};
};
	
if ($_GET["level"] == "slideshow")
	$inHead .= generate_slideshow_js($_GET["id"], "album");
/*
else if ($level == "slideshow")
	$inHead .= generate_slideshow_js($id, "album");
*/

// Set sorting session variables if they are passed
if (isset($_GET['sortby'])) $_SESSION['plogger_sortby'] = $_GET['sortby'];
if (isset($_GET['sortdir'])) $_SESSION['plogger_sortdir'] = $_GET['sortdir'];
	
// This file contains the main gallery function the_gallery();
// this function is placed in the HTML document directly.
// The function does not take any arguments, it reads directly from
// the HTTP_GET_VARS array.

// The three GET parameters that it accepts are
// $level = "collection", "album", or "picture"
// $id = id number of collection, album, or picture
// $n = starting element (for pagination) go from n to n + max_thumbs (in global config)

function the_gallery_head() {
		global $inHead;
		echo $inHead;
}

function the_gallery(){
	
	$start = microtime();
	
	global $TABLE_PREFIX;
	global $config;


	$output = '';
	
	$level = isset($_GET["level"]) ? $_GET["level"] : '';
	$id = isset($_GET["id"]) ? $_GET["id"] : 0;
	$_REQUEST["id"] = $id;
	$_REQUEST["level"] = $level;
	$page = isset($_GET["page"]) ? $_GET["page"] : 1;
	$num = 0;

	// this is needed to get pagination work
	$total_items = 0;
	

	// Output highest level container division
	$output .= '
		<div id="wrapper">
			<table id="header-table" width="100%"><tr><td>'.generate_header().'</td><td style="text-align: right; vertical-align: bottom;">'.generate_jump_menu().generate_search_box().'</td></tr></table>';
	
	if ($level != "picture"){
		$output .= '<form action="' . $config["baseurl"] . 'plog-download.php" method="post">';
	}
	
	$output .= '
		<div id="main_container">
			<div id="breadcrumbs">
				<table width="100%">
					<tr>
						<td>
							'.generate_breadcrumb($level, $id).'
						</td>
						<td style="text-align: right;">';
	
	if ($level != "picture" && $level != "slideshow" && $config["allow_dl"]) {
		$output .= '
							<div id="download_selected">
								<input class="submit" type="submit" name="download_selected" value="Download Selected" />
							</div>';
	}
	else{
		if ($level == "picture" && $config["allow_print"]) {
  		$output .= '
  			<a class="print" href="' . $config["baseurl"] . 'plog-print.php?id='.$id.'">Print Image</a>';
		}
	}
	
	$output .= '
						</td>
					</tr>
				</table>
			</div>';

	if ($level == "picture"){

	
		// first lets load the thumbnail of the picture at the correct size
		$sql = "SELECT *, UNIX_TIMESTAMP(`date_submitted`) AS `unix_date_submitted`, UNIX_TIMESTAMP(`EXIF_date_taken`) AS `unix_exif_date_taken` FROM `".$TABLE_PREFIX."pictures` WHERE id = $id";
		$result = run_query($sql);
		$row = mysql_fetch_assoc($result);

		// generate a list of all image id-s so proper prev/next links can be created. This should be a 
		// fast query, even for big albums.
		$image_list = array();
		$sql = "SELECT id FROM `".$TABLE_PREFIX."pictures` WHERE parent_album = ".$row["parent_album"];

		// determine sort ordering
		switch ($_SESSION["plogger_sortby"]){
			case 'number_of_comments':
				$sql .= " ORDER BY `num_comments`";
				break;
			case 'caption':
				$sql .= " ORDER BY `caption` ";
				break;
			case 'date_taken':
				$sql .= " ORDER BY `EXIF_date_taken` ";
				break;
			case 'filename':
				$sql .= " ORDER BY `path` ";
				break;
			case 'date':
			default:
				$sql .= " ORDER BY `date_submitted` ";
				break;
		}
		
		switch ($_SESSION["plogger_sortdir"]){
			case 'ASC':
				$sql .= " ASC";
				break;
			case 'DESC':
			case 'default':
				$sql .= " DESC";
				break;
		}
	
		$result = run_query($sql);
		while ($image = mysql_fetch_assoc($result)) {
			$image_list[] = $image["id"];
		};

		$current_picture = array_search($id,$image_list);

		$prev_link = $next_link = "";

		if ($current_picture > 0) {
			$prev_link = '<a accesskey="," href="'.generate_url("picture",$image_list[$current_picture-1]).'">&laquo; Previous</a>';
		};

		if ($current_picture < sizeof($image_list)-1)
		{
			$next_link = '<a accesskey="." href="'.generate_url("picture",$image_list[$current_picture+1]).'">Next &raquo;</a>';
		};

		$date_taken = !empty($row["unix_exif_date_taken"]) ? $row["unix_exif_date_taken"] : $row["unix_date_submitted"];
		$output .= '
			<div id="inner_wrapper">
				<div id="big-picture-container">
					<table style="width: 100%;">
						<tr>
							<td>
								<h2 class="date">'.date($config["date_format"],$date_taken).'</h2>
							</td>
							<td style="text-align: right;">
								<h2 id="picture_caption">';
		
		$output .= (trim($row["caption"]) != '') ? stripslashes($row["caption"]) : '';
		
		$output .= '
								</h2>
							</td>
						</tr>
					</table>';
			

		$thumburl = generate_thumb($row['path'], $row['id'], 'large');
		
		// generate XHTML with thumbnail and link to picture view.
		$img_link = $config["baseurl"] . $row["path"];
		
		// generate XHTML with thumbnail and link to picture view.
		$imgtag = '<img class="photos-large" src="'.$thumburl.'" title="'.$row["caption"].'" alt="'.$row["caption"].'" />';
		
		if (!$_SESSION["plogger_details"]){
			$detail_url = '<a accesskey="d" href="javascript:void(0);" onclick="show_details();">Show details</a>';
		}
		else{
			$detail_url = '<a accesskey="d" href="javascript:void(0);" onclick="hide_details();">Hide details</a>';
		}
		
		$output .= '
			<table style="width: 100%;">
				<tr>
					<td style="width: 50%; text-align: left">
						'.$prev_link.'
					</td>
					<td style="width: 50%; text-align: right;">
						'.$next_link.'
					</td>
				</tr>
			</table>';
		
		$output .= '
			<div id="picture-holder">
				<a accesskey="v" href="'.$config["baseurl"].'images/'.$row["path"].'">'.$imgtag.'</a>
			</div>';
		
		$output .= '
			<table style="width: 100%;">
				<tr>
					<td style="width: 100%; text-align: center;"><div id="exif_toggle">'.$detail_url.'</div></td>
				</tr>
			</table>
		</div>';
		
		
		$output .= generate_exif_table($row["id"]);
		
		// display comments for selected picture
		$output .= display_comments($row["id"]);
			
		
		$output .= '</div>
					</div>';
		
	}
	else{
		// so basicly, this whole block gets to run only if I'm not showing a picture
		$output .= '<div id="thumbnail_container">';
		
		if ($level == "slideshow") {
		
			$output .= '<div id="inner_wrapper">
							<div id="big-picture-container">';
							
			$output .= generate_slideshow_interface();
			$output .= '</div></div>';

			$num_items = 1;
		}
		
		elseif ($level == "search"){
			$output .= '<input type="hidden" name="dl_type" value="pictures" />';
			
			$terms = $_REQUEST["searchterms"];
			$terms = explode(" ",$terms);
			
			$query = "SELECT `caption`,`path`,p.`id`,c.`comment` FROM `".$TABLE_PREFIX."pictures` p LEFT JOIN `".$TABLE_PREFIX."comments` c
					ON p.`id` = c.`parent_id` ";
			if ((count($terms) != 1) || ($terms[0] != '')){
				$query .= " WHERE ( ";
				foreach ($terms as $term) {
					$query .= " 
						`path` LIKE '%".mysql_escape_string($term)."%' OR 
						`comment` LIKE '%".mysql_escape_string($term)."%' OR 
						`caption` LIKE '%".mysql_escape_string($term)."%' OR ";
				}
				
				$query = substr($query, 0, strlen($query) - 3) .") ";
			}
			else{
				// no search terms? no results either
				$query .= " WHERE 1 = 0";
			}
			
			$query .= " GROUP BY p.`id`ORDER BY `date_submitted` DESC";
			$result = run_query($query);

			if (mysql_num_rows($result) > 0){
				$output .= '<ul class="slides">';
				$counter = 0;
			
				$from = ($page - 1) * $config["thumb_num"];
				
				mysql_data_seek($result, $from);
				
				$i = $page;
				
				// Loop through each album in the set.
				while(($row = mysql_fetch_assoc($result)) && ($i++ < ($page + $config["thumb_num"]))){
					// display thumbnails within album
					$thumbpath = generate_thumb($row['path'], $row['id'], 'small');
					
					// generate XHTML with thumbnail and link to picture view.
					$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.htmlspecialchars($row["caption"]).'" alt="'.htmlspecialchars($row["caption"]).'" />';
					
					// Tables are a necessary evil for getting the interface done...
					
					$comment_query = "SELECT COUNT(*) AS `num_comments` FROM `".$TABLE_PREFIX."comments` WHERE `parent_id`='".$row["id"]."'";
					$comment_result = run_query($comment_query);
					$num_comments = mysql_result($comment_result, 0, 'num_comments');

					
					$output .= '
						<li class="thumbnail"><div class="tag">
							<a href="'.generate_url("picture",$row["id"]).'">'.$imgtag.'</a><br />';
							
							
							if ($config["allow_dl"])
								 $output .= '<input type="checkbox" name="checked[]" value="'.$row["id"].'" />'; 
							
							$filename = basename($row["path"]);
							if (strlen($filename) > $config["truncate"] && $config["truncate"] != 0)
								$filename = substr($filename, 0, $config["truncate"])."...";
						
							$output .= $filename.'<br />';
							
							if ($config["allow_comments"]) {
  							$output.= '<div class="meta-header">('.$num_comments.' comment';
  					
  							if ($num_comments != 1) $output .= 's';
  							$output .= ')</div>';
							
							}
							
							$output.='</div></li>';
					
					$counter++;
					
				}
				
				$output .= '</ul>';
			}
			else{
				$output .= '<div style="text-align: center; padding: 15px 0px 15px 0px;">There are no pictures that matched your search.</div>';
			}
			
			$num_items = mysql_num_rows($result);
		}
		elseif ($level == "album") {
			// Album level display mode (display all pictures within album)
			$output .= '<input type="hidden" name="dl_type" value="pictures" />';
			$count_sql = "SELECT COUNT(*) AS num_items FROM `".$TABLE_PREFIX."pictures` WHERE parent_album = '$id'";
			$result = mysql_query($count_sql);
			$num_items = mysql_result($result, 'num_items');
			$num = $num_items;

			$sql = "SELECT `".$TABLE_PREFIX."pictures`.`id`,`path`,`caption`, COUNT(`comment`) AS `num_comments` FROM `".$TABLE_PREFIX."pictures` LEFT JOIN `".$TABLE_PREFIX."comments` ON `".$TABLE_PREFIX."pictures`.`id`=`".$TABLE_PREFIX."comments`.`parent_id`  WHERE `".$TABLE_PREFIX."pictures`.`parent_album` = ".$id." GROUP BY `".$TABLE_PREFIX."pictures`.`id`";
			
			// query database and retreive all pictures withing selected album
			switch ($_SESSION["plogger_sortby"]){
				case 'number_of_comments':
					$sql .= " ORDER BY `num_comments`";
					break;
				case 'caption':
					$sql .= " ORDER BY `caption` ";
					break;
				case 'date_taken':
					$sql .= " ORDER BY `EXIF_date_taken` ";
					break;
				case 'filename':
					$sql .= " ORDER BY `path` ";
					break;
				case 'date':
				default:
					$sql .= " ORDER BY `date_submitted` ";
					break;
			}
			
			switch ($_SESSION["plogger_sortdir"]){
				case 'ASC':
					$sql .= " ASC";
					break;
				case 'DESC':
				case 'default':
					$sql .= " DESC";
					break;
			}
			
			$from = ($page - 1) * $config["thumb_num"];
			
			$sql .= ",`".$TABLE_PREFIX."pictures`.`id` DESC ";
			$sql .= " LIMIT ".$from.",".$config["thumb_num"];
			$result = run_query($sql);

			if (mysql_num_rows($result) > 0){
				$output .= '<ul class="slides">';
				$counter = 0;
				
				// Loop through each album in the set.
				while($row = mysql_fetch_assoc($result)) {
					// display thumbnails within album
					$thumbpath = generate_thumb($row['path'], $row['id'], 'small');
					
					// generate XHTML with thumbnail and link to picture view.
					$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.htmlspecialchars($row["caption"]).'" alt="'.htmlspecialchars($row["caption"]).'" />';
					
					$num_comments = $row['num_comments'];
				
					$output .= '<li class="thumbnail"><div class="tag"><a href="' . generate_url("picture",$row["id"]) . '">' . $imgtag . "</a><br />";
							
							
					if ($config["allow_dl"])
						 $output .= '<input type="checkbox" name="checked[]" value="'.$row["id"].'" />'; 
							
					$filename = basename($row["path"]);
						
					if (strlen($filename) > $config["truncate"] && $config["truncate"] != 0)
						$filename = substr($filename, 0, $config["truncate"])."...";
								
					$output .= $filename.'<br />';
							
					if ($config["allow_comments"]) {
  						$output.= '<div class="meta-header">('.$num_comments.' comment';
  					
  						if ($num_comments != 1) $output .= 's';
  							$output .= ')</div>';
							
					}
							
					$output.='</div></li>';
					$counter++;
				}
				
				
				$output .= '</ul>';
			}
			else{
				$output .= '<div style="text-align: center; padding: 15px 0px 15px 0px;">There are no pictures in this album.</div>';
			}
		}
		else if ($level == "collection") {
			$sql = "SELECT COUNT(DISTINCT `parent_album`) AS `num_items` FROM `".$TABLE_PREFIX."pictures` WHERE `parent_collection` = '" . $id . "'";
			$result = run_query($sql);
			$num_items = mysql_result($result, 'num_items');
			$num = $num_items;

			// create a list of all non-empty albums. Could be done with subqueries, but
			// MySQL 4.0 does not support those

			// -1 is just for the case there are no albums at all. Shouldn't happen if user
			// follows links, but let's deal with it anyway
			$image_count = array(-1 => -1);
			// 1. create a list of all albums with at least one photo
			$sql = "SELECT parent_album,COUNT(*) AS imagecount FROM `".$TABLE_PREFIX."pictures` GROUP BY parent_album";
			$result = run_query($sql);
			while($row = mysql_fetch_assoc($result)) {
				$image_count[$row["parent_album"]] = $row["imagecount"];
			};
				
			$output .= '<input type="hidden" name="dl_type" value="collections" />';
			$imlist = join(",",array_keys($image_count));
			
			$from = ($page - 1) * $config["thumb_num"];
			
			$sql = "SELECT * FROM `".$TABLE_PREFIX."albums` WHERE `parent_id` = '$id' AND id IN ($imlist) ORDER BY `name` DESC LIMIT ".$from.",".$config["thumb_num"];
			$result = run_query($sql);
			
			$output .= '<input type="hidden" name="dl_type" value="albums" />';

			if (mysql_num_rows($result) > 0){
				$output .= '<ul class="slides">';
				$counter = 0;
				
				while ($row = mysql_fetch_array($result)){
					// Display a thumbnail of the first picture in the album
					
					if ($row["thumbnail_id"]) {
						$thumb_query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE id='".$row["thumbnail_id"] . "'";
					} else {
						$thumb_query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE `parent_album`='".$row["id"]."' ORDER BY `date_submitted` DESC LIMIT 1";
					};
					$thumb_result = run_query($thumb_query);
					$thumb_row = mysql_fetch_assoc($thumb_result);
				
					
					if (mysql_num_rows($thumb_result) > 0){
						$thumbpath = generate_thumb($thumb_row['path'], $thumb_row['id'], 'small');
					
						$numquery = "SELECT COUNT(*) AS `num_pictures` FROM `".$TABLE_PREFIX."pictures` WHERE `parent_album`='".$row["id"]."'";
						$numresult = run_query($numquery);
						$num_pictures = mysql_result($numresult, 'num_pictures');

						
						$output .= '<li class="thumbnail"><div class="tag"><a href="' . generate_url("album",$row["id"],$row["name"]) . '">';
						
						// generate XHTML with thumbnail and link to picture view.
						$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.htmlspecialchars($row["description"]).'" alt="'.htmlspecialchars($row["description"]).'" />';
						
						$output .= $imgtag;
						
						$output .= '</a>
							<br />';
							
							if ($config["allow_dl"])
								 $output .= '<input type="checkbox" name="checked[]" value="'.$row["id"].'" />';
							
							$output.= $row["name"].'
							<br /><div class="meta-header">('.$num_pictures.' picture';
						
						if ($num_pictures != 1) $output .= 's';
						
						$output .= ')</div></div>
								</li>';
							

						
						$counter++;
					}
				}
	
				
				$output .= '</ul>';
			}
		} // collections mode (show all albums within a collection)
		else {
			// Show all of the collections	
			// How many non-empty collections are there?

			$sql = "SELECT COUNT(DISTINCT `parent_collection`) AS `num_items` FROM `".$TABLE_PREFIX."pictures`";
			$result = run_query($sql);
			$num_items = mysql_result($result, 'num_items');
			$num = $num_items;
        
			// create a list of all non-empty collections. Could be done with subqueries, but
			// MySQL 4.0 does not support those

			// -1 is just for the case there are no collections at all
			$image_count = array(-1 => -1);
			// 1. create a list of all albums with at least one photo
			$sql = "SELECT parent_collection,COUNT(*) AS imagecount FROM `".$TABLE_PREFIX."pictures` GROUP BY parent_collection";
			$result = run_query($sql);
			while($row = mysql_fetch_assoc($result)) {
				$image_count[$row["parent_collection"]] = $row["imagecount"];
			};
				
			$output .= '<input type="hidden" name="dl_type" value="collections" />';
			$imlist = join(",",array_keys($image_count));

			// I need to determine correct arguments for LIMIT from the given page number

			$from = ($page - 1) * $config["thumb_num"];

			$sql = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE id IN ($imlist) LIMIT ".$from.",".$config["thumb_num"];
			$result = run_query($sql);
			
			if (mysql_num_rows($result) > 0){
				$output .= '<ul class="slides">';
				$counter = 0;
				
				while ($row = mysql_fetch_array($result)){
					$numquery = "SELECT COUNT(*) AS `num_albums` FROM `".$TABLE_PREFIX."albums` WHERE `parent_id`='".$row["id"]."'";
					$numresult = run_query($numquery);
					$num_albums = mysql_result($numresult, 'num_albums');
					
					if ($row["thumbnail_id"]) {
						$thumb_query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE id='".$row["thumbnail_id"]."'";
					} else {
						$thumb_query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE `parent_collection`='".$row["id"]."' LIMIT 1";
					};
					$thumb_result = run_query($thumb_query);
					
					if (mysql_num_rows($thumb_result) > 0){
						$thumb_row = mysql_fetch_assoc($thumb_result);
						
						$output .= '<li class="thumbnail"><div class="tag"><a href="' . generate_url("collection",$row["id"],$row["name"]) . '">';
						
						$thumbpath = generate_thumb($thumb_row['path'], $thumb_row['id'], 'small');
						
						// generate XHTML with thumbnail and link to picture view.
						$imgtag = '<img class="photos" src="'.$thumbpath.'" title="'.htmlspecialchars($row["description"]).'" alt="'.htmlspecialchars($row["description"]).'" />';
						
						$output .= $imgtag;
						
						$output .= '</a><br/>';
							
							
							if ($config["allow_dl"])
								 $output .= '<input type="checkbox" name="checked[]" value="'.$row["id"].'" />';
							
							$output .= $row["name"].'
							<br /><div class="meta-header">('.$num_albums.' album';
						
						if ($num_albums != 1) $output .= 's';
						
						$output .= ')</div></div>
							</li>';
						
						$counter++;
					}
				}
				
				$output .= '</ul>';
			}
		}
		
		// change the tooltip message to reflect the nature of the RSS aggregate link.
		if ($level != "") 
				$rss_tooltip = "RSS 2.0 subscribe to this $level";
		else
				$rss_tooltip = "RSS 2.0 subscribe to all images";
 

		if ($config["use_mod_rewrite"]) {
			global $path;
			if (isset($path)) 
				 $rss_link .= "http://".$_SERVER["SERVER_NAME"]."/".SmartStripSlashes(substr($path,1))."/feed/";
			else
					$rss_link .= $config['baseurl']."feed/";
					
		} else {
			$rss_link .= "plog-rss.php?level=$level&id=$id";
		};

		if ($level == "search") { // append the search terms
			$separator = $config["use_mod_rewrite"] ? "?" : "&";
			$rss_link .= $separator . "searchterms=".$_GET["searchterms"];
		};

		if ($level == "search") {
			$searchterms = urlencode($_GET["searchterms"]);
			$p_url = $_SERVER["PHP_SELF"]."?level=search&amp;searchterms=$searchterms&amp;id=$id";
		} 
		else {
			if ($level) {
				$p_url = generate_url($level,$id);
				if ($config["use_mod_rewrite"]) {
					$p_url .= "/";
				};
			}
			else {
				$p_url = $config["baseurl"];
			};
		};

		$ss_tag = $ss_cap_tag = $rss_tag = "";

		if ($level == "album") {
			$ss_url = $_SERVER["PHP_SELF"]."?level=slideshow&amp;mode=$level&amp;id=$id";
			if ($config["use_mod_rewrite"]) {
				$ss_url = generate_url($level,$id) . "/slideshow";
			};
			$ss_tag = "<td><a href=\"$ss_url\">View as Slideshow</a></td>";
		}
		
		if ($level != "slideshow")
			$rss_tag = '<a href="'.$rss_link.'"><img src="' . $config["baseurl"] . 'graphics/rss.gif" title="'.$rss_tooltip.'" alt="RSS 							2.0 Feed" style="margin:0; padding:0; display:inline" /></a>';
		else
			$ss_cap_tag = '<td><span id="picture_caption">&nbsp;</span></td>';
		
		
		$output .= '
					</div>
					<div id="pagination">
						<table style="width: 100%;">
							<tr>'.$ss_tag.$ss_cap_tag.'
								<td>'.generate_pagination($p_url, $page, $num_items, $config["thumb_num"]).'</td>
								<td style="text-align: right; white-space: nowrap;">'.generate_sortby($level,$id).generate_sortdir($level,$id).'</td> 
								<td style="text-align: right; white-space: nowrap;">'.$rss_tag.'</td>
							</tr>
						</table>
					</div>';
					
					$end = microtime();
					$t2 = (getmicrotime($end) - getmicrotime($start));

					$output .= '<p id="link-back"><a href="http://www.plogger.org/">Powered by Plogger!</a></p>
				</div>
			</form>';
	}
	
	$output .= '
			</div>
		';
	
	echo $output;
}

function generate_breadcrumb($level, $id){
	global $TABLE_PREFIX;
	switch ($level){
		
		case 'collection':
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$breadcrumbs = ' <a accesskey="/" href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; <b>' . $row["name"] . '</b>';
			if ($level == "slideshow") $breadcrumbs .= ' &raquo; Slideshow';
			
			break;
		case 'slideshow':
		case 'album':
			$query = "SELECT * FROM `".$TABLE_PREFIX."albums` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$album_name = $row["name"];
			$album_link = generate_url("album",$row["id"],$row["name"]);
						
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$row["parent_id"]."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			$collection_link = '<a accesskey="/" href="' . generate_url("collection",$row["id"],$row["name"]) . '">' . $row["name"] . '</a>';

			if ($level == "slideshow") 
				$breadcrumbs = ' <a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . $collection_link . ' &raquo; ' 
				. '<a href="'.$album_link.'">'.$album_name.'</a> &raquo; ' . ' <b>Slideshow</b>';
			else
				$breadcrumbs = ' <a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . $collection_link . ' 						&raquo; ' . '<b>'.$album_name.'</b>';
			
			break;
		
		case 'picture':
			$query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE `id`='".$id."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			$picture_name = basename($row["path"]);
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."albums` WHERE `id`='".$row["parent_album"]."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			//$album_link = '<a href="'.$_SERVER["PHP_SELF"].'?level=album&amp;id='.$row["id"].'">'.$row["name"].'</a>';
			$album_link = '<a accesskey="/" href="' . generate_url("album",$row["id"],$row["name"]) . '">' . $row["name"] . '</a>';
			
			$query = "SELECT * FROM `".$TABLE_PREFIX."collections` WHERE `id`='".$row["parent_id"]."'";
			$result = run_query($query);
			$row = mysql_fetch_assoc($result);
			
			//$collection_link = '<a href="'.$_SERVER["PHP_SELF"].'?level=collection&amp;id='.$row["id"].'">'.$row["name"].'</a>';
			$collection_link = '<a href="' . generate_url("collection",$row["id"],$row["name"]) . '">' . $row["name"] . '</a>';
			
			$breadcrumbs = ' <a href="'.$_SERVER["PHP_SELF"].'">Collections</a> &raquo; ' . $collection_link . ' &raquo; ' . $album_link . ' &raquo; ' . '<span id="image_name"><b>'.$picture_name.'</b></span>';
			if ($level == "slideshow") $breadcrumbs .= ' &raquo; Slideshow';
			
			break;
		case 'search':
			$breadcrumbs = 'You searched for <b>'.htmlspecialchars($_GET["searchterms"]).'</b>.';
			break;
		default:
			$breadcrumbs = ' <b>Collections</b>';
	}
	
	return '<div id="breadcrumb_links">'.$breadcrumbs.'</div>';
}

function generate_jump_menu() {
	global $TABLE_PREFIX;
    	global $config;

	$output = '';

	$output .=  '<form name="jump_menu" action="#" method="get">';
	$output .=  '<select name="jump_menu" onchange="document.location.href = this.options[this.selectedIndex].value;">
		<option value="#">Jump to...</option>';

        $image_count = array();
	// 1. create a list of all albums with at least one photo
	$sql = "SELECT parent_album,COUNT(*) AS imagecount FROM `".$TABLE_PREFIX."pictures` GROUP BY parent_album";
	$result = run_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$image_count[$row["parent_album"]] = $row["imagecount"];
	};

	// 2. get a list of all albums and collections
	$sqlCollection = "SELECT `".$TABLE_PREFIX."albums`.id AS album_id,
				 `".$TABLE_PREFIX."albums`.name AS album_name,
				 `".$TABLE_PREFIX."collections`.id AS collection_id,
				 `".$TABLE_PREFIX."collections`.name AS collection_name
				 FROM `".$TABLE_PREFIX."albums`
			 LEFT JOIN `".$TABLE_PREFIX."collections` ON `".$TABLE_PREFIX."albums`.parent_id = `".$TABLE_PREFIX."collections`.id
			 ORDER BY `".$TABLE_PREFIX."collections`.name ASC, `".$TABLE_PREFIX."albums`.name ASC";

		$result = run_query($sqlCollection);
		$last_collection = "";
		while ($row = mysql_fetch_assoc($result)){
			// skip albums with no images
			if (empty($image_count[$row["album_id"]])) {
				continue;
			};

			if ($row["collection_id"] != $last_collection) {
				//$output .= '<option value="'.$_SERVER["PHP_SELF"].'?level=collection&amp;id='.$row["collection_id"].'">'.$row["collection_name"].'</option>';
				$output .= '<option value="'.generate_url("collection",$row["collection_id"],$row["collection_name"]).'">'.$row["collection_name"].'</option>';
				$last_collection = $row["collection_id"];
			};

			//$output .=  '<option value="'.$_SERVER["PHP_SELF"].'?level=album&amp;id='.$row["album_id"].'">'.$row["collection_name"].' : '.$row["album_name"];
			$output .=  '<option value="'.generate_url("album",$row["album_id"],$row["album_name"]).'">'.$row["collection_name"].' : '.$row["album_name"];
			$output .=  '</option>';
		}


	$output .=  '</select>';
	$output .=  '</form>';
	return $output;

}

function generate_exif_table($id, $condensed=0){
	global $TABLE_PREFIX;
	global $config;
	
	$query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE `id`='".$id."'";
	$result = run_query($query);
	
	if (mysql_num_rows($result) > 0){
		$row = mysql_fetch_assoc($result);
		foreach($row as $key => $val) if (trim($row[$key]) == '') $row[$key] = '&nbsp;';
		
		$table_data = '<div id="exif_table"><table id="exif_data"';
		
		if (!$_SESSION["plogger_details"]){
			$table_data .= ' style="display: none;"';
		}
		
		// get image size
		$img = $config['basedir'] . 'images/' . $row['path'];
		list($width, $height, $type, $attr) = getimagesize($img);
		$size = round(filesize($img)/1024,2);
		
		if (!$condensed) {
			$table_data .= '>
					<tr>
						<td><strong>Dimensions</strong></td>
						<td>'.$width .' x ' .$height.'</td>
					</tr>
					<tr>
						<td><strong>File size</strong></td>
						<td>'.$size.' kbytes</td>
					</tr>
					<tr>
						<td><strong>Taken on</strong></td>
						<td>'.$row["EXIF_date_taken"].'</td>
					</tr>
					<tr>
						<td><strong>Camera model</strong></td>
						<td>'.$row["EXIF_camera"].'</td>
					</tr>
					<tr>
						<td><strong>Shutter speed</strong></td>
						<td>'.$row["EXIF_shutterspeed"].'</td>
					</tr>
					<tr>
						<td><strong>Focal length</strong></td>
						<td>'.$row["EXIF_focallength"].'</td>
					</tr>
					<tr>
						<td><strong>Aperture</strong></td>
						<td>'.$row["EXIF_aperture"].'</td>
					</tr>
				</table></div>';
		}
		else {
			$table_data .= '><tr><td><strong>Dimensions</strong></td><td>'.$width .' x ' .$height.'</td></tr><tr><td><strong>File size</strong></td><td>'.$size.' kbytes</td></tr><tr><td><strong>Taken on</strong></td><td>'.$row["EXIF_date_taken"].'</td></tr><tr><td><strong>Camera model</strong></td><td>'.$row["EXIF_camera"].'</td></tr><tr><td><strong>Shutter speed</strong></td><td>'.$row["EXIF_shutterspeed"].'</td></tr><tr><td><strong>Focal length</strong></td><td>'.$row["EXIF_focallength"].'</td></tr><tr><td><strong>Aperture</strong></td><td>'.$row["EXIF_aperture"].'</td></tr></table></div>';
		}
	}		
	
	return $table_data;
}

function display_comments($id) {
	global $TABLE_PREFIX;
	global $config;
	$output = "";
	$error_message = "";
	
	
	if ($config["allow_comments"] == 1) {
    	// this function takes the photo id and selects all relevent comments
      	// it then displays them with an ordered HTML list.
     	// get comments from table
      $query = "SELECT *, UNIX_TIMESTAMP(`date`) AS `unix_date` FROM `".$TABLE_PREFIX."comments` WHERE parent_id = '$id'";
      $result = run_query($query) or die(mysql_error());
      
      $output .= "<a name=\"comments\"></a><h2 class=\"comment-heading\">Comments:</h2>";
      
      if (mysql_num_rows($result) == 0) {
    		 $output .= "<p>No comments yet.</p>";
      }
      else {
      	$output .= "<ol class=\"comments\">";
      	while($row = mysql_fetch_assoc($result)) {
			$url = htmlspecialchars($row["url"]);
			$author = htmlspecialchars($row["author"]);
			
        		$output .= "<li>";
        		
        		$output .= "<p>".htmlspecialchars($row["comment"])."</p>";
        		$output .= "<cite>Comment by ";
                $output .= (trim($url) != '') ? "<a href=\"$url\">$author</a>" : "$author";
                $output .= "- posted on ".date($config["date_format"],$row["unix_date"]);
        		
        		$output .= "</cite></li>";
        }
    		$output .= "</ol>";
    	}
    	
    	
    	$query = "SELECT * FROM `".$TABLE_PREFIX."pictures` WHERE id = '$id'";
      	$result = run_query($query) or die(mysql_error());
      	$row = mysql_fetch_assoc($result);
      
    	if ($row["allow_comments"]) {
    		
		if (isset($_SESSION['comment_post_error'])) {
			$error_message = "<p class='errors'>" . $_SESSION['comment_post_error'] . "</p>";
			unset($_SESSION['comment_post_error']);
		};
    		
    		$output .= '
    			<a name="comment-post"></a><h2 class="comment-heading">Post a comment:</h2>
    			'.$error_message.'
    			<form action="' . $config["baseurl"] . 'plog-comment.php" method="post" id="commentform">
    			<p>
    				<input type="text" name="author" id="author" class="textarea" value="" size="28" tabindex="1" />
    				<label for="author">Name</label> (required)	<input type="hidden" name="comment_post_ID" value="40" />
    				<input type="hidden" name="parent" value="'.$row['id'].'" />
    			</p>
    			<p>
    				<input type="text" name="email" id="email" value="" size="28" tabindex="2" />
    				<label for="email">E-mail</label> (required, but not publicly displayed)
    			</p>
    			<p>
    				<input type="text" name="url" id="url" value="" size="28" tabindex="3" />
    				<label for="url">Your Website (optional)</label>
    			</p>
    			<p>
    				<label for="comment">Your Comment</label>
    				<br /><textarea name="comment" id="comment" cols="70" rows="4" tabindex="4"></textarea>
    			</p>
    			<p>
    				<input class="submit" name="submit" type="submit" tabindex="5" value="Post Comment!" />
    			</p>
    			</form>';
    	}
    	else
    		$output .= '<p class="comments-closed">Comments for this entry are closed</p>';
    }
	
	return $output;

}


// generate header produces the Gallery Name, The Jump Menu, and the Breadcrumb trail at the top of the image

function generate_header() {
	global $config;
	
	$output = '<h1 id="gallery-name">'.stripslashes($config["gallery_name"]).'</h1>';
	
	return $output;
}


function generate_sortby($level,$id){
	$output = '';
	
	if ($level == "album"){
		
		$output .= '
			<form action="#" method="get">
				Sort by:
				<select id="change_sortby" name="change_sortby" 
				onchange="document.location.href = \''.$_SERVER["PHP_SELF"].
				'?sortby=\'+this.options[this.selectedIndex].value+\'&level='.$level.'&amp;id='.$id.'&amp;n=0\';">
				
				<option value="date"';
				if($_SESSION["plogger_sortby"] == 'date') $output .= ' selected="selected"';
				$output .= '>Date Submitted</option><option value="date_taken"';
				
				if($_SESSION["plogger_sortby"] == 'date_taken') $output .= ' selected="selected"';
				$output .= '>Date Taken</option>
				
				<option value="caption"';
				if($_SESSION["plogger_sortby"] == 'caption') $output .= ' selected="selected"';
				$output .= '>Caption</option>
				
				<option value="filename"';
				if($_SESSION["plogger_sortby"] == 'filename') $output .= ' selected="selected"';
				$output .= '>Filename</option>
				
				<option value="number_of_comments"';
				if($_SESSION["plogger_sortby"] == 'number_of_comments') $output .= ' selected="selected"';
				$output .= '>Number of Comments</option>
				
				</select>
			</form>';
	}
	
	return $output;
}

function generate_sortdir($level,$id){
	$output = '';

	if ($level == "album") {
		
		$output .= '
			<form action="#" method="get">
				<select id="change_sortdir" name="change_sortdir" 
				onchange="document.location.href = \''.$_SERVER["PHP_SELF"].
				'?sortdir=\'+this.options[this.selectedIndex].value+\'&level='.$level.'&amp;id='.$id.'&amp;n=0\';">
				
				<option value="ASC"';
				
				if($_SESSION["plogger_sortdir"] == 'ASC') $output .= ' selected="selected"';
				
				$output .= '>Ascending</option><option value="DESC"';
				
				if($_SESSION["plogger_sortdir"] == 'DESC') $output .= ' selected="selected"';
				
				$output .= '>Descending</option>
				</select>
			</form>';		
	}
	
	return $output;
}

function generate_search_box(){
	$output = '
		<form action="'.$_SERVER["PHP_SELF"].'" method="get">
			<input type="hidden" name="level" value="search" />
			<input type="text" name="searchterms" />
			<input class="submit" type="submit" value="Search" />
		</form>';
	return $output;
}

// generates correclt urls depending on whether mod_rewrite is in use or not
// level - type of the url to generate
// id - used for id=xx urls
// name - used for mod_rewrite URL-s, both should be passed as arguments, so this
// function doesn't have to query the database


// benchmark timing
function getmicrotime($t) {
	list($usec, $sec) = explode(" ",$t);
	return ((float)$usec + (float)$sec);
}

function generate_slideshow_interface() {
	global $config;
	$large_link = '<a accesskey="v" href="javascript:slides.hotlink()" title="View Large Image"><img hspace="1" src="'.$config	["baseurl"].'graphics/search.gif" width="16" height="16"></a>';
			
	$prev_url = '<a accesskey="," title="Previous Image" 
	href="javascript: slides.previous();"><img hspace="1" src="'.$config["baseurl"].'graphics/rewind.gif" 
	width="16" height="16"></a>';
		
	$next_url = '<a accesskey="." title="Next Image" 
	href="javascript: slides.next();"><img hspace="1" src="'.$config["baseurl"].'graphics/fforward.gif" 
	width="16" height="16"></a>';
		
	$stop_url = '<a accesskey="x" title="Stop Slideshow" 
	href="javascript: slides.pause();"><img hspace="1" src="'.$config["baseurl"].'graphics/stop.gif" 
	width="16" height="16"></a>';
	
	$play_url = '<a accesskey="s" title="Start Slideshow" 
	href="javascript: slides.play();"><img hspace="1" src="'.$config["baseurl"].'graphics/play.gif" 
	width="16" height="16"></a>';
	
	$output = '<div class="large-thumb-toolbar" style="width:'.$config["max_display_size"].'">
			   '.$large_link.$prev_url.$stop_url.$play_url.$next_url.'</div>';
	/*			   
	$imgtag = '<img name="slideshow_image" class="photos-large" src="'.$thumburl.'" 
			  title="'.$row["caption"].'" alt="'.$row["caption"].'" />';
	*/
	$imgtag = '<img name="slideshow_image" class="photos-large" src="about:blank" 
			  title="" alt="" />';
			  
	$output .= '<div id="picture-holder"><a href="javascript:slides.hotlink()">'.$imgtag.'</a></div><br>';
	
	// activate slideshow object using javascript block
	$output .=	'<SCRIPT TYPE="text/javascript">
				<!--
				if (document.images)
				{
				  slides.set_image(document.images.slideshow_image);
				  slides.textid = "picture_caption"; // optional
				  slides.imagenameid = "image_name"; // optional
				  slides.update();
				  slides.play();
				}
				//-->
				</SCRIPT>';
	
	return $output;
}

// function for generating the slideshow javascript
function generate_slideshow_js($id, $mode) {
	global $TABLE_PREFIX;
	global $config;
	
	// output the link to the slideshow javascript
	$output = '<script type="text/javascript" src="'.$config['baseurl'].'slideshow.js"></script>';
	
	
	// get all pictures within album sorted by default sort order
	if ($mode == "collection")
		$sql = "SELECT * FROM ".$TABLE_PREFIX."pictures WHERE parent_collection = '".$id."'";
	elseif ($mode == "album")
		$sql = "SELECT * FROM ".$TABLE_PREFIX."pictures WHERE parent_album = '".$id."'";
	else
		$sql = "SELECT * FROM ".$TABLE_PREFIX."pictures";

	// determine sort ordering
	switch ($_SESSION["plogger_sortby"]){
		case 'number_of_comments':
			$sql .= " ORDER BY `num_comments`";
			break;
		case 'caption':
			$sql .= " ORDER BY `caption` ";
			break;
		case 'date_taken':
			$sql .= " ORDER BY `EXIF_date_taken` ";
			break;
		case 'filename':
			$sql .= " ORDER BY `path` ";
			break;
		case 'date':
		default:
			$sql .= " ORDER BY `date_submitted` ";
			break;
	}
	
	switch ($_SESSION["plogger_sortdir"]){
		case 'ASC':
			$sql .= " ASC";
			break;
		case 'DESC':
		case 'default':
			$sql .= " DESC";
			break;
	}
					
	$result = run_query($sql);
	
	$output .= '<script type="text/javascript"> slides = new slideshow("slides");';
	
	$output .= 'slides.prefetch = 2; 
				slides.timeout = 4000; ';
				
	while($row = mysql_fetch_assoc($result)) {
		// output a line of javascript for each image
		$output .=	's = new slide();
					s.src = "'.generate_thumb($row["path"], $row["id"], 'large').'";
					s.link = "'.$config["baseurl"] . $row["path"].'";
					s.text = "'.$row['caption'].'";
					s.image_name = "'.basename($row['path']).'";
					slides.add_slide(s);';
	}
	
	$output .= '// --> </script>';
	
	return $output;
}
 

?>
