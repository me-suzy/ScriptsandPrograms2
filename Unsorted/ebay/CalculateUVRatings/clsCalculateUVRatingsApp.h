/*	$Id: clsCalculateUVRatingsApp.h,v 1.2 1998/12/16 22:08:21 poon Exp $	*/
//
//	File:	clsCalculateUVRatingsApp.h
//
//	Class:	clsCalculateUVRatingsApp
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
//	Recalculates UV ratings for all users
//
// Modifications:
//				- 12/04/98 Alex	- Created
//
#ifndef CLSCALCULATEUVRATINGSAPP_INCLUDE


#include "clsApp.h"

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif


// Class forwards go here


class clsCalculateUVRatingsApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsCalculateUVRatingsApp(bool recalculateExistingUVRatings);
		~clsCalculateUVRatingsApp();
		
		// Runner
		void Run();

	private:
		bool						mRecalculateExistingUVRatings;
		
};

#define CLSCALCULATEUVRATINGSAPP_INCLUDE 1
#endif /* CLSCALCULATEUVRATINGSAPP_INCLUDE */
