<?php
//Fran&ccedil;ais (French)

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2rd line.

// French translation of Pivot lang file
// Created by Alexandre Drahon (adrahon@adrahon.org)
// http://www.adrahon.org
//
// Last updated by Pivot (www.pivotlog.net) 07.11.2004
// New strings added. Translation required.
//

// allow for different encoding for non-western languages
$encoding='iso-8859-1';
$langname="fr";


//		General		\\
$lang['general'] = array (
	'yes' => 'Oui',	//affirmative
	'no' => 'Non',		//negative
	'go' => 'Suivant',	//proceed

	'minlevel' => 'Vous n&#8217;avez pas l&#8217;autorisation d&#8217;acc&eacute;der &agrave; cette partie de Pivot',
	'email' => 'E-mail',			
	'url' => 'URL',
	'further_options' => 'Options avanc&eacute;es',
	'basic_view' => 'Affichage de base',
	'basic_view_desc' => 'Afficher uniquement les champs les plus courants',
	'extended_view' => 'Affichage &eacute;tendu',
	'extended_view_desc' => 'Afficher tous les champs &eacute;ditables',
	'select' => 'S&eacute;lectionner',
	'cancel' => 'Annuler',
	'delete' => 'Supprimer',
	'welcome' => 'Bienvenue sur %build%.',
	'write' => '&Eacute;crire',
	'write_open_error' => 'Erreur d&#8217;&eacute;criture. Impossible d&#8217;ouvrir le fichier en &eacute;criture',
	'write_write_error' => 'Erreur d&#8217;&eacute;criture. Impossible d&#8217;&eacute;crire dans le fichier',
	'done' => 'Fait.',
	'shortcuts' => 'Raccourcis',
	'cantdelete' => 'Vous n&#8217;&ecirc;tes pas autoris&eacute; &agrave; supprimer l&#8217;entr&eacute;e %title%.',
	'cantdothat' => 'Vous n&#8217;&ecirc;tes pas autoris&eacute; &agrave; faire cela avec l&#8217;entr&eacute;e %title%.',
	'cantdeletelast' => "You can not delete the last entry. You must first post a new entry, before deleting this entry",
);


