<?
include ("settings.php");


$innlogging= <<< HTML

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Login</title>
<link rel="stylesheet" href="$css" type="text/css">
</head>

<body bgcolor="#ACACAC">
<form action=$PHP_SELF?val=login method="post">
<br>
<br>
<br>
<br>
<br>
<table align="center" bgcolor="#49349E" width="250" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td bgcolor="#6956C0" valign="top"><center><b>Login</b></center></td>
</tr>
<tr>
    <td>
	<Table border=0>
	<Tr>
	<Td>Username:</Td>
	<Td><input type="text" name="brnavn"></Td>
	</Tr>
	
	<Tr>
	<Td>Password:</Td>
	<Td><input type="password" name="passord"></Td>
	</Tr>
	</Table>
	<center><br>
	<a href="?val=glemt">Forgotten password?</a><br>
	
	
	<input type="submit" value="Login">
		
	</center>
	
	
	
	</td>
</tr>
</table>



</form>


</body>
</html>


HTML;

$glemtpassord= <<< HTML

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Login</title>
<link rel="stylesheet" href="$css" type="text/css">
</head>

<body bgcolor="#ACACAC">
<form action=$PHP_SELF?val=glemt2 method="post">
<br>
<br>
<br>
<br>
<br>
<table align="center" bgcolor="#49349E" width="250" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td bgcolor="#6956C0" valign="top"><center><b>Forgotten username/password?</b><br></center></td>
</tr>
<tr>
    <td>
	<Table border=0>
	<Tr>
	<Td valign="top">Name:</Td>
	<Td><input type="text" name="navn"><br>
	<br>
	Plese write your name, and your info will be sent to your registrated email address.<br><br>

	<input type="submit" value="Send"><br>
	<br></Td>

	</Tr>
		</center>
	
	
	
	</td>
</tr>
</table>



</form>


</body>
</html>


HTML;




if ($val==login)
{
$file = file("brukere.dat");
while(list(,$value) = each($file)){
list($bbruker,$bpass,$bemail,$bnavn,$bniva) = explode("<~>", $value);
if($brnavn == $bbruker && $passord == $bpass){

setcookie("ns", "logged", time()+36000);
setcookie("nsbruker", $bbruker, time()+36000);
setcookie("nsemail", $bemail, time()+36000);
setcookie("nsnavn", $bnavn, time()+36000);
if($bniva==2)
{
setcookie("nsniva", "admin", time()+36000);
}
else
{
setcookie("nsniva", "vanlig", time()+36000);
}
header("Location: write.php?valg=main");
exit;
}
else
{
}
}
}

elseif ($val==glemt)
{
echo $glemtpassord;
}


elseif ($val==glemt2)
{
$file = file("brukere.dat");
while(list(,$value) = each($file))
{
list($bbruker,$bpass,$bemail,$bnavn,$bniva) = explode("<~>", $value);
if($bnavn==$navn)
{
echo "You will now receive your info to your email ($bemail)";
echo $innlogging;
mail("$bemail", "Login information","
	____________________________________________________________
	You have asked for your login information. These are:
	Full name: $bnavn
	Username: $bbruker
	Password: $bpass
	
	
	This email was generated.
	____________________________________________________________");
$funnet="1";
}
else
{
}

}
if($funnet=="1")
{
}
else
{
echo "<h3 align=\"center\">Could not find that name. Did you spell it right? You spelled: $navn</h3>";
echo $glemtpassord;
}
}







else
{
echo $innlogging;
}


?>
