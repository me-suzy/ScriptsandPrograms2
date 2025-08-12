<?
/* Edit these preferences to suit your needs */

  $mailto = 'your@email-address.com'; // insert the email address you want the form sent to
	$returnpage = 'thankyou.html'; // insert the name of the page/location you want the user to be returned to
	$sitename = '[You Site Name]'; // insert the site name here, it will appear in the subject of your email

/* Do not edit below this line unless you know what you're doing */
	
  $name = $_POST['name'];
  $email = $_POST['email'] ;
  $enquiry = stripslashes($_POST['query']);
	
	if (!$name) {
		print("<strong>Error:</strong> Please provide your name.<br/><br/><a href='javascript:history.go(-1)'>Back</a>");
		 exit;
	}
	if (!$email) {
		print("<strong>Error:</strong> Please provide an email address.<br/><br/><a href='javascript:history.go(-1)'>Back</a>");
		 exit;
	}
	if (!$enquiry) {
		print("<strong>Error:</strong> Please provide your enquiry details.<br/><br/><a href='javascript:history.go(-1)'>Back</a>");
		 exit;
	}
	if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $email)){
    print("<strong>Error:</strong> this email address is not in a valid format.<br/><br/><a href='javascript:history.go(-1)'>Back</a>");
		 exit;
    }	
  
  $message = "\n$name submitted the following message:\n\n$enquiry\n\nTheir contact details are as follows:\n\nName: $name\nEmail Address: $email\n\n";

  mail($mailto, "$sitename Contact Form Enquiry from $name", $message, "From: $email");
	header("Location: " . $returnpage);
?>


