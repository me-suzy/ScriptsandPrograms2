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
## File: graph/graph.inc
## Desc: Class for generating graphs of numerical data
## $Source: /home/cvsroot/squizlib/graph/graph.inc,v $
## $Revision: 2.9 $
## $Author: gsherwood $
## $Date: 2003/03/24 04:21:05 $
#######################################################################
$SQUIZLIB_PATH = dirname(__FILE__)."/..";
# Requires cachableobject from squizlib
include_once("$SQUIZLIB_PATH/cache/cache.inc");
# Requires colour.inc from squizlib for rendering
include_once("$SQUIZLIB_PATH/colour/colour.inc");
#---------------------------------------------------------------------#


class Graph extends CacheableObject {
	
	  ######################
	 # instance-variables #
	######################
	 ########
	# Static
	var $NICE_COLOURS = array('coral', 'crimson', 'cornflowerblue', 'darkgreen', 'darkorchid', 'gold', 'indigo', 'darkmagenta', 'darkolivegreen', 'blue', 'red', 'darkcyan', 'darkorchid','darkorange', 'deeppink', 'maroon', 'mediumaquamarine', 'darkseagreen', 'firebrick', 'forestgreen', 'fuchsia', 'greenyellow', 'hotpink', 'indianred', 'khaki', 'lavender', 'limegreen', 'mediumorchid', 'mediumturquoise');

	

	# This specifies how to space out the values marked along the y-axis.
	var $RANGE_STEP_MAP = array(0=>1,1=>0.2,2=>0.2,3=>0.5,4=>0.5,5=>1,6=>1,7=>1,8=>1,9=>1);

	 ############
	# Variable

	# A cache of colours that have already been added to the image
	var $ADDED_COLOURS = array();

	# What sort of graph the data is displayed as.
	var $orientation = 'vertical';
	var $type        = 'column';

	# The preferred width in pixels of the graph
	var $preferred_width = 600;

	# Title and subtitle to be displayed
	var $title    = 'Graph';
	var $subtitle = '';

	# Label for the x axis, or more specifically, the subject. e.g 'Time', 'Day of the Week', or in a column graph of hits-per-page is would be 'page'.
	var $subject  = 'x';

	# The units the values are in (e.g. '','%','$','000's','sec','kph','L','light years per second'
	var $units = '';

	# The minimum and maximum values that have been entered to far
	var $min = false;
	var $max = false;

	# Layers. Layers are different value sets being measured on the same graph. For a hit report you might have 'hits,'visits','sessions' as separate layers. The key is the layer name, the value is an array of extra information.
	var $layers = array();

	# An associative array of values along the x axis. The key is the name, the value is an array of extra information about that candidate e.g 'short_name', 'url'.
	var $candidates = array();

	# Values is a two-dimentional array using the keys first of $layers, then $candidates to store numerical values for each candidate on each layer.
	# If blank, the value defaults to 0. It is illegal to have a layer or candidate key in the $values array that does not exist in the corresponding $candidates or $layers arrays.
	var $values = array();

	# The image pointer to in which things are rendered
	var $image = 0;

	# Colours
	var $bg_colour = 'eeeeee';

	# The bottom ($other_threshold * 100)% of candidates will be grouped together into an
	# "other" pseudocandidate in pie charts
	var $other_threshold = 0.05;
	
	  ###############
	 # Constructor #
	###############
	function Graph($type, $title, $subtitle,$preferred_width) {
		if ($type) $this->set_type($type);
		if ($title||$subtitle) $this->set_titles($title,$subtitle);
		if ($preferred_width) $this->preferred_width = $preferred_width;
	}	


	  #############################
	 # Set the Type of the graph #
	#############################
	function set_type($type) {
		$type = strtolower($type);
		switch($type) {
			case 'column': case 'bar': case 'line': case 'area': case 'stacked': case 'pie':
				$this->type = $type;
				break;
			default:
				$this->_set_error("Unknown graph type \"$type\" requested",__FILE__,__LINE__);
				return false;
		}
		return $this->type;
	}
	

	  ###########################################
	 # Set the Title and subtitle of the graph #
	###########################################
	function set_titles($title,$subtitle) {
		$this->title    = $title;
		$this->subtitle = $subtitle;
		return $this->title;
	}

	  ###################################
	 # Set the axis label of the graph #
	###################################
	function set_subject($subject) {
		return $this->subject = $subject;
	}

	  ###################################
	 # Set the units of the values     #
	###################################
	function set_units($units) {
		return $this->units = $units;
	}

