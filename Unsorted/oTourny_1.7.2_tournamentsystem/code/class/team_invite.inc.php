<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Team Invite Protocol

 */



 class db_team_invites extends db_table {

  var $invites;      //invite array - holds reference to the invites

  var $user_invites; //user invite array - holds reference to the user invites arrays

  var $team_invites; //team invite array - holds reference to the user invites arrays



  function db_team_invites(){

   //notify parent of db names and class

   parent::db_table("teaminvites", "inviteid", "db_team_invite");



   //reference class list

   $this->invites =& $this->objs;

  }



  //retrieve a invite

  function &invite($id = 0, $create = 0){

   return parent::obj($id, $create);

  }



  //returns if user is invited to a team

  function user_team_invite($user, $team){

   $uid = is_object($user) ? $user->id : $user;

   $tid = is_object($team) ? $team->id : $team;



   $query = new db_cmd("select", "teaminvites", "inviteid", "userid='".$uid."' AND team='".$tid."'");



   return $query->query->db_data[0]["inviteid"] > 0;

  }



  //retrieve array of user invites

  function user_invite($user){

   $id = is_object($user) ? $user->id : $user;



   if(isset($this->user_invites[$id])) return $this->user_invites[$id];



   $query = new db_cmd("select", "teaminvites", "inviteid", "userid='".$id."'");



   foreach($query->query->db_data as $invite)

    $invites[] = $invite["inviteid"];



   return $this->user_invites[$id] = $invites;

  }



  //retrieve array of user invites for a team

  function team_invite($team){

   $id = is_object($team) ? $team->id : $team;



   if(isset($this->team_invites[$id])) return $this->team_invites[$id];



   $query = new db_cmd("select", "teaminvites", "inviteid", "team='".$id."'");



   foreach($query->query->db_data as $invite)

    $invites[] = $invite["inviteid"];



   return $this->user_invites[$id] = $invites;

  }

 }



 class db_team_invite extends db_obj {

  function db_team_invite($id, &$data, &$container){

   parent::db_obj($id, &$data, &$container);

  }

 }



?>