/*	$Id: clsPartnerAds.h,v 1.1.26.1 1999/08/01 03:02:11 barry Exp $	*/
//
//	File:	clsPartnerAds.h
//
//  Class:	clsPartnerAds
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

#ifndef CLSPARTNERADS_INCLUDED

#include "clsPartnerAd.h"

class clsPartnerAds
{
public:
	clsPartnerAds();

	clsPartnerAds(int siteId, int partnerId);

	virtual ~clsPartnerAds();

	// Add new partner ad info.  (mila)
	void AddPartnerAd(clsPartnerAd *pAd);

	// Associate EXISTING cobrand partner ad with page so that the ad will be 
	// displayed on that page when the page is emitted.  (mila)
	void SetPartnerAdPage(int adId,
						  int siteId,
						  int partnerId,
						  PageTypeEnum primaryPageType, 
						  PageTypeEnum secondaryPageType, 
						  int contextSensitiveValue);

#if 0
	// Associate NEW cobrand partner ad with page so that the ad will be displayed 
	// on that page when the page is emitted.  (mila)
	void AddPartnerAdAndPage(clsAd *pAd,
							 int siteId,
							 int partnerId,
							 PageTypeEnum primaryPageType, 
							 PageTypeEnum secondaryPageType, 
							 int contextSensitiveValue);
#endif

	// Get all ads loaded into mpvPartnerAds.  (mila)
	void GetAllPartnerAds(PartnerAdVector *pvAds);

	const clsPartnerAd *GetPartnerAd(int adId,
									 PageTypeEnum pageType1, 
									 PageTypeEnum pageType2, 
									 int contextSensitiveValue);

	// Get cobrand partner ads by page type.  (mila)
	void GetPartnerAdsByPageType(PartnerAdVector *pvAds,
								 PageTypeEnum pageType1 = PageTypeUnknown,
								 PageTypeEnum pageType2 = PageTypeUnknown);

	// Get cobrand partner ads by page type.  (mila)
//	void GetPartnerAdsByPageType(PartnerAdVector *pvAds,
//								 PageTypeEnum pageType1 = PageTypeUnknown,
//								 PageTypeEnum pageType2 = PageTypeUnknown,
//								 int contextSensitiveValue = -1);

protected:
	// Load all ads for the given partner and given page on the given site.  (mila)
	void LoadPartnerAds();

private:
	int					mSiteId;
	int					mPartnerId;
	PartnerAdVector *	mpvPartnerAds;	// vector of cobrand ads for this site/partner
};

#define CLSPARTNERADS_INCLUDED
#endif // CLSPARTNERADS_INCLUDED
