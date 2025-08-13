<?php

##################################################

# /admin/prefs-edit.php

##################################################

define( "ADMPREF_ERR_DBCONN", "No es pot connectar a la base de dades amb la informació proporcionada." );

define( "ADMPREF_ERR_RADIOPLIST", "No es troba o no es pot escriure sobre el directori o arxiu utilitzat per a la llista de ràdio.\\nSi us plau, feu que s'hi pugui escriure per l'usuari del web server o per tothom." );

define( "ADMPREF_ERR_JUKEBOXPLIST", "No es troba o no es pot escriure sobre el directori o arxiu utilitzat per a la llista del JUKEBOX.\\nSi us plau, feu que s'hi pugui escriure per l'usuari del web server o per tothom." );
define( "ADMPREF_ERR_JUKEBOXPLAYERPATH", "No s'ha trobat el reproductor del Jukebox. Si us plau, comproveu-ne el camí d'accés." );

define( "ADMPREF_FILEINFO_1", "Creat" );
define( "ADMPREF_FILEINFO_2", "Des de" );
define( "ADMPREF_FILEINFO_3", "Anomena i desa" );

define( "ADMPREF_DENIED_1", "No es pot escriure al fitxer de preferències. Error d'autorització." );

define( "ADMPREF_CHECKFORM_SECKEY", "La clau de seguretat ha de tenir com a mínim 30 caràcters per poder ser actualitzada" );
define( "ADMPREF_CHECKFORM_DBNAME", "Si us plau, introduïu un nom de BD" );
define( "ADMPREF_CHECKFORM_STREAM", "Si us plau, introduïu un servidor Streaming" );
define( "ADMPREF_CHECKFORM_BGCOLOR", "Si us plau, escolliu un color de fons." );
define( "ADMPREF_CHECKFORM_FONTFACE", "Si us plau, escolliu una llista de tipografiaes." );
define( "ADMPREF_CHECKFORM_FONTSIZE", "Si us plau, escolliu una mida tipogràfica." );
define( "ADMPREF_CHECKFORM_TEXT", "Si us plau, escolliu un color de text." );
define( "ADMPREF_CHECKFORM_LINK", "Si us plau, escolliu un color per als enllaços." );
define( "ADMPREF_CHECKFORM_ALINK", "Si us plau, escolliu un color per als enllaços actius." );
define( "ADMPREF_CHECKFORM_VLINK", "Si us plau, escolliu un color per als enllaços visitats.");
define( "ADMPREF_CHECKFORM_BORDER", "Si us plau, escolliu un color per a la vora de la taula." );
define( "ADMPREF_CHECKFORM_HEADER", "Si us plau, escolliu un color per a la capçalera de la taula." );
define( "ADMPREF_CHECKFORM_HEADERFC", "Si us plau, escolliu un color per al text de la capçalera de la taula." );
define( "ADMPREF_CHECKFORM_CONTENT", "Si us plau, escolliu un color per al contingut de la taula." );

define( "ADMPREF_HEADER_1", "PREFERÈNCIES DE SISTEMA" );
define( "ADMPREF_HEADER_2", "PREFERÈNCIES DE CONTINGUT" );
define( "ADMPREF_HEADER_3", "PREFERÈNCIES DE RÀDIO INTERNET" );
define( "ADMPREF_HEADER_4", "PREFERÈNCIES D'APARENÇA GLOBAL" );
define( "ADMPREF_HEADER_5", "PREFERÈNCIES DEL JUKEBOX (Reproducció en el servidor)" );

define( "ADMPREF_CAPTION", "El següent formulari controla el tipus de lletra i el color per defecte del lloc, així com l'opció de personalització per als usuaris. Aquests valors s'utilitzen per a la creació de nous comptes i per als usuaris anònims." );
define( "ADMPREF_PALETTE", "Utilitzeu aquesta paleta de colors per escollir l'entorn per defecte." );

define( "ADMPREF_FORMS_CAPT_ENABLED", "Actiu" );

