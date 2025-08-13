<?php

##################################################

# /admin/prefs-edit.php

##################################################

define( "ADMPREF_ERR_DBCONN", "Kann mit diesen Einstellungen nicht mit der Datenbank verbinden." );

define( "ADMPREF_ERR_RADIOPLIST", "Das Verzeichniss oder die Datei, welche für die RADIO Spielliste benutzt wird, kann nicht gefunden werden oder ist nicht schreibbar.\\nBitte die Datei oder das Verzeichniss für den Webserver-User oder Alle zum schreiben freigeben." );

define( "ADMPREF_ERR_JUKEBOXPLIST", "Das Verzeichniss oder die Datei, welche für die JUKEBOX Spielliste benutzt wird, kann nicht gefunden werden oder ist nicht schreibbar.\\nBitte die Datei oder das Verzeichniss für den Webserver-User oder Alle zum schreiben freigeben." );
define( "ADMPREF_ERR_JUKEBOXPLAYERPATH", "Jukebox Player nicht gefunden. Bitte die Einstellungen überprüfen." );

define( "ADMPREF_FILEINFO_1", "Erstellt" );
define( "ADMPREF_FILEINFO_2", "Von" );
define( "ADMPREF_FILEINFO_3", "Speichern unter" );

define( "ADMPREF_DENIED_1", "Kann nicht die Einstellungsdatei schreiben. Rechte Problem." );

define( "ADMPREF_CHECKFORM_SECKEY", "Ihr neuer Sicherheitsschlüssel muss mindestens 30 Zeichen lang sein." );
define( "ADMPREF_CHECKFORM_DBNAME", "Bitte geben sie einen DB Namen ein" );
define( "ADMPREF_CHECKFORM_STREAM", "Bitte geben sie einen Streaming Server ein" );
define( "ADMPREF_CHECKFORM_BGCOLOR", "Bitte wählen sie eine Hintergrundfarbe aus ." );
define( "ADMPREF_CHECKFORM_FONTFACE", "Bitte wählen sie eine Schrift." );
define( "ADMPREF_CHECKFORM_FONTSIZE", "Bitte wählen sie eine Schriftgröße." );
define( "ADMPREF_CHECKFORM_TEXT", "Bitte wählen sie eine Textfarbe." );
define( "ADMPREF_CHECKFORM_LINK", "Bitte wählen sie eine Linkfarbe." );
define( "ADMPREF_CHECKFORM_ALINK", "Bitte wählen sie eine Farbe für aktiver Link." );
define( "ADMPREF_CHECKFORM_VLINK", "Bitte wählen sie eine Farbe für besuchter Link." );
define( "ADMPREF_CHECKFORM_BORDER", "Bitte wählen sie eine Farbe für den Tabellenrand." );
define( "ADMPREF_CHECKFORM_HEADER", "Bitte wählen sie eine Farbe für den Tabellenkopf." );
define( "ADMPREF_CHECKFORM_HEADERFC", "Bitte wählen sie eine Farbe für die Tabellenkopfschrift." );
define( "ADMPREF_CHECKFORM_CONTENT", "Bitte wählen sie eine Farbe für den Tabelleninhalt." );

define( "ADMPREF_HEADER_1", "SYSTEM EINSTELLUNGEN" );
define( "ADMPREF_HEADER_2", "INHALT EINSTELLUNGEN" );
define( "ADMPREF_HEADER_3", "INTERNET RADIO EINSTELLUNGEN" );
define( "ADMPREF_HEADER_4", "GLOBALES AUSSEHEN" );
define( "ADMPREF_HEADER_5", "JUKEBOX PREFERENCES (Server-Side Playback)" );

define( "ADMPREF_CAPTION", "Die Felder unten regeln die Standardfarben und -schriften, und die Option ob User eigene Einstellungen machen dürfen. Diese Einstellungen sehen die User im öffentlichen Bereich und in einem neu erstellten Benutzerkonto." );
define( "ADMPREF_PALETTE", "Benutzen Sie die Farbpalette um eine Standardumgebung festzulegen." );

