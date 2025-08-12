<?php
// +----------------------------------------------------------------------+
// |                        AJRgal version 4.1                            |
// +----------------------------------------------------------------------+
// |     This program is free software; you can redistribute it and/or    |
// |      modify it under the terms of the GNU General Public License     |
// |    as published by the Free Software Foundation; either version 2    |
// |        of the License, or (at your option) any later version.        |
// |                                                                      |
// |   This program is distributed in the hope that it will be useful,    |
// |   but WITHOUT ANY WARRANTY; without even the implied warranty of     |
// |    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     |
// |          GNU General Public License for more details.                |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// |    along with this program; if not, write to the Free Software       |
// |     Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA        |
// |                          02111-1307, USA.                            |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author:   Andrew John Reading - A.J.Reading <andrew@cbitsonline.com> |
// | Website:  http://www.cbitsonline.com                                 |
// +----------------------------------------------------------------------+
// | File:             index.php                                          |
// | Description:      Contains html and relevent includes to display     |
// |                   all the images and the gallery correctly           |
// | Last Update:      22/11/2005                                         |
// +----------------------------------------------------------------------+
ini_set('error_reporting', E_ALL ^ E_NOTICE);
require_once('includes/config.php');
require_once('includes/class.php');
$ajrgal = new ajrgal(dirname( __FILE__), $num_rows, $num_cols, $page);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head><title><?php echo $main_title; ?></title>
  <meta name="Author" content="<?php echo $main_author; ?>">
  <meta name="Description" content="<?php echo $main_description; ?>">
  <meta name="Keywords" content="<?php echo $main_keywords; ?>">
  <meta http-equiv="Content-Language" content="en-gb">
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
  <link href="style.css" rel="stylesheet" type="text/css">
 </head>
 <body>
 <div align="center">
  <!-- Start AJRgal Thumbnail Table -->
  <table class="" cellpadding="0" cellspacing="5" width="220">
   <tr>
<?php $ajrgal->display("td", "_blank"); ?>
   </tr>
  </table>
  <!-- End AJRgal Thumbnail Table -->
  <!-- Start AJRgal Links Table -->
  <table class="" cellpadding="0" cellspacing="0" width="220">
   <tr>
    <td width="20%">
     <b><?php $ajrgal->pagePrev("navText"); //Display "Previous" page links ?></b>
    </td>
    <td width="60%">
     <b><?php $ajrgal->pageNumbers("navText","navText2"); //Display page number links ?></b>
    </td>
    <td width="20%">
     <b><?php $ajrgal->pageNext("navText"); //Display "Next" page links?></b>
    </td>
   </tr>
  </table>
  <!-- End AJRgal Links Table -->
  <br>
  <?php echo $powered; ?><br>
  </div>
 </body>
</html>
