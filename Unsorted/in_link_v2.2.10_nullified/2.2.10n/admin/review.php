<?php
//Read in config file
$thisfile = "review";
$admin = 1;
include("../includes/config.php");

if($action=="add_review")
{	if(!$form_input_add_review_text)
	{	$message=base64_encode($la_error_review_not_filled);
		$destin="navigate.php?t=error&message=$message";
	}
	else
	{	$rev_date = mktime(0,0,0,date("m"),date("d"),date("Y"));

		$query="insert into inl_reviews (rev_link, rev_text, rev_user, rev_date) values ('$id', '$form_input_add_review_text', '".$ses["user_id"]."', '$rev_date')";
		$rs = &$conn->Execute($query);
		//count number of reviews for that link
		$query="select count(rev_id) from inl_reviews where rev_link=$id";
		$rs = &$conn->Execute($query);
		if($rs && !$rs->EOF)
		{	$query="update inl_links set link_numrevs='" . $rs->fields[0] ."' where link_id='$id'";
			$rs = &$conn->Execute($query);
			$destin="navigate.php?t=reviews&id=$id";
		}
		else
		{	$message=base64_encode("internal db error");
			$destin="navigate.php?t=error&message=$message";//database error
		}
	}		
	inl_header($destin);
}
else
	inl_header("navigate.php?cat=$cat");
?>