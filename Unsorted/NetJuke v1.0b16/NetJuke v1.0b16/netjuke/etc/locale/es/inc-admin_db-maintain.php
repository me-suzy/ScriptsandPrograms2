<?php

##################################################

# /admin/db-maintain.php

##################################################

define( "TFBCKP_CONFIRM", "Está seguro?" );

define( "TFBCKP_HEADER", "MANTENIMIENTO DE LA BASE DE DATOS MUSICAL" );

define( "TFBCKP_BACKUP_START", "Respaldo de la Base de Datos Musical" );
define( "TFBCKP_BACKUP_HELP", "Respaldo de todos los datos relacionados con la Música (Pistas, Artistas, etc.) en un archivo de texto. Los archivos pueden ser restaurados a partir de las copias de seguridad en la demanda usando la utilidad importar,  O bajadosy usados como parte de datos con otras herramientas (Hojas de Cálculo, etc.)." );
define( "TFBCKP_BACKUP_DONE", "Ver Archivo de Respaldo" );

define( "TFBCKP_MAINTAIN_START", "Mantenimiento de la Base de Datos Musical" );
define( "TFBCKP_MAINTAIN_HELP", "Utilidad para escanear completamente la Base de Datos Musical, y borrar los registros que no se hayan encontrado localmente. Archivos con una dirección URL Completa (http://, rtsp://. etc.) Continuarán sin modificar. Si un artista, album o genero esta posteriormente sin pista, este también será borrado." );
define( "TFBCKP_MAINTAIN_DONE", "Los archivos de Audio perdidos, tienen que ser ubicados y borrados de la base de datos." );

define( "TFBCKP_DELETE_START", "Limpiar Todos Los Datos Relacionados Con la Musica" );
define( "TFBCKP_DELETE_HELP", "Borrar todos los datos relacionados con la Música(Pistas, Artistas, etc.). Los usuarios, sus preferencias y datos de sesión remains untouched. Lista de reproducción será borrada." );
define( "TFBCKP_DELETE_DONE", "Toda la información relacionada con los datos musicales han sido corregidos. Los cambios del usurio todavia están disponibles." );

##################################################

?>
