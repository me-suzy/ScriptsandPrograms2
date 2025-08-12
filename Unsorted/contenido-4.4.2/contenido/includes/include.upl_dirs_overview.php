<?php

/******************************************
* File      :   include.upl_dirs_overview.php
* Project   :   Contenido
* Descr     :   Displays directories in
*               upload directory of client
*
* Author    :   Olaf Niemann
* Created   :   30.03.2003
* Modified  :   30.03.2003
*
* © four for business AG
*****************************************/

if (!isset($action)) $action = "";

$tmp_area = 7;

$tpl->reset();

$path = "";


// Laden der Verzeichnisse und Dateien in separate Arrays
if (@chdir($cfgClient[$client]['upl']['path'].rawurldecode($path))) {
} else {
        die("");
}

$dirlist = uplDirectoryListRecursive($cfgClient[$client]['upl']['path'].rawurldecode($path));

$file = 'Upload';
$pathstring = '';

# create javascript multilink
$tmp_mstr = '<a href="javascript:conMultiLink(\'%s\', \'%s\')">%s</a>';
$mstr = sprintf($tmp_mstr, 'right_bottom',
                $sess->url("main.php?area=upl&frame=4&path=$pathstring"),
                '<img src="images/ordner_oben.gif" width="15" height="13" alt="" border="0"><img src="images/spacer.gif" width="5" border="0">'.$file);

$bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];

$tpl->set('s', 'PATH_0', $pathstring);
$tpl->set('s', 'BGCOLOR_0', $bgcolor);
$tpl->set('s', 'INDENT_0',  0);
$tpl->set('s', 'DIRNAME_0', $mstr);
$tpl->set('s', 'EDITBUTTON_0', '');
$tpl->set('s', 'DELETEBUTTON_0', '');
$tpl->set('s', 'SID', $sess->id);

if( is_array($dirlist) ) {
    foreach ($dirlist as $a_file) {

        $file       = $a_file['name'];
        $depth      = $a_file['depth'];
        $pathstring = $a_file['pathstring'];

        $fileurl = rawurlencode($path.$file.'/');
        $pathurl = rawurlencode($path);

        # Indent for every level
        $cnt = $depth;
        $indent = 15;

        for ($i = 0; $i < $cnt; $i ++) {
            # 15 px for every level
            $indent += 15;
        }

        # create javascript multilink
        $tmp_mstr = '<a href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';
        $mstr = sprintf($tmp_mstr, 'right_bottom',
                        $sess->url("main.php?area=upl&frame=4&path=$pathstring"),
                        'left_bottom',
                        $sess->url("main.php?area=upl&frame=2&path=$pathstring"),
                        '<img src="images/ordner_normal.gif" border="0" width="15" height="13" alt=""><img src="images/spacer.gif" width="5" border="0">'.$file);

        $editbutton = "";

        $hasFiles = uplHasFiles($pathstring);

        if (!$hasFiles && $perm->have_perm_area_action($tmp_area,"upl_rmdir") ) { #&& !in_array($path.$file."/", $upl_protected)) {
            $deletebutton = '<a title="Verzeichnis löschen" href="javascript://" onclick="event.cancelBubble=true;box.confirm(\'Verzeichnis löschen\', \'Das Verzeichnis <br><br><b>'.$file.'</b><br><br>wirklich löschen?\', \'deleteDirectory(\\\''.$pathstring.'\\\')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="Verzeichnis löschen" alt="Verzeichnis löschen"></a>';
//$deletebutton = "<a onClick=\"event.cancelBubble=true;\" href=\"".$sess->url("main.php?area=upl&action=upl_delete&frame=$frame&path=$pathstring&del=")."#deletethis\"><img src=\"".$cfg["path"]["images"]."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".$lngUpl["delfolder"]."\" title=\"".$lngUpl["delfolder"]."\"></a>";
        } else {
            if ($hasFiles)
            {
                $message = i18n("Directory contains files");
            } else {
                $message = i18n("Permission denied");
            }

            $deletebutton = "<img src=\"".$cfg["path"]["images"]."delete_inact.gif\" border=\"0\" alt=\"".$message."\" title=\"".$message."\">";
        }

        $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];

        $tpl->set('d', 'PATH', $pathstring);
        $tpl->set('d', 'BGCOLOR', $bgcolor);
        $tpl->set('d', 'INDENT',  $indent);
        $tpl->set('d', 'DIRNAME', $mstr);
        $tpl->set('d', 'EDITBUTTON', $editbutton);
        $tpl->set('d', 'DELETEBUTTON', $deletebutton);
        $tpl->next();

    }
}
chdir($cfg['path']['contenido']);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['upl_dirs_overview']);




