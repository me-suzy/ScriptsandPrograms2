/*	$Id: clsUserSurvey.h,v 1.2 1998/06/23 04:28:31 josh Exp $	*/
//
//	File:	clsUserSurvey.h
//
// Class:	clsUserSurvey
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Represents User Surveys
//
// Modifications:
//

#ifndef CLSUSERSURVEY_INCLUDED

#include "eBayTypes.h"
#include "time.h"

//
// clsUserSurvey
//
class clsUserSurvey
{
	public:
		//
		// Constructor, Destructor
		//
		clsUserSurvey(int surveyId);
		~clsUserSurvey();

		//
		// UserHasParticipated
		//
		bool UserHasParticipated(int user_id);

		//
		// UserWishesToParticipate
		//
		void UserWishesToParticipate(int user_id, bool *pDontKnow,
									 bool *pWantsTo);

		//
		// SetUserWishesToParticipate
		//
		void SetUserWishesToParticipate(int user_id, bool wantsTo);


	private:
		MarketPlaceId		mMarketPlaceId;
		int					mSurveyId;

};
		
#define CLSUSERSURVEY_INCLUDED 1
#endif /* CLSUSERSURVEY_INCLUDED */


	

	
