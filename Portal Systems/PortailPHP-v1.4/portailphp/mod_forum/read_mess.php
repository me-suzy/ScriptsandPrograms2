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

if (isset($_GET["pos"]) && is_numeric($_GET["pos"]))
{
    $pos = $_GET["pos"] ;
    $limit1 = $pos * $limit ;
}
else
{
    $pos = 0 ;
    $limit-- ;
    $limit1 = 0 ;
}

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Forum</strong><br /><br />" ;

if (isset($_GET["id"]) && is_numeric($_GET["id"]))
{
    $id = $_GET["id"] ;
}
else
{
    $id = -1 ;
}

$request = mysql_query("SELECT * FROM $BD_Tab_forum WHERE id='$id'");
$row = mysql_fetch_object($request) ;

$name = $row->nom ;

$titre = $row->titre;

$texte = $row->texte ;
$texte = str_replace("[return]","<br />",$texte);  // on retransforme les [return] en <br /> pour garder les retour chariot
$texte = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", "<a href=\"\\1://\\2\\3\" target=\"_blank\">\\1://\\2\\3</a>",$texte);

if (!(isset($_GET["pos"]) && is_numeric($_GET["pos"])))
{
?>
<div align="<?php echo $align ; ?>">
  <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>">
    <tr>
      <td>

        <table class="tabTitre" border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>">
          <tr>
            <td width="100%">

              <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>" bgcolor="white">
                <tr>
                  <td width="100%">

                    <table border="0" cellpadding="2" cellspacing="0" width="<?php echo $largeur ; ?>">
                      <tr class="tabTitre">
                        <td class="tabTitre">Message original de <?php echo $name ; ?></td>
                        <td class="tabTitre">D&eacute;j&agrave; lu <?php echo $row->lect ; ?> fois avant vous&nbsp;</td>
                      </tr>
                      <tr class="tabMenu">
                        <td colspan="2">
                          Email&nbsp;&nbsp;&nbsp;&nbsp;: <a href="mailto:<?php echo $row->email ; ?>"><?php echo $row->email ; ?></a><br />
                          Post&eacute; le&nbsp;: <?php echo $row->date . " &agrave; " . $row->heure ; ?><br />
                          Titre&nbsp;&nbsp;&nbsp;&nbsp;:
                          <strong><?php echo $titre ; ?></strong><br /><br />
                          <blockquote><?php echo $texte ; ?></blockquote>
                          <?php
                          if ($_SESSION["Admin"])
                          {
                              echo "<a href='index.php?affiche=Del-Msg-Forum&id=" . $row->id . "'>$Mod_News_Supprimer</a>" ;
                          }
                          ?>
                        </td>
                      </tr>
                    </table>

                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
  <br />
</div>
<?php
}

// Regade le nombre de réponse
$req = mysql_query("SELECT * FROM $BD_Tab_forum WHERE reponse_a_id='$id'");
$max_mess = mysql_numrows($req);
$nbre_page = $max_mess / $limit ;

$req = mysql_query("SELECT * FROM $BD_Tab_forum WHERE reponse_a_id='$id' ORDER BY date_verif LIMIT $limit1, $limit");
$res = mysql_numrows($req);

$i = 0 ;

while($i != $res)
{
    $row = mysql_fetch_object($req) ;
    $nom = $row->nom ;
    $titre = $row->titre ;

    $texte = $texte ;
    $texte = str_replace("[return]","<br />",$texte);
    $texte = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", "<a href=\"\\1://\\2\\3\" target=\"_blank\">\\1://\\2\\3</a>",$texte);

    ?>
    <div align="<?php echo $align ; ?>">
      <table border="0" cellpadding="2" cellspacing="0" width="<?php echo $largeur ; ?>">
        <tr>
          <td>

            <table class="tabTitre" border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>">
              <tr>
                <td width="100%">

                  <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $largeur ; ?>" bgcolor="white">
                    <tr>
                      <td width="100%">

                        <table border="0" cellpadding="2" cellspacing="0" width="<?php echo $largeur ; ?>">
                          <tr class="tabTitre">
                            <td class="tabTitre">R&eacute;ponse de <?php echo $nom ; ?></td>
                          </tr>
                          <tr class="tabMenu">
                            <td>
                              Email&nbsp;&nbsp;&nbsp;&nbsp;: <a href="mailto:<?php echo $row->email ; ?>"><?php echo $row->email ; ?></a><br />
                              Post&eacute; le&nbsp;: <?php echo $row->date . " &agrave; " . $row->heure ; ?><br />
                              Titre&nbsp;&nbsp;&nbsp;&nbsp;: <strong><?php echo $titre ; ?></strong><br /><br /><blockquote><?php echo $texte ; ?></blockquote>
                              <?php
                              if ($_SESSION["Admin"])
                              {
                                  echo "<a href='index.php?affiche=Del-Msg-Forum&id=" . $row->id . "'>$Mod_News_Supprimer</a>" ;
                              }
                              ?>
                            </td>
                          </tr>
                        </table>

                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>
      <br />
    </div>
    <?php
    $i++;
}

// pos -> position en cours
echo 'Page : ' ;

for ($i = 0; $i < $nbre_page; $i++)
{
    if ($pos == $i)
    {
        echo "<strong>" . ($i + 1) . "</strong>, " ;
    }
    else
    {
        echo "<a href='index.php?" . $sid . "affiche=Forum-read_mess&id=" . $id . "&pos=" . $i . "'>" . ($i + 1) ."</a>, " ;
    }
}

?>
<br /><br /><a href="index.php?<?php echo $sid ; ?>affiche=Forum-box_aj_me&id=<?php echo $id ; ?>&titre=<?php echo urlencode($titre) ; ?>">Ajouter une r&eacute;ponse</a>
<?php
mysql_query("UPDATE $BD_Tab_forum SET lect=lect+1 WHERE id='$id'");
?>