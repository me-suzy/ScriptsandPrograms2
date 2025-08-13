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
include("adm-inc.php");
include("adm-cinc.php");

if ( empty($do) ) $do="";
if ( empty($catid) ) $catid="";

if ($ppaction == "delcat") {  //# Delete a category
    if ($do == "process") { //# Process delete cat
        if ($catid != 500 && $catid != "0" && $catid != "") {
            delete_cat($catid);
        }
        else {
            dieWell("Invalid category ID.");
        }

        $forwardid = $Globals{'maindir'}."/adm-cats.php?ppaction=cats";
        forward( $forwardid, "Processing complete!" );
        exit;
    }

    //# Generate an 'are you sure' you want to delete? form...

    $querya="SELECT catname FROM categories where id=$catid";
    $catq = ppmysql_query($querya, $link);
    list( $catname ) = mysql_fetch_row($catq);
    ppmysql_free_result($catq);

    $header = str_replace( "titlereplace", "PhotoPost Categories", $header );            

    $output="$header<center>

        <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><B>PhotoPost Add a Category</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\">
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"><center><Br>
        You're about to delete the category called \"$catname\", and <b>ALL PHOTOS AND COMMENTS WITHIN THE CATEGORY</B>.<p>
        Are you sure you want to do that?
        <form action=\"".$Globals{'maindir'}."/adm-cats.php\" method=\"post\">
        <input type=\"hidden\" name=\"catid\" value=\"$catid\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"hidden\" name=\"ppaction\" value=\"delcat\">
        <input type=\"submit\"
        value=\"I'm sure, delete the category.\"></form></font></td></tr></table></td></tr></table>";

    print "$output<p>".$Globals{'cright'}."<p>$footer";
}

