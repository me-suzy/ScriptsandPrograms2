//
//	File:	clsEndItemApp.h
//
//	Class:	clsEndItemApp
//
//	Author:lena
//
//	Function:
//
//		ending items (moving from ebay_items to ebay_items_ended)
//
// Modifications:
//
#ifndef CLSENDITEMAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;

class clsEndItemApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsEndItemApp();
		~clsEndItemApp();
		
		// Runner
		void Run(char *startTime, char *endTime);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;

};


#define CLSENDITEMAPP_INCLUDED 1
#endif /* CLSENDITEMAPP_INCLUDED */
