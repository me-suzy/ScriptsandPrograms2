<?php

function snippet_moblogcheck() {
	global $Paths;
	
	if (defined('LIVEPAGE')) {
		
		include_once( dirname(dirname(__FILE__))."/moblog/fetchmail.php");
		
		$output = "<!-- moblogcheck -->";
		
	} else {
		
		$output = sprintf("<"."?php include_once('%s/moblog/fetchmail.php'); ?".">",
					dirname(dirname(__FILE__))
					);
	}
	
	
	return $output;
	
}


?>