<?php
if ($user[position]=="Guest"){
   print"Guest account can't change options";
   return;
   }


if ($view == name) {
        print "<form method=post action=$GAME_SELF?p=options&view=name&step=name>";
        print "Change my name to <input type=text name=name value=$user[username]>, please. <input type=submit value=Change>";
        print "</form>";
        if ($step == name) {
                if (empty ($name)) { print "Can't do that."; }else{
                $name = strip_tags($name);
                $numn = mysql_num_rows(mysql_query("select * from users where username='$name'"));
if ($numn > 0) {
                        print "That username is taken.";
                } else {
                        mysql_query("update `users` set `username`='$name' where `id`='$user[id]'");
                        print "You changed your name to <b>$name</b>.";
                }
        }
   }
}elseif ($view == charname) {
        print "<form method=post action=$GAME_SELF?p=options&view=charname&step=name>";
        print "Change my name to <input type=text name=name value=$stat[name]>, please. <input type=submit value=Change>";
        print "</form>";
        if ($step == name) {
                if (empty ($name)) { print "Can't do that."; }else{
                $name = strip_tags($name);
                $numn = mysql_num_rows(mysql_query("select * from characters where name='$name'"));
if ($numn > 0) {
                        print "That name is taken.";
                } else {
                        mysql_query("update `characters` set `name`='$name' where `id`='$stat[id]'");
                        print "You changed your active characters name to <b>$name</b>.";
                }
        }
   }
}elseif ($view == pass) {
        print "<form method=post action=$GAME_SELF?p=options&view=pass&step=cp>
        <table>
<tr><td>Current pass:</td><td><input type=text name=cp></td></tr>
<tr><td>New password:</td><td><input type=text name=np></td></tr>
<tr><td>New password:</td><td><input type=text name=npb></td></tr>
<tr><td colspan=2 align=center><input type=submit value=Change></td></tr>
</form>
</table>";
        if ($step == cp) {

                if (empty ($np) || empty ($cp) || empty ($npb)) {
                print "Duh... fill out all fields.";
        }elseif ($cp!=$user[password]) {
                print "Duh... cant change your pass without the old one.";

        }elseif ($np!=$npb) {
                print "Duh... new passes werent the same.";
        }else{
                mysql_query("update `users` set `password`='$np' where `id`='$user[id]' and `password`='$cp'");
                print "You changed your pass. Now you will have to login again.";
        }

}
}elseif ($view == email) {
        print "<form method=post action=$GAME_SELF?p=options&view=email&step=ce>
<table>
<tr><td>New E-mail:</td><td><input type=text name=ne value=$user[email]></td></tr>
<tr><td colspan=2 align=center><input type=submit value=Change></td></tr>
</form>
</table>";
        if ($step == ce) {

                if (empty ($ne)) {
                print "Duh... fill out all fields.";
                }else{
        $dupe2 = mysql_num_rows(mysql_query("select * from users where email='$ne'"));
        if ($dupe2 > 0) {
                print "Someone already has that email.";
        }else{
                mysql_query("update `users` set `email`='$ne' where `id`='$user[id]'");
                print "You changed your E-mail from $ce to $ne. Now close this window and login again.";
        }
        }
}
}elseif ($view == temple) {
        print "<form method=post action=$GAME_SELF?p=options&view=temple&step=ce>
<table>
<tr><td>New Template:</td><td><select name=temples>
<option value=darkrealmsie>Dark</option>
<option value=realmsie selected=selected>Light</option>
</select></td></tr>
<tr><td colspan=2 align=center><input type=submit value=Change></td></tr>
</form>
</table>";
        if ($step == ce) {

                if (empty ($temples)) {
                print "an error occurred.";
                }else{
                mysql_query("update `users` set `template`='$temples' where `id`='$user[id]'");
                print "You updated your template $temples";

        }
}
}


if(!$view||$p!="options"){
print"<ul><li><a href=$GAME_SELF?p=options&view=name>Change UserName</a>";
if($user[activechar]>1){
print"<li><a href=$GAME_SELF?p=options&view=charname>Change Character name</a>";
}
print"<li><a href=$GAME_SELF?p=options&view=pass>Change Password</a>";
print"<li><a href=$GAME_SELF?p=options&view=email>Change Email Address</a>";
print"<li><a href=$GAME_SELF?p=options&view=temple>Change Template</a>";
print"</ul>";
}

