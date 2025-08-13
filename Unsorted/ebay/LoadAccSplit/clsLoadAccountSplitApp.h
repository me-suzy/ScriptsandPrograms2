/*	$Id: clsLoadAccountSplitApp.h,v 1.3 1999/02/21 02:23:25 josh Exp $	*/
//
//	File:	clsLoadAccountSplitApp.h
//
//	Class:	clsLoadAccountSplitApp
//
//	Author:	inna
//
//	Function:
//
//		Loads New ebay_accounts_# tables from bay_account table
//
// Modifications:
//				- 01/07/98 inna	- created
//
#ifndef CLSLOADACCOUNTSPLITAPP_INCLUDED

#include <vector.h>
#include "clsApp.h"


// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;


class clsLoadAccountSplitApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsLoadAccountSplitApp(unsigned char *pRequestRec);
		~clsLoadAccountSplitApp();
		
		// Runner
		void Run(int FromUser,int ToUser, time_t StartTime);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

		//ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSLOADACCOUNTSPLITAPP_INCLUDED 1
#endif /* CLSSUMMARYREPORTAPP_INCLUDED */
