<?php
if ($submit == "editcontent") {
        consaveContentEntry($idsidelang, "CMS_HTML", $typenr, $CMS_HTML);
        conGenerateCodeForSideInAllCategories($idside);
        Header("Location:".$sess->url("front_content.php?area=$tmp_area&idside=$idside&idcat=$idcat&lang=$lang")."");
}
?>
<html>
<head>
<title>contenido</title>
<link rel="stylesheet" type="text/css" href="<?php print $ContenidoPath ?>contenido.css">



</HEAD>
<BODY MARGINHEIGHT=0 MARGINWIDTH=0 LEFTMARGIN=0 TOPMARGIN=0 >
<table width="100%"  border=0 cellspacing="0" cellpadding="0" bgcolor=<?php echo "$bg"; ?> >
  <tr>
    <td width="10" rowspan="4"><img src="<?php print $ContenidoPath.$cfgPathImg ?>space.gif" width="10" height="10"></td>
    <td width="100%"><img src="<?php print $ContenidoPath.$cfgPathImg ?>space.gif" width="10" height="10"></td>
    <td width="10" rowspan="4"><img src="<?php print $ContenidoPath.$cfgPathImg ?>space.gif" width="10" height="10"></td>
  </tr>
  <tr>
    <td>

<?php
        $sql = "SELECT * FROM $cfgTab_content AS A, $cfgTab_side_lang AS B, $cfgTab_type AS C WHERE A.idtype=C.idtype AND A.idsidelang=B.idsidelang AND B.idsidelang='$idsidelang'";
        $db->query($sql);
        while ($db->next_record()) {
                $a_content[$db->f("type")][$db->f("typeid")] = $db->f("value");
                $a_description[$db->f("type")][$db->f("typeid")] = i18n($db->f("description"));
        }
        echo "  <FORM method=\"post\" action=\"".$sess->url("view.php")."\">";
        $sess->hidden_session();
        echo "  <INPUT type=hidden name=lang value=\"$lang\">";
        echo "  <INPUT type=hidden name=submit value=\"editcontent\">";
        echo "  <INPUT type=hidden name=typenr value=\"$typenr\">";
        echo "  <INPUT type=hidden name=idside value=\"$idside\">";
        echo "  <INPUT type=hidden name=action value=\"10\">";
        echo "  <INPUT type=hidden name=type value=\"$type\">";
        echo "  <INPUT type=hidden name=idcat value=\"$idcat\">";
        echo "  <INPUT type=hidden name=idsidelang value=\"$idsidelang\">";
        echo "  <INPUT type=hidden name=test value=\"Test\">";

        echo "  <TABLE cellpadding=$cellpadding cellspacing=$cellpadding border=0>";

        echo "  <TR><TD valign=top class=head nowrap>&nbsp;".$typenr.".&nbsp;".$a_description[$type][$typenr].":&nbsp;</TD>";



include($PathWYSIWYG."editor.php");?>
<script>
_editor_url = "<?php print $PathWYSIWYGHTML ?>";
</script>

<?php

echo "<td bgcolor=\"$bg\"><textarea name=\"CMS_HTML\" rows=\"20\" cols=\"52\" style=\"width:650\">".urldecode($a_content[$type][$typenr])."</textarea></td>\n";

//<a href="javascript:editor_insertHTML('box2','<font style=\'background-color: yellow\'>','</font>');">Highlight selected text</a> -
?>
<script language="javascript1.2">

var config = new Object();    // create new config object

config.toolbar = [
 //   ['fontname'],
//    ['fontsize'],
      ['fontstyle'],
//    ['linebreak'],
    ['bold','italic','underline','separator'],
//  ['strikethrough','subscript','superscript','separator'],
    ['justifyleft','justifycenter','justifyright','separator'],
    ['OrderedList','UnOrderedList','Outdent','Indent','separator'],
    ['forecolor','backcolor','separator'],
    ['custom1'],
    ['HorizontalRule','Createlink','InsertImage','InsertTable','htmlmode','separator'],
   
];



config.stylesheet = "css/styles.css";
config.fontstyles = [
{ name: "Standard Text", className: "content", classStyle: "" }
];


if(document.all) editor_generate('<?php print 'CMS_HTML' ?>',config);
</script>

<?php

        echo "  </TR>";
        $tmp_area = "con_editcontent";
        echo "  <TR valign=top><TD colspan=2><br>
                      <a href=".$sess->url("view.php?area=$tmp_area&idside=$idside&idcat=$idcat&lang=$lang")."><img src=\"".$ContenidoPath.$cfgPathImg."but_cancel.gif\" border=0></a>
                      <INPUT type=image name=submit value=submit src=\"".$ContenidoPath.$cfgPathImg."but_ok.gif\" border=0>
                      </TD></TR>";

        echo "  </TABLE>
                      </FORM>";

?>
</td></tr></table>

</body>
</HTML>
