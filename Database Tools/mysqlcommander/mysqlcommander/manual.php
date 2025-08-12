<?php 
include "./ressourcen/config.php";
include "./ressourcen/dbopen.php";

$page->kopf();
$page->page_start();
$page->page_pic();
for ($i=0; $i<count($config->menu); $i++) 
	$page->page_menu($config->menu[$i]);

$page->page_email();
$page->page_mitte();

$title = "Manual";
$line1 = "Server: ".$config->dbtext[$HTTP_SESSION_VARS['which_db']];
$line2 = "&copy; 2000 - 2004 by Oliver K&uuml;hrig, Niels Hoffmann";
$picture = "img/img_default.jpg";
$content->topbox($title, $picture, $line1, $line2);

// ###############################################################################

$content->html_black();
$content->config->bgcolor="White";
$content->html_punkt_text("img/pfeil_".$config->maincolor2."_rund.gif", 30, "Manual", "txtfettkl");
$content->html_link("manual_whatsnew.php", "What's new");
$content->html_br();

$i=0;
$head[$i]['german'] = "MySQL Commander ?";
$head[$i]['english'] = "MySQL Commander ?";
$text[$i]['german'] = "Dieses Tool erzeugt Backups von beliebig vielen Tabellen in den Datenbanken. Die Daten werden in Textdateien gespeichert. Diese befinden sich im \"data\"-Verzeichnis. Es kann sowohl die Tabellendefinition als auch der Inhalt gesichert werden.<br>Somit kann man einfach die komplette Datenbank kopieren (z.B. von einem Server auf einen anderen).";
$text[$i]['english'] = "This tool makes backups of all the tables in a database. The data will be stored in textfiles located in the \"data\"directory. You can backup and restore the \"SQL create table command\" and the \"content\". So you can easily make copies of your tables. (i.e. copy a hole database with a few clicks). ";
$i++;

$head[$i]['german'] = "Was wird ben&ouml;tigt ?";
$head[$i]['english'] = "Requirements";
$text[$i]['german'] = "<li>Webserver mit PHP4.1-Unterst&uuml;tzung<br><li>MySQL Datenbank ab Version 3.23";
$text[$i]['english'] = "<li>Webserver with PHP4.1-Support<br><li>MySQL database access since version 3.23";
$i++;

$head[$i]['german'] = "Installation";
$head[$i]['english'] = "Installation";
$text[$i]['german'] = "<li>Bei einer Neuinstallation starte das Script.<li>Es kommt eine Fehlermeldung. Folge dem Link bei der Meldung.<li>Editiere Username, Passwort and Server vom MySQL-Server und evtl. weitere Angaben.<br><li>Nach erfolgreicher Speicherung, klicke auf \"<< back to MySQL Commander\".";
$text[$i]['english'] = "<li>Start the script.<li>It appears a error message. Click on the link.<li>Edit the username, password and server from the MySQL-Server and possibly other settings.<br><li>After successful writing, click on \"<< back to MySQL Commander\".";
$i++;

$head[$i]['german'] = "Wie kopiere ich eine DB auf einen anderen Server ?";
$head[$i]['english'] = "How to copy a database to another server? ";
$text[$i]['german'] = "<b>Erste M&ouml;glichkeit:</b><br><li>Konfiguriere in UPDATE->Konfiguration den Quell- und Zieldatenbankserver</li><li>Mache ein komplettes Backup von allen Tabellen, die repliziert werden sollen.</li><li>Wechsele mit dem Punkt 'Server' auf den Zielserver.</li><li>Erzeuge auf dem neuen Server mit \"Erzeuge DB\" die neue Datenbank. Die Datenbank muss mit dem Namen der Quelldatenbank &uuml;bereinstimmen.</li><li>Nun w&auml;hle \"Restore Definition\", w&auml;hle die Datenbank aus. Jetzt m&uuml;ssen alle Dateien angew&auml;hlt werden. Dann dr&uuml;cke auf \"Start Restore\". Jetzt sollten die Tabellen erstellt worden sein.</li><li>Jetzt fehlt noch der Inhalt der Tabellen. W&auml;hle \"Restore Inhalt\", dann die Datenbank, dann die Dateien. Gehe quasi genauso vor, wie im vorigen Punkt.";
$text[$i]['english'] = "<b>First possibility:</b><li>Make a complete backup of your tables in the database you wish to replicate.<br><li>Change the server in the menuepoint 'server'.<br><li>In MySQL-Commander use: 'create database' to build the database. The database must have the same name like the sourcedatabase.<br><li>Now choose 'Restore Definition'. It appears all databases. Select the database you want to build and start restoring.<br><li>After this step, you can restore the content.";
$i++;

