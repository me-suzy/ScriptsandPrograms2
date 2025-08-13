/*	$Id: clseBayAppRegisterPreview.cpp,v 1.8.2.3.74.2 1999/08/05 18:59:03 nsacco Exp $	*/
//
//	File:	clseBayAppRegisterPre.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//		PreView Registration
//
// Modifications:
//				- 08/03/98 Vicki	- Created
//				- 07/02/99 nsacco	- added siteId and coPartnerId to Register functions
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserVerificationServices.h"
//
// Support for our own personal crypt
#include "malloc.h"		// Crypt uses malloc
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};


// Error Messages
static const char *ErrorMsgSuspended =
"<h2>Registration confirmed, but blocked</h2>"
"There is no need to register again, because your registration has "
"already been confirmed. However, your status has currently "
"been blocked due to the existence of an outstanding issue regarding "
"your account. Typically, this is because of a past due balance on "
"your account, or another issue that you should have already been "
"made aware of. "
"<br>"
"Please contact <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
"Customer Support</a> "
"if you have any questions about this problem. ";

static const char *ErrorMsgUnconfirmed =
"<h2>Unconfirmed Registration</h2>"
"If you are trying to register again because you still see "
"\'not a registered user\' next to your name, it is because you "
"have not confirmed your registration. You should have received "
"an e-mail message containing instructions on confirming your "
" registration. You\'ll need to follow those instructions before "
"your registration is enabled. This confirmation message will be "
"sent to you again now, so please wait for it to arrive and follow "
"the directions it contains."
"\n"
"Otherwise, you may have selected a User ID that is already in use by someone else. "
"In that case, please go back and try again.";


static const char *ErrorMsgIdInUse =
"<h2>Registration conflict!</h2>" 
"The User ID you have requested is already in use. Please select another User ID. "
"If you would like advice on selecting a User ID, " 
"please refer to our <a href=\"http://pages.ebay.com/help/basics/f-userid.html\">User ID FAQ.</a> ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknown =
"<h2>Unknown Registration Error</h2>"
"There has been an unknown error validating your registration. Please "
"report this error, along with all pertinent information (your selected "
"userid, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

static const char *ErrorMsgNoName =
"<h2>Error in Name</h2>" 
"Sorry, a full name is required. Please go back "
"and try again.";

static const char *ErrorMsgNameTooLong =
"<h2>Error in Name</h2>"
"Sorry, the name you entered was too long. Please go back "
"and try again.";

static const char *ErrorMsgCompanyTooLong = 
"<h2>Error in Company</h2>"
"Sorry, the company name you entered was too long. Please go back "
"and try again";

static const char *ErrorMsgZipTooLong = 
"<h2>Error in Zip Code</h2>"
"Sorry, the zip code you entered was too long. Please go back "
"and try again";

static const char *ErrorMsgNoZip =
"<h2>Error in Zip Code</h2>"
"Sorry, a Zip Code is required. Please go back "
"and try again.";

static const char  *ErrorMsgNoAddress = 
"<h2>Error in Address</h2>"
"Sorry, an address is required. Please go back "
"and try again.";

static const char *ErrorMsgAddressTooLong = 
"<h2>Error in Address</h2>"
"Sorry, the address you entered was too long. Please go back "
"and try again.";

static const char *ErrorMsgNoCity =
"<h2>Error in City</h2>"
"Sorry, a city is required. Please go back and "
"try again.";

static const char *ErrorMsgCityTooLong =
"<h2>Error in City</h2>"
"Sorry, the city name you entered was too long. Please go back "
"and try again.";

static const char *ErrorMsgNoUSState =
"<h2>Error in State</h2>"
"Sorry, in the United States you must "
"select a state from the drop down list. "
"Please return to previous page and select a state from "
"the drop down list of States and Provinces.";

static const char *ErrorMsgNoCanadaProvince =
"<h2>Error in State</h2>"
"Sorry, in the Canada you must "
"select a province from the drop down list. "
"Please return to previous page and select a province from "
"the drop down list of States and Provinces.";

static const char *ErrorMsgNoState =
"<h2>Error in State</h2>"
"Sorry, in countries other than the United States or "
"Canada, you must enter a state or province name and select "
"\'Choose a State here\' in the State drop down list. "
"Please go back and try again.";

