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
## Desc: this is a that handle's constant buttons for a menu
## $Source: /home/cvsroot/xtras/site/design_areas/menu/section_types/constant_button/constant_button.inc,v $
## $Revision: 2.24 $
## $Author: brobertson $
## $Date: 2004/03/08 10:43:17 $
#######################################################################
#---------------------------------------------------------------------#



class Site_Design_Area_Menu_Section_Type_Constant_Button extends Site_Design_Area_Menu_Section_Type {

	function Site_Design_Area_Menu_Section_Type_Constant_Button(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		$this->contents = Array();

		  ################
		 #  UNEDITABLE  #
		################
		$this->_set_var("button_name", "", "text", "");
		$this->_set_var("image", "", "text", "");
		$this->_set_var("image_rollover", "", "text", "");
		$this->_set_var("image_current", "", "text", "");
		$this->_set_var("image_blank", "", "text", "");
		$this->_set_var("extra", "", "text", "");
		$this->_set_var("w3c", "0", "boolean", "Whether to use W3C compatible mode or not - not guaranteed to work for NS 4");
		$this->_set_var("onmouseover", "", "text", "the onmouseover events you wish to run");
		$this->_set_var("onmouseout", "", "text", "the onmouseout events you wish to run");

		  ################
		 #   EDITABLE   #
		################
		$this->_set_var("link_to", "", "text", "");
		$this->_set_var("url", "", "text", "An external URL to link to");
		$this->_set_var("target", "", "text", "The name of the target frame for the link");
		$this->_set_var("new_window", "0", "boolean", "");
		$this->_set_var("new_window_width", "700", "text", "");
		$this->_set_var("new_window_height", "600", "text", "");
		$this->_set_var("show_in_menu", "1", "boolean", "");
		$this->_set_var("text", "", "text", "the text that forms the link");
		$this->_set_var("class", "", "text", "");


	}#end constructor

