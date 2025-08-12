<?
/* download.php            version 15/jan/2002 */
/* copyright © 2001 Y0Gi <webmaster@nwsnet.de> */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330,
Boston, MA 02111-1307 USA
*/

/* specify path containing files */
$download_path = 'download/';

/* set up mysql connection */
include("inc.mysql.php");

if (!empty($file)) {
  /* query database */
  $result = mysql_query('SELECT filename FROM download');

  /* check each row for filename and send file via http header */
  while ($row = mysql_fetch_assoc($result)) {
    if ($row["filename"] == $file) {
      $result = mysql_query("UPDATE download SET downloads=downloads+1 WHERE filename='" . $file . "'");
      header('Location: ' . $download_path . basename($file));
    }
  }
}
?>