/*	$Id: clseBayItemDetailWidget.cpp,v 1.13.2.25.2.3 1999/08/05 18:58:47 nsacco Exp $	*/
//
//	File:	clseBayItemDetailWidget.cpp
//
//	Class:	clseBayItemDetailWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits item deails.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 11/18/97	Poon	- Created
//				- 05/26/98	vicki	- new layout
//				- 12/16/98	mila	- excluded bidder's user id from to-seller
//									  link when auction is private
//				- 02/23/99	kaz		- Relist Item now checks feedback rating to bypass 
//									  BetterSellerPage
//				- 02/23/99  sam		- iescrow login link
//				- 04/05/99  steve	- Added "Contact eBay" link in the listing item view page
//				- 04/20/99  sam 	- Reduced i-Escrow eligibility to $750 from $1000
//				- 04/20/99  kaz		- Fixed Relisting an item so it points to the right cgi pool
//				- 05/06/99	nsacco	- Added T & C if user will only ship to home country
//									- Removed old, unused ShippingToCanada code
//				- 05/15/99  alexp	- UI changes
//				- 06/23/99  jen     - Revert to E117 (original UI layout) with New IA 
//									  header and footer
//				- 07/01/99	nsacco	- switch to using GetNamePlural() for currencies and
//									  use GetPicsPath for all image paths
//				- 07/08/99  sam 	- Reduced i-Escrow eligibility to $200 from $750
//				- 05/24/99	nsacco	- Fixed table column widths to equal 100%
//				- 07/27/99	nsacco	- Added display code for new shipping conditions
//				- 07/30/99	nsacco	- Replaced NewItemQuick with ListItemForSale
//				- 08/05/99	nsacco	- Display currency conversion.


#include "widgets.h"
#include "clseBayItemDetailWidget.h"
#include "clsUserValidation.h"
#include "clsCurrencyWidget.h"
#include "clseBayTimeWidget.h"

#include <stdio.h>

// nsacco 07/27/99
// removed statics sNewURL and sGiftURL

static const char *sDynamicGiftAlertNotice = "ViewGiftAlert";
static const char *sGiftAlertIcon = "gift-icon.gif";

clseBayItemDetailWidget::clseBayItemDetailWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
	mpItem = NULL;
	mColor[0] = '\0';
	mMode = Generic;
	mShowTitleBar = true;
	mShowDescription = false;
	mIsViewOldItemPage = false;
}


void clseBayItemDetailWidget::SetParams(vector<char *> *pvArgs)
{
	int p;
	char *cArg;
	char cArgCopy[256];
	char *cName;
	char *cValue;
	bool handled = false;
	int x;

	// reverse through these so that deletions are safe.
	//  stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// separate the name from the value
		strncpy(cArgCopy, cArg, sizeof(cArgCopy)-1);
		cName = cArgCopy;
		cValue = strchr(cArgCopy, '=');
		if (cValue) 
		{
			cValue[0]='\0';		// lock in cName
			cValue++;			// set cValue
		}
		else
			cValue="";

		// remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// try to handle this parameter
		if ((!handled) && (strcmp("mode", cName)==0))
		{
			SetMode((modeEnum)atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("color", cName)==0))
		{
			SetColor(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("showtitlebar", cName)==0))
		{
			SetShowTitleBar(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("showdescription", cName)==0))
		{
			SetShowDescription(strcmp(cValue,"true")==0);
			handled=true;
		}


		// if this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}
	}

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayWidget::SetParams(pvArgs);

}

