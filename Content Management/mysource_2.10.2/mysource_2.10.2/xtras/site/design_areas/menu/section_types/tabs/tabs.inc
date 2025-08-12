<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
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
## Desc: looks after the printing of tabbed menu designs 
## $Source: /home/cvsroot/xtras/site/design_areas/menu/section_types/tabs/tabs.inc,v $
## $Revision: 2.14 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Menu_Section_Type_Tabs extends Site_Design_Area_Menu_Section_Type {

	var $sub_section; # the section within this tag that is looped over for each page we are printing

	function Site_Design_Area_Menu_Section_Type_Tabs(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		  ################
		 #   EDITABLE   #
		################
		$this->_set_var("class", "", "text", "");
		$this->_set_var("class_current", "", "text", "");
		$this->_set_var("target", "", "text", "The name of the target frame for the link");
		$this->_set_var("num_tabs_per_row", "0", "set", "", array("0" => "Always in One Row", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10"));

		  ################
		 #  UNEDITABLE  #
		################

		$this->_set_var("vertical_alignment", "bottom", "set", "", array("bottom" => "Bottom", "top" => "Top"));

		### Background Image for the Top of the Tabs ###
		$this->_set_var("line_image",          "", "text");
		$this->_set_var("line_image_selected", "", "text");

		### bullet image (if any) ###
		$this->_set_var("bullet_image", "", "text");
		$this->_set_var("bullet_image_current", "", "text");

		 ###################################
		# IMAGES FOR THE TOP ROW OF TABS 
		### Start of a Row ###
		# Top of the Image
		$this->_set_var("top_start_top_image", "", "text");
		$this->_set_var("top_start_top_selected_image", "", "text");
		$this->_set_var("top_start_top_extra", "", "text");
		# The rest of Image
		$this->_set_var("top_start_image", "", "text");
		$this->_set_var("top_start_selected_image", "", "text");
		$this->_set_var("top_start_extra", "", "text");
		# Background Image that is needed when the tab wraps
		$this->_set_var("top_start_bg_image", "", "text");
		$this->_set_var("top_start_bg_selected_image", "", "text");

		### End of a Row ###
		# Top of the Image
		$this->_set_var("top_end_top_image", "", "text");
		$this->_set_var("top_end_top_selected_image", "", "text");
		$this->_set_var("top_end_top_extra", "", "text");
		# The rest of Image
		$this->_set_var("top_end_image", "", "text");
		$this->_set_var("top_end_selected_image", "", "text");
		$this->_set_var("top_end_extra", "", "text");
		# Background Image that is needed when the tab wraps
		$this->_set_var("top_end_bg_image", "", "text");
		$this->_set_var("top_end_bg_selected_image", "", "text");

		### Middle of a Row ###
		# Top of the Image
		$this->_set_var("top_centre_top_image", "", "text");
		$this->_set_var("top_centre_top_selected_right_image", "", "text");
		$this->_set_var("top_centre_top_selected_left_image",  "", "text");
		$this->_set_var("top_centre_top_extra", "", "text");
		# The rest of Image
		$this->_set_var("top_centre_image", "", "text");
		$this->_set_var("top_centre_extra", "", "text");
		$this->_set_var("top_centre_selected_right_image", "", "text");
		$this->_set_var("top_centre_selected_left_image",  "", "text");
		# Background Image that is needed when the tab wraps
		$this->_set_var("top_centre_bg_image", "", "text");
		$this->_set_var("top_centre_bg_selected_right_image", "", "text");
		$this->_set_var("top_centre_bg_selected_left_image",  "", "text");



		 ###########################################################
		# IMAGES FOR THE TABS THAT ARE NOT THE TOP ROW OF TABS
		### Start of a Row ###
		# Top of the Image
		$this->_set_var("start_top_image", "", "text");
		$this->_set_var("start_top_selected_image", "", "text");
		$this->_set_var("start_top_extra", "", "text");
		# The rest of Image
		$this->_set_var("start_image", "", "text");
		$this->_set_var("start_selected_image", "", "text");
		$this->_set_var("start_extra", "", "text");
		# Background Image that is needed when the tab wraps
		$this->_set_var("start_bg_image", "", "text");
		$this->_set_var("start_bg_selected_image", "", "text");

		### End of a Row ### 
		# Top of the Image 
		$this->_set_var("end_top_image", "", "text");
		$this->_set_var("end_top_selected_image", "", "text");
		$this->_set_var("end_top_extra", "", "text");
		# The rest of Image 
		$this->_set_var("end_image", "", "text");
		$this->_set_var("end_selected_image", "", "text");
		$this->_set_var("end_extra", "", "text");
		# Background Image that is needed when the tab wraps 
		$this->_set_var("end_bg_image", "", "text");
		$this->_set_var("end_bg_selected_image", "", "text");

		### Middle of a Row ### 
		# Top of the Image 
		$this->_set_var("centre_top_image", "", "text");
		$this->_set_var("centre_top_selected_right_image", "", "text");
		$this->_set_var("centre_top_selected_left_image",  "", "text");
		$this->_set_var("centre_top_extra", "", "text");
		# The rest of Image 
		$this->_set_var("centre_image", "", "text");
		$this->_set_var("centre_extra", "", "text");
		$this->_set_var("centre_selected_right_image", "", "text");
		$this->_set_var("centre_selected_left_image", "", "text");
		# Background Image that is needed when the tab wraps 
		$this->_set_var("centre_bg_image", "", "text");
		$this->_set_var("centre_bg_selected_right_image", "", "text");
		$this->_set_var("centre_bg_selected_left_image", "", "text");

		  #################################################################
		 # IMAGES FOR THE LINE THAT "SHOWS" WHICH TAB IS SELECTED, 
		# THE BASE FROM WHICH ALL THE TABS START
		### Start of a Row ###
		$this->_set_var("baseline_start_image", "", "text");
		$this->_set_var("baseline_start_extra", "", "text");
		$this->_set_var("baseline_start_selected_image", "", "text");

		### End of a Row ###
		$this->_set_var("baseline_end_image", "", "text");
		$this->_set_var("baseline_end_extra", "", "text");
		$this->_set_var("baseline_end_selected_image", "", "text");

		### Middle of a Row ###
		$this->_set_var("baseline_centre_image", "", "text");
		$this->_set_var("baseline_centre_extra", "", "text");
		$this->_set_var("baseline_centre_selected_right_image", "", "text");
		$this->_set_var("baseline_centre_selected_left_image", "", "text");

		### The lines above or below (depending on vertical alignment) the actual tab contents ###
		$this->_set_var("baseline_image", "", "text");
		$this->_set_var("baseline_selected_image", "", "text");
	
	}#end constructor

	 #######################################################################
	# create the object from the tag
	function create(&$tag, $level) {

		 ############################################################################
		# Only set up any variables, because we don't accept any HTML or other stuff here
		$this->_set_variables($tag, true);

	}#end create()
 
	 ###################################################
	# using some special variables that have been set, 
	# create a tabbed interface for the passed pages
	function paint($pages, &$exclude_pageids, $level, $pageid) {

		$pages = $this->get_pages_to_print($pages);

		# do this so that we have a proper indexed array
		# Array(0 => 12, 1 => 34, 2 => 56, ....);
		$pages = array_values($pages);

		# remove all pages that are to be excluded
		foreach($exclude_pageids as $pageID) {

			# if the page is in the array remove it
			list($key) = array_keys($pages, $pageID);
			if (isset($key)) {
				# move all other ids down
				for($i = $key; $i < count($pages) - 1; $i++) {
					$pages[$i] = $pages[$i + 1];
				}#end for
				# remove the last element
				array_pop($pages);

			}#end if
		}#end foreach

		if (!$pages) return;

		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		$page_index = &$site->get_page_index();

		$page_lineage = array_keys($page->get_lineage());

		# set up some vars that we need 
		$num_tabs_per_row = intval($this->get_val('num_tabs_per_row'));
		if (!$num_tabs_per_row) $num_tabs_per_row = count($pages);
		$num_rows = ceil(count($pages) / $num_tabs_per_row);

		$align_rows = $this->get_val("align_rows");
		if ($align_rows) $align_rows = "align='$align_rows'";


		$current_row = 0;
		# split the pages up into multiple rows
		$rows = array();
		$i = 0;
		# create an array of rows
		for($row = 0; $row < $num_rows; $row++) {
			$rows[$row] = Array();
			# create an array of rows
			for($j = 0; ($j < $num_tabs_per_row && $i < count($pages)); $j++) {
				$rows[$row][] = $pages[$i];
				if ($page->id == $pages[$i]) $current_row = $row;
				$i++;
			
			}#end for
		}#end for

		# move the current row to the front of the array
		for($i = $current_row; $i > 0; $i--) {
			$tmp = $rows[$i];
			$rows[$i] = $rows[$i - 1];
			$rows[$i - 1] = $tmp;
		}#end for

		$this->_print_tabs_table(0, $rows, $page_index, $page_lineage);

	}#end print_tabs_pages()

	 ###################################################
	# print the table for the passed for in rows
	function _print_tabs_table($rowid, &$rows, &$page_index, &$page_lineage) {

		# get the alignment of the tabs
		$valign = $this->get_val('vertical_alignment');
		# set a var for the alignment of the images
		$td_valign = ($valign == 'top') ? 'bottom' : 'top';

	?> 
		<table border="0" cellspacing="0" cellpadding="0" <?=($rowid) ? 'width="100%"' : '';?> background="">
	<?

		# if this is the top row of the tabs then use the 'top_' prefixed variables
		$var_prefix = ($rowid == count($rows) - 1) ? 'top_' : '';
		$img_prefix = $this->get_val('IMAGE_PREFIX');
		$contents_colspan = ($this->get_val('BULLET_IMAGE')) ? 'colspan="2"' : '';

		$target = '';
		if ($target = $this->get_val('target')) {
			$target = 'target="'.$target.'"';
		}

		if ($valign == 'top') {
			$this->_print_tabs_baseline($rowid, $rows, $img_prefix, $var_prefix, $contents_colspan, $page_index, $page_lineage);
		}#end if

		if ($valign == 'bottom') {
			# print any nested rows
			$this->_print_nested_tabs_tables($rowid, $rows, $page_index, $page_lineage);
			# print the top of this row
			$this->_print_tabs_topline($rowid, $rows, $img_prefix, $var_prefix, $contents_colspan, $page_index, $page_lineage);
		}#end if

		$row = &$rows[$rowid];

		$start_image    = '';
		$start_bg_image = '';
		if (in_array($row[0], $page_lineage)) {
			$start_image    = $this->get_val($var_prefix.'start_selected_image');
			$start_bg_image = $this->get_val($var_prefix.'start_bg_selected_image');
		}
		if (!$start_image)    $start_image    = $this->get_val($var_prefix.'start_image');
		if (!$start_bg_image) $start_bg_image = $this->get_val($var_prefix.'start_bg_image');

		$end_image    = '';
		$end_bg_image = '';
		if (in_array($row[count($row) -1], $page_lineage)) {
			$end_image    = $this->get_val($var_prefix.'end_selected_image');
			$end_bg_image = $this->get_val($var_prefix.'end_bg_selected_image');
		}
		if (!$end_image)    $end_image    = $this->get_val($var_prefix.'end_image');
		if (!$end_bg_image) $end_bg_image = $this->get_val($var_prefix.'end_bg_image');

		 ###########################################
		# print out the content lines of the rows
		?> 
		<tr>
			<td background="<?=$img_prefix.$start_bg_image?>" valign="<?=$td_valign?>"><img src="<?=$img_prefix.$start_image?>" <?=$this->get_val($var_prefix."start_extra");?> align="<?=$td_valign?>"></td>
		<?
		for($i = 0; $i < count($row); $i++) {
			$this->_active_pageid = $pageid;
			$class = $this->get_val("class", $row[$i]);
			if($class) $class = 'class="'.$class.'"';

			if ($bullet = $this->get_val("BULLET_IMAGE", $row[$i])) {
			?>
				<td valign="top" <?=$class?> align="right"><img src="<?=$bullet?>" align="right" border="0"></td>
			<?
			}#end if
		?> 
			<td valign="top" <?=$class?>><a href="<?=$this->get_val("page_link",(int)$row[$i])?>" <?=$target?> <?=$class?> onmouseover="window.status='<?=str_replace("\"","&quot;",addslashes($page_index[$row[$i]]['name']))?>';return true;" onmouseout="window.status='';return true;"><? echo $page_index[$row[$i]]['short_name'] ?></a></td>
		<?
			# if we ain't the last tab print the centre piece
			if ($i < count($row) - 1) {
				$centre_selected = "";
				$centre_image    = "";
				$centre_bg_image = "";
				# if this tab is selected then
				if (in_array($row[$i], $page_lineage)) {
					$centre_selected = "left";

				# else if the next tab is selected
				} elseif (in_array($row[$i + 1], $page_lineage)) {
					$centre_selected = "right";

				}#end if

				if ($centre_selected) {
					$centre_image    = $this->get_val($var_prefix."centre_selected_".$centre_selected."_image");
					$centre_bg_image = $this->get_val($var_prefix."centre_bg_selected_".$centre_selected."_image");
				}#end if
				if (!$centre_image)    $centre_image    = $this->get_val($var_prefix."centre_image");
				if (!$centre_bg_image) $centre_bg_image = $this->get_val($var_prefix."centre_bg_image");

			?> 
				<td valign="<?=$td_valign?>" background="<?=$img_prefix.$centre_bg_image?>"><img src="<?=$img_prefix.$centre_image?>" <?=$this->get_val($var_prefix."centre_extra");?> align="<?=$td_valign?>"></td>
			<?
			}#endif 
		}#end for
		$this->_active_pageid = 0;
		?> 
			<td valign="<?=$td_valign?>" background="<?=$img_prefix.$end_bg_image?>"><img src="<?=$img_prefix.$end_image?>" <?=$this->get_val($var_prefix."end_extra");?> align="<?=$td_valign?>"></td>
		</tr>
		<?

		if ($valign == "bottom") {
			$this->_print_tabs_baseline($rowid, $rows, $img_prefix, $var_prefix, $contents_colspan, $page_index, $page_lineage);
		}#end if

		if ($valign == "top") {
			# print the top of this row
			$this->_print_tabs_topline($rowid, $rows, $img_prefix, $var_prefix, $contents_colspan, $page_index, $page_lineage);
			# print any nested rows
			$this->_print_nested_tabs_tables($rowid, $rows, $page_index, $page_lineage);
		}#end if

	?> 
		</table>
	<?

	}#end _print_tabs_table()

	 #############################################################################
	# if there are other rows above the current one then print them nested
	function _print_nested_tabs_tables($rowid, &$rows, &$page_index, &$page_lineage) {

		# if there are rows above this one then nest them in a table row
		if ($rowid < count($rows) - 1) {
		?> 
			<tr>
				<td><img src="<?=lib_href('images/blank.gif');?>" width="1" height="1" border="0" align="bottom"></td>
				<td colspan="<?=(count($rows[$rowid]) * 3) - 1?>"> 
				<? 
					$this->_print_tabs_table($rowid + 1, $rows, $page_index, $page_lineage); 
				?> 
				</td>
				<td><img src="<?=lib_href('images/blank.gif');?>" width="1" height="1" border="0" align="bottom"></td>
			</tr>
		<?
		}#end if

	}#end _print_nested_tabs_table()

	 ##############################################################################################
	# print out the connector from the tabs to the design that show which tab has been selected
	function _print_tabs_baseline($rowid, &$rows, $img_prefix, $var_prefix, $contents_colspan, &$page_index, &$page_lineage) {

		 #######################################################
		# if we are the base row then print out the baseline row
		if ($rowid == 0) {

			$row = &$rows[$rowid];

			$start_selected = (in_array($row[0], $page_lineage)) ? "_selected" : "";
			$end_selected   = (in_array($row[count($row) -1], $page_lineage)) ? "_selected" : "";

		?> 
			<tr>
				<td valign="bottom"><img src="<?=$img_prefix.$this->get_val("baseline_start".$start_selected."_image");?>" <?=$this->get_val("baseline_start_extra");?>></td>
		<?
			for($i = 0; $i < count($row); $i++) {
				$tab_selected = "";
				$centre_selected = "";
				$next_tab_selected = "";

				if (in_array($row[$i], $page_lineage)) {
					$tab_selected = "_selected";
					$centre_selected = "_selected_left";
				} 

			?> 
				<td valign="bottom" background="<?=$img_prefix.$this->get_val("baseline".$tab_selected."_image");?>" <?=$contents_colspan?>><img src="<?=lib_href('images/blank.gif');?>" width="1" height="1" border="0" align="bottom"></td>
			<?
				# if we ain't the last tab print the centre piece
				if ($i < count($row) - 1) {
					if (in_array($row[$i + 1], $page_lineage)) {
						$next_tab_selected = "_selected";
						$centre_selected   = "_selected_right";
					}
				?> 
					<td valign="bottom" background="<?=$img_prefix.$this->get_val("baseline".$next_tab_selected."_image");?>"><img src="<?=$img_prefix.$this->get_val("baseline_centre".$centre_selected."_image");?>" <?=$this->get_val("baseline_centre_extra");?> align="bottom"></td>
				<?
				}#endif 

			}#end for
			
		?> 
			<td valign="bottom"><img src="<?=$img_prefix.$this->get_val("baseline_end".$end_selected."_image");?>" <?=$this->get_val("baseline_end_extra");?>></td>
		</tr>
		<?

		}#end if print baseline

	}#end _print_tabs_baseline()

	 ##############################################################################################
	# print out the row that finished off the tab
	function _print_tabs_topline($rowid, &$rows, $img_prefix, $var_prefix, $contents_colspan, &$page_index, &$page_lineage) {

		$row = &$rows[$rowid];
		$start_image = "";
		$start_line_image = "";
		if (in_array($row[0], $page_lineage)) {
			$start_image = $this->get_val($var_prefix."start_top_selected_image");
			$start_line_image = $this->get_val("line_image_selected");
		}
		if (!$start_image) $start_image = $this->get_val($var_prefix."start_top_image");
		if (!$start_line_image) $start_line_image = $this->get_val("line_image");

		$end_image = "";
		if (in_array($row[count($row) -1], $page_lineage)) {
			$end_image = $this->get_val($var_prefix."end_top_selected_image");
		}
		if (!$end_image) $end_image = $this->get_val($var_prefix."end_top_image");

		$centre_image                = $this->get_val($var_prefix."centre_top_image");
		$centre_right_selected_image = $this->get_val($var_prefix."centre_top_selected_right_image");
		$centre_left_selected_image  = $this->get_val($var_prefix."centre_top_selected_left_image");
		if (!$centre_right_selected_image) $centre_right_selected_image = $centre_image;
		if (!$centre_left_selected_image)  $centre_left_selected_image  = $centre_image;

		 ########################################
		# print out the top lines of the rows
		?> 
		<tr>
			<td valign="bottom" background="<?=$img_prefix.$start_line_image?>"><img src="<?=$img_prefix.$start_image?>" <?=$this->get_val($var_prefix."start_top_extra");?>></td>
		<?
		for($i = 0; $i < count($row); $i++) {

			$tab_selected = "";
			$centre_selected = $centre_image;
			$next_tab_selected = "";

			if (in_array($row[$i], $page_lineage)) {
				$tab_selected = "_selected";
				$centre_selected = $centre_left_selected_image;
			} 
		
		?> 
			<td valign="bottom" background="<?=$img_prefix.$this->get_val("line_image".$tab_selected)?>" <?=$contents_colspan?>><img src="<?=lib_href('images/blank.gif');?>" width="1" height="1" border="0" align="bottom"></td>
		<?
			# if we ain't the last tab print the centre piece
			if ($i < count($row) - 1) {
				if (in_array($row[$i + 1], $page_lineage)) {
					$next_tab_selected = "_selected";
					$centre_selected = $centre_right_selected_image;
				}
		?> 
			<td valign="bottom" background="<?=$img_prefix.$this->get_val("line_image".$next_tab_selected)?>"><img src="<?=$img_prefix.$centre_selected?>" <?=$this->get_val($var_prefix."centre_top_extra");?>></td>
		<?
			}#endif 
		}
		?> 
			<td valign="bottom"><img src="<?=$img_prefix.$end_image;?>" <?=$this->get_val($var_prefix."end_top_extra");?>></td>
		</tr>
		<?

	}# end _print_tabs_topline()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid="") {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		switch(strtolower($name)) {
			case "class" : 
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid, true);
			break;

			case "bullet_image" :
				if ($this->_current_page($pageid)) {
					if (Site_Design_Area_Menu_Section_Type::get_val($name."_current")) {
						$name .= "_current";
					}
				}
				$name = strtolower($name);

				# if this image ain't in the cache go get it
				if (!isset($this->_get_val_cache[$name])) {
					$this->_get_val_cache[$name] = $this->get_image_href($name, true);
				}#end if isset
				return $this->_get_val_cache[$name];

			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="", $index) {
		$web_system = &get_web_system();

		$changes_made = false;

		$title = ucwords(str_replace("_", " ", $index));

		$uneditable = array("bullet_image", 
							"bullet_image_current",
							"vertical_alignment",
							"line_image",
							"line_image_selected",
							"top_start_top_image",
							"top_start_top_selected_image",
							"top_start_top_extra",
							"top_start_image",
							"top_start_selected_image",
							"top_start_extra",
							"top_start_bg_image",
							"top_start_bg_selected_image",
							"top_end_top_image",
							"top_end_top_selected_image",
							"top_end_top_extra",
							"top_end_image",
							"top_end_selected_image",
							"top_end_extra",
							"top_end_bg_image",
							"top_end_bg_selected_image",
							"top_centre_top_image",
							"top_centre_top_selected_right_image",
							"top_centre_top_selected_left_image",
							"top_centre_top_extra",
							"top_centre_image",
							"top_centre_extra",
							"top_centre_selected_right_image",
							"top_centre_selected_left_image",
							"top_centre_bg_image",
							"top_centre_bg_selected_right_image",
							"top_centre_bg_selected_left_image",
							"start_top_image",
							"start_top_selected_image",
							"start_top_extra",
							"start_image",
							"start_selected_image",
							"start_extra",
							"start_bg_image",
							"start_bg_selected_image",
							"end_top_image",
							"end_top_selected_image",
							"end_top_extra",
							"end_image",
							"end_selected_image",
							"end_extra",
							"end_bg_image",
							"end_bg_selected_image",
							"centre_top_image",
							"centre_top_selected_right_image",
							"centre_top_selected_left_image",
							"centre_top_extra",
							"centre_image",
							"centre_extra",
							"centre_selected_right_image",
							"centre_selected_left_image",
							"centre_bg_image",
							"centre_bg_selected_right_image",
							"centre_bg_selected_left_image",
							"baseline_start_image",
							"baseline_start_extra",
							"baseline_start_selected_image",
							"baseline_end_image",
							"baseline_end_extra",
							"baseline_end_selected_image",
							"baseline_centre_image",
							"baseline_centre_extra",
							"baseline_centre_selected_right_image",
							"baseline_centre_selected_left_image",
							"baseline_image",
							"baseline_selected_image"
						);


		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable, $title);

		return $changes_made;

	}#end print_user_backend()

}#end class Site_Design_Area_Menu_Section_Type_Tabs

?>