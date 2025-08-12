<?

/*----------------[			crediGold Merchant Main File	      ]------------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 5/14/2002                                                              */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session",  "user" => "Credigold_Users", "auth" => "Credigold_Merchant"));

initPage("",2);

switch(get_param("cmd"))

	{

		case "cart":

			$auth->locked_account_warning();

			include("ihtml/cart.ihtml"); 

		break;

		case "referrals":

			include("ihtml/referrals.ihtml"); 

		break;

		case "reoccur":

			$auth->locked_account_warning();

			include("ihtml/reoccuring.ihtml"); 

		break;

		default:

			$auth->locked_account_warning();

			include("ihtml/cart.ihtml"); 

	}

endPage();

page_close();

exit;

?>