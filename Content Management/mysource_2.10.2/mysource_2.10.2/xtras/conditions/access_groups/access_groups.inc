<?php 
    ##############################################
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
## $Source: /home/cvsroot/xtras/conditions/access_groups/access_groups.inc,v $
## $Revision: 1.4.2.1 $
## $Author: tbarrett $
## $Date: 2004/05/11 01:38:09 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH, $EDIT_DIR;
require_once("$INCLUDE_PATH/condition.inc");
require_once("$INCLUDE_PATH/access_group.inc");
#---------------------------------------------------------------------#

class access_groups extends Condition {
	var $width  = 350;
	var $height = 300;


	  #############################################
	 # gets called to evaluate the condition     #
	#############################################
	
	function evaluate($val) {
		$wanted_ids = explode(",", $val);
		
		$session = &get_mysource_session();
		$user = $session->user;
		if (!$user->id) return false;
		
		$users_sys		= &get_users_system();
		$access_groups	= Access_Group::get_user_access_groups($user);

		# get the andor option when we piggy backed on the front
		# of the groupids

		$andor = $wanted_ids[0];
		unset($wanted_ids[0]);
	
		foreach($wanted_ids as $id){
			if(in_array($id, $access_groups)){
				if($andor == 'or') return true;
				if($andor == 'not') return false;
			} else {
				if($andor == 'and') return false;
			}
		}
		if($andor == 'or') return false;
		return true;
		
	}
	  ##################################
	 # function to print the backend  #
	##################################

	function print_backend(){
		# print the javascript so that we can return a value
		$this->print_javascript();

		$web_system = &$this->get_web_system();

		$siteid = gpc_stripslashes($_GET['siteid']);
		$access_groups = $web_system->db->associative_array("SELECT DISTINCT groupid, name, siteid FROM access_group where (siteid='$siteid' OR siteid='') ORDER BY name");

		echo "<form name=\"access_groups\">\n";
		echo "<table align=\"center\">\n";
		echo "<tr><td>Please select the access groups</td></tr>\n";
		echo "<tr><td align=\"center\"><select name=\"groups\" multiple>\n";
		
		foreach($access_groups as $id => $data){
			echo "<option value=\"" . $id ."\">" . $data['name'] . ($data['siteid'] ? '' : ' [ General ]') . "</option>\n";
		}
		
		echo "</tr></td></select>\n";
		echo "<tr><td align=\"center\">And / Or</td></tr>\n";
		echo "<tr><td align=\"center\"><select name=\"andor\"><option value=\"or\">Or</option><option value=\"and\">And</option>><option value=\"not\">Not</option>\n";
		echo "</select></tr></td><br>";
		echo "</tr><td align=\"center\"><input type=\"submit\" value=\"Commit\" onClick=\"save_conditions()\">";
		echo "</table>\n";
		echo "</form>";
		
		# get the previous selections
		echo "<script language=\"Javascript\">\n";
		echo "get_previous_options()\n";
		echo "</script>\n";
	}
	   ##################################
	  # function to return the values  #
	 # back to the main window        #
	##################################
	
	function print_javascript(){
	?>
		<script language="Javascript">
			var vals   = new String();
			var getVal = new Array();
			vals       = window.opener.document.main_form.showif_conds.value;
			
			getVal = vals.split(",");

			function save_conditions() {
				var selected_groups = new Array();
				selected_groups[0] = document.access_groups.andor.options[document.access_groups.andor.selectedIndex].value;
				var j = 1;
				for(i=0; i < document.access_groups.groups.length; i++){
					if(document.access_groups.groups.options[i].selected){
						selected_groups[j] = document.access_groups.groups.options[i].value;
						j++;
					}
				}
				var retVal = selected_groups;
				window.opener.document.main_form.showif_conds.value = retVal;
				window.close();
			}
			function get_previous_options(){

				for(i = 0; i < document.access_groups.andor.length; i++){
					if(document.access_groups.andor.options[i].value == getVal[0]){
						document.access_groups.andor.options[i].selected = true;
						break;
					}
				}
				for(i = 0; i < document.access_groups.groups.length; i++){
					for(j = 1; j < getVal.length; j++){
						if(document.access_groups.groups.options[i].value == getVal[j]){
							document.access_groups.groups.options[i].selected = true;
							break;
						}
					}
				}
			}
		
		</script>
	<?
	}
}
?>
