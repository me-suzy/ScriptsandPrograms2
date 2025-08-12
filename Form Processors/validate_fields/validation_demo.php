<?php 
include("validation_class.php");
if (isset($_POST['submit'])) {
	$example = new Validate_fields;
	$example->check_4html = true;
	$example->add_text_field("Simple_text", $_POST['string'], "text", "y", 25);
	$example->add_num_field("Number", $_POST['number'], "number", "n", 0, 5);
	$example->add_num_field("Number_with_decimals", $_POST['decimal'], "decimal", "n", 2);
	$example->add_date_field("Euro_date", $_POST['date'], "date", "eu", "n");
	$example->add_link_field("Email_address", $_POST['email'], "email");
	$example->add_link_field("internet_link", $_POST['url'], "url", "n");
	if ($example->validation()) {
		$error = "All form fields are valid!"; // replace this text if you like...
	} else {
		$error = $example->create_msg();
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Form field validation test application</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	text-align:center;
}
p {
	font-size: 14px;
	line-height: 20px;
}
label {
	font: 14px/20px Arial, Helvetica, sans-serif;
	margin-top:10px;
	float:left;
	width: 210px;
}
#main {
	width:640px;
	margin:0 auto;
	padding:10px;
	text-align:left;
	border: 1px solid #000000;
}
input {
	margin-top:10px;
}
-->
</style>
</head>

<body onLoad="javascript:window.resizeTo(700,680);return false;">
<div id="main">
  <h1>Test the validation class...</h1>
  <p>I created this class to get an easy to use form field validation script. Use this class to validate your database inputs or mail forms. Of course there is much more to validate: postcodes, special strings, credit card number etc. In the first version is it (only) possible to validate simple text, numbers, (US)dates and e-mail addresses. Invalid form fields will be reported inside a detailed error message. If all form fields are valid you can use the boolean to submit your form.</p>
  <form action="<?php echo $PHP_SELF; ?>" method="post" style="padding-left:65px;">
    <p><b>Try it by yourself:</b></p>
	<label for="string">Simple text: (required, max. length = 25 chars.)</label>
	<input type="text" name="string" size="20" value="<?php echo (isset($_POST['string'])) ? $_POST['string'] : ""; ?>"><br clear="all">
	<label for="number">An integer (max. digits = 5)</label>
	<input type="text" name="number" size="10" value="<?php echo (isset($_POST['number'])) ? $_POST['number'] : ""; ?>"><br clear="all">
	<label for="decimal">A number with 2 decimals</label>
	<input type="text" name="decimal" size="10" value="<?php echo (isset($_POST['decimal'])) ? $_POST['decimal'] : ""; ?>"><br clear="all">
	<label for="date">EU-date (format = dd-mm-yyyy)</label>
	<input type="text" name="date" size="10" value="<?php echo (isset($_POST['date'])) ? $_POST['date'] : ""; ?>"><br clear="all">
	<label for="email">E-mail address (required)</label>
	<input type="text" name="email" size="25" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ""; ?>">
	<br clear="all">
	<label for="url">Hyperlink (ends with: file.htm or /folder/val/ or just a regular url) </label>
	<input type="text" name="url" size="45" value="<?php echo (isset($_POST['url'])) ? $_POST['url'] : "http://www.domain.com"; ?>">
	<br clear="all">
	<label for="submit">Test the class ->&gt;</label>
	<input type="submit" name="submit" value="Start">
  </form>
  <p style="color:#FF0000;padding-left:65px;"><?php echo (isset($error)) ? $error : "&nbsp;" ?></p>
</div>
<p style="margin-top:10px;">More classes and scripts on: <a href="http://www.finalwebsites.com/snippets.php">www.finalwebsites.com</a></p>
</body>
</html>
