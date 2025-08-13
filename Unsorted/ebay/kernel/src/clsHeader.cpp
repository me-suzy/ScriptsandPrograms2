/*	$Id: clsHeader.cpp,v 1.1.26.1 1999/08/01 03:02:28 barry Exp $	*/
//
//	File:	clsHeader.cpp
//
//  Class:	clsHeader
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class to header header 
//
// Modifications:
//				- 05/20/99 wen	- Created
//
#include "eBayKernel.h"
#include "clsHeader.h"

// constructor
clsHeader::clsHeader(int SiteId, 
					 int PartnerId, 
					 int PageType, 
					 int SecondaryPageType, 
					 const char* pText,
					 PartnerAdVector *pvAds)
	: mSiteId(SiteId),
	  mPartnerId(PartnerId),
	  mPageType(PageType),
	  mSecondaryPageType(SecondaryPageType),
	  mpvPartnerAds(pvAds)
{
	mpText = NULL;
	SetText(pText);
}

// set text to the class
//
void clsHeader::SetText(const char* pText)
{
	delete [] mpText;
	mpText = NULL;

	if (pText)
	{
		mpText = new char [strlen(pText) + 1];
		strcpy(mpText, pText);
	}
}


// compare function for header
//
bool header_comp(clsHeader* pHeader1, clsHeader* pHeader2)
{
	if (pHeader1->GetSiteId() != pHeader2->GetSiteId())
		return (pHeader1->GetSiteId() < pHeader2->GetSiteId());

	if (pHeader1->GetPartnerId() != pHeader2->GetPartnerId())
		return (pHeader1->GetPartnerId() < pHeader2->GetPartnerId());

	if (pHeader1->GetPageType() != pHeader2->GetPageType())
		return (pHeader1->GetPageType() < pHeader2->GetPageType());

	return (pHeader1->GetSecondaryPageType() < pHeader2->GetSecondaryPageType());
}
