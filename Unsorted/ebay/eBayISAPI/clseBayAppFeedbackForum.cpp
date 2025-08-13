/*	$Id: clseBayAppFeedbackForum.cpp,v 1.7.236.1.108.1 1999/08/01 03:01:13 barry Exp $	*/
//
//	File:	clseBayAppFeedbackForum.cc
//
//	Class:	clseBayApp
//
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Simply emits the form required to view a user's
//		feedback.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"


//
// LeaveFeedback
//
void clseBayApp::FeedbackForum(CEBayISAPIExtension *pThis)
{
	SetUp();

	// Title
	*mpStream <<	"<html>"
					"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Profiles"
			  <<	"</title>"
					"<meta http-equiv=\"Refresh\" content=\"0; url="
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/forum/feedback-login.html\">"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	
	*mpStream	<<	"<p><font face=\"arial, helvetica\" size=\"4\">"
				<<	"<strong>See the Feedback Profile of an eBay user</strong></font><br></p>"
				<<	"\n";

	*mpStream	<<	"<form method=post action="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageViewFeedback)
				<<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"ViewFeedback\">"
					"<p>"
					"\n";

	*mpStream	<<	"<input type=text name=userid size=40>"
				<<	"<br><font size=\"2\">"
				<<	"\n"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" of the person whose Feedback you'd like to see</font>"
				<<	"\n"
				<<	"<p>How many feedback comments do you want on each page?<br>"
				<<	"\n"
    			<<	"<input type=radio name=pagination value=\"25\">"
				<<	"\n"
    			<<	"<font size=\"2\">25</font>"
				<<	"\n"
    			<<	"<input type=radio name=pagination value=\"50\">"
				<<	"\n"
    			<<	"<font size=\"2\">50</font>"
				<<	"\n"
    			<<	"<input type=radio name=pagination value=\"100\">"
				<<	"\n"
    			<<	"<font size=\"2\">100</font>"
				<<	"\n"
    			<<	"<input type=radio name=pagination value=\"200\">"
				<<	"\n"
    			<<	"<font size=\"2\">200</font>"
				<<	"\n"
    			<<	"<input type=radio name=pagination value=\"0\">"
				<<	"\n"
    			<<	"<font size=\"2\">All</font></p>"
				<<	"\n"
				<<	"<input type=submit value=\"view feedback\">"
				<<	"\n";

	*mpStream	<<	"&nbsp;&nbsp;&nbsp;&nbsp;"
					"<img src="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"pics/mag_view_feedback.gif"
					"\""
					" width=\"42\" height=\"46\" alt=\"magnifying glass\" align=\"middle\"></p>";

	*mpStream	<<	"</form>"
				<<	"\n"
				<<	"<p><strong>Hint</strong><br>"
				<<	"A shortcut method for viewing the Feedback Profile of a registered user is to click on the "
				<<	"number (the Feedback Rating) beside that person's User ID or e-mail address. For an "
				<<	"explanation of Feedback Ratings, visit the ";

	*mpStream	<<	"<A HREF="
					"\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/forum/feedback.html\">"
				<<	"Feedback Forum</a>.</p>";


	*mpStream	<<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


