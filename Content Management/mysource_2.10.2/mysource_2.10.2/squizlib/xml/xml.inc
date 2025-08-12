<?  ##############################################
   ### SquizLib ------------------------------###
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
## File: csv/csv.inc
## Desc: A class that manages csv files.
## $Source: /home/cvsroot/squizlib/xml/xml.inc,v $
## $Revision: 2.8.2.2 $
## $Author: brobertson $
## $Date: 2004/05/11 09:59:02 $
#######################################################################
# Requires object from squizlib
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/object/object.inc");
#---------------------------------------------------------------------#

class Xml extends Object {
	var $filepath='';
	var $dir='';
	var $filename='';

	# This is a pointer to the tag at the top of the tree
	var $root_tag;

	# Pointer to the current tag we are dealing with
	var $current_tag;
	
	# do we need the ddm? (doc data model)
	var $ddm = false;

	# do we put in new lines?
	var $nl = "\n"; 

	 #############
	# Constructor
	function Xml($filepath='',$ddm=false, $nl = false) {
		if($filepath) {
			# Full path to this file
			$this->filepath = $filepath;

			# Directory of this file
			$this->dir = substr ($filepath,0,(strrpos ($filepath, "/") + 1));
			if (!file_exists($this->dir)) mkdir($this->dir,0775);
			
			# Name of file without full path
			$this->filename = substr( strrchr($filepath,"/") , 1);
		
			if (!file_exists($this->dir)) {
				$this->_set_error("xml directory ($this->dir) does not exist!");
			return false;
			}
		}
		$this->ddm = $ddm;
		
		if ($nl)
			$this->nl = "\n";
		else
			$this->nl = '';
	}

	 ##################################
	# Set the filename of this object
	function set_filename($filename='') {
		$this->filename = $filename;
	}

	 ##################################
	# Set the file of this object
	function set_filepath($filepath='') {
		if($filepath) {
			# Full path to this file
			$this->filepath = $filepath;

			# Directory of this file
			$this->dir = substr ($filepath,0,(strrpos ($filepath, "/") + 1));
			if (!file_exists($this->dir)) mkdir($this->dir,0775);
			
			# Name of file without full path
			$this->filename = substr( strrchr($filepath,"/") , 1);
		
			if (!file_exists($this->dir)) {
				$this->_set_error("xml directory ($this->dir) does not exist!");
			return false;
			}
		}
	}

	 ####################################################################
	# Set the PHP mime header, this will overwrite any previous headers
	function set_header($header='') {
		if($header) {
			# Set custom headers
			header($header);
		} else {
			header("Content-Type: application/xml");
			header("Content-Disposition: filename=".$this->filename.";");
		}
	}

	 #########################################################
	# Export the current values to the current set filepath
	# If no filepath then export to screen
	function export() {
		$fp = 0;
		if(!$this->filepath) {
			# No file to write to.....lets send the header and then the file
			$this->set_header();
		} else {
			if(!$fp = fopen($this->filepath,"w")) {
				$this->_set_error("Unable to open file $this->filepath for writing.",__FILE__,__LINE__);
				return "";
			}
		}

		if($fp) fputs($fp,"<?xml version='1.0' encoding='UTF-8' standalone=\"yes\" ?>".$this->nl);
		else echo "<?xml version='1.0' encoding='UTF-8' standalone=\"yes\" ?>".$this->nl;

		if ($this->ddm) {
			$ddm = $this->generate_ddm();
			if($fp) fputs($fp,$ddm);
			else echo $ddm;
		}

		# Start exporting at the root tag and it will look after everything below it
		$this->root_tag->export($fp);
	}

	function generate_ddm() {
		$ddm = "<!DOCTYPE xmlcopy [".$this->nl;
		$tags = $this->root_tag->get_subtag_names();
		foreach ($tags as $id => $subtag) {
			if (is_array($subtag)) {
				$ddm .= "<!ELEMENT $id (";
				$sub_tags = array_keys($subtag);
				$ddm .= implode(", ",$sub_tags);
				$ddm .= ") >".$this->nl;
			}
		}
		$ddm .= "]>";
		return $ddm;
	}


	 ###################################################
	# Open a new tag with the given name and attributes
	function tag_open($tag='',$attributes=Array()) {
		//make sure it's a valid xml element name., we don't want parse errors!
		$tag = trim($tag);
		$tag = ereg_replace("[^a-zA-Z0-9\.\_\-]","",$tag);
		$tag = ereg_replace("^[^a-zA-Z0-9]","",$tag);
		# If the current tag is not set, then we are at the root
		if (!is_object($this->current_tag)) {
			$this->root_tag = new XmlTag();
			$this->root_tag->set_name($tag);
			$this->root_tag->set_attributes($attributes);
			$this->current_tag = &$this->root_tag;
		} else { # otherwise add a sub-tag and change the current tag pointer
			$this->current_tag->add_subtag($tag,$attributes);
			$this->current_tag = &$this->current_tag->current_tag;
		}
	}

