<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Notify Classes they can load

 define('CONFIG', true);

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Fix all Super Variables

 extract($_POST, EXTR_SKIP);

 extract($_GET,  EXTR_SKIP);

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Classes

 require_once('./code/class/std.inc.php');

 require_once('./code/class/file.inc.php');

 require_once('./code/class/fasttemplate.inc.php');

 require_once('./code/class/forum.inc.php');

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Main objects

 //-----------------------------------------------------------------------------------------------------------------------------------------

  $tpl = new FastTemplate("./install/pages/");

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Load Code

 //-----------------------------------------------------------------------------------------------------------------------------------------

  switch($page){

   case "admin":

    include("./install/admin.inc.php");

    break;

   case "verify":

    include("./install/verify.inc.php");

    break;

   default: //"install"

    include("./install/install.inc.php");

    break;

  }



  if($page_refreshing) //page is refreshing - override

   echo $tpl->fetchfile("refresh.tpl");

  else //normal display

   echo $tpl->fetchfile("index.tpl");

 //-----------------------------------------------------------------------------------------------------------------------------------------

 //Debug Echos

 //-----------------------------------------------------------------------------------------------------------------------------------------

 if(0){

   echo "<hr>".implode('<br>',$tpl->FILELIST)."<hr>";

   foreach($querys->querys as $query){

    if($query->cleared == 1) echo "<span class=\"error\">" . $query->db_sql . "<br>--".$query->error."</span><br>";

    else echo $query->db_sql . "<br>";

   }



   echo "<hr>SQL Calls: ".count($querys->querys)." Execution Time: " . ($tpl->utime()  - $tpl->start);  //$tpl->showDebugInfo();

  }

 //-----------------------------------------------------------------------------------------------------------------------------------------

?>