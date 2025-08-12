<?php

$_REQUEST['id'] = intval($_REQUEST['id']);

//..... I know this appears redundant .. but it is needed.
$Q="SELECT jobs.id,clients.id,clients.clientDesc,clients.user_id
	FROM clients,jobs 
	WHERE jobs.id='$_REQUEST[id]' 
	AND clients.id=jobs.client_id;";

list($job_id,$client_id,$client_name,$user_id) = mysql_fetch_row(mysql_query($Q));

//...... Is this a shared Job?
if ($ts->isSharedJob($job_id,$_SESSION['id'])) 
{
	$user_has_client = 1;
}
//...... Does this user own this job?
else
	$user_has_client = $user_id=$_SESSION['id'];

if ($user_has_client)
{
	$Q="SELECT jobDesc from jobs WHERE id='$job_id'";
	list($jobDesc) = mysql_fetch_row(mysql_query($Q));

	$X->assign('tasks',$ts->getTaskList($job_id));
	$X->assign('totals',$ts->getJobTotals($job_id));
	$X->assign('jobDesc',$jobDesc);
	$X->assign('client_id',$client_id);
	$X->assign('client_name',$client_name);
}

?>
