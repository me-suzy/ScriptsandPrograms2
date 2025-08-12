<?php
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
// create poll
require_once 'config.php';
require_once 'classes/project.php';
require_once 'classes/chart.php';

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.

$project = new Project($GLOBALS['db'], $_GET['id']);
if($project->isError()){
	echo "Project not found";
	return;
}
$poll = $project->selectPoll();
if(!$poll){
	echo "No poll today";
	return;
}

$voted 	= false;
// vote -- if user want to
if(isset($_POST['vote']) && empty($_COOKIE["fungl-vote".$poll->getID()])){
	// set tracking cookie
	setcookie("fungl-vote".$poll->getID(), "1", time()+60*60*24*7); // 1 week
	$voted = true;
	
	// find question
	$questions = $poll->getQuestions();
	while($i = each($questions)){
		if($i['value']->getID() == $_POST['vote']){
			$question = $i['value'];
			break;
		}
	}
	if(!is_a($question, 'Question')){
		echo "Question not found";
	}else{
		$question->addVote();
		$question->save();
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
}
?>
<html>
<head></head>
<body>
<?php
// reload poll
$project = new Project($GLOBALS['db'], $_GET['id']);
$poll = $project->selectPoll();

// show graph or voting options
if(!$voted && empty($_COOKIE['fungl-vote'.$poll->getID()])){
	echo '<h1>'.$poll->getTitle().'</h1>';
	// show questions
	echo '<form action="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/showpoll.php?id='.$_GET['id'].'" method="post">';
	$questions = $poll->getQuestions();
	while($i = each($questions)){
		if(is_a($i['value'], 'Question')){
			echo '<input type="radio" name="vote" value="'.$i['value']->getID().'">'.$i['value']->getText().'<br/>';
		}
	}
	echo '<input type="submit" name="submit" value="'.$poll->getVoteText().'">';
	echo '</form>';
}else{
	echo '<img src="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/external.php?id='.$poll->getID().'"/><br/>';
	echo '<h1>'.$poll->getTitle().'</h1>';
	// echo questions
	$questions = $poll->getQuestions();
	ChartCommon::getColor(true); // reset the colors
	echo '<table>';
	echo '<tr><td colspan="2">Question</td><td>Votes</td></tr>';
	foreach($questions as $n){
		$color = ChartCommon::getColor(); // only use the first color 
		ChartCommon::getColor();
		$color = "#".sprintf('%02X%02X%02X', $color[0], $color[1], $color[2]);
		echo '<tr><td><div style="height:10px;width:10px;background:'.$color.';">&nbsp;</div></td><td>'.$n->getText()."</td><td>".$n->getVotes().'</td></tr>';
		$votes[] = $n->getVotes();
	}
	echo '<tr><td colspan="2">Total votes</td><td>'.array_sum($votes).'</td></tr>';
	echo '</table>';
}

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
?>
</body>
</html>