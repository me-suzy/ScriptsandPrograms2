/*	$Id: clseBayAppAdminViewBlockedItem.cpp	*/
//
//	File:		clseBayAppAdminViewBlockedItem.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay)
//
//	Function:
//
//
//	Modifications:
//				- 04/21/99 loul	- Created - taken from clseBayAppItem.cpp
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//


#include "ebihdr.h"
#include "vector.h"
#include "clsUserIdWidget.h"
#include "clseBayItemDetailWidget.h"

#define CHECKED(x)	(!strcmp(x,"on"))

static const char eBayBlockedItemAppealEmailAddress[] = "itemapl@ebay.com";

bool clseBayApp::GetAndCheckBlockedItem(char *pItemNo, char *pRowNo, time_t delta)
{
	int		item;

	// Ok, let's get started
	if (pItemNo)
	{
		item	= atoi(pItemNo);
		mpItem	= mpItems->GetItem(item, true, pRowNo, delta, true);
	}

	// If we did't get the item, then put out a 
	// nice error message.
	if (!mpItem || item == 0)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" - Invalid Item"
						"</TITLE>"
						"</HEAD>";

		*mpStream <<	mpMarketPlace->GetHeader();

		*mpStream <<	"<p>"
						"<H2>"
						"Item \""
				  <<	pItemNo
				  <<	"\" is invalid or could not be found."
						"</H2>"
						"<p>"
						"Please go back and try again.";

		*mpStream <<	mpMarketPlace->GetFooter();

		*mpStream <<	flush;
		return false;
	}

	return true;
}

