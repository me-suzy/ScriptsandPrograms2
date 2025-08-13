<?php
//Search Admin Functions File
//returns cats search query

function getcatsearch()
{	global $conn, $cat_name, $cat_desc, $fday, $fmonth, $fyear, $lday, $lmonth, $lyear, $cat_pick, $cat_vis, $sep, $ccust1, $ccust2, $ccust3, $ccust4, $ccust5, $ccust6, $cat_query, $pend; 
	$cat_name=search_escape(inl_escape($cat_name));
	$cat_desc=search_escape(inl_escape($cat_desc));
	$fday=search_escape(inl_escape($fday));
	$fmonth=search_escape(inl_escape($fmonth));
	$fyear=search_escape(inl_escape($fyear));
	$lday=search_escape(inl_escape($lday));
	$lmonth=search_escape(inl_escape($lmonth));
	$lyear=search_escape(inl_escape($lyear));
	$sep=search_escape(inl_escape($sep));
	$ccust1=search_escape(inl_escape($ccust1));
	$ccust2=search_escape(inl_escape($ccust2));
	$ccust3=search_escape(inl_escape($ccust3));
	$ccust4=search_escape(inl_escape($ccust4));
	$ccust5=search_escape(inl_escape($ccust5));
	$ccust6=search_escape(inl_escape($ccust6));
	$cat_vis= search_escape(inl_escape($cat_vis));
	
	$log="";
	if (strlen($cat_name) > 0)
		$log.= "|cat_name:$cat_name";
	if (strlen($cat_desc) > 0)
		$log.= "|cat_desc:$cat_desc";
	if	($fday>0 && $fday<32 && $fmonth>0 && $fmonth<13 && $fyear>1900 && $fyear<4000)
		$log.= "|cat_date2:".mktime(0,0,0,$fmonth,$fday,$fyear);
	if	($lday>0 && $lday<32 && $lmonth>0 && $lmonth<13 && $lyear>1900 && $lyear<4000)
		$log.= "|cat_date1:".mktime(0,0,0,$lmonth,$lday,$lyear);
	if (strlen($cat_pick) > 0)
		$log.= "|cat_pick:$cat_pick";
	if (strlen($ccust1) > 0)
		$log.= "|cust1:$ccust1";
	if (strlen($ccust2) > 0)
		$log.= "|cust2:$ccust2";
	if (strlen($ccust3) > 0)
		$log.= "|cust3:$ccust3";
	if (strlen($ccust4) > 0)
		$log.= "|cust4:$ccust4";
	if (strlen($ccust5) > 0)
		$log.= "|cust5:$ccust5";
	if (strlen($ccust6) > 0)
		$log.= "|cust6:$ccust6";
	if ($cat_vis == "0" or $cat_vis == "1")
		$log.= "|cat_vis:$cat_vis";
	if (strlen($log) > 0)
		$log.= "|type:$sep|";
	
	if ($log == "")
	{
		$message = base64_encode($la_error_for_adv_search);
		$destin = "navigate.php?t=error&message=$message";
		inl_header($destin);	
	}	

	$t=time();
	if ($pend==1)
		$SQL="insert into inl_search_log (log_type, log_date, log_search, search_action, log_keyword) values ('2', '$t', '2', '-1','$log')";
	else
		$SQL="insert into inl_search_log (log_type, log_date, log_search, log_keyword) values ('1', '$t', '2', '$log')";
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id"); 
}