	 ###################################
	# create the object from the tag
	function create(&$tag) {

		 ############################################################################
		# Only set up any variables, because we don't accept any HTML or other stuff here
		$this->_set_variables($tag, true);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a button_name, otherwise we don't know who we are
		if (!$this->get_val("button_name")) {
			$this->_set_error("Constant Button without a name", __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# we must have an image or text, otherwise what's the point??
		if (!$this->get_val("image") && !$this->get_val("text")) {
			$this->_set_error("Constant Button without text or image content", __FILE__, __LINE__);
			unset($this);
			return false;
		}

		return true;

	}#end create()

 	 ###################################################################
	# paint the actual button
	function paint(&$tag_attributes) {

		$site = &$this->get_site();
		$page_index = &$site->get_page_index();

		# do a bit of escaping for the var name
		$name = str_replace(" ", "_",    $this->get_val("button_name"));
		$name = str_replace("'", "_sq_", $name);
		$name = str_replace('"', "_dq_", $name);
		$name = "constant_button_".$name;
		$pageid = $this->get_pageid();

		# if there is a link then 
		if (intval($pageid) || trim($this->get_val('url')) != '') {

			# an internal link overrides an external url
			if (intval($pageid)) {
				$link = $this->get_val('page_link', $pageid);
				$status_text = $page_index[$pageid][name];
			} else {
				$link = $this->get_val('url');
				$status_text = $link;
			}

			$mouseover = $this->get_val('onmouseover');
			$mouseout  = $this->get_val('onmouseout');
			
			if ($this->get_val('new_window')) {
				$width = (int) $this->get_val('new_window_width');
				$height = (int) $this->get_val('new_window_height');
				if ($width  <= 0) $width  = 640; #default
				if ($height <= 0) $height = 600; #default
				$onclick = 'onClick="Javascript:new_window = window.open(\''.$link.'\', \'new_window_'.$pageid.'\', \'toolbar=no,width='.$width.',height='.$height.',titlebar=false,scrollbars=yes\');new_window.focus(); return false;"';
				$link = '#';
			}

			$target = '';
			if ($target = $this->get_val("target")) {
				$target = 'target="'.$target.'"';
			}

			# if there is an image then use it
			if ($image = $this->get_image_href("image")) {


				# if this is a rollover 
				if ($this->get_image_href("image_rollover")) { 

					$mouseover  .= "imgRoll('".$name."');";
					$mouseout   .= "imgRoll('".$name."');";
					$site_design = &$this->get_top_owner();
					$site_design->add_image_rollover($name, $this->get_image_href("image_rollover"));

				}#end if

				# if there is a current button and we are the current page
				if ($this->get_val("image_current")) { 
					$page = &$this->get_page();
					if ($pageid == $page->id) {
						$image = $this->get_image_href("image_current");
					}#end if
				}#end if

				?><a href="<?=$link?>" <?=($onclick) ? $onclick : $target?> onmouseover="<?=$mouseover?> window.status='<?=addslashes($status_text)?>';return true;" onmouseout="<?=$mouseout?> window.status='';return true;"><img src="<?=$image?>" id="<?=$name?>" <?= (!$this->get_val("w3c")) ? 'name="'.$name.'"' : ''; ?> border="0" <?=$this->get_val("extra");?>></a><?
				return;

			# else if there is text, use it
			} elseif ($this->get_val("text")) {

				$class = ($this->get_val("class")) ? "class='".$this->get_val("class")."'" : "";

				?><a href="<?=$link?>" <?=($onclick) ? $onclick : $target?> onmouseover="<?=$mouseover?> window.status='<?=addslashes($status_text)?>';return true;" onmouseout="<?=$mouseout?> window.status='';return true;" <?=$class?> <?=$this->get_val("extra");?>><?=$this->get_val("text")?></a><?
				return;

			}#end if

		}#end if page link

		# else something not write, so print a blank image

		# if they supplied a blank image, use it, otherwise use default blank
		if ($this->get_val("image_blank")) {
			$img = $this->get_image_href("image_blank");
		} else {
			$img = lib_href("images/blank.gif");
		}

		?><img src="<?=$img?>" border="0"><?


	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name=""){

		$web_system = &get_web_system();
		$backend     = &$web_system->get_backend();

		$changes_made = false;
		$button_name = $this->get_val("button_name");

		$form_name = ($prefix_name) ? $prefix_name : "menu_constant_buttons";

		$form_vars = &$_POST[$form_name][$button_name];

		if ($form_vars) {

			# if the site has changed set the pageid to its index
			if ($form_vars['change_site']) {
				$form_vars['home_link'] = '';
				$site = $web_system->get_site($form_vars['siteid']);
				$form_vars['pageid'] = $site->index_pageid;
			}

			# if they want a home link then set to the special value
			if ($form_vars['home_link'] == 'yes') {

				$this->set_custom_var('link_to', 'home');
				$form_vars['siteid'] = $web_system->current_siteid;

			# else must be linking to a page
			} else {

				$this->set_custom_var('link_to', $form_vars[pageid]);

			}#end if

			$this->set_custom_var('show_in_menu',  $form_vars['show_in_menu']);
			$this->set_custom_var('new_window',    $form_vars['new_window']);
			$this->set_custom_var('url',           $form_vars['url']);
			
			if ($form_vars['new_window']) {
				$this->set_custom_var('new_window_width',  $form_vars['new_window_width']);
				$this->set_custom_var('new_window_height',  $form_vars['new_window_height']);
			} else {
				$this->set_custom_var('target',  $form_vars["target"]);
			}

			if (!$this->get_val('image')) {
				$this->set_custom_var('text',  $form_vars['text']);
				$this->set_custom_var('class', $form_vars['class']);
			}#end if

			$changes_made = true;

		}#end if

		$backend->open_section(ucwords(str_replace('_', ' ', $button_name)).' Button');
		$backend->open_field('Link To', 'top');

		$home_checked = (strtolower($this->get_val('link_to')) == 'home') ? 'checked' : '';

	?> 
		<table border="0">
			<tr>
				<td align="right">
					<input type="checkbox" name="<?=$form_name?>[<?=$button_name?>][home_link]" value="yes" <?=$home_checked?>>
				</td>
				<td>
					Link to Home Page
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<b>OR</b>
				</td>
			</tr>
	<?
		 ################################################################################
		# NOTE : deliberately don't get by reference, so when we append the [THIS SITE], 
		#        it only happens once
		$sites = $web_system->get_editable_sites();
		$sites[$web_system->current_siteid] = '[THIS SITE] '.$sites[$web_system->current_siteid];

		# if we don't know what site to use use the current one
		if (!$form_vars['siteid']) {
			# if there is a pageid then get the site it belongs to
			if ($this->get_pageid()) {
				if ($page = &$web_system->get_page($this->get_pageid())) {
					$form_vars[siteid] = $page->siteid;
				}
			} 
			# if there still ain't one then use the current site
			if (!$form_vars['siteid']) {
				$form_vars['siteid'] = $web_system->current_siteid;
			}#end if
		}#end if

		# only if they have more than one site 
		if (count($sites) > 1) {
		?>
			<input type="hidden" name="<?=$form_name?>[<?=$button_name?>][change_site]" value="">
			<tr>
				<td align="right">
					Site : 
				</td>
				<td>
					<?= combo_box($form_name."[".$button_name."][siteid]", $sites, $form_vars[siteid], 'onchange="JavaScript: this.form.elements[\''.$form_name.'['.$button_name.'][change_site]\'].value=\'yes\'; this.form.submit();"'); ?> 
				</td>
			</tr>
		<?
		}

		$pages = $web_system->page_array_with_sticks($form_vars['siteid']);
		$pages = array("0" => "[ NONE ]") + $pages;

		?> 
			<tr>
				<td align="right">
					Page :
				</td>
				<td>
					<?= combo_box($form_name."[".$button_name."][pageid]", $pages, $this->get_pageid()); ?> 
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<b>OR</b>
				</td>
			</tr>
			<tr>
				<td align="right">
					URL :
				</td>
				<td>
					<?= text_box($form_name."[".$button_name."][url]", $this->get_val('url'), 40); ?> 
				</td>
			</tr>
		</table>
		<?

		$this->print_var_field($backend, 'new_window',  $form_name."[".$button_name."][new_window]");

		if ($this->get_val('new_window')) {
			$this->print_var_field($backend, 'new_window_width',  $form_name."[".$button_name."][new_window_width]", 10);
			$this->print_var_field($backend, 'new_window_height',  $form_name."[".$button_name."][new_window_height]", 10);
		} else {
			$this->print_var_field($backend, "target",  $form_name."[".$button_name."][target]");
		}

		$this->print_var_field($backend, "show_in_menu",  $form_name."[".$button_name."][show_in_menu]");

		# if we ain't displaying images then it must be text so let them edit it
		if (!$this->get_val("image")) {

			$this->print_var_field($backend, "text",  $form_name."[".$button_name."][text]");
			$this->print_var_field($backend, "class", $form_name."[".$button_name."][class]");

		}#end if no iamge

		return $changes_made;

	}#end print_user_backend()
	 
	 #######################################################
	# get's the page id that this button will be linking to
	function get_pageid() {

		# if this is the special home link then use it
		if (strtolower($this->get_val("link_to")) == "home") {
			return $this->get_val("home_pageid");
		} else {
			return $this->get_val("link_to");
		}#end if

	}#end get_pageid()

	  ####################################################
	 # returns the buttons pageid if it isn't to be 
	# shown in the normal menu
	function get_excluded_pageids() {

		if (!$this->get_val("show_in_menu")) {
			return Array($this->get_pageid());
		} else {
			return Array();
		}#end if

	}#end get_excluded_pageids()


}#end class Site_Design_Area_Menu_Section_Type_Constant_Button

?>
