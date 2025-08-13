/*	$Id: clsStatistics.cpp,v 1.3 1998/06/30 09:11:42 josh Exp $	*/
//
//	File:		clsStatistics.cc
//
// Class:	clsStatistics
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Information for Daily Statistics
//
// Modifications:
//				- 10/06/97 Wen	- Created
//
#include "eBayKernel.h"
#include "clsStatistics.h"
#include "clsCategoryInfo.h"

clsStatistics::clsStatistics(clsMarketPlace *pMarketPlace)
{
	// Choose your database folks
	mpMarketPlace	= pMarketPlace;
}

// ****************************************
//
// Daily statistics routins -- begin
//

void clsStatistics::UpdateDailyStatistics(time_t Today, int StatisticsId, int TransactionId, clsCategoryInfo* pCategoryInfo)
{
	char	Query[501];
	StatsTransactionVector				TransVector;
	StatsTransactionVector::iterator	iTransaction;
	const char* pQuery;

	if (TransactionId != -1)
	{
		// Get transaction info
		gApp->GetDatabase()->GetTransactionQuery(
							TransactionId,
							(StatisticsEnum) StatisticsId, 
							Query,
							sizeof(Query));

		if (strlen(Query) == 0)
		{
			// do snothing
			return;
		}

		// Update transactions
		DoDailyUpdate(Today, TransactionId, Query, pCategoryInfo);
	}
	else
	{
		// Get transaction info
		gApp->GetDatabase()->GetStatisticsTransaction((StatisticsEnum) StatisticsId, &TransVector);

		// statistics on each transation type
		for (	iTransaction  = TransVector.begin(); 
			iTransaction != TransVector.end(); 
			iTransaction++)
		{
			// Get the Query
			pQuery = (*iTransaction)->GetQuery();

			if (pQuery == NULL || strlen(pQuery) == 0)
			{
				// do nothing
				continue;
			}

			// Update transactions
                	DoDailyUpdate(Today, (*iTransaction)->GetId(), pQuery, pCategoryInfo);

			// clean up the transaction object
			delete *iTransaction;
		}
	}
}

// Do the actual update for the daily statistics
//
void clsStatistics::DoDailyUpdate(time_t Today, int TransactionId, const char* pQuery, clsCategoryInfo* pCategoryInfo)
{
	int		index;
	int		categoryid;
	char*	pCategories;

	// update the statistics on leaf categories
	gApp->GetDatabase()->UpdateDailyStatisticsOnLeafCategories(
                                        pQuery,
                                        mpMarketPlace->GetId(),
                                        TransactionId,
                                        Today);

	// done for bid count stats
	if (TransactionId == 0)
	{
		return;
	}

	// update statistics on not leaf category
	index = 0;
	while ((categoryid = pCategoryInfo->GetCategoryId(index)) != -1)
	{
		pCategories = pCategoryInfo->GetLeafCategories(index++);
		gApp->GetDatabase()->UpdateDailyStatisticsOnNotLeafCategory(
					mpMarketPlace->GetId(),
					Today,
					TransactionId,
					categoryid,
					pCategories);
	}

	// summary for the day, a special case of the not leaf categories (ID = 0)
	pCategories = pCategoryInfo->GetTopLevelCategories();
	gApp->GetDatabase()->UpdateDailyStatisticsOnNotLeafCategory(
                                        mpMarketPlace->GetId(),
                                        Today,
                                        TransactionId,
                                        0,
                                        pCategories);

}
					
// retrieve vector of daily statistics
//
void clsStatistics::GetDailyStatistics( time_t StartTime, 
										time_t EndTime, 
										int XactionId, 
										DailyStatsVector* pDailyStats)
{
	gApp->GetDatabase()->GetDailyStatistics(mpMarketPlace->GetId(),
											StartTime, 
											EndTime,
											XactionId,
											0,
											pDailyStats);
}

// retrieve statistics description vector
void clsStatistics::GetStatisticsDesc(StatisticsEnum StatisticsId, 	
								StatsTransactionVector* pTransVector)
{
	gApp->GetDatabase()->GetStatisticsTransaction(StatisticsId, pTransVector);
}

//
// Daily statistics routins -- End
//
// ****************************************

// ****************************************
//
//	Daily finance routins - begin
//
void clsStatistics::UpdateDailyFinance(time_t Today)
{
	gApp->GetDatabase()->UpdateDailyFinance(Today);
}

void clsStatistics::GetDailyFinance(
		time_t StartTime, 
		time_t EndTime,
		DailyFinanceVector* pvDailyFinance)
{
	DailyFinanceRawVector	vDailyFinanceRaw;
	int		MaxAction;

	// Retrieve the raw finance data
	gApp->GetDatabase()->GetDailyFinance(StartTime, EndTime, &vDailyFinanceRaw, &MaxAction);

	// Get needed data  from the raw
	GetReportFinanceData(pvDailyFinance, &vDailyFinanceRaw, MaxAction);

	return;
}

//
//	Get Report data from the raw data
//
void clsStatistics::GetReportFinanceData(DailyFinanceVector* pvFinance,
										 DailyFinanceRawVector* pvRawFinance,
										 int MaxAction)
{
	DailyFinanceRawVector::iterator	iDailyFinanceRaw;
	time_t	TheDay;
	clsDailyFinance*	pDailyFinance;

	if (pvRawFinance->size() == 0)
	{
		return;
	}

	//
	iDailyFinanceRaw = pvRawFinance->begin();
	TheDay = (*iDailyFinanceRaw )->GetDate();
	pDailyFinance = new clsDailyFinance(MaxAction);
	pDailyFinance->SetDate(TheDay);

	for (;
		 iDailyFinanceRaw != pvRawFinance->end();
		 iDailyFinanceRaw++)
	{
		if (TheDay != (*iDailyFinanceRaw )->GetDate())
		{
			// keep the existing object
			pvFinance->push_back(pDailyFinance);

			// start a new object
			TheDay = (*iDailyFinanceRaw )->GetDate();
			pDailyFinance = new clsDailyFinance(MaxAction);
			pDailyFinance->SetDate(TheDay);
		}

		// pass the data to daily finance
		pDailyFinance->SetData(*iDailyFinanceRaw);

		// clean up
		delete (*iDailyFinanceRaw);
	}

	// keep the existing object
	pvFinance->push_back(pDailyFinance);
}

//
//	Daily finance routins - End
//
// ****************************************
