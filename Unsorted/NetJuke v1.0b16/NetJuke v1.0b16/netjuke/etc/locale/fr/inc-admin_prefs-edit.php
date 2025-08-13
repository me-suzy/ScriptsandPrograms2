<?php

##################################################

# /admin/prefs-edit.php

##################################################

define( "ADMPREF_ERR_DBCONN", "Impossible de connecter à la base de donnée avec l\\'information fournie." );

define( "ADMPREF_ERR_RADIOPLIST", "Le répertoire ou fichier utilisé pour l'option RADIO ne peut être trouvé ou n\'est\\npas accessible pour \\\"écriture\\\". Veuillez changer les permissions." );

define( "ADMPREF_ERR_JUKEBOXPLIST", "Le répertoire ou fichier utilisé pour l'option JUKEBOX ne peut être trouvé ou n\'est\\npas accessible pour \\\"écriture\\\". Veuillez changer les permissions." );
define( "ADMPREF_ERR_JUKEBOXPLAYERPATH", "Le lecteur audio pour le jukebox ne peut être trouvé.\\nVeuillez réviser le champ Lecteur Audio." );

define( "ADMPREF_FILEINFO_1", "Créé" );
define( "ADMPREF_FILEINFO_2", "Depuis" );
define( "ADMPREF_FILEINFO_3", "Sauvegarder à" );

define( "ADMPREF_DENIED_1", "Erreur de permissions avec votre fichier de préférences." );

define( "ADMPREF_CHECKFORM_SECKEY", "Votre nouvelle Clée de Sécurité doit être au moins de 30 caractères pour être mise-à-jour." );
define( "ADMPREF_CHECKFORM_DBNAME", "Veuillez indiquer le nom de votre base de données." );
define( "ADMPREF_CHECKFORM_STREAM", "Veuillez indiquer un serveur de débit de musique (Streaming Server)." );
define( "ADMPREF_CHECKFORM_BGCOLOR", "Veuillez choisir une couleur de fond (bgcolor)." );
define( "ADMPREF_CHECKFORM_FONTFACE", "Veuillez soumettre une liste de typographies. (font-family)" );
define( "ADMPREF_CHECKFORM_FONTSIZE", "Veuillez soumettre une taille de typographies (font-size)." );
define( "ADMPREF_CHECKFORM_TEXT", "Veuillez choisir une couleur pour le texte (text)." );
define( "ADMPREF_CHECKFORM_LINK", "Veuillez choisir une couleur pour les liens (link)." );
define( "ADMPREF_CHECKFORM_ALINK", "Veuillez choisir une couleur pour les liens actifs (alink)." );
define( "ADMPREF_CHECKFORM_VLINK", "Veuillez choisir une couleur pour les liens visités (vlink)." );
define( "ADMPREF_CHECKFORM_BORDER", "Veuillez choisir une couleur pour les bordures (border)." );
define( "ADMPREF_CHECKFORM_HEADER", "Veuillez choisir une couleur de fond pour les entêtes (header)." );
define( "ADMPREF_CHECKFORM_HEADERFC", "Veuillez choisir une couleur de texte pour les entêtes (header)." );
define( "ADMPREF_CHECKFORM_CONTENT", "Veuillez choisir une couleur de fond pour le contenu (content)." );

define( "ADMPREF_HEADER_1", "PRÉFÉRENCES DU SYSTÊME." );
define( "ADMPREF_HEADER_2", "PRÉFÉRENCES DU CONTENU" );
define( "ADMPREF_HEADER_3", "PRÉFÉRENCES DE RADIO INTERNET" );
define( "ADMPREF_HEADER_4", "PRÉFÉRENCES GLOBALE D'APPARANCE " );
define( "ADMPREF_HEADER_5", "PRÉFÉRENCES DE JUKEBOX (Lecture sur le serveur)" );

define( "ADMPREF_CAPTION", "Le formulaire ci-dessous contrôle l'apparence de base du logiciel, ainsi que l'option de personnalisation pour les adhérants. Ces valeurs sont utilisées pour la création de nouveaux comptes et pour les utilisateurs anonymes (si permis)." );
define( "ADMPREF_PALETTE", "Veuillez considérer cette palette de couleurs." );

define( "ADMPREF_FORMS_CAPT_ENABLED", "Actif" );

