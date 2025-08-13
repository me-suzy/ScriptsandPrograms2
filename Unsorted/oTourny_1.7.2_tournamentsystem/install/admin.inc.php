<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/

 //load config so we can check it

 require_once('./config.inc.php');

 //load db api

 require_once('./code/class/dbase.inc.php');

 require_once('./code/class/user.inc.php');

 require_once('./code/class/forum.inc.php');

 require_once('./code/class/crypt.inc.php');

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Objects

 //-----------------------------------------------------------------------------------------------------------------------------------------

 $querys = new db_querys($DBNAME, $DBSERVERHOST, $DBUSERNAME, $DBPASSWORD);

 $users  = new db_users();

 $forum  = new forum($forum_enabled);

 //-----------------------------------------------------------------------------------------------------------------------------------------



 function write_admin(){global $users, $tpl, $forum;

  $tpl->splice("SETUP", "admin.tpl");



  //head error section

  $tpl->assign("SETUP->ERRORS", "");



  if(isset($_POST["submit"])){// check their input

   //name bounds

   if(strlen($_POST["user_name"]) < 3  || strlen($_POST["user_name"]) > 50)

    $errchk .= $tpl->parse("SETUP->ERROR_NAME_BOUNDS", "SETUP->ERROR_NAME_BOUNDS");

   //password bounds

   if(strlen($_POST["user_pass"]) < 5  || strlen($_POST["user_pass"]) > 50)

    $errchk .= $tpl->parse("SETUP->ERROR_PASS_BOUNDS", "SETUP->ERROR_PASS_BOUNDS");

   //password bounds

   if(!checkemail($_POST["user_email"]) || strlen($_POST["user_email"]) > 150)

    $errchk .= $tpl->parse("SETUP->ERROR_EMAIL", "SETUP->ERROR_EMAIL");



   if($errchk == ''){ //its good

    $user =& $users->user(0,1); //make admin



    $user->set(array(

      "name"     => htmlchars($_POST["user_name"]),

      "password" => hash($_POST["user_pass"]),

      "email"    => $_POST["user_email"],

      "admin"    => 9 //highest admin level possible

     ));



    //update forum user acc

    if($forum->enabled) //add user to forum

     $forum->add_user($user, true);



    unset($user);



    return $tpl->parsefile("CONTENT", "finish.tpl");

   }else //there are errors

    $tpl->parse("SETUP->ERRORS", "SETUP->ERROR_HEAD", array(

      "ERRORS" => $errchk

     ));

  }



  $tpl->assign(array(

    "FIELD_NAME"        => "user_name",

    "FIELD_NAME_VALUE"  => $_POST["user_name"],

    "FIELD_PASS"        => "user_pass",

    "FIELD_PASS_VALUE"  => $_POST["user_pass"],

    "FIELD_EMAIL"       => "user_email",

    "FIELD_EMAIL_VALUE" => $_POST["user_email"],

    "FIELD_SUBMIT"      => "submit"

   ));



  $tpl->parse("CONTENT", "SETUP");

 }



 switch($page){

  default:

   write_admin();

 }



 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Unload Objects

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $users  -> update_db();

  $querys -> cleanup();



  if($forum->enabled){ //forum is enabled

   $forum->update();

   $forum->cleanup();

  }

 //-----------------------------------------------------------------------------------------------------------------------------------------

?>