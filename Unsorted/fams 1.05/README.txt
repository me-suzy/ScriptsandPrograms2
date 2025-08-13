/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 09.05.01		Version: Readme 1.05		*/
/*	Geändert am: 28.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/


Im folgenden finden Sie eine Anleitung, wie Sie Dateien verwalten, das 
Programm in Ihre Homepage integrieren können, etc.

Index:
1. Kategorien verwalten
2. Dateien verwalten
3. Benutzer verwalten
4. Anpassen an das eigene Layout / Integration in die Homepage
5. Problembehebung
6. Produktupdates / Neuerungen in Vers. 1.05
7. Lizenz

-------------------

1. Kategorien verwalten

Wenn Sie sich auf der Administrationsebene eingeloggt haben, finden Sie links
im Menü den Punkt "Einstellungen". Dort gibt es die Unterpunkte:

- Übersicht
- Kategorie hinzufügen
- Kategorie editieren
- Kategorie löschen

Hier können Sie vorhandene Download-Kategorien verwalten. Die Punkte sind selbsterklärend!
Nur soviel: Es können weder doppelte, noch leere Namen verwendet werden.
Zahlen, etc. sind natürlich möglich.

ANMERKUNG: Wenn Sie eine Kategorie löschen sind die Files, die die entsprechende Kategorie
Nummer besitzen natürlich nicht mit gelöscht worden. Sie müssen allerdings den Files dann
neue Kategorien zuordnen um sie wieder verfügbar zu machen.


-------------------

2. Dateien verwalten

Wenn Sie sich auf der Administrationsebene eingeloggt haben, finden Sie links
im Menü den Punkt "Downloads". Dort gibt es die Unterpunkte (auf die Verwaltung
bezogen):

- Datei hinzufügen
- Datei editieren
- Datei löschen

Wenn Sie auf Datei hinzufügen klicken, erscheint ein neues Fenster mit den Eingabefeldern:

- File URL: Hier muss die komplette Adresse (mit http://www.ihre-domain.de/....) eingegeben werden.
- Mirror URL: Hier kann eine alternative Download-Adresse eingegeben werden (gleiche Bedingung 
  wie File URL). Dieses Feld kann leer gelassen werden.
- Screenshot 1 - 5: Hier kann man, sofern vorhanden, bis zu 5 Screenshots zu einem Download-File einbinden! 
  (gleiche Bedingung wie File URL). Dieses Feld kann leer gelassen werden.
- File Name: Hier müssen Sie einen Namen für den Download eingeben!
- Autor: Hier können Sie den Autor / die Firma des Files nennen!
- Autor eMail/URL: Das Feld für eine Kontakt Adresse, egal ob eMail oder Website, das Programm
  erkennt dies automatisch. Dieses Feld kann leer gelassen werden.
- File Größe: Die Größe der Datei. Fügen Sie entsprechend an die Zahl KB, MB, etc. an.
- Kategorie: Wählen Sie aus dem Dropdown die passende Kategorie aus, der das File zugeordnet 
  werden soll.
- Beschreibung: Hier ein paar Informationen über die anzubietende Datei.

Dann aus "Eintrag speichern". Und schon ist die neue Datei in der entsprechenden Kategorie zum
Downloaden verfügbar.
Haben Sie einen fehlerhafen Eintrag gespeichert, können Sie diesen editieren. Hierbei sind die
Bedingungen wie bei "Datei hinzufügen".
Alte Files, die Sie nicht mehr benötigen können Sie aus der Datenbank mit der entsprechenden Option
einfach löschen.


-------------------


3. Benutzerverwaltung

Seit Version 1.05 ist es nun möglich, mehreren Benutzern Zugang zu der Adminstrationsoberfläche zu gewähren. Dabei
wird zwischen 3 verschiedenen Benutzergruppen unterschieden:

Userlevel 1: Der Benutzer kann Files hinzufügen und editieren.

Userlevel 2: Der Benutzer kann Files hinzufügen, editieren und löschen. Weiterhin kann er das Kommentar System 
verwalten und dort entsprechend editieren oder löschen.

Userlevel 3: Der Administrator hat Zugriff auf alle Einstellungen. Er kann neue User und Kategorien einrichten, 
editieren und löschen sowie alle anderen Funktionen des System nutzen.

Es können hier beliebig viele neue Benutzer eingefügt werden, die dann mit den unterschiedlichen, oben genannten Rechten
Zugang zum System haben.



-------------------


4. Anpassen an das eigene Layout / Integration in Ihre Homepage

Natürlich können Sie das FAMS auch in Ihre bereits vorhandene Homepage eingliedern und nur die
Administrationsoberfläche von uns nutzen. Dazu gehen Sie wie folgt vor:

4.1 Anpassen der Navigation
Um die eingerichteten Kategorien in Ihrer Navigation anzeigen zu lassen, kopieren Sie einfach folgenden
Source Code in Ihre PHP Datei hinein:

/* Code für die Navigation */
<?php

