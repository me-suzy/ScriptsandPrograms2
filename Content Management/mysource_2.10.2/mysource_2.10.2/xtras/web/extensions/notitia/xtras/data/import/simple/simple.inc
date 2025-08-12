<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/import/simple/simple.inc,v $
## $Revision: 1.27 $
## $Author: achadszinow $
## $Date: 2004/03/25 04:14:32 $
#######################################################################

#---------------------------------------------------------------------#

class Web_Extension_Notitia_Data_Import_Simple extends Web_Extension_Notitia_Data_Import {

	var $delimiters = array('comma'=>',','tab'=>"\t", 'tilde' => '~');
	var $temp = array();

	/**
	*
	* The constructor.
	*
	* @returns void
	* @access public
	*/
	function Web_Extension_Notitia_Data_Import_Simple($categoryid) {
		Web_Extension_Notitia_Data_Import::Web_Extension_Notitia_Data_Import($categoryid);

		$session = &get_mysource_session();
		# OK what we are doing here is being a bit tricky we are going to set our parameters 
		# array to a reference to the var in the session, that way we can just reference it as 
		# we need it, PLUS when we process the param set the stuff get's put into the session
		# var and we don't have problems with overwriting arrays
		if ($session->has_var('notitia_import_data_parameters')) {
			$this->parameters = &$session->get_var('notitia_import_data_parameters');
			$this->temp['notitia_imported_records'] = $session->get_var('notitia_imported_records');
		} else {
			$session->set_var('notitia_import_data_parameters', $this->parameters);
		}
	}

	/**
	*
	* Prints the backend and organises the popup status bars.
	*
	* @returns void
	* @access private
	*/
	function print_backend() {
		$backend = &$this->setup_backend();
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,get_class($this),$this->get_backend_href());
		$pset->process($backend);

		switch($_REQUEST['import_action']) {
			case 'Gen_Server_Side_Info':
				$param_str = str_replace(Array("~", "\n", "\r"), Array("~~", "~nl~", "~cr~"), serialize($this->parameters));
				$text_box = str_replace(Array("\n", "\r"), Array("\\n", "\\r"), addslashes(text_box('', $param_str, 60)));
				$js = '
				var simple_importer_win = window.open("", "simple_importer_win", "toolbar=no,location=no,menubar=no,resizable=no,width=500,height=100,nominimize,nomaximize,norestore,scrollbars=no");
				simple_importer_win.document.open();
				simple_importer_win.document.write("<html><body><div align=\"center\"><form>Copy this and Paste into the Server-Side Script when asked :<br>'.$text_box.'<br><input type=\"button\" value=\"Close\" onClick=\"javascript: window.close();\"></form></div></body></html>");
				simple_importer_win.document.close();
				simple_importer_win.focus();
				';
				$backend->set_javascript($js);
			break;
			case 'Import_File_Start':
				$this->cleanup_prev_import();
				$this->split_file();
			case 'Import_File':
				$this->import_file();
			break;
			case 'Import_File_Finished':
				echo status_popup('100',$this->get_backend_href().'&import_action=Import_Cleanup',false,true,'#330099','Import Complete','');
				exit();
			break;
			case 'Import_Cleanup':
				$session = &get_mysource_session();
				$parameters = &$session->get_var('notitia_import_data_parameters');
				$del_c = in_array('delete_categories', $parameters['overwrite_options']);
				$del_p = in_array('delete_records',    $parameters['overwrite_options']);
				# if we don't need to do anything get out of here
				if ($del_c || $del_p) {
					$this->import_cleanup_popup();
				} else {
					# before getting out of here we want to set the import action to display results
					$query_string = $_SERVER['query_string'];
					$location = $this->get_backend_href().$query_string.'&import_action=Import_Display_Results';
					header("Location: $location");
					exit();
				}
			break;
			case 'Import_Cleanup_Finished':
				echo status_popup('100',$this->get_backend_href().'&import_action=Import_Display_Results',false,true,'#330099','Import Complete','');
				exit();
			break;
		}

