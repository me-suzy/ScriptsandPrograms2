/*	$Id: clseBayAppAdminChangeEmail.cpp,v 1.8.168.1 1999/08/01 02:51:42 barry Exp $	*/
//
//	File:		clseBayAppAdminChangeEmail.cpp
//
//	Class:		clseBayApp
//
//	Author:		Tini Widjojo (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/18/97 tini	- Created
//				- 02/05/98 wen	- Added admin change emails
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsAliasHistoryWidget.h"
#include "clsPSSearches.h"

static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, the user has not yet confirmed his/her registration.";

static const char *ErrorMsgEmailUsed =
"<h2>E-mail Already Taken.</h2>"
"Sorry, the new e-mail address is already the e-mail address of a registered user. ";

static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail address is omitted or invalid</h2>"
"Sorry, the e-mail address is omitted or invalid. ";

// inform user not supporting hotmail
static const char *ErrorMsgUnauthenticatedService =
"<h2>Unauthenticated Mail Service</h2>"
"We\'re sorry, due to potential lack of "
"authentication, we are no longer accepting "
"registrations from hotmail.com, mailcity.com, and "
"usa.net for "
"registered users. Please ask the user to register using a "
"different e-mail address.";

static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send the user confirmation "
"notice via electronic mail. This is probably because the user's e-mail "
"address is invalid. Please go back and check it again. ";

void clseBayApp::AdminCombineUsers(CEBayISAPIExtension *pServer,
							 char *pOldUserId,
							 char *pOldPass,
							 char *pNewUserId,
							 char *pNewPass,
							 eBayISAPIAuthEnum authLevel)
{

	clsUser *pNewUser;

	// Setup
	SetUp();

	// Title
	*mpStream <<	
					"<HTML>"
					"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Combine of Users for "
			  <<	pOldUserId
			  <<	" to "
			  <<	pNewUserId
			  <<	"</title>"
					"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pOldUserId, mpStream);

	if (mpUser)
		pNewUser = mpUsers->GetAndCheckUser(pNewUserId, mpStream);

	if (!mpUser || !pNewUser)
	{
		delete pNewUser;
		CleanUp();

		return;
	}

	if (mpUser->IsUnconfirmed() || pNewUser->IsUnconfirmed())
	{
		*mpStream <<	"<h2> User "
				  <<	pOldUserId
				  <<	" is not confirmed</h2>"
				  <<	"This user has not confirmed their registration, and "
						"no action was taken."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();

		return;
	}

	if (mpUser->IsSuspended() || pNewUser->IsSuspended())
	{
		*mpStream <<	"<h2> User "
				  <<	pOldUserId
				  <<	" is not confirmed</h2>"
				  <<	"This user has not confirmed their registration, and "
						"no action was taken."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();

		return;
	}

	// emit verify page
	*mpStream <<	"<p>Warning: this process is not undoable!"
					"<p>Please verify your entry as it appears below. If there are any \n"
					"errors, please use the back button on your browser to go back and \n"
					"correct your entry. Once you are satisfied with the entry, please \n"
					"press the submit button.\n";

	*mpStream <<	"<p>"
					"Old userid:           <strong>"
			  <<	"<blockquote>"
			  <<	pOldUserId
			  <<	"</blockquote>"
			  <<	"</strong>\n"
					"New userid:           <strong>"
			  <<	"<blockquote>"
			  <<	pNewUserId
			  <<	"</blockquote>"
			  <<	"</strong>\n";
	
	*mpStream <<	"<p><form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminCombineUserConf)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AdminCombineUserConf\">"
					"\n"
					"<input type=hidden name=oldid value=\""
			  <<	pOldUserId
			  <<	"\">\n"
					"<input type=hidden name=oldpass value=\""
			  <<	pNewPass
			  <<	"\">\n"
					"<input type=hidden name=newid value=\""
			  <<	pNewUserId
			  <<	"\">\n"
					"<input type=hidden name=newpass value=\""
			  <<	pOldPass
			  <<	"\">\n";
					
	*mpStream <<	"<p>\n"
					"Click this button to submit your changes. <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"\">Click here if you wish to cancel.</a><br>\n"
			  <<	"<input type=submit value=\"Submit\"></blockquote><p>";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;


	CleanUp();

	delete pNewUser;

	return;

}

