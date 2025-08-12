<?
/*
=== MESSAGES FROM AUTHOR ====================================
This program is free,
and you could modify it as much as you like
But, ... If you appreciated with my works.
Please do not remove my software's name on this lines below,
Best regards;
Ridwank (mail@ridwank.com)
=============================================================
*/
echo "<!-- \nGuestbook-Text v1.0 \nDeveloped by RIDWANK \nPlease visit http://ridwank.com \n-->\n\n";

// *** LOADING CONFIG FILE

$data="data.txt"; // file guestbook data
$maxchar=500; // maximum characters for guesbook comments

function conv_asc2html($str,$mode){
   if ($mode==1){
      $str=str_replace("&gt;",">",$str);
      $str=str_replace("&lt;","<",$str);
   }
   if ($mode==2){
      $str=str_replace(">","&gt;",$str);
      $str=str_replace("<","&lt;",$str);
   }
   return $str;
}

function splitwordx(&$text,$w_max,$w_cut){
   if (!strstr($text," ")){ $text=$text." "; }
   $word = explode(" ", $text);
   if (empty($w_max)){ $w_max=10; }
   if (empty($w_cut)){ $w_cut=10; }
   for ($w=0; $w<count($word); $w++){
      $wordlen=strlen($word[$w]);
      if ($wordlen>$w_max){
         $wordnew="";
         $wordpart="";
         for ($l=0; $l<ceil($wordlen/$w_cut); $l++){
            $wordpart[$l]=substr($word[$w],($l*$w_cut),$w_cut)." ";
         }
         $wordnew=implode(" ",$wordpart);
         $word[$w]=$wordnew;
      }
   }
   $text = implode(" ", $word);
   return $text;
}

function disperr($str){
   echo "<table border=1 cellspacing=0 cellpadding=10 style=\"border-collapse:collapse\" bordercolor=#FFCCCC bgcolor=#FFEEEE><tr><td><font color=#FF0000 style=\"font-size:14px\"><b>Page Error :<br>$str</td></tr></table>\n";
}

// *** CHECKING FILE EXISTING
if (!file_exists($data)){
  disperr("File &quot;$data&quot; not found!");
  exit;
}

?>
<html>
<head><title>Guestbook</title>

<style><!--
BODY {font-family:tahoma; font-size:12px}
TD {font-family:tahoma; font-size:12px}
INPUT {font-family:tahoma; font-size:12px}
TEXTAREA {font-family:tahoma; font-size:12px}
SMALL {font-family:tahoma; color=#666666; font-size:11px}
--></style>

<script language="javascript">
<!--
function jumlahKata(form)
{ with (form)
  { sisa.value = <?=$maxchar?>-fcomment.value.length;
    if (parseInt(sisa.value)<0) { sisa.value = '0'; }
    fcomment.value = fcomment.value.substr(0,<?=$maxchar?>);
  }
  return;
}
//-->
</script>

</head>
<body>
<h3>GUESTBOOK</h3>
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

// *** UPDATING CONTENT
if ($_POST[act]=="post"){
   if (empty($_POST[fname])){ $ferr.="<li>Please Fill Your Name"; }
   if (empty($_POST[femail])){ $ferr.="<li>Please Fill Your Email"; }
   if (empty($_POST[fcomment])){ $ferr.="<li>Please Fill Comments"; }

   if (!empty($ferr)){
      disperr($ferr);
   } else {
      $ftanggal=date("d M Y - H:i");
      splitwordx($_POST[fname],30,5);
      splitwordx($_POST[femail],50,30);
      splitwordx($_POST[fhomepage],50,30);
      splitwordx($_POST[fcomment],30,5);
      $fcomment=substr($_POST[fcomment],0,$maxchar);
      $contents_new="|line|".$ftanggal;
      $contents_entry="|#|".$_POST[fname]."|#|".$_POST[femail]."|#|".$_POST[femail_show]."|#|".$_POST[fhomepage]."|#|".$_POST[fcomment];
      $contents_new.=$contents_entry;
      $contents_new=stripslashes($contents_new);
      $contents_new=conv_asc2html($contents_new,1);
      if (strstr($contents_old,$contents_entry)){
         disperr("Duplicate entry");
      } else {
         if ($file=fopen($data,"w")){
            $contents_new=$contents_new. "\r\n" . $contents_old;
            fputs($file,$contents_new);
            fclose($file);
            $contents_old=$contents_new;
         }
      }
   }
}

   echo "<table bgcolor=#EEEEEE border=1 cellspacing=0 cellpadding=5 style=\"border-collapse:collapse\" bordercolor=#999999>\n";
   echo "<FORM name=pesan METHOD=\"post\" ACTION=\"".$_SERVER[PHP_SELF]."\">\n";
   echo "<tr><td>Name* :<td><input name=\"fname\"></tr>\n";
   echo "<tr><td>Email* :<td><input name=\"femail\" width=10> \n";
   echo "<input type=\"checkbox\" name=\"femail_show\" value=\"y\" checked><small>show my email</tr>\n";
   echo "<tr><td>Homepage :<td><input name=\"fhomepage\" size=30></tr>\n";
   echo "<tr><td>Comment* :<td><textarea max=\"$machar\" name=\"fcomment\" rows=5 cols=40 ";
   echo " onKeyup='jumlahKata(document.pesan);' RAP";
   echo "></textarea><small><br>Max $maxchar chars.";
   echo "Chars left : <input type=\"text\" size=3 name=sisa maxlength=3 value=\"$maxchar\">";
   echo "</tr>\n";
   echo "<input type=hidden name=act value=post>";
   echo "<tr><td colspan=2><div>*: <small>Required field</small></div><div align=center><input type=\"submit\" name=\"btpost\" value=\"Post\"> <input type=\"reset\"></div></tr>\n";
   echo "</FORM>";
   echo "</table>\n";



   $contents_old=conv_asc2html($contents_old,2);
   $ar_row=explode("|line|",$contents_old);

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
         echo "[<a href=\"$PHP_SELF?row_start=$start\">$p</a>] ";
      }
   }

//echo "first:$row_first|start:$row_start|end:$row_end|last:$row_last";
   echo "&nbsp;<table bgcolor=#EEEEEE border=1 cellspacing=0 cellpadding=5 style=\"border-collapse:collapse\" bordercolor=#999999>\n";
   for ($r=$_GET[row_start]; $r<=$row_end; $r++){
      $ar_field=explode("|#|",$ar_row[$r]);
      $tanggal=$ar_field[0];
      $name=$ar_field[1];
      $email=$ar_field[2];
      $email_show=$ar_field[3];
      if ($email_show=="y"){
         $email="(<a href=\"mailto:$email\">$email</a>)";
      } else {
         $email="(<strike><font color=#666666>hidden</font></strike>)";
      }
      $homepage=$ar_field[4];
      $homepage="<a href=\"$homepage\" target=\"_blank\">$homepage</a>\n";
      $comment=$ar_field[5];
      echo "<tr><td rowspan=2>$r <td><small>$tanggal<td><small>$name $email<td><small>$homepage</tr>\n";
      echo "<tr><td bgcolor=#EEEECC colspan=3>$comment</tr>\n";
   }
   echo "</table>\n";

?>
</body>
</html>
