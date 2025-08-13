<?php

##################################################

# /admin/prefs-edit.php

##################################################

define( "ADMPREF_ERR_DBCONN", "No se puede conectar a la base de datos usando la información suministrada." );

define( "ADMPREF_ERR_RADIOPLIST", "El directorio o archivo usado para la lista de reproducción para radio no se ha encontrado o este no escribible.\\nPor favor hágalo escribible por el usuario del servidor web, o para todos ." );

define( "ADMPREF_ERR_JUKEBOXPLIST", "El directorio o archivo usado para la lista de reproducción para radio no se ha encontrado o este no escribible.\\nPor favor hágalo escribible por el usuario del servidor web, o para todos ." );
define( "ADMPREF_ERR_JUKEBOXPLAYERPATH", "Reproductor Jukebox no encontrado. Por favor revise el campo del path del reproductor." );

define( "ADMPREF_FILEINFO_1", "Creado" );
define( "ADMPREF_FILEINFO_2", "Desde" );
define( "ADMPREF_FILEINFO_3", "Guardar Como" );

define( "ADMPREF_DENIED_1", "No se puede escribir su archivo de preferencia. Error de permisos." );

define( "ADMPREF_CHECKFORM_SECKEY", "Su nueva clave de seguridad debe poseer 30 caracteres máximo para ser actualizada" );
define( "ADMPREF_CHECKFORM_DBNAME", "Por Favor Digite el Nombre de La Base de Datos" );
define( "ADMPREF_CHECKFORM_STREAM", "Por Favor Ingrese su servidor de Streaming" );
define( "ADMPREF_CHECKFORM_BGCOLOR", "Por favor Seleccione un Color de Fondo." );
define( "ADMPREF_CHECKFORM_FONTFACE", "Por favor Seleccione una Lista de Fuentes." );
define( "ADMPREF_CHECKFORM_FONTSIZE", "Por favor Seleccione el Tamao de la Fuente." );
define( "ADMPREF_CHECKFORM_TEXT", "Por favor Seleccione un Color de Texto." );
define( "ADMPREF_CHECKFORM_LINK", "Por favor Seleccione un Color para los Vinculos." );
define( "ADMPREF_CHECKFORM_ALINK", "Por favor Seleccione un Color para los Vinculos Activos." );
define( "ADMPREF_CHECKFORM_VLINK", "Por favor Seleccione un Color para los Vinculos Visitados." );
define( "ADMPREF_CHECKFORM_BORDER", "Por favor seleccione un color para el borde la tabla." );
define( "ADMPREF_CHECKFORM_HEADER", "Por favor seleccione un color para el Encabezado de la tabla." );
define( "ADMPREF_CHECKFORM_HEADERFC", "Por favor seleccione un color para el color de la fuente en la Cabeza de la tabla." );
define( "ADMPREF_CHECKFORM_CONTENT", "Por favor seleccione un contenido de la Table  de Colores." );

define( "ADMPREF_HEADER_1", "PREFERENCIAS DEL SISTEMA" );
define( "ADMPREF_HEADER_2", "PREFERENCIAS DEL CONTENIDO" );
define( "ADMPREF_HEADER_3", "PREFERENCIAS DE ESTACIONES DE RADIO EN INTERNET" );
define( "ADMPREF_HEADER_4", "PREFERENCIAS DE APARIENCIA GLOBAL" );
define( "ADMPREF_HEADER_5", "PREFERENCIAS JUKEBOX (Parte-Servidor Playback)" );

define( "ADMPREF_CAPTION", "El formulario de abajo controla los valores de la fuente y los colores de los temas del web site, y la opcin de permitir a los usuarios configurar sus propios temas. Esto es, que usuarios sern vistos en modo pblico, tambin como sus ajustes por defecto cuando una cuenta es creada por primera vez." );
define( "ADMPREF_PALETTE", "Use esta paleta de colores para seleccionar un entorno por defecto." );

define( "ADMPREF_FORMS_CAPT_ENABLED", "Activo" );
 
define( "ADMPREF_FORMS_SAVETOFILE", "Guardar en Archivo" );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_1", "Para guardar automticamente esta informacin en su archivo de preferencias, el \\narchivo debe ser grabado por el servidor web. Para hacerlo, aqu hay dos\\ soluciones:\\n\\n -El archivo puede ser puede ser grabado de uno en uno (no recomendado). \\n\\n- El propetario del archivo puede ser asignado al usuario asociado con la web\\n Software de servidor (usualemente requiere un login de root/admin).\\n\\nLa alternativa, pero segura es simplemente copiar y pegar la informacin\\nPresentada en el siguiente pantallazo para el archivo  de preferecnias del sistema /etc/inc-prefs.php." );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_2", "Nota Importante de Seguridad" );

