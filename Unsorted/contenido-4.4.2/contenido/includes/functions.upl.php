<?php

/******************************************
* File      :   Defines the 'upl' related functions
* Project   :   Contenido
* Descr     :   Misc. functions for area
*               upl
*
* Author    :   Paul Eppner, Olaf Niemann
* Created   :   2002
* Modified  :   01.04.2003
*
* © four for business AG
******************************************/


function uplresize($image,&$x,&$y,$resize,$aspectratio) {
        global $con_cfg, $client, $path, $cfgClient;

        error_reporting(0);
        $types = array (1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
//echo "image: $image <br>";
        $cachedir = substr($image,0,strrpos($image,'/') + 1).$cfgClient[$client]["path"]["frontend"].$con_cfg['PathFrontendTmp'];
        $cachedir = $cfgClient[$client]["path"]["frontend"].$con_cfg['PathFrontendTmp'];
//echo "cachedir: $cachedir<br>";
//echo "cachedir2: $cachedir2<br>";
        if (!is_dir ($cachedir)) {
                 mkdir($cachedir, 0777);
        } else {
                system ("chmod 0777 ".$cachedir);
        }

        if (!isset ($resize) || !ereg ('^[0|1]$', $resize, $regs)) {
                 $resize = 0;
        }

        if (!isset($aspectratio) || !ereg ('^[0|1]$', $aspectratio, $regs)) {
                 if (isset($x) && isset($y)) {
                         $aspectratio = 1;
                 } else {
                         $aspectratio = 0;
                 }
        }

        $imagedata = getimagesize($image);

        if (!isset ($x)) {
                 $x = floor ($y * $imagedata[0] / $imagedata[1]);
         }

        if (!isset ($y)) {
                 $y = floor ($x * $imagedata[1] / $imagedata[0]);
         }

        if (($imagedata[0] > $x || $imagedata[1] > $y) || (($imagedata[0] < $x || $imagedata[1] < $y) && $resize)) {
                if ($aspectratio && isset ($x) && isset ($y)) {
                         if ($imagedata[0] > $imagedata[1]) {
                                $y = floor ($x * $imagedata[1] / $imagedata[0]);
                        } else if ($imagedata[1] > $imagedata[0]) {
                                $x = floor ($y * $imagedata[0] / $imagedata[1]);
                        }
                }
        } else {
                $x = $imagedata[0];
                $y = $imagedata[1];
        }

        $thumbfile = substr ($image, strrpos ($image, '/') + 1);
// Wir wohl gar nicht benutzt???
        $filepath = $cfgClient[$client]['upl']['htmlpath'].$path.$thumbfile;

        if (function_exists(imagetypes)) {
                if (file_exists ($cachedir.$thumbfile)) {
                        $thumbdata = getimagesize ($cachedir.$thumbfile);
                        if ($thumbdata[0] == $x && $thumbdata[1] == $y) {
                                 $iscached = true;
                         }
                }
                if (!$iscached) {
                        if (($imagedata[0] > $x || $imagedata[1] > $y) || (($imagedata[0] < $x || $imagedata[1] < $y) && $resize)) {
                                $makethumb = true;
                        } else {
                                $makethumb = false;
                        }
                } else {
                        $makethumb = false;
                }

                eval ('
                if (!(imagetypes() & IMG_'.strtoupper($types[$imagedata[2]]).')) {
                         $makethumb = false;
                }
                ');

                if ($makethumb)        {
                        $image = call_user_func("imagecreatefrom".$types[$imagedata[2]], $image);
                        $thumb = imagecreate ($x, $y);
                        imagecopyresized ($thumb, $image, 0, 0, 0, 0, $x, $y, $imagedata[0], $imagedata[1]);
                        call_user_func("image".$types[$imagedata[2]], $thumb, $cachedir.$thumbfile);
                        imagedestroy ($image);
                        imagedestroy ($thumb);
                        $filepath = $cfgClient[$client]['upl']['htmlpath'].$path.$con_cfg['PathFrontendTmp'].$thumbfile;
                } else {
                        if ($iscached) {
//                                 $filepath = $cfgClient[$client]['upl']['htmlpath'].$path.$con_cfg['PathFrontendTmp'].$thumbfile;
                                 $filepath = $cfgClient[$client]['upl']['htmlpath'].$path.$thumbfile;
                         } else {
                                 $filepath = $cfgClient[$client]['upl']['htmlpath'].$path.$thumbfile;
                         }
                }
        }
return $filepath;
}

function uplRecursiveRmDirIfEmpty($dir) {

    global $notification;

    if(!is_dir($dir)) {
            return 0;
    }
    $directory = opendir($dir);
    readdir($directory);

    while(false !== ($dir_entry = readdir($directory))) {
            if($dir_entry != "." && $dir_entry != "..") {
                    if (is_dir($dir."/".$dir_entry)) {
                            uplrecursivermdir($dir."/".$dir_entry);
                    } else {
                            $notification->displayNotification("warning", "Im Verzeichnis $dir sind noch Dateien vorhanden. L&ouml;schen nicht m&ouml;glich.");
                            //unlink($dir."/".$dir_entry);
                    }
            }
    }
    closedir($directory);
    unset($directory);
    if (@rmdir($dir)) {
            return 1;
    } else {
            return 0;
    }
}

function uplHasFiles($dir) {

    if(!is_dir($dir)) {
            return false;
    }
    $directory = opendir($dir);
    readdir($directory);

    $ret = false;

    while(false !== ($dir_entry = readdir($directory))) {
            if($dir_entry != "." && $dir_entry != "..") {
                            $ret = true;
            }
    }
    closedir($directory);
    unset($directory);

    return ($ret);
}

function uplrecursivermdir($dir) {

        if(!is_dir($dir)) {
                return 0;
        }
        $directory = opendir($dir);
        readdir($directory);

        while(false !== ($dir_entry = readdir($directory))) {
                if($dir_entry != "." && $dir_entry != "..") {
                        if (is_dir($dir."/".$dir_entry)) {
                                uplrecursivermdir($dir."/".$dir_entry);
                        } else {
                                unlink($dir."/".$dir_entry);
                        }
                }
        }
        closedir($directory);
        unset($directory);
        if (rmdir($dir)) {
                return 1;
        } else {
                return 0;
        }
}

function uplupload($path,$userfile,$userfile_name,$userfile_size) {
        global $cfgClient, $client, $cfg, $db, $HTTP_POST_FILES;

        $ArrayCount = count($userfile);
        for ($i=0; $i<$ArrayCount; $i++)
        {
                if ($userfile_name[$i] && $userfile_size[$i]) {

                        $userfile_name[$i] = strtr($userfile_name[$i],'ÄÖÜäöüßé?>\/:\"*<>|#+','AOUaouse--------------');
                        //rplace space

                        $userfile_name[$i] =preg_replace("/\s/","",$userfile_name[$i]);

                        $file_type = substr(strrchr ($userfile_name[$i], "."),1);
                        $file_type = strtolower($file_type);
                        $userfile_name[$i]=substr_replace($userfile_name[$i],$file_type,strrpos($userfile_name[$i],".")+1);


                        if (ereg("".$cfgClient['upl']['forbidden']."",strtolower($userfile_name[$i]))) {
                                $errno = "0705";
                        } else {

                                if (@move_uploaded_file($userfile[$i],$cfgClient[$client]['upl']['path'].$path.$userfile_name[$i])) {
										chmod($cfgClient[$client]['upl']['path'].$path.$userfile_name[$i],0644);

                                        $file_time = filemtime($cfgClient[$client]['upl']['path'].$path.$userfile_name[$i]);
                                        //$file_time = date("Y-m-d H:i:s", $file_time);


                                        $sql = "SELECT idupl
                                                FROM ".$cfg["tab"]["upl"]."
                                                WHERE idclient='$client' AND filename='$userfile_name[$i]' AND dirname='$path' AND filetype='$file_type'";

                                        $db->query($sql);
                                        if ($db->next_record()) {

                                        } else {
                                                $sql = "INSERT INTO ".$cfg["tab"]["upl"]."
                                                                (idupl, idclient, filename, dirname, filetype, size, description, created, lastmodified)
                                                                VALUES
                                                                ('".$db->nextid($cfg["tab"]["upl"])."', '$client','$userfile_name[$i]','$path','$file_type','$userfile_size[$i]','', now(), now())";

                                                $db->query($sql);

                                        }

                                }

                                else {
                                        $errno = "0703";
                                }
                        }
                }
        }

        if ( isset($errno) ){
                return $errno;
        }
}





function savefile($path,$file) {
        global $client, $cfg, $db;

        $file_type = substr(strrchr ($file, "."),1);
        $filesize = @filesize($cfgClient[$client]['upl']['path'].$path.$file);

        $sql = "INSERT INTO ".$cfg["tab"]["upl"]."
               (idupl, idclient, filename, dirname, filetype, size, description, created, lastmodified)
               VALUES
               ('".$db->nextid($cfg["tab"]["upl"])."', '$client','$file','$path','$file_type','".$filesize."','', now(), now())";
        $db->query($sql);

}





function uplrename($path,$edit,$newfile)
{
        global $client, $db, $cfg;


        $file_type = substr(strrchr ($newfile, "."),1);



        $sql = "UPDATE ".$cfg["tab"]["upl"]."
               SET description='$newfile'
               WHERE filename='$edit' AND dirname='$path' AND idclient='$client'";

        $db->query($sql);


}

function upldelete($path, $files) {
        global $cfgClient, $client, $con_cfg, $db, $cfg;

        $path = $cfgClient[$client]['upl']['path'].$path;

        if (!is_array($files)) {
            $tmp[] = $files;
            unset($files);
            $files = $tmp;
        }

        $ArrayCount = count($files);
        for ($i=0; $i<$ArrayCount; $i++) {
                if (is_dir($path.urldecode($files[$i]))) {
//                        uplrecursivermdir($path.urldecode($files[$i]));
                        uplRecursiveRmDirIfEmpty($path.urldecode($files[$i]));

                        $sql = "DELETE FROM ".$cfg["tab"]["upl"]." WHERE dirname='".$files[$i]."/'";
                        $db->query($sql);
                } else {
                        if (file_exists ($cfgClient[$client]["path"]["frontend"].$con_cfg['PathFrontendTmp'].urldecode($files[$i]))) {
                                unlink($cfgClient[$client]["path"]["frontend"].$con_cfg['PathFrontendTmp'].urldecode($files[$i]));
                        }

                        $file_name = urldecode($files[$i]);
                        $sql_dirname = str_replace($cfgClient[$client]['upl']['path'], '', $path);

                        unlink($path.$file_name);

                        $sql = "SELECT idupl
                                          FROM ".$cfg["tab"]["upl"]."
                                          WHERE
                                          idclient='$client'
                                          AND
                                          filename='$file_name'
                                          AND
                                          dirname='$sql_dirname'";
                        $db->query($sql);
                        if ($db->next_record()) {
                                $sql = "DELETE FROM ".$cfg["tab"]["upl"]." WHERE idupl='".$db->f("idupl")."'";

                                $db->query($sql);
                        }

                }
        }
}

function uplmkdir($path,$name) {
        global $cfgClient, $client, $action;
        $name = strtr($name,'ÄÖÜäöüßéè?>\/:\"*<>|#+!"§$%&()={[]}~;,²³`´@^°','AOUaousee............-.......................');
        $name = strtr($name, "'", ".");
        if(file_exists($cfgClient[$client]['upl']['path'].$path.$name)) {
                $action = "upl_mkdir";
                return "0702";
        } else {
                $oldumask = umask(0);
                @mkdir($cfgClient[$client]['upl']['path'].$path.$name,0775);
                umask($oldumask);
        }
}


function uplDirectoryListRecursive ($currentdir, $startdir=NULL, $files=array(), $depth=-1, $pathstring="") {
    $depth++;

    $unsorted_files = array();

    if (chdir ($currentdir) == false)
    {
    	return;
    }

    // remember where we started from
    if (!$startdir) {
        $startdir = $currentdir;
    }
    $d = opendir (".");

    //list the files in the dir
    while ($file = readdir ($d)) {
        if ($file != ".." && $file != ".") {
            if (is_dir ($file)) {
                $unsorted_files[] = $file;
            } else {
            }
        }
    }
    if (is_array($unsorted_files)) sort($unsorted_files);
    $sorted_files = $unsorted_files;

    if(is_array($sorted_files)) {
        foreach ($sorted_files as $file) {
            if ($file != ".." && $file != ".") {

                if ((filetype(getcwd()."/".$file) == "dir") &&
                    (opendir(getcwd()."/".$file) !== false)) { 
                    $a_file['name']  = $file;
                    $a_file['depth'] = $depth;
                    $a_file['pathstring']  = $pathstring.$file.'/';;

                    $files[] = $a_file;
                    // If $file is a directory take a look inside
                    $files = uplDirectoryListRecursive (getcwd().'/'.$file, getcwd(), $files, $depth, $a_file['pathstring']);
                } else {
                    // If $ file is not a directory then do nothing
                }
            }
        }
    }

    closedir ($d);
    chdir ($startdir);
    return $files;
}

?>
