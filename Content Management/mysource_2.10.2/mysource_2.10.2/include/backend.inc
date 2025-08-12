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
## File: include/backend.inc
## Desc: Functions for helping other classes print their own backends
## $Source: /home/cvsroot/mysource/include/backend.inc,v $
## $Revision: 2.52.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/14 04:57:00 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_general.inc");
include_once("$INCLUDE_PATH/html_image.inc");
include_once("$INCLUDE_PATH/html_form.inc");
#---------------------------------------------------------------------#


class Backend extends Object {
	
	var $rel_path = ''; # The relative (web-linkable) path to the backend dir
	var $path = '';     # The server-side path to the backend dir

	var $header_printed = false; # Set after the header has been printed
	var $delay_messages = true; # Wait to print messages until after the header is printed

	var $menu       = array (); # An array of information about the contents of the menu at the top of the screen
	var $active_menu_item = '';

	var $tabs       = array (); # An array of information about the contents of the tabs at the top of the screen
	var $active_tab = '';

	var $context_bars = array();

	var $hide_nav   = false; # Don't print menu and tabs

	var $title      = 'MySource Backend';
	var $message    = '';
	var $relocation = '';
	var $javascript = '';
	var $js_include = '';
	var $jumpto     = ''; # Jump to a certain part of rht page
	var $charset    = '';

	var $heading      = 'MySource Backend';
	var $heading_icon = '';
	var $subheading   = '';
	var $subheading_icon = '';
	var $name         = '';
	var $id_string    = '';

	var $help_link  = "javascript:alert('No help available on this topic.');";
	var $help_label = '[No Help Available]';

	var $toolbar = array(); # A list of buttons that appear on the top right, which do various things.

	var $hidden_fields = array(); # A key-value list of initial hidden values that need remembering

	var $section_opened = false; # Are we in the middle of printing a section?
	var $field_opened   = false; # Are we in the middle of printing a field?
	var $last_format    = '';    # The last format of an open cell (used to close the cell)

	var $section_count = 0;
	var $field_count = array();

	var $tooltips_enabled     = false; 
	var $tooltips_initialised = false; 

	var $default_commit_printed = false;
	var $msgerrorlevel = MYSOURCE_ERROR_CODE_NONE;

	# Values for the message popup.
	var	$ERROR_COLOUR   = '#a80606';
	var	$WARNING_COLOUR = '#b26b01';
	var	$NORMAL_COLOUR  = '#000000';

	 ############################################################################################
	# Constructor - takes the relative path (for web links) and the path to the root backend dir
	function Backend ($rel_path='.', $path='') {
		if(!$path) $path = $rel_path;
		$this->rel_path  = $rel_path;
		if(file_exists($path)) {
			$this->path  = $path;
		} else {
			$this->_set_error("Server-side path does not exist: $path",__FILE__,__LINE__);
		}

		 ###########################
		# Set the initial menu items
		$this->set_menu_item("mysource","mysource.php","MySource","Your MySource Start Page","swish");
		$this->set_menu_item("users","users.php","Users","Manage your user database","users");
		$this->set_menu_item("web","web.php","Web","Content management tools","web");
		if(superuser('web') || superuser('users')) {
			$this->set_menu_item("config","config.php","Config","Edit the configuration for the entire MySource system","create");
		}
	}

	 ###########################################
	# Adds or changes a tab in the tabs array
	function set_tab($code='',$link='',$label='',$desc='',$icon='') {
		$this->tabs[$code]['link']  = $link;
		$this->tabs[$code]['label'] = $label;
		$this->tabs[$code]['desc']  = $desc;
		$this->tabs[$code]['icon']  = $icon;
	}

	 #######################################################
	# Removes a tab from the tab array
	function remove_tab($code='') {
		if (isset($this->tabs[$code])) {
			unset($this->tabs[$code]);
			return true;
		}
		return false;
	}

	 ###########################################
	# Adds or changes a tab in the tabs array
	function set_active_tab($code='') {
		if ($this->tabs[$code]) {
			$this->active_tab = $code;
		} else {
			$this->_set_error("Attempt to set active tabs failed, tab does not exist: $code",__FILE__,__LINE__);
		}
	}

	 ###########################################
	# Returns the name of a particular tab
	function get_tab_label($code='') {
		if (isset($this->tabs[$code])) {
			return $this->tabs[$code]['label'];
		} else {
			return;
		}
	}
	
	 #############################################################
	# Clears the tabs array, but keep the one you pass it
	function clear_tabs($keep=Array()) {
		$tabs = Array();
		if (!empty($keep)) {
			foreach($keep as $code) {
				$tabs[$code] = $this->tabs[$code];
			}#end foreach
		}
		$this->tabs = $tabs;
	}

	 ###########################################
	# Adds or changes a menu_items in the menu_itemss array
	function set_menu_item($code='',$link='',$label='',$desc='',$icon='') {
		$this->menu_items[$code]['link']  = $link;
		$this->menu_items[$code]['label'] = $label;
		$this->menu_items[$code]['desc']  = $desc;
		$this->menu_items[$code]['icon']  = $icon;
	}

	 #######################################################
	# Removes a menu_items from the menu_items array
	function remove_menu_item($code='') {
		if (isset($this->menu_items[$code])) {
			unset($this->menu_items[$code]);
			return true;
		}
		return false;
	}

	 ###########################################
	# Sets the active menu item
	function set_active_menu_item($code='') {
		if ($this->menu_items[$code]) {
			$this->active_menu_item = $code;
		} else {
			$this->_set_error("Attempt to set active menu_item failed, menu_item does not exist: $code",__FILE__,__LINE__);
		}
	}

	 #######################################################
	# A context bar lies under the main menu and provides a 
	# means to jump back through the context e.g. links to
	# parent objects of the object you're currently editing
	function set_context_bar($code='',$content='') {
		$this->context_bars[$code] = $content;
	}

	 #######################################################
	# Appends contents to a context bar
	function append_context_bar($code='',$content='') {
		if (empty($this->context_bars[$code])) {
			$this->set_context_bar($code, $content);
		} else {
			$this->context_bars[$code] .= $content;
		}
	}

