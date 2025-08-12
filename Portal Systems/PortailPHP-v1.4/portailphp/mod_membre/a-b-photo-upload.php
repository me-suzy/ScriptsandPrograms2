<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric claced@m6net.fr
 * http://www.yoopla.net/portailphp/
 *
 * Modifié par Martineau Emeric Copyright (C) 2004
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
 * Portail PHP
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Upload_Photo</strong><br /><br />" ;

if (is_uploaded_file($photo) && is_uploaded_file($vignette) && $action=="Sauvegarder") 
{
    $date = date("ymdhis") ;
    $code = $date . ".jpg" ;

    if (move_uploaded_file($photo, "$chemin/mod_photos/photos/$code") &&
        move_uploaded_file($vignette, "$chemin/mod_photos/vignettes/$code"))
     {
         //$Path_Photo = "$chemin/mod_photos/photos/".$code.".jpg";
         //$Path_Vignette = "$chemin/mod_photos/vignettes/".$code.".jpg";
         //chmod($Path_Photo,0755) && chmod($Path_Vignette,0755)
         if (1)
         {
             $sql = "INSERT INTO $BD_Tab_photos " ;
             $sql .= "(PO_code,PO_date,PO_titre,PO_text) " ;
             $sql .= "VALUES ('$code','$datephoto','$titre','$text')" ;

             if ($Photos = mysql_db_query($BD_name,$sql))
             {
                 echo "photo sauvegardée" ;
             }
             else
             {
                 echo $Err_Insert ;
             }
         }
         else
         {
             echo $Err_Upload_Chmod ;
         }
     }
     else
     {
         echo $Err_Upload_Move ;
     }
} 
else
{
    ?>
    <form name='upload' method="post" action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=Photo-Upload' enctype='multipart/form-data'>
      <table border='0' cellspacing='5' cellpadding='0'>
        <tr>
          <td width='400' align='left' height='20'><?php echo $Mod_Membres_Photos_Form_Photo ; ?><br />
            <input type='file' name='photo' value='<?php echo $photo ; ?>'>
          </td>
        </tr>
        <tr>
          <td width='400' align='left' height='20'><?php echo $Mod_Membres_Photos_Form_Vign ; ?><br />
          <input type='file' name='vignette'>
          </td>
        </tr>
        <tr>
            <td  align='left' height='20'><?php echo $Mod_Membres_Photos_Form_Titre ; ?><br />
            <input type='text' name='titre' size='50' value='<?php echo $titre ; ?>'></td>
        </tr>
        <tr>
          <td  align='left' height='20'><?php echo $Mod_Membres_Photos_Form_Date ; ?><br />
          <input type='text' name='datephoto' size='10' value='<?php echo $datephoto ; ?>'></td>
        </tr>
        <tr>
          <td  align='left' height='20'><?php echo $Mod_Membres_Photos_Form_Texte ; ?><br />
          <input type='text' name='text' size='50' value='<?php echo $text ; ?>'></td>
        </tr>
        <tr>
          <td width='500' align='left' colspan='2'>
            <p>
              <input type='submit' value='Sauvegarder' name='action'>&nbsp<input type='reset' value='Rétablir' name='B2'>
          </td>
       <tr>
     </table>
     <?php
}
?> 