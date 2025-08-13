/*	$Id: clseBayAppRegisterConfirm.cpp,v 1.9.164.4.34.2 1999/08/05 18:59:02 nsacco Exp $	*/
//
//	File:	clseBayAppRegisterConfirm.cpp
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
//              - 07/07/99 soc      - bug fix # 2851 take out reference to skippy
//                                    in the registration confirm email text
//              - 07/08/99 soc      - bug fix # 229 cosmetic (change UserId to UserID)
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
//
// Support for our own personal crypt
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Error Messages
static const char *ErrorMsgAlreadyConfirmed =
"<h2>Already Registered</h2>"
"Thank you, your registration has already been "
"confirmed.";

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

static const char *ErrorMsgBadUser =
"<LI><strong>E-mail address is wrong</strong></LI>"
"<OL>Sorry, the E-mail "
"does not match the information sent to you in the confirmation "
"notice. Please make sure you're using same e-mail address.</OL><br> ";

static const char *ErrorMsgNoPass =
"<LI><strong>The \"confirmation code\" not entered </strong> </LI>"
"<OL>Sorry, you must enter the \"confirmation code\" you received "
"in the confirmation instructions. "
"If you did not receive this e-mail, or if you have lost it, "
"please "
"<a href=\"%sservices/registration/reqtemppass.html\">click here</a>"
" to let eBay resend your comformation instructions. "
"</OL><br>";

static const char *ErrorMsgBadUserOrPassword =
//"<form method=post action=\"%seBayISAPI.dll\"> " 
//"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ResendConfirmationEmail\"> "
//"<INPUT TYPE=HIDDEN NAME=\"email\" VALUE=\"%s\">" 
"<LI><b>Invalid e-mail or confirmation code</b>"
"<OL>Sorry, the E-mail or the \"confirmation code\" "
"do not match the information sent to you in the confirmation "
"notice. "
"Please make sure you have entered this information correctly. "
"If you lost this email, please "
"<a href=\"%sservices/registration/reqtemppass.html\">click here</a>"
" to let eBay resend your comformation instructions. "
" </OL><br>";


static const char *ErrorMsgNoNewPass =
"<LI><b>No password</b></LI>"
"<OL>Sorry, you <b>must</b> create a new password.</OL><br>";

static const char *ErrorMsgNewPassDifferent =
"<LI><b>New passwords differ</b></LI>"
"<OL>Sorry, the two passwords you entered are different. "
"Please try again. </OL><br>";

static const char *ErrorMsgPasswordsTheSame =
"<LI><b>New passwords same as Confirmation Code</b></LI>"
"<OL>Sorry, your password appears to be the same as the "
"\"confirmation code\" provided in the confirmation instructions. Please "
"create a different new password. </OL><br>";

static const char *ErrorMsgIdInUse =
"<LI><b>Registration conflict!</b></LI>" 
"<OL>The User ID you have requested is already in use. Please select another User ID. "
" %s <br>"
"If you would like advice on selecting a User ID, " 
"please refer to our <a href=\"http://pages.ebay.com/help/basics/f-userid.html\">User ID FAQ.</a> </OL><br>";


static const char *ErrorMsgUserIdHaveAmpercent =
"<Li><b>User ID Rejected</b></LI>"
"<br><b>Illegal symbols</b><br>"
"Sorry! The \"&\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"<LI>Hyphens <I>-</I>"
"</LI></UL><P>";

static const char *ErrorMsgUserIdHaveAtSign =
"<Li><b>User ID Rejected</b></LI>"
"<br><b>Illegal symbols</b><br>"
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

// 05/28/99 petra added
static const char *ErrorMsgUserIdHaveFunnyCharacters =
"<Li><b>User ID Rejected</b></LI>"
"<br><b>Illegal symbols</b><br>"
"Sorry! Your User ID contains unacceptable characters, possibly special"
" characters like &uuml; or &eacute;.<BR>"
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

static const char *ErrorMsgNoEmail =
"<LI><strong>Email Address not entered </strong> </LI>"
"<OL>Sorry, you must enter the email addrss. </OL><br>";

static const char *ErrorMsgWrongCountry =
"<LI><strong>Did not accept the correct User Agreement </strong> </LI>"
"<OL>Sorry, you must accept the User Agreement appropriate "
"for your home country. Please "
"<a href=\"http://pages.ebay.com/services/registration/confirm-by-country.html\">"
"select your home country</a> first."
"</OL><br>";

