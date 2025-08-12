<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/design_areas/colourise_mage/individual.inc
## Desc: this is a single custom image for the design
## $Source: /home/cvsroot/xtras/site/design_areas/banner/individual.inc,v $
## $Revision: 2.16 $
## $Author: mbrydon $
## $Date: 2004/03/25 00:32:09 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

/**
** This class prints the actual banner on the frontend
**/
class Site_Design_Area_Banner_Individual extends Site_Design_Area {
	/**
	** Constructor
	** @public
	**/
	function Site_Design_Area_Banner_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);

		$this->_set_var('banners', '', 'variable', '');
		
	}#end Site_Design_Area_Banner_Individual()

	/**
	** @public
	** @returns boolean
	**/
	function create(&$tag) {

		 #######################
		# take a copy of the tag and use it to initialise that vars
		# so that we can get the type and declare some variable depending on that type 
		# below
		$this->_set_variables($tag, true);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a section_name, otherwise we don't know who we are
		if (!$this->get_val("section_name")) {
			$this->_set_error("Banner area without a name");
			unset($this);
			return false;
		}

		return true;

	}#end create()
 
	/**
	** This function randomly selects the appropriate number of each banner type to display.
	** It then displays them in rows according to the 'number across' variable
	** @public
	**/
	function paint() {
		$site = &$this->get_site();
		$banner_engine = &$site->get_extension('banner_engine');
		$page = &$this->get_page();
		$web_system = &get_web_system();

		$banners = $this->get_val('banners');

		$banner_list = "'" . implode("','", $banners) . "'";

		$date = date('Y-m-d');

		$sql =	'SELECT bannerid FROM '.$banner_engine->table_name.'_banner WHERE siteid='.$site->id.' AND (start_date="0000-00-00" OR start_date <= "'.$date.'") AND (end_date="0000-00-00" OR end_date >= "'.$date.'")'.((empty($banners) || in_array('ALL',$banners))?'':' AND bannerid IN (' . $banner_list . ')');

		$banner_set = $web_system->db->single_column($sql);

		if ($banner_set)	{
			$bannerid = $banner_set[array_rand($banner_set)];
			$banner = &$banner_engine->get_banner($bannerid);

			echo $web_system->image_tag($banner->data_path."/banner_".$banner->id,$banner_engine->get_file_href('banner/'.$banner->id),"",0,0,"border=0",$banner_engine->get_href(true)."p=".$page->id."&bannerid=".$banner->id);
		
			# Lets log a banner impression
			if ($banner_engine->parameters['log_impressions']) $banner->log_impression();
		}
	}#end paint()

	/**
	** Prints the backend for the site administrator to allow them to customise their design 
	**
	** @public
	** @returns boolean
	**/
	function print_user_backend($prefix_name="") {
		$changes_made = false;

		$web_system = &get_web_system();
		$site = &$this->get_site();
		$banner_engine = &$site->get_extension('banner_engine');
		$all_banners = $banner_engine->get_banner_names();
		$all_banners = array_reverse($all_banners,true);
		$all_banners['ALL'] = '[ALL BANNERS]';
		$all_banners = array_reverse($all_banners,true);

		$prefix_name = ereg_replace(' ','_',$prefix_name);

		$form_name = ($prefix_name) ? $prefix_name : "banner";
	
		$form_vars = &$_POST[$form_name][$prefix_name];

		if($_POST['action'] == 'Commit') {
			if($form_vars['banners'] != $this->get_val('banners')) {
				$this->set_custom_var('banners',$form_vars['banners']);
				$changes_made = true;
			}
		}

		$set_banners = $this->get_val('banners');
		
		$backend = &$web_system->get_backend();
		$backend->open_section(ucwords(str_replace("_", " ", $this->get_val("section_name"))));
	
		$backend->open_field('Banner Pool');

		echo multiple_combo_box($form_name.'['.$prefix_name.'][banners]',$all_banners,$set_banners);

		return $changes_made;

	}#end print_user_backend()

}#end class Site_Design_Area_Banner_Individual
?>
