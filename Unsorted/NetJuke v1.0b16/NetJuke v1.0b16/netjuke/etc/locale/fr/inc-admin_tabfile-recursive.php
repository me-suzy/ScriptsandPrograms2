<?php

##################################################

# /admin/tabfile-recursive.php

##################################################

define( "TFREC_HEADER", "CHERCHEUR RÉCURSIF DE FICHIERS AUDIO" );
define( "TFREC_HEADER_AUTO", "Mode Automatique" );
define( "TFREC_HEADER_INTER", "Mode Interactif" );
define( "TFREC_CAPTION_1", "" );
define( "TFREC_CAPTION_2", "Veuillez soumettre un sous-répertoire à scanner, ou cliquez le bouton ci-dessous pour scanner tout le répertoire de musique défini dans le fichier de préférences. Si c'est le premiêre fois que vous importez votre musique, il est conseillé de soumettre un sous-répertoire afin de limiter le temps d'import." );
define( "TFREC_OPTION_1", "Recursif" );
define( "TFREC_OPTION_2", "Utiliser le nom de fichier pour les fichiers qui n'ont pas d'info ID3 et importer" );
define( "TFREC_OPTION_3", "Détaillé" );
define( "TFREC_OPTION_4", "Importer Dans la Base de données directement." );
define( "TFREC_BTN", "Lancer la Procédure" );

define( "TFREC_COLS_TR", "Titre" );
define( "TFREC_COLS_AR", "Artiste" );
define( "TFREC_COLS_AL", "Album" );
define( "TFREC_COLS_GE", "Genre" );
define( "TFREC_COLS_FS", "Taille" );
define( "TFREC_COLS_TI", "Durée" );
define( "TFREC_COLS_TN", "Numéro de Piste" );
define( "TFREC_COLS_TC", "Compte de Pistes" );
define( "TFREC_COLS_YR", "Année" );
define( "TFREC_COLS_DT", "Date" );
define( "TFREC_COLS_DA", "Date d'Ajout" );
define( "TFREC_COLS_BR", "Bit Rate" );
define( "TFREC_COLS_SR", "Sample Rate" );
define( "TFREC_COLS_VA", "Ajustement de Volume" );
define( "TFREC_COLS_FK", "Type de Fichier" );
define( "TFREC_COLS_CT", "Commentaires" );
define( "TFREC_COLS_LC", "Location" );

define( "TFREC_ERROR_NOMUDIR_1", "Le répertore que vous avez soumis ne fait pas partie de celui sauvegardé dans votre fichier de préférences." );
define( "TFREC_ERROR_NOMUDIR_2", "n'est pas le répertoire de musique sauvegardé dans votre fichier de préférences." );
define( "TFREC_ERROR_NODIR", "Veuillez soumettre un répertoire valide à être cherché pour des fichiers MP3." );
define( "TFREC_ERROR_NOFILE", "Tous les fichiers dans votre répertoire de musique ont déjà été importé auparavant." );

define( "TFREC_VIEW", "Voir Ce Fichier." );
define( "TFREC_PROCEED", "Accéder à l'Outils d'Import" );

define( "TFREC_SUCCESS_1", "fichiers MP3 ont été trouvés et soumis à être importés." );
define( "TFREC_SUCCESS_2", "Auncune erreur n'à été trouvée." );
define( "TFREC_SUCCESS_3", "fichiers d'import ont été créé(s) et soumis à être importés." );

define( "TFREC_INSERT_1", "Importé" );
define( "TFREC_INSERT_2", "piste(s)" );
define( "TFREC_INSERT_3", "artiste(s)" );
define( "TFREC_INSERT_4", "album(s)" );
define( "TFREC_INSERT_5", "genre(s)" );

define( "TFREC_FORM_HELP_1", "Les fichiers suivants" );
define( "TFREC_FORM_HELP_2", "n'ont pas d'étiquettes ID3 (ou similaires) valides." );
define( "TFREC_FORM_HELP_3", "Afin d'importer ces pistes/fichiers, veuillez soumettre les détails appropriés manuellement." );
define( "TFREC_FORM_HELP_4", "Seulement une poignée de fichiers affectés sont listés ci-dessous afin de limiter le temps de téléchargement de cet écran." );
define( "TFREC_FORM_BTN", "Créer Ce Nouveau Fichier d'Import" );

##################################################

?>