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
       $dbh,
       $language,
       $page,
       $standard_url,
       $theme,
       $this_admin,
       $this_user,
       $u_tile_width,
       $user_page;

       include_once("include/functions.inc.php");
       if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }
       $page = $user_page;
       $include_case_path = "include/cases";
       if (isset($db_table)) include("include/db_attributes.inc.php");

switch ($op) {
        case client_invoice:       include("$include_case_path/user.$op.case.inc.php"); break;
        case change_pw:            include("$include_case_path/user.$op.case.inc.php"); break;
        case change_pw_response:   include("$include_case_path/user.$op.case.inc.php"); break;
        case details:              include("$include_case_path/user.$op.case.inc.php"); break;
        case faq:                  include("$include_case_path/user.$op.case.inc.php"); break;
        case form:                 include("$include_case_path/user.$op.case.inc.php"); break;
        case form_response:        include("$include_case_path/user.$op.case.inc.php"); break;
        case pay_invoice:          if ( ( $authnet_enabled || $echo_enabled ) && $tier2) { include("$include_case_path/user.$op.case.inc.php"); } break;
        case pay_invoice_response: if ( ( $authnet_enabled || $echo_enabled ) && $tier2) { include("$include_case_path/user.$op.case.inc.php"); } break;
        case support:              include("$include_case_path/user.$op.case.inc.php"); break;
        case update_cc:            include("$include_case_path/user.$op.case.inc.php"); break;
        case update_cc_response:   include("$include_case_path/user.$op.case.inc.php"); break;
        case view:                 include("$include_case_path/user.$op.case.inc.php"); break;
        default:                   include("include/html/user_menu.inc.php"); break;
}
?>