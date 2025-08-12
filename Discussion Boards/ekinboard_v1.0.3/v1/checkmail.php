<?PHP
// Makes sure the user is logged in
if ($_userid != NULL){
	// First checks the database for any unread messages
	$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='". $_userid ."' AND message_read='0'");

	// Now counts them
	$new_mail = mysql_num_rows($check_mail);
}
?>