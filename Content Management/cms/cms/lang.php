<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: lang.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Select Language mechanisem
// ----------------------------------------------------------------------

if(isset($_GET['lang'])){
	setcookie('lang',htmlspecialchars($_GET['lang']));
	$lang = htmlspecialchars($_GET['lang']);
	$_COOKIE['lang'] = $lang;
}else if(isset($_COOKIE['lang'])){
	$lang = htmlspecialchars($_COOKIE['lang']);
}else{
	$lang = DEFAUL_LANG;
	$_COOKIE['lang'] = $lang;
}

if(file_exists("language/$lang.php")){
    include_once("language/$lang.php");
}else{
    include_once("language/en.php");
}
?>
