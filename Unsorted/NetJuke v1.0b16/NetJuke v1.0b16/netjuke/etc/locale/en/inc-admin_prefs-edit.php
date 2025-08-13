<?php

##################################################

# /admin/prefs-edit.php

##################################################

define( "ADMPREF_ERR_DBCONN", "Cannot connect to the database using the provided information." );

define( "ADMPREF_ERR_RADIOPLIST", "The directory or file used for the RADIO playlist cannot be found or is not writable.\\nPlease make it writable by the web server user, or everybody." );

define( "ADMPREF_ERR_JUKEBOXPLIST", "The directory or file used for the JUKEBOX playlist cannot be found or is not writable.\\nPlease make it writable by the web server user, or everybody." );
define( "ADMPREF_ERR_JUKEBOXPLAYERPATH", "Jukebox Player not found. Please revise the Player Path field." );

define( "ADMPREF_FILEINFO_1", "Created" );
define( "ADMPREF_FILEINFO_2", "From" );
define( "ADMPREF_FILEINFO_3", "Save As" );

define( "ADMPREF_DENIED_1", "Cannot Write To Your Preference File. Permissions Error." );

define( "ADMPREF_CHECKFORM_SECKEY", "Your new Security Key must be at least 30 characters long to be updated" );
define( "ADMPREF_CHECKFORM_DBNAME", "Please enter a DB name" );
define( "ADMPREF_CHECKFORM_STREAM", "Please enter a Streaming Server" );
define( "ADMPREF_CHECKFORM_BGCOLOR", "Please choose a Background color." );
define( "ADMPREF_CHECKFORM_FONTFACE", "Please choose a list of Fonts." );
define( "ADMPREF_CHECKFORM_FONTSIZE", "Please choose a Font Size." );
define( "ADMPREF_CHECKFORM_TEXT", "Please choose a Text color." );
define( "ADMPREF_CHECKFORM_LINK", "Please choose a Link color." );
define( "ADMPREF_CHECKFORM_ALINK", "Please choose an Active Link color." );
define( "ADMPREF_CHECKFORM_VLINK", "Please choose a Visited Link color." );
define( "ADMPREF_CHECKFORM_BORDER", "Please choose a Table Border color." );
define( "ADMPREF_CHECKFORM_HEADER", "Please choose a Table Header color." );
define( "ADMPREF_CHECKFORM_HEADERFC", "Please choose a Table Header font color." );
define( "ADMPREF_CHECKFORM_CONTENT", "Please choose a Table Content color." );

define( "ADMPREF_HEADER_1", "SYSTEM PREFERENCES" );
define( "ADMPREF_HEADER_2", "CONTENT PREFERENCES" );
define( "ADMPREF_HEADER_3", "INTERNET RADIO PREFERENCES" );
define( "ADMPREF_HEADER_4", "GLOBAL APPEARANCE PREFERENCES" );
define( "ADMPREF_HEADER_5", "JUKEBOX PREFERENCES (Server-Side Playback)" );

define( "ADMPREF_CAPTION", "The form below controls the site's default font and color theme, and the option to let users have custom themes. This is what users will see in public mode, as well as their default settings when an account is first created." );
define( "ADMPREF_PALETTE", "Use this color palette to choose a default environment." );

define( "ADMPREF_FORMS_CAPT_ENABLED", "Enabled" );

define( "ADMPREF_FORMS_SAVETOFILE", "Save To File" );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_1", "To automatically save this information to you preference file, the\\nfile must be writable by the web server. To do so, there are two\\nsolutions:\\n\\n- The file can be writable by everyone (not recommended).\\n\\n- The file owner can be set to be the user associated with the web\\n server software (usually requires a server root/admin login).\\n\\nThe alternate, but secure way is to simply cut-and-paste the information\\npresented in the next screen to the system preference file /etc/inc-prefs.php." );
define( "ADMPREF_FORMS_SAVETOFILE_HELP_2", "Important Security Note" );

