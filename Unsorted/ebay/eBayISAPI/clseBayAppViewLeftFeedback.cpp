/*	$Id: clseBayAppViewLeftFeedback.cpp,v 1.11.2.1.102.2 1999/08/05 20:42:26 nsacco Exp $	*/
//
//	File:	clseBayAppViewLeftFeedback.cc
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods user to retrieve
//		and show all feedback LEFT by a user.
//
// Modifications:
//				- 05/29/97 michael	- Created
//				- 08/13/98 mila		- new feedback forum stuff
//				- 08/14/98 mila		- color feedback items based on date relative
//									  to feature implementation date; fixed bug
//									  in displaying item number in feedback item;
//									  changed page title; change mRebuttal to
//									  mResponse and mResponse to mFollowUp
//				- 08/22/98 mila		- change links to Leave Feedback and Follow Up
//									  on Feedback pages from static to dynamic
//				- 08/24/98 mila		- added missing endquote
//				- 08/26/98 mila		- restrict follow up comments to feedback left
//									  after new feature implementation date
//				- 09/22/98 mila		- change how feedback item bg color is set;
//									  check both encrypted and unencrypted
//									  passwords
//				- 10/01/98 mila		- lighten blue bg color; get rid of white
//									  gap between adjacent blue table cells in
//									  feedback item
//				- 10/12/98 mila		- changed #define of NEW_FEEDBACK_FEATURE_DATE
//									  to static const int NewFeedbackFeatureDate
//				- 10/16/98 mila		- modified PrintFeedbackItemLeft to attach a
//									  link to the item number only if the item is
//									  still in the database and it's not an adult
//									  item
//				- 10/30/98 mila		- added code to check for adult cookie
//				- 11/06/98 mila		- changed to use abbreviated time zone names 
//									  when outputting feedback items; removed
//									  from follow-up icon
//				- 11/06/98 mila		- modified PrintFeedbackItemLeft to fix
//									  problem with missing background in empty
//									  <td>...</td> in Netscape; fixes bug #675
//				- 11/10/98 mila		- changed feature implementation date to 11/23/98;
//									  deleted new feature message at bottom of feedback
//									  list; deleted code to hide the commenting user's 
//									  info in feedback item if that user's feedback
//									  is private.
//				- 12/04/98	Wen		- added checking to remove duplicate feedback items.
//				- 01/08/99 mila		- Changed ViewFeedbackLeft to check only
//									  user ID - not password - to re-open
//									  "back door" on My eBay page.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "hash_map.h"
#include "clseBayTimeWidget.h"


//static const int NewFeedbackFeatureDate = 897634800;	// 6/12/98 00:00:00 -- test
//static const int NewFeedbackFeatureDate = 918720000;	// 2/11/99 00:00:00 -- prod
static const int NewFeedbackFeatureDate = 918806400;

static const char *BgColorLightBlue	= "#cff0ff";
static const char *BgColorLightGray	= "#efefef";


void clseBayApp::PrintFeedbackItemLeft(ostream *mpStream,
					   				   clsFeedbackItem *pItem,
									   bool honorHidden)

