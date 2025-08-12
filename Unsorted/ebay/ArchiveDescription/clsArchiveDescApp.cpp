//
//	File:	clsArchiveDescApp.cpp
//
//	Class:	clsArchiveDescApp
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//		Original function is a hack to copy item description to archive table
//		Now it is being used to wade through everything in a group of item's
//		description field looking for some string(s). Find in description.
//		saves the items list into a table (ebay_items_bad).
//
// Modifications:
//				- 10/27/97 tini	- Created
//

#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsArchiveDescApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

clsArchiveDescApp::clsArchiveDescApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsArchiveDescApp::~clsArchiveDescApp()
{
	return;
};

void clsArchiveDescApp::ConvertLower(char *pText)
{
	char *cp;
	for (cp = pText; *cp; ++cp)
	  *cp = tolower(*cp);

};

void clsArchiveDescApp::Run(char *timefrom, char *timeto)
{
	// logging 
	FILE			*pArcAuctionLog;
	char			fname[25];
	struct tm*		pTime;
    time_t          runTime;
    time_t          nowTime;
	struct tm*      LocalTime;


	// This is the vector of itemids that met our criteria
	// (date range)
	vector<int>						vItems;
	vector<int>::iterator			i;
	clsItem							*pItem;

	char strtofind1[] =    "assword";
	char strtofind2[] =    "<script";
	char *pDest1;
	char *pDest2;
	char *pDescript;

	runTime = time(0);
	pTime = localtime(&runTime);

	sprintf(fname, "descrun%04d%02d%02d.txt", 
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday);

	// File shenanigans
	pArcAuctionLog	= fopen(fname, "a");

	if (!pArcAuctionLog)
	{
		fprintf(stderr,"%s:%d Unable to open archive log. \n",
			  __FILE__, __LINE__);
	}

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	// the first time, clear the bad items table

	nowTime = time(0);
	LocalTime = localtime(&nowTime);

	fprintf(pArcAuctionLog,
		"%2d/%2d/%2d %2d:%2d:%2d\t Start getting all items to Archive.\n",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// First, let's get the item ids
	// condition of this kernel function is hacked locally to get the
	// right items from the right table(s).
	gApp->GetDatabase()->GetItemsByEndDate(mpMarketPlace->GetId(),
			&vItems, timefrom, timeto);
//	mpDatabase->GetItemsToArchive(mpMarketPlace->GetId(), &vItems,
//		timefrom, timeto);

	nowTime = time(0);
	LocalTime = localtime(&nowTime);

	fprintf(pArcAuctionLog,
		"%2d/%2d/%2d %2d:%2d:%2d\t Done getting all items to review.\n",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// Now, we loop through them, updating their descriptions
	// OR check for some words in the descriptions and put into 
	// ItemsToArchive table
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		// Get the item with description
		pItem	= mpItems->GetItem((*i),true);
		if (!pItem)
		{
			fprintf(stderr, "** Error ** Could not get item %d\n",
					(*i));
			continue;
		}
// assuming only active items not archived 
//		else 
//		{
//			mpDatabase->GetItemDescription(0, pItem->GetId(), pItem);
			// put to archive
//			mpDatabase->AddItemDescArc(pItem);

			// copy and delete the item
//			mpDatabase->RemoveItem(pItem);
//			mpItems->ArchiveItem(pItem);
//		}

		// OR insert to items to archive if some words exists
		if (pItem->GetDescription())
		{
			pDescript = pItem->GetDescription();
			ConvertLower(pItem->GetDescription());
			pDescript = pItem->GetDescription();
			ConvertLower(pItem->GetDescription());
			pDest1 = strstr(pDescript, strtofind1);
			pDest2 = strstr(pDescript, strtofind2);
			if (pDest1 != NULL && pDest2 != NULL)
				mpDatabase->SetItemsToBad(pItem->GetId(),3);
				else if (pDest1 != NULL)
				mpDatabase->SetItemsToBad(pItem->GetId(),1);
				else if (pDest2 != NULL)
				mpDatabase->SetItemsToBad(pItem->GetId(),2);
		};
		// end of alternate

		delete pItem;
	}

	nowTime = time(0);
	LocalTime = localtime(&nowTime);
			
	fprintf(pArcAuctionLog,
		"%2d/%2d/%2d %2d:%2d:%2d Done looking at %d Items. ",
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec,
		vItems.size());

	// cleanup
	vItems.erase(vItems.begin(), vItems.end());

}

bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	int		Day;
	int		Month;
	int		Year;
	int		Hour;
	struct tm*	pTimeTm;

	char	Sep[] = "/";
	char*	p;

	// Get day
	p = strtok(pTime, Sep);
	Month = atoi(p);
	if (Month < 1 || Month > 12)
	{
		return false;
	}

	// Get month
	p = strtok(NULL, Sep);
	Day = atoi(p);
	if (Day < 1 || Day > 31)
	{
		return false;
	}

	// Get Year
	p = strtok(NULL, Sep);
	Year = atoi(p);
	if (Year < 0)
	{
		return false;
	}
	
	// Get Hour of the day
	p = strtok(NULL, Sep);
	Hour = atoi(p);
	if (Hour < 0 || Hour > 23)
	{
		return false;
	}

	// put the day, month, and year together
	*pTimeTValue = time(0);
	pTimeTm = localtime(pTimeTValue);
	pTimeTm->tm_mday = Day;
	pTimeTm->tm_mon = Month-1;
	pTimeTm->tm_year = Year;
	pTimeTm->tm_hour = Hour;
	*pTimeTValue = mktime(pTimeTm);

	return true;
}

static clsArchiveDescApp *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tFindInDesc [-s mm/dd/yyyy/hh] [-e mm/dd/yyyy/hh]\n");
}

int main(int argc, char* argv[])
{
	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	time_t	StartTime = 0;
	time_t	EndTime = 0;
	char fromdate[64];
	char todate[64];
	struct tm*	psTime;
	struct tm*	peTime;

	// we need this for Oracle to be able to connect
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif

	while (--argc)
	{
		switch (argv[Index][1])
		{
		// Get starting date
		case 's':
			pStartDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pStartDate, &StartTime))
			{
				InputError();
				exit(0);
			}
			break;

		// Get ending date
		case 'e':
			pEndDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pEndDate, &EndTime))
			{
				InputError();
				exit(0);
			}
			break;

		default:
			InputError();
			return 0;
		}
	}

	if (StartTime == 0 && EndTime != 0)
	{
		printf("Start Date is required when End Date is provided.\n");
		return 0;
	}

	if (!pTestApp)
	{
		pTestApp	= new clsArchiveDescApp(0);
	}

	psTime = localtime(&StartTime);

	// make fromdate and todate = StartTime and EndTime
	sprintf(fromdate, "19%2.2d-%2.2d-%2.2d %2.2d:00:00", 
			psTime->tm_year, 
			psTime->tm_mon+1,
			psTime->tm_mday,
			psTime->tm_hour);

	peTime = localtime(&EndTime);
	sprintf(todate, "19%2.2d-%2.2d-%2.2d %2.2d:00:00", 
			peTime->tm_year, 
			peTime->tm_mon+1,
			peTime->tm_mday,
			peTime->tm_hour);

	pTestApp->InitShell();
	pTestApp->Run((char *)&fromdate, (char *)&todate);

//	delete pTestApp;

	return 0;
}

