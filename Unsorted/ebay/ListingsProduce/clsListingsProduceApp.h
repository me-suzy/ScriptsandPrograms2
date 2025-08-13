/*	$Id: clsListingsProduceApp.h,v 1.4 1999/04/28 05:35:01 josh Exp $	*/
//
//	File:	clsListingsProduce.h
//
//	Class:	clsListingsProduceApp
//
//	Author:	Chad Musick (chad@ebay.com)
//
//	Function:
//      Void all feedback left by suspended users.
//
//  Modifications:
//      10/2/97 -- created -- chad
//

#ifndef clsListingsProduce_INCLUDED

#include "clsApp.h"
#include "clsFillHeader.h"

// Class forward reference
class ofstream;

class clsListingsProduceApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsListingsProduceApp(unsigned char *pRequestRec);
		~clsListingsProduceApp();
		
		// Runner
		void Run(ofstream * pStream, MapFileTypeEnum * arrayOfMapFileType,
			int numberOfArrayElements);
		void RunTemplates(ofstream *pStream);
};

extern "C" void make_testapp(unsigned char *pRequest);

#define clsListingsProduce_INCLUDED 1
#endif /* clsListingsProduce_INCLUDED */