void clseBayApp::AdminCombineUserConf(CEBayISAPIExtension *pServer,
							 char *pOldUserId,
							 char *pOldPass,
							 char *pNewUserId,
							 char *pNewPass,
							 eBayISAPIAuthEnum authLevel)
{

	clsUser *pNewUser;

	// Setup
	SetUp();

	_strlwr(pOldUserId);
	_strlwr(pNewUserId);

	// Title
	*mpStream <<	"<HTML>"
					"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Combining of Users for "
			  <<	pOldUserId
			  <<	" to "
			  <<	pNewUserId
			  <<	"</title>"
					"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pOldUserId, mpStream);
	pNewUser = mpUsers->GetAndCheckUser(pNewUserId, mpStream);

	if (!mpUser || !pNewUser)
	{
		*mpStream <<	"<p>"
					<<  "Not valid user(s)."
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();

		return;
	}

	if (mpUser->IsUnconfirmed() || pNewUser->IsUnconfirmed())
	{
		*mpStream <<	"<h2> User "
				  <<	pOldUserId
				  <<	" is not confirmed</h2>"
				  <<	"This user has not confirmed their registration, and "
						"no action was taken."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();

		return;
	}

	// Rename them!
	mpUsers->RenameUser(mpUser, pNewUser, pOldUserId, pNewUserId);


	// Tell them it worked!
	*mpStream <<	"<h2>User "
			  <<	pOldUserId
			  <<	" combined to "
			  <<	pNewUserId
			  <<	"! </h2>"
			  <<	"<p>"
			  <<	"The two user accounts have been combined. "
			  <<	"Please verify that the user information is correctly combined."
			  <<	"<br>\n"
			  <<	mpMarketPlace->GetFooter();

	delete pNewUser;
	CleanUp();

	return;
}

// Change email
void clseBayApp::AdminChangeEmail(CEBayISAPIExtension *pServer, char* pUserId)
{
	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Admin Change E-mail"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	// And a heading for it all
	*mpStream <<	"<h2>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Admin Change E-mail"
			  <<	"</h2>";

	
	// Now, the rest of the goop
	*mpStream <<	"<h3>Please complete the following:</h3>"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminChangeEmailShow)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AdminChangeEmailShow\">"
					"\n"
					"<p>";

	*mpStream <<	"<pre>"
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	":                  "
					"<input type=text name=userid "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " ";

	if (pUserId && stricmp(pUserId, "default") != 0)
	{
		*mpStream <<	"value="
				  <<	pUserId;
	}

	*mpStream <<	">"
					"\n"
					"\n"
					"User's new e-mail address: "
					"<input type=text name=email "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " "
					">"
					"\n"
					"\n";
					
	// And now, for the closing
	*mpStream <<	"</pre><br>\n"
					"<strong>Press this button to submit your change of address request:</strong>"
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