//return link result
function getlinksearch()
{ global $link_name, $link_desc, $sep, $fdayl, $fmonthl, $fyearl, $ldayl, $lmonthl, $lyearl, $link_rating_l, $link_rating_f, $link_votes_l, $link_votes_f, $link_hits_l, $link_hits_f, $link_pick, $link_vis, $lcust1, $lcust2, $lcust3, $lcust4, $lcust5, $lcust6, $conn, $pend;
	$link_name=search_escape(inl_escape($link_name));
	$link_desc=search_escape(inl_escape($link_desc));
	$fday=search_escape(inl_escape($fdayl));
	$fmonth=search_escape(inl_escape($fmonthl));
	$fyear=search_escape(inl_escape($fyearl));
	$lyear=search_escape(inl_escape($lyearl));
	$lday=search_escape(inl_escape($ldayl));
	$lmonth=search_escape(inl_escape($lmonthl));
	$link_rating_l=search_escape(inl_escape($link_rating_l));
	$link_rating_f=search_escape(inl_escape($link_rating_f));
	$link_votes_l=search_escape(inl_escape($link_votes_l));
	$link_votes_f=search_escape(inl_escape($link_votes_f));
	$link_hits_l=search_escape(inl_escape($link_hits_l));
	$link_hits_f=search_escape(inl_escape($link_hits_f));
	$sep=search_escape(inl_escape($sep));
	$lcust1=search_escape(inl_escape($lcust1));
	$lcust2=search_escape(inl_escape($lcust2));
	$lcust3=search_escape(inl_escape($lcust3));
	$lcust4=search_escape(inl_escape($lcust4));
	$lcust5=search_escape(inl_escape($lcust5));
	$lcust6=search_escape(inl_escape($lcust6));

	
	$log="";
	if (strlen($link_name) > 0)
		$log.= "|link_name:$link_name";
	if (strlen($link_desc) > 0)
		$log.= "|link_desc:$link_desc";
	if	($fday>0 && $fday<32 && $fmonth>0 && $fmonth<13 && $fyear>1900 && $fyear<4000)
		$log.= "|link_date2:".mktime(0,0,0,$fmonth,$fday,$fyear);
	if	($lday>0 && $lday<32 && $lmonth>0 && $lmonth<13 && $lyear>1900 && $lyear<4000)
		$log.= "|link_date1:".mktime(0,0,0,$lmonth,$lday,$lyear);
	if (strlen($link_rating_l) > 0)
		$log.= "|link_rating1:$link_rating_l";
	if (strlen($link_rating_f) > 0)
		$log.= "|link_rating2:$link_rating_f";
	if (strlen($link_votes_l) > 0)
		$log.= "|link_votes1:$link_votes_l";
	if (strlen($link_votes_f) > 0)
		$log.= "|link_votes2:$link_votes_f";
	if (strlen($link_hits_l) > 0)
		$log.= "|link_hits1:$link_hits_l";
	if (strlen($link_hits_f) > 0)
		$log.= "|link_hits2:$link_hits_f";
	if (strlen($link_pick) > 0)
		$log.= "|link_pick:$link_pick";
	if (strlen($lcust1) > 0)
		$log.= "|cust1:$lcust1";
	if (strlen($lcust2) > 0)
		$log.= "|cust2:$lcust2";
	if (strlen($lcust3) > 0)
		$log.= "|cust3:$lcust3";
	if (strlen($lcust4) > 0)
		$log.= "|cust4:$lcust4";
	if (strlen($lcust5) > 0)
		$log.= "|cust5:$lcust5";
	if (strlen($lcust6) > 0)
		$log.= "|cust6:$lcust6";
	if (strlen($link_vis) > 0)
		$log.= "|link_vis:$link_vis";
	if (strlen($log) > 0)
		$log.= "|type:$sep|";

	if ($log == "")
	{
		$message = base64_encode($la_error_for_adv_search);
		$destin = "navigate.php?t=error&message=$message";
		inl_header($destin);	
	}

	$t=time();
	if ($pend==1)
		$SQL="insert into inl_search_log (log_type, log_date, log_search, search_action, log_keyword) values ('2', '$t', '2', '-1','$log')";
	else
		$SQL="insert into inl_search_log (log_type, log_date, log_search, log_keyword) values ('2', '$t', '2', '$log')";
	
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id"); 
}

function keywordsearch($key, $cat)
{	global $table, $conn, $pend;
//	$keyword = search_escape(inl_escape($keyword));
	$time = time();
	$key = trim($key);

	if ($pend == 1)
	{
		$key = ereg_replace("[[:space:]]*(\+)[[:space:]]*","+",$key);
		$a = -1; // SAVING search for PENGING stuff
		if ($table=="links")
			$cat_or_link = 2;
		else 
			$cat_or_link = 1;
		$SQL="INSERT into inl_search_log (log_type, log_date, log_search, search_action, log_keyword) VALUES ($cat_or_link, $time, 1, $a, '$key')";
	}
	else
	{
		if($table=="links" || $table=="links1" || $table=="links2")
		{
			if($key)
			{
				$key = ereg_replace("[[:space:]]*(\+)[[:space:]]*","+",$key);
				if (($table=="links1") || ($table=="links2"))
					$a = substr($table, strlen($table)-1, 1);// where to search current cat or all subcats
				else 
					$a = 0;
				$SQL="insert into inl_search_log (log_type, log_date, log_search, search_action, log_keyword, search_cat) values (2, $time, 1, $a, '$key', $cat)";
				
			}
		}
		if($table=="cats")
		{
			if($key)
			{
				$key = ereg_replace("[[:space:]]*(\+)[[:space:]]*", "+",$key);
				$SQL="insert into inl_search_log (log_type, log_date, log_search, log_keyword) values (1, $time, 1, '$key')";
				
			}
		}
	}
	$conn->Execute($SQL);
	return $conn->Insert_ID("inl_search_log","log_id");
}

