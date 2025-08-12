<?php
// check if user is allowed to view this page
//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.


if($userpref->getPref($user->getUsername(), 'lvl') < USER_LVL){
	echo "You are not allowed to view this page";
	return;
}

// save the projectid
if(isset($_GET['projectid'])){
	$_SESSION['projectid'] = $_GET['projectid'];
}

$project = new Project($GLOBALS['db'], $_SESSION['projectid']);
if($project->isError()){
	echo "<h1>The selected project is not valid</h1>";
	echo "Go back an select another project";
	return;
}
$amount = getPollAmount( listProjects( $user->getUsername() ) );
$amountAllowed = $userpref->getPref($user->getUsername(), 'pollamount');

/* Display the edit poll form */
if($_GET['action'] == 'edit'){
	$polls = $project->getPolls();
	while($i = each($polls)){
		if($i['value']->getID() == $_GET['id']){
			$poll = $i['value'];
			break;
		}
	}
	if(!is_a($poll, 'Poll')){
		echo "Poll not found";
	}else{
	?>
		<script type="text/javascript" src="calendar.js"> </script>
		<script type="text/javascript" src="poll.js"> </script>
		
		<form name="create" action="?page=poll&amp;action=save" method="post" onsubmit="return validatePoll()">
			Title:<br/>
			<input name="title" type="text" value="<?php echo $poll->getTitle(); ?>"/><br/>
			
			Vote button text: <br/>
			<input name="vote_text" type="text" value="<?php echo $poll->getVoteText(); ?>"/><br/>
	
			Chart: <br/>
			<select name="chart">
				<?php
				foreach($GLOBALS['graphs'] as $class => $name){
					if($class == $poll->getChartType()){
						echo '<option selected="selected" value="'.$class.'">'.$name.'</option>';
					}else{
						echo '<option value="'.$class.'">'.$name.'</option>';
					}
				}
				?>
			</select><br/>
			<?php
				// get unix timestamp start and end
				list($start, $end) = $poll->getInterval();
				if($start != null && $end != null){
					// convert to readable format
					$start = date('Y-m-d', $start);
					$end = date('Y-m-d', $end); // add one day
				}else{
					$start = "";
					$end = "";
				}
			?>
			Start time: <a href="#" onclick="disableTime('interval')">enable</a><br/>
			<input type="text" name="start" id="start_time" value="<?php echo $start; ?>" <?php echo ($start == "" ? "disabled=\"disabled\"" : "") ?>/>
			<a href="#" onclick="cal.select(document.forms['create'].start,'anchor1','yyyy-MM-dd'); return false;" name="anchor1" id="anchor1"><img src="cal.gif" alt="select"/></a><br/>
			
			Stop time: <a href="#" onclick="disableTime('interval')">enable</a><br/>
			<input type="text" name="stop" id="stop_time" value="<?php echo $end; ?>" <?php echo ($start == "" ? "disabled=\"disabled\"" : "") ?>/>
			<a href="#" onclick="cal.select(document.forms['create'].stop,'anchor1','yyyy-MM-dd'); return false;" name="anchor1" id="anchor1"><img src="cal.gif" alt="select"/></a><br/>
			
			Weekday: <a href="#" onclick="disableTime('periodic')">enable</a><br/>
			<select name="weekday" id="periodic_time" <?php echo $poll->getPeriodic() ? "" : "disabled=\"disabled\"" ?>>
				<option value="none">-- none --</option>
				<?php
					$weekdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
					$day = $poll->getPeriodic();
					
					while($n = each($weekdays)){
						if($n['key'] == $day && $day != null)
							echo '<option selected="selected" value="'.$n['key'].'">'.$n['value'].'</option>';
						else
							echo '<option value="'.$n['key'].'">'.$n['value'].'</option>';
					}
				?>
			</select><br/>
			
			<input name="id" type="hidden" value="<?php echo $poll->getID(); ?>"/>
			
			<input type="submit" name="submit" value="Save project"/>
		</form>
	<?php
	}
}

/* Delete a poll */
if($_GET['action'] == 'delete'){
	$polls = $project->getPolls();
	while($i = each($polls)){
		if($i['value']->getID() == $_GET['id']){
			$poll = $i['value'];
			break;
		}
	}
	if(!is_a($poll, 'Poll')){
		echo "Poll not found";
	}else{
		if(!$poll->delete()){
			$msg = $poll->isError();
			echo "An unforseen error ocured please contact an admin: ".$msg->getText();
		}else{
			unset($poll);
			echo 'The poll is now deleted';
		}
	}
}