static const char *ErrorMsgStateTooLong = 
"<h2>Error in State</h2>"
"Sorry, the State or Province name you entered was too "
"long. Please go back and try again.";

static const char *ErrorMsgBadZip =
"<h2>Error in Zip Code</h2>"
"Sorry, in the United States, a valid ZIP code "
"is required (5 to 9 numeric digits). Please go back "
"and try again.";

static const char *ErrorMsgBadZipNoDash =
"<h2>Error in Zip+4 Code</h2>"
"Sorry, in the United States, a valid Zip+4 zip code "
"must be of the format \'nnnnn-nnnn\'. Please go back "
"and try again.";

static const char *ErrorMsgBadZipBadDigit =
"<h2>Error in Zip Code</h2>"
"Sorry, in the United States, a valid zip code "
"must contain only digits, or, for Zip+4 zip codes, "
"digits and a hyphen (\'-\').";

static const char *ErrorMsgCountryTooLong = 
"<h2>Error in country</h2>"
"Sorry, the country name you entered was too long. "
"Please go back and try again.";

static const char *ErrorMsgNoPhone =
"<h2>Error in Phone</h2>"
"Sorry, you must supply a valid primary "
"phone number. Please go back and try again.";

static const char *ErrorMsgDayPhoneTooLong = 
"<h2>Error in primary phone</h2>"
"Sorry, the primary phone number you entered was too long. "
"Please go back and try again.";

static const char *ErrorMsgNightPhoneTooLong = 
"<h2>Error in primary phone</h2>"
"Sorry, the primary phone number you entered was too long. "
"Please go back and try again.";

static const char *ErrorMsgFaxPhoneTooLong = 
"<h2>Error in fax phone</h2>"
"Sorry, the fax phone number you entered was too long. "
"Please go back and try again.";


static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send your registration confirmation "
"notice via electronic mail. This is probably because your e-mail "
"address was invalid. Please go back and check it again. ";

static const char *ErrorMsgUserIdHaveAmpercent =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! The \"&\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"</UL><P>";

static const char *ErrorMsgUserIdHaveAtSign =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! The \"@\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"</UL><P>";


static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail address is omitted or invalid</h2>"
"Sorry, the e-mail address  is omitted or invalid. "
"Please remove any spaces "
"in your email address. "
"<p>AOL and WebTV Users:  Please remove any spaces from your username and add the domain suffix  "
"(<b>@aol.com</b> or <b>@webtv.net</b> to your username). "
"For example, if your username is <b>joecool</b>, your e-mail address would be <b>joecool@aol.com</b>. </p>";

static const char *ErrorMsgOmittedAtSignInFriendEmail = 
"<h2>The \"@\" sign is omitted in your friend's email address </h2>"
"If you are referred by a friend, please fill in his/her email "
"address. Please do not omit the \"@\" sign in the email address, i.e., ebayfriend@aol.com.";


