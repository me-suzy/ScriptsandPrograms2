<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //FRONT END - FAQ\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_faq.php");
include("./global.php");

// get forum home stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// create nav bar array
$navbarArr = Array(
	"wtcBB FAQ" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// deal with sessions
$sessionInclude = doSessions("Viewing FAQ","wtcBB FAQ");
include("./includes/sessions.php");

// handle navigation with HEADER redirect...
if($_GET['faqNav']) {
	header("Location: faq.php#".$_GET['faqNav']);
}

$faqinfo = cacheFAQEntities();

// no faq...
if(!is_array($faqinfo)) {
	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit out content
	printTemplate($header);
	printStandardError("error_standard","No FAQ Entries exist.");
	printTemplate($footer);

	exit;

	doError(
		"No FAQ Entries Exist."
	);
}

$faqbits = "";
$faqSelectBits = recurseEntities(-1,true);

$faqbits = "";
$faqbits = recurseEntities();

// get templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$faq = \"".getTemplate("faq")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

printTemplate($header);
printTemplate($faq);
printTemplate($footer);

?>