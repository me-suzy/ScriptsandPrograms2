<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

/**

* Forum Interface Class

*

*/



 //Global declartions of forum modules

 $GLOBALS["forum_module"][1] = array(

   "name"  => "phpBB2",

   "class" => "phpbb"

  );



  class forum_std {

   var $db; //forum db

   var $users; //users to be updated



   //clean up db

   function cleanup(){

    if(is_object($this->db)) $this->db->cleanup();

   }



//-----------------------------------------------------------------------------------------------------------------------------------------

//Local Cmds

//-----------------------------------------------------------------------------------------------------------------------------------------



  //Will forcefully reload whole forum db

  function force_reload(){

   //virtual

  }



   //adds user to array of users to be updated

   function add_user(&$user, $create = FALSE, $admin = FALSE){

    if($user->id > 0){ //make copy of user since it wont exist later

     $this->users[$user->id]["create"] = $create;

     $this->users[$user->id]["user"]   = $user;

     $this->users[$user->id]["admin"]  = $admin;

    }

   }



   //run through and update all users

   function update(){

    if(is_array($this->users)){

     if(!empty($this->users)) $this->load_db();



     foreach($this->users as $fuser){

      if($fuser["create"]) $this->create_user($fuser["user"]);



      $this->update_user($fuser["user"], $fuser["admin"]);

    }}

   }



//-----------------------------------------------------------------------------------------------------------------------------------------

//Foreign DB CMDS

//-----------------------------------------------------------------------------------------------------------------------------------------



   //load db class

   function load_db(){global $DB_FORUM_NAME, $DB_FORUM_SERVERHOST, $DB_FORUM_USERNAME, $DB_FORUM_PASSWORD;





    $this->db = new db_querys($DB_FORUM_NAME, $DB_FORUM_SERVERHOST, $DB_FORUM_USERNAME, $DB_FORUM_PASSWORD);

   }



   //creates user in forum

   function create_user($fuser){

    //virtual

   }



   //update user info in forum

    //basicly just force overwrite everything you can find

   function update_user($fuser, $admin = FALSE){

    //virtual

   }



//-----------------------------------------------------------------------------------------------------------------------------------------

//News CMDS

//-----------------------------------------------------------------------------------------------------------------------------------------



   //grabs the first post of the latest posts from the first forum

   function fetch_news(){

    //virtual

   }

  }



?>