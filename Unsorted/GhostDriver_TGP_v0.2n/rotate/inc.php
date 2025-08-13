<?
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : GhostDriver TGP  (Random Gallery Rotator)        //
//   Release Version      : 0.2                                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//           Random Gallery Rotator  (c) Copyright  Nibbi `2002              //
//                    Copyright  WTN Team `2000 - `2002                      //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////
$path = "";
include ($path . "settings.inc.php");
$Vc5e7dfaf = date(Ymd); 
$Vfa596d3a = fopen($path . "date.txt", "r");
$V7176986e = filesize($path . "date.txt");
$Vc91584b7 = fread($Vfa596d3a, $V7176986e);
fclose($Vfa596d3a);
if ($Vfe01ce2a == 'Yes') {
 $V4b7d8663 = "20020202";
} else {
 $V4b7d8663 = $Vc91584b7;
}
if ($V4b7d8663 != $Vc5e7dfaf) {
 $Va1ff3f69 = fopen($path . "category.txt", "r");
$Vbd948cb4 = fread ($Va1ff3f69, 1024);
$Vb4db433e = explode("\n", $Vbd948cb4);
while (list($V9e3669d1) = each($Vb4db433e)) {
 $Vae7be26c = $Vb4db433e[$V9e3669d1];
$Vae7be26c = trim($Vae7be26c);
$V6820603f = $path . "html_galtmpl.txt";

 if ($Vae7be26c == 'All') {
 $V1b1cc7f0 = "SELECT * FROM TgpRotate where stat = 'Yes' and type= 'text' order by rand() limit $Ve332b677";
} else {
 $V1b1cc7f0 = "SELECT * FROM TgpRotate where stat = 'Yes' and category = '$Vae7be26c' and type= 'text' order by rand() limit $Ve332b677";
} 
 
 $Vb4a88417 = mysql_query($V1b1cc7f0) or die ("Failed to create new listing");

 while ($V4b43b0ae = mysql_fetch_array($Vb4a88417)) {
 
 $V7c4850a6 = fopen($V6820603f, "r");
$V60d26a92 = filesize($V6820603f);
$Veae51e3c = fread($V7c4850a6, $V60d26a92);
fclose($V7c4850a6);
$Ve2cc3569 = $Veae51e3c;
$Vb80bb774 = $V4b43b0ae["id"];
$Vd077f244 = $V4b43b0ae["category"];
$V572d4e42 = $V4b43b0ae["url"];
$V5d3ef8bf = $V4b43b0ae["description"];
$V1dee80c7 = substr($V5d3ef8bf, 0, $V6f7e0389); 
 $Ve2e42a07 = $V4b43b0ae["nickname"];
$Vf085c43b = $V4b43b0ae["numpic"];
$Vf8faf929 = $V4b43b0ae["ppost"];
$V9c9eba94 = $V4b43b0ae["numlisted"];
$V2f46e068 = $V6a1ed5b1 . $V572d4e42 . $V1a2d9e84; 
 $Ve2cc3569 = ereg_replace("%cat%",$Vd077f244, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%url%",$V2f46e068, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%desc%",$V1dee80c7, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%id%",$Vb80bb774, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%nick%",$Ve2e42a07, $Ve2cc3569); 
 $Ve2cc3569 = ereg_replace("%numpic%",$Vf085c43b, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%overurl%",$V572d4e42, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%numlist%",$V9c9eba94, $Ve2cc3569);
$Ve2cc3569 = ereg_replace("%dis_date%",$Vc78e688e, $Ve2cc3569);
$V8de623ea[] = $Ve2cc3569;

 $Vfb46a3bf = $V4b43b0ae['numlisted'] + 1;
$V187925ee = $V4b43b0ae['id'];
$V472672c0 = "UPDATE TgpRotate SET numlisted ='$Vfb46a3bf' WHERE id = $V187925ee"; 
 $Va832414d = mysql_query($V472672c0, $V0c1d0e2e);
}
$V06ecd72b = @implode("", $V8de623ea);

 if ($V0666f0ac = fopen($path . $Vae7be26c . ".inc.txt" , "w")) {
 fwrite($V0666f0ac, $V06ecd72b);
fclose($V0666f0ac);
$V8de623ea = array();
unset($Vae7be26c);
}

 }
if ($V18d0cb2b = fopen($path . "date.txt", "w")) {
 fwrite($V18d0cb2b, $Vc5e7dfaf);
fclose($V18d0cb2b);
} 
}
?>
