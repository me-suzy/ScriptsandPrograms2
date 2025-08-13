/*	$Id: clseBayRandomItemWidget.cpp,v 1.5.166.1 1999/06/04 19:14:40 jpearson Exp $	*/
//
//	File:	clseBayRandomItemWidget.cpp
//
//	Class:	clseBayRandomItemWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows current items using clseBayItemWidget.
//
// Modifications:
//				- 10/14/97	Poon - Created
//
#include "widgets.h"
#include "clseBayRandomItemWidget.h"

clseBayRandomItemWidget::clseBayRandomItemWidget(clsMarketPlace *pMarketPlace) :
	clseBayItemWidget(pMarketPlace)
{
	mCatId = 0;
}

clseBayRandomItemWidget::~clseBayRandomItemWidget()
{
}

void clseBayRandomItemWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("categoryid", cName)==0))
		{
			SetCategoryId(atoi(cValue));
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

// Retrieve ids of all current items and stuff them into pvItemIds.
void clseBayRandomItemWidget::GetItemIds(vector<int> *pvItemIds)
{
	clsItems	*pItems = NULL;
	time_t		CurrentTime;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	CurrentTime = time(0);

	if (mpMarketPlace) pItems = mpMarketPlace->GetItems();

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Mystery Item Ids for Category " << mCatId << "\n";
	}

	// if (pItems) pItems->GetActiveItemIds(pvItemIds, CurrentTime, mCatId);
	// there is an optional parameter for item count at the end, though it's currently disabled in the kernel
	if (pItems) pItems->GetRandomItemIds(pvItemIds, CurrentTime, mCatId /*, howMany */);

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Mystery Item Ids for Category " << mCatId << "\n\n";
	}

}
