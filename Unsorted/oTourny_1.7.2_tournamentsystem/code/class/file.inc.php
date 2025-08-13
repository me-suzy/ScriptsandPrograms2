<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  File/Folder Class

 */



 class folder {

  var $dir;      //writable file dir

  var $readonly; //disable write cmds

  var $files;    //array of files



  function folder($dir, $readonly = 0){

   $this->readonly = $readonly;

   $this->dir      = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir . "/"; //force to location of index

  }



  //query the file class

  function &file($id){

   if(!empty($this->files[$id])) return $this->files[$id];

   else return $this->files[$id] = new file($id, $this);

  }



  function save(){

   if(!empty($this->files))

    foreach($this->files as $file)

     if($file->data_mod && $file->file != '') $file->save();

  }

 }



 class file {

  var $folder;   //reference to folder class, file is kept in

  var $data;     //file's data

  var $data_mod; //if data has been modified

  var $file;     //file with location



  function file($id, &$folder){

   $this->folder = &$folder;

   $this->file   = $this->folder->dir . $id;

  }



  function get(){

   if($this->data != '') return $this->data;

   else return $this->data = $this->open();

  }



  function set($data){

   if($data == $this->data) return;



   $this->data_mod = 1;

   $this->data     = $data;

  }



  function open(){

   if($fp = @fopen($this->file, "r"))

    $data = @fread($fp, filesize($this->file));

   @fclose($fp);



   return $data;

  }



  function save(){

   if(!$this->data_mod || $this->folder->readonly) return;



   if($fp = @fopen($this->file, "w")){

    if(!fwrite($fp, $this->data)) $status = 1;

    else $status = 0;

   }

   @fclose($fp);



   return $status;

  }



  function delete(){

   $this->data = '';     //clear data

   $this->data_mod = 0;  //dont save



   @unlink($this->file); //delete it



   $this->file = '';     //clear file location

  }



 }



 //class made exlusively for files uploaded

 class file_uploaded {

  var $obj; //reference to $_FILE object

  var $err = false; //errors for file

  var $type; //type exploded

  var $file; //file location

   var $file_data; //file data - private



  //obj = object name in html

  function file_uploaded($obj){

   if(!isset($_FILES[$obj])) //check if file uploaded

    return $err = true;



   //check for shortcut hacks...etc

   if(!is_uploaded_file($_FILES[$obj]['tmp_name']))

    return $err = true; //not the uploaded file



   //keep error code but dont reference file

   if($_FILES[$obj]['error'] != 0) //check for errors

    return $err = $_FILES[$obj]['error'];



   //reference file object

   $this->obj =& $_FILES[$obj];



   //explode type

   $this->type = explode("/", $this->obj['type']);



   //set file out

   $this->file = &$this->obj['tmp_name'];

  }



  //retrieve

  function get(){

   if($this->file_data != '') return $this->file_data;

   else return $this->file_data = @fread(@fopen($this->file, "r"), @filesize($this->file));

  }



 }





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// OLD FILE CMDS

///  DEL ME LATER

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





 $file_dir = $_SERVER['DOCUMENT_ROOT'] . $loc_images; //dir for all files



 //saves file

 function file_save($id, $data){global $file_dir;

  if($fp = @fopen($file_dir.$id, "w")){

   if(!fwrite($fp, $data)) $status = 1;

   else $status = 0;

  }

  @fclose($fp);



  return $status;

 }



 //returns opened file source

 function file_open($id){global $file_dir;

  if($fp = @fopen($file_dir.$id, "r"))

   $status = @fread($fp, filesize($file_dir.$id));

  @fclose($fp);



  return $status;

 }



 //deletes file

 function file_del($id){global $file_dir;

  return @unlink($file_dir.$id);

 }



?>