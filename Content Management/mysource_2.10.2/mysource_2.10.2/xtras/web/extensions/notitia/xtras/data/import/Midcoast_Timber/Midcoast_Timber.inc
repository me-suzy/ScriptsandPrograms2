<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/import/Midcoast_Timber/Midcoast_Timber.inc,v $
## $Revision: 1.3 $
## $Author: achadszinow $
## $Date: 2003/02/26 22:30:17 $
#######################################################################

#---------------------------------------------------------------------#

class Web_Extension_Notitia_Data_Import_Midcoast_Timber extends Web_Extension_Notitia_Data_Import {

	 #############
	# Constructor
	function Web_Extension_Notitia_Data_Import($categoryid) {
		Web_Extension_Notitia_Data_Import::Web_Extension_Notitia_Data_Import($categoryid);
	}


	 #####################################################
	# Given a 2D array, starts importing the data based
	# on our schema
	function import(&$matrix) {

		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();

		$pt = &$this->parameters['price_types'];
		$op = &$this->parameters['overwrite_options'];

		$ms = array();

		# Okay before we start importing, lets make sure we have all the correct
		# attributes/variety sets existing in this context, and that we know the ids
		# for each of them.
		$attribute_map = array();
		$required_attributes = array('Product Code' => 'text','End Section Width' => 'number','End Section Height' => 'number','Length' => 'number','Price' => 'number','Trade Discounts' => 'option');
		$existing_attributes   = &$tree->get_context_attributes($this->categoryid);
		foreach($existing_attributes as $attributeid => $name) {
			if(isset($required_attributes[$name])) {
				$attribute_map[$name] = $attributeid;
			}
		}
		# Create new attributes if need be
		foreach($required_attributes as $name => $type) {
			if(!isset($attribute_map[$name])) {
				if($attributeid = $e->create_attribute($type,$name,$this->categoryid)) {
					$ms[] = "New attribute '$name' created.";
					$attribute_map[$name] = $attributeid;
				}
			}
		}

		if(count($attribute_map) < count($required_attributes)) {
			return "Could not complete import! Unable to create the appropriate attributes.";
		}

		 ###########################################################################
		# Now make sure the parameters of the attributes are set up appropriately.
		$attribute = &$this->get_attribute($attribute_map['Product Code']);
		$attribute->set_value('width',20);
		$attribute->set_value('height',1);
		$attribute->set_value('maxlength',30);
		$attribute->save_parameters();
		$attribute->play_role('summary');
		$role = &$attribute->get_role_handler('summary');
		$role->set_value('editable',1);
		$role->save_parameters();
		$attribute->play_role('name');
		$attribute->play_role('required');

		$attribute = &$this->get_attribute($attribute_map['End Section Width']);
		$attribute->set_value('min',0);
		$attribute->set_value('max',10000);
		$attribute->set_value('decimals',0);
		$attribute->set_value('force_decimals',array('Y'));
		$attribute->set_value('suffix','mm');
		$attribute->save_parameters();
		$attribute->play_role('allow_null');
		$attribute->play_role('summary');
		$attribute->play_role('name');
		$role = &$attribute->get_role_handler('name');
		$role->set_value('pre',' (');
		$role->set_value('suf',' x');
		$role->save_parameters();

		$attribute = &$this->get_attribute($attribute_map['End Section Height']);
		$attribute->set_value('min',0);
		$attribute->set_value('max',10000);
		$attribute->set_value('decimals',0);
		$attribute->set_value('force_decimals',array('Y'));
		$attribute->set_value('suffix','mm');
		$attribute->save_parameters();
		$attribute->play_role('allow_null');
		$attribute->play_role('summary');
		$attribute->play_role('name');
		$role = &$attribute->get_role_handler('name');
		$role->set_value('pre',' ');
		$role->set_value('suf',' x');
		$role->save_parameters();

		$attribute = &$this->get_attribute($attribute_map['Length']);
		$attribute->set_value('min',0);
		$attribute->set_value('max',1000);
		$attribute->set_value('decimals',1);
		$attribute->set_value('force_decimals',array('Y'));
		$attribute->set_value('suffix','mtrs');
		$attribute->save_parameters();
		$attribute->play_role('summary');
		$role = &$attribute->get_role_handler('summary');
		$role->set_value('editable',1);
		$role->save_parameters();
		$attribute->play_role('name');
		$role = &$attribute->get_role_handler('name');
		$role->set_value('pre',' ');
		$role->set_value('suf',')');
		$role->save_parameters();
		$attribute->play_role('required');

		$attribute = &$this->get_attribute($attribute_map['Price']);
		$attribute->set_value('min',0);
		$attribute->set_value('max',10000);
		$attribute->set_value('decimals',2);
		$attribute->set_value('force_decimals',array('Y'));
		$attribute->set_value('prefix','$');
		$attribute->save_parameters();
		$attribute->play_role('summary');
		$role = &$attribute->get_role_handler('summary');
		$role->set_value('editable',1);
		$role->set_value('hide_default',1);
		$role->set_value('show_varieties','H');
		$role->save_parameters();
		$attribute->play_role('required');

		$attribute = &$this->get_attribute($attribute_map['Trade Discounts']);
		$attribute->play_role('allow_null');
		$attribute->play_role('force_null');
		$role = &$attribute->get_role_handler('force_null');
		$role->set_value('strict',1);
		$role->save_parameters();
		$clear_pt = $pt;
		foreach($clear_pt as $id => $desc) if(!$desc) unset($clear_pt[$id]);
		$clear_pt = array('option_names'=>$clear_pt);
		$ms[] = $attribute->set_default_override($this->categoryid,$clear_pt,'replace','replace');

		# Find a Trade Discounts variety set.. if there isn't one, create one.
		$existing_variety_sets = &$tree->get_context_variety_sets($this->categoryid);
		$discount_variety_setid = 0;
		foreach($existing_variety_sets as $variety_setid => $name) {
			$variety_set = &$this->get_variety_set($variety_setid);
			if(count($variety_set->option_attributeids) == 1 && $variety_set->option_attributeids[0] == $attribute_map['Trade Discounts']) {
				$discount_variety_setid = $variety_setid;
				# make sure only the price attribute is varied by this set
				$variety_set->update_varied_attributeids(array($attribute_map['Price']));
				break;
			}
		}
		# Create a variety set that uses the trade discount options to vary the price.
		if(!$discount_variety_setid) {
			$discount_variety_setid = $e->create_variety_set($this->categoryid,0,array($attribute_map['Trade Discounts']),array($attribute_map['Price']));
		}

		 ######################################
		# Create a tree-like structure of data
		$data = array();
		for($rowid = 0; $rowid < count($matrix); $rowid++) {
			
			if(!$category     = trim($matrix[$rowid][0])) continue;
			if(!$product_code = trim($matrix[$rowid][1])) continue;
			$end_section  = ((int) $matrix[$rowid][2]) . " x " . ((int)$matrix[$rowid][3]);
			$length       = (float) $matrix[$rowid][4];
			$prices = array();
			foreach($pt as $price_typeid => $desc) {
				if($desc) {
					$prices[$price_typeid] = (float) $matrix[$rowid][((int) 4 + $price_typeid)];
				}
			}
			$data[$category][$end_section][$product_code]['l'] = $length;
			$data[$category][$end_section][$product_code]['p'] = $prices;

		}

		 #######################################################
		# Now the first thing we do is create the categories.
		# Let's see if any of the categories already exist.
		$existing_categoryids = &$tree->childids($this->categoryid);
		$category_map    = array();
		$old_categoryids = array();
		foreach($existing_categoryids as $categoryid) {
			$name = &$tree->name($categoryid);
			if(isset($data[$name]) && in_array('overwrite_categories',$op)) {
				$category_map[$name] = $categoryid;
			} elseif(in_array('delete_categories',$op)) {
				$ms[] = $this->massacre_category($categoryid);
				$old_categoryids[] = $categoryid;
			}
		}

		# Now create new categories if need be
		if(in_array('add_categories',$op)) {
			for(reset($data); NULL !== ($name = key($data)); next($data)) {
				if(!isset($category_map[$name])) {
					if($new_categoryid = &$tree->create_category($name,$this->categoryid,0)) {
						$ms[] = "New category '$name' created.";
						$category_map[$name] = $new_categoryid;
						$new_categoryids[] = $new_categoryid;
					}
				}
			}
		}

		# We now have a mapping of each imported category name to a categoryid
		# if there isn't a mapping well then that data isn't going in.. they've
		# decided they don't want us to create a new category for them.. so be it.
		#
		# On to phase two.
		foreach($category_map as $name => $categoryid) {
			$category = &$this->get_category($categoryid);
			$subdata = &$data[$name];

			 #################################################
			# Repeat the process above for the subcategories
			$existing_subcategoryids = &$tree->childids($categoryid);
			$subcategory_map    = array();
			$old_subcategoryids = array();
			foreach($existing_subcategoryids as $subcategoryid) {
				$name = &$tree->name($subcategoryid);
				if(isset($subdata[$name]) && in_array('overwrite_categories',$op)) {
					$subcategory_map[$name] = $subcategoryid;
				} elseif(in_array('delete_categories',$op)) {
					$ms[] = $this->massacre_category($subcategoryid);
					$old_subcategoryids[] = $subcategoryid;
				}
			}

			# Now create new subcategories if need be
			if(in_array('add_categories',$op)) {
				for(reset($subdata); NULL !== ($name = key($subdata)); next($subdata)) {
					if(!isset($subcategory_map[$name])) {
						if($new_subcategoryid = &$tree->create_category($name,$categoryid,0)) {
							$ms[] = "New subcategory '$name' created.";
							$subcategory_map[$name] = $new_subcategoryid;
							$new_subcategoryids[] = $new_subcategoryid;
						}
					}
				}
			}

			# We now have a mapping of each imported subcategory name to a subcategoryid
			# if there isn't a mapping well then that data isn't going in.. they've
			# decided they don't want us to create a new subcategory for them.. so be it.
			#
			# On to phase three.
			$width_attribute  = &$this->get_attribute($attribute_map['End Section Width']); 
			$height_attribute = &$this->get_attribute($attribute_map['End Section Height']); 
			$price_attribute  = &$this->get_attribute($attribute_map['Price']); 
			$length_attribute = &$this->get_attribute($attribute_map['Length']); 
			foreach($subcategory_map as $subname => $subcategoryid) {
				$subcategory = &$this->get_category($subcategoryid);

				# Firstly, let's change the default values for the end width
				# and height so that they reflect the records in this category
				list($width,$height) = explode(' x ', $subname); # we can rely on this since we did it earlier.

				$width_attribute->set_default_override($subcategoryid,$width,'replace','replace');
				$height_attribute->set_default_override($subcategoryid,$height,'replace','replace');

				$record_data = &$subdata[$subname];

				$existing_recordids = $subcategory->associated_records();

				$record_map = array();

				foreach($existing_recordids as $recordid) {
					$record = &$this->get_record($recordid);
					$code = &$record->get_attribute_value($attribute_map['Product Code']);
					if(isset($record_data[$code]) && in_array('overwrite_records',$op)) {
						$length_attribute->save_record_value($recordid, $record_data[$code]['l']);
						$record_map[$code] = $recordid;
					} elseif(in_array('delete_records',$op)) {
						$ms[] = $record->delete();
						$old_recordids[] = $recordid;
					}
				}

				# Now create new subcategories if need be
				if(in_array('add_records',$op)) {
					for(reset($record_data); NULL !== ($code = key($record_data)); next($record_data)) {
						if(!isset($record_map[$code])) {
							$values = array (
								$attribute_map['Product Code'] => $code,
								$attribute_map['Length'] => $record_data[$code]['l'],
								$attribute_map['Price'] => 0,
							);
							if($new_recordid = &$subcategory->create_record($values)) {
								$record = &$this->get_record($new_recordid);
								$ms[] = "New product '".$record->name()."' created.";
								$record_map[$code] = $new_recordid;
								$new_recordids[] = $new_recordid;
							}
						}
					}
				}

				# And last but not least, set all the prices in the variety set
				foreach($record_map as $code => $recordid) {
					$record = &$this->get_record($recordid);
					$prices  = $record_data[$code]['p'];
					$variety_set = $this->get_variety_set($discount_variety_setid);
					foreach($variety_set->get_variety_names($this->categoryid) as $coord => $name) {
						$priceid = (int) ereg_replace('^x'.$attribute_map['Trade Discounts'].'i',"",$coord);
						$price_attribute->save_variation_value($recordid, $discount_variety_setid, $coord, 'replace',$prices["$priceid"]);
					}
					$record->clear_cache();
				}

			}

		}

		#$db = &$this->get_db();
		#$db->print_log();

		return implode("\n",$ms);

	}


