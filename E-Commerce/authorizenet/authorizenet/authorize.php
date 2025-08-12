# Original copyright is missing - Please update if found
# This file was modified by Adam Luz adam@adamluz.com on Aug 23, 2005
# Script Obtained from http://adamluz.com/authnet/
# Version 2.0
# 
# Please note this script was altered for my use. You can add things like MySQL and
# what not. This script is just to get you started so you can charge transactions.
# The variables listed below must be passed to this script through POST or GET but
# all are required unless otherwise notated.
# You may email me if you have any questions. When I found this script off a website
# it did not include original copyright information. For version 2.0, I retain modified 
# coding copyright. You are welcome to modify this script as you see fit. Please 
# leave my copyright intact. Not required but respected.

# Variables listed below to charge a card

# $test (TRUE|FALSE) Decides if transaction is test or charge. TRUE for test
# $bill_amount Passes the amount to be charged on the card
# $first_name First Name on Credit Card
# $last_name Last Name of Credit Card
# $address & $address2 (address2 not required) Address verification for AUTHORIZE.NET
# $city Customers billing city
# $state Customers billing state
# $zip Customers billing zip code
# $phone Customers phone number
# $id A custimised ID for a customer
# $month Month credit card expires
# $year Year credit card expires
# $cvv Last three digits on signature bar
# $description A description of the transaction



# Configuration 
# 
$x_Login=""; // Your authorize.net login 
$x_Password=""; // Your authorize.net password (if Password-Required Mode is enabled) 

$x_Delim_Data="TRUE"; // Delimited response from the gateway (or set in the Setting Menu) 
$x_Delim_Char=","; // Character that will be used to separate fields 
$x_Encap_Char=""; // Character that will be used to encapsulate fields 

$x_Type="AUTH_CAPTURE"; // Default transaction type 
$x_Test_Request="$test"; // Make this a test transaction 

# 
# Customer Information 
# 
$x_Method="CC"; 
$x_Amount="$bill_amount"; 
$x_Last_Name="$last_name"; 


$x_First_Name="$first_name"; 
$x_Address="$address $address2"; 
$x_City="$city"; 
$x_State="$state"; 
$x_Zip="$zip"; 
$x_Cust_ID="$phone"; 
$x_Invoice_Num="$id"; 
$x_Description="$description"; 
$x_Card_Num="$card_number"; 
$x_Exp_Date="$month$year"; 
$x_card_code="$cvv"; 



# 
# Build fields string to post 
# 
$fields="x_Version=3.1&x_Login=$x_Login&x_Delim_Data=$x_Delim_Data&x_Delim_Char=$x_Delim_Char&x_Enca 
p_Char=$x_Encap_Char"; 
$fields.="&x_Type=$x_Type&x_Test_Request=$x_Test_Request&x_Method=$x_Method&x_Amount=$x_Amount&x_Fir 
st_Name=$x_First_Name"; 
$fields.="&x_Last_Name=$x_Last_Name&x_Card_Num=$x_Card_Num&x_Exp_Date=$x_Exp_Date&x_Address=$x_Address&x_City=$x_City&x_State=$x_State&x_Zip=$x_Zip&x_Cust_ID=$x_Cust_ID&x_Invoice_Num=$x_Invoice_Num&x_Description=$x_Description&x_card_code=$x_card_code"; 
if($x_Password!=``) 
{ 
$fields.="&x_Password=$x_Password"; 
} 

# 
# Start CURL session 
# 
$ch=curl_init("https://secure.authorize.net/gateway/transact.dll"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // set the fields to post 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // make sure we get the response back 

$buffer = curl_exec($ch); // execute the post 

curl_close($ch); // close our session 

$details=explode($x_Delim_Char,$buffer); // create an array of the response values 

/******************************************************************************* 

From here you can read the response from $details and do whatever it is you do! 
Remember that the first position in the $details array is 0, not 1. In the 
Authorize.net documentation, they show the position in the response starting 
with 1, but programmers can`t count. Here is an example of what I am talking 
about: 
The Response Code is the 1st position in the response. That means that 
$details[0] is the response code. 

The Transaction ID is the 7th position in the response. That means 
that $details[6] is the Transaction ID 

Refer to the authorize.net AIM documentation for the list of Response codes! 

*******************************************************************************/ 

// Echo the results of the transaction to see results of approved or not


echo <<<END

<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="400" id="AutoNumber1">
  <tr>
    <td bgcolor="#FFFF99" bordercolor="#FF0000">Result: $details[3]<br>
Auth Code: $details[4] </td>
  </tr>
</table>
END;
?>