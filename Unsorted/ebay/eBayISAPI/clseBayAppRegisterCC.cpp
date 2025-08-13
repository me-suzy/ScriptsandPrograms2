/*	$Id: clseBayAppRegisterCC.cpp,v 1.7.164.3.76.2 1999/08/05 18:59:01 nsacco Exp $	*/
//
//	File:	clseBayAppRegisterCC.cpp
//
//	Class:	clseBayApp
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:
//
//		Register CC info. as part of new user registration.
//
// Modifications:
//				- 05/28/98 Sam	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsAuthorizationQueue.h"

//
// Support for our own personal crypt
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Last Day of Month
const int LastDayOfMonth[]  =  { 31, 28, 31, 30, 31, 30, 31,
									 31, 30, 31, 30, 31 };

#define lastday(dd,mm,yyyy)		if (mm == 2 && ((yyyy % 4)==0) )		\
									dd=29;								\
								else									\
									dd = LastDayOfMonth[mm-1];				


// Error Messages
static const char *ErrorMsgBlankField =
"<h2>Value Not Entered</h2>"
"Sorry, \"%s\" field is required to process the form. "
"Please fill it in and submit again.";

static const char *ErrorMsgAlreadyConfirmed =
"<h2>Already Registered</h2>"
"Thank you, your registration has already been confirmed. "
"If you would like to change your registration information, "
"please go to Site Map and click on Change Registered Information";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNoPass =
"<h2>Temporary password not entered</h2>"
"Sorry, you must enter the confirmation code you received "
"in the confirmation instructions. Please try it again. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again. ";
*/

static const char *ErrorMsgNoNewPass =
"<h2>No new password</h2>"
"Sorry, you <b>must</b> supply a new password. "
"Please try again.";

static const char *ErrorMsgNewPassDifferent =
"<h2>New passwords differ</h2>"
"Sorry, the two new passwords you entered are different. "
"Please try again.";

static const char *ErrorMsgPasswordsTheSame =
"<h2>Old and new passwords the same</h2>"
"Sorry, your new password appears to be the same as the "
"old special password provided in the confirmation instructions. Please "
"choose a different new password.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgBadUserOrPassword =
"<h2>Invalid User ID or password</h2>"
"Sorry, the User ID and/or the special password "
"does not match the one sent to you in the confirmation "
"instructions. Please check and try again. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again. ";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
"Customer Support</a> "
"if you have any questions about this problem. ";
*/

static const char *ErrorMsgUnknown =
"<h2>Internal Error</h2>"
"Sorry, there was a problem processing your request. "
"Please try again.";

static const char *ErrorMsgCouldNotProcess =
"<h2>Internal Error</h2>"
"Sorry, there was a problem processing your request at this time. "
"Please try again later. ";

static const char *ErrorMsgBadCreditCard =
"<h2>Invalid Credit Card Number</h2>"
"Sorry, the Credit Card Number you entered is not valid. "
"We currently accept Visa and MasterCard only. "
"Please check the number and try again.";

static const char *ErrorMsgBadCreditCardDate =
"<h2>Invalid Credit Card Date</h2>"
"Sorry, the Credit Card Expiration Date you entered is invalid. "
"Please enter a correct date.";

static const char *ErrorMsgCreditCardAlreadyExpired =
"<h2>Credit Card Update Error</h2>"
"Sorry, the Credit Card Expiration date you entered has already expired. "
"Please enter a correct date.";


static const char *InformationCCRejected =
"<h2>Credit Card Authorization Failed!</h2>"
"<b>Credit Card Account Number : </b><font color=\"red\"><i>%d-XXXX-XXXX-XXXX</i></font><br>"
"<b>Expiration Date :</b> <i>%02d-%02d-%d</i> <br><br>"
"The credit card number you provided us could *NOT* be authorized. "
"You may provide us a different credit card number for authorization. "
"A valid credit card number is absolutely necessary for completing your registration. ";

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

static const char *ErrorMsgZipTooLong = 
"<h2>Error in Zip Code</h2>"
"Sorry, the zip code you entered was too long. Please go back "
"and try again";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgIdInUse =
"<h2>Registration conflict!</h2>" 
"The User ID you have requested is already in use. Please select another User ID. "
" %s <br>"
"If you would like advice on selecting a User ID, " 
"please refer to our <a href=\"http://pages.ebay.com/help/basics/f-userid.html\">User ID FAQ</a>. ";
*/