define( "ADMPREF_FORMS_SAVETOFILE", "Sauvegarder" );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_1", "Afin de sauvegarder ces valeurs automatiquement dans votre fichier\\nde préférences, le fichier doit être accessible (writable) par\\nle serveur http. Voici plusieurs solutions:\\n\\n- Fichier peut être accessible par tout le monde (pas recommandé).\\n\\n- Le propriétaire du fichier peut être changé pour l'utilisateur\\nassocié avec le serveur http (éxige un accés root).\\n\\nUne autre façon plus sécuritaire est de transférer manuellement l'information\\nprésenté dans le prochain écran vers votre fichier de préférence (/etc/inc-prefs.php)." );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_2", "Notes Importantes de Sécurité" );

define( "ADMPREF_FORMS_SECMODE", "Mode de Sécurité" );
define( "ADMPREF_FORMS_SECKEY", "Clée de Sécurité" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_1", "MODES DE SÉCURITÉ:\\n0.0 = Publique - Login actif - Inscriptions publiques actives \\n0.1 = Publique - Login actif - Inscriptions publiques désactivées\\n0.2 = Publique - Exige administrateur - Inscriptions publiques désactivées\\n\\n1.0 = Privé - Login actif - Inscriptions publiques actives \\n1.1 = Privé - Login actif - Inscriptions publiques désactivées\\n1.2 = Privé - Exige administrateur - Inscriptions publiques désactivées\\n" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_2", "\\nCLÉE DE SÉCURITÉ:\\nLa clée de sécuritée est utilisée pour générer une valeur sécuritaire pour les\\nsessions générées à l\\'entrée du site. Une clée de base est générée pour vous\\nlors de l\\'installation ou d\\'une mise à jour, et est regénérée chaque fois que\\nvous sauvegardez votre fichier de préférence, mais vous devriez changer\\ncette valeur de temps en temps pour plus de sécurité avec une valeur aléatoire\\nde votre chois, et de plus de 30 caractères, dans le formulaire ci-dessous.\\nCette valeurs peut être n\\'importe quoi, et vous n\\'êtes pas tenu de vous en\\nrappler (ce n\\'est pas un mot de passe)." );
define( "ADMPREF_FORMS_SECMODE_HELP_2", "Définitions des Modes et Clée de Sécurité" );

define( "ADMPREF_FORMS_DBTYPE", "Base de Données" );
define( "ADMPREF_FORMS_DBHOST", "Serveur de BdD" );
define( "ADMPREF_FORMS_DBUSER", "Usager de BdD" );
define( "ADMPREF_FORMS_DBPASS", "Mot de Passe" );
define( "ADMPREF_FORMS_DBNAME", "Nom de BdD" );

define( "ADMPREF_FORMS_STREAM", "Serveur de Musique" );
define( "ADMPREF_FORMS_MUSICDIR", "Répertoire de Musique" );

define( "ADMPREF_FORMS_PROTECTMEDIA", "Protêge Média" );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_1", "Si cette option est activée, le netjuke utilise un outil qui essaie d\\'éviter les\\ntéléchargement non-voulus en utilisant l\\'adresse montrée dans le lecteur\\nd\\'audio. Désolé, mais vous ne pouvez pas faire usage de cette option si\\nvous utilisez des fichiers Ogg Vorbis." );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_2", "Définition De Cet Option" );

define( "ADMPREF_FORMS_REALONLY", "Real Player" );
define( "ADMPREF_FORMS_REALONLY_HELP_1", "Sélectioner cette option limite la lecture des fichiers audio au logiciel Real Player.\\nCeci peut être intéressant pour limiter encore plus le téléchargement de fichiers\\nvers le disque dur car le Real Player est l\\'un des seuls logiciels de ce type qui ne\\nmontre pas l\\'addresse Web des fichiers (URL)." );
define( "ADMPREF_FORMS_REALONLY_HELP_2", "Définition De Cet Option" );

