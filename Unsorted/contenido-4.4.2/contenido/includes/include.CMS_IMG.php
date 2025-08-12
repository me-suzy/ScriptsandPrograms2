<?php

/******************************************
* File      :   include.CMS_IMG.php
* Project   :   Contenido 
* Descr     :   Include file for editiing
*               content of type CMS_IMG
*
* Author    :   Jan Lengowski
* Created   :   07.05.2003
* Modified  :   07.05.2003
*
* © four for business AG
******************************************/

if ($doedit == "1") {
    consaveContentEntry($idartlang, "CMS_IMG", $typenr, $CMS_IMG);
    consaveContentEntry($idartlang, "CMS_IMGDESCR", $typenr, $CMS_IMGDESCR);
    conGenerateCodeForArtInAllCategories($idart);
    header("location:".$sess->url($cfgClient[$client]["path"]["htmlpath"]."front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&changeview=edit"));
}

?>
<html>
<head>
<title>contenido</title>
<link rel="stylesheet" type="text/css" href="<?php print $cfg["path"]["contenido_fullhtml"] . $cfg["path"]["styles"] ?>contenido.css">
</HEAD>
<body>
<table width="100%"  border=0 cellspacing="0" cellpadding="0" bgcolor="#ffffff">
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
        
        echo "  <INPUT type=hidden name=lang value=\"$lang\">";
//        echo "  <INPUT type=hidden name=submit value=\"editcontent\">";
        echo "  <INPUT type=hidden name=typenr value=\"$typenr\">";
        echo "  <INPUT type=hidden name=idart value=\"$idart\">";
        echo "  <INPUT type=hidden name=idcat value=\"$idcat\">";
        echo "  <INPUT type=hidden name=idartlang value=\"$idartlang\">";
        echo "<INPUT type=hidden name=doedit value=1>";        
        echo "  <INPUT type=hidden name=action value=\"10\">";
        echo "  <INPUT type=hidden name=type value=\"$type\">";
        echo "<INPUT type=hidden name=changeview value=\"edit\">";

        echo "  <TABLE cellpadding=$cellpadding cellspacing=$cellpadding border=0>";
        
        echo "  <TR><TD valign=\"top\" class=text_medium nowrap>&nbsp;".$typenr.".&nbsp;".$a_description["CMS_IMG"][$typenr].":&nbsp;</TD><TD class=content>";
                echo "<SELECT name=CMS_IMG SIZE=1>";
                if ($a_content["CMS_IMG"][$typenr] != "0") {
                        echo "<option value=0>-- ".i18n("None")." --</option>";
                } else {
                        echo "<option value=0 selected>-- ".i18n("None")." --</option>";
                }
                
                $sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient='".$client."' AND filetype IN ('jpg', 'gif', 'png') ORDER BY dirname, filename";

                $db->query($sql);
                
                while ( $db->next_record() ) {

                    $descr = $db->f("description");
                    if ( strlen($descr) > 24 ) {
                        $descr = substr($descr, 0, 24);
                        $descr .= "..";
                    }

                    if ($db->f("idupl") != $a_content["CMS_IMG"][$typenr]) {
                        echo "<option value=\"".$db->f("idupl")."\">".$db->f("dirname").$db->f("filename")." [".$descr."]</option>";
                    } else {
                        echo "<option value=\"".$db->f("idupl")."\" selected>".$db->f("dirname").$db->f("filename")." [".$descr."]</option>";
                    }
                }
                
                echo "</SELECT>";
        echo "  </TD></TR>";
        echo "  <TR><TD valign=top class=text_medium nowrap>&nbsp;".$a_description["CMS_IMGDESCR"][$typenr].":&nbsp;</TD><TD class=content>";
        echo "  <TEXTAREA class=text_medium name=CMS_IMGDESCR ROWS=3 COLS=30>".$a_content["CMS_IMGDESCR"][$typenr]."</TEXTAREA>";
        echo "  </TD></TR>";
        
        $tmp_area = "con_editcontent";
        
        echo "  <TR valign=top><TD colspan=2><br>
                      <a href=".$sess->url($cfgClient[$client]["path"]["htmlpath"]."front_content.php?area=$tmp_area&idart=$idart&idcat=$idcat&idartlang=$idartlang")."><img src=\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_cancel.gif\" border=0></a>
                      <INPUT type=image name=submit value=editcontent src=\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif\" border=0>
                      </TD></TR>";

        echo "  </TABLE>
                      </FORM>";

?>
</td></tr></table>
</body>
</HTML>
