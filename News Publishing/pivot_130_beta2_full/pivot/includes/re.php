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


// $redir = str_replace($_SERVER['SCRIPT_NAME']."?", "", $_SERVER['REQUEST_URI']);
$redir = ($_SERVER['QUERY_STRING']);

header("Location: $redir");



?>
