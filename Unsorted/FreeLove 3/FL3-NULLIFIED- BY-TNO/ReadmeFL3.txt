#######################################################################
#							              # 
#                      FreeLove   Readme	                      #
#		   	   Version 3                                  #
# 	                                                              #
#                    Created by Russian London Ltd                    # 
#					                              #
# Created on:  30/10/2001       Last Modified on:  30/10/2001         #
# We can be reached at:         office@russianlondon.com              #
# Scripts Found at:   http://www.russianlondon.com/scripts/freelove3  #
#######################################################################
#######################################################################
# COPYRIGHT NOTICE:						      #
# 								      #
# Copyright 1997-2001 Russian London Ltd   All Rights Reserved.       #
#								      #
# This program may be used with the valid licence only.               #
# No modification except those permitted by the program is allowed.   # 
# If you want to midify or upgrade the program please note that you do#
# so on your own risk.                                                #
# By using this program you agree to indemnify Russian London Ltd     #
# from any liability that might arise from it's use.                  #
#								      #
# Selling the code for this program, sharing it or other              #
# redistribution, violation or hacking  is expressly forbidden.       #
# Please read licence information.                                    #
#######################################################################
#                  	LICENSE INFORMATION 			      #
-----------------------------------------------------------------------
  This program is not a free software; you cannot redistribute it and/or     	
  modify it under the terms of the GNU General Public License as    	
  published by the Free Software Foundation; either version 2 of    	
  the License, or (at your option) any later version.               	
                                                                    	
  This program is distributed in the hope that it will be useful,   	
  but WITHOUT ANY WARRANTY; without even the implied warranty of    	
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     	
  GNU General Public License for more details.  
  Please do not take credit for the script. 


Attention: 

You have purchased a licence to use 1(one) copy of FreeLove script
on 1 (one) server (domain name).
You are not allowed to install and run more than 1 copy of this program 
at any time. You are not allowed to copy, distribute, share or let other 
users do the same and violate our terms and conditions.

You are responsible for the sole use of this license.
If you licensed copy has become a subject of illegal violation your legitimate 
license can be terminated and you program can stop function properly. If this 
happens we will not be responsible for any loss of profits or damages that you 
might have suffered as a result of this program malfunction or loss of information. 

The program has a potential facility to detect the running of illegal copies or
unlicensed versions. If this happens we are informing by e-mail the owner of the 
original license and the owner of the illegal licence regarding the same. 
We are giving 14 days notice to make a full payment for the unlicensed copy 
or terminate the use of illegal copy. Failure to do so will result in termination 
of the purchased license, which may result in loss of your data related to this 
script or its malfunction. We are asking you again to observe our terms and 
conditions.
If you are interested in purchasing multilicence or want to use more than 1 copy,
please contact us at office@russianlondon.com

Installation and use of this program constitutes your acceptance of	 
our terms and conditions and your liability not to violate them. 	

This perl script is a copyright of Russian London Ltd
Registered in England No. 3857589
http://www.russianlondon.com
office@russianlondon.com
		
#######################################################################
 	INSTALLATION 	

Please unzip the file fl3-your_registration_number.zip
All files should be in their designated folders.
You will get files in these folders: cgi-bin/ and www/
Please read readmeFL3.txt, adminFL3.txt and licence.txt carefully.
All files inside cgi-bin folder should be copied into your cgi-bin directory in ASSI mode.
Please do not change any folder names or hierarchy at the installation stage. You can alwasys 
do that afterwards.
All files inside of www directory should be copied into www (http) directory in
binary mode.
First of all you inside of your cgi-bin directory you should give the correct permissions to 
all your folders and cgi files (chmod +x) 755 for fl3.cgi. 777 for folders or
contact you hosting provider for correct permissions. Files like: fl3.cfg, rl-com3.lib
should also be chmod at 777 or 755. Please lower permission to fl3.cfg file after you 
complete all installations and settings.
Set a correct path to your perl interpreter in fl3.cgi file. 
#!/usr/bin/perl is a default
Please enter in your browser the following path:
http://www.yourdomain.com/cgi-bin/freelove3/fl3.cgi?admin
or other correct path to your fl3.cgi file with ?admin extention.
You will be prompted to enter your registration number and password.
Your registration number and password are send to you with your program
Please enter them in the designated filds.
Once your program is successfuly registered you will only have to enter your own password
to enter your Admin zone. Please change your initial password asap.

