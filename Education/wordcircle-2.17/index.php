<?php
ob_start();

include ("settings.php");
include("s_classes.php");
$GLOBALS['db'] = new wcdata();
$GLOBALS['page'] = new page();
$GLOBALS['user'] = new user();


if ($GLOBALS['configured'] == false){include("install.php");exit;}

include("v_header.php");

if (isset($_GET['a'])){
	if (($_GET['a']=="login") or ($_GET['a']=='register') or ($_GET['a']=='license') or ($_GET['a']=='help') or ($_GET['a']=='terms')){$ok=1;}
}

if (($GLOBALS['page']->checkSecurity() == false) and (!isset($ok))) {
	$GLOBALS['page']->head("wordcircle course management for teachers and students","","To add someone else&rsquo;s course you must have their course key",0);
	$GLOBALS['page']->tableStart("","100%","TAB","Welcome");
	$GLOBALS['page']->tableStart("","100%","TEXT","");
	$GLOBALS['page']->welcomeMessage();
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br><br>");
}else{

$user = new user();


if (!isset($_GET['a'])){

	include("v_index.php");

} 

elseif ($_GET['a'] == 'login'){

	include("v_login.php");

}

elseif ($_GET['a'] == 'courses'){

	include("v_courses.php");

}

elseif ($_GET['a'] == 'register'){

	include("v_register.php");	
}

elseif ($_GET['a'] == 'remove'){

	include("v_remove.php");
}


elseif ($_GET['a'] == 'help'){

	include("v_help.php");
}

elseif ($_GET['a'] == 'logout'){

	include("v_logout.php");
}

elseif ($_GET['a'] == 'view'){

	include("v_view.php");
}

elseif ($_GET['a'] == 'terms'){

	include("v_terms.php");
}

elseif ($_GET['a'] == 'projects'){

	$GLOBALS['page']->head("wordcircle","","Only course owners create mandatory projects. Anyone can create voluntary projects");

	include("v_projects.php");
}

elseif ($_GET['a'] == 'work'){

	$GLOBALS['page']->head("wordcircle","","Private work can only be seen by you and the course owner - public work can be seen by everyone in the course");

	include("v_work.php");
}

elseif ($_GET['a'] == 'editn'){

	include("v_editn.php");
}

elseif ($_GET['a'] == 'edits'){

	include("v_edits.php");
}

elseif ($_GET['a'] == 'editdoc'){

	include("v_editdoc.php");
}

elseif ($_GET['a'] == 'editgm'){

	include("v_editgm.php");

}

elseif ($_GET['a'] == 'editdiss'){

	include("v_editdiss.php");
}
	
elseif ($_GET['a'] == 'license'){
	$GLOBALS['page']->head("wordcircle","","General Public License",0);
	include("license.txt");
}

elseif ($_GET['a'] == 'thoughts'){
	$GLOBALS['page']->head("wordcircle","","Only the person who created this course can edit and modify thoughts");
		
	include("v_thoughts.php");
}

elseif ($_GET['a'] == 'calendar'){
	$GLOBALS['page']->head("wordcircle","","Only the person who created this course can edit and modify the calendar");
   
	include("v_calendar.php");
}

elseif ($_GET['a'] == 'documents'){
	$GLOBALS['page']->head("wordcircle","","Only the person who created this course can post documents");
		
	include("v_documents.php");
}

elseif ($_GET['a'] == 'discuss'){
$GLOBALS['page']->head("wordcircle","",'Click on the <img src="icon_email.gif" width="16" height="16" alt=""> subscribe option to receive email reminders when new messages are posted');
	
	include("v_discuss.php");
}

elseif ($_GET['a'] == 'members' or $_GET['a']=='admin'){

	if (isset($_GET['gid'])){
	$GLOBALS['page']->head("wordcircle","","You will see everyone&rsquo;s information for all the courses you are a part of");}
	else{
	$GLOBALS['page']->head("wordcircle","","You will see everyone&rsquo;s information for all the courses you are a part of",0);
	}
	
	include("v_members.php");
}

else {

	include("v_index.php");
}


}

include("v_footer.php");



?>