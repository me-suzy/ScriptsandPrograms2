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
function catopt( $subcatid ) {
    global $Globals, $link, $paropts;

    $query = "SELECT id,catname,description,thumbs FROM categories WHERE parent=$subcatid ORDER BY catorder";
    $rows = ppmysql_query($query,$link);

    while ( list( $subid, $subcatname, $subcatdesc, $subthumbs ) = mysql_fetch_row($rows) ) {
        if ($subid != "500") {
            if ($subthumbs == "no") {
                $paropts .= "<option value=\"$subid\">- -$subcatname</option>";
            }
            else {
                $paropts .= "<option value=\"$subid\">$subcatname</option>";
            }
        }
        catopt( $subid );
    }
    ppmysql_free_result($rows);
}

function catli( $subcatid ) {
    global $Globals, $link, $output;

    $catcnt=0;
    $query = "SELECT id,catname,description FROM categories WHERE parent=$subcatid ORDER BY catorder";
    $rows = ppmysql_query($query,$link);

    while ( list( $subid, $subcatname, $subcatdesc ) = mysql_fetch_row($rows) ) {
        $catcnt++;

        if ($subid != "500") {
            $delete = "<a href=\"".$Globals{'maindir'}."/adm-cats.php?ppaction=delcat&catid=$subid\">Delete</a>";
            $addcat = "<a href=\"".$Globals{'maindir'}."/adm-cats.php?ppaction=addcat&catid=$subid\">Add Subcat</a>";
        }
        else {
            $delete = "Delete";
            $addcat = "Add Subcat";
        }
        $edit = "<a href=\"".$Globals{'maindir'}."/adm-cats.php?ppaction=editcat&catid=$subid\">Edit</a>";

        $subcatname = str_replace( "\"", "&quot", $subcatname);
        $subcatdesc = str_replace( "\"", "&quot", $subcatdesc);
        $output .= "<Font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\"><ul><li>$subcatname (Order: <input type=\"text\" size=\"".$Globals{'fontlarge'}."\"
            value=\"$catcnt\" name=\"catorder-$subid\"
            style=\"font-size: 8pt; background: FFFFFF;\">) [$delete] [$addcat] [$edit]";

        catli($subid);
    }
    ppmysql_free_result( $rows );
    
    $output .= "</ul>";

    return;
}

function childcheck( $parid, $nowpar ) {
    global $link;

    $query = "SELECT id,parent FROM categories WHERE id=$parid";
    $rows = ppmysql_query($query,$link);

    while ( list( $tid, $tparent ) = mysql_fetch_row($rows) ) {
        if ($tparent == $nowpar) {
            dieWell("You tried to parent a forum to one of its children.");
            exit;
        }
        if ($tid == $nowpar) {
            dieWell("You tried to parent a forum to itself.");
            exit;
        }
        childcheck( $tparent, $nowpar );
    }
    ppmysql_free_result( $rows );

    return;
}

function albumopt( $subalbumid ) {
    global $Globals, $link, $paropts;

    $query = "SELECT id,albumname FROM useralbums WHERE parent=$subalbumid";
    $rows = ppmysql_query($query,$link);

    while ( list( $subid, $subalbumname ) = mysql_fetch_row($rows) ) {
        if ($subid != "500") {
            if ($subthumbs == "no") {
                $paropts .= "<option value=\"$subid\">- -$subalbumname</option>";
            }
            else {
                $paropts .= "<option value=\"$subid\">$subalbumname</option>";
            }
        }
        albumopt( $subid );
    }
    ppmysql_free_result( $rows );
}

function albumli( $userid ) {
    global $Globals, $link, $output;

    $albumcnt=0;
    $query = "SELECT id,albumname,description,isprivate,password FROM useralbums WHERE parent=$userid";
    $rows = ppmysql_query($query,$link);

    if ( !$rows ) return;

    while ( list( $subid, $subalbumname, $subalbumdesc, $isprivate, $password ) = mysql_fetch_row($rows) ) {
        $albumcnt++;
        
        if ( $isprivate == "yes" ) {
            $private = " (private) ";
            $privatelink = "Private link to access album: <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$subid&amp;papass=$password&amp;thumb=1\">".$Globals{'maindir'}."/showgallery.php?cat=$subid&amp;papass=$password&amp;thumb=1</a>";
        }
        else {
            $private = "";
            $privatelink = "";
        }

        $delete = "<a href=\"".$Globals{'maindir'}."/useralbums.php?ppaction=delalbum&albumid=$subid\">Delete</a>";
        $edit = "<a href=\"".$Globals{'maindir'}."/useralbums.php?ppaction=editalbum&albumid=$subid\">Edit</a>";

        $subalbumname = str_replace( "\"", "&quot", $subalbumname);
        $subalbumdesc = str_replace( "\"", "&quot", $subalbumdesc);

        $output .= "<ul><li><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\">$subalbumname $private [$delete] [$edit]<br>$subalbumdesc<br>$privatelink";

        albumli($subid);
    }
    ppmysql_free_result( $rows );
    
    $output .= "</ul>";

    return;
}

function delete_cat( $catid ) {
    global $Globals, $link;

    // Delete category from categories table

    if ( $catid < 3000 ) {
        $query = "SELECT id FROM categories WHERE parent=$catid";
        $cats = ppmysql_query($query,$link);

        while ( list( $subcatid ) = mysql_fetch_row($cats) ) {
            delete_cat( $subcatid );
        }
        if ( $cats ) ppmysql_free_result( $cats );

        $query = "DELETE FROM categories WHERE id=$catid";
        $cats = ppmysql_query($query,$link);
    }
    else {
        $query = "DELETE FROM useralbums WHERE id=$catid";
        $cats = ppmysql_query($query,$link);
    }

    $query = "SELECT userid,bigimage,medsize,title FROM photos WHERE cat=$catid";
    $resulta = ppmysql_query($query,$link);

    while ( list( $uid, $bigimage, $medsize, $title ) = mysql_fetch_row($resulta) ) {
        remove_all_files( $bigimage, $medsize, $uid, $catid );
    }
    if ( $resulta ) ppmysql_free_result( $resulta );

    //# end delete the files //#
    $query = "DELETE FROM photos WHERE cat=$catid";
    $resulta = ppmysql_query($query, $link);

    $query = "DELETE FROM comments WHERE cat=$catid";
    $resulta = ppmysql_query($query, $link);

    $photodir = $Globals{'datafull'}."$catid";
    if ( file_exists( $photodir ) ) {
        delete_dir( $photodir );
    }
}

?>

