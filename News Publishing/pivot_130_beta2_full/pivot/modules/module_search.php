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


// module_search.php by bob & paul@slorp.org 12/9/2003

@reset ($Weblogs);
@$Current_weblog = (key($Weblogs));

LoadDefLanguage();

@set_time_limit(0);
//error_reporting(0);

// 2004/10/27 =*=*= JM
$filtered_words = getFilteredWords();

global $allowed_chars;
$allowed_chars = "0-9a-zA-Z©®¹¾¼ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿÞþÐðß¦¶Ææµ";

	// 2004/09/19 =*=*= JM
	// search is now an array
	// 0 -> for display
	// 1 -> for search [no accents]

@$search_a[0] = strtolower(trim($_POST['search']." ".$_GET['q'])) ;
 $search_a[0] = preg_replace ('/[^'.$allowed_chars.']/i',' ', $search_a[0] );
 $search_a[1] = transliterate_accents( $search_a[0] ) ;



// ----------------------------------------------------
// 2004/09/19 =*=*= JM
// doing ord() on chars is perhaps long but if I include accented chars in source code
// then we will forever have mac/pc/unix transcoding problems
function transliterate_accents( $theStr ) {

	$r = '' ;
	if( is_string( $theStr )) {

		$t = strlen( $theStr ) ;
		for( $i=0 ; $i < $t ; $i++ ) {

			// what is this 
			switch( ord( $theStr[$i] )) {
				case( 192 ) : // A-grave
				case( 193 ) : // A-acute
				case( 194 ) : // A-circ
				case( 195 ) : // A-tilde
				case( 196 ) : // A-uml
				case( 197 ) : // A-ring
				case( 224 ) : // a-grave
				case( 225 ) : // a-acute
				case( 226 ) : // a-circ
				case( 227 ) : // a-tilde
				case( 228 ) : // a-uml
				case( 229 ) : // a-ring
					$r .= 'a' ; break ;
					// -------------------------
				case( 193 ) : // AE-lig
				case( 230 ) : // ae-lig
					$r .= 'ae' ; break ;
					// -------------------------
				case( 231 ) : // c-cedil
				case( 199 ) : // C-cedil
					$r .= 'c' ; break ;
					// -------------------------
				case( 200 ) : // E-grave
				case( 201 ) : // E-acute
				case( 202 ) : // E-circ
				case( 203 ) : // E-uml
				case( 232 ) : // e-grave			
				case( 233 ) : // e-acute
				case( 234 ) : // e-circ
				case( 235 ) : // e-uml
					$r .= 'e' ; break ;
					// -------------------------
				case( 204 ) : // I-grave
				case( 205 ) : // I-acute
				case( 206 ) : // I-circ
				case( 207 ) : // I-uml
				case( 236 ) : // i-grave
				case( 237 ) : // i-acute
				case( 238 ) : // i-circ
				case( 239 ) : // i-uml
					$r .= 'i' ; break ;
					// -------------------------
				case( 241 ) : // n-tilde
				case( 209 ) : // N-tilde
					$r .= 'n' ; break ;
					// -------------------------
				case( 210 ) : // O-grave
				case( 211 ) : // O-acute
				case( 212 ) : // O-circ
				case( 213 ) : // O-tilde
				case( 214 ) : // O-uml
				case( 216 ) : // O-slash
				case( 242 ) : // o-grave
				case( 243 ) : // o-acute
				case( 244 ) : // o-circ
				case( 245 ) : // o-tilde
				case( 246 ) : // o-uml
				case( 248 ) : // o-slash
					$r .= 'o' ; break ;
					// -------------------------
				// NOTE: these don't get thru form?
				case( 338 ) : // OE-lig
				case( 339 ) : // oe-lig
					$r .= 'oe' ; break ;
					// -------------------------
				case( 217 ) : // U-grave
				case( 218 ) : // U-acute
				case( 219 ) : // U-circ
				case( 220 ) : // U-uml
				case( 249 ) : // u-grave
				case( 250 ) : // u-acute
				case( 251 ) : // u-circ
				case( 252 ) : // u-uml
					$r .= 'u' ; break ;
					// -------------------------
				case( 223 ) : // ss-lig
					$r .= 'ss' ; break ;
					// -------------------------
					// NOTE: y-uml don't get thru form?
				case( 255 ) : // Y-uml
				case( 376 ) : // y-uml
					$r .= 'y' ; break ;
					// -------------------------
					// ADD OTHER CHARS HERE...					
					// -------------------------
				default :					
					$r .= $theStr[$i] ;
			}
		}
	}
	return $r ;
}


