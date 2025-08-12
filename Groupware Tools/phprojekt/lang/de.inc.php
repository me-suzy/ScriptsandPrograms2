<?php

// de.inc.php, german Version
// by Albrecht Guenther <ag@phprojekt.com>
// $Id: de.inc.php,v 1.168.2.4 2005/09/12 08:07:34 fgraf Exp $

$chars = array("A","?","B","C","D","E","F","G","H","I","J","K","L","M","N","O","?","P","Q","R","S","T","U","?","V","W","X","Y","Z");
$name_month = array("", "Jan", "Feb", "Mrz", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez");
$l_text31a = array("Standard", "15 Min.", "30 Min.", " 1 Std.", " 2 Std.", " 4 Std.", " 1 Tag");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
$name_day2 = array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So");

$_lang['No Entries Found']= "Keine Einträge gefunden";
$_lang['No Todays Events']= "Heute keine Termine";
$_lang['No new forum postings']= "Keine neuen Forumsbeiträge";
$_lang['in category']= "in Kategorie";
$_lang['Filtered']= "Gefiltert";
$_lang['Sorted by']= "Sortiert nach";
$_lang['go'] = "los";
$_lang['back'] = "zur&uuml;ck";
$_lang['print'] = "Drucken";
$_lang['export'] = "Export";
$_lang['| (help)'] = "| (Hilfe/Vorgehensweise)";
$_lang['Are you sure?'] = "Sind Sie sicher?";
$_lang['items/page'] = "Elemente/Seite";
$_lang['records'] = "Elemente";
$_lang['previous page'] = "vorige Seite";
$_lang['next page'] = "n&auml;chste Seite";
$_lang['first page'] = "erste Seite";
$_lang['last page'] = "letzte Seite";
$_lang['Move']  = "verschieben";
$_lang['Copy'] = "kopieren";
$_lang['Delete'] = "L&ouml;schen";
$_lang['delete'] = "l&ouml;schen";
$_lang['Save'] = "Speichern";
$_lang['Directory'] = "Verzeichnis";
$_lang['Also Delete Contents'] = "auch Inhalte l&ouml;schen";
$_lang['Sum'] = "Summe";
$_lang['Filter'] = "Filter";
$_lang['Please fill in the following field'] = "Bitte f&uuml;llen Sie folgendes Feld aus";
$_lang['approve'] = "best&auml;tigen";
$_lang['undo'] = "widerrufen";
$_lang['Please select!'] = "Bitte ausw&auml;hlen!";
$_lang['New'] = "Neu";
$_lang['Select all'] = "Alle selektieren";
$_lang['Printable view'] = "Druckansicht";
$_lang['New record in module '] = "Neuer Eintrag in Modul ";
$_lang['Notify all group members'] = "Benachrichtigung an alle Gruppenmitglieder mailen";
$_lang['Yes'] = "Ja";
$_lang['No'] = "Nein";
$_lang['yes'] = "ja";
$_lang['no'] = "nein";
$_lang['Close window'] = "Fenster schlie&szlig;en";
$_lang['No Value'] = "Kein Wert";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "&Auml;ndern";
$_lang['today'] = "Heute";

// admin.php
$_lang['Password'] = "Passwort";
$_lang['Login'] = "Login";
$_lang['Administration section'] = "Administrations-Bereich";
$_lang['Your password'] = "Bitte geben Sie Ihr Passwort f&uuml;r diesen Bereich ein";
$_lang['Sorry you are not allowed to enter. '] = "Sie sind nicht f&uuml;r diesen Bereich autorisiert! ";
$_lang['Help'] = "Hilfe";
$_lang['User management'] = "User-Verwaltung";
$_lang['Create'] = "Anlegen";
$_lang['Projects'] = "Projekte";
$_lang['Resources'] = "Ressourcen";
$_lang['Resources management'] = "Ressourcen-Verwaltung";
$_lang['Bookmarks'] = "Bookmarks";
$_lang['for invalid links'] = "auf tote Links";
$_lang['Check'] = "Check";
$_lang['check'] = "prüfen";
$_lang['delete Bookmark'] = "Lesezeichen l&ouml;schen";
$_lang['(multiple select with the Ctrl-key)'] = "(Mehrfach-Auswahl mit Strg-Taste m&ouml;glich)";
$_lang['Forum'] = "Forum";
$_lang['forum'] = "Forum";
$_lang['Threads older than'] = "Forumsbeitr&auml;ge die vor ";
$_lang[' days '] = " Tagen angelegt wurden";
$_lang['Chat'] = "Chat";
$_lang['save script of current Chat'] = "Chat-Skript sichern";
$_lang['Chat script'] = "Chat Reste";
$_lang['New password'] = "Neues Passwort";
$_lang['(keep old password: leave empty)'] = "(altes Passwort beibehalten: leer lassen)";
$_lang['Default Group<br> (must be selected below as well)'] = "Default Gruppe<br />(muss unten ebenfalls selektiert sein)";
$_lang['Zip code'] = "Postleitzahl";
$_lang['Language'] = "Sprache";
$_lang['schedule readable to others'] = "Kalender f&uuml;r die Gruppe lesbar";
$_lang['schedule invisible to others'] = "Kalender f&uuml;r andere nicht sichtbar";
$_lang['schedule visible but not readable'] = "Kalender f&uuml;r die Gruppe sichtbar, nicht lesbar";
$_lang['schedule visible for group readable'] = "Kalender allgemein sichtbar, f&uuml;r die Gruppe lesbar";
$_lang['these fields have to be filled in.'] = "diese Felder sind unbedingt auszuf&uuml;llen.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Bitte f&uuml;llen Sie auf jeden Fall die Felder Nachname, Abk&uuml;rzung und Passwort aus.";
$_lang['This family name already exists! '] = "Dieser Nachname ist bereits vergeben! Bitte w&auml;hlen Sie einen anderen. ";
$_lang['This short name already exists!'] = "Diese Abk&uuml;rzung ist bereits vergeben! Bitte w&auml;hlen Sie eine andere. ";
$_lang['This login name already exists! Please chosse another one.'] = "Dieser Loginname ist bereits vergeben! Bitte w&auml;hlen Sie einen anderen. ";
$_lang['This password already exists!'] = "Dieses Passwort ist bereits vergeben! Bitte w&auml;hlen Sie ein anderes";
$_lang['This combination first name/family name already exists.'] = "Die Kombination Vorname/Nachname existiert bereits schon.";
$_lang['the user is now in the list.'] = "Der neue User wurde angelegt.";
$_lang['the data set is now modified.'] = "Die Daten des Users wurden ge&auml;ndert";
$_lang['Please choose a user'] = "Bitte einen User ausw&auml;hlen";
$_lang['is still listed in some projects. Please remove it.'] = "ist noch f&uuml;r Projekte vermerkt. Bitte &auml;ndern";
$_lang['All profiles are deleted'] = "Alle User Verteiler sind gel&ouml;scht";
$_lang['A Profile with the same name already exists'] = "Es existiert bereits ein Profil mit dem gleichen Namen";
$_lang['is taken out of all user profiles'] = "ist aus alle User Verteilern rausgenommen worden";
$_lang['All todo lists of the user are deleted'] = "Alle Aufgaben gel&ouml;scht";
$_lang['is taken out of these votes where he/she has not yet participated'] = "ist aus Umfragen, an denen er/sie noch nicht teilgenommen hat, rausgenommen worden";
$_lang['All events are deleted'] = "Alle Termine gel&ouml;scht";
$_lang['user file deleted'] = "User-Daten gel&ouml;scht";
$_lang['bank account deleted'] = "Bankkonten gel&ouml;scht";
$_lang['finished'] = "fertig";
$_lang['Please choose a project'] = "Bitte ein Projekt ausw&auml;hlen";
$_lang['The project is deleted'] = "Das Projekt wurde gel&ouml;scht";
$_lang['All links in events to this project are deleted'] = "Der Projektbezug wurde aus den Terminen entfernt";
$_lang['The duration of the project is incorrect.'] = "Der Projektzeitraum ist falsch gew&auml;hlt";
$_lang['The project is now in the list'] = "Das Projekt wurde angelegt";
$_lang['The project has been modified'] = "Das Projekt wurde ge&auml;ndert";
$_lang['Please choose a resource'] = "Bitte eine Ressource ausw&auml;hlen";
$_lang['The resource is deleted'] = "Die Ressource wurde gel&ouml;scht";
$_lang['All links in events to this resource are deleted'] = "Der Ressourcenbezug wurde aus den Terminen entfernt";
$_lang[' The resource is now in the list.'] = " Die Ressource wurde angelegt";
$_lang[' The resource has been modified.'] = " Die Ressource wurde ge&auml;ndert";
$_lang['The server sent an error message.'] = "Server bringt eine Fehlermeldung.";
$_lang['All Links are valid.'] = "Alle Links sind intakt";
$_lang['Please select at least one bookmark'] = "Bitte mindestens ein Lesezeichen ausw&auml;hlen";
$_lang['The bookmark is deleted'] = "Lesezeichen wurde gel&ouml;scht";
$_lang['threads older than x days are deleted.'] = "Forumsbeitr&auml;ge, die &auml;lter als x Tage alt waren, sind gel&ouml;scht worden";
$_lang['All chat scripts are removed'] = "S&auml;mtliche Chat-Reste sind entfernt worden";
$_lang['or'] = "oder";
$_lang['Timecard management'] = "Zeitkartenverwaltung";
$_lang['View'] = "Anzeigen";
$_lang['Choose group'] = "Gruppe w&auml;hlen";
$_lang['Group name'] = "Gruppenname";
$_lang['Short form'] = "Kurzform";
$_lang['Category'] = "Kategorie";
$_lang['Remark'] = "Bemerkung";
$_lang['Group management'] = "Gruppen Verwaltung";
$_lang['Please insert a name'] = "Bitte geben Sie einen Namen an";
$_lang['Name or short form already exists'] = "Name  Abk&uuml;rzung existieren schon";
$_lang['Automatic assign to group:'] = "Automatische Zuweisung zu Gruppe:";
$_lang['Automatic assign to user:'] = "Automatische Zuweisung zu user:";
$_lang['Help Desk Category Management'] = "Helpdesk Kategorie Verwaltung";
$_lang['Category deleted'] = "Kategorie gel&ouml;scht";
$_lang['The category has been created'] = " Die Kategorie wurde angelegt";
$_lang['The category has been modified'] = " Die Kategorie wurde ge&auml;ndert";
$_lang['Member of following groups'] = "Mitglied weiterer Gruppen";
$_lang['Primary group is not in group list'] = "Prim&auml;r-Gruppe ist nicht in der Gruppenliste enthalten";
$_lang['Login name'] = "Login Name";
$_lang['You cannot delete the default group'] = "Prim&auml;re Gruppe l&ouml;schen nicht m&ouml;glich";
$_lang['Delete group and merge contents with group'] = "Gruppe l&ouml;schen und Inhalte &uuml;bergeben in Gruppe";
$_lang['Please choose an element'] = "Bitte w&auml;hlen Sie ein Element aus";
$_lang['Group created'] = "Gruppe angelegt";
$_lang['File management'] = "File management";
$_lang['Orphan files'] = "Verwaiste Dateien";
$_lang['Deletion of super admin root not possible'] = "Super admin root l&ouml;schen nicht m&ouml;glich";
$_lang['ldap name'] = "ldap Name";
$_lang['mobile // mobile phone'] = "mobil"; // mobil phone
$_lang['Normal user'] = "Normaler Benutzer";
$_lang['User w/Chief Rights'] = "Benutzer mit Chef Rechten";
$_lang['Administrator'] = "Administrator";
$_lang['Logging'] = "Logging";
$_lang['Logout'] = "<span lang='en'>Logout</span>";
$_lang['posting (and all comments) with an ID'] = "Beitrag (und alle Kommentare) mit ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Die Rolle wurde gel&ouml;scht und Verkn&uuml;pfung von Usern zu dieser Rolle aufgehoben";
$_lang['The role has been created'] = "Die Rolle wurde angelegt";
$_lang['The role has been modified'] = "Die Rolle wurde ge&auml;ndert";
$_lang['Access rights'] = "Zugriffsrechte";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Chat beenden";

//contacts.php
$_lang['Contact Manager'] = "Kontakt Manager";
$_lang['New contact'] = "Neuer Kontakt";
$_lang['Group members'] = "Gruppenmitglieder";
$_lang['External contacts'] = "Externe Kontakte";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;&nbsp;Neu&nbsp;&nbsp;";
$_lang['Import'] = "Import";
$_lang['The new contact has been added'] = "Der neue Kontakt wurde angelegt";
$_lang['The date of the contact was modified'] = "Der Kontakt wurde ge&auml;ndert";
$_lang['The contact has been deleted'] = "Der Kontakt wurde gel&ouml;scht";
$_lang['Open to all'] = "Freigegeben";
$_lang['Picture'] = "Bild";
$_lang['Please select a vcard (*.vcf)'] = "Bitte w&auml;hlen Sie eine vcard (*.vcf) aus";
$_lang['create vcard'] = "vcard erzeugen";
$_lang['import address book'] = "Import Adressbuch";
$_lang['Please select a file (*.csv)'] = "Bitte w&auml;hlen Sie eine Datei (*.csv) aus";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "&Ouml;ffnen Sie ihr Outlook Adressbuch mit dem Men&uuml;punkt 'Datei'/'Exportieren'/'anderes Adressbuch'.<br>
Dann 'Textdatei' w&auml;hlen, in der folgenden Liste alle Felder selektieren und 'fertig stellen'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "&Ouml;ffnen Sie Outlook, Men&uuml;punkt 'Datei/Exportieren'/'Exportieren in eine Datei',<br>
dann 'kommagetrennte Werte(Win)' selektieren, im n&auml;chsten Men&uuml; 'Kontakte' selektieren,<br>
 einen Namen f&uuml;r die Exportdatei vergeben und 'fertig stellen'";
$_lang['Please choose an export file (*.csv)'] = "Bitte w&auml;hlen Sie die Export-Datei (*.csv) aus";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Bitte exportieren Sie Ihr Adre&szlig;buch als  csv-Datei und<br>
1) wenden Sie ein Import Muster an ODER <br>
2) passen Sie die Spalten der Tabelle in einer Tabellenkalkulation folgenderma&szlig;en an<br>
(&Uuml;berz&auml;hlige Spalten l&ouml;schen, nicht vorhandene extra anlegen):";
$_lang['Please insert at least the family name'] = "Bitte geben Sie wenigstens einen Nachnamen an";
$_lang['Record import failed because of wrong field count'] = "Datenzeile nicht importiert - falsche Feldanzahl";
$_lang['Import to approve'] = "Import zur Best&auml;tigung";
$_lang['Import list'] = "Importliste";
$_lang['The list has been imported.'] = "Die Liste wurde importiert.";
$_lang['The list has been rejected.'] = "Die Liste wurde verworfen.";
$_lang['Profiles'] = "Verteiler";
$_lang['Parent object'] = "Oberelement";
$_lang['Check for duplicates during import'] = "Auf Dubletten pr&uuml;fen";
$_lang['Fields to match'] = "Felder f&uuml;r &Uuml;bereinstimmung:";
$_lang['Action for duplicates'] = "Aktion bei Fund";
$_lang['Discard duplicates'] = "Dublette verwerfen";
$_lang['Dispose as child'] = "Als Unterobjekt anlegen";
$_lang['Store as profile'] = "Als Verteiler speichern";
$_lang['Apply import pattern'] = "Import Muster anwenden";
$_lang['Import pattern'] = "Import Muster";
$_lang['For modification or creation<br>upload an example csv file'] = "F&uuml;r Anlegen und &Auml;ndern<br>Beispieldatei(csv) hochladen";
$_lang['Skip field'] = "Feld auslassen";
$_lang['Field separator'] = "Feld Trennzeichen";
$_lang['Contact selector'] = "Kontakt Auswahl";
$_lang['Use doublet'] = "Dublette verwenden";
$_lang['Doublets'] = "Dubletten";

