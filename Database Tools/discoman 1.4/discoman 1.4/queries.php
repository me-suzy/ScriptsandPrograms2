<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Disco search');
LAYERS();

include("link.inc.php");
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);

$id="".@$_GET[form_id]."";//id de l'artiste
$nom="".@$_GET[form_artiste]."";
$nom_artiste="".@$_GET[urldecode(nom_artiste)]."";//pour l'en-tête des résultats si aucun résultat
$an1="".@$_GET[form_annee1]."";
$an2="".@$_GET[form_annee2]."";
$form_formats=@$_GET[form_formats];//tableau formats
if (is_Array($form_formats)) $form_formats=addslashes(urlencode(serialize($form_formats)));
$form_pays=@$_GET[form_pays];//tableau pays
if (is_Array($form_pays)) $form_pays=addslashes(urlencode(serialize($form_pays)));

if ($ref=="") $ref="".@$_GET[form_ref]."";
if ($com=="") $com="".@$_GET[form_com]."";
if ($titre=="") $titre="".@$_GET[form_titres]."";

$variables = $HTTP_GET_VARS;

$texte = "";
$texte2 = "";

if ($variables != '') {

while (list($clé, $valeur) = each($variables))
{

  switch ($clé)
  { case "form_pays" :
 $valeur = unserialize(urldecode(stripslashes($form_pays)));

      $i=0;
      while (list($num, $gr) = each ($valeur))
      {
      $i++;
      if ($i==1) $texte .=" AND
	(disco_pays.id_pays LIKE '$gr'";
      if ($i>1) $texte .=" OR
	disco_pays.id_pays LIKE '$gr'";
    if ($gr == '') $texte='';
    }
    if ($texte!='') $texte .=")";//si un texte existe, ferme la parenthèse
    break;

   case "form_formats" :

 $valeur = unserialize(urldecode(stripslashes($form_formats)));

      $i=0;
      while (list($num, $gr) = each ($valeur))
      {
      $i++;
      if ($i==1) $texte2 .=" AND (";
      if ($i>1) $texte2 .=" OR ";
	$texte2 .="disco_formats.id_type LIKE '$gr'";
    if ($gr == '') $texte2='';
    }
    if ($texte2!='') $texte2 .=")";//si un texte existe, ferme la parenthèse

      break;

	}
}
}

$query="
	SELECT
    	disco_artistes.nom,
      	disco_disques.id_disque,
      	disco_formats.type,
      	disco_disques.date,
      	disco_pays.abrege,
      	disco_disques.reference,
      	disco_titres.titre,
      	disco_disques.commentaire
	FROM
		disco_artistes,
        disco_disques,
        disco_formats,
        disco_pays,
        disco_titres";

//query artistes
if ($nom != '') $query .="
WHERE
	disco_artistes.nom LIKE '%$nom%' AND
    disco_artistes.id_artiste = disco_disques.artiste";
else $query .="
WHERE
	disco_artistes.id_artiste = disco_disques.artiste";

//query id
if ($id != '') $query .=" AND
    disco_artistes.id_artiste = '$id'";

//query formats
if ($texte2 != '') $query .="$texte2";
$query .=" AND
	disco_formats.id_type = disco_disques.format";

//query date
if ($an2 != '' && $an1 == 1) $query .=" AND
    disco_disques.date LIKE '$an2'";
if ($an2 != '' && $an1 == 2) $query .=" AND
    disco_disques.date > '$an2'";
if ($an2 != '' && $an1 == 3) $query .=" AND
    disco_disques.date < '$an2'";

//query pays
if ($texte != '') $query .="$texte";
$query .=" AND
	disco_pays.id_pays = disco_disques.pays";

//query ref
if ($ref != '') $query .=" AND
    disco_disques.reference LIKE '%$ref%'";

//query commentaire
if ($com != '') $query .=" AND
    disco_disques.commentaire LIKE '%$com%'";

//query titres
if ($titre != '') $query .=" AND
	disco_titres.titre LIKE '%$titre%' AND
    disco_titres.id_titre = disco_disques.titre";
else $query .=" AND
	disco_titres.id_titre = disco_disques.titre";

 $result = mysql_query($query) or die(mysql_error());
 $numrows = mysql_num_rows($result); // result of count query
 $totres = $numrows;//mémo total résultats

// ************** pager **************************
include ("pager.inc.php");
// ************** end of pager **************************

if($numrows == 0) {
LAYERINTERNE();
	echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>".$txt_resultat." <b>".stripslashes($nom_artiste)."</b></th>
       		</tr>
        </table>
        <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
        	<tr>
            	<td><b>".$txt_no."</b></td>
            </tr>
        </table></div></div>\n";
	}
 else
 {
$query="
	SELECT
    	disco_artistes.nom,
      	disco_disques.id_disque,
      	disco_formats.type,
      	disco_disques.date,
      	disco_pays.abrege,
      	disco_disques.reference,
      	disco_titres.titre
	FROM
		disco_artistes,
        disco_disques,
        disco_formats,
        disco_pays,
        disco_titres
	WHERE ";

//query artistes
if ($nom != '') $query .="
	disco_artistes.nom LIKE '%$nom%' AND
    disco_artistes.id_artiste = disco_disques.artiste";
else $query .="
	disco_artistes.id_artiste = disco_disques.artiste";

//query id
if ($id != '') $query .=" AND
    disco_artistes.id_artiste = '$id'";

//query formats
if ($texte2 != '') $query .="$texte2";
$query .=" AND
	disco_formats.id_type = disco_disques.format";

//query date
if ($an2 != '' && $an1 == 1) $query .=" AND
    disco_disques.date LIKE '$an2'";
if ($an2 != '' && $an1 == 2) $query .=" AND
    disco_disques.date > '$an2'";
if ($an2 != '' && $an1 == 3) $query .=" AND
    disco_disques.date < '$an2'";

//query pays
if ($texte != '') $query .="$texte";
$query .=" AND
	disco_pays.id_pays = disco_disques.pays";

//query ref
if ($ref != '') $query .=" AND
    disco_disques.reference LIKE '%$ref%'";

//query commentaire
if ($com != '') $query .=" AND
    disco_disques.commentaire LIKE '%$com%'";

//query titres
if ($titre != '') $query .=" AND
	disco_titres.titre LIKE '%$titre%' AND
    disco_titres.id_titre = disco_disques.titre";
else $query .=" AND
	disco_titres.id_titre = disco_disques.titre";

 // ************* end of search *****************/
     $query .= " ORDER BY disco_artistes.nom ASC, disco_disques.date ASC, disco_formats.type ASC, disco_titres.titre ASC, disco_pays.abrege ASC"; // add query ORDER
     $query .= " LIMIT ".$_GET['page'].", $limit"; // add query LIMIT
     $result = mysql_query($query) or die(mysql_error());
     $numrows = mysql_num_rows($result);

     //echo our table

     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
         echo "<th width=\"30%\">".$txt_artistes."</th>\n";
         echo "<th width=\"5%\">".$txt_annees."</th>\n";
         echo "<th width=\"5%\">".$txt_formats."</th>\n";
         echo "<th width=\"5%\">".$txt_payss."</th>\n";
         echo "<th width=\"15%\">".$txt_refs."</th>\n";
         echo "<th width=\"40%\">".$txt_titres."</th>\n";

     $i = 0;

     while ($row = mysql_fetch_assoc($result))
     {
        // alternate color
        if($i%2 == 0)
               echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"title.php?id=".$row['id_disque']."\"'>\n";
        else
        echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"title.php?id=".$row['id_disque']."\"'>\n";

        echo "<td>".stripslashes($row["nom"])."</td>\n";
        echo "<td>".$row["date"]."</td>\n";
        echo "<td>".$row["type"]."</td>\n";
        echo "<td>".$row["abrege"]."</td>\n";
        echo "<td>".$row["reference"]."</td>\n";
		$row['titre']=eregi_replace("\n","\n<br>",$row['titre']);//
        echo "<td>".$row["titre"]."</td>\n";

        echo "</tr>\n";
        $i++;
     }
     echo "</table></div>\n";

     mysql_free_result($result);
 }

// ************** bottom pager  **************************
LAYERPAGEDEB2();

include ("bottompager.inc.php");

if ($numrows==0) echo "<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
LAYERPAGEFIN();

mysql_close($link);

BASPAGEWEB2();
?>