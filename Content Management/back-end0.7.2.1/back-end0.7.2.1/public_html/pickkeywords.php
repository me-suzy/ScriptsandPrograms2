<?php
// $Id: pickkeywords.php,v 1.5 2005/03/22 16:06:57 krabu Exp $
/**
* Support to help users pick good keywords
*
* a select option to pick from a number of keywords as defined in an array.
* referenced  by article blocks (and section blocks in the future)
*
* @package     Back-End on phpSlash
* @copyright   2002 - Mike Gifford
* @author      Ian Clysdale, Mike Gifford, Peter Cruickshank
* @version     $Id: pickkeywords.php,v 1.5 2005/03/22 16:06:57 krabu Exp $
*
*/
$pagetitle   = "Pick Keywords";       // The name to be displayed in the header
$xsiteobject = "Admin";  // This Defines The META Tag Object Type

require_once('config.php');

$keywords = pslNew('BE_Keywords',@$_GET['from']);

$out = $keywords->showPicker(@$_GET['from'], @$_GET['lang'], @$_BE['static_keywords']);
// showPicker checks that from and lang are valid, so no explicit cleaning required

echo $out;
?>
