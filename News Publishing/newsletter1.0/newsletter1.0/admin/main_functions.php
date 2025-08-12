<?php
/**
*    
*    @project: newsletter 
*    @file:    main_functions.php
*    @version: 1.0
*    @author:  Konstantin Atanasov
*
*
NO WARRANTY
 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
 THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES 
 PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
 REPAIR OR CORRECTION.
*/



   /**
   *    check if login in admin panel and from where script is executed
   *
   */
    function checkLogin() {
        global $cmd;
        $referer = $_SERVER['HTTP_REFERER'];
        if ((isset($referer) == FALSE) || ($referer == "") || (is_null($referer))) {
            $cmd = "login_page";
            return FALSE;
        }
        
        if ((isset($_SESSION['user']) == false) && ($cmd != "login")) {
            $cmd = "login_page";
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
    *    get preview content from large text
    *
    *
    */
    function getPreviewContent($text,$length=15) {
	$preview =split(' ',$text,$length);
	for ($i = 0;$i <  ($length-1);$i = $i +1) {
		$result = $result . ' ' . $preview[$i];
	}
	$result = $result . "...";
	return $result;
    }
        
    
    /**
    * read email template settings from db
    *
    */
    function read_email_template($type) {
        global $con,$email_body,$email_subject;
        
        switch($type) {
            case "welcome": {
                $sql = " SELECT welcome_email as email_body,
                            welcome_email_subject as email_subject FROM newsletter_settings ";
                break;
            }
            case "confirm": {
                $sql = " SELECT confirm_email as email_body,
                                confirm_email_subject as email_subject FROM newsletter_settings ";
                break;
            }
            case "newsletter": {
                $sql = " SELECT newsletter_email_footer as email_body,
                                newsletter_email_subject as email_subject FROM newsletter_settings ";
                break;
            }
             case "unsubscribe": {
                $sql = " SELECT unsubscribe_email as email_body,
                                unsubscribe_email_subject as email_subject FROM newsletter_settings ";
                break;
            }
        }
        //ECHO $sql;
        $rs = @mysql_query($sql,$con);
        $row = @mysql_fetch_array($rs);
        $email_body = $row['email_body'];
        $email_subject = $row['email_subject'];
        
        return $rs;
    }
 

?>
