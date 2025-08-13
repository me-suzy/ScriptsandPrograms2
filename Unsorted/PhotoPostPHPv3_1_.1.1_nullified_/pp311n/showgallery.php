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
include("login-inc.php");

if (empty($stype)) $stype="";
if (empty($si)) $si="";
if (empty($ppuser)) $ppuser="";
if (empty($thumb)) $thumb=0;

if ( !isset($cat) ) {
    dieWell( "Script call malformed. Notify your Administrator." );
    exit;
}

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if ($ugview{$cat} == 1 ) {
    dieWell( "You do not have permission to view this category." );
    exit;
}

if ( $userid > 0 ) {
    list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
    $mon = $mon + 1;    
    $lasttimeon = mktime($hour,$min,$sec,$mon,$mday,$year);
    
    $laston = "REPLACE INTO laston VALUES($cat,$userid,$lasttimeon)";
    $resultb = ppmysql_query($laston, $link);    
}

$perpage1x = $Globals{'thumbcols'};
$perpage3x = ($perpage1x * 3);
$perpage4x = ($perpage1x * 4);
$perpage5x = ($perpage1x * 5);
$perpage6x = ($perpage1x * 6);

if ( IsSet($perpage) ) {
    if ($perpage > $perpage6x)
        $perpage = $perpage6x;
}
else
    $perpage = $perpage3x;

if ( IsSet($page) ) {
    $startnumb = ($page*$perpage)-$perpage+1;
}
else {
    $page=1;
    $startnumb=1;
}