		$pset->print_backend($backend);

	}

	/**
	* Splits a large file into chunks.
	*
	* Because CSV->import times out trying to read in large files,
	* this splits the uploaded file into 1,000 line chunks.
	* It saves the files in the notitita data directory with files names
	* "$this->codename_import.$userid.tmp.1", "$this->codename_import.$userid.tmp.2" etc
	*
	* @returns void
	* @access private
	*/
	function split_file() {
		$e = &$this->get_notitia_system();
		$session = &get_mysource_session();

		$f = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp";
		$lcount = 0; $fcount = 1; $data = '';

		$fp = fopen($f, "r");
		while (!feof($fp)) {
			$lcount++;
			$data .= fgets($fp, 65535);
			if ($lcount >= 10) {
				$fd = fopen($f . ".$fcount", "w");
				fputs($fd, $data);
				fclose($fd);
				$data = '';
				$fcount++;
				$lcount = 0;
			}
		}
		fclose($fp);
		if ($data != '') {
			$fd = fopen($f . ".$fcount", "w");
			fputs($fd, $data);
			fclose($fd);
			unset($data);
		}
		unlink($f);

		$_REQUEST['num_to_process'] = $fcount++;
		$blank = '';
		$session->set_var('notitia_data_import_survivors', $blank);
		$session->set_var('results_message', $blank);
		$session->set_var('matrix_header', $blank);
		$session->set_var('primary_key_type', $blank);
	}

	/**
	* Displays the import_file popup which lets us know how many files there are to read in and the progress.
	*
	* @returns void
	* @access private
	*/
	function import_file() {
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$finish = $_GET['finish'];
		$num_to_process = $_REQUEST['num_to_process'];
		$num_processed = $_REQUEST['num_processed'];
		$num = $_REQUEST['num'];
		$backend = &$this->setup_backend();
		$session = &get_mysource_session();

		if (isset($num) && isset($num_to_process)) {
			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&import_action=Import_File&started=1&start_time=$now&current_userid=".$session->user->id;
				echo status_popup(1,$process_url,false,false,'#FF0033','Processing Import - Please wait', "Starting to process $num_to_process files");
				exit();
			}
			$this->do_file_import();

			$num_processed++;
			$percent = ceil(($num_processed / $num_to_process) * 100);
			$finish = (($num_processed >= $num_to_process) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_page = $time_diff / $num_processed;
			$time_left = $time_per_page * ($num_to_process - $num_processed);
			if ($time_left <= 0) $time_left = 1;
			$status = '';
			if ($session->get_var('primary_key_type') == 'fail') {
				$process_url = $this->get_backend_href().'&import_action=Import_Cleanup_Finished';
				$percent = 100;
				$status = 'Failed CSV import';
			} elseif ($finish) {
				$process_url = $this->get_backend_href()."&cleanup=0&import_action=Import_File_Finished&num=$num&num_to_process=$num_to_process&current_userid=".$session->user->id;
				$status = 'Finished CSV import';
			} else {
				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=$num_processed&import_action=Import_File&started=1&start_time=$start_time&current_userid=".$session->user->id;
				$status = "$num_processed files processed - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#FF0033','Importing CSV records - Please wait',$status);
			exit();
		}
		if ($num <= 0) $num = 1;
		$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&import_action=Import_File&started=0&current_userid=".$session->user->id;
		echo status_popup(1,$process_url,true,false,'#FF0033','Importing Files - Please wait', "Starting to import $num_to_process files");
	}

	/**
	* Validates the primary key field exists
	*
	* @returns void
	* @param $header - the csv header
	* @access private
	* @returns bool
	*/
	function validate_primary_key($header) {
		$session = &get_mysource_session();
		$n = &$this->get_notitia_system();
		$results_msg = &$session->get_var('results_message');
		$primary_attribute = &$this->get_attribute($this->parameters['primary_attributeid']);

		if (!in_array($primary_attribute->name, $header)) {
			$results_msg .= "The primary attribute does not exist in the csv file\n";
			if ($primary_attribute->plays_role('auto_increment')) {
				$results_msg .= "The primary attribute is an auto increment field continuing\n";
				$session->set_var('primary_key_type', $var1='auto');
			} else {
				# Delete unwanted files and finish
				$f = "$n->data_path/$this->codename"."_import.{$session->user->id}.tmp";
				$files = list_files(dirname($f));
				if (!empty($files)) {
					foreach ($files as $filename) {
						$file = dirname($f).'/'.$filename;
						unlink($file);
					}
				}
				$session->set_var('primary_key_type', $var3='fail');
				return false;
			}
		} else {
			$session->set_var('primary_key_type', $var4='ok'); # set to ok
		}
		return true;
	}

	/**
	* This actually imports the file, adds the imported data to session variables.
	* Once it has run through all the files (ie the directory is empty), it grabs the header line
	* then starts the actual import.
	*
	* @returns void
	* @access private
	*/
	function do_file_import() {
		$e = &$this->get_notitia_system();

		$session = &get_mysource_session();
		$params = &$session->get_var('notitia_import_data_parameters');
		$header = &$session->get_var('matrix_header');
		$primary_key_type = &$session->get_var('primary_key_type');

		$f = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp";

		$files = list_files(dirname($f));
		if (!empty($files)) {
			$file = dirname($f).'/'.$files[0];
			global $SQUIZLIB_PATH;
			include_once("$SQUIZLIB_PATH/csv/csv.inc");
			$csv = new Csv($file);
			$csv->set_deliminator($this->delimiters[$params['delimiter']]);
			$csv->import();
			$tmp = $csv->values;

			if (!isset($header) || empty($header)) {
				$session->set_var('matrix_header', &$tmp[0]);
				$header = &$session->get_var('matrix_header');
				unset($tmp[0]);
			}

			if ($primary_key_type == '') {
				if (!$this->validate_primary_key($header)) {
					return;
				}
			}

			unlink($file);
			$this->import($tmp);
			unset($tmp);
		}
	}

	/**
	* Works out whether to display the results of the import or not based on the query string.
	*
	* @returns void
	* @access private
	*/
	function import_display_results() {
		return ($_GET['import_action'] != 'Import_Display_Results') ? false : true;
	}

	/**
	* Displays the results of the import process in a text box for review.
	*
	* @returns void
	* @access private
	*/
	function import_display_results_form() {
		$session = &get_mysource_session();
		echo text_area('log_results', $session->get_var('results_message'), 50, 7, "", "onfocus=blur()");
		$session->unset_var('results_message');
	}

	/**
	* Processes the popup status bar and works out the time remaining, records to process etc.
	*
	* @returns void
	* @access private
	*/
	function import_cleanup_popup() {
		$tree = &$this->get_category_tree();
		$num_to_process = $_REQUEST['num_to_process'];
		$num = $_REQUEST['num'];
		$num_processed = $_REQUEST['num_processed'];
		$started = $_REQUEST['started'];
		$start_time = $_REQUEST['start_time'];
		$backend = &$this->setup_backend();
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');

		$del_c = in_array('delete_categories', $parameters['overwrite_options']);
		$del_p = in_array('delete_records',    $parameters['overwrite_options']);

		if (isset($num) && isset($num_to_process)) {
			# Tell the backend that it showuld shows our messages
			$backend->set_delay_messages(false);

			if (!$started) {
				$now = time();

				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&import_action=Import_Cleanup&started=1&start_time=$now&current_userid=".$session->user->id;
				echo status_popup(1,$process_url,false,false,'#FF0033','Processing Import - Please wait', "Starting to process $num_to_process records");
				exit();
			}

			$delete_recordids = $session->get_var('recordids_for_deletion');
			# Delete some records
			$backend->add_message(ereg_replace("[\n]+","\n",$this->delete_records($delete_recordids, $num)));
			$session->set_var('recordids_for_deletion', $delete_recordids);

			$num_processed += $num;
			$percent = ceil(($num_processed / $num_to_process) * 100);
			$finish = (($num_processed >= $num_to_process) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_page = $time_diff / $num_processed;
			$time_left = $time_per_page * ($num_to_process - $num_processed);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				# Are we deleting lowest empty categories?
				if ($del_c) {
					# Get all the categories we have under our import category + out import category
					$categories_to_check = &$session->get_var('notitia_import_all_categories');
					$results_msg = &$session->get_var('results_message');

					# Sort from lowest level to highest level so when we delete empty categories we know they have no sub categories
					usort($categories_to_check, array($tree, 'universal_category_order_cmp'));
					$results_msg = &$session->get_var('results_message');
					# Go thru that array and delete category if it has no sub categories and it has no associated records
					foreach($categories_to_check as $categoryid) {
						$category = &$this->get_category($categoryid);
						$associated_records = $category->associated_records();
						if ($category->id && empty($associated_records) && !$tree->has_children($categoryid)) {
							$results_msg .= "Category " .$category->name. " deleted\n";
							$category->delete();
						}
					}
				}

				$process_url = $this->get_backend_href()."&cleanup=0&import_action=Import_Cleanup_Finished&num=$num&num_to_process=$num_to_process&current_userid=".$session->user->id;
				$status = 'Finished cleaning up.';
			} else {
				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=$num_processed&import_action=Import_Cleanup&started=1&start_time=$start_time&current_userid=".$session->user->id;
				$status = "$num_processed processed - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#FF0033','Verifying Records - Please wait',$status);
			exit();
		}

		if ($num <= 0) $num = 20;

		$categoryid = &$this->categoryid;

		if ($del_p || $del_c) {
			# Find out all the recordids that exist below $categoryid and it's sub categories and then remove any suvivers from it
			$all_categories = $tree->all_descendantids($categoryid);;
			$all_categories[] = $categoryid;
		} else {
			# We don't need to do much cleaning at all in this case
			$all_categories = array();
		}

		# Lets save these categories for later cause we may need to check for deleting
		$session->set_var('notitia_import_all_categories', $all_categories);

		if ($del_p) {
			# If we are going to delete records that weren't mentioned in the import lets do this
			$all_associated_recordids = array();
			foreach($all_categories as $categoryid) {
				$category = &$this->get_category($categoryid);
				if ($category->id) {
					$local_associated_records = $category->associated_records();
					$all_associated_recordids = array_merge($all_associated_recordids, $local_associated_records);
				}
			}
			# Find out who the survivors are
			$survivors = &$session->get_var('notitia_data_import_survivors');
			$survivor_list = explode(',',$survivors);

			# Now we know all the associated records lets remove and recordids in this array if theyy are on the surviver list
			$delete_recordids = array_diff($all_associated_recordids, $survivor_list);
		} else {
			# The records we are to delete in zero
			$delete_recordids = array();
		}

		# Save this list to session so we can delete em
		$session->set_var('recordids_for_deletion', $delete_recordids);

		$num_to_process = count($delete_recordids);

		$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&import_action=Import_Cleanup&started=0&current_userid=".$session->user->id;
		echo status_popup(1,$process_url,true,false,'#FF0033','Verifying Records - Please wait', "Starting to clean up $num_to_process records");
	}

	/**
	* Basically the same as import_cleanup_popup but does it all without the popup
	*
	* @returns void
	* @access public
	*/
	function delete_unused($num=0) {
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');
		$del_c = in_array('delete_categories', $parameters['overwrite_options']);
		$del_p = in_array('delete_records',    $parameters['overwrite_options']);
		if (!$del_p && !$del_c) return;
		$tree = &$this->get_category_tree();

		$categoryid = $this->categoryid;

		$all_categories = $session->get_var('notitia_import_all_categories');
		if (empty($all_categories)) {
			# Find out all the recordids that exist below $categoryid and it's sub categories and then remove any suvivers from it
			$all_categories = $tree->all_descendantids($categoryid);
			$all_categories[] = $categoryid;

			# Lets save these categories for later cause we may need to check for deleting
			$session->set_var('notitia_import_all_categories', $all_categories);
		}

		$delete_recordids = $session->get_var('recordids_for_deletion');
		if (empty($delete_recordids) && $delete_recordids != 'no_records') {
			if ($del_p) {
				# If we are going to delete records that weren't mentioned in the import lets do this
				$all_associated_recordids = array();
				foreach($all_categories as $categoryid) {
					$category = &$this->get_category($categoryid);
					if ($category->id) {
						$local_associated_records = $category->associated_records();
						$all_associated_recordids = array_merge($all_associated_recordids, $local_associated_records);
					}
				}
				# Find out who the survivors are
				$survivors = $session->get_var('notitia_data_import_survivors');
				$survivor_list = explode(',',$survivors);

				# Now we know all the associated records lets remove and recordids in this array if theyy are on the surviver list
				$delete_recordids = array_diff($all_associated_recordids, $survivor_list);
			} else {
				# The records we are to delete in zero
				$delete_recordids = 'no_records';
			}
			$session->set_var('recordids_for_deletion', $delete_recordids);
		}

		if ($delete_recordids != 'no_records' && !empty($delete_recordids)) {
			# Delete some records
			$this->delete_records($delete_recordids, $num);
			$session->set_var('recordids_for_deletion', $delete_recordids);
		}

		if (empty($delete_recordids) || $delete_recordids == 'no_records') {
			# Are we deleting lowest empty categories?
			if ($del_c) {
				# Get all the categories we have under our import category + out import category
				$categories_to_check = &$session->get_var('notitia_import_all_categories');

				# Sort from lowest level to highest level so when we delete empty categories we know they have no sub categories
				usort($categories_to_check, array($tree, 'universal_category_order_cmp'));

				# Go thru that array and delete category if it has no sub categories and it has no associated records
				foreach($categories_to_check as $categoryid) {
					$category = &$this->get_category($categoryid);
					$associated_records = $category->associated_records();
					if ($category->id && empty($associated_records) && !$tree->has_children($categoryid)) {
						$category->delete();
					}
				}
			}
		}
	}

	function delete_records(&$to_delete_recordids, $num=0) {
		$session = &get_mysource_session();
		$results_msg = &$session->get_var('results_message');
		$i = 0;
		foreach($to_delete_recordids as $key => $recordid) {
			if ($i < $num || $num == 0) {
				# Let's delete the record
				$record = &$this->get_record($recordid);
				if ($record->id) {
					$results_msg .= "Record ".$record->id." deleted\n";
					$record->delete();
					unset($record);
					unset($to_delete_recordids[$key]);
				}
				$i++;
			} else {
				# We've processed enough for this run
				break;
			}
		}
	}

	/**
	* Clean up any old files from a previous import.
	* Just to make sure.
	*
	* @returns void
	* @access private
	*/
	function cleanup_prev_import() {
		$e = &$this->get_notitia_system();
		$session = &get_mysource_session();
		$blank_array = array();
		$session->set_var('notitia_imported_records', $blank_array);
		$f = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp";
		$files = list_files(dirname($f), true);
		foreach($files as $file) {
			if ($file == $f) continue;
			if (is_file($file)) {
				 unlink($file);
			}
		}
	}

	/**
	* This imports the data
	*
	* @param Array() $record_matrix array of records, whose data matrix is the array of data to import,
	*
	* @returns void
	* @access private
	*/
	function import(&$record_matrix) {
		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();
		$db   = &$this->get_db();
		$session = &get_mysource_session();

		$parameters = &$session->get_var('notitia_import_data_parameters');

		$matrix_header = &$session->get_var('matrix_header');

		$results_msg = &$session->get_var('results_message');

		if($this->categoryid == 0) {
			$category = &$this->get_notitia_system();
		} else {
			$category = &$this->get_category($this->categoryid);
		}

		$primary_attributeid	= &$parameters['primary_attributeid'];
		$op						= &$parameters['overwrite_options'];
		$al						= &$parameters['attributelists'];
		$category_defaultables	= &$al['category_defaultable'];
		$nullibles				= &$al['nullible'];
		$variety_nullibles		= &$al['nullible_varieties'];
		$ignorables				= &$al['ignorable'];
		$variety_ignorables		= &$al['ignorable_varieties'];

		$ms = array();
		$categoryids = array();
		$attribute_map = array();
		$attribute_map = $this->get_attribute_list('', -1);
		$attribute_map = array_flip($attribute_map);
		if (!empty($attribute_map)) {
			foreach($attribute_map as $name => $attributeid) {
				$trimmed_name = trim(strtolower($name));
				if ($trimmed_name != $name) {
					$attribute_map[$trimmed_name] = $attributeid;
					unset($attribute_map[$name]);
				}
			}
		}

		if($catlist = trim(strtolower($parameters['category_labels']))) {
			$category_depth_map = array_flip(split("[ \t\n\r]*\n[ \t\n\r]*",$catlist));
		} else {
			$category_depth_map = array();
		}

		$attribute_to_column      = array();
		$category_depth_to_column = array();
		$variety_to_column        = array();

		# Map the columns to attributes, category levels, varieties
		foreach($matrix_header as $colid => $name) {
			$name = trim(strtolower($name));
			if(isset($attribute_map[$name])) {
				$column_map[$colid] = $attribute_map[$name];
				$attribute_to_column[$column_map[$colid]] = $colid;
			}
			# Maybe its a category label then/as well?
			if(isset($category_depth_map[$name])) {
				$category_depth_to_column[$category_depth_map[$name]] = $colid;
			}

			# Okay, could it be a variety set?
			foreach($parameters['variety_labels'] as $attributeid => $variety_sets) {
				foreach($variety_sets as $variety_setid => $varieties) {
					foreach($varieties as $coord => $label) {
						if(trim(strtolower($label)) == $name) {
							$variety_to_column[$attributeid][$variety_setid][$coord] = $colid;
						}
					}
				}
			}
		}

		$op    = &$parameters['overwrite_options'];
		$add_p = in_array('add_records',$op);
		$ovr_p = in_array('overwrite_records',$op);
		$del_p = in_array('delete_records',$op);
		$add_c = in_array('add_categories',$op);
		$ovr_c = in_array('overwrite_categories',$op);
		$del_c = in_array('delete_categories',$op);

		foreach($record_matrix as $matrix) {
			$data = array();

			$primary_colid = $attribute_to_column[$primary_attributeid];

			$container = &$data;

			for($depth = 0; isset($category_depth_to_column[$depth]); $depth++) {
				$colid = $category_depth_to_column[$depth];
				$category_name = trim($matrix[$colid]);
				if(strlen($category_name) > 0) {
					$container = &$container['c'][$category_name];
				}
			}

			# Get the primary ID
			$primary_key_type = $session->get_var('primary_key_type');
			if ($primary_key_type == 'auto') {
				# In the long run we won't use these numbers anyway
				if (!isset($primary_id)) {
					$primary_id = 0;
				} else {
					$primary_id++;
				}
			} else {
				$primary_id = &$matrix[$primary_colid];
			}

			if(strlen(trim($primary_id))) {
				$container = &$container['p'][$primary_id];
				$contype = 'p';
			} else {
				$contype = 'c';
			}

			# Now lets put in the values
			foreach($attribute_to_column as $attributeid => $colid) {
				if($contype == 'c' && !in_array($attributeid, $category_defaultables)) {
					continue; # Can't assign this value to a category default
				}
				$value = &$matrix[$colid];
				$blank = (strlen(trim($value)) <= 0);
				if(!$blank || !in_array($attributeid,$ignorables)) {
					$container['a'][$attributeid]['d'] = &$value;
				} # Otherwise leave this attribute untouched
			}
			# Now what about varieties?
			foreach($variety_to_column as $attributeid => $varieties) {
				foreach($varieties as $variety_setid => $coords) {
					foreach($coords as $coord => $colid) {
						$variation = &$matrix[$colid];
						$blank = (strlen(trim($variation)) <= 0);
						if(!$blank || !in_array($attributeid,$variety_ignorables)) {
							$container['a'][$attributeid]['v'][$variety_setid][$coord] = &$variation;
						} # Otherwise leave this variety untouched
					}
				}
			}

			$results_msg .= $this->import_category($this->categoryid, $data, 0);
		
			$num_processed++;
			unset($data);
			unset($container);
		}// end foreach

		if ($primary_key_type == 'auto') {
			$primary_id++;
			$session->set_var('primary_key_val', $primary_id);
		}

	}// end import()

	/**
	* Takes a wad of preprocessed data and imports it into category
	* @param int categoryid array matrix int depth
	*
	* categoryid is where to import it to
	* matrix is the array of data to import, depth is how far to go down the category tree.
	*
	* @returns string
	* @access private
	*/
	function import_category($categoryid, &$data, $depth) {
		
		if($categoryid == 0) {
			$category = &$this->get_notitia_system();
		} else {
			$category = &$this->get_category($categoryid);
			if(!isset($category->id)) return "Unable to import category (ID: $categoryid)";
		}
		$tree = &$this->get_category_tree();
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');

		$primary_attributeid = &$parameters['primary_attributeid'];
		$primary_attribute   = &$this->get_attribute($primary_attributeid);
		$op    = &$parameters['overwrite_options'];
		$add_p = in_array('add_records',$op);
		$ovr_p = in_array('overwrite_records',$op);
		$add_c = in_array('add_categories',$op);
		$ovr_c = in_array('overwrite_categories',$op);
		$mlt_c = in_array('multi_categories',$op);
		# Do the attribute defaults
		$dirs = &$parameters['file_directories'];
		
		$attributes_for_this_category = $tree->get_context_attributes($categoryid);
		$attributeids_for_this_category = array_keys($attributes_for_this_category);

		foreach($data['a'] as $attributeid => $values) {
			$value     = &$values['d'];
			$attribute = &$this->get_attribute($attributeid);
			if(isset($dirs[$attributeid])) {
				$dir         = $dirs[$attributeid]['dir'];
				$pageid      = $dirs[$attributeid]['pageid'];
				# Returns fileid, blank on failure
				list($value,$nm) = $this->attach_file($value,$categoryid,$dir,$pageid,$depth);
				if($nm) $m .= "$nm\n";
			}
			if(strlen(trim($value))) {
				# File importing
				if($attribute->get_default_value($category->id) != $value) { # Can't be arrays anyway
					$m .= $attribute->set_default_override($categoryid, $value,'replace','replace')."\n";
				}
			} elseif($attribute->get_default_value($category->id)) {
				$attribute->remove_default_override($categoryid);
			}
		}

		# Do the records - only if not the root category
		if($categoryid > 0) {
			$survivors = &$session->get_var('notitia_data_import_survivors');
			$survivor_list = explode(',',$survivors);
			$p_count = count($data['p']);
			$i = 0;
			$primary_key_type = $session->get_var('primary_key_type');
			foreach($data['p'] as $primary_id => $subdata) {
				$i++;
				# Import all the records for this category. If we are relying on auto increment primary key because of short circuiting it shouldn't evaluate the get_primary_recordid
				if($primary_key_type != 'auto' && $recordid = $this->get_primary_recordid($categoryid, $primary_id)) {
					$survivor_list[] = $recordid; # So we can delete the leftovers
					if(!$ovr_c && !$ovr_p) continue; # Sorry, no can do!
					$record = &$this->get_record($recordid);
					$t = 'u';
					$T = 'UPDATING:';
				} else {
					if(!$add_p) continue; # Sorry, no can do!
					if ($mlt_c && $this->temp['notitia_imported_records'][$primary_id]) {
						# A record would only be in this array if it got created
						$recordid = $this->temp['notitia_imported_records'][$primary_id];
						$category->associate_with_record($recordid);
						$m .= 'Record ID '.$recordid.' has been associated with category ID '.$categoryid."\n";
					} else {
						# No initial values if we are relying on an auto increment
						if ($primary_key_type != 'auto') {
							$values = array($primary_attributeid => $primary_id);
						} else {
							$values = array();
						}
						$recordid = &$category->create_record($values);
						$survivor_list[] = $recordid; # So we can delete the leftovers
						$record = &$this->get_record($recordid);
						if ($primary_key_type == 'auto') {
							# Up until now the primary id wasn't real because the import never provided us with one but now we will find out what notitia assigned for recording it
							$primary_id = $record->get_attribute_value($primary_attributeid);
						}
						$m .= "New record '".$record->name(1)."' created.\n";
						$t = 'a';
						$T = 'ADDING:';
					}
					$this->add_primary_id_to_category_primary_list($category->id, $primary_id, $recordid);
				}
				if ($mlt_c) {
					$this->temp['notitia_imported_records'][$primary_id] = $recordid;
				}

				if(($add_p && $t == 'a') || ($ovr_p && $t == 'u')) {
					$m .= $this->import_record($recordid, $subdata, $category->id, $depth, $attributeids_for_this_category);
				}
			}

			if ($mlt_c) {
				$session->set_var('notitia_imported_records', $this->temp['notitia_imported_records']);
			}
			$session->set_var('notitia_data_import_survivors', implode(',',$survivor_list));
		}

		# Do the subcategories
		$child_lookup = array_flip($tree->child_list($categoryid));
		$c_count = count($data['c']);
		$i = 0;
		foreach($data['c'] as $name => $subdata) {
			$i++;
			$subcategoryid = 0;
			if(!$child_lookup[$name]) {
				if(!$add_c) continue; # Sorry, no can do!
				$subcategoryid = $tree->create_category($name,$categoryid);
				$m .= "New category '$name' created.\n";
			} else {
				$subcategoryid = $child_lookup[$name];
			}
			if ($subcategoryid) {
				$this->import_category($subcategoryid, $subdata, $depth + 1);
			}
		}

		$category->clear_cache();
		if($category->id) {
			$e = &$this->get_notitia_system();
			$e->forget_category($category->id);
		}
		unset($data);
		return $m;
	}

	/**
	* Imports an individual record
	* @param int recordid array data int categoryid int depth
	*
	* recordid is the notitia recordid
	* data is the array of data associated with the record
	* categoryid is where to place the record
	* depth is how far down the tree to look for files to attach
	*
	* @returns string
	* @access private
	*/
	function import_record($recordid, &$data, $categoryid, $depth, $attributeids_for_this_category) {
		$record = &$this->get_record($recordid);
		if(!$recordid) return "Unable to import record (ID: $recordid).";

		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');

		$al = &$parameters['attributelists'];
		$nullibles         = &$al['nullible'];
		$variety_nullibles = &$al['nullible_varieties'];

		$dirs = &$parameters['file_directories'];

		foreach($data['a'] as $attributeid => $values) {

			if (!in_array($attributeid, $attributeids_for_this_category)) continue;

			$attribute = &$this->get_attribute($attributeid);
			$null = $record->null_attribute($attributeid);
			if(isset($values['d'])) {
				$value = &$values['d'];
				if(isset($dirs[$attributeid])) {
					$dir         = $dirs[$attributeid]['dir'];
					$pageid      = $dirs[$attributeid]['pageid'];
					# Returns fileid, blank on failure
					list($value,$nm) = $this->attach_file($value,$categoryid,$dir,$pageid,$depth);
					if($nm) $m .= "$nm\n";
				}

				$rule_broken_msgs = '';
				# Special things like user refs and foreign keys won't be able to be set in an importer normally so lets do some pre processing on these if it's the case
				$handler = &$attribute->get_type_handler();
				$attribute->validate_value($value, $rule_broken_msgs, 'importing');

				# validate value should be called before the user_reference is pre proccessed
				# If we are doing a user ref and they aren't feeding in an int (a user id)
				# And they do not want ints as login names 
				if($handler->settings['sub_type_name'] == 'user_reference' && 
					((!is_numeric($value) && !$handler->get_value('allow_numeric_names')) || $handler->get_value('allow_numeric_names')) 
				) {
					# Lets search the user system for the (either user login or email) they fed in
					$users_sys = &get_users_system();
					$user = &$users_sys->get_user($value, false, $handler->get_value('allow_numeric_names'));
					# Hopefully we have now found a user :-)
					$value = $user->id;
				}

				$curr_value = &$record->attribute_values[$attribute->id];
				$value_changed = (is_scalar($value) || is_scalar($curr_value)) ? ($value !== $curr_value) : !equal_arrays($value, $curr_value);
				if($rule_broken_msgs == '' && $value_changed) { 
					$v = &$record->attribute_values[$attribute->id];
					$v = $value;
					$record->save_attribute_value($attribute->id);
					$m .= "Record '".$record->name(1)."' $attribute->name updated.\n";
				}
				# Report that we broke some rule
				if ($rule_broken_msgs != '') {
					$m .= "Record '".$record->name(1)."' $attribute->name broke rule while setting value $value. Rule Broken $rule_broken_msgs\n";
				}
			}
			# Variety Sets (not if the attribute is null
			if(!isset($record->attribute_values[$attribute->id])) continue;
			foreach($values['v'] as $variety_setid => $coords) {
				foreach($coords as $coord => $var_value) {
					$blank = (strlen(trim($var_value)) <= 0);
					if($blank && in_array($attributeid,$variety_nullibles)) {
						$attribute->clear_variation_value($record->id, $variety_setid, $coord);
						$null = true;
					} else {
						$attribute->validate_value($var_value);
						if(!equal_arrays($var_value,$record->get_raw_variety_value($attribute->id,$variety_setid,$coord))) {
							$v = &$record->variations[$attributeid][$variety_setid][$coord];
							$v = $var_value;
							$type = $parameters['variety_replace'][$attributeid][$variety_setid][$coord];
							$attribute->save_variation_value($record->id, $variety_setid, $coord, $type, $v, 1);
							$m .= "record '".$record->name(1)."' $attribute->name varied.\n";
						}
					}
				}
			}
		}
		$record->clear_cache();
		if($record->id) {
			$e = &$this->get_notitia_system();
			$e->forget_record($record->id);
		}
		return $m;
	}


	/**
	* Intense recursive category GENOCIDE.. to be used with eXXtreme care
	* Also removes any records that would become orphaned in the process
	*
	* @param int categoryid int depth
	*
	* categoryid is the category to remove
	* depth is how far down the tree to remove
	*
	* @returns string
	* @access private
	*/
	function massacre_category($categoryid,$depth=0) {
		$tree = &$this->get_category_tree();
		$category = &$this->get_category($categoryid);
		if(!$category->id) return;
		# First wipe out all the subcategories.
		$c_count = count($tree->childids($categoryid));
		$i = 0;
		foreach($tree->childids($categoryid) as $childid) {
			$i++;
			$ms[] = $this->massacre_category($childid,$depth+1);
		}
		# Now all the records
		$p_count = count($category->associated_records());
		$i = 0;
		foreach($category->associated_records() as $recordid) {
			$record = &$this->get_record($recordid);
			$i++;
			if(count($record->categoryids) <= 1) { # would be orphaned
				$ms[] = $record->delete();
			}
		}
		# The category will clean up the rest of the stuff (attributes etc);
		$ms[] = $category->delete();
		unset($category);
		return trim(implode("\n",$ms));
	}


	/**
	* Tries to attach an external file to a page
	*
	* @param string filename int categoryid string dir int pageid int depth
	*
	* filename is the file to attach
	* categoryid is where the record is to attach it to
	* dir is where the file is currently situated
	* pageid is where to put the file
	* depth is how far to search for the file
	*
	* @returns array
	* @access private
	*/
	function attach_file($filename,$categoryid,$dir,$pageid,$depth) {
		$fileid = '';
		$m = '';
		$master_page = $this->web_system->get_page($pageid);
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');
		if(strlen($filename) && is_dir($dir) && $pageid == $master_page->id) { # AOK
			$tree     = &$this->get_category_tree();
			$maxdepth = &$parameters['cat_to_dir_depth'];
			$here_to_parent = array($categoryid);
			for($i = $depth - 1; $i > 0; $i--) {
				array_unshift($here_to_parent,$tree->parentid(reset($here_to_parent)));
			}
			# Okay, now we need a valid directory and a valid pageid for our files.
			$the_dir    = $dir;
			$the_pageid = $master_page->id;
			$site       = &$master_page->get_site();
			$site_index = &$site->get_page_index();
			$i = 0;
			foreach($here_to_parent as $ancestorid) {
				if($i >= $maxdepth) break;
				$name = $tree->name($ancestorid); # Okay, this is our categoryid.
				# Find a directory
				$d = opendir($the_dir);
				while($f = readdir($d)) {
					if($f[0] == '.') continue;
					if(fuzzy_name_compare($name,$f)) {
						$subdir = $f; break;
					} else $subdir = '';
				}
				if($subdir) { # Hooray, and the page?!
					$the_dir = "$the_dir/$subdir";
					$subpageid = '';
					foreach($site_index[$the_pageid]['childids'] as $subpageid) {
						if(fuzzy_name_compare($name,$site_index[$subpageid]['name'])) {
							break;
						} else $subpageid = '';
					}
					if(!$subpageid) { # Create a page!
						list($blah,$mn,$subpageid) = $site->create_page($name,'standard',$the_pageid,'L');
						if($mn) $m .= "$mn\n";
					}
					$the_pageid = $subpageid;
					$this->web_system->forget_page($subpageid);
				} else $m .= "Subdirectory resembling '$name' could not be found in $the_dir.\n";
				if(!is_dir($the_dir) || !$the_pageid) break; # Something went wrong :(
				$i++;
			}
			$the_page = &$site->get_page($the_pageid);
			if(is_dir($the_dir) && $the_page->id) { # AOK
				# So, is there a file with this name already attached to the page?
				foreach($the_page->file_index as $maybe_fileid) {
					$maybe_file = &$the_page->get_file($maybe_fileid);
					if($filename == $maybe_file->filename) {
						$fileid = $maybe_fileid;
						$file   = &$maybe_file;
					} else $this->web_system->forget_file($maybe_file->id); # Clear up space
				}
				# Okay then. Now is there a new copy of the file in the dir?
				if(file_exists("$the_dir/$filename")) {
					# Yes? Okay, overwrite the old file or create a new record
					if($fileid) {
						if(filemtime("$the_dir/$filename")>filemtime("$file->data_path/$filename")) {
							copy("$the_dir/$filename","$file->data_path/$filename");
							$file->clear_cache();
							$this->web_system->forget_file($file->id); # Clear up space
							$m .= "File '$filename' in $the_dir updated on page '$the_page->name'.\n";
						} else {
							$m .= "File '$filename' in $the_dir not updated as is has a less recent timestamp than the currently attached file.\n";
						}
					} else {
						$to_filename = $filename;
						while(file_exists("$the_page->data_path/$to_filename")) { # Clash!
							$to_filename = increment_filename($to_filename);
						}
						copy("$the_dir/$filename", "$the_page->data_path/$to_filename");
						# Create
						$new_file = new File(0);
						if($mn = $new_file->create($the_page->id, $to_filename, '', '', 'N')) {
							$m .= trim($mn)."\n";
						}
						$m .= "File '$filename' in $the_dir attached to page '$the_page->name'.\n";
						$fileid = $new_file->id;
					}
				} else $m .= "File '$filename' not found in $the_dir\n";
			}
			$this->web_system->forget_page($the_page->id);
		}
		$this->web_system->forget_page($master_page->id);
		return array($fileid,trim($m));
	}

	/**
	* Adds a primary id to the list of primes
	*
	* @param int categoryid string value
	* @param int categoryid string value
	* @param int categoryid string value
	*
	* @returns int
	* @access private
	*/
	function add_primary_id_to_category_primary_list($categoryid, $primary_id, $recordid) {
		if(!is_array($this->temp['primary_recordids'])) {
			# This will do it for us and reconstruct the whole thing
			$this->get_primary_recordid($categoryid, $primary_id);
		} else {
			$session = &get_mysource_session();
			$parameters = &$session->get_var('notitia_import_data_parameters');
			$attribute = &$this->get_attribute($parameters['primary_attributeid']);
			$attribute->validate_value($primary_id, $rule_broken_msgs, 'importing');
			$this->temp['primary_recordids'][$categoryid][$primary_id] = $recordid;
		}
	}

	/**
	* Given an category and a primary id value, returns the record id
	*
	* @param int categoryid string value
	*
	* @returns int
	* @access private
	*/
	function get_primary_recordid($categoryid,$value) {
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');
		$attribute = &$this->get_attribute($parameters['primary_attributeid']);

		if(!is_array($this->temp['primary_recordids'])) {
			$tree = &$this->get_category_tree();
			$db = &$this->get_db();
			$e = &$this->get_notitia_system();

			$primary_attributeid = $parameters['primary_attributeid'];

			# Get an array of all the subcategories
			$desendant_categoryids = $tree->all_descendantids($this->categoryid);
			# Get a list of all the primary ids of the current records
			# in each category
			$categories = "'$this->categoryid'";
			if (!empty($desendant_categoryids)) {
				$categories .= ",'".implode("','",$desendant_categoryids)."'";
			}

			if ($attribute->id) {
				$value_table_name = $attribute->get_value_table_name();
			}

			$this->temp['primary_recordids'] = $db->associative_array2("SELECT c.categoryid, v.value, v.recordid FROM $e->table_name"."_record_to_category c LEFT JOIN $value_table_name v ON (c.recordid=v.recordid) WHERE c.categoryid IN ($categories) AND v.attributeid='$primary_attributeid'");
		}

		if(isset($this->temp['primary_recordids'][$categoryid][$value])) {
			return $this->temp['primary_recordids'][$categoryid][$value];
		} else {
			$attribute->validate_value($value, $rule_broken_msgs, 'importing');
			if(isset($this->temp['primary_recordids'][$categoryid][$value])) {
				return $this->temp['primary_recordids'][$categoryid][$value];
			}
		}
		return 0;
	}

	/**
	* Given a categoryid, returns all the primary id -> record id links
	*
	* @param int categoryid string value
	*
	* @returns array
	* @access private
	*/
	function get_primary_recordid_list($categoryid, $moo) {
		$this->get_primary_recordid();
		return $this->temp['primary_recordids'][$categoryid];
	}

	/**
	* Prints out instructions
	*
	* @returns void
	* @access private
	*/
	function print_instructions_intro() {
		$tree = &$this->get_category_tree();
		?>
		<p>Upload a CSV file using the file format described below. A CSV file is a text file in which a spreadsheet is represented by seperating rows by linebreaks (\n or hitting Enter), and columns are seperated by commas or tab-spaces. Microsoft Excel can export a spreadsheet into either format.

		<p>It is important that there be no more columns than are necessary and there there are no blank or unfinished rows. The import will cease on the first blank row.

		<p>The first row should list the titles of the columns. This is imporant as the importer uses this to determine which column goes with which attribute. It is important that the titles of each column match exactly with an attribute. The attributes available in this category are as follows:</p>

		<p><b><?=$tree->name($this->categoryid)?></b>
		<ul>
			<?foreach($this->get_attribute_list('', -1) as $attributeid => $name){echo "<li>$name</li>";}?>
		</ul>

		<p>Each row after the first row is treated a record.</p>

		<p>You may allocate an attribute to be a Primary ID. This attribute is treated as a unique identifier of a record.</p>

		<p>It is up to you to ensure the data being imported is in the correct format.</p>
		<?
	}

	/**
	* Prints a list of the varieties available here and lets the user designate the column labels
	*
	* @returns void
	* @access private
	*/
	function print_variety_label_fields() {
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');

		echo("<table>");
		foreach($this->get_attribute_list('', -1) as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if(!count($vs = $attribute->get_varieties_in_context($this->categoryid))) continue;
			echo("<tr><td><b>$attribute->name Variety Label</td><td><b>Replace Type</td></tr>");
			foreach($attribute->get_varieties_in_context($this->categoryid) as $variety_setid => $varieties) {
				$variety_set = &$this->get_variety_set($variety_setid);
				foreach($varieties as $coord => $name) {
					echo("<td>");
					if($pre = $parameters['variety_labels'][$attributeid][$variety_setid][$coord]) {
						$name = $pre;
					}
					echo text_box("variety_labels[$attributeid][$variety_setid][$coord]",$name,25,100);
					echo("</td><td>");
					echo combo_box("variety_replace[$attributeid][$variety_setid][$coord]",$attribute->get_type_setting('variation_types'),$parameters['variety_replace'][$attributeid][$variety_setid][$coord]);
					echo("</td></tr>");
				}
			}
		}
		echo("</table>");
		echo("For each attribute that may have varieties, specify the column labels you will use, if any, as well as the replacement methods.");
	}

	/**
	* Processes a list of the varieties available here and lets the user designate the column labels
	*
	* @returns void
	* @access private
	*/
	function process_variety_label_fields() {
		$variety_labels = $_REQUEST['variety_labels'];
		$variety_replace = $_REQUEST['variety_replace'];

		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');

		$parameters['variety_labels'] = $variety_labels;
		$parameters['variety_replace'] = $variety_replace;
	}

	/**
	* Prints the form for defining file attachment locations
	*
	* @returns void
	* @access private
	*/
	function print_directory_allocation() {
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');
		$dirs = &$parameters['file_directories'];
		?><table><?
		foreach($this->get_attribute_list('file_reference', -1) as $attributeid => $name) {
			$attribute    = &$this->get_attribute($attributeid);
			$type_handler = &$attribute->get_type_handler();
			$root_pageid  = &$type_handler->get_value('root_pageid');
			$root_page    = &$this->web_system->get_page($root_pageid);
			$site         = &$root_page->get_site();
			$site_index   = &$site->get_page_index();
			$subpageids   = $root_page->get_subpageids();
			if(!empty($subpageids)) {
				$subpage_options = array();
				foreach($subpageids as $subpageid) {
					$subpage_options[$subpageid] = $site_index[$subpageid]['name'];
				}
			} else {
				$subpage_options = array($root_pageid => $root_page->name);
			}
			?>
			<tr>
				<td valign=top class=field><?=$name?></td>
				<td valign=top>
					<?=text_box("file_directories[$attributeid][dir]",$dirs[$attributeid]['dir'],50,512)?><br>
					Subpage: <?=combo_box("file_directories[$attributeid][pageid]",$subpage_options,$dirs[$attributeid]['pageid'],40);?>
				</td>
			</tr>
			<?
		}
		?></table><span class=smallprint>Enter paths to directories on your web server where files can be found associated with this import.</span><?
	}

	/**
	* processes the directory allocatiosns
	*
	* @returns void
	* @access private
	*/
	function process_directory_allocation() {
		$session = &get_mysource_session();
		$parameters = &$session->get_var('notitia_import_data_parameters');
		$parameters['file_directories'] = gpc_stripslashes($_REQUEST['file_directories']);
	}

	/**
	* Prints the form for uploading the csv file.
	*
	* @returns void
	* @access private
	*/
	function print_file_upload_form() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		echo file_upload('csv_file', "$e->data_path", 50000000, "$this->codename"."_import.{$session->user->id}.tmp",array('csv','tab','txt'));
		?>
		<br> OR <br>
		<input type="checkbox" name="gen_server_side_info" value="1"> Generate param string for Server-Side Run
		<?
	}

	/**
	* Processes the form for uploading the csv file and starts the whole import process.
	*
	* @returns void
	* @access private
	*/
	function process_file_upload_form() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		commit_file_upload('csv_file', 1, 0, '', $e->data_path, "$this->codename"."_import.{$session->user->id}.tmp");
		if(file_exists($f = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp")) {
			$_REQUEST['import_action'] = 'Import_File_Start';
		} else if (!empty($_POST['gen_server_side_info'])) {
			$_REQUEST['import_action'] = 'Gen_Server_Side_Info';
		}
	}
}
?>