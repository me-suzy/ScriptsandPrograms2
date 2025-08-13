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
include("image-inc.php");

if ( is_array($HTTP_POST_FILES) ) {
    while(list($key,$value) = each($HTTP_POST_FILES)) {
        ${$key} = $value;
    }
}



function handleupload( $location = "data" ) {
    global $HTTP_POST_FILES, $userid, $Globals, $category;
    
    $tmpname = $HTTP_POST_FILES['theimage']['tmp_name'];
    $realname = $HTTP_POST_FILES['theimage']['name'];

    if (is_uploaded_file($tmpname) ) {
        $realname = fixfilenames( $realname );

        if ( $location != "data" ) {
            $dst_file = $location;
        }
        else {
            $dst_file = $Globals{'datafull'}."$category/$userid$realname";
        }

        copy($tmpname, $dst_file);
    } else {
        dieWell("Uploaded file not found: $realname<br>Typical reason is that the file exceeded allowed limits.");
        exit;
    }

    return;
}

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

$nolimit = 0;
if ( $Globals{'adminnolimit'} == "yes" && $adminedit == 1 ) {
    $nolimit = 1;
}

$adminexclude = 0;
if ( $Globals{'adminexclude'} == "yes" && $adminedit == 1 ) {
    $adminexclude = 1;
}

if ( $adminedit == 0 ) {
    if ( $Globals{'allowup'} == "no" ) {
        dieWell( "User uploads not allowed" );
    }
}

$querystring = findenv("QUERY_STRING");
if ( ($useruploads == 0 && $gologin==1) || $querystring == "gologin" ) {
    $furl=$Globals{'maindir'};
    $furl= str_replace( $Globals{'domain'}, "", $furl );
    $furl="$furl/uploadphoto.php";

    login( $furl );
    exit;
}

if ( $gologin != 0 ) {
    if ( $useruploads == 0 ) {
        dieWell("Sorry, you don't have permission to upload photos.");
        exit;
    }
    
    if ( $useruploads == 2 ) {
        dieWell("Sorry, but you have not verified your account yet.<p>You must do so before being able to upload.");
    }
}

topmenu();

