# phpMyAdmin MySQL-Dump
# version 2.3.2
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Erstellungszeit: 20. Juni 2003 um 15:58
# Server Version: 3.23.49
# PHP-Version: 4.1.2
# Datenbank: `Contenido base`


ALTER TABLE !PREFIX!_actions add KEY idarea (idarea);
ALTER TABLE !PREFIX!_actions add FULLTEXT KEY name (name);
ALTER TABLE !PREFIX!_actions add KEY name_2 (name);

ALTER TABLE !PREFIX!_area add KEY idarea (idarea,name,online);
ALTER TABLE !PREFIX!_area add FULLTEXT KEY name (name);
ALTER TABLE !PREFIX!_area add KEY idarea_2 (idarea);
ALTER TABLE !PREFIX!_area add KEY name_2 (name);

ALTER TABLE !PREFIX!_art add KEY idart (idart);
ALTER TABLE !PREFIX!_art add KEY idclient (idclient);

ALTER TABLE !PREFIX!_art_lang ADD INDEX idart (idart);
ALTER TABLE !PREFIX!_art_lang ADD INDEX idlang (idlang);
ALTER TABLE !PREFIX!_art_lang ADD INDEX idtplcfg (idtplcfg, idart);
ALTER TABLE !PREFIX!_art_lang ADD INDEX idart_2 (idart, idlang);


ALTER TABLE !PREFIX!_cat ADD INDEX idclient (idclient);
ALTER TABLE !PREFIX!_cat ADD INDEX idcat (idcat);
ALTER TABLE !PREFIX!_cat ADD INDEX idclient_2 (idclient, parentid);
ALTER TABLE !PREFIX!_cat ADD INDEX parentid (parentid, preid);

ALTER TABLE !PREFIX!_cat_art ADD INDEX is_start_2 (is_start, idcat);
ALTER TABLE !PREFIX!_cat_art ADD INDEX idart (idart);
ALTER TABLE !PREFIX!_cat_art ADD INDEX idcat (idcat);
ALTER TABLE !PREFIX!_cat_art ADD INDEX idcatart (idcatart);

ALTER TABLE !PREFIX!_cat_lang ADD INDEX idcat (idcat);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX idcatlang (idcatlang);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX idlang (idlang);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX idtplcfg (idtplcfg);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX idlang_2 (idlang, visible);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX idlang_3 (idlang, idcat);

ALTER TABLE !PREFIX!_cat_tree ADD INDEX idcat (idcat);

ALTER TABLE !PREFIX!_code ADD INDEX idcatart (idcatart);
ALTER TABLE !PREFIX!_code ADD INDEX idlang (idlang);
ALTER TABLE !PREFIX!_code ADD INDEX idclient (idclient);

ALTER TABLE !PREFIX!_container ADD INDEX idtpl (idtpl);
ALTER TABLE !PREFIX!_container ADD INDEX number (number);

ALTER TABLE !PREFIX!_container_conf ADD INDEX idtpl (idtpl);
ALTER TABLE !PREFIX!_container_conf ADD INDEX idtplcfg (idtplcfg);

ALTER TABLE !PREFIX!_content ADD INDEX idartlang (idartlang);
ALTER TABLE !PREFIX!_content ADD INDEX idtype (idtype);
ALTER TABLE !PREFIX!_content ADD INDEX typeid (typeid);

ALTER TABLE !PREFIX!_frame_files add KEY idarea (idarea,idframe,idfile);

ALTER TABLE !PREFIX!_keywords add KEY keyword (keyword);
ALTER TABLE !PREFIX!_keywords add KEY idlang (idlang);
ALTER TABLE !PREFIX!_keywords add KEY idlang2 (idlang, keyword);

ALTER TABLE !PREFIX!_mod add KEY idclient (idclient);
ALTER TABLE !PREFIX!_mod add KEY idclient_2 (idmod, idclient);

ALTER TABLE !PREFIX!_template add KEY idclient (idclient);
ALTER TABLE !PREFIX!_template add KEY idlay (idlay);
ALTER TABLE !PREFIX!_template add KEY idtpl (idtpl);
ALTER TABLE !PREFIX!_template add KEY idtplcfg (idtplcfg);

ALTER TABLE !PREFIX!_template_conf add KEY idtpl (idtpl);
ALTER TABLE !PREFIX!_template_conf add KEY idtplcfg (idtplcfg);

ALTER TABLE !PREFIX!_upl add KEY idclient (idclient);


#
# Daten für Tabelle `!PREFIX!_type`
#

