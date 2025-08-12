<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/

ini_set("session.use_trans_sid",0);
error_reporting(E_ALL & ~E_NOTICE);

session_start();
session_register("secret_number");

require "config.php";
require "lang/".$LANGFILE;

$r=mysql_query("SELECT name,html FROM ".$db["prefix"]."templates;") or die(mysql_error());
while ($a=mysql_fetch_assoc($r)) $TMPL[$a["name"]]=$a["html"];

function mt() {
	list($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
	}

function qp_enc( $input = "", $line_max = 76, $space_conv = true ) {
   $hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
   $lines = preg_split("/(?:\r\n|\r|\n)/", $input);
   $eol = "?=\r\n\t";
   $escape = "=";
   $output = "";
   $charset = "=?Windows-1251?Q?";

       while( list(, $line) = each($lines) ) {
               $linlen = strlen($line);
               $newline = "";
               for($i = 0; $i < $linlen; $i++) {
                       $c = substr( $line, $i, 1 );
                       $dec = ord( $c );
                       if ( ( $i == 0 ) && ( $dec == 46 ) ) {
                               $c = "=2E";
                       }
                       if ( $dec == 32 ) {
                               if ( $i == ( $linlen - 1 ) ) {
                                       $c = "_";
                               } else if ( $space_conv ) {
                                       $c = "_";
                               }
                       } elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
                               $h2 = floor($dec/16);
                               $h1 = floor($dec%16);
                               $c = $escape.$hex["$h2"].$hex["$h1"];
                       }
                       if ( (strlen($newline) + strlen($c)) >= $line_max ) {
                               $output .= $charset.$newline.$eol;
                               $newline = "";
                               if ( $dec == 46 ) {
                                       $c = "=2E";
                               }
                       }
                       $newline .= $c;
               } // end of for
               $output .= $charset.$newline.$eol;
       } // end of while
       return trim(str_replace("\r\n","\n",$output));
	} 