function getsearchquery(){
	global $result, $la_button_search,$user_name,$first, $last, $fday, $dmonth, $fyear,$lday, $lmonth, $lyear,$email, $user_perm, $status, $ucust1,$ucust2,$ucust3,$ucust4,$ucust5,$ucust6, $sep, $page_nav_vars ,$pend; 
	if($user_name){$page_nav_vars["user_name"]=$user_name;}
	if($first){$page_nav_vars["first"]=$first;}
	if($last){$page_nav_vars["last"]=$last;}
	if($fday){$page_nav_vars["fday"]=$fday;}
	if($dmonth){$page_nav_vars["dmonth"]=$dmonth;}
	if($fyear){$page_nav_vars["fyear"]=$fyear;}
	if($lday){$page_nav_vars["lday"]=$lday;}
	if($lmonth){$page_nav_vars["lmonth"]=$lmonth;}
	if($lyear){$page_nav_vars["lyear"]=$lyear;}
	if($email){$page_nav_vars["email"]=$email;}
	if($user_perm){$page_nav_vars["user_perm"]=$user_perm;}
	if($status){$page_nav_vars["status"]=$status;}
	if($sep){$page_nav_vars["sep"]=$sep;}
	if($ucust1){$page_nav_vars["ucust1"]=$ucust1;}
	if($ucust2){$page_nav_vars["ucust2"]=$ucust2;}
	if($ucust3){$page_nav_vars["ucust3"]=$ucust3;}
	if($ucust4){$page_nav_vars["ucust4"]=$ucust4;}
	if($ucust5){$page_nav_vars["ucust5"]=$ucust5;}
	if($ucust6){$page_nav_vars["ucust6"]=$ucust6;}
	$page_nav_vars["submit"]=$la_button_search;
	if($result){$page_nav_vars["result"]=$result;}

	if($user_perm){$page_nav_vars["user_perm"]=$user_perm;}
	$quer="select inl_users.*, inl_custom.* from inl_users left join inl_custom on inl_users.user_cust=inl_custom.cust_id where user_perm>1 and user_pend=$pend ";
	$having="";
	
	if(strlen($user_name)>0){$having=$having."( user_name like '%$user_name%' ";}
	if(strlen($first)>0){
		if(strlen($having)<1){$having=$having."( first like '%$first%' ";}
		else{	$having=$having."$sep first like '$first%' ";}
	}
	if(strlen($last)>0){
		if(strlen($having)<1){$having=$having."( last like '%$last%' ";}
		else{	$having=$having."$sep last like '$last%' ";}
	}
	if($fday<1 || $fday>31 || $fday<1 || $fmonth<1 || $fmonth>12 || $fmonth<1 || $fyear<1 || $fyear>4000 || $fyear<1){;}
	else{
		if(strlen($having)<1){$having=$having."( user_date > '".mktime(0,0,0,$fmonth,$fday,$fyear)."' ";}
		else{	$having=$having."$sep user_date > '".mktime(0,0,0,$fmonth,$fday,$fyear)."' ";}		
	}
	if($lday<1 || $lday>31 || $lday<1 || $lmonth<1 || $lmonth>12 || $lmonth<1 || $lyear<1 || $lyear>4000 || $lyear<1){;}
	else{
		if(strlen($having)<1){$having=$having."( user_date < '".mktime(0,0,0,$lmonth,$lday,$lyear)."' ";}
		else{	$having=$having."$sep user_date < '".mktime(0,0,0,$lmonth,$lday,$lyear)."' ";}		
	}
	if(strlen($email)>0){
		if(strlen($having)<1){$having=$having."( email like '%$email%' ";}
		else{	$having=$having."$sep email like '$email%' ";}
	}
	if(strlen($user_perm)>0){	
		if(strlen($having)<1){$having=$having."( user_perm = $user_perm ";}
		else{	$having=$having."$sep user_perm = $user_perm ";}
	}
	if(strlen($status)>0){
		if(strlen($having)<1){$having=$having."( user_status = $status ";}
		else{	$having=$having."$sep user_status = $status ";}
	}
	if(strlen($ucust1)>0){
		if(strlen($having)<1){$having=$having."( cust1 like '%$ucust1%' ";}
		else{	$having=$having."$sep cust1 like '$ucust1%' ";}
	}
	if(strlen($ucust2)>0){
		if(strlen($having)<1){$having=$having."( cust2 like '%$ucust2%' ";}
		else{	$having=$having."$sep cust2 like '$ucust2%' ";}
	}
	if(strlen($ucust3)>0){
		if(strlen($having)<1){$having=$having."( cust3 like '%$ucust3%' ";}
		else{	$having=$having."$sep cust3 like '$ucust3%' ";}
	}
	if(strlen($ucust4)>0){
		if(strlen($having)<1){$having=$having."( cust4 like '%$ucust4%' ";}
		else{	$having=$having."$sep cust4 like '$ucust4%' ";}
	}
	if(strlen($ucust5)>0){
		if(strlen($having)<1){$having=$having."( cust5 like '%$ucust5%' ";}
		else{	$having=$having."$sep cust5 like '$ucust5%' ";}
	}
	if(strlen($ucust6)>0){
		if(strlen($having)<1){$having=$having."( cust6 like '%$ucust6%' ";}
		else{	$having=$having."$sep cust6 like '$ucust6%' ";}
	}
	if(strlen($having)>0){$having=" and ".$having.")";}
	$toreturn="$quer"."$having";

	return $toreturn; 
}

