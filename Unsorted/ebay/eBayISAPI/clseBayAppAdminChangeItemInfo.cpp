/*	$Id: clseBayAppAdminChangeItemInfo.cpp,v 1.17.2.3.90.3 1999/08/06 20:31:51 nsacco Exp $	*/
//
//	File:		clseBayAppAdmChangeItemInfo.cpp
//
//	Class:		clseBayApp
//
//	Author:		Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/18/97 vicki	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 08/05/99 nsacco	- Added html and head tags as needed.

#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "hash_map.h"

void clseBayApp::ChangeItemInfo(CEBayISAPIExtension *pCtxt, char *pItemNo)
							 
{
		// Time fields
	time_t	startTime;
	time_t	endTime;
	time_t	curtime;
	time_t	diffTime;
	time_t  newEndTime;

	struct tm *timeAsTm;

	char	cStartTime[64];
	char	cEndTime[64];
	char	titleEndTime[64];
	char	cDiffTime[64];
	char    cNewEndTime[64];
	
	char	cEndTimeHour[32];
	char	cEndTimeMin[32];
	char	cEndTimeSec[32];

	int giftIconType;
	char pGiftIcon[5];
	
	const char *pCleanDescription = NULL;

	// Pointer to category name
	char	*pCategory;
   	int     i;
	clsUserIdWidget	*pUserIdWidget;

	SetUp();

		// Let's get the item
    if (!GetAndCheckItem(pItemNo))
	{
	  // nsacco 08/05/99
	  *mpStream <<	"<html><head><title>Error</title></head>"
					"<body>ERROR MESSAGE</body></html>";
	  CleanUp();
	  return ;
	}


	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

	// Added by Charles
	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);

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

	//ending date, hour, min, and sec
	strftime(cEndTime, sizeof(cEndTime),
				 "%m/%d/%y ",
				  timeAsTm);
		
	strftime(cEndTimeHour, sizeof(cEndTime),
		 "%H ",
			  timeAsTm);
	strftime(cEndTimeMin, sizeof(cEndTime),
		 "%M ",
			  timeAsTm);
	strftime(cEndTimeSec, sizeof(cEndTime),
		 "%S ",
			 timeAsTm);

	// diff time
	int days, hours, minutes, seconds;
	curtime		= time(0);
	diffTime	= endTime - curtime;
	if (diffTime < 0)
	{
		strcpy(cDiffTime, "Auction has ended.");
	}
	else
	{
		days	= diffTime / 86400;
		hours	= (diffTime % 86400) / 3600;
		minutes	= (diffTime % 3600) / 60;
		seconds	= diffTime % 60;
		sprintf(cDiffTime, "%d days, %d hours, %d minutes, %d seconds", 
			days, hours, minutes, seconds);
	}

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

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a page title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" item "
			  <<	pItemNo  //or using pItemNo if it declared as char *pItemNo
			  <<	" (Ends "
			  <<	titleEndTime
			  <<	") - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE></head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

		// Spacer
	*mpStream <<	"<br>"; 

	*mpStream <<    "<h2>Admin Version Item Information and Manipulation</h2>\n"	
			  <<	"<form method=post action=\""
			  <<    mpMarketPlace->GetAdminPath()
			  <<    "eBayISAPI.dll?ItemInfo\">\n"
			  <<    "<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ItemInfo\">";

	
	// Title using black on darkgrey table
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#CCCCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>"
			 //<<	mpItem->GetTitle()
              <<	"<input type=text name=title value=\""
			  <<    mpItem->GetTitle()
			  <<    "\" size=45>\n"
			  <<	"</b></font></td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=3 color=\"#000000\">"
					"<b>"
					"Item #"
			  <<	pItemNo
			  <<	"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n";


	// Link to Bidding, or not
	if (curtime < endTime)
	{
		// simple 3-column table to do left/right margins
		*mpStream <<	"<p>"
						"<table border=0 cellpadding=0 "
						"cellspacing=0 width=100%>\n"
						"<td width=2%></td>\n"
						"<td width=96%>"
						"</td>\n"
						"<td width=2%></td>\n"
						"</table>"
						"</p>\n";
	}
	else
	{
		*mpStream <<	"<p align=center>"
						"<b>"
						"Bidding is closed for this item."
						"</b>"
						"</p>"
						"\n";
	}