define( "ADMPREF_FORMS_SECMODE", "Modos de Seguridad" );
define( "ADMPREF_FORMS_SECKEY", "Autentificacin de Seguridad" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_1", "MODOS DE SEGURIDAS:\\n0.0 = Contenido Pblico - Login activo - Registro Pblico Activo\\n0.1 = Contenido Pblico - Login activo - Registro Pblico Inactivo\\n0.2 = Contenido Pblico - Admin Login Requerido - Registro Pblico Inactivo\\n1.0 = Contenido Privado - Login Activo - Registro Pblico Activo\\n1.1 = Contenido Privado - Login Activo - Registro Pblico Inactivo\\n1.2 = Contenido Privado - Admin Login Requerido - Registro Pblico Inactivo\\n" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_2", "\\nLLAVE DE SEGURIDAD:\\nLa llave de Seguridad es usada como una Clave aleatorea para generar una Sesin Unica\\nids necesitados por el netjuke en el momento de login. Una llave es generada por defecto para tu sobre la\\ninstalacin y/o actualizacin, y es re-generada cada vez que usted actualiza sus archivos de configuraciones,\\npero usted podr actualizar a fondo este valor en el momento para hacerlo, incorporando uno personalizado\\nEntero de ms de 30 caracteres en el campo de la forma abajo. El entero puede ser cualquiera,\\n  y que no requiera recordar esto en cualquier momento(Esto no es una Cotrasea)." );
define( "ADMPREF_FORMS_SECMODE_HELP_2", "Modo de Seguridad & Definiciones de Autentificacin" );

define( "ADMPREF_FORMS_DBTYPE", "Tipo DB" );
define( "ADMPREF_FORMS_DBHOST", "Servidor DB" );
define( "ADMPREF_FORMS_DBUSER", "Usuario DB" );
define( "ADMPREF_FORMS_DBPASS", "Password DB" );
define( "ADMPREF_FORMS_DBNAME", "Nombre DB" );

define( "ADMPREF_FORMS_STREAM", "Servidor de msica" );
define( "ADMPREF_FORMS_MUSICDIR", "Directorio de Msica" );

define( "ADMPREF_FORMS_PROTECTMEDIA", "Proteccion de  Media" );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_1", "Activar esta caracteristica usando un proxy de media con el cual tratar\\nde parar sin capturar downloads usando el url visualizado el audio\\nsonar.disculpas, pero esto PODRç ser apadado si tu reproduces Archivos Ogg Vorbis." );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_2", "Definicin de Caracteristicas" );

define( "ADMPREF_FORMS_REALONLY", "Real Player" );
define( "ADMPREF_FORMS_REALONLY_HELP_1", "Activando esta caracteristica limita el streaming de audio solo para la \\nAplicacin Real Player, limitando la visualizacin del archivo URL." );
define( "ADMPREF_FORMS_REALONLY_HELP_2", "Definicin de la Caracteristica" );

define( "ADMPREF_FORMS_RADIO_HELP_1", "1 - Seleccione el tipo de servidor de radio que quiere usar desde la lista (solamente se requiereo\\nsi quiere generar lista de reproducción para correrla en una estación de radio de internet).\\n\\n2 - Entre el path completo del para el archivo de texto de lista de reproducción que quiere editar.\\n\\n3 - Opcionalmente entre el URL del Streaming para mostrar un\\\"Radio\\\" link en la barra de herramientas.\\n\\nPara habilitar el soporte a múltiples tipos de servidores de radio, el netjuke no lo ha probado\\npara administrarse completamente por si solo. El netjuke solamente formateará y grabará\\nlas pistas que seleccione para la lista de reproducción que se creará para satisfacer las necesidades del servidor\\ny luego deberá reiniciar su servidor de streaming cualquiera que sea la\\n interface de administración suministrada por los desarrolladores del servidor que seleccionó\\n(Nota: El QT/Darwin SS4 fué una excelente herramienta de administración libre vía web ;o)\\n\\nExtra: Si quiere administrar mas de un Radio Streaming desde el netjuke, solo\\nponga una lista de reproducción falsa en algún lugar, y muévala manualmente al lugar apropiado\\nluego de editarlo através del netjuke (Yo no uso el link Radio en\\nteste contexto porque solamente puede hacer a uno desde aquí).\\n\\nVea INTERNET RADIO STREAM SERVER INTEGRATION en docs/MAINTAIN.txt." );

