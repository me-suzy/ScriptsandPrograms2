<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/html_image.inc
## Desc: Functions for handling the uploading and displaying of images
## $Source: /home/cvsroot/mysource/include/html_image.inc,v $
## $Revision: 2.11 $
## $Author: brobertson $
## $Date: 2004/02/05 16:57:55 $
#######################################################################
# This file needs EXTREME cleaning up and class-ifying
#---------------------------------------------------------------------##


  ############################################
 # Prints form tags for an image uploading  #
############################################
# Requires some js functions
function image_upload($name='', $destination='', $display_path='', $max_filesize=0, $max_width=0, $max_height=0, $no_caption='', $commitaction='Commit', $visible_buttons = array('preview', 'current' , 'delete')) {
	$string  = "<input type=file name=\"$name\" onchange=\"if (value.indexOf('.gif') <= 0 && value.indexOf('.GIF') <= 0 && value.indexOf('.jpg') <= 0 && value.indexOf('.JPG') <= 0 && value.indexOf('.png') <= 0 && value.indexOf('.PNG') <= 0) {alert('You may only upload image files of types: .gif, .jpg and .png');value = '';focus();}\">";

	if ($max_width && $max_height) $string .= " <font face=arial size=2>(<b>Max: $max_width x $max_height</b>)</font>";

	$string .= "<input type=hidden name=\"images_uploaded[$name]\" value=\"$name\">";
	if (in_array('delete', $visible_buttons)) {
		$string .= "<input type=hidden name=\"$name"."_delete\" value=\"\">";
	}

	if ($max_filesize) $string .= "<input type=hidden name=\"$name"."_max_filesize\" value=$max_filesize>";
	if ($max_width)    $string .= "<input type=hidden name=\"$name"."_max_width\" value=$max_width>";
	if ($max_height)   $string .= "<input type=hidden name=\"$name"."_max_height\" value=$max_height>";

	if (in_array('preview', $visible_buttons)) {
		$string .= "<br><input style=\"font-size: 8pt\" type=button value=\"Preview\" onclick=\"if (form.$name.value.length >= 4) {";
		$string .= "image_popup('".urlencode("New $name Image")."', 'file:///'+form.$name.value, '.' ".(($max_width)?",$max_width":"").(($max_height)?",$max_height":"").");}else{alert('Click BROWSE to find an image to upload.');}\"> ";
	}

	// if destination string is a location rather than a file(ends with a /) do not display buttons for viewing or deleting the image
	if(substr($destination, strlen($destination)-1,1)=='/')	{
		$location = true;
	} else {
		$location = false;
	}
	if ((file_exists($filename = $destination . '.gif') || file_exists($filename = $destination . '.GIF') || file_exists($filename = $destination . '.jpg') || file_exists($filename = $destination . '.JPG') || file_exists($filename = $destination . '.png') || file_exists($filename = $destination . '.PNG') || file_exists($filename = $destination)) && !$location && !is_dir($filename)) {
		if (!$current_image_size = getimagesize($filename))
			$current_image_size = array(10,10);
		# if there is a display path, it was leaving the "/" on the end of it, giving a url with "//".
		if ($display_path) $filename = "$display_path".substr(strrchr($filename,"/"),1);
		if (in_array('current', $visible_buttons)) {
			$string .= "<input style=\"font-size: 8pt\" type=button value=\"Current\" onclick=\"image_popup('".urlencode("Current $name Image")."', '$filename', '.',".$current_image_size[0].",".$current_image_size[1].")\">";
		}
		if (in_array('delete', $visible_buttons)) {
			$string .= "<input style=\"font-size: 8pt\" type=button value=\"Delete\" onclick=\"if(confirm('Are you sure you want to delete the current $name image?')){form.$name"."_delete.value='Yes';form.action.value='".$commitaction."';form.submit();}\">";
		}
	}

	if ($no_caption) {
		$string .= "<input type=\"hidden\" name=\"$name"."_caption\" value=\"\"></font>";
	} else {
		# Handle the caption - if any
		if (file_exists($filename = $destination . '.cap')) {
			$caption = htmlspecialchars(stripslashes(file_to_string($filename)));
		}
		$string .= "<font size=1 face=arial><br><b>Caption</b>:<input style=\"font-size: 8pt\" name=\"$name"."_caption\" value=\"$caption\" size=40 maxlength=255></font>";

	}#endif

	return $string;
}



     ###############################################
    # Processes an image and retains the original filename and specified directory for it to be stored. The filename
   # must be stored in a database, or elsewhere. The return value is an associative array with a message and a success
  # value. If the upload and copying was successful '1' is returned otherwise '0' is returned. This can be used to
 # determine whether to update the database or not.