	   ###########################################################
	  # Adds a layer to the array. This is appended to the end  #
	 # so the order in which this is repeatedly called counts  #
	###########################################################
	function add_layer($label,$minilabel,$url,$colour,$override_type) {
		$label = "$label";
		if (strlen($label)) {
			if(!$minilabel) $minilabel = $label;
			if ($this->layers[$label]) {
				$this->_set_error("Layer \"$label\" already exists in graph, cannot add.",__FILE__,__LINE__);
				return false;
			}
			$this->layers[$label]['minilabel'] = $minilabel;
			$this->layers[$label]['url'] = $url;
			if (!$colour) $colour = $this->NICE_COLOURS[count($this->layers)-1];
			$this->layers[$label]['colour'] = $colour;
			$this->layers[$label]['override_type'] = $override_type;
		} else {
			$this->_set_error("Attempt to add layer to graph with no label.",__FILE__,__LINE__);
			return false;
		}
		# Update values array
		$this->values[$label] = array();
		for(reset($this->candidates); strlen($c = key($this->candidates)); next($this->candidates)) {
			$this->values[$label][$c] = 0;
		}
		return $label;
	}
	
	 ######################
	# Removes a layer
	function remove_layer($label) {
		if ($this->layers[$old_label]) {
			unset($this->layers[$old_label]);
		} else {
			$this->_set_error("Unable to remove layer \"$label\"; does not exist.",__FILE__,__LINE__);
			return false;
		}
		# Update values array
		unset($this->values[$label]);
		return $label;
	}


	   ##############################################################
	  # Adds a candidate to the array. This is appended to the end #
	 # so the order in which this is repeatedly called counts     #
	##############################################################
	function add_candidate($label,$minilabel,$url,$colour) {
		$label = "$label";
		if (strlen($label)) {
			if(!$minilabel) $minilabel = $label;
			if ($this->candidates[$label]) {
				$this->_set_error("WARNING: Candidate \"$label\" already exists in graph.",__FILE__,__LINE__);
				return false;
			}
			$this->candidates[$label]['minilabel'] = $minilabel;
			$this->candidates[$label]['url'] = $url;
			if (!$colour) $colour = $this->NICE_COLOURS[count($this->candidates)-1];
			$this->candidates[$label]['colour'] = $colour;
		} else {
			$this->_set_error("Attempt to add candidate to graph with no label.",__FILE__,__LINE__);
			return false;
		}
		# Update values array
		for(reset($this->layers); strlen($l = key($this->layers)); next($this->layers)) {
			$this->values[$l][$label] = 0;
		}
		return $label;
	}

	 ######################
	# Removes a layer
	function remove_candidate($label) {
		if ($this->candidates[$old_label]) {
			unset($this->candidates[$old_label]);
		} else {
			$this->_set_error("Unable to remove candidate \"$label\"; does not exist.",__FILE__,__LINE__);
			return false;
		}
		# Update values array
		for(reset($this->layers); strlen($l = key($this->layers)); next($this->layers)) {
			unset($this->values[$l][$label]);
		}
		return $label;
	}

	  
	  ########################################################
	 # Sets a value as specified layer/candidate coordinate #
	########################################################
	function set_value($layer,$candidate,$value) {
		if(isset($this->values[$layer])) {
			if(isset($this->values[$layer][$candidate])) {
				$this->values[$layer][$candidate] = $value;
			} else {
				$this->_set_error("Unable to set value for layer \"$layer\", candidate \"$candidate\"; candidate does not exist.",__FILE__,__LINE__);
				return false;
			}
		} else {
			$this->_set_error("Unable to set value for layer \"$layer\"; layer does not exist.",__FILE__,__LINE__);
			return false;
		}
		return true;
	}

	 ###################################################
	# Set the minium and maximum values to be displayed
	function set_limits($min=false,$max=false) {
		$this->min = $min;
		$this->max = $max;
	}

