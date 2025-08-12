<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
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
## Desc: Object that can create the DHTML Menus
## $Source: /home/cvsroot/squizlib/dhtml_menu/dhtml_menu.inc,v $
## $Revision: 2.10 $
## $Author: csmith $
## $Date: 2003/11/21 04:36:27 $
#######################################################################

#---------------------------------------------------------------------#

 ##########################################################################################################
# this is a global variable used to prevent an instance of dhtml_menu causing clashes with other instances
$_DHTML_MENU = ARRAY('num_printed' => 0, 'loaded' => false);

class dhtml_menu extends Object {

	var $default_rel_path = './'; # the path to the image and files use by this object

	var $entries = Array(); # a single dimension array that has an id, a parentid, and the HTML contents
	var $_children_current = false; # boolean indicating whether the current entries array has been updated 
									# to include all children is the most current

	var $page_params = Array();	# an array of parameters that have to be set on the global 
								# scope for HM
	var $attributes  = Array();	# an array of attributes that the menu will have
	
	var $top_level_parentid = 0;

	 ##############################
	# Constructor
	function dhtml_menu() {

		$this->set_top_level_parentid(0);

		# set all the attributes up with default values
		# NOTE : the order and number of these vars is DIRECTLY and CRITICALLY
		#        linked with the JS arrays that we print below
		$this->attributes = Array(
			'menu_width'					=> 100,
			'left_position'					=> 0,
			'top_position'					=> 0,
			'font_color'					=> '',
			'mouseover_font_color'			=> '',
			'background_color'				=> '',
			'mouseover_background_color'	=> '',
			'border_color'					=> '',
			'separator_color'				=> '',
			'top_is_permanent'				=> true,
			'top_is_horizontal'				=> true,
			'tree_is_horizontal'			=> false,
			'position_under'				=> true,
			'top_more_images_visible'		=> true,
			'tree_more_images_visible'		=> true,
			'evaluate_upon_tree_show'		=> 'null',
			'evaluate_upon_tree_hide'		=> 'null',
			'right_to_left'					=> false,
			'display_on_click'				=> false,
			'top_is_variable_width'			=> false,
			'tree_is_variable_width'		=> false,
			'curr_tree_background_color'    => '',
			'semi_perm_hilite'				=> false,

		);


		# set up SOME of the page params with defaults
		$this->page_params = Array(
			'font_family'			=> 'Arial,sans-serif',
			'font_size'				=> 10,
			'font_bold'				=> true,
			'font_italic'			=> false,
			'item_padding'			=> 3,
			'border_width'			=> 0,
			'border_style'			=> 'solid',
			'separator_size'		=> 2,
			'image_src'				=> '',
			'image_src_over'		=> '',
			'image_src_left'		=> '',
			'image_src_left_over'	=> '',
			'image_size'			=> 0, # the width of the image
			'image_horiz_space'		=> 0,
			'image_vert_space'		=> 2,
			'keep_hilite'			=> true,	# keep the highlight for all selected items when 
												# drilling down the hierarchy
			'click_kill'			=> false,	# whether the menus disappear when the user clicks 
												# elsewhere in the page or after the leave the menu
			'child_overlap'			=> 20,
			'child_offset'			=> 10,
			'child_per_cent_over'	=> null,
			'top_seconds_visible'	=> 0.5,
			'status_display_build'	=> false,	# display in the status bar the progress of the menu builing
			'status_display_link'	=> false,	# display in the status bar the link that the page refers to
			'show_link_cursor'		=> true,	# show the 'hand' mouse pointer when over the items
			'screen_padding'		=> 10,		# the amount of padding that will appear between any 
												# menu item and the edge of the window

			'enforce_screen_width'			=> 0,
			'enforce_screen_width_align'	=> 'left'
		);


	}# end constructor

	 ##################################
	# add an entry to the menu
	function add_flat_entry($id=0, $parentid=0, $html='', $href='', $highlight=false, $pos=0) {
		if ($id) {
			$pos = (int) $pos;
			$this->entries[$id] = Array('parentid'  => $parentid,
									 'html'      => $html,
									 'href'      => $href,
									 'highlight' => $highlight,
									 'position'  => (($pos > 0) ? $pos : 0),
									 'children'  => Array());
			$this->_children_current = false;
		} else {
			$this->_set_error('You need to supply an id for '.htmlspecialchars($html), __FILE__, __LINE__);
		}#end if

	}#end add_flat_entry()

	 ##################################
	# remove an entry from the menu
	function remove_flat_entry($id=0) {
		unset($this->entries[$id]);
		$this->_children_current = false;
	}#end remove_flat_entry()

	 ##################################
	# remove all entries from the menu
	function clear_entries() {
		unset($this->entries);
		$this->entries = Array();
		$this->_children_current = false;
	}#end clear_entries()

