<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/*
*********************************************
*   eCard Application Info                  *
*********************************************
*/

$sitePath = "http://www.hostname.com/gcards"; // needed to create url for eCard pickup.  Please leave off trailing slash.
$siteName = "Graphics by Greg gCards"; // name shown in upper left corner of site and in emails
$siteEmail = "ecards@hostname.com";  // 'From' email address shown in notification email
$deleteDays = 30;  // Delete sent cards after this number of days.  Set to 0 to not delete cards
$showLoginLink = 'yes'; // Whether or not to show the 'login' link in the upper right.  Valid options are 'yes' and 'no'.  Access this page at login.php if you have link disabled

/*
*********************************************
*   Database Properties                     *
*********************************************
*/

$dbhost = 'localhost';	// database host
$dbuser = 'test';		// database user name
$dbpass = 'test';		// database user password
$dbdatabase = 'gcards';	// database with eCards tables
$tablePrefix = 'gc_';		// prefix of tables created/used by gCards

/*
*********************************************
*   Card Display Options                    *
*********************************************
*/

$cardsPerRow = 3;  // number of cards shown per row on the index.php page
$rowsPerPage = 2;  // number of rows of cards per page on the index.php page
$orderPop = 'yes';  // whether or not to show the most popular cards (those sent most) first.  Takes precedence over the $order option below.
$order = 'ASC';		// 'ASC' or 'DESC' - set DESC to show most recently added cards first
$dropShadow = 'yes'; // whether to show cards with a drop shadow or a white background.  'yes' for drop shadow, any other value for white background
$stampImage = 'classic.gif';  // stamp image to show when dropShadow = 'yes'.  Use one of the included ones or create your own.

/*
*********************************************
*   Email Text                              *
*********************************************
*/
$subject = 'eCard from $from_name!';  // subject of email sent when a person receives an eCard - $from_name gets replaced with the senders name
$message =	'$from_name has sent you an eCard!\r\nYou can pick it up at the following address:\r\n\r\n$sitePath/getcard.php?cardid=$cardid';  // the message sent to someone who receives an eCard.  

/*
*********************************************
*   Card Options                              *
*********************************************
*/

$imageLibrary = 'GD'; // Choose GD or GD2 - use GD2 if it is available
$maxFileSize = 250; // Maximum image size allowed in upload (measured in KiloBytes)
$resize_height = 100; // Height of generated thumbnails
$imagequality = 95; // Quality of thumbnails.  Range 0-100

/*
*********************************************
*   News Options                            *
*********************************************
*/

$enableNews = 'yes';  		// whether or not to show 'news' on index.php
$newsLocation = 'bottom';	// whether news shows up on the 'right' or the 'bottom' of index.php
$newsTitle = 'Site News'; 	// title for news section
$newsLimit = 2;				// number of news stories to show on index.php
$summaryLength = 350;		// number of characters to show for each news item on index.php - news items with more characters will have a link to another page
$dateFormat ="F j, Y g:i a";// date format for news - see the following url for date format values: http://www.php.net/manual/en/function.date.php

/*
*********************************************
*   Statistics                              *
*********************************************
*/

$stats_unique_index_hits_enabled = true;	// track number of users who come to the index.php page (1 per browser session)
$stats_pickupcard_hits_enabled = true;	// track number of cards that are picked up (getcard.php)


/*
*********************************************
*   Language Options                        *
*********************************************
*/
$defaultLang = 'en';
$lang['en'] = array (
						'file' => "language_en.php",
						'desc' => "English",
						'flag' => "en.gif",
					);
					
/*
*******************************************************
***  Example of adding another language file - add  ***
***  file to inc/lang directory, add image to       ***
***  images/SiteImages/flags directory              ***
*******************************************************
*$lang['fr'] = array (
*						'file' => "language_fr.php",
*						'desc' => "French",
*						'flag' => "fr.gif",
*					);					
********************************************************
*/

$gCardsVersion = '1.41';		// please don't change this
?>