// ---------- functions for indexing ------------- //

function start_index ($start, $stop, $time) {

	$db = new db();

	$entries = $db->db_lowlevel->date_index;

	$count = 0;

	// 2004/10/17 =*=*= JM - doing these in loop will slow things
	$date = date( 'Y-m-d-H-i' );
	$cats = cfg_cat_nosearchindex();

	debug("index excludes cats: ".implode(',',$cats));
	
	foreach($entries as $key => $value) {
	
		if(($count++)<($start)) { continue; }		
		if(($count)>($stop)) { break; }
		
		// 2004/10/17 =*=*= JM - New searchIndex tests...
		$entry = $db->read_entry( $key );
		// rules: index if all are true:
		// - ( status == 'publish' )or(( status == 'timed')&&( publish_date <= date ))
		// - at least one category is not in exclusion array

		// check status and date
		if(( 'publish'==$entry['status'] )
			||(( 'timed'==$entry['status'] )&&( $entry['publish_date'] <= $date ))) {
			
			// categories...
			if( can_search_cats( $cats,$entry['category'] )) {
				if (($count % 50) == 0) { 
					printf("%1.2f sec: Processed %d entries.."."<br />\n", (timetaken('int')+$time), $count);
					flush();
				}
				strip_words( $entry);
			}
		}
	}

	echo("<br /><br />");

	// decide if we need to do some more.
	if(count($entries) > ($stop)) {
		return TRUE;
	} else {
		return FALSE;
	}

}


// 2004/10/27 =*=*= JM - change
// preparing for language specific exclusions
function clear_index() {
	
	$d = dir( 'db/search/' );
	while( false !== ( $entry = $d->read())) {
		if((( '.' != $entry ) &&( '..' != $entry ))
		&&( 'filtered_words' != substr( $entry,0,14 ))) {
			unlink( 'db/search/'.$entry );
			//echo("[$entry]");
		}   
	}
	$d->close();
}



// update the index for a single entry
function update_index($entry) {
	global $master_index;


	strip_words($entry);

	foreach($master_index as $key => $index) {
		$filename = "db/search/" . $key . ".php";

		// load the index if it exists..
		if (file_exists($filename)) {
			$temp = load_serialize($filename);
		} else {
			$temp = array();
		}

		// add the new stuff..
		foreach($index as $key=>$val) {
			if(isset($temp[$key])) {
				$occurr = explode("|", $temp[$key]);
				$occurr[] = $val;
				$val = implode("|", array_unique($occurr));
				$temp[$key] = $val;
			} else {
				$temp[$key] = $val;
			}
		}	

		//echo("<br />mems1:".memory_get_usage());
		save_serialize($filename, $temp);
		unset($master_index[$key]);
		$wordcount += count($index);
	}

}


// parse the input, strip non-words, and add to the index..
function strip_words ($arr) {
	global $allowed_chars;

	$words = $arr['title']." ".$arr['subtitle']." ".$arr['introduction']." ".$arr['body']." ".$arr['keywords'];

	$words = unhtmlentities(strip_tags(str_replace(">", "> ", str_replace("<", " <",$words))));

	$result = preg_split ('/[^'.$allowed_chars.']/', $words);
	
	$filter = filter_words($result);

	add_to_index($filter, $arr['code']);

} 




function add_to_index ($arr,$code) {
	global $master_index;
	
	$arr = array_unique ($arr);
	
	foreach($arr as $string) {
		if(!isset($master_index[ $string{0} ][ $string ])) {
			$master_index[ $string{0} ][ $string ] = $code;
		} else {
			$master_index[ $string{0} ][ $string ] .= "|".$code;
		}
		
	}
	
	
}


