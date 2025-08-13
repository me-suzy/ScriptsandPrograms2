<?php

require "init.php";

if( !isset($session['exists']) ) {
 header("Location: http://".$HTTP_SERVER_VARS['HTTP_HOST'].dirname($HTTP_SERVER_VARS['PHP_SELF'])."/expire.html");
 exit();
}

$current_worksheet = (int)$session['sheet'];

if( isset($session['skip_hidden_cells']) ) {
	$hc = (int)$session['skip_hidden_cells'];
} else {
	$hc = 0;
}

?>
<html>
<head>
<?php
$eexp = new ExcelExplorer();

$res = $eexp->Explore_file($session['file'],array(
		'read_only_sheets_info' => true
	));

switch ($res) {
	case 0: break;
	case 1: die( $die_hdr.'File corrupted or not in Excel 5.0 and above format'.$die_ftr );
	case 2: die( $die_hdr.'Unknown or unsupported Excel file version'.$die_ftr );
	default:
		die( $die_hdr.'Excel Explorer give up'.$die_ftr );
}


?>
<style>
<!--
body, a, table, tr, td, input {font-size: 11px; font-family: Tahoma, Verdana, MS sans serif, Arial, Helvetica, sans-serif}
a:hover, a:active, a:visited, a:link {color: #000000; text-decoration: none}
td.txt {background-color: #EEEEEE}
td.txt2 {background-color: #FFCCCC}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

<table border=0 cellspacing=0 cellpadding=0 height="100%"><tr><td valign=bottom>
<table border=0 cellspacing=0 cellpadding=0 bgcolor="#000000" valign=bottom>
<tr><td>
<table border=0 cellspacing=1 cellpadding=2>
<tr>
<?php

for( $worksheet=0; $worksheet<$eexp->GetWorksheetsNum(); $worksheet++ ) {
	$wt = $eexp->GetWorksheetType($worksheet);

if( $current_worksheet==$worksheet ) {
	print "<td class=txt2>";
} else {
	print <<<CELL
<td class=txt onmouseover="javascript: this.style.backgroundColor='#CCCCCC'" onmouseout="javascript: this.style.backgroundColor='#EEEEEE'">
CELL;
	print "<a href=\"showdata.php?sheet=$worksheet\" target=\"data\">";
}
	print '&nbsp;';
	if( $wt==0 ) print "<b>";

	$title = $eexp->GetWorksheetTitle($worksheet);
	echo $eexp->AsHTML($title);

	switch ($wt) {
		case 0:
			print "</b>";
			break;
		case 2:
			print " <i>(chart)</i>";
			break;
		case 6:
			print " <i>(Visual Basic module)</i>";
			break;
		default:
	}
	if( $eexp->IsHiddenWorksheet($worksheet) ) print " <i>- hidden</i>";
	if( $current_worksheet!=$worksheet )
		print '</a>';

	print "&nbsp;</td>\n";
}

?>
</tr></table>
</td></tr></table>
</td></tr></table>

</body>
</html>