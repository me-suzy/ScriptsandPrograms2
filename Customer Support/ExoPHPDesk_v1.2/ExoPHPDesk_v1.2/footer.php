<?php

// << -------------------------------------------------------------------- >>
// >> EXOPHPDesk Footer File
// >>
// >> FOOTER . PHP File - Footer File For All Pages
// >> Started : January 03, 2004
// >> Edited  : June 17, 2004
// << -------------------------------------------------------------------- >>

// GENERATE EXECUTION TIME
$EndTime     =  end_time();
$Generation  =  number_format( $EndTime - $StartTime, 4 );

// PHP And mySQL Execution Time!!
$myTime  = number_format( $db->exec_time, 3 );
$phpTime = number_format( $Generation - $myTime, 3 );

// PHP and mySQL Load!!
$myLoad  = number_format( ( $myTime / $Generation ) * 100, 0 );
$phpLoad = number_format( ( $phpTime / $Generation ) * 100, 0 );

// QUERIES USED
$Queries     =  $db->count;

// PARSE THE FOOTER FILE
if ( ACT != 'livechat' && $_GET['print'] != 1 )
{
	_parse ( $tpl_dir . 'footer.tpl' );
	echo $class->read;
}

// Close mySQL Connection
$db->end();

// Start GZIP Compression
if( $doGzip )
{
	$Content = ob_get_contents();
	ob_end_clean();
	ob_start( 'ob_gzhandler' );
	echo $Content;
}

exit;

?>