<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// First line defense.
if (file_exists(dirname(__FILE__)."/first_defense.php")) {
	include_once(dirname(__FILE__)."/first_defense.php");
	block_refererspam();
}


define('__SILENT__', TRUE);
// apparently also defined in pv_core
// $marco (who hates php notices)
// define('INPIVOT', TRUE);
define('LIVEPAGE', TRUE);

include_once("pv_core.php");
include_once("modules/module_userreg.php");


// convert encoding to UTF-8
i18n_array_to_utf8($Pivot_Vars, $dummy_variable);

$live_output = TRUE;

if (!isset($Pivot_Vars['w'])) {
	$Pivot_Vars['w']="";
}

if (!isset($Pivot_Vars['c'])) {
	$Pivot_Vars['c']="";
}

if (!isset($Pivot_Vars['u'])) {
	$Pivot_Vars['u']="";
}

if (!isset($Pivot_Vars['t'])) {
	$Pivot_Vars['t']="";
} else {
	$Pivot_Vars['t'] = basename($Pivot_Vars['t']);
}

$output = generate_live_page($Pivot_Vars['w'], $Pivot_Vars['c'], $Pivot_Vars['t'], $Pivot_Vars['u']);

echo $output;



add_hook("getref", "pre");
execute_hook("getref", "pre", $hook_output);


?>
