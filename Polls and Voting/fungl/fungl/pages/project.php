<?php
// check if user is allowed to view this page

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.

if($userpref->getPref($user->getUsername(), 'lvl') < USER_LVL){
	echo "<h1>You are not allowed to view this page</h1>";
	return;
}

$projects = listProjects($user->getUsername());
$amount = count($projects);
$amountAllowed = $userpref->getPref($user->getUsername(), 'projectamount');

/* Show a create project form, or show an error msg if the user 
 * isn't allowed to create more projects
 */
if($_GET['action'] == 'create'){
	if($amount >= $amountAllowed)
		echo "You can't create any more projects, delete a project and try again";
	else{
	?>
		<form action="?page=project&amp;action=save_create" method="post">
			Title:<br/>
			<input name="title" type="text"/><br/>
			
			Site: <br/>
			<input name="site" type="text"/><br/>
			
			<input type="submit" name="submit" value="Create project"/>
		</form>
	<?php
	}
}

/* Show an edit project form 
 * or an error msg if the project isn't found
 */
if($_GET['action'] == 'edit'){
	while($i = each($projects)){
		if($i['value']->getID() == $_GET['id']){
			$project = $i['value'];
			break;
		}
	}
	if(!is_a($project, 'Project')){
		echo "Project not found";
	}else{
	?>
		<form action="?page=project&amp;action=save" method="post">
			Title:<br/>
			<input name="title" type="text" value="<?php echo $project->getTitle(); ?>"/><br/>
			
			Site: <br/>
			<input name="site" type="text" value="<?php echo $project->getSite(); ?>"/><br/>
			<input name="id" type="hidden" value="<?php echo $project->getID(); ?>"/>
			<input type="submit" name="submit" value="Save project"/>
		</form>
	<?php
	}
}

/* Delete a project */
if($_GET['action'] == 'delete'){
	while($i = each($projects)){
		if($i['value']->getID() == $_GET['id']){
			$project = $i['value'];
			break;
		}
	}
	if(!is_a($project, 'Project')){
		echo "Project not found";
	}else{
		if(!$project->delete()){
			$msg = $project->isError();
			echo "An unforseen error ocured please contact an admin: ".$msg->getText();
		}else{
			unset($project);
			echo 'The project is now deleted';
		}
	}
}

/* Save a project that have been submitted by the form
 * shown in the action -- edit block 
 */
if($_GET['action'] == 'save'){
	$project = new Project($GLOBALS['db'], $_POST['id']);
	if($project->isError()){
		$msg = $project->isError();
		echo "Creation of the project failed, error message: ".$msg->getText();
	}else{
		if(!$project->setSite($_POST['site'])){
			$msg = $project->isError();
			$error = true;
			echo "The site is not valid, error msg: ".$msg->getText();
		}elseif(!$project->setTitle($_POST['title'])){
			$msg = $project->isError();
			$error = true;
			echo "The title is not valid, error msg: ".$msg->getText();
		}
		if(!$error){
			if(!$project->save()){
				$msg = $project->isError();
				echo "An unforseen error ocured please contact an admin: ".$msg->getText();
			}else{
				echo "The project is now saved";
			}
		}
	}
}

/* Create a project from the data submitted from the 
 * action -- create form
 */
if($_GET['action'] == 'save_create'){
	if($amount >= $amountAllowed)
		echo "You can't create any more projects, delete a project and try again";
	else{
		$project = new Project($GLOBALS['db']);
		if($project->isError()){
			$msg = $project->isError();
			echo "Creation of the project failed, error message: ".$msg->getText();
		}else{
			if(!$project->setSite($_POST['site'])){
				$msg = $project->isError();
				$error = true;
				echo "The site is not valid, error msg: ".$msg->getText();
			}elseif(!$project->setTitle($_POST['title'])){
				$msg = $project->isError();
				$error = true;
				echo "The title is not valid, error msg: ".$msg->getText();
			}elseif(!$project->setUser($user->getUsername())){
				$msg = $project->isError();
				$error = true;
				echo "Couldn't assign the project to your user: ".$msg->getText();
			}
			if($error){
				// creation of the project failed, so we delete it
				if(!$project->delete()){
					$msg = $project->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}else{
					unset($project);
				}
			}else{
				echo "The project is now created";
				if(!$project->save()){
					$msg = $project->isError();
					echo "An unforseen error ocured please contact an admin: ".$msg->getText();
				}
			}
		}
	}
}

/* Show the iframe code to show the project in a webpage
 */
if($_GET['action'] == 'code'){
	echo 'Copy/paste the code in the box below into your website where you want the poll to show';
	echo '<textarea rows="5" cols="50">';
	?>
	<iframe height="500" marginwidth="0" marginheight="0" src="http://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']) ?>/showpoll.php?id=<?php echo $_GET['id'] ?>">
        <a href="http://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']) ?>/showpoll.php?id=<?php echo $_GET['id'] ?>">funGL poll</a>
    </iframe>
<?php
	echo '</textarea>';
}

// update project data if the user have added or delete a project we will have wrong data
$projects = listProjects($user->getUsername());
$amount = count($projects);
$amountAllowed = $userpref->getPref($user->getUsername(), 'projectamount');
?>

<h1>Projects</h1>
<?php
if($amount < $amountAllowed){
	echo '<a href="?page=project&amp;action=create">Create project</a>';
	echo '<hr/>';
}

echo "You have $amount ".(($amount > 1) ? "projects" : "project")." and is allowed to create $amountAllowed ".(($amountAllowed > 1) ? "projects" : "project")."<br/>";
echo '<hr/>';

echo '<table style="width:90%;" id="projects"><tr><th>Title</th><th>Site</th><th>Actions</th></tr>';
foreach($projects as $i){
	echo '<tr>';
	echo '<td>'.$i->getTitle().'</td><td>'.$i->getSite().'</td>';
	echo '<td><a href="?page=project&amp;action=edit&amp;id='.$i->getID().'">[edit]</a><br/>';
	echo '<a href="?page=poll&amp;projectid='.$i->getID().'">[edit polls]</a><br/>';
	echo '<a href="?page=project&amp;action=delete&amp;id='.$i->getID().'" onclick="return confirm(\'Delete the project?\')">[delete]</a><br/>';
	echo '<a href="?page=project&amp;action=code&amp;id='.$i->getID().'"">[show&nbsp;code]</a></td>';
	echo '</tr>';
}

echo '</table>';


//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
?>