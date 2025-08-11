<?php

// Start - Added by Mike

global $_PSL;
if( !empty($_PSL['phplibdir'])) {
   $_PHPLIB['libdir'] = $_PSL['phplibdir'];
} else {
   $_PHPLIB['libdir'] = $_PSL['classdir']."/phplib/php/";
}

require_once($_PHPLIB['libdir'] .'db_mysql.inc');

$db = new DB_Sql;

// End - Added by Mike

    /* $Id: phplib.php,v 1.1.1.1 2003/11/06 02:20:24 mgifford Exp $ */

    // One of the following may be required, depending on local installation.
    // require('/path/to/prepend.php');
    // require("/path/to/db_mysql.inc");
    
    /* jpcacheDB
     *
     * database class extension to phplib
     */
    class jpcacheDB extends DB_Sql {

       var $Host;
       var $Database;
       var $User;
       var $Password;
       var $Halt_On_Error;

       function jpcacheDB() {
          $this->Host     = $GLOBALS["JPCACHE_DB_HOST"];
          $this->Database = $GLOBALS["JPCACHE_DB_DATABASE"];
          $this->User     = $GLOBALS["JPCACHE_DB_USERNAME"];
          $this->Password = $GLOBALS["JPCACHE_DB_PASSWORD"];

          $this->Halt_On_Error = "yes";
       }

    }



    /* jpcache_db_connect()
     *
     * Makes connection to the database
     */
    function jpcache_db_connect()
    {
       if(array_key_exists("sql_link", $GLOBALS)) {
          $db = $GLOBALS["sql_link"];
       } else {
          $db = '';
       }
       if(!is_object($db)) {
          $db = new jpcacheDB();
       }
       $GLOBALS["sql_link"] = $db;
    }
    
    /* jpcache_db_disconnect()
     *
     * Closes connection to the database
     */
    function jpcache_db_disconnect()
    {
        // mysql_close($GLOBALS["sql_link"]);
    }
    
    /* jpcache_db_query($query)
     *
     * Executes a given query
     */    
    function jpcache_db_query($query)
    {
        $db = $GLOBALS["sql_link"];
        
        // jpcache_debug("Executing SQL-query $query");
        $ret = $db->query($query);
        
        return $ret;
    }
    
    /* jpcache_restore()
     *
     * Will try to restore the cachedata from the db.
     */
    function jpcache_restore()
    {
        $db = $GLOBALS["sql_link"];
        
        $res = $db->query("select GZDATA, 
                                      DATASIZE, 
                                      DATACRC 
                                 from ".  $GLOBALS["JPCACHE_DB_TABLE"].  " 
                                 where CACHEKEY='".  addslashes($GLOBALS["jpcache_key"]).  "' 
                                 and (CACHEEXPIRATION>".  time().  " or CACHEEXPIRATION=0)");
                                
        if ($db->next_record())
        {
           // restore data into global scope from found row
           $GLOBALS["jpcachedata_gzdata"]   = $db->Record["GZDATA"];
           $GLOBALS["jpcachedata_datasize"] = $db->Record["DATASIZE"];
           $GLOBALS["jpcachedata_datacrc"]  = $db->Record["DATACRC"];
           return true;
        }
        return false;
    }

    /* jpcache_write()
     *
     * Will (try to) write out the cachedata to the db
     */
    function jpcache_write($gzdata, $datasize, $datacrc) 
    {

        $db = $GLOBALS["sql_link"];
        
        $dbtable = $GLOBALS["JPCACHE_DB_TABLE"];
        
        // XXX: Later on, maybe implement locking mechanism inhere.
        
        // Check if it already exists
        $res = $db->query("select CACHEEXPIRATION from $dbtable".
                                " where CACHEKEY='".
                                    addslashes($GLOBALS["jpcache_key"]).
                                "'"
                               );
        
        
        if (!$db->next_record()) 
        {
            // Key not found, so insert
            $res = $db->query("insert into $dbtable".
                                    " (CACHEKEY, CACHEEXPIRATION, GZDATA,".
                                    " DATASIZE, DATACRC) values ('".
                                        addslashes($GLOBALS["jpcache_key"]).
                                    "',".
                                        (($GLOBALS["JPCACHE_TIME"] != 0) ? 
                                        (time()+$GLOBALS["JPCACHE_TIME"]) : 0).
                                    ",'".
                                        addslashes($gzdata).
                                    "', $datasize, $datacrc)"
                                   );
            // This fails with unique-key violation when another thread has just
            // inserted the same key. Just continue, as the result is (almost) 
            // the same.
        }
        else
        {
            // Key found, so update
            $res = $db->query("update $dbtable set CACHEEXPIRATION=".
                                        (($GLOBALS["JPCACHE_TIME"] != 0) ?
                                        (time()+$GLOBALS["JPCACHE_TIME"]) : 0).
                                    ", GZDATA='".
                                        addslashes($gzdata).
                                    "', DATASIZE=$datasize, DATACRC=$datacrc where".
                                    " CACHEKEY='".
                                        addslashes($GLOBALS["jpcache_key"]).
                                    "'"
                                   );
            // This might be an update too much, but it shouldn't matter
        }
    }
   
    /* jpcache_do_gc()
     *
     * Performs the actual garbagecollection
     */
    function jpcache_do_gc($method='cachetimeout', $argv='')
    {

        $db = $GLOBALS["sql_link"];
        if( !is_object($db)) {
            jpcache_db_connect();
            $db = $GLOBALS["sql_link"];
        }
 
        switch( $method) {
            
            case 'regex':
                $q  = "delete from ". $GLOBALS["JPCACHE_DB_TABLE"]. 
                      " where CACHEKEY RLIKE \"$argv\" ";
                jpcache_db_query($q);
                break;
            
            case 'string':
                $q  = "delete from ". $GLOBALS["JPCACHE_DB_TABLE"]. 
                      " where CACHEKEY LIKE \"%$argv%\" ";
                $db->query($q);
                break;
                
            case 'cachetimeout':
            default:
            
                $db->query("delete from ".
                             $GLOBALS["JPCACHE_DB_TABLE"].
                           " where CACHEEXPIRATION<=".
                             time().
                           " and CACHEEXPIRATION!=0"
                );
                        
                // Are we allowed to do an optimize table-call?
                // As noted, first check if this works on your mysql-installation!
                if ($GLOBALS["JPCACHE_OPTIMIZE"])
                {
                    $db->query("OPTIMIZE TABLE ".$GLOBALS["JPCACHE_DB_TABLE"]);                       
                }

                break;
            // end default case
        }

    }

    
    /* jpcache_do_start()
     *
     * Additional code that is executed before main jpcache-code kicks in.
     */
    function jpcache_do_start()
    {  
        // Connect to db
        jpcache_db_connect();
    }

    /* jpcache_do_end()
     *
     * Additional code that is executed after caching has been performed,
     * but just before output is returned. No new output can be added!
     */
    function jpcache_do_end()
    {
        // Disconnect from db
        // jpcache_db_disconnect();
    }
    
?>
