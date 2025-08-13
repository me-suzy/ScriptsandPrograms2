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

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if (empty($size)) $size="big";


topmenu();

$output = "<title>".$Globals{'galleryname'}." SlideShow</title>$header
    <p><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
    <Td valign=\"bottom\" width=\"50%\">&nbsp;</td>
    <td width=\"50%\" align=\"right\" valign=\"middle\">$menu&nbsp;</td></tr></table>
    <p><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
    align=\"center\"><tr><td>
    <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
    <tr align=\"center\">
    <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontlarge'}."\">".$Globals{'galleryname'}." Slide Show
    </font></td>
    </tr>
    <form method=\"post\" action=\"".$Globals{'maindir'}."/showphoto.php\">";

$output .= "<tr><Td bgcolor=\"".$Globals{'maincolor'}."\" width=\"50%\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">
    Time delay for photos?<br>(slower connections will want longer timeouts)</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\"><select name=\"slidedelay\">";

$output .= "<option value=\"2\">2 seconds</option>";
$output .= "<option selected value=\"4\">4 seconds</option>";
$output .= "<option value=\"8\">8 seconds</option>";
$output .= "<option value=\"10\">10 seconds</option>";

$output .= "</select></td></tr>
    <Center>

    <Tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><center>
    <input type=\"hidden\" name=\"photo\" value=\"$photo\">
    <input type=\"hidden\" name=\"sort\" value=\"$sort\">        
    <input type=\"hidden\" name=\"size\" value=\"$size\">
    <input type=\"hidden\" name=\"slideshow\" value=\"1\">    
    <input type=\"submit\" value=\"Start Slideshow\"></td></tr></table></td></tr></table><p>".$Globals{'cright'}."$footer";

print $output;

?>

