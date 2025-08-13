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
// because this script can take a long time, we need to disable compression
$disableforadmin=1;

include("adm-inc.php");
include("image-inc.php");

if ( !isset($okay) ) $okay = "no";
if ( !isset($ppaction) ) $ppaction = "";

if ( isset($watermark) ) $watermark = "yes";
else $watermark = "no";

if ($okay != "yes") {
    $header = str_replace( "titlereplace", "PhotoPost Database Scan", $header );        
    
    $output = "$header<center>
        <hr><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\"
        align=\"center\"><tr><td>
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"4\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\">
        <font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\"><b>PhotoPost Refresh Usergroups</font>
        </font></td>
        </tr><tr>
        <td bgcolor=\"".$Globals{'headcolor'}."\"><b>
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'headfontcolor'}."\">$adminmenu</b></font></td></tr>
        <tr><td bgcolor=\"".$Globals{'maincolor'}."\"><center><Br>
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\">You are about to scan your database and image directories for missing thumbnails
        or database entries with no files. This will result in thumbnails being created and possible database entries being removed.<br>

        <form action=\"".$Globals{'maindir'}."/adm-db.php\" method=\"POST\">
        <table cellpadding=\"2\" cellspacing=\"1\" border=\"1\">        
        <tr><td align=\"center\" width=\"50\">
        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Options</font></td>
        <td align=\"center\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Description<br>
        </font></td></tr><tr>
        <td align=\"center\" width=\"50\">
        <input type=\"checkbox\" value=\"0\" name=\"watermark\"></td>
        <td align=\"center\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">Check here to watermark unwatermarked files.<br>
        This action cannot be undone - backup your data directory and photos database!</font></td>
        </tr></table><p>
        <input type=\"hidden\" name=\"okay\" value=\"yes\">
        <input type=\"hidden\" name=\"ppaction\" value=\"scandb\">
        <input type=\"submit\" value=\"Scan and Fix Photo Database.\"></form></td></tr></table></td></tr></table>";

    print "$output<p>".$Globals{'cright'}."<p>$footer";
    exit;
}

if ( $ppaction == "scandb" ) {
    $header = str_replace( "titlereplace", "PhotoPost Database Scan", $header );        
        
    print "$adminmenu<br><br>Processing may take a while... please be patient and wait the for FINISHED message.<br>";
    
    print "Checking for missing/corrupt thumbnails, incorrect usernames and database entries with no image...<br><br>";
    
    $datadir = $Globals{'datafull'};
    
    $query = "SELECT id,user,userid,cat,bigimage,medsize,watermarked FROM photos";
    $queryv = ppmysql_query($query,$link);
    
    while ( list( $id, $user, $tuserid, $cat, $bigimage, $medsize, $watermarked ) = mysql_fetch_row($queryv) ) {    
        if ( strstr($datadir, "/") )
            $newdir = $datadir."$cat/";
        else
            $newdir = $datadir."$cat\\";
        
        $theext = substr($bigimage, strlen($bigimage) - 4,4);
        $filename = $bigimage;
        $filename = str_replace( $theext, "", $filename);
        $newthumblink = $newdir."$tuserid$filename-thumb$theext";
        $imagelink = $newdir."$tuserid$bigimage";
        $bigimage = "$tuserid$bigimage";
        
        $fullsize = @filesize( $imagelink );
        $thumbsize = @filesize( $newthumblink );
        
        $redo = 0;
        if ( $fullsize == $thumbsize ) $redo = 1;
    
        if ( !file_exists( $newthumblink ) || $redo == 1 ) {
            if ( !is_multimedia($newthumblink) ) {
                if ( file_exists( $imagelink ) ) {
                    if ( $redo == 1 ) {
                        print "Thumbnail not sized: $newthumblink<br>Creating from $imagelink ... ";
                        @unlink( $newthumblink );
                    }
                    else
                        print "Thumbnail missing: $newthumblink<br>Creating from $imagelink ... ";
                    
                    // create thumbnail
                    $holduserid = $userid;
                    $userid = $tuserid;
                    $thumbsize = create_thumb( $bigimage, $imagelink, $cat, "rebuildthumbnail" );
                    
                    if ( !file_exists( $newthumblink ) ) {
                        print "<b>failed!</b>";
                    }
                    else {
                        if ( $redo == 1 ) {
                            $fullsize = @filesize( $imagelink );
                            $thumbsize = @filesize( $newthumblink );
                            
                            if ( $fullsize == $thumbsize ) print "failed resize!";
                            else print "completed!";
                        }
                        else {
                            print "completed!";
                        }
                    }
                    print "<br><br>";
                    $userid = $holduserid;
                }
                else {
                    print "Removing database entry with no image: $imagelink<br>";
                    $queryd = "DELETE FROM photos where id=$id";
                    $querydr = ppmysql_query($queryd, $link);                
                }
            }
        }
        
        // watermarks?
        if ( $watermark == "yes" && $watermarked == "no" ) {
            if ( is_image($imagelink) ) {
                print "watermarking: $imagelink<br>";
                watermark( $imagelink, 1 );
                $medwater = $newdir."$tuserid$filename-med$theext";
                if ( file_exists( $medwater ) ) {
                    print "watermarking: $medwater<br>";
                    watermark( $medwater, 1 );
                }
                $watermarked = "yes";
            }
        }
        
        // now lets make sure we have the right username for the photo
        list( $tname, $tmail ) = get_username($tuserid);
        $queryi = "UPDATE photos SET user='$tname', watermarked='$watermarked' where id=$id";
        $queryid = ppmysql_query($queryi, $link);
        
        @flush();
    }

    ppmysql_free_result( $queryv );

    print "<br><b>Finished!<br><br></b></center></td></tr></table></td></tr></table><p>";
    print $Globals{'cright'};
}

?>
