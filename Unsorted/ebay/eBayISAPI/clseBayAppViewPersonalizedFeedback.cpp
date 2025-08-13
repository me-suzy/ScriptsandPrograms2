/*	$Id: clseBayAppViewPersonalizedFeedback.cpp,v 1.7.2.1.102.2 1999/08/05 20:42:27 nsacco Exp $	*/
//
//	File:	clseBayAppViewPersonalizedFeedback.cc
//
//	Class:	clseBayApp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to retrieve and
//		show personalized view of all feedback for
//		a user.
//
// Modifications:
//				- 08/13/98 mila		- Created
//				- 08/13/98 mila		- Fixed table space for response/follow-up
//									  comments
//				- 08/14/98 mila		- color feedback items based on date relative
//									  to feature implementation date; fixed bug
//									  in displaying item number in feedback item;
//									  changed page title; change mRebuttal to
//									  mResponse and mResponse to mFollowUp; fixed
//									  bug that displayed wrong feedback items
//				- 08/15/98 mila		- make pagination controls work with both regular
//									  and personalized feedback pages; add links to
//									  pagination controls
//				- 08/22/98 mila		- change links to Leave Feedback and Respond to
//									  Feedback pages from static to dynamic
//				- 08/24/98 mila		- added missing endquote
//				- 08/26/98 mila		- restrict response comments to feedback left
//									  after new feature implementation date
//				- 09/22/98 mila		- change how feedback item bg color is set;
//									  check both encrypted and unencrypted
//									  passwords
//				- 10/01/98 mila		- lighten blue bg color; get rid of white
//									  gap between adjacent blue table cells in
//									  feedback item
//				- 10/12/98 mila		- changed #define of NEW_FEEDBACK_FEATURE_DATE
//									  to static const int NewFeedbackFeatureDate
//				- 10/16/98 mila		- modified PrintPersonalizedFeedbackItem to attach a
//									  link to the item number only if the item is
//									  still in the database and it's not an adult
//									  item
//				- 10/30/98 mila		- added code to check for adult cookie
//				- 11/06/98 mila		- Changed page title and header to eliminate word
//									  "personalized"
//				- 11/06/98 mila		- changed to use abbreviated time zone names 
//									  when outputting feedback items; removed border
//									  from response icon
//				- 11/06/98 mila		- modified PrintPersonalizedFeedbackItem to fix
//									  problem with missing background in empty
//									  <td>...</td> in Netscape; fixes bug #675
//				- 11/10/98 mila		- changed feature implementation date to 11/23/98;
//									  deleted new feature message at bottom of feedback
//									  list; don't display user status as part of user ID
//									  widget at top of page; deleted code to hide the
//									  commenting user's info in feedback item if
//									  that user's feedback is private
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"

#include <time.h>

#include "hash_map.h"

#include "clseBayTimeWidget.h"


//static const int NewFeedbackFeatureDate = 897634800;	// 6/12/98 00:00:00 -- test
//static const int NewFeedbackFeatureDate = 918720000;	// 2/11/99 00:00:00 -- prod
static const int NewFeedbackFeatureDate = 918806400;

static const char *BgColorLightBlue	= "#cff0ff";
static const char *BgColorLightGray	= "#efefef";


void clseBayApp::PrintPersonalizedFeedbackItem(ostream *mpStream,
											   clsUser *pUser,
					   						   clsFeedbackItem *pItem,
											   int startingPage,
											   int itemsPerPage,
											   bool honorHidden)

