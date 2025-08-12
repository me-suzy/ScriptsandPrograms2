<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/export/xml/xml.inc,v $
## $Revision: 1.3 $
## $Author: achadszinow $
## $Date: 2003/11/13 06:31:56 $
#######################################################################

#---------------------------------------------------------------------#

global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/xml/xml.inc");

/**
* Export records/categories in XML format to file
*/
class Web_Extension_Notitia_Data_Export_XML extends Web_Extension_Notitia_Data_Export {

	var $paramaters=array();
	var $categoryid = 0;

	/**
	* Constructor
	*
	* @access public
	* @return void
	*/
	function Web_Extension_Notitia_Data_Export_XML($categoryid) {
		parent::Web_Extension_Notitia_Object();
		$this->categoryid = $categoryid;

		$session = &get_mysource_session();
		# OK what we are doing here is being a bit tricky we are going to set our parameters 
		# array to a reference to the var in the session, that way we can just reference it as 
		# we need it, PLUS when we process the param set the stuff get's put into the session
		# var and we don't have problems with overwriting arrays
		if ($session->has_var('notitia_export_data_parameters_xml')) {
			$this->parameters = &$session->get_var('notitia_export_data_parameters_xml');
		} else {
			$this->parameters = array();
			$session->set_var('notitia_export_data_parameters_xml', $this->parameters);
		}
	}

	/**
	* Gets the backend href
	*
	* @access public
	* @returns string
	*/
	function get_backend_href() {
		if($this->categoryid) $e = &$this->get_category($this->categoryid);
		else                  $e = &$this->get_notitia_system();
		return $e->get_backend_href()."&data_export=$this->codename";
	}

