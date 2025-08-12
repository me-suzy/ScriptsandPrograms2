<?
/*
Copyright Information
Script File :  functions.php
Creator:  Jose Blanco (x-php)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper
please keep this copyright in place. :)
*/
 	//--- Login
	
 if($_POST["do"]=="login"){
    if(!@$_POST['user'] && !@$_COOKIE['fc_user']){
     	 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">
       Please Input your Username or Password!
	   </font></div></td>
  </tr>
</table>

html;
   }
   else{
	if(@$_COOKIE['fc_user']){
		header("location: admin.php");
	}
	else{
  		$user = strtolower($_POST['user']);
		$pass = $_POST['pass'];
		$file = file("./inc/users.db.php") or die("Problem getting the user details flat-file ");
        $totalLines = sizeof($file);
        $line = 0;
		$match = 0;
		do{
     if('<?php die("You may not access this file"); ?>' != substr($file[$line], 0, 2)){
		@list($username, $password, $permission) = explode("|>", $file[$line]);
     if((strtolower($user) == strtolower($username)) && (md5($pass) == $password)) $match = 1;
			else $match = 0;
			}
	 if($match) break;
		$line++;
		} while($line < $totalLines);

  	 if($match){
        setcookie('fc_user', $user);
        setcookie('fc_pass', $pass);
		setcookie('fc_permission', $permission);
		header("location: admin.php"); 
		
		}
		else {

		 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">
       There Seems to be  a problem with your Login Information, <br> You have Not Been Logged In.
	   </font></div></td>
  </tr>
</table>

html;
        }
	}
     }
      }
	
    //--- Logout
    if($admin == "logout"){
     if(@$_COOKIE["fc_user"]){	
       setcookie('fc_user',"", time() - 3600);
       setcookie('fc_pass',"", time() - 3600);
       setcookie('fc_permission',"", time() - 3600);
     }
    header("location: admin.php?admin=out");
    }
    
	// some basic functions for the flood protection
error_reporting(E_ERROR);

reset ($HTTP_POST_VARS);
while (list($key, $value) = each($HTTP_POST_VARS)) {
	${$key} = $value;
}
function my_array_shift(&$array){
	reset($array);
	$temp = each($array);
	unset($array[$temp['key']]);
	return $temp['value'];
}
function getip() {
  if(isSet($_SERVER)) {
    if(isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif(isSet($_SERVER["HTTP_CLIENT_IP"])) {
      $realip = $_SERVER["HTTP_CLIENT_IP"];
    }else{
      $realip = $_SERVER["REMOTE_ADDR"];
    }
  }else{
  if(getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
    $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
  }elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
    $realip = getenv( 'HTTP_CLIENT_IP' );
  }else {
    $realip = getenv( 'REMOTE_ADDR' );
  }
}
return $realip;
}
function checkifflooding($ip, $floodtime){
	if(file_exists("./inc/flood.db.php")) {
		$data = "";
		$file = file("./inc/flood.db.php");
		my_array_shift($file);
		for($i = 0; $i<= count($file);$i++){
			list($a, $b) = explode('=', trim($file[$i]));
			if($b > time() - $floodtime)$data .= "$a=$b\n";
			}
		}
 
		$fp = fopen("./inc/flood.db.php", "w") or die ("Can't open flood.db.php for reading/writing!");
		$lock = flock($fp, LOCK_EX);
		if ($lock) { 
       		fseek($file_pointer, 0, SEEK_END);  	
			fputs($fp, "<?php die(\"You may not access this file.\"); ?>\n".$data);
			flock($fp, LOCK_UN);  
		}
		fclose($fp);

		$time = 0;
		$file = file("./inc/flood.db.php");
		my_array_shift($file);
		for($i = 0; $i<= count($file);$i++){
		list($a, $b) = explode('=', $file[$i]);
		if(trim($a) == $ip){$time = $b;}
		}

		if($time > "0"){
		if(((time() - $floodtime) <= $time)){$result = true;}else{$result = false;}
		}else{$result = false;}
		return $result;
}
// end basuc funtions

   
?>
