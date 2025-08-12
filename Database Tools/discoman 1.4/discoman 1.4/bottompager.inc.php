<?
if ($totres > 1) $res="résultats";
else $res="résultat";

echo "$totres $res - \n";

if (!empty($_GET['page']) && $_GET['page'] > 0) //affichage du previous page
	{
   	$back_page = $_GET['page'] - $limit;
   	$url = $_SERVER["PHP_SELF"]."?page=$back_page";

    if(!empty($_GET['form_artiste']))$url .= "&form_artiste=".$_GET['form_artiste'];//OK vérifié
    if(!empty($_GET['form_id']))$url .= "&form_id=".$_GET['form_id'];//OK vérifié
	if(!empty($_GET['form_formats'])) $url .= '&form_formats='.$form_formats;//tableau formats serialisé
    if(!empty($_GET['form_pays'])) $url .= '&form_pays='.$form_pays;
    if(!empty($_GET['form_annee2']))$url .= "&form_annee1=".$_GET['form_annee1'];//OK vérifié
    if(!empty($_GET['form_annee2']))$url .= "&form_annee2=".$_GET['form_annee2'];//OK vérifié
	if(!empty($_GET['form_ref']))$url .= "&form_ref=".$_GET['form_ref'];//OK vérifié
	if(!empty($_GET['form_com']))$url .= "&form_com=".$_GET['form_com'];//OK vérifié
	if(!empty($_GET['form_titres']))$url .= "&form_titres=".$_GET['form_titres'];//OK vérifié

   	if(!empty($_GET['type']) && !empty($_GET['search']))$url .= "&type=".$_GET['type']."&search=".$_GET['search']; // add search

   	echo "<a href='".stripslashes($url)."'>[<< previous]</a>\n";
	}

	// loop through each page and give link to it.
	// no page when only one page

if($pages > 1){//affichage de la page en cours et des autres pages

    for ($i=1; $i <= $pages; $i++){
        $ppage = $limit*($i - 1);
        if ($ppage == $_GET['page']) echo("<b>$i</b> \n");
        else {
        	$url = $_SERVER["PHP_SELF"]."?page=$ppage";

   	if(!empty($_GET['form_artiste']))$url .= "&form_artiste=".$_GET['form_artiste'];//OK vérifié
    if(!empty($_GET['form_id']))$url .= "&form_id=".$_GET['form_id'];//OK vérifié
	if(!empty($_GET['form_formats'])) $url .= '&form_formats='.$form_formats;//tableau formats serialisé
    if(!empty($_GET['form_pays'])) $url .= '&form_pays='.$form_pays;
    if(!empty($_GET['form_annee2']))$url .= "&form_annee1=".$_GET['form_annee1'];//OK vérifié
    if(!empty($_GET['form_annee2']))$url .= "&form_annee2=".$_GET['form_annee2'];//OK vérifié
    if(!empty($_GET['form_ref']))$url .= "&form_ref=".$_GET['form_ref'];//OK vérifié
    if(!empty($_GET['form_com']))$url .= "&form_com=".$_GET['form_com'];//OK vérifié
	if(!empty($_GET['form_titres']))$url .= "&form_titres=".$_GET['form_titres'];//OK vérifié

	if(!empty($_GET['type']) && !empty($_GET['search']))$url .="&type=".$_GET['type']."&search=".$_GET['search'];

    echo "<a href='".stripslashes($url)."'>".$i."</a>\n";
    }
}

if(((($_GET['page']+$limit) / $limit) < $pages) && $pages != 1){//affichage de next
   	$next_page = $_GET['page'] + $limit;// If last page don't give next link.

   	$url = $_SERVER["PHP_SELF"]."?page=$next_page";

   	if(!empty($_GET['form_artiste']))$url .= "&form_artiste=".$_GET['form_artiste'];//OK vérifié
    if(!empty($_GET['form_id']))$url .= "&form_id=".$_GET['form_id'];//OK vérifié
	if(!empty($_GET['form_formats'])) $url .= '&form_formats='.$form_formats;//tableau formats serialisé
    if(!empty($_GET['form_pays'])) $url .= '&form_pays='.$form_pays;
    if(!empty($_GET['form_annee2']))$url .= "&form_annee1=".$_GET['form_annee1'];//OK vérifié
    if(!empty($_GET['form_annee2']))$url .= "&form_annee2=".$_GET['form_annee2'];//OK vérifié
   	if(!empty($_GET['form_ref']))$url .= "&form_ref=".$_GET['form_ref'];//OK vérifié
   	if(!empty($_GET['form_com']))$url .= "&form_com=".$_GET['form_com'];//OK vérifié
	if(!empty($_GET['form_titres']))$url .= "&form_titres=".$_GET['form_titres'];//OK vérifié

	echo "<a href='".stripslashes($url)."'>[next >>]</a>\n";
	}

//if(((($_GET['page']+$limit) / $limit) == $pages) && $pages != 1){//affiche un next sans lien
//	echo "<a>[next >>]</a>\n";
//	}
}
?>