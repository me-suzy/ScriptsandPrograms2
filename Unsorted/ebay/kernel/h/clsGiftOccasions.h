/*	$Id: clsGiftOccasions.h,v 1.2 1998/12/06 05:31:19 josh Exp $	*/
//
//	File:		clsGiftOccasions.h
//
// Class:	clsGiftOccasions
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a collection of gift occasions
//
// Modifications:
//				- 10/27/98 mila		- Created
//
#ifndef CLSGIFTOCCASIONS_INCLUDED

#include "eBayTypes.h"

#include <vector.h>
#include <list.h>
#include "clsGiftOccasion.h"

class clsGiftOccasions
{
	public:
		clsGiftOccasions();
		~clsGiftOccasions();

		//
		//GetActiveGiftOccasions
		//
		void GetActiveGiftOccasions(MarketPlaceId marketplace,
									GiftOccasionVector *pvOccasions);

		//
		// GetGiftOccasion retrieves a gift occasion by id
		//
		clsGiftOccasion *GetGiftOccasion(MarketPlaceId marketplace,
										 int id);

		//
		// AddGiftOccasion adds a gift occasion
		//
		void AddGiftOccasion(clsGiftOccasion *pOccasion);

		//
		// UpdateGiftOccasion
		// 
		void UpdateGiftOccasion(clsGiftOccasion *pOccasion);

		//
		// DeleteGiftOccasion deletes a gift occasion
		//
		void DeleteGiftOccasion(clsGiftOccasion *pOccasion);

		//
		// DeleteAllGiftOccasions removes ALL gift occasions
		//
		void DeleteAllGiftOccasions(MarketPlaceId marketplace);

	private:
};

#define CLSGIFTOCCASIONS_INCLUDED 1
#endif /* CLSGIFTOCCASIONS_INCLUDED */

