<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
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
## Desc: A class that handles user content editing
## $Source: /home/cvsroot/squizlib/bodycopy/bodycopy.inc,v $
## $Revision: 2.55 $
## $Author: csmith $
## $Date: 2004/01/14 03:00:49 $
#######################################################################

global $_BODYCOPY;
$_BODYCOPY = array();

#---------------------------------------------------------------------#
include_once(dirname(__FILE__)."/../var_serialise/var_serialise.inc");
include_once(dirname(__FILE__)."/bodycopy_table.inc");
#---------------------------------------------------------------------#

 ########################################################################
# this the file on the outside that we can call to get external files for the 
# pop-ups, JS include and CSS
global $EDIT_DIR;
$_BODYCOPY['external_file'] = "$EDIT_DIR/bodycopy.php";
   ####################################################################
  # this indicates to the bodycopy when it's printing the edit interface
 # whether another bodycopy has already been printed, so it knows not to 
# print out the JS edit functions
$_BODYCOPY['edit_initialised'] = false;
   ####################################################################
  # this indicates to the bodycopy when it's updating whether or not
 # the some operations that are only supposed to be carried out once have 
# been done
$_BODYCOPY['update_initialised'] = false;


/**
* Print a bodycopy style icon
*
* This is normally used to print the bodycopy icons in the 
* for bodycopy, table, row and cell functions
*
* @param   string $href		The HREF that this icons points to
* @param   string $heading	The heading for the tooltip
* @param   string $desc		The main body of the tooltip
* @param   string $icon		The filename of the icon to display (must be a GIF file)
* @param   string $extra	Any extras you want to put at the end of the IMG tag
* @param   string $width	The width of the icon
* @param   string $height	The height of the icon
* @access  public
* @returns void
*/
function print_bodycopy_icon($href='', $heading='', $desc='', $icon='', $extra='', $width=20, $height=20){
	global $_BODYCOPY;
	?><a href="<?=$href?>" onmouseover="javascript: show_tooltip(event, '<?=addslashes(htmlspecialchars($heading))?>', '<?=addslashes(htmlspecialchars($desc))?>', null, 'bodycopyToolTipDiv'); return true;" onmouseout="javascript: hide_tooltip(); return true;" ><img src="<?=$_BODYCOPY['file_prefix']?>images/icons/<?=$icon?>.gif" width="<?=$width?>" height="<?=$height?>" border="0" <?=$extra?>></a><?
}# end print_bodycopy_icon()



/**
* Print a seperator line to divide bodycopy icons into groups
*
* @param   string $axis	 Print Horizontal (horiz) or Vertical (anything else) line
* @param   string $extra Any extras you want to put at the end of the IMG tag
* @access  public
* @returns void
*/
function print_bodycopy_icon_separator($axis='horiz', $extra=''){
	global $_BODYCOPY;

	if ($axis == 'horiz') {
		$axis   = 'horizontal';
		$width  = '20';
		$height = '10';

	} else {
		$axis   = 'vertical';
		$width  = '10';
		$height = '20';

	}#end if


	?><img src="<?=$_BODYCOPY['file_prefix']?>images/icons/separator_<?=$axis?>.gif" width="<?=$width?>" height="<?=$height?>" border="0" <?=$extra?>><?

}# end print_bodycopy_icon()



/** 
* A class that handles user content editing.
* The bodycopy contains an array of table (BodyCopy_Table) objects
* which in turn contain an array of row (BodyCopy_Table_Row) objects
* which in turn contain an array of cell (BodyCopy_Table_Cell) objects.
*
* @access public
* @package Bodycopy
*/
class BodyCopy extends Object {

	/**
	* An array of tables that make up the bodycopy (BodyCopy_Table)
	* @var array
	* @see insert_table(), delete_table(), reorder_tables()
	*/
	var $tables = array();

	/**
	* An array of keyword replacements to make in this bodycopy
	* @var array
	* @see add_replace_keywords(), add_replace_keyword()
	*/
	var $keyword_replacements = array();


	/**
	* Constructor
	*
	* @param   string $serialized	A serialized bodycopy object
	* @param   string $name			A unique name (to the template) for this bodycopy
	* @returns object Bodycopy
	* @access  public
	*/
	function BodyCopy($serialized='', $name='') {
		if (trim($serialized)) {
			if (!$this->unpack($serialized, $name)) {
				$this = new BodyCopy();
			}
		}
	}


	/**
	* Converts the object into a nice string
	*
	* @returns string
	* @access  public
	*/
	function pack() {
		return serialize($this);
	}
	

	/**
	* Takes a string, and unpacks it into the bodycopy object itself
	*
	* @param   string $stringified	A serialized bodycopy object
	* @param   string $name			A unique name (to the template) for this bodycopy
	* @returns object Bodycopy
	* @access  public
	*/
	function unpack($stringified='', $name='') {
		$new_object = unserialize($stringified);
		if (strtolower(get_class($new_object)) == 'bodycopy') {
			$this = $new_object;
			return true;
		} else {
			$new_object = unserialize(stripslashes($stringified));
			if (($type = strtolower(get_class($new_object))) == 'bodycopy') {
				$this = $new_object;
				return true;
			} else {
				# see if it is the old style bodycopy that we have been dealt
				if ($new_object = $this->convert_from_old_style($stringified, $name)) {
					$this = $new_object;
					return true;
				} elseif ($type) {
					$this->_set_error("Attempt to unpack bodycopy yielded an object which did not appear to be a bodycopy: $stringified", __FILE__, __LINE__);
					return false;
				}#end if

			}#end if
		}#end if

	}#end unpack()



	/**
	* Run through and delete all the tables in this bodycopy
	*
	* @returns boolean
	* @access  public
	*/
	function delete() {

		unset($this->attributes);

		# run through the tables and delete() them
		$table_count = count($this->tables);
		for($i = 0; $i < $table_count; $i++) {
			$this->tables[$i]->delete();
		}#end for
		unset($this->tables);

		$this = new BodyCopy();
		return true;

	}#end delete()


