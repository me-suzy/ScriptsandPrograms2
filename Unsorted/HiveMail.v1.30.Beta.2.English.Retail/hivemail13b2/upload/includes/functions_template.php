<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_template.php,v $ - $Revision: 1.51 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Returns either 'normal' or 'high', alternating
function classname($stay = false) {
	static $classcount = 1;

	if (!$stay) {
		$classcount++;
	}

	if ($classcount % 2 == 0) {
		return 'high';
	} else {
		return 'normal';
	}
}

// ############################################################################
// Takes $template and parses it using the other functions
function parse_template($template) {
	global $title, $fulltemplatedata;

	$fulltemplatedata = $template;
	$lex_queue = text_to_lexemes_queue($template);
	$root = array('data' => array());
	if (lex_queue_to_tree($root, $lex_queue, 0)) {
		cp_error("<b>Template parser error:</b><br /> &lt;%else%&gt; already defined or &lt;%elseif%&gt; without &lt;%if%&gt;\n");
	}
	$template = tree_to_tertiary_operator($root);

	$search = array(
		'#<%help {([^}]+)}\s*%>#ie', 
		'#\$skin\[([\[a-z0-9_\-]+)\]#is',
		'#\.php(\?)?#ies',
		'#{<INDEX_FILE>}(\?)?#ies',
		'#{<([a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)>}#i',
	);
	$replace = array(
		'addslashes("<a href=\"#\" onClick=\"alert(\'".str_replace("\'", "\\\'", htmlchars(trim(\'\1\')))."\'); return false;\"><img src=\"\\\$skin[images]/help.gif\" border=\"0\" /></a>")',
		'{$GLOBALS[skin][$1]}',
		'\'.php{$GLOBALS[session_url]}\'.((\'\1\' != \'\') ? (\'{$GLOBALS[session_ampersand]}\') : (\'\'))',
		'\'{<INDEX_FILE>}{$GLOBALS[session_url]}\'.((\'\1\' != \'\') ? (\'{$GLOBALS[session_ampersand]}\') : (\'\'))',
		'".(defined("$1") ? constant("$1") : "{<$1>}")."',
	);
	$template = preg_replace($search, $replace, $template);
	$search = array(
		'$css',
		'$header',
		'$footer',
		"\'"
	);
	$replace = array(
		'$GLOBALS[css]',
		'$GLOBALS[header]',
		'$GLOBALS[footer]',
		'\''
	);
	$template = str_replace($search, $replace, $template);

	return $template;
}

// ############################################################################
// Translates the special folder names as per the skin
function apply_skin_language($skinvars) {
	global $_folders;

	foreach ($_folders as $folderid => $folderinfo) {
		$_folders["$folderid"]['title'] = $skinvars["folder_$folderinfo[name]"];
	}
}

// ############################################################################
// Makes sure the variable array of a skin contains folder names
function verify_skin_language(&$skinvars) {
	$folders = array(
		'inbox' => 'Inbox',
		'sentitems' => 'Sent Items',
		'trashcan' => 'Trash Can',
		'junkmail' => 'Junk Mail',
	);
	foreach ($folders as $codename => $title) {
		if (empty($skinvars["folder_$codename"])) {
			$skinvars["folder_$codename"] = $title;
		}
	}
	$calvars = array(
		'sun_long' => 'Sunday',
		'mon_long' => 'Monday',
		'tue_long' => 'Tuesday',
		'wed_long' => 'Wednesday',
		'thu_long' => 'Thursday',
		'fri_long' => 'Friday',
		'sat_long' => 'Saturday',
		'sun_short' => 'S',
		'mon_short' => 'M',
		'tue_short' => 'T',
		'wed_short' => 'W',
		'thu_short' => 'T',
		'fri_short' => 'F',
		'sat_short' => 'S',
		'jan_long' => 'January',
		'feb_long' => 'February',
		'mar_long' => 'March',
		'apr_long' => 'April',
		'may_long' => 'May',
		'jun_long' => 'June',
		'jul_long' => 'July',
		'aug_long' => 'August',
		'sep_long' => 'September',
		'oct_long' => 'October',
		'nov_long' => 'November',
		'dec_long' => 'December',
		'jan_short' => 'Jan',
		'feb_short' => 'Feb',
		'mar_short' => 'Mar',
		'apr_short' => 'Apr',
		'may_short' => 'May',
		'jun_short' => 'Jun',
		'jul_short' => 'Jul',
		'aug_short' => 'Aug',
		'sep_short' => 'Sep',
		'oct_short' => 'Oct',
		'nov_short' => 'Nov',
		'dec_short' => 'Dec',
	);
	foreach ($calvars as $codename => $title) {
		if (empty($skinvars["cal_$codename"])) {
			$skinvars["cal_$codename"] = $title;
		}
	}
}

