/*	$Id: clsFooter.cpp,v 1.1.26.1 1999/08/01 03:02:27 barry Exp $	*/
//
//	File:	clsFooter.cpp
//
//  Class:	clsFooter
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
#include "clsFooter.h"

// constructor
//
clsFooter::clsFooter(int SiteId, 
					 int PartnerId, 
					 int PageType, 
					 int SecondaryPageType, 
					 const char* pText, 
					 PartnerAdVector *pvAds)
	: mSiteId(SiteId),
	  mPartnerId(PartnerId),
	  mPageType(PageType),
	  mSecondaryPageType(SecondaryPageType),
	  mpvAds(pvAds)
{
	mpText = NULL;
	SetText(pText);
}

// set text
//
void clsFooter::SetText(const char* pText)
{
	delete [] mpText;
	mpText = NULL;

	if (pText)
	{
		mpText = new char [strlen(pText) + 1];
		strcpy(mpText, pText);
	}
}

// comparing footer
bool footer_comp(clsFooter* pFooter1, clsFooter* pFooter2)
{
	if (pFooter1->GetSiteId() != pFooter2->GetSiteId())
		return (pFooter1->GetSiteId() < pFooter2->GetSiteId());

	if (pFooter1->GetPartnerId() != pFooter2->GetPartnerId())
		return (pFooter1->GetPartnerId() < pFooter2->GetPartnerId());

	if (pFooter1->GetPageType() != pFooter2->GetPageType())
		return (pFooter1->GetPageType() < pFooter2->GetPageType());

	return (pFooter1->GetSecondaryPageType() < pFooter2->GetSecondaryPageType());
}
