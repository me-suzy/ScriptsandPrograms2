<?php
	//Revised on May 21 2005
	//Revised by Jason Farrell
	//Revision Number 4

	//functional constant definition
	define('MAX_LENGTH_BLOB', 300);
	define('HIGHLIGHT_COLOR', '#FFFF00');
	
	$path = getcwd();
	chdir ('../');
	include_once "./includes/classes.php";
	include_once "./includes/constants.php";
	include_once "./includes/settings.php";
	chdir($path);

	//Function Definitons Page
	
	//user redirect function
	function checkKBVisibility()
	{
		global $OBJ;
		if (isset($_SESSION['enduser'])) {
			//they are logged in, so it depends
			$u = unserialize($_SESSION['enduser']);
			
			if (!$OBJ->get('show_kb', 'intval') && $u->get('securityLevel', 'intval') == ENDUSER_SECURITY_LEVEL)
				header("Location: ../index.php");
		}
		else {
			//since they are not logged in we treat them as a normal user
			if (!$OBJ->get('show_kb', 'intval'))
				header("Location: ../index.php");	
		}
		return true;
	}
	
	//second argument MUST be an array, first must be a string
	function generateBlob($str, $keys = null)
	{
		$min = strlen($str) - 1;
		$pos = 0;
		if (!is_null($keys))
		{
			foreach ($keys as $index)
			{
				$pos = (strpos($str, $index) > $min) ? $min : strpos($str, $index);
			}
		}
		
		$start = ($pos - 150) < 0 ? 0 : $pos - 150;
		$str = substr($str, $start, MAX_LENGTH_BLOB);
		
		if (!is_null($keys)) $str = preg_replace('/(' . implode('|', $keys) . ')/i', '<span style="background-color:' . HIGHLIGHT_COLOR . '">\0</span>', $str);
		return $str;
	}
	
	/*
		This function is responsible for sorting the array data - it does not need to go past the parent level
		Condition 1: Presence of Key words
		Condition 2: Sum of Key words found
		Condition 3: Page Views
	*/
	function keyCmp($arr1, $arr2)
	{
		$count1 = 0;
		$count2 = 0;
		#print "<pre>";
		#echo "Arr1:\n";
		#print_r($arr1);
		#echo "\n";
		#echo "Arr2:\n";
		#print_r($arr2);
		#print "</pre>";
		
		//count array element 1
		foreach ($arr1['keys'] as $index)
		{
			if ($index) $count1++;
		#	echo $index . "\n";
		}
		
		#echo "<br/>\n";
		
		foreach ($arr2['keys'] as $index)
		{
			if ($index) $count2++;
		#	echo $index . "\n";
		}
		#var_dump(is_array($arr1['keys']));
		#echo "Count1: "; var_dump($count1);
		#echo "\n";
		#echo "Count2: "; var_dump($count2);
		#exit;	
			
		//now compare - if count2 is greater then count1 return -1, if greater then return 1, otherwise continue on
		if ($count1 < $count2) {
			#echo "returning 1 - 1";
	 		return 1;
		}
		elseif ($count2 > $count1) {
			#echo "returning -1 - 1";
			return -1;
		}
		else {
			//since the same numbebr of keys was found - now we shall compare the totals
			//if arr1['keys_found'] is greater then arr2's we shall return return 1
			//if arr2['keys_found'] is greater then arr1's we shall return return -1
			//else we will move on the final comparison check
			if ($arr1['keys_found'] > $arr2['keys_found']) {
				#echo "returning -1 - 2";
				return -1;
			}
			elseif ($arr2['keys_found'] > $arr1['keys_found']) {
				#echo "returning 1 - 2";
				return 1;
			}
			else {
				//the last check is pageView
				if ($arr1['pageView'] <= $arr2['pageView']) {
					#echo "returning -1 - 3";
					return -1;
				}
				else {
					#echo "returning 1 - 3";
					return 1;
				}
			}
		}
	}
	
	function buildArray($s, $key_array, $_retArray)
	{
		while ($r = mysql_fetch_assoc($s))
		{
			//define a cumulative sum variable
			$sum_of_keys = 0;
			$_tArray = array();
			$_tArray['keys_found'] = 0;
			
			//cycle through the key array and call the key function for each
			foreach ($key_array as $key)
			{
				//store the number first
				$_tArray['keys'][$key] = ($found = substr_count($r['descrip'], $key));
				$sum_of_keys += $_tArray['keys'][$key];		//accumulate the number of occurences
			}
			$_tArray['description'] = generateBlob($r['descrip'], $key_array);
			$_tArray['page_views'] = $r['pageView'];
			$_tArray['id'] = $r['id'];
			
			/*
				we will now add resolution searching - since we can have multiple resolutions it is essential that we store these in the same way
				that we store the key counting information
				so we are adding the following to the array structure
				[resolutions] =>
					[resid] => string (blob)
					...
					
				Notice we will still increment the same array fields as above to keeep counting consistent
			*/
			$q  = "select resid, solution from " . DB_PREFIX . "resolution where (id = " . intval($r['id']) . ")";
			$res = mysql_query($q) or die("Inner Query Failed for Resolution List");
			
			while ($row = mysql_fetch_assoc($res))
			{
				//cycle through the key array and call the key function for each
				foreach ($key_array as $key)
				{
					//store the number first
					$_tArray['keys'][$key] += substr_count($row['solution'], $key);
					
					//find the sum
					$sum_of_keys += $_tArray['keys'][$key];		//accumulate the number of occurences
				}
				$_tArray['resolutions'][$row['resid']] = generateBlob($row['solution'], $key_array);
			}
			
			//determine key found value
			foreach ($_tArray['keys'] as $key => $occur)
				if ($occur) $_tArray['keys_found']++;
			
			//save the array
			$_retArray[] = $_tArray;
		}
		
		return $_retArray;
	}
	
	//function takes a results array from key find and fills it with the appropriate information
	//this is called last on the page
	function fillResultsArray($array)
	{
		$_rArray = $array;
		foreach ($array as $id => $arr)
		{
			//$arr is the inner array
			$t = new Ticket($id);
			if (!isset($arr['description'])) $_rArray[$id]['description'] = generateBlob($t->get('descrip'));
			if (!isset($arr['keys'])) $_rArray[$id]['keys'] = array();
			if (!isset($arr['keys_found'])) $_rArray[$id]['keys_found'] = 0;
			if (!isset($arr['sum_of_instances'])) $_rArray[$id]['sum_of_instaces'] = 0;
			if (!isset($arr['pageView'])) $_rArray[$id]['pageView'] = $t->get('pageView', 'intval');
			if (!isset($arr['files'])) $_rArray[$id]['files'] = array();
		}
		
		return $_rArray;
	}
?>