<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Create Team Form

 */



 function write_createteam(){global $centercol, $tpl, $teams, $user;

  $centercol = "team_signup.tpl";



  if(isset($_POST["Team_Name"])){

   $errchk = $teams->check("name", $_POST["Team_Name"]) . $teams->check("tag", $_POST["Team_Tags"]) . $teams->check("pass", $_POST["Team_pass"]) . $teams->check("email", $_POST["Team_Email"]);



   if($errchk == ''){ //no errors

    //create team

    $team = &$teams->team(0,1);



    //update team

    $team->set(array(

      "name"     => htmlchars($_POST["Team_Name"]),

      "tag"      => $_POST["Team_Tags"],

      "tagside"  => ($_POST["side"]==1) ? 1 : 0,//close any open holes

      "password" => $_POST["Team_pass"],

      "email"    => $_POST["Team_Email"],

     ));



    //join team

    $team->add_user($user->id);

    $user->add_team($team->id);



    //set leader rank

    $team->set_founder($user->id);



    $centercol = "team_signup_conf.tpl";

    return;

  }}



  $tpl->assign(array(

    "ERRORS"                 => ($errchk == '') ? '' : write_error_common($errchk),

    "FIELD_TEAM_NAME"        => "Team_Name",

    "FIELD_TEAM_NAME_VALUE"  => htmlchars($_POST["Team_Name"]),

    "FIELD_TEAM_TAGS"        => "Team_Tags",

    "FIELD_TEAM_TAGS_VALUE"  => htmlchars($_POST["Team_Tags"]),

    "FIELD_TEAM_SIDE"        => "side",

    "FIELD_TEAM_SIDE_VALUE"  => "1",

    "FIELD_TEAM_SIDE_CHK"    => $_POST["side"],

    "FIELD_TEAM_EMAIL"       => "Team_Email",

    "FIELD_TEAM_EMAIL_VALUE" => htmlchars($_POST["Team_Email"]),

    "FIELD_TEAM_PASS"        => "Team_pass",

    "FIELD_TEAM_PASS_VALUE"  => htmlchars($_POST["Team_pass"])

   ));

 }



 if($user->id > 0) write_createteam();

?>