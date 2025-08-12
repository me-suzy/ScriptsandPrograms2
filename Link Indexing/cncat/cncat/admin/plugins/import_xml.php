<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/

/* PLUGIN: Import from XML file; */
chdir("..");
$ADLINK="../";
@set_time_limit(3600);

include "auth.php";

function CheckUploadError($filename,$error) {
	GLOBAL $LANG;

	if ($error==UPLOAD_ERR_OK) return;
	if ($error==UPLOAD_ERR_NO_FILE) return;
	if (!empty($filename)) print "<P><B style='color:red'>".$filename."</B>: "; else print "<P><B style='color:red'>Error:</B> ";
	if ($error==UPLOAD_ERR_INI_SIZE) {print "The uploaded file exceeds the upload_max_filesize directive in php.ini.";exit;}
	if ($error==UPLOAD_ERR_FORM_SIZE) {print "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";exit;}
	if ($error==UPLOAD_ERR_PARTIAL) {print "The uploaded file was only partially uploaded.";exit;}

	print $error;
	}

if ($_GET["op"]=="ok") {
	include "_top.php";

	print "<P>".$LANG["plugin_import_finished"]."</P>\n";
	print "<UL>\n";
	print "<LI>".$LANG["plugin_import_ed_links"].": ".intval($_GET["l"])."\n";
	print "<LI>".$LANG["plugin_import_ed_rubrics"].": ".intval($_GET["r"])."\n";
	print "</UL>\n";
	include "_bottom.php";
	exit;
	}

