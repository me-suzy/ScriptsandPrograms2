<?php

/******************************************************************************
File Name    : login.php
Description  : controls all account and counter settings
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 13, 2004
Licence      : Freeware (GPL)
******************************************************************************/

$username = $_POST["usr"];
$password = $_POST["pwd"];
$newpassword = $_POST["newpassword"];
$err = $_GET["err"];
$act = $_GET["act"];
$sav = $_GET["sav"];
$btn = $_POST["btn"];
$siteid = $_POST["siteid"];
$sessionid = $_GET["sessionid"];
$newwebsitetitle  =  $_POST["newwebsitetitle"];
$newwebsite  =  $_POST["newwebsite"];
$newstartcount  =  $_POST["newstartcount"];
$newstyle  =  $_POST["newstyle"];
$newaddtowslst  =  $_POST["newaddtowslst"];
$name = $_POST["name"];
$email = $_POST["email"];

// remove characters that will interfere
$username = str_replace("{", "", $username);
$username = str_replace("}", "", $username);
$password = str_replace("{", "", $password);
$password = str_replace("}", "", $password);
$newpassword = str_replace("{", "", $newpassword);
$newpassword = str_replace("}", "", $newpassword);
$newwebsitetitle = str_replace("{", "", $newwebsitetitle);
$newwebsitetitle = str_replace("}", "", $newwebsitetitle);
$newwebsite = str_replace("{", "", $newwebsite);
$newwebsite = str_replace("}", "", $newwebsite);
$newstartcount = str_replace("{", "", $newstartcount);
$newstartcount = str_replace("}", "", $newstartcount);
$newstyle = str_replace("{", "", $newstyle);
$newstyle = str_replace("}", "", $newstyle);
$name = str_replace("{", "", $name);
$name = str_replace("}", "", $name);
$email = str_replace("{", "", $email);
$email = str_replace("}", "", $email);

switch ($sav) {
        case "edita": savedita(); continue;
        case "newc": savnewc(); continue;
        case "stats": viewstats(); continue;
        case "resetstats": resetstats(); continue;
        case "ecntr": if ($btn=="Edit") {
                saveditc(); continue;
              } elseif ($btn=="Delete") {
                savdeletec(0); continue;
              }
        default: continue;
}

switch ($act) {
        case "home": home(); continue;
        case "login": login(); continue;
        case "logout": logout(); continue;
        case "stats": stats(); continue;
        case "resetstats": resetstatsf(); continue;
        case "edita": edita(); continue;
        case "ecntr": if ($btn=="Edit") {
                        editc(); continue;
                      } elseif ($btn=="Delete") {
                        deletec(); continue;
                      } elseif ($btn=="HTML Code") {
                        htmlcode(); continue;
                      }
        case "newc": newc(); continue;
        default: loginscr($err); continue;
}

function loginscr($err) {
        include("config.php"); include("incl/header.inc");

        // print login forms
        include("incl/login.inc");
        // footer
        include("incl/footer.inc");
}

function home() {
        global $sessionid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

        // rip siteids apart and fill the select box
        $siteid = split("!", $fsiteid);
        $num_siteids = count($siteid);

        // determine counters left on account
        if ($maxcounters!=0) { $maxcounters = $maxcounters+1; }
        $countersleft = $maxcounters-$num_siteids;

        // print body & footer
        include("incl/login-home.inc");
        include("incl/footer.inc");
}

