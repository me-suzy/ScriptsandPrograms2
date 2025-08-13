<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_image_modify($imageid){global $apanel, $tpl, $images;

  $image = &$images->image($imageid);



  $tpl->assign(array(

    "IMG_NAME"         => ($image->id > 0) ? $image->name() : '',

    "IMG_ID"           => ($image->id > 0) ? $image->id     : '',

    "LINK_SAVE"        => "?page=admin&cmd=images&cmdd=save&imageid=".$image->id,

    "FIELD_NAME_MAX"   => "255",

    "FIELD_NAME_NAME"  => "imagename",

    "FIELD_NAME_VALUE" => $image->name(),

    "FIELD_MAX_NAME"   => "MAX_FILE_SIZE",

    "FIELD_MAX_VALUE"  => "1000000",

    "FIELD_FILE_NAME"  => "userfile"

   ));



  $apanel->set_cnt("ap_img_mod.tpl", 1);

 }



 switch($cmdd){

  case "del":

   $image = &$images->image($_GET["imageid"]);

   $image->delete();



   echo write_refresh("?page=admin&cmd=images",0);

   break;

  case "mod":

   write_image_modify($_GET["imageid"]);

   break;

  case "create":

   write_image_modify(-1);

   break;

  case "save":

   $ufile = new file_uploaded("userfile");



   if(!$ufile->err){ //no errors

    if($_GET["imageid"] > 0) //load image if exists

     $image = &$images->image($_GET["imageid"]);

    else  //create image

     $image = &$images->image(0, 1);



    //save file

    $image->upload($ufile);

   }



   echo write_refresh("?page=admin&cmd=images&imageid=".$image->id,0);

   break;

  case "select":

   $search = new form_search("?page=admin&cmd=images&imageid=", 'image');

   $apanel->set_cnt($search->get_form_search());

   break;

  default:

   if(isset($_GET["imageid"])){

    $image = &$images->image($_GET["imageid"]);



    $tpl->assign(array(

      "IMG_NAME" => $image->name(),

      "ID"       => $image->id

     ));



    $apanel->set_cnt("ap_img_sel.tpl", 1);

   }else $apanel->set_cnt("ap_img.tpl", 1);

 }

?>