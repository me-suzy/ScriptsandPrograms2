<?php
/*
   +----------------------------------------------------------------------+
   | phpMyViews - mySQL query and view tool
   | http://phpmyviews.sourceforge.net/
   +----------------------------------------------------------------------+
   | Copyright (c) 2001-2003 by Wolfgang Ulmer
   +----------------------------------------------------------------------+
   | This program is free software; you can redistribute it and/or modify
   | it under the terms of the GNU General Public License as published by
   | the Free Software Foundation; either version 2 of the License, or
   | (at your option) any later version.
   |
   | This program is distributed in the hope that it will be useful, but
   | WITHOUT ANY WARRANTY; without even the implied warranty of
   | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   | General Public License for more details.
   |
   | You should have received a copy of the GNU General Public License
   | along with this program; if not, write to the Free Software
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston,
   | MA  02111-1307, USA.
   +----------------------------------------------------------------------+
   | Original Author: Wolfgang Ulmer (wulmer@users.sourceforge.net)
   +----------------------------------------------------------------------+
*/

  # MySQL Parameters
  $pmv_db_host="db_server";
  $pmv_db_name="db_name";
  $pmv_db_user="db_user";
  $pmv_db_pass="db_pass";

  # Actions
  # $pmv['default'][0]['validate']='./validators/vali_default_func.php';
  $pmv['default'][0]['sql']='SELECT field1 FROM table1 LIMIT $start,10';
  $pmv['default'][0]['pre']='<table>';
  $pmv['default'][0]['main']='<tr><td>$field1</td></tr>';
  $pmv['default'][0]['post']='</table>';

  # execute phpMyViews
  $path_to_pmv="./phpmyviews.php";
  include $path_to_pmv;
?>