#define EMAIL	  0x0001
#define OLDPASS	  0x0002
#define USERID	  0x0004
#define NEWPASS	  0x0008
#define COUNTRYID 0x0010

void clseBayApp::RegisterConfirm(CEBayISAPIExtension *pServer,
 							     CHttpServerContext* pCtxt,
								 char * pEmail,
								 char * pUserId,
								 char * pPass,
								 char * pNewPass,
								 char * pNewPass2,
								 int notify,
								 int countryId)
{
	bool	FirstTimeHasError	= true;
	int		error		= 0;
	int		salt;
	char	cSalt[16];
	char	*pCryptedPassword;
	int		unused;
	char*	pSuggestedUserId;
	int     usersCountry = 0;

	clsUser*	pTempUser;
	char		pRegErrURL[128];
	strstream	ErrorStream;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Registration Confirmation"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";


	// Let's check that we've got a email address.
	if (FIELD_OMITTED(pEmail))
	{
		PrintErrorMsg(FirstTimeHasError, ErrorMsgNoEmail, &ErrorStream );

		error	|=	EMAIL;

	}
	// Let's check that we've got a password.
	if (FIELD_OMITTED(pPass))
	{
		
		char*	pTempMsg = new char[strlen(ErrorMsgNoPass) + 
											strlen(mpMarketPlace->GetHTMLPath()) + 1]; 
											
				sprintf(pTempMsg, ErrorMsgNoPass, 
						mpMarketPlace->GetHTMLPath()); 
					
		PrintErrorMsg(FirstTimeHasError, pTempMsg, &ErrorStream);

		error	|= OLDPASS;
		delete [] pTempMsg;
	}


	// Let's get the user.
	if( !FIELD_OMITTED(pEmail))
	{
		strlwr(pEmail);
		strlwr(pPass);
		mpUser	=	mpUsers->GetUser(pEmail);

		// Let's see how they are
		if (!mpUser)
		{
			PrintErrorMsg(FirstTimeHasError, ErrorMsgBadUser, &ErrorStream);

			error |= EMAIL;

		}
		else
		{
			// We got the user. Let's ensure they're in the right state.
			// If user is confirmed or suspended or ...
			// We do not care any input error any more, so we are out.
			if (mpUser->IsConfirmed())
			{
				*mpStream <<	ErrorMsgAlreadyConfirmed
						  <<	"<br>"
						  <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}

			if (mpUser->IsSuspended())
			{
				*mpStream <<	ErrorMsgSuspended
						  <<	"<p><br>"
						  <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}

			if (mpUser->IsCCVerify())
			{
				*mpStream <<	"<h2>Confirmation through secure server</h2>"
								"Sorry, this account needs to confirm through our secure server. "
								"Please click <a href=\""
						  <<	mpMarketPlace->GetHTMLPath()
						  <<	"services/registration/cc-confirm-by-country.html\">here</a> "
								"to complete your registration. "
						  <<	"<p><br>"
						  <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}

			if (!mpUser->IsUnconfirmed())
			{
			//	*mpStream  <<	ErrorMsgUnknownState

			// kakiyama 07/07/99

				*mpStream  <<   clsIntlResource::GetFResString(-1,
									"<h2>Internal Error</h2>"
									"Sorry, there was a problem confirming your registration. "
									"Please report this to "
									"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
									"Customer Support</a> "
									"if you have any questions about this problem. ",
									clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
									NULL)
						   <<	"<br>"
						   <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}
		
			// Check Password
			if (!mpUser->TestPass(pPass))
			{
				char*	pTempMsg = new char[strlen(ErrorMsgBadUserOrPassword) + 
											strlen(mpMarketPlace->GetHTMLPath()) + 1];
				sprintf(pTempMsg, ErrorMsgBadUserOrPassword, 
						mpMarketPlace->GetHTMLPath()); 
						
				PrintErrorMsg(FirstTimeHasError, pTempMsg, &ErrorStream);

				error	|= OLDPASS;
				delete [] pTempMsg;
			
			}
		}
	}

	// if pUserId is null, we already use the E-mail as the ID in registration page

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	if ( !FIELD_OMITTED(pUserId) )
	{
		// convert to lower case
#ifdef _MSC_VER
		strlwr(pUserId);
#endif

		// 06/01/99 petra we want to display an error msg rather than
		// surreptitiously changing the userId..
		if (clsUtilities::HasSpecialCharacters(pUserId))
		{
			PrintErrorMsg(FirstTimeHasError, ErrorMsgUserIdHaveFunnyCharacters, &ErrorStream);
			error |= USERID;
		}

		else
		{
			//clean up User Id, remove prefix and trailler spaces 
			pUserId = clsUtilities::CleanUpUserId(pUserId);
		}

		//Let's see if the userid is already taken.
		pTempUser = mpUsers->GetUser(pUserId);

		if (pTempUser)
		{
			//in case a user registered before the change and confirmed after 
			//the change. they still can use the User Id chosen in old registration 
			if(strcmp(pEmail, pTempUser->GetEmail()))
			{
				// UserId already to exist and is not the 
				// same email (we know this because we have checked
				// earlier using the email)

				pSuggestedUserId = GetSuggestedUserId(mpUser, pUserId);

				// char*	pTempMsg = new char[strlen(ErrorMsgIdInUse) + strlen(pSuggestedUserId) + 1];

				char*	pTempMsg = new char[strlen(clsIntlResource::GetFResString(-1,
												"<LI><b>Registration conflict!</b></LI>" 
												"<OL>The User ID you have requested is already in use. Please select another User ID. "
												" %s <br>"
												"If you would like advice on selecting a User ID, " 
												"please refer to our <a href=\"%{1:GetHTMLPath}/help/basics/f-userid.html\">User ID FAQ.</a> </OL><br>",
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												NULL))
											+ strlen(pSuggestedUserId) + 1];


				sprintf(pTempMsg, clsIntlResource::GetFResString(-1,
										"<LI><b>Registration conflict!</b></LI>" 
										"<OL>The User ID you have requested is already in use. Please select another User ID. "
										" %s <br>"
										"If you would like advice on selecting a User ID, " 
										"please refer to our <a href=\"%{1:GetHTMLPath}help/basics/f-userid.html\">User ID FAQ.</a> </OL><br>",
										clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
										NULL), pSuggestedUserId );

				PrintErrorMsg(FirstTimeHasError, pTempMsg, &ErrorStream);

				error	|= USERID;

				delete pTempUser;
				delete pSuggestedUserId;
				delete [] pTempMsg;
			}
		}
		
		if(strcmp(pUserId ,pEmail) )
		{
			//
			// pUserId and pEmail are differents
			//
			// Check that if the character "@" is in pUserId
			if(strchr(pUserId,'@'))
			{
				// pUserId has an @ or & and is not the same as email
				PrintErrorMsg(FirstTimeHasError, ErrorMsgUserIdHaveAtSign, &ErrorStream);

				error	|= USERID;
			
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
		FIELD_OMITTED(pNewPass2))
	{
		PrintErrorMsg(FirstTimeHasError, ErrorMsgNoNewPass, &ErrorStream);
		
		error	|= NEWPASS;
	}

	strlwr(pNewPass);
	strlwr(pNewPass2);

	if (strcmp(pNewPass, pNewPass2) != 0)
	{
		PrintErrorMsg(FirstTimeHasError, ErrorMsgNewPassDifferent, &ErrorStream);
	
		error	|= NEWPASS;

	}

	// Make sure the new one isn't the same as the old
	// one
	if (strcmp(pPass, pNewPass) == 0)
	{
		PrintErrorMsg(FirstTimeHasError, ErrorMsgPasswordsTheSame, &ErrorStream);

		error	|= NEWPASS;
	}

	//emit form with error fields shown as red
	//and show all error 
	if (error)
	{
		char*	pTemp = new char[ErrorStream.pcount() + 1];
		strncpy(pTemp, ErrorStream.str(), ErrorStream.pcount());
		pTemp[ErrorStream.pcount()] = 0;

		*mpStream	<< pTemp
					<< "</OL>"; 

		*mpStream	<<	"If you still have any questions, please go to the "
					<<  "<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/basics/n-registering.html\">"
					<<	"New to Registering</a> page.";

//		*mpStream	<<	ErrorStream.str()
//					<<	"'\0'";

		ConfirmError( error, 
						pEmail,
						pPass,
						pUserId,
						pNewPass,
						pNewPass2,
						notify,
						countryId);

		delete [] pTemp;
		

		CleanUp();
		return;
	}

	// Now that we most likely have a user object, check one more thing! 
	// Make sure the user has agreed to the correct user agreement.
	// If not, send the user there immediately! Do not pass go (well --
	// unless we don't know which country they're from!)
	if (mpUser)
	{
		usersCountry = mpUser->GetCountryId();
		if (usersCountry != countryId && 
			countryId != Country_None &&
			usersCountry != Country_None)
		{
			strcpy(pRegErrURL, mpMarketPlace->GetHTMLPath());
			strcat(pRegErrURL, "services/registration/confirm-by-country-err.html");
			pServer->EbayRedirect(pCtxt, pRegErrURL);
			CleanUp();
			return;
		}
	}

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

	ShowWelcomeToEBay();
  
	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	free(pCryptedPassword);

	CleanUp();
	return;
}

void clseBayApp::ConfirmError(int ErrorCode,
								char *pEmail,
								char *pPass,
								char *pUserId,
								char *pNewPass,
								char *pNewPass2,
								int   notify,
								int   countryId)
{

	*mpStream	<<	"<p>The error fields are shown in <font color=\"red\"> <BLINK>red</BLINK></font>.\n";
	*mpStream	<<	"<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageRegisterConfirm)
				<<	"ebayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"RegisterConfirm\">\n";
					
	if (notify)
		*mpStream << "<input type=\"hidden\" name=\"notify\" value=\"1\">\n";
	else
		*mpStream << "<input type=\"hidden\" name=\"notify\" value=\"0\">\n";

	*mpStream << "<input type=\"hidden\" name=\"countryid\" value=\""
		      << countryId
			  << "\">\n";

	*mpStream	<<	"<table width=\"590\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\" width=\"590\">\n"
					"<tr><td width=\"300\" bgcolor=\"#EFEFEF\">\n"
					"<font color="
				<<	CCGetColor(ErrorCode, EMAIL)
				<<	">Your E-mail Address</font></td>\n"
					"<td width=\"290\"><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pEmail))
	{
		*mpStream	<<	" value=\""
				<<	pEmail
				<<	"\"";
	}

	*mpStream	<<	"></td></tr>\n"
				<<	"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, OLDPASS)
				<<	">The confirmation code we assigned to you, "
					"which is in the confirmation instructions</font>"
					"<p><font size=\"2\">Click <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/reqtemppass.html\">"
				<<	"here</a> "
					"if you need eBay to resend your confirmation instructions.</font>"
					"</td>\n"
					"<td width=\"290\"><input type=\"text\" name=\"pass\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pPass))
	{
		*mpStream	<<	" value=\""
				<<	pPass
				<<	"\"";
	}

	*mpStream	<<	"><br></td></tr>\n";

	*mpStream   <<	"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, NEWPASS)
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
				<<	CCGetColor(ErrorCode, NEWPASS)
				<<	">Type your <strong>new</strong> password again</font></td>\n"
					"<td width=\"290\"><input type=\"password\" name=\"newpass2\" size=\"30\" maxlength=\"64\"";
	
	if (!FIELD_OMITTED(pNewPass2))
	{
		*mpStream	<<	" value=\""
				<<	pNewPass2
				<<	"\"";
	}

	*mpStream	<<	"></td></tr></table>\n";
	
	*mpStream   <<  "<p><strong><font size=4 color=\"#800000\">Optional</strong> \n"
		            "<br><table border=1 width=590 cellspacing=0 cellpadding=3> \n"
					"<tr><td width=\"300\" bgcolor=\"#EFEFEF\"><font color="
				<<	CCGetColor(ErrorCode, USERID)
				<<	">Create a <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/myinfo/userid.html\"><strong>User ID</strong></a> nickname:<br>"
					"<font size=2>The User ID that you choose will become "
					"your \"eBay name\" that others see when you participate on eBay "
					"You can create a name or simply use your email address. "
					"<p> Examples \"wunderkid\", \"jsmith98\", \"jeff@aol.com\".</td>\n"
					"<td width=\"290\"><input type=\"text\" name=\"userid\" size=\"30\" maxlength=\"64\"";

	if (!FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	" value=\""
				<<	pUserId
				<<	"\"";
	}
	*mpStream	<<	"><br></td></tr></table>\n";


    *mpStream	<<	"<p><strong>Press </strong>"
					"<input type=\"submit\" value=\"Submit\"> "
					"<strong>to complete your registration.</strong></p>\n"
					"<p>If you are thinking about committing fraud, read "
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/fraud.html\">this</a>.\n</form>"
					"<p>"
				<<	mpMarketPlace->GetSecureFooter();

}

void clseBayApp::PrintErrorMsg(bool& FirstTimeHasError, const char * pMsg, strstream* pErrorStream)
{
	if(FirstTimeHasError)
	{
		*pErrorStream	<< "<H2>Following Errors Were Found: </H2>"
					<< "<OL>";

		FirstTimeHasError = false;
	}

	*pErrorStream	<<  pMsg;
}

//give user 1-3 suggested User Id(s) based on their choice
char* clseBayApp::GetSuggestedUserId(clsUser* pUser, char *pUserId)
{
	// - 07/08/99 soc - bug fix # 229 cosmetic (change UserIds to User IDs)
	static const char *MultiSuggestUserIdsMsg =
	"Here are some suggested User IDs based on your selection: ";

	static const char *SingleSuggestUserIdMsg =
	"Here is a suggested User ID based on your selection: ";

	//maximum 3 user ids plus the space and "," between them
	//now only give one suggeted UserID
	char Suid[EBAY_MAX_USERID_SIZE + 1];

	char* pSuggestedUserIdMsg = new char[250];

//	char* pName; 
	clsUser* pTempUser;

	int i;
	char SuggestedUserId1[EBAY_MAX_USERID_SIZE];
	//SuggestedUserId2[EBAY_MAX_USERID_SIZE], SuggestedUserId3[EBAY_MAX_USERID_SIZE];

	Suid[0]	= '\0';
	*pSuggestedUserIdMsg = '\0';
	
	//if user choose a user id is too long, we won't give any suggestion
	//else a user id appending a number(1-99) will be given 
	if (strlen(pUserId) < (EBAY_MAX_USERID_SIZE - 2) )
	{
		for (i=1; i<100; i++)
		{
			sprintf(SuggestedUserId1, "%s%d", pUserId, i);
		
			if( (pTempUser = mpUsers->GetUser(SuggestedUserId1)) == NULL)
			{
				//do not need check User Id validation because we already
				//checked before, adding a number won't cause problem
				strcpy(Suid, SuggestedUserId1);
				break;
			}
			delete pTempUser;
		}
/* temporary removed, only give one suggested User Id by adding a number
		if (pUser)
		{
			//get user's name from whatever in registration
			pName=pUser->GetName();
			strlwr(pName);

			//give a Suggested UserId by appending the first char 
			//of user's name
			sprintf(SuggestedUserId2, "%s%c", pUserId, pName[0]);
			if( (pTempUser = mpUsers->GetUser(SuggestedUserId2)) == NULL)
			{
				// Check the user id and convert it to lower case
				// but we do not want print out any error msg
				if( !ValidateUserIdChange(pUserId,NULL) )
				{
					if (Suid[0] != '\0')
					{
						strcat(Suid, ", ");
					}
					strcat(Suid, SuggestedUserId2);
				}
			}
			delete pTempUser;

			sprintf(SuggestedUserId3, "%c%s", pName[0], pUserId);
			if( (pTempUser = mpUsers->GetUser(SuggestedUserId3)) == NULL)
			{
				// Check the user id and convert it to lower case
				// but we do not want print out any error msg
				if( !ValidateUserIdChange(pUserId,NULL) )
				{
					if (Suid[0] != '\0')
					{
						strcat(Suid, ", ");
					}
					strcat(Suid, SuggestedUserId3);
				}
			}
			//temporary remove this part 
			//we will give a "Hyphens" rather than underscore later
			else
			{
				delete pTempUser;

				sprintf(SuggestedUserId3, "%c-%s", pName[0], pUserId);
				if( (pTempUser = mpUsers->GetUser(SuggestedUserId3)) == NULL)
				{
					// Check the user id and convert it to lower case
					// but we do not want print out any error msg
					if( !ValidateUserIdChange(pUserId,NULL) )
					{
						if (Suid[0] != '\0')
						{
							strcat(Suid, ", ");
						}
						strcat(Suid, SuggestedUserId3);
					}
				} 
				delete pTempUser;
			} 
		} 
*/
		if(strchr(Suid,','))
			sprintf(pSuggestedUserIdMsg, "%s<b>\"%s\"</b>.", MultiSuggestUserIdsMsg, Suid);
		else
			sprintf(pSuggestedUserIdMsg, "%s<b>\"%s\"</b>.", SingleSuggestUserIdMsg, Suid);

	}
		
	return pSuggestedUserIdMsg;	
}

void clseBayApp::ShowWelcomeToEBay()
{
	
	// Updating welcome message to highlight new-to section
	*mpStream <<	"<BLOCKQUOTE><H2>Welcome to "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"!  You are now registered!</H2>\n"
					"<P>Thank you for completing your "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" registration.  Your registration is confirmed and is effective "
					"immediately.  Please discard your temporary access code - it is no "
					"longer necessary and you can start using your own personal password now.\n"
					"<P>We're glad you're here!  "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" is the world's largest <b>personal online trading community</b>.  "
					"Individuals use eBay to buy and sell items in more than 1000 "
					"categories.  From antiques to collectibles to computers, you'll find "
					"whatever you're looking for here at "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"!  You'll also have a lot of fun doing it - because everything you "
					"buy here is sold in an auction format!\n"
					"<P>A few tips about trading at "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	":\n"
					"<H4>Bid smart, bid safe!</H4>\n"
					"<UL><LI>Check your seller's feedback rating.  Next to every User "
	// bug fix 2851, Soc 7/7/99 -take out skippy reference
	// WARNING: IF YOU UNCOMMENT THE SKIPPY CODE BELOW REMEMBER TO DELETE THE OBJECTS THAT WERE "NEWED"
						"ID is a number in parentheses";
	//				"ID is a number in parentheses, for example,  ";
	//char *pUserid = "skippy";
	/* took out for bug fix 2851
	clsUser*	pSkippyUser;
	pSkippyUser = mpUsers->GetUser(pUserid, true);
	if (pSkippyUser != NULL)
	{
		clsFeedback *pSkippyFeedback = pSkippyUser->GetFeedback();
		int feedbackScore = pSkippyFeedback->GetScore();
		clsUserIdWidget *pUserIdWidget; 
		pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
		pUserIdWidget->SetUserInfo(pSkippyUser->GetUserId(),
			 pSkippyUser->GetEmail(),
			 UserStateEnum(pSkippyUser->GetUserState()),
			 pSkippyUser->UserIdRecentlyChanged(),
			 feedbackScore,
			 pSkippyUser->GetUserFlags());
		pUserIdWidget->SetUserIdBold(false);
		pUserIdWidget->SetShowUserStatus(true);
		pUserIdWidget->SetShowMask(true);
		pUserIdWidget->SetShowFeedback(true);
		pUserIdWidget->SetShowStar(true);
		pUserIdWidget->SetShowAboutMe(true);
		pUserIdWidget->EmitHTML(mpStream);
	}
	else
		*mpStream << "skippy(1)";
	*/

	*mpStream <<	". The number represents the user's Feedback "
					"Rating, a summary of all the feedback comments others have left "
					"about this user.  If you click on the number, you can read what "
					"others have said about that particular user.\n"
					"<LI>Get to know your trading partner!  If you have questions about "
					"the item or service in an auction listing, email the seller!  The "
					"seller will appreciate your interest in their auction.\n"
					"<LI>Ask questions!  You can get help by clicking on Help located at "
					"the top of every "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" page.  You can also click on News/Chat to locate a Chat Board where "
					"you can chat with other users.</UL>\n"
					"<H4>Sell wise, sell well!</H4>\n"
					"<UL><LI>Describe your item or service as fully as possible.  Try to "
					"anticipate questions people may have, and include the relevant "
					"information in your description.\n"
					"<LI>A picture is worth a thousand words. Add a photo so that buyers "
					"can see your item in more detail!\n"
					"<LI>Provide your terms for sale.  Include your payment method and "
					"shipping terms.</UL>\n"
					"<H4>Above all else, enjoy yourself on "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"! Our members think this is a great place to be, and we hope you do too.</H4>\n"
					"</BLOCKQUOTE>\n"
					"<DIV ALIGN=\"CENTER\"><P>Click on a button below for more information!\n"
					"<TABLE WIDTH=\"580\" BORDER=\"0\" CELLSPACING=\"20\" CELLPADDING=\"0\">\n"
					"<TR><TD WIDTH=\"33%\" ALIGN=\"CENTER\">\n"
					"<A HREF=\""
    		  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/basics/n-index.html\"><IMG SRC=\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"new-to-ebay.gif\" WIDTH=\"90\" HEIGHT=\"28\" BORDER=\"0\"></A></TD>\n"
					"<TD WIDTH=\"33%\" ALIGN=\"CENTER\">\n"
					"<A HREF=\""
    		  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/basics/n-bidding.html\"><IMG SRC=\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"new-to-bidding.gif\" WIDTH=\"90\" HEIGHT=\"28\" BORDER=\"0\"></A></TD>\n"
					"<TD WIDTH=\"33%\" ALIGN=\"CENTER\">\n"
					"<A HREF=\""
    		  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/basics/n-selling.html\"><IMG SRC=\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"new-to-selling.gif\" WIDTH=\"90\" HEIGHT=\"28\" BORDER=\"0\"></A></TD>\n"
					"</TR></TABLE></DIV><p>";
}
