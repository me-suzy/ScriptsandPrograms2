<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');


if ( !isset($myDB) )
{
    # open mysql connection
    $myDB = mysql_connect(cnfg('dbHost'), cnfg('dbUser'), cnfg('dbPass'));
    mysql_select_db(cnfg('dbName'), $myDB);
}

$query = 'select bin_data,filetype from std_blob_images where blob_id='.$the_id.';';

$result = MYSQL_QUERY($query);

$data = MYSQL_RESULT($result,0,"bin_data");
$type = MYSQL_RESULT($result,0,"filetype");

Header( "Content-type: $type");
echo $data;


?> 
 
