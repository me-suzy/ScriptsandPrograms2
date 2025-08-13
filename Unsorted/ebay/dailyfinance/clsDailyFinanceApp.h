/*	$Id: clsDailyFinanceApp.h,v 1.2 1999/02/21 02:30:47 josh Exp $	*/
//
//	File:	clsDailyFinanceApp.h
//
//	Class:	clsDailyFinanceApp
//
//	Author:	Wen Wen
//
//	Function:
//			Daily Statistics
//
// Modifications:
//				- 10/07/97	Wen - Created
//
#ifndef CLSDAILYFINANCEAPP_INCLUDED
#define	CLSDAILYFINANCEAPP_INCLUDED

#include <time.h>

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;


class clsDailyFinanceApp : public clsApp
{
public:
	clsDailyFinanceApp();
	~clsDailyFinanceApp();

	bool Run(time_t StartTime, time_t EndTime);

protected:
	clsDatabase			*mpDatabase;
	clsMarketPlaces		*mpMarketPlaces;
};

#endif // CLSDAILYFINANCEAPP_INCLUDED