function filter_words ($arr) {
	global $filtered_words, $allowed_chars;
	
	$clean = array();

	foreach($arr as $value) {
		
		$value = preg_replace ('/[^'.$allowed_chars.']/i','', strtolower($value));

		// save time by checking length of string
		if (is_array($filtered_words)) {
			if ((strlen($value) > 2) && (!in_array($value, $filtered_words)) ) {
					// 2004/09/19 =*=*= JM
					// I hope this doesn't slow things down too much...
					$clean[] = transliterate_accents( $value ) ;
			}
		} else {
			// apparently, $filtered_words is not an array..
		}
	}
	
	return $clean;
}


function write_index ($silent=FALSE) {
	global $master_index;

	if( is_array( $master_index )) {
		
		debug("saving ".count($master_index)." indices.");
		
		if( 0 != count( $master_index )) {

			$wordcount = 0;

			foreach($master_index as $key => $index) {
				$filename = "db/search/" . $key . ".php";
		
				// load the index if it exists..
				if (file_exists($filename)) {
					$temp = load_serialize($filename);
				} else {
					$temp = array();
				}
		
				// add the new stuff..
				foreach($index as $key=>$val) {
					if(isset($temp[$key])) {
						$occurr = explode("|", $temp[$key]);
						$occurr[] = $val;
						$val = implode("|", array_unique($occurr));
						$temp[$key] = $val;
					} else {
						$temp[$key] = $val;
					}
				}	
		
				save_serialize($filename, $temp);
				$wordcount += count($index);
			}
		
			if($silent!=TRUE) {
				echo("<p>A total of ".$wordcount." different words have been indexed.</p>");	
			}
		}
	} else {
		debug("nothing to save");	
	}
}


// ---------- functions for searching ------------- //
	// 2004/09/19 =*=*= JM
	// $str_a (the search string) is now an array
	// 0 -> for display
	// 1 -> for search [no accents]

function search_index ( $str_a ) {
	global $index_file, $matches, $db;

	$words = explode(" ", trim($str_a[1]));
	foreach($words as $key=>$val) {
		if(trim($val)=="") {
			unset($words[$key]);
		} else {
			$words[$key] = trim($val);
		}
	}
	

	if (count($words)>0) {
		foreach ($words as $word) {
			if (file_exists("db/search/".$word[0].".php")) {
				$index_file[ $word[0] ] = load_serialize("db/search/".$word[0].".php");
			}
		}
	}
	


	foreach($words as $word) {
		$res = getword($word);
		if($res) {
			$found_words[]=$word;
		}
	}

	// mix 'n match.. If the result set for 'AND' is empty, just lump
	// them together, so we have an 'OR'..
	if(count($matches)==1) {
		$result = $matches[0];
	} else if(count($matches)==2) { 
		list($word1,$word2) = $matches;
		$result = array_intersect($word1, $word2);
		if(count($result)==0) { $result = array_merge($word1, $word2); }
	} else if(count($matches)==3) {
		list($word1, $word2, $word3) = $matches;
		$result = array_intersect($word1, $word2, $word3);
		if(count($result)==0) { $result = array_merge($word1, $word2, $word3); }
	} else if(count($matches)>3) {
		list($word1, $word2, $word3, $word4) = $matches;
		$result = array_intersect($word1, $word2, $word3, $word4);
		if(count($result)==0) { $result = array_merge($word1, $word2, $word3, $word4); }
	}

	if(isset($found_words) && (count($found_words)>0)) {

		rsort($result);
		$result = array_unique($result);

		$output = str_replace('%name%', implode(', ',$found_words), lang('weblog_text', 'matches'))."<br />\n\n" ;
		$output .="<ul>\n\n";
		$db = new db();
		foreach($result as $hit) {
			
			if($db->entry_exists($hit)) {
				$entry = $db->read_entry($hit);
				$link = make_filelink($entry['code'], "", "");
				if ($entry['title']=="") {
					$entry['title'] = substr(strip_tags($entry['introduction']),0,50);
				}				
				$output .= "<li><!-- ". $entry['code']." --><a href='" . $link . "'>" . wordwrap($entry['title'], 40, " ", 1) . "</a><br /></li>\n";

			}

		}
		$output .="</ul>\n\n";	
	} else {
		// ¥¥¥ Just substitue original string in place				
		$output = str_replace('%name%', $str_a[0], lang('weblog_text', 'nomatches'))."<br />" ;
	}
	
	return $output;
}


