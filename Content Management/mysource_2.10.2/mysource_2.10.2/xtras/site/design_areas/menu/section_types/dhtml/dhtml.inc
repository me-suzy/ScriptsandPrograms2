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
## $Source: /home/cvsroot/xtras/site/design_areas/menu/section_types/dhtml/dhtml.inc,v $
## $Revision: 2.22 $
## $Author: brobertson $
## $Date: 2003/09/03 09:01:26 $
#######################################################################
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/dhtml_menu/dhtml_menu.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Menu_Section_Type_DHTML extends Site_Design_Area_Menu_Section_Type {

	var $sub_section; # the section within this tag that is looped over for each page we are printing
	var $dhtml_menu;  # the dhtml menu object that is used to print this menu
	var $variable_vars;	# an array of value names that have a value_type of 'variable'
						# we keep these because we create the DHTML menu object and set it's values
						# in the backend, but these variables will need to be overridden at paint time

	function Site_Design_Area_Menu_Section_Type_DHTML(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		  ################
		 #   EDITABLE   #
		################
		$this->_set_var('num_levels',					'',	'text', 'The number of levels into the hierarchy to show, blank or zero means show all');
		$this->_set_var('highlight_current_lineage',	true,		'boolean', 'Whether to keep the current page permanently highlighted');
		$this->_set_var('target',    					'',	'text', 'The name of the target frame for the link');


		$this->_set_var('menu_width',					100,		'text', '');
		$this->_set_var('left_position',				0,			'text', '');
		$this->_set_var('top_position',					0,			'text', '');
		$this->_set_var('font_color',					'000000',	'colour', '');
		$this->_set_var('mouseover_font_color',			'ffffff',	'colour', '');
		$this->_set_var('background_color',				'ffffff',	'colour', '');
		$this->_set_var('mouseover_background_color',	'000000',	'colour', '');
		$this->_set_var('border_color',					'',			'colour', '');
		$this->_set_var('separator_color',				'',			'colour', 'NB: Only effective for Internet Explorer');
		$this->_set_var('top_is_permanent',				true,		'boolean', '');
		$this->_set_var('top_is_horizontal',			true,		'boolean', '');
		$this->_set_var('tree_is_horizontal',			false,		'boolean', '');
		$this->_set_var('position_under',				true,		'boolean', '');
		$this->_set_var('top_more_images_visible',		true,		'boolean', '');
		$this->_set_var('tree_more_images_visible',		true,		'boolean', '');
		$this->_set_var('evaluate_upon_tree_show',		'null',		'text', '');
		$this->_set_var('evaluate_upon_tree_hide',		'null',		'text', '');
		$this->_set_var('right_to_left',				false,		'boolean', '');
		$this->_set_var('display_on_click',				false,		'boolean', '');
		$this->_set_var('top_is_variable_width',		false,		'boolean', '');
		$this->_set_var('tree_is_variable_width',		false,		'boolean', '');
		$this->_set_var('top_position_offset',			'',			'text', '');
		$this->_set_var('left_position_offset',			'',			'text', '');
		$this->_set_var('curr_tree_background_color',	'ebebeb',	'colour', '');
		$this->_set_var('semi_perm_hilite',				false,		'boolean', '');

		$this->_set_var('font_family',			'Arial,sans-serif',	'text',		'');
		$this->_set_var('font_size',			10,					'text',		'');
		$this->_set_var('font_bold',			true,				'boolean',	'');
		$this->_set_var('font_italic',			false,				'boolean',	'');
		$this->_set_var('item_padding',			3,					'text',		'');
		$this->_set_var('border_width',			0,					'text',		'');
		$this->_set_var('border_style',			'solid',			'set',		'Only Internet Explorer and Netscape 6 will render the border style. Navigator 4 will always render a border as "solid"', Array('solid' => 'solid', 'inset' => 'inset', 'groove' => 'groove', 'double' => 'double', 'outset' => 'outset', 'ridge' => 'ridge'));
		$this->_set_var('separator_size',		2,					'text',		'');
		$this->_set_var('image_src',			'',					'text',		'');
		$this->_set_var('image_src_over',		'',					'text',		'');
		$this->_set_var('image_src_left',		'',					'text',		'');
		$this->_set_var('image_src_left_over',	'',					'text',		'');
		$this->_set_var('image_size',			0,					'text',		'The width of the image');
		$this->_set_var('image_horiz_space',	0,					'text',		'');
		$this->_set_var('image_vert_space',		2,					'text',		'');
		$this->_set_var('keep_hilite',			true,				'boolean',	'keep the highlight for all selected items when drilling down the hierarchy');
		$this->_set_var('click_kill',			false,				'boolean',	'whether the menus disappear when the user clicks elsewhere in the page or after the leave the menu');
		$this->_set_var('child_overlap',		20,					'text',		'');
		$this->_set_var('child_offset',			10,					'text',		'');
		$this->_set_var('child_per_cent_over',	'',					'text',		'');
		$this->_set_var('top_seconds_visible',	0.5,				'text',		'');
		$this->_set_var('status_display_build',	false,				'boolean',	'display in the status bar the progress of the menu builing');	
		$this->_set_var('status_display_link',	false,				'boolean',	'display in the status bar the link that the page refers to');	
		$this->_set_var('show_link_cursor',		true,				'boolean',	'show the "hand" mouse pointer when over the items');
		$this->_set_var('screen_padding',		10, 				'text',		'the amount of padding that will appear between any menu item and the edge of the window');

		$this->_set_var('enforce_screen_width',			0,			'text', 'Width in pixels for the screen');
		$this->_set_var('enforce_screen_width_align',	'left',		'set',	'', Array('left' => 'Left', 'centre' => 'Centred', 'right' => 'Right'));

	}#end constructor

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {

		if ($this->sub_section) {
			$this->sub_section->reset_owner($this);
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		if ($this->sub_section) {
			$this->sub_section->unset_owner();
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if ($this->sub_section) {
			$tmp->sub_section = &$this->sub_section->copy();
		}
		return $tmp;

	}#end copy()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area_Menu_Section_Type::update($old_this)) return;

		if ($old_this->sub_section && $this->sub_section) {
			$this->sub_section->update($old_this->sub_section);
		}

	}#end update()

	 #######################################################################
	# create the object from the tag
	function create(&$tag, $level) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, 'menu');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case 'TAG' : 

					# if this is the sub section that we use to loop over the pages
					if ($element['attributes']['section'] == 'page') {

						# kill it because it's not needed anymore
						unset($element['attributes']['section']);

						$class_name = get_class($this).'_Page';
						
						$this->sub_section = new $class_name($this);
						$this->sub_section->create($element, $level);

						# append to the contents array so we know were to print this
						$this->contents[] = Array('_type' => 'SUB_SECTION');


					# else if this is a sub level ( the test for 'section' is only for backwards compatibilty)
					} else if ($element['attributes']['type'] || $element['attributes']['section']) {

						$contents_arr = Array('_type' => 'SUB_LEVEL');

						$menu = &$this->get_menu();

						# call create on it and return any additional attributes
						$additional_attributes = $menu->create_section($element, $level);

						# add the additional attributes returned by create()
						if (is_array($additional_attributes)) {
							$contents_arr = array_merge($contents_arr, $additional_attributes);
						}

						$this->contents[] = $contents_arr;

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

				case 'DESIGN_AREA' : # dealt with above so just add to contents
				case 'HTML'        : # nothing to do to this yet, so just add to contents array

						$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

		# now that we have all the variables they set lets set the dhtml menu's attributes
		$this->_set_dhtml_vars(true);

	}#end create()

	 ##################################################
	# set the dhtml menus vars from the _set_vars
	function _set_dhtml_vars($creation=false){

		# create the object if need be
		if (!$this->dhtml_menu || get_class($this->dhtml_menu) != 'dhtml_menu') {
			$this->dhtml_menu = new dhtml_menu();
		}#end if

		for(reset($this->_set_vars);
			NULL !== ($key = key($this->_set_vars));
			next($this->_set_vars)){

			# if the var is a variable reference or if it's an image src (to set the proper path)
			# then we will need to set it a paint time
			if ($this->_set_vars[$key]['value_type'] == 'variable' || strstr($key, 'image_src')) {
				# we only need to add this to the variable vars array if we are in creation mode
				if ($creation) $this->variable_vars[] = $key;
			} else {
				$this->_set_dhtml_var($key);
			}#end if

		}#end for

	}#end _set_dhtml_vars()

 	 ##################################################
	# set the dhtml menus vars from the _set_vars
	function _set_dhtml_var($name) {

		if ($name == 'num_levels') return;

		$var = $this->get_var($name);
		$val = $var['value'];
		# if it's a colour then we need to prepend the hash
		if ($var['value_type'] == 'colour' && strlen($val)) $val = '#'.$val;
		# if it's an image src then prepend the image path
		if (strstr($name, 'image_src') && $val) $val = $this->get_val('IMAGE_PREFIX').$val;
		$this->dhtml_menu->set_attribute($name, $val);

	}#end _set_dhtml_var()


	 ###################################################
	# using some special variables that have been set, 
	# create a tabbed interface for the passed pages
	function paint($pages, &$exclude_pageids, $level, $pageid) {

		$pages = $this->get_pages_to_print($pages);

		# if there ain't no pages, there ain't no need to do anymore
		if (!count($pages)) return; 

		$rel_path = squizlib_href('dhtml_menu','');

		# OK, let's set any variable vars into the DHTML menu
		foreach($this->variable_vars as $name) {
			$this->_set_dhtml_var($name);
		}#end foreach

		# if we have a sub-section then we need to loop over the contents array
		if ($this->sub_section) {

			foreach($this->contents as $element) {

				switch($element['_type']) {

					# sub level, call paint on the menu design area
					case 'SUB_LEVEL' :
						$menu = &$this->get_menu();
						$menu->paint_section($element);

					break;

					# plain html element just echo its contents
					case 'HTML' :
						echo $element['contents'];

					break;

					# nested design area so print/paint it
					case 'DESIGN_AREA' :
						$this->_print_nested_area($element);

					break;

					# taged element ... should really only be a print element
					case 'TAG' :

						switch($element['operation']) {
							case 'print' : 
								$this->print_val($element, $pageid);
							break;
						}

					break;

					# sub section element
					case 'SUB_SECTION' :
						$this->sub_section->paint($pages, $exclude_pageids, $rel_path, $pageid);

					break;

					default :
						$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
						unset($this);
						return;

				}#end switch

			}#end foreach contents

		# else there is only one fully DHTML menu so add the pages and create it
		} else {

			$page = &$this->get_page($pageid);
			$site = &$this->get_site($page->siteid);
			$page_index = &$site->get_page_index();

			$this->_add_pages($pages, 0, $page_index);
			$this->dhtml_menu->paint($rel_path);

		}#end if
		
		# it doesn't matter whether we have a sub section or not we only need to load the menu once		
		$this->dhtml_menu->load($rel_path);

	}#end paint()

	 ###################################################
	# adds the pages into the DHTML object
	function _add_pages(&$pages, $level, &$page_index) {

		# if there ain't no pages, there ain't no need to do anymore
		if (!count($pages)) return; 

		# if the number of levels was set and we have passed it don't go any further
		$num_levels = (int) $this->get_val('num_levels');
		if ($num_levels && $level >= $num_levels) return;
		$highlight_current = $this->get_val('highlight_current_lineage');
	
		for(reset($pages);
			NULL !== ($k = key($pages));
			next($pages)){
			$pageid = &$pages[$k];
			if(!$page_index[$pageid]['effective_visible']) continue;

			$highlight = ($highlight_current && $this->_current_page($pageid));
			# if this is the top level then it doesn't have a parentid
			$parentid = ($level == 0) ? 0 : $page_index[$pageid]['parentid'];
			if ($target = $this->get_val('target')) {
				$link = 'javascript: parent.'.$target.'.location = \''.htmlspecialchars($this->get_val('page_link', $pageid)).'\';';
			} else {
				$link = $this->get_val('page_link', $pageid);
			}#end if

			$this->dhtml_menu->add_flat_entry($pageid, $parentid, $this->get_val('page_short_name', $pageid), $link, $highlight);

			if(count($page_index[$pageid]['childids'])) {
				$this->_add_pages($page_index[$pageid]['childids'], $level + 1, &$page_index);
			}#end if

		}#end for


	}#end _add_pages()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name='', $index=0) {
		$web_system = &get_web_system();

		$changes_made = false;

		$title = ucwords(str_replace('_', ' ', $index.' - '.$this->type));

		$prefix_name .= '_'.$this->type;

		$uneditable = array('image_src', 
							'image_src_over', 
							'image_src_left', 
							'image_src_left_over', 
							'image_size',
							'image_horiz_space',
							'image_vert_space'
							);

		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable, $title);

		if ($this->sub_section && $this->sub_section->customisable) {
			$changes_made |= $this->sub_section->print_user_backend($prefix_name, $index);
		}

		# if changes have been made update the dhtml menu's attributes
		if ($changes_made) {
			$this->_set_dhtml_vars();
		}#end if

		return $changes_made;

	}#end print_user_backend()


}#end class Site_Design_Area_Menu_Section_Type_DHTML

 ################################################################################################
