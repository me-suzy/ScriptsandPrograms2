<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: archive.php
*	Description: Archive functions
****************************************************************************
*	Build Date: March 8, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/
//Include the global settings file
include "settings.php";
include "templates/index_header.php";

include "functions/final_functions.php";
//
//Check if any particular date or category has been set
//and display navigation form
//
	$month = $_REQUEST["month"];
	$year = $_REQUEST["year"];
	$cat = $_REQUEST["cat"];
	if (empty($month))
	{
		$month = date("m");
	}
	if (empty($year))
	{
		$year = date("Y");
	}
		$months_array = array(
			"1" => "January",
			"2" => "February",
			"3" => "March",
			"4" => "April",
			"5" => "May",
			"6" => "June",
			"7" => "July",
			"8" => "August",
			"9" => "September",
			"10" => "October",
			"11" => "November",
			"12" => "December",
		);
?>
<form id="view_navigation" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<div class="horizontal_nav">

		<label for="month">Month:</label>
		<select id="month" name="month">
			<option value="01" selected="selected">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select>

		<label for="year">Year:</label>
		<select id="year" name="year">
<?php
	$yearvars = date("Y");
	$minyear = 2003;

	echo "<option value=\"$yearvars\" selected=\"selected\">$yearvars</option>";

	$i = $yearvars - 1;
	while ($i >= $minyear)
	{
		echo "<option value=\"$i\">$i</option>";
		$i--;
	}
?>
		</select>

		<input type="submit" class="button" value="Go" />
		</div>
</form>
<?php

check_expiry();

$act = $_REQUEST['act'];
switch($act)
{
	case "add_comment":
		comment_edit($act);
		break;

	default:

if (!empty($_REQUEST["id"]))
{
	show_general("id",$_REQUEST['id']);
}

else
{
	if ($disable_categories == 0)
	{
		show_categories();
	}

	if (!empty($_REQUEST["cat"]))
	{
		$type = "cat";
	}

	else
	{
		$type = "archive";
	}

	show_general($type);
}
		break;
}

include "templates/index_footer.php";
?>