static const char *ErrorMsgUserIdHaveAtSign =
"<h2>User ID Rejected</h2>"
"<b>Illegal symbols</b>"
"Sorry! The \"@\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I></LI>"
"<LI>Numbers <I>0-9</I></LI>"
"<LI>Asterisks <I>*</I></LI>"
"<LI>Dollar signs <I>$</I></LI>"
"<LI>Exclamation point <I>!</I></LI>"
"<LI>Parentheses (left and right) <I>( )</I></LI>"
"<LI>Periods <I>.</I></LI>"
"<LI>Hyphens <I>-</I>"
"</LI></UL>";

#define CCUSERID	0x0001
#define CCOLDPASS	0x0002
#define CCNEWPASS	0x0004
#define CCUSERNAME	0x0008
#define CCADDRESS	0x0010
#define CCNUMBER	0x0020
#define CCDATE		0x0040
#define CCUNKNOWN	0x0080
#define CCEMAIL		0x0100

void clseBayApp::RegisterCC(CEBayISAPIExtension *pServer,
							CHttpServerContext *pCtxt,
							char *pEmail,
							char *pOldPass,
							char *pUserId,
							char *pNewPass,
							char *pNewPassAgain,
							char *pUserName,
							char *pStreetAddr,
							char *pCityAddr,
							char *pStateProvAddr,
							char *pZipCodeAddr,
							char *pCountryAddr,
							char *pCC,
							char *pMonth,
							char *pDay,
							char *pYear,
							int	  UseForPayment,
							int	  notify)
{
//	char					 client_hostname[512];
//	char					 client_hostaddr[512];
//	ULONG					 bufLen;
//	eTransState				 trans_state;
	time_t					 expTime;
	clsAccount				*pAccount=NULL;
	int						 CC4Id, DD, MM, YYYY;
	char					 str[5];
	char					 buf[512];
    time_t					 todaysDate;
	clsAuthorizationQueue	*pAuthorizationQueue=NULL;
	int						 salt;
	char					 cSalt[16];
	char					*pCryptedPassword;
	eAccountType			 accType;
	char					 pAccType[5];
	char					 buf_write[1000];
	char					 lpPath[256];
	char					 lpFilePath[256];
	HMODULE					 hmod;
	CFile					 f;
	CFileException			 e;
	bool					 error;
	int						 unused;

	char*					pSuggestedUserId;

	clsUser*				pTempUser;


	// Setup & initialize
	SetUp();
	memset(buf, '\0', sizeof(buf));
	memset(str, '\0', sizeof(str));
//	memset(client_hostname, '\0', sizeof(client_hostname));
//	memset(client_hostaddr, '\0', sizeof(client_hostaddr));

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Registration Confirmation"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetSecureHeader()
			  <<	"\n";

	// Let's check that we've got a password.
	if (FIELD_OMITTED(pOldPass))	
	{
	//	*mpStream <<	ErrorMsgNoPass;

	// kakiyama 07/07/99

		*mpStream <<   clsIntlResource::GetFResString(-1,
							"<h2>Temporary password not entered</h2>"
							"Sorry, you must enter the confirmation code you received "
							"in the confirmation instructions. Please try it again. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again. ",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);

		CCConfirmError( CCOLDPASS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);

		CleanUp();
		return;
	}

	// Let's get the user. 
	strlwr(pEmail);
	strlwr(pOldPass);
	mpUser	=	mpUsers->GetUser(pEmail);

	// Let's see how they are
	if (!mpUser)
	{
	//	*mpStream <<	ErrorMsgBadUserOrPassword;

	// kakiyama 07/07/99

		*mpStream <<	clsIntlResource::GetFResString(-1,
							"<h2>Invalid User ID or password</h2>"
							"Sorry, the User ID and/or the special password "
							"does not match the one sent to you in the confirmation "
							"instructions. Please check and try again. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again. ",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);

		CCConfirmError( CCOLDPASS | CCEMAIL, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (mpUser->IsConfirmed())
	{
		*mpStream	<<	ErrorMsgAlreadyConfirmed
					<<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream	<<	ErrorMsgSuspended
					<<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}


	if (!mpUser->IsCCVerify())
	{
	//	*mpStream	<<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream   <<  clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry, there was a problem confirming your registration. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
							"Customer Support</a> "
							"if you have any questions about this problem. ",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
			   <<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}

	// Check Password

	if (!mpUser->TestPass(pOldPass))
	{
	//	*mpStream <<	ErrorMsgBadUserOrPassword;

	// kakiyama 07/07/99

		*mpStream <<	clsIntlResource::GetFResString(-1,
							"<h2>Invalid User ID or password</h2>"
							"Sorry, the User ID and/or the special password "
							"does not match the one sent to you in the confirmation "
							"instructions. Please check and try again. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}/services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again. ",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);


		CCConfirmError( CCEMAIL | CCOLDPASS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// if pUserId is null, we already use the E-mail as the ID in registration page

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	if ( !FIELD_OMITTED(pUserId) )
	{
		//Let's see if the userid is already taken.
		pTempUser = mpUsers->GetUser(pUserId);

		if (pTempUser)
		{
			//in case a user registered before the change and confirmed after 
			//the change. they still can use the User Id they chose in old registration 
			if(strcmp(pEmail, pTempUser->GetEmail()))
			{
				// UserId already to exist and is not the 
				// same email (we know this because we have checked
				// earlier using the email)

				pSuggestedUserId = GetSuggestedUserId(mpUser, pUserId);

			//	char*	pTempMsg = new char[strlen(ErrorMsgIdInUse) + strlen(pSuggestedUserId) + 1];

			// kakiyama 07/07/99

				char*   pTempMsg = new char[strlen(clsIntlResource::GetFResString(-1,
												"<h2>Registration conflict!</h2>" 
												"The User ID you have requested is already in use. Please select another User ID. "
												" %{1:pSuggestedUserId} <br>"
												"If you would like advice on selecting a User ID, " 
												"please refer to our <a href=\"%{2:GetHTMLPath}help/basics/f-userid.html\">User ID FAQ</a>. ",
												clsIntlResource::ToString(pSuggestedUserId),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												NULL))
											+ strlen(pSuggestedUserId) + 1];

				sprintf(pTempMsg, clsIntlResource::GetFResString(-1,
										"<h2>Registration conflict!</h2>" 
										"The User ID you have requested is already in use. Please select another User ID. "
										" %{1:pSuggestedUserId} <br>"
										"If you would like advice on selecting a User ID, " 
										"please refer to our <a href=\"%{2:GetHTMLPath}help/basics/f-userid.html\">User ID FAQ</a>. ",
										clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
										NULL), pSuggestedUserId );

		 		*mpStream	<< pTempMsg;

				CCConfirmError( CCUSERID, 
							pEmail,
							pOldPass,
							pUserId,
							pNewPass,
							pNewPassAgain,
							pUserName,
							pStreetAddr,
							pCityAddr,
							pStateProvAddr,
							pZipCodeAddr,
							pCountryAddr,
							pCC,
							pMonth,
							pDay,
							pYear,
							UseForPayment,
							notify);

				delete pTempUser;
				delete pSuggestedUserId;
				delete [] pTempMsg;

				CleanUp();
				return;
			}
		}
		// convert to lower case
#ifdef _MSC_VER
		strlwr(pUserId);
#endif

		if(strcmp(pUserId ,pEmail) )
		{
			//
			// pUserId and pEmail are differents
			//
			// Check that if the character "@" is in pUserId
			if(strchr(pUserId,'@'))
			{
				// pUserId has an @ or & and is not the same as email
				*mpStream	<< ErrorMsgUserIdHaveAtSign;

				CCConfirmError( CCUSERID, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);

				CleanUp();
				return;
			
			} 


			// Check that if the character "&" is in pUserId?
			// it's already checked inside ValidateUserIdChange


			// Check the user id and convert it to lower case
			if( !ValidateUserIdChange(pUserId,mpStream) )
			{
				// pUserId is not null or has a wrong size

				*mpStream	<<	mpMarketPlace->GetFooter();

				CleanUp();
				return;
			}
		}
	}

	// Check te new passwords
	if (FIELD_OMITTED(pNewPass) ||
		FIELD_OMITTED(pNewPassAgain))
	{
		*mpStream <<	ErrorMsgNoNewPass;

		CCConfirmError( CCNEWPASS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	strlwr(pNewPass);
	strlwr(pNewPassAgain);

	if (strcmp(pNewPass, pNewPassAgain) != 0)
	{
		*mpStream <<	ErrorMsgNewPassDifferent;

		CCConfirmError( CCNEWPASS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// Make sure the new one isn't the same as the old
	// one
	if (strcmp(pNewPass, pOldPass) == 0)
	{
		*mpStream <<	ErrorMsgPasswordsTheSame;

		CCConfirmError( CCNEWPASS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// Userid and new, old passwords have been verfied, now for the 
	// second part.
	// Check user entered fields, day, month and yearpart1 are 
	// selectable and will have default values set
	// Check Credit Card field
	// Blank Field Checks

	if (FIELD_OMITTED(pUserName))	
	{
		ErrBlankField ("Your Name");
		CCConfirmError( CCUSERNAME, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}
	if (FIELD_OMITTED(pStreetAddr))	
	{
		ErrBlankField ("Street");
		CCConfirmError( CCADDRESS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}
	if (FIELD_OMITTED(pCityAddr))	
	{
		ErrBlankField ("City");
		CCConfirmError( CCADDRESS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}
	if (FIELD_OMITTED(pStateProvAddr))	
	{
		ErrBlankField ("State/Province");
		CCConfirmError( CCADDRESS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}
	if (FIELD_OMITTED(pZipCodeAddr))	
	{
		ErrBlankField ("Zip Code");
		CCConfirmError( CCADDRESS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// Validate zip
	error = false;
	if (strcmp(pCountryAddr, "usa")==0)
	{
		if (strlen(pZipCodeAddr) < 5 || strlen(pZipCodeAddr) > 10)
		{
			*mpStream <<	ErrorMsgBadZip
					  <<	"<br>";

			error	= true;
		}
		else
		{
			char* pC;
			char* pDash;

			pDash	= pZipCodeAddr + 5;

			for (pC	= pZipCodeAddr;
				 *pC != '\0';
				 pC++)
			{
				if (pC == pDash)
				{
					if (*pC != '-')
					{
						*mpStream <<	ErrorMsgBadZipNoDash
								  <<	"<p>";
						error	= true;
						break;
					}
					else
						continue;
				}

				if (!isdigit(*pC))
				{
					*mpStream <<	ErrorMsgBadZipBadDigit
							  <<	"<p>";
					error	= true;
					break;
				}
			}
		}
				
	}
	else
	{
		if (strlen(pZipCodeAddr) > EBAY_MAX_ZIP_SIZE)
		{
			*mpStream <<	ErrorMsgZipTooLong
					  <<	"<br>";

			error	= true;
		}
	}
	if (error)
	{
		CCConfirmError( CCADDRESS, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pCC))	
	{
		ErrBlankField ("Credit Card Number");
		CCConfirmError( CCNUMBER, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}


	CC4Id = atoi(strncpy(str,pCC,4));

	time(&todaysDate);

	DD		= atoi(pDay);
	MM		= atoi(pMonth);
	YYYY	= atoi(pYear);
	if (DD == 0)
		lastday(DD, MM, YYYY);

	// Card is probably valid, check date validity
	expTime = CheckCCDate(pDay, pMonth, pYear);
	if (expTime == (time_t)0)
	{
		*mpStream <<	ErrorMsgCreditCardAlreadyExpired;

		CCConfirmError( CCDATE, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}
	else if (expTime == (time_t)-1)
	{
		*mpStream <<	ErrorMsgBadCreditCardDate;

		CCConfirmError( CCDATE, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	// Perform Credit Card Checksum check
	if (!CheckCCChecksum(pCC))
	{
		*mpStream <<	ErrorMsgBadCreditCard;

		CCConfirmError( CCNUMBER, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}


//	pCtxt->GetServerVariable("REMOTE_ADDR", client_hostaddr, &bufLen);
//	pCtxt->GetServerVariable("REMOTE_HOST", client_hostname, &bufLen);
	// Update Processing Machine database here
	// Update with new cc details

	if (!mpAuthorizationQueue)
	{
		*mpStream	<<	ErrorMsgUnknown;

		CCConfirmError( CCUNKNOWN, 
						pEmail,
						pOldPass,
						pUserId,
						pNewPass,
						pNewPassAgain,
						pUserName,
						pStreetAddr,
						pCityAddr,
						pStateProvAddr,
						pZipCodeAddr,
						pCountryAddr,
						pCC,
						pMonth,
						pDay,
						pYear,
						UseForPayment,
						notify);
		CleanUp();
		return;
	}

	if (UseForPayment == 0)
		accType = ANON_USER_NO_CC_ON_FILE; // default
	else // User wishes this CC to be used for payments in future
		accType = ANON_USER_CC_ON_FILE;
	sprintf(pAccType, "%d", accType);

	// Send request for real time verification
	// 1. Update cc_authorize which is available locally
	// 2. Obtain CC Authorization from FDMS 
	// 3. Check to see the status and report to user, if status
	// is 'Valid' then report info. was updated., if 'Reject',
	// inform card was rejected with prompt to use another card.
	// if 'Retry' then inform user that they will be notified via
	// email on the approval status of their CC.

	pAuthorizationQueue = mpAuthorizationQueue->Enqueue(pCC,
														expTime,
														1,					// Real time prioirty
														mpUser->GetId(),
														1.0,				// $1.0 Amount charge
														Registration,		// Transaction type
														pUserName,
														pStreetAddr,
														pCityAddr,
														pStateProvAddr,
														pZipCodeAddr,
														pCountryAddr,
														pAccType);

	// setup a delay of 7 sec to emulate real time authorizations
	// This should be replaced with the commented block below
	Sleep(7000); // Put this thread to sleep


	// To be used for stage 2, stage 1 only has a checksum and expiration date check
	// Check and update production DB
/*	trans_state = pAuthorizationQueue->GetCCTransState();
	if (trans_state == Reject)
	{
		sprintf(buf, InformationCCRejected, 
				 CC4Id, MM, DD, YYYY);
		*mpStream	<< buf;		
		*mpStream	<< "<p>"
					<< mpMarketPlace->GetSecureFooter();
		
	}
	else if (trans_state == Retry)
	{
			*mpStream	<<	ErrorMsgUnknown
						<<	"<br>"
						<< mpMarketPlace->GetSecureFooter();

	}


	else if (trans_state == Valid)
	{
		// First update USER_CC_QUEUE with the verification state
		// Only update the table for valid authorization
*/
	// Now store data persistently to cc_billing
	pAuthorizationQueue->StoreCCUpdate(	pAuthorizationQueue->GetId(), 
										pAuthorizationQueue->GetReferenceId());

	// Remove entry from cc_authorize
	pAuthorizationQueue->Remove(pAuthorizationQueue->GetReferenceId());

	// Add entry to text file as well
	// Begin *Only Temporary*, for file write
	hmod = GetModuleHandle("ebayisapi.dll");
	if (hmod != 0)
	{
		GetModuleFileName(hmod,lpPath, sizeof(lpPath));
		memset(lpFilePath, 0, sizeof(lpFilePath));
		strncpy(lpFilePath, lpPath, strrchr(lpPath, '\\')-lpPath);
		strcat(lpFilePath, "\\register\\CCReg.txt");
	}
	else
		strcpy(lpFilePath, ".\\register\\CCReg.txt");

	if(!f.Open(lpFilePath, CFile::modeCreate | CFile::modeNoTruncate | 
		CFile::modeWrite | CFile::shareExclusive, &e ))    
	{
		*mpStream	<<	ErrorMsgUnknown
					<<	"<br>"
					<<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}

    sprintf(buf_write, "cc: %s, expiry: %d, id: %d, Name: %s, Street: %s, City: %s, State: %s, Zip: %s, Country: %s\r\n",
										pCC, expTime, mpUser->GetId(), pUserName, pStreetAddr, pCityAddr,
										pStateProvAddr, pZipCodeAddr, pCountryAddr);

	// Add entry to the file, Seek the beginning of the file
	// Entries are added in order 
	f.Seek(f.GetLength(), CFile::begin);
	f.Write(buf_write,strlen(buf_write));
	f.Close();
	// End for file write


/*
		if (UseForPayment==1)
		{
			// User Credit Card can be used for payment purposes
			pAccount	= mpUser->GetAccount();
			if (!pAccount)
			{
				*mpStream	<<	ErrorMsgUnknown
							<<	"<br>"
							<<	mpMarketPlace->GetSecureFooter();

				if (pAuthorizationQueue)
					delete pAuthorizationQueue;
				CleanUp();
				return;
			}	
			pAccount->UpdateCCDetails(	mpUser->GetId(),	
										CC4Id,			
					                    expTime,			
										todaysDate,
										client_hostaddr,
										client_hostname);
		}
		sprintf(buf, InformationRegistrationStarted, mpMarketPlace->GetCurrentPartnerName());
		*mpStream	<< buf;		
		*mpStream	<< "<p>"
					<< mpMarketPlace->GetSecureFooter();
	}
	else
	{
		*mpStream <<	ErrorMsgCouldNotProcess
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();
	}
*/

	// check if they have user_info data
	// if they do not have user info data, do a beta confirm?
	//
	// Everything is A-Ok. Let's change their state
	//
	mpUser->SetUserState(UserConfirmed);

	//Userid if they choose one
	if( !FIELD_OMITTED(pUserId) && strcmp(pUserId ,pEmail))
	{
		mpUser->SetUserId(pUserId);
	}

	//
	// Now, new salt
	// 
	salt				= ((int)rand());
	sprintf(cSalt, "%d", salt);
	pCryptedPassword	= crypt(pNewPass, cSalt);

	// 
	// Set them!
	//

	mpUser->SetPassword(pCryptedPassword);
	mpUser->SetSalt(cSalt);

	//
	// Set that the user has agreed to the new user agreement.
	// Keep track of whether or not the user wants to be notified of 
	// amendments.
	//
	if (notify == 1)
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
	else
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);

	// And update
	mpUser->UpdateUser();

	// Now, we can finally tell the user how wonderful they are
	ShowWelcomeToEBay();

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	free(pCryptedPassword);


	if (pAuthorizationQueue)
		delete pAuthorizationQueue;

	if (pAccount)
		delete pAccount;

	CleanUp();

	return;

}


void clseBayApp::ErrBlankField (char *fldName)
{
		char					*pBlock;		

		pBlock = new char [strlen(ErrorMsgBlankField)+40];
		sprintf(pBlock, ErrorMsgBlankField, fldName);
		*mpStream		<<	pBlock;

		delete pBlock;
}


void clseBayApp::CCConfirmError(int ErrorCode,
							char *pEmail,
							char *pOldPass,
							char *pUserId,
							char *pNewPass,
							char *pNewPassAgain,
							char *pUserName,
							char *pStreetAddr,
							char *pCityAddr,
							char *pStateProvAddr,
							char *pZipCodeAddr,
							char *pCountryAddr,
							char *pCC,
							char *pMonth,
							char *pDay,
							char *pYear,
							int	  UseForPayment,
							int   notify)
{
	int		i,	Month, Day, Year;

	if (ErrorCode) 
	{
		*mpStream	<<	"<p>The error fields are shown in <font color=\"red\">red</font>.\n";
	}

	*mpStream	<<	"<form method=\"post\" action=\""
//				<<  "http://localhost/aw-cgi/"
				<<	mpMarketPlace->GetSecureHTMLPath()
				<<	"ebayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"RegisterCC\">\n"
					"<input type=\"hidden\" name=\"useforpayment\" value=\"0\">\n";

	if (notify)
		*mpStream << "<input type=\"hidden\" name=\"notify\" value=\"1\">\n";
	else
		*mpStream << "<input type=\"hidden\" name=\"notify\" value=\"0\">\n";

	*mpStream	<<	"<p>For your protection, you must choose a <b>new</b> password now, "
					"which <b>must</b> be different from the confirmation code "
					"that we supplied to you in the confirmation instructions.</p>\n"
				<<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\" width=\"590\">\n"
					"<tr><td bgcolor=\"#EFEFEF\">\n"
					"<font color="
				<<	CCGetColor(ErrorCode, CCEMAIL)
				<<	">Your E-mail Address</font></td>\n"
					"<td><input type=\"text\" name=\"userid\" size=\"40\" maxlength=\"64\"";
/*
	if (!FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	" value=\""
				<<	pUserId
				<<	"\"";
	}

	*mpStream	<<	"></td></tr>\n"
				<<	"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCOLDPASS)
				<<	">The confirmation code we assigned to you, "
					"which is in the confirmation instructions</font></td>\n"
					"<td><input type=\"password\" name=\"oldpass\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pOldPass))
	{
		*mpStream	<<	" value=\""
				<<	pOldPass
				<<	"\"";
	}

	*mpStream	<<	"><br></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCNEWPASS)
				<<	">Choose a <strong>new</strong>, permanent password</font></td>\n"
					"<td><input type=\"password\" name=\"newpass\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pNewPass))
	{
		*mpStream	<<	" value=\""
				<<	pNewPass
				<<	"\"";
	}

	*mpStream	<<	"></td></tr>\n"
				<<	"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCNEWPASS)
				<<	">Type your <strong>new</strong> password again</font></td>\n"
					"<td><input type=\"password\" name=\"newpassagain\" size=\"30\" maxlength=\"64\"";
	
	if (!FIELD_OMITTED(pNewPassAgain))
	{
		*mpStream	<<	" value=\""
				<<	pNewPassAgain
				<<	"\"";
	}
*/

	if (!FIELD_OMITTED(pEmail))
	{
		*mpStream	<<	" value=\""
				<<	pEmail
				<<	"\"";
	}

	*mpStream	<<	"></td></tr>\n"
				<<	"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCOLDPASS)
				<<	">The confirmation code we assigned to you, "
					"which is in the confirmation instructions</font>"
					"<p><font size=\"2\">Click <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/reqtemppass.html\">"
				<<	"here</a> "
					"if you need eBay to resend your confirmation instructions.</font>" 
					"</td>\n"
					"<td width=\"290\"><input type=\"text\" name=\"pass\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pOldPass))
	{
		*mpStream	<<	" value=\""
				<<	pOldPass
				<<	"\"";
	}

	*mpStream	<<	"><br></td></tr>\n"
					"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCUSERID)
				<<	">Choose a <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/myinfo/userid.html\"><strong>User ID</strong></a> nickname:<br>"
				"	<font size=2>The User ID that you choose will become "
				"	your \"eBay name\" that others see when you participate on eBay "
				"	You can create a name or simply use your email address. "
				"	<p> Examples \"wunderkid\", \"jsmith98\", \"jeff@aol.com\".</td>\n"
				"	<td width=\"290\"><input type=\"text\" name=\"userid\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	" value=\""
				<<	pUserId
				<<	"\"";
	}
	*mpStream	<<	"><br></td></tr>\n"
					"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCNEWPASS)
				<<	">Create a <strong>new</strong>, permanent password</font></td>\n"
					"<td width=\"290\"><input type=\"password\" name=\"newpass\" size=\"30\" maxlength=\"64\"";


	if (!FIELD_OMITTED(pNewPass))
	{
		*mpStream	<<	" value=\""
				<<	pNewPass
				<<	"\"";
	}

	*mpStream	<<	"></td></tr>\n"
				<<	"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCNEWPASS)
				<<	">Type your <strong>new</strong> password again</font></td>\n"
					"<td width=\"290\"><input type=\"password\" name=\"newpassagain\" size=\"30\" maxlength=\"64\"";
	
	if (!FIELD_OMITTED(pNewPassAgain))
	{
		*mpStream	<<	" value=\""
				<<	pNewPassAgain
				<<	"\"";
	}


	*mpStream	<<	"></td></tr></table>\n"
					"<p>Because you are using an anonymous e-mail address, please provide your "
					"VISA or MasterCard information below. This information is used for "
					"registration purpose only. Your card will not be charged. </p>\n"
					"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\" width=\"590\"\n>"
					"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCUSERNAME)
				<<	">Your Name</font></td>\n"
					"<td><input type=\"text\" name=\"username\" size=\"40\" maxlength=\"64\"";
	
	if (!FIELD_OMITTED(pUserName))
	{
		*mpStream	<<	" value=\""
				<<	pUserName
				<<	"\"";
	}

	*mpStream	<<	"><br><small>Your name as it appears on your credit card</small></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCADDRESS)
				<<	">Credit Card Billing Address</font></td>\n"
					"<td><input type=\"text\" name=\"streetaddr\" size=\"40\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pStreetAddr))
	{
		*mpStream	<<	" value=\""
				<<	pStreetAddr
				<<	"\"";
	}

	*mpStream	<<	"><br><small>Street</small>\n"
				<<	"<p><input type=\"text\" name=\"cityaddr\" size=\"20\" maxlength=\"32\"";

	if (!FIELD_OMITTED(pCityAddr))
	{
		*mpStream	<<	" value=\""
				<<	pCityAddr
				<<	"\"";
	}

	*mpStream	<<	"><br><small>City</small></p>\n"
					"<p><input type=\"text\" name=\"stateprovaddr\" size=\"20\" maxlength=\"32\"";

	if (!FIELD_OMITTED(pStateProvAddr))
	{
		*mpStream	<<	" value=\""
				<<	pStateProvAddr
				<<	"\"";
	}

	*mpStream	<<	"><br><small>State / Province</small></p>\n"
					"<p><input type=\"text\" name=\"zipcodeaddr\" size=\"20\" maxlength=\"16\"";

	if (!FIELD_OMITTED(pZipCodeAddr))
	{
		*mpStream	<<	" value=\""
				<<	pZipCodeAddr
				<<	"\"";
	}

	*mpStream	<<	"> <br><small>Zip Code</small></p>\n"
					"<p>";
					
	// country drop down list
	EmitDropDownList(mpStream, 
					 "countryaddr",
					 (DropDownSelection *)&CountrySelection,
					 pCountryAddr,
					 "other",
					 "Not Selected");

    *mpStream	<<	"<br><small>Country </small></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\">"
					"<font color="
				<<	CCGetColor(ErrorCode, CCNUMBER)
				<<	">Credit Card Number</font></td>\n"
					"<td><input type=\"text\" name=\"cc\" size=\"40\" maxlength=\"20\"";
	
	if (!FIELD_OMITTED(pCC))
	{
		*mpStream	<<	" value=\""
				<<	pCC
				<<	"\"";
	}

	*mpStream	<<	"><br><i><font size=\"2\">e.g. 4123-4567-8910-1234</font></i></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, CCDATE)
				<<	">Expiration Date</font></td>\n"
					"<td><font size=\"2\"><strong>Month</strong>: <select name=\"month\" size=\"1\">\n";
	*mpStream	<<	"<option value=\"0\">--</option>\n";

	if (ErrorCode) 
		Month = atoi(pMonth);
	else
		Month = 0;

	for (i = 1; i < 13; i++)
	{
		*mpStream	<<	"<option value=\""
					<<	i
					<<	"\"";
		
		if (i == Month)
		{
			*mpStream	<<	" selected ";
		}
		*mpStream	<<	">"
					<<	i
					<<	"</option>\n";
	}
	*mpStream	<<	"</select>&nbsp;&nbsp;&nbsp; Day: <select name=\"day\" size=\"1\">";
	*mpStream	<<	"<option value=\"0\">--</option>\n";

	if (ErrorCode)
		Day = atoi(pDay);
	else
		Day = 0;

	for (i = 1; i <= 31; i++)
	{
		*mpStream	<<	"<option value=\""
					<<	i
					<<	"\"";
		
		if (i == Day)
		{
			*mpStream	<<	" selected ";
		}
		*mpStream	<<	">"
					<<	i
					<<	"</option>\n";
	}
	*mpStream	<<	"</select> <strong>&nbsp; Year</strong>: <select name=\"year\" size=\"1\">";
	*mpStream	<<	"<option value=\"0\">--</option>\n";

	if (ErrorCode)
		Year = atoi(pYear);
	else
		Year = 0;

	for (i = 1998; i < 2011; i++)
	{
		*mpStream	<<	"<option value=\""
					<<	i
					<<	"\"";
		
		if (i == Year)
		{
			*mpStream	<<	" selected ";
		}
		*mpStream	<<	">"
					<<	i
					<<	"</option>\n";
	}
    *mpStream	<<	"</select> <br><i>Leave day as --, if day on credit card is not listed</i>"
					"</font> </td></tr></table>\n"
					"<p><strong>Press </strong>"
					"<input type=\"submit\" value=\"Submit\"> "
					"<strong>to complete your registration.</strong></p>\n"
					"<p>If you are thinking about committing fraud, read "
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/fraud.html\">this</a>.\n</form>"
					"<p>"
				<<	mpMarketPlace->GetSecureFooter();

}

const char* clseBayApp::CCGetColor(int ErrorCode, int FieldCode)
{
	if (ErrorCode & FieldCode)
	{
		return "\"red\"";
	}

	return "\"green\"";
}

