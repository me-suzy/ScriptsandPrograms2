<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: template_functions.php,v $
// | $Date: 2002/11/09 10:17:59 $
// | $Revision: 1.26 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Takes $template and parses it using the other functions
function parse_template($template) {
	$lex_queue = text_to_lexemes_queue($template);
	$root = array('data' => array());
	if (lex_queue_to_tree($root, $lex_queue, 0)) {
		cp_error("<b>Template parser error:</b><br /> &lt;%else%&gt; already defined or &lt;%elseif%&gt; without &lt;%if%&gt;\n");
	}
	$template = tree_to_tertiary_operator($root);

	// Easier than making sure $skin is available inside functions... ;)
	$template = preg_replace('#\$skin\[([\[a-z0-9_\-]+)\]#is', '{$GLOBALS[skin][$1]}', $template);
	$template = preg_replace('#\.php(\?)?#ies', '\'.php{$GLOBALS[session_url]}\'.((\'\1\' != \'\') ? (\'{$GLOBALS[session_ampersand]}\') : (\'\'))', $template);
	$template = str_replace(array('$css', '$header', '$footer'), array('$GLOBALS[css]', '$GLOBALS[header]', '$GLOBALS[footer]'), $template);

	return str_replace("\'", '\'', $template);
}

// ############################################################################
// Verifies and gets the skin information
function sort_skin() {
	global $skinid, $hiveuser;

	$skin = array();
	$skin['info'] = getinfo('skin', $skinid, false, false);
	if (!$skin['info'] or !in_array($skinid, explode(',', $hiveuser['allowedskins']))) {
		$skinid = getop('defaultskin');
		$skin['info'] = getinfo('skin', $skinid);
	}
	eval('$skin["vars"] = '.$skin['info']['vars'].';');

	return array_merge($skin['info'], $skin['vars']);
}

// ############################################################################
// Creates the code that we need to eval() for templates
function makeeval($varname, $templatename = '', $add = false, $escape = true, $dieonecho = true) {
	global $DB_site, $footer, $header, $css, $navigation, $youarehere, $youvegotmail, $appname, $defaultfolders, $customfolders, $hiveuser, $skin, $headimgs, $php_hours, $php_minutes, $php_time;

	if (empty($templatename)) {
		$templatename = $varname;
	}
	$template = gettemplate($templatename, $escape);

	if ($varname == 'echo') {
		if (defined('LOAD_MINI_TEMPLATES')) {
			eval(makeeval('header', 'header_mini'));
			eval(makeeval('footer', 'footer_mini'));
		} else {
			eval(makeeval('header'));
			eval(makeeval('footer'));
		}
		eval(makeeval('css'));

		$footer = str_replace('{queries}', $DB_site->queries, $footer);
		return 'echo str_replace(\'<body\', \'<body '.str_replace('\'', '\\\'', $skin['body']).'\','.$template.'); '.iif($dieonecho, ' exit;');
	} else {
		return '$'.$varname.' '.iif($add, '.').'= '.$template.';';
	}
}

// ############################################################################
// Creates a redirection screen
function makeredirect($templatename, $newurl) {
	global $footer, $header, $css, $youvegotmail, $hiveuser, $skin, $appname, $php_hours, $php_minutes, $php_time;
	
	$hiveuser['userid'] = 0;
	$hiveuser['showfoldertab'] = false;
	eval(makeeval('header'));
	eval(makeeval('footer'));
	eval(makeeval('css'));

	$redirect = str_replace(array('<body', '$newurl'), array('<body '.str_replace('"', '\"', $skin['body']), $newurl), gettemplate('redirect'));
	return "global \$appname;\n".makeeval('message', $templatename)."\necho $redirect; exit;";
}

// ############################################################################
// Creates an error screen
function makeerror($templatename, $extra = '') {
	global $footer, $header, $css, $youvegotmail, $hiveuser, $appname, $php_hours, $php_minutes, $php_time;
	
	$hiveuser['userid'] = 0;
	$hiveuser['showfoldertab'] = false;
	eval(makeeval('header'));
	eval(makeeval('footer'));
	eval(makeeval('css'));

	return makeeval('message', $templatename)."\n".iif(!empty($extra), "\$message .= \"$extra\";\n")."global \$appname;\n".makeeval('echo', 'error');
}	

