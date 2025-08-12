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
## File: include/parameter.inc
## Desc: A class which reads in a uses MySource parameter files - a quick backend dev tool
## $Source: /home/cvsroot/mysource/include/parameter_set.inc,v $
## $Revision: 2.62.2.3 $
## $Author: tbarrett $
## $Date: 2004/05/12 23:07:01 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/config.inc");
#---------------------------------------------------------------------#

class Parameter_Set extends Config {

	var $screens=array(); # Screens split into sections, split into parameters, split into settings
	var $__settings=''; # Set-wide settings
	var $values; # A reference to an array where we can get default values from
	var $caller; # A reference to the object using this
	var $encapsulate=array(); # An array name to encapsulate all the options is
					  # so that the same parameter set can be used for different
					  # entities on the same screen

	function Parameter_Set($id='',$conf_file='',&$values,&$caller,&$encapsulate) {
		$this->values = &$values;
		$this->caller = &$caller;
		$result = Config::Config($id,$conf_file);
		$this->encapsulate = $encapsulate;
		return $result;
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Config::__sleep();
		array_remove_element('values',$result);
		array_remove_element('caller',$result);
		array_remove_element('encapsulate',$result);
		return $result;
	}

	 #########
	# Wake up 
	function __wakeup() {
	}

	  ##################################
	 # Load all the stuff from the file
	function load(&$values) {
		  ##########################
		 # Can we load from cache ?
		$tmp_values = &$this->values; # Preserve this
		$tmp_caller = &$this->caller; # Preserve this
		if (is_file($this->conf_path . "/" . $this->conf_file)) {
			if (filemtime("$this->conf_path/$this->conf_file") < $this->get_cache_timestamp()
				&& $this->load_from_cache()) {
				$this->values = &$tmp_values;
				$this->caller = &$tmp_caller;
				return $this->id;
			}
		}

		# Otherwise, lets get started
		$screen  = '';
		$section = '';
		$param   = '';
		$setting = '';
		$data    = '';

		$killme = -1;

		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {

			if (ereg("^[ \t]*\^[^\n]*\^[ \t]*$",$line)) { # New Screen defined
				$screen  = ereg_replace("^[ \t]*\^|\^[ \t]*$",'',$line);
				$section = ''; $param = ''; $setting = ''; $data = ''; $dest = &$killme;
			} elseif (ereg("^[ \t]*\[[^\n]*\][ \t]*$",$line)) { # New Section defined
				$section = ereg_replace("^[ \t]*\[|\][ \t]*$",'',$line);
				$param = ''; $setting = ''; $data = ''; $dest = &$killme;
			} elseif (ereg("^[ \t]*\([^\n]*\)[ \t]*$",$line)) { # New Parameter defined
				$param   = ereg_replace("^[ \t]*\(|\)[ \t]*$",'',$line);
				$setting = ''; $data = ''; $dest = &$killme;
			} elseif (ereg("^([a-zA-Z][a-zA-Z0-9_\-]*)[ ]?:([^\n]*)$",$line)) { # New Setting defined
				$setting = strtolower(ereg_replace("^([a-zA-Z][a-zA-Z0-9_\-]*)[ ]?:([^\n]*)$","\\1",$line));
				$data    = ereg_replace("^([a-zA-Z][a-zA-Z0-9_\-]*)[ ]?:([^\n]*)$","\\2",$line);
				if($data[0] == '+') {
					$op = 'concat'; # Concatenate this to any existing data
					$data = substr($data,1);
				} elseif($data[0] == '-') {
					$op = 'precat'; # Concatenate this to any existing data
					$data = substr($data,1);
				} elseif($data[0] == '*') {
					$op = 'replace'; # Replace an existing data with this
					$data = substr($data,1);
				} else {
					$op = ''; # Append this to an array of stringz
				}
				$data = ltrim($data);
				if($param) {
					if(!isset($this->screens[$screen][$section][$param]['__settings']))
						$this->screens[$screen][$section][$param]['__settings'] = array();
					$dest = &$this->screens[$screen][$section][$param]['__settings'];
				} elseif($section) { # Setting applies to a section
					if(!isset($this->screens[$screen][$section]['__settings']))
						$this->screens[$screen][$section]['__settings'] = array();
					$dest = &$this->screens[$screen][$section]['__settings'];
				} elseif($screen) { # Setting applies to a screen
					if(!isset($this->screens[$screen]['__settings']))
						$this->screens[$screen]['__settings'] = array();
					$dest = &$this->screens[$screen]['__settings'];
				} else $dest = &$this->__settings;

				if($dest != -1) {
					if(isset($dest[$setting]) && !$op) {
						if(is_array($dest[$setting])) {
							$dest[$setting][] = '';
							$dest = &$dest[$setting][count($dest[$setting])-1];
						} else {
							$dest[$setting] = array($dest[$setting]);
							$dest = &$dest[$setting][1];
						}
					} else {
						if($op == 'concat') $data = $dest[$setting] . $data;
						if($op == 'precat') $data .= $dest[$setting];
						$dest[$setting] = '';
						$dest = &$dest[$setting];
					}
				}
			} else {
				$data = &$line;
			}
			if($setting && $dest != -1 && strlen($data)) { # We have a setting and something to apply this setting to
				if(isset($dest) && strlen($dest) > 1) {
					$dest .= "\n$data";
				} else {
					$dest = $data;
				}
			}

		}

		 ####################
		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing parameter set file:</b><br><br>$parse_error_log");
			exit();
		}
		
		# Write the cache
		$this->save_to_cache($this->id);

