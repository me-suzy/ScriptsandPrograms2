/*	$Id: clsFooters.cpp,v 1.1.26.1 1999/08/01 03:02:27 barry Exp $	*/
//
//	File:	clsFooters.cpp
//
//  Class:	clsFooter
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class handle clsFooter 
//
// Modifications:
//				- 05/20/99 wen	- Created
//
#include "eBayKernel.h"
#include "clsFooter.h"
#include "clsFooters.h"

clsFooter* clsFooters::GetFooter(int SiteId, int PartnerId, int PageType, int SecondaryPageType)
{
	FooterVector::iterator	iFooter;
	// this is for qa
	PageType = 1;
	SecondaryPageType = 0;

	clsFooter				TestFooter(SiteId, PartnerId, PageType, SecondaryPageType, NULL, NULL);

	if (mpvFooters && mpvFooters->size() > 0)
	{
		// binary search
		iFooter = lower_bound(mpvFooters->begin(), mpvFooters->end(), &TestFooter, footer_comp);

		if (iFooter != mpvFooters->end() &&
			(*iFooter)->GetSiteId() == SiteId &&
			(*iFooter)->GetPartnerId() == PartnerId &&
			(*iFooter)->GetPageType() == PageType &&
			(*iFooter)->GetSecondaryPageType() == SecondaryPageType)
			return *iFooter;
	}

	return NULL;
}
