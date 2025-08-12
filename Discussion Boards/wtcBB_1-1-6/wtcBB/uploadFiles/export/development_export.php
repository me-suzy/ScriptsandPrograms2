<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ######## //DEVELOPMENT - EXPORT DEFAULT TEMPLATES\\ ####### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include files
include("./../includes/config.php");
include("./../includes/functions.php");

function buildTemplateArr3($styleid) {
	// get all customized templates
	$cus = query("SELECT * FROM templates_default ORDER BY title ASC");
	
	// put custom into array...
	if(mysql_num_rows($cus) > 0) {
		while($custom = mysql_fetch_array($cus)) {
			$templateinfo[$custom['templategroupid']][$custom['title']] = $custom;
		}
	}

	// returns all templates for current style...
	return $templateinfo;
}

$styleinfo = query("SELECT * FROM styles WHERE styleid = 1 LIMIT 1",1);
$color_q = query("SELECT * FROM styles_colors_default LIMIT 1");
$theGroups = query("SELECT * FROM templategroups ORDER BY title");

$styleinfo['title'] = trim($styleinfo['title']);
$title_noSpaces = preg_replace("|\s|","",$styleinfo['title']);

$templateinfo2 = buildTemplateArr3(1);

$handle = fopen("install_style.xml","wb");
fwrite($handle,"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n");
fwrite($handle,"<style title=\"".$styleinfo['title']."\" display_order=\"".$styleinfo['display_order']."\" user_selection=\"".$styleinfo['user_selection']."\">\n");

// loop through templates
while($groupinfo = mysql_fetch_array($theGroups)) {
	if(is_array($templateinfo2[$groupinfo['templategroupid']])) {
		$theGroupID = $groupinfo['templategroupid'];
		fwrite($handle,"\t<group templategroupid=\"".$theGroupID."\" title=\"".$groupinfo['title']."\">\n");

		foreach($templateinfo2[$groupinfo['templategroupid']] as $theTitle => $templateinfo) {
			$theTemplateID = $templateinfo['defaultid'];

			// before i messed up my own templates
			// then there was a bug in functions_xml.php
			// but i got it all worked out, and i don't think
			// i need this small fix anymore :D
			/*if($theTemplateID == 14) {
				$theTemplateID = 13;
			}

			if($theTemplateID > 14) {
				$theTemplateID -= 2;
			}*/

			$templateinfo['template'] = str_replace("\r\n","\n",$templateinfo['template']);

			fwrite($handle,"\t\t<template defaultid=\"".$theTemplateID."\" type=\"".$templateinfo['type']."\" templategroupid=\"".$theGroupID."\" title=\"".$templateinfo['title']."\" version=\"".$templateinfo['version']."\"><![CDATA[".$templateinfo['template']."]]></template>\n");
		}

		fwrite($handle,"\t</group>\n\n");
	}
}

// fetch arr
$colorinfo = mysql_fetch_array($color_q);
$attribs = "";

foreach($colorinfo as $fieldName => $value) {
	if(strlen($fieldName) <= 3 OR $fieldName == "styleid" OR $fieldName == "colorid") {
		continue;
	}

	$attribs .= $fieldName."=\"".htmlspecialchars($value)."\" ";
}

fwrite($handle,"\t<colors ".$attribs."/>\n");
fwrite($handle,"</style>");
fclose($handle);
?>