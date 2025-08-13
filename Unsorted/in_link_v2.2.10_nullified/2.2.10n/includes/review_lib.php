<?php
//print reviews
function print_reviews($query, $lim=0, $start=0) 
{
	global $conn, $reviews, $datefmt, $la_no_reviews,$rev_data, $admin,$lu_no_reviews, $t, $ses;

	if($t=="list_pend_reviews")
		$p="list_previews";
	else
		$p="list_reviews";

	$ret="";

	settype($lim,"integer");
	settype($start,"integer");

	if($lim)
		$rs=&$conn->SelectLimit($query,$lim,$start);
	else
		$rs = &$conn->Execute($query);

	if($rs && !$rs->EOF)
	{
		while (($rev_data = $rs->fields) && !$rs->EOF) 
		{
			if($t=="pend_reviews" && $ses["user_perm"]==5)
			{	//get cat info
				$rs2 = &$conn->Execute("SELECT cat_user FROM inl_cats, inl_lc WHERE inl_cats.cat_id=inl_lc.cat_id and inl_lc.link_id=$rev_data[5]");
				if ($rs2 && !$rs2->EOF)
				{	if($rs2->fields[0]==$ses["user_id"])
						$show=1;
					else
						$show=0;
				}
				else $show=0;
			}
			else
				$show=1;

			if($show)
			{	$rs2 = &$conn->Execute("select user_name, email from inl_users where user_id='$rev_data[2]'");
				if($rs2 && !$rs2->EOF)
				{	$row2 = $rs2->fields; 
					$rev_data[4] = $row2[0];
					$rev_data[5] = $row2[1];
				}
				$ret .= parse($p);
			}
			$rs->MoveNext();
		} 
	} 
	elseif($admin==1)
		$ret = "<span class='sys-message'>$la_no_reviews</span>";
	else
		$ret = "<span class='sys-message'>$lu_no_reviews</span>";

	return $ret;
}

//validate review values
function validatereview() 
{
	global $error, $rev_text, $rev_month, $rev_day, $rev_year, $admin, $rev_user;
	$error = 0;
	settype($rev_month, "integer");
	settype($rev_year, "integer");
	settype($rev_day, "integer");
	if ($rev_text == "") 
	{	$error = 1;
	}
	elseif ((is_int($rev_month) == false) || ($rev_month > 12)) 
	{	$error = 3;
	} 
	elseif ((is_int($rev_day) == false) || ($rev_day > 31)) 
	{	$error = 4;
	}
	elseif (is_int($rev_year) == false) 
	{	$error = 5;
	}
	elseif($admin==1)
	{
		if(!$rev_user)
			$error=6;
	}
}

function delreview($delid) 
{	global $conn;
	$rs = &$conn->Execute("SELECT rev_link FROM inl_reviews WHERE rev_id=$delid");
	$query="delete from inl_reviews where rev_id=$delid";
	$conn->Execute($query);
	if($rs && !$rs->EOF)
		update_link_reviews($rs->fields[0]);
}

function addreview($rev_id) 
{
	global $conn, $rev_text, $rev_month, $rev_day, $rev_year, $rev_user;
	$rev_date = mktime(0,0,0,$rev_month,$rev_day,$rev_year);
	$query="insert into inl_reviews (rev_link, rev_text, rev_user, rev_date, rev_pend) values ('$rev_id', '$rev_text', '$rev_user', '$rev_date',0)";
	$conn->Execute($query);
	update_link_reviews($rev_id);

}
function editreview($rev_id) 
{
	global $conn, $rev_text, $rev_month, $rev_day, $rev_year, $rev_user;
	
	$rev_date = mktime(0,0,0,$rev_month,$rev_day,$rev_year);
	$query="update inl_reviews set rev_text='$rev_text',rev_user='$rev_user', rev_date='$rev_date' where rev_id=$rev_id";
	$conn->Execute($query);
}

function update_link_reviews($link_id)
{	global $conn;
	$rs = &$conn->Execute("SELECT count(rev_id) FROM inl_reviews WHERE rev_link=$link_id and rev_pend=0");
	if($rs && !$rs->EOF)
		$conn->Execute("UPDATE inl_links SET link_numrevs=" .$rs->fields[0] ." WHERE link_id=$link_id");
}
?>