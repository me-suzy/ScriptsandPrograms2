<?php
/*****************************************
* File      :   $RCSfile: functions.i18n.php,v $
* Project   :   Contenido
* Descr     :   Contenido i18n Functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   03.07.2003
* Modified  :   $Date: 2003/08/25 14:04:12 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.i18n.php,v 1.7 2003/08/25 14:04:12 timo.hummel Exp $
******************************************/


/**
 * trans($string)
 *
 * gettext wrapper (for future extensions). Usage:
 * trans("Your text which has to be translated");
 *
 * @param $string string The string to translate
 * @return string  Returns the translation
 */
function trans ($string)
{
	return i18n($string);
}
/**
 * i18n($string)
 *
 * gettext wrapper (for future extensions). Usage:
 * i18n("Your text which has to be translated");
 *
 * @param $string string The string to translate
 * @return string  Returns the translation
 */
function i18n ($string)
{
	global $cfg, $i18nLanguage;
	
	if ($i18nLanguage == "he_SS")
	{
		return i18nEmulateGettext($string);
	}
	
	//if (function_exists("gettext"))
	//{
//		return gettext($string);
//	}
	

	return i18nEmulateGettext($string);
	
	
}

/**
 * i18nEmulateGettext()
 *
 * Emulates GNU gettext
 *
 * @param $localePath string Path to the locales
 * @param $langCode string Language code to set
 * @return string  Returns the translation
 */
function i18nEmulateGettext ($string)
{
	global $cfg, $i18nLanguage, $transFile;

	/* Bad thing, gettext is not available. Let's emulate it */
		
	if (!file_exists($cfg["path"]["contenido"].$cfg['path']['locale'].$i18nLanguage."/LC_MESSAGES/contenido.po"))
	{
		return $string;
	}
	
	if (!isset($transFile))
	{
		$transFile = implode('',file($cfg["path"]["contenido"].$cfg['path']['locale'].$i18nLanguage."/LC_MESSAGES/contenido.po"));
	}	
	
	
	$stringStart = strpos($transFile,'"'.$string.'"');

	if ($stringStart === false)
	{
		return $string;
	}
	
	
	$searchStart = $stringStart + strlen($string);
	$msgstrStart = strpos($transFile, 'msgstr', $searchStart);
	
	
	
	$messageStart = strpos($transFile, '"', $msgstrStart);
	$maxMsgEnd = strpos($transFile, "\n", $messageStart);

	$tempMessage = substr($transFile, $messageStart+1, $maxMsgEnd- $messageStart+1);
	

	$startSearch = 0;
	$endOfMessage = 0;
	while ($pos = strpos($tempMessage,'"',$startSearch+1))
	{
		if ($pos === false)
		{
			return "Error in i18nEmulateGettext";
		}
		if (substr($tempMessage, $pos-1,1) != '\\')
		{
			$endOfMessage = $pos;
		}
		
		$startSearch = $pos;
	}
	
	$tempMessage = substr($tempMessage,0,$endOfMessage);
	$tempMessage = str_replace('\\"','"',$tempMessage);
	
	return ($tempMessage); 
	 
}

/**
 * i18nInit()
 *
 * Initializes the i18n stuff.
 *
 * @param $localePath string Path to the locales
 * @param $langCode string Language code to set
 * @return string  Returns the translation
 */
function i18nInit ($localePath, $langCode)
{
	global $i18nLanguage;
	
	if (function_exists("bindtextdomain"))
	{
    	/* Bind the domain "contenido" to our locale path */
    	bindtextdomain("contenido", $localePath);
    	
    	/* Set the default text domain to "contenido" */
    	textdomain("contenido");
    	
    	/* Half brute-force to set the locale. */
    	if (!ini_get("safe_mode"))
    	{
    		putenv("LANG=$langCode");
    	} 
    	setlocale(LC_ALL, $langCode);
	}
	
	$i18nLanguage = $langCode;
}

/**
 * i18nStripAcceptLanguages($accept)
 *
 * Strips all unnecessary information from the $accept string.
 * Example: de,nl;q=0.7,en-us;q=0.3 would become an array with de,nl,en-us
 *
 * @return array Array with the short form of the accept languages  
 */
function i18nStripAcceptLanguages($accept)
{
	$languages = explode(',', $accept);
	foreach ($languages as $value)
	{
			$components = explode(';', $value);
			$shortLanguages[] = $components[0];
	}	
	
	return ($shortLanguages);
}

/**
 * i18nMatchBrowserAccept($accept)
 *
 * Tries to match the language given by $accept to
 * one of the languages in the system.
 *
 * @return string The locale key for the given accept string 
 */
function i18nMatchBrowserAccept ($accept)
{
	$available_languages = i18nGetAvailableLanguages();
	
	/* Try to match the whole accept string */
	foreach ($available_languages as $key => $value)
	{
		list($country, $lang, $encoding, $shortaccept) = $value;
		
		if ($accept	== $shortaccept)
		{
			return $key;
		}
	}
	
	/* Whoops, we are still here. Let's match the stripped-down string.
       Example: de-ch isn't in the list. Cut it down after the "-" to "de"
       which should be in the list. */
       
    $accept = substr($accept,0,2);
	foreach ($available_languages as $key => $value)
	{
		list($country, $lang, $encoding, $shortaccept) = $value;
		
		if ($accept	== $shortaccept)
		{
			return $key;
		}
	}       

	/* Whoops, still here? Seems that we didn't find any language. Return
       the default (german, yikes) */
   return (false);
}


