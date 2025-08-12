<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";

if (isset($id) || $id != "") {
$sql = "SELECT * FROM $mysql_table WHERE id = '$id'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
?>
<br><table align=center><tr>
<td valign=top>
<?php
if ($i[pic] != "") {
?>
<a href="<?php echo $i[pic]; ?>" target="_blank"><img src="<?php echo $i[pic]; ?>" border=0 width=150></a></td>
<?php
}
?>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?> width=350>
<tr class=head><td colspan=2 align=center><?php echo $lang[37]; ?><?php echo $i[user]; ?></td></tr>
<tr class=mes><td><?php echo $lang[9]; ?></td><td><?php echo $i[user]; ?></td></tr>
<tr class=mes><td><?php echo $lang[13]; ?></td><td><?php echo $i[country]; ?></td></tr>
<tr class=mes><td><?php echo $lang[15]; ?></td><td><?php echo $i[city]; ?></td></tr>
<tr class=mes><td><?php echo $lang[14]; ?></td><td><?php echo $langgender[$i[gender]]; ?> <?php echo $langpurposes[$i[purposes]]; ?></td></tr>
<tr class=mes><td><?php echo $lang[18]; ?></td><td><?php echo $i[height]; ?></td></tr>
<tr class=mes><td><?php echo $lang[19]; ?></td><td><?php echo $i[weight]; ?></td></tr>
<tr class=mes><td><?php echo $lang[20]; ?></td><td><?php echo $i[age]; ?></td></tr>
<tr class=mes><td><?php echo $lang[16]; ?></td><td><?php echo $i[hobby]; ?></td></tr>
<tr class=mes><td><?php echo $lang[17]; ?></td><td><?php echo $i[Description]; ?></td></tr>
<tr class=mes><td><?php echo $lang[12]; ?></td><td><a href="email.php?l=<?php echo $l; ?>&id=<?php echo $i[id]; ?>&user=<?php echo $i[user]; ?>"><?php echo $lang[36]; ?></td></tr>

</table>
</td></tr></table><br>


<?php
}
} else {
         echo $err_mes_top.$lang[90].$err_mes_bottom;
         include "templates/footer.php";
         die;
}

include "templates/footer.php";
?>