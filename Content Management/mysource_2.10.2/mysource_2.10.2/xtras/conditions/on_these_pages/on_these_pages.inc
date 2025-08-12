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
## $Source: /home/cvsroot/xtras/conditions/on_these_pages/on_these_pages.inc,v $
## $Revision: 1.1 $
## $Author: nking $
## $Date: 2004/01/15 06:49:49 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH, $EDIT_DIR;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class on_these_pages extends Condition {
	var $width  = 350;
	var $height = 600;

	  #############################################
	 # gets called to evaluate the condition     #
	#############################################
	
	function evaluate($val) {
		$wanted_ids = explode(",", $val);
		
		$web_system = &$this->get_web_system();
		$site = &$web_system->get_site();
		$page = &$site->get_page();

		#check to see if the page we are on is on the list
		if (in_array(&$page->id,$wanted_ids))
			return true;
		return false;
		
	}
	  ##################################
	 # function to print the backend  #
	##################################

	function print_backend(){
		# print the javascript so that we can return a value
		$this->print_javascript();

		$web_system = &$this->get_web_system();
		$siteid = gpc_stripslashes($_GET['siteid']);

		echo "<form name=\"on_these_pages\">\n";
		echo "<table align=\"center\">\n";
		echo "<tr><td>Please select which pages on this site, you would like this condition to be true for.</td></tr>\n";
		echo "<tr><td align=\"center\"><select name=\"included_pageids\" multiple size=20>\n";
		$pages_array = $web_system->page_array_with_sticks();
		foreach($pages_array as $id => $pagename){
			echo "<option value=\"" . $id ."\">" . $pagename . "</option>\n";
		}

		echo "</select></tr><tr><td align=\"center\"><input type=\"submit\" value=\"Commit\" onClick=\"save_conditions()\"></tr>";
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
				var selected_pages = new Array();
				var j = 0;

				for(i=0; i < this.on_these_pages.included_pageids.length; i++){
					if(this.on_these_pages.included_pageids.options[i].selected){
						selected_pages[j] = this.on_these_pages.included_pageids.options[i].value;
						j++;
					}
				}
				var retVal = selected_pages;
				window.opener.document.main_form.showif_conds.value = retVal;
				window.close();
			}
			function get_previous_options(){

				for(i = 0; i < this.on_these_pages.included_pageids.length; i++){
					for(j = 0; j < getVal.length; j++){
						if(this.on_these_pages.included_pageids.options[i].value == getVal[j]){
							this.on_these_pages.included_pageids.options[i].selected = true;
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
