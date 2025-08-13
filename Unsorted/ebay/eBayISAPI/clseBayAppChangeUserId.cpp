/*	$Id: clseBayAppChangeUserId.cpp,v 1.8.166.4.14.2 1999/08/05 18:58:54 nsacco Exp $	*/
//
//	File:	clseBayAppChangeUserId.cpp
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
//				- 12/16/97 Charles	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// 07/02/99 nsacco - removed use of %s for 'eBay'
static const char *ChangeUserIdTextPart_1 =
"<P>"
"To change your User ID, simply complete all the information on "
"this form and press the \"Change User ID\" button.<p> Please consider this carefully!<P>"
"Your User ID is a valuable asset to participating on eBay. You may find that your trading partners will "
"come to recognize you by your User ID and to associate certain characteristics with your User ID, "
"much like they might with your Feedback Rating. Changing your User ID could jeopardize those relationships!"
//"<p><strong>You may only change your User ID once in 30 days!</strong></p>"
"<p>When you change your User ID, eBay will give you a pair of \"shades\" "
"<img border=0 height=15 width=21 alt=\"mask\""
"src=\"%smask.gif\"></A>"
"  to help you tell other eBay users about your "
"new look. The \"shades\" icon will appear after your User ID for %d days. During this time, your old User ID will be "
"\"embargoed.\" No one else will be able to use your old User ID until the %d-day period has expired. "
"All of your current auctions, and any other eBay activity, will be immediately "
"updated to reflect your new User ID.<P>"
"Choose a User ID that is suitable for a long, successful life on eBay. And choose wisely."
"<P>";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same User ID and e-mail address) to have it sent to "
"you again.";
*/

static const char *ErrorMsgUserIdChanged =
"<H3>Your User ID cannot be changed.</H3>"
"Sorry, you cannot change your User ID so frequently. You "
"cannot change it more than once in <B>%d</B> days.<BR>";

void clseBayApp::ChangeUserId(CEBayISAPIExtension *pServer,char *pUserId)
{
	char	*pBlock;
	char	*pLeTexte;
	int		interval = 0;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	" Change User ID"
				<<	"</TITLE>"
				<<	"</HEAD>"
				<<	flush;

	*mpStream	<<	mpMarketPlace->GetHeader()
				<<	"<br>";

	// And a heading for it all
	*mpStream	<<	"<h2>"
				<<	"Change User ID"
				<<	"</h2>";

	if(!FIELD_OMITTED(pUserId))
	{
		// The User ID is already known
		// So, let's check it and see if it has changed recently
		mpUser	=	mpUsers->GetUser(pUserId);
		if (!mpUser)
		{
			// UserId already seems to exist. Cannot rename.

			*mpStream	<<	"No such user."
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}

		// We got the user. Let's ensure they're in the right state.
		if (!mpUser->IsConfirmed())
		{
		//	*mpStream	<<	ErrorMsgNotConfirmed

		// kakiyama 07/07/99
	
			*mpStream   << clsIntlResource::GetFResString(-1,
								"<h2>Unconfirmed Registration</h2>"
								"Sorry, you have not yet confirmed your registration."
								"You should have received an e-mail with instructions for "
								"confirming your registration. "
								"If you did not receive this e-mail, or if you have lost it, "
								"please return to "
								"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
								" and re-register "
								"(with the same User ID and e-mail address) to have it sent to "
								"you again.",
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
								NULL)
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		//
		// Check the number of changes in a generic time (Ex: Once a month)
		// in the User ID changes history table EBAY_USER_PAST_ALIASES
		//
		if (mpUser->CanUserChangeUserId())
		{
			pLeTexte	 = new char[strlen(ErrorMsgUserIdChanged) + 3 + 1];
			sprintf(pLeTexte,ErrorMsgUserIdChanged,EBAY_USERID_CHANGE_DAYS);
			*mpStream	<<	pLeTexte
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();

			CleanUp();
			delete [] pLeTexte;
			return;

		}

	}


	// We're going to need storage for the block o'text at the top
	// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
	pBlock	 = new char[strlen(ChangeUserIdTextPart_1) + 
		                (strlen(mpMarketPlace->GetImagePath())) +
						(2 * 3) +
						1];

	sprintf(pBlock, ChangeUserIdTextPart_1,	   
			mpMarketPlace->GetImagePath(),
			EBAY_USERID_EMBARGO_OLD_USERID_DAYS,
			EBAY_USERID_EMBARGO_OLD_USERID_DAYS);

	*mpStream	<<	pBlock;
	delete [] pBlock;
	
	// Now, the rest of the goop
	*mpStream	<<	"<h3>Please complete the following:</h3>"
				<<	"<form method=post action="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageChangeUserIdShow)
				<<	"eBayISAPI.dll"
				<<	"\""
				<<	">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ChangeUserIdShow\">";

	if(!FIELD_OMITTED(pUserId))
	{
		*mpStream	<<	"<INPUT TYPE=HIDDEN NAME=\"olduserid\" VALUE="
					<<	"\""
					<<	pUserId
					<<	"\""
					<<	">";
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
					"<strong>Press this button to submit your change of User ID request:</strong>"
					"<p> <strong><I>Note:</I> You may only change your User ID once in "
				<<  EBAY_USERID_CHANGE_DAYS	
				<<	" days! </strong>"
					"<p>"
					"<blockquote><input type=submit value=\"Change User ID\"></blockquote>"
                    "<p>"
					"\n"
					"Press this button to clear the form if you made a mistake:"
					"<p>"
					"<blockquote><input type=reset value=\"clear form\"></blockquote>"
					"\n"
					"</form>";


	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}


