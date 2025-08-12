<?

$data="data.txt"; // file guestbook data
$maxchar=250; // maximum characters for guesbook comments

$adm_user="demo"; // ADMIN USER
$adm_pswd="1234"; // ADMIN PASSWORD

function conv_asc2html($str,$mode){
   if ($mode==1){
      $str=str_replace("&gt;",">",$str);
      $str=str_replace("&lt;","<",$str);
      $str=str_replace("&quot;",'"',$str);
   }
   if ($mode==2){
      $str=str_replace(">","&gt;",$str);
      $str=str_replace("<","&lt;",$str);
      $str=str_replace('"',"&quot;",$str);
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

function drawcss(){
   echo "<style><!--\n";
   echo "BODY {font-family:tahoma; font-size:12px}\n";
   echo "TD {font-family:tahoma; font-size:12px}\n";
   echo "INPUT {font-family:tahoma; font-size:12px}\n";
   echo "TEXTAREA {font-family:tahoma; font-size:12px}\n";
   echo "SMALL {font-family:tahoma; color=#666666; font-size:11px}\n";
   echo "a {color:#000088;text-decoration:none}\n";
   echo "a:hover {color:#FFFFFF;background-color:#000088}\n";
   echo "--></style>\n";

}
?>
