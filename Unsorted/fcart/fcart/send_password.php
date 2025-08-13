<?
include "config.php";
include "mod.php";
include "params.php";
include "processt.php";

include "cookie.php";

$uname = r_secure($HTTP_POST_VARS["uname"]);

$eresult = mysql_query("select login,password,firstname,lastname,email from customers where login='$uname' or email='$uname'");
if (list($login, $password, $firstname, $lastname, $email) = mysql_fetch_row($eresult)) {
	mysql_free_result($eresult);
	process_template_retrieve_password($mail_pwd, $login, $password, $firstname, $lastname);
	mail($email, $mail_pwd_subj, $mail_pwd, "From: $support_email\nReply-To: $support_email\nX-Mailer: PHP/".phpversion());
	echo "The password will be shortly sent to $uname";
} else {
	echo "Invalid username or e-mail: $uname";
}
?>
