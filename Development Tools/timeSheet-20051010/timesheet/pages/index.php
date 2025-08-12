<?php


//...... Display all users / stats for admin
if ($_SESSION[id] == 1)
{
	$Q="SELECT * FROM users WHERE id != 1 ORDER BY dateAdded DESC";
	$ures = mysql_query($Q);
	while($user = mysql_fetch_assoc($ures))
	{
		$Q="SELECT id FROM jobs WHERE user_id='$user[id]'";
		$jres = mysql_query($Q);
		while($job = mysql_fetch_assoc($jres))
		{
            $jstats = $ts->getJobStats($job['id']);

            $revenue += floatval($jstats['revenue']);
			$time    += intval($jstats['time']);
		}

		$user['time'] = $ts->time_left($time);
		$user['revenue'] = number_format($revenue,2);

		$totalTime += intval($time);
		$totalRev += floatval($revenue);

		$user['totals'] = $totals;
		$users[] = $user;

		$revenue = 0;
		$time = 0;
	}

	$totals['time'] = $ts->time_left($totalTime);
	$totals['revenue'] = number_format($totalRev,2);

	$X->assign('totals',$totals);
	$X->assign('users',$users);
}

//...... Get shared Clients
$allClients = $ts->getAllClients($_SESSION['id']);

if (is_array($allClients))
{
	foreach($allClients as $key=>$clientStats)
	{
		//...... Get grand totals for total line
		$total['isRunning']	+= intval($clientStats['isRunning']);
		$total['jobs']		+= intval($clientStats['numJobs']);
		$total['finished']	+= intval($clientStats['numJobsClosed']);
		$total['revenue']	+= floatval($clientStats['revenue']);
		$total['time']		+= intval($clientStats['time']);
	}
}
//...... Set totals for time
$total['time'] = time_left($total['time']);

$X->assign('total',$total);
$X->assign('clients',$allClients);

?>
