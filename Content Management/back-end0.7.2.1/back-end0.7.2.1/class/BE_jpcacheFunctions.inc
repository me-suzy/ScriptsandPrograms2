<?php

   // Use internal cache if set in the config.ini.php,
   // if the user is logged in, but not if the user is on the login.php page
   // so that the admin can clear the cache.
   if($_PSL['jpcache.enable'] == 'internal' || ($perm->have_perm('user') && $_SERVER['SCRIPT_FILENAME'] != $_PSL['basedir'] . '/login.php')) {

      // jpcachedir->the full directory path to the jpcache scripts.
      if (empty($_PSL['jpcachedir'])) {
         $_PSL['jpcachedir'] = $_PSL['classdir'] . '/jpcache';
      }

      $JPCACHE_TYPE = 'file';
      // $JPCACHE_TYPE = 'mysql';
      // $JPCACHE_TYPE = 'dbm';
      // $JPCACHE_TYPE = 'phplib';

      /**
       * General jpcache configuration options.
       */
      $JPCACHE_ON = 1;            // Turn caching on/off
      $JPCACHE_TIME = 14400;      // Default number of seconds to cache a page (4hrs)
      $JPCACHE_DEBUG = 0;         // Turn debugging on/off
      $JPCACHE_IGNORE_DOMAIN = 1; // Ignore domain name in request(single site)
      $JPCACHE_USE_GZIP = 0;      // Whether or not to use GZIP
      $JPCACHE_POST = 1;          // Should POST's be cached
      $JPCACHE_GC = 1;            // Probability % of garbage collection
      $JPCACHE_GZIP_LEVEL = 9;    // GZIPcompressionlevel to use (1=low,9=high)
      $JPCACHE_DELAY_START = 1;   // Immediate or manual call to jpcache_start()
      $JPCACHE_HASHSCRIPTKEY = 0; // Hash SCRIPT-KEY or not
      $JPCACHE_HASHVARKEY = 1;    // Hash VAR-KEY or not

      /**
       * Choose File or DB based caching settings.
       */
      if ('mysql' == $JPCACHE_TYPE || 'phplib' == $JPCACHE_TYPE) {
         $JPCACHE_DB_HOST = $_PSL['DB_Host'];
         // Database Server
         $JPCACHE_DB_DATABASE = $_PSL['DB_Database']; // Database-name to use
         $JPCACHE_DB_USERNAME = $_PSL['DB_User'];
         // Username
         $JPCACHE_DB_PASSWORD = $_PSL['DB_Password']; // Password
         $JPCACHE_DB_TABLE = 'CACHEDATA'; // Table that holds the data
         $JPCACHE_OPTIMIZE = 0;
         // If 'OPTIMIZE TABLE' after garbage
         // collection is executed. Please check
         // first if this works on your mySQL!
      } else {
         // $JPCACHE_DIR          = '/tmp/jpcache';
         $JPCACHE_DIR = $_PSL['basedir'] . '/updir/fileCache'; // Directory where jpcache must store
         // generated files. Please use a dedicated
         // directory, and make it writable
         $JPCACHE_FILEPREFIX = 'jpc'; // Prefix used in the filename. This enables
         // us to (more accuratly) recognize jpcache-
         // files.
      }

      $JPCACHE_VERSION = '2.0.0rc1';

      // Standard jpcache functions
#      addLibraryRequirement('jpcache', $_PSL['jpcachedir'] . '/jpcache-main.php');
      require_once($_PSL['jpcachedir'] . '/jpcache-main.php');

      // Type specific jpcache implementations
      if ('phplib' == $JPCACHE_TYPE) {
      //MAR05 CLEANUP - This needs replaced!
#         addClassRequirement('jpcacheDB', 'db_sql');
#         addClassRequirement('jpcacheDB', $_PSL['jpcachedir'] .'/type/phplib.php');
         include_once($_PSL['jpcachedir'] . '/type/phplib.php');
      }
#      addLibraryRequirement('jpcache', $_PSL['jpcachedir'] ."/type/$JPCACHE_TYPE.php");
#      loadLibrary('jpcache');
      require_once($_PSL['jpcachedir'] . '/type/' . $JPCACHE_TYPE . '.php');

      // end of jpcache configuration

      // cache invocation
      $varkey = '';
      $scriptkey = '';

      // non logged in users were having their pages cached
      if ($perm->have_perm('user')) {
         if (!empty($_COOKIE['slashSess'])) {
            $scriptkey = '-slashSess-'. $_COOKIE['slashSess'];
         } elseif (!empty($_COOKIE['user_info']) ) {
            $varkey = md5('POST=' . serialize(clean($_POST)) . ' GET=' . serialize(clean($_GET)) . ' COOKIE=' . serialize($_COOKIE));
         }
      }

      if (!empty($_COOKIE['skin'])) {
         $scriptkey .= '-skin-'. $_PSL['skin'];
      }

      $cacheLanguage = getCurrentLanguage();
      if ($cacheLanguage) {
         $scriptkey .= '-lang-' . $cacheLanguage;
      }

      // Caching the Time Zone isn't all that important to BE at this point
      // set to true if web site requires more timezone sensativity.
      if (false) {
         if (!empty($_COOKIE['TZ'])) {
            $scriptkey .= '-TZ-'. $_COOKIE['TZ'];
         } else {
            $scriptkey .= '-TZ-'. $_PSL['timezone']['name'];
         }
      }

      // add GET variables to scriptkey
      if (is_array($_GET) && !empty($_GET)) {
         foreach ($_GET as $key => $value) {
            $scriptkey .= '-'. clean($key) .'-'. clean($value);
         }
      }

      // psl specific
      // if (empty($_GET['section']) && empty($_GET['section_id'])){
      //    $scriptkey .=  '-section_id-'. $_PSL['home_section_id'];
      // }

      // not too goofy key names
      $scriptkey = preg_replace("'/|:|\\|\*|<|>|\|'", '-', $_PSL['phpself'].$scriptkey);
      if ($GLOBALS['JPCACHE_IGNORE_DOMAIN'] == 0) {
         $scriptkey = str_replace('.', '-', $_SERVER['HTTP_HOST'] .$scriptkey);
      }

      // Spacer before md5's variable
      $scriptkey .= '-';

      jpcache_start('', $scriptkey, $varkey);

      // end of jpcache invocation

   // end of built-in jpcache portion
   }  elseif($_PSL['jpcache.enable'] == 'static') {

   // change .php to .html
   $numberOfMenuitems = count($_PSL['menuitem']);
   for ($i=0 ; $i < $numberOfMenuitems ; ++$i) {
      $_PSL['menuitem'][$i]['link'] = ereg_replace(".php$", '.html', $_PSL['menuitem'][$i]['link']);
   }

   // beginning of static cache.  This mechanism writes the html cache files
   // to the basedir by default.  This can be a security risk on servers that
   // php writes files using a common user/group.

   // This option should only be used where the files created are owned by
   // the user, the server is in a jail, uml, or security assured in some
   // other way.

   // jpcachedir->the full directory path to the jpcache scripts.
   if (empty($_PSL['jpcachedir'])) {
      $_PSL['jpcachedir'] = $_PSL['classdir'] . '/jpcache';
   }

   $JPCACHE_TYPE = 'static';

   /**
     * General jpcache configuration options.
     */
   $JPCACHE_TIME         =   14400; // Default number of seconds to cache a page
   $JPCACHE_DEBUG        =   0;     // Turn debugging on/off
   $JPCACHE_IGNORE_DOMAIN=   1;     // Ignore domain name in request(single site)
   $JPCACHE_ON           =   1;     // Turn caching on/off
   $JPCACHE_USE_GZIP     =   0;     // Whether or not to use GZIP
   $JPCACHE_POST         =   1;     // Should POST's be cached
   $JPCACHE_GC           =   1;    // Probability % of garbage collection
   $JPCACHE_GZIP_LEVEL   =   9;     // GZIPcompressionlevel to use (1=low,9=high)
   $JPCACHE_DELAY_START  =   1;     // Immediate or manual call to jpcache_start()
   $JPCACHE_HASHSCRIPTKEY =  0;     // Hash SCRIPT-KEY or not
   $JPCACHE_HASHVARKEY    =  0;     // Hash VAR-KEY or not

   /**
     * File based caching setting.
     */
   $JPCACHE_DIR          = $_PSL['basedir'];  // store cache files in basedir

   $JPCACHE_FILEPREFIX   = '';// Prefix used in the filename. This enables
                                   // us to (more accuratly) recognize jpcache-
                                   // files.

   // end of jpcache configuration

   $JPCACHE_VERSION = '2.0.0rc1';

   // Standard jpcache functions
#   addLibraryRequirement('jpcache', $_PSL['jpcachedir'] . '/jpcache-main.php');
#   addLibraryRequirement('jpcache', $_PSL['jpcachedir'] ."/type/$JPCACHE_TYPE.php");
#   loadLibrary('jpcache');
   require_once($_PSL['jpcachedir'] . '/jpcache-main.php');
   require_once($_PSL['jpcachedir'] ."/type/$JPCACHE_TYPE.php");

   // cache invocation
   $varkey = '';
   $scriptkey = '';

   $noSubDirectoriesArray = array('/search', '/poll', '/login', '/sitemap', '/index');

   // Check for gallery.php, etc
   if ($_SERVER['SCRIPT_NAME'] != "/{$_PSL['mainpage']}" && $cachetimeout != -1) {
      $scriptName = str_replace('.php', '', $_SERVER['SCRIPT_NAME']);
      if (in_array($scriptName, $noSubDirectoriesArray)) {
         $noSubDirectories = true;
      } else {
         $JPCACHE_DIR .= $scriptName;
         if(!is_dir($JPCACHE_DIR)) {
            mkdir ($JPCACHE_DIR);
         }
      }
   }

   // check to cache language
   $cacheLanguage = getCurrentLanguage();
   if ($_PSL['language'] != $cacheLanguage && !empty($cacheLanguage) && $cachetimeout != -1) {
      $JPCACHE_DIR .= '/' . $cacheLanguage;
      if(!is_dir($JPCACHE_DIR)) {
         mkdir ($JPCACHE_DIR);
      }
   }

   // Check to cache skin
   if (!empty($_COOKIE['skin']) && $cachetimeout != -1) {
      $JPCACHE_DIR .= '/' . $_PSL['skin'];
      if(!is_dir($JPCACHE_DIR)) {
         mkdir ($JPCACHE_DIR);
      }
   }

   // add GET variables to scriptkey
   foreach($_GET as $key => $value) {
      $key = clean($key);
      $value = clean($value);
      $scriptkey = $key . '/' . $value . '/' . $scriptkey;
      $JPCACHE_DIR .= "/$key-$value";
      if(!is_dir($JPCACHE_DIR) && $cachetimeout != -1 && !$noSubDirectories) {
         mkdir ($JPCACHE_DIR);
      }
   }

   // Set section and article
   $getVars = ereg_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
   @list($nothing, $section, $article) = split('/', $getVars);
   $JPCACHE_DIR .= '/' . strtolower($section);
   if(!is_dir($JPCACHE_DIR) && $cachetimeout != -1) {
      mkdir ($JPCACHE_DIR);
   }
   if (!empty($article)) {
      $scriptkey = strtolower($article);
   } elseif($noSubDirectories) {
      $scriptkey = $scriptName;
   } else {
      $scriptkey = 'index';
   }

   // append html file extension
   $varkey .= '.html';

   jpcache_start('', $scriptkey, $varkey);

   // end of jpcache invocation

} // end of static jpcache portion

?>