	 ############################################
	# Find the minimum value (for a layer or all)
	function _min_value($layer) {
		reset ($this->values);
		while(list($l,$cans) = each($this->values)) {
			if ($layer && $layer != $l) continue;
			while(list($c,$val) = each($cans)) {
				if (!isset($min) || $val < $min) $min = $val;
			}
		}
		return $min;
	}
	 ############################################
	# Find the maximum value (for a layer or all)
	function _max_value($layer) {
		reset ($this->values);
		while(list($l,$cans) = each($this->values)) {
			if ($layer && $layer != $l) continue;
			while(list($c,$val) = each($cans)) {
				if (!isset($max) || $val > $max) $max = $val;
			}
		}
		return $max;
	}
	 ####################################################
	# Find the total of the values in a particular layer
	function _layer_total($layer) {
		$total = 0;
		reset ($this->values);
		while(list($l,$cans) = each($this->values)) {
			if ($layer && $layer != $l) continue;
			while(list($c,$val) = each($cans)) {
				$total += $val;
			}
		}
		return $total;
	}
	 ##################################################
	# Find the total of all the values for a candidate
	function _candidate_total($candidate) {
		$total = 0;
		reset ($this->values);
		while(list($l,$cans) = each($this->values)) {
			while(list($c,$val) = each($cans)) {
				if ($candidate && $candidate != $c) continue;
				$total += $val;
			}
		}
		return $total;
	}
	 ##########################################################
	# Find the minimum total of all the values for a candidate
	function _min_candidate_total() {
		for(reset($this->candidates);strlen($c=key($this->candidates));next($this->candidates)) {
			$total = $this->_candidate_total($c);
			if (!isset($min) || $total < $min) $min = $total;
		}
		return $min;
	}
	 ##########################################################
	# Find the maximum total of all the values for a candidate
	function _max_candidate_total() {
		for(reset($this->candidates);strlen($c=key($this->candidates));next($this->candidates)) {
			$total = $this->_candidate_total($c);
			if (!isset($max) || $total > $max) $max = $total;
		}
		return $max;
	}
	 ####################################
	# Returns the magnitude of a number
	function _mag($n) {
		if ($n == 0) return $n;
		$mag = 0;
		$n = abs($n);
		if ($n >= 1) {
			while($n >= 1) {$mag++; $n /= 10;}
		} else {
			while($n < 0.1) {$mag--; $n *= 10;}
		}
		return $mag;
	}
	 ################################################
	# Returns the most significant digit of a number
	function _msd($n) {
		if ($n == 0) return $n;
		$n = abs($n);
		if ($n > 1) {
			while($n >= 10) $n /= 10;
		} else {
			while($n < 1) $n *= 10;
		}
		return (int) $n;
	}

