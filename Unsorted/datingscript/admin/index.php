<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               admin/index.php                  #
# File purpose            Admin area                       #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once '../include/config.inc.php';
include_once '../include/options.inc.php';
include_once '../languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'.php';
include_once '../languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'_.php';
include_once '../languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'a.php';
include_once '../include/functions.inc.php';
include_once '../templates/'.C_TEMP.'/config.php';
admin_security($x[6]);
include_once '../templates/'.C_TEMP.'/sheader.php';

if(!isset($ds)) $ds='0';
if($ds != '1') {
   function menu($do,$word) {
   global $l;                            
   $mitem = '<a href="'.C_URL.'/admin/index.php?do='.$do.'&'.s().'" class=desc>['.$word.']</a> ';
   echo $mitem;}                               

?>
<center><span class=head><?=$x[2]?></span><br>
<table width="98%" align=center><tr><td width="98%" align=center valign=top>
<?menu('mp',$x[7]);?>
<?menu('ro',$x[9]);?>
<?menu('um',$x[10]);?>
<?menu('ex&ds=1',$x[13]);?>
</Td></Tr></Table><br>
<?
}
if(!isset($do)) $do='';
switch($do) {
############################################
# Exit - Begin
############################################
case 'ex':
session_destroy();
unset($adminlogin);
unset($adminpass);
unset($adminip);
sprintm($x[14]);
break;
############################################
# Exit - End
############################################
############################################
# Profile update
############################################
case 'uu':
$ip=cb($ip);$fname=cb($fname);$lname=cb($lname);$email=cb($email);$url=cb($url);$icq=cb($icq);$aim=cb($aim);$phone=cb($phone);$city=cb($city);$job=cb($job);$hobby=cb($hobby);$descr=cb($descr);
// Check for numeric select
if(C_HACK1) {
if(!is_numeric($status)||!is_numeric($id)||!is_numeric($req)||!is_numeric($day)||!is_numeric($month)||!is_numeric($year)||!is_numeric($gender)||!is_numeric($purpose)||!is_numeric($country)||!is_numeric($marstat)||!is_numeric($child)||!is_numeric($height)||!is_numeric($weight)||!is_numeric($hcolor)||!is_numeric($ecolor)||!is_numeric($heightf)||!is_numeric($heightt)||!is_numeric($weightf)||!is_numeric($weightt)||!is_numeric($etnicity)||!is_numeric($setnicity)||!is_numeric($religion)||!is_numeric($sreligion)||!is_numeric($smoke)||!is_numeric($drink)||!is_numeric($education)||!is_numeric($sgender)||!is_numeric($agef)||!is_numeric($aget)) printm($w[1].'1',1);}

// Check for real data of arrays!
if(C_HACK2) { 
if(($day < 0)||($day > 31)||($month < 0)||($month > 12)||($gender < 0)||($gender >= sizeof($wg))||($sgender < 0)||($sgender >= sizeof($wg))||($purpose < 0)||($purpose >= sizeof($wp))||($country < 0)||($country >= sizeof($wcr))||($marstat < 0)||($marstat >= sizeof($wm))||($child < 0)||($child >= sizeof($wc))||($height < 0)||($height >= sizeof($wh))||($weight < 0)||($weight >= sizeof($ww))||($heightf < 0)||($heightf >= sizeof($wh))||($weightf < 0)||($weightf >= sizeof($ww))||($heightt < 0)||($heightt >= sizeof($wh))||($weightt < 0)||($weightt >= sizeof($ww))||($hcolor < 0)||($hcolor >= sizeof($whc))||($ecolor < 0)||($ecolor >= sizeof($we))||($etnicity < 0)||($etnicity >= sizeof($wet))||($setnicity < 0)||($setnicity >= sizeof($wet))||($religion < 0)||($religion >= sizeof($wr))||($sreligion < 0)||($sreligion >= sizeof($wr))||($smoke < 0)||($smoke >= sizeof($ws))||($drink < 0)||($drink >= sizeof($wd))||($education < 0)||($education >= sizeof($wed))||($agef < C_AGES)||($agef > C_AGEB)||($aget < C_AGES)||($aget > C_AGEB)) printm($w[1].'2',1);}

if (C_UNICM) {
$result = mysql_query("SELECT count(id) as count FROM ".C_MYSQL_MEMBERS." WHERE email = '".$email."' AND id != '".$id."'");
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
if ((strlen($pass) > C_PASSB)||(strlen($pass) < C_PASSS)) { 
$tm=array(C_PASSS,C_PASSB);
printm(template($w[6],$tm));
}
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


///////////
$mpic=array('','','','');
$cnt='0';$cntm='0';
for($k=1;$k<=3;$k++) {
$tmp='delpic'.$k;
if (isset($$tmp)&&($$tmp == 'on')) $cnt++;
}
if ($cnt != '0') {
    $tmp=mysql_query("SELECT pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'");
    while($i=mysql_fetch_array($tmp)) {
         for($k=1;$k<=3;$k++) {
             $tmpm='pic'.$k;
             if($i[$tmpm] != '') $cntm++;
             }   
             if((C_PHOTOR)&&($cnt>=$cntm)) printm($w[257]);
         for($k=1;$k<=3;$k++) {
             $tmpm='pic'.$k;
             $tmpr='delpic'.$k;
             if ($$tmpr == 'on') { 
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
   {umask(0);mkdir ("members/uploads/".$dir, 0777);}
   } else $dir=$slash="";
$fb=date("dHis",$time);$fe=rand(0,999);$fn =$fb."-".$fe;
$intpic = $dir.$slash.$fn.'.';
$u = new Upload($file,C_MAXSZ,C_MAXWD,C_MAXHG,C_PATH.'/members/uploads/'.$intpic);
if(!$u->do_upload()) printm($u->getErrors());
$mpic[$k]=", pic".$k."='".$intpic.$u->getType()."'";
}
}

/////////////
$last_id = $id;
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET fname='".$fname."',lname='".$lname."',birthday='".$year."-".$month."-".$day."',gender='".$gender."',
purposes='".$purpose."',country='".$country."',email='".$email."',url='".$url."',
icq='".$icq."',aim='".$aim."',phone='".$phone."',city='".$city."',marstat='".$marstat."',child='".$child."',height='".$height."',weight='".$weight."',hcolor='".$hcolor."',ecolor='".$ecolor."',etnicity='".$etnicity."',religion='".$religion."',smoke='".$smoke."',drink='".$drink."',education='".$education."',job='".$job."',hobby='".$hobby."',descr='".$descr."',sgender='".$sgender."',setnicity='".$setnicity."',sreligion='".$sreligion."',agef='".$agef."',aget='".$aget."',heightf='".$heightf."',heightt='".$heightt."',weightf='".$weightf."',weightt='".$weightt."',horo='".horo($month,$day)."',editdate=NOW(''),ip=INET_ATON('".ip()."'),ip=INET_ATON('".$ip."'),req='".$req."',status='".$status."'".$mpic[1].$mpic[2].$mpic[3]." WHERE id='".$last_id."'") or die(mysql_error());

printm($x[41]);

break;
############################################
# Profile update - End
############################################
############################################
# Status help - Begin
############################################
case 'sh':
sprintm('<p align=left>'.$x[67]);
break;
############################################
# Status help - End
############################################
############################################
# Repair&Optimize - Begin
############################################
case 'ro':
mysql_query("REPAIR TABLE ".C_MYSQL_MEMBERS) or die(mysql_error());
mysql_query("REPAIR TABLE ".C_MYSQL_TEMP) or die(mysql_error());
mysql_query("REPAIR TABLE ".C_MYSQL_ONLINE_USERS) or die(mysql_error());
mysql_query("REPAIR TABLE ".C_MYSQL_ONLINE_QUESTS) or die(mysql_error());
mysql_query("OPTIMIZE TABLE ".C_MYSQL_MEMBERS) or die(mysql_error());
mysql_query("OPTIMIZE TABLE ".C_MYSQL_TEMP) or die(mysql_error());
mysql_query("OPTIMIZE TABLE ".C_MYSQL_ONLINE_USERS) or die(mysql_error());
mysql_query("OPTIMIZE TABLE ".C_MYSQL_ONLINE_QUESTS) or die(mysql_error());
sprintm($x[68]);
break;
############################################
# Repair&Optimize - End
############################################
############################################
# Delete profile - Begin
############################################
case 'dp':
if(isset($id) && is_numeric($id)) {
   $tmp=mysql_query("SELECT pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'");
    while($i=mysql_fetch_array($tmp)) {
         for($k=1;$k<=3;$k++) $tmpm='pic'.$k;   
         if(($i[$tmpm] != '') && (is_file(C_PATH.'/members/uploads/'.$i[$tmpm]))) unlink (C_PATH.'/members/uploads/'.$i[$tmpm]);
    }
}
mysql_query("DELETE FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'") or die(mysql_error());
mysql_query("DELETE FROM ".C_MYSQL_TEMP." WHERE id='".$id."'") or die(mysql_error());
$tm=array($id);
sprintm(template($x[69],$tm));
break;
############################################
# Delete profile - End
############################################
############################################
# Users allow - small window - Begin
############################################
case 'al':
if(isset($id) && is_numeric($id)) {
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET status='7' WHERE id='".$id."'") or die(mysql_error());
$tm=array($id);
sprintm(template($x[71],$tm),2);
}
break;
############################################
# Users allow - small window - End
############################################
############################################
# User edit form - Begin
############################################
case 'ue':
if(isset($id) && is_numeric($id)) {
######################
# Change profile - form
######################
$tmp=mysql_query("SELECT * FROM ".C_MYSQL_MEMBERS." WHERE id='".$id."'");
while($i=mysql_fetch_array($tmp)){
?>
<script language="JavaScript">
<!--
function formCheck(form) {
<?if (C_FIRSTNR) {?>
if (form.fname.value == "")
{alert("<?=$w[3]?>");return false;}
<?}?>
<?if (C_LASTNR) {?>
if (form.lname.value == "")
{alert("<?=$w[4]?>");return false;}
<?}?>
if (form.email.value == "")
{alert("<?=$w[11]?>");return false;}

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>

<form action="index.php?do=uu&<?=s()?>" method="post" enctype="multipart/form-data" name=form OnSubmit="return formCheck(this)">
<center><span class=head><?=$x[99]?></span><br>
<center><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[126]?></td>
<td><input class=input type=hidden name=id value="<?=$i['id']?>"><?=$i['id']?></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[208]?><?if(C_FIRSTNR) echo $w[0];?> [<?=C_FIRSTNS?>-<?=C_FIRSTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=fname maxlength="<?=C_FIRSTNB?>" value="<?=$i['fname']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[209]?><?if(C_LASTNR) echo $w[0];?> [<?=C_LASTNS?>-<?=C_LASTNB?> <?=$w[52]?>]</td>
<td><input class=input type=text name=lname maxlength="<?=C_LASTNB?>" value="<?=$i['lname']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[54]?></td>
<td><input class=input type=text name=pass maxlength="<?=C_PASSB?>" value="<?=$i['password']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$x[72]?></td>
<td><input class=input type=text name=ip value="<?=int2ip($i['ip'])?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$x[73]?></td>
<td><input class=input type=text name=req value="<?=$i['req']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[210]?><?if(C_BIRTHR) echo $w[0];?></td>
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
<?=$w[132]?><?if(C_GENDR) echo $w[0];?></td>
<td>
<select name="gender" class="input">
<? $p=0;while(isset($wg[$p])) {
($i['gender'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wg[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[133]?><?if(C_PURPR) echo $w[0];?></td>
<td>
<select name="purpose" class="input">
<? $p=0;while(isset($wp[$p])) {
($i['purposes'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wp[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[121]?><?if(C_CNTRR) echo $w[0];?></td>
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
<?=$w[211]?><?=$w[0];?></td>
<td><input class=input type=text name=email value="<?=$i['email']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[144]?></td>
<td><input class=input type=text name=url value="<?=$i['url']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[145]?></td>
<td><input class=input type=text name=icq value="<?=$i['icq']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[146]?></td>
<td><input class=input type=text name=aim value="<?=$i['aim']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[147]?><?if(C_PHONER) echo $w[0];?></td>
<td><input class=input type=text name=phone value="<?=$i['phone']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[122]?><?if(C_CITYR) echo $w[0];?></td>
<td><input class=input type=text name=city value="<?=$i['city']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[134]?><?if(C_MARSR) echo $w[0];?></td>
<td>
<select name="marstat" class="input">
<? $p=0;while(isset($wm[$p])) {
($i['marstat'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wm[$p];$p++;}
?>
</select>
</td></tr>

<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[135]?><?if(C_CHILDR) echo $w[0];?></td>
<td>
<select name="child" class="input">
<? $p=0;while(isset($wc[$p])) {
($i['child'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wc[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[130]?><?if(C_HGHTR) echo $w[0];?></td>
<td>
<select name="height" class="input">
<? $p=0;while(isset($wh[$p])) {
($i['height'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wh[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[131]?><?if(C_WGHTR) echo $w[0];?></td>
<td>
<select name="weight" class="input">
<? $p=0;while(isset($ww[$p])) {
($i['weight'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$ww[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[136]?><?if(C_HAIRR) echo $w[0];?></td>
<td>
<select name="hcolor" class="input">
<? $p=0;while(isset($whc[$p])) {
($i['hcolor'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$whc[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[137]?><?if(C_EYER) echo $w[0];?></td>
<td>
<select name="ecolor" class="input">
<? $p=0;while(isset($we[$p])) {
($i['ecolor'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$we[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[138]?><?if(C_ETNR) echo $w[0];?></td>
<td>
<select name="etnicity" class="input">
<? $p=0;while(isset($wet[$p])) {
($i['etnicity'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wet[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[139]?><?if(C_RELR) echo $w[0];?></td>
<td>
<select name="religion" class="input">
<? $p=0;while(isset($wr[$p])) {
($i['religion'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wr[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[140]?><?if(C_SMOKER) echo $w[0];?></td>
<td>
<select name="smoke" class="input">
<? $p=0;while(isset($ws[$p])) {
($i['smoke'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$ws[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[141]?><?if(C_DRINKR) echo $w[0];?></td>
<td>
<select name="drink" class="input">
<? $p=0;while(isset($wd[$p])) {
($i['drink'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wd[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[142]?><?if(C_EDUCR) echo $w[0];?></td>
<td>
<select name="education" class="input">
<? $p=0;while(isset($wed[$p])) {
($i['education'] == $p) ? $sel=" selected" : $sel="";
echo '<option value="'.$p.'"'.$sel.'>'.$wed[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[213]?><?if(C_JOBR) echo $w[0];?></td>
<td><input class=input type=text name=job value="<?=$i['job']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[214]?><?if(C_HOBBR) echo $w[0];?></td>
<td><input class=input type=text name=hobby value="<?=$i['hobby']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td><?=$w[215]?><?=$w[0];?></td><td><textarea class=textarea cols=20 rows=8 name=descr><?=tb($i['descr'])?></textarea></td></tr>
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
<? $p=0;while(isset($wh[$p])) {
if($p == $i['heightf']) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;}?>
</select>-<select name="heightt" class="minput">
<? $p=0;while(isset($wh[$p])) {
if($p == $i['heightt']) echo '<option value="'.$p.'" selected>'.$wh[$p];
else echo '<option value="'.$p.'">'.$wh[$p];
$p++;}?>
</select>

</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[85]?><?if(C_SWGHTR) echo $w[0];?></td>
<td><select name="weightf" class="minput">
<? $p=0;while(isset($ww[$p])) {
if($p == $i['weightf']) echo '<option value="'.$p.'" selected>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}?>
</select>-<select name="weightt" class="minput">
<? $p=0;while(isset($ww[$p])) {
if($p == $i['weightt']) echo '<option value="'.$p.'" selected value=0>'.$ww[$p];
else echo '<option value="'.$p.'">'.$ww[$p];
$p++;}?>
</select>
</td></tr>

<?for($j=0;$j<=2;$j++) {$k=$j+1;$ind='pic'.$k;if(!empty($i[$ind])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
Photo <?=$k?> <br>
<a href="<?=C_URL?>/members/uploads/<?=$i[$ind];?>" target="_blank"><img src="<?=C_URL?>/members/uploads/<?=$i[$ind];?>" border="<?=C_IMG_BRDR?>" width=150></a></td>
<td><input type="checkbox" name="delpic<?=$k?>"> - Delete picture</td></tr>
<?} else {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[87]?> <?=$k?> </td>
<td><input class=input type=file name="file<?=$j?>"></td></tr>
<?}}?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<a href="<?="javascript:open_win('index.php?ds=1&do=sh&".s()."','help');"?>" class=desc><?=$x[74]?></a></td>
<td><input class=input type=text name=status value="<?=$i['status']?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><td colspan=2 align=right><input class=input type=submit value="<?=$w[99]?>" name="submit">
</td></tr></table></Td></Tr></table>
<br>
<?
}
}
break;
############################################
# User edit form - End
############################################
############################################
# User management - Begin
############################################
case 'um':
if (!isset($a)) $a='';
if ($a == "s") {
if(!isset($userstat)) $userstat=0;
$horo=$gender=$purpose=$country=$agef=$regin=$sortby=0;$aget=255;
if (!isset($step)) $step = 10;
if (!isset($from)) $from = 0;
$id=cb($id);
if($agef > $aget) printm($w[109]);
if(C_HACK1) {
if((!empty($id)&&!is_numeric($id))||!is_numeric($horo)||!is_numeric($step)||!is_numeric($from)||!is_numeric($gender)||!is_numeric($purpose)||!is_numeric($country)||!is_numeric($agef)||!is_numeric($aget)||!is_numeric($regin)||!is_numeric($sortby)||!is_numeric($userstat)) printm($w[1].'1',1);}

if(C_HACK2) { 
if(($regin < 0)||($regin >= sizeof($wrg))||($regin >= sizeof($wrgv))||($horo < 0)||($horo >= sizeof($whr))||($gender < 0)||($gender >= sizeof($wg))||($purpose < 0)||($purpose >= sizeof($wp))||($country < 0)||($country >= sizeof($wcr))||($userstat < 0)||($userstat >= sizeof($wst))) printm($w[1].'2',1);}

///////// Checking and creating for search
switch ($sortby) {
case "1":$msortby = " order by req DESC, editdate ASC";break;
case "2":$msortby = " order by req DESC, fname DESC";break;
case "3":$msortby = " order by req DESC, fname ASC";break;
case "4":$msortby = " order by req DESC, year DESC";break;
case "5":$msortby = " order by req DESC, year ASC";break;
case "6":$msortby = " order by req DESC, height DESC";break;
case "7":$msortby = " order by req DESC, height ASC";break;
case "8":$msortby = " order by req DESC, weight DESC";break;
case "9":$msortby = " order by req DESC, weight ASC";break;
default:$msortby = " order by req DESC, editdate DESC";break;
}

// Important /////////////
$id != "" ? $mid = " id = '".$id."'" : $mid = " id != '0'";
//////////////////////////
!empty($horo) ? $mhoro=" AND horo = '".$horo."'" : $mhoro = "";
$magef=" AND birthday <= DATE_SUB(NOW(), INTERVAL ".$agef." YEAR)";
$maget=" AND birthday >= DATE_SUB(NOW(), INTERVAL ".($aget+1)." YEAR)";
!empty($gender) ? $mgender=" AND gender = '".$gender."'" : $mgender = "";
!empty($purpose) ? $mpurpose=" AND purposes = '".$purpose."'" : $mpurpose = "";
!empty($country) ? $mcountry=" AND country = '".$country."'" : $mcountry = "";

///////// Checking and creating for search
switch ($userstat) {
case "1":$muserstat = " AND status = '0'";break;
case "2":$muserstat = " AND status = '1'";break;
case "3":$muserstat = " AND status = '2'";break;
case "4":$muserstat = " AND (status = '3' OR status = '8')";break;
case "5":$muserstat = " AND status >= '7'";break;
default:$muserstat = "";break;
}

!empty($regin) ? $mregin=" AND regdate > DATE_SUB(NOW(), INTERVAL ".$wrgv[$regin]." DAY)" : $mregin = "";

$sql="SELECT * FROM ".C_MYSQL_MEMBERS." WHERE ".$mid.$mhoro.$magef.$maget.$mgender.$mpurpose.$mcountry.$muserstat.$mregin.$msortby." limit ".$from.",".$step;
$tsql = "SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE ".$mid.$mhoro.$magef.$maget.$mgender.$mpurpose.$mcountry.$muserstat.$mregin;
$result = mysql_query($sql) or die(mysql_error());
$tquery = mysql_query($tsql) or die(mysql_error());
$trows = mysql_fetch_array($tquery);
$count = $trows['total'];
if($count == "0") printm($w[110]);
$str=$color='';
while ($i = mysql_fetch_array($result)) {
$picav = (($i['pic1'] == '')&&($i['pic2'] == '')&&($i['pic3'] == '')) ? $w[111] : $w[112];
$color = ($color == COLOR4) ? COLOR3 : COLOR4;
$age=mysql2data($i['birthday'],1);
$name = (trim($i['fname']) == '') ? $i['id'] : $i['fname']; 
$str.="<Tr bgcolor=".$color." align=center class=desc><td><a href=../view.php?id=".$i['id']." class=desc target='_blank'>".$name."</a></td><td>".$wg[$i['gender']]." ".$wp[$i['purposes']]."</td><td>".abs($age)."</td><td>".$wcr[$i['country']]."</td><td>".mysql2data($i['regdate'])."</td><td>".$picav."</td><td><a href=\"javascript:open_win('index.php?id=".$i['id']."&ds=1&do=al&".s()."','allow');\" class=desc>".$x[75]."</a></td><td><a href=\"index.php?id=".$i['id']."&do=ue&".s()."\" class=desc>".$x[60]."</a></td><td><a href=\"javascript:open_win('index.php?id=".$i['id']."&ds=1&do=dp&".s()."','delete');\" class=desc>".$x[66]."</td></tr>";
}

$param='l='.$l.'&a=s&do=um&id='.$id.'&horo='.$horo.'&agef='.$agef.'&aget='.$aget.'&gender='.$gender.'&purpose='.$purpose.'&country='.$country.'&userstat='.$userstat.'&regin='.$regin.'&sortby='.$sortby;
$colspan=9;
$str.=pages($from,$step,$count,$param,$colspan);
$str.="</table></td></tr></table>";
?>
<br>
<span class=head><?=search_results($from,$step,$count);?></span>
<br><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_BWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_BWIDTH?>" class=mes>
<Tr align="center" bgcolor="<?=COLORH?>"><Td><?=$w[118]?></td>
<Td><?=$w[119]?></td><Td><?=$w[120]?></td><Td><?=$w[121]?></td><Td><?=$w[124]?></td><Td><?=$w[87]?></td><Td><?=$x[75]?></td><Td><?=$x[60]?></td><Td><?=$x[66]?></td></Tr>
<?=$str;?>
<?
} else {
?>
<form action="?<?=s()?>" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="s">
<input type="hidden" name="do" value="um">
<center><span class=head><?=$x[76]?></span>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[126]?></td>
<td><input type="text" name="id" class="minput"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[150]?> </td>
<td>
<select name="step" class="sinput">
<? for($p=10;$p<=50;$p+=10) {
echo '<option>'.$p;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td colspan=2>
<input type="submit" value="<?=$w[91]?>" class=button></td></tr>
</table></td></tr></table></form>
<?php
}
break;
############################################
# User management - End
############################################
############################################
# Main page - Begin
############################################
default:
?>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_SWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_SWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td width=220>
<?=$x[77]?>
</td>
<td>
<?
$tmp=mysql_query("SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE status >= '7'");
$rows=mysql_fetch_array($tmp);
$total=$rows['total'];
echo $total;
?>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td width=220>
<a href="index.php?a=s&step=<?=C_APAGE?>&id=&horo=0&agef=0&aget=255&gender=0&purpose=0&country=0&regin=0&sortby=1&do=um&userstat=2&".s()><?=$x[78]?></a>:
</td>
<td>
<?
$tmp=mysql_query("SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE status = '1'");
$rows=mysql_fetch_array($tmp);
$total=$rows['total'];
echo $total;
?>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td width=220>
<a href="index.php?a=s&step=<?=C_APAGE?>&id=&horo=0&agef=0&aget=255&gender=0&purpose=0&country=0&regin=0&sortby=1&do=um&userstat=3&".s()><?=$x[79]?></a> :
</td>
<td>
<?
$tmp=mysql_query("SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE status = '2'");
$rows=mysql_fetch_array($tmp);
$total=$rows['total'];
echo $total;
?>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td width=220>
<a href="index.php?a=s&step=<?=C_APAGE?>&id=&horo=0&agef=0&aget=255&gender=0&purpose=0&country=0&regin=0&sortby=1&do=um&userstat=4&".s()><?=$x[80]?></a> :
</td>
<td>
<?
$tmp=mysql_query("SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE status = '3' or status = '8'");
$rows=mysql_fetch_array($tmp);
$total=$rows['total'];
echo $total;
?>
</td></tr></table></Td></Tr></table><br>
<IFRAME SRC="http://azdg.com/news.php?id=1" width="<?=C_SWIDTH?>" height="250" frameborder="0" border="0" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto"></IFRAME>
<?
break;
############################################
# Main page - End
############################################
}
include_once '../templates/'.C_TEMP.'/sfooter.php';

?>