<?
/**
*    
*    @project:     newsletter
*    @file:        newsletter.php
*    @version:     1.0
*    @author:      Konstantin Atanasov
*/


include_once "template_engine.inc";

class newsletter {
    var 
        $template_engine = null,// template engin eobject
        $url = "",              // newsletter url
        $db_connection,         // mysql db connection reference
        $message = "",          // message displayed in newsletter panel
        $error_message = "",    // error message
        $from_email = "",       // From: emaqil header for sending emails via mail function 
        $email_header = "";     // email header 
        
    
    /**
    *    constructor
    *
    */
    function newsletter(&$db_conn) {
        $this->db_connection = $db_conn;
        $this->template_engine = new template_engine("templates/");
        $this->setEmailHeader();
        return $this;
    }
    
   
    /**
    *    set from email header
    *
    */
    function setFromEmail($fromEmail) {
        $this->from_email = $fromEmail;
        $this->setEmailHeader();
    }
    
    /**
    *
    *
    */
    function parseCommands() {
        if (isset($_POST['newslettercmd'])) {
          $cmd = $_POST['newslettercmd'];
        } else {
          $cmd = $_GET['newslettercmd'];
        }   
        
        if (isset($_POST['newsletteremail'])) {
          $email = $_POST['newsletteremail'];
        } else {
          $email = $_GET['newsletteremail'];
        }  
       
        switch($cmd) {
            case "subscribe": {
                if ($this->subscribe($email)) {
                    $this->message = " you are subscribed ";
                } else {
                    $this->message = "  "; // write  error message here
                }
                break;
            }
            case "unsubscribe": {
                if ($this->unsubscribe($email)) {
                    $this->message = " you are unsubscribed ";
                } else {
                   $this->message = "  "; // write error message here
                }
                break;
            }
            case "confirm": {
                if ($this->subscribe_activation($email)) {
                    $this->message = " subscribtion confirmed ";
                } else {
                   $this->message = " error confirm command "; // write error message here
                }
                break;
            }
        }
    }
    
    
    /**
    *    set email content type header
    *
    */
    function setEmailHeader($content_type = "text/plain") {
        $this->email_header = "From:$this->from_email \n";
        $this->email_header = $this->email_header .  "Content-type: $content_type \n";
        $this->email_header = $this->email_header . "Reply-to:$this->from_email \n";   
    }
    
    
    /**
    *    return html code with newsletter panel
    *
    */
    function showPanel($template_file =  "newsletter_panel.htm") {
        global $message;
        $message = $this->message;
        $html = $this->template_engine->compile_template($template_file);
        return $html;
    }
    
    /**
    *    return scalar value from db table 
    *
    */
    function getScalar($tableName,$fieldName,$filter = "") {
        $sql = " SELECT $fieldName FROM $tableName $filter";
        $rs = @mysql_query($sql,$this->db_connection);
        if ($rs == FALSE) {
            return FALSE;
        } else {
            $row = @mysql_fetch_array($rs);
            return $row[$fieldName];
        }
    }
    
    /**
    *    return newsletter article text
    *
    */
    function getNewsletterArticle($articleID) {
        $result = @$this->getScalar("newsletter","article"," WHERE id=$articleID");
        return $result;
    }
    
    /**
    *    return url for unsubscribtion
    *
    */
    function getUnsubscribeLink($email_address) {
        if (defined(ENEWSLETTER_URL)) {
            return ENEWSLETTER_URL . "?newslettercmd=unsubscribe&newsletteremail=$email_address";
        } else {
            return FALSE;
        }
    }
    
  /**
    *    return confirm subscribtion url
    *
    */
    function getConfirmLink($email_address,$base_url = "") {
        if (defined(ENEWSLETTER_URL) && ($base_url == "")) {
            return ENEWSLETTER_URL . "?newslettercmd=confirm&newsletteremail=$email_address";
        } else {
            return $base_url . "?newslettercmd=confirm&newsletteremail=$email_address";
        }
    }
    
