<?
session_start();
if(!session_is_registered("client_id"))
{
header("Location: index.htm");
exit;
}
if ($client_name !== 'admin')
{
header("Location: index.htm");
exit;
}
?>
<HTML>
<link rel="stylesheet" href="inc/style.css" type="text/css">
<body>
<img src="inc/title.gif" width="308" height="82">
  <blockquote>
  <? if ($id) {
echo   "<h1>Edit client</h1>";
   }
 else {
  echo " <h1>Add client</h1>";
  }
  ?>
  <?php
include("inc/dbconnect.php");
if($submit && ($passwd === $passwd2))
{

$sql = "INSERT INTO clients (name, password, email, title, ref) VALUES ('$name',PASSWORD('$passwd'),'$email','$title','$ref')";
$result = mysql_query($sql);
echo "<p>Thank you: a new client has been created</p>";
echo "<p>To view client details, please <a href=clients.php>see the client list</a>.</p>";
}
else if($update && ($passwd === $passwd2))
{
$sql = "UPDATE clients SET name='$name',password=PASSWORD('$passwd'),email='$email',title='$title',ref='$ref' WHERE clientid=$id";
$result = mysql_query($sql);
echo "<p>Thank you: the client details have been updated.</p>";
echo "<p><a href=clients.php>Return to client list</a></p>";
}
else if ($passwd !== $passwd2) {
echo "You entered two different passwords. <a href=$PHP_SELF?id=$id>Please try again</a>";
}
else if($id)
{
$result = mysql_query("SELECT * FROM clients WHERE clientid=$id",$db);
$myrow = mysql_fetch_array($result);
?>
  <form method="post" action="<?php echo $PHP_SELF?>">
    <p><a href="clients.php">return to clients details</a></p>
    <p>
      <input type="hidden" name="id" value="<?php echo $myrow["clientid"]?>">
      User name:<br>
      <input type="text" name="name" size="20" value="<?php echo $myrow["name"]?>">
      <br>
      Enter NEW password (this must not be left blank):<br>
          <input type="password" name="passwd" size="20">
    <br>
	Re-type password:<br>
    <input type="password" name="passwd2" size="20">
      <br>
      email:<br>
          <input type="text" name="email" size="20" value="<?php echo $myrow["email"]?>">
    <br>
      title:<br>
          <input type="text" name="title" size="20" value="<?php echo $myrow["title"]?>">
    <br>
      ref:<br>
      <input type="text" name="ref" size="5" value="<?php echo $myrow["ref"]?>" maxlength="5">
          <br>
    <br>
      <input type="Submit" name="update" value="Update information">
    </p>
    </form>
  <?

}
else
{
?>
  <form method="post" action="<?php echo $PHP_SELF?>">
      <p><a href="clients.php">return to clients details</a></p>
      <p>
    User name:<br>
    <input type="text" size="20" name="name" >
    <br>
    Password:<br>
    <input type="password" name="passwd" size="20">
    <br>
    Re-type password:<br>
    <input type="password" name="passwd2" size="20">
    <br>
    email:<br>
    <input type="text" name="email" size="20">
    <br>
    title:<br>
    <input type="text" name="title" size="20">
    <br>
     ref:<br>
          <input type="text" name="ref" size="5" maxlength="5">
          <br>
    <br>
    <input type="Submit" name="submit" value="Enter information">
    </p>
  </form>
</blockquote>
<?
}
include "inc/nav.inc";
include "inc/footer.inc";
?>
</body>
</HTML>