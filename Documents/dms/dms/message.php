<?php
#####################################################################
# NAME/ PURPOSE - this page is the 'message board' for the site
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done (for version 1.0). refer to sourceforge project
#		page for desired modifications
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

function list_messages() {
    $result = @mysql_query("SELECT c.id AS id,u.name AS user,u.email AS email,c.subject AS subject,c.ref_id AS ref,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id ORDER BY id DESC LIMIT 50" );
    if( ($num = @mysql_num_rows( $result )) == 1) {
        print("<h1>Message Board: $num message</h1>\n");
    } 
	else {
        print("<h1>Message Board: $num messages</h1>\n");
 	}

    if($num)
      print("Click on any of the subject lines below to read.</p>\n");
    else
      print(".</p>\n");

    while( $row = @mysql_fetch_array($result) ) {
        if($row[subject])
            if($row[ref])
                print("<div>$row[id] <a href=\"message.php?action=read&mid=$row[id]\">Re: ". stripslashes($row[subject]) ."</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]</div>\n");
            else
                print("<div>$row[id] <a href=\"message.php?action=read&mid=$row[id]\">". stripslashes($row[subject]) ."</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]</div>\n");
        else
            if($row[ref])
                print("<div>$row[id] <a href=\"message.php?action=read&mid=$row[id]\">Re: No subject</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]</div>\n");
            else
                print("<div>$row[id] <a href=\"message.php?action=read&mid=$row[id]\">No subject</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]</div>\n");
    }
    return;
}

function show_message( $mid ) {

    $result = @mysql_query("SELECT c.id AS id,c.ref_id AS ref,u.name AS user,u.email AS email,c.subject AS subject,c.content AS content,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id WHERE c.id=$mid");
    $row = @mysql_fetch_array($result);

    print("<h2>Message $row[id] by $row[user]</h2>\n");

    print("<table>\n");
    print("<tr>\n<td>\n");

    print("<p>Date: $row[date]\n");
    print("<br />From: $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt;\n");
    print("<br />Subject: ". stripslashes( $row[subject] ) ."\n");

    if($row[ref]) {
        $result = @mysql_query("SELECT c.id AS id,u.name AS user,c.subject AS subject,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id WHERE c.id=$row[ref]");
        $ref = @mysql_fetch_array( $result );
        print("<br />In reply to: <a href=\"message.php?action=read&mid=$ref[id]\">". stripslashes($ref[subject]) ."</a> by $ref[user] on $ref[date]\n");
    }

    print("<p>". nl2br( htmlentities( stripslashes($row[content]) )) ."\n");
    print("<p><a href=\"message.php?action=reply&reply=$row[id]\">Reply</a> | <a href=\"message.php?action=post\">Post New</a>\n");

    $res = @mysql_query("SELECT id FROM chat WHERE id<$row[id]");
    if( mysql_num_rows($res) )
        print(" | <a href=\"message.php?action=read&mid=". ($row[id]-1) ."\">Previous</a>\n");

    $res = @mysql_query("SELECT id FROM chat WHERE id>$row[id]");
    if( mysql_num_rows($res) )
        print(" | <a href=\"message.php?action=read&mid=". ($row[id]+1) ."\">Next</a>\n");

    print(" | <a href=\"message.php\">Index</a>\n");

    print("</td>\n</tr>\n</table>\n");

    return;
}

function new_message( $mid ) {

    if( $id > 0){
        print("<h2>Message Board - Reply to message #$mid</h2>\n");
	}
    else{
        print("<h2>Message Board - Enter a message</h2>\n");
	}
    print("<div><form action=\"message.php?action=save\" method=\"post\">\n");

	print("<table class=\"form_table\">");

    print("<div><input type=\"hidden\" value=\"$mid\" name=\"ref\"></div>\n");
    if($id) {
       $res = @mysql_query("SELECT subject FROM chat WHERE id=$mid");
       $row = @mysql_fetch_array($res);
       print("<tr><td>Subject: Re: ". stripslashes($row[subject]) ."</td>\n");
       print("<td><input type=\"hidden\" name=\"subject\" value=\"". stripslashes($row[subject]) ."\"></td></tr>\n");
    } 
	else {
        print("<tr><td>Subject:</td><td><input type=\"text\" class=\"input_text\" maxsize=\"128\" name=\"subject\"></td></tr>\n");
    }
    print("<tr><td>Message:</td><td><textarea cols=\"40\" rows=\"10\" name=\"content\"></textarea></td></tr>\n");

    print("<tr><td></td><td><input type=\"submit\" class=\"form_button\" value=\"Post message\" /></td></tr>\n");

	print("</table>");

    print("</form></div>\n");

    return;
}

function save_message( $ref, $subject, $content ) {
    global $user;

    if($ref){
        $query = "INSERT INTO chat(ref_id,user,subject,content,date) values($ref,$user->id,'". addslashes($subject) ."','". addslashes($content) ."',NOW())";
	}
    else{
        $query = "INSERT INTO chat(user,subject,content,date) values($user->id,'". addslashes($subject) ."','". addslashes($content) ."',NOW())";
	}
    
	$result = @mysql_query( $query );
    if($result != -1) {
        print("<h2>Error ". mysql_errno() .": ". mysql_error() ."</h2>\n");
        print("<p>$query</p>\n");
    } 
	else {
        $result = @mysql_query("SELECT LAST_INSERT_ID() FROM chat LIMIT 1");
        $row = @mysql_fetch_array($result);
        print("<h2>Your message was posted successfully</h2>\n");
        print("<p>You can <a href=\"message.php?action=read&mid=$row[0]\">read it</a> or go to the <a href=\"message.php\">message index.</p>\n");
    }
    return;
}

global $action, $mid, $reply, $ref, $subject, $content;

print_header("Message Board");

print("<table>");
print("<tr>\n");
print("<td>\n");

switch($action) {

    case "read":
      show_message( $mid );
      break;

    case "post":
      new_message( 0 );
      break;

    case "reply":
      new_message( $reply );
      break;

    case"save":
      save_message( $ref, $subject, $content );
      break;

    default:
      list_messages();
      break;
}

print("</td>\n");
print("</tr>\n");
print("</table>");

if(!isset($action)){
	print("<div class=\"bottom_button\"><a href=\"message.php?action=post\"><button>Add A New Message</button></a></div>");
}

print_footer();

?>