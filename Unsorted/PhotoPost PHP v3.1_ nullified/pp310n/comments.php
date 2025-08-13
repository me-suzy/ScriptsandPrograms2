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

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

$querystring = findenv("QUERY_STRING");
if ( ($gologin == 1 && $usercomment == 0) ||$querystring == "gologin" ) {
    $furl=$Globals{'maindir'};
    $furl= str_replace( $Globals{'domain'}, "", $furl );
    $furl="$furl/comments.php?photo=$photo&cedit=$cedit";
    login($furl);
    exit;
}

if ($Globals{'allowpost'} == "no" && $adminedit != 1) {
    dieWell("User comments not allowed.");
    exit;
}

if ($gologin != 1) {
    if ($nopost == 1) {
        dieWell("Sorry, you don't have permission to post/edit.<p>If you tried to edit, you might not be the post's
            author or editing may<Br> be disabled for your usergroup.");
        exit;
    }    
}

list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
$mon = $mon + 1;

$query = "SELECT cat FROM photos WHERE id=$photo";
$resulta = ppmysql_query($query,$link);
list( $thiscat ) = mysql_fetch_row($resulta);
ppmysql_free_result($resulta);

if ( $ugpost{$thiscat} == 1 || $usercomment == 0 ) {
     dieWell("You don't have permission to post comments for images in this category.");
     exit;
}

if (empty($notify)) $notify="";

if ($Globals{'usenotify'} == "yes") {
    if ($notify == "on") {
        $query = "INSERT INTO notify values(NULL,$userid,$photo)";
        $resulta = ppmysql_query($query,$link);

        forward( $Globals{'maindir'}."/showphoto.php?photo=$photo", "Email notification enabled." );
        exit;
    }

    if ($notify == "off") {
        if ( !is_numeric($notifyid) || !is_numeric($userid) ) {
            dieWell( "Malformed parameter passed!" );
            exit;
        }
        
        $query = "DELETE FROM notify WHERE id=$notifyid AND userid=$userid";
        $resulta = ppmysql_query($query,$link);

        forward( $Globals{'maindir'}."/showphoto.php?photo=$photo", "Email notification disabled." );
        exit;
    }
}

if ( isset($photo) ) {
    if ( !isset($post) ) {
        $erating=""; $ecomments="";

        if ( IsSet($cedit) && $cedit != "" ) {
            $query = "SELECT userid,username,rating,comment FROM comments WHERE id=$cedit LIMIT 1";
            $resulta = ppmysql_query($query,$link);
            list( $cuserid, $cusername, $erating, $ecomments ) = mysql_fetch_row($resulta);
            ppmysql_free_result( $resulta );

            if ( ($userid != $cuserid || $userid < 1) && $adminedit != 1 ) {
                dieWell( "You do not have permission to edit this comment.");
                exit;
            }
        }
        else {
            $cusername = $username;
        }

        topmenu();

        $header = str_replace( "titlereplace", "Add a Comment", $header );    

        $output = "$header<center>
        
            <p><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\">
            <Tr><Td valign=\"middle\" width=\"50%\">&nbsp;</td><td width=\"50%\" align=\"right\" valign=\"middle\">
            $menu&nbsp;
            </td></tr></table>

            <form name=\"theform\" method=\"post\" action=\"".$Globals{'maindir'}."/comments.php\">
            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"
            width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
            
            <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
            <tr align=\"center\">
            <td colspan=\"1\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font
            face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
            <b>Add your comments</b></font>
            </td>
            <td colspan=\"2\" align=\"right\" bgcolor=\"".$Globals{'headcolor'}."\"><font
            face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
            <a href=\"javascript:PopUpHelp('comments.php')\">help</a></font>
            </td>            
            </tr>";

        $output .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">
            Username</font></td><td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\">
            <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">$cusername
            </font></td></tr>";
            
        if ( $Globals{'allowrate'} == "yes" ) {
            $output .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Rate this photo overall</font></td>
            <td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><select name=\"rating\">
            <option value=\"0\" selected>Rate this photo</option>
            <option value=\"5\">5 - Excellent</option>
            <option value=\"4\">4 - Great</option>
            <option value=\"3\">3 - Good</option>
            <option value=\"2\">2 - Fair</option>
            <option value=\"1\">1 - Poor</option>
            </select></td></tr>";
        }
        
        $output .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Comments:<br><br>
            <font size=\"".$Globals{'fontsmall'}."\"><a href=\"javascript:PopUpHelp('ubbcode.php')\">UBB Code legend</a><br>
            <a href=\"javascript:PopUpHelp('smilies.php')\">Smilies legend</a></font></font></td><td
            bgcolor=\"".$Globals{'maincolor'}."\"><textarea
            name=\"message\" cols=\"50\" rows=\"10\">$ecomments</textarea></td>
            <Td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\">";

        $query = "SELECT bigimage,approved,cat,title,userid FROM photos WHERE id=$photo LIMIT 1";
        $resulta = ppmysql_query($query,$link);
        list( $bigimage, $approved, $cat, $title, $theuser ) = mysql_fetch_row($resulta);
        ppmysql_free_result( $resulta );

        $imgtag = get_imagethumb( $bigimage, $cat, $theuser, $approved );
        
        $output .= "$imgtag<br>";
        $output .= "<font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">$title</font>";

        $output .= "</td></tr>";
        if ($cedit != "") {
            $output .= "<Tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Delete post?</font></td><Td colspan=\"2\"
                bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\"
                color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Only check this box if you want to completely delete this post: <input type=\"checkbox\" name=\"delete\" value=\"yes\"></td></tr>";
        }

        $inputcat=$cat;
        $inputedit=$cedit;

        $output .= "<Tr><Td colspan=\"3\" bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\">
            <input type=\"hidden\" name=\"cat\" value=\"$inputcat\">
            <input type=\"hidden\" name=\"password\" value=\"$password\">
            <input type=\"hidden\" name=\"puserid\" value=\"$theuser\">
            <input type=\"hidden\" name=\"photo\" value=\"$photo\">";

        if ($cedit == "") {
            $output .= "<input type=\"hidden\" name=\"post\" value=\"new\">
                        <input type=\"submit\" value=\"Submit Post\">";
        }
        else {
            $output .= "<input type=\"hidden\" name=\"postid\" value=\"$inputedit\">
                        <input type=\"hidden\" name=\"post\" value=\"cedit\">
                        <input type=\"submit\" value=\"Submit Edit\">";
        }

        $output .= "</td></tr></table></td></tr></table></form><p>".$Globals{'cright'}."$footer";
        print $output;
    }
    else {
        // Go ahead and post the comment to the database
        if ( $rating == "" ) $rating=0;
        if ( $username == "" ) $gologin=1;
        if ( !isset($message) ) $message="";
        if ( !isset($delete) ) $delete="no";
        $noinsert=0;

        if ( $Globals{'ipcache'} != 0 ) {
            $ipaddress = findenv("REMOTE_ADDR");
            $query = "SELECT userid,date,photo FROM ipcache WHERE ipaddr='$ipaddress' AND type='vote' AND photo='$photo' LIMIT 1";
            $result = ppmysql_query($query, $link);
            $numfound = mysql_num_rows($result);
            
            // for voting we do a double-check; we want to see if the userid has voted before
            if ( $numfound == 0 && $userid != 0 ) {
                $query = "SELECT userid,date,photo FROM ipcache WHERE userid='$userid' AND type='vote' AND photo='$photo' LIMIT 1";
                $result = ppmysql_query($query, $link);
                $numfound = mysql_num_rows($result);
            }
            
            if ( $numfound > 0 ) {
                list( $userid, $lastdate, $photo ) = mysql_fetch_row($result);
                
                list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
                $mon = $mon + 1;
                $hour = $hour - $Globals{'ipcache'};  
                $timeout = mktime($hour,$min,$sec,$mon,$mday,$year);
    
                if ( $lastdate < $timeout ) {
                    $query = "DELETE FROM ipcache WHERE date < $timeout";
                    $result = ppmysql_query($query,$link);
                }
                else {
                    if ( $rating != 0 ) {
                        dieWell( "You can only rate a photo once every ".$Globals{'ipcache'}." hour(s).");
                        exit;
                    }
                }
            }
            else {
                list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
                $mon = $mon + 1;
                $mytime = mktime($hour,$min,$sec,$mon,$mday,$year);
                
                $query = "INSERT INTO ipcache (userid,ipaddr,date,type,photo) VALUES ('$userid', '$ipaddress', '$mytime', 'vote', '$photo')";
                $result = ppmysql_query($query,$link);
            }
        }

        if ( $message == "" && $rating == 0 ) {
            dieWell( "You did not fill in the comments or rating field." );
            exit;
        }

        $julian = mktime($hour,$min,$sec,$mon,$mday,$year);

        if ($post == "new") {
            $query = "SELECT userid,comment,rating FROM comments WHERE photo=$photo";
            $resultb = ppmysql_query($query,$link);

            if ( $userid > 0 ) {
                while( list( $checkuid, $checkdup, $checkrating ) = mysql_fetch_row($resultb) ) {
                    if ( $checkdup == $message && $checkuid == $userid ) $noinsert=1;
                }
                ppmysql_free_result($resultb);
            }

            $query = "SELECT cat,title,userid FROM photos WHERE id=$photo";
            $resulta = ppmysql_query($query,$link);
            list( $getcat, $gettitle, $getuserid ) = mysql_fetch_row($resulta);
            ppmysql_free_result($resulta);

            if ($noinsert != 1) {
                $message = fixmessage ( $message );

                $username = addslashes( $username );
                $message = addslashes( $message );

                $query = "INSERT INTO comments values(NULL,'$username',$userid,$julian,$rating,'$message',$photo,$getcat)";
                $resulta = ppmysql_query($query,$link);

                if ($Globals{'cpostcount'} == "yes") {
                    inc_user_posts();
                }

                if ($Globals{'usenotify'} == "yes") {
                    $queryc = "SELECT userid FROM notify WHERE photo=$photo";
                    $resultc = ppmysql_query($queryc, $link);

                    if ( $resultc ) {
                        while( list( $notify_user ) = mysql_fetch_row($resultc) ) {
                            list( $usernm, $useremail ) = get_username( $notify_user );

                            $email_from = "From: ".$Globals{'adminemail'};
                            $letter="$username has posted a reply about the following photo:

\"$gettitle\"
".$Globals{'maindir'}."/showphoto.php?photo=$photo

If you no longer wish to be notified of replies about the above photo, you can disable notification for it here:

".$Globals{'maindir'}."/comments.php?notify=off&notifyid=$getuserid&photo=$photo

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

                            $subject="New Reply to $gettitle at ".$Globals{'webname'};
                            mail( $useremail, $subject, $letter, $email_from );
                        }
                        ppmysql_free_result($resultc);
                    }
                }
            }
        }
        else {
            if ( $delete == "yes" ) {
                if ( !is_numeric($postid) ) {
                    dieWell( "Malformed parameter passed!" );
                    exit;
                }
                    
                $query = "DELETE FROM comments WHERE id=$postid";
                $resulta = ppmysql_query($query,$link);

                if ($Globals{'cpostcount'} == "yes") {
                    inc_user_posts( "minus" );
                }
            }
            else {
                $message = fixmessage( $message );
                $message = addslashes( $message );

                $query = "UPDATE comments SET rating=$rating, comment='$message' WHERE id=$postid";
                $resulta = ppmysql_query($query,$link);

                $query = "UPDATE photos SET lastpost=$julian WHERE id=$photo";
                $resulta = ppmysql_query($query,$link);
            }
        }

        // just to revalidate the rating, we need to recheck the rating for the post
        $query = "SELECT rating FROM comments WHERE photo=$photo AND rating != '0'";
        $resultb = ppmysql_query($query,$link);

        $numrating=0; $sumrating=0; $averagerate=0;
        while( list ( $checkrating ) = mysql_fetch_row($resultb) ) {
            $numrating++;
            $sumrating = ($sumrating+$checkrating);
        }
        if ( $resultb ) ppmysql_free_result( $resultb );
        
        if ( $numrating != 0 && $sumrating != 0 ) $averagerate = round( $sumrating / $numrating );

        $query = "UPDATE photos SET rating=$averagerate WHERE id=$photo";
        $resulta = ppmysql_query($query, $link);

        if ( $noinsert == 1 ) $text="You have a duplicate post, so it was not added.";
        else $text="Your post or edit was successful.";

        forward( $Globals{'maindir'}."/showphoto.php?photo=$photo", $text );
        exit;
    }

}
else {
    print "Invalid call to script.";
}

?>
