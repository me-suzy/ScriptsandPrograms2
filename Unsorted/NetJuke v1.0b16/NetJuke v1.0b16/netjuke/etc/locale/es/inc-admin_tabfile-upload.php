<?php

##################################################

# /admin/tabfile-upload.php

##################################################

define( "TFUPL_HEADER", "LENGUETA-DELIMITADORA de Archivos A Cargar" );

define( "TFUPL_ERROR", "Error con el archivo a Cargar." );
define( "TFUPL_ERROR_NOTXT", "Disculpe, Pero esto no es considerado como un archivo de texto" );

define( "TFUPL_PROCEED", "Proceder para la Herramienta de importación de Archivos" );
define( "TFUPL_RETURN", "Volver a la forma de Cargar Archivo" );

define( "TFUPL_COLS_TR", "Titulo" );
define( "TFUPL_COLS_AR", "Artista" );
define( "TFUPL_COLS_AL", "Album" );
define( "TFUPL_COLS_GE", "Genero" );
define( "TFUPL_COLS_FS", "Tamaño" );
define( "TFUPL_COLS_TI", "Duración" );
define( "TFUPL_COLS_TN", "Numero de Pista" );
define( "TFUPL_COLS_TC", "Contador de Pista" );
define( "TFUPL_COLS_YR", "Año" );
define( "TFUPL_COLS_DT", "Fecha" );
define( "TFUPL_COLS_DA", "Fecha adicionada" );
define( "TFUPL_COLS_BR", "Tasa de Bits" );
define( "TFUPL_COLS_SR", "Frecuencia de Muestreo" );
define( "TFUPL_COLS_VA", "Ajuste del Volumen" );
define( "TFUPL_COLS_FK", "Bueno" );
define( "TFUPL_COLS_CT", "Comentarios" );
define( "TFUPL_COLS_LC", "Ubicación" );

define( "TFUPL_CAPTION_1", "Los Archivos a cargar en la importación deben estar en la lengueta-delimitadora, y tienen las siguientes columnas" );
define( "TFUPL_CAPTION_2", "El script de importación cambiará todo los \":\" encontrados en el campo de  ubicción, si los ultimos no contienen \"://\", por \"/\" para un mejor desempeño con el Mac OS separadores de directorio, como esta característica fue implementada originalmente por el C&G Soundjam y Apple Yo formateo el texto para exportar. Esto lo hará el software <i>algunos productos de software</i> tienen la tendencia de renombrar los archivos que en la columna de localización son mayores a 31 carácteres..." );
define( "TFUPL_CAPTION_3", "El tamaño máximo para cargar un archivo a importar es 2MB ." );
define( "TFUPL_CAPTION_4", "El netjuke no (por el momento) no tiene encuenta archivos de audio grandes, porque los archivos de audio grandes en una página Web pueden ser alterados fácilmente." );

define( "TFUPL_BTN", "Cargar sus Archivos de la Lengueta-Delimitadora" );

##################################################

?>
