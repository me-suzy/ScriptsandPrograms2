<?php


# Function MakeOrderDropMenu ##################################################
function MakeOrderDropMenu($Name,$Selected="") {

$OrderDropMenu=<<<DropMenu
<SELECT NAME="$Name" SIZE="1">
<OPTION VALUE="desc">descending</OPTION>
<OPTION VALUE="asc">ascending</OPTION>
</SELECT>

DropMenu;
	if ($Selected) {
	  $OrderDropMenu = preg_replace("|\"$Selected\">*|","\"$Selected\" SELECTED>",$OrderDropMenu);
	} # if ($Selected)
	return $OrderDropMenu;
}


# Function MakeOrderbyDropMenu ##################################################
function MakeOrderbyDropMenu($Name,$Selected="") {

$OrderbyDropMenu=<<<DropMenu
<SELECT NAME="$Name" SIZE="1">
<OPTION VALUE="added">date added</OPTION>
<OPTION VALUE="title">image title</OPTION>
<OPTION VALUE="img_date">image date</OPTION>
<OPTION VALUE="rand()">random</OPTION>
</SELECT>

DropMenu;
	if ($Selected) {
	  $OrderbyDropMenu = preg_replace("|\"$Selected\">*|","\"$Selected\" SELECTED>",$OrderbyDropMenu);
	} # if ($Selected)
return $OrderbyDropMenu;
}


# Function MakeMonthDropMenu ##################################################
function MakeMonthDropMenu($Name,$Selected="") {

$MonthDropMenu=<<<DropMenu
<SELECT NAME="$Name" SIZE="1">
    <option value="00">-- Month --</option>
    <OPTION VALUE="01">January</OPTION>
    <OPTION VALUE="02">February</OPTION>
    <OPTION VALUE="03">March</OPTION>
    <OPTION VALUE="04">April</OPTION>
    <OPTION VALUE="05">May</OPTION>
	<OPTION VALUE="06">June</OPTION>
	<OPTION VALUE="07">July</OPTION>
	<OPTION VALUE="08">August</OPTION>
	<OPTION VALUE="09">September</OPTION>
	<OPTION VALUE="10">October</OPTION>
	<OPTION VALUE="11">November</OPTION>
	<OPTION VALUE="12">December</OPTION>
</SELECT>

DropMenu;
	if ($Selected) {
	  $MonthDropMenu = preg_replace("|\"$Selected\">*|","\"$Selected\" SELECTED>",$MonthDropMenu);
	} # if ($Selected)
return $MonthDropMenu;
}
 # End of function MakeMonthDropMenu #########################################


# Function MakeDaysDropMenu ##################################################
function MakeDayDropMenu($Name,$Selected="") {

$DayDropMenu=<<<DropMenu
<SELECT NAME="$Name" SIZE="1">
    <option value="00">-- Day --</option>
    <OPTION VALUE="01">1</OPTION>
    <OPTION VALUE="02">2</OPTION>
    <OPTION VALUE="03">3</OPTION>
    <OPTION VALUE="04">4</OPTION>
    <OPTION VALUE="05">5</OPTION>
	<OPTION VALUE="06">6</OPTION>
	<OPTION VALUE="07">7</OPTION>
	<OPTION VALUE="08">8</OPTION>
	<OPTION VALUE="09">9</OPTION>
	<OPTION VALUE="10">10</OPTION>
	<OPTION VALUE="11">11</OPTION>
	<OPTION VALUE="12">12</OPTION>
	<OPTION VALUE="13">13</OPTION>
	<OPTION VALUE="14">14</OPTION>
	<OPTION VALUE="15">15</OPTION>
	<OPTION VALUE="16">16</OPTION>
	<OPTION VALUE="17">17</OPTION>
	<OPTION VALUE="18">18</OPTION>
	<OPTION VALUE="19">19</OPTION>
	<OPTION VALUE="20">20</OPTION>
	<OPTION VALUE="21">21</OPTION>
	<OPTION VALUE="22">22</OPTION>
	<OPTION VALUE="23">23</OPTION>
	<OPTION VALUE="24">24</OPTION>
	<OPTION VALUE="25">25</OPTION>
	<OPTION VALUE="26">26</OPTION>
	<OPTION VALUE="27">27</OPTION>
	<OPTION VALUE="28">28</OPTION>
	<OPTION VALUE="29">29</OPTION>
	<OPTION VALUE="30">30</OPTION>
	<OPTION VALUE="31">31</OPTION>
</SELECT>

DropMenu;
if ($Selected) {
  $DayDropMenu = preg_replace("|\"$Selected\">*|","\"$Selected\" SELECTED>",$DayDropMenu);
} # if ($Selected)
return $DayDropMenu;

} # End of function MakeDaysDropMenu #########################################


?>