// Admin change email show
void clseBayApp::AdminChangeEmailShow(CEBayISAPIExtension *pServer,
							char *pUserId, char *pNewEmail)
{
	bool	error		= false;

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
			  <<	" Admin Change eMail"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// get and check user and password
	mpUser	= mpUsers->GetUser(pUserId); 
	if (!mpUser)
	{
		*mpStream <<	"Can't find user: "
				  <<	pUserId
				  <<	".<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
		*mpStream <<	ErrorMsgNotConfirmed
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Remove the space in pNewEmail and convert it to lower case
	if( !ValidateEmail(pNewEmail) )
	{
		*mpStream	<<	ErrorMsgOmittedEmail
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
/*
	// Check if it is from hotmail.com , mailcity.com or usa.net
	if (strstr(pNewEmail, "@hotmail.com")  != NULL ||
		strstr(pNewEmail, "@mailcity.com") != NULL ||
		strstr(pNewEmail, "@usa.net")      != NULL) 
	{
		// inform user not supporting hotmail
		*mpStream <<	ErrorMsgUnauthenticatedService
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}
*/

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	pNewUser	=	mpUsers->GetUserByEmail(pNewEmail);
	if (pNewUser && stricmp(pNewUser->GetUserId(), pUserId) != 0)
	{
		// provide information regarding the user and
		// let the admin to dertermine wheter to commit
		// change
		AdminShowUserHistory(pUserId, pNewEmail, pNewUser);

		*mpStream	<< "<p>"
					<<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();
		return;
	}
	delete pNewUser;

	// And mail the confirmation of email change. 
	if (MailUserChangeEmailConfirmatiom(mpUser, pNewEmail) == 0)
	{
		*mpStream <<	ErrorMsgMail
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Tell Netmind that the user has change email if the user uses personal shopper
	if (mpUser->GetOneUserFlag(UserFlagPersonalShopper))
	{
		pPSSearches = GetPSSearches();
		pPSSearches->ChangeEmailPassword(mpUser->GetEmail(), 
									mpUser->GetPassword(), 
									pNewEmail, 
									NULL);
	}

	mpUser->ChangeEmail(pNewEmail);

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Change of E-mail is now complete!</h2>"
			  <<	"The e-mail of the user \'"
			  <<	pUserId
			  <<	"\' has been changed to \'"
			  <<	pNewEmail
			  <<	"\', and is "
					"effective immediately! "
					"A confirmation e-mail has been sent to "
					"the user."
					"<p>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}

// Show user email history and let the admin confirm the change
void clseBayApp::AdminShowUserHistory(char* pUserId, char* pNewEmail, clsUser* pNewUser)
{
	clsAliasHistoryWidget*	pAliasHistoryWidet;

	*mpStream	<<	"<h2>E-mail is already taken by "
				<<	pNewUser->GetUserId()
				<<	"</h2>"
				<<	"User \'"
				<<	pUserId
				<<	"\' would like to change to e-mail address \'"
				<<	pNewEmail
				<<	"\' which is already taken by \'"
				<<	pNewUser->GetUserId()
				<<	"\'. "
				<<	"Please check the user history and then "
				<<	"determine whether the user \'"
				<<	pUserId
				<<	"\' should be allowed to change to the e-mail \'"
				<<	pNewEmail
				<<	"\'.<p>";

	// display user alias history
	pAliasHistoryWidet = new clsAliasHistoryWidget(mpMarketPlace, this, UserIdAlias);
	pAliasHistoryWidet->SetUser(pNewUser);
	pAliasHistoryWidet->EmitHTML(mpStream);
	delete pAliasHistoryWidet;

	*mpStream	<< "<p>";

	pAliasHistoryWidet = new clsAliasHistoryWidget(mpMarketPlace, this, EMailAlias);
	pAliasHistoryWidet->SetUser(pNewUser);
	pAliasHistoryWidet->EmitHTML(mpStream);
	delete pAliasHistoryWidet;

	*mpStream	<< "</p>";

	// display a form let the admin confirm
	*mpStream	<< 	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminChangeEmailConfirm)
			  <<	"eBayISAPI.dll"
					"\">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AdminChangeEmailConfirm\">\n"
					"<INPUT TYPE=HIDDEN NAME=\"userid\" VALUE=\""
			  <<	pUserId
			  <<	"\">\n"
			  <<	"<INPUT TYPE=HIDDEN NAME=\"email\" VALUE=\""
			  <<	pNewEmail
			  <<	"\">\n"
			  <<	"Should the user \'"
			  <<	pUserId
			  <<	"\' be allowed to change to the e-mail \'"
			  <<	pNewEmail
			  <<	"\'?<br>\n"
			  <<	"<INPUT TYPE=RADIO NAME=\"change\" VALUE=1>Yes\n"
			  <<	"<INPUT TYPE=RADIO NAME=\"change\" VALUE=0 checked>No\n"
			  <<	"<br><INPUT TYPE=submit VALUE=\"submit\">\n"
			  <<	"</form>";

	return;
}
	
void clseBayApp::AdminChangeEmailConfirm(CEBayISAPIExtension* pServer, 
										 char* pUserId, 
										 char* pNewEmail, 
										 int Change)
{
	clsPSSearches*	pPSSearches;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Admin Change E-mail"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	if (Change == 0)
	{
		*mpStream	<<	"<h2>E-mail is not changed</h2>"
					<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// get and check user and password
	mpUser	= mpUsers->GetUser(pUserId); 

	// And mail the confirmation of email change. 
	if (MailUserChangeEmailConfirmatiom(mpUser, pNewEmail) == 0)
	{
		*mpStream <<	ErrorMsgMail
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

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

	mpUser->ChangeEmail(pNewEmail);

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Change of E-mail is now complete!</h2>"
			  <<	"The e-mail of the user \'"
			  <<	pUserId
			  <<	"\' has been changed to \'"
			  <<	pNewEmail
			  <<	"\', and is "
					"effective immediately! "
					"A confirmation e-mail has been sent to "
					"the user."
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

int clseBayApp::MailUserChangeEmailConfirmatiom(clsUser *pUser, char* pNewEmail)
{
	clsMail		*pMail;
	ostream		*pMStream;
	char		subject[256];
	int			mailRc;
	clsAnnouncement			*pAnnouncement;
	char*	pTemp;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Emit
	*pMStream 
		<<	"Dear "
		<<	pUser->GetUserId()
		<<	",\n\n";

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
	};

	// emit change email announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgEmail,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream	<<	"\nTHIS IS TO CONFIRM YOU THAT YOUR E-MAIL ADDRESS HAS BEEN CHANGED\n"
			"\n"
			"You e-mail address has been changed from:\n"
		<<	pUser->GetEmail()
		<<	"\nto:\n"
		<<	pNewEmail
		<<	"\nby the customer support according to your request."
		<<	"\n"
			"\n";

	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit change email footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgEmail,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream	<<	mpMarketPlace->GetThankYouText()
		<<	"\n"
		<<	mpMarketPlace->GetHomeURL()
		<<	"\n";

	// Send
	sprintf(subject, "%s Change E-mail",
			mpMarketPlace->GetCurrentPartnerName());

	mailRc =	pMail->Send(pNewEmail, 
							(char *)mpMarketPlace->GetConfirmEmail(),
							subject);

	// All done!
	delete	pMail;

	return mailRc;
}
