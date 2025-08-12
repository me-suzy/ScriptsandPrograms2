<?php
/*

==================================================================
Snippet-title:          Hardened BBClone v0.2 (10th October 2005)
Creator:                Hans Fredrik Nordhaug
E-mail:                 hans@nordhaug.priv.no
Creation date:          10th October 2005
Last edited:			29th October 2005 - Bob
License:                GPL
==================================================================

This snippet protects your BBClone stats from referer spam.
Read readme.txt for more information.

*/


if(!defined('INPIVOT')){ exit('not in pivot'); }

function snippet_hardened_bbclone($title = '') {
    global $Paths;
    if ($title != '') {
        $title = str_replace("%title%", snippet_title(), $title);
        $title = str_replace("%weblogtitle%", snippet_weblogtitle(), $title);
        $title = addslashes($title);
    }
    return '
        <!-- hardened bbclone counter -->
        <script type="text/javascript" src="'.
        $Paths["extensions_url"].'bbclone_tools/getkey.php"></script>

        <script type="text/javascript" src="'.
        $Paths["extensions_url"].'bbclone_tools/getfunc.php"></script>

        <script type="text/javascript">
        bbcloneCount("'.$title.'");
        </script>';
}

?>