	 ##################################################
	# Removes a context_bar from the menu_items array
	function remove_context_bar($code='') {
		if (isset($this->context_bar[$code])) {
			unset($this->context_bar[$code]);
			return true;
		}
		return false;
	}

	 ###############################
	# Set the title of the HTML page
	function set_title($title='') {
		$this->title = ereg_replace("[\r\n]+"," ",$title);
	}

	 #################################
	# Set the message to be displayed
	function set_message($message='') {
		$this->message = $message;
	}

	 ############################################
	# Set if the messages should be delayed
	function set_delay_messages($delay_messages='') {
		$this->delay_messages = $delay_messages;
	}


	#################################
	# Are messages being delayed?
	function get_delay_messages() {
		return $this->delay_messages;
	}

	 #######################
	# Append to the message
	# This function is almost identical to add_message.It returns the message rather than adding it.
	# Why? So you can have multiple messages inside the same message, and each one can be highlighted.
	# Otherwise it brings up j/script errors..
	function append_message($var1, $var2='') {
		if (is_array($var1)) {
			reset($var1);
			list($msgerrorlevel,$msg) = $var1;

		} elseif($var2 != '') {
			$msgerrorlevel = $var1;
			$msg = (string) $var2;

		} else {
			$msgerrorlevel = MYSOURCE_ERROR_CODE_NONE;
			$msg = (string) $var1;
		}

		if (!trim($msg)) return;

		switch($msgerrorlevel) {
			case MYSOURCE_ERROR_CODE_ERROR:
				$col = $this->ERROR_COLOUR;
			break;
			case MYSOURCE_ERROR_CODE_WARNING:
				$col = $this->WARNING_COLOUR;
			break;
			case MYSOURCE_ERROR_CODE_NONE:
			default:
				$col = $this->NORMAL_COLOUR;
			break;
		}

		$msg = "<span style=\"color:".$col.";\">".$msg."</span>";
		$this->message .= (($this->message)?"\n":"").$msg;
		$this->msgerrorlevel = max($this->msgerrorlevel, $msgerrorlevel);
		return $msg;
	}

	 #######################
	# Append to the message
	# 
	# If $var1 is an array then the first 2 elements become the error level and message respectively
	# Else If $var2 is set then $var1 becomes the error level and $var2 is the message
	# Else $var1 is taken as the message and the error level is set to NORMAL
	function add_message($var1, $var2='') {
		if (is_array($var1)) {
			reset($var1);
			list($msgerrorlevel,$msg) = $var1;

		} elseif($var2 != '') {
			$msgerrorlevel = $var1;
			$msg = (string) $var2;

		} else {
			$msgerrorlevel = MYSOURCE_ERROR_CODE_NONE;
			$msg = (string) $var1;
		}

		if (!trim($msg)) return;

		switch($msgerrorlevel) {
			case MYSOURCE_ERROR_CODE_ERROR:
				$col = $this->ERROR_COLOUR;
			break;
			case MYSOURCE_ERROR_CODE_WARNING:
				$col = $this->WARNING_COLOUR;
			break;
			case MYSOURCE_ERROR_CODE_NONE:
			default:
				$col = $this->NORMAL_COLOUR;
			break;
		}

		$web_system = &get_web_system();

		$page = &$web_system->get_page();
		if ($page->id) {
			$msg = "(PageID: $page->id) " . $msg;
		}

		$this->_log_message($msg);
		$msg = "<span style=\"color:".$col.";\">".$msg."</span>";
		$this->message .= (($this->message)?"\n":"").$msg;
		$this->msgerrorlevel = max($this->msgerrorlevel, $msgerrorlevel);
		# if the header has already been printed then we need to print the message as well
		if ($this->header_printed || !$this->delay_messages) {
			$this->print_message($msg);
		}
	}

	 ############################
	# Returns the current message
	function get_message() {
		return $this->message;
	}

	 
	 ############################################################
	# Set the place to relocate to after displaying the message
	function set_relocation($location='') {
		if (!empty($location)) $this->relocation = $location;
	}

	 #######################################################################
	# Takes some arbtrary javascript to execute before the page is displayed
	function set_javascript($js='') {
		if (!empty($js)) $this->javascript = $js;
	}

	 #######################################################################
	# Takes a link to a javascript file to be included
	function add_js_include($path='') {
		if (!empty($path)) $this->js_include[] = $path;
	}

	 ##################################
	# Takes an <a name="x"> href thing
	function set_jumpto($jumpto='') {
		$this->jumpto = $jumpto;
	}

	 #####################################################
	# Sets the character set to use printing this backend
	function set_charset($charset='') {
		$this->charset = $charset;
	}

	 ############################################################
	# Set the heading (and optional icon)
	function set_heading($heading='', $icon='') {
		$this->heading      = $heading;
		$this->heading_icon = $icon;
	}

	 ############################################################
	# Set the subheading
	function set_subheading($subheading='', $icon='') {
		$this->subheading = $subheading;
		$this->subheading_icon = $icon;
	}

	 #####################################################
	# Set the name string for the currently edited object
	function set_name($name='') {
		$this->name = $name;
	}

	 ################################
	# Set the ID String on the right
	function set_id_string($id=0) {
		$this->id_string = $id;
	}

	 #################################################
	# Sets information for the help link
	function set_help($link='',$label="Help") {
		$this->help_link  = $link;
		$this->help_label = $label;
	}

	 ############################################################
	# Allows you to add or alter the contents of a tolbar button
	function set_toolbar_button($code='',$href='',$label='',$icon='',$target='') {
		if(!$href && !$label) {
			$this->remove_toolbar_button($code);
			return false;
		}
		if(!$icon)   $icon   = 'help';
		if(!$label)  $label  = $icon;
		if(!$href)   $href   = '#';
		$this->toolbar[$code] = array('href' => $href, 'label' => $label, 'icon' => $icon, 'target' => $target);
	}


	 ############################################################
	# Allows you to add or alter the contents of a tolbar button
	function remove_toolbar_button($code='') {
		if (isset($this->toolbar[$code])) {
			unset($this->toolbar[$code]);
			return true;
		}
		return false;
	}
	 
	 #################################################
	# Sets information for the help link
	function set_hidden_field($k='',$v='') {
		$this->hidden_fields[$k] = $v;
	}