// filemanager.php
$_lang['Please select a file'] = "Bitte eine Datei angeben";
$_lang['A file with this name already exists!'] = "Eine Datei mit gleichem Namen existiert schon";
$_lang['Name'] = "Name";
$_lang['Comment'] = "Bemerkung";
$_lang['Date'] = "Datum";
$_lang['Upload'] = "Upload";
$_lang['Filename and path'] = "Dateiname & -pfad";
$_lang['Delete file'] = "Datei l&ouml;schen";
$_lang['Overwrite'] = "&Uuml;berschreiben";
$_lang['Access'] = "Zugriff";
$_lang['Me'] = "Ich";
$_lang['Group'] = "Gruppe";
$_lang['Some'] = "einige";
$_lang['As parent object'] = "wie Oberobjekt";
$_lang['All groups'] = "Alle Gruppen";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Sie k&ouml;nnen diese Datei nicht &uuml;berschreiben, da sie von Ihnen  von jemand anderen angelegt wurde";
$_lang['personal'] = "pers&ouml;nlich";
$_lang['Link'] = "Link";
$_lang['name and network path'] = "Netzwerkpfad und Dateiname";
$_lang['with new values'] = "mit neuen Werten";
$_lang['All files in this directory will be removed! Continue?'] = "Achtung! Es werden alle Dateien in dem Verzeichnis gel&ouml;scht! Weitermachen?";
$_lang['This name already exists'] = "Dieser Name ist bereits vergeben";
$_lang['Max. file size'] = "Max. Dateigr&ouml;sse";
$_lang['links to'] = "Verweis auf";
$_lang['objects'] = "Objekte";
$_lang['Action in same directory not possible'] = "Aktion im selben Verzeichnis nicht m&ouml;glich";
$_lang['Upload = replace file'] = "Upload = Datei ersetzen";
$_lang['Insert password for crypted file'] = "Passwort f&uuml;r verschl&uuml;sselte Datei angeben";
$_lang['Crypt upload file with password'] = "Upload-Datei mit Passwort verschl&uuml;sseln";
$_lang['Repeat'] = "Wiederholen";
$_lang['Passwords dont match!'] = "Passw&ouml;rter stimmen nicht &uuml;berein!";
$_lang['Download of the password protected file '] = "Download der passwortgesch&uuml;tzten Datei ";
$_lang['notify all users with access'] = "Alle berechtigten user benachrichtigen";
$_lang['Write access'] = "Schreibzugriff";
$_lang['Version'] = "Version";
$_lang['Version management'] = "Versionsmanagement";
$_lang['lock'] = "Sperren";
$_lang['unlock'] = "Entsperren";
$_lang['locked by'] = "gesperrt durch";
$_lang['Alternative Download'] = "Alternativer Download";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Typ ausw&auml;hlen";
$_lang['Create directory'] = "Verzeichnis anlegen";
$_lang['filesize (Byte)'] = "Dateigröße (Byte)";

// filter
$_lang['contains'] = 'enth&auml;lt';
$_lang['exact'] = 'exakt';
$_lang['starts with'] = 'beginnt mit';
$_lang['ends with'] = 'endet mit';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '&lt;';
$_lang['<='] = '&lt;=';
$_lang['does not contain'] = 'enth&auml;lt nicht';
$_lang['Please set (other) filters - too many hits!'] = "Andere Filter setzen - zu viele Treffer!";

$_lang['Edit filter'] = "Filter bearbeiten";
$_lang['Filter configuration'] = "Filterkonfiguration";
$_lang['Disable set filters'] = "Gesetzte Filter aufheben.";
$_lang['Load filter'] = "Gespeicherte Filter";
$_lang['Delete saved filter'] = "Gespeicherten Filter l&ouml;schen";
$_lang['Save currently set filters'] = "Aktuell gesetzte Filter speichern";
$_lang['Save as'] = "Speichern als";
$_lang['News'] = 'Nachrichten';

// module designer
$_lang['Module Designer'] = "Modul Designer";
$_lang['Module element'] = "Modul Element";
$_lang['Module'] = "Modul";
$_lang['Active'] = "Aktiv";
$_lang['Inactive'] = "Inaktiv";
$_lang['Activate'] = "Aktivieren";
$_lang['Deactivate'] = "Deaktivieren";
$_lang['Create new element'] = "Neues Element erzeugen";
$_lang['Modify element'] = "Element editieren";
$_lang['Field name in database'] = "Feldname in Datenbank";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Nur Buchstaben und Zahlen, keine Sonderzeichen und Leerstellen benutzen";
$_lang['Field name in form'] = "Feldname in Formular";
$_lang['(could be modified later)'] = "(kann nachtr&auml;glich ge&auml;ndert werden)";
$_lang['Single Text line'] = "Textzeile";
$_lang['Textarea'] = "Textbereich";
$_lang['Display'] = "Display";
$_lang['First insert'] = "Ersteingabe";
$_lang['Predefined selection'] = "Vorgegebene Auswahl";
$_lang['Select by db query'] = "Auswahl aus db-Abfrage";
$_lang['File'] = "Datei";

$_lang['Email Address'] = "Email Adresse";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Mehrfache Auswahl";
$_lang['Display value from db query'] = "Anzeige von db-Abfrage";
$_lang['Time'] = "Uhrzeit";
$_lang['Tooltip'] = "Tooltip";
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Erscheint als Tipp bei mouse-over &uuml;ber dem Feld: Zus&auml;tzliche Erl&auml;uterungen zum Feld oder Erkl&auml;rung der angewandten Regular Expression";
$_lang['Position'] = "Position";
$_lang['is current position, other free positions are:'] = "ist die momentane Position, freie Positionen:";
$_lang['Regular Expression:'] = "Regul&auml;rer Ausdruck";
$_lang['Please enter a regular expression to check the input on this field'] = "Bitte geben Sie einen regul&auml;ren Ausdruck an um den Feldinhalt zu pr&uuml;fen";
$_lang['Default value'] = "Vorgegebener Wert";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "wert der beim Erstellen eines neuen Datensatzes als vorbelegter Inhalt erscheint";
$_lang['Content for select Box'] = "Inhalt f&uuml;r die select Box";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Festen Satz von Werten mit | trennen (Wert1|WErt2|Wert3) oder sql Abfrage eingeben, je nach elementtyp";
$_lang['Position in list view'] = "Position in der Listenansicht";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Wenn das Feld in der Lsitenansicht erscheinen soll bitte einen Wert > 0 eingeben";
$_lang['Alternative list view'] = "Alternative Ansicht";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Der Inhalt erscheint im 'alt tag' des blauen buttons (mouse over Effekt) in der Listenansicht";
$_lang['Filter element'] = "Filter Element";
$_lang['Appears in the filter select box in the list view'] = "Erscheint in der select Box zur Filterung der angezeigten Datens&auml;tze";
$_lang['Element Type'] = "Element Typ";
$_lang['Select the type of this form element'] = "Geben sie den Typ des Formularelementes an";
$_lang['Check the content of the previous field!'] = "Pr&uuml;en Sie das soeben ausgef&uuml;llte Feld!";
$_lang['Span element over'] = "Element umfasst";
$_lang['columns'] = "Spalten";
$_lang['rows'] = "Zeilen";
$_lang['Telephone'] = "Telefon";
$_lang['History'] = "Historie";
$_lang['Field'] = "Feld";
$_lang['Old value'] = "Alter Wert";
$_lang['New value'] = "Neuer Wert";
$_lang['Author'] = "Autor";
$_lang['Show Date'] = "Datumsanzeige";
$_lang['Creation date'] = "Datum der Erstellung";
$_lang['Last modification date'] = "Datum der letzten &Auml;nderung";
$_lang['Email (at record cration)'] = "Emailangabe bei Erstellung";
$_lang['Contact (at record cration)'] = "Kontaktangabe bei Erstellung";
$_lang['Select user'] = "User Auswahl";
$_lang['Show user'] = "User Anzeige";