/* Show the create poll form */
if($_GET['action'] == 'create'){
	if($amount >= $amountAllowed)
		echo "You can't create any more polls, delete a poll and try again";
	else{
	?>
		<script type="text/javascript" src="calendar.js"> </script>
		<script type="text/javascript" src="poll.js"> </script>
		<form name="create" action="?page=poll&amp;action=save_create" method="post" onsubmit="return validatePoll()">
			Title:<br/>
			<input name="title" type="text"/><br/>
			
			Vote button text: <br/>
			<input name="vote_text" type="text"/><br/>
			
			Chart type: <br/>
			<select name="chart">
				<?php 
					foreach($GLOBALS['graphs'] as $class => $name)
						echo '<option value="'.$class.'">'.$name.'</option>';
				?>
			</select><br/>
			
			Start time: <a href="#" onclick="disableTime('interval')">enable</a><br/>
			<input type="text" name="start" id="start_time"/>
			<a href="#" onclick="cal.select(document.forms['create'].start,'anchor1','yyyy-MM-dd'); return false;" name="anchor1" id="anchor1"><img src="cal.gif" alt="select"/></a><br/>
			
			Stop time: <a href="#" onclick="disableTime('interval')">enable</a><br/>
			<input type="text" name="stop" id="stop_time"/>
			<a href="#" onclick="cal.select(document.forms['create'].stop,'anchor1','yyyy-MM-dd'); return false;" name="anchor1" id="anchor1"><img src="cal.gif" alt="select"/></a><br/>
			
			Weekday: <br/>
			<select name="weekday" disabled="disabled" id="periodic_time">
				<option value="none">-- none --</option>
				<?php
					$weekdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
					
					while($n = each($weekdays)){
						echo '<option value="'.$n['key'].'">'.$n['value'].'</option>';
					}
				?>
			</select><a href="#" onclick="disableTime('periodic')">enable</a><br/>
			<input type="submit" name="submit" value="Create project"/>
		</form>
	<?php
	}
}

/* Create a new poll from the data in create poll form */
if($_GET['action'] == 'save_create'){
	if($amount >= $amountAllowed)
		echo "You can't create any more polls, delete a poll and try again";
	else{
		$poll = new Poll($GLOBALS['db'], null, $_SESSION['projectid']);
		if($poll->isError()){
			$msg = $poll->isError();
			echo "Creation of the poll failed, error message: ".$msg->getText();
		}else{
			if(!$poll->setTitle($_POST['title'])){
				$msg = $poll->isError();
				$error = true;
				echo "The title is not valid, error msg: ".$msg->getText();
			}elseif(!$poll->setVoteText($_POST['vote_text'])){
				$msg = $poll->isError();
				$error = true;
				echo "The vote button text is not valid, error msg: ".$msg->getText();
			}elseif(!$poll->setChartType($_POST['chart'])){
				$msg = $poll->isError();
				$error = true;
				echo "The chart type couldn't be selected, error msg: ".$msg->getText();
			}
			if(!empty($_POST['start']) && !empty($_POST['stop'])){
				// set a periode to show the poll
				// start and end is expected to be in ISO YYYY-MM-DD format
				$start = explode("-", $_POST['start']);
				$start = mktime(0, 0, 0, $start[1], $start[2], $start[0]);
				
				$end = explode("-", $_POST['stop']);
				$end = mktime(0, 0, 0, $end[1], $end[2], $end[0])+24*60*60;
				if($end < $start){
					echo "You can't have a start date after the end date";
					$error = true;
				}elseif(!$poll->selectInterval($start, $end)){
					$msg = $poll->isError();
					$error = true;
					echo "The interval could not be set, error msg: ".$msg->getText();
				}
			}elseif(!empty($_POST['weekday'])){
				// set a weekday to display the poll
				if(!$poll->selectPeriodic($_POST['weekday'])){
					$msg = $poll->isError();
					$error = true;
					echo "The weekday could not be set, error msg: ".$msg->getText();
				}
			}
			if($error){
				// creation of the poll failed, so we delete it
				if(!$poll->delete()){
					$msg = $poll->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}else{
					unset($poll);
				}
			}else{
				echo "The poll is now created";
				if(!$poll->save()){
					$msg = $poll->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}
			}
		}
	}
}

