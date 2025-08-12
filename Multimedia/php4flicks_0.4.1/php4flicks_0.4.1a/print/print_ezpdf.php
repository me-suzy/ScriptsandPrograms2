<?php
	// test the table functions
	error_reporting(E_ALL);
	include('class.ezpdf.php');
	$pdf =& new Cezpdf();
	$pdf->selectFont('./fonts/Times-Roman.afm');

	// Connect to database
	require('../config/config.php');

	// GET WHAT
	if(!isset($_GET['what']) || $_GET['what'] == '')
		$_GET['what'] = ' ORDER BY nr ASC, name ASC';

	// select and order with what
	$query = 'SELECT DISTINCT CONCAT(cat,nr)as nr, runtime, id, fid, name, year, genre, ratio, medium FROM movies '.rawurldecode($_GET['what']) or die(mysql_error());

	// initialize the array
	$data = array();

	// do the SQL query
	$result = mysql_query($query);

	// step through the result set, populating the array, note that this could also have been written:
	//while($data[] = mysql_fetch_array($result, MYSQL_ASSOC)) {}
	while($data[] = mysql_fetch_assoc($result)) {}

	// set page size
	$pdf =& new Cezpdf($cfg['pagesize']);

	// set fonts
	$pdf->selectFont('./fonts/Times-Roman.afm');
	$tmp = array(
		'b'=>'Times-Bold.afm',
		'i'=>'Times-Italic.afm',
		'bi'=>'Times-BoldItalic.afm',
		'ib'=>'Times-BoldItalic.afm'
	);
	$pdf->setFontFamily('./fonts/Times-Roman.afm',$tmp);

	// link back
	if ($cfg['backlink'] == 1)
	{
		$pdf->ezText('
		<c:alink:./index.php>BACK</c:alink>',8,array('justification'=>'left'));
	}

	// make the table
	$pdf->ezTable($data,$cfg['tablecat'],$cfg['tabletitle']);

	// do the output, this is my standard testing output code, adding ?d=1
	// to the url puts the pdf code to the screen in raw form, good for checking
	// for parse errors before you actually try to generate the pdf file.
	if (isset($_GET['d']) && $_GET['d']){
		$pdfcode = $pdf->output(1);
		$pdfcode = str_replace("\n","\n<br>",htmlspecialchars($pdfcode));
		echo '<html><body>';
		echo trim($pdfcode);
		echo '</body></html>';
	} else {
		$pdf->stream();
 	}
?>