// forum.php
$_lang['Please give your thread a title'] = "Bitte geben sie einen Titel f&uuml;r Ihren Beitrag an";
$_lang['New Thread'] = "Neuer Beitrag";
$_lang['Title'] = "Titel";
$_lang['Text'] = "Text";
$_lang['Post'] = "abschicken";
$_lang['From'] = "von";
$_lang['open'] = "auf";
$_lang['closed'] = "zu";
$_lang['Notify me on comments'] = "Bei Antworten mich benachrichtigen";
$_lang['Answer to your posting in the forum'] = "Antwort auf Ihren Beitrag im Forum";
$_lang['You got an answer to your posting'] = "Sie haben eine Antwort auf Ihren Beitrag bekommen";
$_lang['New posting'] = "Neuer Beitrag";
$_lang['Create new forum'] = "Neues Forum anlegen";
$_lang['down'] ="nach unten";
$_lang['up']= "nach oben";
$_lang['Forums']= "Foren";
$_lang['Topics']="Themen";
$_lang['Threads']="Beitr&auml;ge";
$_lang['Latest Thread']="Letzter&nbsp;Beitrag";
$_lang['Overview forums']= "Foren-&Uuml;bersicht";
$_lang['Succeeding answers']= "Nachfolgende Antworten";
$_lang['Count']= "Anzahl";
$_lang['from']= "von";
$_lang['Path']= "Pfad";
$_lang['Thread title']= "Titel des Beitrags";
$_lang['Notification']= "Benachrichtigung";
$_lang['Delete forum']= "Forum l&ouml;schen";
$_lang['Delete posting']= "Forumsbeitrag l&ouml;schen";
$_lang['In this table you can find all forums listed']= "In dieser Tabelle finden Sie in einer &uuml;bersichtlichen Liste aller Foren.";
$_lang['In this table you can find all threads listed']= "In dieser Tabelle finden Sie in einer &uuml;bersichtlichen Liste aller Forumsbeitr&auml;ge.";

// index.php
$_lang['Last name'] = "Username";
$_lang['Short name'] = "Kurzname";
$_lang['Sorry you are not allowed to enter.'] = "Leider sind Sie nicht als Nutzer registriert";
$_lang['Please run index.php: '] = "Bitte hier starten: !";
$_lang['Reminder'] = "Wecker";
$_lang['Session time over, please login again'] = "Sitzungszeit &uuml;berschritten, bitte erneut anmelden";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Gelesene Elemente verbergen";
$_lang['&nbsp;Show read elements'] = "&nbsp;Gelesene Elemente anzeigen";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Archiv Elemente verbergen";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Archiv Elemente anzeigen";
$_lang['Tree view'] = "Baumansicht";
$_lang['flat view'] = "Listenansicht";
$_lang['New todo'] = "Neue Aufgabe";
$_lang['New note'] = "Neue Notiz";
$_lang['New document'] = "Neues Dokument";
$_lang['Set bookmark'] = "Link setzen";
$_lang['Move to archive'] = "Ins Archiv verschieben";
$_lang['Mark as read'] = "Als gelesen markieren";
$_lang['Export as csv file'] = "Export als csv Datei";
$_lang['Deselect all'] = "Alle deselektieren";
$_lang['selected elements'] = "selektierte Elemente";
$_lang['wider'] = "Breiter";
$_lang['narrower'] = "Schm&auml;ler";
$_lang['ascending'] = "Aufsteigend";
$_lang['descending'] = "Absteigend";
$_lang['Column'] = "Spalte";
$_lang['Sorting'] = "Sortierung";
$_lang['Save width'] = "Breite speichern";
$_lang['Width'] = "Breite";
$_lang['switch off html editor'] = "html editor ausschalten";
$_lang['switch on html editor'] = "html editor einschalten";
$_lang['hits were shown for'] = "Treffer werden angezeigt";
$_lang['there were no hits found.'] = "Es wurden leider keine Entsprechungen gefunden";
$_lang['Filename'] = "Dateiname";
$_lang['First Name'] = "Vorname";
$_lang['Family Name'] = "Nachname";
$_lang['Company'] = "Firma";
$_lang['Street'] = "Straße";
$_lang['City'] = "Stadt";
$_lang['Country'] = "Land";
$_lang['Please select the modules where the keyword will be searched'] = "W&auml;hlen Sie die Module aus, in denen gesucht werden soll";
$_lang['Enter your keyword(s)'] = "Geben Sie das Stichwort  die Stichw&ouml;rter ein.";
$_lang['Salutation'] = "Anrede";
$_lang['State'] = "Region";
$_lang['Add to link list'] = "Auf Linkliste setzen";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "Willkommen bei dem Setup von PHProjekt";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Bitte beachten Sie folgendes:<ul>
<li>Es mu&szlig; eine leere Datenbank vorhanden sein
<li>stellen Sie sicher da&szlig; der Webserver die Datei config.inc.php<br>in das Verzeichnis schreiben kann";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Falls Fehler w&auml;hrend der Installation auftreten, rufen Sie die <a href='help/faq_install.html' target=_blank>install faq</a>
 auf  besuchen Sie das <a href='http://www.PHProjekt.com/forum.html' target=_blank>Installationsforum</a></i>";
$_lang['Please fill in the fields below'] = "Zun&auml;chst geben Sie bitte die Daten Ihres Datenbankzuganges ein,<br>diese werden dann getestet";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Bei dieser Routine kann es in einigen F&auml;llen dazu kommen, da&szlig; das Skript<br>
sich nicht mehr meldet. Dann brechen Sie den Vorgang ab, schlie&szlig;en den Browser und wiederholen den Vorgang.)";
$_lang['Type of database'] = "Typ der Datenbank";
$_lang['Hostname'] = "Hostname";
$_lang['Username'] = "Nutzername";

