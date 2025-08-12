<?php

// $Id: timezones.php 231 2005-11-20 10:56:13Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*

Timezone list file

This file is used to generate a list of timezones for the user to select

*/

if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

// Create array
$TIMEZONES = array();

// Add "System Default" to top of list
if(isset($TEXT['SYSTEM_DEFAULT'])) {
	$TIMEZONES['-20'] = $TEXT['SYSTEM_DEFAULT'];
} else {
	$TIMEZONES['-20'] = 'System Default';
}

$TIMEZONES['-12'] = 'GMT - 12 Hours';
$TIMEZONES['-11'] = 'GMT -11 Hours';
$TIMEZONES['-10'] = 'GMT -10 Hours';
$TIMEZONES['-9'] = 'GMT -9 Hours';
$TIMEZONES['-8'] = 'GMT -8 Hours';
$TIMEZONES['-7'] = 'GMT -7 Hours';
$TIMEZONES['-6'] = 'GMT -6 Hours';
$TIMEZONES['-5'] = 'GMT -5 Hours';
$TIMEZONES['-4'] = 'GMT -4 Hours';
$TIMEZONES['-3.5'] = 'GMT -3.5 Hours';
$TIMEZONES['-3'] = 'GMT -3 Hours';
$TIMEZONES['-2'] = 'GMT -2 Hours';
$TIMEZONES['-1'] = 'GMT -1 Hour';
$TIMEZONES['0'] = 'GMT';
$TIMEZONES['1'] = 'GMT +1 Hour';
$TIMEZONES['2'] = 'GMT +2 Hours';
$TIMEZONES['3'] = 'GMT +3 Hours';
$TIMEZONES['3.5'] = 'GMT +3.5 Hours';
$TIMEZONES['4'] = 'GMT +4 Hours';
$TIMEZONES['4.5'] = 'GMT +4.5 Hours';
$TIMEZONES['5'] = 'GMT +5 Hours';
$TIMEZONES['5.5'] = 'GMT +5.5 Hours';
$TIMEZONES['6'] = 'GMT +6 Hours';
$TIMEZONES['6.5'] = 'GMT +6.5 Hours';
$TIMEZONES['7'] = 'GMT +7 Hours';
$TIMEZONES['8'] = 'GMT +8 Hours';
$TIMEZONES['9'] = 'GMT +9 Hours';
$TIMEZONES['9.5'] = 'GMT +9.5 Hours';
$TIMEZONES['10'] = 'GMT +10 Hours';
$TIMEZONES['11'] = 'GMT +11 Hours';
$TIMEZONES['12'] = 'GMT +12 Hours';
$TIMEZONES['13'] = 'GMT +13 Hours';

?>