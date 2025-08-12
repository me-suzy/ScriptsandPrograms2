<?
/**********************************************************
 *                phpJobScheduler                         *
 *           Author:  DWalker.co.uk                        *
 *    phpJobScheduler © Copyright 2003 DWalker.co.uk      *
 *              All rights reserved.                      *
 **********************************************************
 *        Launch Date:  Oct 2003                          *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *     Version    Date              Comment               *
 *     1.0       14th Oct 2003      Original release      *
 *     2.0       Oct 2004         Improved functions      *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *  NOTES:                                                *
 *        Requires:  PHP 4.2.3 (or greater)               *
 *                   and MySQL                            *
 **********************************************************/
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.0";
// ---------------------------------------------------------

chdir("pjsfiles");
include("phpjobscheduler.php");
if (isset($return_image))
{
 if ($return_image) include("clearpixel.gif");
}

?>
