<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ##################### //XML FUNCTIONS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

$style = Array();
$templates = Array();
$templategroups = Array();
$where = "nowhere";
$title = "";

function buildTemplateArr4($styleid) {
	// get all customized templates
	$cus = query("SELECT * FROM templates WHERE styleid = '".$styleid."' AND is_global = 0 ORDER BY title ASC");
	
	// put custom into array...
	if(mysql_num_rows($cus)) {
		while($custom = mysql_fetch_array($cus)) {
			$templateinfo[$custom['defaultid']] = $custom;
		}
	}

	// returns all templates for current style...
	return $templateinfo;
}

function xml_start_handler($parser,$name,$atts) {
	global $style, $templates, $templategroups, $color, $where, $title;

	if($name == "style") {
		$style['title'] = $atts['title'];
		$style['display_order'] = $atts['display_order'];
		$style['user_selection'] = $atts['user_selection'];
	}

	else if($name == "group") {
		$templategroups[$atts['templategroupid']] = Array(
			"templategroupid" => $atts['templategroupid'],
			"title" => $atts['title']
			);
	}

	else if($name == "template") {
		$where = "template";
		$title = $atts['title'];

		$templates[$title] = Array(
			"templateid" => $atts['templateid'],
			"type" => $atts['type'],
			"templategroupid" => $atts['templategroupid'],
			"title" => $atts['title'],
			"defaultid" => $atts['defaultid'],
			"styleid" => $atts['styleid'],
			"last_edit" => $atts['last_edit'],
			"username" => $atts['username'],
			"version" => $atts['version'],
			"is_global" => $atts['is_global'],
			"is_custom" => $atts['is_custom']
			);
	}

	else if($name == "colors") {
		foreach($atts as $fieldName => $value) {
			$color[$fieldName] = $value;
		}
	}
}

function xml_end_handler($parser,$name) {
	global $where;

	if($name == "template") {
		$where = "nowhere";
	}
}

function xml_character_handler($parser,$txt) { 
	global $title, $templates, $where;

	if($where == "template") {
		$templates[$title]['template'] .= $txt;
	}
}

function xml_format_error($parser) {
	$code = xml_get_error_code($parser);
	$str = xml_error_string($code);
	$line = xml_get_current_line_number($parser);

	return "<strong>XML Error:</strong> ".$str." at line ".$line;
}


