<?php

##################################################

# /admin/index.php

##################################################

define( "ADMNDX_SYS_HEADER", "ADMINISTRACIÓ DE SISTEMES" );
define( "ADMNDX_CONT_HEADER", "ADMINISTRACIÓ DE MÚSICA" );

define( "ADMNDX_EDIT_HELP", "Feu clic aquí per accedir a aquesta eina d'administració" );
define( "ADMNDX_INFO_HELP", "AJUDA: Per a més informació sobre aquesta eina d'administració, feu clic aquí" );

define( "ADMNDX_PREFEDIT", "FITXER DE PREFERÈNCIES" );
define( "ADMNDX_PREFEDIT_HELP", "Utilitzeu aquest formulari per editar el fitxer de preferències que conté la informació del login de la base de dades, el camí d'importació de fitxers, la capçalera i el peu de pàgina html, etc." );

define( "ADMNDX_USERMAINT", "MANTENIMENT DE L'USUARI" );
define( "ADMNDX_USERMAINT_HELP", "Permet veure / afegir / editar / esborrar usuaris." );

define( "ADMNDX_HIDFILE", "CERCADOR DE FITXERS OCULTS" );
define( "ADMNDX_HIDFILE_HELP", "Escaneja el directori indicat, hi busca els fitxers ocults o sub-directoris (els noms d'arxius que comencen amb un punt) i genera un fitxer de lots adequat per esborrar-los en la línia d'ordre. És especialment útil per carregar arxius des del Mac a l'Unix (.AppleDouble, .DS_Store, etc.)" );

define( "ADMNDX_TRADD", "ENTRADA MANUAL DE PISTES DE MÚSICA" );
define( "ADMNDX_TRADD_HELP", "Serveix per afegir manualment pistes de música a la base de dades. És especialment útil per a la música que no es troba dins del directori de música, com ara emissores de ràdio virtuals o fitxers residents a Internet." );

define( "ADMNDX_MP3UPL", "CÀRREGA DE FITXERS ÀUDIO" );
define( "ADMNDX_MP3UPL_HELP", "Utilitzeu aquest formulari per carregar fitxers d'audio al servidor local. Els arxius es poden carregar individualment com a .mp3, o en lots com a arxius .zip/.tar.gz/.tgz" );

define( "ADMNDX_MP3FIND", "RECERCA RECURSIVA DE FITXERS ÀUDIO" );
define( "ADMNDX_MP3FIND_HELP", "Escaneja de forma recursiva el directori sol·licitat i hi busca fitxers que acabin en \".mp3\" or \".ogg\" or \".wma\" (ja estiguin en majúscula o en minúscula). Si troba etiquetes ID3 (o semblants), automàticament crea un fitxer d'importació. Finalment, presenta un formulari per introduir -de forma opcional- la informació per als fitxers que no tenen metaetiquetes vàlides." );

define( "ADMNDX_TABFILEUPL", "CÀRREGA DE FITXERS DELIMITATS" );
define( "ADMNDX_TABFILEUPL_HELP", "S'utilitza per carregar fitxers de text especials delimitats que es volen importar a la base de dades. Feu clic per obtenir més informació sobre el format del fitxer, etc." );

define( "ADMNDX_TABFILEIMP", "IMPORTACIÓ DE FITXERS DELIMITATS" );
define( "ADMNDX_TABFILEIMP_HELP", "Aquesta utilitat processa tots els arxius de text especials delimitats que s'han carregat o generat amb altres eines, i n'importa el contingut a la base de dades activa." );

define( "ADMNDX_DBMAINTAIN", "MANTENIMENT DE LA BASE DE DADES DE MÚSICA" );
define( "ADMNDX_DBMAINTAIN_HELP", "Utilitats que serveixen per fer còpies de seguretat de la base de dades de música, escanejar fitxers d'àudio extraviats, etc." );

##################################################

?>