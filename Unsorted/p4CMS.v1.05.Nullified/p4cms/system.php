<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($_REQUEST['action']=="" or !isset($_REQUEST['action'])) {
 	?>
 	<html>
 	<head>
 	 <title>Einen Moment...</title>
     <? StyleSheet(); ?>
	 <link rel="stylesheet" href="style/style.css">
	<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
	</head>
 	<body>
	
      <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/rebuild.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Rebuild</b></td>
              <td><div align="right">
                &gt;&gt; <a href="javascript:SwitchPage('rebuild.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Rebuild durchf&uuml;hren</b></a> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Hier k&ouml;nnen Sie alle Seiten neu zusammenf&uuml;hren lassen. Dabei werden alle Dokumente neu aus der Datenbank eingelesen, gerendert und als HTML-Datei in das Dateisystem abgelegt. Damit stellen Sie sicher, dass alle Seiten auf dem neuesten Stand sind.<br></td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/wartung.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Wartung</b></td>
              <td><div align="right"> &gt;&gt; <a href="javascript:SwitchPage('wartung.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Wartung durchf&uuml;hren</b></a> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Hier k&ouml;nnen Sie die p4CMS-Datenbank optimieren, sodass eventuell nicht freigewordener Datenbankspeicher freigegeben wird. Das System wird dadurch beschleunigt und es wird Speicherplatz eingespart. Auch ist eine Reperatur m&ouml;glich, falls der Datenbankserver &quot;MySQL&quot; einmal unerwartet beendet wurde und die Tabellen schaden genommen haben. Die Durchf&uuml;hrung behebt diese Probleme.<br>          </td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/backup.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Backup-Manager</b></td>
              <td><div align="right"> &gt;&gt; <a href="backup_manager.php?d4sess=<? echo($sessid); ?>"><b>Backup durchf&uuml;hren</b></a> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><p>Hier k&ouml;nnen Sie ein Systemupdate durchf&uuml;hren. Es wird eine Sicherungsdatei erstellt, die folgende Dateien enth&auml;lt:<br>
            Sicherungsdatei der Datenbank, Ordner &quot;media&quot;, sowie den Ordner &quot;mediapool&quot;.<br>
            Dieses Backup k&ouml;nnen Sie sp&auml;ter wieder herstellen. Beachten Sie bitte, dass Sie nach einer Backup-Wiederherstellung ein Rebuild durchf&uuml;hren. <br>
            <br>
          </p>            </td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/benutzer_g.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Benutzer</b></td>
              <td><div align="right"> &gt;&gt; <a href="javascript:SwitchPage('benutzer.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Benutzer&uuml;bersicht</b></a> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Hier k&ouml;nnen Sie alle Administratoren und Redakteure anlegen, Rechte zuweisen und l&ouml;schen, die mit diesem System arbeiten d&uuml;rfen.<br> </td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/gruppen.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Benutzergruppen</b></td>
              <td><div align="right"> &gt;&gt; <a href="javascript:SwitchPage('gruppen.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Benutzergruppen</b></a></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Hier k&ouml;nnen Sie Benutzergruppen anlegen, bearbeiten und l&ouml;schen. Hier legen Sie die Rechte fest, welche die verschiedenen Redakteure besitzen werden, die mit diesem System arbeiten d&uuml;rfen. <br></td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/logbrowser.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Logbrowser</b></td>
              <td><div align="right"> &gt;&gt; <a href="javascript:SwitchPage('logs.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Logbrowser</b></a></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Hier erhalten Sie Einblick &uuml;ber die letzten T&auml;tigkeiten der Administratoren und Redakteure.<br></td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/dateimanager.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Dateimanager</b></td>
              <td><div align="right"> &gt;&gt; <a href="javascript:SwitchPage('filemanager.php?d4sess=<? echo($sessid); ?>','inhalt');"><b>Dateimanager</b></a></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Mit dem Dateimanager k&ouml;nnen Sie beliebige Dateien, welche Sie erstellt haben herunterladen. Sie k&ouml;nnen nat&uuml;rlich auch neue Ordner anlegen und- oder Dateien f&uuml;r die sp&auml;tere Verwendung auf den Server laden.<br></td>
        </tr>
        <tr>
          <td bgcolor="#F4F5F7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/main/sys.gif" alt="" width="27" height="25" hspace="2" align="absmiddle"><b>Module</b></td>
              <td><div align="right"> </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Unter Modulen befinden sich alle installierten Module, welche ihnen zur Verf&uuml;gung stehen.<br>
            Um eine &Uuml;bersicht aller existierenden Module zu erhalten, schauen Sie auf unserer p4CMS-Modul vorbei.</td>
        </tr>
      </table>

 	<center>
 	</center>
 	
 	</html>
 	<?
 }
?>