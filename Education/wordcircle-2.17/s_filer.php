<?php

/*

CLASS
-----
FILER


PROPERTIES
----------
path


METHODS
-------
createFolder()
deleteFolder()
getFolderContents()
putInFolder()
removeFromFolder()
moveToFolder()


*/

class filer {

function dir_size($dir){
$totalsize=0;
if ($dirstream = @opendir($dir)) {
while (false !== ($filename = readdir($dirstream))) {
if ($filename!="." && $filename!="..")
{
if (is_file($dir."/".$filename))
$totalsize+=filesize($dir."/".$filename);

if (is_dir($dir."/".$filename))
$totalsize+=$this->dir_size($dir."/".$filename);
}
}
}
closedir($dirstream);
return $totalsize;
}

function recursiveDelete($dir){

if ($handle = @opendir($dir))
		  {
		     while (($file = readdir($handle)) !== false)
		     {
		        if (($file == ".") || ($file == ".."))
		        {
		           continue;
		        }
		        if (is_dir($dir . '/' . $file))
		        {
		           // call self for this directory
		           $this->recursiveDelete($dir . '/' . $file);
		        }
		        else
		        {
		           unlink($dir . '/' . $file); // remove this file
		        }
		     }
		     @closedir($handle);
		     rmdir ($dir);  
		  }

}


}

?>