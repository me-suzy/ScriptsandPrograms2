/*	$Id: clseBayItemWidget.cpp,v 1.8.62.3 1999/06/04 19:14:40 jpearson Exp $	*/
//
//	File:	clseBayItemWidget.cpp
//
//	Class:	clseBayItemWidget
//
//	Author:	Poon
//
//	Function:
//			Abstract base class for widgets that show a random set
//			of items. Will not show adult or black-listed items.
//
//			This is based on a clseBayTableWidget.
//
// Modifications:
//				- 10/23/97	Poon - Created
//

#include "widgets.h"


clseBayItemWidget::clseBayItemWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mShowPrice = true;
	mShowBidCount = false;
	memset(mName, 0, sizeof(mName));
	memset(mMoreLink, 0, sizeof(mMoreLink));
	memset(mMoreText, 0, sizeof(mMoreText));
	mNumPool = 0;
	memset(mFont, 0, sizeof(mFont));
	mFontSize = 0;
	mCountryId = -1;
	mCurrency = Currency_USD;
	mRegion = 0;
}

clseBayItemWidget::~clseBayItemWidget()
{
	vector<clsItem*>::iterator i;

	// delete all the items
	for (i=mvItems.begin(); i!=mvItems.end(); i++)
	{
		delete (*i);
	}
}

void clseBayItemWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("name", cName)==0))
		{
			SetName(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("moretext", cName)==0))
		{
			SetMoreText(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("morelink", cName)==0))
		{
			SetMoreLink(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("showprice", cName)==0))
		{
			SetShowPrice(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("showbidcount", cName)==0))
		{
			SetShowBidCount(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("font", cName)==0))
		{
			SetFont(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("fontsize", cName)==0))
		{
			SetFontSize(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("country", cName)==0))
		{
			this->SetCountry(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("currency", cName)==0))
		{
			SetCurrency(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("region", cName)==0))
		{
			SetRegion(atoi(cValue));
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

// Get the items from the database and choose a random set of them
bool clseBayItemWidget::Initialize()
{
	clsItems		*pItems;
//	clsCategories	*pCategories;
	vector<int>	vItemIds;		
	vector<int> vBlackListItemIds;		
	vector<int> vChosenItemIds;	
	clsItem		*pItem = NULL;
//	clsCategory *pCategory;
	int			i, x, vectorSize;
	bool		passed;
	time_t		CurrentTime;
	int			z = 0;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	// get the current time
	CurrentTime = time(0);
	// seed the random number generator with the current time
	srand((unsigned int)CurrentTime);

	// get the marketplace's clsItems and clsCategories object
	if (mpMarketPlace)
	{
		pItems = mpMarketPlace->GetItems();
//		pCategories = mpMarketPlace->GetCategories();

		// btw, don't need to delete these, because mpMarketPlace's
		//  dtor will delete them
	}
	else
		return false;

	// get the item ids of all active, black-listed auctions,
	//  and stuff the ids into a vector
	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Blacklisted Item Ids\n";
	}

	pItems->GetBlackListItemIds(&vBlackListItemIds, CurrentTime);

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Blacklisted Item Ids\n\n";
	}

	// get the relevant item ids and stuff the ids into a vector
	GetItemIds(&vItemIds);

	// get the size of the vector
	vectorSize = vItemIds.size();

	// remember the size for a cool HTML comment in EmitPreTable
	mNumPool = vectorSize;

	// if there aren't enough items in the vector, then reset mNumItems
	if (mNumItems > vectorSize) 
		mNumItems = vectorSize;

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Begin Random Selection of " << mNumItems << " out of a Pool of " << mNumPool << " Items\n";
	}

	// randomly choose the items
	for (i = 0; i < mNumItems; i++)
	{
		z = 0;	// reset timeout

		// keep choosing a random item until you get one passes these tests
		//  1) is not already chosen
		//	2) is not adult
		//	3) is not in the black list
		//	4) title isn't vulgar
		//  5) international test (Lena)
		//  6) item must be active (Pete)
		do
		{
			// get a random number between 0 and vectorSize
			//  (RAND_MAX is 32767 in VC++)
			x = (int)((float)vectorSize * ((float)rand() / (float)(RAND_MAX+1)));
			z++;				// increment timeout

			// get the item without the description
			pItem = pItems->GetItem(vItemIds[x], false);

			// prepare to do the checks
			passed = true;

			// 0) make sure the item actually still exists in the db
			if (passed)
			{
				if (!pItem)
				 passed = false;
			}

			// 1) check if item has already chosen been chosen
			if (passed)
			{
				if ((find(vChosenItemIds.begin(), vChosenItemIds.end(), vItemIds[x])
				!= vChosenItemIds.end()))
				passed = false;
			}
			
			// 2) check for an adult category
			if (passed)
			{
				// check for adultness
				if (pItem->IsAdult() || pItem->NoBidAndListForMinor())
					passed = false;
			}

			// 3) check the black list
			if (passed)
			{
				if ((find(vBlackListItemIds.begin(), vBlackListItemIds.end(), vItemIds[x])
					!= vBlackListItemIds.end()))
					passed = false;
			}

			// 4) check for vulgarity
			if (passed)
			{
				if (clsUtilities::TooVulgar(pItem->GetTitle())) 
					passed = false;
			}

			// 5) check for international
			if ((passed) && (mCountryId == 0))
			{
				if (!pItem->IsShippingInternationally())
					passed = false;
			}

			// 6) check for active state
			if (passed)
			{
				// compare current time against ending auction time
				if (CurrentTime > pItem->GetEndTime())
					passed = false;
			}

			// 7) check for currency
			if ((passed) && (mCurrency != pItem->GetCurrencyId()))
			{
				passed = false;
			}

			// 8) Check for Region ID
			if (passed && mRegion && mRegion != pItem->GetRegionID())
			{
				passed = false;
			}

			// if the item didn't pass all the tests, then delete it because
			//  we're not going to use it
			if (!passed)
			{
				delete pItem;
				pItem = NULL;
			}

		} while (!passed && (z < (vectorSize*5)));

		// ok, you got one, so add the item id to the "chosen" vector
		if (passed)
			vChosenItemIds.push_back(vItemIds[x]);

		// put the item into mvItems
		if (passed)
		{
			assert(pItem);
			mvItems.push_back(pItem);
		}

		// check for timeout. this might happen if there are aren't
		//  enough items that pass all the tests. this is a safety
		//  measure that prevents the above do loop to go on infinitely.
		if (!passed)
		{
			mNumItems = mvItems.size();
			break;		// don't try anymore
		}
	}

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Random Selection of " << mNumItems << " Items\n\n";
	}

	// add 1 to mNumItems to accomodate the more... link
	if (strlen(mMoreText)) 
		mNumItems++;

	return true;
}

