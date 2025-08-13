
<? 
include ("config.inc.php");
include ("header.php");
?>
<?

mail("$email", "Your website","Hi $name !, $sendername at $senderemail wanted you to check out this product at $link.", "From: $senderemail\n\n" );
echo "<div align=\"center\"><table width=\"96%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" ";
echo "style=\"font-size:11pt;font-color:black;\">";
echo "<tr height=\"40\" align=center><td><p>&nbsp;</p></td></tr>";

echo "<tr><td >An e-mail  with the link was sent to  <font color=#ED1820 >$name</font> at <a href=\"mailto:$email\">$email</a> from <font color=#ED1820 >$sendername</font> at <a href=\"mailto:$senderemail\">$senderemail</a>, now also  <font color=#ED1820 >$name</font> your friend ";
echo "  will know about the link <a href=$link target=_blank>$link </a></td></tr>";
echo "</table></div>";
?>

<? include ("footer.php");?>