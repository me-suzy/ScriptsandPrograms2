<?
require_once('class.dir_reader.php');
require_once('function.viewarray.php');

$file = &new dir_reader();
$file->read_directory('./class');
echo '<br><div align="center">';
$temp = array();
$temp = $file->get_files();
$count = count($temp);
$temp2 = array();
for($i=0;$i<$count;$i++){
   if(preg_match("/^class.*.php/i",$temp[$i])){
       $temp2[] = $temp[$i];
   }
}
echo viewarray($temp2);
echo '</div><br>';
?>