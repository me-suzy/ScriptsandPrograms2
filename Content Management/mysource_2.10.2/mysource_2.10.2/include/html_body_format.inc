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
## File: include/html_body_format.inc
## Desc: Set of functions allowing the creation and printing of table-formatted pages
## $Source: /home/cvsroot/mysource/include/html_body_format.inc,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:58 $
#######################################################################
# This file is messy and old. It needs to be replaced with a neater,
# more flexible class.
# (see squizlib/bodycopy :)
#---------------------------------------------------------------------#

  #############################################################################################
 # Using a special delimiting format, converts a body of text into formatted html for display#
#############################################################################################

function format_text_to_html ($text='') {
	$lines = explode("\n",$text);
	for ($line = reset($lines); $line; $line = next($lines)) {
		# Get the level of indentation
		for ($indent = 0; $line[$indent] == " "; $indent++);

		# Ordered list or not
		$ordered = (substr(trim($line),0,1) == "#");
		$bulleted = (substr(trim($line),0,1) == ".");
		$listed = ($bulleted || $ordered);

		# Auto-indent bullets
		#if ($listed && !$indent && !$was_listed)
		#	$new_text .= (($ordered) ? "<ol>\n" : "<ul>\n");
		# Compensate for afterwards
		#if (!$listed && !$indent && $was_listed && $previous_indent)
		#	$new_text .= (($was_ordered) ? "</ol>\n" : "</ul>\n");

		# Adjust level of indenting if changed - and bullet types etc
		if (($moved = $indent - $previous_indent) != 0) { # Moved
			for($i = 0; $i > $moved; $i--) $new_text .= (($was_ordered) ? "</ol>\n" : "</ul>\n");
			for($i = 0; $i < $moved; $i++) $new_text .= (($ordered) ? "<ol>\n" : "<ul>\n");
		} else {
			if ($indent && $ordered && !$was_ordered) $new_text .= "</ul>\n<ol>\n";
			if ($indent && !$ordered && $was_ordered) $new_text .= "</ol>\n<ul>\n";
		}

		# Rememeber last line details
		$previous_indent  = $indent;
		$was_ordered      = $ordered;
		$was_bulleted     = $bulleted;
		$was_listed       = $listed;

		$line = trim($line); # Now trim.
	
		if (0 == 1) {
		} elseif (!$line && $previous_line) { # Sudden line break
			$new_text .= "</p>\n";

		} elseif (!$line && !$previous_line) { # Add a line break if extra blank line
			$new_text .= "<br>\n";

		} elseif ($line[0] == "^") { # Detect headings
			$new_text .= "<p class=subheading>" . substr($line,1) . "</p>\n";

		} elseif ($line[0] == ".") { # Detect bullets
			$new_text .= "<li>" . substr($line,1) . "</li>\n";

		} elseif ($line[0] == "#") { # Detect bullets
			$new_text .= "<li>" . substr($line,1) . "</li>\n";

		} elseif ($line && $previous_line) { # Line break in paragraph
			$new_text .= "<br>" . $line . "\n";


		} elseif ($line && !$previous_line) { # Normal new paragraph
			$new_text .= "<p>" . $line . "\n";
		}

		$previous_line = $line;
	}
	for($i = 0; $i < $indent; $i++) $new_text .= "</ul>\n";
	$new_text = ereg_replace("\(c\)", "&copy;",$new_text);
	$new_text = ereg_replace("\(r\)", "&reg;",$new_text);
	$new_text = ereg_replace("\(tm\)","",$new_text);

	return $new_text;
}


  ###########################################
 # Converts an encoded body copy into HTML #
###########################################

