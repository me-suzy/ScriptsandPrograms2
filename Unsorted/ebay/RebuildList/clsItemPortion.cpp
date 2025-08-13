/*	$Id: clsItemPortion.cpp,v 1.4.388.3 1999/08/09 18:45:04 nsacco Exp $	*/
//
//	File:	clsItemPortion.h
//
//	Class:	clsItemPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Listing the child items for the current category
//
// Modifications:
//				- 07/01/99	nsacco - use GetPicsPath() for images
//				- 07/07/97	Wen - Created
//				- 07/22/99	petra	- use clsIntlLocale for time zone
//
#include "clsRebuildListApp.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsListingItem.h"
#include "clsFileName.h"
#include "clsItemPortion.h"
#include "clsPageLink.h"


// nsacco 07/01/99
#define HotIconURL      "hot.gif"
#define NewIconURL      "new.gif"
#define PicIconURL      "pic.gif"

// TODO - replace?
#define TempCGIPath	"http://iguana.ebay.com/aw-cgi/"

// Constructor
clsItemPortion::clsItemPortion(clsCategory* pCurrentCategory, 
							   ListingItemVector*	pItems,
							   TimeCriterion TimeStamp,
							   ItemType Type,
							   int NumberItemsPerPage,
							   bool	HasFeaturedOrHot)
{
	struct tm*	pTime;

	mpCategory = pCurrentCategory;
	mpItems	   = pItems;
	mTimeStamp = TimeStamp;
	mType	   = Type;
	mHasFeaturedOrHot = HasFeaturedOrHot;
	
	if (mpCategory || TimeStamp == GOING)
	{
		mNumberItemsPerPage = NumberItemsPerPage;
	}
	else
	{
		// the top page
		// there is no limit for featured items (make it 2000 for now)
		mNumberItemsPerPage = 2000;
	}

	mpApp = (clsRebuildListApp*) gApp;

	// reset the Index
	mIndex = mpItems ? mpItems->begin() : NULL;

	// get the starting time
	mCreatingTime = mpApp->GetCreatingTime();

	// get marketplace and hot item count
	mpMarketPlace = mpApp->GetMarketPlace();
	mHotItemCount = mpMarketPlace->GetHotItemCount();
	sprintf(mHotItemCountText, "%d", mHotItemCount);

	// get pointer to the file name object
	mpFileName = mpApp->GetFileName();

	// Time name
// petra	pTime = localtime(&mCreatingTime);
// petra	
// petra	if (pTime->tm_isdst)
// petra	{
// petra		strcpy(mTimeName, "PDT");
// petra	}
// petra	else
// petra	{
// petra		strcpy(mTimeName, "PST");
// petra	}
	strcpy (mTimeName, mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale()->GetTimeZone());	// petra
}

clsItemPortion::~clsItemPortion()
{
}

// Retrieve items under the current category
void clsItemPortion::Initialize()
{
}

// Set the icon image path for the hot items
void clsItemPortion::SetHotIconPath(char* pPath)
{
	mpHotIconPath = pPath;
}

// Set the icon image path for the new items
void clsItemPortion::SetNewIconPath(char* pPath)
{
	mpNewIconPath = pPath;
}


// Print the items in the specifyed page to the file
void clsItemPortion::Print(ostream* pOutputFile, int CurrentPage)
{
	if ((mType == FEATURED || mType == HOT) && CurrentPage != 1)
	{
		return;
	}

	PrintTitle(pOutputFile, CurrentPage);

	if (mpItems == NULL || mpItems->size() == 0)
	{
		return;
	}

/*	if (mTimeStamp == COMPLETED)
	{
		PrintCompletedItems(pOutputFile, CurrentPage);
	}
	else*/
	{
		PrintNoCompletedItems(pOutputFile, CurrentPage);
	}
}