require("./config.php");

$result = mysql_query("SELECT EID, cat_names FROM dl_categories ORDER BY cat_names");
while ($db=mysql_fetch_array($result))	
{
Echo "<A HREF=\"./cat.php?sec=main&show=".$db['EID']."\">".$db['cat_names']."</A><BR>";
}

?>
/* Ende Code für Navigation */

Hier werden alle eingerichteten Kategorien nach dem Alphabet geordnet und angezeigt.
Der Pfad zu der Datei 'config.php' muss ggf. noch angepasst werden!


4.2 Eingliedern der einzelnen Kategorien
Um die Links mit Inhalt zu füllen, sollten Sie am besten die Datei 'cat.php' aus demHauptverzeichnis nutzen und diese 
Ihrem Layout anpassen.
Sie ist in einer simplen Tabellenform aufgebaut und sollte für jeden zu modifizieren sein, allerdings würde ich 
dabei von Editoren wie Frontpage, etc. abraten.
Einfach die Datei im Notepad öffnen und dort bearbeiten.


--------------------


5. Problembehebung

Sie haben Probleme, bei der Installation? Dann sollten Sie sich als erstes die Systemanforderungen aus der Datei
"INSTALL.txt" durchlesen und sicherstellen, dass alle Anforderungen von Ihrem Server erfüllt werden. Wenn dies
der Fall ist, gehen Sie zum nächsten Schritt über:

Stellen Sie sicher, dass Sie alle Zugangsdaten richtig eingegeben haben! Im Zweifelsfall das FAMS noch einmal 
komplett löschen, neu uploaden und die Installation noch einmal durchführen. 

Weiterhin stellen Sie bitte unbedingt sicher, dass Sie die Datei

- config.php (Hauptverzeichnis)

VOR der Installation die korrekten Dateirechte (CHMOD) zugewiesen haben! Die Datei muss den Status '0755' oder 
'755' haben. Sollte es mit diesen Rechten nicht gehen, setzen Sie alle Dateien auf den Status '0777' oder '777'
und achten Sie nach der Installation darauf, der Datei wieder den Wert '0644' bzw. '644' zuzuweisen!

Weiterhin sollten Sie darauf achten (z.B. mit dem Tool phpMyAdmin prüfen), dass Ihr mySQL Datenbankserver online 
und erreichbar ist. Genauso natürlich Ihr WebServer.


--------------------

6. Produktupdates

Folgende Änderungen / Neuerungen gibt es in Version 1.05:

- Fehler in der statistik.php korrigiert
- Neue Suchengine
- Neue Fehlermeldungen (wenn in einer Kategorie kein File vorhanden ist, etc.)
- Es kann festgelegt werden, wie viele Download-Files pro Seite angezeigt werden sollen
- Alle Bilder transparent (Pfeile, etc.) / neue Standartfarben
- Dropdown beim Editieren und löschen der File Kategorien
- Zu jedem Download können Besucher Kommentare abgeben
- Kommentare können über Admin Panel editiert oder gelöscht werden
- Jedes Downloadfile kann auf einer Skala von 1 bis 10 bewertet werden
- Neuer Schutz der Admin Area (Cookie basierend - kein .htaccess mehr notwendig)
- Userverwaltung: beliebig viele User können verlwatet werden (hinzufügen / entfernen)
- 3 unterschiedliche Userlevel: Level 1 kann nur Files hinzufügen, editieren. Level 2 Kann 
  Files hinzufügen, editieren und löschen, sowie das Kommentar System verwalten. Level 3 hat
  Administratorrechte, d.h. er verwaltet alle Systemeinstellungen.


Weitere Neuerungen sind in Planung...



--------------------


7. Lizenz

WICHTIG: Wir übernehmen keinerlei Haftung für evtl. auftretende Schäden an Hard- oder Software, sowie
Dateiverlusten oder jeglichen anderen Schäden! Weitere Lizenzbedingungen finden Sie in der Datei "LIZENZ.txt".

Bei Rückfragen können Sie uns gerne unter der eMail info@bg-studios.de kontaktieren!

Bastian Grimm, [ BG-Studios ] 

--------------------
