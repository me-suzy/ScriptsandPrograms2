<?php

// datepicker.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $auth$
// $Id: datepicker.php,v 1.8 2005/07/22 18:32:55 paolo Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

$name_month = explode('_', $_GET['m']);
$name_day2  = explode('_', $_GET['d']);
$today = $_GET['t'];


$out = array();
for ($i=1; $i<13; $i++) {
    $out['month'] .= '<option value="'.$i.'">'.$name_month[$i]."</option>\n";
}
for ($i=2000; $i<2021; $i++) {
    $out['year'] .= '<option value="'.$i.'">'.$i."</option>\n";
}
for ($i=0; $i<7; $i++) {
    $out['day2'] .= '<td>'.$name_day2[$i]."</td>\n";
}
for ($i=1; $i<43; $i++) {
    $out['btn'] .= '<td><input name="btn'.$i.'" onClick="go(this.value);" type="button" /></td>'."\n";
    $out['btn'] .= ($i % 7) ? '' : "            </tr>\n            <tr>\n";
}


echo '<html>
<head>
<title>_______________________________</title>
<script src="datepicker.js" type="text/javascript"></script>
<style type="text/css">
body      { font-size: 8pt; font-family: Arial, helvetica, sans-serif; text-decoration: none; }
input     { width: 30px; background-color: #f5f5f5; border: none; }
input.std { border: thin outset; background-color: silver; width: 28px; height: 24px; }
#cal      { background-color: #006699; color: #cccccc; font-size: 10pt; font-weight: bold; text-align: center; }
</style>
<link rel="shortcut icon" href="/'.PHPR_INSTALL_DIR.'favicon.ico" />
</head>
<body style="margin:0px;text-align:center;">

<form name="frm" style="display:inline;">

    <table cellspacing="0" cellpadding="0" width="200" border="1">
        <tr align="center" bgcolor="silver">
            <td>
                <input name="previous" class="std" onClick="prevMonth();" type="button" value="&lt;" />
            </td>
            <td>
                <select name="lMonths" style="left:2px;width:80px;top:2px;height:22px;" onChange="selMonth(this.selectedIndex);">
'.$out['month'].'
                </select>
            </td>
            <td>
                <select name="lYears" style="width:80px;height:22px;" onChange="selYear(this.selectedIndex);">
'.$out['year'].'
                </select>
            </td>
            <td>
                <input name="next" class="std" onClick="nextMonth();" type="button" value="&gt;" />
            </td>
        </tr>
    </table>

    <table cellSpacing="0" cellPadding="0" width="200" border="2">
        <tr class="cal">
'.$out['day2'].'
        </tr>
        <tr>
'.$out['btn'].'
            <td colspan="7" align="center">
                <input name="today" class="std" style="width:100px;" value="'.__('today').'" onClick="go(\'x\');" type="button">
            </td>
        </tr>
    </table>

</form>

<script type="text/javascript">
<!--
picker();
//-->
</script>

</body>
</html>
';

?>
