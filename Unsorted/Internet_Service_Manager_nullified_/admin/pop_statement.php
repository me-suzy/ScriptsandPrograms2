<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="billing";
include "../conf.php";
include "auth.php";

$in=mysql_fetch_array(mysql_query("SELECT * FROM statements WHERE id='$statement_id'"));
if($in[sent_type]=="email"){
 header("Content-Type: text/plain");
 echo $in[data];
 exit;
}
echo '<HTML>
<HEAD>
<TITLE>Statement #: '.$in[id].'</TITLE>

</HEAD>
<BODY bgcolor="#efefef">';



echo $in[data];

if($print){
    echo '<script language="javascript">
         window.print()
</script>';
}

?>
</BODY>
</HTML>
