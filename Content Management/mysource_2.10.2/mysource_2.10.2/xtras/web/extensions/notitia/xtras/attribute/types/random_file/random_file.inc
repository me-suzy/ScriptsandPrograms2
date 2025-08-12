<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/random_file/random_file.inc,v $
## $Revision: 1.5 $
## $Author: nduggal $
## $Date: 2004/01/16 06:25:35 $
#######################################################################

#---------------------------------------------------------------------#
global $SQUIZLIB_PATH;
include_once ("$SQUIZLIB_PATH/general/general.inc");

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Type_Random_File extends Web_Extension_Notitia_Attribute_Type {
	
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
				#'upload_only' => 'Upload Box Only'
			),
			'sort_formats' => array (
				'raw' => 'By File ID',
				'raw<' => 'By File ID Desc.'
			),
			'rule_types' => array (
			),
			'requires_context_values' => false,
			'sub_type_name' => 'random_file',
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
		if(!$format) $format = 'url_link';
		$web_system = &get_web_system();

		# pick a random file to display
		$fileids = array_values(unserialize($value));
		$fileid = $fileids[rand(0, count($fileids) - 1)];
		
		if($format) {
			$file = &$web_system->get_file($fileid);
			if(!$file->id) {
				if (in_array($format,array('name','url_link','href_link'))) {
					return '[File not found.]';
				} else {
					return '';
				}
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
				return "<a href=\"".$file->get_href()."\">$file->filename</a>";
			case 'url_link':
				return "<a href=\"".$file->get_url()."\">$file->filename</a>";
			case 'description':
				return $file->description;
			case 'keywords':
				return $file->keywords;
			case 'extension':
				return $file->get_file_type();
			case 'type':
				$file_types_config = &get_config("file_types");
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
				return "<img src=\"".$file->get_href()."\" alt=\"\" border=0>";
			case 'image_tag_left':
				return "<img src=\"".$file->get_href()."\" alt=\"\" border=0 align=left>";
			case 'image_tag_right':
				return "<img src=\"".$file->get_href()."\" alt=\"\" border=0 align=right>";
			case 'id': default: # File ID
				return $fileid;
		}
	}
		
	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		$web_system =& $this->web_system;
		$root_pageid = $this->get_value('root_pageid');
		$root_page = &$web_system->get_page ($root_pageid);

		if (strlen($value)) {
			$file_list = unserialize($value);
		} else {
			$file_list = array();
		}

		foreach ($root_page->file_index as $fileid) {
			$file = &$web_system->get_file($fileid);
			$file_options[$file->id] = $file->filename;
		}

		$height = (((count($file_options) + 1) > 7)?8:(count($file_options) + 1));

		?>
		<table>
			<tr>
				<?
				if ($height > 1) {
					?><td><?=multiple_combo_box ($prefix."file_list", $file_options, $file_list,'' ,'' , $height)?></td><?
				} else {
					?><td><b>There are no files attached to the page</b></td><?
				}
				?>
			</tr>
			<tr>
				<td><b>Page: </b><?=$root_page->name?> <a href="page.php?p=<?=$root_page->id?>&PARAMETER_SCREEN=page_attachments">*</a></td>
			</tr>
			<tr>
				<td><?=file_upload ($prefix."new_file");?></td>
			</tr>
		</table>
		<?
		
	}


	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value("note")) {
			echo("<span class=smallprint><br>$note</span>");
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
		# get file ids from the file list
		$old_fileids = unserialize ($value_destination);
		$fileids = $_POST[$prefix.'file_list'];

		if (!equal_arrays(array_values($fileids), array_values($old_fileids)))
			$msg[] = "Updated file list.";
		
		# get the root page
		$notitia = &$this->get_notitia_system();
		$web_system = &$this->web_system;
		$root_page = &$web_system->get_page ($this->get_value ('root_pageid'));
		
		# get uploads and put it in the page's data path, auto incrementing the file name if necessary
		$upload_result = commit_file_upload($prefix.'new_file', false, true, false, $root_page->data_path,'', $saved_filename);
		if ($upload_result) {
			$msg[] = $upload_result;

			# create a new file in MySource and link it with the file system file in the root page's data path
			$new_file = new File(0);
			$msg[] = $new_file->create($root_page->id, $saved_filename, '', '', "N");

			# add this file to the file list
			$fileids[] = $new_file->id;
		}

		# update the file list
		$value_destination = serialize ($fileids);
		$m = (join ("\n", $msg));
		return $m;
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
		# Check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);

		# Do normal checks
		$web_system = &get_web_system();
		$file_list = &$this->parameters['file_list'];

		foreach ($this->get_value ('file_list') as $fileid_i => $fileid)  {
			$file = &$web_system->get_file($fileid);
			if(!$file->id) {
				unset ($file_list[$fileid_i]);
			}
		}
	}
}
?>