<?
// Guestbook v1.0 
// Copyright 2005 Armand Niculescu
// Website: www.armandniculescu.com

if (!empty($_POST['parola_veche']) AND !empty($_POST['parola_noua'])){

    $camp = mysql_fetch_array(mysql_query("SELECT * FROM users")) or die (mysql_error());
   	if (md5($_POST['parola_veche']) == $camp['pass']) {
   		$parola_noua = md5($_POST['parola_noua']);
   		mysql_query("UPDATE users SET pass = '$parola_noua'") or die (mysql_error());
   		echo "<div class=\"text\">Parola a fost schimbata cu succes!</div>";	
   	}
   	else {
			echo "<div class=\"text\">The old password doesn't match!</div>";		
      echo "
      <form action=\"index.php?page=schimbapass\" method=\"post\">
      <div id=\"login\">
						<dl class=\"formular\">
      		<dt>Old password:</dt><dd><input type=\"password\" name=\"parola_veche\" size=\"30\"></dd>
      		<dt>New password:</dt><dd><input type=\"password\" name=\"parola_noua\" size=\"30\"></dd> 
      		<dt>&nbsp;</dt><dd><input type=\"submit\" name=\"submit\" value=\"Change password\"></dd>
						</dl>
      </div>	
      ";
			exit;				
   	}
 		echo "<script>document.location.replace('index.php')</script>";			
}

echo "
      <form action=\"index.php?page=schimbapass\" method=\"post\">
      <div id=\"login\">
						<dl class=\"formular\">
      		<dt>Old password:</dt><dd><input type=\"password\" name=\"parola_veche\" size=\"30\"></dd>
      		<dt>New password:</dt><dd><input type=\"password\" name=\"parola_noua\" size=\"30\"></dd> 
      		<dt>&nbsp;</dt><dd><input type=\"submit\" name=\"submit\" value=\"Change password\"></dd>
						</dl>
      </div>	
";
?>