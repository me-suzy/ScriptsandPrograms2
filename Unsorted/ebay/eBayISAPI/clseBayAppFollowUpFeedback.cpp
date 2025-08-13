/*	$Id: clseBayAppFollowUpFeedback.cpp,v 1.6.2.1.102.3 1999/08/06 20:31:52 nsacco Exp $	*/
//
//	File:	clseBayAppFollowUpFeedback.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppFollowUpFeedback.cpp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods user to follow up
//		on feedback comments.
//
// Modifications:
//				- 08/13/98 wen		- Created
//				- 08/25/98 mila		- restrict comment text to 80 characters
//				- 08/27/98 mila		- modified follow-up confirmation page to
//									  contain link to updated feedback profile;
//									  added missing endquote to output stream
//				- 09/12/98 mila		- modified error message headers and text
//				- 09/22/98 mila		- cleaned up error checking and error messages;
//									  changed "here" link URL to contain encrypted
//									  password (was using unencrypted)
//				- 10/01/98 mila		- lightened blue bg color for feedback items
//				- 10/30/98 mila		- added code to prevent linking to ViewItem
//									  page from item number if item is an adult
//									  item and user has no adult cookie
//				- 11/11/98 mila		- run feedback text and response through method
//									  clsUtilities::StripHTML before emitting
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "clseBayTimeWidget.h"		// petra

extern const char* ErrorInInput;

static const char* ErrorMsgHasNoResponse =
"<h2>Feedback has no response</h2>"
"Sorry, the feedback you followed up on has no response. You are not allowed to follow up on a feedback comment that has not yet been responded to.";

static const char* ErrorMsgHasFollowUp =
"<h2>Feedback already has a follow-up comment</h2>"
"Sorry, the feedback you responded to already has a follow-up comment. You are allowed to follow up on a particular feedback comment ONCE only.";

static const char* ErrorMsgFollowUpTooLong =
"<h2>Comment too long</h2>"
"Sorry, your comment is too long.  Please go back and enter a comment that is no longer than 80 characters.";

static const char* ErrorMsgNoFollowUp =
"<h2>Comment field empty</h2>"
"Sorry, the comment field was empty. Please go back and include a comment to complete your follow-up.";

static const char* ErrorMsgNoFollowUpPassword =
"<h2>Password field empty</h2>"
"Sorry, your password field was empty. Please go back and enter your password to leave your follow-up comment.";

