/*	$Id: clseBayAppPersonalizedFeedbackLogin.cpp,v 1.3.236.2.90.2 1999/08/05 20:42:17 nsacco Exp $	*/
//
//	File:	clseBayAppPersonalizedFeedbackLogin.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppPersonalizedFeedbackLogin.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to emit the
//		personalized feedback login page.
//
// Modifications:
//				- 08/13/98 mila		- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"

#include <time.h>

#include "hash_map.h"


//
// ShowPersonalizedFeedbackLoginPage
//
//	This routine outputs the login page before viewing
//	one's own personalized feedback. It's a seperate
//	method so that it can be called independantly
//	of PersonalizedFeedbackLogin. The latter emits a 
//	<TITLE> and other goodies.
//
void clseBayApp::ShowPersonalizedFeedbackLoginPage(char *pUserId,
												   int itemsPerPage)
{
	*mpStream <<	"\n"
					"<strong><font face=\"arial, helvetica\" size=\"4\">"
					"\n"
					"  <p>Review Feedback Comments Others Have Left about You</p>"
					"\n"
					"</font></strong>"
					"\n";

	*mpStream <<	"<form method=\"post\" action=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewPersonalizedFeedback)
			  <<	"eBayISAPI.dll\">"
					"\n"
					"  <input type=\"hidden\" name=\"MfcISAPICommand\" value=\"ViewPersonalizedFeedback\">"
					"\n";

	// Output user id text input field.
	*mpStream	<<	"  <p>"
					"\n"
					"    <input type=\"text\" name=\"userid\" size=\"40\"";

	if (pUserId != NULL && strlen(pUserId)> 0 && strcmp(pUserId, "default") != 0)	// if user id given, put value in text field
	{
		*mpStream <<	" value=\""
				  <<	pUserId
				  <<	"\"";
	}

	*mpStream <<	"><br>"
					"\n"
					"    <font size=\"2\">Your registered <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/myinfo/userid.html\">User ID</a>"
					"\n"
					"    </font>"
					"\n"
					"  </p>"
					"\n";

	// Output password text input field.
	*mpStream <<	"  <p>"
					"    <input type=\"password\" name=\"pass\" size=\"40\"><br>"
					"\n"
					"    <font size=\"2\">Your <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html\">password</a>"
					"\n"
					"    </font>"
					"\n"
					"  </p>"
					"\n";

	*mpStream <<	"  <p>How many feedback comments do you want on each page?<br>"
					"\n";

	// Output items per page radio buttons.
	switch (itemsPerPage)
	{
	case 0:
		*mpStream <<	"    <input type=\"radio\" name=\"items\" value=\"25\">"
						"\n"
						"    <font size=\"2\">25</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"50\">"
						"\n"
						"    <font size=\"2\">50</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"100\">"
						"\n"
						"    <font size=\"2\">100</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"200\">"
						"\n"
						"    <font size=\"2\">200</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"0\" checked>"
						"\n"
						"    <font size=\"2\">All</font>"
						"\n";
		break;
	case 200:
		*mpStream <<	"    <input type=\"radio\" name=\"items\" value=\"25\">"
						"\n"
						"    <font size=\"2\">25</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"50\">"
						"\n"
						"    <font size=\"2\">50</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"100\">"
						"\n"
						"    <font size=\"2\">100</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"200\" checked>"
						"\n"
						"    <font size=\"2\">200</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"0\">"
						"\n"
						"    <font size=\"2\">All</font>"
						"\n";
		break;
	case 100:
		*mpStream <<	"    <input type=\"radio\" name=\"items\" value=\"25\">"
						"\n"
						"    <font size=\"2\">25</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"50\">"
						"\n"
						"    <font size=\"2\">50</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"100\" checked>"
						"\n"
						"    <font size=\"2\">100</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"200\">"
						"\n"
						"    <font size=\"2\">200</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"0\">"
						"\n"
						"    <font size=\"2\">All</font>"
						"\n";
		break;
	case 50:
		*mpStream <<	"    <input type=\"radio\" name=\"items\" value=\"25\">"
						"\n"
						"    <font size=\"2\">25</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"50\" checked>"
						"\n"
						"    <font size=\"2\">50</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"100\">"
						"\n"
						"    <font size=\"2\">100</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"200\">"
						"\n"
						"    <font size=\"2\">200</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"0\">"
						"\n"
						"    <font size=\"2\">All</font>"
						"\n";
		break;
	case 25:
	default:
		*mpStream <<	"    <input type=\"radio\" name=\"items\" value=\"25\"checked>"
						"\n"
						"    <font size=\"2\">25</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"50\">"
						"\n"
						"    <font size=\"2\">50</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"100\">"
						"\n"
						"    <font size=\"2\">100</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"200\">"
						"\n"
						"    <font size=\"2\">200</font>"
						"\n"
						"    <input type=\"radio\" name=\"items\" value=\"0\">"
						"\n"
						"    <font size=\"2\">All</font>"
						"\n";
		break;
	}

	*mpStream <<		"  </p>"
						"\n";

	// Output submit button.
	*mpStream <<	"  <blockquote>"
					"    <p><input type=\"submit\" value=\"view feedback\"></p>"
					"  </blockquote>"
					"\n";

	*mpStream <<	"</form>"
					"\n";

	return;
}

//
// PersonalizedFeedbackLogin
//
void clseBayApp::PersonalizedFeedbackLogin(CEBayISAPIExtension *pThis,
										   char *pUserId,
										   int itemsPerPage)
{
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Review Feedback Comments Others Have Left about You"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	ShowPersonalizedFeedbackLoginPage(pUserId, itemsPerPage);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

