<?php // SCRIPT DE RECHERCHE POUR LES NOMS D'ARTISTES COMMENCANT PAR UN CHIFFRE

require("presentation.inc.php");

HAUTPAGEWEB('Discoman - Lyrics search by artist');
LAYERS2();

include("link.inc.php");

$type="".@$_GET[type]."";
$search="".@$_GET[search]."";

 $query = "SELECT
                                       nom
                                 FROM
                                       disco_artistes
                                 WHERE
                                 		nom LIKE '0%' OR
                                        nom LIKE '1%' OR
                                        nom LIKE '2%' OR
                                        nom LIKE '3%' OR
                                        nom LIKE '4%' OR
                                        nom LIKE '5%' OR
                                        nom LIKE '6%' OR
                                        nom LIKE '7%' OR
                                        nom LIKE '8%' OR
                                        nom LIKE '9%'";

  // ************* search *****************/

 $result = mysql_query($query) or die(mysql_error());
 $numrows = mysql_num_rows($result); // result of count query
 $totres = $numrows;//mémo total résultats
// ************** pager **************************
include ("pager.inc.php");
// ************** end of pager **************************

 if($numrows == 0)
 {
LAYERINTERNE();
	echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Results for 0-9 artists</th>
       		</tr>
            <tr>
            	<td  bgcolor=\"#FFFFFF\"><b>No record found</b></td>
            </tr>
        </table></div></div>\n";
 }
 else
 {

     $query = "SELECT
                                       	nom
                                 FROM
                                 		disco_artistes
                                 WHERE
                                 		nom LIKE '0%' OR
                                        nom LIKE '1%' OR
                                        nom LIKE '2%' OR
                                        nom LIKE '3%' OR
                                        nom LIKE '4%' OR
                                        nom LIKE '5%' OR
                                        nom LIKE '6%' OR
                                        nom LIKE '7%' OR
                                        nom LIKE '8%' OR
                                        nom LIKE '9%'";

     $query .= " ORDER BY nom ASC"; // add query ORDER
     $query .= " LIMIT ".$_GET['page'].", $limit"; // add query LIMIT

     $result = mysql_query($query) or die(mysql_error());
     $numrows = mysql_num_rows($result);

     //echo our table

     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
         echo "<th>".ucfirst("artist")."</th>\n";

     $i = 0;

     while ($row = mysql_fetch_assoc($result))
     {
        // alternate color
        if($i%2 == 0)
               echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"queries.php?form_artiste=".$row['nom']."\"'>\n";
        else
        echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"queries.php?form_artiste=".$row['nom']."\"'>\n";

         echo "<td>&nbsp".$row["nom"]."</td>\n";
        echo "</tr>\n";
        $i++;
     }
     echo "</table></div>\n";
     mysql_free_result($result);
 }

// ************** bottom pager  **************************
LAYERPAGEDEB();

include ("bottompager.inc.php");
if ($numrows==0) echo "<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";

LAYERPAGEFIN();
// ************** end of bottom pager **************************

 mysql_close($link);

BASPAGEWEB2();
?>