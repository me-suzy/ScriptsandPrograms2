/*	$Id: clseBayHighTicketItemWidget.cpp,v 1.7.54.7.4.2 1999/08/05 18:58:47 nsacco Exp $	*/
//
//	File:	clseBayHighTicketItemWidget.cpp
//
//	Class:	clseBayHighTicketItemWidget
//
//	Author:	Wen Wen
//
//	Function:
//			Widget that shows high ticket items using clseBayItemWidget.
//
// Modifications:
//				- 09/15/98	Wen Wen		- Created
//				- 06/25/99  Jennifer	- Make the page similar to the listings page.
//				- 07/01/99	nsacco		- switch to GetPicsPath() instead of GetImagePath() and
//										  use GetHTMLPath()
//				- 07/22/99	petra	- use clseBayTimeWidget
//
#include "widgets.h"
#include "clseBayHighTicketItemWidget.h"
#include "clseBayTimeWidget.h"

clseBayHighTicketItemWidget::clseBayHighTicketItemWidget(clsMarketPlace *pMarketPlace) :
	clseBayItemWidget(pMarketPlace)
{
			mPrice=5000;
			mFullPage = 0;
}

clseBayHighTicketItemWidget::~clseBayHighTicketItemWidget()
{
}

void clseBayHighTicketItemWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("price", cName)==0))
		{
			SetPrice(atof(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("fullpage", cName)==0))
		{
			SetFullPage(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("currency", cName)==0))
		{
			SetCurrency(atoi(cValue));
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
	clseBayItemWidget::SetParams(pvArgs);

}

// Retrieve ids of all current, super-featured items and stuff them into pvItemIds.
void clseBayHighTicketItemWidget::GetItemIds(vector<int> *pvItemIds)
{
	clsItems	*pItems = NULL;
	time_t		CurrentTime;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	CurrentTime = time(0);

	if (mpMarketPlace) pItems = mpMarketPlace->GetItems();
	if (pItems)
	{
		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " Start Getting High Ticket Item Ids\n";
		}

		// get the items which current price is equal or higher than mPrice with
		// minimum bids mMinBids
//			pItems->GetHighTicketItems(pvItemIds, CurrentTime, mPrice);
		pItems->GetHighTicketIds(pvItemIds, CurrentTime, mPrice);

		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " End Getting High Ticket Item Ids\n\n";
		}
	}

		

}

const int THREE_HOURS =	3 * 60 * 60;