INSERT INTO !PREFIX!_type VALUES (1, 'CMS_HTMLHEAD', '/**\r\n * CMS_HTMLHEAD\r\n */\r\n$tmp = $a_content[\'CMS_HTMLHEAD\'][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = AddSlashes(AddSlashes($tmp));\r\n$tmp = str_replace("\\\\\\\'","\'",$tmp);\r\nif ($edit) {\r\n\r\n$before="<div id=\\"HTMLHEAD_".$db->f("idtype")."_".$val."\\" onFocus=\\"this.style.border=\'1px solid #bb5577\'\\"".\r\n       " onBlur=\\"this.style.border=\'1px dashed #bfbfbf\'\\" style=\\"border:1px dashed #bfbfbf\\" contentEditable=\\"true\\">";\r\n$editbutton = "</div><a href=\\"javascript:setcontent(\'$idartlang\',\'".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTMLHEAD&typenr=$val")."\')\\">\r\n<img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif\\" border=\\"0\\"></a>\r\n<a href=\\"javascript:setcontent(\'$idartlang\',\'0\')\\"><img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_speichern.gif\\" border=\\"0\\"></a>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$editbutton = str_replace("\\\\\\\'","\'",$editbutton);\r\n$before= AddSlashes(AddSlashes($before));\r\n$before = str_replace("\\\\\\\'","\'",$before);\r\nif ($tmp == "") {\r\n$tmp = "&nbsp;";\r\n}\r\n$tmp = $before.$tmp.$editbutton;}', 'Headline / HTML', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (2, 'CMS_HTML', '/**\r\n * CMS_HTML\r\n */\r\n$tmp = $a_content[\'CMS_HTML\'][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = AddSlashes(AddSlashes($tmp));\r\n$tmp = str_replace("\\\\\\\'","\'",$tmp);\r\nif ($edit) {\r\n\r\n$before="<div id=\\"HTML_".$db->f("idtype")."_".$val."\\" onFocus=\\"this.style.border=\'1px solid #bb5577\'\\"".\r\n       " onBlur=\\"this.style.border=\'1px dashed #bfbfbf\'\\" style=\\"border:1px dashed #bfbfbf\\" contentEditable=\\"true\\" >";\r\n$editbutton = "</div><a href=\\"javascript:setcontent(\'$idartlang\',\'".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTML&typenr=$val&lang=$lang")."\')\\">\r\n<img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithtml.gif\\" border=\\"0\\"></a>\r\n<a href=\\"javascript:setcontent(\'$idartlang\',\'0\')\\"><img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_speichern.gif\\" border=\\"0\\">   </a>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$editbutton = str_replace("\\\\\\\'","\'",$editbutton);\r\n$before= AddSlashes(AddSlashes($before));\r\n$before = str_replace("\\\\\\\'","\'",$before);\r\nif ($tmp == "") {\r\n$tmp = "&nbsp;";\r\n}\r\n$tmp = $before.$tmp.$editbutton;}', 'Text / HTML', 0, '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
INSERT INTO !PREFIX!_type VALUES (3, 'CMS_TEXT', '/**\r\n * CMS_TEXT\r\n */\r\n$tmp = $a_content["CMS_TEXT"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = nl2br($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\n$tmp = str_replace("<br />","<br>", $tmp);\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_TEXT&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edittext.gif\\" border=0></A>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$tmp = $tmp.$editbutton;}', 'Text / Standard', 0, '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
INSERT INTO !PREFIX!_type VALUES (4, 'CMS_IMG', '/**\r\n * CMS_IMG\r\n */\r\n \r\n$tmp = $a_content["CMS_IMG"][$val];\r\n$tmp = urldecode($tmp);\r\nif($tmp==""||$tmp=="0"){\r\n$tmp="";\r\n\r\n}else{\r\n\r\n$sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp."\'";\r\n\r\n$db2 = new DB_Contenido;\r\n$db2->query($sql);\r\n\r\nif ( $db2->next_record() ) {\r\n\r\n        $tmp = $cfgClient[$client]["path"]["htmlpath"].$cfgClient[$client]["upload"].$db2->f("dirname").$db2->f("filename");\r\n    }\r\n\r\n    $tmp = htmlspecialchars($tmp);\r\n$tmp = urldecode($tmp);\r\n    $tmp = str_replace("\'", "\\\'", $tmp);\r\n}', 'Image', 0, '', '2002-05-13 19:04:21', '2002-05-13 19:04:21');
INSERT INTO !PREFIX!_type VALUES (5, 'CMS_IMGDESCR', '/**\r\n * CMS_IMGDESCR\r\n */\r\n$tmp = $a_content["CMS_IMGDESCR"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = urldecode($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_IMG&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editimage.gif\\" border=0></A>";\r\n$editbutton = addslashes($editbutton);\r\n$editbutton = addslashes($editbutton);\r\n$tmp = $tmp.$editbutton;}', 'Description', 0, '', '2002-05-13 19:04:28', '2002-05-13 19:04:28');
INSERT INTO !PREFIX!_type VALUES (6, 'CMS_LINK', '/**\r\n * CMS_LINK\r\n */\r\nglobal $cfgClient;\r\nglobal $client;\r\n\r\n$tmp = urldecode($a_content["CMS_LINK"][$val]);\r\n\r\n/* internal link */\r\nif ( is_numeric($tmp) ) {\r\n\r\n   if ($contenido)\r\n   {\r\n      $tmp = $sess->url("front_content.php?idcatart=$tmp");\r\n   } else {\r\n     $tmp = "front_content.php?idcatart=$tmp";\r\n   }\r\n\r\n} else {\r\nif ((substr($tmp,0,6) != "mailto") && (substr($tmp,0,4) != "http"))\r\n{\r\n$tmp = $cfgClient[$client]["path"]["htmlpath"].$tmp;\r\n}\r\n\r\n}', 'Link', 0, '', '2002-05-13 19:04:36', '2002-05-13 19:04:36');

# Removed old CMS_LINK
#INSERT INTO !PREFIX!_type VALUES (6, 'CMS_LINK', 'CMS_LINK', '/**\r\n * CMS_LINK\r\n */\r\n$tmp = urldecode($a_content["CMS_LINK"][$val]);\r\n\r\n/* internal link */\r\nif ( is_numeric($tmp) ) {\r\n\r\n   if ($contenido)\r\n   {\r\n      $tmp = $sess->url("front_content.php?idcatart=$tmp");\r\n   } else {\r\n     $tmp = "front_content.php?idcatart=$tmp";\r\n   }\r\n\r\n} elseif ( substr($tmp,0,6) == "mailto" ) {\r\n} elseif ( substr($tmp,0,6) != "mailto" ) {\r\n    if (substr($tmp,0,7)=="http://") {\r\n\r\n    } elseif ( substr($tmp,0,7) != "http://" ) {\r\n        $tmp = "http://$tmp";\r\n    }\r\n}', 'Link', 0, '', '2002-05-13 19:04:36', '2002-05-13 19:04:36');
INSERT INTO !PREFIX!_type VALUES (7, 'CMS_LINKTARGET', '/**\r\n * CMS_LINKTARGET\r\n */\r\n$tmp = $a_content["CMS_LINKTARGET"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\n$tmp = urldecode($tmp);', 'Frame', 0, '', '2002-05-13 19:04:43', '2002-05-13 19:04:43');
INSERT INTO !PREFIX!_type VALUES (8, 'CMS_LINKDESCR', '/**\r\n * CMS_LINKDESCR\r\n */\r\n$tmp = $a_content["CMS_LINKDESCR"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = urldecode($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\nif ($edit) {\r\n    $editbutton = \'<a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_LINK&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editlink.gif" border=0></a>\';\r\n    $editbutton = addslashes($editbutton);\r\n$editbutton = addslashes($editbutton);\r\n    $tmp = $tmp.$editbutton;\r\n}', 'Description', 0, '', '2002-05-13 19:05:00', '2002-05-13 19:05:00');
INSERT INTO !PREFIX!_type VALUES (9, 'CMS_HEAD', '/**\r\n * CMS_HEAD\r\n */\r\n$tmp = $a_content["CMS_HEAD"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\n\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HEAD&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif\\" border=0></A>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$tmp = $tmp.$editbutton;}', 'Headline / Standard', 0, '', '2002-05-13 19:02:34', '2002-05-13 19:02:34');
INSERT INTO !PREFIX!_type VALUES (10, 'CMS_SWF', '/**\r\n * CMS_SWF\r\n */\r\n\r\nif ( !is_object($db2) ) $db2 = new DB_Contenido;\r\n\r\nif ( $edit ) {\r\n\r\n    $tmp_id = $a_content[\'CMS_SWF\'][$val];\r\n\r\n    $sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp_id."\' AND filetype = \'swf\'";\r\n\r\n    $db2->query($sql);\r\n\r\n    if ( $db2->next_record() ) {\r\n\r\n        $tmp_swf = $cfgClient[$client]["upload"] . $db2->f("dirname") . $db2->f("filename");\r\n        \r\n        $aImgSize = @getimagesize($tmp_swf);\r\n\r\n        $width  = $aImgSize[0];\r\n        $height = $aImgSize[1];\r\n\r\n        $tmp = \'<table cellspacing="0" cellpadding="0" border="0">\r\n\r\n                    <tr>\r\n                        <td>\r\n                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\r\n                               codebase="http://download.macromedia.com\r\n                               /pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"\r\n                               width="\'.$width.\'" height="\'.$height.\'" id="movie" align="">\r\n                               <param name="movie" value="\'.$tmp_swf.\'">\r\n                               <embed src="\'.$tmp_swf.\'" quality="high" width="\'.$width.\'"\r\n                                  height="\'.$height.\'" name="movie" align="" type="application/x-shockwave-flash"\r\n                                  plug inspage="http://www.macromedia.com/go/getflashplayer">\r\n                            </object>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td><a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SWF&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editswf.gif" border="0"></a></td>\r\n                    </tr>\r\n\r\n                </table>\';\r\n\r\n    } else {\r\n\r\n        $tmp = \'<a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SWF&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editswf.gif" border="0"></a>\';\r\n\r\n    }\r\n    \r\n} else {\r\n\r\n    $tmp_id = $a_content[\'CMS_SWF\'][$val];\r\n\r\n    $sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp_id."\' AND filetype = \'swf\'";\r\n\r\n    $db2->query($sql);\r\n\r\n    if ( $db2->next_record() ) {\r\n\r\n        $tmp_swf = $cfgClient[$client]["upload"] . $db2->f("dirname") . $db2->f("filename");\r\n\r\n        $aImgSize = @getimagesize($tmp_swf);\r\n\r\n        $width  = $aImgSize[0];\r\n        $height = $aImgSize[1];\r\n\r\n        $tmp = \'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\r\n                   codebase="http://download.macromedia.com\r\n                   /pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"\r\n                   width="\'.$width.\'" height="\'.$height.\'" id="movie" align="">\r\n                   <param name="movie" value="\'.$tmp_swf.\'">\r\n                   <embed src="\'.$tmp_swf.\'" quality="high" width="\'.$width.\'"\r\n                      height="\'.$height.\'" name="movie" align="" type="application/x-shockwave-flash"\r\n                      plug inspage="http://www.macromedia.com/go/getflashplayer">\r\n                </object>\';\r\n\r\n    } else {\r\n\r\n        $tmp = "";\r\n\r\n    }\r\n\r\n}', 'Flash Movie', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

#INSERT INTO !PREFIX!_type VALUES (1, 'CMS_HTMLHEAD', '/**\r\n * CMS_HTMLHEAD\r\n */\r\n$tmp = $a_content[\'CMS_HTMLHEAD\'][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = AddSlashes(AddSlashes($tmp));\r\n$tmp = str_replace("\\\\\\\'","\'",$tmp);\r\nif ($edit) {\r\n\r\n$before="<div id=\\"HTMLHEAD_".$db->f("idtype")."_".$val."\\" onFocus=\\"this.style.border=\'1px solid #bb5577\'\\"".\r\n       " onBlur=\\"this.style.border=\'1px dashed #bfbfbf\'\\" style=\\"border:1px dashed #bfbfbf\\" contentEditable=\\"true\\">";\r\n$editbutton = "</div><a href=\\"javascript:setcontent(\'$idartlang\',\'".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTMLHEAD&typenr=$val")."\')\\">\r\n<img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif\\" border=\\"0\\"></a>\r\n<a href=\\"javascript:setcontent(\'$idartlang\',\'0\')\\"><img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_speichern.gif\\" border=\\"0\\"></a>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$editbutton = str_replace("\\\\\\\'","\'",$editbutton);\r\n$before= AddSlashes(AddSlashes($before));\r\n$before = str_replace("\\\\\\\'","\'",$before);\r\nif ($tmp == "") {\r\n$tmp = "&nbsp;";\r\n}\r\n$tmp = $before.$tmp.$editbutton;}', 'Headline / HTML', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
#INSERT INTO !PREFIX!_type VALUES (2, 'CMS_HTML', '/**\r\n * CMS_HTML\r\n */\r\n$tmp = $a_content[\'CMS_HTML\'][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = AddSlashes(AddSlashes($tmp));\r\n$tmp = str_replace("\\\\\\\'","\'",$tmp);\r\nif ($edit) {\r\n\r\n$before="<div id=\\"HTML_".$db->f("idtype")."_".$val."\\" onFocus=\\"this.style.border=\'1px solid #bb5577\'\\"".\r\n       " onBlur=\\"this.style.border=\'1px dashed #bfbfbf\'\\" style=\\"border:1px dashed #bfbfbf\\" contentEditable=\\"true\\" >";\r\n$editbutton = "</div><a href=\\"javascript:setcontent(\'$idartlang\',\'".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTML&typenr=$val&lang=$lang")."\')\\">\r\n<img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithtml.gif\\" border=\\"0\\"></a>\r\n<a href=\\"javascript:setcontent(\'$idartlang\',\'0\')\\"><img src=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_speichern.gif\\" border=\\"0\\">   </a>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$editbutton = str_replace("\\\\\\\'","\'",$editbutton);\r\n$before= AddSlashes(AddSlashes($before));\r\n$before = str_replace("\\\\\\\'","\'",$before);\r\nif ($tmp == "") {\r\n$tmp = "&nbsp;";\r\n}\r\n$tmp = $before.$tmp.$editbutton;}', 'Text / HTML', 0, '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
#INSERT INTO !PREFIX!_type VALUES (3, 'CMS_TEXT', '/**\r\n * CMS_TEXT\r\n */\r\n$tmp = $a_content["CMS_TEXT"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = nl2br($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\n$tmp = str_replace("<br />","<br>", $tmp);\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_TEXT&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edittext.gif\\" border=0></A>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$tmp = $tmp.$editbutton;}', 'Text / Standard', 0, '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
#INSERT INTO !PREFIX!_type VALUES (4, 'CMS_IMG', '/**\r\n * CMS_IMG\r\n */\r\n \r\n$tmp = $a_content["CMS_IMG"][$val];\r\nif($tmp==""||$tmp=="0"){\r\n$tmp="";\r\n\r\n}else{\r\n\r\n$sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp."\'";\r\n\r\n$db2 = new DB_Contenido;\r\n$db2->query($sql);\r\n\r\nif ( $db2->next_record() ) {\r\n\r\n        $tmp = $cfgClient[$client]["path"]["htmlpath"].$cfgClient[$client]["upload"].$db2->f("dirname").$db2->f("filename");\r\n    }\r\n\r\n    $tmp = htmlspecialchars($tmp);\r\n    $tmp = str_replace("\'", "\\\'", $tmp);\r\n}', 'Image', 0, '', '2002-05-13 19:04:21', '2002-05-13 19:04:21');
#INSERT INTO !PREFIX!_type VALUES (5, 'CMS_IMGDESCR', '/**\r\n * CMS_IMGDESCR\r\n */\r\n$tmp = $a_content["CMS_IMGDESCR"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_IMG&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editimage.gif\\" border=0></A>";\r\n$editbutton = addslashes($editbutton);\r\n$editbutton = addslashes($editbutton);\r\n$tmp = $tmp.$editbutton;}', 'Description', 0, '', '2002-05-13 19:04:28', '2002-05-13 19:04:28');
#INSERT INTO !PREFIX!_type VALUES (6, 'CMS_LINK', '/**\r\n * CMS_LINK\r\n */\r\n$tmp = $a_content["CMS_LINK"][$val];\r\n\r\n/* internal link */\r\nif ( is_numeric($tmp) ) {\r\n    $tmp = $sess->url("front_content.php?idcatart=$tmp");\r\n} elseif ( substr($tmp,0,6) == "mailto" ) {\r\n} elseif ( substr($tmp,0,6) != "mailto" ) {\r\n    if (substr($tmp,0,7)=="http://") {\r\n\r\n    } elseif ( substr($tmp,0,7) != "http://" ) {\r\n        $tmp = "http://$tmp";\r\n    }\r\n}', 'Link', 0, '', '2002-05-13 19:04:36', '2002-05-13 19:04:36');
#INSERT INTO !PREFIX!_type VALUES (7, 'CMS_LINKTARGET', '/**\r\n * CMS_LINKTARGET\r\n */\r\n$tmp = $a_content["CMS_LINKTARGET"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);', 'Frame', 0, '', '2002-05-13 19:04:43', '2002-05-13 19:04:43');
#INSERT INTO !PREFIX!_type VALUES (8, 'CMS_LINKDESCR', '/**\r\n * CMS_LINKDESCR\r\n */\r\n$tmp = $a_content["CMS_LINKDESCR"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\nif ($edit) {\r\n    $editbutton = \'<a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_LINK&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editlink.gif" border=0></a>\';\r\n    $editbutton = addslashes($editbutton);\r\n$editbutton = addslashes($editbutton);\r\n    $tmp = $tmp.$editbutton;\r\n}', 'Description', 0, '', '2002-05-13 19:05:00', '2002-05-13 19:05:00');
#INSERT INTO !PREFIX!_type VALUES (9, 'CMS_HEAD', '/**\r\n * CMS_HEAD\r\n */\r\n$tmp = $a_content["CMS_HEAD"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("\'", "\\\'", $tmp);\r\nif ($edit) {$editbutton = "<A HREF=\\"".$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HEAD&typenr=$val&lang=$lang")."\\"><IMG SRC=\\"".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif\\" border=0></A>";\r\n$editbutton = AddSlashes(AddSlashes($editbutton));\r\n$tmp = $tmp.$editbutton;}', 'Headline / Standard', 0, '', '2002-05-13 19:02:34', '2002-05-13 19:02:34');
#INSERT INTO !PREFIX!_type VALUES (10, 'CMS_SWF', '/**\r\n * CMS_SWF\r\n */\r\n\r\nif ( !is_object($db2) ) $db2 = new DB_Contenido;\r\n\r\nif ( $edit ) {\r\n\r\n    $tmp_id = $a_content[\'CMS_SWF\'][$val];\r\n\r\n    $sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp_id."\' AND filetype = \'swf\'";\r\n\r\n    $db2->query($sql);\r\n\r\n    if ( $db2->next_record() ) {\r\n\r\n        $tmp_swf = $cfgClient[$client]["upload"] . $db2->f("dirname") . $db2->f("filename");\r\n        \r\n        $aImgSize = @getimagesize($tmp_swf);\r\n\r\n        $width  = $aImgSize[0];\r\n        $height = $aImgSize[1];\r\n\r\n        $tmp = \'<table cellspacing="0" cellpadding="0" border="0">\r\n\r\n                    <tr>\r\n                        <td>\r\n                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\r\n                               codebase="http://download.macromedia.com\r\n                               /pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"\r\n                               width="\'.$width.\'" height="\'.$height.\'" id="movie" align="">\r\n                               <param name="movie" value="\'.$tmp_swf.\'">\r\n                               <embed src="\'.$tmp_swf.\'" quality="high" width="\'.$width.\'"\r\n                                  height="\'.$height.\'" name="movie" align="" type="application/x-shockwave-flash"\r\n                                  plug inspage="http://www.macromedia.com/go/getflashplayer">\r\n                            </object>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td><a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SWF&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editswf.gif" border="0"></a></td>\r\n                    </tr>\r\n\r\n                </table>\';\r\n\r\n    } else {\r\n\r\n        $tmp = \'<a href="\'.$sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SWF&typenr=$val").\'"><img src="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].\'but_editswf.gif" border="0"></a>\';\r\n\r\n    }\r\n    \r\n} else {\r\n\r\n    $tmp_id = $a_content[\'CMS_SWF\'][$val];\r\n\r\n    $sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=\'".$client."\' AND idupl=\'".$tmp_id."\' AND filetype = \'swf\'";\r\n\r\n    $db2->query($sql);\r\n\r\n    if ( $db2->next_record() ) {\r\n\r\n        $tmp_swf = $cfgClient[$client]["upload"] . $db2->f("dirname") . $db2->f("filename");\r\n\r\n        $aImgSize = @getimagesize($tmp_swf);\r\n\r\n        $width  = $aImgSize[0];\r\n        $height = $aImgSize[1];\r\n\r\n        $tmp = \'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\r\n                   codebase="http://download.macromedia.com\r\n                   /pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"\r\n                   width="\'.$width.\'" height="\'.$height.\'" id="movie" align="">\r\n                   <param name="movie" value="\'.$tmp_swf.\'">\r\n                   <embed src="\'.$tmp_swf.\'" quality="high" width="\'.$width.\'"\r\n                      height="\'.$height.\'" name="movie" align="" type="application/x-shockwave-flash"\r\n                      plug inspage="http://www.macromedia.com/go/getflashplayer">\r\n                </object>\';\r\n\r\n    } else {\r\n\r\n        $tmp = "";\r\n\r\n    }\r\n\r\n}', 'Flash Movie', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

    

#
# Daten für Tabelle `!PREFIX!_actions`
#

INSERT INTO !PREFIX!_actions VALUES (63, 1, '10', 'con_makestart', 'conMakeStart ($idcatart, !$is_start);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (2, 1, '33', 'con_makeonline', 'conMakeOnline ($idart, $lang);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (3, 1, '41', 'con_deleteart', 'conDeleteArt ($idart);\r\n$tmp_notification = $notification->returnNotification("info", i18n("Article deleted"));', '', 1);
INSERT INTO !PREFIX!_actions VALUES (4, 1, '50', 'con_expand', 'conExpand ($idcat, $lang, $expanded);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (5, 3, '30', 'con_edit', '// Nothing', '', 1);
INSERT INTO !PREFIX!_actions VALUES (9, 6, '11', 'str_newtree', 'strNewTree($treename); strRemakeTreeTable();', '', 1);
INSERT INTO !PREFIX!_actions VALUES (10, 6, '21', 'str_newcat', 'strNewCategory($idcat, $categoryname);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (11, 6, '31', 'str_renamecat', 'strRenameCategory($idcat, $lang, $newcategoryname);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (12, 6, '40', 'str_makevisible', 'strMakeVisible($idcat, $lang, !$visible);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (13, 6, '50', 'str_makepublic', 'strMakePublic($idcat, $lang, !$public);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (14, 6, '61', 'str_deletecat', '$errno = strDeleteCategory($idcat);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (15, 6, '70', 'str_moveupcat', 'strMoveUpCategory($idcat); strRemakeTreeTable();', '', 1);
INSERT INTO !PREFIX!_actions VALUES (16, 6, '81', 'str_movesubtree', 'strMoveSubtree($idcat, $parentid_new); strRemakeTreeTable();', '', 1);
INSERT INTO !PREFIX!_actions VALUES (17, 7, '31', 'upl_mkdir', '$errno = uplmkdir($path,$foldername);  \r\n', '', 1);
INSERT INTO !PREFIX!_actions VALUES (61, 7, '31', 'upl_upload', '$userfile = $HTTP_POST_FILES[\'userfile\'][\'tmp_name\'];$userfile_name = $HTTP_POST_FILES[\'userfile\'][\'name\'];$userfile_size = $HTTP_POST_FILES[\'userfile\'][\'size\'];$errno = uplupload($path,$userfile,$userfile_name,$userfile_size);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (62, 7, '31', 'upl_delete', 'upldelete($path,$del);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (18, 9, '20', 'lay_edit', '$idlay = layEditLayout($idlay, $layname, $description, $code);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (19, 8, '31', 'lay_delete', '$errno = layDeleteLayout($idlay);\r\n', '', 1);
INSERT INTO !PREFIX!_actions VALUES (20, 11, '20', 'mod_edit', '$idmod = modEditModule($idmod, $name, $descr, $input, $output, $template);        ', '', 1);
INSERT INTO !PREFIX!_actions VALUES (21, 10, '31', 'mod_delete', 'modDeleteModule($idmod);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (22, 12, '31', 'tpl_delete', '$tmp_notification =  tplDeleteTemplate($idtpl);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (23, 13, '20', 'tpl_edit', '$idtpl = tplEditTemplate($changelayout, $idtpl, $tplname, $description, $idlay, $c);        ', '', 1);
INSERT INTO !PREFIX!_actions VALUES (347, 31, '', 'style_create', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (348, 31, '', 'style_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (349, 32, '', 'js_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (337, 16, '', 'news_editnewsletter', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (338, 16, '', 'news_createnewsletter', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (339, 16, '', 'news_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (340, 19, '', 'news_send', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (341, 50, '', 'recipients_editrecipient', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (342, 50, '', 'recipients_createrecipient', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (343, 50, '', 'recipients_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (345, 32, '', 'js_create', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (346, 11, '', 'mod_new', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (351, 20, '', 'stat_show', '', '', 0);
INSERT INTO !PREFIX!_actions VALUES (350, 49, '', 'log_show', '', '', 0);
INSERT INTO !PREFIX!_actions VALUES (35, 22, '10', 'lang_newlanguage', 'if (!is_numeric($targetclient)) { $targetclient = $client; } $errno = langNewLanguage("-- ".i18n("New language")." --",$targetclient);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (36, 22, '21', 'lang_renamelanguage', '$errno = langRenameLanguage($idlang, $name);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (37, 22, '31', 'lang_deletelanguage', '$errno = langDeleteLanguage($idlang);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (38, 22, '40', 'lang_activatelanguage', 'langActivateDeactivateLanguage($idlang, 1);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (39, 22, '41', 'lang_deactivatelanguage', 'langActivateDeactivateLanguage($idlang, 0);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (328, 13, '', 'tpl_new', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (48, 26, '20', '20', 'include($cfgPathInc."con_edittpl.inc.php");', '', 0);
INSERT INTO !PREFIX!_actions VALUES (44, 25, '12', 'user_saverightsarea', 'saverightsarea();  ', '', 0);
INSERT INTO !PREFIX!_actions VALUES (327, 9, '', 'lay_new', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (47, 40, '10', 'user_edit', '//fake action => edit frontenduser', '', 1);
INSERT INTO !PREFIX!_actions VALUES (352, 12, '', 'tpl_duplicate', 'tplDuplicateTemplate($idtpl);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (53, 28, '10', '10', 'if ( $installed == 0 ) {\r\n   installplugin();\r\n} else {\r\n   deinstallplugin();\r\n}', '', 1);
INSERT INTO !PREFIX!_actions VALUES (320, 7, '51', '51', 'uplrename($path,$edit,$newfile); ', '', 0);
INSERT INTO !PREFIX!_actions VALUES (317, 29, '10', '10', '//fake action for editing whole langfile', '', 1);
DELETE FROM !PREFIX!_actions WHERE idaction = '318';

INSERT INTO !PREFIX!_actions VALUES (316, 29, '21', '21', 'langnew ($idbereich);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (314, 29, '50', '50', 'langfileExpand ($expbereich,$expanded);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (315, 29, '20', '20', 'bereichnew ();', '', 0);
INSERT INTO !PREFIX!_actions VALUES (313, 29, '44', '44', 'langDelete ($idkey);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (312, 29, '41', '41', 'bereichDelete ($idbereich);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (7, 2, '41', '41', 'bereichDelete ($idbereich);', '', 0);
INSERT INTO !PREFIX!_actions VALUES (58, 1, '', 'con_makepublic', 'conMakePublic($idcat, $lang, $public);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (321, 30, '', 'tplcfg_edit', '// include ($cfg["path"]["includes"] . "include.tplcfg_edit_form.php");', '', 0);
INSERT INTO !PREFIX!_actions VALUES (57, 1, '', 'con_tplcfg_edit', 'include ($cfg["path"]["includes"] . "include.tplcfg_edit_form.php");', '', 1);
INSERT INTO !PREFIX!_actions VALUES (322, 31, '', 'style_edit', 'styleEdit($file, $code);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (323, 32, '', 'js_edit', 'jsEdit($file, $code);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (59, 1, '', 'con_makecatonline', 'conMakeCatOnline($idcat, $lang, $online);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (60, 1, '', 'con_changetemplate', 'if ($perm->have_perm_area_action("con","con_changetemplate") ||\r\n  $perm->have_perm_area_action_item("con","con_changetemplate",$idcat))\r\n{\r\nconChangeTemplateForCat($idcat, $idtpl);\r\n} else {\r\n$notification->displayNotification("error", i18n("Permission denied"));\r\n}', '', 1);
INSERT INTO !PREFIX!_actions VALUES (325, 39, '', 'user_createuser', '', '', 0);
INSERT INTO !PREFIX!_actions VALUES (326, 21, '', 'user_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (0, 0, '', 'fake_permission_action', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (329, 45, '', 'mycontenido_editself', '', '', 0);
INSERT INTO !PREFIX!_actions VALUES (330, 24, '', 'login', '//fake login action', '', 1);
INSERT INTO !PREFIX!_actions VALUES (353, 30, '', 'str_tplcfg', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (334, 48, '', 'client_new', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (335, 48, '', 'client_edit', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (336, 46, '', 'client_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (40, 22, '', 'lang_editlanguage', '\r\n\r\nlangEditLanguage($idlang, $langname, $sencoding, $active);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (354, 54, '', 'group_delete', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (355, 60, '', 'group_create', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (356, 61, '', 'group_edit', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (357, 63, '', 'group_deletemember', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (358, 63, '', 'group_addmember', '', '', 1);
INSERT INTO !PREFIX!_actions VALUES (359, 6, '', 'front_allow', '// fake action', '', 1);
INSERT INTO !PREFIX!_actions VALUES (56, 2, '', 'con_editart', '/* Action für \'con_editart\' */\r\n$path = $cfgClient[$client]["path"]["htmlpath"];\r\n\r\n$url = $sess->url("front_content.php?changeview=$changeview&action=$action&idartlang=$idartlang&type=$type&typenr=$typenr&idart=$idart&idcat=$idcat&idcatart=$idcatart&lang=$lang");\r\nheader("location: $path$url");\r\n', 'rights/content/article/edit', 1);
INSERT INTO !PREFIX!_actions VALUES (55, 3, '', 'con_saveart', 'if (isset($title)) {\r\n\r\n	if (1 == $tmp_firstedit) {	\r\n	\r\n        $idart = conEditFirstTime($idcat, $idcatnew, $idart, $is_start, $idtpl, $idartlang, $idlang, $title, $summary, $created, $lastmodified, $author, $online, $datestart, $dateend, $artsort);\r\n        $tmp_notification = $notification->returnNotification("info", i18n("Changes saved"));\r\n\r\n        if ( !isset($idartlang) ) {\r\n            $sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE idart = $idart AND idlang = $lang";\r\n            $db->query($sql);\r\n            $db->next_record();\r\n            $idartlang = $db->f("idartlang");\r\n        }\r\n\r\n        if ( in_array($idcat, $idcatnew) ) {\r\n\r\n            $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat = \'".$idcat."\' AND idart = \'".$idart."\'";\r\n\r\n            $db->query($sql);\r\n            $db->next_record();\r\n\r\n            $tmp_idcatart = $db->f("idcatart");\r\n\r\n            if ( $is_start == 1 ) {\r\n                conMakeStart($tmp_idcatart, $is_start);\r\n            }\r\n\r\n        }\r\n        \r\n        if ( is_array($idcatnew) ) {\r\n            if ( is_array($idcatnew) ) {\r\n\r\n                foreach ( $idcatnew as $idcat ) {\r\n\r\n                    $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat = $idcat AND idart = $idart";\r\n\r\n                    $db->query($sql);\r\n                    $db->next_record();\r\n\r\n                    conSetCodeFlag( $db->f("idcatart") );\r\n\r\n                }\r\n            }\r\n        }\r\n\r\n\r\n\r\n    } else {\r\n\r\n        conEditArt($idcat, $idcatnew, $idart, $is_start, $idtpl, $idartlang, $idlang, $title, $summary, $created, $lastmodified, $author, $online, $datestart, $dateend, $artsort);\r\n        $tmp_notification = $notification->returnNotification("info", i18n("Changes saved"));\r\n\r\n        if ( !isset($idartlang) ) {\r\n            $sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE idart = $idart AND idlang = $lang";\r\n            $db->query($sql);\r\n            $db->next_record();\r\n            $idartlang = $db->f("idartlang");\r\n        }\r\n\r\n        if ( is_array($idcatnew) ) {\r\n            if ( in_array($idcat, $idcatnew) ) {\r\n\r\n                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat = \'".$idcat."\' AND idart = \'".$idart."\'";\r\n\r\n                $db->query($sql);\r\n                $db->next_record();\r\n\r\n                $tmp_idcatart = $db->f("idcatart");\r\n\r\n                if ( $is_start == 1 ) {\r\n                    conMakeStart($tmp_idcatart, $is_start);\r\n                }\r\n\r\n            }\r\n        }\r\n\r\n        if ( is_array($idcatnew) ) {\r\n\r\n            foreach ( $idcatnew as $idcat ) {\r\n\r\n                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat = $idcat AND idart = $idart";\r\n\r\n                $db->query($sql);\r\n                $db->next_record();\r\n\r\n                conSetCodeFlag( $db->f("idcatart") );\r\n\r\n            }\r\n        }\r\n    }\r\n}\r\n\r\n', '', 0);
INSERT INTO !PREFIX!_actions VALUES (54, 3, '', 'con_newart', '/* Code for action\r\n   \'con_newart\' */\r\n$sql = "SELECT\r\n            a.idtplcfg,\r\n            a.name\r\n        FROM\r\n            ".$cfg["tab"]["cat_lang"]." AS a,\r\n            ".$cfg["tab"]["cat"]." AS b\r\n        WHERE\r\n            a.idlang    = \'".$lang."\' AND\r\n            b.idclient  = \'".$client."\' AND\r\n            a.idcat     = \'".$idcat."\' AND\r\n            b.idcat     = a.idcat";\r\n\r\n$db->query($sql);\r\n$db->next_record();\r\n\r\nif ( $db->f("idtplcfg") != 0 ) {\r\n$newart = true;\r\n \r\n\r\n} else {\r\n\r\n    $noti_html = \'<table cellspacing="0" cellpadding="2" border="0">\r\n\r\n                    <tr class="text_medium">\r\n                        <td colspan="2">\r\n                            <b>Fehler bei der Erstellung des Artikels</b><br><br>\r\n                            Der Kategorie ist kein Template zugewiesen.\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <tr>\r\n                        <td colspan="2">&nbsp;</td>\r\n                    </tr>\r\n\r\n                  </table>\';\r\n\r\n    $code = \'\r\n            <html>\r\n                <head>\r\n                    <title>Error</title>\r\n                    <link rel="stylesheet" type="text/css" href="\'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["styles"].\'contenido.css"></link>\r\n                </head>\r\n                <body style="margin: 10px">\'.$notification->returnNotification("error", $noti_html).\'</body>\r\n            </html>\';\r\n\r\n    echo $code;\r\n\r\n}', '', 1);
INSERT INTO !PREFIX!_actions VALUES (378, 1, '', 'con_lock', 'conLock ($idart, $lang);', '', 1);
INSERT INTO !PREFIX!_actions VALUES (379, 65, '', 'emptyLog', '$tmp_notification = emptyLogFile();', '', 0);
INSERT INTO !PREFIX!_actions VALUES (380, 66, '', 'sendMail', '$tmpReturnVar = sendBugReport();', '', 0);
INSERT INTO !PREFIX!_actions VALUES (381, 7, '', 'upl_rmdir', '', '', 1);


#
# Daten für Tabelle `!PREFIX!_area`
#

INSERT INTO !PREFIX!_area VALUES (3, 'con', 'con_editart', 1, 1);
INSERT INTO !PREFIX!_area VALUES (2, 'con', 'con_editcontent', 1, 1);
INSERT INTO !PREFIX!_area VALUES (1, '0', 'con', 1, 1);
INSERT INTO !PREFIX!_area VALUES (6, '0', 'str', 1, 1);
INSERT INTO !PREFIX!_area VALUES (7, '0', 'upl', 1, 1);
INSERT INTO !PREFIX!_area VALUES (8, '0', 'lay', 1, 1);
INSERT INTO !PREFIX!_area VALUES (9, 'lay', 'lay_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (10, '0', 'mod', 1, 1);
INSERT INTO !PREFIX!_area VALUES (11, 'mod', 'mod_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (12, '0', 'tpl', 1, 1);
INSERT INTO !PREFIX!_area VALUES (13, 'tpl', 'tpl_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (16, '0', 'news', 1, 1);
INSERT INTO !PREFIX!_area VALUES (17, 'news', 'news_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (53, '0', 'symbolhelp', 0, 1);
INSERT INTO !PREFIX!_area VALUES (19, 'news', 'news_send', 1, 1);
INSERT INTO !PREFIX!_area VALUES (20, '0', 'stat', 1, 1);
INSERT INTO !PREFIX!_area VALUES (33, 'tpl', 'tpl_cfg', 1, 1);
INSERT INTO !PREFIX!_area VALUES (21, '0', 'user', 1, 1);
INSERT INTO !PREFIX!_area VALUES (22, '0', 'lang', 1, 1);
INSERT INTO !PREFIX!_area VALUES (24, '0', 'login', 0, 1);
INSERT INTO !PREFIX!_area VALUES (25, 'user', 'user_areas', 1, 1);
INSERT INTO !PREFIX!_area VALUES (26, 'news', 'news_edittpl', 1, 1);
INSERT INTO !PREFIX!_area VALUES (27, 'news', 'news_editcontent', 1, 1);
INSERT INTO !PREFIX!_area VALUES (28, '0', 'plug', 1, 1);
INSERT INTO !PREFIX!_area VALUES (5, 'con', 'con_tplcfg', 1, 1);
INSERT INTO !PREFIX!_area VALUES (29, '0', 'langfile', 1, 0);
INSERT INTO !PREFIX!_area VALUES (4, 'con', 'con_preview', 0, 1);
INSERT INTO !PREFIX!_area VALUES (30, 'str', 'str_tplcfg', 1, 1);
INSERT INTO !PREFIX!_area VALUES (31, '0', 'style', 1, 1);
INSERT INTO !PREFIX!_area VALUES (32, '0', 'js', 1, 1);
INSERT INTO !PREFIX!_area VALUES (40, 'user', 'user_overview', 1, 1);
INSERT INTO !PREFIX!_area VALUES (34, 'user', 'user_layout', 1, 1);
INSERT INTO !PREFIX!_area VALUES (35, 'user', 'user_content', 1, 1);
INSERT INTO !PREFIX!_area VALUES (36, 'user', 'user_module', 1, 1);
INSERT INTO !PREFIX!_area VALUES (37, 'user', 'user_template', 1, 1);
INSERT INTO !PREFIX!_area VALUES (38, 'user', 'user_structure', 1, 1);
INSERT INTO !PREFIX!_area VALUES (39, 'user', 'user_create', 1, 1);
INSERT INTO !PREFIX!_area VALUES (52, '0', 'info', 0, 1);
INSERT INTO !PREFIX!_area VALUES (42, '0', 'mycontenido', 0, 1);
INSERT INTO !PREFIX!_area VALUES (43, 'mycontenido', 'mycontenido_overview', 0, 1);
INSERT INTO !PREFIX!_area VALUES (44, 'mycontenido', 'mycontenido_tasks', 0, 1);
INSERT INTO !PREFIX!_area VALUES (45, 'mycontenido', 'mycontenido_settings', 0, 1);
INSERT INTO !PREFIX!_area VALUES (46, '0', 'client', 1, 1);
INSERT INTO !PREFIX!_area VALUES (47, 'lang', 'lang_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (48, 'client', 'client_edit', 1, 1);
INSERT INTO !PREFIX!_area VALUES (49, '0', 'logs', 1, 1);
INSERT INTO !PREFIX!_area VALUES (50, '0', 'recipients', 1, 1);
INSERT INTO !PREFIX!_area VALUES (54, '0', 'groups', 1, 1);
INSERT INTO !PREFIX!_area VALUES (55, 'groups', 'groups_layout', 1, 1);
INSERT INTO !PREFIX!_area VALUES (56, 'groups', 'groups_content', 1, 1);
INSERT INTO !PREFIX!_area VALUES (57, 'groups', 'groups_module', 1, 1);
INSERT INTO !PREFIX!_area VALUES (58, 'groups', 'groups_template', 1, 1);
INSERT INTO !PREFIX!_area VALUES (59, 'groups', 'groups_structure', 1, 1);
INSERT INTO !PREFIX!_area VALUES (60, 'groups', 'groups_create', 1, 1);
INSERT INTO !PREFIX!_area VALUES (61, 'groups', 'groups_overview', 1, 1);
INSERT INTO !PREFIX!_area VALUES (62, 'groups', 'groups_areas', 1, 1);
INSERT INTO !PREFIX!_area VALUES (63, 'groups', 'groups_members', 1, 1);
INSERT INTO !PREFIX!_area VALUES (64, '0', 'debug', 0, 0);
INSERT INTO !PREFIX!_area VALUES (66, 'system', 'syserrorreport', 1, 1);
INSERT INTO !PREFIX!_area VALUES (65, '0', 'system', 1, 1);

#
# Daten für Tabelle `!PREFIX!_files`
#

INSERT INTO !PREFIX!_files VALUES (1, 10, 'include.mod_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (2, 11, 'include.mod_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (3, 11, 'functions.mod.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (4, 8, 'include.lay_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (5, 9, 'include.lay_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (6, 10, 'include.mod_new.php', 'main');
INSERT INTO !PREFIX!_files VALUES (7, 9, 'functions.lay.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (8, 10, 'functions.mod.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (9, 1, 'include.con_str_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (10, 1, 'include.con_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (11, 1, 'include.con_art_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (12, 1, 'include.con_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (13, 8, 'include.lay_new.php', 'main');
INSERT INTO !PREFIX!_files VALUES (14, 8, 'functions.lay.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (15, 12, 'include.tpl_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (16, 13, 'include.tpl_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (17, 13, 'functions.tpl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (18, 12, 'include.tpl_new.php', 'main');
INSERT INTO !PREFIX!_files VALUES (19, 12, 'functions.tpl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (20, 7, 'functions.upl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (21, 7, 'include.upl_files_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (22, 1, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (23, 6, 'functions.str.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (24, 6, 'include.str_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (25, 7, 'include.upl_dirs_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (26, 7, 'include.upl_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (27, 22, 'functions.lang.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (28, 22, 'include.lang_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (29, 30, 'include.tplcfg_edit.php', 'main');
INSERT INTO !PREFIX!_files VALUES (30, 30, 'include.tplcfg_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (31, 3, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (32, 31, 'functions.style.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (33, 31, 'include.style_files_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (34, 31, 'include.style_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (35, 32, 'functions.js.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (36, 32, 'include.js_files_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (37, 32, 'include.js_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (38, 20, 'functions.stat.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (39, 20, 'include.stat_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (40, 20, 'include.stat_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (41, 31, 'include.style_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (42, 32, 'include.js_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (43, 12, 'include.tpl_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (45, 31, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (46, 32, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (47, 8, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (48, 10, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (49, 7, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (50, 20, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (51, 6, 'include.right_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (52, 22, 'include.subnav_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (53, 33, 'include.pretplcfg_edit.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (54, 33, 'include.pretplcfg_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (55, 33, 'functions.tpl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (56, 20, 'include.stat_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (57, 40, 'include.rights_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (58, 21, 'include.rights_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (59, 21, 'include.rights_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (60, 21, 'include.rights_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (61, 40, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (62, 25, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (63, 25, 'rights_area.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (64, 25, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (65, 34, 'rights_lay.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (66, 34, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (67, 35, 'rights_con.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (68, 35, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (69, 36, 'rights_mod.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (70, 36, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (71, 37, 'rights_tpl.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (72, 37, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (73, 38, 'rights_str.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (74, 38, 'rights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (75, 30, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (76, 30, 'functions.tpl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (77, 39, 'include.rights_create.php', 'main');
INSERT INTO !PREFIX!_files VALUES (78, 39, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (79, 39, 'include.rights_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (80, 24, 'main.login.php', 'main');
INSERT INTO !PREFIX!_files VALUES (81, 16, 'include.newsletter_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (82, 42, 'include.mycontenido_lastarticles.php', 'main');
INSERT INTO !PREFIX!_files VALUES (83, 42, 'include.mycontenido_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (84, 45, 'include.mycontenido_settings.php', 'main');
INSERT INTO !PREFIX!_files VALUES (85, 45, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (86, 43, 'include.mycontenido_lastarticles.php', 'main');
INSERT INTO !PREFIX!_files VALUES (87, 22, 'include.lang_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (88, 46, 'include.client_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (115, 47, 'functions.lang.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (90, 46, 'include.subnav_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (91, 47, 'include.lang_edit.php', 'main');
INSERT INTO !PREFIX!_files VALUES (92, 47, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (93, 46, 'include.client_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (94, 48, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (95, 48, 'include.client_edit.php', 'main');
INSERT INTO !PREFIX!_files VALUES (96, 49, 'include.log_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (97, 49, 'include.left_top_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (98, 49, 'include.log_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (99, 49, 'include.logs.php', 'main');
INSERT INTO !PREFIX!_files VALUES (100, 16, 'include.newsletter_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (101, 16, 'include.newsletter_edit.php', 'main');
INSERT INTO !PREFIX!_files VALUES (102, 16, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (103, 50, 'include.recipients_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (104, 50, 'include.recipients_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (105, 50, 'include.subnav_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (106, 50, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (107, 50, 'include.recipients_edit.php', 'main');
INSERT INTO !PREFIX!_files VALUES (108, 5, 'include.tplcfg_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (109, 5, 'include.tplcfg_edit.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (110, 5, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (111, 5, 'functions.tpl.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (112, 19, 'include.newsletter_send.php', 'main');
INSERT INTO !PREFIX!_files VALUES (113, 33, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (114, 16, 'include.subnav_blank.php', 'main');
INSERT INTO !PREFIX!_files VALUES (116, 22, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (117, 45, 'include.mycontenido_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (118, 52, 'include.info.php', 'main');
INSERT INTO !PREFIX!_files VALUES (119, 53, 'include.symbolhelp.php', 'main');
INSERT INTO !PREFIX!_files VALUES (120, 43, 'functions.con.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (121, 43, 'include.mycontenido_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (122, 54, 'include.grouprights_left_top.php', 'main');
INSERT INTO !PREFIX!_files VALUES (123, 54, 'include.grouprights_menu.php', 'main');
INSERT INTO !PREFIX!_files VALUES (124, 60, 'include.grouprights_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (125, 60, 'include.grouprights_create.php', 'main');
INSERT INTO !PREFIX!_files VALUES (126, 60, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (127, 61, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (128, 54, 'include.grouprights_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (129, 61, 'include.grouprights_overview.php', 'main');
INSERT INTO !PREFIX!_files VALUES (130, 62, 'grouprights_area.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (131, 62, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (132, 55, 'grouprights_lay.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (133, 55, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (134, 56, 'grouprights_con.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (135, 56, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (136, 57, 'grouprights_mod.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (137, 57, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (138, 58, 'grouprights_tpl.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (139, 58, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (140, 59, 'grouprights_str.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (141, 59, 'grouprights.inc.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (142, 63, 'grouprights_members.inc.php', 'main');
INSERT INTO !PREFIX!_files VALUES (143, 63, 'functions.forms.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (144, 64, 'include.debug.php', 'main');
INSERT INTO !PREFIX!_files VALUES (145, 3,  'include.con_edit_form.php', 'main');
INSERT INTO !PREFIX!_files VALUES (146, 65, 'include.system_sysvalues.php', 'main');
INSERT INTO !PREFIX!_files VALUES (147, 66, 'include.system_error_report.php', 'main');
INSERT INTO !PREFIX!_files VALUES (148, 65, 'include.system_subnav.php', 'main');
INSERT INTO !PREFIX!_files VALUES (149, 65, 'functions.system.php', 'inc');
INSERT INTO !PREFIX!_files VALUES (150, 66, 'functions.system.php', 'inc');

#
# Daten für Tabelle `!PREFIX!_frame_files`
#

INSERT INTO !PREFIX!_frame_files VALUES (1, 10, 2, 1);
INSERT INTO !PREFIX!_frame_files VALUES (2, 11, 4, 2);
INSERT INTO !PREFIX!_frame_files VALUES (3, 11, 4, 3);
INSERT INTO !PREFIX!_frame_files VALUES (4, 8, 2, 4);
INSERT INTO !PREFIX!_frame_files VALUES (5, 9, 4, 5);
INSERT INTO !PREFIX!_frame_files VALUES (6, 10, 1, 6);
INSERT INTO !PREFIX!_frame_files VALUES (7, 9, 4, 7);
INSERT INTO !PREFIX!_frame_files VALUES (8, 10, 4, 8);
INSERT INTO !PREFIX!_frame_files VALUES (9, 1, 2, 9);
INSERT INTO !PREFIX!_frame_files VALUES (10, 1, 1, 10);
INSERT INTO !PREFIX!_frame_files VALUES (11, 1, 4, 11);
INSERT INTO !PREFIX!_frame_files VALUES (12, 1, 3, 12);
INSERT INTO !PREFIX!_frame_files VALUES (13, 8, 1, 13);
INSERT INTO !PREFIX!_frame_files VALUES (14, 8, 4, 14);
INSERT INTO !PREFIX!_frame_files VALUES (15, 12, 2, 15);
INSERT INTO !PREFIX!_frame_files VALUES (16, 13, 4, 16);
INSERT INTO !PREFIX!_frame_files VALUES (17, 13, 4, 17);
INSERT INTO !PREFIX!_frame_files VALUES (18, 12, 1, 18);
INSERT INTO !PREFIX!_frame_files VALUES (19, 12, 4, 19);
INSERT INTO !PREFIX!_frame_files VALUES (20, 7, 4, 20);
INSERT INTO !PREFIX!_frame_files VALUES (21, 7, 4, 21);
INSERT INTO !PREFIX!_frame_files VALUES (22, 1, 4, 22);
INSERT INTO !PREFIX!_frame_files VALUES (23, 6, 4, 23);
INSERT INTO !PREFIX!_frame_files VALUES (24, 6, 4, 24);
INSERT INTO !PREFIX!_frame_files VALUES (25, 7, 2, 25);
INSERT INTO !PREFIX!_frame_files VALUES (26, 7, 2, 20);
INSERT INTO !PREFIX!_frame_files VALUES (27, 7, 1, 26);
INSERT INTO !PREFIX!_frame_files VALUES (95, 46, 2, 93);
INSERT INTO !PREFIX!_frame_files VALUES (96, 48, 4, 94);
INSERT INTO !PREFIX!_frame_files VALUES (30, 30, 4, 29);
INSERT INTO !PREFIX!_frame_files VALUES (31, 30, 4, 30);
INSERT INTO !PREFIX!_frame_files VALUES (32, 3, 4, 31);
INSERT INTO !PREFIX!_frame_files VALUES (33, 31, 2, 33);
INSERT INTO !PREFIX!_frame_files VALUES (34, 31, 4, 32);
INSERT INTO !PREFIX!_frame_files VALUES (35, 31, 4, 34);
INSERT INTO !PREFIX!_frame_files VALUES (36, 32, 2, 36);
INSERT INTO !PREFIX!_frame_files VALUES (37, 32, 4, 35);
INSERT INTO !PREFIX!_frame_files VALUES (38, 32, 4, 37);
INSERT INTO !PREFIX!_frame_files VALUES (39, 20, 4, 38);
INSERT INTO !PREFIX!_frame_files VALUES (40, 20, 4, 39);
INSERT INTO !PREFIX!_frame_files VALUES (41, 1, 2, 22);
INSERT INTO !PREFIX!_frame_files VALUES (42, 20, 2, 40);
INSERT INTO !PREFIX!_frame_files VALUES (43, 20, 2, 38);
INSERT INTO !PREFIX!_frame_files VALUES (44, 31, 1, 41);
INSERT INTO !PREFIX!_frame_files VALUES (45, 32, 1, 42);
INSERT INTO !PREFIX!_frame_files VALUES (46, 12, 3, 43);
INSERT INTO !PREFIX!_frame_files VALUES (47, 31, 3, 45);
INSERT INTO !PREFIX!_frame_files VALUES (48, 32, 3, 46);
INSERT INTO !PREFIX!_frame_files VALUES (49, 8, 3, 47);
INSERT INTO !PREFIX!_frame_files VALUES (50, 10, 3, 48);
INSERT INTO !PREFIX!_frame_files VALUES (51, 7, 3, 49);
INSERT INTO !PREFIX!_frame_files VALUES (52, 20, 3, 50);
INSERT INTO !PREFIX!_frame_files VALUES (53, 6, 3, 51);
INSERT INTO !PREFIX!_frame_files VALUES (54, 22, 3, 52);
INSERT INTO !PREFIX!_frame_files VALUES (55, 33, 4, 53);
INSERT INTO !PREFIX!_frame_files VALUES (56, 33, 4, 54);
INSERT INTO !PREFIX!_frame_files VALUES (57, 33, 4, 55);
INSERT INTO !PREFIX!_frame_files VALUES (58, 20, 1, 56);
INSERT INTO !PREFIX!_frame_files VALUES (59, 40, 4, 57);
INSERT INTO !PREFIX!_frame_files VALUES (60, 21, 1, 58);
INSERT INTO !PREFIX!_frame_files VALUES (61, 21, 2, 59);
INSERT INTO !PREFIX!_frame_files VALUES (62, 21, 3, 60);
INSERT INTO !PREFIX!_frame_files VALUES (63, 40, 4, 61);
INSERT INTO !PREFIX!_frame_files VALUES (64, 25, 4, 63);
INSERT INTO !PREFIX!_frame_files VALUES (65, 25, 4, 62);
INSERT INTO !PREFIX!_frame_files VALUES (66, 25, 4, 64);
INSERT INTO !PREFIX!_frame_files VALUES (67, 34, 4, 65);
INSERT INTO !PREFIX!_frame_files VALUES (68, 34, 4, 66);
INSERT INTO !PREFIX!_frame_files VALUES (69, 35, 4, 67);
INSERT INTO !PREFIX!_frame_files VALUES (70, 35, 4, 68);
INSERT INTO !PREFIX!_frame_files VALUES (71, 36, 4, 69);
INSERT INTO !PREFIX!_frame_files VALUES (72, 36, 4, 70);
INSERT INTO !PREFIX!_frame_files VALUES (73, 30, 4, 75);
INSERT INTO !PREFIX!_frame_files VALUES (74, 30, 4, 76);
INSERT INTO !PREFIX!_frame_files VALUES (75, 37, 4, 71);
INSERT INTO !PREFIX!_frame_files VALUES (76, 37, 4, 72);
INSERT INTO !PREFIX!_frame_files VALUES (77, 38, 4, 73);
INSERT INTO !PREFIX!_frame_files VALUES (78, 38, 4, 74);
INSERT INTO !PREFIX!_frame_files VALUES (79, 39, 4, 77);
INSERT INTO !PREFIX!_frame_files VALUES (80, 39, 4, 78);
INSERT INTO !PREFIX!_frame_files VALUES (81, 40, 3, 79);
INSERT INTO !PREFIX!_frame_files VALUES (82, 24, 1, 80);
INSERT INTO !PREFIX!_frame_files VALUES (83, 16, 1, 81);
INSERT INTO !PREFIX!_frame_files VALUES (84, 42, 4, 82);
INSERT INTO !PREFIX!_frame_files VALUES (85, 42, 3, 83);
INSERT INTO !PREFIX!_frame_files VALUES (86, 45, 4, 84);
INSERT INTO !PREFIX!_frame_files VALUES (87, 45, 4, 85);
INSERT INTO !PREFIX!_frame_files VALUES (88, 43, 4, 86);
INSERT INTO !PREFIX!_frame_files VALUES (89, 22, 1, 87);
INSERT INTO !PREFIX!_frame_files VALUES (90, 46, 1, 88);
INSERT INTO !PREFIX!_frame_files VALUES (91, 46, 3, 90);
INSERT INTO !PREFIX!_frame_files VALUES (92, 22, 2, 28);
INSERT INTO !PREFIX!_frame_files VALUES (93, 47, 4, 92);
INSERT INTO !PREFIX!_frame_files VALUES (94, 47, 4, 91);
INSERT INTO !PREFIX!_frame_files VALUES (97, 48, 4, 95);
INSERT INTO !PREFIX!_frame_files VALUES (98, 49, 3, 96);
INSERT INTO !PREFIX!_frame_files VALUES (99, 49, 1, 97);
INSERT INTO !PREFIX!_frame_files VALUES (100, 49, 2, 98);
INSERT INTO !PREFIX!_frame_files VALUES (101, 49, 4, 99);
INSERT INTO !PREFIX!_frame_files VALUES (102, 16, 2, 100);
INSERT INTO !PREFIX!_frame_files VALUES (103, 16, 4, 101);
INSERT INTO !PREFIX!_frame_files VALUES (104, 16, 4, 102);
INSERT INTO !PREFIX!_frame_files VALUES (105, 50, 1, 103);
INSERT INTO !PREFIX!_frame_files VALUES (106, 50, 2, 104);
INSERT INTO !PREFIX!_frame_files VALUES (107, 50, 3, 105);
INSERT INTO !PREFIX!_frame_files VALUES (108, 50, 4, 106);
INSERT INTO !PREFIX!_frame_files VALUES (109, 50, 4, 107);
INSERT INTO !PREFIX!_frame_files VALUES (110, 5, 4, 108);
INSERT INTO !PREFIX!_frame_files VALUES (111, 5, 4, 111);
INSERT INTO !PREFIX!_frame_files VALUES (112, 5, 4, 110);
INSERT INTO !PREFIX!_frame_files VALUES (113, 5, 4, 109);
INSERT INTO !PREFIX!_frame_files VALUES (114, 19, 4, 112);
INSERT INTO !PREFIX!_frame_files VALUES (115, 12, 2, 19);
INSERT INTO !PREFIX!_frame_files VALUES (116, 33, 4, 113);
INSERT INTO !PREFIX!_frame_files VALUES (117, 10, 2, 8);
INSERT INTO !PREFIX!_frame_files VALUES (118, 16, 3, 114);
INSERT INTO !PREFIX!_frame_files VALUES (119, 22, 2, 27);
INSERT INTO !PREFIX!_frame_files VALUES (120, 22, 4, 27);
INSERT INTO !PREFIX!_frame_files VALUES (121, 22, 2, 116);
INSERT INTO !PREFIX!_frame_files VALUES (122, 47, 4, 22);
INSERT INTO !PREFIX!_frame_files VALUES (123, 47, 4, 115);
INSERT INTO !PREFIX!_frame_files VALUES (124, 8, 2, 14);
INSERT INTO !PREFIX!_frame_files VALUES (125, 45, 3, 117);
INSERT INTO !PREFIX!_frame_files VALUES (126, 52, 4, 118);
INSERT INTO !PREFIX!_frame_files VALUES (127, 53, 4, 119);
INSERT INTO !PREFIX!_frame_files VALUES (128, 43, 4, 120);
INSERT INTO !PREFIX!_frame_files VALUES (129, 43, 3, 121);
INSERT INTO !PREFIX!_frame_files VALUES (130, 54, 1, 122);
INSERT INTO !PREFIX!_frame_files VALUES (131, 54, 2, 123);
INSERT INTO !PREFIX!_frame_files VALUES (132, 60, 3, 124);
INSERT INTO !PREFIX!_frame_files VALUES (133, 60, 4, 125);
INSERT INTO !PREFIX!_frame_files VALUES (134, 60, 4, 126);
INSERT INTO !PREFIX!_frame_files VALUES (135, 54, 3, 128);
INSERT INTO !PREFIX!_frame_files VALUES (136, 61, 4, 127);
INSERT INTO !PREFIX!_frame_files VALUES (137, 61, 4, 129);
INSERT INTO !PREFIX!_frame_files VALUES (138, 62, 4, 130);
INSERT INTO !PREFIX!_frame_files VALUES (139, 62, 4, 131);
INSERT INTO !PREFIX!_frame_files VALUES (140, 55, 4, 132);
INSERT INTO !PREFIX!_frame_files VALUES (141, 55, 4, 133);
INSERT INTO !PREFIX!_frame_files VALUES (142, 56, 4, 134);
INSERT INTO !PREFIX!_frame_files VALUES (143, 56, 4, 135);
INSERT INTO !PREFIX!_frame_files VALUES (144, 57, 4, 136);
INSERT INTO !PREFIX!_frame_files VALUES (145, 57, 4, 137);
INSERT INTO !PREFIX!_frame_files VALUES (146, 58, 4, 138);
INSERT INTO !PREFIX!_frame_files VALUES (147, 58, 4, 139);
INSERT INTO !PREFIX!_frame_files VALUES (148, 59, 4, 140);
INSERT INTO !PREFIX!_frame_files VALUES (149, 59, 4, 141);
INSERT INTO !PREFIX!_frame_files VALUES (150, 63, 4, 142);
INSERT INTO !PREFIX!_frame_files VALUES (151, 63, 4, 143);
INSERT INTO !PREFIX!_frame_files VALUES (152, 64, 4, 144);
INSERT INTO !PREFIX!_frame_files VALUES (153, 3,  4, 145);
INSERT INTO !PREFIX!_frame_files VALUES (156, 65, 3, 148);
INSERT INTO !PREFIX!_frame_files VALUES (155, 66, 4, 147);
INSERT INTO !PREFIX!_frame_files VALUES (154, 65, 4, 146);
INSERT INTO !PREFIX!_frame_files VALUES (158, 66, 4, 150);
INSERT INTO !PREFIX!_frame_files VALUES (157, 65, 4, 149);

#
# Daten für Tabelle `!PREFIX!_nav_main`
#

INSERT INTO !PREFIX!_nav_main VALUES (1, 'navigation/content/main');
INSERT INTO !PREFIX!_nav_main VALUES (2, 'navigation/style/main');
INSERT INTO !PREFIX!_nav_main VALUES (4, 'navigation/statistic/main');
INSERT INTO !PREFIX!_nav_main VALUES (5, 'navigation/administration/main');
INSERT INTO !PREFIX!_nav_main VALUES (3, 'navigation/extra/main');

#
# Daten für Tabelle `!PREFIX!_nav_sub`
#

INSERT INTO !PREFIX!_nav_sub VALUES (1, 1, 1, 0, 'navigation/content/article/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (2, 1, 6, 0, 'navigation/content/structure', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (3, 1, 7, 0, 'navigation/content/upload', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (4, 2, 8, 0, 'navigation/style/layouts', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (5, 2, 10, 0, 'navigation/style/modules', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (6, 2, 12, 0, 'navigation/style/templates/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (7, 4, 20, 0, 'navigation/statistic/hits', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (8, 5, 21, 0, 'navigation/administration/users/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (13, 5, 22, 0, 'navigation/administration/languages', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (40, 0, 52, 1, 'navigation/info', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (39, 0, 42, 0, 'navigation/mycontenido/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (12, 0, 24, 0, 'navigation/login', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (35, 5, 28, 0, 'navigation/administration/plugins', 0);
INSERT INTO !PREFIX!_nav_sub VALUES (14, 1, 29, 0, 'plug-ins/langfile/', 0);
INSERT INTO !PREFIX!_nav_sub VALUES (15, 0, 1, 1, 'navigation/content/article/overview', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (16, 0, 3, 1, 'navigation/content/article/properties', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (17, 0, 5, 1, 'navigation/content/article/configuration', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (18, 0, 2, 1, 'navigation/content/article/editor', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (19, 2, 31, 0, 'navigation/style/styleeditor', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (20, 2, 32, 0, 'navigation/style/jseditor', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (31, 3, 16, 0, 'navigation/extra/newsletter', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (22, 0, 13, 1, 'navigation/style/templates/edit', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (23, 0, 33, 1, 'navigation/style/templates/conf', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (24, 0, 40, 1, 'navigation/administration/users/userproperties', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (25, 0, 25, 1, 'navigation/administration/users/areas', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (26, 0, 34, 1, 'navigation/administration/users/layout', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (27, 0, 35, 1, 'navigation/administration/users/content', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (28, 0, 36, 1, 'navigation/administration/users/module', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (29, 0, 37, 1, 'navigation/administration/users/template', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (30, 0, 38, 1, 'navigation/administration/users/structure', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (32, 2, 9, 1, 'navigation/style/layout', 0);
INSERT INTO !PREFIX!_nav_sub VALUES (33, 0, 43, 1, 'navigation/mycontenido/overview', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (34, 0, 45, 1, 'navigation/mycontenido/settings', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (36, 5, 46, 0, 'navigation/administration/clients', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (49, 5, 49, 0, 'navigation/administration/logs', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (37, 0, 4, 1, 'navigation/content/article/preview', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (38, 3, 50, 0, 'navigation/extra/recipients', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (41, 0, 61, 1, 'navigation/administration/groups/overview', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (43, 0, 62, 1, 'navigation/administration/groups/areas', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (44, 0, 55, 1, 'navigation/administration/groups/layout', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (45, 0, 56, 1, 'navigation/administration/groups/content', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (46, 0, 57, 1, 'navigation/administration/groups/module', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (47, 0, 58, 1, 'navigation/administration/groups/template', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (48, 0, 59, 1, 'navigation/administration/groups/structure', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (9, 5, 54, 0, 'navigation/administration/groups/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (42, 0, 63, 1, 'navigation/administration/groups/members', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (50, 5, 64, 0, 'navigation/administration/debug', 0);
INSERT INTO !PREFIX!_nav_sub VALUES (53, 5, 65, 0, 'navigation/administration/system/main', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (52, 0, 66, 1, 'navigation/administration/system/syserrorreport', 1);
INSERT INTO !PREFIX!_nav_sub VALUES (51, 0, 65, 1, 'navigation/administration/system/sysvalues', 1);

INSERT INTO !PREFIX!_meta_type VALUES (1, 'author', 'text', 256);
INSERT INTO !PREFIX!_meta_type VALUES (2, 'date', 'text', 64);
INSERT INTO !PREFIX!_meta_type VALUES (3, 'description', 'textarea', 48);
INSERT INTO !PREFIX!_meta_type VALUES (4, 'expires', 'text', 64);
INSERT INTO !PREFIX!_meta_type VALUES (5, 'keywords', 'textarea', 48);
INSERT INTO !PREFIX!_meta_type VALUES (6, 'revisit-after', 'text', 64);
INSERT INTO !PREFIX!_meta_type VALUES (7, 'robots', 'text', 64);

# Indizes
ALTER TABLE !PREFIX!_art ADD INDEX ( idart );
ALTER TABLE !PREFIX!_art ADD INDEX ( idclient);
ALTER TABLE !PREFIX!_art_lang ADD INDEX (idartlang);
ALTER TABLE !PREFIX!_art_lang ADD INDEX (idart);
ALTER TABLE !PREFIX!_art_lang ADD INDEX (idlang);
ALTER TABLE !PREFIX!_art_lang ADD INDEX (idtplcfg);
ALTER TABLE !PREFIX!_cat ADD INDEX (idcat);
ALTER TABLE !PREFIX!_cat ADD INDEX (idclient);
ALTER TABLE !PREFIX!_cat_art ADD INDEX (idcatart);
ALTER TABLE !PREFIX!_cat_art ADD INDEX (idcat);
ALTER TABLE !PREFIX!_cat_art ADD INDEX (idart);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX (idcatlang);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX (idcat);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX (idlang);
ALTER TABLE !PREFIX!_cat_lang ADD INDEX (idtplcfg);
ALTER TABLE !PREFIX!_code ADD INDEX (idcatart);
ALTER TABLE !PREFIX!_code ADD INDEX (idlang);
ALTER TABLE !PREFIX!_code ADD INDEX (idclient);
ALTER TABLE !PREFIX!_container ADD INDEX (idtpl);
ALTER TABLE !PREFIX!_container ADD INDEX (number);
ALTER TABLE !PREFIX!_container_conf ADD INDEX (idtplcfg);
ALTER TABLE !PREFIX!_container_conf ADD INDEX (number);
ALTER TABLE !PREFIX!_content ADD INDEX (idartlang);
ALTER TABLE !PREFIX!_content ADD INDEX (idtype);
ALTER TABLE !PREFIX!_content ADD INDEX (typeid);
ALTER TABLE !PREFIX!_keywords ADD INDEX (keyword);
ALTER TABLE !PREFIX!_keywords ADD INDEX (idlang);
ALTER TABLE !PREFIX!_mod ADD INDEX (idmod);
ALTER TABLE !PREFIX!_mod ADD INDEX (idclient);
ALTER TABLE !PREFIX!_template ADD INDEX (idclient);
ALTER TABLE !PREFIX!_template ADD INDEX (idlay);
ALTER TABLE !PREFIX!_template ADD INDEX (idtpl);
ALTER TABLE !PREFIX!_template ADD INDEX (idtplcfg);
ALTER TABLE !PREFIX!_template_conf ADD INDEX (idtplcfg);
ALTER TABLE !PREFIX!_template_conf ADD INDEX (idtpl);
ALTER TABLE !PREFIX!_upl ADD INDEX (idclient);
