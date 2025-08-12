<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

include('./includes/config.php');
include('./includes/functions/fns_db.php');
include('./includes/settings.php');
include('./includes/functions/fns_std.php');

$DOC_TITLE = "User Not Active";

include_once('./templates/header.php');
?> 
  <p><span class="error_mark">LOGIN ACCOUNT NOT ACTIVE</span></p>
  <p>Sorry, you have reached this page because your login account is not active. <br>
    Please contact the site owner.</p>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<?php include_once('./templates/footer.php'); ?>