/* Save the poll with data from the edit poll form */
if($_GET['action'] == 'save'){
	$polls = $project->getPolls();
	while($i = each($polls)){
		if($i['value']->getID() == $_POST['id']){
			$poll = $i['value'];
			break;
		}
	}
	if(!is_a($poll, 'Poll')){
		echo "Poll not found";
	}else{
		if($poll->isError()){
			$msg = $poll->isError();
			echo "Creation of the poll failed, error message: ".$msg->getText();
		}else{
			if(!$poll->setVoteText($_POST['vote_text'])){
				$msg = $poll->isError();
				$error = true;
				echo "The vote button text is not valid, error msg: ".$msg->getText();
			}elseif(!$poll->setTitle($_POST['title'])){
				$msg = $poll->isError();
				$error = true;
				echo "The chart type is not valid, error msg: ".$msg->getText();
			}elseif(!$poll->setChartType($_POST['chart'])){
				$msg = $poll->isError();
				$error = true;
				echo "The chart type is not valid, error msg: ".$msg->getText();
			}
			
			if(!empty($_POST['start']) && !empty($_POST['stop'])){
				// set a periode to show the poll
				// start and end is expected to be in ISO YYYY-MM-DD format
				$start = explode("-", $_POST['start']);
				$start = mktime(0, 0, 0, $start[1], $start[2], $start[0]);
				
				$end = explode("-", $_POST['stop']);
				$end = mktime(0, 0, 0, $end[1], $end[2], $end[0])+24*60*60;
				if($end < $start){
					echo "You can't have a start date after the end date";
					$error = true;
				}elseif(!$poll->selectInterval($start, $end)){
					$msg = $poll->isError();
					$error = true;
					echo "The interval could not be set, error msg: ".$msg->getText();
				}
			}elseif(!empty($_POST['weekday'])){
				// set a weekday to display the poll
				if(!$poll->selectPeriodic($_POST['weekday'])){
					$msg = $poll->isError();
					$error = true;
					echo "The weekday could not be set, error msg: ".$msg->getText();
				}
			}
			if(!$error){
				if(!$poll->save()){
					$msg = $poll->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}else{
					echo "The poll is now saved";
				}
			}
		}
	}
}
?>

<h1>Polls in <?php echo $project->getTitle(); ?></h1>
<?php
// update the project and amount, if the user created polls the creation would not show up
$project = new Project($GLOBALS['db'], $_SESSION['projectid']);
$amount = getPollAmount( listProjects( $user->getUsername() ) );
$amountAllowed = $userpref->getPref($user->getUsername(), 'pollamount');

if($amount < $amountAllowed){
	echo '<a href="?page=poll&amp;action=create">Create poll</a>';
	echo ' <a href="?page=project">Back to project</a>';
	echo '<hr/>';
}

echo "You have $amount ".(($amount > 1) ? "polls" : "poll")." and is allowed to create $amountAllowed ".(($amountAllowed > 1) ? "polls" : "poll")."<br/>";
echo '<hr/>';

echo '<table style="width:90%;" id="polls"><tr><th>Title</th><th>Questions</th><th>Actions</th><th>Time</th></tr>';
foreach($project->getPolls() as $i){
	echo '<tr>';
	echo '<td>'.$i->getTitle().'</td><td>'.count( $i->getQuestions() ).'</td>';
	echo '<td><a href="?page=poll&amp;action=edit&amp;id='.$i->getID().'">[edit]</a><br/>';
	echo '<a href="?page=question&amp;pollid='.$i->getID().'">[edit&nbsp;questions]</a><br/>';
	echo '<a href="?page=poll&amp;action=delete&amp;id='.$i->getID().'" onclick="return confirm(\'Delete the poll?\')">[delete]</a></td>';
	echo '<td>';
	if(($day = $i->getPeriodic()) != null){
		$weekdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		echo $weekdays[$day]."s";
	}else{
		list($start, $end) = $i->getInterval();
		$start = date('Y-m-d', $start);
		$end = date('Y-m-d', $end);
		echo $start."<br/>to<br/>".$end;
	}
	echo '</td>';
	echo '</tr>';
}

echo '</table>';

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
?>