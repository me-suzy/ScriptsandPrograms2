<?php
//Norsk bokm&aring;l (Norwegian Bokm&aring;l) (UTF-8)

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2nd line.

// Norwegian Bokmål translation of Pivot lang file
// Created by Hans Fredrik Nordhaug (hans@nordhaug.priv.no), 2004.05.15 (Pivot 1.10)
// Updated by Hans Fredrik Nordhaug (hans@nordhaug.priv.no) for
// Pivot 1.14, Pivot 1.20, Pivot 1.22 and Pivot 1.30 (alpha).
//

// allow for different encoding for non-western languages
$encoding="iso-8859-1";
$langname="nb";


//		General		\\
$lang['general'] = array (
	'yes' => 'Ja',	//affirmative
	'no' => 'Nei',	//negative
	'go' => 'Gå!',	//proceed

	'minlevel' => 'Du har ikke adgang til dette området i Pivot',
	'email' => 'E-post',
	'url' => 'URL',
	'further_options' => "Videre innstillinger",
	'basic_view' => "Enkel visning",
	'basic_view_desc' => "Vis kun de vanligste feltene",
	'extended_view' => "Utvidet visning",
	'extended_view_desc' => "Vis alle felt som kan endres",
	'select' => "Velg",
	'cancel' => "Avbryt",
	'delete' => 'Slett',
	'welcome' => "Velkommen til %build%.",
	'write' => "Skriv",
	'write_open_error' => "Feil ved skriving. Klarte ikke åpne fil for skriving",
	'write_write_error' => "Feil ved skriving. Klarte ikke skrive til fil",
	'done' => "Ferdig!",
	'shortcuts' => "Snarveier",
	'cantdelete' => "Du har ikke lov til å slette oppføring %title%!",
	'cantdothat' => "Du har ikke lov til å gjøre det med oppføring %title%!",
	'cantdeletelast' => "Du kan ikke slette den siste oppføringen. Du må første opprette en ny oppføring før du sletter denne oppføringen.",
);


