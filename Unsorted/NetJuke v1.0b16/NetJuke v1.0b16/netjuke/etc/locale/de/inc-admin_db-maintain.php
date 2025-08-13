<?php

##################################################

# /admin/db-maintain.php

##################################################

define( "TFBCKP_CONFIRM", "Sind Sie sicher das Sie weitermachen wollen?" );
define( "TFBCKP_HEADER", "MUSIK DATENBANK WARTUNG" );

define( "TFBCKP_BACKUP_START", "Musik Datenbank Backup" );
define( "TFBCKP_BACKUP_HELP", "Erstellt ein Backup aller Musik-bezogener Daten (Titel, Interpret, etc.) in einer Textdatei. Diese Dateien können bei Bedarf mit der Importfunktion zu Wiederherstellung oder zur Benutzung mit anderen Programmen verwendet werden." );
define( "TFBCKP_BACKUP_DONE", "Backup-Datei anzeigen" );

define( "TFBCKP_MAINTAIN_START", "Musik Datenbank pflegen" );
define( "TFBCKP_MAINTAIN_HELP", "Werkzeug um die Musik Datenbank auf Einträge zu durchsuchen, deren Originaldatein gelöscht wurden.  Dateien mit einer vollständigen URL (http://, rtsp://. etc.) beliben davon unberührt. Falls ein Interpret, Album oder Genre ohne Titel ist wird es ebenfalls gelöscht." );
define( "TFBCKP_MAINTAIN_DONE", "Einträge mit fehlende Audiodateien wurden gefunden und aus der Datenbank gelöscht." );

define( "TFBCKP_DELETE_START", "Musik-bezogene Daten löschen" );
define( "TFBCKP_DELETE_HELP", "Löscht alle Musik-relevanten Daten (Titel, Interpret, etc.). Die Benutzer, deren Einstellungen und Sessiondaten bleiben erhalten. Spiellisten werden gelöscht." );
define( "TFBCKP_DELETE_DONE", "Alle Musik-relevanten Daten wurden gelöscht. Die Benutzereinträge sind weiterhin vorhanden." );

##################################################

?>