function bodycopy_to_html($bodycopy='bodycopy',$name='',$image_src='', $image_rel='') {
	$WEB = &get_web_system();
	$db  = &$WEB->get_db();
	#$bodycopy = hyperlink_urls($bodycopy);
	$array = html_bodycopy_text_to_array($bodycopy);
	if (!empty($array)) {
		# Get all the coordinates out and organise them
		ksort($array);
		while(list($coord,$content) = each($array)) {
			# Break it up
			$tableid = strtok($coord,"-");
			$rowid   = strtok("-");
			$cellid  = strtok("-");
			$tables[$tableid][$rowid][$cellid] = $content;
		}
	}

	if (!empty($tables)) {
		while(list($tableid,$rows) = each($tables)) {
			$html .= "<table width=100%>";
			if (!empty($rows)) {
				while(list($rowid,$cols) = each($rows)) {
					$html .= "<tr>";
					if (!empty($cols)) {
						while(list($colid,$content) = each($cols)) {
							# Detect alignment codes
							if (ereg("\<[TMB][LCR]\>",substr(trim($content),0,4))) {
								switch ($content[1]) { # Vertical alignment
									case "T": $valign = "top"; break;
									case "M": $valign = "middle"; break;
									case "B": $valign = "bottom"; break;
								}
								switch ($content[2]) { # Horizontal alignment
									case "L": $align = "left"; break;
									case "C": $align = "center"; break;
									case "R": $align = "right"; break;
								}
								$content = substr(trim($content),4);
							} else {
								$valign  = "top";
								$align   = "left";
							}
							# Detect images
							if (substr(trim($content),0,7) == "[IMAGE:") {
								$coord = ereg_replace("-","_", substr($content,7,11));
								$link  = substr(trim($content),19);
								$this_image_src = "$image_src/body_image_$name"."_".$coord;
								$html .= "<td valign=$valign align=$align>";
								$html .= $WEB->image_tag($this_image_src,$image_rel,"",0,0,"border=0",$link);
								$html .= "</td>";
							# Detect files
							} elseif (substr(trim($content),0,6) == "[FILE:") {
								$html .= "<td valign=$valign align=$align>";
								$fileid = substr(trim($content),6,-1);
								# double-check
								if (($fileid = $db->single_element("SELECT fileid FROM file WHERE fileid='$fileid'")) && ($file = &$WEB->get_file($fileid))) {
									$html .= "<p style=\"font-size: 7pt\">";
									$html .= "<b><a target=_new href=\"./?f=$file->id\" onmouseover=\"window.status='$file->description';return true;\" onmouseout=\"window.status='';return true;\">";
									$html .= "$file->filename";
									$html .= "</a></b><br>";
									$html .= "<b>Size:</b> ".easy_filesize($file->size)."<br>";
									$html .= "<b>Updated:</b> ".readable_datetime($file->modified)."<br>";
									if ($file->description) {
										$html .= "<b>Desc:</b> $file->description<br>";
									}
								}
								$html .= "</td>";
							} else {
								# halt called to all Tyco services work. This section of code is still under development.
								# Ereg is not doing what you would expect. What has to be done is find the first match of FILELINK...
								# and then replace it, before calling ereg again to find the next match of FILELINK.....
								/*ereg("\[FILELINK:[0-9]+\]",$content,$regs);
								next($regs);
								while(list($key,$val) = each($regs)) {
									echo "key $key, $val val<br>";
									$fileid = substr(trim($val,11,-1));
									# double-check 
									if (($fileid = db_single_element("SELECT fileid FROM file WHERE fileid = '$fileid'")) && ($file = load_file_info($fileid))) {
										$replace_files[$val] = "<a href=\"./?f=".$file[fileid]."\">".$file[filename]."</a> (".easy_filesize($file[size]).")";
									}
								}
								while(list($key,$val) = each($replace_files)) {
									$content = ereg_replace($key,$val,$content);
								}*/
								$html .= "<td valign=$valign align=$align class=bodycopy>".format_text_to_html(hyperlink_urls($content))."</td>";
							}
						}
					}
					$html .= "</tr>";
				}
			}
			$html .= "</table>";
		}
	}
	return $html;
}

  ##############################################################################
 # Convert Body-copy text into a nice datastructure
###############################################################################

function html_bodycopy_text_to_array($text='') {
	$text = ltrim($text);
	$current_coord = "000-000-000";
	$array = array();
	if (!$text) return array(); #$array[$current_coord] = " ";
	for($line = strtok($text,"\n"); $line; $line = strtok("\n")) {
		if  ($line[0] == "~" && $line[count($line) - 1] == "~") {
			$current_coord = substr($line,1,-2);
			$array[$current_coord] = "";
		} else {
			$array[$current_coord] .= $line. "\n";
		}
	}
	return $array;
}

  ##############################################################################
 # Convert Body-copy array to a nice bit a of text
###############################################################################

function html_bodycopy_array_to_text($name='') {
	# Grab related variables
	$array = $_REQUEST[$name];
	if (!is_array($array)) $array = array();

	if (empty($array)) return;

	$array = stripslashes_array($array);
	$alignment = $_REQUEST[${$name . "_alignment"}];

	ksort($array);
	$text = "";
	while(list($cell_coord,$content) = each($array)) {
		$text .= "\r\n~$cell_coord~\r\n";
		# Alignment
		if ($alignment[$cell_coord]) {
			$text .= "<$alignment[$cell_coord]>";
		}
		$text .= "$content";
	}
	
	return $text;
}

?>
