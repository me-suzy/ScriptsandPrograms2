/*	$Id: clseBayAppPassRecognizer.cpp,v 1.9.204.2.90.2 1999/08/05 18:58:59 nsacco Exp $	*/
//
//	File:	clseBayAppPassRecognizer.cpp
//
//	Class:	clseBayApp
//
//	Author:	Craig Huang	(chuang@ebay.com)
//
//	Function:
//
//		Handle a batch item login
//
// Modifications:
//				- 04/28/98 Craig Huang - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"


// code is 0 default; 1 if admin request
// admin request bypass test of user having any past activities
void clseBayApp::PassRecognizer(CEBayISAPIExtension *pServer, char * userid,
								int code,
								char *pHostAddr)
{
	clsMail		*pMail;
	ostream	*pMailStream;
	int			mailRc;
	char		subject[256];	

	// Setup
	SetUp();
	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Requesting a new Password"
			  <<	"</TITLE>"
			  <<	"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Let's get the user
	mpUser = mpUsers->GetAndCheckUser(userid, mpStream);
	if (!mpUser)
	{
		CleanUp();
		return;
	}

	// if admin request, then code = 1 and go to generate email directly
	// otherwise defaults to 0.
	if( mpUser->UserHasActivities() && code == 0)
	{
		// Show the reqpass.html
		*mpStream <<	"<h2>Request a temporary password via e-mail</h2>"
						"To request a new password, please send an e-mail "
						"<strong>with the following information</strong> "
						"to <A HREF=\"mailto:password@ebay.com\">password@ebay.com</A>:"
						"<ul><li>Your <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/myinfo/userid.html\">User ID</a>"
						"<li>Your name"
						"<li>Your address (street, city, state and zip)"
						"</ul>"
						"<p>"
						"eBay will send instructions to replace your password via e-mail to "
						"your registered eBay e-mail address."
						"<p>"
						"<font color=red><strong>NOTE: We cannot process your request "
						"without your name and address.</strong></font> "
						"<p>"
						"Your current password will remain active until you respond to the email.";
						
	}
	else
	{
		// email to user about the password URL.
		// For email stuff
		pMail	= new clsMail;
		pMailStream	= pMail->OpenStream();
		// Prepare the stream
		pMailStream->setf(ios::fixed, ios::floatfield);
		pMailStream->setf(ios::showpoint, 1);
		pMailStream->precision(2);
		*pMailStream <<		"Forgot your password?\n\n"
							"If you did not forget your password, please ignore this email. \n\n"
							"To choose a new password, please go to the URL below:\n"
							"(please use it exactly as is including all trailing fullstops)\n"
					 <<		mpMarketPlace->GetCGIPath(PageChangeSecretPassword)
					 <<		"pass/"
					 <<		mpUser->GetPassword()
					 <<		"a\n\n" // Yes -- we want the 'a'. It makes it never end in punctuation.
					 <<		"This request was made from host: "
					 <<		pHostAddr
					 <<		".\n"
					 <<		"Thank you for using eBay!\n"
		//			 <<		"http://www.ebay.com";
		//	kakiyama 07/16/99
					 <<     mpMarketPlace->GetHTMLPath();

		sprintf(subject, "Change password.");

		mailRc =	pMail->Send(mpUser->GetEmail(), 
							(char *)mpMarketPlace->GetConfirmEmail(),
							subject);		
		// We don't need no mail now
		delete	pMail;

		if (!mailRc)
		{
			*mpStream <<	"<h2>Warning: Unable to send change password notice</h2>"
							"Sorry, we could not send the change password information to you "
							"via e-mail. Please check your e-mail to ensure it "
							"is valid and try again. You may wish to contact your service provider."
							"<br>";
		}
		else
		{
			*mpStream << "<br>"	
					  << "An e-mail has been sent to you with instructions on how to replace your forgotten password.<br>"	
					  << "\n";
		}


	}

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
}


void clseBayApp::ChangeSecretPassword(CEBayISAPIExtension *pServer, char * password)
{
	// Setup
	SetUp();
	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" ChangeSecretPassword"
			  <<	"</TITLE>"
			  <<	"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";


	*mpStream <<	"<h2>Change your password</h2>"
					"This form lets you select your own private password for use within "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	".<p>"
					"<form method=post action=\""
			  <<	mpMarketPlace->GetCGIPath(PageChangePasswordCrypted)
			  <<	"eBayISAPI.dll\">"
			  <<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ChangePasswordCrypted\">"
			  <<	"<input type=text name=userid size=30 maxlength = 60><br>"
					"<font size=\"2\"> <a href=\""
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</font></p>"
					"<p>"
					"<input type=hidden name=pass value=\""
			  <<	password
			  <<	"\" size=30 maxlength= 60>"
					"</p>"
					"<p>"
					"<input type=password name=newpass1 size=30 maxlength=60><br>"
					"<font size=\"2\">Your new password:</font></p>"
					"<p>"
					"<input type=password name=newpass2 size=30 maxlength=60><br>"       
					"<font size=\"2\">Type it again: </font></p>"           
					"<p>"
					"<input type=submit value=\"submit\">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input type=reset value=\"clear form\">"
					"</form>";
					

	*mpStream <<	"<p>"
					"If you have a problem with this form, please email your request "
					"to <A HREF=\"mailto:password@ebay.com\">password@ebay.com</A> with"
					" your <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/myinfo/userid.html\">User ID</a>,"
					" name, and"
					" your address (street, city, state and zip) "
					"in the mail body and Failed URL change as subject."
					"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
}
