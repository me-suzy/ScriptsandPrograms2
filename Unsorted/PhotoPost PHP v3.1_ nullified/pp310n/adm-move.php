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
include("adm-inc.php");

if ($ppaction == "move") {    
    if ( empty($origcat) || empty($catmove) ) {
        dieWell( "Categories not selected properly. Please go back and try again." );
        exit;
    }
    
    $images_moved = 0;
    $query = "SELECT id,userid,cat,bigimage FROM photos WHERE cat=$origcat";
    $resulta = ppmysql_query($query,$link);
    
    while ( list( $pid, $puserid, $thecat, $filename ) = mysql_fetch_row($resulta) ) {        
        if ($catmove != "") {
            if ( $catmove == "notcat" ) {
                dieWell( "You cannot move an image to that category, please try again." );
                exit;
            }
    
            if ( $origcat != $catmove && !empty($catmove) ) {
                move_image_cat( $pid, $catmove, "no" );
                $images_moved++;
            }
            else {
                $furl = $Globals{'maindir'}."/showphoto.php?photo=$pid";
                forward($furl, "No need to move image to same category!");
                exit;
            }
        }
    }
    
    if ( $resulta )
        ppmysql_free_result( $resulta );

    $from_cat = get_catname( $origcat );
    $to_cat = get_catname( $catmove );
        
    $forwardid = $Globals{'maindir'}."/adm-index.php";
    forward( $forwardid, "$images_moved images moved from '$from_cat' to '$to_cat'" );
    exit;
}

catmoveopt(0);
$header = str_replace( "titlereplace", "PhotoPost Bulk Move", $header );        

$output = "$header<center>
    <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
    align=\"center\"><tr><td>
    <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
    <tr align=\"center\">
    <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
    size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
    face=\"".$Globals{'mainfonts'}."\"><B>PhotoPost Bulk Move Images</font>
    </font></td>
    </tr><tr>
    <td bgcolor=\"".$Globals{'headcolor'}."\">
    <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</font></td></tr>
    <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"><center><Br>
    You are about to move all images from one category to another. This will move all images and update your database. Use carefully!
    <form action=\"".$Globals{'maindir'}."/adm-move.php\" method=\"post\">
    Category you wish to move?
    <select name=\"origcat\" style=\"font-size: 9pt; background: FFFFFF;\"><option
                selected></option>$catoptions</select>
    <br>Category you wish to move to?
    <select name=\"catmove\" style=\"font-size: 9pt; background: FFFFFF;\"><option
                selected></option>$catoptions</select>   
    <input type=\"hidden\" name=\"ppaction\" value=\"move\">
    <p><input type=\"submit\" value=\"Select options and click to move photos.\"></form></font></td></tr></table></td></tr></table>";

print "$output<p>".$Globals{'cright'}."<p>$footer";

?>

