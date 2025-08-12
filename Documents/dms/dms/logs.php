<?php
#####################################################################
# NAME/ PURPOSE - this page tracks who has downloaded files
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

print_header("View Download Logs");

print("<h1>Download Logs</h1>");

print("<table id=\"dl_logs\">");
print("<tr>\n");
printf("<th>User</th>\n");
printf("<th>.</th>\n");
printf("<th>Filename</th>\n");
printf("<th>Rev Downl.</th>\n");
printf("<th>Rev Curr.</th>\n");
printf("<th>Date</th>\n");
printf("<th>Address</th>\n");
print("</tr>\n");

$res = @mysql_query("SELECT COUNT(*) FROM documents_log");
$row = @mysql_fetch_array($res);
$total = $row[0];

if(!isset($next)){
	$next = 0;
}

$res = mysql_query("SELECT l.document AS id,l.revision AS revision,d.revision AS current,DATE_FORMAT(l.date, '%d/%m/%Y %H:%i:%S') AS date,u.name AS user,u.email AS email,d.name AS name,l.address AS address FROM documents_log AS l LEFT JOIN users AS u ON u.id=l.user LEFT JOIN documents AS d ON d.id=l.document ORDER BY l.date DESC LIMIT $next,20");

if( ! ($count = @mysql_num_rows($res)) ) {
	print("<tr>\n");
	print("<td colspan=\"7\">No downloads found</td>\n");
	print("</tr>\n");
	print("</table>");
} 

else {
	printf("<h2>Listing downloads %d - %d of $total</h2>\n", ($next+1), (($next+20) > $total) ? $total : ($next+20) );
	while($row = @mysql_fetch_array($res)) {
		print("<tr>\n");
		print("<td>$row[user]</td>\n");
		print("<td><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\"></td>\n");
		print("<td><a href=\"detail.php?doc_id=$row[id]\">$row[name]</a></td>\n");
		print("<td>$row[revision]</td>\n");
		print("<td>$row[current]</td>\n");
		print("<td>$row[date]</td>\n");
		print("<td>$row[address]</td>\n");
		print("</tr>\n");
}

print("</table>\n");

    if($next > 0){
        printf("<div><a href=\"logs.php?next=%d\">&lt; Prev</a></td>\n", $next-20);
	}
    else{
        echo "<div> </div>\n"; 
	}
    if(($next+20) < $total){
        printf("<a href=\"logs.php?next=%d\">Next &gt;</a></div>\n", $next+20);
	}
    else{
        echo "<div> </div>\n"; 
	}
}

print_footer();
?>