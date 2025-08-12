<?php
ob_start();

include("configure.php");
include("settings.php");
include("template.php");
include("classes.php");

$GLOBALS['db'] = new db();
$GLOBALS['error'] = array();
$t = new Template();


if (isset($_COOKIE['loggedin'])){
$user = $GLOBALS['db']->getUser();
$t->set("user",$user);
	if(trim($user['passcode'])<>""){
		$admin = true;
	}else
		$admin = false;
}


if($GLOBALS['configured'] == false){

	if(isset($_POST['submit'])){
	
	$c = new Configurator();
	$c->configure();
	
		if(count($GLOBALS['error'])==0){
		$t->set("url","index.php");
		$t->set("message","Configuration complete<br><br>Please login after page refreshes");
		$t->set("title","Completing Setup");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_wait.tpl');
		echo $t->fetch('template_footer.tpl');
		
		}else{
		$t->set("title","Setup");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_install.tpl');
		echo $t->fetch('template_footer.tpl');
		}
	
	}else{
	$t->set("title","Setup");
	echo $t->fetch('template_header.tpl');
	echo $t->fetch('template_install.tpl');
	echo $t->fetch('template_footer.tpl');
	
	}

}

if ($GLOBALS['configured'] == true){
if (isset($_GET['a'])){$action=$_GET['a'];}else{$action='';}

switch($action) {


case "help":
include("help.html");
break;

case "login":

	//flood control...
	if(isset($_REQUEST['submit']) or isset($_REQUEST['c'])){
	if($GLOBALS['db']->floodControl()){
	
	$t->set("title","Warning");
		echo $t->fetch("template_header.tpl");
		echo $t->fetch("template_flood.tpl");
		echo $t->fetch("template_footer.tpl");
		break;
	
	}
	}

if($GLOBALS['db']->checkLogin()){
	$t->set("url","index.php");
	$t->set("message","Logging you in");
	$t->set("text","Logging in");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_wait.tpl");
	echo $t->fetch("template_footer.tpl");
	}else{
	$t->set("title","Please Login");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_welcome.tpl");
	echo $t->fetch("template_footer.tpl");
	}
break;

case "logout":
	setcookie ("loggedin", " ", time() - 3600);
	setcookie ("parent", " ", time() - 3600);
	$t->set("url","index.php");
	$t->set("message","Please wait - logging you out of the system");
	$t->set("text","Logging out");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_wait.tpl");
	echo $t->fetch("template_footer.tpl");
	break;
	
case "edit":
	if(isset($_COOKIE['loggedin'])){
	if(isset($_REQUEST['submit'])){
	if($GLOBALS['db']->updateSegment()){
		$t->set("url","index.php");
		$t->set("message","Please wait - updating report info");
		echo $t->fetch("template_header.tpl");
		echo $t->fetch("template_wait.tpl");
		echo $t->fetch("template_footer.tpl");
		break;
		}
	}
	$t->set("reportArray",$GLOBALS['db']->getSegment());
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_edit_report.tpl");
	echo $t->fetch("template_footer.tpl");
	break;
	}else{
	$t->set("title","Welcome");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_welcome.tpl");
	echo $t->fetch("template_footer.tpl");
	break;
	}
	
case "delete":
	if(isset($_COOKIE['loggedin']) and $admin == true){
		$GLOBALS['db']->deleteReport();
		$t->set("url","index.php");
		$t->set("message","Please wait - deleting report");
		echo $t->fetch("template_header.tpl");
		echo $t->fetch("template_wait.tpl");
		echo $t->fetch("template_footer.tpl");
		break;
	}else{
	$t->set("title","Welcome");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_welcome.tpl");
	echo $t->fetch("template_footer.tpl");
	break;
	}
	
case "admin":

	if(isset($_REQUEST['m'])){
		$m = $_REQUEST['m'];
		}else{
		$m = "";
		}

	if($GLOBALS['db']->checkUser() > 0){
	 if($admin == true){
	  $t->set("title","Admin Menu");
	  
	  
	  
	  if(isset($_REQUEST['e_edit'])){
	  
	 	 if(isset($_REQUEST['submit'])){
		 $GLOBALS['db']->updateSemester($_REQUEST['e_edit']);
		
		if(count($GLOBALS['error'])==0){
		$t->set("url","index.php?a=admin&m=semesters");
  		$t->set("message","Please wait - updating semesters");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
		 }
		 }
		 
	  $GLOBALS['db']->dbGet = $GLOBALS['db']->getOneSemester($_REQUEST['e_edit']);
	  
	  }
	  
	  if(isset($_REQUEST['flush_hack'])){
	  
			$t->set("url","index.php?a=admin&m=hack");
			$t->set("message","Please wait - flushing hack log");
			$GLOBALS['db']->flushLog();
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
	  
	  }
	  
	  
	  if(isset($_REQUEST['s_edit'])){
	  
	 	 if(isset($_REQUEST['submit'])){
		 $GLOBALS['db']->updateDiscipline($_REQUEST['s_edit']);
		if(count($GLOBALS['error'])==0){
		$t->set("url","index.php?a=admin&m=subjects");
  		$t->set("message","Please wait - updating subject areas");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
		 }
		 }
		 
	  $GLOBALS['db']->dbGet = $GLOBALS['db']->getOneDiscipline($_REQUEST['s_edit']);
	  
	  }
	  
	  if(isset($_REQUEST['t_edit'])){
	  
	 	 if(isset($_REQUEST['submit'])){
		 $GLOBALS['db']->updateTeacher($_REQUEST['t_edit']);
		if(count($GLOBALS['error'])==0){
		$t->set("url","index.php?a=admin&m=teachers");
  		$t->set("message","Please wait - updating teachers");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
		}
		}
		 
	  $GLOBALS['db']->dbGet = $GLOBALS['db']->getOneTeacher($_REQUEST['t_edit']);
	  
	  }
	  
	  if(isset($_REQUEST['st_edit'])){
	  
	 	 if(isset($_REQUEST['submit'])){
		 $GLOBALS['db']->updateStudent($_REQUEST['st_edit']);
		if(count($GLOBALS['error'] == 0)){
		$t->set("url","index.php?a=admin&m=students");
  		$t->set("message","Please wait - updating students");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
		}		 
		}
		 
	  $GLOBALS['db']->dbGet = $GLOBALS['db']->getOneStudent($_REQUEST['st_edit']);
	  
	  }
	  
	  if(isset($_REQUEST['temp_edit'])){
	   $GLOBALS['db']->updateTemplates();
	  $t->set("url","index.php?a=admin&m=email");
  		$t->set("message","Please wait - updating email templates");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  }
	  
	  if(isset($_REQUEST['sched_edit'])){
	   $GLOBALS['db']->updateSchedule();
	  	$t->set("url","index.php?a=admin&m=schedule");
  		$t->set("message","Please wait - updating schedule");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  
	  if(isset($_REQUEST['e_delete'])){
	   $GLOBALS['db']->deleteSemester($_REQUEST['e_delete']);
	  $t->set("url","index.php?a=admin&m=semesters");
  		$t->set("message","Please wait - deleting semester");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  if(isset($_REQUEST['s_delete'])){
	   $GLOBALS['db']->deleteSubject($_REQUEST['s_delete']);
	  $t->set("url","index.php?a=admin&m=subjects");
  		$t->set("message","Please wait - deleting subject");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  	  if(isset($_REQUEST['t_delete'])){
	   $GLOBALS['db']->deleteTeacher($_REQUEST['t_delete']);
	  $t->set("url","index.php?a=admin&m=teachers");
  		$t->set("message","Please wait - deleting teacher");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	   if(isset($_REQUEST['st_delete'])){
	   $GLOBALS['db']->deleteStudent($_REQUEST['st_delete']);
	  $t->set("url","index.php?a=admin&m=students");
  		$t->set("message","Please wait - deleting student");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  if(isset($_REQUEST['g_edit'])){
	   $GLOBALS['db']->updateGrades();
	  $t->set("url","index.php?a=admin&m=grades");
  		$t->set("message","Please wait - updating grades");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  if(isset($_REQUEST['graduate'])){
	  $GLOBALS['db']->graduate();
	  $t->set("url","index.php?a=admin&m=students");
  		$t->set("message","Please wait - graduating students");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  }

	  if(isset($_REQUEST['et_edit'])){
	   $GLOBALS['db']->updateTemplates();
	  $t->set("url","index.php?a=admin&m=email");
  		$t->set("message","Please wait - updating email templates");
  		echo $t->fetch("template_header.tpl");
  		echo $t->fetch("template_wait.tpl");
  		echo $t->fetch("template_footer.tpl");
  		break;
	  
	  }
	  
	  	//this is in case they are inserting new records...
		if(isset($_REQUEST['submit'])){
	  
	  	switch($m){
		
			case "send":
			$t->set("url","index.php?a=admin");
			if(isset($_REQUEST['parent_send'])){
  			$t->set("message","Please wait - sending reports to parents");
			$GLOBALS['db']->sendReports();
			}else{
			$t->set("message","Please wait - sending reports to teachers");
			$GLOBALS['db']->sendReports();
			}
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
		
			case "semesters":
			if($GLOBALS['db']->insertSemester()){
			$t->set("url","index.php?a=admin&m=semesters");
  			$t->set("message","Please wait - inserting semester");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
			}else{
			$t->set("semesters",$GLOBALS['db']->getSemesters());
	  		echo $t->fetch('template_header.tpl');
	  		echo $t->fetch('template_admin.tpl');
	  		echo $t->fetch('template_footer.tpl');
			exit;
			}
			
			
			case "subjects":
			if($GLOBALS['db']->insertSubject()){
			$t->set("url","index.php?a=admin&m=subjects");
  			$t->set("message","Please wait - inserting subject");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
			}else{
			$t->set("semesters",$GLOBALS['db']->getSubjects());
	  		echo $t->fetch('template_header.tpl');
	  		echo $t->fetch('template_admin.tpl');
	  		echo $t->fetch('template_footer.tpl');
			exit;
			}
			
			
			case "students":
			if($GLOBALS['db']->insertStudent()){
			$t->set("url","index.php?a=admin&m=students");
  			$t->set("message","Please wait - inserting student");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
			}else{
			$t->set("subjectArray",$GLOBALS['db']->getDisciplines());
			$t->set("allTeacherDisciplines",$GLOBALS['db']->getAllTeacherDisciplines());
	  		$t->set("students",$GLOBALS['db']->getStudents());
	if(isset($_REQUEST['st_edit']))$t->set("studentSubjects",$GLOBALS['db']->getStudentsSubjects($_REQUEST['st_edit']));
	  		echo $t->fetch('template_header.tpl');
	  		echo $t->fetch('template_admin.tpl');
	  		echo $t->fetch('template_footer.tpl');
			exit;
			}

			case "teachers":
			if($GLOBALS['db']->insertTeacher()){
			$t->set("url","index.php?a=admin&m=teachers");
  			$t->set("message","Please wait - inserting teacher");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			exit;
			}else{
			$t->set("teachers",$GLOBALS['db']->getTeachers());
	  		if(isset($_REQUEST['t_edit'])){$id = $_REQUEST['t_edit'];}else{$id=0;}
			$t->set("subjectArray",$GLOBALS['db']->getDisciplines());
	 		$t->set("teacherDisciplines",$GLOBALS['db']->getTeacherDisciplines($id));
	  		echo $t->fetch('template_header.tpl');
	  		echo $t->fetch('template_admin.tpl');
	  		echo $t->fetch('template_footer.tpl');
			exit;
			}
		
		}
	  
	  }
	  
	  //default functionality for the admin page follows: 
	  
	  $t->set("grades",$GLOBALS['db']->getGrades());
	   $latest = $GLOBALS['db']->getLatestReportDate();
	   if($latest=="")$latest=date("y-m-d",mktime(0, 0, 0, 1, 1, 1988));
	   $teacherCount = $GLOBALS['db']->getCountTeachersSentByDate($latest);
	   $studentCount = $GLOBALS['db']->getStudentsSentByDate($latest);
	  $t->set("semester",$GLOBALS['db']->getCurrentSemester());
	  $t->set("subjects",$GLOBALS['db']->getNumberOfSubjects());
	  $t->set("subjectArray",$GLOBALS['db']->getDisciplines());
	  $t->set("semesters",$GLOBALS['db']->getSemesters());
	  if($m=="students"){
	  $t->set("allTeacherDisciplines",$GLOBALS['db']->getAllTeacherDisciplines());
	  $t->set("students",$GLOBALS['db']->getStudents());
	  }
	  if($m=="email"){$t->set("messages",$GLOBALS['db']->getTemplates());}
	  if($m=="hack"){$t->set("reports",$GLOBALS['db']->getHackAttempts());} if(isset($_REQUEST['st_edit']))$t->set("studentSubjects",$GLOBALS['db']->getStudentsSubjects($_REQUEST['st_edit']));
	  if($m=="teachers" or $m=="students")$t->set("teachers",$GLOBALS['db']->getTeachers());
	  if(isset($_REQUEST['t_edit'])){$id = $_REQUEST['t_edit'];}else{$id=0;}
	  $t->set("teacherDisciplines",$GLOBALS['db']->getTeacherDisciplines($id));
	  if($m=="sendform")$t->set("students",$GLOBALS['db']->getActiveStudents());
	  $t->set("latest",$latest);
	  $t->set("teacherCount",$teacherCount);
	  $t->set("studentCount",$studentCount);
	  echo $t->fetch('template_header.tpl');
	  echo $t->fetch('template_admin.tpl');
	  echo $t->fetch('template_footer.tpl');
	   break;
	 }else{
		setcookie ("loggedin", " ", time() - 3600);
		$t->set("title","Welcome");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_welcome.tpl');
		echo $t->fetch('template_footer.tpl');
	  break;
		}
		}
			
	
default:

	if(isset($_COOKIE['parent'])){
	if($GLOBALS['db']->checkParent() > 0){
	
	
	if($action=='saveComments' and $GLOBALS['db']->insertComments()){
			$t->set("url","index.php");
  			$t->set("message","Please wait - saving comments");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			break;
	}	
	
	
			if(!isset($_REQUEST['semester_id']) and !isset($_REQUEST['week'])){
			$latestWeek = $GLOBALS['db']->getLatestReportWeek();
			}elseif(isset($_REQUEST['week'])){
			$latestWeek = $_REQUEST['week'];
			}else{
			$latestWeek = "";
			}
		$t->set("semesters",$GLOBALS['db']->getWeeksAndSemesters());
		$t->set("reports",$GLOBALS['db']->getReports($latestWeek));
		$t->set("archives",$GLOBALS['db']->getArchives());
		$t->set("admin",false);
		$t->set("title","Report List");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_myreports.tpl');
		echo $t->fetch('template_footer.tpl');
		break;
		}else{
		setcookie ("loggedin", " ", time() - 3600);
		$t->set("title","Welcome");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_welcome.tpl');
		echo $t->fetch('template_footer.tpl');
		break;
		}
	
	}
	
	if(isset($_COOKIE['teacher']) and $action=="latest"){
		if(!isset($_REQUEST['submit'])){
		$latestWeek = $GLOBALS['db']->getLatestReportWeek();
		$t->set("reports",$GLOBALS['db']->getReports($latestWeek));
		$t->set("title","Latest reports");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_latest.tpl');
		echo $t->fetch('template_footer.tpl');
		break;
		}else{
		$GLOBALS['db']->updateReports();
			$t->set("url","index.php");
  			$t->set("message","Please wait - saving reports");
  			echo $t->fetch("template_header.tpl");
  			echo $t->fetch("template_wait.tpl");
  			echo $t->fetch("template_footer.tpl");
			break;
		}
	}
	
	if(!isset($_COOKIE['loggedin']) and (!isset($_COOKIE['parent']))){
	
	$t->set("title","Welcome");
	echo $t->fetch('template_header.tpl');
	echo $t->fetch('template_welcome.tpl');
	echo $t->fetch('template_footer.tpl');
	
	}else{
	
		if($GLOBALS['db']->checkUser() > 0){
			if(!isset($_REQUEST['semester_id']) and !isset($_REQUEST['week'])){
			$latestWeek = $GLOBALS['db']->getLatestReportWeek();
			}elseif(isset($_REQUEST['week'])){
			$latestWeek = $_REQUEST['week'];
			}else{
			$latestWeek = "";
			}
		$t->set("semesters",$GLOBALS['db']->getWeeksAndSemesters());
		$t->set("reports",$GLOBALS['db']->getReports($latestWeek));
		$t->set("archives",$GLOBALS['db']->getArchives());
		$t->set("admin",$admin);
		$t->set("title","Report List");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_myreports.tpl');
		echo $t->fetch('template_footer.tpl');
		}else{
		if(!isset($_COOKIE['parent'])){
		setcookie ("loggedin", " ", time() - 3600);
		$t->set("title","Welcome");
		echo $t->fetch('template_header.tpl');
		echo $t->fetch('template_welcome.tpl');
		echo $t->fetch('template_footer.tpl');
		}
		}
	}//end if for cookie

}//end switch
}//end if for configured


ob_end_flush()
?>