if ( !isset($theimage) ) {
    $catdefault = "";

    if ( !empty($cat) ) {
        $query = "SELECT id,catname,thumbs FROM categories WHERE id=$cat LIMIT 1";
        $resultb = ppmysql_query($query,$link);
        while ( list( $subid, $subcatname, $subthumbs ) = mysql_fetch_row($resultb) ) {
            if ( $subid < 3000 ) {
                if ( $ugcat{$subid} != 1 ) {
                    $catdefault = "<option selected value=\"$subid\">$subcatname</option>";
                }
            }
        }
        ppmysql_free_result( $resultb );
    }
    else {
        if ( $ugcat{500} != 1 ) {
            $query = "SELECT id,catname,thumbs FROM categories WHERE id=500 LIMIT 1";
            $resultb = ppmysql_query($query,$link);
            list( $subid, $subcatname, $subthumbs ) = mysql_fetch_row($resultb);
            ppmysql_free_result( $resultb );

            $catdefault = "<option selected value=\"$subid\">$subcatname</option>";
        }
    }

    $header = str_replace( "titlereplace", "Upload Photo", $header );    

    $output = "$header<center><p>

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" height=\"40\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"50%\">$menu2</td>
        <td width=\"50%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>
        <table cellpadding=\"0\" cellspacing=\"0\"
        border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"1\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\"
        color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\"><B>".$Globals{'galleryname'}." Image
        Upload</font></td>
        <td colspan=\"1\" align=\"right\" bgcolor=\"".$Globals{'headcolor'}."\"><font
        face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
        <a href=\"javascript:PopUpHelp('uploadphoto.php')\">help</a></font>
        </td>            
        </tr>
        <form method=\"post\" action=\"".$Globals{'maindir'}."/uploadphoto.php\" enctype=\"multipart/form-data\">
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">Username</font></td><td
        bgcolor=\"".$Globals{'maincolor'}."\">
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">$username</td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\" width=\"50%\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Choose a
        category</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\"><select
        name=\"category\">$catdefault";

    if (empty($subid)) $subid="";
    $selected = $subid;
    catmoveopt(0);
    $output .= $catoptions;

    $query = "SELECT SUM(filesize) AS fsize FROM photos WHERE userid=$userid";
    $resulta = ppmysql_query($query,$link);
    list( $diskuse ) = mysql_fetch_row($resulta);
    ppmysql_free_result( $resulta );


    $disk_b = $disk_k * 1024;
    
    if ( $nolimit == 0 ) {
        $diskbytes = $disk_b-$diskuse;
        $diskspace = $diskbytes;
        $diskspace = $diskbytes/1024;
        $diskspace = sprintf("%1.1f", $diskspace);
        $diskbytes = number_format( $diskbytes );
        $diskspace = $diskspace."kb ($diskbytes bytes)";
    }
    else {
        $diskspace = "Unlimited";
    }
    
    $diskusekb = $diskuse/1024;
    $diskusekb = sprintf("%1.1f", $diskusekb );
    $diskusekb = number_format( $diskusekb );
    $diskuse = number_format( $diskuse );
    $diskuse = $diskusekb."kb ($diskuse bytes)";
    $disk_k = number_format($disk_k);
    $disk_b = number_format($disk_b);    
    $disk_k .= "kb ($disk_b bytes)";   

    if ( $Globals{'usenotify'} == "yes" && $userid != 0 ) {
        $notifyhtml = "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Want to be notified by email when users post replies?</td><td
            bgcolor=\"".$Globals{'maincolor'}."\"><select name=\"notify\"><option selected>No</option><option>Yes</option></select></td></tr>";
    }
    else
        $notifyhtml="";

    if ( $adminedit == 1 ) {
        $imgdir = $Globals{'zipuploaddir'}."/$userid";
        $skiphtml = "</table><table cellpadding=\"4\" cellspacing=\"0\" border=\"0\"  width=\"100%\">
            <tr><Td bgcolor=\"".$Globals{'headcolor'}."\" align=\"center\">
            <font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">
            <b>ADMIN OPTIONS FOR BULK OR ZIP UPLOADS</b></font>
            </td>
            <td align=\"right\" bgcolor=\"".$Globals{'headcolor'}."\"><font
            face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
            <a href=\"javascript:PopUpHelp('adminskip.php')\">help</a></font>
            </td>            
            </tr></table>
            <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
            <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Skip upload and process the files in your upload directory<br><font size=\"".$Globals{'fontsmall'}."\">Files should already be in: <b>$imgdir</b></td>
            <td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><input type=\"checkbox\" name=\"skipupload\" value=\"skipupload\"></td></tr>
            <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Show thumbnails during processing?</td>
            <td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"><select name=\"dthumbs\"><option selected>yes</option><option>no</option></select></font></td></tr>
            <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Number of thumbnails to process next?</td>
            <td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"><select name=\"numprocess\"><option selected>10</option><option>25</option><option>50</option><option>100</option></select></td></tr>";
    }
    else
        $skiphtml="";

    if ( $nolimit == 0 || $userid == 0 ) {
        $maxfilesize = $uploadsize."k file size limit.";
    }
    else {
        $maxfilesize = "No file size limit.";
    }

    if ( $Globals{'allowzip'} == "yes" ) {
        $maxfilesize .= " ZIP file uploads allowed (2MB limit).";
    }

    $output .= "</select></td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">Photo to upload:</font><br><b><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\"
        color=\"red\">$maxfilesize</font></b></td><td bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"file\" name=\"theimage\"></td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Disk space allowed on your account:</td><td
        bgcolor=\"".$Globals{'maincolor'}."\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\">$disk_k</td></tr>        
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Disk space used on your account:</td><td
        bgcolor=\"".$Globals{'maincolor'}."\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\">$diskuse</td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Disk space remaining for your account:</td><td
        bgcolor=\"".$Globals{'maincolor'}."\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\">$diskspace</td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Enter a title for the photo</td><td bgcolor=\"".$Globals{'maincolor'}."\"><input
        type=\"text\" name=\"title\"></td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">To help users find your photo, please enter a few (up to 10) descriptive
        keywords (separated by spaces):</td><td bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"text\" name=\"keywords\"></td></tr>
        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Photo Description</td><td bgcolor=\"".$Globals{'maincolor'}."\"><textarea
        name=\"desc\" cols=\"30\" rows=\"5\"></textarea></td></tr>
        $notifyhtml
        $skiphtml
        <Center>
        <Tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><center>
        <input type=\"hidden\" name=\"password\" value=\"$password\">
        <input type=\"hidden\" name=\"userid\" value=\"$userid\">
        <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2000000\">
        <input type=\"submit\" value=\"Upload/Submit\">
        <p><b>When you hit SUBMIT, the file you selected will be uploaded.</b><br></font><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">
        (Depending on the size of the file and your connection, this may take some time. <b>Please be patient.</b>)</p></font></td></tr></table></td></tr></table><p>".$Globals{'cright'}."$footer";

    print $output;
}
else {
    if (empty($skipupload)) $skipupload="";
    if ( $category == "" ) {
        $category = 500;
    }

    if ( $skipupload == "skipupload" ) {
        $deftitle = urlencode($title);
        $defdesc = urlencode($desc);
        $furl = $Globals{'zipuploadurl'}."/$userid";

        forward( $Globals{'maindir'}."/bulkupload.php?ppaction=addphotos&do=preview&photopath=$userid&deftitle=$deftitle&defdesc=$defdesc&defcat=$category&keywords=$keywords&numprocess=$numprocess&dthumbs=$dthumbs&furl=$furl", "Preparing to process image list!" );
        exit;
    }

    $realname = $HTTP_POST_FILES['theimage']['name'];
    
    if ( !empty($thevideo) && empty($realname) ) {
        process_image( $thevideo, $filepath, $category, 1 );
        
        $query = "SELECT id FROM photos WHERE userid=$userid AND bigimage='$thevideo'";
        $resulta = ppmysql_query($query,$link);
        list( $forwardid ) = mysql_fetch_row($resulta);
        ppmysql_free_result($resulta);

        if ( empty($forwardid) ) {
            dieWell( "There was a problem processing your video: $realname.<p>Please notify the System Administrator." );
            exit;
        }
        forward( $Globals{'maindir'}."/showphoto.php?photo=$forwardid", "Your image was uploaded successfully!" );
        exit;
    }

    if ( $realname == "" ) {
        dieWell( "You need to enter the name of a file to upload! $thevideo / $theimage" );
        exit;
    }

    $realname = fixfilenames( $realname );
    $theext   = get_ext( $realname );
    
    $filepath = $Globals{'datafull'}."$category/$userid$realname";
    $outfilename = "$userid$realname";

    $query = "SELECT userid,bigimage FROM photos where userid=$userid";
    $resulta = ppmysql_query($query,$link);

    while( list( $uid, $bgimage ) = mysql_fetch_row($resulta) ) {
        if ($uid == $userid && $uid != 0) {
            if ( $bgimage == $realname ) {
                dieWell("Sorry, you already uploaded an image called $realname.  Try a different name.");
                exit;
            }
        }
    }
    ppmysql_free_result($resulta);

    $title = fixmessage( $title );
    $keywords = fixmessage( $keywords );
    $desc = fixmessage( $desc );

    if ( $category == "notcat" ) {
        $emessage = "The category you chose is a top level category.<p>Please go back and choose one of its subcategories to upload your image.";
        dieWell($emessage);
    }

    //####// Write the file to a directory #####

    //#// Do you wish to allow all file types?  yes/no (no capital letters)
    $allowall = "no";

    //#// If the above = "no"; then which is the only extention to allow?
    //#// Remember to have the LAST 4 characters i.e. .ext
    if ($realname != "") {
        $isfilegood = "yes";
        if ( $allowall != "yes" ) {
            if ( !is_image($outfilename) ) {
                $isfilegood = "no";
            }
        }

        if ($isfilegood == "yes") {
            handleupload();
        }

        //
        // ZIP Uploads for Users
        //
        if ( $Globals{'allowzip'} ) {
            if (strtolower(substr($outfilename,strlen($outfilename) - 4,4)) == ".zip" ) {
                if ( $Globals{'unregpho'} == "yes" && $gologin == 1 ) {
                    dieWell("You must be a registered user to upload a ZIP file!");
                }

                $filepath = $Globals{'zipuploaddir'}."/$userid";
                $filedir = "$filepath/$outfilename";

                if ( !file_exists( $filepath ) ) {
                    if ( !mkdir( $filepath, 0755 ) ) {
                        dieWell( "Error creating directory $filepath. Please notify the System Administrator." );
                        exit;
                    }
                    chmod( $filepath, 0777 );
                }

                chdir( $filepath );
                handleupload( $filedir );

                $sys_cmd = $Globals{'zip_command'}." -qq $filedir";
                system( $sys_cmd );
                unlink( $filedir );

                $deftitle = urlencode($title);
                $defdesc = urlencode($desc);
                $furl = $Globals{'zipuploadurl'}."/$userid";
                if ( empty($numprocess) ) $numprocess = 10;
                if ( empty($dthumbs) ) $dthumbs = "yes";

                forward( $Globals{'maindir'}."/bulkupload.php?ppaction=addphotos&do=preview&photopath=$userid&deftitle=$deftitle&defdesc=$defdesc&defcat=$category&keywords=$keywords&numprocess=$numprocess&dthumbs=$dthumbs&furl=$furl", "Preparing to process image list!" );
                exit;
            }
        }

        //
        // Multimedia uploads
        //
        if ( $Globals{'allowmedia'} == "yes" ) {
            if ( is_multimedia( $outfilename ) ) {
                if ( $Globals{'unregpho'} == "yes" && $gologin == 1 ) {
                    dieWell("You must be a registered user to upload a video file!");
                }

                if ( !isset($thevideo) ) {
                    handleupload();
                    
                    if ( file_exists($filepath) ) {
                        $insize = filesize( $filepath );
                    }
                    else {
                        dieWell("File upload error. Cannot find uploaded file.<br>Path: [$filepath]");
                        exit;
                    }
                
                    $uploadsize = $Globals{'mmuploadsize'};
                    if ( $nolimit == 0 && ($insize > ($uploadsize*1024)) ) {
                        unlink($filepath);
                        dieWell( "Your file exceeded our limit of ".$uploadsize."kb.  Please go back and try again.");
                    }

                    $header = str_replace( "titlereplace", "Video Upload", $header );    

                    $output = "$header
                        <p><center><table cellpadding=\"0\" cellspacing=\"0\"
                        border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
                        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
                        <tr align=\"center\">
                        <td colspan=\"2\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\"
                        color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\"><B>".$Globals{'galleryname'}." Video Upload</font>
                        </font></td></tr>
                        <form method=\"post\" action=\"".$Globals{'maindir'}."/uploadphoto.php\" enctype=\"multipart/form-data\">
                        <tr><Td bgcolor=\"".$Globals{'maincolor'}."\" width=\"50%\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">
                        You have uploaded a multimedia file for which we cannot generate a thumbnail. Please upload your choice of a thumbnail image,
                        or we will use our default image which indicates the link is to a video file.
                        </font></b></td><td bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"file\" name=\"theimage\"></td></tr>
                        <Center>
                        <Tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><center>
                        <input type=\"hidden\" name=\"userid\" value=\"$userid\">
                        <input type=\"hidden\" name=\"category\" value=\"$category\">
                        <input type=\"hidden\" name=\"thevideo\" value=\"$realname\">
                        <input type=\"hidden\" name=\"title\" value=\"$title\">
                        <input type=\"hidden\" name=\"desc\" value=\"$desc\">
                        <input type=\"hidden\" name=\"keywords\" value=\"$keywords\">
                        <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2000000\">
                        <input type=\"submit\" value=\"Submit\">";

                    print $output;
                    exit;
                }
                else {
                    handleupload( $filedir );
                }
            }
        }
    }

    if ( file_exists($filepath) ) {
        $insize = filesize( $filepath );
    }
    else {
        dieWell("File upload error. Cannot find uploaded file.<br>Path: [$filepath]");
        exit;
    }

    if ( $theext != "zip" ) {
        if ( $nolimit == 0 && ($insize > ($uploadsize*1024)) ) {
            unlink($filepath);
            dieWell("Your file exceeded our limit of ".$uploadsize."kb.  Please go back and try again.");
            exit;
        }
    }

    $query = "SELECT SUM(filesize) AS fsize FROM photos WHERE userid=$userid";
    $resulta = ppmysql_query($query,$link);
    list( $diskuse ) = mysql_fetch_row($resulta);
    ppmysql_free_result( $resulta );
    
    $disk_k = ($disk_k * 1024);
    $diskbytes = $disk_k-($diskuse+$insize);

    if ( $nolimit == 0 ) {
        if ( $diskbytes < 0 ) {
            dieWell( "You are allowed a maximum of $disk_k bytes of diskspace.  If you would like to
                upload more images, please delete some of your older images and/or optimize your images using lower quality jpg settings before
                uploading." );
            exit;
        }
    }

    if ( $isfilegood != "yes" ) {
        dieWell( "Image must be a .jpg, .gif, .tif or .png file." );
        exit;
    }

    if ( isset($thevideo) ) {
        $thumbsize = create_thumb( $realname, $filepath, $category, $thevideo );
        unlink( $filepath );
        process_image( $thevideo, $filepath, $category, 1 );
        $realname = $thevideo;
    }
    else {
        $thumbsize = create_thumb( $realname, $filepath, $category );
        process_image( $realname, $filepath, $category );
    }

    $query = "SELECT id FROM photos WHERE userid=$userid AND bigimage='$realname'";
    $resulta = ppmysql_query($query,$link);
    list( $forwardid ) = mysql_fetch_row($resulta);
    ppmysql_free_result($resulta);

    if ( empty($forwardid) ) {
        dieWell( "There was a problem processing your image: $realname.<p>Please notify the System Administrator." );
        exit;
    }
    
    forward( $Globals{'maindir'}."/showphoto.php?photo=$forwardid", "Your image was uploaded successfully!" );
}

?>
