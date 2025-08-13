<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.11                                           //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Poncho                                         //
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
include("adm-cinc.php");
include("login-inc.php");

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if ( $gologin != 1 ) {
    if ( $nopost == 1 ) {
        dieWell("Sorry, you don't have permission to create albums.");
        exit;
    }
    if ( $useruploads == 2 ) {
        dieWell("Sorry, but you have not verified your account yet.<p>You must do so before being able to manage your albums.");
    }
}

if ( $Globals{'allowpa'} == "no" ) {
    dieWell("Sorry, Personal Albums are not enabled.");
    exit;
}
    
topmenu();

if ( empty($ppaction) ) $ppaction="albums";
if ( empty($do) ) $do="";

if ( $ppaction == "albums" ) {
    //# Generate the edit useralbums HTML form

    $header = str_replace( "titlereplace", "User Albums", $header );    

    $output = "$header<center><p>
        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" height=\"40\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"50%\">$menu2</td>
        <td width=\"50%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>    
        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><b>PhotoPost Album Editor</font>
        </font></td>
        </tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><Br><ul>(<a
        href=\"".$Globals{'maindir'}."/useralbums.php?ppaction=addalbum\">Add Album</a>)</ul>";

    albumli( $userid );

    $output.= "<p><center></td></tr></table></td></tr></table><p>".$Globals{'cright'}."$footer";

    print $output;
}

if ($ppaction == "addalbum") { //# Add a album
    if ( !empty($do) ) {
        if ( empty($isprivate) ) $isprivate="no";
        if ( empty($albumdesc) ) $albumdesc = "";
        if ( empty($albumname) ) $albumname = "New Album";
        
        $pa_password = "";        
        if ( $isprivate == "yes" ) $pa_password = gen_password();
        
        // Process a user's album submission
        $albumname = addslashes( $albumname );
        $albumdesc = addslashes( $albumdesc );

        $query = "INSERT INTO useralbums values(NULL,'$albumname',$userid,'$albumdesc','$isprivate','$pa_password')";
        $setug = ppmysql_query($query,$link);
        $thealbumid = mysql_insert_id( $link );

        if ( $thealbumid < 3000 ) {
            $query = "UPDATE useralbums SET id='3000' WHERE id=$thealbumid";
            $setug = ppmysql_query($query, $link);
            $thealbumid = 3000;
        }

        $newdir = $Globals{'datafull'}."$thealbumid";
        if ( !mkdir( $newdir, 0755 ) ) {
            dieWell( "Error creating directory $newdir. Please notify the system administrator." );
            exit;
        }
        chmod( $newdir, 0777 );

        forward( $Globals{'maindir'}."/useralbums.php?ppaction=albums", "Processing complete!" );
        exit;
    }

    //# Print out the Add a album HTML form
    $header = str_replace( "titlereplace", "Add User Album", $header );    
    
    $output = "$header<center><hr>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><b>PhotoPost Add a album</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"#f7f7f7\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"#000000\">$menu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>

        <table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"><form method=\"post\"
        action=\"".$Globals{'maindir'}."/useralbums.php\">
        <tr><Td bgcolor=\"".$Globals{'headcolor'}."\">
        <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\">
        <tr><Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Album Name</font></th>
        <Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Album Description</font></th>
        <Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Is Album Private?</font></th>        
        </tr><Tr>
        <Td bgcolor=\"#f7f7f7\">
        <input type=\"text\" size=\"50\" value=\"\" name=\"albumname\"></td>
        <Td bgcolor=\"#f7f7f7\"><input type=\"text\" size=\"50\" value=\"\" name=\"albumdesc\"></td>
        <Td bgcolor=\"#f7f7f7\"><select name=\"isprivate\">
        <option selected>no</option><option>yes</option></select></td>
        </tr></table></td></tr></table><p>
        <input type=\"hidden\" name=\"albumid\" value=\"$userid\">
        <input type=\"hidden\" name=\"ppaction\" value=\"addalbum\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"submit\" value=\"Add album\"></form></td></tr></table></td></tr></table><p>".$Globals{'cright'}."
        $footer";

    print $output;
}

if ($ppaction == "editalbum") {
    if ( $do == "process" ) {
        if ( empty($isprivate) ) $isprivate="no";
        if ( empty($albumdesc) ) $albumdesc = "";
        if ( empty($albumname) ) $albumname = "New Album";
        
        $pa_password = "";        
        if ( $isprivate == "yes" && $oldstat == "no" ) $pa_password = gen_password();        
        
        $albumname = addslashes( $albumname );
        $albumdesc = addslashes( $albumdesc );

        $query = "UPDATE useralbums SET albumname='$albumname', description='$albumdesc', isprivate='$isprivate', password='$pa_password' WHERE id='$albumid'";
        $setug = ppmysql_query($query,$link);

        forward( $Globals{'maindir'}."/useralbums.php?ppaction=albums", "Processing complete!" );
        exit;
    }

    $header = str_replace( "titlereplace", "Edit User Album", $header );    

    $output = "$header<center><hr>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><B>PhotoPost Album Editor</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"#f7f7f7\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"#000000\">$menu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"><form method=\"post\"
        action=\"".$Globals{'maindir'}."/useralbums.php\"><tr><Td>
        <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\"><tr>
        <Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Album Name</font></th>
        <Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Album Description</font></th>
        <Th bgcolor=\"#F7f7f7\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Is Album Private?</font></th>        
        </tr>";


    $query = "SELECT id,albumname,description,isprivate,password FROM useralbums WHERE id=$albumid";
    $boards = ppmysql_query($query,$link);
    list($id,$albumname,$albumdesc,$isprivate,$password) = mysql_fetch_row($boards);
    ppmysql_free_result($boards);

    $albumname = str_replace( "\"", "&quot", $albumname);
    $albumdesc = str_replace( "\"", "&quot", $albumdesc);
    
    if ($isprivate == "yes") {
        $privatecode = "<Td bgcolor=\"#f7f7f7\"><select name=\"isprivate\"><option
            selected>yes</option><option>no</option></select></td>";
    }
    else {
        $privatecode = "<Td bgcolor=\"#f7f7f7\"><select name=\"isprivate\">
            <option selected>no</option><option>yes</option></select></td>";
    }

    $output .= "<Tr><Td bgcolor=\"#f7f7f7\"><input type=\"text\" size=\"50\"
        value=\"$albumname\" name=\"albumname\"></td><Td bgcolor=\"#f7f7f7\"><input type=\"text\" size=\"50\" value=\"$albumdesc\" name=\"albumdesc\"></td>
        $privatecode</tr>";

    $output .= "</table></td></tr></table><p>
        <input type=\"hidden\" name=\"albumid\" value=\"$id\">
        <input type=\"hidden\" name=\"oldstat\" value=\"$isprivate\">
        <input type=\"hidden\" name=\"ppaction\" value=\"editalbum\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"submit\" value=\"Save Changes\"></form></td></tr></table></td></tr></table><p>".$Globals{'cright'}."$footer";

    print $output;
    exit;
}

if ($ppaction == "delalbum") {  //# Delete a album
    if ($do == "process") { //# Process delete album
        if ( !is_numeric($albumid) ) {
            dieWell( "Malformed parameter passed!" );
            exit;
        }
        
        if ($albumid != 500 && $albumid != "0" && $albumid != "") {
            delete_cat($albumid);
        }
        else {
            dieWell("Invalid album ID.");
            exit;
        }

        $forwardid = $Globals{'maindir'}."/useralbums.php?ppaction=albums";
        forward( $forwardid, "Processing complete!" );
        exit;
    }

    //# Generate an 'are you sure' you want to delete? form...

    $querya="SELECT albumname FROM useralbums where id=$albumid";
    $albumq = ppmysql_query($querya, $link);
    list( $albumname ) = mysql_fetch_row($albumq);
    ppmysql_free_result($albumq);

    $header = str_replace( "titlereplace", "Delete User Album", $header );    

    $output="$header<center><hr>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><B>PhotoPost Add a album</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"#f7f7f7\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"#000000\">$menu</b></font></td></tr>
        <tr><td bgcolor=\"#f7f7f7\"><center><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><Br>
        You're about to delete the album called \"$albumname\", and <b>ALL PHOTOS AND COMMENTS</b> within the album.<p>
        Are you sure you want to do that?
        <form action=\"".$Globals{'maindir'}."/useralbums.php\" method=\"post\">
        <input type=\"hidden\" name=\"albumid\" value=\"$albumid\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"hidden\" name=\"ppaction\" value=\"delalbum\">
        <input type=\"submit\" value=\"I'm sure, delete the album.\"></form></font></td></tr></table></td></tr></table>";

    print $output;
}

?>
