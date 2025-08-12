<form name="FormName" action="vote.php" method="post">

<?php
$i=0;
while($i<5){
$entry[id]=$i;
print"<br><input name=\"art_$i\" type=\"checkbox\" value=\"$entry[id]\">Art";
print"<br><input name=\"concept_$i\" type=\"checkbox\" value=\"$entry[id]\">Concept";
print"<br><input name=\"pop_$i\" type=\"checkbox\" value=\"$entry[id]\">Popular";
$i++;
}
?>

<br><input type="submit" name="act" value="Send">

</form>