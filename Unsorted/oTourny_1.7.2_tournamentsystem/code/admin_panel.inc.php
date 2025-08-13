<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;



 function write_teamsubbar(){

  global $user, $team, $apanel;



  $toolbar = new form_toolbar();



  if($team > 0) $teamtxt = "&team=".$team;

  $toolbar->add("Select Team","?page=admin&cmd=team&cmdd=select".$teamtxt);



  if($team > 0){

   $toolbar->add("Edit Team","?page=admin&cmd=team&cmdd=edit".$teamtxt);

   $toolbar->add("Invites","?page=admin&cmd=team&cmdd=invites".$teamtxt);

   $toolbar->add("Users","?page=admin&cmd=team&cmdd=users".$teamtxt);

  }



  $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("Team Admin Console");

 }



 function write_usersubbar(){

  global $level_user_admin, $user, $iuser, $apanel;



  $toolbar = new form_toolbar();



  if($iuser > 0) $playertxt = "&iuser=".$iuser;

  $toolbar->add("Select User","?page=admin&cmd=user&cmdd=select".$playertxt);



  if($iuser > 0){

   $toolbar->add("Edit User","?page=admin&cmd=user&cmdd=edit".$playertxt);

   $toolbar->add("Discipline","?page=admin&cmd=user&cmdd=disc".$playertxt);

   $toolbar->add("Teams","?page=admin&cmd=user&cmdd=teams".$playertxt);

   if($user->get("admin") >= $level_user_admin) $toolbar->add("Set Admin Level","?page=admin&cmd=user&cmdd=admin".$playertxt);

  }



  $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("User Admin Console");

 }



 function write_imagesubbar(){global $imageid, $apanel;

  $toolbar = new form_toolbar();

   if(isset($imageid)) $imagetxt = "&imageid=".$imageid;

   $toolbar->add("Select Picture","?page=admin&cmd=images&cmdd=select$imagetxt");

   $toolbar->add("Upload Picture","?page=admin&cmd=images&cmdd=create$imagetxt");

   if(isset($imageid)){

    $toolbar->add("Modify Picture","?page=admin&cmd=images&cmdd=mod$imagetxt");

    $toolbar->add("Delete Picture","?page=admin&cmd=images&cmdd=del$imagetxt");

   }

  $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("Picture Admin Console");

 }



 function write_uauthsubbar(){global $apanel;

  $toolbar = new form_toolbar(); $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("User Auth Admin Console");

 }



 function write_emailsubbar(){global $apanel;

  $apanel->set_tlb('');

  $apanel->set_hdr("Email Admin Console");

 }



 function write_gamessubbar(){global $id, $apanel;

  $toolbar = new form_toolbar();

   $toolbar->add("Create Game","?page=admin&cmd=games&cmdd=mod&id=-1");



   if($id > 0){

    $toolbar->add("Select Game","?page=admin&cmd=games&cmdd=sel&id=".$id);

    $toolbar->add("Modify Game","?page=admin&cmd=games&cmdd=mod&id=".$id);

    $toolbar->add("Delete Game","?page=admin&cmd=games&cmdd=del&id=".$id);

   }else

    $toolbar->add("Select Game","?page=admin&cmd=games&cmdd=sel");



  $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("Games Admin Console");

 }



 function write_locationsubbar(){global $apanel, $id;

 $toolbar = new form_toolbar();

   $toolbar->add("Create Location","?page=admin&cmd=locations&cmdd=mod&id=-1");



   if($id > 0){

    $toolbar->add("Select Location","?page=admin&cmd=locations&cmdd=sel&id=".$id);

    $toolbar->add("Modify Location","?page=admin&cmd=locations&cmdd=mod&id=".$id);

    $toolbar->add("Delete Location","?page=admin&cmd=locations&cmdd=del&id=".$id);

   }else

    $toolbar->add("Select Location","?page=admin&cmd=locations&cmdd=sel");



  $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("Location Admin Console");

 }



 function write_dbbacksubbar(){global $apanel;

  $toolbar = new form_toolbar(); $apanel->set_tlb($toolbar->parse());

  $apanel->set_hdr("Database Backup Admin Console");

 }



 if($user->id > 0) //valid user

 if(($user->get("admin") >= $level_console) || $cmd == "tourny"){

  $apanel = new tlbhdrcnt();



  switch($cmd) {

   case "user":

    if($user->get("admin") >= $level_user){

     write_usersubbar();

     include './code/admin_user.inc.php';

    }

    break;

   case "team":

    if($user->get("admin") >= $level_team){

     write_teamsubbar();

     include './code/admin_team.inc.php';

    }

    break;

   case "tourny":

    include './code/admin_tourny.inc.php';

    break;

   case "team":

    if($user->get("admin") >= $level_user){

     write_teamsubbar();

    }

    break;

   case "locations":

    if($user->get("admin") >= $level_location){

     write_locationsubbar();

     include './code/admin_location.inc.php';

    }

    break;

   case "games":

    if($user->get("admin") >= $level_location){

     write_gamessubbar();

     include './code/admin_games.inc.php';

    }

    break;

   case "email":

    if($user->get("admin") >= $level_email){

     write_emailsubbar();

     include './code/admin_email.inc.php';

    }

    break;

   case "uauth":

    if($user->get("admin") >= $level_userauth){

     write_uauthsubbar();

     include './code/admin_uauth.inc.php';

    }

    break;

   case "images":

    if($user->get("admin") >= $level_pictures){

     write_imagesubbar();

     include './code/admin_images.inc.php';

    }

    break;

   default:

    $toolbar = new form_toolbar();

     if($user->get("admin") >= $level_user)     $toolbar->add("Users","?page=admin&cmd=user");

     if($user->get("admin") >= $level_user)     $toolbar->add("Teams","?page=admin&cmd=team");

     if($user->get("admin") >= $level_tourny)   $toolbar->add("Tournaments","?page=admin&cmd=tourny");

     if($user->get("admin") >= $level_location) $toolbar->add("Locations","?page=admin&cmd=locations");

     if($user->get("admin") >= $level_games)    $toolbar->add("Games","?page=admin&cmd=games");

     if($user->get("admin") >= $level_email)    $toolbar->add("Mass Email","?page=admin&cmd=email");

     if($user->get("admin") >= $level_userauth) $toolbar->add("User Auth","?page=admin&cmd=uauth");

     if($user->get("admin") >= $level_pictures) $toolbar->add("Pictures","?page=admin&cmd=images");

    $apanel->set_tlb($toolbar->parse());

    $apanel->set_hdr("Admin Console");

    $apanel->set_cnt("ap_frontpage_cnt.tpl", 1);

    break;

  }

 }



?>