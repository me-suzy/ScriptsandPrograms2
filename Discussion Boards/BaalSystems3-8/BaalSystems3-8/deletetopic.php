<?php
include("common.php");
if (!empty($_GET['fid'])) {
    $result = db_query("delete from {$tableprefix}tblforum where forumid=" . intval($_GET['fid']) . "");
    $result1 = db_query("delete from {$tableprefix}tblsubforum where forumid=" . intval($_GET['fid']) . "");
    header('location:admin.php');
} elseif (!empty($_GET['sfid'])) {
    $fid = $_GET['fid1'];

    $result = db_query("delete from {$tableprefix}tblsubforum where subforumid=" . intval($_GET['sfid']) . "");

    $result1 = db_query("update {$tableprefix}tblforum set totalpost=totalpost-1 where forumid=" . intval($fid) . "");
	
    header("location:subtopic.php?fid=" . $fid);
} 

?>
<?php ob_end_flush();

?>
