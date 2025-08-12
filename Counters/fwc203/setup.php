<?php

/******************************************************************************
File Name    : setup.php
Description  : creates a new user account
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 7, 2004
Licence      : Freeware (GPL)
******************************************************************************/

$step = $_GET["st"];

switch ($step){
        case "signup": signup();
        case "check": check(); continue;
        default: signup();
}

function isvalid($str) {
        $alpha = "abcdefghijklmnopqrstuvwxyz1234567890._";

        $str = str_replace("?", "/?", $str);
        $str = str_replace("+", "/+", $str);
        $str = str_replace("*", "/*", $str);
        $str = str_replace("$", "/$", $str);
        $str = str_replace("^", "", $str);
        $str = str_replace("(", "/(", $str);
        $str = str_replace(")", "/)", $str);
        $str = str_replace("[", "/[", $str);
        $str = str_replace("]", "/]", $str);
        $str = str_replace("\\", "", $str);
        $str = str_replace("\"", "/\"", $str);

        for ($i = 0; $i < strlen($str); ++$i) {
                $tmp = substr($str, $i, 1);
                if (!eregi($tmp,$alpha)) {
                        return 0;
                        exit;
                }
        }

        return 1;
}

function signup(){
        include("config.php");

        include("incl/header.inc");
        include("incl/setup-signup.inc");
        include("incl/footer.inc");
}

function check() {
        include("config.php"); include("incl/header.inc");

        $username = $_POST["usr"];
        $password = $_POST["pass"];
        $name = $_POST["name"];
        $email = $_POST["email"];

        $username = str_replace("{", "", $username);
        $username = str_replace("}", "", $username);
        $password = str_replace("{", "", $password);
        $password = str_replace("}", "", $password);
        $name = str_replace("{", "", $name);
        $name = str_replace("}", "", $name);
        $email = str_replace("{", "", $email);
        $email = str_replace("}", "", $email);

        // error - missing fields
        if ((!$username) || (!$name) || (!$email)) {
                $err = 1;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        // error - password len is to small or to long
        } elseif ((strlen($password) < 5) || (strlen($password) > 15)) {
                $err = 3;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        // error - invalid characters in username or to long
        } elseif ((isvalid($username)==0) || (strlen($username) > 15)) {
                $err = 4;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        // error - invalid characters in password
        } elseif (isvalid($password)==0) {
                $err = 3;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        }

        $userfile = "userdb/".$username.".db";
        // error - user already exists
        if (file_exists($userfile)) {
                $err = 2;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        }

        // a vaild email has a @ symbol and a period, lets see if we can find them
        $apos = strpos($email, "@");
        $ppos = strpos($email, ".");
        if (($apos==0) || ($ppos==0)) {
                $err = 5;
                include("incl/setup-signup.inc"); include("incl/footer.inc");
                exit;
        }

        // everything passed

        include("incl/setup-check.inc");

	// mail the user his/her username and password
	$mailbody = "Dear $name,\n\nI'm pleased to welcome you to $title! The details of your account are below.\n\nUsername: $username\nPassword: $password\n\nClick the link below to login and manage your counters.\n$linkpath/login.php\n\nNote: It is crucial that you remember your password. Forgotten passwords CANNOT be retrieved.\n\n".$mailfromname;
	mail($email, $mailsubject, $mailbody, "From: $mailfromaddr");

	// if set to 1 in config.php, inform the admin on a new signup
	if ($informadmin==1) {
		$ip = $_SERVER["REMOTE_ADDR"];
		mail($contactaddress, $title."-Signup", "$title - New Signup\n\nNew account details:\nName: $name\nE-mail: $email\nUsername: $username\nPassword: $password\nIP Address: $ip\nTime: ".date("l dS of F Y h:i:s A"), "From: $mailfromaddr");
	}

        // encrypt password
        $time = localtime();
        $new_time = $time[2].$time[1].$time[0];
        $b_time = base64_encode($new_time);
        $to_crypt = $b_time.$password;
        $crypted = md5($to_crypt);
        $password = $b_time.$crypted;

        // create new user file
        $userdata = "u:".$username."{}p:".$password."{}n:".$name."{}e:".$email."{}i:";
        $ud = fopen($userfile,"w");
        $usernew = fputs($ud,$userdata);
        fclose($ud);

        include("incl/footer.inc");
}
?>

