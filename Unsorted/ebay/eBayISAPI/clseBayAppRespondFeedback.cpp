/*	$Id: clseBayAppRespondFeedback.cpp,v 1.5.164.1.102.2 1999/08/06 20:31:53 nsacco Exp $	*/
//
//	File:	clseBayAppRespondFeedback.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppRespondFeedback.cpp
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//		Contains the methods used to respond to
//		feedback. 
//
// Modifications:
//				- 08/13/98 wen		- Created
//				- 08/22/98 mila		- string formatting and capitalization
//				- 08/25/98 mila		- restrict response text to 80 characters
//				- 08/27/98 mila		- add link from confirmation page to updated
//									  feedback profile; added missing endquote
//				- 09/12/98 mila		- changed error message headers and text
//				- 09/22/98 mila		- error checking clean-up; use encrypted
//									  password in link on confirmation page
//				- 10/01/98 mila		- lightened blue bg color in feedback item
//				- 10/30/98 mila		- added code to prevent linking to ViewItem
//									  page from item number if item is an adult
//									  item and user has no adult cookie
//				- 11/11/98 mila		- run feedback text through method
//									  clsUtilities::StripHTML before emitting
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "clseBayTimeWidget.h"		// petra

const char* ErrorInInput =
"<h2>Error in the Input Data</h2>"
"There are errors in the input data, please go back and try again.";

const char* ErrorMsgHasResponse =
"<h2>Feedback already has a response</h2>"
"Sorry, the feedback you responded to already has a response.  You are allowed to respond to a particular feedback comment ONCE only.";

const char* ErrorMsgResponseTooLong =
"<h2>Comment too long</h2>"
"Sorry, your comment is too long.  Please go back and enter a comment that is no longer than 80 characters.";

const char* ErrorMsgNoResponse =
"<h2>Comment field empty</h2>"
"Sorry, the comment field was empty. Please go back and include a comment to complete your response.";

const char* ErrorMsgNoResponsePassword =
"<h2>Password field empty</h2>"
"Sorry, your password field was empty. Please go back and enter your password to leave your response comment.";

