<?PHP

/*
        |: MWChat (My Web based Chat)
        |: Web\HTTP based chat application
        |:
        |: Copyright (C) 2000, 2001, 2002, 2003
        |: Distributed under the terms of license provided.
        |: Available at http://www.appindex.net
        |: Authored by Appindex.net - <support@appindex.net>
*/


/* 

   This option enables support for multiple languages. MWChat has dynamic language support.
   That means that any translations available for MWChat are seemlessly available. Do remember
   that this feature ONLY translates MWChat's text and internal messaging, it will NOT 
   translate end user conversations into your preferred language.

*/

$CONFIG[Locales] = "true";


/* 

   The language types setting lets you specify which languages are available. Usually this setting 
   need not be adjusted. MWChat developers will update this as new translations are available. If you 
   wish to add a translation, read the TRANSLATIONS file located in the docs/ directory. You can make
   any language unaccessable to end users by removing it from this list. 

*/

                    
$CONFIG[Locales_Types] = array(
                                  
                                "en:English",
                                "es:Spanish",
                                "fr:French",
                                "ge:German",
                                "it:Italian",
                                "nr:Norwegian",
                                "pg:Portuguese",
                                "ru:Russian"

                                 );


/* 

   This option allows you to choose a default language for the MWChat system. This abbreviation is derrived 
   from the setting above, everything before the colon. So if I wanted spanish as the default language, 
   I would use "es". Remember that only MWChat's text will be available in the language you choose. 

*/

$CONFIG[Locales_Default] = "en";

?>