{
	// Interesting formatting things
// petra	time_t					theTimeT;
// petra	struct tm *				pTheTime;
// petra	char					cTheTime[40];
	char *					pSafeText;
// petra	clseBayTimeWidget		theTimeWidget;
// petra	TimeZoneEnum			timeZone;

	clsItem					item;
	bool					bIsAdultItem;
	bool					bHasAdultCookie;

	clsUserIdWidget *		pUserIdWidget;
	clsUser *				pUser;

	const int				userDivWidth = pItem->mItem > 0 ? 82 : 100;

// petra	theTimeT	= pItem->mTime;
// petra	pTheTime	= localtime(&theTimeT); //yp

	//samuel au, 4/6/99
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	theTimeWidget.SetTime(theTimeT);
// petra	theTimeWidget.SetTimeZone(timeZone);

// petra	if (pTheTime->tm_isdst)
// petra	{
// petra		strftime(cTheTime, sizeof(cTheTime),
// petra				 "%m/%d/%y %H:%M:%S PDT ",
// petra				 pTheTime);
// petra	}
// petra	else
// petra	{
// petra		strftime(cTheTime, sizeof(cTheTime),
// petra				 "%m/%d/%y %H:%M:%S PST ",
// petra				 pTheTime);
// petra	}

	// output table stuff
	if (pItem->mTime > NewFeedbackFeatureDate)	// light blue background
	{
		*mpStream	<<	"<table WIDTH=\"100%\" BORDER=\"1\">"
					<<	"\n"
					<<	"  <tr>"
					<<	"\n"
					<<	"    <td>"
					<<	"\n"
					<<	"      <table WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"0\">"
					<<	"\n"
					<<	"        <tr>"
					<<	"\n"
					<<	"          <td WIDTH=\""
					<<	userDivWidth
					<<	"%\" ALIGN=\"left\" BGCOLOR=\""
					<<	BgColorLightBlue
					<<	"\">"
					<<	"\n"
					<<	"            <div ALIGN=\"left\"><b>User:</b> ";
	}
	else	// light gray background
	{
		*mpStream	<<	"<table WIDTH=\"100%\" BORDER=\"1\">"
					<<	"\n"
					<<	"  <tr>"
					<<	"\n"
					<<	"    <td>"
					<<	"\n"
					<<	"      <table WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"0\">"
					<<	"\n"
					<<	"        <tr>"
					<<	"\n"
					<<	"          <td WIDTH=\""
					<<	userDivWidth
					<<	"%\" ALIGN=\"left\" BGCOLOR=\""
					<<	BgColorLightGray
					<<	"\">"
					<<	"\n"
					<<	"            <div ALIGN=\"left\"><b>User:</b> ";
	}

	pUser = mpUsers->GetUser(pItem->mCommentingId);

	// output user 
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pItem->mCommentingUserId, 
								pItem->mCommentingEmail,
								pUser->GetUserState(),
								mpMarketPlace->UserIdRecentlyChanged(pItem->mCommentingUserIdLastModified),
								pItem->mCommentingUserScore,
								pItem->mCommentingUserFlags);
	pUserIdWidget->SetShowAboutMe();
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;
	delete pUser;
	
	// output date & time

	*mpStream	<<	" <b>Date:</b> ";

	clseBayTimeWidget theTimeWidget (mpMarketPlace,					// petra
									 EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									 EBAY_TIMEWIDGET_LONG_TIME,		// petra
									 pItem->mTime);					// petra
	//samuel au, 4/6/99
	theTimeWidget.EmitHTML(mpStream);

	*mpStream	<<	"            </div>"
				<<	"\n"
				<<	"          </td>"
				<<	"\n";

	// output item number if transaction-based
	if (pItem->mItem > 0)
	{
		if (pItem->mTime > NewFeedbackFeatureDate)	// light blue background
		{
			*mpStream	<<	"          <td BGCOLOR=\""
						<<	BgColorLightBlue
						<<	"\" ALIGN=\"left\" WIDTH=\"18%\">";
		}
		else	// light gray background
		{
			*mpStream	<<	"          <td BGCOLOR=\""
						<<	BgColorLightGray
						<<	"\" ALIGN=\"left\" WIDTH=\"18%\">";
		}

		if (gApp->GetDatabase()->GetItem(pItem->mItem, &item, NULL, 0))
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
							<<	pItem->mItem
							<<	"\">"
							<<	pItem->mItem
							<<	"</A>"
							<<	"\n";
			}
			else if (bIsAdultItem && !bHasAdultCookie)
			{
				// Don't provide a link to the view item page if the
				// item is an adult item but the user doesn't have an
				// adult cookie.
				*mpStream	<<	"<b>Item:</b> "
							<<	pItem->mItem
							<<	"\n";
			}
		}
		else
		{
			// Don't provide a link to the view item page if the item
			// is no longer in the database.
			*mpStream	<<	"<b>Item:</b> "
						<<	pItem->mItem
						<<	"\n";
		}

		// end of item number
		*mpStream	<<	"          </td>"
					<<	"\n";
	}

	// end of table feedback info row of table
	*mpStream	<<	"        </tr>"
				<<	"\n"
				<<	"      </table>"
				<<	"\n";

	// start new table for feedback comment
	*mpStream	<<	"      <table WIDTH=\"100%\" BGCOLOR=\"#ffffff\">"
				<<	"\n"
				<<	"        <tr>"
				<<	"\n"
				<<	"          <td WIDTH=\"97%\">"
				<<	"\n";

	*mpStream	<<	"            <p><strong>";

	// output Complaint, Praise, or Neutral, with color coding
	switch (pItem->mType)
	{

		case FEEDBACK_NEGATIVE:
			*mpStream << "<font color=red>Complaint</font>:"
							 "</strong>"
							 " ";
			break;
		case FEEDBACK_POSITIVE:
			*mpStream << "<font color=green>Praise</font>:"
							 "</strong>"
							 "    ";
			break;
		case FEEDBACK_NEUTRAL:
		case FEEDBACK_NEGATIVE_SUSPENDED:
		case FEEDBACK_POSITIVE_SUSPENDED:
			*mpStream << "Neutral:"
							 "</strong>"
							 "   ";
			break;
		default:
			*mpStream << ":"
							 "</strong>"
							 "          ";
			break;
	}

	// Pass the text through a filter to make it "safe"
	pSafeText	= clsUtilities::StripHTML(pItem->mText);

	*mpStream <<	pSafeText
			  <<	"</p>"
			  <<	"\n"
			  <<	"            </td>"
			  <<	"\n"
			  <<	flush;

	// Make room for a follow-up icon.
	*mpStream <<	"            <td WIDTH=\"3%\">"
			  <<	"\n"
			  <<	"              &nbsp;"
			  <<	"\n"
			  <<	"            </td>"
			  <<	"\n"
			  <<	"          </tr>"
			  <<	"\n";

	delete pSafeText;

	// output response and follow-up comments, if any
	if (strlen(pItem->mResponse) > 0)
	{
		// start new table row for feedback response
		*mpStream	<<	"          <tr><td WIDTH=\"90%\" ALIGN=\"left\">";

		*mpStream <<	"<i>Response</i>: ";

		pSafeText = clsUtilities::StripHTML(pItem->mResponse);
		*mpStream <<	pSafeText
				  <<	"\n"
				  <<	flush;
		delete pSafeText;

		*mpStream	<<	"            </td>"
						"\n"
						"            <td WIDTH=\"10%\" ALIGN=\"right\">"
						"\n";

		if (strlen(pItem->mFollowUp) == 0 && pItem->mTime >= NewFeedbackFeatureDate)
		{
			*mpStream	<<	"              <A HREF=\""
						<<	mpMarketPlace->GetCGIPath(PageFollowUpFeedbackShow)
						<<	"eBayISAPI.dll?FollowUpFeedbackShow"
						<<	"&commentor="
						<<	pItem->mId
						<<	"&time="
						<<	pItem->mTime
						<<	"&commentee="
						<<	pItem->mCommentingId
						<<	"\">"
						<<	"\n"
						<<	"                <img border =\"0\" src=\""
						<<	mpMarketPlace->GetPicsPath(PageFollowUpFeedbackShow)
//						<<	"file:C:\\E102_feedback\\pics\\"
						<<	"feedback_reply.gif\" width=\"23\" height=\"25\" align=\"absmiddle\">"
						<<	"\n"
						<<	"              </A>";
		}
		else
		{
			*mpStream	<<	"              &nbsp;";
		}

		// follow-up comment cannot exist without an existing response
		// to same feedback
		if (strlen(pItem->mFollowUp) > 0)
		{
			// start new table row for feedback follow-up
			*mpStream	<<	"          <tr><td ALIGN=\"left\">";

			*mpStream	<<	"<i>Follow-up</i>: ";

			pSafeText = clsUtilities::StripHTML(pItem->mFollowUp);
			*mpStream	<<	pSafeText
						<<	"          </td>\n"
							"        </tr>"
							"\n"
						<<	flush;
			delete pSafeText;
		}
	}
	
	// output table stuff
	*mpStream	<<	"      </table>"
				<<	"\n"
				<<	"    </td>"
				<<	"\n"
				<<	"  </tr>"
				<<	"\n"
				<<	"</table>"
				<<	"\n";
		
	return;


}



