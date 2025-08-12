<?php
/*
 * web2printer
 * Module     		   : web2printer_conf.php
 *
 * Version    		   : 3.1 ENTERPRISE
 * Reference Plattform: 4.0.6; 4.2.1
 *
 * Copyright (c) 1998 - 2003 pda-systems.COM. All rights reserved.
 * http://www.pda-systems.com
 *
 * This software is confidential and proprietary information of
 * pda-systems.COM ("Confidential Information"). You  shall not
 * disclose such Confidential Information and shall use it only
 * in accordance  with the  terms of the  license agreement you
 * entered into with pda-systems.COM.
 *
 * PDA-SYSTEMS.COM MAKES NO  REPRESENTATIONS OR WARRANTIES ABOUT THE
 * SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR IMPLIED, INCLUDING
 * BUT  NOT  LIMITED TO  THE  IMPLIED WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGMENT. 
 * PDA-SYSTEMS.COM SHALL NOT  BE LIABLE FOR ANY DAMAGES  SUFFERED BY 
 * LICENSEE AS A  RESULT OF  USING,  MODIFYING OR  DISTRIBUTING THIS 
 * SOFTWARE OR ITS DERIVATES.
 */

// page url header:
$pageUrlHeader = "<b>This page URL</b>:<br>";

// page url crossreference header:
$pageXRefURLHeader = "<b>Links:</b>";

// page images crossreference header:
$pageXRefImages = "<b>Images:</b>";

// active link
// 0 - hyperlinks not active (clickable) in the resulting html file
// 1 - hyperlinks active (clickable) in the resulting html file
$activeLinks = 0;

// 0 disable footer 
// 1 enable  footer 
// 2 use custom footer 
$footer = 1;
// custom footer text - if you not enjoy the standard footer
// this page was generated with ....
$customFooter = "";

// path to your e-mail template (absolute or relative to web2printer.php)
$myEMailForm    = "./form.html";

// 0 disable email logging
// 1 enable email loggin
$logmail		= 1;
// logfile path - put it outside your html path (ONLY PATH !!!)
$logfile	   = "/www/htdocs/htsuccess/stack/scriptlog";
// maximum number of mails a single (visitor)ip can send per day
// important to prevent abuse
$maxip	   = 10;
?>
