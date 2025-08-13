<ul>
<?
$stop=NULL;
################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Invoice Template [CC Batch Billing Method]',
    'Hello %%FULLNAME%%,',
    'This is a notice that your invoice %%INVOICE_NUMBER%% has been generated for services by $company_name on %%DATE%%.\nTotal Due: %%INVOICE_AMOUNTDUE%%\nDue Date: %%INVOICE_DUEDATE%%\nYour payment type is: %%BILLINGMETHOD%%\nWe will process your credit card, %%INVOICE_CCTYPE%% shortly.',
    'You may login to view your invoice online here:\n%%USERLOGINURL%%\nThank you,',
    'Billing Dept.\n$company_url',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Invoice Template [CC Batch Billing Method] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Invoice Template [CC Batch Billing Method] <b>NOT OK</b></font>";
    }
}

################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Invoice Template [Check Billing Method]',
    'Hello %%FULLNAME%%,',
    'This is a notice that your invoice %%INVOICE_NUMBER%% has been generated for services by $company_name on %%DATE%%.\nTotal Due: %%INVOICE_AMOUNTDUE%%\nDue Date: %%INVOICE_DUEDATE%%\nYour payment method is: %%BILLINGMETHOD%%\nPlease send payment to:\n%%INVOICE_ADDRESS%%',
    'You may login to view your invoice online here:\n%%USERLOGINURL%%\nThank you,',
    'Billing Dept.\n$company_url\n',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Invoice Template [Check Billing Method] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Invoice Template [Check Billing Method] <b>NOT OK</b></font>";
    }
}

################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Payment Template [CC Approved]',
    'Hello %%FULLNAME%%,',
    '%%DATE%%\nYou invoice %%INVOICE_NUMBER%% in the amount of %%INVOICE_AMOUNTDUE%% has been processed and approved.\nTransaction ID: %%INVOICE_TRANSID%%',
    'Thank you,',
    'Billing Dept.\n$company_url',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Payment Template [CC Approved] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Payment Template [CC Approved] <b>NOT OK</b></font>";
    }
}
################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Payment Template [CC Declined]',
    'Hello %%FULLNAME%%,',
    '%%DATE%%\nYou invoice %%INVOICE_NUMBER%% in the amount of %%INVOICE_AMOUNTDUE%% has been processed and declined.\nYou may login to your account and pay online or we will reprocess your invoice in 5 days.\n%%USERLOGINURL%%\nTransaction ID: %%INVOICE_TRANSID%%',
    'Thank you,',
    'Billing Dept.\n$company_url',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Payment Template [CC Declined] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Payment Template [CC Declined] <b>NOT OK</b></font>";
    }
}

################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Payment Template [CC Error]',
    'Hello %%FULLNAME%%,',
    '%%DATE%%\nYou invoice %%INVOICE_NUMBER%% in the amount of %%INVOICE_AMOUNTDUE%% has been processed with erros.\nYou may login to your account and pay online or we will reprocess your invoice in 5 days.\n%%USERLOGINURL%%\nTransaction ID: %%INVOICE_TRANSID%%',
    'Thank you,',
    'Billing Dept.\n$company_url',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Payment Template [CC Error] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Payment Template [CC Error] <b>NOT OK</b></font>";
    }
}

