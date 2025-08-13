/*	$Id: clseBayUserBiddingDetailWidget.cpp,v 1.3 1998/12/06 05:23:20 josh Exp $	*/
//
//	File:	clseBayUserBiddingDetailWidget.cpp
//
//	Class:	clseBayUserBiddingDetailWidget
//
//	Author:	Poon
//
//	Function:
//			Shows items that a user is bidding, in detail.
//
//			This is based on a clseBayTableWidget.
//
// Modifications:
//				- 11/21/97	Poon - Created
//
#include "widgets.h"
#include "clseBayUserBiddingDetailWidget.h"
#include "clseBayItemDetailWidget.h"

clseBayUserBiddingDetailWidget::clseBayUserBiddingDetailWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
		mpUser = NULL;
		mDaysSince = 3;
		mSortCode = SortItemsByEndTimeReverse;
		mHeaderColor[0] = '\0';
		mNeedToDeleteUser = false;
}

clseBayUserBiddingDetailWidget::~clseBayUserBiddingDetailWidget()
{
	ItemList::iterator i;

	// delete all the items
	for (i=mvItems.begin(); i!=mvItems.end(); i++)
	{
		delete (*i).mpItem;
	}

	mvItems.erase(mvItems.begin(), mvItems.end());

	if (mNeedToDeleteUser && mpUser) delete mpUser;
}

void clseBayUserBiddingDetailWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("userid", cName)==0))
		{
			SetUser(mpMarketPlace->GetUsers()->GetUser(cValue, false, false));
			mNeedToDeleteUser = true;
			handled=true;
		}
		if ((!handled) && (strcmp("dayssince", cName)==0))
		{
			SetDaysSince(atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("sortcode", cName)==0))
		{
			SetSortCode((ItemListSortEnum)atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("headercolor", cName)==0))
		{
			SetHeaderColor(cValue);
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
	clseBayTableWidget::SetParams(pvArgs);

}

// Get the items from the database
bool clseBayUserBiddingDetailWidget::Initialize()
{
	// safety
	if (!mpMarketPlace) return false;

	// safety
	if (!mpUser) return false;

	// get 'em (1st true means get more stuff, 2nd true means include private)
	mpUser->GetBidItems(&mvItems, mDaysSince, true, mSortCode, true);

	// set the number of cells (each item will take 1 cell)
	mNumItems = mvItems.size();

	// set the number of columns appropriately
	mNumCols = 1;

	return true;
}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayUserBiddingDetailWidget::EmitCell(ostream *pStream, int n)
{
	ItemList::iterator		i;
	int						ii;
	clsItem					*pItem = NULL;
	clseBayItemDetailWidget *idw;

	// get the item that we're dealing with

	//
	// ** NOTE ** 
	// YES, you see this. I don't know how else to do this.
	// ** NOTE **
	for (i = mvItems.begin(), ii = 0;
		 i != mvItems.end() && ii != n;
		 i++, ii++)
	{
		;
	}

	
	if (i == mvItems.end())
		return false;

	pItem =	(*i).mpItem;

	// safety
	if (!pItem) return false;

	*pStream <<		"<TD>";

	idw = new clseBayItemDetailWidget(mpMarketPlace);
	idw->SetItem(pItem);
	idw->SetColor(mHeaderColor);
	idw->SetMode(clseBayItemDetailWidget::Bidder);
	idw->EmitHTML(pStream);
	delete idw;

	*pStream <<		"</TD>";

	return true;
}

// Just tell user how things are sorted
bool clseBayUserBiddingDetailWidget::EmitPreTable(ostream *pStream)
{
	// Items I bid on
	*pStream <<		"<p align=\"center\">"
					"<FONT size=4><b>"
					"Items I'm Bidding On"
					"</b></FONT>";

	*pStream <<		"<br>";

	// Tell how it's sorted
	*pStream <<		"<FONT size=2>";

	switch (mSortCode)
	{
		case SortItemsById:
			*pStream <<		"Sorted by Item #";
			break;

		case SortItemsByStartTime:
			*pStream <<		"Sorted by Start Time";
			break;

		case SortItemsByEndTime:
			*pStream <<		"Sorted by End Time";
			break;

		case SortItemsByPrice:
			*pStream <<		"Sorted by Current Price";
			break;

		case SortItemsByTitle:
			*pStream <<		"Sorted by Item Title";
			break;

		case SortItemsByTitleReverse:
			*pStream <<		"Sorted by Item Title (reverse)";
			break;

		case SortItemsByHighBidder:
			*pStream <<		"Sorted by High Bidder";
			break;

		case SortItemsByIdReverse:
			*pStream <<		"Sorted by Item # (reverse)";
			break;

		case SortItemsByStartTimeReverse:
			*pStream <<		"Sorted by Start Time (reverse)";
			break;

		case SortItemsByEndTimeReverse:
			*pStream <<		"Sorted by End Time (reverse)";
			break;

		case SortItemsByPriceReverse:
			*pStream <<		"Sorted by Current Price (reverse)";
			break;

		case SortItemsByStartPrice:
			*pStream <<		"Sorted by Start Price";
			break;

		case SortItemsByStartPriceReverse:
			*pStream <<		"Sorted by Start Price (reverse)";
			break;

		case SortItemsByReservePrice:
			*pStream <<		"Sorted by Reserve Price";
			break;

		case SortItemsByReservePriceReverse:
			*pStream <<		"Sorted by Reserve Price (reverse)";
			break;

		case SortItemsByBidCount:
			*pStream <<		"Sorted by Bid Count";
			break;

		case SortItemsByBidCountReverse:
			*pStream <<		"Sorted by Bid Count (reverse)";
			break;

		case SortItemsByQuantity:
			*pStream <<		"Sorted by Quantity";
			break;

		case SortItemsByQuantityReverse:
			*pStream <<		"Sorted by Quantity (reverse)";
			break;

		case SortItemsByUnknown:
		default:
			*pStream <<		"&nbsp;";
			break;
	}

	*pStream <<		"</FONT>";

	// just so they know
	if (mNumItems == 0)
	{
		*pStream <<	"<p>No items to display.</p>";
	}

	return true;
}