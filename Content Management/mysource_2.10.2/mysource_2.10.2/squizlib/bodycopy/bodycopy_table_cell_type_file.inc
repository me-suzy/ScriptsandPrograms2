<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
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
## Desc: This allows the editing of the Files in a table cell
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy_table_cell_type_file.inc,v $
## $Revision: 2.23 $
## $Author: dofford $
## $Date: 2004/02/23 02:29:05 $
#######################################################################
global $INCLUDE_PATH;
include_once(dirname(__FILE__).'/bodycopy_table_cell_type.inc');
include_once($INCLUDE_PATH.'/file.inc');
#---------------------------------------------------------------------#

 ####################################################################
# An array of the available cell types (class_name => display_name)
$_BODYCOPY['TABLE_CELL']['types']['bodycopy_table_cell_type_file']
		= Array('name' => 'File/Image', 
				'description' => 'This allows the embedding of Shockwave Flash files or Quicktime Movies, the linking of attached files to table cells or the displaying of images within the cell');

   ####################################################################
  # this indicates to the class when it's printing the edit interface
 # whether another instance of it has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['TABLE_CELL_TYPE']['file']['edit_initialised'] = false;


/** 
* @access public
* @package Bodycopy
*/
class BodyCopy_Table_Cell_Type_File extends BodyCopy_Table_Cell_Type {
	
	var $fileid = ''; # the file we want to display
	var $embed  = 1;  # boolean indicating whether the file should be embedded or not
	var $embed_options = Array('width' => 0, 'height' => 0, 'auto_start' => 1, 'show_controls' => 1, 'loop' => 1);
	var $html = '';  # raw html code used for converting this file type


	 ##############################
	# Constructor
	function BodyCopy_Table_Cell_Type_File() {
	}#end constructor

	 ##############################
	# called un unserialisation
	function __wakeup() {

		 ##############################################################################
		# for backwards compatibilty see if there is an embed_width or embed_height
		# and set the embed_options vars for them if so
		if (isset($this->embed_width)) {
			if (!empty($this->embed_width)) {
				$this->embed_options['width'] = $this->embed_width;
			}
			unset($this->embed_width);
		}
		if (isset($this->embed_height)) {
			if (!empty($this->embed_height)) {
				$this->embed_options['height'] = $this->embed_height;
			}
			unset($this->embed_height);
		}

	}#end __wakeup()

	 #########################################
	# set the fileid variable
	function set_fileid($fileid=0) {
		if ($fileid == 'blankfileid') $fileid = '';
		if ($this->fileid == $fileid) return '';
		$this->fileid = $fileid;
		return 'File updated';
	}

	 #########################################
	# set the embed variable
	function set_embed($embed=false) {
		# convert to boolean
		$embed = ($embed) ? 1 : 0;
		if ($this->embed == $embed) return '';
		$this->embed = $embed;
		return 'File Embedding updated';
	}

	 #########################################
	# set the embed options 
	function set_embed_options($options=array()) {

		$msgs = Array();
		if (!empty($options)) {
			foreach($options as $k => $v) {
				# make sure that the height and width are just ints
				if ($k == 'width' || $k == 'height') {
					$suffix = (strstr($v, '%')) ? '%' : '';
					$v      = intval($v).$suffix;
				}
				# only change if the values are different
				if ($this->embed_options[$k] != $v) {
					$this->embed_options[$k] = $v;
					$msgs[] = 'File Embed '.ucwords(str_replace('_', ' ', $k)).' updated';
				}

			}#end foreach
		}

		return $msgs;
	}

	 ##############################
	# Delete this bastard
	function delete() {
		unset($this);
		$this = new BodyCopy_Table_Cell_Type_File();
		return true;
	}#end delete()

