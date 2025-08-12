<?php

$tmp_area = "upl";

$tpl2 = new Template;

$tpl->reset();
$tpl2->reset();

$db2 = new DB_Contenido;

if (isset($path)) {

    if (!isset($path) || $path == "./") {$path = "";};
    // Laden der Verzeichnisse und Dateien in separate Arrays
    if (@chdir($cfgClient[$client]['upl']['path'].rawurldecode($path))) {
    } else {
            die("");
    }


    # SELF_URL (Variable für das javascript);
    $tpl->set('s', 'SELF_URL', $sess->url("main.php?area=$area&frame=$frame&path=$path"));
    # Sortierungs select
    $s_types = array("fileasc" => i18n("Filename A-Z"),
                     "filedesc" => i18n("Filename Z-A"),
                     "descriptionasc" => i18n("Description A-Z"),
                     "descriptiondesc" => i18n("Description Z-A"),
                     "sizeasc" => i18n("File size")
    #                 "sizedesc" => "Dateigröße 99999-0"
                    );

    $tpl2->set('s', 'NAME', 'sort');
    $tpl2->set('s', 'CLASS', 'text_medium');
    $tpl2->set('s', 'OPTIONS', 'onchange="uplSort(this)"');

    foreach ($s_types as $key => $value) {

        $selected = ( isset($HTTP_GET_VARS['sort']) && $HTTP_GET_VARS['sort'] == $key ) ? 'selected="selected"' : '';

        $tpl2->set('d', 'VALUE',    $key);
        $tpl2->set('d', 'CAPTION',  $value);
        $tpl2->set('d', 'SELECTED', $selected);
        $tpl2->next();

    }





    $sql="SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient='$client' AND dirname='$path'";
    $db->query($sql);
    while($db->next_record()){
          if (!is_file($cfgClient[$client]["path"]["upload"].$path.$db->f("filename")))
          {
          	//echo "delete since ".$cfgClient[$client]["path"]["upload"].$path.$db->f("filename")." doesnt exist";
          	
          	$idupl = $db->f("idupl");
          	$sql = "DELETE FROM ".$cfg["tab"]["upl"]." WHERE idupl='$idupl'";
          	$db2->query($sql);
          } else {
          $filelist[$db->f("filename")]=$db->f("description");
          $filesize[$db->f("filename")]=$db->f("size");
          }
          

    }


    $handle = opendir(".");
    $upl_protected = explode(",",$cfgClient['upl']['protected']);
    while ($file = readdir($handle))        {
            if ($path.$file."/" == $cfgClient[$client]["path"]["upload"].$path.$con_cfg['PathFrontendTmp']) {
             } else {
                    if(is_dir($file)) {
                            $dirlist[] = $file;
                    }
            }
            if(is_file($file) && !isset($filelist[$file])) {
                 savefile($path,$file);
                 //set the filelist,   description is emty
                 $filelist[$file] = "";
                 $filesize[$file] = filesize($cfgClient[$client]['upl']['path'].$path.$file);
            }
    }
    closedir($handle);

	if ($perm->have_perm_area_action("upl","upl_upload"))
	{ 
        $uploadform = "<form action=\"".$sess->url("main.php?frame=$frame")."\" method=\"post\" enctype=\"multipart/form-data\">";
        $uploadform .= "<input type=\"hidden\" name=\"action\" value=\"upl_upload\">";
        $uploadform .= "<input type=\"hidden\" name=\"path\" value=\"$path\">\n";
        $uploadform .= '<table id="upload" style="border:0px; border-left:1px; border-bottom: 1px;border-color: '. $cfg["color"]["table_border"] .'; border-style: solid;" cellspacing="0" cellpadding="2" border="0">';
        $uploadform .=  '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_header"].';" >';
        $uploadform .= '<td valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;px">';
        $uploadform .= i18n("File upload");
        $uploadform .= "</td></tr>";
        $uploadform .=  '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_dark"].';" >';
        $uploadform .= '<td valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;px">';
        $uploadform .= " <input id=\"uplinput\" name=\"userfile[]\" type=\"file\">";
        $uploadform .= "</td></tr>";
        $uploadform .=  '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_light"].';" >';
        $uploadform .= '<td valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;px">';
        $uploadform .= "<input title=\"bla\" id=\"uplinput\" name=\"userfile[]\" type=\"file\">";
        $uploadform .= "</td></tr>";
        $uploadform .=  '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_dark"].';" >';
        $uploadform .= '<td valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;px">';    
        $uploadform .= "<input id=\"uplinput\" name=\"userfile[]\" type=\"file\">";
        $uploadform .= "</td></tr>";
        $uploadform .=  '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_light"].';" >';
        $uploadform .= '<td align="right" valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;px">';
        $uploadform .= "<input type=\"image\" src=\"".$cfg['path']['images']."but_ok.gif\" alt=\"".i18n("Upload file")."\" title=\"".i18n("Upload file")."\">";
        $uploadform .="</td></tr></table></form><br><br>\n";
	} else {
		$uploadform = "";
	}

    $tpl->set('s', 'UPLOADFORM', $uploadform);


    if (!isset($direct) ) $direct = "down";
    if($direct=="down"){
        $tmp_direct="up";
    }else{
        $tmp_direct="down";
    }


    // Die Dateien auflisten
    if(isset($filelist)) {
            $resize      = $con_cfg["thumb"]["resize"];
            $aspectratio = $con_cfg["thumb"]["aspectratio"];
            $pathurl     = rawurlencode($path);

            // sortierung)
            if(isset($sort)){
                switch($sort){
                    case "descriptionasc":  asort($filelist);
                        break;
                    case "fileasc":         ksort($filelist);
                        break;
                    case "sizeasc":         array_multisort ($filesize, $filelist,SORT_ASC);
                        break;
                    case "descriptiondesc": arsort($filelist);
                        break;
                    case "filedesc":        krsort($filelist);
                        break;
                    case "sizedesc":        array_multisort ($filesize, $filelist,SORT_DESC);
                        break;
                }
            }else{
                ksort($filelist);
            }

            $counter = 0;
            foreach ($filelist as $file => $descript) {

                $counter++;

                //Imagesize
                $size = getimagesize($cfgClient[$client]['upl']['path'].$path.$file);

                //Filesize
                $html_filesize = $filesize[$file]." Bytes";

                if (isset($con_cfg['thumb']['width']) && isset($con_cfg['thumb']['height'])) {
                        $width       = $con_cfg['thumb']['width'];
                        $height      = $con_cfg['thumb']['height'];
                        $htmlfileurl = uplresize($cfgClient[$client]['upl']['path'].$path.$file,$width,$height,$resize,$aspectratio);
//echo "htmlfileurl: $htmlfileurl<br>";
                }

                // Dateien auflisten, dabei unterscheiden zwischen den versch Formaten
                if (ereg(".jpg|.png|.gif",strtolower($file))) {

                     $previewimage = "<a href=\"javascript:iZoom('".$cfgClient[$client]['upl']['htmlpath'].rawurldecode($path).$file."',".$size[0].",".($size[1]+20).")\">";

                     if ($htmlfileurl) {
                             $previewimage .= "<img src=\"".$htmlfileurl."\" width=\"".$width."\" height=\"".$height."\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\">";
                     } else {
                             $previewimage .= "<img src=\"".$cfg['path']['images']."upl_image.gif\" width=\"16\" height=\"16\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\">";
                     }

                     $previewimage .= "</a>";

                     $html_filename = "<a class=\"action\" href=\"javascript:iZoom('".$cfgClient[$client]['upl']['htmlpath'].rawurldecode($path).$file."',".$size[0].",".($size[1]+20).")\">".htmlspecialchars($file)."</a>";

                } elseif (ereg(".bmp|.cdr|.pcd|.msp",strtolower($file))) {

                     $previewimage = "<a href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\"><img src=\"".$cfg['path']['images']."upl_image.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\"></a>";

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\"  target=\"_blank\">".htmlspecialchars($file)."</a>";

                } elseif (ereg(".swf",strtolower($file))) {

                     if ($htmlfileurl) {
                             $previewimage = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"".$htmlfileurl."\"><param name=\"quality\" value=\"high\"><param name=\"bgcolor\" value=\"#DBE3EF\"><embed src=\"".$htmlfileurl."\" quality=\"high\" bgcolor=\"#DBE3EF\" width=\"".$width."\" height=\"".$height."\" type=\"application/x-shockwave-flash\" pluginspace=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"></embed></object>";
                     } else {
                             $previewimage = "<img src=\"".$cfg['path']['images']."upl_flash.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\">";
                     }

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\">".htmlspecialchars($file)."</a>";

                } elseif (ereg(".pdf",strtolower($file))) {

                     $previewimage  = "<a href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\"  target=\"_blank\"><img src=\"".$cfg['path']['images']."pdf.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\"></a>";

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\">".htmlspecialchars($file)."</a>";

                } elseif (ereg(".wav|.mp2|.mp3|.mp4|.vqf|.midi",strtolower($file))) {

                     $previewimage  = "<a href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\"  target=\"_blank\"><img src=\"".$cfg['path']['images']."upl_audio.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\"></a>";

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\">".htmlspecialchars($file)."</a>";

                } elseif (ereg(".txt",strtolower($file))) {

                     $previewimage  = "<a href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\"><img src=\"".$cfg['path']['images']."upl_text.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\"></a>";

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\">".htmlspecialchars($file)."</a>";

                } else {

                     $previewimage = "<a href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\"><img src=\"".$cfg['path']['images']."upl_unknown.gif\" alt=\"".i18n("Show file")."\" title=\"".i18n("Show file")."\" border=\"0\"></a>";

                     $html_filename = "<a class=\"action\" href=\"".$cfgClient[$client]['upl']['htmlpath'].$path.$file."\" target=\"_blank\">".htmlspecialchars($file)."</a>";

                }

                //Beschreibung
                if ($action == 50 && $file == $edit) {
                        $html_description = "<form method=\"post\" action=\"".$sess->url("main.php?frame=$frame")."\">\n
                                             <input type=\"hidden\" name=\"action\" value=\"51\">\n
                                             <input type=\"hidden\" name=\"path\" value=\"$path\">\n
                                             <input type=\"hidden\" name=\"edit\" value=\"$edit\">\n
                                             <input type=\"text\" name=\"newfile\" value=\"".$descript."\">&nbsp;\n
                                             <a href=\"".$sess->url("main.php?area=upl&frame=$frame&path=$path")."\"><img src=\"".$cfg['path']['images']."but_cancel.gif\" border=\"0\" alt=\"".$lngUpl["gen_break"]."\" title=\"".$lngUpl["gen_break"]."\"></a>&nbsp;<input type=\"image\" src=\"".$cfg['path']['images']."but_ok.gif\" alt=\"".$lngUpl["renamefile"]."\" title=\"".$lngUpl["renamefile"]."\">\n
                                             </form>\n";

                } else {
                        $html_description = $descript;
                }


                //Edit Button
                $html_editbutton =  "<a class=\"action\" href=\"".$sess->url("main.php?area=upl&action=50&frame=$frame&path=$path&edit=$file")."#edit\"><img src=\"".$cfg['path']['images']."but_rename.gif\" border=\"0\" alt=\"".i18n("Rename file")."\" title=\"".i18n("Rename file")."\" ></a>&nbsp;";

                //Delete Button
                if ($perm->have_perm_area_action("upl","upl_delete")) {
                        $html_deletebutton = "<a href=\"".$sess->url("main.php?area=upl&action=upl_delete&frame=$frame&path=$pathurl&del=$file")."#deletethis\"><img src=\"".$cfg['path']['images']."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".i18n("Delete file")."\" title=\"".i18n("Delete file")."\"></a>&nbsp;";
                }


                $bgcolor = ( is_int($row / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];

                $tpl->set('d', 'BGCOLOR', $bgcolor);
                $tpl->set('d', 'PREVIEWIMAGE', $previewimage);
                $tpl->set('d', 'FILENAME', $html_filename);
                $tpl->set('d', 'FILEDESCRIPTION', $html_description);
                $tpl->set('d', 'FILESIZE', $html_filesize);

                if (isset($size[0]) && isset($size[1]))
                {
                    $tpl->set('d', 'IMAGESIZE', $size[0].'x'.$size[1]);
                } else {
                    $tpl->set('d', 'IMAGESIZE', '');
                }
                
                $tpl->set('d', 'EDITBUTTON', $html_editbutton);
                $tpl->set('d', 'DELETEBUTTON', $html_deletebutton);
                    
                if ( is_int($counter/2) ) {
                    $tpl->set('d', 'ENDTR', "</tr>");
                    $tpl->set('d', 'BEGINTR', '');
                    $row++;
                } else {
                    $tpl->set('d', 'BEGINTR', "<tr style=\"background-color: ".$bgcolor."\"> ");
                    $tpl->set('d', 'ENDTR', '');
                }

                $tpl->set('d', 'ENDTD', '');

                $tpl->next();

        }

        

    }

        if (($counter > 1) && (!is_int($counter/2)))
        {
            $tpl->set('s', 'ENDTD', '<td colspan="2" align="left" valign="top" style="background-color: #FFFFFF; border: 0px; border-color: #747488; border-style: solid">&nbsp;</td></tr>');
        } else {
            $tpl->set('s', 'ENDTD', '');
        }

chdir($cfg['path']['contenido']);
$select = $tpl2->generate($cfg["path"]["templates"] . $cfg['templates']['generic_select'], true);
$tpl->set('s', 'UPLSORT', $select);
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['upl_files_overview']);

} else {
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
}
?>