		return $this->id;
	}

	 ############################################
	# Handy functions (no description necessary)
	function &get_setting($setting='') {
		$this->process_meta_settings($this->__settings);
		if($setting) {
			return $this->__settings[$setting];
		}
		return $this->__settings;
	}
	function get_screen_names() {
		return array_keys($this->screens);
	}
	function &get_screen_info($screen='',$setting='') {
		$this->process_meta_settings($this->screens[$screen]['__settings']);
		if($setting) {
			return $this->screens[$screen]['__settings'][$setting];
		}
		return $this->screens[$screen]['__settings'];
	}
	function &get_sections($screen='') {
		return $this->screens[$screen];
	}
	function get_section_names($screen='') {
		$result = array_keys($this->screens[$screen]);
		array_remove_element('__settings', $result);
		return $result;
	}
	function &get_parameters($screen='',$section='') {
		return $this->screens[$screen][$section];
	}
	function get_parameter_names($screen='',$section='') {
		$result = array_keys($this->screens[$screen][$section]);
		array_remove_element('__settings', $result);
		return $result;
	}
	function &get_section_info($screen='',$section='',$setting='') {
		$this->process_meta_settings($this->screens[$screen][$section]['__settings']);
		if($setting) {
			return $this->screens[$screen][$section]['__settings'][$setting];
		}
		return $this->screens[$screen][$section]['__settings'];
	}
	function &get_parameter_info($screen='',$section='',$parameter='',$setting='') {
		$this->process_meta_settings($this->screens[$screen][$section][$parameter]['__settings']);
		if($setting) {
			return $this->screens[$screen][$section][$parameter]['__settings'][$setting];
		}
		return $this->screens[$screen][$section][$parameter]['__settings'];
	}


	 ######################################################
	# Given an array of settings, processes any meta ones.
	function process_meta_settings(&$settings) {
		if(!isset($settings['meta'])) return;
		if (!empty($settings['meta'])) {
			foreach($settings['meta'] as $item) {
				list($k,$v) = $this->split_option($item);
				if(method_exists($this->caller,$v)) {
					$val = &$this->caller->$v();
				} else {
					$val = &$this->caller->$v;
				}
				if(isset($settings[$k])) {
					if(is_array($settings[$k])) {
						$settings[$k][] = &$val;
					} else {
						$settings[$k] = array($settings[$k], $val);
					}
				} else {
					$settings[$k] = &$val;
				}
			}
			unset($settings['meta']);
		}
	}


	 ###################################################################
	# These functions verify that a certain screen, section or parameter
	# are allowed to be seen by the current user
	function check_screen_show_if($screen='') {
		$a = $this->get_screen_info($screen,'show_if');
		if(!is_array($a)) $b[] = $a;
		else $b = $a;
		foreach($b as $c) {
			$c = trim($c);
			if (is_object($this->caller) && method_exists($this->caller,$c)) {
				if(!$this->caller->$c()) return false;
			}
		}
		return true; # Yeah, display it
	}

	function check_section_show_if($screen='',$section='') {
		$a = $this->get_section_info($screen,$section,'show_if');
		if(!is_array($a)) $b[] = $a;
		else $b = $a;
		foreach($b as $c) {
			$c = trim($c);
			$this->split_function_string($c, $main_function, $inside_extention);
			if (is_object($this->caller) && method_exists($this->caller, $main_function)) {
				eval("\$value = \$this->caller->$main_function($inside_extention);");
				if(!$value) return false;
			}
		}
		return true; # Yeah, display it
	}

	function check_parameter_show_if($screen='',$section='',$parameter='') {
		$a = $this->get_parameter_info($screen,$section,$parameter,'show_if');
		if(!is_array($a)) $b[] = $a;
		else $b = $a;
		foreach($b as $c) {
			$c = trim($c);
			$this->split_function_string($c, $main_function, $inside_extention);
			if (is_object($this->caller) && method_exists($this->caller, $main_function)) {
				eval("\$value = \$this->caller->$main_function($inside_extention);");
				if(!$value) return false;
			}
		}
		return true; # Yeah, display it
	}
	 ################################################################
	# Like the above three functions but applies to write acces only
	function check_screen_change_if($screen='') {
		if($c = trim($this->get_screen_info($screen, 'change_if'))) {
			if (method_exists($this->caller,$c)) return $this->caller->$c();
		} return true; # Yeah, edit it
	}
	function check_section_change_if($screen='',$section='') {
		if(!$this->check_screen_change_if($screen)) return false;
		if($c = trim($this->get_section_info($screen,$section, 'change_if'))) {
			if (method_exists($this->caller,$c)) return $this->caller->$c();
		} return true; # Yeah, edit it
	}
	function check_parameter_change_if($screen='',$section='',$parameter='') {
		if(!$this->check_section_change_if($screen,$section)) return false;
		if($c = trim($this->get_parameter_info($screen,$section,$parameter, 'change_if'))) {
			if (method_exists($this->caller,$c)) {
				return $this->caller->$c();
			}
		}
		return true; # Yeah, edit it
	}

	 ########################################################
	# Given a backend object, a code prefix and a href prefic
	# sets all the tabs for a multi-screened parameter set
	function set_tabs(&$backend,$code_prefix='',$href_prefix='') {
		if(!ereg('\?',$href_prefix)) {
			$href_prefix .= '?';
		} else {
			$href_prefix .= '&';
		}
		$screen_no = 1;
		$parameter_screen = $_GET['PARAMETER_SCREEN'];
		foreach($this->get_screen_names() as $screen_name) {
			$icon = $this->get_screen_info($screen_name, 'icon');
			if(!$icon) $icon = 'page';
			if(!$screen_name) continue;
			if(!$this->check_screen_show_if($screen_name)) continue;
			$backend->set_tab("$code_prefix$screen_no", "$href_prefix&PARAMETER_SCREEN=".urlencode($screen_name),$screen_name,$this->get_screen_info($screen_name,"description"),$icon);
			if($parameter_screen == $screen_name) {
				$backend->set_active_tab("$code_prefix$screen_no");
			}
			$screen_no++;
		}
	}

	 #######################################
	# Processes a commited parameter screen
	function process(&$backend) {
		$parameter_screen = $_GET['PARAMETER_SCREEN'];
		if(in_array($parameter_screen,$this->get_screen_names())) {
			if(!$this->check_screen_show_if($parameter_screen)) return 0;
			if($_POST['action'] == 'Commit') {
				$this->process_screen($backend, $parameter_screen);
				return 1;
			}
		}
		return 0;
	}

	 ##########################################
	# Prints the backend of this parameter set
	function print_backend(&$backend,$embedded=false) {
		$parameter_screen = $_GET['PARAMETER_SCREEN'];
		if(in_array($parameter_screen,$this->get_screen_names())) {
			if(!$this->check_screen_show_if($parameter_screen)) return 0;
			if(!$embedded) {
				$backend->set_hidden_field('PARAMETER_SCREEN', $parameter_screen);
				$icon = $this->get_screen_info($parameter_screen, 'icon');
				$backend->set_subheading($parameter_screen,$icon);
				$this->print_screen($backend,$parameter_screen);
			} else {
				$this->print_screen($backend,$parameter_screen,1);
			}
			return 1;
		}
		return 0;
	}

	 #######################################################################
	# Prints a summary of the parameters with links to the various sections
	function print_summary($href_prefix='') {
		if(!ereg('\?',$href_prefix)) {
			$href_prefix .= '?';
		} else {
			$href_prefix .= '&';
		}
		?><table width="100%" cellpadding=2 cellspacing=0 border=0><?
		$screen_count  = 0;
		$section_count = 0;
		$param_count   = 0;
		foreach($this->get_screen_names() as $screen) {
			#if(!$this->check_screen_show_if($screen)) continue;
			if($screen) $screen_href = "$href_prefix&PARAMETER_SCREEN=".urlencode($screen);
			else        $screen_href = $href_prefix;
			if($screen_count++ % 2 == 0) {
				?><tr><?;
			}
			?><td valign=top><?
			if($screen) {
				?><div align=center><a class=backend_subheading href="<?=$screen_href?>"><?=$screen?></a></div><?
			}
			$section_names = $this->get_section_names($screen);
			if (!empty($section_names)) {
				foreach($section_names as $section) {
					#if(!$this->check_section_show_if($screen,$section)) continue;
					$section_href = "$screen_href#PSET_SEC_".ereg_replace("[^a-zA-Z0-9_]","_",$section);
					if($section) {
						?><br><a class=backend_field href="<?=$section_href?>"><?=$section?></a><?
					}
					$pnames = $this->get_parameter_names($screen,$section);
					if (!empty($pnames)) {
						foreach($pnames as $parameter) {
							if(!$this->check_parameter_show_if($screen,$section,$parameter)) continue;
							$parameter_href = $section_href."_PARAM_".ereg_replace("[^a-zA-Z0-9_]","_",$parameter);
							if(trim($parameter)) {
								?><li> <a href="<?=$parameter_href?>"><?=$parameter?></a></li><?
							}
						}
					}
					?><br><?
				}
			}
		}
		?></table><?
	}

	 ###########################################################
	# Prints an entire screen out, havign been passed a backend
	function print_screen(&$backend,$screen='',$embedded=false) {
		if(!$embedded) {
			$backend->print_header();
		}
		$print_commit = true;

		$info     = &$this->get_screen_info($screen);

		if($info['note']) {
			$backend->open_section('Note');
			$backend->open_field();
			echo $info['note'];
		}

		if($info['print']) {
			if($this->caller && is_object($this->caller) && method_exists($this->caller,$info['print'])) {
				$print_commit = $this->caller->$info['print']();
			} else {
				echo("<div class=backend_warning>Cannot find '$info[print]' method in caller object.</div>");
			}
		} else {
			$sections = &$this->get_sections($screen);
			foreach(array_keys($sections) as $section) {
				if(substr($section,0,2) == '__') continue;
				if(!$this->check_section_show_if($screen,$section)) continue;
				$this->print_section($backend,$screen,$section);
			}
		}

		if(!$embedded) {
			if ($print_commit) $backend->print_commit_button('Commit', "document.edit.action.value='Commit';document.edit.submit();");
			$backend->print_footer();
		}
	}

	 ##################
	# Prints a section
	function print_section(&$backend,$screen='',$section='') {
		$info     = &$this->get_section_info($screen,$section);
		if($section) {
			$backend->open_section($section,$info['icon'],'PSET_SEC_'.preg_replace("/[^a-zA-Z0-9_]/",'_',$section));
		}

		$info     = &$this->get_section_info($screen,$section);
		if($info['note']) {
			$backend->open_field('Note', 'top');
			echo $info['note'];
		}

		if($info['print']) {
			if($this->caller && is_object($this->caller) && method_exists($this->caller,$info['print'])) {
				$this->caller->$info['print']();
			} else {
				echo"<div class=cwarning>Cannot find '$info[print]' method in caller object.</div>";
			}
		} else {
			$parameters = &$this->get_parameters($screen,$section);
			foreach(array_keys($parameters) as $parameter) {
				if(substr($parameter,0,2) == '__') continue;
				if(!$this->check_parameter_show_if($screen,$section,$parameter)) continue;
				$this->print_parameter($backend,$screen,$section,$parameter);
			}
		}
	}

	 ####################
	# Prints a parameter
	function print_parameter(&$backend,$screen='',$section='',$parameter='') {
		$settings = &$this->get_parameter_info($screen,$section,$parameter);
		# Special parameters
		if($settings['type'] == '_load_parameters') {
			$backend->open_field($parameter,'top');
			return $this->print_load_interface($backend);
		} elseif($settings['type'] == '_save_parameters') {
			$backend->open_field($parameter,'top');
			return $this->print_save_interface($backend);
		}
		
		# Work out a prefix to the variable name. This is to allow encapsulation
		# in variables
		if($code = $settings['code']) {
			$code = $this->encapsulation_string($screen,$section,$parameter,$code);
			if(isset($settings['get_function'])) {
				$this->split_function_string($settings['get_function'], $main_function, $inside_extention);
				if(method_exists($this->caller, $main_function)) {
					eval("\$value = &\$this->caller->$main_function($inside_extention);");
				} elseif(isset($this->caller->$settings['get_function'])) {
					$value = &$this->caller->$settings['get_function'];
				}
			} elseif(is_object($this->values)) {
				eval("\$value = &\$this->values->$code;");
			} elseif(is_array($this->values)) {
				$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
				eval("\$value = &\$this->values$tmp_code;");
			}
			# Now, we don't really want a big multi-dimentional array coming back
			# Bodycopies and forms don't like them (at the moment). So we're going to 
			# fake it.
			$code = str_replace('[','__OP__',$code);
			$code = str_replace(']','__CL__',$code);
		}

		$anchor = 'PSET_SEC_'.preg_replace("/[^a-zA-Z0-9_]/",'_',$section).'_PARAM_'.preg_replace("/[^a-zA-Z0-9_]/",'_',$parameter);

		 #############################################################
		# Does the current user have write access to this parameter?
		$wa = $this->check_parameter_change_if($screen,$section,$parameter);

		switch($settings['type']) {
			case 'comment': # A special one just for printing out information
				$backend->open_field($parameter,$settings['format'],$anchor);
				if($settings['text']) {
					echo($settings['text']);
				}
				break;
			case 'function': # A special one for calling a function to print the field
				# We'll let the functions themselves handle write-access issues.
				if($settings['print']) {
					$backend->open_field($parameter,$settings['format'],$anchor);
					if($this->caller && is_object($this->caller)) {
						$this->split_function_string($settings['print'], $main_function, $inside_extention);
						if (method_exists($this->caller, $main_function)) {
							eval("\$this->caller->$main_function($inside_extention);");
						}
					} else {
						echo "<div class=backend_smallprintwarning>Cannot find '$main_function' method in caller object.</div>";
					}
				} # Else do nothing
				break;
			case 'int':
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				if($wa) { 
					if($settings['max']) {
						$l = strlen((int)$settings['max']) + 1;
					} else $l = 22; # (Maximum length of an signed decimal integer on a 64-bit system :P)
					echo text_box($code,$value,$l,$l,'class=backend_data');
				} else {
					echo $value;
				}
				if($settings['note']) {
					?><span class=backend_smallprint><br><?=$settings['note']?></span><?
				}
				break;
			case 'real':
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				if($wa) {
					$l = 30;
					echo text_box($code,$value,$l,$l,'class=backend_data');
				} else {
					echo $value;
				}
				if($settings['note']) {
					?><span class=backend_smallprint><br><?=$settings['note']?></span><?
				}
				break;
			case 'text':
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				if($wa) {
					if($settings['height'] > 1) {
						echo text_area($code,$value,$settings['width'],$settings['height'],$settings['maxlength'],"class=backend_data $settings[extras]");
					} else {
						$password = in_array(strtolower($settings['password']),array(1,'y','yes','on'));
						$fn = (($password)?'password_box':'text_box');
						echo $fn($code,$value,$settings['width'],$settings['maxlength'],"class=backend_data $settings[extras]");
					}
				} else {
					echo nl2br($value);
				}
				if($settings['note']) {
					?><span class=backend_smallprint><br><?=$settings['note']?></span><?
				}
				break;
			case 'country':
			case 'permission':
			case 'option':
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				if($settings['type'] == 'permission') {
					$group_names = $this->get_access_group_names();
					$groups_only = in_array(strtolower($settings['groups_only']),array(1,'y','yes','on'));
					if($groups_only) {
						unset($group_names['P']);
						unset($group_names['L']);
					}
					if (!empty($group_names)) {
						foreach($group_names as $id => $name) {
							if($id == (int) $id && $id > 0) {
								$group_names[$id] = "<a href=\"access_group.php?groupid=$id\">$name</a>";
							}
						}
					}
					if($settings['option']) {
						$options = $group_names + $this->make_options_array($settings['option']);
					} else {
						$options = &$group_names;
					}
				} else if($settings['type'] == 'country') {
					$countries_config = &get_config('Countries');
					$options = $countries_config->countries;
				} else {
					$options = $this->make_options_array($settings['option']);
				}
				if($settings['option_function']) {
					$this->split_function_string($settings['option_function'], $main_function, $inside_extention);
					if(
						$this->caller &&
						is_object($this->caller) &&
						method_exists($this->caller, $main_function)
					) {
						eval("\$more_options = \$this->caller->$main_function($inside_extention);");
						if (is_array($more_options)) {
							$options += $more_options;
						}
					}
				}
				$permits = $this->make_options_array($settings['permit']);
				if($settings['permit_function']) {
					if(
						$this->caller &&
						is_object($this->caller) &&
						method_exists($this->caller,$settings['permit_function']) &&
						is_array($more_permits = $this->caller->$settings['permit_function']())
					) {
						$permits += $more_permits;
					}
				}
				if(!empty($permits)) { # An 2_d array of permissions
					$codes = array();
					$code_vals = array();
					foreach($permits as $permit => $label) {
						if(!is_array($value)) $value = array($permit=>$value);
						$codes[$code."[$permit]"] = $label;
						$code_vals[$code."[$permit]"] = &$value[$permit];
					}
				} else {
					$codes     = array($code => '');
					$code_vals = array(); $code_vals[$code] = &$value;
				}
				$single = in_array(strtolower($settings['single']),array(1,'y','yes','on'));
				$list   = in_array(strtolower($settings['list']),array(1,'y','yes','on'));
				if($list) {
					if (!empty($codes)) {
						foreach($codes as $code => $label) {
							if($label) {
								if($space_labels) {
									echo '<br>';
								}
								$space_labels = 1;
								?><b><?=$label?></b><br><?
							}
							if($single) {
								if($wa) {
									echo combo_box($code,$options,$code_vals[$code],$settings['extras'],$settings['width']).'<br>';
								} else {
									echo $options[$value].'<br>';
								}
							} else {
								if($wa) {
									echo multiple_combo_box($code,$options,$code_vals[$code],$settings['extras'],$settings['width']).'<br>';
								} else {
									foreach($value as $valuette) {
										echo($options[$valuette].'<br>');
									}
								}
							}
						}
					}
				} else { # Radio/checkboxen
					foreach(array_keys($codes) as $code) echo '<input type=hidden name="'.$code.'" value="">';
					echo checkbox_array('',$codes,$options,$code_vals,$single, $wa);
				}
				if($settings['note']) {
					?><span class=backend_smallprint><?=$settings['note']?></span><?
				}
				break;
			case 'datetime':
				$backend->open_field($parameter,$settings['format'],$anchor);
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/datetime_field/datetime_field.inc");
				$show = array();
				for($c=0;$ch=$settings['show'][$c];$c++) $show[] = $ch;
				$settings['show'] = $show;
				$style = array();
				if(is_array($settings['style'])) {
					foreach($settings['style'] as $s) {
						list($k,$v) = split("[ \t]+",trim($s));
						$style[$k] = $v;
					}
				} else {
					list($k,$v) = split("[ \t]+",trim($settings['style']));
					$style[$k] = $v;
				}
				$settings['style'] = $style;
				for($c=0;$ch=$settings['null'][$c];$c++) $null[] = $ch;
				$settings['null'] = $null;
				$d = new datetime_field($code,$value,$settings);
				$d->print_field();
				break;
			case 'user_list': # A list of users, stored as an array of userids
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				$users_system = &get_users_system();
				if($wa) {
					$users_system->display_users_table($value, $code.'[delete_userids]','Remove?','page_counter_'.$code);
					?><br><b>Add:</b><?
					if(!isset($settings['height'])) $settings['height'] = 3;
					if($settings['height'] > 1) {
						echo '<br>'.text_area($code.'[add_search]','',40,$settings['height'],3000,'class=backend_data');
					} else {
						echo text_box($code.'[add_search]','',35,3000,'class=backend_data');
					}
				} else {
					$users_system->display_users_table($value);
				}
				if($settings['note']) {
					echo("<span class=backend_smallprint><br>$settings[note]</span>");
				}
				break;
			case "org_list": # A list of orgs, stored as an array of organisationids
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo 'No code supplied for this parameter.';
					return;
				}
				$users_system = &get_users_system();
				$orgs = &$users_system->organisation_array_with_sticks();
				$orgs = array('') + $orgs;
				if($wa) {
					$users_system->display_orgs_table($value, $code.'[delete_orgids]','Remove?');
					?><br><b>Add:</b><?
					if(empty($orgs)) {
						echo 'No Organisations.';	
					} else {
						echo combo_box($code.'[add_search]',$orgs);
					}
				} else {
					$users_system->display_orgs_table($value);
				}
				if($settings['note']) {
					echo("<span class=backend_smallprint><br>$settings[note]</span>");
				}				
				break;
			case "bodycopy": # To be used with caution
				$backend->open_field($parameter,$settings['format'],$anchor);
				if(!$code) {
					echo("No code supplied for this parameter.");
					return;
				}
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
				# This type ASSUMES this is being used on a page template - for now. If not, TROUBLE
				$web_system = &get_web_system();
				$page = &$web_system->get_page();
				if(!$page->id) {
					echo("Unable to determine the current page for 'bodycopy' parameter.");
					return;
				}
				$bodycopy = new BodyCopy($value, $code);
				if($wa) {
					$site = &$web_system->get_site($page->siteid);
					$site_design = &$page->get_design();
					$special_styles = $site_design->get_var("bodycopy_styles");
					$special_styles = $special_styles['options'];
					$template = &$page->get_template($page->template);
					/* set some initial values */
					$keyword_replacements = array();
					$temp1 = array();
					$temp2 = array();
					$keyword_function = $settings['keyword_function'];
					if (method_exists($template,$keyword_function)) {
						$temp1 = $template->$keyword_function();
					}
					if (method_exists($template, 'get_universal_replacements_descriptions')) {
						$temp2 = $template->get_universal_replacements_descriptions();
					}
					$keyword_replacements = array_merge($temp1, $temp2);

					$bodycopy->print_backend($code, 
											 "action", 
											 "Commit", 
											 "edit", 
											 $site_design->stylesheet("src"), 
											 $site_design->stylesheet("rel"), 
											 $special_styles,
											 $site->id,
											 $page->id,
											 squizlib_href("bodycopy"),
											 $keyword_replacements
						);
				} else {
					$bodycopy->paint();
				}
				if($settings['note']) {
					echo("<span class=backend_smallprint>$settings[note]</span>");
				}
				echo("<hr size=0 noshade>");
				break;
			case "form": # To be used with caution
				if(!$code) {
					$backend->open_section("Form - $parameter");
					$backend->open_field('','',$anchor);
					echo('No code supplied for this parameter.');
					return;
				}
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/form/form.inc");
				if($settings['note']) {
					$backend->open_section("$parameter - Note");
					$backend->open_field('','',$anchor);
					echo($settings['note']);
				}
				$form = new Form($value);
				$form->name($code);

				# get any auto answer offer options
				$offer_auto_answer = Array();
				if($settings['offer_auto_answer_function']) {
					if ($this->caller && is_object($this->caller) && method_exists($this->caller,$settings['offer_auto_answer_function'])) {
						$offer_auto_answer = $this->caller->$settings['offer_auto_answer_function']();
					} else {
						echo("<div class=warning>Cannot find '$settings[offer_auto_answer_function]' method in caller object.</div>");
					}
				}
				if ($settings['offer_auto_answer']) {
					$offer_auto_answer = $this->make_options_array($settings['offer_auto_answer']);
				}
				if (!empty($offer_auto_answer)) {
					foreach($offer_auto_answer as $code => $desc) {
						$form->offer_auto_answer($code, $desc);
					}
				}

				# get any requestion information options
				$request_information = Array();
				if($settings["request_information_function"]) {
					if ($this->caller && is_object($this->caller) && method_exists($this->caller,$settings['request_information_function'])) {
						$request_information = $this->caller->$settings['request_information_function']();
					} else {
						echo("<div class=warning>Cannot find '$settings[request_information_function]' method in caller object.</div>");
					}
				}
				if ($settings['request_information']) {
					$request_information = $this->make_options_array($settings['request_information']);
				}
				if (!empty($request_information)) {
					foreach($request_information as $code => $desc) {
						$form->request_information($code, $desc);
					}
				}

				$auto_answer_code = $settings['session_auto_answer_parameter_code'];
				$auto_answer = false;
				if (!empty($auto_answer_code) && !empty($this->values[$auto_answer_code])) {
					$auto_answer = true;
				}
				$form->set_session_auto_answer($auto_answer);

				if($wa) {
					$form->meta_form($backend,$parameter);
				} else {
					$form->print_answers();
				}
				break;
			case "colour_picker":
				if(!$code) {echo("No code supplied for this parameter.");return;}
				$backend->open_field($parameter,$settings['format'],$anchor);
				echo colour_box($code,$value);
				break;
			case "page_picker":
				if(!$code) { echo("No code supplied for this parameter."); return;}
				$backend->open_field($parameter,$settings['format'],$anchor);
				$web_system = &get_web_system();
				$site = &$web_system->get_site();
				if(!$site->id) {
					echo "Cannot print page picker - no site id.";
				} else {
					$options = $web_system->page_array_with_sticks($site->id);
					if ($settings['show_top_level']) {
						$options = array_reverse($options, true);
						if ($settings['top_level_label']) {
							$options[0] = $settings['top_level_label'];
						} else {
							$options[0] = '[TOP LEVEL]';
						}
						$options = array_reverse($options, true);
					}
					echo combo_box($code,$options,$value,"class=backend_data");
				}
				if($settings['note']) {
					echo("<span class=backend_smallprint><br>$settings[note]</span>");
				}
				break;
			default:
				$backend->open_field($parameter,'top',$anchor);
				echo("Parameter type '$settings[type]' unknown.");
				break;
		}
	}

	 ####################################################
	# Works out the array-defining string for a parameter
	function encapsulation_string($screen='',$section='',$parameter='',$code='',$enc='') {
		if($enc) { # Encapsulation for this instance only
			$name_prefix = $enc.'[';
			$name_suffix = "]";
		}
		if($enc = $this->encapsulate) { # Encapsulation for this instance only
			$name_prefix = (($name_prefix)?"$name_prefix$enc][":$enc."[");
			$name_suffix = "]";
		}
		if($enc = &$this->get_setting("encapsulate")) {
			$name_prefix = (($name_prefix)?"$name_prefix$enc][":$enc."[");
			$name_suffix = "]";
		}
		if($enc = &$this->get_screen_info($screen,"encapsulate")) {
			$name_prefix = (($name_prefix)?"$name_prefix$enc][":$enc."[");
			$name_suffix = "]";
		}
		if($enc = &$this->get_section_info($screen,$section,"encapsulate")) {
			$name_prefix = (($name_prefix)?"$name_prefix$enc][":$enc."[");
			$name_suffix = "]";
		}
		if($enc = &$this->get_parameter_info($screen,$section,$parameter,"encapsulate")) {
			$name_prefix = (($name_prefix)?"$name_prefix$enc][":$enc."[");
			$name_suffix = "]";
		}
		return "$name_prefix$code$name_suffix";
	}


	 #########################################
	# Splits an option into a key and a value
	function split_option($option='') {
		$option = ltrim($option);
		$r = array();
		if($option[0] == "\"") {
			$r[0] = ereg_replace("^\"([^\n]+[^\\])\"[ \t]+([^\n]+)","\\1",$option);
			$r[0] = str_replace('\"','"',$r[0]);
			$r[1] = ereg_replace("^\"([^\n]+[^\\])\"[ \t]+([^\n]+)","\\2",$option);
		} else {
			$r[0] = ereg_replace("^([^ \t]+)[ \t]+([^\n]+)","\\1",$option);
			$r[1] = ereg_replace("^([^ \t]+)[ \t]+([^\n]+)","\\2",$option);
		}
		return $r;
	}

	 ################################################################################
	# Takes an array of options from the  parsing and makes a nice associative array
	function make_options_array($o=array()) {
		$r = array();
		if(!is_array($o)) {
			if($o) $o = array($o);
			else   $o = array();
		}
		foreach($o as $option) {
			list($k,$v) = $this->split_option($option);
			$r[$k] = $v;
		}
		return $r;
	}

	 ###################################################################
	# Returns a refernce to an associative array of permission descriptions
	function &get_access_group_names() {
		$web_system = &get_web_system();
		$g = 'parameter_set_permission_access_group_names';
		$l = &$_REQUEST[$g];
		if(is_array($l)) return $l;

		# This is where you edit the options
		$l = array (
			'P' => 'Everyone',
			'L' => 'Anyone Logged In',
		);
		$gens = &$web_system->get_general_access_group_list();
		if(!is_array($gens)) $gens = array();
		$l += $gens;
		$site = &$web_system->get_site();
		if($site->id) {
			$sites = &$site->access_groups;
			$l += $sites;
		}
		return $l;
	}


	 ################################################
	# Processes a screen after it has been committed
	function process_screen(&$backend, $screen='') {
		$sections = &$this->get_sections($screen);
		if (!is_array($sections)) $sections = array();
		if (!empty($sections)) {
			foreach(array_keys($sections) as $section) {
				if(substr($section,0,2)=='__') continue;
				if(!$this->check_section_show_if($screen,$section)) continue;
				$this->process_section($backend, $screen,$section);
			}
		}
	}


	 ##################
	# Prints a section
	function process_section(&$backend, $screen='',$section='') {
		$parameters = &$this->get_parameters($screen,$section);
		if (!is_array($parameters)) $parameters = array();
		if (!empty($parameters)) {
			foreach(array_keys($parameters) as $parameter) {
				if(substr($parameter,0,2)=="__") continue;
				if(!$this->check_parameter_show_if($screen,$section,$parameter)) continue;
				$this->process_parameter($backend, $screen,$section,$parameter);
			}
		}
	}


	 ######################################################
	# Processes a parameter and saves it back into $values
	function process_parameter(&$backend, $screen='',$section='',$parameter='') {
		$settings = &$this->get_parameter_info($screen,$section,$parameter);
		
		# Special parameters
		if($settings['type'] == '_load_parameters') {
			return $this->process_load_interface($backend);
		} elseif($settings['type'] == '_save_parameters') {
			return $this->process_save_interface($backend);
		}

		if($settings['code']) {
			# Work out a prefix to the variable name. This is to allow encapsulation
			# in variables
			$code = $this->encapsulation_string($screen,$section,$parameter,$settings['code']);

			 ##################################################
			# Call a function to set the value rather than
			# setting it in the parameter array itself
			$this->split_function_string($settings['set_function'], $main_set_function, $inside_set_extention, 1);

			if ($settings['set_function'] && method_exists($this->caller, $main_set_function)) {
				$set_fn = true;
			}
			$this->split_function_string($settings['get_function'], $main_get_function, $inside_get_extention);
			if(!$settings['get_function'] || !method_exists($this->caller,$main_get_function)) {
				if(is_object($this->values)) {
					eval("\$value_destination = &\$this->values->$code;");
				} elseif(is_array($this->values)) {
					$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
					eval("\$value_destination = &\$this->values$tmp_code;");
				} else {
					# this is the first time ever this parameter is getting saved to so the by reference isn't going to work. We'll need to save in a special way here.
					$first_time = true;
				}
			} else {
				# this is a case where we are probably using a get function
				if (is_object($this->caller) && method_exists($this->caller, $main_get_function)) {
					eval("\$value_destination = &\$this->caller->$main_get_function($inside_get_extention);");
				} else {
					# we are unable to get a value because the get function doesn't exist oh well
					$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, "Problem with getting value using get function");
					return;
				}

			}
			$code = str_replace('[','__OP__',$code);
			$code = str_replace(']','__CL__',$code);
		}


		 #############################################################
		# Does the current use rhave write access to this parameter?
		$wa = $this->check_parameter_change_if($screen,$section,$parameter);

		switch($settings['type']) {
			case 'function':
				$saving_type = 'array';
				if($settings['process']) {
					if($this->caller && is_object($this->caller)) {
						$this->split_function_string($settings['process'], $main_set_function, $inside_set_extention);
						if (method_exists($this->caller,$main_set_function)) {
							eval("\$backend->add_message(\$this->caller->$main_set_function($inside_set_extention));");
						}
					} else {
						$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, "Cannot find '$settings[process]' method in caller object.");
					}
				} # else do nothing :)
			break;
			case 'int': case 'real';
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$saving_type = 'array';
				if(!in_array(strtolower($settings['allow_blank']),array('yes','y','on','true'))
					|| strlen($$code) > 0) {
					if(!$set_fn && strlen($value_destination) && (double)$value_destination == (double)$$code) return;
					if($settings['type'] == 'int') {
						$$code = (int) $$code;
					}
					if($settings['type'] == 'real') {
						$$code = (double) $$code;
					}
					if(isset($settings['max'])) {
						$$code = min($settings['max'], $$code);
					}
					if(isset($settings['min'])) {
						$$code = max($settings['min'], $$code);
					}
				} else {
					$$code = '';
				}

				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$$code, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					if ($code_existed) {
						$value_destination = $$code;
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
					}
				}
			break;
			case 'text':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$$code = gpc_stripslashes($$code);
				if(!$set_fn && $value_destination == $$code) return;
				if($settings['maxlength']) {
					$$code = substr($$code,0,$settings['maxlength']);
				}
				$saving_type = 'array';
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$$code, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					if($code_existed) {
						$value_destination = $$code;
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
					}
				}
			break;
			case 'country': 
			case 'permission':
			case 'option':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$saving_type = 'array';
				if(!$set_fn) {
					if(is_array($value_destination)) {
						if(equal_arrays($value_destination,$$code)) return;
					} else {
						if($value_destination === $$code) return;
					}
				}
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$$code, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					$value_destination = $$code;
					$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
				}
			break;
			case 'datetime':
				if(!$wa) return;
				if(!$code) return;
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/datetime_field/datetime_field.inc");
				$saving_type = 'array';
				if($set_fn) {
					$d = new datetime_field($code,$value,$settings);
					$d->process_field();
					eval("\$message = \$this->caller->$main_set_function(\$value);");
					$backend->add_message($message);
				} else {
					$d = new datetime_field($code,$value_destination,$settings);
					if($d->process_field()) {
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
					}
				}
				break;
			case 'user_list':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$v = &$$code;
				$die = array();
				$users_system = &get_users_system();
				$saving_type = 'array';
				if (!empty($value_destination)) {
					foreach($value_destination as $i => $userid) {
						if(in_array($userid,$v['delete_userids'])) {
							$die[] = $i;
							$user = &$users_system->get_user($userid);
							if($user->id) {
								$message .= $user->name()." removed from the $parameter user list.\n";
							}
						}
					}
				}
				if (!empty($die)) {
					foreach($die as $i) {
						unset($value_destination[$i]);
					}
				}
				$user_searches = split("[ \t\r\n\,]+",$v['add_search']);
				if (!empty($user_searches)) {
					foreach($user_searches as $user_search) {
						if ($user_search) {
							$result = $users_system->find_user($user_search);
							if($result['userid'] > 0 && !in_array($result['userid'],$value_destination)) {
								$value_destination[] = $result['userid'];
								$message .= "$result[firstname] $result[surname] added to the $parameter user list.\n";
							}
						}
					}
				}
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$value_destination, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					$backend->add_message(MYSOURCE_ERROR_CODE_NONE, trim($message));
				}
			break;
			case 'org_list':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$v = &$$code;
				$die = array();
				$users_system = &get_users_system();
				$saving_type = 'array';
				if (!empty($value_destination)) {
					foreach($value_destination as $i => $orgid) {
						if(in_array($orgid,$v['delete_orgids'])) {
							$die[] = $i;
							$org = &$users_system->get_organisation($orgid);
							if($org->id) {
								$message .= $org->name." removed from the $parameter organisation list.\n";
							}
						}
					}
				}
				if (!empty($die)) {
					foreach($die as $i) {
						unset($value_destination[$i]);
					}
				}
				$new_orgid = $v['add_search'];
				if($new_orgid && !in_array($new_orgid,$value_destination)) {
					$value_destination[] = $new_orgid;
					$new_org = &$users_system->get_organisation($new_orgid);
					$message .= $new_org->name." added to the $parameter organiastion list.\n";
				}
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$value_destination, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					$backend->add_message(MYSOURCE_ERROR_CODE_NONE, trim($message));
				}
			break;
			case 'bodycopy': # To be used with caution
				if(!$wa) return;
				if(!$code) return;
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
				$bodycopy = new BodyCopy($value_destination, $code);
				$message = $bodycopy->update($code);
				$saving_type = 'array';
				if($set_fn) {
					eval("\$this->caller->$main_set_function(\$bodycopy->pack(){$inside_set_extention});");
				} else {
					$value_destination = $bodycopy->pack();
				}
				$backend->add_message(MYSOURCE_ERROR_CODE_NONE, $message);
			break;
			case 'form': # To be used with caution
				if(!$wa) return;
				if(!$code) return;
				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/form/form.inc");
				$form = new Form($value_destination);
				$form->name($code);
				$auto_answer_code = $settings['session_auto_answer_parameter_code'];
				$auto_answer = false;
				if (!empty($auto_answer_code) && !empty($this->values[$auto_answer_code])) {
					$auto_answer = true;
				}
				$form->set_session_auto_answer($auto_answer);
				$message = $form->update();
				$saving_type = 'array';
				if($set_fn) {
					eval("\$this->caller->$main_set_function(\$form->pack(){$inside_set_extention});");
				} else {
					$value_destination = $form->pack();
				}
				$backend->add_message(MYSOURCE_ERROR_CODE_NONE, $message);
			break;
			case 'colour_picker':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$$code = gpc_stripslashes($$code);
				if(!$set_fn && $value_destination == $$code) return;
				$saving_type = 'array';
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$$code, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					if ($code_existed) {
						$value_destination = $$code;
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
					}
				}
			case 'page_picker':
				if(!$wa) return;
				if(!$code) return;
				$code_existed = isset($_REQUEST[$code]);
				$$code = $_REQUEST[$code];
				$$code = gpc_stripslashes($$code);
				$saving_type = 'array';
				if($set_fn) {
					eval("\$message = \$this->caller->$main_set_function(\$$code, \$code_existed{$inside_set_extention});");
					$backend->add_message($message);
				} else {
					if ($code_existed) {
						$value_destination = $$code;
						$backend->add_message(MYSOURCE_ERROR_CODE_NONE, "$parameter updated.");
					}
				}
				break;
			break;
		}

		# this is the special case saving code.
		if ($first_time) {
			switch ($saving_type) {
				case 'array': default:
					$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
					eval("\$this->values$tmp_code = \$value_destination;");
					break;
				case 'object':
					eval("\$this->values->$code = \$value_destination;");
					break;
			}
		}
	}


	 #################################################################
	# Prints an interface for loading parameters from various places.
	function print_load_interface(&$backend) {
		?>
		<table cellpadding=3 cellspacing=0 border=0>
			<?
			# Get a list of builtin or caller-supplied parameters
			if(method_exists($this->caller,'get_pset_supplied_params')) {
				$options = $this->caller->get_pset_supplied_params();
				if(is_array($options) && count($options) > 0) {
					?>
					<tr>
						<td valign=top><input type=radio name=param_load_source value="_supplied"></td>
						<td valign=top align=right class=backend_field>Supplied</td>
						<td valign=top>
						<?
						echo combo_box('param_load_supplied_filename',array(''=>'-- Select a supplied parameter configuration --')+$options);
						?>
						</td>
					</tr>
					<?
				}
			}
			?>
			<tr>
				<td valign=top><input type=radio name=param_load_source value="_account"></td>
				<td valign=top align=right class=backend_field>From Account</td>
				<td valign=top>
				<?
				$session = &get_mysource_session();
				$d = opendir("{$session->user->data_path}/params/$this->id");
				$options = array(''=>'-- Select a previously saved parameter configuration --');
				while($file = readdir($d)) {
					if(ereg("^.+\.param$",$file)) {
						$name = stripslashes(str_replace("_"," ",ereg_replace("\.param$","",$file)));
						$options[$file] = $name;
					}
				}
				ksort($options);
				echo combo_box('param_load_account_filename',$options);
				?>
				</td>
			</tr>
			<tr>
				<td valign=top><input type=radio name=param_load_source value="_upload"></td>
				<td valign=top align=right class=backend_field>Upload</td>
				<td valign=top><input type=file name=param_load_file></td>
			</tr>
			<tr>
				<td colspan=2 valign=top align=right class=backend_field>Include:</td>
				<td valign=top>
				<?
				$screen_names = $this->get_screen_names();
				if (!empty($screen_names)) {
					foreach($screen_names as $screen) {
						$section_names = $this->get_section_names($screen);
						if (!empty($section_names)) {
							foreach($section_names as $section) {
								$pnames = $this->get_parameter_names($screen,$section);
								if (!empty($pnames)) {
									foreach($pnames as $parameter) {
										$code = $this->get_parameter_info($screen,$section,$parameter,'code');
										if(!$code) continue;
										if(!$secprinted) {
											echo("<b>$screen : $section :</b><br>");
											$secprinted = 1;
										}
										$code = $this->encapsulation_string($screen,$section,$parameter,$code);
										$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
										echo("<input type=checkbox name=\"param_load_include$tmp_code\" value=1 checked>$parameter<br>");
									}
								}
								$secprinted = 0;
							}
						}
					}
				}
				?>
				</td>
			</tr>
		</table>
		<?
	}

	 #################################################################
	# Processes an interface for loading parameters from various places.
	function process_load_interface(&$backend) {
		$param_load_source = $_REQUEST['param_load_source'];
		$session = &get_mysource_session();
		switch($param_load_source) {
			case '_supplied':
				$param_load_supplied_filename = $_REQUEST['param_load_supplied_filename'];
				if(method_exists($this->caller,'get_pset_supplied_param')) {
					$new_p = $this->caller->get_pset_supplied_param($param_load_supplied_filename);
				}
				break;
			case '_account':
				$param_load_account_filename = $_REQUEST['param_load_account_filename'];
				$new_p =  unserialize(trim(file_to_string("{$session->user->data_path}/params/$this->id/$param_load_account_filename"),"\0x00..\0x1F \t\n\r"));
				break;
			case '_upload':
				$param_load_file = $_FILES['param_load_file']['tmp_name'];
				if($param_load_file != "none") {
					$new_p =  unserialize(trim(file_to_string($param_load_file),"\0x00..\0x1F \t\n\r"));
				}
				break;
			default:
				break;
		}
		if(is_array($new_p)) { # We've loaded something successfully.
			if($new_p['__type__'] && $this->id != $new_p['__type__']) {
				$backend->add_message(MYSOURCE_ERROR_CODE_ERROR,"Unable to import parameters - invalid type. This type: $this->id / New type: ".$new_p['__type__']);
				#return;
			}
			if(isset($this->caller->version)) {
				if(version_no_compare($new_p["__version__"],$this->caller->version) > 0) {
					$backend->add_message(MYSOURCE_ERROR_CODE_WARNING,"WARNING: The imported parameters are from a more recent version. Some of your settings may be corrupt.");
				} elseif(version_no_compare($new_p["__version__"],$this->caller->version) < 0) {
					$backend->add_message(MYSOURCE_ERROR_CODE_WARNING,"WARNING: The imported parameters are from an older version. Some of your settings may be corrupt.");
				}
			}
			$param_load_include = $_REQUEST['param_load_include'];
			#global $param_load_include_extras;
			$existing_codes = array();
			$screen_names = $this->get_screen_names();
			if (!empty($screen_names)) {
				foreach($screen_names as $screen) {
					$section_names = $this->get_section_names($screen);
					if (!empty($section_names)) {
						foreach($section_names as $section) {
							$pnames = $this->get_parameter_names($screen,$section);
							if (!empty($pnames)) {
								foreach($pnames as $parameter) {
									$code = $this->get_parameter_info($screen,$section,$parameter,'code');
									if(!$code) {
										#$backend->add_message(MYSOURCE_ERROR_CODE_WARNING,"No information imported for $screen: $sections: $parameter - no 'code' in parameter set.");
										continue;
									}
									$code = $this->encapsulation_string($screen,$section,$parameter,$code);
									$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
									eval("\$inc = isset(\$param_load_include$tmp_code);");
									if(is_object($this->values)) {
										eval("\$value = &\$this->values->$code;");
									} elseif(is_array($this->values)) {
										eval("\$value = &\$this->values$tmp_code;");
									}
									if($inc) {
										eval("\$set = isset(\$new_p$tmp_code);");
										if(!$set) {
											$backend->add_message(MYSOURCE_ERROR_CODE_WARNING,"$screen: $section: $parameter could not be updated - not found in imported parameters.");
											continue;
										}
										eval("\$new_value = &\$new_p$tmp_code;");
										$value = $new_value;
										$backend->add_message(MYSOURCE_ERROR_CODE_NONE,"$screen: $section: $parameter updated.");
									}
									eval("\$existing_codes$tmp_code = array();");
								}
							}
						}
					}
				} # end foreach screen
			}
		}
	}


	 #################################################################
	# Prints an interface for saveing parameters from various places.
	function print_save_interface(&$backend) {
		?>
		<table cellpadding=3 cellspacing=0 border=0>
			<tr>
				<td valign=top><input type=radio name=param_save_source value="_account"></td>
				<td valign=top align=right class=backend_field>To&nbsp;Account</td>
				<td valign=top><?=text_box('param_save_account_name',$this->caller->id.'_'.date("Y-m-d"),40,200,"class=backend_smallprint")?> - Name</td>
			</tr>
			<tr>
				<td valign=top><input type=radio name=param_save_source value="_download"></td>
				<td valign=top align=right class=backend_field>Download</td>
				<td valign=top><?=text_box('param_save_download_filename',$this->caller->id.'_'.date("Y-m-d"),40,200,"class=backend_smallprint")?> - Filename</td>
			</tr>
			<tr>
				<td colspan=2 valign=top align=right class=backend_field>Include:</td>
				<td valign=top>
				<?
				$screen_names = $this->get_screen_names();
				if (!empty($screen_names)) {
					foreach($screen_names as $screen) {
						$section_names = $this->get_section_names($screen);
						if (!empty($section_names)) {
							foreach($section_names as $section) {
								$pnames = $this->get_parameter_names($screen,$section);
								if (!empty($pnames)) {
									foreach($pnames as $parameter) {
										$code = $this->get_parameter_info($screen,$section,$parameter,'code');
										if(!$code) continue;
										if(!$secprinted) {
											echo("<b>$screen : $section :</b><br>");
											$secprinted = 1;
										}
										$code = $this->encapsulation_string($screen,$section,$parameter,$code);
										$tmp_code = ereg_replace("^[^\[]+","[\\0]",$code);
										echo("<input type=checkbox name=\"param_save_include$tmp_code\" value=1 checked>$parameter<br>");
									}
								}
								$secprinted = 0;
							}
						}
					}
				}
				?>
				</td>
			</tr>
		</table>
		<?
	}


	 #################################################################
	# Processs an interface for saveing parameters from various places.
	function process_save_interface(&$backend) {
		$session = &get_mysource_session();
		if(!$_POST['param_save_source']) return;
		$old_p = $this->_build_save_array($_POST['param_save_include'],$this->values);
		$old_p['__id__']      = $this->id;
		$old_p['__version__'] = $this->caller->version;
		switch($_POST['param_save_source']) {
			case '_account':
				$filename = str_replace(' ','_',$_POST['param_save_account_name']).'.param';
				$dir      = "{$session->user->data_path}/params/$this->id";
				create_directory($dir);
				$path     = "$dir/$filename";
				if(string_to_file(serialize($old_p),$path)) {
					$backend->add_message(MYSOURCE_ERROR_CODE_NONE,"Parameters saved to user account: $param_save_account_name");
				}
				break;
			case '_download':
				$filename = str_replace(' ','_',$_POST['param_save_download_filename']).'.param';
				$path = "{$session->user->data_path}/$filename";
				string_to_file(serialize($old_p),$path);
				send_cacheable_file($path);
				unlink($path);
				exit();
			default;
				break;
		}
	}

	 ####################################
	# Builds the parameter array to save
	function _build_save_array(&$include_list,&$values) {
		$old_p = array();
		if (!empty($include_list)) {
			foreach($include_list as $k => $v) {
				if($v == 1) {
					$old_p[$k] = &$values[$k];
				} elseif(is_array($v) && isset($values[$k])) {
					if($sub_p = $this->_build_save_array($v,$values[$k])) {
						$old_p[$k] = $sub_p;
					}
				}
			}
		}
		return $old_p;
	}

	/**
	* Splits up a function string like print_stuff('arg1', 'arg2') into $main_function ie print_stuff and $inside_extention ie 'arg1', 'arg2'
	*
	* @param string $function_string the function to split up
	* @param string &$main_function Just some by ref vars to gain the returning values
	* @param string &$inside_extention Just some by ref vars to gain the returning values
	* @access public
	* @return void
	*/
	function split_function_string($function_string='', &$main_function, &$inside_extention, $prefix_comma=false) {
		$main_function = preg_replace("/\(.*\)/", '', $function_string);
		$extention = str_replace($main_function, '', $function_string);
		$inside_extention = substr($extention, 1, -1);
		if ($inside_extention && $prefix_comma) {
			$inside_extention = ', '.$inside_extention;
		}
	}
}

?>
