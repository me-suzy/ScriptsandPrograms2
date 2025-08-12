<?php
function fetchtopic($linkid, $nolink = 0){
	global $db, $dbprefix, $config;
	
	$loop = 1;
	$cur  = intval($linkid);
	$row  = 0;
	$etc1 = "";
	$etc2 = "";
	
	while($loop == 1 && $row < 100){
	
		$sql = "SELECT * FROM " . $dbprefix . "topics WHERE topicid = " . dbSecure($cur);
		$top = $db->execute($sql);
		if ($top->rows < 1){ return false; }
		
		if ($top->fields["parent"] == 0){ $loop = 0; }
		
		// set new variables
		$cur  = $top->fields["parent"];
		$etc2 = d2($top->fields["title"]) . "/" . $etc2;
		
		// add extra code
		if ($row == 0){
			$etc1 = $top->fields["title"];
		} else {
			$etc1 = $top->fields["title"] . ' ' . $config["breadcrumb"] . ' ' . $etc1;
		}
		
		$row++;
	
	}
	
	// post processing
	if ($nolink == 0){
		$etc = '<a href="' . $config["topicpage"] . $etc2 . '">' . $etc1 . '</a>';
	} else {
		$etc = $etc1;
	}
	
	return $etc;
}

function fetchoptions($parent, $exclude){
	global $db, $dbprefix, $config;
	
	if ($config["dropdownfullpaths"] == "true"){
		// new style, all topic things
		$loop = 1;
		$cur  = 0;
		$code  = "";
		
		// sub-function for getting lower beings
		function fetchchildtopics($cur, $parent, $exclude){
			global $db, $dbprefix, $config, $code;
			$sql = "SELECT * FROM " . $dbprefix . "topics WHERE parent = " . $cur . " ORDER BY title ASC";
			$erk = $db->execute($sql);
			
			if ($erk->rows > 0){ do {
				
				if ($erk->fields["topicid"] <> $exclude){
				
					// work out textual path
					$nexttitle = fetchtopic($erk->fields["topicid"], 1);
					
					// work out extra code
					if ($erk->fields["topicid"] == $parent){
						$xc = ' selected="selected"';
					} else {
						$xc = '';
					}
					
					// add code to selector box
					$code .= '<option value="' . $erk->fields["topicid"] . '"' . $xc . '>' . $nexttitle . '</option>';
					
					// get children of this one
					$code = fetchchildtopics($erk->fields["topicid"], $parent, $exclude);
				
				}
			
			} while ($erk->loop()); }
			
			// and return
			return $code;
		}
		
		// call the top function
		$code = fetchchildtopics(0, $parent, $exclude);
	
	} else {
		// old style, less db intensive
		$sql = "SELECT * FROM " . $dbprefix . "topics WHERE topicid <> " . $exclude . " ORDER BY title ASC";
		$tmp = $db->execute($sql);
		if ($tmp->rows > 0){ do {
		
			if ($tmp->fields["topicid"] == $parent){
				$xc = ' selected="selected"';
			} else {
				$xc = '';
			}
			
			// add in the new code
			$code .= '<option value="' . $tmp->fields["topicid"] . '"' . $xc . '>#' . $tmp->fields["topicid"] . ' - ' . $tmp->fields["title"] . '</option>';
		
		} while ($tmp->loop()); }
	}
	
	// and return code
	return $code;
}
?>