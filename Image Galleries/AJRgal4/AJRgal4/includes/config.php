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
// | File:             config.php                                         |
// | Description:      Used to change and control vital variables         |
// | Last Update:      22/11/2005                                         |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// |                            PAGE SETTINGS                             |
// +----------------------------------------------------------------------+

// Page Title
$main_title = 'AJRgal v4.1';

// Page Author
$main_author = 'A.J.Reading';

// Page Description
$main_description = 'Easy to use PHP Image Gallery - Requires PHP4 + GD by A.J.Reading - www.cbitsonline.com';

// Page Keywords
$main_keywords = 'php, gd, gallery, ajreading, a.j.rading, images, image, image gallery, cbits, cbitsonline';

// +----------------------------------------------------------------------+
// |                         THUMBNAIL SETTINGS                           |
// +----------------------------------------------------------------------+

// Maximum Thumbnail Width
$max_width = 100;

// Maximum Thumbnail Height
$max_height = 100;

// AJRgal now has two display methods, please choose one of the following:

// PROPORTIONAL: Enter "1" below to have images resized to a max width and
// height proportionally

// FIXED: Enter "2" below to have images resized to a fixed height or width
// without distorting the images

$display_method = 2; // Enter either 1 for "proportional" or 2 for "fixed"

// FIXED SETTINGS: If fixed is the chosen display method please choose
// which part of the image should be used, the options are listed below:

// topleft
// bottomleft
// topright
// bottomright
// center

$fixed_pos = "center";

// +----------------------------------------------------------------------+
// |                         DISPLAY SETTINGS                             |
// +----------------------------------------------------------------------+

// Number of rows to display pictures
$num_rows = 2;

// Number of columns to display pictures
$num_cols = 2;

// Thumbnail Table <td> Class
$td_align = 'center';


// +----------------------------------------------------------------------+
// |                    DO NOT EDIT BELOW THIS LINE                       |
// +----------------------------------------------------------------------+
$powered = '<a href="http://www.cbitsonline.com" target="_blank">PHP Image Gallery</a> Provided by: A.J.Reading';
$page = $_GET['page']; if(!isset($page)) { $page = 1; }
?>
