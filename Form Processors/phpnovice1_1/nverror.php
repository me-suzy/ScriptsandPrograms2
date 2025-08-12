<?php
/* START ERROR FUNCTIONS */

function default_success() {
?>

	<html>
	<head>
	<title>Thank You.</title>
	</head>
	<body>
	<BR><BR><CENTER>
	<h2>Your form has been submitted successfully!</h2>

	</CENTER>
	<?php echo "$footer"; ?>

	</body>
	</html>

<?php

exit;
} /* end function: "default_success" */




function no_pst() {
?>

<html>

<head>
<title>Novice Form &nbsp; &nbsp; &nbsp; &nbsp;  Version 1.1 </title> 
</head><body bgcolor="#cfcfcf">
<center>


<table width=500 border=1><tr><td bgcolor="#000080">
<br><br>
<font face="Arial" color="#ffffff">
<center>
Novice Form &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Version 1.1<br><br>

 &copy; Copyright 2000-2003 Seth Knorr<br><br>
A free download of this script can be found at: <a href="http://www.noviceform.com/" target="_blank"> <font color="#ff0000"><b> http://www.noviceform.com/ </b></font></a>
</font></center>
<br><br>
</td></tr></table>
</center>

</body></html>

<?php
exit;
} /* end function: "no_pst" */



function msng_email() {

	$title = "<title>Missing or invalid format of email!</title>";
	$errormessage = "<h2>Missing or invalid format of email.</h2><b>The email Field must be filled in and in the proper format!</b>";

        echo "$title";
	echo "$errormessage";
	echo "$backbutton";
	echo "$footer";
        exit;

} /* end function: "msng_email" */




function msng_required() {

		$title = "<title>Missing form fields!</title>";
		$errormessage = "<h2>Missing form fields!</h2><b>The Below Required Fields Where Left Blank:</b><br><br>$REQ_error";

		echo "$title";
		echo "$errormessage";
		echo "$backbutton";
		echo "$footer";
		exit;

} /* end function: "msng_required" */

?>