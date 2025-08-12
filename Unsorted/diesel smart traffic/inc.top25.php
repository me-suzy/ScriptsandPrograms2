<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   require "lib/bann.lib";
  
$db = c();?>
document.writeln('<table border=0 cellspacing=1 cellpadding=1 align=center bgcolor=#E0E0E0 width=100%><tr bgcolor=#F0F0F0 align=center><td></td><td>Title</td><td>Visitors</td><td>Uniques</td></tr><tr><td></td><td></td><td></td><td></td></tr>');
<?

	$sQuery = "
		select cam.id as id, cam.url as url, cam.title as title, count(pr.cid) as
		 pws from campaigns cam, previews pr where cam.id=pr.camp_id and cam.status='1'  group by cam.id order by pws desc
		limit 25
	";

   $r = q($sQuery);

   if(!e($r))
	 {$i=1;
			while($site = f($r))
			{
				$clicks_a = f(q("select count(cl.id) as c from clicks cl where '$site[id]'=cl.cid"));
				$clicks = (int)$clicks_a[ c ];

				$url = (strstr($site[url],"http://")!=""?"":"http://").$site[url];
				echo "\n document.writeln(\"<tr height=20 bgcolor=#FFFFFF align=center><td width=20>".($i++).".</td><td><a href='".$ROOT_HOST."target.php?topframe=1&uid=".$uid."&cmid=".$site[id]."' target=_blank>$site[title]</td><td align=center>$site[pws]</td><td align=center>$clicks</td></tr>\");";

			}
   }
?>
document.writeln('</table><a href=<? echo $ROOT_HOST; ?>index.php?forward=top100&uid=<? echo $uid; ?>>View Top 100 </a>');<?   d($db); ?>