	  ##################################
	 # Renders the data into an image #
	##################################
	function render() {
		$title_height      = 40;  # At the top
		$copyright_height  = 0;   # At the bottom
		$axis_label_height = 20;  # Markers for either label

		# The width of the entire image - nice for an A4 page
		$image_width   = $this->preferred_width;

		# The height of the rectangle which contains the graph
		if (in_array($this->type,array('column','line','area'))) {
			$graph_height  = 300;
		} elseif ($this->type == 'stacked') {
			$graph_height  = 250 + 25 * count($this->layers);
		} elseif($this->type == 'bar') {
			$bar_height    = max((int) (100 / pow(count($this->candidates), 0.5)), 15);
			$graph_height  = count($this->candidates) * $bar_height;
		} elseif($this->type == 'pie') {
			$pie_count = count($this->layers);
			$graph_height = ($image_width - 100) * $pie_count;
		}

		# And soo...
		$x_axis_offset = $title_height + $graph_height;

		# Right, now lets figure out how much space we need at the bottom
		# How much space are the candidate labels going to take?
		for(reset($this->candidates); strlen($label = key($this->candidates)); next($this->candidates)) {
			$label = (($this->candidates[$label]['minilabel'])?$this->candidates[$label]['minilabel']:$label);
			$max_candidate_label_length = max(strlen($label)*6,$max_candidate_label_length);
		}

		# So now we can figure out how pushed in the y-axis will be
		if (in_array($this->type,array('column','stacked','line','area'))) {
			$y_axis_offset = $axis_label_height + 20;
		} elseif ($this->type == 'bar') {
			$y_axis_offset = $axis_label_height + 5 + $max_candidate_label_length + 5;
		} elseif($this->type == 'pie') {
			$y_axis_offset = 0;
		}

		# And soo....
		$graph_width   = $image_width - $y_axis_offset;

		# How much space are the layer labels going to take
		for(reset($this->layers); strlen($label = key($this->layers)); next($this->layers)) {
			$label = (($this->layers[$label]['minilabel'])?$this->layers[$label]['minilabel']:$label);
			$max_layer_label_length = max(strlen($label)*6,$max_layer_label_length);
		}

		# The labels at the bottom.. candidates if Pie, layers otherwise
		if (in_array($this->type,array('column','stacked','line','area','bar'))) {
			$layer_labels_per_line = max(1,floor($graph_width / ($max_layer_label_length+20)));
			$layer_label_lines     = ceil(count($this->layers)/$layer_labels_per_line);
			$layer_labels_height   = $layer_label_lines * 15;
			$labels_height = $layer_labels_height;
		} elseif($this->type == 'pie') {
			$candidate_labels_per_line = max(1,floor($graph_width / ($max_candidate_label_length+20)));
			$candidate_label_lines     = ceil(count($this->candidates)/$candidate_labels_per_line);
			$candidate_labels_height   = $candidate_label_lines * 15;
			$labels_height = $candidate_labels_height;
		}

		# And so...
		$image_height = $x_axis_offset 
						+ 5 
						+ ((in_array($this->type,array('column','stacked','line','area')))?$max_candidate_label_length:0)
						+ (($this->type == 'bar')?20:0)
						+ 5
						+ $axis_label_height
						+ 5
						+ $labels_height 
						+ $copyright_height;

		# Create the image
		if (!$this->image = imageCreate($image_width,$image_height)) {
			$this->_set_error("Unexpected error, unable to create image for rendering.",__FILE__,__LINE__);
			return false;
		}

		# Render background
		$this->_rect(0, 0, $image_width - 1, $image_height - 1, $this->bg_colour, black, 1);

		# Print title and subtitle
		$this->_rect(1, 1, $image_width - 2, 30, Colour::twice_as_dark($this->bg_colour));
		$this->_string(5,5,2,$this->title,white);
		$this->_string(2,5,16,$this->subtitle,white);

		# Put value markers up the side (column) or across the bottom (row)..
		
		# Get some important statistical data
		if ($this->type == "stacked") {
			$min = false;
			$max = false;
			# Make sure to ignore layers which are not stacked
			reset($this->layers);
			while(list($layer_name,$layer_info) = each($this->layers)) {
				if (!$layer_info['override_type'] || $layer_info['override_type'] == "stacked") {
					for(reset($this->candidates);strlen($c=key($this->candidates));next($this->candidates)) {
						$candidate_totals[$c] += $this->values[$layer_name][$c];
					}
				}
			}
			while(list($c,$stack_total) = each($candidate_totals)) {
				$min = (($min === false)?$stack_total:min($min,$stack_total));
				$max = (($max === false)?$stack_total:max($max,$stack_total));
			}
		} else {
			$min = $this->_min_value();
			$max = $this->_max_value();
		}
		$min = (($this->min === false)?$min:$this->min);
		$max = (($this->max === false)?$max:$this->max);
		if ($min > $max) {$tmp = $max; $max = $min; $min = $tmp;}
		$range = $max - $min;

		# Get the magnitude and most significant digit of the range
		$mag  = $this->_mag($range);
		$msd  = $this->_msd($range);

		# Figure out the amount to step up each label
		$step = $this->RANGE_STEP_MAP[$msd] * pow(10,$mag-1);

		# Pad the min and max to nice values
		if ($max % $step) 
			$max = $max + ($step - ($max % $step));
		if ($min % $step)
			$min = $min - ($min % $step);

		# Units description - for things like "000's" or "000,000's"
		$top_mag = max($this->_mag($min), $this->_mag($max));
		if ($top_mag > 3) { # For very large numbers
			$thousands = (int) (($top_mag - 1) / 3);
			if     ($thousands == 1) $units = "thousand";
			elseif ($thousands == 2) $units = "million";
			else {
				$units = "000,000";
				for($i = 2; $i < $thousands; $i++) $units .= ",000";
			}
			$min  /= pow(10,$thousands*3);
			$max  /= pow(10,$thousands*3);
			$step /= pow(10,$thousands*3);

			# Remember what we did
			$mag_adjust = $thousands*3;
		}
		$units = (($units)?$units."s $this->units":$this->units);

		if ($min > $max) {
			$this->_set_error("Graph minimum is greater than graph maxiumum",__FILE__,__LINE__);
			return false;
		}

		# Generate an array of value tags
		for($i = $min; $i <= $max; $i += $step) $value_tags[] = ereg_replace("\.0$",'',sprintf('%.1f',$i));

		# For pie charts, work out the candidate proportions
		if($this->type == 'pie') {
			$layer_totals = array();
			$proportions  = array();
			for(reset($this->layers); $layer = key($this->layers); next($this->layers)) {
				$total = 0;
				for(reset($this->candidates); strlen($candidate = key($this->candidates)); next($this->candidates)) {
					$total += $this->values[$layer][$candidate];
				}
				$layer_totals[$layer] = $total;
				for(reset($this->candidates); strlen($candidate = key($this->candidates)); next($this->candidates)) {
					if(abs($total) != 0) {
						$proportions[$layer][$candidate] = abs($this->values[$layer][$candidate]) / abs($total);
					} else {
						$proportions[$layer][$candidate] = 0;
					}
				}
			}
		}

		#echo "$range: [$min - $max] $mag, $msd, $step, ".array_contents($value_tags);
	
		if (in_array($this->type,array('column','stacked','line','area','bar'))) {
			# Draw the axes 
			$this->_line($y_axis_offset, $title_height, $y_axis_offset, $x_axis_offset, black);
			$this->_line($y_axis_offset, $x_axis_offset, $image_width-1, $x_axis_offset, black);

			# Label the axises
			if (in_array($this->type,array('column','stacked','line','area'))) {
				$y_label        = $units;
				$x_label_height = $max_candidate_label_length;
				$x_label        = $this->subject;
			} elseif ($this->type == 'bar') {
				$x_label        = $units;
				$x_label_height = 20;
				$y_label        = $this->subject;
			}
			$y_label_x = 5;
			$y_label_y = $title_height + ($graph_height / 2) + ((strlen($y_label) * 5) / 2);
			$this->_string_up(3, $y_label_x, $y_label_y, $y_label, black);

			$x_label_x = $y_axis_offset + ($graph_width / 2) - ((strlen($x_label) * 5) / 2);
			$x_label_y = $x_axis_offset + 5 + $x_label_height + 5;
			$this->_string(3, $x_label_x, $x_label_y, $x_label, black);

			# Mark tags along the axes - slight alteration to stop things going over the edge
			$axis_width  = $graph_width - 5;
			$axis_height = $graph_height;

			# Mark the value tags
			$tag_count   = count($value_tags);
			if (in_array($this->type,array('column','stacked','line','area'))) {
				$tag_space   = $axis_height / ($tag_count - 1);
				for($i = 0; $i < $tag_count; $i++) {
					$tag_ypos = $x_axis_offset - $tag_space * $i;
					$this->_line($y_axis_offset,$tag_ypos, $y_axis_offset-2, $tag_ypos, black);
					$this->_line($y_axis_offset,$tag_ypos, $y_axis_offset + $graph_width, $tag_ypos, Colour::adjust_brightness($this->bg_colour,-0.2));
					$this->_string(1, $y_axis_offset-strlen($value_tags[$i])*5-4, $tag_ypos-4, $value_tags[$i], black);
				}
			} elseif ($this->type == 'bar') {
				$tag_space   = $axis_width / ($tag_count - 1);
				for($i = 0; $i < $tag_count; $i++) {
					$tag_xpos = $y_axis_offset + $tag_space * $i;
					$this->_line($tag_xpos, $x_axis_offset, $tag_xpos, $x_axis_offset + 2, black);
					$this->_line($tag_xpos, $x_axis_offset, $tag_xpos, $x_axis_offset - $graph_height, Colour::adjust_brightness($this->bg_colour,-0.2));
					$this->_string_up(1, $tag_xpos - 4, $x_axis_offset + strlen($value_tags[$i]) * 5 + 4,$value_tags[$i],black);
				}
			}


			# Mark the candidate tags
			$candidate_count = count($this->candidates);
			if (in_array($this->type,array("column","stacked","line","area"))) {
				$candidate_space = floor($axis_width  / $candidate_count);
			} elseif ($this->type == "bar") {
				$candidate_space = floor($axis_height / $candidate_count);
			}

			reset($this->candidates);
			for($i = 0; $i < $candidate_count; $i++) {
				list($candidate_label,$candidate_info) = each($this->candidates);
				$candidate_minilabel = $candidate_info['minilabel'];
				if (in_array($this->type,array("column","stacked","line","area"))) {
					if(in_array($this->type,array("column","stacked")))  {
						$candidate_xpos = $y_axis_offset + 1 + ($i+1) * $candidate_space - ($candidate_space / 2);
					} elseif(in_array($this->type,array("line","area")))  {
						$candidate_xpos = $y_axis_offset + 1 + ($i+1) * $candidate_space - $candidate_space + ($candidate_space * ($i/$candidate_count));
					}
					$this->_line($candidate_xpos,$x_axis_offset,$candidate_xpos,$x_axis_offset + 3,black);
					$this->_string_up(2, $candidate_xpos - 6, $x_axis_offset + strlen($candidate_minilabel) * 6 + 5,$candidate_minilabel, black);
				} elseif ($this->type == "bar") {
					$candidate_ypos = $title_height + 1 + ($i+1) * $candidate_space - ($candidate_space / 2);
					$this->_line($y_axis_offset, $candidate_ypos, $y_axis_offset - 3, $candidate_ypos, black);
					$this->_string(2, $y_axis_offset - strlen($candidate_minilabel) * 6 - 5, $candidate_ypos - 6,  $candidate_minilabel, black);
				}

				# And now, at last, the columns, bars, line and areas
				reset($this->layers);
				$layer_count = count($this->layers);
				$layer_no = 0;
				$total_stack_offset = 0;
				while(list($layer_label,$layer_info) = each($this->layers)) {
					$layer_type = (($layer_info['override_type'])?$layer_info['override_type']:$this->type);
					$value = $this->values[$layer_label][$candidate_label];
					$adjusted_value = min((($mag_adjust)?$value*pow(10,-$mag_adjust):$value),$max);
					if(in_array($layer_type,array("column","stacked")))  {
						if ($layer_type == "column") {
							$x1 = $candidate_xpos - ($candidate_space/2) + 1 + $layer_no * 3;
							$x2 = $candidate_xpos + ($candidate_space/2) - 1 - $layer_count * 3 + $layer_no * 3;
						} elseif($layer_type == "stacked") {
							$x1 = $candidate_xpos - ($candidate_space/2) + 2;
							$x2 = $candidate_xpos + ($candidate_space/2) - 2;
						}
						$stack_offset = $axis_height * ($adjusted_value / $max);
						if ($layer_type == "column") {
							$y1 = $x_axis_offset - $stack_offset;
							$y2 = $x_axis_offset;
						} elseif($layer_type == "stacked") {
							$y1 = $x_axis_offset - round($stack_offset + $total_stack_offset);
							$y2 = $x_axis_offset - round($total_stack_offset);
							$total_stack_offset += $stack_offset;
						}
						$this->_rect($x1,$y1,$x2,$y2,$layer_info['colour'],black,1);
					} elseif (in_array($layer_type, array("line","area"))) {
						$x = $candidate_xpos;
						$y = $x_axis_offset - round($axis_height * ($adjusted_value / $max));
						$this->_circle($x,$y,5,$layer_info['colour']);
						if ($old_x[$layer_label] && $old_y[$layer_label]) {
							if ($layer_type == "line") {
								$this->_line($old_x[$layer_label], $old_y[$layer_label], $x, $y, $layer_info[colour]);
								#$this->_line($old_x[$layer_label]+1, min($old_y[$layer_label]+1,$x_axis_offset), $x+1, min($y+1,$x_axis_offset), $layer_info[colour]);
							} elseif($layer_type == "area") {
								$polypoints = array($old_x[$layer_label], $old_y[$layer_label], $x, $y, $x, $x_axis_offset, $old_x[$layer_label], $x_axis_offset);
								$this->_polygon($polypoints,4,$layer_info['colour']);
								$this->_line($old_x[$layer_label], $old_y[$layer_label], $x, $y, black);
							}
						}
						# Remember old stuff
						$old_x[$layer_label] = $x;
						$old_y[$layer_label] = $y;
					} elseif ($this->type == "bar") {
						$x1 = $y_axis_offset;
						$x2 = $y_axis_offset + round($axis_width * ($adjusted_value / $max));
						$y1 = $candidate_ypos - ($candidate_space/2) + 1 + $layer_no * 3;
						$y2 = $candidate_ypos + ($candidate_space/2) - 1 - $layer_count * 3 + $layer_no * 3;
						$this->_rect($x1,$y1,$x2,$y2,$layer_info['colour'],black,1);
					}
					$layer_no++;
				}
			}

			# Draw the axes - again !  (because they get messed up by the value label lines
			$this->_line($y_axis_offset, $title_height, $y_axis_offset, $x_axis_offset, black);
			$this->_line($y_axis_offset, $x_axis_offset, $image_width-1, $x_axis_offset, black);

		} elseif($this->type == 'pie') {
			# Draw a pie graph for every layer
			$layer_count = 0;
			reset($this->layers);
			while(list($layer_label,$layer_info) = each($this->layers)) {
				$radius = $image_width / 2 - 100;
				$x = $image_width  / 2;
				$y = $title_height + ($radius*2 + 100) * ($layer_count + 0.5);
				$proportion_sum = 0;
				reset($this->candidates);
				while(list($candidate_label,$candidate_info) = each($this->candidates)) {
					$proportion = &$proportions[$layer_label][$candidate_label];
					if($proportion > 0) {
						$this->_segment($x,$y,$radius, $proportion_sum*360, ($proportion_sum+$proportion)*360, $candidate_info['colour'],black);
					}
					$proportion_sum += $proportion;
				}
				$this->_string(4,$x - strlen($layer_label)/2*8,$y + $radius + 35,$layer_label,$colour="black");
				$layer_count++;
			}
		}

		
		# Now draw the Layer labels
		if (in_array($this->type,array('column','stacked','line','area','bar'))) {
			$layer_no = 0;
			reset($this->layers);
			while(list($layer_label,$layer_info) = each($this->layers)) {
				$label = (($layer_info['minilabel'])?$layer_info['minilabel']:$layer_label);
				$line_no = floor($layer_no / $layer_labels_per_line);
				$x = $y_axis_offset + ($layer_no % $layer_labels_per_line) * (20 + $max_layer_label_length) + 5;
				$y = $x_axis_offset + 5 + $x_label_height + $axis_label_height + 5 + $line_no * 15;
				$this->_rect($x+7,$y+2,$x+16,$y+11,$layer_info['colour'],black,1);
				$this->_string(2, $x + 20, $y, $label, black);
				$layer_no++;
			}
		} elseif($this->type == 'pie') { # Or the candidate labels
			$candidate_no = 0;
			reset($this->candidates);
			while(list($candidate_label,$candidate_info) = each($this->candidates)) {
				$label = (($candidate_info['minilabel'])?$candidate_info['minilabel']:$candidate_label);
				$line_no = floor($candidate_no / $candidate_labels_per_line);
				$x = $y_axis_offset + ($candidate_no % $candidate_labels_per_line) * (20 + $max_candidate_label_length) + 5;
				$y = $x_axis_offset + 5 + $x_label_height + $axis_label_height + 5 + $line_no * 15;
				$this->_rect($x+7,$y+2,$x+16,$y+11,$candidate_info['colour'],black,1);
				$this->_string(2, $x + 20, $y, $label, black);
				$candidate_no++;
			}
		}

		# Tack on a copyright notice
		#$copyright_notice = "Graph Generator (c) Copyright Squiz.net Pty Ltd ".date("Y");
		#$this->_string(2, $image_width - strlen($copyright_notice) * 6 - 2, $image_height - 15,$copyright_notice, Colour::twice_as_dark($this->bg_colour));
	}


