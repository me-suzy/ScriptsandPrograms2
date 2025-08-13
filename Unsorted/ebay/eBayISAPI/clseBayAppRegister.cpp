/*	$Id: clseBayAppRegister.cpp,v 1.13.2.4.14.2 1999/08/05 18:59:00 nsacco Exp $	*/
//
//	File:	clseBayAppRegister.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Handle a registration request
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 12/16/97 charles  - added the privacy user id controls
//				- 06/09/99 nsacco	- added Australia reg.
//				- 07/02/99 nsacco	- added a siteId and coPartnerId to the user
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//									- rewrote RegisterByCountry()
//
#include "ebihdr.h"
#include "clsUserVerificationServices.h"
#include "clsCountries.h"
#include "clsInternationalUtilities.h"

//
// Support for our own personal crypt
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};


// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
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
*/

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
"Please contact <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
"Customer Support</a> "
"if you have any questions about this problem. ";
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
"Canada, you must select "
"\'Choose a State here\' in the State drop down list and enter a "
"state or province name. You may leave state or privince as a blank if it's not applicable. "
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
"<p>AOL and WebTV Users:  Please remove any spaces from your username and add "
"the domain suffix  "
"(<b>@aol.com</b> or <b>@webtv.net</b> to your username). "
"For example, if your username is <b>joecool</b>, your e-mail address would be "
"<b>joecool@aol.com</b>. </p>";

static const char *ErrorMsgOmittedAtSignInFriendEmail = 
"<h2>The \"@\" sign is omitted in your friend's email address </h2>"
"If you are referred by a friend, please fill in his/her email "
"address. Please do not omit the \"@\" sign in the email address, i.e., ebayfriend@aol.com.";

bool clseBayApp::ValidateEmail(char* pUserId) const
{
	char* pBuff;

	// check wether it contains @ or a period
	if (strchr(pUserId, '@') == NULL || strchr(pUserId, '.') == NULL)
	{
		return false;
	}

	pBuff = new char[strlen(pUserId) + 1];
	strcpy(pBuff, pUserId);
	int i = 0;
	int j = 0;

	// Remove white spaces
	while(pBuff[i])
	{
		if (pBuff[i] != ' ' && pBuff[i] != '\t')
		{
			pUserId[j++] = pBuff[i];
		}
		i++;
	}
	pUserId[j] = '\0';

	// convert to lower case
	clsUtilities::StringLower(pUserId);


	// need for checking here

	delete	[] pBuff;
	return true;
}


// 
// MailUserRegistrationNotice
//
int clseBayApp::MailUserRegistrationNotice(char *pUserId,
										   char *pEmail,
										   char *pPassword,
										   bool  WithCC)
{
	clsMail			*pMail;
	ostream			*pMStream;
	char			 subject[256];
	int				 mailRc;
	clsAnnouncement	*pAnnouncement;
	char*			 pTemp;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Emit
	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}

	// emit Register announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(Registrn,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}
	
	*pMStream 
		<<	"\n\nDear "
		<<	pUserId
		<<	",\n\n";



	*pMStream	<<	"Welcome to eBay! We're so happy to have you as our "
					"newest member.\n"
					"\n"
					"You have now completed step 2 of the registration process. "
					"In order to begin buying or selling at eBay, you simply "
					"need to activate your account, which is step 3. Here's how...\n"
					"\n"
					"*Jot down the following info:\n"
					"\tYour email is "
				<<	pEmail
				<<	"\n"
					"\tYour confirmation code is "
				<<	pPassword
				<<	"\n"
				<<	"\n";

	*pMStream	<<	"*Return to the registration section by clicking on this link:\n"
					"\t";

	// Go to path/us/cc-reg-confirm.html for U.S., etc.
	if (strstr(pUserId, "@aol.com") == NULL)
	{
		OutputConfirmationPath(pMStream, WithCC);
	} 
	else
	{
		*pMStream	<<	"<A HREF=\"";
						OutputConfirmationPath(pMStream, WithCC);
		*pMStream	<<	"\">";
						OutputConfirmationPath(pMStream, WithCC);
		*pMStream	<<	"</A>";
	}

	*pMStream	<<	"\n"
					"Note: If this link doesn't work, copy and paste or type the "
					"link into your Web browser (make sure you don't add extra spaces). "
					"AOL members can also go to keyword \"eBay confirm\" on AOL.\n"
					"\n";

	*pMStream	<<	"*When you get there, just complete the form and review the "
					"eBay User Agreement. [We know it's long, but you know how "
					"lawyers are ;-) ]\n"
					"\n"
					"And that's it! It's great to have you on board. Be sure to "
					"visit our Help section if you have any questions for us--and "
					"have fun!\n";    

	if (WithCC)
	{
		*pMStream	<<	"You will be required to enter your VISA or MasterCard information.";

	}
	
	*pMStream	<<	"\n"
				<<	"\n";

	*pMStream   <<  "WebTV users -- please visit the link below for instructions "
		            "on accessing secure pages:"
					"\n        "
				<<  mpMarketPlace->GetHTMLPath()
				<<  "services/registration/secure-webtv-support.html \n\n";

	*pMStream	<<	flush;

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	}

	// emit Register announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(Registrn,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// Send
	sprintf(subject, "%s Registration",
			mpMarketPlace->GetCurrentPartnerName());

	mailRc =	pMail->Send(pEmail, 
							(char *)mpMarketPlace->GetRegistrationEmail(),
							subject,
							NULL,
							NULL,
							REG_POOL);

	// All done!
	delete	pMail;

	return mailRc;
}