bool clseBayItemDetailWidget::EmitHTML(ostream *pStream)
{
	// safety
	if (!mpItem) return false;
	if (!mpMarketPlace) return false;

	// Time fields
// petra	time_t	startTime;
	time_t	endTime;
	time_t	curtime;
	time_t	diffTime;
	//samuel au, 4/6/99
// petra	clseBayTimeWidget	startTimeWidget;	
// petra	clseBayTimeWidget	endTimeWidget;		
// petra	TimeZoneEnum		timeZone;
	//end

// petra	struct tm *timeAsTm;

// petra	char	cStartTime[96];
// petra	char	cEndTime[96];
// petra	char	titleEndTime[96];
	char	cDiffTime[128];
	char	*cleanTitle;

	// Pointer to category name
	char	*pCategory;

	char	*pSafeUserId;

	clsUserIdWidget*	pUserIdWidget;

	char	*pNewDescription = NULL;

	bool     guernseys = false;
	const int	kHighEnoughFeedback = 50;			// kaz: added 2/23/99
	int		iconType	= 0;

	// nsacco 08/05/99
	// get the current site
	clsSite* theSite = mpMarketPlace->GetSites()->GetCurrentSite();


	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

	// DO IT

	// Finalize
	mpItem->Finalize();

	// samuel au, 4/6/99
	// set time zone here
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
	//end

	// First, we convert the times to nice strings
	//  start time
// petra	startTime	= mpItem->GetStartTime();
// petra	timeAsTm	= localtime(&startTime);
	//samuel au, 4/6/99
// petra 	startTimeWidget.SetTime(startTime);
// petra	startTimeWidget.SetTimeZone(timeZone);
	//end
	clseBayTimeWidget startTimeWidget(mpMarketPlace, 1, 2, mpItem->GetStartTime());	// petra

// petra	if (timeAsTm)
// petra	{
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strfime(cStartTime, sizeof(cStartTime),
// petra					 "%m/%d/%y %H:%M:%S PDT ",
// petra					  timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(cStartTime, sizeof(cStartTime),
// petra					 "%m/%d/%y %H:%M:%S PST ",
// petra					  timeAsTm);
// petra		}
// petra 		
// petra	}
// petra	else
// petra		strcpy(cStartTime, "(*Error*)");

	// end time
	endTime		= mpItem->GetEndTime();
// petra	timeAsTm	= localtime(&endTime);
	//samuel au, 4/6/99
// petra	endTimeWidget.SetTime(endTime);
// petra	endTimeWidget.SetTimeZone(timeZone);	// timeZone already set above
	//end
	clseBayTimeWidget endTimeWidget (mpMarketPlace, 1, 2, endTime);	// petra

// petra	if (timeAsTm)
// petra	{
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%m/%d/%y %H:%M:%S PDT ",
// petra					  timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%m/%d/%y %H:%M:%S PST ",
// petra					  timeAsTm);
// petra		}
// petra
// petra		if (timeAsTm->tm_isdst)
// petra		{
// petra			strftime(titleEndTime, sizeof(titleEndTime),
// petra					 "%m/%d/%y %H:%M:%S PDT",
// petra					 timeAsTm);
// petra		}
// petra		else
// petra		{
// petra			strftime(titleEndTime, sizeof(titleEndTime),
// petra					 "%m/%d/%y %H:%M:%S PST",
// petra					 timeAsTm);
// petra		}
// petra	}
// petra	else
// petra		strcpy(cEndTime, "(*Error*)");

	// diff time
	int days, hours, minutes, seconds;
	curtime		= time(0);
	diffTime	= endTime - curtime;
	if (diffTime < 0)
	{
		strcpy(cDiffTime, "<font color=\"red\">Auction has ended.</font>");
	}
	else
	{
		days	= diffTime / 86400;
		hours	= (diffTime % 86400) / 3600;
		minutes	= (diffTime % 3600) / 60;
		seconds	= diffTime % 60;

		// format the time left in a "smart" way
		if (days > 0) 
			sprintf(cDiffTime, "%d days, %d hours +", days, hours);
		else if (hours > 0)
			sprintf(cDiffTime, "%d hours, %d mins +", hours, minutes);
		else
			sprintf(cDiffTime, "%d mins, %d secs", minutes, seconds);
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

	//clean up title
	cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
	if (!cleanTitle)
		return false;

	guernseys = (mpItem->GetSeller() == 2573939); // Guernsey's

	if (guernseys) 
	{
		*pStream  <<	"<table border=1 cellspacing=0 "
						"width=\"100%\" bgcolor=\""
				  <<	mColor
				  <<	"\">\n";

		*pStream  <<	"<tr>\n"
						"<td align=center width=\"100%\">"
						"<font size=\"5\" color=\"#000000\">"
						"<b>"
				    	"Guernsey\'s and eBay\'s Home Run Ball Auction";
	
		*pStream  <<	"</b></font></td></tr></table>\n";	
	}

	if (mShowTitleBar)
	{
		// Table tag for title and item #
		if (mColor[0]=='\0')
		{
			*pStream  <<	"<table border=1 cellspacing=0 "
							"width=\"100%\">\n";
		}
		else
		{
			*pStream  <<	"<table border=1 cellspacing=0 "
							"width=\"100%\" bgcolor=\""
					  <<	mColor
					  <<	"\">\n";
		}

		// The title and item #
		*pStream  <<	"<tr>\n"
						"<td align=center width=\"100%\">"
						"<font color=\"#000000\">"
						"<b>"
				  <<	cleanTitle
				  <<	" ("
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewItem)
				  <<	"eBayISAPI.dll?ViewItem&item="
				  <<	mpItem->GetId()
				  <<	"\">"
				  <<	mpItem->GetId()
				  <<	"</a>)";

		//pick up right gift icon image
		if (iconType != GiftIconUnknown)
		{
			if (iconType == RosieIcon)
				*pStream	<<	"<A HREF=\""
							<<	mpMarketPlace->GetMembersPath()
							<<	"aboutme/4allkids/\">";
			else
				*pStream	<<  "<A HREF=\""
							<<	mpMarketPlace->GetHTMLPath()
							<<  "help/buyerguide/gift-icon.html\">";

			*pStream <<  "<img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
					 <<	 mpMarketPlace->GetPicsPath()
					 <<	 mpMarketPlace->GetGiftIconImage(mpItem->GetGiftIconType())
					 << "\"></a>";
		}

		*pStream	  <<	"</b></font></td></tr></table>\n";		
	}
	else
	{
		// Title using black on darkgrey table

		// Begin table for title and item#
		*pStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>"
			  <<	cleanTitle 
			  <<	"</b></font></td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=3 color=\"#000000\">"
					"<b>Item #"
			  <<	mpItem->GetId();

	//pick up right gift icon image
		iconType = mpItem->GetGiftIconType();
		if (iconType != GiftIconUnknown)
		{
			if (iconType == RosieIcon)
			{
				*pStream	<<	"<A HREF=\""
							<<	mpMarketPlace->GetMembersPath()
							<<	"aboutme/4allkids/\">";
			}
			else
			{
				*pStream	<<  "<A HREF=\""
							<<	mpMarketPlace->GetHTMLPath()
							<<  "help/buyerguide/gift-icon.html\">";
			}

			*pStream	<<	"<img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"" //http://pics.ebay.com/aw"
						<<	mpMarketPlace->GetPicsPath()
						<<	mpMarketPlace->GetGiftIconImage(mpItem->GetGiftIconType())
						 <<	"\"></a>";
		}
	}
		*pStream	 <<	"</b></font></td>\n"
						"</tr>\n";
	// end the table
		*pStream  <<	"</table></center>\n";
	

	delete [] cleanTitle;

	// for category row
	*pStream <<		"<center>"
					"<table border=0 cellspacing=0 "
					"width=\"100%\">\n";
	*pStream <<		"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=2 color=\"#000000\">"
			  <<	"<a href=\""
			  <<	mpMarketPlace->GetCategories()->GetLinkPath(mpItem->GetCategory())
			  <<	"\""
			  <<	">"
			  <<	pCategory
			  <<	"</a></font>"
					"</td></tr>\n"
					"</table></center>\n";



	// spacer
	*pStream <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"dot_clear.gif\" "
					"width=\"1\" vspace=\"2\" border=\"0\">";

	// Link to Bidding, or not 
	if (diffTime < 0 && mShowDescription)
	{
		*pStream <<	"<center><align=center>"
						"<b>"
						"Bidding is closed for this item."
						"</b>"
						"</center>"
				 <<		"<img src=\""
				 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				 <<		"dot_clear.gif\" "
						"width=\"1\" vspace=\"2\" border=\"0\">"
						"\n";
	}

	// Auction properties (current bid, etc..) in a 6-column table

	// Begin table tag
	*pStream <<	"<center><table border=0 cellpadding=0 "
					"cellspacing=0 width=\"100%\">\n"
					"<tr>\n";

	//add image icon for regular view item page-- vicki
	//show desc icon
	*pStream <<	"<td width=\"13%\" ";
		if(mMode == Generic)
			*pStream <<	"rowspan=\"17\" ";
		else if ((mpItem->GetBidCount() > 0) && (mpItem->GetPassword() & ItemUpdated))
			*pStream <<	"rowspan=\"15\" ";
		else if ((diffTime > 0) && (mMode == Seller))
			*pStream <<	"rowspan=\"13\" ";
		else
			*pStream <<	"rowspan=\"13\" ";
	*pStream <<	"valign=\"top\" align=\"left\">";
				

	if(mShowDescription)
	{
		*pStream << "<a href=\""
				 <<	"#DESC\">";
	}
	else 
	{
		*pStream <<	"<a href=\""
				 <<	mpMarketPlace->GetCGIPath(PageViewItem)
				 <<	"eBayISAPI.dll?ViewItem&item="
				 <<	mpItem->GetId()
				 <<	"#DESC\">";
	}

	*pStream <<	"<img src="
				"\""
			 <<	mpMarketPlace->GetPicsPath();

	if(mShowDescription)
	{
		*pStream <<	"descriptionicon2arrow.gif";
		*pStream <<	"\""
					" width=\"60\" height=\"51\" vspace=\"12\" "
					"alt=\"Show description\" border=\"0\">"
					"</a><br>";
	}
	else
	{
		*pStream <<	"descriptionicon2.gif";
		*pStream <<	"\""
					" width=\"60\" height=\"50\" vspace=\"12\" "
					"alt=\"Show description\" border=\"0\">"
					"</a><br>";
	}


/*
	//show picture icon
	if  (mpItem->GetPictureURL() != NULL)
	{
		if(mShowDescription)
		{
			*pStream << "<a href=\""
					 <<	"#PIC\">";
		}
		else 
		{
		*pStream <<	"<a href=\""
				 <<	mpMarketPlace->GetCGIPath(PageViewItem)
				 <<	"eBayISAPI.dll?ViewItem&item="
				 <<	mpItem->GetId()
				 <<	"#PIC\">";
		}

		*pStream <<	"<img src="
					"\""
				 <<	"http://pics.ebay.com/aw/pics/";
		if(mShowDescription)
		{
			*pStream <<	"pictureicon2arrow.gif";
			*pStream <<	"\""
						" width=\"60\" height=\"37\" vspace=\"4\" "
						"alt=\"Show picture\""
    					"border=\"0\"></a><br>";
		}
		else
		{
			*pStream <<	"pictureicon2.gif";
			*pStream <<	"\""
						" width=\"60\" height=\"35\" vspace=\"4\" "
						"alt=\"Show picture\""
    					"border=\"0\"></a><br>";
		}


	}
	else
	{
		*pStream <<	"<img src="
					"\""
				 <<	"http://pics.ebay.com/aw/pics/"
				 <<	"dot_clear.gif"
					"\""
					" width=\"60\" height=\"38\" vspace=\"4\" "
					"alt=\"dot_clear.gif (564 bytes)\""
    				"border=\"0\"></a><br>";
	}
*/

	//show bid icon
	if(diffTime > 0)
	{
		if (mMode != Seller)	// if you're not the seller, then show the bid icon
		{
			if(mShowDescription)
			{
				*pStream << "<a href=\""
						 <<	"#BID\">";
			}
			else 
			{
				*pStream <<	"<a href=\""
					<<	mpMarketPlace->GetCGIPath(PageViewItem)
					<<	"eBayISAPI.dll?ViewItem&item="
					<<	mpItem->GetId()
					<<	"#BID\">";
			}
			
			*pStream <<	"<img src="
						"\""
					 <<	mpMarketPlace->GetPicsPath();	// nsacco 07/01/99
			if(mShowDescription)
			{
				*pStream <<	"bidicon2arrow.gif";
				*pStream <<	"\""
							" width=\"60\" height=\"60\" vspace=\"12\" "
							"alt=\"Bid!\" border=\"0\"></a><br>";
			}
			else
			{
				*pStream <<	"bidicon2.gif";
				*pStream <<	"\""
							" width=\"60\" height=\"56\" vspace=\"12\" "
							"alt=\"Bid!\" border=\"0\"></a><br>";
			}

		}
		else	// sellers don't need the bid icon
		{
/*			*pStream <<	"<img src="
						"\""
					 <<	"http://pics.ebay.com/aw/pics"
					 <<	"dot_clear.gif"
						"\""
						" width=\"60\" height=\"56\" vspace=\"12\" "
						"alt=\"\""
    					"border=\"0\"></a><br>";
*/
		}
	}

	// show feedback icon and links if the auction is closed
	if (diffTime <= 0) 
	{

		// make the icon point to the leave feedback page (no prefilled fields)
		*pStream <<	"<a href=\""
				 <<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
				 <<	"eBayISAPI.dll?LeaveFeedbackShow"
				 <<	"\""
					">"
					"<img src="
					"\""
				<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99	
				<<	"leavefeedbackicon.gif\" "
					"width=\"60\" height=\"56\" vspace=\"0\" "
					"alt=\"Leave feedback\" border=\"0\">"
				<<	"</a>";

		// change font
		*pStream << "<font size=\"2\">";

		// if the user is not the seller, then make a link to leaving feedback to the seller
		if (mMode != Seller)
		{
			// begin the a href
			*pStream	<<	"<br>"
							"&nbsp;&nbsp;<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
						<<	"eBayISAPI.dll?LeaveFeedbackShow&useridto="
						<<	mpItem->GetSellerUserId();

			// fill in useridfrom only if there is a _single_ high bidder...
			// AND the auction is not private!!
			if ((mpItem->GetQuantity() == 1) && (mpItem->GetHighBidder() != 0) &&
				(!mpItem->GetPrivate()))
			{
				*pStream	<<	"&useridfrom="
							<<	mpItem->GetHighBidderUserId();
			}

			// pass the item #
			*pStream	<<	"&item="
						<<	mpItem->GetId();

			// finish off a href
			*pStream	<<	"\">"
							"(to seller)"
							"</a>";
		}

		// if the user is not the bidder, then make a link to leaving feedback to the bidder
		if ((mMode != Bidder) && (mpItem->GetBidCount() > 0))
		{
			// chinese
			if ((mpItem->GetQuantity() == 1))
			{
				*pStream	<<	"<br>"
								"&nbsp;&nbsp;<a href=\""
							<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
							<<	"eBayISAPI.dll?LeaveFeedbackShow&useridfrom="
							<<	mpItem->GetSellerUserId();
				
				// if the auction isn't private then fill in the high bidder's user id
				if (!mpItem->GetPrivate())
				{
					*pStream	<<	"&useridto="
								<<	mpItem->GetHighBidderUserId();
				}

				// pass the item #
				*pStream	<<	"&item="
							<<	mpItem->GetId();

				*pStream	<<	"\">"
								"(to bidder)"
								"</a>";
			}
			// dutch
			else
			{
				*pStream	<<	"<br>"
								"&nbsp;&nbsp;<a href=\""
							<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
							<<	"eBayISAPI.dll?LeaveFeedbackShow&useridfrom="
							<<	mpItem->GetSellerUserId();


				// pass the item #
				*pStream	<<	"&item="
							<<	mpItem->GetId();

				
				*pStream	<<	"\">"
								"(to bidders)"
								"</a>";
			}
		}

		// end center and change font
		*pStream << "</font>";

	} 


	// Added "Contact eBay" link here.
/*
	*pStream << "<font size=\"3\">";
	*pStream	<<	"<br><br>"
					"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageContacteBay)
				<<	"eBayISAPI.dll?ContacteBay&item="
				<<	mpItem->GetId();

	
	*pStream	<<	"\">"
					"Contact eBay"
					"</a>";

	// end center and change font
	*pStream << "</font>";

*/

	*pStream <<	"</td>\n";

	// Create row for bid $ & first bid
	if (mpItem->GetBidCount() > 0 && mpItem->GetPrice() > 0)
	{
		// show current/lowest bid because there are bids
		*pStream  <<	"<td width=\"13%\"><font size=\"2\">"
				  <<	((mpItem->GetQuantity() > 1) ? 
						"Lowest" :
						"Currently")
				  <<	"</font></td>\n"
						"<td width=\"31%\">";				 	
	
		currencyWidget.SetNativeAmount(mpItem->GetPrice());
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(pStream);
		
		// nsacco 08/05/99
		// display converted amount if auction currency is different than site currency
		if (mpItem->GetCurrencyId() != theSite->GetDefaultListingCurrency())
		{
			

			*pStream  <<    "<font size=\"2\" color=\"#FF0000\" >"
					  <<    " (approx. ";

			// change the currency
			currencyWidget.SetNativeCurrencyId(theSite->GetDefaultListingCurrency());
			currencyWidget.SetNativeAmount(
			mpMarketPlace->GetCurrencies()->GetExchangeRates()->FromAmountTo(mpItem->GetCurrencyId(),
																			mpItem->GetPrice(),
																			theSite->GetDefaultListingCurrency(), 
																			mpItem->GetEndTime())
										);
			currencyWidget.EmitHTML(pStream);
			*pStream  <<    ")"
					  <<    "</font>";

			// restore the currency in the widget
			currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
		}
	}
	else
	{
		// show starting bid because there are no bids yet
		*pStream <<		"<td width=\"13%\"><font size=\"2\">"
						"Starts at"
						"</font></td>\n"
						"<td width=\"31%\">";

		currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(pStream);
		
		// nsacco 08/05/99
		// display converted amount if auction currency is different than site currency
		if (mpItem->GetCurrencyId() != theSite->GetDefaultListingCurrency())
		{
			

			*pStream  <<    "<font size=\"2\" color=\"#FF0000\" >"
					  <<    " (approx. ";

			// change the currency
			currencyWidget.SetNativeCurrencyId(theSite->GetDefaultListingCurrency());
			currencyWidget.SetNativeAmount(
				mpMarketPlace->GetCurrencies()->GetExchangeRates()->FromAmountTo(mpItem->GetCurrencyId(),
																				mpItem->GetStartPrice(),
																				theSite->GetDefaultListingCurrency(), 
																				mpItem->GetEndTime())
										);
			currencyWidget.EmitHTML(pStream);
			*pStream  <<    ")"
					  <<    "</font>";

			// restore the currency in the widget
			currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
		}
			
			
	}
	// optionally add reserve price auction message next to the bid price
	if (mpItem->GetReservePrice() != 0)
	{

		// show state of reserve auction
		*pStream	<<	"&nbsp;"
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
	*pStream <<	"</td>\n"
					"<td width=\"1%\"></td>\n";

	// Create cell for first bid $
	*pStream <<	"<td width=\"10%\"><font size=\"2\">"
					"First bid"
					"</font></td>\n"
					"<td width=\"45%\">";
	
	currencyWidget.SetNativeAmount(mpItem->GetStartPrice());
	currencyWidget.EmitHTML(pStream);

	// nsacco 08/05/99
	// display converted amount if auction currency is different than site currency
	if (mpItem->GetCurrencyId() != theSite->GetDefaultListingCurrency())
	{
		

		*pStream  <<    "<font size=\"2\" color=\"#FF0000\" >"
				  <<    " (approx. ";

		// change the currency
		currencyWidget.SetNativeCurrencyId(theSite->GetDefaultListingCurrency());
		currencyWidget.SetNativeAmount(
		mpMarketPlace->GetCurrencies()->GetExchangeRates()->FromAmountTo(mpItem->GetCurrencyId(),
																		mpItem->GetStartPrice(),
																		theSite->GetDefaultListingCurrency(), 
																		mpItem->GetEndTime())
									);
		currencyWidget.EmitHTML(pStream);
		*pStream  <<    ")"
				  <<    "</font>";

		// restore the currency in the widget
		currencyWidget.SetNativeCurrencyId(mpItem->GetCurrencyId());
	}

	*pStream  <<	"</td>\n";

	// Finish off the row for bid $ and first bid
	*pStream <<	"</tr>\n";


	// Create row for quantity & # of bids
	*pStream <<	"<tr>\n"
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
					" "
					"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBids)
			  <<	"eBayISAPI.dll?ViewBids&item="
			  <<	mpItem->GetId()
			  <<	"\">"
					"(bid history)"
					"</a>"
			  <<	flush;

	// if it not a private auction, display the link for emails
	if (!mpItem->GetPrivate() && !mpMarketPlace->GetUsers()->GetUserValidation()->IsSoftValidated())
	{
		*pStream  <<	" <a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageGetBidderEmails)
				  <<	"eBayISAPI.dll?GetBidderEmails&item="
				  <<	mpItem->GetId()
				  <<	"&pagetype="
				  <<	PageViewBidderWithEmails
				  <<	"\">"
						"(with emails)"
						"</a>";
	}

	// Finish off the table cell for bids, and the row too
	*pStream <<	"</font></td></tr>\n";


	// Create row for time left & location
	*pStream <<	"<tr>\n"
					"<td width=\"13%\"><font size=2>"
					"Time left"
					"</font></td>\n"
					"<td width=\"31%\"><b>"
			  <<	cDiffTime
			  <<	"</b></td>\n"
					"<td width=\"1%\"></td>\n"
					"<td width=\"10%\"><font size=2>"
					"Location"
					"</font></td>\n"
					"<td width=\"45%\"><b>"
			  <<	mpItem->GetLocation()
			  <<	"</b></td>\n"
					"</tr>\n";


	// nsacco, shipping country, 05/06/99
	char pCountryName[256] = "";
	mpMarketPlace->GetCountries()->GetCountryName(mpItem->GetCountryId(), pCountryName);
		
	// Create row for the country item is located in
	*pStream << "<tr>\n"
					"<td width=\"13%\"><font size=\"2\">"
					""
					"</font></td>\n"
					"<td width=\"31%\"><b>"
					""
					"</b></td>\n"
					"<td width=\"1%\"></td>\n"
					"<td width=\"10%\"><font size=\"2\">"
					"Country"
					"</font></td>\n"
					"<td width=\"45%\"><b>"
			  <<	pCountryName
			  <<	"</b></td>\n"
					"</tr>\n";

	// ------------
	
	// Create row for auction start date & email auction
	*pStream <<	"<tr>\n"
					"<td width=\"13%\"><font size=\"2\">"
					"Started"
					"</font></td>\n"
					"<td width=\"31%\"><font size=\"2\">";
	//samuel au, 4/6/99
	startTimeWidget.EmitHTML(pStream);
	//		  <<	cStartTime
	//end
	*pStream  <<	"</font></td>\n";


	// Email auction to a friend
	if (!mpItem->IsAdult())
	{
	*pStream <<	"<td width=\"1%\"></td>\n"
				"<td width=\"10%\" colspan=\"2\"><font size=2>"
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetCGIPath(PageShowEmailAuctionToFriend)
			 << "eBayISAPI.dll?ShowEmailAuctionToFriend&item="
			 <<	mpItem->GetId() 
			 <<	"\">"
			 <<	"<IMG "
				"border=0 "
				"alt=\"envelope\" "
				"height=9 "
				"width=13 "
				"src=\""
			 <<	mpMarketPlace->GetPicsPath()
			 << "envelope.gif"
				"\">"
			 <<	"</a>&nbsp;"
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetCGIPath(PageShowEmailAuctionToFriend)
			 << "eBayISAPI.dll?ShowEmailAuctionToFriend&item="
			 <<	mpItem->GetId() 
			 <<	"\">"
			 << "(mail this auction to a friend)"
			 <<	"</a></font>"
			 <<	"</td>\n"
			 <<	"</tr>\n";	
	}

	// Create row for auction end date 
	*pStream <<	"<tr>"
					"<td width=\"13%\"><font size=2>"
					"Ends"
					"</font></td>\n"
					"<td width=\"31%\"><font size=2>";
	//samuel au, 4/6/99
	endTimeWidget.EmitHTML(pStream);
	//		  <<	cEndTime
	//end
	*pStream  <<	"</font></td>\n";


	// Gift alert
	*pStream <<	"<td width=\"1%\"></td>\n"
				"<td width=\"10%\" colspan=\"2\"><font size=2>"
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetCGIPath()		// link to dynamic info page
			 <<	"eBayISAPI.dll?"				// with link to page where
			 << sDynamicGiftAlertNotice;		// user requests gift alert

	// Include high bidder user ID for Chinese auctions only
	if (mpItem->GetQuantity() == 1 && mpItem->GetHighBidder() != 0 &&
		!mpItem->GetPrivate())
	{
		*pStream << "&userid="
				 <<	mpItem->GetHighBidderUserId();
	}

	*pStream <<	"&item="
			 << mpItem->GetId()
			 << "\">"
			 <<	"<img border=\"0\" height=\"14\" width=\"16\" alt=\"[Gift Alert]\" src=\"" // was sGiftURL
			 <<	mpMarketPlace->GetPicsPath()
			 << "gift-icon.gif\">"
			 <<	"</a>&nbsp;"
			 <<	"<a href=\""
			 <<	mpMarketPlace->GetCGIPath()		// link to dynamic info page
			 <<	"eBayISAPI.dll?"				// with link to page where
			 << sDynamicGiftAlertNotice;		// user requests gift alert

	pSafeUserId = clsUtilities::MakeSafeString(mpItem->GetHighBidderUserId());

	// Include high bidder user ID for Chinese auctions only
	if (mpItem->GetQuantity() == 1 && mpItem->GetHighBidder() != 0 &&
		!mpItem->GetPrivate())
	{
		*pStream << "&userid="
				 <<	pSafeUserId;
	}

	delete [] pSafeUserId;

	*pStream <<	"&item="
			 << mpItem->GetId()
			 << "\">"
			 << "(request a gift alert)"
			 <<	"</a>"
			 <<	"</font>"
			 <<	"</td>\n"
			 <<	"</tr>\n";

	*pStream <<	flush;

	//  Optional cells for featured auction note
	if (mpItem->GetSuperFeatured() || mpItem->GetFeatured())
	{
		*pStream <<	"<tr>\n"
					"<td width=\"13%\"></td>\n"
					"<td width=\"31%\"></td>\n"
					"<td width=\"1%\"></td>\n"
					"<td width=\"10%\" colspan=\"2\"><font size=\"2\"><b>";
						
		if (!mpItem->GetFeatured())
			*pStream << "Featured Auction</font></b>";
		else if (!mpItem->GetSuperFeatured())
			*pStream << "Featured Category Auction</font></b>";
			else *pStream << "Featured & Featured Category Auction</font></b>";

		*pStream <<	"</td>\n"
					"</tr>\n";
	}

	// create row for seller
	if (mMode != Seller)	// if you're the seller, you don't need to see this
							//  over and over again

	{
		//added vertical space if need show seller
		*pStream <<	"<tr>"
					"<td width=\"13%\"></td>"
					"<td width=\"31%\"></td>"
					"<td width=\"1%\"></td>"
					"<td width=\"10%\">"
					"<img src=\""
				 << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				 << "dot_clear.gif\" "
					"width=\"1\" vspace=\"4\" border=\"0\"></td>"
					"<td width=\"45%\"></td>"
					"</tr>";

		// Create row for seller
		*pStream <<	"<tr>\n"
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
									mpItem->GetSellerFeedbackScore(),
									mpItem->GetSellerUserFlags());
		pUserIdWidget->SetUserIdBold(true);
		pUserIdWidget->SetShowUserStatus(true);
		pUserIdWidget->SetShowMask(true);
		pUserIdWidget->SetShowFeedback(true);
		pUserIdWidget->SetShowStar(true);
		pUserIdWidget->SetShowAboutMe(true);
		pUserIdWidget->EmitHTML(pStream);

		// Finish off seller row
		*pStream	<<	"</td>\n"
						"</tr>\n";	

		// Create row for viewing seller feedback
		*pStream <<	"<tr>"
						"<td width=\"13%\"></td>\n"
						"<td width=\"31%\" colspan=4>"
						"<font size=2>";
		if (!guernseys)
		{
			*pStream <<
						"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewFeedback)
				  <<	"eBayISAPI.dll?ViewFeedback&userid="
				  <<	mpItem->GetSellerUserId()
				  <<	"\">"
						"(view comments in seller's Feedback Profile)"
						"</a>"
						"&nbsp; ";
		}

		*pStream  <<    "<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewListedItems)
				  <<	"eBayISAPI.dll?ViewListedItems&userid="
				  <<	mpItem->GetSellerUserId()
				  <<	"\">"
						"(view&nbsp;seller's&nbsp;other&nbsp;auctions)"
						"</a>"
						"&nbsp; ";

		pUserIdWidget->SetDescription("(ask&nbsp;seller&nbsp;a&nbsp;question)");
		pUserIdWidget->SetShowFeedback(false);
		pUserIdWidget->SetShowStar(false);
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowMask(false);
		pUserIdWidget->SetUserIdBold(false);
		pUserIdWidget->SetShowAboutMe(false);
		pUserIdWidget->EmitHTML(pStream);
		delete pUserIdWidget;


		*pStream <<		"</font>"
						"</td>\n"
						"</tr>\n";

	}	// if mode isn't seller

	//add vertical space
	//if this is not a regular view item page, do not add the space
	
	*pStream <<	"<tr>"
			"<td width=\"13%\"></td>"
			"<td width=\"31%\"></td>"
			"<td width=\"1%\"></td>"
			"<td width=\"10%\">"
			"<img src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<<	"dot_clear.gif\" "
			"width=\"1\" vspace=\"4\" border=\"0\"></td>"
			"<td width=\"45%\"></td>"
			"</tr>";

//05/06/99 - Create a row for a link to ReInstateItem Link
//Gurinder - If this page is showing  old item from archive tables then 
//			 show a link to AdminUndoRemoveItemLogin page
	if (mIsViewOldItemPage)
	{
		*pStream << "<tr>\n"
				 "<td width=\"13%\"><font size=2>"
				 "Admin Option "
				 "</font></td>\n"
				 "<td width=\"35%\" colspan=4><font size=2>"		  
		  <<	 "<a href=\""
		  <<	 mpMarketPlace->GetCGIPath(PageAdminReInstateItemLogin)
		  <<	 "eBayISAPI.dll?AdminReInstateItemLogin&item="
		  <<	 mpItem->GetId()
		  <<	 "\""
				 ">"
				 "Click here to reinstate this item"
				 "</a>"
				 "."
		  <<	 "</font></td>\n"
				 "</tr>\n";		
	
	}
// End 05/06/99
	

	// Create row for high bidder
	*pStream <<	"<tr>\n"
					"<td width=\"13%\" valign=\"top\"><font size=2>"
			  <<	((mpItem->GetQuantity() > 1) ? 
					"High bids" :
					"High bid")
			  <<	"</font></td>\n"
					"<td width=\"31%\" valign=\"top\" colspan=4>";

	// if dutch, just show link to dutch bidders
	if (mpItem->GetQuantity() > 1)
	{
		*pStream <<		"<font size=\"3\"><b><a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewBidsDutchHighBidder)
				  <<	"eBayISAPI.dll?ViewBidsDutchHighBidder&item="
				  <<	mpItem->GetId()
				  <<	"\""
						">"
						"see Dutch high bidders"
						"</a></b></font>";
		
		// if user doesn't have cookie, show emails link
		if (!mpMarketPlace->GetUsers()->GetUserValidation()->IsSoftValidated())
		{
			*pStream <<		"<font size=\"2\">"
				<<	"&nbsp; <a href=\""
				<<	mpMarketPlace->GetCGIPath(PageGetBidderEmails)
				<<	"eBayISAPI.dll?GetBidderEmails&item="
				<<	mpItem->GetId()
				<<	"&pagetype="
				<<	PageViewBidDutchHighBidderEmails
				<<	"\">"
				"(include&nbsp;e-mails)"
				"</a></font>";
		}

	}
	// if not dutch, show high bidder (unless it's a private auction)
	else 
	{
		if (!((mpItem->GetPrivate()) && (mMode!=Seller)))
		{
			if (mpItem->GetHighBidder() != 0)
			{

				if (*(mpItem->GetHighBidderUserId()))
				{

					// Show the high bidder userid using widget
					pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
					pUserIdWidget->SetUserInfo(mpItem->GetHighBidderUserId(), 
												mpItem->GetHighBidderEmail(),
												UserStateEnum(mpItem->GetHighBidderUserState()),
												mpMarketPlace->UserIdRecentlyChanged(mpItem->GetHighBidderIdLastModified()),
												mpItem->GetHighBidderFeedbackScore(),
												mpItem->GetHighBidderUserFlags());
					pUserIdWidget->SetUserIdBold(true);
					pUserIdWidget->SetShowUserStatus(true);
					pUserIdWidget->SetShowMask(true);
					pUserIdWidget->SetShowFeedback(true);
					pUserIdWidget->SetShowStar(true);
					pUserIdWidget->SetShowAboutMe(true);
					pUserIdWidget->EmitHTML(pStream);
					delete pUserIdWidget;
				}
			}
			else
				*pStream <<	"--";
		}
		else
		// it's private, so just show note
		{
			*pStream <<	"<font size=3><a href=\""
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
	*pStream <<	"</td>"
				"</tr>\n";

// Lena - Ts and Cs

	//added vertical space
	*pStream <<	"<tr>"
				"<td width=\"13%\"></td>"
				"<td width=\"31%\"></td>"
				"<td width=\"1%\"></td>"
				"<td width=\"10%\">"
				"<img src=\""
			 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<	"dot_clear.gif\" "
				"width=\"1\" vspace=\"4\" border=\"0\"></td>"
				"<td width=\"45%\"></td>"
				"</tr>";

	mpItem->TsAndCsSet();
	bool first = true;
	*pStream <<	"<tr>"; 
	*pStream << "<td width=\"13%\" valign=\"top\"><font size=2>Payment</font></td>"
				"<td width=\"31%\" valign=\"top\" colspan=4><font size=\"2\" "
				"color=\"#CC3333\">";

	if (mpItem->AcceptsPaymentVisaMaster())
	{
		first = false;
		*pStream << "Visa/MasterCard";
	}
	if ( mpItem->AcceptsPaymentAmEx())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " American Express";
	}
	if (mpItem->AcceptsPaymentDiscover())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " Discover";
	}					
	if (mpItem->AcceptsPaymentMOCashiers())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " Money Order/Cashiers Checks";
	}
	if (mpItem->AcceptsPaymentCOD())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " COD (collect on delivery)";
	}

	if (mpItem->AcceptsPaymentPersonalCheck())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " Personal Checks";
	}
	if (mpItem->AcceptsPaymentEscrow())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " Online escrow";
	}
	if (mpItem->AcceptsPaymentOther())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " Other";
	}
	if (mpItem->MorePaymentSeeDescription())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << " See item description for payment methods accepted";
	}
	first = true;
	*pStream << "</font></td>"
		"</tr>"
		"<tr>"
		"<td width=\"13%\" valign=\"top\"><font size=\"2\">Shipping</font></td>"
		"<td width=\"31%\" valign=\"top\" colspan=4><font size=\"2\" "
		"color=\"#CC3333\">";
			
	if (mpItem->BuyerPaysForShippingActual())
	{
		first = false;
		*pStream << "Buyer pays actual shipping charges";
	}
	if (mpItem->BuyerPaysForShippingFixed())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << "Buyer pays fixed shipping charges";
	}
	if (mpItem->SellerPaysForShipping())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << "Seller pays for shipping";
	}
	
	// nsacco 06/16/99
	// removed old IsShippingToCanada code

	// nsacco 07/27/99
	// display new shipping options
	if (mpItem->IsShippingInternationally())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;

		*pStream << "Seller ships internationally (worldwide)";
	}
	else if (mpItem->IsShippingToSiteOnly())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		
		*pStream << "Will ship to ";
		
		switch (mpItem->GetSiteId())
		{
		case SITE_EBAY_DE:
			*pStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*pStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*pStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*pStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*pStream << "United States";
			break;
		}
		
		*pStream<< " only";
	}
	else if (mpItem->IsShippingToSiteAndRegions())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;

		*pStream << "Will ship to ";

		switch (mpItem->GetSiteId())
		{
		case SITE_EBAY_DE:
			*pStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*pStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*pStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*pStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*pStream << "United States";
			break;
		}

		*pStream << " and the following regions: ";
		bool bComma = false;

		if (mpItem->IsShippingToRegion(ShipRegion_NorthAmerica))
		{
			*pStream << "North America";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_Europe))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Europe";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_Oceania))
		{
			/// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Australia / NZ";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_Asia))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Asia";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_SouthAmerica))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "South America";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_Africa))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Africa";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_LatinAmerica))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Latin America";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_MiddleEast))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Middle East";

			bComma = true;
		}

		if (mpItem->IsShippingToRegion(ShipRegion_Caribbean))
		{
			// check if we need a comma
			if (bComma)
				*pStream << ", ";

			*pStream << "Caribbean";

			bComma = true;
		}

	}
	// end new shipping options

	if (mpItem->MoreShippingSeeDescription())
	{
		if (!first)
			*pStream << ", ";
		else
			first = false;
		*pStream << "See item description for shipping charges";
	}
    *pStream << "</font></tr>";

	// Sam, i-Escrow, 02/23/99
	// start showing links to iescrow on 3/3/99
	// AlexP: don't show link if seller didn't check the box (no matter what the $) 07/26/99
	if ((clsUtilities::CompareTimeToGivenDate(time(0), 3, 3, 99, 0, 0, 0) >= 0) &&
		(mpItem->GetCurrencyId() == Currency_USD )
		)
	{
		if ((mpItem->GetBidCount() > 0) && (curtime > endTime))
		{
			if (mpItem->AcceptsPaymentEscrow()) // || (mpItem->GetPrice()>=200))
			{					
				*pStream << "<tr>"
						 << "<td width=\"13%\" valign=\"top\"><font size=\"2\">"
						 << "Escrow Services</font></td>"
						 << "<td width=\"31%\" valign=\"top\" colspan=4><font size=\"2\">"
						 <<	"Click "
						 <<	"<a href=\""
						 <<	mpMarketPlace->GetCGIPath(PageIEscrowLogin)
						 <<	"eBayISAPI.dll?iescrowlogin&item="
						 <<	mpItem->GetId()
						 <<	"&type=initial"
						 <<	"\">"
						 << "here"
						 <<	"</a>"
						 << " to begin escrow transaction.";
				*pStream << "</font></tr>";
			}
		}
	}

	//start edit unbid item
	if ((mMode != Bidder) && (mpItem->GetBidCount() == 0) && (diffTime > 0))
	{

		//add vertical space if edit item show up
		*pStream <<	"<tr>"
					"<td width=\"13%\"></td>"
					"<td width=\"31%\"></td>"
					"<td width=\"1%\"></td>"
					"<td width=\"10%\">"
					"<img src=\""
				 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				 <<	"dot_clear.gif\" "
					"width=\"1\" vspace=\"4\" border=\"0\"></td>"
					"<td width=\"45%\"></td>"
					"</tr>";
		*pStream <<	"<tr>\n"
					"<td width=\"13%\"><font size=2>"
					"Update item "
					"</font></td>\n"
					"<td width=\"31%\" colspan=4><font size=2>"
				<<	"<b>Seller: </b> " 
				<<	"If this item has received no bids, you may "
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageUserItemVerification)
				<<	"eBayISAPI.dll?UserItemVerification&item="
				<<	mpItem->GetId()
				<<	"\""
					">"
				<<	"revise"
					"</a>"
				<<	" it.</font>";
		if (mpItem->GetPassword() & ItemUpdated)
		{
			*pStream <<	"<br><font size=\"2\">"
					<<	"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/seller_revisions_explanation.html\">"
						"Seller revised</a>"
						" this item before first bid.</font>";
		}

		*pStream <<	"</td>\n"
					"</tr>\n";
	}	

	//start show note, when seller edit the item at least once, and item received bid
	if ((mpItem->GetBidCount() > 0) && (mpItem->GetPassword() & ItemUpdated))
	{
		//add vertical space if edit item show up
		*pStream <<	"<tr>"
					"<td width=\"13%\"></td>"
					"<td width=\"31%\"></td>"
					"<td width=\"1%\"></td>"
					"<td width=\"10%\">"
					"<img src=\""
				 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				 <<	"dot_clear.gif\" "
					"width=\"1\" vspace=\"4\" border=\"0\"></td>"
					"<td width=\"45%\"></td>"
					"</tr>";
		*pStream <<	"<tr>\n"
					"<td width=\"13%\"><font size=2>"
					"<I>Note:</I> "
					"</font></td>\n"
					"<td width=\"31%\" colspan=4><font size=2>"
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/seller_revisions_explanation.html\">"
					"Seller revised</a>"
					" this item before first bid.</font>"
					"</td>\n"
					"</tr>\n";
	}

	if (!mIsViewOldItemPage)
	{//Gurinder - Don't show relist link in AdminViewOldItems Page
		//start relist row
		if ((diffTime <= 0) && ((mMode == Seller) || (mMode == Generic)))
		{
			//add vertical space if relist show up
			*pStream <<	"<tr>"
						"<td width=\"13%\"></td>"
						"<td width=\"31%\"></td>"
						"<td width=\"1%\"></td>"
						"<td width=\"10%\">"
						"<img src=\""
					 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
					 <<	"dot_clear.gif\" "
						"width=\"1\" vspace=\"4\" border=\"0\"></td>"
						"<td width=\"45%\"></td>"
						"</tr>";
			*pStream <<	"<tr>\n"
						"<td width=\"13%\"><font size=2>"
						"Relist item"
						"</font></td>\n"
						"<td width=\"31%\" colspan=5><font size=2>"
					<<	"<b>Seller:</b> " 
					<<	"Didn't sell your item the first time?  eBay will refund your "
						"relisting fee if it sells the second time around. "
					<<	"<a href=\"";
						
					// kaz - 2/23/99: If the seller feedback score is high enough, don't show the tips
					// kaz - 4/20/99: Point to the right pages in case we're in the middle of a rollout
					// nsacco 07/30/99
					// Link to ListItemForSale instead of NewItemQuick and send to the site 
					// the item was originally listed on.
					// TODO - fix the building of the URL
					*pStream << "http://cgi5"
							 << clsUtilities::GetDomainToken(mpItem->GetSiteId(), PARTNER_EBAY)
							 << "/aw-cgi/";

					if (mpItem->GetSellerFeedbackScore() >= kHighEnoughFeedback)
						//*pStream	<<	mpMarketPlace->GetCGIPath(PageListItemForSale)
						*pStream	<<	"eBayISAPI.dll?ListItemForSale&item=";
					else
						//*pStream	<<	mpMarketPlace->GetCGIPath(PageBetterSeller)
						*pStream	<<	"eBayISAPI.dll?BetterSeller&item=";


			*pStream	<<	mpItem->GetId()
						<<	"\""
							">"
							"Relist this item"
							"</a>"
							"."
						<<	"</font></td>\n"
							"</tr>\n";
		}
	}
	
	*pStream << flush;
	// End table tag
	*pStream << "</table></center>";

	// Seller responsibiilty note
	// simple 3-column table to do left/right margins
	
	
	if (mMode != Seller)
	{
		*pStream <<	"<br><table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" "
				"width=\"100%\">"
				"<tr>";

		*pStream <<	"<td>"
				"Seller assumes all responsibility for listing this item."
				" You should contact the seller to resolve any questions"
				" before bidding.";

		// PH added 04/26/99 >>
		clsCurrency* cur = mpMarketPlace->GetCurrencies()->GetCurrency(mpItem->GetCurrencyId());
		*pStream << " Currency is ";
		// nsacco 07/01/99
		*pStream << cur->GetNamePlural();
		*pStream << " (";
		*pStream << cur->GetSymbol();
		*pStream << ") unless otherwise noted.";

		*pStream <<	"</td>\n"
					"</tr>\n";

		//show reserve auction info if it is a reserve auction & not meet reserve price
		if(diffTime < 0 && mMode != Seller && mpItem->GetReservePrice() > mpItem->GetPrice()) 
		{
			*pStream <<	"<tr><td>\n"
						"<b><I>Note: </b></I>"
						"This is a Reserve Auction in which the reserve price was not met. "
						"The seller is not obligated to offer this item again or to sell it "
						"to the highest bidder. Likewise, the high bidder is not obligated to "
						"buy this item.</td>\n"
						"</tr>\n";
		}
	
		//finish 2nd table
		*pStream <<	"</table>"
					"\n";
	}

	// Optional description
	if (mShowDescription)
	{
		if (mpItem->GetDescription() != NULL)
			pNewDescription	= clsUtilities::ChangeHTMLQuoteToQuote(mpItem->GetDescription());
		else
			pNewDescription	= NULL;

		// Table tag for description headline
		if (mColor[0]=='\0')
		{
			*pStream  <<	"<center><table border=1 cellspacing=0 "
							"width=\"100%\">\n";
		}
		else
		{
			*pStream  <<	"<center><table border=1 cellspacing=0 "
							"width=\"100%\" bgcolor=\""
					  <<	mColor
					  <<	"\">\n";
		}

		// Description headline
		*pStream <<		"<tr>\n"
						"<td align=center width=\"100%\">"
						"<font size=4 color=\"#000000\">"
						"<b>"
						"<a name=\"DESC\">"
						"Description"
						"</a></b></font></td>\n"
						"</tr>\n"
						"</table></center>\n";

		*pStream <<	"\n";
		
		*pStream << "<blockquote>\n";

		if (pNewDescription)
			*pStream << pNewDescription;

		*pStream << "\n</blockquote>\n";

		// We can get rid of these paranoid close tags once we put DrawSafeHTML back in
		*pStream <<		"</blockquote>"
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

		if  (mpItem->GetPictureURL() != NULL)
		{
			*pStream <<		"<hr><img src="
							"\""
					  <<	mpItem->GetPictureURL()
					  <<	"\""
							">"
							"<p>";
		}

		delete [] pNewDescription;
	}

	*pStream << flush;
	return true;
}