$head[$i]['german'] = "Wie kopiere ich eine DB auf einen anderen Server ?";
$head[$i]['english'] = "How to copy a database to another server? ";
$text[$i]['german'] = "<b>Zweite M&ouml;glichkeit:</b><li>Mache ein komplettes Backup von allen Tabellen, die repliziert werden sollen.<br><li>Installiere den MySQL Commander auf dem anderen Server<br><li>Kopiere die Dateien aus dem Verzeichnis \"data/{Datenbank}\" auf den anderen Server in das gleiche Verzeichnis.<br><li>Erzeuge auf dem neuen Server mit \"Erzeuge DB\" die neue Datenbank. Die Datenbank muss mit dem Namen des Verzeichnisses &uuml;bereinstimmen. Andernfalls umbenennen.<br><li>Nun w&auml;hle \"Restore Definition\", w&auml;hle die Datenbank aus. Jetzt m&uuml;ssen alle Dateien angew&auml;hlt werden. Dann dr&uuml;cke auf \"Start Restore\". Jetzt sollten die Tabellen erstellt worden sein.<br><li>Jetzt fehlt noch der Inhalt der Tabellen. W&auml;hle \"Restore Inhalt\", dann die Datenbank, dann die Dateien. Gehe quasi genauso vor, wie im vorigen Punkt.";
$text[$i]['english'] = "<b>Second possibility:</b><li>Make a complete backup of your tables in the database you wish to replicate.<br><li>Install MySQL-Commander on the new server.<br><li>Copy the directory with your backupfiles to the new server. The folder must be in the 'data'-directory just like on the other server.<br><li>In MySQL-Commander use: 'create database' to build the database. The database must have the same name like the directory you have copied.<br><li>Now choose 'Restore Definition'. It appears all databases. Select the database you want to build and start restoring.<br><li>After this step, you can restore the content.";
$i++;

$head[$i]['german'] = "Parametrisierte Sicherung";
$head[$i]['english'] = "Backup with Parameters";
$text[$i]['german'] = "Diese Funktion kann genutzt werden, wenn nur eingeschränkt Daten gebackupped werden sollen. Es kann nur eine Tabelle dabei zur Zeit gebackupped werden.<br>
<strong>Backup:</strong><br>
<li>Wähle PARAMETER, dann die Datenbank, dann die Tabelle, und dann lege für die Tabellenfelder, die eingeschränkt werden sollen, die entsprechenden Werte und den Operator fest. Wenn 'LIKE' als Operator verwendet wird können die Wildcards '_' für <em>ein</em> beliebiges Zeichen und '%' für <em>mehrere</em> beliebige Zeichen eingesetzt werden.</li>
<li>Die Optionen sind gleich denen des normalen Backups.</li>
<strong>Restore:</strong><br>
<li>Das Restore geht analog zum normalen Restore.</li>
<li>Darauf achten, das beim Restore nur der Datenausschnitt wieder in die DB geschrieben wird, der aufgrund der Einschränkung gesichert wurde. (Kann bei Restore mit Datenlöschung zu Datenverlust führen)</li>
";
$text[$i]['english'] = "This Function can be used to run a limited backup on the database. Only one table can be backupped each time.<br>
<strong>Backup:</strong><br>
<li>Choose PARAMETER, then the database, the table, then set for the tablefields, which schall limit the backup, the corresponding values and the operator between them. When using 'LIKE' as operator the wildcards '_' for <em>one</em> arbitary character und '%' for <em>multiple</em> arbitary characters can be used.</li>
<li>The options are equal to the standard backup.</li>
<strong>Restore:</strong><br>
<li>The restore is equal to the normal restore.</li>
<li>Take attention if you restore a limited backup. Only the datasets of the limited backup will be restored, which can lead into loss of data!</li>
";
$i++;

$head[$i]['german'] = "Wie nutze ich die BigTable-Funktion?";
$head[$i]['english'] = "How do I use the BigTable-Feature?";
$text[$i]['german'] = "Sollte eine Tabelle zu groß sein um sie mit der normalen Backupfunktion zu sichern, dann bietet sich die BigTable-Funktion an.<br>
<strong>Backup:</strong>
<li>Wähle BIGTABLE, dann die Datenbank, dann die Tabelle. Bestimme die Anzahl der Datensätze pro Datei in den Optionen.</li>
<li>Es kann nur eine Tabelle zur Zeit gesichert werden.</li>
<li>Während der Sicherung werden 'n' einzelne Dateien geschrieben. (Anzahl n = Datensätze in der Tabelle / Datensätze pro Datei)</li>
<li>Die Dateien werden beginnend mit 001 durchnummeriert.</li>
<strong>Restore:</strong>
<li>Gehe ins normale Restore. Wähle Restore Inhalt und beginne die erste Datei mit '001' wiederherzustellen.</li>
<li>Wenn dies abgeschlossen ist, nimm die Datei '002' u.s.w.. Achte darauf, dass die Tabelle nicht jedesmal gelöscht wird, also die Checkbox nicht angekreuzt ist.</li>
";
$text[$i]['english'] = "If a table is too big to backup, then try to use the BIGTABLE-function<br>
<strong>Backup:</strong>
<li>Choose BIGTABLE, then the database, then the table. Set the number of datasets per file in the options.</li>
<li>You can only save one table at a time.</li>
<li>While backup the script produce 'n' files. (Number n = datasets in the table / datasets per file)</li>
<li>The file get the extensions from \"001\" to \"nnn\".</li>
<strong>Restore:</strong>
<li>Go to the normal restore. Choose Restore Content and start writing back with the file \"001\".</li>
<li> Then file \"002\" and so on. Pay attention not to delete the content of the table everytime you start restoring. (Uncheck the checkbox)</li>
";
$i++;

