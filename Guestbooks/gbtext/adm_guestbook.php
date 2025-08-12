<?

// *** LOADING CONFIG FILE
include "var.inc.php";

// *** CHECKING ADMIN SESSION
session_start();

if ($_GET[logout]=="y"){
   session_destroy();
   header("location:".$_SERVER[PHP_SELF]);
}

if ( ($_POST[fuser]==$adm_user) and ($_POST[fpswd]==$adm_pswd) ){
   $login=$adm_user."|".$adm_pswd;
   session_register("login");
   header("location:".$_SERVER[PHP_SELF]);
}

if ( (empty($_SESSION[login])) or ($_SESSION[login]<>$adm_user."|".$adm_pswd) ){

   drawcss();
   echo "<title>RIDWANK'S GUESTBOOK</title>\n";
   echo "<p align=center><font style=\"font-size:20px\">GUESTBOOK ADMIN LOGIN</font></p>";
   echo "<form action=\"".$_SERVER[PHP_SELF]."\" method=\"post\">";
   echo "<p align=center>";
   echo "Username:<br><input name=\"fuser\">";
   echo "<br>Password:<br><input type=password name=\"fpswd\">";
   echo "<br><input type=submit value=LOGIN>";
   echo "<br>Username : $adm_user<br>Password : $adm_pswd";
   echo "</p>";
   echo "</form>";

} else {

// *** (start) CONTENTS ***


// *** CHECKING FILE EXISTING
if (!file_exists($data)){
  disperr("File &quot;$data&quot; not found!");
  exit;
}

?>
<html>
<head><title>RIDWANK'S GUESTBOOK (ADMIN)</title>

<? drawcss(); ?>

</head>
<body>
<p><font style="font-size:20px"><b>GUESTBOOK (ADMIN)</b></font> - <a href="guestbook.php">HOME</a> | <a href="<?=$_SERVER[PHP_SELF]?>?logout=y">LOGOUT</a></p>
<?

// *** LOADING DATA
   if($file=fopen($data,"r"))
   {
      while(!feof($file))
      {
         $contents_old.=fgets($file,255);
      }
      fclose($file);
   }

   $contents_old=conv_asc2html($contents_old,2);
   $ar_row=explode("|line|",$contents_old);

   if ( ($_POST[btedit]=="Edit") and (!empty($_POST[line_ed])) ){
      $ar_field=explode("|#|",$ar_row[$_POST[line_ed]]);
      $ar_field[1]=$_POST[fname];
      $ar_field[2]=$_POST[femail];
      $ar_field[3]=$_POST[femail_show];
      $ar_field[4]=$_POST[fhomepage];
      $ar_field[5]=$_POST[fcomment];
      $ar_row[$_POST[line_ed]]=implode("|#|",$ar_field);
      $contents_new=implode("|line|",$ar_row);

      if ($file=fopen($data,"w")){
         $contents_new=conv_asc2html($contents_new,1);
         fputs($file,$contents_new);
         fclose($file);
         $contents_old=$contents_new;
         $contents_old=conv_asc2html($contents_old,2);
         $ar_row=explode("|line|",$contents_old);
         //echo "\n<script>\n alert(\"Record has been EDITED!\"); \n</script>\n";
      }

   }

   if ( ($_POST[btdelete]=="Delete") and ($_POST[del_conf]=="y") and (!empty($_POST[line_ed])) ){
      array_splice ($ar_row, $_POST[line_ed], 1);
      $contents_new=implode("|line|",$ar_row);
      if ($file=fopen($data,"w")){
         $contents_new=conv_asc2html($contents_new,1);
         fputs($file,$contents_new);
         fclose($file);
         $contents_old=$contents_new;
         $contents_old=conv_asc2html($contents_old,2);
         $ar_row=explode("|line|",$contents_old);
         //echo "\n<script>\n alert(\"Record has been DELETED!\"); \n</script>\n";
      }
   }

   $recperpage=10;
   $row_first=1;
   $row_last=count($ar_row)-1;
   if (empty($_GET[row_start])){ $_GET[row_start]=$row_first; }
   $row_end=$_GET[row_start]+$recperpage-1;
   if ($row_end>$row_last){ $row_end=$row_last; }

   if ($row_last>$recperpage){
      echo "Page : ";
      for ($p=1; $p<=ceil($row_last / $recperpage); $p++){
         if ($p>1){ $start=((($p-1)*$recperpage)+1);
         } else { $start=1; }
         if ($p==ceil($_GET[row_start]/$recperpage)){ $tdcol="#cccccc"; } else { $tdcol="#ffffff"; }
         echo "<font style=\"background-color:$tdcol\">[<a href=\"$PHP_SELF?row_start=$start\">$p</a>]</font> ";
      }
   }

//echo "first:$row_first|start:$row_start|end:$row_end|last:$row_last";
   echo "&nbsp;<table bgcolor=#FFEEEE border=1 cellspacing=0 cellpadding=5 style=\"border-collapse:collapse\" bordercolor=#999999>\n";
   echo "<tr bgcolor=#CCCCCC><td><b>NO<td><b>Postdate<td><b>Name | Email | Email-(show/hide)<td><b>Homepage</tr>\n";
   for ($r=$_GET[row_start]; $r<=$row_end; $r++){
      $ar_field=explode("|#|",$ar_row[$r]);
      $tanggal=$ar_field[0];
      $name=$ar_field[1];
      $email=$ar_field[2];
      $email_show=$ar_field[3];
      $homepage=$ar_field[4];
      $comment=$ar_field[5];
      if ($_GET[line]==$r){
      echo "<form action=\"".$_SERVER[PHP_SELF]."?row_start=".$_GET[row_start]."\" method=\"post\">\n";
      echo "<tr><td rowspan=2>$r <td><small>$tanggal";
      echo " <input type=\"hidden\" name=\"row_start\" value=\"$row_start\">";
      echo " <input type=\"hidden\" name=\"line_ed\" value=\"".$_GET[line]."\">";
      echo " <input type=\"submit\" name=\"btedit\" value=\"Edit\">";
      echo " <input type=\"reset\">";
      echo " <input type=\"button\" name=\"btback\" value=\"Back\" onclick=window.location=\"$PHP_SELF?row_start=$row_start\">";
      echo " <br><input type=\"checkbox\" name=\"del_conf\" value=\"y\">Are you Sure?";
      echo " <input type=\"submit\" name=\"btdelete\" value=\"Delete\">";
      echo " <td>";
      echo " <input name=\"fname\" value=\"$name\">";
      echo " <input name=\"femail\" value=\"$email\">";
      echo " <input type=\"checkbox\" name=\"femail_show\" value=\"y\"";
      if ($email_show=="y"){ echo " checked"; }
      echo ">";
      echo "<td>";
      echo "<input name=\"fhomepage\" value=\"$homepage\"></tr>\n";
      echo "<tr><td bgcolor=#EEEEEE colspan=3>";
      echo "<textarea name=\"fcomment\" rows=2 cols=100>$comment</textarea></tr>\n";
      echo "</form>\n";
      } else {
      echo "<tr><td rowspan=2>$r <td><small>$tanggal";
      echo " <b>[<a href=\"$PHP_SELF?line=".$r."&row_start=".$_GET[row_start]."\">MODIFY</a>]<td>";
      echo "<small>$name | $email | $email_show<td><small>$homepage</tr>\n";
      echo "<tr><td bgcolor=#EEEEEE colspan=3>$comment</tr>\n";
      }
   }
   echo "</table>\n";

?>
</body>
</html>

<?
} // *** (end) CONTENTS ***
?>