// this one's for the entries screen..
function search_entries ($str) {
	global $index_file, $matches, $db;

	$words = explode(" ", trim($str));
	foreach($words as $key=>$val) {
		if(trim($val)=="") {
			unset($words[$key]);
		} else {
			$words[$key] = trim($val);
		}
	}
	

	if (count($words)>0) {
		foreach ($words as $word) {
			if (file_exists("db/search/".$word[0].".php")) {
				$index_file[ $word[0] ] = load_serialize("db/search/".$word[0].".php");
			}
		}
	}

	foreach($words as $word) {
		$res = getword($word);
		if($res) {
			$found_words[]=$word;
		}
	}

	// mix 'n match.. If the result set for 'AND' is empty, just lump
	// them together, so we have an 'OR'..
	if(count($matches)==1) {
		$result = $matches[0];
	} else if(count($matches)==2) { 
		list($word1,$word2) = $matches;
		$result = array_intersect($word1, $word2);
		if(count($result)==0) { $result = array_merge($word1, $word2); }
	} else if(count($matches)==3) {
		list($word1, $word2, $word3) = $matches;
		$result = array_intersect($word1, $word2, $word3);
		if(count($result)==0) { $result = array_merge($word1, $word2, $word3); }
	} else if(count($matches)>3) {
		list($word1, $word2, $word3, $word4) = $matches;
		$result = array_intersect($word1, $word2, $word3, $word4);
		if(count($result)==0) { $result = array_merge($word1, $word2, $word3, $word4); }
	}

	if(isset($found_words) && (count($found_words)>0)) {

		$db = new db();
		foreach($result as $hit) {
	
			$entry = $db->read_entry($hit);	
			if ($entry['title']=="") {
				$entry['title'] = substr(strip_tags($entry['introduction']),0,50);
			}
			unset($entry['comments']);
			unset($entry['introduction']);
			unset($entry['body']);
			$output[]=$entry;

		}

		return $output;
	} else {
		return array();
	}
}




function getword($word) {
	global $index_file, $db, $temp_entry, $matches;

	if(isset($index_file[ $word[0] ][ $word ])) {
		$matches[] = explode("|", $index_file[ $word[0] ][ $word ]);
		return TRUE;
	} else {
		return FALSE;
	}
}






function unhtmlentities ($string) {
	global $encode_html, $decode_html;

	if (!isset($encode_html)) {
		init_encode_table();
	}

	return strtr ($string, $decode_html);
}


// 2004/10/04 =*=*= changes for accessibility  - JM
// 2004/09/19 =*=*= JM - changes to preserve accents in searches - JM
// search is now an array
// 0 -> for display; 1 -> for search [no accents]
// 2004/11/26 =*=*= JM - correction for stylesheet

function search_result() {
    global $search_a ;
    
    $search_formname    = lang( 'accessibility','search_formname' ) ;
    $search_fldname     = lang( 'accessibility','search_fldname' ) ;
    $search_idname      = lang( 'accessibility','search_idname' ) ;
    $search_placeholder = lang( 'accessibility','search_placeholder' ) ;

    // build up accessible form
    $output  = '<br />'."\n" ;
    $output .= '<form method="post" action="search.php" class="pivot-search-result">'."\n" ;
    $output .= '<fieldset><legend>'.$search_formname.'</legend>'."\n" ;
    $output .= '<label for="'.$search_idname.'">'.$search_fldname.'</label>'."\n" ;
    $output .= '<input id="'.$search_idname.'" type="text" name="search" class="result-searchbox" value="'.$search_a[0].'" onfocus="this.select();" />'."\n" ;
    $output .= '<input type="submit" class="result-searchbutton" value="'.lang( 'weblog_text','search' ).'" />'."\n" ;
    $output .= '</fieldset></form>'."\n" ;
    // add search results - if any
    $output .= search_index( $search_a ) ;

    return $output;
}



?>