	 ###############
	# Closing a tag
	function tag_close() {
		# Move the current pointer up a level
		$this->current_tag = &$this->current_tag->parent;
	}

	 ###############################
	# Write data to the current tag
	function add_data($data) {
		if(is_object($this->current_tag)) $this->current_tag->add_data($data);
	}
}

class XmlTag extends Object {
	var $data = '';
	var $attributes = array();
	var $name;
	var $tags = array();
	var $parent;

	var $current_tag;

	 ####################################################################
	# Constructor - takes an optional tag to make the parent of this tag
	function XMLTag($parent=NULL) {
		if(is_object($parent)) {
			$this->parent = &$parent;
		}
	}

	 ###########################################
	# Add a subtag and make it the current tag
	function add_subtag($name='',$attributes='') {
		$tag = new XMLTag(&$this);
		$tag->set_name($name);
		if (is_array($attributes)) {
			$tag->set_attributes($attributes);
		}
		# Append the subtag to this tags
		$this->tags[] = &$tag;
		$this->current_tag = &$tag;
	}

	function get_subtag_names() {
		$elements = array();
		$subtag_keys = array_keys($this->tags);
		# Grab each subtag
		foreach($subtag_keys as $k) {
			$elements[$this->tags[$k]->name] = $this->tags[$k]->get_subtag_names();
		}
		return $elements;
	}

	 ############################
	# How many subtags do I have
	function num_subtags() {
		return sizeof($this->tags);
	}

	 #####################################
	# Lets add an attribute to this tag
	function add_attribute($name='',$val=''){
		$this->attributes[strtolower($name)] = $val;
	}

	 ############################
	# Set the name of this tag
	function set_name($name='') {
		$this->name = strtolower($name);
	}

	 ###############################
	# Set the attributes of this tag
	function set_attributes($attributes='') {
		$this->attributes = $attributes;
	}

	 #########################
	# Add some data in this tag
	function add_data($data='') {
		$this->data .= (string) $data;
	}

	 ###########################
	# Delete all data
	function clear_data() {
		$this->data = "";
	}

	 #######################################
	# Lets write this tag to the file
	function export($fp='') {
		# Get the attribute string
		$attributes = Array();
		foreach($this->attributes as $key => $val) {
			$attributes[] = strtolower($key) . "=\"$val\"";
		}

		$attributes_string = (!empty($attributes)) ? join(" ",$attributes) : '';

		# Write out the start element
		$tag_string = "<{$this->name}";
		if ($attributes_string) $tag_string .= " ".$attributes_string;

		$num_subtags = $this->num_subtags();
		# If there is no data in this tag then print a newline
		# If there is no data, and no subtags, then close this tag 
		$data = trim($this->encodeXmlEntities($this->data));
		if ($num_subtags && ($data == "")) {
			$tag_string .= ">".$this->nl;
		} elseif (!$num_subtags && ($data == "")) {
			$tag_string .= "/>".$this->nl;
		} else {
			$tag_string .= ">";
		}

		if($fp) fwrite($fp,$tag_string);
		else echo $tag_string;

		# Write out the data if it is not purely whitespace
		if ($data != "") {
			if($fp) fwrite($fp,$data);
			else echo $data;
		}

		$subtag_keys = array_keys($this->tags);
		# Write out each subtag
		foreach($subtag_keys as $k) {
			$this->tags[$k]->export($fp);
		}

		# Write out the end element if necessary
		if ($this->num_subtags() || ($data != "")) {
			$tag_string = "</{$this->name}>".$this->nl;
			if($fp) fwrite($fp,$tag_string);
			else echo $tag_string;
		}
	}


	/**
	* Escape XML entities.
	* Taken straight from PEAR::XML_Tree_Node
	*
	* @param   string  xml      Text string to escape.
	*
	* @return  string  xml
	* @access  public
	*/
	function encodeXmlEntities($xml)
	{
		$xml = str_replace(array('ü', 'Ü', 'ö',
								 'Ö', 'ä', 'Ä',
								 'ß', '<', '>',
								 '"', '\''
								),
						   array('&#252;', '&#220;', '&#246;',
								 '&#214;', '&#228;', '&#196;',
								  '&#223;', '&lt;', '&gt;',
								  '&quot;', '&apos;'
								),
						   $xml
						  );

		$xml = preg_replace(array("/\&([a-z\d\#]+)\;/i",
								  "/\&/",
								  "/\#\|\|([a-z\d\#]+)\|\|\#/i",
								  "/([^a-zA-Z\d\s\<\>\&\;\.\:\=\"\-\/\%\?\!\'\(\)\[\]\{\}\$\#\+\,\@_])/e"
								 ),
							array("#||\\1||#",
								  "&amp;",
								  "&\\1;",
								  "'&#'.ord('\\1').';'"
								 ),
							$xml
						   );

		return $xml;
	}# end encodeXmlEntities()

}
?>
