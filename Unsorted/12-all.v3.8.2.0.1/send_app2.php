<?PHP

        #        INITIAL SENDING FUNCTIONS

        flush();
        mysql_close($db_link);
        @ignore_user_abort(1);
        ini_set('max_execution_time', '950*60');
        set_time_limit (950*60);
        require("engine.inc.php");
        include_once('sender.inc.php');

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
        $breturn = $bdback["pop_em"];
        $result5 = mysql_query ("SELECT * FROM Messages
                                 WHERE id LIKE '$id'
                                                         LIMIT 1");
        $row5 = mysql_fetch_array($result5);

        $bgcolor   = "#FFFFFF";
        $from      = $row5["mfrom"];
        $frome      = $row5["mfrom"];
        $fromn     = $row5["mfromn"];
        $subject   = $row5["subject"];
        $textmesg  = $row5["textmesg"];
        $htmlmesg  = $row5["htmlmesg"];
        $predone   = $row5["sent"];
        $nl        = $row5["nl"];
        $currentnl = $row5["nl"];
        $type      = $row5["type"];
        $links     = $row5["tlinks"];
        $link1n    = $row5["link1n"];
        $link1t    = $row5["link1t"];
        $filter    = $row5["filter"];
        $mesg_id    = $row5["mesg_id"];
        $mdata_d    = $row5["mdate"];
        $mdata_t    = $row5["mtime"];
        $mdata_a    = $row5["amt"];
        $textmesg = ereg_replace ("\r", "", $textmesg);
        $msgCounter = 0;
        if ($link1n != ""){
        $link1n=explode("  .  ",$link1n);
    $link1t=explode("  .  ",$link1t);
        }
        if($predone == "0"){

        $filterdata = "";
                                if ($filter != ""){
                                        $prefilter = "AND nl LIKE '$nl'
                                                                AND email != ''
                                                                AND active LIKE '0'";
                                        $ffind = mysql_query ("SELECT * FROM Templates
                                                                                 WHERE id LIKE '$filter'
                                                                                 LIMIT 1
                                                                                ");
                                        $fresult = mysql_fetch_array($ffind);
                                        $filterdata = stripslashes($fresult["content"]);
                                        $filterdata = "AND $filterdata";
                                        $filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
                                }
                                $findcount = mysql_query ("SELECT * FROM ListMembers
                                WHERE nl = '$nl'
                                AND email != ''
                                AND active = '0'
                                $filterdata
                                ");
                                $countdata = mysql_num_rows($findcount);
                                mysql_query("UPDATE Messages SET amt = $countdata WHERE id = '$id'");
        }
        if($fromn != ""){
                $fq = '"';
                $from = "\"".$fromn."\" <".$from.">";

                //$fq$fromn$fq <$from>";
                $from = stripslashes($from);
        }

        $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
        $chk = mysql_fetch_array($check);

/**
* Query for addresses
*/

        $filterdata = "";
        if ($filter != ""){
                $prefilter = "AND nl LIKE '$nl'
                                        AND email != ''
                                        AND active LIKE '0'";
                $ffind = mysql_query ("SELECT * FROM Templates
                                                         WHERE id LIKE '$filter'
                                                         LIMIT 1
                                                        ");
                $fresult = mysql_fetch_array($ffind);
                $filterdata = stripslashes($fresult["content"]);
                $filterdata = "AND $filterdata";
                $filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
        }

        $limitClause = $sendval == 'resend' ? "LIMIT $predone,80000000" : '';
        $membersResult = mysql_query ("SELECT * FROM ListMembers
                                                                         WHERE active LIKE '0'
                                                                        AND email != ''
                                                                        AND nl LIKE '$nl'
                                                                        $filterdata
                                                                        ORDER BY id
                                                                        $limitClause");

/**
* Loop thru results
*/
        if (mysql_num_rows($membersResult)) {
                while ($row = mysql_fetch_array($membersResult)) {
                $em = $row["email"];
                $eid = $row["id"];

                                $status_check = mysql_query ("SELECT * FROM Messages
                                                                                 WHERE id LIKE '$id'
                                                                                 LIMIT 1");
                                $status_checker = mysql_fetch_array($status_check);

                                if ($status_checker["status"] == "3" OR $status_checker["completed"] == "1"){
                                die();
                                }

                                $em          = $row["email"];
                                $pastfind = mysql_query ("SELECT COUNT(email) FROM 12all_MesgTemp
                         WHERE runid = '$mesg_id'
                                                 AND email = '$em'
                       ");
                                $pastcheck = mysql_result($pastfind, 0, 0);
                                if ($pastcheck < "1"){

                        $msgCounter++;

                        $subject     = $row5["subject"];
                        $textmesg    = $row5["textmesg"];
                        $htmlmesg    = $row5["htmlmesg"];
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
                        $htmlmesg = str_replace("%subscriberid%", base64_encode($eid), $htmlmesg);
                        $textmesg = str_replace("%subscriberid%", base64_encode($eid), $textmesg);
                        /**
            * Loop through subject, text and html replacing
                        * certain things.
                */

                        //if ($chk["a_pz"] == "0") {

                        foreach (array('subject', 'textmesg', 'htmlmesg') as $var) {
                                $$var = str_replace ("subscribername", $name, $$var);
                                $$var = str_replace ("subscriberemail", $em, $$var);
                                $$var = ereg_replace ("[\]", "", $$var);
                        //}
                        }
                        $htmlmesg = str_replace("currentnl", $currentnl, $htmlmesg);
                        $textmesg = str_replace("currentnl", $currentnl, $textmesg);
                        $textmesg = ereg_replace ("\r", "", $textmesg);
                        $htmlmesg = str_replace("currentmesg", $id, $htmlmesg);

                        /**
                * Loop thru all subscriber fields replacing content in
                        * subject, text and html.
                */
                        if ($chk["a_pz"] == "0") {

                        for ($i=10; $i>=1; $i--) {
                                $subject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $subject);
                                $textmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $textmesg);
                                $htmlmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $htmlmesg);
                        }
                        }

                        $htmlmesg = stripslashes($htmlmesg);
                        $textmesg = stripslashes($textmesg);
                        //$subject  = stripslashes($subject);


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
                        //$mail->setReturnPath($breturn);

                        if ($btype == "none"){
                        $mail->setReturnPath($frome);
                        }
                        else {
                        $mail->setReturnPath($breturn);
                        }
                        if ($link1n != ""){
                          $at_num = "0";
                                foreach ($link1n as $ataa){
                                //$ataa = $link1n;
                            $ataa = str_replace("  .  ", "", $ataa);
                                if ($ataa != ""){
                                $locattach = "attach/$ataa";
                                $attachment = $mail->getFile($locattach);
                                $at1t = $link1t[$at_num];
                                $mail->addAttachment($attachment, $ataa, $at1t);
                                }
                                $at_nm++;
                                }
                        }
                        $em_xmid = $em.' , '.$id;
                        $em_xmid=base64_encode($em_xmid);
                        $mail->setHeader('X-mid', $em_xmid);
                        $sendResult = $mail->send(array($em));
                        }
                        //$sendResult = $mail->send(array($em));
                        /**
                * Update database ... ?
                */
                        mysql_query("UPDATE Messages SET sent = sent + 1,d_check = 0,status = 0 WHERE id = '$id'");
                        mysql_query ("INSERT INTO 12all_MesgTemp (runid, email) VALUES ('$mesg_id', '$em')");
                        }
                        // CHECKS NUMBER OF MESSAGES SENT.  BY DEFAULT EVERY 40 MESSAGES THE SCRIPT WILL SLEEP FOR 3 SECONDS.
                        if ($msgCounter % 40 == 0){
                                mysql_close($db_link);
                                sleep(2);
                                require("engine.inc.php");
                                $msgCounter == 0;
                        }
                        flush();
                }

                /**
                * Update database
                */
                mysql_query("UPDATE Messages SET completed = '1' WHERE id = '$id'");
                mysql_query("UPDATE 12all_MesgId SET numlist_comp = numlist_comp + 1 WHERE id = '$mesg_id'");
                        $mesgidf = mysql_query ("SELECT * FROM 12all_MesgId
                         WHERE id = '$mesg_id'
                                                 LIMIT 1
                       ");
                        $mesgid = mysql_fetch_array($mesgidf);
                        $mesg_tot = $mesgid["numlist"];
                        $mesg_comp = $mesgid["numlist_comp"];
                        if ($mesg_comp >= $mesg_tot){
                                mysql_query ("DELETE FROM 12all_MesgTemp
                                                                        WHERE runid = '$mesg_id'
                                                                        ");
                        }
                //print("$msgCounter messages sent...");
                                                $check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
                                                 limit 1
                       ");
                        $chk = mysql_fetch_array($check);
                        if ($chk["a_em"] != ""){

                        $em = $chk["a_em"];
                        $mail = new htmlMimeMail();
                        $text = $textmesg;
                        $html = $htmlmesg;
                        $subject = "COPY OF LAST MAILED IN MAILING - $subject";
                        if ($text != ""){
                        $text = "Date & Time Sending Started:  $mdata_d  ( $mdata_t )\nRecipients:  $mdata_a\n\n$text";
                        }
                        if ($html != ""){
                        $html = "Date & Time Sending Started:  $mdata_d  ( $mdata_t )<br>Recipients:  $mdata_a<br><br>$html";
                        }
                        if ($type == 'multi') {
                                $mail->setHtml($html, $text, './');

                        } elseif ($type == 'text') {
                                $mail->setText($text);

                        } else {
                                $mail->setHtml($html);
                        }

                        $mail->setFrom("$from");
                        $mail->setSubject($subject);
                        $mail->setReturnPath($breturn);

                        if ($btype == "none"){
                        $mail->setReturnPath($from);
                        }
                        else {
                        $mail->setReturnPath($breturn);
                        }
                        $em_xmid=base64_encode($em);
                        $mail->setHeader('X-mid', $em_xmid);
                        $sendResult = $mail->send(array($em));
        }
        else {
                //print("Error: Your mailing list does not contain any members.");
                mysql_query("UPDATE Messages SET completed = '1' WHERE id = '$id'");
                mysql_query("UPDATE 12all_MesgId SET numlist_comp = numlist_comp + 1 WHERE id = '$mesg_id'");
        }
?>