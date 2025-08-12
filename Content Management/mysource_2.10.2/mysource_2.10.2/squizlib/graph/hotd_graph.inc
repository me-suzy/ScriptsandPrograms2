<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
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
## File: graph/moty_graph.inc
## Desc: Class for generating graphs based on the hours of the day - just a commonly-used shortcut
## $Source: /home/cvsroot/squizlib/graph/hotd_graph.inc,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:05 $
#######################################################################
# Requires the graph superclass
include_once(dirname(__FILE__)."/graph.inc");

#---------------------------------------------------------------------#

class Hotd_Graph extends Graph {

	var $twelvehr_lookup = array();

	  #############
	 # Constructor 
	function Hotd_Graph($type, $title, $subtitle,$preferred_width) {
		$result = Graph::Graph($type, $title, $subtitle,$preferred_width);
		$this->set_subject("Hour of the Day");
		for($h = 0; $h < 24; $h++) {
			$stamp = mktime($h,0,0);
			$this->twelvehr_lookup[strtolower(date("Ga",$stamp))] = $h;
			$this->twelvehr_lookup[strtolower(date("Ha",$stamp))] = $h;
			$this->add_candidate("h$h",date("ga",$stamp));
		}
		$this->twelvehr_lookup["12midnight"] = 0;
		$this->twelvehr_lookup["12noon"] = 12;
		return $result;
	}	


	 ########################################################
	# Sets the value, adjusted for little error tolerance
	function set_value($layer,$candidate,$value) {
		if(ereg("[^0-9]",$candidate)) {
			$candidate = $this->twelvehr_lookup[strtolower(eregi_replace("[^0-9-z]+","",$candidate))];
		}
		Graph::set_value($layer,"h".max(0,min(23,(int)$candidate)),$value);
	}

}


?>