function display_search_log(){
		global $conn, $orderby, $lim, $start , $log_search, $bgc, $la_advanced_search, $la_simple_search, $la_drop_categories, $la_no_search_logs, $la_drop_links; 
		$display_log=""; $bgc="#DEDEDE";
		if ($orderby=="log_date"){$sort="desc";}
		if (!$log_search)
			$wher="where log_search='1'";
		else
			$wher="where log_search='$log_search'";
		$query="SELECT * FROM inl_search_log $wher order by $orderby $sort ";
		$random=1;

		settype($lim,"integer");
		settype($start,"integer");

		if($lim || $start)
			$rs =&$conn->SelectLimit($query,$lim,$start);
		else
			$rs =&$conn->Execute($query);
		if($rs && !$rs->EOF){
			do{
				if($rs->fields[1]==1){$logt=$la_drop_categories;}
				if($rs->fields[1]==2){$logt=$la_drop_links;}
				if($rs->fields[3]==2){$logs=$la_advanced_search;}
				if($rs->fields[3]==1){$logs=$la_simple_search;}
				$display_log=$display_log."	 <TR bgcolor='$bgc'> 
				<TD class='text'>".$rs->fields[4]."&nbsp; </TD>
				<TD class='text'>$logt&nbsp;</TD>
				<TD class='text'>".date("F j, Y, g:i 	a",$rs->fields[2])."&nbsp; </TD>
				<TD class='text'>$logs&nbsp;</TD>
						</TR>";
				if($random%2==1){$bgc="#F6F6F6";}else{$bgc="#DEDEDE";}
				$random++;
			$rs->MoveNext();
			}while($rs && !$rs->EOF);
		}else{$display_log=$display_log."	 <TR bgcolor='#DEDEDE'> <TD class='text' colspan=4 align='center'>$la_no_search_logs</TD>	</TR>";}
		$ar["orderby"]=$orderby;
		$ar["log_search"]=$log_search;
		pagenav("", "select * from inl_search_log $wher order by '$orderby'","search_log", $start, $ar);
		return $display_log;
}
function deletelog(){
	global $conn, $log_search;
	if($log_search==2){$conn->Execute("Delete from inl_search_log where log_search=2 or log_search=3");}
	if($log_search==1){$conn->Execute("Delete from inl_search_log where log_search=1 or log_search=3");}
}

?>