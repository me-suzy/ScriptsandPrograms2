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
$post = "";
include ($post . "settings.inc.php");
$V4a8a08f0=1;
$Vc5e7dfaf = date(Ymd); 
$Ve72fd8b5 = fopen($post . "pdate.txt", "r");
$V893412c9 = filesize($post . "pdate.txt");
$V3216891e = fread($Ve72fd8b5, $V893412c9);
fclose($Ve72fd8b5);
if ($Vfe01ce2a == 'Yes') {
 $V4b7d8663 = "20020202";
} else {
 $V4b7d8663 = $V3216891e;
}
if ($V4b7d8663 != $Vc5e7dfaf) {
 $V328364fa = fopen($post . "category.txt", "r");
$Vfc633e61 = fread ($V328364fa, 1024);
$V5d877e28 = explode("\n", $Vfc633e61);
while (list($V35b36b28) = each($V5d877e28)) {
 $Vd4277f68 = $V5d877e28[$V35b36b28];
$Vd4277f68 = trim($Vd4277f68);
$V18ce02ad = $post . "phtml_galtmpl.txt";

 
 if ($Vd4277f68 == 'All') {
 $Vf5d1610b = "SELECT * FROM TgpRotate where stat = 'Yes' and type= 'thumb' order by rand() limit $Va70fcfcf";
} else {
 $Vf5d1610b = "SELECT * FROM TgpRotate where stat = 'Yes' and category = '$Vd4277f68' and type= 'thumb' order by rand() limit $Va70fcfcf";
} 
 $V89e3503b = mysql_query($Vf5d1610b) or die ("Failed to create new listing");

 $Vbcf76f8f[] = "<table width=$V255a5cac border=$V2de42fbb cellspacing=$Vc73291af cellpadding=$V42a76fcb>
 <tr align=center>";

 while ($V64e1e1cb = mysql_fetch_array($V89e3503b)) {
 
 $Va521c5ed = fopen($V18ce02ad, "r");
$V2307facb = filesize($V18ce02ad);
$V8c5e3635 = fread($Va521c5ed, $V2307facb);
fclose($Va521c5ed);
$V9331ea3c = $V8c5e3635;
$Vb80bb774 = $V64e1e1cb["id"];
$Vd077f244 = $V64e1e1cb["category"];
$V572d4e42 = $V64e1e1cb["url"];
$V9c9eba94 = $V64e1e1cb["numlisted"];
$V3039627b = $V64e1e1cb["picname"];
$V2f46e068 = $V89001b0f . $V572d4e42 . $V1aee9883; 
 $V9331ea3c = ereg_replace("%cat%",$Vd077f244, $V9331ea3c);
$V9331ea3c = ereg_replace("%url%",$V2f46e068, $V9331ea3c);
$V9331ea3c = ereg_replace("%desc%",$V1dee80c7, $V9331ea3c);
$V9331ea3c = ereg_replace("%id%",$Vb80bb774, $V9331ea3c);
$V9331ea3c = ereg_replace("%nick%",$Ve2e42a07, $V9331ea3c);
$V9331ea3c = ereg_replace("%picname%",$V3039627b, $V9331ea3c);
$V9331ea3c = ereg_replace("%numpic%",$Vf085c43b, $V9331ea3c);
$V9331ea3c = ereg_replace("%overurl%",$V572d4e42, $V9331ea3c);
$V9331ea3c = ereg_replace("%numlist%",$V9c9eba94, $V9331ea3c);
$V9331ea3c = ereg_replace("%dis_date%",$Vc78e688e, $V9331ea3c);
$Vbcf76f8f[] = $V9331ea3c;

 if ($V4a8a08f0==$Vf1965a85) {
 $Vbcf76f8f[] = "</tr> <tr align=center>";
$V4a8a08f0=0;
}
$V4a8a08f0++;
$Vfb46a3bf = $V64e1e1cb['numlisted'] + 1;
$V187925ee = $V64e1e1cb['id'];
$V472672c0 = "UPDATE TgpRotate SET numlisted ='$Vfb46a3bf' WHERE id = $V187925ee"; 
 $Va832414d = mysql_query($V472672c0, $V0c1d0e2e);
}
$Vbcf76f8f[] = "</tr></table>";
$Vada0efa8 = @implode("", $Vbcf76f8f);

 if ($V0666f0ac = fopen($post . $Vd4277f68 . ".pinc.txt" , "w")) {
 fwrite($V0666f0ac, $Vada0efa8);
fclose($V0666f0ac);
$Vbcf76f8f = array();
unset($Vd4277f68);
}

 }
if ($Vede8efd5 = fopen($post . "pdate.txt", "w")) {
 fwrite($Vede8efd5, $Vc5e7dfaf);
fclose($Vede8efd5);
} 
}
?>