void clseBayApp::AdminViewBlockedItem(CEBayISAPIExtension *pServer,
										LPSTR pItemNo, LPSTR pRowNo,
										time_t delta,
										eBayISAPIAuthEnum authLevel,
										CHttpServerContext* pCtxt)
{
	// Time fields
	time_t	startTime;
	time_t	endTime;

	struct tm *timeAsTm;

	char	cStartTime[96];
	char	cEndTime[96];
	char	titleEndTime[96];
	char	*cleanTitle;

	// Pointer to category name
	char	*pCategory;

	clsUserIdWidget*	pUserIdWidget;

	char	*pNewDescription = NULL;

	// Setup
	SetUp();
	// Dynamic Cobrand

	// Let's try and get the item, set last param to true for blocked items
	if (!GetAndCheckBlockedItem(pItemNo, pRowNo, delta))
	{
		CleanUp();
		return;
	}

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";

	// We'll need a page title here
	*mpStream <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" item "
			  <<	pItemNo
			  <<	" - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>";

	*mpStream <<	mpMarketPlace->GetHeader();

	// Finalize
	mpItem->Finalize();

	// First, we convert the times to nice strings
	//  start time
	startTime	= mpItem->GetStartTime();
	timeAsTm	= localtime(&startTime);
	if (timeAsTm)
	{
		if (timeAsTm->tm_isdst)
		{
			strftime(cStartTime, sizeof(cStartTime),
					 "%m/%d/%y %H:%M:%S PDT ",
					  timeAsTm);
		}
		else
		{
			strftime(cStartTime, sizeof(cStartTime),
					 "%m/%d/%y %H:%M:%S PST ",
					  timeAsTm);
		}
		
	}
	else
		strcpy(cStartTime, "(*Error*)");

	// end time
	endTime		= mpItem->GetEndTime();
	timeAsTm	= localtime(&endTime);
	if (timeAsTm)
	{
		if (timeAsTm->tm_isdst)
		{
			strftime(cEndTime, sizeof(cEndTime),
					 "%m/%d/%y %H:%M:%S PDT ",
					  timeAsTm);
		}
		else
		{
			strftime(cEndTime, sizeof(cEndTime),
					 "%m/%d/%y %H:%M:%S PST ",
					  timeAsTm);
		}

		if (timeAsTm->tm_isdst)
		{
			strftime(titleEndTime, sizeof(titleEndTime),
					 "%m/%d/%y %H:%M:%S PDT",
					 timeAsTm);
		}
		else
		{
			strftime(titleEndTime, sizeof(titleEndTime),
					 "%m/%d/%y %H:%M:%S PST",
					 timeAsTm);
		}
	}
	else
		strcpy(cEndTime, "(*Error*)");

	// The Category could contain a number of leading
	// ':' characters, which is a side affect of the query
	// used to retrieve it. Let's skip past them.
	pCategory	= mpItem->GetCategoryName();

	if (pCategory)
	{
		for (;
			 *pCategory == ':';
			 pCategory++)
		{
			;
		}
	}
	else
		pCategory	= "";

	//clean up title
	cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
	if (!cleanTitle)
		return;

	// Show Title Bar
	*mpStream	<<	"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n";

	// The title and item #
	*mpStream	<<	"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font color=\"#000000\">"
					"<b>"
				<<	cleanTitle
				<<	"</b></font></td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=3 color=\"red\">"
					"<b>Blocked </b></font>"
					"<font size=3 color=\"#000000\">"
					"<b>Item #"
				<<	mpItem->GetId();

	*mpStream	<<	"</b></font></td>\n"
					"</tr>\n";

	*mpStream	<<	"</table></center>\n";

	delete [] cleanTitle;

	// for category row
	*mpStream	<<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=\"100%\">\n";
	*mpStream	<<	"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=2 color=\"#000000\">"
				<<	pCategory
				<<	"</a></font>"
					"</td></tr>\n"
					"</table></center>\n";

	// spacer
	// kakiyama 07/16/99
	*mpStream   <<  "<img src=\""
				<< mpMarketPlace->GetPicsPath()
				<< "dot_clear.gif\" "
					"width=\"1\" vspace=\"2\" border=\"0\">";

	//add image icon for regular view item page-- vicki

	// Begin table tag
	*mpStream	<<	"<center><table border=0 cellpadding=0 "
					"cellspacing=0 width=\"100%\">\n"
					"<tr>\n";

	//show desc icon
	*mpStream	<<	"<td width=\"13%\" "
				<<	"rowspan=\"17\" "
				<<	"valign=\"top\" align=\"left\">";
	*mpStream	<<	"</td>\n";
				
	// Auction properties (current bid, etc..) in a 6-column table


	// Create row for bid $ & first bid
	*mpStream	<<	"<td width=\"13%\"><font size=2>"
						"Starts at"
						"</font></td>\n"
						"<td width=\"31%\"><b>$"
				<<	mpItem->GetStartPrice()
				<<	"</b>";

	// optionally add reserve price auction message next to the bid price
	if (mpItem->GetReservePrice() != 0)
	{

		// show state of reserve auction
		*mpStream	<<	"&nbsp;"
						"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/buyerguide/bidding-type.html#reserve"
						"\"><font size=2>"
					<<	((RoundToCents(mpItem->GetPrice())
						>= RoundToCents(mpItem->GetReservePrice())) ?
						"(reserve met)" : 
						"(reserve not yet met)")
					<<	"</font>"
						"</a>";	


	}

	// Finish off the bid price
	*mpStream	<<	"</td>\n"
					"<td width=\"1%\"></td>\n";

	// Create cell for first bid $
	*mpStream	<<	"<td width=\"10%\"><font size=2>"
					"First bid"
					"</font></td>\n"
					"<td width=\"45%\">$"
				<<	mpItem->GetStartPrice()
				<<	"</td>\n";

	// Finish off the row for bid $ and first bid
	*mpStream	<<	"</tr>\n";


	// Create row for quantity & # of bids
	*mpStream	<<	"<tr>\n"
					"<td width=\"13%\"><font size=2>"
					"Quantity"
					"</font></td>\n"
					"<td width=\"31%\"><b>"
				<<	mpItem->GetQuantity()
				<<	"</b></td>\n"
					"<td width=\"1%\"></td>\n"
					"<td width=\"10%\"><font size=2>"
					"# of bids"
					"</font></td>\n"
					"<td width=\"45%\"><b>"
				<<	mpItem->GetBidCount()
				<<	"</b>"
					"<font size=2>"
				<<	flush;

	// Finish off the table cell for bids, and the row too
	*mpStream	<<	"</font></td></tr>\n";


	// Create row for time left & location
	*mpStream	<<	"<tr>\n"
				<<	"<td width=\"13%\"><font size=2>"
				<<	"Time left</font></td>\n"
				<<	"<td width=\"31%\"><b>"
				<<	"<font color=\"red\">"
				<<	"Auction has been Blocked."
				<<	"</font></b></td>"
					"<td width=\"1%\"></td>\n"
					"<td width=\"10%\"><font size=2>"
					"Location"
					"</font></td>\n"
					"<td width=\"45%\"><b>"
				<<	mpItem->GetLocation()
				<<	"</b></td>\n"
					"</tr>\n";

	// Create row for auction start date & email auction
	*mpStream	<<	"<tr>\n"
					"<td width=\"13%\"><font size=\"2\">"
					"Started"
					"</font></td>\n"
					"<td width=\"31%\"><font size=\"2\">"
				<<	cStartTime
				<<	"</font></td>\n";


	// Create row for auction end date 
	*mpStream	<<	"<tr>"
					"<td width=\"13%\"><font size=2>"
					"Blocked"
					"</font></td>\n"
					"<td width=\"31%\"><font size=2>"
				<<	cEndTime
				<<	"</font></td>\n";



	// Include high bidder user ID for Chinese auctions only
	if (mpItem->GetQuantity() == 1 && mpItem->GetHighBidder() != 0 &&
		!mpItem->GetPrivate())
	{
		*mpStream	<< "&userid="
					<<	mpItem->GetHighBidderUserId();
	}


	//  Optional cells for featured auction note
	if (mpItem->GetSuperFeatured() || mpItem->GetFeatured())
	{
		*mpStream	<<	"<tr>\n"
						"<td width=\"13%\"></td>\n"
						"<td width=\"31%\"></td>\n"
						"<td width=\"1%\"></td>\n"
						"<td width=\"10%\" colspan=\"2\"><font size=\"2\"><b>\n";
						
		if (!mpItem->GetFeatured())
			*mpStream	<<	"Featured Auction</font></b>";
		else if (!mpItem->GetSuperFeatured())
			*mpStream	<<	"Featured Category Auction</font></b>";
			else *mpStream	<<	"Featured & Featured Category Auction</font></b>";

		*mpStream	<<	"</td>\n"
						"</tr>\n";
	}

	//added vertical space if need show seller
	*mpStream	<<	"<tr>"
					"<td width=\"13%\"></td>"
					"<td width=\"31%\"></td>"
					"<td width=\"1%\"></td>"
					"<td width=\"10%\">"
// kakiyama 07/16/99
				<<  "<img src=\""
				<<  mpMarketPlace->GetPicsPath()
				<<  "dot_clear.gif\" "
					"width=\"1\" vspace=\"4\" border=\"0\"></td>"
					"<td width=\"45%\"></td>"
					"</tr>";

	// Create row for seller
	*mpStream	<<	"<tr>\n"
					"<td width=\"13%\"><font size=2>"
					"Seller"
					"</font></td>\n"
					"<td width=\"31%\" colspan=4>";

	// Show the seller userid using widget
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
	pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
									mpItem->GetSellerEmail(),
									UserStateEnum(mpItem->GetSellerUserState()),
									mpMarketPlace->UserIdRecentlyChanged(mpItem->GetSellerIdLastModified()),
									NULL,
									NULL);
	pUserIdWidget->SetUserIdBold(true);
	pUserIdWidget->SetShowUserStatus(true);
	pUserIdWidget->SetShowMask(true);
	pUserIdWidget->SetShowFeedback(false);
	pUserIdWidget->SetShowStar(false);
	pUserIdWidget->SetShowAboutMe(false);
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;

	// Finish off seller row
	*mpStream	<<	"</td>\n"
					"</tr>\n";	



	//add vertical space
	*mpStream	<<	"<tr>"
					"<td width=\"13%\"></td>"
					"<td width=\"31%\"></td>"
					"<td width=\"1%\"></td>"
					"<td width=\"10%\">"
