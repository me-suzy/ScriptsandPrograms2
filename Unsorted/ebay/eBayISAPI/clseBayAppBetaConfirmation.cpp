/*	$Id: clseBayAppBetaConfirmation.cpp,v 1.9.128.3 1999/08/05 20:42:10 nsacco Exp $	*/
//
//	File:	clseBayAppBetaConfirmation.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Update a user's registration information. 
//		
//		** NOTE **
//		Uses ValidateRegistrationInfo in clseBayAppRegister.cpp
//		** NOTE **
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. If you did not receive this "
"mail, or lost it, please return to the registration page and "
"re-register (with the same user ID and e-mail address) to have it sent to "
"you again."
"<br>";

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. "
"Please contact <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow\">Customer Support</a> if you have any questions.";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this error, along with all pertinent information (your selected "
"userid, e-mail, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail is omitted or invalid</h2>"
"Sorry, your e-mail is required to proceed. "
"If you feel this is an error, "
"please report it to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

void clseBayApp::BetaConfirmation(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pEmail,
									char * pPass,
									char * pName,
									char * pCompany,
									char * pAddress,
									char * pCity,
									char * pState,
									char * pZip,
									char * pCountry,
									char * pDayPhone,
									char * pNightPhone,
									char * pFaxPhone,
									char * pGender)
{
	bool	error		= false;
	time_t	nowTime;

	bool	credit_card_on_file		= false;
	bool	good_credit				= false;

	int UVrating;
	int UVdetail;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Beta Registration Confirmation"
					"</TITLE></head>"
			  <<	"\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";


	// Let's get the user.
	clsUtilities::StringLower(pUserId);
	mpUser = mpUsers->GetUser(pUserId);
	if (!mpUser)
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" userid, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Let's check the password
	clsUtilities::StringLower(pPass);
	if (!mpUser->TestPass(pPass))
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" userid, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Let's check the E-mail address
	if(FIELD_OMITTED(pEmail))
	{
	//	*mpStream	<<	ErrorMsgOmittedEmail

	// kakiyama 07/07/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>The e-mail is omitted or invalid</h2>"
							"Sorry, your e-mail is required to proceed. "
							"If you feel this is an error, "
							"please report it to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							mpMarketPlace->GetCGIPath(PageSendQueryEmailShow),
							NULL)
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if(!ValidateEmail(pEmail))
	{
	//	*mpStream	<<	ErrorMsgOmittedEmail

	// kakiyama 07/07/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>The e-mail is omitted or invalid</h2>"
							"Sorry, your e-mail is required to proceed. "
							"If you feel this is an error, "
							"please report it to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							mpMarketPlace->GetCGIPath(PageSendQueryEmailShow),
							NULL)
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (mpUser->IsConfirmed())
	{
		*mpStream <<	"<h2>eBay Beta Registration already confirmed!</h2>"
						"Our records indicate that you have already confirmed "
						"your eBay Beta registration, and there is no need to "
						"do so again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<h2>eBay Beta Registration blocked</h2>"
						"Our records indicate that your current eBay registration "
						"is blocked. Please resolve any outstanding issues and "
						"try again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->GetUserState() != UserGhost)
	{
		*mpStream  <<	"<h2>eBay Beta Registration unknown state</h2>"
						"Your userid is in unknown state, and we are unable "
						"to confirm your Beta registration. Please contact "
				  <<	"<A HREF="
				  <<	"\""
				  <<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				  <<	"eBayISAPI.dll?SendQueryEmailShow&subject=registering"
				  <<	"\">"
				  <<	"Customer Support"
				  <<	"</A> "
						"along with all pertinent information "
						"(your selected userid, e-mail, name, address, etc.)."
						"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Ok, user appears to be legit. Let's see if the rest
	// of the information if valid
	error =	ValidateNonRequiredRegistrationInfo(
									 pCompany,
									 pNightPhone,
									 NULL,
									 NULL,
									 NULL,
									 pFaxPhone,
									 NULL,
									 NULL,
									 NULL,
									 pGender,
									 NULL);

	// If we got an error, let's bail
	if (error)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Check required info again in case someone tried to hack the preview page
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, false, pEmail, pName, pAddress, pCity, pState, pZip, pCountry, 
		0, // PH 05/04/99 0 for countryId coz we don't get the id here.. have to check later
		pDayPhone, NULL, NULL, NULL))
	{
		*mpStream <<	"<p>Sorry, invalid data<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Now, let's touch everything. We don't know what's changed,
	// so, we'll change  it all!

	// This is unfortunate. For now, we need to set the creation
	// time for the account to now. We should find a way to batch
	// update this later.
	nowTime	= time(0);
	mpUser->SetCreated(nowTime);

	mpUser->SetEmail(pEmail);
	mpUser->SetName(pName);

	if (FIELD_OMITTED(pCompany))
		mpUser->SetCompany("");
	else
		mpUser->SetCompany(pCompany);

	mpUser->SetAddress(pAddress);
	mpUser->SetCity(pCity);

	mpUser->SetState(pState);

	mpUser->SetZip(pZip);
// petra	mpUser->SetCountry(pCountry);

	if (FIELD_OMITTED(pDayPhone))
		mpUser->SetDayPhone(NULL);
	else
		mpUser->SetDayPhone(pDayPhone);

	if (FIELD_OMITTED(pNightPhone))
		mpUser->SetNightPhone(NULL);
	else
		mpUser->SetNightPhone(pNightPhone);

	if (FIELD_OMITTED(pFaxPhone))
		mpUser->SetFaxPhone(NULL);
	else
		mpUser->SetFaxPhone(pFaxPhone);

	mpUser->SetGender(pGender);

	// UV stuff
	mpUser->SetUVRating(UVrating);
	mpUser->SetUVDetail(UVdetail);

	mpUser->SetHost("unknown");

	mpUser->SetConfirmed();

	// Get their old AW credit status
	mpDatabase->GetAWCreditStatus(mpUser->GetUserId(),
								  &credit_card_on_file,
								  &good_credit);


	mpUser->SetHasCreditCardOnFile(credit_card_on_file);
	mpUser->SetHasGoodCredit(good_credit);

	// Now, we can't do an "updateuser" because, down deep inside, 
	// it assumes that the ebay_user_info record exists. So, we 
	// do the nasty and call the db directly.
	gApp->GetDatabase()->AddUserInfo(mpUser);

	// Now, update them
	mpUser->UpdateUser();

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>eBay Beta Registration confirmation complete!</h2>"
					"Thank you for taking the time to confirm your registration "
					"information."
					"<p>"
					"You registered userid is now enabled on the eBay "
					"system and you're free to explore and use it just as you "
					"would the original eBay AuctionWeb."
					"<p>"
					"If you'd like to review your registration information, you "
					"can go to our ";

	*mpStream	<<	"<A HREF="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageChangeRegistrationShow)
				<<	"ebayISAPI.dll?ChangeRegistrationShow"
				<<	"&userid="
				<<	pUserId
				<<	"&pass="
				<<	pPass
				<<	"\""
				<<	">"
				<<	"change registration information"
				<<	"</A>";

	*mpStream	<<	" page."
				<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

void clseBayApp::BetaConfirmationPreview(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pEmail,
									char * pPass,
									char * pName,
									char * pCompany,
									char * pAddress,
									char * pCity,
									char * pState,
									char * pZip,
									char * pCountry,
									char * pDayPhone,
									char * pNightPhone,
									char * pFaxPhone,
									char * pGender)
{
	bool	error		= false;

	bool	credit_card_on_file		= false;
	bool	good_credit				= false;

	int UVrating;
	int UVdetail;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Beta Registration Preview"
					"</TITLE></head>"
			  <<	"\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";


	// Let's get the user.
	clsUtilities::StringLower(pUserId);
	mpUser = mpUsers->GetUser(pUserId);
	if (!mpUser)
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" userid, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Let's check the password
	clsUtilities::StringLower(pPass);
	if (!mpUser->TestPass(pPass))
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" userid, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Let's check the E-mail address
	if(FIELD_OMITTED(pEmail))
	{
	//	*mpStream	<<	ErrorMsgOmittedEmail

	// kakiyama 07/07/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>The e-mail is omitted or invalid</h2>"
							"Sorry, your e-mail is required to proceed. "
							"If you feel this is an error, "
							"please report it to "
							"<a href=\"%{1:GetHTMLPath}/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							mpMarketPlace->GetHTMLPath(),
							NULL)
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if(!ValidateEmail(pEmail))
	{
	//	*mpStream	<<	ErrorMsgOmittedEmail

	// kakiyama 07/07/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>The e-mail is omitted or invalid</h2>"
							"Sorry, your e-mail is required to proceed. "
							"If you feel this is an error, "
							"please report it to "
							"<a href=\"%{1:GetHTMLPath}/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							mpMarketPlace->GetHTMLPath(),
							NULL)
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (mpUser->IsConfirmed())
	{
		*mpStream <<	"<h2>eBay Beta Registration already confirmed!</h2>"
						"Our records indicate that you have already confirmed "
						"your eBay Beta registration, and there is no need to "
						"do so again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<h2>eBay Beta Registration blocked</h2>"
						"Our records indicate that your current eBay registration "
						"is blocked. Please resolve any outstanding issues and "
						"try again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->GetUserState() != UserGhost)
	{
		*mpStream  <<	"<h2>eBay Beta Registration unknown state</h2>"
						"Your userid is in unknown state, and we are unable "
						"to confirm your Beta registration. Please contact "
				  <<	"<A HREF="
				  <<	"\""
				  <<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				  <<	"eBayISAPI.dll?SendQueryEmailShow&subject=registering"
				  <<	"\">"
				  <<	"Customer Support"
				  <<	"</A> "
						"along with all pertinent information "
						"(your selected userid, e-mail, name, address, etc.)."
				   <<	"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Ok, user appears to be legit. Let's see if the rest
	// of the information if valid
	error =	ValidateNonRequiredRegistrationInfo(
									 pCompany,
									 pNightPhone,
									 NULL,
									 NULL,
									 NULL,
									 pFaxPhone,
									 NULL,
									 NULL,
									 NULL,
									 pGender,
									 NULL);

	// If we got an error, let's bail
	if (error)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// let users review their required info 
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, true, pEmail, pName, pAddress, pCity, pState, pZip, pCountry, 
		0, // PH 05/04/99 0 for countryId coz we don't get it here - have to check that later
		pDayPhone, NULL, NULL, NULL))
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();		
		CleanUp();
		return;
	}

	// output hidden fields for BetaConfirmation
	*mpStream << "<br><form method=\"POST\" action=\""
			 << mpMarketPlace->GetCGIPath(PageBetaConfirmation)
			 <<	"eBayISAPI.dll?\">\n"
				"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"BetaConfirmation\""
			 << "\">\n<input TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
			 << pUserId
			 << "\">\n<input TYPE=\"hidden\" NAME=\"pass\" VALUE=\""
			 << pPass
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
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone\" VALUE=\""
			 << pDayPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone\" VALUE=\""
			 << pNightPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone\" VALUE=\""
			 << pFaxPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"gender\" VALUE=\""
			 << pGender
			 << "\">\n"
				"<B>Click the Back button on your browser if you want to change any of the listed information.</B> <p>"
				"<b> Click "
				"<input type=\"submit\" value=\"submit\"> "
				"to commit your changes. </b>\n"
				"</form>\n";

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

