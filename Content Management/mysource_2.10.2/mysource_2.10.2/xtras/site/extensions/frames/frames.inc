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
## File: xtras/page/templates/standard/standard.inc
## Desc: A page template class.. the standard template is the most generic you can get
## $Source: /home/cvsroot/xtras/site/extensions/frames/frames.inc,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/02/28 05:10:48 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_extension.inc");
include_once(dirname(__FILE__)."/frame.inc");
#---------------------------------------------------------------------#

class Site_Extension_Frames extends Site_Extension {

	var $frameset_text = ""; # the html that forms the frameset
	var $index_pageid  = 0;  # the page to use as the index page for the main content frame
	var $index_frameid = 0;  # the frame that will contain the main content for the page

	var $_num_frames    = 0;  # the number of frames in the frameset_text, not stored
	var $_frames = Array();  # an array of frame objects, not stored

	 ##############################
	# Constructor
	function Site_Extension_Frames($siteid) {
		return Site_Extension::Site_Extension($siteid);
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Site_Extension::__sleep();
		array_remove_element("_frames",$result);
		return $result;
	}

	 ######################################################################
	# Create a 4record in the databse and load it into this object
	function create($siteid) {
		$message = Site_Extension::create($siteid);
		if(!$this->id)	return false;
		$db = &$this->get_db();
		$site = &$this->get_site();
		# set the index page from sites current one
		$this->index_pageid = $site->index_pageid;

		$this->frameset_text =	"<frameset cols=\"20%,*\">\n"
								."    <frame name=\"%name_0%\" src=\"%src_0%\">\n"
								."    <frame name=\"%name_1%\" src=\"%src_1%\">\n"
								."    <noframes>\n"
								."        Sorry, this site can be viewed only with a frames-capable browser.\n"
								."    </noframes>\n"
								."</frameset>\n";

		$sql = "UPDATE $this->table_name 
				SET index_pageid = '".$site->index_pageid."',
					frameset_text = '".addslashes($this->frameset_text)."'
				WHERE siteid='$siteid'";
		$db->update($sql);

		$this->clear_cache($this->id);
		$this->load();
		$this->update_frames();

		return $message;
	}

	 ################################################
	# Loads the template details, from cache or database
	function load($siteid) {
		if ($siteid <= 0 && !($siteid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name site extension without a valid siteid.",__FILE__,__LINE__);
		}

		 #################
		# Check the cache 
		if ($this->load_from_cache($siteid)) {
			return $siteid;
		}

		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();
		$sql = "SELECT siteid, frameset_text, index_pageid, index_frameid
				FROM $this->table_name 
				WHERE siteid='$siteid'";
		list(
			$this->id,
			$this->frameset_text,
			$this->index_pageid,
			$this->index_frameid,
			) = $db->single_row($sql);

		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($siteid);
		}

		$this->set_generated_information();

		 ###################
		# Save to the cache 
		$this->save_to_cache($siteid);
		return $siteid;
	}

	 #######################################################
	# Removes the template utterly from existance !
	function delete()   {
		# need to do this because the numer of frames drops when we delete a frame
		$num_frames = $this->get_num_frames();
		for($i = 0; $i < $num_frames; $i++) {
			$this->delete_frame($i);
		}
		return Site_Extension::delete();
	}

	 #######################################################
	# Duplicates this entire site extension to a new siteid
	function dupe($new_siteid) {
		global $DUPE_MESSAGE, $DUPE_MAP;

		$index_pageid = $DUPE_MAP['page'][$this->index_pageid];

		$db = &$this->get_db();
		$sql = "INSERT INTO $this->table_name 
				(siteid, frameset_text, index_pageid, index_frameid) 
				VALUES 
				('".addslashes($new_siteid)."', '".addslashes($this->frameset_text)."', 
				'".addslashes($index_pageid)."', '".addslashes($this->index_frameid)."')";
		if ($db->insert($sql)) {
			for($i = 0; $i < $this->get_num_frames(); $i++) {
				$frame = &$this->get_frame($i);
				$frame->dupe($new_siteid);
			}
		}

		$DUPE_MESSAGE .= "Frames Site Extension Dupicated";
		return true;

	}#end dupe()

	 #############################################
	# gets a frame for this page and returns it
	function &get_frame($frameid) {
		$frameid = (int) $frameid;
		$class = get_class($this)."_frame";
		if (get_class($this->_frames[$frameid]) != $class) {
			$this->_frames[$frameid] = new $class($this, $this->id, $frameid);
		}
		return $this->_frames[$frameid];
	}#end get_frame()

