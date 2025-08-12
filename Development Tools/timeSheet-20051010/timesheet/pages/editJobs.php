<?php
$users  = $ts->getSharedUsers($_REQUEST['client_id']);

$_REQUEST['client_id'] = intval($_REQUEST['client_id']);

//...... Check to see if this is a shared client
if ($ts->isSharedClient($_REQUEST['client_id'],$_SESSION['id']))
{
	$shared = 1;
	$Q="SELECT * FROM clients 
		WHERE id='$_REQUEST[client_id]'";
}
//...... Regular client
else
{
	$Q="SELECT * FROM clients 
		WHERE id=$_REQUEST[client_id] 
		AND user_id='$_SESSION[id]' 
		LIMIT 1;";
}

if ($cres = mysql_query($Q))
	$clientInfo = mysql_fetch_assoc($cres);

if ($clientInfo)
{
    //..... Get listing of all jobs for a client
	$jobs = $ts->getClientJobs($clientInfo['id']);
	$totals = $ts->getClientTotals($clientInfo['id']);

	//...... Get notes if activated
    if ($_SESSION['showNotes'] > 0) $notes = $ts->getJobNotes($_SESSION['showNotes']);

	$X->assign('totals',$totals);
	$X->assign('jobs',$jobs);
	$X->assign('notes',$notes);
	$X->assign('client',$clientInfo);
	$X->assign('users',$users);
}
$X->assign('shared',$shared);

?>
