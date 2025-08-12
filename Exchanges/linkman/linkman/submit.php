<html>
<head>
<title>Linkman</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.boxs {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
	color: #FFFFFF;
	background-color: #000000;
	border: 1px solid #999999;
}
-->
</style>
</head>

<body bgcolor="#000000" text="#FFFFFF" link="#FFFFFF" alink="#FF0000" vlink="#CCCCCC">
<form action="addlink.php" method="post">
  Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  <input name="name" type="text" maxlength="20" class="boxs"><br>
  URL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  <input name="url" type="text" value="http://www." class="boxs"><br>
  Website Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input name="title" type="text" class="boxs"><br>
Website Description:
  <textarea name="description" cols="20" rows="5" class="boxs"></textarea>
<p><input name="submit" type="submit" value="Submit" class="boxs"></p>
</form>
</body>
</html>
