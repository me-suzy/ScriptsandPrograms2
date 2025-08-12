<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > deutsche Sprachdatei für's AdminCenter
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: german_admin.php 22 2005-10-15 12:38:44Z alex $
|
+--------------------------------------------------------------------------
*/

$a_lang['info1'] = array("Mitgliederliste","Es werden alle Mitglieder aufgeführt, die sich entpsrechend registriert haben. Dabei werden sowohl freigeschaltete als auch nicht freigeschaltet User aufgeführt.<br><br>Administratoren können nur gelöscht werden, wenn mehr als ein Administrator vorhanden ist.");
$a_lang['info2'] = array("Mitglieder anlegen","User die hier angelegt werden müssen erst über die entsprechenden Daten (Username, Passwort) informiert werden.<br><br>
                            Es erfolgt keine automatische Benachrichtigung an die Benutzer. Zudem ist es erforderlich eine korrekte Email-Adresse anzugeben. <br><br>
                            Hier angelegt Accounts bedürfen keiner gesonderten Aktivierung durch die Bestätigung einer Email. Besondere Vorsicht sollte bei Auswahl der jeweilig zugehörigen Gruppe getroffen werden.<br><br>
                            User, die als Administratoren angelegt sind, können das AdminCenter betretten und alle Änderungen wie Sie vornehmen.");
$a_lang['info3'] = array("Update-Information","Die Versionsnummer wird mit dem Updateserver abgeglichen. Ist eine neuere Version als die verwendete vorhanden, wird dies entsprechend angezeigt.<br><br>Aus Sicherheitsgr&uuml;nden, sollte immer die neueste Version verwendetet werden. Zudem steht f&uuml;r &auml;ltere Versionen kein Support mehr zur Verf&uuml;gung.");
$a_lang['info4'] = array("Email","Hier können Textemails an beliebig viele Benutzer gesendet werden.<br><br>
                            Der Betreff wie auch der Text müssen nur in die vorhergesehenen Felder eingetragen werden. Anschliessend können im unteren Bereich die registrierten User ausgewählt werden, an die die Email gesendet werden soll.<br><br>
                            Mit gedrückter Shift- bzw. gedrückter STRG-Taste können mehrere User gleichzeitig ausgewählt werden.");	
$a_lang['info5'] = array("Datenbankbackup","Es ist möglich ein <b>Backup der Datenbank</b> anzufertigen. Dabei können entweder alle Tabellen oder beliebige Tabellen für das Backup ausgewählt werden. <br><br>
                            Die Backup Dateien werden dann in einem Verzeichnis abgelegt und können auf Wunsch heruntergeladen werden. Wir empfehlen die Sicherung der Datenbank in gewissen Zeitabständen, damit die Datenbank zu bestimmten Zeitpunkten wieder hergestellt werden kann, falls erforderlich. <br><br>
                            Durch die Option <b>nur Struktur</b>, wird nicht der Inhalt sondern lediglich der Aufbau der Datenbank gesichert. Wird diese Datei wiederhergestellt, werden evtl. vorhandene Datenbankeinträge gelöscht.<br><br>
                            <b>Auch die Einträge, die für einen Betrieb der Engine nötig sind!!!</b><br><br>
                            Bei Klick auf den Punkt <b>Datei wiederherstellen</b> werden bereits vorgenommene Sicherungen wieder in die Datenbank gespielt.");	    
$a_lang['info6'] = array("Usergruppen Übersicht","Alle definierten Benutzergruppen werden hier aufgeführt.<br><br>Standardmässig sind die Gruppen Administrator, Besucher und Mitglied hinterlegt. Diese können zwar verändert, jedoch <b>nicht gelöscht werden</b>.<br><br>Die Anzahl der weiteren Gruppen ist nicht beschränkt und kann individuell erweitert werden.");    
$a_lang['info7'] = array("Usergruppen anlegen","Hier können eigene Berechtigungsklassen für den Benutzerbereich festgelegt werden, bzw. wer das AdminCenter betretten darf und wer nicht.<br><br>Administrationsberechtigte User können alle Einstellungen ändern und einträge editieren, auch wenn diese für den Userbereich evtl. nicht vorhanden sind. <b>Diese Option sollte mit Vorsicht verwendet werden. Nur vertraute Person sollten Administratorrechte erhalten!</b>");
$a_lang['info8'] = array("Mitglieder löschen","Gelöschte User können nicht mehr reaktiviert werden.<br><br>Die gelöschten Accounts werden aus der Datenbank genommen und sind nicht mehr verfügbar. Ein wiederherstellen ist nicht mehr möglich.");
$a_lang['info9'] = array("Useraktivierung","In dieser Liste sind Benutzer zu finden, die sich zwar registriert haben, aber die versendete Registrierungsmail <b>nicht</b> entsprechend beantwortet haben.<br><br>Es obliegt dem Administrator die nicht bestätigten Accounts freizuschalten oder diese zu löschen. In dieser Liste sind nur Einträge möglich, wenn die Option für die Registrierung mit Bestätigungsmail aktiviert ist.<br><br>Durch anklicken der Checkbox am Anfang der jeweiligen Zeile können alle markierten User gelöscht oder freigeschaltet werden.");
$a_lang['info10'] = array("Haupteinstellungen","Diese Einstellungen bilden die Grundlage für die gesamte Engine. Die Einstellungen sollten alle mit entsprechenden Angaben befüllt werden.<br><br>Die angegebenen <b>Urls</b> müssen korrekt sein, da u. U. keine Dateien per Formular hochgeladen werden können oder Grafiken nicht angezeigt werden können.<br><br>
                            Die <b>Registrierungsoptionen</b> entscheiden darüber ob die Engine als Community-Plattform dienen soll oder nicht. <br><br>Die <b>GZip</b> Option bringt bei manchen Servern Schwierigkeiten. Sollten im Userbereich Probleme mit der Darstellung auftretten (leere Seite o. Ä.) ist diese Einstellung zu ändern.<br><br>
                            Die <b>Datums- und Zeiteinstellungen</b> regeln die korrekte Darstellung, wenn der Server in einer anderen Zeitzone steht (z. B. Seite für deutsche Besucher, Server steht aber in den USA).<br><br>Die <b>Standardgruppe für Registrierung</b> regelt, welche Gruppe ein Benutzer bei Registrierung erhält. Wird die Engine einzeln oder mit anderen Engine betrieben ist hier keine Änderung notwendig. Dies sollte geändert werden, wenn z. B. ein Forum angeschlossen ist.<br><br>
                            Um die Templates für den Userbereich einfacher bearbeiten zu können, können die Templatenamen mit Hilfe der Option <b>Template Namen hinzufügen</b> im Quelltext angezeigt werden. Die jeweilige Seite kann dann aufgerufen werden. Im Quelltext (z. B. IE - Rechtsklick, Quelltext anzeigen) ist dann der jeweilige Templatename als Kommentar dargestellt.<br><br>Zudem ist zu beachten, dass die einzelnen Einstellung sinngemäß sind und zueinander passen. Es wäre theoretisch möglich hier unsinnige Einstellungen zu wählen, die die Funktionsfähigkeit der Engine enorm einschränkt");		
$a_lang['info11'] = array("Farbeinstellungen","Die angegebenen Farben werden später im Script verwendet. <br><br>
                            Werden neue Farben gespeichert, sollte danach auch die CSS-Datei neu erstellt werden, damit die Farben auch in diesen Dateien entsprechende Anwendung finden und auf der Seite dargestellt werden. Werden die Platzhalter für die Farben (alle Daten in geschweiften Klammern {} innerhalb der Templates durch normale Farbangaben ersetzt (z. B. #000000), hat die Änderung der Farbe im Formular für diesen CSS-Bereich keine Auswirkungen mehr.<br><br>
                            Andere bzw. eigene CSS-Daten können nach belieben geändert und eingetragen werden.<br><br>Um Änderungen an den CSS-Klassen oder an Farben innerhalb der CSS-Klassen zu übernehmen, sollte auf jeden Fall die CSS-Datei neu erstellt werden.");		        
$a_lang['info12'] = array("Engine Online/Offline schalten","Ist die Engine offline geschaltet, k&ouml;nnen nur noch Personen die Engine betretten, die auch die erforderlichen Gruppenrechte besitzen. Die Funktion ist n&uuml;tzlich f&uuml;r Updates o. &Auml;.");        
$a_lang['info13'] = array("Templates editieren","Templates, die verantwortlich für das Design des Userbereichs sind, können mit dem untenstehenden Editor bearbeitet werden.<br><br>
                            Zu beachten ist, dass <b>alle</b> Template-Dateien und der Ordner templates mit CHMOD 777 ausgestattet sind. Andernfalls kann das Script das veränderte Template nicht speichern.<br><br>
                            Bei Klick auf den <b>Button Preview</b> wird eine Vorschau des Templates in einem neuen Fenster gezeigt. Zu beachten ist hierbei, dass die Farben wahrscheinlich falsch dargestellt werden, da keine CSS-Informationen vorhanden sind.");		
$a_lang['info14'] = array("Im Verzeichnis gefundene Bilder in die Datenbank eintragen","Neu gefundene Bilder k&ouml;nnen hier den jeweiligen Kategorien zugeordnet werden. Die detaillierten Angaben m&uuml;ssen jedoch im nachhinein <b>pro Bild</b> einzeln erg&auml;nzt werden");							

// Datei index.php ok
$a_lang['index_er1'] = "Du musst alle Felder ausfüllen.<br>";
$a_lang['index_er2'] = "Falsches Passwort ! <br>";
$a_lang['index_er3'] = "Dieser Account besitzt keine Adminrechte !";
$a_lang['index_head'] = "Login Admin Bereich";
$a_lang['index_login'] = "Login";
$a_lang['index_pw'] = "Passwort";

// Datei head.php
$a_lang['head_logged_in_as'] = "&raquo; Angemeldet als %s";
$a_lang['head_logout'] = "Logout";

// Datei main.php ok
$a_lang['main_welcome'] = "Willkommen";
$a_lang['main_head'] = "Herzlich Willkommen im NewsEngine Admin Center";
$a_lang['main_stat'] = "Statistik";
$a_lang['main_reguser'] = "Registrierte User";
$a_lang['main_avnews'] = "News auf diesem System";
$a_lang['main_comoverall'] = "Kommentare Gesamt";
$a_lang['main_confirm'] = "Es stehen folgende Bestätigungen an";
$a_lang['main_newnews'] = "neue Newsbeitr&auml;ge";
$a_lang['main_newcom'] = "neue Kommentare";
$a_lang['main_imthings'] = "Nützliches";
$a_lang['main_installer'] = "ACHTUNG: Installer-Datei (installer.php / beta.php) ist noch nicht vom Server gelöscht!";
$a_lang['main_installed'] = "Installierte Version";
$a_lang['main_notactive'] = "Auf Aktivierung wartende User";
$a_lang['main_activate'] = "freischalten";
$a_lang['main_databasesize'] = "Gr&ouml;sse Datenbank";

// Datei navi.php ok
$a_lang['navi_cat1'] = "Menu";
$a_lang['navi_p1'] = "AdminCenter Start";
$a_lang['navi_p2'] = "NewsEngine Home";
$a_lang['navi_cat2'] = "Kategorien";
$a_lang['navi_p3'] = "Kategorien anlegen";
$a_lang['navi_p4'] = "Kategorie bearbeiten";
$a_lang['navi_cat3'] = "News";
$a_lang['navi_p5'] = "News anlegen";
$a_lang['navi_p6'] = "News bearbeiten";
$a_lang['navi_p7'] = "News suchen";
$a_lang['navi_cat4'] = "Mitglieder";
$a_lang['navi_p8'] = "Mitglieder anlegen";
$a_lang['navi_p9'] = "Mitglieder bearbeiten";
$a_lang['navi_p10'] = "Mitglieder suchen";
$a_lang['navi_cat5'] = "Avatare";
$a_lang['navi_p11'] = "Avatar anlegen";
$a_lang['navi_p12'] = "Avatare bearbeiten";
$a_lang['navi_cat6'] = "Sprache / Templates";
$a_lang['navi_p13'] = "Sprache";
$a_lang['navi_p14'] = "Templates editieren";
$a_lang['navi_cat7'] = "Konfiguration";
$a_lang['navi_p15'] = "Haupteinstellungen";
$a_lang['navi_p16'] = "Farben und Anzeigeoptionen";
$a_lang['navi_p17'] = "Newseinstellungen";
$a_lang['navi_p18'] = "Engine online/offline";
$a_lang['navi_cat8'] = "Usergruppen";
$a_lang['navi_p19'] = "Gruppen bearbeiten";
$a_lang['navi_p20'] = "Gruppe anlegen";
$a_lang['navi_p23'] = "Email versenden";
$a_lang['navi_p24'] = "Datenbank Update";
$a_lang['navi_p25'] = "Update Check";
$a_lang['navi_cat9'] = "Newsletter";
$a_lang['navi_p26'] = "Newsletter senden";
$a_lang['navi_p27'] = "Mailingliste bearbeiten";
$a_lang['navi_p28'] = "Url und Pfadinfo";
$a_lang['navi_cat10'] = "Style";
$a_lang['navi_p29'] = "Style anlegen";
$a_lang['navi_p30'] = "Style bearbeiten";
$a_lang['navi_cat11'] = "Info &amp; Pflege";

// Datei avatar.php ok
$a_lang['avatar_mes1'] = "Avatar wurde erfolgreich hinzugefügt";
$a_lang['avatar_mes2'] = "Avatar wurde erfolgreich editiert";
$a_lang['avatar_mes3'] = "Avatar wurde unwiderruflich gelöscht - ID";
$a_lang['avatar_del1'] = "Soll das gewählte Avatar";
$a_lang['avatar_del2'] = "wirklich unwiderruflich gelöscht werden?";
$a_lang['avatar_yes'] = "JA";
$a_lang['avatar_here'] = "hier";
$a_lang['avatar_mainp'] = ", um zur Hauptseite zurück zu gelangen";
$a_lang['avatar_new'] = "Neuen Avatar anlegen";

// Datei bbhelp.php
$a_lang['bbhelp_1'] = "BBCode Hilfe";
$a_lang['bbhelp_2'] = "Informationen zu BBCodes";
$a_lang['bbhelp_3'] = "Was sind BBCodes, Welche gibt es";
$a_lang['bbhelp_4'] = "BBCodes sind eine einfache Art und HTML-Anweisung zu ersetzen. Nachfolgend siehst Du die möglichen BBCodes aufgelistet und deren Ergebniss wie diese letztendlich am Bildschirm dargestellt werden. <br><br>Durch die Verwendung von BBCodes sind jegliche HTML-Codes überflüssig und werden bei der Anzeige am Bildschirm herausgefiltert.";
$a_lang['bbhelp_5'] = "BBCode";
$a_lang['bbhelp_6'] = "Darstellung am Bildschirm";
$a_lang['bbhelp_7'] = "hier Dein Text";
$a_lang['bbhelp_8'] = "kursiver Text";
$a_lang['bbhelp_9'] = "unterstrichener Text";
$a_lang['bbhelp_10'] = "Linkname";
$a_lang['bbhelp_11'] = "Email";
$a_lang['bbhelp_12'] = "hier steht der code";
$a_lang['bbhelp_13'] = "hier ein zitat";

// Datei newscat.php ok
$a_lang['newscat_mes1'] = "Kategorie erfolgreich hinzugefügt";
$a_lang['newscat_mes2'] = "Kategorie wurde gelöscht";
$a_lang['newscat_mes3'] = "Kategorie erfolgreich bearbeitet";
$a_lang['newscat_del1'] = "Soll die gewählte Kategorie";
$a_lang['newscat_del2'] = "wirklich unwiderruflich gelöscht werden?";
$a_lang['newscat_yes'] = "JA";
$a_lang['newscat_not'] = "Falls nicht, klicke";
$a_lang['newscat_here'] = "hier";
$a_lang['newscat_mainp'] = ", um zur Hauptseite zurück zu gelangen.";
$a_lang['newscat_name'] = "Name (ID)";
$a_lang['newscat_picture_name'] = "Bildname";
$a_lang['newscat_options'] = "Optionen";

// Datei newsletter.php
$a_lang['newsletter_1'] = "Abonnent wurde unwiderruflich gel&ouml;scht";
$a_lang['newsletter_2'] = "Abonnent wurde hinzugef&uuml;gt";
$a_lang['newsletter_3'] = "Abonnentendaten wurden aktualisiert";
$a_lang['newsletter_4'] = "<br>Fehler aufgetreten bei %s User ";
$a_lang['newsletter_5'] = "Es wurde(n) insgesamt %s Newsletter versendet %s";
$a_lang['newsletter_6'] = "Newsletter versenden";
$a_lang['newsletter_7'] = "Newsletterdaten<br><span class=\"smalltext\">Der Newsletter verwendet das Template newsletter_html.html und newsletter_txt.txt aus dem Verzeichnis 'templates'. Folgende Platzhalter k&ouml;nnen in den Templates oder in untenstehendem Text verwendet werden:<br>
    {abouser} = Name des Newsletterempf&auml;ngers<br>{abomail} = Emailadresse des Newsletterempf&auml;ngers<br>{abostart} = Datum seit wann der Newsletter abonniert ist<br>{stoplink} = Link um den Newsletter abzubestellen<br>{disclaimer} = Rechtlicher Hinweis</span>";
$a_lang['newsletter_8'] = "<b>Betreff</b>";
$a_lang['newsletter_9'] = "<b>Anrede</b><br><span class=\"smalltext\">Der Name des jeweiligen Benutzers wird sp&auml;ter automatisch hinzugef&uuml;gt; Platzhalter ist nicht notwendig</span>";
$a_lang['newsletter_10'] = "<b>Newsletter Text</b><br><span class=\"smalltext\">Text kann nach belieben ver&auml;ndert werden. Verwenden Sie Standard-&lt;img&gt;-Tags um Bilder einzubinden (im HTML-Modus), die Grafik/Bild muss auf Ihrem Server liegen. Geben Sie die komplette Url zur Grafik an.</span>";
$a_lang['newsletter_11'] = "Newsletter senden";
$a_lang['newsletter_12'] = "Zur&uuml;cksetzen";
$a_lang['newsletter_13'] = "Newsletter versenden";
$a_lang['newsletter_14'] = "Newsletter Konfiguration";
$a_lang['newsletter_14b'] = "Achtung, der Newsletter ist zur Zeit deaktiviert. Bevor Sie einen Newsletter versenden, sollten Sie die Newseinstellungen &auml;ndern.";
$a_lang['newsletter_15'] = "Newsletterdaten bestimmen";
$a_lang['newsletter_16'] = "HTML - Newsletter";
$a_lang['newsletter_17'] = "nur Text - Newsletter";
$a_lang['newsletter_18'] = "<b>Art des Newsletters</b><br><span class=\"smalltext\">In HTML-Newslettern k&ouml;nnen normalen HTML-Tags verwendet werden, aber nicht von jedem Email Client empfangen werden.</span>";
$a_lang['newsletter_19'] = "Alle Kategorien";
$a_lang['newsletter_20'] = "<b>Kategorie</b><br><span class=\"smalltext\">Ist eine Kategorie gew&auml;hlt, werden nur News aus dieser angezeigt. Mehrfachauswahl m&ouml;glich (STRG+)</span>";
$a_lang['newsletter_21'] = "<b>Startdatum</b><br><span class=\"smalltext\">Es werden alle News ab diesem Datum selektiert</span>";
$a_lang['newsletter_22'] = "<b>Endedatum</b><br><span class=\"smalltext\">Es werden alle News bis zu diesem Datum selektiert</span>";
$a_lang['newsletter_23'] = "Newsletter schreiben";
$a_lang['newsletter_24'] = "Mailingliste bearbeiten";
$a_lang['newsletter_25'] = "Newsletter Abonennten";
$a_lang['newsletter_26'] = "Email schreiben";
$a_lang['newsletter_27'] = "Neuen Abonennten anlegen";
$a_lang['newsletter_28'] = "Abonennten bearbeiten";
$a_lang['newsletter_29'] = "Daten zum Abonennten";
$a_lang['newsletter_30'] = "<b>Name</b><br><span class=\"smalltext\">Im Newsletter wird der Tag {abouser} durch diesen Namen ersetzt</span>";
$a_lang['newsletter_31'] = "<b>Email-Adresse</b><br><span class=\"smalltext\">An diese Adresse wird der Newsletter geschickt</span>";
$a_lang['newsletter_32'] = "Daten speichern";
$a_lang['newsletter_33'] = "Zur&uuml;ck";
$a_lang['newsletter_34'] = "Soll der gewählte Abonennt (ID: %s) wirklich unwiderruflich gelöscht werden";

// Datei comment.php ok
$a_lang['comment_mes1'] = "Der Kommentar wurde bestätigt und ist jetzt veröffentlicht";
$a_lang['comment_mes2'] = "Der Kommentar wurde gelöscht und ist nicht mehr verfügbar";
$a_lang['comment_det'] = "Details zum Kommentar";
$a_lang['comment_main'] = "Allgemeine Kommentardaten";
$a_lang['comment_news'] = "zum Artikel";
$a_lang['comment_categ'] = "in Kategorie";
$a_lang['comment_written'] = "geschrieben von";
$a_lang['comment_at'] = "am";
$a_lang['comment_headcomment'] = "Überschrift und Kommentar";
$a_lang['comment_headline'] = "Überschrift";
$a_lang['comment_close'] = "klicke hier, um das Fenster zu schließen";

// Datei language.php ok
$a_lang['language_nopacks'] = "keine Sprachpacks im angegebenen Verzeichnis gefunden";
$a_lang['language_done'] = "Sprache im Userbereich umgestellt.";
$a_lang['language_nothing'] = "Es wurde kein Einstelltyp gewählt. Bitte wähle links aus der Navigation die gewünschte Option aus.";
$a_lang['language_head'] = "Sprache auswählen";
$a_lang['language_choose'] = "Sprach-File auswählen";
$a_lang['language_exist'] = "vorhandene Sprach-Packs";
$a_lang['language_current'] = "momentan gewähltes Sprach-Pack";
$a_lang['language_button'] = "Sprache verwenden";

// Datei member.php ok
$a_lang['member_mes1a'] = "Der Login";
$a_lang['member_mes1b'] = "ist schon vergeben. Bitte einen anderen Namen wählen.<br>";
$a_lang['member_mes2'] = "Du musst Deine Email-Adresse angeben<br>";
$a_lang['member_mes3'] = "Du musst eine gültige Email-Adresse eingeben<br>";
$a_lang['member_mes4'] = "Mitglied erfolgreich hinzugefügt";
$a_lang['member_mes5'] = "Mitglied wurde unwiderruflich gelöscht";
$a_lang['member_mes6'] = "Mitgliedsdaten wurden erfolgreich bearbeitet";
$a_lang['member_u_search'] = "Suche nach Usern";
$a_lang['member_insert'] = "Bitte hier den Usernamen eingeben";
$a_lang['member_search'] = "Suchen";
$a_lang['member_del1'] = "Soll der gewählte User";
$a_lang['member_del2'] = "wirklich unwiderruflich gelöscht werden?";
$a_lang['member_not1'] = "Falls nicht, klicke";
$a_lang['member_here'] = "hier";
$a_lang['member_not2'] = ", um zur Hauptseite zurück zu gelangen.";
$a_lang['member_infos'] = "Informationen zu den Gruppenrechten";
$a_lang['member_group'] = "Gruppenname";
$a_lang['member_q1'] = "Kann Admin-Center betreten";
$a_lang['member_q2'] = "Kann News posten (wenn NewsEngine angeschlossen)";
$a_lang['member_q3'] = "Kann Kommentare editieren";
$a_lang['member_q4'] = "Kann Kommentare löschen";
$a_lang['member_q5'] = "Kann Kommentare schreiben";
$a_lang['member_admin'] = "Administrator";
$a_lang['member_coadmin'] = "Co-Administrator";
$a_lang['member_newsposter'] = "News-Poster";
$a_lang['member_supermod'] = "Super-Moderator";
$a_lang['member_mod'] = "Moderator";
$a_lang['member_member'] = "Mitglied";
$a_lang['member_guest'] = "Gast";
$a_lang['member_choosen'] = "wenn gewählt";
$a_lang['member_desc'] = "alle anderen, hier nicht aufgeführten Gruppen sind lediglich für Programm interne Zwecke, haben aber keine besonderen Recht";
$a_lang['member_close'] = "klicke hier, um das Fenster zu schließen";
$a_lang['member_yes'] = "Ja";
$a_lang['member_del_success'] = "Markierte User wurden gelöscht";
$a_lang['member_active_success'] = "Markierte User wurden aktiviert";
$a_lang['member_activation'] = "Auf Aktivierung wartende User";
$a_lang['member_actname'] = "Username";
$a_lang['member_actmail'] = "Email";
$a_lang['member_actsince'] = "Angemeldet am";
$a_lang['member_actyes'] = "Freischalten";
$a_lang['member_actdel'] = "User löschen";
$a_lang['member_avatars'] = "Avatare";
$a_lang['member_use_avatar'] = "Avatar &uuml;bernehmen";
$a_lang['member_available_avatars'] = "Verf&uuml;gbare Avatare";
$a_lang['member_choose_avatar'] = "Avatar auswählen";

// Datei news.php ok
$a_lang['news_mes1'] = "Links wurden erfolgreich bearbeitet";
$a_lang['news_mes2'] = "News wurde erfolgreich bearbeitet";
$a_lang['news_mes3'] = "News wurde unwiderruflich gelöscht";
$a_lang['news_mes4'] = "News wurden erfolgreich in die Datenbank eingetragen";
$a_lang['news_mes5'] = "Die News wurden bestätigt und sind jetzt veröffentlicht";
$a_lang['news_mes6'] = "Link wurde unwiderruflich gelöscht";
$a_lang['news_mes7'] = "Link wurde in die Datenbank eingetragen";
$a_lang['news_mes8'] = "Eingabe wurde nicht gespeichert, bitte wiederholen.";
$a_lang['news_click'] = "Klicke";
$a_lang['news_addlink'] = "um einen Link hinzuzufügen";
$a_lang['news_addmorelinks'] = "um einen weiteren Link hinzuzufügen";
$a_lang['news_search_f'] = "Suche nach News-Headline";
$a_lang['news_inserthead'] = "Bitte hier die gesuchte Headline eingeben";
$a_lang['news_search'] = "Suchen";
$a_lang['news_del1'] = "Sollen die gewählten News";
$a_lang['news_del2'] = "wirklich unwiderruflich gelöscht werden?";
$a_lang['news_yes'] = "JA";
$a_lang['news_not1'] = "Falls nicht, klicke";
$a_lang['news_here'] = "hier";
$a_lang['news_not2'] = ", um zur Hauptseite zurück zu gelangen.";
$a_lang['search_define'] = "<b>Suche definieren</b>";
$a_lang['search_button1'] = "Suchen";
$a_lang['search_note1'] = "<b>Suchbegriff eingeben</b><br><span class=\"smalltext\">Je nachdem was bei 'Suche definieren' gew&auml;hlt wurde</span>";	
$a_lang['prog_no_result'] = "Hinweis";
$a_lang['prog_no_result1'] = "Zum angegebenen Suchbegriff";
$a_lang['prog_no_result2'] = "wurde kein Eintrag gefunden";
$a_lang['search_in_headline'] = "in der &Uuml;berschrift";
$a_lang['search_in_newstext'] = "im Newstext";
$a_lang['pic_right_of_news'] = "Bild rechts vom Newstext";
$a_lang['pic_left_of_news'] = "Bild links vom Newstext";
$a_lang['pic_in_front_of_news'] = "Bild vor der News&uuml;berschrift";
$a_lang['news_in_category'] = "in %s";
$a_lang['news_really_delete_link'] = "Soll der gewählte Link (ID: %s) wirklich unwiderruflich gelöscht werden?";
$a_lang['news_pictures'] = "Bilder";
$a_lang['news_size'] = "Gr&ouml;sse:";
$a_lang['news_insert_image'] = "Bild einf&uuml;gen";
$a_lang['news_delete_image'] = "Bild l&ouml;schen";
$a_lang['news_delete_image_not_possible'] = "Bild l&ouml;schen nicht m&ouml;glich";
$a_lang['news_pic_success_deleted'] = "Bild wurde vom Server entfernt.";
$a_lang['news_pic_not_deleted'] = "Bild konnte NICHT vom Server entfernt werden";
$a_lang['news_do_you_really_want_delete'] = "Wollen Sie das Bild wirklich l&ouml;schen?";

// Datei settings.php ok
$a_lang['settings_mes1'] = "Farben wurden erfolgreich geändert";
$a_lang['settings_mes2'] = "Haupteinstellungen wurden erfolgreich geändert";
$a_lang['settings_mes3'] = "Newseinstellungen wurden erfolgreich bearbeitet";
$a_lang['settings_onoff'] = "Status der Engine erfolgreich bearbeitet";
$a_lang['settings_mes4'] = "CSS-Template erfolgreich geschrieben";
$a_lang['settings_mes5'] = "CSS-Template konnten NICHT gespeichert werden!";
$a_lang['settings_mes6'] = "CSS-Datei erfolgreich aktualisiert";
$a_lang['settings_mes7'] = "CSS-Datei konnten NICHT aktualisiert werden!";
$a_lang['settings_css1'] = "CSS-File erstellen";
$a_lang['settings_css2'] = "<b>CSS-File:</b><br><span class=\"smalltext\">Wird ein neues CSS-File erstellt, wird die vorhandene CSS-Datei gel&ouml;scht und neu erstellt. Manuelle &Auml;nderungen die an der Datei dlengine.css vorgenommen wurden werden &uuml;berschrieben. Das CSS-Template mit den eigenen Einstellungen wird neu gespeichert.<br><br>Folgende Platzhalter werden beim speichern durch oben eingetragene Daten ersetzt:<br>{fontf} = Schriftart<br>{maincol} = Hauptfarbe<br>{primcol} = Zweite Farbe<br>{backcol} = Hintergrundfarbe<br>{bordercol} = Farbe der Ränder<br>{textcol1} = Textfarbe1<br>{textcol2} = Textfarbe2<br>{hovercol} = Textfarbe3<br>{postcol1} = Farbe 1 der Kommentare<br>{postcol2} = Farbe 2 der Kommentare</span>";
$a_lang['settings_css3'] = "CSS-File speichern";
$a_lang['settings_1'] = "Datums- und Zeiteinstellungen";
$a_lang['settings_2'] = "Stunden";
$a_lang['settings_3'] = "<b>Unterschied zur Serverzeit</b><br><span class=\"smalltext\">Hier die Differenz des Servers in Stunden zur GMT ausw&auml;hlen</span>";
$a_lang['settings_4'] = "<b>Datumformat Lang:</b><br><span class=\"smalltext\">Einstellungen gem. PHP-Funktion <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_5'] = "<b>Datumformat Kurz:</b><br><span class=\"smalltext\">Einstellungen gem. PHP-Funktion <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_6'] = "<b>Zeitformat:</b><br><span class=\"smalltext\">Einstellungen gem. PHP-Funktion <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_7'] = "<b>SMTP-Server verwenden</b><br><span class=\"smalltext\">Wird meist bei Windows-Servern vewendet, da hier die mail()-Funktion von PHP nicht funktioniert.</span>";    
$a_lang['settings_8'] = "<b>SMTP-Server Adresse</b><br><span class=\"smalltext\">Nur n&ouml;tig, wenn ein SMTP-Server verwendet wird, sonst leer lassen.</span>";
$a_lang['settings_9'] = "<b>SMTP Username</b><br><span class=\"smalltext\">Nur n&ouml;tig, wenn ein SMTP-Server verwendet wird, sonst leer lassen.</span>";
$a_lang['settings_10'] = "<b>SMTP Passwort</b><br><span class=\"smalltext\">Nur n&ouml;tig, wenn ein SMTP-Server verwendet wird, sonst leer lassen.</span>";
$a_lang['settings_11'] = "<b>Diashow aktivieren</b><br><span class=\"smalltext\">F&uuml;r jede Kategorie kann eine Diashow angesehen werden, f&uuml;r diese Diashow werden Thumbnails in einer Zwischengr&ouml;sse ben&ouml;tigt, die &uuml;ber den Men&uuml;punkt 'Thumbnails erstellen' angelegt werden m&uuml;ssen - andernfalls wird die Diashow nur f&uuml;r die existierenden Bilder gezeigt.<br><i>ACHTUNG, ist nur m&ouml;glich wenn die GD-Bibliothek vorhanden ist.</i></span>";
$a_lang['wysiwyg_settings'] = "WYSIWYG Einstellungen";
$a_lang['wysiwyg_editor_in_userarea'] = "<b>WYSIWYG-Editor im Userbereich</b><br><span class=\"smalltext\">F&uuml;r alle Newsposter im Userbereich wird statt dem normalen BBCode-Editor ein WYSIWYG-Editor angezeigt.<br><b>ACHTUNG SICHERHEITSRISIKO!</b> User k&ouml;nnen hier HTML-Code und Javascript posten, die evtl. die Seite zerst&ouml;ren k&ouml;nnen.</span>";
$a_lang['wysiwyg_editor_in_admincenter'] = "<b>WYSIWYG-Editor im AdminCenter</b><br><span class=\"smalltext\">Ersetzt den Standard BBCode-Editor im Admin-Center durch einen WYSIWYG-Editor. Ein mit dem WYSIWYG-Editor angelegter Text kann NICHT mit dem BBCode-Editor bearbeitet werden.<br><b>ACHTUNG SICHERHEITSRISIKO!</b> User k&ouml;nnen hier HTML-Code und Javascript posten, die evtl. die Seite zerst&ouml;ren k&ouml;nnen.</span>";
$a_lang['path_and_url_external_use'] = "Pfade und Url's zur externen Verwendung";
$a_lang['path'] = "Pfade";
$a_lang['path_to_the_engine'] = "<b>Pfad zur Engine</b><br><span class=\"smalltext\">Wichtig bei Verwendung der Datei newsinfo.php</span>";
$a_lang['url_to_display_category'] = "<b>%s</b><br><span class=\"smalltext\">Nur Anzeige der Kategorie %s</span>";
$a_lang['archive_splitting'] = "<b>Archiv-Teilung</b><br><span class=\"smalltext\">Ansicht-Aufteilung nach Monaten/Jahren oder nur nach Jahren</span>";
$a_lang['by_month_year'] = "nach Monaten/Jahren";
$a_lang['by_year'] = "nach Jahren";
$a_lang['alternating_news_color'] = "<b>News abwechselnd farbig anzeigen</b><br><span class=\"smalltext\">Die News werden abwechselnd farbig dargestellt gem. Einstellungen Listenfarben, andernfalls werden alle News in der zweiten Farbe angezeigt</span>";
$a_lang['mail_a_friend'] = "<b>Weiterempfehlen</b><br><span class=\"smalltext\">Aktiviert den Weiterempfehlungs-Link unterhalb eines Newsberichts</span>";
$a_lang['display_categorie_names'] = "<b>Kategorienname vor News&uuml;berschrift</b><br><span class=\"smalltext\">Zeigt den Namen der jeweiligen Kategorie vor der News&uuml;berschrift an</span>";
$a_lang['settings_newsdisplay'] = "Einstellungen Newsanzeige";
$a_lang['date_n_timesettings_news'] = "<b>Datumsanzeige Newsbericht</b><br><span class=\"smalltext\">Datums- und Uhrzeitanzeige zu einem Newsbericht</span>";
$a_lang['settings_newsdisplay_date'] = "Datum";
$a_lang['settings_newsdisplay_date_time'] = "Datum - Uhrzeit";
$a_lang['settings_newsdisplay_day_date'] = "Tag, Datum";
$a_lang['settings_newsdisplay_day_date-time'] = "Tag, Datum - Uhrzeit";
$a_lang['settings_category_start_tags'] = "<b>Kategorie Start-Tags</b><br><span class=\"smalltext\">HTML-Tags die vor dem Kategorie-Namen angezeigt werden, wenn der Kategoriename vor der News&uuml;berschrift angezeigt wird.</span>";
$a_lang['settings_category_end_tags'] = "<b>Kategorie Ende-Tags</b><br><span class=\"smalltext\">HTML-Tags die nach dem Kategorie-Namen angezeigt werden, wenn der Kategoriename vor der News&uuml;berschrift angezeigt wird.</span>";

// Datei style.php
$a_lang['no_template_folder_found'] = "Es wurden keine Unterordner im Ordner /templates gefunden";
$a_lang['style_successfully_created'] = "Style wurde erfolgreich erstellt<br>";
$a_lang['style_changed'] = "Style wurde ge&auml;ndert<br>";
$a_lang['style_changed2'] = "Style wurde ge&auml;ndert";
$a_lang['edit_style_sets'] = "Style-Sets bearbeiten";
$a_lang['available_styles'] = "Verf&uuml;gbare Styles";
$a_lang['style_set_in_use'] = "Style-Set wird verwendet";
$a_lang['style_edit'] = "bearbeiten";
$a_lang['style_delete'] = "l&ouml;schen";
$a_lang['use_style_set'] = "Style verwenden";
$a_lang['no_style_set_available'] = "Kein Style-Set hinterlegt";
$a_lang['add_style_set'] = "Neues Style-Set";
$a_lang['body_data'] = "Allgemeine Daten<br><span class=\"smalltext\">Angaben die f&uuml;r die komplette Seite oder f&uuml;r das Style-Set selbst.</span>";
$a_lang['style_set_name'] = "<b>Name des Style-Sets</b>";
$a_lang['style_templat_folder_name'] = "<b>Template-Ordner</b>";
$a_lang['body_font_face'] = "<b>Schriftart</b>";
$a_lang['body_font_color'] = "<b>Schriftfarbe</b>";
$a_lang['body_font_size'] = "<b>Schriftgroesse</b>";
$a_lang['background_color'] = "<b>Hintergrundfarbe</b>";
$a_lang['border_color'] = "<b>Rahmenfarbe</b>";
$a_lang['design_row_top'] = "Balken oben<br><span class=\"smalltext\">Leiste, die Willkommensgru&szlig; zeigt und Links zur Mitglieder&uuml;bersicht</span>";
$a_lang['breadcrumb_row'] = "Breadcrumb Leiste";
$a_lang['font_color_mouseover'] = "<b>Schriftfarbe bei Mouseover</b>";
$a_lang['design_main_area'] = "Hauptteil<br><span class=\"smalltext\">Alle Inhaltsbereiche</span>";
$a_lang['alternating_bg_color1'] = "<b>Hintergrundfarbe 1</b><br><span class=\"smalltext\">z. B. Kommentare, Suchergebnisse etc.; alle Listen mit abwechselnder Farbe</span>";
$a_lang['alternating_bg_color2'] = "<b>Hintergrundfarbe 2</b><br><span class=\"smalltext\">z. B. Kommentare, Suchergebnisse etc.; alle Listen mit abwechselnder Farbe</span>";
$a_lang['design_row_bottom'] = "Balken unten<br><span class=\"smalltext\">Enth&auml;lt z. B. die Quick-Suche</span>";
$a_lang['background_highlighted_area'] = "<b>Hintergrundfarbe f&uuml;r hervorgehobene Bereiche</b><br><span class=\"smalltext\">z. B. &Uuml;berschriften bei Listen</span>";
$a_lang['font_color_highlighted_area'] = "<b>Schriftfarbe f&uuml;r hervorgehobene Bereiche</b>";
$a_lang['font_color_hover_highlighted_area'] = "<b>Schriftfarbe bei Mouseover f&uuml;r hervorgehobene Bereiche</b>";
$a_lang['edit_css_file_directly'] = "CSS-Datei direkt bearbeiten";
$a_lang['css_description'] = "<b>CSS-Daten</b><br><span class=\"smalltext\">Wird das Style-Set gespeichert, werden automatisch alle Platzhalter in geschweiften Klammern durch obige Farben ersetzt. Bei Bedarf, kann auch eine &Auml;nderung direkt im nebenstehenden Text stattfinden.<br /><br />Bei &Auml;nderung des kompletten CSS-Files sollte es in dieses Textfeld eingegeben bzw. kopiert werden, damit die CSS-Datei beim speichern entsprechend erstellt wird.</span>";
$a_lang['save_style_set'] = "Style-Set anlegen";
$a_lang['reset_style_set'] = "Zur&uuml;cksetzen";
$a_lang['delete_style_set'] = "Style-Set l&ouml;schen:";
$a_lang['confirm_delete_style_set'] = "Soll das gew&auml;hlte Style-Set wirklich unwiderruflich gel&ouml;scht werden?";
$a_lang['style_set_deleted'] = "Style-Set wurde gel&ouml;scht";
$a_lang['style_del_not_possible'] = "Aktuelles Style-Set kann nicht gel&ouml;scht werden";

// Datei templates.php ok
$a_lang['templates_mes1'] = "Template wurden erfolgreich gespeichert";
$a_lang['templates_mes2'] = "Es ist ein Fehler aufgetreten. Bitte Schritte wiederholen";
$a_lang['templates_mes3'] = "keine Templates im angegebenen Verzeichnis gefunden";
$a_lang['templates_nochoosen'] = "Es wurde kein Einstelltyp gewählt. Bitte wähle links aus der Navigation die gewünschte Option aus.";
$a_lang['templates_info'] = "Innerhalb der Templates kann normaler HTML-Code verwendet werden. Tags mit führendem \$ sind entsprechende Variablen die durch das Programm ersetzt werden, ebenso wie Anweisungen in geschweiften Klammern {}";
$a_lang['templates_edittpl'] = "Templates bearbeiten";
$a_lang['templates_existtpl'] = "vorhandene Templates editieren";
$a_lang['templates_choosetpl'] = "Template auswählen";
$a_lang['templates_loadtpl'] = "Template laden";
$a_lang['templates_htmltpl'] = "Template Quellcode";
$a_lang['templates_savetpl'] = "Template speichern";

// Datei uploads.php ok
$a_lang['uploads_url'] = "Url zum Download";
$a_lang['uploads_dat_cat'] = "Dateiname des Kategorie-/Newsbildes";
$a_lang['uploads_dat_av'] = "Dateiname des Avatars";
$a_lang['uploads_extens'] = "Dateierweiterung ist ungültig, Upload wurde nicht durchgeführt";
$a_lang['uploads_size'] = "Die angegebene Datei ist zu groß, maximale Grösse";
$a_lang['uploads_success'] = "Upload erfolgreich, Name wurde nicht geändert!";
$a_lang['uploads_copy'] = "Kopieren der Datei nicht möglich";
$a_lang['uploads_stillexist'] = "Dateiname existiert bereits, kopieren nicht möglich!";
$a_lang['uploads_fileupload'] = "Fileupload";
$a_lang['uploads_categupload'] = "Upload Kategorie-/Newsbild";
$a_lang['uploads_avatupload'] = "Avatarupload";
$a_lang['uploads_new'] = "Neues File bzw. Bild hochladen";
$a_lang['uploads_note1'] = "Bitte beachte, dass es nur möglich ist Dateien bis zu";
$a_lang['uploads_note2'] = "Bytes (oder evtl. maximale Grösse der Servereinstellungen) hochzuladen. Für größere Dateien muss ein FTP-Programm verwendet werden.";
$a_lang['uploads_search'] = "Suche hier das";
$a_lang['uploads_upload'] = "Upload";
$a_lang['uploads_reset'] = "Zurücksetzen";
$a_lang['uploads_close'] = "klicke hier, um das Fenster zu schließen";
$a_lang['uploads_ok1'] = "Datei-Upload erfolgreich !";
$a_lang['uploads_ok2'] = "Übertrage den Namen in das Feld";
$a_lang['uploads_ok3'] = "Daten übertragen, Feldname";
$a_lang['uploads_ok4'] = "Übertrage den Namen in das Feld <u>Grösse des Downloads in Byte</u>";
$a_lang['uploads_ok5'] = "Daten in das Feld Grösse des Downloads in Byte übertragen";
$a_lang['uploads_changename'] = "Falls eine Datei mit diesem Dateinamen existiert, Dateinamen &auml;ndern?";
$a_lang['uploads_nopermission'] = "In dieses Verzeichnis kann nicht geschrieben werden, keine Schreibberechtigung";
$a_lang['uploads_ok6'] = "Daten &uuml;bertragen";
$a_lang['uploads_ok7'] = "Klicke den folgenden Link, um die Daten (Dateiname: ";
$a_lang['uploads_ok8'] = ") in das Formularfeld zu &uuml;bertragen. Dieses Fenster wird dann automatisch geschlossen.";
$a_lang['uploads_message'] = "Hier muss die Datei mit dem Bild auf dem eigenen Computer ausgew&auml;hlt werden";
$a_lang['uploads_h1'] = "Bild";
$a_lang['uploads_button1'] = "Datei senden";

// Datei groups.php
$a_lang['groups_1'] = "Gruppe erfolgreich hinzugef&uuml;gt";
$a_lang['groups_2'] = "Gruppe gel&ouml;scht";
$a_lang['groups_3'] = "Gruppe erfolgreich editiert";
$a_lang['groups_4'] = "Gruppe zum editieren ausw&auml;hlen";
$a_lang['groups_5'] = "Folgende Gruppen wurden gefunden";
$a_lang['groups_6'] = "Gruppe l&ouml;schen";
$a_lang['groups_7'] = "Rechte editieren";
$a_lang['groups_8'] = "Gew&auml;hlte Gruppe editieren";
$a_lang['groups_9'] = "Allgemeine Einstellungen";
$a_lang['groups_10'] = "<b>Gruppenname</b>";
$a_lang['groups_11'] = "<b>Kann AdminCenter betreten</b>";
$a_lang['groups_12'] = "<b>Kann Engine betreten, wenn offline geschaltet</b>";
$a_lang['groups_13'] = "<b>Kann Engine-Suche benutzen</b>";
$a_lang['groups_14'] = "<b>Kann eigenes Profil editieren</b>";
$a_lang['groups_15'] = "<b>Kann Miglieder&uuml;bersicht sehen</b>";
$a_lang['groups_16'] = "<b>Kann Kommentare schreiben:</b><br><span class=\"smalltext\">Anzuzeigender Name kann bei den Haupteinstellungen gew&auml;hlt werden</span>";
$a_lang['groups_17'] = "Moderatorspezifische Einstellungen";
$a_lang['groups_18'] = "<b>Kann Kommentare editieren</b>";
$a_lang['groups_19'] = "<b>Kann Kommentare l&ouml;schen</b>";
$a_lang['groups_20'] = "Enginespezifische Einstellungen";
$a_lang['groups_21'] = "<b>Kann News schreiben</b><br><span class=\"smalltext\">Erlaubt dem User gleichzeitig Bilder zu einem Newsbericht auf den Server zu laden</span>";
$a_lang['groups_22'] = "Kann die Top-Liste sehen:<br><span class=\"smalltext\">Die Topliste muss dazu bei den Haupteinstellungen aktiviert sein</span>";
$a_lang['groups_23'] = "Kann die erweiterte Statistik benutzen:<br><span class=\"smalltext\">Die erweiterte Statistik muss dazu bei den Haupteinstellungen aktiviert sein</span>";
$a_lang['groups_24'] = "Kann Dateien f&uuml;r registrierte Mitglieder downloaden";
$a_lang['groups_25'] = "User-Gruppe l&ouml;schen";
$a_lang['groups_26'] = "Soll die gew&auml;hlte Gruppe wirklich unwiderruflich gel&ouml;scht werden?<br><span class=\"smalltext\">Alle User in dieser Gruppe erhalten den Status eines normalen registrierten Mitglieds</span>";
$a_lang['groups_28'] = "Neue Gruppe anlegen";

// Datei adminutil.php
$a_lang['adminutil_1'] = "Gew&uuml;nschtes File kann nicht geladen werden, bitte pr&uuml;fen!";
$a_lang['adminutil_2'] = "Datei wiederherstellen";
$a_lang['adminutil_3'] = "Download";
$a_lang['adminutil_4'] = "L&ouml;schen";
$a_lang['adminutil_5'] = "Kein Filename angegeben!";
$a_lang['adminutil_6'] = "Backup erstellt!";
$a_lang['adminutil_7'] = "Konnte Daten nicht in Datei einf&uuml;gen!";
$a_lang['adminutil_8'] = "Datei wurde erfolgreich gel&ouml;scht";
$a_lang['adminutil_9'] = "Datei konnte nicht gel&ouml;scht werden";
$a_lang['adminutil_10'] = "Nachricht nicht versendet!<br>";
$a_lang['adminutil_11'] = "Mail Error";
$a_lang['adminutil_12'] = "Nachricht wurde versendet!";
$a_lang['adminutil_13'] = "Email an User senden";
$a_lang['adminutil_14'] = "Nachfolgend Text eingeben und User ausw&auml;hlen";
$a_lang['adminutil_15'] = "<b>Betreff der Email</b>";
$a_lang['adminutil_16'] = "<b>Email-Text</b>";
$a_lang['adminutil_17'] = "<b>Empf&auml;nger ausw&auml;hlen</b>";
$a_lang['adminutil_18'] = "Email senden";
$a_lang['adminutil_19'] = "Zur&uuml;cksetzen";
$a_lang['adminutil_20'] = "Keine Tabellen ausgew&auml;hlt";
$a_lang['adminutil_21'] = "Backupdatei wurde erfolgreich zur&uuml;ckgespielt";
$a_lang['adminutil_22'] = "Beim Zur&uuml;ckspielen der Datei ist ein Fehler aufgetreten!";
$a_lang['adminutil_23'] = "Datenbank Backup erstellen";
$a_lang['adminutil_24'] = "Backup Einstellungen/Auswahl";
$a_lang['adminutil_25'] = "Alle ausw&auml;hlen";
$a_lang['adminutil_26'] = "Auswahl aufheben";
$a_lang['adminutil_27'] = "<b>Tabellen ausw&auml;hlen</b><br><span class=\"smalltext\">Es k&ouml;nnen eine oder mehrere der nebenstehenden Tabellen ausgew&auml;hlt werden. Die Sicherung wird anschliessend im Verzeichnis backup zur weitere Verwendung abgelegt.</span>";
$a_lang['adminutil_28'] = "<b>Nur Struktur</b><br><span class=\"smalltext\">Bei Ja wird lediglich die Struktur der Tabellen gesichert, nicht aber deren Inhalt!</span>";
$a_lang['adminutil_29'] = "Backupdatei erstellen";
$a_lang['adminutil_30'] = "Verf&uuml;gbare Backups";
$a_lang['adminutil_31'] = "Backupdatei l&ouml;schen";
$a_lang['adminutil_32'] = "L&ouml;sche";
$a_lang['adminutil_33'] = "Soll genannte Backup-Datei wirklich gel&ouml;scht werden?";
$a_lang['adminutil_34'] = "Update-Pr&uuml;fung";
$a_lang['adminutil_35'] = "Es ist momentan keine neuere Version verf&uuml;gbar. Die Version";
$a_lang['adminutil_36'] = "ist auf dem aktuellen Stand.";
$a_lang['adminutil_37'] = "Datenbank Gr&ouml;sse";

// Datei adminfunc.inc.php
$a_lang['afunc_1'] = "ID";
$a_lang['afunc_2'] = "News-&Uuml;berschrift/Kategorie";
$a_lang['afunc_3'] = "Titel";
$a_lang['afunc_4'] = "Autor / Datum";
$a_lang['afunc_5'] = "Optionen";
$a_lang['afunc_6'] = "GAST";
$a_lang['afunc_7'] = "Bestätigen";
$a_lang['afunc_8'] = "Löschen";
$a_lang['afunc_9'] = "Details";
$a_lang['afunc_10'] = "Introtext";
$a_lang['afunc_11'] = "News bestätigen";
$a_lang['afunc_12'] = "News löschen";
$a_lang['afunc_13'] = "News editieren";
$a_lang['afunc_14'] = "Headline";
$a_lang['afunc_15'] = "zu News";
$a_lang['afunc_16'] = "Kategorie";
$a_lang['afunc_17'] = "Hits / Bewertung";
$a_lang['afunc_18'] = "<b>keine</b> Bewertung";
$a_lang['afunc_19'] = "Stimmen";
$a_lang['afunc_20'] = "keine Kommentare";
$a_lang['afunc_21'] = "Kommentare";
$a_lang['afunc_22'] = "<b>Hintergrundfarbe:</b><br><span class=\"smalltext\">Seitenhintergrund, wird für alle Seiten verwendet</span>";
$a_lang['afunc_23'] = "";
$a_lang['afunc_24'] = "Email / HP";
$a_lang['afunc_25'] = "Wähle eine Kategorie aus";
$a_lang['afunc_26'] = "Folgende Kategorien sind verfügbar";
$a_lang['afunc_27'] = "";
$a_lang['afunc_28'] = "editieren";
$a_lang['afunc_29'] = "löschen";
$a_lang['afunc_30'] = "";
$a_lang['afunc_31'] = "Füge eine neue Kategorie hinzu";
$a_lang['afunc_32'] = "Daten zur neuen Kategorie";
$a_lang['afunc_33'] = "<b>Kategorien-Name</b>";
$a_lang['afunc_34'] = "<b>Grafik zur Kategorie</b><br><span class=\"smalltext\">(nicht die kompl URL, z. B. &quot;bild.gif&quot;) ";
$a_lang['afunc_35'] = "Upload - Kategoriebild";
$a_lang['afunc_36'] = "Kategorie anlegen";
$a_lang['afunc_37'] = "Eingabe zurücksetzen";
$a_lang['afunc_38'] = "keine (neue Hauptkategorie)";
$a_lang['afunc_39'] = "Bearbeite die Kategorie";
$a_lang['afunc_40'] = "Daten zur gewählten Kategorie";
$a_lang['afunc_41'] = "Kategorien-Name";
$a_lang['afunc_42'] = "hier kannst Du eine Grafik angeben<br><span class=\"smalltext\"> (nicht die kompl URL, z. B. &quot;bild.gif&quot;)";
$a_lang['afunc_43'] = "";
$a_lang['afunc_44'] = "Kategorie ändern";
$a_lang['afunc_45'] = "Farbeinstellungen und Anzeigeoptionen";
$a_lang['afunc_46'] = "Allgemeine Farbeinstellungen";
$a_lang['afunc_47'] = "<b>Hauptfarbe:</b><br><span class=\"smalltext\">Hintergrundfarbe der Überschriften</span>";
$a_lang['afunc_48'] = "<b>Zweite Farbe:</b><br><span class=\"smalltext\">Farbe der Downloads und Kategorien</span>";
$a_lang['afunc_49'] = "<b>Farbe der Ränder</b>";
$a_lang['afunc_50'] = "<b>Farbe 1 der Listen</b><br><span class=\"smalltext\">z. B. Kommentare, Suchergebnisse etc.; alle Listen mit abwechselnder Farbe</span>";
$a_lang['afunc_51'] = "<b>Farbe 2 der Listen</b><br><span class=\"smalltext\">z. B. Kommentare, Suchergebnisse etc.; alle Listen mit abwechselnder Farbe</span>";
$a_lang['afunc_52'] = "Textfarben";
$a_lang['afunc_53'] = "<b>Text:</b><br><span class=\"smalltext\">Textfarbe innerhalb der Downloads und Kategorien</span>";
$a_lang['afunc_54'] = "<b>Textfarbe2:</b><br><span class=\"smalltext\">Textfarbe der Überschriften</span>";
$a_lang['afunc_55'] = "<b>Textfarbe3:</b><br><span class=\"smalltext\">Textfarbe für hervorgehobenen Text</span>";
$a_lang['afunc_56'] = "<b>Schriftart</b>";
$a_lang['afunc_57'] = "Änderungen speichern";
$a_lang['afunc_58'] = "Newseinstellungen";
$a_lang['afunc_59'] = "Haupteinstellung für die News";
$a_lang['afunc_60'] = "<b>Anzahl der News auf der Startseite</b>";
$a_lang['afunc_61'] = "Ja";
$a_lang['afunc_62'] = "Nein";
$a_lang['afunc_63'] = "<b>Sollen bei den News Grafiken angezeigt werden</b><br><span class=\"smalltext\">sollten zu den News extra Grafiken vorhanden sein, werden diese angezeigt</span>";
$a_lang['afunc_64'] = "Einstellungen Newsposter-Funktion";
$a_lang['afunc_65'] = "<b>Newsartikel der Newsposter sofort veröffentlicht?</b><br><span class=\"smalltext\">Nein, wenn diese erst vom Admin bestätigt werden müssen</span>";
$a_lang['afunc_66'] = "<b>Dürfen die Newsposter Click-Smilies verwenden?</b><br><span class=\"smalltext\">Click-Smilies sind nur bei deaktiviertem WYSIWYG-Modus m&ouml;glich</span>";
$a_lang['afunc_67'] = "Headlines, Kategoriensuche";
$a_lang['afunc_68'] = "<b>Newsheadlines auf der Startseite extra anzeigen</b>";
$a_lang['afunc_69'] = "<b>Anzahl der Headlines</b><br><span class=\"smalltext\">Ist nur aktive, wenn die Headlines auf der Startseite extra angezeigt werden.</span>";
$a_lang['afunc_70'] = "<b>Kategorienbox</b><br><span class=\"smalltext\">Auswahlbox um nur News einer Kategorie auszuw&auml;hlen</span>";
$a_lang['afunc_71'] = "Archiv-Einstellungen";
$a_lang['afunc_72'] = "<b>Sortierung Newsarchiv</b><br><span class=\"smalltext\">Das Newsarchiv kann aufsteigend oder absteigend sortiert werden.</span>";
$a_lang['afunc_73'] = "aufsteigend";
$a_lang['afunc_74'] = "absteigend";
$a_lang['afunc_75'] = "Zur&uuml;ck";
$a_lang['afunc_76'] = "";
$a_lang['afunc_77'] = "";
$a_lang['afunc_78'] = "";
$a_lang['afunc_79'] = "";
$a_lang['afunc_80'] = "";
$a_lang['afunc_81'] = "";
$a_lang['afunc_82'] = "";
$a_lang['afunc_83'] = "";
$a_lang['afunc_84'] = "Haupteinstellungen";
$a_lang['afunc_85'] = "<b>Url's für die Newsengine</b>";
$a_lang['afunc_86'] = "<b>Url zur Homepage</b><br><span class=\"smalltext\">Url zur Startseite, wird in den Breadcrumbs verwendet</span>";
$a_lang['afunc_87'] = "<b>Haupturl zum Script</b>";
$a_lang['afunc_88'] = "<b>Url zu den Smilies</b>";
$a_lang['afunc_89'] = "<b>Url zu den Grafiken</b>";
$a_lang['afunc_90'] = "<b>Url zu den Avataren</b>";
$a_lang['afunc_91'] = "<b>Url zu den Kategorie-Grafiken</b><br><span class=\"smalltext\">Ordner unterhalb des Engine-Root Verzeichnisses; Komplette Url inkl. http://</span>";
$a_lang['afunc_92'] = "<b>Template Name hinzufügen?</b><br><span class=\"smalltext\">zeigt den Dateinamen des Templates im Quelltext als HTML-Kommentar an</span>";
$a_lang['afunc_93'] = "Namen, Email-Adresse und Seitenbreite";
$a_lang['afunc_94'] = "<b>Name Deiner News-Engine</b>";
$a_lang['afunc_95'] = "<b>Mail-Adresse des Admins:</b><br><span class=\"smalltext\">Wird allgemein bei den versendeten Mails angezeigt</font>";
$a_lang['afunc_96'] = "<b>Hauptbreite aller Seiten:</b><br><span class=\"smalltext\">Kann absolut oder relativ angegeben werden</span>";
$a_lang['afunc_97'] = "Kommentar-Setup";
$a_lang['afunc_98'] = "<b>Mailbenachrichtigung bei neuen Kommentaren?</b>";
$a_lang['afunc_99'] = "<b>Dürfen Gäste Kommentare posten?</b>";
$a_lang['afunc_100'] = "Gastposting nicht erlaubt";
$a_lang['afunc_101'] = "erlaubt, nur mit Namen Besucher";
$a_lang['afunc_102'] = "erlaubt, Name kann frei gewählt werden";
$a_lang['afunc_103'] = "<b>Kommentare vor Veröffentlichung bestätigen?</b><br><span class=\"smalltext\">siehe Startseite AdminCenter</span>";
$a_lang['afunc_104'] = "Einstellungen für Komprimierung";
$a_lang['afunc_105'] = "<b>GZIP aktivieren?</b><br><span class=\"smalltext\">Um dies zu aktivieren, muß die ZLIB Bibliothek mit PHP installiert sein (bitte evtl. beim Hoster nachfragen). Erreicht wird dadurch eine schnellere Übertratung, sofern der Client PC dies Unterstützt und HTTP1.1 kompatibel ist.</span>";
$a_lang['afunc_106'] = "<b>GZIP Komprimierungslevel</b><br><span class=\"smalltext\">Bestimmt den Grad der Komprimierung. 0=keine; 9=max.</span>";
$a_lang['afunc_107'] = "weitere Einstellungen";
$a_lang['afunc_108'] = "<b>Dürfen Gäste alle Details der registrierten Member sehen?</b>";
$a_lang['afunc_109'] = "<b>Registrierung aktivieren?</b>";
$a_lang['afunc_110'] = "<b>Login aktivieren?</b><br><span class=\"smalltext\">Ja macht nur mit aktivierter User-Registrierung sinn</span>";
$a_lang['afunc_111'] = "Wähle die Kategorie aus, in der der gewünschte News-Artikel ist";
$a_lang['afunc_112'] = "Folgende Kategorien sind verfügbar";
$a_lang['afunc_113'] = "bearbeiten oder löschen";
$a_lang['afunc_114'] = "Folgende News wurden gefunden";
$a_lang['afunc_115'] = "Kommentare nicht erlaubt";
$a_lang['afunc_116'] = "AKTIV";
$a_lang['afunc_117'] = "keine Links vorhanden";
$a_lang['afunc_118'] = "Links vorhanden";
$a_lang['afunc_119'] = "Veröffentlicht";
$a_lang['afunc_120'] = "News sind aktiv und steht zur Verfügung";
$a_lang['afunc_121'] = "News noch nicht veröffentlicht";
$a_lang['afunc_122'] = "muss noch freigegeben werden";
$a_lang['afunc_123'] = "";
$a_lang['afunc_124'] = "";
$a_lang['afunc_125'] = "vom";
$a_lang['afunc_126'] = "Bild der Kategorie verwenden";
$a_lang['afunc_127'] = "eigenes Bild für diesen Artikel verwenden";
$a_lang['afunc_128'] = "kein Bild für diesen Artikel verwenden";
$a_lang['afunc_129'] = "aktive Kommentare";
$a_lang['afunc_130'] = "News editieren";
$a_lang['afunc_131'] = "Links editieren";
$a_lang['afunc_132'] = "News löschen";
$a_lang['afunc_133'] = "Editiere die News mit der Überschrift";
$a_lang['afunc_134'] = "Thema, Kategorie, Newsbild";
$a_lang['afunc_135'] = "<b>Headline</b>";
$a_lang['afunc_136'] = "<b>Kategorie</b>";
$a_lang['afunc_137'] = "<b>Bild-Herkunft</b>";
$a_lang['afunc_138'] = "<b>Newsbild:</b><br><span class=\"smalltext\">Gebe hier den Dateinamen an, wenn ein eigenes Bild verwendet werden soll (nicht URL!) <a href=\"JavaScript:Uploadimage()\"><img src=\"images/upload.gif\" alt=\"Upload - Newsbild\" width=\"16\" height=\"16\" border=\"0\" align=\"middle\"></a></span>";
$a_lang['afunc_139'] = "Intro- und Haupttext";
$a_lang['afunc_140'] = "<b>Introtext</b><br><span class=\"smalltext\">erscheint auf der Startseite, BBCode erlaubt, keine HTML-Tags</span>";
$a_lang['afunc_141'] = "<b>Nachricht</b> <a href=\"JavaScript:Helpfile()\" class=\"smalltext\">[Hilfe]</a><br><span class=\"smalltext\">(wird unter 'mehr lesen' angezeigt)<br>neue Zeile einfach mit Enter-Taste einfügen";
$a_lang['afunc_142'] = "Kommentare, Veröffentlichen";
$a_lang['afunc_143'] = "<b>Newsbeitrag ver&ouml;ffentlichen</b>";
$a_lang['afunc_144'] = "<b>Kommentare erlauben</b>";
$a_lang['afunc_145'] = "Link Setup";
$a_lang['afunc_146'] = "<b>Links auf der Startseite anzeigen?</b>";
$a_lang['afunc_147'] = "Folgende News wurden gefunden";
$a_lang['afunc_148'] = "aktive Kommentare";
$a_lang['afunc_149'] = "Kommentare nicht erlaubt";
$a_lang['afunc_150'] = "keine Links vorhanden";
$a_lang['afunc_151'] = "Links vorhanden";
$a_lang['afunc_152'] = "Veröffentlicht";
$a_lang['afunc_153'] = "News sind aktiv und stehen zur Verfügung";
$a_lang['afunc_154'] = "muss noch freigegeben werden";
$a_lang['afunc_155'] = "News sind noch nicht veröffentlicht";
$a_lang['afunc_156'] = "News editieren";
$a_lang['afunc_157'] = "Links editieren";
$a_lang['afunc_158'] = "News löschen";
$a_lang['afunc_159'] = "Mit folgender Überschrift konnnten keine News gefunden werden";
$a_lang['afunc_160'] = "Email des Authors";
$a_lang['afunc_161'] = "weitere Optionen zum File";
$a_lang['afunc_162'] = "Das entsprechende File auf den Server laden";
$a_lang['afunc_163'] = "Vorschaubild zum File auf den Server laden";
$a_lang['afunc_164'] = "Folgende Downloads wurden gefunden";
$a_lang['afunc_165'] = "Es wurden keine Downloads zum angegebenen Suchbegriff gefunden. Suchbegriff";
$a_lang['afunc_166'] = "Wähle das gewünschte Mitglied aus der Liste";
$a_lang['afunc_167'] = "Folgende Mitglieder wurden gefunden";
$a_lang['afunc_168'] = "Username";
$a_lang['afunc_169'] = "<b>Email</b>";
$a_lang['afunc_170'] = "Gruppe";
$a_lang['afunc_171'] = "aktive<br>Kommentare";
$a_lang['afunc_172'] = "gesperrt?";
$a_lang['afunc_173'] = "Optionen";
$a_lang['afunc_174'] = "löschen unmöglich";
$a_lang['afunc_175'] = "löschen";
$a_lang['afunc_176'] = "editieren";
$a_lang['afunc_177'] = "Es wurden keine Mitglieder gefunden. Suchbegriff";
$a_lang['afunc_178'] = "User-Daten bearbeiten";
$a_lang['afunc_179'] = "Daten von";
$a_lang['afunc_180'] = "<b>Username</b>";
$a_lang['afunc_181'] = "<b>Registriert seit</b>";
$a_lang['afunc_182'] = "<b>letzter Besuch</b>";
$a_lang['afunc_183'] = "<b>Homepage</b>";
$a_lang['afunc_184'] = "<b>Gruppe</b>";
$a_lang['afunc_185'] = "Information zu den Gruppenrechten";
$a_lang['afunc_186'] = "<b>AvatarID</b>";
$a_lang['afunc_187'] = "<b>Darf die Email angezeigt werden?</b>";
$a_lang['afunc_188'] = "<b>Ist der User gesperrt?</b>";
$a_lang['afunc_189'] = "<b>Darf der User Files hochladen und anlegen?</b>";
$a_lang['afunc_190'] = "Userdaten ändern";
$a_lang['afunc_191'] = "neuen User anlegen";
$a_lang['afunc_192'] = "Bitte hier die neuen Daten eingeben";
$a_lang['afunc_193'] = "<b>Passwort</b>";
$a_lang['afunc_194'] = "Hier kannst Du die Avatardaten ändern.";
$a_lang['afunc_195'] = "Folgende Avatare sind auf diesem System verfügbar";
$a_lang['afunc_196'] = "ändern";
$a_lang['afunc_197'] = "löschen";
$a_lang['afunc_198'] = "Bitte gebe hier den neuen Namen für das gewählte Avatar ein";
$a_lang['afunc_199'] = "Avatardaten ändern";
$a_lang['afunc_200'] = "Daten ändern";
$a_lang['afunc_201'] = "eine Liste der verfügbaren Avatare zeigen";
$a_lang['afunc_202'] = "Hier kannst Du die Links zu";
$a_lang['afunc_203'] = "editieren";
$a_lang['afunc_204'] = "gefundene Links";
$a_lang['afunc_205'] = "Extern";
$a_lang['afunc_206'] = "Intern";
$a_lang['afunc_207'] = "Link";
$a_lang['afunc_208'] = "editieren";
$a_lang['afunc_209'] = "löschen";
$a_lang['afunc_210'] = "neuen Link hinzufügen";
$a_lang['afunc_211'] = "Link editieren";
$a_lang['afunc_212'] = "zu bearbeitender Link";
$a_lang['afunc_213'] = "<b>Link Beschreibung</b>";
$a_lang['afunc_214'] = "<b>Link URL</b>";
$a_lang['afunc_215'] = "<b>Link Target</b>";
$a_lang['afunc_216'] = "Link zu NewsID";
$a_lang['afunc_217'] = "neue Linkdaten eingeben";
$a_lang['afunc_218'] = "Link speichern";
$a_lang['afunc_219'] = "Neuer Newseintrag";
$a_lang['afunc_220'] = "Thema, Kategorie, Newsbild";
$a_lang['afunc_221'] = "<b>Thema</b>";
$a_lang['afunc_222'] = "<b>Kategorie</b>";
$a_lang['afunc_223'] = "<b>Bild-Herkunft</b>";
$a_lang['afunc_224'] = "Bild der Kategorie verwenden";
$a_lang['afunc_225'] = "Eigenes Bild für diese News verwenden";
$a_lang['afunc_226'] = "kein Bild verwenden";
$a_lang['afunc_227'] = "<b>Newsbild:</b><br><span class=\"smalltext\">Dateinamen , wenn ein eigenes Bild verwendet werden soll (nicht URL!)";
$a_lang['afunc_228'] = "Upload - Newsbild";
$a_lang['afunc_229'] = "Introtext und Haupttext";
$a_lang['afunc_230'] = "Click-Smilies";
$a_lang['afunc_231'] = "<b>Links zu den News</b>";
$a_lang['afunc_232'] = "Nach dem Absenden der News erhalten Sie die entsprechende Bestätigung und einen Link, unter dem Sie dann weitere Links zu diesem Newstext eingeben k&ouml;nnen.";
$a_lang['afunc_233'] = "News posten";
$a_lang['afunc_234'] = "Eingabe zurücksetzen";
$a_lang['afunc_235'] = "klein";
$a_lang['afunc_236'] = "mittel";
$a_lang['afunc_237'] = "groß";
$a_lang['afunc_238'] = "riesig";
$a_lang['afunc_239'] = "hoch";
$a_lang['afunc_240'] = "tief";
$a_lang['afunc_241'] = "hochgestellter Text";
$a_lang['afunc_242'] = "tiefgestellter Text";
$a_lang['afunc_243'] = "Hyperlink einfügen";
$a_lang['afunc_244'] = "Email Adresse einfügen";
$a_lang['afunc_245'] = "Quelltext einfügen";
$a_lang['afunc_246'] = "Linie";
$a_lang['afunc_247'] = "Liste";
$a_lang['afunc_248'] = "Liste einfügen";
$a_lang['afunc_249'] = "Zitat";
$a_lang['afunc_250'] = "Zitat einfügen";
$a_lang['afunc_251'] = "Image einfügen";
$a_lang['afunc_252'] = "aktuellen tag schließen";
$a_lang['afunc_253'] = "alle tags schließen";
$a_lang['afunc_254'] = "Benutze Buttons um Text zu formatieren";
$a_lang['afunc_255'] = "einfacher Modus";
$a_lang['afunc_256'] = "erweiterter Modus";
$a_lang['afunc_257'] = "<b>Maximale Zeichenlänge der Kommentare:</b>";
$a_lang['afunc_258'] = "";
$a_lang['afunc_259'] = "Löschen";
$a_lang['afunc_260'] = "Datum der News:<br><span class=\"smalltext\">im Format jjjj-mm-dd hh:mm:ss<br>leer lassen um aktuelles Datum zu verwenden</span>";
$a_lang['afunc_280'] = "<b>Aktivierungscode bei Registrierung per Mail versenden</b><br><span class=\"smalltext\">User muß per Email die Registrierung bestätigen, bevor der Account aktiviert ist</span>";
$a_lang['afunc_282'] = "Engine online/offline schalten";
$a_lang['afunc_283'] = "Status und Begründung";
$a_lang['afunc_284'] = "<b>Ist die Engine offline oder online</b>";
$a_lang['afunc_285'] = "Engine online";
$a_lang['afunc_286'] = "Engine offline";
$a_lang['afunc_287'] = "<b>Grund, für offline Status:</b><br><span class=\"smalltext\">Der Grund wird den Usern angezeigt. Die Engine wird nur offline geschaltet, wenn sowohl ein Grund eingegeben wurde als auch die entsprechende Einstellung getätigt wurde</span>";
$a_lang['afunc_288'] = "<b>Startdatum</b>";
$a_lang['afunc_289'] = "Datumseinstellungen<br><span class=\"smalltext\">Das Startdatum gibt an, ab wann ein Newsbeitrag ver&ouml;ffentlicht wird. Das Endedatum gibt an, wann der Newsbeitrag verf&auml;llt. Ist ein Verfallsdatum gesetzt, ist dieser Newsbeitrag nur noch im Archiv sichtbar. Format <b>jjjj-mm-dd hh:mm:ss</b></span>";
$a_lang['afunc_290'] = "<b>Endedatum</b>";
$a_lang['afunc_291'] = "Seite";
$a_lang['afunc_292'] = "bearbeiten";
$a_lang['afunc_293'] = "l&ouml;schen";
$a_lang['afunc_294'] = "Seite";
$a_lang['afunc_314'] = "<b>Standardgruppe bei Registrierung:</b><br><span class=\"smalltext\">Im Normalfall wird hier die ID 7 verwendet, bei Kombination mit dem WBB sollte die 3 verwendet werden, da dies der normale User des WBB ist. Eigene Anpassungen m&uuml;ssen zu&auml;tzlich ber&uuml;cksichtigt werden.</span>";
$a_lang['afunc_315'] = "Keine neuen Kommentare verf&uuml;gbar";
$a_lang['afunc_316'] = "Kein neuer Newsbeitrag verf&uuml;gbar";
$a_lang['afunc_318'] = "Neuen Benutzer anlegen";
$a_lang['afunc_319'] = "Bild hochladen/Browser";
$a_lang['afunc_320'] = "zur Kategorie";
$a_lang['post_news_js1'] = "Zu formatierenden Text eingeben";
$a_lang['post_news_js2'] = "Beschreibungstext für den Link eingeben (optional)";
$a_lang['post_news_js3'] = "Komplette URL für den Link eingeben";
$a_lang['post_news_js4'] = "Email-Adresse für den Link eingeben";
$a_lang['disclaimer'] = "Diesen Newsletter haben Sie erhalten, weil Ihre E-Mailadresse in unsere Mailingliste eingetragen wurde. 
Falls dies ohne Ihr Einverständnis erfolgt ist oder wenn Sie keine weiteren Newsletter erhalten möchten, 
klicken Sie bitte auf folgenden Link, um Ihre E-Mailadresse aus unserer Mailingliste auszutragen:";
$a_lang['afunc_321'] = "<b>RSS Newsfeed aktivieren</b><br><span class=\"smalltext\">News dieser Kategorie werden im RSS Newsfeed aktiviert um diese auf fremden Seiten einzusetzen.</span>";
$a_lang['afunc_322'] = "<b>RSS Newsfeed bereitstellen</b><br><span class=\"smalltext\">Dabei werden in der Datei rss.php die letzten 15 News aus allen Kategorien bei denen RSS aktiviert ist bereitgestellt. RSS ist ein Austauschformat das auf XML basiert, damit News unkompliziert auf anderen Websites eingebunden werden k&ouml;nnen. Weitere Informationen gibt es <a target=\"_blank\" href=\"http://www.drweb.de/suche/index.php?query_string=rss&option=start&limite=15&result_page=index.php\">hier</a></span>";
$a_lang['afunc_323'] = "<b>Newsletter aktivieren</b><br><span class=\"smalltext\">Auf der Startseite besteht die M&ouml;glichkeit, dass sich User in den Newsletter ein- und austragen k&ouml;nnen.</span>";
$a_lang['afunc_proceed'] = "Weiter";
$lang['php_fu_day_0'] = "Sonntag";
$lang['php_fu_day_1'] = "Montag";
$lang['php_fu_day_2'] = "Dienstag";
$lang['php_fu_day_3'] = "Mittwoch";
$lang['php_fu_day_4'] = "Donnerstag";
$lang['php_fu_day_5'] = "Freitag";
$lang['php_fu_day_6'] = "Samstag";
$lang['php_fu_month_1'] = "Januar";
$lang['php_fu_month_2'] = "Februar";
$lang['php_fu_month_3'] = "M&auml;rz";
$lang['php_fu_month_4'] = "April";
$lang['php_fu_month_5'] = "Mai";
$lang['php_fu_month_6'] = "Juni";
$lang['php_fu_month_7'] = "Juli";
$lang['php_fu_month_8'] = "August";
$lang['php_fu_month_9'] = "September";
$lang['php_fu_month_10'] = "Oktober";
$lang['php_fu_month_11'] = "November";
$lang['php_fu_month_12'] = "Dezember";
$lang['php_mailer_lang'] = "de";
$lang['php_mailer_error'] = "Fehler beim Mailversand: ";
?>
