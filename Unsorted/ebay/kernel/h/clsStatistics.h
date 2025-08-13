/*	$Id: clsStatistics.h,v 1.2 1998/06/23 04:28:24 josh Exp $	*/
//
//	File:		clsStatistics.h
//
// Class:	clsStatistics
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents a collection of statistics
//
// Modifications:
//				- 10/07/97 wen	- Created
//
#ifndef CLSSTATISTICS_INCLUDED
#define CLSSTATISTICS_INCLUDED

#include "eBayTypes.h"
#include "time.h"
#include "clsDailyStatistics.h"
#include "clsDailyFinance.h"
#include "clsStatisticsTransaction.h"

// Class forward
class clsMarketPlace;
class clsCategoryInfo;

class clsStatistics
{
	public:
		// constructor and destructor
		clsStatistics(clsMarketPlace *pMarketPlace);
		~clsStatistics(){;}

		// Update daily stats
		void UpdateDailyStatistics( time_t Today, 
									int StatisticsId, 
									int TransactionId, 
									clsCategoryInfo* pCategoryInfo);

		// retrieve stats description
		void GetStatisticsDesc(StatisticsEnum StatisticsId, 	
							   StatsTransactionVector* pTransVector);
		
		// retrieve daily stats
		void GetDailyStatistics(time_t StartTime, 
								time_t EndTime, 
								int XactinId, 
								DailyStatsVector* pDailyStats);

		// Update finance information daily
		void UpdateDailyFinance(time_t Today);

		// retrieve data from ebay_finance
		void GetDailyFinance(
				time_t StartTime, 
				time_t EndTime,
				DailyFinanceVector* pvDailyFinance);

	protected:
		void DoDailyUpdate(time_t Today, int TransactionId, const char* pQuery, clsCategoryInfo* pCategoryInfo);
		void GetReportFinanceData(DailyFinanceVector* pvFinance,
								  DailyFinanceRawVector* pvRawFinance,
								  int MaxAction);

		clsMarketPlace *mpMarketPlace;
};

#endif // CLSSTATISTICS_INCLUDED
