<?
/*
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/

//USEFULL FUNCTIONS
function icon($filename, $isdir = 0, $returnType = 0, $dirSize = 0, $public = 0, $thumb = false, $small = false) {
	global $db, $config;
	
	if (!$isdir) {
		$ext = getExtension($filename);
	}	else {
		$ext = "";
	}

	if($small) {
		$icoDir = "images/icons/small";
	} else {
		$icoDir = "images/icons/big";
	}

	if ($ext == "htm" || $ext == "html" || $ext == "shtml" || $ext == "shtm") {
		$icon = "$icoDir/html.gif";
		$type = "HTML Document";
	} elseif ($ext == "xml") {
		$icon = "$icoDir/xml.gif";
		$type = "XML File";
	} elseif ($ext == "txt") {
		$icon = "$icoDir/txt.gif";
		$type = "Text Document";
	}elseif ($ext == "log") {
		$icon = "$icoDir/txt.gif";
		$type = "Log File";
	}elseif ($ext == "zip") {
		$icon = "$icoDir/zip.gif";
		$type = "Zip Archive File";
	} elseif ($ext == "gz" || $ext == "rar" || $ext == "cab" || $ext == "ace" || $ext == "bz") {
		$icon = "$icoDir/archive.gif";
		$type = "Archive File";
	} elseif ($ext == "doc" || $ext == "rtf") {
		$icon = "$icoDir/doc.gif";
		$type = "Rich Text Document";
	} elseif ($ext == "pdf") {
		$icon = "$icoDir/pdf.gif";
		$type = "Adobe Acrobat Document";
	} elseif ($ext == "gif") {
		$icon = "$icoDir/gif.gif";
		$type = "GIF Image";
	}elseif ($ext == "bmp") {
		$icon = "$icoDir/bmp.gif";
		$type = "Bitmap Image";
	} elseif ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
		if ($thumb && extension_loaded("gd") == true) {
			global $dir;
			$icon = "thumb/?dir=$dir&img=".urlencode($filename)."";
		} else {
			$icon = "$icoDir/jpg.gif";
		}
		$type =  strtoupper($ext)." Image";
	} elseif ($ext == "psd") {
		$icon = "$icoDir/psd.gif";
		$type = "Adobe Photoshop Image";
	} elseif ($ext == "xar") {
		$icon = "$icoDir/xar.gif";
		$type = "Xara Image";
	} elseif ($ext == "cdr") {
		$icon = "$icoDir/cdr.gif";
		$type = "Corel Draw Image";
	}  elseif ($ext == "css") {
		$icon = "$icoDir/css.gif";
		$type = "Style Sheet File";
	} elseif ($ext == "fla") {
		$icon = "$icoDir/fla.gif";
		$type = "Flash Source File";
	} elseif ($ext == "swf") {
		$icon = "$icoDir/swf.gif";
		$type = "Flash File";
	}
	 elseif ($ext == "xls") {
		$icon = "$icoDir/xls.gif";
		$type = "Excel File";
	} elseif ($ext == "qxd") {
		$icon = "$icoDir/qxd.gif";
		$type = "Quark Xpress File";
	} elseif ($ext == "js") {
		$icon = "$icoDir/js.gif";
		$type = "JavaScript File";
	} elseif ($ext == "asp") {
		$icon = "$icoDir/asp.gif";
		$type = "ASP Script File";
	} elseif ($ext == "php" || $ext == "php3" || $ext == "phtml") {
		$icon = "$icoDir/php.gif";
		$type = "PHP Script File";
	} elseif ($ext == "cgi" || $ext == "pl") {
		$icon = "$icoDir/txt.gif";
		$type = "Perl Script File";
	} elseif ($ext == "tmp" || $ext == "temp") {
		$type = "Temporary File";
	} elseif ($ext == "inc") {
		$icon = "$icoDir/txt.gif";
		$type = "Include File (PHP)";
	} elseif ($ext == "mp3" || $ext == "wav" || $ext == "mid" || $ext == "mod" || $ext == "wma" || $ext == "wmv") {
		$icon = "$icoDir/audio.gif";
		$type = "Audio File";
	} elseif ($ext == "avi" || $ext == "mpg" || $ext == "asf" || $ext == "mpeg") {
		$icon = "$icoDir/video.gif";
		$type = "Video File";
	} elseif ($ext == "sql") {
		$icon = "$icoDir/txt.gif";
		$type = "SQL Database file";
	} else {
		if ($isdir) {
			$icon = "$icoDir/folder.gif";
			$type = "Folder";
		} else {
			$type = strtoupper($ext)." File";
		}
	}
	if ($returnType) {
		return $type;
	} else {
		if (!$icon) {
			$icon = "$icoDir/generic.gif";
		}
		return $icon;
	}
}

function getFileSize ($filesize = 0) {
/* Filesize (MB, KB, Bytes) */
	if ($filesize > 1024 && $filesize < 1048576) {
		$filesize = round($filesize/1024);
		$filesize .= " KB";
	} elseif ($filesize > 1048576) {
		$filesize = round(($filesize/1024)/1024, 2);
		$filesize .= " MB";
	} elseif ($filesize > 0) {
		$filesize = $filesize;
		$filesize .= " Bytes";
	} else {
		$filesize = "Empty";
	}
	return $filesize;
}



function getExtension($filename) {
	$m = explode(".", $filename);
	$sizet = sizeof($m)-1;
	if ($sizet == "0") {
		$ext = "";
	} else {
		$ext = strtolower($m[$sizet]);
	}
	return $ext;
}


