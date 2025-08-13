<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Fix all Super Variables

 extract($_POST, EXTR_SKIP);

 extract($_GET,  EXTR_SKIP);

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Blocker - Stop Blocked Requests First

 require_once('./code/class/blocker.inc.php');

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Classes

 require_once('./config.inc.php');

  //make sure config is valid

  if(!defined('CONFIG')) die;

 require_once('./code/class/time.inc.php');

 require_once('./code/class/error.inc.php');

 require_once('./code/class/array.inc.php');

 require_once('./code/class/std.inc.php');

 require_once('./code/class/dbase.inc.php');

 require_once('./code/class/crypt.inc.php');

 require_once('./code/class/email.inc.php');

 require_once('./code/class/game.inc.php');

 require_once('./code/class/image.inc.php');

 require_once('./code/class/region.inc.php');

 require_once('./code/class/server.inc.php');

 require_once('./code/class/team.inc.php');

 require_once('./code/class/team_invite.inc.php');

 require_once('./code/class/team_status.inc.php');

 require_once('./code/class/tournament.inc.php');

 require_once('./code/class/tournament_module.inc.php');

 require_once('./code/class/tournament_match.inc.php');

 require_once('./code/class/tournament_auth.inc.php');

 require_once('./code/class/user.inc.php');

 require_once('./code/class/userauth.inc.php');

 require_once('./code/class/file.inc.php');

 require_once('./code/class/fasttemplate.inc.php');

 require_once('./code/class/template.inc.php');

 require_once('./code/class/search.inc.php');

 require_once('./code/class/toolbar.inc.php');

 require_once('./code/class/menu.inc.php');

 require_once('./code/class/forum.inc.php');

 require_once('./code/class/news.inc.php');

 //Load Includes

 require_once('./code/user_tab.inc.php');

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Main objects

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $tpl         = new FastTemplate("./" . $loc_tpl_default);



  $querys      = new db_querys($DBNAME, $DBSERVERHOST, $DBUSERNAME, $DBPASSWORD);

  	unset($DBNAME); unset($DBSERVERHOST); unset($DBUSERNAME); unset($DBPASSWORD); //clear passwords for security



  $users       = new db_users();

  $usersauth   = new db_users_auth();



  $teams       = new db_teams();

  $teaminvites = new db_team_invites();

  $teamstatus  = new db_teams_status();



  $tournys     = new db_tournys();

  $tournysauth = new db_tourny_auths();



  $games       = new db_games();

  $regions     = new db_regions();

  $servers     = new db_servers();

  $images      = new db_images();

  $emails      = new db_emails();



  $forum       = new forum();

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Send Headers

 //-----------------------------------------------------------------------------------------------------------------------------------------

  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); //P3P Policy Statement

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Assign Site Vars

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $tpl->assign(array(

    "SITE_NAME"  => $site_name,

    "SITE_URL"   => $site_url,

    "SITE_DNS"   => $site_dns,

    "SITE_EMAIL" => $site_email

   ));

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //User Login

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $users->login();

  if(is_object($users->user)) $user = &$users->user;

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Page Select

 //-----------------------------------------------------------------------------------------------------------------------------------------

  include("./code/class/select.inc.php");

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Templates

 //-----------------------------------------------------------------------------------------------------------------------------------------

  //create the menu

  create_menu();



  $tpl->splice("MAIN", "index.tpl");



  //create user tab

  write_user_tab();



  if($page_refreshing) //page is refreshing - override

  {

   $tpl->clear("CENTER");

   $tpl->assign("CENTER", '');



   echo $tpl->fetchfile("refresh.tpl");

  }else{ //normal display

   if($tpl->fetch("CENTER") == '')

    $tpl->parsefile("CENTER", ($centercol!='') ? $centercol : $loc_cache . "news.tpl" );



   echo $tpl->parse("MAIN","MAIN", array(

     "LOCATION_IMAGES" => $loc_tpl_images,

     "LOCATION_CSS"    => $loc_tpl_css

    ));

  }



 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Unload all DB objects - Cleanup

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $games       -> update_db();

  $regions     -> update_db();

  $servers     -> update_db();

  $images      -> update_db();

  $emails      -> update_db();



  $tournys     -> update_db();

  $tournysauth -> update_db();



  $users       -> update_db();

  $usersauth   -> update_db();



  $teams       -> update_db();

  $teaminvites -> update_db();

  $teamstatus  -> update_db();



  $querys      -> cleanup();

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Forum Updates

 //-----------------------------------------------------------------------------------------------------------------------------------------

  if($update_news) //only update when needed

   if(is_object($user)) if($user->get("admin") >= $level_news)

    $forum->fetch_news();



  $forum->update();

  $forum->cleanup();

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Debug Echos

 //-----------------------------------------------------------------------------------------------------------------------------------------

  if(is_object($user)) //valid user object

  if($user->get("admin") > 2){ //they are an admin

   echo "<hr>".implode('<br>',$tpl->FILELIST)."<hr>";

   foreach($querys->querys as $query){

    if($query->cleared == 1) echo "<span class=\"error\">" . $query->db_sql . "<br>--".$query->error."</span><br>";

    else echo $query->db_sql . "<br>";

   }



   echo "<hr>SQL Calls: ".count($querys->querys)." Execution Time: " . ($tpl->utime()  - $tpl->start);  //$tpl->showDebugInfo();

  }

 //-----------------------------------------------------------------------------------------------------------------------------------------

?>