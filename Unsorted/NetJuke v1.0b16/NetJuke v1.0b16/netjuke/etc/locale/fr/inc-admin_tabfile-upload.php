<?php

##################################################

# /admin/tabfile-upload.php

##################################################

define( "TFUPL_HEADER", "TÉLÉCHARGEMENT DE FICHIERS D'IMPORT" );

define( "TFUPL_ERROR", "Erreur de téléchargement." );
define( "TFUPL_ERROR_NOTXT", "Désolé, mais ce fichier n'est pas considéré comme un fichier texte." );

define( "TFUPL_PROCEED", "Accéder à l'Outils d'Import" );
define( "TFUPL_RETURN", "Retouner au Formulaire" );

define( "TFUPL_COLS_TR", "Titre" );
define( "TFUPL_COLS_AR", "Artiste" );
define( "TFUPL_COLS_AL", "Album" );
define( "TFUPL_COLS_GE", "Genre" );
define( "TFUPL_COLS_FS", "Taille" );
define( "TFUPL_COLS_TI", "Durée" );
define( "TFUPL_COLS_TN", "Numéro de Piste" );
define( "TFUPL_COLS_TC", "Compte de Pistes" );
define( "TFUPL_COLS_YR", "Année" );
define( "TFUPL_COLS_DT", "Date" );
define( "TFUPL_COLS_DA", "Date d'Ajout" );
define( "TFUPL_COLS_BR", "Bit Rate" );
define( "TFUPL_COLS_SR", "Sample Rate" );
define( "TFUPL_COLS_VA", "Ajustement de Volume" );
define( "TFUPL_COLS_FK", "Type de Fichier" );
define( "TFUPL_COLS_CT", "Commentaires" );
define( "TFUPL_COLS_LC", "Location" );

define( "TFUPL_CAPTION_1", "les colonnes du fichier importé doivent être les suivantes et être séparées par un signe de tabulation (indiqué ci-dessous par \\t)." );
define( "TFUPL_CAPTION_2", "L'outils d'import remplacera tous les \":\" trouvés dans la colonnes de location, si la valeur de cette dernière ne contient pas \"://\", par \"/\" afin de mieux supporter les Macintosh, car cet option a originallement été conçue pour la fonction d'export des logiciels C&G Soundjam et Apple iTunes. Faites attention au nom de fichiers ou répertoires de plus de 31 caractères dépendemment de votre système d'opération." );
define( "TFUPL_CAPTION_3", "Les fichiers téléchargés sont individuellement limités à 2 MB." );
define( "TFUPL_CAPTION_4", "Le netjuke ne permet pas (encore) de télécharger des fichiers audio vers le serveur car poster des ressources énormes depuis une page web peut être extrèmement peu fiable" );

define( "TFUPL_BTN", "Télécharger Les Fichiers Soumis" );

##################################################

?>