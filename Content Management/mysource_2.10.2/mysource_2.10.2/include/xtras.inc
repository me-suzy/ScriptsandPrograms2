<?  ##############################################
   ### MySource ------------------------------###
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
## File: include/xtras.inc
## Desc: Functions for dealing with Xtras - or plugins for MySource
## $Source: /home/cvsroot/mysource/include/xtras.inc,v $
## $Revision: 2.12 $
## $Author: brobertson $
## $Date: 2004/01/05 17:15:14 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/config.inc");
#---------------------------------------------------------------------#

 ############################################################
# Scrounge among the Xtras folder and find what we can find
class XtrasRegistry extends CacheableObject {
		
	 ###############################
	# variables
	var $dir='';
	var $id=0;
	var $register = array();
	
	 ##############
	# constructor:
	function XtrasRegistry($xtras_dir='xtras', $xtras_id='mysource') {
		$this->dir = $xtras_dir;
		$this->id  = $xtras_id;

		$this->load();

	}	


	 #########################################################
	# Explore the directory, find the extras - catalogue them
	function load() {

		if($this->load_from_cache()) return $this->id;
	
		if (!file_exists($this->dir)) {
			$this->_set_error("Xtras directory ($this->dir) does not exist!",__FILE__,__LINE__);
			return false;
		}
		$this->register = $this->_load_dir($this->dir);
		$this->save_to_cache();
	}


	 #############################################
	# Clears the cache and loads all the info again
	function reload() {
		$this->clear_cache();
		$this->load();
	}

	 ########################################################
	# Recursive function that explores directories for xtras
	function _load_dir($dir='') {
		 ############################
		# Is this an Xtra?
		if(file_exists("$dir/Xtra.info")) {
			return new XtrasEntry("$dir/Xtra.info");
		} elseif (file_exists("$dir/Xtra.name")) { # Legacy system
			$entry = new XtrasEntry(basename($dir));
			$entry->name = trim(ereg_replace("[\n\r]+"," ",file_to_string("$dir/Xtra.name")));
			return $entry;
		}

		 ##############################################################
		# No, it must be another folder full of Xtras of a certain type
		if(!$d = opendir($dir)) {
			$this->_set_error("Unable to open Xtras dir: $dir",__FILE__,__LINE__);
			return false;
		}
		$result = array();
		while($f = readdir($d)) {
			if (is_dir("$dir/$f") && $f[0] != "." && $f != "CVS") {
				$result[$f] = $this->_load_dir("$dir/$f");
			}
		}
		closedir($d);

		uasort($result,array($this,'_xtraentry_compare'));
		#uksort($result,array($this,'_xtra_codename_compare'));

		return $result;
	}


	 #######################################
	# Compares two xtraentries, or arrays
	# and returns 1,0,-1 depending on which 
	# comes first alphabetically
	function _xtraentry_compare($a='',$b='') {
		if(get_class($a) == "xtrasentry" && get_class($b) == "xtrasentry") {
			return strcmp(strtolower($a->name),strtolower($b->name));
		} elseif(get_class($a) == "xtrasentry") {
			return 1;
		} elseif(get_class($b) == "xtrasentry") {
			return -1;
		} else {
			return 0;
		}
	}

	 ##############################################################
	# Put the client-specific xtras last (starting with a capital)
	function _xtra_codename_compare($a='',$b='') {
		if(ereg('^[a-z]',$a))     $c = 2;
		elseif(ereg('^[A-Z]',$a)) $c = 3;
		else $c = 1;
		if(ereg('^[a-z]',$b))     $d = 2;
		elseif(ereg('^[A-Z]',$b)) $d = 3;
		else $d = 1;
		if($c==$d) return 0;
		if($c>$d)  return 1;
		return -1;
	}

	 ###############################################################################
	# Returns a list of Xtras of a certain type, in the for of an associative array
	# $restrictions is a standard array of codenames to limit the result to
	function list_type($type='',$restrictions=Array()) {

		$dir = &$this->_type_to_array($type);
		$result = array();
		if (!is_array($dir)) $dir = array();
		if (!empty($dir)) {
			foreach($dir as $subdir => $entry) {
				if (get_class($entry) == 'xtrasentry' && (empty($restrictions) || in_array($entry->codename,$restrictions))) {
					$result[$entry->codename] = $entry->name;
				}
			}
		}
		return $result;
	}

	 #######################################################
	# Returns the name of a Xtra given a type and codename
	function name($type='',$codename='') {
		$dir = &$this->_type_to_array($type);
		return $dir[$codename]->name;
	}