if ($_SERVER["REQUEST_METHOD"]=="POST") {

	if ($_POST["del"]=="on") {
		mysql_query("DELETE FROM ".$db["prefix"]."cat;") or die(mysql_error());
		mysql_query("DELETE FROM ".$db["prefix"]."main;") or die(mysql_error());
		mysql_query("DELETE FROM ".$db["prefix"]."cat_linear;") or die(mysql_error());
		}

	$overwrite=intval($_POST["idoverwrite"]);

	$c_r=$c_l=0;
	header("Content-Type: text/html");
	print "Importing rubrics<br>\n";
	/* Rubrics import */
	CheckUploadError($_FILES['filer']['name'],$_FILES['filel']['error']);
	$size=intval($_FILES['filer']['size']);
	if ($size>0 && $size<104857600) {
		function r_startElement($parser, $name, $attrs) {
			GLOBAL $db,$overwrite,$c_r;
			if ($name=="RUBRIC") {
				/* Skip exisiting */
				if ($overwrite==0) {
					$r=mysql_query("SELECT count(*) FROM ".$db["prefix"]."cat WHERE cid='".intval($attrs["INDEX"])."';") or die(mysql_error());
					if (mysql_result($r,0,0)==0) {
						$c_r++;
						mysql_query("INSERT INTO ".$db["prefix"]."cat SET cid='".intval($attrs["INDEX"])."', parent='".intval($attrs["PARENT"])."', name='".mhtml($attrs["NAME"])."' ;") or die(mysql_error());
						}
					}
				/* Overwrite exisiting */
				else {
					mysql_query("DELETE FROM ".$db["prefix"]."cat WHERE cid='".intval($attrs["INDEX"])."';") or die(mysql_error());
					mysql_query("INSERT INTO ".$db["prefix"]."cat SET cid='".intval($attrs["INDEX"])."', parent='".intval($attrs["PARENT"])."', name='".mhtml($attrs["NAME"])."' ;") or die(mysql_error());
					$c_r++;
					}
				}
    		}

		function r_endElement($parser, $name) {}
		function r_characterData($parser, $data) {}

		if (is_file($_FILES['filer']['tmp_name']))
		$xmldata=implode("",file($_FILES['filer']['tmp_name']));
		
		$xml_parser = xml_parser_create();

		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($xml_parser, "r_startElement", "r_endElement");
		xml_set_character_data_handler($xml_parser, "r_characterData");

		if (!xml_parse($xml_parser, $xmldata)) {
	        die(sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
			}
		xml_parser_free($xml_parser);
		}

	/* Links import */
	print "Importing links<br>\n";flush();
	CheckUploadError($_FILES['filel']['name'],$_FILES['filel']['error']);
	$size=intval($_FILES['filel']['size']);
	if ($size>0 && $size<104857600) {
		function l_startElement($parser, $name, $attrs) {
			GLOBAL $F,$d;
			$d="";
			if ($name=="LINK") {$F=Array();}
    		}

		function l_endElement($parser, $name) {
			GLOBAL $F,$d,$db,$overwrite,$c_l;
			if ($name=="LINK") {

				$SQL="";
				while (list ($key, $val) = each ($F)) $SQL.=$key."='".$val."', ";
				$SQL="INSERT INTO ".$db["prefix"]."main SET ".substr($SQL,0,-2).";";

				/* Skip exisiting */
				if ($overwrite==0) {
					$r=mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE lid='".intval($F["LID"])."';") or die(mysql_error());
					if (mysql_result($r,0,0)==0) {
						mysql_query($SQL) or die(mysql_error());
						$c_l++;
						}
					}
				/* Overwrite exisiting */
				else {
					mysql_query("DELETE FROM ".$db["prefix"]."main WHERE lid='".intval($F["LID"])."';") or die(mysql_error());
					mysql_query($SQL) or die(mysql_error());
					$c_l++;
					}

				}
			else $F[$name]=$d;
    		}

		function l_characterData($parser, $data) {
			GLOBAL $d;
			$d.=$data;
			}

		if (is_file($_FILES['filel']['tmp_name']))
			$xmldata=implode("",file($_FILES['filel']['tmp_name']));

			$xml_parser = xml_parser_create();

			xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
			xml_set_element_handler($xml_parser, "l_startElement", "l_endElement");
			xml_set_character_data_handler($xml_parser, "l_characterData");

			if (!xml_parse($xml_parser, $xmldata)) {
		        die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
					}
			xml_parser_free($xml_parser);
		}


	print "Importing design templates<br>\n";
	/* Design import */
	CheckUploadError($_FILES['filet']['name'],$_FILES['filel']['error']);
	$size=intval($_FILES['filet']['size']);
	$may=0;$ename="";$edata="";$sqlc="";
	if ($size>0 && $size<104857600) {

		function t_startElement($parser, $name, $attrs) {
			GLOBAL $may,$ename,$edata;

			$name=strtolower($name);
			if ($may==1) {$ename=$name;$edata="";}
			if ($name=="design") $may=1;
    		}

		function t_endElement($parser, $name) {
			GLOBAL $db,$may,$ename,$edata,$sqlc;

			$name=strtolower($name);
			if ($name=="design") {$may=0;$ename="";}

			if (strlen($ename)>1 && $may==1) {
				$sqlc.="name!='".$ename."' AND\n";
				$edata=base64_decode($edata);
				$sql="UPDATE ".$db["prefix"]."templates SET html='".mysql_escape_string($edata)."' WHERE name='".mysql_escape_string($ename)."';";
				mysql_query($sql) or die(mysql_error());
				}
			}

		function t_characterData($parser, $data) {
			GLOBAL $ename,$edata;

			if (strlen($ename)>1) $edata.=$data;
			}

		if (is_file($_FILES['filet']['tmp_name']))
		$xmldata=implode("",file($_FILES['filet']['tmp_name']));
		
		$xml_parser = xml_parser_create();

		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($xml_parser, "t_startElement", "t_endElement");
		xml_set_character_data_handler($xml_parser, "t_characterData");

		if (!xml_parse($xml_parser, $xmldata)) {
	        die(sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
			}
		mysql_query("UPDATE ".$db["prefix"]."templates SET html='' WHERE ".substr($sqlc,0,-4)) or die(mysql_error());
		xml_parser_free($xml_parser);
		}

	print "Sync<br>\n";flush();
	sync();
	sync_names();

	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=import_xml.php?op=ok&l=".$c_l."&r=".$c_r."'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}

include "_top.php";
?>
<table width=500 cellspacing=1 cellpadding=10 border=0><form action=import_xml.php method=post enctype="multipart/form-data">
<th colspan=2><?=$LANG["plugin_import"];?></th></tr>
<tr><td colspan=2><table cellspacing=0 cellpadding=0 border=0><tr><td valign=top><input class=checkbox type=checkbox name=del></td><td><?=$LANG["plugin_import_delete"];?></td></tr></table></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td colspan=2><table cellspacing=0 cellpadding=0 border=0><tr><td><input name=idoverwrite class=checkbox type=radio value=1></td><td><?=$LANG["plugin_import_overwrite"];?></td></tr></table></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td colspan=2><table cellspacing=0 cellpadding=0 border=0><tr><td><input checked name=idoverwrite class=checkbox type=radio value=0></td><td><?=$LANG["plugin_import_skip"];?></td></tr></table></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td width=50%><?=$LANG["plugin_import_rubrics"];?></td><td><input width=50% style='width:100%' type=file name=filer></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td width=50%><?=$LANG["plugin_import_links"];?></td><td width=50%><input style='width:100%' type=file name=filel></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td width=50%><?=$LANG["plugin_import_templates"];?></td><td width=50%><input style='width:100%' type=file name=filet></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td colspan=2><input type=submit value='<?=$LANG["plugin_import_do"];?>'></td></tr>
</form></table>
<?
include "_bottom.php";
?>