$head[$i]['german'] = "Sicherung von BLOB-Feldern";
$head[$i]['english'] = "Backup of BLOB-Fields";
$text[$i]['german'] = "Bis Version 2.2 war es nicht möglich Binärdaten, die in der Datenbank stehen, zu sichern.<br>Ab Version 2.3 werden alle BLOB-Felder als Datei in einem seperaten Verzeichnis gespeichert. Damit lassen sich nun auch Binärdateien in der Datenbank sichern und wiederherstellen.<br>Wenn BLOB-Felder in einer Tabelle gefunden werden, wird ein Verzeichnis namens 'blobs' erstellt. Dort werden die Inhalte der BLOB-Felder in einer Datei gesichert.<br><br>Der Restore läuft genau anders herum. Es werden die Dateien eingelesen und in die Datenbank zurückgeschrieben.";
$text[$i]['english'] = "Up to version 2.2 it was impossible to save the content of BLOB-Fields.<br>Beginning with version 2.3 all BLOB-Fields are saved in a seperate files. With this improvement its it now possible to backup and restore binary files in the database. If BLOB-Fields are found, the script creates the directory 'blobs'. In this directory all files will be stored.<br><br>The restore works in the opposite way around. The script reads the file and writes the content in the database.";
$i++;

$head[$i]['german'] = "PHP kann das Verzeichnis \"data\" nicht anlegen.";
$head[$i]['english'] = "Not possible to create the directory \"data\"";
$text[$i]['german'] = "Der Webserver ist möglicherweise so konfiguriert, dass das Script keine Schreibrechte im Commander-Verzeichnis hat.<br>Abhilfe: Legen Sie per FTP ein Verzeichnis \"data\" an. Diesem Verzeichnis geben sie die Rechte \"777\". Nun sollte das Backup funktionieren.";
$text[$i]['english'] = "The webserver maybe is configured that way, the script has no rights to create the directory \"data\". To find a remedy create per FTP a directory \"data\". Give this directory the rights \"777\". Now the backup should work.";
$i++;

$head[$i]['german'] = "Backup über URL anstossen";
$head[$i]['english'] = "run backup via url adress";
$text[$i]['german'] = "Das Backup kann über einen URL Aufruf gestartet werden. Der Aufruf der Url: 'interface/backup.php?username=test& password=test2& database=mysql&tables=db|user' wird die Tabellen 'db' und 'user' aus der MySQL Systemdatenbank sichern. Die Namen der Tabellen werden durch '|' getrennt.<br>Wichtig ist Username und Passwort in der Konfiguration für die Schnittstelle angegeben zu haben.<br> Die Beschreibung der kompletten Aufrufparameter ist in '/interface/backup.php' zu finden";
$text[$i]['english'] = "The backup can be started by calling an url like: 'interface/backup.php?username=test&password=test2&database=mysql&tables=db|user'. Then the process backups the tables 'db' and 'tables' from the MySQL system database. Tablenames are seperated by '|'.<br>Username and password for the interface must be defined in the configuration.<br>The description of all parameters can be found in '/interface/backup.php'.";
$i++;



$head[$i]['german'] = "WICHTIG";
$head[$i]['english'] = "IMPORTANT";
$text[$i]['german'] = "Dieses Tool ist Freeware. Es ist erlaubt den Sourcecode zu editieren. Bitte mailt mir dann das von Euch ver&auml;nderte Script, um allen Benutzern die Modifizierungen zug&auml;nglich machen zu k&ouml;nnen. Ich &uuml;bernehme keine Garantie auf jegliche Datenverluste.";
$text[$i]['english'] = "This tool is freeware. You can edit the sourcecode at your desire, but you have to mail me the script you have changed. So, every user could profit from your mofifications. You have no warranty for any loss of data.";
$i++;

for ($i=0; $i<count($head); $i++) {
	$content->html_black();
	$content->config->bgcolor="#ebebeb";
	$content->html_headtext($head[$i][$config->language], "txtblaufett");
	$content->config->bgcolor="White";
	$content->html_text($text[$i][$config->language]);
	$content->html_br();
}
$content->html_br();

// ###############################################################################
$content->html_br();

$page->page_stop();
$page->fuss();
?>
