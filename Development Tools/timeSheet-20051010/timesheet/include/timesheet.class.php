<?php
class Timesheet
{
	function Timesheet($db_name)
	{
		$this->db_name = $db_name;
	}

	function isSharedJob($job_id,$shared_owner_id)
	{
		//$Q="SELECT 
	}

	

	function isSharedClient($client_id,$shared_owner_id)
	{
		$Q="SELECT client_id 
			FROM $this->db_name.clientShare 
			WHERE client_id='$client_id' 
			AND share_owner_id='$shared_owner_id'";
		$res = mysql_query($Q);
		return(mysql_num_rows($res));
	}

	function getSharedUsers($client_id)
	{
		$Q="SELECT share_owner_id 
			FROM $this->db_name.users,$this->db_name.clientShare 
			WHERE share_owner_id=id 
			AND client_id='$client_id'";
		$res = mysql_query($Q);

		while(list($userID) = mysql_fetch_row($res))
		{
			$sharedUser[$userID] = 1;
		}

		$users = $this->getUserList();
		if (is_array($users))
		{
			foreach($users as $user)
			{
				$uid = $user['id'];
				if ($sharedUser[$uid]) $user['shared'] = 1;
				$sharedUsers[] = $user;
			}
		}

		return($sharedUsers);
	}
			

	function getUserList()
	{
		//...... Get a list of all users for job sharing
		$Q="SELECT *
			FROM $this->db_name.users
			WHERE id NOT in('$_SESSION[id]')
			ORDER BY firstname,lastname ";
		$res = mysql_query($Q);

		while($user = mysql_fetch_assoc($res))
		{
			$users[] = $user;
		}

		return($users);
	}

	function getClientTotals($client_id)
	{
		$Q="SELECT id FROM $this->db_name.jobs WHERE client_id='$client_id'";
		$res = mysql_query($Q);

		while($job = mysql_fetch_assoc($res))
		{
            //...... Get stats for each job
            $totals = $this->getJobTotals($job['id']);

			$cTotals['rawtime'] += $totals['rawtime'];
			$cTotals['revenue'] += floatVal($totals['revenue']);
		}

		$cTotals['time'] = $this->time_left($cTotals['rawtime']);
		$cTotals['revenue'] = $cTotals['revenue'];

		return($cTotals);
	}

	function getClientJobs($client_id,$is_finished = 0)
	{
		$Q="SELECT * FROM $this->db_name.jobs WHERE client_id='$client_id'";

		//...... Make sure we only get the "Finished" jobs.
		if ($is_finished)
			$Q .= " AND finished NOT IN('0000-00-00 00:00:00',NULL)";

		$res = mysql_query($Q);

		while($job = mysql_fetch_assoc($res))
		{
            //...... Get stast for each job
            $jstats = $this->getJobStats($job['id']);
			
			$job['isRunning']   = $jstats['isRunning'];
			$job['rawtime']		= $jstats['time'];
			$job['time']		= $this->time_left($jstats['time']);
			$job['revenue']		= $jstats['revenue'];

			$job['numNotes'] = $this->getNumNotes($job['id']);
			$job['numTasks'] = $this->getNumTasks($job['id']);

			$jobs[] = $job;
		}

		return($jobs);
	}

	function archiveTask($task_id)
	{
		$Q="UPDATE $this->db_name.tasks SET archive=NOW() WHERE id='$task_id' LIMIT 1";
		mysql_query($Q);
		return(mysql_affected_rows());
	}

	function getNumTasks($job_id)
	{
		$Q="SELECT COUNT(*) as tasks FROM $this->db_name.tasks WHERE job_id='$job_id'";
		list($tasks) = mysql_fetch_row(mysql_query($Q));

		return($tasks);
	}

	function getNumNotes($job_id)
	{
		$Q="SELECT COUNT(*) as notes 
			FROM $this->db_name.notes 
			WHERE job_id='$job_id'";
		list($numNotes) = mysql_fetch_row(mysql_query($Q));

		return($numNotes);
	}