	 #############################################################
	# set an the top level parentid so we know where to start
	function set_top_level_parentid($parentid=0) {

		# remove the old top level entry
		unset($this->entries[$this->top_level_parentid]);
		$this->top_level_parentid = $parentid;

		# create a new entry for the top level parentid
		$this->entries[$this->top_level_parentid] = Array("children"  => Array());

	}#end set_top_level_parentid()

	 #####################################################
	# set an attribute of the menu or a page parameter
	function set_attribute($name='', $value='') {

		# because all the attributes are initialised by the constructor, 
		# if the element isset then its an attribute
		if (isset($this->attributes[$name])) {
			$this->attributes[$name] = $value;
		# else it must be a page parameter
		} else {
			if (strpos($name, 'bg') === 0) {
				$name = 'b_g'.substr($name, 2);
			}
			$this->page_params[$name] = $value;
		}#end if

	}#end set_attribute()

	 ###########################################################
	# create the children structure that we need to paint this info
	function set_children() {
		if ($this->_children_current) return;

		for(reset($this->entries); NULL !== ($id = key($this->entries)); next($this->entries)) {
			if ($id == $this->top_level_parentid) continue;
			$entry = &$this->entries[$id];
			$kids = &$this->entries[$entry['parentid']]['children'];
			# if they want a specific entry position
			if ($entry['position']) {
				$pos = $entry['position'] - 1;
				$kid_count = count($kids);
				for($i = $kid_count; $i > $pos; $i--) {
					$kids[$i] = $kids[$i - 1];
				}#end for
				$kids[$pos] = $id;
			} else {
				$kids[] = $id;
			}#end if

		}#end for

		$this->_children_current = true;

	}#end set_children()

	 ########################################################################
	# Get's the id for the next menu to be printed
	function get_next_id() {
		return $_DHTML_MENU['num_printed'] + 1;
	}#end get_next_id()

	 ########################################################################
	# OK let's paint the array that the JS will use to create this bastard
	# and if they supply the anchor we will replace some special strings for them 
	function paint($rel_path='', $anchor='') {
		global $_DHTML_MENU;

		# no point painting if we ain't got anything to paint :)
		if (empty($this->entries)) return;

		# if we are the first menu to be printed then print out the initaliasation code
		if ($_DHTML_MENU['num_printed'] == 0) {

			if (empty($rel_path)) $rel_path = $this->default_rel_path;

			# if we don't have any "more" images then set some defaults
			if (empty($this->page_params['image_src']) && empty($this->page_params['image_src_left'])) {
				$this->page_params['image_src']				= $rel_path.'images/HM_More_black_right.gif';
				$this->page_params['image_src_over']		= $rel_path.'images/HM_More_white_right.gif';
				$this->page_params['image_src_left']		= $rel_path.'images/HM_More_black_left.gif';
				$this->page_params['image_src_left_over']	= $rel_path.'images/HM_More_white_left.gif';
				$this->page_params['image_size']			= 5;
			}#end if
		?>
			<script language="JavaScript1.2" type="text/javascript">
			<!--

			<?

				for(reset($this->page_params); 
					NULL !== ($k = key($this->page_params)); 
					next($this->page_params)) {
					$val = &$this->page_params[$k];

					$name = str_replace(" ", "", ucwords(str_replace("_", " ", $k)));
					echo "\nHM_PG_".$name." = ";

					if      (is_numeric($val)) echo $val;
					else if (is_string($val))  echo "'".addslashes($val)."'";
					else if (is_bool($val))    echo ($val) ? "true" : "false";
					else if ($val == NULL)     echo "null";
					else echo $val;

					echo ";";

				}#end for

			?> 

				HM_PG_NSFontOver = true;

			//-->
			</script>

		<?

		}#end if num_printed == 0

		$_DHTML_MENU['num_printed']++;

		?> 
		<script language="JavaScript1.2" type="text/javascript">
		<?
		$this->set_children();
		$this->print_js_data($this->top_level_parentid, $_DHTML_MENU[num_printed], $_DHTML_MENU[num_printed]);
		?> 
			if (!window.HM_a_TreesToBuild) window.HM_a_TreesToBuild = [];
			window.HM_a_TreesToBuild[HM_a_TreesToBuild.length] = <?=$_DHTML_MENU[num_printed]?>;
		</script>
		<?

		# if the anchor exists then print it out with the text replaced
		if ($anchor) {
			$keyword_replacements = Array('menuid' => $_DHTML_MENU['num_printed'],
										  'onmouseover' => $this->get_anchor_replacements('onmouseover', $_DHTML_MENU['num_printed']),
										  'onmouseout'  => $this->get_anchor_replacements('onmouseout', $_DHTML_MENU['num_printed']),
										  'link_name'   => $this->get_anchor_replacements('link_name', $_DHTML_MENU['num_printed'])
										  );
			echo replace_keywords($anchor, $keyword_replacements);
		}#end if anchor

		# return the id so that the caller can use it
		return $_DHTML_MENU['num_printed'];

	}#end paint()

