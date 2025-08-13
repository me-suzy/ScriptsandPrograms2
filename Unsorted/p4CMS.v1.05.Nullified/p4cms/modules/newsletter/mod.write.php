<?PHP
class MIMEMail {
	var $_parts;
	var $xmailer;
	var $_mimesep;
	var $_mimeheader;
	var $from_name;
	var $from_email;
	var $signatur;
	var $cc;
	var $bcc;
	var $_i;
	var $subject;
	var $to;
	
	function MIMEMail() {
		$this->xmailer = "p4CMS (WTN Team)";
		$this->_mimesep = "--Boundary-=_" . md5(uniqid(time()));
		$this->from_name = "";
		$this->from_email = "";
		$this->cc = "";
		$this->bcc = "";
		$this->_mimeheader = "";
		$this->_i = 0;
		$this->_parts = array();
		$this->subject = "";
		$this->to = "";
	}
	
	function Headers () {
		global $bm_prefs;
		
		$mimeheader = "From: \"" . $this->from_name . "\" <" . $this->from_email . ">\n";
		if (!($this->cc=="") or !isset($this->cc)) {
			$mimeheader .= "Cc: " . $this->cc . "\n";
		}
		if (!($this->bcc=="") or !isset($this->bcc)) {
			$mimeheader .= "Bcc: " . $this->bcc . "\n";
		}
		$mimeheader .= "Return-Path: <" . $this->from_email . ">\n";
		$mimeheader .= "MIME-Version: 1.0\n";
		$mimeheader .= "Content-Type: multipart/mixed;boundary=\"" . $this->_mimesep . "\"\n";
		$mimeheader .= "X-Mailer: " . $this->xmailer . ""."\n";
		$this->_mimeheader = $mimeheader;
	}
	
	function AddTextPart ($html,$text) {
		if ($html==true) {
			$delim = "html";
			$brake = "<br>";
			$enc = "base64";
		} else {
			$delim = "plain";
			$brake = "";
			$enc = "quoted-printable";
		}
		$newmessage = "--" . $this->_mimesep . "\n";
		$newmessage .= "Content-Type: text/".$delim." ; CHARSET=windows-1251\n";
		$newmessage .= "Content-Transfer-Encoding: $enc\n\n";
		if (!$html) {
			$newmessage .= $text;
		} else {
			$newmessage .= base64_encode($text);
		}
		$this->_parts[$this->_i] = $newmessage;
			
		$this->_i++;
	}
	
	function AddAttachment ($content,$filename,$typ,$af="ATTACHMENT") {
		$newmessage = "\n\n--" . $this->_mimesep . "\n";
		$newmessage .= "Content-Type: " . $typ . "; name=\"" . $filename . "\"\n";
		$newmessage .= "Content-Transfer-Encoding: base64\n";
		$newmessage .= "Content-Disposition: $af; filename=\"" . $filename . "\"\n\n";
		$newmessage .= base64_encode($content);
		$this->_parts[$this->_i] = $newmessage;
		$this->_i++;
	}
	
	function Finish() {
		$newmessage = "\n--" . $this->_mimesep . "--\n\n";
		$this->_parts[$this->_i] = $newmessage;
		$this->_i++;
	}
	
