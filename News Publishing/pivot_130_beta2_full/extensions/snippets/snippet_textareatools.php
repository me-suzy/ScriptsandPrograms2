<?php

/**
 * This snippet allows for commentform texarea's to be resizeable on the fly
 *
 * @return string
 */
function snippet_textareatools() {
	global $Paths;

	$output = ' <script type="text/javascript" src="%ext%textareatools/scripts.js"></script>
<style type="text/css">
@import "%ext%textareatools/resizable.css";
</style>';
	
	$output = str_replace("%ext%", $Paths['extensions_url'], $output);
	
	return $output;
	
	
}


?> 