	 ###############################
	# Draw a rectangle on our image
	function _rect($x1,$y1,$x2,$y2,$colour = "white", $border_colour, $border_width = 1) {
		# Convert doubles to ints
		$x1 = round($x1);$x2 = round($x2);
		$y1 = round($y1);$y2 = round($y2);
		# Ensure 1's are lefter and higher than 2's
		if($x1>$x2) {$tmp = $x2; $x2 = $x1; $x1 = $tmp;}
		if($y1>$y2) {$tmp = $y2; $y2 = $y1; $y1 = $tmp;}
		$c = $this->_add_colour($colour);
		imageFilledRectangle($this->image, $x1, $y1, $x2, $y2, $c); 
		if ($border_colour) {
			$c = $this->_add_colour($border_colour);
			for ($i = 0; $i < $border_width; $i++) {
				ImageLine($this->image, $x1+$i, $y1+$i, $x1+$i, $y2-$i, $c); 
				ImageLine($this->image, $x1+$i, $y2-$i, $x2-$i, $y2-$i, $c); 
				ImageLine($this->image, $x2-$i, $y2-$i, $x2-$i, $y1+$i, $c); 
				ImageLine($this->image, $x2-$i, $y1+$i, $x1+$i, $y1+$i, $c); 
			}
		}
	}