	function Send() {
		$body = $this->_mimeheader;
		for ($i=0;$i<=count($this->_parts);$i++) {
			$body .= $this->_parts[$i];
		}
		
		return (mail($this->to, $this->subject, "", $body));
	}
}


 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_newsletter']=="no") {
 	$msg = "<center>Ihre Gruppe hat keine Berechtigung, diese Seite zu betreten.</center>";
	MsgBox($msg);
	exit;
 }
 
 if ($_REQUEST['action']=="send") {
 	$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
 	ereg("http:\/\/(.*)" . preg_quote($p4cms_pfad) . "\/", $url, $find);
 	$baseurl = $find[0];
 	$unsubscribe = $baseurl . "modules/newsletter/unsubscribe.php?";
 	
 	$textv = stripslashes($_REQUEST['textversion']);
 	$htmlv = stripslashes($_REQUEST['htmlversion']);
 	$betreff = stripslashes($_REQUEST['betreff']);
 	$from_name = $_REQUEST['absname'];
 	$from_mail = $_REQUEST['absmail'];

 	if (!isset($_REQUEST['start'])) {
 		$start = 0;
 	} else {
 		$start = $_REQUEST['start'];
 	}
 	
 	$count = $start;
 	
 	$sql =& new MySQLq();
 	$sql->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE liste='$_REQUEST[an]'");
 	$gesamt = $sql->RowCount();
 	$sql->Close();

 	$sql =& new MySQLq();
 	$sql->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE liste='$_REQUEST[an]' ORDER BY id ASC LIMIT $start,50");
 	while ($row = $sql->FetchRow()) {
 		$an = $row->email;
 		$name = $row->name;
 		
 		if ($row->art == "html") {
 			$a = true;
 			$text = $htmlv;
 		} else {
 			$a = false;
 			$text = $textv;
 		}
 		
 		$text = str_replace("{EMAIL}", $an, $text);
 		$text = str_replace("{NAME}", $name, $text);
 		$text = str_replace("{AUSTRAGEN}", "${unsubscribe}mail=$an&list=$_REQUEST[an]&code=" . md5(crc32($an . $name . $_REQUEST[an])), $text);
 		
 		$thismail =& new MIMEMail();
 		$thismail->from_name = $from_name;
 		$thismail->from_email = $from_mail;
 		$thismail->subject = $betreff;
 		$thismail->cc = "";
 		$thismail->bcc = "";
 		$thismail->to = $an;
 		$thismail->headers();
 		$thismail->addtextpart($a, $text);
 		$thismail->finish();
 		$thismail->send();
 		
 		$count++;
 	}
 	$sql->Close();
 	
 	if ($count < $gesamt) {
 		?>
 		<form action="" method="post" name="nextform">
 		<input type="hidden" name="action" value="send">
 		<input type="hidden" name="start" value="<?=$count;?>">
 		<input type="hidden" name="betreff" value="<?=$betreff;?>">
 		<input type="hidden" name="absname" value="<?=$from_name;?>">
 		<input type="hidden" name="an" value="<?=$_REQUEST['an'];?>">
 		<input type="hidden" name="absmail" value="<?=$from_mail;?>">
 		<textarea style="width:1;height:1;visibility:hidden;" name="textversion">
 		<?=htmlentities($textv);?>
 		</textarea>
 		<textarea style="width:1;height:1;visibility:hidden;" name="htmlversion">
 		<?=htmlentities($htmlv);?>
 		</textarea>
 		</form>
 		<center>
 		<h3>Bitte warten...</h3>
 		Versand zu <b><?
		$einprozent = $gesamt / 100;
		$prozent = $count / $einprozent;
		echo (round($prozent, 2));
 		?>%</b> abgeschlossen.
 		</center>
 		<script language="javascript">
 		<!--
 			function nexts() {
 				document.all.nextform.submit();
 			}
 			setTimeout("nexts();", 1000);
 		//-->
 		</script>
 		<?	
 	} else {
 		?>
  		<center>
 		<h3>Fertig</h3>
 		Der Versand wurde abgeschlossen.
 		</center>		
 		<?
 	}
 		
 	exit;	
 }
 
 ?>
<html>
<head>
<? StyleSheet(); ?>
<link rel="stylesheet" href="/p4cms/style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">

                <form action="" style="display:inline;" method="post">
                <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

                <tr bgcolor="#FAFAFB">
                  <td colspan="2"><b>Newsletter verfassen</b><br>
                    Folgende Variablen k&ouml;nnen im Text/HTML-Part verwendet wertden:<BR>
&nbsp;&nbsp;<br>
{EMAIL} - E-Mail des Empf&auml;ngers<br>
{NAME} - Name des Empf&auml;ngers<br>
{AUSTRAGEN} - Link zum Austragen aus dem Newsletter</td>
                  </tr>
                <tr>
                <td width="20%" nowrap bgcolor="#EAEBEE">Empf&auml;nger:&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <select name="an">
                <?
					$sql =& new MySQLq();
					$sql->Query("SELECT titel, id FROM " . $sql_prefix . "mailinglisten ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						echo "<option value=\"$row->id\">" . stripslashes($row->titel) . "</option>\r\n";
					}
					$sql->Close();
                ?>
                </select></td>
                </tr>
                <tr>
                <td width="20%" nowrap bgcolor="#EAEBEE">Absender Name:&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <input type="text" name="absname" value="" style="width:98%;" size="32"></td>
                </tr>
                <tr>
                <td width="20%" nowrap bgcolor="#EAEBEE">Absender E-Mail:&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <input type="text" name="absmail" value="" style="width:98%;" size="32"></td>
                </tr>
                <tr>
                <td width="20%" nowrap bgcolor="#EAEBEE">Betreff:&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <input type="text" name="betreff" value="" style="width:98%;" size="32"></td>
                </tr>
                <tr>
                <td width="20%" valign="top" nowrap bgcolor="#EAEBEE">Text-Version:&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <textarea name="textversion" style="width:100%;height:300;"></textarea></td>
                </tr>
                <tr>
                <td width="20%" valign="top" nowrap bgcolor="#EAEBEE">HTML-Version:&nbsp;</td>
                <td bgcolor="#FAFAFB"><? 
                $editor =& new p4cmsEditor("");
                $editor->CreateFCKeditor("htmlversion", "100%", "300");
                ?></td>
                </tr>
                <tr>
                <td width="20%" valign="top" nowrap bgcolor="#EAEBEE">&nbsp;</td>
                <td bgcolor="#FAFAFB">
                  <input type="hidden" name="action" value="send">
                  <input type="submit" class="button" value=" Newsletter Absenden "></td>
                </tr>
                </table>
                </form>
                