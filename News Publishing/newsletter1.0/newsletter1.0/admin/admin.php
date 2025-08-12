<?php 
    session_start(); 
/**
*    
*    @project: newsletter 
*    @file:    admin.php
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
?>
<HTML>
    <HEAD>
	<META http-equiv=Content-Type content="text/html;">
        <META http-equiv=Expires content="0">
        <META content="newsletter administrator" name=description>
        <META content="newsletter administrator" name=keywords>
	<link rel="stylesheet" type="text/css" href="default.css">
    </HEAD>
<BODY style='padding-left:0px;'>
<?php
   
    global $con; 
    
    include_once "../template_engine.inc";
    include_once "../config.php";
    include_once "../newsletter.php";
    include_once "main_functions.php";
    
   
      
    $templatePath = "templates/";   
   
 
    $te = new template_engine($templatePath);
        
    $news = new newsletter($con);
    
    $news->setFromEmail("you_email");
    
    $title = $te->compile_template("title.htm");
    $menu  = $te->compile_template("menu.htm");
 
    
    import_request_variables("gp");
     
    // check login status
    if (!checkLogin()) {
         $content = $te->compile_template("login.htm"); 
    }
   
    // show panel
    if ($cmd == "showpanel") {
       // $content = $te->compile_template("newsletter_panel.htm");
      
    }
    
    // send article
    if ($cmd == "send") {
        $count_emails = $news->send($artid);
        $message = " emails send :$count_emails";
        $content = $te->compile_template("message.htm");
    }
    
    // delete user from list   
    if ($cmd == "deluser") { // login to system
        $sql = " DELETE FROM newsletter_users WHERE email='$email'";
        @mysql_query($sql,$con);
        $content = $te->compile_template("user_list.php");
    }
    
    // show users list  
    if ($cmd == "users_list") { // login to system
        $content = $te->compile_template("user_list.php");
    }
    
    // article list page
    if ($cmd == "artlist") {
        $content = $te->compile_template("articles_list.php");
    }
    
    // delete article;
    if ($cmd == "delart") {
        $sql = "DELETE FROM newsletter WHERE id = $artid";
        $result = @mysql_query($sql,$con);
        $content = $te->compile_template("articles_list.php");
    }
    
    // post news article 
    if ($cmd == "postnews") {
        $next_cmd = "addarticle";
        $content = $te->compile_template("post_article.htm");   
    }
    
     // post news article 
    if ($cmd == "addarticle") {
        $next_cmd = "addarticle";
        if ($article != "") {
            $sql = " INSERT INTO newsletter(article,datetime_posted,article_type) VALUES('$article',Now(),'$articleType') ";
           // echo $sql;
            $result = @mysql_query($sql,$con);
            if ($result == TRUE) {
                $message = 'article saved';
                $content = $te->compile_template("articles_list.php");
            } else {
                $message = "error saving article";
                $content = $te->compile_template("post_article.htm");   
            }
        }
      
    } 
    
    // newsletter email template
    if ($cmd == "net") {
        $email_template_title = " newsletter email template "; 
        $email_subject_label = " email subject";
        $email_body_label  = " email footer ";
        $next_cmd = "savetemplate";
        $type = "newsletter";
        read_email_template("newsletter");
        $content = $te->compile_template("email_template.htm");
    }
      
    // confirm email template
    if ($cmd == "uet") {
        $email_template_title = " unsubscribe email template "; 
        $email_subject_label = " email subject";
        $email_body_label  = " email ";
        $next_cmd = "savetemplate";
        $type = "unsubscribe";
        read_email_template("unsubscribe");
        $content = $te->compile_template("email_template.htm");
    }
    
    // confirm email template
    if ($cmd == "cet") {
        $email_template_title = " confirm email template "; 
        $email_subject_label = " email subject";
        $email_body_label  = " email ";
        $next_cmd = "savetemplate";
        $type = "confirm";
        read_email_template("confirm");
        $content = $te->compile_template("email_template.htm");
    }
      
    
    // welcome email template
    if ($cmd == "wet") {
        $email_template_title = " welcome email template "; 
        $email_subject_label = " email subject";
        $email_body_label  = " email ";
        $next_cmd = "savetemplate";
        $type = "welcome";
        read_email_template("welcome");
        $email_body = $email_body . $news->getConfirmLink("email_address",ENEWSLETTER_URL . "admin/admin.php");
        $content = $te->compile_template("email_template.htm");
    }
     
    
    
    // save emails templates 
    if ($cmd == "savetemplate") {
        if ($type == "welcome") {
            $sql = " UPDATE newsletter_settings SET welcome_email = '$email_body', 
                        welcome_email_subject = '$email_subject'";
            $email_template_title = " welcome email template ";
           
        }
        if ($type == "unsubscribe") {
            $sql = " UPDATE newsletter_settings SET unsubscribe_email = '$email_body', 
                        unsubscribe_email_subject = '$email_subject'";
            $email_template_title = " unsubscribe email template "; 
            
        }
        if ($type == "confirm") {
            $sql = " UPDATE newsletter_settings SET confirm_email = '$email_body', 
                        confirm_email_subject = '$email_subject'";
            $email_template_title = " confirm email template "; 
        }
        if ($type == "newsletter") {
            $sql = " UPDATE newsletter_settings SET newsletter_email_footer = '$email_body', 
                        newsletter_email_subject = '$email_subject'";
            $email_template_title = " newsletter email template "; 
        }
        
        $result = @mysql_query($sql,$con);
        if (($result != FALSE ) && (mysql_affected_rows() > 0)) { // sucessful saved
            $message = " email template saved ";
        } else {    // error ocur
            //ECHO $sql;HTTP_REFERER
            $message = " error  saving email template ";
        }
        $next_cmd = "savetemplate";
        $content = $te->compile_template("email_template.htm");
    }
    
    // show login pagesubscribe
    if ($cmd == "login_page") {
        $content = $te->compile_template("login.htm");
    }
    
    // login to admin panel
    if ($cmd == "login") { // login to system
      if (($password == $admin_user_password) && ($UserName == $admin_user_name)) {
          $_SESSION['user'] = $admin_user_name;
          $menu  = $te->compile_template("menu.htm");
      } else {
          $message = "invalid password or username ";
          $content = $te->compile_template("login.htm");
      }
    }
    
    // logoutemail_template
    if ($cmd == "logout") { 
       unset($_SESSION['user']);
       $content = $te->compile_template("login.htm"); 
       $menu  = $te->compile_template("menu.htm");
    }
    
    
    
  
    
//    
// display page    
//    
    
    ECHO $title;
    ECHO $menu;
    ECHO $content;
?>
</BODY>
