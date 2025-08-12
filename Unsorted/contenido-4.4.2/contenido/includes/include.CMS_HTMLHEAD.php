<?php

/******************************************
* File      :   include.CMS_HTMLHEAD.php
* Project   :   Contenido 
* Descr     :   Include file for editiing
*               content of type CMS_HTMLHEAD
*
* Author    :   Jan Lengowski
* Created   :   07.05.2003
* Modified  :   07.05.2003
*
* © four for business AG
******************************************/

if ( $doedit == "1" ) {
    consaveContentEntry($idartlang, "CMS_HTMLHEAD", $typenr, $CMS_HTML);
    conGenerateCodeForArtInAllCategories($idart);
    header("location:" . $sess->url($cfgClient[$client]["path"]["htmlpath"]."front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&changeview=edit"));
}

?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $cfg["path"]["contenido_fullhtml"].$cfg["path"]["styles"] ?>contenido.css" />
<base href="<?php echo $cfgClient[$client]["path"]["htmlpath"]; ?>">
</head>
<body>
<table width="100%"  border=0 cellspacing="0" cellpadding="0" bgcolor=<?php echo "$bg"; ?> >
  <tr>
    <td width="10" rowspan="4"><img src="<?php print $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"] ?>spacer.gif" width="10" height="10"></td>
    <td width="100%"><img src="<?php print $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"] ?>spacer.gif" width="10" height="10"></td>
    <td width="10" rowspan="4"><img src="<?php print $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"] ?>spacer.gif" width="10" height="10"></td>
  </tr>
  <tr>
    <td>

<?php

        getAvailableContentTypes($idartlang);
        
        echo "  <FORM method=\"post\" action=\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["includes"]."include.backendedit.php\">";
        $sess->hidden_session();
        echo "<INPUT type=hidden name=lang value=\"$lang\">";
//        echo "<INPUT type=hidden name=submit value=\"editcontent\">";
        echo "<INPUT type=hidden name=typenr value=\"$typenr\">";
        echo "<INPUT type=hidden name=idart value=\"$idart\">";
        echo "<INPUT type=hidden name=action value=\"10\">";
        echo "<INPUT type=hidden name=type value=\"$type\">";
        echo "<INPUT type=hidden name=idcat value=\"$idcat\">";
        echo "<INPUT type=hidden name=doedit value=1>";
        echo "<INPUT type=hidden name=idartlang value=\"$idartlang\">";
        echo "<INPUT type=hidden name=test value=\"Test\">";
        echo "<INPUT type=hidden name=changeview value=\"edit\">";
        echo "<TABLE cellpadding=2 cellspacing=0 border=0>";
        echo "<TR><TD valign=top class=text_medium nowrap>&nbsp;".$typenr.".&nbsp;".$a_description[$type][$typenr].":&nbsp;<br></TD>";

        /* Include Richt-Text editor */
        include ($cfg["path"]["wysiwyg"] . 'editor.php');



        echo "  </TR>";
        
        echo "  </TR>";
        $tmp_area = "con_editcontent";
        echo "  <TR valign=top><TD colspan=2><br>
                      <a href=".$sess->url($cfgClient[$client]["path"]["htmlpath"]."front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&lang=$lang")."><img src=\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_cancel.gif\" border=0></a>
                      <INPUT type=image name=submit value=editcontent src=\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif\" border=0>
                      </TD></TR>";

        echo "  </TABLE>
                      </FORM>";



        /*
        echo "  <TR valign=top><TD colspan=2><br>
                      <a href=\"".$sess->url("front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat")."><img src=\"images/but_cancel.gif\" border=0></a>
                      <INPUT type=image name=submit value=submit src=\"images/but_ok.gif\" border=0>
                      </TD></TR>";

        echo "  </TABLE>
                      </FORM>";*/

?>
</td></tr></table>

</body>
</HTML>