	function getJobNotes($job_id)
	{
		$Q="SELECT email,notes,datePosted,notes.id,notes.user_id
			FROM $this->db_name.notes,$this->db_name.users 
			WHERE job_id='$job_id' 
			AND user_id=users.id 
			ORDER BY datePosted DESC";

		$nres = mysql_query($Q);
		while($note = mysql_fetch_assoc($nres))
		{
			$note['notes'] = htmlentities(stripslashes($note[notes]));
			$notes[] = $note;
		}

		return($notes);
	}

	function setJobLastBilled($date,$job_id)
	{
		$Q="UPDATE $this->db_name.jobs SET lastBilled='$date' WHERE id='$job_id' LIMIT 1";
		mysql_query($Q);
		return(mysql_affected_rows());
	}

	function countOpenJobs($client_id)
	{
		$Q="SELECT COUNT(*) FROM $this->db_name.jobs WHERE client_id='$client_id' AND finished='0'";
		list($numJobsOpen) = mysql_fetch_row(mysql_query($Q));

		return($numJobsOpen);
	}

	function countClosedJobs($client_id)
	{
		$Q="SELECT COUNT(*) FROM $this->db_name.jobs WHERE client_id='$client_id' AND finished > '0'";
		list($numJobsClosed) = mysql_fetch_row(mysql_query($Q));
		
		return($numJobsClosed);
	}

	function getSharedClients($owner_id)
	{
		$Q="SELECT * from clientShare WHERE share_owner_id='$owner_id'";
		$shares = mysql_query($Q);

		while($client = mysql_fetch_assoc($shares))
		{
			$clients[] = $client;	
		}
		return($clients);
	}

	function getAllClients($owner_id)
	{
		//..... Gets shared clients list
		$sharedClients = $this->getSharedClients($owner_id);

		if (is_array($sharedClients))
		{
			$js=" OR id IN( ";
			foreach($sharedClients as $info)
			{
				$js .= "'$info[client_id]',";
			}
			$js = substr($js,0,strlen($js) - 1);
			$js .= ")";
		}

		$Q="SELECT *
			FROM $this->db_name.clients
			WHERE user_id='$_SESSION[id]'
			$js
			ORDER BY clientDesc";

		$res = mysql_query($Q);

		while($clientInfo = mysql_fetch_assoc($res))
		{
			$clientStats = $this->getClientStats($clientInfo['id']);
			$clientStats['numJobs'] = intval($clientStats['numJobsOpen']) + intval($clientStats['numJobsClosed']);
			$clientInfo['shared'] =  $this->isSharedClient($clientInfo['id'],$owner_id);
			$clients[] = array_merge($clientStats,$clientInfo);
		}

		return($clients);
	}

	function getClientInfo($client_id)
	{
		$Q="SELECT * FROM $this->db_name.clients WHERE id='$client_id' LIMIT 1";
		$info = mysql_fetch_assoc(mysql_query($Q));
		print mysql_error();
		return($info);
	}

	function getClientStats($client_id)
	{
		$job_time		= 0;
		$job_revenue	= 0;


		//...... Go through each job this client has
		$Q="SELECT id,rate FROM $this->db_name.jobs WHERE client_id='$client_id'";
		$jres = mysql_query($Q);
		
		//..... Get job info / stats / Totals
		while($jobInfo = mysql_fetch_assoc($jres))
		{
			//...... Get stast for each job
			$jstats = $this->getJobStats($jobInfo['id']);
			
			$isRunning			+= intval($jstats['isRunning']);
			$total_job_revenue	+= floatval($jstats['revenue']);
			$total_job_time		+= $jstats['time'];
		}

		$clientInfo['isRunning']	= $isRunning;
		$clientInfo['revenue']		= $total_job_revenue;
		$clientInfo['time']			= $total_job_time;
		$clientInfo['totalTime']	= $this->time_left($total_job_time);

		$clientInfo['numJobsOpen']		+= $this->countOpenJobs($client_id);
		$clientInfo['numJobsClosed']	+= $this->countClosedJobs($client_id);

		return($clientInfo);
	}

