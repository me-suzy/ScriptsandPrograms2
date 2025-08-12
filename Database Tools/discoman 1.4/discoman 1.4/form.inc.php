<?php

function affichformat($num,$typ) {//1=search, 2=add, 3=update

	include("link.inc.php");

 	$result = mysql_query("
    	SELECT
        	id_type,
            type
        FROM
        	disco_formats
        ORDER BY
        	type ASC") or die(mysql_error());

    $a=0;//variable initialisée à 0 pour la construction du tableau
    $b = mysql_num_rows($result);//compte le nbre de résultats
    echo "<table cellspacing=\"5\">";

     while ($row = mysql_fetch_assoc($result))
     {
        $a=$a+1;
        if ($a==1) echo "<tr align='right'>";
		if ($num==1) echo "<td>".$row["type"]."<input type=\"checkbox\" name=\"form_formats[]\" value='".$row["id_type"]."'></td>";
		if ($num==2) echo "<td>".$row["type"]."<input type=\"radio\" name=\"form_formats\" value='".$row["id_type"]."'></td>";
		if ($num==3) {
			if ($typ==$row['type']) echo "<td>".$row["type"]."<input type=\"radio\" name=\"form_formats\" checked value='".$row["id_type"]."'></td>";
    		else echo "<td>".$row["type"]."<input type=\"radio\" name=\"form_formats\" value='".$row["id_type"]."'></td>";
		}
        if ($a==8 && $b>8) {
        echo "</tr>";
        $a=0;//réinitialise la variable à 0
        }
     }
     echo "</tr></table>";
     mysql_free_result($result);
	 mysql_close($link);
}

function affichpays($num,$pay) {//1=search, 2=add, 3=update

	include("link.inc.php");

 	$result = mysql_query("
 		SELECT
        	id_pays,
            abrege
        FROM
        	disco_pays
        ORDER BY
        	abrege ASC") or die(mysql_error());

		if ($num==1) echo"<select name='form_pays[]' multiple><option value='' selected><br>";
		if ($num==2) echo"<select name='form_pays'><option value='' selected><br>";
		if ($num==3) echo"<select name='form_pays'>";

     while ($row = mysql_fetch_assoc($result))
     {
     	if ($num==3 && $pay==$row["abrege"]) {
            echo "<option value='".$row["id_pays"]."' selected>".$row["abrege"]."<br>";
            }
        else echo "<option value='".$row["id_pays"]."'>".$row["abrege"]."<br>";
     }
     echo"</select>";
     mysql_free_result($result);

      mysql_close($link);
}

function affichartiste($memonom) {

	include("link.inc.php");

 	$result = mysql_query(
    	"SELECT
        	id_artiste,
            nom
        FROM
        	disco_artistes
        ORDER BY
        	nom ASC") or die(mysql_error());

    echo "<select name='form_artiste'>";

    if ($memonom=="") echo "<option value='' selected><br>";
    while ($row = mysql_fetch_assoc($result))
    	{
        if ($row["id_artiste"]!=$memonom) echo "<option value='".$row["id_artiste"]."'>".stripslashes($row["nom"])."</option>";
        else echo "<option value='".$row["id_artiste"]."' selected>".stripslashes($row["nom"])."</option>";
		}

    echo"</select>";
    mysql_free_result($result);
	mysql_close($link);
}

?>