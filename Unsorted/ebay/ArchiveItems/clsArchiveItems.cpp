//
//	File:	EndingItems.cpp
//
//	Class:	endItemsApp
//
//	Author:	lena
//
//	Function:
//
//		Move items from ebay_items_ended to ebay_items_arc_MMYY
//


#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsArchiveItems.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"


clsArchiveItemsApp::clsArchiveItemsApp()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsArchiveItemsApp::~clsArchiveItemsApp()
{
	return;
};


void clsArchiveItemsApp::Run(char *startDate, char *endDate)
{
	// logging 
	int				itemId = 0;

	// This is the vector of itemids that met our criteria
	// (date range)
	vector<int>						vItems;
	vector<int>::iterator			i;
	clsItem							*currentItem;


	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	// First, let's get the item ids 		

	mpDatabase->GetItemsToArchive(0, &vItems, startDate, endDate);
	if (vItems.size() == 0)
		return;
	
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
		// Get the item
	{
			currentItem = mpMarketPlace->GetItems()->GetItemEnded((*i), true);

		if (currentItem != NULL)
			mpDatabase->ArchiveItem(currentItem);

		delete currentItem;
	}
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


void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tArchiveDesc [-s mm/dd/yy/hh] [-e mm/dd/yy/hh]\n");
}

static clsArchiveItemsApp *pTestApp = NULL;


int main(int argc, char* argv[])
{
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif
	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	time_t	StartTime = 0;
	time_t	EndTime = 0;
	char fromdate[64];
	char todate[64];
	struct tm*	psTime;
	struct tm*	peTime;

	if (!pTestApp)
	{
		pTestApp	= new clsArchiveItemsApp();
	}
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
	
	if (StartTime == 0 && EndTime == 0)
	{
		time(&EndTime);
		StartTime = EndTime - ONE_DAY; // if no time is given, we'll make for 24 hours
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


	return 0;
}

