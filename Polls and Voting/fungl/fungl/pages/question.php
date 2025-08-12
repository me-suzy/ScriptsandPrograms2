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
if(isset($_GET['pollid'])){
	$_SESSION['pollid'] = $_GET['pollid'];
}

$poll = new Poll($GLOBALS['db'], $_SESSION['pollid']);
if($poll->isError()){
	echo "<h1>The selected poll is not valid</h1>";
	echo "Go back and select another poll";
	return;
}

if($_GET['action'] == 'create'){
	?>
	<form action="?page=question&amp;action=save_create" method="post">
		Text:<br/>
		<input name="text" type="text"/><br/>
		
		<input type="submit" name="submit" value="Create question"/>
	</form>
	<?php
}
if($_GET['action'] == 'save_create'){
	$question = new Question($GLOBALS['db'], null, $_SESSION['pollid']);
	if($question->isError()){
		$msg = $poll->isError();
		echo "Creation of the question failed, error message: ".$msg->getText();
	}else{
		if(!$question->setText($_POST['text'])){
			$msg = $question->isError();
			$error = true;
			echo "The text is not valid, error msg: ".$msg->getText();
		}
		if($error){
			// creation of the question failed, so we delete it
			if(!$question->delete()){
				$msg = $question->isError();
				echo "An unforseen error ocured please contact an admin: ".$msg->getText();
			}else{
				unset($question);
			}
		}else{
			echo "The question is now created";
			if(!$question->save()){
				$msg = $question->isError();
				echo "An unforseen error ocured please contact an admin: ".$msg->getText();
			}
		}
	}
}

if($_GET['action'] == 'delete'){
	$questions = $poll->getQuestions();
	while($i = each($questions)){
		if($i['value']->getID() == $_GET['id']){
			$question = $i['value'];
			break;
		}
	}
	if(!is_a($question, 'Question')){
		echo "Question not found";
	}else{
		if(!$question->delete()){
			$msg = $question->isError();
			echo "An unforseen error ocured please contact an admin: ".$msg->getText();
		}else{
			unset($question);
			echo 'The question is now deleted';
		}
	}
}

if($_GET['action'] == 'edit'){
	$questions = $poll->getQuestions();
	while($i = each($questions)){
		if($i['value']->getID() == $_GET['id']){
			$question = $i['value'];
			break;
		}
	}
	if(!is_a($question, 'Question')){
		echo "Question not found";
	}else{
	?>
		<form action="?page=question&amp;action=save" method="post">
			Text:<br/>
			<input name="text" type="text" value="<?php echo $question->getText(); ?>"/><br/>
			
			<input name="id" type="hidden" value="<?php echo $question->getID(); ?>"/>
			
			<input type="submit" name="submit" value="Save question"/>
		</form>
	<?php
	}
}

if($_GET['action'] == 'save'){
	$questions = $poll->getQuestions();
	while($i = each($questions)){
		if($i['value']->getID() == $_POST['id']){
			$question = $i['value'];
			break;
		}
	}
	if(!is_a($question, 'Question')){
		echo "Question not found";
	}else{
		if($question->isError()){
			$msg = $question->isError();
			echo "Creation of the question failed, error message: ".$msg->getText();
		}else{
			if(!$question->setText($_POST['text'])){
				$msg = $question->isError();
				$error = true;
				echo "The question text is not valid, error msg: ".$msg->getText();
			}
			if(!$error){
				if(!$question->save()){
					$msg = $question->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}else{
					echo "The question is now saved";
				}
			}
		}
	}
}

// reload poll 
$poll = new Poll($GLOBALS['db'], $_SESSION['pollid']);
?>

<h1>Questions in <?php echo $poll->getTitle(); ?></h1>
<?php
echo '<a href="?page=question&amp;action=create">Create question</a>';
echo ' <a href="?page=poll&amp;projectid='.$poll->getProjectID().'">Back to poll</a>';
echo '<hr/>';

echo '<table style="width:90%" id="questions"><tr><th>Title</th><th>Votes</th><th>Actions</th></tr>';
foreach($poll->getQuestions() as $i){
	echo '<tr>';
	echo '<td>'.$i->getText().'</td><td>'.$i->getVotes().'</td>';
	echo '<td><a href="?page=question&amp;action=edit&amp;id='.$i->getID().'">[edit]</a>';
	echo '<a href="?page=question&amp;action=delete&amp;id='.$i->getID().'" onclick="return confirm(\'Delete this question?\')">[delete]</a></td>';
	echo '</tr>';
}

echo '</table>';

?>