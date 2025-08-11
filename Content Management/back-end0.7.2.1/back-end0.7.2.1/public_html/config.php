<?php
   /**
    * @package     Back-End
    * @version     0.7 $Id: config.php,v 1.61 2005/06/16 19:32:58 mgifford Exp $
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    *
    * This file is part of Back-End.
    *
    * Back-End is free software; you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation; either version 2 of the License, or
    * (at your option) any later version.
    *
    * Back-End is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with Back-End; if not, write to the Free Software
    * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    */

   // Specify full path to ini file
   $psl_inifile = 'config.ini.php';

   // end of required configuration.  config.ini.php contains the phpSlash
   // configuration variables.

   // This is a comment, it spans one line

   #  This is a comment also, it spans one line but we do not like these

   /* This is a comment as well, it can span multiple lines
    like this, but you need to be sure to close it like so: */

   // Swaping the order for jpcache.  Too many variables to redefine
   $_PSL = array();

   // parse the ini configuration file into the $_PSL array
   // Specify full path to ini file
   $_PSL = @parse_ini_file($psl_inifile, TRUE);

   // Make sure that there is a default cacheExpiryTime
   $_PSL['cacheExpiryTime'] = (isset($_PSL['cacheExpiryTime'])) ? $_PSL['cacheExpiryTime'] : 86400;
   $_PSL['filecache.enable'] = !isset($_PSL['filecache.enable']) || $_PSL['filecache.enable'];

   // To run profiler class
   // include_once($_PSL['classdir'] . '/solace.profiler.class.php');
   // declare(ticks=1);

   // for migration to php5
   // ini_set('zend.ze1_compatibility_mode', 1);

   // *******************************
   // **** BEGIN: BACK-END PATCH ****

   // BEGIN - Config crutch for new users
   // This can be turned off at the config.ini.php or deleted
   if ((empty($_PSL['rootdomain']) || @$_PSL['newconfig']) && !strstr($_SERVER['PHP_SELF'], 'test.php')) {
      // New Install = true ; Tested config.ini.php = false

      /**
      * @return string
      * @access private
      */
      function _removeTrailingSlash($string) {
          return preg_replace('/\/\s*$/', '', $string);
      }

      function _configMsg($string) {
         $goToConfigMsg = '<p>You may choose to proceed to the <a href="config_setup.php">configurator</a> or edit your config.ini.php file manually.</p>';

         echo "<html>
         <head>
         <style><!--
         body {   margin: 0px; padding: 0px; font-family: arial, geneva, helvetica, sans-serif; color: #000; background-color: #E4E4D2; text-align: center}
         --></style>
         </head>
         <body>\n";
         echo '<img src="./images/logo1.gif" alt="Back-End Message">';
         echo "\n<p>$string</p>\n";
         echo $goToConfigMsg;
         echo "\n</body></html>\n";
         exit();
      }

      if (is_readable($psl_inifile)) {
         if (filesize($psl_inifile) > 0) {
            if (count($_PSL) == 0) {
               _configMsg(sprintf('The configuration file (%s) not properly defined.',  $psl_inifile));
            }
         } else {
            _configMsg(sprintf('The configuration file (%s) is empty.', $psl_inifile));
         }
      } else {
         _configMsg(sprintf('The configuration file (%s) does not appear to be initialized.', $psl_inifile));
      }

      // Checks for missing rooturl
      if (empty($_PSL['rooturl']) && (empty($_PSL['rootdomain']) || $_PSL['rootdomain'] == 'be.ca')) {
         $subdir = _removeTrailingSlash($_SERVER['SCRIPT_NAME']);
         $subdir = explode('/',dirname($_SERVER['SCRIPT_NAME']));
         $numberOfSubDirectories = count($subdir);
         for ($i=0 ; $i < $numberOfSubDirectories ; ++$i) {
            $publicSubdirArray[$i] = $subdir[$i];
         }
         $publicSubdirVar  = implode('/', $publicSubdirArray);
         $_PSL['rooturl'] = _removeTrailingSlash(str_replace('/admin', '', $publicSubdirVar));
      }

      // Check for missing rootdomain
      $_PSL['rootdomain'] = (!empty($_PSL['rootdomain'])) ? $_PSL['rootdomain'] : _removeTrailingSlash($_SERVER['HTTP_HOST']);

      // Check for missing subdomain
      $_PSL['rootsubdomain'] = (!empty($_PSL['rootsubdomain'])) ? $_PSL['rootsubdomain'] : '';

      if (empty($_PSL['absoluteurl'])) {
         if (!empty($_PSL['rooturl']) && eregi('http://', $_PSL['rooturl'])) {
            $_PSL['absoluteurl'] = $_PSL['rooturl'];
         } else {
            $_PSL['absoluteurl'] = 'http://' . $_PSL['rootsubdomain'] . $_PSL['rootdomain'] . $_PSL['rooturl'];
         }
      }

      // check if classdir is defined correctly
      if (!isset($_PSL['classdir']) || !is_file($_PSL['classdir'] . '/functions.inc')) {

         // Try to find default class directory
         $path = explode('/',dirname($_SERVER['SCRIPT_FILENAME']));
         $numberOfSubDirectories = count($path);
         for ($i=0 ; $i < $numberOfSubDirectories ; ++$i) {
            $publicPathArray[$i] = $path[$i];
            if (($i+1) < $numberOfSubDirectories) {
               $basePathArray[$i] = $path[$i];
            }
         }

         $publicPathVar  = implode('/', $publicPathArray);
         $publicPathVar = preg_replace('/\/\s*$/', '', $publicPathVar);

         $basePathVar  = implode('/', $basePathArray);
         $basePathVar = preg_replace('/\/\s*$/', '', $basePathVar);

         if (!is_file($basePathVar . '/class/functions.inc')) {
            _configMsg(sprintf('Back-End cannnot find class directory %s', $basePathVar));
         } elseif(!isset($_PSL['classdir'])) {
            _configMsg('You have not defined your class directory in your config.ini.php file.');
         } else {
            _configMsg(sprintf('Variable classdir is incorrect defined in config.ini.php as "%s", please change to "%s/class".', $psl_inifile, $basePathVar));
         }

         if (!is_file($_PSL['basedir'] . '/config.php')) {
            $_PSL['basedir'] = $publicPathVar;
         }

      }

      // Check if the template basedir is defined correctly
      if (!(isset($_PSL['templatedir']) && $_PSL['templatedir']) && !is_dir($_PSL['basedir'] . '/templates')) {
         _configMsg('The template directory does not seem to be in the default location "' . $_PSL['basedir'] . '/templates". Please check that it exists and is readable.');
      } elseif(isset($_PSL['templatedir']) && $_PSL['templatedir'] && !is_dir($_PSL['templatedir'])) {
         $msg = 'The template directory in the config.ini.php is incorrectly defined as "' . $_PSL['templatedir'] . '".';

         if (!is_dir($_PSL['basedir'] . '/templates')) {
            $msg .= ' Please check that it exists and is readable.';
         } else {
            $msg .= ' You may want to change the templatedir reference to "' . $_PSL['basedir'] . '/templates' . '".';
         }
         _configMsg($msg);
      }

      // Check if database is connected
      $dbCheck = mysql_connect($_PSL['DB_Host'], $_PSL['DB_User'], $_PSL['DB_Password']);
      $result = mysql_list_tables($_PSL['DB_Database']);
      if (!$result) {
         _configMsg('Back-End cannnot connect to the defined database host ' . $_PSL['DB_Host'] . ' or database ' . $_PSL['DB_Database'] . ' or there is a problem with your username ' . $_PSL['DB_User'] . ' or password.');
      }

      // Check if tables are set up
      $tableNameArray = array();
      for ($i = 0; $i < mysql_num_rows($result); ++$i) {
         $tableNameArray[] = mysql_tablename($result, $i);
      }
      mysql_free_result($result);
      mysql_close($dbCheck);
      if (!in_array('db_sequence', $tableNameArray)) {
         _configMsg('Back-End cannnot critical tables in the database ' . $_PSL['DB_Database'] . '. ');
      }

      // Check php version
      if (strncmp(phpversion(), '4.1', 3) < 0) {
         die('You need at least PHP4.1 to run Back-End');
      }

   }
   // END - Config crutch for new users

   if(isset($_GET['debug.templates']) && $_GET['debug.templates']) {
      $_PSL['debug.templates'] = true;
   }

   // Uncomment this next line if you have problems with sessions - required with sf.net hosting
   // $sessionVar = session_save_path($_PSL['basedir'] . '/updir/');

   // If you get an error in test.php for magic_quotes_gpc, uncomment the following line:
   // ini_set('magic_quotes_gpc', '');

   // If slashSess appears in the url, and cookies are working,
   // you can set the php_admin_flag session.use_trans_sid off
   // in php.ini or virtual host / directory or .htaccess
   // The following works for PHP 4.1.2, does not work in 4.3+, others?
   ini_set('session.use_trans_sid', 0);

   // **** END: BACK-END PATCH ****
   // *******************************

   // PSL Version - BE Version number defined in BE_config.php
   $_PSL['version'] = '0.7.1-2';

   /**** START DEBUGGING - Comment or delete this for production! ****/
#   ini_set('error_reporting', E_ALL);
#   ini_set('error_reporting', E_ALL~E_NOTICE);
#   ini_set('error_reporting', E_ALL~E_NOTICE~E_WARNING);
#   error_reporting(E_NOTICE | E_WARNING | E_PARSE | E_ERROR);
   /**** END DEBUGGING ****/


   //////////////////////////////////////////////////////////////////////////
   // SECTION 1 - REQUIRED FILES --------------------------------------------
   //////////////////////////////////////////////////////////////////////////

   // 1.1 Comment this if you can't use a local php.ini or .htaccess

   $_PHPLIB = array();
   $_PHPLIB['libdir'] = (isset($_PSL['phplibdir']) && !empty($_PSL['phplibdir'])) ? $_PSL['phplibdir'] : $_PSL['classdir'] . '/phplib/php/';

   // Include PEAR in Back-End's search path
   // Taken from Seagull 0.3.11 code - seagull.phpkitchen.com
   $_PEARDIR = (!empty($_PSL['peardir'])) ? $_PSL['peardir'] : $_PSL['classdir'] . '/pear';
   if (!empty($_PEARDIR)) {
      $includeSeparator = (substr(PHP_OS, 0, 3) == 'WIN') ? ';' : ':';

      $allowed = @ini_set('include_path',
            ini_get('include_path') . $includeSeparator . $_PEARDIR);
      if (!$allowed) { // We're in safemode
         //  depends on PHP version being >= 4.3.0
         if (function_exists('set_include_path')) {
            set_include_path(ini_get('include_path') .
                              $includeSeparator . $_PEARDIR);
         } else {
            debug('Unable to add PEAR to include path'.$_PEARDIR);
         }
      }
   }

   // 1.11) Global Variables.  (Don't add trailing slashes)

   // 1.11.1) Paths to important files phpSlash needs to operate

   /***************************************************
    *
    *  NOTE THE NEW NAMES like adminurl vs. basedir.
    *   blahurl are for http://... type paths while
    *   blahdir are used for /home/httpd/... paths.
    * --this is mainly a note to Ajay from Ajay.
    *
    ***************************************************/

#   $_PSL['phpself'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
   $_PSL['phpself'] = $_SERVER['PHP_SELF'];

   // Setting absolute URL
   if (empty($_PSL['absoluteurl'])) {
      $_PSL['absoluteurl'] = 'http://' . @$_PSL['rootsubdomain'] . $_PSL['rootdomain'] . $_PSL['rooturl'];
   }
   // Set null rootsubdomain if not set
   if (empty($_PSL['rootsubdomain'])) {
      $_PSL['rootsubdomain'] = '';
   }
   // $templatedir->Path to the templates directory
   if (empty($_PSL['templatedir'])) {
      $_PSL['templatedir'] = $_PSL['basedir'] . '/templates';
   }
   // $adminurl->The base URL for the admin pages
   if (empty($_PSL['adminurl'])) {
      $_PSL['adminurl'] = $_PSL['rooturl'] . '/admin';
   }
   // $imageurl->The base URL for images that pertain to phpslash
   if (empty($_PSL['imageurl'])) {
      $_PSL['imageurl'] = $_PSL['rooturl'] . '/images';
   }
   // topicimageurl->the url for all the topic images.
   if (empty($_PSL['topicimageurl'])) {
      $_PSL['topicimageurl'] = $_PSL['imageurl'] . '/topics';
   }
   // topicimagedir->the full directory path to the topic images.
   if (empty($_PSL['topicimagedir'])) {
      $_PSL['topicimagedir'] = $_PSL['basedir'] . '/images/topics';
   }
   // localedir->the full directory path to the language files
   if (empty($_PSL['localedir'])) {
      $_PSL['localedir'] = $_PSL['classdir'] . '/locale';
   }

   // 1.3) PHPSlash functions library
   require_once($_PSL['classdir'] . '/functions.inc');
   require_once($_PSL['classdir'] . '/lib.resources.php');

   /* now that the functions are in, we can define the arg_separator */
   $_PSL['amp'] = arg_separator('1');

   // 1.99) Add any other require()'s or include()'s you need here and they
   //       should  be prepended to all phpSlash pages

   // comment to enable setting lang in url
   $lang = false;

   $_PSL['languagefile'] = $_PSL['localedir'] . '/' . $_PSL['language'] . '.php';

   // comment to disable auto lang detection
   $_PSL['languagefile'] = setLang($lang);

   // comment to disable auto tpl lang detection
   $_PSL['templatedir'] = setLangTpl($lang);

   // comment out this block to disable setting theme in url
   if (!empty($_GET['skin'])) {
      // TODO Clear the cache!
      // Needs to take place perhaps in BE_config.php needs to wipe most everything
      if (empty($_GET['nocookie'])) {
         $_PSL['templatedir'] = setSkinTpl(clean($_GET['skin']), 'cookie');
      } else {
         $_PSL['templatedir'] = setSkinTpl(clean($_GET['skin']), '');
      }
   } else {
      $_PSL['templatedir'] = setSkinTpl('', '');
   }

   /**
    * Class information.  Add any class replacements like this:
    *
    * AddClassReplacement('template','myTemplate');
    *
    * This will make PHPSlash use your class instead of its defaults.
    *
    * First we load the PHPLIB basic classes.  This next block of code does
    * the work of $_PHPLIB['libdir'] . 'prepend.php', so make sure that
    * file's not processed automatically.
    **/

    require_once($_PHPLIB['libdir'] .'db_mysql.inc');
    require_once($_PHPLIB['libdir'] .'session4.inc');
    require_once($_PHPLIB['libdir'] .'auth4.inc');
    require_once($_PHPLIB['libdir'] .'perm.inc');
    require_once($_PHPLIB['libdir'] .'page4.inc');

    require_once($_PHPLIB['libdir'] .'template.inc');

   /**
    * These classes are PHPSlash's extensions to the PHPLIB base classes.
    * We only configure the ones which are required to get the page open
    * and restore session data.
    **/

    require_once($_PSL['classdir'] . '/slashDB.class');
    require_once($_PSL['classdir'] . '/BE_DB.class');
    require_once($_PSL['classdir'] . '/slashSess.class');
    require_once($_PSL['classdir'] . '/slashAuthCR.class');
    require_once($_PSL['classdir'] . '/slashPerm.class');
    require_once($_PSL['classdir'] . '/slashTemplate.class');

   /**
    * this necessity will change when the entire resource hash becomes
    * sessionized.
    */


   // *******************************
   // **** BEGIN: BACK-END PATCH ****

   // Login info was being cached on other pages - mg:not sure if this will resolve the problem
   if (!empty($_GET['login'])) {
      $cachetimeout = -1;
   }

   // ***** END: BACK-END PATCH *****
   // *******************************


   // 1.11.4) Other Variables...

   // censorfile->the full directory path to the censor file
   if (empty($_PSL['censorfile'])) {
      $_PSL['censorfile'] = $_PSL['basedir'] . '/censor.php'; //comment to disable comment censor
   }

   // 1.2)  Other PHPLIB things not already included.  Comment out if this
   //       is something you've already dealt with in prepend.php

   /**
    * $ary is used by story::getStory and block_i::getBlocks
    * this block just merges in the default section
    */
   $ary = array();
   if (!empty($_GET)) {
      $ary = clean($_GET);
   }

   if ((empty($ary['section'])) AND (empty($ary['section_id']))) {
      $section_id = $_PSL['home_section_id'];
      if (!$section_id) {
         $section_id = '3';
      }
      $ary['section_id'] = $section_id;
   }


   // NavBar Menu Definitions

   $menuitem = array();

   /*
    *  menuitem array legend:
    *
    *  name - The text displayed in the NavBar menu.  This text is checked in the
    *         languagefile.  To customize, change the text in the appropriate
    *         languagefile to avoid translation problems.
    *  link - url for menu item link.
    *  perm - permission require to view this menu item.
    *         Note inverted logic:
    *          - 'nobody' is for all users, '' to restrict item to users who are not logged in
    *  module - module require for viewing this menu item. '' (blank) for all.
    *
    *  To add navbar menu items, add a complete entry below.  Translation will be
    *  attempted automatically.
    */
/*
   $menuitem[] = array(
   'name' => 'Login',
      'link' => $_PSL['absoluteurl'] . '/login.php',
      'perm' => '',
      'module' => '',
      'group' => 'user',
      'order' => 1);

   $menuitem[] = array(
   'name' => 'Logout',
      'link' => $_PSL['absoluteurl'] . '/login.php?logout=yes&amp;redirect=' . urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']),
      'perm' => 'user',
      'module' => '',
      'group' => 'user',
      'order' => 1);
*/
   $menuitem[] = array(
      'name' => 'Home',
      'link' => $_PSL['rooturl'] . '/' . $_PSL['mainpage'],
      'perm' => 'nobody',
      'module' => '',
      'group' => 'content',
      'order' => 9
   );
   $menuitem[] = array(
      'name' => 'Glossary',
      'link' => $_PSL['rooturl'] . '/glossary.php',
      'perm' => 'nobody',
      'module' => 'Glossary',
      'group' => 'content',
      'order' => 28
   );
   $menuitem[] = array(
      'name' => 'Search',
      'link' => $_PSL['rooturl'] . '/search.php',
      'perm' => 'nobody',
      'module' => '',
      'group' => 'content',
      'order' => 22
   );
   $menuitem[] = array(
      'name' => 'Polls',
      'link' => $_PSL['rooturl'] . '/poll.php',
      'perm' => 'nobody',
      'module' => 'Poll',
      'group' => 'action',
      'order' => 27
   );

   $_PSL['menuitem'] = $menuitem; // add default menuitems to global array

   $menuitem = array();

   $menuitem[] = array(
      'name' => 'My Preferences',
      'link' => $_PSL['rooturl'] . '/profile.php',
      'perm' => 'user',
      'module' => '',
      'group' => 'user',
      'order' => 2);
   $menuitem[] = array(
      'name' => 'Block',
      'link' => $_PSL['adminurl'] . '/blockAdmin.php',
      'perm' => 'block',
      'module' => 'Block',
      'group' => 'layout',
      'order' => 74
   );
   $menuitem[] = array(
      'name' => 'Poll',
      'link' => $_PSL['adminurl'] . '/pollAdmin.php',
      'perm' => 'poll',
      'module' => 'Poll',
      'group' => 'action',
      'order' => 56
   );
   $menuitem[] = array(
      'name' => 'Users',
      'link' => $_PSL['adminurl'] . '/authorAdmin.php',
      'perm' => 'author',
      'module' => 'Author',
      'group' => 'admin',
      'order' => 91
   );
   $menuitem[] = array(
      'name' => 'Glossary',
      'link' => $_PSL['adminurl'] . '/glossaryAdmin.php',
      'perm' => 'glossary',
      'module' => 'Glossary',
      'group' => 'content',
      'order' => 28
   );
   $menuitem[] = array(
      'name' => 'Variable',
      'link' => $_PSL['adminurl'] . '/variableAdmin.php',
      'perm' => 'variable',
      'module' => 'Variable',
      'group' => 'admin',
      'order' => 98
   );
   $menuitem[] = array(
      'name' => 'Logging',
      'link' => $_PSL['adminurl'] . '/infologAdmin.php',
      'perm' => 'logging',
      'module' => 'Infolog',
      'group' => 'admin',
      'order' => 97);

   // Pulled from general admin section - not a navbar option
   // $menuitem[] = array('name' => 'Group', 'link' => $_PSL['adminurl'] . '/groupAdmin.php', 'perm' => 'groupAdmin','module' => 'Group');

   $_PSL['menuadmin'] = $menuitem; // add default menuitems to global array
    unset($menuitem);

   // end of NavBar Menu Definitions

   // *******************************
   // **** BEGIN: BACK-END PATCH ****
   // Needs to be after the menuitems are defined
   // - not sure of the implications of having this after the jpcache stuff earlier on in this file

   require_once('BE_config.php');

   // ***** END: BACK-END PATCH *****
   // *******************************


   /**
    * page features, all derived from PHPLIB classes and user-customizable
    **/

//Mar05: Remove over-generalisation - class files are now required earlier on
   $_PSL['page_features'] = array('sess' => 'slashSess','auth' => 'slashAuth','perm' => 'slashPerm');

   /* NOTE ON EMBEDDING PHPSLASH:
    *
    * Used to determine if phpslash is being invoked by a client
    * application. In this case, don't proceed with opening the
    * page.
    *
    * The implementation of this was required initially
    * for phplist integration but will likely be handy for other
    * embedding situations.
    */
   if (defined('PHPSLASH_CLIENT')) {
      return;
   }

   # debug('config.php page_open page features',$_PSL['page_features']);
   page_open($_PSL['page_features']);

   if(isset($_PSL['module']['phpOpenTracker']) && $_PSL['module']['phpOpenTracker']) {
      // Include phpOpenTracker after page_open to avoid session conflicts
      include_once('phpOpenTracker.php');
      phpOpenTracker::log(array('client_id' => $_BE['phpOpenTracker.client_id']));
   }


   /**
    * Now the session has started.  What is below here will be moving into
    * its own file to be called once per session.
    **/

   //////////////////////////////////////////////////////////////////////////
   // SECTION 2 - PHPLIB CLASS CONFIGURATION --------------------------------
   // SECTION 3 - PHPSLASH CLASS CONFIGURATION ------------------------------
   //////////////////////////////////////////////////////////////////////////


   //////////////////////////////////////////////////////////////////////////
   // SECTION 4 - MISC ------------------------------------------------------
   //////////////////////////////////////////////////////////////////////////


   // ***********************************************************************
   // ***** BEGIN: BACK-END PATCH ************************************************
   // Some Back_End class requirements/replacements could go here (instead of before page_open)
   // - this is for settings that require sessions etc to be instantiated

   // Make sure the correct language is being used
   if (empty($BE_currentLanguage)) {
      $BE_currentLanguage = (!empty($_BE['languagedomains'][$_SERVER['HTTP_HOST']])) ? $_BE['languagedomains'][$_SERVER['HTTP_HOST']] : $_BE['Default_language'];
   }

   if (!$sess->is_registered('BE_currentLanguage')) {
      $sess->register('BE_currentLanguage');
      # debug('config.php registering BE_currentLanguage',$BE_currentLanguage);
   }

   // Begin built-in jpcache - set after setCurrentLanguage to allow the
   // language choice to be cached properly.
   // Performance Improvements could be made by setting the language in the URL
   // or moving jpcache up higher in the config.php to improve load times.
   if (!empty($_PSL['jpcache.enable']) && $_PSL['jpcache.enable'] != 'off') {
      include_once($_PSL['classdir'] . '/BE_jpcacheFunctions.inc');
   }

   $newLanguage = (isset($_REQUEST['language'])) ? $_REQUEST['language'] : null;

   // A new language has been set explicitly, it must be defined
   // and not be the same as the current language
   if (!empty($newLanguage) && in_array($newLanguage, $_BE['Language_array']) && $newLanguage != $BE_currentLanguage) {

      // switch languages
      $BE_currentLanguage = $newLanguage;

      # debug('switch_lang newLanguage',$newLanguage);

      // is the currentURL defined or is it determined
      if (empty($_REQUEST['currentURL'])) {
         // HTTP_REFERER doesnt always exist & REQUEST_URI stumbles when running
         // php from the command line
         $currentURL = $_SERVER['PHP_SELF'];
         $currentURL .= (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : null;
      } else {
         // Strip out host from currentURL
         $currentURL = $_REQUEST['currentURL'];
         $currentURLarray = parse_url($currentURL);
         $currentURL = $currentURLarray['path'];
         $currentURL .= (!empty($currentURLarray['query'])) ? '?' . $currentURLarray['query'] : null;
         $currentURL .= (!empty($currentURLarray['fragment'])) ? '#' . $currentURLarray['fragment'] : null;
      }


      // Is language inherent in the domain name?
      if (isset($_BE['languagedomains']) && is_array($_BE['languagedomains'])) {

         $_BE['languagedomains.flipped'] = array_flip($_BE['languagedomains']);

         // there are language specific domain names, so relocate to new domain,
         $translatedDomain = pslgetText($_PSL['rootdomain']);
         if($translatedDomain != $_PSL['rootdomain'] && !empty($_BE['languagedomains'][$translatedDomain])) {
            $newDomain = $translatedDomain;
         } else {
            $newDomain = (!empty($_BE['languagedomains.flipped'][$newLanguage])) ? $_BE['languagedomains.flipped'][$newLanguage] : $_BE['languagedomains.flipped'][$_BE['Default_language']];
         }

         // explicitly set new domain
         $location = 'http://';
         $location .= (isset($BE_subsite['http_host'])) ? $BE_subsite['http_host'] . '.' : null;
         $location .= $newDomain . $currentURL;

         //Tag on session info if we're changing domain
         $sessInfo = $sess->name . '=' . $sess->id();

         // If not already in the URL add the above session info
         if (!strstr($currentURL, $sessInfo)) {
            if (empty($_REQUEST['currentURL'])) {
               $location .= (!empty($_SERVER['QUERY_STRING'])) ? '&amp;' : '?';
            } else {
               $location .= (!empty($currentURLarray['query'])) ? '&amp;' : '?';
            }
            $location .= $sessInfo;
         }

      }

      // simplest case of redirection
      if (empty($location)) {
         $location = $currentURL;
      }

      // close session page and redirect browser
      page_close();
      Header('Location: ' . $location);

   }

   setCurrentLanguage();

   // if subsites are active, then setup the current envioronment
   //   based on subsite rules
   if ($_PSL['module']['BE_Subsite']) {
      $subsite = pslNew('BE_Subsite');
      $subsite->setupEnvironmentForSubsite();
      # debug('config.php setting up for subsites',$_PSL['module']['BE_Subsite']);
      $cmSections = $subsite->getContentManagerSections($auth->auth['uid']);

      foreach($cmSections as $cmId => $cmUrl) {
         $cmName = $cmUrl;
         if(strpos($cmUrl,'http://')===0) {
            $cmName = substr($cmUrl,7);
         }
         if(strpos($cmName,'/')) {
            $cmName = substr($cmName,0,strlen($cmName)-1);
         }

         $_PSL['menuadmin'][] = array(
            'name' => pslGetText('Edit').' '.$cmName,
            'link' => $cmUrl.$_PSL['adminurl']."/BE_subsiteAdmin.php?submit=edit&amp;id=$cmId",
            'perm' => 'nobody',
            'module' => 'BE_Subsite'
         );
      }

      // absoluteurl plus sub-dir information - correcting default URL information above
      if (be_inSubsite()) {
         if (!isset($BE_subsite['http_host'])) {
            $BE_subsite['http_host'] = $_PSL['rootsubdomain'] . $_PSL['rootdomain'];
         }
         $_PSL['absoluteurl'] = 'http://' . $BE_subsite['http_host'] . $_PSL['rooturl'];
      }
   }

   // ***** END: BACK-END PATCH *********************************************
   // ***********************************************************************

   define('PERM_ALLSECTIONS', 'section_id0'); // Called ALLSECTIONS in phpSlash

   if ($_PSL['timezone']['engine']) {
#      loadLibrary('tz');
      require_once($_PSL['classdir'] . "/tz_functions.inc");
      // set up the time zone environment array
      $_TZ = &$_PSL['timezone'];
      $_TZ['templatedir'] = $_PSL['templatedir'];
      $_TZ['show_format'] = psl_getLocalInfo('LC_TIME', '%a %b %e %H:%M:%S %Z %Y');
      /*   $_TZ['available'] = array('America/New_York',
       'America/Chicago',
       'America/Denver',
       'America/Los_Angeles',
       'Pacific/Honolulu');
       */
      // to enable setting of time zone in URL
      if(isset($_GET['TZ']) && !empty($_GET['TZ'])) {
         set_TZ(clean($_GET['TZ']), 'cookie');
      }
      // to disable setting in URL and to set $_PSL['timezone']['name'] as the time zone
      // set_TZ();
   }

   $default_block_options = '';
/*
*  default values of options for all blocks
*
*  default options legend:
*
*  name  - option name.  This option will always be available to the admin.
*  description - optional text explanation.
*  value - option value.  This value will be displayed for the option.  The
*          admin can assign a new value.
*  type - type of form input - text, radio, or select.
*  choices - for radio or select form entry, defines
*  To add default option items, add a complete entry below.
*/
$default_block_options[] = array(
   'name'  => 'column',
   'description' => ' - main content column is named center',
   'value' => 'right',
   'type'  => 'radio',
   'choices' => array('left'   => 'left',
                      'right'  => 'right',
                      'center' => 'center'));

$default_block_options[] = array(
   'name'  => 'width',
   'description' => '',
   'value' => '',
   'type'  => 'select',
   'choices' => array(''     => 'Default(100%)',
                      '160'  => '160',
                      '210'  => '210',
                      '100%' => '100%')
);

/*
$default_block_options[] = array(
   'name'  => 'box_type',
   'description' => '',
   'value' => '',
   'type'  => 'select',
   'choices' => array(''         => 'Default(fancy)',
                      'open'     => 'Open',
                      'framed'   => 'Framed',
                      'bordered' => 'Bordered')
);
*/


$_PSL['default_block_options'] = $default_block_options;
unset($default_block_options);

   /* ============= Things to deprecate ============= */

   // ----------------------------------------------------------

   // ----------------------------------------------------------
   // The following can be deleted
   // ----------------------------------------------------------
   // ----------------------------------------------------------

?>
