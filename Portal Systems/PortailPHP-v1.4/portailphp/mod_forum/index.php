<?php
/*******************************************************************************
 * Copyright (C) 2004 Martineau Emeric
 *
 * Script original LightForum v1.0 © Octobre 2000 - Thaal-Rasha 
 *
 * Rewritten from scratch by Martineau Emeric
 *
 * Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le
 * modifier conformément aux dispositions de la Licence Publique Générale GNU,
 * telle que publiée par la Free Software Foundation ; version 2 de la licence,
 * ou encore (à votre choix) toute version ultérieure.
 *
 * Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE
 * GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou
 * D'ADAPTATION A UN OBJET PARTICULIER. Pour plus de détail, voir la Licence
 * Publique Générale GNU .
 *
 * Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en
 * même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free
 * Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis.
 *
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/
require("./mod_forum/config.inc.php");

$request = mysql_query("SELECT id FROM $BD_Tab_forum WHERE reponse_a_id='0'");
$max_mess = mysql_numrows($request);

// Calcule du nombre de page
$nbre_page = $max_mess / $limit ;
$nombre = ceil($nbre_page) ;
$nombre = $nombre - 1 ;
    
// fixer la limite d'affichage
if (isset($_GET["pos"]) && is_numeric($_GET["pos"]))
{
    $limit1 = $_GET["pos"] * $limit ;
}
else
{
    $limit1 = 0 ;
    $_GET["pos"] = 0 ;
}

    
$req = mysql_query("SELECT * FROM $BD_Tab_forum WHERE reponse_a_id='0' ORDER BY date_verif DESC LIMIT $limit1, $limit");
$res = mysql_numrows($req);

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Forum</strong><br /><br />" ;


?>
<div align="<?php echo $align ; ?>">

  <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>" bgcolor="white">
    <tr>
      <td>

        <table class="tabTitre" border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>">
          <tr>
            <td width="100%">

              <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>" bgcolor="white">
                <tr>
                  <td width="100%" align="center">

                      <table border="0" cellpadding="4" cellspacing="0" width="<?php echo $largeur ; ?>">
                        <tr class='tabTitre'>
                          <td class='tabTitre'>Titre</td>
                          <td class='tabTitre'><?php echo $Mod_Forum_Auteurs ; ?> :</td>
                          <td class='tabTitre' width="140"><?php echo $Mod_Forum_Post ; ?> :</td>
                          <td class='tabTitre' width="140"><?php echo $Mod_Forum_Rep ; ?> :</td>
                        </tr>
                        <?php
                            $i = 0 ;

                            while($i != $res)
                            {
                                $row = mysql_fetch_object($req) ;

                                $id = htmlentities($row->id) ;
                                $nom = htmlentities($row->nom) ;
                                $email = htmlentities($row->email) ;
                                $date = htmlentities($row->date);
                                $heure = htmlentities($row->heure) ;
                                $titre = $row->titre ;

                                // Coupe le titre s'il est trop long
                                $titre = substr($titre, 0, $carac) ;

                                // Regarde le nombre de réponse
                                $rep = mysql_query("SELECT reponse_a_id FROM $BD_Tab_forum WHERE reponse_a_id='$id'");
                                $nb_rep = mysql_numrows($rep);

                                if ($nb_rep > '1')
                                {
                                    $reptexte = $Mod_Forum_Rep1 ;
                                }
                                else if ($nb_rep == '1')
                                {
                                    $reptexte = $Mod_Forum_Rep2 ;
                                }
                                else if ($nb_rep == '0')
                                {
                                    $reptexte = "" ;
                                }

                                if ($bgcolor == '#FFFFFF')
                                {
                                    $bgcolor = "" ;
                                }
                                else
                                {
                                    $bgcolor = "#FFFFFF" ;
                                }

                                // Prend la dernière réponse
                                if ($nb_rep >= 1)
                                {
                                    $q = mysql_query("SELECT date, heure FROM $BD_Tab_forum WHERE reponse_a_id='$id' ORDER BY date_verif ASC LIMIT 0,1") ;
                                    $row1 = mysql_fetch_array($q) ;

                                    $TexteReponse = $row1["date"] . " &agrave; " . $row1["heure"] ;
                                }
                                else
                                {
                                    $TexteReponse = "" ;
                                }

                                ?>
                                <tr class='tabMenu'>
                                  <td bgcolor='<?php echo $bgcolor ; ?>'>
                                    <a href="index.php?<?php echo $sid ; ?>affiche=Forum-read_mess&id=<?php echo $id ; ?>" title="Voir le message de <?php echo $nom . $reptexte ; ?>"><?php echo $titre ; ?></a> (<?php echo $nb_rep ; ?>)
                                  </td>
                                  <td height="24" width="180" bgcolor='<?php echo $bgcolor ; ?>'>
                                    <a href="mailto:<?php echo $email ; ?>?subject=Ton message sur le Forum de <?php echo $mail_url ; ?>">
                                    <img src="mod_forum/images/email.gif" border="0" alt="Envoyer un email à <?php echo $nom ; ?>"><?php echo $nom ; ?></a>
                                  </td>
                                  <td bgcolor='<?php echo $bgcolor ; ?>'>
                                    <?php echo "$date &agrave; $heure" ; ?>
                                  </td>
                                  <td bgcolor='<?php echo $bgcolor ; ?>'>
                                    <?php echo $TexteReponse ; ?>
                                  </td>
                                </tr>
                                <?php

                                $i++ ;
                            }

                        ?>
                      </table>

                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>

      </td>
    </tr>
    <tr>
      <td>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
          <tr>
            <td>
              <a href="index.php?<?php echo $sid ; ?>affiche=Forum-box_aj_me"><?php echo $Mod_Forum_Ajouter ; ?></a>
            </td>
            <td align="right">
            <?php
                // pos -> position en cours
                echo 'Page : ' ;

                for ($i = 0; $i < $nbre_page; $i++)
                {
                    if ($_GET["pos"] == $i)
                    {
                        echo "<strong>" . ($i + 1) . "</strong>, " ;
                    }
                    else
                    {
                        echo "<a href='index.php?".  $sid . "affiche=Forum-Lire&pos=" . $i . "'>" . ($i + 1) ."</a>, " ;
                    }
                }
            ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<?php
include("./mod_forum/box_arc.php"); // Box archives
?>