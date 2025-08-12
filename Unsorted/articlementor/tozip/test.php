<?php
require ("incconfig.php");
require ("inctemplate.php");


$sContent = $sContent . "Hej";
WriteContent( $conn, $NavNames, $NavLinks, $sContent, $sHeader, $sHeader2);
?>