define( "ADMPREF_FORMS_SECMODE", "Security Mode" );
define( "ADMPREF_FORMS_SECKEY", "Security Key" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_1", "SECURITY MODES:\\n0.0 = Public content - Login enabled - Public registration enabled\\n0.1 = Public content - Login enabled - Public registration disabled\\n0.2 = Public content - Admin Login required - Public registration disabled\\n1.0 = Private content - Login enabled - Public registration enabled\\n1.1 = Private content - Login enabled - Public registration disabled\\n1.2 = Private content - Admin Login required - Public registration disabled\\n" );
define( "ADMPREF_FORMS_SECMODE_HELP_1_2", "\\nSECURITY KEY:\\nThe security key is used as a secure random seed to generate the unique session\\nids needed by the netjuke at login time. A default key is generated for you upon\\ninstall and/or upgrade, and is re-generated every time you update your config file,\\nbut you should thoroughly update this value from time to time by entering a custom\\nstring of more than 30 characters in the form field below. The string can be anything,\\nand you will not be required to remember it in any way (This is not a password)." );
define( "ADMPREF_FORMS_SECMODE_HELP_2", "Security Mode & Key Definitions" );

define( "ADMPREF_FORMS_DBTYPE", "DB Type" );
define( "ADMPREF_FORMS_DBHOST", "DB Host" );
define( "ADMPREF_FORMS_DBUSER", "DB User" );
define( "ADMPREF_FORMS_DBPASS", "DB Password" );
define( "ADMPREF_FORMS_DBNAME", "DB Name" );

define( "ADMPREF_FORMS_STREAM", "Music Server" );
define( "ADMPREF_FORMS_MUSICDIR", "Music Directory" );

define( "ADMPREF_FORMS_PROTECTMEDIA", "Protect Media" );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_1", "Enabling this feature will use a built-in media proxy which tries\\nto stop unwanted downloads using the url displayed in the audio\\nplayer.Sorry, but this MUST be turned off if you play Ogg Vorbis files." );
define( "ADMPREF_FORMS_PROTECTMEDIA_HELP_2", "Feature Definition" );

define( "ADMPREF_FORMS_REALONLY", "Real Player" );
define( "ADMPREF_FORMS_REALONLY_HELP_1", "Enabling this feature limits the audio streaming to the\\nReal Player application, which does not to show the file URL." );
define( "ADMPREF_FORMS_REALONLY_HELP_2", "Feature Definition" );

define( "ADMPREF_FORMS_RADIO_HELP_1", "1 - Choose the type of radio server you want to use from the list (only required\\nif you want to generate playlist to run an internet radio station).\\n\\n2 - Enter the full filesystem path to the radio playlist text file you want to edit.\\n\\n3 - Optionally enter the Radio Stream's URL to display a \\\"Radio\\\" link in the toolbar.\\n\\nTo be able to support multiple radio server types, the netjuke does not attempt\\nto completely administer the server itself. The net juke will only format and save\\nthe tracks you select to a playlist you will already have created to satisfy your server\\nrequirements, and you will need to (re)start your stream server using whatever\\nadministration interface provided by the developers of the server you selected\\n(Hint: The QT/Darwin SS4 has an excellent free web-based admin tool ;o)\\n\\nExtra: If you want to manage more than one Radio stream from the netjuke, just\\npoint to a dummy playlist somewhere, and move it manually in the appropriate\\nlocation after editing it through the netjuke (I would not use the Radio link in\\nthis context as you can only link to one stream from it).\\n\\nSee INTERNET RADIO STREAM SERVER INTEGRATION paragraphs in docs/MAINTAIN.txt." );
define( "ADMPREF_FORMS_RADIO_HELP_2", "Radio Setup Help" );
define( "ADMPREF_FORMS_RADIOTYPE", "Radio Server Type" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_1", "None" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_2", "Apple Quicktime/Darwin SS4" );
define( "ADMPREF_FORMS_RADIOTYPE_CAPTION_3", "ModMP3, Ices, WinAmp, etc." );
define( "ADMPREF_FORMS_RADIOURL", "Radio Stream URL" );
define( "ADMPREF_FORMS_RADIOPLIST", "Radio Playlist" );

define( "ADMPREF_FORMS_JUKEBOX_HELP_1", "1 - Choose the type of audio player you want to use on the server (only required\\nif you want to use audio playback on the remote server running this netjuke).\\n\\n2 - Enter the full filesystem path to the player software on the server\\n(eg: /usr/bin/mpg123 or C:\\\Program Files\\\Winamp\\\Winamp.exe).\\n\\n3 - Enter the full filesystem path to the jukebox playlist text file you want to edit.\\n\\nThe jukebox feature of the netjuke allows for the generation and playback\\nof playlists on the server-side (the computer running the netjuke). This\\nis mainly meant for people who want to play the music on another machine\\nthan the one they are accessing the netjuke from. The feature set is at\\nthis time quite limited because of the cross-platform goals of the netjuke.\\nIf you want more control over the remote player, and better features, join\\nus and help out integrating new players or upgrade the code, or you can\\nalso check out one the the other apps that specialize on this very task.\\nThe netjuke principal focus is streaming.\\n\\nSee JUKEBOX FEATURE: SERVER-SIDE PLAYBACK INTEGRATION paragraphs in\\ndocs/INSTALL.txt for more info on how to setup your player, etc." );
define( "ADMPREF_FORMS_JUKEBOX_HELP_2", "Jukebox Setup Help" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER", "Player Type" );
define( "ADMPREF_FORMS_JUKEBOXPLAYER_CAPTION", "None" );
define( "ADMPREF_FORMS_JUKEBOXPLAYERPATH", "Player Path" );
define( "ADMPREF_FORMS_JUKEBOXPLIST", "Jukebox Playlist" );

define( "ADMPREF_FORMS_HTMLHEAD", "HTML Header" );
define( "ADMPREF_FORMS_HTMLFOOT", "HTML Footer" );

define( "ADMPREF_FORMS_ENABLECOMM", "Community" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_1", "- Primary Navigation Toolbar\\n- Community Section\\n- Shared Playlist Features\\n" );
define( "ADMPREF_FORMS_ENABLECOMM_HELP_2", "Affected Features" );

define( "ADMPREF_FORMS_ENABLEDLOAD", "File Download" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_1", "If enabled, a new icon will show up in the track listings so\\nthat users can download a file instead of streaming it.\\n" );
define( "ADMPREF_FORMS_ENABLEDLOAD_HELP_2", "Feature Definition" );

define( "ADMPREF_FORMS_RESPERPAGE_1", "Limit results to " );
define( "ADMPREF_FORMS_RESPERPAGE_2", "items per page where available" );

define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS", "Display Track Counts" );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_1", "This feature displays the total number of tracks for the related value (artist, album\\nor genre) on the Browse page and the Alphabetical Listings.\\n\\nPlease note that this option can bring your server to a crawl because these counts\\nrequire a tremendous amount of connection to the heaviest table in the database.\\nOnly use this feature if you run the Netjuke on an extremely fast dedicated server." );
define( "ADMPREF_FORMS_DISPLAY_TRCOUNTS_HELP_2", "Feature Definition" );

define( "ADMPREF_FORMS_LANGPACK", "Language" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_1", "English" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_2", "French" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_3", "German" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_4", "Catalan" );
define( "ADMPREF_FORMS_LANGPACK_CAPTION_5", "Spanish" );

define( "ADMPREF_FORMS_THEMES", "User Themes" );
define( "ADMPREF_FORMS_THEMES_HELP", "Enables users to create their own color/font themes once logged in" );

define( "ADMPREF_FORMS_INVICN", "Inverse Icons" );
define( "ADMPREF_FORMS_INVICN_HELP", "Enables users to inverse the color of the icons: Play, Get Info, Filter..." );

define( "ADMPREF_FORMS_FONTFACE", "Font Face" );
define( "ADMPREF_FORMS_FONTSIZE", "Font Size" );
define( "ADMPREF_FORMS_BGCOLOR", "Background Color" );
define( "ADMPREF_FORMS_TEXT", "Text Color" );
define( "ADMPREF_FORMS_LINK", "Links Color" );
define( "ADMPREF_FORMS_ALINK", "Active Links Color" );
define( "ADMPREF_FORMS_VLINK", "Visited Links Color" );
define( "ADMPREF_FORMS_BORDER", "Border Color" );
define( "ADMPREF_FORMS_HEADER", "Header Color" );
define( "ADMPREF_FORMS_HEADERFC", "Header Font Color" );
define( "ADMPREF_FORMS_CONTENT", "Content Color" );

define( "ADMPREF_FORMS_BTN_SAVE", "Save" );
define( "ADMPREF_FORMS_BTN_RESET", "Reset" );

##################################################

?>