	/**
	* Insert a new table before or after the passed tableid
	*
	* @param   integer $tableid				The ID of the table to insert before or after
	* @param   integer $num_rows			The number of rows in the new table
	* @param   integer $num_cols			The number of columns in the new table
	* @param   string  $default_cell_type	The default cell type for the new table
	* @param   boolean $before				Insert the table before the table with the passed id?
	* @param   array   $attributes			An array of attributes for the new table (eg. bgcolor)
	* @access  public
	* @returns string
	* @see delete_table(), reorder_tables()
	*/
	function insert_table($tableid=0, $num_rows=1, $num_cols=1, $default_cell_type='', $before=true, $attributes=Array()) {

		$tableid = intval($tableid);

		# if the table is greater than the number of tables we have, set it to the last one
		if ($tableid > count($this->tables) - 1) $tableid = count($this->tables) - 1;
		else if ($tableid < 0) $tableid = 0;

		# if they want to insert after the passed tableid what 
		# they really mean is to insert before the next tableid
		if (!$before) $tableid++;

		# move all the tables up one position
		$table_count = count($this->tables);
		for($i = $table_count; $i > $tableid; $i--) {
			$this->tables[$i] = $this->tables[$i - 1];
		}#end for
		# now add the table
		$this->tables[$tableid] = new BodyCopy_Table($num_rows, $num_cols, $default_cell_type, $attributes);

		return 'A table has been inserted '.((count($this->tables) > 1) ? (($before) ? 'before' : 'after').' table '.(($before) ? $tableid + 1 : $tableid) : '');

	}#end insert_table()


	/**
	* Delete the passed table from the bodycopy
	*
	* @param   integer $tableid The ID of the table to delete
	* @access  public
	* @returns string
	* @see insert_table(), reorder_tables()
	*/
	function delete_table($tableid=0) {

		# make sure it exists
		if ($this->tables[$tableid]) {

			# do any clean up
			$this->tables[$tableid]->delete();

			# move all the tables down one position
			for($i = $tableid; $i < count($this->tables) - 1; $i++) {
				$this->tables[$i] = $this->tables[$i + 1];
			}#end for

			# remove the last element
			array_pop($this->tables);

			return "Table ".($tableid + 1)." has been deleted";

		}#end if

		return '';

	}#end delete_table()



	/**
	* Re-orders the tables in this bodycopy
	*
	* @param   array $table_order An array of the table structure (new_place => old_place)
	* @access  public
	* @returns string
	* @see insert_table(), delete_table()
	*/
	function reorder_tables($table_order=array()) {

		# if there ain't no changes, die
		$changes = false;
		foreach($table_order as $new_place => $old_place) $changes |= ($new_place != $old_place);
		if (!$changes) return "";

		# if there is a conflict in the number of tables, die
		if (count($this->tables) != count(array_unique($table_order))) return "";

		$new_array = Array();
		for($i = 0; $i < count($table_order); $i++) {
			$new_array[$i] = $this->tables[$table_order[$i]];
		}
		# now assign the new ordered array
		$this->tables = $new_array;

		return "Table's have been reordered";

	}#end reorder_tables()


	/**
	* Prints the frontend HTML for the bodycopy
	*
	* @access  public
	* @returns void
	*/
	function paint() {
		$table_count = count($this->tables);
		for($i = 0; $i < $table_count; $i++) {
			$this->tables[$i]->paint($this->keyword_replacements);
		}#end for

	}#end paint()



	/**
	* Returns the HTML that would form the frontend bodycopy
	*
	* @access  public
	* @returns string
	*/
	function get_html() {

		# start output buffering
		ob_start();
		# now call paint as usual
		$this->paint();
		# now get the painted HTML
		$html = ob_get_contents();
		# turn off the output buffering without printing anything
		ob_end_clean();

		return $html;

	}#end get_html()



	/**
	* Replace old page and site IDs with the new ones created during duping
	*
	* Given a map of file and page ids from old to new
	* (usually during duplication of sites or pages)
	* this will change all the links etc in the bodycopy
	* to reflect the mapping
	*
	* @param   array &$map The dupe map to use - created during duping of pages or sites
	* @access  public
	* @returns void
	* @see Page::Dupe(), Site::Dupe()
	*/
	function remap_link_ids(&$map) {
		for($i = 0; $i < count($this->tables); $i++) {
			$this->tables[$i]->remap_link_ids($map);
		}#end for
	}



