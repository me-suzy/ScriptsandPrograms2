<?php
/********************************************************************************/
/*				FILE NAME CASE CHANGER				        */
/*				PROGRAMMER : Vipin Chandran				        */
/*              E-Mail : vipin1973@hotmail.com					*/
/* Convert the file name case into lower case in a folder and sub folders */
/********************************************************************************/

/* Scan root directory */
/* ******************* */

if(!isset($dir_arr) && isset($root))
{
 $main_dir = stripslashes($root);

 if(!($handle=@opendir($main_dir)))
 {
     print "<DIV align=center><h5>PLEASE SPECIFY A VALID DIRECTORY</h5></div>";
     exit;
 }
 $i=1;
 $dir_arr=array();
 echo "THE FOLLOWING FILENAMES WERE CONVERTED TO LOWERCASE<br>";
 echo "********************************<br>".$main_dir."<br>********************************<br>";
  while ($file = readdir($handle))
 {
  if ($file != "." && $file != "..")
  {
    /* Check if the file is a directory and if so, store it in an array */
    /* **************************************************************** */
    
   if(is_dir($main_dir."\\".$file))
   {
    array_push($dir_arr,$file);
   }
   echo "$i=>\"$file\",<br>";
   $file2 = strtolower($file);
   if(!(@rename($main_dir."\\".$file,$main_dir."\\".$file2)))
   {
       Print "<br>Rename Failed<br>";
   }
   $i++;
  }
 }
 closedir($handle);
}

/* Traverse through the subfolders stored in the array  */
/* **************************************************** */

$x=0;
while($x<count($dir_arr))
{
 $main_dir = addslashes(stripslashes($root)."\\".$dir_arr[$x]);
 if(!($handle=@opendir($main_dir)))
 {
     print "<DIV align=center><h5>CANNOT OPEN DIRECTORY</h5></div>";
     exit;
 }
 $i=1;
 echo "********************************<br>".stripslashes($main_dir)."<br>********************************<br>";
 while ($file = readdir($handle))
 {
  if ($file != "." && $file != "..")
  {
   if(is_dir($main_dir."\\".$file))
   {
    $dir_arr[]=$dir_arr[$x]."\\".$file;
   }
    echo "$i=>\"$file\",<br>";
    $file2 = strtolower($file);
    if(!(@rename($main_dir."\\".$file,$main_dir."\\".$file2)))
   {
       Print "<br>Rename Failed<br>";
   }
    $i++;
  }
 }
 closedir($handle);
 $x++;
}
?>
