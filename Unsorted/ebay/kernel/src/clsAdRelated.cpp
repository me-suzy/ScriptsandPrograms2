/*	$Id: clsAdRelated.cpp,v 1.3 1998/06/30 09:10:48 josh Exp $	*/
//
//	File:		clsAdRelated.cc
//
// Class:	clsAdRelated
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Ad related information
//
// Modifications:
//				- 12/04/97 Wen	- Created
//

#include "eBayKernel.h"
#include "clsAdRelated.h"

clsAdRelated::clsAdRelated(clsMarketPlace *pMarketPlace)
{
	// Choose your database folks
	mpMarketPlace	= pMarketPlace;
}

void clsAdRelated::GetPageViews(int PageType, int* pPageViews)
{
	gApp->GetDatabase()->GetPageViews(mpMarketPlace->GetId(),
										PageType,
										pPageViews);
}

void clsAdRelated::GetDailyAds(int PageType, void** pAdVectorArray)
{
	time_t	tNow;
	time_t	tTomorrow;

	// get today's time and tomorrow's time
	tNow = time(0);
	tTomorrow = tNow + ONE_DAY;

	gApp->GetDatabase()->GetAds(PageType, 
								tNow,
								tTomorrow,
								pAdVectorArray);
}
