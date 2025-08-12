<?php

// ----------------------------------------------------------------------
// eFiction
// Copyright (C) 2003 by Rebecca Smallwood.
// http://orodruin.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

include ("header.php");
global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout;
$result = mysql_query("SELECT help,copyright from ".$tableprefix."fanfiction_settings");
$helpcop = mysql_fetch_array($result);

$tpl = new TemplatePower( "skins/$skin/default.tpl" );
$tpl->prepare();
$tpl->assign( "footer", $helpcop[copyright] );
$tpl->assign( "logo", $logo );
$tpl->assign( "home", $home );
$tpl->assign( "recent", $recent );
$tpl->assign( "catslink", $catslink );
$tpl->assign( "authors", $authors );
$tpl->assign( "help", $help );
$tpl->assign( "search", $search );
$tpl->assign( "login", $login );
$tpl->assign( "adminarea", $adminarea );
$tpl->assign( "titles", $titles );
$tpl->assign( "logout", $logout );

$output = "$helpcop[help]";
$tpl->assign( "output", $output );

$tpl->printToScreen();

?>