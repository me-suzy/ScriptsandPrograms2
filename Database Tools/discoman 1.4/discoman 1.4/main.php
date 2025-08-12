<?php

$type="".@$_GET[type]."";
$search="".@$_GET[search]."";

if ($search != '') {
	switch ($type)
	{
	CASE "1":   // CHOIX D'UN ARTISTE
		include ("main2.php");

		break;

	CASE "2": // CHOIX D'UN TITRE

		$titre="".@$_GET[search]."";
		include ("queries.php");

		break;

	CASE "3": // CHOIX D'UNE REF

		$ref="".@$_GET[search]."";
		include ("queries.php");

		break;

	CASE "4": // CHOIX D'UN COMMENTAIRE

		$com="".@$_GET[search]."";
		include ("queries.php");

		break;
	}
}

else {

require("presentation.inc.php");
HAUTPAGEWEB('disco search');
LAYERS();

LAYERINTERNE();
	echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
				<th colspan=2>Results</th>
    		</tr>
            <tr>
            	<td  bgcolor=\"#FFFFFF\"><b>No record found.</b></td>
            </tr>
        </table></div></div>\n";

LAYERPAGEDEB();

echo "<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
LAYERPAGEFIN();

BASPAGEWEB();

}

?>