	 ###################################
	# paints the html
	function paint($from_backend=false, &$keyword_replacements) {

		$web = &get_web_system();
		$file = &$web->get_file($this->fileid);
		if ($file->id) {

			$link = ($GLOBALS['ABSOLUTE']) ? $file->get_url() : $file->get_href();

			if (is_image($file->data_path.'/'.$file->filename)) {

				$image_size = getImageSize($file->data_path.'/'.$file->filename);
				?><img src="<?=$link?>" <?=$image_size[3]?> border="0" alt="<?=$file->description?>"><?
				return;

			# else if we wan't to try and embed the file see if we know what to do with it
			} elseif($this->embed) {

				$width  = ($this->embed_options['width'])  ? 'width="'.$this->embed_options['width'].'"'   : '';
				$height = ($this->embed_options['height']) ? 'height="'.$this->embed_options['height'].'"' : '';

				switch (strtolower(get_file_type($file->filename))) {
					# Quicktime Movie
					case 'mov' :
						if ($from_backend) {
							$this->_paint_embedded_blank();
						} else {
							?><embed src="<?=$link?>" controller="<?=($this->embed_options['show_controls']) ? 'true' : 'false'?>" cache="true" loop="<?=($this->embed_options['loop']) ? 'true' : 'false'; ?>" <?=$width?> <?=$height?>><?
						}#end if
						return;
					break;

					# Shockwave Flash
					case 'swf' :

						if ($from_backend) {
							$this->_paint_embedded_blank();
						} else {
							?><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0" <?=$width?> <?=$height?>>
								<param name="movie"   value="<?=$link?>">
								<param name="quality" value="high">
								<param name="scale"   value="exactfit">
								<embed src="<?=$link?>" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"  <?=$width?> <?=$height?> scale="exactfit"></embed>
							</object><?

						}#end if

						return;

					break;

					# Windows Media File
					case 'wmv' :
					case 'asf' :
					case 'asx' :

						if ($from_backend) {
							$this->_paint_embedded_blank();
						} else {
							?><object id="WinMedia" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
							  codebase= "http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
							  <?=$width?> <?=$height?> 
							  standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
								<param name="FileName"     value="<?=$link?>">
								<param name="AutoStart"    value="<?=($this->embed_options['auto_start'])    ? 'true' : 'false'; ?>">
								<param name="ShowControls" value="<?=($this->embed_options['show_controls']) ? 'true' : 'false'; ?>">
								<param name="loop"         value="<?=($this->embed_options['loop']) ? 'true' : 'false';?>">
								<embed type="application/x-mplayer2" 
									pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" 
									src="<?=$link?>" 
									name="MediaPlayer" 
									AutoStart="<?=($this->embed_options['auto_start'])       ? 'true' : 'false'; ?>" 
									ShowControls="<?=($this->embed_options['show_controls']) ? 'true' : 'false'; ?>" 
									loop="<?=($this->embed_options['loop']) ? 'true' : 'false'; ?>"
									<?=$width?> 
									<?=$height?> 
								></embed>
							</object><?
						}#end if
						return;
					break;
				} #end switch


			}#end if

			# if we got this far, then it ain't any of the above, so print out the file information
			?> 
				<p style="font-size: 7pt">
				<b><a target="_blank" href="<?=$link?>" onmouseover="window.status='<?=str_replace("\n", "", addslashes($file->description))?>';return true;" onmouseout="window.status='';return true;"><?=$file->filename?></a></b><br>
				<b>Size:</b> <?=easy_filesize($file->size)?><br>
				<b>Updated:</b> <?=readable_datetime($file->modified)?><br>
			<?
			if ($file->description) {
				?><b>Desc:</b> <?=$file->description?><br><?
			}
			return;

		} else {
			?><img src="images/blank.gif" width="1" height="1" border="0"><?
			return;
		}#end if

	}#end paint()

