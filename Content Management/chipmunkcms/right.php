<?php
print "<table class='maintables'><tr class='headline'><td><b><font color='white'><center>News Categories</center></font></b></td></tr>";
print "<tr class='mainrow'><td>";
$getnewscats="SELECT * from b_artcats order by categoryid ASC";
$getnewscats2=mysql_query($getnewscats) or die("Could not get article categories");
while($getnewscats3=mysql_fetch_array($getnewscats2))
{
  print "<li><A href='cat.php?ID=$getnewscats3[categoryid]'>$getnewscats3[categoryname]</a><br>";
}
print "</td></tr></table><br><br>";
?>
<table class='maintables'><tr class='headline'><td>Search Articles</td></tr>
<tr class='mainrow'><td><form action='searchart.php' method='post'><input type='text' name='phrase' size='20'><br>
<input type='submit' name='submit' value='search'></form></td></tr></table><br><br>
<table class='maintables'><tr class='headline'><td><b><font color='white'><center>Site sections</center></font></b></td></tr>
<tr class='mainrow'><td>
<li><A href="board/index.php">Community</a><br>
<li><A href="catalog/index.php">Link Directory</a><br>
</td></tr></table>