// Auction properties (current bid, etc..) in a 4-column table

	// Begin table tag
	*mpStream <<	"<center><table border=0 cellpadding=0 "
					"cellspacing=0 width=100%>\n";

	// Create row for bid $
	if (mpItem->GetBidCount() > 0 && mpItem->GetPrice() > 0)
	{
		// show current/lowest bid because there are bids
		*mpStream <<	"<tr>\n"
						"<td width=8%></td>\n"
						"<td width=25% valign=top valign=top><font size=2>"
				  <<	((mpItem->GetQuantity() > 1) ? 
						"Lowest bid" :
						"Current bid")
				  <<	"</font></td>\n"
						"<td width=1%></td>\n"
						"<td width=66%><font size=4><b>";

		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		currencyWidget.EmitHTML(mpStream);

		*mpStream  <<	"</b></font>";
	}
	else
	{
		// show starting bid because there are no bids yet
		*mpStream <<	"<tr>\n"
						"<td width=8%></td>\n"
						"<td width=25% valign=top><font size=2>"
						"Bidding starts at"
						"</font></td>\n"
						"<td width=1%></td>\n"
						"<td width=66%><font size=4><b>";

		currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	"</b></font>";
	}
	// optionally add reserve price auction message next to the bid price
	if (mpItem->GetReservePrice() != 0)
	{
		*mpStream <<	" "
				//		"<a href=\""
				//  <<	mpMarketPlace->GetHTMLPath()
				//  <<	"help/buyerguide/bidding-type.html#reserve"
				//		"\">"
						"(reserve price auction)"
						"</a>";
	} 
	// Finish off the row for bid price
	*mpStream <<	"</td>\n</tr>\n";

// Create row for quantity
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Quantity"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3><b>"
			  <<	"<input type=text name=quantity value=\""
			  <<    mpItem->GetQuantity()
			  <<    "\" size=20>\n"
			  <<	"</b></font></td>\n"
					"</tr>\n";

	// Create row for diff auction time
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Time left"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3><b>"
			  <<	cDiffTime
			  <<	"</b></font></td>\n"
					"</tr>\n";

	// Create row for auction start date
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Auction started"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3>"
			  <<	cStartTime
			  <<	"</font></td>\n"
					"</tr>\n";

	// Create row for auction end date
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Auction ends"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3>"
	          <<    "<select name=cEndTime> <option value=\""
			  <<    cEndTime
			  <<    "\"selected>"
			  <<    cEndTime;

