<?

include ("functions.php");
//if the form is submitted corectly we process addcomment()
if (($action == "add") && (($nick != "") && ($comments != ""))) addcomment();

//displaying the page result
echo "
<html>
<head></head>
<body leftmargin=0 topmargin=0 marginwidth=\"0\" marginheight=\"0\">
  <table align=\"center\" width=\"90%\" border=\"0\">
";

include ("comments.txt");

echo "  </table>
</body>
</html>
";

?>