// ############################################################################
// Gets a template from the database or cache
function gettemplate($templatename, $escape = true) {
	global $templatecache, $DB_site, $skin;

	if (!isset($templatecache["$templatename"])) {
		$template = $DB_site->query_first("
			SELECT parsed_data
			FROM template
			WHERE title = '".addslashes($templatename)."'
			AND templatesetid IN (-1, '$skin[templatesetid]')
			ORDER BY templatesetid DESC
		");
	//	echo ','.$templatename;
		$templatecache["$templatename"] = $template['parsed_data'];
	}

	return $templatecache["$templatename"];
}

// ############################################################################
// Caches all templates we'll be using for the page
function cachetemplates($templatelist) {
	global $templatecache, $DB_site, $skin;

	$templatelist .= ',css,header,footer,error,redirect,navigation,pagenav_prevlink,pagenav_nextlink,pagenav_pagelink,pagenav_curpage,pagenav';
	$templates = $DB_site->query("
		SELECT parsed_data, title
		FROM template
		WHERE title IN ('".str_replace(',', '\', \'', $templatelist)."')
		AND templatesetid IN (-1, $skin[templatesetid])
		ORDER BY templatesetid
	");
	for ($templatecache = array(); $template = $DB_site->fetch_array($templates); $templatecache["$template[title]"] = $template['parsed_data']);
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
				$if_block .= "''";
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
	$ignore_level_up = 0;

	do {
		$next_item = array_shift($lex_queue);

		if ($next_item['type'] == 'text') {
			array_push ($root_node['data'], $next_item['value']);
		} elseif ($next_item['type'] == 'if') {
			$conditional = array_shift($lex_queue);
			if(!($conditional['type'] == 'conditional' && $conditional['value'] != "")) {
				cp_error("<b>Template parser error:</b><br /> &lt;%if%&gt; requires conditional statement");
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
				cp_error("<b>Template parser error:</b><br /> &lt;%elseif%&gt; requires conditional statement");
			}
		
			if(!(is_array($root_node['data'][count($root_node['data']) - 1]))) {
				cp_error("<b>Template parser error:</b><br /> &lt;%elseif%&gt; requires preceding &lt;%if%&gt;");
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
				cp_error("<b>Template parser error:</b><br /> &lt;%else%&gt; requires preceding &lt;%if%&gt;");
			}
		
			if (defined($root_node['data'][count($root_node['data']) - 1]['else'])) {
				cp_error("<b>Template parser error:</b><br /> &lt;%else%&gt; already defined for &lt;%if%&gt;");
			}
			
			$root_node['data'][count($root_node['data']) - 1]['else'] 
				= array( 'data' => array() );
		
			lex_queue_to_tree($root_node['data'][count($root_node['data']) - 1]['else'],
				 $lex_queue, $level_deep + 1);
		} elseif ($next_item['type'] == 'endif') {
			if(!$level_deep) {
				cp_error("<b>Template parser error:</b><br /> &lt;%endif%&gt; requires preceding &lt;%if%&gt;");
			}

			return 0;
		} elseif ($next_item['type'] == 'conditional') {
			cp_error("<b>Template parser error:</b><br /> Unexpected conditional statement, could be a bug in code parser.\n");
		}
	} while (count($lex_queue) > 0);
	
	if ($level_deep) {
		cp_error("<b>Template parser error:</b><br /> No enough &lt;%endif%&gt;'s\n");
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
			array_push ($out, array('type' => 'conditional', 'value' => read_conditional(& $text)));
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
			cp_error("<b>Template parser error:</b><br /> Unexpected end of text while reading PHP conditional statement");
		}
		
		$textref = substr($textref, $consume_chars);
		if ($parse_quoted) {
			$out .= read_quoted(& $textref, $quote_char);
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
			cp_error("<b>Template parser error:</b><br /> Unexpected end of text while reading quoted string");
		}
			
		$out .= substr($textref, 0, $consume_chars);
		$textref = substr($textref, $consume_chars);
	} while (! $exit_quote);		
	
	return $out;
}

?>