$_lang['Name of the existing database'] = "Name der existierenden Datenbank";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php nicht gefunden! Wollen Sie wirklich ein update machen? Bitte INSTALL lesen ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php gefunden! Wollen Sie nicht lieber ein Update machen? Bitte INSTALL lesen ...";
$_lang['Please choose Installation,Update or Configure!'] = "Bitte 'Installation'  'Update' ausw&auml;hlen! zur&uuml;ck ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Ich bekomme keine Verbindung zur Datenbank ... <br> bitte schliessen Sie alle Fenster und rufen sie setup.php nochmals auf!";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Ups, da stimmt was nicht ... <br> Setzen Sie DBDATE auf 'Y4MD-'  lassen Sie eine &Auml;nderung der Environmentvariable zu (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Die Anbindung an die Datenbank scheint erfolgreich zu sein";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "W&auml;hlen Sie die zu installierenden Komponenten aus.<br> (Sie k&ouml;nnen diese auch sp&auml;ter in der config.inc.php deaktivieren)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Installieren = eine '1' eintragen, nicht installieren = 0";
$_lang['Group views'] = "Gruppenansichten im<br> Terminkalender";
$_lang['Todo lists'] = "Aufgaben";

$_lang['Voting system'] = "Umfragesystem";


$_lang['Contact manager'] = "Kontakt Manager";
$_lang['Name of userdefined field'] = "Name des userdefinierten Feldes";
$_lang['Userdefined'] = "Userdefiniert";
$_lang['Profiles for contacts'] = "Verteiler f&uuml;r Kontakte";
$_lang['Mail'] = "Mail";
$_lang['send mail'] = " senden";
$_lang['send html mail'] = "HTML Nachricht verschicken";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = "<br> &nbsp; &nbsp; mail reader (senden &amp; lesen)";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' Terminliste im eigenen Fenster,<br>
&nbsp; &nbsp; '2' f&uuml;r zus&auml;tzliche Alarmbox.";
$_lang['Alarm'] = "Reminder/Alarm";
$_lang['max. minutes before the event'] = "max. Minuten vor dem Termin";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Erinnert via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Projekte anlegen, Status &auml;ndern,<br>
&nbsp; &nbsp; '2'= Projektzuweisung nur mit Zeitkarteneintrag<br>
&nbsp; &nbsp; '3'= Projektzuweisung auch ohne Zeitkarteneintrag<br>&nbsp; &nbsp; (Auswahl '2' und '3' nur mit Modul Zeitkarte!)";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Verzeichnis angeben, in dem Dateien abgelegt werden sollen<br>(keine Dateimanagement: Feld leeren)";
$_lang['absolute path to this directory (no files = empty field)'] = "absoluten Pfad zum diesem Verzeichnis angeben";
$_lang['Time card'] = "Zeiterfassung";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' Zeiterfassung aktivieren,<br>
&nbsp; &nbsp; '2' Kopie an chef bei nachtr&auml;gliche Eintragungen";
$_lang['Notes'] = "Notizen";
$_lang['Password change'] = "Passwort&auml;nderung";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Neue Passw&ouml;rter durch user selbst - 0:nein, 1:Zufallspasswort, 2: eigene Eingabe";
$_lang['Encrypt passwords'] = "Passw&ouml;rter verschl&uuml;sseln";
$_lang['Login via '] = "Anmeldung &uuml;ber ";
$_lang['Extra page for login via SSL'] = "Bei Anmeldung &uuml;ber SSL";
$_lang['Groups'] = "Gruppen";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "User und Module arbeiten gruppenweise<br>
&nbsp; &nbsp; (empfohlen f&uuml;r userzahlen > 40)";
$_lang['User and module functions are assigned to groups'] = "User und Module arbeiten gruppenweise";
$_lang['Help desk'] = "Help desk";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Manager f&uuml;r Anfragen an den Support";
$_lang['RT Option: Customer can set a due date'] = "Helpdesk: Kunde kann eine Frist setzen";
$_lang['RT Option: Customer Authentification'] = "Helpdesk: Support Berechtigung";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: offen, email-Adresse reicht<br>1: Registrierter Kontakt mu&szlig; Nachnamen eingeben<br>2. wie 1. mit Email Adresse";
$_lang['RT Option: Assigning request'] = "Helpdesk: Anfragezuweisung";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: durch alle, 1: nur durch chef.";
$_lang['Email Address of the support'] = "Email Adresse des Supports";
$_lang['Scramble filenames'] = "Dateinamen verschl&uuml;sseln";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "legt Dateien auf dem Server mit zuf&auml;lligen Namen ab,<br>
(und weist richtigen Namen vor download wieder zu)";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Nachname, 1: Kurzname, 2: Loginname";
$_lang['Prefix for table names in db'] = "Prefix f&uuml;r Tabellennamen in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Die Datei config.inc.php kann nicht geschrieben werden!<br>
Das Installationsverzeichnis ben&ouml;tigt 'rwx' Rechte f&uuml;r den Webserver, alle anderen 'rx'.";
$_lang['Location of the database'] = "Standort der Datenbank";
$_lang['Type of database system'] = "Typ des Datenbanksystems";
$_lang['Username for the access'] = "Nutzername f&uuml;r Datenbankzugang";
$_lang['Password for the access'] = "Passwort f&uuml;r den Datenbankzugang";
$_lang['Name of the database'] = "Name der Datenbank";
$_lang['Prefix for database table names'] = "Pr&auml;fix f&uuml;r Tabellennamen in der Datenbank";
$_lang['First background color'] = "erste Hintergrundfarbe";
$_lang['Second background color'] = "zweite Hintergundfarbe";
$_lang['Third background color'] = "dritte Hintergrundfarbe";
$_lang['Color to mark rows'] = "Farbe um Zeilen zu markieren";
$_lang['Color to highlight rows'] = "Farbe um Zeilen hervorzuheben";
$_lang['Event color in the tables'] = "Terminfarbe in der<br> Tabellendarstellung";
$_lang['company icon yes = insert name of image'] = "Firmenlogo ja = Namen angeben";
$_lang['URL to the homepage of the company'] = "URL der Firmenhomepage";
$_lang['no = leave empty'] = "nein = leer lassen";
$_lang['First hour of the day:'] = "Erste Stunde am Tag: ";
$_lang['Last hour of the day:'] = "Letzte Stunde am Tag: ";
$_lang['An error ocurred while creating table: '] = "Fehler beim Anlegen der Tabelle: ";
$_lang['Table dateien (for file-handling) created'] = "Tabelle Dateien (f&uuml;r Dateimanagement) angelegt";
$_lang['File management no = leave empty'] = "Dateimanagement nein = leer lassen";
$_lang['yes = insert full path'] = "ja = absolutes Verzeichnis angeben";
$_lang['and the relative path to the PHProjekt directory'] = "zus&auml;tzlich auch relativer Pfad zum PHProjekt Verzeichnis";
$_lang['Table profile (for user-profiles) created'] = "Tabelle Profile (f&uuml;r Gruppenansichten) angelegt";
$_lang['User Profiles yes = 1, no = 0'] = "Gruppenansichten ja = 1, nein = 0";
$_lang['Table todo (for todo-lists) created'] = "Tabelle todo (f&uuml;r Aufgaben) angelegt";
$_lang['Todo-Lists yes = 1, no = 0'] = "Aufgaben ja = 1, nein = 0";
$_lang['Table forum (for discssions etc.) created'] = "Tabelle forum (f&uuml;r das Forum) angelegt";
$_lang['Forum yes = 1, no = 0'] = "Forum ja = 1, nein = 0";
$_lang['Table votum (for polls) created'] = "Tabelle votum (f&uuml;r Umfragen) angelegt";
$_lang['Voting system yes = 1, no = 0'] = "Umfrage ja = 1, nein = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Tabelle Lesezeichen (f&uuml;r die Bookmarks) angelegt";
$_lang['Bookmarks yes = 1, no = 0'] = "Lesezeichen ja = 1, nein = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Tabelle Ressourcen angelegt";
$_lang['Resources yes = 1, no = 0'] = "Ressourcen ja = 1, nein = 0";
$_lang['Table projekte (for project management) created'] = "Tabelle Projekte (f&uuml;r das Projektmanagement) angelegt";
$_lang['Table contacts (for external contacts) created'] = "Tabelle contacts (f&uuml;r externe Kontakte) angelegt";
$_lang['Table notes (for notes) created'] = "Tabelle notes (f&uuml;r Notizen) angelegt";
$_lang['Table timecard (for time sheet system) created'] = "Tabelle timecard (f&uuml;r die Zeiterfassung) angelegt";
$_lang['Table groups (for group management) created'] = "Tabelle groups (f&uuml;r die Gruppen) angelegt";
$_lang['Table timeproj (assigning work time to projects) created'] = "Tabelle timeproj (f&uuml;r Buchung der Arbeitszeiten auf Projekte) ist angelegt";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Tabellen rts und rts_cat (f&uuml;r den help desk) angelegt";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Tabellen mail_account, mail_attach, mail_client und mail_rules (f&uuml;r den mail reader) angelegt";
$_lang['Table logs (for user login/-out tracking) created'] = "Tabelle logs angelegt";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tabellen contacts_profiles und contacts_prof_rel angelegt";
$_lang['Project management yes = 1, no = 0'] = "Projektmanagement ja = 1, nein = 0";
$_lang['additionally assign resources to events'] = "zus&auml;tzlich Terminzuweisung";
$_lang['Address book  = 1, nein = 0'] = "Adressbuch ja = 1, nein = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Mail nein = 0, nur senden = 1, senden und empfangen = 2";
$_lang['Chat yes = 1, no = 0'] = "Chat ja = 1, nein = 0";
$_lang['Name format in chat list'] = "Format der Namensliste im chat";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: Nachname, 1: Vorname,2: Vorname, Nachname,<br> &nbsp; &nbsp; 3: Nachname, Vorname";
$_lang['Timestamp for chat messages'] = "Zeitstempel f&uuml;r chat Eintr&auml;ge";
$_lang['users (for authentification and address management)'] = "Tabelle user (f&uuml;r die Nutzer von PHProjekt) angelegt";
$_lang['Table termine (for events) created'] = "Tabelle termine (f&uuml;r den Gruppenkalender) angelegt";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Folgende user sind in der Tabelle user angelegt worden:<br>
'root' - Nutzer mit Administrationsrechten<br>
'test' - Chef-Nutzer mit eingeschr&auml;nkten Rechten";
$_lang['The group default has been created'] = "Die Gruppe 'default' wurde angelegt";
$_lang['Please do not change anything below this line!'] = "Bitte ab hier nichts mehr editieren";
$_lang['Database error'] = "Datenbank-Fehler";
$_lang['Finished'] = "Geschafft";
$_lang['There were errors, please have a look at the messages above'] = "Es traten Fehler auf. Bitte kontrollieren Sie die obige Liste";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Die Tabellen sind installiert und <br>
die Konfigurationsdatei config.inc.php neu editiert<br>
Am besten, Sie sichern
jetzt diese Datei.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "Das Passwort des Administrators 'root' ist auf 'root' eingestellt. Bitte &auml;ndern Sie dieses Passwort:";
$_lang['Please define here a password for the administrator "root":'] = "Bitte definieren Sie hier ein Passwort für den Administrator 'root':";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "Der user 'test' ist Mitglied der Gruppe 'default'.<br>
Erstellen Sie nun neue Gruppen und legen Sie dort neue user an.";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Die Applikation starten Sie &uuml;ber den die Datei <b>index.php</b> im Hauptverzeichnis.<br>
Testen Sie Ihre Installation, vor allem die Module 'Mail' und 'Dateien'";
$_lang['Alarm x minutes before the event'] = "Alarm x Minuten vor dem Termin";
$_lang['Additional Alarmbox'] = "zus&auml;tzlich Alarmbox";
$_lang['Mail to the chief'] = "mail an den chef";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Kommen/Gehen z&auml;hlt als: 1: Pause - 0: Arbeitszeit";
$_lang['Passwords will now be encrypted ...'] = "Passw&ouml;rter werden jetzt verschl&uuml;sselt ...";
$_lang['Filenames will now be crypted ...'] = "Dateinamen werden jetzt verschl&uuml;sselt ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Wollen Sie vor dem Update nicht noch eine Datenbanksicherung durchf&uuml;hren?
(Und mit der config.inc.php packen) ...<br> Ich warte gerne!";
$_lang['Next'] = "Weiter";
$_lang['Notification on new event in others calendar'] = "Benachrichtigung bei Fremdeintragung in den Kalender";
$_lang['Path to sendfax'] = "Pfad zu sendfax";
$_lang['no fax option: leave blank'] = "kein Fax: leer lassen";
$_lang['Please read the FAQ about the installation with postgres'] = "Bitte FAQ zu postgres beachten";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "L&auml;nge der Kurznamen<br> (Buchstabenanzahl 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "<li>wenn Sie PHProjekt manuell installieren wollen, soe finden Sie
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>hier</a> einen mysql dump und eine 'default' config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "Der Server braucht Schreibrechte f&uuml;r die Verzeichnisse";
$_lang['Header groupviews'] = "&Uuml;berschrift Gruppenansichten";
$_lang['name, F.'] = "Nachname, V.";
$_lang['shortname'] = "Kurzname";
$_lang['loginname'] = "Loginname";
$_lang['Please create the file directory'] = "Bitte erstellen Sie das Verzeichnis";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "Postings bei Start 1: ge&ouml;ffnet, 0: geschlossen";
$_lang['Currency symbol'] = "W&auml;hrungssymbol";
$_lang['current'] = "Aktuell";
$_lang['Default size of form elements'] = "Default Breite von Elementen";
$_lang['use LDAP'] = "LDAP nutzen";
$_lang['Allow parallel events'] = "Parallele Termine zulassen";
$_lang['Timezone difference [h] Server - user'] = "Zeitzonen Unterschied [h] Server - user";
$_lang['Timezone'] = "Zeitzone";
$_lang['max. hits displayed in search module'] = "max. Trefferanzeige bei Suche";
$_lang['Time limit for sessions'] = "Zeitlimit f&uuml;r Sessions";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: Normalbetrieb, 1: Debugging Modus";
$_lang['Enables mail notification on new elements'] = "Aktiviert mail Benachrichtigung bei neuen Elementen";
$_lang['Enables versioning for files'] = "Aktiviert Dateiversionierung";
$_lang['no link to contacts in other modules'] = "Kein Link zu Kontakten in anderen Modulen";
$_lang['Highlight list records with mouseover'] = "'Hover'-Effekt und Doppelklick bei Listeneintr&auml;gen";
$_lang['Track user login/logout'] = "User-Login/-out erfassen";
$_lang['Access for all groups'] = "Zugriff f&uuml;r alle Gruppen";
$_lang['Option to release objects in all groups'] = "Objekte in allen Gruppen freizugeben";
$_lang['Default access mode: private=0, group=1'] = "Vorbelegung Lese-Zugriff: privat=0, Gruppe=1";
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "F&uuml;gt '-f' dem mail() befehl hinzu, siehe php manual";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "Zeilenende in Mailtext; z.B. \\r\\n (nach RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "Zeilenende im Header; z.B. \\r\\n (nach RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Sendemodus f&uuml;r mails: 0: mail() Funktion nutzen; 1: Sockets nutzen";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "Adresse des SMTP Mail-Servers (ggf. localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "Name des lokalen Servers der w&auml;hrend der HELO Prozedur identifiziert wird";
$_lang['Authentication'] = "Authentifizierung";
$_lang['fill out in case of authentication via POP before SMTP'] = "Nur auszuf&uuml;llen wenn die Authentifizierung via POP vor SMTP l&auml;uft";
$_lang['real username for POP before SMTP'] = "Username f&uuml;r POP vor SMTP";
$_lang['password for this pop account'] = "Passwort f&uuml;r diesen pop Account";
$_lang['the POP server'] = "Der POP server";
$_lang['fill out in case of SMTP authentication'] = "Nur auszuf&uuml;llen wenn die Authentifizierung &uuml;ber SMTP l&auml;uft";
$_lang['real username for SMTP auth'] = "Username f&uuml;r SMTP Authentifizierung";
$_lang['password for this account'] = "Passwort f&uuml;r diesen account";
$_lang['SMTP account data (only needed in case of socket)'] = "SMTP Account Daten (nur bei socket)";
$_lang['No Authentication'] = "Keine Authentifizierung";
$_lang['with POP before SMTP'] = "mit POP vor SMTP";
$_lang['SMTP auth (via socket only!)'] = "SMTP auth (nur  &uuml;ber socket!)";
$_lang['Log history of records'] = "Datensatz&auml;nderungen loggen";
$_lang['Send'] = " Senden";
$_lang['Host-Path'] = "Host-Path";
$_lang['Installation directory'] = "Installation directory";
$_lang['0 Date assignment by chief, 1 Invitation System'] = "0 Zuweisung durch Chef,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Einladungssystem";
$_lang['0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System'] = "0 Zuweisung durch Chef,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Einladungssystem";
$_lang['Default write access mode: private=0, group=1'] = "DefaultSchreibmodus: privat=0, Gruppe=1";
$_lang['Select-Option accepted available = 1, not available = 0'] = "Select-Option 'angenommen' verf&uuml;gbar = 1, nicht verf&uuml;gbar = 0";
$_lang['absolute path to host, e.g. http://myhost/'] = "absoluter Pfad zum Host, z.B. http://myhost/";
$_lang['installation directory below host, e.g. myInstallation/of/phprojekt5/'] = "Installationsverzeichnis unterhalb Host, z.B. myInstallation/of/phprojekt5/";

// l.php
$_lang['Resource List'] = "Ressourcenliste";
$_lang['Event List'] = "Terminliste";
$_lang['Calendar Views'] = "Ansichten";

$_lang['Personnel'] = "Personal";

$_lang['Create new event'] = "Neuen Termin anlegen";
$_lang['Day'] = "Tag";

$_lang['Until'] = "bis";

$_lang['Note'] = "Notiz";
$_lang['Project'] = "Projekt";
$_lang['Res'] = "Ress";
$_lang['Once'] = "einmalig";
$_lang['Daily'] = "t&auml;glich";
$_lang['Weekly'] = "1x/Woche";
$_lang['Monthly'] = "1x/Monat";
$_lang['Yearly'] = "1x/Jahr";

$_lang['Create'] = "Create";

$_lang['Begin'] = "Beginn";
$_lang['Out of office'] = "Gehen";
$_lang['Back in office'] = "Kommen";
$_lang['End'] = "Ende";
$_lang['@work'] = "@work";
$_lang['We'] = "Wo";
$_lang['group events'] = "Gruppentermine";
$_lang['or profile'] = "oder Verteiler";
$_lang['Profile'] = "Verteiler";
$_lang['All Day Event'] = "Tagestermin";
$_lang['time-axis:'] = "Zeitachse:";
$_lang['vertical'] = "vertikal";
$_lang['horizontal'] = "horizontal";
$_lang['Horz. Narrow'] = "hor. dicht";
$_lang['-interval:'] = "Zeitinterval:";
$_lang['Self'] = "Mein Kalender";

$_lang['...write'] = "...eintragen";

$_lang['Calendar dates'] = "Kalender Daten";
$_lang['List'] = "Liste";
$_lang['Year'] = "Jahr";
$_lang['Month'] = "Monat";
$_lang['Week'] = "Woche";
$_lang['Substitution'] = "Vertretung";
$_lang['Substitution for'] = "Vertretung f&uuml;r";
$_lang['Extended&nbsp;selection'] = "Erweiterte&nbsp;Terminauswahl";
$_lang['New Date'] = "Neuer Termin";
$_lang['Date changed'] = "Termin ge&auml;ndert";
$_lang['Date deleted'] = "Termin gel&ouml;scht";

// links
$_lang['Database table'] = "DB Tabelle";
$_lang['Record set'] = "Datensatz";
$_lang['Resubmission at:'] = "Wiedervorlage am:";
$_lang['Set Links'] = "Links";
$_lang['From date'] = "Ab Datum";
$_lang['Call record set'] = "Datensatz aufrufen";

//login.php
$_lang['Please call login.php!'] = "Bitte rufen Sie index.php auf!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Leider gibt es &Uuml;berschneidungen!<br>Der kritische Termin: am ";
$_lang['Sorry, this resource is already occupied: '] = "Leider ist die Ressource schon vergeben: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Dieser Termin existiert leider nicht.<br> <br> Bitte pr&uuml;fen Sie ihre Eingabe. ";
$_lang['Please check your date and time format! '] = "Zahlenformat bitte &uuml;berpr&uuml;fen! ";
$_lang['Please check the date!'] = "Datum bitte &uuml;berpr&uuml;fen!";
$_lang['Please check the start time! '] = "Anfangszeit bitte &uuml;berpr&uuml;fen! ";
$_lang['Please check the end time! '] = "Endzeit bitte &uuml;berpr&uuml;fen! ";
$_lang['Please give a text or note!'] = "Bitte Text  Anmerkung eingeben!";
$_lang['Please check start and end time! '] = "Keine sinnvolle Zeitangabe! ";
$_lang['Please check the format of the end date! '] = "Zahlenformat des Enddatums bitte &uuml;berpr&uuml;fen! ";
$_lang['Please check the end date! '] = "Bitte End-Datum &uuml;berpr&uuml;fen! ";

$_lang['Resource'] = "Ressource";
$_lang['User'] = "Anwender";
$_lang['delete event'] = "Termin l&ouml;schen";
$_lang['Address book'] = "Adressbuch";
$_lang['Short Form'] = "Kurzform";
$_lang['Phone'] = "Fon";
$_lang['Fax'] = "Fax";
$_lang['Bookmark'] = "Lesezeichen";
$_lang['Description'] = "Bezeichnung";
$_lang['Entire List'] = "Gesamte Liste";

$_lang['New event'] = "Neuer Termin";
$_lang['Created by'] = "angelegt von";
$_lang['Red button -> delete a day event'] = "Tagestermine bitte mit dem roten Feld l&ouml;schen!";
$_lang['multiple events'] = "Serientermin";
$_lang['Delete multiple event completely'] = "Serientermin komplett löschen";
$_lang['Year view'] = "Jahresplan";
$_lang['calendar week'] = "Kalenderwoche";

//m2.php
$_lang['Create &amp; Delete Events'] = "Termin anlegen / l&ouml;schen";
$_lang['normal'] = "normal";
$_lang['private'] = "privat";
$_lang['public'] = "&ouml;ffentlich";
$_lang['Visibility'] = "Sichtbarkeit";

//mail module
$_lang['Please select at least one (valid) address.'] = "Bitte geben Sie mindestens eine (g&uuml;ltige) Adresse an.";
$_lang['Your mail has been sent successfully'] = "Ihre mail wurde erfolgreich versendet";
$_lang['Attachment'] = "Anlage";
$_lang['Send single mails'] = "Einzelne mails verschicken";
$_lang['Does not exist'] = "nicht vorhanden";
$_lang['Additional number'] = "zus&auml;tzliche Nummer";
$_lang['has been canceled'] = "wurde nicht gesendet";

$_lang['marked objects'] = "Markierte Objekte";
$_lang['Additional address'] = "An";
$_lang['in mails'] = "in Mails";
$_lang['Mail account'] = "Mail Konto";
$_lang['Body'] = "Text";
$_lang['Sender'] = "Sender";

$_lang['Receiver'] = "Empf&auml;nger";
$_lang['Reply'] = "antworten";
$_lang['answer all'] = "allen antworten";
$_lang['Forward'] = "Weiterleiten";
$_lang['Access error for mailbox'] = "Fehler bei Zugriff auf Mailkonto";
$_lang['Receive'] = "Empfangen";
$_lang['Write'] = "Erstellen";
$_lang['Accounts'] = "Konten";
$_lang['Rules'] = "Regeln";
$_lang['host name'] = "host name";
$_lang['Type'] = "Typ";
$_lang['misses'] = "fehlt";
$_lang['has been created'] = "ist angelegt";
$_lang['has been changed'] = "wurde ge&auml;ndert";
$_lang['is in field'] = "ist enthalten im Feld";
$_lang['and leave on server'] = "und auf dem Server lassen.";
$_lang['name of the rule'] = "Name der Regel";
$_lang['part of the word'] = "Wortteil";
$_lang['in'] = "in";
$_lang['sent mails'] = "Gesendete Mails";
$_lang['Send date'] = "Sendedatum";
$_lang['Received'] = "Empfangsdatum";
$_lang['to'] = "An";
$_lang['imcoming Mails'] = "eingehende Mails";
$_lang['sent Mails'] = "gesendeten Mails";
$_lang['Contact Profile'] = "Kontakt Verteiler";
$_lang['unread'] = "ungelesen";
$_lang['view mail list'] = "Mailliste ansehen";
$_lang['insert db field (only for contacts)'] = "DB-Feld einf&uuml;gen (nur f&uuml;r Kontakte)";
$_lang['Signature'] = "Signatur";
$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Einzelabfrage";
$_lang['Notice of receipt'] = "Empfangsbest&auml;tigung";
$_lang['Assign to project'] = "Zuweisung zu Projekt";
$_lang['Assign to contact'] = "Zuweisung zu Kontakt";
$_lang['Assign to contact according to address'] = "Ordne mails nach Adresse Kontakten zu";
$_lang['Include account for default receipt'] = "Bei allgemeinem Mail Empfang einbeziehen";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "Your token wurde bereits verwendet.<br>Wenn Sie nicht dies ausgeführt haben benachrichtigen Sie bitte Ihren Administrator";
$_lang['Your token has already been expired.'] = "Ihr token ist bereits abgelaufen";
$_lang['Unconfirmed Events'] = "Unbestätigte Termine";
$_lang['Visibility presetting when creating an event'] = "Voreinstellung der Sichtbarkeit beim Anlegen eines Termins";
$_lang['Subject'] = "Titel";
$_lang['Content'] = "Inhalt";
$_lang['answer all'] = "Antwort an alle";
$_lang['Create new message'] = "Neue Nachricht verfassen";
$_lang['Attachments'] = "Attachments";
$_lang['Recipients'] = "Empfänger";
$_lang['file away message'] = "Nachricht ablegen";
$_lang['Message from:'] = "Nachricht von:";

//notes.php
$_lang['Mail note to'] = "Notiz";
$_lang['added'] = "Erstellung";
$_lang['changed'] = "&Auml;nderung";

// o.php
$_lang['Calendar'] = "Kalender";
$_lang['Contacts'] = "Kontakte";


$_lang['Files'] = "Dateien";



$_lang['Options'] = "Optionen";
$_lang['Timecard'] = "Zeitkarte";

$_lang['Helpdesk'] = "Helpdesk";
$_lang['helpdesk'] = "Helpdesk";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Aufgaben";
$_lang['News'] = "News";
$_lang['Settings'] = "Einstellungen";
$_lang['Other'] = "Div.";
$_lang['Summary'] = "&Uuml;bersicht";

// options.php
$_lang['Description:'] = "Bezeichnung:";
$_lang['Comment:'] = "Bemerkung:";
$_lang['Insert a valid Internet address! '] = "Geben Sie eine Internetadresse an! ";
$_lang['Please specify a description!'] = "Bitte geben Sie eine Bezeichnung an!";
$_lang['This address already exists with a different description'] = "Dieselbe Adresse existiert schon unter der Bezeichnung";
$_lang[' already exists. '] = " ist als Bezeichnung leider schon vergeben. ";
$_lang['is taken to the bookmark list.'] = "wurde den Lesezeichen hinzugef&uuml;gt.";
$_lang[' is changed.'] = " wurde ge&auml;ndert.";
$_lang[' is deleted.'] = " wurde gel&ouml;scht.";
$_lang['Please specify a description! '] = "Bitte geben Sie eine Bezeichnung an! ";
$_lang['Please select at least one name! '] = "Bitte mind. 1 Namen selektieren! ";
$_lang[' is created as a profile.<br>'] = " wurde als Verteiler angelegt.";
$_lang['is changed.<br>'] = "wurde ge&auml;ndert.";
$_lang['The profile has been deleted.'] = "Der Verteiler wurde gel&ouml;scht.";
$_lang['Please specify the question for the poll! '] = "Bitte geben Sie eine Fragestellung an! ";
$_lang['You should give at least one answer! '] = "eine Wahlm&ouml;glichkeit m&uuml;ssen Sie schon angeben! ";
$_lang['Your call for votes is now active. '] = "Ihr Votum wird nun vorgelegt. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h4>Lesezeichen</h4>Hier k&ouml;nnen Sie neue Lesezeichen (Bookmarks) erstellen  bestehende &auml;ndern und l&ouml;schen:";
$_lang['Create'] = "Anlegen";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h4>Verteiler</h4>Hier k&ouml;nnen Sie neue Verteiler erstellen  bestehende &auml;ndern und l&ouml;schen:";
$_lang['<h2>Voting Formula</h2>'] = "<h4>Votum Formular</h4>";
$_lang['In this section you can create a call for votes.'] = "Hier k&ouml;nnen Sie eine Umfrage  ein Votum erstellen. Sie k&ouml;nnen bis zu drei Alternativen vorgeben.";
$_lang['Question:'] = "Frage:";
$_lang['just one <b>Alternative</b> or'] = "nur eine <b>Alternative</b> ";
$_lang['several to choose?'] = "mehrere w&auml;hlen?";

$_lang['Participants:'] = "Abstimmende Personen:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h4>Passwort &Auml;nderung</h4> Hier k&ouml;nnen Sie eine neues Zufallspasswort erhalten";
$_lang['Old Password'] = "Altes Passwort";
$_lang['Generate a new password'] = "Neues Passwort erzeugen";
$_lang['Save password'] = "Passwort abspeichern";
$_lang['Your new password has been stored'] = "Ihr neues Passwort ist gespeichert";
$_lang['Wrong password'] = "Falsches Passwort";
$_lang['Delete poll'] = "Votum l&ouml;schen";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Forumsbeitr&auml;ge l&ouml;schen</h4> Hier k&ouml;nnen Sie eigene Forumsbeitr&auml;ge l&ouml;schen<br>
Es erscheinen nur die Beitr&auml;ge ohne Antworten.";

$_lang['Old password'] = "Altes Passwort";
$_lang['New Password'] = "Neues Passwort";
$_lang['Retype new password'] = "Wiederholung";
$_lang['The new password must have 5 letters at least'] = "Mindestl&auml;nge des neuen Passwortes: 5";
$_lang['You didnt repeat the new password correctly'] = "Die beiden Eingaben des neuen Passwortes stimmen nicht &uuml;berein";

$_lang['Show bookings'] = "Buchungen";
$_lang['Valid characters'] = "M&ouml;gliche Zeichen";
$_lang['Suggestion'] = "Vorschlag";
$_lang['Put the word AND between several phrases'] = "Mehrere Suchbegriffe mit AND trennen"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Kalender-Schreibrecht";
$_lang['Write access for other users to your calendar'] = "Schreibberechtigung anderer User f&uuml;r Ihren Kalender.";
$_lang['User with chief status still have write access'] = "Der Schreibzugriff von Personen mit Chef-Status ist davon unabh&auml;ngig.";

// projects
$_lang['Project Listing'] = "Projektliste";
$_lang['Project Name'] = "Projektname";

$_lang['o_files'] = "Files";
$_lang['o_notes'] = "Notes";
$_lang['o_projects'] = "Projects";
$_lang['o_todo'] = "Todo";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Links";
$_lang['New profile'] = "Neuer Verteiler";
$_lang['In this section you can choose a new random generated password.'] = "In this section you can choose a new random generated password.";
$_lang['timescale'] = "Zeitskala";
$_lang['Manual Scaling'] = " Manuelle Skalierung";
$_lang['column view'] = "Spaltenanzeige";
$_lang['display format'] = "Darstellungsform";
$_lang['for chart only'] = "Nur für chart relevant:";
$_lang['scaling:'] = "Skalierung:";
$_lang['colours:'] = "Farben:";
$_lang['display project colours'] = "Projektfarben anzeigen";
$_lang['weekly'] = "w&ouml;chentlich";
$_lang['every 2 weeks'] = "alle 2 Wochen";
$_lang['every 3 weeks'] = "alle 3 Wochen";
$_lang['every 4 weeks'] = "alle 4 Wochen";
$_lang['monthly'] = "monatlich";
$_lang['annually'] = "j&auml;hrlich";
$_lang['automatic'] = "automatisch";
$_lang['New project'] = "Neues Projekt";
$_lang['Basis data'] = "Basisdaten";
$_lang['Categorization'] = "Kategorisierung";
$_lang['Real End'] = "Tats&auml;chliches Ende";
$_lang['Participants'] = "Teilnehmer";
$_lang['Status'] = "Status";
$_lang['Assignment'] ="Zuordnung";
$_lang['Last status change'] = "Status&auml;nderung";
$_lang['Leader'] = "Leiter";
$_lang['Statistics'] = "Statistik";
$_lang['My Statistic'] = "Meine Statistik";

$_lang['Persons'] = "Person(en)";
$_lang['Person'] = "Person";
$_lang['Hrs.'] = "Std.";
$_lang['Hours'] = "Stunden";
$_lang['Project summary'] = "Projektzusammenfassung";
$_lang[' Choose a combination Project/Person'] = " W&auml;hlen Sie die Projekte/Personen aus";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(Mehrfachauswahl mit gedr&uuml;ckter 'strg-Taste')";

$_lang['Begin:'] = "Beginn:";
$_lang['End:'] = "Ende:";
$_lang['All'] = "Alle";
$_lang['Work time booked on'] = "Buchungen auf";
$_lang['Sub-Project of'] = "Unterprojekt von";
$_lang['Aim'] = "Ziel";
$_lang['Contact'] = "Kontakt";
$_lang['Hourly rate'] = "Stundensatz";
$_lang['Calculated budget'] = "Budget";
$_lang['New Sub-Project'] = "Neues Unterprojekt";
$_lang['Booked To Date'] = "Bereits gebucht";
$_lang['Budget'] = "Budget";
$_lang['Detailed list'] = "Detaillierte Liste";
$_lang['Gantt'] = "Gantt";
$_lang['offered'] = "angeboten";
$_lang['ordered'] = "erteilt";
$_lang['Working'] = "in Bearbeitung";
$_lang['ended'] = "beendet";
$_lang['stopped'] = "gestoppt";
$_lang['Re-Opened'] = "wiederer&ouml;ffnet";
$_lang['waiting'] = "wartend";
$_lang['Only main projects'] = "Nur Hauptprojekte";
$_lang['Only this project'] = "Nur dieses Projekt";
$_lang['Begin > End'] = "Anfang > Ende";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-Format: yyyy-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "Beginn und Ende des Projektes m&uuml;ssen innerhalb des Zeitraums des Oberprojektes liegen. Werte bitte anpassen";
$_lang['Please choose at least one person'] = "Bitte w&auml;hlen Sie mind. eine Person aus";
$_lang['Please choose at least one project'] = "Bitte w&auml;hlen Sie mind. ein Projekt aus.";
$_lang['Dependency'] = "Abh&auml;ngigkeit";
$_lang['Previous'] = "davor";

$_lang['cannot start before the end of project'] = "kann nicht beginnen vor dem Ende von Projekt";
$_lang['cannot start before the start of project'] = "kann nicht beginnen vor dem Beginn von Projekt";
$_lang['cannot end before the start of project'] = "kann nicht enden vor dem Start von Projekt";
$_lang['cannot end before the end of project'] = "kann nicht enden vor dem Ende von Projekt";
$_lang['Warning, violation of dependency'] = "Achtung, Verletzung der Abh&auml;ngigkeit";
$_lang['Container'] = "Container";
$_lang['External project'] = "Externes Projekt";
$_lang['Automatic scaling'] = "Automatische Skalierung";
$_lang['Legend'] = "Legende";
$_lang['No value'] = "Kein Wert";
$_lang['Copy project branch'] = "Objektast kopieren";
$_lang['Copy this element<br> (and all elements below)'] = "Kopiere dieses Element (und alle Elemente darunter)";
$_lang['And put it below this element'] = "und setze es unterhalb dieses Elements";
$_lang['Edit timeframe of a project branch'] = "Zeitrahmen eines Projektastes &auml;ndern";

$_lang['of this element<br> (and all elements below)'] = "dieses Elements (und aller Elemente darunter)";
$_lang['by'] = "von";
$_lang['Probability'] = "Wahrscheinlichkeit";
$_lang['Please delete all subelements first'] = "Bitte zuerst alle Unterelemente l&ouml;schen";
$_lang['Assignment'] ="Zuweisung";
$_lang['display'] = "Anzeige";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Nach Datum sortiert";
$_lang['sort by'] = "Sortiert nach";
$_lang['Calculated budget has a wrong format'] = "Bitte das Budget-Format &uuml;berpr&uuml;fen";
$_lang['Hourly rate has a wrong format'] = "Bitte das Stundensatz-Format &uuml;berpr&uuml;fen";

// r.php
$_lang['please check the status!'] = "Statuszahl bitte &uuml;berpr&uuml;fen!";
$_lang['Todo List: '] = "Aufgaben";
$_lang['New Remark: '] = "Neue Aufgabe anlegen: ";
$_lang['Delete Remark '] = "Notiz l&ouml;schen ";
$_lang['Keyword Search'] = "Volltextsuche";
$_lang['Events'] = "bei Terminen";
$_lang['the forum'] = "im Forum";
$_lang['the files'] = "in Dateien";
$_lang['Addresses'] = "Adressen";
$_lang['Extended'] = "Erweitert";
$_lang['all modules'] = "alle Module";
$_lang['Bookmarks:'] = "Lesezeichen:";
$_lang['List'] = "Liste";
$_lang['Projects:'] = "Projekte:";

$_lang['Deadline'] = "Termin";

$_lang['Polls:'] = "Umfragen:";

$_lang['Poll created on the '] = "Umfrage angelegt am ";


// reminder.php
$_lang['Starts in'] = "beginnt in";
$_lang['minutes'] = "Minuten";
$_lang['No events yet today'] = "Heute noch keine Termine";
$_lang['New mail arrived'] = "Neue mails eingetroffen!";

//ress.php

$_lang['List of Resources'] =  "Ressourcenliste";
$_lang['Name of Resource'] = "Ressourcenname";
$_lang['Comments'] =  "Bemerkung";


// roles
$_lang['Roles'] = "Rollen";
$_lang['No access'] = "Kein Zugriff";
$_lang['Read access'] = "Lesezugriff";

$_lang['Role'] = "Rolle";

// helpdesk - rts
$_lang['Request'] = "Anfrage";

$_lang['pending requests'] = "Offene Anfragen";
$_lang['show queue'] = "Liste zeigen";
$_lang['Search the knowledge database'] = "Wissensdatenbank";
$_lang['Keyword'] = "Stichwort";
$_lang['show results'] = "Ergebnisse anzeigen";
$_lang['request form'] = "Anfrage stellen";
$_lang['Enter your keyword'] = "Kennwort eingeben";
$_lang['Enter your email'] = "Email Adressen angeben";
$_lang['Give your request a name'] = "Titel der Anfrage";
$_lang['Describe your request'] = "Beschreibung";

$_lang['Due date'] = "Frist";
$_lang['Days'] = "Tage";
$_lang['Sorry, you are not in the list'] = "Sie sind leider nicht registriert";
$_lang['Your request Nr. is'] = "Ihre Anfrage Nr. ist";
$_lang['Customer'] = "Kunde";


$_lang['Search'] = "Suche";
$_lang['at'] = "bei";
$_lang['all fields'] = "allen Feldern";


$_lang['Solution'] = "Antwort";
$_lang['AND'] = "UND";

$_lang['pending'] = "in Bearbeitung";
$_lang['stalled'] = "gestoppt";
$_lang['moved'] = "verschoben";
$_lang['solved'] = "gel&ouml;st";
$_lang['Submit'] = "Datum";
$_lang['Ass.'] = "bei";
$_lang['Pri.'] = "Pri.";
$_lang['access'] = "Zugriff";
$_lang['Assigned'] = "zugewiesen";

$_lang['update'] = "update";
$_lang['remark'] = "Bemerkung intern";
$_lang['solve'] = "Antwort";
$_lang['stall'] = "stoppen";
$_lang['cancel'] = "zur&uuml;ck";
$_lang['Move to request'] = "Verschieben zu Anfrage";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Bitte geben Sie diese Nummer an, wenn Sie Fragen dazu haben.
Wir werden Ihre Anfrage so schnell wie m&ouml;glich bearbeiten.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Ihre Anfrage ist aufgenommen worden.<br>
In K&uuml;rze werden Sie eine Email mit einer Best&auml;tigung erhalten.";
$_lang['n/a'] = "n/a";
$_lang['internal'] = "intern";

$_lang['has reassigned the following request'] = "hat folgende Anfrage neu zugewiesen";
$_lang['New request'] = "Neue Anfrage";
$_lang['Assign work time'] = "Arbeitszeit buchen";
$_lang['Assigned to:'] = "Zugewiesen zu:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Die Antwort wurde dem Kunden zugemailt und in die Datenbank uebernommen.";
$_lang['Answer to your request Nr.'] = "Antwort auf Ticket Nr.";
$_lang['Fetch new request by mail'] = "Neue Anfragen &uuml;ber mail abrufen";
$_lang['Your request was solved by'] = "Ihre Anfrage wurde beantwortet durch";

$_lang['Your solution was mailed to the customer and taken into the database'] = "Ihre Antwort wurde dem Kunden zugemailt und in die Datenbank uebernommen";
$_lang['Search term'] = "Suchbegriff";
$_lang['Search area'] = "Suchbereich";
$_lang['Extended search'] = "Erweiterte Suche";
$_lang['knowledge database'] = "Wissensdatenbank";
$_lang['Cancel'] = "Abbrechen";
$_lang['New ticket'] = "Neues Ticket";
$_lang['Ticket status'] ="Ticket-Status";
$_lang['Ticket status changed'] ="Ticket-Status wurde ver&auml;ndert";

// bitte diese Status anpassen -> Status hinzufügen/entfernen in helpdesk.php
$_lang['unconfirmed'] = 'unbestätigt';
$_lang['new'] = 'neu';
$_lang['assigned'] = 'zugewiesen';
$_lang['reopened'] = 'wieder eröffnet';
$_lang['resolved'] = 'gelöst';
$_lang['verified'] = 'verglichen';

// settings.php
$_lang['The settings have been modified'] = "Die Einstellungen wurden gespeichert.";
$_lang['Skin'] = "Skin";
$_lang['First module view on startup'] = "Startmodul";
$_lang['none'] = "Nichts";
$_lang['Check for mail'] = "Maileingang pr&uuml;fen";
$_lang['Additional alert box'] = "zus&auml;tzlich Alertbox";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Horizontale Aufl&ouml;sung<br>(z.B. 800, 1024)";
$_lang['Chat Entry'] = "Chat Eingabe";
$_lang['single line'] = "einzeilig";
$_lang['multi lines'] = "mehrzeilig";
$_lang['Chat Direction'] = "Chat Richtung";
$_lang['Newest messages on top'] = "Neueste Nachrichten oben";
$_lang['Newest messages at bottom'] = "Neueste Nachrichten unten";
$_lang['File Downloads'] = "Standard Download Modus";

$_lang['Inline'] = "Inline";
$_lang['Lock file'] = "Datei sperren";
$_lang['Unlock file'] = "Datei enstperren";
$_lang['New file here'] = "Neue Datei hier";
$_lang['New directory here'] = "Neues Verzeichnis hier";
$_lang['Position of form'] = "Formularposition";
$_lang['On a separate page'] = "Auf separater Seite";
$_lang['Below the list'] = "Unterhalb der Liste";
$_lang['Treeview mode on module startup'] = "Baumansicht bei Modulaufruf";
$_lang['Elements per page on module startup'] = "Elemente/Seite bei Modulaufruf";
$_lang['General Settings'] = "Allgemein";
$_lang['First view on module startup'] = "Startansicht";
$_lang['Left frame width [px]'] = "Breite linker Rahmen [px]";
$_lang['Timestep Daywiew [min]'] = "Zeitschritt Tagesansicht [min]";
$_lang['Timestep Weekwiew [min]'] = "Zeitschritt Wochenansicht [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px pro Zeichen f&uuml;r Termin-Text<br>(ungenau bei Proportionalschrift)";
$_lang['Text length of events will be cut'] = "Text auf Spaltenbreite k&uuml;rzen";
$_lang['Standard View'] = "Standard Ansicht";
$_lang['Standard View 1'] = "Standard Ansicht 1";
$_lang['Standard View 2'] = "Standard Ansicht 2";
$_lang['View refresh rate [min]'] = "Ansicht aktualisieren [min]";
$_lang['Own Schedule'] = "Mein Kalender";
$_lang['Group Schedule'] = "Gruppenkalender";
$_lang['Group - Create Event'] = "Gruppe - Termin anlegen";
$_lang['Group, only representation'] = "Gruppe, nur Vertretung";
$_lang['Holiday file'] = "Feiertagsdatei";

// summary
$_lang['Todays Events'] = "Termine heute";
$_lang['New files'] = "Neue Dateien";
$_lang['New file'] = "Neue Datei";
$_lang['New notes'] = "Neue Notizen";
$_lang['New Polls'] = "Neue Umfragen";
$_lang['Current projects'] = "Aktuelle Projekte";
$_lang['Help Desk Requests'] = "Helpdesk Anfragen";
$_lang['Current todos'] = "Aktuelle Aufgaben";
$_lang['New forum postings'] = "Neue Forumsbeitr&auml;ge";
$_lang['New Mails'] = "Neue Mails";

//timecard
$_lang['Theres an error in your time sheet: '] = "Fehler in der Arbeitszeittabelle: ";




$_lang['Consistency check'] = "Konsistenzpr&uuml;fung";
$_lang['Please enter the end afterwards at the'] = "Bitte Arbeitsende nachtragen am";
$_lang['insert'] = "eintragen";
$_lang['Enter records afterwards'] = "Zeitangaben nachtragen";
$_lang['Please fill in only emtpy records'] = "Bitte nur leere Eintr&auml;ge nachtragen";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Geben Sie einen Zeitraum an, alle Zeiteintr&auml;ge werden dem gew&auml;hltem Projekt zugewiesen";
$_lang['There is no record on this day'] = "Dieses Datum gibt es nicht";
$_lang['This field is not empty. Please ask the administrator'] = "F&uuml;r diese Zeitangabe besteht bereits ein anderer Eintrag an diesem Tag!";
$_lang['There is no open record with a begin time on this day!'] = "Datumsangaben inkonsistent! Bitte &uuml;berpr&uuml;fen.";
$_lang['Please close the open record on this day first!'] = "Bitte zuerst die Anfangszeit eingeben";
$_lang['Please check the given time'] = "Bitte Zeitangabe &uuml;berpr&uuml;fen";
$_lang['Assigning projects'] = "Zuweisung auf Projekte";
$_lang['Select a day'] = "Tagesauswahl";
$_lang['Copy to the boss'] = "Kopie an den Chef";
$_lang['Change in the timecard'] = "Zeitkartenmodifikation";
$_lang['Sum for'] = "Summe f&uuml;r";

$_lang['Unassigned time'] = "Nicht zugewiesene Zeit";
$_lang['delete record of this day'] = "Tageseintrag l&ouml;schen";
$_lang['Bookings'] = "Buchungen";

$_lang['insert additional working time'] = "Arbeitszeit nachtragen";
$_lang['Project assignment']= "Projektzuweisung";
$_lang['Working time stop watch']= "Arbeitszeiten Stoppuhr";
$_lang['stop watches']= "Stoppuhren";
$_lang['Project stop watch']= "Projekt-Stoppuhr";
$_lang['Overview my working time']= "&Uuml;bersicht meiner Arbeitszeiten";
$_lang['GO']= "GO";
$_lang['Day view']= "Tages-Ansicht";
$_lang['Project view']= "Projekt-Ansicht";
$_lang['Weekday']= "Wochentag";
$_lang['Start']= "Beginn";
$_lang['Net time']= "Nettozeit";
$_lang['Project bookings']= "Projektzeiten";
$_lang['save+close']= "speichern+schlie&szlig;en";
$_lang['Working times']= "Arbeitszeiten";
$_lang['Working times start']= "Arbeitszeit Start";
$_lang['Working times stop']= "Arbeitszeit Ende";
$_lang['Project booking start']= "Projektbuchung Start";
$_lang['Project booking stop']= "Projektbuchung Ende";
$_lang['choose day']= "Tag ausw&auml;hlen";
$_lang['choose month']= "Monat ausw&auml;hlen";
$_lang['1 day back']= "1 Tag zur&uuml;ck";
$_lang['1 day forward']= "1 Tag weiter";
$_lang['Sum working time']= "Gesamtarbeitszeit";
$_lang['Time: h / m']= "Zeit: h / m";
$_lang['activate project stop watch']= "Projekt-Stoppuhr aktivieren";
$_lang['activate']= "aktivieren";
$_lang['project choice']= "Projektauswahl";
$_lang['stop stop watch']= "Stoppuhr anhalten";
$_lang['still to allocate:']= "Noch zu vergeben:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "Sie haben keine Berechtigung Zeitkarteneintr&auml;ge zu l&ouml;schen. Bitte kontaktieren Sie Ihren Administrator";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "Leider k&ouml;nnen Sie an diesem Datum keine Eintr&auml;ge l&ouml;schen. Es sind seit dem %s Tage vergangen, Sie haben aber nur die Berechtigung Eintr&auml;ge die nicht &auml;lter als %s Tage sind zu ver&auml;ndern.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "Leider k&ouml;nnen Sie an diesem Datum keine Buchungen l&ouml;schen. Es sind seit dem %s Tage vergangen, Sie haben aber nur die Berechtigung Buchungen von Eintr&auml;gen die nicht &auml;lter als %s Tage sind zu bearbeiten hinzuzuf&uuml;gen.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "Leider k&ouml;nnen Sie an diesem Datum keine Eintr&auml;ge mehr vornhemen. Es sind seit dem %s Tage vergangen, Sie haben aber nur die Berechtigumg Eintr&auml;ge die nicht &auml;lter als %s Tage sind zu ver&auml;ndern.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "Leider k&ouml;nnen Sie an diesem Datum keine Buchungen mehr vornehmen. Es sind seit dem %s Tage vergangen, Sie haben aber nur die Berechtigung f&uuml;r Eintr&auml;ge die nicht &auml;lter als %s Tage sind Buchungen hinzuzuf&uuml;gen.";
$_lang['activate+close']="aktivieren+schlie&szlig;en";

// todos
$_lang['accepted'] = "angenommen";
$_lang['rejected'] = "abgelehnt";
$_lang['own'] = "eigene";
$_lang['progress'] = "Fortschritt";
$_lang['delegated to'] = "Delegiert an";
$_lang['Assigned from'] = "Zugewiesen von";
$_lang['done'] = "erledigt";
$_lang['Not yet assigned'] = "Noch nicht zugewiesen";
$_lang['Undertake'] = "&Uuml;bernehmen";
$_lang['New todo'] = "Neue Aufgabe";
$_lang['Notify recipient'] = "Empf&auml;nger benachrichtigen";

// votum.php
$_lang['results of the vote: '] = "Umfrageergebnisse";
$_lang['Poll Question: '] = "Votum mit der Frage: ";
$_lang['several answers possible'] = "(Mehrfachnennungen m&ouml;glich)";
$_lang['Alternative '] = "Variante ";
$_lang['no vote: '] = "keine Wertung: ";
$_lang['of'] = "von";
$_lang['participants have voted in this poll'] = "Befragten haben abgestimmt";
$_lang['Current Open Polls'] = "Aktuell offene Umfragen";
$_lang['Results of Polls'] = "Ergebnisliste aller Umfragen";
$_lang['New survey'] ="Neue Umfrage";
$_lang['Alternatives'] ="Alternativen";
$_lang['currently no open polls'] = "Momentan sind keine Umfragen offen";

// export_page.php
$_lang['export_timecard']       = "Zeitkarten exportieren";
$_lang['export_timecard_admin'] = "Zeitkarten exportieren";
$_lang['export_users']          = "User der Gruppe exportieren";
$_lang['export_contacts']       = "Kontakte exportieren";
$_lang['export_projects']       = "Projektdaten exportieren";
$_lang['export_bookmarks']      = "Lesezeichen exportieren";
$_lang['export_timeproj']       = "Zeit-zu-Projekt-Zuordnung exportieren";
$_lang['export_project_stat']   = "Projektstati exportieren";
$_lang['export_todo']           = "Aufgaben exportieren";
$_lang['export_notes']          = "Notizen exportieren";
$_lang['export_calendar']       = "(Alle) Termine exportieren";
$_lang['export_calendar_detail']= "Einen Termin exportieren";
$_lang['submit'] = "Absenden";
$_lang['Address'] = "Adresse";
$_lang['Next Project'] = "N&auml;chstes Projekt";
$_lang['Dependend projects'] = "Abh&auml;ngige Projekte";
$_lang['db_type'] = "Datenbanktyp";
$_lang['Log in, please'] = "Bitte loggen Sie sich ein";
$_lang['Recipient'] = "Empf&auml;nger";
$_lang['untreated'] = "offen";
$_lang['Select participants'] = "Teilnehmerauswahl";
$_lang['Participation'] = "Teilnahme";
$_lang['not yet decided'] = "nicht entschieden";
$_lang['accept'] = "zugestimmt";
$_lang['reject'] = "abgelehnt";
$_lang['Substitute for'] = "Vertretung f&uuml;r";
$_lang['Calendar user'] = "Kalenderbenutzer";
$_lang['Refresh'] = "Aktualisieren";
$_lang['Event'] = "Termin";
$_lang['Upload file size is too big'] = "Die hochgeladene Datei ist zu gross";
$_lang['Upload has been interrupted'] = "Der Datei-Upload wurde abgebrochen";
$_lang['view'] = "ansehen";
$_lang['found elements'] = "gefundene Elemente";
$_lang['chosen elements'] = "gew&auml;hlte Elemente";
$_lang['too many hits'] = "Die Treffermenge &uuml;bersteigt die Anzeigemenge";
$_lang['please extend filter'] = "Bitte erweitern Sie ihre Filter";
$_lang['Edit profile'] = "Verteiler bearbeiten";
$_lang['add profile'] = "Verteiler hinzuf&uuml;gen";
$_lang['Add profile'] = "Verteiler hinzuf&uuml;gen";
$_lang['Added profile'] = "Verteiler hinzugef&uuml;gt.";
$_lang['No profile found'] = "Kein Verteiler gefunden.";
$_lang['add project participants'] = "Projektteilnehmer hinzuf&uuml;gen";
$_lang['Added project participants'] = "Projektteilnehmer hinzugef&uuml;gt.";
$_lang['add group of participants'] = "Gruppenmitglieder hinzuf&uuml;gen";
$_lang['Added group of participants'] = "Gruppenmitglieder hinzugef&uuml;gt.";
$_lang['add user'] = "Benutzer hinzuf&uuml;gen";
$_lang['Added users'] = "Benutzer hinzugef&uuml;gt.";
$_lang['Selection'] = "Auswahl";
$_lang['selector'] = "Selektor";
$_lang['Send email notification'] = "E-Mail&nbsp;Benachrichtigung&nbsp;senden";
$_lang['Member selection'] = "Teilnehmerauswahl";
$_lang['Collision check'] = "Kollisionspr&uuml;fung";
$_lang['Collision'] = "Kollision";
$_lang['Users, who can represent me'] = "Benutzer, die mich vertreten d&uuml;rfen";
$_lang['Users, who can see my private events'] = "Benutzer, die meine privaten<br />Termine sehen d&uuml;rfen";
$_lang['Users, who can read my normal events'] = "Benutzer, die meine normalen<br />Termine lesen d&uuml;rfen";
$_lang['quickadd'] = "Schnelles Hinzuf&uuml;gen";
$_lang['set filter'] = "Filter setzen";
$_lang['Select date'] = "Datum ausw&auml;hlen";
$_lang['Next serial events'] = "N&auml;chste Serientermine";
$_lang['All day event'] = "Ganztagstermin";
$_lang['Event is canceled'] = "Termin&nbsp;ist&nbsp;abgesagt";
$_lang['Please enter a password!'] = "Bitte geben Sie ein Passwort ein!";
$_lang['You are not allowed to create an event!'] = "Sie können keinen Termin anlegen!";
$_lang['Event successfully created.'] = "Termin erfolgreich angelegt.";
$_lang['You are not allowed to edit this event!'] = "Sie können diesen Termin nicht ändern!";
$_lang['Event successfully updated.'] = "Termin erfolgreich geändert.";
$_lang['You are not allowed to remove this event!'] = "Sie können diesen Termin nicht löschen!";
$_lang['Event successfully removed.'] = "Termin erfolgreich gelöscht.";
$_lang['Please give a text!'] = "Bitte geben Sie einen Text ein!";
$_lang['Please check the event date!'] = "Bitte überprüfen Sie das Datum!";
$_lang['Please check your time format!'] = "Bitte überprüfen Sie das Zeitformat!";
$_lang['Please check start and end time!'] = "Bitte überprüfen Sie die Start- und Endzeit!";
$_lang['Please check the serial event date!'] = "Bitte überprüfen Sie das Seriendatum!";
$_lang['The serial event data has no result!'] = "Die Daten zum Serientermin ergeben keine Einträge!";
$_lang['Really delete this event?'] = "Diesen Termin wirklich löschen?";
$_lang['use'] = "Anwenden";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Handy";
$_lang['Further events'] = "Weitere Termine";
$_lang['Remove settings only'] = "Nur Einstellungen löschen";
$_lang['Settings removed.'] = "Einstellungen gelöscht.";
$_lang['User selection'] = "Benutzerauswahl";
$_lang['Release'] = "Freigabe";
$_lang['none'] = "keine";
$_lang['only read access to selection'] = "Auswahl hat nur Leserechte";
$_lang['read and write access to selection'] = "Auswahl hat Lese- und Schreibrechte";
$_lang['Available time'] = "Verfügbare Zeit";
$_lang['flat view'] = "Listenansicht";
$_lang['o_dateien'] = "Dateiablage";
$_lang['Location'] = "Location";
$_lang['date_received'] = "Empfangsdatum";
$_lang['subject'] = "Betreff";
$_lang['kat'] = "Kategorie";
$_lang['projekt'] = "Projekt";
$_lang['Location'] = "Ort";
$_lang['name'] = "Titel";
$_lang['contact'] = "Kontakt";
$_lang['div1'] = "Erstellung";
$_lang['div2'] = "Änderung";
$_lang['kategorie'] = "Kategorie";
$_lang['anfang'] = "Beginn";
$_lang['ende'] = "Ende";
$_lang['status'] = "Status";
$_lang['filename'] = "Titel";
$_lang['deadline'] = "Termin";
$_lang['ext'] = "an";
$_lang['priority'] = "Priorität";
$_lang['project'] = "Projekt";
$_lang['Accept'] = "Übernehmen";
$_lang['Please enter your user name here.'] = "Bitte geben Sie hier Ihren Benutzernamen ein.";
$_lang['Please enter your password here.'] = "Bitte geben Sie hier Ihr Passwort ein.";
$_lang['Click here to login.'] = "Hier klicken zum einloggen.";
$_lang['No New Polls'] = "Keine neuen Umfragen";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Gelesene Elemente verstecken";
$_lang['&nbsp;Show read elements'] = "&nbsp;Gelesene Elemente anzeigen";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Archiv Elemente verstecken";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Archiv Elemente anzeigen";

$_lang['go to content'] = "zum Inhalt";
$_lang['This link opens a popup window'] = "Neues Popup Fenster";
$_lang['This button opens a popup window'] = "Neues Popup Fenster";
$_lang['In this section you can choose a new random generated password.'] = "Hier können Sie ein Zufalls-Passwort erzeugen.";

$_lang['Filemanager'] = "Dateien";

// additional entries after sending the files to the translators
$_lang['My working time overview'] = "Übersicht meiner Arbeitszeiten";
$_lang['project could not be deleted, because it has sub-projects'] = "Das Projekt konnte nicht gelöscht werden, da es Unterprojekte hat.";

// entries for project conflicts
$_lang['You can choose between the following options:'] = "Sie haben nun folgende M&ouml;glichkeiten:";
$_lang['A conflict exists with the following parent project:'] = "Konflikte mit folgendem Oberprojekt:";
$_lang['A conflict exists with the following subproject:'] = "Konflikte mit folgendem Unterprojekt:";
$_lang['Discard changes'] = "Den Vorgang abbrechen ";
$_lang['Delay project start for '] = "Den Anfang des Projektzeitraums um";
$_lang['days '] = "Tage später legen ";
$_lang[' days'] = " Tage vorverlegen";
$_lang['move up the End '] = " das Ende um ";
$_lang['and'] = "und";
$_lang['move up the Beginning '] = "den Anfang um  ";
$_lang['Delay the end of the parent project '] = "Das Ende des Oberprojekts um";
$_lang['Delay the end of the subproject '] = "Das Ende des Unterprojekts um";
$_lang['move up the Beginning of all affected parent projects ']="Den Anfang aller betroffenen Oberprojekte um ";
$_lang['move up the Beginning of all affected subprojects ']="Den Anfang der Unterprojekte um ";
$_lang['Delay the end of all affected parent projects ']="Das Ende aller betroffenen Oberprojekte um ";
$_lang['Delay the end of all affected subprojects ']="Das Ende aller betroffenen Unterprojekte um ";

$_lang['Modules'] = "Module";
$_lang['Controls'] = "Steuerung";
$_lang['Addons'] = "Addons";
$_lang['Delete all filter'] = "Alle Filter löschen";
$_lang['Date format'] = "Datumsformat";

?>
