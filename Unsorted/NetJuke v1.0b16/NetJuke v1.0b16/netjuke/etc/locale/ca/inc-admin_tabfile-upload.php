<?php

##################################################

# /admin/tabfile-upload.php

##################################################

define( "TFUPL_HEADER", "CÀRREGA DE FITXER DELIMITATS" );

define( "TFUPL_ERROR", "Hi ha hagut un error en la càrrega del fitxer." );
define( "TFUPL_ERROR_NOTXT", "Aquest fitxer no es considera un arxiu de text" );

define( "TFUPL_PROCEED", "Accedeix a l'eina d'importació de fitxers" );
define( "TFUPL_RETURN", "Torna al formulari de càrrega" );

define( "TFUPL_COLS_TR", "Títol" );
define( "TFUPL_COLS_AR", "Intèrpret" );
define( "TFUPL_COLS_AL", "Àlbum" );
define( "TFUPL_COLS_GE", "Gènere" );
define( "TFUPL_COLS_FS", "Mida" );
define( "TFUPL_COLS_TI", "Durada" );
define( "TFUPL_COLS_TN", "Número de pista" );
define( "TFUPL_COLS_TC", "Comptador de pistes" );
define( "TFUPL_COLS_YR", "Any" );
define( "TFUPL_COLS_DT", "Data" );
define( "TFUPL_COLS_DA", "Data afegida" );
define( "TFUPL_COLS_BR", "Bit rate" );
define( "TFUPL_COLS_SR", "Sample rate" );
define( "TFUPL_COLS_VA", "Ajust de volum" );
define( "TFUPL_COLS_FK", "Tipus de fitxer" );
define( "TFUPL_COLS_CT", "Comentaris" );
define( "TFUPL_COLS_LC", "Ubicació" );

define( "TFUPL_CAPTION_1", "Els fitxers carregats per a importar han d'estar separats per un signe de tabulació i han de tenir les següents columnes" );
define( "TFUPL_CAPTION_2", "L'eina d'importació reemplaça tots els \":\" trobats en les columnes d'ubicació, si el valor de la darrera no conté \"://\", per \"/\" per tal de permetre l'ús de Macintosh, ja que aquesta opció ha estat dissenyada per a la funció d'exportació de C&G Soundjam i Apple iTunes. Teniu en compte que alguns programaris tendeixen a canviar el nom dels fitxers o directoris de més de 31 caràcters de la columna d'ubicació.");
define( "TFUPL_CAPTION_3", "Els fitxers carregats tenen un límit de 2MB." );
define( "TFUPL_CAPTION_4", "El Netjuke (encara) no permet carregar fitxers àudio perquè penjar fitxers grans d'una pàgina web pot ser molt poc fiable." );

define( "TFUPL_BTN", "Descarrega els fitxers delimitats" );

##################################################

?>