//
// GetAndShowFeedbackLeft
//
//	This routine actually retrieves and emits
//	the feedback a user has received. It's a seperate
//	method so that it can be called independantly
//	of ViewFeedback. The latter emits a <TITLE>
//	and other goodies
//
void clseBayApp::GetAndShowFeedbackLeft(clsUser *pUser,
										bool honorHidden)
{
	// Our Feedback object
	clsFeedback						*pFeedback;

	clsUserIdWidget					*pUserIdWidget;

	// Feedback about the user
	FeedbackItemVector				*pvItems;
	FeedbackItemVector::iterator	i;

	bool							newFeaturesMessagePrinted = false;


	// We need a feedback object to do this
	// DON'T delete the pFeedback object because clsUser will do it.
	pFeedback	= pUser->GetFeedback();

	// Get the feedback we've left, if we have a feedback object.
	if (pFeedback)
		pvItems = pFeedback->GetItemsLeft();

	// Let's see if there's anything to do here
	if (pFeedback == NULL || pvItems->empty())
	{
		*mpStream <<	"\n"
						"<h2>"
						"No Feedback has been left by "
						"<A HREF="
				  <<	mpMarketPlace->GetCGIPath(PageGetUserEmail)
				  <<	"eBayISAPI.dll?GetUserEmail&userid="
                  <<     pUser->GetUserId()
				  <<	"\">"
				  <<	pUser->GetUserId()
				  <<    "</a>"
				  <<	"</h2>";

		return;
	}
	*mpStream	<<	"\n"
					"<H2>"
				<<	pvItems->size()
				<<	" Feedback Comments Left by ";

	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);

	pUserIdWidget->SetShowFeedback(true);

	pUserIdWidget->SetUserInfo(pUser->GetUserId(), 
								pUser->GetEmail(),
								pUser->GetUserState(),
								mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
								pFeedback->GetScore(),
								pUser->GetUserFlags());
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetShowAboutMe();
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;

	*mpStream <<	"</H2>"
					"\n";

	*mpStream	<<	"You can "
					"<A HREF=\""
				<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
				<<	"eBayISAPI.dll?LeaveFeedbackShow"
					"&useridfrom="
				<<	pUser->GetUserId()
				<<	"\">"
					"leave feedback"
					"</A>"
				<<	" for any other eBay user.  ";
	*mpStream	<<	"Visit the "
					"<A HREF=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/forum/feedback.html\">"
					"Feedback Forum"
					"</A>"
					" for more info on feedback profiles."
					"<br><br>"
					"\n";

	*mpStream <<	"<table border=\"0\" width=\"100%\">"
			  <<	"\n"
			  <<	"  <tr>"
			  <<	"\n"
			  <<	"    <td width=\"55%\">"
			  <<	"\n"
			  <<	"      <div align=\"center\"></div>"
			  <<	"\n"
			  <<	"    </td>"
			  <<	"\n"
			  <<	"    <td width=\"45%\" align=\"center\">"
			  <<	"\n"
			  <<	"      <div align=\"right\">"
			  <<	"\n"
			  <<	"        <p align=\"center\">Click on a "
			  <<	"( <img border =\"0\" src=\""
					<<	mpMarketPlace->GetPicsPath(PageFollowUpFeedbackShow)
