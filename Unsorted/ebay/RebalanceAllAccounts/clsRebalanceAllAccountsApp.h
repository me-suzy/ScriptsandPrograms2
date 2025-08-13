/*	$Id: clsRebalanceAllAccountsApp.h,v 1.2 1999/02/21 02:23:39 josh Exp $	*/
//
//	File:	clsRebalanceAllAccountsApp.h
//
//	Class:	clsRebalanceAllAccountsApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Rebalances ALL accounts.
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSREBALANCEALLACCOUNTSAPP_INCLUDE

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;

class clsRebalanceAllAccountsApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsRebalanceAllAccountsApp(unsigned char *pRequestRec);
		~clsRebalanceAllAccountsApp();
		
		// Runner
		void Run();

	private:
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSREBALANCEALLACCOUNTSAPP_INCLUDE 1
#endif /* CLSREBALANCEALLACCOUNTSAPP_INCLUDE */
