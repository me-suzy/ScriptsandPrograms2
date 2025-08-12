<?php
#####################################################################
# NAME/ PURPOSE - this file processes file uploads
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

function upload_failed($message) {
	global $userfile;
	
	// Trash it.
	@unlink($userfile);
	
	print("<h2>Error: $message</h2>\n");
	print_footer();
	exit;
}

function add_standard_access($document_id,$level = "R") {
	global $user;
	// Owner access.
	@mysql_query("INSERT INTO ACL(user_id,document_id,level) VALUES($user->id,$document_id,'W')");
	
	// Others - set what was specified.
	switch($level) {
		case "X":
			break;
		default:
			$res = @mysql_query("SELECT id FROM users");
			while($row = @mysql_fetch_array($res)){
				@mysql_query("INSERT INTO ACL(user_id,document_id,level) VALUES($row[id],$document_id,'$level')");
			}
			break;
	}
	return;
}

print_header("Uploading Document");

if(!isset($userfile)){
    upload_failed("Document was not found");
}

if(!file_exists($userfile)){
    upload_failed("Document was not uploaded");
}

$fp = fopen($userfile, "r");
if(!$fp)
upload_failed("Cannot open uploaded documentile");
$content = fread($fp, $userfile_size);
fclose($fp);
unlink($userfile);

$res = @mysql_query("INSERT INTO documents(name,type,size,author,maintainer,revision,created) VALUES('$userfile_name','$userfile_type',$userfile_size,$user->id,$user->id,1,NOW())");

switch( mysql_errno() ) {

	case 0:
		$doc_id = mysql_insert_id();
		@mysql_query("INSERT INTO documents_content(id,content) VALUES($doc_id,'". base64_encode($content) ."')");
		if(mysql_errno() ) {
			$error = mysql_error();
			@mysql_query("DELETE FROM documents WHERE id=$doc_id");
			upload_failed( "Index ($doc_id) succeeded, but content failed<br>Error: $error" );
		}
		else {
			if($info) {
				@mysql_query("INSERT INTO documents_info(id,info) VALUES($doc_id,'". addslashes($info) ."')");
				if(mysql_errno() ) {
					$error = mysql_error();
					@mysql_query("DELETE FROM documents WHERE id=$doc_id");
					@mysql_query("DELETE FROM documents_content WHERE id=$doc_id");
					upload_failed( "Index ($doc_id) and content succeeded, but info failed<br>Error: $error" );
				}
			}
		}
		add_standard_access($doc_id,$level);
		$keywords = ereg_replace(",", " ", $keywords);
		$keywords = ereg_replace("  ", " ", $keywords);
		$keywords = explode(" ", $keywords);
		$keyword = current($keywords);
		echo "<h2>Uploaded ". htmlspecialchars(stripslashes($userfile_name)) ." ($userfile_size bytes) as Document ID $doc_id</h2>\n";
		echo "<h3>Using keywords: \n";
		do {
			@mysql_query("INSERT INTO documents_keywords(id,keyword) VALUES($doc_id,'". addslashes($keyword) ."')");
			if(mysql_errno())
			echo "<br />Error, $keyword not saved\n";
			else
			echo "<br />$keyword\n";
		} while ($keyword = next($keywords));
		echo "</h3>\n";
		break;
	
	default:
		upload_failed( mysql_error() );
		break;

}

print_footer();

?>