// kakiyama 07/16/99
					"<img src=\""
				<<  mpMarketPlace->GetPicsPath()
				<<  "dot_clear.gif\" "
					"width=\"1\" vspace=\"4\" border=\"0\"></td>"
					"<td width=\"45%\"></td>"
					"</tr></table>\n";

	// Optional description
	if (mpItem->GetDescription() != NULL)
		pNewDescription	= clsUtilities::ChangeHTMLQuoteToQuote(mpItem->GetDescription());
	else
		pNewDescription	= NULL;

	*mpStream	<<	"<center><table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n";


	// Description headline
	*mpStream	<<	"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>"
					"Description"
					"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n";

	*mpStream	<<	"\n";
		
	*mpStream	<< "<blockquote>\n";

	if (pNewDescription)
		*mpStream << pNewDescription;

	*mpStream	<<	"\n</blockquote>\n";

	// We can get rid of these paranoid close tags once we put DrawSafeHTML back in
	*mpStream	<<	"</blockquote>"
					"</blockquote>"
					"</center>"
					"</center>"
					"</strong>"
					"</pre>"
					"</em>"
					"</font>"
					"</dl>"
					"</ul>"
					"</li>"
					"</h1>"
					"</h2>"
					"</h3>"
					"</h4>"
					"</h5>"
					"</h6>"
					"\n";

	delete [] pNewDescription;

	// Spacer
	*mpStream	<<	"<br>";


	// Add link to reinstate item
	*mpStream	<<	"<p>"
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminReinstateAuctionShow)
				<<	"eBayISAPI.dll?AdminReinstateAuctionShow&action=0&item="
				<<	mpItem->GetId() 
				<<	"\">"
				<<	"Reinstate this Auction."
				<<	"</a>\n";

	// Add link to Deny Appeal item
	*mpStream	<<	"<p>"
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminReinstateAuctionShow)
				<<	"eBayISAPI.dll?AdminReinstateAuctionShow&action=1&item="
				<<	mpItem->GetId() 
				<<	"\">"
				<<	"Deny Appeal for this Auction."
				<<	"</a>\n";

	// Spacer
	*mpStream	<<	"<p>";

	*mpStream	<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;
}

