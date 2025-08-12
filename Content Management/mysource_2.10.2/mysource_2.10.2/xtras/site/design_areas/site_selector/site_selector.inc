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
## File: xtras/site/designs_areas/searchbox/searchbox.inc
## Desc: this class is used to print the customised search box on the page
## $Source: /home/cvsroot/xtras/site/design_areas/site_selector/site_selector.inc,v $
## $Revision: 2.2 $
## $Author: blair $
## $Date: 2002/05/10 06:21:41 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Site_Selector extends Site_Design_Area {

	var $sites;  # an array of siteids that this selector will contain

	function Site_Design_Area_Site_Selector(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up some default variables, 
		# so unless they write over them we have something to play with
		#editable
		$this->_set_var("intro_text", "", "text", "text to show before the drop-down box");
		$this->_set_var("class",      "", "text", "stylesheet class for the text and drop-down");
		$this->_set_var("new_window",  "0", "boolean");
		$this->_set_var("onChange",    "1", "boolean", "redirect user as soon as they change the drop down");
		$this->_set_var("show_submit", "0", "boolean", "allow them to hit a button to perform the redirection");

		#uneditable
		$this->_set_var("intro_image", "", "text");
		$this->_set_var("submit_image", "", "text");

	}#end Site_Design_Area_Site_Selector()

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag, true);

		# no additional attributes needed
		return;

	}#end create()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		$this->sites = $old_this->sites;

	}#end update()

	function paint(&$tag_attributes) {

		if (!$this->sites) return;

		$web_system = &get_web_system();
		$sites = $web_system->get_site_names($this->sites);
		if (!$sites) return;

		$site_urls = Array();
		$current_site_url = '';
		foreach($sites as $siteid => $name) {
			$site = &$web_system->get_site($siteid);
			if (!$site->id) continue;
			$url = $site->get_url();
			if (!$url) continue;
			if ($site->id == $web_system->current_siteid) $current_site_url = $url;
			$site_urls[$url] = $name;
		}

		?> 
		<script language="JavaScript">
			function site_selector() {

				var elem = document.site_selector_form.site_selector_combo;
				var url  = 'http://' + elem.options[elem.selectedIndex].value;

			<? if ($this->get_val("new_window")) { ?>
				window.open(url, 'site_selector_window');
			<? } else { ?>
				window.location = url;
			<? }# end if ?>

			}//end site_selector()
		</script>
		<table border="0" cellspacing="0" cellpadding="0" background="">
		<form name="site_selector_form">
			<tr>
		<?

		$class = $this->get_val("class");
		if ($class) $class = 'class="$class"';


		$image = $this->get_image_href("intro_image");
		if ($image) {
		?> 
			<td><img src="<?=$image?>" border="0"></td>
		<?
		#only if there is something to print
		} else if ($this->get_val("intro_text")) {
		?> 
			<td><p <?=$class?>><?=$this->get_val("intro_text")?></td>
		<?
		}#end if

		$extras = ($this->get_val("onChange")) ? 'onChange="javascript: site_selector();"' : '';
		?> 
			<td><?= combo_box("site_selector_combo", $site_urls, $current_site_url, $extras);?></td>
		<?

		if ($this->get_val("show_submit")) {
		?> 
			<td>
			<?
				$image = $this->get_image_href("submit_image");
				if ($image) {

				?>
						<a href="javascript: site_selector();" onMouseOver="javascript: window.status=''; return true;" <?=$class?>><img src="<?=$image?>" border="0"></a>

				<?
				} else { # no image use default input button
				?>
						<input type="button" onClick="javascript: site_selector();" <?=$class?> name=Go value="Go">
				<?
				}#e3nd if
			?>

			</td>
		<?
		}#end if no submit
		?>

		</tr>
		</form>
	</table>
	<?

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name) {
		$web_system = &get_web_system();

		$changes_made = false;

		$uneditable = array("submit_image", "intro_image");

		# if there is an intro image no use editing the text
		if ($this->get_val("intro_image")) {
			$uneditable[] = "intro_text";
		}
		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable);

		$backend->open_field("Sites To List", "top");

		global $action, $sites_list;
		if ($action == "Commit") {
			$changes_made |= true;
			$this->sites = $sites_list;
		}
		$sites = &$web_system->get_editable_sites($id);
		echo multiple_combo_box("sites_list", $sites, $this->sites, "", "", 8);

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		return "Change the list of sites and the amount of interaction needed to redirect to another site";

	}#end get_description()

}#end class Site_Design_Area_Site_Selector

?>