################################################

function process_image_upload($image='', $dest='', $retain_filename=true) {

	# First, grab all the global variables
	$file_vars = array('size','type','name');
	for($var = reset($file_vars); $var; $var = next($file_vars)) {
		$$var = $_FILES[$image][$var];
	}

	$post_vars = array('max_filesize','max_width','max_height','delete','caption');
	for($var = reset($post_vars); $var; $var = next($post_vars)) {
		$$var = $_POST[$image.'_'.$var];
	}
	# If they've uploaded it..
	if ($name) {

		$return_result = commit_image_upload($image, $retain_filename, false, $dest);
		if ($retain_filename) {
			$result['message'] .= "\\n" .$return_result['message'];
			$result['success'] = $return_result['success'];
		} else {
			$result['message'] = $return_result;
		}
	}

	# If they want to delete it, do so
	if ($delete) {
		if (file_exists($dest.'.gif')) {
			if (unlink($dest.'.gif')) $delete_message .= " $image image deleted.";
			else $delete_message .= "Unable to delete $image image.";
		} else if (file_exists($dest.'.jpg')) {
			if (unlink($dest.'.jpg')) $delete_message .= " $image image deleted.";
			else $delete_message .= " Unable to delete $image image.";
		} else if (file_exists($dest.'.png')) {
			if (unlink($dest.'.png')) $delete_message .= " $image image deleted.";
			else $delete_message .= " Unable to delete $image image.";
		} else if (file_exists($dest.'.GIF')) {
			if (unlink($dest.'.GIF')) $delete_message .= " $image image deleted.";
			else $delete_message .= " Unable to delete $image image.";
		} else if (file_exists($dest.'.JPG')) {
			if (unlink($dest.'.JPG')) $delete_message .= " $image image deleted.";
			else $delete_message .= " Unable to delete $image image.";
		} else if (file_exists($dest.'.PNG')) {
			if (unlink($dest.'.PNG')) $delete_message .= " $image image deleted.";
			else $delete_message .= " Unable to delete $image image.";
		} else if (!$delete_message) {
			$delete_message .= ' Cannot find file to delete.';
		}
		$result['message'] .= "\\n" . $delete_message;
	}

	#Process the caption if there is any
	if ($caption && (file_exists($dest.'.gif') || file_exists($dest.'.GIF') || file_exists($dest.'.jpg') || file_exists($dest.'.JPG') ||  file_exists($dest.'.png') || file_exists($dest.'.PNG'))) {
		string_to_file(stripslashes($caption),$dest.'.cap');
	} elseif (file_exists($dest.'.cap')) {
		$result .= "\\n$image image caption deleted.";
			unlink($dest.'.cap');
	}

	return $result;
}




  ############################################
 # Process a bunch of potentially uploaded images
########################################

