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

$gologin=0; $nopost=0;

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if ( $Globals{'adminnolimit'} == "yes" ) {
    if ( $adminedit  == 1) $nolimit = 1;
    else $nolimit = 0;
}
else
    $nolimit = 0;

if ( $Globals{'adminexclude'} == "yes" ) {
    if ( $adminedit == 1 ) $adminexclude = 1;
    else $adminexclude = 0;
}
else
    $adminexclude = 0;

if ( $adminedit == 0 ) {
    if ( $Globals{'allowup'} == "no" )
        dieWell( "User uploads not allowed" );
}

$qenv = findenv( "QUERY_STRING" );
if ( ($useruploads == 0 && $gologin==1) || $querystring == "gologin" ) {
    $furl=$Globals{'maindir'};
    $furl= str_replace( $Globals{'domain'}, "", $furl );
    $furl="$furl/uploadform.php";
    login($furl);
    exit;
}

if ( $gologin != 1 ) {
    if ( $nopost == 1 || $useruploads == 0 ) {
        dieWell("Sorry, you don't have permission to upload photos.");
        exit;
    }
    
    if ( $useruploads == 2 ) {
        dieWell("Sorry, but you have not verified your account yet.<p>You must do so before being able to upload.");
    }
}

topmenu();

if ( $ppaction == "addphotos" ) {
    if ($do == "process") {

        $totalphotos=$thecount;

        for ( $i = 1; $i <= $totalphotos; $i++ ) {
            $addk = "add$i";
            $catkey = "cat$i";
            $titlekey = "title$i";
            $desckey = "desc$i";
            $imgkey = "imgname$i";

            $imgname = ${$imgkey};
            $category = ${$catkey};
            $title = ${$titlekey};
            $desc = ${$desckey};
            $addkey = ${$addk};

            $filein = $inpath."/$imgname";

            if ( $origcat != $category ) {
                move_image( $origcat, $category, $tuserid, $imgname );
            }

            if ($addkey == 1) {
                $imgname = fixfilenames($imgname);
                $filepath = $Globals{'datafull'}."$category/$photopath$imgname";
                copy( $filein, $filepath );

                // Open image, write out thumb, fullsize, and medium as needed
                create_thumb( $imgname, $filepath, $category );

                if ( is_multimedia( $filepath ) ) {
                    process_image( $imgname, $filepath, $category, 1 );
                }
                else {
                    process_image( $imgname, $filepath, $category );
                }

                // Delete thumb and image from temp dir
                if ( file_exists( $filein ) )
                    @unlink ($filein);
            }
            else {
                // Delete the image and thumb from temp dir
                if ( file_exists( $filein ) )
                    @unlink ($filein);

                $filenoext = get_filename( $imgname );
                $theext    = get_ext( $imgname );
                $thumbnail = "$userid".$filenoext."-thumb.$theext";
                $tfile = $Globals{'datafull'}."$category/$thumbnail";

                if ( file_exists( $tfile ) )
                    @unlink ($tfile);
            }
        }
        
        $forward_url = $Globals{'maindir'}."/bulkupload.php?ppaction=addphotos&do=preview&thecount=$totalphotos&photopath=$userid&deftitle=$deftitle&defdesc=$defdesc&defcat=$defcat&keywords=$keywords&numprocess=$numprocess&dthumbs=$dthumbs&furl=$furl";
        forward( $forward_url, "Processing image list!" );
        exit;
    }

    catmoveopt(0);
    $header = str_replace( "titlereplace", "Bulk Uploads", $header );            


    $output = "$header<center><hr>
        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\">
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontlarge'}."\" color=\"".$Globals{'headfontcolor'}."\"
        face=\"".$Globals{'mainfonts'}."\">PhotoPost Add Photos</font>
        </td></tr><tr>
        <td bgcolor=\"#f7f7f7\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"#000000\">$menu</b></font></td></tr>
        <form action=\"".$Globals{'maindir'}."/bulkupload.php\" method=\"POST\">";

    if ( $do == "preview" ) {  // Get dir listing, thumbs, w/checkboxes
        if ( $numprocess == 10 ) $numopts = "<option selected>10</option>";
        else $numopts = "<option>10</option>";
        if ( $numprocess == 25 ) $numopts .= "<option selected>25</option>";
        else $numopts .= "<option>25</option>";
        if ( $numprocess == 50 ) $numopts .= "<option selected>50</option>";
        else $numopts .= "<option>50</option>";
        if ( $numprocess == 100 ) $numopts .= "<option selected>100</option>";
        else $numopts .= "<option>100</option>";

        if ( $dthumbs == "yes" ) $thumopts = "<option selected>yes</option><option>no</option>";
        else $thumopts = "<option selected>no</option><option>yes</option>";

        $middle = "<tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>
            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"><tr><Td>
            <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\"><tr><Th colspan=\"4\" bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\"
            size=\"".$Globals{'fontmedium'}."\">Add Photos - Select Photos / Configure Properties</th></tr>
            <Tr><Td colspan=\"4\" width=\"100%\" height=\"2\" bgcolor=\"#000000\"></td></tr><Tr>
            <th bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Image</font></th>
            <th bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Add?</font></th>
            <th bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Category</font></th>
            <th bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Optional Text</font></th></tr>";

        $photocount=0;
        $userid = $photopath;
        $inpath = $Globals{'zipuploaddir'}."/$userid";
        $openpath = $inpath;
        $imgurl = $furl;        
        $maxp = $numprocess;

        $disptitle = $deftitle;
        $dispdesc = $defdesc;
        $defcatname = "";

        $querya = "SELECT catname FROM categories WHERE id=$defcat";
        $catq = ppmysql_query($querya, $link);
        
        if ( $catq ) {
            list ( $defcatname ) = mysql_fetch_row($catq);
            ppmysql_free_result($catq);
        }

        catmoveopt(0);

        if ( $handle = opendir( $openpath ) ) {
            while ( $realname = readdir( $handle ) ) {
                if (( $realname != ".") && ( $realname != ".." ) ) {
                    $filepath = $inpath."/$realname";
                    $chkrealname  = fixfilenames($realname);

                    if ( strcmp($chkrealname, $realname) != 0 ) {
                        $newfile = $inpath."/$chkrealname";
                        @rename($filepath, $newfile);
                        $realname = $chkrealname;
                        $filepath = $newfile;
                    }
                    
                    $size = filesize( $filepath );
                    $theext = get_ext($realname);

                    $querya="SELECT id FROM photos WHERE userid=$userid AND bigimage='$realname'";
                    $catq = ppmysql_query($querya,$link);
                    $imgchk = mysql_num_rows($catq);
 
                    if ( $imgchk != 0 ) {
                        // Image is a duplicate
                        $filenoext = get_filename( $realname );

                        $x = 0;                        
                        while ( $imgchk != 0 ) {
                            $x++;
                            $newfile = "$filenoext$x.$theext";
                            $newfilepath = $inpath."/$newfile";                            
                            
                            $querya="SELECT id FROM photos WHERE userid=$userid AND bigimage='$newfile'";
                            $catq = ppmysql_query($querya,$link);
                            $imgchk = mysql_num_rows($catq);
                            
                            if ( $imgchk == 0 ) {
                                @rename($filepath, $newfilepath);
                                $realname = $newfile;
                                $filepath = $newfilepath;
                            }
                        }
                    }

                    $image_stats = @getimagesize( $filepath );
                    $imagewidth = $image_stats[0];
                    $imageheight = $image_stats[1];
                    $type = $image_stats[2];

                    $photocount++;
                    $thumb = "";
                        
                    if ( is_image($realname) && $photocount < $maxp && $size > 0 ) {
                        if ( $dthumbs == "yes") {
                            create_thumb( $realname, $filepath, $defcat );
                            $thumb = "<A target=\"_blank\" href=\"".$Globals{'zipuploadurl'}."/$userid/$thumbnail\"><img border=\"0\" src=\"".$Globals{'datadir'}."/$defcat/$thumbnail\"></a><Br>";
                        }

                        $middle .= "<input type=\"hidden\" name=\"imgname".$photocount."\" value=\"$realname\">
                            <tr><Td bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\"><b><center>$thumb$realname</center></td><Td
                            bgcolor=\"#FFFFFF\"><center><input type=\"checkbox\" CHECKED value=\"1\" name=\"add$photocount\"></center></td>
                            <Td bgcolor=\"#FFFFFF\"><select name=\"cat$photocount\" style=\"font-size: 9pt; background: FFFFFF;\">
                            <option value=\"$defcat\" selected>$defcatname</option>$catoptions</select>
                            <td bgcolor=\"#FFFFFF\"><Table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\"><tr><Td><font face=\"".$Globals{'mainfonts'}."\"
                            size=\"".$Globals{'fontsmall'}."\">Title:</td><Td><input type=\"text\" size=\"30\" name=\"title$photocount\"
                            value=\"$disptitle\"
                            style=\"font-size: 9pt; background: FFFFFF;\"></td></tr><tr><Td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\">Description:</td><Td>
                            <input type=\"text\" size=\"30\" name=\"desc$photocount\" value=\"$dispdesc\" style=\"font-size:
                            9pt; background: FFFFFF;\"></td></tr></table></td></tr>";
                    }
                    elseif ( is_multimedia($realname) && $photocount < $maxp && $size > 0) {
                        $photocount++;

                        if ( $dthumbs == "yes") {
                            //create_thumb( $realname, $filepath, $defcat );
                            $thumb = "<A target=\"_blank\" href=\"".$Globals{'zipuploadurl'}."/$userid/$realname\"><img border=\"0\" src=\"".$Globals{'idir'}."/video.jpg\"></a><Br>";
                        }

                        $middle .= "<input type=\"hidden\" name=\"imgname".$photocount."\" value=\"$realname\">
                            <tr><Td bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\"><b><center>$thumb$realname</center></td><Td
                            bgcolor=\"#FFFFFF\"><center><input type=\"checkbox\" CHECKED value=\"1\" name=\"add$photocount\"></center></td>
                            <Td bgcolor=\"#FFFFFF\"><select name=\"cat$photocount\" style=\"font-size: 9pt; background: FFFFFF;\">
                            <option value=\"$defcat\" selected>$defcatname</option>$catoptions</select>
                            <td bgcolor=\"#FFFFFF\"><Table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\"><tr><Td><font face=\"".$Globals{'mainfonts'}."\"
                            size=\"".$Globals{'fontsmall'}."\">Title:</td><Td><input type=\"text\" size=\"30\" name=\"title$photocount\"
                            value=\"$deftitle\"
                            style=\"font-size: 9pt; background: FFFFFF;\"></td></tr><tr><Td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\">Description:</td><Td>
                            <input type=\"text\" size=\"30\" name=\"desc$photocount\" value=\"$defdesc\" style=\"font-size:
                            9pt; background: FFFFFF;\"></td></tr></table></td></tr>";
                    }
                }
            }
        }

        if ($photocount == 0) {
            $middle .= "<Tr><Td colspan=\"4\" bgcolor=\"#FFFFFF\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><center>No more images found.<br><b><a href=\"".$Globals{'maindir'}."/index.php\">Click here</a> to return to main menu.</b></td></tr>";
        }

        $middle .= "<input type=\"hidden\" name=\"furl\" value=\"$imgurl\">
            <input type=\"hidden\" name=\"deftitle\" value=\"$deftitle\">
            <input type=\"hidden\" name=\"defdesc\" value=\"$defdesc\">
            <input type=\"hidden\" name=\"defcat\" value=\"$defcat\">
            <input type=\"hidden\" name=\"keywords\" value=\"$keywords\">
            <input type=\"hidden\" name=\"photopath\" value=\"$userid\">
            <input type=\"hidden\" name=\"origcat\" value=\"$defcat\">
            <input type=\"hidden\" name=\"tuserid\" value=\"$userid\">
            <input type=\"hidden\" name=\"ppaction\" value=\"addphotos\">
            <input type=\"hidden\" name=\"do\" value=\"process\">
            <input name=\"thecount\" value=\"$photocount\" type=\"hidden\">
            <input name=\"inpath\" value=\"$inpath\" type=\"hidden\"><Tr><td bgcolor=\"#FFFFFF\" colspan=\"4\"><center>";

        if ( $photocount != 0 )
            $middle .= "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Show thumbnails? <select name=\"dthumbs\">$thumopts</select></font><br>
                <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Number of thumbnails to process next? <select name=\"numprocess\">$numopts</select><p>";

        $middle .= "<input type=\"submit\" value=\"Process\"></form>
            </td></tr></table></td></tr></table>";
    }

    $output .= "$middle</td></tr></table></td></tr></table>".$Globals{'cright'}."$footer";

    print $output;
    exit;
}

?>