	/**
	* Prints the backend interface for editing this bodycopy
	*
	* @param   string  $this_bodycopy_name	A unique name (to the template) for this bodycopy
	* @param   string  $action_name			The name of the HTML form var to hold the commit action
	* @param   string  $action_value		The value to set the action to when submitting
	* @param   string  $form_name			The name of the HTML form to submit to
	* @param   string  $stylesheet_src		The URL of the stylesheet to use
	* @param   string  $stylesheet_rel		Relative path to the stylesheet so the popups know where it is
	* @param   array   $special_styles		???
	* @param   integer $siteid				The ID of the site that this bodycopy is in
	* @param   integer $pageid				The ID of the page that this bodycopy is on
	* @param   string  $file_href			The prefix for all external files (JS, CSS, images) that are needed
	* @param   array   $keywords			A list of keywords that the bodycopy can use for a drop-down list.
	* @access  public
	* @returns void
	* @see set_prefixes()
	*/
	function print_backend($this_bodycopy_name='bodycopy', $action_name='action', $action_value='', $form_name='edit', 
							$stylesheet_src='', $stylesheet_rel='', $special_styles=array(),
							$siteid=0, $pageid=0, $file_href='',
							$keywords=array()
						  ) {
		global $_BODYCOPY, $EDIT_DIR, $XTRAS_PATH;

		if (!is_array($special_styles)) $special_styles = Array();
		if (!is_array($keywords)) $keywords = Array();

		$mysource_conf = &get_config('mysource');
		if ($mysource_conf->default_table_cell_type) $_BODYCOPY['TABLE_CELL']['default_type'] = 'bodycopy_table_cell_type_'.$mysource_conf->default_table_cell_type;

		$this->set_prefixes($file_href, $stylesheet_rel, $siteid, $pageid);

		# if it exists, parse the stylesheet to get a list of
		# styles we can use
		if ($mysource_conf->use_css_styles && file_exists($stylesheet_src)) {
			$classes = array();
			$stylesheet = preg_replace('%[\r\n]+%',' ',file_to_string($stylesheet_src));
			$matches = array();
			preg_match_all("%[\s]+([^\{]+)\{[^\}]+\}%",$stylesheet,$matches);
			$class_strings = $matches[1];
			# $class_strings is now a list of all the class and their variations
			# in the stylesheet - so we need to parse this again to get unique
			# class names without variations
			foreach ($class_strings as $class_string) {
				$variations = explode(',',$class_string);
				foreach ($variations as $class) {
					# non-standard class begin with a '.'
					list($class) = explode(':',trim($class));
					if (substr($class,0,1) == '.') $classes[trim($class,'.')] = trim($class,'.');
				}
			}
			# make sure the specials stlyes
			# come before the stylesheet classes
			# in the array
			$temp = $special_styles;
			$special_styles = array();
			foreach ($temp as $id => $name) $special_styles[$id] = $name;
			foreach ($classes as $id => $name) $special_styles[$id] = $name;
		}

		# do some thing for the first time we print a bodycopy on this page
		if (!$_BODYCOPY['edit_initialised']) {

			# load in the available IF conditions
			$_BODYCOPY['showconditions'] = array();
			$_types = new XtrasRegistry($XTRAS_PATH.'/conditions', 'conditions');
			$_types = $_types->register;
			foreach ($_types as $code => $data) {
				$_BODYCOPY['showconditions'][$code]['name'] = $data->name;
				if (trim($data->extra_values['allowfalse']) != '') {
					# now insert the reverse (NOT) condition
					$_BODYCOPY['showconditions'][$code.'_is_false']['name'] = $data->extra_values['allowfalse'];
				}
			}

			# the initial width and height to have the layer display created at;
			$init_layer_width  = 1000;
			$init_layer_height = 1000;

		?>
			<script language="JavaScript" src="<?=squizlib_href('var_serialise', 'var_serialise.js')?>"	></script>
			<script language="JavaScript" src="<?=squizlib_href('js','detect.js');?>"					></script>
			<script language="JavaScript" src="<?=squizlib_href('js','layer_handler.js');?>"			></script>
			<script language="JavaScript" src="<?=squizlib_href('js','general.js');?>"					></script>
			<script language="JavaScript" src="<?=squizlib_href('js','debug.js');?>"					></script>
			<script language="JavaScript" src="<?=squizlib_href('js','form_functions.js');?>"			></script>
			<script language="JavaScript" src="<?=squizlib_href('js','tooltips.js');?>"					></script>
			<script language="JavaScript" src="<?=$EDIT_DIR?>/colour_picker.js"							></script>

			<input type="hidden" name="bodycopy_action" value="">
			<input type="hidden" name="bodycopy_name"   value="">
			<input type="hidden" name="bodycopy_data"   value="">
			<script language="JavaScript">

				// this is an array of data that each element (tables, rows, and cells)
				// can place data for use in editing
				var bodycopy_current_data = new Object();
				var bodycopy_saved	      = new Object(); 

				// this is an object that gets set everytime something is getting edited 
				// (as apposed to inserted or deleted) so that the pop-ups can get the info once
				// they have loaded themselves
				var bodycopy_current_edit = new Object();
				bodycopy_current_edit["data"] = null;  
				bodycopy_current_edit["bodycopy_name"] = null;  

				bodycopy_chgColor('mysource_header','559AE7');
				var bodycopy_initialised = false;	// true once init() has been run
				var bodycopy_popup   = null;		// pointer to the popup Layer_Handler Object
				var bodycopy_popup_visible = false;	// boolean indicating whether the popup is visisble ( not access directly )
				var bodycopy_nested_doc = null;		// pointer to the Netscape Layer or the IE Iframe

				// initialise the popup
				function bodycopy_init() {
					bodycopy_popup   = new Layer_Handler("bodycopyPopupDiv", 0,<?=$init_layer_width?>,<?=$init_layer_height?>,0);
					set_bodycopy_nested_doc();
					bodycopy_hide_popup();
					bodycopy_initialised = true;
					bodycopy_chgColor('mysource_header','212E61');
					bodycopy_otheronload();
				}

				var bodycopy_otheronload = (window.onload) ? window.onload :  new Function;
				window.onload = bodycopy_init;

				// generic function used everywhere to send the form
				function bodycopy_submit(bodycopy_action, bodycopy_name, bodycopy_data) {
					var form = document.<?=$form_name?>;

					form.bodycopy_action.value = bodycopy_action;
					form.bodycopy_name.value   = bodycopy_name;
					// pack up the passed object
					form.bodycopy_data.value = var_serialise(bodycopy_data);

					form.<?=$action_name?>.value = '<?=$action_value?>';
					form.submit();

				}// end bodycopy_submit()

				function serialise_table(bodycopy_name, bodycopy_data, tableid, rowid, cellid) {
					var form = document.<?=$form_name?>;
					if (!bodycopy_saved[bodycopy_name] && bodycopy_name != null) bodycopy_saved[bodycopy_name] = new Object();

					if (tableid != null) {
						if (!bodycopy_saved[bodycopy_name][tableid]) {
							bodycopy_saved[bodycopy_name][tableid] = new Object();
						}

						if (rowid != null) {
							if (!bodycopy_saved[bodycopy_name][tableid]['rows']) {
								bodycopy_saved[bodycopy_name][tableid]['rows'] = new Object();
							}
							if (!bodycopy_saved[bodycopy_name][tableid]['rows'][rowid] && rowid != null) {
								bodycopy_saved[bodycopy_name][tableid]['rows'][rowid] = new Object();
							} 
							if (cellid != null) {
								if (!bodycopy_saved[bodycopy_name][tableid]['rows'][rowid]['cells']) { 
									bodycopy_saved[bodycopy_name][tableid]['rows'][rowid]['cells'] = new Object();
								}
								bodycopy_saved[bodycopy_name][tableid]['rows'][rowid]['cells'][cellid] = bodycopy_data;
							} else {
								// no cellid means that we are setting row attributes
								bodycopy_saved[bodycopy_name][tableid]['rows'][rowid]['attributes'] = bodycopy_data['attributes'];
							}// end if cellid

						} else {
							// no rowid means that we are settign the table attributes
							bodycopy_saved[bodycopy_name][tableid]['attributes'] = bodycopy_data['attributes'];
						}//end if rowid

					}// end if tableid

					form.elements['bodycopy_saved[' + bodycopy_name + '][' + tableid + ']'].value = var_serialise(bodycopy_saved[bodycopy_name][tableid]);
				}// end serialise_table()


				function set_bodycopy_nested_doc() {
					if (is_ie4up) {
						bodycopy_nested_doc = bodycopyFrame;
					} else if (is_dom) {
						bodycopy_nested_doc = document.getElementById("bodycopyFrame");
					} else {
						bodycopy_nested_doc = bodycopy_popup.layer;
					}//end if
				}

				function get_bodycopy_popup_visibilty() {
					return bodycopy_popup_visible;
				}

				function check_external_links(html) {
					// we'll target specific extensions - we can't target a number of chars (for eg)
					// since if you put a reference to a site rather than a page it might end in
					// .net, .com, .org.
					var re = /http:\/\/(.*).(js|jpg|jpeg|gif|png|pdf|doc|zip|htm|html|php)/gi;
					if (re.test(html)) {
						alert('Warning: You have a link to a page or document in another website. This may move later on and cause issues for your site.');
					}
				}

				function bodycopy_show_popup(file, width, height) {
					if (!bodycopy_initialised) {
						if (confirm('You were too quick off the mark, the page is not loaded yet. Do you want to reload the page?')) {
							document.edit.action.value='';
							document.edit.submit()
						}
						return;
					}

					var w = (width  != null) ? width  : 500;
					var h = (height != null) ? height : 400;
					var border = 15;
					var top_offset = 20;

					var page_w = (is_nav4 || is_gecko) ? w - 17 : w;
					var page_h = (is_nav4 || is_gecko) ? h - 17 : h;

					file  = '<?=$_BODYCOPY['pop_up_prefix']?>' + file;
					file += '&page_width=' + page_w;
					file += '&page_height=' + page_h;
					file += '&browser=' + ((is_dom) ? "dom" : ((is_ie4up) ? "ie" : "ns"));
					file += '&enable_keywords=' + <?=!(empty($keywords)) + '0';?>;

					 /////////////////////////////////////////////////////////////////////////////////
					// cache prevention for pop_ups - use alert to remind that this has been enabled
					//var now = new Date();
					//file += '&random=' + now.getTime();
					//alert(file);

					bodycopy_popup.w = w;
					bodycopy_popup.h = h;
					bodycopy_popup.clip(null, w, h, null);
					bodycopy_popup_visible = true;

					if (is_ie4up) {
						// %!%#, frameElement is IE 5.5 only
						//bodycopy_nested_doc.frameElement.width  = w - border;
						//bodycopy_nested_doc.frameElement.height = h - border;
						bodycopy_nested_doc.width  = w - border;
						bodycopy_nested_doc.height = h - border;
						bodycopy_nested_doc.location = file;
					} else if (is_dom) {
						bodycopy_nested_doc.width  = w - border;
						bodycopy_nested_doc.height = h - border;
						bodycopy_nested_doc.src = file;
					} else {
						bodycopy_nested_doc.clip.right     = w - border;
						bodycopy_nested_doc.clip.width     = w - border;
						bodycopy_nested_doc.clip.height    = h - border;
						bodycopy_nested_doc.clip.bottom    = h - border;
						bodycopy_nested_doc.load(file, w - 5);
					}

					// how far down the page we want to display this popup
					var scroll_top  = ((is_ie4up) ? document.body.scrollTop  : self.pageYOffset);
					bodycopy_popup.move(null, scroll_top + top_offset);
					bodycopy_popup.show();


				}// end bodycopy_show_popup()

				function bodycopy_hide_popup() {
					if (!bodycopy_initialised) {
						return;
					}
					bodycopy_show_popup("blank.php");
					bodycopy_popup_visible = false;
					bodycopy_popup.hide();
				
				}// end bodycopy_hide_popup()

				// get the available cell types 
				var BODYCOPY_AVAILABLE_CELL_TYPES = null;
				function get_bodycopy_available_cell_types() {
					if (BODYCOPY_AVAILABLE_CELL_TYPES == null) {
						BODYCOPY_AVAILABLE_CELL_TYPES = var_unserialise('<?=var_serialise($_BODYCOPY["TABLE_CELL"]["types"], true)?>');
					}
					return BODYCOPY_AVAILABLE_CELL_TYPES;
				}


				// get the available conditional statements 
				var BODYCOPY_AVAILABLE_CONDITIONS = null;
				function get_bodycopy_available_conditions() {
					if (BODYCOPY_AVAILABLE_CONDITIONS == null) {
						BODYCOPY_AVAILABLE_CONDITIONS = var_unserialise('<?=var_serialise($_BODYCOPY["showconditions"], true)?>');
					}
					return BODYCOPY_AVAILABLE_CONDITIONS;
				}

				function get_bodycopy_available_default_cell_type() {
					return '<?=addslashes($_BODYCOPY["TABLE_CELL"]["default_type"])?>';
				}



				// general fn that the generic include fns can use to get data to use
				function get_bodycopy_current_data(bodycopy_name, tableid, rowid, cellid) {
					if (tableid == null) tableid = -1;
					if (rowid   == null) rowid   = -1;
					if (cellid  == null) cellid  = -1;
					return bodycopy_current_data[bodycopy_name].get_data(tableid, rowid, cellid);
				}

				// general fn that the generic include fns can use to get data to use
				var BODYCOPY_SPECIAL_STYLES = null;
				function get_bodycopy_special_styles() {
					if (BODYCOPY_SPECIAL_STYLES == null) {
						BODYCOPY_SPECIAL_STYLES = var_unserialise('<?=var_serialise($special_styles, true)?>');
					}
					return BODYCOPY_SPECIAL_STYLES;
				}

				// general fn that the generic include fns can use to get data to use
				var BODYCOPY_SPECIAL_KEYWORDS = null;
				function get_bodycopy_special_keywords() {
					if (BODYCOPY_SPECIAL_KEYWORDS == null) {
						BODYCOPY_SPECIAL_KEYWORDS = var_unserialise('<?=var_serialise($keywords, true)?>');
					}
					return BODYCOPY_SPECIAL_KEYWORDS;
				}

				function bodycopy_data_exists(args) {
					var str = 'bodycopy_saved';
					for(var i = 0; i < args.length; i++) {
						switch(typeof(args[i])) {
							case "number" :
								str += '[' + args[i] + ']';
							break;
							default :
								str += '["' + args[i] + '"]';
						}
						eval('var exists = (' + str + ') ? true : false;');
						//alert(str + ' : exists : ' + exists);
						if (!exists) return false;
					}
					return true;
				}

				// general fn that the generic include fns can use to get data to use
				function get_bodycopy_anchors(bodycopy_name) {
					return bodycopy_current_data[bodycopy_name].get_anchors();
				}

				function bodycopy_chgColor(id, colour) {
					if (is_dom) {
						var chgcell
						if (!colour) { colour = '559AE7'; }
						chgcell = "document.getElementById('"+ id + "').style.backgroundColor = '#"+ colour +"'";
						eval(chgcell);
					}
				}

			</script>
			<script language="JavaScript" src="<?=$_BODYCOPY['file_prefix']?>js/bodycopy_edit_tables.js"></script>

			<STYLE TYPE="text/css">
				#bodycopyPopupDiv {
					color: #fffff;
					position:absolute; 
					left:100; 
					top:120; 
					width:<?=$init_layer_width?>; 
					height:<?=$init_layer_height?>; 
					clip:rect(0,600,600,0);
					visibility: hidden;
				}
				#bodycopyToolTipDiv {
					position:absolute; 
					width:  1px; 
					height: 1px; 
					visibility: hidden;
				}
				.bodycopy-edit {
					background-color: #b0b0b0;
				}
			</STYLE>
			<!-- Begin Tooltip -->
			<div id="bodycopyToolTipDiv">
			</div>
			<!-- End Tooltip -->

