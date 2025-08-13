/*	$Id: clsDailyFinanceApp.cpp,v 1.3 1999/02/21 02:30:46 josh Exp $	*/
//
//	File:		clsDailyFinanceApp.cpp
//
// Class:	clsDailyFinanceApp
//
//	Author:	Wen Wen
//
//	Function:
//			Daily Statistics
//
// Modifications:
//				- 10/07/97	Wen - Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsDailyFinanceApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsStatistics.h"

#define ONE_DAY	24*60*60

clsDailyFinanceApp::clsDailyFinanceApp()
{
	mpDatabase			= (clsDatabase *)0;
	mpMarketPlaces		= (clsMarketPlaces *)0;
	
	return;
}


clsDailyFinanceApp::~clsDailyFinanceApp()
{
	return;
}

//
// Where are all actions
//
bool clsDailyFinanceApp::Run(time_t StartTime, time_t EndTime)
{
	clsMarketPlace*	pCurrentMarketPlace;
	clsStatistics*	pStatistics;

	// Initialize
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	pCurrentMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	pStatistics = pCurrentMarketPlace->GetStatistics();

	// Check whether statistics is on one only
	if (EndTime == 0)
	{
		if (StartTime == 0)
		{
			// Get the current time and update statitstics for the day before
			StartTime = time(0);
			StartTime -= 2*ONE_DAY;
		}
		pStatistics->UpdateDailyFinance(StartTime);
	}
	else
	{
		for (; StartTime <= EndTime; StartTime += ONE_DAY)
		{
			pStatistics->UpdateDailyFinance(StartTime);
		}
	}	

	return true;
}

