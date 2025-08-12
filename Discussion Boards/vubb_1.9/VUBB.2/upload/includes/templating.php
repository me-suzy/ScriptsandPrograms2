<?php
/*
Copyright 2005 VUBB
Templating V0.1
*/
function get_template($template_name)
{	
	/// make global so templates can use them
	// site_config - forum configuration settings
	// lang - language
	global $site_config, $lang;
	
	// set the template file
	$tn = 'templates/' . $site_config['template'] . '/' . $template_name . '.php';
	
	// if exists include
	if (file_exists($tn))
	{
		include($tn);
	}
	
	// if doesn't exist show error
	else
	{
		error($lang['title']['error'], $lang['text']['template_not_found']);
	}
}
?>