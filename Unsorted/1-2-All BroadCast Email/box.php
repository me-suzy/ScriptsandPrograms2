<?PHP

        // INCLUDE FILES
        require("lang_select.php");
        require("sender.inc.php");

        // SET SOME VARIABLES
        $box_status = "0";

        // SET DATE
        $today = date("Ymd");
        $today_time = date("H:i:s");
        // BACK-CHECK FOR OLD VERSIONS
        if ($nlbox[1] == ""){
        $nlbox[1] = "$nl";
        }
        if ($nl == ""){
        $nl = "$nlbox[1]";
        }

        // IF NO CUSTOM FORM OR INPUTED DATA IS FOUND, THE SCRIPT WILL RE-DIRECT TO THE DEFAUTL SUBSCRIPTION SCREEN
        if ($funcml == "" OR $funcml == "addpre" OR $funcml == "unsub1"){
        header("Location: box_preset.php?funcml=$funcml&mlt=$mlt&nl=$nl&email=$email");
        exit();
        }

        // LOOKS UP THE DEFAULT REDIRECT URLS IF A CUSTOM FORM IS NOT FOUND
        if ($p != ""){
                $listtab = mysql_query ("SELECT * FROM 12all_SubForms
                                                                        WHERE id LIKE '$p'
                                                                ");
                $listtabs = mysql_fetch_array($listtab);
                $link_add1 = $listtabs["add1"];
                $link_add2 = $listtabs["add2"];
                $link_add3 = $listtabs["add3"];
                $link_add4 = $listtabs["add4"];
                $link_unsub1 = $listtabs["unsub1"];
                $link_unsub2 = $listtabs["unsub2"];
                $link_unsub3 = $listtabs["unsub3"];
                $link_unsub4 = $listtabs["unsub4"];
        }
        // LOOKS UP THE CUSTOM FORM REDIRECT URLS
        else {
                $listtab = mysql_query ("SELECT * FROM Backend
                                                                        WHERE valid LIKE '1'
                                                                ");
                $listtabs = mysql_fetch_array($listtab);
                $link_add1 = $listtabs["add1"];
                $link_add2 = $listtabs["add2"];
                $link_add3 = $listtabs["add3"];
                $link_add4 = $listtabs["add4"];
                $link_unsub1 = $listtabs["unsub1"];
                $link_unsub2 = $listtabs["unsub2"];
                $link_unsub3 = $listtabs["unsub3"];
                $link_unsub4 = $listtabs["unsub4"];
        }
                function plugin($email)
                {
                                $pat1 = "@";
                                $emailarr = split ($pat1,$email);
                                $email1 = $emailarr[0];
                                $email2 = $emailarr[1];
                                $email = trim($email);
                                $elen = strlen($email);
                                $dotpresent = 0;
                                for ($i=2;$i<=$elen;$i++)
                                {
                                        $j = substr($email,0,$i);
                                        $jlen = strlen($j);
                                        $lastj = substr($j,$jlen-1,$jlen);
                                        $asci = ord($lastj);
                                        if ($asci==46)
                                        {
                                                $dotpresent = 1;
                                        }
                                }
                                $spaceexist = 0;
                                for ($k=0;$k<$elen;$k++)
                                {
                                        $myword = substr($email,$k,1);
                                        if (ord($myword)==32)
                                        {
                                                $spaceexist = 1;
                                        }

                                }
                                if ($email2)
                                {
                                        $atpresent = 1;
                                }
                                if ($atpresent=='1' AND $dotpresent=='1' AND $spaceexist=='0')
                                {
                                        $validmail = 1;
                                }
                                else
                                {
                                        $validmail = 0;
                                }
                                return ($validmail);
                }



        // IF FUNCTION IS TO ADD E-mail ADDRESS
        // THIS IS ONLY USED IF THE DATA IS ALREADY READY
        if ($funcml == "add"){
                $validmail = plugin($email);
                if ($validmail == 1){


                // LOOPS THROUGH NEWSLETTERS
                foreach ($nlbox as $something)
                {
                if ($something != "")
                {
                                $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$something'
                                                 limit 1
                       ");
                                $chk = mysql_fetch_array($check);
                                if ($chk["a_mx"] != ""){
                                $findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$something'
                                                 AND email != ''
                       ");
                                $num_email = mysql_result($findcount, 0, 0);
                                if ($num_email >= $chk["a_mx"]){
                                                $box_status = "1";
                                                $box_prompt = "$lang_469";
                                                $stopper = "yes";
                                                if ($link_add1 != ""){
                                                $locit = "$link_add1?mesg=$box_prompt";
                                                }
                                }
                                }

                // FIND NEWSLETTER INFORMATION
                $listtab = mysql_query ("SELECT * FROM Lists
                WHERE id LIKE '$something'
                                                ");
                $listtabs = mysql_fetch_array($listtab);
                                $listtabname = $listtabs["name"];        // NAME OF LIST
                $findcount = mysql_query ("SELECT * FROM ListMembers
                         WHERE email LIKE '$email'
                                                 AND nl LIKE '$something'
                       ");
                $countdata = mysql_num_rows($findcount);
                $countdatac = mysql_fetch_array($findcount);
                if ($countdata != 0)
                {
                $active = $countdatac["active"];
                if ($active == "0"){
                $box_status = "1";//MARK PROCESS AS BEING AN ERROR
                $box_prompt = "$box_prompt $lang_39, $email, $lang_40 \"$listtabname\" <p>";
                $stopper = "yes";
                if ($link_add1 != ""){
                $locit = "$link_add1?mesg=$box_prompt";
                }

                }
                else {
                $box_status = "2";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_41.<p>";
                $skipper = "yes";
                                if ($link_add2 != ""){
                $locit = "$link_add2?mesg=$box_prompt";
                }
                }
                }
                $findcount2 = mysql_query ("SELECT * FROM Lists
                                                 WHERE id LIKE '$something'
                                                 AND bk LIKE '%$email%'
                       ");

                $countdata2 = mysql_num_rows($findcount2);
                if ($countdata2 != "0"){
                $box_status = "1";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_42 [ $listtabname ].<br>$lang_43.<p>";
                $stopper = "yes";
                if ($link_add1 != ""){
                $locit = "$link_add1?mesg=$box_prompt";
                }

                }
                if ($stopper != "yes"){
                $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$something'
                                                 limit 1
                       ");
                $thanks = mysql_fetch_array($result);
                $confirm = $thanks["confirmopt"];
                if ($confirm == 1){
                if ($skipper != "yes"){
                mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, stime, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, comp) VALUES ('$email' ,'$name' ,'$something' ,'$today' ,'$today_time' ,'$REMOTE_ADDR' ,'1' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10','$HTTP_USER_AGENT')");
                }
                $shedval = "go";
                $box_status = "2";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_44 [ $listtabname ].<BR>$lang_45<BR>$lang_46<p>";
                if ($link_add2 != ""){
                $locit = "$link_add2?mesg=$box_prompt";
                }
                }
                else {
                mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, stime, sip, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, comp) VALUES ('$email' ,'$name' ,'$something' ,'$today' ,'$today_time' ,'$REMOTE_ADDR' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10' ,'$HTTP_USER_AGENT')");
                $box_status = "3";
                $box_prompt = "$box_prompt $lang_39, $email, $lang_47 [ $listtabname ].<p>";
                if ($link_add3 != ""){
                $locit = "$link_add3?mesg=$box_prompt";
                }
                }
                if ($confirm == 1){
                                                                if ($name == ""){
                                                                        $name = "Subscriber";
                                                                }
                                                                $message = stripslashes($thanks["icontent"]);
                                                                $message = ereg_replace ("subscribername", "$name", $message);
                                                                $message = ereg_replace ("subscriberemail", "$email", $message);
                                                                $subject = stripslashes($thanks["isubject"]);
                                                                $subject = ereg_replace ("subscribername", "$name", $subject);
                                                                $subject = ereg_replace ("subscriberemail", "$email", $subject);
                                                                $from = $thanks["email"];
                                                                $mtype = $thanks["confirmoptt"];
                                                                $urlfinder = mysql_query ("SELECT * FROM Backend
                                                                                                                         WHERE valid LIKE '1'
                                                                                                                         limit 1
                                                                                                                 ");
                                                                $findurl = mysql_fetch_array($urlfinder);
                                                                $murl = $findurl["murl"];
                                                                $cemail = base64_encode($email);
                                                                $message = ereg_replace ("%CONFIRMLINK%", "$murl/box.php?p=$p&e=$cemail&funcml=csub&nl=$something", $message);
                                                                $mail = new htmlMimeMail();
                                                                if ($mtype == 'html') {
                                                                        $mail->setHtml($message);
                                                                } elseif ($mtype == 'text') {
                                                                        $message = ereg_replace ("\r", "", $message);
                                                                        $mail->setText($message);
                                                                }
                                                                $mail->setFrom($from);
                                                                $mail->setSubject($subject);
                                                                //$mail->setReturnPath($from);
                                                                $sendResult = $mail->send(array($email));
                }
                else{
                                $respond = mysql_query ("SELECT * FROM 12all_Respond
                                                                                WHERE nl = '$something'
                                                                                AND time = '0'
                                                                                ORDER BY time, subject
                                                                                ");
                                if ($c1 = mysql_num_rows($respond)) {
                                while($respondf = mysql_fetch_array($respond)) {
                                        $fid = $respondf["id"];
                                        $rid = ",$fid,";
                                        $msubject = stripslashes($respondf["subject"]);
                                        $mfromn = stripslashes($respondf["fromn"]);
                                        $mfrome = $respondf["frome"];
                                        $mtype = $respondf["type"];
                                        $mcontent = stripslashes($respondf["content"]);
                                        $membersResult = mysql_query ("SELECT sdate,stime,email,name,id,respond FROM ListMembers
                                                                                                        WHERE active LIKE '0'
                                                                                                        AND email = '$email'
                                                                                                        AND nl LIKE '$something'
                                                                                                        ");
                                        $row = mysql_fetch_array($membersResult);
                                                        $sid = $row["id"];
                                                        $email = $row["email"];
                                                        $ridold = $row["respond"];
                                                                                $urlfinder = mysql_query ("SELECT * FROM Backend
                                                                                                                                        WHERE valid LIKE '1'
                                                                                                                                        limit 1
                                                                                                                                        ");
                                                                                $findurl = mysql_fetch_array($urlfinder);
                                                                                $murl = $findurl["murl"];
                                                                                $mcontent = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nlbox[1]=$nl&email=$email", $mcontent);
                                                                                foreach (array('msubject', 'mcontent') as $var) {
                                                                                        $$var = str_replace ("subscribername", $name, $$var);
                                                                                        $$var = str_replace ("subscriberemail", $email, $$var);
                                                                                        $$var = ereg_replace ("[\]", "", $$var);
                                                                                }
                                                                                for ($i=10; $i>=1; $i--) {
                                                                                        $msubject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $msubject);
                                                                                        $mcontent = str_replace("subscriberfield" . $i, ${'field' . $i}, $mcontent);
                                                                                }

                                                                                $mail = new htmlMimeMail();
                                                                                if ($mtype == 'html') {
                                                                                        $mail->setHtml($mcontent);
                                                                                } elseif ($mtype == 'text') {
                                                                                        $mail->setText($mcontent);
                                                                                }
                                                                                if($mfromn != ""){
                                                                                        $mfromd = "\"".$mfromn."\" <".$mfrome.">";
                                                                                        $mfrome = stripslashes($mfromd);
                                                                                }

                                                                                $mail->setFrom($mfrome);
                                                                                $mail->setSubject($msubject);
                                                                                $sendResult = $mail->send(array($email));
                                                                                mysql_query ("INSERT INTO 12all_RespondT (sid,fid,sdate) VALUES ('$sid' ,'$fid' ,'$s_date')");
                                                                                $ridspond = "$ridold $rid";
                                                                                mysql_query("UPDATE ListMembers SET respond='$ridspond' WHERE (id='$sid')");

                                }

                                }
                }
                }
                $stopper = "no";
                $cucc = $cucc2 + 1;
                }
                // CLOSE ADD FUNCTION
                }
                }
                else {
                $box_status = "1";
                $box_prompt = "$box_prompt $lang_360<p>";
                $stopper = "yes";
                if ($link_add1 != ""){
                $locit = "$link_add1?mesg=$box_prompt";
                }
                }
                }
                // UNSUBSCRIBE FUNCTION
                // REMOVE WHITESPACES
                if ($funcml == "unsub2"){

                $validmail = plugin($email);
                if ($validmail == 1){

                $email = str_replace("  ", "", $email);
                $email = str_replace(" ", "", $email);
                foreach ($nlbox as $something)
                {
                if ($something != "")
                {
                $listtab = mysql_query ("SELECT * FROM Lists
                                WHERE id LIKE '$something'
                                                                ");
                $listtabs = mysql_fetch_array($listtab);
                $listtabname = $listtabs["name"];
                $findcount = mysql_query ("SELECT * FROM ListMembers
                                                                 WHERE email LIKE '$email'
                                                                 AND nl LIKE '$something'
                                                           ");
                $countdata = mysql_num_rows($findcount);
                $ac_info = mysql_fetch_array($findcount);
                $ac_name = $ac_info["name"];
                if ($countdata != 0)
                {
                $result = mysql_query ("SELECT * FROM Lists
                                                                 WHERE id = '$something'
                                                                 limit 1
                                                           ");
                $thanks = mysql_fetch_array($result);
                $confirm = $thanks["confirmopt2"];
                if ($confirm != 1){
                mysql_query ("DELETE FROM ListMembers
                                                                                WHERE email = '$email'
                                                                                AND nl = '$something'
                                                                                ");
                mysql_query ("INSERT INTO ListMembersU (em, nl) VALUES ('$email' ,'$something')");
                }
                $confirm = $thanks["confirmopt2"];
                if ($confirm == 1){
                                                                if ($ac_name == ""){
                                                                        $ac_name = "Subscriber";
                                                                }
                                                                $message = stripslashes($thanks["ocontent"]);
                                                                $message = ereg_replace ("subscribername", "$ac_name", $message);
                                                                $message = ereg_replace ("subscriberemail", "$email", $message);
                                                                $subject = stripslashes($thanks["osubject"]);
                                                                $subject = ereg_replace ("subscribername", "$ac_name", $subject);
                                                                $subject = ereg_replace ("subscriberemail", "$email", $subject);
                                                                $from = $thanks["email"];
                                                                $mtype = $thanks["confirmoptt"];
                                                                $urlfinder = mysql_query ("SELECT * FROM Backend
                                                                                                                         WHERE valid LIKE '1'
                                                                                                                         limit 1
                                                                                                                 ");
                                                                $findurl = mysql_fetch_array($urlfinder);
                                                                $murl = $findurl["murl"];
                                                                $cemail = base64_encode($email);
                                                                $message = ereg_replace ("%CONFIRMLINK%", "$murl/box.php?p=$p&e=$cemail&funcml=cunsub&nl=$something", $message);
                                                                $mail = new htmlMimeMail();
                                                                if ($mtype == 'html') {
                                                                        $mail->setHtml($message);
                                                                } elseif ($mtype == 'text') {
                                                                        $message = ereg_replace ("\r", "", $message);
                                                                        $mail->setText($message);
                                                                }
                                                                $mail->setFrom($from);
                                                                $mail->setSubject($subject);
                                                                //$mail->setReturnPath($from);
                                                                $sendResult = $mail->send(array($email));

                if ($confirm == 1){
                $box_status = "2";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_53 [ $listtabname ].<BR>$lang_54<BR>$lang_46";
                if ($link_unsub2 != ""){
                $locit = "$link_unsub2?mesg=$box_prompt";
                }
                }
                else{
                $box_status = "3";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_55 [ $listtabname ].<p>";
                if ($link_unsub3 != ""){
                $locit = "$link_unsub3?mesg=$box_prompt";
                }
                }
                }
                else{
                $box_status = "3";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_55 [ $listtabname ].<p>";
                if ($link_unsub3 != ""){
                $locit = "$link_unsub3?mesg=$box_prompt";
                }
                }
                }
                else {
                $box_status = "1";
                $box_prompt = "$box_prompt $lang_39 [ $email ] $lang_56 [ $listtabname ].<p>";
                if ($link_unsub1 != ""){
                $locit = "$link_unsub1?mesg=$box_prompt";
                }
                }
                }
                }
                }
                else {
                $box_status = "1";
                $box_prompt = "$box_prompt $lang_360<p>";
                $stopper = "yes";
                if ($link_add1 != ""){
                $locit = "$link_add1?mesg=$box_prompt";
                }
                }

                }
                // CONFIRM SUBSCRIPTION
                if ($funcml == csub){
                        $e = base64_decode($e);
                        $result = mysql_query ("SELECT * FROM Lists
                                                                         WHERE id LIKE '$nl'
                                                                         limit 1
                                                                   ");
                        $thanks = mysql_fetch_array($result);
                        $page = $thanks["confirmoptpage"];
                        mysql_query("UPDATE ListMembers SET active='0', sdate='$today', stime='$today_time' WHERE (email='$e' AND nl='$nl')");
                        $result21 = mysql_query ("SELECT * FROM Lists
                                                                 WHERE id = '$nl'
                                                                 limit 1
                                                           ");
                        $listinfo = mysql_fetch_array($result21);
                        $box_status = "4";
                        $box_prompt = "$lang_57";
                                $respond = mysql_query ("SELECT * FROM 12all_Respond
                                                                                WHERE nl = '$nl'
                                                                                AND time = '0'
                                                                                ORDER BY time, subject
                                                                                ");
                                if ($c1 = mysql_num_rows($respond)) {
                                while($respondf = mysql_fetch_array($respond)) {
                                        $fid = $respondf["id"];
                                        $rid = ",$fid,";
                                        $msubject = stripslashes($respondf["subject"]);
                                        $mfromn = stripslashes($respondf["fromn"]);
                                        $mfrome = $respondf["frome"];
                                        $mtype = $respondf["type"];
                                        $mcontent = stripslashes($respondf["content"]);
                                        $membersResult = mysql_query ("SELECT sdate,stime,email,name,id,respond,field1,field2,field3,field4,field5,field6,field7,field8,field9,field10 FROM ListMembers
                                                                                                        WHERE active LIKE '0'
                                                                                                        AND email = '$e'
                                                                                                        AND nl LIKE '$nl'
                                                                                                        ");
                                        $row = mysql_fetch_array($membersResult);
                                                        $sid = $row["id"];
                                                        $email = $row["email"];
                                                        $name = $row["name"];
                                                        $field1 = $row["field1"];
                                                        $field2 = $row["field2"];
                                                        $field3 = $row["field3"];
                                                        $field4 = $row["field4"];
                                                        $field5 = $row["field5"];
                                                        $field6 = $row["field6"];
                                                        $field7 = $row["field7"];
                                                        $field8 = $row["field8"];
                                                        $field9 = $row["field9"];
                                                        $field10 = $row["field10"];
                                                        $ridold = $row["respond"];
                                                                                $urlfinder = mysql_query ("SELECT * FROM Backend
                                                                                                                                        WHERE valid LIKE '1'
                                                                                                                                        limit 1
                                                                                                                                        ");
                                                                                $findurl = mysql_fetch_array($urlfinder);
                                                                                $murl = $findurl["murl"];
                                                                                $mcontent = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nlbox[1]=$nl&email=$email", $mcontent);
                                                                                foreach (array('msubject', 'mcontent') as $var) {
                                                                                        $$var = str_replace ("subscribername", $name, $$var);
                                                                                        $$var = str_replace ("subscriberemail", $email, $$var);
                                                                                        $$var = ereg_replace ("[\]", "", $$var);
                                                                                }
                                                                                for ($i=10; $i>=1; $i--) {
                                                                                        $msubject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $msubject);
                                                                                        $mcontent = str_replace("subscriberfield" . $i, ${'field' . $i}, $mcontent);
                                                                                }
                                                                                $mail = new htmlMimeMail();
                                                                                if ($mtype == 'html') {
                                                                                        $mail->setHtml($mcontent);
                                                                                } elseif ($mtype == 'text') {
                                                                                        $mail->setText($mcontent);
                                                                                }
                                                                                if($mfromn != ""){
                                                                                        $mfromd = "\"".$mfromn."\" <".$mfrome.">";
                                                                                        $mfrome = stripslashes($mfromd);
                                                                                }

                                                                                $mail->setFrom($mfrome);
                                                                                $mail->setSubject($msubject);
                                                                                $sendResult = $mail->send(array($email));
                                                                                mysql_query ("INSERT INTO 12all_RespondT (sid,fid,sdate) VALUES ('$sid' ,'$fid' ,'$s_date')");
                                                                                $ridspond = "$ridold $rid";
                                                                                mysql_query("UPDATE ListMembers SET respond='$ridspond' WHERE (id='$sid')");

                                }

                                }
                if ($link_add4 != ""){
                $locit = "$link_add4?mesg=$box_prompt";
                }

                }

                // CONFIRM UNSUBSCRIBES
                if ($funcml == cunsub){
                        $e = base64_decode($e);
                        $result = mysql_query ("SELECT * FROM Lists
                                                                         WHERE id = '$nl'
                                                                         limit 1
                                                                   ");
                        $thanks = mysql_fetch_array($result);
                        $page = $thanks["confirmoptpage"];
                        mysql_query ("DELETE FROM ListMembers
                                WHERE email = '$e'
                                                                AND nl = '$nl'
                                                                ");
                                                                mysql_query ("INSERT INTO ListMembersU (em, nl) VALUES ('$e' ,'$nl')");
                        $box_status = "4";
                        $box_prompt = "$lang_58";
                        if ($link_unsub4 != ""){
                        $locit = "$link_unsub4?mesg=$box_prompt";
                        }

                }
                if ($locit != ""){

                header("Location: $locit");
                exit();
                }
                // IF NO REDIRECT IS CALLED THE DEFAULT SYSTEM MESSAGE WILL BE DISPLAYED
                print $box_prompt;
?>