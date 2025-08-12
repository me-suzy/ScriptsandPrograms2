<?php

include_once('config.php');
$phpOnlineVer = 1.8;

$TransLangs = array(array("en","english"),
		    array("de","German"),
		    array("es","Spanish"),
		    array("fr","French"),
		    array("it","Italian"),
		    array("pt","Portuguese"));



if(trim($HTTP_SERVER_VARS["SERVER_PORT"])=="")
{
	$HTTP_GET_VARS = $_GET;
	$HTTP_POST_VARS = $_POST;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_SERVER_VARS = $_SERVER;
}



include_once('loadconfdb.php');





function GetCCodeStatus($CC,$FN)
{
	return 1;
}


function SetCCodeStatus($CC,$ST,$FN)
{
}

function GetPStatus($PPTTP="john")
{
return 1;
}

function SetPStatus($ST)
{
}

function GetEQString($ST)
{
	$RV = "";
	for($i=0;$i<strlen($ST);$i++)
	{
		$RV .= ("%".dechex(ord($ST{$i})));
		
	}
	return($RV);
}

function Translate($Msg,$From,$To)
{

$SupportedTrans = array("en|de","en|es","en|fr","en|it","en|pt",
			"de|en","de|fr",
			"es|en",
			"fr|en","fr|de",
			"it|en",
			"pt|en");

	$LPR = $From.'|'.$To;
	$TMsg = $Msg;
	if(in_array($LPR,$SupportedTrans))
	{
		$TUL = implode("",file("http://translate.google.com/translate_t?ie=UTF8&hl=en&langpair=$LPR&text=".rawurlencode($TMsg) ));
		$Result = preg_match("'<textarea.+name=q[^>]*>([^<]*)</textarea>'i",$TUL,$matches);
		if(isset($matches[1]))
			$TMsg = $matches[1];

	}
	else
	{
		$LPR = $From.'|en';
		$TUL = implode("",file("http://translate.google.com/translate_t?ie=UTF8&hl=en&langpair=$LPR&text=".rawurlencode($TMsg) ));
		$Result = preg_match("'<textarea.+name=q[^>]*>([^<]*)</textarea>'i",$TUL,$matches);
		if(isset($matches[1]))
			$TMsg = $matches[1];
		$TMsg2 = $TMsg;

		$LPR = 'en|'.$To;
		$TUL = implode("",file("http://translate.google.com/translate_t?ie=UTF8&hl=en&langpair=$LPR&text=".rawurlencode($TMsg2) ));
		$Result = preg_match("'<textarea.+name=q[^>]*>([^<]*)</textarea>'i",$TUL,$matches);
		if(isset($matches[1]))
			$TMsg2 = $matches[1];

		$TMsg = $TMsg2;
	}




	return($TMsg);

}



?>