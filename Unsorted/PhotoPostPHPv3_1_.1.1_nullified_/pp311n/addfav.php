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

list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime();

if ( !isset($photo) || !isset($do) ) {
    dieWell("Script not called correctly.  Navigate to a specific photo, then click on the Add to Favorites link.");
    exit;
}

authenticate();

if ( isset($Globals{'ppboards'}) && $adminedit != 1 ) {
    if ( $Globals{'ppboards'} == "closed" ) {
        print "We're sorry, but our Photo Boards are currently down for maintainence. Please try again later.";
        exit;
    }
}

if ($userid == "") {
    dieWell( "Only registered users can have favorite photos." );
    exit;
}

if ( $do == "add" ) {
    $query = "REPLACE INTO favorites values(NULL,$userid,$photo)";
    $type = "added to";
}
else {
    if ( !is_numeric($photo) || !is_numeric($userid) ) {
        dieWell( "Malformed call to addfav.php!");
        exit;
    }
    $type = "removed from";
    $query = "DELETE FROM favorites WHERE photo=$photo AND userid=$userid";
}
$result = ppmysql_query($query, $link);

forward( $Globals{'maindir'}."/showphoto.php?photo=$photo", "The image has been $type your Favorites album!" );

?>
