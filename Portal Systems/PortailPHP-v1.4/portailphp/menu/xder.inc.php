<?php
//------------------------------------------------------------------
//Affichage des x derniers message du forum PortailPhp 
//------------------------------------------------------------------ 
//Module version 0.1 créé par Pilou (Philippe ALBRAND - http://phil.albrand.free.fr) le 20-11-2002 

//variable du module menu
$PostNumber = 5 ;

// Module derniers messages du forum
$res_forum = sql_query("SELECT id, titre, lect FROM $BD_Tab_forum WHERE reponse_a_id=0 ORDER BY date_verif DESC LIMIT $PostNumber ",
             $sql_id) ;
?>
<table width='100%'>
  <tr>
    <td width='100%' class='tabTitre' valign='middle' align='center'>
      <strong><?php echo $Lib_Rub_ForumLive ; ?></strong>
    </td>
  </tr>
  <tr>
    <td width='100%' class='tabMenu' valign='top' align='center'>
      Les <?php echo $PostNumber ; ?> derniers messages du forum :<br />
      <?php
        while($row = mysql_fetch_object($res_forum))
        {
            $res_forum2 = sql_query("SELECT * FROM $BD_Tab_forum WHERE reponse_a_id=" . $row->id, $sql_id) ;
            $nb = mysql_num_rows($res_forum2) ;
            $titre_affiche = substr($row->titre, 0, 20) ;
            $titre_affiche = $titre_affiche."... (".$nb.")" ;
            echo "&nbsp;<a href='index.php?" . $sid . "affiche=Forum-read_mess&id=" . $row->id . "'>$titre_affiche</a><br />";
        }
      ?>  
    </td>
  </tr>
</table>