if ( isset($cat) ) {
    if ( $thumb != 0 ) {
        $thecat = $cat;

        // do the sort box //
        $query = "SELECT * FROM sort";
        if ($thecat == "500") {
            if ( empty($ppuser) && empty($si) ) {
                $query = "SELECT * FROM sortmemb";
            }
        }
        $resultc = ppmysql_query($query,$link);

        if ( empty($sort) ) $sortparam = 1;
        else $sortparam = $sort;

        $sortoptions = ""; $sortdefault=""; $catrows="";

        while ( list($sortid, $sortname, $sortc) = mysql_fetch_row($resultc) ) {
            if ($sortparam != $sortid) {
                $sortoptions .= "<OPTION value = $sortid>$sortname</OPTION>";
            }
            else {
                $sortdefault = "<option selected value=\"$sortid\">$sortname</option>";
                $sortcode = "$sortc";
            }

            if ($sortdefault == "") {
                $sortdefault = "<option selected>Date (newest first)</option>";
            }
        }
        ppmysql_free_result( $resultc );

        $sort = "<select onChange=\"submit();\" name=\"sort\" style=\"font-size: 9pt; background: FFFFFF;\">$sortdefault$sortoptions</select>";

        // end sort box //

        if ( $thecat < 3000 ) {
            $query = "SELECT id,header,footer,headtags,catname,thumbs FROM categories WHERE id=$thecat";
            $resultb = ppmysql_query($query,$link);

            if ( $resultb ) {
                list( $thecatid, $newheader, $newfooter, $newheadtags, $thecatname, $catthumbs ) = mysql_fetch_row($resultb);

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
                
                $header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n
                    <html>
                    <head>
                    <title>".$Globals{'galleryname'}." - titlereplace - Powered by PhotoPost</title>\n
                    $nocachetag\n
                    $headtags\n
                    $javapopup\n
                    </head>
                    $theader";
            }
            ppmysql_free_result( $resultb );
            
        }
        else {
            $catthumbs = "yes";
            
            $query = "SELECT id,albumname,parent,isprivate,password FROM useralbums WHERE id=$thecat";
            $resultb = ppmysql_query($query,$link);

            if ( $resultb ) {
                list( $thecatid, $thecatname, $parent, $isprivate, $password ) = mysql_fetch_row($resultb);
                ppmysql_free_result( $resultb );
                
                if ( ($isprivate == "yes" && $userid != $parent) && $adminedit != 1 ) {
                    if ( empty($papass) ) $papass = "";
                    if ( $password != $papass ) {
                        dieWell( "You do not have permission to view this Personal Album." );
                        exit;
                    }
                }
            }
        }
        
        $output = "$header<center>";

        $keycheck=""; $ucheck="";
        $albums=""; $personal=0;
        $subcats=""; $albumrow="";

        if ( $stype == "1" ) $keycheck="CHECKED";
        if ( $stype == "2" ) $ucheck="CHECKED";
        if ( $stype == "" ) $keycheck="CHECKED";
        
        $searchterms = $si;
        $inputuser = $ppuser;
        $incat = $cat;
        
        if ($ppuser != "") {
            if ( $cat == 500 ) {
                list( $tcat, $tmail ) = get_username($ppuser);
                $thecatname = "$tcat's Gallery";
                $output = str_replace( "titlereplace", "$tcat's Gallery", $output );
            }
            elseif ( $cat > 3000 ) {
                list( $tcat, $tmail ) = get_username($ppuser);
                $output = str_replace( "titlereplace", "$tcat's Personal Album", $output );
            }
        }
        else {
            if ( $cat == "999" ) {
                list( $tcat, $tmail ) = get_username($userid);
                $thecatname = "$tcat's Favorites";
            }
            elseif ( $cat == "998" ) {
                $thecatname = "All Images";
            }
            elseif ( $cat == "997" ) {
                $thecatname = "Posts Past Day";
            }
            elseif ( $cat == "996" ) {
                $thecatname = "Posts Past 7 Days";
            }
            elseif ( $cat == "995" ) {
                $thecatname = "Posts Past 14 Days";
            }
            elseif ( $cat == "991" ) {
                $thecatname = "Search Results";
            }
            else {
                $query = "SELECT id,catname FROM categories WHERE id='$cat'";
                $ctitleq = ppmysql_query($query, $link);
                if ( $ctitleq ) {
                    list( $catid, $thecatname ) = mysql_fetch_row($ctitleq);
                    ppmysql_free_result( $ctitleq );
                }
            }                        
            
            $output = str_replace( "titlereplace", "$thecatname", $output );            
        }        
        
        childsub($incat);
        $childnav = "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'catfontsize'}."\"><a href=\"".$Globals{'maindir'}."/index.php\">Home</a> $childnav</font>";        

        topmenu();        

        $searchbox = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><Tr>
            <td align=\"right\"><!-- CyKuH [WTN] -->
            <input type=\"hidden\" name=\"thumb\" value=\"1\">
            <input type=\"hidden\" name=\"cat\" value=\"$cat\">            
            <input type=\"text\" name=\"si\" style=\"font-size: 8pt;\" size=\"15\" value=\"$si\">
            <input type=\"submit\" value=\"Search\" style=\"font-size: 9pt;\">
            </td></tr><tr><td colspan=\"6\" align=\"right\">
            <font color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Recent Posts:
            <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=997&amp;thumb=1\">last day</a>
            &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=996&amp;thumb=1\">last 7 days</a>
            &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=995&amp;thumb=1\">last 14 days</a>
            &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=998&amp;thumb=1\">all posts</a>
            </font></td>
            </table>";
    
        if ($cat == "500") {
            if ($si == "") {
                if ( $ppuser == "" ) {
                    $thumb = 2;
                }
                else {
                    $query = "SELECT id,albumname,description,isprivate FROM useralbums WHERE parent=$ppuser";
                    $arows = ppmysql_query($query,$link);

                    if ( $arows > 0 ) {
                        while ( list( $subid, $subalbumname, $subalbumdesc, $isprivate ) = mysql_fetch_row($arows) ) {                            
                            if ( empty($subalbumdesc) ) $subalbumdesc = "&nbsp;";
                            
                            if ( $isprivate == "no" || ($isprivate == "yes" && ($userid == $ppuser || $adminedit == 1)) ) {
                                $albumrow .= "<tr><Td width=\"30%\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><A
                                    href=\"".$Globals{'maindir'}."/showgallery.php?cat=$subid&amp;ppuser=$ppuser&amp;thumb=1\">$subalbumname</a></font></td><Td align=\"left\" width=\"70%\"
                                    bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"
                                    color=\"".$Globals{'maintext'}."\">$subalbumdesc</font></td>
                                    </tr>\n";
                            }
                        }
                        
                        if ( $arows ) 
                            ppmysql_free_result( $arows );                        

                        if ( $albumrow != "" ) {
                            $albums .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"
                                bgcolor=\"".$Globals{'bordercolor'}."\" width=\"".$Globals{'tablewidth'}."\" align=\"center\">
                                <tr><td>
                                <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\">
                                <tr align=\"center\">
                                <td align=\"left\" colspan=\"$cols\" bgcolor=\"".$Globals{'headcolor'}."\">
                                <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                                <Tr><Td>
                                <font size=\"".$Globals{'fontlarge'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\">$childnav</font>
                                </td><td align=\"right\">
                                $searchbox</td>
                                </tr></table>
                                </td>
                                <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"100%\">                                
                                <Tr><Td bgcolor=\"".$Globals{'headcolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\"
                                face=\"".$Globals{'mainfonts'}."\"><b>Personal Albums</b></font></td>
                                <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\" nowrap><font color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\"
                                face=\"".$Globals{'mainfonts'}."\"><B>Description</b></font></td>
                                </tr>$albumrow</table></td></tr></table><p>";
                        }

                        $personal=1;
                    }
                }
            }
        }
        else {
            if ( empty($si) ) {
                catrow( $cat );
    
                if ( !empty($catrows) ) {
                    $subcats = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"
                        bgcolor=\"".$Globals{'bordercolor'}."\" width=\"".$Globals{'tablewidth'}."\" align=\"center\">
                        <tr><td>
                        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\">
                        <tr align=\"center\">
                        <td align=\"left\" colspan=\"$cols\" bgcolor=\"".$Globals{'headcolor'}."\">
                        <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                        <Tr><Td>
                        <font size=\"".$Globals{'fontlarge'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\">$childnav</font>
                        </td><td align=\"right\">
                        $searchbox</td>
                        </tr></table></td></tr></table>
                        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"100%\">
                        <tr align=\"center\">
                        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\"
                        color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Category</b>
                        </font></td><Td bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\"
                        color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Photos</b></font></td><Td
                        bgcolor=\"".$Globals{'headcolor'}."\" align=\"center\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
                        size=\"".$Globals{'fontsmall'}."\"><b>Comments</b></font></td><Td bgcolor=\"".$Globals{'headcolor'}."\">
                        <font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Last Comment</b></font></td>
                        <Td bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
                        size=\"".$Globals{'fontsmall'}."\"><B>Last Photo Upload</b></font></td></tr>";
    
                    $subcats .= $catrows;
                    $subcats .= "</table></td></tr></table><p>";
                }
            }
        }

        if ( !empty($incat) ) {
            if ( $incat == "500" )
                $cols = "6";
            else
                $cols = $Globals{'thumbcols'};
        }
        else {
            $cols = $Globals{'thumbcols'};
        }

        $output .= "<p><table cellpadding=\"10\" cellspacing=\"0\" align=\"center\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
            <Td valign=\"middle\" width=\"50%\">$menu2</td>
            <td width=\"50%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>";

        if ( ($incat < 990 || $incat > 2999) && empty($si) ) {
            if ( $Globals{'memformat'} == "no" && ($incat == "500" && $ppuser != "") ) {
                if ( $Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "no" ) {
                    display_gallery("latest", $ppuser);
                }
                else {
                    $output .= "<br>";                    
                }

                if ( $si == "" ) {
                    list( $tname, $tmail ) = get_username($ppuser);
                    $output .= "<center><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b><A href=\"".$Globals{'maindir'}."/showgallery.php?thumb=1&amp;stype=2&amp;si=$tname&amp;cat=500&amp;sort=1&amp;ppuser=$ppuser\">Click here to see all of $tname's photos</a></b></font></center><p>";
                }
            }
            elseif ( $Globals{'memformat'} == "no" && ($Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "no") ) {
                display_gallery("latest", "", $incat);
            }
        }

        if ( !empty($albums) ) {
            $output .= "<form method=\"get\" action=\"".$Globals{'maindir'}."/showgallery.php\">$albums";
            $galleryhead = "<tr><td>
                <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">";            
        }
        elseif ( !empty($subcats) ) {
            $output .= "<form method=\"get\" action=\"".$Globals{'maindir'}."/showgallery.php\">$subcats";
            $galleryhead = "<tr><td>
                <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">";
        }
        else {
            $output .= "<form method=\"get\" action=\"".$Globals{'maindir'}."/showgallery.php\">";
            $galleryhead = "<tr><td>
                <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
                <tr align=\"center\">
                <td align=\"left\" colspan=\"$cols\" bgcolor=\"".$Globals{'headcolor'}."\">
                <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                <Tr><Td>
                <font size=\"".$Globals{'fontlarge'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\">$childnav</font>
                </td><td align=\"right\">$searchbox</td></tr>
                </table>
                </td></tr>";            
        }

        $output .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\" align=\"center\">
            $galleryhead";

        if ($incat != "" && $thumb != 1) {
            if ($incat != "500" ) {
                $space = catrow($incat);
            }

            if ( IsSet($space) ) {
                $catrows .= "</table></td></tr></table><p><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"
                    bgcolor=\"".$Globals{'bordercolor'}."\" width=\"100%\" align=\"center\"><tr><td><table cellpadding=\"0\"
                    cellspacing=\"1\" border=\"0\" width=\"100%\"><tr align=\"center\"><td colspan=\"5\" align=\"left\">";
            }
            $output .= $catrows;
        }

        $output .= "<";
        $output .= "!--7565-->";
        $output .= "<tr><td bgcolor=\"".$Globals{'navbarcolor'}."\" colspan=\"$cols\">
            <Table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
            <tr align=\"center\">
            <td width=\"50%\" align=\"left\"><font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\"
            color=\"".$Globals{'searchtext'}."\"><b>Per Page:</b></font>
            <select onChange=\"submit();\" name=\"perpage\" style=\"font-size: 9pt; background: FFFFFF;\">
            <option selected>$perpage</option><option>$perpage3x</option><option>$perpage4x</option><option>$perpage5x</option><option>$perpage6x</option></select>
            </td>

            <td width=\"50%\" align=\"right\"><font size=\"".$Globals{'fontsmall'}."\"
            face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'searchtext'}."\"><b>Sort by:</b> $sort
            <input type=\"hidden\" name=\"stype\" value=\"$stype\">
            <input type=\"hidden\" name=\"ppuser\" value=\"$inputuser\">
            </font></td>
            </tr>
            </table></td></tr><tr>";

        // If we're not in the member gallery cat, then print thumbs..
        // Otherwise, print a list of users.
        $phrase="";
        $sword = $sterms;                
        
        if ( $thumb != 2 ) {
            if ($si != "") {
                $sterms = trim($si);
                $sword = $sterms;                
                $searchterms = explode(" ", $sterms);
                $scount=0;
                $totalterms = count($searchterms);
                $totalterms++;

                foreach ($searchterms as $key) {
                    $scount++;
                    if ($scount > 1) {
                            $phrase .= " AND ";
                    }
                    
                    $key = addslashes( $key );                    
                    $phrase .= "(title LIKE \"% $key%\" OR description LIKE \"% $key%\" OR keywords LIKE \"% $key%\" OR bigimage LIKE \"% $key%\")";
                    $phrase .= " OR (title LIKE \"$key%\" OR description LIKE \"$key%\" OR keywords LIKE \"$key%\" OR bigimage LIKE \"$key%\")";
                }                
            }

            if ( $personal == 1 ) {
                $exclude_cat .= " AND cat < 3000";
            }
            elseif ( $cat > 3000 ) {
                $exclude_cat .= " AND cat=$cat";
            }

            if ( $cat == 999 ) {
                // My Favorites
                $query = "SELECT f.userid,p.id,p.user,p.userid,p.cat,p.date,p.title,p.description,p.keywords,
                    p.bigimage,p.width,p.height,p.filesize,p.views,p.medwidth,p.medheight,p.medsize,p.approved,p.rating
                    FROM favorites f, photos p
                    WHERE f.userid=$userid AND f.photo=p.id $sortcode";
                $queryv = ppmysql_query($query, $link);                        
            }
            elseif ( $cat == 998 ) {
                // All Images
                if ( empty($si) ) {
                    $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE bigimage!='' $exclude_cat $sortcode";
                    $queryv = ppmysql_query($query, $link);                        
                }
                else {
                    $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE ($phrase) AND bigimage!='' $exclude_cat $sortcode";
                    $queryv = ppmysql_query($query, $link);                        
                }
            }
            elseif ( $cat == 997 ) {
                // Last 1 days
                $days = 1;
                
                list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
                $mon = $mon + 1;
                $hour = $hour - ($days * 24);  
                $searchdate = mktime($hour,$min,$sec,$mon,$mday,$year);
                
                $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE bigimage!='' AND date > $searchdate $exclude_cat $sortcode";
                $queryv = ppmysql_query($query, $link);                        
            }
            elseif ( $cat == 996 ) {
                // Last 7 days
                $days = 7;
                
                list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
                $mon = $mon + 1;
                $hour = $hour - ($days * 24);  
                $searchdate = mktime($hour,$min,$sec,$mon,$mday,$year);
                
                $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE bigimage!='' AND date > $searchdate $exclude_cat $sortcode";
                $queryv = ppmysql_query($query, $link);                        
            }
            elseif ( $cat == 995 ) {
                // Last 14 days
                $days = 14;
                
                list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
                $mon = $mon + 1;
                $hour = $hour - ($days * 24);  
                $searchdate = mktime($hour,$min,$sec,$mon,$mday,$year);
                
                $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE bigimage!='' AND date > $searchdate $exclude_cat $sortcode";
                $queryv = ppmysql_query($query, $link);                        
            } 
            elseif ($si == "") {
                if ($ppuser == "") {
                    $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                        WHERE bigimage!='' AND cat=$thecat $exclude_cat $sortcode";
                }
                else {
                    if ( $Globals{'memformat'} == "yes" ) {
                        $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                            WHERE bigimage!='' AND userid=$ppuser $exclude_cat $sortcode";
                    }
                    else {
                        $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating FROM photos
                            WHERE bigimage!='' AND userid=$ppuser AND cat=$thecat $exclude_cat $sortcode";
                    }
                }
                $queryv = ppmysql_query($query,$link);
            }
            else {
                if ($stype == "") {
                    $stype=1;
                }

                if ($stype == "1") {
                    if ($thecat != 500) {
                        $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating
                            FROM photos WHERE ($phrase) AND cat=$thecat $exclude_cat $sortcode";
                        $queryv = ppmysql_query($query,$link);
                    }
                    else {
                        if ($phrase != "") {
                            $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating
                                FROM photos WHERE $phrase $exclude_cat $sortcode";
                            $queryv = ppmysql_query($query,$link);
                        }
                        else {
                            if ($exclude_cat) {
                                $exclude_cat = str_replace("AND", "WHERE", $exclude_cat);
                            }
                            $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating
                                FROM photos $exclude_cat $sortcode";
                            $queryv = ppmysql_query($query,$link);
                        }
                    }
                }
                else {
                    if ($thecat != 500) {
                        $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating
                            FROM photos WHERE user LIKE '$sword' AND cat=$thecat $exclude_cat $sortcode";
                        $queryv = ppmysql_query($query,$link);
                    }
                    else {
                        $query = "SELECT id,user,userid,cat,date,title,description,keywords,bigimage,width,height,filesize,views,medwidth,medheight,medsize,approved,rating
                            FROM photos WHERE user LIKE '$sword' $exclude_cat $sortcode";
                        $queryv = ppmysql_query($query,$link);
                    }
                }
            }

            $rowcnt = mysql_num_rows($queryv);

            if ($rowcnt == "0") {
                if ( $catthumbs == "yes" ) {
                    if ($ugview{$thecat} != 1 ) {
                        $noresults = "<center><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">No photos found.  If you searched, try fewer or less specific
                            keywords.<p></font></center>";
                        }
                        else {
                            $noresults = "<center><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">You do not have permission to view the images in this category.<p></font></center>";
                        }
                }
                else {
                    $noresults = "<center><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\">This category does not contain any images.<p></font></center>";
                }
            }
            else {
                $noresults="";
            }

            pagesystem($rowcnt);

            $count=0; $cntresults=0;
            $numcols = $Globals{'thumbcols'}+1;
            $pwidth = intval(100/($numcols-1));

            while ( $row = mysql_fetch_row($queryv) ) {
                if ( $cat == 999 ) 
                    list( $favid, $id, $tuser, $tuserid, $pcat, $date, $title, $desc, $keywords, $bigimage, $width, $height, $filesize, $views, $medwidth, $medheight, $medsize, $approved, $imgrating ) = $row;
                else
                    list( $id, $tuser, $tuserid, $pcat, $date, $title, $desc, $keywords, $bigimage, $width, $height, $filesize, $views, $medwidth, $medheight, $medsize, $approved, $imgrating ) = $row;

                $is_private = "no";
                if ( $pcat != $thecat ) $is_private = is_image_private( $pcat );
                    
                if ( $is_private == "no" ) {
                    if ( $width == 0 && $height == 0 )
                        $sizecode = "n/a";
                    else
                        $sizecode = "$width x $height";
    
                    $cntresults++;
                    $filesize = $filesize/1024;
                    $filesize = sprintf("%1.1f", $filesize);
                    $filesize = $filesize."k";
    
                    if ($cntresults >= $startnumb) {
                        if ($cntresults < ($startnumb+$perpage)) {
                            // Print out the thumbnail photo along with the title, username, etc
                            // PERL->PHP (had to +1 for some reason)
    
                            $querya = "SELECT username FROM comments WHERE photo=$id ORDER BY date DESC";
                            $queryz = ppmysql_query($querya,$link);
                            list( $lastposter ) = mysql_fetch_row($queryz);
                            $comcount = mysql_num_rows($queryz);
                            ppmysql_free_result($queryz);
    
                            $count++;
                            if ($count == $numcols) {
                                $output .= "</tr><Tr>";
                                $count = 1;
                            }
    
                            $theext = substr($bigimage, strlen($bigimage) - 4,4);
                            $filename = $bigimage;
                            $filename = str_replace( $theext, "", $filename);
    
                            $ppdate = formatppdate( $date );
    
                            if ($medsize > 0) {
                                $medsize = $medsize/1024;
                                $medsize = sprintf("%1.1f", $medsize);
                                $medsize = $medsize."k";
                                $ilink = $Globals{'datadir'}."/$pcat/$tuserid$filename-med$theext";
                                $biglink = $Globals{'datadir'}."/$pcat/$tuserid$filename$theext";
                                $fsizedisp = "<A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass&amp;sort=$sortparam\">$medsize</a>, <A
                                    href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;size=big&amp;papass=$papass&amp;sort=$sortparam\">$filesize</a>";
                            }
                            else {
                                $ilink = $Globals{'datadir'}."/$pcat/$tuserid$filename$theext";
                                $fsizedisp = "<A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass&amp;sort=$sortparam\">$filesize</a>";
                            }
    
                            // Find out if a photo has comments
    
                            if ($comcount != "0") {
                                $comline = "<font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\"><a
                                    href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass&amp;sort=$sortparam\">$comcount comments</a></font>";
                            }
                            else {
                                $comline = "<font size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'detailfontcolor'}."\"
                                    face=\"".$Globals{'mainfonts'}."\">No comments</font>";
                            }
    
                            // get the rating
                            if ($imgrating && $Globals{'allowrate'} == "yes" ) {
                                for ( $x = 1; $x <= $imgrating; $x++ ) {
                                    if ( $x == 1 ) $rating = "<img src=\"".$Globals{'idir'}."/star.gif\" alt=\"$imgrating stars\">";
                                    else $rating .= "<img src=\"".$Globals{'idir'}."/star.gif\" alt=\"$imgrating stars\">";
                                }
                            }
                            else {
                                $rating = "None";
                            }
                            
                            $thumbrc = get_imagethumb( $bigimage, $pcat, $tuserid, $approved );
                            $profilelink = get_profilelink( $tuserid );
    
                            $output .= "<Td bgcolor=\"".$Globals{'maincolor'}."\" valign=\"top\" align=\"center\" width=\"$pwidth%\">
                                <Table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><td align=\"center\" height=\"125\">
                                <A href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass&amp;sort=$sortparam\">$thumbrc</a></td></tr></table>
                                <Table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"".$Globals{'detailbgcolor'}."\" width=\"90%\"><tr><Td>
                                <Table cellpadding=\"2\" cellspacing=\"1\" width=\"100%\"><tr>
                                <td colspan=\"2\" bgcolor=\"".$Globals{'detailbgcolor'}."\"><font size=\"".$Globals{'fontmedium'}."\"
                                face=\"".$Globals{'mainfonts'}."\"><!-- CyKuH [WTN] --><A
                                href=\"".$Globals{'maindir'}."/showphoto.php?photo=$id&amp;papass=$papass&amp;sort=$sortparam\">$title</a>&nbsp;</font></td></tr><Tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">User:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\"><A href=\"$profilelink\">$tuser</a></font></td>
                                </tr><Tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Views:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$views</font></td>
                                </tr><Tr>";
                                
                            if ( $Globals{'allowrate'} == "yes" ) {
                                $output .= "<Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Rating:</font></td>
                                    <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$rating</font></td>
                                    </tr><Tr>";
                            }
                                
                            $output .= "<td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Date:</font></td>
                                <td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$ppdate</font></td>
                                </tr><tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Filesize:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$fsizedisp</font></td>
                                </tr><tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Dimensions:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$sizecode</font></td>
                                </tr>";
                                
                            if ( $Globals{'allowpost'} == "yes" ) {
                                $output .="<tr><Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Comments:</font></td>
                                    <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$comline</font></td>
                                    </tr>";
                            }
                                
                            $output .= "</table></td></tr></table></td>";
                        }
                    }
                }
            }
            
            if ( $queryv )
                ppmysql_free_result( $queryv );
            
            $squares = $Globals{'thumbcols'}-$count;

            for ($v=1; $v <= $squares; $v++) {
                $output .= "<td bgcolor=\"".$Globals{'maincolor'}."\" width=\"$pwidth%\">&nbsp;</td>";
            }

            if ( $posternav != "" ) $posternav = "$posternav<p>";

            $output .= "</tr></table></td></tr></table></form><p>$posternav$noresults";

            if ( ($incat < 990 || $incat > 2999) && empty($si) ) {
                if ( $Globals{'memformat'} == "no" ) {
                    if ( $incat == "500" && ($Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "yes") ) {
                        display_gallery("latest", $inputuser);
                        $output .= "<p>";
                    }
                    elseif ( $Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "yes" ) {
                        display_gallery("latest", "", $incat);
                        $output .= "<p>";
                    }

                    if ( $incat == "500" && $ppuser != "" ) {
                        display_gallery("most_views", $inputuser);
                        $output .= "<p>";
                    }
                    elseif ( $Globals{'dispopular'} == "yes" ) {
                        display_gallery("most_views", "", $incat);
                        $output .= "<p>";
                    }

                    if ( $incat == "500" && $ppuser != "" ) {
                        display_gallery("random", $inputuser);
                        $output .= "<p>";
                    }
                    elseif ( $Globals{'disrandom'} == "yes" ) {
                        display_gallery("random", "", $incat);
                        $output .= "<p>";
                    }
                }
            }

            print "$output<p>".$Globals{'cright'}."$footer";
            exit;
        }
        else {
            $query = "SELECT user,userid,SUM(views) AS tviews,COUNT(*) AS pcount,MAX(lastpost) AS maxlast,MAX(date) AS
                maxdate,date,SUM(filesize) AS tfilesize,id FROM photos GROUP BY user $sortcode";
            $queryz = ppmysql_query($query,$link);
            $rowcnt = mysql_num_rows($queryz);

            pagesystem($rowcnt);

            if ($rowcnt == "0") {
                $noresults = "<font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'maintext'}."\"><Br>No photos found.  If you searched, try fewer or less specific
                    keywords.<p></font></b>";
            }
            else {
                $noresults="";
            }

            $uout=""; $cc=0;
            $count=0; $cntresults=0;
            $numcols = $Globals{'thumbcols'}+1;
            //$numcols = 7;
            $pwidth = intval(100/($numcols-1));

            while ( list($theuser, $theuserid, $views, $uphotos, $ulast, $maxdate, $date, $tfilesize, $pid) = mysql_fetch_row($queryz)) {
                $cc++;
                if ($cc >= $startnumb) {
                    if ($cc < ($startnumb+$perpage)) {
                        $query = "select comments.id from comments,photos where photos.id=comments.photo AND photos.userid=$theuserid";
                        $comcountdb = ppmysql_query($query,$link);
                        $comcount = mysql_num_rows($comcountdb);

                        //$lastphotime = $ulast+$soffset;
                        $cclock = formatpptime( $ulast );
                        $ppdate = formatppdate( $ulast );

                        $lpprint = "$ppdate $cclock";
                        $mthumb = "";
                        
                        if ($Globals{'membthumb'} == "yes") {
                            $query = "SELECT bigimage,id,cat FROM photos WHERE userid=$theuserid AND approved=1 $exclude_cat ORDER BY date DESC"; // CyKuH [WTN]
                            $resulta = ppmysql_query($query,$link);
                            
                            while( list( $bigimage, $phoid, $pcat ) = mysql_fetch_row($resulta) ) {
                                $is_private = is_image_private( $pcat );
                                if ( $is_private == "no" ) break;
                            }
                            ppmysql_free_result( $resulta );
                            
                            if ( $is_private == "yes" ) {
                                $bigimage="";
                                $mthumb = "<img border=\"0\" src=\"".$Globals{'idir'}."/nothumb.gif\" alt=\"\">";
                            }

                            if ( !empty($bigimage) ) {
                                $imgthumb = get_imagethumb( $bigimage, $pcat, $theuserid, 1 );
                                $mthumb = "<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;ppuser=$theuserid&amp;thumb=1\">$imgthumb</a>";
                            }
                        }
                        
                        if ( !empty($mthumb) || (empty($mthumb) && $Globals{'membthumb'} == "no") ) {
                            $tfilesize = $tfilesize/1024;
                            $filesize=sprintf("%1.1f", $tfilesize);
                            $filesize = $filesize."k";
                            
                            $count++;
                            
                            if ($count == $numcols) {
                                $uout .= "</tr><Tr>";
                                $count = 1;
                            }                        
                            
                            list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($ulast);
                            $mon++;
                            $year=1900+$year;
                            
                            $profilelink = get_profilelink( $theuserid );
                                
                            $uout .= "<Td bgcolor=\"".$Globals{'maincolor'}."\" valign=\"top\" align=\"center\" width=\"$pwidth%\">
                                <Table cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"".$Globals{'detailbgcolor'}."\" width=\"90%\"><tr><Td>
                                <Table cellpadding=\"2\" cellspacing=\"1\" width=\"100%\"><tr>
                                <td colspan=\"2\" bgcolor=\"".$Globals{'detailbgcolor'}."\" align=\"center\" valign=\"middle\" height=\"125\"><font size=\"".$Globals{'fontsmall'}."\"
                                face=\"".$Globals{'mainfonts'}."\"><!-- CyKuH [WTN] -->$mthumb</font></td>
                                </tr><tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">User:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\"><A href=\"$profilelink\">$theuser</a></font></td>
                                </tr><Tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Photos:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$uphotos</font></td>
                                </tr><Tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Space used:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$filesize</font></td>                                
                                </tr><Tr>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Views:</font></td>
                                <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$views</font></td>
                                </tr>";
                                
                                if ( $Globals{'allowpost'} == "yes" ) {
                                    $uout .= "<Tr><Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Comments:</font></td>
                                        <Td bgcolor=\"".$Globals{'detailcolor'}."\"><font color=\"".$Globals{'detailfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">$comcount</font></td></tr>";
                                }
                                
                                $uout .= "</table></td></tr></table></td>";
                        }
                        else {
                            // usually means a person with no approved images, so we dont display them
                            $cc--;
                        }
                    }
                }
            }
            
            if ( $queryz ) 
                ppmysql_free_result( $queryz );

            $squares = $Globals{'thumbcols'}-$count;

            for ($v=1; $v <= $squares; $v++) {
                $uout .= "<td bgcolor=\"".$Globals{'maincolor'}."\" width=\"$pwidth%\">&nbsp;</td>";
            }

            $output .= "$uout</table></td></tr></table>";

            print "$output</form>$posternav$noresults<p>".$Globals{'cright'}."$footer";
            exit;
        }
    }
}

?>