################
if ($type!="upgrade")
{
    if (mysql_query("INSERT INTO email_config VALUES (
    NULL,
    'Payment Template [Manual Approved]',
    'Hello %%FULLNAME%%,',
    '%%DATE%%\nYou invoice %%INVOICE_NUMBER%% in the amount of %%INVOICE_AMOUNTDUE%% has been processed.\nTransaction ID: %%INVOICE_TRANSID%%\nYou may login to your account and view your invoice anytime.\n%%USERLOGINURL%%',
    'Thank you,',
    'Billing Dept.\n$company_url',
    '".mktime()."')"))
    {
      echo "<li>--> <font color=blue>Payment Template [Manual Approved] <b>OK</b></font>";
    }
    else
    { $stop=1;
      echo "<li> --> <font color=red>Payment Template [Manual Approved] <b>NOT OK</b></font>";
    }
}

################
if (mysql_query("INSERT INTO email_config VALUES (
NULL,
'Vortech Template [Signup Email]',
'This is not your account information email.  You will receive your account information including username, password, and IP address within 24 hours of the receipt of this email if you are paying by credit card. If you are paying by check or money order, please see below for further instructions.\n************************************************************\n\nDear Valued $company_name Customer:\n\nThank you very much for choosing $company_name as your Web and E-Commerce Hosting Provider. This is your order confirmation email for your Web hosting account with $company_name. Please note that this is not your account information. An email with your username, password, and IP address will be emailed to you later once your account is setup at $company_name.\n\n************************************************************\n',
'Hello [[FIRSTNAME]] [[LASTNAME]],\n\nHere is your order information:\n\nEmail: [[EMAIL]]\nCmpany: [[COMPANYNAME]]\nAddress:\n[[ADDRESS]]\n[[CITY]], [[STATE]] [[ZIPCODE]]\n[[COUNTRY]]\n\nPhone: [[PHONE]]\nFax: [[FAX]]\n\nReferrer: [[REFERRER]]\nDomain: [[DOMAIN]]\nUsername: [[USERNAME]]\nPassword: [[PASSWORD]]\n\nComments:\n[[COMMENTS]]\n\nOrder Summary: \n[[DISPLAYCART]]\n\n\nPayment Method: [[PAYMENTMETHOD]]\n\n_START_CCTEXT_\nCC Type: [[CCTYPE]]\nCC Number: [[CCNUMBER]]\nCC Exp: [[CCEXP]]\n_STOP_CCTEXT_\n_START_PAYPALTEXT_\nFollow this link to pay via PayPal:\n[[PAYPALLINK]]\n_STOP_PAYPALTEXT_\n_START_INVOICETEXT_\nSend Payment to:\n[[PAYADDRESS]]\n_STOP_INVOICETEXT_\n',
'Thank You for Signing Up!\n\n$company_name\n',
'Mini-FAQ for Your $company_name Account\n***********************************************************\nQ: I paid by credit card. When will my account info arrive?\nA: Within 24 hours, you will receive an email from our Order Processing Team that contains the login information for for your Site, your control panel for your $company_name account.\n***********************************************************\nQ: When will my transferred domain be available?\nA: Within 4 to 48 hours after you submit the order, to transfer your domain to $company_name. You will need to go to where you got your domain and change the DNS info to:\nNS1.MODERNSERVER.COM  66.70.153.32  NSO5190-HST  COHO-46911\nNS2.MODERNSERVER.COM  66.70.153.33  NSO5191-HST  COHO-46913\n***********************************************************\nQ: If I am paying by invoice how do I pay?\nA: If you choose the invoice option. Payment must be received within 10 days to keep account active. $company_name will accept either business, personal checks or money orders.\nSend mail to:\n[[PAYADDRESS]]\n***********************************************************\n',
'".mktime()."')"))
{
  echo "<li>--> <font color=blue>Vortech Template [Signup Email] <b>OK</b></font>";
}
else
{ $stop=1;
  echo "<li> --> <font color=red>Vortech Template [Signup Email] <b>NOT OK</b></font>";
}

################
if (mysql_query("INSERT INTO email_config VALUES (
NULL,
'Invoice Template [PayPal Billing Method]',
'Heading: Hello %%FULLNAME%%,',
'This is a notice that your invoice %%INVOICE_NUMBER%% has been generated for services by $c on %%DATE%%.\nTotal Due: %%INVOICE_AMOUNTDUE%%\nDue Date: %%INVOICE_DUEDATE%%\nYour payment type is: %%BILLINGMETHOD%%\nPlease click here to pay via PayPal:\n%%INVOICE_PAYPAL_LINK%%\n',
'Footer: You may login to view your invoice online here:\n%%USERLOGINURL%%\n',
'Thank you,\nBilling Dept.\n$company_url',
'".mktime()."')"))
{
  echo "<li>--> <font color=blue>Invoice Template [PayPal Billing Method] <b>OK</b></font>";
}
else
{ $stop=1;
  echo "<li> --> <font color=red>Invoice Template [PayPal Billing Method] <b>NOT OK</b></font>";
}

################
if (mysql_query("INSERT INTO email_config VALUES (
NULL,
'ModernBill Transfer Template',
'Hello %%FULLNAME%%,\n%%DATE%%\n',
'Your information on file is:\nEmail: %%EMAIL%%\nCompany: %%COMPANY%%\nAddress:\n%%FULLADDRESS%%\nPhone: %%PHONE1%%\nFax: %%PHONE2%%\n
Billing Metthod: %%BILLINGMETHOD%%\nbilling Type: %%BILLINGTYPE%%\nYou may login and update your information here:\n%%USERLOGINURL%%\nusername: %%EMAIL%%\npassword: %%LASTNAME%%',
'Thank you,\nBilling Dept.\n',
'%%INVOICE_ADDRESS%%',
'".mktime()."')"))
{
  echo "<li>--> <font color=blue>ModernBill Transfer Template <b>OK</b></font>";
}
else
{ $stop=1;
  echo "<li> --> <font color=red>ModernBill Transfer Template <b>NOT OK</b></font>";
}

################
if (mysql_query("INSERT INTO email_config VALUES (
NULL,
'Invoice Template [WorldPay Billing Method]',
'Hello %%FULLNAME%%,',
'This is a notice that your invoice %%INVOICE_NUMBER%% has been generated for services by $c on %%DATE%%.\nTotal Due: %%INVOICE_AMOUNTDUE%%\nDue Date: %%INVOICE_DUEDATE%%\nYour payment type is: %%BILLINGMETHOD%%\nPlease click here to pay via WorldPay:\n%%INVOICE_WORLDPAY_LINK%%\n',
'You may login to view your invoice online here:\n%%USERLOGINURL%%\n',
'Thank you,\nBilling Dept.\n$company_url',
'".mktime()."')"))
{
  echo "<li>--> <font color=blue>Invoice Template [WorldPay Billing Method] <b>OK</b></font>";
}
else
{ $stop=1;
  echo "<li> --> <font color=red>Invoice Template [WorldPay Billing Method] <b>NOT OK</b></font>";
}
################
if (mysql_query("INSERT INTO email_config VALUES (
NULL,
'Welcome Template [New Client]',
'Hello %%FULLNAME%%:\n\nDate: %%DATE%%\n\nYour new account has been created!\nhttp://%%AD_IP%%/ (%%AD_DOMAIN%%)\n',
'~~~~~~~~~\n
You may now transfer your domain name by pointing your nameservers to:\n
NameServers              IP Address    Nic Handle   CORE Handle\n
NS1.MODERNSERVER.COM  66.70.153.32  NSO5190-HST  COHO-46911\n
NS2.MODERNSERVER.COM  66.70.153.33  NSO5191-HST  COHO-46913\n
\n
This info is also available at:\n
http://www.modernserver.com/\n
\n
~~~~~~~~~\n
Your FTP and login information:\n
\n
SITE ADMINISTRATOR:\n
address: http://%%AD_IP%%/admin/\n
admin name: %%AD_USERNAME%%\n
admin pass: %%AD_PASSWORD%%\n
\n
FTP INFO:\n
ftp to: %%AD_IP%%\n
admin name: %%AD_USERNAME%%\n
admin pass: %%AD_PASSWORD%%\n
\n
TELNET INFO:\n
telnet to: ns1.modernhost.com\n
admin name: %%AD_USERNAME%%\n
admin pass: %%AD_PASSWORD%%\n
\n
PLEASE NOTE:\n
Access to User (POP ACCOUNT) Administrator: http://%%AD_IP%%/useradmin/\n
Each individual user within your site will have access to their own\n
administrator page. To login they will need to supply their full user name,\n
johndoe@yourdomain.com, and the password that was given to them.\n',
'~~~~~~~~~~\nPlease contact us if you have any questions about your account.',
'Thank you,\nBilling Dept.\n$company_url',
'".mktime()."')"))
{
  echo "<li>--> <font color=blue>Welcome Template [New Client] <b>OK</b></font>";
}
else
{ $stop=1;
  echo "<li> --> <font color=red>Welcome Template [New Client] <b>NOT OK</b></font>";
}
?>
</ul>
<center><b>
<? if ($stop) { ?>
<font color=red>Please correct the inserts that are "NOT OK".</font>
<? } else { ?>
<font color=blue>All email templated inserted successfully!</font>
<? } ?>
</b></center>
<br>