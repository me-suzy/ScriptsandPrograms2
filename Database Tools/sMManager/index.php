<?php
/*
index.php
Author : Thomas Whitecotton
Email  : admin@ciamosbase.com
Website: http://www.ciamosbase.com
*/
include("includes/include.php");
include("includes/functions.php");
include("includes/layout.php");

$op = retrieve_var();

$navMenu = '<a href="index.php">Home</a>';
$dbMenu = '<a href="index.php?op=VIEW">'.$dbname.'</a>';
$exMenu = '<a href="index.php?op=VIEW_EXPORTS">View All</a><br />
<a href="index.php?op=DELETE_EXPORTS">Delete All</a>';

$layout = new siteLayout;
$layout->pageHead();
$layout->pageTop();
$layout->makeMenu('Navigation',$navMenu);
$layout->makeMenu('Database',$dbMenu);
$layout->makeMenu('Exported Files',$exMenu);

switch($op) {
	case "DELETE_EXPORTS":
		$content = deleteExports();
		$layout->pageCenter("Delete Exported XML Files",$content);
	break;

	case "EXECUTE":
		$content = executeSQL();
		$layout->pageCenter("SQL Command",$content);
	break;

	case "EXPORT":
		$step = retrieve_var('step');
		if(empty($step) || $step=='') {
			$content = exportStep1();
			$layout->pageCenter("Export", $content);
		} else 
		if($step=='2') {
			$content = exportStep2();
			$layout->pageCenter("Export", $content);
		} else 
		if($step=='3') {
			$content = exportStep3();
			$layout->pageCenter("Exported", $content);
		}
	break;

	case "VIEW":
		$content = viewDatabase();
		$layout->pageCenter("Viewing Database",$content);
	break;

	case "VIEW_EXPORTS":
		$content = viewExports();
		$layout->pageCenter("Viewing Exports",$content);
	break;

	default:
		$content = defaultMessage();
		$layout->pageCenter("simplyDBtoXML",$content);
	break;
}

$layout->pageBottom();
$layout->compile();
?>