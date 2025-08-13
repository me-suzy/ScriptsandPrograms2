/*	$Id: ClsAds.cpp,v 1.1.26.1 1999/08/01 03:02:16 barry Exp $	*/
//
//	File:	clsAds.cpp
//
//  Class:	clsAd
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
#include "eBayKernel.h"
#include "clsAds.h"


clsAds::clsAds()
{
	mpvAds = new AdVector;
	LoadAds();
}

// Class destructor
clsAds::~clsAds()
{
	AdVector::iterator	i;

	if (mpvAds != NULL)
	{
		for (i = mpvAds->begin(); i != mpvAds->end(); i++)
			delete *i;
		mpvAds->erase(mpvAds->begin(), mpvAds->end());

		delete mpvAds;
	}
}


// Add new cobrand partner ad.  (mila)
bool clsAds::AddAd(clsAd *pAd)
{
	if (pAd != NULL)
	{
		if (pAd->GetId() == 0)
			pAd->SetId(gApp->GetDatabase()->GetNextCobrandAdDescId());

		return gApp->GetDatabase()->AddCobrandAdDesc(pAd);
	}

	return false;
}


// Load all ads from the database into a local vector.  (mila)
void clsAds::LoadAds()
{
	AdVector::iterator i;

	if (mpvAds != NULL && !mpvAds->empty())
	{
		for (i = mpvAds->begin(); i != mpvAds->end(); ++i)
			delete *i;

		mpvAds->erase(mpvAds->begin(), mpvAds->end());
	}

	gApp->GetDatabase()->LoadAllCobrandAdDescs(mpvAds);
}


// Copies the whole vector.
void clsAds::GetAllAds(AdVector *pvAds)
{
	pvAds->insert(pvAds->end(), mpvAds->begin(), mpvAds->end());

	return;
}


clsAd* clsAds::GetAd(int id)
{
	AdVector::iterator	iAd;
	clsAd				testAd(id, NULL, NULL);

	if (mpvAds != NULL && !mpvAds->empty())
	{
		// binary search
		iAd = lower_bound(mpvAds->begin(), 
						  mpvAds->end(), 
						  &testAd, 
						  ad_comp);

		if ((*iAd)->GetId() == id)
			return *iAd;
	}

	return NULL;
}


clsAd* clsAds::GetAd(const char *pName)
{
	if (pName != NULL)
		return gApp->GetDatabase()->GetCobrandAdDesc(pName);

	return NULL;
}

