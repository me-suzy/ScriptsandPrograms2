document.writeln('<table border=0 cellspacing=0 cellpadding=0 width=100%>');
<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   require "lib/bann.lib";
   $db = c();

    if($pid == "") $pid = -1;
    $r = q("select * from groups where pid='$pid' order by topic");
    if(!e($r)){
	    $i = 1;
	    while($group = f($r)){
	 	if(!$i) echo "document.writeln('<tr>');\n";
		if($pid == -1){
			echo "document.writeln('<td>');\n";
			echo "document.writeln('<table border=0 width=100%>');\n";
			echo "document.writeln('<tr><td><b><a href=".$ROOT_HOST."index.php?src=groups&pid=$group[id]&uid=$uid>$group[topic]</td></tr>');\n";
			$subs = q("select id,topic from groups where pid='$group[id]'");
			if(!e($subs)){
				echo "document.writeln('<tr><td>');\n";
				$sub_group_count = 0;
				while($sub_group = f($subs)){
					echo "document.writeln('<a href=".$ROOT_HOST."index.php?src=groups&pid=$sub_group[id]&uid=$uid>$sub_group[topic]</a>');\n";

					if($sub_group_count == 3 || $sub_group_count == nr($subs)){
						echo "document.writeln('...');\n";
						break;
					} else echo "document.writeln(',');\n";

					$sub_group_count++;
				}

				echo "document.writeln('</td></tr>');\n";
			}

			echo "document.writeln('<tr><td></td></tr>');\n";
			echo "document.writeln('</table>');\n";
			echo "document.writeln('</td>');\n";
		} else {
			echo "document.writeln('<td><b><a href=".ROOT_HOST."index.php?src=groups&pid=$group[id]&uid=$uid>$group[topic]</a></td>');\n";
		}



		if($i == 2){
		  echo "document.writeln('</tr>');\n";
		  $i = 0;
		}

		$i++;


	    }
		if($i) echo "document.writeln('</tr>');\n";
    } else {
	echo "document.writeln('<tr><td colspan=2>No groups found.</td></tr>');\n";
    }
?>
document.writeln('</TABLE>');