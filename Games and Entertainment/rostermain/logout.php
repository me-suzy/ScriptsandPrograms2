<?
$old_user = $valid_user; //test if user *were* logged in
$result = session_unregister("valid_user");
session_destroy();
header("index.php?page=home");
?>

<?
if (!empty($old_user))
{
if ($result)
{
// if user was logged in and are not logged out
echo "<div class=\"log\">You are now logged out.</div>";
echo "<table align=\"right\"><tr><td><a href=\"index.php?log=\">Log in here.</a></td></tr></table>";
}
else
{
// user was logged in and could not be logged out
echo "Could not log you out.";
}
}
else
{
// not logged in and accessed this page
echo "<div class=\"log\">You were not logged in.</div>";
echo "<table align=\"right\"><tr><td><a href=\"index.php?log=\">Log in here.</a></td></tr></table>";
}
?>