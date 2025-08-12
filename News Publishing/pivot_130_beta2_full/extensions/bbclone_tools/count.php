<?php
/*

==================================================================
Snippet-title:          Hardened BBClone v0.1 (10th October 2005)
Creator:                Hans Fredrik Nordhaug
E-mail:                 hans@nordhaug.priv.no
Creation date:          10th October 2005
License:                GPL
==================================================================

This snippet protects your BBClone stats from referer spam.
Read readme.txt for more information.

*/

include_once("./hr_conf.php");

include_once("../../pivot/pvlib.php");
include_once("../../pivot/pv_core.php");

if( file_exists( $Paths['pivot_path'].'../bbclone/' )) {
    $bbclone_path = fixpath( $Paths['pivot_path'].'../bbclone/' );
} elseif( file_exists( $Paths['pivot_path'].'../../bbclone/' )) {
    $bbclone_path = fixpath( $Paths['pivot_path'].'../../bbclone/' );
} else {
    // Bbclone not found - just abort.
    header("content-type:image/gif");
    readfile("pixel.gif");
    exit;
}

if ( ($_GET["refkey"]!="") && file_exists("$refkeydir/".$_GET["refkey"])) {
    if ((time() - filectime("$refkeydir/".$_GET["refkey"])) < 1000) {

        // If we have Pivot-Blacklist, apply blacklist check on the
        // referrer for even more protection
        if(file_exists("../blacklist/blacklist_lib.php"))  {
            include_once("../blacklist/blacklist_lib.php");
            $aConfig = pbl_getconfig();
            if($aConfig["refwhiteonly"] == 1)  {
                if(pbl_whitelisted($_GET["ref"]))  {
                include("do_count.php");
                    header("content-type:image/gif");
                    readfile("pixel.gif");
                    exit;
                }
            }
            if (strlen(pbl_checkforspam($_GET["ref"])) > 0)  {
                pbl_logspammer($_GET["ref"], "breferer");
                // act normal
                header("content-type:image/gif");
                readfile("pixel.gif");
                exit;
            }
        }
        // End Pivot-Blacklist

        // If not handled by Blacklist, count it
        include("do_count.php");
    }
    unlink("$refkeydir/".$_GET["refkey"]);
}

header("content-type:image/gif");
readfile("pixel.gif");

?>
