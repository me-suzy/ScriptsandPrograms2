<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

GLOBAL $debug,
       $authnet_enabled,
       $isloggedin,
       $login_error,
       $this_admin,
       $this_user,
       $https,
       $page,
       $index_page,
       $dbh;

include_once("include/functions.inc.php");

$page = $index_page;
if (!$dbh) dbconnect();

## BEGIN LOGIN DISPATCH
switch ($op) {

   ## LOGOUT AND DESTROY ALL SESSION VARIABLES
   case logout:
        if (session_unset()) { session_destroy(); }
        Header("Location: http://$standard_url"."$index_page");
        break;

   ## PASSWORD REMINDER FORM
   case reminder:
        if ( isset($email) && $email != "") {
          $email     = strip_tags(strtolower(trim($email)));
          $client_id = mysql_one_data("SELECT client_id FROM client_info WHERE client_email = \"$email\"");
          if ($client_id) {
            reset_password($client_id);
            $response = 2;
          } else {
            $response = 1;
          }
        } else {
          $response = NULL;
        }
        display_reminder($response);
        break;

   ## VALIDATE LOGIN ATTEMPT AND DISPATCH TO ADMIN or USER INTERFACE
   case login:
        if (login(strip_tags($username),strip_tags($password))) {
            if ($this_admin) {
               session_unregister('this_user');
               Header("Location: $https://$secure_url"."$admin_page?".session_id());
            } elseif ($this_user) {
               session_unregister('this_admin');
               Header("Location: $https://$secure_url"."$user_page?".session_id());
            } else {
               if (session_unset()) session_destroy();
               $login_error = TRUE;
               display_login();
            }
        } else {
            $login_error = TRUE;
            display_login();
        }
        break;

   ## VALIDATE USER AND DISPATCH TO ADMIN or USER INTERFACE
   default:
        if  (testlogin($isloggedin)) {
            if ($this_admin) {
               session_unregister('this_user');
               Header("Location: $https://$secure_url"."$admin_page?".session_id());
            } elseif ($this_user) {
               session_unregister('this_admin');
               Header("Location: $https://$secure_url"."$user_page?".session_id());
            } else {
               if (session_unset()) session_destroy();
               display_login();
            }
        } else {
            display_login();
        }
        break;

}
?>