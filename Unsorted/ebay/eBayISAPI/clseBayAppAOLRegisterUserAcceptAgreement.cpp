/*	$Id: clseBayAppAOLRegisterAcceptAgreement.cpp	*/
//
//	File:	clseBayAppAOLRegisterAcceptAgreement.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Registration Accept User Agreement
//
// Modifications:
//				- 06/08/99 Lou	- Created
//				- 07/07/99 nsacco - added siteId and coPartnerId to AOLRegister()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
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
" Retrieve confirmation email";

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
"<p><font size=\"4\"><strong>Retrieve your registration confirmation "
"email</strong></font></p>\n"
"<p><strong><font size=\"3\">The registration process is almost finished!"
"&nbsp; Just one more step to go and you're ready to play on eBay. "
"</font></strong></p>\n"
"<p>eBay just sent you a registration confirmation email message.&nbsp; "
"We do this to confirm the email address you entered.&nbsp; The message "
"contains a link you can click to complete your eBay registration.&nbsp; "
"The confirmation message normally takes just a few minutes to arrive.&nbsp;</p>\n"
"<p><font size=\"4\" color=\"#008000\"><strong>Go check your email now.&nbsp; "
"Your eBay confirmation message may have already arrived!</strong></font></p>\n"
"<p>If you do not receive your eBay registration confirmation email within the "
"next 24 hours, there was most likely an error in your email address.&nbsp; "
"Please send a message to <a href=\"mailto:register@ebay.com\">register@ebay.com</a> "
" and they will help you complete the registration process.</p><p>\n";


// nsacco 07/07/99 added siteId and coPartnerId
void clseBayApp::AOLRegisterUserAcceptAgreement(CEBayISAPIExtension *pServer,
							char * pUserId,
							char * pEmail,
							char * pName,
							char * pCompany,
							char * pAddress,
							char * pCity,
							char * pState,
							char * pZip,
							char * pCountry,
							int countryId,
							char * pDayPhone1,
							char * pDayPhone2,
							char * pDayPhone3,
							char * pDayPhone4,
							char * pNightPhone1,
							char * pNightPhone2,
							char * pNightPhone3,
							char * pNightPhone4,
							char * pFaxPhone1,
							char * pFaxPhone2,
							char * pFaxPhone3,
							char * pFaxPhone4,
							char * pGender,
							int referral,
						    char * pTradeshow_source1,
						    char * pTradeshow_source2,
						    char * pTradeshow_source3,
						    char * pFriend_email,
						    int purpose,
						    int interested_in,
						    int age,
						    int education,
						    int income,
						    int survey,
							LPTSTR pNewPass,
							int	nAccept,
							int nNotify,
							int nAgreementQ1,
							int nAgreementQ2,
							int nPartnerId,
							int siteId,
							int coPartnerId,
							int UsingSSL,
							int nVerify
							)
{
	bool bRet = false;

	// Setup
	SetUp();	

	//Check all flags to make sure they agree to all questions.
	if (!nAccept || !nAgreementQ1 || !nAgreementQ2)
	{
		// Whatever happens, we need a title and a standard header
		*mpStream	<<	"<HTML>"
					<<	"<HEAD>"
					<<	"<TITLE>"
					<<	mpMarketPlace->GetCurrentPartnerName()
					<<	" User Agreement"
					<<	"</TITLE>"
					<<	"</HEAD>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetHeader();
		else
			*mpStream <<	mpMarketPlace->GetSecureHeader();

		*mpStream  <<	"\n";

		// Redirect the user to the User Agreement FAQ.
		ProduceUserAgreementFAQ();

		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Whatever happens, we need a title and a standard header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	HeaderText
				<<	"</TITLE>"
				<<	"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";

	//Display the Registration Banner with image
	*mpStream	<<	RegistrationBannerPart1
				<<	"	<strong><img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	RegBannerImage
				<<	"\" width=\"60\" height=\"37\" align=\"absmiddle\">"
				<<	"</strong>\n"
				<<	RegistrationBannerPart2;

	//Do the registration
	// nsacco 07/07/99 added siteId and coPartnerId
	bRet = AOLRegister(pServer, pUserId, pEmail, pName, pCompany, pAddress, pCity,
							pState, pZip, pCountry, countryId, pDayPhone1,
							pDayPhone2, pDayPhone3, pDayPhone4, pNightPhone1,
							pNightPhone2, pNightPhone3, pNightPhone4, pFaxPhone1,
							pFaxPhone2, pFaxPhone3, pFaxPhone4, pGender, referral,
							pTradeshow_source1, pTradeshow_source2, 
							pTradeshow_source3, pFriend_email, purpose, 
							interested_in, age, education, income, survey, 
							pNewPass, nNotify, UsingSSL, nPartnerId, siteId, coPartnerId,
							mpStream);

	if (bRet)
	{
		//Display the body of the text
		*mpStream	<<	TitleText;
	}
	
	//Add the footer
	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	

	CleanUp();
	return;
}