// ############################################################################
// Verifies and gets the skin information
function sort_skin() {
	global $skinid, $hiveuser;

	if (defined('SKIP_SKIN')) {
		return array();
	}

	$skin = array();
	if (!in_array(intme($skinid), explode(',', $hiveuser['allowedskins'])) or !($skin['info'] = getinfo('skin', $skinid, false, false))) {
		$skinid = getop('defaultskin');
		$skin['info'] = getinfo('skin', $skinid);
	}
	eval('$skin["vars"] = '.$skin['info']['vars'].';');
	verify_skin_language($skin['vars']);

	return array_merge($skin['info'], $skin['vars']);
}

// ############################################################################
// Creates the code that we need to eval() for templates
function makeeval($varname, $templatename = '', $add = false, $escape = true, $dieonecho = true, $system = false) {
	global $DB_site, $footer, $header, $css, $navigation, $youarehere, $youvegotmail, $appname, $defaultfolders, $customfolders, $hiveuser, $skin, $headimgs, $domainname_options, $runpop, $runuserpop, $_folders;

	if (empty($templatename)) {
		$templatename = $varname;
	}
	$template = gettemplate($templatename, $escape, $system);

	if ($varname == 'echo') {
		// Something for POP3 checking
		if (is_array($runuserpop)) {
			$runuserpop = implode(',', $runuserpop);
		} else {
			$runuserpop = '';
		}

		if (defined('LOAD_MINI_TEMPLATES')) {
			eval(makeeval('header', 'header_mini'));
			eval(makeeval('footer', 'footer_mini'));
		} else {
			eval(makeeval('header'));
			eval(makeeval('footer'));
		}
		eval(makeeval('css'));

		$totaltime = round(microdiff(STARTTIME), iif(SHOWSQL, 15, 10));
		$sqltime = round($DB_site->sqltime, iif(SHOWSQL, 15, 9));
		$conntime = round($DB_site->conntime, iif(SHOWSQL, 15, 9));
		$phptime = round($totaltime - $conntime - $sqltime, iif(SHOWSQL, 15, 9));
		$sqlpercent = round($sqltime / $totaltime * 100, 2);
		$connpercent = round($conntime / $totaltime * 100, 2);
		$phppercent = 100 - iif(SHOWSQL, $connpercent, 0) - $sqlpercent;
		$footer = str_replace(array('{queries}', '{sqlstats}'), array($DB_site->queries, 'Page was generated in '.$totaltime.' seconds with '.$DB_site->queries.' ('.$DB_site->slowcount.'/'.$DB_site->fullscans.') queries (<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].iif(strpos($_SERVER['REQUEST_URI'], '?') !== false, '&', '?').'showsql=1" style="color: #FFFFFF;">details</a>)<br />(SQL stuff: '.$sqltime.' - '.$sqlpercent.'% and PHP stuff: '.$phptime.' - '.$phppercent.'%)'), $footer);
		if (!SHOWSQL) {
			return 'echo gzipdata(preg_replace(\'#{(new)?classname}#e\', \'classname("\1" != "new")\', str_replace(\'<body\', \'<body '.str_replace('\'', '\\\'', $skin['body']).'\', '.$template.')), getop(\'gzip_level\')); flush(); '.iif($dieonecho, ' exit;');
		} else {
			return 'echo "<blockquote><pre><span style=\"font-family: Tahoma, sans-serif; font-size: 11px;\"><b>Page generated in '.$totaltime.' seconds with '.$DB_site->queries.' queries ('.$DB_site->slowcount.'/'.$DB_site->fullscans.'): </b> (<a href=\"http://'.$_SERVER['HTTP_HOST'].str_replace('showsql=1', '', $_SERVER['REQUEST_URI']).'\">no details</a>)\n<b>Connect:\t'.$conntime.'\t('.$connpercent.'%)\nSQL stuff:\t'.$sqltime.'\t('.$sqlpercent.'%)\nPHP stuff:\t'.$phptime.'\t('.$phppercent.'%)</b></span></pre></blockquote>"; exit;';
		}
	} else {
		return '$'.$varname.' '.iif($add, '.').'= '.$template.';';
	}
}

// ############################################################################
// Creates the code that we need to eval() for templates
// Always returns the default-set template
function makeevalsystem($varname, $templatename = '') {
	return makeeval($varname, $templatename, false, true, true, true);
}

// ############################################################################
// Creates a redirection screen
function makeredirect($templatename, $newurl, $canskip = true) {
	global $footer, $header, $css, $youvegotmail, $hiveuser, $skin, $appname, $domainname_options;

	// Add session information
	$GLOBALS['session_url'] = '?'.session_name().'='.session_id();
	$GLOBALS['session_ampersand'] = '&';
	$newurl = preg_replace('#\.php(\?)?#ies', '\'.php'.$GLOBALS['session_url'].'\'.((\'\1\' != \'\') ? (\''.$GLOBALS['session_ampersand'].'\') : (\'\'))', $newurl);

	if (!getop('useredirect') and !headers_sent() and $canskip) {
		header("Location: $newurl");
		exit;
	}

	$hiveuser['userid'] = 0;
	$hiveuser['showfoldertab'] = false;
	if (defined('LOAD_MINI_TEMPLATES')) {
		eval(makeeval('header', 'header_mini'));
		eval(makeeval('footer', 'footer_mini'));
	} else {
		eval(makeeval('header'));
		eval(makeeval('footer'));
	}
	eval(makeeval('css'));

	$redirect = str_replace(array('<body', '$newurl'), array('<body '.str_replace('"', '\"', $skin['body']), $newurl), gettemplate('redirect'));
	return "global \$appname;\n".makeeval('message', $templatename)."\necho gzipdata($redirect, getop('gzip_level')); flush(); exit;";
}

// ############################################################################
// Creates an error screen
function makeerror($templatename, $extra = '', $iserror = true) {
	global $footer, $header, $css, $youvegotmail, $hiveuser, $appname, $domainname_options;
	
	$hiveuser['userid'] = 0;
	$hiveuser['showfoldertab'] = false;
	if (defined('LOAD_MINI_TEMPLATES')) {
		eval(makeeval('header', 'header_mini'));
		eval(makeeval('footer', 'footer_mini'));
	} else {
		eval(makeeval('header'));
		eval(makeeval('footer'));
	}
	eval(makeeval('css'));

	return '$iserror = '.intval($iserror).";\n".makeeval('message', $templatename)."\n".iif(!empty($extra), "\$message .= \"$extra\";\n")."global \$appname;\n".makeeval('echo', 'error');
}	

// ############################################################################
// Gets a template from the database or cache
function gettemplate($templatename, $escape = true, $system = false) {
	global $templatecache, $DB_site, $skin, $uncachedtemplates;

	if (!isset($templatecache["$templatename"])) {
		if (defined('HIVE_DEV') and HIVE_DEV == true) {
			$template['parsed_data'] = @implode('', file('.'.iif(INADMIN, '.')."/templates/parsed/$templatename.html"));
		} else {
			if (!$system) {
				$template = $DB_site->query_first("
					SELECT parsed_data
					FROM hive_template
					WHERE title = '".addslashes($templatename)."'
					AND templatesetid IN (-1, '$skin[templatesetid]')
					ORDER BY templatesetid DESC
				");
			} else {
				if (!defined('DEFAULT_TEMPLATESET')) {
					$defskinid = getop('defaultskin');
					$defskin = getinfo('skin', $defskinid);
					define('DEFAULT_TEMPLATESET', intval($defskin['templatesetid']));
				}
				$template = $DB_site->query_first('
					SELECT parsed_data
					FROM hive_template
					WHERE title = "'.addslashes($templatename).'"
					AND templatesetid IN (-1, '.DEFAULT_TEMPLATESET.')
					ORDER BY templatesetid DESC
				');
			}
		}
		if (!isset($template['parsed_data'])) {
			log_event(EVENT_CRITICAL, 301, array('templatename' => $templatename));
			$template['parsed_data'] = '""'; // Prevent parse errors
		} elseif (defined('HIVE_DEV') and HIVE_DEV == true) {
			// May generates a lot of events but important for us
			//echo $templatename;
			log_event(EVENT_NOTICE, 302, array('templatename' => $templatename, 'script' => basename($_SERVER['PHP_SELF'])));
		}
		$uncachedtemplates[] = $templatename;
		$templatecache["$templatename"] = $template['parsed_data'];
	}

	return $templatecache["$templatename"];
}

// ############################################################################
// Caches all templates we'll be using for the page
function cachetemplates($templatelist = '') {
	global $templatecache, $DB_site, $skin;

	if (defined('SKIP_SKIN')) {
		return;
	}

	$templatelist .= ',login,css,header,footer,error,redirect,navigation,pagenav_lastlink,pagenav_prevlink,pagenav_nextlink,pagenav_pagelink,pagenav_curpage,pagenav,header_minifolderbit,header_minifolderbit_current,header_mini,footer_mini,error_invalidid';
	$templatelist = trim($templatelist, ',');

	if (defined('HIVE_DEV') and HIVE_DEV == true) {
		$templates = explode(',', $templatelist);
		foreach ($templates as $template) {
			$templatecache["$template"] = @implode('', file(iif(INADMIN, '.')."./templates/parsed/$template.html"));
		}
	} else {
		$templates = $DB_site->query("
			SELECT parsed_data, title
			FROM hive_template
			WHERE title IN ('".str_replace(',', '\', \'', $templatelist)."')
			AND templatesetid IN (-1, $skin[templatesetid])
			ORDER BY templatesetid
		");
		for ($templatecache = array(); $template = $DB_site->fetch_array($templates); $templatecache["$template[title]"] = $template['parsed_data']);
	}
}

##########################################################
#
#		function format_text_item: return formatted string 
#		without leading spaces and with slashes
#		for example: $in = " test''test  "
#		function return: "test\'\'test"
#
function format_text_item($in) {
//	$in = trim($in);
	
	$in = AddSlashes($in);
	
	// changed
	return "\"$in\"";
	#return "'$in'";
}

##########################################################
#
#		recursive function tree_to_tertiary_operator: return 
#		tertiary operator string, which was built from tree
#
function tree_to_tertiary_operator(& $node) {
	$out = '';
	
	$dataref = $node['data'];
	$node_items = array();

	for ($i = 0; $i < count($node['data']); $i++) {
		$data_item = & $node['data'][$i];

		if (is_array($data_item)) {
		 	$if_block = '(('.$data_item['if']['conditional'].') ? (';
			$if_data = tree_to_tertiary_operator($data_item['if']);
			$if_block .= $if_data.') : ';
			
			if (!is_array($data_item['elseif']) && !is_array($data_item['else'])) {
				$if_block .= "('')";
			} elseif (!is_array($data_item['elseif']) && is_array($data_item['else'])) {
				$else_block = tree_to_tertiary_operator($data_item['else']);
				$if_block .= "($else_block)";
			} else {
				$elif_block = elsif_array_to_tertiary_operator($data_item['elseif'],
					$data_item['else']);
				$if_block .= "($elif_block)";
			}
			
			array_push ($node_items, $if_block.')');
		} else {
			array_push ($node_items, format_text_item($data_item));
		}
	}	
	$out .= join('.', $node_items);
	// changed
	if (empty($out)) {
		$out = '""';
	}
	
	return $out;
}

#################################################################
#
#		function elsif_array_to_tertiary_operator: return
#		tertiary operator string, which was built from tree for 
#		elseif operator
#
#

function elsif_array_to_tertiary_operator (& $elsif_array, & $else_hash) {
	$out = '(';
	$elsif_hash = array_shift($elsif_array);
	
	$out .= "(".$elsif_hash['conditional'].") ? (";
	$out .= tree_to_tertiary_operator($elsif_hash);
	$out .= ") : ";
	
	if (count($elsif_array) > 0) {
		$out .= elsif_array_to_tertiary_operator($elsif_array, $else_hash);
	} elseif (is_array($else_hash)) {
		$out .= "(";
		$out .= tree_to_tertiary_operator($else_hash);
		$out .= ")";
	} else {
		$out .= "\"\"";
	}
	
	$out .= ")";
	
	return $out;
}

##############################################################
#
#		recursive function lex_queue_to_tree: return
#		tree. This function check also syntax for all lexemes
#
#

function lex_queue_to_tree(& $root_node,& $lex_queue, $level_deep) {
	global $fulltemplatedata, $title;
	$ignore_level_up = 0;

	do {
		$next_item = array_shift($lex_queue);

		if ($next_item['type'] == 'text') {
			array_push ($root_node['data'], $next_item['value']);
		} elseif ($next_item['type'] == 'if') {
			$conditional = array_shift($lex_queue);
			if(!($conditional['type'] == 'conditional' && $conditional['value'] != "")) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%if%&gt; requires conditional statement", $fulltemplatedata);
			}
		
			$new_check_node['if']['parent'] = & $new_check_node;
			array_push ($root_node['data'], array(
				'if' => array(
					'conditional' => $conditional['value'],
				 	'data' => array()
				)
			));

			$ignore_level_up = 
			lex_queue_to_tree($root_node['data'][count($root_node['data']) - 1]['if'],
				 $lex_queue, $level_deep + 1);
		} elseif ($next_item['type'] == 'elseif') {
			if ($ignore_level_up > 0) 
				$ignore_level_up = 0;
			else {
				array_unshift($lex_queue, $next_item);
				return 1;
			}
		
			$conditional = array_shift($lex_queue);
			if(!($conditional['type'] == 'conditional' && $conditional['value']!="")) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%elseif%&gt; requires conditional statement", $fulltemplatedata);
			}
		
			if(!(is_array($root_node['data'][count($root_node['data']) - 1]))) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%elseif%&gt; requires preceding &lt;%if%&gt;", $fulltemplatedata);
			}	

			if (!is_array($root_node['data'][count($root_node['data']) - 1]['elseif'])) {
				$root_node['data'][count($root_node['data']) - 1]['elseif'] = array();
			}
			
			array_push($root_node['data'][count($root_node['data']) - 1]['elseif'], array(
				'conditional' => $conditional['value'],
				'data' => array()
			));
		
			$ignore_level_up = 
			lex_queue_to_tree($root_node['data'][count($root_node['data']) - 1]['elseif']
				[count($root_node['data'][count($root_node['data']) - 1]['elseif']) - 1],
				 $lex_queue, $level_deep + 1);
		} elseif ($next_item['type'] == 'else') {
			if ($ignore_level_up > 0) 
				$ignore_level_up = 0;
			else {
				array_unshift($lex_queue, $next_item);
				return 1;
			}

			if(!(is_array($root_node['data'][count($root_node['data']) - 1]))) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%else%&gt; requires preceding &lt;%if%&gt;", $fulltemplatedata);
			}
		
			if (defined($root_node['data'][count($root_node['data']) - 1]['else'])) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%else%&gt; already defined for &lt;%if%&gt;", $fulltemplatedata);
			}
			
			$root_node['data'][count($root_node['data']) - 1]['else'] 
				= array( 'data' => array() );
		
			lex_queue_to_tree($root_node['data'][count($root_node['data']) - 1]['else'],
				 $lex_queue, $level_deep + 1);
		} elseif ($next_item['type'] == 'endif') {
			if(!$level_deep) {
				cp_template_error("<b>Template parser error:</b><br /> &lt;%endif%&gt; requires preceding &lt;%if%&gt;", $fulltemplatedata);
			}

			return 0;
		} elseif ($next_item['type'] == 'conditional') {
			cp_error("<b>Template parser error:</b><br /> Unexpected conditional statement, could be a bug in code parser.\n");
		}
	} while (count($lex_queue) > 0);
	
	if ($level_deep) {
		cp_template_error("<b>Template parser error:</b><br /> No enough &lt;%endif%&gt;'s\n", $fulltemplatedata);
	}
	
	return 0;
}
#######################################################################
#
#		function text_to_lexemes_queue: return hash, which contain 
#		queue with lexemes from template
#
#

