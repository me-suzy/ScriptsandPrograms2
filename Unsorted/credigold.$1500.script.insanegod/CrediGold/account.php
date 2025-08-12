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

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "account" => "Credigold_Account"));



if (get_param("cmd") == "logout") $auth->logout_success();

initPage("",3);



switch(get_param("cmd")){

	case "edit":

		$account->edit_account();

	break;

	case "lock":

		$account->lock_account();

	break;

	case "new_password":

		$auth->locked_account_warning();

		$account->new_password();

	break;

	case "ip_blocking":

		$auth->locked_account_warning();

		$account->ip_blocking();

	break;

	default:

		$account->edit_account();

	break;

}



endPage();

page_close();

exit;

?>