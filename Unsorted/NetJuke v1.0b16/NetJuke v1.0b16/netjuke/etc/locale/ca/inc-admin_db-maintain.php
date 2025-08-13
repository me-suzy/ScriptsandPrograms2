<?php

##################################################

# /admin/db-maintain.php

##################################################

define( "TFBCKP_CONFIRM", "Esteu segur que voleu continuar?" );

define( "TFBCKP_HEADER", "MANTENIMENT DE LA BASE DE DADES DE MÚSICA" );

define( "TFBCKP_BACKUP_START", "Còpia de seguretat de la base de dades de música" );
define( "TFBCKP_BACKUP_HELP", "Fa còpies de seguretat de totes les dades relacionades amb la música (pistes, intèrprets, etc.) en un arxiu de text. Els fitxers poden ser còpies de seguretat que es poden recuperar mitjançant l'eina d'importació, o baixar i utilitzar per compartir-ne la informació amb altres eines (Full de càlcul, etc.)." );
define( "TFBCKP_BACKUP_DONE", "Mostra fitxer de còpia de seguretat" );

define( "TFBCKP_MAINTAIN_START", "Fer manteniment de la base de dades de música" );
define( "TFBCKP_MAINTAIN_HELP", "Eina per escanejar tota la base de dades de música i esborrar els discs si no s'hi troba el fitxer local relacionat. Els arxius amb un URL complet (http://, rtsp://. etc.) quedaran intactes. Si posteriorment algun intèrpret, àlbum o gènere es deixès sense pista, també s'esborrarà." );
define( "TFBCKP_MAINTAIN_DONE", "Arxius d'àudio extraviats trobats i esborrats de la base de dades." );

define( "TFBCKP_DELETE_START", "Esborra totes les dades relacionades amb la música" );
define( "TFBCKP_DELETE_HELP", "Esborra totes les dades relacionades amb la música (pistes, intèrprets, etc.). Els usuaris, les seves preferències i la informació de la sessió romanen intactes. S'esborren les llistes de cançons." );
define( "TFBCKP_DELETE_DONE", "S'ha esborrat tota la informació relacionada amb la música. Els documents de l'usuari encara estan disponibles." );

##################################################

?>