<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               view.php                         #
# File purpose            View profiles                    #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
security(C_VIEW,$w[152]);
include_once 'templates/'.C_TEMP.'/header.php';

if (isset($id) || $id != "" || is_numeric($id)) {
$result = mysql_query("SELECT * FROM ".C_MYSQL_MEMBERS." WHERE id = '".$id."' AND status = '7'");
$count=mysql_num_rows($result);
if($count == '0') printm($w[205]);
while ($i = mysql_fetch_array($result)) {
?>
<br>
<span class=dat><?=$w[207]?>[<?=$id?>]</span>
<br><br>
<table CellSpacing="0" CellPadding="0" width="<?=C_BWIDTH?>" border=0>
<Tr>
<Td valign="top">

<?for ($p=1;$p <= 3;$p++) {$pic='pic'.$p; if ($i[$pic] != "") {?>
<a href="<?=C_URL?>/members/uploads/<?=$i[$pic];?>" target="_blank"><img src="<?=C_URL?>/members/uploads/<?=$i[$pic];?>" border="<?=C_IMG_BRDR?>" width=150></a>
<?} else echo ' &nbsp; '; }?>

</td><td width="<?=C_WIDTH?>" valign="top" align="<?=C_ALIGN?>">

<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTH?>" class=mes>
<?if(!empty($i['fname'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[208]?>
</td><td><?=$i['fname'];?></td></tr>
<?}?>
<?if(!empty($i['lname'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[209]?>
</td><td><?=$i['lname'];?></td></tr>
<?}?>
<?if(!empty($i['birthday'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[210]?>
</td><td><?=$i['birthday'];?></td></tr>
<?}?>
<?if(C_HOROSH) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[129]?>
</td><td><?=$whr[$i['horo']];?></td></tr>
<?}?>
<?if(!empty($i['gender'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[132]?>
</td><td><?=$wg[$i['gender']]?></td></tr>
<?}?>
<?if(!empty($i['purposes'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[133]?>
</td><td><?=$wp[$i['purposes']]?></td></tr>
<?}?>
<?if(!empty($i['country'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[121]?>
</td><td><?=$wcr[$i['country']];?></td></tr>
<?}?>
<?if(!empty($i['email'])&&(C_EMAILSH)) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[211]?>
</td><td><?=crm($i['email'],$w[212]);?></td></tr>
<?}?>
<?if(!empty($i['url'])&&(C_URLSH)) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[144]?>
</td><td><a href="<?=$i['url'];?>" target="_blank"><?=$i['url'];?></a></td></tr>
<?}?>
<?if(!empty($i['icq'])&&(C_ICQSH)) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[145]?>
</td><td><?=$i['icq'];?></td></tr>
<?}?>
<?if(!empty($i['aim'])&&(C_AIMSH)) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[146]?>
</td><td><?=$i['aim'];?></td></tr>
<?}?>
<?if(!empty($i['phone'])&&(C_PHONESH)) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[147]?>
</td><td><?=$i['phone'];?></td></tr>
<?}?>
<?if(!empty($i['city'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[122]?>
</td><td><?=$i['city'];?></td></tr>
<?}?>
<?if(!empty($i['marstat'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[134]?>
</td><td><?=$wm[$i['marstat']];?></td></tr>
<?}?>
<?if(!empty($i['child'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[135]?>
</td><td><?=$wc[$i['child']];?></td></tr>
<?}?>
<?if(!empty($i['height'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[130]?>
</td><td><?=$wh[$i['height']];?></td></tr>
<?}?>
<?if(!empty($i['weight'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[131]?>
</td><td><?=$ww[$i['weight']];?></td></tr>
<?}?>
<?if(!empty($i['hcolor'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[136]?>
</td><td><?=$whc[$i['hcolor']];?></td></tr>
<?}?>
<?if(!empty($i['ecolor'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[137]?>
</td><td><?=$we[$i['ecolor']];?></td></tr>
<?}?>
<?if(!empty($i['etnicity'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[138]?>
</td><td><?=$wet[$i['etnicity']];?></td></tr>
<?}?>
<?if(!empty($i['religion'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[139]?>
</td><td><?=$wr[$i['religion']];?></td></tr>
<?}?>
<?if(!empty($i['smoke'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[140]?>
</td><td><?=$ws[$i['smoke']];?></td></tr>
<?}?>
<?if(!empty($i['drink'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[141]?>
</td><td><?=$wd[$i['drink']];?></td></tr>
<?}?>
<?if(!empty($i['education'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[142]?>
</td><td><?=$wed[$i['education']];?></td></tr>
<?}?>
<?if(!empty($i['job'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[213]?>
</td><td><?=$i['job'];?></td></tr>
<?}?>
<?if(!empty($i['hobby'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[214]?>
</td><td><?=$i['hobby'];?></td></tr>
<?}?>
<?if(!empty($i['descr'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[215]?>
</td><td><?=$i['descr'];?></td></tr>
<?}?>
<?if(!empty($i['sgender'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[80]?>
</td><td><?=$wg[$i['sgender']];?></td></tr>
<?}?>
<?if(!empty($i['setnicity'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[81]?>
</td><td><?=$wet[$i['setnicity']];?></td></tr>
<?}?>
<?if(!empty($i['sreligion'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[82]?>
</td><td><?=$wr[$i['sreligion']];?></td></tr>
<?}?>
<?if(!empty($i['agef'])||!empty($i['aget'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[83]?>
</td><td><?if(!empty($i['agef'])){?><?=$i['agef'];}?> <?if(!empty($i['aget'])){?> - <?=$i['aget'];}?></td></tr>
<?}?>
<?if(!empty($i['heightf'])||!empty($i['heightt'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[84]?>
</td><td><?if(!empty($i['heightf'])){?> <?=$wh[$i['heightf']];}?> <?if(!empty($i['heightt'])){?> - <?=$wh[$i['heightt']];}?></td></tr>
<?}?>
<?if(!empty($i['weightf'])||!empty($i['weightt'])) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[85]?>
</td><td><?if(!empty($i['weightf'])){?> <?=$ww[$i['weightf']];}?> <?if(!empty($i['weightt'])){?> - <?=$ww[$i['weightt']];}?></td></tr>
<?}?>
<?if(C_REGDATE) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[124]?>
</td><td><?=$i['regdate'];?></td></tr>
<?}?>
<?if(C_ACCDATE) {?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[123]?>
</td><td><?=$i['editdate'];?></td></tr>
<?}?>
<Tr align="center" bgcolor="<?=COLOR1?>"><Td colspan="2">
<? if(C_MAILSH) {?><a href="mail.php?l=<?=$l?>&id=<?=$i['id']?>">[<?=$w[217]?>]</a> &nbsp; <?}?>
</td></tr></td></tr></table></td></tr></table></td></tr></table>
<? }}
include_once 'templates/'.C_TEMP.'/footer.php';?>