/*	$Id: clsMarketPlaces.h,v 1.4 1999/04/07 05:42:37 josh Exp $	*/
//
//	File:		clsMarketPlaces.h
//
// Class:	clsMarketPlaces
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Repository for all Marketplaces
//
// Modifications:
//				- 02/07/97 michael	- Created
//
#ifndef CLSMARKETPLACES_INCLUDED


extern const char * const machineName;


// Class forward
class clsMarketPlace;

class clsMarketPlaces
{

	public:
		clsMarketPlaces();
		~clsMarketPlaces();
		
		//
		// GetCurrentMarketPlace returns the clsMarketPlace
		// object for the "current" marketplace
		//
		clsMarketPlace *GetCurrentMarketPlace();

		//
		// CreateMarketPlace records the external representation
		// of a marketplace within the current marketplace. It's
		// passed a filled-in clsMarketPlaceObject.
		//
		// Only a marketplacemaster can create a marketplace.
		//
		void CreateMarketPlace(clsMarketPlace *pNewMarketPlace);

		//
		// DestroyMarketplace, well, destroys a marketplace
		//
		// Only a marketplacemaster can destroy a marketplace
		//
		void DestroyMarketPlace(MarketPlaceId id);

	private:
		clsMarketPlace	*mpCurrentMarketPlace;

};

#define CLSMARKETPLACES_INCLUDED 1
#endif CLSMARKETPLACES_INCLUDED