if (isset($_GET["bad"])) {
	$brokenlink=intval($_GET["bad"]);
	mysql_query("UPDATE ".$db["prefix"]."main SET broken=broken+1 WHERE lid='$brokenlink';") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=javascript:window.close()'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}

if ($_POST["do"]=="add") {

	$ttitle=mhtml(substr($_POST["ttitle"],0,256));

	$url=$_POST["url"];
	if (substr($url,0,7)!="http://") $url="http://".$url;
	$url=mhtml(substr($url,0,256));

	$email=mhtml(substr($_POST["email"],0,256));
	$description=mhtml(substr($_POST["description"],0,2048));
	$resfield1=mhtml(substr($_POST["resfield1"],0,2048));
	$resfield2=mhtml(substr($_POST["resfield2"],0,2048));
	$resfield3=mhtml(substr($_POST["resfield3"],0,2048));
	$c1=intval($_POST["c1"]);

	$error="";


	if ($cat["robotsdeny"]=="on") {
		if ($_POST["secretcode"]!=$_SESSION["secret_number"] && intval($_POST["secretcode"])!=0) $error.="<LI>".$LANG["secretcodeerror"];
		}

	if ($c1==0) $error.="<LI>".$LANG["mustbecat"];
	if (empty($email)) $error.="<LI>".$LANG["mustbeemail"];
	if (empty($url)) $error.="<LI>".$LANG["mustbeurl"];
	if (empty($ttitle)) $error.="<LI>".$LANG["mustbetitle"];
	if (empty($description)) $error.="<LI>".$LANG["mustbedescription"];

	if (empty($error)) {
		if ($cat["mailifnewlink"]=="yes") {
			$err=@mail($cat["mailifnewlinkto"],qp_enc($cat["mailifnewlinksubject"]),"TITLE: $ttitle\nURL: $url\n","","-f".$a["from"]);
			if (!$err) {
				mail($cat["mailifnewlinkto"],qp_enc($cat["mailifnewlinksubject"]),"TITLE: $ttitle\nURL: $url\n");
				}
			}
		mysql_query("INSERT INTO ".$db["prefix"]."main SET insert_date=NOW(), title='$ttitle', description='$description', url='$url', cat1='$c1', gin=0, gout=0, moder_vote=0, email='$email', type=0, resfield1='$resfield1', resfield2='$resfield2', resfield3='$resfield3';") or die(mysql_error());
		$r=mysql_query("SELECT max(lid) FROM ".$db["prefix"]."main WHERE url='$url'") or die(mysql_error());
		$cid=@mysql_result($r,0,0);

		$r=mysql_query("SELECT * FROM ".$db["prefix"]."mail WHERE mid=1 AND active=1;") or die(mysql_error());
		if ($a=mysql_fetch_assoc($r)) {
			$body=$a["body"];
	
			$body=str_replace("%SITENAME%",$ttitle,$body);
			$body=str_replace("%SITEURL%",$url,$body);
			$body=str_replace("%CATNAME%",$CATNAME,$body);
			$body=str_replace("\r\n","\n",$body);

			$err=@mail($email,qp_enc($a["subject"]),$body,"From: \"".AddSlashes($CATNAME)."\" <".$a["from"].">\r\n".(!empty($a["replyto"])?"Reply-To:".$a["replyto"]."\r\n":"").$a["headers"],"-f".$a["from"]);
			if (!$err) {
				mail($email,qp_enc($a["subject"]),$body,"From: \"".AddSlashes($CATNAME)."\" <".$a["from"].">\r\n".(!empty($a["replyto"])?"Reply-To:".$a["replyto"]."\r\n":"").$a["headers"]);
				}
			}

		if ($cat["robotsdeny"]=="on") {
			$_SESSION["secret_number"]=0;
			}

		print ("<HTML><HEAD>\n");
		print ("<META HTTP-EQUIV=refresh CONTENT='0;url=thx.php?id=$cid'>\n");
		print ("</HEAD></HTML>\n");
		exit;
		}
	}

if ($cat["robotsdeny"]=="on") {
	if (intval($_SESSION["secret_number"])<1000) {
		srand(mt());
		$_SESSION["secret_number"]=rand(1000,9999);
		}
	}


$title=$LANG["addlink"];
include "_top.php";
$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;
print "<br>";

if (!empty($error)) {
	print "<P><B>".$LANG["errorsfound"]."</B>\n";
	print "<font color=red><UL>\n".$error."\n</UL></font>\n";
	}
?>
</center>
<table width=100% class=tbl0 cellspacing=1 cellpadding=0>
<tr><td class=tbl1>
<img src=./cat/none.gif width=1 height=6><br>
<center><table border=0>
<form action=add.php method=post>
<input type=hidden name='do' value='add'>
<tr><td valign=top>
<?=$LANG["category"];?>:
</td><td>
<select style='width:320px;' name=c1>
<option value=0><?=$LANG["notselected"];?>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	if ($row["cid"]==$c1) $sel="selected"; else $sel="";
	echo "<OPTION $sel value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>

<tr><td valign=top>
<?=$LANG["sitetitle"];?>:&nbsp;&nbsp;
</td><td>
<input style='width:320px;' type=text name=ttitle value='<?=$ttitle;?>'>
</td></tr>

<tr><td valign=top>
<?=$LANG["siteurl"];?>:
</td><td>
<input style='width:320px;' type=text name=url value='<?=$url;?>'>
</td></tr>

<tr><td valign=top>
<?=$LANG["email"];?>:
</td><td>
<input style='width:320px;' type=text name=email value='<?=$email;?>'>
</td></tr>

<?if (!empty($cat["resfield1"])) {?>
<tr><td valign=top>
<?=$cat["resfield1"];?>:
</td><td>
<input style='width:320px;' type=text name=resfield1 value='<?=$resfield1;?>'>
</td></tr>
<?}?>

<?if (!empty($cat["resfield2"])) {?>
<tr><td valign=top>
<?=$cat["resfield2"];?>:
</td><td>
<input style='width:320px;' type=text name=resfield2 value='<?=$resfield2;?>'>
</td></tr>
<?}?>

<?if (!empty($cat["resfield3"])) {?>
<tr><td valign=top>
<?=$cat["resfield3"];?>:
</td><td>
<input style='width:320px;' type=text name=resfield3 value='<?=$resfield3;?>'>
</td></tr>
<?}?>

<tr><td valign=top colspan=2>
<?=$LANG["sitedescription"];?>:<br>

<textarea style='width:100%;' name=description rows=6><?=$description;?></textarea>
</td></tr>

<?
if ($cat["robotsdeny"]=="on") {
	print "<tr><td valign=top colspan=2>\n";
	print "<br><table width='100%' cellspacing=0 cellpadding=0 border=0>";
	print "<tr><td colspan=2>".$LANG["secretcode"]."</td></tr>";
	print "<tr><td><img src='code.php?".mt()."' width=101 height=26 vspace=5></td><td align='right'><input style='width:320px;' type=text name=secretcode value='".$secretcode."'></td></tr></table>\n";
	print "</td></tr>\n";
	print "<tr><td colspan=2><br></td></tr>";
	}
?>

<tr><td colspan=2 align=right>
<input type=submit value='<?=$LANG["submit"];?>' class=small>
</td></tr>

</table>

</td></form></tr></table>
<br>
<?
$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;

include "_bottom.php";?>
