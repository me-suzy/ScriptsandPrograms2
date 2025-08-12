<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               members/index.php                #
# File purpose            Members area                     #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once '../include/config.inc.php';
include_once '../include/options.inc.php';
include_once '../include/security.inc.php';
include_once '../include/functions.inc.php';
include_once '../templates/'.C_TEMP.'/config.php';
security(C_MUST,$w[152]);
include_once '../templates/'.C_TEMP.'/header.php';


#############################################################
# Secure Zone                                               #
#############################################################
if(!isset($a)) $a='';
switch($a) {
case "v":
break;
case "p":
######################
# Change password - form
######################
?>
<script language="JavaScript">
<!--
function formCheck(form) {
if (form.oldpass.value == "")
{alert("<?=$w[239]?>");return false;}
if (form.newpass.value == "")
{alert("<?=$w[240]?>");return false;}
if (form.repass.value == "")
{alert("<?=$w[241]?>");return false;}
if (form.newpass.value != form.repass.value)
{alert("<?=$w[49]?>");return false;}

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>
<form action="index.php" method="post" name=form OnSubmit="return formCheck(this)">
<input class=input type=hidden name="l" value="<?=$l?>">
<input class=input type=hidden name="a" value="x">
<span class=head><?=$w[242]?></span>
<br><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr bgcolor="<?=COLOR1?>">
<Td><?=$w[243]?></Td>
<td><input type="password" name="oldpass" class="input"></td>
</Tr>
<Tr bgcolor="<?=COLOR1?>">
<Td><?=$w[244]?></Td>
<td><input type="password" name="newpass" maxlength="<?=C_PASSB?>" class="input"></td>
</Tr>
<Tr bgcolor="<?=COLOR1?>">
<Td><?=$w[245]?></Td>
<td><input type="password" name="repass" maxlength="<?=C_PASSB?>" class="input"></td>
</Tr>
<Tr bgcolor="<?=COLOR1?>" align=center><Td colspan="2">
<input class=input type=submit value="<?=$w[100]?>" name="submit">
</td></tr></Table></td></Tr></Table></form>
<?
break;
case "h":
######################
# Delete profile
######################
if(C_REMOVE_ALLOW != '0') {
?>
<form action="index.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="y">
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_BWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td width="<?=C_BWIDTH?>" bgcolor="<?=COLOR1?>" valign="top">
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_BWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=mes($w[249])?>
</td></tr>
<tr class=mes bgcolor="<?=COLORH?>" align=center><td colspan=8><input type="submit" value="<?=$w[101]?>" class=input></td></tr>
</table></Td></Tr></Table></Td></Tr></Table></Td></Tr></Table></form>
<?
}
break;
case "y":
######################
# Delete profile - MySQL remove (only add remove status)
######################
if(C_REMOVE_ALLOW != '0') {
unset($m);$id=$_SESSION['m'];
if(C_REMOVE_ALLOW != '3') { // Remove profile from database
if(isset($id) && is_numeric($id)) {
   $tmp=mysql_query("SELECT pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'");
    while($i=mysql_fetch_array($tmp)) {
         for($k=1;$k<=3;$k++) $tmpm='pic'.$k;   
         if(($i[$tmpm] != '') && (is_file(C_PATH.'/members/uploads/'.$i[$tmpm]))) unlink (C_PATH.'/members/uploads/'.$i[$tmpm]);
    }
}
mysql_query("DELETE FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'") or die(mysql_error());
mysql_query("DELETE FROM ".C_MYSQL_TEMP." WHERE id='".$id."'") or die(mysql_error());
session_destroy();unset($s);unset($m);
$tm=array($id);
printm(template($w[250],$tm));
}
else {
$remstat = (C_REMOVE_ALLOW == '1') ? '3' : '8';
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET status='".$remstat."' WHERE id='".$_SESSION['m']."'") or die(mysql_error());
session_destroy();unset($s);unset($m);
printm($w[251],2);
}
}
break;
case "x":
######################
# Change password - check and MySQL change
######################
if(empty($oldpass)||empty($newpass)||empty($repass)) printm($w[164],1);
$newpass=cb($newpass);
if($newpass != $repass) printm($w[253],1);
$tmp=mysql_query("SELECT password FROM ".C_MYSQL_MEMBERS." WHERE id='".$_SESSION['m']."' AND status = '7'");
$count=mysql_num_rows($tmp);
if($count=="0") printm($w[254],1);
while($i=mysql_fetch_array($tmp)) {
if($oldpass != $i['password']) printm($w[255],2);
}
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET password='".$newpass."' WHERE id='".$_SESSION['m']."' AND status = '7'");
printm($w[256],2);
break;
case "u":
######################
# Change profile - check and MySQL change
######################
$fname=cb($fname);$lname=cb($lname);$email=cb($email);$url=cb($url);$icq=cb($icq);$aim=cb($aim);$phone=cb($phone);$city=cb($city);$job=cb($job);$hobby=cb($hobby);$descr=cb($descr);
// Check for numeric select
if(C_HACK1) {
if(!is_numeric($day)||!is_numeric($month)||!is_numeric($year)||!is_numeric($gender)||!is_numeric($purpose)||!is_numeric($country)||!is_numeric($marstat)||!is_numeric($child)||!is_numeric($height)||!is_numeric($weight)||!is_numeric($hcolor)||!is_numeric($ecolor)||!is_numeric($heightf)||!is_numeric($heightt)||!is_numeric($weightf)||!is_numeric($weightt)||!is_numeric($etnicity)||!is_numeric($setnicity)||!is_numeric($religion)||!is_numeric($sreligion)||!is_numeric($smoke)||!is_numeric($drink)||!is_numeric($education)||!is_numeric($sgender)||!is_numeric($agef)||!is_numeric($aget)) printm($w[1].'1',1);}

// Check for real data of arrays!
if(C_HACK2) { 
if(($day < 0)||($day > 31)||($month < 0)||($month > 12)||($gender < 0)||($gender >= sizeof($wg))||($sgender < 0)||($sgender >= sizeof($wg))||($purpose < 0)||($purpose >= sizeof($wp))||($country < 0)||($country >= sizeof($wcr))||($marstat < 0)||($marstat >= sizeof($wm))||($child < 0)||($child >= sizeof($wc))||($height < 0)||($height >= sizeof($wh))||($weight < 0)||($weight >= sizeof($ww))||($heightf < 0)||($heightf >= sizeof($wh))||($weightf < 0)||($weightf >= sizeof($ww))||($heightt < 0)||($heightt >= sizeof($wh))||($weightt < 0)||($weightt >= sizeof($ww))||($hcolor < 0)||($hcolor >= sizeof($whc))||($ecolor < 0)||($ecolor >= sizeof($we))||($etnicity < 0)||($etnicity >= sizeof($wet))||($setnicity < 0)||($setnicity >= sizeof($wet))||($religion < 0)||($religion >= sizeof($wr))||($sreligion < 0)||($sreligion >= sizeof($wr))||($smoke < 0)||($smoke >= sizeof($ws))||($drink < 0)||($drink >= sizeof($wd))||($education < 0)||($education >= sizeof($wed))||($agef < C_AGES)||($agef > C_AGEB)||($aget < C_AGES)||($aget > C_AGEB)) printm($w[1].'2',1);}

if (C_UNICM) {
$result = mysql_query("SELECT count(id) as count FROM ".C_MYSQL_MEMBERS." WHERE email = '".$email."' AND id != '".$_SESSION['m']."'");
$trows = mysql_fetch_array($result);
$count = $trows['count'];
if ($count != '0') printm($w[2]);}

if (((C_FIRSTNR)||(!empty($fname)))&&((strlen($fname) > C_FIRSTNB)||(strlen($fname) < C_FIRSTNS))) {
$tm=array(C_FIRSTNS,C_FIRSTNB);
printm(template($w[3],$tm));
}
if (((C_LASTNR)||(!empty($lname)))&&((strlen($lname) > C_LASTNB)||(strlen($lname) < C_LASTNS))) {
$tm=array(C_LASTNS,C_LASTNB);
printm(template($w[4],$tm));
}

// Final checks for most real values for profiles
if (((C_BIRTHR)||(!empty($month))||(!empty($day))||(!empty($year)))&&(($month == "0")||($day == "0")||($year == "0"))) printm($w[5]);
if ((C_GENDR)&&($gender == "0")) printm($w[7]);
if ((C_SGENDR)&&($sgender == "0")) printm($w[8]);
if ((C_PURPR)&&($purpose == "0")) printm($w[9]);
if ((C_CNTRR)&&($country == "0")) printm($w[10]);
if (c_email($email) == 0) printm($w[11]);
if (!empty($url)) {
   $url=checkurl($url);
   if (strlen($url) < 3) {$url="";printm($w[12],1);}
}
if ((!empty($icq))&&(!is_numeric($icq))) printm($w[13],1);
if ((!empty($aim))&&((strlen($aim) < 3)||(strlen($aim) > 16))) printm($w[14],1);
if ((C_PHONER)&&(empty($phone))) printm($w[15]);
if ((C_CITYR)&&(empty($city))) printm($w[16]);
if ((C_MARSR)&&($marstat == "0")) printm($w[17]);
if ((C_CHILDR)&&($child == "0")) printm($w[18]);
if ((C_HGHTR)&&($height == "0")) printm($w[19]);
if ((C_WGHTR)&&($weight == "0")) printm($w[20]);
if ((C_SHGHTR)&&($heightf == "0")) printm($w[21]);
if ((C_SHGHTR)&&($heightt == "0")) printm($w[21]);
if ((C_SWGHTR)&&($weightf == "0")) printm($w[22]);
if ((C_SWGHTR)&&($weightt == "0")) printm($w[22]);
if ((C_HAIRR)&&($hcolor == "0")) printm($w[23]);
if ((C_EYER)&&($ecolor == "0")) printm($w[24]);
if ((C_ETNR)&&($etnicity == "0")) printm($w[25]);
if ((C_RELR)&&($religion == "0")) printm($w[26]);
if ((C_SETNR)&&($setnicity == "0")) printm($w[27]);
if ((C_SRELR)&&($sreligion == "0")) printm($w[28]);
if ((C_SMOKER)&&($smoke == "0")) printm($w[29]);
if ((C_DRINKR)&&($drink == "0")) printm($w[30]);
if ((C_EDUCR)&&($education == "0")) printm($w[31]);
if ((C_JOBR)&&(empty($job))) printm($w[32]);
if ((C_SAGER)&&((empty($agef)||empty($aget)))) printm($w[33]);
if ((C_HDYFUR)&&($hdyfu == "0")) printm($w[34]);
if (C_HOBBR) {
   if (empty($hobby) || trim($hobby) == "") printm($w[35]); 
   if (strlen($hobby) > C_HOBBB) {
   $tm=array(C_HOBBB);
   printm(template($w[36],$tm));
   }
   $e = explode(" ",$hobby);
   for ($a = 0; $a < sizeof($e); $a++){
       $o = strlen($e[$a]);
       if ($o > C_HOBBW) {
           $tm=array(C_HOBBW);
           printm(template($w[37],$tm));
       }
   }
}

if (empty($descr) || trim($descr) == "") printm($w[38]);
if (strlen($descr) > C_DESCB) {
    $tm=array(C_DESCB);
    printm(template($w[39],$tm));
    } 
$e = explode(" ",$descr);
for ($a = 0; $a < sizeof($e); $a++){$o = strlen($e[$a]);
    if ($o > C_DESCW) {
    $tm=array(C_DESCW);
    printm(template($w[40],$tm));
    }
}

$cst = (C_UPDATE_ALLOW == '0') ? '2' : '7';

///////////
$mpic=array('','','','');
$cnt='0';$cntm='0';
for($k=1;$k<=3;$k++) {
$tmp='delpic'.$k;
if (isset($$tmp) && ($$tmp == 'on')) $cnt++;
}
if ($cnt != '0') {
    $tmp=mysql_query("SELECT pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE id='".$_SESSION['m']."' AND status = '7'");
    while($i=mysql_fetch_array($tmp)) {
         for($k=1;$k<=3;$k++) {
             $tmpm='pic'.$k;
             if($i[$tmpm] != '') $cntm++;
             }   
             if((C_PHOTOR)&&($cnt>=$cntm)) printm($w[257]);
         for($k=1;$k<=3;$k++) {
             $tmpm='pic'.$k;
             $tmpr='delpic'.$k;
             if ((isset($$tmpr)) && ($$tmpr == 'on')) { 
             if($i[$tmpm] != '') {
             if(is_file(C_PATH.'/members/uploads/'.$i[$tmpm])){ 
                unlink (C_PATH.'/members/uploads/'.$i[$tmpm]);
                }
             $mpic[$k]=", pic".$k."=''";
             }
             }
             }   
    }  
}
////////////////////////////
include_once C_PATH.'/classes/upload.class.php';
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
if(!$u->do_upload()) printm($u->getErrors());
$mpic[$k]=", pic".$k."='".$intpic.$u->getType()."'";
}
}

