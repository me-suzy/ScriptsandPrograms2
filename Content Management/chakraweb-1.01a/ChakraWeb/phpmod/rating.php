<?php 
// ----------------------------------------------------------------------
// ModName: rating.php
// Purpose: Process web page rating 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");


$redirect = RequestGetValue('path', '');
$page_id  = RequestGetValue('id', 0);
$rating   = RequestGetValue('rating', 0);

$chk_rating = 'rating_'.$page_id;
if (Session($chk_rating, 0) == 0)
{
    UpdateWebPageRating($page_id, $rating);
    SessionSetValue($chk_rating, 1);
}

Header("Location: $redirect");


function UpdateWebPageRating($page_id, $rating)
{
	global $db;

	$sql = "select page_rating, page_votes from web_page where page_id=$page_id";
	$rs = DbExecute($sql);

	if ($rs && !$rs->EOF)
    {
		$page_rating = $rs->fields[0];
		$page_votes  = $rs->fields[1];

        //update to the new values
		$page_rating = (float)($page_rating*$page_votes+$rating)/($page_votes+1);
		$page_votes++;
		
		$sql = "update web_page set page_rating=$page_rating, page_votes=$page_votes where page_id=$page_id";
        DbExecute($sql);
	}
}

?>
