<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: config.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  System configuration
// ----------------------------------------------------------------------

define("WEBMASTER", "khaled@al-shamaa.com");
define("DEFAUL_LANG", "en");

// Define the active language in the system
// Note: You should have a language file in the language directory has the same name of this hash id
$activeLang['ar']="cmsimages/arabic.gif";
$activeLang['en']="cmsimages/english.gif";

// Restrict or grant access to the admin tool based upon the clients IP address
// 0 to unactivate it or IP address to activate this feature
$admin_ip = 0;

// Search mode 0 used LIKE SQL operator
// Search mode 1 used FullText MySQL function + Arabic lex's
// Note: you have to set FullText index for both title, content in pages table manualy (ADODB can't do that)
$search_mode = 0;

// Define the default status of the new added pages
// 0 tobe hidden by default, and 1 to be visible
$default_page = 1;

// Use SSL layer for admin pages [0,1]
$useSSL = 0;

// Define the maximum size of uploaded files Throw the system (in bytes)
// Note: You may need to edit related configuration in both php.ini and Apache web server
$maxUpload = 1000000;

// View pages counter to all visitors [0,1]
$viewCounter = 1;

// View sub pages links in the home page also [0 hide those links in homepage, 1 view those links in homepage]
$homeLinks = 1;

// Set 1 to activate shopping cart, 0 to deactivate it.
$cart_mode = 1;
// List of countries you are able to shipping your good to
$shipping_country = "Syria, Lebanon";

// =============== CashU Configuration ====================
// Set 1 to activate CashU payment method, 0 to ignore it
$cashu = 1;
// Your CashU merchant id
$merchant_id = "test";
// Set 1 to test CashU system, 0 to use real merchant id
$cashu_testmode = 1;

// =============== PayPal Configuration ====================
// Set 1 to activate PayPal payment method, 0 to ignore it
$paypal = 1;
// Primary Account email address (PayPal.com)
$business_id = "khaled@paypal.com";

$view_last_update_at = 1;
$view_last_update_by = 1;
$view_date_format = "d/m/Y";

?>