##############################################
function rec_copy($from_path, $to_path)
{
 if (!is_dir($to_path))
   mkdir($to_path, 0777);
 $this_path = getcwd();
 if (is_dir($from_path))
 {
   chdir($from_path);
   $handle = opendir('.');
   while (($file = readdir($handle)) !== false)
   {
     if (($file != ".") && ($file != ".."))
     {
       if (is_dir($file))
       {
         rec_copy ($from_path."/".$file,  $to_path."/".$file);
         chdir($from_path);
       }
       if (is_file($file))
       {
         copy($from_path."/".$file, $to_path."/".$file);
       }
     }
   }
   closedir($handle); 
 }
}



function create_path($to_path)
{
$path_array = explode('/', $to_path);  // split the path by directories
$dir='';                 // start with empty directory
foreach($path_array as $key => $val) {
 // echo "$key => $val\n";
if (strpos($val, ':')) {  // if it's not a drive letter
	$dir .= $val;
} else {
	$dir .= '/'. $val;
}
	   if (!is_dir($dir)) {
	     // echo "Not a dir: $dir\n";
		     if (!mkdir($dir, 0777)){
		     // echo "Failed creating directory: $dir\n";
			    return false;
		    } else{
		    //  echo "Created directory: $dir\n";
			  return true;
			}
		  
	     }
  // }
 }
}



function delete_dir ($del_path)
{
//set path to current dir
$this_path = getcwd();
if (is_dir($del_path))
{
//open dir, read files
chdir($del_path);
$handle=opendir('.');
while (($file = readdir($handle))!==false) 
{
if (($file != ".") && ($file != ".."))
{
if (is_dir($file))
{ //do recursive stuff if the "file" is a dir
delete_dir($file."/");
}
if (is_file($file))
{ //delete all files in the dir
chdir($this_path);
unlink("$del_path/$file");
chdir($del_path);
} 
}
}
closedir($handle); //close dir
}
//go to where we know
chdir($this_path);
//replace the web path with the dir path and remove the now empty dir
rmdir($del_path);
return;
}



function prepUrl($url) {
	//for url
	return htmlentities(urlencode($url));
}

function safestr($str, $post=true) {
	$str = ereg_replace("\\\{2,}", "\\", $str);
	if ($post) {
		return $str;
	} else {
		return ereg_replace("\\\{2,}", "\\", addslashes($str));
	}
}

function safePath($dir) {
	$dir = trim($dir);
	$dir = urldecode($dir);
	//make all backslashes slashes
	$dir = str_replace("\\", "/", $dir);
	$dir = ereg_replace("%2F", "/", $dir);
	//multiple slashes to single slahes
	$dir = ereg_replace("/{2,}", "/", $dir);
	//strip trailing slash
	if (substr($dir, -1) == "/") {
		$dir = substr($dir, 0, strlen($dir)-1);
	}
	//do not allow going up with ../
	$dir = ereg_replace("/\.+", "", $dir);
	$dir = ereg_replace("^\.+/", "/", $dir);
	//strip /.. from the end
	$dir = ereg_replace("/\.+$", "", $dir);
	return $dir;
}

function safeFilename($filename) {
	$filename = stripslashes($filename);
	$filename = trim($filename);
	$filename = urldecode($filename);
	//strip all backslashes and slashes
	$filename = str_replace("\\", "", $filename);
	$filename = str_replace("\"", "", $filename);
	$filename = str_replace("/", "", $filename);
	return $filename;
}



function superChmod($file, $mode) {
	if (file_exists($file)) {
		$code = "chmod(\"".$file."\", 0".$mode.");";
		eval($code);
		if (getperms($file) != $mode) {
			$code = "chmod(\"".$file."\", ".$mode.");";
			eval($code);
			if (getperms($file) != $mode) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	} else {
		echo "chmod error: file \"$file\" doesn't exists";
		return false;
	}
}

function getperms($file) {
clearstatcache();
return substr(sprintf("%o",fileperms($file)),-3);
}


function getDirList ($dirName, $list) {
	$d = dir($dirName);
	while($entry = $d->read()) {
		if ($entry != "." && $entry != "..") {
			if (is_dir($dirName."/".$entry)) {
			$list[] = $dirName."/".$entry;
				getDirList($dirName."/".$entry, $list);
			} else {
			$list[] = $dirName."/".$entry;
				//echo $dirName."/".$entry."\n";
			}
		}
	}
	return $list;
	$d->close();
}



function dirsize($dir) {
    $size = -1;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." and $file != "..") {
                $path = $dir."/".$file;
                if (is_dir($path)) {
                    $size += dirsize("$path/");
                }
                elseif (is_file($path)) {
                    $size += filesize($path);
                }
            }
        }
        closedir($dh);
    }
    return $size;
}


function linkToDir($dir) {
	$splited = split("/", $dir);
	$i = sizeof($splited);
	for ($j = 0 ; $j < $i-1 ; $j++) {
	$upOneDirAddr .= $splited[$j];
		if ($j == $i-2) {
		} else {
		$upOneDirAddr .= "/";
		}
	}
	return "?dir=".urlencode($upOneDirAddr);
}

function isCfgOn($option) {
	if (strtolower(ini_get($option)) == "on" || ini_get($option) == "1" || strtolower(ini_get($option)) == "yes") {
		return true;
	} else {
		return false;
	}
}


//END USEFULL FUNCTIONS
?>