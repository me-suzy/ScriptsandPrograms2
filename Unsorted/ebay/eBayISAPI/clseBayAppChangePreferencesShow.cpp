/*	$Id: clseBayAppChangePreferencesShow.cpp,v 1.7.166.7.74.2 1999/08/05 18:58:53 nsacco Exp $	*/
//
//	File:	clseBayAppChangeRegistrationShow.cpp
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
//				- 12/09/97 charles  - Added the function to allow encripted password
//				- 05/13/99 bill     - Added a new style of category selector
//				- 07/02/99 nsacco	- removed use of mpMarketPlace->GetName()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
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
"(with the same e-mail address) to have it sent to "
"you again.";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, Registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

void clseBayApp::ChangePreferencesShow(CEBayISAPIExtension *pServer,
										char * pUserId,
										char * pPass,
										bool oldStyle)
{
	CategoryVector	vCategories;
	bool			browserIsJScompatible;

	// Setup
	SetUp();

	// Determine if browser is JS compatible
	browserIsJScompatible = ((GetEnvironment()->GetMozillaLevel() >= 4) && (!GetEnvironment()->IsWebTV()) && (!GetEnvironment()->IsWin16()) && (!GetEnvironment()->IsOpera()));

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Preferences"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream,
						true, NULL, false, false, false, true);

	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
	//	*mpStream <<	ErrorMsgNotConfirmed
	
	// kakiyama 07/07/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry, you have not yet confirmed your registration."
							"You should have received an e-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	ErrorMsgSuspended
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream  << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry, there was a problem confirming your registration. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (!mpUser->HasDetail())
	{
		*mpStream <<	"<h2>Error</h2>"
						"Our records do not show registration information for "
				  <<	mpUser->GetUserId()
				  <<	" on file. This is an <font color=red><b>error</b></font> "
						"and should be reported to "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Allll righty! Let's start.
	*mpStream <<	"<h2>Changing your user preferences</h2>"
					"Thank you for letting us know about changes to your user "
					"information. Please make any changes necessary to the following "
					"information, and then click the submit button on the bottom of "
					"the page.";

	// Link to the same form, but forcing the old style selector
	if (!oldStyle && browserIsJScompatible)
	{
		*mpStream <<    "<p>";
		*mpStream <<	"If you prefer to use the old-style method of choosing a category, click "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageChangePreferencesShow)
				  <<	"eBayISAPI.dll?ChangePreferencesShow&oldStyle=1"
				  <<	"&userid=" 
				  <<	pUserId							// pUserId
				  <<	"&pass=" 
				  <<	mpUser->GetPasswordNoSalt()		// pPass
				  <<	"\">here</a>.";
	}	

/*
	*mpStream <<	"<p>"
					"<b>Note</b>: This page takes a while to load. Thanks for your patience.<p>";
*/

	// begin the form
	*mpStream <<	"<form name=\"ChangePreferencesShow\" method=post action="
			  <<	"\""
			  <<	mpMarketPlace->GetCGIPath(PageChangePreferences)
			  <<	"eBayISAPI.dll"
			  <<	"\""
			  <<	">"
			  <<	"<INPUT TYPE=HIDDEN "
			  <<	"NAME=\"MfcISAPICommand\" "
			  <<	"VALUE=\"ChangePreferences\">"
			  <<	"\n";

	// Emit the email address and password so someone else can't sneak
	// in and choose this user's preferences...
	*mpStream <<	"<input type=hidden name=userid value=\""
			  <<	pUserId									// pUserId
			  <<	"\">\n"
			  <<	"<input type=hidden name=pass value=\""
			  <<	pPass									// pPass
			  <<	"\">\n";

	*mpStream <<	"<table BORDER=\"1\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"450\" BGCOLOR=\"#99CCCC\">"
			  <<	"<tr><td align=\"center\">"				
			  <<	"<strong><font face=\"arial, helvetica\" size=\"3\">My Favorites</font></strong>"
			  <<	"</td></tr>"
			  <<	"</table>";


	///////////////////////////////////////////////////////////////////

	//--------------- category 1 -------------------------
	*mpStream <<	"<p><b>Favorite category 1</b></p>";

	if (!oldStyle && browserIsJScompatible)
	{
		mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"ChangePreferencesShow",
									"1", 
									"interest1",
									(CategoryId)mpUser->GetInterests_1(), 
									true);

	}
	else	// use old-style category selector	
	{
		mpCategories->EmitHTMLLeafSelectionList(
									mpStream,
									"interest1",
									(CategoryId)mpUser->GetInterests_1(),
									"0",
									"Not Selected",
									&vCategories,
									true, true);
	}


	//--------------- category 2 -------------------------
	*mpStream <<	"<p><b>Favorite category 2</b></p>";

	if (!oldStyle && browserIsJScompatible)
	{
		mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"ChangePreferencesShow",
									"2", 
									"interest2",
									(CategoryId)mpUser->GetInterests_2(),
									false);

	}
	else	// use old-style category selector	
	{
		mpCategories->EmitHTMLLeafSelectionList(
									mpStream,
									"interest2",
									(CategoryId)mpUser->GetInterests_2(),
									"0",
									"Not Selected",
									&vCategories,
									true, true);
	}


	//--------------- category 3 -------------------------
	*mpStream <<	"<p><b>Favorite category 3</b></p>";

	// Use javascript version if browser supports it
	if (!oldStyle && browserIsJScompatible)
	{
		mpCategories->EmitHTMLJavascriptCategorySelector(
										mpStream, 
										"ChangePreferencesShow", 
										"3", 
										"interest3",
										(CategoryId)mpUser->GetInterests_3(), 
										false);

	}
	else	// use old-style category selector	
	{
		mpCategories->EmitHTMLLeafSelectionList(
										mpStream,
										"interest3",
										(CategoryId)mpUser->GetInterests_3(),
										"0",
										"Not Selected",
										&vCategories,
										true, true);
	}

	//--------------- category 4 -------------------------
	*mpStream <<	"<p><b>Favorite category 4</b></p>";

	// Use javascript version if browser supports it
	if (!oldStyle && browserIsJScompatible)
	{
		mpCategories->EmitHTMLJavascriptCategorySelector(
										mpStream, 
										"ChangePreferencesShow",
										"4", 
										"interest4",
										(CategoryId)mpUser->GetInterests_4(), 
										false);

	}
	else	// use old-style category selector	
	{
		mpCategories->EmitHTMLLeafSelectionList(
										mpStream,
										"interest4",
										(CategoryId)mpUser->GetInterests_4(),
										"0",
										"Not Selected",
										&vCategories,
										true, true);
	}

	////////////////////////////////////////////////////////////////////

	*mpStream <<	"<p><strong>Click </strong><input type=\"submit\" value=\"submit\"><strong> to change your"
			  <<	" preferences</strong></p>";

	*mpStream <<	"</form>\n";

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

