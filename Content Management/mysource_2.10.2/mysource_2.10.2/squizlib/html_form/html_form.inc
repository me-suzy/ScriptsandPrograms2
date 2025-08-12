<?  ################################################
   ### SQUIZLIB --------------------------------###
  ##- General HTML Form Field Functions - PHP4 -##
 #-- Copyright Squiz.net -----------------------#
################################################
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
## File: html_form/html_form.inc
## Desc: Functions for the quick and easy creation of form elements in HTML
## $Source: /home/cvsroot/squizlib/html_form/html_form.inc,v $
## $Revision: 2.29.2.1 $
## $Author: mbrydon $
## $Date: 2004/05/12 06:11:15 $
#######################################################################

#---------------------------------------------------------------------#

  #####################
 # A simple text box 
function text_box($name='', $value='', $size='', $max='', $extras='', $autos=-1) {

	$pre  = '';
	$post = '';

	if($autos != -1 && $autos) {
		if(!is_array($autos)) {
			$autos = split("[\n\r]+", trim($autos));
		} else {
			// just to make sure we have an indexed array
			$autos = array_values($autos);
		}

		if (!empty($autos)) {

			$n = preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
			$data_name = 'window.html_form_text_box_auto_complete_data_'.$n;
			$btn_name = $n.'_auto_button';

			if (empty($GLOBALS['HTML_FORM_TEXT_BOX_AUTO_COMPLETE_INIT'])) {
				$pre .= '
				<script language="javascript" type="text/javascript">

					function html_form_text_box_change_auto_complete(name, auto_options) {
						window["html_form_text_box_auto_complete_data_" + name].auto_options = auto_options;
						window["html_form_text_box_auto_complete_data_" + name].reset = true;
					}
						
					function html_form_text_box_auto_complete(form, text_box_name, data) {
						var t = form.elements[text_box_name]; 
						if (data.auto_options.length == 0) return;
						// if this is the first time that they have pressed this 
						// then store the current value of the text box
						if (data.reset) {
							data.orig_value = t.value.toString();
							data.index = 0;
							data.reset = false;
						}

						var i = (data.index >= data.auto_options.length) ? 0 : data.index;
						// basically this is an infinite loop, so we have to be very careful
						do {
							// if the start of this option is the same as the original value, use it
							if (data.orig_value.toLowerCase() == data.auto_options[i].substr(0, data.orig_value.length).toLowerCase()) {
								t.value = data.auto_options[i];
								data.index = i + 1; // increment so next time we start at the next item
								break;
							}

							// if the index is about to go past the end then reset, else increment
							if (i >= data.auto_options.length - 1) i = 0;
							else i++;

							// if we are back to the index we started at it means we have hit the end
							// of the options array and come all the way from the beginning again, 
							// so we have not found anything, tell em and get out of here
							if (i == data.index) {
								break;
							}
						} while(true);


					}// end html_form_text_box_auto_complete()

				</script>';

				$GLOBALS['HTML_FORM_TEXT_BOX_AUTO_COMPLETE_INIT'] = 1;
			}#end if

			$auto_count = count($autos);
			for($i = 0; $i < $auto_count; $i++) {
				$autos[$i] = addslashes(preg_replace("/[\r\n]/", '', $autos[$i]));
			}

			$pre .= '<script language="javascript" type="text/javascript">
						'.$data_name.' = new Object();
						'.$data_name.'.auto_options = new Array("'.implode('","', $autos).'");
						'.$data_name.'.index = 0;
						'.$data_name.'.reset = true;
					</script><nobr>';
			# this on focus is to reset the index as soon as they attempt to edit the text again, 
			# so next time we search we start from the beginning again
			$extras .= ' onfocus="'.$data_name.'.reset = true;"';
			$post = '<input name="'.$btn_name.'" type="button" value=">" onclick="javascript: html_form_text_box_auto_complete(this.form, \''.addslashes(str_replace('"', '&quot;', $name)).'\', '.$data_name.');"></nobr>';
		}// end if count autos

	}// end if autos
	return $pre.'<input type=text '
		 . 'name="'.str_replace('"', '&quot;', $name).'" '
		 . 'value="'.htmlspecialchars($value).'" '
		 . (($size)  ? "size=$size " : '')
		 . (($max)   ? "maxlength=$max " : '')
		 . (($extras) ? $extras : '')
		 . '>'.$post;
}



  #####################
 # A simple text box #