void clsItemPortion::PrintTitle(ostream* pOutputFile, int CurrentPage)
{
	char	Title[256];
	char*	pHeading;
	char	Description[1000];
	char	BgColor[8];

	// print the title
	switch (mType)
	{
	case FEATURED:
		// set name tag and background color
		pHeading = LIST_HEADING;
		strcpy(BgColor, "#99CCCC");

		// set featurd title
		if (mpCategory)
		{
			sprintf(Title, "Featured Auctions in %s", mpCategory->GetName());
		}
		else
		{
			strcpy(Title, "<a name=featured>Featured Auctions</a>");
		}

		// set description
		sprintf(Description, "To find out how to be listed in this section and seen by thousands, please <a href=\"%seBayISAPI.dll?Featured\">visit this link</a>.",
				mpMarketPlace->GetCGIPath(PageFeatured));

		// there is no limit for featured items (make it 2000 for now)
		mNumberItemsPerPage = 2000;
		break;

	case HOT:
		// set name tag and background color
		strcpy(BgColor, "#FF9999");

		// set featurd title
		if (mpCategory)
		{
			sprintf(Title, "Hot Items in %s", mpCategory->GetName());
		}
		else
		{
			strcpy(Title, "<a name=hot>Hot Items</a>");
		}

		// set description
		strcpy(Description, "These items have received more than 30 bids. (No reserve price auctions.)");

		// there is no limit for hot items (make it 2000 for now)
		mNumberItemsPerPage = 2000;
		break;
		
	default:
		// set name tag and background color
		strcpy(BgColor, "#cccccc");

		// set featurd title
		if (mpCategory)
		{
			sprintf(Title, "All Items in %s", mpCategory->GetName());
		}
		else
		{
			strcpy(Title, "All Items");
		}

		break;
	}

	// Get the heading
	switch (mTimeStamp)
	{
	case LISTING:
		pHeading = LIST_HEADING;
		break;
	
	case NEW_TODAY:
		pHeading = NEWTODAY_HEADING;
		break;
	
	case END_TODAY:
		pHeading = ENDTODAY_HEADING;
		break;
	
	case COMPLETED:
		sprintf(mCompletedHeading, "%s %s", COMPLETED_HEADING, mpApp->GetTodayString());
		pHeading = mCompletedHeading;
		break;

	case GOING:
		pHeading = GOING_HEADING;
		break;
	}

	if (CurrentPage == 1)
	{
		// print title
		*pOutputFile << "<p><table border=\"1\" cellspacing=\"0\" width=\"100%\" bgcolor=\""
					 << BgColor
					 << "\"><tr><td align=\"center\"><font size=4 face=\"arial, helvetica\"><strong>"
					 << Title
					 << "</strong></font></td></tr>"
					 << "<tr><td align=\"center\" width=\"100%\"><font size=2>"
					 << pHeading
					 << "</font></td></tr></table>\n";
	}

	// print description
	if (mType == FEATURED || mType == HOT)
	{
		*pOutputFile << "<p align=\"center\"><font size=\"2\">"
					 << Description
					 << "</font></p>";
	}
	else
	{
		// print page links as needed
		if (mpItems && mpItems->size() > mNumberItemsPerPage)
		{
			clsPageLink	PageLink(mpCategory, mpItems->size(), mTimeStamp, mpFileName, mNumberItemsPerPage);
			PageLink.Print(pOutputFile, CurrentPage);
		}
	}

	// print the header for the item list if needed
	if (mpItems && mpItems->size())
	{
		*pOutputFile << "<table border=1 cellspacing=0 width=\"100%\" bgcolor=\""
					 << BgColor
					 << "\"><tr><td align=center valign=top width=\"62%\"><font size=2>" 
						"<strong>Item</strong></font></td>\n"
						"<td align=center valign=top width=\"12%\"><font size=2>" 
						"<strong>Price</strong></font></td>\n"
						"<td align=center width=\"6%\"><font size=2>" 
						"<strong>Bids</strong></font></td>\n"
						"<td align=center valign=top width=\"15%\"><font size=2>" 
						"<strong>Ends "
					 << mTimeName
					 << "</strong></font></td></tr></table>";
	}

	*pOutputFile << "\n";
}

