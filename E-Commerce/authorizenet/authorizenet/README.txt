# Original copyright is missing - Please update if found
# This file was modified by Adam Luz adam@adamluz.com on Aug 23, 2005
# Script Obtained from http://adamluz.com/authnet/
# Version 2.0
# 

+ Requires Curl
- TESTED ON APACHE 1 and APACHE 2
# Customized scripts can be made for a small fee. E-Mail me!


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


############################# IMPORTAINT INFORMATION #################################

IN AUTHORIZE.php the following variables *MUST* be updated for this script to work
properly.

# Configuration 
# 
$x_Login=""; // Your authorize.net login 
$x_Password=""; // Your authorize.net password (if Password-Required Mode is enabled) 

####### SET THIS TO YOUR USERNAME AND PASSWORD YOU USE TO LOGIN TO AUTHNET #########
