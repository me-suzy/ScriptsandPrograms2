<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\    D   G  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# DGDating                      Version 1.1b                                 #
# Writed by                     Emin Sadykhov (estof@azdata.net)             #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
if ($page == "remind")
{
   include "templates/header.php";
   if ($username == "" && $email == "") 
      {
      echo $err_mes_top.$lang[78].$err_mes_bottom;
      include "templates/footer.php";
      die;
      }
   elseif ($username != "")
      {
      $sql = "SELECT user, password, email FROM $mysql_table WHERE user = '$username'";
      $result = mysql_db_query($mysql_base, $sql, $mysql_link);
         while ($i = mysql_fetch_array($result)) {
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $from_mail\nX-Mailer: DGDating";
         $body = $body1.$i[user].$body2.$i[password].$body3;
         mail($i[email],$newm,$body,$headers);

         echo $err_mes_top.$lang[79].$err_mes_bottom;
         include "templates/footer.php";
         die;
         }
         echo $err_mes_top.$lang[46].$err_mes_bottom;
         include "templates/footer.php";
         die;
       } 
   elseif ($email != "")
      {
      $sql = "SELECT user, password, email FROM $mysql_table WHERE email = '$email'";
      $result = mysql_db_query($mysql_base, $sql, $mysql_link);
         while ($i = mysql_fetch_array($result)) {
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $from_mail\nX-Mailer: DGDating";
         $body = $body1.$i[user].$body2.$i[password].$body3;
         mail($i[email],$newm,$body,$headers);

         echo $err_mes_top.$lang[79].$err_mes_bottom;
         include "templates/footer.php";
         die;
         }
         echo $err_mes_top.$lang[80].$err_mes_bottom;
         include "templates/footer.php";
         die;
       } 
}
else 
{
include "templates/header.php";
echo "<form action=remind.php?l=".$l."&page=remind method=post><center><span class=head>".$lang[77]."</span></center><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black><tr><td colspan=2 align=center><span class=dat>".$lang[81]."</span></td></tr><tr class=desc align=center><td width=100>".$lang[9]."</td><td><input class=input type=text name=username></td></tr><tr class=desc align=center><td width=100>".$lang[82]."</td><td><input class=input type=text name=email></td></tr><tr><td colspan=2 align=right><input class=input type=submit value=\"".$lang[83]."\"></td></tr></table><br><br>";
include "templates/footer.php";
}
?>