$lang['userlevels'] = array (
		'Superadmin', 'Administrator', 'Avansert', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin,
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array (
	'null', 'en', 'to', 'tre', 'fire', 'fem', 'seks', 'sju', 'åtte', 'ni', 'ti', 'elleve', 'tolv', 'tretten', 'fjorten', 'femten', 'seksten'
);


$lang['months'] = array (
	'januar', 'februar', 'mars', 'april', 'mai', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'desember'
);


$lang['months_abbr'] = array (
	'jan', 'feb', 'mar', 'apr', 'mai', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'des'
);


$lang['days'] = array (
	'søndag', 'mandag', 'tirsdag', 'onsdag', 'torsdag', 'fredag', 'lørdag'
);


$lang['days_abbr'] = array (
	'søn', 'man', 'tir', 'ons', 'tor', 'fre', 'lør'
);


$lang['days_calendar'] = array (
	'S', 'M', 'T', 'O', 'T', 'F', 'L'
);


$lang['datetime_words'] = array (
	'år', 'måned', 'uke', 'dag', 'time', 'minutt', 'sekund'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'Logg inn',
	'name' => 'Brukernavn',
	'pass' => 'Passord',
	'remember' => 'Husk',
	'rchoice' => array (
		'0' => 'Ingenting',
		'1' => 'Mitt brukernavn og passord',
		'2' => 'At jeg vil være innlogget'
	),
	'delete_cookies_desc' => 'Hvis du er sikker på at du bruker riktig brukernavn og passord, men likevel har problemer <br />med å logge inn, kan du prøve å slette informasjonskapslene for dette domenet:',
	'delete_cookies' => 'Slett informasjonskapsler',
	'retry' => 'Feil brukernavn/passord',
	'banned' => ' Du har brukt 10 forsøk på å logge inn. IP-adressen har blitt blokkert fra å logge inn i 12 timer.',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => 'Oversikt',
	'entries' => 'Oppføringer',
	'submit' => 'Ny oppføring',
	'comments' => 'Kommentarer',
	'trackbacks' => 'Tilbaketråkk',
	'modify' => 'Endre oppføringer',
	'userinfo' => 'Mine opplysninger',
	'u_settings' => 'Mine innstillinger',
	'u_marklet' => 'Skriptbokmerker',
	'files' => 'Behandle mediafiler',
	'upload' => 'Opplasting',
	'stats' => 'Statistikk',
	'admin' => 'Administrasjon',

	'main_title' => 'Hovedoversikt over Pivot',
	'entries_title' => 'Oversikt over oppføringer',
	'submit_title' => 'Ny oppføring',
	'comments_title' => 'Endre eller slett kommentarer',
	'trackbacks_title' => 'Endre eller slett tilbaketråkk',
	'modify_title' => 'Endre oppføring',
	'userinfo_title' => 'Vis min personlige informasjon',
	'u_settings_title' => 'Endre mine personlige innstillinger',
	'u_marklet_title' => 'Opprett skriptbokmerker',
	'files_title' => 'Behandle og laste opp mediafiler',
	'upload_title' => 'Last opp filer',
	'uploaded_success' => 'Fil er lastet opp',
	'stats_title' => 'Vis logger og statistikk',
	'updatetitles_title' => 'Vis logger og statistikk',
	'admin_title' => 'Oversikt over administrasjon',
	'recent_entries' => 'Nylige oppføringer',
	'recent_comments' => 'Nylige kommentarer ',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
	//'trebuild' => 'Rebuild all Files', rolled into maintenance
	'seeusers' => 'Brukere',
	'seeconfig' => 'Konfigurasjon',
	'filemappings' => 'Filmapping',
	'templates' => 'Maler',
	'maintenance' => 'Vedlikehold',
	'regen' => 'Gjenoppbygg alle filer',
	'blogs' => 'Blogger',
	'categories' => 'Kategorier',
	'verifydb' => 'Kontroller databasen',
	'buildindex' => 'Oppdater indeksen',
	'buildsearchindex' => 'Oppdater søkeindeksen',
	'buildfrontpage' => 'Oppdater forsiden(e)',
	'sendping' => 'Send ping',


	'backup' => 'Backup',
	'description' => 'Beskrivelse',
	'conversion' => 'Omgjøring',
	'seeusers_title' => 'Opprett, endre og slette brukere',
	'userfields' => 'Felt for brukerinformasjon',
	'userfields_title' => 'Opprett, endre og slette felt for brukerinformasjon',
	'seeconfig_title' => 'Endre konfigurasjonsfilen',
	'filemappings_title' => 'Oversikt over hvilke filer som blir lagd og brukt av Pivot for de forskjellige bloggene',
	'templates_title' => 'Opprett, endre og slette maler',
	'maintenance_title' => 'Utfør rutinemessig vedlikehold på Pivot sine filer',
	'regen_title' => 'Gjenoppbygg filene og arkivet som Pivot lager',
	'blogs_title' => 'Opprett, endre og slette blogger',
	'blogs_edit_title' => 'Endre blogginnstilliger for ',
	'categories_title' => 'Opprett, endre og slette kategorier',
	'verifydb_title' => 'Kontroller integriteten for databasen din',
	'buildindex_title' => 'Gjenoppbygg indeksen for databasen din',
	'buildsearchindex_title' => 'Gjenoppbygg søkeindeks slik at folk kan søke i oppføringene',
	'buildfrontpage_title' => 'Gjenoppbygg forsiden, de siste arkivene og RSS-filene for hver av bloggene.',
	'backup_title' => 'Opprett en sikkerhetskopi av oppføringene dine',
	'backup_config' => 'Opprett en sikkerhetskopi av konfigurasjonsfilene',
	'backup_config_desc' => 'Dette lar deg laste ned en zip-fil som inneholder alle dine konfigurasjonsfiler.',
	'ipblocks' => 'IP-blokker',
	'ipblocks_title' => 'Vis og endre de blokkerte IP-adressene.',
	'ipblocks_stored' => 'IP-adressene har blitt lagret.',
	'ipblocks_store' => 'Lagre disse IP-adressene',
	'ignoreddomains' => 'Ignorerte domener',
	'ignoreddomains_title' => 'Vis og endre de ignorerte domenene.',
	'ignoreddomains_stored' => 'De ignorerte domenene har blitt lagret.',
	'ignoreddomains_store' => 'Lagre disse ignorerte domenene',
	'fileexplore' => 'Filutforsker',
	'fileexplore_title' => 'Vis filer (både tekst- og databasefiler)',
	'sendping_title' => 'Send Ping oppdateringssporere.',
	'buildindex_start' => 'Bygger indeks nå. Dette kan ta litt tid, så ikke avbryt.',
	'buildsearchindex_start' => 'Bygger søkeindeks nå. Dette kan ta litt tid, så ikke avbryt.',
	'buildindex_finished' => 'Ferdig! Bygging av indeks tok %num% sekunder',

	'filemappings_desc' => 'Nedenfor ser du en oversikt over hver av bloggene i denne Pivot-installasjonen sammen med filer lagd av Pivot og maler som brukes for å lage disse filene. Dette kan være nyttig for å finne ut nøyaktig hva som går feil ved opprettelse av filer.',

	'debug' => 'Åpne feilsøkingsvindu',

	'latest_pivot_news' => "Siste nyheter om Pivot",
	'remove_setup_header' => "Pivot installasjonsskript tilstede",
	'remove_setup' => "Pivot installasjonsskriptet 'pivot-setup.php' er fortsatt tilstede in foreldremappen. Dette er en potensiell sikkerhetsrisiko. Vi anbefaler at du enten fjerner filen eller setter et blankt passord slik at det ikke kan bli brukt av folk med stygge hensikter.",

);


$lang['templates'] = array (
	'rollback' => 'Restaurer',
	'create_template' => 'Opprett mal',
	'create_template_info' => 'Opprett en Pivot-mal fra grunnen av',
	'no_comment' => 'Ingen kommentar',
	'comment' => 'Kommentar*',
	'comment_note' => '(*Merknad: Kommentarer kan bare bli lagret ved <b>første</b> lagring av endringer eller opprettelse)',
	'create' => 'Opprett maler',
	'editing' => 'Endringer',
	'filename' => 'Filenavn',
	'save_changes' => 'Lagre endringer!',
	'save_template' => 'Lagre mal!',
	'aux_template' => 'Hjelpemal',
	'sub_template' => 'Undermal',
	'standard_template' => 'Standardmal',
	'feed_template' => 'Strømmal',
	'css_template' => 'CSS-fil',
	'txt_template' => 'Tekstfil',
	'php_template' => 'PHP-fil',
);


//		Admin			\\
// bob notes: Mark made these, i think they should be replaced by the 'adminbar']['xxx_title'] ones
$lang['admin'] = array (
	'seeusers' => 'Opprett, endre og slette brukere',
	'seeconfig' => 'Endre konfigurasjonsfilen',
	'templates' => 'Opprett, endre og slette maler',
	'maintenance' => 'Utfør rutinemessig vedlikehold på Pivot sine filer, som \'Gjenoppbygg filer\', \'Kontroller databasen\', \'Gjenoppbygg indeksen\' og \'Sikkerhetskopiering\'.',
	'regen' => 'Gjenoppbygg alle siden som Pivot lager',
	'blogs' => 'Opprett, endre og slette de forskjellige bloggene som Pivot publiserer til',
);


//		Maintenace		\\
$lang['maint'] = array (
	'title' => 'Vedlikehold',
	'gen_arc_title' => 'Bygg arkiv', /* bob notes: redundant, see 'regen' */
	'gen_arc_text' => 'Gjenoppbygg alle dine arkiver', /* bob notes: redundant, see 'regen' */
	'xml_title' => 'Kontroller XML-filef', /* bob notes: replace with more general 'Verify DB' */
	'xml_text' => 'Kontroller (og reparer hvis nødvendig) integriteten til XML-filene', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'Sikkerhetskopiering',
	'backup_text' => 'Opprett en sikkerhetskopi av alle Pivot sine essensielle filer',
);


//		Stats and referers		\\
$lang['stats'] = array (
	'show_last' => "Vis de siste",
	'20ref' => "20 henvisere",
	'50ref' => "50 henvisere",
	'allref' => "alle henvisere",
	'showunblocked' => "bare ikke-blokkerte linjer",
	'showall' => "både blokkerte og ikke-blokkerte linjer",
	'updateref' => "Oppdater tittelhenvisermappingene",
	'hostaddress' => 'Tjeneradresse (IP-adresse)',
	'whichpage' => 'Hvilken side',

	'getting' => 'Henter nye titler',
	'awhile' => 'Dette kan ta litt tid, så ikke avbryt.',
	'firstpass' => 'Første kjøring',
	'secondpass' => 'Andre kjøring',
	'nowuptodate' => 'Dine tittelhenvisermappinger er nå oppdatert.',
	'finished' => 'Ferdig',
);


//		User Info		\\
$lang['userinfo'] = array (
	'editfields' => 'Endre felt for brukere',
	'desc_editfields' => 'Endrer feltene som brukere kan bruke til å beskrive seg selv',
	'username' => 'Brukernavn',
	'nickname' => 'Kallenavn',
	'pass1' => 'Passord',
	'pass2' => 'Passord (bekreft)',
	'email' => 'E-post',
	'userlevel' => 'Brukernivå',
	'userlevel_desc' => 'Brukernivå avgjør hvilke handlinger brukeren kan utføre i Pivot.',
	'language' => 'Språk',
	'lastlogin' => 'Siste innlogging',
	'edituser' => 'Endre bruker',  //the link to.. well, edit the user (also the title)
	'edituserinfo' => 'Endre brukerinformasjon',
	'selfreg' => 'Selvregistrering',
	'newuser' => 'Opprett ny bruker',
	'desc_newuser' => 'Opprett en ny innloggingskonto for Pivot som tillater dem å poste til en blogg.',
	'newuser_button' => 'Opprett',
	'edituser_button' => 'Endre',
	'pass_too_short' => 'Passord må ha minst 4 tegn.',
	'pass_equal_name' => 'Passordet kan ikke være det samme som brukernavnet.',
	'pass_dont_match' => 'Passordene er ulike',
	'username_in_use' => 'Brukernavn er allerede i bruk',
	'username_too_short' => 'Navnet må være tre bokstaver eller lenger',
	'username_not_valid' => 'Brukernavn kan kun inneholde alfanumeriske tegn (A-Z, 0-9) og understreker (_).',
	'not_good_email' => 'Det er ingen gyldig e-postadresse',
	'c_admin_title' => 'Bekreft opprettelse av en '.$lang['userlevels']['1'],
	'c_admin_message' => 'En '.$lang['userlevels']['1'].' har full tilgang til Pivot, kan endre alle oppføringer, kommentarer og innstillinger. Er du sikker på at du vil gjøre %s til en '.$lang['userlevels']['1'].'?',
);


//		Config Page		\\
	$lang['config'] = array (
	'save' => 'Lagre innstillinger',

	'sitename' => 'Nettstedets navn',
	'defaultlanguage' => 'Standard språk',
	'defaultencoding' => 'Bruk koding',
	'defaultencoding_desc' => 'Dette definerer kodingen som er brukt (f.eks utf-8 eller iso-8859-1). Du bør la dette feltet være åpent hvis du ikke vet hva du gjør. Hvis du lar feltet være blankt, velges samme koding som oppgitt i språk(filen) du bruker.',
	'defaulttheme' => 'Tema',
	'selfreg' => 'Tillat selvregistrering',
	'selfreg_desc' => 'Hvis denne settes til ja kan folk registrere seg som (vanlig) brukere og poste oppføringer. (Dette er ikke en "kommentar"-bruker.)',
	'siteurl' => 'Nettstedets URL',
	'header_fileinfo' => 'File Info',
	'localpath' => 'Lokalsti',
	'debug_options' => 'Feilsøkealternativ ',
	'debug' => 'Feilsøkemodus',
	'debug_desc' => 'Vis vilkårlig feilsøkeinformasjon',
	'log' => 'Loggfiler',
	'log_desc' => 'Lag loggfiler for forskjellige aktiviteter',

	'unlink' => 'Koble fra filer',
	'unlink_desc' => 'Noen tjenere som har safe_mode (PHP) aktivert, krever kanskje at man leker med denne innstillingen. På de fleste tjenere har denne innstillingen ingen virkning',
	'chmod' => 'Endre filrettigheter (chmod) til',
	'chmod_desc' => 'Noen tjenere krever at opprettede filer får satt rettighetene (chmod) på en spesiell måte. Vanlige verdier er \'0644\' og \'0755\'. Ikke endre denne, hvis du ikke er helt sikker på hva du gjør',
	'header_uploads' => 'Filopplasting',
	'upload_path' => 'Filopplastingssti',
	'upload_accept' => 'Aksepterte filtyper',
	'upload_extension' => 'Standard filetternavn',
	'upload_save_mode' => 'Skriv over',
	'make_safe' => 'Rene filnavn',
	'c_upload_save_mode' => 'Inkrementelle filnavn',
	'max_filesize' => 'Maksimum filstørrelse',
	'header_datetime' => 'Dato/Tid',
	'timeoffset_unit' => 'Tidsavvikenhet',
	'timeoffset' => 'Tidsavvik',
	'header_extra' => 'Forskjellige innstillinger',
	'wysiwyg' => 'Bruk Wysiwyg-redigering',
	'wysiwyg_desc' => 'Avgjør om Wysiwyg-redigering er standard. Individuelle brukere kan endre dette under \'Mine innstillinger\'',
	'basic_view' => 'Bruk enkel visning',
	'basic_view_desc' => 'Avgjør om nye oppføringer åpnes med enkel eller utvidet visning.',
	'def_text_processing' => 'Standard tekstbehandling',
	'text_processing' => 'Tekstbehandling',
	'text_processing_desc' => 'Avgjør standard tekstbehandling når en bruker ikke har valgt Wysiwyg redigering. \'Omgjør linjeskift\' endrer kun linjeskift til en &lt;br&gt;-tagg. <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> er en kraftig, men likevel lett å lære, markeringsstil.',
	'none' => 'Ingen',
	'convert_br' => 'Omgjør linjeskift til &lt;br /&gt;',
	'textile' => 'Textile',
	'markdown' => 'Markdown',
	'markdown_smartypants' => 'Markdown og Smartypants',

	'allowed_cats' => 'Tillatte kategorier',
	'allowed_cats_desc' => 'Denne brukeren har lov å poste oppføringer i de valgte kategoriene.',
	'delete_user' => 'Slett bruker',
	'delete_user_desc' => 'Du kan slette denne brukeren hvis du vil. Alle deres oppføringer vil bestå, men de vil ikke lenger kunne logge inn.',
	'delete_user_confirm' => 'Du er i gang med å fjerne tilgangen for %s. Er du sikker på at du vil gjøre dette?',

	'setup_ping' => 'Ping-oppsett ',
	'ping_use' => 'Ping oppdateringssporere',
	'ping_use_desc' => 'Avgjør om oppdateringssporere som weblogs.com skal automatisk varsles av Pivot når du poster en ny oppføring. Tjenester som blogrolling.com avhenger av slike varsler.',
	'ping_urls' => 'Send ping til følgende URL-er',
	'ping_urls_desc' => 'Du kan liste flere URL-er som det skal sendes ping til. Ikke ta med \'http://\'-delen, ellers virker det ikke. Plasser hver tjener på en ny linje. Noen vanlige tjener er:<br /><b>rpc.weblogs.com/RPC2</b> (kanskje den mest brukte)<br /><b>pivotlog.net/pinger</b> (ikke i bruk enda)<br /><b>rcs.datashed.net/RPC2</b> <br /><b>ping.blo.gs</b> <br />',

	'setup_tb' => 'Tilbaketråkk-oppsett',
	'tb_email' => 'Epost',
	'tb_email_desc' => 'Hvis oppgitt, så vil en e-post bli sendt til denne adressen når et tilbaketråkk blir lagt til.',

	'new_window' => 'Åpne lenker i nye vinduer',
	'emoticons' => 'Bruk føleikon',
	'javascript_email' => 'Kod/skjul e-postadresser?',
	'new_window_desc' => 'Avgjør om alle lenker i en oppføring vil åpnes i et nytt nettleservindu.',

	'mod_rewrite' => 'Bruk Mod_rewrite',
	'mod_rewrite_desc' => 'Hvis du bruker Apache sin Mod_rewrite-innstilling, så vil Pivot lage URL-er som www.mysite.com/archive/2003/05/30/fin_tur, i steden for www.mysite.com/pivot/entry.php?id=134. Ikke alle tjenere støtter dette.',
	'mod_rewrite_1' => 'Ja, som /archive/2005/04/28/title_of_entry',
	'mod_rewrite_2' => 'Ja, som /archive/2005-04-28/title_of_entry',
	'mod_rewrite_3' => 'Ja, som /entry/1234',
	'mod_rewrite_4' => 'Ja, som /entry/1234/title_of_entry',

	'search_index' => 'Oppdater søkeindeks automatisk',
	'search_index_desc' => 'Avgjør om søkeindeksfilene automatisk opdateres hver gang du poster en ny oppføring eller endrer en oppføring.',

	'default_allow_comments' => 'Tillatt kommentarer som standard',
	'default_allow_comments_desc' => 'Avgjør om oppføringer er satt til å tillate kommentarer eller ikke.',

	'maxhrefs' => 'Antall lenker',
	'maxhrefs_desc' => 'Maksimalt antall lenker tillatt i kommentarer. Nyttig for å bli kvitt de plagsomme kommentar-spammerne. Sett til 0 for ubegrenset antall lenker.',
	'rebuild_threshold' => 'Gjennoppbyggingsgrense',
	'rebuild_threshold_desc' => 'Antall sekund det tar før Pivot oppdaterer siden. Standardverdien er 28 sekunder, men hvis du har problemer prøv å senke verdien til 10.',
	'default_introduction' => 'Standard introduksjon/kropp',
	'default_introduction_desc' => 'Bestemmer standardverdiene for introduksjon og kropp for en ny oppføring. Vanligvis er dette et tomt avsnitt, som gir mest mening semantisk.',

	'upload_autothumb'	=> 'Automatiske miniatyrer',
	'upload_thumb_width' => 'Miniatyrbredde',
	'upload_thumb_height' => 'Miniatyrhøyde',
	'upload_thumb_remote' => 'Eksternt beskjæringsskript',
	'upload_thumb_remote_desc' => 'Hvis tjeneren din ikke har de nødvendig bibliotek installert, kan du bruke et eksternt beskjæringsskript',

        'extensions_header' => 'Katalog for utvidelser',
        'extensions_desc'   => '\'Utvidelser\'-katalogen er plassen hvor du lagrer dine tillegg til Pivot.
                Dette gjør oppdatering av Pivot mye enklere - se dokumentasjon for mer informasjon.',
        'extensions_path'   => 'Stien til katalogen for utvidelser',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'Endre blogg ',
	'edit_blog' => 'Endre blogger',
	'new_weblog' => 'Ny blogg',
	'new_weblog_desc' => 'Legg til en ny blogg',
	'del_weblog' => 'Slett blogg',
	'del_this_weblog' => 'Slett denne bloggen.',
	'create_new' => 'Opprett ny blogg',
	'subw_heading' => 'For hver av underbloggene som ble funnet kan du bestemme hvilke maler de bruker og hvilke kategorier som ksla publiseres i dem',
	'create' => 'Fullfør',

	'create_1' => 'Opprett / endre blogg, steg 1 av 3',
	'create_2' => 'Opprett / endre blogg, steg 2 av 3',
	'create_3' => 'Opprett / endre blogg, steg 3 av 3',

	'name' => 'Bloggnavn',
	'payoff' => 'Undertittel',
	'payoff_desc' => 'Undertittel eller kort beskrivelse for bloggen din (payoff)',
	'url' => 'URL til bloggen',
	'url_desc' => 'Pivot avgjør URL-en til bloggen din hvis feltet er tomt. Hvis du bruker bloggen din som en del av et rammesett (frameset) eller som en SSI (serverside include), så kan du bruke dette feltet til å overstyre Pivot.',
	'index_name' => 'Forside (Index)',
	'index_name_desc' => 'Filnavnet for index-fila. Vanligvis \'index.html\' eller \'index.php\'.',

	'ssi_prefix' => 'SSI-prefiks',
	'ssi_prefix_desc' => 'Hvis bloggen din bruker SSI (som ikke er anbefalt), kan du bruke denne til å prefikse Pivot sine filnavn med filnavnet brukt for SSI-en (\'index.shtml?p=\'). Hvis du ikke er 100% sikker på hva du gjør bør du la dette feltet stå tomt.',

	'front_path' => 'Sti til forsiden',
	'front_path_desc' => 'Den relative eller absolutte stien til katalogen hvor Pivot vil opprette forsiden for denne bloggen.',
	'file_format' => 'Filnavn',
	'entry_heading' => 'Innstillinger for oppføringer',
	'entry_path' => 'Stil til oppføringer',
	'entry_path_desc' => 'Den relative eller absolutte stien til katalogen hvor Pivot vil opprette enslige oppføringssider (hvis du velger å ikke bruke \'Levende oppføringer\')',
	'live_comments' => 'Levende oppføringer',
	'live_comments_desc' => 'Hvis du bruker \'Levende oppføringer\', trenger ikke Pivot å lage filer for hver enkelt oppføring. Dette er den foretrukne innstillingen.',
	'readmore' => '\'Les videre\'-tekst',
	'readmore_desc' => 'Teksten som forteller at det er mer å lese i denne oppføringen en det som er vist på forsiden. Hvis tom, vil Pivot bruke standarden definert av det valgte språket.',

	'arc_heading' => 'Arkivinnstillinger',
	'arc_index' => 'Indeksfil',
	'arc_path' => 'Arkivsti',
	'archive_amount' => 'Arkivstørrelse',
	'archive_unit' => 'Arkivtype',
	'archive_format' => 'Arkivformat',
	'archive_none' => 'Ingen arkiv',
	'archive_weekly' => 'Ukentlige arkiv',
	'archive_monthly' => 'Månedlige arkiv',
	'archive_yearly' => 'Årlige arkiv',

	'archive_link' => 'Arkivlenke',
	'archive_linkfile' => 'Arkivlistformat',
	'archive_order' => 'Arkivrekkefølge',
	'archive_ascending' => 'Eldste først',
	'archive_descending' => 'Nyeste først',

	'templates_heading' => 'Maler',
	'frontpage_template' => 'Forsidemal',
	'frontpage_template_desc' => 'Malen som bestemmer utseende på forsiden for denne bloggen.',
	'archivepage_template' => 'Arkivsidemal',
	'archivepage_template_desc' => 'Malen som bestestemmer utseende på arkivene. Dette kan være det samme som \'Forsidemal\'.',
	'entrypage_template' => 'Oppføringssidemal',
	'entrypage_template_desc' => 'Malen som bestestemmer utseende på enslige oppføringer (på egen side).',
	'extrapage_template' => 'Ekstra mal',
	'extrapage_template_desc' => 'Malen som bestestemmer utseende på arkiv- og søkesiden.',

	'shortentry_template' => 'Kortoppføringsmal',
	'shortentry_template_desc' => 'Malen  som bestemmer utseende på enslige oppføringer slik de er vist i selve bloggen eller arkivet.',
	'num_entries' => 'Antall oppføringer',
	'num_entries_desc' => 'Antall oppføringer i denne underbloggen som vil bli vist på forsiden.',
	'offset' => 'Forskyving',
	'offset_desc' => 'Hvis forskyving er satt til et tall, så vil den mengden av oppføringer hoppes over når siden lages. Du kan bruke dette til å lage en \'Tidligere oppføringer\'-liste for eksempel.',
	'comments' => 'Tillat kommentarer?',
	'comments_desc' => 'Avgjør om besøkende/brukere er i stand til å legge igjen kommentarer i denne underbloggen.',

	'publish_cats' => 'Publisér disse kategoriene',

	'setup_rss_head' => 'RSS- og Atom-konfigurasjon',
	'rss_use' => 'Lag strømmer',
	'rss_use_desc' => 'Avgjør om Pivot automatisk lager en RSS- og Atom-strøm for denne bloggen.',
	'rss_filename' => 'RSS-filnavn',
	'atom_filename' => 'Atom-filnavn',
	'rss_path' => 'Sti til strømmer',
	'rss_path_desc' => 'Den relative eller absolutte stien til katalogen hvor Pivot vil opprette strømfilene.',
//	'rss_size' => 'Feed Entry Length',	/* DEPRECATED */
//	'rss_size_desc' => 'The length (in characters) of an entry in the Feed files', /* DEPRECATED */
	'rss_full' => 'Lag fullstendige strømmer',
	'rss_full_desc' => 'Avgjør om Pivot lager fullstendige Atom- eller RSS-strømmer. Hvis satt til \'nei\', vil Pivot lage strømmer som kun inneholder korte beskrivelse og dermed gjøre strømmene dine mindre nyttige.',
	'rss_link' => 'Strømlenke',
	'rss_link_desc' => 'Lenken som sendes sammen med strømmen for å peke til hovedsiden. Hvis du ikke oppgir en lenke, vil Pivot sende bloggens startside som lenke.',
	'rss_img' => 'Strømbilde',
	'rss_img_desc' => 'Du kan oppgi et bilde som sendes sammen med strømmen. Noen strømleser vil vise dette bildet vedsiden av strømmen din. Oppgi fullstendig URL eller ingenting.',

	'lastcomm_head' => 'Innstillinger for siste kommentarer',
	'lastcomm_amount' => 'Vis hvor mange',
	'lastcomm_length' => 'Kutt ved lengde',
	'lastcomm_format' => 'Format',
	'lastcomm_format_desc' => 'Bestemmer utseende for \'Siste kommentarer\' på webloggens forside.',
	'lastcomm_nofollow' => 'Bruk \'Nofollow\'',
	'lastcomm_nofollow_desc' => 'For å motkjempe henvisningsspam, kan du velge å legge attributtet rel="nofollow" til alle lenker i kommentarer og henvisere, siden dette vil hindre spammere til å få en bedre siderangering (pagerank) hos Google.',

	'lastref_head' => 'Innstillinger for siste henvisere',
	'lastref_amount' => 'Vis hvor mange',
	'lastref_length' => 'Kutt ved lengde',
	'lastref_format' => 'Format',
	'lastref_format_desc' => 'Bestemmer utseende for \'Siste henvisere\' på webloggens forside.',
	'lastref_graphic' => 'Bruk grafikk',
	'lastref_graphic_desc' => 'Avgjør om siste henvisere bruker små ikoner for de mest vanlige søkemotoren som besøkende kan komme via.',
	'lastref_redirect' => 'Omadresser henvisere',
	'lastref_redirect_desc' => 'For å motkjempe henvisningsspam, kan du velge å omadressere utgående lenker til henvisere, siden dette ikke vil hjelpe spammere til å få en bedre siderangering (pagerank) hos Google.',

	'various_head' => 'Forskjellige innstillinger',
	'emoticons' => 'Bruk føleikon',
	'emoticons_desc' => 'Avgjør om føleikon som :-) blir transformert til sine grafiske ekvivalenter.',
	'encode_email_addresses' => 'Kode e-postadresser',
	'encode_email_addresses_desc' => 'Avgjør om e-postadresser kodes i javascript for å beskytte mot e-postsamlere (spam).',
	'target_blank' => 'Target Blank',
	'xhtml_workaround' => 'XHTML-fiks',
	'target_blank_desc' => 'Hvis \'Ja\', så vil alle lenker i dine oppføringer bli åpnet i et nytt nettleservindu. Hvis \'XHTML-fiks\', så vil alle lenker ha en rel="external" attributt, som ikke ødelegger velformet XHTML',

	'date_head' => 'Datovisningsinnstillinger',
	'full_date' => 'Fullt datoformat',
	'full_date_desc' => 'Bestemmer formatet for full dato og tid. Som regel brukt på toppen av en enslig oppføringsside',
	'entry_date' => 'Oppføringsdato',
	'diff_date' => 'Diff Date',
	'diff_date_desc' => '\'Diff Date\' er vanligvis brukt i forbindelse med \'Oppføringsdato\'. Oppføringsdatoen blir vist på hver oppføring i bloggen, mens \'Diff Date\' bare blir vist hvis datoen er forskjellig fra forrige oppføring.',
	'language' => 'Språk',
	'language_desc' => 'Bestemmer på hvilket språk datoene og tallene blir vist, og hvilket tegnsett som blir brukt.',

	'comment_head' => 'Kommentarinnstillinger',
	'comment_sendmail' => 'Sende e-post?',
	'comment_sendmail_desc' => 'Etterat en kommentar har blitt plassert, kan e-post bli sendt til bloggeieren.',
	'comment_emailto' => 'Send til',
	'comment_emailto_desc' => 'Oppgi e-postadressen(e) til den som skal motta melding. (Skill adressene med komma.)',
	'comment_texttolinks' => 'Tekst til lenker',
	'comment_texttolinks_desc' => 'Avgjør om URL-er og e-postadresse skal gjøres klikkbare.',
	'comment_wrap' => 'Bryt linjene i kommentarer etter',
	'comment_wrap_desc' => 'For å hindre at lange tekststrenger ødelegger sideoppsettet ditt, vil teksten bli brutt etter det oppgitte antall tegn.',
	'comments_text_0' => 'Tekst for \'ingen kommentarer\'',
	'comments_text_1' => 'Tekst for \'en kommentar\'',
	'comments_text_2' => 'Tekst for \'X kommentarer\'',
	'comments_text_2_desc' => 'Teksten som forteller hvor mange kommentarer det er. Hvis du lar feltet være tomt, vil Pivot bruke standard for det valgte språket.',

	'comment_pop' => 'Kommentarsprettopp?',
	'comment_pop_desc' => 'Avgjør om kommentarside (eller \'enslig oppføring\') vises i et sprettoppvindu eller i det original nettleservinduet.',
	'comment_width' => 'Sprettoppbredde',
	'comment_height' => 'Sprettopphøyde',
	'comment_height_desc' => 'Oppgi bredde og høyde (i piksler) for kommentarsprettoppene.',

	'comment_format' => "Formatering av kommentarer",
	'comment_format_desc' => "Denne bestemmer formatering av kommentarer på siden for oppføringer.",

	'comment_reply' => "Formatering av 'svar ..'",
	'comment_reply_desc' => "Denne bestemmer formatering av lenka som besøkende kan bruke for å svare på en spesifikk kommentar.",
	'comment_forward' => "Formatering av 'svar fra ..'",
	'comment_forward_desc' => "Denne bestemmer formatering av teksten som vises når en kommentar er besvart med en annen kommentar.",
	'comment_backward' => "Formatering av 'svar på ..'",
	'comment_backward_desc' => "Denne bestemmer formatering av teksten som vises når en kommentar er svar på en annen kommentar.",

	'comment_textile' => 'Tillat Textile',
	'comment_textile_desc' => 'Hvis \'Ja\', så har besøkende lov til å bruke <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> kommentarene sine.',
	'save_comment' => 'Lagre kommentar',
	'comment_gravatardefault' => 'Standard gravatar',
	'comment_gravatardefault_desc' => 'URL til standard gravatar-bilde.  Starter med http://',
	'comment_gravatarhtml' => 'Gravatar-HTML',
	'comment_gravatarhtml_desc' => 'HTML som skal settes inn for en gravatar. %img% byttes ut med URL-en til bildet.',
	'comment_gravatarsize' => 'Gravatar-størrelse',
	'comment_gravatarsize_desc' => 'Størrelse (i piksler) for gravatar-bildet. Standardverdien er 48.',
	
	'trackback_head' => 'Tilbaketråkkinnstillinger',
	'trackback_sendmail' => 'Sende e-post?',
	'trackback_sendmail_desc' => 'Etterat et tilbaketråkk har blitt plassert, kan e-post bli sendt til bloggeieren.',
	'trackback_emailto' => 'Send til',
	'trackback_emailto_desc' => 'Oppgi e-postadressen(e) til den som skal motta melding. (Skill adressene med komma.)',
	'trackbacks_text_0' => 'Tekst for \'ingen tilbaketråkk\'',
	'trackbacks_text_1' => 'Tekst for \'ett tilbaketråkk\'',
	'trackbacks_text_2' => 'Tekst for \'X tilbaketråkk\'',
	'trackbacks_text_2_desc' => 'Teksten som forteller hvor mange tilbaketråkk det er. Hvis du lar feltet være tomt, vil Pivot bruke standard for det valgte språket.',
	'trackback_pop' => 'Tilbaketråkksprettopp?',
	'trackback_pop_desc' => 'Avgjør om tilbaketråkkside (eller \'enslig oppføring\') vises i et sprettoppvindu eller i det original nettleservinduet.',
	'trackback_width' => 'Sprettoppbredde',
	'trackback_height' => 'Sprettopphøyde',
	'trackback_height_desc' => 'Oppgi bredde og høyde (i piksler) for tilbaketråkksprettoppene.',
	'trackback_format' => "Formatering av tilbaketråkk",
	'trackback_format_desc' => "Denne bestemmer formatering av tilbaketråkk på siden for oppføringer.",
	'trackback_link_format' => "Formatering av tilbaketråkklenke",
	'save_trackback' => 'Lagre tilbaketråkk',

	'saved_create' => 'Den nye bloggen har blitt opprettet.',
	'saved_update' => 'Bloggen har blitt oppdatert.',
	'deleted' => 'Bloggen har blitt slettet.',
	'confirm_delete' => 'Du er iferd med å slette bloggen  %1. Er du sikker?',

	'blogroll_heading' => 'Blogroll-innstillinger',
	'blogroll_id' => 'Blogrolling ID #',
	'blogroll_id_desc' => 'Du kan frivillig inkludere en <a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a>-blogroll i bloggen din. Blogrolling er en fremragende tjeneste for å vedlikeholde en liste av lenker, som viser hvor nylig de var oppdatert. Hvis du ikke ønsker dette, kan du bare hoppe over disse feltene.',
        'blogroll_fg' => 'Tekstfarge',
	'blogroll_bg' => 'Bakgrunnsfarge',
	'blogroll_line1' => 'Linjefarge 1',
	'blogroll_line2' => 'Linjefarge 2',
	'blogroll_c1' => 'Farge 1',
	'blogroll_c2' => 'Farge 2',
	'blogroll_c3' => 'Farge 3',
	'blogroll_c4' => 'Farge 4',
	'blogroll_c4_desc' => 'Disse fargene avgjør hvordan blogroll-en din vil se ut. Farge 1 til 4 gir en visuell indikasjon på hvor nylig en lenke er oppdatert.',
);


$lang['upload'] = array (
	//		File Upload		\\
	'preview' => 'Fullstendig listeforhåndsvisning',
	'thumbs' => 'Miniatyrforhåndsvisning',
	'create_thumb' => '(Lag miniatyr)',
	'title' => 'Filer',
	'thisfile' => 'Last opp en ny fil:',
	'button' => 'Last opp',
	'filename' => 'Filnavn',
	'thumbnail' => 'Miniatyr',
	'date' => 'Dato',
	'filesize' => 'Filstørrelse',
	'dimensions' => 'Bredde x høyde',
	'delete_title' => 'Slette bilde',
	'areyousure' => 'Er du sikker på at du vil slette filen %s?',
	'picheader' => 'Slette dette bildet?',
	'create' => 'opprett',
	'edit' => 'endre',

	'insert_image' => 'Sett inn et bilde',
	'insert_image_desc' => 'For å sette inn et bilde må du laste opp et bilde eller velge ett bilde som allerede er lastet opp.',
	'insert_image_popup' => 'Sett inn et sprettoppbilde',
	'insert_image_popup_desc' => 'For å lage en sprettopp for et bilde må du laste opp et bilde eller velge ett bilde som allerede er lastet opp. Så må du velge tekst eller miniatyr som utløser sprettoppen',
	'choose_upload' => 'last opp',
	'choose_select' => 'eller velg',
	'imagename' => 'Bildenavn',
	'alt_text' => 'Alternativ tekst (alt-tagg)',
	'align' => 'Align',
	'border' => 'Bord',
	'pixels' => 'piksler',
	'uploaded_as' => 'Filen din ble lastet opp som \'%s\'.',
	'not_uploaded' => 'Filen din ble ikke lastet opp, og følgende feil oppstod:',
	'center' => 'Senter (standard)',
	'left' => 'Venstre',
	'right' => 'Høyre',
	'inline' => 'Inline',
	'notice_upload_first' => 'Du må først velge eller laste opp et bilde',
	'select_image' => 'Velge bilde',
	'select_file' => 'Velge fil',

	'for_popup' => 'For sprettopp',
	'use_thumbnail' => 'Bruk miniatyr',
	'edit_thumbnail' => 'endre miniatyr',
	'use_text' => 'Bruk tekst',
	'insert_download' => 'Sett inn en nedlasting',
	'insert_download_desc' => 'For å lage en filnedlasting, må du laste opp en fil eller velge en tidligere opplastet fil. Så velger du om du vil bruke et ikon, tekst eller en miniatyr for å starte nedlastingen.',
	'use_icon' => 'Bruk ikon',
);


$lang['link'] = array (
	//		Link Insertion \\
	'insert_link' => 'Sett inn ei lenke',
	'insert_link_desc' => 'Sett inn ei lenke, ved å skrive en URL i feltet under. Beøskende vil se tittelen når de har musa over lenka.',
	'url' => 'URL',
	'title' => 'Tittel',
	'text' => 'Tekst',
);


//		Categories		\\
$lang['category'] = array (
	'edit_who' => 'Sett hvem som kan redigere i kategorien \'%s\'',
	'name' => 'Navn',
	'users' => 'Brukere',
	'make_new' => 'Opprett ny kategori',
	'create' => 'Opprett kategori',
	'canpost' => 'Velg brukerne du vil at skal ha lov til å post i denne kategorien',
	'same_name' => 'En kategori med dette navnet fins allerede',
	'need_name' => 'Denne kategorien trenger et navn',

	'allowed' => 'Tillatt',
	'allow' => 'Tillat',
	'denied' => 'Forbudt',
	'deny' => 'Forby',
	'edit' => 'Endre kategori',

	'delete' => 'Slette kategori',
	'delete_desc' => 'Velg \'ja\', hvis du vil slette denne kategorien',

	'delete_message' => 'I denne utgaven (1.10) av Pivot slettes bare kategorinavnet. In senere utgaver kan du velge hva du vil gjøre med oppføringene i kategorien.',
	'search_index_newctitle'   => 'Indeksér denne kategorien',
	'search_index_newcdesc'    => 'Sett til \'Nei\' bare hvis du ikke vil at besøkende skal kunne søke i denne kategorien.',
	'search_index_editcheader' => 'Indeksér kategori',

	'order' => 'Sorteringsregler',
	'order_desc' => 'Kategorier med lav sorteringsverdi vil vises høyere i lista. Hvis alle nummerne er de samme, så sorteres kategoriene alfabetisk.',
	'public' => 'Offentlig kategori',
	'public_desc' => 'Hvis satt til \'Nei\', så vil denne kategorien kunne være synlig for registrerte besøkende. (Gjelder kun for levende sider.)',
	'hidden' => 'Skjult kategori',
	'hidden_desc' => 'Hvis satt til \'Ja\', så vil denne kategorien bli skjult i arkivoppføringene. (Gjelder kun for levende sider.)',
		
);


$lang['entries'] = array (
	//		Entries			\\
	'post_entry' => "Send oppføring",
	'preview_entry' => "Forhåndsvis oppføring",

	'first' => 'først',
	'last' => 'sist',
	'next' => 'neste',
	'previous' => 'forrige',

	'jumptopage' => 'hopp til side (%num%)',
	'filteron' => 'filtrer med (%name%)',
	'filteroff' => 'filtrering av',
	'title' => 'Tittel',
	'subtitle' => 'Undertittel',
	'introduction' => 'Introduksjon',
	'body' => 'Kropp',
	'publish_on' => 'Publisert på',
	'status' => 'Status',
	'post_status' => 'Status for oppføring',
	'category' => 'Kategori',
	'select_multi_cats' => '(Ctrl-klikk for å velge flere kategorier samtidig)',
	'last_edited' => "Sist endret på",
	'created_on' => "Opprettet på",
	'date' => 'Dato',
	'author' => 'Forfatter',
	'code' => 'Kode',
	'comm' => '# komm',
	'track' => '# tråkk',
	'name' => 'Navn',
	'allow_comments' => 'Tillatt kommentarer',

	'delete_entry' => "Slett oppføring",
	'delete_entry_desc' => "Slett denne oppføringen og korresponderende kommentarer",
	'delete_one_confirm' => "Er du sikker på at du vil slette denne oppføringen?",
	'delete_multiple_confirm' => "Er du sikker på at du vil slette disse oppføringene?",

	'convert_lb' => 'Omgjør linjeskift',
	'always_off' => '(Denne er alltid avslått under Wysiwyg-redigering)',
	'be_careful' => '(Vær forsiktig med denne!)',
	'edit_comments' => 'Endre kommentarer',
	'edit_comments_desc' => 'Endre kommentarene som har blitt postet på denne oppføringen',
	'edit_comment' => 'Endre kommentar',
	'delete_comment' => 'Slett kommentar',
	'edit_trackback' => 'Endre tilbaketråkk',
	'delete_trackback' => 'Slett tilbaketråkk',
	'block_single' => 'Blokker IP %s',
	'block_range' => 'Blokker IP-område %s',
	'unblock_single' => 'Avblokker IP %s',
	'unblock_range' => 'Avblokker IP-område %s',
	'trackback' => 'Tilbaketråkk-ping',
	'trackback_desc' => 'Send tilbaketråkk-pinger til følgende URL(-er). For å sende til flere URL-er, plasser hver URL på en separat linje.',
	'keywords' => 'Nøkkelord',
	'keywords_desc' => 'Bruk denne for å sette nøkkelord som kan søkes etter for å finne denne oppføringen, eller for å sette en enklere URL for denne oppføringen.',
	'vialink' => "Via-lenke ",
	'viatitle' => "Via-tittel",
	'via_desc' => 'Bruk denne for å sette en lenke til kilden for denne oppføringen.',
	'entry_catnopost' => 'Du har ikke lov til å poste i kategorien: \'%s\'.',
	'entry_saved_ok' => 'Din oppføring \'%s\' ble lagret uten problemer.',
	'entry_ping_sent' => 'En tilbaketråkk-ping har blitt sendt til \'%s\'.',
);


//		Form Fun		\\
$lang['forms'] = array (
	'c_all' => 'Kryss av alle',
	'c_none' => 'Fjern alle kryss',
	'choose' => '- velg et alternativ -',
	'publish' => 'Sett status til \'publisert\'',
	'hold' => 'Sett status til \'vent\'',
	'delete' => 'Slett dem',
	'generate' => 'Publiser og generer',

	'with_checked_entries' => "Med de avkryssede oppføringene, utfør:",
	'with_checked_files' => "Med de avkryssede filene, utfør:",
	'with_checked_templates' => 'Med de avkryssede malene, utfør:',
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'kan ikke åpne katalog, kontroller dine rettigheter.',
	'path_read' => 'kan ikke lese katalog, kontroller dine rettigheter.',
	'path_write' => 'kan ikke skrive til katalog, kontroller dine rettigheter.',

	'file_open' => 'kan ikke åpne fil, kontroller dine rettigheter.',
	'file_read' => 'kan ikke lese fil, kontroller dine rettigheter.',
	'file_write' => 'kan ikke skrive til fil, kontroller dine rettigheter.',
);


//		Notices			\\
$lang['notice'] = array (
	'comment_saved' => "Kommentaren har blitt lagret.",
	'comment_deleted' => "Kommentaren har blitt slettet.",
	'comment_none' => "Denne oppføringen har ingen kommentarer.",
	'trackback_saved' => "Tilbaketråkket har blitt lagret.",
	'trackback_deleted' => "Tilbaketråkket har blitt slettet.",
	'trackback_none' => "Denne oppføringen har ingen tilbaketråkk.",
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'Stem \'%val%\' på denne oppføringen',
	'good' => 'Bra',
	'bad' => 'Dårlig',
	'already' => 'Du har allerede stemt på denne oppføringen',
	'register' => 'Din stemme for  \'%val%\' har blitt registrert',
);


$lang['comment'] = array (
	'register' => 'Din kommentar har blitt lagret.',
	'preview' => 'Forhåndsvisning av kommentaren din. Klikk \'Send kommentar\' for å lagre den.',
	'duplicate' => 'Kommentaren din har ikke bli lagret, siden den ser ut til å være en duplikat av en tidligere oppføring',
	'no_name' => 'Du må skrive navnet ditt (eller et alias) i \'navn\'-feltet. Klikk \'Send kommentar\' for å lagre den permanent.',
	'no_comment' => 'Du må skrive noe i \'kommentar\'-feltet. Klikk \'Send kommentar\' for å lagre den permanent.',
	'too_many_hrefs' => 'Maksimalt antall lenker oversteget. Stopp spamming.',
        'email_subject' => '[Kommentar]',
);


$lang['comments_text'] = array (
	'0' => "Ingen kommentarer",
	'1' => "Bare en kommentar",
	'2' => "Allerede %num% kommentarer",
);

$lang['trackbacks_text'] = array (
	'0' => "Ingen tilbaketråkk",
	'1' => "Bare et tilbaketråkk",
	'2' => "Allerede %num% tilbaketråkk",
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => "Arkiv",
	'next_archive' => "Neste arkiv",
	'previous_archive' => "Forrige arkiv",
	'last_comments' => "Siste kommentarer",
	'last_referrers' => "Siste henvisere",
	'calendar' => "Kalender",
	'links' => "Lenker",
	'xml_feed' => "XML-strøm (RSS 1.0)",
        'atom_feed' => "XML: Atom-strøm",
	'powered_by' => "Drevet av",
	'blog_name' => "Bloggnavn",
	'title' => "Tittel",
	'excerpt' => "Utdrag",
	'name' => "Navn",
	'email' => "E-post",
	'url' => "URL",
	'date' => "Dato",
	'comment' => "Kommentar",
	'ip' => "IP-adresse",
	'yes' => "Ja",
	'no' => "Nei",
	'emoticons' => "Føleikon",
	'emoticons_reference' => "Åpne referanse for føleikon",
	'textile' => "Textile",
	'textile_reference' => "Åpne referanse for Textile",
	'post_comment' => "Send kommentar",
	'preview_comment' => "Forhåndsvis kommentar",
	'remember_info' => "Husk personlig info?",
        'notify' => "Varsling",
	'notify_yes' => "Ja, send meg en e-post når noen svarer.",
	'register' => "Registrer ditt brukernavn / logg inn",
	'disclaimer' => "<b>Merk:</b> Alle HTML-tagger utenom &lt;b&gt; og &lt;i&gt; fjernes fra kommentarer. Du kan lage lenker ved å skrive URLen eller e-postadressen rett inn i teksten.",
	'search_title' => "Søkeresultat",
	'search' => "Søk!",
	'nomatches' => "Ingen treff for '%name%'. Prøv med noe annet.",
	'matches' => "Treff for '%name%':",
        'about' => "Om",
        'stuff' => "Diverse",
        'linkdump' => "Lenkedump",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
	'title' => 'Endre brukerfelt',
	'edit' => 'Endre',
	'create' => 'Opprette',

	'dispname' => 'Visningsnavn',
	'intname' => 'Internt navn',
	'intname_desc' => 'Internt navn er navnet på dette feltet slik det vil vises når du forteller Pivot å vise det i en mal.',
	'size' => 'Størrelse',
	'rows' => 'Rekker',
	'cols' => 'Kolonner',
	'maxlen' => 'Maksimum lengde',
	'minlevel' => 'Min. brukernivå',
	'filter' => 'Filtrer med',
	'filter_desc' => 'Ved å filtrere dette feltet, kan du begrense hva slags inndata som kan bli brukt',
	'no_filter' => 'Ingenting',
	'del_title' => 'Bekreft sletting',
	'del_desc' => 'Sletting av dette brukerfeltet (<b>%s</b>) vil også slette all data som brukerne har lagret i det, samt at alle instanser av det i malen vil vises tomt.',

	'already' => 'Dette navnet er allerede i bruk',
	'int' => 'Internt navn må være lenger enn 3 tegn',
	'short_disp' => 'Visningsnavn må være lenger enn 3 tegn',
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'Skriptbokmerker',
	'bm_add' => 'Legg til skriptbokmerke',
	'bm_withlink' => 'Pivot » Ny (med lenke)',
	'bm_withlink_desc' => 'Dette skriptbokmerket åpner et vindu med en ny oppføring som inneholder en lenke til siden den ble åpnet fra.',

	'bm_nolink' => 'Pivot » Ny',
	'bm_nolink_desc' => 'Dette skriptbokmerket åpner et vindu med en ny tom oppføring.',

	'bookmarklets_info' => 'Du kan bruke skriptbokmerker for å raskt skrive nye oppføringer med Pivot. For å legge til et skriptbokmerke i en nettleser, bruk en av de følgende valgene: (Eksakt tekst varierer fra nettleser til nettleser.)',
	'bookmarklets_info_1' => 'Klikk og dra skriptbokmerket til din lenkeverktøylinje eller din nettlesers \'Bokmerker\'-knapp.',
	'bookmarklets_info_2' => 'Høyreklikk på skriptbokmerket og velg \'Legg til i bokmerker\'.',
);

// Accessibility - These are used for form fields, labels, fieldsets etc.
// for Web Content Accessibility Guidelines & 508 compliancy issues.
// see: http://bobby.watchfire.com/bobby/html/en/index.jsp
// JM =*=*= 2004/10/04
// 2004/11/25 =*=*= JM - minor correction for tim
$lang['accessibility'] = array(
        'search_idname'      => 'søk',
        'search_formname'    => 'Søk etter ord brukt i oppføringer på dette nettstedet',
        'search_fldname'     => 'Skriv inn søkeord her:',
        'search_placeholder' => 'Skriv inn søkeord',

        'calendar_summary'   => 'Denne tabellen representerer en kalender over oppføringene i webloggen med lenker på dager med oppføringer.',
        'calendar_noscript'  => 'Denne kalenderen gjør det mulig å velge oppføringer i denne webloggen',
        /*
        2-letter language code, used to designate the principal language used on the site
        see: http://www.oasis-open.org/cover/iso639a.html
        */

        'lang' => $langname,
) ;


$lang['snippets_text'] = array (
    'word_plural'     => 'ord',
    'image_single'    => 'bilde',
    'image_plural'    => 'bilder',
    'download_single' => 'fil',
    'download_plural' => 'filer',
);

$lang['trackback'] = array (
    'register' => 'Ditt tilbaketråkk har blitt lagret.',
    'duplicate' => 'Tilbaketråkket ditt har ikke bli lagret, siden den ser ut til å være en duplikat.',
    'too_many_hrefs' => 'Maksimalt antall lenker oversteget. Stopp spamming.',
    'noid'      => 'Ingen tilbaketråkk-ID (tb_id)',
    'nourl'     => 'Ingen URL (url)',
    'tracked'   => 'Tilbaketråkket',
    'email_subject' => '[Tilbaketråkk]',
);

$lang['commentuser'] = array (
    'title'             => 'Pivot brukerinnlogging',
    'header'            => 'Logg inn som registrert besøkende',
    'logout'            => 'Logg ut.',
    'loggedout'         => 'Logget ut',
    'login'             => 'Logg inn',
    'loggedin'          => 'Logget inn',
    'loggedinas'        => 'Logget inn som',
    'pass_forgot'       => 'Glemt passordet?',
    'register_new'      => 'Registrer et nytt brukernavn.',
    'register'          => 'Registrer som en besøkende',
    'register_info'     => 'Fyll ut følgende informasjon. <strong>Vær sikker på at e-postadressen er gyldig</strong>, fordi vi sender e-post med verifikasjonsdata til den adressen.',
    'pass_note'         => 'Merk: Det er mulig for eieren av dette nettstedet å se passordet ditt. <br /> <em>Ikke</em> bruk et passord som du er redd for eller bruker andre steder!',
    'show_email'        => 'Vis e-postadressen min sammen med kommentaren',
    'notify'            => 'Varsle om nye oppføringer på e-post',
    'def_notify'        => 'Standard varsling ved svar',
    'register'          => 'Registrer',
    'pass_invalid'      => 'Ugyldig passord',
    'nouser'            => 'Ukjent brukernavn',
    'change_info'       => 'Her kan du endre informasjonen din.',
    'pref_edit'         => 'Endre dine innstillinger.',
    'pref_change'       => 'Endre innstillinger',
    'options'           => 'Innstillinger',
    'user_exists'       => 'Brukernavn eksisterer allerede - velg et annet navn.',
    'email_note'        => 'Du må oppgi en gyldig e-postadresse, slik at vi kan verifisere kontoen din. Du kan også velge å vise e-postadressen din etter kommentarene du skriver.',
    'stored'            => 'Endringene har blitt lagret',
    'verified'          => 'Kontoen er verifisert. Logg inn.',
    'not_verified'      => 'Verifikasjonskoden ser ut til å være ugyldig.',
    'pass_sent'         => 'Passord er sendt til e-postadressen du oppga.',
    'user_pass_nomatch' => 'Brukernavn og passord stemmer ikke overens.',
    'pass_send'         => 'Send passord',
    'pass_send_desc'    => 'Hvis du har glemt passordet ditt, fyll inn brukernavn og e-postadresse og så vil Pivot sende deg passordet på e-post.',
    'oops'              => 'Oops',
    'back'              => 'Tilbake til',
    'back_login'        => 'Tilbake til innlogging',
    'forgotten_pass_mail' => "Ditt glemte passord for Pivot '%name%' er: \n\n%pass%\n\nIkke glem det igjen ;-)\n\nFor å logge inn på din konto, bruk følgende lenke:\n %link%"
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
