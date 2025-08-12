This is the CSV-Parse 2.04, the third major release of this data upload script
designed to save you hours of pain by adding/updated and deleting mass products from 
the fishcartSQL database.
All the variables in the standard product template are included in this script. 
To use this script, copy all files and includes folder with it's files up to the csvparse directory
 to under your fishcartsql maint directory. 
It is now all self contained within this one directory. Make sure you chmod 777
all files in the includes directory except csvlang.php and chmod 755 the includes directory or you will get errors 
when running the scripts.
The column section looks a bit cryptic at this stage, basically it's using the category numbers 
defined when you view the source code of the "add a new product" page in the maintenance section. 
For example, down the bottom of that page where it  offers drop down lists for what cat you wish 
to assign the product to, the script is parsing a variable.
ie:
Select up to three categories for this product:<br>

        <select name=pc00 size=1
         onFocus="currfield='newcat'">
        <option value="0">Select A Category
        <option value="1:1">Inkjet Consumables / Epson
        <option value="1:7">Inkjet Consumables / Hewlett Packard
        <option value="2:2">Laser Consumables / Epson
        <option value="2:6">Laser Consumables / Hewlett Packard
        <option value="3:3">Ribbon Consumables / Epson
        <option value="4:4">Paper Consumables / Epson
        <option value="4:8">Paper Consumables / Hewlett Packard
        <option value="6:9">Books / Microsoft Press
        </select><br>

In the fields in the CSV file, marked "cat selection 1" etc, put the value the goes with the 
category..if I want to add a product to say books/microsoft press, the value in cat selection 1 
would be 6:9.

Remove any column headers you have in your csv file (ie descriptions of the columns, so that the 
data is on the first line).
Load the scripts and choose whether you want to add/update or deleted products
Follow the menus
Sit back and relax!
A template for the CSV file is included although not applicable, USE the Field order list which is
displayed on the csvsetup.php, csvindex.php and csvsetup3.php pages after you select the fields 
you want to use in your CSV file.

Contains code from the excellent fishcartsql cart written by Michael Brennen. Kind regards to him 
for releasing the code under free source licence. 
This code is provided without warranty and does not assert to work for your given situation. 
 
Requires PHP version 3.08 or later

Notes:
PHP timeout still applies, most ISP's have it set for 30 secs...so will a quick bit if maths  
this script maybe able to handle a fair number of items prior to the time out coming into effect.


What's new:
1.2
Now supports updating products
Fixed 2 bugs pointed out by Benjamin Krajmalnik:
Insert/update was reversed in the if-else statement catching the $act
error in data formating prior to dropping into the database
1.3
made it easier to configure
fixed some minor bugs
1.4
Major rewrite of just about everything including a new interface to centralise the scripts.
upload direct from your hard drive now, no need to upload the csv file for processing
Centralised config for both csv-parse and delprod scripts
Logging to show you what you have already processed with date and file size shown
This script hasn't been tested on FishcartSQL 1.7, although it should work...
if anyone has any problems let me know.

2.04 (rewrite by Chris Carroll)
Major rewrite. Includes support for multi-language and multi-zone carts. Ability to select the 
fields you want to use in your CVS file. Integration of the product deletion into the csvparse.php script.
Fixed the nasty habit of added slashes to the droot variable and the custID variable 
when you ran the modify config on the csvsetup.php script.
Works with Fishcart version 2.04. All fields available on the product add page in the 
maint directory are supported by this csvparse script. 

	1. Product start and stop date fields are entered in the following format;
	year field (for 2002 enter 02)
	month field (for july enter 07)
	day field (for 1st of the month enter 01)
	
	2. Sale start and end date fields are entered in the following format;
	year field (for 2002 enter 02)
	month field (for july enter 07)
	day field (for 1st of the month enter 01)
	
	3. The "Use Inventory Quantity Field for product" and "Inventory Quantity" fields are used
	in conjunction with one another. So if you use this select both fields in the csvsetup.php script.

regards,

Simon Weller
simon@nzservers.com
NZServers

Chris Carroll
ctcarroll@mindspring.com
