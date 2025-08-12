<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?PHP
$to = "your@email.com";

$msg .= "This message has been sent from your Contact Form\n\n";
$msg .= "Name: $name\n";
$msg .= "Email: $email\n";
$msg .= "Website: $website\n";
$msg .= "Address 1: $address1\n";
$msg .= "Address 2: $address2\n";
$msg .= "City: $city\n";
$msg .= "State: $state\n";
$msg .= "Zip Code: $zip\n";
$msg .= "Country: $country\n";
$msg .= "Phone: $phone\n";
$msg .= "Fax: $fax\n";
$msg .= "Message: $comment\n"; 

$headers = "From: $email" . "\r\n" .
   "Reply-To: $email" . "\r\n" .
   "X-Mailer: PHP/" . phpversion();

mail($to, "Contact Form", $msg, $headers);
?> 

</body>
</html>

