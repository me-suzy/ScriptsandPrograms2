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

// variable setup

if (empty($slideshow)) $slideshow=0;
if (empty($pperpage)) $pperpage="";
if (empty($size)) $size="";

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if ( !empty($photo) ) {
    if ( ($username == "" || $username == "Unregistered") && $Globals{'reqregister'} == "yes" ) {
        dieWell( "You must be a registered user to view images!<p><b>To register click on the REGISTER button in the menu above.</b>");
        exit;
    }

    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $query = "SELECT maxposts FROM user WHERE userid=$userid";
        $pperpagedb = ppmysql_query($query,$db_link);
        list( $pperpage ) = mysql_fetch_row($pperpagedb);
        ppmysql_free_result($pperpagedb);
    }

    if ($pperpage == "-1" || $pperpage == "") {
        $pperpage=$Globals{'defaultposts'};
    }

    $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos WHERE id=$photo";
    $rows = ppmysql_query($query,$link);
    
    if ( !$rows ) {
        dieWell( "Photo $photo not found in the database!" );
        exit;
    }

    list( $id, $user, $iuserid, $cat, $date, $title, $desc, $keywords, $bigimage, $width, $height, $filesize, $views, $medwidth, $medheight, $medsize, $approved, $imgrating) = mysql_fetch_row($rows);
    ppmysql_free_result( $rows );
    
    if ( $cat < 3000 ) {
        if ( $ugview{$cat} == 1 ) {
            dieWell( "You do not have permission to view this image.");
            exit;
        }
    }

    if ( $cat > 2999 ) {
        $query = "SELECT id,albumname,parent,isprivate,password FROM useralbums WHERE id=$cat";
        $resultb = ppmysql_query($query,$link);
        list( $thecatid, $thecatname, $aparent, $isprivate, $password ) = mysql_fetch_row($resultb);
        
        if ( ($isprivate == "yes" && $userid != $aparent) && $adminedit != 1 ) {
            if ( empty($papass) ) $papass = "";
            
            if ( $password != $papass ) {
                dieWell( "You do not have permission to view this photo." );
                exit;
            }
        }
    }
    else {
        $query = "SELECT catname FROM categories where id=$cat";
        $resulta = ppmysql_query($query,$link);
        list( $thecatname ) = mysql_fetch_row($resulta);
        ppmysql_free_result($resulta);        
    }

    //
    // Next and Previous images for display
    //
    if ( empty($sort) ) $sort = 1;
    $query = "SELECT * FROM sort WHERE sortid=$sort";
    $resultc = ppmysql_query($query,$link);
    list($sortid, $sortname, $sortc) = mysql_fetch_row($resultc);
    ppmysql_free_result( $resultc );
    $sortcode = "$sortc";    
    
    if ( $cat == 500 ) 
        $query = "SELECT id FROM photos WHERE cat=500 AND userid=$iuserid $sortcode"; 
    else 
        $query = "SELECT id FROM photos WHERE cat=$cat $sortcode";
        
    $rows = ppmysql_query($query,$link);
    $ref=0; $first_image=0; $last_image=0; $ids = array(0); $curr=0;

    while ( $resultp = mysql_fetch_array($rows) ) {
        $ref++;
        $ids[$ref]=$resultp['id'];
        if ( $ids[$ref] == $photo ) {
            $curr = $ref;
        }
    }
    ppmysql_free_result($rows);

    $previous_image = 0;
    $next_image = 0;

    if ( $curr > 1 ) {
         $previous_image = $ids[$curr-1];
    }
    if ( $curr != $ref ) {
        $next_image = $ids[$curr+1];
    }

    if ( $previous_image == 0 ) $previous_image=$ids[$ref];
    if ( $next_image == 0 ) $next_image=$ids[1];

    $botbuster="";
    if ( $Globals{'botbuster'} == "yes" )
        $botbuster = "<a href=\"".$Globals{'domain'}."/".mt_srand ((double) microtime() * 1000000).mt_rand(10,99)."images".mt_rand(1000,9999)."/".mt_rand(1000000,9999999).".jpg\"></a>";

    $prevlink = "<font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$previous_image&amp;papass=$papass&amp;sort=$sort\"><img border=\"0\" src=\"".$Globals{'idir'}."/previmg.gif\" alt=\"Previous image in category\"></a></font>$botbuster";
    $nextlink = "<font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$next_image&amp;papass=$papass&amp;sort=$sort\"><img border=\"0\" src=\"".$Globals{'idir'}."/nextimg.gif\" alt=\"Next image in category\"></a></font>";

    // End to get Next and Previous images for display

    if ( $slideshow == 1 ) {
        $slideurl = $Globals{'maindir'}."/showphoto.php?photo=$next_image&amp;slideshow=1&amp;papass=$papass&amp;sort=$sort";
        $slidestop = $Globals{'maindir'}."/showphoto.php?photo=$photo&amp;papass=$papass&amp;sort=$sort";
        $slidecode = "<font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><A href=\"$slidestop\"><img border=\"0\" src=\"".$Globals{'idir'}."/stopshow.gif\" alt=\"Stop the slideshow\"></a></font>";

        if ( $slideshow == 1 ) {
            if ( empty($slidedelay) ) $slidedelay = 4;
            $tslidedelay = (1000 * $slidedelay);
            
            $headslide="<script language=\"JavaScript\"><!--
                t=1; function dorefresh() { u=new String(\"$slideurl\");
                ti=setTimeout(\"dorefresh();\",$tslidedelay); if (t>0) { t-=1; }
                else { clearTimeout(ti); window.location=u.replace(\"#\",\"&t=\"+parseInt(10000*Math.random())+\"#\"); }
                } window.onLoad=dorefresh();
                --></script><noscript><meta http-equiv=\"Refresh\" content=\"$slidedelay; URL=$slideurl\"></noscript>";

            $prevlink=""; $nextlink="";
        }
    }
    else {
        $headslide="";
        $slidecode="";
        if ( $next_image != 0 ) {
            $slideurl = $Globals{'maindir'}."/slideshow.php?photo=$next_image&amp;sort=$sort";
            $slidecode = "<A href=\"$slideurl\"><img border=\"0\" src=\"".$Globals{'idir'}."/slideshow.gif\" alt=\"Start a slideshow of images\"></a>";
        }
    }

    if ( $slideshow != 1 ) {
        // for childsub, we need to set these globals
        $ppuser = $iuserid; 
        $tcat = $user;
        childsub($cat);
        $childnav = "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'catfontsize'}."\"><a href=\"".$Globals{'maindir'}."/index.php\">Home</a> $childnav</font>";
    }
    else
        $childnav="";

    $uploadquery = "?cat=$cat";

    if ( $width == 0 && $height == 0 )
        $sizecode = "n/a";
    else
        $sizecode = "$width x $height";
        
    if ( $slideshow != 1 ) topmenu();        
    
    if ( $slideshow == 1 && $Globals{'slidehead'} == "no" ) {
        $theader="";
    }
    elseif ( $cat < 3000 ) {
        $query = "SELECT id,header,footer,headtags,catname FROM categories WHERE id=$cat";
        $resultb = ppmysql_query($query,$link);

        if ( $resultb ) {
            list( $thecatid, $newheader, $newfooter, $newheadtags, $thecatname ) = mysql_fetch_row($resultb);

            if ( $newheadtags != "" && file_exists($newheadtags) ) {
                $filearray = file($newheadtags);
                $headtags = implode( " ", $filearray );
            }

            if ( $newheader != "" && file_exists($newheader) ) {
                $filearray = file($newheader);
                $theader = implode( " ", $filearray );
            }

            if ( $newfooter != "" && file_exists($newfooter) ) {
                $filearray = file($newfooter);
                $footer = implode( " ", $filearray );
            }    
            
            ppmysql_free_result( $resultb );        
        }
    }

    // this is the once place we modify the header to possibly include a timeout
    // for the slideshow, so we need to recreate our own header here
    $header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n
        <html>
        <head>
        <title>".$Globals{'galleryname'}." - titlereplace - Powered by PhotoPost</title>\n
        $nocachetag\n
        $headtags\n
        $javapopup\n
        $headslide\n
        </head>
        $theader";

    if ( !empty($title) )
        $header = str_replace( "titlereplace", " $title ", $header );
    else
        $header = str_replace( "titlereplace", " $bigimage ", $header );
        
    $output = "$header<p><center>                    
        <p><table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"40%\">$menu2</td>
        <td width=\"60%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>
        <table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\" align=\"center\">
        <tr><td>
        <table cellpadding=\"0\" cellspacing=\"1\" border=\"0\"  width=\"100%\" bgcolor=\"".$Globals{'headcolor'}."\">
        <tr><td>
        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"  width=\"98%\" align=\"center\"><tr>
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\">
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">$childnav</font></td>
        <Td bgcolor=\"".$Globals{'headcolor'}."\" valign=\"middle\" align=\"right\">
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">$prevlink&nbsp;$slidecode&nbsp;$nextlink</font>
        </td></tr></table></td></tr><!-- CyKuH [WTN] -->";

    $count=0;
    $theext = substr($bigimage,strlen($bigimage) - 4,4);
    $filename = $bigimage;
    $filename = str_replace( $theext, "", $filename);
    $dispmed = 0; $altlink="";

    $profilelink = get_profilelink( $iuserid );

    if ( $imgrating && $Globals{'allowrate'} == "yes" ) {
        for ( $x = 1; $x <= $imgrating; $x++ ) {
            if ( $x == 1 ) $rating = "<img src=\"".$Globals{'idir'}."/star.gif\" alt=\"$imgrating stars\">";
            else $rating .= "<img src=\"".$Globals{'idir'}."/star.gif\" alt=\"$imgrating stars\">";
        }
    }
    else {
        $rating = "None";
    }

    if ($approved == "1") {
        if ($medsize > 0) {
            if ($size != "big") {
                $dispmed=1;
                $altlink = "<center><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Click on image to view larger image</font></center><Br>";
            }
            else {
                $altlink = "<center><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\"><B><a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$photo&amp;papass=$papass\">View Smaller Image</a></b></font></center><Br>";
            }
        }

        $filesize=$filesize/1024;                
        $filesize=sprintf("%1.1f", $filesize);
        $filesize = number_format($filesize)."kb";
        
        if ($Globals{'bigsave'} == "yes") {
            if ($dispmed > 0) {
                $medsize = $medsize/1024;
                $medsize = sprintf("%1.1f", $medsize);
                $medsize = number_format($medsize)."kb";
                
                $filesize = "<A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass\">$medsize</a>, <A
                    href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;size=big&amp;papass=$papass\">$filesize</a>";

                if ( $Globals{'onthefly'} == 1 ) {
                    $imgdisp = "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$photo&amp;size=big&amp;papass=$papass\"><img
                        width=\"$medwidth\" height=\"$medheight\" src=\"".$Globals{'maindir'}."/watermark.php?file=$cat/$iuserid$filename-med$theext\" border=\"0\" alt=\"\"></a>";
                }
                else {
                    $imgdisp = "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$photo&amp;size=big&amp;papass=$papass\"><img
                        width=\"$medwidth\" height=\"$medheight\" src=\"".$Globals{'datadir'}."/$cat/$iuserid$filename-med$theext\" border=\"0\" alt=\"\"></a>";
                }
            }
            else {
                if ( is_multimedia($bigimage) == 1 ) {
                     $mmthumb = $Globals{'datadir'}."/$cat/$iuserid$filename-thumb.jpg";
                     $dirthumb = $Globals{'datafull'}."/$cat/$iuserid$filename-thumb.jpg";
                     
                     if ( !file_exists($dirthumb) ) $mmthumb = $Globals{'idir'}."/video.jpg";
                     
                     $imgdisp = "<a href=\"".$Globals{'datadir'}."/$cat/$iuserid$filename$theext\"><img src=\"$mmthumb\" border=\"0\" alt=\"\"></a>
                        <br><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">This is a video, click on the thumbnail to download</font>";
                }
                else {
                    if ($filesize != "") {
                        if ( $Globals{'onthefly'} == 1 ) {
                            $imgdisp = "<img width=\"$width\" height=\"$height\" src=\"".$Globals{'maindir'}."/watermark.php?file=$cat/$iuserid$filename$theext\" border=\"0\" alt=\"\">";
                        }
                        else {
                            $imgdisp = "<img width=\"$width\" height=\"$height\" src=\"".$Globals{'datadir'}."/$cat/$iuserid$filename$theext\" border=\"0\" alt=\"\">";
                        }
                    }
                    else {
                        $imgdisp = "<img src=\"".$Globals{'datadir'}."/$cat/$iuserid$filename-thumb$theext\" border=\"0\" alt=\"\">";
                    }
                }
            }
        }
        else {
            $imgdisp = "<img src=\"".$Globals{'datadir'}."/$cat/$iuserid$filename-thumb$theext\" border=\"0\" alt=\"\">";
        }
    }
    else {
        $imgdisp = "<img width=\"100\" height=\"75\" src=\"".$Globals{'idir'}."/ipending.gif\" border=\"0\" alt=\"\">";
    }

    $output .= "<Tr><Td bgcolor=\"".$Globals{'maincolor'}."\" valign=\"top\" align=\"center\"><br>$imgdisp<br>
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\"><b>$title</b></font><br>";

    $admindisplay=""; $adminopts="";

    if ( $slideshow != 1 ) {
        if ( $adminedit == 1 || ($userid == $iuserid && $Globals{'userdel'} == "yes") ) {
            $selected = $cat;
            catmoveopt(0);
            $adminopts = "<tr align=\"center\" valign=\"top\"><Td><form method=\"post\" action=\"".$Globals{'maindir'}."/adm-photo.php\">
                <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">
                Move photo to: <select name=\"catmove\" style=\"font-size: 9pt; background: FFFFFF;\"><option
                selected></option>$catoptions</select></font></td><td><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">&nbsp;&nbsp;<input
                type=\"checkbox\" name=\"pdelete\" value=\"yes\"> Delete
                Photo?&nbsp;&nbsp;<input type=\"hidden\" name=\"ppaction\" value=\"movedel\"><input type=\"hidden\" name=\"pid\" value=\"$id\">
                <input type=\"hidden\" name=\"origcat\" value=\"$cat\"></font></td><td>
                <input type=\"submit\" value=\"Submit Change\" style=\"font-size: 8pt;\"></form></td></tr>";
        }

        if ( $usercomment == 1 && $Globals{'allowrate'} == "yes" ) {
            $ratedisplay = "<tr align=\"center\" valign=\"top\"><td colspan=\"3\">
                <form name=\"theform\" method=\"post\" action=\"".$Globals{'maindir'}."/comments.php\">
                <select name=\"rating\" onChange=\"submit();\">
                <option selected>Rate this photo</option>
                <option value=\"5\">5 - Excellent</option>
                <option value=\"4\">4 - Great</option>
                <option value=\"3\">3 - Good</option>
                <option value=\"2\">2 - Fair</option>
                <option value=\"1\">1 - Poor</option>
                </select>
                <input type=\"hidden\" name=\"cat\" value=\"$cat\">
                <input type=\"hidden\" name=\"password\" value=\"$password\">
                <input type=\"hidden\" name=\"puserid\" value=\"$userid\">
                <input type=\"hidden\" name=\"photo\" value=\"$photo\">
                <input type=\"hidden\" name=\"message\" value=\" \">
                <input type=\"hidden\" name=\"post\" value=\"new\">
                </form></td></tr>";
        }

        if ( !empty($adminopts) || !empty($ratedisplay) ) {
            $admindisplay = "<br><Table width=\"100%\">$adminopts$ratedisplay</table>";
        }

        $ppdate = formatppdate( $date );
        $desc = convert_markups( $desc );
        $desc = ConvertReturns( $desc );

        $output .= "<br>$altlink<p>
            <Table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td bgcolor=\"".$Globals{'detailbgcolor'}."\">
            <Table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\"><tr>
            <td bgcolor=\"#E6E6E6\">
            <Table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><Tr>
            <Td bgcolor=\"".$Globals{'detailbgcolor'}."\" align=\"right\">
            <font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">";

        if ( $userid != "" ) {
            $query = "SELECT id FROM favorites WHERE photo=$id AND userid=$userid";
            $resultf = ppmysql_query($query, $link);
            $isfav = mysql_num_rows($resultf);
            
            if ( $isfav == 0 )
                $pmenu .= "<A href=\"".$Globals{'maindir'}."/addfav.php?photo=$id&do=add\">Add to Favorites</a> | ";
            else
                $pmenu .= "<A href=\"".$Globals{'maindir'}."/addfav.php?photo=$id&do=del\">Remove from Favorites</a> | ";
        }
        
        if ( $usercomment == 1 && $Globals{'allowpost'} == "yes" ) {
            $pmenu .= "<A href=\"".$Globals{'maindir'}."/comments.php?photo=$id\">Post a Comment</a>";
        }

        if ($userid != "") {
            if ( !empty($pmenu) )
                $pmenu .= " | ";
            
            $pmenu .= "<A href=\"".$Globals{'maindir'}."/reportphoto.php?report=$id\">Report Photo</a>";
        }

        if ( $Globals{'enablecard'} == "yes" && $userid != "" ) {
            if ( !empty($pmenu) )
                $pmenu .= " | ";

            $pmenu .= "<a href=\"".$Globals{'maindir'}."/ecard.php?ecard=$id\">Send as e-Card</a>";
        }

        if ($Globals{'usenotify'} == "yes" && $userid > 0) {
            $query = "SELECT id FROM notify WHERE userid=$userid AND photo=$photo LIMIT 1";
            $results = ppmysql_query($query,$link);
            list( $notifyid ) = mysql_fetch_row($results);
            ppmysql_free_result($results);

            if ( !empty($pmenu) )
                $pmenu .= " | ";

            if ($notifyid != "") {
                $pmenu .= "<A href=\"".$Globals{'maindir'}."/comments.php?notify=off&notifyid=$notifyid&photo=$photo\">Disable Email Updates</a>";
            }
            else {
                $pmenu .= "<A href=\"".$Globals{'maindir'}."/comments.php?notify=on&photo=$photo\">Receive Email Updates</a>";
            }
        }

        if ( $adminedit == 1 || ($userid == $iuserid && $ueditpho == 1) ) {
            if ( !empty($pmenu) )
                $pmenu .= " | ";

            $pmenu .= "<A href=\"".$Globals{'maindir'}."/editphoto.php?phoedit=$id\">Edit Photo</a>";
        }

        // find similiar posts
        if ( empty($keywords) ) $keywords = $title;
        
        $keylinks = "";
        $keys = explode( " ", $keywords );
        $keys = array_unique ($keys );
        
        foreach($keys as $eachkey) {
            if ( !empty($eachkey) && $eachkey != "the" && $eachkey != "a" && $eachkey != "but" && $eachkey != "are" && $eachkey != "and" )
                $keylinks .= "<A href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;stype=1&amp;thumb=1&amp;si=$eachkey\">$eachkey</a> ";
        }

        $output .= "$pmenu</font></td></tr></table></td>
            </tr><Tr><Td><Table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\"><Tr>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Poster:</font></td>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">
            <A href=\"$profilelink\"><b>$user</b></a> 
            <font size=\"".$Globals{'fontsmall'}."\">(<A href=\"".$Globals{'maindir'}."/showgallery.php?thumb=1&amp;stype=2&amp;si=$user&amp;cat=500&amp;perpage=12&amp;sort=1&amp;ppuser=$iuserid\">see all of $user's photos</a>)</font></font></td>
            </tr><Tr>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Views:</font></td>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$views</font></td>
            </tr><Tr>";
            
        if ( $Globals{'allowrate'} == "yes" ) {
            $output .= "<Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Rating:</font></td>
                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$rating</font></td>
                </tr><Tr>";
        }
        
        $output .= "<td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Date:</font></td>
            <td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$ppdate</font></td>
            </tr><tr>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Filesize:</font></td>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$filesize</font></td>
            </tr><tr>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Dimensions:</font></td>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$sizecode</font></td>
            </tr><tr>
            <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Keywords:</font></td>
            <td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$keylinks</font></td>
            </tr>
            <Tr><Td bgcolor=\"".$Globals{'detailcolor'}."\"><font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">Description:</font></td><td
            bgcolor=\"".$Globals{'detailcolor'}."\"><font
            size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">$desc</font></td></tr></table>
            </tr></table>";
    }
    else {
        $output .= "<br>";
    }

    $output .= "</td></tr></table>$admindisplay</td></tr></table></td></tr></table>";

    if ( $slideshow != 1 ) {
        $query = "SELECT id FROM comments WHERE photo=$photo";
        $results = ppmysql_query($query,$link);
        $comcount = mysql_num_rows($results);
    
        if ( $comcount == 0 ) {
            $compages = 0;
        }
        else {
            if ($pperpage > 0) {
                $compages=($comcount/$pperpage);
            }
            else {
                $pperpage=$Globals{'defaultposts'};
                $compages=($comcount/$pperpage);
            }
        }
    
        if (intval($compages) < $compages) {
            $compages=intval($compages)+1;
        }
        else {
            $compages=intval($compages);
        }
    
        if ( IsSet($cpage) ) {
            $cstartnumb=($cpage*$pperpage)-$pperpage+1;
        }
        else {
            $cpage=1;
            $cstartnumb=1;
        }
    
        $cc=0; $ckcolor=0; $posts=""; $comq = "";
    
        $query = "SELECT id,username,userid,date,rating,comment FROM comments WHERE photo=$photo ORDER BY date ASC";
        $rows = ppmysql_query($query,$link);
    
        while ( list( $id, $user, $cuserid, $date, $rating, $commenttext ) = mysql_fetch_row($rows) ) {
    
            $yescomments="yes";
            if ($rating > 0 && $Globals{'allowrate'} == "yes" ) {
                $ratingdisp = "Rating: <b>$rating/5</b>&nbsp;";
            }
            else {
                $ratingdisp="";
            }
            $cc++;
    
            if ($cc >= $cstartnumb) {
                if ($cc < ($cstartnumb+$pperpage)) {
                    $profilelink = get_profilelink( $cuserid );
    
                    $cclock = formatpptime( $date );
                    $ppdate = formatppdate( $date );
    
                    $query = "SELECT id FROM photos WHERE userid=$cuserid LIMIT 1";
                    $results = ppmysql_query($query,$link);
                    list( $phoid ) = mysql_fetch_row($results);
                    ppmysql_free_result($results);                        
    
                    $cuser="Unregistered";
                    $clocation="";
                    $ctitle="";
                    $cposts="";
                    $regdate="";
                    $ugallery="";
                    $isonline="";
                    $hpage="";
    
                    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
                        if ($cuserid != 0) {
                            $query = "SELECT username,homepage,usertitle,posts,joindate FROM user WHERE userid=$cuserid LIMIT 1";
                            $results = ppmysql_query($query,$db_link);                            
                            list( $cuser, $chomepage, $ctitle, $cposts, $regdate ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );
    
                            list($rsec,$rmin,$rhour,$rmday,$rmon,$ryear,$rwday,$ryday,$risdst) = localtime($regdate);
                            $ryear=$ryear+1900;
                            $rmon++;
                            $regdate="$rmon/$ryear";
    
                            $query = "SELECT field2 FROM userfield WHERE userid=$cuserid LIMIT 1"; 
                            $results = mysql_query($query, $db_link); 
                            
                            if ( $results ) { 
                                $ufields = mysql_fetch_array($results); 
                                $clocation = $ufields['field2']; 
                                ppmysql_free_result($results); 
                            } 
    
                            $query = "SELECT host FROM session WHERE userid=$cuserid LIMIT 1";
                            $results = ppmysql_query($query,$db_link);
                            list( $conline ) = mysql_fetch_row($results);
                            ppmysql_free_result($results);                            
                        }
                        
                        if ($phoid != "") {
                            $ugallery = "<a href=\"".$Globals{'maindir'}."/showgallery.php?ppuser=$cuserid&amp;cat=500&amp;thumb=1\"><img alt=\"Visit this user's gallery.\" border=\"0\"
                                src=\"".$Globals{'idir'}."/gallery4.gif\"></a>";
                        }
    
                        if ( $cuserid != 0 ) {
                            if ( $chomepage != "" ) {
                                $hpage = "<a
                                    href=\"$chomepage\" target=\"_blank\"><img src=\"".$Globals{'vbulletin'}."/images/home.gif\" alt=\"Visit ".$cuser."'s homepage!\"
                                    border=\"0\"></a>";
                            }
                            if ($conline == "") {
                                $isonline = "<img src=\"".$Globals{'vbulletin'}."/images/off.gif\" border=\"0\" alt=\"$cuser is offline\" align=\"absmiddle\"> ";
                            }
                            else {
                                $isonline = "<img src=\"".$Globals{'vbulletin'}."/images/on.gif\" border=\"0\" alt=\"$cuser is online\" align=\"absmiddle\"> ";
                            }
                        }
                    }
    
                    if ($Globals{'vbversion'} == "Internal") {
                        if ($cuserid != 0) {
                            $query = "SELECT username,homepage,posts,joindate,location FROM users WHERE userid=$cuserid LIMIT 1";
                            $results = ppmysql_query($query, $db_link);
                            list( $cuser, $chomepage, $cposts, $regdate, $clocation ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );

                            list($rsec,$rmin,$rhour,$rmday,$rmon,$ryear,$rwday,$ryday,$risdst) = localtime($regdate);
                            $ryear=$ryear+1900;
                            $regdate="$rmon/$ryear";
                        }
                    }
    
                    if ($Globals{'vbversion'} == "phpBB") {
                        if ($cuserid != 0) {
                            $query = "SELECT username,user_website,user_posts,user_rank,user_regdate FROM users WHERE user_id=$cuserid LIMIT 1";
                            $results = ppmysql_query($query,$db_link);
                            list( $cuser, $chomepage, $cposts, $regdate, $ctitlenum ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );
    
                            $query = "SELECT rank_title FROM ranks WHERE rank_id=$ctitlenum LIMIT 1";
                            $results = ppmysql_query($query,$db_link);
                            list( $ctitle ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );
    
                            $query = "SELECT sess_id FROM sessions WHERE user_id=$cuserid LIMIT 1";
                            $results = ppmysql_query($query,$db_link);
                            list( $conline ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );                            
    
                            if ( $chomepage != "" ) {
                                $hpage = "<a
                                    href=\"$chomepage\" target=\"_blank\"><img src=\"".$Globals{'vbulletin'}."/images/www_icon.gif\" alt=\"Visit ".$cuser."'s homepage!\"
                                    border=\"0\"></a>)";
                            }
                        }
                        if ($phoid != "") {
                            $ugallery = "<a href=\"".$Globals{'maindir'}."/showgallery.php?ppuser=$cuserid&amp;cat=500&amp;thumb=1\"><img alt=\"Visit this user's gallery.\" border=\"0\"
                                src=\"".$Globals{'idir'}."/gallery/phbb.gif\"></a>";
                        }
                    }
    
                    if ($Globals{'vbversion'} == "phpBB2") {
                        if ($cuserid != 0) {
                            if ( !empty( $Globals{'dprefix'} ) ) {
                                $utable=$Globals{'dprefix'} ."_users";
                                $rtable=$Globals{'dprefix'} ."_ranks";
                            }
                            else {
                                $utable="users";
                                $rtable="ranks";
                            }
                            $query = "SELECT $utable.username,$utable.user_website,$utable.user_posts,$rtable.rank_title,$utable.user_regdate FROM ";
                            $query .= "$utable LEFT JOIN $rtable ON $utable.user_rank = $rtable.rank_id WHERE $utable.user_id=$cuserid LIMIT 1";
                            $results = ppmysql_query($query, $db_link);
    
                            if ( $results ) {
                                list( $cuser, $chomepage, $cposts, $ctitle, $regdate ) = mysql_fetch_row($results);
                                ppmysql_free_result( $results );
    
                                list($rsec,$rmin,$rhour,$rmday,$rmon,$ryear,$rwday,$ryday,$risdst) = localtime($regdate);
                                $rmon++;
                                $ryear=1900+$ryear;
                                $regdate = "$rmon/$rmday/$ryear";
                            }
                        }
                    }
    
                    if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
                        if ($cuserid != 0) {
                            $query = "SELECT U_Username,U_Homepage,U_Totalposts,U_Title,U_Registered FROM w3t_Users WHERE U_Number=$cuserid LIMIT 1";
                            $results = ppmysql_query($query,$db_link);
                            list( $cuser, $chomepage, $cposts, $ctitle, $tdate ) = mysql_fetch_row($results);
                            ppmysql_free_result( $results );
                            
                            list($rsec,$rmin,$rhour,$rmday,$rmon,$ryear,$rwday,$ryday,$risdst) = localtime($tdate);
                            $rmon++;
                            $ryear=1900+$ryear;
                            $regdate = "$rmon/$rmday/$ryear";                            
                        }
                    }
    
                    if ($Globals{'vbversion'} == "Internal" || $Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6" ) {
                        if ($phoid != "") {
                            $ugallery = "<a href=\"".$Globals{'maindir'}."/showgallery.php?ppuser=$cuserid&amp;cat=500&amp;thumb=1\"><img alt=\"Visit this user's gallery.\"  border=\"0\" src=\"".$Globals{'idir'}."/gallery.gif\"></a>";
                        }
                        if ($chomepage != "" ) {
                            $chomepage = str_replace("http://", "", $chomepage);
                            $hpage = "<a href=\"http://$chomepage\" target=\"_blank\"><img src=\"".$Globals{'idir'}."/www.gif\" alt=\"Visit ".$cuser."'s homepage!\"
                                border=\"0\"></a>";
                        }
                    }
    
                    if ($regdate != "") $regdate = "<br><br>Registered: $regdate";
                    if ($cposts != "") $cposts = "<br>Posts: $cposts";
                    if ($clocation != "") $clocation = "<br>Location: $clocation";
                    if ($ctitle != "") $ctitle = "<br>$ctitle";
    
                    if ($ckcolor == 1) {
                        $fillcolor = $Globals{'altcolor1'};
                        $ckcolor = 0;
                    }
                    else {
                        $fillcolor = $Globals{'altcolor2'};
                        $ckcolor = 1;
                    }
    
                    $commenttext = convert_markups($commenttext);
                    $commenttext = ConvertReturns($commenttext);
    
                    $posts .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
                        align=\"center\"><tr><td>
                        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"100%\">
                        <tr><td bgcolor=\"$fillcolor\" width=\"175\" valign=\"top\" nowrap>
                        <font face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\" size=\"".$Globals{'fontmedium'}."\">
                        <b>$cuser</b>
                        <font size=\"".$Globals{'fontsmall'}."\">$ctitle
                        $regdate$clocation$cposts</font></font></td>
    
                        <td bgcolor=\"$fillcolor\" width=\"100%\" valign=\"top\">
                        <p><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><Td><font face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\"
                        size=\"".$Globals{'fontmedium'}."\">$commenttext</font></td><Td align=\"right\" valign=\"top\">
                        <font face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'commentstext'}."\" size=\"".$Globals{'fontmedium'}."\">$ratingdisp</font></td></tr></table>
    
                        </td></tr><tr>
                        <td bgcolor=\"$fillcolor\" width=\"175\" height=\"16\" nowrap><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\">$isonline
                        $ppdate <font color=\"".$Globals{'commentstext'}."\">$cclock</font></font></td>
    
                        <td bgcolor=\"$fillcolor\" width=\"100%\" valign=\"middle\" height=\"16\">
                        <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                        <tr valign=\"bottom\"><td>";
    
                    if ( $cuserid > 0 ) {
                        if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
                            $posts .= "<a href=\"$profilelink\" target=\"_blank\"><img src=\"".$Globals{'vbulletin'}."/images/profile.gif\"
                                border=\"0\" alt=\"Click Here to See the Profile for $cuser\"></a> <a
                                href=\"$privatelink\"><img
                                src=\"".$Globals{'vbulletin'}."/images/sendpm.gif\" border=\"0\" alt=\"Click here to Send $username a Private Message\"></a>  $hpage
                                <a href=\"".$Globals{'vbulletin'}."/search.php?s=&action=finduser&amp;userid=$cuserid\"><img src=\"".$Globals{'vbulletin'}."/images/find.gif\"
                                border=\"0\"
                                alt=\"Find more posts by $cuser\"></a><!--PhotoPost, copyright All, Enthusiast, Inc.--> <a
                                href=\"".$Globals{'vbulletin'}."/member2.php?s=&action=addlist&amp;userlist=buddy&amp;userid=$cuserid\"><img
                                src=\"".$Globals{'vbulletin'}."/images/buddy.gif\" border=\"0\"
                                alt=\"Add $cuser to your buddy list\"></a> $ugallery
                                </td>";
                        }
                        
                        if ($Globals{'vbversion'} == "Internal" || $Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
                            $posts .= "<a href=\"$profilelink\" target=\"_blank\"><img src=\"".$Globals{'idir'}."/profile.gif\"
                                border=\"0\" alt=\"Click Here to See the Profile for $cuser\"></a>
                                $hpage<!--PhotoPost, copyright All, Enthusiast, Inc.-->
                                $ugallery
                                </td>";
                        }
                        
                        if ( $Globals{'vbversion'} == "phpBB" || $Globals{'vbversion'} == "phpBB2" ) {
                            $posts .= "<a href=\"$profilelink\" target=\"_blank\"><img src=\"".$Globals{'idir'}."/profile.gif\"
                                border=\"0\" alt=\"Click Here to See the Profile for $cuser\"></a>
                                $hpage<!--PhotoPost, copyright All, Enthusiast, Inc.-->
                                $ugallery
                                </td>";
                        }
                    }
       
                    $posts .= "<td align=\"right\" nowrap>";
                    
                    if ( $adminedit == 1 || ($userid == $cuserid && $ueditposts == 1) ) {                           
                        $posts .= "<a href=\"comments.php?photo=$photo&amp;cedit=$id\"><img src=\"".$Globals{'idir'}."/edit.gif\" border=\"0\" alt=\"Edit/Delete Message\"></a>";
                    }
                    else {
                        $posts .= "&nbsp;";
                    }
                    
                    $posts .= "</td></tr></table></td></tr></table></td></tr></table>";
                }
            }
        }
        
        if ( $rows )
            ppmysql_free_result( $rows );
    
        if ( $usercomment == 1 && $Globals{'allowpost'} == "yes" ) {
            $comq = "<p><form name=\"theform\" method=\"post\" action=\"".$Globals{'maindir'}."/comments.php\">
                    <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"
                    width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr>
                    <td>
                    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"100%\">
                    <tr align=\"center\">
                    <td colspan=\"1\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font
                    face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
                    <b>Add your comments</b></font>
                    </td>
                    <td colspan=\"1\" align=\"right\" bgcolor=\"".$Globals{'headcolor'}."\"><font
                    face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\">
                    <a href=\"javascript:PopUpHelp('comments.php')\">help</a></font>
                    </td>            
                    </tr>";
    
            $comq .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">
                    Username</font></td><td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">$username
                    </font></td></tr>";
                    
            if ( $Globals{'allowrate'} == "yes" ) {                    
                $comq .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Rate this photo overall</font></td>
                    <td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\">
                    <select name=\"rating\">
                    <option value=\"0\" selected>Rate this photo</option>
                    <option value=\"5\">5 - Excellent</option>
                    <option value=\"4\">4 - Great</option>
                    <option value=\"3\">3 - Good</option>
                    <option value=\"2\">2 - Fair</option>
                    <option value=\"1\">1 - Poor</option>
                    </select></td></tr>";
            }
                
            $comq .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Comments:<br><br>
                    <font size=\"".$Globals{'fontsmall'}."\"><a href=\"javascript:PopUpHelp('ubbcode.php')\">UBB Code legend</a><br>
                    <a href=\"javascript:PopUpHelp('smilies.php')\">Smilies legend</a></font></font></td><td
                    bgcolor=\"".$Globals{'maincolor'}."\">
                    <textarea name=\"message\" cols=\"40\" rows=\"5\"></textarea></td></tr>";
        
            $comq .= "<Tr><Td colspan=\"3\" bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\">
                    <input type=\"hidden\" name=\"cat\" value=\"$cat\">
                    <input type=\"hidden\" name=\"password\" value=\"$password\">
                    <input type=\"hidden\" name=\"puserid\" value=\"$userid\">
                    <input type=\"hidden\" name=\"photo\" value=\"$photo\">";
    
            $comq .= "<input type=\"hidden\" name=\"post\" value=\"new\"><input type=\"submit\" value=\"Submit Post\">";
            $comq .= "</td></tr></table></td></tr></table></form>";
        }
    }

    $cheader = "<p><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\" width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\" width=\"175\" nowrap><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'headfontcolor'}."\"><b>Author</b></font></td>
        <td bgcolor=\"".$Globals{'headcolor'}."\" width=\"100%\">
        <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
        <td width=\"100%\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'headfontcolor'}."\"><b>Thread</b></font></td>
        <td nowrap><a href=\"comments.php?photo=$photo\">$postreply</a>&nbsp;</td>
        </tr>
        </table></td></tr></table></td></tr></table>";

    // begin pages/nav system ##
    $comnav="";

    if ($compages > 1) {
        $comnav .= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\"><Tr bgcolor=\"".$Globals{'maincolor'}."\"><Td width=\"40%\"></td>
            <Td><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\"><B>Page:&nbsp;</b> ";
        $thestart="";

        if ($cpage < 11) {
            $thestart=1;
        }
        if ($cpage > 10) {
            $thestart=$cpage/10;
            $thestart=intval($thestart);
            $thestart=$thestart*10;
        }
        $theend=$thestart+9;

        for ($p=$thestart;$p<=$compages;$p++) {
            if ($p != $thestart) {
                $comnav .= " | ";
            }

            if ($cpage != $p) {
                if ($p == ($theend+1)) {
                    $thispage="$p>";
                }
                else {
                    $thispage="$p";
                }
                $comnav .= "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$photo&amp;cpage=$p&amp;pperpage=$pperpage&amp;papass=$papass#poststart\">$thispage</a>";
            }
            if ($p >$theend) {
                break;
            }
            if ($cpage == $p) {
                $comnav .= "<b>$p</b>";
            }
        }
        if ($cpage < $compages) {
            $nextpage=$cpage+1;
            $more = "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$photo&amp;cpage=$nextpage&amp;sort=$sortparam&amp;perpage=$pperpage&amp;papass=$papass\"><img
                height=\"16\" width=\"63\" alt=\"More Items\"
                border=\"0\" src=\"".$Globals{'idir'}."/more.gif\"></a>";
        }
        else {
            $more = "&nbsp";
        }

        $comnav .= "</td><td width=\"20%\" align=\"center\">$more</td></tr></table>";
    }
    // end pages/nav ###

    if ( $Globals{'ipcache'} != 0 ) {
        $ipaddress = findenv("REMOTE_ADDR");
        $query = "SELECT userid,date,photo FROM ipcache WHERE ipaddr='$ipaddress' AND type='view' AND photo='$photo' LIMIT 1";
        $result = ppmysql_query($query, $link);
        $numfound = mysql_num_rows($result);
        
        if ( $numfound > 0 ) {
            list( $tuserid, $lastdate, $photo ) = mysql_fetch_row($result);
            
            list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
            $mon = $mon + 1;
            $mytime = mktime($hour,$min,$sec,$mon,$mday,$year);
            
            $hour = $hour - $Globals{'ipcache'};  
            $timeout = mktime($hour,$min,$sec,$mon,$mday,$year);

            if ( $lastdate < $timeout ) {
                $query = "UPDATE photos SET views=views+1 WHERE id=$photo";
                $result = ppmysql_query($query,$link);

                if ( $userid > 0 && $Globals{'vbversion'} == "Internal" ) {
                    $query = "UPDATE users SET views=views+1 WHERE userid=$userid";
                    $result = ppmysql_query($query,$db_link);
                }
                
                $query = "DELETE FROM ipcache WHERE date < $timeout";
                $result = ppmysql_query($query,$link);
                
                $query = "INSERT INTO ipcache (userid,ipaddr,date,type,photo) VALUES ('$tuserid', '$ipaddress', '$mytime', 'view', '$photo')";
                $result = ppmysql_query($query,$link);
            }
        }
        else {
            list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
            $mon = $mon + 1;
            $mytime = mktime($hour,$min,$sec,$mon,$mday,$year);
            
            $query = "INSERT INTO ipcache (userid,ipaddr,date,type,photo) VALUES ('$tuserid', '$ipaddress', '$mytime', 'view', '$photo')";
            $result = ppmysql_query($query,$link);
            
            $query = "UPDATE photos SET views=views+1 WHERE id=$photo";
            $result = ppmysql_query($query,$link);
            
            if ( $userid > 0 && $Globals{'vbversion'} == "Internal" ) {                
                $query = "UPDATE users SET views=views+1 WHERE userid=$userid";
                $result = ppmysql_query($query,$db_link);
            }
        }
    }
    else {
        $query = "UPDATE photos SET views=views+1 WHERE id=$photo";
        $result = ppmysql_query($query,$link);
        
        if ( $userid > 0 && $Globals{'vbversion'} == "Internal" ) {
            $query = "UPDATE users SET views=views+1 WHERE userid=$userid";
            $result = ppmysql_query($query,$link);
        }
    }

    if ( $posts != ""  ) {
        print "$output$cheader$posts$comnav$comq<p>".$Globals{'cright'}."$footer";
    }
    else {
        print "$output$comq</td></tr></table><p>".$Globals{'cright'}."$footer";
    }
} // end individual photo display ###

// Closing connection
mysql_close($link);
mysql_close($db_link);

?>

