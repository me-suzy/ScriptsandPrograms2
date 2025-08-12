<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: final.php
*	Description: Calls up news from the database--this file is included in
*				 index.php, and in any other file you want to use to
*				 display news.
****************************************************************************
*	Build Date: March 8, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//Include the global settings file
include_once "settings.php";
include "functions/final_functions.php";

check_expiry();

if ($disable_categories == 0)
{
	show_categories();
	$type = "cat";
}

else
{
	$type = "general";
}

switch ($headline)
{
	case 0:
	default:
		show_general("pinned");
		show_general($type);
		break;

	case 1:
		show_headlines("pinned");
		show_headlines($type);
		break;
}
?>