	/**
	* Wake up with this handy information EVERY day !
	*
	* @access private
	* @returns void
	*/
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$this->set_generated_information();
	}

	/**
	* Set's up some useful information that shouldn't be cached
	*
	* @access public
	* @returns void
	*/
	function set_generated_information() {
		$n     = &$this->get_notitia_system();
		$xtras = &$n->get_xtras();
		$this->codename   = $xtras->codename("data/export",ereg_replace('^'.get_parent_class($this).'_','',get_class($this)));
		$this->name      = &$xtras->name("data/export",$this->codename);
		$this->xtra_path = "$n->xtra_path/xtras/data/export/$this->codename";
	}

	/**
	* Gets the value in the parameters
	*
	* @param string $code The name
	* @access public
	* @return mixed
	*/
	function &get_value($name) {
		return $this->parameters[$name];
	}

	/**
	* Export a category and it's subs and records to XML
	*
	* @param &object $xml Xml object
	* @param int $categoryid Categoryid to start from
	* @param mixed $restrict_recordids Can be an array of recordids or false in the case it's not in use
	* @access public
	* @returns void
	*/
	function export_category(&$xml, $categoryid, $restrict_recordids=false) {
		$tree = &$this->get_category_tree($categoryid);
		$category = &$this->get_category($categoryid);

		$recordids = $category->associated_records();
		if (is_array($restrict_recordids)) {
			$recordids = array_intersect($recordids, $restrict_recordids);
		}

		$subcategoryids = array_keys($tree->child_list($categoryid));

		$category_metadata = array();
		if ($this->get_value('show_categoryids')) {
			$category_metadata['categoryid'] = $category->id;
		}
		if ($this->get_value('show_category_name')) {
			$category_metadata['name'] = $category->name;
		}
		if ($this->get_value('show_category_description')) {
			$category_metadata['description'] = $category->description;
		}

		$xml->tag_open($this->get_category_tag_name($categoryid), $category_metadata);

		foreach ($recordids as $recordid) {
			$this->export_record($xml, $category, $recordid);
		}

		foreach ($subcategoryids as $subcategoryid) {
			$this->export_category($xml, $subcategoryid, $restrict_recordids);
		}

		$xml->tag_close();
	}

	/**
	* Export a category and it's subs and records to XML
	*
	* @param &object $xml Xml object
	* @param array $recordids The recordids
	* @access public
	* @returns void
	*/
	function export_records(&$xml, $recordids) {
		$category = &$this->get_category($this->categoryid);
		$category_metadata = array();
		if ($this->get_value('show_categoryids')) {
			$category_metadata['categoryid'] = $category->id;
		}
		if ($this->get_value('show_category_name')) {
			$category_metadata['name'] = $category->name;
		}
		if ($this->get_value('show_category_description')) {
			$category_metadata['description'] = $category->description;
		}

		$xml->tag_open($this->get_category_tag_name($categoryid), $category_metadata);

		if (!empty($recordids)) {
			foreach ($recordids as $recordid) {
				$this->export_record($xml, $category, $recordid);
			}
		}

		$xml->tag_close();
	}

	/**
	* Exports a single record to xml
	*
	* @param &object $xml Xml object
	* @param &object $category The category
	* @param int $recordids The recordid to export to xml
	* @access public
	* @returns void
	*/
	function export_record(&$xml, &$category, $recordid) {
		$record = $this->get_record($recordid);
		$elements = $this->get_value('record_elements');
		$record_metadata = array();

		if ($this->get_value('show_recordids')) {
			$record_metadata['recordid'] = $recordid;
		}

		$xml->tag_open($this->get_record_tag_name($category->id), $record_metadata);
		$combined_formats = array_keys($elements);
		foreach($combined_formats as $combined_format) {
			$this->export_element($xml, $record, $combined_format);
		}

		$xml->tag_close();
	}

	/**
	* Exports a single element of the record
	*
	* @param &object $xml Xml object
	* @param &object $record The record
	* @param string $combined_format The format to export the attribute value
	* @access public
	* @returns void
	*/
	function export_element(&$xml, &$record, $combined_format) {
		$attributeid = $this->get_attributeid($combined_format);
		$attribute =& $this->get_attribute($attributeid);
		$value_metadata = array();
		if ($this->get_value('show_attributeids')) {
			$value_metadata['attributeid'] = $attributeid;
		}

		$format = $this->get_attribute_format($combined_format);
		
		$xml->tag_open($this->get_attribute_tag_name($combined_format), $value_metadata);
		$xml->add_data ($record->get_attribute_formatted_value($attributeid, $format));
		$xml->tag_close();
	}

	/**
	* Returns the backend object after setting it up in a standard kinda way
	*
	* @access public
	* @returns &object
	*/
	function &setup_backend() {
		$e       = &$this->get_notitia_system();
		$xtras   = &$e->get_xtras();
		$backend = &$this->get_backend();
		$backend->set_tab(get_class($this),$this->get_backend_href(),"Data Export: $this->name",$xtras->description("data/export",$this->codename),"page");
		$backend->set_active_tab(get_class($this));
		$backend->set_subheading($this->name);
		$backend->set_hidden_field('data_export',$this->codename);
		return $backend;
	}

	/**
	* Prints the backend 
	*
	* @access public
	* @returns void
	*/
	function print_backend() {
		if ($_REQUEST['action'] == 'export') {
			if (!$this->do_export()) {
				parent::print_backend();
			}
		} else {
			parent::print_backend();
		}
	}

	/**
	* Do the exporting
	*
	* @param mixed $restrict_recordids Can be an array of recordids or false in the case it's not in use
	* @param boolean $export_categories Whether to export the category structure with it
	* @access public
	* @returns void
	*/
	function do_export($restrict_recordids=false, $categoryid=0, $param) {
		if (is_array($param)) {
			$this->parameters = $param;
		}

		if ($categoryid) {
			$this->categoryid = $categoryid;
		}
		$path = '';
		$xml = new Xml($path);
		$xml->set_filename('export-'.date("Ymd-Hi").'.xml');

		if ($this->get_value('export_category_structure') && $this->categoryid) {
			$this->export_category($xml, $this->categoryid, $restrict_recordids);
		} elseif (is_array($restrict_recordids)) {
			$this->export_records($xml, $restrict_recordids);
		} else {
			return false;
		}
		$xml->export();
		return true;
	}

	/**
	* Prints the download button
	*
	* @access public
	* @returns void
	*/
	function print_download() {
		$backend = &$this->get_backend();
		?>
			<a href="" onClick="
				document.edit.action.value ='export';
				document.edit.submit();
				return false;
			" onMouseOver="window.status='Export to XML'; return true;" onMouseOut="window.status='';return true;"><img src="<?=$backend->rel_path?>/images/icons/create.gif" border=0></a>
		<?
	}

	/**
	* Prints the big box of tag names, whatever they may be
	*
	* @access public
	* @returns void
	*/
	function backend_print_category_tag_names() {
		$this->print_category_text_fields('category_tag_names', $this->get_category_tag_name_list());
	}

	/**
	* Process the category names box
	*
	* @access public
	* @returns void
	*/
	function backend_process_category_tag_names() {
		$tree = &$this->get_category_tree();
		$descendants = array_merge($this->categoryid, $tree->all_descendantids($this->categoryid));

		$tag_names = &$this->get_value('category_tag_names');
		$tag_names = array();

		foreach ($descendants as $categoryid) {
			$post = &$_POST['category_tag_names'][$categoryid];
			$tag_name = &$tag_names[$categoryid];

			if (isset($post)) {
				$tag_name = $post;
			} else {
				$tag_name = $this->get_category_tag_name($categoryid);
			}
		}
	}

	/**
	* Gets the category tag list from the parameters and if it doesn't find it it creates one
	*
	* @access public
	* @returns array
	*/
	function get_category_tag_name_list() {
		if (isset($this->parameters['category_tag_names'])) {
			return $this->get_value('category_tag_names');
		}

		$tree = &$this->get_category_tree();
		$descendants = array_merge($this->categoryid, $tree->all_descendantids($this->categoryid));

		$out = array();

		foreach ($descendants as $categoryid) {
			$category = &$this->get_category($categoryid);
			$out[$categoryid] = $this->conform_xml_tag($category->name);
		}
		return $out;
	}

	/**
	* Prins the individual text field for category text labels
	*
	* @param string $name The name
	* @param array $defaults The values that have already been set
	* @param int $categoryid The categoryid to start from
	* @param int $depth The depth limit
	* @access public
	* @returns void
	*/
	function print_category_text_fields($name, $defaults, $categoryid=0, $depth=0) {
		if (!$categoryid) {
			$categoryid = $this->categoryid;
		}

		$tree = &$this->get_category_tree();
		$category = &$this->get_category($categoryid);
		$child_categoryids = $tree->get_descendantids_at_depth($categoryid, 1);
		
		for($i = 0; $i < $depth; ++$i) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		
		?>
		<b><?=$category->name?></b> : <?= text_box($name."[".$categoryid."]", $defaults[$categoryid])?><br />
		<?
		
		foreach ($child_categoryids as $child_categoryid) {
			$this->print_category_text_fields($name, $defaults, $child_categoryid, $depth + 1);
		}
	}

	/**
	* Prints the tag names for records
	*
	* @access public
	* @returns void
	*/
	function backend_print_record_tag_names() {
		$this->print_category_text_fields('record_tag_names', $this->get_record_tag_name_list());
	}

	/**
	* Process any tag name changes
	*
	* @access public
	* @returns void
	*/
	function backend_process_record_tag_names() {
		$tree = &$this->get_category_tree();
		$descendants = array_merge($this->categoryid, $tree->all_descendantids($this->categoryid));

		$tag_names = &$this->get_value('record_tag_names');
		$tag_names = array();

		foreach ($descendants as $categoryid) {
			$post = &$_POST['record_tag_names'][$categoryid];
			$tag_name =& $tag_names[$categoryid];

			if (isset($post)) {
				$tag_name = $post;
			} else {
				$tag_name = $this->get_record_tag_name($categoryid);
			}
			
			$tag_name = $this->conform_xml_tag($tag_name);
		}
	}

	/**
	* Gets a category tag name from the saved list
	*
	* @access public
	* @returns string
	*/
	function get_category_tag_name($categoryid) {
		$category_tag_names = $this->get_value('category_tag_names');
		if (!($tag_name = $category_tag_names[$categoryid])) {
			$category = &$this->get_category($categoryid);
			$tag_name = $category->name;
		}
		return $this->conform_xml_tag($tag_name);
	}

	/**
	* Gets a record tag name from the saved list
	*
	* @access public
	* @returns string
	*/
	function get_record_tag_name($categoryid) {
		$record_tag_names = $this->get_value('record_tag_names');
		if (!($tag_name = $record_tag_names[$categoryid])) {
			$tag_name = 'record_'.$categoryid;
		}
		return $this->conform_xml_tag($tag_name);
	}

	/**
	* Makes sure the tags conform to xml object
	*
	* @param string $tag_name The name that is wished
	* @access public
	* @returns string
	*/
	function conform_xml_tag($tag_name) {
		$tag_name = trim(strtolower($tag_name));
		$tag_name = ereg_replace("[^a-zA-Z0-9\.\_\-]","",$tag_name);
		$tag_name = ereg_replace("^[^a-zA-Z0-9]","",$tag_name);
		return $tag_name;
	}

	/**
	* Gets the attributeid given a combined format
	*
	* @param string $combined_format The combined format
	* @access public
	* @returns string
	*/
	function get_attributeid($combined_format) {
		list($attibuteid, $format) = explode('_', $combined_format, 2);
		return $attibuteid;
	}

	/**
	* Gets the tag name given a combined format. It gets it from the saved list
	*
	* @param string $combined_format The combined format
	* @access public
	* @returns string
	*/
	function get_attribute_tag_name($combined_format) {
		$record_elements = $this->get_value('record_elements');
		if (!($label = $record_elements[$combined_format])) {
			$attributeid = $this->get_attributeid($combined_format);
			$attribute = &$this->get_attribute($attributeid);
			$label = $attribute->name;
		}
		return $this->conform_xml_tag($label);
	}

	/**
	* Gets the format given a combined format
	*
	* @param string $combined_format The combined format
	* @access public
	* @returns string
	*/
	function get_attribute_format($combined_format) {
		list($attibuteid, $format) = explode('_', $combined_format, 2);
		return $format;
	}

	/**
	* Gets the record tag name list and if one doesn't exist it makes one up
	*
	* @access public
	* @returns array
	*/
	function get_record_tag_name_list() {
		if (isset($this->parameters['record_tag_names'])) {
			return $this->get_value('record_tag_names');
		}

		$tree = &$this->get_category_tree();
		$descendants = array_merge($this->categoryid, $tree->all_descendantids($this->categoryid));
		
		$out = array();

		foreach ($descendants as $categoryid) {
			$out[$categoryid] = 'record_'.$categoryid;
		}
		return $out;
	}

	/**
	* Prints out the exportable attributes
	*
	* @access  public
	* @returns void
	*/
	function print_exportable_attribute_list() {
		$category = &$this->get_category($this->categoryid);
		if($category->id) {
			echo 'Select attributes that will be exported. The text entry area is the column name it will export to and the second is the order in which the columns will be placed';
			$exportable_attributes = $this->get_value('record_elements');
			$category->print_attribute_format_list_selection_form('export_attributes', $exportable_attributes, 'print', 1);
		}
	}

	/**
	* Processes any calls from print_exportable_attribute_list()
	*
	* @access  public
	* @returns void
	*/
	function process_exportable_attribute_list() {
		$category = &$this->get_category($this->categoryid);
		if($category->id) {
			$exportable_attributes = &$this->get_value('record_elements');
			$exportable_attributes = $category->process_attribute_format_list_selection_form('export_attributes', $exportable_attributes);
		}
	}

	/**
	* Sets the value in the parameters
	*
	* @param string $code The name
	* @param mixed $value The value
	* @access public
	* @return boolean
	*/
	function &set_value($code,$value) {
		return $this->parameters[$code] = $value;
	}
}
?>