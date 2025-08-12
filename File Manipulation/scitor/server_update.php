<?
/*
This file is used to replace every occurance of any string within any file on your server.  Simply copy the file into any directory and it will 
scan all files within that directory and its subdirectories replacing every occurance of the variable $oldserver with the value of $newserver.  
It is essential when moving to a new server or domain if you have often used full paths to other files or images.  It can also be used to 
update links to another website if that site changes its domain.  On large sites this will take a long time to run so you may need to update 
your Apache config files or your php.ini, or run it in subdirectories first.
*/

$oldserver="http://www.oldserver.com";//name of the old webserver root folder - after running this script, this name will be replaced
$newserver="http://www.ph-solutions.net";//name of the new webserver root folder

$directoriestoscan  = array(realpath('.'));
$directoriesscanned = array();
while (count($directoriestoscan) > 0) 
{
  foreach ($directoriestoscan as $directorykey => $startdir) 
	{
   if ($dir = @opendir($startdir)) {
     while (($file = readdir($dir)) !== false) 
		 {
       if (($file != '.') && ($file != '..')) 
			 {
         $realpathname = realpath($startdir.'/'.$file);
         if (is_dir($realpathname)) 
						 {
							 if (!in_array($realpathname, $directoriesscanned) && !in_array($realpathname, $directoriestoscan)) 
									 {
										 $directoriestoscan[] = $realpathname;
									 }
						 } 
				 elseif (is_file($realpathname)) 
						 {
							 $filesindir[] = $realpathname;
						 }
       }
     }
     closedir($dir);
   }
   $directoriesscanned[] = $startdir;
   unset($directoriestoscan[$directorykey]);
  }
}

$filesindir = array_unique($filesindir);
sort($filesindir);
foreach ($filesindir as $filename) 
{
echo "$filename";
$fp=fopen("$filename",'r');//open the file as read only
$output=fread($fp,filesize($filename));//get out the entire contents into the variable $output
fclose ($fp);//close the file
$pos1 = strstr($output,$oldserver);//find if the name of the old server appears anywhere in the file
if ($pos1)
		{
		$output=str_replace($oldserver,$newserver,$output);//replace the name of the old server with the name of the new server
		$fp=fopen("$filename",'w+');//reopen the old file in 'write' mode - deleting the entire contents
		fwrite($fp,"$output");//write the same details into the file with the new server name
		fclose ($fp);
		echo "<b> updated</b>";//list of all files updated - this file will appear in this list
		$pos1="";
		}
echo "<br>";
}
?>