<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";
include "auth.php";

$data=mysql_fetch_array(mysql_query("SELECT * FROM attatchments WHERE id='$id'"));

			header("Content-Type: ".$data[type]."; name=\"".$data[filename]."\"");
			header("Content-Disposition: attatchment; filename=\"".$data[filename]."\"\r\n");
                        echo $data[data];
?>
