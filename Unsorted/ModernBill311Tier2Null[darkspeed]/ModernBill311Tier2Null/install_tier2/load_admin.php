<ul>
<?
$stop=NULL;
$password = $HTTP_POST_VARS["password"];
$username = $HTTP_POST_VARS["username"];
$realname = ($realname) ? strip_tags($realname) : "Administrator";
$email    = ($email)    ? strip_tags($email)    : "admin@modernbill.com";
$username = ($username) ? strip_tags($username) : "admin";
$password = ($password) ? strip_tags($password) : "admin";
if($delete) $result = mysql_query("DELETE FROM admin");
$result = mysql_query("INSERT INTO admin VALUES (NULL,'$realname','$email','$username','".md5($password)."','9');");
if ($result) {
    echo "<li> --> <font color=blue>Admin User: <b>($username)</b> added successfully.</font><br>";
} else {
    echo "<li> --> <font color=blue>Administrator inserted successfully!</font>";
}
?>
</ul>
<br>