//new ending time, only support 15 days later from current time	
	for(i=0; i<=14; i++) {
	   newEndTime = (curtime + (i * 86400));
	   timeAsTm = localtime(&newEndTime);
	   strftime(cNewEndTime, sizeof(cNewEndTime),
				 "%m/%d/%y",
				  timeAsTm);
       *mpStream  <<    "<option value=\""
	   			  <<    cNewEndTime
				  <<    "\">"
	   			  <<    cNewEndTime;
	   
	}
	*mpStream <<    "</select>"
		      <<    "&nbsp;\n";

	//show seclect box for hour
	*mpStream <<	"<select name=cEndTimeHour>\n <option value=\""
			  <<    cEndTimeHour
			  <<    "\"selected>"
			  <<    cEndTimeHour;

	for(i=0; i<24; i++) 
	{
		*mpStream  <<    "<option value=\""
			      <<    i
			      <<    "\">";
		if (i < 10)
			*mpStream  << "0";
		*mpStream  <<	i
				   <<	"\n";
	}
	*mpStream <<    "</select>"
		      <<    ":\n";
	//Show select box for mins
	*mpStream <<	"<select name=cEndTimeMin>\n <option value=\""
			  <<    cEndTimeMin
			  <<    "\"selected>"
			  <<    cEndTimeMin;

	for(i=0; i<60; i++) 
	{
		*mpStream  <<    "<option value=\""
			      <<    i
			      <<    "\">";
		if (i < 10)
			*mpStream  << "0";
		*mpStream  <<	i
				   <<	"\n";
	}
	*mpStream <<    "</select>"
		      <<    ":\n";
	
	//show select box for sec
	*mpStream <<	"<select name=cEndTimesec> <option value=\""
			  <<    cEndTimeSec
			  <<    "\"selected>"
			  <<    cEndTimeSec;

	for(i=0; i<60; i++) 
	{
		*mpStream  <<    "<option value=\""
			      <<    i
			      <<    "\">";
		if (i < 10)
			*mpStream  << "0";
		*mpStream  <<	i
				   <<	"\n";
	}
	*mpStream <<    "</select>";
	if (timeAsTm->tm_isdst)
		*mpStream << "PDT";
	else
		*mpStream << "PST";
	//finish row for date
	*mpStream <<	"</font></td>\n"
					"</tr>\n";
	
	// Featured auction, featured category action
    
		*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Featured Auction?"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3>";

		
		if (mpItem->GetSuperFeatured()) 	{
			*mpStream << "<input type=checkbox name=superfeatured value =\"1\""
				      << "checked>";
		}           
		else {
		    *mpStream << "<input type=checkbox name=superfeatured  value =\"1\""
			       	 <<  " >";
		  
		}
	time_t when = time(0);		
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		*mpStream << "<font size=2>($49.95 charge)"
			         "</font></td>"
                     "</tr>\n";
	else	  
		*mpStream << "<font size=2>($99.95 charge)"
			         "</font></td>"
                     "</tr>\n";
		
        *mpStream << 	"<tr>\n"
					    "<td width=8%></td>\n"
					    "<td width=25% valign=top><font size=2>"
					    "Featured Category Auction?"
					    "</font></td>\n"
					    "<td width=1%></td>\n"
					    "<td width=66%><font size=3>";
		if (mpItem->GetFeatured()) {
		    *mpStream << "<input type=checkbox name=featured  value = \"1\""
					  <<  "checked>";			
		}
		else {
		    *mpStream << "<input type=checkbox name=featured value = \"1\""
				      << " >";	
		}
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		*mpStream <<    "<font size=2>($9.95 charge)"
			            "</font></td>"
                        "</tr>\n"; 
	else
		*mpStream <<    "<font size=2>($14.95 charge)"
			            "</font></td>"
                        "</tr>\n"; 

	//creat a row for gift icon section
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Gift Icon"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>";

	
	giftIconType=mpItem->GetGiftIconType();
	
	sprintf(pGiftIcon, "%d", giftIconType);

	EmitDropDownList(mpStream,
					 "giftIcon",
					 (DropDownSelection *)&GiftIconSelection,
					 pGiftIcon,
					 "0",
					 "Not Selected");

	*mpStream <<	"</td>\n"
					"</tr>\n";

	// 10-pixel vertical spacer
	// TODO - check this!!!
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<img src=\"http://www2.ebay.com/aw/pics/dot_clear.gif\" "
					"width=1 vspace=6 border=0>"
					"</td>\n"
					"</tr>\n";

	// Create row for seller
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Seller"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=4>";

	pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
								mpItem->GetSellerEmail(),
								UserStateEnum(mpItem->GetSellerUserState()),
								mpMarketPlace->UserIdRecentlyChanged(mpItem->GetSellerIdLastModified()),
								mpItem->GetSellerFeedbackScore());
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->EmitHTML(mpStream);

	// Finish off seller row
	*mpStream <<	"</td>\n</tr>\n";

	// Create row for viewing seller feedback
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<font size=2>"
					"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewFeedback)
			  <<	"eBayISAPI.dll?ViewFeedback&userid="
			  <<	mpItem->GetSellerUserId()
			  <<	"&page="
			  <<	1
			  <<	"&items="
			  <<	0
			  <<	"\">"
					"(view feedback on this seller)"
					"</a>"
					"</font>"
					"</td>\n"
					"</tr>\n";

	// Create row for viewing auctions by this seller
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<font size=2>"
					"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			  <<	"eBayISAPI.dll?ViewListedItems&userid="
			  <<	mpItem->GetSellerUserId()
			  <<	"\">"
					"(view other auctions by this seller)"
					"</a>"
					"</font>"
					"</td>\n"
					"</tr>\n";

	// Create row for asking the seller a question
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<font size=2>";

	pUserIdWidget->SetUserIdBold(false);
	pUserIdWidget->SetShowStar(false);
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetShowFeedback(false);
	pUserIdWidget->SetIncludeEmail(true);
	pUserIdWidget->SetDescription("(ask the seller a question)");
	pUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	"</font>"
					"</td>\n"
					"</tr>\n";

	// 10-pixel vertical spacer
	// TODO - check this
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<img src=\"http://www2.ebay.com/aw/pics/dot_clear.gif\" "
					"width=1 vspace=6 border=0>"
					"</td>\n"
					"</tr>\n";

	// Create row for high bidder
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
			  <<	((mpItem->GetQuantity() > 1) ? 
					"Current high bidders" :
					"Current high bidder")
			  <<	"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>";

	// if dutch, just show link to dutch bidders
	if (mpItem->GetQuantity() > 1)
	{
		*mpStream <<	"<font size=3><a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewBidsDutchHighBidder)
				  <<	"eBayISAPI.dll?ViewBidsDutchHighBidder&item="
				  <<	mpItem->GetId()
				  <<	"\""
						">"
						"click here for Dutch Auction bidders"
						"</a></font>";
	}
	// if not dutch, show high bidder (unless it's a private auction)
	else 
	{
		if (!mpItem->GetPrivate())
		{
			if (mpItem->GetHighBidder() != 0)
			{

				if (*(mpItem->GetHighBidderUserId()))
				{
					*mpStream <<	"<font size=3>";

					pUserIdWidget->SetUserInfo(mpItem->GetHighBidderUserId(), 
											mpItem->GetHighBidderEmail(),
											UserStateEnum(mpItem->GetHighBidderUserState()),
											mpMarketPlace->UserIdRecentlyChanged(mpItem->GetHighBidderIdLastModified()),
											mpItem->GetHighBidderFeedbackScore());
					pUserIdWidget->SetIncludeEmail(true);
					pUserIdWidget->EmitHTML(mpStream);
				}
			}
		}
		else
		// it's private, so just show note
		{
			*mpStream <<	"<font size=3><a href=\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"help/buyerguide/bidding-type.html#private"
							"\""
							">"
							"private auction"
							"</a>"
							" -- bidders' identities protected"
							"</font>";
		}	
	}

	// Finish off high bidder row
	*mpStream <<	"</td>\n"
					"</tr>\n";
	
	// Create row for number of bids
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Number of bids made"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=4><b>"
			  <<	mpItem->GetBidCount()
			  <<	"</b></font>"
					"<font size=2>"
					" "
					"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBids)
			  <<	"eBayISAPI.dll?ViewBids&item="
			  <<	pItemNo
			  <<	"\">"
					"(view bidding history)"
					"</a>"
					"</font>"
					"</td>\n"
					"</tr>\n";


	// 10-pixel vertical spacer
	// TODO check this
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%>"
					"<img src=\"http://www2.ebay.com/aw/pics/dot_clear.gif\" "
					"width=1 vspace=6 border=0>"
					"</td>\n"
					"</tr>\n";

	// Create row for first bid $
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"First bid"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3>";

	currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
	currencyWidget.EmitHTML(mpStream);

	*mpStream  <<	"</font></td>\n"
					"</tr>\n";

	// Create row for item #
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Item #"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3>"
			  <<	pItemNo
			  <<	"</font></td>\n"
					"</tr>\n";

	// Create row for location
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Location of item"
					"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=4><b>"
			  <<	mpItem->GetLocation()
			  <<	"</b></font></td>\n"
					"</tr>\n";

	// Create row for location
	*mpStream <<	"<tr>\n"
					"<td width=8%></td>\n"
					"<td width=25% valign=top><font size=2>"
					"Category"
			  <<	"</font></td>\n"
					"<td width=1%></td>\n"
					"<td width=66%><font size=3><b>"
					"<a href=\""
			  <<	mpCategories->GetLinkPath(mpItem->GetCategory())
			  <<	"\""
					">"
			  <<	pCategory
			  <<	"</a>"
			  <<	"</b></font>"
					"</td>\n"
					"</tr>\n";

	//  Optionally create row for featured auction note
	if (mpItem->GetSuperFeatured() || mpItem->GetFeatured())
	{
		*mpStream <<	"<tr>\n"
						"<td width=8%></td>\n"
						"<td width=25% valign=top></td>\n"
						"<td width=1%></td>\n"
						"<td width=66%><font size=3><b>";
		if (!mpItem->GetFeatured())
			*mpStream << "Featured Auction";
		else if (!mpItem->GetSuperFeatured())
			*mpStream << "Featured Category Auction";
			else *mpStream << "Featured & Featured Category Auction";

		*mpStream <<"</td>\n"
						"</tr>\n";
	}
	else
	{
		*mpStream << 	"</b></font>"
						"</td>\n"
						"</tr>\n";	
	}

	// If auction has ended, allow seller to relist this item easily
	if (diffTime < 0)
	{

		// 10-pixel vertical spacer
		// TODO - check this
		*mpStream <<	"<tr>\n"
						"<td width=8%></td>\n"
						"<td width=25% valign=top></td>\n"
						"<td width=1%></td>\n"
						"<td width=66%>"
						"<img src=\"http://www2.ebay.com/aw/pics/dot_clear.gif\" "
						"width=1 vspace=6 border=0>"
						"</td>\n"
						"</tr>\n";

		//  Create row for relisting
		*mpStream <<	"<tr>\n"
						"<td width=8%></td>\n"
						"<td width=25% valign=top><font size=2>"
						"Relist this item"
						"</font></td>\n"
						"<td width=1%></td>\n"
						"<td width=66%><font size=2>"
				  <<	"<b>Note to seller:</b> " 
				  <<	"Now that the auction has ended, you can easily "
						"relist this item by "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageListItemForSale)
				  <<	"eBayISAPI.dll?ListItemForSale&item="
				  <<	mpItem->GetId()
				  <<	"\""
						">"
						"clicking here"
						"</a>"
						"."
				  <<	"</font></td>\n"
						"</tr>\n";
	}


	// End table tag
	*mpStream <<	"</table></center>";

    *mpStream <<    "<input type=hidden name=action value=\"ChangeInfo\">"
			  <<    "<blockquote><input type=hidden name=item value = \""
			  <<     mpItem->GetId()
			  <<    "\" size=12></blockquote>\n "
			  <<	"<p>\n"
			  <<	"<strong>Description of the item.</strong> "
			  <<	"<blockquote><textarea name=description cols=70 rows=10 >";

	char *pDesc = mpItem->GetDescription();

	if (pDesc)
	{
		pCleanDescription = clsUtilities::DrawSafeHTML(mpItem->GetDescription());
		if (pCleanDescription)
			*mpStream << pCleanDescription;
		else
			*mpStream << mpItem->GetDescription();
	}

	*mpStream <<    "</textarea></blockquote>";


	// Gallery Item handling
	
	*mpStream << "<strong>Gallery picture</strong><br>";
	*mpStream <<	"<p>Note: At this time, the gallery checkboxes are NOT toggles. They are only"
					" used to turn features <i>off</i>, not on. Turning off GalleryFeatured only will credit"
					" the account for $"
				<< mpItem->GetFeaturedGalleryFee()
				<< ", but then chargeback $"
				<< mpItem->GetGalleryFee()
				<< " for normal Gallery insertion. Turning off Gallery if the item was GalleryFeatured will credit them for $"
				<< mpItem->GetFeaturedGalleryFee()
				<< ".</p>";

	*mpStream << "<br><center><table border=0 cellpadding=0 "
					"cellspacing=0 width=100%>";

	// gallery featured
	*mpStream <<	"<tr>\n"
		"<td width=8%></td>\n"
		"<td width=25% valign=top><font size=2>"
		"Gallery Featured?"
		"</font></td>\n"
		"<td width=1%></td>\n"
		"<td width=66%><font size=3>";
	
	if (mpItem->GetGalleryType() == FeaturedGallery) 	{
		*mpStream << "<input type=checkbox name=galleryfeatured value=\"1\""
			<< " checked>";
	}           
	else {
		*mpStream << "<input type=checkbox name=galleryfeatured value=\"1\""
			       	 <<  ">";
	}
	*mpStream << "<font size=2>($"
		<< mpItem->GetFeaturedGalleryFee()
		<< "charge)"
		"</font></td>"
		"</tr>\n";
	
	// gallery 
	*mpStream <<	"<tr>\n"
		"<td width=8%></td>\n"
		"<td width=25% valign=top><font size=2>"
		"Include picture in Gallery?"
		"</font></td>\n"
		"<td width=1%></td>\n"
		"<td width=66%><font size=3>";
	
	if (mpItem->GetGalleryType() == Gallery || mpItem->GetGalleryType() == FeaturedGallery) 	{
		*mpStream << "<input type=checkbox name=gallery value=\"1\""
			<< " checked>";
	}           
	else {
		*mpStream << "<input type=checkbox name=gallery value=\"1\">";

		// what if it's UnGallery or UnFeaturedGallery???
	}
	
	*mpStream << "<font size=2>($ "
		<< mpItem->GetGalleryFee()
		<< " charge)"
		"</font></td>"
		"</tr>\n";
	
	// End table tag
	*mpStream <<	"</table></center><br>";

	// Image
	// now get the thumbnail image and display it
	*mpStream << "<table border=\"0\" width=\"100%\" >"
		<< "<tr>";
	if(mpItem->GetGalleryURL() != NULL) 
		*mpStream << "<td align=center>Thumbnail</td><td align=center>Original Image</td>";
	else if(mpItem->GetPictureURL() != NULL) 
		*mpStream << "<td align=center>Thumbnail</td><td align=center>Original Image<br>(from picture URL)</td>";
	else
		*mpStream << "<td align=center>Thumbnail</td><td align=center>Original Image<br>(none)</td>";
	
	*mpStream<< "</tr><tr>"
		<< "<td align=center><img src=\"http://thumbnails.ebay.com/pict/"
