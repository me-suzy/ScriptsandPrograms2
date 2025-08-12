<?
require('../prepend.inc.php');
include("header.inc.php");

?>
<?
include("../templates/admin-header.txt");
?>
<? $result = mysql_query("SELECT email FROM `demo_a_accounts`"); 

       while ($myrow = mysql_fetch_row($result)) {
mail("$myrow[0]", "$betreff", "$text","From: $seitenname <$emailadresse>"); 
echo"Erledigt: $myrow[0]<br>";
}; ?>
<?
include("../templates/admin-footer.txt");
?>