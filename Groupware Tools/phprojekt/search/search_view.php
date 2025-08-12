<?php

// votum_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: search_view.php,v 1.13 2005/06/10 12:56:34 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


// tabs
$tabs = array();
$output = get_tabs_area($tabs);

// button bar
$buttons = array();
$buttons[] = array('type' => 'text', 'text' => __('Search term').': '.$searchterm);
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

if ($searchterm) {
    include_once('./search_forms.php');
    //$output .= $ou;
}
else {
    $ou = '';
}

$output .= '
<br/>
<div class="inner_content">
    <div class="boxHeader">'.__('Extended search').'</div>
    <div class="boxContent">'.$out1.'</div>
    <br style="clear:both"/><br/>
    '.$ou.'
</div>
';

echo $output;

?>