void clseBayApp::OutputConfirmationPath(ostream	*pStream, bool WithCC)
{
	clsCountries    *pCountries = mpMarketPlace->GetCountries();

	if (WithCC) // Register on the secure server
	{
		if (pCountries)
		{
			switch (pCountries->GetCurrentCountry())
			{
				// TODO - fix this so we don't need to use CountryCode()
				case Country_US:
				case Country_CA:
				case Country_UK:
				case Country_AU:		// nsacco 06/09/99
				case Country_DE:		// PH 05/04/99
					*pStream << mpMarketPlace->GetSecureHTMLPath()
						     << pCountries->GetCurrentCountryCode()
							 << "-cc-reg-confirm.html ";
					break;
				default:
					*pStream <<	mpMarketPlace->GetHTMLPath()
				 			 <<	"services/registration/cc-confirm-by-country.html ";
			}

		}
		else // Should never happen
		{
			*pStream	<<	mpMarketPlace->GetHTMLPath()
						<<	"services/registration/cc-confirm-by-country.html ";
		}
	}
	else  // Normal registration, don't confirm on the secure server.
	{

		if (pCountries)
		{
			switch (pCountries->GetCurrentCountry())
			{
				// TODO - fix to not use CountryDir()
				case Country_US:
				case Country_CA:
				case Country_UK:
				case Country_AU:			// nsacco 06/09/99
				case Country_DE:			// PH 05/04/99 added
					*pStream <<	mpMarketPlace->GetHTMLPath()
						     << pCountries->GetCurrentCountryDir()
		 					 <<	"services/registration/reg-confirm.html ";
					break;
				default:
					*pStream <<	mpMarketPlace->GetHTMLPath()
				 			 <<	"services/registration/confirm-by-country.html ";
			}
		}
		else // Should never happen.
		{
			*pStream <<	mpMarketPlace->GetHTMLPath()
		 			 <<	"services/registration/confirm-by-country.html ";
		}
	}
}

