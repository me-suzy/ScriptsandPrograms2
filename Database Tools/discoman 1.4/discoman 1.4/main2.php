<?php

//SCRIPT DE RECHERCHE DES artistes PAR ORDRE ALPHABETIQUE

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Titles search by artist');
LAYERS2();

include("link.inc.php");
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);

$search="".@$_GET[search]."";
$search=strtoupper($search);

 $query = "
 	SELECT
    	nom
    FROM
    	disco_artistes
    WHERE
    	nom LIKE '{$_GET['search']}%'";

 $result = mysql_query($query) or die(mysql_error());
 $numrows = mysql_num_rows($result); // result of count query
 $totres = $numrows;//mémo total résultats

// ************** pager **************************
include ("pager.inc.php");
// ************** end of pager **************************

if ($search == '%') $search = 'all';

	if($numrows == 0){
		LAYERINTERNE();
		echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>".$txt_resultat." ".$search."</th>
       		</tr>
            <tr>
            	<td  bgcolor=\"#FFFFFF\"><b>".$txt_no."</b></td>
            </tr>
        </table></div></div>\n";
		}
 	else
 	{
     $query = "
     	SELECT
        	nom,
        	id_artiste
        FROM
        	disco_artistes
        WHERE
        	nom LIKE '{$_GET['search']}%'
        ORDER BY
      		nom ASC";

     $query .= " LIMIT ".$_GET['page'].", $limit"; // add query LIMIT
     $result = mysql_query($query) or die(mysql_error());
     $numrows = mysql_num_rows($result);

     //echo our table

     echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
         echo "<th>".$txt_resultat." ".$search."</th>\n";

     $i = 0;

     while ($row = mysql_fetch_assoc($result))
     {
        // alternate color
        if($i%2 == 0)
        	echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"queries.php?form_id=".$row['id_artiste']."&nom_artiste=".urlencode($row["nom"])."\"'>\n";
        else
        	echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"queries.php?form_id=".$row['id_artiste']."&nom_artiste=".urlencode($row["nom"])."\"'>\n";

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