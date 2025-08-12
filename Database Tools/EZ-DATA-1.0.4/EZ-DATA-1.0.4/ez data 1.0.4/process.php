<body background="images/bg.jpg" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php
/*
#############################################
#
#
# Programmer: Mike Koenig
# Contact: techwizz78@yahoo.com
# Program: EZ-Data 1.0.2 beta
#
# Changes: 1.0.2 beta adds more fields to the data entry form,
# more search options, cleaner code, data entry verification for $name 
# and $email to prevent from blank entries.
#
# Date Last Modified: 12-27-04
#
#
# License: Free Under The GNU
# We are not responsible for any damage caused by this program,
# it is still in its testing phases.
# 
##################################################
*/


include ("includes/menu.inc");

if (!$name)
{
	echo "You have not entered your Name...";
	die;
}

if (!$email)
{
	echo "You have not entered your E-mail Address...";
	die;
}


include ("includes/data1.inc");
include ("includes/data2.inc");

$query = "INSERT INTO ez_data VALUES('','$name', '$street', '$city', '$state', '$zip', '$email')"; 
  $result = mysql_query($query);

echo "Thank you for submitting your data!";


include ("includes/footer.inc");
?>
