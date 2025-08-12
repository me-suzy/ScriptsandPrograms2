<?php
include "../config.inc.php";
include "../templates/secure.php";
include "../templates/header.php";
?>
<?php
if ($page == "admin")
{
   if (($login == md5(stripslashes($adminlogin))) && ($password == md5(stripslashes($adminpass))))
   {
   ?>
<span class=mes>
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=optimize><?php echo $lang[102]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=repair><?php echo $lang[103]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=remove><?php echo $lang[105]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=info><?php echo $lang[140]; ?></a><br><br>
<?php     
      if ($action == "repair")
      {
      // Repairing database
      $sql = "REPAIR TABLE $mysql_table";
      $result = mysql_db_query($mysql_base, $sql, $mysql_link);
      echo $err_mes_top.$lang[110].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
      elseif ($action == "optimize")
      {
      // Repairing database
      $sql = "OPTIMIZE TABLE $mysql_table";
      $result = mysql_db_query($mysql_base, $sql, $mysql_link);
      echo $err_mes_top.$lang[111].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
      elseif ($action == "remove")
      {
      if (isset($id)) {
$sql = "SELECT imgname, imgtime FROM $mysql_table WHERE id = '$id'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
if (!empty($i[imgname]))
{
// Delete file
unlink ($int_path."/members/uploads/".$i[imgname]);
}
}
      
$sql = "DELETE FROM $mysql_table WHERE id = '$id'";
mysql_db_query($mysql_base, $sql, $mysql_link) or die(mysql_error());

      echo $err_mes_top.$lang[112]." ".$id." ".$lang[113].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      
      }
      else
      {
      ?>
<h1 style=color:red><?php echo $lang[105]; ?></h1>
<form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=remove" method=post>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
<tr><td colspan=2 class=head><center><?php echo $lang[114]; ?>
<tr><td class=desc><?php echo $lang[115]; ?></td><td><input class=input type=text name=id></td></tr>
<tr><td colspan=2 align=right><input class=input type=submit></td></tr>
</table>
</form>
<?php      
      }
      }
      elseif ($action == "info")
      {
      phpinfo();
      }
      else
      {
      echo $err_mes_top.$lang[127].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
   }
}
include "../templates/footer.php";
?>