//		<< "<td align=center><img src=\"http://pete.corp.ebay.com/pict/"
//		<< "<td align=center><img src=\"http://mangrove.ebay.com/pict/"
		<<	pItemNo
		<< ".jpg\"></td>";
	
	// get the image form the user's URL
	*mpStream << "<td align=center><img src=\"";
	
	// do we have a gallery image?
	if(mpItem->GetGalleryURL() == NULL) {
		if(mpItem->GetPictureURL() != NULL)
			*mpStream << mpItem->GetPictureURL();
		else
			*mpStream << "";
	} else	if(mpItem->GetGalleryURL() != NULL)
		*mpStream << mpItem->GetGalleryURL();
	else
		*mpStream << "";
	
	*mpStream		<< "\"></td>"
		<< "</tr>"
		<< "</table>"		
		<< flush;
	
	
	
	

	// close off form
	*mpStream		<<	"<p>" 
			  <<	"<p>"
			  <<	"<strong>Press this button to submit:</strong>"
			  <<	"<blockquote><input type=submit value=\"submit\"></blockquote>"
			  <<	"<p>"
			  <<	"</form>";

	
	
	
#if 0
	*mpStream << "<br>USE THIS vv TO REMOVE PICTURE FROM GALLERY FOR THE TIME BEING!<br>";
	
	// form for deletion
	*mpStream << "<br><form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemDeleteConfirm
		<<	"eBayISAPI.dll?\">\n"
		"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemDeleteConfirm\""
		<< "\">\n<input TYPE=\"hidden\" NAME=\"item\" VALUE=\""
		<< pItemNo
		<< ">\n"
		"<b> Click "
		"<input type=\"submit\" value=\"here\"> "
		"to delete the Gallery picture for this item. </b>\n"
		"</form>\n";
#endif
	

	
	*mpStream <<	mpMarketPlace->GetFooter();

	delete pUserIdWidget;					
	delete (char *) pCleanDescription;
	CleanUp();
	return ;
}
