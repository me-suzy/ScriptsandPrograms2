<?php
// set up the template
include("template.php");

// create template from class
$t = new Template(skinget());

// work out the page title
if ($pagetitle == ""){ $pagetitle = $config["sitename"]; }

// set up some variables
$t->set_var("SITENAME", $config["sitename"]);
$t->set_var("DATETIME", date($config["dateformat"]));
$t->set_var("VERSION", $config["version"]);
$t->set_var("PAGETITLE", $pagetitle);
$t->set_var("META_DESCRIPTION", $config["metadescription"]);
$t->set_var("META_KEYWORDS", $config["metakeywords"]);
$t->set_var("BREADCRUMBS", $bread);
$t->set_var("CSS_CODE", $t->csscode());
$t->set_var("IMAGES_DIR", $config["virtualpath"] . $t->imagesdir);
$t->set_var("ROOT", $config["virtualpath"]);
$t->set_var("LANGUAGE_CODE", $config["languagecode"]);

// language phrases
$t->set_var("SEARCH", $phrase["search"]);
$t->set_var("ALLRIGHTSRESERVED", $phrase["allrightsreserved"]);
$t->set_var("POWERED_BY", $phrase["poweredby"]);

// parse the skin selector?
if ($config["skinselector"] == "true"){
	$t->set_var("SKIN_SELECTOR", skinselector());
}

// parse in the admin navigation
if ($config["showadminlink"] == "true" || $config["showrecentlink"] == "true"){
	// put the recent  link in?
	if ($config["showrecentlink"] == "true"){
		$ncode = '<a href="' . $config["virtualpath"] . 'recent.php">' . $phrase["newest"] . '</a>';
		
		if ($config["showadminlink"] == "true"){
			$ncode .= ' | ';
		}
	}
	
	// put the admin link in?
	if ($config["showadminlink"] == "true"){
		if (!$_SERVER["REQUEST_URI"]){
			$url = "admin.php";
		} else {
			$url = "admin.php?from=" . $_SERVER["REQUEST_URI"];
		}
		
		$ncode .= '<a href="' . $config["virtualpath"] . $url . '">' . $phrase["admin"] . '</a>';
	}
	
	// and parse it in
	$t->set_var("ADMIN_LINK", $ncode);
}

// parse and output straight away
$t->set_file("page_footer", "overall_header");
$t->parse("page_all_2", "page_footer", true);
$t->p("page_all_2");
?>