void clseBayApp::RegisterPreview(CEBayISAPIExtension *pServer,
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
							int partnerId,
							int siteId,		// nsacco 07/02/99
							int coPartnerId,
							int UsingSSL
							)
{
	bool	error		= false;

	int		password;
	char	cPassword[5];
	char	cSalt[5];
	char	*pCryptedPassword;
	int		mailRc;
	int UVrating = 0;
	int UVdetail = 0;

//	time_t	nowTime;
 


	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Registration"
					"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";

	// Is the field specified ???
	// Remove the space in pEmail and convert it to lower case
	if( FIELD_OMITTED(pEmail) || !ValidateEmail(pEmail) )
	{
		*mpStream	<<	ErrorMsgOmittedEmail;

		if (UsingSSL)
			*mpStream << mpMarketPlace->GetSecureFooter();
		else
			*mpStream << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	clsUtilities::StringLower(pEmail);

	// pUserId is null so we will use the E-mail as the ID
	pUserId = pEmail;

	// Let's check if the E-mail address is not used 

	mpUser	=	mpUsers->GetUser(pEmail);
	if (mpUser)
	{
		if (mpUser->GetUserState() != UserGhost)
		{
			// E-mail already seems to exist. Let's
			// see if they're suspended, or they just
			// exist already
			if (mpUser->IsSuspended())
			{
			//	*mpStream	<< "<BR>\n"	<<	ErrorMsgSuspended;

			// kakiyama 07/07/99

				*mpStream   << "<BR>\n" << clsIntlResource::GetFResString(-1,
											"<h2>Registration confirmed, but blocked</h2>"
											"There is no need to register again, because your registration has "
											"already been confirmed. However, your status has currently "
											"been blocked due to the existence of an outstanding issue regarding "
											"your account. Typically, this is because of a past due balance on "
											"your account, or another issue that you should have already been "
											"made aware of. "
											"<br>"
											"Please contact <a href=\"%{1:SendQueryEmailShow}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
											"Customer Support</a> "
											"if you have any questions about this problem. ",
											clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
											NULL);

			}
			else if (mpUser->IsUnconfirmed() || mpUser->IsCCVerify())
			{
				srand( (unsigned)time( NULL ) );
				password		= ((int)rand());
				sprintf(cPassword, "%d", password);
				sprintf(cSalt, "%d", password);

				// And mail the notice. We do this here because if we
				// have a problem, the user will never be able to confirm.
				mailRc = MailUserRegistrationNotice(pUserId, pEmail, cPassword, mpUser->IsCCVerify());

				if (!mailRc)
				{
					*mpStream	<<	"<BR>\n"	<<	ErrorMsgMail;
				}
				else
				{
					// Let's encrypt it
					pCryptedPassword	= crypt(cPassword, cSalt);

					mpUser->SetPassword(pCryptedPassword);
					mpUser->SetSalt(cSalt);
					mpUser->UpdateUser();

					*mpStream	<<	"<BR>\n"	<<	ErrorMsgUnconfirmed;
				} 
			} 
			else if (mpUser->IsConfirmed())
			{
				*mpStream	<<	"<BR>\n"
				<<	"<h2>Registration already confirmed!</h2>" 
				"There is no need to  register again, because your registration has already "
				"been confirmed. "
				"<br> "
				"Click here to: "
				"<ul> <li> <a href=\""
			<<	mpMarketPlace->GetHTMLPath()
			<<	"services/buyandsell/reqpass.html\">Request a new password</a>"
				"<li> <a href=\""
				<< mpMarketPlace->GetCGIPath(PageChangeUserId)
				<< "eBayISAPI.dll?ChangeUserid\">Change your User ID</a>"
				"<li> <a href=\""
			<<	mpMarketPlace->GetHTMLPath()
			<<	"/services/myebay/change-registration.html\">"
				"Change your registration information</a>"
				"</ul>";

			}	
			else
			{
			//	*mpStream	<<	"<BR>\n"	<<	ErrorMsgUnknown;

			// kakiyama 07/07/99

				*mpStream   <<  "<BR>\n"    << clsIntlResource::GetFResString(-1,
											"<h2>Unknown Registration Error</h2>"
											"There has been an unknown error validating your registration. Please "
											"report this error, along with all pertinent information (your selected "
											"userid, name, address, etc.) to "
											"<a href=\"%{1:GetHTMLPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
											clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
											NULL);

			}

			if (UsingSSL)
				*mpStream << mpMarketPlace->GetSecureFooter();
			else
				*mpStream << mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}
	} 

	
	// Tell user we're validating the info
	// *mpStream <<	"<H3><font color=\"darkgreen\">Please wait as we validate the information you submitted</font></H3>" << flush;

	// Validate input for non-required info
	error =	ValidateNonRequiredRegistrationInfo(
									 pCompany,
									 pNightPhone1,
									 pNightPhone2,
									 pNightPhone3,
									 pNightPhone4,
									 pFaxPhone1,
									 pFaxPhone2,
									 pFaxPhone3,
									 pFaxPhone4,
									 pGender,
									 pFriend_email);
	
	
	// If we got an error, let's leave
	if (error)
	{
		*mpStream <<	"<br>";

		if (UsingSSL)
			*mpStream << mpMarketPlace->GetSecureFooter();
		else
			*mpStream << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's make sure the extension doesn't show up as "default".
	/*
	if (strcmp(pDayPhone4, "default") == 0)
		strcpy(pDayPhone4, "\0");

	if (strcmp(pNightPhone4, "default") == 0)
		strcpy(pNightPhone4, "\0");

	if (strcmp(pFaxPhone4, "default") == 0)
		strcpy(pFaxPhone4, "\0");
	*/

	// let users review their required info 
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, true, pEmail, pName, pAddress, pCity, pState, pZip, pCountry, countryId,
		pDayPhone1, pDayPhone2, pDayPhone3, pDayPhone4))
	{
		*mpStream <<	"<p>";

		if (UsingSSL)
			*mpStream << mpMarketPlace->GetSecureFooter();
		else
			*mpStream << mpMarketPlace->GetFooter();
		
		CleanUp();
		return;
	}

	// output hidden fields for Register
	*mpStream << "<br><form method=\"POST\" action=\"";

	if (UsingSSL == 0)
		*mpStream <<  mpMarketPlace->GetCGIPath(PageRegister);
	else
		*mpStream <<  mpMarketPlace->GetSSLCGIPath(PageRegister);

	*mpStream <<	"eBayISAPI.dll?\">\n"
				"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"Register\">\n"
				"\n<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
			 << UsingSSL
			 << "\">\n<input TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
			 << pUserId
			 << "\">\n<input TYPE=\"hidden\" NAME=\"email\" VALUE=\""
			 << pEmail
			 << "\">\n<input TYPE=\"hidden\" NAME=\"name\" VALUE=\""
			 << pName
			 << "\">\n<input TYPE=\"hidden\" NAME=\"company\" VALUE=\""
			 << pCompany
			 << "\">\n<input TYPE=\"hidden\" NAME=\"address\" VALUE=\""
			 << pAddress
			 << "\">\n<input TYPE=\"hidden\" NAME=\"city\" VALUE=\""
			 << pCity
			 << "\">\n<input TYPE=\"hidden\" NAME=\"state\" VALUE=\""
			 << pState
			 << "\">\n<input TYPE=\"hidden\" NAME=\"zip\" VALUE=\""
			 << pZip
			 << "\">\n<input TYPE=\"hidden\" NAME=\"country\" VALUE=\""
			 << pCountry
			 << "\">\n<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
			 << countryId
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone1\" VALUE=\""
			 << pDayPhone1
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone2\" VALUE=\""
			 << pDayPhone2
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone3\" VALUE=\""
			 << pDayPhone3
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone4\" VALUE=\""
			 << pDayPhone4
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone1\" VALUE=\""
			 << pNightPhone1
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone2\" VALUE=\""
			 << pNightPhone2
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone3\" VALUE=\""
			 << pNightPhone3
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone4\" VALUE=\""
			 << pNightPhone4
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone1\" VALUE=\""
			 << pFaxPhone1
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone2\" VALUE=\""
			 << pFaxPhone2
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone3\" VALUE=\""
			 << pFaxPhone3
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone4\" VALUE=\""
			 << pFaxPhone4
			 << "\">\n<input TYPE=\"hidden\" NAME=\"gender\" VALUE=\""
			 << pGender
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q1\" VALUE=\""
			 << referral
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q17\" VALUE=\""
			 << pTradeshow_source1
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q18\" VALUE=\""
			 << pTradeshow_source2
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q19\" VALUE=\""
			 << pTradeshow_source3
			 <<	"\">\n<input TYPE=\"hidden\" NAME=\"Q20\" VALUE=\""
			 <<	pFriend_email
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q7\" VALUE=\""
			 << purpose
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q14\" VALUE=\""
			 << interested_in
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q3\" VALUE=\""
			 << age
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q4\" VALUE=\""
			 << education
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q5\" VALUE=\""
			 << income
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q16\" VALUE=\""
			 << survey
			 << "\">\n"
			 // nsacco 07/07/99 added siteid and copartnerid
			 << "<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
			 << siteId
			 << "\">\n"
			 << "<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
			 << coPartnerId
			 << "\">\n"
			 <<	"<B>Click the Back button on your browser if you need to make any changes.</B> <p>"
				"<b> Click "
				"<input type=\"submit\" value=\"submit\"> "
				"<font color=\"#ff0000\"><b>(please click only once)</b></font> to complete Step 1 of the eBay registration process. </b>\n"
				"</form>\n";

	*mpStream <<	"<p>"
					"Please wait a few seconds for the next page to load. "
					"Clicking the submit button more than once may invalidate your confirmation information.<p>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	
	CleanUp();
	return;
}



