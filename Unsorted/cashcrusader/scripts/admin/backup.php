<?
include("../conf.inc.php");
include("../functions.inc.php");
error_reporting(1);
admin_login();
if (!$mysqldumppath){
$mysqldumppath="/usr/local/bin";}
if (!$gzippath){
$gzippath='/usr/bin';}
	$cur_time=date("Y-m-d H:i");
if (file_exists($mysqldumppath."/mysqldump") and file_exists($gzippath."/gzip")){
$gzipext=".gz";}
header("Content-disposition: filename=$mysql_database.sql$gzipext");
                                        header("Content-type: application/octetstream");
                                        header("Pragma: no-cache");
                                        header("Expires: 0");
if ($gzipext){
passthru($mysqldumppath."/mysqldump -h$mysql_hostname -u$mysql_user -p$mysql_password $mysql_database | ".$gzippath."/gzip -f");
exit;}


 $zaehler = 0;
 $start=0;

$tbl_array = array(); $c = 0;
$result2 = mysql_list_tables($mysql_database);
for($x=0; $x<mysql_num_rows($result2); $x++) 
{ 	
		$tabelle = mysql_tablename($result2,$x);
		 if ($tabelle <>"") {
	  						$tbl_array[$c] = mysql_tablename($result2,$x); $c++;$zaehler++;
							 }

}								 
flush();
for ($y = 0; $y < $c; $y++){  
	$tabelle=$tbl_array[$y];
    $def = "";
    $def .= "CREATE TABLE $tabelle (\n"; 
    $result3 = mysql_db_query($mysql_database, "SHOW FIELDS FROM $tabelle",$conn_id);
    while($row = @mysql_fetch_array($result3)) {
        $def .= "    $row[Field] $row[Type]";
        if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
        if ($row["Null"] != "YES") $def .= " NOT NULL";
       	if ($row[Extra] != "") $def .= " $row[Extra]";
        	$def .= ",\n";
     }
     $def = ereg_replace(",\n$","", $def);
     $result3 = mysql_db_query($mysql_database, "SHOW KEYS FROM $tabelle",$conn_id);
     while($row = @mysql_fetch_array($result3)) {
          $kname=$row[Key_name];
          if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
          if(!isset($index[$kname])) $index[$kname] = array();
          $index[$kname][] = $row[Column_name];
     }
     while(list($xy, $columns) = @each($index)) {
          $def .= ",\n";
          if($xy == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
          else if (substr($xy,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($xy,7)." (" . implode($columns, ", ") . ")";
          else $def .= "   KEY $xy (" . implode($columns, ", ") . ")";
     }

     $def .= "\n); \n";
     
echo $def;

$db = mysql_select_db($mysql_database,$conn_id); 

$tabelle="".$tabelle; 
$ergebnis=array();
	unset($data);
if ($tabelle>""){  
    $ergebnis[]=mysql_select_db($mysql_database,$conn_id); 
    $result=@mysql_query("select * from $tabelle"); 
        $anzahl= mysql_num_rows ($result); 
    $spaltenzahl = mysql_num_fields($result); 
        for ($i=0;$i<$anzahl;$i++) { 
                $zeile=@mysql_fetch_array($result); 
        
                $data.="insert into $tabelle ("; 
        for ($spalte = 0; $spalte < $spaltenzahl;$spalte++) { 
              $feldname = mysql_field_name($result, $spalte); 
              if($spalte == ($spaltenzahl - 1)) 
          { 
            $data.= $feldname; 
          } 
          else 
          { 
            $data.= $feldname.","; 
          } 
        }; 
        $data.=") VALUES ("; 
                for ($k=0;$k < $spaltenzahl;$k++){ 
          if($k == ($spaltenzahl - 1)) 
          { 
                        $data.="'".addslashes($zeile[$k])."'"; 
                  } 
          else 
          { 
                        $data.="'".addslashes($zeile[$k])."',"; 
                  } 
        } 
                $data.= ");\n"; 
echo $data;
$data='';
        } 
echo "\n";
} 
else 
{ 
      $ergebnis[]= $err; 
} 

$zeit = (date("d_m_Y")); 
$zeit = time() - $start;
$speed = $speed+$zeit;
}
	
?>
