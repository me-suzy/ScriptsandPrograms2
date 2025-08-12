<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## File: include/page_template_oldstyle.inc
## Desc: A page template class that lets old-style templates work as objects
## $Source: /home/cvsroot/mysource/include/page_template_oldstyle.inc,v $
## $Revision: 2.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:14 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$SQUIZLIB_PATH/colour/colour.inc");
include_once("$INCLUDE_PATH/image.inc");
#---------------------------------------------------------------------#


  ##############################################
 # Prints MySource backend tabs atop the page #
##############################################

function print_backend_tabs($tabs, $activated) {
	global $SESSION, $EDIT_IMAGES_DIR;
	if (!$activated) {
		end($tabs);
		$activated = key($tabs);
	}

	 #############################################
	# Make sure we don't go overboard
	$tab_count = count($tabs);
	for(reset($tabs); $key = key($tabs); next($tabs)) {
		if (!$tabs[$key][label]) $tabs[$key][label] = "*";
		$label_lengths[$key] = strlen(strip_tags($tabs[$key][label]));
		$total_label_length += $label_lengths[$key];
	}
	# Estimate character width average: 8
	$width = $total_label_length * 8 + (12 + 20) * $tab_count;
	if ($width > 550) {
		$total_trim = round(($width - 550) / 8);
		arsort($label_lengths);
		reset($label_lengths);
		for($i = $total_trim; $i > 0; $i--) {
			$key = key($label_lengths);
			$label_lengths[$key]--;
			if ($label_lengths[$key] <= 0) break;
			if (!$next = next($label_lengths)) {
				$next = reset($label_lengths);
			}
			if($label_lengths[$key] > $next) {
				reset($label_lengths);
			}
		}
	}

	echo("<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td valign=bottom>");
	echo("<table cellpadding=0 cellspacing=0 border=0><tr>");

	reset($tabs); 
	while(list($tab,$data) = each($tabs)) {
		$a = (($activated == $tab) ? "active" : "inactive");
		$desc = ereg_replace("'","\\'",$data[desc]);
		$desc = ellipsisize(ereg_replace("[\n\r]+"," ",$desc),100);
		$label = substr($data[label],0,$label_lengths[$tab]);
		$label = ellipsisize($data[label],$label_lengths[$tab]);
		if ($data[link]) {
			$a_tag = "href=\"$data[link]\" onmouseover=\"window.status='$desc';return true;\" onmouseout=\"window.status='';\"";
		} else $a_tag = "";
		echo("<td><img src=\"$EDIT_IMAGES_DIR/tab_$a"."_l.gif\" width=6 height=25></td>");
		if ($data[icon]) {
			echo("<td background=\"$EDIT_IMAGES_DIR/tab_$a"."_m.gif\"><a $a_tag><img src=\"$EDIT_IMAGES_DIR/icons/$data[icon].gif\" alt=\"$data[label]\" width=20 height=20 border=0><img src=\"images/blank.gif\" width=5 height=2 border=0></a></td>");
		}
		echo("<td background=\"$EDIT_IMAGES_DIR/tab_$a"."_m.gif\"><a $a_tag style=\"text-decoration:none; color: ".(($activated == $tab) ? "darkred" : "black").";\">$label</a></td>");
		echo("<td><img src=\"$EDIT_IMAGES_DIR/tab_$a"."_r.gif\" width=6 height=25></td>");
	}

	echo("</tr></table></td>");
	echo("<td align=right valign=top><a href=\"".$_SERVER['PHP_SELF']."?mysource_session_action=logout\" onmouseover=\"window.status='Log out ". ereg_replace("'","\\'",$SESSION->user->name())."'; return true;\" onmouseout=\"window.status='';return true;\"><img src=\"$EDIT_IMAGES_DIR/logout_button.gif\" width=20 height=20 alt=\"Log out ".$SESSION->user->name()."\" border=0 align=right></a><b>".$SESSION->user->name()."</b><br><span class=smallprint>" . date("d M Y|H:i") . "</span></td>");
	echo("</tr></table>");
}



 ######################################################
# Prints the standard backend header
function print_backend_header($extra_tabs=Array(), $subheading="", $extra_toolbar_buttons=Array()) {
	global $PAGEID;
	$template = &get_page_template($PAGEID);
	$backend  = &$template->setup_backend();

	foreach($extra_tabs as $code => $data) {
		$backend->set_tab($code,$data['link'],$data['label'],$data['desc'],$data['icon']);
		# if thi is meant to be the active tab, make it so
		if ($data['active_tab']) {
			$backend->set_active_tab($code);
			# also let's remove the default toolbar icons as they could get confusing with these sub tabs
			$backend->remove_toolbar_button("preview");
			$backend->remove_toolbar_button("delete");
			$backend->remove_toolbar_button("dupe");
		}
	}

	foreach($extra_toolbar_buttons as $code => $data) {
		$backend->set_toolbar_button($code,$data['href'],$data['label'],$data['icon'],$data['target']);
	}


	if ($subheading) $backend->set_subheading($subheading, "");

	$backend->print_header();
}


 ######################################################