function process_image_uploads($image_array=array(), $dest='') {
	if (empty($image_array)) return;
	if (!$dest) return;

	for($image = reset($image_array); $image; $image = next($image_array)) {
		# First, grab all the global variables
		$file_vars = array('size','type','name');
		for($var = reset($file_vars); $var; $var = next($file_vars)) {
			$$var = $_FILES[$image][$var];
		}

		$post_vars = array('max_filesize','max_width','max_height','delete','caption');
		for($var = reset($post_vars); $var; $var = next($post_vars)) {
			$$var = $_POST[$image.'_'.$var];
		}

		# If they've uploaded it..
		if ($name) {
			$result .= "\\n" . commit_image_upload($image, false, false, $dest);
		}

		#If they want to delete it, do so
		if ($delete) {
			if (file_exists($dest.'.gif')) {
				if (unlink($dest.'.gif')) $delete_message .= " $image image deleted.";
				else $delete_message .= "Unable to delete $image image.";
			} else if (file_exists($dest.'.jpg')) {
				if (unlink($dest.'.jpg')) $delete_message .= " $image image deleted.";
				else $delete_message .= " Unable to delete $image image.";
			} else if (file_exists($dest.'.png')) {
				if (unlink($dest.'.png')) $delete_message .= " $image image deleted.";
				else $delete_message .= " Unable to delete $image image.";
			} else if (file_exists($dest.'.GIF')) {
				if (unlink($dest.'.GIF')) $delete_message .= " $image image deleted.";
				else $delete_message .= " Unable to delete $image image.";
			} else if (file_exists($dest.'.JPG')) {
				if (unlink($dest.'.JPG')) $delete_message .= " $image image deleted.";
				else $delete_message .= " Unable to delete $image image.";
			} else if (file_exists($dest.'.PNG')) {
				if (unlink($dest.'.PNG')) $delete_message .= " $image image deleted.";
				else $delete_message .= " Unable to delete $image image.";
			} else if (!$delete_message) {
				$delete_message .= ' Cannot find file to delete.';
			}
			if (is_file($dest.'.cap')) {
				unlink($dest . '.cap');
			}
			$result .= "\\n" . $delete_message;
		}

		# Do the captions
		if ($caption && (file_exists($dest.'.gif') || file_exists($dest.'.GIF') || file_exists($dest.'.jpg') || file_exists($dest.'.JPG') ||  file_exists($dest.'.png') || file_exists($dest.'.PNG'))) {
			string_to_file(stripslashes($caption),$dest.'.cap');
		} elseif (file_exists($dest.'.cap')) {
			$result .= "\\n$image image caption deleted.";
			unlink($dest.'.cap');
		}
	}
	return $result;
}


    ############################################
   # Saves an image uploaded with the above method, returns result message, and if retain_filename is set, returns
  # an array with a message and a 1 or 0 value indicating a success or failure respectively of the upload and copying
 # of the file
############################################


