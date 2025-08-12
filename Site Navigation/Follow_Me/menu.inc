<?
/*
---------------------------------------

FOLLOW ME - AUTOMATIC DYNAMIC MENUS - V1.1

---------------------------------------
Copyright (C) 2004, Patrice Grometto [Le Grom]
*/


// Variables
if ( empty($_GET['valid'])  ) {
        $_GET['valid'] = '[ss]';
}
$_GET['valid'] = $_GET['valid'];
$valid = $_GET['valid'];



//



// Tableau
$Debut="<table> "  ;
$Fin=" </table>";
// autres variables
  $R="[r]";
  $S="[s]";
  $SS="[ss]";
  if (!isset($sousrep)) {$sousrep="";
  }
  $compteur_section="0";
  // Ouverture fichier texte
$myfilee = fopen($sousrep."content.txt", "r");
// construction du tableau
echo $Debut;
// boucle analyse
$counter=0;
if(!$myfilee)
{
print("erreur pas de lecture possible");
exit;
}
 $deter_ssection="0";
 // examen fichier    ----------
while(!feof($myfilee))
{
//lire une ligne de fichier
$myline=fgets($myfilee,255);
//DETER URL COURANTE
   $compteur="0";
   $var="0";

   $url=$_SERVER['PHP_SELF'];
$acount=substr_count($url,"/");
      $ruri=strtok($url,"/");
       for ($t =$compteur; $t<$acount ; $t++)
       {
            $ruri=strtok("/");
       }
// recherche de l'url
if (preg_match("/](.*)#/s", $myline, $m)) $affiche= nl2br(htmlspecialchars($m[1]));
if (preg_match("/#(.*)!/s", $myline, $m)) $lien= nl2br(htmlspecialchars($m[1]));
$report="";

// Gestion des affichages lignes tableau

// Rubrique
$Rubrique="<tr><td><b>".$affiche."<b></td></tr>";

$RubriqueLien="<tr><td><b><a href=\"".$report.$lien."\">".$affiche."</a></b></td></tr>    ";

// Section
$Section=" <tr>
                <td>
               ".$affiche."</td>
              </tr>  ";
      $SectionLien=" <tr>
                <td>
                <a href=\"".$report.$lien."\">".$affiche."</a></td>
              </tr>  ";


// Sous Section
$SousSection =" <tr>

                <td  >
                &#149;&nbsp;".$affiche."</td>
              </tr>   ";

            $SousSectionLien =" <tr>

                <td >
                <a href=\"".$lien."?valid=".$valid."\">&#149;&nbsp;".$affiche."</a> </td>
              </tr>   ";

// debut de la détection de l'emplacement courant

//ici  section  et sous sections si besoin
$pos1=strpos($myline,$S);
if($pos1 !== false)
{
$compteur_section=$compteur_section+1;
if ($url===$lien){print$Section;
$deter_ssection=$deter_ssection+1;
$SS="[ss".$deter_ssection."]";
$valid=$SS;
}
else {
  $deter_ssection=$deter_ssection+1;
$SS="[ss".$deter_ssection."]";
print$SectionLien;
     }

}
// ici affichage  Rubrique
$pos2=strpos($myline,$R);
                    if($pos2 !== false )
                    {
                    if ($url===$lien){print$Rubrique;     }
                    else {print$RubriqueLien;      }
                      print" ";
                    }
//ici sous sections
    $pos3=strpos($myline,$valid);
    if ($pos3 !== false )
               {
                if ($url===$lien)
               {
          print$SousSection;
               }
               else {
               print$SousSectionLien;
                                       print" ";
                     }
               }
// ferme la boucle  principale
   }
// ferme la table et le  fichier
   echo"</table>";
   fclose($myfilee);
?>
