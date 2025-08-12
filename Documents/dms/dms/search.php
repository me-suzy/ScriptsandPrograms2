<?php
#####################################################################
# NAME/ PURPOSE - this script processes and displays searches
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done.
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
$query = strtolower($query);

print_header("Search Results");

print("<table id=\"search_results\">");

print("<tr>\n");
printf("<th>.</th>\n");
printf("<th><a href=\"search.php?query=$query&order=%s\">Filename</a></th>\n", ($order == "name,id DESC" ) ? "name,id%20ASC" : "name,id%20DESC" );
printf("<th><a href=\"search.php?query=$query&order=%s\">Size</a></th>\n", ($order == "size DESC" ) ? "size%20ASC" : "size%20DESC" );
printf("<th><a href=\"search.php?query=$query&order=%s\">Rev</a></th>\n", ($order == "revision DESC" ) ? "revision%20ASC" : "revision%20DESC" );
printf("<th><a href=\"search.php?query=$query&order=%s\">Author</a></th>\n", ($order == "author DESC" ) ? "author%20ASC" : "author%20DESC" );
printf("<th><a href=\"search.php?query=$query&order=%s\">Created</a></th>\n", ($order == "cdate DESC" ) ? "cdate%20ASC" : "cdate%20DESC" );
printf("<th><a href=\"search.php?query=$query&order=%s\">Modified</a></th>\n", ($order == "mdate DESC" ) ? "mdate%20ASC" : "mdate%20DESC" );
printf("<th>.</th>\n");
print("</tr>\n");

  if($user->god) {
    if(isset($order))
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id WHERE k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%' ORDER BY ". rawurldecode($order) );
    else
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id WHERE k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%' ORDER BY id ASC");
  } else {
    if(isset($order))
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%') ORDER BY ". rawurldecode($order) );
    else
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%') ORDER BY id ASC");
  }
  if( ! ($count = @mysql_num_rows($res)) ) {
    print("<tr>\n");
    print("<td colspan=\"7\">No documents found</td>\n");
    print("</tr>\n");
  } else {
    echo "<h2>Found $count matching documents</h2>\n";
    while($row = @mysql_fetch_array($res)) {
      print("<tr>\n");
      print("<td><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\"></td>\n");
      print("<td><a href=\"detail.php?doc_id=$row[id]&query=$query\">$row[name]</a></td>\n");
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
      print("<td>$row[created]</td>\n");
      printf("<td%s</td>\n", ($row[modified] == NULL) ? ">-" : ">$row[modified]" );
      printf("<td><img src=\"pix/%s.gif\" height=\"15\" width=\"15\" alt=\"[ Access: %s ]\"></td>\n", ($row[level] == NULL) ? "G" : $row[level], access_string( ($row[level] == NULL) ? "G" : $row[level] ) );
      print("</tr>\n");
    }
  }

print("</table>");

print_footer();

?>