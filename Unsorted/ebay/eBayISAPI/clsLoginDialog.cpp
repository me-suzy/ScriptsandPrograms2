/*	$Id: clsLoginDialog.cpp,v 1.8.22.3.76.1 1999/08/06 20:31:50 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display a dialog for user to provide user id and password
//
//	Modifications:
//				- 12/19/97 Wen	- Created
//
#include "ebihdr.h"
#include "clsEmailLoginWidget.h"
#include "clsPSLoginWidget.h"


bool ShowRememberMe = true;

// TODO - replace picspath
const char MsgPrompt[] =
"<font size=\"3\">" 
"eBay kindly requests that you submit your User ID and password to "
"view the User ID History or email addresses of other users. \n"
"<P>Note: When the shades icon<img border=0 height=15 width=21 alt=\"mask\""
"src=\"http://pics.ebay.com/aw/pics/mask.gif\"> appears next to a User ID, it "
"signifies that the user has changed his/her User ID within the "
"last 30 days.  The shades icon will disappear after the user has "
"maintained the same User ID for a 30-day period."
"</font>";

const char MsgCookie[] = 
"By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
"to skip this step if you request another user's email address during this "
"browser session.  When you turn your browser off, the cookie will "
"disappear. For more information about cookies, click "
"<A HREF=\"http://pages.ebay.com/help/myinfo/cookies.html\">here</A>.";

const char MsgRegitration[] =
"If you are not a registered eBay user, you can "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">register<a> "
"for free.";

//
// pAction is the cgi path upto the dll
// Pairs is the number of hidden name values pairs
// pNameValue is the hidden name value pairs
// FieldsOnly indicates whether to emit the whole page or 
//		only the user id, password, checkbox, and submit button.
//
void clseBayApp::LoginDialog(char* pAction,
							 int Pairs, 
							 clsNameValuePair* pNameValue,
							 bool FieldsOnly/*=false*/,
							 LoginTypeEnum LoginType/*=LOGIN_GET_EMAIL*/)
{
	clsLoginWidget*	pLogin;

	// create Login Widget based on Login Type
	switch (LoginType)
	{
	case eLoginGetEmail:
		pLogin = new clsEmailLoginWidget(mpMarketPlace);
		break;

	case eLoginPersonalShopper:
		pLogin = new clsPSLoginWidget(mpMarketPlace);
		break;

	default:
		pLogin = new clsLoginWidget(mpMarketPlace);
		break;
	}

	// passing the parameters and emit html
	pLogin->SetParams(pAction, Pairs, pNameValue, FieldsOnly);
	pLogin->EmitHTML(mpStream);

	delete pLogin;
}