	 #############################################
	# adds a frame for this page
	function add_frame($frameid, $name="") {
		$class = get_class($this)."_frame";
		if (!$name) $name = "frame_".$frameid;
		$frame = new $class($this);
		$msg = $frame->create($this->id, $frameid, $name);
		$this->_frames[$frame->id] = $frame;
		unset($this->_num_frames);
		return $msg;
	}#end add_frame()

	 #############################################
	# deletes a frame for this page
	function delete_frame($frameid) {
		$frame = &$this->get_frame($frameid);
		$msg = $frame->delete();
		unset($this->_num_frames);
		return $msg;
	}#end delete_frame()

	 ######################################
	# Updates the frameset_text 
	function set_frameset_text($frameset_text) {
		if($frameset_text == $this->frameset_text) return "";
		$this->frameset_text = $frameset_text;
		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET frameset_text='".addslashes($this->frameset_text)."' WHERE siteid='$this->id'");
		$this->update_frames();
		$this->clear_cache();
		return "Frameset Text Updated";
	}#end set_frameset_text()


	 ####################################################################
	# runs through the frameset_text var and updates the frames array
	function update_frames() {

		# OK, let's count the number of frame tags we have
		# while there is both a name and src replacement for the frame keep counting
		$i = 0;
		while(strstr($this->frameset_text, "%name_$i%") && strstr($this->frameset_text, "%src_$i%")) {
			$i++;
		}

		# if there are now less frames counted than there were previously 
		# remove all extra frames from the array
		$num_frames = $this->get_num_frames();
		if ($num_frames > $i) {

			for($j = $i; $j < $num_frames; $j++) {
				$this->delete_frame($j);
			}#end for
		
		# else we need to add some elements to the array
		} else {
			for($j = $num_frames; $j < $i; $j++) {
				$this->add_frame($j);
			}#end for
		}#end if

	}#end update_frames()

	 ##########################################################
	# Updates the number of frames that are used by this page
	function get_num_frames() {
		if (!$this->_num_frames) {
			$db = &$this->get_db();
			$sql = "SELECT count(*) 
					FROM ".$this->table_name."_frame 
					WHERE siteid='$this->id'";
			$this->_num_frames = $db->single_element($sql);
		}
		return $this->_num_frames;
	}#end get_num_frames()


	 ######################################
	# Updates the index_pageid of the site
	function set_index_pageid($pageid) {
		if(!$pageid) return "Unable to set unspecified index page.";
		if($pageid == $this->index_pageid) return "";
		$new_page = &$this->get_page($pageid);
		if ($this->id != $new_page->siteid) return "Unable to set index page. Page not found on this site.";
		$this->index_pageid = $pageid;
		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET index_pageid='$this->index_pageid' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Index Page set to '$new_page->name'";

	}#end set_index_pageid()

	 ######################################
	# Updates the index_frameid of the site
	function set_index_frameid($frameid) {
		$frameid = (int)$frameid;
		if ($frameid < 0) return "Unable to set index frame.";
		if($frameid == $this->index_frameid) return "";
		$frame = &$this->get_frame($frameid);
		if (!$frame->siteid) return "Unable to set index frame. Frame not found.";
		$this->index_frameid = $frameid;
		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET index_frameid = '$this->index_frameid' WHERE siteid='$this->id'");
		$this->clear_cache();
		return "Index Frame set to '$frame->name'";
	}#end set_index_frameid()


