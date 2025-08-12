<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }



// 2004/10/27 =*=*= JM
// pulls in needed filtered_words files and returns the array
function getFilteredWords( ) {
	global $Weblogs;

	if( file_exists( 'db/search/filtered_words.txt' )) {
		$filtered_file = file( 'db/search/filtered_words.txt' );
		foreach( $filtered_file as $val ) {
			$filtered_words[] = trim( $val );
		}
	} else {
		$filtered_words = array();	
	}


	@reset ($Weblogs);
	@$Current_weblog = ( key( $Weblogs ));

	$theLang = $Weblogs[$Current_weblog]['language'];
	
	if(( ''!=$theLang )
	&&( file_exists( 'db/search/filtered_words_'.$theLang.'.txt' ))) {

		 // echo '*' ;
		$filtered_file = file( 'db/search/filtered_words_'.$theLang.'.txt' );
		foreach( $filtered_file as $val ) {
			$filtered_words[] = trim( $val );
		}
	}
	return $filtered_words;
}

//  user editing fun!
function GetLangs() {
	$Languages = array();
	$dh = opendir('langs/');
		while($fname = readdir($dh)) {
			if(preg_match('!pvl_(.*?).php!', $fname, $null)){
				$langfile = file('langs/'.$fname);
				array_push($Languages, trim(substr($langfile[1], 2, -1)), $null[1]);
			}
		}
		$langfile = '';
	closedir($dh);
	return $Languages;
}



function lang($a='', $b='', $c='') {
	global $havelang, $Language, $CurrentLanguage;

	

	// see if the desired lang has the label.	
	if (isset($Language[$CurrentLanguage])) {
		$label = $Language[$CurrentLanguage]->label($a, $b, $c);
	} else {
		debug("leeg lang: ".$CurrentLanguage);
	}

	//debug("label: $label (a=$a -- b=$b -- c=$c)");

	if ($label != "") { 
		//return "{".$label."}"; 
		return $label; 
	} else {
		//debug("not set: a=$a -- b=$b -- c=$c");
		// fall back on the english file
		if (isset($Language['eng'])) {
			$label = $Language['eng']->label($a, $b, $c);
		}
		if ($label != "") {
			//return "[".$label."]";
			return $label;
		} else {
			return "$a - $b - ($c)";
		}
	}


}

function LoadUserLanguage() {
	global $Users, $Pivot_Vars, $Language, $CurrentLanguage, $CurrentEncoding, $Cfg;

	//debug("userlang: ".$Users[$Pivot_Vars['user']]['language']);

	if(isset($Users[$Pivot_Vars['user']]['language']) ) {
		$lang = $Users[$Pivot_Vars['user']]['language'];
		if (!isset($Language[$lang])) {
			$Language[$lang] = new LangClass($lang);
		}
		$CurrentLanguage = $lang;
		$CurrentEncoding = $Language[$lang]->getEncoding();

	}	
	
}	

function LoadDefLanguage() {
	global $Cfg, $Language, $CurrentLanguage, $CurrentEncoding;

	$lang = $Cfg['deflang'];

	//debug("deflang: ".$lang);
	if (!isset($Language[$lang])) {
		$Language[$lang] = new LangClass($lang);
	}
	$CurrentLanguage = $lang;
	$CurrentEncoding = $Language[$lang]->getEncoding();
	
	//always load english, just to be sure..
	if (!isset($Language['eng'])) {
		$Language['eng'] = new LangClass('eng');
	}
}

function LoadWeblogLanguage($lang) {
	global $Language, $CurrentLanguage, $CurrentEncoding;

	//debug("webloglang: ".$lang);
	if (!isset($Language[$lang])) {
		$Language[$lang] = new LangClass($lang);
	}
	$CurrentLanguage = $lang;
	$CurrentEncoding = $Language[$lang]->getEncoding();
}


/**
 * Loads an extra set of labels, for instance for extensions
 *
 * @param string filename of the file to load
 * @return void
 *
 */
function LoadLabels( $filename ) {
	global $Language;
	
	//always load english, just to be sure..
	if (!isset($Language['eng'])) {
		$Language['eng'] = new LangClass('eng');
	}
	
	// The new strings will always be added to english.
	$Language['eng']->LoadLabels( $filename );
	
}

class LangClass {

	function LangClass($language) {
		global $pivot_path;

		if (file_exists('langs/pvl_'.$language.'.php')) {
			include ('langs/pvl_'.$language.'.php');
		} else if (file_exists(realpath($pivot_path).'/langs/pvl_'.$language.'.php')) {
			include (realpath($pivot_path).'/langs/pvl_'.$language.'.php');
		} else if (file_exists('langs/pvl_eng.php')) {
			include ('langs/pvl_eng.php');
		} else if (file_exists(realpath($pivot_path).'/langs/pvl_eng.php')) {
			include (realpath($pivot_path).'/langs/pvl_eng.php');
		}




		$this->lang = $lang;
		$this->name = $langname;
		$this->encoding = $encoding;
	}

	function label($a="", $b="", $c="") {

		if ($a==="") {
			return "";
		} else if ($b==="") {
			return $this->lang['general'][$a];
		} else if ($c==="") {
			$label =  @$this->lang[$a][$b];					
			if ( ($label=="") && (is_numeric($b)) ) { 
				return $b; 
			} else {
				return $label;	
			}
		} else {
			return $this->lang[$a][$b][$c];
		}
	}

	function getEncoding() {
		global $Cfg;							
									
		// if default encoding is set, use that.
		if ((isset($Cfg['defencoding'])) && (strlen($Cfg['defencoding'])>1)) {
			return $Cfg['defencoding'];
		} else {
			return $this->encoding;
		}
	}

	function getName() {
		return $this->name;
	}

	/**
	 * Loads a file with extra labels, and adds them to the 
	 * labels that are already present
	 *
	 * @param string filename of the file to load
	 *
	 */
	function LoadLabels ( $filename ) {
		
		if (file_exists($filename)) {
			include ($filename);
		}
		
		$this->lang = array_merge( $this->lang, $lang );
		
	}

}

?>