<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 6th September 2005                      #||
||#     Filename: directory.php                          #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package AdminCP
*/

define ('wbnews', true);
include "./global.php";
include $config['installdir']."/templates/".$theme['THEME_DIRECTORY']."/admin/theme_info.php";

if (!checkLogged($dbclass) === true)
    redirect($tpl, $themeInfo['redirect']['NOT_LOGGED_IN'], PAGE_LOGIN);
else
{
    /**
    *   
    *   @param string directory - The Directory name
    *   @param Array extensions - An Array of Valid Extensions
    *   @return Array
    */
    function getDir($directory, $extensions)
    {
        if (is_dir($directory) && is_readable($directory))
        {
            $list = array("getcwd" => $directory, "directory" => array(), "files" => array());
            
            $dh = opendir($directory);
            while (($file = readdir($dh)) !== false)
            {
                if ($file != '.' && $file != '..')
                    if (is_dir($directory . "/" . $file))
                        array_push($list['directory'], $directory . "/" . $file);
                    else
                    {
                        $extension = explode(".", $file);
                        $extension = $extension[sizeof($extension) - 1];
                        
                        if (in_array($extension, $extensions))
                            array_push($list['files'], str_replace($_SERVER['DOCUMENT_ROOT'], "http://" . $_SERVER['HTTP_HOST'], $directory . "/" . $file));
                    }
                else
                {
                    if ($file === "..")
                        if ($directory !== $_SERVER['DOCUMENT_ROOT'])
                            array_push($list['directory'], str_replace("/". basename($directory), "", $directory) . "/..");
                }
            }
            
            return $list;
        }
        else
            return getDir($_SERVER['DOCUMENT_ROOT'], $extensions); // Directory doesnt exist, back to doc_root
    }
    
    /**
    *   @param String cwd - The Current Directory
    *   @return String
    */
    function fileupload($cwd, $extensions)
    {
        if (is_writable($cwd))
        {
            if ($_FILES['img_file']['error'] != 0)
                return "Couldnt Upload File - Error in Uploading";
            else
            {
                $extension = explode(".", $_FILES['img_file']['name']);
                $extension = $extension[sizeof($extension) - 1];
                
                if (in_array($extension, $extensions))
                    if (move_uploaded_file($_FILES['img_file']['tmp_name'], $cwd."/".$_FILES['img_file']['name']))
                        return "File Uploaded";
                    else
                        return "Couldnt Upload File";
                else
                    return "Not a Valid file Type";
            }
        }
        else
            return "Directory doesnt have write Permissions enabled";
    }
    
    $files = getDir((isset($_GET['dir']) ? $_GET['dir'] : $_SERVER['DOCUMENT_ROOT']), array("gif", "jpg", "jpeg", "png"));
    
    // check if submit has been pressed, do file upload function
    $error = "";
    if (isset($_POST['submit']))
        $error = fileupload($files['getcwd'], array("gif", "jpg", "jpeg", "png")) . "<br />";
    
    //directories
    $dir = "";
    $numDir = sizeof($files['directory']);
    sort($files['directory']);
    
    for ($i = 0; $i < $numDir; $i++)
        $dir .= $tpl->replace($tpl->getTemplate("dirlist"), array("path" => (basename($files['directory'][$i]) == ".." ? substr($files['directory'][$i], 0, -3) : $files['directory'][$i]), "name" => basename($files['directory'][$i])));
    
    $file = "";
    $numFile = sizeof($files['files']);
    sort($files['files']);
    
    for ($i = 0; $i < $numFile; $i++)
        $file .= $tpl->replace($tpl->getTemplate("filelist"), array("path" => $files['files'][$i], "name" => basename($files['files'][$i])));
    
    $contents = array(
                      "getcwd" => $files['getcwd'],
                      "dirlist" => $dir,
                      "filelist" => $file,
                      "error" => $error
                      );
    
    $contents = array_merge($contents, $GLOBAL);
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('directory'), $contents));
    
}

?>
