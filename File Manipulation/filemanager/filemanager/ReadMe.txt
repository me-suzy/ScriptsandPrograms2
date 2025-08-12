FileManager Version 1.0
***********************


Beschreibung:

FileManager ist eine in PHP und MySQL geschriebene Applikation um Dateien zum Download zur Verfügung zu stellen.
Es können Benutzer, Benutzergruppen und Kategorien erstellt und verwaltet werden. Der Datei-Upload erfolgt über
die FTP-Funktionen von PHP. Die Applikation ist komplett in Deutsch gehalten.


Voraussetzungen:

    1. PHP ab Version 5
    2. MySQL ab version 4 (1 Datenbank)
    3. Unix basierter Webserver mit FTP-Zugang


Installation:

    1. Laden Sie das Verzeichnis 'filemanager' auf Ihren Webserver.
    2. Geben Sie dem Verzeichnis 'filemanager/data/' Schreibrechte.
    3. Tragen Sie in die Datei 'filemanager/system/classes/config.class.php' Die MySQL-Verbindung ein.
    4. Rufen Sie in Ihrem Browser die Datei 'filemanager/install.php' auf um die Datenbank-Tabellen anzulegen 
       oder laden Sie die Datei 'filemanager/mysql.sql' in Ihre Datenbank.
    5. Rufen Sie in Ihrem Browser die Datei 'filemanager/index.php' auf und loggen Sie sich mit admin/admin
       ein um die Installation zu testen.
    6. Löschen Sie die Datei 'filemanager/install.php' und die Datei 'filemanager/mysql.sql'


Rechtliches:

Der FileManager darf ohne Einschränkungen für kommerzielle wie auch für nicht kommerzielle Zwecke verwendet und
weiterentwickelt werden. Der Author leistet lediglich E-Mail Support im für ihn akzeptablen Rahmen.
Alle verwendeten Grafiken wurden vom Author selber hergestellt und unterliegen keinem Copyright.


Mike Kaufmann
mike@cractix.ch
http://www.cractix.ch

2005-08-04