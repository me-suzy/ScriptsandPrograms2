<?php

$incdbhost = "localhost";
$incdbuser = "stefan";
$incdbpwd = "stefan";
$databasename = "phpinfo";
$sSiteTitle = "Stefans PHP Code";


function Incdb_GetConnectionDedicated()
{
        global $incdbhost;
        global $incdbuser;
        global  $incdbpwd, $databasename;
        $conn = mysql_connect($incdbhost, $incdbuser, $incdbpwd);
        mysql_select_db($databasename, $conn);
        return $conn;
};

function Incdb_GenerateDatetime( $dDate )
{
        return date( "Y-m-d h:i:s", $dDate );
}

function My_FormatDate( $datum )
{
 $ret = "";
 $ret = substr($datum,0,4) . "-" . substr($datum,4,2) . "-" . substr($datum,6,2) . " " . substr($datum,8,2) . ":" . substr($datum,10,2);
return $ret;
};


?>