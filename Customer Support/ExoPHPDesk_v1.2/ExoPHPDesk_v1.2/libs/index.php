<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Main File
// >>
// >> INDEX . PHP File - Main File For ExoPHPDesk
// >> Started : January 25, 2004
// >> Edited  : January 25, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

_parse( $tpl_dir . 'index.tpl' );
echo $class->read;

?>