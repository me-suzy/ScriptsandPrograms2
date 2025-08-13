<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 /*
  Mass Email Protocol
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// DB_EMAILS
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_emails extends db_table {
  var $emails; //email array - holds reference to email objs
  var $count = 0;  //count of all emails created this page

  function db_emails(){
   //notify parent of db names and class
   parent::db_table("emails", "id", "db_email");

   //reference class list
   $this->emails =& $this->objs;
  }

  //retrieve a email
  function &email($id = 0, $create = 0){
   return parent::obj($id, $create);
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Email Code
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //quick function to create email and fill it out
  function create_email($email, $title, $content, $xtra){
   if($email == '' || $title == '' || $content == '') return; //invalid email

   $email =& $this->email(0, 1);

   $email->set(array(
     "email"   => $email,
     "title"   => $title,
     "message" => $content,
     "xtra"    => $xtra
    ));

   //up the count
   $this->count++;

   unset($email);
  }

  function send($count){
   //query all tournaments to add them to listing
   $query = new db_cmd("select", "emails", "id", '', $count);
   if(is_array($query->data))
    foreach($query->data as $data) if($data["id"] > 0){//possibly valid id
     $email =& $this->email($data["id"]);

     if($email->id > 0){ //valid id
      $email->send();
      $email->delete();
     }

     unset($email);
    }

   if($query->data[0]["id"] > 0) return true; //more to send
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// List Generation Code
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  function gen_list_users($title, $content, $xtra){global $users;
   $query = new db_cmd("select", "users", "userid");
   if(is_array($query->data))
   foreach($query->data as $data) if($data["userid"] > 0){//possibly valid id
    $euser =& $users->user($data["userid"]);

    if($euser->id > 0)//valid user
     $this->create_email($euser->get("email"), $title, $content, $xtra);

    unset($euser);
   }

   return $this->count;
  }

  function gen_list_team($title, $content, $xtra){global $teams;
   $query = new db_cmd("select", "teams", "teamid");
   if(is_array($query->data))
   foreach($query->data as $data) if($data["teamid"] > 0){//possibly valid id
    $team =& $teams->team($data["teamid"]);

    if($team->id > 0)//valid team
     $this->create_email($team->get("email"), $title, $content, $xtra);

    unset($team);
   }

   return $this->count;
  }

  function gen_list_team_leaders($title, $content, $xtra){global $teams, $users;
   $query = new db_cmd("select", "teams", "teamid");
   if(is_array($query->data))
   foreach($query->data as $data) if($data["teamid"] > 0){//possibly valid id
    $team =& $teams->team($data["teamid"]);

    if($team->id > 0){//valid team
     $teamusers = $team->users(); //grab user list

     if(is_array($teamusers))
     foreach($teamusers as $userid)
      if($userid > 0)//valid id
       if($team->user_rank($userid) > $GLOBALS["level_player"]){ //team leaders
        $euser =& $users->user($userid);

        if($euser->id > 0){ //valid user
         $this->create_email($euser->get("email"), $title, $content, $xtra);
        }

        unset($euser);
    }  }

    unset($team);
   }

   return $this->count;
  }

  function gen_list_admins($title, $content, $xtra){global $users;
   $query = new db_cmd("select", "users", "userid", "admin > ".$level_news);
   if(is_array($query->data))
   foreach($query->data as $data) if($data["userid"] > 0){//possibly valid id
    $euser =& $users->user($data["userid"]);

    if($euser->id > 0)//valid user
     $this->create_email($euser->get("email"), $title, $content, $xtra);

    unset($euser);
   }

   return $this->count;
  }

  function gen_list_tourny_admins($tournyid, $title, $content, $xtra){global $users, $tournys;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0){//valid tourny
    $admins = $tourny->admins(); //grab admin list
    if(is_array($admins))
     foreach($admins as $adminid) if($adminid > 0){
      $admin =& $users->user($adminid);

      if($admin->id > 0)//valid admin
       $this->create_email($admin->get("email"), $title, $content, $xtra);

      unset($admin);
   } }

   unset($tourny);

   return $this->count;
  }

  function gen_list_tourny_players($tournyid, $title, $content, $xtra){global $users, $tournys, $teams;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0){//valid tourny
    $tteams = $tourny->teams(); //grab team list

    if($tourny->type() == $GLOBALS["tourny_type_single"])
     if(is_array($tteams))
      foreach($tteams as $userid) if($userid > 0){
       $euser =& $users->user($userid);

       if($euser->id > 0)//valid euser
        $this->create_email($euser->get("email"), $title, $content, $xtra);

       unset($euser);
      }

    if($tourny->type() == $GLOBALS["tourny_type_team"])
     if(is_array($tteams))
      foreach($tteams as $teamid) if($teamid > 0){
       $team =& $teams->team($teamid);

       if($team->id > 0){//valid team
        $teamlst = $team->users();

        if(is_array($teamlst))
         foreach($teamlst as $userid) if($userid > 0){
          $euser =& $users->user($userid);

          if($euser->id > 0)//valid euser
           $this->create_email($euser->get("email"), $title, $content, $xtra);

          unset($euser);
       } }

       unset($team);
   }  }

   unset($tourny);

   return $this->count;
  }

  function gen_list_tourny_leaders($tournyid, $title, $content, $xtra){global $users, $tournys, $teams;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0){//valid tourny
    $tteams = $tourny->teams(); //grab team list

    if($tourny->type() == $GLOBALS["tourny_type_team"])
     if(is_array($tteams))
      foreach($tteams as $teamid) if($teamid > 0){
       $team =& $teams->team($teamid);

       if($team->id > 0){//valid team
        $teamlst = $team->users();

        if(is_array($teamlst))
         foreach($teamlst as $userid) if($userid > 0)
          if($team->user_rank($userid) > $GLOBALS["level_player"]){
           $euser =& $users->user($userid);

           if($euser->id > 0)//valid euser
            $this->create_email($euser->get("email"), $title, $content, $xtra);

           unset($euser);
       }  }

       unset($team);
   }  }

   unset($tourny);

   return $this->count;
  }

  function gen_list_tourny_team($tournyid, $title, $content, $xtra){global $tournys, $teams;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0){//valid tourny
    $tteams = $tourny->teams(); //grab team list

    if($tourny->type() == $GLOBALS["tourny_type_team"])
     if(is_array($tteams))
      foreach($tteams as $teamid) if($teamid > 0){
       $team =& $teams->team($teamid);

       if($team->id > 0){//valid team
        $this->create_email($team->get("email"), $title, $content, $xtra);

       unset($team);
   }  }}

   unset($tourny);

   return $this->count;
  }

  function gen_list_tourny_draft_users($tournyid, $title, $content, $xtra){global $tournys, $users;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0)
   if($tourny->get("draft")){//valid tourny
    $players = $tourny->draft_users(); //grab player list

    if($tourny->type() == $GLOBALS["tourny_type_team"])
     if(is_array($players))
      foreach($players as $userid) if($userid > 0){
       $euser =& $users->user($userid);

       if($euser->id > 0){//valid euser
        $this->create_email($euser->get("email"), $title, $content, $xtra);

       unset($euser);
   }  }}

   unset($tourny);

   return $this->count;
  }

  function gen_list_tourny_draft_capts($tournyid, $title, $content, $xtra){global $tournys, $users;
   $tourny =& $tournys->tourny($tournyid);

   if($tourny->id > 0)
   if($tourny->get("draft")){//valid tourny
    $players = $tourny->draft_capts(); //grab capt list

    if($tourny->type() == $GLOBALS["tourny_type_team"])
     if(is_array($players))
      foreach($players as $userid) if($userid > 0){
       $euser =& $users->user($userid);

       if($euser->id > 0){//valid euser
        $this->create_email($euser->get("email"), $title, $content, $xtra);

       unset($euser);
   }  }}

   unset($tourny);

   return $this->count;
  }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// DB_EMAIL
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_email extends db_obj {
  function db_email($id, &$data, &$container){
   parent::db_obj($id, &$data, &$container);
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Send Code
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //send the email
  function send(){
   set_time_limit(200); //2 min max -- special override for sending only

   //make sure its probably valid
   if($this->get("email") != '' && $this->get("title") != '' && $this->get("message") != '')
    //send it
    mail($this->get("email"), $this->get("title"), $this->get("message"), $this->get("xtra"));
  }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 }

?>