#####################

function password_box($name='', $value='', $size=0, $max=0, $extras='') {
	return    "<input type=password "
			. "name=\"".str_replace("\"","&quot;",$name)."\" "
			. "value=\"".htmlspecialchars($value)."\" "
			. (($size)  ? "size=$size " : "")
			. (($max)   ? "maxlength=$max " : "")
			. (($extras) ? "$extras" : "")
			. ">";
}


  #####################
 # A text area       #
#####################

function text_area($name='', $contents='', $cols=0, $rows=0, $max=0, $extras='') {
	return    "<textarea "
			. "name=\"".str_replace("\"","&quot;",$name)."\" "
			. (($cols)  ? "cols=$cols " : "")
			. (($rows)  ? "rows=$rows " : "")
			. (($max)   ? "onchange=\"if (value.length > $max){value=value.substr(0,$max); alert('$name may be no more than $max characters long.');focus()}\" " : "")
			. "$extras>"
			. htmlspecialchars($contents)
			. "</textarea>";
}


  ######################################################################################
 # Given a name, an array of keys and values, and a default value, prints a combo box #
######################################################################################

function combo_box($name='', $contents='', $default='', $extras='', $maxlength=0, $height=0, $force_width=0) {
	$string = '<select name="'.$name.'" '.(($height>1)?'size='.$height:'').' '.$extras.'>';

	if (!is_array($contents)) $contents = array($contents);
	if (!empty($contents)) {
		reset($contents);
		while(list($key, $value) = each($contents)) {
			if ($maxlength) {
				$value = str_replace('&nbsp;', ' ', $value);  # we don't want a space to take 6 chars do we ?
				$value = ellipsisize($value,$maxlength);
				$value = str_replace(' ', '&nbsp;', $value);
			}
			if ($force_width) {
				$length = strlen($value);
				$multiplier = $force_width - $length;
				if ($multiplier > 0) {
					$value .= str_repeat('&nbsp;',$multiplier);
				}
			}
			$value = str_replace('<','&lt;',$value);
			$selected = ((string) $key === (string) $default) ? 'selected' : '';
			$string .= '<option value="'.str_replace("\"",'&quot;',$key).'" '.$selected.'>'.$value.'</option>';
		}
	}
	$string .= '</select>';
	return $string;
}

   ################################################################################################
  # Given a name, an array of keys and values, and an array of default values, 
 # prints a multiple select combo box 
#################################################################################################

function multiple_combo_box($name='', $contents='', $defaults='', $extras='', $maxlength=0, $height=0, $force_width=0) {
	 #############################################
	# Make sure we are only dealing with an array
	if (!is_array($defaults)) $defaults = array($defaults);
	if (!is_array($contents)) $contents = array($contents);

	 ################################################################################################
	# if the name doesn't have the square brackets add them as they are needed for multiple selects
	if (!ereg("\[\]$", $name)) $name .= '[]';
	$string = '<select name="'.$name.'" '.(($height > 1)?'size='.$height:'').' multiple '.$extras.'>';

	if (!empty($contents)) {
		reset($contents);
		while(list($key, $value) = each($contents)) {
			if ($maxlength) {
				$value = str_replace('&nbsp;', ' ', $value);  # we don't want a space to take 6 chars do we ?
				$value = ellipsisize($value,$maxlength);
				$value = str_replace(' ', '&nbsp;', $value);
			}
			if ($force_width) {
				$length = strlen($value);
				$multiplier = $force_width - $length;
				if ($multiplier > 0) {
					$value .= str_repeat('&nbsp;',$multiplier);
				}
			}
			$selected = (in_array($key, $defaults)) ? 'selected' : '';
			$string .= '<option value="'.str_replace("\"",'&quot;',$key).'" '.$selected.'>'.$value.'</option>';
		}
	}
	$string .= '</select>';
	return $string;
}


 ############################################################
