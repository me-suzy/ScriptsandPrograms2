<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 11th May 2005                           #||
||#     Filename: bbcode.php                             #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package BBCode
	@todo Complete section
*/

if (!defined('wbnews'))
	die('Hacking Attempt');

/**
	Since we are mainly parsing strings and replacing adding the emoticon function
	within the BBcode file is not a bad idea it should also help with organistaion
	and making sure BBcode doesnt do anything bad and vice versa
	
	@param string $string
	@return string
	@global Object $dbclass
	@todo test to make sure it doesnt interrupt, Links and BBCode
*/
function emoticons($string)
{
	global $dbclass, $tpl;
	
	$getEmoticons = $dbclass->db_query("SELECT code, image, name
									    FROM " . TBL_EMOTICON . "
                                        ");
	
	if ($dbclass->db_numrows($getEmoticons) != 0)
	{
		while ($emoticon = $dbclass->db_fetcharray($getEmoticons))
			$string = str_replace($emoticon['code'], $tpl->replace($GLOBALS['TPL_EMOTICON'], $emoticon), $string);
        return $string;
	}
	else
		return $string;
}

/**
	Parses PHP, Lists, Bold, Underlined text etc. These are the built in functions,
	Also once parsed checks to see if there are user defined BBCode Tags if there are
	then attempts to parse them correctly

	@access public
	@param string $string
	@return string
*/
function bbcode($string)
{
	$string = bbcodeLists($string); //parse the Lists if we have any first
	
	/*
	  	Built in Tags
	*/
	
	$find = array(
				"/(\[b\])(.+?)(\[\/b\])/is",
				"/(\[u\])(.+?)(\[\/u\])/is",
				"/(\[i\])(.+?)(\[\/i\])/is",
				"/(\[url=(.+?)\])(.+?)(\[\/url\])/is",
				"/(\[url])(.+?)(\[\/url\])/is",
				"/(\[type=(.+?)\])(.+?)(\[\/type\])/is",
				"/(\[size=(.+?)\])(.+?)(\[\/size\])/is",
				"/(\[color=(.+?)\])(.+?)(\[\/color\])/is",
				"#\[php\](.+?)\[/php\]#msie"
				);
					
	$replace = array(
					"<strong>$2</strong>",
					"<u>$2</u>",
					"<em>$2</em>",
					'<a href="$2">$3</a>',
					'<a href="$2">$2</a>',
					'<span style="font-family: $2;">$3</span>',
					'<span style="font-size: $2em;">$3</span>',
					'<span style="color: $2;">$3</span>',
					"phpHighlight('$1')"
					);

	for ($i=0;$i<sizeof($find);$i++)
		$string=preg_replace($find[$i], $replace[$i], $string);
	
	return $string;
}

/**
	PHP Highlighter, removes slashes and adds proper tags in since the string has
	been parsed before being stored in the database, we then use the PHP Template

	@access public
	@param string $php
	@return string
	@global $tpl object
*/
function phpHighlight($php)
{
	global $tpl;

	$entities = array(
					'&lt;',
					'&gt;',
                    '<br />'
					);
    
    $replace = array(
					'<',
					'>',
                    ''
					);

	$php = str_replace($entities, $replace, $php);
	$php = stripslashes($php);
	$php = html_entity_decode($php);
    $php = trim($php);
    
    if (preg_match('#^\s*<\?#si', $php)) //match the starting tag
		$code['php'] = highlight_string($php, true);
    else
		$code['php'] = highlight_string("<?php\n".$php, true);
        
    $code['php'] = str_replace("\r", "", $code['php']);
    
    return $tpl->replace($tpl->getTemplate('php_display'), $code);
}

/**
	Attempts to match a list which are predefined if there are no lists the string
	is returned without being parsed if so the list is parsed as well as its list items
	
	@access public
	@param string $string
	@return string
*/
function bbcodeLists($string) 
{
	//if (preg_match("/(\[list=numbered\])\s*(.+?)\s*(\[\/list\])\s*/is",$string)) 
	//	$string = preg_replace("/(\[list=numbered\])\s*(.+?)\s*(\[\/list\])\s*/is","<ol>$2</ol>",$string);
        
	//if (preg_match("/(\[list\])\s*(.+?)\s*(\[\/list\])\s*/is",$string))
	//	$string = preg_replace("/(\[list\])\s*(.+?)\s*(\[\/list\])\s*/is","<ul>$2</ul>",$string);*/
	
    if (preg_match("/\[list\]<br \/>(.+?)\[\/list\]/ism", $string))
        $string = preg_replace("/\[list\]<br \/>(.+?)\[\/list\]/ism", "<ul>$1</ul>", $string);
       
    // ordered list
    if (preg_match("/\[list=numbered\](.+?)\[\/list\]/ism", $string))
        $string = preg_replace("/\[list=numbered\](.+?)\[\/list\]/ism", "<ol>$1</ol>", $string);
    
    if (preg_match("/\[\*\](.+?)<br \/>/ism", $string))
        $string = preg_replace("/\[\*\](.+?)<br \/>/ism", "<li>$1</li>", $string);
    //$string = preg_replace("/\s*(\[li\])\s*(.+?)\s*(\[\/li\])\s*/is","<li>$2</li>",$string);
    
	return $string;
}

?>
