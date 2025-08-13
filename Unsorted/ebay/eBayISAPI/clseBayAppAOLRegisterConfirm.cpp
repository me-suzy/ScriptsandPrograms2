/*	$Id: clseBayAppAOLRegisterConfirm.cpp	*/
//
//	File:	clseBayAppAOLRegisterConfirm.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Registration Complete
//
// Modifications:
//				- 06/10/99 Lou	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//

#include "ebihdr.h"

//
// Support for our own personal crypt
#include "malloc.h"		// Crypt uses malloc
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Header text
static const char *HeaderText =
" Email confirmation";

// Registration Banner
static const char *RegistrationBannerPart1 =
"<table border=\"1\" width=\"669\" cellspacing=\"0\" bgcolor=\"#99CCCC\" cellpadding=\"2\">"
"<tr>"
"<td width=\"663\"><p align=\"center\"><strong><font size=\"5\">"
"eBay at AOL Registration</font>&nbsp;&nbsp;</strong>\n";

//Name of Image
static const char *RegBannerImage =
"flag-us.gif";

//Leave space for the image that will go along with the text
static const char *RegistrationBannerPart2 =
"</td></tr></table>\n";

// Title text
static const char *TitleText =
"<p><font size=\"4\"><strong>Please enter your UserID or E-mail address "
"and you're a registered eBay member!</strong></font>\n";

// Email Register link
static const char *EmailRegisterText =
"<p><br><br><br>If you have any problems registering, send an email to "
"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n";

void clseBayApp::AOLRegisterConfirm(CEBayISAPIExtension *pServer,
										int nConfirmation,
										LPTSTR pUserID,
										int nVerify)
{
	ostrstream	*pTempStream = NULL;
	bool		bRet = false;

	// Setup
	SetUp();	

	if (nVerify)
	{
		//Init the stream pointer
		pTempStream	= new ostrstream();

		//Call the test routine
		bRet = AOLVerifyConfirmation(pUserID, nConfirmation, pTempStream);

		if (bRet)
		{
			//All done, go to next page.
			CleanUp();

			//Set the page of who we are going to call....like eBayISAPI does
			SetCurrentPage(PageAOLRegisterComplete);

			AOLRegisterComplete(pServer, pUserID);

			return;
		}
	}

	// Whatever happens, we need a title and a standard header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	HeaderText
				<<	"</TITLE>"
				<<	"</HEAD>";

//	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
//	else
//		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";

	//Display the Registration Banner with image
	*mpStream	<<	RegistrationBannerPart1
				<<	"	<strong><img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	RegBannerImage
				<<	"\" width=\"60\" height=\"37\" align=\"absmiddle\">"
				<<	"</strong>\n"
				<<	RegistrationBannerPart2
				<<	TitleText;

	//Set up the 
	//1st time here, so just show the info to the user so they can confirm
	*mpStream << "<br><form method=\"POST\" action=\"";

	//Do we need a SSL
//	if (UsingSSL == 0)
		*mpStream	<<  mpMarketPlace->GetCGIPath(PageAOLRegisterConfirm);
//	else
//		*mpStream	<<	mpMarketPlace->GetSSLCGIPath(PageAOLRegisterConfirm);


	//Setup the ISAPI routine
	*mpStream	<<	"eBayISAPI.dll?\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AOLRegisterConfirm\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"verify\" VALUE=\"1\">\n";

	//Get userid or email
	*mpStream	<<	"<center><table width=\"585\">\n"
				<<	"<tr><td align=\"center\"><font size=\"4\">"
				<<	"<input name=\"userid\" maxlength=\"63\" size=\"33\"></font></td></tr>\n"
				<<	"<tr><td align=\"center\">"
				<<	"<font size=\"3\"><strong>"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	"</strong> or E-mail address</font></td></tr>\n"
				<<	"<p><tr><td align=\"center\"><font size=\"4\">"
				<<	"<input name=\"number\" maxlength=\"63\" size=\"33\" value=\""
				<<	nConfirmation
				<<	"\"></font></td></tr>\n"
				<<	"<tr><td align=\"center\"><font size=\"3\">"
				<<	"Your Confirmation Number</font></td></tr>\n"
				<<	"</table></center>\n";


	//Add Continue Button
	*mpStream	<<	"<p><br><center>"
				<<	"<input type=\"submit\" value=\"Continue\">"
				<<	"</center></p></p></p></form>\n";

	//Add link for register help
	*mpStream	<<	EmailRegisterText;


	//Add the footer
//	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
//	else
//		*mpStream <<	mpMarketPlace->GetSecureFooter();

	if (pTempStream)
		delete pTempStream;

	CleanUp();
	return;
}


bool clseBayApp::AOLVerifyConfirmation(LPTSTR pUserID, int nConfirmation, 
										ostream *pTheStream)
{
	bool	bValid = false;

	// Let's see if we can find the user
	mpUser	=	mpUsers->GetUser(pUserID);
	if (mpUser)
	{ 
		//LL: Get info so I can see it -- Remove later
		bool	bRet;
		int		nId;

		bRet = mpUser->IsConfirmed();
		bRet = mpUser->IsSuspended();
		bRet = mpUser->IsCCVerify();
		bRet = mpUser->IsUnconfirmed();
		nId = mpUser->GetId();




		//Make sure the user is still unconfirmed
		if (mpUser->IsConfirmed())
		{
			//No need to continue, let user know that they are done.
			return false;
		}

		//See if the id matches the confermation #
		if (mpUser->GetId() == nConfirmation)
		{

			bValid = true;
		}

		//Set the confirmation flag for the user 
		mpUser->SetConfirmed();
	
	}


	return bValid;
}
