<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/export/csv/csv.inc,v $
## $Revision: 1.5 $
## $Author: dofford $
## $Date: 2004/02/20 05:53:15 $
#######################################################################

#---------------------------------------------------------------------#

global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/csv/csv.inc");

 ##############################################################
# Abstract base class for extensions which can export record data
# from the notitia system.
class Web_Extension_Notitia_Data_Export_CSV extends Web_Extension_Notitia_Data_Export {

	var $paramaters;
	var $categoryid = 0;


	/*
	 * Constructor
	 *
	 * @access public
	 */
	function Web_Extension_Notitia_Data_Export($categoryid) {
		parent::Web_Extension_Notitia_Object();
		$this->categoryid = $categoryid;
	}

	function get_backend_href() {
		if($this->categoryid) $e = &$this->get_category($this->categoryid);
		else                  $e = &$this->get_notitia_system();
		return $e->get_backend_href()."&data_export=$this->codename";
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element("temp",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$this->set_generated_information();
		$this->parameters = array();
	}

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$this->codename   = $xtras->codename("data/export",ereg_replace('^'.get_parent_class($this).'_','',get_class($this)));
		$this->name      = &$xtras->name("data/export",$this->codename);
		$this->xtra_path = "$e->xtra_path/xtras/data/export/$this->codename";
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$e = &$this->get_notitia_system();
			$pset = new Parameter_Set(get_class($this), "$this->xtra_path/$this->codename.pset", $this->parameters, $this);
		}
		return $pset;
	}


	 ################################################
	# Returns the backend object after setting it up 
	# in a standard kinda way
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
	* Export records in CSV format to file
	*
	* @param array $recordids - array of record ids to export
	* @param array $attribute_formats - array of attribute formats
	* @access public
	* @returns void
	*/
	function export_records($recordids, $attribute_formats) {
		# print the header - implement me
		$csv = new Csv();

		# Make the header row
		$header_columns = array();
		foreach ($attribute_formats as $format_descriptor) {
			array_push($header_columns, $format_descriptor['column_name']);
		}
		$csv->set_field_headers($header_columns);
		$csv->set_filename('export-'.date("Ymd-Hi").'.csv');
		$rows = $this->export_records_rows($recordids, $attribute_formats);
		$csv->set_values($rows);
		$csv->export();
	}

	/**
	* Export records in CSV row format.
	*
	* @param Array $recordids - array of record ids to export
	* @returns array - a list of CSV rows
	* @access public
	*/
	function export_records_rows($recordids, $attribute_formats, $cat_labels=array()) {
		$tree =& $this->get_category_tree();
		$csv_record_rows = array();

		foreach ($recordids as $recordid) {
			$attribute_formatted_values = $cat_labels;
			$record =& $this->get_record($recordid);
			if (!$record->id)
				continue;

			foreach ($attribute_formats as $column => $format_descriptor) {
				$format = $format_descriptor['format'];
				$attributeid = $format_descriptor['attributeid'];
				$attribute_formatted_values[$column] = $record->get_attribute_formatted_value($attributeid, $format);
			}
			$csv_record_rows[$recordid] = $attribute_formatted_values;
		}
		return $csv_record_rows;
	}

	/**
	* Export a category's records (and possibly its descendant categories).
	*
	* @param int $categoryid	The category to export
	* @param array $attribute_formats - array of attribute formats
	* @param array $subcat_scope - array of category ids in scope
	* @access public
	*/
	function export_category($categoryid, $attribute_formats, $max_depth) {
		$csv = new Csv();

		for ($i=1; $i<=$max_depth-1; $i++) {
			$cat_labels['cat'.$i] = '';
		}

		$header_columns = array_keys($cat_labels);
		foreach ($attribute_formats as $format_descriptor) {
			array_push($header_columns, $format_descriptor['column_name']);
		}

		$rows = $this->get_all_category_record_rows($categoryid, $attribute_formats, $depth_counter=0, $max_depth, $cat_labels);

		$csv->set_field_headers($header_columns);
		$csv->set_filename('export-'.date("Ymd-Hi").'.csv');
		$csv->set_values($rows);
		$csv->export();

	}

	# get all attribute formats including subcategories attributes up to max depth
	function get_all_category_record_rows($categoryid, &$attribute_formats, &$depth_counter, $max_depth, &$cat_labels){
		if ($depth_counter == $max_depth) return; # skip any depths greater than max depth
		$category = $this->get_category($categoryid);
		if ($depth_counter > 0) {
			$cat_labels['cat'.$depth_counter] = $category->name;
		}

		$recordids = $category->search_records();
		$rows = $this->export_records_rows($recordids, $attribute_formats, $cat_labels);

		$tree = &$this->get_category_tree();
		$subcategoryids = &$tree->childids($categoryid);
		$depth_counter++;
		foreach ($subcategoryids as $subcid) {
			$rows = array_merge($rows, $this->get_all_category_record_rows($subcid, $attribute_formats, $depth_counter, $max_depth, $cat_labels));
		}

		$cat_labels['cat'.$depth_counter] = '';
		$depth_counter--;
		return $rows;
	}

	 ####################
	# Prints the backend 
	function print_backend() {
		if ($_REQUEST['action'] == 'export') {
			$this->do_export();
		} else {
			parent::print_backend();
		}
	}

	function do_export($recordids=array(), $export_attribute_formats=array()) {
		$this->process_depth_parameter();

		if (!is_null($_REQUEST['category'])) {
			$this->categoryid = $_REQUEST['category'];
		}

		if (empty($export_attribute_formats)) {
			if (!is_null($_REQUEST['attribute_formats'])) {
				$export_attribute_formats = unserialize(urldecode($_REQUEST['attribute_formats']));
			} else {
				$this->get_attribute_format_set($this->categoryid, $export_attribute_formats=array(), $depth_counter=0, $max_depth=0);
			}
		}

		if (empty($recordids)) {
			# Try to get em from the request
			$recordids = unserialize(urldecode($_REQUEST['records']));
		}

		if (!empty($recordids)) {
			$this->export_records($recordids, $export_attribute_formats);
		} else {
			if ($this->categoryid) {
				$this->export_category($this->categoryid, $export_attribute_formats, ($this->parameters['depth'] > -1)?$this->parameters['depth']:$max_depth);
			}
		}
	}

	function print_backend_depth_parameter() {
		if (is_null($this->parameters['depth']) || $this->parameters['depth'] < 0) {
			$all_checked = 'checked';
		} else {
			$limit_checked = 'checked';
		}
		?>
		<input type=radio name="depth_type" value="all" <?=$all_checked?>>All descendants
		<input type=radio name="depth_type" value="limit" <?=$limit_checked?>>Limit to : <?= text_box("depth", ($this->parameters['depth']>0?$this->parameters['depth']:0), 2) ?> levels
		<?
	}

	function process_depth_parameter() {
		if (!is_null($_REQUEST['depth']) && !isset($_REQUEST['depth_type'])) {
			$this->parameters['depth'] = $_REQUEST['depth'];
		} elseif ($_REQUEST['depth_type'] == 'limit') {
			$this->parameters['depth'] = (int)$_REQUEST['depth'];
		} else {
			$this->parameters['depth'] = -1;
		}
	}

	# get all attribute formats including subcategories attributes up to max depth
	function get_attribute_format_set($categoryid, &$attribute_formats, &$depth_counter, &$max_depth) {
		$depth_counter++;
		if ($depth_counter == $this->parameters['depth']+1) {
			return; # max_depth = -1 for no limit
		} elseif ($this->parameters['depth'] == -1 && $depth_counter > $max_depth) {
			$max_depth = $depth_counter;
		}
		$cat=$this->get_category($categoryid);
		$tree = &$this->get_category_tree();
		foreach ($tree->get_context_attributes($categoryid) as $attributeid => $attribute_name) {
			$attribute = &$this->get_attribute($attributeid);
			$attribute_formats[$attribute_name] = array("format" => "raw", "column_name" => $attribute_name, 'attributeid'=>$attributeid);
		}
		$subcategoryids = &$tree->childids($categoryid);
		foreach ($subcategoryids as $subcid) {
			$this->get_attribute_format_set($subcid, &$attribute_formats, &$depth_counter, &$max_depth);
			$depth_counter--;
		}
	}

	function print_download() {
		$backend =& $this->get_backend();
		?>
			<a href="" onClick="
				document.edit.action.value ='export';
				document.edit.submit();
				return false;
			" onMouseOver="window.status='Export to CSV'; return true;" onMouseOut="window.status='';return true;"><img src="<?=$backend->rel_path?>/images/icons/create.gif" border=0></a>

		<?
	}

}

?>