void clseBayApp::Register(CEBayISAPIExtension *pServer,
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
					  bool WithCC,
					  int partnerId,
					  int siteId,	// nsacco 07/02/99
					  int coPartnerId,
					  int UsingSSL)
{
	bool	error		= false;

	int		password;
	char	cPassword[5];
	char	cSalt[5];
	char	*pCryptedPassword;
	char	*pHost;
	int		mailRc;

	time_t	nowTime;

	char*	pTempState = pState;

	bool	IsGhost = false;
 
	clsUser	*pUser;
 
	clsInternationalUtilities objIntlUtils;
	char *pFormattedDayPhone;
	char *pFormattedNightPhone;
	char *pFormattedFaxPhone;

	int UVrating;
	int UVdetail;

	clsCountries *pCountries = NULL;

	// Setup
	SetUp();  

	// Set the current country so that we can reference the correct
	// country-specific pages relating to registration.
	pCountries = mpMarketPlace->GetCountries();
	if (pCountries) 
		pCountries->SetCurrentCountry(countryId);

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
		*mpStream	<<	ErrorMsgOmittedEmail
					<<	"<BR><BR>";

		if (UsingSSL == 0)
			*mpStream	<<	mpMarketPlace->GetFooter();
		else
			*mpStream	<<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	clsUtilities::StringLower(pEmail);
	
	//UserId email should be same unless user modified in source
	if (FIELD_OMITTED(pUserId) || strcmp(pUserId, pEmail))
	{
	//	*mpStream	<<	"<BR>\n"	<< ErrorMsgUnknown;

	// kakiyama 07/07/99


		*mpStream   << "<BR>\n";
		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>Unknown Registration Error</h2>"
							"There has been an unknown error validating your registration. Please "
							"report this error, along with all pertinent information (your selected "
							"userid, name, address, etc.) to "
							"Please contact <a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
							"Customer Support</a> "
							"if you have any questions about this problem. ",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL);

		if (UsingSSL == 0)
			*mpStream	<<	mpMarketPlace->GetFooter();
		else
			*mpStream	<<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}


	// Let's check if the E-mail address is not used
	mpUser	=	mpUsers->GetUser(pEmail);
	if (mpUser)
	{ 
		mpUser->SetCountryId(countryId);

		IsGhost = mpUser->GetUserState() == UserGhost;
		if (!IsGhost)
		{
			if (strcmp(mpUser->GetUserId(), pUserId) != 0)
			{
				// if the user ids are different
				*mpStream	<<	"<BR>\n"	
					<< "<h2>Registration conflict!</h2>" 
					"We already have confirmation of your e-mail address in our registration records. "
					"<p>"
					"Click here to: "
					"<br>"
					"<ul> <li> <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/reqpass.html\">Request a new password</a>"
					"<li> <a href=\""
					<< mpMarketPlace->GetCGIPath(PageChangeUserId)
					<< "eBayISAPI.dll?ChangeUserid\">Change your User ID</a>"
					"<li> <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"/services/myebay/change-registration.html\">Change your registration information</a>"
					"</ul>";
			}
			else
			{
				// E-mail already seems to exist. Let's
				// see if they're suspended, or they just
				// exist already
				if (mpUser->IsSuspended())
				{
				//	*mpStream	<< "<BR>\n"	<<	ErrorMsgSuspended;

				// kakiyama 07/07/99

					*mpStream   << "<BR>\n";
					*mpStream   << clsIntlResource::GetFResString(-1,
										"<h2>Registration confirmed, but blocked</h2>"
										"There is no need to register again, because your registration has "
										"already been confirmed. However, your status has currently "
										"been blocked due to the existence of an outstanding issue regarding "
										"your account. Typically, this is because of a past due balance on "
										"your account, or another issue that you should have already been "
										"made aware of. "
										"<br>"
										"Please contact <a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
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
					// have a problem, the user will never be able to 
					// confirm.
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
				<<	"/services/myebay/change-registration.html\">Change your registration information</a>"
					"</ul>";

				}
				else
				{
				//	*mpStream	<<	"<BR>\n"	<<	ErrorMsgUnknown;

				// kakiyama 07/07/99

					*mpStream   << "<BR>\n";
					*mpStream   << clsIntlResource::GetFResString(-1,
										"<h2>Unknown Registration Error</h2>"
										"There has been an unknown error validating your registration. Please "
										"report this error, along with all pertinent information (your selected "
										"userid, name, address, etc.) to "
										"Please contact <a href=\"%{1:GetHTMLPath}/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
										"Customer Support</a> "
										"if you have any questions about this problem. ",
										clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
										NULL);

				}
			}

			*mpStream <<	"\n";
			if (UsingSSL == 0)
				*mpStream	<<	mpMarketPlace->GetFooter();
			else
				*mpStream	<<	mpMarketPlace->GetSecureFooter();

			CleanUp();
			return;
		}
	}


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
		if (UsingSSL == 0)
			*mpStream	<<	mpMarketPlace->GetFooter();
		else
			*mpStream	<<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// Check required info again in case someone tried to hack the preview page
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, false, pEmail, pName, pAddress, pCity, pState, pZip, pCountry, countryId,
		pDayPhone1, pDayPhone2, pDayPhone3, pDayPhone4))
	{
		*mpStream <<	"<p>Sorry, invalid data<br>";
		if (UsingSSL == 0)
			*mpStream	<<	mpMarketPlace->GetFooter();
		else
			*mpStream	<<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// Check whether the user is registering with anonymous email
	if (!WithCC && IsAnonymousEmail(pEmail))
	{
		// YES. Special registration procedure
		RegisterWithAnonymousEmail(pUserId,
							pEmail,
							pName,
							pCompany,
							pAddress,
							pCity,
							pState,
							pZip,
							pCountry,
							countryId,
							pDayPhone1,
							pDayPhone2,
							pDayPhone3,
							pDayPhone4,
							pNightPhone1,
							pNightPhone2,
							pNightPhone3,
							pNightPhone4,
							pFaxPhone1,
							pFaxPhone2,
							pFaxPhone3,
							pFaxPhone4,
							pGender,			
							referral,
							pTradeshow_source1,
							pTradeshow_source2,
							pTradeshow_source3,
							pFriend_email,
							purpose,
							interested_in,
							age,
							education,
							income,
							survey,							
							partnerId,
							siteId,		// nsacco 07/02/99
							coPartnerId,
							UsingSSL);

		*mpStream <<	"<br>";
		if (UsingSSL == 0)
			*mpStream	<<	mpMarketPlace->GetFooter();
		else
			*mpStream	<<	mpMarketPlace->GetSecureFooter();

		CleanUp();

		return;
	}

	// Now, for a password. 
	// 
	// *** IMPORTANT ***
	// Since we might have to re-mail the user their 
	// password, we need to have a way to extract the
	// "first" password in cleartext. Soooo, the first
	// password is the same as the salt. Isn't that
	// just oogy?
	// *** IMPORTANT ***
	//
	//for AnonymousEmail, we need send email to let them confirm with CC
	if (!IsGhost ||(IsGhost && IsAnonymousEmail(pEmail)))
	{
		srand( (unsigned)time( NULL ) );
		password		= ((int)rand());
		sprintf(cPassword, "%d", password);
		sprintf(cSalt, "%d", password);

		// And mail the notice. We do this here because if we
		// have a problem, the user will never be able to 
		// confirm.
		mailRc = MailUserRegistrationNotice(pUserId, pEmail, cPassword, WithCC);

		if (!mailRc) 
		{
			*mpStream <<	ErrorMsgMail
					  <<	"<br>";
			if (UsingSSL == 0)
				*mpStream	<<	mpMarketPlace->GetFooter();
			else
				*mpStream	<<	mpMarketPlace->GetSecureFooter();

			CleanUp();

			return;
		}
		// Let's encrypt it
		pCryptedPassword	= crypt(cPassword, cSalt);
	}
	

	
	// Some phoney modifications
	if (FIELD_OMITTED(pNightPhone1))
		pNightPhone1	= NULL;

	if (FIELD_OMITTED(pNightPhone2))
		pNightPhone2	= NULL;

	if (FIELD_OMITTED(pNightPhone3))
		pNightPhone3	= NULL;

	if (FIELD_OMITTED(pNightPhone4))
		pNightPhone4	= NULL;
	
	if (FIELD_OMITTED(pFaxPhone1))
		pFaxPhone1	= NULL;

	if (FIELD_OMITTED(pFaxPhone2))
		pFaxPhone2	= NULL;

	if (FIELD_OMITTED(pFaxPhone3))
		pFaxPhone3	= NULL;

	if (FIELD_OMITTED(pFaxPhone4))
		pFaxPhone4	= NULL;

	if (FIELD_OMITTED(pCompany))
		pCompany	= NULL;

	// make sure gender is ok.
	// if IIS somehow gives us "default", then set it to 'u'
	// or else we get an ORA error
	if (FIELD_OMITTED(pGender) && (pGender))
	{
		pGender[0]='u';
		pGender[1]='\0';
	}


	// Let's make a user!
	// Get the partners if we don't have them.
	if (mpvPartners == NULL)
	{
		mpvPartners = new vector<const char *>;
		gApp->GetDatabase()->GetPartnerIds(mpvPartners);
	}
	// Unknown partner, set to eBay.
//	if (partnerId >= mpvPartners->size())
//		partnerId = 0;


	pHost	= gApp->GetEnvironment()->GetRemoteAddr();
	
	nowTime	= time(0);

	if (pCountries) 
// PH		objIntlUtils.SetCurrentCountry(pCountries->GetCountryId(pCountry));
		objIntlUtils.SetCurrentCountry (countryId);		// PH 05/04/99 

	// Don't forget to free what FormatPhone returns.
	pFormattedDayPhone = objIntlUtils.FormatPhone(pDayPhone1, pDayPhone2, pDayPhone3, pDayPhone4);
	pFormattedNightPhone = objIntlUtils.FormatPhone(pNightPhone1, pNightPhone2, pNightPhone3, pNightPhone4);
	pFormattedFaxPhone = objIntlUtils.FormatPhone(pFaxPhone1, pFaxPhone2, pFaxPhone3, pFaxPhone4);

	//*** For Ghost User only***
	//This is unfortunate. For now, we need to set the creation
	// time for the account to now. We should find a way to batch
	// update this later.
	// nsacco 07/07/99 added siteid and co_partnerid
	pUser	= new clsUser(	mpMarketPlace->GetId(),
							IsGhost ? mpUser->GetId() : 0,
							pUserId,
							(WithCC ? UserCCVerify : UserUnconfirmed),
							(IsGhost && !IsAnonymousEmail(pEmail))? mpUser->GetPassword() : pCryptedPassword,
							(IsGhost && !IsAnonymousEmail(pEmail))? mpUser->GetSalt() : cSalt ,
							nowTime,
							nowTime,
							0,
							countryId,
							UVrating,							// UVRating
							UVdetail,							// UVDetail
							pHost,
							pName,
							pCompany,
							pAddress,
							pCity,
							pTempState,
							pZip,
							pCountry,
							pFormattedDayPhone,
							pFormattedNightPhone,
							pFormattedFaxPhone,
							nowTime,
							pEmail,
							0,
							false,
							false,
							pGender,
							0, /* interest_1, now unsed */
							0, /* interest_2, now unsed */
							0, /* interest_3, now unsed */
							0, /* interest_4, now unsed */
							partnerId,
							siteId,
							coPartnerId
						);

	// Free the memory allocated in FormatPhone.
	delete [] pFormattedDayPhone;
	delete [] pFormattedNightPhone;
	delete [] pFormattedFaxPhone;

	if (IsGhost)
	{
		//only allow non AnonysEmail user to confirm immediatly
		if(!IsAnonymousEmail(pEmail))
			pUser->SetConfirmed();
		else
			pUser->SetCCVerify();


		// Now, we can't do an "updateuser" because, down deep inside, 
		// it assumes that the ebay_user_info record exists. So, we 
		// do the nasty and call the db directly.
		gApp->GetDatabase()->AddUserInfo(pUser);

		// Now, update them
		pUser->UpdateUser();
	}
	else
	{
		// Now, let's add the user
		mpUsers->AddUser(pUser);
	}


	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  1,
									  referral); 		
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  3,
									  age);
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  4,
									  education);
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  5,
									  income);
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  7,
									  purpose);
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  14,
									  interested_in);

	// For historical purposes: Let it be known that I have removed
	// the last vestages of "abducted by alien," "experiments performed,"
	// and "alien technology used." 
	mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  16,
									  survey);

	//tradeshow source  changed to fill in one column
	if (!FIELD_OMITTED( pTradeshow_source1))
	{
		//format tradeshow code as xx-xx-xxxx
		strcat(pTradeshow_source1, "-");
		strcat(pTradeshow_source1, pTradeshow_source2);
		strcat(pTradeshow_source1, "-");
		strcat(pTradeshow_source1, pTradeshow_source3);
		mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  17,
									  pTradeshow_source1);
	}


	//refer by a friend
	if (!FIELD_OMITTED( pFriend_email))
		mpDatabase->SetUserAttributeValue(pUser->GetId(),
									  20,
									  pFriend_email);

	// Here is the information I got rid of because it was no longer
	// being collected in the registration form. However, it is still
	// in our database. (I don't know what the values are, since they
	// were erased with the registration form, long before I started
	// touching this code.)
	// 2:  preferred_activity
	// 6:  modem speed
	// 7:  purpose
	// 8:  access_from
	// 9:  abducted_by_alien
	// 10: experiments_performed
	// 11: alient_technology_used
	// 12: people_on_grassy_knoll
	// 13: elvis_sighting
	// 15: interest2

	if (IsGhost && !IsAnonymousEmail(pEmail))
	{
	// Now, we can finally tell the user what happened
		*mpStream <<	"<h2>You have updated your eBay Registration information!</h2>"
					"Welcome back to eBay! "
					"<p>"
					"You're registered e-mail is now enabled on the eBay "
					"system and you're free to explore and use it just as you "
					"would the original eBay AuctionWeb."
					"<p>"
				<<	"If you have forgotten your password, "
					"please <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/reqpass.html\">click here</a>. <br>";
	}
	else
	{
		ConfirmInstruction(WithCC);
		free	(pCryptedPassword);
	}

	delete	pUser;

	if (UsingSSL == 0)
		*mpStream	<<	mpMarketPlace->GetFooter();
	else
		*mpStream	<<	mpMarketPlace->GetSecureFooter();

	CleanUp();
	return;
}

