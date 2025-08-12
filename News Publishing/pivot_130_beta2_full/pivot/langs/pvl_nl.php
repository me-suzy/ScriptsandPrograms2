<?php
//Nederlands (Dutch)

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2rd line.

// Dutch translation of Pivot lang file
// Translated by: Sander <sander@creativefocus.nl> (www.creativefocus.nl)
// Updated by: Marius 't Hart <marius@a.rug.nl> (www.ai.rug.nl/~marius/weblog)
// Updated by: Marcel "MadJo" de Jong <marcel@madjo.net> (www.madjo.net/pivot/)
// Last updated by Pivot Dev. (www.pivotlog.net) 25.04.2005
//

// the correct encoding is used for non-western languages
$encoding="iso-8859-1";
$langname="nl";


//		General		\\
$lang['general'] = array (
	'yes' => 'Ja',	//affirmative
	'no' => 'Nee',		//negative
	'go' => 'Verder',	//proceed

	'minlevel' => 'Je hebt geen toegang tot dit gedeelte van Pivot',
	'email' => 'Email',
	'url' => 'URL',
	'further_options' => "Overige Opties",
	'basic_view' => "Standaard View",
	'basic_view_desc' => "Bekijk alleen de standaard velden",
	'extended_view' => "Extended View",
	'extended_view_desc' => "Bekijk alle velden",
	'select' => "Selecteer",
	'cancel' => "Annuleer",
	'delete' => 'Verwijderen',
	'welcome' => "Welkom bij %build%.",
	'write' => "Schrijf",
	'write_open_error' => "Write Error. Could not open file for writing",
	'write_write_error' => "Write Error. Could not write to file",
	'done' => "Klaar!",
	'shortcuts' => "Snelkoppelingen",
	'cantdelete' => "Het is niet toegestaan om artikel %title% te verwijderen!",
	'cantdothat' => "Het is niet toegestaan om dat met artikel %title% te doen!",
	'cantdeletelast' => "You can not delete the last entry. You must first post a new entry, before deleting this entry",
);


