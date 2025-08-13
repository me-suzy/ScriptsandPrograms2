/*	$Id: clsDailyStatsApp.cpp,v 1.3 1999/02/21 02:30:49 josh Exp $	*/
//
//	File:		clsDailyStatsApp.cpp
//
// Class:	clsDailyStatsApp
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
#include "clsDailyStatsApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsStatistics.h"
#include "clsCategoryInfo.h"

#define ONE_DAY	24*60*60

clsDailyStatsApp::clsDailyStatsApp()
{
	mpDatabase			= (clsDatabase *)0;
	mpMarketPlaces		= (clsMarketPlaces *)0;
	
	return;
}


clsDailyStatsApp::~clsDailyStatsApp()
{
	return;
}

//
// Where are all actions
//
bool clsDailyStatsApp::Run(time_t StartTime, time_t EndTime, int StatisticsId, int XactionId)
{
	clsMarketPlace*	pCurrentMarketPlace;
	clsStatistics*	pStatistics;
	clsCategoryInfo*	pCategoryInfo;

	// Initialize
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	pCurrentMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	pStatistics = pCurrentMarketPlace->GetStatistics();
	mpCategories = pCurrentMarketPlace->GetCategories();

	// Get all not leaf category ids and their leaf children
	pCategoryInfo = new clsCategoryInfo;
	GetCategoryInfo(pCategoryInfo);

	// Check whether statistics is on one only
	for (; StartTime <= EndTime; StartTime += ONE_DAY)
	{
		// decrement the bidcount in ebay_marketplaces_info if needed
		// pStatistics->DecrementBidCount(StartTime);

		// do regular statistics update
		pStatistics->UpdateDailyStatistics(StartTime, StatisticsId, XactionId, pCategoryInfo);

		// increment the bidcount in ebay_marketplaces_info
		// pStatistics->IncrementBidCount(StartTime);
	}

	delete pCategoryInfo;

	return true;
}

// get category info
//	Top Categories
//	Not leaf Categories and their kids
//
void clsDailyStatsApp::GetCategoryInfo(clsCategoryInfo* pCategoryInfo)
{
	CategoryVector	vCategory;
	CategoryVector::iterator	iCategory;
	vector<int>	vCategoryIds;
	vector<int>::iterator	iCategoryId;
	int*		pLeafCategoryIds;

	// Get the top level categroies;
	mpCategories->TopLevel(&vCategory);
	pCategoryInfo->SetTopCategories(&vCategory);
	for (iCategory = vCategory.begin(); iCategory != vCategory.end(); iCategory++)
	{
		delete *iCategory;
	}

	// Get Not leaf categories and their kids
	mpCategories->GetNotLeafCategoryIds(&vCategoryIds);
	for (iCategoryId = vCategoryIds.begin(); iCategoryId != vCategoryIds.end(); iCategoryId++)
	{
		mpCategories->GetChildLeafCategoryIds(*iCategoryId, &pLeafCategoryIds);
		pCategoryInfo->SetCategoryAndKids(*iCategoryId, pLeafCategoryIds);
		delete [] pLeafCategoryIds;
	}
}

