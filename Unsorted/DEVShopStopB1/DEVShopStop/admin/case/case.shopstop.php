<?php
/************************************************************************/
/*                                                                      */
/* Copyright (c) 2001-2002 by CrossWalkCentral                          */
/* http://www.crosswalkcentral.net                                      */
/*                                                                      */
/* CrossWalkCentral                                                     */
/* You Web Hosting Community!                                           */
/*                                                                      */
/* Let us customize this script for you.                                */
/*                                                                      */
/* Please let us know what you think of this script			*/
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/* 									*/
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    case "shopstop":
    include("admin/modules/ShopStop/index.php");
    break;

    case "shoplst":
    include("admin/modules/ShopStop/shoplst.php");
    break;

    case "shopcat":
    include("admin/modules/ShopStop/shopcat.php");
    break;

    case "editcat":
    include("admin/modules/ShopStop/editcat.php");
    break;

    case "shopstopcfg":
    include("admin/modules/ShopStop/config.php");
    break;

    case "editprod":
    include("admin/modules/ShopStop/editprod.php");
    break;
}

?>