function commit_image_upload($image='', $retain_filename=false, $return_status=false, $dest='') {
	$result = array('success' => 0, 'message' => '');

	# First, grab all the global variables
	$file_vars = array('size','type','name');
	for($var = reset($file_vars); $var; $var = next($file_vars)) {
		$$var = $_FILES[$image][$var];
	}

	$post_vars = array('max_filesize','max_width','max_height');
	for($var = reset($post_vars); $var; $var = next($post_vars)) {
		$$var = $_POST[$image.'_'.$var];
	}

	$location = $_FILES[$image]['tmp_name'];

	# Check its a legal filetype
	$system_config = &get_system_config();
	if(!$system_config->check_file_legality($name)) {
		$result['message'] = "File '".ucwords(str_replace('_', ' ', $image))."' could not be updated - the uploaded file was of an illegal filetype.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	}

	# Then check the file type
	if ($type == 'image/gif') {
		$extension = '.gif';
	} elseif ($type == 'image/jpeg' || $type == 'image/pjpeg') {
		$extension = '.jpg';
	} elseif ($type == 'image/x-png' || $type == 'image/png') {
		$extension = '.png';
	} else {
		$result['message'] = "The file you uploaded was not a valid image type ($type) - try gif, jpg or png. The $image image remains unchanged.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	}
	# Just want to be sure about this, because explorer does weird things like "image/jpeg" for gifs
	if (get_file_type($name) == 'gif') $extension = '.gif';
	if (get_file_type($name) == 'jpg') $extension = '.jpg';
	if (get_file_type($name) == 'jpeg') $extension = '.jpg';
	if (get_file_type($name) == 'png') $extension = '.png';

	# Then make sure its not too large
	if ($max_filesize && $size > $max_filesize) {
		$result['message'] = "The file you uploaded was too large, please keep below ".easy_filesize($max_filesize).". The $image image remains unchanged.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	} elseif ($size < 5) { # Or too small
		$result['message'] = "The file you uploaded was too small. The $image image remains unchanged.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	}

	# Ensure the image itself is of correct proportions
	list($width, $height) = getimagesize($location);
	if ($max_width && $width > $max_width) {
		$result['message'] = "The image you uploaded was too wide.  The $image image remains unchanged.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	} elseif ($max_height && $height > $max_height) {
		$result['message'] = "The image you uploaded was too high.  The $image image remains unchanged.";
		$result['success'] = 0;
		if ($retain_filename) {
			return $result;
		} else {
			return $result['message'];
		}
	}

	// finds if destination points to a file or a directory
	if(substr($dest, strlen($dest)-1,1)=='/')	{
		$is_dest_location = true;
	}
	else
	{
		$is_dest_location = false;
	}

	# Create the destination directory, if necessary
	$old_umask = umask(0);
	if($is_dest_location) {
		if (!create_directory($dest)) {
			$result['message'] = "Unable to create directory.";
			$result['success'] = 0;
			return $result;
		}
	} else {
		if(!create_directory(dirname($dest))) {
			$result['message'] = "Unable to create directory.";
			$result['success'] = 0;
			return $result;
		}
	}
	umask($old_umask);

	# if the destination is a directory (ends with a /), then use the original name of
	# the file that is uploaded - except the extension is changed to either .jpg or .gif depending on the type

	$tmp_name = ereg_replace("\.[^\.]*$",'', $name);
	if($tmp_name=='')
	{
		$tmp_name = $name;
	}
	if($is_dest_location)	{
		$destination = $dest.$tmp_name.$extension;
	} else if($retain_filename) {
		$destination = dirname($dest).'/'.$tmp_name.$extension;
	} else {
		$destination = $dest . $extension;
	}

	// ensures that we are not about to overcopy an existing file, as this could lead to integrity problems when filenames are stored in the database.. because you may end up with a case where 2 references point to the same file.. if the user selects to delete one of these files then the file would be deleted and only one reference would be removed but not the other.

	if($retain_filename)
	{
		$old_filename = substr (strrchr ($dest, '/'), 1);
		$new_filename = substr (strrchr ($destination, '/'), 1);
		if($old_filename != $new_filename && file_exists($destination))
		{
			$result['message'] .= "There already exists another file called $new_filename. Please rename this file before copying over, or delete the other image before copying over.";
			$result['success'] = 0;
			return $result;
		}
	}
	# Move file over
	if (!copy($location, $destination)) {
		report_error(__FILE__,__LINE__,"Unable to create file: $dest");
		exit();
	}
	else if ($retain_filename && is_file($dest) && !$is_dest_location && $dest!=$destination)
	{
		// deletes the old file if the new file is copied successfully
		 {
			if (unlink($dest)) {
				$result['message'] .= "old $image image deleted.\\n";
				$result['success'] = 1;
			} else {
				$result['message'] .= "Unable to delete old $image image.\\n";
				$result['success'] = 0;
			}
		}

	}
	# Delete the old one, if filename changed.
	if(!$retain_filename && is_file($destination)) {
		switch ($extension) {
			case '.gif':
				unlink($dest . '.jpg');
				unlink($dest . '.png');
				break;
			case '.png':
				unlink($dest . '.gif');
				unlink($dest . '.jpg');
				break;
			case '.jpg':
				unlink($dest . '.gif');
				unlink($dest . '.png');
				break;
		}
	}
	$result['message'] .= "Image $image has been updated.";
	if($retain_filename || $return_status) {
		$result['success'] = 1;
		return $result;
	} else {
		return $result['message'];
	}

}


  #################################################
 # Deletes an image from server
###################################################

function delete_image($filename='') {
	if (!$filename) return;
	# Assumes an extension hasn't been supplied
	if (unlink($filename.'.gif') || unlink($filename.'.GIF') || unlink($filename.'.jpg') || unlink($filename.'.JPG') || unlink($filename.'.png') || unlink($filename.'.PNG')) {
		unlink($filename.'.cap'); # Captions
	}
}

?>
