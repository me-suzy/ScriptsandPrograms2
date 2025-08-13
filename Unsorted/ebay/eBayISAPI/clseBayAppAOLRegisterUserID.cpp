/*	$Id: clseBayAppAOLRegisterUserID.cpp	*/
//
//	File:	clseBayAppAOLRegisterUserID.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Registration UserID
//
// Modifications:
//				- 06/06/99 Lou	- Created
//				- 07/07/99 nsacco - added siteId and coPartnerId
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

//Error color param
static const char *ErrorColor =
"color=\"#FF0000\"";

// Header text
static const char *HeaderText =
" at AOL Registration - Choose UserID / password";

// Title text
static const char *TitleText =
"<p><font size=\"4\"><strong>Choose a user ID and password you would like to use on "
"eBay &nbsp;</strong></font>\n"
"<p>Your user ID is the 'official eBay name' you will use on eBay.&nbsp; "
"It is also the name other eBay users will see when you buy or sell or chat "
"on eBay.&nbsp; </p></p>\n";


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

//Top of Table Part 1
static const char *TableTop1 =
"<table border=\"1\" width=\"595\" cellspacing=\"0\" cellpadding=\"4\">\n"
"<td width=\"585\" colspan=\"2\"><strong><font size=\"3\"><div align=\"center\">"
"<center><p></font><big>\n";

//Top of Table Part 2
static const char *TableTop2 =
"</big></strong></td></tr>\n";

//Table Title
static const char *TableTitle =
"Choose a UserID and password";

//This works for all the text in the left side of the table
static const char *TableLeftSide =
"<tr align=\"center\"><td width=\"164\" align=\"left\">"
"<strong><font size=\"3\" >";

//UserID Text
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString

/*
static const char *TableUserID =
"eBay <a href=\"http://pages.ebay.com/aw/userid.html\">UserID</a>:"
"</strong></font><font size=\"2\">"
"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.g., jsmith@aol.com</font></td>\n"
"<td width=\"411\" align=\"left\">";
*/

static const char *TableUserIDData =
"<input type=\"text\" name=\"userid\" size=\"33\" maxlength=\"63\" value=\"";

//Password Text
static const char *TablePassword1 =
">Choose a password:</strong></font><font color=\"#008000\">"
"&nbsp;</font><font size=\"2\">&nbsp; (pick one you will remember!)"
"</font></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TablePasswordData =
"<input type=\"password\" name=\"newpass\" size=\"33\" maxlength=\"63\" value=\"";

//Password2 Text
static const char *TablePassword2 =
">Retype your password:</strong></font></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TablePassword2Data =
"<input type=\"password\" name=\"newpass2\" size=\"33\" maxlength=\"63\" value=\"";

//Bottom text and confirm button
static const char *BottomButton =
"</table><p><p><p><tr><td width=\"100%\"><div align=\"center\"><center>"
"<input type=\"submit\" value=\" Continue \"></td></tr></form>\n";

// Email Register link
static const char *EmailRegisterText =
"<p><br><br><br>If you have any problems registering, send an email to "
"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n";

//UserID already taken error
static const char *ErrorTakenUserID =
"<p><font size=\"4\"><strong>The user ID you entered has already been taken "
"by another eBay member</strong></font></p>"
"<p>We're sorry!&nbsp; The user ID you wanted is already being used.&nbsp; "
"There are so many eBay members it's not always possible for everyone to get "
"their first choice of user ID.&nbsp; Please enter a different user ID below.&nbsp;<p>\n";

//Passwords different
static const char *ErrorPasswordsDifferent =
"<p><font size=\"4\"><strong>New passwords differ</strong></font>"
"<p>Sorry, the two passwords you entered are different. "
"Please try again. <p>\n";

//Passwords different
static const char *ErrorNoPasswords =
"<p><font size=\"4\"><strong>Passwords Missing</strong></font>"
"<p>Sorry, both passwords must be entered to confirm. "
"Please try again. <p>\n";


