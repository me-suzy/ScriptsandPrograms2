#######################################################################
#							              # 
#                      FreeVF  Readme	                              #
#		   	Version 2.00                                  #
# 	                                                              #
#                    Created by Russian London Ltd                    # 
#					                              #
# Created on:  20/07/2001       Last Modified on:  01/10/2001         #
# We can be reached at:         office@russianlondon.com              #
# Scripts Found at:    http://www.russianlondon.com/scripts/freevf    #
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
# redistribution  is expressly forbidden. Please read licence         #
# information                                                         #
#######################################################################
# 			LICENSE INFORMATION		 	      #
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

You have purchased a licence to use 1(one) copy of FreeVF script
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
http://www.russianlondon.com.scripts
office@russianlondon.com
		
#######################################################################
 	INSTALLATION 	

Please unzip the file fvf-your_registration_number.zip
All files should be in their designated folders and they should be copied in
accordance with their hierarchy.
All files should be copied into your cgi-bin directory in ASSI mode.
If your cgi-bin doesn't allow images or html files inside, then all files 
inside of www-pic-dir should be copied outside of your cgi-bin directory (into 
www or html directory) in binary mode.
Inside of your cgi-bin directory you should give the correct permissions to 
all your folders and cgi files (chmod +x) 755 for freevf.cgi. 777 for all folders or
contact you hosting provider for correct permissions. All other files should be 755 
to start with. After the program works fine plese reduce the permisions for freevf.cfg file.
Set a correct path to your perl interpreter in freevf.cgi file. 
#!/usr/bin/perl is a default
Please enter in your browser the following path:
http://www.yourdomain.com/cgi-bin/freevf.cgi?admin
or other correct path to your freevf.cgi file with ?admin extention.
You will be prompted to enter your registration number and password.
Your registration number and password are send to you with your program
Please enter them in the designated filds.
Once in the ADMIN ZONE, go to "Picture directory" settings.
Enter correct and full path to your picture directory "www-pic-dir" on your server.
If you see a RussianLondon.com button above it in the Example field then you have done it. ;-)
The program is succesfully installed.
If you have any problems with installation please fill in this form and we will install 
the program for free on your server. (FTP access is required).
https://www.russianlondon.com/scripts/installation.htm
If you cannot see the logo, then you will have to make some modifications to freevf.cgi file.
Instructions are below.
That is how the top of fl.cgi looks like 
$root_d      = substr($ENV{'SCRIPT_FILENAME'},0,rindex($ENV{'SCRIPT_FILENAME'},'/')); # program root dir
$home_dir    = $ENV{'DOCUMENT_ROOT'};     # html root dir

#****************************************************************************
#  $home_dir    = "/home/websites/www.russianlondon.com";    # server home dir
#  $root_d      = "$home_dir/cgi-bin/freevf";                # program home dir
#****************************************************************************

Please uncomment(remove # simbols from 2 lines mentioned below)

#  $home_dir    = "/home/websites/www.russianlondon.com";    # server home dir
#  $root_d      = "$home_dir/cgi-bin/freevf";                # program home dir

Then you have manualy input the correct system path of the server 
(Please ask you hosting provider for this information) and the location 
where you have installed FreeVF script in your cgi-bin directory.


ATTENTION! Please note that any other alteration to the code of a program
except the one described above are made solely on your own risk and removes
any obligations from Russian London Ltd to support the program in any way.

Please reade this file carefully before installing the script.

This information is not essential, as the administration and settings are now
available in online mode, but it is nice to know what some or the other files are
doing in this program.
------------------------------------------------------------------------
1 If you have an opportunity we strongly reccomend to place install program data files
  in the directories not accessible from WWW
2 We also recomend to start program installation as below:
 a) "Directory testing"
 b)  Set picture directory
-------------------------------------------------------------------------
  Online administration comes in a separate file







