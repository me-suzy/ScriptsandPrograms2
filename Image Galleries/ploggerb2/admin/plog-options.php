<?php
require("plog-globals.php");
require_once("../plog-load_config.php");
require_once("plog-admin-functions.php");

$output = '';
if (isset($_POST["submit"])){
	
	if (isset($_POST["allow_dl"])) $allow_dl = 1; else $allow_dl = 0;
	if (isset($_POST["allow_comments"])) $allow_comments = 1; else $allow_comments = 0;
	if (isset($_POST["allow_print"])) $allow_print = 1; else $allow_print = 0;
	if (isset($_POST["square_thumbs"])) $square_thumbs = 1; else $square_thumbs = 0;
	
	$query = "UPDATE `".$TABLE_PREFIX."config` SET 
		`truncate`='".intval($_POST["truncate"])."',
		`feed_title`='".mysql_escape_string($_POST["feed_title"])."',
		`rss_thumbsize`='".intval($_POST["rss_thumbsize"])."',
		`feed_language`='".mysql_escape_string($_POST["feed_language"])."',
		`feed_num_entries`='".intval($_POST["feed_num_entries"])."',
		`allow_dl`='".intval($allow_dl)."',
		`allow_comments`='".intval($allow_comments)."',
		`allow_print`='".intval($allow_print)."',
		`max_thumbnail_size`='".intval($_POST["max_thumbnail_size"])."',
		`max_display_size`='".intval($_POST["max_display_size"])."',
		`default_sortby`='".mysql_escape_string($_POST["default_sortby"])."',
		`default_sortdir`='".mysql_escape_string($_POST["default_sortdir"])."',
		`thumb_num`='".intval($_POST["thumb_num"])."',
		`compression`='".intval($_POST["image_quality"])."',
		`admin_username`='".mysql_escape_string($_POST["admin_username"])."',
		`admin_email`='".mysql_escape_string($_POST["admin_email"])."',
		`date_format`='".mysql_escape_string($_POST["date_format"])."',
		`use_mod_rewrite`='".intval($_POST["use_mod_rewrite"])."',
		`square_thumbs`='".intval($square_thumbs)."',
		`comments_notify`='".intval($_POST["comments_notify"])."',
		`gallery_name`='".mysql_escape_string($_POST["gallery_name"])."'";
		
	if (trim($_POST["admin_password"]) != ''){
		if (trim($_POST["admin_password"]) == trim($_POST["confirm_admin_password"])){
			$query .= ", `admin_password`='".md5(mysql_real_escape_string(trim($_POST["admin_password"])))."'";
		}
		else{
			$error_flag = true;
			$output .= '<p class="errors">The passwords you entered did not match.</p>';
			$output .= '<p class="actions">Other changes have been applied successfully.</p>';
		}
	}

	run_query($query);

	// and read the configuration back again
	$config["use_mod_rewrite"] = intval($_POST["use_mod_rewrite"]);
	configure_mod_rewrite($config["use_mod_rewrite"]);
	
	// refresh config array with new variables
	$query = "SELECT * FROM ".$TABLE_PREFIX."config";
	$result = mysql_query($query) or die("Could not run query.");

	// array_merge - latter values will overwrite previous ones. 
	// $config contains values that are not in the table, we have to keep them
	$config = array_merge($config,mysql_fetch_assoc($result));
	
	if (!isset($error_flag)) $output .= '<p class="actions">You have updated your settings successfully.</p>';
	

}


$date_formats = array(
	"n.j.Y",
	"j.n.Y",
	"F j, Y",
	"m.d.y",
	"Ymd",
	"j-m-y",
	"D M j Y");
    
