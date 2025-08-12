<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   require "lib/bann.lib";
   $db = c();

   $previews = q("select count(id) as v from previews");
   $imp = $mkadd["previews"];
   while($preview = f($previews))
	 {
			$imp += $preview[v];
	 }

   $previews_today = q("select count(id) as v from previews where idate>='".strtotime(date("d M Y"))."'");
   $pt = $mkadd["previewstoday"];
   while($preview_today = f($previews_today))
	 {
			$pt += $preview_today[v];
	 }

   $clicks = q("select count(cl.id) as v from clicks cl");
    $hit=$mkadd["hits"];
    while($click = f($clicks))
	 {
			$hit += $click[v];
	 }

   $clicks_today = q("select count(cl.id) as v from clicks cl where cl.idate>='".strtotime(date("d M Y"))."'");

   $ct =$mkadd["hitstoday"];
   while($click_today = f($clicks_today))
	 {
			$ct += $click_today[v];
		}

   $members_num = f(q("select count(id) as v from members where status='1'"));
   $campaigns_num = f(q("select count(id) as v from campaigns where status='1'"));
?>
document.writeln('<table border=0 cellspacing=1 cellpadding=1 bgcolor=CCCCCC>');
document.writeln('<tr><td bgcolor=CCCCCC>&nbsp;System information &nbsp;<B><a href=<?php echo $ROOT_HOST."index.php?src=groups&uid=$uid"; ?>> &gt;&gt;</a></B> </td></tr>');
document.writeln('<tr><td bgcolor=FFFFFF>');
document.writeln('<table border=0 cellspacing=2 width=100%>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Total Impressions:</td><td align=right><? echo $imp; ?></td></tr>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Total Hits:</td><td align=right><? echo $hit; ?></td></tr>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Impressions today:</td><td align=right><? echo $pt; ?></td></tr>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Hits today:</td><td align=right><? echo $ct; ?></td></tr>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Campaigns:</td><td align=right><? echo ($campaigns_num[v]+$mkadd["campaigns"]); ?></td></tr>');
document.writeln('<tr bgcolor=FFFFFF><td>&nbsp;Members:</td><td align=right><? echo ($members_num[v]+ $mkadd["members"]); ?></td></tr>');
document.writeln('</td></tr>');
document.writeln('</table>');
document.writeln('</table>');