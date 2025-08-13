/*	$Id: clsLoadTestDataApp.h,v 1.2 1999/02/21 02:23:27 josh Exp $	*/
//
//	File:	clsLoadTestDataApp.h
//
// Class:	clsLoadTestDataApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSLOADTESTDATAAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsCategories;

class clsLoadTestDataApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsLoadTestDataApp(unsigned char *pRequestRec);
		~clsLoadTestDataApp();
		
		// Runner
		void Run();

	private:
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;
		clsCategories		*mpCategories;
		clsItems			*mpItems;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSLOADTESTDATAAPP_INCLUDED 1
#endif /* CLSLOADTESTDATAAPP_INCLUDED */
