/*	$Id: clsComputeAllPastARApp.h,v 1.2 1999/02/21 02:21:32 josh Exp $	*/

//
//	File:	clsComputeAllPastARApp.h
//
//	Class:	clsComputeAllPastARApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSCOMPUTEALLPASTARAPP_INC

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;


class clsComputeAllPastARApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsComputeAllPastARApp(unsigned char *pRequestRec);
		~clsComputeAllPastARApp();
		
		// Runner
		void Run(int id, int start, int count);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSCOMPUTEALLPASTARAPP_INC 1
#endif /* CLSCOMPUTEALLPASTARAPP_INC */