	 ##################################################################
	# Prints the backend for the user
	function print_backend() {

		$backend = &$this->setup_backend();
		$site = &$this->get_site();

		$frameid = $_REQUEST['frameid'];
		$frame_prefix = $_SERVER['PHP_SELF']."?s=$this->id&extension_edit=$this->codename&frameid=";
		if (isset($frameid)) {
			$frame = &$this->get_frame($frameid);
			$frame->print_backend($frame_prefix, ($frameid == $this->index_frameid));
			return;
		}#end if


		switch($_REQUEST['action']) {
			case 'Commit':
				$backend->add_message($this->set_frameset_text(gpc_stripslashes($_REQUEST['frameset_text'])));
				$backend->add_message($this->set_index_pageid(gpc_stripslashes($_REQUEST['index_pageid'])));
				$backend->add_message($this->set_index_frameid(gpc_stripslashes($_REQUEST['index_frameid'])));
				break;
			default:
				break;
		}

		$backend->print_header();

		$backend->open_section();
		
		$backend->open_field('Frameset Text', 'one_liner');
		echo text_area('frameset_text', $this->frameset_text, 50, 20, 0, 'wrap="off" class="backend_data"');

		$backend->open_field('Index Page', 'one_liner');
		$index = &$site->get_page_index();
		if ($site->admin_access()) {
			$top_pageids = $site->get_top_pageids();
			foreach($top_pageids as $top_pageid)
				$top_pages_array[$top_pageid] = $index[$top_pageid]['name'];
			echo combo_box("index_pageid",$top_pages_array,$this->index_pageid,"class=backend_data");
			?><br><span class="fineprint">This is the page to display for the main content area</span><?
			unset($top_pages_array);
		} else {
			echo ("<p class=backend_data>".$index[$this->index_pageid]['name']);
		}

		$backend->open_field('Index Frame', 'one_liner');
		echo combo_box("index_frameid", range(0, $this->get_num_frames() - 1), $this->index_frameid, "class=backend_data");
		?><br><span class="fineprint">This is the frame to be used for the main content area</span><?


		$backend->open_field('Frames', 'one_liner');
		if ($this->get_num_frames()) {
		?> 
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td>
						<b>Frame #</b>
					</td>
					<td>
						<b>Name</b>
					</td>
				</tr>
		<?
			for($i = 0; $i < $this->get_num_frames(); $i++) {
				$frame = &$this->get_frame($i);
			?>
				<tr>
					<td align="right">
						<?=$frame->id?>
					</td>
					<td>
						<a href="<?=$frame_prefix.$frame->id?>"><?=$frame->name?></a>
					</td>
				</tr>
			<?				
			}
		?>
			</table>
		<?
		} else {
			?>No Frames Created<?
		}#end if


		$backend->print_commit_button('Commit',"document.edit.action.value='Commit';document.edit.submit();");
		$backend->print_footer();

	}#end print_backend()

	 ################################################
	# OK, let's create our frameset
	function print_frontend() {

		# if we are trying to print out a specific frame's contents call its print_frontend()
		$frameid = $_REQUEST['frameid'];
		if (isset($frameid)) {
			$frame = &$this->get_frame($frameid);
			$frame->print_frontend();
			exit();
		}#end if

		$system_config = &get_system_config();
		$site = &$this->get_site();
		$index_page = &$this->get_page($this->index_pageid);

		$replacements = Array();
		$set_pages    = Array();
		$href_prefix = $index_page->get_href(true);
		for($i = 0; $i < $this->get_num_frames(); $i++) {

			$frame = &$this->get_frame($i);

			$replacements["name_$i"] = $frame->name;
			if ($i == $this->index_frameid) {
				$replacements["src_$i"]  = $index_page->get_href();
				$set_pages[] = 'window.'.$frame->name.'.location = url;';
			} else {
				$suffix = 'mysource_site_extension='.$this->codename.'&frameid='.$frame->id;
				$replacements["src_$i"]  = $href_prefix.$suffix;
				$set_pages[] = 'window.'.$frame->name.'.location = url + ((url.indexOf("?") >= 0) ? "&" : "?") + "'.$suffix.'";';
			}


		}#end for
		$frameset_text = replace_keywords($this->frameset_text, $replacements);

		# we want to send these caching headers so that when the user hits the refresh button
		# they don't go back to the home page, expire the page in an hour
		header("Cache-Control:");
		header("Pragma: cache");
		header("Expires: ". gmdate("D, d M Y H:i:s",time()+3600) . " GMT");

	?>
		<html>
		<head>
		<!--   <?=$system_config->system_name?> 
		      Content © Copyright <?=$system_config->system_owner?> 
		    Running <?=MYSOURCE_LONG_NAME?> - <?=MYSOURCE_URL?> 
		   Developed by Squiz  - http://www.squiz.net
		  © Copyright 2001. All rights reserved. --> 
			<title><?=$site->name?></title>
			<script language="JavaScript">
				function show_page(url) {
					<?=implode("\n\t\t\t\t\t", $set_pages);?> 
				}// end show_page()
			</script>

	<?
		if ($index_page->keywords) {
		?> 
			<meta name="keywords" content="<?=str_replace('"','&quot;',$index_page->keywords)?>">
		<?
		}#end if keywords
		if ($index_page->description) {
		?> 
			<meta name="description" content="<?=str_replace('"','&quot;',$index_page->description)?>">
		<?
		}#end if description
	?> 
		</head> 
			<?="\n".$frameset_text?> 
		</html>
	<?
	}#end print_frontend()

	  ##########################################################################
	 # we don't want to do anything in print_body(), it should NEVER get called
	function print_body() {
	}

}#end class

?>