$lang['userlevels'] = array (
		'Superadmin', 'Administrateur', 'Avanc&eacute;', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin, 
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array (
	'z&eacute;ro', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize'
);


$lang['months'] = array (
	'janvier', 'f&eacute;vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao&ucirc;t', 'septembre', 'octobre', 'novembre', 'd&eacute;cembre'
);


$lang['months_abbr'] = array (
	'jan', 'f&eacute;v', 'mar', 'avr', 'mai', 'jui', 'jul', 'ao&ucirc;', 'sep', 'oct', 'nov', 'd&eacute;c'
);


$lang['days'] = array (
	'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'
);


$lang['days_abbr'] = array (
	'dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam'
);


$lang['days_calendar'] = array (
	'S', 'M', 'T', 'W', 'T', 'F', 'S'
); 

	
$lang['datetime_words'] = array (
	'Ann&eacute;e', 'Mois', 'Semaine', 'Jour', 'Heure', 'Minute', 'Seconde'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'Identification',
	'name' => 'Nom d&#8217;utilisateur',
	'pass' => 'Mot de passe',
	'remember' => 'Se rappeler',
	'rchoice' => array (
		'0' => 'Rien',
		'1' => 'Mon nom d&#8217;utilisateur et mon mot de passe',
		'2' => 'Que je veux rester connect&eacute;'
	),
	'delete_cookies_desc' => 'Si vous &ecirc;tes certain que vous utilisez les bons nom d&#8217;utilisateur et mot de passe et que vous avez <br />des probl&egrave;mes d&#8217;acc&egrave;s, vous pouvez essayer de supprimer les cookies pour ce domaine :',
	'delete_cookies' => 'Supprimer les cookies',
	'retry' => 'Nom d&#8217;utilisateur ou mot de passe incorrect',
	'banned' => 'Vous avez &eacute;chou&eacute; &agrave; l&#8217;identification en 10 tentatives. L&#8217;identification &agrave; partir de cette adresse IP est bloqu&eacute;e pour 12 heures.',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => 'Accueil',
	'entries' => 'Entr&eacute;es',
	'submit' => 'Nouvelle Entr&eacute;e',
	'comments' => 'Commentaires',
	'trackbacks' => 'Trackbacks',
	'modify' => 'Modifier des Entr&eacute;es',
	'userinfo' => 'Mes Informations',
	'u_settings' => 'Mes Pr&eacute;f&eacute;rences',
	'u_marklet' => 'Mes Favoris',
	'files' => 'Gestion des M&eacute;dias',
	'upload' => 'Upload',
	'stats' => 'Statistiques',
	'admin' => 'Administration',

	'main_title' => 'Menu g&eacute;n&eacute;ral de Pivot',
	'entries_title' => 'Menu des entr&eacute;es',
	'submit_title' => 'Write and Publish a new Entry',
	'submit_title' => 'Nouvelle entr&eacute;e',
	'comments_title' => '&Eacute;diter ou supprimer des commentaires',
	'modify_title' => 'Modifier une entr&eacute;e',
	'userinfo_title' => 'Voir mes informations personnelles',
	'u_settings_title' => '&Eacute;diter mes param&egrave;tres personnels',
	'u_marklet_title' => 'Cr&eacute;er des favoris',
	'files_title' => 'G&eacute;rer et ajouter des fichiers',
	'upload_title' => 'Upload de fichiers',
	'uploaded_success' => 'Upload du fichier r&eacute;ussi',
	'stats_title' => 'Afficher les logs et les statistiques.',
	'updatetitles_title' => 'Afficher les logs et les statistiques.',
	'admin_title' => 'Menu g&eacute;n&eacute;ral de l&#8217;Administration',
	'recent_entries' => 'Derni&egrave;res Entr&eacute;es',
	'recent_comments' => 'Derniers Commentaires',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
	//'trebuild\' => 'Rebuild all Files', rolled into maintenance
	'seeusers' => 'Utilisateurs',
	'seeconfig' => 'Configuration',
	'filemappings' => 'Correspondance de fichiers',
	'templates' => 'Gabarits',
	'maintenance' => 'Maintenance',
	'regen' => 'Reconstruire tous les fichiers',
	'blogs' => 'Weblogs',
	'categories' => 'Cat&eacute;gories',
	'verifydb' => 'V&eacute;rifier la base de donn&eacute;es',
	'buildindex' => 'Reconstruire l&#8217;index',
	'buildsearchindex' => 'Reconstruire l&#8217;index de recherche',
	'buildfrontpage' => 'Reconstruire les pages d&#8217;accueil',
	'sendping' => 'Envoyer les pings',


	'backup' => 'Sauvegarde',
	'description' => 'Description',
	'conversion' => 'Conversion',
	'seeusers_title' => 'Cr&eacute;er, &eacute;diter et supprimer des utilisateurs',
	'userfields' => 'Champs d&#8217;information utilisateur',
	'userfields_title' => 'Cr&eacute;er, &eacute;diter et supprimer des champs d&#8217;information utilisateur',
	'seeconfig_title' => '&Eacute;diter le fichier de configuration',
	'filemappings_title' => 'Affiche les fichiers de votre site et leur fonction',
	'templates_title' => 'Cr&eacute;er, &eacute;diter et supprimer des gabarits',
	'maintenance_title' => 'Fonctions de maintenance courante des fichiers de Pivot',
	'regen_title' => 'Reconstruire les fichiers et les archives g&eacute;n&eacute;r&eacute;s par Pivot',
	'blogs_title' => 'Cr&eacute;er, &eacute;diter et supprimer des Weblogs',
	'blogs_edit_title' => '&Eacute;diter les param&egrave;tres de Weblog de ',
	'categories_title' => 'Cr&eacute;er, &eacute;diter et supprimer les cat&eacute;gories',
	'verifydb_title' => 'V&eacute;rifier l&#8217;int&eacute;grit&eacute; de votre base de donn&eacute;es',
	'buildindex_title' => 'Reconstruire les index de votre base de donn&eacute;es',
	'buildsearchindex_title' => 'Reconstruire l&#8217;index de recherche, qui permet les recherches dans les entr&eacute;es',
	'buildfrontpage_title' => 'Reconstruire la page d&#8217;accueil, les derni&egrave;res archives et les fichiers RSS de chaque weblog.',
	'backup_title' => 'Cr&eacute;er une sauvegarde de vos entr&eacute;es',
	'backup_config' => 'Backup of configuration files',
	'backup_config_desc' => 'This will let you download a zip file containing your configuration files',
	'ipblocks' => 'Blocage d&#8217;IP',
	'ipblocks_title' => 'Afficher et &eacute;diter les adresses IP bloqu&eacute;es.',
	'ipblocks_stored' => 'Les adresses IP ont &eacute;t&eacute; enregistr&eacute;es.',
	'ipblocks_store' => 'Enregistrer ces adresses IP',
	'ignoreddomains' => 'Ignored Domains',
	'ignoreddomains_title' => 'View and Edit the Ignored Domains.',
	'ignoreddomains_stored' => 'The Ignored Domains have been stored.',
	'ignoreddomains_store' => 'Store these Ignored Domains',
	'fileexplore' => 'Explorateur de fichiers',
	'fileexplore_title' => 'Afficher les fichiers (fichiers texte et base de donn&eacute;es)',
	'sendping_title' => 'Envoyer les pings pour mise &agrave; jour des trackers.',
	'buildindex_start' => 'Construction de l&#8217;index en cours. Cela peut prendre un certain temps, veuillez ne pas interrompre la proc&eacute;dure.',
	'buildsearchindex_start' => 'Construction de l&#8217;index de recherche. Cela peut prendre un certain temps, veuillez ne pas interrompre la proc&eacute;dure.',
	'buildindex_finished' => 'Termin&eacute; ! La construction de l&#8217;index a pris %num% secondes',

	'filemappings_desc' => 'Vous pouvez voir ci-dessous un aper&ccedil;u de chaque weblog de l&#8217;installation Pivot, ainsi que la liste des fichiers cr&eacute;&eacute;s par Pivot et les gabarits utilis&eacute;s pour les cr&eacute;er. Cela peut &ecirc;tre &eacute;galement utile pour identifier pr&eacute;cis&eacute;mment des probl&egrave;mes de cr&eacute;ation de fichiers.',
	
	'debug' => 'Open Debug window',

);


$lang['templates'] = array (
	'rollback' => 'Annulation',
	'create_template' => 'Cr&eacute;er un gabarit',
	'create_template_info' => 'Cr&eacute;er un gabarit Pivot en partant de z&eacute;ro',
	'no_comment' => 'Pas de commentaires',
	'comment' => 'Commentaires*',
	'comment_note' => '(*Note : les commentaires ne peuvent &ecirc;tre enregistr&eacute;s qu&#8217;au <b>premier</b> enregistrement des modifications ou &agrave; la cr&eacute;ation)',
	'create' => 'Cr&eacute;er le gabarit',
	'editing' => 'Edition',
	'filename' => 'Nom de fichier',
	'save_changes' => 'Enregistrer les modifications',
	'save_template' => 'Enregistrer le gabarit',
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
	'seeusers' => 'Cr&eacute;er, &eacute;diter et supprimer des utilisateurs',
	'seeconfig' => '&Eacute;diter le fichier de configuration',
	'gabarits' => 'Cr&eacute;er, &eacute;diter et supprimer des gabarits',
	'maintenance' => 'Effectuer la maintenance courante des fichiers de Pivot.',
	'regen' => 'Reconstruire toutes les pages g&eacute;n&eacute;r&eacute;es par Pivot',
	'blogs' => 'Cr&eacute;er, &eacute;diter et supprimer les weblogs publi&eacute;s par Pivot',
);


//		Maintenance		\\
$lang['maint'] = array (
	'title' => 'Maintenance',
	'gen_arc_title' => 'G&eacute;n&eacute;rer les archives', /* bob notes: redundant, see 'regen\' */
	'gen_arc_text' => 'Reg&eacute;n&eacute;rer toutes vos archives', /* bob notes: redundant, see 'regen\' */
	'xml_title' => 'V&eacute;rifier les fichiers XML', /* bob notes: replace with more general 'Verify DB' */
	'xml_text' => 'V&eacute;rifier (et r&eacute;parer si n&eacute;cessaire) l&#8217;int&eacute;griter des fichiers XML', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'Sauvegarde',
	'backup_text' => 'Cr&eacute;er une sauvegarde des fichiers essentiels de Pivot',
);


//		Stats and referers		\\
$lang['stats'] = array (
	'show_last' => 'Voir les',
	'20ref' => '20 derniers r&eacute;f&eacute;rents',
	'50ref' => '50 derniers r&eacute;f&eacute;rents',
	'allref' => 'tous les r&eacute;f&eacute;rents',
	'updateref' => 'Mise &agrave; jour des correspondances r&eacute;f&eacute;rent-titre',
	'showall' => "both blocked and non-blocked lines",
	'updateref' => "Update the referer to title mappings",
	'hostaddress' => 'Host-address (adresse IP)',
	'whichpage' => 'Page',

	'getting' => 'Obtention des nouveaux titres',
	'awhile' => 'Cela peut prendre un certain temps, veuillez ne pas interrompre la proc&eacute;dure.',
	'firstpass' => 'Premier passage',
	'secondpass' => 'Deuxi&egrave;me passage',
	'nowuptodate' => 'Les correspondances r&eacute;f&eacute;rent-titre sont maintenant &agrave; jour.',
	'finished' => 'Termin&eacute;',
);


//		User Info		\\
	$lang['userinfo'] = array (
	'editfields' => '&Eacute;diter les champs utilisateur',
	'desc_editfields' => '&Eacute;diter les champs que les utilisateurs peuvent remplir pour se d&eacute;crire',
	'username' => 'Nom d&#8217;utilisateur',
	'pass1' => 'Mot de passe',
	'pass2' => 'Mot de passe (confirmer)',
	'email' => 'e-mail',
	'userlevel' => 'Niveau utilisateur',
	'userlevel_desc' => 'Le niveau utilisateur d&eacute;termine quelles actions l&#8217;utilisateur peut effectuer dans Pivot.',
	'language' => 'Langue',
	'lastlogin' => 'Last Login',
	'edituser' => '&Eacute;diter l&#8217;utilisateur',  //the link to.. well, edit the user (also the title)
	'edituserinfo' => '&Eacute;diter les informations utilisateur',
	'newuser' => 'Cr&eacute;er un nouvel utilisateur',
	'desc_newuser' => 'Cr&eacute;er un nouveau compte dans Pivot, permettant d&#8217;&eacute;crire dans un weblog.',
	'newuser_button' => 'Cr&eacute;er',
	'edituser_button' => 'Modifier',
	'pass_too_short' => 'Le mot de passe doit faire au moins 4 lettres.',
	'pass_equal_name' => 'Password can\'t be the same as username.',
	'pass_dont_match' => 'Les mots de passe ne correspondent pas',
	'username_in_use' => 'Le nom d&#8217;utilisateur existe d&eacute;j&agrave;',
	'username_too_short' => 'Le nom doit faire au moins 3 lettres',
	'username_not_valid' => 'Le nom d&#8217;utilisateur ne peut contenir que des caract&egrave;res alphanum&eacute;riques (A-Z, 0-9) et des caract&egrave;res de soulignement (_).',
	'not_good_email' => 'L&#8217;adresse e-mail n&#8217;est pas valide',
	'c_admin_title' => 'Confirmater la cr&eacute;ation d&#8217;un administrateur',
	'c_admin_message' => 'Un '.$lang['userlevels']['1'].' a un acc&egrave;s complet &agrave; Pivot, il peut &eacute;diter toutes les entr&eacute;es, tous les commentaires et modifier tous les param&egrave;tres de configuration. Voulez-vous vraiment que %s soit '.$lang['userlevels']['1'].' ?',
);


//		Config Page		\\
	$lang['config'] = array (
	'save' => 'Sauvegarder les param&egrave;tres',

	'sitename' => 'Nom du site',
	'defaultlanguage' => 'Langue par d&eacute;faut',
	'defaultencoding' => 'Use encoding',
	'defaultencoding_desc' => 'This defines the encoding that is used (like utf-8 or iso-8859-1). You should leave this blank, unless you know what you\'re doing. If you leave this blank it will use the appropriate settings from the language files.',
	'siteurl' => 'URL du site',
	'header_fileinfo' => 'Information fichier',
	'localpath' => 'Chemin local',
	'debug_options' => 'Options de d&eacute;bogage',
	'debug' => 'Mode d&eacute;bogage',
	'debug_desc' => 'Affiche diverses informations de d&eacute;bogage un peu partout.',
	'log' => 'Fichiers de log',
	'log_desc' => 'Enregistre des fichiers de log de diverses activit&eacute;s.',

	'unlink' => 'Utiliser unlink',
	'unlink_desc' => 'Certains serveurs sur lesquels safe_mode est activ&eacute; peuvent n&eacute;cessiter cette option. Sur la plupart des serveurs, elle n&#8217;aura aucun effet.',
	'chmod' => 'Chmod des fichiers en',
	'chmod_desc' => 'Certains serveurs n&eacute;c&eacute;ssitent un chmod sp&eacute;cifique sur les fichiers cr&eacute;&eacute;s. Le valeurs les plus courantes sont &#8216;0644&#8217; et &#8216;0755&#8217;. Ne changez cette option que si vous savez ce que vous faites.',
	'header_uploads' => 'Upload de fichiers',
	'upload_path' => 'Chemin d&#8217;upload',
	'upload_accept' => 'Types accept&eacute;s',
	'upload_extension' => 'Extension par d&eacute;faut',
	'upload_save_mode' => '&Eacute;craser',
	'make_safe' => 'Nettoyer le nom',
	'c_upload_save_mode' => 'Incr&eacute;menter le nom',
	'max_filesize' => 'Taille maximale',
	'header_datetime' => 'Date/Heure',
	'timeoffset_unit' => 'Unit&eacute; du D&eacute;calage',
	'timeoffset' => 'D&eacute;calage',
	'header_extra' => 'Autres Param&egrave;tres',
	'wysiwyg' => 'Utiliser l&#8217;&eacute;diteur Wysiwyg',
	'wysiwyg_desc' => 'D&eacute;termine si l&#8217;&eacute;diteur Wysiwyg est activ&eacute; par d&eacute;faut. Chaque utilisateur peut changer ce param&egrave;tre dans la rubrique &#8216;Mes Informations&#8217;.',
	'basic_view' => 'Use Basic View',
	'basic_view_desc' => 'Determines whether the \'New Entry\' opens in Basic View or in Extended View.',
	'def_text_processing' => 'Traitement &agrave; effectuer',
	'text_processing' => 'Traitement du texte',
	'text_processing_desc' => 'D&eacute;termine le traitement par d&eacute;faut effectu&eacute; sur le texte lorsqu&#8217;un utilisateur utilise l&#8217;&eacute;diteur non wysiwyg. &#8216;Conversion de saut de ligne&#8217; remplace uniquement les sauts de ligne par des balises &lt;br&gt;. <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> est un style de balisage simple et puissant.',
	'none' => 'Aucun',
	'convert_br' => 'Conversion de saut de ligne en &lt;br /&gt;',
	'textile' => 'Textile',
	'markdown' => 'Markdown',
	'markdown_smartypants' => 'Markdown and Smartypants',

	'allowed_cats' => 'Allowed Categories',
	'allowed_cats_desc' => 'This user is allowed to post entries in the selected categories',
	'delete_user' => "Delete user",
	'delete_user_desc' => "You can delete this user if you would like. All of their posts will remain, but they will no longer be able to login",
	'delete_user_confirm' => 'You\'re about to remove access for %s. Are you sure you want to do this?',
	
	'setup_ping' => 'Configuration du ping',
	'ping_use' => 'Ping des trackers',
	'ping_use_desc' => 'D&eacute;termine si Pivot pr&eacute;vient automatiquement les trackers comme weblogs.com lorsque vous publiez une nouvelle entr&eacute;e. Les services comme blogrolling.com d&eacute;pendent de ces pings.',
	'ping_urls' => 'Envoyer un ping &agrave; ces URL',
	'ping_urls_desc' => 'Vous pouvez indiquer plusieurs URL. Pour que cela fonctionne n&#8217;incluez pas http:// dans l&#8217;URL. Placez simplement chaque serveur sur une ligne ou s&eacute;parez-les par un caract&egrave;re &#8216;|&#8217;. Les serveurs les plus courants sont :<br /><b>rpc.weblogs.com/RPC2</b> (pour weblogs.com, le plus utilis&eacute;)<br /><b>pivotlog.net/pinger</b> (pour pivotlog, pas encore fonctionnel)<br /><b>rcs.datashed.net/RPC2</b> (pour euro.weblogs.com)<br /><b>ping.blo.gs</b> (pour blo.gs)<br />',

	'setup_tb' => 'Trackback Setup',
	'tb_email' => 'Email',
	'tb_email_desc' => 'If set, an email will be sent to this address when a Tracback is added.',

	'new_window' => 'Liens dans une nouvelle fen&ecirc;tre',
	'emoticons' => 'Utiliser les &eacute;moticones',
	'javascript_email' => 'Coder les adresses e-mail',
	'new_window_desc' => 'D&eacute;termine si les liens contenu dans les entr&eacute;es seront ouverts dans une nouvelle fen&ecirc;tre de navigateur.',

	'mod_rewrite' => 'Utiliser Filesmatch',
	'mod_rewrite_desc' => 'Si vous utilisez l&#8217;option Filesmatch d&#8217;Apache, Pivot cr&eacute;era des URL comme www.mysite.com/archive/2003/05/30/nice_weather, au lieu de www.mysite.com/pivot/entry.php?id=134. Tous les serveurs ne le permettent pas, veuillez consulter la section correspondante du manuel.',
	'mod_rewrite_1' => 'Yes, like /archive/2005/04/28/title_of_entry',
	'mod_rewrite_2' => 'Yes, like /archive/2005-04-28/title_of_entry',
	'mod_rewrite_3' => 'Yes, like /entry/1234',
	'mod_rewrite_4' => 'Yes, like /entry/1234/title_of_entry',

	'search_index' => 'Mise &agrave; jour de l&#8217;index',
	'search_index_desc' => 'D&eacute;termine si l&#8217;index de recherche est mis &agrave; jour &agrave; chaque fois que vous cr&eacute;ez ou que vous modifiez une entr&eacute;e.',

	'default_allow_comments' => 'Allow comments by default',
	'default_allow_comments_desc' => 'Determine whether entries are set to allow comments or not.',	

  'maxhrefs' => 'Number of links',
  'maxhrefs_desc' => 'Maximum number of hyperlinks in allowed in comments. Useful to get rid of those pesky comment spammers. Set to 0 for unlimited links.',
  'rebuild_threshold' => 'Rebuild Threshold',
  'rebuild_threshold_desc' => 'The number of seconds rebuilding takes, before Pivot refreshes the page. The default is 28, but if you are having problems with rebuilding, try lowering this number to 10.',
	'default_introduction' => 'Introduction/Corps par d&eacute;faut',
	'default_introduction_desc' => 'D&eacute;termine les valeurs par d&eacute;faut pour l&#8217;Introduction et le Corps lorsqu&#8217;un auteur &eacute;crit une nouvelle entr&eacute;e. Normalement, ce sera un paragraphe vide, ce qui est le plus logique du point de vue s&eacute;mantique.',

	'upload_autothumb'	=> 'Vignettes automatiques',
	'upload_thumb_width' => 'Largeur de vignette',
	'upload_thumb_height' => 'Hauteur de vignette',
	'upload_thumb_remote' => 'Script de redimensionnement',
	'upload_thumb_remote_desc' => 'Si votre serveur n&#8217;a pas les biblioth&egrave;ques n&eacute;c&eacute;ssaires pour redimensionner les images, vous pouvez utiliser un script de redimensionnement distant.',

	'extensions_header' => 'Extensions directory',
	'extensions_desc'   => 'The \'extensions\' directory is the place to store your additions to Pivot.
		This makes updating a lot easier. See the Docs for more info.',
	'extensions_path'   => 'Extensions directory path',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'Editer le weblog ',
	'edit_blog' => 'Editer les weblogs',
	'new_weblog' => 'Nouveau weblog',
	'new_weblog_desc' => 'Ajouter une nouveau weblog',
	'del_weblog' => 'Supprimer le weblog',
	'del_this_weblog' => 'Supprimer ce weblog.',
	'create_new' => 'Cr&eacute;er un nouveau weblog',
	'subw_heading' => 'Pour chacun des sous-weblogs trouv&eacute;s dans les gabarits, vous pouvez configurer quel gabarit sera utilis&eacute; et quelles cat&eacute;gories y seront publi&eacute;es',
	'create' => 'Terminer',

	'create_1' => 'Cr&eacute;er / &Eacute;diter weblog, &eacute;tape 1 de 3',
	'create_2' => 'Cr&eacute;er / &Eacute;diter weblog, &eacute;tape 2 de 3',
	'create_3' => 'Cr&eacute;er / &Eacute;diter weblog, &eacute;tape 3 de 3',

	'name' => 'Nom du weblog',
	'payoff' => 'Exergue',
	'payoff_desc' => 'L&#8217;exergue peut &ecirc;tre utilis&eacute;e comme sous-titre ou comme courte description de votre site',
	'url' => 'URL du Weblog',
	'url_desc' => 'Pivot d&eacute;terminera automatiquement l&#8217;URL de votre weblog si vous laissez ce champ vide. Si vous utilisez votre weblog dans des frames ou comme Server Side Include, vous pouvez l&#8217;utiliser pour modifier cette valeur.',
	'index_name' => 'Page d&#8217;accueil (Index)',
   	'index_name_desc' => 'Le nom de fichier du fichier index. Normalement c&#8217;est quelque chose comme &#8216;index.html&#8217; ou &#8216;index.php&#8217;.',

	'ssi_prefix' => 'Pr&eacute;fixe SSI',
	'ssi_prefix_desc' => 'Si votre weblog utilise des SSI (ce qui n&#8217;est pas recommand&eacute;), vous pouvez utiliser ce champ pour pr&eacute;fixer les noms de fichier de Pivot par le nom de fichier utilis&eacute; pour les SSI. Par exemple : &#8216;index.shtml?p=&#8217;. Vous devriez laisser ce champ vide &agrave; moins de savoir ce que vous faites.',

	'front_path' => 'Chemin de la page d&#8217;accueil',
	'front_path_desc' => 'Le chemin relatif ou absolu du r&eacute;pertoire o&ugrave; Pivot cr&eacute;era la page d&#8217;accueil du weblog.',
	'file_format' => 'Nom de fichier',
	'entry_heading' => 'Param&egrave;tre des entr&eacute;es',
	'entry_path' => 'Chemin des entr&eacute;es',
	'entry_path_desc' => 'Le chemin relatif ou absolu du r&eacute;pertoire o&ugrave; Pivot cr&eacute;era les pages a entr&eacute;e unique (si vous choisissez de ne pas utiliser des &#8216;entr&eacute;es dynamiques&#8217;)',
	'live_comments' => 'Entr&eacute;es dynamiques',
	'live_comments_desc' => 'Si vous utilisez des &#8216;entr&eacute;es dynamiques&#8217;, Pivot n&#8217;aura pas &agrave; g&eacute;n&eacute;rer un fichier pour chaque entr&eacute;e. C&#8217;est le param&eacute;trage pr&eacute;f&eacute;rable.',
	'readmore' => 'Message &#8216;Lire la suite&#8217;',
	'readmore_desc' => 'Le message utilis&eacute; pour indiquer qu&#8217;il y a plus de texte &agrave; lire dans cette entr&eacute;e que ce qui est affich&eacute; sur la page d&#8217;accueil. Si vous laissez ce champ vide, Pivot utilisera le message par d&eacute;faut du fichier de langue',
	
	'arc_heading' => 'Param&egrave;tres des archives',
	'arc_index' => 'Fichier d&#8217;index',
	'arc_path' => 'Chemin des archives',
	'archive_amount' => 'Quantit&eacute; d&#8217;archives',
	'archive_unit' => 'Type d&#8217;archives',
	'archive_format' => 'Format des archives',
	'archive_none' => 'Pas d&#8217;archives',
	'archive_weekly' => 'Archives hebdomadaires',
	'archive_monthly' => 'Archives mensuelles',
	'archive_yearly' => 'Yearly Archives',

	'archive_link' => 'Lien des archives',
	'archive_linkfile' => 'Format de liste des archives',
	'archive_order' => 'Tri des archives',
	'archive_ascending' => 'Ascendant (plus anciennes d&#8217;abord)',
	'archive_descending' => 'Descendant (plus r&eacute;centes d&#8217;abord)',

	'templates_heading' => 'Gabarits',
	'frontpage_template' => 'Gabarit page d&#8217;accueil',
	'frontpage_template_desc' => 'Le gabarit qui d&eacute;termine la pr&eacute;sentation de la page d&#8217;index de ce weblog.',
	'archivepage_template' => 'Gabarit page archives',
	'archivepage_template_desc' => 'Le gabarit qui d&eacute;termine la pr&eacute;sentation de vos archives. Cela peut &ecirc;tre le m&ecirc;me que &#8216;Gabarit page d&#8217;accueil&#8217;.',
	'entrypage_template' => 'Gabarit page entr&eacute;e',
	'entrypage_template_desc' => 'Le gabarit qui d&eacute;termine la pr&eacute;sentation des entr&eacute;es individuelles.',
	'extrapage_template' => 'Gabarit suppl&eacute;mentaire',
	'extrapage_template_desc' => 'Le gabarit qui d&eacute;termine la pr&eacute;sentation de vos pages archives et search.php.',

	'shortentry_template' => 'Gabarit entr&eacute;e courte',
	'shortentry_template_desc' => 'Le gabarit qui d&eacute;termine la pr&eacute;sentation des entr&eacute;es individuelles lorsqu&#8217;elles sont affich&eacute;es dans le weblog ou les archives.',
	'num_entries' => 'Nombre d&#8217;entr&eacute;es',
	'num_entries_desc' => 'Le nombre d&#8217;entr&eacute;es dans ce sous-weblog qui seront affich&eacute;es sur la page d&#8217;accueil.',
	'offset' => 'D&eacute;calage',
	'offset_desc' => 'Si un nombre est entr&eacute; pour d&eacute;calage, le nombre d&#8217;entr&eacute;es correspondant sera saut&eacute; &agrave; la g&eacute;n&eacute;ration de page. Vous pouvez utiliser cela pour cr&eacute;er une liste &#8216;Entr&eacute;es pr&eacute;c&eacute;dentes&#8217; par exemple.',
	'comments' => 'Commentaires autoris&eacute;s',
	'comments_desc' => 'D&eacute;termine si les utilisateurs pourron laisser des commentaires dans les entr&eacute;es de ce sous-weblog.',

	'publish_cats' => 'Publish these categories',

	'setup_rss_head' => 'Configuration RSS et Atom',
	'rss_use' => 'G&eacute;nerer des fils',
	'rss_use_desc' => 'D&eacute;termine si Pivot doit g&eacute;n&eacute;rer automatiquement des fils RSS et Atom pour ce weblog.',
	'rss_filename' => 'Fichier RSS',
	'atom_filename' => 'Fichier Atom',
	'rss_path' => 'Chemin des fils',
	'rss_path_desc' => 'Le chemin relatif ou absolu du r&eacute;pertoire ou Pivot va cr&eacute;er les fichiers de fils.',
//	'rss_size' => 'Longeur des entr&eacute;es',
//	'rss_size_desc' => 'La longueur (en caract&egrave;res) d&#8217;une entr&eacute;e dans les fichiers de fils',
	'rss_full' => 'Create Full Feeds',
	'rss_full_desc' => 'Determines whether Pivot creates full Atom and RSS feeds. If set to &#8216;No&#8217; Pivot will create feeds that just contains short descriptions, thereby making your feeds less useful.',
	'rss_link' => 'Feed Link',
	'rss_link_desc' => 'The link to send with the Feed, to point to the main page. If you leave this blank, Pivot will send the weblog\'s index as link.',
	'rss_img' => 'Feed Image', 
	'rss_img_desc' => 'You can specify an image to send with the Feed. Some feed readers will display this image along with your feed. Leave this blank, or specify a full URL.',
	
	'lastcomm_head' => 'Param&egrave;tres des derniers commentaires',
	'lastcomm_amount' => 'Nombre &agrave; afficher',
	'lastcomm_length' => 'Longueur &agrave; laquelle couper',
	'lastcomm_format' => 'Format',
	'lastcomm_format_desc' => 'Ces param&egrave;tres d&eacute;terminent l&#8217;apparence des &#8216;derniers commentaires&#8217; sur la page d&#8217;accueil du weblog.',
	'lastcomm_redirect' => 'Redirection commentaires',
	'lastcomm_redirect_desc' => 'Pour combattre le &#8216;refererspam&#8217; vous pouvez choisir de rediriger les liens vers l&#8217;ext&eacute;rieur dans les commentaires, cela permet de ne pas faire monter le pagerank Google des spammeurs.',

	'lastref_head' => 'Param&egrave;tres des derniers r&eacute;f&eacute;rents',
	'lastref_amount' => 'Nombre &agrave; afficher',
	'lastref_length' => 'Longueur &agrave; laquelle couper',
	'lastref_format' => 'Format',
	'lastref_format_desc' => 'Ces param&egrave;tres d&eacute;terminent l&#8217;apparence des &#8216;derniers r&eacute;f&eacute;rents&#8217; sur la page d&#8217;accueil du weblog.',
	'lastref_graphic' => 'Utiliser des images',
	'lastref_graphic_desc' => 'D&eacute;termine si Pivot utilise des petites icones repr&eacute;sentant les moteurs de recherche par lesquels les visiteurs arrivent.',
	'lastref_redirect' => 'Redirection r&eacute;f&eacute;rents',
	'lastref_redirect_desc' => 'Pour combattre le &#8216;refererspam&#8217; vous pouvez choisir de rediriger les liens vers l&#8217;ext&eacute;rieur des r&eacute;f&eacute;rents, cela permet de ne pas faire monter le pagerank Google des spammeurs.',

	'various_head' => 'Autres param&egrave;tres',
	'emoticons' => 'Utiliser des &eacute;moticones',
	'emoticons_desc' => 'D&eacute;termine si les &eacute;moticones comme :-) seront transform&eacute;es en &eacute;quivalent graphique.',
	'encode_email_addresses' => 'Coder les adresses e-mail',
	'encode_email_addresses_desc' => 'D&eacute;termine si les adresses e-mail seront cod&eacute;es en javascript pour se prot&eacute;ger de spammeurs.',
	'target_blank' => 'Target Blank',
	'xhtml_workaround' => 'Compatible XHTML',
	'target_blank_desc' => 'Si ce param&egrave;tre est &agrave; &#8216;Oui&#8217;, tous les liens externes seront ouvert dans une nouvelle fen&ecirc;tre de navigateur. Si il est &agrave; &#8216;Compatible XHTML&#8217;, tous les liens externes auront un attribut rel="external", ce qui n&#8217;invalide pas le XHTML bien form&eacute;.',

	'date_head' => 'Affichage de la date',
	'full_date' => 'Format de date complet',
	'full_date_desc' => 'D&eacute;termine le format de date et heure complet. Ce format est le plus souvent utilis&eacute; en haut de page d&#8217;entr&eacute;e unique',
	'entry_date' => 'Date d&#8217;entr&eacute;e',
	'diff_date' => 'Date diff&eacute;rente',
	'diff_date_desc' => 'La &#8216;Date diff&eacute;rente&#8217; est le plus souvent utilis&eacute;e conjointement &agrave; la &#8216;Date d&#8217;entr&eacute;e&#8217;. La date d&#8217;entr&eacute;e est affich&eacute;e pour chaque entr&eacute;e, alors que la date diff&eacute;rente est seulement affich&eacute;e si la date diff&egrave;re de celle de l&#8217;entr&eacute;e pr&eacute;c&eacute;dente.',
	'language' => 'Langue',
	'language_desc' => 'D&eacute;termine la langue dans laquelle les dates et nombres seront affich&eacute;s, ainsi que l&#8217;encodage des caract&egrave;res (par exemple iso-8859-1 ou koi8-r).',

	'comment_head' => 'Param&egrave;tres des commentaires',
	'comment_sendmail' => 'Envoyer un e-mail',
	'comment_sendmail_desc' => 'Envoi d&#8217;un e-mail &agrave; l&#8217;auteur du weblog apr&egrave;s ajout d&#8217;un commentaire par un visiteur.',
	'comment_emailto' => 'Adresse e-mail',
	'comment_emailto_desc' => 'Adresse(s) &agrave; laquelle sera envoy&eacute; l&#8217;e-mail. S&eacute;parez des adresses multiples par des virgules.',
	'comment_texttolinks' => 'Conversion des liens',
	'comment_texttolinks_desc' => 'D&eacute;termine si les URL et adresses e-mail seront converties en liens cliquables.',
	'comment_wrap' => 'Retour &agrave; la ligne apr&egrave;s',
	'comment_wrap_desc' => 'Pour emp&ecirc;cher les longues cha¥nes de caract&egrave;re de nuire &agrave; la mise en page, un retour &agrave; la ligne sera ins&eacute;r&eacute; automatiquement apr&egrave;s le nombre de caract&egrave;res sp&eacute;cifi&eacute;.',
	'comments_text_0' => 'Message &#8216;pas de commentaires&#8217;',
	'comments_text_1' => 'Message &#8216;un commentaire&#8217;',
	'comments_text_2' => 'Message &#8216;X commentaires&#8217;',
	'comments_text_2_desc' => 'Le message affich&eacute; pour indiquer le nombre de commentaires. Si vous laissez ces champs vides, Pivot utilisera les valeurs par d&eacute;faut de votre param&egrave;tres de langue.',

	'comment_pop' => 'Popup pour commentaires',
	'comment_pop_desc' => 'D&eacute;termine si la page de commentaires (ou &#8216;entr&eacute;e unique&#8217;) sera affich&eacute;e dans une fen&ecirc;tre popup ou dans la fen&ecirc;tre originelle du navigateur.',
	'comment_width' => 'Largeur de fen&ecirc;tre',
	'comment_height' => 'Hauteur de fen&ecirc;tre',
	'comment_height_desc' => 'D&eacute;termine la largeur et la hauteur (en pixels) de la fen&ecirc;tre popup.',

	'comment_format' => 'Format des commentaires',
	'comment_format_desc' => 'D&eacute;termine le formatage des commentaires sur les pages d&#8217;entr&eacute;es.',

	'comment_reply' => "Format of 'reply ..'",
	'comment_reply_desc' => "This determines the formatting of the link that visitors can use to reply on a specific comment.",
	'comment_forward' => "Format of 'reply by ..'",
	'comment_forward_desc' => "This determines the formatting of the text that is displayed when the comment is replied by another comment.",
	'comment_backward' => "Format of 'reply on ..'",
	'comment_backward_desc' => "This determines the formatting of the text that is displayed when the comment is a reply on another comment.",
	
	'comment_textile' => 'Autoriser Textile',
	'comment_textile_desc' => 'D&eacute;termine si les visiteurs peuvent utiliser <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> dans leurs commentaires.',
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

	'saved_create' => 'Le nouveau weblog a &eacute;t&eacute; cr&eacute;&eacute;.',
	'saved_update' => 'Le weblog a &eacute;t&eacute; mis &agrave; jour.',
	'deleted' => 'Le weblog a &eacute;t&eacute; supprim&eacute;.',
	'confirm_delete' => 'Vous allez supprimer le weblog %1. Etes vous s&ucirc;r ?',

	'blogroll_heading' => 'Param&egrave;tres de blogroll',
	'blogroll_id' => 'Blogrolling ID #',
	'blogroll_id_desc' => 'Vous pouvez inclure de mani&egrave;re optionnelle un blogroll <a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a> dans votre weblog. Blogrolling est un service excellent pour maintenir une liste de liens qui montre quand ils ont &eacute;t&eacute; mis &agrave; jour. Si cela ne vous int&eacute;resse pas, sautez simplement cette &eacute;tape. Dans le cas contraire, en &eacute;tant connect&eacute; &agrave; blogrolling.com, allez dans &#8216;get code&#8217;, vous y trouverez des liens contenant votre num&eacute;ro d&#8217;identifiant (blogroll ID #), qui devrait ressembler &agrave; 2ef8b42161020d87223d42ae18191f6d',
	'blogroll_fg' => 'Couleur de texte',
	'blogroll_bg' => 'Couleur de fond',
	'blogroll_line1' => 'Couleur de ligne 1',
	'blogroll_line2' => 'Couleur de ligne 2',
	'blogroll_c1' => 'Couleur 1',
	'blogroll_c2' => 'Couleur 2',
	'blogroll_c3' => 'Couleur 3',
	'blogroll_c4' => 'Couleur 4',
	'blogroll_c4_desc' => 'Ces couleurs d&eacute;terminent l&#8217;apparence de votre blogroll. Les couleurs 1 &agrave; 4 donnent une indication visuelle du temps depuis la derni&egrave;re mise &agrave; jour du lien.',
);


$lang['upload'] = array (
	//		File Upload		\\
	'preview' => 'Affichage liste',
	'thumbs' => 'Affichage vignettes',
	'create_thumb' => '(Cr&eacute;er les vignettes)',
	'title' => 'Fichiers',
	'thisfile' => 'Upload d&#8217;un nouveau fichier :',
	'button' => 'Upload',
	'filename' => 'Fichier',
	'thumbnail' => 'Vignette',
	'date' => 'Date',
	'filesize' => 'Taille',
	'dimensions' => 'Dimensions',
	'delete_title' => 'Supprimer l&#8217;image',
	'areyousure' => 'Voulez-vous vraiment supprimer le fichier %s ?',
	'picheader' => 'Supprimer cette image ?',
	'create' => 'Cr&eacute;er',
	'edit' => 'Editer',

	'insert_image' => 'Ins&eacute;rer une image',
	'insert_image_desc' => 'Pour ins&eacute;rer une image, vous devez en ajouter une par upload ou en s&eacute;lectionner une d&eacute;j&agrave; sur le serveur.',
	'insert_image_popup' => 'Ins&eacute;rer un popup d&#8217;image',
	'insert_image_popup_desc' => 'Pour cr&eacute;er un popup d&#8217;image, vous devez ajouter une image par upload ou en s&eacute;lectionner une d&eacute;j&agrave; sur le serveur. S&eacute;lectionnez ensuite un message ou une vignette qui d&eacute;clenchera le popup.',
	'choose_upload' => 'upload',
	'choose_select' => 'ou s&eacute;lection',
	'imagename' => 'Nom de l&#8217;image',
	'alt_text' => 'Texte alternatif',
	'align' => 'Aligner',
	'border' => 'Bordure',
	'pixels' => 'pixels',
	'uploaded_as' => 'Votre fichier a &eacute;t&eacute; enregistr&eacute; comme &#8216;%s&#8217;.',
	'not_uploaded' => 'Votre fichier n&#8217;a pas &eacute;t&eacute; enregistr&eacute;, l&#8217;erreur suivante s&#8217;est produite :',
	'center' => 'Centrer (d&eacute;faut)',
	'left' => 'Gauche',
	'right' => 'Droite',
	'inline' => 'Ins&eacute;r&eacute;e',
	'notice_upload_first' => 'Vous devez d&#8217;abord s&eacute;lectionner ou uploader une image',
	'select_image' => 'S&eacute;lectionner l&#8217;image',
	'select_file' => 'Select File',

	'for_popup' => 'Pour le popup',
	'use_thumbnail' => 'Utiliser la vignette',
	'edit_thumbnail' => '&Eacute;dition de la vignette',
	'use_text' => 'Utiliser le message',
	'insert_download' => 'Insert a Download',
	'insert_download_desc' => 'To make a file download, you should upload a file, or select a previously uploaded file. Then select whether you want an icon or a text or a thumbnail that triggers the download.',
	'use_icon' => 'Use icon',
);


$lang['link'] = array (
	//		Link Insertion \\
	'insert_link' => 'Ins&eacute;rer un lien',
	'insert_link_desc' => 'Ins&eacute;rer un lien en entrant une URL dans le champ ci-dessous. Les visiteurs de votre site verront le titre lorsque leur curseur souris passera au dessus du lien.',
	'url' => 'URL',
	'title' => 'Titre',
	'text' => 'Texte',
);


//		Categories		\\
$lang['category'] = array (
	'edit_who' => '&Eacute;diter qui peut publier dans la cat&eacute;gorie &#8216;%s&#8217;',
	'name' => 'Nom',
	'users' => 'Utilisateurs',
	'make_new' => 'Cr&eacute;er une nouvelle cat&eacute;gorie',
	'create' => 'Cr&eacute;er la cat&eacute;gorie',
	'canpost' => 'S&eacute;lectionnez les utilisateurs auxquels vous voulez donner l&#8217;autorisation de publier dans cette cat&eacute;gorie.',
	'same_name' => 'Une cat&eacute;gorie ayant le m&ecirc;me nom existe d&eacute;j&agrave;.',
	'need_name' => 'Vous devez entrer un nom pour la cat&eacute;gorie.',
	
	'allowed' => 'Autoris&eacute;',
	'allow' => 'Autoriser',
	'denied' => 'Interdit',
	'deny' => 'Interdire',
	'edit' => '&Eacute;diter la cat&eacute;gorie',

	'delete' => 'Supprimer la cat&eacute;gorie',
	'delete_desc' => 'S&eacute;lectionnez &#8216;Oui&#8217;, si vous souhaitez supprimer cette cat&eacute;gorie',

	'delete_message' => 'Dans cette version de Pivot, seul le nom de la cat&eacute;gorie sera supprim&eacute;. Dans une version future, vous pourrez choisir quoi faire des entr&eacute;es de cette cat&eacute;gorie.',
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
	'post_entry' => 'Publier',
	'preview_entry' => 'Aper&ccedil;u',

	'first' => 'premier',
	'last' => 'dernier',
	'next' => 'suivant',
	'previous' => 'pr&eacute;c&eacute;dent',

	'jumptopage' => 'aller &agrave; la page (%num%)',
	'filteron' => 'filtrer par (%name%)',
	'filteroff' => 'filtre d&eacute;sactiv&eacute;',
	'title' => 'Titre',
	'subtitle' => 'Sous-titre',
	'introduction' => 'Introduction',
	'body' => 'Corps',
	'publish_on' => 'Publi&eacute; le',
	'status' => 'Statut',
	'post_status' => 'Statut de publication',
	'category' => 'Cat&eacute;gorie',
	'select_multi_cats' => '(Ctrl-clic pour s&eacute;lectionner plusieurs cat&eacute;gories)',
	'last_edited' => 'Derni&egrave;re modification',
	'created_on' => 'Cr&eacute;&eacute; le',
	'date' => 'Date',
	'author' => 'Auteur',
	'code' => 'Code',
	'comm' => 'Nb Comm.',
	'name' => 'Nom',
	'allow_comments' => 'Autoriser les commentaires',
	'always_off' => '(Toujours desactiv&eacute; en mode Wysiwyg)',
	'delete_entry' => "Delete Entry",
	'delete_entry_desc' => "Delete this Entry and the corresponding Comments ",
	'delete_one_confirm' => "Are you sure you want to delete this entry?",
	'delete_multiple_confirm' => "Are you sure you want to delete these entries?",
	
	'convert_lb' => 'Convert Linebreaks',
	'always_off' => '(This is always off, when in Wysiwyg mode)',
	'be_careful' => '(Be careful with this!)',
	'edit_comments' => '&Eacute;diter les commentaires',
	'edit_comments_desc' => '&Eacute;diter les commentaires publi&eacute;s pour cette entr&eacute;e',
	'edit_comment' => '&Eacute;diter le commentaire',
	'delete_comment' => 'Supprimer le commentaire',
	'block_single' => 'Bloquer l&#8217;IP %s',
	'block_range' => 'Bloquer la plage d&#8217;IP %s',
	'unblock_single' => 'D&eacute;bloquer l&#8217;IP %s',
	'unblock_range' => 'D&eacute;bloquer la plage d&#8217;IP %s',
	'trackback' => 'Ping de trackback',
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
	'c_all' => 'Tout cocher',
	'c_none' => 'Tout d&eacute;cocher',
	'choose' => '- choisir une option -',
	'publish' => 'Mettre le statut &agrave; &#8216;publi&eacute;&#8217;',
	'hold' => 'Mettre le statut &agrave; &#8216;attente&#8217;',
	'delete' => 'Supprimer',
	'generate' => 'Publier et g&eacute;n&eacute;rer',

	'with_checked_entries' => 'Pour les entr&eacute;es s&eacute;lectionn&eacute;es :',
	'with_checked_files' => 'Pour les fichiers s&eacute;lectionn&eacute;s :',
	'with_checked_templates' => 'Pour les gabarits s&eacute;lectionn&eacute;s :',
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'impossible d&#8217;ouvrir le r&eacute;pertoire, v&eacute;rifiez les droits.',
	'path_read' => 'impossible de lire le r&eacute;pertoire, v&eacute;rifiez les droits.',
	'path_write' => 'impossible d&#8217;&eacute;crire dans le r&eacute;pertoire, v&eacute;rifiez les droits.',

	'file_open' => 'impossible d&#8217;ouvrir le fichier, v&eacute;rifiez les droits.',
	'file_read' => 'impossible de lire le fichier, v&eacute;rifiez les droits.',
	'file_write' => 'impossible d&#8217;&eacute;crire dans le fichier, v&eacute;rifiez les droits.',
);


//		Notices			\\
$lang['notice'] = array (		
	'comment_saved' => 'Le commentaire a &eacute;t&eacute; enregistr&eacute;.',
	'comment_deleted' => 'Le commentaire a &eacute;t&eacute; supprim&eacute;.',
	'comment_none' => 'Cette entr&eacute;e n&#8217;a pas de commentaires.',
	'trackback_saved' => "The Trackback has been saved.",
	'trackback_deleted' => "The Trackback has been deleted.",
	'trackback_none' => "This entry has no trackbacks.",
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'Voter &#8216;%val%&#8217; pour cette entr&eacute;e',
	'good' => 'Bon',
	'bad' => 'Mauvais',
	'already' => 'Vous avez d&eacute;j&agrave; vot&eacute; pour cette entr&eacute;e ou sondage',
	'register' => 'Votre vote pour &#8216;%val%&#8217; a bien &eacute;t&eacute; enregistr&eacute;',
);


$lang['comment'] = array (
	'register' => 'Votre commentaire a &eacute;t&eacute; enregistr&eacute;.',
	'preview' => 'Vous &ecirc;tes en mode aper&ccedil;u du commentaire. N&#8217;oubliez pas de cliquer sur &#8216;Publier&#8217; pour l&#8217;enregistrer.',
	'duplicate' => 'Votre commentaire n&#8217;a pas &eacute;t&eacute; enregistr&eacute; car il semble &ecirc;tre une copie d&#8217;une entr&eacute;e pr&eacute;c&eacute;dente.',
	'no_name' => 'Vous devez entrer votre nom (ou un pseudo) dans le champ &#8216;nom&#8217;. N&#8217;oubliez pas de cliquer sur &#8216;Publier&#8217; pour l&#8217;enregistrer d&eacute;finitivement.',
	'no_comment' => 'Vous devez entrer quelque chose dans le champ &#8216;commentaire&#8217;. N&#8217;oubliez pas de cliquer sur &#8216;Publier&#8217; pour l&#8217;enregistrer d&eacute;finitivement.',
	'too_many_hrefs' => 'The maximum number of hyperlinks was exceeded. Stop spamming.',
    'email_subject' => '[Comment] Re:',	
);


$lang['comments_text'] = array (
	'0' => 'Pas de commentaires',
	'1' => '%num% commentaire',
	'2' => '%num% commentaires',
);

$lang['trackbacks_text'] = array (
	'0' => "No trackback",
	'1' => "%num% trackback",
	'2' => "%num% trackback",
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => 'Archives',
	'next_archive' => 'Archive suivante',
	'previous_archive' => 'Archive pr&eacute;c&eacute;dente',
	'last_comments' => 'Derniers commentaires',
	'last_referrers' => 'Derniers r&eacute;f&eacute;rents',
	'calendar' => 'Calendrier',
	'links' => 'Liens',
	'xml_feed' => 'Fil XML (RSS 1.0)',
	'atom_feed' => "XML: Atom Feed",
	'powered_by' => 'Motoris&eacute; par',
	'blog_name' => "Weblog Name",
	'title' => "Title",
	'excerpt' => "Excerpt",
	'name' => 'Nom',
	'email' => 'E-mail',
	'url' => 'URL',
	'date' => 'Date',
	'comment' => 'Commentaire',
	'ip' => 'Adresse IP',
	'yes' => 'Oui',
	'no' => 'Non',
	'emoticons' => 'Emoticones',
	'emoticons_reference' => 'Voir la r&eacute;f&eacute;rence des &eacute;moticones',
	'textile' => 'Textile',
	'textile_reference' => 'Voir la r&eacute;f&eacute;rence de Textile',
	'post_comment' => 'Publier',
	'preview_comment' => 'Aper&ccedil;u',
	'remember_info' => 'Retenir les informations personnelles ?',
	'disclaimer' => '<b>Pr&eacute;cision :</b> Toutes les balises HTML sauf &lt;b&gt; et &lt;i&gt; seront supprim&eacute;es de votre commentaire. Vous pouvez cr&eacute;er des liens juste en entrant l&#8217;URL ou l&#8217;adresse e-mail.',
	'notify_yes' => "Yes, send me email when someone replies.",
	'register' => "Register your username / Log in",
	'disclaimer' => "<b>Small print:</b> All html tags except &lt;b&gt; and &lt;i&gt; will be removed from your comment. You can make links by just typing the url or mail-address.",	
	'search_title' => 'R&eacute;sultat de la recherche',
	'search' => 'Rechercher',
	'nomatches' => 'Aucun r&eacute;sultat trouv&eacute; pour &#8216;%name%&#8217;. Essayez autre chose.',
	'matches' => 'R&eacute;sultats pour &#8216;%name%&#8217; :',
	'about' => "About",
	'stuff' => "Stuff",
	'linkdump' => "Linkdump",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
	'title' => '&Eacute;diter les champs utilisateur',
	'edit' => '&Eacute;diter',
	'create' => 'Cr&eacute;er',

	'dispname' => 'Nom affich&eacute;',
	'intname' => 'Nom interne',
	'intname_desc' => 'Le nom interne est le nom de cet &eacute;l&eacute;ment tel qu&#8217;il appara¥tra dans un gabarit.',
	'size' => 'Taille',
	'rows' => 'Lignes',
	'cols' => 'Colonnes',
	'maxlen' => 'Longeur max.',
	'minlevel' => 'Niveau util. min.',
	'filter' => 'Filtrer par',
	'filter_desc' => 'En filtrant cet &eacute;l&eacute;ment, vous limitez le type d&#8217;information qui peut y &ecirc;tre entr&eacute;.',
	'no_filter' => 'Rien',
	'del_title' => 'Confirmer la suppression',
	'del_desc' => 'Supprimer ce champ utilisateur (<b>%s</b>) d&eacute;truira &eacute;galement toutes les donn&eacute;es que les utilisateurs on stock&eacute; dedans et rendre vide toutes ses instances dans des gabarits.',

	'already' => 'Ce nom est d&eacute;j&agrave; utilis&eacute;',
	'int' => 'Le nom interne doit faire plus de 3 caract&egrave;res',
	'short_disp' => 'Le nom affich&eacute; doit faire plus de 3 caract&egrave;res',
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'Favoris',
	'bm_add' => 'Ajouter un favori.',
	'bm_withlink' => 'Piv &raquo; Nouveau',
	'bm_withlink_desc' => 'Ce favori ouvre une fen&ecirc;tre avec une nouvelle entr&eacute;e, contenant un lien vers la page dans laquelle il a &eacute;t&eacute; ouvert.',

	'bm_nolink' => 'Piv &raquo; Nouveau',
	'bm_nolink_desc' => 'Ce favori ouvre une fen&ecirc;tre avec une nouvelle entr&eacute;e vide.',

	'bookmarklets_info' => 'Vous pouvez utiliser les favoris pour &eacute;crire rapidement des nouvelles entr&eacute;es avec Pivot. Pour ajouter un favori &agrave; votre navigateur, utilisez une des m&eacute;thodes suivantes : (le texte varie en fonction du navigateur)',
	'bookmarklets_info_1' => 'Cliquez et faites glisser le favori dans votre barre de liens ou sur bouton &#8216;Favoris&#8217; de votre navigateur.',
	'bookmarklets_info_2' => 'Faites un clic droit sur le favori et s&eacute;lectionnez &#8216;Ajouter au Favoris&#8217;.',
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
    'email_subject' => '[Trackback] Re:',
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
