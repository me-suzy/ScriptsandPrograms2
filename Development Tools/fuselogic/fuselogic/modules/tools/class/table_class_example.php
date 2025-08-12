<?php
/*
*	@PROGRAM NAME:	Table Class Example
*	@FILENAME:		multiplication_table.php
*	@PROJECT NAME:	Table Class Example
*	@LICENSE FILE:	n/a
*	@VERSION:		1.0
*	
*	@AUTHOR_INFO
*		@@NAME:				Jason Lotito
*		@@EMAIL:			jason@lehighweb.com
*		@@URL:				http://www.newbienetwork.net/	
*
*	@LATEST_CHANGE: 
*		@@ 9/1/2001: Initial Creation - JL
*		@@ 9/3/2001: Corrected 'Undefined variable' warning on Line 39 - JL/NH
*/

include "class.table.php";

$starting_number = (int)$HTTP_GET_VARS['starting_number'];

$table = new Table();
$table->SetTableAttributes( array( "width" => "600", "border" => "1", "align" => "center", "cellpadding" => "4" ) );
$table->SetDefaultCellAttributes( array( "width" => "60", "bgcolor" => "white", "align" => "center" ) );
if ( isset($starting_number) )
{
	$total_num = $starting_number + 10;
} else {
	$starting_number = 1;
	$total_num = $starting_number + 9;
}
$row = $table->AddRow();
$table->SetCellColSpan( $row, 1, 10 );
$table->SetCellAttribute( $row, 1, "width", "100%" );
$table->SetCellContent( $row, 1, "<h2>Mutiplication Table Starting At $starting_number</h2>" );

$row = $table->AddRow();
$table->SetFancyRowStyle( $row, array("bgcolor" => "black", 'style' => 'color: white' ) );
$table->SetRowContent( $row, range(1, 10) );


for ( $x = $starting_number; $x <= $total_num; $x++ )
{
	$row = $table->AddRow();
	for ( $i = 1; $i <= 10; $i++ ) 
	{
		$num = $x*$i;
		$content = '<a href="'.$PHP_SELF.'?starting_number='.urlencode($num).'" title="'.$x.' x '.$i.'">';
		$content .= $num;
		$content .= '</a>';
		$number = ($x + 3) - $starting_number; 
		if ( $i == 1 )
		{
			$table->SetCellAttribute( $row, 1, "bgcolor", "#cccccc" );
		}
		$table->SetCellContent( $row, $i, $content );
	}
}

$table->set2RowColors( "white", "#eeeeee", 3, $row );

$table->PrintTable();
/*
*
*	@DOCINFO
*		@@TABSIZE:			4 SPACES
*		@@TAB_OR_SPACE:			TAB
*		@@LANGUAGE:			PHP
*		@@EDITOR:			EditPlus
*/
?>

