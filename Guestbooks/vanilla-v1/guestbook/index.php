<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: index.php
*	Description: Default Index Page
****************************************************************************
*	Build Date: August 19, 2005
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

include "settings.php";
include "templates/index_header.php";

$act = $_REQUEST["act"];

switch($act)
{
	default:
		view();
		break;

	case "add":
		edit("add");
		break;

	case "sign":
		if ($allow != 0)
		{
			form("add_entry");
		}
		else
		{
			echo '<p class="response">Guestbook signing is currently disabled.</p>';
		}
		break;
}

//Include the footer
include "templates/index_footer.php";

if ($connected)
{
	mysql_close($connected);
}
?>