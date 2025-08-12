<html>
<head>
<title>Guestbook</title>
</head>
<body>

<!--- your html --->

<a href="addguestbook.html">Click here to add to my guestbook<br />
if your browser dosn't support javascript</a><br /><br />
<a href="js_addguestbook.html">Click here to add to my guestbook<br />
if your browser dose support javascript</a><br /><br />
<?php
$smile        = "<img src='smile.gif'>";
$sad          = "<img src='sad.gif'>";
$disappointed = "<img src='disappointed.gif'>";
$confused     = "<img src='confused.gif'>";
$thumbdown    = "<img src='thumbdown.gif'>";
$thumbup      = "<img src='thumbup.gif'>";
$data= "guestbook.txt"; 
$data1 = fopen ($data, "r"); 
$done = fread ($data1, filesize ($data));
$done = str_replace('SsmileS', $smile, $done);
$done = str_replace('SsadS', $sad, $done);
$done = str_replace('SdisappointedS', $disappointed, $done);
$done = str_replace('SconfusedS', $confused , $done);
$done = str_replace('SthumbdownS', $thumbdown , $done);
$done = str_replace('SthumbupS', $thumbup , $done);
fclose ($data1);
echo "$done";
?>

<!--- your html --->

</body>
</html>