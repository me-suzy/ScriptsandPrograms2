<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               search.php                       #
# File purpose            search profiles                  #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
security(C_SEARCH,$w[152]);
include_once 'templates/'.C_TEMP.'/header.php';

if(!isset($a)) $a='';
if ($a == "s") {
if (!isset($step)) $step = 10;
if (!isset($from)) $from = 0;
$id=cb($id);
if($agef > $aget) printm($w[109]);
if(C_HACK1) {
if((!empty($id)&&!is_numeric($id))||!is_numeric($horo)||!is_numeric($step)||!is_numeric($from)||!is_numeric($gender)||!is_numeric($purpose)||!is_numeric($country)||!is_numeric($agef)||!is_numeric($aget)||!is_numeric($regin)||!is_numeric($sortby)) printm($w[1].'1',1);}

if(C_HACK2) { 
if(($regin < 0)||($regin >= sizeof($wrg))||($regin >= sizeof($wrgv))||($horo < 0)||($horo >= sizeof($whr))||($gender < 0)||($gender >= sizeof($wg))||($purpose < 0)||($purpose >= sizeof($wp))||($country < 0)||($country >= sizeof($wcr))) printm($w[1].'2',1);}

///////// Checking and creating for search
switch ($sortby) {
case "1":$msortby = " order by req DESC, editdate ASC";break;
case "2":$msortby = " order by req DESC, fname DESC";break;
case "3":$msortby = " order by req DESC, fname ASC";break;
case "4":$msortby = " order by req DESC, birthday DESC";break;
case "5":$msortby = " order by req DESC, birthday ASC";break;
case "6":$msortby = " order by req DESC, height DESC";break;
case "7":$msortby = " order by req DESC, height ASC";break;
case "8":$msortby = " order by req DESC, weight DESC";break;
case "9":$msortby = " order by req DESC, weight ASC";break;
default:$msortby = " order by req DESC, editdate DESC";break;
}

// Important /////////////
$id != "" ? $mid = " id = '".$id."' AND status >= '7'" : $mid = " status >= '7'";
//////////////////////////
!empty($horo) ? $mhoro=" AND horo = '".$horo."'" : $mhoro = "";
$magef=" AND birthday <= DATE_SUB(NOW(), INTERVAL ".$agef." YEAR)";
$maget=" AND birthday >= DATE_SUB(NOW(), INTERVAL ".($aget+1)." YEAR)";
!empty($gender) ? $mgender=" AND gender = '".$gender."'" : $mgender = "";
!empty($purpose) ? $mpurpose=" AND purposes = '".$purpose."'" : $mpurpose = "";
!empty($country) ? $mcountry=" AND country = '".$country."'" : $mcountry = "";
!empty($regin) ? $mregin=" AND regdate > DATE_SUB(NOW(), INTERVAL ".$wrgv[$regin]." DAY)" : $mregin = "";

$sql="SELECT * FROM ".C_MYSQL_MEMBERS." WHERE ".$mid.$mhoro.$magef.$maget.$mgender.$mpurpose.$mcountry.$mregin.$msortby." limit ".$from.",".$step;
$tsql = "SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE ".$mid.$mhoro.$magef.$maget.$mgender.$mpurpose.$mcountry.$mregin;
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
$str.="<Tr bgcolor=".$color." align=center><td><a href=view.php?l=".$l."&id=".$i['id'].">".$name."</a></td><td>".$wg[$i['gender']]." ".$wp[$i['purposes']]."</td><td>".abs($age)."</td><td>".$wcr[$i['country']]."</td><td>".$i['city']."</td><td>".mysql2data($i['editdate'])."</td><td>".mysql2data($i['regdate'])."</td><td>".$picav."</td></tr>";
}

$param='l='.$l.'&a=s&id='.$id.'&horo='.$horo.'&agef='.$agef.'&aget='.$aget.'&gender='.$gender.'&purpose='.$purpose.'&country='.$country.'&regin='.$regin.'&sortby='.$sortby;
$colspan=8;
$str.=pages($from,$step,$count,$param,$colspan);
$str.="</table></td></tr></table>";
?>
<br>
<span class=head><?=search_results($from,$step,$count);?></span>
<br><br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_BWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_BWIDTH?>" class=mes>
<Tr align="center" bgcolor="<?=COLORH?>"><Td><?=$w[118]?></td>
<Td><?=$w[119]?></td><Td><?=$w[120]?></td><Td><?=$w[121]?></td><Td><?=$w[122]?></td><Td><?=$w[123]?></td><Td><?=$w[124]?></td><Td><?=$w[87]?></td></Tr>
<?=$str;?>
<?
} else {
?>
<form action="search.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="s">
<center><span class=head><?=$w[91]?></span>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[126]?></td>
<td><input type="text" name="id" class="minput"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[129]?></td>
<td><select name="horo" class="input">
<? $p=0; while(isset($whr[$p])) {echo '<option value="'.$p.'">'.$whr[$p];$p++;}?>
</select></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[120]?></td>
<td><select name="agef" class="sinput"> 
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if($p == C_AGES) echo '<option selected>'.$p;
else echo '<option>'.$p;
}?>
</select> - <select name="aget" class="sinput">
<? for($p=C_AGES;$p<=C_AGEB;$p++){
if($p == C_AGEB) echo '<option selected>'.$p;
else echo '<option>'.$p;
}?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[132]?></td>
<td>
<select name="gender" class="input">
<? $p=0;while(isset($wg[$p])) {echo '<option value="'.$p.'">'.$wg[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[133]?></td>
<td>
<select name="purpose" class="input">
<? $p=0;while(isset($wp[$p])) {echo '<option value="'.$p.'">'.$wp[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[121]?></td>
<td>
<select name="country" class="input">
<? $p=0;asort($wcr);reset($wcr);
while (list ($p, $val) = each ($wcr)) {
echo '<option value="'.$p.'">'.$val;
}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[148]?> </td>
<td>
<select name="regin" class="input">
<? $p=0;while(isset($wrg[$p])) {echo '<option value="'.$p.'">'.$wrg[$p];$p++;}
?>
</select>
</td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[149]?> </td>
<td>
<select name="sortby" class="input">
<? $p=0;while(isset($wsb[$p])) {echo '<option value="'.$p.'">'.$wsb[$p];$p++;}
?>
</select>
</td></tr>
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
<table width=100%><tr><td align=right><input type="submit" value="<?=$w[91]?>" class=button></td></tr></table>
</td></tr></table></td></tr></table></form>
<? } include_once 'templates/'.C_TEMP.'/footer.php'; ?>