// the final words for completing the registration
void clseBayApp::ConfirmInstruction(bool WithCC/*=false*/)
{

	clsCountries *pCountries = mpMarketPlace->GetCountries();
		// Current country was set in the caller.

	// Now, we can finally tell the user what happened
	*mpStream <<	"<h2>Registration - Step 1 Complete!</h2>"
					"\n"
					"You have successfully completed "
			  <<	"step 1 of the eBay registration process.<p>"
			  <<	"<b>Important!</b> "
			  <<	"You cannot begin buying or selling on eBay until "
			  <<	"the registration process is complete. Please complete steps 2 and 3 below. "
			  <<	"\n"
			  <<	"<p><b>Step 2 - Receive Confirmation Instructions </b><br> "
			  <<	"eBay will send you an e-mail message (within 24 hours) with a confirmation code. "
			  <<	"Please go to <a href=\"" 
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/registration/reqtemppass.html\">"
			  <<	"resend Confirmation Instructions</a> page "
			  <<	"if the instructions are not received within 24 hours. "
			  <<	"<p><b>Step 3 - Confirm Your Registration</b><br> "
			  <<	"Once you have your confirmation code (and your e-mail address), "
			  <<	"finalize your registration by accepting the eBay User Agreement "
			  <<	"and by completing the " 
			  <<	"<a href=\"";
	 		  
	OutputConfirmationPath(mpStream, WithCC);

	*mpStream		<< "\">Confirmation Registration form"
					   "</a>."
					   "\n";

/*

	if (WithCC)
	{
		*mpStream	<<	"<h2>Your registration is in process!</h2>\n"
						"Within 24 hours (though usually much sooner), "
						"you will receive a Confirmation Instructions with "
						"instructions to complete your registration on our secure "
						"server. You will need a valid VISA or MasterCard at that "
						"time. This information will be used for verification "
						"purposes only. We will not charge your credit card.";
	}

*/
	*mpStream	<<	"<p>"
					"\n";

	return;
}