void clsItemPortion::PrintNoCompletedItems(ostream* pOutputFile, int CurrentPage)
{
	int			i;
	clsListingItem*	pItem;
	char		PriceString[20];
	char		TimeString[20];
	char		BidCountString[10];
	struct tm*	pEndTime;
	time_t		EndingTime;
	int			Color=0;
	static char*	BGColor[] = {"#EFEFEF", "#FFFFFF"};

	i = 0;
	while (mIndex != mpItems->end() && i < mNumberItemsPerPage)
	{
		pItem = *mIndex;

		// make a table per line so that it can be down-loaded faster
		*pOutputFile << "<table width=\"100%\" cellpadding=4 border=0 cellspacing=0 bgcolor=\""
					 << BGColor[(Color++)%2]
					 << "\">\n<tr valign=middle><td width=\"60%\">";
		
		// Bold title?
		if (pItem->IsBoldTitle())
		{
			*pOutputFile << "<b>";
		}

		// Insert image if it is new
		if (IsNewItem(pItem))
		{
			 *pOutputFile << "<img height=11 width=28 alt=\"[NEW!]\" src=\""
						  << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
						  << NewIconURL
						  << "\">";
		}

		// Insert image if it is hot
		if (IsHotItem(pItem))
		{
			*pOutputFile << "<img height=11 width=28 alt=\"[HOT!]\" src=\""
						 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
						 << HotIconURL
						 << "\">";
		}

		// Print the item and the link
		*pOutputFile << "<a href=\"";
//		if (Color%2 == 0)
//		{
//			*pOutputFile << TempCGIPath;
//		}
//		else
//		{
			*pOutputFile <<	mpMarketPlace->GetCGIPath(PageViewItem);
//		}
		*pOutputFile <<	"eBayISAPI.dll?ViewItem&item="
					 <<	pItem->GetId()
					 <<	"\">"
					 << pItem->GetTitle()
					 << "</a>";

		// Bold title?
		if (pItem->IsBoldTitle())
		{
			*pOutputFile << "</b>";
		}

		// Insert image if it has pics
		if (pItem->HasPic())
		{
			*pOutputFile << "<img height=11 width=28 alt=\"[PIC!]\" src=\""
						 <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
						 << PicIconURL
						 << "\">";
		}

		*pOutputFile << "</td>";

		// Print the current item price
		sprintf(PriceString, "<b>$%.2f</b>", pItem->GetPrice());
		*pOutputFile << "<td align=right width=\"12%\">"
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
		*pOutputFile << "<td align=center width=\"6%\">"
					 << BidCountString
					 << "</td>"; 

		// Print ending time
		EndingTime = pItem->GetEndTime();
		pEndTime = localtime(&EndingTime);

		if (EndingTime >= mCreatingTime && difftime(EndingTime, mCreatingTime) < THREE_HOURS)
		{
			// make it red
			sprintf(TimeString, "<font color=\"#FF0000\">%2.2d/%2.2d %2.2d:%2.2d</font>", 
				pEndTime->tm_mon+1, 
				pEndTime->tm_mday, 
				pEndTime->tm_hour, 
				pEndTime->tm_min);
		}
		else
		{
			sprintf(TimeString, "%2.2d/%2.2d %2.2d:%2.2d", 
				pEndTime->tm_mon+1, 
				pEndTime->tm_mday, 
				pEndTime->tm_hour, 
				pEndTime->tm_min);
		}

		*pOutputFile << "<td align=center width=\"15%\">"
					 << TimeString
					 << "</td></tr></table>\n";

		mIndex++;
		i++;
	}
}

void clsItemPortion::PrintCompletedItems(ostream* pOutputFile, int CurrentPage)
{
	int			i;
	clsListingItem*	pItem;
	char		PriceString[50];

	i = 0;
	while (mIndex != mpItems->end() && i < mNumberItemsPerPage)
	{
		pItem = *mIndex;

		// make a table per line so that it can down load fast
		*pOutputFile << "<table width=\"100%\" border=0>\n"
					 << "<tr><td width=\"65%\" valign=top>";
		
		// Print the item and the link
		*pOutputFile << "<a href=\""
					 <<	mpMarketPlace->GetCGIPath(PageViewItem)
					 <<	"eBayISAPI.dll?ViewItem&item="
					 <<	pItem->GetId()
					 <<	"\">"
					 << pItem->GetTitle()
					 << "</a></td>";

		// Print the current item price
		if (pItem->GetBidCount() == 0)
		{
			// start price
			sprintf(PriceString, "No bid at <b>$%.2f</b>", pItem->GetPrice());
		}
		else
		{
			// bid price
			sprintf(PriceString, "Bid at <b>$%.2f</b>", pItem->GetPrice());
		}
		*pOutputFile << "<td width=\"34%\" align=left valign=top>"
					 << PriceString
					 << "</td></tr></table>\n"; 

		mIndex++;
		i++;
	}
}


bool clsItemPortion::IsNewItem(clsListingItem* pItem)
{
	if (difftime(mCreatingTime, pItem->GetStartTime()) < ONE_DAY)
	{
		return true;
	}

	return false;
}

bool clsItemPortion::IsHotItem(clsListingItem* pItem)
{
	return (pItem->GetBidCount() > mHotItemCount && pItem->IsReserved() == false);
}

// Check whethere there is more items to print
bool clsItemPortion::MoreItems()
{
	return mIndex != mpItems->end();
}

