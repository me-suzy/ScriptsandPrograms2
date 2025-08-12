<?php

/*----------------[			crediGold Account Functions	      ]---------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 14.5.2002                                                          */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "perm" => "Credigold_Perm", "account" => "Credigold_Account"));



// Withdrawals Comment Handle //

if (get_param("cmd") == "withdraw" && get_param("action") == "comment")

	{

		if (get_param("sent"))

			{

				$dc->query("UPDATE ".$_Config["database_withdrawals"]." SET comment='".get_param("comment")."' WHERE id='".get_param("id")."';");

				include("ihtml/withdraw_comment_success.ihtml");

				exit;

			}

		else

			{

				$dc->query("SELECT comment FROM ".$_Config["database_withdrawals"]." WHERE id='".get_param("id")."';");

				$dc->next_record();

				$currentComment = $dc->get("comment");

				include("ihtml/withdraw_comment.ihtml");

				exit;

			}

	}

// Withdrawals Comment Handle //



if (get_param("cmd") == "logout") $auth->logout_success();

initPage();

$perm->check("admin");



switch(get_param("cmd")){

	case "profiles":

		$auth->locked_account_warning();

		$account->view_profile();

	break;

	case "credigold":

		$auth->locked_account_warning();

		include("ihtml/index_admin.ihtml");

	break;

	case "load":

		$auth->locked_account_warning();

		include("ihtml/credigold_loader.ihtml");

	break;

	case "newsletters":

		$auth->locked_account_warning();

		include("ihtml/newsletters.ihtml");

	break;

	case "faq":

		$auth->locked_account_warning();

		include("ihtml/faq_admin.ihtml");

	break;

	case "history":

		$auth->locked_account_warning();

		include("ihtml/history.ihtml");

	break;

	case "ip_blocking":

		$auth->locked_account_warning();

		$account->global_ip_blocking();

	break;

	case "pages":

		$auth->locked_account_warning();

		$account->pages_editor();

	break;

	case "emails":

		$auth->locked_account_warning();

		$account->emails_editor();

	break;

	case "meta":

		$auth->locked_account_warning();

		$account->meta_editor();

	break;

	case "banners":

		$auth->locked_account_warning();

		$account->banner_manager();

	break;

	case "backup":

		$auth->locked_account_warning();

		$account->site_backup();

	break;

	case "online":

		$auth->locked_account_warning();

		$account->people_online();

	break;

	case "calendar":

		$auth->locked_account_warning();

		$account->calendar();

	break;

	case "fund":

		$auth->locked_account_warning();

		$account->fundAdmin();

	break;

	case "withdraw":

		$auth->locked_account_warning();

		$account->withdrawAdmin();

	break;

	default:

		$auth->locked_account_warning();

		include("ihtml/history.ihtml");

	break;

}



endPage();

page_close();

exit;

?>