// xml importing functions
function xml_import($contents,$install=false,$upgrade=false) {
	global $style, $templategroups, $templates, $color;

	$parser = xml_parser_create();
	xml_set_element_handler($parser,"xml_start_handler","xml_end_handler");
	xml_set_character_data_handler($parser,"xml_character_handler");
	xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
	xml_parse($parser,$contents) OR die(xml_format_error($parser));
	xml_parser_free($parser);

	// overwrite existing style?
	if(!$upgrade) {
		if($install) {
			query("INSERT INTO styles (title,user_selection,display_order,enabled) VALUES ('".$style['title']."','".$style['user_selection']."','".$style['display_order']."',1)");

			$GET_styleid = mysql_insert_id();
		} else if($_POST['import']['styleid'] == -1) {
			// add new style
			query("INSERT INTO styles (title,user_selection,display_order,enabled) VALUES ('".addslashes(htmlspecialchars($_POST['import']['title']))."','".$_POST['import']['user_selection']."','".addslashes(htmlspecialchars($_POST['import']['display_order']))."',1)");

			$GET_styleid = mysql_insert_id();
		} else {
			$GET_styleid = $_POST['import']['styleid'];
		}
	}

	if(is_array($templategroups)) {
		foreach($templategroups as $groupid => $groupinfo) {
			if($groupid < 1) continue;
			// replace into
			query("REPLACE INTO templategroups (templategroupid,title) VALUES ('".$groupinfo['templategroupid']."','".addslashes($groupinfo['title'])."')");
		}
	}

	// if upgrade, drop all templates and colors
	if($upgrade) {
		query("TRUNCATE TABLE templates_default");
		query("TRUNCATE TABLE styles_colors_default");
	}

	if(is_array($templates)) {
		$cusTemplateinfo = buildTemplateArr4($GET_styleid);

		foreach($templates as $title => $templateinfo) {
			// get the template
			$theTemplate = addslashes($templateinfo['template']);
			$phpTemplate = parseConditionals($templateinfo['template']);

			// if it exists, take it's template id and use replace into for one query
			if(!$install) {
				if(is_array($cusTemplateinfo[$templateinfo['defaultid']])) {
					query("REPLACE INTO templates (templateid,type,templategroupid,title,defaultid,styleid,last_edit,username,version,is_global,is_custom,template,template_php) VALUES ('".$cusTemplateinfo[$templateinfo['defaultid']]['templateid']."','".$templateinfo['type']."','".$templateinfo['templategroupid']."','".addslashes($templateinfo['title'])."','".$templateinfo['defaultid']."','".$GET_styleid."','".$templateinfo['last_edit']."','".$templateinfo['username']."','".$templateinfo['version']."','".$templateinfo['is_global']."','".$templateinfo['is_custom']."','".$theTemplate."','".$phpTemplate."')");
				} else {
					query("INSERT INTO templates (type,templategroupid,title,defaultid,styleid,last_edit,username,version,is_global,is_custom,template,template_php) VALUES ('".$templateinfo['type']."','".$templateinfo['templategroupid']."','".addslashes($templateinfo['title'])."','".$templateinfo['defaultid']."','".$GET_styleid."','".$templateinfo['last_edit']."','".$templateinfo['username']."','".$templateinfo['version']."','".$templateinfo['is_global']."','".$templateinfo['is_custom']."','".$theTemplate."','".$phpTemplate."')");
				}
			} else {
				if(!$upgrade) {
					query("INSERT INTO templates_default (defaultid,type,templategroupid,title,template,version,template_php) VALUES ('".$templateinfo['defaultid']."','".$templateinfo['type']."','".$templateinfo['templategroupid']."','".addslashes($templateinfo['title'])."','".$theTemplate."','".$templateinfo['version']."','".$phpTemplate."')");
				} else {
					query("REPLACE INTO templates_default (defaultid,type,templategroupid,title,template,version,template_php) VALUES ('".$templateinfo['defaultid']."','".$templateinfo['type']."','".$templateinfo['templategroupid']."','".addslashes($templateinfo['title'])."','".$theTemplate."','".$templateinfo['version']."','".$phpTemplate."')");
				}
			}
		}
	}

	// do colors?
	if(is_array($color) AND ($_POST['import']['colors'] OR $install)) {
		// overwrite, or insert?
		if($install) {
			// delete
			query("DELETE FROM styles_colors_default");

			// form query
			$fields = "";
			$values = "";

			foreach($color as $fieldName => $value) {
				$fields .= ",".$fieldName;
			}

			foreach($color as $fieldName2 => $value2) {
				$values .= ",'".$value2."'";
			}

			// chop off leading commas
			$fields = preg_replace("|^,|","",$fields);
			$values = preg_replace("|^,|","",$values);

			// run query
			query("INSERT INTO styles_colors_default (".$fields.") VALUES (".$values.")");
		}

		else if($_POST['import']['styleid'] == -1) {
			// form query
			$fields = "";
			$values = "";

			foreach($color as $fieldName => $value) {
				$fields .= ",".$fieldName;
			}

			foreach($color as $fieldName2 => $value2) {
				$values .= ",'".$value2."'";
			}

			// chop off leading commas
			$fields = preg_replace("|^,|","",$fields);
			$values = preg_replace("|^,|","",$values);

			// run query
			query("INSERT INTO styles_colors (".$fields.",styleid) VALUES (".$values.",'".$GET_styleid."')");
		}

		else {
			// delete
			query("DELETE FROM styles_colors WHERE styleid = '".$GET_styleid."'");

			// form query
			$fields = "";
			$values = "";

			foreach($color as $fieldName => $value) {
				$fields .= ",".$fieldName;
			}

			foreach($color as $fieldName2 => $value2) {
				$values .= ",'".$value2."'";
			}

			// chop off leading commas
			$fields = preg_replace("|^,|","",$fields);
			$values = preg_replace("|^,|","",$values);

			// run query
			query("INSERT INTO styles_colors (".$fields.",styleid) VALUES (".$values.",'".$GET_styleid."')");
		}
	}
}
	
?>