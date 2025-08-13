<?php

##################################################

# /admin/index.php

##################################################

define( "ADMNDX_SYS_HEADER", "ADMINISTRATION DE SYSTÊME" );
define( "ADMNDX_CONT_HEADER", "ADMINISTRATION DE MUSIQUE" );

define( "ADMNDX_EDIT_HELP", "Cliquez ici pour accéder à cet outils" );
define( "ADMNDX_INFO_HELP", "AIDE: Cliquez ici pour accéder à de plus amples information sur cet outils." );

define( "ADMNDX_PREFEDIT", "FICHIER DE PRÉFÉRENCES" );
define( "ADMNDX_PREFEDIT_HELP", "Utilisé pour modifier le fichier de préférences qui contient vos crédentiels de base de données, le language utilisé par le logiciel, etc." );

define( "ADMNDX_USERMAINT", "MAINTENANCE D'USAGERS" );
define( "ADMNDX_USERMAINT_HELP", "Utilisé pour créer, mofifier ou effacer des comptes d'adhérants." );

define( "ADMNDX_HIDFILE", "CHERCHEUR DE FICHIERS CACHÉS" );
define( "ADMNDX_HIDFILE_HELP", "Cherche le répertoire soumis pour des fichiers \"invisibles\" (commençant par un point) afin de générer un scripte approprié à les effacer en groupe. Ceci est particuliêrement intéressant si vous téléchargez des fichers depuis un Mac vers un serveur Unix (.AppleDouble, .DS_Store, etc)." );

define( "ADMNDX_PHPINFO", "INFOS PHP" );
define( "ADMNDX_PHPINFO_HELP", "Un outils qui affiche des infos très utiles associées avec le PHP résidant sur le serveur du Netjuke." );

define( "ADMNDX_TRADD", "AJOUT MANUEL DE MUSIQUE" );
define( "ADMNDX_TRADD_HELP", "Utilisé pour ajouter de la musique manuellement à la base de données. Ceci est particuliêrement intéressant pour de la musique qui ne se trouve pas dans le répertoire dédié, tels que des Stations de Radio Virtuelles, ou des fichiers résidants ailleurs sur Internet." );

define( "ADMNDX_MP3FIND", "CHERCHEUR RÉCURSIF DE FICHIERS AUDIO" );
define( "ADMNDX_MP3FIND_HELP", "Cherche le répertoire soumis pour des fichiers dont le nom termine par \".mp3\" ou \".ogg\" ou \".wma\". Si des \"étiquette\" ID3 (ou similaires) sont trouvées, un fichier d'import est généré automatiquement. Un formulaire vous sera potentiellement présenté à la fin afin d'entrer l'information requise si des fichiers sans \"étiquette\" sont trouvés." );

define( "ADMNDX_TABFILEIMP", "OUTILS D'IMPORT DE FICHIERS" );
define( "ADMNDX_TABFILEIMP_HELP", "Utilisé pour traîter tous les fichiers d'import spéciaux trouvés dans le répertoire dédié qui ont été téléchargés ou générés par d'autres outils. Le contenu de ces fichers est ensuite importé dans la base de données." );

define( "ADMNDX_DBMAINTAIN", "MAINTENANCE DE BASE DE DONNÉES" );
define( "ADMNDX_DBMAINTAIN_HELP", "Utilisé pour sauvegarder tout le contenu de la base de données de musique vers un fichier de secours, effacer les fichiers manquants, etc." );

##################################################

?>