# takes care of the looping over each page
class Site_Design_Area_Menu_Section_Type_DHTML_Page extends Site_Design_Area_Menu_Section_Type {
	
	function Site_Design_Area_Menu_Section_Type_DHTML_Page(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		# we don't need these because they are taken care of by our parent
		$this->delete_var('start_printing_at');
		$this->delete_var('pages_to_print');

	}#end constructor

	 ######################################################
	# loops over the contents array for each of the pages
	function paint(&$pages, &$exclude_pageids, $rel_path='', $pageid) {

		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		$page_index = &$site->get_page_index();
		
		if (($left_pos_offset = $this->get_val('left_position_offset')) != '') {
			$left_pos_offset = (int) $left_pos_offset;
			$left_pos_offset = ($left_pos_offset) ? $left_pos_offset = ' + '.$left_pos_offset : '';
			$this->_owner->dhtml_menu->set_attribute('left_position', '%anchor_left_pos% '.$left_pos_offset);
		}

		if (($top_pos_offset = $this->get_val('top_position_offset')) != '') {
			$top_pos_offset = (int) $top_pos_offset;
			$top_pos_offset = ($top_pos_offset) ? $top_pos_offset = ' + '.$top_pos_offset : '';
			$this->_owner->dhtml_menu->set_attribute('top_position', '%anchor_top_pos% '.$top_pos_offset);
		}

		foreach($pages as $pageid) {
			$this->_active_pageid = $pageid;

			$arrayid = '';

			if ($page_index[$pageid]['childids']) {
				$this->_owner->dhtml_menu->clear_entries();
				$this->_owner->_add_pages($page_index[$pageid]['childids'], 0, $page_index);
				$arrayid = $this->_owner->dhtml_menu->paint($rel_path);
			}

			foreach($this->contents as $element) {

				switch($element['_type']) {

					# sub level, call paint on the meun design area
					case 'SUB_LEVEL' :
						# only paint if this page is in the lineage
						# or we are supposed to be showing all pages
						if ($this->get_val('SHOW_ALL_PAGES')
							|| $this->_current_page($pageid)) {

							$menu = &$this->get_menu();
							$menu->paint_section($element, $pageid);
						}

					break;

					# plain html element just echo its contents
					case 'HTML' :
						echo $element['contents'];

					break;

					# plain html element just echo its contents
					case 'DESIGN_AREA' :
						$this->_print_nested_area($element);

					break;

					# taged element ... should really only be a print element
					case 'TAG' :

						switch($element['operation']) {
							case 'print' : 
								$this->print_val($element, $pageid, $arrayid);
							break;
						}

					break;

					default :
						$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
						unset($this);
						return;

				}#end switch

			}#end for

		}#end foreach $pages
		$this->_active_pageid = 0;

	}#end paint()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid=0, $arrayid=0) {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		switch(strtolower($name)) {
			case 'onmouseover' :
			case 'onmouseout' :
			case 'link_name' :
				return $this->_owner->dhtml_menu->get_anchor_replacements(strtolower($name), $arrayid);
			break;

			case 'class' : 
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid, true);
			break;

			case 'bullet_image' :
				if ($this->_current_page($pageid)) {
					if (Site_Design_Area_Menu_Section_Type::get_val($name.'_current')) {
						$name .= '_current';
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

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, $pageid, $arrayid) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'], $pageid, $arrayid));
	}

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name='', $index) {
		$web_system = &get_web_system();

		$changes_made = false;

		$title = ucwords(str_replace('_', ' ', $index.' - '.$this->type));

		$prefix_name .= '_'.$this->type;

		$uneditable = array('bullet_image', 'bullet_image_current');

		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable, $title);

		return $changes_made;

	}#end print_user_backend()

 
}#end class Site_Design_Area_Menu_Section_Type_DHTML_Page

?>