function login() {
        global $username; global $password;
        include("config.php");

        $userfile = "userdb/".$username.".db";
        if (!file_exists($userfile)) { header("location: login.php?err=2"); }
        // read user file and set varables
        $fp = fopen($userfile,"r");
        $fdata = fgets($fp, filesize($userfile)+1);
        $fdata = split("{}", $fdata);
        $fusername = str_replace("u:", "", $fdata[0]);  // username in userdb
        $fpassword = str_replace("p:", "", $fdata[1]);  // password in userdb
        $fname = str_replace("n:", "", $fdata[2]);      // name in userdb
        $femail = str_replace("e:", "", $fdata[3]);     // email in userdb
        $fsiteid = str_replace("i:", "", $fdata[4]);    // siteid(s) in userdb
        fclose($fp);

        // encrypt input password
        $old_time = substr($fpassword, 0, 8);
        $to_crypt = $old_time.$password;
        $crypted2 = md5($to_crypt);
        $crypted2 = $old_time.$crypted2;

        // match encrypted input pass with encrypted pass in user file
        if ($fpassword==$crypted2) {
                // passed
                // generating session id
                $sid = uniqid(date("s"));
                $ip = $_SERVER["REMOTE_ADDR"];

                // write to sid file
                $cpass = md5($adminpass);
		$cpass = substr($cpass, strlen($cpass)-6, 4);
                $sidw = fopen("sidtemp/".$sid.".".$cpass.".sid","w");
                // writing the ip address will prevent someone else from
                // using your session id.
                $siddata = fputs($sidw,$ip."!".$username."!".$password);
                fclose($sidw);

                // navigate to Account Control Panel
                header("location: login.php?act=home&sessionid=$sid");
                exit;
        } else {
                // failed - password does not match
                header("location: login.php?err=2");
                exit;
        }
}

