<?

	include("config.php");
	include("lib/quiz_lib.php");

	if(empty($answer)){
		print "You must chose an answer!";
		exit;
	}
	if(empty($email) && !$email_not_required){
		print "You must enter an email address!";
		exit;
	}

	//- check/set cookie
	setcookie("quiz[$id]", "$id", time()+360000);  	

	if($quiz[$id]>0){
		print "You can't vote twice!";
		exit;
	}

	//- check IP
	if (getenv(HTTP_CLIENT_IP)){ 
		$ip=getenv(HTTP_CLIENT_IP); 
	} else { 
		$ip=getenv(REMOTE_ADDR); 
	}	

	$dbh = @Connect_Database();
	
	$query = "select quiz_id from votes where ip='$ip' and quiz_id=$id";
	$dbc = @mysql_query($query);
	if($row = @mysql_fetch_array($dbc) ){
		print "You can't vote twice!";
		exit;
	}


	//- insert into db
	$query = "insert into votes(quiz_id, email, vote, ip, created) values($id, '$email', $answer, '$ip', NOW())";
	$dbc = @mysql_query($query) 
		or print "Error occurred while processing your entry. Email webmaster about it.";
	

	//- result page

?>

Thank you for your submission!
