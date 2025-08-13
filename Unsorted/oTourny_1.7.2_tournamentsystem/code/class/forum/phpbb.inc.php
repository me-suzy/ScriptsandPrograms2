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

  class forum extends forum_std {

   function forum(){
    //construct
   }

//-----------------------------------------------------------------------------------------------------------------------------------------
//Local Cmds
//-----------------------------------------------------------------------------------------------------------------------------------------

  //Will forcefully reload whole forum db
  function force_reload(){global $users;
   $query = new db_cmd("SELECT", "users", "userid", "1");

   foreach($query->data as $data)
    $this->add_user($users->user($data["userid"]), 1);
  }

//-----------------------------------------------------------------------------------------------------------------------------------------
//Foreign DB CMDS
//-----------------------------------------------------------------------------------------------------------------------------------------

   //creates user in forum
   function create_user($fuser){
    //delete copies
    $this->db->query("DELETE FROM `".$GLOBALS["forum_prefix"]."users` WHERE `user_id` = '".$fuser->id."'");

    //add user
    $this->db->query("INSERT INTO `".$GLOBALS["forum_prefix"]."users` (`user_id`, `user_regdate`) VALUES (".$fuser->id.", ".time().")");

   }

   //update user info in forum
    //basicly just force overwrite everything you can find
   function update_user($fuser, $admin = FALSE){
     $items = array(
      "user_active"   => "1",
      "username"      => $fuser->get("name"),
      "user_password" => $fuser->get("password"), //md5
      "user_email"    => $fuser->get("email"),
      "user_regdate"  => time(),
      "user_style"    => "1",
      "user_lang"     => "english",
      "user_rank"     => "0",
     );

     if($admin) //add user as god admin
      $items[] = array("user_level" => 1);

    //compile items
    foreach($items as $key => $val)
     if($key != ''){
      if(gettype($key) != 'integer') $itemlst .= ($itemlst!=''?", ":'') . $key . "=\"" . convertsqlquotes($val) . "\"";
      else $itemlst .= ($itemlst!=''?", ":'') . $val;
     }
    echo "UPDATE `".$GLOBALS["forum_prefix"]."users` SET ".$itemlst." WHERE `user_id` = '".$fuser->id."'\n\n";
    $this->db->query("UPDATE `".$GLOBALS["forum_prefix"]."users` SET ".$itemlst." WHERE `user_id` = '".$fuser->id."'");
   }

//-----------------------------------------------------------------------------------------------------------------------------------------
//News CMDS
//-----------------------------------------------------------------------------------------------------------------------------------------

   //grabs the first post of the latest posts from the first forum
   function fetch_news(){
    //load db if not loaded
    if(!is_object($this->db)) $this->load_db();

    //create news object
    $news = new news();

    //grab ids of all posts
    $query =& $this->db->query("SELECT `post_id`, `topic_id` FROM `".$GLOBALS["forum_prefix"]."posts` WHERE `forum_id` = '".$GLOBALS["forum_news"]."' ORDER BY `post_id` DESC");

    //load all
    $query->load();

    $topics = array(); //declare to stop any errors

    //load everything into an array
    if(is_array($query->db_data))
     foreach($query->db_data as $data) if(!empty($data)) //valid data
      if(!in_array($data["topic_id"], $topics)) //Only have 1 topic - no sub topic shit
       if(++$c < $GLOBALS["news_count"]){ //dont go over limit

       $topics[] = $data["topic_id"];

       //grab forum text and title
       $query_text =& $this->db->query("SELECT `post_subject`, `post_text` FROM `".$GLOBALS["forum_prefix"]."posts_text` WHERE `post_id` = '".$data["post_id"]."' LIMIT 1");

       //load all
       $query_text->load();

       ///////////////////////////////////////////////////////////////////////////////////////
       //taken directly from bbcode.php and viewtopic.php
       ///////////////////////////////////////////////////////////////////////////////////////
       $message = $query_text->db_data[0]["post_text"];
       //html
       $message = preg_replace("/&gt;/i", ">", $message);
	$message = preg_replace("/&lt;/i", "<", $message);
	$message = preg_replace("/&quot;/i", "\"", $message);
	$message = preg_replace("/&amp;/i", "&", $message);

       ///////////////////////////////////////////////////////////////////////////////////////

       //notify news obj
       $news->add_news($data["post_id"], $query_text->db_data[0]["post_subject"], $message);
     }

    //save!
    $news->generate();

    unset($news);
   }
  }
//-----------------------------------------------------------------------------------------------------------------------------------------

 //Called during install to verify if the forum database is valid
 //retrieves forum list for user can choose news forum
  //format of list: array("forum_name" => name, "forum_id" => id)
 function install_verify_dbf(){
  $querys = new db_querys($_POST["dbf_name"], $_POST["db_host"], $_POST["dbf_user"], $_POST["dbf_pass"]);

  //quick reference db
  $dbapi =& $querys->db;

  //grab error code if any
  $errors["connect"]["error"] = $dbapi->error();

  if($dbapi->connect_id){ //valid connection
   $errors["connect"]["valid"] = true;

   //get a list of the tables
   $query =& $querys->query("select * from ".$_POST["dbf_prefix"]."users", true);
   //get list from mysql
   $query->load();
   //grab any error codes
   $errors["tables"]["error"] = $dbapi->error();

   //make sure db isnt empty
   if(!empty($query->db_data))
    $errors["tables"]["valid"] = true;
   else //db is empty
    $errors["tables"]["valid"] = false;

   unset($query);

   //Grab a list of the Forums
   $query =& $querys->query("SELECT `forum_id`, `forum_name` FROM ".$_POST["dbf_prefix"]."forums", true);
   //get list from mysql
   $query->load();

   //grab any error codes
   $errors["forums"]["error"] = $dbapi->error();
   $errors["forums"]["list"]  = $query->db_data;

   unset($query);
  }else //didnt work
   $errors["connect"]["valid"] = false;

  //clean up db
  $querys->cleanup();

  return $errors;
 }

?>