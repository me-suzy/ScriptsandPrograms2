<?php
function sendMailProfile($email,$from,$fromName,$html,$text,$imageFiles, $attachFiles){

        error_reporting(E_ALL);
        //include(__CFG_PATH_LIBS."/mail/".'class.html.mime.mail.inc');

/***************************************
** Example of usage. This example shows
** how to use the class with html,
** embedded images, and an attachment,
** using the usual methods.
***************************************/

		define('CRLF', "\r\n", TRUE);
	
        $mail = new html_mime_mail(array('X-Mailer: Html Mime Mail Class'));

        
        //$attachment = $mail->get_file('example.zip');
/*
		$html = $mail->get_file('1.html');
		echo "aaaaa<br>";
		echo "$html<br>";
*/		
        //$mail->add_html($html, $text,__CFG_PATH_PROFILEIMAGE);
        $mail->add_html($html, $text);

		$imgs = array();
        foreach ( $imageFiles as $imageFile ) 
		{
		        	$url=$imageFile["imageURL"];
		        	//echo "add $url<br>";
		        	$img = $mail->get_file(__CFG_PATH_PROFILEIMAGE.$url);
		        	$mail->add_html_image($img, $url, 'image/gif');
		}
		
		//$background = $mail->get_file('background.gif');
        
		/*
		$url=$imageFiles[0]["imageURL"];
		echo "mail:url=$url<br>";
		$img = $mail->get_file(__CFG_PATH_PROFILEIMAGE.$url);
		$mail->add_html_image($img, $url, 'image/gif');
		
		*/        
        
		
        //$mail->add_attachment($attachment, 'example.zip', 'application/zip');

        if(!$mail->build_message())
            die('Failed to build email');

        $mail->send('Alex', $email, $fromName, $from , 'ppc');

        /***************************************
        ** Send the email using smtp method.
                ** This is the preferred method of sending.
        ***************************************/
/*
        include('class.smtp.inc');

                $params = array(
                                                'host' => '192.168.1.1',                // Mail server address
                                                'port' => 25,                           // Mail server port
                                                'helo' => 'youdomain.com',    		// Use your domain here.
                                                'auth' => FALSE,                        // Whether to use authentication or not.
                                                'user' => '',                           // Authentication username
                                                'pass' => ''                            // Authentication password
                                           );

        $smtp =& smtp::connect($params);

                $send_params = array(
                                                        'from'                  => 'you@youdomain.com',                       // The return path
                                                        'recipients'    	=> 'you@youdomain.com',               // Can be more than one address in this array.
                                                        'headers'               => array(
                                                                                                                'From: "Alex" <you@youdomain.com>',
                                                                                                                'To:  "Alex" <you@youdomain.com>',    // A To: header is necessary, but does
                                                                                                                'Subject: Test email'                                                   // not have to match the recipients list.
                                                                                                        )
                                                    );
        $mail->smtp_send($smtp, $send_params);

        /***************************************
        ** Debug stuff. Entirely unnecessary.
        ***************************************/

//        echo '<PRE>'.htmlentities($mail->get_rfc822('CyKuH', 'cykuh@[10.1.1.2]', 'Joe', 'joe@example.com', 'Example email using HTML Mime Mail class')).'</PRE>';


//	fclose($htmlHandle);	
//	fclose($textHandle);	
}
?>