	 ###########################
	# Draws a line on our image
	function _line($x1,$y1,$x2,$y2,$colour = "black") {
		$c = $this->_add_colour($colour);
		ImageLine($this->image, $x1, $y1, $x2, $y2, $c); 
	}

	 ###########################
	# Draws a polygon on our image
	function _polygon($points,$num_points,$colour = "black") {
		$c = $this->_add_colour($colour);
		ImageFilledPolygon($this->image,$points,$num_points,$c); 
	}

	 ###########################
	# Draws a circle on our image
	function _circle($x,$y,$radius,$colour = "black") {
		$c = $this->_add_colour($colour);
		ImageArc($this->image,$x,$y,$radius,$radius,0,360,$c); 
	}

	 #################################
	# Draws a pie segment on our image
	function _segment($x,$y,$radius,$from,$to,$colour='black',$border_colour) {
		$cf = $this->_add_colour($colour);
		if($border_colour)
			$cb = $this->_add_colour($border_colour);
		else
			$cb = $cf;
		#$from %= 360.0;
		#$to   %= 360.0;
		$this->_line($x,$y,$x+$radius*cos(deg2rad($from)),$y+$radius*sin(deg2rad($from)),$cb);
		$this->_line($x,$y,$x+$radius*cos(deg2rad($to)),$y+$radius*sin(deg2rad($to)),$cb);
		$med = ($from + $to) / 2;
		ImageArc($this->image,$x,$y,$radius*2,$radius*2,$from,$to+1,$cb);
		#$this->_circle($x+$radius*cos(deg2rad($med))/2, $y+$radius*sin(deg2rad($med))/2,50);
		ImageFill($this->image, $x+$radius*cos(deg2rad($med))/2, $y+$radius*sin(deg2rad($med))/2, $cf);
	}