void clseBayApp::RegisterWithAnonymousEmail(

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
							int UsingSSL)
{
	char*	pTemp = strchr(pEmail, '@');

	*mpStream << "<h2>Registering with E-mail from "
			 <<	pTemp
			<<	"</h2>\n"
			 << "<p>You are registering with an e-mail address from <strong>"
			 <<	pTemp
			 <<	"</strong>. Due to possible authentication problems with "
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetHTMLPath()
			 <<	"help/basics/f-faq.html#44\">"
			 <<	"this e-mail service</a>, "
			 <<	"we ask that you please select <strong>ONE</strong> "
			 <<	"of the following options to complete your registration.\n<p>";

	*mpStream <<	"<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n"
				"<tr><td bgcolor=\"#99CCCC\"><font size=\"+1\"><b>1.&nbsp;&nbsp;</b></font></td>\n"
				"<td bgcolor=\"#99CCCC\"><font size=\"+1\"><b>Provide a different e-mail address</b></font></td></tr>\n"
				"<tr><td>&nbsp;</td>"
				"<td><br>Please go back to the previous page and enter a "
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetHTMLPath()
			 <<	"help/basics/f-faq.html#44\">"
				"non-anonymous e-mail address</a> on the eBay "
				"Registration Form. For example, use an e-mail "
				"address from an internet service provider such as AOL, "
				"MSN<sup><font size=1>TM</font></sup>, or Netcom<sup><font size=1>TM</font></sup>. Or enter the "
				"e-mail address you use at work.</td></tr>"
				"<tr><td>&nbsp; </td></tr>\n"
				"<tr><td>&nbsp; </td></tr>\n"
				"<tr><td bgcolor=\"#99CCCC\"><font size=\"+1\"><b>2.&nbsp;&nbsp;</b></font></td>\n"
				"<td bgcolor=\"#99CCCC\"><font size=\"+1\"><b>Provide valid credit card information (no charge)</b></font></td></tr>\n"
				"<tr><td>&nbsp;</td>"
				"<td><br><form method=\"POST\" action=\""
			 	"eBayISAPI.dll?\">\n"
				"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"Register\">\n"
				"\n<input TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
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
			 << "\">\n<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""	// PH 05/04/99
			 << countryId													// PH 05/04/99 
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
 			 /*	
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q15\" VALUE=\""
			 << interest2
			 */
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q3\" VALUE=\""
			 << age
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q4\" VALUE=\""
			 << education
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q5\" VALUE=\""
			 << income
			 << "\">\n<input TYPE=\"hidden\" NAME=\"Q16\" VALUE=\""
			 << survey
			 << "\">\n<input TYPE=\"hidden\" NAME=\"withcc\" VALUE=\"1\">\n"
			 << "<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
			 << UsingSSL
			 << "\">\n"
			 // nsacco 07/07/99 added siteid and copartnerid
			 << "<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
			 << siteId
			 << "\">\n"
			 << "<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
			 << coPartnerId
			 << "\">\n"
			 << "<p>If you wish to use your <strong>"
			 <<	pTemp
			 <<	"</strong> e-mail address "
				"at eBay, you must provide a valid VISA or MasterCard for "
				"verification. Click \"continue\" below to receive e-mail "
				"instructions to complete the registration process.<p>\n"
				"<input type=\"submit\" value=\"Continue\"></p>\n"
				"</form></td></tr></table>\n";
}

bool clseBayApp::IsAnonymousEmail(char* pEmail)
{
	return mpUsers->IsAnonymousEmail(pEmail);
} 

