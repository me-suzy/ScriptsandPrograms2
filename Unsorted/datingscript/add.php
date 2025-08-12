<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               add.php                          #
# File purpose            Add user to DB                   #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
include_once 'templates/'.C_TEMP.'/header.php';

$errors=''; // By default  - no errors
if(!isset($a)) $a='';
if ($a == 'a') {
#################
### Check section - it very big and also very-very needed 
### for correct profiles in database
#################    
// Important and Need check&rewrite!
$fname=cb($fname);$lname=cb($lname);$pass=cb($pass);$rpass=cb($rpass);$email=cb($email);$url=cb($url);$icq=cb($icq);$aim=cb($aim);$phone=cb($phone);$city=cb($city);$job=cb($job);$hobby=cb($hobby);$descr=cb($descr);

// Check for numeric select
if(C_HACK1) {
if(!is_numeric($day)||!is_numeric($month)||!is_numeric($year)||!is_numeric($gender)||!is_numeric($purpose)||!is_numeric($country)||!is_numeric($marstat)||!is_numeric($child)||!is_numeric($height)||!is_numeric($weight)||!is_numeric($hcolor)||!is_numeric($ecolor)||!is_numeric($heightf)||!is_numeric($heightt)||!is_numeric($weightf)||!is_numeric($weightt)||!is_numeric($etnicity)||!is_numeric($setnicity)||!is_numeric($religion)||!is_numeric($sreligion)||!is_numeric($smoke)||!is_numeric($drink)||!is_numeric($education)||!is_numeric($sgender)||!is_numeric($agef)||!is_numeric($aget)||!is_numeric($hdyfu)) printm($w[1].'1',1);}

// Check for real data of arrays!
if(C_HACK2) { 
if(($day < 0)||($day > 31)||($month < 0)||($month > 12)||($gender < 0)||($gender >= sizeof($wg))||($sgender < 0)||($sgender >= sizeof($wg))||($purpose < 0)||($purpose >= sizeof($wp))||($country < 0)||($country >= sizeof($wcr))||($marstat < 0)||($marstat >= sizeof($wm))||($child < 0)||($child >= sizeof($wc))||($height < 0)||($height >= sizeof($wh))||($weight < 0)||($weight >= sizeof($ww))||($heightf < 0)||($heightf >= sizeof($wh))||($weightf < 0)||($weightf >= sizeof($ww))||($heightt < 0)||($heightt >= sizeof($wh))||($weightt < 0)||($weightt >= sizeof($ww))||($hcolor < 0)||($hcolor >= sizeof($whc))||($ecolor < 0)||($ecolor >= sizeof($we))||($etnicity < 0)||($etnicity >= sizeof($wet))||($setnicity < 0)||($setnicity >= sizeof($wet))||($religion < 0)||($religion >= sizeof($wr))||($sreligion < 0)||($sreligion >= sizeof($wr))||($smoke < 0)||($smoke >= sizeof($ws))||($drink < 0)||($drink >= sizeof($wd))||($education < 0)||($education >= sizeof($wed))||($agef < C_AGES)||($agef > C_AGEB)||($aget < C_AGES)||($aget > C_AGEB)||($hdyfu < 0)||($hdyfu >= sizeof($wu))) printm($w[1].'2',1);}

// Check for unic. email
if (C_UNICM) {
$result = mysql_query('SELECT count(id) as count FROM '.C_MYSQL_MEMBERS.' WHERE email = \''.$email.'\'');
$trows = mysql_fetch_array($result);
$count = $trows['count'];
if ($count != '0') $errors.=$w[2].'<br>';}

if (((C_FIRSTNR)||(!empty($fname)))&&((strlen($fname) > C_FIRSTNB)||(strlen($fname) < C_FIRSTNS))) {
$tm=array(C_FIRSTNS,C_FIRSTNB);
$errors.=template($w[3],$tm).'<br>';
}
if (((C_LASTNR)||(!empty($lname)))&&((strlen($lname) > C_LASTNB)||(strlen($lname) < C_LASTNS))) {
$tm=array(C_LASTNS,C_LASTNB);
$errors.=template($w[4],$tm).'<br>';
}

// Final checks for most real values for profiles
if (((C_BIRTHR)||(!empty($month))||(!empty($day))||(!empty($year)))&&(($month == "0")||($day == "0")||($year == "0"))) $errors.=$w[5].'<br>';
if ((strlen($pass) > C_PASSB)||(strlen($pass) < C_PASSS)) { 
$tm=array(C_PASSS,C_PASSB);
$errors.=template($w[6],$tm).'<br>';
}
if ((C_GENDR)&&($gender == "0")) $errors.=$w[7].'<br>';
if ((C_SGENDR)&&($sgender == "0")) $errors.=$w[8].'<br>';
if ((C_PURPR)&&($purpose == "0")) $errors.=$w[9].'<br>';
if ((C_CNTRR)&&($country == "0")) $errors.=$w[10].'<br>';
if (c_email($email) == 0) $errors.=$w[11].'<br>';
if (!empty($url)) {
   $urll=checkurl($url);
   if (strlen($urll) < 3) {$errors.=$w[12].'<br>';}
}
if ((!empty($icq))&&(!is_numeric($icq))) $errors.=$w[13].'<br>';
if ((!empty($aim))&&((strlen($aim) < 3)||(strlen($aim) > 16))) $errors.=$w[14].'<br>';
if ((C_PHONER)&&(empty($phone))) $errors.=$w[15].'<br>';
if ((C_CITYR)&&(empty($city))) $errors.=$w[16].'<br>';
if ((C_MARSR)&&($marstat == "0")) $errors.=$w[17].'<br>';
if ((C_CHILDR)&&($child == "0")) $errors.=$w[18].'<br>';
if ((C_HGHTR)&&($height == "0")) $errors.=$w[19].'<br>';
if ((C_WGHTR)&&($weight == "0")) $errors.=$w[20].'<br>';
if ((C_SHGHTR)&&($heightf == "0")) $errors.=$w[21].'<br>';
if ((C_SHGHTR)&&($heightt == "0")) $errors.=$w[21].'<br>';
if ((C_SWGHTR)&&($weightf == "0")) $errors.=$w[22].'<br>';
if ((C_SWGHTR)&&($weightt == "0")) $errors.=$w[22].'<br>';
if ((C_HAIRR)&&($hcolor == "0")) $errors.=$w[23].'<br>';
if ((C_EYER)&&($ecolor == "0")) $errors.=$w[24].'<br>';
if ((C_ETNR)&&($etnicity == "0")) $errors.=$w[25].'<br>';
if ((C_RELR)&&($religion == "0")) $errors.=$w[26].'<br>';
if ((C_SETNR)&&($setnicity == "0")) $errors.=$w[27].'<br>';
if ((C_SRELR)&&($sreligion == "0")) $errors.=$w[28].'<br>';
if ((C_SMOKER)&&($smoke == "0")) $errors.=$w[29].'<br>';
if ((C_DRINKR)&&($drink == "0")) $errors.=$w[30].'<br>';
if ((C_EDUCR)&&($education == "0")) $errors.=$w[31].'<br>';
if ((C_JOBR)&&(empty($job))) $errors.=$w[32].'<br>';
if (C_HOBBR) {
   if (empty($hobby) || trim($hobby) == "") $errors.=$w[35].'<br>';
   if (strlen($hobby) > C_HOBBB) {
   $tm=array(C_HOBBB);
   $errors.=template($w[36],$tm).'<br>';
   }
   $e = explode(" ",$hobby);
   for ($a = 0; $a < sizeof($e); $a++){
       $o = strlen($e[$a]);
       if ($o > C_HOBBW) {
           $tm=array(C_HOBBW);
           $errors.=template($w[37],$tm).'<br>';
       }
   }
}

if (empty($descr) || trim($descr) == "") printm($w[38]);
if (strlen($descr) > C_DESCB) {
    $tm=array(C_DESCB);
    $errors.=template($w[39],$tm).'<br>';
    } 
$e = explode(" ",$descr);
for ($a = 0; $a < sizeof($e); $a++){$o = strlen($e[$a]);
    if ($o > C_DESCW) {
    $tm=array(C_DESCW);
    $errors.=template($w[40],$tm).'<br>';
    }
}

if (C_CHECK_REGISTER == '0') $cst = 7;
elseif (C_CHECK_REGISTER == '2') $cst = 1;
else $cst = 0;
$picture=array('','','');
if ((C_PHOTOR)&&((empty($HTTP_POST_FILES['file0']['name']))&&(empty($HTTP_POST_FILES['file1']['name']))&&(empty($HTTP_POST_FILES['file2']['name'])))) $errors.=$w[41].'<br>';
////////////// Include class for file uploading!
include_once 'classes/upload.class.php';
//////////////
$time = time();
for($p=0;$p<=2;$p++) {

$file='file'.$p;$k=$p+1;
if(!empty($HTTP_POST_FILES[$file]['name'])) {
if(!C_IMG_ERR) { // If Unavailable image upload errors with UIN
   $dir = date("mY", $time);$slash="/";
   if (!file_exists(C_PATH.'/members/uploads/'.$dir))
   {umask(0);mkdir (C_PATH."/members/uploads/".$dir, 0777);}
   } else $dir=$slash="";
$fb=date("dHis",$time);$fe=rand(0,999);$fn =$fb."-".$fe;
$intpic = $dir.$slash.$fn.'.';
$u = new Upload($file,C_MAXSZ,C_MAXWD,C_MAXHG,C_PATH.'/members/uploads/'.$intpic);
if(!$u->do_upload()) $errors.=$u->getErrors().'<br>'; 
$picture[$p]=$intpic.$u->getType();
}
}
if($errors == '') {
mysql_query("INSERT INTO ".C_MYSQL_MEMBERS." VALUES ('','".$fname."','".$lname."','".$pass."',
'".$year."-".$month."-".$day."','".$gender."',
'".$purpose."','".$country."','".$email."','".$url."',
'".$icq."','".$aim."','".$phone."','".$city."','".$marstat."','".$child."','".$height."','".$weight."','".$hcolor."','".$ecolor."','".$etnicity."','".$religion."','".$smoke."','".$drink."','".$education."','".$job."','".$hobby."','".$descr."','".$sgender."','".$setnicity."','".$sreligion."','".$agef."','".$aget."','".$heightf."','".$heightt."','".$weightf."','".$weightt."','".$hdyfu."','".$picture[0]."','".$picture[1]."','".$picture[2]."','".horo($month,$day)."',NOW(''),NOW(''),INET_ATON('".ip()."'),'".$cst."','0')") or die(mysql_error());

$last_id = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID() AS last_id"));
$last_id = $last_id['last_id'];

$codegen=code_gen();
if((C_CHECK_REGISTER == '1')||(C_CHECK_REGISTER == '3')) {
$str=$w[42];
mysql_query("INSERT INTO ".C_MYSQL_TEMP." VALUES ('".$last_id."',NOW(''),'".$codegen."')");
sendmail(C_ADMINM,$email,$w[43],$w[44].C_URL.'/check.php?id='.$last_id.'&code='.$codegen,'text');
}
elseif(C_CHECK_REGISTER == '2') {
$str=$w[45];
}
elseif(C_CHECK_REGISTER == '0') {
if (C_ID == '2') $last_id=$email;
$str=$w[46].$last_id.$w[47].$pass;
}
printm($str);
} else {
mes($w[290].'<p align=left>'.$errors.'</p>');
}
}
?>
<script language="JavaScript">
<!--
function formCheck(form) {
<?if (C_FIRSTNR) {$tm=array(C_FIRSTNS,C_FIRSTNB);?>
if (form.fname.value == "")
{alert("<?=template($w[3],$tm)?>");return false;}
<?}?>
<?if (C_LASTNR) {$tm=array(C_LASTNS,C_LASTNB);?>
if (form.lname.value == "")
{alert("<?=template($w[4],$tm)?>");return false;}
<?}?>
if (form.pass.value == "")
<?$tm=array(C_PASSS,C_PASSB);?>
{alert("<?=template($w[6],$tm)?>");return false;}
if (form.rpass.value == "")
{alert("<?=$w[48]?>");return false;}
if (form.pass.value != form.rpass.value)
{alert("<?=$w[49]?>");return false;}
<?if (C_BIRTHR) {?>
if ((form.month.selectedIndex=="")||(form.day.selectedIndex=="")||(form.year.selectedIndex==""))
{alert("<?=$w[5]?>");return false;}
<?}?>
<?if (C_GENDR) {?>
if (form.gender.selectedIndex=="")
{alert("<?=$w[7]?>");return false;}
<?}?>
<?if (C_SGENDR) {?>
if (form.sgender.selectedIndex=="")
{alert("<?=$w[8]?>");return false;}
<?}?>
<?if (C_PURPR) {?>
if (form.purpose.selectedIndex=="")
{alert("<?=$w[9]?>");return false;}
<?}?>
<?if (C_CNTRR) {?>
if (form.country.selectedIndex=="")
{alert("<?=$w[10]?>");return false;}
<?}?>
if (form.email.value == "")
{alert("<?=$w[11]?>");return false;}
<?if (C_PHONER) {?>
if (form.phone.value == "")
{alert("<?=$w[15]?>");return false;}
<?}?>
<?if (C_CITYR) {?>
if (form.city.value == "")
{alert("<?=$w[16]?>");return false;}
<?}?>
<?if (C_MARSR) {?>
if (form.marstat.selectedIndex=="")
{alert("<?=$w[17]?>");return false;}
<?}?>
<?if (C_CHILDR) {?>
if (form.child.selectedIndex=="")
{alert("<?=$w[18]?>");return false;}
<?}?>
<?if (C_HGHTR) {?>
if (form.height.selectedIndex=="")
{alert("<?=$w[19]?>");return false;}
<?}?>
<?if (C_WGHTR) {?>
if (form.weight.selectedIndex=="")
{alert("<?=$w[20]?>");return false;}
<?}?>
<?if (C_HAIRR) {?>
if (form.hcolor.selectedIndex=="")
{alert("<?=$w[23]?>");return false;}
<?}?>
<?if (C_EYER) {?>
if (form.ecolor.selectedIndex=="")
{alert("<?=$w[24]?>");return false;}
<?}?>
<?if (C_ETNR) {?>
if (form.etnicity.selectedIndex=="")
{alert("<?=$w[25]?>");return false;}
<?}?>
<?if (C_RELR) {?>
if (form.religion.selectedIndex=="")
{alert("<?=$w[26]?>");return false;}
<?}?>
<?if (C_SETNR) {?>
if (form.setnicity.selectedIndex=="")
{alert("<?=$w[27]?>");return false;}
<?}?>
<?if (C_SRELR) {?>
if (form.sreligion.selectedIndex=="")
{alert("<?=$w[28]?>");return false;}
<?}?>
<?if (C_SMOKER) {?>
if (form.smoke.selectedIndex=="")
{alert("<?=$w[29]?>");return false;}
<?}?>
<?if (C_DRINKR) {?>
if (form.drink.selectedIndex=="")
{alert("<?=$w[30]?>");return false;}
<?}?>
<?if (C_EDUCR) {?>
if (form.education.selectedIndex=="")
{alert("<?=$w[31]?>");return false;}
<?}?>
<?if (C_JOBR) {?>
if (form.job.value=="")
{alert("<?=$w[32]?>");return false;}
<?}?>
<?if (C_HOBBR) {?>
if (form.hobby.value=="")
{alert("<?=$w[35]?>");return false;}
<?}?>
if (form.descr.value=="")
{alert("<?=$w[38]?>");return false;}
<?if (C_HDYFUR) {?>
if (form.hdyfu.selectedIndex=="")
{alert("<?=$w[34]?>");return false;}
<?}?>
<?if (C_PHOTOR) {?>
if ((form.file0.value=="")&&(form.file1.value=="")&&(form.file2.value==""))
{alert("<?=$w[41]?>");return false;}
<?}?>

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>
<form action="add.php" method="post" enctype="multipart/form-data" name=form OnSubmit="return formCheck(this)">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="a">
<center><span class=head><?=$w[50]?></span><br>
<center><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[51]?><?if(C_FIRSTNR) echo $w[0];?> [<?=C_FIRSTNS?>-<?=C_FIRSTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=fname maxlength="<?=C_FIRSTNB?>" value="<?if(isset($fname)) echo $fname;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[53]?><?if(C_LASTNR) echo $w[0];?> [<?=C_LASTNS?>-<?=C_LASTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=lname maxlength="<?=C_LASTNB?>" value="<?if(isset($lname)) echo $lname;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[54]?><?=$w[0]?> [<?=C_PASSS?>-<?=C_PASSB?> <?=$w[52]?>]</td>
<td><input class=input type=password name=pass maxlength="<?=C_PASSB?>" value="<?if(isset($pass)) echo $pass;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[55]?><?=$w[0]?> [<?=C_PASSS?>-<?=C_PASSB?> <?=$w[52]?>]</td>
<td><input class=input type=password name=rpass maxlength="<?=C_PASSB?>" value="<?if(isset($rpass)) echo $rpass;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[56]?><?if(C_BIRTHR) echo $w[0];?></td>
<td>
<select name="month" class="minput"><option value=0> --------- 
<? $p=1; while(isset($wmm[$p])) {

if(isset($month) && ($month == $p)) echo '<option value="'.$p.'" selected>'.$wmm[$p];
else echo '<option value="'.$p.'">'.$wmm[$p];
$p++; 
}
?>
</select>
<select name="day" class="sinput"><option value=0> ----- 
<? for($p=1;$p<32;$p++) {
if(isset($day) && ($day == $p)) echo '<option selected>'.$p;
else echo '<option>'.$p;
}
?>
</select>
<? $y=date("Y", time());$yfrom=$y-C_AGES;$yto=$y-C_AGEB; ?>
<select name="year" class="sinput"><option value=0> ----- 
<? for($p=$yto;$p<=$yfrom;$p++) {
if(isset($year) && ($year == $p)) echo '<option selected>'.$p;
else echo '<option>'.$p;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[57]?><?if(C_GENDR) echo $w[0];?></td>
<td>
<select name="gender" class="input"><option value=0> --------- 
<? $p=1;while(isset($wg[$p])) {
if(isset($gender) && ($gender == $p)) echo '<option value="'.$p.'" selected>'.$wg[$p];
else echo '<option value="'.$p.'">'.$wg[$p];
$p++;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[58]?><?if(C_PURPR) echo $w[0];?></td>
<td>
<select name="purpose" class="input"><option value=0> --------- 
<? $p=1;while(isset($wp[$p])) {
if(isset($purpose) && ($purpose == $p)) echo '<option value="'.$p.'" selected>'.$wp[$p];
else echo '<option value="'.$p.'">'.$wp[$p];
$p++;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[59]?><?if(C_CNTRR) echo $w[0];?></td>
<td>
<select name="country" class="input">
<? $p=0;asort($wcr);reset($wcr);
while (list ($p, $val) = each ($wcr)) {
if(isset($country) && ($country == $p)) echo '<option value="'.$p.'" selected>'.$val;
else echo '<option value="'.$p.'">'.$val;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[60]?><?=$w[0];?></td>
<td><input class=input type=text name=email value="<?if(isset($email)) echo $email;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[61]?></td>
<td><input class=input type=text name=url value="<?if(isset($url)) echo $url;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[62]?></td>
<td><input class=input type=text name=icq value="<?if(isset($icq)) echo $icq;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[63]?></td>
<td><input class=input type=text name=aim value="<?if(isset($aim)) echo $aim;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[64]?><?if(C_PHONER) echo $w[0];?></td>
<td><input class=input type=text name=phone value="<?if(isset($phone)) echo $phone;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[65]?><?if(C_CITYR) echo $w[0];?></td>
<td><input class=input type=text name=city value="<?if(isset($city)) echo $city;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[66]?><?if(C_MARSR) echo $w[0];?></td>
<td>
<select name="marstat" class="input"><option value=0> --------- 
<? $p=1;while(isset($wm[$p])) {
if(isset($marstat) && ($marstat == $p)) echo '<option value="'.$p.'" selected>'.$wm[$p];
else echo '<option value="'.$p.'">'.$wm[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[67]?><?if(C_CHILDR) echo $w[0];?></td>
<td>
<select name="child" class="input"><option value=0> --------- 
<? $p=1;while(isset($wc[$p])) {
if(isset($child) && ($child == $p)) echo '<option value="'.$p.'" selected>'.$wc[$p];
else echo '<option value="'.$p.'">'.$wc[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[68]?><?if(C_HGHTR) echo $w[0];?></td>
<td>
<select name="height" class="input"><option value=0> --------- 
<? $p=1;while(isset($wh[$p])) {
if(isset($height) && ($height == $p)) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[69]?><?if(C_WGHTR) echo $w[0];?></td>
<td>
<select name="weight" class="input"><option value=0> --------- 
<? $p=1;while(isset($ww[$p])) {if(isset($weight) && ($weight == $p)) echo '<option value="'.$p.'" selected>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[70]?><?if(C_HAIRR) echo $w[0];?></td>
<td>
<select name="hcolor" class="input"><option value=0> --------- 
<? $p=1;while(isset($whc[$p])) {if(isset($hcolor) && ($hcolor == $p)) echo '<option value="'.$p.'" selected>'.$whc[$p];
else echo '<option value="'.$p.'">'.$whc[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[71]?><?if(C_EYER) echo $w[0];?></td>
<td>
<select name="ecolor" class="input"><option value=0> --------- 
<? $p=1;while(isset($we[$p])) {if(isset($ecolor) && ($ecolor == $p)) echo '<option value="'.$p.'" selected>'.$we[$p];
else echo '<option value="'.$p.'">'.$we[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[72]?><?if(C_ETNR) echo $w[0];?></td>
<td>
<select name="etnicity" class="input"><option value=0> --------- 
<? $p=1;while(isset($wet[$p])) {if(isset($etnicity) && ($etnicity == $p)) echo '<option value="'.$p.'" selected>'.$wet[$p];
else echo '<option value="'.$p.'">'.$wet[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[73]?><?if(C_RELR) echo $w[0];?></td>
<td>
<select name="religion" class="input"><option value=0> --------- 
<? $p=1;while(isset($wr[$p])) {if(isset($religion) && ($religion == $p)) echo '<option value="'.$p.'" selected>'.$wr[$p];
else echo '<option value="'.$p.'">'.$wr[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[74]?><?if(C_SMOKER) echo $w[0];?></td>
<td>
<select name="smoke" class="input"><option value=0> --------- 
<? $p=1;while(isset($ws[$p])) {
if(isset($smoke) && ($smoke == $p)) echo '<option value="'.$p.'" selected>'.$ws[$p];
else echo '<option value="'.$p.'">'.$ws[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[75]?><?if(C_DRINKR) echo $w[0];?></td>
<td>
<select name="drink" class="input"><option value=0> --------- 
<? $p=1;while(isset($wd[$p])) {if(isset($drink) && ($drink == $p)) echo '<option value="'.$p.'" selected>'.$wd[$p];
else echo '<option value="'.$p.'">'.$wd[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[76]?><?if(C_EDUCR) echo $w[0];?></td>
<td>
<select name="education" class="input"><option value=0> --------- 
<? $p=1;while(isset($wed[$p])) {if(isset($education) && ($education == $p)) echo '<option value="'.$p.'" selected>'.$wed[$p];
else echo '<option value="'.$p.'">'.$wed[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[77]?><?if(C_JOBR) echo $w[0];?></td>
<td><input class=input type=text name=job value="<?if(isset($job)) echo $job;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[78]?><?if(C_HOBBR) echo $w[0];?></td>
<td><input class=input type=text name=hobby value="<?if(isset($hobby)) echo $hobby;?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td><?=$w[79]?><?=$w[0];?></td><td><textarea class=textarea cols=20 rows=8 name=descr><?if(isset($descr)) echo $descr;?></textarea></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[80]?><?if(C_SGENDR) echo $w[0];?></td>
<td>
<select name="sgender" class="input"><option value=0> --------- 
<? $p=1;while(isset($wg[$p])) {if(isset($sgender) && ($sgender == $p)) echo '<option value="'.$p.'" selected>'.$wg[$p];
else echo '<option value="'.$p.'">'.$wg[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[81]?><?if(C_SETNR) echo $w[0];?></td>
<td>
<select name="setnicity" class="input"><option value=0> --------- 
<? $p=1;while(isset($wet[$p])) {if(isset($setnicity) && ($setnicity == $p)) echo '<option value="'.$p.'" selected>'.$wet[$p];
else echo '<option value="'.$p.'">'.$wet[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[82]?><?if(C_SRELR) echo $w[0];?></td>
<td>
<select name="sreligion" class="input"><option value=0> --------- 
<? $p=1;while(isset($wr[$p])) {if(isset($sreligion) && ($sreligion == $p)) echo '<option value="'.$p.'" selected>'.$wr[$p];
else echo '<option value="'.$p.'">'.$wr[$p];
$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[83]?><?if(C_SAGER) echo $w[0];?></td>
<td><select name="agef" class="minput"> 
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if(isset($agef) && ($agef == $p)) echo '<option selected>'.$p;
else echo '<option>'.$p;
}?>
</select>-<select name="aget" class="minput">
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if(isset($aget) && ($aget == $p)) echo '<option selected>'.$p;
else {
   if(($p == C_AGEB) && ($a == '')) echo '<option selected>'.$p;
else echo '<option>'.$p;
   }
}?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[84]?><?if(C_SHGHTR) echo $w[0];?></td>
<td><select name="heightf" class="minput"> 
<? $p=1;while(isset($wh[$p])) {
if(isset($heightf) && ($heightf == $p)) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;
}?>
</select>-<select name="heightt" class="minput">
<? $p=1;while(isset($wh[$p])) {
if(isset($heightt) && ($heightt == $p)) echo '<option value="'.$p.'" selected>'.$wh[$p];
else {
if(($p == (sizeof($wh)-1)) && ($a == '')) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
}
$p++;}?>
</select>

</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[85]?><?if(C_SWGHTR) echo $w[0];?></td>
<td><select name="weightf" class="minput">
<? $p=1;while(isset($ww[$p])) {
if(isset($weightf) && ($weightf == $p)) echo '<option value="'.$p.'" selected>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}?>
</select>-<select name="weightt" class="minput">
<? $p=1;while(isset($ww[$p])) {
if(isset($weightt) && ($weightt == $p)) echo '<option value="'.$p.'" selected>'.$ww[$p];
else {
if(($p == (sizeof($ww)-1)) && ($a == '')) echo '<option value="'.$p.'" selected>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
}
$p++;}?>
</select>
</td></tr>

<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[86]?><?if(C_HDYFUR) echo $w[0];?></td>
<td>
<select name="hdyfu" class="input"><option selected value=0> --------- 
<? $p=1;while(isset($wu[$p])) {echo '<option value="'.$p.'">'.$wu[$p];if(isset($hdyfu) && ($hdyfu == $p)) echo '<option value="'.$p.'" selected>'.$wu[$p];$p++;}
?>
</select>
</td></tr>
<?for($i=0;$i<=2;$i++) {$k=$i+1;?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[87]?> <?=$k?> <?if((C_PHOTOR)&&($i=="0")) echo $w[0];$tm=array(C_MAXSZ);echo template($w[223],$tm);?></td>
<td><input class=input type=file name="file<?=$i?>"></td></tr>
<?}?>
<? if((C_AGR) && ($a == '')) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td colspan=2 align=right>
<textarea cols="60" rows="10" class=binput>
<?php
if(!file_exists(C_PATH.'/languages/'.$l.'/agr.php') || (empty($l))) $l='default';
include_once C_PATH.'/languages/'.$l.'/agr.php';
?>
</textarea>
</td></tr>
<? } ?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td colspan=2 align=right><input class=input type=submit value="<?=$w[89]?>" name="submit">
</td></tr>
</table></Td></Tr></table><br>
<? include_once 'templates/'.C_TEMP.'/footer.php';?>