	 ##############################
	# Writes a string to the image
	function _string($font_size=2,$x=0,$y=0,$str="",$colour="black") {
		$c = $this->_add_colour($colour);
		imageString($this->image, $font_size,$x,$y,$str,$c);
	}

	 ####################################
	# Writes a string to the image - up!
	function _string_up($font_size=2,$x=0,$y=0,$str="",$colour="black") {
		$c = $this->_add_colour($colour);
		imageStringUp($this->image, $font_size,$x,$y,$str,$c);
	}

	 ###################################
	# Adds or finds a colour in the image palette
	function _add_colour($html_code) {
		$c_int = Colour::html_to_int($html_code);
		if (isset($this->ADDED_COLOURS[$c_int])) {
			return $this->ADDED_COLOURS[$c_int];
		} else {
			$c_rgb = Colour::html_to_rgb($html_code);
			$c = imageColorAllocate($this->image, 255*$c_rgb['r'],255*$c_rgb['g'],255*$c_rgb['b']);
			$this->ADDED_COLOURS[$c_int] = $c;
			return $c;
		}
	}

	  ###################################################
	 # Prints the image out, complete with HTTP header #
	###################################################
	function print_image($type = 'png') {
		if (!$this->image) {
			$this->_set_error("Unable to print graph image. Image not rendered.",__FILE__,__LINE__);
			return false;
		}
		switch($type) {
			case 'gif':
				header("Content-type: image/gif");
				imageGIF($this->image);
				break;
			case 'jpg': case 'jpeg':
				header("Content-type: image/jpeg");
				imageJPEG($this->image);
				break;
			case 'png': default:
				header("Content-type: image/png");
				imagePNG($this->image);
				break;
		}
	}

	  
	  ################################################
	 # Saves the currently rendered graph to a file #
	################################################
	function save_image($filename) {
		if (!$this->image) {
			$this->_set_error("Unable to save graph image \"$filename\". Image not rendered.",__FILE__,__LINE__);
			return false;
		}
		$type = get_file_type($filename);
		switch($type) {
			case 'gif':
				if (!imageGIF($this->image,$filename)) {
					$this->_set_error("Unable to save graph image \"$filename\". Unable to write to file.",__FILE__,__LINE__);
					return false;
				}
				break;
			case 'jpg': case 'jpeg':
				if (!imageJPEG($this->image,$filename)) {
					$this->_set_error("Unable to save graph image \"$filename\". Unable to write to file.",__FILE__,__LINE__);
					return false;
				}
				break;
			case 'png': default:
			if (strtolower(substr($filename,-3)) != "png") $filename = "$filename.png";
			if (!imagePNG($this->image,$filename)) {
					$this->_set_error("Unable to save graph image \"$filename\". Unable to write to file.",__FILE__,__LINE__);
					return false;
				}
				break;
		}
	}


