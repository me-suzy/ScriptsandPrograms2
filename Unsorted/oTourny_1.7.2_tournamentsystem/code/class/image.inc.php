<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Image DB Protocol

 */



 class db_images extends db_table {

  var $images; //image array - holds reference to images



  function db_images(){

   //notify parent of db names and class

   parent::db_table("images", "id", "db_image");



   //reference class list

   $this->games =& $this->objs;

  }



  //retrieve a image

  function &image($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //find user

  function &find_image($name){

   $query = new db_cmd("select", "images", "id", "name LIKE '%".$name."%'", 1);



   return $this->image($query->data[0]["id"]);

  }



  //loads up the picture - (redirects to picture file)

  function load($id){

   if(is_numeric($id)) //index id

    $image = $this->image($id);

   else //name

    $image = $this->find_image($id);



   if($image->id > 0 && $id != ''){ //image is in db - should exist

    //redirect to correct location in image db

    header( "Location: " . $GLOBALS["loc_images"] . $image->file_name() );



    //update hit count

    $image->set("hits", $image->get("hits") + 1);

   }else //bad image

    header( "Location: ".$GLOBALS["loc_images_default"] ); //send default err

  }

 }



 class db_image extends db_obj {

  function db_image($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }



  //retrieves file name

  function file_name(){

   return $this->id . "." . $this->get("type");

  }



  //files name from user

  function name(){

   return $this->get("name");

  }



  //file's name picture.php?id=#

  function image(){

   return "picture.php?id=" . $this->id;

  }



  //file hits

  function hits(){

   return $this->get("hits");

  }



  //upload picture to db/save to file

   //$obj  = class file_uploaded

   //$name = optional override

  //return error codes

   // 1 - image isnt in db

   // 2 - bad upload file

   // 3 - not a image

   // 4 - empty image

  function upload($file, $name = ''){

   if(!($this->id > 0)) return 1; //cant save if this doesnt exist

   if($file->err)       return 2; //dont try to save with errors



   if($file->type[0] != "image") return 3; //only images allowed



   //load file into $data

   if(($data = $file->get()) == '') return 4; //empty image



   //del the image your overwriting

   file_del($this->file_name());



   $this->set(array(

     "name" => htmlchars( $name == '' ? $file->obj['name'] : $name ),

     "hits" => 0,

     "type" => $file->type[count($file->type) - 1], //get the files .type

     "size" => $file->obj['size']

    ));



   //save - should add size restrictions

   file_save($this->file_name(), $data);

  }

 }





 /*

  function scale_to_height ($filename, $targetheight) {

   $size = getimagesize($filename);

   $targetwidth = $targetheight * ($size[0] / $size[1]);

   return $targetwidth;

}



function scale_to_width ($filename, $targetwidth) {

   $size = getimagesize($filename);

   $targetheight = $targetwidth * ($size[1] / $size[0]);

   return $targetheight;

} */

?>