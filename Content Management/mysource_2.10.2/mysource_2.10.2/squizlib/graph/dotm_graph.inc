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
## File: graph/dotm_graph.inc
## Desc: Class for generating graphs based on the days of the month - just a commonly-used shortcut
## $Source: /home/cvsroot/squizlib/graph/dotm_graph.inc,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:05 $
#######################################################################
# Requires the graph superclass
include_once(dirname(__FILE__)."/graph.inc");

#---------------------------------------------------------------------#

class Dotm_Graph extends Graph {
	
	var $month;

	  #############
	 # Constructor 
	function Dotm_Graph($type, $title, $subtitle,$preferred_width,$month) {
		$result = Graph::Graph($type, $title, $subtitle,$preferred_width);
		$this->set_subject("Day of the Month");
		if($month) $this->set_month($month);
		return $result;
	}	

	 ##############################################
	# Takes the month in question in "Y-m" format.
	function set_month($month) {
		list($y,$m) = explode("-",$month);
		$y = sprintf("%04d",max(0,$y));
		$m = sprintf("%02d",min(12,max(0,$m)));
		$this->month = "$y-m";

		for($d = 1; $d <= 31; $d++) {
			$stamp = mktime(0,0,0,$m,$d,$y);
			if($m != date("m",$stamp)) break; # This month doesn't have this day !
			$label = date("D jS",$stamp);
			$this->add_candidate($d,$label);
		}

		return $this->month;
	}

	 ########################################################
	# Sets the value, adjusted for little error tolerance
	function set_value($layer,$candidate,$value) {
		Graph::set_value($layer,max(1,min(count($this->candidates),(int)$candidate)),$value);
	}

}


?>