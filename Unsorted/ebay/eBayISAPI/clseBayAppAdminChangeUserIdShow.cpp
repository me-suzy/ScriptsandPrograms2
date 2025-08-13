/*	$Id: clseBayAppAdminChangeUserIdShow.cpp,v 1.5.396.1 1999/08/01 02:51:43 barry Exp $	*/
//
//	File:	clseBayAppAdminChangeUserIdShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Charles Manga (charles@ebay.com)
//
//	Function:
//
//		Handle a change user ID request
//
// Modifications:
//				- 01/06/98 Charles	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *ChangeUserIdTextPart_1 =
"<P>"
"To change your User ID, simply complete all the information on "
"this form and press the \"Change User ID\" button.<BR> Please consider this carefully!<P>"
"Your User ID is a valuable asset to participating on %s. You may find that your trading partners will "
"come to recognize you by your User ID and to associate certain characteristics with your User ID, "
"much like they might with your Feedback Rating. Changing your User ID could jeopardize those relationships!<P>"
"When you change your User ID, eBay will give you a pair of \"shades\" to help you tell other %s users about your "
"new look. The shades icon will appear after your User ID for %d days. During this time, your old User ID will be "
"\"embargoed.\" No one else will be able to use your old User ID until the %d-day period has expired, "
"and your old User ID will still appear in any of your current auctions.<P>"
"Choose a User ID that is suitable for a long, successful life on %s. And choose wisely."
"<P>";

static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry! You have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. If you did not receive this "
"e-mail, or if you have lost it, please return to the registration page and "
"re-register (with the same User ID and e-mail address) to have it sent to "
"you again."
"<br>"
"Please contact <a href=mailto:support@ebay.com>Customer Support</a> if you have any questions.<P>";


void clseBayApp::AdminChangeUserIdShow(CEBayISAPIExtension *pServer,
									   char *pUserId,
									   eBayISAPIAuthEnum authLevel)
{
	char	*pBlock;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	" Admin Change "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" Show"
				<<	"</TITLE>"
				<<	"</HEAD>"
				<<	flush;

	*mpStream	<<	mpMarketPlace->GetHeader()
				<<	"<br>";

	// And a heading for it all
	*mpStream	<<	"<h2>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	" Admin Change "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" Show"
				<<	"</h2>";


	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	if(!FIELD_OMITTED(pUserId))
	{
		// The User ID is already known
		mpUser	=	mpUsers->GetUser(pUserId);
		if (!mpUser)
		{
			// User ID does not exist. Cannot rename.
			*mpStream	<<	"No such user."
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// We got the user. Let's ensure he is in the right state
		if(!mpUser->IsConfirmed())
		{
			*mpStream	<<	ErrorMsgNotConfirmed
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}
	}


	// We're going to need storage for the block o'text at the top
	pBlock	 = new char[strlen(ChangeUserIdTextPart_1) + 
						(3 * strlen(mpMarketPlace->GetCurrentPartnerName())) +
						(2 * 3) +
						1];

	sprintf(pBlock, ChangeUserIdTextPart_1, 
			mpMarketPlace->GetCurrentPartnerName(),
			mpMarketPlace->GetCurrentPartnerName(),
			EBAY_USERID_EMBARGO_OLD_USERID_DAYS,
			EBAY_USERID_EMBARGO_OLD_USERID_DAYS,
			mpMarketPlace->GetCurrentPartnerName());
	*mpStream	<<	pBlock;
	delete [] pBlock;	
	
	// Now, the rest of the goop
	*mpStream	<<	"<h3>Please complete the following:</h3>"
				<<	"<form method=post action="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageAdminChangeUserId)
				<<	"eBayISAPI.dll"
				<<	"\""
				<<	">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AdminChangeUserId\">";

	if(!FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	"<INPUT TYPE=HIDDEN NAME=\"olduserid\" VALUE="
					<<	"\""
					<<	pUserId
					<<	"\""
					<<	">"
					<<	"<INPUT TYPE=HIDDEN NAME=\"confirm\" VALUE=\"0\">";
	}

	*mpStream	<<	"\n"
				<<	"<p>";


	*mpStream	<<	"<pre>Your current "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	": ";

	if(!FIELD_OMITTED(pUserId))
	{
		// We don't have to enter the User ID because it's known
		*mpStream	<<	pUserId;
	}
	else
	{
		// We do have to get the current User ID
		*mpStream	<<	"<INPUT TYPE=TEXT NAME=\"olduserid\" "
					<<	"SIZE=" << "25" << " "
					<<	"MAXLENGTH=" << EBAY_MAX_USERID_SIZE << " "
					<<	">";
	}


	*mpStream	<<	"\n";

	*mpStream	<<	"Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":        "
				<<	"<input type=password name=pass "
				<<	"size=" << "25" << " "
				<<	"maxlength=" << EBAY_MAX_PASSWORD_SIZE << " "
				<<	">"
				<<	"\n"
				<<	"Your new "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":     "
				<<	"<input type=text name=newuserid "
				<<	"size=" << "25" << " "
				<<	"maxlength=" << EBAY_MAX_USERID_SIZE << " "
				<<	">"
				<<	"\n"
				<<	"\n";
					
	// And now, for the closing
	*mpStream	<<	"</pre><br>\n"
				<<	"<strong>Press this button to submit your change of " 
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" request:</strong>"
				<<	"<p>"
				<<	"<blockquote><input type=submit value=\"Change UserId\">"
				<<	"</blockquote>"
				<<	"<p>"
				<<	"\n"
				<<	"Press this button to clear the form if you made a mistake:"
				<<	"<p>"
				<<	"<blockquote><input type=reset value=\"clear form\"></blockquote>"
				<<	"\n"
				<<	"</form>";


	*mpStream	<<	"<BR>"
				<<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}


