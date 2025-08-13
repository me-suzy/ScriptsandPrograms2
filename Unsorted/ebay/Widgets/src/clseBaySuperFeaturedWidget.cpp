/*	$Id: clseBaySuperFeaturedWidget.cpp,v 1.5 1999/03/07 08:15:17 josh Exp $	*/
//
//	File:	clseBaySuperFeaturedWidget.cpp
//
//	Class:	clseBaySuperFeaturedWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows super-featured items using clseBayItemWidget.
//
// Modifications:
//				- 10/14/97	Poon - Created
//
#include "widgets.h"
#include "clseBaySuperFeaturedWidget.h"

clseBaySuperFeaturedWidget::clseBaySuperFeaturedWidget(clsMarketPlace *pMarketPlace) :
	clseBayItemWidget(pMarketPlace)
{
			mCatId=0;
			mIncludeCategoryFeatured=false;
}

clseBaySuperFeaturedWidget::~clseBaySuperFeaturedWidget()
{
}

void clseBaySuperFeaturedWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("includecategoryfeatured", cName)==0))
		{
			SetIncludeCategoryFeatured(strcmp(cValue,"true")==0);
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
void clseBaySuperFeaturedWidget::GetItemIds(vector<int> *pvItemIds)
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
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Featured Item Ids for Category " << mCatId << "\n";
	}

	if (pItems)
	{
		if (mIncludeCategoryFeatured)
			pItems->GetAllFeaturedItemIds(pvItemIds, CurrentTime, mCatId);
		else
			pItems->GetSuperFeaturedItemIds(pvItemIds, CurrentTime, mCatId);
	}


	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Featured Item Ids for Category " << mCatId << "\n\n";
	}

}
