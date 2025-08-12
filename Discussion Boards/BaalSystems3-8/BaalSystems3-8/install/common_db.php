<?php
include "envvars.php";
extract ($HTTP_POST_VARS,EXTR_OVERWRITE) ;
extract ($HTTP_GET_VARS,EXTR_OVERWRITE) ;
include "dataaccess.php" ;
$dbhost = "$dbservername";
$dbusername = "$dbusername";
$dbuserpassword = "$dbpassword";
$default_dbname = "$dbname";
$MYSQL_ERRNO = "";
$MYSQL_ERROR = "";
//CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
function db_connect()
{
global $dbhost,$dbusername,$dbuserpassword,$default_dbname;
global $MYSQL_ERRNO,$MYSQL_ERROR;
$link_id=mysql_connect($dbhost,$dbusername,$dbuserpassword);
if( $link_id == 0 )
    return 0 ;
if(mysql_select_db($default_dbname) == false)
{
        $db_name = mysql_create_db("$default_dbname") ;
        if(mysql_select_db($default_dbname) == false)
        {
            $MYSQL_ERRNO=mysql_errno();
            $MYSQL_ERROR=mysql_error();
            echo "connection failed @ stage2.<br>" ;
            return 0;
        }
}
return $link_id;
}
function sql_error()
{
global $MYSQL_ERRNO,$MYSQL_ERROR;
if(empty($MYSQL_ERROR))
{
$MYSQL_ERRNO=mysql_errno();
$MYSQL_ERROR=mysql_error();
}
return $MYSQL_ERRNO ;
}

