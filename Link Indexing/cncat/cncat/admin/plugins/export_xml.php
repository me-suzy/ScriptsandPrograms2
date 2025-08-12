<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/

/* PLUGIN: Export to XML file; */
chdir("..");
$ADLINK="../";
@set_time_limit(3600);

include "auth.php";

function xml_encode($str) {
	$nstr="";
	for ($i=0;$i<strlen($str);$i++) {
		if (ord($str[$i])<32) $nstr.=sprintf("&#%d;",ord($str[$i]));
		else $nstr.=$str[$i];
		}

	return($nstr);
	}

function export_rubric($id) {
	GLOBAL $db;

	$r=mysql_query("SELECT name,cid FROM ".$db["prefix"]."cat WHERE parent='".$id."';") or die(mysql_error());
	while ($a=mysql_fetch_array($r)) {
		$a["name"]=str_replace("&","&amp;",$a["name"]);
		$a["name"]=xml_encode($a["name"]);
		print "<rubric name=\"".$a["name"]."\" index=\"".$a["cid"]."\" parent=\"".$id."\">\n";
		export_rubric($a["cid"]);
		print "</rubric>\n";
		}
	}

if (isset($_GET["what"])) {
	/* Rubrics export */
	if ($_GET["what"]==0) {
		header("Content-Type: text/xml");
		header("Content-Disposition: attachment; filename=cncat_rubrics.xml");
		print "<?xml version=\"1.0\" encoding=\"".$LANG["charset"]."\"?>\n";

		print "<!DOCTYPE rubricator>\n";
		print "<rubricator \n";
		print "  name=\"".htmlspecialchars($CATNAME)."\"\n";
		print "  date=\"".date("Y-m-d")."\"\n";
		print "  xmlns=\"http://www.cn-software.com/cncat/\"\n";
		print ">\n\n";

		export_rubric(0);

		print "</rubricator>";
		exit;
		}
	/* Links export */
	if ($_GET["what"]==1) {
		header("Content-Type: text/xml");
		header("Content-Disposition: attachment; filename=cncat_links.xml");
		print "<?xml version=\"1.0\" encoding=\"".$LANG["charset"]."\"?>\n";

		print "<!DOCTYPE links>\n";
		print "<links \n";
		print "  name=\"".htmlspecialchars($CATNAME)."\"\n";
		print "  date=\"".date("Y-m-d")."\"\n";
		print "  xmlns=\"http://www.cn-software.com/cncat/\"\n";
		print ">\n\n";

		$r=mysql_query("SELECT * FROM ".$db["prefix"]."main") or die(mysql_error());
		while ($a=mysql_fetch_array($r,MYSQL_ASSOC)) {
			print "<link>\n";
			while (list ($key, $val) = each ($a)) {
				if ($key=="title" || $key=="url" || $key=="description" || $key=="email" || $key=="resfield1" || $key=="resfield2" || $key=="resfield3") {
					$cdata1="<![CDATA[";
					$cdata2="]]>";
					$val=xml_encode($val);
					}
				else $cdata1=$cdata2="";
				echo " <".$key.">".$cdata1.$val.$cdata2."</".$key.">\n";
				}
			print "</link>\n";
			}

		print "</links>";
		exit;
		}
	/* Design export */
	if ($_GET["what"]==2) {
		header("Content-Type: text/xml");
		header("Content-Disposition: attachment; filename=cncat_design.xml");
		print "<?xml version=\"1.0\" encoding=\"".$LANG["charset"]."\"?>\n";

		$r=mysql_query("SELECT name,html FROM ".$db["prefix"]."templates;") or die(mysql_error());
		while ($a=mysql_fetch_assoc($r)) $TMPL[$a["name"]]=$a["html"];

		print "<!DOCTYPE design>\n";
		print "<design \n";
		print "  name=\"".htmlspecialchars($CATNAME)."\"\n";
		print "  date=\"".date("Y-m-d")."\"\n";
		print "  xmlns=\"http://www.cn-software.com/cncat/\"\n";
		print ">\n\n";

		while (list ($key, $val) = each ($TMPL)) {
			print "  <".$key.">".base64_encode($val)."</".$key.">\n";
		    }
		print "</design>\n";
		exit;
		}
	}

include "_top.php";
?>
<table width=500 cellspacing=1 cellpadding=6 border=0><form action=export_xml.php method=get>                           
<th colspan=2><?=$LANG["plugin_export"];?></th></tr>
<tr><td><table cellspacing=0 cellpadding=0 border=0><tr><td><input class=checkbox type=radio name=what value=0></td><td><?=$LANG["plugin_export_rubrics"];?></td></tr></table></td></tr>
<tr><td><table cellspacing=0 cellpadding=0 border=0><tr><td><input class=checkbox checked type=radio name=what value=1></td><td><?=$LANG["plugin_export_links"];?></td></tr></table></td></tr>
<tr><td><table cellspacing=0 cellpadding=0 border=0><tr><td><input class=checkbox type=radio name=what value=2></td><td><?=$LANG["plugin_export_design"];?></td></tr></table></td></tr>
<tr><td colspan=2><input type=submit value='<?=$LANG["plugin_export_download"];?>'></td></tr>
</form></table>
<?
include "_bottom.php";
?>
