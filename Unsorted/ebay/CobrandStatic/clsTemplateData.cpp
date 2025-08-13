/* $Id: clsTemplateData.cpp,v 1.2 1998/11/16 19:25:42 chad Exp $ */
//
// File: clsTemplateData
//
// Author: Chad Musick (chad@ebay.com)
//
// This is the mapped file for headers, footers, cgi paths,
// and html paths.
//

#include "clsTemplateData.h"
#include "../Listings/clsMappedFile.h"

#include <stdio.h>

// Constructor.
// Just get the addresses and make the casts.
clsTemplateData::clsTemplateData(const char *lpFileName)
{
	mpMap = new clsMappedFile(lpFileName);

	mpHeader = (headerEntry *) mpMap->GetBaseAddress();
	mpTextBase = (char *) ((char *) mpMap->GetBaseAddress() + mpHeader->textOffset);
	mpPartners = (partnerEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->partnerOffset);
	mpTextHeadersOffset = (int32_t *) ((char *) mpMap->GetBaseAddress() + mpHeader->headersOffset);

	return;
}

clsTemplateData::~clsTemplateData()
{
	delete mpMap;
} //lint !e1740 Safe to not delete unallocated things

bool clsTemplateData::ValidatePageType(int pageType) const
{
	return (pageType >= 0 && pageType <= mpHeader->numPageTypes);
}

bool clsTemplateData::ValidatePartner(int partner) const
{
	return (partner >= 0 && partner <= mpHeader->numPartners && 
		mpPartners[partner].partnerNumber == partner);
}

const char *clsTemplateData::GetHeader(int pageType, int partner) const
{
	if (!ValidatePageType(pageType))
		return GetHeader(0, partner);

	if (!ValidatePartner(partner))
		return GetHeader(pageType, 0);

	return mpTextBase + *(mpTextHeadersOffset + mpPartners[partner].headersOffset + pageType);
}

const char *clsTemplateData::GetFooter(int pageType, int partner) const
{
	if (!ValidatePageType(pageType))
		return GetFooter(0, partner);

	if (!ValidatePartner(partner))
		return GetFooter(pageType, 0);

	return mpTextBase + *(mpTextHeadersOffset + mpPartners[partner].footersOffset + pageType);
}

const char *clsTemplateData::GetCGI(int pageType, int partner) const
{
	if (!ValidatePartner(partner))
		return GetCGI(pageType, 0);

	return mpTextBase + mpPartners[partner].cgiOffset;
}

const char *clsTemplateData::GetHTML(int pageType, int partner) const
{
	if (!ValidatePartner(partner))
		return GetHTML(pageType, 0);

	return mpTextBase + mpPartners[partner].htmlOffset;
}