// nsacco 07/19/99 rewritten
void clseBayApp::RegisterByCountry(CEBayISAPIExtension *pServer,
								   CHttpServerContext* pCtxt,
								   int countryId,
								   int UsingSSL)
{
	char			 pRegURL[256];
	clsCountries    *pCountries = NULL;

	// Instead of maintaining this essentially static page in code,
	// redirect the user to the correct registration page.

	pCountries = mpMarketPlace->GetCountries();
	if (pCountries)
		pCountries->SetCurrentCountry(countryId);

	
	strcpy(pRegURL, mpMarketPlace->GetHTMLPath());

	// nsacco 06/24/99
	switch ((CountryCodes)countryId)
	{
	case Country_None:
		strcat(pRegURL, "services/registration/register-by-country-again.html");
		break;
	case Country_US:
		if (UsingSSL == 0)
		{
			strcpy(pRegURL, "http://");
			strcat(pRegURL, gApp->GetEnvironment()->GetServerName());
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_US));
			strcat(pRegURL, "services/registration/registration-show.html");
		}
		else
		{
			strcpy(pRegURL, "https://secure");
			strcat(pRegURL,clsUtilities::GetDomainToken(SITE_EBAY_US, PARTNER_EBAY));
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_US));
			strcat(pRegURL, "services/registration/ssl-registration-show.html");
		}
		break;

	case Country_UK:
		if (UsingSSL == 0)
		{
			strcpy(pRegURL, "http://");
			strcat(pRegURL, gApp->GetEnvironment()->GetServerName());
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_UK));
			strcat(pRegURL, "services/registration/registration-show.html");
		}
		else
		{
			strcpy(pRegURL, "https://secure");
			strcat(pRegURL,clsUtilities::GetDomainToken(SITE_EBAY_UK, PARTNER_EBAY));
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_UK));
			strcat(pRegURL, "services/registration/ssl-registration-show.html");
		}
		break;
	case Country_CA:
    case Country_DE:			// PH 05/03/99
		if (UsingSSL == 0)
		{
			strcpy(pRegURL, "http://");
			strcat(pRegURL, gApp->GetEnvironment()->GetServerName());
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_CA));
			strcat(pRegURL, "services/registration/registration-show.html");
		}
		else
		{
			strcpy(pRegURL, "https://secure");
			strcat(pRegURL,clsUtilities::GetDomainToken(SITE_EBAY_CA, PARTNER_EBAY));
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_CA));
			strcat(pRegURL, "services/registration/ssl-registration-show.html");
		}
		break;
	case Country_AU:
		if (UsingSSL == 0)
		{
			strcpy(pRegURL, "http://");
			strcat(pRegURL, gApp->GetEnvironment()->GetServerName());
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_AU));
			strcat(pRegURL, "services/registration/registration-show.html");
		}
		else
		{
			strcpy(pRegURL, "https://secure");
			strcat(pRegURL,clsUtilities::GetDomainToken(SITE_EBAY_AU, PARTNER_EBAY));
			strcat(pRegURL, "/");
			strcat(pRegURL, mpMarketPlace->GetCountries()->GetCountryDir(Country_AU));
			strcat(pRegURL, "services/registration/ssl-registration-show.html");
		}
		break;
	default:
		pCountries = mpMarketPlace->GetCountries();
		if (pCountries)
		{
			if (UsingSSL == 0)
			{
				ShowRegistrationForm(pServer, countryId);
				return;
			}
			else
			{
				sprintf(pRegURL, "%seBayISAPI.dll?ShowRegistrationForm&cid=%d&UsingSSL=1",
					mpMarketPlace->GetSSLCGIPath(), countryId);
			}
		}
		else 
		{
			// This should never happen!
			strcat(pRegURL, "register-by-country-again.html");
		}
	}

	pServer->EbayRedirect(pCtxt, pRegURL);

	return;
}

void clseBayApp::ConfirmByCountry(CEBayISAPIExtension *pServer,
								  CHttpServerContext* pCtxt,
								  int countryId,
								  int withCC)
{
	char			 pRegURL[128];
	clsCountries    *pCountries = NULL;

	// Instead of maintaining this essentially static page in code,
	// redirect the user to the correct registration page.

	SetUp(); 

	pCountries = mpMarketPlace->GetCountries();
	if (pCountries)
		pCountries->SetCurrentCountry(countryId);

	if (withCC)
		strcpy(pRegURL, mpMarketPlace->GetSecureHTMLPath());
	else
		strcpy(pRegURL, mpMarketPlace->GetHTMLPath());

	switch ((CountryCodes)countryId)
	{
	case Country_US:
	case Country_CA:
	case Country_UK:
	case Country_AU:		// nsacco 06/09/99
	case Country_DE:		// PH 05/04/99

		if (withCC)
		{
			strcat(pRegURL, pCountries->GetCurrentCountryCode());
			strcat(pRegURL, "-cc-reg-confirm.html");
		}
		else
		{
			strcat(pRegURL, pCountries->GetCurrentCountryDir());
			strcat(pRegURL, "services/registration/reg-confirm.html");
		}
		break;

	case Country_None:
		if (withCC)
			strcat(pRegURL, "cc-reg-confirm.html");
		else
			strcat(pRegURL, "services/registration/confirm-by-country-again.html");
		break;

	default:
		if (withCC)
			strcat(pRegURL, "cc-reg-confirm.html");
		else
			strcat(pRegURL, "services/registration/reg-confirm.html");
	}

	pServer->EbayRedirect(pCtxt, pRegURL);

	CleanUp();

	return;
}

//
// ValidateNonRequiredRegistrationInfo
//
//	Common routine (used in other places, too) to validate
//	non-required registration information. Returns true if everything
//	was ok, false if an error occurred
// Note: This routine now does very little. 12/1/98
bool clseBayApp::ValidateNonRequiredRegistrationInfo(const char * pCompany,
													  const char * pNightPhone1,
													  const char * pNightPhone2,
													  const char * pNightPhone3,
													  const char * pNightPhone4,
													  const char * pFaxPhone1,
													  const char * pFaxPhone2,
													  const char * pFaxPhone3,
													  const char * pFaxPhone4,
													  const char * pGender,
													  char * pFriend_email) const
{
	bool	error	= false;

	// check company name length 
	if (!FIELD_OMITTED(pCompany))
	{
		if (strlen(pCompany) > EBAY_MAX_COMPANY_SIZE)
		{
			*mpStream <<	ErrorMsgCompanyTooLong
					  <<	"\n";
			error	= true;
		}
	}


	//checking refer a friend field, the email address must have @
	if ((pFriend_email) && (!FIELD_OMITTED( pFriend_email)))
	{
		if(!ValidateEmail(pFriend_email))
		{
			*mpStream	<< ErrorMsgOmittedAtSignInFriendEmail;
						   
			*mpStream <<	"<P><br>";
			
			error = true;
		}
	}

	return	error;
}


