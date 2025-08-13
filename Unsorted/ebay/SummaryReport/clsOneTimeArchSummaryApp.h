/*	$Id: clsOneTimeArchSummaryApp.h,v 1.3 1999/02/21 02:24:34 josh Exp $	*/
//
//	File:	clsOneTimeArchSummaryApp.h
//
//	Class:	clsOneTimeArchSummaryApp
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
#ifndef CLSOneTimeArchSummaryAPP_INCLUDED

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

class clsOneTimeArchSummaryApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsOneTimeArchSummaryApp(unsigned char *pRequestRec);
		~clsOneTimeArchSummaryApp();
		
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

#define CLSOneTimeArchSummaryAPP_INCLUDED 1
#endif /* CLSOneTimeArchSummaryAPP_INCLUDED */