	 #####################################################################
	# Intense recursive category deletion.. to be used with eXXtreme care
	function massacre_category($categoryid) {

		$category = &$this->get_category($categoryid);
		if(!$category->id) return;

		$tree = &$this->get_category_tree();

		# First wipe out all the subcategories.
		foreach($tree->childids($categoryid) as $childid) {
			$ms[] = $this->massacre_category($childid);
		}

		# Now all the records
		foreach($category->associated_records() as $recordid) {
			$record = &$this->get_record($recordid);
			if(count($record->categoryids) <= 1) { # would be orphaned
				$ms[] = $record->delete();
			}
		}

		# The category will clean up the rest of the stuff (attributes etc);
		$ms[] = $category->delete();

		return implode("\n",$ms);

	}


	 #########################
	# Prints out instructions
	function print_instructions_intro() {
		$tree = &$this->get_category_tree();
		?>
		<p>Upload a CSV file using the file format described below. A CSV file is a text file in which a spreadsheet is represented by seperating rows by linebreaks (\n or hitting Enter), and columns are seperated by commas or tab-spaces. Microsoft Excel can export a into either format.

		<p>It is important that there be no more columns than are necessary and there there are no blank or unfinished rows. The import will cease on the first blank row.

		<p>For every unique value in the <b>Category</b> column, a subcategory will be created below the current one (<i><?echo(($this->categoryid)?$tree->name($this->categoryid):'the root category in this case')?></i>). They will be created in the order they appear in the spreadsheet. For each <b>End Width/Height</b> combination within each subcategory, a <i>sub</i>subcategory will be created called, for example, "90 x 90". It is in these second-level categories that the records will be placed.

		<p>Each record must have a <b>product code</b>; if a row is missing a record code, no record will be created.

		<p>If during the import the system tries to create a category that already exists (i.e. has the same name as one already in the same spot), then a new one will not be created and any new records will go into the existing category.

		<p>If during the import the system tries to create a record that already exists (i.e. has the same <b>product code</b> as one already in the same category), then a new one will not be created and the details of the current one will be updated.
		<?
	}
	function print_instructions_file_format() {
		?>
		<table cellpadding=1 cellspacing=0 border=0 bgcolor=black><tr><td>
		<table cellpadding=3 cellspacing=1 border=0>
			<tr bgcolor=#e0e0e0>
				<td class=fineprint><b>Category</td>
				<td class=fineprint><b>Product Code</td>
				<td class=fineprint><b>End Width (mm)</td>
				<td class=fineprint><b>End Height (mm)</td>
				<td class=fineprint><b>Length (m)</td>
				<td class=fineprint><b>Price 1</td>
				<td class=fineprint><b>Price 2</td>
				<td class=fineprint><b>Price ...</td>
			</tr>
			<tr></td>
			<tr bgcolor=#f0f0f0>
				<td class=fineprint>A</td>
				<td class=fineprint>09009010F7TR</td>
				<td class=fineprint>90</td>
				<td class=fineprint>90</td>
				<td class=fineprint>1.0</td>
				<td class=fineprint>12.05</td>
				<td class=fineprint>12.76</td>
				<td class=fineprint></td>
			</tr>
			<tr bgcolor=#f0f0f0>
				<td class=fineprint>...</td>
				<td class=fineprint></td>
				<td class=fineprint></td>
				<td class=fineprint></td>
				<td class=fineprint></td>
				<td class=fineprint></td>
				<td class=fineprint></td>
				<td class=fineprint></td>
			</tr>
		</table></td></tr></table>
		<?
	}


	 #############################################
	# Prints the form for uploading the csv file.
	function print_file_upload_form() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		echo file_upload('csv_file', "$e->data_path", 50000000, "$this->codename"."_import.{$session->user->id}.tmp",array('csv','tab'));
	}


	 #############################################
	# Prints the form for uploading the csv file.
	function process_file_upload_form() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		commit_file_upload('csv_file', 1);
		if(file_exists($f = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp")) {
			$delimiters = array('comma'=>',','tab'=>"\t");
			global $SQUIZLIB_PATH;
			include_once("$SQUIZLIB_PATH/csv/csv.inc");
			$csv = new Csv($f);
			$csv->set_deliminator($delimiters[$this->parameters[delimiter]]);
			$csv->import();
			unlink($f);

			return $this->import($csv->values);
		}
	}

}


?>