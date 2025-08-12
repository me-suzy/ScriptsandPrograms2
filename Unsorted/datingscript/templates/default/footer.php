<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# Template Name           Default                          #
# Author                  AzDG <support@azdg.com>          #
############################################################
# File name               footer.php                       #
# File purpose            Footer for Default template      #
# File created by         AzDG <support@azdg.com>          #
############################################################
global $stime,$w;$m2 = explode(" ", microtime());$etime = $m2[1] + $m2[0];$ttime = ($etime - $stime);$ttime = number_format($ttime, 7);?>

<!-- !!!Don`t Remove CopyRights (link to azdg.com) !!! -->

<br>
<center><Table border="1" cellspacing="0" cellpadding="0" bordercolor="black" bgcolor="<?=COLOR2?>">
<tr><td width="738" height="40" align=center class=desc valign=top>
<?=$w[103]?> <?=$ttime ?> <?=$w[104]?><br>
<?=$w[105]?> <?=online_users()?>,
<?=$w[106]?> <?=online_quests()?><br>
<?=$w[107]?><br>
</td></tr></table>
</body></html>
