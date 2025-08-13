/*	$Id: clsDailyStatsApp.h,v 1.2 1999/02/21 02:30:50 josh Exp $	*/
//
//	File:	clsDailyStatsApp.h
//
//	Class:	clsDailyStatsApp
//
//	Author:	Wen Wen
//
//	Function:
//			Daily Statistics
//
// Modifications:
//				- 10/07/97	Wen - Created
//
#ifndef CLSDAILYSTATSAPP_INCLUDED
#define	CLSDAILYSTATSAPP_INCLUDED

#include <time.h>

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsCategories;
class clsCategoryInfo;


class clsDailyStatsApp : public clsApp
{
public:
	clsDailyStatsApp();
	~clsDailyStatsApp();

	bool Run(time_t StartTime, time_t EndTime, int StatisticsId, int XactionId);
	void GetCategoryInfo(clsCategoryInfo* pCatInfo);

protected:
	clsDatabase			*mpDatabase;
	clsMarketPlaces		*mpMarketPlaces;
	clsCategories		*mpCategories;
};

#endif // CLSDAILYSTATSAPP_INCLUDED
