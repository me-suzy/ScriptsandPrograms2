<?php

	$r = q("select group_id as group_id, id as id from campaigns where status='1' and id='$uid' ORDER BY RAND()");

	if(e($r))
	{
		d($db);
		$errorcode="nocampaign_$uid";
		go();
		exit;
	}

	$c = f($r);

	//	Campaign record is true.
	//	Now we must target the ad.
	
	$campaign=q("SELECT *, cam.id as id, cam.user_id as user_id, cam.title as c_title FROM campaigns cam, camp_groups cam_gr , prev pn WHERE pn.cid=cam.id AND pn.prev_number>0 AND cam.id<>'$c[id]' AND ((cam.id=cam_gr.cid and cam_gr.guid='$c[group_id]') OR cam.group_id='$c[group_id]') AND cam.status='1' ORDER BY (RAND()*pn.prev_number) DESC");

	if (!$campaign[id])
	{
     $campaign = f(q("SELECT *, cam.id as id, cam.user_id as user_id, cam.title as c_title FROM campaigns cam, camp_groups cam_gr , prev pn WHERE pn.cid=cam.id AND pn.prev_number>0 AND cam.id<>'$c[id]' AND cam.status='1' ORDER BY RAND()"));
	};

	if (!$campaign[id]) 
	{
	$errorcode="nocampaignfound";
	go();
	exit;
	};

	$cid=$campaign[id];
	$title = ereg_replace("'", "''", $campaign[ c_title ]);
	$title = ereg_replace("\"", "\"\"", $title);

	if(e(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'")))
	{
			$rate = def_rate();
	}
	else
	{
	 	$set = f(q("select cs.rate from credits_set cs, campaigns c where cs.user_id=c.user_id and c.id='$uid'"));
		$rate_str = @split("/", $set[ rate ]);

		$rate = (float)sprintf("%0.2f",$rate_str[ 1 ] / $rate_str[ 0 ]);
	};

?>