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
## Desc: Class for generating graphs based on the months of the year - just a commonly-used shortcut
## $Source: /home/cvsroot/squizlib/graph/moty_graph.inc,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:05 $
#######################################################################
# Requires the graph superclass
include_once(dirname(__FILE__)."/graph.inc");

#---------------------------------------------------------------------#

class Moty_Graph extends Graph {

	var $month_lookup = array();

	  #############
	 # Constructor 
	function Moty_Graph($type, $title, $subtitle,$preferred_width) {
		$result = Graph::Graph($type, $title, $subtitle,$preferred_width);
		$this->set_subject("Month of the Year");
		for($m = 1; $m <= 12; $m++) {
			$stamp = mktime(0,0,0,$m,1,2000);
			$this->month_lookup[strtolower(date("M",$stamp))] = $m;
			$this->month_lookup[strtolower(date("F",$stamp))] = $m;
			$this->add_candidate($m,date("M",$stamp));
		}
		return $result;
	}	


	 ########################################################
	# Sets the value, adjusted for little error tolerance
	function set_value($layer,$candidate,$value) {
		if(!(int) $candidate) {
			$candidate = $this->month_lookup[strtolower($candidate)];
		}
		Graph::set_value($layer,max(1,min(12,(int)$candidate)),$value);
	}

}


?>