/*	$Id: clsVoidSuspendedFeedback.h,v 1.2 1999/02/21 02:25:09 josh Exp $	*/
//
//	File:	clsVoidSuspendedFeedback.h
//
//	Class:	clsVoidSuspendedFeedbackApp
//
//	Author:	Chad Musick (chad@ebay.com)
//
//	Function:
//      Void all feedback left by suspended users.
//
//  Modifications:
//      10/2/97 -- created -- chad
//

#ifndef CLSVOIDSUSPENDEDFEEDBACK_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsUserListings;

class clsVoidSuspendedFeedbackApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsVoidSuspendedFeedbackApp(unsigned char *pRequestRec);
		~clsVoidSuspendedFeedbackApp();
		
		// Runner
		void Run();

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSVOIDSUSPENDEDFEEDBACK_INCLUDED 1
#endif /* CLSVOIDSUSPENDEDFEEDBACK_INCLUDED */
