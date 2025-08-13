<?php

##################################################

# /admin/db-maintain.php

##################################################

define( "TFBCKP_CONFIRM", "Êtes-vous sûr de vouloir procéder?" );

define( "TFBCKP_HEADER", "MAINTENANCE DE BASE DE DONNÉES" );

define( "TFBCKP_BACKUP_START", "Sauvegarde de Secour De La Base De Donnée de Musique" );
define( "TFBCKP_BACKUP_HELP", "Sauvegarde toute les données reliées à la musique dans un fichier texte. Ces fichiers peuvent ensuite être utilisés avec l'outils d'import de fichiers, ou téléchargés pour échanger des données avec d'autres outils d'édition (MS Access, MS Excel, etc)." );
define( "TFBCKP_BACKUP_DONE", "Voir le Fichier de Secours" );

define( "TFBCKP_MAINTAIN_START", "Maintenance De La Base De Donnée de Musique" );
define( "TFBCKP_MAINTAIN_HELP", "Scanne toute la base de données de musique et efface toutes les entrées dont le fichier local a lui-même été effacé. Ignore les fichiers qui ont une addresse Web complète (http://, rtsp://. etc.). Si un artiste, album ou genre est laissé sans aucune chanson, il sera également effacé." );
define( "TFBCKP_MAINTAIN_DONE", "fichiers audio manquant ont été trouvés et effacés de la base de données." );

define( "TFBCKP_DELETE_START", "Effacer Toutes Les Données Reliées À La Musique" );
define( "TFBCKP_DELETE_HELP", "Effacee toutes les données reliées à la musique (tracks, artists, etc.). Les comptes d'usagers, leurs préférences et leurs données de session sont laissées intactes. Les listes d'écoutes sont aussi effacées." );
define( "TFBCKP_DELETE_DONE", "Toutes les données reliées à la musique on été effacées. Les comptes d'usagers sont encore disponibles." );



##################################################

?>