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
## File: xtras/site/design_areas/switch/switch.inc
## Desc: A switch statement for set string variabes
#######################################################################
include_once(dirname(__FILE__).'/individual.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Switch extends Site_Design_Area {

	var $switches;  # an array of individual switch sections

	function Site_Design_Area_Switch(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = false;
		$this->settable = false;

		$this->switches = Array();

	}#end Site_Design_Area_Switch()

	  ############################################################
	 # Reset the owner for this object, then reset the buttons
	# owner to this object
	function reset_owner(&$owner) {

		for(reset($this->switches);
			$key = key($this->switches);
			next($this->switches)) {

			$this->switches[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		for(reset($this->switches);
			$key = key($this->switches);
			next($this->switches)) {

			$this->switches[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	function paint(&$tag_attributes) {

		# if this tag has the needed switch name
		#print("TAGATTRIBS: ");
		#print_r($tag_attributes);
		#print("...<br>");

		if ($tag_attributes['switch_name']) {

			# if this switch exists in this container
			if ($this->switches[$tag_attributes['switch_name']]) {
				#print("Painting ". $tag_attributes['switch_name'] ." with...<pre>");
				#print_r($tag_attributes);
				#print("!!!");
				$this->switches[$tag_attributes['switch_name']]->paint(&$tag_attributes);

			} else {
				$this->_set_error("Match '$tag_attributes[matches]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {

			$this->_set_error('Switch Name Not Present - Tag Attributes : '.array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}

	function create(&$tag) {

		$new_switch  = new Site_Design_Area_Switch_Individual($this);
		# try to create
		if ($new_switch->create($tag)) {

			#print("Trying to create switch: <pre><br>");
			#print_r($new_switch->_set_vars);
			#print("...</pre><br>");

			# check for matching switches and if they match then append a number
			# and keep going until we have a unique switch name
			$switch_name = $new_switch->compare;
			$i = 0;
			while (in_array($switch_name, array_keys($this->switches))) {

				$i++;
				$switch_name = $new_switch->compare . $i;
			}

			# if the name has changed then update the switch objects
			#if ($image_name != $new_image->get_val('image_name')) {
			#
			#        $new_image->set_var('image_name', $image_name);
			#}

			# add this image into the array
			$this->switches[$switch_name] = $new_switch;

			# return the switch name so it can be passed to paint() later
			#print("Created $switch_name<br>");
			return Array('switch_name' => $switch_name);
		}
	}

}#end class Site_Design_Area_Switch

?>
