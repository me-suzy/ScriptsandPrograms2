<?php
	include ('useall.php');
?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Free Online Web Based Cooking Recipe Software</title>
<link href="style/style.css" rel="stylesheet" type="text/css">
<meta name="SOFTWARE_NAME" content="CookDojo Home Edition - Web Based Version">
<meta name="SOFTWARE_VERSION" content="1.12">
</head>

<body>

<?php
	if (!isset($recID))
		$recID = "";
	
		

	echo "<table width=90% align=center class=h4>\n";
	

	$recipe_title = "";
	$recipe_ingredients  = "";
	$recipe_method = "";
	$recipe_note = "";
	$q = "SELECT * FROM recipe WHERE recipeID = '$recID'";
	mysql_first_data ($q, "recipe_title|recipe_ingredients|recipe_method|recipe_note");
	
	echo "<tr><td class=h2><br><b>$recipe_title</b></td><td align=right valign=top><img src='images/logo_white.jpg'></td></tr>\n";
	echo "<tr><td colspan=2 class=h4_brown><br><b>INGREDIENTS</b></td></tr>\n";
	echo "<tr><td colspan=2 class=h4>". nl2br($recipe_ingredients) ."</td></tr>\n";
	echo "<tr><td colspan=2 class=h4_brown><br><b>METHOD</b></td></tr>\n";
	echo "<tr><td colspan=2 class=h4>". nl2br($recipe_method) ."</td></tr>\n";
	
	if (!empty($recipe_note))
	{
		echo "<tr><td colspan=2 class=h4_brown><br><b>NOTE</b></td></tr>\n";
		echo "<tr><td colspan=2 class=h4>". nl2br($recipe_note) ."</td></tr>\n";
	}
	
	echo "</table>\n";
	
?>
</body>
</html>