# Prints the standard backend header
function print_backend_footer() {
	global $PAGEID;
	$template = &get_page_template($PAGEID);
	$backend  = &$template->setup_backend();
	$backend->print_footer();
}

 ######################################################
# Adds a message to the pop-up
function add_backend_message($message) {
	global $PAGEID;
	$template = &get_page_template($PAGEID);
	$backend  = &$template->setup_backend();
	if ($message) $backend->add_message($message);
}


  ######################################################################################
 # These functions are wrappers to the page object so that old templates can use them #
######################################################################################

function page_changable() {
	global $WEB,$page;
	$page = &$WEB->get_page();
	return $page->write_access();
}

function page_updated($pageid) {
	global $WEB,$page;
	$page->updated($pageid);
	return;
}

function load_page() {
	return;
}

function load_site() {
	return;
}

function &get_page_template($pageid) {
	global $PAGE,$page;
	$PAGE[template] = &$page->get_template();
	return $PAGE[template];
}



 ######################################
# Set these global values
# reserved work 'gobal' does not seem to work
$GLOBALS[WEB] = &get_web_system();
$GLOBALS[WEB_DB] = &$GLOBALS[WEB]->get_db();
$GLOBALS[USERS] = &get_users_system();
$GLOBALS[USERS_DB] = &$GLOBALS[USERS]->get_db();

$site = &$GLOBALS[WEB]->get_site();
$GLOBALS[BASE_DIR] = "http://{$site->urls[0]}";
$GLOBALS[EDIT_DIR] = "."; # None of the old templates use this anywhere that WOULDN'T be in the edit folder anyway.

$GLOBALS[SITEID]    = $GLOBALS[WEB]->current_siteid;
$GLOBALS[PAGEID]    = $GLOBALS[WEB]->current_pageid;
$GLOBALS[FILEID]    = $GLOBALS[WEB]->current_fileid;
$GLOBALS[COUNTRIES] = &get_config("Countries");
$GLOBALS[COUNTRIES] = $GLOBALS[COUNTRIES]->countries;

 ###########################################################
# This class can be reused to emulate old-style templates
# left over from the non-OO MySource
class Page_Template_OldStyle extends Page_Template {
	var $dir;

	 ##############################
	# Constructor
	function Page_Template_OldStyle($pageid) {
		$result = Page_Template::Page_Template($pageid);
		global $XTRAS_PATH;
		$this->dir = "$XTRAS_PATH/page/templates/$this->codename";
		return $result;
	}

	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID;
		global $PAGEID; 
		global $PAGE_TEMPLATE_ACTION;
		$PAGEID = $pageid;

		$file_types_config = &get_config("file_types");