define( "ADMPREF_FORMS_RADIO_HELP_1", "1 - Choisissez le type de serveur radio approprié dans la liste disponible.\\n(Pré-requis si vous utilisez l'option \\\"Radio Playlist\\\" ci-dessous.)\\n\\n2 - Entrez la location complète d'une liste de radio que vous\\nvoulez éditer avec le netjuke.\\n\\n3 - Optionel: Entrez l'addresse du serveur Radio afin d'afficher un lien \\\"Radio\\\"\\ndans la barre de navigation principale.\\n\\nVoir les paragraphes INTERNET RADIO STREAM SERVER INTEGRATION dans docs/MAINTAIN.txt." );
define( "ADMPREF_FORMS_RADIO_HELP_2", "Aide De Configurations" );
define( "ADMPREF_FORMS_RADIOTYPE", "Type De Serveur Radio" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_1", "Aucun" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_2", "Apple Quicktime/Darwin SS4" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_3", "ModMP3, Ices, WinAmp, etc." );
define( "ADMPREF_FORMS_RADIOURL", "Radio Stream URL" );
define( "ADMPREF_FORMS_RADIOPLIST", "Liste d'Écoute Radio" );

define( "ADMPREF_FORMS_JUKEBOX_HELP_1", "1 - Choisir le type de lecteur à utiliser sur le serveur (uniquement requis\\nsi vous voulez jouer la musique sur le serveur qui sert le netjuke).\\n\\n2 - Entrez la location complète du lecteur audio que vous voulez\\néditer avec le netjuke(eg: /usr/bin/mpg123 ou\\nC:\\\Program Files\\\Winamp\\\Winamp.exe).\\n\\n3 - Entrez la location complète d'une liste de jukebox que vous voulez\\néditer avec le netjuke.\\n\\nVoir les paragraphes JUKEBOX FEATURE: SERVER-SIDE PLAYBACK\\nINTEGRATION dans docs/MAINTAIN.txt." );
define( "ADMPREF_FORMS_JUKEBOX_HELP_2", "Aide De Configurations" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER", "Type de Lecteur" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION", "Aucun" );
define( "ADMPREF_FORMS_JUKEBOXPLAYERPATH", "Location du Lecteur" );
define( "ADMPREF_FORMS_JUKEBOXPLIST", "Liste d'Écoute Jukebox" );

define( "ADMPREF_FORMS_HTMLHEAD", "Entêtes HTML" );
define( "ADMPREF_FORMS_HTMLFOOT", "Bas-de-Page HTML" );

define( "ADMPREF_FORMS_ENABLECOMM", "Communauté" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_1", "- Outils de Navigation\\n- Communauté\\n- Listes d'écoute Partagées\\n" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_2", "Options Affectés" );

define( "ADMPREF_FORMS_ENABLEDLOAD", "Téléchargement" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_1", "Si cette option est sélectionnée, un nouveau bouton est activé pour chaque piste\\nafin de laisser les usagers télécharger les fichiers audio.\\n" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_2", "Définition De Cet Option" );

define( "ADMPREF_FORMS_RESPERPAGE_1", "Limiter les résultats à " );
define( "ADMPREF_FORMS_RESPERPAGE_2", "éléments par page (où disponible)" );

define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS", "Comptes De Pistes" );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_1", "Cette option permets de montrer le nombre total de pistes pour la valeur associée\\n(artiste, album ou genre) dans la section Explorer, ainsi que dans les Listes\\nAlphabétiques.\\n\\nVeuillez noter que cette option peut extrèmement ralentir votre serveur car elle\\nfait appel a de nombreuses connections à la table la plus lourde de la base de\\ndonnées. Il est connseillé de n'utiliser cette option qu'avec un serveur ultra-rapide\\net dédié au Netjuke." );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_2", "Définition De Cet Option" );

define( "ADMPREF_FORMS_LANGPACK", "Language" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_1", "Anglais" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_2", "Français" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_3", "Allemand" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_4", "Catalan" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_5", "Espagnol" );

define( "ADMPREF_FORMS_THEMES", "Thèmes d'usagers" );
define( "ADMPREF_FORMS_THEMES_HELP", "Permet aux usagers inscrits de choisir leurs prôpres couleurs et typographies utilisées par le logiciel." );

define( "ADMPREF_FORMS_INVICN", "Inverser Les Îcones" );
define( "ADMPREF_FORMS_INVICN_HELP", "Permet à l'usager d'inverser les couleurs d'îcones: Lecture, Information, Filtrer, etc." );

define( "ADMPREF_FORMS_FONTFACE", "Liste De Typographies" );
define( "ADMPREF_FORMS_FONTSIZE", "Taille De Typographies" );
define( "ADMPREF_FORMS_BGCOLOR", "Fond De Page" );
define( "ADMPREF_FORMS_TEXT", "Couleur De Texte" );
define( "ADMPREF_FORMS_LINK", "Couleur De Liens" );
define( "ADMPREF_FORMS_ALINK", "Couleur De Liens Actifs" );
define( "ADMPREF_FORMS_VLINK", "Couleur De Liens Visités" );
define( "ADMPREF_FORMS_BORDER", "Couleur De Bordure" );
define( "ADMPREF_FORMS_HEADER", "Couleur De Fond D'Entêtes" );
define( "ADMPREF_FORMS_HEADERFC", "Couleur De Texte d'Entêtes" );
define( "ADMPREF_FORMS_CONTENT", "Couleur De Fond Du Contenu" );

define( "ADMPREF_FORMS_BTN_SAVE", "Sauvegarder" );
define( "ADMPREF_FORMS_BTN_RESET", "Recommencer" );

##################################################

?>