    /**
    *    send news email
    *
    */
    function sendNewsEmail($email_address,$articleID) {
         $subject = $this->getScalar("newsletter_settings","newsletter_email_subject");
         $body = $this->getNewsletterArticle($articleID);
         if ($body != FALSE) { // send article
             $footer = $this->getScalar("newsletter_settings","newsletter_email_footer");
             $body = $body . $footer;
             $result = @mail($email_address,$subject,$body,$this->email_header);
         } else { $result = FALSE; }
         return $result;
    }
    
    
    /**
    *    send welcome,activate,newsletter emails
    *
    */
    function sendEmail($email_address,$type,$articleID = "0") {
        $unsubscribe_phplink = $this->getUnsubscribeLink($email_address);
        switch ($type) {    
            case "welcome": {
                $subject = $this->getScalar("newsletter_settings","welcome_email_subject");
                $body = $this->getScalar("newsletter_settings","welcome_email");
                $link =  $this->getConfirmLink($email_address,$this->url);
                $body = $body .  $link;// add confirm link 
                $result = @mail($email_address,$subject,$body,$this->email_header); // send welcome email
                return $result;
            }
            case "unsubscribe": {
            
                $subject = $this->getScalar("newsletter_settings","unsubscribe_email_subject");
                $body = $this->getScalar("newsletter_settings","unsubscribe_email");
                $result = @mail($email_address,$subject,$body,$this->email_header);
                return $result;
            }
            
            case "newsletter": {
                $article_status = $this->getScalar("newsletter","status"," WHERE id = $articleID ");
                if ($article_status == 0) {  // article not send 
                  $article_type = $this->getScalar("newsletter","article_type"," WHERE id = $articleID ");
                  $this->setEmailHeader($article_type);
                   return $this->sendNewsEmail($email_address,$articleID);
                } else {
                    return FALSE;
                }
            }
        }
        
        return FALSE;
    }
    
    
    /**
    *    send welcome email to subscrinbed user
    *
    */
    function sendWelcomeEmail($email_address) {
        return $this->sendEmail($email_address,"welcome");
    }
    
    /**
    *    send unsubscribed email notification
    *
    */
    function sendUnsubscribeEmail($email_address) {
         return $this->sendEmail($email_address,"unsubscribed");
    }
    
    
    /**
    *    activating subscribed user
    *
    */
    function subscribe_activation($email_address) {
        $sql= " UPDATE newsletter_users SET status = 2 WHERE email = '$email_address'";
        $result = @mysql_query($sql,$this->db_connection);
        return $result;
    }
    
    
    /**
    *    send news to all subscribed users
    *
    */
    function send($articleID) {
        $sql = " SELECT email FROM newsletter_users WHERE status = 2 "; // only confirmed users
        $rs = @mysql_query($sql,$this->db_connection);
        $count = 0;  // number of emails send
        while ($row = @mysql_fetch_array($rs)) {
            $email = $row['email'];
            $result = @$this->sendEmail($email,"newsletter",$articleID);
            if ($result == TRUE) {
                $count = $count + 1;
            } else {
                $this->error_message =  $this->error_message + 1;
            }
        }
        // update article astatus
        $sql = " UPDATE newsletter SET status = 1 WHERE id = $articleID ";
        $res = @mysql_query($sql,$this->db_connection);
        return $count;
    }
    
    /**
    *     unsubscribe email address to newsletters
    *
    */
    function unsubscribe($email_address) {
        $sql = "UPDATE newsletter_users SET status = 1 WHERE email = '$email_address'";
        $result = @mysql_query($sql,$this->db_connection);
        if ($result != FALSE) {
         //   $result = $this->sendUnsubscribeEmail($email_address);
        }
        return $result;
    }
    
    /**
    *    subscribe email address to newsletters
    *
    */
    function subscribe($email_address) {
        $sql = "INSERT INTO newsletter_users(email,datetime_subscribed) VALUES('$email_address','Now()')";
        $result = @mysql_query($sql,$this->db_connection);
        if ($result == FALSE) {
           $sql = "UPDATE newsletter_users SET status = 0 WHERE email='$email_address'";
           $result = @mysql_query($sql,$this->db_connection);
        }
        if ($result != FALSE) {
            $result = $this->sendWelcomeEmail($email_address);
        }
        return $result;
    }
    
  
    
    
    
}





?>