		$PAGE_TEMPLATE_ACTION = "create";
		include($this->dir."/action.inc");
		return "$this->name page template created.";
	}

	

	 ###############################################################################
	# Pack the template details into $PAGE, like in the old-days, also setup tabs
	function load($pageid) {
		$this->id = $pageid;
		global $PAGE,$PAGEID,$SITE,$WEB,$WEB_DB,$USERS,$USERS_DB,$BASE_DIR,$EDIT_DIR,$BASE_TABS,$WEB_TABS,$EDIT_IMAGES_DIR;
		$PAGEID = $pageid;
		$db = &$this->get_db();
		$page = &$this->get_page();
		$site = &$this->get_site();

		global $PAGE_TEMPLATE_DATA_PATH;
		$PAGE_TEMPLATE_DATA_PATH = $page->data_path."/template";

		$PAGE[template] = $this->codename;
		$PAGE[name] = $page->name;
		$PAGE[href] = $page->get_href(true);
		$SITE[siteid] = $site->id;
		$SITE[name] = $site->name;
		$SITE[pages] = &$site->get_page_index();
		$SITE[description] = $site->description;

		 #####################################################
		# Load rest of page information from template record
		$template_db = $db->select("SELECT * FROM $this->table_name WHERE pageid='$this->id'");

		if (mysql_num_rows($template_db) == 0)	{
			$this->create($pageid);
			$template_db = $db->select("SELECT * FROM $this->table_name WHERE pageid='$this->id'");
		}
		for ($i = 0; $field = mysql_field_name($template_db, $i); $i++) {
			$PAGE[$field] = $this->$field = stripslashes(mysql_result($template_db, 0, $i));
			if (!$PAGE[$field]) $PAGE[$field] = "";
		}
		mysql_free_result($template_db);

		#$EDIT_DIR = "$BASE_DIR/edit"; # Done in init now I hope
		$EDIT_IMAGES_DIR = "$EDIT_DIR/images"; # Yeah yeah..

		 ############################################
		# Initialise some TABS arrays
		$BASE_TABS = array (
			"mysource" => array (
				"link"  => "mysource.php",
				"label" => "<span style=\"color: #800080; font-weight:bold;\">M<small>Y</small>S<small>OURCE</small></span>",
				"desc"  => "Configure MySource",
				"icon"  => ""
			),
			"web" => array (
				"link"  => "web.php",
				"label" => "Web",
				"desc"  => "Manage the Web System - Sites",
				"icon"  => "web"
			)
		);
		
		$WEB_TABS = $BASE_TABS;
		
		if ($WEB->current_siteid) {
			$WEB_TABS["site"] = array (
			"link"  => "site.php?siteid=$WEB->current_siteid",
			"label" => "$SITE[name]",
			"desc" => "Edit Site? $SITE[name] - $SITE[description]",
			"icon" => "site"
			);
		}

		if ($WEB->current_pageid) {
			$WEB_TABS["page"] = array (
			"link"  => "page.php?pageid=$WEB->current_pageid()",
			"label" => $SITE[pages][$WEB->current_pageid][name],
			"desc" => "Edit Page? ".$SITE[pages][$WEB->current_pageid][name]." - ".$SITE[pages][$WEB->current_pageid][description],
			"icon" => "page"
			);
		}

		return $pageid;
	}

	# dupe, new_id and delete need no change from the default


	 #######################################################
    # Duplicates this entire page template to a new pageid
    function dupe($new_pageid,$fileid_map)   {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID;
		global $PAGE_TEMPLATE_ACTION, $NEW_PAGEID, $PAGEID, $OLD_NEW_FILEIDS,$PAGE_TEMPLATE_DATA_PATH;
		$file_types_config = &get_config("file_types");
		$OLD_NEW_FILEIDS = $fileid_map;
		$NEW_PAGEID = $new_pageid;
		$PAGE_TEMPLATE_ACTION = "dupe";
		$old_PAGEID = $PAGEID;
		$PAGEID = $this->id;
		include($this->dir."/action.inc");
		$PAGEID = $old_PAGEID;
	}


	 #######################################################
    # Changes the id for this particular template
    function new_id($new_pageid) {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID;
		global $PAGE_TEMPLATE_ACTION, $NEW_PAGEID, $OLD_PAGEID,$PAGE_TEMPLATE_DATA_PATH;
		$file_types_config = &get_config("file_types");
		$PAGE_TEMPLATE_ACTION = "new_id";
		$NEW_PAGEID = $new_pageid; $OLD_PAGEID = $this->id;
		include($this->dir."/action.inc");
    }

	 
	 #######################################################
    # Removes the extension utterly from existance !
    function delete()   {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR,$PAGE_TEMPLATE_DATA_PATH, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID;
		global $PAGE_TEMPLATE_ACTION, $PAGEID;
		$file_types_config = &get_config("file_types");
		$PAGE_TEMPLATE_ACTION = "delete";
		$old_PAGEID = $PAGEID;
		$PAGEID = $this->id;
		include($this->dir."/action.inc");
		$PAGEID = $old_PAGEID;
    }


     ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		if (!$template_edit_file) $template_edit_file = "index.php";
		global $_POST,$_GET;
		foreach($_POST as $k => $v) {
			$$k = $v;
		}
		foreach($_GET as $k => $v) {
			$$k = $v;
		}
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID, $COUNTRIES,$PAGE_TEMPLATE_DATA_PATH;
		global $action;
		# Hack to make sure everything works fine
		$pageid = $PAGE[pageid];
		$siteid = $SITE[siteid];
		$file_types_config = &get_config("file_types");
		include($this->dir."/edit/index.php");
	}


	 ################################################
	# Prints or does various things on the front-end
	function print_frontend() {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID,$PAGE_TEMPLATE_DATA_PATH, $page;
		$file_types_config = &get_config("file_types");
		$site = &$this->get_site();
		$design = &$site->get_design();
		$print_design = true;  # paint the design by default, but can be overritten by the print.inc
		include_once($this->dir."/print.inc");
		 ###########################################
		# Let the site design take care of the rest
		if ($print_design) {
			$design->paint($this);
		}
	}



	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		global $PAGE;
		if($PAGE[title]) return $PAGE[title];
		return $PAGE[name];
	}

	 
	 ################################################
	# Prints the "body" of a page.. the main section
	function print_body() {
		global $WEB, $USERS, $WEB_TABS, $WEB_DB, $USERS_DB, $SITE, $PAGE, $FILE, $SESSION, $XTRAS_PATH, $INCLUDE_PATH, $XTRAS, $WEB_PATH, $DATA_PATH, $SQUIZLIB_PATH, $BIN_PATH, $BASE_DIR, $EDIT_DIR, $SYSTEM_CONFIG, $PAGEID, $SITEID, $FILEID,$PAGE_TEMPLATE_DATA_PATH, $page;
		$file_types_config = &get_config("file_types");
		include_once($this->dir."/print.inc");
		print_body();
	}

	function image_tag($file,$alt,$width,$height,$extra) {
		global $WEB;
		$page = &$WEB->get_page();
		$page = &$this->get_page();
		return $page->image_tag("template/$file",$alt,$width,$height,$extra);
	}

}

?>