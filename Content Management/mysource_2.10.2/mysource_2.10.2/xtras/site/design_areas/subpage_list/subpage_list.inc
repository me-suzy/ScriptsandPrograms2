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
## File: xtras/site/designs_areas/subpage_list/subpage_list.inc
## Desc: this class is used to print the list of subpages
## $Source: /home/cvsroot/xtras/site/design_areas/subpage_list/subpage_list.inc,v $
## $Revision: 2.10 $
## $Author: csmith $
## $Date: 2004/01/19 03:12:58 $
#######################################################################
include_once(dirname(__FILE__).'/subpage_list_listing.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Subpage_List extends Site_Design_Area {

	var $subpage; # the individual subpage that we loop over

	function Site_Design_Area_Subpage_List(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->_set_var('parentid', '0', 'text', 'The Page to display the sub pages of - defaults to the current page');
		$this->_set_var('anchor_to_parent', false, 'boolean', 'Do you want link to the selected page with anchors');

	}#end Site_Design_Area_Subpage_List()

	  ############################################################
	 # Reset the owner for this object, then reset the buttons 
	# owner to this object 
	function reset_owner(&$owner) {

		if($this->subpage) {
			$this->subpage->reset_owner($this);
		}#end if

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner()

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		if($this->subpage) {
			$this->subpage->unset_owner();
		}#end if

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if($this->subpage) {
			$tmp->subpage = &$this->subpage->copy();
		}#end if
		return $tmp;

	}#end copy()


	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		if($this->subpage && $old_this->subpage) {
			$this->subpage->update($old_this->subpage);
		}#end if

	}#end update()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, 'sub_list');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case 'TAG' : 

					# if this is the subpage section
					if (strtolower($element['attributes']['section']) == 'subpage') {

						$class_name = get_class($this).'_listing';

						# kill it because it's not needed anymore
						unset($element['attributes']['section']);
						
						$this->subpage = new $class_name($this);
						$this->subpage->create($element);

						# append to the contents array so we know were to print this
						# only print results once, therefore only put it in the content array once.
						$this->contents[] = Array('_type' => 'subpage');

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

	}#end create()


	function paint(&$tag_attributes) {
		# Only if we're printing a page, not a site extension or something
		$site_design = &$this->get_top_owner();
		if (!$site_design->painter_is_page_template())
			return;

		$web_system = &get_web_system();

		$site = &$this->get_site();
		$page = &$this->get_page($this->get_val('parentid'));

		# Don't bother if they're all invisible
		if(!$this->get_val('count')) return;

		# Now look through all our contents
		foreach($this->contents as $element) {

			switch($element['_type']) {
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
					switch($element['operation'])	{
						case 'print':
							$this->print_val($element);
						break;
					}

				break;

				# subpage section element for printing the file records
				case 'subpage' :
					$subpageids = &$page->get_subpageids();
					foreach($subpageids as $subpageid) {
						$this->subpage->paint($subpageid);
					}
					break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents


	}#end paint()


	  ################################################
	 # Returns a description of the design area 
	function get_description() {
		return 'Change the appearance of the list of the current page\'s subpages.';
	}#end get_description()



	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name,$pageid) {

		switch(strtolower($name)) {
            case 'count' :
				$web_system = &get_web_system();
				$page       = $web_system->get_page($this->get_val('parentid'));
				return count($page->get_subpageids());
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name,$pageid);
		}#end switch
	}


	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name='') {

		$web_system = &get_web_system();
		$backend     = &$web_system->get_backend();

		$changes_made = false;
		$form_name   = ($prefix_name) ? $prefix_name : get_class($this);

		$form_vars = &$_POST[$form_name];

		if ($form_vars) {

			# if the site has changed set the pageid to its index
			if ($form_vars['change_site']) {
				$site = $web_system->get_site($form_vars['siteid']);
				$form_vars['parentid'] = $site->index_pageid;
			}#end if

			$this->set_custom_var('parentid', $form_vars['parentid']);
			if (isset($form_vars['anchor_to_parent'])) {
				$this->set_custom_var('anchor_to_parent', $form_vars['anchor_to_parent']);
			}

			$changes_made = true;

		}#end if

		$backend->open_section('');
		$backend->open_field('Parent Page', 'top');

	?> 
		<table border="0">
	<?
		 ################################################################################
		# NOTE : deliberately don't get by reference, so when we append the [THIS SITE], 
		#        it only happens once
		$sites = $web_system->get_editable_sites($id);
		$sites[$web_system->current_siteid] = '[THIS SITE] '.$sites[$web_system->current_siteid];

		# if we don't know what site to use use the current one
		if (!$form_vars['siteid']) {
			# if there is a pageid then get the site it belongs to
			if ($parentid = (int) $this->get_val('parentid')) {
				if ($page = &$web_system->get_page($parentid)) {
					$form_vars['siteid'] = $page->siteid;
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
			<input type="hidden" name="<?=$form_name?>[change_site]" value="">
			<tr>
				<td align="right">
					Site : 
				</td>
				<td>
					<?= combo_box($form_name.'[siteid]', $sites, $form_vars['siteid'], 'onchange="JavaScript: this.form.elements[\''.$form_name.'[change_site]\'].value=\'yes\'; this.form.submit();"'); ?> 
				</td>
			</tr>
		<?
		}

		$pages = $web_system->page_array_with_sticks($form_vars['siteid']);
		$pages = array('0' => '[ NONE ]') + $pages;

		?> 
			<tr>
				<td align="right">
					Page :
				</td>
				<td>
					<?= combo_box($form_name.'[parentid]', $pages, $this->get_val('parentid')); ?> 
				</td>
			</tr> 
		</table>
		<?

		if ($this->get_val('parentid')) {
			$parent = &$this->get_page($this->get_val('parentid'));

			# we only want to print out this anchor option if the page we are pointing to is a
			# sub-page template because it is this that will put the anchors in
			if ($parent->template == 'sub_page') {
				$this->print_var_field($backend, 'anchor_to_parent', $form_name.'[anchor_to_parent]');
			}
		}

		return $changes_made;

	}#end print_user_backend()

}#end class Site_Design_Area_Pagefile_List

?>