// display stats form
function stats() {
        global $sessionid;
        include("config.php"); include("incl/filevars.php");

        $siteid = split("!", $fsiteid);
        $num_siteids = count($siteid)-1;

        // if there is only 1 counter - skip to viewstats
        if ($num_siteids==1) {
                header("location: login.php?sav=stats&siteid=$siteid[1]&sessionid=$sessionid");
                exit;
        } elseif ($num_siteids <= 0) {
                include("incl/header.inc");
                print "<h1>Statistics - Error</h1><b>You must create a counter first.</b><br><br><div align=\"center\">[<a href=\"login.php?act=newc&sessionid=$sessionid\">Create a New Counter</a>]<br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }

        include("incl/header.inc");
        include("incl/login-stats.inc");
        include("incl/footer.inc");
}

// display reset stats form
function resetstatsf() {
        global $sessionid;
        $siteid = $_GET["siteid"];
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

        $counterfile = "countdb/".$siteid.".db";
        if (!file_exists($counterfile)) {
                print "<h1>Statistics - Error</h1><b>The site ID does not exists.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }
        include("incl/login-resetstats.inc");
        include("incl/footer.inc");
}


// logout user
function logout() {
        global $sessionid;
        include("config.php"); include("incl/filevars.php");

        // delete session id file
        $cpass = md5($adminpass);
	$cpass = substr($cpass, strlen($cpass)-6, 4);
        unlink("sidtemp/".$sessionid.".".$cpass.".sid");
        // navigate user home
        header("location: index.php");
}

//display edit account form
function edita() {
        global $sessionid;
        include("config.php"); include("incl/filevars.php");

        include("incl/header.inc");
        include("incl/login-edita.inc");
        include("incl/footer.inc");
}

// display edit counter form
function editc() {
        global $sessionid; global $siteid;
        include("config.php"); include("incl/header.inc");

        // read counter file settings
        $counterfile = "countdb/".$siteid.".db";
        if (!file_exists($counterfile)) {
                print "<h1>Edit Counter - Error</h1><b>The site ID does not exists.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }
        $fp = fopen($counterfile,"r");
        $fdata = fgets($fp,1024);
        $fdata = split("{}", $fdata);
        $owner = str_replace("owner:", "", $fdata[0]);          // owners name
        $email = str_replace("email:", "", $fdata[1]);          // owners email
        $created = str_replace("created:", "", $fdata[2]);      // counter creation date
        $url = str_replace("url:", "", $fdata[3]);              // web site url
        $count = str_replace("count:", "", $fdata[4]);          // current count
        $style = str_replace("style:", "", $fdata[5]);          // counter style
        fclose($fp);

        // print body and footer
        include("incl/login-editc.inc");
        include("incl/footer.inc");
}

// display delete counter confirm
function deletec() {
        global $sessionid; global $siteid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

        $counterfile = "countdb/".$siteid.".db";
        if (!file_exists($counterfile)) {
                print "<h1>Delete Counter - Error</h1><b>The site ID does not exists. <i>".$siteid."</i> was removed from your counter list.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                // only delete site ID from counter list
                savdeletec(1);
                exit;
        }

        include("incl/login-deletec.inc");
        include("incl/footer.inc");
}

// display html code for counter
function htmlcode() {
        global $sessionid; global $siteid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

	$counterfile = "countdb/".$siteid.".db";
        if (!file_exists($counterfile)) {
                print "<h1>HTML Code - Error</h1><b>The site ID does not exists.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }

        include("incl/login-htmlcode.inc");
        include("incl/footer.inc");
}

//  display new counter form
function newc() {
        global $sessionid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

        // prevent user from exceding his/her max counters
        $siteid = split("!", $fsiteid);
        $num_siteids = count($siteid);
        // determine counters left on account
        $maxcounters = $maxcounters+1;
        $countersleft = $maxcounters-$num_siteids;
        if ($maxcounters <= $num_siteids) {
                print "<h1>Create New Counter - Error</h1><b>You have reached the maximum counter limit.</b><br><p>What can I do?<ul><li>Delete unused counters at your Account Control Panel</li><li>Contact the admin and request he/she increase the maximum counter limit</li></ul></p><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }

        include("incl/login-newc.inc");
        include("incl/footer.inc");
}

// do edit account action
function savedita() {
        global $sessionid; global $username; global $password; global $newpassword;
        global $name; global $email;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");
        
        // stop someone on the demo account from changing settings
        if ($username=="demo") {
        	print "<h1>Edit Account Settings - Error</h1><b>You can not change settings on the demo account.</b><br><br><div align=\"center\"><input type=\"button\" class=\"button\" onclick=\"javascript:window.location='setup.php'\" value=\"I'm convinced, Sign me up!\"><br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        	include("incl/footer.inc");
        	exit;
        }

        // encrypt new password
        $time = localtime();
        $new_time = $time[2].$time[1].$time[0];
        $b_time = base64_encode($new_time);
        $to_crypt = $b_time.$newpassword;
        $crypted = md5($to_crypt);
        $password = $b_time.$crypted;

        // write new account settings to user file
        $userfile = "userdb/".$username.".db";
        if (!file_exists($userfile)) { header("location: login.php?err=2"); }
        $userdata = "u:".$fusername."{}p:".$password."{}n:".$name."{}e:".$email."{}i:".$fsiteid;
        $ud = fopen($userfile,"w");
        $usernew = fputs($ud,$userdata);
        fclose($ud);

        // write password to temp. session id file
        $cpass = md5($adminpass);
	$cpass = substr($cpass, strlen($cpass)-6, 4);
        $sidfile = "sidtemp/".$sessionid.".".$cpass.".sid";
        if (!file_exists($sidfile)) { header("location: login.php?err=2"); }
        $siddata = $ip."!".$username."!".$newpassword;
        $wsid = fopen($sidfile,"w");
        $sidn = fputs($wsid,$siddata);
        fclose($wsid);

        print "<h1>Edit Account Settings</h1><b>Your account settings have been saved!</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        include("incl/footer.inc");
}

// do make new counter action
function savnewc() {
        global $sessionid; global $username;
        global $password; global $newwebsite; global $newwebsitetitle;
        global $newstartcount; global $newstyle; global $newaddtowslst;

        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");

        // stop someone on the demo account from changing settings
        if ($username=="demo") {
        	print "<h1>Create New Counter - Error</h1><b>You can not create new counters on the demo account.</b><br><br><div align=\"center\"><input type=\"button\" class=\"button\" onclick=\"javascript:window.location='setup.php'\" value=\"I'm convinced, Sign me up!\"><br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        	include("incl/footer.inc");
        	exit;
        }

        // prevent user from exceding his/her max counters
        $siteid = split("!", $fsiteid);
        $num_siteids = count($siteid);
        // determine counters left on account
        $maxcounters = $maxcounters+1;
        if ($maxcounters <= $num_siteids) {
                print "<h1>Create New Counter - Error</h1><b>You have reached the maximum counter limit.</b><br><p>What can I do?<ul><li>Delete unused counters at your Account Control Panel</li><li>Contact the admin and request he/she increase the maximum counter limit</li></ul></p><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                include("incl/footer.inc");
                exit;
        }

        settype($newstartcount,"integer");
        if ((strlen($newwebsite) < 5) || (!$newwebsitetitle) || ($newstartcount < 0)) {
                print "<h1>Create New Counter - Error</h1><b>Missing or invalid information.</b><br><br><div align=\"center\">[<a href=\"javascript:history.go(-1)\">Back</a>]</div>";
                include("incl/footer.inc");
                exit;
        }

        // generate new counter id
        $length = 10;
        $code = md5(date("s"));
        srand((double) microtime()*1000000);
        $sl = strlen($code);
        $bp = rand(0,($sl-$length-1));

        // read prev id
        $fp = fopen("previd.db","r");
        $count = fgets($fp, 1024);
        fclose($fp);
        // write new prev id
        $fw = fopen("previd.db","w");
        $previd = $count + 1;
        $countnew = fputs($fw,$count + 1);
        fclose($fw);

        $newsiteid = substr($code, $bp, $length).$prevId;
        $counterfile = "countdb/".$newsiteid.".db";
        $userfile = "userdb/".$username.".db";
        $counter_file = "countdb/".$newsiteid."-c.db";
        $log_file = "countdb/".$newsiteid."-l.db";
        $today_file = "countdb/".$newsiteid."-t.db";

        $fp = fopen($counter_file,"w"); $fdata = fwrite($fp,""); fclose($fp);
        $fp = fopen($log_file,"w"); $fdata = fwrite($fp,""); fclose($fp);
        $fp = fopen($today_file,"w"); $fdata = fwrite($fp,"0"); fclose($fp);

        if (file_exists($counterfile)) {
                print "<h1>Create New Counter - Error</h1><b>An internal script error has occurred. Click \"Refresh\" button.</b>";
                include("incl/footer.inc");
                exit;
        }

        // create new user & counter file
        $date = date("F d, Y");
        $countdata = "owner:".$fname."{}email:".$femail."{}created:".$date."{}url:".$newwebsite ."{}count:".$newstartcount."{}style:".$newstyle;
        $userdata = "u:".$username."{}p:".$fpassword."{}n:".$fname."{}e:".$femail."{}i:".$fsiteid."!".$newsiteid;
        $cd = fopen($counterfile,"w");
        $ud = fopen($userfile,"w");
        $countnew = fputs($cd,$countdata);
        $usernew = fputs($ud,$userdata);
        fclose($cd);
        fclose($ud);

        // add counter to beginning of the signup list
        if ($newaddtowslst=="on") {
                $slr = fopen("signup.log", "r");
                $fdata = fread($slr, filesize("signup.log"));
                $slw = fopen("signup.log", "w");
                $newent = fputs($slw, "<li><a href=\"$newwebsite\" target=\"_new\">[$newwebsitetitle] $newwebsite</a>&nbsp;&nbsp;[<a href=\"details.php?siteid=$newsiteid\">S</a>]</li>\n".$fdata);
                fclose($slw);
                fclose($slr);
        }

        // print body and footer
        include("incl/login-newcsav.inc");
        include("incl/footer.inc");
        exit;
}

// do edit counter action
function saveditc() {
        global $sessionid; global $username; global $password;
        global $newwebsite; global $newstartcount; global $newstyle; global $siteid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");
        
        // stop someone on the demo account from changing settings
        if ($username=="demo") {
        	print "<h1>Edit Counter - Error</h1><b>You can not change settings on the demo account.</b><br><br><div align=\"center\"><input type=\"button\" class=\"button\" onclick=\"javascript:window.location='setup.php'\" value=\"I'm convinced, Sign me up!\"><br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        	include("incl/footer.inc");
        	exit;
        }

        $date = date("F d, Y");
        $counterfile = "countdb/".$siteid.".db";

        // read previous counter settings
        $fp = fopen($counterfile,"r");
        $fdata = fgets($fp,1024);
        $fdata = split("{}", $fdata);
        $owner = str_replace("owner:", "", $fdata[0]);          // owners name
        $email = str_replace("email:", "", $fdata[1]);          // owners email
        $created = str_replace("created:", "", $fdata[2]);      // counter creation date
        fclose($fp);

        $fsiteid = split("!", $fsiteid);
        $num_fsiteid = count($fsiteid);
        // prevent some from deleting a counter they dont own
        for ($i = 0; $i < $num_fsiteid; ++$i) {
                if ($fsiteid[$i]==$siteid) {
                        // write to counter file with new settings
                        $countdata = "owner:".$owner."{}email:".$email."{}created:".$created."{}url:".$newwebsite ."{}count:".$newstartcount."{}style:".$newstyle;
                        $cd = fopen($counterfile,"w");
                        $countnew = fputs($cd,$countdata);
                        fclose($cd);
                        print "<h1>Edit Counter Details - Finished!</h1><b>Counter settings for <i>$siteid</i> have been saved!</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
                        include("incl/footer.inc");
                        exit;
                }
        }

        print "<h1>Edit Counter Details - Error</h1><b>The counter you attempted to edit does not belong to you.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        include("incl/footer.inc");
}

// do delete counter action
function savdeletec($fromlist) {
        global $sessionid; global $username; global $siteid;
        include("config.php"); include("incl/filevars.php");
        
        // stop someone on the demo account from changing settings
        if ($username=="demo") {
        	include("incl/header.inc");
        	print "<h1>Delete Counter - Error</h1><b>You can not delete counters on the demo account.</b><br><br><div align=\"center\"><input type=\"button\" class=\"button\" onclick=\"javascript:window.location='setup.php'\" value=\"I'm convinced, Sign me up!\"><br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        	include("incl/footer.inc");
        	exit;
        }

        $fsiteid = split("!", $fsiteid);
        $num_fsiteid = count($fsiteid);
        // prevent some from deleting a counter they dont own
        for ($i = 0; $i < $num_fsiteid; ++$i) {
                if ($fsiteid[$i]==$siteid) {
                        $counterfile = "countdb/".$siteid.".db";
                        $userfile = "userdb/".$username.".db";
                        $counter_file = "countdb/".$siteid."-c.db";
                        $log_file = "countdb/".$siteid."-l.db";
                        $today_file = "countdb/".$siteid."-t.db";

                        // read the user file
                        $ruf = fopen($userfile,"r");
                        $rufdata = fgets($ruf,1024);
                        fclose($ruf);

                        // remove the site ID from the user file
                        $rufdata = str_replace("!".$siteid, "", $rufdata);
                        // delete all files related to the counter
                        if (!$fromlist==1) {
                                unlink($counterfile);
                                unlink($log_file);
                                unlink($counter_file);
                                unlink($today_file);
                        }

                        // write the user file without the deleted site ID
                        $wuf = fopen($userfile,"w");
                        $wufdata = fputs($wuf,$rufdata);
                        fclose($wuf);

                        if (!$fromlist) { header("location: login.php?act=home&sessionid=$sessionid"); }
                        exit;
                }
        }

        include("incl/header.inc");
        print "<h1>Delete Counter - Error</h1><b>The counter you attempted to delete does not belong to you.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        include("incl/footer.inc");
        exit;
}

// print stats
function viewstats() {
        global $sessionid; global $username; global $siteid;
        include("config.php"); include("incl/filevars.php"); include("incl/header.inc");
        if (!isset($siteid)) { $siteid=$_GET["siteid"]; }

        $lstsiteid = split("!", $fsiteid);
        $num_lstsiteids = count($lstsiteid)-1;

        $counter_file = "countdb/".$siteid."-c.db";
        $log_file = "countdb/".$siteid."-l.db";
        $today_file = "countdb/".$siteid."-t.db";

        $latest_max = 30;
        $lines = file($log_file);
        $a = count($lines) - 1;
        $u = $a-$latest_max;
        $today = date("F d, Y");
        $unique = file($counter_file);

        $tf = fopen($today_file, "r");
        $th = fread($tf, filesize($today_file));
        if (!$th) { $th = 0; }
        fclose($tf);

        $cf = fopen($counter_file, "r");
        $counterdata = fread($cf, filesize($counter_file));
        $counterdata = split("!", $counterdata);
        if (($counterdata[2]==$today) && ($unique[0])) {
                $uh = count($unique);
                $yesterday = "";
        } else {
                $num_unique = count($unique);
                if ($unique[0]) {
                        $yesterday = "   <tr><td width=\"33%\" bgcolor=\"#CFE820\" align=\"center\">$counterdata[2]</td><td width=\"33%\">$num_unique</td><td width=\"33%\">$th</td></tr>\n";
                }
                $uh = 0; $th = 0;
        }
        fclose($cf);

        // load the counter settings, all we need is
        // the date created and the count
        $counterfile = "countdb/".$siteid.".db";
        $fp = fopen($counterfile,"r");
        $fdata = fgets($fp,1024);
        $fdata = split("{}", $fdata);
        $created = str_replace("created:", "", $fdata[2]);      // counter creation date
        $url = str_replace("url:", "", $fdata[3]);              // web site counter is on
        $count = str_replace("count:", "", $fdata[4]);          // current count
        fclose($fp);

        $fsiteid = split("!", $fsiteid);
        $num_fsiteid = count($fsiteid);
        // prevent someone from viewing a counter they dont own
        for ($i = 0; $i < $num_fsiteid; ++$i) {
                if ($fsiteid[$i]==$siteid) {
                        include("incl/login-viewstats.inc");
                        include("incl/footer.inc");
                        exit;
                }
        }

        print "<h1>Statistics - Error</h1><b>You can not view counter statistics that don't belong to you.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        include("incl/footer.inc");
        exit;
}

// do reset action
function resetstats() {
        global $sessionid; global $siteid;
        include("config.php"); include("incl/filevars.php");
        
        // stop someone on the demo account from changing settings
        if ($username=="demo") {
        	include("incl/header.inc");
        	print "<h1>Statistics - Error</h1><b>You can not reset statistics on the demo account.</b><br><br><div align=\"center\"><input type=\"button\" class=\"button\" onclick=\"javascript:window.location='setup.php'\" value=\"I'm convinced, Sign me up!\"><br><br>[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        	include("incl/footer.inc");
        	exit;
        }

        $fsiteid = split("!", $fsiteid);
        $num_fsiteid = count($fsiteid);
        // prevent some from resetting a counter they dont own
        for ($i = 0; $i < $num_fsiteid; ++$i) {
                if ($fsiteid[$i]==$siteid) {
                        $log_file = "countdb/".$siteid."-l.db";
                        // reset the log file
                        $lfw = fopen($log_file,"w");
                        $lfwdata = fputs($lfw,"");
                        fclose($lfw);

                        // go home
                        header("location: login.php?act=home&sessionid=$sessionid");
                        exit;
                }
        }

        include("incl/header.inc");
        print "<h1>Statistics - Error</h1><b>The counter you attempted to reset does not belong to you.</b><br><br><div align=\"center\">[<a href=\"login.php?act=home&sessionid=$sessionid\">Back to Account Control Panel</a>]</div>";
        include("incl/footer.inc");
        exit;
}
?>