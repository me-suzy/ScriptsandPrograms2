/*	$Id: clsAds.h,v 1.1.26.1 1999/08/01 03:02:04 barry Exp $	*/
//
//	File:	clsAds.h
//
//  Class:	clsAds
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				a class to handle clsAd 
//
// Modifications:
//				- 05/31/99 mila		- Created
//

#ifndef CLSADS_INCLUDED

#include "clsAd.h"

class clsAds
{
public:
	clsAds();

	virtual ~clsAds();

	// Add new ad entry to the database.  (mila)
	bool AddAd(clsAd *pAd);

	// Update existing ad info in the database.  (mila)
//	void UpdateAd(clsAd *pAd);

	// Return all ads loaded into mpvAds.  (mila)
	void GetAllAds(AdVector *pvAds);

	// Return the ad with the given id.  (mila)
	clsAd* GetAd(int id);

	// Return the ad with the given name.  (mila)
	clsAd* GetAd(const char *pName);

protected:
	// Load all ads from the database into a local vector.  (mila)
	void LoadAds();

private:
	AdVector *	mpvAds;		// vector of all cobrand ads
};

#define CLSADS_INCLUDED
#endif // CLSADS_INCLUDED