{
	// Interesting formatting things
	char					*pSafeText;
	clsItem					item;
	bool					bIsAdultItem;
	bool					bHasAdultCookie;

	clsUserIdWidget*		pUserIdWidget;

	const int				userDivWidth = pItem->mItem > 0 ? 82 : 100;


	//samuel au, 4/6/99

	// Start table for feedback items.
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

	// Output user info.
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pItem->mCommentingUserId, 
								pItem->mCommentingEmail,
								UserStateEnum(pItem->mCommentingUserState),
								mpMarketPlace->UserIdRecentlyChanged(pItem->mCommentingUserIdLastModified),
								pItem->mCommentingUserScore,
								pItem->mCommentingUserFlags);
	pUserIdWidget->SetShowAboutMe();
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;
	
	// Output date & time.

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

	// Output item number if transaction-based.  
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

		// End of item number.
		*mpStream	<<	"          </td>"
					<<	"\n";
	}

	// End of table feedback info row of table.
	*mpStream	<<	"        </tr>"
				<<	"\n"
				<<	"      </table>"
				<<	"\n";

	// Start new table for feedback comment.
	*mpStream	<<	"      <table WIDTH=\"100%\" BGCOLOR=\"#ffffff\">"
				<<	"\n"
				<<	"        <tr>"
				<<	"\n"
				<<	"          <td WIDTH=\"97%\">"
				<<	"\n";

	*mpStream	<<	"            <p><strong>";

	// Output Complaint, Praise, or Neutral, with color coding.
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

	// Pass the text through a filter to make it "safe".
	pSafeText	= clsUtilities::StripHTML(pItem->mText);

	*mpStream <<	pSafeText
			  <<	"</p>"
			  <<	"\n"
			  <<	"            </td>"
			  <<	"\n"
			  <<	flush;

	delete pSafeText;

	// Make room for a response icon.
	*mpStream <<	"            <td WIDTH=\"3%\" ALIGN=\"right\">"
			  <<	"\n";

	if (strlen(pItem->mResponse) == 0 && pItem->mTime >= NewFeedbackFeatureDate)
	{
		*mpStream	<<	"              <A HREF=\""
					<<	mpMarketPlace->GetCGIPath(PageRespondFeedbackShow)
					<<	"eBayISAPI.dll?RespondFeedbackShow"
					<<	"&commentor="
					<<	pItem->mCommentingId
					<<	"&time="
					<<	pItem->mTime
					<<	"&commentee="
					<<	pItem->mId
					<<	"&page="
					<<	startingPage
					<<	"&items="
					<<	itemsPerPage
					<<	"\">"
					<<	"\n"
					<<	"                <img border =\"0\" src=\""
					<<	mpMarketPlace->GetPicsPath(PageRespondFeedbackShow)
//					<<	"file:C:\\E108_feedback\\pics\\"
					<<	"feedback_reply.gif\" width=\"23\" height=\"25\" align=\"absmiddle\">"
					<<	"\n"
					<<	"              </A>";
	}
	else
	{
		*mpStream	<<	"              &nbsp;";
	}

	*mpStream <<	"\n"
			  <<	"            </td>"
			  <<	"\n"
			  <<	"          </tr>"
			  <<	"\n";

	// output response and follow-up comments, if any
	if (strlen(pItem->mResponse) > 0)
	{
		// Start new table row for feedback response.
		*mpStream	<<	"          <tr>"
					<<	"\n"
					<<	"            <td WIDTH=\"100%\" ALIGN=\"left\">"
					<<	"\n";

		*mpStream <<	"              <i>Response</i>: ";

		pSafeText = clsUtilities::StripHTML(pItem->mResponse);

		*mpStream <<	pSafeText
				  <<	"\n"
				  <<	flush;
		delete pSafeText;

		*mpStream	<<	"            </td>"
					<<	"\n"
					<<	"          </tr>"
					<<	"\n";

		// follow-up comment cannot exist without an existing response
		// to same feedback
		if (strlen(pItem->mFollowUp) > 0)
		{
			// Start new table row for feedback follow-up.
			*mpStream	<<	"          <tr>"
						<<	"\n"
						<<	"            <td ALIGN=\"left\">"
						<<	"\n";

			*mpStream	<<	"              <i>Follow-up</i>: ";

			pSafeText = clsUtilities::StripHTML(pItem->mFollowUp);

			*mpStream	<<	pSafeText
						<<	"\n"
						<<	"            </td>"
						<<	"\n"
						<<	"          </tr>"
						<<	"\n"
						<<	flush;
			delete pSafeText;
		}
	}
	
	// Output table stuff.
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
// GetAndShowPersonalizedFeedback
//
//	This routine actually retrieves and emits
//	the feedback a user has received. It's a seperate
//	method so that it can be called independantly
//	of ViewPewrsonalizedFeedback. The latter emits a <TITLE>
//	and other goodies
//
void clseBayApp::GetAndShowPersonalizedFeedback(clsUser *pUser,
												int startingPage,
												int itemsPerPage,
												bool honorHidden)
{
	// Our Feedback object
	clsFeedback						*pFeedback;

	// Feedback about the user
	FeedbackItemVector				*pvItems;
	FeedbackItemVector::iterator	i;

	bool				newFeaturesMessagePrinted = false;

	// Pagination variables.
	int					itemStart;
	int					itemCount;

	int					endingItem;
	int					totalItems;

	int					index;

	// We need a feedback object to do this
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	= pUser->GetFeedback();

	// Pagination calculation
	itemStart	= (startingPage - 1) * itemsPerPage + 1;
	itemCount	= itemsPerPage;

	// Get the feedback we've left, if we have a feedback object.
	if (pFeedback)
		pvItems = pFeedback->GetItems(itemStart, itemCount, &totalItems);

	// Let's see if there's anything to do here
	if (pFeedback == NULL || pvItems->empty())
	{
		*mpStream <<	"\n"
						"<h2>"
						"No Feedback has been left for "
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

	// Let's get the vector of feedback items
	if (startingPage == 1)
	{
		pvItems		= pFeedback->GetItems(itemStart, 0, &totalItems);
	}
	else
	{
		pvItems		= pFeedback->GetItems(itemStart, itemCount, &totalItems);
	}

	if (pFeedback->GetFlag() & FEEDBACK_FLAG_HIDE)
	{
		itemStart	= 1;
		itemCount	= 0;
	}
	else
	{
		itemStart	= (startingPage - 1) * itemsPerPage + 1;
		itemCount	= itemsPerPage;
	}

	*mpStream	<<	"\n"
					"<H2>"
					"Review and Respond to Feedback Comments Left for You"
					"</H2>"
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
					<<	mpMarketPlace->GetPicsPath(PageRespondFeedbackShow)
//					<<	"file:C:\\E108_feedback\\pics\\"
					<<	"feedback_reply.gif\" width=\"23\" height=\"25\" align=\"absmiddle\">"
			  <<	" ) below to respond<br>"
			  <<	"to feedback left for you.</p>"
			  <<	"\n"
			  <<	"      </div>"
			  <<	"\n"
			  <<	"    </td>"
			  <<	"\n"
			  <<	"  </tr>"
			  <<	"\n"
			  <<	"</table>"
			  <<	"\n";

	// Do calculations for pagination.
	endingItem		= itemsPerPage == 0 ? 
					  totalItems : min(itemsPerPage * startingPage, totalItems);

	// Display pagination controls.
	PrintFeedbackPaginationControl(mpStream, itemStart, endingItem,
								   totalItems, itemsPerPage, true,
								   PageViewPersonalizedFeedback, pUser);

	// List all the feedback items.
			for (i = pvItems->begin(), index = itemStart;
				 i != pvItems->end() && index <= endingItem;
				 i++, index++)
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
		
		PrintPersonalizedFeedbackItem(mpStream, mpUser, *i, startingPage, itemsPerPage, honorHidden);
		
	}

	// Display pagination controls.
	PrintFeedbackPaginationControl(mpStream, itemStart, endingItem,
								   totalItems, itemsPerPage, false,
								   PageViewPersonalizedFeedback, pUser);

	*mpStream <<	"<p>"
					"This feedback is ordered most-recent first. "
					"Each comment is attributed to its author, "
					"who takes full responsibility for the comment. "
					"If you have any questions or concerns about a "
					"particular comment, please contact the author "
					"directly, using the mail link provided with "
					"the author's "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"."
					"<p>"
					"\n";

	CleanUp();

	return;

}





//
// ViewFeedback
//
void clseBayApp::ViewPersonalizedFeedback(CEBayISAPIExtension *pThis,
										  char *pUserId,
										  char *pPass,
										  int startingPage,
										  int itemsPerPage)
										 // bool honorHidden
{

	SetUp();


	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Review and Respond to Feedback Left for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream,
												true, NULL, false, false, false, true);
	if (mpUser == NULL)
	{
		*mpStream <<	"<b>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	GetAndShowPersonalizedFeedback(mpUser, startingPage, itemsPerPage, true);


	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

