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

GLOBAL $a_tile_width,
       $admin_page,
       $dbh,
       $language,
       $page,
       $theme,
       $this_admin,
       $this_user,
       $standard_url,
       $submitted,
       $this_date_display;

       include_once("include/functions.inc.php");
       session_unregister('this_user');
       if (!testlogin()||!$this_admin||$this_user)  { if ($op!="exp_batch") { Header("Location: http://$standard_url?op=logout"); exit; } }
       $page = $admin_page;
       $include_case_path = "include/cases";
       if (isset($db_table)) include("include/db_attributes.inc.php");

switch ($op) {
        case change_pw:              if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case change_pw_response:     if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case clear_batch:            if ($this_admin[admin_level]>=7) { if ($tier2) { include("$include_case_path/admin.$op.case.inc.php"); } } else { deny_access(); } break;
        case client_details:         if ($this_admin[admin_level]>=5) { include("include/html/$op.inc.php"); } else { deny_access(); } break;
        case client_invoice:         if ($this_admin[admin_level]>=5) { include("include/html/$op.inc.php"); } else { deny_access(); } break;
        case client_login:           if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case client_package:         if ($this_admin[admin_level]>=7) { include("include/html/$op.inc.php"); } else { deny_access(); } break;
        case delete:                 if ($this_admin[admin_level]>7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case delete_response:        if ($this_admin[admin_level]>7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case delete_whois:           if ($this_admin[admin_level]>7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case details:                if ($this_admin[admin_level]>=7) { $case = ($db_table=="client_invoice") ? "client_invoice" : "details" ; include("$include_case_path/admin.$case.case.inc.php"); } else { deny_access(); } break;
        case exp_batch:              if ($this_admin[admin_level]>=7) { if ($tier2) { include("$include_case_path/admin.$op.case.inc.php"); } } else { deny_access(); } break;
        case exp_data:               if ($this_admin[admin_level]>=7) { if ($tier2) { include("$include_case_path/admin.$op.case.inc.php"); } } else { deny_access(); } break;
        case form:                   if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case form_response:          if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case gen_batch:              if ($this_admin[admin_level]>=7) { if ($tier2) { include("$include_case_path/admin.$op.case.inc.php"); } } else { deny_access(); } break;
        case gen_inv:                if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case insert_theme:           if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case insert_vortech:         if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case pay_invoice:            if ($this_admin[admin_level]>=7) { if ( ( $authnet_enabled || $echo_enabled ) && $tier2) { include("$include_case_path/user.$op.case.inc.php"); } } else { deny_access(); } break;
        case pay_invoice_response:   if ($this_admin[admin_level]>=7) { if ( ( $authnet_enabled || $echo_enabled ) && $tier2) { include("$include_case_path/user.$op.case.inc.php"); } } else { deny_access(); } break;
        case quick_encrypt:          if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case quick_encrypt_response: if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case quick_status_update:    if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case reports:                if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case run_batch:              if ($this_admin[admin_level]>=7) { if ($tier2) { include("$include_case_path/admin.$op.case.inc.php"); } } else { deny_access(); } break;
        case shortcuts:              if ($this_admin[admin_level]>=7) { include("include/html/email_shortcuts.inc.php"); } else { deny_access(); } break;
        case sqlwiz:                 if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case update_cc:              if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case update_cc_response:     if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case view:                   if ($this_admin[admin_level]>=5) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case view_cc:                if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        case view_cc_response:       if ($this_admin[admin_level]>=7) { include("$include_case_path/admin.$op.case.inc.php"); } else { deny_access(); } break;
        default:                     if ($this_admin[admin_level]>=5) { include("include/html/admin_menu.inc.php"); } else { deny_access(); } break;
}
?>