<?
  	if($uid == "") exit;
	require "conf/sys.conf";
	require "lib/mysql.lib";
	require "lib/ban.lib";
	require "lib/bann.lib";

$d_url = get_ban($DURL_P);
function go($url="") {

 global $d_url,$d_banner_url, $ipfseconds;
 if (!$url) $url=$d_url;
{
?>
document.writeln("<A href=$d_url><img src=$d_banner_url border=0 alt='DEFAULT BANNER (<?php echo $ipfseconds; ?> seconds timeframe security protection activated)'></A>");
<?
}

}

	$db = c();

	$d_banner_url = get_ban($DBAN_P);
	$d_url = get_ban($DURL_P);

	$r = q("select cam.group_id as group_id, cam.id as id from campaigns cam where cam.status='1' and cam.id='$uid' group by cam.id");

	if(e($r))
	{
		d($db);
		go();
		exit;
	}

	$c = f($r);

	$campaign = f(q("SELECT *, cam.id as id, cam.user_id as user_id, ban.type as banner_type, cam.title as c_title, ban.id as banner_id FROM campaigns cam, camp_groups cam_gr, banners ban , prev pn WHERE pn.cid=cam.id AND pn.prev_number>0 AND cam.id<>'$c[id]' AND ((cam.id=cam_gr.cid and cam_gr.guid='$c[group_id]') OR cam.group_id='$c[group_id]') AND ban.cid=cam.id AND ban.status='1' AND cam.status='1' group by ban.id ORDER BY (RAND()*ban.weight*pn.prev_number) desc"));

	if(!$campaign[id]) $campaign = f(q("SELECT *, cam.id as id, cam.user_id as user_id, ban.type as banner_type, cam.title as c_title, ban.id as banner_id FROM campaigns cam, camp_groups cam_gr, banners ban , prev pn WHERE pn.cid=cam.id AND pn.prev_number>0 AND cam.id<>'$c[id]' AND ban.cid=cam.id AND ban.status='1' AND cam.status='1' group by ban.id ORDER BY (RAND()*ban.weight*pn.prev_number) desc"));

	if(!$campaign[id])
	{
		d($db);
		echo "document.writeln(\"<A href=$d_url><img src='$d_banner_url' border='0' alt='DEFAULT BANNER (no valid traffic receiver found)'></A>\");\n";
		exit;
	}

	$banner_url = (strstr($campaign[burl],"http://")!=""?"":"http://").$campaign[burl];
	if($campaign[ burl ] == "" || !@fopen($campaign[ burl ],"r"))
	{
		$banner_url = $d_banner_url;
		$campaign[ banner_type ] = "Image";
	}

	if($banner_url == "")
	{
		d($db);
		echo "document.writeln(\"<A href=$d_url><img src='$d_banner_url' border='0' alt='DEFAULT BANNER (banner not found)'></A>\");\n";
		exit;
	}

	$title = ereg_replace("'", "''", $campaign[c_title] . " ($campaign[banner_id])" );
	$title = ereg_replace("\"", "\"\"", $title);
	
	if (!$title) $title= "Error : No campaign title !!";

	if(e(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'")))
	{
			$rate = def_rate();
	}
	else
	{
	 	$set = f(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'"));
		$rate_str = @split("/", $set[ rate ]);

		$rate = (float)sprintf("%0.2f",$rate_str[ 1 ] / $rate_str[ 0 ]);
	}

	q("insert into previews values('','$campaign[banner_id]','$uid','".strtotime(date("d M Y H:i:s"))."','$REMOTE_ADDR','$campaign[id]')");
	q("update prev set prev_number=prev_number-1 where cid='$campaign[id]'");
	q("update prev set prev_number=prev_number+$rate where cid='$uid'");
	d($db);


	if(e(q("select id from logs where user_id='$campaign[user_id]'")))
	{
		q("insert into logs values('','$campaign[user_id]','".strtotime(date("d M Y H:i:s"))."')");
	}
	else
	{
		q("update logs set idate='".strtotime(date("d M Y H:i:s"))."' where user_id='$campaign[user_id]'");
	};


	echo "document.write(\"<a href='".(strstr($ROOT_HOST ,"http://")!=""?"":"http://")."$ROOT_HOST/bn_click.php?uid=$uid&ruid=$campaign[banner_id]&topframe=$topframe' target=_blank>\");";
	$sz = split("x",$campaign[isize]);
	switch($campaign[ type ]){
		case "Image":
			echo "document.write(\"<img src='$campaign[burl]' border='0' alt='$title' width='$sz[0]' height='$sz[1]'>\");\n";
			break;
		case "Flash":
			echo "document.write('<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"$sz[0]\" height=\"$sz[1]\">');";
			echo "document.write('<param name=movie value=\"$campaign[burl]\">');";
			echo "document.write('<param name=quality value=high><param name=\"SCALE\" value=\"noborder\">');";
			echo "document.write('<embed src=\"$campaign[burl]\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"$sz[0]\" height=\"$sz[1]\" scale=\"noborder\">');";
			echo "document.write('</embed>');";
			echo "document.write('</object>');";
			break;
		case "Text":

			$campaign[ btext ] = str_replace("\n", "<br>", $campaign[ btext ]);
			$campaign[ btext ] = str_replace("\r", "", $campaign[ btext ]);
			$campaign[ btext ] = str_replace("\t", "&nbsp;", $campaign[ btext ]);
			$campaign[ btext ] = str_replace("'", "&#146;", $campaign[ btext ]);
			$campaign[ btext ] = str_replace("\"", "&#148;", $campaign[ btext ]);

			if(strlen($campaign[ btext ]) > 200)
			{
				$tmp = $campaign[ btext ];
				for($i = 0; $i < 200; $i++)
				{
					$txt .= $tmp[ $i ];
				}
			}
			else
			{
				$txt = $campaign[ btext ];
			}

			echo "document.write(\"$txt\");";
			break;
	}
echo "document.write(\"</a>\");";
?>