# Prints out a matrix of checkboxes for a 2D boolean array
# often very handy!
function checkbox_array($name='',$codes='',$options='',$code_vals='',$single=false,$wa = 1) {
	$r .= ('<table cellpadding=2 cellspacing=0 border=0 width=100%>');
	$i = 0;
	if (!empty($codes)) {
		foreach($codes as $code => $label) {
			$r .= ('<tr>');
			for($j = 0; $j < $i; $j++) $r .= ("<td bgcolour=\"".((($j)%2)?"#dddddd":"#eeeeee")."\">&nbsp;&nbsp;|</td>");
			$colour = (($i % 2) ? "#dddddd":"#eeeeee");
			$r .= ("<td bgcolour=$colour colspan=\"".(count($codes)+1-$i)."\" width=100%>$label</td>");
			$r .= ("</tr>");
			$i++;
		}
		if(count($codes) > 1) {
			$r .= ("<tr>");
			for($j = 0; $j < $i; $j++) $r .= ("<td bgcolour=\"".((($j)%2)?"#dddddd":"#eeeeee")."\">&nbsp;&nbsp;|</td>");
			$r .= ("<td width=100%>&nbsp;</td></tr>");
		}
	}
	if (!empty($options)) {
		foreach($options as $key => $label) {
			$r .= ("<tr>");
			$j = 0;
			if (!empty($codes)) {
				foreach($codes as $code => $code_label) {
					$value = &$code_vals[$code];
					if($name) $code = $name."[$code]";
					$r .= ("<td valign=top bgcolour=\"".((($j)%2)?"#dddddd":"#eeeeee")."\">&nbsp;");
					if($single)	{
						if ($wa) $r .=  "<input type=radio name=\"$code\" value=\"$key\" ".(($key==$value)?"checked":"").">";
						else     $r .=  (($key==$value)?"&nbsp;<span style=\"color: green;font-weight:bold;\">Y</span>":"&nbsp;<span style=\"color: red;font-weight:bold;\">N</span>");
					} else {
						if ($wa) $r .=  "<input type=checkbox name=\"$code"."[]\" value=\"$key\" ".(isset($value) && (in_array($key,$value))?"checked":"").">";
						else     $r .=  (isset($value) && (in_array($key,$value))?"&nbsp;<span style=\"color: green;font-weight:bold;\">Y</span>":"&nbsp;<span style=\"color: red;font-weight:bold;\">N</span>");
					}
					$r .= ('</td>');
					$j++;
				}
			}
			$r .= ("<td width=100%>$label</td></tr>");
		}
	}
	$r .= ("</table>");
	return $r;
}


  ############################################
 # Prints form tags for file uploading  #
############################################

function file_upload($name='', $destination='', $max_filesize=0, $dest_filename='', $restrict_extensions=Array()) {
	$onchange  = '';
	$condition = '';
	if (!empty($restrict_extensions)) {
		foreach($restrict_extensions as $ext) {
			$condition .= (($condition) ? ' && ' : '')."this.value.toLowerCase().indexOf('.".$ext."') < 0";
		}
	}
	if ($condition) {
		$onchange = " onchange=\"if (".$condition.") {alert('You may only upload the following files types: ".implode(",", $restrict_extensions)."'); this.value=''; this.focus();}\"";
	}#end if

	$string  = "<input type=file name=\"$name\"$onchange>";
	if ($max_filesize)  $string .= "<input type=hidden name=\"$name"."_max_filesize\" value=$max_filesize>";
	if ($restrict_extensions) $string .= "<input type=hidden name=\"$name"."_restrict_extensions\" value=\"".implode(",", $restrict_extensions)."\">";
	return $string;
}


  ############################################
 # Commits an uploaded file to its intended place
############################################