$lang['userlevels'] = array (
		'Superadmin', 'Administrator', 'Advanced', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin,
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array(
	'nul', 'een', 'twee', 'drie', 'vier', 'vijf', 'zes', 'zeven', 'acht', 'negen', 'tien', 'elf', 'twaalf', 'dertien', 'veertien', 'vijftien', 'zestien'
);


$lang['months'] = array (
	'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'
);


$lang['months_abbr'] = array (
	'Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'
);


$lang['days'] = array(
	'Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'
);


$lang['days_abbr'] = array(
	'Zon', 'Ma', 'Di', 'Woe', 'Do', 'Vrij', 'Zat'
);


$lang['days_calendar'] = array (
	'Z', 'M', 'D', 'W', 'D', 'V', 'Z'
);


$lang['datetime_words'] = array(
	'Jaar', 'Maand', 'Week', 'Dag', 'Uur', 'Minuut', 'Seconde'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'Login',
	'name' => 'Gebruikersnaam',
	'pass' => 'Wachtwoord',
	'remember' => 'Onthoud',
	'rchoice' => array (
		'0' => 'Niks',
		'1' => 'Mijn gebruikersnaam en wachtwoord',
		'2' => 'Dat ik ingelogd wil blijven'
	),
	'delete_cookies_desc' => 'If you are certain you\'re using the correct username and password, but you are <br />having problems logging in, you might try deleting the cookies for this domain:',
	'delete_cookies' => 'Delete cookies',
	'retry' => 'Foutieve gebruikersnaam/wachtwoord',
	'banned' => 'In 10 pogingen heb je geen toegang tot het systeem gekregen. Daarom kan vanaf dit IP gedurende 12 uur niet worden ingelogd.',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => 'Overzicht',
	'entries' => 'Artikelen',
	'submit' => 'Nieuw Artikel',
	'comments' => 'Reacties',
	'trackbacks' => 'Trackbacks',
	'modify' => 'Bewerk Reacties',
	'userinfo' => 'Mijn Info',
	'u_settings' => 'Mijn Instellingen',
	'u_marklet' => 'Bookmarklets',
	'files' => 'Mediabeheer',
	'upload' => 'Upload',
	'stats' => 'Statistieken',
	'admin' => 'Administratie',

	'main_title' => 'Algemeen overzicht van Pivot',
	'entries_title' => 'Overzicht van de Artikelen',
	'submit_title' => 'Schrijf en publiceer een nieuw Artikel',
	'comments_title' => 'Bewerk of verwijder reacties',
	'trackbacks_title' => 'Bewerk of verwijder Trackbacks',
	'modify_title' => 'Bewerk Artikel',
	'userinfo_title' => 'Bekijk mijn persoonlijke informatie',
	'u_settings_title' => 'Bewerk mijn persoonlijke instellingen',
	'u_marklet_title' => 'Create Bookmarklets',
	'files_title' => 'Bestandsbeheer en upload nieuwe bestanden',
	'upload_title' => 'Upload bestanden',
	'uploaded_success' => 'Bestand is geupload',
	'stats_title' => 'Bekijk Logs en Statistieken.',
    'updatetitles_title' => 'Bekijk Logs en Statistieken.',
	'admin_title' => 'Overzicht van de Administratie',
	'recent_entries' => 'Recent Entries',
    'recent_comments' => 'Recente Reacties',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
	//'trebuild' => 'Rebuild all Files', rolled into maintenance
	'seeusers' => 'Gebruikers',
	'seeconfig' => 'Configuratie',
	'filemappings' => 'File Mappings',
	'templates' => 'Templates',
	'maintenance' => 'Onderhoud',
	'regen' => 'Rebuild alle bestanden',
	'blogs' => 'Weblogs',
	'categories' => 'Categorie&euml;n',
	'verifydb' => 'Controleer de database',
	'buildindex' => 'Rebuild de Index',
    'buildsearchindex' => 'Rebuild de Zoekindex',
	'buildfrontpage' => 'Rebuild de voorpagina',
	'sendping' => 'Stuur Pings',


	'backup' => 'Backup',
	'description' => 'Beschrijving',
	'conversion' => 'Conversie',
	'seeusers_title' => 'Gebruikers aanmaken, bewerken en verwijderen',
	'userfields' => 'Gebruikersvelden',
	'userfields_title' => 'Gebruikersvelden aanmaken, bewerken en verwijderen',
	'seeconfig_title' => 'Bewerk Configuratiebestand',
	'filemappings_title' => 'Laat een overzicht zien van welke bestanden Pivot aanmaakt voor de verschillende weblogs',
	'templates_title' => 'Templates aanmaken, bewerken en verwijderen',
	'maintenance_title' => 'Voer standaard onderhoud uit op Pivot\'s files',
	'regen_title' => 'Rebuild de bestanden en archieven die Pivot genereert.',
	'blogs_title' => 'Weblogs aanmaken, bewerken en verwijderen',
	'blogs_edit_title' => 'Bewerk weblog instellingen voor ',
	'categories_title' => 'Categorie&euml;n aanmaken, bewerken en verwijderen',
	'verifydb_title' => 'Controleer de integriteit van je Database',
	'buildindex_title' => 'Rebuild de index van je Database',
    'buildsearchindex_title' => 'Rebuild de Zoekindex, zodat er in artikelen gezocht kan worden',
	'buildfrontpage_title' => 'Maak voor elk weblog opnieuw de voorpagina, laatste archieven en RSS bestanden aan.',
	'backup_title' => 'Maak een backup van je artikelen',
	'backup_config' => 'Maak een backup van de configuratie',
	'backup_config_desc' => 'Hiermee kun je een zipfile downloaden die je configuratie files bevat',
	'ipblocks' => 'IP blocks',
    'ipblocks_title' => 'Geblokkeerde IP addressen bewerken en bekijken.',
    'ipblocks_stored' => 'De IP-adressen zijn opgeslagen.',
    'ipblocks_store' => 'Sla deze IP-adressen op',
	'ignoreddomains' => 'Te blokkeren woorden',
	'ignoreddomains_title' => 'Bekijk de te blokkeren woorden om referrer spam tegen te gaan.',
	'ignoreddomains_stored' => 'De geblokkeerde woorden zijn opgeslagen.',
	'ignoreddomains_store' => 'Sla de te blokkeren woorden op',
	'ignoreddomains_asterisk' => 'Items gemarkeerd met een sterretje ( * ) worden alleen gebruikt om referers te blocken. Alle andere items will be gebruikt voor zowel referers, comments als trackbacks.',
	'fileexplore' => 'Bestandsbeheer',
	'fileexplore_title' => 'Bekijk bestanden (zowel tekst- als database bestanden)',
	'sendping_title' => 'Stuur Pings naar Update Trackers.',
    'buildindex_start' => 'Index wordt nu opgebouwd. Dit kan even duren... Niet onderbreken a.u.b.',
    'buildsearchindex_start' => 'Zoekindex wordt nu opgebouwd. Dit kan even duren... Niet onderbreken a.u.b.',
	'buildindex_finished' => 'Opnieuw samenstellen van de index duurde %num% seconden',

	'filemappings_desc' => 'Hieronder zie je een overzicht van alle weblogs in deze Pivot installatie, samen met de bestanden die door Pivot zijn gemaakt en de templates die gebruikt zijn om de bestanden te maken. Dit kan handig zijn om problemen op te lossen bij het maken van de bestanden.',

	'debug' => 'Open Debug window',

);


$lang['templates'] = array (
	'rollback' => 'Rollback',
	'create_template' => 'Template aanmaken',
	'create_template_info' => 'Leeg template aanmaken',
	'no_comment' => 'Geen reactie',
	'comment' => 'Reactie*',
	'comment_note' => '(*Let op: Commentaar kan alleen worden opgeslagen bij de <b>eerste</b> keer opslaan of wijzigen)',
	'create' => 'Template aanmaken',
	'editing' => 'Bewerken',
	'filename' => 'Bestandsnaam',
	'save_changes' => 'Veranderingen Opslaan!',
	'save_template' => 'Template Opslaan!',
	'aux_template' => 'Auxillary template',
	'sub_template' => 'Subtemplate',
	'standard_template' => 'Normal template',
	'feed_template' => 'Feed template',
	'css_template' => 'CSS file',
	'txt_template' => 'Text file',
	'php_template' => 'PHP file',
);


//		Admin			\\
// bob notes: Mark made these, i think they should be replaced by the 'adminbar']['xxx_title'] ones
$lang['admin'] = array (
	'seeusers' => 'Gebruikers aanmaken, bewerken en verwijderen',
	'seeconfig' => 'Configuratiebestand bewerken',
	'templates' => 'Templates aanmaken, bewerken en verwijderen',
	'maintenance' => 'Standaard Pivot onderhoudsbewerkingen uitvoeren, zoals \'Rebuild bestanden\', \'Controleer de Database\', \'Rebuild de Index\' en \'Backup\'.',
	'regen' => 'Alle paginas opnieuw genereren',
	'blogs' => 'Weblogs aanmaken, bewerken en verwijderen',
);


//		Maintenace		\\
$lang['maint'] = array (
	'title' => 'Onderhoud',
	'gen_arc_title' => 'Genereer Archief', /* bob notes: redundant, see 'regen' */
	'gen_arc_text' => 'Alle archieven opnieuw genereren', /* bob notes: redundant, see 'regen' */
	'xml_title' => 'Controleer XML-bestanden', /* bob notes: replace with more general 'Verify DB' */
	'xml_text' => 'Controleer (en repareer waar nodig) de integriteit van de XML-bestanden', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'Backup',
	'backup_text' => 'Maak een backup van alle belangrijke pivot-bestanden',
);


//		Stats and referers		\\
$lang['stats'] = array (
	'show_last' => "Toon de laatste",
	'20ref' => "20 referrers",
	'50ref' => "50 referrers",
	'allref' => "alle referrers",
	'showunblocked' => "alleen niet geblokkeerde regels",
	'showall' => "zowel geblokkeerde als niet geblokkeerde",
	'updateref' => "Update de referer-naar-title verwijzingen",
	'hostaddress' => 'Host-adres (ip-adres)',
	'whichpage' => 'welke pagina',

	'getting' => 'Bezig met verzamelen nieuwe titels',
	'awhile' => 'Dit kan even duren.. Niet onderbreken a.u.b.',
	'firstpass' => 'Eerste fase',
	'secondpass' => 'Tweede fase',
	'nowuptodate' => 'Je referrer naar titles conversie is up to date.',
	'finished' => 'Voltooid',
);


//		User Info		\\
	$lang['userinfo'] = array (
	'editfields' => 'Bewerk Gebruikers velden',
	'desc_editfields' => 'Bewerk de velden die de gebruiker kan gebruiken om zichzelf te beschrijven',
	'username' => 'Gebruikersnaam',
	'pass1' => 'Wachtwoord',
	'pass2' => 'Wachtwoord (nogmaals)',
	'email' => 'Email',
	'nickname' => 'Bijnaam',
	'userlevel' => 'Level van Gebruiker',
	'userlevel_desc' => 'Het level van de gebruiker geeft aan welke bewerkingen deze mag uitvoeren op Pivot',
	'language' => 'Taal',
	'lastlogin' => 'Laatste login',
    'edituser' => 'Wijzig Gebruiker', //the link to.. well, edit the user (also the title)
    'edituserinfo' => 'Wijzig Gebruikers Info',
	'newuser' => 'Nieuwe gebruiker aanmaken',
	'desc_newuser' => 'Maak een nieuw account aan in Pivot, om zo de gebruiker artikelen te laten posten.',
	'newuser_button' => 'Aanmaken',
	'edituser_button' => 'Veranderen',
	'pass_too_short' => 'Wachtwoord moet minimaal 4 tekens lang zijn.',
	'pass_dont_match' => 'Wachtwoorden komen niet overeen',
	'pass_equal_name' => 'Wachtwoord mag niet gelijk zijn aan de gebruikersnaam.',
	'username_in_use' => 'Gebruikersnaam al bezet',
	'username_too_short' => 'Gebruikersnaam moet minimaal 3 tekens lang zijn.',
	'username_not_valid' => 'Gebruikersnamen kunnen slechts alphanumerieke tekens (a-z, A-Z, 0-9) en underscores (_) bevatten.',
	'not_good_email' => 'Dat is geen geldig emailadres',
	'c_admin_title' => 'Bevestig het aanmaken van een administator',
	'c_admin_message' => 'Een '.$lang['userlevels']['1'].' heeft volledige toegang tot pivot, kan alle items en reacties bewerken, en kan alle instellingen wijzigen. Weet je zeker dat je %s een  '.$lang['userlevels']['1'].' wilt maken?',
);


//		Config Page		\\
	$lang['config'] = array (
	'save' => 'Instellingen bewaren.',

	'sitename' => 'Naam website',
	'defaultlanguage' => 'Standaard taal',
	'defaultencoding' => 'Gebruik encoding',
	'defaultencoding_desc' => 'Dit bepaalt encoding die gebruikt wordt (zoals utf-8 of iso-8859-1). Deze kun je het beste leeg laten, tenzij je weet wat je doet. Als je deze leeg laat wordt de juiste encoding gebruikt, zoals bepaald in de language file.',
	'siteurl' => 'Website URL',
	'header_fileinfo' => 'Bestands informatie',
	'localpath' => 'Lokaal pad',
	'debug' => 'Debug modus',
	'debug_options' => 'Debug Opties',
	'debug_desc' => 'Geef willekeurige Debug informatie weer (hier en daar)',
	'log' => 'Logfiles',
    'log_desc' => 'Houd logbestanden bij van verscheidene activiteiten.',

	'unlink' => 'Unlink Files',
	'unlink_desc' => 'Op sommige servers waar de gruwelijke safe_mode aanstaat moet je met deze optie spelen om je log het best te laten werken. Op de meeste servers heeft deze optie geen effect.',
	'chmod' => 'Chmod Files To',
	'chmod_desc' => 'Bij sommige servers moeten de bestanden die gemaakt worden in een bepaalde chmod gezet worden. Gebruikelijke waardes zijn \'0644\' en \'0755\'. Verander hier niets aan, tenzij je weet wat je doet.',
	'header_uploads' => 'Bestands uploaden configuratie',
	'upload_path' => 'Upload Pad',
	'upload_accept' => 'Toegestane bestandsformaten',
	'upload_extension' => 'Standaard Extensie',
	'upload_save_mode' => 'Overschrijven',
	'make_safe' => 'Kale bestandsnaam',
	'c_upload_save_mode' => 'Incrementele bestandsnaam',
	'max_filesize' => 'Maximum bestandsgrootte',
	'header_datetime' => 'Datum/Tijd',
	'timeoffset_unit' => 'Tijd Offset Eenheid',
	'timeoffset' => 'Tijd Offset',
	'header_extra' => 'Overige instellingen',
    'wysiwyg' => 'WYSIWYG-editor gebruiken',
	'wysiwyg_desc' => 'Bepaalt of je de Wysiwyg-editor standaard aan of uit wil hebben. Alle gebruikers kunnen dit zelf bepalen in hun instellingen',
	'basic_view' => 'Gebruik Basic View',
	'basic_view_desc' => 'Bepaalt of het \'Nieuwe Artikel\' scherm in \'Basic View\' of in \'Extended View\' opent.',
	'def_text_processing' => 'Standaard Tekstverwerking',
    'text_processing' => 'Tekstverwerking',
    'text_processing_desc' => 'Bepaalt de standaard tekstverwerking, als een gebruiker de non-wysiwyg editor gebruikt. \'Converteer Regeleinden naar &lt;br&gt;\' doet niet meer dan het veranderen van regeleinden naar een &lt;br&gt;-tag. <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> is een krachtige, en toch eenvoudig te leren opmaakstijl.',
    'none' => 'Geen',
    'convert_br' => 'Converteer Regeleinden naar &lt;br /&gt;',
	'textile' => 'Textile',
	'markdown' => 'Markdown',
	'markdown_smartypants' => 'Markdown and Smartypants',

	'allowed_cats' => 'Toegestane Categorieen',
	'allowed_cats_desc' => 'De gebruker mag artikelen plaatsen in deze categorieen',
	'delete_user' => "Verwijder gebruiker",
	'delete_user_desc' => "Je kunt nu deze gebruiker verwijderen. Al zijn/haar artikelen blijven bewaard, maar de gebruiker kan niet meer inloggen",
	'delete_user_confirm' => 'Je staat op het punt om %s te verwijderen. Weet je het zeker?',

	'setup_ping' => 'Ping instellingen',
	'ping_use' => 'Ping update trackers',
	'ping_use_desc' => 'Bepaalt of update trackers zoals weblogs.com automatisch op de hoogte worden gesteld door Pivot wanneer er een nieuwe post is. Diensten als blogrolling.com hebben pings nodig.',
	'ping_urls' => 'URLs to ping',
	'ping_urls_desc' => 'Je kunt een aantal URLs invullen om te pingen. Zet het http:// deel er niet bij, dan werkt het niet. Zet iedere server op een aparte regel of gescheiden door pipe-tekens. Hier zijn een paar bekende diensten:  <br /><b>rpc.weblogs.com/RPC2</b> (weblogs.com pinger, meest gebruikt)<br /><b>pivotlog.net/pinger</b> (pivotlog pinger, nog niet functionerend)<br /><b>rcs.datashed.net/RPC2</b> (euro.weblogs.com pinger)<br /><b>ping.blo.gs</b> (blo.gs pinger)<br />',

	'setup_tb' => 'Trackback Setup',
	'tb_email' => 'Email',
	'tb_email_desc' => 'Als je een mailadres opgeeft, worden naar dit adres meldingen van trackbacks gestuurd.',

	'new_window' => 'Links in nieuw venster openen',
	'emoticons' => 'Emoticons gebruiken',
	'javascript_email' => 'Emailadressen coderen',
	'new_window_desc' => 'Bepaalt of je alle links in een nieuw venster wil openen.',

	'mod_rewrite' => 'Mod_rewrite gebruiken',
	'mod_rewrite_desc' => 'Als je Apache\'s Mod_rewrite optie gebruikt, zal Pivot urls genereren als www.mysite.com/archive/2003/05/30/nice_weather, in plaats van www.mysite.com/pivot/entry.php?id=134. Dit wordt niet door alle servers ondersteund, raadpleeg daarom eerst de handleiding.',
	'mod_rewrite_1' => 'Yes, like /archive/2005/04/28/title_of_entry',
	'mod_rewrite_2' => 'Yes, like /archive/2005-04-28/title_of_entry',
	'mod_rewrite_3' => 'Yes, like /entry/1234',
	'mod_rewrite_4' => 'Yes, like /entry/1234/title_of_entry',

	'search_index' => 'Autoupdate Zoek Index',
	'search_index_desc' => 'Dit bepaalt of de zoekindex altijd wordt bijgewerkt als je een nieuw artikel plaatst, of een artikel wijzigt.',

	'default_allow_comments' => 'Standaard reacties toestaan',
	'default_allow_comments_desc' => 'Bepaalt of bij nieuwe artikelen standaard de optie voor reacties aan of uit staat.',

  'maxhrefs' => 'Aantal links',
  'maxhrefs_desc' => 'Maximale aantal hyperlinks toegestaan in reacties. Handig tegen reactie-spam. Zet op 0 voor oneindig.',
  'rebuild_threshold' => 'Rebuild drempel',
  'rebuild_threshold_desc' => 'Het aantal seconden dat rebuilden maximaal duurt, voordat Pivot de pagina ververs. De standaard is 28 seconden, maar als je problemen met het rebuilden hebt, probeer dit getal dan te verlagen tot bijvoorbeeld 10.',
    'default_introduction' => 'Standaard Introductie/Body',
    'default_introduction_desc' => 'Dit bepaalt de standaard waarden voor Introductie en Body, wanneer een auteur een nieuw artikel schrijft. Normaal is dit een lege paragraaf, wat het meest voor de hand ligt, semantisch gezien.',

    'upload_autothumb' => 'Automatische Thumbnails?',
    'upload_thumb_width' => 'Thumbnail breedte',
    'upload_thumb_height' => 'Thumbnail hoogte',
    'upload_thumb_remote' => 'Remote cropping script',
    'upload_thumb_remote_desc' => 'Als je server niet de nodige bibliotheekbestanden geinstalleerd heeft om plaatjes te kunnen verkleinen, kun je een remote cropping script gebruiken.',

	'extensions_header' => 'Extensions directory',
	'extensions_desc'   => 'The \'extensions\' directory is the place to store your additions to Pivot.
		This makes updating a lot easier. See the Docs for more info.',
	'extensions_path'   => 'Extensions directory path',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'Weblog bewerken',
	'edit_blog' => 'Weblogs bewerken',
	'new_weblog' => 'Nieuw Weblog',
	'new_weblog_desc' => 'Nieuw weblog toevoegen',
	'del_weblog' => 'Weblog verwijderen',
	'del_this_weblog' => 'Dit weblog verwijderen.',
	'create_new' => 'Nieuw Weblog aanmaken',
	'subw_heading' => 'Voor elk subweblog dat gevonden werd in de templates kun je instellen welk template ze gebruiken, evenals welke categorie&euml;n weergegeven worden.',
	'create' => 'Afronden',

	'create_1' => 'Aanmaken / Wijzigen Weblog, stap 1 van 3',
	'create_2' => 'Aanmaken / Wijzigen Weblog, stap 2 van 3',
	'create_3' => 'Aanmaken / Wijzigen Weblog, stap 3 van 3',

	'name' => 'Weblog naam',
	'payoff' => 'Payoff',
	'payoff_desc' => 'De Payoff kan worden gebruikt voor een subtitel of slogan',
	'url' => 'URL naar Weblog',
	'url_desc' => 'Pivot bepaalt zelf de url als je dit veld niet invult. Als je weblog bijvoorbeeld onderdeel uitmaakt van een frameset, kun je dat hier aangeven.',
	'index_name' => 'Voorpagina (Index)',
	'index_name_desc' => 'De bestandsnaam van de index-file. Meestal iets als \'index.html\' of \'index.php\'.',

	'ssi_prefix' => 'SSI Prefix',
	'ssi_prefix_desc' => 'Als je weblog SSI gebruikt (dat wordt niet aangeraden kun je dit gebruiken om de bestandsnaam die voor de SSI gebruikt wordt voor de bestandsnamen van Pivot te plaatsen. Bijvoorbeeld: \'index.shtml?p=\'. Laat dit leeg, tenzij je weet wat je doet.',

	'front_path' => 'Pad naar Voorpagina',
	'front_path_desc' => 'Het relatieve of absolute pad waar Pivot de index-pagina genereert.',
	'file_format' => 'Bestandsnaam',
	'entry_heading' => 'Artikel Instellingen',
	'entry_path' => 'Artikel Pad',
	'entry_path_desc' => 'Het relatieve of absolute pad waar Pivot de artikel-pagina\'s genereert.',
	'live_comments' => 'Live artikelen',
	'live_comments_desc' => 'Als je \'Live artikelen\' gebruikt, zal Pivot geen pagina per afzonderlijke entry genereren. Deze instelling heeft de voorkeur.',
	'readmore' => '\'Lees meer\' Tekst',
	'readmore_desc' => 'De tekst die gebruikt wordt om aan te geven dat er meer tekst is dan dat er weergegeven wordt.',

	'arc_heading' => 'Archief Settings',
	'arc_index' => 'Index bestand',
	'arc_path' => 'Archief Pad',
	'archive_amount' => 'Archief Hoeveelheid',
	'archive_unit' => 'Archief Eenheid',
	'archive_format' => 'Archief Formaat',
	'archive_none' => 'Geen Archieven',
	'archive_weekly' => 'Archieven per week',
	'archive_monthly' => 'Archieven per maand',
	'archive_yearly' => 'Archieven per jaar',

	'archive_link' => 'Archief Link',
	'archive_linkfile' => 'Archief Linkbestand',
    'archive_order' => 'Archief volgorde',
    'archive_ascending' => 'Oplopend (oudste eerst)',
    'archive_descending' => 'Aflopend (nieuwste eerst)',

	'templates_heading' => 'Templates',
	'frontpage_template' => 'Voorpagina Template',
	'frontpage_template_desc' => 'De Template die de layout van de voorpagina bepaalt.',
	'archivepage_template' => 'Archiefpagina Template',
	'archivepage_template_desc' => 'De Template die de layout van je archieven bepaalt. Dit kan dezelfde zijn als je \'Voorpagina Template\'.',
	'entrypage_template' => 'Artikel Template',
	'entrypage_template_desc' => 'De Template die de layout van je afzonderlijke artikelen bepaalt.',
	'extrapage_template' => 'Extra Template',
	'extrapage_template_desc' => 'The Template that defines what your archive and search.php will look like.',

	'shortentry_template' => 'Shortentry Template',
	'shortentry_template_desc' => 'De Template die de layout van de afzonderlijke artikelen bepaalt, zoals ze weergegeven worden in je weblog of de archieven.',
	'num_entries' => 'Aantal artikelen',
	'num_entries_desc' => 'Het aantal artikelen dat wordt weergegeven op de voorpagina.',
	'offset' => 'Offset',
	'offset_desc' => 'Als voor offset een waarde wordt ingevuld, dan worden bij het genereren van de pagina zoveel artikelen overgeslagen. Hiermee kun je dus bijv. een lijstje met \'Eerdere artikelen\' maken.',
	'comments' => 'Reacties toestaan?',
	'comments_desc' => 'Bepaalt of bezoekers reacties mogen plaatsen bij de artikelen',

	'publish_cats' => 'Publish these categories',

	'setup_rss_head' => 'RSS en Atom Configuratie',
	'rss_use' => 'Genereer Feeds',
	'rss_use_desc' => 'Bepaalt of Pivot automatisch een RSS- en Atom-feed voor je pagina genereert.',
	'rss_filename' => 'RSS bestandsnaam',
	'atom_filename' => 'Atom bestandsnaam',
	'rss_path' => 'Feed Pad',
	'rss_path_desc' => 'Het relatieve of absolute pad naar de Feed bestanden.',
//	'rss_size' => 'Feed Artikel Lengte',
//	'rss_size_desc' => 'De lengte (in tekens) van afzonderlijke artikelen in de Feed bestanden.',
	'rss_full' => 'Maak volle feeds',
	'rss_full_desc' => 'Bepaalt of Pivot volledige Atom- en RSS-feeds maakt. Als je hier voor \'nee\' kiest, maakt Pivot feeds met slechts een korte beschrijving, waardoor je feeds minder nuttig worden.',
	'rss_link' => 'Feed Link',
	'rss_link_desc' => 'De link om met de Feed mee te sturen. Als je deze leeg laat, stuurt Pivot de link naar de index mee.',
	'rss_img' => 'Feed Afbeelding',
	'rss_img_desc' => 'Je kunt een afbeelding meesturen met de Feed. Sommige Feedreaders laten deze afbeelding zien bij je Feed. Laat dit veld leeg, of vul er een volledige URL in.',

	'lastcomm_head' => 'Instellingen voor Laatste Reacties',
	'lastcomm_amount' => 'Aantal tonen',
	'lastcomm_length' => 'Afbreken na lengte',
	'lastcomm_format' => 'Opmaak',
	'lastcomm_format_desc' => 'Deze instellingen bepalen het uiterlijk  van de \'laatste reacties\' op de weblog\'s voorpagina',
	'lastcomm_nofollow' => 'Gebruik \'Nofollow\'',
	'lastcomm_nofollow_desc' => 'Om refererspam te voorkomen kun je aan alle uitgaande links in reacties en referers een attribuut rel="nofollow" meegeven, omdat dit de spammer niet helpt om een hogere ranking bij Google te krijgen.',

	'lastref_head' => 'Instellingen voor Laatste Referers',
	'lastref_amount' => 'Aantal tonen',
	'lastref_length' => 'Afbreken na lengte',
	'lastref_format' => 'Opmaak',
	'lastref_format_desc' => 'Deze instellingen bepalen het uiterlijk  van de \'laatste referers\' op de weblog\'s voorpagina',
	'lastref_graphic' => 'Gebruik graphics',
	'lastref_graphic_desc' => 'Deze instelling bepaalt of de laatste referers kleine iconen gebruiken voor de meeste zoekmachines waarmee bezoeker mogelijke op de pagina komen.',
	'lastref_redirect' => 'Redirect Referers',
	'lastref_redirect_desc' => 'Om refererspam te voorkomen kun je voor een redirect op uitgaande links in de commentaren kiezen, omdat dit de spammer niet helpt om een hogere ranking bij Google te krijgen.',

	'various_head' => 'Overige Instellingen',
	'emoticons' =>  'Emoticons gebruiken',
	'emoticons_desc' => 'Bepaalt of emoticons zoals :-) worden omgezet naar smileys.',
	'encode_email_addresses' => 'Email Addressen Encoderen',
	'encode_email_addresses_desc' => 'Bepaalt of emailadressen worden gecodeerd, tegen spam-robots.',
	'target_blank' => 'Target Blank',
	'xhtml_workaround' => 'XHTML Workaround',
	'target_blank_desc' => 'Indien \'Ja\', zullen alle links in artikelen worden geopend in een nieuw venster.',

	'date_head' => 'Datum weergave instellingen',
	'full_date' => 'Volledige datum formaat',
	'full_date_desc' => 'Bepaalt de syntax van een volledige datum.',
	'entry_date' => 'Artikel Datum',
	'diff_date' => 'Afwijkende Datum',
	'diff_date_desc' => 'Dit wordt meestal samen met \'Artikel Datum\' gebruikt. De Artikel datum wordt op elk artikel weergegeven, terwijl de afwijkende datum alleen wordt weergegeven indien de datum afwijkt van die van het vorige artikel.',
	'language' => 'Taal',
	'language_desc' => 'De Taal bepaalt in welke taal de datum en cijfers getoond worden, en bepalen ook welke charset (zoals iso-8859-1 or koi8-r, bijvoorbeeld) gebruikt wordt voor de pagina\'s.',

	'comment_head' => 'Reactie Instellingen',
	'comment_sendmail' => 'Mail versturen?',
	'comment_sendmail_desc' => 'Nadat er een reactie is geplaatst, is het mogelijk om (automatisch) een email te versturen aan degene die het weblog onderhoudt',
	'comment_emailto' => 'Email aan',
	'comment_emailto_desc' => 'Specificeer het adres waarnaar de mail gestuurd moet worden. Meerdere adressen scheid je door gebruik van een komma.',
	'comment_texttolinks' => 'Tekst naar links converteren',
	'comment_texttolinks_desc' => 'Bepaalt of URL\'s en emailadressen aanklikbaar gemaakt worden.',
	'comment_wrap' => 'Reactie terugloop na',
	'comment_wrap_desc' => 'Om te voorkomen dat woorden met (te) veel tekens je layout verstoren worden ze na dit aantal tekens op de volgende regel gezet.',
	'comments_text_0' => 'label voor \'geen reacties\'',
	'comments_text_1' => 'label voor \'een reactie\'',
	'comments_text_2' => 'label voor \'X reacties\'',
	'comments_text_2_desc' => 'De tekst die gebruikt wordt om aan te geven hoeveel reacties er zijn. Als je hier niks invult gebruikt Pivot de standaardinstellingen.',

	'comment_pop' => 'Reacties in een popup?',
	'comment_pop_desc' => 'Bepaalt of het reactiescherm weergegeven wordt in een popup of in het originele venster.',
	'comment_width' => 'Breedte van de Popup',
	'comment_height' => 'Hoogte van de Popup',
	'comment_height_desc' => 'Specificeer de breedte en de hoogte van de reactie-popup.',

    'comment_format' => "Opmaak van Reacties",
    'comment_format_desc' => "Specificeer hoe een reactie op de pagina van een artikel er uit ziet.",

	'comment_reply' => "Opmaak van 'reageer ..'",
	'comment_reply_desc' => "Dit bepaalt hoe de link er uitziet, waarmee men op een bepaalde reactie kan reageren.",
	'comment_forward' => "Opmaak van 'reactie door ..'",
	'comment_forward_desc' => "Dit bepaalt de opmaak van de tekst die getoond wordt als er op de reactie gereageerd is.",
	'comment_backward' => "Opmaak van 'reactie op ..'",
	'comment_backward_desc' => "Dit bepaalt de opmaak van de tekst die getoond wordt de reactie een antwoord op een eerdere reactie is",

    'comment_textile' => 'Sta Textile toe',
    'comment_textile_desc' => 'Als Textile is toegestaan, kunnen bezoekers gebruik maken van <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> in hun reacties.',
	'save_comment' => 'Reactie opslaan',
	'comment_gravatardefault' => 'Default Gravatar',
	'comment_gravatardefault_desc' => 'URL to the default Gravatar image. Start with http://',
	'comment_gravatarhtml' => 'Gravatar HTML',
	'comment_gravatarhtml_desc' => 'HTML to insert for a gravatar. %img% will be substituted by the url to the image.',
	'comment_gravatarsize' => 'Gravatar size',
	'comment_gravatarsize_desc' => 'Size (in pixels) of the gravatar. The default is 48.',

    'trackback_head' => 'Trackback instellingen',
	'trackback_sendmail' => 'Verstuur e-mail?',
	'trackback_sendmail_desc' => 'Als een trackback is geplaatst kan een mail worden verstuurd aan de beheerder',
	'trackback_emailto' => 'E-mail aan',
	'trackback_emailto_desc' => 'Geef het emailadres waar de notificatie naar toe moet. Scheid meerdere adressen met een komma.',
	'trackbacks_text_0' => 'Label voor \'geen trackbacks\'',
	'trackbacks_text_1' => 'Label voor \'een trackback\'',
	'trackbacks_text_2' => 'Label voor \'X trackbacks\'',
	'trackbacks_text_2_desc' => 'De tekst waarmee Pivot aangeeft hoeveel trackbacks er zijn. Laat je dit leeg, dan gebruikt Pivot default instellingen.',
	'trackback_pop' => 'Trackbacks Popup?',
	'trackback_pop_desc' => 'Bepaalt of de trackbacks pagina (of \'single entry\') wordt getoond in een popup, of in het originele browservenster.',
	'trackback_width' => 'Breedte van de Popup',
	'trackback_height' => 'Hoogte van de Popup',
	'trackback_height_desc' => 'Specificeer de grootte (in pixels) van de trackbacks pop-up.',
	'trackback_format' => "Opmaak van Trackbacks",
	'trackback_format_desc' => "Bepaalt de opmaak van trackbacks op de entry pages.",
	'trackback_link_format' => "Opmaak van Trackback Link",
    'save_trackback' => 'Bewaar Trackback',

	'saved_create' => 'Het nieuwe weblog is aangemaakt.',
	'saved_update' => 'Het weblog is succesvol aangepast.',
	'deleted' => 'Het weblog is verwijderd.',
	'confirm_delete' => 'Je staat op het punt weblog %1 te verwijderen. Weet je het zeker?',

	'blogroll_heading' => 'Blogroll instellingen',
	'blogroll_id' => 'Blogrolling ID #',
	'blogroll_id_desc' => 'Je kunt optioneel een <a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a> blogroll invoegen in je weblog. Blogrolling is een uitstekende service om een linklijst bij te houden waarvan je kunt zien wanneer de pagina\'s zijn geupdate. Als je deze feature niet wilt, kun je onderstaande invulvelden overslaan. Anders: Als je bent ingelogd op blogrolling.com, ga dan naar \'get code\', daar vind je links met je blogroll ID #. Die ziet er ongeveer zo uit: 2ef8b42161020d87223d42ae18191f6d',
	'blogroll_fg' => 'Tekstkleur',
	'blogroll_bg' => 'Achtergrondkleur',
	'blogroll_line1' => 'Lijnkleur 1',
	'blogroll_line2' => 'Lijnkleur 2',
	'blogroll_c1' => 'Kleur 1',
	'blogroll_c2' => 'Kleur 2',
	'blogroll_c3' => 'Kleur 3',
	'blogroll_c4' => 'Kleur 4',
	'blogroll_c4_desc' => 'Deze kleuren bepalen hoe je blogroll er uit komt te zien. Kleur 1 tot kleur 4 geven een visuele indicatie van hoe recent een site is geupdate.',
);


$lang['upload'] = array (
	//		File Upload		\\
	'preview' => 'Volledige Lijst Preview',
	'thumbs' => 'Thumbnail Preview',
	'create_thumb' => '(Thumbnail aanmaken)',
	'title' => 'Bestanden',
	'thisfile' => 'Nieuw bestand uploaden:',
	'button' => 'Uploaden',
	'filename' => 'Bestandsnaam',
	'thumbnail' => 'Thumbnail',
	'date' => 'Datum',
	'filesize' => 'Grootte',
	'dimensions' => 'Breedte x Hoogte',
	'delete_title' => 'Afbeelding verwijderen',
	'areyousure' => 'Weet je zeker dat je het bestand %s wilt verwijderen?',
	'picheader' => 'Deze afbeelding verwijderen?',
	'create' => 'nieuwe',
	'edit' => 'bewerken',

	'insert_image' => 'Afbeelding invoegen',
	'insert_image_desc' => 'Om een afbeelding in te voegen dien je een reeds geuploade afbeelding te kiezen of een nieuwe afbeelding te uploaden',
	'insert_image_popup' => 'Popup afbeelding invoegen',
	'insert_image_popup_desc' => 'Om een popup-afbeelding in te voegen dien je een reeds geuploade afbeelding te kiezen of een nieuwe afbeelding te uploaden. Kies daarna een afbeelding of tekst die als link dient.',
	'choose_upload' => 'uploaden',
	'choose_select' => 'of selecteer',
	'imagename' => 'Afbeeldings naam',
	'alt_text' => 'Alternatieve tekst',
	'align' => 'Uitlijning',
	'border' => 'Omlijning',
	'pixels' => 'pixels',
	'uploaded_as' => 'Je bestand is geupload als \'%s\'.',
	'not_uploaded' => 'Je bestand is niet geupload, en er vonden de volgende errors plaats:',
	'center' => 'Centreren (standaard)',
	'left' => 'Links',
	'right' => 'Rechts',
	'inline' => 'Inline',
	'notice_upload_first' => 'Je dient eerst een afbeelding te uploaden of te selecteren',
	'select_image' => 'Selecteer Afbeelding',
	'select_image' => 'Selecteer Bestand',

	'for_popup' => 'Voor de popup',
	'use_thumbnail' => 'Gebruik Thumbnail',
	'edit_thumbnail' => 'Bewerk thumbnail',
	'use_text' => 'Gebruik tekst',
	'insert_download' => 'Download invoegen',
	'insert_download_desc' => 'To make a file download, you should upload a file, or select a previously uploaded file. Then select whether you want an icon or a text or a thumbnail that triggers the download.',
	'use_icon' => 'Gebruik Icoon',
);


$lang['link'] = array (
	//		Link Insertion \\
	'insert_link' => 'Link invoegen',
	'insert_link_desc' => 'Voeg een link in door in onderstaand veld de URL te typen. Bezoekers zien de titel van de site als ze met hun muis over de link bewegen.',
	'url' => 'URL',
	'title' => 'Titel',
	'text' => 'Tekst',
);


//		Categories		\\
$lang['category'] = array (
	'edit_who' => 'Bepaal wie er kan posten in de categorie \'%s\'',
	'name' => 'Naam',
	'users' => 'Gebruikers',
	'make_new' => 'Nieuwe Categorie Aanmaken',
	'create' => 'Categorie Aanmaken',
	'canpost' => 'Selecteer de gebruikers die toestemming hebben om in deze categorie artikelen te plaatsen',
	'same_name' => 'Er bestaat al een categorie met deze naam',
	'need_name' => 'Je dient wel een naam in te vullen',

	'allowed' => 'Toegestaan',
	'allow' => 'Toestaan',
	'denied' => 'Niet toegestaan',
	'deny' => 'Niet toestaan',
	'edit' => 'Categorie Bewerken',

	'delete' => 'Categorie Verwijderen',
    'delete_desc' => 'Selecteer \'ja\', als je deze categorie wilt verwijderen',

	'delete_message' => 'In deze versie van Pivot, wordt alleen de naam van de categorie verwijderd. In volgende versies kun je bepalen wat er gebeurt met de artikelen uit deze categorie.',
	'search_index_newctitle'   => 'Index this category',
	'search_index_newcdesc'    => 'Only set to \'No\' if you do not want visitors to your site to search in this category.',
	'search_index_editcheader' => 'Index Category',

	'order' => 'Sorting Order',
	'order_desc' => 'Categories with a lower sorting order will appear higher in the list. If you keep all the numbers the same, they will be sorted alphabetically',
	'public' => 'Public Category',
	'public_desc' => 'If set to \'No\', this category will only be viewable for registered visitors. (applies only to live pages)',
	'hidden' => 'Verborgen Categorie',
	'hidden_desc' => 'Als je deze op \'Ja\' zet, wordt deze categorie verborgen in de lijst met categorieen. (alleen van toepassing op \'live pages\')',

);


$lang['entries'] = array (
	//		Entries			\\
	'post_entry' => "Artikel plaatsen",
	'preview_entry' => "Artikel preview",

	'first' => 'eerste',
	'last' => 'laatste',
	'next' => 'volgende',
	'previous' => 'vorige',

    'jumptopage' => 'spring naar pagina (%num%)',
    'filteron' => 'filter op (%name%)',
    'filteroff' => 'filter uit',
	'title' => 'Titel',
	'subtitle' => 'Ondertitel',
	'introduction' => 'Introductie',
	'body' => 'Body',
	'publish_on' => 'Publiceer op',
	'status' => 'Status',
	'post_status' => 'Post Status',
	'category' => 'Categorie',
	'select_multi_cats' => '(Ctrl-click om meerdere categorieen te selecteren)',
	'last_edited' => "Laatst bewerkt op",
	'created_on' => "Aangemaakt op",
	'date' => 'Datum',
	'author' => 'Auteur',
	'code' => 'Code',
	'comm' => '# reacties',
	'track' => '# Track',
	'name' => 'Name',
	'allow_comments' => 'Reacties toestaan',

	'delete_entry' => "Verwijder Entry",
	'delete_entry_desc' => "Verwijder deze Entry en de bijbehorende Comments ",
	'delete_one_confirm' => "Weet je zeker dat je deze entry wilt verwijderen?",
	'delete_multiple_confirm' => "Weet je zeker dat je deze entries wilt verwijderen?",

	'convert_lb' => 'Linebreaks converten',
	'always_off' => '(Dit staat altijd uit als je in Wysiwyg mode werkt)',
	'be_careful' => '(Kijk hiermee uit!)',
	'edit_comments' => 'Reacties bewerken',
	'edit_comments_desc' => 'Bewerk de reacties die bij dit artikel geplaatst zijn',
	'edit_comment' => 'Reactie bewerken',
	'delete_comment' => 'Reactie verwijderen',
	'edit_trackback' => 'Trackback bewerken',
	'delete_trackback' => 'Trackback verwijderen',
	'block_single' => 'IP %s blokkeren',
	'block_range' => 'IP range %s blokkeren',
	'unblock_single' => 'Blokkering van IP %s opheffen',
	'unblock_range' => 'Blokkering van IP range %s opheffen',
	'trackback' => 'Trackback ping',
	'trackback_desc' => 'Stuur Trackback pings naar de volgende url(s). Om meerdere pings te sturen zet je elke url op een aparte regel.',
	'keywords' => 'Keywords',
	'keywords_desc' => 'Use this to set some keywords that can be used to find this entry, or to set the non-crufty url for this entry.',
	'vialink' => "Via link",
	'viatitle' => "Via title",
	'via_desc' => 'Use this to set a link to the source of this entry.',
	'entry_catnopost' => 'You are not allowed to post in category:\'%s\'.',
	'entry_saved_ok' => 'De entry \'%s\' is opgeslagen.',
	'entry_ping_sent' => 'A trackback ping has been sent to \'%s\'.',
);


//		Form Fun		\\
$lang['forms'] = array (
	'c_all' => 'Selecteer alles',
	'c_none' => 'Selecteer geen',
	'choose' => '- Selecteer optie -',
	'publish' => 'Status veranderen naar \'publiceren\'',
	'hold' => 'Status veranderen naar \'Nog niet publiceren\'',
	'delete' => 'Verwijder deze',
	'generate' => 'Publiceren en genereren',

	'with_checked_entries' => "Actie:",
	'with_checked_files' => "Actie:",
	'with_checked_templates' => 'Actie:',
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'Kan directory niet openen, controleer je rechten.',
	'path_read' => 'Kan directory niet lezen, controleer je rechten..',
	'path_write' => 'Kan directory niet schrijven, controleer je rechten.',

	'file_open' => 'Kan bestand niet openen, controleer je rechten.',
	'file_read' => 'Kan bestand niet lezen, controleer je rechten.',
	'file_write' => 'Kan bestand niet schrijven, controleer je rechten.',
);


//		Notices			\\
$lang['notice'] = array (
	'comment_saved' => "De reactie is bewaard.",
	'comment_deleted' => "De reactie is verwijderd.",
	'comment_none' => "Dit artikel heeft geen reacties.",
	'trackback_saved' => "De Trackback is bewaard.",
	'trackback_deleted' => "De Trackback is verwijderd.",
	'trackback_none' => "Dit artikel heeft geen trackbacks.",
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'Stem \'%val%\' voor dit artikel',
	'good' => 'Goed',
	'bad' => 'Slecht',
	'already' => 'Je hebt al voor dit item gestemd',
	'register' => 'Je \'%val%\' stem is geregistreerd',
);


$lang['comment'] = array (
	'register' => 'Je reactie is bewaard.',
	'preview' => 'Dit is slechts een preview van je reactie. Door op \'Plaats Reactie\' te klikken wordt je reactie opgeslagen.',
	'duplicate' => 'Je reactie is niet opgeslagen omdat het hetzelfde is als een eerder reactie.',
	'no_name' => 'Typ je naam (of een alias) in het \'naam\'-veld. Klik op \'Plaats Reactie\' om je reactie op te slaan.',
	'no_comment' => 'Zou je niet iets intypen in het \'reactie\'-veld? Klik op \'Plaats Reactie\' om je reactie permanent op te slaan.',
	'too_many_hrefs' => 'Teveel hyperlinks in je reactie. Hou eens op met spammen, joh!',
);


$lang['comments_text'] = array (
	'0' => "Geen reacties",
	'1' => "%num% reactie",
	'2' => "%num% reacties",
);

$lang['trackbacks_text'] = array (
	'0' => "Geen trackback",
	'1' => "%num% trackback",
	'2' => "%num% trackbacks",
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => "Archieven",
	'next_archive' => "Volgend Archief",
	'previous_archive' => "Vorig Archief",
	'last_comments' => "Laatste Reacties",
	'last_referrers' => "Laatste Referrers",
	'calendar' => "Kalender",
	'links' => "Links",
	'xml_feed' => "XML Feed (RSS 1.0)",
	'atom_feed' => "XML: Atom Feed",
	'powered_by' => "Powered by",
	'blog_name' => "Weblog naam",
	'title' => "Titel",
	'excerpt' => "Samenvatting",
	'name' => "Naam",
	'email' => "Email",
	'url' => "URL",
	'date' => "Datum",
	'comment' => "Reactie",
	'ip' => "IP-adres",
	'yes' => "Ja",
	'no' => "Nee",
	'emoticons' => "Emoticons",
	'emoticons_reference' => 'Open Emoticons Reference',
	'textile' => 'Textile',
	'textile_reference' => 'Open Textile Reference',
	'post_comment' => "Plaats Reactie",
	'preview_comment' => "Preview Reactie",
	'remember_info' => "Persoonlijke info onthouden?",
	'notify' => "Kattenbel",
	'notify_yes' => "Ja, stuur mij email als iemand anders reageert.",
	'register' => "Register your username / Log in",
	'disclaimer' => "<b>Kleine lettertjes:</b> Alle HTML-tags behalve &lt;b&gt; en &lt;i&gt; zullen uit je reactie worden verwijderd. Je maakt links door gewoon een URL of email-adres in te typen.",
	'search_title' => "Zoek resultaten",
	'search' => "Zoek!",
	'nomatches' => "Niets gevonden voor '%name%'. Probeer iets anders.",
	'matches' => "Resultaten voor '%name%':",
	'about' => "About",
	'stuff' => "Stuff",
	'linkdump' => "Linkdump",
	'discreet' => "Verberg email",
	'discreet_yes' => "Ja, verberg mijn email-adres.",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
	'title' => 'Gebruikersvelden bewerken',
	'edit' => 'Bewerken',
	'create' => 'Aanmaken',

	'dispname' => 'Weergegeven naam',
	'intname' => 'Naam voor het systeem',
	'intname_desc' => 'De naam voor het systeem is de naam die je invult als Pivot het in een template weer dient te geven.',
	'size' => 'Grootte',
	'rows' => 'Rijen',
	'cols' => 'Kolommen',
	'maxlen' => 'Maximum Lengte',
	'minlevel' => 'Min. Gebruikers Niveau',
	'filter' => 'Filteren op',
	'filter_desc' => 'Door dit item te filteren kun je de input aanpassen.',
	'no_filter' => 'Niks',
	'del_title' => 'Verwijderen bevestigen',
	'del_desc' => 'Het verwijderen van dit gebruikersveld (<b>%s</b>) verwijderd ook alle gegevens die de gebruikers in dit veld hadden ingevuld, en zal de plek in het template waar je dit veld aanroept leeg laten',

	'already' => 'Deze naam is al in gebruik.',
	'int' => 'De naam voor het systeem moet langer zijn dan 3 tekens',
	'short_disp' => 'De weergegeven naam moet langer zijn dan 3 tekens',
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'Bookmarklets',
	'bm_add' => 'Bookmarklet toevoegen',
	'bm_withlink' => 'Piv » Nieuw',
	'bm_withlink_desc' => 'Bookmarklet met Link. Deze bookmarklet opent een venster met een Nieuw Artikel, met daarin een link naar de pagina van waaruit deze geopend is.',

	'bm_nolink' => 'Piv » Nieuw',
	'bm_nolink_desc' => 'Bookmarklet zonder Link. Deze bookmarklet opent een venster met een blanco Nieuw Artikel.',

	'bookmarklets_info' => 'Bookmarklets kun je gebruiken om snel Nieuwe Artikelen te posten met Pivot. Om een bookmarklet aan je browser toe te voegen, kun je twee dingen doen: (exacte tekst is afhankelijk van welke browser je gebruikt)',
	'bookmarklets_info_1' => 'Klik en sleep de Bookmarklet in je browser naar de Links(koppelingen) toolbar of naar de \'Bookmarks\'-button.',
	'bookmarklets_info_2' => 'Klik met rechts op de bookmarklet en kies \'Toevoegen aan Bookmarks\'.',
);

// Accessibility - These are used for form fields, labels, fieldsets etc.
// for Web Content Accessibility Guidelines & 508 compliancy issues.
// see: http://bobby.watchfire.com/bobby/html/en/index.jsp
// JM =*=*= 2004/10/04
// 2004/11/25 =*=*= JM - minor correction for tim
$lang['accessibility'] = array(
	'search_idname'      => 'zoek',
	'search_formname'    => 'Zoek naar woorden die gebruikt zijn in artikelen',
	'search_fldname'     => 'Zoeken naar:',
	'search_placeholder' => 'geef zoekterm',

	'calendar_summary'   => 'Dit is een tabel die weergeeft op welke datum er artikelen geplaatst zijn.',
	'calendar_noscript'  => 'De kalender biedt een alternatieve navigatie voor de site, maar is alleen beschikbaar voor bezoekers met javascript.',
	/*
	2-letter language code, used to designate the principal language used on the site
	see: http://www.oasis-open.org/cover/iso639a.html
	*/

	'lang' => $langname,
) ;


$lang['snippets_text'] = array (
    'word_plural'     => 'woorden',
    'image_single'    => 'afbeelding',
    'image_plural'    => 'afbeeldingen',
    'download_single' => 'bestand',
    'download_plural' => 'bestanden',
);

$lang['trackback'] = array (
    'register' => 'Je trackback is bewaard.',
    'duplicate' => 'je trackback is niet bewaard, omdat het een kopie van een eerdere lijkt te zijn.',
    'too_many_hrefs' => 'Je hebt meer links geplaatst dan toegestaan. Stop met spammen!',
    'noid'      => 'No TrackBack ID (tb_id)',
    'nourl'     => 'No URL (url)',
    'tracked'   => 'Tracked',
    'email_subject' => '[Trackback] new Trackback',
);

$lang['commentuser'] = array (
    'title'             => 'Pivot bezoeker login',
    'header'            => 'Log in als vaste bezoeker',
    'logout'            => 'Log uit.',
    'loggedout'         => 'Uitgelogd',
    'login'             => 'Log in',
    'loggedin'          => 'Ingelogd',
    'loggedinas'        => 'Ingelogd als',
    'pass_forgot'       => 'Wachtwoord vergeten?',
    'register_new'      => 'Registreer een nieuwe naam.',
    'register'          => 'Registreer als vaste bezoeker',
    'register_info'     => 'Vul de gevraagde informatie in. <strong>Let er op dat je een werkend mailadres invult</strong>, want naar dat adres wordt een bevestiging gestuurd.',
    'pass_note'         => 'Let op: Het is voor de beheerder van deze site mogelijk om de gebruikte wachtwoorden in te zien.. Gebruik <em>niet</em> een wachtwoord dat je ook voor andere accounts / websites gebruikt.',
    'show_email'        => 'Toon mijn mailadres bij een reactie',
    'notify'            => 'Houd mij via email op de hoogte van reacties',
    'def_notify'        => 'Standaard reacties toesturen',
    'register'          => 'Registreer',
    'pass_invalid'      => 'Onjuist wachtwoord',
    'nouser'            => 'Onbekende gebruiker..',
    'change_info'       => 'Verander hier je persoonlijke gegevens.',
    'pref_edit'         => 'Stel je voorkeuren in',
    'pref_change'       => 'Verander voorkeuren',
    'options'           => 'Opties',
    'user_exists'       => 'Die gebruiker bestaat al.. Kies een andere naam.',
    'email_note'        => 'Je moet een geldig mailadres opgeven , aangezien het anders onmogelij is om je account te verifieren. Je kunt later altijd instellen dat je je mailadres niet wilt tonen op de site.',
    'stored'            => 'De wijzigingen zijn doorgevoerd',
    'verified'          => 'Je aanmelding is bevestigd. Je kunt nu inloggen..',
    'not_verified'      => 'Die code klopt niet. Ik kan de aanmelding helaas niet verifie&euml;ren.',
    'pass_sent'         => 'JE wachtwoord is verstuurd aan het opgegeven mailadres...',
    'user_pass_nomatch' => 'Die inlognaam en wachtwoord lijken niet overeen te komen.',
    'pass_send'         => 'Stuur wachtwoord',
    'pass_send_desc'    => 'Als je je wachtwoord vergeten bent, vul dan je inlognaam en mailadres in. Pivot stuurt dan je wachtwoord aan het opgegeven adres. ',
    'oops'              => 'Oeps!',
    'back'              => 'Terug naar',
    'back_login'        => 'Terug naar de inlogpagina',
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