define( "ADMPREF_FORMS_CAPT_ENABLED", "Aktiviert" );

define( "ADMPREF_FORMS_SAVETOFILE", "In Datei speichern" );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_1", "Um diese Daten automatisch in der Einstelluns-Datei zu speichern, muss die \\nDatei für den Webserver schreibbar sein. Um dies zu gewährleisten gibt es zwei \\nMöglichkeiten:\\n\\n- Die Datei kann für jeden schreibbar sein (nicht empfehlenswert).\\n\\n- Der Dateibesitz kann dem User gegeben werden, welcher mit der \\nServersoftware verbunden ist (idR root/admin Rechte erforderlich).\\n\\nDer Alternative aber sichere weg ist einfach die n\\folgenden Informationen über kopieren-einfügen in die Einstellungs-Datei /etc/inc-prefs.php zu schreiben." );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_2", "Wichtige Sicherheitsmitteilung" );

define( "ADMPREF_FORMS_SECMODE", "Sicherheits Modus" );
define( "ADMPREF_FORMS_SECKEY", "Sicherheits Schlüssel" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_1", "SICHERHEITS MODI:\\n0.0 = Öffentlicher Inhalt - Anmeldung aktiviert - Öffentliche Registrierung aktiviert\\n0.1 = Öffentlicher Inhalt - Anmeldung aktiviert - Öffentliche Registrierung deaktiviert\\n0.2 = Öffentlicher Inhalt - Administator Anmeldung erforderlich - Öffentliche Registrierung deaktiviert\\n1.0 = Privater Inhalt - Anmeldung aktiviert - Öffentliche Registrierung aktiviert\\n1.1 = Privater Inhalt - Anmeldung aktiviert - Öffentliche Registrierung deaktiviert\\n1.2 = Privater Inhalt - Administator Anmeldung erforderlich - Öffentliche Registrierung deaktiviert\\n" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_2", "\\nSICHERHEITS SCHLÜSSEL:\\nDer SicherheitsschlÜssel wird als zufälliger Wert zu generierung der Session\\nIDs verwendet. Ein Standardschlüssel wird für Sie bei der \\nInstallation und/oder Upgrade erstellt, und wird bei jeder Änderung der Einstellungsdatei erneuert, sie solten trotzdem von Zeit zu Zeit einen beliebigen Schlüssel mit 30 Zeichen eingeben. Der Schlüssel ist beliebig, \\nund muss nicht von Ihnen gemerkt werden (Dies ist kein Passwort)." );
define( "ADMPREF_FORMS_SECMODE_HELP_2", "Sicherheits Modus & Schlüssel Definition" );

define( "ADMPREF_FORMS_DBTYPE", "DB Typ" );
define( "ADMPREF_FORMS_DBHOST", "DB Host" );
define( "ADMPREF_FORMS_DBUSER", "DB Benutzer" );
define( "ADMPREF_FORMS_DBPASS", "DB Passwort" );
define( "ADMPREF_FORMS_DBNAME", "DB Name" );

define( "ADMPREF_FORMS_STREAM", "Musik Server" );
define( "ADMPREF_FORMS_MUSICDIR", "Musik Verzeichniss" );

define( "ADMPREF_FORMS_PROTECTMEDIA", "Medien schützen" );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_1", "Die Aktivierung dieser Option führt zur Benutzung eines integrierten media proxy der versuchts\\nungewollte downloads durch verwendung der im \\nPlayer angezeigten URL zu unterbinden. Leider muss diese Option bei der Verwendung von Ogg Vorbis Dateien deaktivert bleiben." );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_2", "Optionsbeschreibung" );

define( "ADMPREF_FORMS_REALONLY", "Real Player" );
define( "ADMPREF_FORMS_REALONLY_HELP_1", "Die Aktivierung dieser Option begrenzt das audio streaming auf den \\nReal Player,welcher nicht die Datei URL anzeigt." );
define( "ADMPREF_FORMS_REALONLY_HELP_2", "Optionsbeschreibung" );

