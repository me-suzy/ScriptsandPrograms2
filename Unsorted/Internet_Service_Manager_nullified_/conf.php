<?
/////////////////////  MAIN CONFIG FILE /////////////////////////
// Program Name         : Internet Service Manager               
// Program Version      : 1.0                                    
// Program Author       : InternetServiceManager.com             
// Supplied by          : Zeedy2k                                
// Nullified by         : CyKuH [WTN]                            
// This is the main config file.                                 
// Variable descriptions are next to each variable               
// See documentation for more info!                              
/////////////////////////////////////////////////////////////////
  include "../edit_this.php";
  
  ## EDITING THIS FILE MAY CAUSE ERRORS ONLY EDIT VARIABLES THAT YOU ARE SURE YOU UNDERSTAND!
  
  //client site variables...
  $client_template_dir=$main_dir.'/clients/templates';
  
  //email variables...
  $email_execution_method="include"; //either include or cron, if you have a cron task executing send_emails.php set to cron otherwise leave as include.
  $email_check_interval=200; //in seconds, only needed for include method, otherwise this is the gab between cron execution of the script..

  //mime file types..
  $email_attatchment_types[txt]="plain/txt";
  $email_attatchment_types[html]="plain/html";
  $email_attatchment_types[htm]="plain/htm";
  $email_attatchment_types[pdf]="application/pdf";
  $email_attatchment_types[rtf]="application/rtf";
  $email_attatchment_types[zip]="application/zip";
  $email_attatchment_types[gif]="image/gif";
  $email_attatchment_types[jpeg]="image/jpeg";
  $email_attatchment_types[jpg]="image/jpeg";

  //chat variables..
  $chat_admin_enter="Hi. You are speaking with %admin"; //message displayed when an admin enters a chat!
  $chat_online_image=$main_url."/images/online.gif"; //default admins online image, if one isnt fed to the chat_status.php script.
  $chat_offline_image=$main_url."/images/offline.gif"; //default admins offline image, if one isnt fed to the chat_status.php script.
  $please_wait_message="<font color=green>----Welcome. A support technician will be with you shortly!"; //message displayed to a client when they request a chat.
  
  //admin variables...
  $admin_color="#006699";  //hex code for the colour of all the bars in the admin area..
  $admin_color_2="#efefef"; //hex code of the secondary colour for admin!
  $admin_logo="admin_logo.gif";  //url location of the logo you wish to use in the admin pages.
  $admin_font="verdana,arial"; //font to use in the admin pages.
  $admin_font_color="black";
  $admin_font_color_2="white";
  $admin_auth_type="ht"; //admin login type, choose between ht, se (sessions), co (cookies)
  $image_url=$main_url."/images";
  $image_dir=$main_dir."/images";
  $admin_news_page=""; 
  $admin_update_check=""; //dont change this, unless you dont want to be notified of future updates to the software.
  $thisversion=1; //dont change this!
  
  //support system variables..
  $support_priorities=array(0=>"Normal", 1=>"Urgent", 2=>"VERY URGENT"); //no need to change.
  $support_response_subject="Response to trouble ticket: %ticketid%"; //subject of response to support ticket email.
  $support_response_link=$main_url."/clients/support_ticket.php?id=%ticketid%&key=%key%"; //format of the link users click to follow up a support request.

  //billing variables..
  $billing_methods=array("none", "email", "mail"); //dont change.
  $payment_unit="USD$"; //units in which money will be handled by the system.
  $default_due_date=604800*2; //in seconds, set as one week..
  $email_invoice_item_format="%details%       %cost%       \n";
  $html_invoice_item_format="%details%    |   %cost%    |   <BR>";
  $email_statement_item_format="%details%       %cost%   |   %already_paid%    \n";
  $html_statement_item_format="%details%    |   %cost%   |   %already_paid%<BR>";
  $billing_name="Billing Department";
  $invoice_email_subject="Invoice Number: %invoice_number%"; //subject of invoice email.
  $reminder_email_subject="Invoice Number: %invoice_id% OVERDUE!"; //subject of billing reminder email.
  $reciept_email_subject="Reciept #: %id%"; //subject of receipt email.
  $add_on_sales_tax=0; //Add on sales tax to total amount of invoice. 1 = yes; 0 = no
  $billing_auth_key_length=5; //length of billing authorisation keys.
  
  //project_management_variables..
  $upload_dir=$main_dir."/uploads";
  $upload_url=$main_url."/uploads"; //the url to the above directory.
  

  
function sales_tax($total)
{
    return round($total/9);
	//in the brackets above you can modify the sales tax. Currently it is calculated at one ninth of the total sale.
}

  
  
  
  
  /////////// END CONFIGURATION! DO NOT EDIT BELOW THIS LINE!!! ##########
  mysql_connect($mysql_host, $mysql_user, $mysql_pass);
  mysql_select_db($mysql_db);
  
  ?>