// A generic routine that allows the user to review the required reg info.
// Returns whether or not the user should be allowed to continue with
//  the info given. Also returns UVrating and UVdetail.
// Set ShowReview=true if you want the review to be printed to the user.
// Set ShowReview=false if you want just to get the bool back without emitting anything.
bool clseBayApp::ValidateAndReviewRequiredInfo(int * pUVrating,
												int * pUVdetail,
												bool ShowReview,
												const char * pEmail, 
												const char * pName, 
												const char * pAddress, 
												const char * pCity, 
												const char * pState, 
												const char * pZip, 
												const char * pCountry, 
												int countryId,			// PH 05/04/99
												const char * pDayPhone1,
												const char * pDayPhone2,
												const char * pDayPhone3,
												const char * pDayPhone4) const
{
	ostrstream   deadStream;
	ostream		*pTheStream; 
	
	bool informationOK = true;

	char* pFormattedDayPhone;
	clsCountries *pCountries = NULL;
// PH	int country = 0;

 	clsInternationalUtilities objIntlUtils;
	pCountries = mpMarketPlace->GetCountries();
//	if (pCountries)
//		country = pCountries->GetCountryId(pCountry);
	objIntlUtils.SetCurrentCountry(countryId);   // PH 05/04/99

	const char* pOk = "<font color=\"darkgreen\"><b>OK</b></font>";
	const char* pNotOk = "<font color=\"#CC6600\"><b>Please double check</b></font>";
	const char* pReallyNotOk = "<font color=\"red\"><b>Invalid</b></font>";
	const char* pTooLong = "<font color=\"red\">Too long</font>";

	// decide whether or not to print out anything
	if (ShowReview) pTheStream = mpStream;
		else pTheStream = &deadStream;

	// first check UVrating 

	// Calculate user verification rating
	mpUserVerificationServices->CalculateUVRatingAndDetail(	pUVrating,
															pUVdetail,
															pCity,
															pState,
															pZip,
// petra															pCountry,
															countryId,	// petra
															pDayPhone1,
															pDayPhone2,
															pDayPhone3,
															pDayPhone4);

	
	if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
		informationOK = (*pUVrating >= 0);
	else
		informationOK = true;

	*pTheStream <<	"<H3>Please Review The Following Information </H3>";

	*pTheStream <<	"<p><table border=\"1\" width=\"590\" "
			  <<	"cellspacing=\"0\" cellpadding=\"4\">";

	// Email address
	if (pEmail)
	{
		*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong>"
						"<font size=\"3\" color=\"#006600\">E-mail address</font></strong>"
						"</td>"
						"<td width=\"45%\">";
		*pTheStream <<	(FIELD_OMITTED(pEmail) ? "&nbsp;" : pEmail)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// No check for email address. Done previously. Also will be checking in Step 2.
		*pTheStream <<	"to be verified in Step 2";
		
		*pTheStream <<	"</td></tr>\n";
	}

	// Full name
	if (pName)
	{
		*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
						"<font color=\"#006600\"><strong>Full name</strong></font></td>"
						"<td width=\"45%\">"
				  <<	(FIELD_OMITTED(pName) ? "&nbsp;" : pName)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Name check is minimal--makes sure there is at least one space
		if (FIELD_OMITTED(pName) || !strchr(pName, ' '))
		{
			informationOK = false;
			*pTheStream <<	pReallyNotOk;
		}
		else
		{
			if (strlen(pName) > EBAY_MAX_NAME_SIZE)
			{
				informationOK = false;
				*pTheStream <<	pTooLong;
			}
			else
				*pTheStream <<	pOk;
		}

		*pTheStream <<	"</td></tr>\n";
	}

	// Address
	if (pAddress)
	{
		*pTheStream <<	"<tr>"
				  <<	"<td width=\"25%\" bgcolor=\"#EFEFEF\">"
				  <<	"<font color=\"#006600\"><strong>Address</strong></font></td>"
				  <<	"<td width=\"5545\">"
				  <<	 (FIELD_OMITTED(pAddress) ? "&nbsp;" : pAddress)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Address check is minimal--makes sure there is at least one space
		if (FIELD_OMITTED(pAddress) || !strchr(pAddress, ' '))
		{
			informationOK = false;
			*pTheStream <<	pReallyNotOk;
		}
		else
		{
			if (strlen(pAddress) > EBAY_MAX_ADDRESS_SIZE)
			{
				informationOK = false;
				*pTheStream <<	pTooLong;
			}
			else
				*pTheStream <<	pOk;
		}
	
		*pTheStream <<	"</td></tr>\n";
	}

	// City
	if (pCity)
	{
		*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
						"<font color=\"#006600\"><strong>City</strong></font></td>"
						"<td width=\"45%\">"
				  <<	(FIELD_OMITTED(pCity) ? "&nbsp;" : pCity)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Check for empty one
		if (FIELD_OMITTED(pCity))
		{
			informationOK = false;
			*pTheStream << pReallyNotOk;
		}
		else
		{
			// Show the city check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
				*pTheStream <<	(((*pUVdetail & UVDetailValidCity) &&
								((*pUVdetail & UVDetailZipMatchesCity) || (*pUVdetail & UVDetailAreaCodeMatchesCity) || (*pUVdetail & UVDetailCityMatchesState)))
								? pOk : pNotOk);

			}
		}
		
		*pTheStream <<	"</td></tr>\n";
	}

	// State
	if (pState)
	{
		*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
						"<font color=\"#006600\"><strong>";
		
		switch (countryId)
		{
		case Country_US: 
			*pTheStream << "State";
			break;
		case Country_CA:
			*pTheStream << "Province";
			break;
		case Country_UK:	// nsacco 06/09/99
			*pTheStream << "County";
			break;
		case Country_AU:
			*pTheStream << "State/Territory";
			break;
		default:
			*pTheStream << "Region";
			break;
		}

		*pTheStream <<  "</strong></font></td>"
						"<td width=\"45%\">";

		*pTheStream <<	((FIELD_OMITTED(pState) || strcmp(pState,"other")==0) ? "&nbsp;" : pState);											
		*pTheStream <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Check for empty one
		if (FIELD_OMITTED(pState) || strcmp(pState,"other")==0)
		{
			informationOK = false;
			*pTheStream << pReallyNotOk;
		}
		else
		{
			// Show the state check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
				*pTheStream <<	(((*pUVdetail & UVDetailZipMatchesState) ||
								(*pUVdetail & UVDetailAreaCodeMatchesState) || (*pUVdetail & UVDetailCityMatchesState))
								? pOk : pNotOk);
			}
		}
		
		*pTheStream <<	"</td></tr>\n";
	}

	// Zip
	if (pZip)
	{
		*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
						"<font color=\"#006600\"><strong>";
		
		switch (countryId)
		{
		case Country_US: 
			*pTheStream << "Zip Code";
			break;
		case Country_UK: 
		case Country_AU:	// nsacco 06/09/99
			*pTheStream << "Postcode";
			break;
		case Country_CA:
		default:
			*pTheStream << "Postal Code";
			break;
		}

		*pTheStream <<  "</strong></font></td>"
						"<td width=\"45%\"> "
				  <<	(FIELD_OMITTED(pZip) ? "&nbsp;" : pZip)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Check for empty one
		if (FIELD_OMITTED(pZip))
		{
			informationOK = false;
			*pTheStream << pReallyNotOk;
		}
		else
		{
			// Show the zip check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
				*pTheStream <<	(((*pUVdetail & UVDetailValidZipCode) &&
								((*pUVdetail & UVDetailZipMatchesState) || (*pUVdetail & UVDetailZipCloseToAreaCode)))
								? pOk : pNotOk);
			}
		}

		*pTheStream <<	"</td></tr>\n";
	}
  
	// Country
	if (pCountry)
	{
		*pTheStream <<	"<tr> <td width=\"25%\" bgcolor=\"#EFEFEF\">"
						"<font color=\"#006600\"><strong>Country</strong></font></td>"
						"<td width=\"45%\">"
				  <<	(FIELD_OMITTED(pCountry) ? "&nbsp;" : pCountry)
				  <<	"</td>";

		*pTheStream <<	"<td width=\"30%\">&nbsp;";

		// Minimal country check
		if (FIELD_OMITTED(pCountry) || strcmp(pCountry,"other")==0)
		{
			informationOK = false;
			*pTheStream <<	pReallyNotOk;
		}
		else
		{
			*pTheStream <<	pOk;
		}


		*pTheStream <<	"</td></tr>\n";
	}

	// Phone
	// nsacco 06/10/99
	if (countryId == Country_UK)	// only 3 sections so there is no pDayPhone3
	{
		if (pDayPhone1 && pDayPhone2 && pDayPhone4)	// new style (separated out in 3 fields)
		{
			// Don't forget to free the memory allocated here.
			pFormattedDayPhone = objIntlUtils.FormatPhone(pDayPhone1, pDayPhone2, NULL, pDayPhone4);

			*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
							"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
							"<td width=\"45%\">"
						<<  pFormattedDayPhone;

			*pTheStream <<  "</td>";

			*pTheStream <<	"<td width=\"30%\">&nbsp;";

			// Free the memory allocated in FormatPhone.
			delete [] pFormattedDayPhone;
		}
		else	// for backward compatibility where everything is packed into dayphone1
		{
			if (pDayPhone1)
			{
				*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
								"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
								"<td width=\"45%\">"
						  <<    pDayPhone1;

				*pTheStream <<  "</td>";

				*pTheStream <<	"<td width=\"30%\">&nbsp;";
			}
		}
	}
	else	// all other
	{
		if (pDayPhone1 && pDayPhone2 && pDayPhone3 && pDayPhone4)	// new style (separated out in 4 fields)
		{
			// Don't forget to free the memory allocated here.
			pFormattedDayPhone = objIntlUtils.FormatPhone(pDayPhone1, pDayPhone2, pDayPhone3, pDayPhone4);

			*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
							"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
							"<td width=\"45%\">"
						<<  pFormattedDayPhone;

			*pTheStream <<  "</td>";

			*pTheStream <<	"<td width=\"30%\">&nbsp;";

			// Free the memory allocated in FormatPhone.
			delete [] pFormattedDayPhone;
		}
		else	// for backward compatibility where everything is packed into dayphone1
		{
			if (pDayPhone1)
			{
				*pTheStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
								"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
								"<td width=\"45%\">"
						  <<    pDayPhone1;

				*pTheStream <<  "</td>";

				*pTheStream <<	"<td width=\"30%\">&nbsp;";
			}
		}
	}

	// Check for empty one
	if (FIELD_OMITTED(pDayPhone1))
	{
		informationOK = false;
		*pTheStream << pReallyNotOk;
	}
	else
	{
		// Show the phone check only if we actually have a valid UV rating for this country
		if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
		{

			if (pDayPhone1)
			{

				// special checks for really bad phone #s
				if (!(*pUVdetail & UVDetailPhonePrefixNot555) || !(*pUVdetail & UVDetailPhoneNumberLength))
				{
					*pTheStream	<<	pReallyNotOk;
				}
				else
				{
					*pTheStream	<<	(((*pUVdetail & UVDetailValidAreaCode)  &&
									((*pUVdetail & UVDetailAreaCodeMatchesState) || (*pUVdetail & UVDetailZipCloseToAreaCode) || (*pUVdetail & UVDetailAreaCodeMatchesCity)))
									? pOk : pNotOk);
				}
			}
		}
	}

	*pTheStream <<	"</td></tr>\n";


	// Ok, just finish off the table
	*pTheStream <<	"</table>\n";

	// For debugging purposes only
	//*pTheStream	<<	"<br><font size=1 color=\"#999999\">UV Rating " << *pUVrating;
	//*pTheStream	<<	"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UV Detail " << *pUVdetail << "</font>";

	if (!informationOK)
		*pTheStream << "<p><font color=\"red\" size=\"4\">There are problems with the information you submitted.<p>Please click the Back button to make the corrections indicated.</font>";


	return informationOK;

}