void clseBayApp::RespondFeedbackShow(CEBayISAPIExtension *pServer,
									 int CommentorId,		// author of original comment
									 time_t CommentDate,
									 int CommenteeId,		// user who original comment is for
									 int startingPage,
									 int itemsPerPage)
{
	clsUserIdWidget *	pUserIdWidget;
	clsFeedback *		pFeedback;
	clsFeedbackItem *	pFeedbackItem;
	clsUser *			pCommentingUser;

	clsItem				item;
	bool				bIsAdultItem;
	bool				bHasAdultCookie;

	// commenting date
// petra	struct tm *			pTheTime;
// petra	char				theTime[40];

	char *				pSafeText;
	
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Response"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// get user id of person who feedback is for
	mpUser = mpUsers->GetUser(CommenteeId, true);
	if (mpUser == NULL)
	{
		*mpStream << "<h2>Invalid User ID</h2>"
				  << "Sorry, an invalid User ID has been encountered.  "
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;
		CleanUp();
		return;
	}

	// get list of feedback left for above user
	pFeedback = mpUser->GetFeedback();

	// get feedback item left by person with given commentor id
	pFeedbackItem = pFeedback->GetItem(CommentorId, CommentDate);

	// check for null feedback item pointer
	if (pFeedbackItem == NULL)
	{
		pCommentingUser = mpUsers->GetUser(CommentorId, true);

		*mpStream << "<h2>No Feedback from User</h2>"
				  << "Sorry, you have no feedback from ";

		if (pCommentingUser != NULL)
		{
			*mpStream << "<font color=green>"
					  << pCommentingUser->GetUserId()
					  << "</font>. ";
		}
		else
		{
			*mpStream << "this user.  ";
		}

		*mpStream << "If you feel you have reached this page in error, "
				  << "please report it to "
				  <<	"<A HREF="
				  <<	"\""
				 <<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				 <<	"eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue"
				 <<	"\">"
				 <<	"Customer Support"
				 <<	"</A>."
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		delete pCommentingUser;

		CleanUp();
		return;
	}

	// check feedback item for existing response
	if (pFeedbackItem->mResponse[0] != 0)
	{
		*mpStream	<<	ErrorMsgHasResponse
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// header
	*mpStream	<< "<h2>Respond To Feedback Left for You </h2>\n";

	*mpStream	<<	"<p>Here is the feedback you're responding to:</p>\n"
					"<table border=\"1\" width=\"100%\">\n"
					"<tr><td>\n"
					"<table border=\"0\" width=\"100%\" cellspacing=\"0\">\n"
					"<tr><td bgcolor=\"#cff0ff\" align=\"left\">\n"
					"<b>User: </b>";

	// commentor user id
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
	pUserIdWidget->SetUserInfo(pFeedbackItem->mCommentingUserId, 
								"ERROR",
								(UserStateEnum) pFeedbackItem->mCommentingUserState,
								mpMarketPlace->UserIdRecentlyChanged(pFeedbackItem->mCommentingUserIdLastModified),
								pFeedbackItem->mCommentingUserScore);
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;

// petra	pTheTime	= localtime(&CommentDate);
// petra	strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S %z", pTheTime);
	*mpStream	<<	" <b>Date:</b> ";
	clseBayTimeWidget timeWidget (mpMarketPlace, 1, 2, CommentDate);	// petra
	timeWidget.EmitHTML (mpStream);
// petra				<<	theTime
	*mpStream	<<	"</td>\n";

	*mpStream	<<	"<td bgcolor=\"#cff0ff\" align=\"right\">";

	// Display item number.
	if (pFeedbackItem->mItem > 0)
	{
		if (gApp->GetDatabase()->GetItem(pFeedbackItem->mItem, &item, NULL, 0))
		{
			bHasAdultCookie = HasAdultCookie();
			bIsAdultItem = item.IsAdult();
			if (!bIsAdultItem || (bIsAdultItem && bHasAdultCookie))
			{
				// If the item isn't an adult item, or if it is an adult item
				// and the user has an adult cookie, then go ahead an provide
				// a link to the view item page.
				*mpStream	<<	"<b>Item:</b> "
							<<	"<A HREF="
							<<	"\""
							<<	mpMarketPlace->GetCGIPath(PageViewItem)
							<<	"eBayISAPI.dll?ViewItem&amp;item="
							<<	pFeedbackItem->mItem
							<<	"\">"
							<<	pFeedbackItem->mItem
							<<	"</A>"
							<<	"\n";
			}
			else if (bIsAdultItem && !bHasAdultCookie)
			{
				// Don't provide a link to the view item page if the
				// item is an adult item but the user doesn't have an
				// adult cookie.
				*mpStream	<<	"<b>Item:</b> "
							<<	pFeedbackItem->mItem
							<<	"\n";
			}
		}
		else
		{
			// Don't provide a link to the view item page if the item
			// is no longer in the database.
			*mpStream	<<	"<b>Item:</b> "
						<<	pFeedbackItem->mItem
						<<	"\n";
		}
	}

	*mpStream	<<	"&nbsp;</td></tr>";

	// Output Complaint, Praise, or Neutral, with color coding.
	*mpStream << "<tr><td colspan=2><strong>";
	switch (pFeedbackItem->mType)
	{

		case FEEDBACK_NEGATIVE:
			*mpStream << "<font color=red>Complaint</font>:</strong> ";
			break;

		case FEEDBACK_POSITIVE:
			*mpStream << "<font color=green>Praise</font>:</strong> ";
			break;

		case FEEDBACK_NEUTRAL:
		case FEEDBACK_NEGATIVE_SUSPENDED:
		case FEEDBACK_POSITIVE_SUSPENDED:
			*mpStream << "Neutral:</strong> ";
			break;

		default:
			*mpStream << ":</strong> ";
			break;
	}

	pSafeText = clsUtilities::StripHTML(pFeedbackItem->mText);
	*mpStream	<<	pSafeText
				<<	"\n</td></tr></table>\n"
					"</td></tr></table>";
	delete pSafeText;

	// create the form
	*mpStream	<<	"<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageRespondFeedback)
				<<	"eBayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"RespondFeedback\">\n"
					"<table border=\"0\" cellpadding=\"6\" cellspacing=\"0\"\n>"
					"<tr><td valign=\"top\" width=\"291\">\n"
					"<input type=\"hidden\" name=\"commentor\" value=\""
				<<	CommentorId
				<<	"\">\n"
					"<input type=\"hidden\" name=\"time\" value=\""
				<<	CommentDate
				<<	"\">\n"
					"<input type=\"hidden\" name=\"commentee\" value=\""
				<<	mpUser->GetUserId()
				<<	"\">\n"
					"<p><input type=\"password\" name=\"pass\" size=\"40\"\n>"
					"<br><font size=\"2\">Your <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/reqpass.html\">"
					"password</a></font></p></td>\n"
					"<td valign=\"top\" width=\"323\">\n"
					"<table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">\n"
					"<tr><td width=\"100%\" bgcolor=\"#99CCCC\">"
					"<font face=\"arial,helvetica\" size=\"2\">\n"
					"<strong>You are responsible for your own words.</strong></font></td></tr>\n"
					"<tr><td width=\"100%\" bgcolor=\"#FFFFCC\">\n"
					"<font size=\"2\">Your responses will be attributed with your name. "
					"eBay cannot take responsibility for the responses you post here, "
					"and you should be careful about making comments that could be libelous or slanderous. "
					"To be safe, make only factual, emotionless comments. "
					"Contact your attorney if you have any doubts. "
					"Once left, responses <b>cannot be retracted nor edited</b> by you or by eBay.<br>\n"
					"<br>Please try to resolve any disputes with the other party "
					"before publicly declaring a complaint.</font>\n"
					"</td></tr></table>\n"
					"</td></tr></table>\n"
					"<p><input type=\"text\" name=\"response\" size=\"80\" maxlength=\"80\">\n"
					"<font size=\"2\"><br>Your response (max. 80 characters)</font>\n"
					"<br></p>"
					"<p><strong>WARNING: Once placed, responses cannot be retracted.</strong>\n"
					"If you later change your mind about what you wrote, "
					"you'll have to leave a separate response. </p>\n"
					"<input type=\"hidden\" name=\"page\" value=\""
				<<	startingPage
				<<	"\">\n"
					"<input type=\"hidden\" name=\"items\" value=\""
				<<	itemsPerPage
				// TODO - replace pics path below
				<<	"\">\n"
					"<p><input type=\"submit\" value=\"leave response\">"
					"&nbsp;&nbsp;&nbsp; "
					"<img src=\"http://pics.ebay.com/aw/pics/mouse_leave_feedback.gif\" "
					"width=\"42\" height=\"48\""
					"align=\"middle\"></p>"
					"<p><input type=\"reset\" value=\"clear form\"></p>"
					"<hr><p><strong>If you regret a comment you made. </strong>"
					"<br>If you have previously left a negative comment and "
					"have since been able to resolve your misunderstanding, "
					"we encourage you to do one of two things:<br>"
					"1. If the person you left feedback for <i>has</i> responded "
					"to your original feedback, "
					"leave a follow-up comment to your feedback "
					"which explains that the misunderstanding has been resolved."
					"<br>2. If the person you left feedback for <i>has not</i> "
					"responded to your original feedback, "
					"leave a new feedback comment for that person which explains "
					"that the misunderstanding has been resolved. </p>"
					"<p><strong>Resolving disputes by e-mail or by telephone.</strong>"
					"<br>eBay cannot remove a comment once it is submitted, "
					"nor edit a user's Feedback Profile. "
					"For this reason, we encourage you to contact your trading partner "
					"directly by e-mail or by telephone <i>before</i> "
					"leaving a negative Feedback comment. "
					"Usually, a misunderstanding or dispute can be resolved by "
					"telephone. You can request another person's contact information "
					"by clicking <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"search/members/user-query.html\">here</a>.</p>";

	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	// clean up
	delete pFeedbackItem;

	CleanUp();
	return;

}

void clseBayApp::RespondFeedback(CEBayISAPIExtension *pServer,
								 int CommentorId,
								 time_t CommentDate,
								 char* pCommentee,
								 char* pPassword,
								 char* pResponse,
								 int startingPage,
								 int itemsPerPage)
{
	clsFeedback*	pFeedback;
	int				feedbackScore;

	int				badWordClass;
	char			*pBadWord;

	
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Response"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// check the password
	if (FIELD_OMITTED(pPassword))
	{
		*mpStream	<<	ErrorMsgNoResponsePassword
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// check the repsonse
	if (FIELD_OMITTED(pResponse))
	{
		*mpStream	<<	ErrorMsgNoResponse
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (strlen(pResponse) > 80)
	{
		*mpStream	<<	ErrorMsgResponseTooLong
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// use max length of vulgar words ok.
	pBadWord = new char[EBAY_MAX_USERID_SIZE + 1];

	if (clsUtilities::TooVulgar(pResponse, &badWordClass, pBadWord))
	{
		if (badWordClass == clsUtilities::VULGAR)
		{
			*mpStream <<	"<h2>Comment Too Vulgar.</h2>"
					 "Sorry, our vulgarity-checking program has determined that "
					 "your comment may contain the word <font color=\"red\">\""
						<< pBadWord
						<< ".\" </font>"
					 "Sometimes the program "
					 "is wrong, however, and will piece together perfectly friendly words "
					 "to make a word that sounds dirty.<p>For example, let's pretend that "
					 "<font color=\"red\">\"dingo\"</font> is a vulgar word. If you leave feedback that reads "
					 "\"Merchandise received in good order,\" the program will piece "
					 "together \"recieve<font color=\"red\">d in go</font>od\" and "
					 "give you this warning message. If this happened to you, "
					 "just change your feedback a little (e.g., \"Merchandise received "
					 "in fine order\") and the program will let it through. Sorry for the inconvenience, "
					 "but we strive to ensure a pleasant experience for all of those in the eBay community, "
					 "and in some cases, we may be overly protective just to be safe."
					 "\n"
					  <<	mpMarketPlace->GetFooter();

			delete [] pBadWord;
			CleanUp();
			return;
		}
	}

	delete [] pBadWord;

	// check user id and password of commentee (who is now responding to comment)
	mpUser = mpUsers->GetAndCheckUserAndPassword(pCommentee, pPassword, mpStream);
	if (mpUser == NULL)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Check the commentee's status.  If the commentor is
	// suspended, don't let him/her leave feedback.
	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You are not allowed to respond to Feedback while you are "
						"suspended."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// get commentee's feedback
	pFeedback = mpUser->GetFeedback();

	// check for no feedback
	if (pFeedback == NULL)
	{
		*mpStream	<< "<h2>Feedback Error</h2>"
					<< "Sorry, you have no feedback.  "
					<< "You cannot respond to feedback you don't have.";

		CleanUp();
		return;
	}

	feedbackScore = pFeedback->GetScore();
	if (feedbackScore <= -4)
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You are not allowed to respond to Feedback if your Feedback "
						"rating is "
				  <<	feedbackScore
				  <<	"."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// insert the response; AddResponse will check whether the reponse is there
	if (!pFeedback->AddResponse(CommentorId, CommentDate, pResponse))
	{
		*mpStream	<<	ErrorMsgHasResponse
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// done
	*mpStream	<<	"<h2>Response Has Been Recorded</h2>"
				<<	"Your response to the feedback comment from "
				<<	"<font color=\"green\">"
				<<	mpUsers->GetUser(CommentorId, true)->GetUserId()
				<<	"</font>"
				<<	" has been recorded. Click "
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewPersonalizedFeedback)
				<<	"eBayISAPI.dll?ViewPersonalizedFeedback&userid="
				<<	pCommentee
				<<	"&pass="
				<<	mpUser->GetPasswordNoSalt()
				<<	"&page="
				<<	startingPage
				<<	"&items="
				<<	itemsPerPage
				<<	"\">here</a> to view your updated feedback profile."
				<<	"<p>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
}
