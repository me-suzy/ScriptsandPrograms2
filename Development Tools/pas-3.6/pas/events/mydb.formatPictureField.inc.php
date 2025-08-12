<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
/**
  *   Event Mydb.formatPictureField.
  *
  * Format a picture field and upload the file
  * <br>- param array filefield name of the fields that contain file name.
  * <br>- param array filedirectoryuploded array directory of where to upload the file.
  * <br>- param array filenameuploded array with the optional name on the server.
  * <br>- param file userfile name of the field file.
  * <br>Options :
  * <br>-param string errorpage page to display the errors
  * @package PASEvents
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0	
  */
  global $strUnabletoSave;
  if (!isset($strUnabletoSave)) {
      $strUnabletoSave = "Was unable to save the file :";
  }
  $strUnabletoSave .= $userfile_name[$fidx] ; 

    function setnewfilename($path, $filename, $id=0) {
        if (!ereg("/$", $path)) { $path .= "/";}
        if (file_exists($path.$filename)) {
            $id++;
            $filename = "n".$id."-".$filename;
            setnewfilename($path, $filename, $id);
        } 
        return $filename;
    }

  //while (list($key, $val) = each($fields)) {
    $this->setLogRun(false);
    //global $userfile, $userfile_name ;
    for($fidx=0;$fidx<count($filefield);$fidx++) {
        $userfile = $_FILES['userfile']['tmp_name'][$fidx];
        $userfile_name = $_FILES['userfile']['name'][$fidx];
        $this->setLog("\nUserfile:".$userfile_name);
      if($userfile != "none") {
        if($userfile_name=="") {
           $val="";
        } else {
          $filepatharray = explode("/", $userfile_name) ;
          $numsubdir = count($filepatharray) ;
          if ($numsubdir >1) {
            $userfile_name = $filepatharray[$numsubdir-1] ;
          }
          $iname =  $filenameuploaded[$fidx] ;
          $ipath = $filedirectoryuploaded[$fidx] ;
          if($iname!="") { 
           $val= strrchr($iname,".");
           $val = setnewfilename($ipath, $val);
          } else { 
            $val=$userfile_name;
          }
//            $val = setnewfilename($ipath, $val);
            $destpath="$ipath/$val";
//            $userfile[$fidx];
          if(!is_uploaded_file($userfile)) {
            $this->setError("<b>File Upload</b>".$this->getErrorMessage()) ;
            if (strlen($errorpage)>0) {
              $urlerror = $errorpage;
            } else {
              $urlerror = $this->getMessagePage() ;
            }
            $disp = new Display($urlerror);
            $disp->addParam("message", $strUnabletoSave) ;
            $this->updateparam("doSave", "no") ;
            $this->setDisplayNext($disp) ;
          } else {
                move_uploaded_file($userfile, $destpath);
          }
          $fields[$filefield[$fidx]] = $val ;
          $this->updateparam("fields", $fields) ;
        }
      }
    }
  //}
  
  
?>