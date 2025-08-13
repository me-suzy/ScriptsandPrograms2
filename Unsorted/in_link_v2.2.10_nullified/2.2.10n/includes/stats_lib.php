<?php
//Statistics functions file

	function statsdisp($str)
	{	global $conn;

		$rs = &$conn->Execute($str);
		if ($rs && !$rs->EOF)
			return $rs->fields[0];
		else
			return 0;
	}

	function stats_num_links()
	{	global $conn,$sql_type;
		
		if($sql_type!="postgres7")	
			$query="select * from inl_lc where link_pend=0 group by link_id";
		else
			$query="select distinct on (link_id) * from inl_lc where link_pend=0 order by link_id";

		$rs = &$conn->Execute($query);
		if ($rs && !$rs->EOF)
			return $rs->RecordCount();
		else
			return 0;
	}
	
	function stats_num_pendlinks()
	{	global $conn;
		return statsdisp("select count(link_id) from inl_lc where link_pend!=0");
	}

	function stats_num_cats()
	{	global $conn;
		return statsdisp("select count(cat_id) from inl_cats where cat_pend=0");
	}
	
	function stats_num_pedcats()
	{	global $conn;
		return statsdisp("select count(cat_id) from inl_cats where cat_pend=1");
	}

	
	function stats_num_reviews()
	{	global $conn;
		return statsdisp("select count(rev_id) from inl_reviews where rev_pend=0");
	}


	function stats_num_newlinks()	
	{	global $conn, $link_new;

		$cutoffdate = mktime(0,0,0,date("m"),date("d")-$link_new,date("Y"));
			
		$param = "select count(link_id) from inl_links where link_date >=". $cutoffdate;
		return statsdisp($param);
	}


	function stats_num_newcats()
	{	global $conn, $cat_new;

		$cutoffdate = mktime(0,0,0,date("m"),date("d")-$cat_new,date("Y"));

	
		$param = "select count(cat_id) from inl_cats where cat_date >=". $cutoffdate;
		return statsdisp($param);
	}
			
	
	function stats_num_picklinks()
	{	global $conn;
		return statsdisp("select count(link_id) from inl_links where link_pick=1");
	}

	
	function stats_num_pickcats()
	{	global $conn;
		return statsdisp("select count(cat_id) from inl_cats where cat_pick=1");
	}

		
	function stats_num_poplinks()
	{	global $conn, $link_pop;

	$rs = &$conn->Execute("select max(link_hits) as max, min(link_hits) as min from inl_links");
	if ($rs && !$rs->EOF)
	{
		$maxhits = $rs->fields[0];
		$minhits = $rs->fields[1];
	}
	$tophits = $maxhits - (($maxhits-$minhits)*($link_pop*.01));
			
		return statsdisp("select count(link_id) from inl_links where link_hits >= $tophits");
		
	}

	
	function stats_num_toplinks()
	{	global $conn, $link_top;

	$rs = &$conn->Execute("select max(link_rating) as max, min(link_rating) as min from inl_links");
	if ($rs && !$rs->EOF)
	{
		$maxrate = $rs->fields[0];
		$minrate = $rs->fields[1];
	}
			$toprate = $maxrate - (($maxrate-$minrate)*($link_top*.01));
			
		return statsdisp("select count(link_id) from inl_links where link_rating >= $toprate");
		
	}


	function stats_num_hiddenlinks()
	{	global $conn;
		return statsdisp("select count(link_id) from inl_links where link_vis=0");
	}

	
	function stats_num_hiddencats()
	{	global $conn;
		return statsdisp("select count(cat_id) from inl_cats where cat_vis=0");
	}

	
	function stats_num_users()
	{	global $conn;
		return statsdisp("select count(user_id) from inl_users");
	}	
			

	function stats_num_linkhits()
	{	global $conn;
 	$rs = &$conn->Execute("select sum(link_hits) as total from inl_links");
	if ($rs && !$rs->EOF)
	{
		return $rs->fields[0];
	}
	else
		return "<span class=\"error\"> SQL Error " . $rs->ErrorNo() . ": " . $rs->ErrorMsg() . "</span>";
	}	
			
	function stats_num_linkvotes()
	{	global $conn;
 	$rs = &$conn->Execute("select sum(link_votes) as total from inl_links");
	if ($rs && !$rs->EOF)
	{
		return $rs->fields[0];
	}
	else
		return "<span class=\"error\"> SQL Error " . $rs->ErrorNo() . ": " . $rs->ErrorMsg() . "</span>";	}	
		
	
	function stats_num_avgvotes()
	{	global $conn;
 	$rs = &$conn->Execute("select sum(link_rating)/count(link_id) as total from inl_links");
	if ($rs && !$rs->EOF)
	{
		return number_format($rs->fields[0], 2);
	}
	else
		return "<span class=\"error\"> SQL Error " . $conn->ErrorNo() . ": " . $conn->ErrorMsg() . "</span>";	}		


	function stats_tables()
	{	global $conn;
		$rs = &$conn->MetaTables();
		foreach($rs as $tbl)
			if(ereg("inl_",$tbl))
				$table_array[] = $tbl;
 
		return $table_array;	
	}		
		

	function stats_num_fields()
	{	global $conn;

		$rs = &$conn->MetaTables();
		foreach($rs as $tbl)
		{	if(ereg("inl_",$tbl))
			{	$col=&$conn->MetaColumns($tbl);
				$col=array_keys($col);
				$r = &$conn->Execute("select count($col[0]) from $tbl");
				if($r && !$r->EOF) 
					$total_fields += $r->fields[0];
			}
		}
		
		return $total_fields;
		
	}
?>