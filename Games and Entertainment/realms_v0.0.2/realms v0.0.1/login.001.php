<?php
global $username;
global $pass;

if($_GET[thing]!=register){
        if($login==yes){
                $username=$_POST['username'];
                $pass=$_POST['pass'];

                //print "<br>$username : $pass<br>";

                if($md5pass==1){
                        //$pass=md5($pass);
                }
                $user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));

                if($user[id]){
                if($remember){
                                setcookie("username",$username,time()+10000000);
                                setcookie("pass",$pass,time()+10000000);
                }else{
                                setcookie("username",$username,time()+3600*24*365);
                                setcookie("pass",$pass,time()+3600*24*365);
                }
                }
                $user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
                print " $user[username] -( <a href='$GAME_SELF?p=updates'>Click here<!-- if you are not automatically redirected....--></a> )";
                
                //print "<br>$username : $pass<br>";//cookie check, if this shows " : " cookies are not working (worked in firefox but not IE.. yet IE still logs in, it just cant get the cookie yet or something)

                //print "<meta http-equiv='refresh' content='3; url=$GAME_SELF?p=updates'>";
        }else{
                //setcookie("username");
                //setcookie("pass");

                $username=$_COOKIE['username'];
                $pass=$_COOKIE['pass'];
                $user = mysql_fetch_array(mysql_query("select * from `users` where username='$username' and password='$pass'"));

				if($user[username]!=""){
					print"Welcome to $gametitle, $user[username]<br>You're already logged in";
				}else{
					print "Welcome to $gametitle<br>
                <form method=post action=$GAME_SELF?p=login&login=yes>
                <table>
                <tr><td>username:</td><td><input type=text name=username></td></tr>
                <tr><td>Pass:</td><td><input type=password name=pass></td></tr>
                <tr><td>Remember:</td><td><input type=checkbox name=remember>yes</td></tr>
                <tr><td colspan=2 align=center><input type=submit value=Login> ... Or [<a href=$GAME_SELF?p=login&thing=register>REGISTER</a>]</td></tr>
                </form>
                </table>";
				}

        }
}else{
        if(!$_GET[part]){
                global $name;
                global $email;
                global $pass;
                global $glomp;
                print"Hello, Welcome to $gametitle.<br>
                <form method=post action=$GAME_SELF?p=login&thing=register&part=1>
                Username:<input type=text name=\"name\"><br>
                Email address:<input type=text name=\"email\"><br>";
                if($glomp){
                        print"<input type=hidden name=\"glomp\" value=\"$glomp\">";
                }
                print"
                <input type=submit value=GO>";
        }

        if($_GET[part]==1){

                $name=$_POST['name'];
                $email=$_POST['email'];
                $glomp=$_POST['glomp'];

                global $name;
                global $email;
                global $pass;
                global $glomp;

                $testname = mysql_fetch_array(mysql_query("select * from users where user='$name'"));

                $testmail = mysql_fetch_array(mysql_query("select * from users where email='$email'"));

                //$userip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
                //$testip = mysql_fetch_array(mysql_query("select * from users where ip='$userip'"));


                if(!$name||!$email){
                        print"$name | $email<br> Failed, please fill out all sections";
                }elseif($testname[id]>0){
                        print"the name $name is taken... please try a different one";
                }elseif($testmail[id]>0){
                        print"the email address $email is taken... please try a different one... and remember only one account per user/computer/network is allowed";
                }elseif($testip[id]>0&&$testip[id]!=2){
                        print"the ip address of this computer already has an account... please remember only one account per IP address is allowed, if you have not registerred an account using this network/computer before, and have a dynamic IP address please contact an admin to create your account,";
                }else{


                        $newestid=1;
                        $testat = mysql_fetch_array(mysql_query("select * from users where id='$newestid'"));
                        while($testat[id]){
                        $newestid=$newestid+1;
                        $testat = mysql_fetch_array(mysql_query("select * from users where id='$newestid'"));
                }

                $pass=rand_pass();

                if($md5pass==1){
                        $passb=md5($pass);
                }else{
                        $passb=$pass;
                }
                if($race=="Clone"){
                        $worldy="001";
                }elseif($race=="Testrace"){
                        $worldy="004";
                }else{
                        $worldy="003";
                }
                $ctime=time();
                $userip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
                mysql_query("INSERT INTO `users` (`lastseen` , `id` , `username` , `email` , `password` , `ip`)
                VALUES
                ('$ctime' , '$newestid' , '$name' , '$email' , '$passb' , '$userip')") or die("<br>Could not register.");

                mail($email, "Welcome $name", "Welcome to $gametitle, /n Your login is $email and the password is $pass .... $gameurl ", "From: $adminemail");

                if($newemailadmin==1){
                        mail($adminemail, "$name has joined $gametitle", "$name has joined $gametitle", "From: $adminemail");
                }

                print "You are now registered as $name, your password has been emailed to you at $email . : <a href=$GAME_SELF?p=login>Continue</a>";

                if($glomp){
                        $glomped = mysql_fetch_array(mysql_query("select * from users where id=$glomp"));
                        mysql_query("update users set credits=credits+1 where id=$glomped[id]");
                }

        }
}


}

?>