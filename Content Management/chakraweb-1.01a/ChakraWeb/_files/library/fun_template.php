<?php
// ----------------------------------------------------------------------
// ModName: fun_template.php
// Purpose: Functions related to template engine
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_template.php] file directly...");

//load template file then parse the content
function TemplateLoad($tplname)
{
    //PrintLine("TemplateLoad($tplname)");

    global $gTemplateLocalPath;

	$content = ReadLocalFile($gTemplateLocalPath.$tplname, $errmsg, true);
	if (empty($content))
		$content = $errmsg;
	else
		$content = TemplateParse($content);

	return $content;
}


function TemplateParse($content)
{
    global $gWebPage;

    //first: we parse the $gWebPage. This avoid unfinished recursive
    $arTemps = $gWebPage;
    //PrintArray($gWebPage, '$gWebPage');
    foreach($arTemps as $var => $value)
    {
        if (is_string($value) && !empty($value))
        {
            if (!StrIsStartWith($var, "fld_"))
                $gWebPage[$var] = preg_replace_callback("'{.*?}'mi", 'TemplateParseCallBack', $value);
        }
    }

    //second: parse the content
    $content = preg_replace_callback("'{.*?}'mi", 'TemplateParseCallBack', $content);

    //$content = SmileyParse($content);
    return $content;
}

function TemplateParseCallBack($match)
{
    global $gWebPage;

    $token = $match[0];
    if (strlen($token) > 2)
        $token = substr($token, 1, strlen($token)-2);

    //PrintLine($token, '$token');

    if (StrIsStartWith($token, "~"))
        return '{'.substr($token, 1, strlen($token)-1).'}';

    reset($gWebPage);
    foreach($gWebPage as $var => $value)
    {
        if ($token == $var)
            return $value;
    }

    //no match. return the unparsed string  
    return $match[0];
}


?>
