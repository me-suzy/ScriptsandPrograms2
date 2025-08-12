<?php
#####################################################################
# NAME/ PURPOSE
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - 
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################

require('lib/config.inc');
require('lib/auth.inc');
require('lib/classes.inc');
require('lib/functions.inc');

$user = new user($login);

print_header("List Documents");

print("<h1>Document List</h1>");


  if($user->god)
      if(isset($order))
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer ORDER BY ". rawurldecode($order) );
      else
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer ORDER BY id ASC");
  else
      if(isset($order))
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id ORDER BY ". rawurldecode($order) );
      else
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id ORDER BY id ASC");
  if( ! ($count = @mysql_num_rows($res)) ) {
    print("<tr>\n");
    print("<td colspan=\"7\">No documents found</td>\n");
    print("</tr>\n");
  } 
   else {
    print("<h2>Listing $count documents</h2></td>\n");
	print("<table id=\"files_list\">");
	print("<tr>\n");
	printf("<th></th>\n");
	printf("<th><a href=\"list.php?order=%s\">Filename</a></th>\n", ($order == "name DESC" ) ? "name%20ASC" : "name%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Size</a></th>\n", ($order == "size DESC" ) ? "size%20ASC" : "size%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Rev</a></th>\n", ($order == "revision DESC" ) ? "revision%20ASC" : "revision%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Author</a></th>\n", ($order == "author DESC" ) ? "author%20ASC" : "author%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Maintainer</a></th>\n", ($order == "maintainer DESC" ) ? "maintainer%20ASC" : "maintainer%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Created</a></th>\n", ($order == "cdate DESC" ) ? "cdate%20ASC" : "cdate%20DESC" );
	printf("<th><a href=\"list.php?order=%s\">Modified</a></th>\n", ($order == "mdate DESC" ) ? "mdate%20ASC" : "mdate%20DESC" );
	printf("<th></th>\n");
	print("</tr>\n");

    while($row = @mysql_fetch_array($res)) {
      print("<tr>\n");
      print("<td><a href=\"download.php?doc_id=$row[id]\"><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\" border=\"0\"></a></td>\n");
      print("<td><a href=\"detail.php?doc_id=$row[id]\">$row[name]</a></td>\n");
      if($row[size] < 0)
          continue;
      if( $row[size] < 10240 ) {
          $size_str = sprintf("%d bytes", $row[size]);
      } else if( $row[size] < 1048576 ) {
          $size_str = sprintf("%.1f Kb", ($row[size]/1024));
      } else {
          $size_str = sprintf("%.1f Mb", ($row[size])/(1024*1024));
      }
      print("<td>$size_str</td>\n");
      print("<td>$row[revision]</td>\n");
      print("<td>$row[author]</td>\n");
      print("<td>$row[maintainer]</td>\n");
      print("<td>$row[created]</td>\n");
      printf("<td%s</td>\n", ($row[modified] == NULL) ? " align=\"center\">-" : ">$row[modified]" );
      printf("<td><img src=\"pix/%s.gif\" height=\"15\" width=\"15\" alt=\"[ Access: %s ]\"></td>\n", ($row[level] == NULL) ? "G" : $row[level], access_string( ($row[level] == NULL) ? "G" : $row[level] ) );
      print("</tr>\n");
    }
  }

print("</table>");

print("<div><a href=\"new.php\"><button>Add A New File</button></a></div>");

print_footer();

?>