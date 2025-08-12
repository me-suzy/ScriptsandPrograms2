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

echo("<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Forum</strong><br /><br />");

if (isset($_GET["id"]) && is_numeric($_GET["id"]))
{
    $id = $_GET["id"] ;
}
else
{
    $id = 0 ;
}

if (isset($_GET["titre"]))
{
    $titre = "Re : " . StripSlashes($_GET["titre"]) ;
}
else
{
    $titre = "" ;
}

?>
<!-- BOX -->
<script language="JavaScript" src="mod_forum/controle.js"></script>
<div align="center">
  <form name="Formulaire" method="post">
    <input type="hidden" name="reponse_a_id" value="<?php echo $id ; ?>">
    <input type="hidden" name="form" value="ajout">

    <table border="0" cellpadding="1" cellspacing="0">
      <tr>
        <td>

          <table border="0" cellpadding="6" cellspacing="0">
            <tr>
              <td class="tabTitre" align="center">
                <?php echo $Mod_Forum_Ajouter ; ?>
              </td>
            </tr>
            <tr>
              <td class="tabMenu">

                <table  border="0" cellpadding="1" cellspacing="0">
                  <tr>
                    <td>Nom :</td>
                    <td><input type="text" name="nom" size="38" style="background-color: <?php echo $bgcolor_box ; ?>" value="your_name"></td>
                  </tr>
                  <tr>
                    <td>E-mail :</td>
                    <td><input type="text" name="email" size="38" style="background-color: <?php echo $bgcolor_box ; ?>" value="your_email@your_domaine.com"></td>
                  </tr>
                  <tr>
                    <td>Titre :</td>
                    <td><input type="text" name="titre" size="38" style="background-color: <?php echo $bgcolor_box ; ?>" value="<?php echo $titre ; ?>"></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <textarea rows="10" name="message" cols="43" wrap="virtual" style="background-color: <?php echo $bgcolor_box ; ?>">le message ici ...</textarea>
                      <div align="center">
                        <br /><br />
                        <input type="button" value="Ajouter" name="envoi" onClick="Verif()">
                      </div>
                    </td>
                  </tr>
                </table>

              </td>
            </tr>
          </table>

        </td>
      </tr>
    </table>
  </form>
</div>
<!-- FIN BOX -->