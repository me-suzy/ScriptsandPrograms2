<?

echo"
<form method=\"post\" action=\"result.php\">
<input type=\"hidden\" name=\"action\" value=\"add\">
<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td><font class=\"normal\">Nick:</font></td>
    <td><input class=\"box\" type=\"text\" name=\"nick\" size=\"15\"></td>
  </tr>
  <tr>
    <td><font class=\"normal\">Comments:</font></td>
    <td><input class=\"box\" type=\"text\" name=\"comments\" size=\"15\"></td>
  </tr>
  <tr><td><br></td></tr>
  <tr>
    <td colspan=\"2\"><center><input class=\"box\" type=\"submit\" name=\"submit\" value=\"Send message\"></center></td>
  </tr>
</table>
</form>
";

?>