	 #########################################################
	# Returns the version of a Xtra given a type and codename
	function version($type='',$codename='') {
		$dir = &$this->_type_to_array($type);
		return $dir[$codename]->version;
	}

	 #############################################################
	# Returns the description of a Xtra given a type and codename
	function description($type='',$codename='') {
		$dir = &$this->_type_to_array($type);
		return $dir[$codename]->description;
	}

	 #############################################################
	# Returns the description of a Xtra given a type and codename
	function value($type='',$codename='',$key='') {
		$dir = &$this->_type_to_array($type);
		return $dir[$codename]->extra_values[$key];
	}

	 ##############################################################################
	# Returns the codename of a Xtra IN THE CORRECT CASE given a type and codename
	function codename($type='',$codename='') {
		$dir = &$this->_type_to_array($type);
		if (!empty($dir)) {
			for(reset($dir); NULL !== ($c = key($dir)); next($dir)) {
				if (strtolower($c) == strtolower($codename)) {
					return $c;
				}
			}
		}
		return "";
	}


	 ##############################################################
	# Takes a "path" to a type and returns the array of that type
	function &_type_to_array($type='') {

		if ($type) {

			$type = $type;
			# Trim off any leading/trailing slashes.
			$type = ereg_replace("[\/]+$","",ereg_replace("^[\/]+","",$type));
			$type = explode("/",ereg_replace("\"","\\\"",$type));
			$type = implode("\"][\"",$type);
			$type = "\$this->register[\"$type\"]";
			eval("\$dir = $type;");

		} else {
			$dir = $this->register;
		}

		if (!is_array($dir)) {
			return;
		}
		return $dir;
	}

	  ##############################################
	 # Prints the backend for the xtras, 
	# assumes that it's within another (ie config's backend)
	function print_backend(&$backend) {
		if($_POST['action'] == "Refresh Xtras") {
			$this->reload();
		}
		$backend->enable_tooltips();
		?> 
		<table cellpadding=0 cellspacing=0 border=0 width=100%>
		<?
		$this->print_xtra_hierarchy($this->register, Array());
		?>
		</table>
		<input type=button value="Refresh" onclick="form.action.value='Refresh Xtras';form.submit();">
		<?

	}#end print_backend()

	  ################################################################
	 # Prints a hierarchy table of all the xtras 
	function print_xtra_hierarchy(&$array,$depth=0) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/colour/colour.inc");
		$depth[] = true; # The indenting level, stalk on/off

