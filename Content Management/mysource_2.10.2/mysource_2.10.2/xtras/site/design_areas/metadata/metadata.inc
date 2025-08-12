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
## $Source: /home/cvsroot/xtras/site/design_areas/metadata/metadata.inc,v $
## $Revision: 1.1 $
## $Author: gsherwood $
## $Date: 2003/02/06 00:46:26 $
#######################################################################
include_once(dirname(__FILE__).'/individual.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Metadata extends Site_Design_Area {

	var $groups = Array(); # an array of individual metadata groups for this design

	function Site_Design_Area_Metadata(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;
	}#end Site_Design_Area_Metadata()

	  ############################################################
	 # Reset the owner for this object, then reset the groups 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->groups); 
			$key = key($this->groups); 
			next($this->groups)) {

			$this->groups[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the groups beneath it
	function &unset_owner() {

		for(reset($this->groups); 
			$key = key($this->groups); 
			next($this->groups)) {

			$this->groups[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->groups); 
			$key = key($this->groups); 
			next($this->groups)) {

			$tmp->groups[$key] = &$this->groups[$key]->copy();

		}#end for
		return $tmp;

	}#end copy()


	function create(&$tag) {

		$class = get_class($this).'_Individual';
		$new_group  = new $class($this);
		# try to create
		if ($new_group->create($tag)) {
			# check for matching group names and if they match then append a number 
			# and keep going until we have a unique group name
			$group = $new_group->get_val('group');
			$i = 0;
			while (in_array($group, array_keys($this->groups))) {
				$i++;
				$group = $new_group->get_val('group') . $i;
			}

			# if the name has changed then update the group objects
			if ($group != $new_group->get_val('group')) {

				$new_group->set_var('group', $group);
			}

			# add this group into the array
			$this->groups[$group] = $new_group;

			# return the group name so it can be passed to paint() later
			return Array('group' => $group);

		}

	}#end create()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		for(reset($this->groups); $key = key($this->groups); next($this->groups)) {
			if ($old_this->groups[$key]) {
				$this->groups[$key]->update($old_this->groups[$key]);
			}

		}#end for

	}#end update()

	function paint(&$tag_attributes) {

		# if this tag has the needed group name
		if ($tag_attributes['group']) {
		
			# if this group exists in this container
			if ($this->groups[$tag_attributes['group']]) {
				$this->groups[$tag_attributes['group']]->paint($keywords);
			} else {
				$this->_set_error("Metadata Area '$tag_attributes[group]' unknown", __FILE__, __LINE__);
			}#end if

		# else no group name
		} else {

			$this->_set_error('Metadata Area Name Not Present - Tag Attributes : '.array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

}#end class 

?>