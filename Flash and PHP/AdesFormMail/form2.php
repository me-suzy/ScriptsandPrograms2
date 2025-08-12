
<html>
<head>
<title>Contact</title>
</head>

<body>

<?PHP
$to = "you@yoursite.com";

$msg .= "This message has been sent from your Contact Form\n\n";
$msg .= "Name: " . $HTTP_POST_VARS["name"] . "\n";
$msg .= "Email: " . $HTTP_POST_VARS["email"] . "\n";
$msg .= "Website: " . $HTTP_POST_VARS["website"] . "\n";
$msg .= "Address 1: " . $HTTP_POST_VARS["address1"] . "\n";
$msg .= "Address 2: " . $HTTP_POST_VARS["address2"] . "\n";
$msg .= "City: " . $HTTP_POST_VARS["city"] . "\n";
$msg .= "State: " . $HTTP_POST_VARS["state"] . "\n";
$msg .= "Zip Code: " . $HTTP_POST_VARS["zip"] . "\n";
$msg .= "Country: " . $HTTP_POST_VARS["country"] . "\n";
$msg .= "Phone: " . $HTTP_POST_VARS["phone"] . "\n";
$msg .= "Fax: " . $HTTP_POST_VARS["fax"] . "\n";
$msg .= "Message: " . $HTTP_POST_VARS["comment"] . "\n";
mail($to, $HTTP_POST_VARS["name"], $msg, "Contact Form\nReply-To:". $HTTP_POST_VARS["email"] . "\n");
?>
</body>
</html>
