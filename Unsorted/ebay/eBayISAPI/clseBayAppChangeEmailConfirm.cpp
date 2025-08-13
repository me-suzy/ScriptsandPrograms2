/*	$Id: clseBayAppChangeEmailConfirm.cpp,v 1.9.22.3.74.2 1999/08/05 18:58:52 nsacco Exp $	*/
//
//	File:	clseBayAppChangeEmailConfirm.cpp
//
//	Class:	clseBayApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Handle a change email confirmation request
//
// Modifications:
//				- 02/06/97 tini	- Created
//				- 02/17/99 wen  - Inform PSSearches for email changed
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
//
// 

#include "ebihdr.h"
#include "clsPSSearches.h"
//
// Support for our own personal crypt
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

static const char *ChangeEmailTextPart_2 = 
"<h2>Change of E-mail Address Confirmation </h2>"
"Once you submit this form, your Change of E-mail Address "
"will be complete."
"<p>"
"Please refer to the e-mail message you received confirming "
"your Change of E-mail Address Request. For your protection, "
"the e-mail contains an important Confirmation Code that "
"you must submit here to complete your Change of E-mail Address."
"</P>";

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgEmailUsed =
"<h2>E-mail Already taken.</h2>"
"Sorry, your new e-mail is already the e-mail of a registered user. "
"If the other account was yours and you would like to merge them, "
"please go to our <a href=\"http://pages.ebay.com/help/myinfo/index.html\">My Information</a> page "
"for more information.";
*/

static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"<br>"
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same User ID and e-mail address) to have it sent to "
"you again.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry. There was a problem confirming your Change of E-mail Request. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

static void msgBadUserOrPassword(clsMarketPlace *pMarketPlace, ostream *pStream)
{
	*pStream << 
		"<h2>Invalid e-mail address or password</h2>"
		"Sorry. The e-mail address and/or the special confirmation code "
		"does not match the one sent to you in the confirmation "
		"notice. Please go back and check it again."
		"<br>"
		"If you have lost your confirmation e-mail, please return to "
		"the <a href=\""
		<< pMarketPlace->GetCGIPath(PageChangeEmail)
		<< "eBayISAPI.dll?ChangeEmail\">Change of E-mail Request</a> " 
		"page and submit a new request to receive another confirmation code. "
		"<br>"
		<< pMarketPlace->GetFooter();
}
		

void clseBayApp::ChangeEmailConfirm(CEBayISAPIExtension *pServer)
{
	char						*pBlock;


	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<    mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change E-mail"
					"</TITLE>"
					"</HEAD>";

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";


	// We're going to need storage for the block o'text at the top
	// nsacco 07/02/99 removed mpMarketPlace->GetName()
	pBlock	 = new char[strlen(ChangeEmailTextPart_2) + 1];

	sprintf(pBlock, ChangeEmailTextPart_2);

	*mpStream <<	pBlock;

	delete pBlock;
	
	// Now, the rest of the goop
	*mpStream <<	"<h3>Please complete the following:</h3>"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageChangeEmailConfirmShow)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ChangeEmailConfirmShow\">"
					"\n"
					"<p>";

	*mpStream <<	"<pre>Your "
			   <<	mpMarketPlace->GetLoginPrompt()
			   <<	":	         "
					"<input type=text name=userid "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " "
					">"
					"\n"
					"\n"
					"Your new e-mail address: "
					"<input type=text name=newmail "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " "
					">"
					"\n"
					"\n"
					"Your confirmation code"
					":  "
					"<input type=password name=pass "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PASSWORD_SIZE << " "
					">"
					"\n";

	// And now, for the closing
	*mpStream <<	"</pre>\n"
					"<strong>Press this button to submit your change of e-mail address confirmation:</strong>"
					"<p>"
					"<blockquote><input type=submit value=\"submit\"></blockquote>"
					"<p>"
					"\n"
					"Press this button to clear the form if you made a mistake:"
					"<p>"
					"<blockquote><input type=reset value=\"clear form\"></blockquote>"
					"\n"
					"</form>";


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}


void clseBayApp::ChangeEmailConfirmShow(CEBayISAPIExtension *pServer,
							 char * pUserId,
							 char * pNewEmail,
							 char * pPass)
{
	bool		error = false;
	clsUser*	pNewUser;
	clsPSSearches*	pPSSearches;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change E-mail Confirmation"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Let's check that we've got a password/code.
	if (FIELD_OMITTED(pPass))
	{
		*mpStream <<
			"<h2>Confirmation Code not entered</h2>"
			"Sorry. To confirm your Change of E-mail, you must enter "
			"the special confirmation code you received "
			"in the confirmation e-mail. Please go back and try again."
			"<br>"
			"If you have lost your confirmation e-mail, please return to "
			"the <a href=\""
			<< mpMarketPlace->GetCGIPath(PageChangeEmail)
			<< "eBayISAPI.dll?ChangeEmail\">Change of E-mail Request</a> " 
			"page and submit a new request to receive another confirmation code. "
			<<	"<br>"
			<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	pNewUser	=	mpUsers->GetUserByEmail(pNewEmail);
	if (pNewUser && stricmp(pNewUser->GetUserId(), pUserId) != 0)
	{
		// UserId already seems to exist. Cannot rename.

	//	*mpStream <<	ErrorMsgEmailUsed

	// kakiyama 07/07/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>E-mail Already taken.</h2>"
							"Sorry, your new e-mail is already the e-mail of a registered user. "
							"If the other account was yours and you would like to merge them, "
							"please go to our <a href=\"%{1:GetHTMLPath}help/myinfo/index.html\">My Information</a> page "
							"for more information.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();
		return;
	}
	delete pNewUser;

	// Let's get the user. 
	strlwr(pUserId);
	strlwr(pPass);
	mpUser = mpUsers->GetUser(pUserId);

	// Let's see how they are
	if (!mpUser)
	{
		msgBadUserOrPassword(mpMarketPlace, mpStream);
		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream  << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry. There was a problem confirming your Change of E-mail Request. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// check if record exists in ebay_rename_pending
	if (!mpUser->IsRenamePending(pNewEmail))
	{
		*mpStream << "<h2>No Request found</h2>"
				"Sorry. There is no record of your Change of E-mail Request. Please go to "
				"the <a href=\""
			<<  mpMarketPlace->GetCGIPath(PageChangeEmail)
			<< "eBayISAPI.dll?ChangeEmail\">Change of E-mail Request</a> "
				"page to change your e-mail.<br>"
			<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Check Password
	if (!mpUser->TestPendingPass(pNewEmail, pPass))
	{
		msgBadUserOrPassword(mpMarketPlace, mpStream);
		CleanUp();
		return;
	}

	// Tell PSSearches that the user has change email if the user uses personal shopper
	if (mpUser->GetOneUserFlag(UserFlagPersonalShopper))
	{
		pPSSearches = GetPSSearches();
		pPSSearches->ChangeEmailPassword(mpUser->GetEmail(), 
									mpUser->GetPassword(), 
									pNewEmail, 
									NULL);
	}

	//
	// Everything is A-Ok. Let's change email
	//
	mpUser->ChangeEmail(pNewEmail);

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Change of E-mail is now complete!</h2>"
					"Thank you for completing your change of e-mail. "
					"Your change of e-mail has now been confirmed, and is "
					"effective immediately! You may discard your special "
					"confirmation code because it is no longer necessary."
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