// overwrite EmitHTML
bool clseBayHighTicketItemWidget::EmitHTML(ostream *pStream)
{
	int			i;
	int			iconType;
	clsItem*	pItem;
	char		PriceString[20];
	char		TimeString[20];
	char		BidCountString[10];
// petra	struct tm*	pEndTime;
	time_t		EndingTime;
// petra	time_t		Now;
	int			Color=0;
	static char*	BGColor[] = {"#EFEFEF", "#FFFFFF"};
	char*		pTimeName;

	const char* statusIconTemplate = 
			"<td align=\"CENTER\" width=\"80\">\n"
			"<img height=15 width=76 border=0 alt=\"Status\" "
			"usemap=\"#status_icon_map\" "
			"src=\"http://pics.ebay.com/aw/pics/lst/%s.gif\">\n"
			"</td>\n";

	char statusGif[5];
	char finalStatusGIF[1024];		// includes td's and stuff

	if (mFullPage == 0)
	{
		// print a little widget for home page
		return clseBayItemWidget::EmitHTML(pStream);
	}

	// Initialize
	if (!Initialize())
	{
		return false;
	}


	pTimeName = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale()->GetTimeZone();	// petra

	// print the legend for icons

	/*
	*pStream	<<	"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td><b>Current</b></td><td align=\"RIGHT\">\n"
					"<a href=\"http://pages.ebay.com/help/basics/g-gallery.html\">"
					"<img height=15 width=16 border=0 alt=\"Gallery\" "
					"src=\"http://pics.ebay.com/aw/pics/lst/gal.gif\"></a>"
					"<font size=\"2\"> = Gallery </font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\"http://pages.ebay.com/help/basics/g-pic.html\">"
					"<img height=15 width=16 border=0 alt=\"Picture\" "
					"src=\"http://pics.ebay.com/aw/pics/lst/pic.gif\"></a>"
					"<font size=\"2\"> = Picture</font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\"http://pages.ebay.com/help/basics/g-hot-items.html\">"
					"<img height=15 width=16 border=0 alt=\"Hot!\" "
					"src=\"http://pics.ebay.com/aw/pics/lst/hot.gif\"></a>"
					"<font size=\"2\"> = Hot!</font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\"http://pages.ebay.com/help/basics/g-new.html\">"
					"<img height=15 width=16 border=0 alt=\"New!\" "
					"src=\"http://pics.ebay.com/aw/pics/lst/new.gif\"></a>"
					"<font size=\"2\"> = New!</font>\n"
					"</td></tr></table>\n";
	*/
	// kakiyama 08/02/99

		*pStream	<<	"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td><b>Current</b></td><td align=\"RIGHT\">\n"
					"<a href=\""
					<< mpMarketPlace->GetHTMLPath()
					<< "help/basics/g-gallery.html\">"
					"<img height=15 width=16 border=0 alt=\"Gallery\" "
					"src=\""
					<< mpMarketPlace->GetPicsPath()
					<< "lst/gal.gif\"></a>"
					"<font size=\"2\"> = Gallery </font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\""
					<< mpMarketPlace->GetHTMLPath()
					<< "help/basics/g-pic.html\">"
					"<img height=15 width=16 border=0 alt=\"Picture\" "
					"src=\""
					<< mpMarketPlace->GetPicsPath()
					<< "lst/pic.gif\"></a>"
					"<font size=\"2\"> = Picture</font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\""
					<< mpMarketPlace->GetHTMLPath()
					<< "help/basics/g-hot-items.html\">"
					"<img height=15 width=16 border=0 alt=\"Hot!\" "
					"src=\""
					<< mpMarketPlace->GetPicsPath()
					<< "lst/hot.gif\"></a>"
					"<font size=\"2\"> = Hot!</font>\n"
					"&nbsp;&nbsp;&nbsp;"
					"<a href=\""
					<< mpMarketPlace->GetHTMLPath()
					<< "help/basics/g-new.html\">"
					"<img height=15 width=16 border=0 alt=\"New!\" "
					"src=\""
					<< mpMarketPlace->GetPicsPath()
					<< "lst/new.gif\"></a>"
					"<font size=\"2\"> = New!</font>\n"
					"</td></tr></table>\n";


	// print the heading bar
	*pStream	<<	"<table width=\"100%\" cellpadding=0 border=0 cellspacing=1 bgcolor=\"#FFFFFF\">\n"
					"<tr valign=middle bgcolor=\"#FFCC00\">\n"
					"<td align=center valign=top width=\"80\" bgcolor=\"#FFCC00\">\n"
					"<img src=\""
				<< mpMarketPlace->GetHTMLPath()
				<< "home/spacer.gif\" "
					"width=80 height=1 alt=\"\" border=\"0\"><br>"
					"<strong>Status</strong><br></td>\n"
					"<td align=center valign=top width=\"60%\" bgcolor=\"#FFCC00\">\n" 
					"<strong>Item</strong><br></td>\n"
					"<td align=center valign=top width=\"12%\" bgcolor=\"#FFCC00\">\n" 
					"<strong>Price</strong><br></td>\n"
					"<td align=center valign=top width=\"6%\" bgcolor=\"#FFCC00\">\n" 
					"<strong>Bids</strong><br></td>\n"
					"<td align=center valign=top width=\"15%\" bgcolor=\"#FFCC00\">\n" 
					"<strong>Ends "
				<<	pTimeName
				<<	"</strong><br></td></tr></table>\n";

	if (mvItems.empty())
	{
		return false;
	}

	sort(mvItems.begin(), mvItems.end(), sort_items_end_time);

    // status icon map
    *pStream	<<	"<map name=\"status_icon_map\">\n"
					"<area shape=rect coords=\"1,0,16,15\"\n" 
					"	href=\""
				<<  mpMarketPlace->GetHTMLPath()
				<<  "help/basics/g-gallery.html\" alt=\"Gallery\">\n"
					"<area shape=rect coords=\"20,0,36,15\"\n" 
					"	href=\""
				<<  mpMarketPlace->GetHTMLPath()
				<<  "help/basics/g-pic.html\" alt=\"Picture\">\n"
					"<area shape=rect coords=\"40,0,56,15\"\n" 
					"	href=\""
				<<  mpMarketPlace->GetHTMLPath()
			    <<  "help/basics/g-hot-items.html\" alt=\"Hot!\">\n"
					"<area shape=rect coords=\"60,0,76,15\"\n" 
					"	href=\""
				<<  mpMarketPlace->GetHTMLPath()
				<<  "help/basics/g-new.html\" alt=\"New!\">\n"
					"</map>\n";


	// print a full page
	for (i = 0; i < mvItems.size(); i++)
	{
		pItem = mvItems[i];
		if (!pItem || pItem->GetPrice() < pItem->GetStartPrice())
		{
			continue;
		}

		// make a table per line so that it can be down-loaded faster
		*pStream << "<table width=\"100%\" cellpadding=4 border=0 cellspacing=0 bgcolor=\""
					 << BGColor[(Color++)%2]
					 << "\">\n<tr valign=middle>";
		
		// Draw gallery icon if any
		if (pItem->IsGallery() || pItem->IsFeaturedGallery())
			statusGif[0]='g';
		else
			statusGif[0]='_';

		// Draw pics icon if any
		if (pItem->GetPictureURL() && strlen(pItem->GetPictureURL())>0)
			statusGif[1]='p';
		else
			statusGif[1]='_';

		// Draw hot icon if any
		if (pItem->GetBidCount() > 30 && pItem->GetPrice() >= pItem->GetReservePrice() )
			statusGif[2]='h';
		else
			statusGif[2]='_';

		// Draw new icon if any
		if (difftime(time(0), pItem->GetStartTime()) < ONE_DAY)
			statusGif[3]='n';
		else
			statusGif[3]='_';

		statusGif[4]='\0';

		sprintf(finalStatusGIF, statusIconTemplate, statusGif);

		// Output the status gif
		*pStream	<<	finalStatusGIF;

		// Bold title?
		if (pItem->GetBoldTitle())
		{
			*pStream	<< "<td width=\"58%\"><b>";
		}
		else
		{
			*pStream	<<	"<td width=\"58%\">";
		}

		// Print the item and the link
		*pStream	<< "<a href=\""
					<<	mpMarketPlace->GetCGIPath(PageViewItem)
					<<	"eBayISAPI.dll?ViewItem&item="
					<<	pItem->GetId()
					<<	"\">"
					<< pItem->GetTitle()
					<< "</a>";

		// Bold title?
		if (pItem->GetBoldTitle())
		{
			*pStream << "</b>";
		}

		// If Gift, print Gift Icon
		iconType = pItem->GetGiftIconType();
		if (iconType != GiftIconUnknown )
        {
			*pStream	<<	"<A HREF=\""
						<<	mpMarketPlace->GetHTMLPath()	// nsacco 07/01/99
						<<	"help/buyerguide/gift-icon.html\">"
						<<	"<img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
						<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99 was GetImagePath()
						<<	mpMarketPlace->GetGiftIconImage(iconType)
						<<	"\"></a>";
		}
		
		*pStream << "</td>";

		// Print the current item price
		sprintf(PriceString, "<b>$%.2f</b>", pItem->GetPrice());
		*pStream << "<td align=right width=\"12%\">"
					 << PriceString
					 << "</td>"; 

		// print current bid count
		if (pItem->GetBidCount() > 0)
		{
			sprintf(BidCountString, "%d", pItem->GetBidCount());
		}
		else
		{
			strcpy(BidCountString, "-");
		}
		*pStream << "<td align=center width=\"6%\">"
					 << BidCountString
					 << "</td>"; 

		// Print ending time
		// Print ending time
		EndingTime = pItem->GetEndTime();
// petra		pEndTime = localtime(&EndingTime);
		clseBayTimeWidget timeWidget (mpMarketPlace, 0, 0, pItem->GetEndTime());	// petra
		char temp[50];														// petra
		timeWidget.EmitString (&temp[0]);									// petra

		if (EndingTime >= time(0) && difftime(EndingTime, time(0)) < THREE_HOURS)
		{
			// make it red
			sprintf(TimeString, "<font color=\"#FF0000\">%s</font>",	// petra
					temp);												// petra
// petra			sprintf(TimeString, "<font color=\"#FF0000\">%2.2d/%2.2d %2.2d:%2.2d</font>", 
// petra				pEndTime->tm_mon+1, 
// petra				pEndTime->tm_mday, 
// petra				pEndTime->tm_hour, 
// petra				pEndTime->tm_min);
		}
		else
		{
			strcpy(TimeString, temp);		// petra
// petra			sprintf(TimeString, "%2.2d/%2.2d %2.2d:%2.2d", 
// petra				pEndTime->tm_mon+1, 
// petra				pEndTime->tm_mday, 
// petra				pEndTime->tm_hour, 
// petra				pEndTime->tm_min);
		}

		*pStream << "<td align=center width=\"15%\">"
					 << TimeString
					 << "</td></tr></table>\n";

	}

	return true;
}

