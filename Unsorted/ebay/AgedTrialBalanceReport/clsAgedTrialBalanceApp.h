/*	$Id: clsAgedTrialBalanceApp.h,v 1.3.202.1 1999/07/12 22:48:19 sliang Exp $	*/

//
//	File:	clsAgedTrailBalanceApp.h
//
//	Class:	clsAgedTrailBalanceApp
//
//	Author:	inna markov (inna@ebay.com)
//
//	Function:
//	create Aged Trial Balance report as of end of month
//
// Modifications:
//				- 09/28/98 inna	- Created
//
#ifndef CLSAGEDTRIALBALANCEAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;

class clsAgedTrailBalanceApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsAgedTrailBalanceApp(unsigned char *pRequestRec);
		~clsAgedTrailBalanceApp();
		
		// Runner
		void Run(time_t tInvoiceDate, int idStart, int idEnd);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;
};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSAGEDTRAILBALANCEAPP_INCLUDED 1
#endif /* CLSAGEDTRAILBALANCEAPP_INCLUDED */
