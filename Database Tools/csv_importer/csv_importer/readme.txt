#####################
# CSV Importer v2.0 #
#####################

------------------------------
Author: Matthew Lindley
E-mail: sir_tripod@hotmail.com
------------------------------


Introduction:
------------

Thank your downloading this FREE software.

As this software is FREE, I'd appreciate an e-mail to say you've downloaded it.  There's a send feedback
link at the bottom of each page.

This FREE piece of software is designed to get information from a CSV file into a MySQL
database.  Yes, there are ways around it and you CAN write things to import it via the command
line but not everyone has that ability.  Also, filtering and other database actions need to be
done from there, too.  With this you can do it all very easily and save your rule sets (see below.)

As this software is FREE, I'd appreciate an e-mail to say you've downloaded it.  There's a send feedback
link at the bottom of each page.

There is no technical support offered for this software.  Use it by all means but I'm far too busy
to be a helpline!  d:-)  There should be enough information here.

As this software is FREE, I'd appreciate an e-mail to say you've downloaded it.  There's a send feedback
link at the bottom of each page.

On a legal note, I cannot and will not be held responsible for the use or misuse of this software.
You should test it first before any mission-critical systems are altered.  By you loading the CSV
Importer software up, you are agreeing to all this.

As this software is FREE, I'd appreciate an e-mail to say you've downloaded it.  There's a send feedback
link at the bottom of each page.



Requirements:
------------
PHP 4.2 (Latest version advised, available from http://www.php.net)
MySQL 3.23 (Available from http://www.mysql.com)
IE5+ (IE6 recommended)



Using the CSV Importer:
----------------------
1) Browse to your CSV file.  I've created an empty folder for you to put your CSV files in.  You'd
be best using that.

2) Preview the file.  The "Use first row as header" option is for when, for example, you have columns
of data and the word "price" is at the top of the price column.  Selecting this will make things easier
later on.

If your file is delimited with a pipe "|" or some other character, you can put this in and then click
on "try again".

3) Connect to your MySQL database.  Enter your domain, MySQL username and password.  You can save these
settings if you wish by ticking the checkbox underneath.

4) Now you can select to import to an existing or new table.  The existing table option will allow you
select the database and then the table.  Next will take you on to the final stage.

The new table option will allow you to create the table just before you import it.  Select the containing
database, then enter the table name.  You have an option to use the column headers as the field names.
They will be converted to MySQL-compatible names.  You can now enter the field types and any extras.

5) This is the last stage and quite possibly the neatest!  d:-)

Match the database fields to your CSV file's columns.  The sequence option is there so field 1 will match
column 1, field 2 will match column 2 and so on.

The next step is the biggest new feature: import rule sets.  A rule set is a group of individual rules.
When importing a file you might want to delete all records in the table then import all of the CSV file.
Therefore you have two rules: delete all existing records, insert all csv columns.  These two rule as a
whole are a rule set.

Let's take that example and put it into practice.

a) Click "Add new rule".  Select "Delete".  Select "All records in [table_name]".
b) Click "Add new rule".  Select "Insert".  Select "All records in .CSV file".

You could now run this but you would have to create the rule set again in future.  What you need is a
way of creating these quickly.  Click the minus icon to the left of these two rules.  Now click on the
"Load rule set" combo-box and select "Delete all, add all".  Your rule set should now have been created
from the combo-box.

You might like to insert some records in the database if the price is over a certain amount.  So, change
the 2nd combo-box to "into [table_name] where".  Select your price column, then "is greater than" then
enter the price, say "10.99".

Click on "Save rule set" and enter how you'd like to refer to the rule set by name.  Your rule set will
be saved when you do the import.

The import first row option is self-explanitory.  If you have the named columns, you need this on "No".

Logging options allow you to see what has been going on while the CSV Importer has been importing.


As this software is FREE, I'd appreciate an e-mail to say you've downloaded it.  There's a send feedback
link at the bottom of each page.


Good luck and happy importing!



Matthew Lindley