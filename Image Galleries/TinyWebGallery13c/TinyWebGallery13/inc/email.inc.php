<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
function cryptEmail($email, $pos)
{
    global $encrypt_emails_key; 
    // we test if the crypt key is o.k.!
    global $encrypt_emails;

    if ($encrypt_emails) {
        for ($i = 0; $i < strlen($encrypt_emails_key); $i++) {
            if (ord($encrypt_emails_key{$i}) > 127) {
                if ($pos == 0) {
                    echo "The crypt key at position " . $i . " is not valid - please change the key in the configuration!";
                } 
                return $email;
            } 
        } 
        // we use every single char and add the corresponding char of the security string!
        // we could check if the website do exists!
        $pos = $pos * 20;
        $codedemail = "";
        $keylen = strlen($encrypt_emails_key);
        for ($i = 0; $i < strlen($email); $i++) {
            $codedemail .= chr(ord($email{$i}) + ord($encrypt_emails_key{  (($pos + $i) % $keylen) }));
        } 
        return $codedemail;
    } else {
        return $email;
    } 
} 

/*
the position is important . it tells which part of the crypt key is used!
*/
function decryptEmail($email , $pos)
{
    global $encrypt_emails_key;
    global $encrypt_emails;

    if ($encrypt_emails) {
        for ($i = 0; $i < strlen($encrypt_emails_key); $i++) {
            if (ord($encrypt_emails_key{$i}) > 127) {
                if ($pos == 0) {
                    echo "The crypt key at position " . $i . " is not valid - please change the key in the configuration!";
                } 
                return $email;
            } 
        } 
        $pos = $pos * 20;
        $codedemail = "";
        $keylen = strlen($encrypt_emails_key);
        for ($i = 0; $i < strlen($email); $i++) {
            $codedemail .= chr(ord($email{$i}) - ord($encrypt_emails_key{  (($pos + $i) % $keylen) }));
        } 
        return $codedemail;
    } else {
        return $email;
    } 
} 

function testEmailDomain($email)
{
    global $test_email_by_checking_url;

    if ($test_email_by_checking_url)
        $handle = @fopen ("http://www.google.de/", "r");
    if ($handle == false) {
        // no connection - we skip the real test
        return true;
    } else {
        // we do the test!
        $domain = "http://www." . substr(strstr($email, '@'), 1);
        $handle = @fopen ($domain, "r");
        if ($handle == false) {
            // no connection - we skip the real test
            return false;
        } 
    } 
    // email domain is good :)
    return true;
} 

?>