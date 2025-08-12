<?
include("header.inc.php");
?>
<?
global $email;
        $query="DELETE FROM demo_a_accounts WHERE email='$email'";
        mysql_query($query);
?>
<?
include("../templates/admin-header.txt");
?>
<center><font size=3>User with e-mail <?php echo "$email"; ?> was deleted from database</center>