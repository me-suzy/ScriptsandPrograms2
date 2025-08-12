<?php
/*

==================================================================
Snippet-title:          Hardened BBClone v0.2 (29th October 2005)
Creator:                Hans Fredrik Nordhaug
E-mail:                 hans@nordhaug.priv.no
Creation date:          10th October 2005
License:                GPL
==================================================================

This snippet protects your BBClone stats from referer spam.
Read readme.txt for more information.

*/

include_once("../../pivot/pv_core.php");

echo '
        function bbcloneCount(title)  {
        var ref=escape("NO");
        var uri=escape("NO");
        var ua=escape(navigator.userAgent);
        var rem=escape("'.$_SERVER["REMOTE_ADDR"].'");
        '.
        "document.write('<img src=\"".
            $Paths["extensions_url"]."bbclone_tools/count.php?".
            "title='+escape(title)+'&amp;refkey='+refkey+'&amp;".
            "rem='+rem+'&amp;ref='+escape(document.referrer)+'&amp;uri='+escape(document.URL)+'&amp;ua='+ua+'\" />');
        }";
?>