	 ###############################################################################
	# returns values for anchor tags that are being used to control a pop-up menu
	# (as apposed to having a full DHTML menuing system
	function get_anchor_replacements($name, $menuid) {
		# if there is no id then there is no menu being printed
		if (empty($menuid) || empty($name)) return "";
		switch($name) {
			
			case "onmouseover" :
				return "HM_f_PopUp('elMenu".$menuid."',event);";
			break;

			case "onmouseout" :
				return "HM_f_PopDown('elMenu".$menuid."');";
			break;

			case "link_name" : 
				return "NAME='menu_anchor_".$menuid."' ID='menu_anchor_".$menuid."'";
			break;

			case "anchor_left_pos" :
				return "HM_f_PlaceAtElement('menu_anchor_".$menuid."','left')";
			break;

			case "anchor_top_pos" :
				return "HM_f_PlaceAtElement('menu_anchor_".$menuid."','top')";
			break;

			default :
				return "";

		}#end switch

	}#end get_anchor_replacements()

	 ########################################################################
	# prints out the JS arrays used by the browser to create the menus
	function print_js_data($parentid=0, $menuid=0, $suffix='',$is_sub=false) {
		
		$make_readable = false;  # makes the js output more readable, but adds to download
		$nl = ($make_readable) ? "\n" : "";
		$tb = ($make_readable) ? "\t" : "";

		echo "\nHM_Array".$suffix." = [".$nl;
		if ($is_sub) { 
			echo $tb."[],".$nl;

		} else { 
			echo $tb."[".$nl;

			$keyword_replacements = array("menuid" => $menuid,
										  "anchor_left_pos" => $this->get_anchor_replacements("anchor_left_pos", $menuid),
										  "anchor_top_pos"  => $this->get_anchor_replacements("anchor_top_pos", $menuid)
										);

			for(reset($this->attributes); 
				NULL !== ($k = key($this->attributes)); 
				next($this->attributes)) {
				$val = &$this->attributes[$k];

				echo $tb.$tb;

				if      (is_numeric($val)) echo $val;
				else if (is_string($val))  echo "'".addslashes(replace_keywords($val, $keyword_replacements))."'";
				else if (is_bool($val))    echo ($val) ? "true" : "false";
				else echo $val;

				echo ",";
				if ($make_readable) {
					echo "		// $k".$nl;
				}

			}#end for
			echo $nl.$tb."],".$nl;

		} #end if

		$counter = 0;
		$ids = &$this->entries[$parentid]['children'];
		if (!empty($ids)) {
			for(reset($ids); NULL !== ($key = key($ids)); next($ids)) {
				$data = &$this->entries[$ids[$key]];
				$counter++;

				# Format is [html, href, is rollover, permanently_highlighted, has children]
				echo $tb."['".
					addslashes($data['html'])."','".
					addslashes($data['href'])."',".
					"1,".
					(($data['highlight']) ? 1 : 0).",".
					((count($data['children'])) ? 1 : 0).
					"]";
				if ($counter < count($ids)) {
					echo ",";
				}#end if
				echo $nl;

			}#end foreach
		}
		echo "];$nl";

		# now print any sub arrays
		$counter = 0;
		for(reset($ids); NULL !== ($key = key($ids)); next($ids)) {
			$data = &$this->entries[$ids[$key]];
			$counter++;
			if (isset($data['children']) && !empty($data['children'])) {
				$this->print_js_data($ids[$key], $menuid, $suffix."_".$counter, true);
			}#end if
		}#end for


	}#end print_js_data()

	 #########################################################
	# returns the HTML that would form the dhtml menu
	function get_html($rel_path='', $anchor='', $load=false) {

		# start output buffering
		ob_start();
		# now call paint as usual
		$this->paint($rel_path, $anchor);
		# if they wanted to load it as well then do so
		if ($load) $this->load($rel_path);
		# now get the painted contents
		$html = ob_get_contents();
		# turn off the output buffering without printing anything
		ob_end_clean();

		return $html;

	}#end get_html()


	 ########################################################################
	# Let's load the menus, this function should only be called
	function load($rel_path='') {
		global $_DHTML_MENU;

		if (!$_DHTML_MENU['loaded']) {
			if (empty($rel_path)) $rel_path = $this->default_rel_path;
		?>
			<script language="JavaScript1.2" type='text/javascript'>
				DHTML_MENU_rel_path = '<?=$rel_path?>images/';
			</script>
			<script language="JavaScript1.2" src="<?=$rel_path?>images/HM_Loader.js" type='text/javascript'></script>
		<?

			$_DHTML_MENU['loaded'] = true;

		} else {
		?>
			<!-- 
				ERROR: 
				You Tried to load the menus again, 
				the menus loading script had are already been printed
			-->
		<?
		}#end if

	}#end load()

}#end class

?>
