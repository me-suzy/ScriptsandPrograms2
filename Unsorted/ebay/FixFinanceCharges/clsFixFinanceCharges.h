/*	$Id: clsFixFinanceCharges.h,v 1.2 1999/02/21 02:22:07 josh Exp $	*/
//
//	File:	clsFixFinanceChargesApp.h
//
// Class:	clsFixFinanceChargesApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSFIXFINANCECHARGESAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;

class clsFixFinanceChargesApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsFixFinanceChargesApp(unsigned char *pRequestRec);
		~clsFixFinanceChargesApp();
		
		// Runner
		void Run(int BatchId);

	private:
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSFIXFINANCECHARGESAPP_INCLUDED 1
#endif /* CLSFIXFINANCECHARGESAPP_INCLUDED */
