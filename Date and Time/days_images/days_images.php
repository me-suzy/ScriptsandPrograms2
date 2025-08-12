<html>
<head>
<title>Today is <?php echo date("l");?></title>
</head>
<body>
<?php
/* This PHP Script developed by Scott Clark
The Source is available at http://www.clarksco.com/dev/
Copyright 2005 Clark Consulting */
// Variables
$url = "http://YOURDOMAIN.COM/images/";
$monImage = "$url"."monday.jpg";
$tueImage = "$url"."tuesday.jpg";
$wedImage = "$url"."wednesday.jpg";
$thurImage = "$url"."thursday.jpg";
$friImage = "$url"."friday.jpg";
$weekendImage = "$url"."weekend.jpg";
$d = date("D");
//Function that switches between date images based on the actual day of the week in $d
switch ($d)
{
case Mon:
echo "<img src=$monImage>\n";
break;
case Tue:
echo "<img src=$tueImage>\n";
break;
case Wed:
echo "<img src=$wedImage>\n";
break;
case Thu:
echo "<img src=$thurImage>\n";
break;
case Fri:
echo "<img src=$friImage>\n";
break;
default:
echo "<img src=$weekendImage>\n";
}
//End
?>
</body>
</html>
