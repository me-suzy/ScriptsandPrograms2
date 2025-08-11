<?php

    /* $Id: jpcache-config.php,v 1.2 2005/03/21 04:28:01 mgifford Exp $ */

    // jpcache configuration file
    //
    // Some settings are specific for the type of cache you are running, like
    // file- or database-based.
    //

    /**
     * Define which jpcache-type you want to use (storage and/or system).
     *
     * This allows for system-specific patches & handling, as sometimes
     * 'platform independent' is behaving quite differently.
     *
     * Uncomment only the one you want to use in the following lines!
     */

    // $JPCACHE_TYPE = "file";
    $JPCACHE_TYPE = 'mysql';
    // $JPCACHE_TYPE = "dbm";
    // $JPCACHE_TYPE = "phplib";

    // DOH! Strip out this check for performance if you are sure you did set it.
    if (!isset($JPCACHE_TYPE))
    {
        exit("[jpcache-config.php] No JPCACHE_TYPE has been set!");
    }

    /**
     * General configuration options.
     */
    $JPCACHE_TIME         =   28800; // Default number of seconds to cache a page (default set to 8)
    $JPCACHE_DEBUG        =   1;   // Turn debugging on/off
    $JPCACHE_IGNORE_DOMAIN=   1;   // Ignore domain name in request(single site)
    $JPCACHE_ON           =   1;   // Turn caching on/off
    $JPCACHE_USE_GZIP     =   0;   // Whether or not to use GZIP
    $JPCACHE_POST         =   1;   // Should POST's be cached
    $JPCACHE_GC           =   1;   // Probability % of garbage collection
    $JPCACHE_GZIP_LEVEL   =   9;   // GZIPcompressionlevel to use (1=low,9=high)
    $JPCACHE_DELAY_START  =   0;   // Immediate or manual call to jpcache_start
    $JPCACHE_HASHSCRIPTKEY =  1;   // Hash SCRIPT-KEY or not


    /**
     * File based caching setting.
     */
    $JPCACHE_DIR          = $_PSL['basedir'] . '/updir/jpcache'; // Directory where jpcache must store
                                   // generated files. Please use a dedicated
                                   // directory, and make it writable
    $JPCACHE_FILEPREFIX   = 'jpc-';// Prefix used in the filename. This enables
                                   // us to (more accuratly) recognize jpcache-
                                   // files.

    /**
     * DB based caching settings.
     */
    // $JPCACHE_DB_HOST      = "localhost"; // Database Server
    // $JPCACHE_DB_DATABASE  = "be053";   // Database-name to use
    // $JPCACHE_DB_USERNAME  = "user";   // Username
    // $JPCACHE_DB_PASSWORD  = "password";    // Password

    $JPCACHE_DB_HOST      = $_PSL['DB_Host']; // Database Server
    $JPCACHE_DB_DATABASE  = $_PSL['DB_Database'];   // Database-name to use
    $JPCACHE_DB_USERNAME  = $_PSL['DB_User'];   // Username
    $JPCACHE_DB_PASSWORD  = $_PSL['DB_Password'];    // Password

    $JPCACHE_DB_TABLE     = 'CACHEDATA'; // Table that holds the data
    $JPCACHE_OPTIMIZE     = 1;           // If 'OPTIMIZE TABLE' after garbage
                                         // collection is executed. Please check
                                         // first if this works on your mySQL!
?>