		for(reset($array); NULL !== ($key=key($array)); next($array)) {
			if (!next($array)) $end = true;
			else $end = false;
			prev($array);

			$current = &$array[$key];

			# if it's an xtra then we just need to print it
			if (is_object($current) && get_class($current) == "xtrasentry"){
				$is_xtra = true;
				$haskids = false;
				$requirement_results = $this->check_requirements(&$current);
				$bgcolor = ($requirement_results['satisfied']) ? "99ee99" : "ee9999";

			# if it's an array (and it has contents) then it must contain
			} elseif (is_array($current) && count($current)) {
				$is_xtra = false;
				$haskids  = true;
				$bgcolor  = "99ee99";
			# else we don't know what it is so let's go to the next one
			} else {
				continue;
			}
				
			?>
			<a name="xtra<?=$key?>">
			<tr><td <?=(($bgcolor) ? "bgcolor=#".Colour::adjust_saturate($bgcolor,-0.7) : "")?>>
			<table cellpadding=0 cellspacing=0 border=0>
				<tr>
				<?
				$maxdepth = count($depth);
				for ($i = 0; $i < $maxdepth; $i++) {
					?><td valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1))) echo "background=\"images/tree/stalk.gif\"" ?> ><?
					if ($i != count($depth) - 1) { 
						?><img src="images/blank.gif" height=1 width=20><?
					} else {
						?><img src="images/tree/branch.gif" height=20 width=20><?
					}
					?></td><?
				}#end for

				if ($is_xtra) {
					$tooltip['heading']  = $current->name;
					$tooltip['text']  = '<table border="0">'
									 .'<tr><td>Codename:</td><td>'.$current->codename.'</td></tr>'
									 .'<tr><td>Version:</td><td>'.$current->version.'</td></tr>';
					if ($current->description) {
						$tooltip['text'] .= '<tr><td valign="top">Description:</td><td>'.$current->description.'</td></tr>';
					}
					if ($current->requirements) {
						$tooltip['text'] .= '<tr><td colspan="2">Requirements:</td></tr>'
										 .'<tr><td colspan="2" bgcolor="#000000">'
										 .'<table border="0" width="100%" cellspacing="1" cellpadding="2">'
										 .'<tr><td bgcolor="#ffffff">Requires</td><td bgcolor="#ffffff" align="center">Reqd Version</td><td bgcolor="#ffffff" align="center">Avail Version</td></tr>';
						foreach($requirement_results['results'] as $requires => $data) {
							$bgcolor =  'bgcolor="'.(($data['satisfied']) ? '99ee99' : 'ee9999').'"';
							$tooltip['text'] .= '<tr>'
											 .'<td '.$bgcolor.'>'.$requires.'</td>'
											 .'<td '.$bgcolor.' align="right">'.$current->requirements[$requires].'</td>'
											 .'<td '.$bgcolor.' align="right">'.$data['version'].'</td>'
											 .'</tr>';
						}#end foreach

						$tooltip['text'] .= '</table>'
										 .'</td></tr>';

					}#end if requirements
					$tooltip['text'] .= '</table>';
					foreach($tooltip as $key => $val) $tooltip[$key] = ereg_replace("[\n\r]+", " ",addslashes(str_replace("\"", "&quot;", $val)));
					?><td class=backend_data>&nbsp;<a href="#" onmouseover="javascript: show_tooltip(event,'<?=$tooltip['heading']?>', '<?=$tooltip['text']?>',400); return true;" onmouseout="javascript: hide_tooltip(); return true;"><?=$current->name?></a></td><?
					/*
					?><td class=backend_data>&nbsp;<a href="#" onmouseover="window.status='<?=ereg_replace("[\n\r]+"," ",ereg_replace("'","\\'",$current->description))?>'; return true;" onmouseout="window.status=''; return true;"><?=$current->name?> (<?=$current->codename?>)</a></td><?
					*/
				} else {
					?><td class=backend_data>&nbsp;<?=ucwords(str_replace("_", " ", $key))?></td><?
				}#endif
				?> 
				</tr>
			</table>
			</td></tr>
			<?
			if ($haskids) {
				if ($end) $depth[count($depth)-1] = false;
				$this->print_xtra_hierarchy($current, $depth);
			}
		}

	}#end print_xtra_hierarchy()

	 #############################################################
	# Checks that the passed xtra's requirements are satisfied
	function check_requirements(&$xtra_entry) {
		
		$results = Array();
		if (!empty($xtra_entry->requirements)) {
			foreach($xtra_entry->requirements as $requires => $reqd_version) {
				
				switch(strtolower($requires)) {

					case "mysource" :
						$available_version = MYSOURCE_VERSION;
					break;

					case "php" :
						$available_version = phpversion();
					break;

					# must require some other extra
					default :
						$temp = explode("/", $requires);
						$codename = array_pop($temp);
						$type = implode("/", $temp);

						$available_version = $this->version($type, $codename);

				}#end switch

				$results[$requires] = array("satisfied" => (version_no_compare($reqd_version, $available_version) <= 0),
											 "version"   => $available_version
											);
			}#end foreach
		}
		$satisfied = true;
		foreach($results as $data) $satisfied &= $data['satisfied'];

		return array("satisfied" => $satisfied, "results" => $results);

	}#end check_requirements()

}#end class XtrasRegistry



 ##############################################################
# This class defines an Xtra registry ENTRY, not an xtra itself
class XtrasEntry extends Config { # Uses the config classes file-reading functions
	var $codename;    # Unique amoung is Xtra type, this is a short name [a-zA-z_]+
	var $name;        # Longer descriptive name
	var $version = "0.0";  # The version number
	var $requirements;
	var $description; # etc
	var $extra_values;

	function XtrasEntry($codename='') { # Codename can be THE codename or the path to an info file.
		if(strstr($codename,"/")) { # Must be a filename for the infor file
			$this->codename = basename(dirname($codename));
			$this->name = $this->codename;
			$this->read_info_file($codename);
		} else {
			$this->codename = $codename;
			$this->name = $codename;
		}
	}

	 ##########################################################################
	# Reads in the information about an Xtra fiven a filename to its info file
	function read_info_file($filename='') {
		while($line = $this->get_conf_line($filename)) {
			$colon = strcspn($line,":");
			$key = trim(strtolower(substr($line,0,$colon)));
			$val = trim(substr($line,$colon+1));
			switch($key) {
			case "name":
				$this->name = $val;
				break;
			case "version":
				$this->version = $val;
				break;
			case "description":
				$this->description = $val;
				break;
			case "requires":
				list($xtra,$version) = split("[ \t]+",$val);
				$xtra = trim($xtra); $version = trim($version);
				if(!$version) $version = "0.0";
				$this->requirements[$xtra] = $version;
				break;
			default:
				$this->extra_values[$key] = $val;
				break;
			}
		}
		unset($this->conf_parser_files);
	}
}

?>