define( "ADMPREF_FORMS_RADIO_HELP_1", "1 - Wählen Sie den Typ des Radio Servers aus der Liste, welchen Sie benutzen möchten (nur nötig\\nwenn auch das Radio Playlist Feld unten ausgefüllt).\\n\\n2 - Optional kann der volle Dateisystem-Pfad zu einer lokal EXISTIERENDEN Radio Playlist\\nDatei angegeben werden, wenn Sie diese mit Netjuke bearbeiten möchten.\\n\\nUm verschiedene Radio Server Typen unterstützen zu können, kann Netjuke nicht \\n komplett die Verwaltung des Servers selbst übernehmen. Netjuke wird nur die ausgewählten \\nTitel dem Server entsprechend speichern und formatieren und in die schon bestehende Liste einfügen. \\nDanach muss der Streamserver (neu)gestartet werden.\\n(Tip: Der QT/Darwin SS4 hat ein excellentes freies web-basiertes admin tool ;o)\\n\\n3 - Geben Sie optional die Radio Stream URL an, um einen \\\"Radio\\\" link in der Leiste anzuzeigen.\\n\\n\\nExtra: Wenn Sie mehr als einen Radio Stream von Netjuke aus verwalten wollen, plazieren \\nSie einfach eine dummy playlist irgrend wo, und verschieben Sie diese manuell an den vorgesehenen\\nPlatz nachdem Sie sie mit Netjuke bearbeitet haben (Es wird nicht der Radio link dieses\\nKontextes benutzt da Sie diesen nur mit einem Stream verbinden können)." );
define( "ADMPREF_FORMS_RADIO_HELP_2", "Radio Setup Hilfe" );
define( "ADMPREF_FORMS_RADIOTYPE", "Radio Server Typ" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_1", "Keiner" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_2", "Apple Quicktime/Darwin SS4" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_3", "ModMP3, Ices, WinAmp, etc." );
define( "ADMPREF_FORMS_RADIOURL", "Radio Stream URL" );
define( "ADMPREF_FORMS_RADIOPLIST", "Radio Playlist" );

define( "ADMPREF_FORMS_JUKEBOX_HELP_1", "1 - Wählen sie den Audio-Player welchen sie auf dem Server verwenden wollen (nur notwendig\\nwenn sie die Server-Abspielfunktion benutzen wollen).\\n\\n2 - Geben sie den vollen Dateisystempfad zu der Audio-Player-Software auf dem Server an.\\n(zB: /usr/bin/mpg123 oder C:\\\Programme\\\Winamp\\\Winamp.exe).\\n\\n3 - Geben sie den vollen Dateisystempfad zu der Jukebox-Spielliste-Textdatei an, die sie bearbeiten möchten.\\n\\nDie Jukebox Funktion von Netjuke  erlaubt die Erstellung und das Apspielen\\nvon Spiellisten auf dem Server (der Computer auf welchem Netjuke läuft). Dies\\nist für Benutzer gedacht, welche die Musik auf einem anderen Rechner als dem Rechner von dem aus sie auf Netjuke zugreiffen, apsielen möchten.\\nDie Möglichkeiten dieser Funktion sind momentan aufrund der Plattformunabhängigkeit noch sehr beschränkt.\\nFalls sie mehr Kontrolle und weitere Optionen für den serverbasierten Player haben möchten,\\n sind Sie herzlich eingeladen uns bei der Intergration neuer Player oder verbessertem Code zu helfen, oder sie können\\nandere Anwendungen die sich auf diese Aufgabe spezialisiert haben verwenden.\\nDas Hauptaugenmerk von Netjuke liegt beim Streaming.\\n\\nSiehe auch JUKEBOX FEATURE: SERVER-SIDE PLAYBACK INTEGRATION in\\ndocs/INSTALL.txt für genauere Information zum Einrichten des Players, etc." );
define( "ADMPREF_FORMS_JUKEBOX_HELP_2", "Jukebox Setup Hilfe" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER", "Player Typ" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION", "Keiner" );
define( "ADMPREF_FORMS_JUKEBOXPLAYERPATH", "Player Pfad" );
define( "ADMPREF_FORMS_JUKEBOXPLIST", "Jukebox Spielliste" );

define( "ADMPREF_FORMS_HTMLHEAD", "HTML Kopf" );
define( "ADMPREF_FORMS_HTMLFOOT", "HTML Fuss" );

define( "ADMPREF_FORMS_ENABLECOMM", "Gemeinschaft" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_1", "- Haupt Navigations Balken\\n- Gemeinschafts-Bereich\\n- Einstellungen gemeinsamer Spiellisten\\n" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_2", "betroffene Eigenschaften" );

define( "ADMPREF_FORMS_ENABLEDLOAD", "Datei Download" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_1", "Wenn aktiviert, steht ein neues Icon neben dem Titeltitel, mit dem\\nder Benutzer eine Datei herunterladen kann.\\n" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_2", "Optionsbeschreibung" );

define( "ADMPREF_FORMS_RESPERPAGE_1", "Ergebnisse beschränken auf " );
define( "ADMPREF_FORMS_RESPERPAGE_2", "Stück pro Seite, soweit vorhanden" );

define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS", "Anzeige Titel Zähler" );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_1", "Diese Option zeigt die absolute Anzahl der Titel bezüglich des aktuellen Kriteriums (Interpret, Album\\noder Genre) auf der Suchen-Seite und in der alphabetischen Auslistung.\\n\\nBitte beachten Sie, dass diese Option Ihren Server verlangsamen kann, da diese Zählung einen\\ngrosse Anzahl von Abfragen in der grösste Tabelle der Datenbank ausführt.\\nBenutzen Sie diese Option nur wenn Sie Netjuke auf einem leistungsfähigen Server einsetzen." );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_2", "Optionsbeschreibung" );