	 ############################
	# Function hide the tabs
	function hide_tabs() {
		$this->hide_nav = true;
	}
	function show_tabs() {
		$this->hide_nav = false;
	}

	function initialise_tooltips() {
		$web_system = &get_web_system();
		return $web_system->initialise_tooltips();
	}

	 ###############################
	# Enables tooltips on the page
	function enable_tooltips() {

		$this->tooltips_enabled = true;

		# if the header has already been printed then we need to initialise the tooltips ourselves
		if ($this->header_printed) {
			$this->initialise_tooltips();
		}#end if
				
	}#end enable_tooltips()

	 ####################################################
	# Prints a special backend html header
	function print_header() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		if($this->charset) {
			header("Content-type: text/html; charset=$this->charset");
		} elseif($system_config->default_charset) {
			header("Content-type: text/html; charset=$system_config->default_charset");
		}
		$lib_href = lib_href();
		?> 
		<html>
		<head>
			<title><?=$this->title?></title>
			<!-- <?=MYSOURCE_LONG_NAME?> -->
			<link rel="STYLESHEET" type="text/css" href="<?=$lib_href?>css/edit.css">
			<?
			echo js_include($lib_href.'js/general.js');
			echo js_include($lib_href.'js/edit.js');
			echo js_include(squizlib_href('js','general.js'));
			echo js_include(squizlib_href('js','debug.js'));
			if (is_array($this->js_include)) {
				foreach($this->js_include as $js_file) {
					echo js_include($js_file);
				}
			}
			$message = $session->result_message;
			if($this->message) {
				if($message) $message .= "\n------------------------\n";
				$message .= $this->message;
			}
			if($message) {
				$this->print_message($message);
			}#end if

			if($this->javascript)   echo js($this->javascript);
			//if($this->relocation)   echo js('var loc = prompt("Redirect to ", "'.addslashes($this->relocation).'"); if (loc != null && loc != "") self.location = loc;');
			if($this->relocation)   echo js_location($this->relocation);
			if (mysource_jukebox()) echo "<bgsound src=\"http://mysource.squiz.net/?f=32\" loop=\"-1\">";
			?> 
		</head>
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF" background="<?=$this->rel_path?>/images/brushed_metal.gif">
		<? $this->initialise_tooltips(); ?> 
		<form name="edit" method="post" enctype="multipart/form-data">
		<? 
		foreach($this->hidden_fields as $key => $val) { ?>
			<input type="hidden" name="<?echo htmlspecialchars($key)?>" value="<?echo htmlspecialchars($val)?>">
		<?
		}

