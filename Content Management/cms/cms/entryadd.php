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
// Filename: entryadd.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Insert user inputs to the interactive table
// ----------------------------------------------------------------------
?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if (isset($_POST['submit'])){
    // get the form values
    if(is_numeric($_POST["x_mode"])){ $x_mode = @$_POST["x_mode"]; }else{ header("Location: page-1.html"); }
    if(is_numeric($_POST["key"])){ $x_id = @$_POST["key"]; }else{ header("Location: page-1.html"); }
    $x_alias = htmlspecialchars(@$_POST["x_alias"]);
    $x_email = htmlspecialchars(@$_POST["x_email"]);
    $x_content = htmlspecialchars(@$_POST["x_content"]);

    // Clean XSS
    $x_alias = cleanXSS($x_alias);
    $x_email = cleanXSS($x_email);
    $x_content = cleanXSS($x_content);

    if($x_mode == 4){
      $message = "$x_alias,\n";

      $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_id . " AND `lang`='" . $lang . "'";
      $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
      if ($rs->RecordCount()  != 0 ) { $x_mode_ext = @$rs->fields["mode_ext"]; }else{ $x_mode_ext = ""; }

      $frmFields = split("\|",$x_mode_ext);
      $i = 0;
      foreach ($frmFields as $field) {
          if($field != '') $message .= "$field: " . htmlspecialchars(@$_POST["frmEmail$i"]) . "\n";
          $i++;
      }

      $message .= "\n$x_content";

      // Email the post
      mail(WEBMASTER, SITE_TITLE, $message,"From: $x_email");
    }else{
      // add the values into an array

      // Get record ID
      $fieldList["`page_id`"] = $x_id;

      // phone number
      $theValue = $x_alias;
      $theValue = ($theValue != "") ? " '" . $theValue . "'" : "' '";
      $fieldList["`alias`"] = $theValue;

      // shipping name
      $theValue = $x_email;
      $theValue = ($theValue != "") ? " '" . $theValue . "'" : "' '";
      $fieldList["`email`"] = $theValue;

      // shipping address
      $theValue = $x_content;
      $theValue = ($theValue != "") ? " '" . $theValue . "'" : "' '";
      $fieldList["`content`"] = $theValue;

      // insert into database
      $strsql = "INSERT INTO `interactive` (";
      $strsql .= implode(",", array_keys($fieldList));
      $strsql .= ") VALUES (";
      $strsql .= implode(",", array_values($fieldList));
      $strsql .= ")";
      $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    }
    header("Location:page-".$x_id.".html");

}else{
    header("Location:page-1.html");
}

function cleanXSS($strInput){
    // One of the easiest way to do XSS is to use one of the on* attributes, like onclick or onload.
    // With this you can easily execute a script, without the user even having to do something (with onload, etc)
    // or just having to click or hover over something. We just remove them all with
    $strInput = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU',"$1>",$strInput);

    // As you certainly know, can you use javascript: and vbscript: as protocol handlers instead of http:// and others.
    // Something like <a href="javascript:alert('foobar')">lll</a> executes just nicely if a user clicks on it.
    // We of course remove that as well. IE as also the strange behaviour that something like "java script :" is also valid,
    // so we have to check for a whitespace between every character.
    $strInput = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2nojavascript...',$strInput);
    $strInput = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2novbscript...',$strInput);

    // We removed all namespace declarations above, here we remove all elements, which have a prefix, they are not needed in HTML..
    $strInput = preg_replace('#</*\w+:\w[^>]*>#i',"",$strInput);

    // There are quite some elements in HTML, which you definitively don't want in something like user comments.
    // The reason for the while loop is, that stuff like
    // <sc<script>ript>alert('hello')</sc</script>ript>
    // We remove them with:
    do {
       $oldstring = $strInput;
       $strInput = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$strInput);
    } while ($oldstring != $strInput);

    return $strInput;
}
?>
