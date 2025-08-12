<?  ##############################################
   ### SquizLib ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## File: csv/csv.inc
## Desc: A class that manages csv files.
## $Source: /home/cvsroot/squizlib/csv/csv.inc,v $
## $Revision: 2.8 $
## $Author: mbrydon $
## $Date: 2004/03/01 03:31:41 $
#######################################################################
# Requires object from squizlib
include_once("$SQUIZLIB_PATH/object/object.inc");
#---------------------------------------------------------------------#

class Csv extends Object {
	
	 #####################
	# Variables
	var $filepath='';
	var $dir='';
	var $filename='';
	var $deliminator = ',';
	var $field_headers=false;
	var $values='';

	 ##############
	# constructor:
	function CSV($filepath='') {
		if($filepath) $this->set_filepath($filepath);
	}

	 ####################################
	# Takes an array of fields names 
	function set_field_headers(&$headers) {
		$this->field_headers = &$headers;
	}

	 ##############################################################
	# Takes a multi-dimensional array and stores it as values
	function set_values(&$values) {
		$this->values = &$values;
	}

	 ######################################
	# Set the deliminator
	function set_deliminator($deliminator='') {
		$this->deliminator = $deliminator;
	}
	
	 ##################################
	# Set the filename of this object
	function set_filename($filename='') {
		$this->filename = $filename;
	}

	 ##################################
	# Set the file of this object
	function set_filepath($filepath='') {
		if($filepath) {
			# Full path to this file
			$this->filepath = $filepath;

			# Directory of this file
			$this->dir = dirname($filepath);
			if (!file_exists($this->dir)) mkdir($this->dir,0775);
			
			# Name of file without full path
			$this->set_filename(basename($filepath));
		
			if (!file_exists($this->dir)) {
				$this->_set_error("csv directory ($this->dir) does not exist!",__FILE__,__LINE__);
				return false;
			}
		}
	}


	 ####################################################################
	# Set the PHP mime header, this will overwrite any previous headers
	function set_header($header='') {
		if($header) {
			# Set custom headers
			header($header);
		} else {
			header("Content-Type: application/csv");
			header("Content-Disposition: filename=\"".$this->filename."\"");
			header("Pragma: public");
		}

	}

	 #########################################################
	# Export the current values to the current set filepath
	# If no filepath then export to screen
	function export() {
		if(!$this->filepath) {
			# No file to write to.....lets send the header and then the file
			$this->set_header();
		} else {
			if(!$fp = fopen($this->filepath,"w")) {
				$this->_set_error("Unable to open file $this->filepath for writing.",__FILE__,__LINE__);
				return "";
			}
		}
		if($this->field_headers) {
			$line = $this->format_line($this->field_headers,$this->deliminator);
			if($fp) fputs($fp, $line."\n");
			else echo $line."\n";
		}

		foreach($this->values as $value) {
			$line = $this->format_line(&$value,$this->deliminator);
			if($fp) fputs($fp, $line."\n");
			else echo $line."\n";
		}
	}

	 ############################################
	# Import the current file to this->values
	function import($start_at=1, $lines=0) {
		# Can only import from a file
		if(!$this->filepath) return '';
		if(!$fp = fopen($this->filepath,'r')) {
			$this->_set_error("Unable to open file $this->filepath for reading.",__FILE__,__LINE__);
			return "";
		}

		$line_count  = 0;
		$lines_done  = 0;

		while(($tmp = fgetcsv($fp,65535,$this->deliminator)) != NULL) {
			$line_count++;
			if ($line_count >= $start_at) {
				$this->values[] = $tmp; 
				$lines_done++;
				if ($lines && ($lines_done >= $lines)) break;
			}
		
		}
		fclose($fp);
	}

	 #################################################
	# Formats a CSV line
	function format_line (&$array, $deliminator=',') {
		$line = '';
		foreach($array as $val) {
			if(is_array($val)) {
				$line .= '"'.$this->format_line(&$val,$deliminator).'"'.$deliminator;
			} else {
				if(ereg("[$deliminator\"\n\r]", $val)) {
					$val = '"'.str_replace('"', '""', $val).'"';
				}
				$line .= $val.$deliminator;
			}
		}

		# strip the last deliminator 
		return $line = substr($line, 0, (strlen($deliminator) * -1));
	}

}
?>