		if(!$this->hide_nav) {
			$this->print_menu();
			foreach($this->context_bars as $code => $content) {
				?><table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#c0c0c0" class="backend_page-lineage"><?=$content?></td></tr></table><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td valign="bottom"><img src="<?=$this->rel_path?>/images/black.gif" width="100%" height="1"></td></tr></table><?
			}
		}
		if($this->heading) {?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr bgcolor="#FFFFFF">
				<td class="backend_heading">
					<img src="<?=$this->rel_path?>/images/icons/<?=$this->heading_icon?>.gif" width="20" height="20" border="0" align="left">
					<?=$this->heading?>
				</td>
		<?
			if ($system_config->backend_brand_image) {
				?><td align="right"><?=$system_config->backend_brand_image?></td><?
			}
		?>
			</tr>
		</table>
		<? }
		if ($this->name || $this->id_string) { ?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr bgcolor="#ffffff">
				<td>
					<span class="backend_subheading"><?=(($this->name)? $this->name : "&nbsp;")?></span>
				</td>
				<td align="right">
					<span class="backend_subheading"><?=(($this->id_string)?"ID: $this->id_string":"&nbsp;")?></span>
				</td>
			</tr>
		</table>
		<? } 
		if(!$this->hide_nav) $this->print_tabs($this->tabs);
		?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td valign="bottom" bgcolor="#666666"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#ffffff"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
		</table>
		<?
		if($this->subheading || count($this->toolbar) > 0){?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr bgcolor="#212E61" id="mysource_header">
				<td>
					<?if($this->subheading_icon) {?><img src="<?=$this->rel_path?>/images/icons/<?=$this->subheading_icon?>.gif" width="20" height="20" border="0" align="left"><?}?>
					<p class="backend_subheading" style="color:#ffffff"><?=($this->subheading) ? $this->subheading : '&nbsp;';?>
				</td>
				<td align="right">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
						<?
						if ($this->toolbar) {
							$i = 1;
							$num = count($this->toolbar);
							foreach($this->toolbar as $code => $button) {
								?><td valign="middle" class="backend_subheading" style="color:#ffffff"><?
								$this->print_icon_button($button['href'],$button['label'],$button['icon'],$button['target']);
								?></td><?
								?><td valign="middle" class="backend_subheading" style="color:#ffffff"><?
								echo '&nbsp;';
								if ($i < $num) {
									echo ':&nbsp;';
								}
								$i++;
								?></td><?
							}
						} else {
							echo '&nbsp;';
						}
						?>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?}?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td valign="bottom" bgcolor="#212E61"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#259EC5"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#212E61"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
		</table>
		<?
		$this->header_printed = true;
	}



	 ################################################################
	# Prints a special backend html header for the floating site map
	function print_floating_site_map_header() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		if($this->charset) {
			header("Content-type: text/html; charset=$this->charset");
		} elseif($system_config->default_charset) {
			header("Content-type: text/html; charset=$system_config->default_charset");
		}
		$lib_href = lib_href();
		?> 
		<html>
		<head>
			<title><?=$this->title?></title>
			<!-- <?=MYSOURCE_LONG_NAME?> -->
			<link rel="STYLESHEET" type="text/css" href="<?=$lib_href?>css/edit.css">
			<?
			echo js_include($lib_href.'js/general.js');
			echo js_include($lib_href.'js/edit.js');
			echo js_include(squizlib_href('js','general.js'));
			echo js_include(squizlib_href('js','debug.js'));
			if (!empty($this->js_include)) {
				foreach($this->js_include as $js_file) {
					echo js_include($js_file);
				}
			}
			$message = $session->result_message;
			if($this->message) {
				if($message) $message .= "\n------------------------\n";
				$message .= $this->message;
			}
			if($message) {
				$this->print_message($message);
			}#end if

			if($this->javascript)   echo js($this->javascript);
			if($this->relocation)   echo js_location($this->relocation);
			if (mysource_jukebox()) echo "<bgsound src=\"http://mysource.squiz.net/?f=32\" loop=\"-1\">";
			?> 
		</head>
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="<?=$this->rel_path?>/images/brushed_metal.gif">
		<? $this->initialise_tooltips(); ?> 
		<form name="testform"></form>
		<form name="edit" method="post" enctype="multipart/form-data">
		<? 
		foreach($this->hidden_fields as $key => $val) { ?>
			<input type="hidden" name="<?echo htmlspecialchars($key)?>" value="<?echo htmlspecialchars($val)?>">
		<?
		}

		if ($this->name || $this->id_string) { ?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" background="">
			<tr>
				<td>
					<span class="backend_subheading"><?=(($this->name)? $this->name : "&nbsp;")?>&nbsp;<?=(($this->id_string)?"(ID: $this->id_string)":"&nbsp;")?></span>
				</td>
				<td align="right">
					<span class="backend_subheading"><?=$this->print_icon_button("Javascript: document.edit.action.value='Dock'; document.edit.submit();",'Dock','dock','','','');?></span>
				</td>
			</tr>
		</table>
		<? } 

		$this->header_printed = true;
	}


	 ##################################################
	# Print out the messages to the pop-up window
	function print_message($message='') {
		$system_config = &get_system_config();
		$win_name = "backend_messages_popup_".eregi_replace("[^a-z]","_",$_SERVER['HTTP_HOST']);

		$message = str_replace("\n", "<br>", str_replace("\r", "<br>", (str_replace("'","\\'", $message))));
		?>
		<script language="javascript" type="text/javascript">
		window.<?=$win_name?> = window.open('', '<?=$win_name?>', 'toolbar=no,width=400,height=250,titlebar=false,scrollbars=yes,resizable=yes');
		if(window.<?=$win_name?>.document.testform == null) {
			window.<?=$win_name?>.document.write('<html><head><title>Messages</title></head><body style="background-color:#d0d0d0;font-family:arial,verdana,sans serif,sans-serif;font-size:12px"><form name="testform"></form><img src="<?=$this->rel_path?>/images/icons/page.gif"> <span style="font-size:18px;font-weight:bold">Messages</span><br><br><form name="theform">');
		}
		window.<?=$win_name?>.document.writeln('<?=$message?><div align="right"><input type="button" value="/\\ <?=date("G:i:s")?>" style="font-size:10px;" onclick="window.opener.focus()" name="i<?=($t=time())?>" onfocus="javascript: blur()" /></div>');
	<?
		# if they want the message box to get focus then do it
		# OR if the errorlevel == error
		if ($system_config->backend_message_box_focus || $this->msgerrorlevel != MYSOURCE_ERROR_CODE_NONE) {
	?>
		window.<?=$win_name?>.focus();
		window.<?=$win_name?>.document.theform.i<?=$t?>.focus();
	<?
		}# end if
	?>
		</script>
		<?

	}#end print_message()


	 #################################################################
	# Prints a little icon button
	function print_icon_button($href='',$label='',$icon='',$target='',$width='20',$height='20',$border='0') {
		$tt_label  = ereg_replace("\n|\r|\n\r|\r\n","<br>",addslashes(str_replace("\"", "&quot;", $label)));
		?><a href="<?=$href?>" onmouseover="show_tooltip(event,'<?=$tt_label?>','',200);return true;" onmouseout="hide_tooltip();return'';" <?echo (($target)?"target=\"$target\"":"")?>><img src="<?=$this->rel_path?>/images/icons/<?=$icon?>.gif" <?=($width > 0) ? "width='$width'" : ''?> <?=($height > 0) ? "width='$height'" : ''?> border="<?=$border?>"></a><?
	}

	 ################################################
	# Prints a menu bar across the top of the screen
	function print_menu() {
		$session = &get_mysource_session();
 		$this->enable_tooltips();

		?><table width="100%" bgcolor="#212E61" cellpadding="0" border="0" cellspacing="0"><tr><td><table cellpadding="0" border="0" cellspacing="0"><tr><?

		foreach($this->menu_items as $code => $menu_item) {
			$tt_label = ereg_replace("[\n\r]+", " ",addslashes(str_replace("\"", "&quot;", $menu_item['label'])));
			$tt_desc  = ereg_replace("\n|\r|\n\r|\r\n","<br>",addslashes(str_replace("\"", "&quot;", $menu_item['desc'])));
			$tt_desc = addslashes(htmlspecialchars("<span class=\"backend_smallprint\">$tt_desc</span>"));
			$a_tag = "href=\"$menu_item[link]\" onmouseover=\"show_tooltip(event,'$tt_label','$tt_desc',200);return true;\" onmouseout=\"hide_tooltip();window.status='';\"";

			?><td nowrap><a <?=$a_tag?>><img src="<?=$this->rel_path?>/images/blank.gif" width="10" height="2" border="0"><img src="<?=$this->rel_path?>/images/icons/<?=$menu_item['icon']?>.gif" width="20" height="20" border="0"><img src="<?=$this->rel_path?>/images/blank.gif" width="3" height="2" border="0"></a></td><td><a <?=$a_tag?> class="backend_smallprint" style="font-weight: bold; text-decoration: none; color: <?=(($code==$this->active_menu_item)?"#ffffff":"#ffffff")?>"><?=$menu_item['label']?></a></td><?
		}

		?></tr></table>
		</td>
		<td>&nbsp;</td><td align="center" width="100%"><? $this->print_quicknav_combo(); ?></td><td>&nbsp;</td>
		<td align="right" valign="bottom" nowrap><span class="backend_smallprint" style="font-size:10px; color:#ffffff"><b><?=$session->user->name()?></b></span><br><span class="backend_fineprint" style="color:#ffffff"><?=date("d M Y H:i")?></span></td>
		<td valign="middle" align="center"><img src="<?=$this->rel_path?>/images/blank.gif" width="5" height="2" border="0"><a href="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>&mysource_session_action=logout" onmouseover="show_tooltip(event,'Log out','<?=ereg_replace("'","\\'",$session->user->name())?>',200); return true;" onmouseout="hide_tooltip();return true;"><img src="<?=$this->rel_path?>/images/icons/logout.gif" width="20" height="20" border="0"></a><img src="<?=$this->rel_path?>/images/blank.gif" width="5" height="2" border="0"></td></tr><tr><td bgcolor="#259EC5" colspan="6"><img src="<?=$this->rel_path?>/images/blank.gif" width="1" height="1"></td>
		</tr><tr><td bgcolor="#212E61" colspan="6"><img src="<?=$this->rel_path?>/images/blank.gif" width="1" height="1"></td>
		</tr><tr><td bgcolor="#ffffff" colspan="6"><img src="<?=$this->rel_path?>/images/blank.gif" width="1" height="1"></td>
		</tr></table><?
	}

	  ########################################################################################
	 # Prints a commit button with optional js checking to use the 's' hotkey to submit a page
	 function print_commit_button($button='',$js='',$print_section=true) {
		if ($print_section) $this->open_section($this->get_commit_button($button, $js),'','212E61');
		else echo $this->get_commit_button($button, $js);
		$this->close_section(true);
	 }

	  ########################################################################################
	 # Returns a commit button with optional js checking to use the 's' hotkey to submit a page
	 function get_commit_button($button='',$js='') {
		global $XTRAS;
		$js2 = "";
		# check to see if the email_page_update web extension is installed
		$mysource_conf = &get_config('mysource');

		if ($mysource_conf->email_page_update && version_compare($XTRAS->version('site/design_areas','email_page_update'), 1.1) > 0){
			$web = &get_web_system();
			$epu = &$web->get_extension('email_page_update');
			$p = ($_GET['p']) ? $_GET['p'] : $_GET['pageid'];
			
			if ($epu->is_in_page_list($p) && $epu->is_edit()){
				$js2 = "; open_page_update()";
				$site = &$web->get_site();
				
				?>
				<script language="Javascript">
				   function open_page_update(){
					  var link = "<?=$site->get_url(true)?>p=<?php echo $p ?>&mysource_web_extension=email_page_update&add_update=1";
					  window.open(link, 'Page_Update', 'width=400, height=200, scrollbars=no,resizable=no');
				   }
				</script>
			
			<?
			}
		}

		if(!$button) $button = 'Commit';
		if(!$js) $js = "document.edit.action='Commit';document.edit.submit();";
		$s = "<input type=\"button\" class=\"backend_field\" value=\"".htmlspecialchars($button)."\" onclick=\"".htmlspecialchars($js). $js2 . "\">";
		# only if we haven't printed this fn before do so
		if (!$this->default_commit_printed){
			$s .= "<script language=\"javascript\">function checksubmitform() { ".$js . $js2 . "}</script>";
			$this->default_commit_printed = true;
		}
		return $s;
	 }


	 #######################################################################################
	# Prints the combo box containing the modules listing at the top of every backend page
	function print_quicknav_combo() {
		global $XTRAS;

		$web = &get_web_system();

		$show = false;

		$site = &$web->get_site();
		if ($site->id) {
			$editable_site_extensions = $site->get_editable_extensions();
			$site_extensions          = $XTRAS->list_type("site/extensions");
			$show |= (!empty($editable_site_extensions));
		} else {
			$show |= false;
		}

		$global_areas = Array( 'system'  => array("base_page" => 'mysource.php'), 
							   'web'    => array("base_page" =>  'web.php'), 
							   'users'  => array("base_page" =>  'users.php')
							);

		for(reset($global_areas); ($type = key($global_areas)) !== NULL; next($global_areas)) {
			$global_areas[$type]['extensions'] = $XTRAS->list_type("$type/extensions");
			$show |= (!empty($global_areas[$type]['extensions']));
		}

		if (!$show) {
			echo '&nbsp;';
			return;
		}

		# the reason that we need to manually right the combo box is to allow 
		# the more than one separator to have a blank value
		?><select name="backend_quicknav" class="backend_smallprint" onChange="javascript: if (elementValue(this) != '') { window.location=elementValue(this); } return true;">
			<option value="">[ Available Extensions ... ]</option><?
		$site = &$web->get_site();
		if ($site->id && $site->admin_access()) {
			$editable_site_extensions = $site->get_editable_extensions();
			$site_extensions     = $XTRAS->list_type("site/extensions");
			if (!empty($editable_site_extensions)) {
				?><option value=""></option><option value="">Site Extensions &quot;<?=htmlspecialchars($site->name)?>&quot;&nbsp;:</option><?
				$count = 0;
				foreach($editable_site_extensions as $code) {
					if (!$site_extensions[$code]) continue;
				?><option value="site.php?s=<?=$site->id?>&extension_edit=<?=$code?>">&nbsp;&nbsp; <?=htmlspecialchars(ellipsisize($site_extensions[$code], 35))?></option><?
					$count++;
				}#end foreach
			}#end if
		}#end if

		if (superuser('user')) {
			$type = 'users';
			if (!empty($global_areas[$type]['extensions'])) {
			?><option value=""></option><option value=""><?=ucwords($type)?> Extensions&nbsp;:</option><?
				foreach($global_areas[$type]['extensions'] as $code => $name) {
				?><option value="<?=$global_areas[$type]['base_page']?>?extension_edit=<?=$code?>">&nbsp;&nbsp; <?=htmlspecialchars(ellipsisize($name, 35))?></option><?
				}#end foreach
			}#end if
		}

		if (superuser('web')) {
			$types = array('system', 'web');
			foreach($types as $type) {
				if (!empty($global_areas[$type]['extensions'])) {
				?><option value=""></option><option value=""><?=ucwords($type)?> Extensions&nbsp;:</option><?
					foreach($global_areas[$type]['extensions'] as $code => $name) {
					?><option value="<?=$global_areas[$type]['base_page']?>?extension_edit=<?=$code?>">&nbsp;&nbsp; <?=htmlspecialchars(ellipsisize($name, 35))?></option><?
					}#end foreach
				}#end if
			}
		}

		?></select><?

	}# print_quicknav_combo()


	  ###############################################
	 # Prints MySource backend tabs atop the page
	function print_tabs($tabs='') {
		$img_dir = "$this->rel_path/images";
 		$this->enable_tooltips();

		if(empty($tabs)) return;

		 #####################################################################
		# If the active tab hasn't been specified or it doesn't exist, we should use the last tab
		if (!$this->active_tab || !$tabs[$this->active_tab]) {
			end($tabs);
			$this->active_tab = key($tabs);
		}

		 #############################################
		# Make sure we don't go overboard
		# First lets shorten any offensively long tabs
		# (longer than half our total tab width)
		$total_width = 700; # We allow for a little spillage
		$chr_width   = 5; # Guestimate based on current stylesheet
		$tab_count   = count($tabs);
		for(reset($tabs); NULL !== ($key = key($tabs)); next($tabs)) {
			$label = &$tabs[$key]['label'];
			if (!$label) $label = "-----";
			$character_width = strlen(strip_tags($label));
			if($character_width > ($total_width/2)-2) { # Too big
				$label = ellipsisize($label,($total_width/2)-2);
				$character_width = strlen(strip_tags($label));
			}
			$label_lengths[$key] = $character_width * $chr_width + 12; # 12 pixels border each tab
			if($tabs[$key]['icon']) $label_lengths[$key] += 20; # Icon width
			$total_label_length += $label_lengths[$key];
			$label = &$blah;
		}

		$tab_rows = array();
		if($total_label_length > $total_width) {
			# We've gone too far - we have to split it into rows
			# Now its important that lower rows are longer than higher ones.
			# This means we have to do the tabs in reverse order !
			$row = array();
			$row_length = 0;
			$max_width = $total_width;
			$row_lengths = array();
			$err = 30; # Allowanceper row
			for(end($tabs); $key = key($tabs); prev($tabs)) {
				$length = $label_lengths[$key];
				if($row_length + $length > $max_width) { # Okay that's this row finito
					$max_width = $row_length - $err;
					$row_lengths[] = $row_length;
					array_unshift($tab_rows,array_reverse($row,1));
					$row = array();
					$row_length = 0;
				}
				$row[$key] = &$tabs[$key];
				$row_length += $length;
			}
			$row_lengths[] = $row_length;
			array_unshift($tab_rows,array_reverse($row,1));
			$row_lengths = array_reverse($row_lengths);

			# Okay, so now we have our rows, that's all very well
			# But they might be severely unbalanced. So lets balence em up bit.
			$tab_rows_count = count($tab_rows) - 1;
			for($rowid = 0; $rowid < $tab_rows_count; $rowid++) {
				# Grab as many tabs from the next row as we can
				$grabables = array();
				for(reset($tab_rows[$rowid + 1]); NULL !== ($key = key($tab_rows[$rowid + 1])); next($tab_rows[$rowid + 1])) {
					if($row_lengths[$rowid] + $label_lengths[$key] < $row_lengths[$rowid+1] - $label_lengths[$key] - $err) {
						$grabables[] = $key;
						$row_lengths[$rowid] += $label_lengths[$key];
						$row_lengths[$rowid+1] -= $label_lengths[$key];
					} else break; # Or else things get out of order :(
				}
				if (!empty($grabables)) {
					foreach($grabables as $key) {
						$tab_rows[$rowid][$key] = $tab_rows[$rowid + 1][$key];
						unset($tab_rows[$rowid + 1][$key]);
					}
				}
			}
		} else { # Only one row of tabz
			$tab_rows[0] = &$tabs;
		}


		# try and set the row that the active tab is in to the front
		$tab_rows_count = count($tab_rows);
		for($i = 0; $i < $tab_rows_count; $i++) {
			$tab_names = array_keys($tab_rows[$i]);
			$is_active_row = false;
			if (!empty($tab_names)) {
				foreach($tab_names as $name) {
					if ($this->active_tab == $name) {
						$is_active_row = true;
						break;
					}#end if
				}#end foreach
			}

			# so we are the active row are we, let's move us to the front
			if ($is_active_row) {
				$active_row = $tab_rows[$i];
				for($j = $i; $j > 0; $j--) {
					$tab_rows[$j] = $tab_rows[$j - 1];
				}#end for

				$tab_rows[0] = $active_row;
				
				break;
			}#end if

		}#end foreach

	?> 
		<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#ffffff">
			<tr>
				<td>
				<? $this->_print_tab_rows(0, $tab_rows); ?>
				</td>
				<td valign="bottom" width="100%">
					<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#ffffff">
						<tr>
							<td valign="bottom" width="100%"><img src="<?=$img_dir?>/blank.gif" width="100%" height="25" border="0"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?


	}

	function _print_tab_rows($rowid=0, &$tab_rows) {

		$img_dir = "$this->rel_path/images";

		#set up some easy to read vars
		$base_row = ($rowid == 0);
		$top_row  = ($rowid == count($tab_rows) - 1);

		# if this is not the base row then set the width to 100%
		$table_width = (!$base_row) ? 'width="100%"' : '';

	?> 
		<table cellpadding="0" cellspacing="0" border="0" <?=$table_width?> bgcolor="#ffffff">
	<?
		# if there are rows above this one, print them
		if (!$top_row) {
			$colspan = count($tab_rows[$rowid]);
		?> 
			<tr>
			<?
			# if this is not the base, pad it in a bit
			if (!$base_row) {
			?> 
					<td><img src="<?=$img_dir?>/blank.gif" width="5" height="1"></td>
			<?
			}#end if
		?>
				<td colspan="<?=$colspan?>" valign="bottom">
			<?
				$this->_print_tab_rows($rowid + 1, $tab_rows);
			?> 
				</td>
			<?
			# if this is not the base, pad it in a bit
			if (!$base_row) {
			?> 
					<td><img src="<?=$img_dir?>/blank.gif" width="5" height="1"></td>
			<?
			}#end if
		?>
			</tr>
		<?
		}#end if

		?> 
			<tr>
		<?

		# if this is not the base, pad it in a bit
		if (!$base_row) {
		?> 
				<td><img src="<?=$img_dir?>/blank.gif" width="5" height="1"></td>
		<?
		}#end if

		$tab_num = 0;
		if (!empty($tab_rows[$rowid])) {
			foreach($tab_rows[$rowid] as $tab => $data) {
				# we only want the inactive white lines if we are on the base row and not the active tab
				$state = (!$base_row || $this->active_tab == $tab) ? 'active' : 'inactive';
				# some escaping
				$tt_label = ereg_replace("[\n\r]+", " ",addslashes(str_replace("\"", "&quot;", $data['label'])));
				$tt_desc  = ereg_replace("\n|\r|\n\r|\r\n","<br>",addslashes(str_replace("\"", "&quot;", $data['desc'])));
				$tt_desc = "<span class=backend_tabs>$tt_desc</span>";
				if ($data['link']) {
					$a_tag = "href=\"$data[link]\" onmouseover=\"show_tooltip(event,'$tt_label','$tt_desc',200);return true;\" onmouseout=\"hide_tooltip();window.status='';\"";
				} else {
					$a_tag = "";
				}

				?> 
					<td>
						<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#ffffff">
							<tr>
								<td bgcolor="#ffffff" background="<?=$img_dir?>/tab_<?=$state?>_m.gif"><img src="<?=$img_dir?>/tab_<?=$state?>_l.gif" width="4" height="25"></td>
				<?
				if ($data['icon']) {
				?> 
								<td bgcolor="#ffffff" background="<?=$img_dir?>/tab_<?=$state?>_m.gif" nowrap><a <?=$a_tag?>><img src="<?=$img_dir?>/icons/<?=$data['icon']?>.gif" width="20" height="20" border="0"><img src="<?=$this->rel_path?>/images/blank.gif" width="5" height="2" border="0"></a></td>
				<?
				}
				?> 
								<td width="100%" bgcolor="#ffffff" background="<?=$img_dir?>/tab_<?=$state?>_m.gif" nowrap><a <?=$a_tag?> class="backend_smallprint" style="text-decoration:none; color: <?=(($this->active_tab == $tab) ? '#880088' : 'black')?>; font-weight:none;"><nobr><?=$data['label']?></nobr></a></td>
								<td bgcolor="#ffffff"><img src="<?=$img_dir?>/tab_<?=$state?>_r.gif" width="3" height="25"></td>
							</tr>
						</table>
					</td>
				<?
				$tab_num++;

			}#end foreach
		}

		# if this is not the base, so we need to pad it in a bit
		if (!$base_row) {
		?> 
				<td><img src="<?=$img_dir?>/blank.gif" width="5" height="1"></td>
		<?
		}#end if
	?> 
			</tr>
		</table>
	<?

	}#end _print_tab_rows()


	 ######################################################
	# Opens a sectin by printing a heading
	function open_section($heading='',$icon='',$anchor='', $bgcolor='B1BDCD') {
		if (!$heading) $heading = '&nbsp;';
		if ($this->section_opened) $this->close_section();
		$this->section_count++;
		?>
		<a name="Section_<?=$this->section_count?>"></a>
		<?=($anchor?'<a name="'.$anchor.'"></a>':'')?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td bgcolor="#<?=$bgcolor?>"><img src="<?=$this->rel_path?>/images/blank.gif" width="10" height="1"></td>
				<td bgcolor="#<?=$bgcolor?>" class="backend_data" colspan="2" width="100%">
					<?if($icon){?><img src="<?=$this->rel_path?>/images/icons/<?=$icon?>.gif" width="20" height="20" border="0" align="left"><?}?>
					<span class="backend_sectionheading"><?=$heading?></span>
				</td>
			</tr>
		<?
		$this->section_opened = true;
		return "Section_$this->section_count";
	}

	 #########################################
	# Closes off a section of form
	function close_section($nobreak=false) {
		if ($this->field_opened) $this->close_field();
		?>
		<?=(!$nobreak) ? '<tr><td bgcolor=#F0F0F0 colspan="3"><br></td></tr>' : ''?>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#F0F0F0" background="<?=$this->rel_path?>/images/section_dots.gif"><tr><td width="100%" colspan="3"><img src="<?=$this->rel_path?>/images/blank.gif" width="1" height="1"></td></tr></table>
		<?
		$this->section_opened = false;
	}

	 ######################################################
	# Prints the field and opens the area fo rprinting stuff
	function open_field($name='',$format='',$anchor='',$bgcolor='f0f0f0',$width=0) {
		if (!$name) {
			$name = '&nbsp;';
			$clean_name = '_Not available_'; # Saves us from some javascript errors...
		} else {
			$clean_name = str_replace('\"','\x22',addslashes(strip_tags(trim($name))));
		}
		
		if ($this->field_opened) {
			$this->close_field();
		}
		if (!$this->section_opened) {
			$this->open_section();
		}
		$this->field_count[$this->section_count]++;
		$bgcolor = trim($bgcolor);
		if (!$bgcolor) {
			$bgcolor = 'f0f0f0';
		}

		$system_config = &get_system_config();
		switch ($format) {
			case 'one_liner' :
				?>
					<tr>
						<td bgcolor="#<?=$bgcolor?>"><img src="<?=$this->rel_path?>/images/blank.gif" width="10" height="1"></td>
						<td nowrap bgcolor="#<?=$bgcolor?>" valign="top" class="backend_data"<?=($width) ? " width=$width" : ''?>>
							<a name="Field_<?=$this->section_count.'_'.$this->field_count[$this->section_count];?>"></a>
							<?=($anchor?'<a name="'.$anchor.'"></a>':'')?>
							<span class="backend_field"><?=$name?></span>
							<?
							if (isset($this->hidden_fields['siteid']) && $this->hidden_fields['siteid'] != '' && $name != '' && $name != '&nbsp;' && $system_config->mysource_help) {
							?>
								<span class="backend_help">&nbsp;<a href="javascript: popup_feature_help(<? echo $this->hidden_fields['siteid'] . ", '" . rawurlencode($clean_name)?>')" onmouseover="show_tooltip(event,'Get help for the field: <?=$clean_name?>','',200);return true;" onmouseout="hide_tooltip();return'';" >[<img src="<?=$this->rel_path?>/images/icons/help_small.gif" border="0" height="11" width="11" align="middle">]</a></span>
							<?
							}
							?>
						</td>
						<td width="100%" bgcolor="#<?=$bgcolor?>" valign="top" class="backend_data">
				<?
				break;
			default :
				?>
					<tr>
						<td bgcolor="#<?=$bgcolor?>"><img src="<?=$this->rel_path?>/images/blank.gif" width="10" height="1"></td>
						<td bgcolor="#<?=$bgcolor?>" valign="middle" class="backend_data" colspan="2"<?=($width) ? " width=$width" : ''?>>
							<a name="Field_<?=$this->section_count.'_'.$this->field_count[$this->section_count];?>"></a>
							<?=($anchor?'<a name="'.$anchor.'"></a>':'')?>
							<span class="backend_field"><?=$name?></span>
							<?
							if (isset($this->hidden_fields['siteid']) && $this->hidden_fields['siteid'] != '' && $name != '' && $name != '&nbsp;' && $system_config->mysource_help) {
							?>
								<span class="backend_help">&nbsp;<a href="javascript: popup_feature_help(<? echo $this->hidden_fields['siteid'] . ", '" . rawurlencode($clean_name)?>')" onmouseover="show_tooltip(event,'Get help for the field: <?=$clean_name?>','',200);return true;" onmouseout="hide_tooltip();return'';" >[<img src="<?=$this->rel_path?>/images/icons/help_small.gif" border="0" height="11" width="11" align="middle">]</a></span>
							<?
							}
							?>
							<table cellpadding=10 cellspacing=0 border=0><tr><td width=5><img src="<?=lib_href('images/blank.gif')?>" width=5 height=1></td><td width=100%>
				<?
				break;
		}
		$this->field_opened = true;
		$this->last_format = $format;
		return 'Field_'.$this->section_count.'_'.$this->field_count[$this->section_count];
	}


	 #########################################
	# Closes off a field of form
	function close_field() {
		switch ($this->last_format) {
			case 'one_liner' :
				?>
						</td>
					</tr>
				<?
				break;
			default :
				?>
						</td></tr></table>
						</td>
					</tr>
				<?
			break;
		}
		$this->field_opened = false;
		$this->last_format = '';
	}


	 ######################################################
	# Prints a nice little standard backend footer
	function print_footer($hide_version=false) {
		if ($this->field_opened) $this->close_field();
		if ($this->section_opened) $this->close_section();
		if (!$hide_version) {
			?>
			<table width="100%" cellpadding="2" cellspacing="0" border="0" bgcolor="#7A92AD">
				<tr><td bgcolor="#212E61"><img src="images/blank.gif" width="1" height="1"></td></tr>	
				<tr><td align="right"><b><a href="<?=MYSOURCE_URL?>" class="backend_smallprint" target="_blank"><?=MYSOURCE_LONG_NAME?></a></b></td></tr>
			</table>
			<?
		}
		if($this->jumpto) echo js_location("#$this->jumpto");
		?>
		</form>
		</body>
		</html>
		<?
	}

	function print_help_header() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		if($this->charset) {
			header("Content-type: text/html; charset=$this->charset");
		} elseif($system_config->default_charset) {
			header("Content-type: text/html; charset=$system_config->default_charset");
		}
		$lib_href =	lib_href();
		?> 
		<html>
		<head>
			<title>Help	System</title>
			<!-- <?=MYSOURCE_LONG_NAME?> -->
			<link rel="STYLESHEET" type="text/css" href="<?=$lib_href?>css/edit.css">
			<?
			echo js_include($lib_href.'js/general.js');
			echo js_include($lib_href.'js/edit.js');
			echo js_include(squizlib_href('js','general.js'));
			echo js_include(squizlib_href('js','debug.js'));
			if (is_array($this->js_include)) {
				foreach($this->js_include as $js_file) {
					echo js_include($js_file);
				}
			}
			$message = $session->result_message;
			if($this->message) {
				if($message) $message .= "\n------------------------\n";
				$message .=	$this->message;
			}
			if($message) {
				$this->print_message($message);
			}#end if

			if($this->javascript)	echo js($this->javascript);
			//if($this->relocation)	  echo js('var loc = prompt("Redirect to ",	"'.addslashes($this->relocation).'"); if (loc != null && loc !=	"")	self.location =	loc;');
			if($this->relocation)	echo js_location($this->relocation);
			if (mysource_jukebox())	echo "<bgsound src=\"http://mysource.squiz.net/?f=32\" loop=\"-1\">";
			?> 
		</head>
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF" background="<?=$this->rel_path?>/images/brushed_metal.gif">
		<? $this->initialise_tooltips(); ?>	
		<form name="edit" method="post"	action="<?=$_SERVER['REQUEST_URI']?>" enctype="multipart/form-data">
		<? 
		foreach($this->hidden_fields as	$key =>	$val) {	?>
			<input type="hidden" name="<?echo htmlspecialchars($key)?>"	value="<?echo htmlspecialchars($val)?>">
		<?
		}

		if($this->heading) {?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr bgcolor="#FFFFFF">
				<td class="backend_heading">
					<img src="<?=$this->rel_path?>/images/icons/help.gif" width="20" height="20" border="0"	align="left">
					<?=$this->heading?>
				</td>
				<td align="right"><img src="<?=$this->rel_path?>/images/help_brand_image.gif" width="100" height="50" border="0" align="right"></td>
			</tr>
		</table>

		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr bgcolor="#ffffff">
				<td>
					<span class="backend_subheading">"<?=stripslashes(strip_tags($_GET['fieldname']))?>"</span>
				</td>
			</tr>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td valign="bottom" bgcolor="#666666"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#ffffff"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td valign="bottom" bgcolor="#212E61"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#259EC5"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
			<tr><td valign="bottom" bgcolor="#212E61"><img src="<?=$this->rel_path?>/images/blank.gif" width="100%" height="1"></td></tr>
		</table>
		
		<?
		}
		$this->header_printed = true;
	}

	 ########################################
	# Log a backend message
	function _log_message($msg='') {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		if(trim($system_config->backend_log_messages)) {
			if (!$fp = fopen($system_config->backend_log_messages,"a")) {
				$this->_set_error("Unable to open file: ".$system_config->backend_log_messages,__FILE__,__LINE__);
				return false;
			}
			$msg = "\n".$session->user->id."\t".date('Y-m-d H-i-s')."\t".$msg;
			fputs($fp,$msg,strlen($msg));
			if (!fclose($fp)) {
				$this->_set_error("Unable to close file: ".$system_config->backend_log_messages,__FILE__,__LINE__);
				return false;
			}
		}
		return true;
	}

}

?>