define( "ADMPREF_FORMS_SAVETOFILE", "Desa" );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_1", "Per desar automàticament aquesta informació al fitxer de preferències, s'ha\\nde poder accedir al fitxer a través del servidor web. Aquí tenim dues \\nsolucions:\\n\\n- Al fitxer hi pot accedir tothom (no és recomanable).\\n\\n- El propietari del fitxer es pot canviar per l'usuari associat\\n amb el serivdor http (requereix un servidor root/login d'admin.).\\n\\nUna alternativa més segura és transferir manualment la informació\\npresentada en la següent pantalla sobre el fitxer de preferències del sistema /etc/inc-prefs.php." );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_2", "Avís de seguretat important" );

define( "ADMPREF_FORMS_SECMODE", "Mode de seguretat" );
define( "ADMPREF_FORMS_SECKEY", "Clau de seguretat" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_1", "MODES DE SEGURETAT:\\n0.0 = Contingut públic- Login actiu- Inscripcions públiques actives\\n0.1 = Contingut públic- Login actiu- Inscripcions públiques desactivades\\n0.2 = Contingut públic- Requereix aministrador- Inscripcions públiques desactivades\\n1.0 = Contingut privat- Login actiu- Inscripcions públiques actives\\n1.1 = Contingut privat- Login actiu - Inscripcions públiques desactivades\\n1.2 = Contingut privat- Requereix administrador- Inscripcions públiques desactivades\\n" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_2", "\\nCLAU DE SEGURETAT:\\nLa clau de seguretat s'utilitza per crear un valor segur per a les\\nsessions generades a\\l'entrada del lloc. Us generarà una clau per defecte quan\\n instal·leu o actualitzeu i es tornarà a generar cada vegada que actualitzeu el fitxer de preferències,\\nperò haurieu d'actualitzar aquest valor de tant en tant per a més seguretat amb el valor aleatori que escolliu\\nsuperior a 30 caràcters en el formulari següent. El valor pot ser qualsevol,\\ni no cal que el recordeu ja que no és una contrasenya.");
define( "ADMPREF_FORMS_SECMODE_HELP_2", "Definició dels modes i claus de seguretat." );

define( "ADMPREF_FORMS_DBTYPE", "Tipus de BD" );
define( "ADMPREF_FORMS_DBHOST", "Host de la BD" );
define( "ADMPREF_FORMS_DBUSER", "Usuari de la BD" );
define( "ADMPREF_FORMS_DBPASS", "Contrasenya de la BD" );
define( "ADMPREF_FORMS_DBNAME", "Nom de la BD" );

define( "ADMPREF_FORMS_STREAM", "Servidor de música" );
define( "ADMPREF_FORMS_MUSICDIR", "Directori de música" );

define( "ADMPREF_FORMS_PROTECTMEDIA", "Protecció dels fitxers audiovisuals" );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_1", "Si activeu aquesta opció, el Netjuke utilitza una eina que intenta\\nevitar les descàrregues no desitjades utilitzant el url mostrat al lector\\nd'àudio. Aquesta opció no es pot fer servir si no utilitzeu fitxers Ogg Vorbis." );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_2", "Definició de l'opció" );

define( "ADMPREF_FORMS_REALONLY", "Real Player" );
define( "ADMPREF_FORMS_REALONLY_HELP_1", "Si seleccioneu aquesta opció, limitará la lectura dels fitxers àudio a l'\\naplicació Real Player, que no mostra l'URL de l'arxiu.");
define( "ADMPREF_FORMS_REALONLY_HELP_2", "Definició de l\'opció" );
		 
define( "ADMPREF_FORMS_RADIO_HELP_1", "1 - Seleccioneu d'aquesta llista el tipus de servidor de ràdio que voleu fer servir (només en el cas\\nque vulgueu generar una llista per a una emissora de ràdio d'internet).\\n\\n2 - Introduïu el camí d'accés complet a l'arxiu de text de la llista de ràdio que voleu editar.\\n\\n3 - Opcionalment, podeu introduir la URL de l'stream de ràdio per mostrar l'enllaç \\\Radio\\\ a la barra d'eines.\\n\\nPer tal de permetre la utilització de diversos tipus de servidor de ràdio, el Netjuke no s'encarrega\\nde tota l'administració del servidor, sinó que sols formata i desa\\nles cançons que seleccioneu d'una llista que ja heu creat per complir amb els requisits\\ndel servidor. Haureu de (re)iniciar el servidor de l'stream mitjançant qualsevol\\ninterfície d'administració proporcionada pels desenvolupadors del servidor que heu triat\\n(Suggeriment: El QT/Darwin SS4 té una eina d'administració gratuïta basada en una pàgina web molt bona ;o)\\n\\nExtra: Si voleu gestionar més d'un stream des del Netjuke, seleccioneu\\namb el punter una llista fictícia, i moveu-la manualment a la ubicació\\ncorresponent després d'editar-la mitjançant el Netjuke (En aquest cas, no es recomana utilitzar\\nl'enllaç Radio, ja que sols ens pot enllaçar amb un stream).\\n\\nVegeu els paràgrafs INTERNET RADIO STREAM SERVER INTEGRATION al docs/MAINTAIN.txt." );
define( "ADMPREF_FORMS_RADIO_HELP_2", "Ajuda de configuracions" );
define( "ADMPREF_FORMS_RADIOTYPE", "Tipus de servidor Ràdio" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_1", "Cap" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_2", "Apple Quicktime/Darwin SS4" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_3", "ModMP3, Ices, WinAmp, etc." );
define( "ADMPREF_FORMS_RADIOURL", "URL de la ràdio" );
define( "ADMPREF_FORMS_RADIOPLIST", "Llista de ràdio" );

define( "ADMPREF_FORMS_JUKEBOX_HELP_1", "1 - Seleccioneu el tipus de reproductor d'àudio que voleu utilitzar al servidor (només\\nsi voleu fer servir un reproductor al servidor remot d'aquest Netjuke).\\n\\n2 - Introduïu el camí d'accés complet al reproductor que hi ha al servidor\\n(ex.: /usr/bin/mpg123 o C:\\\Program Files\\\Winamp\\\Winamp.exe).\\n\\n3 - Introduïu el camí d'accés complet de la llista del fitxer de text del jukebox que voleu editar.\\n\\nL'opció Jukebox del Netjuke permet la generació i la reproducció\\nde les llistes des del servidor (l'ordinador que executa el Netjuke). Aquesta \\nopció està pensada per als usuaris que vulguin reproduir la música des d'una màquina diferent\\nd'aquella des d'on han accedit al Netjuke. El conjunt d'opcions\\nés bastant limitat a causa de les característiques multiplataforma del Netjuke.\\nSi voleu tenir més control sobre el reproductor remot i gaudir de millors opcions, podeu\\najudar-nos a integrar nous reproductors o a millorar el codi, així com\\nverificar una de les altres aplicacions dissenyades per a aquesta tasca.\\nL'objectiu principal del Netjuke és l'streaming.\\n\\nPer obtenir més informació sobre com configurar el reproductor, etc., vegeu els paràgrafs JUKEBOX FEATURE: SERVER-SIDE PLAYBACK INTEGRATION al\\ndocs/INSTALL.txt" );
define( "ADMPREF_FORMS_JUKEBOX_HELP_2", "Ajuda per configurar el Jukebox" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER", "Tipus de reproductor" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION", "Cap" );
define( "ADMPREF_FORMS_JUKEBOXPLAYERPATH", "Camí d'accés al reproductor" );
define( "ADMPREF_FORMS_JUKEBOXPLIST", "Llista del Jukebox" );


define( "ADMPREF_FORMS_HTMLHEAD", "Capçalera HTML" );
define( "ADMPREF_FORMS_HTMLFOOT", "Peu de pàgina HTML" );

define( "ADMPREF_FORMS_ENABLECOMM", "Comunitat" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_1", "- Eines de navegació\\n- Secció comunitària\\n- Llistes de cançons compartides\\n" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_2", "Opcions afectades" );

define( "ADMPREF_FORMS_ENABLEDLOAD", "Descàrrega de fitxers" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_1", "Si aquesta opció està seleccionada, s'activarà un nou botó per a cada pista\\nperquè els usuaris puguin descarregar els fitxers àudio.\\n" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_2", "Definició de l'opció" );

define( "ADMPREF_FORMS_RESPERPAGE_1", "Limita els resultats a" );
define( "ADMPREF_FORMS_RESPERPAGE_2", "elements per pàgina (quan siguin disponibles)" );

define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS", "Comptador de pistes" );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_1", "Aquesta opció permet contar el nombre total de pistes per al valor associat(intèrpret, àlbum\\o gènere)a la pàgina del navegador i les llistes alfabètiques.\\n\\nSi us plau, tingueu en compte que aquesta opció pot ralentir el servidor perquè aquests comptes\\nrequereixen nombroses connexions a la taula més pesada de la base de dades.\\nS'aconsella utilitzar aquesta opció només amb un servidor molt ràpid dedicat al Netjuke." );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_2", "Definició de l'opció" );

define( "ADMPREF_FORMS_LANGPACK", "Idioma" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_1", "Anglès" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_2", "Francès" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_3", "Alemany" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_4", "Català" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_5", "Espanyol" );

define( "ADMPREF_FORMS_THEMES", "Temes dels usuaris" );
define( "ADMPREF_FORMS_THEMES_HELP", "Permet als usuaris escollir els seus propis colors i tipografies utilitzades per l'aplicació" );

define( "ADMPREF_FORMS_INVICN", "Invertir les icones" );
define( "ADMPREF_FORMS_INVICN_HELP", "Permet a l'usuari invertir el color de les icones: Reproducció, Informació, Filtre..." );

define( "ADMPREF_FORMS_FONTFACE", "Llista de tipografies" );
define( "ADMPREF_FORMS_FONTSIZE", "Mida de tipografies" );
define( "ADMPREF_FORMS_BGCOLOR", "Color de fons" );
define( "ADMPREF_FORMS_TEXT", "Color del text" );
define( "ADMPREF_FORMS_LINK", "Color de l'enllaç" );
define( "ADMPREF_FORMS_ALINK", "Color dels enllaços actius" );
define( "ADMPREF_FORMS_VLINK", "Color dels enllaços visitats" );
define( "ADMPREF_FORMS_BORDER", "Color de les vores" );
define( "ADMPREF_FORMS_HEADER", "Color de la capçalera" );
define( "ADMPREF_FORMS_HEADERFC", "Color del text de la capçalera" );
define( "ADMPREF_FORMS_CONTENT", "Color del fons del contingut" );

define( "ADMPREF_FORMS_BTN_SAVE", "Desa" );
define( "ADMPREF_FORMS_BTN_RESET", "Desfés canvis" );

##################################################

?>