<html>

<head>
<title>Contact Us</title>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>

<body>

<h2 align="center">Contact Us</h2>

This is a basic return page - you only need add the php lines below to any page you create

  <?php $message=$_GET['message'];
                  echo urldecode ($message);?>

</body>
</html>