/*	$Id: clsTestApp.h,v 1.2 1999/02/21 02:24:40 josh Exp $	*/
#ifndef CLSTESTAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;

class clsTestApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsTestApp(unsigned char *pRequestRec);
		~clsTestApp();
		
		// Runner
		void Run();

	private:
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSTESTAPP_INCLUDED 1
#endif /* CLSTESTAPP_INCLUDED */
