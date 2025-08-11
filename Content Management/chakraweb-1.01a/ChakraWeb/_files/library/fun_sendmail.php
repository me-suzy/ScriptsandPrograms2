<?php
// ----------------------------------------------------------------------
// ModName: fun_sendmail.php
// Purpose: SendMail facility
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_sendmail.php] file directly...");

require_once(dirname(__FILE__) . '/htmMail/htmlMimeMail.php');


//send one email
function MailSend($target, $from, $replay, $subject, $html, $text)
{
    $mail = new htmlMimeMail();

    $mail->setSMTPParams(SMTP_HOST, SMTP_PORT, SMTP_HELO);

    $mail->setHtml($html, $text);
    $mail->setReturnPath($replay);
    $mail->setFrom($from);
    $mail->setSubject($subject);
    $mail->setHeader('X-Mailer', 'Quick4All Mailer (http://chakra.quick4all.com)');
		
    $result = $mail->send(array($target), MAIL_TYPE);

    //if (!$result)
    //{
    //    print_r($mail->errors);
    //}

    return $result;
}


//ugly code, but it work :)
function HtmlToText($htm)
{
    $htm = str_replace("\r\n", " ", $htm);
    $htm = str_replace("\n\r", " ", $htm);
    $htm = str_replace("\n", " ", $htm);
    $htm = str_replace("<p", "\n\n<p", $htm);
    $htm = str_replace("<P", "\n\n<p", $htm);
    $htm = str_replace("</P>", "</p>", $htm);
    $htm = str_replace("<p></p>", "", $htm);
    $htm = str_replace("<p>&nbsp;</p>", "", $htm);
    $htm = str_replace("<br", "\n<br", $htm);
    $htm = str_replace("<BR", "\n<br", $htm);
    $htm = str_replace("<tr", "\n<tr", $htm);
    $htm = str_replace("<TR", "\n<tr", $htm);
    $htm = str_replace("<li>", "\n\n- ", $htm);
    $htm = str_replace("<LI>", "\n\n- ", $htm);
    $htm = str_replace("\t", " ", $htm);

    $htm = HRefToText($htm);
    $htm = ereg_replace("<([^>]|\n)*>", '', $htm);

    for ($i=0;$i<3;$i++)
    {
        $htm = str_replace("  ", " ", $htm);
        $htm = str_replace("  ", " ", $htm);
        $htm = str_replace("\n ", "\n", $htm);
    }

    $htm = str_replace("\n \n", "\n\n", $htm);
    $htm = str_replace("\n\n\n", "\n\n", $htm);
    $htm = str_replace("\n\n\n", "\n\n", $htm);

    $htm = TextSlice($htm, 80);

    return $htm;
}

function HRefToText($htm)
{
    return preg_replace_callback("|<a href=\"(.*)\"(.*)>(.*)</a>|mi", 'HRefToTextCallBack', $htm);
}

function HRefToTextCallBack($match)
{
    return $match[3].' ('.$match[1].')';
}

//ugly code again, but work
function TextSlice($txt, $maxlengh)
{
    $artxt = explode("\n", $txt);

    $out = '';
    foreach($artxt as $line)
    {
        if (strlen($line) <= $maxlengh)
            $out .= $line;
        else
        {
            while (strlen($line) > $maxlengh)
            {
                $pos = FindTextSlicePos($line, $maxlengh);
                $out  .= substr($line, 0, $pos)."\n";
                $line = substr($line, $pos);
            }
            $out  .= $line;
        }
        $out .= "\n";
    }

    return $out;
}

function FindTextSlicePos($line, $maxlengh)
{
    $str = substr($line, 0, $maxlengh);

    $pos = strrpos($str, ' ');
    if ($pos === false)
    {
        return $maxlengh;
    }
    else
    {
        return $pos+1;
    }
}



?>