void clseBayApp::FollowUpFeedbackShow(CEBayISAPIExtension *pThis,
									  int CommentorId,
									  time_t CommentDate,
									  int CommenteeId)
{
	clsUserIdWidget *	pUserIdWidget;
	clsUser *			pCommentor;

	clsFeedback *		pFeedback;			// feedback left for commentee
	clsFeedbackItem *	pFeedbackItem;
	clsFeedback *		pCommentorFeedback;	// feedback left for commentor
	long				feedbackScore;

	clsItem				item;
	bool				bIsAdultItem;
	bool				bHasAdultCookie;

	// commenting date
// petra	struct tm			*pTheTime;
// petra	char				theTime[40];

	char *				pSafeText;

	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>\n"
					"<head>\n"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Follow-Up"
					"</title>\n"
					"</head>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n"
			  <<	flush;

	// get user id of person who left original feedback comment
	pCommentor = mpUsers->GetUser(CommentorId, true);

	// get user id of person who feedback is for
	mpUser = mpUsers->GetUser(CommenteeId, true);

	// validate commentee user id
	if (mpUser == NULL)
	{
		*mpStream << "<h2>Invalid User ID</h2>"
				  << "Sorry, "
				  << "<font color=green>"
				  << CommenteeId
				  << "</font> is not a valid numeric user ID."
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	// validate commentor user id
	if (pCommentor == NULL)
	{
		*mpStream << "<h2>Invalid User ID</h2>"
				  << "Sorry, "
				  << "<font color=green>"
				  << CommentorId
				  << "</font> is not a valid numeric user ID."
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	// get list of feedback left for commentee
	pFeedback = mpUser->GetFeedback();

	// get feedback item left for person with given commentee id
	pFeedbackItem = pFeedback->GetItem(CommentorId, CommentDate);

	if (pFeedbackItem == NULL)
	{
		*mpStream << "<h2>No Feedback for User</h2>"
				  << "Sorry, you have not left feedback for "
				  << "<font color=green>"
				  << mpUser->GetUserId()
				  << "</font>. You cannot follow up on feedback you haven't left. "
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	if (pFeedbackItem->mResponse[0] == 0)
	{
		*mpStream << ErrorMsgHasNoResponse
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	if (pFeedbackItem->mFollowUp[0] != 0)
	{
		*mpStream << ErrorMsgHasFollowUp
				  << "\n<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	// output header
	*mpStream <<	"<h2>Follow up on Feedback You Left</h2>\n";

	// output feedback item being followed up on
	*mpStream <<	"<p>Here is the feedback you're following up on:</p>\n"
					"<table border=\"1\" width=\"100%\">\n"
					"  <tr>\n"
					"    <td>\n"
					"      <table border=\"0\" width=\"100%\" cellspacing=\"0\">\n"
					"        <tr>\n"
					"          <td bgcolor=\"#cff0ff\" align=\"left\">\n"
					"<b>User: </b>";

	pCommentorFeedback = pCommentor->GetFeedback();
	feedbackScore = pCommentorFeedback == NULL ? 0 : pCommentorFeedback->GetScore();

	// commentor user id
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
	pUserIdWidget->SetUserInfo(mpUser->GetUserId(), 
								"ERROR",
								mpUser->GetUserState(),
								mpMarketPlace->UserIdRecentlyChanged(mpUser->GetUserIdLastModified()),
								pFeedback->GetScore());
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;

// petra	pTheTime	= localtime(&CommentDate);
// petra	strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S %z", pTheTime);
	*mpStream	<<	" <b>Date:</b> ";
	clseBayTimeWidget timeWidget (mpMarketPlace, 1, 2, CommentDate);	// petra
	timeWidget.EmitHTML (mpStream);										// petra
// petra				<<	theTime
	*mpStream	<<	"          </td>\n";

	*mpStream	<<	"          <td bgcolor=\"#cff0ff\" align=\"right\">\n";

	// display item number
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

	*mpStream	<<	"&nbsp;\n"
					"          </td>\n"
					"        </tr>\n";

	// Output Complaint, Praise, or Neutral, with color coding.
	*mpStream <<	"        <tr>\n"
					"          <td colspan=2><strong>";
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
				<<	"\n"
					"          </td>\n"
					"        </tr>\n";
	delete pSafeText;

	// Output response to original comment.
	pSafeText = clsUtilities::StripHTML(pFeedbackItem->mResponse);
	*mpStream	<<	"        <tr>\n"
					"          <td>\n"
					"            <i>Response</i>: "
				<<	pSafeText
				<<	"\n"
					"          </td>\n"
					"        </tr>\n"
					"      </table>\n"
					"    </td>\n"
					"  </tr>\n"
					"</table>\n";
	delete pSafeText;

	// create the form
	*mpStream	<<	"<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageFollowUpFeedback)
				<<	"eBayISAPI.dll\">\n"
					"  <input type=\"hidden\" name=\"MfcISAPICommand\" value=\"FollowUpFeedback\">\n"
					"  <table border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n"
					"  <tr>\n"
					"    <td valign=\"top\" width=\"291\">\n"
					"      <input type=\"hidden\" name=\"commentor\" value=\""
				<<	CommentorId
				<<	"\">\n"
					"      <input type=\"hidden\" name=\"time\" value=\""
				<<	CommentDate
				<<	"\">\n"
					"      <input type=\"hidden\" name=\"commentee\" value=\""
				<<	mpUser->GetUserId()
				<<	"\">\n"
					"      <p><input type=\"password\" name=\"pass\" size=\"40\">\n"
					"      <br><font size=\"2\">Your <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				// TODO fix pics path in text below
				<<	"services/buyandsell/reqpass.html\">"
					"password</a></font></p>\n"
					"    </td>\n"
					"    <td valign=\"top\" width=\"323\">\n"
					"      <table border=\"1\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">\n"
					"        <tr>\n"
					"          <td width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"            <font face=\"arial,helvetica\" size=\"2\">\n"
					"              <strong>You are responsible for your own words.</strong>\n"
					"            </font>\n"
					"          </td>\n"
					"        </tr>\n"
					"        <tr>\n"
					"          <td width=\"100%\" bgcolor=\"#FFFFCC\">\n"
					"            <font size=\"2\">Your remarks will be attributed with your name. "
					"eBay cannot take responsibility for the remarks you post here, "
					"and you should be careful about making comments that could be libelous or slanderous. "
					"To be safe, make only factual, emotionless comments. "
					"Contact your attorney if you have any doubts. "
					"Once left, remarks <b>cannot be retracted nor edited</b> by you or by eBay.<br>\n"
					"<br>Please try to resolve any disputes with the other party "
					"before publicly declaring a complaint.\n"
					"            </font>\n"
					"          </td>\n"
					"        </tr>\n"
					"      </table>\n"
					"    </td>\n"
					"  </tr>\n"
					"</table>\n"
					"<p><input type=\"text\" name=\"followup\" size=\"80\" maxlength=\"80\">\n"
					"  <font size=\"2\"><br>Your follow-up remarks (max. 80 characters)</font><br>\n"
					"</p>\n"
					"<p>\n"
					"  <strong>WARNING: Once placed, follow-up remarks cannot be retracted.</strong>\n"
					"If you later change your mind about what you wrote, "
					"you'll have to leave a separate feedback message.\n"
					"</p>\n"
					"<p>\n"
					"  <input type=\"submit\" value=\"leave follow-up remark\">"
					"  &nbsp;&nbsp;&nbsp; "
					"  <img src=\"http://pics.ebay.com/aw/pics/mouse_leave_feedback.gif\" "
					"width=\"42\" height=\"48\""
					"align=\"middle\">\n"
					"</p>\n"
					"<p>\n"
					"  <input type=\"reset\" value=\"clear form\">\n"
					"</p>"
					"<hr><p>\n"
					"  <strong>If you regret a comment you made. </strong>"
					"<br>If you have previously left a negative comment and "
					"have since been able to resolve your misunderstanding, "
					"we encourage you to do one of two things:<br>"
					"1. If the person you left feedback for <i>has</i> responded "
					"to your original feedback, leave a follow-up comment to your "
					"feedback which explains that the misunderstanding has been "
					"resolved.<br>"
					"2. If the person you left feedback for <i>has not</i> "
					"responded to your original feedback, leave a new feedback "
					"comment for that person which explains that the misunderstanding "
					"has been resolved.</p>"
					"<p><strong>Resolving disputes by e-mail or by telephone.</strong>"
					"<br>eBay cannot remove a comment once it is submitted, nor edit "
					"a user's Feedback Profile. For this reason, we encourage you to "
					"contact your trading partner directly by e-mail or by telephone "
					"<i>before</i> leaving a negative Feedback comment. Usually, a "
					"misunderstanding or dispute can be resolved by telephone. You"
					"can request another person's contact information by clicking "
					"<a href=\""
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

void clseBayApp::FollowUpFeedback(CEBayISAPIExtension *pThis,
								  int CommentorId,
								  time_t CommentDate,
								  char* pCommentee,
								  char* pPassword,
								  char* pFollowUp)
{
	clsFeedback*	pFeedback;
	clsFeedback*	pCommentorFeedback;
	int				feedbackScore;

	clsUser*		pCommentor;
	clsUser*		pTempUser;

	int				badWordClass;
	char			*pBadWord;

	
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Follow-up"
					"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n"
			  <<	flush;

	// check the password
	if (FIELD_OMITTED(pPassword))
	{
		*mpStream	<<	ErrorMsgNoFollowUpPassword
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// check the follow-up
	if (FIELD_OMITTED(pFollowUp))
	{
		*mpStream	<<	ErrorMsgNoFollowUp
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (strlen(pFollowUp) > 80)
	{
		*mpStream	<<	ErrorMsgFollowUpTooLong
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// use max length of vulgar words ok.
	pBadWord = new char[EBAY_MAX_USERID_SIZE + 1];

	if (clsUtilities::TooVulgar(pFollowUp, &badWordClass, pBadWord))
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
					 "together \"receive<font color=\"red\">d in go</font>od\" and "
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

	// check user id and password
	mpUser = mpUsers->GetAndCheckUser(pCommentee, mpStream);
	if (mpUser == NULL)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	pTempUser = mpUsers->GetUser(CommentorId, true);
	if (pTempUser == NULL)
	{
		*mpStream << ErrorInInput
				  << "<p>"
				  << mpMarketPlace->GetFooter()
				  << flush;

		CleanUp();
		return;
	}

	// check user id and password
	pCommentor = mpUsers->GetAndCheckUserAndPassword(pTempUser->GetUserId(), pPassword, mpStream);
	if (pCommentor == NULL)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Check the commentor's status.  If the commentor is
	// suspended, don't let him/her leave feedback.
	if (pCommentor->IsSuspended())
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You are not allowed to follow up on Feedback while you are "
						"suspended."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	pCommentorFeedback = pCommentor->GetFeedback();
	if (pCommentorFeedback != NULL)
	{
		feedbackScore = pCommentorFeedback->GetScore();
		if (feedbackScore <= -4)
		{
			*mpStream <<	"<b>Sorry!</b>"
							"<br>"
							"You are not allowed to follow up on Feedback if your Feedback "
							"rating is "
					  <<	feedbackScore
					  <<	"."
							"<p>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}
	}

	pFeedback = mpUser->GetFeedback();

	// insert the follow-up comment; AddFollowUp will check whether the comment is there
	if (!pFeedback->AddFollowUp(CommentorId, CommentDate, pFollowUp))
	{
		*mpStream	<<	ErrorMsgHasFollowUp
					<< "<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// done
	*mpStream	<<	"<h2>Follow-up Comment Has Been Recorded</h2>"
				<<	"Your follow-up to the feedback you left for "
				<<	"<font color=\"green\">"
				<<	pCommentee
				<<	"</font>"
				<<	" has been recorded. Click "
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewFeedbackLeft)
				<<	"eBayISAPI.dll?ViewFeedbackLeft&userid="
				<<	pCommentor->GetUserId()
				<<	"&pass="
				<<	pCommentor->GetPasswordNoSalt()
				<<	"\">here</a> to view an updated listing of feedback "
				<<	"you've left for other eBay users."
				<<	"<p>"
				<<	mpMarketPlace->GetFooter();

	delete pTempUser;
	delete pCommentor;

	CleanUp();
}
