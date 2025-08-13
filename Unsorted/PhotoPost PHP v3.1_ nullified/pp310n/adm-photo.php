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

if (empty($pdelete)) $pdelete="";

if ($ppaction == "movedel") {
    if ($pdelete == "yes") {
        if ( $pid == "") {
            dieWell( "Error: No pic to delete!" );
            exit;
        }
        
        if ( !is_numeric($pid) ) {
            dieWell( "Malformed parameter passed!" );
            exit;
        }

        $query = "SELECT userid,cat,bigimage,medsize,title FROM photos WHERE id=$pid";
        $resulta = ppmysql_query($query,$link);

        if ( !$resulta ) {
            dieWell( "Photo $pid not found in your database!" );
            exit;
        }

        list( $puserid, $thecat, $filename, $medsize, $ptitle ) = mysql_fetch_row($resulta);
        ppmysql_free_result( $resulta );

        if ( ($userid == $puserid && $Globals{'userdel'} == "yes") || $adminedit == 1 ) {
            if ( $filename != "" ) remove_all_files( $filename, $medsize, $puserid, $thecat );

            $query = "DELETE FROM photos WHERE id=$pid";
            $resulta = ppmysql_query($query,$link);

            $query = "DELETE FROM comments WHERE photo=$pid";
            $resulta = ppmysql_query($query,$link);

            if ($Globals{'ppostcount'} == "yes") {
                inc_user_posts( "minus", $puserid );
            }

            $adesc = "Deleted image #$pid";
            $furl = $Globals{'maindir'}."/showgallery.php?cat=$thecat&amp;thumb=1";

            if ( $Globals{'useemail'} == "yes" && ($adminedit == 1 && $userid != $puserid) ) admin_email( 'delete', $pid, $puserid, $ptitle );

            forward($furl, $adesc);
            exit;
        }
        else {
            dieWell( "You do not have permission for this action!");
            exit;
        }
    }

    if ($catmove != "") {
        if ( $catmove == "notcat" ) {
            dieWell( "You cannot move an image to that category, please try again." );
            exit;
        }

        if ( $origcat != $catmove && !empty($catmove) ) {
            move_image_cat( $pid, $catmove );
        }
        else {
            $furl = $Globals{'maindir'}."/showphoto.php?photo=$pid";
            forward($furl, "No need to move image to same category!");
            exit;
        }
    }
}

dieWell( "No action specified" );
exit;

?>

