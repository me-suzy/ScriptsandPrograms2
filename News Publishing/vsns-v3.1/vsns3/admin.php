<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: admin.php
*	Description: Admin Control Panel
****************************************************************************
*	Build Date: March 1, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net
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

//Start the session
session_start();
header("Cache-control: private"); // IE 6 Fix.

//Include the global settings file and functions file
include "settings.php";

$act = $_REQUEST["act"];

//Check to see if they have a session, if not, prompt them to log in.
if(!isset($_SESSION['password']) && $act != "login_check")
{
  $act="login";
}

include "templates/header.php";

switch ($act)
{
	case "add":
	case "edit_form":
		edit_form($act);
		break;

	case "add_news":
	case "Delete":
	case "edit":
	case "Pin":
	case "Unpin":
		edit_news($act);
		break;

	case "ban_ip":
		ip_ban($_REQUEST['ip']);
		break;

	case "blog_config":
		blog_form();
		break;

	case "blog_update":
		blog_update();
		break;

	case "changepass_update":
		changepass_update();
		break;

	case "comments_browse";
		comment_browse($_REQUEST['id']);
		break;

	case "comment_edit":
		comment_form($act,$_REQUEST['article_id']);
		break;

	case "Delete Comment":
	case "edit_comment":
		comment_edit($act);
		break;

	case "idx":
		index_display();
		break;

	case "login_check":
		login();
		break;

	case "login":
		login_form();
		break;

	case "logout":
		logout();
		break;

	case "manage_ip":
		ip_manage();
		break;

	case "mysql":
		mysql_config();
		break;

	case "mysql_update":
		mysql_update();
		break;

	case "news_config":
		newsopt_form();
		break;

	case "newsopt_update":
		newsopt_update();
		break;

	case "pass":
		changepass_form();
		break;

	case "queue":
	case "update_queue":
		manage_queue($act);
		break;

	case "queue_form":
		comment_form("queue_form",$_REQUEST['id']);
		break;

	case "update_ip":
		ip_update();
		break;

	case "update":
		update();
		break;

	case "view":
		view();
		break;

	default:
		index_display();
		break;
}

//Include the footer
include "templates/footer.php";

if ($connected)
{
	mysql_close($connected);
}
?>