/***********
                if ($action == 20 && rawurldecode($file) == $del&&$perm->have_perm_area_action($tmp_area,"21")) {
                        echo "        <a name=\"deletethis\">
                                      <a href=\"".$sess->url("main.php?area=upl&frame=$frame&action=21&path=$pathurl&del[0]=$del")."\">
                                      <img src=\"".$cfgPathImg."but_confirm.gif\" border=\"0\" alt=\"".$lngUpl["delfolder"]."\" title=\"".$lngUpl["delfolder"]."\"></a>&nbsp;
                                      <a href=\"".$sess->url("main.php?area=upl&frame=$frame&path=$path")."\"><img src=\"".$cfgPathImg."but_cancel_delete.gif\" border=\"0\" alt=\"".$lngUpl["gen_break"]."\" title=\"".$lngUpl["gen_break"]."\"></a>&nbsp;\n";
                  }
                echo "        </td>\n";
                echo "        <td class=\"content2\" align=\"right\" nowrap>&nbsp;";

                //************* Löschen Button *************
                if ($perm->have_perm_area_action($tmp_area,"21") && !in_array($path.$file."/", $upl_protected)) {

                        echo "&nbsp;<img src=\"".$cfgPathImg."space.gif\" border=\"0\" width=\"18\" height=\"15\">&nbsp;";
                        echo "<a href=\"".$sess->url("main.php?area=upl&action=20&frame=$frame&path=$pathurl&del=$file")."#deletethis\"><img src=\"".$cfgPathImg."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".$lngUpl["delfolder"]."\" title=\"".$lngUpl["delfolder"]."\"></a>&nbsp;";

                }

              echo "</td>\n";
            echo "      </tr>\n";
       }
}
if ($action==30 && $perm->have_perm_area_action($tmp_area,"upl_mkdir")) {
        echo "      <form method=\"post\" action=\"".$sess->url("main.php?frame=$frame")."\">\n";
        echo "        <input type=\"hidden\" name=\"action\" value=\"31\">\n";
        echo "        <input type=\"hidden\" name=\"path\" value=\"$path\">\n";
        echo "      <tr>\n";

        echo "        <td class=\"content2\" colspan=\"1\">
                          <a name=\"newfolder\">&nbsp;<img src=\"".$cfgPathImg."upl_folder.gif\" width=\"20\" height=\"17\" border=\"0\">\n";
        echo "        <input type=\"text\" name=\"foldername\" value=\"".$foldername."\"></td>\n";

        echo "        <td class=\"content2\" align=\"right\" nowrap>
                          <a href=\"".$sess->url("main.php?area=upl&frame=$frame&path=$path")."\"><img src=\"".$cfgPathImg."but_cancel.gif\" border=\"0\" alt=\"".$lngUpl["gen_break"]."\" title=\"".$lngUpl["gen_break"]."\"></a>&nbsp;\n";
        echo "        <input type=\"image\" src=\"".$cfgPathImg."but_ok.gif\" alt=\"".$lngUpl["actions"]["30"]."\" title=\"".$lngUpl["actions"]["30"]."\">&nbsp;</td>\n";

        echo "      </tr>\n";
        echo "      </form>\n";
}




echo "    </table>\n";
//chdir($con_cfg['PathContenido']);

***************/

?>
