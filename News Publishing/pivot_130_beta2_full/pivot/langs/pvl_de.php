<?php
//Deutsch (German)

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2rd line.

// German translation of Pivot lang file
// Translated by: Teebee <tim@teebee.org> (www.teebee.org), and Knut <inbox@etribe.de> (www.etribe.de)
// Complete re-edited and corrected by: Nicole Simon (http://beissholz.de)
//
// Last updated by Pivot (www.pivotlog.net) 07.11.2004
// New strings added. Translation required.
//

// allow for different encoding for non-western languages
$encoding="iso-8859-1";
$langname="de";


//		General		\\
$lang['general'] = array (
	'yes' => 'Ja',	//affirmative
	'no' => 'Nein',		//negative
	'go' => 'Weiter',	//proceed

    'minlevel' => 'Sie haben keine Berechtigung für diesen Bereich von Pivot',    
    'email' => 'E-Mail',            
	'url' => 'URL',
	'further_options' => "Weitere Optionen",
    'basic_view' => "Standard-Ansicht",
    'basic_view_desc' => "Zeige die einfache Ansicht",
	'extended_view' => "Erweiterte Ansicht",
    'extended_view_desc' => "Alle editierbaren Felder anzeigen",
    'select' => "Auswählen",
	'cancel' => "Abbrechen",
	'delete' => 'Löschen',
	'welcome' => "Willkommen zu %build%.",
	'write' => "Schreibe",
	'write_open_error' => "Write Error. Could not open file for writing",
	'write_write_error' => "Write Error. Could not write to file",
	'done' => "Fertig!",
	'shortcuts' => "Shortcuts",		
    'cantdelete' => "Sie haben keine Berechtigung, den Artikel %title% zu löschen!",
    'cantdothat' => "Sie haben keine Berechtigung, dies mit dem Artikel %title% zu machen!", 
	'cantdeletelast' => "You can not delete the last entry. You must first post a new entry, before deleting this entry",
);


