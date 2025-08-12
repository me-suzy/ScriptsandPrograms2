<?php

include ("settings.php");

class db {

 var $numberOfRows = 0;
 var $dbGet = array();

 function cr($varName){ //check request
 	if(isset($_REQUEST[$varName])){
	return $_REQUEST[$varName];
	}elseif(isset($this->dbGet[$varName])){
	return $this->dbGet[$varName];
	}else{
	return "";
	}
 }
 
 function execQuery($queryString){
   $errorCode = "0";
  $link = mysql_connect($GLOBALS["dbserver"], $GLOBALS["dbuser"], $GLOBALS["dbpass"]) or   $errorCode = mysql_error();
     mysql_select_db($GLOBALS["dbname"]) or $errorCode = mysql_error();
  /* Performing SQL query */
  $result = mysql_query($queryString) or $errorCode = mysql_error();
  /* Closing connection */
	   if ($errorCode <> "0"){
	   $error = $errorCode;
	   $GLOBALS['error'][0] = $error;
	   return 0;
	   }else{
	   return $result;
	   }
  }

  
    function checkNameSpace($nameToCheck,$errorMessage){
  $charsallowed='.1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (!ereg("^[a-zA-Z]",$nameToCheck))){
   array_push($GLOBALS['error'],$errorMessage);
  } 
  }
  
  function checkNames($nameToCheck,$errorMessage){
  $charsallowed='.1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_ -';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (!ereg("^[a-zA-Z]",$nameToCheck))){
   array_push($GLOBALS['error'],$errorMessage);
  } 
  }

  function checkTyped($nameToCheck,$errorMessage){
  if (strlen($nameToCheck) < 1){
  	array_push($GLOBALS['error'],$errorMessage);
	return false;
   }else{
   	return true;
	}
  }
  
   function checkLen($nameToCheck,$len,$errorMessage){
  if (strlen($nameToCheck) > $len){
  	array_push($GLOBALS['error'],$errorMessage);
   }
  }
  
  	 function compareTwo($nameToCheck,$nameToCheck2,$errorMessage){
  if (trim($nameToCheck) <> trim($nameToCheck2)){
  	array_push($GLOBALS['error'],$errorMessage);
   }
  } 
  
  function checkEmail($nameToCheck,$errorMessage){
  $charsallowed='1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-.@';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (strlen($nameToCheck) < 1)){
  	array_push($GLOBALS['error'],$errorMessage);
   }
}

 function checkFileTypes($nameToCheck,$errorMessage){
	   foreach($GLOBALS['forbidden_filetypes'] as $value){
	    if (strstr($nameToCheck,$value) !== false){
			  	array_push($GLOBALS['error'],$errorMessage);
		}
	   }
   }
   
      function checkParent(){
   
   if(isset($_COOKIE['parent'])){
   $logged = $_COOKIE['parent'];
   }else{
   $logged = $this->code();
   }
   
	$result = $GLOBALS['db']->execQuery("select student_id from students where security_code = '".($logged)."'");

	 if(mysql_num_rows($result)>0){
	 return mysql_result($result,0,"student_id");
	 }else{
	 return 0;
	 }
 	}
	
	function checkTeacher(){
   
   if(isset($_COOKIE['teacher'])){
   $logged = $_COOKIE['teacher'];
   }else{
   $logged = $this->code();
   }
   
	$result = $GLOBALS['db']->execQuery("select teacher_id from teachers where security_code = '".($logged)."'");

	 if(mysql_num_rows($result)>0){
	 return mysql_result($result,0,"teacher_id");
	 }else{
	 return 0;
	 }
 	}
   
   function checkUser(){
   
   if(isset($_COOKIE['loggedin'])){
   $logged = $_COOKIE['loggedin'];
   }else{
   $logged = $this->code();
   }
   
	$result = $GLOBALS['db']->execQuery("select teacher_id from teachers where security_code = '".($logged)."'");

	 if(mysql_num_rows($result)>0){
	 return mysql_result($result,0,"teacher_id");
	 }else{
	 return 0;
	 }
 	}
	
	function validate_email($email)
{

   // Create the syntactical validation regular expression
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

   // Presume that the email is invalid
   $valid = false;

   // Validate the syntax
   if (eregi($regexp, $email))
   {
      list($username,$domaintld) = split("@",$email);
         $valid = true;
   } else {
      $valid = false;
   }

   return $valid;

 }
 
 function code($length=9, $list="23456789ABCEFGHJKLMNPQRSTUVWXYZ"){
		mt_srand((double)microtime()*1000000);
		$thoughtstring="";
		if($length>0){
		while(strlen($thoughtstring)<$length){
		$thoughtstring.=$list[mt_rand(0, strlen($list)-1)];
		}
		}
			
			$result = $GLOBALS['db']->execQuery("select security_code from teachers where security_code = '".$thoughtstring."'");
			if (mysql_num_rows($result)){$thoughtstring=$this->code();}
			
			
			$result = $GLOBALS['db']->execQuery("select security_code from students where security_code = '".$thoughtstring."'");
			if (mysql_num_rows($result)){$thoughtstring=$this->code();}
			
			
		return $thoughtstring;
		
	}
	
function insertComments(){

	foreach($_POST as $key=>$value){
		
		if(!is_array($value)){
	 	if(strlen(strstr($key,"seg_"))>0 and strlen(trim($value))>0){
		$GLOBALS['db']->execQuery("update segments set parent_comments = '".$value."'
		where segment_id =  '".substr($key,4)."'");
		}
		}
	}
	
	return true;
}	
	
function updateViewed(){

	$result = $GLOBALS['db']->execQuery("select distinct reports.report_id from reports, students, segments where reports.report_id = segments.report_id and students.security_code = '".trim($_COOKIE['parent'])."' and students.student_id = reports.student_id and viewed < '2000-01-01'");
	$reportids = "0";
	while ($row = mysql_fetch_assoc($result)) {
	$reportids .= "," . $row['report_id'];
	}
	$GLOBALS['db']->execQuery("update reports set viewed = now() where report_id in (".$reportids.")");
	
}

function floodControl(){
	
	/* Flood Control */

$ip = getenv("REMOTE_ADDR"); // IP Address of User
$floodTime = "20"; // Time in seconds before user may post again
$result = $GLOBALS['db']->execQuery("SELECT flood_id FROM floodlog WHERE ip_address = '$ip' AND flood_stamp > (NOW() - INTERVAL '". $floodTime . "' SECOND)");
		while($f=mysql_fetch_array($result))
		{
		//they are flooding!!!
	
		//put new IP and Time into db
		$GLOBALS['db']->execQuery("insert into floodlog (ip_address, flood_stamp, flooding) values ('".$ip."',now(),'1')");
	
		return true;	
		}
	
	//put new IP and Time into db
	$GLOBALS['db']->execQuery("insert into floodlog (ip_address, flood_stamp, flooding) values ('".$ip."',now(),'0')");
	
	return false;
	
	
}

function getHackAttempts(){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select * from floodlog where flooding = '1'");
		$i = 0;
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['ip'] = $row['ip_address'];
		$reportArray[$i]['stamp'] = $row['flood_stamp'];
		$i++;
		}
		return $reportArray;
}