	 ##################################################################################################
	# paints an image to the size of the embedded object rather than print the actual embedded object
	function _paint_embedded_blank() {
		global $_BODYCOPY;
		$width  = ($this->embed_options['width'])  ? 'width="'.$this->embed_options['width'].'"'   : '';
		$height = ($this->embed_options['height']) ? 'height="'.$this->embed_options['height'].'"' : '';
		?><img src="<?=$_BODYCOPY['file_prefix']?>/images/embedded_object.gif" <?=$width?> <?=$height?> border="1"><?

	}#end _paint_embedded_blank()


	 #################################################
	# Given a map of file and page ids from old to new
	# (usually during duplication of sites or pages)
	# this will change all the links etc in the bodycopy
	# to reflect the mapping
	function remap_link_ids(&$map) {
		if(isset($map['file'][$this->fileid]))
			$this->fileid = $map['file'][$this->fileid];
	}


	 #####################################################
	# Given a fileid, cell type checks its contents to
	# see if the file is used
	function uses_file($fileid=0) {
		if ($this->fileid == $fileid) return true;
		return false;
	}


	 #########################################################################
	# returns true if there is nothing to print (ie no content) for the cell
	function empty_cell() {
		if ($this->fileid) {
			$web = &get_web_system();
			$file = &$web->get_file($this->fileid);
			return ($file->id <= 0);
		} else {
			return true;
		}
		
	}#end empty_cell()


	  ###########################################################
	 # prints the JavaScript needed by the backend functions for 
	# this table 
	function print_backend_js_data($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		?> 
			retVal["fileid"]        = var_serialise('<?=$this->js_string_escape($this->fileid, '\'')?>');
			retVal["embed"]         = var_serialise('<?=$this->js_string_escape($this->embed,  '\'')?>');
			retVal["embed_options"] = '<?=$this->js_string_escape(var_serialise($this->embed_options), '\'')?>';
		<?
	}#end print_backend_js_data()

	 ############################################################
	# function to initialise some things need by this cell type
	# NOTE : should be able to be called without an instance of the object
	#        ie. NO references to $this
	#        eg.  BodyCopy_Table_Cell_Type_SOMENAME::init_backend();
	function init_backend() {
		global $_BODYCOPY;
		
		# do some thing for the first time we print this object on this page
		if (!$_BODYCOPY['TABLE_CELL_TYPE']['file']['edit_initialised']) {
			?><script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_table_cell_type_file.js"></script><?
			$_BODYCOPY['TABLE_CELL_TYPE']['file']['edit_initialised'] = true;

		}#endif

	}#end init_backend()

	 #####################################################
	# prints the backend interface for this table cell
	function print_backend($this_bodycopy_name='', $tableid=0, $rowid=0, $cellid=0) {
		
		# initilaise the backend for this cell type
		$this->init_backend();

		# print out the edit button
		print_bodycopy_icon("javascript: bodycopy_edit_table_cell_type_file('$this_bodycopy_name', $tableid, $rowid, $cellid);", 'Edit the Contents of Cell '.($cellid + 1), '', 'edit_content');

	}#end print_backend()

	 #########################################################
	# process the input created by print_backend()'s popup
	function update($this_bodycopy_name='bodycopy', $tableid=0, $rowid=0, $cellid=0) {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved; 
		global $_BODYCOPY;

		$msg = Array();

		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['fileid']) != '') {
			if ($tmp_msg = $this->set_fileid($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['fileid'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}
		if (trim($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['embed']) != '') {
			if ($tmp_msg = $this->set_embed($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['embed'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}
		if ($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['embed_options']) {
			if ($tmp_msg = $this->set_embed_options($bodycopy_saved[$this_bodycopy_name][$tableid]['rows'][$rowid]['cells'][$cellid]['embed_options'])) {
				$msg = array_merge($msg, $tmp_msg);
			}
		}

		# save the html so this cell can be converted
		# to another cell type later
		ob_start();
		$this->paint();
		$this->html = ob_get_contents();
		ob_end_clean();

		return $msg;

	}#end update()

}#end Class BodyCopy_Table_Cell_Type_File

?>
