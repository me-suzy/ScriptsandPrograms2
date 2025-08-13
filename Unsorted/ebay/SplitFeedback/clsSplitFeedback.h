/*	$Id: clsSplitFeedback.h,v 1.3 1999/02/21 02:24:31 josh Exp $	*/
//
//	File:	clsSplitFeedback.h
//
//	Class:	clsSplitFeedback
//
//	Author:	inna
//
//	Function:
//
//		Loads New ebay_feedback_detail## tables from ebay_feedback_detail table
//
// Modifications:
//				- 07/31/98 inna	- created
//
#ifndef CLSSplitFeedback_INCLUDED

#include "clsApp.h"


// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;


class clsSplitFeedback : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsSplitFeedback(unsigned char *pRequestRec);
		~clsSplitFeedback();
		
		// Runner
		void Run(int FromUser,int ToUser);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSSplitFeedback_INCLUDED 1
#endif /* CLSSplitFeedback__INCLUDED */
