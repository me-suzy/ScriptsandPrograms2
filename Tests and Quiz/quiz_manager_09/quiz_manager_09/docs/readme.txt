####################################
# QUIZ MANAGER 0.9
# 
# Aytekin Tank
# email: aytekin@interlogy.com
# http://www.interlogy.com/products/content/quiz/
# 
####################################
# COPYRIGHT NOTICE:
#
# Copyright 2001-2002 Aytekin Tank.
#
# This program may be used and modified free of charge by anyone, so
# long as this copyright notice and the header above remain intact.
# Selling the code for this program without prior written consent is
# expressly forbidden. Obtain permission before redistributing this
# program over the Internet or in any other medium.  In all cases
# copyright and header must remain intact.
#
# This program is distributed "as is" and without warranty of any
# kind, either express or implied. All responsibility is belong to 
# you if any damage or loss occurs.
####################################


----------------------------------------
Quiz Manager 0.9 Installation:
----------------------------------------

First create a quiz directory and upload all files into it. 
Then install the script as descibed below:

- Automatic Installation
1. Upload all you files in a quiz directory
2. Go to your browser and visit "install.php" and follow instructions
3. You should be all set! Login to "admin.php" and create your quizes.
4. Include the quiz form using a server side include or a PHP include as described below

- Manual Installation
1. Open "tables.txt" with a text editor and copy it to mysql to create tables.
2. Open "config.php" with a text editor and modify database variables
3. You should be all set! Login to "admin.php" and create your quizes.
4. Include the quiz form using a server side include or a PHP include as described below


----------------------------------------
Placing current quiz to one of your pages:
----------------------------------------

1. If it is a .shtml file, add it like this:
<!--#include virtual="quizdir/quiz_form.php"--> 

2. If it is a .php file, add it like this:
<? include "quiz_form.php" ?>

3. If it is on a different directory, change "quizdir" to the path:
<?
        $curdir = getcwd();
        chdir("quizdir");
        include "quiz_form.php" ;
        chdir($curdir);
?>

A quiz will be showed here only if you have selected a current quiz 
by going to the admin panel defining a quiz start and end date wchich includes today.



