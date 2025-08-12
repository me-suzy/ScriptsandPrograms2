<?php

/***************************************************************************

 datepicker.php
 ---------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

include_once ("rootdatapath.php");

includeLanguageFiles('admin','main');


$todaydate = strftime("%d");
$todaymonth = strftime("%m");
$todayyear = strftime("%Y");

if (!isset($_POST["control"]))	{ $_POST["control"] = $_GET["control"]; }

if (!isset($_POST["day"]))		{ $_POST["day"] = $_GET["day"]; }
if (!isset($_POST["month"]))	{ $_POST["month"] = $_GET["month"]; }
if (!isset($_POST["year"]))	{ $_POST["year"] = $_GET["year"]; }

if ($_POST["day"] == '')	{ $_POST["day"] = $todaydate; }
if ($_POST["month"] == '')	{ $_POST["month"] = $todaymonth; }
if ($_POST["year"] == '')	{ $_POST["year"] = $todayyear; }

if (($_POST["day"] < 10) && (strlen($_POST["day"]) > 1)) { $_POST["day"] = substr($_POST["day"],1,1); }
if (($_POST["month"] < 10) && (strlen($_POST["month"]) > 1)) { $_POST["month"] = substr($_POST["month"],1,1); }


force_page_refresh();
frmDates($_POST["day"],$_POST["month"],$_POST["year"]);


function frmDates($dd,$mm,$yy)
{
	global $_POST, $EzAdmin_Style, $todaydate, $todaymonth, $todayyear;


	$month_titles = $GLOBALS["tMonth_Array"];
	$week_titles = $GLOBALS["tWeek_Array"];
	$x = $mm;
	$month_text = $month_titles[$x];

	admhdr();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
			function ReturnDate(sDD,sMM,sYY) {
				window.opener.document.MaintForm.<?php echo $_POST["control"]; ?>Day.value=sDD;
				window.opener.document.MaintForm.<?php echo $_POST["control"]; ?>Month.value=sMM;
				window.opener.document.MaintForm.<?php echo $_POST["control"]; ?>Year.value=sYY;
				window.close();
			}
		//  End -->
	</script>
	<title>DatePicker</title>
	</head>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<table border="0" width="100%" cellspacing="3" cellpadding="3">


		<tr>
			<form name="MaintForm" action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="POST" enctype="multipart/form-data">
				<td><select name="month"><?php RenderMonths($mm); ?></select></td>
				<td><select name="year"><?php RenderYears($yy); ?></select></td>
				<td><input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go"></td>
				<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
				<input type="hidden" name="control" value="<?php echo $_POST["control"]; ?>">
			</form>
		</tr>
		<tr><td colspan="3">
				<?php
				// Determine the total number of days in the month
				$totaldays = 31;
				while (!checkdate( $mm, $totaldays + 1, $yy )) { $totaldays--; }
				$totaldays++;

				// Build table
				?><table border="1" cellpadding="2" cellspacing="1" width="100%" class="tablecontent"><tr><?php
				for ( $x = 0; $x < 7; $x++ ) echo '<th><b>'.substr($week_titles[$x],0,1).'</b></th>';
				echo '</tr>';

				$day = 1;
				// Ensure that a number of blanks are put in so that the first day of the month lines up with the proper day of the week
				// $offset = date( "w", mktime( 0, 0, 0, $month, $day, $year )) + 1;
				$offset = date( "w", mktime( 0, 0, 0, $mm, $day, $yy ));
				if ($offset == 7) { $offset = 0; }
				echo '<tr>';
				if ($offset > 0) echo str_repeat( '<td>&nbsp;</td>', $offset );

				// Start entering in the information
				for ( $day = 1; $day <= $totaldays; $day++ ) {
					echo '<td align="center"><a href="javascript:ReturnDate('.$day.','.$mm.','.$yy.')">';
					if (($yy == $todayyear) && ($mm == $todaymonth) && ($day == $todaydate)) {
						echo '<font color="Red"><b>'.$day.'</b></font>';
					} else {
					if (($offset == 0) || ($offset == 6)) {
						echo '<font color="Blue"><b>'.$day.'</b></font>';
					} else {
						echo $day;
					}
					echo '</a></td>';
				}
				$offset++;
				// If we're on the last day of the week, wrap to the other side
				if ($offset > 6) {
					$offset = 0;
					echo '</tr>';
					if ( $day < $totaldays ) echo '<tr>';
				}
			}

			// Fill in the remaining spaces for the end of the month, just to make it look pretty
			if ($offset > 0) $offset = 7 - $offset;
			if ($offset > 0) echo str_repeat( '<td>&nbsp;</td>', $offset );
			?>
			</table>
		</td>
	</tr>
	<tr class="headercontent">
		<td colspan="3" align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseHelp"]; ?></a></td>
	</tr>
	</table>
	</body>
	</html>
	<?php
} // function frmModules()




function RenderMonths($sDate)
{
	$Months = $GLOBALS["tMonth_Array"];
	for ( $sMonth = 1; $sMonth <= 12; $sMonth++ ) {
		$s = "";
		if ($sMonth == $sDate) $s = " selected";
		echo '<option value="'.$sMonth.'"'.$s.'>'.$Months[$sMonth].'</option>';
	}
} // function RenderMonths()


function RenderYears($sDate)
{
	$sYear = strftime("%Y") - 2;
	$eYear = $sYear + 32;
	while ($sYear <= $eYear) {
		$s = "";
		if ($sYear == $sDate) $s = " selected";
		echo '<option value="'.$sYear.'"'.$s.'>'.$sYear.'</option>';
		$sYear++;
	}
} // function RenderYears()

?>