// just an HTML comment showing the pool size
bool clseBayItemWidget::EmitPreTable(ostream *pStream)
{
	// emit the pool size as an HTML comment
	*pStream << "\n<!-- pool size = "
			 <<	mNumPool
			 << " -->";

	return true;

}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayItemWidget::EmitCell(ostream *pStream, int n)
{
	clsItem		*pItem;
	char		*cSuperCleanText = NULL;
	char		*cDelimitedSuperCleanText = NULL;
	char		*cSafeDelimitedSuperCleanText = NULL;
	char		cPrice[50];
	char		cName[255];

	// handle the more... link in a special way
	if ((strlen(mMoreText)) && (n == mNumItems-1))
	{
		// begin cell
		*pStream <<		"<TD align=\"right\">";

		// emit begin tags if user supplied them
		if (mBeginTags[0]!='\0')
			*pStream	<<	mBeginTags;
	
		// specify font and font size if needed
		if ((mFontSize>0) || (mFont[0]!='\0'))
		{
			if ((mFontSize>0) && (mFont[0]!='\0'))
				*pStream <<		"<FONT size=\"" << mFontSize << "\" face=\"" << mFont << "\">"; 	// both
			else if (mFontSize>0)
					*pStream <<		"<FONT size=\"" << mFontSize << "\">";	// just size
				else
					*pStream <<		"<FONT face=\"" << mFont << "\">";		// just face
		}

		*pStream <<		"<a href=\""
				 <<		mMoreLink
				 <<		"\">"
				 <<		mMoreText
				 <<		"</a>"
				 <<		"\n";

		// end font if needed
		if ((mFontSize>0) || (mFont[0]!='\0'))
			*pStream <<		"</FONT>";

		// emit end tags if user supplied them
		if (mEndTags[0]!='\0')
			*pStream	<<	mEndTags;	
		
		// end cell
		*pStream <<		"</TD>\n";

		return true;
	}

	// get the item from the vector that Initialize() prepared for me
	pItem = mvItems[n];

	// check if we want to use the generic name
	if (strlen(mName) > 0)
	{
		sprintf(cName, "%s%d", mName, n+1);
	}
	else
	{
		// make the text super-clean
		cSuperCleanText = clsUtilities::SuperClean(pItem->GetTitle());

		// delimit the text in case it's one big word
		cDelimitedSuperCleanText = clsUtilities::Delimit(cSuperCleanText);

		// make the text safe
		cSafeDelimitedSuperCleanText = clsUtilities::StripHTML(cDelimitedSuperCleanText);

		strncpy(cName, cSafeDelimitedSuperCleanText, sizeof(cName)-1);
	}

	// begin cell
	*pStream <<		"<TD valign=\"top\">";

	// emit begin tags if user supplied them
	if (mBeginTags[0]!='\0')
		*pStream	<<	mBeginTags;	

	// specify font and font size if needed
	if ((mFontSize>0) || (mFont[0]!='\0'))
	{
		if ((mFontSize>0) && (mFont[0]!='\0'))
			*pStream <<		"<FONT size=\"" << mFontSize << "\" face=\"" << mFont << "\">"; 	// both
		else if (mFontSize>0)
			*pStream <<		"<FONT size=\"" << mFontSize << "\">";	// just size
		else
			*pStream <<		"<FONT face=\"" << mFont << "\">";		// just face
	}

	// output the item title + price
	*pStream <<		"<a href="
					"\""
			 <<		mpMarketPlace->GetCGIPath(PageViewItem)
			 <<		"eBayISAPI.dll?ViewItem&item="
			 <<		pItem->GetId()
			 <<		"\">"
			 <<		cName		// could be empty
			 <<		"</a>";


	// output the price if necessary
	if (mShowPrice)
	{
		// get and format the price
		sprintf(cPrice, "%.2f", 
			((pItem->GetPrice() > 0) ? pItem->GetPrice() : pItem->GetStartPrice()));

		*pStream <<		" at $"
				 <<		cPrice;
	}

	// output the bid count if necessary
	if (mShowBidCount)
	{
		*pStream <<		" ("
				 <<		pItem->GetBidCount()
				 <<		" bids)";
	}

	// end font if needed
	if ((mFontSize>0) || (mFont[0]!='\0'))
		*pStream <<		"</FONT>";

	// emit end tags if user supplied them
	if (mEndTags[0]!='\0')
		*pStream	<<	mEndTags;	
	
	// end cell
	*pStream <<		"</TD>\n";

	// delete the new strings
	delete [] cSuperCleanText;
	delete [] cDelimitedSuperCleanText;
	delete [] cSafeDelimitedSuperCleanText;

	return true;
}

// return the given item
clsItem* clseBayItemWidget::GetItem(int n)
{
	return mvItems[n];
}
