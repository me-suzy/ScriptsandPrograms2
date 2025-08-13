/*	$Id: clsUserVerificationServices.h,v 1.2.368.1 1999/08/01 03:02:12 barry Exp $	*/
//
//	File:	clsUserVerificationServices.h
//
//	Class:	clsUserVerificationServices
//
//	Author:	Alex Poon
//
//	Function:
//
//			This class encapsulates user-verification services.
//
// Modifications:
//				- 11/2/98 alex		- Created
//				- 06/28/99 petra	- change country name to country ID
//							in CalculateUVRatingAndDetail
//

#ifndef CLSUSERVERIFICATIONSERVICES_INCLUDED

#include "eBayTypes.h"

#include <vector.h>

// Class forward
class clsMarketPlace;

class clsUserVerificationServices
{

	public:
		clsUserVerificationServices(clsMarketPlace *pMarketPlace);
		~clsUserVerificationServices();
		
		// All the services that clsUserVerificationServices provides

		// Given a state, zip, country, and phone number, calculate and return
		// a numerical UV rating and a UV detail bitfield.
		/// Note: for backwards compatability with old registrants, if pDayPhone2-4 are empty,
		//   then this function will attempt to parse out the areacode from pDayPhone1. also,
		//   for backwards compatibility, state can be either spelled out, or the 2-letter abbreviation
		void CalculateUVRatingAndDetail(
										int * pUVrating,
										int * pUVdetail,
										const char * pCity,
										const char * pState,
										const char * pZip,
// petra										const char * pCountry,
										const int countryId,	// petra
										const char * pDayPhone1,
										const char * pDayPhone2,
										const char * pDayPhone3,
										const char * pDayPhone4) const;

		// Given a UV Detail, will output to the given stream what the record means
		//  in English
		void TranslateUVDetailToText(int UVdetail, ostream *theStream) const;

		static const int UV_RATING_NOT_CALCULATED;
		static const int UV_RATING_FOR_COUNTRY_NOT_AVAILABLE;

	protected:

	// Given a UV Detail, return the numerical rating
	int CalculateUVRating(int UVdetail) const;

	const char* GetStateAbbreviation(const char *pState) const;


		//
		// Parent MarketPlace
		//
		clsMarketPlace	*mpMarketPlace;


};

#define CLSUSERVERIFICATIONSERVICES_INCLUDED 1
#endif CLSUSERVERIFICATIONSERVICES_INCLUDED