define( "ADMPREF_FORMS_LANGPACK", "Sprache" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_1", "Englisch" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_2", "Französisch" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_3", "Deutsch" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_4", "Katalanisch" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_5", "Spanisch" );

define( "ADMPREF_FORMS_THEMES", "Benutzer-Umgebungen" );
define( "ADMPREF_FORMS_THEMES_HELP", "Erlaubt den Benutzern Ihre eigenen Farben und Schriften zu defenieren." );

define( "ADMPREF_FORMS_INVICN", "Invertierte Icons" );
define( "ADMPREF_FORMS_INVICN_HELP", "Erlaubt den Benutzern die Farben folgender Icons zu invertieren: Play, Get Info, Filter..." );

define( "ADMPREF_FORMS_FONTFACE", "Schrifttyp" );
define( "ADMPREF_FORMS_FONTSIZE", "Schriftgrösse" );
define( "ADMPREF_FORMS_BGCOLOR", "Hintergrundfarbe" );
define( "ADMPREF_FORMS_TEXT", "Textfarbe" );
define( "ADMPREF_FORMS_LINK", "Link-Farbe" );
define( "ADMPREF_FORMS_ALINK", "Farbe aktiver Links" );
define( "ADMPREF_FORMS_VLINK", "Farbe besuchter Links" );
define( "ADMPREF_FORMS_BORDER", "Randfarbe" );
define( "ADMPREF_FORMS_HEADER", "Kopffarbe" );
define( "ADMPREF_FORMS_HEADERFC", "Kopftextfarbe" );
define( "ADMPREF_FORMS_CONTENT", "Farbe des Inhalts" );

define( "ADMPREF_FORMS_BTN_SAVE", "Speichern" );
define( "ADMPREF_FORMS_BTN_RESET", "Zurücksetzten" );

##################################################

?>