$lang['userlevels'] = array(
    'Superadmin', 'Administrator', 'Fortgeschritten', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin, 
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array(
	'null', 'ein', 'zwei', 'drei', 'vier', 'fünf', 'sechs', 'sieben', 'acht', 'neun', 'zehn', 'elf', 'zwölf', 'dreizehn', 'vierzehn', 'fünfzehn', 'sechszehn'
);


$lang['months'] = array (
	'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'	
);	
		

$lang['months_abbr'] = array (
	'Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'		
);


$lang['days'] = array (
	'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'
);


$lang['days_abbr'] = array (
	'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'
);
	

$lang['days_calendar'] = array (
	'S', 'M', 'D', 'M', 'D', 'F', 'S'
); 


$lang['datetime_words'] = array (
	'Jahr', 'Monat', 'Woche', 'Wochentag', 'Stunde', 'Minute', 'Sekunden'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'Login',
	'name' => 'Benutzername',
	'pass' => 'Passwort',
	'remember' => 'Pivot soll folgendes beibehalten:',
	'rchoice' => array (
		'0' => 'Nichts',
        '1' => 'Meinen Benutzername und mein Passwort',
		'2' => 'Pivot soll mich ab jetzt automatisch anmelden',
	),
    'delete_cookies_desc' => 'Wenn Sie sicher sind, daß Sie den korrekten Usernamen und Paßwort eingegeben haben, Sie aber Probleme mit dem Einloggen haben, können Sie versuchen, die Cookies für diese Domain zu löschen:',
    'delete_cookies' => 'Cookies löschen',
    'retry' => 'Falscher Benutzernahme oder falsches Passwort',
    'banned' => 'Ihr Einloggen ist 10 mal fehlgeschlagen.  Ihre IP wurde für die nächsten 12 Stunden zum Einloggen gesperrt.',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => 'Übersicht',
	'entries' => 'Artikel',
	'submit' => 'Neuer Artikel',
	'comments' => 'Kommentare',
	'trackbacks' => 'Trackbacks',
	'modify' => 'Artikel bearbeiten',
    'userinfo' => 'Meine persönlichen Informationen',
	'u_settings' => 'Meine Einstellungen',
	'u_marklet' => 'Bookmarklets',
    'files' => 'Media-Dateien',
	'upload' => 'Upload',
	'stats' => 'Statistik',
    'admin' => 'Administration',

    'main_title' => 'Globale Übersicht von Pivot',
	'entries_title' => 'Übersicht der Artikel',
    'submit_title' => 'Neuer Artikel',
	'comments_title' => 'Bearbeite oder lösche Kommentare',		
	'modify_title' => 'Artikel bearbeiten',
    'userinfo_title' => 'Meine persönlichen Informationen einsehen',
    'u_settings_title' => 'Meine Einstellungen bearbeiten',
    'u_marklet_title' => 'Bookmarklets erstellen',
    'files_title' => 'Verwaltung und Hochladen von Media-Dateien',
    'upload_title' => 'Dateien hochladen',
	'uploaded_success' => 'Datei ist hochgeladen',
	'stats_title' => 'Statistiken und Logfiles ansehen.',
    'updatetitles_title' => 'Statistiken und Logfiles ansehen.',
    'admin_title' => 'Übersicht der Administration',
    'recent_entries' => 'Neueste Artikel',
    'recent_comments' => 'Neueste Kommentare',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
    //'trebuild' => 'Rebuild all Files', rolled into maintenance
	'seeusers' => 'Benutzer',
	'seeconfig' => 'Konfiguration',
	'filemappings' => 'File Mappings',
	'templates' => 'Templates',
	'maintenance' => 'Wartung',
    'regen' => 'Alle von Pivot generierten Dateien und Archive neu generieren',
	'blogs' => 'Weblogs',
	'categories' => 'Kategorien',
    'verifydb' => 'Datenbank verifizieren',
    'buildindex' => 'Index neu generieren',
    'buildsearchindex' => 'Such-Index neu generieren',
    'buildfrontpage' => 'Startseite neu generieren',
	'sendping' => 'Sende Pings',


	'backup' => 'Sicherheitskopie',
	'description' => 'Beschreibung',
    'conversion' => 'Konvertierung',
    'seeusers_title' => 'Erstelle, bearbeite und lösche Benutzer ',
    'userfields' => 'Felder des Benutzerkontos',
    'userfields_title' => 'Felder des Benutzerkontos bearbeiten',
	'seeconfig_title' => 'Bearbeite Konfigurationsdatei',
	'filemappings_title' => 'Show and overview of which files in your site are created and by the weblogs in this Pivot',
    'templates_title' => 'Templates erstellen, bearbeiten oder löschen',
    'maintenance_title' => 'Standard-Wartung der Pivot-Dateien durchführen',
    'regen_title' => 'Alle von Pivot generierten Dateien und Archive neu generieren',
    'blogs_title' => 'Weblogs erstellen, bearbeiten oder löschen',
    'blogs_edit_title' => 'Bearbeite Weblog-Einstellungen für ',
    'categories_title' => 'Kategorien erstellen, bearbeiten oder löschen',    
    'verifydb_title' => 'Überprüfen der Integrität Ihrer Datenbank',
    'buildindex_title' => 'Generiert den Index der Datenbank neu',
    'buildsearchindex_title' => 'Erstellt den Such-Index neu, um Suchen in den Artikeln zu ermöglichen',
	'buildfrontpage_title' => 'Erneut die Hauptseite, Archive und den RSS-Feed Ihres Weblogs generieren.',
	'backup_title' => 'Sicherheitskopie Ihrer Artikel erstellen ',
	'backup_config' => 'Backup of configuration files',
	'backup_config_desc' => 'This will let you download a zip file containing your configuration files',
	'ipblocks' => 'IP Blockieren',
	'ipblocks_title' => 'Blockierte IP-Adressen anzeigen und bearbeiten.',
    'ipblocks_stored' => 'IP-addresses sind gespeichert.',
    'ipblocks_store' => 'Diese IP-Adressen speichern',
	'ignoreddomains' => 'Ignored Domains',
	'ignoreddomains_title' => 'View and Edit the Ignored Domains.',
	'ignoreddomains_stored' => 'The Ignored Domains have been stored.',
	'ignoreddomains_store' => 'Store these Ignored Domains',
    'fileexplore' => 'Datei-Explorer',
    'fileexplore_title' => 'Dateien ansehen, (sowohl Text- wie auch Datenbankdateien',
    'sendping_title' => 'Pings zu Update-Tracker(n) senden.',
    'buildindex_start' => 'Erstelle den Index ... Dies kann etwas dauern, bitte NICHT unterbrechen.',
    'buildsearchindex_start' => 'Erstelle den Such-Index  ... Dies kann etwas dauern, bitte NICHT unterbrechen.',
    'buildindex_finished' => 'Erstellen des Index dauerte %num% Sekunden',

	'filemappings_desc' => 'Below you can see an overview of each weblog in this Pivot installation, together with which files are created by Pivot and what templates it uses to create these files. This can also be very useful when pinpointing trouble with the creation of files.',
	
	'debug' => 'Open Debug window',

);


$lang['templates'] = array (	
	'rollback' => 'Rollback',
    'create_template' => 'Template erstellen',
    'create_template_info' => 'Erstelle ein neues Template',
	'no_comment' => 'Keine Kommentare',
	'comment' => 'Kommentar*',
    'comment_note' => '(*Beachte: Kommentare können nur beim <b>ersten</b> Speichern erstellt werden.)',
    'create' => 'Template erstellen',
	'editing' => 'Bearbeiten',
	'filename' => 'Dateiname',
	'save_changes' => 'Änderungen speichern!',
	'save_template' => 'Template speichern!',		
	'aux_template' => 'Auxillary template',
	'sub_template' => 'Subtemplate',
	'standard_template' => 'Normal template',
	'feed_template' => 'Feed template',
	'css_template' => 'CSS file',
	'txt_template' => 'Text file',	
	'php_template' => 'PHP file',	
);


//		Admin			\\
// bob notes: Mark made these, i think they should be replaced by the 'adminbar_xxx_title'] ones
$lang['admin'] = array (
	'seeusers' => 'Benutzer erstellen, bearbeiten oder löschen',
	'seeconfig' => 'Konfigurationsdatei erstellen, bearbeiten oder löschen',
	'templates' => 'Templates erstellen, bearbeiten oder löschen',
    'maintenance' => 'Wartungsarbeiten durchführen wie Index neu erstellen, Sicherungen erstellen oder Artikel neu generieren.',
    'regen' => 'Alle von Pivot generierten Dateien und Archive neu generieren',
	'blogs' => 'Weblogs erstellen, bearbeiten oder löschen',
);


//		Maintenace		\\	
$lang['maint'] = array (
	'title' => 'Wartung',	
	'gen_arc_title' => 'Archiv generieren', /* bob notes: redundant, see 'regen' */
	'gen_arc_text' => 'Alle Archive erneut generieren', /* bob notes: redundant, see 'regen' */
    'xml_title' => 'Überprüfe XML-Dateien', /* bob notes: replace with more general 'Verify DB' */
    'xml_text' => 'Überprüfe  (und falls notwendig repariere) die Integrität der XML-Dateien', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'Sicherheitskopie',
    'backup_text' => 'Fertigt eine Sicherheitskopie aller wichtigen Pivot-Dateien an',
);


//		Stats and referers		\\
$lang['stats'] = array (
    'show_last' => "Zeige die neuesten",
    '20ref' => "20 Verweise",
    '50ref' => "50 Verweise",
    'allref' => "alle Verweise",
    'updateref' => "Aktualisiere Verweise von Verweisen zu Titeln",
	'showall' => "both blocked and non-blocked lines",
	'updateref' => "Update the referer to title mappings",
    'hostaddress' => 'Host-Adresse (IP-Adresse)',
    'which page' => 'welche Seite',

	'getting' => 'Sammle neue Titel',
	'awhile' => 'Der Vorgang kann einige Zeit in Anspruch nehmen, bitte NICHT unterbrechen',
    'firstpass' => 'Erster Durchlauf',
    'secondpass' => 'Zweiter Durchlauf',
    'nowuptodate' => 'Ihre Verweiseliste wurde aktualisiert.',
	'finished' => 'Erledigt',
);


//		User Info		\\
	$lang['userinfo'] = array (
	'editfields' => 'Bearbeite Benutzerfelder',
    'desc_editfields' => 'Bearbeite die Eingabefelder, die der Benutzer verwenden kann, um sich selbst zu beschreiben',
	'username' => 'Benutzername',
	'pass1' => 'Passwort',
	'pass2' => 'Passwort (zur Verifikation)',
    'email' => 'E-Mail',
    'userlevel' => 'Benutzer-Level',    
    'userlevel_desc' => 'Der Benutzer-Level legt fest, welche Aktionen ein Benutzer in Pivot durchführen darf.',
	'language' => 'Sprache',	
	'lastlogin' => 'Last Login',
	'edituser' => 'Bearbeite Benutzer',  //the link to.. well, edit the user (also the title)
    'edituserinfo' => 'Bearbeite Benutzer-Informationen',
    'newuser' => 'Neuen Benutzer erstellen',
    'desc_newuser' => 'Einen neuen Account in Pivot eröffnen, um dem Benutzer das Erstellen von Artikel zu ermöglichen.',
    'newuser_button' => 'Erstellen',
	'edituser_button' => 'Ändern',
    'pass_too_short' => 'Passwort muss mindestens 4 Zeichen lang sein.',
	'pass_equal_name' => 'Password can\'t be the same as username.',
    'pass_dont_match' => 'Passwörter stimmen nicht überein',
	'username_in_use' => 'Benutzername existiert bereits',
	'username_too_short' => 'Benutzername muss mindestens 4 Zeichen lang sein.',
    'username_not_valid' => 'Benutzernamen können nur alphanumerische Zeichen (A bis Z, 0 bis 9) und Unterstriche (_) enthalten.',
    'not_good_email' => 'Die eingegebene E-Mailadresse ist ungültig',    
    'c_admin_title' => 'Bitte bestätigen Sie,  das ein Administrator definiert wurde.',
    'c_admin_message' => 'Ein '.$lang['userlevels']['1'].' hat alle Rechte in Pivot, kann alle Artikel und Kommentare verändern, und ist in der Lage alle Einstellungen zu ändern. Sind Sie sicher, dass Sie aus %s einen  '.$lang['userlevels']['1'].' machen wollen?',    
);


//		Config Page		\\		
	$lang['config'] = array (
	'save' => 'Einstellungen speichern.',

    'sitename' => 'Site-Name',
    'defaultlanguage' => 'Standardsprache',
	'defaultencoding' => 'Use encoding',
	'defaultencoding_desc' => 'This defines the encoding that is used (like utf-8 or iso-8859-1). You should leave this blank, unless you know what you\'re doing. If you leave this blank it will use the appropriate settings from the language files.',
    'siteurl' => 'Site-URL',
    'header_fileinfo' => 'Datei-Info',
	'localpath' => 'Lokaler Pfad',
    'debug_options' => 'Debug-Optionen',
    'debug' => 'Debug-Modus',
    'debug_desc' => 'Gelegentlich Debug-Informationen anzeigen ...',
	'log' => 'Logfiles',
    'log_desc' => 'Logfiles für verschiednene Aktivitäten erstellen',

	'unlink' => 'Unlink Files',
	'unlink_desc' => 'Some instances of servers on which the ghastly safe_mode is enabled, might require playing with this option. On most servers this option will not have any effect',
	'chmod' => 'Chmod Files To',
	'chmod_desc' => 'Some servers require that created files are chmodded in a specific way. Common values are \'0644\' and \'0755\'. Do not change this, unless you know what you\'re doing.',
	'header_uploads' => 'Dateien hochladen',
    'upload_path' => 'Upload-Pfad',    
    'upload_accept' => 'Akzeptierte Dateiformate',                
    'upload_extension' => 'Standard-Dateiendung',
    'upload_save_mode' => 'Überschreiben',
	'make_safe' => 'In websicheren Dateinamen umwandeln',
	'c_upload_save_mode' => 'Inkrementaler Dateiname',
	'max_filesize' => 'Maximale Dateigröße',
	'header_datetime' => 'Datum/Uhrzeit',
    'timeoffset_unit' => 'Einheit, in der der Zeitunterschied gemessen wird',
	'timeoffset' => 'Zeitunterschied',
	'header_extra' => 'Weitere Einstellungen',
    'wysiwyg' => 'WYSIWYG-Editor standardmäßig einschalten',
    'wysiwyg_desc' => 'Legt fest, ob der WYSIWYG-Editor standardmäßig eingestellt sein soll. Jeder Benutzer kann es in seinen eigenen Einstellungen definieren.',
	'basic_view' => 'Use Basic View',
	'basic_view_desc' => 'Determines whether the \'New Entry\' opens in Basic View or in Extended View.',
    'def_text_processing' => 'Standardverhalten bei der Texteingabe',
    'text_processing' => 'Texteingabe',
    'text_processing_desc' => 'Legt fest, wie der Benutzer den Text eingeben kann, wenn kein  WYSIWYG-Editor verwendet wird. \'Konvertiere Absätze\' ändert nur die Absätze zu einem   &lt;br&gt;-tag. <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> ist eine mächtige Textauszeichnungssprache.',
    'none' => 'Nichts',
    'convert_br' => 'Konvertiere Absätze zu &lt;br /&gt;',
    'textile' => 'Benutze Textile',
	'markdown' => 'Markdown',
	'markdown_smartypants' => 'Markdown and Smartypants',

	'allowed_cats' => 'Allowed Categories',
	'allowed_cats_desc' => 'This user is allowed to post entries in the selected categories',
	'delete_user' => "Delete user",
	'delete_user_desc' => "You can delete this user if you would like. All of their posts will remain, but they will no longer be able to login",
	'delete_user_confirm' => 'You\'re about to remove access for %s. Are you sure you want to do this?',
	
    'setup_ping' => 'Ping-Einstellungen',
    'ping_use' => 'Erlaube Update-Tracker',
    'ping_use_desc' => 'Diese Option sendet Update-Trackern wie z.B. weblogs.com Informationen wenn ein neuer Artikel gepostet wurde. Webseiten wie blogrolling.com brauchen diesen Ping.',    
    'ping_urls' => 'URLs die angepingt werden sollen',
    'ping_urls_desc' => 'Sie können mehrere URLS definieren, an die Sie Update-Informationen schicken wollen. Schreiben Sie jeden Servernamen OHNE http://, jeweils eine URL pro Zeile, getrennt durch ein "|" Zeichen.  Bekannte Server sind z.B.:<br /><b>rpc.weblogs.com/RPC2</b> (weblogs.com pinger, der Bekannteste)<br /><b>pivotlog.net/pinger</b> (pivotlog pinger, noch nicht online)<br /><b>rcs.datashed.net/RPC2</b> (euro.weblogs.com pinger)<br /><b>ping.blo.gs</b> (blo.gs pinger). Für deutschsprachige Artikel ist auch blogg.de interessant.<br />',

	'setup_tb' => 'Trackback Setup',
	'tb_email' => 'Email',
	'tb_email_desc' => 'If set, an email will be sent to this address when a Tracback is added.',

	'new_window' => 'Links in einem neuen Fenster öffnen',
	'emoticons' => 'Emoticons benutzen',
    'javascript_email' => 'E-Mailadresse codieren',    
    'new_window_desc' => 'Legt fest, ob Links in Artikel in einem neuen Fenster geöffnet werden sollen.',

	'mod_rewrite' => 'Filesmatch benutzen',
    'mod_rewrite_desc' => 'Wenn Sie Apache\'s Filesmatch benutzen, wird Pivot Urls wie www.mysite.com/archive/2003/05/30/nice_weather, statt www.mysite.com/pivot/entry.php?id=134 erzeugen. Nicht alle Server unterstützen diese Funktion, lesen Sie bitte erst die Redienungsanleitung oder fragen Sie bei Ihrem Provider nach.',
	'mod_rewrite_1' => 'Yes, like /archive/2005/04/28/title_of_entry',
	'mod_rewrite_2' => 'Yes, like /archive/2005-04-28/title_of_entry',
	'mod_rewrite_3' => 'Yes, like /entry/1234',
	'mod_rewrite_4' => 'Yes, like /entry/1234/title_of_entry',

    'default_introduction' => 'Standardvorlage für neuen Artikel (Einleitung / Haupttext)',
    'default_introduction_desc' => 'Dies legt die Standardwerte für Einleitung / Haupttext in einem neuen Artikel fest. Normalerweise ist dieser Eintrag leer.',

	'default_allow_comments' => 'Allow comments by default',
	'default_allow_comments_desc' => 'Determine whether entries are set to allow comments or not.',	

  'maxhrefs' => 'Number of links',
  'maxhrefs_desc' => 'Maximum number of hyperlinks in allowed in comments. Useful to get rid of those pesky comment spammers. Set to 0 for unlimited links.',
  'rebuild_threshold' => 'Rebuild Threshold',
  'rebuild_threshold_desc' => 'The number of seconds rebuilding takes, before Pivot refreshes the page. The default is 28, but if you are having problems with rebuilding, try lowering this number to 10.',
	'default_introduction' => 'Default Introduction/Body',
	'default_introduction_desc' => 'This will determine the default values for Introduction and Body when an author writes a new entry. Normally this will be an empty paragraph, which makes the most sense semantically.',

    'upload_autothumb'    => 'Automatische Thumbnails',
    'upload_thumb_width' => 'Thumbnail-Breite',
    'upload_thumb_height' => 'Thumbnail-Höhe',
    'upload_thumb_remote' => 'Externes cropping-Script',
    'upload_thumb_remote_desc' => 'Wenn Ihr Server nicht die notwendigen Bibliotheken für automatisierte Imagebeschneidung installiert hat, können Sie ein externes cropping Script verwenden.',

	'extensions_header' => 'Extensions directory',
	'extensions_desc'   => 'The \'extensions\' directory is the place to store your additions to Pivot.
		This makes updating a lot easier. See the Docs for more info.',
	'extensions_path'   => 'Extensions directory path',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'Weblog bearbeiten',
    'edit_blog' => 'Weblogs bearbeiten',
    'new_weblog' => 'Neues Weblog',
	'new_weblog_desc' => 'Neues Weblog hinzufügen',
	'del_weblog' => 'Weblog löschen',
	'del_this_weblog' => 'Dieses Weblog löschen.',
	'create_new' => 'Neues Weblog erstellen',
    'subw_heading' => 'Sie können für jedes Subweblog festlegen, welches  Templates es benutzten soll und welche Kategorien in diesem veröffentlicht werden sollen.',
	'create' => 'Fertig',
	
    'create_1' => 'Erstellen  / ändern Weblog, Schritt 1 von 3',
    'create_2' => 'Erstellen  / ändern Weblog, Schritt 2 von 3',
    'create_3' => 'Erstellen  / ändern Weblog, Schritt 3 von 3',

    'name' => 'Name des Weblogs',
	'payoff' => 'Untertitel',
	'payoff_desc' => 'Geben Sie hier einen Untertitel oder Slogan ein',
	'url' => 'URL zum Weblog',
    'url_desc' => 'Pivot wird die URL selber bestimmen, wenn Sie dieses Eingabefeld nicht ausfüllen. Wenn Ihr Weblog zum Beispiel Teil eines Framesets oder eines server-side-includes ist, können Sie dieses hier festlegen.',
	'index_name' => 'Hauptseite (Index)',
	'index_name_desc' => 'Der Dateiname der Index-Datei. Meistens so etwas wie \'index.html\' oder \'index.php\'.',

    'ssi_prefix' => 'SSI-Prefix',
    'ssi_prefix_desc' => 'Wenn Ihr Weblog SSI benutzt (wozu wir nicht raten) können Sie dieses Eingabefeld benutzen um die SSI-Dateinamen für die Pivotdateien zu benutzen. \'index.shtml?p=\'. Lassen Sie dieses Eingabefeld einfach leer, es sei denn Sie wissen genau was Sie tun.',

	'front_path' => 'Pfad zur Hauptseite',
    'front_path_desc' => 'Der relative oder absolute Pfad, wo Pivot die Hauptseite dieses Weblogs generiert soll.',
	'file_format' => 'Dateiname',
    'entry_heading' => 'Artikel-Einstellungen',
    'entry_path' => 'Artikel-Pfad',
    'entry_path_desc' => 'Der relative oder absolute Pfad,  wo Pivot die Artikelseiten generieren soll.',
    'live_comments' => 'Live-Artikel',
    'live_comments_desc' => 'Wenn Sie \'Live-Artikel\' benutzen, muß Pivot nicht für jeden Beitrag eine neue Seite erstellen. Diese Einstellung wird empfohlen.',
    'readmore' => '\'weiterlesen\'-Text',
    'readmore_desc' => 'Der Text des Links, der angezeigt wird, um den gesamten Artikel zu lesen',
    
    'arc_heading' => 'Archiv-Einstellungen',
    'arc_index' => 'Index-Datei',
    'arc_path' => 'Archiv-Pfad',
	'archive_amount' => 'Anzahl der Archive',
    'archive_unit' => 'Archiv-Art',
    'archive_format' => 'Archiv-Format',
	'archive_none' => 'Keine Archive',
	'archive_weekly' => 'Archive pro Woche',
	'archive_monthly' => 'Archive pro Monat',
	'archive_yearly' => 'Yearly Archives',

    'archive_link' => 'Archiv-Link',
    'archive_linkfile' => 'Archive-Linkformat',    
    'archive_order' => 'Archive-Sortierung',
    'archive_ascending' => 'Aufsteigend (älteste zuerst)',
    'archive_descending' => 'Absteigend (neueste zuerst)',

	'templates_heading' => 'Templates',
    'frontpage_template' => 'Template für die Hauptseite',
    'frontpage_template_desc' => 'Das Template, welches das Layout der Hauptseite bestimmt.',
    'archivepage_template' => 'Template für die Archivseite',
    'archivepage_template_desc' => 'Das Template, welches das Layout der Archivseiten bestimmt. Dies kann das gleiche sein wie das Ihrer Hauptseite.',    
    'entrypage_template' => 'Template für einen kompletten Artikel',
    'entrypage_template_desc' => 'Das Template, welches das Layout für die Anzeige eines einzelnen Artikel bestimmt.',    
	'extrapage_template' => 'Extra Template',
	'extrapage_template_desc' => 'The Template that defines what your archive and search.php will look like.',

    'shortentry_template' => 'Template für den \'Shortentry\'',
    'shortentry_template_desc' => 'Das Template, welches das Layout eines \'Shortentry\' bestimmt, so wie es auf dem Weblog oder in den Archiven angezeigt werden, quasi die Kurzversion inklusive einem möglichen \'weiterlesen\' .',    
    'num_entries' => 'Anzahl der Artikel',
    'num_entries_desc' => 'Die Zahl der Artikel, die auf der Hauptseite gezeigt werden sollen',    
	'offset' => 'Offset',
	'offset_desc' => 'If Offset is set to a number, that amount of entries will be skipped when generating the page. You can use this to make a \'Previous entries\' list, for example.',
	'comments' => 'Kommentare erlauben?',
    'comments_desc' => 'Legt fest, ob generell Kommentare zu Ihren Beiträge geschrieben werden können. (Sie können in den erweiterten Einstellungen für jeden Artikel festlegen, ob hierfür Kommentare erlaubt sind oder nicht.) ',    

	'publish_cats' => 'Publish these categories',

    'setup_rss_head' => 'RSS und Atom-Konfiguration',
    'rss_use' => 'Erzeuge Feeds',
    'rss_use_desc' => 'Legt fest, ob Pivot automatisch einen RSS-Feed und einen Atom-Feed für Ihrer Seite generiert.',
    'rss_filename' => 'RSS-Dateiname',
    'atom_filename' => 'Atom-Dateiname',
    'rss_path' => 'Feed-Pfad',
    'rss_path_desc' => 'Der relative oder absolute Pfad in dem Pivot die Feed-Dateien erzeugen soll.',
//    'rss_size' => 'Länge der Feed-Artikel',    
//    'rss_size_desc' => 'Die Länge eines Artikel (in Zeichen) in den Feed-Dateien.',    
	'rss_full' => 'Create Full Feeds',
	'rss_full_desc' => 'Determines whether Pivot creates full Atom and RSS feeds. If set to \'no\' Pivot will create feeds that just contains short descriptions, thereby making your feeds less useful.',
	'rss_link' => 'Feed Link',
	'rss_link_desc' => 'The link to send with the Feed, to point to the main page. If you leave this blank, Pivot will send the weblog\'s index as link.',
	'rss_img' => 'Feed Image', 
	'rss_img_desc' => 'You can specify an image to send with the Feed. Some feed readers will display this image along with your feed. Leave this blank, or specify a full URL.',
	
  'lastcomm_head' => 'Einstellungen für "Neueste Kommentare"',
    'lastcomm_amount' => 'Anzahl der zu zeigenden Kommentare',
    'lastcomm_length' => 'Schneide ab nach wieviel Zeichen',
	'lastcomm_format' => 'Format',
    'lastcomm_format_desc' => 'Diese Einstellungen verändern die Ausgabe \'Neueste Kommentare\' ([[last_comments]]) ',
	'lastcomm_redirect' => 'Redirect Referers',
	'lastcomm_redirect_desc' => 'To combat refererspam you might choose to redirect outgoing links in the comments, as this will not help the spammer get a better pagerank in Google.',

    'lastref_head' => 'Einstellungen für "Neueste Verweise"',
    'lastref_amount' => 'Anzahl der zu zeigenden Verweise',
    'lastref_length' => 'Schneide ab nach wieviel Zeichen',
	'lastref_format' => 'Format',
    'lastref_format_desc' => 'Diese Einstellungen verändern die Ausgabe \'Neueste Verweise\' ([[last_referrers]])',
	'lastref_graphic' => 'Use graphics',
	'lastref_graphic_desc' => 'This determines if the last referers use small icons for the most common search engines through which visitors may arrive.',
	'lastref_redirect' => 'Redirect Referers',
	'lastref_redirect_desc' => 'To combat refererspam you might choose to redirect outgoing links to referers, as this will not help the spammer get a better pagerank in Google.',

	'various_head' => 'Weitere Einstellungen',
	'emoticons' =>  'Emoticons verwenden',
    'emoticons_desc' => 'Legt fest, ob zum Beispiel :-) zu einen Smiley konvertiert wird.',
    'encode_email_addresses' => 'E-Mailadressen codieren',
    'encode_email_addresses_desc' => 'Entscheidet ob E-Mailadressen als Javascript geschützt werden, um das automatisierte Sammeln von E-Mailadressen durch sog. Spam-Harvester zu vermeiden.',
    'target_blank' => 'Neues Fenster',
    'xhtml_workaround' => 'XHTML-Workaround',
    'target_blank_desc' => 'Falls \'Ja\', werden alle Links in Ihren Artikeln in einem neuen Fenster geöffnet. Wählen Sie \'XHTML workaround\' damit alle Links einen rel="external" erhalten, um ein \'wellformed XML\' zu erreichen.',

    'date_head' => 'Einstellungen der Datumsausgabe ',
	'full_date' => 'Schreibweise des vollständigen Datums',
    'full_date_desc' => 'Legt das Format fest, in dem das komplette Datum (full date) dargestellt wird. Wird meistens am Start eines einzelnen Artikels dargestellt',
    'entry_date' => 'Artikel-Datum (entry_date)',
    'diff_date' => 'Alternatives Datum (diff_date)',
    'diff_date_desc' => 'Das alternative Datum wird meistens zusammen mit dem Artikeldatum verwendet. Im Gegensatz zum Artikeldatum wird das alternative Datum nur angezeigt, wenn sich das Datum des Tages von dem vorherigen Artikel unterscheidet.',
	'language' => 'Sprache',
	'language_desc' => 'The Language determines in what language the dates and numbers will be output, and also determines the page\'s charset encoding (like iso-8859-1 or koi8-r, for example).',	

    'comment_head' => 'Kommentar-Einstellungen',
	'comment_sendmail' => 'Benachrichtigung bei neuen Kommentaren',
    'comment_sendmail_desc' => 'Versendet automatisch eine E-Mail bei neuen Kommentaren',
    'comment_emailto' => 'E-Mailadressen der Empfänger',
    'comment_emailto_desc' => 'An welche Adresse(n) sollen die Kommentarbenachrichtigungen geschickt werden? Mehrere Adressen bitte durch ein Komma (,) trennen.',
	'comment_texttolinks' => 'Text zu Links konvertieren',
    'comment_texttolinks_desc' => 'Bestimmt ob eingegebene URL\'s und Mailadressen automatisch zu klickbaren Links konvertiert werden.',
	'comment_wrap' => 'Automatischer Zeilenumbruch',
	'comment_wrap_desc' => 'Um das Layout zu schuetzen wird eine Zeile des Kommentartextes nach einer gewissen Anzahl von Zeichen automatisch umgebrochen',
    'comments_text_0' => 'Text für \'kein Kommentar\'',
    'comments_text_1' => 'Text für \'einen Kommentar\'',
	'comments_text_2' => 'Text für \'X Kommentare\'',
    'comments_text_2_desc' => 'Der Text der benutzt wird um die Zahl der Kommentare anzuzeigen, wenn Sie nichts eingeben wird Pivot die Standardtexte benutzen.',

	'comment_pop' => 'Kommentare in einem Popup anzeigen?',
	'comment_pop_desc' => 'Kommentare werden als Popup geöffnet.',
	'comment_width' => 'Breite des Popups',
	'comment_height' => 'Höhe des Popups',
    'comment_height_desc' => 'Legt Breite und Höhe des Kommentar-Popups fest.',

    'comment_format' => "Format der Komments",
    'comment_format_desc' => "Legt fest, wie Kommentare auf den Artikelseiten angezeigt werden sollen.",

	'comment_reply' => "Format of 'reply ..'",
	'comment_reply_desc' => "This determines the formatting of the link that visitors can use to reply on a specific comment.",
	'comment_forward' => "Format of 'reply by ..'",
	'comment_forward_desc' => "This determines the formatting of the text that is displayed when the comment is replied by another comment.",
	'comment_backward' => "Format of 'reply on ..'",
	'comment_backward_desc' => "This determines the formatting of the text that is displayed when the comment is a reply on another comment.",
			
    'comment_textile' => 'Textile erlauben',
    'comment_textile_desc' => 'Wenn dieses mit \'Ja\' eingestellt wird können Besucher <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a>  in ihren Kommentaren verwenden.',
	'save_comment' => 'Store Comment',
	'comment_gravatardefault' => 'Default Gravatar',
	'comment_gravatardefault_desc' => 'URL to the default Gravatar image. Start with http://',
	'comment_gravatarhtml' => 'Gravatar HTML',
	'comment_gravatarhtml_desc' => 'HTML to insert for a gravatar. %img% will be substituted by the url to the image.',
	'comment_gravatarsize' => 'Gravatar size',
	'comment_gravatarsize_desc' => 'Size (in pixels) of the gravatar. The default is 48.',
	
    'trackback_head' => 'Trackback Settings',
	'trackback_sendmail' => 'Send Mail?',
	'trackback_sendmail_desc' => 'After a trackback has been placed, mail can be sent to maintainers of this weblog.',
	'trackback_emailto' => 'Mail to',
	'trackback_emailto_desc' => 'Specify the email address(es) to whom mail will be sent. Seperate multiple addresses with a comma.',
	'trackbacks_text_0' => 'Label for \'no trackbacks\'',
	'trackbacks_text_1' => 'Label for \'one trackback\'',
	'trackbacks_text_2' => 'Label for \'X trackbacks\'',
	'trackbacks_text_2_desc' => 'The text that is used to indicate how many trackbacks there are. If you leave this blank, Pivot will use the default as defined by the language settings',
	'trackback_pop' => 'Trackbacks Popup?',
	'trackback_pop_desc' => 'determines whether the trackbacks page (or \'single entry\') will be shown in a popup window, or in the original browser window.',
	'trackback_width' => 'Width of Popup',
	'trackback_height' => 'Height of Popup',
	'trackback_height_desc' => 'Specify the width and height (in pixels) of the trackbacks pop-up.',
	'trackback_format' => "Format of Trackbacks",
	'trackback_format_desc' => "This specifies the formatting of trackbacks on the entry pages.",
	'trackback_link_format' => "Format of Trackback Link",
        'save_trackback' => 'Store Trackback',

	'saved_create' => 'Das neue Weblog wurde erstellt.',
	'saved_update' => 'Das Weblog wurde erfolgreich modifiziert.',
	'deleted' => 'Das Weblog wurde gelöscht.',
	'confirm_delete' => 'Sie wollen das Weblog %1 löschen. Sind Sie sicher?',

	'blogroll_heading' => 'Blogroll settings',
	'blogroll_id' => 'Blogrolling ID #',
    'blogroll_id_desc' => 'Optional können Sie eine <a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a>-Blogroll in Ihrem Weblog verwenden.  Blogrolling ist ein guter Dienst um eine Linkliste zu erstellen, die automatisch Updates anzeigt. Wenn Sie das nicht wollen, können Sie diese Eingabe überspringen. Andernfalls loggen Sie sich bitte bei blogrolling.com ein, schauen nach \'get code\' und suchen Sie nach Links die Ihre blogroll\' ID # beinhalten - sie sollten Aussehen wie dieses hier: 2ef8b42161020d87223d42ae18191f6d',
    'blogroll_fg' => 'Text-Farbe',
    'blogroll_bg' => 'Background-Farbe',
    'blogroll_line1' => 'Farbe Line 1',
    'blogroll_line2' => 'Farbe Line 2',
    'blogroll_c1' => 'Farbe 1',
    'blogroll_c2' => 'Farbe 2',
    'blogroll_c3' => 'Farbe 3',
    'blogroll_c4' => 'Farbe 4',
    'blogroll_c4_desc' => 'Diese Farben legen fest, wie Ihre Blogroll aussehen wird. Farbe 1  bis 4 zeigt visuell an, kurzfristig es her ist, seit ein Link upgedated wurde.',
);


$lang['upload'] = array (
	//		File Upload		\\
    'preview' => 'Vorschau der vollständigen Liste',
    'thumbs' => 'Thumbnail-Vorschau',
	'create_thumb' => '(Thumbnail erstellen)',
	'title' => 'Dateien',
	'thisfile' => 'Neue Datei hochladen:',
	'button' => 'Hochladen',
	'filename' => 'Dateiname',
	'thumbnail' => 'Thumbnail',
	'date' => 'Datum',
	'filesize' => 'Größe',
	'dimensions' => 'Breite x Höhe',		
	'delete_title' => 'Bild löschen',
    'areyousure' => 'Sind Sie sicher, dass Sie die Datei %s löschen wollen?',
	'picheader' => 'Dieses Bild löschen?',
	'create' => 'erstellen',
	'edit' => 'bearbeiten',

	'insert_image' => 'Bild einfügen',
    'insert_image_desc' => 'Um ein Bild einzufügen müssen Sie erst ein Bild hochladen oder ein bereits hochgeladenes Bild auswählen',
    'insert_image_popup' => 'Popup-Bild einfügen',
    'insert_image_popup_desc' => 'Um ein Popup-Bild einzufügen müssen Sie erst ein Bild hochladen oder ein bereits hochgeladenes Bild auswählen. Danach wählen Sie einen Text oder einen Thumbnail, welcher als Trigger für das Popup dienen soll.',
	'choose_upload' => 'hochladen',
    'choose_select' => 'oder auswählen',
	'imagename' => 'Name des Bildes',
	'alt_text' => 'Alternativer Text',
    'align' => 'Ausrichtung',
    'border' => 'Rahmen',
    'pixels' => 'Pixelstärke',
    'uploaded_as' => 'Ihre Datei wurde als \'%s\' heraufgeladen.',
    'not_uploaded' => 'Ihre Datei wurde nicht heraufgeladen. Folgende Fehler traten auf:',
    'center' => 'Zentrieren (Standard)',
	'left' => 'Links',
	'right' => 'Rechts',
	'inline' => 'Inline',		
    'notice_upload_first' => 'Sie müssen zuerst ein Bild hochladen oder auswählen',
    'select_image' => 'Bild wählen',
	'select_file' => 'Select File',

	'for_popup' => 'Für das Popup',		
	'use_thumbnail' => 'Verwende Thumbnail',		
    'edit_thumbnail' => 'Bearbeite Thumbnail',        
	'use_text' => 'Benutze Text',		
	'insert_download' => 'Insert a Download',
	'insert_download_desc' => 'To make a file download, you should upload a file, or select a previously uploaded file. Then select whether you want an icon or a text or a thumbnail that triggers the download.',
	'use_icon' => 'Use icon',
);


$lang['link'] = array (
	//		Link Insertion \\
    'insert_link' => 'Einen Link einfügen',
    'insert_link_desc' => 'Fügen Sie einen Link hinzu indem Sie einen in das Feld unten eingeben. Besucher der Seite werden diesen Text sehen, wenn sie mit der Maus über das Bild gehen.',
	'url' => 'URL',
	'title' => 'Titel',
	'text' => 'Text',
);


//		Categories		\\
$lang['category'] = array (
    'edit_who' => 'Festlegen wer in dieser Kategorie \'%s\' Artikel erstellen darf.',
	'name' => 'Name',
	'users' => 'Benutzer',
	'make_new' => 'Neue Kategorie erstellen',
	'create' => 'Kategorie erstellen',
    'canpost' => 'Bitte die Benutzer auswählen, die in dieser Kategorie Artikel verfassen dürfen',
    'same_name' => 'Eine Kategorie mit diesem Namen existiert bereits',
	'need_name' => 'Bitte geben Sie einen Namen ein',

	'allowed' => 'Erlaubt',
    'allow' => 'Erlauben',
    'denied' => 'Verweigert',
    'deny' => 'verweigern',
	'edit' => 'Kategorie bearbeiten',

	'delete' => 'Kategorie löschen', 
    'delete_desc' => 'Wählen Sie \'Ja\' aus, wenn Sie diese Kategorie löschen wollen',

    'delete_message' => 'In dieser Version wird nur der Kategoriename gelöscht, in späteren Versionen können Sie im gleichen Moment auch entscheiden, was mit den Artikel aus dieser Kategorie geschehen soll.',
	'search_index_newctitle'   => 'Index this category',
	'search_index_newcdesc'    => 'Only set to \'No\' if you do not want visitors to your site to search in this category.',
	'search_index_editcheader' => 'Index Category',
	
	'order' => 'Sorting Order',
	'order_desc' => 'Categories with a lower sorting order will appear higher in the list. If you keep all the numbers the same, they will be sorted alphabetically',
	'public' => 'Public Category',
	'public_desc' => 'If set to \'No\', this category will only be viewable for registered visitors. (applies only to live pages)',
	'hidden' => 'Hidden Category',
	'hidden_desc' => 'If set to \'Yes\', this category will be hidden in archive listings. (applies only to live pages)',
	
);


$lang['entries'] = array (
	//		Entries			\\
    'post_entry' => "Artikel veröffentlichen",
	'preview_entry' => "Vorschau",

	'first' => 'erster',
	'last' => 'letzter',
    'next' => 'nächster',
	'previous' => 'vorheriger',

    'jumptopage' => 'springe zu Seite (%num%)',
    'filteron' => 'Filter auf (%name%)',
    'filteroff' => 'Filter off',
	'title' => 'Titel',
	'subtitle' => 'Untertitel',
    'introduction' => 'Einleitung Artikel',
    'body' => 'Haupttext',
    'publish_on' => 'Veröffentlicht am',
	'status' => 'Status',
    'post_status' => 'Status des Artikels',
	'category' => 'Kategorie',
    'select_multi_cats' => '(Ctrl-Klick um mehrere Kategorien auszuwählen)',
	'last_edited' => "Zuletzt bearbeitet am",
	'created_on' => "Erstellt am",		
	'date' => 'Datum',
	'author' => 'Autor',
	'code' => 'Code',
	'comm' => '# Kommentare',
	'track' => '# Track',
	'name' => 'Name',
	'allow_comments' => 'Kommentare erlauben',

	'delete_entry' => "Delete Entry",
	'delete_entry_desc' => "Delete this Entry and the corresponding Comments ",
	'delete_one_confirm' => "Are you sure you want to delete this entry?",
	'delete_multiple_confirm' => "Are you sure you want to delete these entries?",
	
    'convert_lb' => 'Linebreaks konvertieren',
    'always_off' => '(Wenn Sie in WYSIWYG-Modus arbeiten ist dies standardmässig ausgeschaltet)',
	'be_careful' => '(Seien Sie hiermit vorsichtig!)',
	'edit_comments' => 'Kommentare bearbeiten',
	'edit_comments_desc' => 'Die Kommentare zu diesem Artikel bearbeiten',
	'edit_comment' => 'Kommentare bearbeiten',
	'delete_comment' => 'Kommentar löschen',
	'edit_trackback' => 'Edit Trackback',
	'delete_trackback' => 'Delete Trackback',
	'block_single' => 'IP %s blockieren',
    'block_range' => 'IP-Bereich %s blockieren',
	'unblock_single' => 'Blockade der IP %s aufheben',
    'unblock_range' => 'Blockade des IP-Bereiches %s aufheben',
    'trackback' => 'Trackback-Ping',
	'trackback_desc' => 'Send Trackback Pings to the following url(s). To send to multiple urls, place each one on a seperate line.',
	'keywords' => 'Keywords',
	'keywords_desc' => 'Use this to set some keywords that can be used to find this entry, or to set the non-crufty url for this entry.',
	'vialink' => "Via link",
	'viatitle' => "Via title",
	'via_desc' => 'Use this to set a link to the source of this entry.',
	'entry_catnopost' => 'You are not allowed to post in category:\'%s\'.',
	'entry_saved_ok' => 'Your entry \'%s\' was successfully saved.',
	'entry_ping_sent' => 'A trackback ping has been sent to \'%s\'.',
);


//		Form Fun		\\
$lang['forms'] = array (
    'c_all' => 'alles auswählen',
    'c_none' => 'nichts auswählen',
    'choose' => '- Wählen Sie eine Option -',
    'publish' => 'Status auf \'veröffentlicht\' setzen',
    'hold' => 'Status auf \'Noch nicht veröffentlichen\' setzen',
	'delete' => 'Löschen',
    'generate' => 'Veröffentlichen und generieren',

    'with_checked_entries' =>   "Mit den selektierten Artikel:",
    'with_checked_files' =>     "Mit den selektierten Dateien:",
    'with_checked_templates' => "Mit den selektierten Templates:",
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'Kann Verzeichnis nicht öffnen, kontrollieren Sie die Rechte.',
    'path_read' => 'Kann Verzeichnis nicht lesen, kontrollieren Sie die Rechte.',
    'path_write' => 'Kann in Verzeichnis nicht schreiben, kontrollieren Sie die Rechte.',

    'file_open' => 'Kann Datei nicht öffnen, kontrollieren Sie die Rechte.',
    'file_read' => 'Kann Datei nicht lesen, kontrollieren Sie die Rechte.',
    'file_write' => 'Kann nicht in Datei schreiben, kontrollieren Sie die Rechte.',    
);


//		Notices			\\
$lang['notice'] = array (		
    'comment_saved' => "Der Kommentar wurde gespeichert.",
    'comment_deleted' => "Der Kommentar wurde gelöscht.",
	'comment_none' => "Dieser Artikel hat keine Kommentare.",
	'trackback_saved' => "The Trackback has been saved.",
	'trackback_deleted' => "The Trackback has been deleted.",
	'trackback_none' => "This entry has no trackbacks.",
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'Wähle \'%val%\' für diesen Artikel',
	'good' => 'Gut',
	'bad' => 'Schlecht',
    'already' => 'Sie haben schon für diesen Artikel gestimmt.',
    'register' => 'Ihre Wahl für \'%val%\' wurde registriert',
);


$lang['comment'] = array (
	'register' => 'Ihr Kommentar wurde gespeichert.',
    'preview' => 'Sie sehen sich die Vorschau an, wählen Sie \'Beitrag veröffentlichen\' um den Kommentar zu speichern.',
    'duplicate' => 'Ihr Kommentar wurde nicht gespeichert, weil er identisch mit einem anderen Kommentar ist',
    'no_name' => 'Bitte geben Sie Ihren Name oder einen Alias ein. Denken Sie daran, den Kommentar zu veröffentlichen.',
    'no_comment' => 'Sie müssen im \'Kommentar\'-Eingabefeld etwas eingeben.',
	'too_many_hrefs' => 'The maximum number of hyperlinks was exceeded. Stop spamming.',
    'email_subject' => '[Comment] Re:',	
);


$lang['comments_text'] = array (
    '0' => "Kein Kommentar",
	'1' => "%num% Kommentar",
	'2' => "%num% Kommentare",
);

$lang['trackbacks_text'] = array (
	'0' => "No trackback",
	'1' => "%num% trackback",
	'2' => "%num% trackback",
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => "Archive",
	'next_archive' => "Nächstes Archiv",
	'previous_archive' => "Vorheriges Archiv",
    'last_comments' => "Neueste Kommentare",
    'last_referrers' => "Neueste Verweise:",
	'calendar' => "Kalender",
	'links' => "Links",
    'xml_feed' => "XML-Feed (RSS 1.0)",
	'atom_feed' => "XML: Atom Feed",
	'powered_by' => "Powered by",
	'blog_name' => "Weblog Name",
	'title' => "Title",
	'excerpt' => "Excerpt",
	'name' => "Name",
    'email' => "E-Mail",
	'url' => "URL",
	'date' => "Datum",		
	'comment' => "Kommentar",
	'ip' => "IP-Adresse",		
	'yes' => "Ja",
	'no' => "Nein",
	'emoticons' => "Emoticons",
    'emoticons_reference' => 'Zeige Emoticons-Übersicht',
	'textile' => 'Textile',
    'textile_reference' => 'Zeige Textile-Übersicht',
    'post_comment' => "Kommentar veröffentlichen",
	'preview_comment' => "Vorschau",
	'remember_info' => "Persönliche Informationen speichern?",
	'notify' => "Notify",
	'notify_yes' => "Yes, send me email when someone replies.",
	'register' => "Register your username / Log in",
    'disclaimer' => "Alle HTML-Tags außer &lt;b&gt; und &lt;i&gt; werden aus Ihrem Kommentar entfernt. Links erstellen Sie einfach durch Eingabe einer URL oder der Mailadresse.",    
	'search_title' => "Search Results",
    'search' => "suchen!",
    'nomatches' => "Kein Treffer für '%name%'. Bitte etwas anderes versuchen.",
    'matches' => "Treffer für '%name%':",
	'about' => "About",
	'stuff' => "Stuff",
	'linkdump' => "Linkdump",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
    'title' => 'Benutzerfelder bearbeiten',
	'edit' => 'bearbeiten',
	'create' => 'erstellen',

	'dispname' => 'Name anzeigen',
    'intname' => 'Interner Name',
    'intname_desc' => 'Der interne Name ist der Name, der erscheint, wenn Sie Pivot sagen er soll ihn in einem Template anzeigen',
	'size' => 'Größe',
	'rows' => 'Reihen',
	'cols' => 'Spalten',
	'maxlen' => 'Maximale Länge',
    'minlevel' => 'Minimaler Benutzer-Level',    
    'filter' => 'Filter auf',
    'filter_desc' => 'Mit dieser Option können Sie die Art der Eingabe eingrenzen',
	'no_filter' => 'kein Filter',
    'del_title' => 'Löschen bestätigen',
    'del_desc' => 'Durch das Löschen dieses Benutzerfeldes (<b>%s</b>) werden auch alle Daten die von Benutzern hier eingegeben wurden gelöscht sowie alle Stellen im Template wo auf dieses Eingabefeld zurückgegriffen wird.',    
	
	'already' => 'Der Name wird bereits benutzt.',
    'int' => 'Der interne Name muss länger als 3 Zeichen sein',
	'short_disp' => 'Der angezeigte Name muss länger als 3 Zeichen sein',	
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'Bookmarklets',
	'bm_add' => 'Add Bookmarklet.',
    'bm_withlink' => 'Piv » Neu',
    'bm_withlink_desc' => 'Dieses Bookmarklet öffnet ein neues Fenster bereit zur Eingabe eines neuen Artikels inklusive eines Links auf die Seite, von der es geöffnet wurde.',

    'bm_nolink' => 'Piv » Neu',
    'bm_nolink_desc' => 'Dieses Bookmarklet öffnet ein Fenster mit einem leeren neuen Artikel.',

    'bookmarklets_info' => 'Bookmarklets können zum schnellen Erstellen von neuen Artikeln verwendet werden. Um ein Bookmarklet zu Ihrem Browser hinzuzufügen verwenden Sie bitte eien der folgenden Optionen: (Text kann unterschiedlich je nach verwendetem Browser lauten)',
    'bookmarklets_info_1' => 'Ziehen Sie mit einem Klick das Bookmarklet auf Ihre Bookmark-Symbolleiste in Ihrem Browsers.',
    'bookmarklets_info_2' => 'Klicken Sie mit der rechten Maustaste auf das Bookmarklet udn wählen \'Bookmark (Lesezeichen) hinzufügen\'.',
);

// Accessibility - These are used for form fields, labels, fieldsets etc.
// for Web Content Accessibility Guidelines & 508 compliancy issues.
// see: http://bobby.watchfire.com/bobby/html/en/index.jsp
// JM =*=*= 2004/10/04
// 2004/11/25 =*=*= JM - minor correction for tim
$lang['accessibility'] = array(
	'search_idname'      => 'search',
	'search_formname'    => 'Search for words used in entries on this website',
	'search_fldname'     => 'Enter the words[s] to search for here:',
	'search_placeholder' => 'Enter searchterms',

	'calendar_summary'   => 'This table represents a calendar of entries in the weblog with hyperlinks on dates with entries.',
	'calendar_noscript'  => 'The calendar provides a means to access entries in this weblog',
	/* 
	2-letter language code, used to designate the principal language used on the site
	see: http://www.oasis-open.org/cover/iso639a.html
	*/

	'lang' => $langname,
) ;


$lang['snippets_text'] = array (
    'word_plural'     => 'words',
    'image_single'    => 'image',
    'image_plural'    => 'images',
    'download_single' => 'file',
    'download_plural' => 'files',
); 

$lang['trackback'] = array (
    'register' => 'Your trackback has been stored.',
    'duplicate' => 'Your trackback has not been stored, because it seems to be a duplicate of a previous entry',
    'too_many_hrefs' => 'The maximum number of hyperlinks was exceeded. Stop spamming.',
    'noid'      => 'No TrackBack ID (tb_id)',
    'nourl'     => 'No URL (url)',
    'tracked'   => 'Tracked',
    'email_subject' => '[Trackback] new Trackback',
);

$lang['commentuser'] = array (
    'title'             => 'Pivot user login',
    'header'            => 'Log in as a registered visitor',
    'logout'            => 'Log out.',
    'loggedout'         => 'Logged out',
    'login'             => 'Login',
    'loggedin'          => 'Logged in',
    'loggedinas'        => 'Logged in as',
    'pass_forgot'       => 'Forgotten your password?',
    'register_new'      => 'Register a new username.',
    'register'          => 'Register as a visitor',
    'register_info'     => 'Please fill out the following information. <strong>Be sure to give a valid email address</strong>, because we will send a verification email to that address.',
    'pass_note'         => 'Note: It\'s possible for the maintainer of this site <br /> to see your password.. Do <em>not</em> use a password<br /> that you use for other websites / accounts!',
    'show_email'        => 'Show my email address with comments',
    'notify'            => 'Notify me via email of new entries',
    'def_notify'        => 'Default notification of replies',
    'register'          => 'Register',
    'pass_invalid'      => 'Incorrect password',
    'nouser'            => 'No such user..',
    'change_info'       => 'Here you can change your information.',
    'pref_edit'         => 'Edit your preferences',
    'pref_change'       => 'Change preferences',
    'options'           => 'Options',
    'user_exists'       => 'User already exists.. Please pick another name.',
    'email_note'        => 'You must give your email address, since it\'ll be impossible to verify your account. You can always choose not to show your address to other visitors.',
    'stored'            => 'The changes have been stored',
    'verified'          => 'Your account is verified. Please log in..',
    'not_verified'      => 'That Code seems to incorrect. I\'m sorry, but I can\'t verify.',
    'pass_sent'         => 'Your password was sent to the mailbox given..',
    'user_pass_nomatch' => 'That username and email address do not seem to match.',
    'pass_send'         => 'Send password',
    'pass_send_desc'    => 'If you\'ve forgotten your password, fill in your username and e-mail address, and Pivot will send your password to your email address. ',
    'oops'              => 'Oops',
    'back'              => 'Back to',
    'back_login'        => 'Back to login',
    'forgotten_pass_mail' => "Your forgotten password for Pivot '%name%' is: \n\n%pass%\n\nDon't forget it again, please!\n\nTo log in to your account, click the following link:\n %link%"
);

// A little tool to help you check if the file is correct..
if (count(get_included_files())<2) {

	$groups = count($lang);
	$total = 0;
	foreach ($lang as $langgroup) {
		$total += count($langgroup);
	}
	echo "<h2>Language file is correct!</h2>";
	echo "This file contains $groups groups and a total of $total labels.";

}

?>