<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";

//first check if there are support techs online!
if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE online>'".(time()-30)."'"))){
$fd = fopen ("../images/online".$QUERY_STRING.".gif", "rb");
$contents = fread ($fd, filesize ("../images/online".$QUERY_STRING.".gif"));
fclose ($fd);
}else{
$fd = fopen ("../images/offline".$QUERY_STRING.".gif", "rb");
$contents = fread ($fd, filesize ("../images/offline".$QUERY_STRING.".gif"));
fclose ($fd);
}


      

Header( "Content-type: image/gif"); 
echo $contents;
?>

}
