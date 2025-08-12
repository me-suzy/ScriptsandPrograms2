<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/file_reference/file_reference.inc,v $
## $Revision: 1.21.2.1 $
## $Author: achadszinow $
## $Date: 2004/05/07 05:23:51 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Type_File_Reference extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'   => array(
				'replace' => 'Replace',
			),
			'value_formats' => array(
				'id' => 'ID',
				'name' => 'Filename',
				'href' => 'HREF',
				'url' => 'URL',
				'href_link' => 'Filename Hyperlinked to HREF',
				'url_link' => 'Filename Hyperlinked to URL',
				'description' => 'Description',
				'keywords' => 'Keywords',
				'extension' => 'Extension',
				'type' => 'Type',
				'size_bytes' => 'Size in Bytes',
				'size_kilobytes' => 'Size in Kilobytes',
				'size_megabytes' => 'Size in Megabytes',
				'size_auto' => 'Size, Friendly Format',
				'modified' => 'Last Modified Date (YYYY-MM-DD HH:MM:SS)',
				'created' => 'Date Created (YYYY-MM-DD HH:MM:SS)',
				'readable_modified' => 'Last Modified Date, Readable Format',
				'readable_created' => 'Date Created, Readable Format',
				'easy_modified' => 'Last Modified Date, Context-sensitive Format',
				'easy_created' => 'Date Created, Context-sensitive Format',
				'image_tag' => 'As HTML Image Tag',
				'image_tag_left' => 'As HTML Image Tag, Left-aligned',
				'image_tag_right' => 'As HTML Image Tag, Right-aligned',
			),
			'search_formats' => array (
				'f_exists' => 'File Exists'
				#'match'     => 'Complete Filename Match',
				#'partial'   => 'Partial Filename Match',
				#'complex'   => 'Complex Filename Match',
				#'keywords'  => 'Description and Keyword Complex match',
				#'extension' => 'Extension Match (any, space-seperated)'
				# filesize date etc..
				# TO DO..
			),
			'edit_formats' => array (
				'raw' => 'Standard',
				'upload_only' => 'Upload Box Only',
				'file_dd_only' => 'File Dropdown Only'
			),
			'sort_formats' => array (
				'raw' => 'By File ID',
				'raw<' => 'By File ID Desc.'
			),
			'rule_types' => array (
			),
			'requires_context_values' => false,
			'sub_type_name' => 'file_reference',
			'table_settings' => array(
				'sub_table_name' => ''
			)
		);

	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			case 'replace':
				return $new_value;
			default:
				return $original;
				break;
		}
	}

	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = 'url_link') {
		if($value <= 0) return '';
		if(!$format) $format = 'url_link';
		$web_system = &get_web_system();
		if($format) {
			$file = &$web_system->get_file((int)$value);
			if(!$file->id) {
				if(in_array($format,array('name','url_link','href_link'))) return '[File not found.]';
				return '';
			}
		}
		switch($format) {
			case 'name':
				return $file->filename;
			case 'href':
				return $file->get_href();
			case 'url':
				return $file->get_url();
			case 'href_link':
				return '<a href="'.$file->get_href().'">'.$file->filename.'</a>';
			case 'url_link':
				return '<a href="'.$file->get_url().'">'.$file->filename.'</a>';
			case 'description':
				return $file->description;
			case 'keywords':
				return $file->keywords;
			case 'extension':
				return $file->get_file_type();
			case 'type':
				$file_types_config = &get_config('file_types');
				return $file_types_config->file_types[$file->type];
			case 'size_bytes':
				return $file->size;
			case 'size_kilobytes':
				return $file->size / 1024;
			case 'size_megabytes':
				return $file->size / 1024 / 1024;
			case 'size_auto':
				return easy_filesize($file->size);
			case 'modified':
				return $file->modified;
			case 'created':
				return $file->created;
			case 'easy_modified':
				return easy_datetime($file->modified);
			case 'easy_created':
				return easy_datetime($file->created);
			case 'readable_modified':
				return readable_datetime($file->modified);
			case 'readable_created':
				return readable_datetime($file->created);
			case 'image_tag':
				return '<img src="'.$file->get_href().'" alt="" border="0">';
			case 'image_tag_left':
				return '<img src="'.$file->get_href().'" alt="" border="0" align="left">';
			case 'image_tag_right':
				return '<img src="'.$file->get_href().'" alt="" border="0" align="right">';
			case 'id': default: # File ID
				return $value;
		}
	}
		
	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		global $IN_BACKEND;
		# This is for the benefit of some templates that don't supply the attribute with the current value
		echo '<input type="hidden" name="'.$prefix.'current_value" value="'.$value.'">';

		$root_pageid = &$this->get_value('root_pageid');
		$web_system = &get_web_system();
		$root_page = &$web_system->get_page($root_pageid);
		if(!$root_page->id) {
			echo 'A valid page has not been selected for uploading files into.';
			return;
		}

		$n = &$this->get_notitia_system();
		$site = &$root_page->get_site();
		$site_index = &$site->get_page_index();
		$subpageids = $root_page->get_subpageids();

		$file = null;
		$page = null;
		if((int) $value > 0) {
			$file = &$web_system->get_file((int)$value);
			if($file->id) {
				$page = &$web_system->get_page($file->pageid);
			}
		} elseif ((int) $value < 0) {
			$page = &$web_system->get_page((int) $value * -1); # Hidden pageid?
		}
		if(!$page->id) $page = &$root_page;

		switch($format) {
			case 'upload_only':
				echo '<input type="hidden" name="'.$prefix.'new_fileid" value="'.((int)$file->id).'">';
				echo '<input type="hidden" name="'.$prefix.'new_pageid" value="'.((int)$page->id).'">';
				echo file_upload($prefix.'new_upload');
				break;
			case 'file_dd_only':
				# Now print the file information.
				$file_options = array();
				foreach($page->file_index as $fileid) {
					$tmp_file = &$web_system->get_file($fileid);
					$file_options[$fileid] = "$tmp_file->filename (".easy_filesize($tmp_file->size).", ".easy_datetime($tmp_file->modified).")";
				}
				echo combo_box($prefix.'new_fileid',array(''=>'-- No File Selected --')+$file_options,$file->id);
				break;
			default:

				# Now print the file information.
				$file_options = array();
				foreach($page->file_index as $fileid) {
					$tmp_file = &$web_system->get_file($fileid);
					$file_options[$fileid] = "$tmp_file->filename (".easy_filesize($tmp_file->size).", ".easy_datetime($tmp_file->modified).")";
				}
				echo combo_box($prefix.'new_fileid',array(''=>'-- No File Selected --')+$file_options,$file->id);
				if ($IN_BACKEND) {
					echo '<a href="javascript: document.location = \'./page.php?file_edit=1&f=\'+elementValue(document.edit.'.$prefix.'new_fileid);">*</a>';
				}
				echo '<br />';

				# Page info
				if($subpageids && count($subpageids) > 0) {
					$subpage_options = $web_system->page_array_with_sticks($root_page->siteid,$root_page->id);
					echo '<b>Page</b>: ';
					echo combo_box($prefix.'new_pageid',$subpage_options,$page->id);
					if ($IN_BACKEND) {
						echo "<a href=\"javascript: document.location = './page.php?PARAMETER_SCREEN=page_attachments&p='+elementValue(document.edit.$prefix"."new_pageid);\">*</a>";
					}
					echo '<span class="smallprint"><br />Changing the page while a file is selected will move the file to that page.<br />Unselect the file and change pages to find an alternative file on a different page.<br /></span>';
				} else {
					echo '<input type="hidden" name="'.$prefix.'new_pageid" value="'.$page->id.'">';
					echo '<b>Page</b>: '.$page->name;
					if ($IN_BACKEND) {
						echo ' <a href="./page.php?p='.$page->id.'&PARAMETER_SCREEN=page_attachments">*</a>';
					}
					echo '<br />';
				}
				
				# New file?
				echo "<b>New:</b>";
				echo file_upload($prefix.'new_upload');
				echo '<span class="smallprint"><br />Uploading a new file while one is already selected will replace the selected file.<br>Unselect the file when uploading to create a new file record.<br />Remember that replacing a file reflects all references to that file.<br /></span>';
				break;
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value("note")) {
			echo'<span class="smallprint"><br />'.$note.'</span>';
		}
	}

	/**
	* Processes changes to an attribute
	*
	* @param  string  $prefix                 The attribute changing prefix
	* @param  &string $value_destination      The current value
	* @param  string  $format                 Don't remember
	* @param  &string &$rules_broken_messages Var to hold broken rull messages
	* @access public
	* @return string
	*/
	function process_field($prefix, &$value_destination, $format, &$rules_broken_messages) {
		if (!$value_destination && $format == 'upload_only') {
			# Try to grab the previous value from the post form
			$value_destination = $_POST[$prefix.'current_value'];
		}

		$n = &$this->get_notitia_system();
		$web_system = &get_web_system();
		if ((int)$value_destination > 0) {
			# Now, the value_destination should hold the current files id
			$old_fileid = (int)$value_destination;
			$old_file = &$web_system->get_file($old_fileid);
			if($old_file->id) {
				$old_page = &$web_system->get_page($old_file->pageid);
			}
		} else {
			# Fhere is no file
			$old_file = NULL;
			$old_pageid = ((int)$value_destination*-1);
			$old_page = &$web_system->get_page();
		}
		if (!$old_page->id) {
			$root_pageid = &$this->get_value('root_pageid');
			$old_page = &$web_system->get_page($root_pageid);
		}

		$pageid   = $_REQUEST[$prefix.'new_pageid'];
		$fileid   = $_REQUEST[$prefix.'new_fileid'];
		$new_file = &$web_system->get_file((int)$fileid);
		$new_page = &$web_system->get_page($pageid);

		# attempt an upload
		$upload_result = commit_file_upload($prefix.'new_upload', 1, 1, '', $n->data_path, '', $saved_filename);

		# This should mean the was no change to to file
		if($old_file->id == $new_file->id && $old_page->id == $new_page->id && !$upload_result) return;

		# Update the value
		$value_destination = (int) $new_file->id;

		if($new_file->id != $old_file->id) {
			$ms[] = "File changed to $new_file->filename for {$this->attribute->name}.";
		}

		if(!$value_destination && $new_page->id) { # No file specified..
			$ms[] = "No file specified for {$this->attribute->name}.";
			$value_destination = $new_page->id * -1; # Rememeber the page if not the file
		} elseif($new_file->pageid != $new_page->id && $new_page->id) { # Moving file to a different page
			$ms[] = "For {$this->attribute->name}: " . $new_file->set_pageid($new_page->id);
		}

		# All is well.. OR IS IT!?!??!?! They might be uploading a new file.
		$saved_filename = '';
		if($upload_result) { # They've uploaded something !
			$new_filename = $_FILES[$prefix.'new_upload']['name'];
			if($new_file->id) { # replace old file
				if($new_filename == $new_file->filename) {
					# Copy
					copy("$n->data_path/$new_filename", "$new_file->data_path/$new_file->filename");
					$new_file->clear_cache();
				} else {
					$to_filename = $new_filename;
					while(file_exists("$new_file->data_path/$to_filename")) { # Clash!
						$to_filename = increment_filename($to_filename);
					}
					copy("$n->data_path/$new_filename", "$new_file->data_path/$new_file->filename");
					# Rename
					$ms[] = $new_file->set_filename($to_filename);
				}
			} elseif($new_page->id) { # create new file
				# Copy
				$to_filename = $new_filename;
				while(file_exists("$new_page->data_path/$to_filename")) { # Clash!
					$to_filename = increment_filename($to_filename);
				}
				copy("$n->data_path/$new_filename", "$new_page->data_path/$to_filename");
				# Create
				$new_file = new File(0);
				$ms[] = $new_file->create($new_page->id, $to_filename, "", "", "N");
				$value_destination = $new_file->id;
			} else { # Pfft, get outta 'ere
				unlink("$n->data_path/$new_filename");
			}
		}

		return implode("\n",$ms);
	}

	/**
	* Checks the value is valid
	*
	* @param  string &$value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value(&$value, &$rule_broken_msgs) {
		#check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);

		# do normal checks
		$value = (int) $value;
		$web_system = &get_web_system();
		if($value > 0) {
			$file = &$web_system->get_file($value);
			if(!$file->id) $value = 0;
		} else { # Secret encoding of a pageid in negative
			$page = &$web_system->get_page($value * -1);
			if(!$page->id) $value = 0;
		}
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		switch($format) {
			case 'f_exists':
				$match_clause = $value_string.' > 0';
				break;
			default:
				$match_clause = '0=1';
				break;
		}

		return $match_clause;
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		switch($format) {
			case 'f_exists':
				# Search Format type: f_exists
				# print out check box used for searching
				# input used to search for records with files
				if ($default == 1) {
					$value = 'checked';
				} else {
					$value = '';
				}
				?>
				<input type="checkbox" name="<?=$prefix?>q" value="1" <?=$value?>>
				<?
				break;
		}
	}

	/**
	* Sets in the request vars a static search
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		switch($format) {
			case 'f_exists':
				$_REQUEST[$prefix.'q'] = $value;
				break;
		}
	}
}
?>
