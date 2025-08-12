# This script was written by George A. from Web4Future.com
# There are no copyrights in the sent emails.

Usage:

sendpage.php -	You really don't need to modify anything here, unless you want to translate or modify the sent e-mail. 
							At the bottom of the page, there are 2 lines like:
							mail( $friendemail2, 'Message from $name', "$friendname2,  \n\n".$text ."\n\nYour friend,\n $name", "From: $email");
							You can simply edit the text: "Message from" and "Your friend" to the equivalent frazes in your language.
					  
							In the same file, there is a line: "I found this great page and I believe you would be interested."
							You can change this to anything you want.
						  
page.php 		-	If you wish to link to your front page, instead of the current page, you will need to change this line: 
							'sendpage.php?'+document.location.href
							with 
							'sendpage.php?http://www.mysite.com'

Don't forget to add <script language="JavaScript" src="w4ftell.js"></script> to every page of your website.

:) 
That's all. Thank you for downloading.