function login(){
					
				$result = $GLOBALS['db']->execQuery("select * from teachers where  passcode = '" . strtolower(trim(urldecode($_POST['password']))) . "'");
					if(mysql_num_rows($result) > 0){
					//make sure they have user level greater than 0 (confirmed)
					
					setcookie("loggedin", mysql_result($result,0,"security_code"));
					
					return true;
					}else{
					//not in the database
					//implement flood control...
					
					$GLOBALS['error'][0] = 'That password does not exist\n\nPlease try again';
				return false;
				}					
		}

	function checkLogin(){
	
		if (isset($_POST['submit'])){
		
		$GLOBALS['db']->CheckTyped($_POST['password'],"you must enter a password");
		
			if(count($GLOBALS['error'])==0){
			//form is correct - try to log in
			$this->login();
					if(count($GLOBALS['error'])==0){
					//they are in the database - return true
					return true;
			
					}else{
					
					return false;
					
					}
			}
		}
	
	}
	
	function checkTeacherLogin(){
	
					if(isset($_REQUEST['c'])){
					$c = $_REQUEST['c'];
					}else{
					$c = $this->code();
					}
	
					$result = $GLOBALS['db']->execQuery("select security_code from teachers where security_code = '" . strtolower(trim(urldecode($c))) . "'");
					if(mysql_num_rows($result) > 0){
					setcookie("teacher", mysql_result($result,0,"security_code"));
					setcookie("loggedin", mysql_result($result,0,"security_code"));
					return true;
					}else{
					//not in the database
					$GLOBALS['error'][0] = 'Your code is invalid';
				return false;
				}					
	}
	
	
	function checkParentLogin(){
	
					if(isset($_REQUEST['c'])){
					$c = $_REQUEST['c'];
					}else{
					$c = $this->code();
					}
	
					$result = $GLOBALS['db']->execQuery("select security_code from students where security_code = '" . strtolower(trim(urldecode($c))) . "'");
					if(mysql_num_rows($result) > 0){
					setcookie("parent", mysql_result($result,0,"security_code"));
					return true;
					}else{
					//not in the database
					$GLOBALS['error'][0] = 'Your code is invalid';
				return false;
				}					
	}
	
	
	function editUser(){
	$GLOBALS['db']->execQuery("update members set first_name = '".trim($_POST['first_name'])."' , last_name = '".trim($_POST['last_name'])."', email='".trim($_POST['email'])."' , pass='".trim($_POST['pass'])."' , bday = '0000/".$_POST['month'].'/'.$_POST['day']."', address1 = '".trim($_POST['address1'])."', address2 = '".trim($_POST['address2'])."', city = '".trim($_POST['city'])."', state='".trim($_POST['state'])."', country = '".trim($_POST['country'])."', postal_code = '".trim($_POST['postal_code'])."' , phone='".trim($_POST['phone'])."' , fax='".trim($_POST['fax'])."', cell='".trim($_POST['cell'])."' , chat_id ='".trim($_POST['chat_id'])."', work_phone = '".trim($_POST['work_phone'])."', second_email = '".trim($_POST['second_email'])."' where code = '" . strtolower(trim(urldecode($_COOKIE['loggedin']))) . "'");
	}
	
	function getUser(){
	$result = $GLOBALS['db']->execQuery("select distinct teacher_id , first_name , last_name , email , passcode, security_code from teachers where security_code = '" . strtolower(trim(urldecode($_COOKIE['loggedin']))) . "'");
	$userArray = array();
	if(mysql_num_rows($result)==0){
	$GLOBALS['error'][0] = "You have been logged out due to technical problems";
	return 0;
	}else{
	$userArray['teacher_id']=mysql_result($result,0,"teacher_id");
	$userArray['first_name']=mysql_result($result,0,"first_name");
	$userArray['last_name']=mysql_result($result,0,"last_name");
	$userArray['email']=mysql_result($result,0,"email");
	$userArray['passcode']=mysql_result($result,0,"passcode");
	$userArray['security_code']=mysql_result($result,0,"security_code");
	return $userArray;
	}
	}
	
	function graduate(){
	$header = "";
	$data = "";
	
	//grab all the data to get ready to archive it.
	$queryStatement = "select reports.report_id,students.parent_emails,date_format(teacher_sent_date,'%m.%d.%y') as teacher_sent_date,date_format(parent_sent_date,'%m.%d.%y') as parent_sent_date,reports.viewed,segments.segment_id,date_format(week,'%m.%d.%y') as week,week as sort_week,semesters.name,semesters.semester_id,students.student_id,students.first_name,students.last_name,students.grade,students.details,segments.assignmentsYN,segments.behaviorYN,segments.comments,teachers.teacher_id,teachers.first_name as tfirst,teachers.last_name as tlast,disciplines.discipline from reports,students,semesters,teachers,segments,disciplines where segments.report_id = reports.report_id and segments.discipline_id = disciplines.discipline_id and students.student_id = reports.student_id and semesters.semester_id = reports.semester_id and teachers.teacher_id = segments.teacher_id order by students.grade asc, students.last_name asc";

	$result = $GLOBALS['db']->execQuery($queryStatement);
	$i=0;
	while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['segment_id'] = $row['segment_id'];
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
		switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray[$i]['grade'] = $row['grade'].$add;
		$reportArray[$i]['semester'] = $row['name'];
		$reportArray[$i]['semester_id'] = $row['semester_id'];
		$reportArray[$i]['week'] = $row['week'];
		$reportArray[$i]['report_id'] = $row['report_id'];
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['behavior'] = $row['behaviorYN'];
		$reportArray[$i]['assignments'] = $row['assignmentsYN'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$reportArray[$i]['tfirst'] = $row['tfirst'];
		$reportArray[$i]['tlast'] = $row['tlast'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$reportArray[$i]['tfull'] = $reportArray[$i]['tfirst']." ".$row['tlast'];
		$reportArray[$i]['comments'] = $row['comments'];
		$reportArray[$i]['discipline'] = $row['discipline'];
		$reportArray[$i]['viewed'] = $row['viewed'];
		$reportArray[$i]['teacher_sent_date'] = $row['teacher_sent_date'];
		$reportArray[$i]['parent_sent_date'] = $row['parent_sent_date'];
		$reportArray[$i]['sort_week'] = $row['sort_week'];
		$reportArray[$i]['parent_emails'] = $row['parent_emails'];
	  	 $i++;	
		}
		
		foreach ($reportArray[0] as $colname => $key){
			    $header .= ($colname)."\t";
			}
			
			foreach($reportArray as $datum){
			  $line = '';
			  foreach($datum as $value){
			    if(!isset($value) || $value == ""){
			      $value = "\t";
			    }else{
			# important to escape any quotes to preserve them in the data.
			      $value = str_replace('"', '""', $value);
			# needed to encapsulate data in quotes because some data might be multi line.
			# the good news is that numbers remain numbers in Excel even though quoted.
			      $value = '"' . $value . '"' . "\t";
			    }
			    $line .= $value;
			  }
			  $data .= trim($line)."\n";
			}
			# this line is needed because returns embedded in the data have "\r"
			# and this looks like a "box character" in Excel
			  $data = str_replace("\r", "", $data);
			
			
			# Nice to let someone know that the search came up empty.
			# Otherwise only the column name headers will be output to Excel.
			if ($data == "") {
			  $data = "\nno matching records found\n";
			}
			$stamp = strtotime("now"); 
			$daquery = "insert into archives (archive_name,file_name) values ('".date("F d Y",$stamp)."','a".$stamp.".xls')";
			$fp = fopen("a".$stamp.".xls", "w") or die("Couldn't create new file"); 
			$numBytes = fwrite($fp, $header."\n".$data); 
			fclose($fp);
			$GLOBALS['db']->execQuery($daquery);
		
	
	$resstud = $GLOBALS['db']->execQuery("select student_id,grade from students order by grade");
		$ress = $GLOBALS['db']->execQuery("select bottom_grade,top_grade from grades");
				
				while ($row = mysql_fetch_assoc($resstud)) {
					if($row['grade'] == mysql_result($ress,0,"top_grade")){
					$GLOBALS['db']->execQuery("delete from students where student_id = ".$row['student_id']);
					}else{
					$newGrade = $row['grade'];
					$newGrade++;
					$GLOBALS['db']->execQuery("update students set grade = ".$newGrade." where student_id = ".$row['student_id']);
					}
				}
				
		$GLOBALS['db']->execQuery("delete from segments"); 			
		$GLOBALS['db']->execQuery("delete from reports"); 
		$GLOBALS['db']->execQuery("delete from students_subjects"); 
		
		}

	function getLatestReportDate(){
	$result = $GLOBALS['db']->execQuery("select max(week) as week from reports");
	return mysql_result($result,0,"week");
	}
	
	function getCountTeachersSentByDate($week='0000-00-00'){
	$result = $GLOBALS['db']->execQuery("select count(distinct teacher_id)
	as cnt
	from reports, segments where reports.report_id = segments.report_id
	and reports.week = '".$week."'");
	return mysql_result($result,0,"cnt");
	}
	
	function getStudentsSentByDate($week='0000-00-00'){
	$result = $GLOBALS['db']->execQuery("select count(distinct student_id)
	as cnt
	from reports where reports.week = '".$week."'");
	return mysql_result($result,0,"cnt");
	}
	
	function javascript_format($str){
	$new_str = '';
	for($i = 0; $i < strlen($str); $i++) {
		$new_str .= '\x' . dechex(ord(substr($str, $i, 1)));
	}

	return $new_str;
	
	}
	
	function getArchives(){
			$reportArray = array();
			$user = $this->getUser();
			if(trim($user['passcode'])<>""){
			$result = $GLOBALS['db']->execQuery("select * from archives order by file_name asc");
			$i=0;
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['archive_id'] = $row['archive_id'];
		$reportArray[$i]['archive_name'] = $row['archive_name'];
		$reportArray[$i]['file_name'] =  $row['file_name'];
	  	 $i++;	
		}
		}
		return $reportArray;
	}
	
	function getStudentsSubjects($studentID){
		$reportArray = array();
			$result = $GLOBALS['db']->execQuery("select student_id,discipline_id,teacher_id from students_subjects where student_id = ".$studentID);
			$i=0;
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['discipline_id'] = $row['discipline_id'];
		$reportArray[$i]['teacher_id'] =  $row['teacher_id'];
	  	 $i++;	
		}
		return $reportArray;
	}
	
	function getOneStudent($id){
	
	$reportArray = array();
	$result = $GLOBALS['db']->execQuery("select students.student_id,students.first_name,inactive,students.parent_emails,students.last_name,students.grade,students.details from students where student_id = ".$id);
	if(mysql_num_rows($result)>0){
	$reportArray['student_id'] = mysql_result($result,0,"student_id");
	$reportArray['first_name'] = mysql_result($result,0,"first_name");
	$reportArray['last_name'] = mysql_result($result,0,"last_name");
	$reportArray['parent_emails'] = mysql_result($result,0,"parent_emails");
	$reportArray['detals'] = mysql_result($result,0,"details");
	$reportArray['grade'] = mysql_result($result,0,"grade");
	$reportArray['inactive'] = mysql_result($result,0,"inactive");
	}
	return $reportArray;
	}

	function getActiveStudents(){
			$result = $GLOBALS['db']->execQuery("select students.student_id,students.first_name,inactive,students.parent_emails,students.last_name,students.grade,students.details from students where inactive <> 1 or inactive is null order by grade,last_name");
			$i=0;
			$reportArray = array();
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
			switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray[$i]['grade_full'] = $row['grade'].$add;
		$reportArray[$i]['grade'] = $row['grade'];
		$reportArray[$i]['create_id'] = $row['student_id'];
		$reportArray[$i]['details'] = $row['details'];
		$reportArray[$i]['parent_emails'] = $row['parent_emails'];
		$reportArray[$i]['inactive'] = $row['inactive'];
	  	 $i++;	
		}
		return $reportArray;
		}
	
	
	function getStudents(){
			$result = $GLOBALS['db']->execQuery("select students.student_id,students.first_name,inactive,students.parent_emails,students.last_name,students.grade,students.details from students order by grade,last_name");
			$i=0;
			$reportArray = array();
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
			switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray[$i]['grade_full'] = $row['grade'].$add;
		$reportArray[$i]['grade'] = $row['grade'];
		$reportArray[$i]['create_id'] = $row['student_id'];
		$reportArray[$i]['details'] = $row['details'];
		$reportArray[$i]['parent_emails'] = $row['parent_emails'];
		$reportArray[$i]['inactive'] = $row['inactive'];
	  	 $i++;	
		}
		
		return $reportArray;
	}

		function insertStudent(){
		$GLOBALS['db']->CheckTyped($_POST['first_name'],"you must enter a first name");
		$GLOBALS['db']->CheckTyped($_POST['last_name'],"you must enter a last name");
		if(count($GLOBALS['error'])>0){return false;}
		$code = $this->code();
		if(isset($_REQUEST['inactive'])){$instr = "1";}else{$instr = "0";}
		
		$GLOBALS['db']->execQuery("insert into students (first_name,last_name,parent_emails,grade,details,inactive,security_code) values('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['parent_emails']."', ".$_POST['grade'].",'".$_POST['details']."',".$instr.",,'".$code."')");
		
		

		$id = mysql_insert_id(); 
		

		foreach($_POST as $key => $value){
				if((substr_count($key, "d_")>0) and ($value<>'')){
						$GLOBALS['db']->execQuery("insert into students_subjects(discipline_id,teacher_id,student_id) values(".substr($key,2).",".$value.",".$id.")");
				}
			
		}
		return true;

		}
	
	
	function insertSubject(){
		$GLOBALS['db']->CheckTyped($_POST['discipline'],"you must enter a subject");
		if(count($GLOBALS['error'])>0){return false;}
		$GLOBALS['db']->execQuery("insert into disciplines(discipline) values ('".$_POST['discipline']."')");
		return true;
		}
		
		
		
	function getSubjects(){
			$result = $GLOBALS['db']->execQuery("select discipline from disciplines order by discipline");
			$i=0;
			$reportArray = array();
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['discipline'] = $row['discipline'];
		$i++;	
		}
		
		return $reportArray;
	}
	
		function flushLog(){
		
		$GLOBALS['db']->execQuery("delete from floodlog where 1=1");
		
		}
	
		function updateStudent($id){
		
		$GLOBALS['db']->CheckTyped($_POST['first_name'],"you must enter a first name");
		$GLOBALS['db']->CheckTyped($_POST['last_name'],"you must enter a last name");
		
		if(count($GLOBALS['error'])>0){return false;}
		
		if(isset($_REQUEST['inactive'])){$instr = "1";}else{$instr = "0";}
		
		
		$GLOBALS['db']->execQuery("update students set first_name='".$_POST['first_name']."', last_name='".$_POST['last_name']."',inactive = ".$instr.", parent_emails='".$_POST['parent_emails']."',grade = ".$_POST['grade'].",  details='".$_POST['details']."' where student_id=".$id);


		$GLOBALS['db']->execQuery("delete from students_subjects where student_id = ".$id);
		
		foreach($_POST as $key => $value){
				if((substr_count($key, "d_")>0) and ($value<>'')){
						$GLOBALS['db']->execQuery("insert into students_subjects(discipline_id,teacher_id,student_id) values(".substr($key,2).",".$value.",".$id.")");
				}
			
		}
		
		}

		function deleteStudent($id){
		
		$GLOBALS['db']->execQuery("delete from students where student_id = ".$id);
		$GLOBALS['db']->execQuery("delete from students_subjects where student_id = ".$id);
		$result = $GLOBALS['db']->execQuery("select report_id from reports where student_id = ".$id);
		$reportList = "0";
		while ($row = mysql_fetch_assoc($result)){
		$reportList .= ",".$row['report_id'];
		}
		$GLOBALS['db']->execQuery("delete from segments where report_id in(".$reportList.")");
		$GLOBALS['db']->execQuery("delete from reports where student_id = ".$id);
		}
	
		function getTeacherDisciplines($id){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select discipline_id from teachers_subjects where teacher_id = ".$id);
		while ($row = mysql_fetch_assoc($result)) {
		array_push($reportArray,$row['discipline_id']);
		}
		return $reportArray;
		}
		
		function getAllTeacherDisciplines(){
		$reportArray = array();
		$i=0;
		$result = $GLOBALS['db']->execQuery("select distinct teachers.first_name, teachers.last_name, discipline_id, teachers.teacher_id from teachers_subjects, teachers where teachers.teacher_id = teachers_subjects.teacher_id");
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['discipline_id'] = $row['discipline_id'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$i++;
		}
		return $reportArray;
		}

		function updateReports(){
		foreach($_POST as $key=>$value){
		if(!is_array($value)){
	 	if(strlen(strstr($key,"a_"))>0 and strlen(trim($value))>0){
		$GLOBALS['db']->execQuery("update segments set assignmentsYN = '".$value."'
		where segment_id =  '".substr($key,2)."'");
		}
		if(strlen(strstr($key,"b_"))>0 and strlen(trim($value))>0){
		$GLOBALS['db']->execQuery("update segments set behaviorYN = '".$value."'
		where segment_id =  '".substr($key,2)."'");
		}
		if(strlen(strstr($key,"c_"))>0 and strlen(trim($value))>0){
		$GLOBALS['db']->execQuery("update segments set comments = '".$value."'
		where segment_id =  '".substr($key,2)."'");
		}
		}
		}
		}
		
		function updateTeacher($id){
		
		$GLOBALS['db']->CheckTyped($_POST['first_name'],"you must enter a name");
		$GLOBALS['db']->CheckTyped($_POST['last_name'],"you must enter a name");
		$GLOBALS['db']->CheckTyped($_POST['email'],"you must enter an email");
		
		if(count($GLOBALS['error'])>0){return false;}
		
		$GLOBALS['db']->execQuery("update teachers set first_name='".$_POST['first_name']."',last_name='".$_POST['last_name']."',email='".$_POST['email']."',passcode = '".$_POST['passcode']."' where teacher_id=".$id);

		$GLOBALS['db']->execQuery("delete from teachers_subjects where teacher_id = ".$id);
		
		foreach($_POST as $key => $value){
				if((substr_count($key, "d_")>0) and ($value<>'')){
					$GLOBALS['db']->execQuery("insert into teachers_subjects(discipline_id,teacher_id) values(".$value.",".$id.")");
				}
			
			}
		}
		
		function insertTeacher(){
		
		$GLOBALS['db']->CheckTyped($_POST['first_name'],"you must enter a name");
		$GLOBALS['db']->CheckTyped($_POST['last_name'],"you must enter a name");
		$GLOBALS['db']->CheckTyped($_POST['email'],"you must enter an email");
		
		if(count($GLOBALS['error'])>0){return false;}
		$code = $this->code();
		$GLOBALS['db']->execQuery("insert into teachers(first_name,last_name,email,passcode,security_code) values('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['email']."','".$_POST['passcode']."','".$code."')");
		
		$insert_id = mysql_insert_id(); 
		
		foreach($_POST as $key => $value){
				if((substr_count($key, "d_")>0) and ($value<>'')){
					$GLOBALS['db']->execQuery("insert into teachers_subjects(discipline_id,teacher_id) values(".$value.",".$insert_id.")");
				}
			
			}
			
			return true;
		}
		
		function deleteTeacher($id){
			$GLOBALS['db']->execQuery("delete from teachers where teacher_id = ".$id);		
			$GLOBALS['db']->execQuery("delete from teachers_subjects where teacher_id = ".$id);		
			$GLOBALS['db']->execQuery("delete from students_subjects where teacher_id = ".$id);
			$GLOBALS['db']->execQuery("delete from segments where teacher_id = ".$id);			
		}
		
				function deleteSubject($id){
			$GLOBALS['db']->execQuery("delete from disciplines where discipline_id = ".$id);		
			$GLOBALS['db']->execQuery("delete from teachers_subjects where discipline_id = ".$id);		
			$GLOBALS['db']->execQuery("delete from students_subjects where discipline_id = ".$id);
			$GLOBALS['db']->execQuery("delete from segments where discipline_id = ".$id);			
		}
	
		function getTeachers(){
			$result = $GLOBALS['db']->execQuery("select teachers.teacher_id,passcode,teachers.email,teachers.last_name,teachers.first_name from teachers order by last_name");
			$i=0;
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$reportArray[$i]['passcode'] = $row['passcode'];
		$reportArray[$i]['email'] = $row['email'];
	  	 $i++;	
		}
		return $reportArray;
	}
		function getNumberOfSubjects(){
		$result = $GLOBALS['db']->execQuery("select count(discipline_id) as cnt from disciplines");
		return mysql_result($result,0,"cnt");
		}
	
		function getLatestReportWeek(){
		$result = $GLOBALS['db']->execQuery("select max(week) as week from reports");
		return mysql_result($result,0,"week");
		}
		
		function getWeeksAndSemesters(){
		$reportArray = array();
		$i=0;
		$result = $GLOBALS['db']->execQuery("select distinct week,semesters.semester_id,name from reports,semesters where reports.semester_id = semesters.semester_id order by week desc");
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['week'] = $row['week'];
		$reportArray[$i]['semester_id'] = $row['semester_id'];
		$reportArray[$i]['name'] = $row['name'];
		$i++;
		}
		return $reportArray;
		}
	
		function getReportSimple(){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select report_id,students.student_id,first_name,last_name,viewed,semesters.name,grade,date_format(week,'%m.%d.%y') as week,date_format(parent_sent_date,'%m.%d.%y') as parent_sent_date,reports.week as week_sort,reports.access_code,date_format(teacher_sent_date,'%m.%d.%y') as teacher_sent_date from reports,students,semesters where students.student_id = reports.student_id and semesters.semester_id = reports.semester_id order by week_sort desc, last_name asc");
			$i=0;
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['report_id'] = $row['report_id'];
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
				switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray[$i]['grade'] = $row['grade'].$add;
		$reportArray[$i]['full_name'] =  $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['semester'] = $row['name'];
		$reportArray[$i]['week'] = $row['week'];
		$reportArray[$i]['viewed'] = $row['viewed'];
		$reportArray[$i]['parent_link'] = "<a href='".$GLOBALS['rootURL']."/parent.php?c=".$row['access_code']."&donotupdate=1'>go</a>";
		$reportArray[$i]['teacher_sent_date'] = $row['teacher_sent_date'];
		$reportArray[$i]['parent_sent_date'] = $row['parent_sent_date'];
		$reportArray[$i]['week_sort'] = $row['week_sort'];
	  	 $i++;	
		}
		return $reportArray;
	}

	function updateSegment(){
	$GLOBALS['db']->execQuery("update segments set behaviorYN = '".$_POST['behavior']."',
	assignmentsYN = '".$_POST['assignments']."',
	comments = '".$_POST['comments']."'
	 where segment_id = ".$_REQUEST['segment_id']);
	return true;
	}
	
	function getSegment(){
	
	$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select reports.report_id,date_format(teacher_sent_date,'%m.%d.%y') as teacher_sent_date,date_format(parent_sent_date,'%m.%d.%y') as parent_sent_date,reports.viewed,segments.segment_id,date_format(week,'%m.%d.%y') as week,week as sort_week,semesters.name,semesters.semester_id,students.student_id,students.first_name,students.last_name,students.grade,segments.assignmentsYN,segments.behaviorYN,segments.comments,teachers.teacher_id,teachers.first_name as tfirst,teachers.last_name as tlast,disciplines.discipline from reports,students,semesters,teachers,segments,disciplines where segments.report_id = reports.report_id and segments.discipline_id = disciplines.discipline_id and students.student_id = reports.student_id and semesters.semester_id = reports.semester_id and teachers.teacher_id = segments.teacher_id and segments.segment_id = ".$_REQUEST['segment_id']);
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray['segment_id'] = $row['segment_id'];
		$reportArray['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray['first_name'] = $row['first_name'];
		$reportArray['last_name'] =  $row['last_name'];
		switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray['grade'] = $row['grade'].$add;
		$reportArray['semester'] = $row['name'];
		$reportArray['semester_id'] = $row['semester_id'];
		$reportArray['week'] = $row['week'];
		$reportArray['report_id'] = $row['report_id'];
		$reportArray['student_id'] = $row['student_id'];
		$reportArray['behavior'] = $row['behaviorYN'];
		$reportArray['assignments'] = $row['assignmentsYN'];
		$reportArray['teacher_id'] = $row['teacher_id'];
		$reportArray['tfirst'] = $row['tfirst'];
		$reportArray['tlast'] = $row['tlast'];
		$reportArray['teacher_id'] = $row['teacher_id'];
		$reportArray['tfull'] = $reportArray['tfirst']." ".$row['tlast'];
		$reportArray['comments'] = $row['comments'];
		$reportArray['discipline'] = $row['discipline'];
		$reportArray['viewed'] = $row['viewed'];
		$reportArray['teacher_sent_date'] = $row['teacher_sent_date'];
		$reportArray['parent_sent_date'] = $row['parent_sent_date'];
		$reportArray['sort_week'] = $row['sort_week'];
		}
		return $reportArray;

	
	}
	
	function getSemesters(){
			$reportArray = array();
			$result = $GLOBALS['db']->execQuery("select semesters.semester_id,name,date_format(begins_on,'%m.%d.%y') as begins_on,date_format(begins_on,'y-m-d') as begins_unformatted,date_format(ends_on,'y-m-d') as ends_unformatted, date_format(ends_on,'%m.%d.%y') as ends_on,ends_on as e_for_sort from semesters order by e_for_sort asc");
			$i=0;
	
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['edit_semester_id'] = $row['semester_id'];
		$reportArray[$i]['delete_semester_id'] = $row['semester_id'];
		$reportArray[$i]['semester_id'] = $row['semester_id'];
		$reportArray[$i]['name'] = $row['name'];
		$reportArray[$i]['begins_on'] =  $row['begins_on'];
		$reportArray[$i]['ends_on'] = $row['ends_on'];
		$reportArray[$i]['begins_unformatted'] =  $row['begins_unformatted'];
		$reportArray[$i]['ends_unformatted'] = $row['ends_unformatted'];
	  	 $i++;	
		}
		return $reportArray;
	}
	
	function getScheduleDates(){
	$semesters = $this->getSemesters();
	$schedule = array();
	for($x=0;$x<=count($semesters);$x++){
	 	$schedule[$x]['start']=="";
		//finish later!!
	}
	
	}
	
	function insertSemester(){
	
		if($GLOBALS['db']->checkTyped($_POST['name'],"you must enter a semester name such as \'first semester\'")){
		$GLOBALS['db']->execQuery("insert into semesters (name,begins_on,ends_on) values('".$_REQUEST['name']."','".$_REQUEST['byear']."-".$_REQUEST['bmonth']."-".$_REQUEST['bday']."',
	'".$_REQUEST['eyear']."-".$_REQUEST['emonth']."-".$_REQUEST['eday']."')");
		return true;
		}else{
		return false;
		}	
	}
	
		function getWeekOf($dte=""){
		// this here function gets the week (week of monday april 3 fer exampul) partna.
		
		if($dte == ""){
		$dayOfWeek = date("w");
		$timestamp = strtotime("now");
		}
		else
		{
		$dayOfWeek = date("w",$dte);
		$timestamp = strtotime($dte);
		}
		
		switch($dayOfWeek){
		
		case 0:
		$newTime = strtotime("next Monday");
		break;
		
		case 1:
		$newTime = strtotime("now");
		break;
		
		case 2:
		$newTime = strtotime("last Monday");
		break;
		
		case 3:
		$newTime = strtotime("last Monday");
		break;
		
		case 4:
		$newTime = strtotime("last Monday");
		break;
		
		case 5:
		$newTime = strtotime("last Monday");
		break;
		
		case 6:
		$newTime = strtotime("next Monday");
		break;
		
		}
		
		return $newTime;
	
	}
	
	function getOneSemester($id){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select semesters.semester_id,name,date_format(begins_on,'%m') as bmonth, date_format(begins_on,'%Y')as byear,date_format(begins_on,'%d') as bday,date_format(ends_on,'%m') as emonth,date_format(ends_on,'%d') as eday, date_format(ends_on,'%Y') as eyear from semesters where semester_id = ".$id);
		if(mysql_num_rows($result)>0){
		$reportArray['semester_id'] = mysql_result($result,0,"semester_id");
		$reportArray['name'] = mysql_result($result,0,"name");
		$reportArray['bmonth'] = mysql_result($result,0,"bmonth");
		$reportArray['byear'] = mysql_result($result,0,"byear");
		$reportArray['bday'] = mysql_result($result,0,"bday");
		$reportArray['emonth'] = mysql_result($result,0,"emonth");
		$reportArray['eday'] = mysql_result($result,0,"eday");
		$reportArray['eyear'] = mysql_result($result,0,"eyear");
		}
		return $reportArray;			
	}
	
		function getOneTeacher($id){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select * from teachers where teacher_id = ".$id);
		if(mysql_num_rows($result)>0){
		$reportArray['teacher_id'] = mysql_result($result,0,"teacher_id");
		$reportArray['first_name'] = mysql_result($result,0,"first_name");
		$reportArray['last_name'] = mysql_result($result,0,"last_name");
		$reportArray['email'] = mysql_result($result,0,"email");
		$reportArray['passcode'] = mysql_result($result,0,"passcode");
		}
		return $reportArray;			
	}
	
	function getOneDiscipline($id){
		$reportArray = array();
		$result = $GLOBALS['db']->execQuery("select discipline_id,discipline from disciplines where discipline_id = ".$id);
		if(mysql_num_rows($result)>0){
		$reportArray['discipline_id'] = mysql_result($result,0,"discipline_id");
		$reportArray['discipline'] = mysql_result($result,0,"discipline");
		}
		return $reportArray;			
	}
	
	function updateDiscipline($id){
		$GLOBALS['db']->execQuery("update disciplines set 
	    discipline = '".$_REQUEST['discipline']."'
		where discipline_id = ".$id);
	}
	
	function updateSemester($id){
		$GLOBALS['db']->execQuery("update semesters set 
	    name = '".$_REQUEST['name']."',
		begins_on = '".$_REQUEST['byear']."-".$_REQUEST['bmonth']."-".$_REQUEST['bday']."',
		ends_on = '".$_REQUEST['eyear']."-".$_REQUEST['emonth']."-".$_REQUEST['eday']."'
		where semester_id = ".$id);
	}
	
	function getCurrentSemester(){
	$result = $GLOBALS['db']->execQuery("select semesters.semester_id,name from 	semesters where begins_on < now() and ends_on > now()");
	$semArray = array();
	if(mysql_num_rows($result)>0){
	$semArray['semester_id'] = mysql_result($result,0,"semester_id");
	$semArray['name'] = mysql_result($result,0,"name");
	}else{
	$semArray['semester_id'] = 0;
	$semArray['name'] = "no current semester";
	}
	
	return $semArray;
	}
	
	function deleteSemester($id){
		$GLOBALS['db']->execQuery("delete from semesters where semester_id = ".$id);
	}
	
	function deleteReport(){
		$GLOBALS['db']->execQuery("delete from reports where report_id = ".$_REQUEST['report_id']);
		$GLOBALS['db']->execQuery("delete from segments where report_id = ".$_REQUEST['report_id']);
	}
	
	function getDisciplines(){
		$result = $GLOBALS['db']->execQuery("select discipline_id,discipline from disciplines order by discipline");
		$i=0;
		while ($row = mysql_fetch_assoc($result)) {
		$reportArray[$i]['discipline_id'] = $row['discipline_id'];
		$reportArray[$i]['discipline'] = $row['discipline'];
	  	 $i++;
		}
		return $reportArray;
	}
	
	
	function sendReports(){
	
	
			if(isset($_REQUEST['teacher_send'])){
			//first create the reports...
			
			if(isset($_POST['stdnts'])){
			$students = $_POST['stdnts'];
			}else{
			$students=array();
			}
			
			$newReports = array();
			array_push($newReports,0);
	$semArray = $this->getCurrentSemester();
	foreach ($students as $studmuffin){	
	$GLOBALS['db']->execQuery("insert into reports(student_id,week,semester_id) values(".$studmuffin.",'".date("Y").'/'.date("m").'/'.date("d")."',".$semArray['semester_id'].")");

			$insert_id = mysql_insert_id(); 
			$courseKids = $GLOBALS['db']->execQuery("select discipline_id,teacher_id from students_subjects where student_id = ".$studmuffin);

			//if there are no classes for this kid - delete his/her report
			
			if(mysql_num_rows($courseKids)==0){
			
			$GLOBALS['db']->execQuery("delete from reports where report_id =".$insert_id);
			}
			
			
			while ($myrow = mysql_fetch_array($courseKids, MYSQL_ASSOC)) {
			$GLOBALS['db']->execQuery("insert into 	segments(discipline_id,teacher_id,report_id) values(".$myrow['discipline_id'].",".$myrow['teacher_id'].",".$insert_id.")");
			}
			
			array_push($newReports,$insert_id);
		}
		
		$result5 = $GLOBALS['db']->execQuery("select distinct teachers.first_name, teachers.last_name, teachers.security_code, teachers.email from teachers,reports,segments where segments.teacher_id = teachers.teacher_id and segments.report_id = reports.report_id and reports.report_id in (".implode(",",$newReports).")");

			while ($row = mysql_fetch_assoc($result5)) {
			$email_templates = $GLOBALS['db']->getTemplates();
			$subject = "New ".$GLOBALS['school']." Progress Reports";
	 		$message = $row['first_name'].":<br><br>
			
			".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($email_templates['teacher']),ENT_QUOTES))."<br>
<br>
You can access the progress report by entering this address into your browser:<br>
<br>
<a href=http://".$_SERVER['SERVER_NAME']."/t.php?c=".$row['security_code'].">http://".$_SERVER['SERVER_NAME']."/t.php?c=".$row['security_code']."</a><br>
<br>
			";
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From: ".$GLOBALS['school']." <do-not-respond@yahoo.com>\r\n"; 
			
			
			if (mail($row['email'], $subject, $message,$headers)){
				$GLOBALS['db']->execQuery("update reports set teacher_sent_date = now() where  reports.report_id = ".$insert_id);		}else{
				$GLOBALS['error'][0] = 'email was not sent - mailserver is not configured';
				}
				
			
				
			}
		}elseif(isset($_REQUEST['parent_send'])){
		
			if(isset($_POST['stdnts'])){
			$students = $_POST['stdnts'];
			}else{
			$students=array();
			}
		
			$result5 = $GLOBALS['db']->execQuery("select distinct students.parent_emails, students.security_code, reports.report_id from students,reports where students.student_id = reports.student_id and reports.parent_sent_date is null and students.student_id in (".implode(",",$students).")");

				while ($row = mysql_fetch_assoc($result5)) {
			$email_templates = $GLOBALS['db']->getTemplates();
			$subject = "New ".$GLOBALS['school']." Progress Reports";
	 		$message = str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($email_templates['parent']),ENT_QUOTES))."<br>
<br>
You can access the progress report by entering this address into your browser:<br>
<br>
<a href=http://".$_SERVER['SERVER_NAME']."/p.php?c=".$row['security_code'].">http://".$_SERVER['SERVER_NAME']."/p.php?c=".$row['security_code']."</a><br>
<br>
			";
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From: ".$GLOBALS['school']." <do-not-respond@yahoo.com>\r\n"; 
			
			
			if (mail($row['parent_emails'], $subject, $message,$headers)){
				$GLOBALS['db']->execQuery("update reports set parent_sent_date = now() where  reports.report_id = ".$row['report_id']);		}else{
				$GLOBALS['error'][0] = 'email was not sent - mailserver is not configured';
				}
			
			}
	
		}
	
	}
	
	
	function getReports($weekArg=""){
	
	$reportArray = array();
	$sems = $this->getSemesters();
	$x = count($sems);
	if($x==0){
	$GLOBALS['error'][0] = "The system will not function until the administrator creates semesters";
	$lastSem = 0;
	}else{
	$lastSem = $sems[$x-1];
	}
	
	$queryStatement = "select reports.report_id,date_format(teacher_sent_date,'%m.%d.%y') as teacher_sent_date,date_format(parent_sent_date,'%m.%d.%y') as parent_sent_date,reports.viewed,segments.segment_id,date_format(week,'%m.%d.%y') as week,week as sort_week,semesters.name,segments.parent_comments,semesters.semester_id,students.student_id,students.first_name,students.last_name,students.grade,students.details,segments.assignmentsYN,segments.behaviorYN,segments.comments,teachers.teacher_id,teachers.first_name as tfirst,teachers.last_name as tlast,disciplines.discipline from reports,students,semesters,teachers,segments,disciplines where segments.report_id = reports.report_id and segments.discipline_id = disciplines.discipline_id and students.student_id = reports.student_id and semesters.semester_id = reports.semester_id and teachers.teacher_id = segments.teacher_id ";

			if($weekArg <> ""){$queryStatement.=" and week ='".$weekArg."'";
						}
			if(isset($_COOKIE['teacher'])){$queryStatement.=" and teachers.security_code ='".$_COOKIE['teacher']."' and segments.teacher_id = teachers.teacher_id ";
			}
			if(isset($_COOKIE['parent'])){$queryStatement.=" and students.security_code ='".$_COOKIE['parent']."'";
			}
			if((isset($_REQUEST['teacher_id'])) and ($_REQUEST['teacher_id'] <>'')){$queryStatement.=" and teachers.teacher_id =".$_REQUEST['teacher_id'];
			}
			
			if((isset($_REQUEST['grade'])) and ($_REQUEST['grade'] <>'')){$queryStatement.=" and students.grade =".$_REQUEST['grade'];
			}
			
			if((isset($_REQUEST['discipline_id'])) and ($_REQUEST['discipline_id'] <>'')){$queryStatement.=" and disciplines.discipline_id =".$_REQUEST['discipline_id'];
			}
			
			if((isset($_REQUEST['student_id'])) and ($_REQUEST['student_id'] <> '')){$queryStatement .=" and students.student_id=".$_REQUEST['student_id'];
			}
			
			if(isset($_REQUEST['month'])){$queryStatement .= " and month(week)  >= ".$_REQUEST['month']." and year(week) >= ".$_REQUEST['year'] ." and month(week)  <= ".$_REQUEST['month2']." and year(week) <= ".$_REQUEST['year2'];
			}
			
			if(isset($_REQUEST['semester_id']) and $_REQUEST['semester_id'] <> 'all'){$queryStatement .=" and semesters.semester_id = ".$_REQUEST['semester_id'];
			}
			
			$queryStatement .= " order by sort_week desc, grade asc, last_name asc";
			
			$result = $GLOBALS['db']->execQuery($queryStatement);
			
			$i=0;
		$segmentArray = array();
		while ($row = mysql_fetch_assoc($result)) {
		
		$reportArray[$i]['segment_id'] = $row['segment_id'];
		
		
		$reportArray[$i]['full_name'] = $row['first_name']." ".$row['last_name'];
		$reportArray[$i]['first_name'] = $row['first_name'];
		$reportArray[$i]['last_name'] =  $row['last_name'];
		switch ($row['grade']){
			case "1":$add = "st";
			break;
			case "2":$add = "nd";
			break;
			case "3":$add = "rd";
			break;
			default : $add = "th";			
			}
		$reportArray[$i]['grade'] = $row['grade'].$add;
		$reportArray[$i]['semester'] = $row['name'];
		$reportArray[$i]['semester_id'] = $row['semester_id'];
		$reportArray[$i]['week'] = $row['week'];
		$reportArray[$i]['report_id'] = $row['report_id'];
		$reportArray[$i]['student_id'] = $row['student_id'];
		$reportArray[$i]['behavior'] = $row['behaviorYN'];
		$reportArray[$i]['assignments'] = $row['assignmentsYN'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$reportArray[$i]['tfirst'] = $row['tfirst'];
		$reportArray[$i]['tlast'] = $row['tlast'];
		$reportArray[$i]['teacher_id'] = $row['teacher_id'];
		$reportArray[$i]['tfull'] = $reportArray[$i]['tfirst']." ".$row['tlast'];
		$reportArray[$i]['parent_comments'] = $row['parent_comments'];
		$reportArray[$i]['comments'] = $row['comments'];
		$reportArray[$i]['discipline'] = $row['discipline'];
		$reportArray[$i]['viewed'] = $row['viewed'];
		$reportArray[$i]['teacher_sent_date'] = $row['teacher_sent_date'];
		$reportArray[$i]['parent_sent_date'] = $row['parent_sent_date'];
		$reportArray[$i]['sort_week'] = $row['sort_week'];
	  	 $i++;	
		}
		return $reportArray;	
	}
	
	function getGrades(){
	    $returnArray = array();
		$result = $GLOBALS['db']->execQuery("select bottom_grade,top_grade from grades");
		$returnArray['top_grade'] = mysql_result($result,0,"top_grade");
		$returnArray['bottom_grade'] = mysql_result($result,0,"bottom_grade");
		return $returnArray;
	}
	
		function updateGrades(){
		$GLOBALS['db']->execQuery("update grades set 
	    top_grade = '".$_REQUEST['top_grade']."',
		bottom_grade = '".$_REQUEST['bottom_grade']."'");
	}


	function getTemplates(){
	
	$filename = "template_teacher.tpl";
	$handle = fopen($filename, "r");
	$teacher_message = stripslashes(fread($handle, filesize($filename)));
	fclose($handle);
	
	$filename = "template_parent.tpl";
	$handle = fopen($filename, "r");
	$parent_message = stripslashes(fread($handle, filesize($filename)));
	fclose($handle);

	$messages = array();
	$messages['teacher'] = $teacher_message;
	$messages['parent'] = $parent_message;

	return $messages;

	}

	
	function updateTemplates(){
	
			$parent_filename = 'template_parent.tpl';
			$teacher_filename = 'template_teacher.tpl';
			
			// Let's make sure the file exists and is writable first.
			if (is_writable($teacher_filename)) {
			
			   // In our example we're opening $filename in append mode.
			   // The file pointer is at the bottom of the file
			   if (!$handle = fopen($teacher_filename, 'w')) {
			         echo "Cannot open file ($teacher_filename)";
			         exit;
			   }
			
			   // Write $somecontent to our opened file.
			   if (fwrite($handle, $_POST['teacher_message']) === FALSE) {
			       echo "Cannot write to file ($teacher_filename)";
			       exit;
			   }
			   
			  }
			   
			// Let's make sure the file exists and is writable first.
			if (is_writable($parent_filename)) {
			
			   // In our example we're opening $filename in append mode.
			   // The file pointer is at the bottom of the file
			   if (!$handle = fopen($parent_filename, 'w')) {
			         echo "Cannot open file ($parent_filename) </body></html>";
			         exit;
			   }
			
			   // Write $somecontent to our opened file.
			   if (fwrite($handle, $_POST['parent_message']) === FALSE) {
			       echo "Cannot write to file ($parent_filename) </body></html>";
			       exit;
			   }
			   
			  }
			   
			   fclose($handle);
		
	}

	function show_calendar(){

//MAKE SURE TO INCLUDE THE OVERLIB HTML FILE!
echo('<SCRIPT language="JavaScript" src="j_overlib.js"></SCRIPT>');
echo('<DIV id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;">
	</DIV>');
	
if(!isset($_REQUEST['date'])){ 
   $date = mktime(0,0,0,date('m'), date('d'), date('Y')); 
} else { 
   $date = $_REQUEST['date']; 
} 

$day = date('d', $date); 
$month = date('m', $date); 
$year = date('Y', $date); 

//Get things that are happening


$monthcal = $GLOBALS['db']->execQuery("select quiet_week, date_format(quiet_week,'%H') as dHour,date_format(quiet_week,'%i') as dMin,date_format(quiet_week,'%m') as dMon,date_format(quiet_week,'%d') as dDay,date_format(quiet_week,'%y') as dYear from quiet_weeks order by quiet_week asc");

$dayEvents = array();
$dayEventList = array();

while ($row = mysql_fetch_assoc($monthcal)){
$tdate = mktime($row['dHour'],$row['dMin'],0,$row['dMon'], $row['dDay'], $row['dYear']); 
$ddate = mktime(0,0,0,$row['dMon'], $row['dDay'], $row['dYear']); 
array_push($dayEventList,$ddate);
if(array_key_exists($ddate,$dayEvents))
	{$dayEvents[$ddate] .= "<p>" . date('h',$tdate).":".date('i',$tdate)."".date('a',$tdate)." ".htmlentities($row['quiet_week'],ENT_QUOTES);}
	else
	{$dayEvents[$ddate] = date('h',$tdate).":".date('i',$tdate)."".date('a',$tdate)." ". htmlentities($row['quiet_week'],ENT_QUOTES);}
}

// Get the first day of the month 
$month_start = mktime(0,0,0,$month, 1, $year); 

// Get friendly month name 
$month_name = date('M', $month_start); 

// Figure out which day of the week 
// the month starts on. 
$month_start_day = date('D', $month_start); 

switch($month_start_day){ 
    case "Sun": $offset = 0; break; 
    case "Mon": $offset = 1; break; 
    case "Tue": $offset = 2; break; 
    case "Wed": $offset = 3; break; 
    case "Thu": $offset = 4; break; 
    case "Fri": $offset = 5; break; 
    case "Sat": $offset = 6; break; 
} 

// determine how many days are in the last month. 
if($month == 1){ 
   $num_days_last = cal_days_in_month(0, 12, ($year -1)); 
} else { 
   $num_days_last = cal_days_in_month(0, ($month -1), $year); 
} 
// determine how many days are in the current month. 
$num_days_current = cal_days_in_month(0, $month, $year); 

// Build an array for the current days 
// in the month 
for($i = 1; $i <= $num_days_current; $i++){ 
    $num_days_array[] = $i; 
} 

// Build an array for the number of days 
// in last month 
for($i = 1; $i <= $num_days_last; $i++){ 
    $num_days_last_array[] = $i; 
} 

// If the $offset from the starting day of the 
// week happens to be Sunday, $offset would be 0, 
// so don't need an offset correction. 

if($offset > 0){ 
    $offset_correction = array_slice($num_days_last_array, -$offset, $offset); 
    $new_count = array_merge($offset_correction, $num_days_array); 
    $offset_count = count($offset_correction); 
} 

// The else statement is to prevent building the $offset array. 
else { 
    $offset_count = 0; 
    $new_count = $num_days_array; 
} 

// count how many days we have with the two 
// previous arrays merged together 
$current_num = count($new_count); 

// Since we will have 5 HTML table rows (TR) 
// with 7 table data entries (TD) 
// we need to fill in 35 TDs 
// so, we will have to figure out 
// how many days to appened to the end 
// of the final array to make it 35 days. 


if($current_num > 35){ 
   $num_weeks = 6; 
   $outset = (42 - $current_num); 
} elseif($current_num < 35){ 
   $num_weeks = 5; 
   $outset = (35 - $current_num); 
} 
if($current_num == 35){ 
   $num_weeks = 5; 
   $outset = 0; 
} 
// Outset Correction 
for($i = 1; $i <= $outset; $i++){ 
   $new_count[] = $i; 
} 

// Now let's "chunk" the $all_days array 
// into weeks. Each week has 7 days 
// so we will array_chunk it into 7 days. 
$weeks = array_chunk($new_count, 7); 


// Build the heading portion of the calendar table 
echo "<br><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"230\" class=\"calendar\" align='center'>\n". 
     "<tr>\n". 
     "<td colspan=\"7\" align=\"center\">$month - $year \n". 
     "</td>\n". 
     "<tr>\n". 
     "<td align='center'>S</td><td align='center'>M</td><td align='center'>T</td><td align='center'>W</td><td align='center'>T</td><td align='center'>F</td><td align='center'>S</td>\n". 
     "</tr>\n"; 

// Now we break each key of the array 
// into a week and create a new table row for each 
// week with the days of that week in the table data 

$i = 0; 
foreach($weeks AS $week){ 
       echo "<tr>\n"; 
       foreach($week as $d){ 
         if($i < $offset_count){ 
             $day_link = "$d"; 
             echo "<td align='center'>$day_link</td>\n"; 
         } 
         if(($i >= $offset_count) && ($i < ($num_weeks * 7) - $outset)){ 
            $day_link = "$d"; 
           if($date == mktime(0,0,0,$month,$d,$year)){ 
		   
               echo "<td ".(in_array(mktime(0,0,0,$month,$d,$year),$dayEventList)?" style='font-weight:bold;color:maroon'":" ")." align='center'>$d</td>\n"; 
           } else { 
               echo "<td ".(in_array(mktime(0,0,0,$month,$d,$year),$dayEventList)?" style='font-weight:bold;color:maroon'":" ")." align='center'>$day_link</td>\n"; 
           } 
        } elseif(($outset > 0)) { 
            if(($i >= ($num_weeks * 7) - $outset)){ 
               $day_link = "$d"; 
               echo "<td align='center'>$day_link</td>\n"; 
           } 
        } 
        $i++; 
      } 
      echo "</tr>\n";    
} 

// Close out your table and that's it! 
echo '</table>'; 

}


	
}


?>