/**
 * i18nGetAvailableLanguages()
 *
 * Returns the available_languages array to prevent globals.
 *
 * @return array All available languages
 */
function i18nGetAvailableLanguages ()
{
	/* Array notes:
       First field: Language
	   Second field: Country
       Third field: ISO-Encoding
       Fourth field: Browser accept mapping
       Fifth field: SPAW language
    */
    $available_languages = array(
    	'de_DE' => array('German', 'Germany', 'ISO8859-1', 'de', 'de'),
    	'en_US' => array('English', 'United States', 'ISO8859-1', 'en', 'en'),
		'nl_NL' => array('Dutch', 'Netherlands', 'ISO8859-1', 'nl', 'nl')
    	);
    	
    if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],"hessisch") !== false)
    {
    	$available_languages['he_SS'] = array('Hessisch', 'Germany', 'ISO8859-1', 'de', 'de'); 
    }
    	
    	/*
    	'ar_AA' => array('Arabic','Arabic Countries', 'ISO8859-6', 'ar','en'),
    	'be_BY' => array('Byelorussian', 'Belarus', 'ISO8859-5', 'be', 'en'),
    	'bg_BG' => array('Bulgarian','Bulgaria', 'ISO8859-5', 'bg', 'en'),
    	'cs_CZ' => array('Czech', 'Czech Republic', 'ISO8859-2', 'cs', 'cz'),
    	'da_DK' => array('Danish', 'Denmark', 'ISO8859-1', 'da', 'dk'),
    	'de_CH' => array('German', 'Switzerland', 'ISO8859-1', 'de-ch', 'de'),
    	
    	'el_GR' => array('Greek', 'Greece', 'ISO8859-7', 'el', 'en'),
    	'en_GB' => array('English', 'Great Britain', 'ISO8859-1', 'en-gb', 'en'),
    	',
    	'es_ES' => array('Spanish', 'Spain', 'ISO8859-1', 'es', 'es')
    	'fi_FI' => array('Finnish', 'Finland', 'ISO8859-1', 'fi', 'en'),
    	'fr_BE' => array('French', 'Belgium', 'ISO8859-1', 'fr-be', 'fr'),
    	'fr_CA' => array('French', 'Canada', 'ISO8859-1', 'fr-ca', 'fr'),
    	'fr_FR' => array('French', 'France', 'ISO8859-1', 'fr', 'fr'),
    	'fr_CH' => array('French', 'Switzerland', 'ISO8859-1', 'fr-ch', 'fr'),
    	'hr_HR' => array('Croatian', 'Croatia', 'ISO8859-2', 'hr', 'en'),
    	'hu_HU' => array('Hungarian', 'Hungary', 'ISO8859-2', 'hu', 'hu'),
    	'is_IS' => array('Icelandic', 'Iceland', 'ISO8859-1', 'is', 'en'),
    	'it_IT' => array('Italian', 'Italy', 'ISO8859-1', 'it', 'it'),
    	'iw_IL' => array('Hebrew', 'Israel', 'ISO8859-8', 'he', 'he'),
    	'nl_BE' => array('Dutch', 'Belgium', 'ISO8859-1', 'nl-be', 'nl'),
    	
    	'no_NO' => array('Norwegian', 'Norway', 'ISO8859-1', 'no', 'en'),
    	'pl_PL' => array('Polish', 'Poland', 'ISO8859-2', 'pl', 'en'),
    	'pt_BR' => array('Brazillian', 'Brazil', 'ISO8859-1', 'pt-br', 'br'),
    	'pt_PT' => array('Portuguese', 'Portugal', 'ISO8859-1', 'pt', 'en'),
    	'ro_RO' => array('Romanian', 'Romania', 'ISO8859-2', 'ro', 'en'),
    	'ru_RU' => array('Russian', 'Russia', 'ISO8859-5', 'ru', 'ru'),
    	'sh_SP' => array('Serbian Latin', 'Yugoslavia', 'ISO8859-2', 'sr', 'en'),
    	'sl_SI' => array('Slovene', 'Slovenia', 'ISO8859-2', 'sl', 'en'),
    	'sk_SK' => array('Slovak', 'Slovakia', 'ISO8859-2', 'sk', 'en'),
    	'sq_AL' => array('Albanian', 'Albania', 'ISO8859-1', 'sq', 'en'),
    	'sr_SP' => array('Serbian Cyrillic', 'Yugoslavia', 'ISO8859-5', 'sr-cy', 'en'),
    	'sv_SE' => array('Swedish', 'Sweden', 'ISO8859-1', 'sv', 'se'),
    	'tr_TR' => array('Turkisch', 'Turkey', 'ISO8859-9', 'tr', 'tr'));*/
	
	return ($available_languages);
}
	
?>