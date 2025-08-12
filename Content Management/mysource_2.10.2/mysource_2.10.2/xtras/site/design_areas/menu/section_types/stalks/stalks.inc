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
## Desc: looks after the printing of stalked menu sections
## $Source: /home/cvsroot/xtras/site/design_areas/menu/section_types/stalks/stalks.inc,v $
## $Revision: 2.30 $
## $Author: bvial $
## $Date: 2004/03/03 04:02:27 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Menu_Section_Type_Stalks extends Site_Design_Area_Menu_Section_Type {


	var $sub_section; # the section within this tag that is looped over for each page we are printing

	function Site_Design_Area_Menu_Section_Type_Stalks(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;
		
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
	function create(&$tag, $level, $_index, $parent_level, $parent_index) {

		 ########################################
		# Set up any variables
		$this->parent_level = $parent_level;
		$this->parent_index = $parent_index;
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "menu");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element) {
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case "TAG" : 

					# if this is the sub section that we use to loop over the pages
					if ($element[attributes][section] == "page") {

						# kill it because it's not needed anymore
						unset($element[attributes][section]);

						$class_name = get_class($this)."_Page";
						
						$this->sub_section = new $class_name($this, $level);
						$this->sub_section->create($element, $level, $_index, $level, $_index);

						# append to the contents array so we know were to print this
						$this->contents[] = Array("_type" => "SUB_SECTION");


					# else if this is a sub level ( the test for 'section' is only for backwards compatibilty)
					} else if ($element[attributes][level] || $element[attributes][section]) {

						$contents_arr = Array("_type" => "SUB_LEVEL");

						$menu = &$this->get_menu();

						# call create on it and return any additional attributes
						$additional_attributes = $menu->create_section($element, $level, $_index);

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

				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML"        : # nothing to do to this yet, so just add to contents array

						$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	 ###################################################################
	# paint the passed pages, excluding some that are not needed
	function paint($pages, &$exclude_pageids, $level, $pageid) {

		$pages = $this->get_pages_to_print($pages);

		# no point going any further if there are no pages
		if (!$pages) return;

		foreach($this->contents as $element) {

			switch($element['_type']) {

				# sub level, call paint on the meun design area
				case "SUB_LEVEL" :
					$menu = &$this->get_menu();
					$menu->paint_section($element);

				break;

				# plain html element just echo its contents
				case "HTML" :
					echo $element['contents'];

				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :

					switch($element['operation']) {
						case "print" : 
							$this->print_val($element, $pageid);
						break;
					}

				break;

				# sub section element
				case "SUB_SECTION" :
					$this->sub_section->paint($pages, $exclude_pageids);

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="", $index=0) {

		$changes_made = false;
		$changes_made |= Site_Design_Area_Menu_Section_Type::print_user_backend($prefix_name);

		if ($this->sub_section && $this->sub_section->customisable) {
			$changes_made |= $this->sub_section->print_user_backend($prefix_name, $index);
		}

		return $changes_made;

	}#end print_user_backend()

}#end class Site_Design_Area_Menu_Section_Type_Stalks


 ################################################################################################
# takes care of the looping over each page
class Site_Design_Area_Menu_Section_Type_Stalks_Page extends Site_Design_Area_Menu_Section_Type {
	
	var $has_sub_levels = false;	# var set at create time, 
									# and used at paint time to help decide whether 
									# or not to print an excluded page

	function Site_Design_Area_Menu_Section_Type_Stalks_Page(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		# we don't need these because they are taken care of by our parent
		$this->delete_var("start_printing_at");
		$this->delete_var("pages_to_print");

		  ################
		 #   EDITABLE   #
		################
		$this->_set_var("num_levels",		"",		"text",		"The number of levels into the hierarchy to show, blank or zero means show all");
		$this->_set_var("show_all_pages",	"0",	"boolean",	"");
		$this->_set_var("disable_ellipsis",	"0",	"boolean",	"Whether or not to truncate the long lines and add an ellipsis (...)");
		$this->_set_var("ellipsis_length",	"23",	"text",		"The length of a page name before ellipsis are printed - only valid if disable_ellipsis is false");
		$this->_set_var("class",			"",		"text",		"");
		$this->_set_var("class_current",	"",		"text",		"");
		$this->_set_var("table_class",		"",		"text",		"The class for the stalk table");
		$this->_set_var("div_class",		"stalkdiv",		"text",		"The class for the div that surrounds the stalk table");

		$this->_set_var("target", "", "text", "The name of the target frame for the link");
		$this->_set_var("show_stalks", "1", "boolean", "Show or hide the stalks - still indents text");
		$this->_set_var("restrict_text_size", "1", "boolean", "Restrict the text size to fit with the stalk size");
		$this->_set_var("stalk_image_type", "png", "set", "The Type of Image to use for the menu stalks", Array("png" => "png","gif" => "gif"));
		$this->_set_var("stalk_colour",    "000000", "colour", "The Colour of the stalks");
		$this->_set_var("stalk_bg_colour", "", "colour", "The Background Colour of the stalks, if not set transperant bg will be used");
		$this->_set_var("alignment", "left", "set", "", array("left" => "Left Align", "right" => "Right Align"));
		$this->_set_var("indent", "0", "text", "How many pixels to indent the stalk menu before printing the stalks - set to 'none' to completly remove stalk indenting");
		$this->_set_var("wrap", "1", "boolean", "Whether to wrap the text in the stalks or not");
		$this->_set_var("use_class_for_cells", "0", "boolean", "Whether or not to use the class in the cells of the stalks, mainly used with indent");
		//$this->_set_var("use_full_name", "0", "boolean", "Whether to use the page full name or short name");

	}#end constructor


#	 ###################################
#	# create the object from the tag
#	#function create(&$tag, $level) {
#
#		 ############################################################################
#		# Only set up any variables, because we don't accept any HTML or other stuff here
#		$this->_set_variables($tag, true);
#
#	}#end create()

	function _paint_page_contents($pageid) {

		$printed_something = false;

		foreach($this->contents as $element) {

			switch($element['_type']) {

				# plain html element just echo its contents
				case "HTML" :
					echo $element['contents'];
					if (trim($element['contents']) != '') $printed_something = true;

				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :
					$printed_something = true;
					switch($element['operation']) {
						case 'print' : 
							$this->print_val($element, $pageid);
						break;
					}

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

		if (!$printed_something) {
			# print a default format for them
			return false;
		}

		return true;

	}#end _paint_page_contents()


	  ###############################################################
	 # checks the stalk colours then prints the menu out
	function paint(&$pages, &$exclude_pageids) {

		# if they want the stalks then create any necessary menu files
		if ($this->get_val("show_stalks")) {

			$custom_dir = $this->get_val("CUSTOM_GENERATED_DIR");
			$colour    = strtolower($this->get_val("stalk_colour"));
			$bg_colour = strtolower($this->get_val("stalk_bg_colour"));
			# if we ahave a bg colour we need to usee the solid images
			if ($bg_colour) {
				$bg_type = "bg_colour";
				$bg_colour_name = $bg_colour; 
			# otherwise we need the transperant images
			} else {
				$bg_colour = $colour;  # don't need a bg_colour
				$bg_colour_name = "zzzzzz";  
				$bg_type = "transparent";
			}#end if

			$images = array("menu_stalk", 
							"menu_left_arrow", "menu_left_branch", "menu_left_endbranch",
							"menu_right_arrow", "menu_right_branch", "menu_right_endbranch"
							);
			$ext = $this->get_val("stalk_image_type");

			# if the first one doesn't exist assume none do, colourise
			if (!file_exists($custom_dir."/".$images[0]."_".$bg_type."_".$colour."_".$bg_colour_name.".".$ext)) {
				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/image.inc");
				foreach($images as $file) {
					$from = dirname(__FILE__)."/images/".$bg_type."/".$file.".".$ext;
					$to   = $custom_dir."/".$file."_".$bg_type."_".$colour."_".$bg_colour_name.".".$ext;
					image_gradient_palette($from,$to,$colour,$bg_colour);
				}

			}#end if file_exists

		}#end if show stalks

		$this->_print_menu_page_hierarchy($pages, $exclude_pageids, array());

	}#end print_hierarchical_menu()

	  ###############################################################
	 # Prints a hierarchy menu of pages in the sidebar (recursive) #
	function _print_menu_page_hierarchy(&$pages,&$exclude_pageids,$depth,$pageid) {

		$blank_src = lib_href('images/blank.gif');

		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		$page_index = &$site->get_page_index();
		$page_lineage = array_keys($page->get_lineage());

		$depth[] = true; # The indenting level, stalk on/off
		# if the number of levels was set and we have passed it don't go any further
		$num_levels = (int) $this->get_val('num_levels');
		if ($num_levels && count($depth) > $num_levels) return;


		$blanks = (!$this->get_val('show_stalks'));
		$colour = strtolower($this->get_val('stalk_colour'));
		$wrap = $this->get_val('wrap');
		if($wrap) {
			$wrap = '';
		} else {
			$wrap = ' nowrap';
		} 
		$image_prefix = $this->get_val('CUSTOM_GENERATED_PREFIX');
		$image_suffix = '_';
		if ($bg_colour = strtolower($this->get_val('stalk_bg_colour'))) {
			$image_suffix .= 'bg_colour';
		} else {
			$image_suffix .=  'transparent';
			$bg_colour = 'zzzzzz';
		}
		$image_suffix .= '_'.$colour.'_'.$bg_colour.'.'.$this->get_val('stalk_image_type');

		$target = '';
		if ($target = $this->get_val('target')) {
			$target = 'target="'.$target.'"';
		}


		for($pageid = reset($pages); $pageid; $pageid = next($pages)) {
			if (!next($pages)) $end = true;
			else $end = false;
			prev($pages);

			# set up the children
			if (isset($page_index[$pageid]['childids'])) $childids = $page_index[$pageid]['childids'];
			else unset($childids);

			# Trim invisible pages
			if(isset($childids)) {
				foreach($childids as $k => $childid) {
					if(!$page_index[$childid]['effective_visible']) unset($childids[$k]);
				}
			}
	
			# if this page is on the exclude list
			if (in_array($pageid, $exclude_pageids)) {

				# if this page has children
				if (isset($childids) && count($childids) > 0) {

					# if we have a need to print the children because this 
					# page is somewhere in the current in the page lineage
					# or the specific SHOW_ALL_PAGES is set
					$print_page = ($this->get_val('SHOW_ALL_PAGES')
									|| in_array($pageid,$page_lineage));

				# else no kids, no need to print
				} else {
					$print_page = false;
				}#end if

				# go to the next top page id
				if (!$print_page) continue;

			}#end if excluded

			$class = $this->get_val('table_class');
			if($class) $class = 'class="'.$class.'"';
			
			?>
			<div align="<?=$this->get_val('alignment')?>" class="<?=$this->get_val('div_class')?>">
			<table cellpadding=0 cellspacing=0 border=0 width="100%" <?=$class?>>
				<tr>
		<?
	
			$class = $this->get_val('class', $pageid);
			if($class) $class = 'class="'.$class.'"';

			if ($this->get_val('use_class_for_cells')) {
				$cell_class = &$class;
			} else {
				$cell_class = '';
			}

			if ($indent = $this->get_val('indent') && $indent != 'none') {
				$indent = '<td '.$cell_class.'><img src="'.$blank_src.'" border="0" width="'.$indent.'" height="1"></td>';
			} else {
				$indent = '';
			}

			$alt_text = str_replace("\"","&quot;",$this->get_val("full_page_name",$pageid));

			# if they want this to be right aligned
			if ($this->get_val('alignment') == 'right') {

			?>
					<td <?=$cell_class.$wrap?> width="100%" valign=top align="right"><?
					if (!$this->_paint_page_contents($pageid)) {
						?><a href="<?=$this->get_val('page_link',$pageid)?>" <?=$target?> <?=$class?> <?=($this->get_val('restrict_text_size')) ? 'style="font-size: 10px;"' : ''?> onmouseover="window.status='<?=addslashes($alt_text);?>';return true;" onmouseout="window.status='';return true;"><? echo $this->get_val('page_short_name',$pageid); ?></a><?
					}
					?>
					</td>
			<?
				if ($this->get_val('indent') != 'none') {
					?>
					<td <?=$cell_class?> valign="top"><img src="<? echo (($this->_current_page($pageid) && !$blanks) ? $image_prefix."menu_right_arrow".$image_suffix : $blank_src)?>" width="5" height="15" alt="<?=$alt_text?>"></td>
					<?

					for ($i = count($depth) - 1; $i >= 0; $i--) {
						?><td <?=$cell_class?> valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1)) && !$blanks) echo "background=\"".$image_prefix."menu_stalk".$image_suffix."\"" ?> ><?
						if ($i != count($depth) - 1 || $blanks) { 
							?><img src="<?=$blank_src?>" height="1" width="9" alt="<?=$alt_text?>"><?
						} elseif ($end) { 
							?><img src="<?=$image_prefix?>menu_right_endbranch<?=$image_suffix?>" height="9" width="9" alt="<?=$alt_text?>"><?
						} else {
							?><img src="<?=$image_prefix?>menu_right_branch<?=$image_suffix?>" height="9" width="9" alt="<?=$alt_text?>"><?
						}
						?></td><?

					}#end for
				}

				echo $indent;
			
			# default to left alignment
			} else {

				echo $indent;
				
				if ($this->get_val('indent') != 'none') {
					for ($i = 0; $i < count($depth); $i++) {
						?><td <?=$cell_class?> valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1)) && !$blanks) echo "background=\"".$image_prefix."menu_stalk".$image_suffix."\"" ?> ><?
						if ($i != count($depth) - 1 || $blanks) { 
							?><img src="<?=$blank_src?>" height="1" width="9" alt="<?=$alt_text?>"><?
						} elseif ($end) { 
							?><img src="<?=$image_prefix?>menu_left_endbranch<?=$image_suffix?>" height="9" width="9" alt="<?=$alt_text?>"><?
						} else {
							?><img src="<?=$image_prefix?>menu_left_branch<?=$image_suffix?>" height="9" width="9" alt="<?=$alt_text?>"><?
						}
						?></td><?

					}#end for
					?>
					<td <?=$cell_class?> valign="top"><img src="<? echo (($this->_current_page($pageid) && !$blanks) ? $image_prefix."menu_left_arrow".$image_suffix : $blank_src)?>" width="5" height="15" alt="<?=$alt_text?>"></td>
					<?
				}
			?>
					<td <?=$cell_class.$wrap?> width="100%" valign=top><?
					if (!$this->_paint_page_contents($pageid)) {
						?><a href="<?=$this->get_val('page_link',$pageid)?>" <?=$target?> <?=$class?> <?=($this->get_val('restrict_text_size')) ? 'style="font-size: 10px;"' : ''?> onmouseover="window.status='<?=addslashes($alt_text);?>';return true;" onmouseout="window.status='';return true;"><? echo $this->get_val('page_short_name',$pageid); ?></a><?
					}
					?></td>
			<?
			}#end if alignement
		?>

				</tr>
			</table>
			</div>
			<?

			# if there are kids to print
			if (isset($childids) && count($childids) > 0) {

				# if we have been told to print all pages in this section
				# or if this page is above the one we are on then print its children
				if ($this->get_val('SHOW_ALL_PAGES') 
					|| in_array($pageid,$page_lineage)) { 

						if ($end) $depth[count($depth)-1] = false;
						$this->_print_menu_page_hierarchy($childids, $excluded_pageids, $depth);

				}
			}

		}#end for

	}#end _print_menu_page_hierarchy()


	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid="") {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		switch(strtolower($name)) {
			case 'class' : 
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid, true);
			break;

			case 'page_name' :
			case 'page_short_name' :
				if ($this->get_val('disable_ellipsis')) {
					$output_name = Site_Design_Area_Menu_Section_Type::get_val($name, $pageid);
				} else {
					$ellipsis_length = (int)$this->get_val('ellipsis_length');
					if (!$ellipsis_length) $ellipsis_length = 23;
					$output_name = ellipsisize(Site_Design_Area_Menu_Section_Type::get_val($name, $pageid), $ellipsis_length - 2 * count($depth));
				}
				return $output_name;
			break;

			case 'full_page_name' :
				return Site_Design_Area_Menu_Section_Type::get_val('page_name', $pageid);
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid);
		}#end switch

	}#end get_val()
 
}#end class Site_Design_Area_Menu_Section_Type_Stalks_Page

?>
