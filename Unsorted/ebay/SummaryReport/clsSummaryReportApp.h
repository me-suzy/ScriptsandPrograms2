/*	$Id: clsSummaryReportApp.h,v 1.2 1999/02/21 02:24:38 josh Exp $	*/
//
//	File:	clsSummaryReportApp.h
//
//	Class:	clsSummaryReportApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Generates the total items and total price for a given time period
//
// Modifications:
//				- 10/01/97 tini	- Created
//
#ifndef CLSSUMMARYREPORTAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsBid;
class clsCategories;
class clsCategory;

class clsSummaryReportApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsSummaryReportApp(unsigned char *pRequestRec);
		~clsSummaryReportApp();
		
		// Runner
		void Run(char *fromdate, char *todate);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsCategories		*mpCategories;
		clsItems			*mpItems;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSSUMMARYREPORTAPP_INCLUDED 1
#endif /* CLSSUMMARYREPORTAPP_INCLUDED */
