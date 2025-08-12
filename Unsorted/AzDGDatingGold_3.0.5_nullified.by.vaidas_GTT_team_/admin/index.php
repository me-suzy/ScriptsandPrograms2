<?php
//session_start();unset($s);
include "../config.inc.php";
include "../templates/secure.admin.php";
//   if (!isset($_SESSION["s"])) {include "../templates/header.php";echo $err_mes_top."Session has been expired<br>You must login again!".$suc_mes_bottom;include "../templates/footer.php";session_destroy();unset($s);die;}
include "../templates/header.php";
?>
<?php
if ($page == "admin")
{
   if (($login == md5(stripslashes($adminlogin))) && ($password == md5(stripslashes($adminpass))))
   {
   ?>
<span class=mes><a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=optimize><?php echo $lang[102]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=repair><?php echo $lang[103]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=clear><?php echo $lang[104]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=remove><?php echo $lang[105]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=edit><?php echo $lang[106]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=sendmail><?php echo $lang[107]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=addfaq>Add</a>/
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=editfaq>Edit</a>/
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=delfaq>Del</a> FAQ|
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=adminlog><?php echo $lang[132]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=info><?php echo $lang[140]; ?></a> |
<a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=quit><?php echo $lang[109]; ?></a><br><br>
<?php     
      if ($action == "repair")
      {
      // Repairing database
      $sql = "REPAIR TABLE ".$mysql_table;
      mysql_query($sql);
      $sql = "REPAIR TABLE ".$mysql_messages;
      mysql_query($sql);
      $sql = "REPAIR TABLE ".$mysql_admin;
      mysql_query($sql);
      $sql = "REPAIR TABLE ".$mysql_hits;
      mysql_query($sql);
      $sql = "REPAIR TABLE ".$mysql_faq;
      mysql_query($sql);
      echo $err_mes_top.$lang[110].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
      elseif ($action == "quit")
      {
      echo $err_mes_top."You have been exited from admin menu<br>You can close window!<br><br>Thanks for using our script!".$suc_mes_bottom;
      include "../templates/footer.php";
      session_destroy();
      unset($s); 
      die;
      }
      elseif ($action == "optimize")
      {
      // Repairing database
      $sql = "OPTIMIZE TABLE ".$mysql_table;
      mysql_query($sql);
      $sql = "OPTIMIZE TABLE ".$mysql_messages;
      mysql_query($sql);
      $sql = "OPTIMIZE TABLE ".$mysql_admin;
      mysql_query($sql);
      $sql = "OPTIMIZE TABLE ".$mysql_hits;
      mysql_query($sql);
      $sql = "OPTIMIZE TABLE ".$mysql_faq;
      mysql_query($sql);
      echo $err_mes_top.$lang[111].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
      elseif ($action == "remove")
      {
      if (isset($id)) {
$sql = "SELECT imgname, imgtime FROM $mysql_table WHERE id = '$id'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
if (!empty($i[imgname]))
{
// Delete file
unlink ($int_path."/members/uploads/".$i[imgname]);
}
}
      
$sql = "DELETE FROM $mysql_table WHERE id = '$id'";
mysql_query($sql) or die(mysql_error());
$sql = "DELETE FROM $mysql_hits WHERE id = '$id'";
mysql_query($sql);
$sql = "DELETE FROM $mysql_messages WHERE fromid = '$id' OR toid = '$id'";
mysql_query($sql);

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
      elseif ($action == "clear")
      {
      $sql = "DELETE FROM $mysql_hits";
      mysql_query($sql);
      echo $err_mes_top.$lang[116].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      
      }
      elseif ($action == "edit")
      {
      if ($search == "search")
      {
         if (isset($edit)) 
         {
            if ($action2 == "update")
            {
            $sql = "UPDATE $mysql_table SET gender='$gender', email='$email', country='$country', purposes='$purposes', city='$city', hobby='$hobby', Description='$Description', height='$height', weight='$weight', age='$age' WHERE id = '$edit'";
             mysql_query($sql);
      echo $err_mes_top.$lang[35].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
            }
            else
            {
         $sql = "SELECT * FROM $mysql_table WHERE id = '$edit'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {

?>
<form action=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=edit&search=search&edit=<?php echo $i[id]; ?>&action2=update method=post enctype="post">
<center><span class=head><?php echo $lang[32];?></span></center><Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black><tr class=desc><td width=100><?php echo $lang[11]; ?></td><td><select class=select name=gender>
<option value="<?php echo $i[gender]; ?>"><?php echo $langgender[$i[gender]]; ?>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td></tr>
<tr class=desc><td width=100><?php echo $lang[14]; ?></td><td><select class=select name=purposes>
<option value="<?php echo $i[purposes]; ?>"><?php echo $langpurposes[$i[purposes]]; ?>
<?php
$p = 1;
while ($langpurposes[$p]) 
{
echo "<OPTION value=".$p.">".$langpurposes[$p];
	$p++;
}
?>

</select></td></tr>
<tr class=desc><td width=100><?php echo $lang[12]; ?></td><td><input class=input type=text name=email maxlength="70" value="<?php echo $i[email]; ?>"></td></tr>
<tr class=desc><td width=100><span class=mes><?php echo $lang[13]; ?></td><td><select class=select name="country">
<option><?php echo $i[country]; ?>
<?php 
include "../templates/countries.php";
?>
</td></tr>
<tr class=desc><td><span class=mes><?php echo $lang[15]; ?></td><td><input class=input type=text name=city maxlength="99" value="<?php echo $i[city]; ?>"></td></tr>
<tr class=desc><td><?php echo $lang[16]; ?></td><td><textarea class=textarea cols=20 rows=4 name=hobby><?php echo $i[hobby]; ?></textarea></td></tr>
<tr class=desc><td><?php echo $lang[17]; ?></td><td><textarea class=textarea cols=20 rows=4 name=Description><?php echo $i[Description]; ?></textarea></td></tr>
<tr class=desc><td><?php echo $lang[18]; ?></td><td><input class=input type=text name=height maxlength="20" value="<?php echo $i[height]; ?>"></td></tr>
<tr class=desc><td><?php echo $lang[19]; ?></td><td><input class=input type=text name=weight maxlength="20" value="<?php echo $i[weight]; ?>"></td></tr>
<tr class=desc><td><?php echo $lang[20]; ?></td><td><input class=input type=text name=age maxlength="2" value="<?php echo $i[age]; ?>"></td></tr>
<tr><td align=right colspan=2><input class=input type=submit value="<?php echo $lang[32]; ?>"></td></tr>
</table>
</form>
<?php
}
}
         }
         elseif (isset($delete))
         {
         $sql = "SELECT imgname, imgtime FROM $mysql_table WHERE id = '$delete'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
if (!empty($i[imgname]))
{
// Delete file
unlink ($int_path."/members/uploads/".$i[imgname]);
}
}

         $sql = "DELETE FROM $mysql_table WHERE id = '$delete'";
mysql_query($sql);
         $sql = "DELETE FROM $mysql_hits WHERE id = '$delete'";
mysql_query($sql);
         $sql = "DELETE FROM $mysql_messages WHERE fromid = '$delete' OR toid = '$delete'";
mysql_query($sql);

      echo $err_mes_top.$lang[112]." ".$delete." ".$lang[117].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
         }
         else
         {
         

if (!$t_step) {$t_step = 0;}
if (!$from) {$from = 0;}

//$t_step=10;
if ($photos == "on")
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%' AND pic != '' order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%' AND pic != ''";
}
else
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%' order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'";
}
$result = mysql_query($sql)  or die(mysql_error());
if (mysql_fetch_array($result) == 0)
{
      echo $err_mes_top.$lang[26].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
}
else
{
$result = mysql_query($sql)  or die(mysql_error());
$tquery = mysql_query($tsql)  or die(mysql_error());
$trows = mysql_fetch_array($tquery);
$count = $trows[total];
echo "<center><span class=head>".$lang[27]."</span></center><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black><tr class=desc align=center><td width=100>".$lang[9]."</td><td width=100>".$lang[14]."</td><td width=100>".$lang[13]."</td><td width=50>".$lang[20]."</td><td width=50>".$lang[21]."</td><td>".$lang[118]."</td><td>".$lang[119]."</td><td>".$lang[120]."</td></tr>";
$colorchange = 0;
while ($i = mysql_fetch_array($result)) {
if ($i[pic] == "")
{
$picav = $lang[84];
}
else
{
$picav = "<a href=../view.php?l=".$l."&id=".$i[id]." target=_blank>".$lang[85]."</a>";
}
if ($colorchange == 0)
{
$data=date("d/m/Y", $i[imgtime] + $date_diff*60*60);
echo "<tr bgcolor=".$color1." align=center><td><a href=../view.php?l=".$l."&id=".$i[id]." target=_blank>".$i[user]."</a></td><td>".$langgender[$i[gender]]." ".$langpurposes[$i[purposes]]."</td><td>".$i[country]."</td><td>".$i[age]."</td><td>".$picav."</td><td>".$data."</td><td><a href=index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&edit=".$i[id].">".$lang[119]."</a></td><td><a href=index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&delete=".$i[id].">".$lang[120]."</a></td></tr>";
$colorchange = 1;
}
else
{
echo "<tr bgcolor=".$color2." align=center><td><a href=../view.php?l=".$l."&id=".$i[id]." target=_blank>".$i[user]."</a></td><td>".$langgender[$i[gender]]." ".$langpurposes[$i[purposes]]."</td><td>".$i[country]."</td><td>".$i[age]."</td><td>".$picav."</td><td>".$data."</td><td><a href=index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&edit=".$i[id].">".$lang[119]."</a></td><td><a href=index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&delete=".$i[id].">".$lang[120]."</a></td></tr>";
$colorchange = 0;
}
}

// Page generating
////////////////////////////////
if ($t_step < $count)
{
echo "<tr bgcolor=".$color1." align=center><td colspan=8>".$lang[86]." : ";

$mesdisp = $t_step;

	$max = $count + 1;
	$from = ($from > $count) ? $count : $from;
	$from = ( floor( $from / $mesdisp ) ) * $mesdisp;

		if (($cpage % 2) == 1)	//1,3,5,...
			$pc = (int)(($cpage - 1) / 2);
		else
			$pc = (int)($cpage / 2);	

		if ($from > $mesdisp * $pc)	
			$str.= "<a href=\"index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&from=0&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">1</a> ";

		if ($from > $mesdisp * ($pc + 1))
			$str.= "<B> . . . </B>";

		for ($nCont=$pc; $nCont >= 1; $nCont--)	// 1 & 2 before
			if ($from >= $mesdisp * $nCont) {
				$tmpStart = $from - $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		$tmpPage = $from / $mesdisp + 1;	// page to show
		$str.= " [<B>$tmpPage</B>] ";

		$tmpMaxPages = (int)(($max - 1) / $mesdisp) * $mesdisp;	// 1 & 2 after
		for ($nCont=1; $nCont <= $pc; $nCont++)
			if ($from + $mesdisp * $nCont <= $tmpMaxPages) {
				$tmpStart = $from + $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		if ($from + $mesdisp * ($pc + 1) < $tmpMaxPages)	
			$str.= "<B> . . . </B>";

		if ($from + $mesdisp * $pc < $tmpMaxPages)	{ 
			$tmpPage = $tmpMaxPages / $mesdisp + 1;
			$str.= "<a href=\"index.php?l=".$l."&page=admin&login=".$login."&password=".$password."&action=edit&search=search&from=".$tmpMaxPages."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
		}
echo $str;
echo "</td></tr></table>";
}
else
{
echo "</table><br>";
}
// end page generating
}
      
      }
      }
      else
      {
      ?>
      
<form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=edit&search=search" method="post">
<center><span class=head><?php echo $lang[3]; ?></span>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?>>
<tr><td width=200><span class=mes><?php echo $lang[13]; ?></td>
<td>
<select class=select name="country">
<option value="">
<?php 
include "../templates/countries.php";
?>
</td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[11]; ?></td>
       <td><select class=select name=gender>
<option>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[14]; ?></td>
       <td><select class=select name=purposes>
<option>
<?php
$p = 1;
while ($langpurposes[$p]) 
{
echo "<OPTION value=".$p.">".$langpurposes[$p];
	$p++;
}
?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[87]; ?></td>
       <td><select class=select name=t_step>
<option selected value=10>10 <?php echo $lang[88]; ?>
<OPTION value=20>20 <?php echo $lang[88]; ?>
<OPTION value=30>30 <?php echo $lang[88]; ?>
<OPTION value=40>40 <?php echo $lang[88]; ?>
<OPTION value=50>50 <?php echo $lang[88]; ?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[51]; ?></td>
       <td><input type="checkbox" name="photos"></td>
</tr>
<tr>
       <td colspan=2><input class=button type="submit" Value="<?php echo $lang[3]; ?>"> <input class=button type="reset"></td>
</tr>
</table>
</form>
<?php
      }
      }
      elseif ($action == "sendmail")
      {
           $sm = ini_get("safe_mode");
           if ($sm == "") // if safe_mode = off
           {
           set_time_limit(10000);
           }
      if ($send == "unread")
      {
      $sql = "SELECT id, email, user, password FROM $mysql_table";
      $result = mysql_query($sql) or die(mysql_error());
      while ($i = mysql_fetch_array($result)) 
            {
      $msql = "SELECT * FROM $mysql_messages WHERE toid = '$i[id]' and readed = '0' and confirm != '9'";
      $mresult = mysql_query($msql) or die(mysql_error());
              $mcount = 0;
                   
           
      while ($m = mysql_fetch_array($mresult)) 
            {
//            echo $m[touser];
              $mcount++;
            }
            if ($mcount > 0)
            {
     
     ////////////////////////////////
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $from_mail\nX-Mailer: AzDGDatingGold v3.0.5";
$msub = "New messages from ".$sname;
$mmes = "Çäðàñòâóéòå ".$i[user]."<br><br>Ó Âàñ <b>".$mcount."</b> ñîîáùåíèé íà íàøåì ñàéòå ".$sname."<br><br>Ïîìíèòå ÷òî,<br>Âàø ëîãèí : ".$i[user]."<br>Âàø ïàðîëü : ".$i[password]."<br><br><br>Çàéäèòå íà ýòîò ëèíê è ïðîâåðüòå : <a href=".$url."/login.php?l=russian target=_blank>".$url."/login.php?l=russian</a><br><br>---------------------------------<br>Ñ Óâàæåíèåì,<br>Êîìàíäà AzeriLove";
$mmes .= "<br>________________________________________________________________<br><br>";
$mmes .= "Hi ".$i[user]."<br><br>You have <b>".$mcount."</b> messages for you on our ".$sname."<br><br>Remember that,<br>Your Login : ".$i[user]."<br>Your password : ".$i[password]."<br><br><br>Url for checking messages : <a href=".$url."/login.php?l=english target=_blank>".$url."/login.php?l=english</a><br><br>---------------------------------<br>With best regards,<br>AzeriLove Team";
         @mail($i[email],$msub,$mmes,$headers);
                     $count++;
            }
            }
         if ($notify == "on")
         {
          $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $adminmail\nX-Mailer: AzDGDatingGold v3.0.5";
         $msub=$lang[122]." ".$count." ".$lang[123];
         mail($adminmail,$msub,$msub,$headers);
         }   
         echo $err_mes_top.$lang[122]." ".$count." ".$lang[123].$err_mes_bottom;

     ////////////////////////////////
           }

      elseif ($send == "send")
      {
      $sql = "SELECT user, email, gender, country FROM $mysql_table";
      if (($gender != "") && ($country != ""))
         {
         $sql .= " where gender='$gender' and country='$country'";
         }
      else
      {   
         if ($gender != "")
            {
            $sql .= " where gender='$gender'";
            }
         if ($country != "")
            {
            $sql .= " where country='$country'";
            }
      }      
      $result = mysql_query($sql) or die(mysql_error());
      while ($i = mysql_fetch_array($result)) 
            {
            
            // There including mail programm
          $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $adminmail\nX-Mailer: AzDGDatingGold v3.0.5";
         mail($i[email],$sub,$mes,$headers);
        // echo "user ".$i[user]."; email ".$i[email]."; gender ".$i[gender].";<br>";
                     $count++;
            }
         if ($notify == "on")
         {
          $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $adminmail\nX-Mailer: AzDGDatingGold v3.0.5";
         $sub=$lang[122]." ".$count." ".$lang[123];
         mail($adminmail,$sub,$mes,$headers);
         }   
         echo $err_mes_top.$lang[122]." ".$count." ".$lang[123].$err_mes_bottom;
      }
      else
      {
?>      
      <h1 style=color:red><?php echo $lang[121]; ?></h1>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=sendmail&send=send" method=post>
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td colspan=2 class=head><center><?php echo $lang[107]; ?>
      <tr><td class=desc><?php echo $lang[124]; ?></td><td><input class=input type=text name=sub></td></tr>
      <tr><td class=desc><?php echo $lang[125]; ?></td><td><textarea name="mes" class=textarea rows="5" cols="10"></textarea></td></tr>
      <tr><td class=desc><?php echo $lang[11]; ?></td><td><select class=select name=gender>
<option value=""><?php echo $lang[95]; ?>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td></tr>
      <tr><td class=desc><?php echo $lang[13]; ?></td><td>
<select class=select name="country">
<option value=""><?php echo $lang[95]; ?>
<?php 
include "../templates/countries.php";
?>
</td></tr>
      <tr><td colspan=2><input type="checkbox" name="notify" checked> <?php echo $lang[126]; ?></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form><br><br>
      
      <h3 style=color:red><?php echo $lang[181]; ?></h1>
<cite><?php echo $lang[182]; ?></cite>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=sendmail&send=unread" method=post>
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td colspan=2><input type="checkbox" name="notify" checked> <?php echo $lang[126]; ?></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form>
      
<?php      
      }      
      }
      elseif ($action == "addfaq")
      {
      if ($action2 == "do")
      {
       if (empty($question) || trim($question) == "" || empty($answer) || trim($answer) == "") 
         {
         echo $err_mes_top."You must enter question and answer!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
         }
      $sql = "INSERT INTO $mysql_faq (fid, question, answer) VALUES ('', '$question', '$answer')";
      mysql_query($sql);
      echo $err_mes_top."You question:<br>".$question."<br>and your answer:<br>".$answer."<br>has been added to database!".$suc_mes_bottom;

      }
      ?>
      <h3 style=color:red>Add FAQ</h3><sup>You can use any html tags - please be very carefull</sup>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=addfaq&action2=do" method=post>
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td class=desc>Enter question</td><td><input class=input type=text name=question></td></tr>
      <tr><td class=desc>Enter answer</td><td><textarea name="answer" class=textarea rows="5" cols="10"></textarea></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form>

      <?php
      }
      elseif ($action == "editfaq")
      {
      if ($action2 == "doedit")
      {
         if (!is_numeric($fid)) 
         {
         echo $err_mes_top."FAQ id must be numeric!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
         }
      $sql = "SELECT count(*) as total FROM $mysql_faq WHERE fid='".$fid."'";
      $tquery = mysql_query($sql) or die(mysql_error());
      $trows = mysql_fetch_array($tquery);
      $count = $trows[total];
      if ($count == 0)
      {
         echo $err_mes_top."No such FAQ id available in database!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
      
      }
      $sql1 = "UPDATE $mysql_faq SET question='".$question."', answer='".$answer."' WHERE fid='".$fid."'";
      mysql_query($sql1) or die(mysql_error());
      echo $err_mes_top."FAQ has been changed<br>Question:<br>".$question."<br>Answer:<br>".$answer.$err_mes_bottom;
      include "../templates/footer.php";
      die;

      }
      else if ($action2 == "do")
      {
         if (!is_numeric($fid)) 
         {
         echo $err_mes_top."FAQ id must be numeric!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
         }
      $sql = "SELECT count(*) as total FROM $mysql_faq WHERE fid='".$fid."'";
      $tquery = mysql_query($sql) or die(mysql_error());
      $trows = mysql_fetch_array($tquery);
      $count = $trows[total];
      if ($count == 0)
      {
         echo $err_mes_top."No such FAQ id available in database!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
      
      }
      $sql1 = "SELECT * FROM $mysql_faq WHERE fid='".$fid."'";
      $result1 = mysql_query($sql1) or die(mysql_error());
      while ($i = mysql_fetch_array($result1)) {
?>
      <h3 style=color:red>Edit FAQ</h3><sup>You can use any html tags - please be very carefull</sup>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=editfaq&action2=doedit" method=post>
      <input class=input type=hidden name=fid value="<?=$fid?>">     
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td class=desc>Question</td><td><input class=input type=text name=question value="<?=$i[question]?>"></td></tr>
      <tr><td class=desc>Enter answer</td><td><textarea name="answer" class=textarea rows="5" cols="10"><?=$i[answer]?></textarea></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form>

<?php
      }                     
      }
      else
      {
      ?>
      <h3 style=color:red>Edit FAQ</h3>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=editfaq&action2=do" method=post>
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td class=desc>Enter question ID</td><td><input class=input type=text name=fid></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form>
      
      <?php
      }
      }
      elseif ($action == "delfaq")
      {
      if ($action2 == "do")
      {
       if (!is_numeric($fid)) 
         {
         echo $err_mes_top."FAQ id must be numeric!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
         }
      $sql = "SELECT count(*) as total FROM $mysql_faq WHERE fid='".$fid."'";
      $tquery = mysql_query($sql) or die(mysql_error());
      $trows = mysql_fetch_array($tquery);
      $count = $trows[total];
      if ($count == 0)
      {
         echo $err_mes_top."No such FAQ id available in database!".$err_mes_bottom;
         include "../templates/footer.php";
         die;
      
      }
      $sql1 = "SELECT * FROM $mysql_faq WHERE fid='".$fid."'";
      $result1 = mysql_query($sql1) or die(mysql_error());
      $sql = "DELETE FROM $mysql_faq WHERE fid='".$fid."'";
      mysql_query($sql) or die(mysql_error());
      while ($i = mysql_fetch_array($result1)) {
      echo $err_mes_top."You question:<br>".$i[question]."<br>and your answer:<br>".$i[answer]."<br>has been deleted from database!".$suc_mes_bottom;
      }                     
      }
      ?>
      <h3 style=color:red>Delete FAQ</h3>
      <form action="index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=delfaq&action2=do" method=post>
      <Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
      <tr><td class=desc>Enter question ID</td><td><input class=input type=text name=fid></td></tr>
      <tr><td colspan=2 align=right><input class=input type=submit></td></tr>
      </table>
      </form>
      <?php
      }
      elseif ($action == "adminlog")
      {
      if ($action2 == "clear_admin_log")
      {
      $sql = "DELETE FROM ".$mysql_admin;
      mysql_query($sql);

      echo $err_mes_top.$lang[136].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
   
      }
      else
      {
      $tsql = "SELECT count(*) as total FROM ".$mysql_admin;
      $tquery = mysql_query($tsql);
      $trows = mysql_fetch_array($tquery);
      $count = $trows[total];
      if ($count > 0)
      {
      ?>
<center><a href=index.php?l=<?php echo $l; ?>&page=admin&login=<?php echo $login; ?>&password=<?php echo $password; ?>&action=adminlog&action2=clear_admin_log><?php echo $lang[137]; ?></a></center>
<?php

function int2ip($i) {
   $d[0]=(int)($i/256/256/256);
   $d[1]=(int)(($i-$d[0]*256*256*256)/256/256);
   $d[2]=(int)(($i-$d[0]*256*256*256-$d[1]*256*256)/256);
   $d[3]=$i-$d[0]*256*256*256-$d[1]*256*256-$d[2]*256;
   return "$d[0].$d[1].$d[2].$d[3]";
}

      $sql = "SELECT ip,sys,path,date FROM ".$mysql_admin." order by date DESC";
      $result = mysql_query($sql);
      echo "<br><span class=head>".$lang[34]."</span><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black width=740><tr align=center><td class=dat>".$lang[118]."</td><td class=dat>".$lang[130]."</td><td class=dat>".$lang[131]."</td><td class=dat>IP</td></tr>";
      while ($i = mysql_fetch_array($result)) {
      $data=date("H:i:s d/m/Y", $i[date] + $date_diff*60*60);
      echo "<tr><td class=mes>".$data."</td><td class=mes>".$i[sys]."</td><td class=mes><input type=text class=input value=".$i[path]."></td><td class=mes>".int2ip($i[ip])."</td></tr>";
      }
      echo "</table><br>";
      }            
      else
      {
      echo $err_mes_top.$lang[134].$suc_mes_bottom;
      include "../templates/footer.php";
      die;
      }
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
   else
   {
      $time = time();
      $sql = "INSERT INTO ".$mysql_admin." (ip, sys, path, date) VALUES (INET_ATON('".ip()."'), '".$_ENV['HTTP_USER_AGENT']."', '".$_ENV['REQUEST_URI']."', NOW(''))";
      mysql_query($sql);
      $data=date("H:i:s d/m/Y", $time + $date_diff*60*60);

      echo $err_mes_top.$lang[128].ip().$lang[129].$lang[133].$_ENV['HTTP_USER_AGENT'].$lang[135].$data.$err_mes_bottom;
      include "../templates/footer.php";
      die;
   }
}
else
{
$time = time();
      $sql = "INSERT INTO ".$mysql_admin." (ip, sys, path, date) VALUES (INET_ATON('".ip()."'), '".$_ENV['HTTP_USER_AGENT']."', '".$_ENV['REQUEST_URI']."', NOW(''))";
      mysql_query($sql);
      $data=date("H:i:s d/m/Y", $time + $date_diff*60*60);

      echo $err_mes_top.$lang[128].ip().$lang[129].$lang[133].$_ENV['HTTP_USER_AGENT'].$lang[135].$data.$err_mes_bottom;
      include "../templates/footer.php";
      die;
}
include "../templates/footer.php";
?>