//					<<	"file:C:\\E102_feedback\\pics\\"
					<<	"feedback_reply.gif\" width=\"23\" height=\"25\" align=\"absmiddle\">"
			  <<	" ) below to follow up<br>"
			  <<	"on feedback you left.</p>"
			  <<	"\n"
			  <<	"      </div>"
			  <<	"\n"
			  <<	"    </td>"
			  <<	"\n"
			  <<	"  </tr>"
			  <<	"\n"
			  <<	"</table>"
			  <<	"\n";

	for (i = pvItems->begin();
		  i != pvItems->end();
		  i++)
	{
		// If this is the first feedback item after the
		// new feature implementation date, then put in
		// the new feature message.
		if (!newFeaturesMessagePrinted && (*i)->mTime < NewFeedbackFeatureDate)
		{
			PrintNewFeedbackFeaturesMessage(mpStream);
			newFeaturesMessagePrinted = true;
		}

		// Get the user and feedback object
		
		//*mpStream <<	"<pre>";
		// check whether the item has been displayed
		if (i == pvItems->begin() || (*i)->mTime != (*(i-1))->mTime)
			PrintFeedbackItemLeft(mpStream, *i, honorHidden);
		//*mpStream <<	"</pre>";
		
	}

    *mpStream << "<p>"
				 "This feedback is ordered most-recent first. Each comment is "
				 "attributed to its author who takes full responsibility for the "
				 "comment. If you have any questions or concerns about a particular "
				 "comment, please contact the author directly using the e-mail link "
				 "provided with the author's "
			  << mpMarketPlace->GetLoginPrompt()
			  << "."
				 "<p>"
				 "\n";

	*mpStream << "<hr><p>"
				 "<strong>You can follow up directly on feedback you left for "
				 "another eBay user.</strong><br>"
				 "If you left feedback for someone, the person can respond to your "
				 "feedback. If the person leaves a response, you have an opportunity "
				 "to leave a follow-up comment. This is as far as the dialog can "
				 "take place within a single block of feedback."
				 "\n";


