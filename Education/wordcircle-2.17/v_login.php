<?php


if(isset($_POST['submit'])){


	$GLOBALS['db']->checkEmail($_POST['email'],"your email address has invalid characters");
	$GLOBALS['db']->checkTyped($_POST['email'],"you must enter a valid email address");
	$GLOBALS['db']->CheckTyped($_POST['password'],"you must enter a password");
	
	if(count($GLOBALS['error'])==0){
	//form is correct - try to log in
	$url = $GLOBALS['user']->login();
		if(count($GLOBALS['error'])==0){
		//they are in the database - send them to the index page
		$GLOBALS['page']->head("wordcircle","","passwords are not case-sensitive");
		$GLOBALS['page']->pleaseWait("Please wait while we log you in",$url);
		include("v_footer.php");
		exit;
		}
	}
	
}
$GLOBALS['page']->head("wordcircle","","passwords are not case-sensitive");
//function tableStart($class,$width,$type,$image="",$linkArray=array())

$GLOBALS['page']->tableStart("","100%","TAB","Login");
$GLOBALS['page']->tableStart("","100%","FORM");
echo("<br>");
$arr[0]['Remember me on this computer']='1';
//function text($value,$name,$class,$desc,$size,$chngeOnPost=0)
$GLOBALS['page']->text("","email","inputs","Enter your email address:",30,1);
$GLOBALS['page']->password("","password","inputs","Enter your password:",30,1);
echo("<tr class='inputs'><td align='right'>Automatic login:</td><td><input type='checkbox' name='agree' value='1' class='inputs' >Remember me on this computer<br></td></tr>");
$GLOBALS['page']->submit("Login","inputs");
$GLOBALS['page']->tableEnd("FORM");
$GLOBALS['page']->tableEnd("TAB");


?>