define( "ADMPREF_FORMS_RADIO_HELP_2", "Radio Ayuda de Configuracin" );
define( "ADMPREF_FORMS_RADIOTYPE", "Tipo de Servidor de Radio" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_1", "Ninguno" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_2", "Apple Quicktime/Darwin SS4" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_3", "ModMP3, Ices, WinAmp, etc." );
define( "ADMPREF_FORMS_RADIOURL", "Radio Stream URL" );
define( "ADMPREF_FORMS_RADIOPLIST", "Lista de Reproduccin de Radio" );

define( "ADMPREF_FORMS_JUKEBOX_HELP_1", "1 - Seleccione el tipo de reproductor de audio que quiere usar en el servidor (solamente requerido\\nsi quiere usar audio  playback del servidor remoto donde se está corriendo este netjuke).\\n\\n2 - Entre la ruta completa donde se encuentra el reproductor en el servidor\\n(ej: /usr/bin/mpg123 or C:\\\Archivos de Programa\\\Winamp\\\Winamp.exe).\\n\\n3 - Entre la ruta completa donde se encuentra el archivo de lista de reproducción que quiere editar.\\n\\nLas caracterísiticas de netjuke soportan generación y playbackplayl\\ de listas de reproducción por parte del servidor (la computadora que corre el netjuke). Esto\\nes principalmente para la gente que quiere reproducir la música desde otra máquina\\n que los que están accesando desde el netjuke. Esta característica asignada es para\\neste tiempo limitada porque no concuerda con las metas del netjuke.\\nSi quiere mas control sobre el reproductor remoto, y mejores características, disfruta\\ny ayudanos integrando nuevos reproductores o actualizando el código, o también puedes\\nchecar mas aplicaciones especializadas en esta tarea.\\nEl principal foco de netjuke es el streaming.\\n\\nVea JUKEBOX FEATURE: SERVER-SIDE PLAYBACK INTEGRATION en\\ndocs/INSTALL.txt para más información de como configurar su reproductor, etc." );
define( "ADMPREF_FORMS_JUKEBOX_HELP_2", "Ayuda de Configuración del Jukebox" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER", "Tipo de Reproductor" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION", "Ninguno" );
define( "ADMPREF_FORMS_JUKEBOXPLAYERPATH", "Ruta del Reproductor" );
define( "ADMPREF_FORMS_JUKEBOXPLIST", "Lista de Reproducción del Jukebox" );

define( "ADMPREF_FORMS_HTMLHEAD", "HTML Encabezado" );
define( "ADMPREF_FORMS_HTMLFOOT", "HTML Pie de Página" );

define( "ADMPREF_FORMS_ENABLECOMM", "Comunidad" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_1", "- Barra de Herramientas Primaria de Navegacin\\n- Seccin de la Comunidad\\n- Caracteristicas Compartdias de la Lista de Reproduccin\\n" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_2", "Cambios Realizados " );

define( "ADMPREF_FORMS_ENABLEDLOAD", "Descargar Archivo" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_1", "Si est permitido, un nuevo icono se mostrar para arriba en los listados de la pista tan \\nque los usuarios podrn descargar un archivo en vez de escucharlo.\\n" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_2", "Futuras definiciones " );

define( "ADMPREF_FORMS_RESPERPAGE_1", "Limitar los resultados a  " );
define( "ADMPREF_FORMS_RESPERPAGE_2", "items por Pgina cuando sean Disponibles" );

define( "admpref_forms_display_trcounts", "Visualizar Contadores de Pistas" );
define( "admpref_forms_display_trcounts_help_1", "esta caracterstica exhibe el nmero total de las pistas para el valor relacionado (artista, album\\no genero) en la pgina del navegador y los listados automaticos.\\n\\nobserve por favor que esta opcin puede traer su servidor a un cuelgue porque estas cuentas\\nrequiera una enorme cantidad de conexin a la tabla ms pesada de la base de datos. .\\nSolo use esta caracterstica si tu corres el Netjuke en un servidor dedicado extremadamente rpido." );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_2", "Definicin de caractersticas" );

define( "ADMPREF_FORMS_LANGPACK", "Lenguaje" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_1", "Ingls" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_2", "Francs" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_3", "Alemn" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_4", "Catalan" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_5", "Espaol" );

define( "ADMPREF_FORMS_THEMES", "Temas Personalizados por el Usuario" );
define( "ADMPREF_FORMS_THEMES_HELP", "Permite a los Usuarios crear sus Propios Colores/Temas de Fuentes una vez que esten en el Sistema" );

define( "ADMPREF_FORMS_INVICN", "Invertir Iconos" );
define( "ADMPREF_FORMS_INVICN_HELP", "Permite a los Usuarios Invertir el Color de los Iconos: Sonar, Obtener Informacin, Filtrar..." );

define( "ADMPREF_FORMS_FONTFACE", "Tipo de fuente" );
define( "ADMPREF_FORMS_FONTSIZE", "Tamao de La Fuente" );
define( "ADMPREF_FORMS_BGCOLOR", "Color De Fondo" );
define( "ADMPREF_FORMS_TEXT", "Color del Texto" );
define( "ADMPREF_FORMS_LINK", "Color de los Vinculos" );
define( "ADMPREF_FORMS_ALINK", "Color de los Vinculos Activos" );
define( "ADMPREF_FORMS_VLINK", "Color de los Vinculos Visitados" );
define( "ADMPREF_FORMS_BORDER", "Color del Marco" );
define( "ADMPREF_FORMS_HEADER", "Color de la Cabezera" );
define( "ADMPREF_FORMS_HEADERFC", "Color de la Fuente de la Cabezera" );
define( "ADMPREF_FORMS_CONTENT", "Content Color" );

define( "ADMPREF_FORMS_BTN_SAVE", "Guardar" );
define( "ADMPREF_FORMS_BTN_RESET", "Reiniciar" );

##################################################

?>
