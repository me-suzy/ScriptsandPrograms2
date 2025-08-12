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
?>
<div align='center'>
<?php
  if (!isset($_POST["mon-profil"]))
  {
  ?>
  <form action="index.php?<?php echo $sid ; ?>affiche=Mon-Profil" method="post">
  <table border="0" cellpadding="1" cellspacing="0">
    <tr>
      <td><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;<?php echo $Mod_Membres_Pseudo ; ?> :</td>
      <td><strong><?php echo $_SESSION["Admin_Pseudo"] ; ?></strong></td>
    </tr>
    <tr>
      <td><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;Enregistré le :</td>
      <td><strong><?php echo $_SESSION["Admin_RegDatel"] ; ?></strong></td>
    </tr>

    <tr>
      <td><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;<?php echo $Mod_Membres_Nom ; ?> :</td>
      <td><input type='text' name='nom' value='<?php echo $_SESSION["Admin_Nom"] ; ?>' /></td>
    </tr>
    <tr>
      <td><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;<?php echo $Mod_Membres_Mail ; ?> :</td>
      <td><input type='text' name='email' value='<?php echo $_SESSION["Admin_Mail"] ; ?>' /></td>
    </tr>
    <tr>
      <td><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;<?php echo $Mod_Membres_Web ; ?>:</td>
      <td><input type='text' name='web' value='<?php echo $_SESSION["Admin_Web"] ; ?>' /></td>
    </tr>
    <tr>
      <td valign='top'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />&nbsp;<?php echo $Mod_Membres_Password ; ?> :</td>
      <td><input type='password' name='mdp' value='' /><br /><input type='password' name='mdp2' value='' /> (confirmation)</td>
    </tr>
    <tr>
      <td colspan='2'><input type='submit' name='mon-profil' value='Valider' /></td>
    </tr>
  </table>
  </form>
  <?php
  }
  else
  {
      $nom = AuAddSlashes($_POST["nom"]) ;
      $email = AuAddSlashes($_POST["email"]) ;
      $web = AuAddSlashes($_POST["web"]) ;
      $mdp = AuAddSlashes($_POST["mdp"]) ;
      $mdp2 = AuAddSlashes($_POST["mdp2"]) ;

      if ($mdp == $mdp2)
      {
          if (!empty($mdp))
          {
              $pass = ", US_pwd=md5('$mdp')" ;
          }
          else
          {
              $pass = "" ;
          }

          mysql_query("UPDATE $BD_Tab_user SET US_nom='$nom', US_mail='$email', US_web='$web' $pass " .
                      "WHERE US_nom='" . $_SESSION["Admin_Nom"] . "' " .
                      "AND US_mail='" . $_SESSION["Admin_Mail"] . "' AND US_pseudo='" . $_SESSION["Admin_Pseudo"] . "' " .
                      "AND US_regdate='" . $_SESSION["Admin_RegDatel"]. "' AND US_droit='" . $_SESSION["Admin_Droit"] . "' " .
                      "AND US_img='" . $_SESSION["Admin_Img"] . "' AND US_web='" . $_SESSION["Admin_Web"] . "'"
                     ) ;

          echo $Mod_Membre_MyProfil_OK ;

          $_SESSION["Admin_Nom"] = $nom ;
          $_SESSION["Admin_Mail"] = $email ;
          $_SESSION["Admin_Web"] = $web ;
      }
      else
      {
          echo $Mod_Membre_Mdp_dif ;
      }
  }
  ?>
</div>