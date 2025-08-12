<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/designs_areas/searchbox/searchbox.inc
## Desc: this class is used to print the customised search box on the page
## $Source: /home/cvsroot/xtras/site/design_areas/searchbox/searchbox.inc,v $
## $Revision: 2.19.2.1 $
## $Author: dofford $
## $Date: 2004/05/25 03:33:07 $
#######################################################################	
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_SearchBox extends Site_Design_Area {

	var $_hidden_areas;		# an array for holding the sections and sub_sections
							# of the menu that should not be printed
							# DOES NOT get saved


	function Site_Design_Area_SearchBox(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;
		# set up some default variables, 
		# so unless they write over them we have something to play with

		#editable
		$this->_set_var('intro_text', '', 'text', 'text to appear before the boxes');
		$this->_set_var('class', 'fineprint', 'text', 'stylesheet class for the text and boxes');
		$this->_set_var('form_class', 'fineprint', 'text', 'stylesheet class for the form');
		$this->_set_var('show_domain', '1', 'boolean', '');
		$this->_set_var('default_domain', 'S', 'set', '', array('S'=>'Current Site', 'A' => 'All Sites'));
		$this->_set_var('domain_labels', 't', 'set', '', array('g' => 'Generic', 't' => 'Use Titles'));
		$this->_set_var('show_type', '1', 'boolean');
		$this->_set_var('show_joining_words','1','boolean','');
		$this->_set_var('for_joining_words','for','text','the text shown before the search field itself');
		$this->_set_var('default_type', 'pf', 'set', '', array('p' => 'Pages', 'f' => 'Files', 'pf' => 'Pages & Files'));
		$this->_set_var('input_size', '5', 'text', 'size of the text box');
		$this->_set_var('show_submit', '1', 'boolean', '');
		$this->_set_var('allow_wrapping','0','boolean','');

		#uneditable
		$this->_set_var('intro_image', '', 'text');
		$this->_set_var('submit_image', '', 'text');
		$this->_set_var('table_padding', '2', 'text');
		$this->_set_var('customisable_domain', '1', 'boolean', '');
		$this->_set_var('customisable_submit', '1', 'boolean', '');
		$this->_set_var('customisable_category','1','boolean', '');

	}#end Site_Design_Area_SearchBox()

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag, true);

		# no additional attributes needed
		return;

	}#end create()

	/**
	* Prints the searchbox on the frontend using the values specified in your parse file
	*
	* This function now does some elaborate checking to see what kind of types shoudl be included in the search
	* (e.g. should you search for pages only, files only, or pages and files?) this is dependent on what's
	* in the design that is responsible for printing out the search results.
	*
	* @param string &$tag_attributes 
	*/
	function paint(&$tag_attributes) {

		if (isset($this->_hidden_areas['searchbox'])) return;

		$web_system = &get_web_system();
		$site = &$web_system->get_site();
		$search_extension = &$site->get_extension('search');

		# Now that we check the design to see if we have the proper searchresults design areas,
		# we need to get the design responsible for printing the searchresults and examine it.
		# so.... 

		# find out if we're using a specific page to print the results
		$results_pageid = $search_extension->get_results_page();
		if($results_pageid) {  
			# get that page's design
			$result_page = &$site->get_page($results_pageid); 
			$design = $result_page->get_design();
		} else {
			# just get the current page's design
			$page = &$this->get_page();
			$design = $page->get_design();
			# if for some reason that fails, get the site design
			if(!$design) {
				$design = $site->get_design();
			}
		}

		$search_categories = Array(0=>'All pages');
		$search_categories = $search_categories + $search_extension->get_categories();
		$search_domain = $_REQUEST['search_domain'];
		$search_type = $_REQUEST['search_type'];
		$q = gpc_stripslashes($_REQUEST['q']);
		$search_category = $_REQUEST['search_category'];

		$site = &$this->get_site();
		$page_index = &$site->get_page_index();
		$page = &$this->get_page();

		# user's first time here...
		if(!$search_type) {
			# we will have to build the search type var from the searchresults
			# areas in the parse file.
			$search_type = '';
			$rebuild = true;
		} else {
			# we already have a default type
			$rebuild = false;
		}

		foreach($design->design_areas['searchresults']->results as $id => $type) {
			$type = strtolower(substr($id,0,1));
			switch($type) {
				case 'p': $type_array['p'] = 'Pages Only'; break;
				case 'f': $type_array['f'] = 'Files Only'; break;
			}
			if($rebuild) $search_type .= $type;
		}

		# if both the pages and files are in the parse file then add p&f option to the
		# search_type array
		if($type_array['p'] && $type_array['f']) {
			$type_array['pf'] = 'Pages & Files';
		}


		$default_domain = ($search_domain) ? $search_domain : $this->get_val('default_domain');
		$default_type   = $search_type;

		# because you specify the text for the default category, we have to find the corresponding
		# key in the search categories array.
		if($search_category) {
			$default_category = $search_category;
		} else {
			$default_category = $this->get_val('default_category');
			$ids = array_keys($search_categories);

			#could be a string, could be a number, so I'm using array_search over in_array
			if(!(array_search($default_category,$ids))) { 
				# if it's not already an id then let's try to match strings on the category name
				if(in_array($default_category,$search_categories)) {
					foreach($search_categories as $k=>$v) {
						if(strtolower($v) == strtolower($default_category)) $default_category = $k;
					}
				} else { #okay fine no match, search all pages
					$default_category = 0;
				}
			}
		}
		$wrap = $this->get_val("allow_wrapping");
	?>
	<form action="<?$_SERVER['PHP_SELF']?>" name="mysourcesearch" method="get" onsubmit="if (this.q.value.length > 2) return true; else return false;" class="<?=$this->get_val('form_class');?>">
	<table cellpadding="<?=$this->get_val("table_padding");?>" cellspacing="0" border="0">
		<tr>
		<? 
		if(!$wrap){?><td><?}
		
		$image = $this->get_image_href("intro_image");
		if ($image) {
			?><img src="<?=$image?>" style="border-style: none"><?
		#only if there is something to print
		} else if ($this->get_val("intro_text")) {
			?><p class="<?=$this->get_val("class")?>"><?echo $this->get_val("intro_text"); 
		}#end if

		if(!$wrap){?></td><?}else{?> <?}

		function is_yes($input) {
			return (strcasecmp($input, "yes") == 0) || ($input == 1);
		}

		
		# if we want the domain, show it
		if (is_yes($this->get_val("show_domain"))) {

			if(!$wrap){?><td><?}
			?>
			<p class="<?=$this->get_val("class")?>">
			<select class="<?=$this->get_val("class")?>" name="search_domain">
			<? if (is_array($page_index[$page->id][childids]) && count($page_index[$page->id][childids]) > 0) { ?>
				<option value="P"><? echo (($this->get_val("domain_labels")=='t')?ellipsisize($page->short_name,25):'Subpages') ?>
			<? } ?>
				<option value="S"<? echo (($default_domain == "S")?" selected":""); ?>><? echo (($this->get_val("domain_labels")=='t')?ellipsisize($site->name,25):'This Site')?>
				<option value="A"<? echo (($default_domain == "A")?" selected":""); ?>>All Sites
			</select>
			<?
			if(!$wrap){?></td><?}
		} else { 
			if(!$wrap){?><td><?}
			?><input type="hidden" name="search_domain" value="<?=$default_domain?>"><?
			if(!$wrap){?></td><?}
		} #end if 

		if(!$wrap){?><td><?}else{?> <?}

		# if we want the type, show it
		if (is_yes($this->get_val("show_type"))) {
			echo combo_box("search_type",$type_array,$default_type,"class=".$this->get_val("class"));
		} else {
			?><input type="hidden" name="search_type" value="<?=$default_type?>"><?
		} #end if 

		if(!$wrap){?></td><?}else{?> <?}

		if(is_yes($this->get_val("show_category"))) {
			if(!$wrap) { echo '<td>'; }
			echo combo_box("search_category", $search_categories, $default_category,"class=".$this->get_val("class"));
			if(!$wrap) { echo '</td>'; }
		} else {
			?><input type="hidden" name="search_category" value="<?=$default_category?>"><?
		} #end if

		# if we want either the domain, the type, the category or all of the above we need joining words
		if ((is_yes($this->get_val("show_domain") || is_yes($this->get_val("show_type")) || is_yes($this->get_val("show_category")))) && is_yes($this->get_val("show_joining_words"))) {

			if(!$wrap){?><td><?}else{?> <?}
			?><span class="<?=$this->get_val("class")?>"><?=$this->get_val("for_joining_words")?></span><?
			if(!$wrap){?></td><?}else{?> <?}
		}#end if no domain or type

		if(!$wrap){?><td><?}else{?> <?}
		?><input class="<?=$this->get_val("class")?>" type="text" name="q" value="<? echo ereg_replace("\"","&quot;",$q) ?>" size="<?=$this->get_val("input_size");?>" maxlength="50"><?
		
		if(!$wrap){?></td><?}else{?> <?}

		if (is_yes($this->get_val("show_submit"))) {
			if(!$wrap){?><td><?}else{?> <?}
			$image      = $this->get_image_href("submit_image");
			if ($image) {
				?>&nbsp;<input type="image" src="<?=$image?>" name="Go" value="Go" style="border-style: none"><?
			} else { # no image use default input button
				?><input type="submit" class="<?=$this->get_val("class")?>" name="Go" value="Go"><?
			}#e3nd if
			if(!$wrap){?></td><?}else{?> <?}
		}#end if no submit

		if(!$wrap){?><td><?}else{?> <?}
		?>
			<input type="hidden" name="siteid" value="<?=$site->id?>">
			<input type="hidden" name="pageid" value="<?=$page->id?>">
			<input type="hidden" name="mysource_site_extension" value="search">
			<input type="hidden" name="new_search" value="true">
		
		<?
		if(!$wrap){?></td><?}else{?> <?}
		?>
		</tr>
	</table>
	</form>
	<?

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name) {
		$web_system = &get_web_system();
		$site = &$web_system->get_site();
		$search_extension = &$site->get_extension('search');
		$search_categories = array();
		$search_categories[] = 'ALL Pages';
		$search_categories = $search_categories + $search_extension->get_categories();
		$category = $this->get_val('default_category');

		# because you specify the text for the default category, we have to find the corresponding
		# key in the search categories array.
		$ids = array_keys($search_categories);
		#could be a string, could be a number, so I'm using array_search over in_array
		if(!(array_search($category,$ids))) { 
			# if it's not already an id then let's try to match strings on the category name
			if(in_array($category,$search_categories)) {
				foreach($search_categories as $k=>$v) {
					if(strtolower($v) == strtolower($category)) $category = $k;
				}
			} else { #okay fine no match, search all pages
				$category = 0;
			}
		}
		$this->_set_var('default_category',$category,'set','a drop-down of the groups of pages defined in the site search extension',$search_categories);

		$changes_made = false;

		$uneditable = array("submit_image", "table_padding", "intro_image", "customisable_domain", "customisable_submit","customisable_category");

		# if we ain't allowed to customise the domain then it be uneditable
		if (!$this->get_val("customisable_domain")) {
			$uneditable[] = "show_domain";
			$uneditable[] = "default_domain";
		}
		# if we ain't allowed to customise the submit then it be uneditable
		if (!$this->get_val("customisable_submit")) {
			$uneditable[] = "show_submit";
		}
		# if there is an intro image no use editing the text
		if ($this->get_val("intro_image")) {
			$uneditable[] = "intro_text";
		}
		# if we can't customise the category then it be uneditable
		if (!$this->get_val("customisable_category")) {
			$uneditable[] = "show_category";
			$uneditable[] = "default_category";
		}

		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable);

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		return "Change the appearance of the search area and the scope of the search";

	}#end get_description()



	function get_area_array() {
		return array('searchbox');
	}#end get_area_array()

}#end class Site_Design_Area_SearchBox

?>