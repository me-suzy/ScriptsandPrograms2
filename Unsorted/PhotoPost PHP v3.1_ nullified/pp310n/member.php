<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.1                                            //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Goshik [WTN]                                   //
// Nullified By          : CyKuH [WTN]                                    //
//  This script is part of PhotoPost PHP, a software application by       //
// All Enthusiast, Inc.  Use of any kind of part or all of this           //
// script or modification of this script requires a license from All      //
// Enthusiast, Inc.  Use or modification of this script without a license //
// constitutes Software Piracy and will result in legal action from All   //
//                                                                        //
//           PhotoPost Copyright 2002, All Enthusiast, Inc.               //
//                       Copyright WTN Team`2002                          //
////////////////////////////////////////////////////////////////////////////
include("pp-inc.php");
include("login-inc.php");

list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
authenticate();

// Display a user's profile

if ($ppaction == "rpwd") {
    $query = "SELECT joindate,email,username FROM users WHERE userid=$uid";
    $resulta = ppmysql_query($query,$link);
    list( $dbkey, $email, $username ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    $redir = $Globals{'maindir'}."/index.php";
    
    if ($verifykey == $dbkey) {
        
        $newpass = gen_password();
        $npass = md5($newpass);

        $query = "UPDATE users SET password='$npass' WHERE userid=$uid";
        $resulta = ppmysql_query($query,$link);

        $mail_from = "From: ".$Globals{'adminemail'};
        $letter="You just requested that your password be reset at ".$Globals{'webname'}.".
        
We have issued a you a new password.

Your username is: $username
Your new password is: $newpass

If you would like to change that password, you may do so here:

".$Globals{'maindir'}."/member.php?ppaction=chgpass

Or to edit your profle:

".$Globals{'maindir'}."/member.php?ppaction=edit

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

        $subject="New temporary ".$Globals{'webname'}." password";

        mail( $email, $subject, $letter, $email_from );
        
        if ( isset($adminreset) ) {
            dieWell( "User's password was reset and emailed." );
            exit;
        }
        
        forward( $redir, "Your password has been reset and emailed to you." );
        exit;
    }
    else {
        forward( $redir, "No match. Check the URL and try again." );
        exit;
    }
}

if ($ppaction == "profile") {
    $query = "SELECT username,usergroupid,homepage,icq,aim,yahoo,joindate,posts,birthday,location,interests,occupation,bio,offset FROM users WHERE userid=$uid LIMIT 1";
    $resulta = ppmysql_query($query,$link);
    list( $username,$usergroupid,$homepage,$icq,$aim,$yahoo,$joindate,$posts,$birthday,$location,$interests,$occupation,$bio,$offset ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    $query = "SELECT id,title FROM photos WHERE userid=$uid ORDER BY date DESC LIMIT 1";
    $resulta = ppmysql_query($query,$link);
    list( $phoid, $photitle ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    $query = "SELECT photo FROM comments WHERE userid=$uid ORDER BY date DESC LIMIT 1";
    $resulta = ppmysql_query($query,$link);
    list( $comid ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    if ($comid != "" ) {
        $query = "SELECT id,title FROM photos WHERE id=$comid ORDER BY date DESC LIMIT 1";
        $resulta = ppmysql_query($query,$link);
        list($comphoid,$comphotitle) = mysql_fetch_row($resulta);
        ppmysql_free_result($resulta);
    }
    else {
        $comphotitle = "";
        $comphoid = -1;
    }

    list($jsec,$jmin,$jhour,$jmday,$jmon,$jyear,$jwday,$jyday,$jisdst) = localtime($joindate);

    $jmon++;
    $jyear=1900+$jyear;

    $query = "SELECT groupname from usergroups WHERE groupid=$usergroupid";
    $resulta = ppmysql_query($query,$link);
    list( $usergroup ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    topmenu();
    
    $header = str_replace( "titlereplace", "Member Profile", $header );    

    $output = "$header<p>
        <center><table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"50%\">$menu2</td>
        <td width=\"50%\" align=\"right\" valign=\"middle\">$menu&nbsp;</td></tr></table>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"
        width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontlarge'}."\"
        face=\"".$Globals{'mainfonts'}."\">".$Globals{'galleryname'}."</font>
        </font></td></tr><!-- CyKuH [WTN] -->

        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><b>Profile for $username</font>
        </font></td></tr><!-- CyKuH [WTN] -->

        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Date Registered</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$jmon-$jmday-$jyear</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Posts</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$posts</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Status</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$usergroup</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Last Photo Uploaded</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$phoid\">$photitle</a></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Last Comment Posted</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$comphoid\">$comphotitle</a></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Birthday</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$birthday</font></td></tr>        
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Homepage</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><a href=\"$homepage\">$homepage</a></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">ICQ</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$icq</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">AIM</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$aim</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Yahoo</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$yahoo</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Location</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$location</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Interests</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$interests</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Occupation</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$occupation</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">About Me</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$bio</font></td></tr>
        
        </table></td></tr></table>";

    print "$output<p>".$Globals{'cright'}."$footer";
}

if ( $ppaction == "forgot" ) {
    if ( $do == "process" ) {
        $query = "SELECT username,userid,joindate FROM users WHERE email='$inemail'";
        $resultb = ppmysql_query($query,$link);
        $checkrows = mysql_num_rows($resultb);

        while( list( $dbuser, $dbuid, $joindate ) = mysql_fetch_row($resultb) ) {
            $email_from = "From: ".$Globals{'adminemail'};
            $letter="You just requested that your password be reset at ".$Globals{'webname'}.".
            
In order to do so, you must click on the link below or copy it into your web browser:

".$Globals{'maindir'}."/member.php?ppaction=rpwd&amp;uid=$dbuid&verifykey=$joindate

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

            $subject="How to reset your ".$Globals{'webname'}." password";
            mail( $inemail, $subject, $letter, $email_from );
        }
        if ( $resultb ) ppmysql_free_result( $resultb );

        if ($checkrows > 0) {
            $redir = $Globals{'maindir'}."/index.php";
            forward( $redir, "Please check your email for instructions." );
            exit;
        }
        else {
            $message = "<form size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">That address was not found in our records.</font>";
        }
    }

    $header = str_replace( "titlereplace", "Member Password Admin", $header );    

    $output = "$header<p>
        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">

        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'mainfonts'}."\"
        color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font
        size=\"".$Globals{'fontlarge'}."\"
        face=\"".$Globals{'mainfonts'}."\">".$Globals{'galleryname'}." Forgot Password</font>
        </font></td></tr></table></td></tr></table><p><Center>$message<p>";

    $login = "<p><FORM ACTION=\"".$Globals{'maindir'}."/member.php\" METHOD=\"POST\">
        <TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"CENTER\" VALIGN=\"TOP\">
        <TR><TD BGCOLOR=\"".$Globals{'bordercolor'}."\">
        <TABLE BORDER=\"0\" CELLPADDING=\"10\" CELLSPACING=\"1\" WIDTH=\"100%\">
        <TR BGCOLOR=\"".$Globals{'headcolor'}."\">
        <TD COLSPAN=\"3\">
        <FONT FACE=\"sans-serif\" size=\"".$Globals{'fontmedium'}."\" COLOR=\"".$Globals{'headfontcolor'}."\">
        <b>Reset Password Form</b>
        </FONT>
        <br>
        </TD>
        </TR><TR BGCOLOR=\"".$Globals{'maincolor'}."\">
        <TD>
        <FONT FACE=\"sans-serif\" size=\"".$Globals{'fontmedium'}."\" COLOR=\"".$Globals{'maintext'}."\">
        <b>Your Email Address: &nbsp;</b></font>
        </FONT>
        </TD>
        <TD>
        <INPUT TYPE=\"TEXT\" NAME=\"inemail\" SIZE=\"25\" MAXstrlen=\"40\" VALUE=\"\"></td>
        </TR>
        <TR BGCOLOR=\"".$Globals{'maincolor'}."\">
        <TD COLSPAN=\"3\" ALIGN=\"CENTER\">
        <input type=\"hidden\" name=\"ppaction\" value=\"forgot\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <INPUT TYPE=\"SUBMIT\" NAME=\"submit\"
        VALUE=\"Submit\">
        </TD>
        </TR>
        </TABLE>
        </TD></TR>
        </TABLE>
        </FORM>

        </td></tr></table></td></tr></table>";

    print "$output$login".$Globals{'cright'}."$footer";
}


if ( $ppaction == "chgpass" ) {
    if ( $gologin == 1 ) {
        $furl = $Globals{'maindir'};
        $furl = str_replace( $Globals{'domain'}, "", $furl );
        $furl = "$furl/member.php?ppaction=chgpass";
        login($furl);
        exit;
    }

    if ( $do == "process" ) {
        $reason = "";

        if ( empty($oldpassword) ) {
            $reason .= "<li>The old password is blank.";
            $stop = 1;
        }
        if ( empty($newpassword) ) {
            $reason .= "<li>The new password is blank.";
            $stop = 1;
        }
        if ( empty($cnewpassword) ) {
            $reason .= "<li>The confirm new password field is blank.";
            $stop = 1;
        }
        if ($newpassword != $cnewpassword) {
            $reason .= "<li>The new password field does not match the confirmation password field.";
            $stop = 1;
        }

        if ($newpassword != "") {
            $pwdstrlen = strlen($newpassword);
            if ($pwdstrlen < 4) {
                $reason .= "<li>Your password must be at least 4 characters long.";
                $stop = 1;
            }
        }

        $query = "SELECT password FROM users WHERE userid=$userid LIMIT 1";
        $resulta = ppmysql_query($query, $link);
        list( $dbpwd ) = mysql_fetch_row($resulta);
        ppmysql_free_result($resulta);        

        $oldpassword = md5($oldpassword);
        if ($oldpassword != $dbpwd) {
            $reason .= "<li>Your old password is not correct.";
            $stop = 1;
        }

        if ($stop == 1) {
            dieWell($reason);
        }

        $newpassword = md5($newpassword);
        $query = "UPDATE users SET password='$newpassword' WHERE userid=$userid";
        $resulta = ppmysql_query($query,$link);

        $redirc = $Globals{'maindir'}."/index.php";
        forward( $redirc, "Password changed" );
        exit;
    }

    topmenu();

    $header = str_replace( "titlereplace", "Member Password Admin", $header );    

    $output = "$header<p>
        <Center><table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"25%\">$menu2</td>
        <td width=\"75%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"100%\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\">
        <font size=\"".$Globals{'fontlarge'}."\" face=\"".$Globals{'mainfonts'}."\">".$Globals{'galleryname'}."</font>
        </td></tr><!-- CyKuH [WTN] -->

        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\">
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><b>Password Change Form</b></font>
        </td></tr><!-- CyKuH [WTN] -->
        <form method=\"POST\" action=\"".$Globals{'maindir'}."/member.php\">
        <tr>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><b>Old Password:</b></font></td>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"password\" name=\"oldpassword\" maxstrlen=\"100\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><b>New Password:</b></font></td>
        <td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"password\" name=\"newpassword\" maxstrlen=\"100\"></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><b>Confirm New Password:</b></font></td>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"password\" name=\"cnewpassword\" maxstrlen=\"100\"></font></td></tr>
        </table></td></tr></table><p>
        <input type=\"hidden\" name=\"ppaction\" value=\"chgpass\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <Center><input type=\"submit\" name=\"submit\" value=\"Change Password\"></center>

        </td></tr></table></td></tr></table><p>";

    print "$output".$Globals{'cright'}."$footer";
}

// Edit a user's profile (form)

if ($ppaction == "edit") {
    if ( $gologin == 1 ){
        $furl = $Globals{'maindir'};
        $furl = str_replace( $Globals{'domain'}, "", $furl );
        $furl = "$furl/member.php?ppaction=edit";
        login($furl);
        exit;
    }

    $uid = $userid;
    if ($adminedit == 1) {
        if ($uid == "") {
            $uid = $cookuser;
        }
    }

    $months = array('January','February','March','April','May','June','July','August','September','October','November','December');

    $query = "SELECT username,usergroupid,homepage,icq,aim,yahoo,joindate,posts,birthday,location,interests,occupation,bio,email,offset FROM users WHERE userid=$uid LIMIT 1";
    $resulta = ppmysql_query($query,$link);
    list($username,$usergroupid,$homepage,$icq,$aim,$yahoo,$joindate,$posts,$birthday,$location,$interests,$occupation,$bio,$email,$offset) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    $birth = explode( "-", $birthday );
    $bmon = intval($birth[1]); $bday = intval($birth[2]); $byear = $birth[0];

    if ($bmon != "") $bmonsel = "<option value=\"$bmon\">".$months[$bmon-1]."</option>";
    else $bmonsel = "<option value=\"-1\"></option>";

    if ($bday != "") $bdaysel = "<option value=\"$bday\">$bday</option>";
    else $bdaysel = "<option value=\"-1\"></option>";

    if ($byear == "") $byear = "";
    if ($byear == "0000") $byear = "";

    list($jsec,$jmin,$jhour,$jmday,$jmon,$jyear,$jwday,$jyday,$jisdst) = localtime($joindate);
    $jmon++;
    $jyear=1900+$jyear;

    $query = "SELECT groupname from usergroups WHERE groupid=$usergroup";
    $resulta = ppmysql_query($query,$link);
    list( $usergroup ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);    

    topmenu();
    
    list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
    $mon = $mon + 1;
    $julian = mktime($hour,$min,$sec,$mon,$mday,$year);
    $cclock = formatpptime( $julian );
    $ppdate = formatppdate( $julian );
    $ttime = "$ppdate $cclock";

    $header = str_replace( "titlereplace", "Member Profile", $header );    

    $output = "$header
        <p><Center><table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"50%\">$menu2</td>
        <td width=\"50%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"
        width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontlarge'}."\"
        face=\"".$Globals{'mainfonts'}."\">".$Globals{'galleryname'}."</font>
        </font></td></tr><!-- CyKuH [WTN] -->

        <tr align=\"center\">
        <td align=\"left\" colspan=\"2\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><b>Edit Profile for $username</font>
        </font></td></tr><!-- CyKuH [WTN] -->
        <form method=\"post\" action=\"".$Globals{'maindir'}."/member.php\">
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Date Registered</font>
        </td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$jmon-$jmday-$jyear</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Status</font></td>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">$usergroup</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Password</font></td>
        <td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">[ <a href=\"".$Globals{'maindir'}."/member.php?ppaction=chgpass\">Change Password</a> ]</td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Email</font></td>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><input type=\"text\" name=\"editemail\" size=\"25\" maxstrlen=\"100\" value=\"$email\"></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Email Confirmation</font></td>
        <td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"><input type=\"text\" name=\"editemailconfirm\" size=\"25\" maxstrlen=\"100\" value=\"$email\"></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Birthday</font></td>
        <td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
        <tr>
        <td align=\"center\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" >Month</font></td>
        <td align=\"center\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" >Day</font></td>
        <td align=\"center\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" >Year</font></td>
        </tr>
        <tr>
        <td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" ><select name=\"editmonth\">
        $bmonsel";
        
    for ( $m=0; $m < 12; $m++ ) {
        $output .= "<option value=\"".($m+1)."\">".$months[$m]."</option>\n";
    }

    $output .= "</select></font></td>
        <td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\"><select name=\"editday\">
        $bdaysel";
        
    for ( $x=1; $x < 32; $x++ ) {
        $output .= "<option value=\"$x\" >$x</option>\n";
    }
            
    $output .= "</select></font></td>
        <td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\">
        <input type=\"text\" name=\"edityear\" value=\"$byear\" size=\"".$Globals{'fontlarge'}."\" maxstrlen=\"4\"></font>
        </td></tr>
        </table>
        </font></td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Homepage</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"edithomepage\" size=\"25\" maxstrlen=\"100\" value=\"$homepage\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">ICQ</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editicq\" size=\"25\" maxstrlen=\"20\" value=\"$icq\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">AIM</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editaim\" size=\"25\" maxstrlen=\"20\" value=\"$aim\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Yahoo</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"edityahoo\" size=\"25\" maxstrlen=\"20\" value=\"$yahoo\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Location</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editlocation\" size=\"25\" maxstrlen=\"250\" value=\"$location\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Interests</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editinterests\" size=\"25\" maxstrlen=\"250\" value=\"$interests\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Occupation</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editoccupation\" size=\"25\" maxstrlen=\"250\" value=\"$occupation\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">About Me</font></td><td bgcolor=\"".$Globals{'altcolor1'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"editbio\" size=\"25\" maxstrlen=\"250\" value=\"$bio\"></font>
        </td></tr>
        <tr><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">Time Zone offset<br>Current adjusted time: $ttime</font></td><td bgcolor=\"".$Globals{'altcolor2'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\">
        <input type=\"text\" name=\"offset\" size=\"25\" maxstrlen=\"3\" value=\"$offset\"></font>
        </td></tr>
        </table></td></tr></table><br>
        <center>
        <input type=\"hidden\" name=\"uid\" value=\"$uid\">
        <input type=\"hidden\" name=\"ppaction\" value=\"processedit\">
        <input type=\"submit\" value=\"Save Changes\"></form>";

    print "$output".$Globals{'cright'}."$footer";
}

// Process a user's edit, forward to profile display

if ($ppaction == "processedit") {
    if ($gologin==1){
        $furl=$Globals{'maindir'};
        $furl= str_replace( $Globals{'domain'}, "", $furl );
        $furl="$furl/member.php?ppaction=edit";
        login($furl);
        exit;
    }

    $redir = $Globals{'maindir'}."/member.php?ppaction=profile&amp;uid=$uid";
    if ($adminedit == 0) {
        if ($uid != $userid) {
            dieWell( "You don't have permission to edit this profile." );
            exit;
        }
    }

    $email=$editemail;
    $emailconfirm=$editemailconfirm;
    $bio=$editbio;
    $birthday="$edityear-$editmonth-$editday";
    $homepage = fixstring($edithomepage);
    $icq = fixstring( $editicq );
    $aim = fixstring( $editaim );
    $yahoo = fixstring( $edityahoo );
    $year = fixstring( $edityear );
    $hobbies = fixstring( $editinterests );
    $occupation = fixstring( $editoccupation );
    $location = fixstring( $editlocation );
    
    if ($year == "") $year="0000";
    if ($month == "") $month="0";
    if ($day == "") $day="0";    

    if ($email != $emailconfirm) {
        dieWell("The email field must match the email confirmation field.");
        exit;
    }

    if ($Globals{'emailverify'} == "yes") {
        // Check to see if user changed email. Verify it if needed.
        $query = "SELECT email,username FROM users WHERE userid=$uid LIMIT 1";
        $resulta = ppmysql_query($query,$link);
        list( $emaildb, $username ) = mysql_fetch_row($resulta);
        ppmysql_free_result($resulta);        

        if ($email != $emaildb) {
            list($dsec,$dmin,$dhour,$dmday,$dmon,$dyear,$dwday,$dyday,$disdst) = localtime();
            
            $nowtime = mktime($dhour,$dmin,$dsec,$dmon,$dmday,$dyear);
            $nowpass = md5($nowtime);
            
            $query = "UPDATE users SET password='$nowpass' WHERE userid=$uid";
            $resulta = ppmysql_query($query,$link);

            $email_from = "From: ".$Globals{'adminemail'};
            $letter="You just changed your email address at ".$Globals{'webname'}.".
We have issued a you a new temporary password in order to confirm your new email address.

Your New Temporary Password is: $nowtime

If you would like to change that password, you may do so here:

".$Globals{'maindir'}."/member.php?ppaction=chgpass

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

            $subject="New temporary ".$Globals{'webname'}." password";
            mail( $email, $subject, $letter, $email_from );
        }
    }

    // Write input data to db
    $query = "UPDATE users SET email='$email',homepage='$homepage',icq='$icq',aim='$aim',yahoo='$yahoo',birthday='$birthday',interests='$hobbies',occupation='$occupation',bio='$bio',location='$location',offset='$offset' WHERE userid=$uid";
    $resulta = ppmysql_query($query,$link);

    forward( $redir, "Profile updated." );
}

?>