// nsacco 07/07/99 added siteId and coPartnerId
void clseBayApp::AOLRegisterUserID(CEBayISAPIExtension *pServer,
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
							LPTSTR pNewPass2,
							int nPartnerId,
							int siteId,
							int coPartnerId,
							int UsingSSL,
							int nVerify
							)
{
	ostrstream   *pTempStream = NULL;

	bool	bGood = false;


	// Setup
	SetUp();	

	//See if we are going to do the verify part now.
	if (nVerify)
	{
		//Init the stream pointer
		pTempStream	= new ostrstream();

		//Call the verify method
		bGood = AOLValidateUserID( pEmail, pUserId, pNewPass, pNewPass2, pTempStream);

		//See if there were any problems
		if (bGood)
		{
			//Clean up memory
			if (pTempStream)
				delete pTempStream;

			//Everything checked out ok... SwitchGears to the next Reg page.
			CleanUp();

			//Set the page of who we are going to call....like eBayISAPI does
			SetCurrentPage(PageRegisterPreview);

			// nsacco 07/07/99 added siteId and coPartnerId
			AOLRegisterUserAgreement(pServer,
								pUserId,
								pEmail,
								(char *) pName,
								(char *) pCompany,
								(char *) pAddress,
								(char *) pCity,
								(char *) pState,
								(char *) pZip,
								(char *) pCountry,
							   countryId,
							   (char *) pDayPhone1,
							   (char *) pDayPhone2,
							   (char *) pDayPhone3,
							   (char *) pDayPhone4,
							   (char *) pNightPhone1,
							   (char *) pNightPhone2,
							   (char *) pNightPhone3,
							   (char *) pNightPhone4,
							   (char *) pFaxPhone1,
							   (char *) pFaxPhone2,
							   (char *) pFaxPhone3,
							   (char *) pFaxPhone4,
							   (char *) pGender,
							   referral,
							   (char *) pTradeshow_source1,
							   (char *) pTradeshow_source2,
							   (char *) pTradeshow_source3,
							   (char *) pFriend_email,
							   purpose,
							   interested_in,
							   age,
							   education,
							   income,
							   survey,   
							   pNewPass,
							   nPartnerId,
							   siteId,
							   coPartnerId,
							   UsingSSL
							   );
			return;
		}
		else
		{
			//Error happened when we verified the data
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


	//1st time here, so just show the info to the user so they can confirm
	*mpStream << "<br><form method=\"POST\" action=\"";

	//Do we need a SSL
	if (UsingSSL == 0)
		*mpStream	<<  mpMarketPlace->GetCGIPath(PageAOLRegisterUserID);
	else
		*mpStream	<<	mpMarketPlace->GetSSLCGIPath(PageAOLRegisterUserID);


	//Setup the call
	// nsacco 07/07/99 added siteId and coPartnerId
	*mpStream	<<	"eBayISAPI.dll?\">\n"
			<<	"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AOLRegisterUserID\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
				<<	UsingSSL
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"email\" VALUE=\""
				<<	pEmail
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"name\" VALUE=\""
				<<	pName
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"company\" VALUE=\""
				<<	 pCompany
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"address\" VALUE=\""
				<<	pAddress
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"city\" VALUE=\""
				<<	pCity
				<<	 "\">\n<input TYPE=\"hidden\" NAME=\"state\" VALUE=\""
				<<	pState
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"zip\" VALUE=\""
				<<	pZip
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"country\" VALUE=\""
				<<	pCountry
				<<	 "\">\n<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
				<<	countryId
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone1\" VALUE=\""
				<<	pDayPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone2\" VALUE=\""
				<<	pDayPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone3\" VALUE=\""
				<<	pDayPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone4\" VALUE=\""
				<<	pDayPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone1\" VALUE=\""
				<<	pNightPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone2\" VALUE=\""
				<<	pNightPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone3\" VALUE=\""
				<<	pNightPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone4\" VALUE=\""
				<<	pNightPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone1\" VALUE=\""
				<<	pFaxPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone2\" VALUE=\""
				<<	pFaxPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone3\" VALUE=\""
				<<	pFaxPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone4\" VALUE=\""
				<<	pFaxPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"gender\" VALUE=\""
				<<	pGender
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q1\" VALUE=\""
				<<	referral
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q17\" VALUE=\""
				<<	pTradeshow_source1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q18\" VALUE=\""
				<<	pTradeshow_source2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q19\" VALUE=\""
				<<	pTradeshow_source3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q20\" VALUE=\""
				<<	pFriend_email
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q7\" VALUE=\""
				<<	purpose
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q14\" VALUE=\""
				<<	interested_in
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q3\" VALUE=\""
				<<	age
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q4\" VALUE=\""
				<<	education
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q5\" VALUE=\""
				<<	income
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q16\" VALUE=\""
				<<	survey
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"partnerId\" VALUE=\""
				<<	nPartnerId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"verify\" VALUE=\"1\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
				<<	siteId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
				<<	coPartnerId
				<<	"\">\n";

	//Display the Title
	if (nVerify && !bGood && pTempStream)
	{
		*mpStream	<<	pTempStream->str();
	}
	else
	{
		*mpStream	<<	TitleText;
	}

	//Start building the table top and title
	*mpStream	<<	TableTop1
				<<	TableTitle
				<<	TableTop2;

	//Add Email Block
	*mpStream	<<	TableLeftSide
	//			<<	TableUserID
	// kakiyama 07/09/99
				<< clsIntlResource::GetFResString(-1,
								"eBay <a href=\"%{1:GetHTMLPath}userid.html\">UserID</a>:"
								"</strong></font><font size=\"2\">"
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.g., jsmith@aol.com</font></td>\n"
								"<td width=\"411\" align=\"left\">",
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
								NULL)
				<<	TableUserIDData
				<<	pUserId
				<<	"\">"
				<<	"</td></tr>\n";

	//Add Name Block
	*mpStream	<<	TableLeftSide
				<<	TablePassword1
				<<	TablePasswordData
				<<	pNewPass
				<<	"\"></td></tr>\n";

	//Add Company Block
	*mpStream	<<	TableLeftSide
				<<	TablePassword2
				<<	TablePassword2Data
				<<	pNewPass2
				<<	"\"></td></tr>\n";

	//Add bottom note and confirm button
	*mpStream	<<	BottomButton;

	//Add link to register email
	*mpStream	<<	EmailRegisterText;

	//Add the footer
	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();

	if (pTempStream)
		delete pTempStream;

	CleanUp();
	return;
}


bool clseBayApp::AOLValidateUserID( LPTSTR pEmail, LPTSTR pUserId, LPTSTR pNewPass, 
									LPTSTR pNewPass2, ostream *pTheStream) const
{
	clsUser	*pTempUser;

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	if ( !FIELD_OMITTED(pUserId) )
	{
		// convert to lower case
		pUserId = clsUtilities::StringLower(pUserId);

		//clean up User Id, remove prefix and trailler spaces 
		pUserId = clsUtilities::CleanUpUserId(pUserId);

		//Let's see if the userid is already taken.
		pTempUser = mpUsers->GetUser(pUserId);

		if (pTempUser)
		{
			//in case a user registered before the change and confirmed after 
			//the change. they still can use the User Id chosen in old registration 
			if(strcmp(pEmail, pTempUser->GetEmail()))
			{
				//No UserId entered
				*pTheStream	<<	ErrorTakenUserID
							<<	ends;

				delete pTempUser;

				return false;
			}

			delete pTempUser;
		}
	}
	else
	{
		//No UserId entered
		*pTheStream	<<	TitleText
					<<	ends;
	
		return false;
	}


	// Check te new passwords
	if (FIELD_OMITTED(pNewPass) ||
		FIELD_OMITTED(pNewPass2))
	{
		//No UserId entered
		*pTheStream	<<	ErrorNoPasswords
					<<	ends;
	
		return false;
	}

	pNewPass = clsUtilities::StringLower(pNewPass);
	pNewPass2 = clsUtilities::StringLower(pNewPass2);

	if (strcmp(pNewPass, pNewPass2) != 0)
	{
		//No UserId entered
		*pTheStream	<<	ErrorPasswordsDifferent
					<<	ends;
	
		return false;
	}

	//We made it Ok
	return true;
}
