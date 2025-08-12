<?
addbstack('', 'Supply news', 'supplynews');
addbstack('', 'Home');

$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "Supply news");		    
$t->set_var("pagetitle", $sitename." - Supply news");

$formt = new Template("./");
$formt->set_file("block", "supplynewsform.html");	    
// general form
$formt->set_block("block", "GFORM","GFORMZ");
// general error form (incorrect url)
$formt->set_block("block", "EFORM","EFORMZ");
// Required field error
$formt->set_block("block", "SFORM","SFORMZ");
// End form
$formt->set_block("block", "OFORM","OFORMZ");
$formt->set_var(array("absolutepath"=>$absolutepath,"absolutepathfull"=>$absolutepathfull ));
$formt->set_var(array ("recipient"=> $supplynewsrecipient));
if($_REQUEST["signupsubmit"]=="true"){
  include("formmail.php");
}
if($signupokay==true){
	$formt->parse("OFORMZ", "OFORM");
}
else {
	$formt->set_var(array("absolutepath"=>$absolutepath,"absolutepathfull"=>$absolutepathfull ));
	if ($formerror==true) {
		$formt->set_var(array ("firstname"=> $_REQUEST["firstname"],
		"error"=>"<br>".implode("<br>",$errors),
		"surname"=> $_REQUEST["surname"],
		"middlename"=> $_REQUEST["middlename"],
		"companyname"=> $_REQUEST["companyname"],
		"companyposition"=> $_REQUEST["companyfunction"],
		"address"=> $_REQUEST["address"],
		"address2"=> $_REQUEST["address2"],
		"address3"=> $_REQUEST["address3"],
		"city"=> $_REQUEST["city"],
		"state"=> $_REQUEST["state"],
		"zip"=> $_REQUEST["zip"],
		"county"=> $_REQUEST["county"],
		"country"=> $_REQUEST["country"],
		"email"=> $_REQUEST["email"],
		"workphone"=> $_REQUEST["workphone"],
		"title"=> $_REQUEST["title"],
		"topic"=> $_REQUEST["topic"],
		"website"=> $_REQUEST["website"],
		"text"=> $_REQUEST["text"],

		));
	$formt->parse("SFORMZ", "SFORM");
	}
	elseif($uarray){
		$formt->set_var(array ("firstname"=> $uarray["firstname"],
		"surname"=> $uarray["lastname"],
		"middlename"=> $uarray["middlename"],
		"companyname"=> $uarray["companyname"],
		"companyposition"=> $uarray["companyfunction"],
		"address"=> $uarray["address"],
		"address2"=> $uarray["address2"],
		"address3"=> $uarray["address3"],
		"city"=> $uarray["city"],
		"state"=> $uarray["state"],
		"zip"=> $uarray["zip"],
		"county"=> $uarray["county"],
		"country"=> $uarray["country"],
		"email"=> $uarray["email"],
		"workphone"=> $uarray["workphone"],
		"homephone"=> $uarray["homephone"],
		));
	}
	if (!isset($uarray)) {
			$formt->set_var(array ("registerlink"=> "&nbsp<b><a href=\"".$absolutepathfull."view/webuser\">Login or register first.</a></b>"));
	}
	$formt->parse("GFORMZ", "GFORM");

}

$formt->parse("b", "block");
$t->set_var("containera", $formt->get("b"));