			<!-- Begin Popup -->
			<DIV ID="bodycopyPopupDiv">
				<!-- LAYER NAME="bodycopyLayer" WIDTH="<?=$init_layer_width?>" HEIGHT="<?=$init_layer_height?>" CLIP="0,0,<?=$init_layer_width?>,<?=$init_layer_height?>"></LAYER -->
				<IFRAME NAME="bodycopyFrame" ID="bodycopyFrame" SCROLLING="No" WIDTH="<?=$init_layer_width?>" HEIGHT="<?=$init_layer_height?>" MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER="No"></IFRAME>
			</DIV>
			<!-- End Popup -->

			<? if (file_exists($stylesheet_src)) { ?>
				<style type="text/css">
				<?=file_to_string($stylesheet_src); ?>
				</style>
			<? } #end if ?>

			<?

			$_BODYCOPY['edit_initialised'] = true;

		}#endif BODYCOPY INITIALISED

			$this->get_anchors();

		?> 

		<script language="JavaScript">
			bodycopy_current_data["<?=$this_bodycopy_name?>"] = new Object();
			function get_bodycopy_<?=$this_bodycopy_name?>_current_data(tableid, rowid, cellid) {
	
				retVal = new Object();

				switch (tableid) {
			<?

			// if we have tables get them to print the js data they need
			if (count($this->tables)) {
				# NOTE: Don't need to print the JavaScript include files
				# here because they will be printed by each objects print_backend()
				# only when required ( so as not to unecessarily include files 
				# {mainly applicable to the cell types} )

				for($i = 0; $i < count($this->tables); $i++) {
					$this->tables[$i]->print_backend_js_data($this_bodycopy_name, $i);
				}#end for tables

			}#end if 
			
			?> 
					default :
						retVal["num_tables"] = <?=(int) count($this->tables)?>;

				}// end switch tableid


				// check if this piece of data has been changed before
				// if it has, return the changed version, not the original
				if (tableid != -1) {
					if (rowid != -1) {
						if (cellid != -1) {
							if (bodycopy_saved && 
									bodycopy_saved["<?=$this_bodycopy_name?>"] && 
									bodycopy_saved["<?=$this_bodycopy_name?>"][tableid] && 
									bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"] &&
									bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid] &&
									bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid]["cells"] &&
									bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid]["cells"][cellid]) {
								for (var key in bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid]["cells"][cellid]) {
									retVal[key] = var_serialise(bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid]["cells"][cellid][key]); 
								}
							}
							return retVal;
						} else { 
							// editing row properties
							if (bodycopy_data_exists(new Array('<?=$this_bodycopy_name?>', tableid, 'rows', rowid, 'attributes'))) {
								retVal["attributes"] = var_serialise(bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["rows"][rowid]["attributes"]);
								return retVal;
							}
						}
					} else {
						// editing table properties
						if (bodycopy_data_exists(new Array('<?=$this_bodycopy_name?>', tableid, 'attributes'))) {
							retVal ["attributes"] = var_serialise(bodycopy_saved["<?=$this_bodycopy_name?>"][tableid]["attributes"]);
							return retVal;
						}
					}
				}

				return retVal;


			}// end get_bodycopy_<?=$this_bodycopy_name?>_current_data()

			function get_bodycopy_<?=$this_bodycopy_name?>_anchors() {
				if (bodycopy_current_data["<?=$this_bodycopy_name?>"]._anchors == null) {
					bodycopy_current_data["<?=$this_bodycopy_name?>"]._anchors = var_unserialise('<?=var_serialise($this->get_anchors(), true);?>');
				}
				return bodycopy_current_data["<?=$this_bodycopy_name?>"]._anchors;
			}

			// set reference so generic fn can be called above
			bodycopy_current_data["<?=$this_bodycopy_name?>"].get_data    = get_bodycopy_<?=$this_bodycopy_name?>_current_data;
			bodycopy_current_data["<?=$this_bodycopy_name?>"].get_anchors = get_bodycopy_<?=$this_bodycopy_name?>_anchors;
			bodycopy_current_data["<?=$this_bodycopy_name?>"]._anchors    = null;
		</script>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="bodytext">
		<? 
		# if there is more than table, we can reorder them
		if (count($this->tables) > 1) { 
		?>
			<tr>
				<td class="bodycopy-edit">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td nowrap><? 
								print_bodycopy_icon("javascript: bodycopy_edit_table_order('$this_bodycopy_name');", "Reorder Tables ?", "", "reorder_tables");
							?></td>
							<td nowrap>
								&nbsp;&nbsp;Reorder Tables
							</td>
							<td align="right" width="100%" nowrap><? 
								print_bodycopy_icon("javascript: bodycopy_copy_content('$this_bodycopy_name');", "Copy Bodycopy ?", "", "copy");
							?></td>
							<td nowrap>
								&nbsp;&nbsp;Copy All Tables
							</td>
						</tr>
					</table>
				</td> 
			</tr>
			<tr>
				<td>&nbsp;</td> 
			</tr>
		<?
		}#end if 
		$table_count = count($this->tables);
		for($i = 0; $i < $table_count; $i++) {
		?> 
			<tr>
				<td class="bodycopy-edit">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><? 
								print_bodycopy_icon("javascript: bodycopy_insert_table('$this_bodycopy_name', $i, true);", "Insert Table", "", "insert_table"); 
							?></td>
							<td>
								&nbsp;&nbsp;Create New Table&nbsp;&nbsp;
							</td>
							<td><? 
								print_bodycopy_icon("javascript: bodycopy_paste_table('$this_bodycopy_name', $i, true);", "Paste Table", "", "paste"); 
							?></td>
							<td>
								&nbsp;&nbsp;Paste Table
							</td>
						</tr>
					</table>
				</td> 
			</tr>
			<tr>
				<td>&nbsp;</td> 
			</tr>
			<tr>
				<td><? 
					$this->tables[$i]->print_backend($this_bodycopy_name, $i); 
				?></td>
			</tr>
			<tr>
				<td>&nbsp;</td> 
			</tr>
		<?
		}#end for
		?> 
			<tr>
				<td class="bodycopy-edit">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><? 
								print_bodycopy_icon("javascript: bodycopy_insert_table('$this_bodycopy_name', $i, false);", "Insert Table", "", "insert_table"); 
							?></td>
							<td>
								&nbsp;&nbsp;Create New Table&nbsp;&nbsp;
							</td>
							<td><? 
								print_bodycopy_icon("javascript: bodycopy_paste_table('$this_bodycopy_name', ($i-1), false);", "Paste Table", "", "paste"); 
							?></td>
							<td>
								&nbsp;&nbsp;Paste Table
							</td>
						</tr>
					</table>
				</td> 
			</tr>
		</table>
		<?
		// now print hidden fields for each table
		for($i = 0; $i < count($this->tables); $i++) {
			?>
			<input type="hidden" name="bodycopy_saved[<?=$this_bodycopy_name?>][<?=$i?>]" value="">
			<?
		}

	}#end print_backend()


	/**
	* Gets all the anchors from the bodycopy
	*
	* @access  public
	* @returns array
	*/
	function get_anchors() {

		$anchors = Array();
		$table_count = count($this->tables);
		for($i = 0; $i < $table_count; $i++) {
			$this->tables[$i]->get_anchors($anchors);
		}#end for

		array_unique($anchors);
		sort($anchors);

		return $anchors;

	}#end get_anchors()



	/**
	* Paste copied tables into the bodycopy
	*
	* @param   integer $pageid		The ID of the page that the content is being pasted on
	* @param   integer $tableid		The ID of the table to paste - if none supplied, 
	*								the whole bodycopy will be pasted
	* @param   integer $new_tableid The ID of the table you are pasting before or after
	* @param   boolean $before		Paste this content before the supplied table?
	* @param   string  $content		The content to be pasted (serialized)
	* @access  public
	* @returns void
	*/
	function paste_content($pageid=0, $tableid=0, $new_tableid=0, $before=false, $content='') {

		$web = &get_web_system();
		$page = &$web->get_page($pageid);
		$this_page = &$web->get_page();

		if (empty($this->tables)) {
			$new_tableid = 0;
		} else {
			# if the table is greater than the number of tables we have, set it to the last one
			if ($new_tableid > count($this->tables) - 1) $new_tableid = count($this->tables) - 1;
			else if ($new_tableid < 0) $new_tableid = 0;

			# if they want to insert after the passed tableid what 
			# they really mean is to insert before the next tableid
			if (!$before) $new_tableid++;
		}

		$map = array();
		$current_tableid = 0;

		if ($tableid >= 0) {
			$pasted_bodycopy = new Bodycopy('bodycopy');
			$pasted_bodycopy->tables[0] = unserialize($content);
		} else {
			$pasted_bodycopy = unserialize($content);
		}

		# pasting the entire page
		if (!empty($pasted_bodycopy->tables)) {
			foreach ($pasted_bodycopy->tables as $pasted_table) {

				# move all the tables up one position
				$table_count = count($this->tables);
				for($i = $table_count; $i > $new_tableid; $i--) {
					$this->tables[$i] = $this->tables[$i - 1];
				}

				# now paste the table
				$this->tables[$new_tableid] = $pasted_table;

				if ($page->id != $this_page->id) {
					# also need to copy files and change the ids
					if (!empty($page->file_index)) {
						foreach ($page->file_index as $fileid) {
							if ($pasted_bodycopy->tables[$current_tableid]->uses_file($fileid)) {
								$file = new File($fileid);
								$new_data_path = $this_page->data_path.'/'.$file->filename;
								$old_data_path = $page->data_path.'/'.$file->filename;
								if (file_exists($new_data_path)) {
									# get the id of this file
									$db = &$web->get_db();
									$id = $db->single_element("SELECT fileid FROM file WHERE pageid='$this_page->id' AND filename='$file->filename' LIMIT 1");
									$file = new File($id);
								} else {
									copy ($old_data_path,$new_data_path);
									$file->create($this_page->id, $file->filename, $file->description, $file->keywords, $file->visible, $file->orderno);
								}
								$map['file'][$fileid] = $file->id;
							}
						}
					}
				
					$this->tables[$new_tableid]->remap_link_ids($map);
				}

				$new_tableid++;

				# the id of the table we are currently looking at
				$current_tableid++;
			}
		}
	}



	/**
	* Process the input created by print_backend()
	*
	* This function handles all the actions that can be done
	* to a bodycopy. If will catch some bodycopy-wide actions
	* and will also call update() on each of its tables so they
	* can catch table specific actions.
	*
	* @param   string $this_bodycopy_name The name of the bodycopy to update
	* @access  public
	* @returns string
	* @see print_backend();
	*/
	function update($this_bodycopy_name='bodycopy') {
		global $bodycopy_action, $bodycopy_name, $bodycopy_data, $bodycopy_saved;
		global $_BODYCOPY;

		$msg = Array();
		
		# do some thing for the first time we print a bodycopy on this page
		if (!$_BODYCOPY['update_initialised']) {
			$bodycopy_action = $_POST['bodycopy_action'];
			$bodycopy_name  = gpc_stripslashes($_POST['bodycopy_name']);
			$bodycopy_data  = gpc_stripslashes($_POST['bodycopy_data']);
			$bodycopy_saved = gpc_stripslashes($_POST['bodycopy_saved']);

			# if there is some bodycopy data deserialise it into the arrays
			# so that everything else can use it
			if ($bodycopy_data) {
				$bodycopy_data = var_unserialise($bodycopy_data);
			}

			if (!empty($bodycopy_saved)) {
				for (reset($bodycopy_saved); NULL !== ($k = key($bodycopy_saved)); next($bodycopy_saved)) {
					$tables = &$bodycopy_saved[$k];
					if (!empty($tables)) {
						for (reset($tables); NULL !== ($tableid = key($tables)); next($tables)) {
							$tables[$tableid] = var_unserialise($tables[$tableid]);
						}
					}
				}
			}


			$_BODYCOPY['update_initialised'] = true;

		}#end if


		 ############################################################################
		# only update if we are the bodycopy that submitted the form
		if ($bodycopy_name == $this_bodycopy_name || count($bodycopy_saved[$this_bodycopy_name])) {

			###########################
			# now update the tables
			$table_count = count($this->tables);
			for($i = 0; $i < $table_count; $i++) {
				if ($tmp_msg = $this->tables[$i]->update($this_bodycopy_name, $i)) {
					$msg = array_merge($msg, $tmp_msg);
				}
			}#end for

			if ($bodycopy_name == $this_bodycopy_name) {
				switch($bodycopy_action) {
					case 'insert table' :
						$msg[] = $this->insert_table($bodycopy_data['tableid'], $bodycopy_data['num_rows'], $bodycopy_data['num_cols'], $bodycopy_data['type'], $bodycopy_data['before'], $bodycopy_data['attributes']);
					break;

					case 'delete table' :
						$msg[] = $this->delete_table($bodycopy_data['tableid']);
					break;

					case 'copy table' :
						# save the data we are going to need into the session
						# so that we can get the correct table on any other page
						$SESSION = &get_mysource_session();
						$web = &get_web_system();
						$page = &$web->get_page();
						$copy_content = serialize($this->tables[$bodycopy_data['tableid']]);
						$SESSION->set_var('bodycopy_copy_tableid', $bodycopy_data['tableid']);
						$SESSION->set_var('bodycopy_copy_content', $copy_content);
						$SESSION->set_var('bodycopy_copy_pageid', $page->id);
						$msg[] = 'Table '.($bodycopy_data['tableid'] + 1).' has been copied';
					break;


					case 'copy content' :
						# save the data we are going to need into the session
						# so that we can get the correct table on any other page
						$SESSION = &get_mysource_session();
						$tableid = -1;
						$web = &get_web_system();
						$page = &$web->get_page();
						$content = $this->pack();
						$SESSION->set_var('bodycopy_copy_tableid', $page->id);
						$SESSION->set_var('bodycopy_copy_tableid', $tableid);
						$SESSION->set_var('bodycopy_copy_content', $content);
						$msg[] = 'This pages content has been copied';
					break;

					case 'paste table' :
						$SESSION = &get_mysource_session();
						$pageid  = $SESSION->get_var('bodycopy_copy_pageid');
						$tableid  = $SESSION->get_var('bodycopy_copy_tableid');
						$content = $SESSION->get_var('bodycopy_copy_content');
						if (trim($content) != '') {
							$new_tableid = $bodycopy_data['tableid'];
							$this->paste_content($pageid, $tableid, $new_tableid, $bodycopy_data['before'], $content);
						} else {
							$msg[] = 'There was nothing to paste';
						}
					break;

					case 'edit table order' :
						$msg[] = $this->reorder_tables($bodycopy_data['table_order']);
					break;

				}#end switch
			}


		}#end if

		return implode("\n", $msg);

	}#end update()




	/**
	* Set up the file and pop-up prefixes
	*
	* @param   string  $file_href		The prefix for all external files (JS, CSS, images) that are needed
	* @param   string  $stylesheet_rel	Relative path to the stylesheet so the popups know where it is
	* @param   integer $siteid			The ID of the site that this bodycopy is in
	* @param   integer $pageid			The ID of the page that this bodycopy is on
	* @access  private
	* @returns void
	* @see print_backend()
	*/
	function set_prefixes($file_href='', $stylesheet_rel='', $siteid=0, $pageid=0) {
		global $_BODYCOPY;

		 ########################################################################
		# this prefixed for all external files (JS, CSS, images) that are needed
		$_BODYCOPY['file_prefix'] = $file_href."send_files/";

		 ########################################################################
		# this prefixed for all files that are needed popped-up
		$_BODYCOPY['pop_up_prefix'] = $_BODYCOPY['external_file']."?bodycopy_file_action=pop_up"
									.(($file_href) ? "&bodycopy_file_href=".rawurlencode($file_href) : "")
									.(($stylesheet_rel) ? "&bodycopy_stylesheet=".rawurlencode($stylesheet_rel) : "")
									.(($siteid) ? "&siteid=".rawurlencode($siteid) : "")
									.(($pageid) ? "&pageid=".rawurlencode($pageid) : "")
									."&bodycopy_file=";

	}#end set_prefixes()



	/**
	* Figure out what to do with an external file
	*
	* Either include a PHP page for the pop-ups or pipe down a file
	*
	* @param   string  $action		What you want to do with the file (eg send_file, pop_up)
	* @param   string  $file		The name of the file
	* @param   string  $file_href	The prefix for all external files (JS, CSS, images) that are needed
	* @param   string  $stylesheet	Relative path to the stylesheet so the popups know where it is
	* @access  public
	* @returns void
	*/
	function external_file($action='', $file='', $file_href='', $stylesheet='') {

		$file = str_replace("../", '', $file);
		$file = str_replace("./", '', $file);
	
		switch($action) {
			case 'send_file' :
				$file = dirname(__FILE__)."/send_files/$file";
				send_cacheable_file($file);
			break;

			case 'pop_up' :
				global $_BODYCOPY, $siteid, $pageid, $page_width, $page_height, $browser;
				# get the browsers to cache the files, and expire the page in an hour
				header("Cache-Control:");
				header("Pragma: cache");
				header("Expires: ". gmdate("D, d M Y H:i:s",time()+3600) . " GMT");
				BodyCopy::set_prefixes($file_href, $stylesheet, $siteid, $pageid);
				$file = dirname(__FILE__)."/pop_ups/$file";
				if (file_exists($file)) {
					include($file);
				}#end if
			break;

		} #end switch

	}#end external_file()



	/**
	* Replace a set of keywords with other text
	*
	* Keywords are denoted in the form "%keyword%"
	*
	* @param   array &$replacements An array of the replacements to make ($keyword => $replacement)
	* @access  public
	* @returns void
	*/
	function add_replace_keywords(&$replacements) {
		if (empty($replacements)) return;
		for(reset($replacements); NULL !== ($keyword = key($replacements)); next($replacements)) {
			$this->keyword_replacements[$keyword] = &$replacements[$keyword];
		}
	}


	/**
	* Replace a single keywords with other text
	*
	* Keywords are denoted in the form "%keyword%"
	*
	* @param   string $keyword		The keyowrd to replace
	* @param   string $replacement	The text to replace the keyword with
	* @access  public
	* @returns void
	*/
	function add_replace_keyword($keyword='', $replacement='') {
		$this->keyword_replacements[$keyword] = $replacement;
	}


	/**
	* Return all the keywords that are in the bodycopy
	*
	* @access  public
	* @returns array
	*/
	function get_keywords() {
		$keywords = array();
		$table_count = count($this->tables);
		for($i = 0; $i < $table_count; $i++) {
			$this->tables[$i]->get_keywords($keywords);
		}#end for
		return array_unique($keywords);
	}


	/**
	* Try and convert the passed string from the old style bodycopy to this one
	*
	* NOTE: THIS IS *REALLY* MYSOURCE DEPENDENT
	*
	* @param   string $text	The old bodycopy
	* @param   string $name	A unique name (to the template) for this bodycopy
	* @access  public
	* @returns array
	*/
	function convert_from_old_style($text='', $name='bodycopy') {

		# we only need this if we are converting
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/html_body_format.inc");

		$web = &get_web_system();
		$page = &$web->get_page();

		?> 
		<!-- 
		########################################
		      CONVERTING TO NEW BODYCOPY
		########################################
		-->
		<?

		$text = ltrim($text);
		# if it doesn't start with this I don't want to know you ........
		if (!ereg("^~[0-9]{3}-[0-9]{3}-[0-9]{3}", $text)) return false;

		$current_coord = "000-000-000";
		$array = array();
		if (!$text) return array(); #$array[$current_coord] = " ";
		for($line = strtok($text,"\n"); $line; $line = strtok("\n")) {
			if  ($line[0] == "~" && $line[count($line) - 1] == "~") {
				$current_coord = substr($line,1,-2);
				$array[$current_coord] = "";
			} else {
				$array[$current_coord] .= $line. "\n";
			}
		}

		# Get all the coordinates out and organise them
		$old_tables = Array();
		ksort($array);
		while(list($coord,$content) = each($array)) {
			# Break it up
			$tableid = strtok($coord,"-");
			$rowid   = strtok("-");
			$cellid  = strtok("-");
			$old_tables[$tableid][$rowid][$cellid] = $content;
		}

		# make a proper array out of the table array
		$tables = Array();
		foreach($old_tables as $old_tableid => $old_rows) {
			$tables[] = Array();
			$tableid = count($tables) - 1;
			$tables[$tableid]['tableid'] = $old_tableid;
			$tables[$tableid]['rows'] = Array();
			foreach($old_rows as $old_rowid => $old_cells) {
				$tables[$tableid]['rows'][] = Array();
				$rowid = count($tables[$tableid]['rows']) - 1;
				$tables[$tableid]['rows'][$rowid]['rowid'] = $old_rowid;
				$tables[$tableid]['rows'][$rowid]['cells'] = Array();
				foreach($old_cells as $old_cellid => $content) {
					$tables[$tableid]['rows'][$rowid]['cells'][] = Array();
					$cellid = count($tables[$tableid]['rows'][$rowid]['cells']) - 1;
					$tables[$tableid]['rows'][$rowid]['cells'][$cellid]['cellid']   = $old_cellid;
					$tables[$tableid]['rows'][$rowid]['cells'][$cellid]['content'] = $content;
				}#end foreach old_rows
			}#end foreach old_rows
		}#end foreach old_tables
	
		$bodycopy = new BodyCopy();

		foreach($tables as $tableid => $table_data) {

			$num_rows = count($table_data['rows']);
			$num_cols = 0;
			foreach($table_data['rows'] as $row_data) {
				if ($num_cols < count($row_data['cells'])) $num_cols = count($row_data['cells']);
			}
			# insert a table at the end of the list
			$bodycopy->insert_table(count($bodycopy->tables), $num_rows, $num_cols, "", false, Array("width" => "100%"));
			$new_tableid = count($bodycopy->tables) - 1;

			foreach($table_data['rows'] as $rowid => $row_data) {
				$rowid = (int) $rowid;
				foreach($row_data['cells'] as $cellid => $cell_data) {
					$cellid = (int) $cellid;
					$cell = &$bodycopy->tables[$new_tableid]->rows[$rowid]->cells[$cellid];
					$content = &$cell_data['content'];

					# Detect alignment codes
					if (ereg("\<[TMB][LCR]\>",substr(trim($content),0,4))) {
						switch ($content[1]) { # Vertical alignment
							case "T": $valign = "top"; break;
							case "M": $valign = "middle"; break;
							case "B": $valign = "bottom"; break;
						}
						switch ($content[2]) { # Horizontal alignment
							case "L": $align = "left"; break;
							case "C": $align = "center"; break;
							case "R": $align = "right"; break;
						}
						$content = substr(trim($content),4);
					} else {
						$valign  = "top";
						$align   = "left";
					}

					$cell->set_attribute("align", $align);
					$cell->set_attribute("valign", $valign);

					# Detect images
					if (substr(trim($content),0,7) == "[IMAGE:") {
						$coord = ereg_replace("-","_", substr($content,7,11));
						$link  = substr(trim($content),19);
						$image_file = get_image("$page->data_path/images/body_image_$name"."_".$coord);
						$caption    = file_to_string("$page->data_path/images/body_image_$name"."_".$coord.".cap");
						
						if ($image_file) {
							$fileid = 0;
							$new_file = $page->data_path."/".basename($image_file);
							# if the file exists assume that this convertor has already been run and 
							# that's why the file exists
							if (file_exists($new_file)) {
								# *cough* - direct DB access to find the fileid of the file 
								$fileid = $web->db->single_element("SELECT fileid FROM file WHERE pageid = '$page->id' AND filename='".basename($new_file)."'");

							}#end if file_exists

							# if there ain't no fileid then better copy the file and create the file record
							if (!$fileid) {
								if (copy($image_file, $new_file)) {
									$file = new File(0);
									# create the new invisible file
									$file->create($page->id, basename($new_file), "", "", "N");
									
								}#end if copy successful

							#else just create an instance of the file
							} else {
								$file = new File($fileid);
							}

							# if the file was created OK 
							if ($file->id) {
								# if there is a link or a caption then we need to make this a HTML cell
								if ($link || $caption) {
									if ($cell->set_type("bodycopy_table_cell_type_richtext")) {
										$img = "<img src=\"./?f=$file->id\" border=\"0\">";
										$html = "";
										if ($link) {
											$html .= "<a href=\"$link\">".$img."</a>";
										} else {
											$html .= $img;
										}
										if ($caption) {
											$html .= "<br><span class=fineprint>$caption</span>";
										}
										$cell->type->set_html($html);
									}#end if

								# else no link so can be just made into a file cell
								} else {
									if ($cell->set_type("bodycopy_table_cell_type_file")) {
										$cell->type->set_fileid($file->id);
									}
								}#end is links exists

							}#3n dif

						}#end if image_file

					# Detect files
					} elseif (substr(trim($content),0,6) == "[FILE:") {
						$fileid = substr(trim($content),6,-1);
						$fileid = (int) $fileid;
						# if the file exists, then we can use it 
						if ($fileid) {
							if ($cell->set_type("bodycopy_table_cell_type_file")) {
								$cell->type->set_fileid($fileid);
							}
						}
					} else {
						if ($cell->set_type("bodycopy_table_cell_type_richtext")) {
							$cell->type->set_html(format_text_to_html(hyperlink_urls($content)));
						}#end if
					}#end if

				}#end foreach cols

			}#end foreach rows

		}#end foreach tables

		return $bodycopy;

	}#end convert_from_old_style()


}#end Class BodyCopy
?>
