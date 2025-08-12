<?PHP
        set_time_limit(0);
        ignore_user_abort();
        require("engine.inc.php");
        require('sender.inc.php');
        require('send_app_settings.inc.php');

/**
* Query DB
*/
        $bback = mysql_query ("SELECT * FROM Backend
                                                WHERE valid LIKE '1'
                                                limit 1
                                                ");
        $bdback = mysql_fetch_array($bback);
        $btype = $bdback["btype"];
        $bdomain = $bdback["bdomain"];
        $bdomain2 = $bdback["bdomain2"];

        $result5 = mysql_query ("SELECT * FROM Messages
                                 WHERE id LIKE '$id'
                                                         LIMIT 1");
        $row5 = mysql_fetch_array($result5);
        $sendval = "resend";
        $bgcolor   = "#FFFFFF";
        $from      = $row5["mfrom"];
        $subject   = $row5["subject"];
        $textmesg  = $row5["textmesg"];
        $htmlmesg  = $row5["htmlmesg"];
        if ($predone == ""){
        $predone   = $row5["sent"];
        }
        if ($predone == ""){
        $predone = 0;
        }
        $nl        = $row5["nl"];
        $currentnl = $row5["nl"];
        $type      = $row5["type"];
        $links     = $row5["tlinks"];
        $link1n    = $row5["link1n"];
        $link1t    = $row5["link1t"];
        $msgCounter = 0;

/**
* Query for addresses
*/
        $limitClause = $sendval == 'resend' ? "LIMIT $predone,$sa_amt" : '';
        $membersResult = mysql_query ("SELECT * FROM ListMembers
                                                                         WHERE active LIKE '0'
                                                                        AND email != ''
                                                                        AND nl LIKE '$nl'
                                                                        ORDER BY id
                                                                        $limitClause ");

/**
* Loop thru results
*/
        if (mysql_num_rows($membersResult)) {
                while ($row = mysql_fetch_array($membersResult)) {
                        $msgCounter++;

                        $subject     = $row5["subject"];
                        $textmesg    = $row5["textmesg"];
                        $htmlmesg    = $row5["htmlmesg"];
                        $em          = $row["email"];
                        $emailid     = $row["email"];
                        $emailbounce = $row["bounce"];
                        $name        = !empty($row["name"]) ? $row["name"] : 'Subscriber';

                        /**
                * Assign fields
                */
                        for ($i=1; $i<=10; $i++) {
                                ${'field' . $i} = $row['field' . $i];
                        }

                        $htmlmesg = str_replace("subscriberemailec", base64_encode($em), $htmlmesg);

                        /**
            * Loop through subject, text and html replacing
                        * certain things.
                */
                        foreach (array('subject', 'textmesg', 'htmlmesg') as $var) {
                                $$var = str_replace ("subscribername", $name, $$var);
                                $$var = str_replace ("subscriberemail", $em, $$var);
                                $$var = ereg_replace ("[\]", "", $$var);
                        }

                        $htmlmesg = str_replace("currentnl", $currentnl, $htmlmesg);
                        $htmlmesg = str_replace("currentmesg", $id, $htmlmesg);

                        /**
                * Loop thru all subscriber fields replacing content in
                        * subject, text and html.
                */
                        for ($i=1; $i<=10; $i++) {
                                $subject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $subject);
                                $textmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $textmesg);
                                $htmlmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $htmlmesg);
                        }

                        /**
                * If you're stripping slashes here because you're doing addslashes()
                        * when you insert the addresses/data, then don't.
                */
                        $htmlmesg = stripslashes($htmlmesg);
                        $textmesg = stripslashes($textmesg);
                        $subject  = stripslashes($subject);


                        /**
                * Build and send the email
                */
                        $mail = new htmlMimeMail();
                        $text = $textmesg;
                        $html = $htmlmesg;

                        if ($type == 'multi') {
                                $mail->setHtml($html, $text, './');

                        } elseif ($type == 'text') {
                                $mail->setText($text);

                        } else {
                                $mail->setHtml($html);
                        }

                        $mail->setFrom($from);
                        $mail->setSubject($subject);
                        if ($btype == "none"){
                        $mail->setReturnPath($from);
                        }
                        else {
                        $mail->setReturnPath('bounce+' . str_replace('@', '=', $em) . '@nulled.ru.com');
                        }
                        if ($link1n != ""){
                        $locattach = "attach/$link1n";
                        $attachment = $mail->getFile($locattach);
                      $mail->addAttachment($attachment, $link1n, $link1t);
                        }

                        $sendResult = $mail->send(array($em));

                        /**
                * Update database ... ?
                */
                        mysql_query("UPDATE Messages SET sent = sent + 1 WHERE id = '$id'");

                        if ($msgCounter % 100 == 0){
                                sleep(1);
                        }

                        flush();
                }

                /**
                * Update database
                */
                mysql_query("UPDATE Messages SET completed = '1' WHERE id = '$id'");
                $titol = $predone + $sa_amt;
                print "Sent e-amils $predone to $titol<br>";
                print("$msgCounter messages sent this process.<br>");
                print "Next process in $sa_time seconds.";
        } else {
                print("Mailing Complete.<p> You may now close this window.");
        }
        $predone = $predone + $sa_amt;
?>
<html>
<head>
<title>Sending...</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<META HTTP-EQUIV="Refresh" CONTENT="<?PHP print $sa_time; ?>; URL=send_appm.php?nl=<?PHP print $nl; ?>&predone=<?PHP print $predone; ?>&id=<?PHP print $id; ?>&nl=<?PHP print $nl; ?>">

</head>

<body bgcolor="#FFFFFF" text="#000000">

</body>
</html>