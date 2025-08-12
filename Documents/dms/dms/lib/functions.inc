<?php
#####################################################################
# NAME/ PURPOSE - this page holds functions used throughout the system
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


/* This function is called on the login page. This prints the page header */ 
function print_login_header($title) {

	global $user, $cfg;
	global $debug_time_start;
	
	$debug_time_start = microtime();
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
	print("<html xmlns=\"http://www.w3.org/1999/xhtml\">");
	print("<head>\n");
	print("<title>$cfg[site_name] Document Management: $title</title>\n");
	print("<meta http-equiv=\"Expires\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
	print("<meta http-equiv=\"Last-Modified\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
	print("<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\" />\n");
	print("<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n");
	print("<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />");
	print("</head>\n");
	print("<body>\n");
	print("<div id=\"logged\">");
	print("<strong>Not Logged in</strong>\n");
	print("</div>\n");
	print("<div id=\"content\">");
	print("<div id=\"inner\">");
}

/* This function prints the page header on all pages (except the login page) */
function print_header($title) {

	global $user, $cfg;
	global $debug_time_start;
	
	$debug_time_start = microtime();
	
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
	print("<html xmlns=\"http://www.w3.org/1999/xhtml\">");
	print("<head>\n");
	print("<title>$cfg[site_name] Document Management: $title</title>\n");
	print("<meta http-equiv=\"Expires\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
	print("<meta http-equiv=\"Last-Modified\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
	print("<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\" />\n");
	print("<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n");
	print("<script type=\"text/javascript\" src=\"js/confirmlogout.js\"></script>\n");
	print("<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />");
	
	print("</head>\n");
	print("<body>\n");
	print("<div id=\"logged\">");

	print("<strong>Logged in as ");

	if($user->god){
		print("(GOD) ");
	}

	print("$user->name\n");
	print("</strong></div>\n");
	print("<div id=\"navcontainer\">");
	print("<ul id=\"navlist\">");
	print("<li><a href=\"main.php\">Home</a></li>\n");
	print("<li><a href=\"contacts.php\">Contacts</a></li>\n");
	print("<li><a href=\"message.php\">Messages</a></li>\n");
	print("<li><a href=\"list.php\">List</a></li>\n");
	print("<li><a href=\"up.php\">Update</a></li>\n");
	print("<li><a href=\"new.php\">New</a></li>\n");

	if($user->god) {
		print("<li><a href=\"users.php\">Users</a></li>\n");
		print("<li><a href=\"logs.php\">Logs</a></li>\n");
	}

	print("<li><a href=\"logout.php\" onclick=\"return confirm_logout()\">Logout</a></li>\n");
	print("</ul></div>\n");
	print("<div id=\"content\">");
	print("<div id=\"inner\">");
}


/* This function prints the footer on the login page */
function print_login_footer() {

	global $cfg;
	
	if($cfg[debug]) {
		global $debug_time_start;
		$debug_time_end = microtime();
		
		$start = explode(" ", $debug_time_start);
		$end = explode(" ", $debug_time_end);
		
		$elapsed = ($end[0] - $start[0]) + ($end[1] - $start[1]);
	}
	
	print("</div>\n");
	print("<div id=\"search_footer\">For assistance, please e-mail <a href=\"mailto:$cfg[site_owner_email]\">$cfg[site_owner]</a>.</div>");
	print("</div><p id=\"v_foot\"> Grayscale Document Manager $cfg[version], &copy; 2005 <a href=\"http://www.karlcore.com\">Karl Core</a><br /> Based on <a href=\"http://www.cafuego.net\">Cafuego's</a> SDMS</p>\n");

	if( $cfg[debug] ){
		print("<br />debug: Generated in ". number_format( $elapsed, 3, ".", ",") ." seconds.\n");
	}

	print("</body>\n");
	print("</html>\n");
}


/* This function prints the footer on all pages (except the login page) */
function print_footer() {

	global $cfg;
	
	if($cfg[debug]) {
		global $debug_time_start;
		$debug_time_end = microtime();
		
		$start = explode(" ", $debug_time_start);
		$end = explode(" ", $debug_time_end);
		
		$elapsed = ($end[0] - $start[0]) + ($end[1] - $start[1]);
	}
	
	print("</div><div id=\"search_footer\">\n");
	print("<form action=\"search.php\" method=\"post\">\n");
	print("<div><label for=\"search_text\">Search For Documents:</label> <input type=\"text\" name=\"query\" />\n");
	print("<input type=\"submit\" value=\"Find!\" /></div>\n");
	print("</form>\n");
	print("</div>");
	print("</div><p id=\"v_foot\">Grayscale Document Manager $cfg[version], &copy; 2005 <a href=\"http://www.karlcore.com\">Karl Core</a><br /> Based on <a href=\"http://www.cafuego.net\">Cafuego's</a> SDMS</p>\n");

	if( $cfg[debug] ){
		print("<br />debug: Generated in ". number_format( $elapsed, 3, ".", ",") ." seconds.\n");
	}
	
	print("</body>\n");
	print("</html>\n");
}


function get_extension($filename) {
	$tmp = explode(".", $filename);
	if( sizeof($tmp) > 1){
		return strtolower($tmp[sizeof($tmp)-1]);
	}
	else{
		return "none";
	}
}

function is_god($user_id) {
	$res = @mysql_query("SELECT user FROM gods WHERE user=$user_id");
	if(mysql_errno())
		return false;
	if(mysql_num_rows($res) == 1)
		return true;
	return false;
}

function may_read($user_id, $document_id) {
	if( is_god($user_id) )
		return true;
	$res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");
	if(mysql_errno())
		return false;
	if(mysql_num_rows($res) != 1)
		return false;
	return true;
}

function may_write($user_id, $document_id) {
	if( is_god($user_id) )
		return true;
	$res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");
	if(mysql_errno())
		return false;
	if(mysql_num_rows($res) != 1)
		return false;
	$row = @mysql_fetch_array($res);
	if($row[level] != "R")
		return true;
	return false;
} 

function may_god($user_id, $document_id) {
	if( is_god($user_id) )
		return true;
	$res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");   
	if(mysql_errno())
		return false;
	if(mysql_num_rows($res) != 1)
		return false;
	$row = @mysql_fetch_array($res);
	if($row[level] == "G")
		return true;
	return false;
}

function access_string($level) {
	switch($level) {
		case "R":
			return "Read-Only";
		break;
		case "W":
			return "Read/Write";
		break;
		case "G":
			return "God Mode";
		break;
		case "X":
			return "No Access";
		break;
		default:
			return "Unknown";
		break;
	}
}

function get_access($user_id,$document_id) {
	if( may_god($user_id,$document_id))
		return "G";
	if( may_write($user_id,$document_id))
		return "W";
	if( may_read($user_id,$document_id))
		return "R";
	return "X";
}
?>