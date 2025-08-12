<?
/*
* THIS SCRIPT WILL UPDATE SNIPPET/DESCRIPTION FILES FROM 
* VERSION 0.8 TO WORK WITH VERSION 0.9.
*
* - ONLY EVER RUN THIS ONCE AGAINST EACH DEFAULT LIBRARY -
* - ALTHOUGH IF YOU RUN IT TWICE BY MISTAKE, JUST RUN IT -
* - ONE MORE TIME TO ACHEIVE THE SAME INITIAL EFFECT -
*
********************************************************************
*   HOW TO DO AN UPDATE - FROM V0.8 TO V0.9
********************************************************************
*
* 1. MAKE SURE convert08to09.php IS LOCATED ALONG WITH ALL THE OTHER 
*    PHP-CSL PHP CODE.
*
* 2. TAKE A NOTE OF EACH OF YOUR LIBRARIES - INCLUDING THE DEFAULT
*
* 3. RUN THIS CODE - http://pathtophpcsl/convert08to09.php
*
* 4. IF YOU HAVE MORE THAN ON LIBRARY - CHANGE THE DEFAULT ONE
*    AND REPEAT STEP 3. DO THIS FOR ALL YOUR LIBRARIES.
*
* 5. THATS IT, YOU CAN DELETE convert08to09.php IF SUCCESSFULL 
*    ANY PROBS - GOTO THE HELP FORUMS.
*
*/

include 'config.php';
include 'functions.php';

$all_cats = cats_get();

foreach($all_cats as $cat) { 
	if(strlen($cat) >1) {
		$ob = new SNIPS;
		$ob->snips_list_set($cat);
		$array = $ob->snips_list_get();
		if(is_array($array)) {
			 foreach($array as $ar) {
			 	echo '--------------------------------------------<br>';
			 	// open files and get content
			 	if($s = file_open($cat.".".$ar.".s")) { echo "openend: $cat.$ar.s fine!!<br>"; }
			 	if($d = file_open($cat.".".$ar.".d")) { echo "openend: $cat.$ar.d fine!!<br>"; }
			 	
			 	$s = strrev($s);
			 	$d = strrev($d);
			 	
			 	if(file_delete($cat,$ar)) {
			 		echo "deleted: $cat.$ar.s fine!!<br>";
					echo "deleted: $cat.$ar.d fine!!<br>";
					
					$ob = new SNIPS;
					if($ob->snips_create($cat, $ar, $s, $d)) {
						echo "recreated: $cat.$ar.s fine!!<br>";
						echo "recreated: $cat.$ar.d fine!!<br>";
					}
			 	}
			 	echo '--------------------------------------------<br>';	
			 }
		}
	
	}
}
?>