Once in the ADMIN ZONE, go to Directory Testing section. This will show you that everything
is ok, or it will give your prompts where to set right permissions or look for errors.

Then go to "Picture directory" settings.
Enter correct and full path to your picture directory "www-pic-dir" on your server.
If you see a RussianLondon.com button above it in the Example field then you have done it. ;-)
The program is succesfully installed.
If you cannot see the logo, then you will have to make some modifications to fl3.cgi file.
Instructions are below.
That is how the top of fl.cgi looks like 
$root_d      = substr($ENV{'SCRIPT_FILENAME'},0,rindex($ENV{'SCRIPT_FILENAME'},'/')); # program root dir
$home_dir    = $ENV{'DOCUMENT_ROOT'};     # html root dir

#****************************************************************************
#  $home_dir    = "/home/websites/www.russianlondon.com";    # server home dir
#  $root_d      = "$home_dir/cgi-bin/freelove3";             # program home dir
#****************************************************************************

Please uncomment(remove # simbols from 2 lines mentioned below)

#  $home_dir    = "/home/websites/www.russianlondon.com";    # server home dir
#  $root_d      = "$home_dir/cgi-bin/freelove3";             # program home dir

Then you have manualy input the correct system path of the server 
(Please ask you hosting provider for this information) and the location 
where you have installed FreeLove3 script in your cgi-bin directory.


ATTENTION! Please note that any other alteration to the code of a program
except the one described above are made solely on your own risk and removes
any obligations from Russian London Ltd to support the program in any way.

Please reade this file carefully before installing the script.

This information is not essential, as the administration and settings are now
available in online mode, but it is nice to know what some or the other files are
doing in this program.
    


		             
         FILE	      -      DESCRIPTION
      1. welcome-email.txt   This file is sent automatically by sendmail program when someone is just registered or when
			     someone forgot or lost their password or login. You can customize this file to meet your
                             requirements, there are no limitations on size or content of this file.
                             This option is available only if: 
			     1. Your hosting has a  sendmail (Please ask your hosting 
                             company about the path to sendmail program, usually it is /usr/lib/sendmail
			     2. Your visitor left a e-mail (correct e-mail) address during registration
The default message inside of this file is:
Your login $login
Your password $password

Welcome to FreeLove 3
http://www.RussianLondon.com/cgi-bin/freelove3/fl3.cgi

-------------------
         FOLDERS      -      DESCRIPTION
      1. form                Very important folder, containts all the question for the profiles
                             Here are the files that should be changed if you want to alter questions
                             They are all in text format and it is very easy to change them.
                             By default it contains the general questions that are relevant to the 
                             introduction, dating profiles. The number of questions is unlimited and you can 
                             always set your own questions.
                             Should look like: /cgi-bin/freelove3/data/eng/form
		            
         FILE	      -      DESCRIPTION
      1. 0100-name           Question about the name
         0102-sex            Question about the sex
         0105-target	     Question about the purpose of the contact
         0106-sex-o          Question about the sexual preferences
         0108-resume	     Question about the personal statement
         0110-country        Question about the country of residence
         0120-city           Question about the town of residence
         0130-age            Question about the age
         0140-bdate          Question about the birthday
         0145-star-sign      Question about the star sign
         0150-mstatus        Question about the marital status
         0155-email          Question about the email
         0156-icq            Question about the icq
         0157-homepage       Question about the homepage
         0160-ecolor         Question about the color of the eyes
         0170-hcolor         Question about the hair color
         0180-weight         Question about the weigth
         0190-height         Question about the height
         0200-smoker         Question about the smoking habit
         0210-flang          Question about the foreigh languages   

         FOLDERS      -      DESCRIPTION
      1. help                There are some files inside that provide general help and information.

         FILE	      -      DESCRIPTION
         adv.txt             Please change this file to the correct information about advertising at your
                             web site
         faq.txt             Please change this file to the correct information about frequently asked question
                             at your web site
         first               Inside of this file there are some text that appear on the first page. Change it.
         readme              Nothing special keeps information about the above mentioned fiels. No need to change.
         

If your cgi-bin directory doesn't allow to store pictures in it then you have to create
a directory outside of your cgi-bin like this:
In order not to mix your images directory with the photos that people will download to your
web site we recommend to open a freelove/ directory first and then to open there images/ directory

     http://www.yourdomain.com/fl3pic/img/
   
-------------------------------------------------------------------------
  Online administration comes in a separate file