	  ###################################################
	 # Print out the data in the graph as a HTML table #
	###################################################
	function print_table() {
		$column_count = 1 + count($this->layers);
		$row_count    = 1 + count($this->candidates) + 1;
		$table = "<table border=1 cellpadding=2 cellspacing=0 width=600>";
		#heading
		$table .= "<tr bgcolor=".Colour::twice_as_dark($this->bg_colour)."><td colspan=$column_count><p style=\"color: white; font-family: sans-serif; font-size: 14px; font-weight: bold;\">$this->title<br><span style=\"font-size:12px; font-weight=normal;\">$this->subtitle</p></td></tr>";

		# Column layer headers
		reset($this->layers);
		$table .= "<tr bgcolor=$this->bg_colour><td align=center><p style=\"font-family: sans-serif; font-size: 10pt; font-weight: bold;\">$this->subject</p></td>";
		while(list($layer_name,$layer_info) = each($this->layers)) {
			if($layer_info['minilabel']) $label = $layer_info['minilabel'];
			else $label = $layer_name;
			$mins[$layer_name] = $this->_min_value($layer_name);
			$maxs[$layer_name] = $this->_max_value($layer_name);
			$table .= "<td align=center bgcolor=$layer_info[colour]>";
			if ($layer_info['url']) $table .= "<a href=\"$layer_info[url]\">";
			$table .= "<p style=\"color: ".Colour::contrasting_shade($layer_info['colour'])."; font-family: sans-serif; font-size: 10pt; font-weight: bold;\">$label</p>";
			if ($layer_info[url]) $table .= "</a>";
			$table .= "</td>";
		}

		# Now go through all the candidates and print a row for each
		reset($this->candidates);
		while(list($candidate_name,$candidate_info) = each($this->candidates)) {
			if($candidate_info['minilabel']) $label = $candidate_info['minilabel'];
			else $label = $candidate_name;
			$table .= "<tr bgcolor=$this->bg_colour><td align=left><p style=\"font-family: sans-serif; font-size: 10pt; font-weight: bold;\">$label</p></td>";

			reset($this->layers);
			while(list($layer_name,$layer_info) = each($this->layers)) {
				$value = $this->values[$layer_name][$candidate_name];
				$table .= "<td align=right>";
				if ($value == $this->_max_value($layer_name)) {
					$colour = "red";
				} elseif ($value == $this->_min_value($layer_name)) {
					$colour = "blue";
				} else {
					$colour = "black";
				}
				if($value == (int) $value) {
					$value = number_format($value);
				} else {
					$value = number_format($value,2);
				}
				$table .= "<p style=\"color: $colour; font-family: courier; font-size: 10pt;\">$value</p>";
				$table .= "</td>";
			}

			$table .= "</tr>";

		}

		# Print the totals
		$table .= "<tr bgcolor=$this->bg_colour><td align=left><p style=\"font-family: sans-serif; font-size: 10pt; font-weight: bold;\">TOTAL</p></td>";
		reset($this->layers);			
		while(list($layer_name,$layer_info) = each($this->layers)) {
			$value = $this->_layer_total($layer_name);
			if($value == (int) $value) {
				$value = number_format($value);
			} else {
				$value = number_format($value,2);
			}
			$table .= "<td align=right>";
			$table .= "<p style=\"font-family: courier; font-size: 10pt; font-weight: bold;\">$value</p>";
			$table .= "</td>";
		}
		$table .= "</tr>";

		$table .= "</table>";
		return $table;
	}

}

?>
