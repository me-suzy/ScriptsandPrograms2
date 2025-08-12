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
## $Source: /home/cvsroot/mysource/include/wizard_backend.inc,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/backend.inc");
#---------------------------------------------------------------------#


/**
* A backend class for printing the wizards
* A backend class for printing the wizards. The
* interface is more simple and doesnt contain a lot
* of the more complex backend functions. It does, however,
* inherit from Backend, so it can do all of them - it just
* might not use them for output.
*
* @access  public
* @package Wizards
*/
class Wizard_Backend extends Backend {

	var $errors = array();


	 ############################################################################################
	# Constructor - takes the relative path (for web links) and the path to the root backend dir
	function Wizard_Backend ($rel_path=".", $path="") {
		Backend::Backend($rel_path,$path);
	}


	function set_error($error_text='') {
		$this->errors[] = $error_text;
	}


	 ####################################################
	# Prints a special backend html header
	function print_header() {
		$system_config = &get_system_config();

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
			if (!empty($this->js_include)) {
				foreach($this->js_include as $js_file) {
					echo js_include($js_file);
				}
			}

			if($this->javascript)   echo js($this->javascript);
			if($this->relocation)   echo js_location($this->relocation);
			?>
		</head>
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="<?=$this->rel_path?>/images/brushed_metal.gif">
		<? $this->initialise_tooltips(); ?>
		<form name="edit" method="post" enctype="multipart/form-data">
		<?
		if (!empty($this->hidden_fields)) {
			foreach($this->hidden_fields as $key => $val) { ?>
				<input type="hidden" name="<?echo htmlspecialchars($key)?>" value="<?echo htmlspecialchars($val)?>">
			<?
			}
		}

		if($this->heading) {?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td>
					<?if($this->heading_icon) {?><img src="<?=$this->rel_path?>/images/icons/<?=$this->heading_icon?>.gif" width="20" height="20" border="0" align="left"><?}?>
					<p class="backend_heading"><?=$this->heading?>
				</td>
			</tr>
		</table>
		<? }

		if($this->subheading){?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td align="right">
					<?if($this->subheading_icon) {?><img src="<?=$this->rel_path?>/images/icons/<?=$this->subheading_icon?>.gif" width="20" height="20" border="0" align="left"><?}?>
					<p class="backend_subheading"><b><?=($this->subheading) ? $this->subheading : "&nbsp;";?></b>
				</td>
			</tr>
		</table>
		<?}
		if(!empty($this->errors)){?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<?
			foreach ($this->errors as $error) {
			?>
			<tr bgcolor="#c0c0c0">
				<td>
					<span style="color:#FF0000"><b>Error: </b><?=$error?></span>
				</td>
			</tr>
			<?}?>
		</table>
		<?}?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#c0c0c0">
			<tr><td valign="bottom"><img src="<?=$this->rel_path?>/images/black.gif" width="100%" height="1"></td></tr>
		</table>
		<?
		$this->header_printed = true;
	}

	function print_buttons() {
		$s = '';
		if (!empty($this->buttons)) {
			foreach ($this->buttons as $button) {
				$s .= $this->get_button($button['name']);
			}
		}
		$this->open_section($s);
	}

	function print_commit_button() {
		$this->print_buttons();
	}


	function add_button($button='Commit',$js='') {
		if(!$js) $js = "document.edit.action.value='Commit';document.edit.submit();";
		$this->buttons[$button]['name'] = $button;
		$this->buttons[$button]['js'] = $js;
	}

	function get_button($button='') {
		if (!isset($this->buttons[$button])) return '';
		$name = $this->buttons[$button]['name'];
		$js = $this->buttons[$button]['js'];
		$s = "<input type=\"button\" class=\"backend_field\" value=\"".htmlspecialchars($name)."\" onclick=\"".htmlspecialchars($js)."\">";
		return $s;
	}


	 ######################################################
	# Prints a nice little standard backend footer
	function print_footer() {
		if ($this->field_opened) $this->close_field();
		if ($this->section_opened) $this->close_section();
		?>
		<?if($this->jumpto) echo js_location("#$this->jumpto");?>
		</form>
		</body>
		</html>
		<?
	}

}



?>
