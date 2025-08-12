<body onLoad="window.resizeTo(400,375)">
<?PHP
     #        Test send function

        include_once('engine_admin.inc.php');
        include_once('sender.inc.php');
        $em = $testemail;
        $bgcolor   = "#FFFFFF";
        $frome      = $from;
        $textmesg  = urldecode($Text);
        $htmlmesg  = urldecode($Content);
        $subject = urldecode($subject);
        $currentnl = $nl;
        $msgCounter = 0;
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
                        $msgCounter++;
                        $name        = !empty($name) ? $name : 'Subscriber';
                        $htmlmesg = str_replace("currentnl", $currentnl, $htmlmesg);
                        $textmesg = str_replace("currentnl", $currentnl, $textmesg);
                        $htmlmesg = str_replace("currentmesg", $id, $htmlmesg);
                        $htmlmesg = str_replace("subscriberemail", $em, $htmlmesg);
                        $textmesg = str_replace("subscriberemail", $em, $textmesg);
                        $htmlmesg = stripslashes($htmlmesg);
                        $textmesg = stripslashes($textmesg);
                        $subject  = stripslashes($subject);
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
                        $em_xmid = $em.' , '.$id;
                        $em_xmid=base64_encode($em_xmid);
                        $mail->setHeader('X-mid', $em_xmid);
                        $result = $mail->send(array($em));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="7" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font face="Arial, Helvetica, sans-serif" size="2"><strong><?PHP print $lang_551; ?></strong></font><font color="#666666" size="1" face="Arial, Helvetica, sans-serif"></font></p></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
  <tr>
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="7" bgcolor="#FFFFFF">
        <tr>
          <td> <p><font color="#666666" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_552; ?>:
              <?PHP print $em; ?> </font></p>
            <p><font color="#999999" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_553; ?></font> </p></td>
        </tr>
      </table></td>
  </tr>
</table>