/*				"<strong>You are responsible for your own words.</strong><br>"
				"eBay cannot take responsibility for the comments you post here. "
				"No comment can or will be removed once it is submitted. Per our "
				"legal disclaimer, we cannot edit public postings. Once left, "
				"Feedback CANNOT be retracted. eBay cannot remove a comment once "
				"it is submitted. eBay cannot edit a Feedback Profile. "
				"<p>"
				"<strong>You regret a comment you made. </strong> <br>"
				"If you have left a negative comment and have been able to "
				"resolve your misunderstanding, we encourage you to leave a "
				"neutral Feedback comment for that person and explain that "
				"the misunderstanding has been resolved. "
				"<p>"
				"eBay cannot remove a comment once it is submitted, or edit a "
				"Feedback Profile. For this reason, we encourage you to contact "
				"your trading partner directly by e-mail or by telephone "
				"<i>before</i> leaving a negative Feedback comment. "
				"Generally, a misunderstanding or dispute can be resolved civilly "
				"by telephone. You can request another person's contact information "
				"at "
				"<A HREF=\""
		  <<	mpMarketPlace->GetHTMLPath()
		  <<	"search/members/user-query.html\">"
		  <<	mpMarketPlace->GetHTMLPath()
		  <<	"search/members/user-query.html"
				"</A>\n";
*/
	// Ending stuff already in ViewFeedbackLeft
//	*mpStream	<<	"\n"
//				<< mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

//
// ViewFeedbackLeft
//
void clseBayApp::ViewFeedbackLeft(CEBayISAPIExtension *pThis,
								  char *pUserId,
								  char *pPass)
								 // bool honorHidden
{

	SetUp();

	// Title
	*mpStream <<	"<html><head>\n"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Feedback Comments Left by "
			  <<	pUserId
			  <<	"</title>\n"
					"</head>\n"
			  <<	mpMarketPlace->GetHeader();

#if 0	// password required -- closes "back door" on My eBay page (mila)
		mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream,
													 true, NULL, false, false, false,
													 true);
#else	// don't bother checking the password
		mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
#endif

	if (!mpUser)
	{
		*mpStream <<	"<b>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	GetAndShowFeedbackLeft(mpUser, true);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

