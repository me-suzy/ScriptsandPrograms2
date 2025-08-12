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
## Desc: Class for generating graphs based on the days of the week - just a commonly-used shortcut
## $Source: /home/cvsroot/squizlib/graph/dotw_graph.inc,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:05 $
#######################################################################
# Requires the graph superclass
include_once(dirname(__FILE__)."/graph.inc");

#---------------------------------------------------------------------#

class Dotw_Graph extends Graph {

	var $day_lookup = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
	var $lil_day_lookup = array("mon","tue","wed","thu","fri","sat","sun");
	var $big_day_lookup = array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");
	var $lookup = array();

	  #############
	 # Constructor 
	function Dotw_Graph($type, $title, $subtitle,$preferred_width) {
		$result = Graph::Graph($type, $title, $subtitle,$preferred_width);
		$this->set_subject("Day of the Week");
		for($d = 0; $d < 7; $d++) {
			$this->add_candidate("d$d",$this->day_lookup[$d]);
		}
		$lookup = array_merge(array_flip($this->lil_day_lookup),array_flip($this->big_day_lookup));
		return $result;
	}	


	 ########################################################
	# Sets the value, adjusted for little error tolerance
	function set_value($layer,$candidate,$value) {
		if(!(int) $candidate) {
			$candidate = $this->lookup[strtolower($candidate)];
		}
		Graph::set_value($layer,"d".(string)max(0,min(6,(int)$candidate)),$value);
	}

}


?>