$output .= '
	<h1>System Options</h1>
	<form action="'.$_SERVER["PHP_SELF"].'" method="post">
    	<div id="options_section">
	        <table style="width:550px;text-align:right;font:georgia">
	            <tr>
	                <td><b>Gallery Name</b> (optional):</td>
	                <td>
	             
	                    <input type="text" name="gallery_name" value="'.stripslashes($config['gallery_name']).'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Administrator Username:</b></td>
	                <td>
	                    <input type="text" name="admin_username" value="'.$config['admin_username'].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Administrator E-mail address:</b></td>
	                <td>
	                    <input type="text" name="admin_email" value="'.$config['admin_email'].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Send E-mail Notification for Comments?</b><br> (requires valid e-mail address)</td>
	                <td>';
	             				
						if ($config['comments_notify'] == 1) $checked = "CHECKED"; else $checked = "";
	             				
	                		$output .=
	                    '<input type="checkbox" name="comments_notify" value="1" '.$checked.'/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>New Administrator Password:</b></td>
	                <td>
	                    <input type="password" name="admin_password"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Confirm New Administrator Password:</b></td>
	                <td>
	                    <input type="password" name="confirm_admin_password"/>
	                </td>
	            </tr>
	            </table>
	            <h1>Thumbnail Options</h1>
	            <table style="width:550px;text-align:right;font:georgia">';
	            
	            if ($config['square_thumbs']) $dim = "Width"; else $dim = "Height";
	            
	            $output.='<tr>
	                <td width="370"><b>Small Thumbnail '.$dim.'</b> (pixels):</td>
	                <td>
	                    <input type="text" name="max_thumbnail_size" value="'.$config['max_thumbnail_size'].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Large Thumbnail Width</b> (pixels):</td>
	                <td>
	                    <input type="text" name="max_display_size" value="'.$config['max_display_size'].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Number of Thumbnails per Page:</b></td>
	                <td>
	                    <input type="text" name="thumb_num" value="'.$config['thumb_num'].'"/>
	                </td>
	            </tr>
                <tr>
	                <td><b>JPEG Image Quality</b> (1=worst, 95=best, 75=default):</td>
	                <td>
	                    <input type="text" name="image_quality" value="'.$config['compression'].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Default Sort Order:</b></td>
	                <td>';

			$sort_by_fields = array(
				'date' => 'Date Submitted',
				'date_taken' => 'Date Taken',
				'caption' => 'Caption',
				'filename' => 'Filename',
				'number_of_comments' => 'Number of Comments',
			);

			$sort_dir_fields = array(
				'ASC' => 'Ascending',
				'DESC' => 'Descending',
			);
	                    
	                $output .= '<select STYLE="width: 146px" id="default_sortby" name="default_sortby">';

			foreach($sort_by_fields as $sort_key => $sort_caption) {
				$selected = ($config['default_sortby'] == $sort_key) ? 'selected ' : '';
				$output .= '<option '.$selected.'value="'.$sort_key.'">'.$sort_caption.'</option>';
			};
			$output .= '</select><select id="default_sortdir" name="default_sortdir">';

			foreach($sort_dir_fields as $sort_key => $sort_caption) {
				$selected = ($config['default_sortdir'] == $sort_key) ? 'selected ' : '';
				$output .= '<option ' .$selected.'value="'.$sort_key.'">'.$sort_caption.'</option>';
			};
			$output .= '</select>';
			$output .= '
	                </td>
	            </tr>
	            <tr>
	                <td><b>Use Cropped Square Thumbnails?:</b></td>
	                <td>';
	                
	                if ($config['square_thumbs'] == 1) $checked = "CHECKED"; else $checked = "";
	             	$output .= '<input type="checkbox" name="square_thumbs" value="1" '.$checked.'/>
	                   
	                </td>
	            </tr>
	            </table>
	            <h1>Interface Options</h1>
	            <table style="width:550px;text-align:right;font:georgia">
	            <tr>
	                <td><b>Date Format</b>:</td>
	                <td>
	                    <select name="date_format">';

foreach ($date_formats as $format){
	$output .= '<option value="'.$format.'"';
	if ($config["date_format"] == $format) $output .= ' selected="selected"';
	$output .= '>'.date($format).'</option>';
}

$output .= '          </select>
	                </td>
	            </tr>
							<tr>
	                <td><b>Allow Compressed Recursive Downloads?</b>:</td>
	                <td>';
									
									if ($config['allow_dl'] == 1) $checked = "CHECKED"; else $checked = "";
	             				
	                $output .= '<input type="checkbox" name="allow_dl" value="1" '.$checked.'/>
	                </td>
	            </tr>
							<tr>
	                <td><b>Allow User Comments?</b> (will override individual settings)</td>
	                <td>';
	             				
											if ($config['allow_comments'] == 1) $checked = "CHECKED"; else $checked = "";
	             				
	                		$output .=
	                    '<input type="checkbox" name="allow_comments" value="1" '.$checked.'/>
	                </td>
	            </tr>

							<tr>
	                <td><b>Allow Auto Print?</b></td>
	                <td>';
	             				
											if ($config['allow_print'] == 1) $checked = "CHECKED"; else $checked = "";
	             				
	                		$output .=
	                    '<input type="checkbox" name="allow_print" value="1" '.$checked.'/>
	                </td>
	            </tr>
	            </tr>
							<tr>
	                <td><b>Truncate Long Filenames How Long?</b> (Use zero for no truncation)</td>
	                <td>
	             				
							<input size="2" type="text" name="truncate" value="'.$config['truncate'].'"/>
	                </td>
	            </tr>
	            </tr>
							<tr>
	                <td><b>Generate Cruft-Free URLs</b> (requires mod_rewrite)</td>
	               	<td>';
				$htaccess_file = $config["basedir"] . ".htaccess";
				if ($config['use_mod_rewrite'] == 1) $checked = "CHECKED"; else $checked = "";

				if (is_writable($htaccess_file)) {
	                		$output .= '<input type="checkbox" name="use_mod_rewrite" value="1" '.$checked.'/>';
				} else {
					$output .= ".htaccess is not writable, please check permissions";
				};

			$output .= '
	                </td>
	            </tr>
	        </table>

					<h1>RSS Syndication Options</h1>
					<table style="width:550px;text-align:right;font:georgia">
					<tr>
	            <td><b>RSS Feed Title:</b></td>
	                <td>
	                    <input type="text" name="feed_title" value="'.stripslashes($config['feed_title']).'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>RSS Image Thumbnail Width (pixels):</b></td>
	                <td>
	                    <input type="text" name="rss_thumbsize" value="'.$config["rss_thumbsize"].'"/>
	                </td>
	            </tr>
	            <tr>
	                <td><b>Language:</b> <a href="http://www.w3.org/TR/REC-html40/struct/dirlang.html#langcodes">(language codes)</a></td>
	                <td>
	                    <input type="text" name="feed_language" value="'.$config['feed_language'].'"/>
	                </td>
	            </tr>
                <tr>
	                <td><b>Number of Images Per Feed:</b></td>
	                <td>
	                    <input type="text" name="feed_num_entries" value="'.$config['feed_num_entries'].'"/>
	                </td>
	            </tr>
							<tr><td></td><td><input class="submit" type="submit" name="submit" value="Update Options"></td></tr>
	    				</table>
					 
        	
	    </div>';

display($output, "options");

?>
