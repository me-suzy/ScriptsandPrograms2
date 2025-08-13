/*	$Id: clseBayAppLeaveFeedbackComment.cpp,v 1.4.204.4.34.2 1999/08/05 18:58:57 nsacco Exp $	*/
//
//	File:	clseBayAppLeaveFeedbackComment.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppLeaveFeedbackComment.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display the page
//		where a user leaves a feedback comment.
//
// Modifications:
//				- 08/13/98 mila		- Created
//				- 09/22/98 mila		- use FIELD_OMITTED macro to check userid,
//									  password, foruserid
//				- 05/18/99 mila		- added text just above submit button to
//									  discourage users from clicking it multiple times
//				- 05/19/99 mila		- modified above text message to incorporate button text
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"

#include <time.h>

#include "hash_map.h"


//
// ShowFeedbackCommentPage
//
//	This routine actually retrieves and emits
//	the feedback a user has received. It's a seperate
//	method so that it can be called independently
//	of LeaveFeedbackShow. The latter emits a <TITLE>
//	and other goodies.
//
void clseBayApp::ShowFeedbackCommentPage(char *pUserIdTo,
										 char *pUserIdFrom,
										 int itemNo)
{
	// display header
	*mpStream <<	"\n"
					"<strong><font face=\"arial, helvetica\" size=\"4\">"
					"\n"
					"  <p>Leave Feedback about an eBay User</p>"
					"\n"
					"</font></strong>"
					"\n";

	*mpStream <<	"<form method=\"post\" action=\""
			  <<	mpMarketPlace->GetCGIPath(PageLeaveFeedback)
			  <<	"eBayISAPI.dll\">"
					"\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"LeaveFeedback\">"
					"\n";

	// Begin 2-column table.
	*mpStream	<<	"<table border=\"0\" cellpadding=\"6\" cellspacing=\"0\" width=\"590\">"
					"\n"
				<<	"  <tr>"
					"\n";

	// Output column 1 for user input.
	*mpStream	<<	"    <td valign=\"top\">"
					"\n"
					"      <input type=\"text\" name=\"userid\" size=\"40\"";

	// If user id given, put value in text field.
	if (!FIELD_OMITTED(pUserIdFrom))
	{
		*mpStream <<	" value=\""
				  <<	pUserIdFrom
				  <<	"\"";
	}

	*mpStream <<	"><br>"
					"\n"
					"        <font size=\"2\">Your registered <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/myinfo/userid.html\">User ID</a>"
					"\n"
					"        </font>"
					"\n"
					"      <p><input type=\"password\" name=\"pass\" size=\"40\"><br>";

	*mpStream <<	"\n"
					"        <font size=\"2\">Your <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html\">password</a>"
					"\n"
					"        </font>"
					"\n"
					"      </p>"
					"\n"
					"      <p><input type=\"text\" name=\"otheruserid\" size=\"40\"";

	if (!FIELD_OMITTED(pUserIdTo))
	{
		*mpStream <<	" value=\""
				  <<	pUserIdTo
				  <<	"\"";
	}

	*mpStream <<	"><br>"
					"\n"
					"        <font size=\"2\">User ID of person who you are commenting on</font>"
					"\n"
					"      </p>"
					"\n"
					"      <p><input type=\"text\" name=\"itemno\" size=\"40\"";

	if (itemNo > 0)
	{
		*mpStream <<	" value=\""
				  <<	itemNo
				  <<	"\"";
	}

	*mpStream <<	"><br>"
					"\n"
					"        <font size=\"2\">Item number (include if you want to relate the comment to a transaction)</font>"
					"\n"
					"      </p>"
					"\n"
					"      <p>Is your comment positive, negative, or neutral?<br>"
					"\n"
					"        <font size=\"2\">"
					"\n"
					"          <input type=\"radio\" name=\"which\" value=\"positive\">positive"
					"\n"
					"          &nbsp;&nbsp;"
					"\n"
					"          <input type=\"radio\" name=\"which\" value=\"negative\">negative"
					"\n"
					"          &nbsp;&nbsp;"
					"\n"
					"          <input type=\"radio\" name=\"which\" value=\"neutral\">neutral"
					"\n"
					"        </font>"
					"\n"
					"      </p>"
					"\n"
					"    </td>"
					"\n";

	// Output column 2 for user responsibility message box.
	*mpStream	<<	"    <td valign=\"top\">"
					"\n"
					"      <table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">"
					"\n"
					"        <tr>"
					"\n"
					"          <td width=\"100%\" bgcolor=\"#99cccc\">"
					"\n"
					"            <font face=\"arial,helvetica\" size=\"2\">"
					"\n"
					"              <strong>You are responsible for your own words.</strong>"
					"\n"
					"            </font>"
					"\n"
					"          </td>"
					"\n"
					"        </tr>"
					"\n"
					"        <tr>"
					"\n"
					"          <td width=\"100%\" bgcolor=\"#ffffcc\">"
					"\n"
					"            <font size=\"2\">"
					"\n"
					"              Your comments will be attributed with your name and the date.  eBay cannot take responsibility for the comments you post here, and you should be careful about making comments that could be libelous or slanderous.  To be safe, make only factual, emotionless comments.  Contact your attorney if you have any doubts.  Once left, Feedback <b>cannot be retracted or edited</b> by you or by eBay.<br><br>Please try to resolve any disputes with the other party before publicly declaring a complaint."
					"\n"
					"            </font>"
					"\n"
					"          </td>"
					"\n"
					"        </tr>"
					"\n"
					"      </table>"
					"\n"
					"    </td>"
					"\n"
					"  </tr>"
					"\n"
					"</table>"
					"\n";

	// Output comment text input field.
	*mpStream	<<	"<p>"
					"\n"
					"  <input type=\"text\" name=\"comment\" size=\"80\" maxlength=\"80\"><br>"
					"\n"
					"  <font size=\"2\">Your comment (max. 80 characters)</font>"
					"\n"
					"</p>"
					"\n";

	// Output warning message.
	*mpStream	<<	"<p>"
					"\n"
					"  <strong>WARNING: Once placed, comments cannot be retracted.</strong> If you later change your mind about someone, you'll have to leave another comment. See the <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/forum/feedback.html\">Feedback Forum</a> for an explanation about how your comments affect a users's Feedback Rating."
					"\n"
					"</p>"
					"\n";

	// Output submit button and mouse pic.
	*mpStream	<<	"<p>"
					"\n"
					"  Click <b>once</b> to "
					"  <input type=\"submit\" value=\"leave comment\">&nbsp;&nbsp;&nbsp;"
					"\n"
					"  <img src=\""
				<<	mpMarketPlace->GetPicsPath(PageLeaveFeedbackShow)
				<<	"mouse_leave_feedback.gif\" width=\"42\" height=\"48\" alt=\"mouse with pencil\" align=\"middle\">"
					"\n"
					"</p>"
					"\n";

	// Output clear button.
	*mpStream	<<	"<p>"
					"\n"
					"  Or "
					"  <input type=\"reset\" value=\"clear form\">"
					"  to start again"
					"\n"
					"\n"
					"</p>"
					"\n";

	// Output separator.
	*mpStream	<<	"</form><hr>";

	// Output message.
	*mpStream	<<	"<p>"
					"\n"
					"  <strong>If you regret a comment you made.</strong><br>"
					"\n"
					"If you have previously left a negative comment and have since been able to resolve your misunderstanding, we encourage you to leave a positive or neutral Feedback comment for that person and explain that the misunderstanding has been resolved."
					"\n"
					"</p>"
					"\n";

	// Output message.
	*mpStream	<<	"<p>"
					"\n"
					"  <strong>Resolving disputes by e-mail or by telephone.</strong><br>"
					"\n"
					"eBay cannot remove a comment once it is submitted, nor edit a user's Feedback profile. For this reason, we encourage you to contact your trading partner directly by e-mail or by telephone <i>before</i> leaving a negative Feedback comment. Usually, a misunderstanding or dispute can be resolved by telephone. You can request another person's contact information by clicking <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"search/members/user-query.html\">here</a>."
					"\n"
					"</p>"
					"\n";

	return;
}

//
// ViewFeedback
//
void clseBayApp::LeaveFeedbackShow(CEBayISAPIExtension *pThis,
									  char *pUserIdTo,
									  char *pUserIdFrom,
									  int itemNo)
{

	SetUp();

	// Title
	// nsacco 08/04/99 added <html> tag
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Leave Feedback about an eBay User"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	ShowFeedbackCommentPage(pUserIdTo, pUserIdFrom, itemNo);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

