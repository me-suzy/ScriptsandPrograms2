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
## Desc: Lets the user select a page to have displayed on every page
## $Source: /home/cvsroot/xtras/site/design_areas/permanent_page/individual.inc,v $
## $Revision: 2.10 $
## $Author: ramato $
## $Date: 2003/07/18 06:46:21 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Permanent_Page_Individual extends Site_Design_Area {

	var $excluded_pageids = Array();

	function Site_Design_Area_Permanent_Page_Individual(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->_set_var('permanent_pageid', '', 'text', '');

	}#end constructor

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val('page_area_name')) {
			$this->_set_error('Permanent Page Area without a name', __FILE__, __LINE__);
			unset($this);
			return false;

		}

		return true;

	}#end create()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		if (count($old_this->excluded_pageids)) {
			$this->excluded_pageids = $old_this->excluded_pageids;
		}#endif

		return true;

	}#end update()

	function paint(&$tag_attributes) {

		$permanent_pageid = (int) $this->get_val('permanent_pageid');

		# no page to print
		if ($permanent_pageid) {
			# if we have excluded pages, make sure the current page is not one of them
			if (count($this->excluded_pageids)) {
				$current_pageid = $this->get_val('pageid');
				if (in_array($current_pageid, $this->excluded_pageids)) return;
			}

			# OK, things seem fine let's print the page
			$page = &$this->get_page($permanent_pageid);
			if ($page->id && $page->read_access()) {
				$template = &$page->get_template();
				$template->print_body();
				return;
			}
		}
		# some thing went wrong paint a blank image
		echo '<img src="'.lib_href('images/blank.gif').'" width="1" height="1" border="0">';

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name='') {

		$web_system = &get_web_system();
		$backend     = &$web_system->get_backend();

		$changes_made = false;
		$page_area_name = $this->get_val('page_area_name');
		$form_name   = ($prefix_name) ? $prefix_name : 'permanent_page_areas';
		$form_prefix = $form_name.'['.$page_area_name.']';

		$form_vars = $_POST[$form_name][$page_area_name];

		if ($form_vars) {

			# if the site has changed set the pageid to its index
			if ($form_vars['change_site']) {
				$site = $web_system->get_site($form_vars['siteid']);
				$form_vars['permanent_pageid'] = $site->index_pageid;
			}#end if

			$this->set_custom_var('permanent_pageid', $form_vars['permanent_pageid']);
			$this->set_excluded_pageids($form_vars['add_excluded_pageids'], $form_vars['remove_excluded_pageids']);

			$changes_made = true;

		}#end if

		$backend->open_section(ucwords(str_replace('_', ' ', $page_area_name)));
		$backend->open_field('Content Page', 'top');

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
			if ($pageid = (int) $this->get_val('permanent_pageid')) {
				if ($page = &$web_system->get_page($pageid)) {
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
			<input type="hidden" name="<?=$form_prefix?>[change_site]" value="">
			<tr>
				<td align="right">
					Site : 
				</td>
				<td>
					<?= combo_box($form_prefix.'[siteid]', $sites, $form_vars['siteid'], 'onchange="JavaScript: this.form.elements[\''.$form_prefix.'[change_site]\'].value=\'yes\'; this.form.submit();"'); ?> 
				</td>
			</tr>
		<?
		}

		$pages = $web_system->page_array_with_sticks($form_vars['siteid'], 0, '', '', true);
		$pages = array('0' => '[ NONE ]') + $pages;

		?> 
			<tr>
				<td align="right">
					Page :
				</td>
				<td>
					<?= combo_box($form_prefix.'[permanent_pageid]', $pages, $this->get_val('permanent_pageid')); ?> 
				</td>
			</tr> 
		</table>
		<?

		# Do page linking part
		$backend->open_field('Current Exclusisions','top');
		if (!count($this->excluded_pageids)) {
			echo "Every Page in this site is set to display this permanent page";
		} else {
			$site = &$this->get_site();
			$page_index = &$site->get_page_index();
			?>
			<table width="95%" cellpadding=2 border=0>
				<tr>
					<td class="data"><b>Page Lineage</b></td>
					<td width="2%" class="data"><span style="font-size:9pt; color:red;"><b>Delete</b></span></td>
				</tr>
			<?
			foreach($this->excluded_pageids as $tmp_page_id) {
				$page = &$web_system->get_page($tmp_page_id);
				$page_lineage = array_keys($page->get_lineage());
				$echo_str = '';
				foreach($page_lineage as $pageid) {
					if ($pageid == $page->id) break;
					$echo_str .= '<span style="font-size:8pt">'.$page_index[$pageid]['name'].'</style> : ';
				}#end foreach
				?> 
					<tr bgcolor="#d0d0e0">
						<td><?=$echo_str?><b><i><?=$page_index[$tmp_page_id]['name']?></i></b></td>
						<td align="center"><input type="checkbox" name="<?=$form_prefix?>[remove_excluded_pageids][]" value="<?=$tmp_page_id?>"></td>
					</tr>
				<?
			}#end foreach
			?> 
			</table>
			<?
		}#end if

		# Now do option to add new ones
		$backend->open_field('Exclude Other Pages','top');
		echo $web_system->page_multiple_combo_box($form_prefix.'[add_excluded_pageids]','', '', '', 20, true);
		return $changes_made;

	}#end print_user_backend()

	function set_excluded_pageids($additions, $removals) {

		if (!empty($additions))	{
			$this->excluded_pageids = array_unique(array_merge($this->excluded_pageids,$additions));
		}#end if

		if (!empty($removals)) {
			$this->excluded_pageids = array_diff($this->excluded_pageids,$removals);
		}#end if

	}#end set_excluded_pageids()

}#end class 

?>