function text_to_lexemes_queue($text) {
	#list ($parse_conditional, $consume_chars) = (0, 0);
	$parse_conditional = 0;
	$consume_chars = 0;
	$out = array();
	do {
		$parse_conditional = 0;
		if (preg_match("/^(<\%elseif\s+)/s", $text, $matches)) {
			$consume_chars = strlen($matches[1]);
			array_push($out, array( 'type' => 'elseif' ));
			$parse_conditional = 1;
		} elseif (preg_match("/^(<\%else\%>)/s",$text,$matches)) {
			$consume_chars = strlen($matches[1]);
			array_push($out, array( 'type' => 'else' ));
		} elseif (preg_match("/^(<\%endif\%>)/s",$text, $matches)) {
			$consume_chars = strlen($matches[1]);
			array_push($out, array( 'type' => 'endif' ));
		} elseif (preg_match("/^(<\%if\s+)/s", $text,$matches)) {
			$consume_chars = strlen($matches[1]);
			array_push($out, array( 'type' => 'if' ));
			$parse_conditional = 1;
		} elseif (preg_match("/^(.)/s", $text, $matches)) {
			$consume_chars = 1;
			$textchar = $matches[1];
			
            if ($out[count($out)-1]['type'] == 'text')
            {
                $out[count($out)-1]['value'] .= $textchar;
            } else {
				array_push($out, array('type' => 'text', 'value' => $textchar ));
			}
		}

		$text = substr($text, $consume_chars);
		if ($parse_conditional) {
			array_push ($out, array('type' => 'conditional', 'value' => read_conditional($text)));
		}
	} while (strlen ($text));
	
	return $out;
}
################################################################################
#
#		function read_conditional: become reference to conditional in
#		if or elseif statements.
#
#
function read_conditional(& $textref) {
	global $title, $fulltemplatedata;
	$out='';
	$consume_chars=0;
	$exit=0;
	$parse_quoted=0;
	$quote_char=0;

	do {
		$parse_quoted = 0;
		
		if ((preg_match("/^(\')/", $textref,$matches)) || (preg_match("/^(\")/",$textref,$matches))) {
			$quote_char = $matches[1];
			$out .= $quote_char;
			$consume_chars = 1;
			$parse_quoted = 1;
		} elseif (preg_match("/^(\%>)/",$textref)) {
			$consume_chars = 2;
			$exit = 1;
		} elseif (preg_match("/^(.)/s", $textref, $matches)) {
			$out .= $matches[1];
			$consume_chars = 1;
		} elseif (!strlen($textref)) {
			cp_template_error("<b>Template parser error:</b><br /> Unexpected end of text while reading PHP conditional statement", $fulltemplatedata);
		}
		
		$textref = substr($textref, $consume_chars);
		if ($parse_quoted) {
			$out .= read_quoted($textref, $quote_char);
		}
	} while (! $exit);
	
	# (PHP syntax) check out with eval
	return $out;
}
####################################################################
#
#		function read_quoted: function for testing quoted text
#
#
function read_quoted (& $textref, $quote_char) {
	global $title, $fulltemplatedata;
	$regexp_quote = AddSlashes($quote_char);
	$out = '';
	$consume_chars = 0;
	$exit_quote = 0;
	do {
		if ((preg_match("/^\\$regexp_quote/",$textref)) || (preg_match("/^\\\\/", $textref))) {
			$consume_chars = 2;
		} elseif (preg_match("/^$regexp_quote/", $textref)) {
			$consume_chars = 1;
			$exit_quote = 1;
		} elseif (preg_match("/^(.)/s", $textref)) {
			$consume_chars = 1;
		} elseif (!strlen($textref)) {
			cp_template_error("<b>Template parser error:</b><br /> Unexpected end of text while reading quoted string", $fulltemplatedata);
		}
			
		$out .= substr($textref, 0, $consume_chars);
		$textref = substr($textref, $consume_chars);
	} while (! $exit_quote);		
	
	return $out;
}

?>