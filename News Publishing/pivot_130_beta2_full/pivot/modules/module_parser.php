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
if(!defined('INPIVOT')){ ('not in pivot'); }

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection


// ----------------------------------------------------
// first the general functions we use to generate pages
// ----------------------------------------------------
// Eventually, these will all be changed into one Class.


// parse entry parses an entry, given an id.
// the entry is saved, if specified so in $Weblogs,
// The global var $entry_html contains the parsed html
function parse_entry($id, $weblog="") {
	global $Weblogs, $db, $Current_weblog, $override_template;


	// if no entry is set, or the passed id differs from the one that's in $db->entry
	// we'll have to load it..
	if ( (!isset($db->entry['code'])) || ($id != $db->entry['code'])  ) {

		$db = new db(TRUE);
		if ($db->entry_exists($id)) {
			$entry = $db->read_entry($id);
		} else {
			piv_error("Entry Does not Exist!", "That entry either doesn't exist, or it isn't published yet.", 0);
		}
	}

	//if it's not set to 'publish', display the nondescript error as well
	if ($db->entry['status']!="publish") {
		piv_error("Entry Does not Exist!", "That entry either doesn't exist, or it isn't published yet.", 0);
	}


	$allcats = cfg_cats();

	// check to see if we need to be logged in..
	foreach ($db->entry['category'] as $cat) {
		if ( ($allcats[$cat]['nonpublic']==1) && ( snippet_registered() != "registered") ) {
			echo "You must be registered to view this page.";
			die();
		}
	}

	// figure out what weblogs this entry is in
	if ($weblog!="") {

		// if we override the template by setting a weblog, load it here.
		$Current_weblog=$weblog;

		// switch to weblog's language
		LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

		$template= $Weblogs[$weblog]['entry_template'];
		$html = generate_html($template, $weblog);

	} else {

		$in_weblogs = find_weblogs_with_cat($db->entry['category']);

		// either select a weblog that publishes this cat, or else just select the first.
		if (count($in_weblogs)==0) {
			reset($Weblogs);
			$Current_weblog = key($Weblogs);
		} else {
			$Current_weblog=$in_weblogs[0];

		}





		// switch to weblog's language
		LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

		// if we override a template by explicitly overriding it, load it here.
		if (isset($override_template)) {
			$template = $override_template;
		} else {
			$template= $Weblogs[$Current_weblog]['entry_template'];
		}
		$html = generate_html($template, $Current_weblog);

		if ($stopafterfirst) {
			break;
		}

	}

	return $html;

}

/**
 * Returns a piece of HTML that is prepended on written files. It triggers the
 * first line of defense against spammers.
 *
 * @param string $html
 * @return string
 */
