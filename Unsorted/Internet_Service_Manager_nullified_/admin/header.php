<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include_once "../conf.php";
include "auth.php";
echo '<script language="javascript">';
if($email_execution_method=="include" && mysql_num_rows(mysql_query("SELECT * FROM admins WHERE id='$admin_id' && last_check<'".(time()-$email_check_interval)."'"))){
?>
             window.name='opener';
             var newwindow=window.open('send_emails.php','CheckEmail','width=20,height=20,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
             newwindow.blur()
			 <?}?>
			 
			 function popChatMonitor(){
				var chatmonitorwindow=window.open('chat_monitor.php?opened=2','popupwinPUP','width=300,height=250,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');        				 
			 }
    </script>
 
<html>
<head>
<title>Admin!</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
       .left_menu{font: <?echo $admin_font;?>; text-decoratation: none; color: <?echo $admin_font_color;?>};
       .other_text{font: <?echo $admin_font;?>; text-decoratation: none; color: <?echo $admin_font_color_2;?>};
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" rightmargin="0">


<table width="100%" border="0" cellspacing="0" cellpadding="5" height="100%" >
  <tr>
    <td height="94" colspan="2"><img src="<?echo $admin_logo;?>"></td>
  </tr>
  <tr bgcolor="<?echo $admin_color;?>">
    <td width="143" height="7"></td>
    <td width="844" height="7"></td>
  </tr>
  <tr>
    <td width="143" valign="top" bgcolor="<?echo $admin_color_2;?>"> <br>
      <table width="143" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">General&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>
                     <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="index.php">Admin Home
            </a></font> </td>
        </tr>
		      <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="auth.php?logout=immediate">LOGOUT
            </a></font> </td>
        </tr>
			    <?
          $newemail=mysql_num_rows(mysql_query("SELECT * FROM emails WHERE handled='0' && to_id='$admin_id' && type='0'"));
        ?>
               <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="email_inbox.php">New Email</a> (<?echo $newemail;?>)
            </font> </td>
        </tr>
			            <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="compose.php">Compose Mail
            </a></font> </td>
        </tr>
             <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="email_inbox.php?folder=sent">Sent Items
            </a></font> </td>
        </tr>
		 <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="quick_msg.php?">Quick Msg's
            </a></font> </td>
        </tr>
        <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="client_list.php">Client
            List</a></font> </td>
        </tr>
		  <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="todo.php">Todo List
            </a></font> </td>
        </tr>
		 <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="javascript: popChatMonitor()">Go Online
            </a></font> </td>
        </tr>
      
              <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%editclients,%' && id='$admin_id'"))){
        ?>
             <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="add_client.php">Add Client</a></font> </td>
        </tr>
             <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="nullification.php">Nullification Info</a></font> </td>
        </tr>
        <?}?>
      </table>
      <P>
      <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%support,%' && id='$admin_id'"))){
        ?>
               <table width="143" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">Support&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>
        <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="support.php">New Tickets</a> (<?
          echo mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE completed='0'"))

          ?>)</font> </td>
        </tr>
               <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="enter_support_ticket.php">Enter Ticket
          </a></font> </td>
        </tr>
           <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="support_history.php">Support History
          </a></font> </td>
        </tr>
      </table><P>
        <?
        }
      
      ?>
      
            <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%billing,%' && id='$admin_id'"))){
        ?>
               <table width="143" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">Billing&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>               <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="billing.php">Outstanding Bills
          </a></font> </td>
        </tr>
           <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="create_invoice.php">Invoice Client
          </a></font> </td>
        </tr>
               <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="receive_money.php">Receive Money
          </a></font> </td>
        </tr>
           <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="billing_reminders.php">Billing Reminders
          </a></font> </td>
        </tr>
                   <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="to_bill.php">Ready for Billing
          </a></font> </td>
        </tr>
               <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="send_statement.php">Send Statement
          </a></font> </td>
        </tr>
      </table>
        <?
        }

      ?>
      
            <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%developer,%' && id='$admin_id'"))){
        ?>
                <P>
              <table width="143" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">Projects&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>
                <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="change_project_status.php">Project Status
            </a></font> </td>
        </tr>
        
        <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="file_management.php">Files Management
            </a></font> </td>
        </tr>
            </table>
        <?}?>
		
		     <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%export,%' && id='$admin_id'"))){
        ?>
                <P><table width="143" border="0" cellspacing="0" cellpadding="1">
           
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">Export&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>
                <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="export_billing.php">Billing
            </a></font> </td>
        </tr>
        
        <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="export_clients.php">Clients
            </a></font> </td>
        </tr>
		            </table>
        <?}?>
		
		     <?
        if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE privelages LIKE '%management,%' && id='$admin_id'"))){
        ?>
                <P><table width="143" border="0" cellspacing="0" cellpadding="1">
              
        <tr>
          <td bgcolor="<?echo $admin_color;?>" align="right"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="<?echo $admin_font_color_2;?>">Management&nbsp;</font><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
            </font></b></td>
        </tr>
                <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="admins.php">Admins
            </a></font> </td>
        </tr>
        
        <tr>
          <td align="left"><font color="<?echo $admin_font_color;?>" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a class="left_menu" href="statistics.php">Statistics
            </a></font> </td>
        </tr>
		            </table>
        <?}?>


    </td>          <td width="844" valign="top">&nbsp;
