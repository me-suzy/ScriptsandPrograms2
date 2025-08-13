/*	$Id: clsPartnerAds.cpp,v 1.1.26.1 1999/08/01 03:02:32 barry Exp $	*/
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
#include "clsPartnerAds.h"


clsPartnerAds::clsPartnerAds()
	: mSiteId(-1),
	  mPartnerId(-1)
{
	mpvPartnerAds = new PartnerAdVector;
	LoadPartnerAds();
}

clsPartnerAds::clsPartnerAds(int siteId, int partnerId)
	: mSiteId(siteId),
	  mPartnerId(partnerId)
{
	mpvPartnerAds = new PartnerAdVector;
	LoadPartnerAds();
}

// Class destructor
clsPartnerAds::~clsPartnerAds()
{
	PartnerAdVector::iterator	i;

	if (mpvPartnerAds != NULL)
	{
		for (i = mpvPartnerAds->begin(); i != mpvPartnerAds->end(); i++)
			delete *i;
		mpvPartnerAds->erase(mpvPartnerAds->begin(), mpvPartnerAds->end());

		delete mpvPartnerAds;
	}
}


// Add new cobrand partner ad.  (mila)
void clsPartnerAds::AddPartnerAd(clsPartnerAd *pAd)
{
	if (pAd != NULL)
		gApp->GetDatabase()->AddCobrandAd(pAd);
}


// Associate cobrand partner ad with page so that the ad will be displayed on
// that page when the page is emitted.  (mila)
void clsPartnerAds::SetPartnerAdPage(int adId,
									 int siteId,
									 int partnerId,
									 PageTypeEnum primaryPageType, 
									 PageTypeEnum secondaryPageType, 
									 int contextSensitiveValue)
{
	// Return if the primary or secondary page type is PageTypeUnknown
	if (primaryPageType == PageTypeUnknown || secondaryPageType == PageTypeUnknown)
		return;

	clsPartnerAd	partnerAd(adId, 
							  NULL,
							  siteId,
							  partnerId, 
							  primaryPageType,
							  secondaryPageType,
							  contextSensitiveValue,
							  NULL);

	gApp->GetDatabase()->AddCobrandAd(&partnerAd);
}


#if 0
// Associate cobrand partner ad with page so that the ad will be displayed on
// that page when the page is emitted.  (mila)
void clsPartnerAds::AddPartnerAdToSitePage(clsAd *pAd,
										   int siteId,
										   int partnerId,
										   PageTypeEnum primaryPageType, 
										   PageTypeEnum secondaryPageType, 
										   int contextSensitiveValue)
{
	int			adId;

	// Return if the primary or secondary page type is PageTypeUnknown
	if (primaryPageType == PageTypeUnknown || secondaryPageType == PageTypeUnknown)
		return;

	adId = gApp->GetDatabase()->GetNextCobrandAdDescId();
	if (adId == 0)
		return;

	// First add the new ad to the database
	pAd->SetId(adId);
	gApp->GetDatabase()->AddCobrandAdDesc(pAd);

	gApp->GetDatabase()->AddCobrandAd(adId,
									  siteId,
									  partnerId, 
									  primaryPageType,
									  secondaryPageType,
									  contextSensitiveValue);
}
#endif

// Load all ads for for this partner on this site.  (mila)
void clsPartnerAds::LoadPartnerAds()
{
	PartnerAdVector::iterator i;

	if (mpvPartnerAds != NULL && !mpvPartnerAds->empty())
	{
		for (i = mpvPartnerAds->begin(); i != mpvPartnerAds->end(); ++i)
			delete *i;

		mpvPartnerAds->erase(mpvPartnerAds->begin(), mpvPartnerAds->end());
	}

	if (mSiteId < 0 && mPartnerId < 0)
	{
		gApp->GetDatabase()->LoadAllCobrandAds(mpvPartnerAds);
	}
	else
	{
		gApp->GetDatabase()->GetCobrandAdsBySiteAndPartner(mSiteId, 
														   mPartnerId,
														   mpvPartnerAds);
	}
}


// Copies the whole vector.
void clsPartnerAds::GetAllPartnerAds(PartnerAdVector *pvPartnerAds)
{
	pvPartnerAds->insert(pvPartnerAds->end(), mpvPartnerAds->begin(), 
		mpvPartnerAds->end());

	return;
}


const clsPartnerAd* clsPartnerAds::GetPartnerAd(int adId,
												PageTypeEnum pageType, 
												PageTypeEnum secondaryPageType,
												int contextSensitiveValue)
{
	PartnerAdVector::iterator	iAd;
	clsPartnerAd				testAd(adId,
									   NULL,
									   mSiteId, 
									   mPartnerId, 
									   pageType, 
									   secondaryPageType, 
									   contextSensitiveValue, 
									   NULL);

	if (mpvPartnerAds != NULL && !mpvPartnerAds->empty())
	{
		// binary search
		iAd = lower_bound(mpvPartnerAds->begin(), 
						  mpvPartnerAds->end(), 
						  &testAd, 
						  partner_ad_comp);

		if ((*iAd)->GetId() == adId &&
			(*iAd)->GetSiteId() == mSiteId &&
			(*iAd)->GetPartnerId() == mPartnerId &&
			(*iAd)->GetPageType() == pageType &&
			(*iAd)->GetSecondaryPageType() == secondaryPageType &&
			(*iAd)->GetContextSensitiveValue() == contextSensitiveValue)
			return *iAd;
	}

	return NULL;
}


// Get all ads for this page type.  (mila)
void clsPartnerAds::GetPartnerAdsByPageType(PartnerAdVector *pvAds,
											PageTypeEnum pageType,
											PageTypeEnum secondaryPageType)
{
	PartnerAdVector::iterator	i;

	if (pvAds == NULL || mpvPartnerAds == NULL || mpvPartnerAds->empty())
		return;

	for (i = mpvPartnerAds->begin(); i != mpvPartnerAds->end(); i++)
	{
		if (*i == NULL)
			continue;

		if ((*i)->GetSiteId() == mSiteId &&
			(*i)->GetPartnerId() == mPartnerId &&
			(*i)->GetPageType() == pageType &&
			(*i)->GetSecondaryPageType() == secondaryPageType)
		{
			pvAds->push_back(*i);
		}
	}
}

#if 0
// Update all ads for this page type.  (mila)
void clsPartnerAds::UpdatePartnerAdPage(int adId,
										PageTypeEnum newPageType,
										PageTypeEnum newSecondaryPageType,
										int newContextSensitiveValue)
{
	clsPartnerAd *pPartnerAd;
	
	pPartnerAd = GetPartnerAd(adId, pageType, secondaryPageType, contextSensitiveValue);

	
}

#endif