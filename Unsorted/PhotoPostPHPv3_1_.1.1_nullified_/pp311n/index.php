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

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

topmenu();

if ( IsSet($cat) ) {
    if ( $userid > 0 ) {
        list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();
        $mon = $mon + 1;    
        $lasttimeon = mktime($hour,$min,$sec,$mon,$mday,$year);
        
        $laston = "REPLACE INTO laston VALUES($cat,$userid,$lasttimeon)";
        $resultb = ppmysql_query($laston, $link);    
    }
    
    childsub($cat);
    $childnav = "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'catfontsize'}."\"><a href=\"".$Globals{'maindir'}."/index.php\">Home</a> $childnav</font>";
    $searchcat = $cat;
}
else {
    if ( $username != "" && $username != "Unregistered" ) $childnav = "Welcome, $username!";
    else $childnav = "Welcome to the ".$Globals{'galleryname'}."!";
    $searchcat = 998;
}

if ( !empty($cat) ) {
    $query = "SELECT id,catname FROM categories WHERE id='$cat'";
    $ctitleq = ppmysql_query($query, $link);
    if ( $ctitleq ) {
        list( $catid, $cattitle ) = mysql_fetch_row($ctitleq);
        ppmysql_free_result( $ctitleq );
        $tablehead = "$cattitle";        
    }
    else
        $tablehead = "";
}
else {
    $tablehead = "Main Index";
}

$header = str_replace( "titlereplace", "$tablehead", $header );

$output = "$header<center>
    <p><table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
    <Td valign=\"middle\" width=\"40%\">$menu2</td>
    <td width=\"60%\" align=\"right\" valign=\"middle\">$menu</td></tr></table>";

if ( $Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "no" ) {
    display_gallery("latest");
}

$output .= "<form method=\"get\" action=\"".$Globals{'maindir'}."/showgallery.php\">    
    <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"
    bgcolor=\"".$Globals{'bordercolor'}."\" width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td><table cellpadding=\"4\"
    cellspacing=\"1\" border=\"0\" width=\"100%\"><tr align=\"center\"><td colspan=\"5\" align=\"left\"
    bgcolor=\"".$Globals{'headcolor'}."\">
    <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    <Tr><Td>
    <font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\">&nbsp;$childnav</font>
    </td><td align=\"right\">
    <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><Tr>
    <td align=\"right\"><!-- CyKuH [WTN] -->
    <input type=\"hidden\" name=\"cat\" value=\"$searchcat\">    
    <input type=\"hidden\" name=\"thumb\" value=\"1\">
    <input type=\"text\" name=\"si\" style=\"font-size: 8pt;\" size=\"15\" value=\"\">
    <input type=\"submit\" value=\"Search\" style=\"font-size: 9pt;\">
    </td></tr><tr><td colspan=\"6\" align=\"right\">
    <font color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">Recent Posts:
    <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=997&amp;thumb=1\">last day</a>
    &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=996&amp;thumb=1\">last 7 days</a>
    &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=995&amp;thumb=1\">last 14 days</a>
    &nbsp;<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=998&amp;thumb=1\">all images</a>
    </font></td>
    </table>

    </td></tr></table></td></tr>
    <tr align=\"center\">
    <Td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Category</b></font></td>
    <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Photos</b></font></td>
    <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Comments</b></font></td>
    <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Last Comment</b></font></td>
    <!--REPLACEME-->
    <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><B>Last Photo Upload</b></font></td>
    </tr>";

$count = 0; $catdepth = 0;
$catrows = ""; $cptotal = 0; $posttotal = 0; $totalviews = 0; $diskspace = 0;

if ( !(IsSet($cat)) ) {
    catrow(0);
}
else {
    catrow($cat);
}

$output .= $catrows;

$usertotal = get_totalusers();

$query = "SELECT SUM(views), SUM(filesize), COUNT(*) AS fsize FROM photos";
$totalv = ppmysql_query($query,$link);
list( $totalviews, $diskuse, $totalphotos ) = mysql_fetch_row($totalv);
ppmysql_free_result($totalv);

$query = "SELECT id FROM comments";
$lastc = ppmysql_query($query, $link);
$catposts = mysql_num_rows($lastc);
ppmysql_free_result($lastc);

$totalviews = number_format( $totalviews );
$totalphotos = number_format( $totalphotos );
$usertotal = number_format( $usertotal );
$posttotal = number_format( $catposts );

$diskspace = $diskuse/1048576;
$diskspace = number_format( $diskspace, 1 );
$diskspace = "$diskspace MB";

// Lets get the Top 5 Posters
$query = "SELECT user,userid,COUNT(*) AS pcount FROM photos GROUP BY user ORDER BY pcount DESC";
$queryz = ppmysql_query($query,$link);
$rowcnt = mysql_num_rows($queryz);
$numfound = 0;

while ( list($theuser, $theuserid, $uphotos) = mysql_fetch_row($queryz)) {
    $numfound++;
    $topposters[$numfound] = $theuser;
    $topid[$numfound] = $theuserid;
    $topposts[$numfound] = $uphotos;
    if ( $numfound == 5 ) break;
}

$toplist = "<br>Top Posters:";
for ( $x=1; $x < ($numfound+1); $x++ ) {
    $toplist .= "&nbsp;&nbsp;&nbsp;<A href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;ppuser=$topid[$x]&amp;thumb=1\">$topposters[$x] <font size=\"".$Globals{'fontsmall'}."\">($topposts[$x])</font></a>";
}

if ($Globals{'stats'} == "yes") {
    if ( !IsSet($cat) ) {
        $output .= "<tr><Td colspan=\"5\" bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><font color=\"".$Globals{'maintext'}."\" size=\"".$Globals{'fontmedium'}."\"
            face=\"".$Globals{'mainfonts'}."\">$usertotal registered users, $totalphotos posted photos and $posttotal comments<br>
            $totalviews photo views using $diskspace disk space.$toplist</font></td></tr>";
    }
}

$output .= "</table></td></tr></table></form><p>";

if ( $Globals{'mostrecent'} == "yes" && $Globals{'recentdefault'} == "yes") {
    display_gallery("latest");
}
if ( $Globals{'dispopular'} == "yes" ) {
    display_gallery("most_views");
}
if ( $Globals{'disrandom'} == "yes" ) {
    display_gallery("random");
}

print $output."<p>".$Globals{'cright'}."$footer";

// Closing connection
mysql_close($link);
mysql_close($db_link);

?>
