<?php

/*----------------[			crediGold Merchant Functions	      ]---------------*/

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



class Credigold_Merchant extends Credigold_Auth {



  var $classname      = "Credigold_Merchant";

  var $lifetime       =  15;



  var $magic          = "FlashGet_1.1";  ## Challenge seed

  var $database_class = "DB_Credigold";

  var $database_table = "user_auth";

  var $database_details = "user_details";



  var $mode = "log";



} // CrediGold_Merchant class ends here

?>

