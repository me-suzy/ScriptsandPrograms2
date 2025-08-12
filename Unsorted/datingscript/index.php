<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               index.php                        #
# File purpose            Main page                        #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
include_once 'templates/'.C_TEMP.'/header.php';
if(file_exists("install.php")) printm("<i>Security Alert</i>: Please remove install.php",2); 
?>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_WIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td width="<?=C_WIDTHL?>" bgcolor="<?=COLOR1?>" valign="top">
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTHL?>" class=mes>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="center" bgcolor="<?=COLORH?>"><Td>
<?=$w[171]?>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>" class=desc><Td>
<form action="login.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="p" value="s">
<?=login(C_ID);?> <input class=minput type=text name=id><br>
<?=$w[54]?> <input class=minput type=password name=password><br>
<input class=minput type=submit value="<?=$w[263]?>">
<Tr align="center" bgcolor="<?=COLOR1?>"></form><Td>
<a href="<?=C_URL?>/add.php?l=<?=$l?>" class=desc>[<?=$w[89]?>]</a> <a href="remind.php?l=<?=$l?>" class=desc>[<?=$w[173]?>]</a>
</Td></Tr>
</Table>
</Td><Td width="<?=C_WIDTHR?>" valign="top" bgcolor="<?=COLOR1?>">
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTHC?>" class=mes>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="center" bgcolor="<?=COLORH?>"><Td>
<?=$w[178]?>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<span class=mes><?=$w[179]?> </span>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="center" bgcolor="<?=COLORH?>"><Td>
<? $tm=array(C_LASTREG);echo template($w[180],$tm); ?>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<table width="100%" class=tr>
<tr class=mes bgcolor="<?=COLORH?>" align=center><td><?=$w[118]?></td><td><?=$w[132]?></td><td><?=$w[120]?></td><td><?=$w[87]?></td></tr>
<?$tmp=mysql_query("SELECT id, fname, gender, birthday, pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE status >= '7' order by regdate DESC limit ".C_LASTREG);
$color='';
while($i=mysql_fetch_array($tmp)) {
$color = ($color == COLOR4) ? COLOR3 : COLOR4;
$name = (trim($i['fname']) == '') ? $i['id'] : $i['fname']; 
$ph = (($i['pic1'] == '')&&($i['pic2'] == '')&&($i['pic3'] == '')) ? $w[111] : $w[112];
$age=abs(mysql2data($i['birthday'],1));
echo "<tr class=desc bgcolor=\"".$color."\" align=center><td><a href=\"view.php?l=".$l."&id=".$i['id']."\" class=desc>".$name."</a></td><td>".$wg[$i['gender']]."</td><td>".$age."</td><td>".$ph."</td></tr>";}
$tmp=mysql_query("SELECT count(id) as total FROM ".C_MYSQL_MEMBERS." WHERE status >= '7'");
$rows=mysql_fetch_array($tmp);$usc=$rows['total'];?>
</table>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="center" bgcolor="<?=COLORH?>"><Td>
<? $tm=array($usc);echo template($w[268],$tm); ?>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
</Td></Tr></Table>
</Td><Td width="<?=C_WIDTHR?>" bgcolor="<?=COLOR1?>" valign="top">
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_WIDTHR?>" class=mes>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="center" bgcolor="<?=COLORH?>"><Td>
<?=$w[181]?>
</Td></Tr>
<Tr><Td bgcolor="<?=C_TBCOLOR?>"></Td></Tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<form action="search.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="a" value="s">
<input type="hidden" name="id" value="">
<input type="hidden" name="horo" value="0">
<input type="hidden" name="aget" value="<?=C_AGEB?>">
<input type="hidden" name="agef" value="<?=C_AGES?>">
<input type="hidden" name="purpose" value="0">
<input type="hidden" name="regin" value="0">
<input type="hidden" name="step" value="10">
<input type="hidden" name="sortby" value="0">
<?=$w[132]?> <select name="gender" class="minput">
<? $p=0;while(isset($wg[$p])) {echo '<option value="'.$p.'">'.$wg[$p];$p++;}?></select>
<br>
<?=$w[121]?> <select name="country" class="minput">
<? $p=0;asort($wcr);reset($wcr);
while (list ($p, $val) = each ($wcr)) {
echo '<option value="'.$p.'">'.$val;
}
?>
</select>
<br>
<input class=minput type=submit value="<?=$w[181]?>">
<Tr align="center" bgcolor="<?=COLOR1?>"></form><Td>
<a href="<?=C_URL?>/search.php?l=<?=$l?>" class=desc>[<?=$w[91]?>]</a> </Td></Tr>
</table></Td></Tr></Td></Tr></Table></Td></Tr></Table>
<?include_once 'templates/'.C_TEMP.'/footer.php';?>