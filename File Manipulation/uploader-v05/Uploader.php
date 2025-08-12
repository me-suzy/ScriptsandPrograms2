<?php
////////////////////////////////////////////////////////////////////////

require_once "../forms/Forms.php";

////////////////////////////////////////////////////////////////////////
/*

Handles file uploads 
    

For the lastest version go to:
http://www.phpclasses.org/browse.html/package/949.html

This class generates the form elements for a file upload and stores the
uploaded files in a directory. 
- You restrict the file size and the allowed file types easily.
- The uploaded file information is returned in an associative array.


This class uses my form generating class, Form
(http://www.phpclasses.org/browse.html/package/931.html)
    
////////////////////////////////////////////////////////////////////////
    
    CONSTRUCTOR:
        function Uploader() 
    PUBLIC:
        function openForm($action, $add_on = '') 
        function fileField($size = -1, $fileSize=1048576, $accept='text/*') 
        function closeForm($addSubmitButton = true) 
        function uploadTo($path, $overwrite=false, $allowedTypeArray=null) 
        function wasSubmitted() {
        function debug() 
    PRIVATE:
        function _error($msg) 

    PUBLIC VARS:
        var $error
    
////////////////////////////////////////////////////////////////

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.
    
    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.
    
    You should have received a copy of the GNU Lesser General Public
    License along with this library; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
////////////////////////////////////////////////////////////////////////
/**
* Class for handling file uploads 
*
* @author	    Lennart Groetzbach <lennartg@web.de>
* @copyright	Lennart Groetzbach <lennartg@web.de> - distributed under the LGPL
* @version 	    0.5 - 2003/01/13
*/

////////////////////////////////////////////////////////////////////////
/**
* @access   public
*/
class Uploader {
    
////////////////////////////////////////////////////////////////////////
/**
* maximum file size
* 
* @access   private
* @type     Integer
*/
var $_size = 1048576;

/**
* errors
* 
* @access   public
* @type     String
*/
var $error = '';

////////////////////////////////////////////////////////////////////////
/**
* Constructor
* 
* @access   public
*/
function Uploader() {
    if (!ini_get("safe_mode")) {
    } else {
        die ("Turn 'safemode' in your php.ini off!");
    }
}
////////////////////////////////////////////////////////////////////////
//PUBLIC
////////////////////////////////////////////////////////////////////////
/**
* Returns debug information
*
* @access   public
*
* @return   String      debug information
*/
function debug() {
    $str = '';
    $str .='safemode: "' . (ini_get('safe_mode') ? 'on" (bad idea!)' : 'off"') . "\n";
    $str .='upload_tmp_dir: "' . ini_get('upload_tmp_dir') . "\"\n";
    $str .='local TMP dir: "' . getenv('TMP') . "\"\n";
    $str .='local TEMP dir: "' . getenv('TEMP') . "\"\n";
    $str .='upload_max_filesize: "' . ini_get("upload_max_filesize") . "\"\n";
    $str .='post_max_size: "' . ini_get("post_max_size") . "\"\n";
    return $str;
}

////////////////////////////////////////////////////////////////////////
/**
* Returns open form tag
*
* @access   public
*
* @param    String      $action     the target page
* @param    String      $add_on     add on for the tag
*
* @return   String      form tag
*/
function openForm($action, $add_on = '') {
    return Forms::openForm($action, 'post', '', true, '', $add_on);
}

////////////////////////////////////////////////////////////////////////
/**
* Returns input file field tag
*
* @access   public
*
* @param    Integer     $fileSize
* @param    Integer     $size
* @param    String      $accept
* @param    String      $add_on
*
* @return   String      input file tag
*/
function fileField($fileSize=1048576, $size=-1, $accept='text/*', $add_on='') {
    return Forms::fileField('', '', $fileSize, $size, $accept, $add_on);
}

////////////////////////////////////////////////////////////////////////
/**
* Returns close form tag and internal tags
* MUST BE CALLED!
*
* @access   public
*
* @param    Boolean     $addSubmitButton
*
* @return   String      closing form tags
*/
function closeForm($addSubmitButton = true) {
    return Forms::hidden('_uploaded', 'true') ."\n"
            . Forms::hidden('MAX_FILE_SIZE', $this->_size) ."\n"
            . ($addSubmitButton ? Forms::submitButton() : '') 
            . Forms::closeForm();
}

////////////////////////////////////////////////////////////////////////
/**
* Uploads files
*
* @access   public
*
* @param    String      $path               dir to move files to
* @param    Boolean     $overwrite          overwrite existing file?   
* @param    Array       $allowedTypeArray   array of allowed file types, if empty all files are allowed
*
* @return   Array       array with file information
*/
function uploadTo($path, $overwrite=false, $allowedTypeArray=null) {
    // fix path
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') {
        $path .= '/';
    }
    // does upload path exists?
    if ((file_exists($path)) && (is_writable($path))) {
        // for all files
        $res = array();
        // get the file list
        if (@$_FILE) {
            $files = & $_FILE;
        } else {
            $files = & $GLOBALS['HTTP_POST_FILES'];
        }
        // for all files...
        foreach($files as $key => $file) {
            // does the file exist?
            if (!@$file['error'] && $file['size'] && $file['name'] != '') {
                // is the file type allowed?
                if (($allowedTypeArray == null) || (@in_array($file['type'], $allowedTypeArray))) {
                    // is it really an uploaded file?
                    if (is_uploaded_file($file['tmp_name'])) {
                        // does file exists?
                        $exists = file_exists($path . $file['name']);
                        // overwrite file?
                        if ($overwrite || !$exists) {
                            // move file to new destination
                            move_uploaded_file($file['tmp_name'], $path . $file['name']);
                            // store name, path, type and size information
                            array_push ($res, array('name' => $file['name'], 'full_path' => $path . $file['name'], 'type' => $file['type'], 'size' => $file['size'], 'overwritten' => $exists));
                        } else {
                            $this->error .= $this->_error("File \"" . $file['name'] . "\" already exists!");
                        }
                    } else {
                        $this->error .= $this->_error("File \"" . $file['name'] . "\" is not a file!");
                    }
                } else {
                    $this->error .= $this->_error("Content Type \"" .  $file['type'] . "\" for file \"".$file['name']."\" not allowed!");
                }
            } else {
                if (@$file['error'] && $file['error'] != 4) {
                    $this->error .= $this->_error("File \"" .  $file['name'] . "\" does not exist!");
                }
            }
        }
        return $res;
    }
    $this->_error("Path \"$path\" does not exist or is not writable!");
    return false;
}

////////////////////////////////////////////////////////////////////////
/**
* Checks if an upload was made
*
* @access   public
* @return   boolean
*/
function wasSubmitted() {
    return (@$GLOBALS['HTTP_POST_VARS']['_uploaded'] == "true");
}

////////////////////////////////////////////////////////////////////////
// PRIVATE
////////////////////////////////////////////////////////////////////////
/**
* Generates error message
*
* @access   private
* @param    String      $msg
* @return   String
*/
function _error($msg) {
    $this->error .= date('Y-m-d H:i:s') . ' | ' . basename($GLOBALS['HTTP_SERVER_VARS']['PHP_SELF'])  . ' | ' . $msg . "\n";
}

////////////////////////////////////////////////////////////////////////
}
////////////////////////////////////////////////////////////////////////
?>