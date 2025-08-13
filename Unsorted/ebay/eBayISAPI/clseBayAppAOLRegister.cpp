/*	$Id: clseBayAppAOLRegister.cpp,v 1.1.8.2.4.2 1999/08/05 18:58:50 nsacco Exp $	*/
//
//	File:	clseBayAppAOLRegister.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Handle a registration request for AOL users
//
// Modifications:
//				- 06/02/99 lou		- Created
//				- 07/02/99 nsacco	- added siteId to RegisterWithAnonymousEmail
//				- 07/07/99 nsacco	- added siteid and co_partnerid
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
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
"please refer to our <a href=\"http://pages.ebay.com/help/faq/userid.html\">User ID FAQ.</a> ";

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

// 
// MailUserRegistrationNotice
//
int clseBayApp::AOLMailUserRegistrationNotice(char *pUserId,
											   char *pEmail,
											   int	nId,
											   bool WithCC)
{
	clsMail			*pMail;
	ostream			*pMStream;
	char			 subject[256];
	int				 mailRc;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	*pMStream 
		<<	"\n\nDear "
		<<	pUserId
		<<	",\n\n";


	*pMStream	<<	"Thank you for registering with eBay, the world's favorite "
				<<	"personal trading community.  Now that you have received this "
				<<	"confirmation message, you are almost finished with the "
				<<	"registration process!  Just one more step to confirm your "
				<<	"registration.  Please note that you cannot begin buying or "
				<<	"selling on eBay until this confirmation process is completed."
				<<	"\n\n"
				<<	"To confirm your registration, simply click on the URL "
				<<	"(the underlined Web address that begins with \"http://\") "
				<<	"below: \n\n\t";

	// Go to path/us/cc-reg-confirm.html for U.S., etc.
	if (strstr(pUserId, "@aol.com") == NULL)
	{
		AOLOutputConfirmationPath(pMStream, WithCC, nId);
	} 
	else
	{
		*pMStream	<<	"<A HREF=\"";
						AOLOutputConfirmationPath(pMStream, WithCC, nId);
		*pMStream	<<	"\">";
						AOLOutputConfirmationPath(pMStream, WithCC, nId);
		*pMStream	<<	"</A>";
	}


	*pMStream	<<	"\n\n";

		
	*pMStream	<<	"If for some reason clicking on the URL does not work, "
				<<	"you can Copy and Paste or type the URL into your Web "
				<<	"browser at the top of the screen (make sure you do not "
				<<	"include any extra spaces).  For assistance with eBay "
				<<	"registration, please contact "
				<<	"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n\n"
				<<	"After you complete click on the URL, you can begin buying "
				<<	"and selling on eBay.\n\n"
				<<	"Welcome to the eBay community!";

	if (WithCC)
	{
		*pMStream	<<	"You will be required to enter your VISA or MasterCard information.";

	}
	

//	*pMStream	<<	"\n"
//				<<	"\n";

//	*pMStream   <<  "WebTV users -- please visit the link below for instructions "
//		            "on accessing secure pages:"
//					"\n        "
//				<<  mpMarketPlace->GetHTMLPath()
//				<<  "services/registration/secure-webtv-support.html \n\n";

	*pMStream	<<	flush;

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

void clseBayApp::AOLOutputConfirmationPath(ostream	*pStream, bool WithCC, int nId)
{
	clsCountries    *pCountries = mpMarketPlace->GetCountries();

	if (WithCC) // Register on the secure server
		*pStream	<< mpMarketPlace->GetSSLCGIPath();
	else
		*pStream	<<	mpMarketPlace->GetCGIPath();

	//Now add the rest
		*pStream	<<	"eBayISAPI.dll?AOLRegisterConfirm&number="
					<<	nId
					<<	"\n";
}

// nsacco 07/07/99 added siteid and co_partnerid
bool clseBayApp::AOLRegister(CEBayISAPIExtension *pServer,
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
					  char * pNewPass,
					  int notify,
					  int Using,
					  int partnerId,
					  int siteId,
					  int coPartnerId,
					  ostream *pTheStream)					
{

	bool	error		= false;

	int		salt;
	char	cSalt[5];
	char	*pCryptedPassword = NULL;
	char	*pHost;
	int		mailRc;

	time_t	nowTime;

	char*	pTempState = pState;

	bool	IsGhost = false;
 
	clsUser	*pUser = NULL;
 
	clsInternationalUtilities objIntlUtils;
	char *pFormattedDayPhone;
	char *pFormattedNightPhone;
	char *pFormattedFaxPhone;

	int UVrating;
	int UVdetail;

	int nId = 0;

	bool WithCC = false;		//LL: For now

	clsCountries *pCountries = NULL;

	// Set the current country so that we can reference the correct
	// country-specific pages relating to registration.
	pCountries = mpMarketPlace->GetCountries();
	if (pCountries) 
		pCountries->SetCurrentCountry(countryId);

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
				*pTheStream	<<	"<BR>\n"	
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
					"</ul>\n";

				return false;
			}
			else
			{
				// E-mail already seems to exist. Let's
				// see if they're suspended, or they just
				// exist already
				if (mpUser->IsSuspended())
				{
				//	*pTheStream	<< "<BR>\n"	<<	ErrorMsgSuspended;

				// kakiyama 07/09/99

					*pTheStream << clsIntlResource::GetFResString(-1,
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


					return false;
				}
				else if (mpUser->IsConfirmed())
				{
					*pTheStream	<<	"<BR>\n"
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
					"</ul>\n";

					return false;
				}
				else if (mpUser->IsUnconfirmed() || mpUser->IsCCVerify())
				{
					//Get user's id so we can resend the confirmation email
					nId = mpUser->GetId();
				}
				else
				{
				//	*pTheStream	<<	"<BR>\n"	<<	ErrorMsgUnknown;

				// kakiyama 07/08/99

					*pTheStream <<  "<BR>\n"    
						        << clsIntlResource::GetFResString(-1,
											"<h2>Unknown Registration Error</h2>"
											"There has been an unknown error validating your registration. Please "
											"report this error, along with all pertinent information (your selected "
											"userid, name, address, etc.) to "
											"Please contact <a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
											"Customer Support</a> "
											"if you have any questions about this problem. ",
											clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
											NULL);

					return false;
				}
			}
		}
	}
	else
	{
		//Didn't find user by email, so they must be new

		// Check required info again in case someone tried to hack the preview page
		if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, false, pEmail, pName, pAddress, pCity, pState, pZip, pCountry, countryId,
			pDayPhone1, pDayPhone2, pDayPhone3, pDayPhone4))
		{
			*pTheStream <<	"<p>Sorry, invalid data in user contact info.<br>";

			return false;
		}

	/*	LL: Don't need this right now

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
								UsingSSL);

			*mpStream <<	"<br>";
			if (UsingSSL == 0)
				*mpStream	<<	mpMarketPlace->GetFooter();
			else
				*mpStream	<<	mpMarketPlace->GetSecureFooter();

			CleanUp();

			return;
		}
	*/

		//Get salt
		srand( (unsigned)time( NULL ) );
		salt			= ((int)rand());
		sprintf(cSalt, "%d", salt);

		// Let's encrypt it
		pCryptedPassword = crypt(pNewPass, cSalt);
		
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

		pHost	= gApp->GetEnvironment()->GetRemoteAddr();
		
		nowTime	= time(0);

		if (pCountries) 
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
								0,
								pUserId,
								UserUnconfirmed,
								pCryptedPassword,
								cSalt ,
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

		// Now, let's add the user
		mpUsers->AddUser(pUser);


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
		//Get user's id
		nId = pUser->GetId();
	}

	//Now send the email
	mailRc = AOLMailUserRegistrationNotice(pUserId, pEmail, nId, WithCC);
		
	if (!mailRc) 
	{
		*pTheStream	<<	ErrorMsgMail
						<<	"<br>";

		return false;
	}

	//Make sure it's valid
	if (pCryptedPassword)
		free (pCryptedPassword);
	
	//Make sure it's valid
	if (pUser)
		delete	pUser;

	return true;
}

#ifdef LOU	//LL:

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
			 <<	"help/faq/faq.html#44\">"
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
			 <<	"help/faq/faq.html#44\">"
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

#endif	//LOU