	function getJobStats($id)
	{
		$Q="SELECT rate FROM $this->db_name.jobs WHERE id='$id' LIMIT 1";
		list($rate) = mysql_fetch_row(mysql_query($Q));
		print mysql_error();

		$Q="SELECT punchIn,punchOut FROM $this->db_name.tasks WHERE job_id='$id' AND (punchIn > 0)";
		$revres = mysql_query($Q);

		while(list($punchIn,$punchOut) = mysql_fetch_row($revres))
		{
			$isRunning += ($punchIn > 0 && $punchOut == 0) ? 1 : 0;
			if ($punchOut == 0) $punchOut = time();

			$jtime = ($punchOut - $punchIn);
			$rev = (intval($jtime) / 60 / 60) * floatval($rate);

			$jobRevenue    += floatval($rev);
			$jobTime       += intval($jtime);
		}
		return(array('revenue'=>$jobRevenue,'time'=>$jobTime,'isRunning'=>$isRunning));
	}

	function getUserInfo($user_id)
	{
		$Q="SELECT * FROM $this->db_name.users WHERE id='$user_id' LIMIT 1";
		$userInfo = mysql_fetch_assoc(mysql_query($Q));

		return($userInfo);
	}

	function getJobTotals($job_id)
	{
		$Q="SELECT rate FROM $this->db_name.jobs WHERE id='$job_id' LIMIT 1";
		list($rate) = mysql_fetch_row(mysql_query($Q));

	    $Q="SELECT punchIn,punchOut FROM $this->db_name.tasks 
			WHERE job_id='$job_id' 
			ORDER BY punchDesc ASC";

		$res = mysql_query($Q);

		while($task = mysql_fetch_assoc($res))
		{
			if ($task['punchIn'] > 0 && $task['punchOut'] == 0)
			{
				$task_time += time() - $task['punchIn'];
			}
			else 
			{
				$task_time += $task['punchOut'] - $task['punchIn'];
			}

		}

		$totals['revenue'] = ($task_time / 60 / 60)  * $rate;
		$totals['rawtime'] = $task_time;
		$totals['time']    = $this->time_left($task_time);

		return($totals);
	}

	function getTaskList($job_id)
	{
	    $Q="SELECT * FROM $this->db_name.tasks 
			WHERE job_id='$job_id' 
			ORDER BY punchDesc ASC";

		$res = mysql_query($Q);

		while($task = mysql_fetch_assoc($res))
		{
			$task['ownerInfo'] = $this->getUserInfo($task['user_id']);
			$task['isRunning'] = ($task['punchIn'] > 0 && $task['punchOut'] == 0) ? 1 : 0;
			$task['punchDesc'] = htmlentities(stripslashes($task['punchDesc']));

			if ($task['isRunning']) 
			{
				$task['time'] = $this->time_left(time() - $task['punchIn']);
			}
			else 
			{
				$task['time'] = $this->time_left($task['punchOut'] - $task['punchIn']);
			}

			$tasks[] = $task;
		}
		return($tasks);
	}

	function parseTime($timeStr)
	{
		preg_match('/([0-9].*)D/i',$timeStr,$days);
		$days = $days[1];
		$timeStr = preg_replace('/([0-9].*)D/i','',$timeStr);
		list($hours,$minutes,$seconds) = explode(":",$timeStr);

		$totalTime += 86400 * intval($days);
		$totalTime += 3600 * intval($hours);
		$totalTime += 60 * intval($minutes);
		$totalTime += intval($seconds);

		return(abs($totalTime));
	}

	function time_left($seconds)
	{
		$days = intval($seconds / 86400);
		$rem = fmod($seconds,86400);

		$hours = sprintf("%02d",intval($rem / 3600));
		$rem = fmod($rem,3600);

		$minutes = sprintf("%02d",intval($rem / 60));
		$rem = fmod($rem,60);

		$seconds = sprintf("%02d",intval($rem));
		return (array('days'=>$days,'hours'=>$hours,'minutes'=>$minutes,'seconds'=>$seconds));
	}

}

?>
