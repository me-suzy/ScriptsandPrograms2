<?
 include("include/include.inc.php");

 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_vorlagen']=="no") {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
?>
<html>
<head>
 <? StyleSheet(); ?>
<link rel="stylesheet" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><style type="text/css">
</style>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
<script language="JavaScript">
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Inhalt wird markiert (und in die Zwischenablage kopiert) !"
setTimeout("window.status=''",1800)
}
}
</script>
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">

<form name="vorlage" method="post" action="vorlage.php?d4sess=<?=$d4sess;?>">
<input type="hidden" name="mode" value="save" />
<?
	if ($_REQUEST['mode'] == "save") {
		if ($_REQUEST['amode'] == "change") {
		if(file_exists("modules/stats/stat.php"))
			{$zusatz = "{p4:counter}"; };
			$sql =& new MySQLq();
			$sql->Query("UPDATE " . $sql_prefix . "vorlagen SET titel='$_REQUEST[titel]', vorlage='".$_REQUEST['vorlageneditor'].$zusatz."' WHERE id='$_REQUEST[aid]'");
			$sql->Close();
			$sql2 =& new MySQLq();
 			$sql2->Query("SELECT id FROM " . $sql_prefix . "rubriken WHERE stdvorlange='$_REQUEST[aid]'");
 			while ($row2 = $sql2->FetchRow()) {
 				$sql3 =& new MySQLq();
 				$sql3->Query("UPDATE " . $sql_prefix . "dokumente SET published='no' WHERE rubrik='$row2->id'");
 				$sql3->Close();
 			}
 			$sql2->Close();
 			eLog("user", "$_SESSION[u_user] ändert Vorlage $_REQUEST[aid]");
			RenderPages();
		}
		if ($_REQUEST['amode'] == "new") {
		if(file_exists("modules/stats/stat.php"))
			{$zusatz = "{p4:counter}"; }
			$sql =& new MySQLq();
			$sql->Query("INSERT INTO " . $sql_prefix . "vorlagen(titel,vorlage,lastupdate) VALUES ('$_REQUEST[titel]','".$_REQUEST[vorlageneditor].$zusatz."','" . time() . "')");
			$sql->Close();
			eLog("user", "$_SESSION[u_user] erstellt Vorlage $_REQUEST[titel]");
		}
		?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr>
        <td class="boxstandart">Die Vorlage wurde gespeichert und steht ab sofort zur Verf&uuml;gung.<br>
                <?
				if (isset($_REQUEST['amode'])=="change") {
					?>
Die &Auml;nderungen werden sofort wirksam.
<?
				}
				?></td>
      </tr>
    </table>



		<?
		exit;
	}

 	if ($_REQUEST['mode'] == "edit") {
 		$sql =& new MySQLq();
 		$sql->Query("SELECT * FROM " . $sql_prefix . "vorlagen WHERE id='$_REQUEST[id]'");
 		$row = $sql->FetchRow();
 		$vtitel = stripslashes($row->titel);
 		$vtext = str_replace("{p4:counter}","",stripslashes($row->vorlage));
 		$sql->Close();
		?>
		<input type="hidden" name="amode" value="change">
		<input type="hidden" name="aid" value="<?=$_REQUEST['id'];?>">
		<?
 	} else {
		?>
		<input type="hidden" name="amode" value="new">
		<?
		$vtitel = "";
 		$vtext = "";
 	}
?>
      <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

      <tr>
        <td bgcolor="#EAEBEE">
          <table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td width="10%" nowrap>Titel der Vorlage:</td>
              <td><input type="text" size="62" name="titel" class="feld" style="width:99%;" value=""></td>
              <script language="javascript">
					<!--
						document.all.titel.value = '<?=addslashes($vtitel);?>';
					//-->
					</script>
            </tr>
          </table>
       </td>
      </tr>
      <tr>
        <td bgcolor="#FAFAFB">

<?php
if($_REQUEST['mode']=="new"){
$vtext = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
\"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<title>IHRE FIRMA - {TITEL}</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
<link href=\"/media/style_norm.css\" rel=\"stylesheet\" type=\"text/css\">
<script language=\"javascript\" src=\"/media/common.js\" type=\"text/javascript\"></script>
</head>

<body text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\"> 

{CONTENT}

</body>
</html>";
}
CreateEditor("100%","320",$vtext,"vorlageneditor"); ?>
        </td>
      </tr>
      <tr>
        <td bgcolor="#EAEBEE">
          <input name="v" type="button" class="button"  id="v" onClick="Vorschau();" value="Vorschau">
          <input class="button" onClick="HighlightAll('vorlage.vorlageneditor')" style="height:10%;" type="button" value="in die Zwischenablage kopieren">
        </td>
      </tr>
  </table> 
      <br>
      <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

        <tr bgcolor="#EAEBEE">
          <td height="17">&nbsp;Variable</td>
          <td height="17">&nbsp;Beschreibung</td>
          <td height="17">&nbsp;</td>
        </tr>
        <tr>
          <td height="17" bgcolor="#FAFAFB">&nbsp;{CONTENT}</td>
          <td height="17" bgcolor="#FAFAFB">&nbsp;Der Inhalt der Rubrik</td>
          <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{CONTENT}');">Einf&uuml;gen</a></td>
        </tr>
        <tr>
          <td height="17" bgcolor="#FAFAFB">&nbsp;{TITEL}</td>
          <td height="17" bgcolor="#FAFAFB">&nbsp;Titel der Seite</td>
          <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{TITEL}');">Einf&uuml;gen</a></td>
        </tr>
        <?
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "abfragen ORDER BY titel ASC");
while ($row = $sql->FetchRow()) {
					?>
        <tr>
          <td height="17" bgcolor="#FAFAFB">&nbsp;{ABF:<? echo($row->id); ?>}</td>
          <td height="17" bgcolor="#FAFAFB">&nbsp;<font color="#dddddd">(ABFRAGE)</font> <? echo(stripslashes($row->titel)); ?></td>
          <td height="17" bgcolor="#FAFAFB" align="center"><a href="javascript:insertAtCaret(document.all.vorlageneditor,'{ABF:<? echo($row->id); ?>}');">Einf&uuml;gen</a></td>
        </tr>
        <?
}
$sql->Close();
?>
  </table>
      <br>  
      <input type="submit" class="button" value="speichern und weiter >>">
</form>