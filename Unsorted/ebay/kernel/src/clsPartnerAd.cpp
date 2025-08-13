/*	$Id: clsPartnerAd.cpp,v 1.1.26.1 1999/08/01 03:02:31 barry Exp $	*/
//
//	File:	clsPartnerAd.cpp
//
//  Class:	clsPartnerAd
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class for footer 
//
// Modifications:
//				- 05/20/99 wen	- Created
//
#include "eBayKernel.h"
#include "clsPartnerAd.h"

// compare function for partner ad
//
bool partner_ad_comp(clsPartnerAd *pAd1, clsPartnerAd *pAd2)
{
	if (pAd1->GetSiteId() != pAd2->GetSiteId())
		return (pAd1->GetSiteId() < pAd2->GetSiteId());

	if (pAd1->GetPartnerId() != pAd2->GetPartnerId())
		return (pAd1->GetPartnerId() < pAd2->GetPartnerId());

	if (pAd1->GetPageType() != pAd2->GetPageType())
		return (pAd1->GetPageType() < pAd2->GetPageType());

	if (pAd1->GetSecondaryPageType() != pAd2->GetSecondaryPageType())
		return (pAd1->GetSecondaryPageType() < pAd2->GetSecondaryPageType());

	return (pAd1->GetContextSensitiveValue() < pAd2->GetContextSensitiveValue());
}
