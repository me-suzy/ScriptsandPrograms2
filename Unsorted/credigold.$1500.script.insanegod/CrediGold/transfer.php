<?

/*----------------[			crediGold Transfer Main File	      ]---------------*/

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

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "transfer" => "Credigold_Transfer"));



initPage("",1);

switch(get_param("cmd")){

   case "send":

      $auth->locked_account_warning();

      $transfer->send_gold();

   break;

   case "request":

      $auth->locked_account_warning();

      $transfer->request_gold();

   break;

   case "fund":

      $auth->locked_account_warning();

      $transfer->fund();

   break;

   case "withdraw":

      $auth->locked_account_warning();

      $transfer->withdraw();

   break;

   case "history":

      $transfer->history();

   break;

   default:

      $transfer->send_balance();

}

endPage();

page_close();

exit;

?>