function prepend_spamblock_code($html)  {
	global $pivot_path;
	$spamblock_code = sprintf('<?php
	// First line defense.
	if (file_exists("%sfirst_defense.php")) {
		include_once("%sfirst_defense.php");
		block_refererspam();
	}
	?>',$pivot_path,$pivot_path);

	$output = $spamblock_code.$html;
	return $output;
	}

function generate_html($template, $weblog) {
	global $db, $Weblogs, $Current_weblog, $totalfiles, $set_output_paths, $pivot_path;
	// Generate some html
	$template_html = load_template($template);

	$Current_weblog = $weblog;

	// should be unset before each archive and frontpage..
	$set_output_paths=FALSE;

	if (!($template_html)) {
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {
		$output = $template_html;
		$output = parse_step4($output);
		$output = tidy_html($output);
	}

	// save the entry, if necessary..
	if ($Weblogs[$Current_weblog]['live_entries']<>1) {

		$filename =  make_filename();

		makedir($Weblogs[$Current_weblog]['entry_path']);

		add_log("Write entry file: $filename");
		if(strstr($filename, ".php") != false)  {
			$output = prepend_spamblock_code($output);
			}
		write_file($filename, $output);

	}

	return $output;
}



function generate_frontpage($weblog) {
	global $Current_weblog, $Weblogs, $done_archives, $totalfiles, $Cfg, $base_url, $Allow_RSS, $set_output_paths;

	// make a key for this page
	$archive_key = $weblog."-front";

	// return, if this page has already been made.
	if ( isset($done_archives[$archive_key]) && $done_archives[$archive_key] ) {
		return;
	} else {
		$done_archives[$archive_key]=TRUE;
	}

	// switch to weblog's language
	LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

	$template_html = load_template($Weblogs[$weblog]['front_template']);

	 if ( ($Weblogs[$weblog]['rss']==1) && ($Allow_RSS) ) {
			start_rss();
	 }

	// should be unset before each archive and frontpage..
	$set_output_paths=FALSE;

	if (!($template_html)) {
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {
		$output = "[[tick]]".$template_html;
		$output = parse_step4($output);
		$output = tidy_html($output);
	}

	$filename = $Weblogs[$weblog]['front_path'] . $Weblogs[$weblog]['front_filename'];

	//make sure the directory exists
	makedir($Weblogs[$weblog]['front_path']);

	add_log("Write frontpage file: $filename");
	if(strstr($filename, ".php"))  {
		$output = prepend_spamblock_code($output);
		}
	write_file($filename, $output);

	$totalfiles++;
	debug("total_f: $filename - ".getcwd());

	// finish RSS
	if ( ($Weblogs[$weblog]['rss']==1) && ($Allow_RSS) ) {
		finish_rss();
	}


	$weblogname= urlencode($Weblogs[$Current_weblog]['name']);
	$filename = urlencode(fixpath($base_url.$filename));

	return $filename;

}



function generate_live_page($weblog="", $category="", $template="", $user="") {
	global $Current_weblog, $Paths, $Weblogs, $Cfg, $set_output_paths, $db;




	// Switch to weblog that was passed as parameter. Else use the first one..
	if (isset($Weblogs[$weblog])) {
		$Current_weblog = $weblog;
	} else {
		reset ($Weblogs);
		$Current_weblog = (key($Weblogs));
	}

	$allcats = cfg_cats();
	$requirelogin = FALSE;

	// perhaps filter on categories..
	if (!$category=="") {
		$category=explode(",", $category);

		foreach ($category as $key => $val) {
			if ($allcats[$val]['nonpublic']==1) { $requirelogin = TRUE; }
			$category[$key] = trim($val);
		}

		// Try to squeeze filtered cats into 'standard'.. Else pick the first subweblog..
		if (isset($Weblogs[$Current_weblog]['sub_weblog']['standard'])) {
			$Weblogs[$Current_weblog]['sub_weblog']['standard']['categories'] = $category;
		} else {
			$subweblog = (key($Weblogs[$Current_weblog]['sub_weblog']));
			$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['categories'] = $category;
		}
	}

	if ( ($requirelogin) && ( snippet_registered() != "registered") ) {
		echo "You must be registered to view this page.";
		die();
	}


	$db = new db(TRUE);
	// switch to weblog's language
	LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

	if ( $template != "") {
		$template_html = load_template($template);
	} else 	if ( ($weblog=="") && ($category=="") && ($user=="")) {
		$template_html = load_template($Weblogs[$Current_weblog]['front_template']);
	} else {
		$template_html = load_template($Weblogs[$Current_weblog]['archive_template']);
	}


	// should be unset before each archive and frontpage..
	$set_output_paths=FALSE;

	if (!($template_html)) {
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {

		$output = "[[tick]]".$template_html;
		$output = parse_step4($output);
		$output = tidy_html($output);
	}

	return $output;

}




function generate_archive($weblog, $date) {
	global $Current_weblog, $Weblogs, $done_archives, $totalfiles, $set_output_paths;

	// make a key for this archive
	$archive_key = $weblog."-".make_archive_name($date);

	// return, if this archive has already been made.
	if ( isset($done_archives[$archive_key]) && $done_archives[$archive_key] ) {
		return;
	} else {
		$done_archives[$archive_key]=TRUE;
	}

	// switch to weblog's language
	LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

	$template_html = load_template($Weblogs[$weblog]['archive_template']);
	$template_html = str_replace("[[weblog", "[[archive", $template_html);
	$template_html = str_replace("[[subweblog", "[[archive", $template_html);

	$filename = $Weblogs[$weblog]['archive_path'] . make_archive_name($date);

	// should be unset before each archive and frontpage..
	$set_output_paths=FALSE;

	if (!($template_html)) {
		echo "never this";
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {
		$output = $template_html;
		$output = parse_step4($output);
		$output = tidy_html($output);
		if(strstr($filename, ".php"))  {
			$output = prepend_spamblock_code($output);
			}
	}

	//make sure the directory exists
	makedir($Weblogs[$weblog]['archive_path']);

	add_log("Write archive file: $filename");
	write_file($filename, $output);

	$totalfiles++;
	debug("total_a: $date");
}

// one function, to generate entries, frontpages and archives
function generate_pages($id, $singlepage=TRUE, $frontpage=TRUE, $archive=TRUE, $ping=FALSE, $rss= TRUE) {
	global $db, $Weblogs, $Current_weblog, $Allow_RSS;

	//LoadTempLanguage();

	$Allow_RSS = $rss;


	// load an entry
	$entry = $db->read_entry($id, TRUE);

	$in_weblogs = find_weblogs_with_cat($entry['category']);


	foreach ($in_weblogs as $in_weblog) {

		$entry = $db->read_entry($id);
		$org_date = $entry['date'];

		$Current_weblog=$in_weblog;
		$template= $Weblogs[$in_weblog]['entry_template'];

		// we generate the single entry..
		if ($Weblogs[$in_weblog]['live_entries']<>1) {
			generate_html($template, $in_weblog);
		}

		// generate the archives
		if ($archive && $Weblogs[$Current_weblog]['archive_unit']!='none')  {
			generate_archive($in_weblog, $org_date);
		}

		// generate the frontpage
		if ($frontpage) {
			$filename = generate_frontpage($in_weblog);
		}

		// if we need to ping. do here:
		if ($ping) {

		  $path = $Weblogs[$in_weblog]['front_path'].$Weblogs[$in_weblog]['front_filename'];

			open_ping_window($in_weblog, $path);
		}


		LoadUserLanguage();

	}


}


// ----------------------------------------------------
// Then the functions in which the actual parsing takes place
// ----------------------------------------------------


function parse_step4 ($text) {
	global $parse_recursion;

	// We keep a counter to prevent infinite loops. The maximum amount of
	// recursion is 4..
	if (isset($parse_recursion)) {
		$parse_recursion++;
	} else {
		$parse_recursion = 1;
	}

	if ($parse_recursion > 4) { return $text; }

	preg_match_all ("|\[\[(.*)\]\]|U", $text, $match, PREG_PATTERN_ORDER);

	foreach($match[1] as $snippet_code) {
		$snippet_replace= snippet_parse ( $snippet_code );
		$text=str_replace("[[".$snippet_code."]]", $snippet_replace, $text);
	}

	$parse_recursion = 0;

	return $text;
}


function parse_intro_or_body ($text, $strip="") {
	global $db, $Weblogs, $Current_weblog;

	$output = $text;

	if ($strip=="strip") {
		$output = strip_tags($output,"<a><b><i><u><embed><strong><ol><li><ul>");
	}


	if ($db->entry['convert_lb']==1) {
		$output = convert_linebreaks( $output );
	} else if ($db->entry['convert_lb']==2) {
		$output = pivot_textile( $output );
	} else if ( ($db->entry['convert_lb']==3) || ($db->entry['convert_lb']==4) ) {
		$output = pivot_markdown( $output, $db->entry['convert_lb'] );
	}

	// emoticons..
	if ($Weblogs[$Current_weblog]['emoticons']==1) {
		$output=emoticonize( $output );
	}

	// targetblank
	if ($Weblogs[$Current_weblog]['target_blank']>0) {
		$output =targetblank( $output );
	}

	$output = parse_step4( $output );

 	return tidy_html($output);

}

// ------------------------------------------
// These functions are used to tidy up the general nastyness
// in html generated by wysiwyg editors in IE and Mozilla,

function tidy_html($text, $thorough=FALSE) {

	// Change <br /><br /> into </p><p>
	$text = preg_replace("/<br( [^>]*)?>\s*<br( [^>]*)?>/Ui", "</p>\n<p>", $text);

	// clean up empty paragraphs
	$text = preg_replace("/<p>[\s|&nbsp;]*<\/p>/Ui", "</p>", $text);

	// Clean up loose br's inside or outside of paragraphs.
	$text = preg_replace("/<\/p>\s*<br( [^>]*)?>/Ui", "</p>", $text);
	$text = preg_replace("/<br( [^>]*)?>\s*<\/p>/Ui", "</p>", $text);
	$text = preg_replace("/<p>\s*<br( [^>]*)?>/Ui", "<p>", $text);
	$text = preg_replace("/<br( [^>]*)?>\s*<p>/Ui", "<p>", $text);

	// clean <p><p> and </p></p>
	$text = preg_replace("/<p( [^>]*)?>\s*<p( [^>]*)?>/Ui", "<p\\1\\2>", $text);
	$text = preg_replace("/<\/p>\s*<\/p>/Ui", "</p>\n", $text);

	// after this, we might end up starting with a closing </p>. We don't want that.
	$text = preg_replace("/^\s*<\/p>/Ui", "", $text);

	// clean up <div>'s in <p>'s
	$text = preg_replace("/<p>\s*<div(.*)>(.*)<\/div>\s*<\/p>/Ui", "<div\\1>\\2</div>\n", $text);

	$text = preg_replace_callback("/<p>(\s*)<div(.*)>(.*)<\/div>(\s*)<\/p>/Ui", "tidy_html_callback_nesteddivs", $text);

	if ($thorough) {
		$text = preg_replace_callback("/<(.*)>/Ui", 'tidy_html_callback', $text);
	}

	return $text;

}

function tidy_html_callback($match) {

	$match = $match[0];

	//first, change the tag to lowercase (added the "." because otherwise it breaks my editor's syntax highlighting)
	$match = preg_replace_callback("/<(\/"."*)([a-z]+)([\s|>])/i", "tidy_html_callback_changetag", $match);

	//then, change attributes to lowercase, making sure they are quoted..
	$match = preg_replace_callback('/(\s[a-z]+)="(([^"\\\\]|\\.)+)"/i', "tidy_html_callback_doublequote", $match);
	$match = preg_replace_callback('/(\s[a-z]+)=([a-z0-9]+)/i', "tidy_html_callback_doublequote", $match);
	$match = preg_replace_callback("/(\s[a-z]+)='(([^'\\\\]|\\.)+)'/i", "tidy_html_callback_singlequote", $match);

	//this one doesn't work..
	//$match = preg_replace_callback("/\s([a-z]+)=([\s>])/i", "tidy_html_callback_novalueattr", $match);



	// change 'optional' non closing tags to resemble proper xhtml..
	$match = preg_replace("/<br([^\/]*)>/Ui", "<br \\1 />", $match);
	$match = preg_replace("/<hr([^\/]*)>/Ui", "<hr \\1 />", $match);
	$match = preg_replace("/<img([^\/]*)>/Ui", "<img \\1 />", $match);
	$match = preg_replace("/<input([^\/]*)>/Ui", "<input \\1 />", $match);

	return $match;
}


function tidy_html_callback_changetag($match) {
	return "<".$match[1].strtolower($match[2]).$match[3];
}


function tidy_html_callback_doublequote($match) {
	return strtolower($match[1])."=\"".$match[2]."\"";
}


function tidy_html_callback_singlequote($match) {
	return strtolower($match[1])."='".$match[2]."'";
}


function tidy_html_callback_novalueattr($match) {

	//echo "<pre>";
 //print_r($match);
 //echo "</pre>";

	return " ".strtolower($match[1])."='".$match[1]."'".$match[2];
}


function tidy_html_callback_nesteddivs($match) {

	$output="";

	if (strlen(trim($match[1]))>2) { $output .= "<p>".trim($match[1])."</p>\n"; }

	$output .= "<div".$match[2].">".$match[3]."</div>\n";

	if (strlen(trim($match[4]))>2) { $output .= "<p>".trim($match[1])."</p>\n"; }

	return $output;
}



// -- End tidy functions ---------------


// 2004/12/01 =*=*= changes for drop-in snippets...
function snippet_parse( $snippet_code ) {
    global $Cfg, $Paths;

    @list( $command, $para1, $para2, $para3, $para4, $para5, $para6, $para7, $para8 ) = preg_split(  "/:(?!\/\/)/",$snippet_code,-1 );

    $insert   = '';
    $command  = str_replace( '-','_',$command );
    $command  = str_replace( '/','slash',$command );
    $function = 'snippet_'.$command;

    // has the snippet already been declared?
    if( function_exists( $function )) {
        $insert = $function( $para1, $para2, $para3, $para4, $para5, $para6, $para7, $para8 );
    } else {
        // determine the path to the extensions..

        $extensions_path = $Paths['extensions_path'];

        // do the 'extensions/snippets/ directories' exist?
        if( file_exists( realpath(  $extensions_path . 'snippets/snippet_' . $command.'.php' ))) {
            // pull in the snippet
            include_once( realpath( $extensions_path . 'snippets/snippet_' . $command.'.php' ));
            // final test
            if( function_exists( $function )) {
                $insert = $function( $para1,$para2,$para3,$para4,$para5 );
            } else {
            	$insert = '<!-- snippet ='.$command.'= is not defined -->';
            	debug( 'snippeterror: '.$command );
            }
        } else {
            $insert = '<!-- snippet ='.$command.'= is not defined -->';
            debug( 'snippeterror: '.$command );
        }
    }
    if($snippet_code == 'commentform')  {
	// append spam key to the comment form
	global $entry;
	global $Pivot_Vars;
	if(!isset($entry['code']))  {
		$insert = str_replace('<input type="hidden" name="piv_code', '<input type="hidden" name="piv_spkey" value="'.md5($Cfg['server_spam_key'].$Pivot_Vars['id']).'" />'."\n".'<input type="hidden" name="piv_code', $insert);
	}
	else  {
		$insert = str_replace('<input type="hidden" name="piv_code', '<input type="hidden" name="piv_spkey" value="'.md5($Cfg['server_spam_key'].$entry['code']).'" />'."\n".'<input type="hidden" name="piv_code', $insert);
		}
      }
    return $insert;
}


// ----------------------------------------------------
// Finally some auxillary functions.
// ----------------------------------------------------

function load_template($basename) {
	global $template_cache, $pivot_path;

	$filename= $pivot_path."templates/$basename";

	if (isset($template_cache[$basename])) {
		return $template_cache[$basename];
	} else {

		if (!(file_exists($filename))) {
			$filename=$pivot_path."templates/entrypage_template.html";
		}

		$filetext=implode("", file($filename));

		$template_cache[$basename]=$filetext;

		return $filetext;
	}

}



// =============================================
// the functions below are used for processing the <cms> tags
// into <html>.
// =============================================

function cms_tag_weblog($tag_attr, $tag_default){
	global $db,  $Cfg, $current_date, $Weblogs, $Current_weblog, $Current_subweblog, $diffdate_lastformat, $even_odd, $Pivot_Vars;

	// some people use [[subweblog]] inside an entry page. to allow this
	// we need to 'store' the entry, make the subweblog, and 'restore'
	// the entry..
	if (isset($db->entry)) {     $temp_entry = $db->entry; }

	$order=get_attr_value('order', $tag_attr);
	if ($order=="firsttolast") {
		$order="asc";
	} else {
		$order="desc";
	}

	$countshow=0;
	$output="";



	// start output of RSS, if necessary
	if  ( (isset($global_pref['rss'])) && ($global_pref['rss']!="") ) { start_rss(); }

	// to force the 'diffdate' to start anew on each (sub)weblog..
	$diffdate_lastformat="";

	$subweblog=get_attr_value('subweblog', $tag_attr);
	$Current_subweblog = $subweblog;

	if (file_exists("templates/".$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template'])) {
		$tag_default_orig= implode("", file("templates/".$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template']));
	} else {
		piv_error("File does not exist!", "Could not load template file 'templates/". $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template'] ."'. Make sure it exists, and has the right permissions", 0);
	}

	// safety check to prevent recursive weblogs..
	if (preg_match("/\[\[weblog:(.*)(:[0-9]*)?\]\]/mUi", $tag_default_orig)) {
		 $tag_default_orig = "<p>(You can't recursively use [weblogs]!)</p>";
	}

	$show = $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['num_entries'];
	$cats = $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['categories'];
	$offset = $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['offset'];

	// If called from a 'dynamic archive page', the offset needs to be taken into account.
	if ($Pivot_Vars['o']>0) {
		$offset += 	$Pivot_Vars['o'];
	}


	$db->disallow_write();
	$list_entries = $db->getlist_end(-$show-$offset,"",$cats);

	if (count($list_entries)>0) {
		foreach ($list_entries as $list_entry) {
			// if offset > 0, we need to skip this entry
			if ($offset>0) {
				$offset--;
				continue;
			}


			$entry = $db->read_entry($list_entry['code'], TRUE);
			if ( (!isset($entry['status'])) || ($entry['status']=='publish') ) {

				// for 'even' and 'odd' messages..
				if ($even_odd == 1) {
					$even_odd = 0;
				} else {
					$even_odd = 1;
				}

				// include an anchor, if it's not set manually with the [[id_anchor]] tag
				if (strpos($tag_default_orig, "[[id_anchor]]")==0) {
					$entry_html = '<span id="e'.$db->entry['code'].'"></span>';
				} else {
					$entry_html ="";
				}

				$entry_html .= parse_step4($tag_default_orig);

				$output.=$entry_html;
				if ($Weblogs[$Current_weblog]['rss']==1) {
					add_rss($entry['code'], $entry['date'], $entry['title'], $entry['introduction'], $entry['body'], $entry['user'], $entry['category'] );
				}
			}
		}
	}

	// perhaps restore the entry
	if (isset($temp_entry)) { $db->set_entry($temp_entry); }

	return $output;

}



function cms_tag_archive($tag_attr, $tag_default){
	global $db,  $Cfg, $current_date, $Weblogs, $Current_weblog, $diffdate_lastformat, $even_odd;

	$order=get_attr_value('order', $tag_attr);
	if ($order=="firsttolast") {
		$order="asc";
	} else {
		$order="desc";
	}

	$countshow=0;
	$output="";

	// start output of RSS, if necessary
	if  ( (isset($global_pref['rss'])) && ($global_pref['rss']!="") ) { start_rss(); }

	// to force the 'diffdate' to start anew on each (sub)weblog..
	$diffdate_lastformat="";

	$subweblog=get_attr_value('subweblog', $tag_attr);


	if ( (file_exists("templates/".$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template'])) && ($Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template'] != "") ) {
		$tag_default_orig= implode("", file("templates/".$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template']));
	} else {
		debug("cant open file: ".$Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['template']);
		debug(" - for weblog ".$Current_weblog.", subweblog ".$subweblog . " - $tag_attr, $tag_default - ");
		$tag_default_orig = "";

		//debug_printbacktrace();

	}

	// safety check to prevent recursive weblogs..
	if (preg_match("/\[\[weblog:(.*)(:[0-9]*)?\]\]/mUi", $tag_default_orig)) {
		 $tag_default_orig = "<p>(You can't recursively use [weblogs]!)</p>";
	}

	$show = $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['num_entries'];
	$cats = $Weblogs[$Current_weblog]['sub_weblog'][$subweblog]['categories'];

	list($start_date, $stop_date) = getdaterange($db->entry['date'], $Weblogs[$Current_weblog]['archive_unit'] );

	$db->disallow_write();
	$list_entries = $db->getlist_range($start_date, $stop_date,"",$cats, FALSE);

	foreach ($list_entries as $list_entry) {
		$entry = $db->read_entry($list_entry['code']);
		if ( (!isset($entry['status'])) || ($entry['status']=='publish') ) {

				// for 'even' and 'odd' messages..
				if ($even_odd == 1) {
					$even_odd = 0;
				} else {
					$even_odd = 1;
				}

				// include an anchor, if it's not set manually with the [[id_anchor]] tag
				if (strpos($tag_default_orig, "[[id_anchor]]")==0) {
					$entry_html = '<span id="e'.$db->entry['code'].'"></span>';
				} else {
					$entry_html ="";
				}

				$entry_html .= parse_step4($tag_default_orig);
			$output.=$entry_html;
		}
	}

	return $output;

}


function cms_tag_comments($tag_attr, $tag_default){
	global $db, $global_pref, $row, $block, $Current_weblog, $Weblogs, $Paths;

	// load the functions for ip-blocking, if necessary..
	include_once $Paths['pivot_path']."modules/module_ipblock.php";

	if (strlen($Weblogs[$Current_weblog]['comment_format'])>1) {
		$format = $Weblogs[$Current_weblog]['comment_format'];
	} else {
		$format = "%anchor%<p>%comment%</p><p><small><b>%name%</b> %email% %url% - %date%</small></p>";
	}

	if (strlen($Weblogs[$Current_weblog]['comment_reply'])>1) {
		$format_reply = $Weblogs[$Current_weblog]['comment_reply'];
	} else {
		$format_reply = "Reply on %name%";
	}

	if (strlen($Weblogs[$Current_weblog]['comment_forward'])>1) {
		$format_forward = $Weblogs[$Current_weblog]['comment_forward'];
	} else {
		$format_forward = "Replied on by %name%";
	}


	if (strlen($Weblogs[$Current_weblog]['comment_backward'])>1) {
		$format_backward = $Weblogs[$Current_weblog]['comment_backward'];
	} else {
		$format_backward = "This is a reply on %name%";
	}



	$content_code=get_attr_value('content_code', $tag_attr);
	$nocomments=get_attr_value('ifnocomments', $tag_attr);
	$comments=get_attr_value('ifcomments', $tag_attr);
	$entrydate=$Weblogs[$Current_weblog]['fulldate_format'];


	if ($content_code=="") { $content_code=$row["code"]; }

	$output='<a id="comm"></a>';
	$last_comment="";

	if ((isset($db->entry['comments']))&&(count($db->entry['comments'])>0)) {

		// first, make a list of comment-on-comments..
		$crosslink = array();

		foreach ($db->entry['comments'] as $count => $temp_row) {
			if(preg_match("/\[(.*):([0-9]*)\]/Ui",$temp_row['comment'], $matches)) {
				$crosslink[$count+1] = $matches[2];
				// remove [name:1] from comment..
				$db->entry['comments'][$count]['comment'] = str_replace($matches[0], "", $db->entry['comments'][$count]['comment']);
			}
		}

		foreach ($db->entry['comments'] as $count => $temp_row) {

			// this is a record we have to output in some form..
			$temp_row['name'] = strip_tags($temp_row['name']);
			$temp_row['email'] = strip_tags($temp_row['email']);
			$temp_row['url'] = strip_tags($temp_row['url']);

			if ( ($temp_row["ip"].$temp_row["comment"]!=$last_comment) && (!(ip_check_block($temp_row["ip"]))) ){

				// make email link..
				if (isemail($temp_row["email"]) && !$temp_row["discreet"]) {
					$email_format = "(".encodemail_link($temp_row["email"], "email", $temp_row["name"]).")";
					$emailtoname = encodemail_link($temp_row["email"], $temp_row["name"], $temp_row["name"]);
				} else {
					$email_format = "";
					$emailtoname = $temp_row["name"];
				}

				if (isemail($temp_row["email"])) {

					$grav_email = $temp_row["email"];
					$grav_default=$Weblogs[$Current_weblog]['comment_gravatardefault'];
					$grav_html=stripslashes($Weblogs[$Current_weblog]['comment_gravatarhtml']);
					$grav_size=$Weblogs[$Current_weblog]['comment_gravatarsize'];

					if ($grav_default == "") { $grav_default = "http://www.pivotlog.net/images/gravatar.gif"; }

					if ($grav_html == "") {
						$grav_html = '<img src="%img%" align="right" valign="top" hspace="2" vspace="2" />';
					}

					if ($grav_size == "") { $grav_size = 48; }


					$grav_imgurl = "http://www.gravatar.com/avatar.php?gravatar_id=" .
										md5($grav_email) .
										"&amp;default=" .
										urlencode($grav_default) .
										"&amp;size=" .
										$grav_size;

					$grav_url = str_replace("%img%", $grav_imgurl, $grav_html);
					//debug("GU: ". htmlentities($grav_url));
				} else {
					$grav_url = "";
				}

				// make url link..
				if (isurl($temp_row["url"])) {
					if (strpos($temp_row["url"], "ttp://") < 1 ) {
						$temp_row["url"]="http://".$temp_row["url"];
					}

					$target= ($Weblogs[$Current_weblog]['target_blank']==1) ? " target='_blank'" : "";

					$temp_row["url_title"]= str_replace('http://', '', $temp_row["url"]);

					//perhaps redirect the link..
					if (isset($Weblogs[$Current_weblog]['lastcomm_redirect'])  && ($Weblogs[$Current_weblog]['lastcomm_redirect']==1) ) {
						// $temp_row["url"] = str_replace("http://", $Paths['pivot_url']."includes/re.php?http://",  $temp_row["url"]);
						$target .= " rel=\"nofollow\" ";
					}

					$url_format = sprintf("(<a href='%s' $target title='%s'>link</a>)",
												$temp_row["url"], $temp_row["url_title"]);
					$urltoname = sprintf("<a href='%s' $target title='%s'>%s</a>",
												$temp_row["url"], $temp_row["url_title"], $temp_row['name']);
				} else {
					$url_format = "";
					$urltoname = $temp_row["name"];
				}

				// make a 'registered user' span..
				if ($temp_row['registered']==1) {
					$name = "<span class='registered'>[" . $temp_row["name"] . "]</span>";
				} else {
					$name = $temp_row["name"];
				}

				// make quote link..
				$quote = sprintf("<a href='#form' onclick='javascript:var pv=document.getElementsByName(\"piv_comment\");pv[0].value=\"[%s:%s] \"+pv[0].value;'>%s</a>",
					$temp_row["name"], $count+1, $format_reply );

				// make backward link..
				if (isset($crosslink[$count+1])) {
					$to = $db->entry['comments'][ ($crosslink[$count+1] - 1) ];
					$backward_text = str_replace("%name%", $to['name'], $format_backward);
					$backward_anchor = safe_string($to["name"],TRUE) ."-". format_date($to["date"],"%ye%%month%%day%%hour24%%minute%");
					$backward_link = sprintf("<a href='#%s'>%s</a>", $backward_anchor, $backward_text);
				} else {
					$backward_link = "";
				}

				// make forward link..
				$forward_link = "";
				foreach ($crosslink as $key => $val) {
					if (($val-1) == $count) {
						$from = $db->entry['comments'][ ($key-1) ];
						$forward_text = str_replace("%name%", $from['name'], $format_forward);
						$forward_anchor = safe_string($from["name"],TRUE) ."-". format_date($from["date"],"%ye%%month%%day%%hour24%%minute%");
						$forward_link .= sprintf("<a href='#%s'>%s</a> ", $forward_anchor, $forward_text);
					}
				}

				$anchor = "<a id=\"" . safe_string($temp_row["name"],TRUE) ."-". format_date($temp_row["date"],"%ye%%month%%day%%hour24%%minute%") ."\"></a>";

				$this_tag= $format;
				//$this_tag= str_replace("%uid%", $temp_row["uid"], $this_tag);
				$this_tag= str_replace("%quote%", $quote, $this_tag);
				$this_tag= str_replace("%quoted-back%", $backward_link, $this_tag);
				$this_tag= str_replace("%quoted-forward%", $forward_link, $this_tag);
				$this_tag= str_replace("%count%", $count+1, $this_tag);
				$this_tag= str_replace("%even-odd%", ( (($count)%2) ? 'even' : 'odd' ), $this_tag);
				$this_tag= str_replace("%ip%", $temp_row["ip"], $this_tag);
				$this_tag= str_replace("%date%", format_date($temp_row["date"],$entrydate), $this_tag);
				$this_tag= str_replace("%comment%", comment_format($temp_row["comment"]), $this_tag);
				$this_tag= str_replace("%name%", $name, $this_tag);
				$this_tag= str_replace("%email%", $email_format, $this_tag);
				$this_tag= str_replace("%url%", $url_format, $this_tag);
				$this_tag= str_replace("%anchor%", $anchor, $this_tag);
				$this_tag= str_replace("%url-to-name%", $urltoname, $this_tag);
				$this_tag= str_replace("%email-to-name%", $emailtoname, $this_tag);
				$this_tag= str_replace("%gravatar%", $grav_url, $this_tag);

				$output.=$this_tag;
				$last_comment=$temp_row["ip"].$temp_row["comment"];

			}
		}
	}

	// make an array of strings with the notices to print
	$text = array($Weblogs[$Current_weblog]['comments_text_0'], $Weblogs[$Current_weblog]['comments_text_1'], $Weblogs[$Current_weblog]['comments_text_2']);

//	$comm_ind = sprintf("<p><b>%s:</b></p>", $text[min(2,count($db->entry['comments']))]);
//	$comm_ind = str_replace("%num%", lang('numbers', count($db->entry['comments'])), $comm_ind);
//	$comm_ind = str_replace("%n%", count($db->entry['comments']), $comm_ind);

	$output = $output;

	return $output;
}

// get archive name for a certain entry. Used to make permalinks
function make_archive_name($date="", $this_weblog="") {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	if ($date=="") {
		if (isset($db->entry)) {
			$date = $db->entry['date'];
		} else {
			$date = date("Y-m-d-H-i");
		}
	}


	$year = format_date($date, "%year%");

	if ($this_weblog=="") {
		$this_weblog = $Weblogs[$Current_weblog];
	} else {
		$this_weblog = $Weblogs[$this_weblog];
	}

    $archive_num = (($this_weblog['archive_unit']=="week")  ? format_date($date, "%weeknum%") :
                   (($this_weblog['archive_unit']=="month") ? format_date($date, "%month%")   :  ''  ));
    $archive_type= (($this_weblog['archive_unit']=="week")  ? "w" : (($this_weblog['archive_unit']=="month") ? "m" :  "y" ));

	$archive_name=sprintf("%s-%s%02d", $year, $archive_type, $archive_num);
	$archive_name=str_replace("%1",$archive_name,$this_weblog['archive_filename']);


	return $archive_name;
}

function cms_tag_trackbacks($tag_attr, $tag_default){
    global $db, $global_pref, $row, $block, $Current_weblog, $Weblogs, $Paths;

    // load the functions for ip-blocking, if necessary..
    include_once $Paths['pivot_path']."modules/module_ipblock.php";

    if (strlen($Weblogs[$Current_weblog]['trackback_format'])>1) {
        $format = $Weblogs[$Current_weblog]['trackback_format'];
    } else {
        $format = "%anchor%<p><strong>%title%</strong><br />%excerpt%<br /><small>Sent on %date%, via %url%</small></p>";
    }

    $content_code=get_attr_value('content_code', $tag_attr);
    $entrydate=$Weblogs[$Current_weblog]['fulldate_format'];

    if ($content_code=="") { $content_code=$row["code"]; }

    $output='<a id="track"></a>';
    $last_trackback="";

    if ((isset($db->entry['trackbacks']))&&(count($db->entry['trackbacks'])>0)) {

        foreach ($db->entry['trackbacks'] as $count => $temp_row) {

            // this is a record we have to output in some form..
            $temp_row['url'] = strip_tags($temp_row['url']);

            if (!ip_check_block($temp_row["ip"])){

                $anchor = "<a id=\"" . safe_string($temp_row["name"],TRUE) ."-". format_date($temp_row["date"],"%ye%%month%%day%%hour24%%minute%") ."\"></a>";

                $this_tag= $format;
                $this_tag= str_replace("%count%", $count+1, $this_tag);
                $this_tag= str_replace("%even-odd%", ( (($count)%2) ? 'even' : 'odd' ), $this_tag);
                $this_tag= str_replace("%ip%", $temp_row["ip"], $this_tag);
                $this_tag= str_replace("%date%", format_date($temp_row["date"],$entrydate), $this_tag);
                $this_tag= str_replace("%excerpt%", comment_format($temp_row["excerpt"]), $this_tag);
                $this_tag= str_replace("%title%", $temp_row["title"], $this_tag);
                $url = '<a href="'.$temp_row["url"].'">'.  stripslashes($temp_row["name"]).'</a>';
                $this_tag= str_replace("%url%", $url, $this_tag);
                $this_tag= str_replace("%anchor%", $anchor, $this_tag);


                $output.=$this_tag;
            }
        }
    }

    // make an array of strings with the notices to print
    $text = array($Weblogs[$Current_weblog]['trackbacks_text_0'], $Weblogs[$Current_weblog]['trackbacks_text_1'], $Weblogs[$Current_weblog]['trackbacks_text_2']);

 //   $track_ind = sprintf("<p><b>%s:</b></p>", $text[min(2,count($db->entry['trackbacks']))]);
 //   $track_ind = str_replace("%num%", lang('numbers', count($db->entry['trackbacks'])), $track_ind);
 //   $track_ind = str_replace("%n%", count($db->entry['trackbacks']), $track_ind);
    $output = $output;

    return $output;
}


function make_archive_link($date="") {
	global $db, $Paths, $Weblogs, $Current_weblog;

	if ($date=="") { $date = $db->entry['date']; }

	$filelink = $Paths['pivot_url'] . $Weblogs[$Current_weblog]['archive_path'] . make_archive_name($date);
	$filelink = fixPath($filelink);

	return $filelink;

}


function make_archive_array($force=FALSE) {
	global $Archive_array, $db;

	$arc_db = new db();

	$Archive_array = $arc_db->get_archive_array();



}


function make_filename($code="", $weblog="", $anchor="comm", $parameter="") {
	global $db, $Paths, $Weblogs, $Current_weblog;

	if ($code=="") { $code = $db->entry['code']; }
	if ($weblog=="") { $weblog=$Current_weblog; }

	$filename =  $Weblogs[$weblog]['entry_path'] . $Weblogs[$weblog]['entry_filename'];
	$filename = str_replace("%1", $code, $filename);
	$filename = format_date("", $filename);

	return $filename;

}

function make_filelink($code="", $weblog="", $anchor="comm", $parameter="", $para_weblog=FALSE) {
	global $db, $Weblogs, $Current_weblog, $Cfg, $temp_entry, $Paths;

	if ($code=="") { $code = $db->entry['code']; }
	if ($weblog=="") { $weblog=$Current_weblog; }

	// for non-crufty urls
	if (!isset($temp_entry) || (!is_array($temp_entry)) ) { @$temp_entry = $db->entry; }

	if (!$Weblogs[$weblog]['live_entries']) {
		$filelink = $Paths['pivot_url'] . $Weblogs[$Current_weblog]['entry_path'] . $Weblogs[$Current_weblog]['entry_filename'];
	} else if ( (isset($Cfg['mod_rewrite'])) && ($Cfg['mod_rewrite']!=0) && ($temp_entry['date']!="") ) {
		// if $temp_entry['date'] is not set, we cant make the non-crufty url,
		// and we fall back to the crufty one..

		switch ($Cfg['mod_rewrite']) {

			// archive/2005/04/20/title_of_entry
			case "1":

				$name = (strlen($temp_entry['title'])>1) ? $temp_entry['title'] : substr(strip_tags($temp_entry['introduction']),0,70);
				$name = safe_string(trim($name), TRUE);
				if (strlen($name)>30) { $name = substr($name, 0, 30); }

				list($yr,$mo,$da,$ho,$mi)=split("-",$temp_entry['date']);
				$filelink = $Paths['log_url'] . "/archive/$yr/$mo/$da/".$name;

				break;

			// archive/2005-04-20/title_of_entry
			case "2":

				$name = (strlen($temp_entry['title'])>1) ? $temp_entry['title'] : substr(strip_tags($temp_entry['introduction']),0,70);
				$name = safe_string(trim($name), TRUE);
				if (strlen($name)>30) { $name = substr($name, 0, 30); }

				list($yr,$mo,$da,$ho,$mi)=split("-",$temp_entry['date']);
				$filelink = $Paths['log_url'] . "/archive/$yr-$mo-$da/".$name;

				break;

			// entry/1234
			case "3":

				$filelink = $Paths['log_url'] . "/entry/".$temp_entry['code'];

				break;

			// entry/1234/title_of_entry
			case "4":

				$name = (strlen($temp_entry['title'])>1) ? $temp_entry['title'] : substr(strip_tags($temp_entry['introduction']),0,70);
				$name = safe_string(trim($name), TRUE);
				if (strlen($name)>30) { $name = substr($name, 0, 30); }

				$filelink = $Paths['log_url'] . "/entry/".$temp_entry['code']."/$name";

				break;

		}

	} else {
		$filelink = $Paths['pivot_url'] . "entry.php?id=%1$parameter";
		if ($para_weblog) { $filelink .= "&amp;w=".$Current_weblog; }
	}

	$temp_entry = "";

	$filelink = fixPath($filelink);
	$filelink = str_replace("%1", $code, $filelink);
	$filelink = format_date("", $filelink);

	if ($anchor != "") {
		$filelink .= "#".$anchor;
	}

	return $filelink;

}

function make_fileurl($code="", $weblog="", $anchor="comm", $parameter="") {

	$link = make_filelink($code, $weblog, $anchor, $parameter);

	return "http://".$_SERVER['HTTP_HOST'].$link;

}

// =============================================
// the functions below are used for outputting
// the weblog as RSS.
// =============================================

function rss_offset() {

	$z=date("Z");

	if (!is_numeric($z)) { $z = 0; }

	$offset = ( ($z>0) ? "+" : "-" ) . sprintf("%02d:%02d", floor(abs($z) / 3600), floor($z % 3600)/60);

	return $offset;

}

function feedtemplate($format, $whatpart) {
	global $feedtemplates, $Paths;

	if (!isset($feedtemplates[$format])) {
		$file = implode('', file( $Paths['pivot_path'].'templates/'.$format));
		list ($feedtemplates[$format]['head'], $feedtemplates[$format]['item'], $feedtemplates[$format]['footer']) =
				explode("------", $file);
	}

	return $feedtemplates[$format][$whatpart];


}

function start_rss() {
	global $rss, $rss_items, $atom, $atom_items, $db, $build, $Current_user, $Weblogs, $Current_weblog, $Paths, $Users, $Cfg;

	$rss_preamble=feedtemplate('feed_rss_template.xml','head');;
	$atom_preamble=feedtemplate('feed_atom_template.xml','head');


	if (strlen($Weblogs[$Current_weblog]['rss_link'])>2) {
		$link= trim($Weblogs[$Current_weblog]['rss_link']);
	} else {
		if ( gethost() != $Paths['host'] ) {
			// use the override value from weblog config..
			$link = gethost();
		} else {
			// determine the value ourselves..
			$link= gethost() . fixPath($Paths['pivot_url'] . $Weblogs[$Current_weblog]['front_path'] . $Weblogs[$Current_weblog]['front_filename']);
		}
	}

	if (strlen($Weblogs[$Current_weblog]['rss_img'])>2) {
		$image = trim($Weblogs[$Current_weblog]['rss_img']);
	} else {
		// if no image is set, we will also have to remove the <image> .. </image>
		// part from the feed.. Bit hackish, but it works.
		$image= "";
		$rss_preamble = preg_replace("/<image>(.*)<\/image>/msi", "", $rss_preamble);
	}


	$charset = snippet_charset();



	reset($Users);
	$user = each($Users);
	$user = $user['value'];


	$from = array(
		"%sitename%",
		"%title%",
		"%sitename_safe%",
		"%title_safe%",
		"%link%",
		"%description%",
		"%author%",
		"%admin-email%",
		"%admin-nick%",
		"%year%",
		"%date%",
		"%genagent%",
		"%version%",
		"%lang%",
		"%charset%",
		"%image%",
	);

	$to = array(
		$Cfg['sitename'],
		str_replace("&", "&amp;", $Weblogs[$Current_weblog]['name']),
		str_replace("_", "", safe_string($Cfg['sitename'], TRUE)),
		str_replace("_", "", safe_string($Weblogs[$Current_weblog]['name'], TRUE)),
		$link,
		$Weblogs[$Current_weblog]['payoff'],
		$Current_user,
		$user['email'],
		$user['nick'],
		date("Y"),
		date("Y-m-d\TH:i:s").rss_offset(),
		"http://www.pivotlog.net/?ver=".urlencode($build),
		$build,
		$Cfg['deflang'],
		$charset,
		$image,
	);


	$rss= str_replace($from, $to, $rss_preamble);
	$atom= str_replace($from, $to, $atom_preamble);

	$rss_items = array();
	$atom_items = array();


}

function add_rss($uid, $orgdate, $title, $introduction, $body, $user, $category) {
	global $db, $rss_items, $atom_items, $Cfg, $Weblogs, $Current_weblog, $Allow_RSS, $Paths;

	if (!$Allow_RSS) { return; }

	$link = make_fileurl($uid, "", "");

	if ($Weblogs[$Current_weblog]['siteurl'] == "") {
		$weblog = gethost() . fixPath($Paths['pivot_url'] . $Weblogs[$Current_weblog]['entry_path']);
	} else {
		$weblog = $Weblogs[$Current_weblog]['siteurl'];
	}

	$title = $db->entry['title'];

	// parse fields and remove scripting from the feed. Script in feed is bad..
	$introduction = parse_intro_or_body( $db->entry['introduction'] );
	$introduction = preg_replace('/(onclick="[^"]*")/Ui', "", $introduction);
	$introduction = preg_replace("/(onclick='[^']*')/Ui", "", $introduction);

	$body = parse_intro_or_body( $db->entry['body'] );
	$body = preg_replace('/(onclick="[^"]*")/Ui', "", $body);
	$body = preg_replace("/(onclick='[^']*')/Ui", "", $body);

	$tag = 	str_replace("_", "",  safe_string($Cfg['sitename'], TRUE)) . ",". date("Y") . ":" .
		str_replace("_", "",  safe_string($Weblogs[$Current_weblog]['name'], TRUE))."." . $uid;

	$lang = snippet_lang();

	$date = format_date( $orgdate, "%year%-%month%-%day%T%hour24%:%minute%:00").rss_offset();

	$description = htmlspecialchars(strip_tags($introduction));
	$description = str_replace("&nbsp;"," ", $description);

	// make sure description is not too long..
	if ( (isset($Weblogs[$Current_weblog]['rss_full'])) && ($Weblogs[$Current_weblog]['rss_full']==0) ) {
		// don't put anything in the content.
		$content="";
	} else {
		// put the introduction and body in the content..
		$content = str_replace("&nbsp;"," ", ($introduction.$body));
	}



	$rss_item=feedtemplate('feed_rss_template.xml','item');

	$atom_item=feedtemplate('feed_atom_template.xml','item');


	$from = array(
		"%title%",
		"%link%",
		"%description%",
		"%author%",
		"%guid%",
		"%date%",
		"%category%",
		"%content%",
		"%tag%",
		"%lang%",
		"%vialink%",
		"%viatitle%"
	);

	$to = array(
		htmlspecialchars(strip_tags($title)),
		$link,
		RelativeToAbsoluteURLS($description),
		$user,
		$uid."@".$weblog,
		$date,
		implode(", ",$category),
		RelativeToAbsoluteURLS($content),
		$tag,
		$lang,
		$db->entry['vialink'],
		$db->entry['viatitle'],
	);

	$rss_item= str_replace($from, $to, $rss_item);
	$atom_item= str_replace($from, $to, $atom_item);


	// We add the count($rss_items), because otherwise we can't have two items
	// that are posted at the same minute.
	$rss_items[$orgdate.".".count($rss_items)]=$rss_item;
	$atom_items[$orgdate.".".count($rss_items)]=$atom_item;


}

function finish_rss() {
	global $rss, $rss_items, $atom, $atom_items, $global_pref, $Weblogs, $Current_weblog, $VerboseGenerate;


	//write out the rss
	if($Weblogs[$Current_weblog]['rss_filename'] != "") {

		krsort($rss_items);

		foreach ($rss_items as $item) {
			$rss .= $item;
		}

		$rss.=feedtemplate('feed_rss_template.xml','footer');

		$filename = $Weblogs[$Current_weblog]['rss_path'] . $Weblogs[$Current_weblog]['rss_filename'];

		add_log("Write RSS: $filename");

		//make sure the directory exists
		makedir($Weblogs[$Current_weblog]['rss_path']);

		write_file($filename, $rss);

	}


	//write out the atom feed
	if($Weblogs[$Current_weblog]['atom_filename'] != "") {

		krsort($atom_items);


		foreach ($atom_items as $item) {
			$atom .= $item;
		}

		$atom.=feedtemplate('feed_atom_template.xml','footer');

		$filename = $Weblogs[$Current_weblog]['rss_path'] . $Weblogs[$Current_weblog]['atom_filename'];

		add_log("Write Atom: $filename");

		write_file($filename, $atom);


	}

}


?>
