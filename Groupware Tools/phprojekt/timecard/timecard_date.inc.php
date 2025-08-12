<?php

// timecard_date.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2004 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther
// $Id: timecard_date.inc.php,v 1.7.2.1 2005/09/05 12:18:09 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) {
    die('Please use index.php!');
}

if (!$day)   { $day   = date('d', mktime(date('H')+PHPR_TIMEZONE,date('i'),date('s'),date('m'),date('d'),date('Y'))); }
if (!$month) { $month = date('m', mktime(date('H')+PHPR_TIMEZONE,date('i'),date('s'),date('m'),date('d'),date('Y'))); }
if (!$year)  { $year  = date('Y', mktime(date('H')+PHPR_TIMEZONE,date('i'),date('s'),date('m'),date('d'),date('Y'))); } 

// set some date variables
if (!$datum) {
    if (!$year) {
        $datum = date('Y-m-d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    } else {
        if (strlen($day) == 1) {
            $day = '0'.$day;
        }
        if (strlen($month) == 1) {
            $month = '0'.$month;     // date has only 3 digits?
        }
        $datum = "$year-$month-$day";
    }
} else {
    list($year,$month,$day) = explode('-', $datum); 
}


$today1 = date('Y-m-d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
function check_4d($value) {
  if (strlen($value) == 3) { $value = "0".$value; }
  if (strlen($value) == 2) { $value = "00".$value; }
  if (strlen($value) == 1) { $value = "000".$value; }
  return $value;
}
?>
