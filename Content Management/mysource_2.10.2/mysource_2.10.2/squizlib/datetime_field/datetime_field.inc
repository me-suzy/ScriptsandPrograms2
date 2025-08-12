<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- General Include ---- PHP4 --------------##
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
## $Source: /home/cvsroot/squizlib/datetime_field/datetime_field.inc,v $
## $Revision: 1.20.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/16 02:23:00 $
#######################################################################

#---------------------------------------------------------------------#


 #########################################################
# This class is datatype for storing a datetime. The data
# isn't really important, its just 'YYYY-MM-DD HH:II:SS'
# its the functionality that counts.
# It's okay to send a parameters array across with other
# elements, this thing won't diturb other elements, but
# it uses: show, style, min, max
class DateTime_Field extends Object {

	var $prefix='';
	var $value='';
	var $parameters='';

	 ############
	# Constructor
	function DateTime_Field($prefix='',&$value,&$parameters) {
		Object::Object();
		$this->prefix     =  $prefix;
		$this->value      = &$value;
		$this->parameters = &$parameters;
	}

	 ###############################
	# Sets a refernce to a new value
	function set_value(&$value) {
		$this->value = &$value;
	}
	function set_parameters(&$parameters) {
		$this->parameters = &$parameters;
	}
	function &get_param($code='') {
		if(isset($this->parameters[$code])) return $this->parameters[$code];
		return;
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_field() {
		$show = &$this->get_param('show');
		$null = &$this->get_param('null');
		echo "<input type=hidden name=\"$this->prefix"."show\" value=\"".implode('',$show)."\">";
		$this->prefix .= 'value';
		if($this->get_param('allow_circa')) {
			echo combo_box($this->prefix.'[c]',array('0'=>'','1'=>'circa.'),$this->get_unit('c'));
		}
		foreach(array('d','m','y','h','i','s') as $t) {
			$u = $this->get_unit($t);
			if(in_array($t,$show)) {
				echo $this->input_box($t,$this->prefix,$u,in_array($t,$null));
			} else echo "<input type=hidden name=\"{$this->prefix}[$t]\" value=\"$u\">";
			if($t == 'y') echo ' ';
			if($t == 'h' && in_array('h',$show) && in_array('i',$show)) echo ':';
			if($t == 'i' && in_array('i',$show) && in_array('s',$show)) echo ':';
		}

	}

	 #############################################
	# Prints an input box for a particular unit
	function input_box($u='',$prefix='',&$value,$allow_null=false) {
		$input_style = &$this->get_param('style');
		$extras = &$this->get_param('extras');
		$increments = &$this->get_param('increment');
		$min = (int) $this->get_unit($u,$this->get_param('min'));
		$max = (int) $this->get_unit($u,$this->get_param('max'));
		if($allow_null && (!strlen($value) || $value == '--')) {
			$value = '--';
		} else {
			$value = (int) $value;
		}

		$increment = $increments[$u];
		if (!$increment) $increment = 1;

		if($input_style[$u] == 's') { #select box
			if($allow_null) $options = array('--' => '--');
			else            $options = array();

			switch($u) {
				case 'y': case 'd':
					for($i = (int) $min; $i <= $max; $i += $increment) $options[$i] = $i;
					break;
				case 'm':
					for($i = (int) $min; $i <= $max; $i += $increment) $options[$i] = $this->short_month($i);
					break;
				case 'h': case 'i': case 's':
					for($i = (int) $min; $i <= $max; $i += $increment) $options[$i] = sprintf('%02d',$i);
					break;
			}

			return combo_box($prefix."[$u]",$options,$value,$extras[$u]);
		} elseif($input_style[$u] == 't' || 1) { # plain text - Default I guess
			$size = (($u=='y')?4:2);
			if($allow_null) {
				$onchange = "value=parseInt(value);if(isNaN(value)){value='--';}else{value=Math.min($max,Math.max($min,isNaN(value)?0:value));}";
			} else {
				$onchange = "value=parseInt(value);value=Math.min($max,Math.max($min,isNaN(value)?0:value));";
			}

			# merge onChange local values with those coming in from extras
			if (eregi('onchange *="|\'', $extras[$u])) {
				$extra = eregi_replace ("onchange *=(\"|')", 'onChange=\1'.$onchange, $extras[$u]);
				return text_box($prefix."[$u]",$value,$size,$size, $extra);
			} else {
				return text_box($prefix."[$u]",$value,$size,$size, $extras[$u]);
			}
		}
		return "<input type=hidden name=\"$prefix"."[$u]\" value=\"$value\" ".$extras[$u].">";
	}

	 ########################################
	# Returns the value of a particular unit
	function get_unit($unit='',$value='') {
		if(!strlen($value)) $value = $this->value;
		list($date,$time,$c) = explode(' ',$value);
		list($y,$m,$d)       = explode('-',$date);
		list($h,$i,$s)       = explode(':',$time);
		return $$unit;
	}

	 ###########################################
	# Given a month number returns the short name
	function short_month($m='',$invert=false) {
		$months = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		if($invert) {
			$months = array_flip($months);
			$m = ucfirst($m);
		}
		return $months[$m];
	}

	 ###########################################
	# Given a month number returns the long name
	function long_month($m='',$invert=false) {
		$months = array('','January','February','March','April','May','June','July','August','September','October','November','December');
		if($invert) {
			$months = array_flip($months);
			$m = ucfirst($m);
		}
		return $months[$m];
	}


	 #############################################
	# Prints fields for inputting the min and max
	function print_min_field($prefix='') {
		$p = array(
			'min'       => '0000-01-01 00:00:00',
			'max'       => '9999-12-31 23:59:59',
			'show'      => array('y','m','d','h','i','s'),
			'increment' => array(),
			'style'     => $this->get_param('style')
		);
		$p['style']['y'] = 't';
		$d = new DateTime_Field("{$this->prefix}_min",$this->get_param('min'),$p);
		$d->print_field();
	}
	function process_min_field($prefix='') {
		$p = array(
			'min'       => '0000-01-01 00:00:00',
			'max'       => '9999-12-31 23:59:59',
			'show'      => array('y','m','d','h','i','s'),
			'increment' => array(),
			'style'     => $this->get_param('style')
		);
		$d = new DateTime_Field("{$this->prefix}_min",$this->get_param('min'),$p);
		$d->process_field();
	}
	function print_max_field($prefix='') {
		$p = array(
			'min'       => '0000-01-01 00:00:00',
			'max'       => '9999-12-31 23:59:59',
			'show'      => array('y','m','d','h','i','s'),
			'increment' => array(),
			'style'     => $this->get_param('style')
		);
		$p['style']['y'] = 't';
		$d = new DateTime_Field("{$this->prefix}_max",$this->get_param('max'),$p);
		$d->print_field();
	}
	function process_max_field($prefix='') {
		$p = array(
			'min'       => '0000-01-01 00:00:00',
			'max'       => '9999-12-31 23:59:59',
			'show'      => array('y','m','d','h','i','s'),
			'increment' => array(),
			'style'     => $this->get_param('style')
		);
		$d = new DateTime_Field("{$this->prefix}_max",$this->get_param('max'),$p);
		$d->process_field();
	}


	 #############################################
	# Prints the interface for filling in a value
	function process_field() {
		${$this->prefix.'value'} = $_REQUEST[$this->prefix.'value'];
		$input = ${$this->prefix.'value'};
		$input = $this->units_array_to_str($input);
		$old_value = $this->value;
		$this->value = $input;
		$this->validate_value();
		if($old_value == $this->value) return;
		return 1;
	}


	 #####################################################
	# Ensures that a value is in the correct format etc
	function validate_value() {
		if(!is_array($this->value)) {
			$this->value = trim($this->value);
			$l = strlen($this->value);
			$this->value = preg_replace("/^c(irc[a]?)?\.?[\s]*/i",'',$this->value);
			if(($new_l = strlen($this->value)) != $l) {
				$c = true;
			}
			if(!preg_match("/^[0-9\-]{4}-[0-9\-]{2}-[0-9\-]{2} ([0-9\-]{2}\:?){3}( [01]?)?$/",$this->value)) { # Correct format?
				if(preg_match("/^[0-9]{4}$/",$this->value)) { # Just a year?
					$this->value .= '------ --:--:--';
				} elseif(preg_match("/^[0-9]+$/",$this->value)) { # Timestamp?
					$this->value = date('Y-m-d H:i:s',$this->value);
				} else {
					$show = &$this->get_param('show');
					# Okay lets try to parse this thing.
					$v = array('y'=>-1,'m'=>-1,'d'=>-1,'h'=>-1,'i'=>-1,'s'=>-1);
					# Dates
					if(preg_match("/^([0-9]{1,2})[\-\/]([0-9]{1,2})[\-\/]([0-9]{2,4})([^0-9].*|)$/",$this->value,$matches)) {
						# Probably "dd/mm/yyyy" or "mm/dd/yyyy"
						if($this->get_param('standard') == 'us') {
							$v['d'] = $matches[2]; $v['m'] = $matches[1];
						} else {
							$v['d'] = $matches[1]; $v['m'] = $matches[2];
						}
						$v['y'] = $matches[3];
						if(strlen($v['y']) <= 2) $v['y'] += (($v['y']<20)?2000:1900);
					} elseif(preg_match("/^([0-9]{1,2})[\-\/]([0-9]{1,2})([^0-9].*|)$/",$this->value,$matches)) {
						# Probably dd/mm
						if($this->get_param('standard') == 'us') {
							$v['d'] = $matches[2]; $v['m'] = $matches[1];
						} else {
							$v['d'] = $matches[1]; $v['m'] = $matches[2];
						}
					} elseif(preg_match("/^([0-9]{1,2})[\-\/]([0-9]{4})([^0-9].*|)$/",$this->value,$matches)) {
						# Probably mm/yy
						$v['m'] = $matches[1];
						$v['y'] = $matches[2];
					}
					elseif(preg_match("/^([0-9\-]{4})[\-\/]([0-9\-]{2})[\-\/]([0-9\-]{2})$/",$this->value,$matches)) {
						# Probably "yyyy/dd/mm" or "yyyy/mm/dd"
						if($this->get_param('standard') == 'us') {
							$v['d'] = $matches[2]; $v['m'] = $matches[3];
						} else {
							$v['d'] = $matches[3]; $v['m'] = $matches[2];
						}
						$v['y'] = $matches[1];
						if(strlen($v['y']) <= 2) $v['y'] += (($v['y']<20)?2000:1900);
					}
					else {
						# Maybe a more verbal date.
						if(preg_match("/([0-9]{1,2})?([\s\,]*)(jan(uary)?|feb(ruary)?|mar(ch)?|apr(il)?|may|jun(e)?|jul(y)?|aug(ust)?|sep(tember)?|oct(ober)?|nov(ember)?|dec(ember)?)([\s\,]*)([0-9]{1,2})?([^0-9].*|)$/i",$this->value,$matches)) {
							# textural month?
							if(strlen($tmp =  $matches[3]) == 3) {
								$v['m'] = $this->short_month($tmp,1);
							} else {
								$v['m'] = $this->long_month($tmp,1);
							}
							# maybe the day?
							if($matches[1])      $v['d'] = $matches[1];
							elseif($matches[16]) $v['d'] = $matches[16];
						}
						if(preg_match("/([0-9]{4})([^0-9].*|)$/",$this->value,$matches)) {
							# four digit year?
							$v['y'] = $matches[1];
						}
						if($v['d'] == -1 && preg_match("/([0-9]{1,2})(st|nd|rd|th)/i",$this->value,$matches)) {
							# 1st, 2nd, 3rd, 4th etc
							$v['d'] = $matches[1];
						}

						# Times !
						if(preg_match("/([0-9]{1,2})[\:\.]([0-9]{1,2})[\:\.]([0-9]{1,2})([^0-9].*|)$/",$this->value,$matches)) {
							$v['h'] = $matches[1];
							$v['i'] = $matches[2];
							$v['s'] = $matches[3];
						} elseif(preg_match("/([0-9]{1,2})[\:\.]([0-9]{1,2})([^0-9].*|)$/",$this->value,$matches)) {
							if(in_array('s',$show) && !in_array('h',$show)) {
								$v['i'] = $matches[1];
								$v['s'] = $matches[2];
							} else {
								$v['h'] = $matches[1];
								$v['i'] = $matches[2];
							}
						}
						if(preg_match("/([0-9]{1,2})([\s]*)(p\.?m\.?|a\.?m\.?)/i",$this->value,$matches)) {
							if($v['h'] == -1) $v['h'] = $matches[1];
							if(strtolower($matches[3][0]) == 'p') {
								if($v['h'] <= 12) $v['h'] += 12;
							} else {
								if($v['h'] == 12) $v['h'] -= 12;
							}
						}

					}
					$this->value = $this->units_array_to_str($v);
				}
			}
			$this->value = $this->str_to_units_array($this->value);
			if($c) $this->value['c'] = 1;
		}
		# Now we can assume we have an array of units values to verify
		$min = $this->str_to_units_array($this->get_param('min'));
		$max = $this->str_to_units_array($this->get_param('max'));
		$us = array('y','m','d','h','i','s');
		foreach($us as $u) {
			if($this->value[$u] >= 0) {
				if($min[$u] >= 0) $this->value[$u] = max($this->value[$u],$min[$u]);
				if($max[$u] >= 0) $this->value[$u] = min($this->value[$u],$max[$u]);
			}
		}
		# DO CIRCA STUFF HERE ON DOWN
		$validate_day_month = $this->value['y'] > 1970 && $this->value['y'] < 2038 && $this->value['m'] >= 0 && $this->value['d'] >= 0; # PHP limit
		$this->value = $this->units_array_to_str($this->value);

		# Ensures valid
		if($validate_day_month) {
			$this->value = date('Y-m-d',mysql_to_timestamp($this->value))
						 . substr($this->value,10);
		}
		if(strlen($this->value) > 20 && !$this->get_param('allow_circa')) {
			$this->value = substr($this->value,20);
		}
	}

	 ##################################
	# Returns the value as a timestamp
	function timestamp() {
		return mysql_to_timestamp($this->value);
	}

	 #################################################
	# Converts a datetime string "yyyy-mm-dd hh:ii:ss"
	# to an array with an int for each unit. -1 = 'null'
	function str_to_units_array(&$value) {
		$r = array();
		$r['y'] = ((($i = (int) ($s = substr($value,0,4)))  || ($s[0] != '-' && strlen($s))) ? $i : -1);
		$r['m'] = ((($i = (int) ($s = substr($value,5,2)))  || ($s[0] != '-' && strlen($s))) ? $i : -1);
		$r['d'] = ((($i = (int) ($s = substr($value,8,2)))  || ($s[0] != '-' && strlen($s))) ? $i : -1);
		$r['h'] = ((($i = (int) ($s = substr($value,11,2))) || ($s[0] != '-' && strlen($s))) ? $i : -1);
		$r['i'] = ((($i = (int) ($s = substr($value,14,2))) || ($s[0] != '-' && strlen($s))) ? $i : -1);
		$r['s'] = ((($i = (int) ($s = substr($value,17,2))) || ($s[0] != '-' && strlen($s))) ? $i : -1);
		if($this->get_param('allow_circa')) {
			$r['c'] = (substr($value,20,1)?1:0);
		}
		return $r;
	}

	 #################################################
	# Converts an array with an int for each unit
	# to a  datetime string "yyyy-mm-dd hh:ii:ss"
	function units_array_to_str(&$v) {
		$r = (($v['y'] < 0 || !strlen($v['y']) || $v['y'][0] == '-') ? '----' : sprintf('%04d', $v['y'])).'-'
            .(($v['m'] < 0 || !strlen($v['m']) || $v['m'][0] == '-') ?  '--'  : sprintf('%02d', $v['m'])).'-'
            .(($v['d'] < 0 || !strlen($v['d']) || $v['d'][0] == '-') ?  '--'  : sprintf('%02d', $v['d'])).' '
            .(($v['h'] < 0 || !strlen($v['h']) || $v['h'][0] == '-') ?  '--'  : sprintf('%02d', $v['h'])).':'
            .(($v['i'] < 0 || !strlen($v['i']) || $v['i'][0] == '-') ?  '--'  : sprintf('%02d', $v['i'])).':'
            .(($v['s'] < 0 || !strlen($v['s']) || $v['s'][0] == '-') ?  '--'  : sprintf('%02d', $v['s']));
		if($this->get_param('allow_circa')) $r .= ' '.($v['c']?1:0);
		return $r;
	}


	 ################################################
	# Uses date() to format a value in a given way
	function format($format='') {
		# We need to strip certain elements out of the format if we don't have enough
		# information about our datetime.
		# A mapping of which format elements are dependant on which time elements.
		$dep=array('h' => 'aABgGhHrU', 'd' => 'dDIjlOrSUwWzZ', 'm' => 'DFIlmMnOrtUwWzZ', 'y' => 'DlLrtUwWYyz', 'i' => 'irU', 's' => 'rsU');
		$es = '';
		foreach($v = $this->str_to_units_array($this->value) as $u => $n) {
			if($n < 0) {
				if($u == 'm' || $u == 'd') $$u = 1;
				else $$u = 0;
				$es .= $dep[$u];
			} else {
				$$u = $n;
			}
		}
		# Certain things we can't work out if the year is before 1970
		if($y < 1970 && !stristr($es,'y')) {
			$es .= 'DIlLrUwWzZ';
			$real_y = $y;
			$y      = 1980;
			$format = str_replace('Y','||||',$format);
			$format = str_replace('y','~~',$format);
		}
		if($es) {
			$regexp = "/[\\s]*[^a-zA-Z\\s]*[$es][^a-zA-Z\\|\{4\}\\~\{2\}\\s]*[\\s]*/";
			$format = preg_replace($regexp,' ',$format);
			if($real_y) {
				$format = str_replace('||||',$real_y,$format);
				$format = str_replace('~~',$real_y%100,$format);
			}
		}

		# Circa
		$format = str_replace('c',($c?'|':''),$format);
		$format = str_replace('C',($c?'~':''),$format);

		$format = date($format,mktime($h,$i,$s,$m,$d,$y));

		# Circa
		$format = str_replace('|','c.',$format);
		$format = str_replace('~','circa.',$format);

		return $format;
	}

	 #########################
	# Prints the edit options
	function print_edit_options() {
		$us = array('d'=>'Day','m'=>'Month','y'=>'Year','h'=>'Hour','i'=>'Min.','s'=>'Sec.');
		$s = &$this->get_param('show');
		$n = &$this->get_param('null');
		$y = &$this->get_param('style');
		$c = &$this->get_param('allow_circa');
		$t = &$this->get_param('standard');
		?>
		<table cellspacing=0 cellpadding=2 border=1>
			<tr><td><?=combo_box($this->prefix.'_allow_circa',array(''=>'','1'=>'Allow "circa."'),$c,'class=smallprint');?><br><?=combo_box($this->prefix.'_standard',array('int'=>'Intl. (dd/mm/yyyy)','us'=>'US (mm/dd/yyyy)'),$t,'class=fineprint');?></td><?foreach($us as $k=>$v) {?><td align=center><b><?=$v?></b></td><?}?></tr>
			<tr><td><b>Show</td><?foreach($us as $k=>$v) {?><td align=center><input type=checkbox name="<?=$this->prefix?>_show[]" value=<?=$k?> <?=((in_array($k,$s))?'checked':'')?>></b></td><?}?></tr>
			<tr><td><b>Allow blank</td><?foreach($us as $k=>$v) {?><td align=center><input type=checkbox name="<?=$this->prefix?>_null[]" value=<?=$k?> <?=((in_array($k,$n))?'checked':'')?>></b></td><?}?></tr>
			<tr><td><b>Select box</td><?foreach($us as $k=>$v) {?><td align=center><input type=radio name="<?=$this->prefix?>_style[<?=$k?>]" value=s <?=(($y[$k]=='s')?'checked':'')?>></b></td><?}?></tr>
			<tr><td><b>Text box</td><?foreach($us as $k=>$v) {?><td align=center><input type=radio name="<?=$this->prefix?>_style[<?=$k?>]" value=t <?=(($y[$k]=='t')?'checked':'')?>></b></td><?}?></tr>
			<tr><td><b>Min</td><td colspan=6><?$this->print_min_field()?></td></tr>
			<tr><td><b>Max</td><td colspan=6><?$this->print_max_field()?></td></tr>
		</table>
		<?
	}

	 #########################
	# Prints the edit options
	function process_edit_options() {
		$s = $_REQUEST[$this->prefix.'_show'];
		$n = $_REQUEST[$this->prefix.'_null'];
		$y = $_REQUEST[$this->prefix.'_style'];
		$c = $_REQUEST[$this->prefix.'_allow_circa'];
		$t = $_REQUEST[$this->prefix.'_standard'];
		$this->parameters['show']        = $s;
		$this->parameters['null']        = $n;
		$this->parameters['style']       = $y;
		$this->parameters['allow_circa'] = $c;
		$this->parameters['standard']    = $t;
		$this->process_min_field();
		$this->process_max_field();
	}

}

?>