/////////////
$last_id = $_SESSION['m'];
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET fname='".$fname."',lname='".$lname."',birthday='".$year."-".$month."-".$day."',gender='".$gender."',
purposes='".$purpose."',country='".$country."',email='".$email."',url='".$url."',
icq='".$icq."',aim='".$aim."',phone='".$phone."',city='".$city."',marstat='".$marstat."',child='".$child."',height='".$height."',weight='".$weight."',hcolor='".$hcolor."',ecolor='".$ecolor."',etnicity='".$etnicity."',religion='".$religion."',smoke='".$smoke."',drink='".$drink."',education='".$education."',job='".$job."',hobby='".$hobby."',descr='".$descr."',sgender='".$sgender."',setnicity='".$setnicity."',sreligion='".$sreligion."',agef='".$agef."',aget='".$aget."',heightf='".$heightf."',heightt='".$heightt."',weightf='".$weightf."',weightt='".$weightt."',horo='".horo($month,$day)."',editdate=NOW(''),ip=INET_ATON('".ip()."'),status='".$cst."'".$mpic[1].$mpic[2].$mpic[3]." WHERE id='".$last_id."'") or die(mysql_error());

printm($w[258]);
break;
case "c":
######################
# Change profile - form
######################
$tmp=mysql_query("SELECT * FROM ".C_MYSQL_MEMBERS." WHERE id='".$_SESSION['m']."'");
while($i=mysql_fetch_array($tmp)){
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

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>

<form action="index.php" method="post" enctype="multipart/form-data" name=form OnSubmit="return formCheck(this)">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="u">
<center><span class=head><?=$w[99]?></span><br>
<center><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[51]?><?if(C_FIRSTNR) echo $w[0];?> [<?=C_FIRSTNS?>-<?=C_FIRSTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=fname maxlength="<?=C_FIRSTNB?>" value="<?=$i['fname']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[53]?><?if(C_LASTNR) echo $w[0];?> [<?=C_LASTNS?>-<?=C_LASTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=lname maxlength="<?=C_LASTNB?>" value="<?=$i['lname']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[56]?><?if(C_BIRTHR) echo $w[0];?></td>
<td>
<select name="month" class="minput">
<? 
$sqldata=str_replace(" ","-",$i['birthday']);
$val = explode('-', $sqldata);
$year=$val[0];$month=$val[1];$day=$val[2];
$p=1; while(isset($wmm[$p])) {
($month == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wmm[$p];
$p++;}
?>
</select>
<select name="day" class="sinput">
<? for($p=1;$p<32;$p++) {
($day == $p) ? $sel=" selected" : $sel="";
echo '<option'.$sel.'>'.$p;}
?>
</select>
<? $y=date("Y", time());$yfrom=$y-C_AGES;$yto=$y-C_AGEB; ?>
<select name="year" class="sinput">
<? for($p=$yto;$p<=$yfrom;$p++) {
($year == $p) ? $sel=" selected" : $sel="";
echo '<option'.$sel.'>'.$p;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[57]?><?if(C_GENDR) echo $w[0];?></td>
<td>
<select name="gender" class="input">
<? $p=0;while(isset($wg[$p])) {
($i['gender'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wg[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[58]?><?if(C_PURPR) echo $w[0];?></td>
<td>
<select name="purpose" class="input">
<? $p=0;while(isset($wp[$p])) {
($i['purposes'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wp[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[59]?><?if(C_CNTRR) echo $w[0];?></td>
<td>
<select name="country" class="input">
<? $p=0;asort($wcr);reset($wcr);
while (list ($p, $val) = each ($wcr)) {
($i['country'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$val;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[60]?><?=$w[0];?></td>
<td><input class=input type=text name=email value="<?=$i['email']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[61]?></td>
<td><input class=input type=text name=url value="<?=$i['url']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[62]?></td>
<td><input class=input type=text name=icq value="<?=$i['icq']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[63]?></td>
<td><input class=input type=text name=aim value="<?=$i['aim']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[64]?><?if(C_PHONER) echo $w[0];?></td>
<td><input class=input type=text name=phone value="<?=$i['phone']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[65]?><?if(C_CITYR) echo $w[0];?></td>
<td><input class=input type=text name=city value="<?=$i['city']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[66]?><?if(C_MARSR) echo $w[0];?></td>
<td>
<select name="marstat" class="input">
<? $p=0;while(isset($wm[$p])) {
($i['marstat'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wm[$p];$p++;}
?>
</select>
</td></tr>

<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[67]?><?if(C_CHILDR) echo $w[0];?></td>
<td>
<select name="child" class="input">
<? $p=0;while(isset($wc[$p])) {
($i['child'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wc[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[68]?><?if(C_HGHTR) echo $w[0];?></td>
<td>
<select name="height" class="input">
<? $p=0;while(isset($wh[$p])) {
($i['height'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wh[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[69]?><?if(C_WGHTR) echo $w[0];?></td>
<td>
<select name="weight" class="input">
<? $p=0;while(isset($ww[$p])) {
($i['weight'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$ww[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[70]?><?if(C_HAIRR) echo $w[0];?></td>
<td>
<select name="hcolor" class="input">
<? $p=0;while(isset($whc[$p])) {
($i['hcolor'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$whc[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[71]?><?if(C_EYER) echo $w[0];?></td>
<td>
<select name="ecolor" class="input">
<? $p=0;while(isset($we[$p])) {
($i['ecolor'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$we[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[72]?><?if(C_ETNR) echo $w[0];?></td>
<td>
<select name="etnicity" class="input">
<? $p=0;while(isset($wet[$p])) {
($i['etnicity'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wet[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[73]?><?if(C_RELR) echo $w[0];?></td>
<td>
<select name="religion" class="input">
<? $p=0;while(isset($wr[$p])) {
($i['religion'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wr[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[74]?><?if(C_SMOKER) echo $w[0];?></td>
<td>
<select name="smoke" class="input">
<? $p=0;while(isset($ws[$p])) {
($i['smoke'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$ws[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[75]?><?if(C_DRINKR) echo $w[0];?></td>
<td>
<select name="drink" class="input">
<? $p=0;while(isset($wd[$p])) {
($i['drink'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wd[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[76]?><?if(C_EDUCR) echo $w[0];?></td>
<td>
<select name="education" class="input">
<? $p=0;while(isset($wed[$p])) {
($i['education'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wed[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[77]?><?if(C_JOBR) echo $w[0];?></td>
<td><input class=input type=text name=job value="<?=$i['job']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[78]?><?if(C_HOBBR) echo $w[0];?></td>
<td><input class=input type=text name=hobby value="<?=$i['hobby']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td><?=$w[79]?><?=$w[0];?></td><td><textarea class=textarea cols=20 rows=8 name=descr><?=tb($i['descr'])?></textarea></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[80]?><?if(C_SGENDR) echo $w[0];?></td>
<td>
<select name="sgender" class="input">
<? $p=0;while(isset($wg[$p])) {
($i['sgender'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wg[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[81]?><?if(C_SETNR) echo $w[0];?></td>
<td>
<select name="setnicity" class="input">
<? $p=0;while(isset($wet[$p])) {
($i['setnicity'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wet[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[82]?><?if(C_SRELR) echo $w[0];?></td>
<td>
<select name="sreligion" class="input">
<? $p=0;while(isset($wr[$p])) {
($i['sreligion'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wr[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[83]?><?if(C_SAGER) echo $w[0];?></td>
<td><select name="agef" class="minput"> 
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if($p == $i['agef']) echo '<option selected>'.$p;
else echo '<option>'.$p;
}?>
</select>-<select name="aget" class="minput">
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if($p == $i['aget']) echo '<option selected>'.$p;
else echo '<option>'.$p;
}?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[84]?><?if(C_SHGHTR) echo $w[0];?></td>
<td><select name="heightf" class="minput"> 
<? $p=1;while(isset($wh[$p])) {
if($p == $i['heightf']) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;}?>
</select>-<select name="heightt" class="minput">
<? $p=1;while(isset($wh[$p])) {
if($p == $i['heightt']) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;}?>
</select>

</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[85]?><?if(C_SWGHTR) echo $w[0];?></td>
<td><select name="weightf" class="minput">
<? $p=1;while(isset($ww[$p])) {
if($p == $i['weightf']) echo '<option value="'.$p.'" selected>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}?>
</select>-<select name="weightt" class="minput">
<? $p=1;while(isset($ww[$p])) {
if($p == $i['weightt']) echo '<option value="'.$p.'" selected value=0>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}?>
</select>
</td></tr>

<?for($j=0;$j<=2;$j++) {$k=$j+1;$ind='pic'.$k;if(!empty($i[$ind])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[87]?> <?=$k?> <br>
<a href="<?=C_URL?>/members/uploads/<?=$i[$ind];?>" target="_blank"><img src="<?=C_URL?>/members/uploads/<?=$i[$ind];?>" border="<?=C_IMG_BRDR?>" width=150></a></td>
<td><input type="checkbox" name="delpic<?=$k?>"><?=$w[259]?></td></tr>
<?} else {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[87]?> <?=$k;$tm=array(C_MAXSZ);echo template($w[223],$tm);?> </td>
<td><input class=input type=file name="file<?=$j?>"></td></tr>
<?}}?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td colspan=2 align=right><input class=input type=submit value="<?=$w[99]?>" name="submit">
</td></tr></table></Td></Tr></table>
<br>
<?
}
break;
case "e":
######################
# Exit from login
######################
session_destroy();unset($s);unset($m);
printm($w[260]); 
break;
}
include_once '../templates/'.C_TEMP.'/footer.php';
?>