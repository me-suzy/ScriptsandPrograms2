//
//	File:	clsArchiveItemsApp.h
//
//	Class:	clsArchiveItemsApp
//
//	Author:	lena (lena@ebay.com)
//
//	Function:
//
//		Move items from ebay_items_ended to ebay_items_arc_MMYY
//
// Modifications:
//				
//
#ifndef CLSARCHIVEITEMSAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;

class clsArchiveItemsApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsArchiveItemsApp();
		~clsArchiveItemsApp();
		
		// Runner
		void Run(char *fromdate, char *todate);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		void ConvertLower(char *pText);

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSARCHIVEITEMSAPP_INCLUDED 1
#endif /* CLSARCHIVEITEMSAPP_INCLUDED */