if ($ppaction == "addcat") { //# Add a category
    $parent=$catid;

    if ($do == "process") {
        // Process a user's category submission

        if ($parent == "") {
            $parent=0;
        }

        $querya="SELECT catorder FROM categories ORDER BY catorder DESC";
        $catq = ppmysql_query($querya, $link);
        list( $maxcatorder ) = mysql_fetch_row($catq);
        ppmysql_free_result( $catq );
        $maxcatorder++;

        $catname = addslashes( $catname );
        $catdesc = addslashes( $catdesc );

        $query = "INSERT INTO categories values(NULL,'$catname','$catdesc','$maxcatorder','yes',$parent,NULL,NULL,NULL,'no',NULL,NULL,NULL,NULL)";
        $setug = ppmysql_query($query,$link);

        $thecatid = mysql_insert_id( $link );
        $newdir = $Globals{'datafull'}."$thecatid";

        if ( !mkdir( $newdir, 0755 ) ) {
            dieWell( "Error creating directory $newdir. You can ignore this error if the directory already exists." );
            exit;
        }
        chmod( $newdir, 0777 );

        forward( $Globals{'maindir'}."/adm-cats.php?ppaction=cats", "Processing complete!" );
        exit;
    }

    //# Print out the Add a category HTML form

    if ($parent != "") {
        $querya="SELECT catname FROM categories where id=$parent";
        $catq = ppmysql_query($querya,$link);
        list( $parname ) = mysql_fetch_row($catq);
        ppmysql_free_result( $catq );
        
        $partext = "<font size=\"".$Globals{'fontlarge'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\">&nbsp;Create a subcategory for: \"<b>$parname</b>\"<Br>";
    }
    else
        $partext="";

    $header = str_replace( "titlereplace", "PhotoPost Categories", $header );            

    $output = "$header<center>

        <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><b>PhotoPost Add a Category</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>

        <table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"><form method=\"post\"
        action=\"".$Globals{'maindir'}."/adm-cats.php\">
        <tr><Td bgcolor=\"".$Globals{'headcolor'}."\">
        $partext
        <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\">
        <tr><Th bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Category Name</font></th>
        <Th bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Category Description</font></th></tr><Tr>
        <Td
        bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"text\" size=\"50\"
        value=\"\" name=\"catname\" class=\"bginput\"></td><Td bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"text\" size=\"50\" value=\"\" name=\"catdesc\" class=\"bginput\"></td>
        </tr>
        </table></td></tr></table><p>
        <input type=\"hidden\" name=\"catid\" value=\"$parent\">
        <input type=\"hidden\" name=\"ppaction\" value=\"addcat\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"submit\" value=\"Add Category\"></form></td></tr></table></td></tr></table>";

    print "$output<p>".$Globals{'cright'}."<p>$footer";
}


//# Edit categories

if ($ppaction == "editcat") {
    $header = str_replace( "titlereplace", "PhotoPost Categories", $header );            
    
    $output = "$header<center>

        <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\"
        face=\"".$Globals{'mainfonts'}."\"><B>PhotoPost Category Editor</font>
        </font></td>
        </tr>
        <tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"><form method=\"post\"
        action=\"".$Globals{'maindir'}."/adm-cats.php\"><tr><Td>
        <table border=\"0\" cellpadding=\"5\" cellspacing=\"1\"><tr><Th bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Display Order</th>
        <Th bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Category Name</font></th>
        <Th bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">Category Description</font></th></tr>";

    $viewoptions = "";
    $upoptions = "";
    $postoptions = "";
    $annooptions = "";
    $thumbpar = "";
    $adminup = "";

    $query = "SELECT
        id,catname,description,catorder,parent,thumbs,header,footer,headtags,private,ugnoview,ugnoupload,ugnopost,ugnoanno
        FROM categories WHERE id=$catid";
    $boards = ppmysql_query($query,$link);
    $posts = mysql_num_rows($boards);

    while ( list($id,$catname,$catdesc,$catorder,$parent,$thumbs,$cheader,$cfooter,$cheadtags,$cprivate,$ugnoview,$ugnoupload,$ugnopost,$ugnoanno) = mysql_fetch_row($boards) ) {
        //# Do the HTML for the usergroup access
        $ugviewblock = explode( ",", $ugnoview );
        $ugupblock = explode( ",", $ugnoupload );
        $ugpostblock = explode( ",", $ugnopost );
        $ugannoblock = explode( ",", $ugnoanno );

        $query = "SELECT groupid,groupname FROM usergroups";
        $resultug = ppmysql_query($query,$link);

        while ( list($groupid,$groupname) = mysql_fetch_row($resultug) ) {
            $view_checked="CHECKED";
            $up_checked="CHECKED";
            $post_checked="CHECKED";
            $anno_checked="CHECKED";

            reset( $ugviewblock );
            while ( list($ignore,$vgid) = each($ugviewblock) ) {
                if ($groupid == $vgid) $view_checked="";
            }

            reset( $ugupblock );
            while ( list($ignore,$ugid) = each($ugupblock) ) {
                if ($groupid == $ugid) $up_checked="";
            }

            reset( $ugpostblock );
            while ( list($ignore,$pgid) = each($ugpostblock) ) {
                if ($groupid == $pgid) $post_checked="";
            }

            reset( $ugannoblock );
            while ( list($ignore,$agid) = each($ugannoblock) ) {
                if ($groupid == $agid) $anno_checked="";
            }

            $viewoptions .= "<input type=\"checkbox\" name=\"view-$groupid\" value=\"1\" $view_checked> $groupname<Br>";
            $upoptions .= "<input type=\"checkbox\" name=\"up-$groupid\" value=\"1\" $up_checked> $groupname<br>";
            $postoptions .= "<input type=\"checkbox\" name=\"post-$groupid\" value=\"1\" $post_checked> $groupname<br>";
            $annooptions .= "<input type=\"checkbox\" name=\"anno-$groupid\" value=\"1\" $anno_checked> $groupname<br>";
        }
        ppmysql_free_result( $resultug );
        //# end usergroup access html

        if ($id != "500") {
            if ($thumbs == "yes") {
                $thumbopt = "<option selected>yes</option><option>no</option>";
            }
            else {
                $thumbopt = "<option selected>no</option><option>yes</option>";
            }

            if ($cprivate == "yes") {
                $privateopt = "<option selected>yes</option><option>no</option>";
            }
            else {
                $privateopt = "<option selected>no</option><option>yes</option>";
            }

            //$adminup = "<tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">Only allow admin uploads?
            //    <br></font>
            //    <font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">(Set to yes, users won't be able to upload to this category.)</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\">
            //    <select name=\"private-$id\">$privateopt</select></td></tr>";

            $query = "SELECT catname FROM categories WHERE id=$parent";
            $catquery = ppmysql_query($query,$link);
            list( $parname ) = mysql_fetch_row($catquery);
            ppmysql_free_result($catquery);

            if ($parent != "0") {
                $defaultopt = "<option selected value=\"$parent\">$parname</option>";
                catopt(0);
                $defaultopt .= "<option value=\"0\">None</option>";
            }
            else {
                $defaultopt = "<option selected value=\"0\">None</option>";
                catopt(0);
            }

            $thumbpar = "<tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><Center>Contains Thumbnails? <select
                name=\"thumbs-$id\">$thumbopt</select></center></font></td>
                <Td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><Center>Parent: <select
                name=\"parent-$id\">$defaultopt$paropts</select></td></tr>";
        }
        else {
            $delete="";
            $addcat="";
        }

        $catname = str_replace( "\"", "&quot", $catname);
        $catdesc = str_replace( "\"", "&quot", $catdesc);

        $output .= "<Tr><Td bgcolor=\"".$Globals{'maincolor'}."\"><center><input type=\"text\" size=\"".$Globals{'fontlarge'}."\" value=\"$catorder\" name=\"catorder-$id\" class=\"bginput\"></td><Td
            bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"text\" size=\"50\"
            value=\"$catname\" name=\"catname-$id\" class=\"bginput\"></td><Td bgcolor=\"".$Globals{'maincolor'}."\"><input type=\"text\" size=\"50\" value=\"$catdesc\" name=\"description-$id\" class=\"bginput\"></td></tr>";

        $output.= "
            $thumbpar
            <tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">Header Include File Path:
            <br></font>
            <font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">(leave blank to use admin setting)</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\">
            <input type=\"text\" name=\"header-$id\" value=\"$cheader\" size=\"50\" class=\"bginput\"></td></tr>
            <tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">Headtags Include File Path:
            <br></font>
            <font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">(leave blank to use admin setting)</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\">
            <input type=\"text\" name=\"headtags-$id\" value=\"$cheadtags\" size=\"50\" class=\"bginput\"></td></tr>
            <tr><Td colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">Footer Include File Path:
            <br></font>
            <font size=\"".$Globals{'fontsmall'}."\" face=\"".$Globals{'mainfonts'}."\">(leave blank to use admin setting)</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\">
            <input type=\"text\" name=\"footer-$id\" value=\"$cfooter\" size=\"50\" class=\"bginput\"></td></tr>
            <Tr><Td bgcolor=\"".$Globals{'headcolor'}."\" colspan=\"3\"><br><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><B>Usergroup Access
            Permissions</b></font><br><font size=\"".$Globals{'fontsmall'}."\"
            face=\"".$Globals{'mainfonts'}."\">Note: If you disable a
            usergroup's uploads or posts in the
            Usergroups panel, the category specific settings below won't have an effect for that usergroup.</td></tr>
            <Tr><Td valign=\"top\" colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">These checked usergroups can view
            images in this category.</td><Td bgcolor=\"".$Globals{'maincolor'}."\">$viewoptions</td></tr>
            <Tr><Td valign=\"top\" colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">These checked usergroups can upload
            to this category,<Br>unless a usergroup's overall upload access is disabled.</td><Td
            bgcolor=\"".$Globals{'maincolor'}."\">$upoptions</td></tr>

            <Tr><Td valign=\"top\" colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">These checked
            usergroups can post to this category,<Br>unless a usergroup's overall posting access is disabled.</td><Td
            bgcolor=\"".$Globals{'maincolor'}."\">$postoptions</td></tr>";

        if ($Globals{'annotate'} == "yes") {
            $output .= "<Tr><Td valign=\"top\" colspan=\"2\" bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">These checked
                usergroups  will have their photos stamped with your overlay photo.</td><Td bgcolor=\"".$Globals{'maincolor'}."\">$annooptions</td></tr>";
        }

        $output .= "

            </table></td></tr></table><p>

            <input type=\"hidden\" name=\"catid\" value=\"$id\">
            <input type=\"hidden\" name=\"ppaction\" value=\"cats\">
            <input type=\"hidden\" name=\"do\" value=\"process\">
            <input type=\"submit\" value=\"Save Changes\"></form></td></tr></table></td></tr></table>";
    }
    
    ppmysql_free_result( $boards );

    print "$output<p>".$Globals{'cright'}."<p>$footer";
    exit;
}


if ($ppaction == "cats") {
    if ($do == "process") {
        $doview = "";
        $doupload = "";
        $dopost = "";
        $doanno = "";

        foreach($HTTP_POST_VARS as $vid=>$setting) {
            $ugorder = explode("-", $vid);
            $ugtype=$ugorder[0];
            if ( count($ugorder) > 1 ) $ugid=$ugorder[1];

            //print "==[$ugtype][$ugid]==<br>";
            if ($ugtype == "view") {
                if ($setting==1) {
                    if ( $doview != "" ) $doview .= ",";
                    $doview .= "$ugid";
                }
            }
            elseif ($ugtype == "up") {
                if ($setting==1) {
                    if ( $doupload != "" ) $doupload .= ",";
                    $doupload .= "$ugid";
                }
            }
            elseif ($ugtype == "post") {
                if ($setting==1) {
                    if ( $dopost != "" ) $dopost .= ",";
                    $dopost .= "$ugid";
                }
            }
            elseif ($ugtype == "anno") {
                if ($setting==1) {
                    if ( $doanno != "" ) $doanno .= ",";
                    $doanno .= "$ugid";
                }
            }
            else {
                $query = "";
                //print "[$id][$vid][$ugid/$catid][$ugtype][$setting]<br>";
                if ( $ugid != "" ) {
                    if ( $ugtype != "private" && $ugtype != "thumbs" && $ugtype != "parent" && $ugtype != "catid" )
                        $query = "UPDATE categories SET ".$ugtype."='$setting' WHERE id=$ugid";

                    if ($ugid != 500) {
                        if ($ugtype == "private" ) {
                            $query = "UPDATE categories SET private='$setting' WHERE id=$ugid";
                        }
                        elseif ($ugtype == "thumbs" ) {
                            $query = "UPDATE categories SET thumbs='$setting' WHERE id=$ugid";
                        }
                        elseif ($ugtype == "parent" ) {
                            childcheck($setting, $ugid);
                            $query = "UPDATE categories SET parent='$setting' WHERE id=$ugid";
                        }
                    }

                    if ($query != "" && $ugtype != "ppaction" && $ugtype != "do" && $ugtype != "catid") {
                        //print "[$query]<br>";
                        $resulta = ppmysql_query($query,$link);
                    }
                }
            }
        }

        if ( $catid != "" ) {
            //print "[$doview][$doupload][$dopost][$doanno]<br>";
            $ugviewblock = explode( ",", $doview);
            $ugupblock = explode( ",", $doupload);
            $ugpostblock = explode( ",", $dopost);
            $ugannoblock = explode( ",", $doanno);

            $blockview = "";
            $blockup = "";
            $blockpost = "";
            $blockanno = "";

            $query = "SELECT groupid,groupname FROM usergroups";
            $resultg = ppmysql_query($query,$link);

            while ( list($groupid, $groupname) = mysql_fetch_row($resultg) ) {
                $vthisid=0; $uthisid=0; $pthisid=0; $athisid=0;

                reset($ugviewblock);
                while ( list($ignore,$vgid) = each($ugviewblock) ) {
                    if ($groupid == $vgid) $vthisid="1";
                }

                reset($ugupblock);
                while ( list($ignore,$ugid) = each($ugupblock) ) {
                    if ($groupid == $ugid) $uthisid="1";
                }

                reset($ugpostblock);
                while ( list($ignore,$pgid) = each($ugpostblock) ) {
                    if ($groupid == $pgid) $pthisid="1";
                }

                reset($ugannoblock);
                while ( list($ignore,$agid) = each($ugannoblock) ) {
                    if ($groupid == $agid) $athisid="1";
                }

                if ($vthisid==0) {
                    if ( $blockview != "" ) $blockview .= ",";
                    $blockview .= "$groupid";
                }

                if ($uthisid==0) {
                    if ( $blockup != "" ) $blockup .= ",";
                    $blockup .= "$groupid";
                }

                if ($pthisid==0) {
                    if ( $blockpost != "" ) $blockpost .= ",";
                    $blockpost .= "$groupid";
                }

                if ($athisid==0) {
                    if ( $blockanno  != "" ) $blockanno .= ",";
                    $blockanno .= "$groupid";
                }
            }
            ppmysql_free_result( $resultg );

            $sql = "UPDATE categories SET ugnoview='$blockview',ugnoupload='$blockup',ugnopost='$blockpost',ugnoanno='$blockanno' WHERE id=$catid";
            $resultc = ppmysql_query($sql,$link);
        }

        forward( $Globals{'maindir'}."/adm-cats.php?ppaction=cats", "Processing complete!" );
        exit;
    }

    //# Generate the edit categories HTML form
    $header = str_replace( "titlereplace", "PhotoPost Categories", $header );                

    $output = "$header<center>

        <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\"
        size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><b>PhotoPost Category Editor</font>
        </font></td>
        </tr>
        <form method=\"post\" action=\"".$Globals{'maindir'}."/adm-cats.php\">
        <tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><font size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"><Br><ul>(
        <a href=\"".$Globals{'maindir'}."/adm-cats.php?ppaction=addcat\">Add Top Level Category</a> )</font></ul>";

    catli(0);

    $output.= "<p><center>
        <input type=\"hidden\" name=\"ppaction\" value=\"cats\">
        <input type=\"hidden\" name=\"do\" value=\"process\">
        <input type=\"submit\" value=\"Save Order Changes\"></form></td></tr></table></td></tr></table>";

    print "$output<p>".$Globals{'cright'}."<p>$footer";
}

?>

