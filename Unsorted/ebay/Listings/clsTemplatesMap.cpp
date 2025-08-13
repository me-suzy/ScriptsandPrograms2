/* $Id: clsTemplatesMap.cpp,v 1.6.538.1 1999/08/01 02:51:14 barry Exp $ */
//
// File: clsTemplatesMap
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: This is one of the two mapped files for dynamic
// listings. This one contains information that will change
// relatively infrequently, such as links, graphics, and templates
// for the pages.
//

#include "clsTemplatesMap.h"
#include "clsItemMap.h"
#include "clsMappedFile.h"

#include <stdio.h>

// Constructor.
// Just get the addresses and make the casts.
clsTemplatesMap::clsTemplatesMap(LPCSTR lpFileName)
{
	mpMap = new clsMappedFile(lpFileName);
	mpAds = new int32_t;

	mpHeader = (templatesHeaderEntry *) mpMap->GetBaseAddress();

	mpPartners = (templatesPartnerEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->partnersOffset);
	mpTextBase = (char *) ((char *) mpMap->GetBaseAddress() + mpHeader->textOffset);
	mpPieces = (templatesPieceEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->piecesOffset);
//	mpCategories = (templatesCategoryHeaderEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->headerInfosOffset);
	mpAds = (int32_t *) ((char *) mpMap->GetBaseAddress() + mpHeader->AdsOffset);

	return;
}

clsTemplatesMap::~clsTemplatesMap()
{
	delete mpMap;
} //lint !e1740 Safe to not delete unallocated things

static void ReplaceFile(LPCSTR lpOldFile, LPCSTR lpNewFile)
{
	remove(lpOldFile);
	rename(lpNewFile, lpOldFile);
}

// Replace the map file.
// This is our constructor except that it refreshes the
// map rather than creating a new one.
void clsTemplatesMap::DoReplace(LPCSTR lpNewFile)
{
	mpMap->RefreshMap(ReplaceFile, lpNewFile);

	mpHeader = (templatesHeaderEntry *) mpMap->GetBaseAddress();

	mpPartners = (templatesPartnerEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->partnersOffset);
	mpTextBase = (char *) ((char *) mpMap->GetBaseAddress() + mpHeader->textOffset);
	mpPieces = (templatesPieceEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->piecesOffset);
//	mpCategories = (templatesCategoryHeaderEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->headerInfosOffset);
	return;
}

templatesPartnerEntry *clsTemplatesMap::GetPartner(int partner)
{
	if (partner < 0 || partner >= mpHeader->numPartners)
		return NULL;

	return mpPartners + partner;
}

// Just gets a pointer to the base of the list, and sets numPieces
// We count on the caller not to overrun.
templatesPieceEntry *clsTemplatesMap::GetTemplatePieces(int partner,
														int level,
														int type,
														int page,
														int *numPieces)
{
	int32_t size;
	int32_t offset;
	templatesListingTypeEntry *pTemplate;

	if (partner < 0 || partner >= mpHeader->numPartners)
	{
		*numPieces = 0;
		return NULL;
	}

	if (level < 0 || level > 4 || type < 0 || type >= UnknownListingType)
	{
		*numPieces = 0;
		return NULL;
	}

	// The formula for the template is: level * 2 + ((page > 1) ? 1 : 0)
	// since the templates are stored by depth, with odd numbers being pages 2+
	pTemplate = &((mpPartners + partner)->theTemplates[level * 2 + ((page > 1) ? 1 : 0)]);

	// We have seperate entries for each of the types, so figure out which one it is now.
	switch (type)
	{
	case CurrentListingType:
		size = pTemplate->normalTemplateSize;
		offset = pTemplate->normalTemplateOffset;
		break;
	case NewListingType:
		size = pTemplate->newTodayTemplateSize;
		offset = pTemplate->newTodayTemplateOffset;
		break;
	case EndingListingType:
		size = pTemplate->endingTemplateSize;
		offset = pTemplate->endingTemplateOffset;
		break;
	case CompletedListingType:
		size = pTemplate->completedTemplateSize;
		offset = pTemplate->completedTemplateOffset;
		break;
	case GoingListingType:
		size = pTemplate->goingTemplateSize;
		offset = pTemplate->goingTemplateOffset;
		break;
	default:
		size = 0;
		offset = 0;
		break;
	}

	*numPieces = size;
	if (!size || (offset == -1))
		return NULL;

	return mpPieces + offset;
}
