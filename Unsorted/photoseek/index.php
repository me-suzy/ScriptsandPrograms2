<?php
 // file: index.php
 // desc: Photoseek switchboard
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include ("config.inc");

switch ($action) {
  case "Search Categories":
  	$criteria = $criteria_categories;
	$search   = $search_categories;
	$action   = "catsearch";
	include ("search.php");				break;
  case "Search Keywords":
  	$criteria = $criteria_keywords;
	$search   = $search_keywords;
	$action   = "keysearch";
  	include ("search.php");				break;
  case "catsearch":
  case "keysearch":
  case "locationsearch":
  case "simplesearch":
  case "advancedsearch":
  case "search":
  	$criteria = ( !empty($criteria_regular) ? $criteria_regular : $criteria );
  	$search   = ( !empty($search_regular) ? $search_regular : $search );
  	include ("search.php");				break;
  case "info":       include ("image_information.php");  break;
  case "admin":      include ("admin.php");              break;
  case "auth":       include ("authenticate.php");       break;
  case "Tree View":  Header ("Location: ".
   str_replace("index.php", "tree_view.php", basename($GLOBALS["REQUEST_URI"])).
   "?repository=$repository"); break;
  case "Advanced Search":
  	include ("advancedsearchform.php");		break;
  default:           include ("searchform.php");         break;
} // end switch for action

?>