function commit_file_upload($file='', $overwrite=false, $keep_extension=false, $overwrite_if_filename='', $dest='', $dest_filename='', &$saved_filename) {
	$saved_filename = '';

	$file_vars = array('tmp_name', 'name', 'size', 'type');
	for($var = reset($file_vars); $var; $var = next($file_vars)) {
		$$var = $_FILES[$file][$var];
	}

	$post_vars = array('max_filesize','restrict_extensions');
	for($var = reset($post_vars); $var; $var = next($post_vars)) {
		$$var = $_POST[$file.'_'.$var];
	}

	if (!$name || !is_uploaded_file($tmp_name)) return '';

	$ext = strtolower(substr(strrchr($name,'.'), 1));
	if (!empty($restrict_extensions)) {
		$exts = explode(",", strtolower($restrict_extensions));
		if (!in_array($ext, $exts)) {
			return "File '".ucwords(str_replace('_', ' ', $file))."' can only be the following types : $restrict_extensions";
		}#end if
	}#end if
	$system_config = &get_system_config();
	if(!$system_config->check_file_legality($name)) {
		return "File '".ucwords(str_replace('_', ' ', $name))."' could not be updated - the uploaded file was of an illegal filetype.";
	}

	# if they want a specific filename then use it
	if ($dest_filename) {
		# if they want to keep the extension of the file, remove the dest_filenames extension
		# and add the uploaded files one
		if ($keep_extension) {
			$dest_ext = strtolower(substr(strrchr($dest_filename,'.'), 0));
			$dest_filename = substr($dest_filename, 0, strlen($dest_filename) - strlen($dest_ext));
			$name = $dest_filename.".".$ext;
		} else {
			$name = $dest_filename;
		}#end if
	}

	# Then make sure its not too large
	if ($max_filesize && $size > $max_filesize) {
		return "The file you uploaded was too large, please keep below ".easy_filesize($max_filesize).". The current one remains unchanged.";
	}

        # Don't be silly and create the tree structure if the directory already exists! :)
        $create_dir = false;
        if (!file_exists("/".$dest)) $create_dir = true;

        # Create the destination directory, if necessary
        if ($create_dir)        {
		$dir_tree = explode("/",$dest);
		$dir_tree_string = $dir_tree[0];
		$dir_tree_count = count($dir_tree);
		for($i = 1; $i <= $dir_tree_count; $i++) { # Dest does not include filename
			if ($dir_tree_string && !file_exists($dir_tree_string)) {
				if (!mkdir($dir_tree_string, 0775)) {
					report_error(__FILE__,__LINE__,"Could not create directory: $dir_tree_string");
					exit();
				}
			}
			$dir_tree_string .= "/" . $dir_tree[$i];
		}
	}

	# if this filename already exist, we aren't to overwrite outright and we aren't to overwright it if 
	# it's a particular filename, then rename it
	if(file_exists($dest . "/" . stripslashes($name)) && !$overwrite && $overwrite_if_filename != $name) {
		while(file_exists($dest . "/" . stripslashes($name)) && $overwrite_if_filename != $name) {
			$name = increment_filename($name);
			$thefile_name = $name;
		}
		$message .= "File name already exists. File renamed to $name.\\n";
	}

	# Copy file over
	if (!copy($tmp_name, $dest . "/" . stripslashes($name))) {
		report_error(__FILE__,__LINE__,"Unable to create file: $dest/$name");
		exit();
	} else {
		$saved_filename = $name;
		$message .= "File '".ucwords(str_replace("_", " ", $name))."' has been updated.";
	}

	return $message;
}

 ################################################################################
# Takes a variable and returns as many hidden fields as possible to recreate it
function var_to_hidden_field($name='',$var='',$stripslashes='') {
	if(is_array($var) || is_object($var)) {
		$result = '';
		if (!empty($var)) {
			foreach($var as $k => $v) {
				if($name) $new_name = $name."[$k]";
				else $new_name = $k;
				$result .= var_to_hidden_field($new_name,&$v,$stripslashes);
			}
		}
		return $result;
	} else {
		if($name) {
			$name = str_replace("\"","&quot;",$name);
			$var = str_replace("\"","&quot;",$var);
			if($stripslashes) $var = stripslashes($var);
			return ("<input type=hidden name=\"$name\" value=\"$var\">");
		}
	}
}

?>
