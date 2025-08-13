<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include_once "../conf.php";
if($logout){

if($admin_auth_type=="ht"){
            Header("WWW-authenticate: basic realm=\"You need to login!!\"");
			echo '<script language="javascript">
			window.location="index.php"
			</script>';
}elseif($admin_auth_type=="se"){
                 session_register("username", "password");
                 $username="XXXXXXXXXXXXX";
                 $password="XXXXXXXXXXXXX";
				 		echo '<script language="javascript">
			window.location="index.php"
			</script>';
}else{
                 setcookie("username", "XXXXXXXXXXXXX", time()-2000);
                 setcookie("password", "XXXXXXXXXXXXX", time()-2000);
				echo '<script language="javascript">
			window.location="index.php"
			</script>';
}
}


if($admin_auth_type=="ht"){
 //ht type authentication..
    $username=$PHP_AUTH_USER;
    $password=$PHP_AUTH_PW;
                    if(mysql_num_rows($data=mysql_query("SELECT * FROM admins WHERE username='$username' && password='$password'"))!=1){
            Header("WWW-authenticate: basic realm=\"You need to login!!\"");
            Header("HTTP/1.0 401 Unauthorized");
            exit;
            }

            $admin_id=mysql_fetch_array($data);
            $admin_id=$admin_id[id];

}elseif($admin_auth_type=="se"){
 //session type authentication..
 if($login){
                 //log the user in...
                 session_register("username", "password");
                 $username=$username;
                 $password=$password;
                 echo '<head><title>Sorry no access!</title></head><center><font face="verdana,arial" size=2><B>You have been loged in, now forwarding to admin center...</center></B></font>';
                 echo '<script language=javascript> window.location="index.php" </script>';
 }else{
          //retrieve the username and password.
          $username=$HTTP_SESSION_VARS[username];
          $password=$HTTP_SESSION_VARS[password];
    }
      //check the username and password!..
    if(mysql_num_rows($data=mysql_query("SELECT * FROM admins WHERE username='$username' && password='$password'"))!=1 ||$logout){
    setcookie("username", "", time()+1);
    setcookie("password", "", time()+1);
         $login_now=1; //so no error is displayed!
   }else{
            $admin_id=mysql_fetch_array($data);
            $admin_id=$admin_id[id];
   }


}else{
 //cookie type authentication
      if($login){
                 //log the user in...
                 setcookie("username", $username, time()+2000);
                 setcookie("password", $password, time()+2000);
                 echo '<head><title>Sorry no access!</title></head><center><font face="verdana,arial" size=2><B>You have been loged in, now forwarding to admin center...</center></B></font>';
                 echo '<script language=javascript> window.location="index.php" </script>';
    }else{
          //retrieve the username and password.
          $username=$HTTP_COOKIE_VARS[username];
          $password=$HTTP_COOKIE_VARS[password];
    }
      //check the username and password!..
    if(mysql_num_rows($data=mysql_query("SELECT * FROM admins WHERE username='$username' && password='$password'"))!=1 ||$logout){
    setcookie("username", "", time()+1);
    setcookie("password", "", time()+1);
         $login_now=1; //so no error is displayed!
   } else{
            $admin_id=mysql_fetch_array($data);
            $admin_id=$admin_id[id];
   }
}


//now check the current admin is allowed on this page!
            //check if this admin is allowed in this section.
            $res=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
            $rights=explode(",", $res[privelages]);
            if($section=="general" || !$section){$verified=1;}
                foreach($rights as $right){
                            if($right==$section){$verified=1;break;}
            }


            //check if this admin is allowed to work on this project!
            if($project && $verified){
                           $xes=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$project' && status!='1'"));
                                    if($res[id]==$xes[project_manager]){$project_verified=1;}

                                    $adm=explode(",", $xes[admins]);
                                    foreach($adm as $ad){
                                                    if($ad==$admin_id){$project_verified=1;break;}
                                    }
            }else{
                  $project_verified=1;
            }
            

            if($login_now){
                           //display the html login form!
                         echo' <html>
<head>
<title>Please Login!</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td height="163" width="26%">&nbsp;</td>
    <td height="163" width="38%">&nbsp;</td>
    <td height="163" width="36%">&nbsp;</td>
  </tr>
  <tr>
    <td width="26%" height="214">&nbsp;</td>
    <td width="38%" bgcolor="#EFEFEF" height="214" valign="top">
      <div align="center">
        <p><br>
          <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Please
          login..</font></p>
        <form name="form1" method="post" action="auth.php"> <input type=hidden name=login value=1>
          <table width="500" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="181" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Username:
                </font></td>
              <td width="319">
                <input type="text" name="username">
              </td>
            </tr>
            <tr>
              <td width="181" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Password:
                </font></td>
              <td width="319">
                <input type="text" name="username2">
              </td>
            </tr>
          </table>
		  <br>
          &nbsp;
          <input type="submit" name="submit" value="Login Now!">
        </form>
        <p>&nbsp;</p>
      </div>
    </td>
    <td width="36%" height="214">&nbsp;</td>
  </tr>
  <tr>
    <td width="26%">&nbsp;</td>
    <td width="38%">&nbsp;</td>
    <td width="36%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
 ';

            }

              
            //now wrap it all up!

            if($project_verified && $verified){
            }else{
                  if(!$login_now){ echo '<head><title>Sorry no access!</title></head><center><font face="verdana,arial" size=2 color=red><B>SORRY YOU DO NOT HAVE THE PRIVELAGES NECCESARY TO ACCESS THIS PAGE!</center></B></font>'; }
                  exit;
            }


?>
