//
//	File:	clsArchiveDescApp.h
//
//	Class:	clsArchiveDescApp
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//		Hack to copy item description to archive table
//
// Modifications:
//				- 10/27/97 tini	- Created
//
#ifndef CLSARCHIVEDESCAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;

class clsArchiveDescApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsArchiveDescApp(unsigned char *pRequestRec);
		~clsArchiveDescApp();
		
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

#define CLSARCHIVEDESCAPP_INCLUDED 1
#endif /* CLSARCHIVEDESCAPP_INCLUDED */
