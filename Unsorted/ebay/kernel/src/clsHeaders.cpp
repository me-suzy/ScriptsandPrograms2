/*	$Id: clsHeaders.cpp,v 1.1.26.1 1999/08/01 03:02:28 barry Exp $	*/
//
//	File:	clsHeaders.cpp
//
//  Class:	clsHeader
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class handle clsHeader 
//
// Modifications:
//				- 05/20/99 wen	- Created
//
#include "eBayKernel.h"
#include "clsHeaders.h"

const clsHeader *clsHeaders::GetHeader(int SiteId, int PartnerId, int PageType, int SecondaryPageType)
{
	HeaderVector::iterator	iHeader;
	clsHeader				TestHeader(SiteId, PartnerId, PageType, SecondaryPageType, NULL, NULL);

	if (mpvHeaders && mpvHeaders->size() > 0)
	{
		// binary search
		iHeader = lower_bound(mpvHeaders->begin(), mpvHeaders->end(), &TestHeader, header_comp);

		if (iHeader != mpvHeaders->end() &&
			(*iHeader)->GetSiteId() == SiteId &&
			(*iHeader)->GetPartnerId() == PartnerId &&
			(*iHeader)->GetPageType() == PageType &&
			(*iHeader)->GetSecondaryPageType() == SecondaryPageType)
			return *iHeader;
	}

	return NULL;
}
