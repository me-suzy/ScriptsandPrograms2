/* $Id: clsTemplatesMap.h,v 1.9.2.2.56.1 1999/08/01 02:51:14 barry Exp $ */
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
// If CLSTEMPLATESMAP_WANT_STRUCTURES_ONLY is defined we don't
// define any of the classes.
#ifndef CLSTEMPLATESMAP_INCLUDE
#define CLSTEMPLATESMAP_INCLUDE

#ifndef _EBAY_H
#include "ebay.h"
#endif

#ifdef _MSC_VER
#include <windows.h>
#endif

// The following structure fields are all assumed to
// be packed, with no padding inbetween.  They are also
// assumed to have been written in the byte order of
// the _target_ host, not of the generating host.



struct templatesHeaderEntry {
	int32_t magicNumber;		//	 4 bytes
#define	TEMPLATES_PAGEINDEX_MAGIC	0xadfadbad
	int32_t partnersOffset;		//	 4 bytes
	int32_t numPartners;		//	 4 bytes
	int32_t textOffset;			//	 4 bytes
	int32_t piecesOffset;		//	 4 bytes
	int32_t newURLOffset;		//	 4 bytes
	int32_t hotURLOffset;		//	 4 bytes
	int32_t picURLOffset;		//	 4 bytes
	int32_t viewItemURLOffset;	//	 4 bytes
	int32_t featuredURLOffset;	//	 4 bytes
	int32_t searchURLOffset;	//	 4 bytes
	int32_t AdsOffset;	//	 4 bytes
	int32_t giftFatherURLOffset;		//	 4 bytes
	int32_t giftRosieURLOffset;	//	 4 bytes
	int32_t giftAnniversaryURLOffset;	//	 4 bytes
	int32_t giftBabyURLOffset;	//	 4 bytes
	int32_t giftBirthdayURLOffset;	//	 4 bytes
	int32_t giftChristmasURLOffset;	//	 4 bytes
	int32_t giftEasterURLOffset;	//	 4 bytes
	int32_t giftGraduationURLOffset;	//	 4 bytes
	int32_t giftHalloweenURLOffset;	//	 4 bytes
	int32_t giftHanukahURLOffset;	//	 4 bytes
	int32_t giftJuly4thURLOffset;	//	 4 bytes
	int32_t giftMotherURLOffset;	//	 4 bytes

	int32_t giftStpatrickURLOffset;	//	 4 bytes
	int32_t giftThanksgivingURLOffset;	//	 4 bytes
	int32_t giftValentineURLOffset;	//	 4 bytes
	int32_t giftWeddingURLOffset;	//	 4 bytes

};							

// We do a different template (possibly) for
// different types of listings.
struct templatesListingTypeEntry
{
	int32_t normalTemplateOffset;	//	4 bytes
	int32_t normalTemplateSize;		//	4 bytes
	int32_t newTodayTemplateOffset;	//	4 bytes
	int32_t newTodayTemplateSize;	//	4 bytes
	int32_t endingTemplateOffset;	//	4 bytes
	int32_t endingTemplateSize;		//	4 bytes
	int32_t completedTemplateOffset;//	4 bytes
	int32_t completedTemplateSize;	//	4 bytes
	int32_t goingTemplateOffset;	//	4 bytes
	int32_t goingTemplateSize;		//	4 bytes
};									// 40 bytes

// Put text headers and footers here.
struct templatesCategoryHeaderEntry
{
	int32_t headerOffset;
	int32_t footerOffset;
};

// The order of the templatesListingTypeEntry
// in here is:
// level0 page 1
// level0 page 2+
// level1 page 1
// level1 page 2+
// level2 page 1
// level2 page 2+
// level3 page 1
// level3 page 2+
// level4 page 1
// level4 page 2+
// Thus, to find the index, the formula is:
// level * 2 + ((page > 1) ? 1 : 0)
// (and there are 10 of these structures.)
struct templatesPartnerEntry {
	templatesListingTypeEntry theTemplates[10];	// 400 bytes
	int32_t headersOffsetAll;
	int32_t headersOffsetFeatured;
	int32_t headersOffsetHot;
	int32_t headersOffsetGallery;
	int32_t headersOffsetGrabbag;
	int32_t headersOffsetCategory;
	int32_t footersOffsetAll;
	int32_t footersOffsetFeatured;
	int32_t footersOffsetHot;
	int32_t footersOffsetGallery;
	int32_t footersOffsetGrabbag;
	int32_t footersOffsetCategory;
	int32_t adsOffsetAll;							//	 4 bytes
	int32_t adsOffsetFeatured;						//	 4 bytes
	int32_t adsOffsetHot;							//	 4 bytes
	int32_t adsOffsetGallery;						//	 4 bytes
	int32_t adsSizeAll;								//	 4 bytes
	int32_t adsSizeFeatured;						//	 4 bytes
	int32_t adsSizeHot;								//	 4 bytes
	int32_t adsSizeGallery;							//	 4 bytes
	int32_t adsOffsetGrabbag;
	int32_t adsOffsetCategory;
					
};												// 408 bytes

// We'd like to use an enum here, but that's not all
// the way portable, so we'll use defines instead.
#define textPiece						0

#define navigatorPiece					1
#define featuredPiece					2
#define hotPiece						3
#define categoryPiece					4
#define itemsPiece						5
#define pageLinksPiece					6
#define timeAndSearchPiece				7
#define categoryNumberPiece				8
#define allHeaderPiece					9
#define topTitleBoxPiece				10
#define bottomTitleBoxPiece				11
#define jumpLinkPiece					12
#define listingTypesPiece				13
#define adPiece							14
#define bidSponsorPiece					15

// pieceType says what we do here.
// if it's type 'text', then textOffset
// contains that text.
struct templatesPieceEntry {
	int32_t pieceType;			//	 4 bytes
	int32_t textOffset;			//	 4 bytes
};								//	 8 bytes

// We don't want these in certain instances, such
// as when we generate the templates file.
#ifndef CLSTEMPLATESMAP_WANT_STRUCTURES_ONLY

class clsMappedFile;

class clsTemplatesMap {

private:
	char					*mpTextBase;
	templatesHeaderEntry	*mpHeader;
	templatesPartnerEntry	*mpPartners;
	templatesPieceEntry		*mpPieces;
//	templatesCategoryHeaderEntry *mpCategories;
	int32_t					*mpAds;

	clsMappedFile	*mpMap;

public:
	explicit clsTemplatesMap(LPCSTR lpFileName);
    Defaults(clsTemplatesMap);
	~clsTemplatesMap();

	templatesPieceEntry *GetTemplatePieces(int partner,
										   int level,
										   int type,
										   int page,
										   int *numPieces);

	void DoReplace(LPCSTR lpNewFile);

	// Get the number of partners.
	int GetNumberOfPartners() { return mpHeader->numPartners; }

	// Get the text of a text piece.
	const char *GetText(templatesPieceEntry *pPiece)
	{ return mpTextBase + pPiece->textOffset; }

	// Get one partner object.
	templatesPartnerEntry *GetPartner(int partner);

/*
	// All of these get various text.
	const char *GetHeader(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  templatesCategoryHeaderEntry *pEntry = mpCategories + pPartner->headersOffset;

	  if (category < 0 || category >= pPartner->headersSize)
		category = 0;
	  return mpTextBase + pEntry[category].headerOffset;
	}

	const char *GetFooter(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  templatesCategoryHeaderEntry *pEntry = mpCategories + pPartner->headersOffset;

	  if (category < 0 || category >= pPartner->headersSize)
		category = 0;
	  return mpTextBase + pEntry[category].footerOffset; 
	}
*/

	// This are the headers/footers for AOL cobrand

	// Header for all item pages
	const char *GetHeaderAll(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetAll;
	}

	// Header for featured item pages
	const char *GetHeaderFeatured(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetFeatured;
	}

	// Header for hot item pages
	const char *GetHeaderHot(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetHot;
	}

	// Header for gallery item pages
	const char *GetHeaderGallery(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetGallery;
	}

	// Header for grabbag page
	const char *GetHeaderGrabbag(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetGrabbag;
	}

	// Header for category page
	const char *GetHeaderCategory(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->headersOffsetCategory;
	}

	// Footer for all item pages
	const char *GetFooterAll(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetAll;
	}

	// Footer for featured item pages
	const char *GetFooterFeatured(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetFeatured;
	}

	// Footer for hot item pages
	const char *GetFooterHot(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetHot;
	}

	// Footer for gallery item pages
	const char *GetFooterGallery(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetGallery;
	}

	// Footer for grabbag item pages
	const char *GetFooterGrabbag(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetGrabbag;
	}

	// Footer for category item pages
	const char *GetFooterCategory(int partner)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  return mpTextBase + pPartner->footersOffsetCategory;
	}

	// This is the ad for AOL cobrand

	// For all item pages
	const char *GetAdsAllItem(int partner, int category)
	{ 
		
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetAll;

	  return mpTextBase + pAd[category + 1]; 
	}

	// For featured item pages
	const char *GetAdsFeatured(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetFeatured;

	  return mpTextBase + pAd[category + 1]; 
	}

	// For hot item pages
	const char *GetAdsHot(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetHot;

	  return mpTextBase + pAd[category + 1]; 
	}

	// For gallery item pages
	const char *GetAdsGallery(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetGallery;

	  return mpTextBase + pAd[category + 1]; 
	}

	// For grabbag page
	const char *GetAdsGrabbag(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetGrabbag;

	  return mpTextBase + pAd[category + 1];  
	}

	// For category page
	const char *GetAdsCategory(int partner, int category)
	{ 
	  templatesPartnerEntry *pPartner = (templatesPartnerEntry *) mpPartners + partner;
	  int32_t *pAd = mpAds + pPartner->adsOffsetCategory;

	  return mpTextBase + pAd[category + 1];  
	}


	const char *GetNewURL()
	{ return mpTextBase + mpHeader->newURLOffset; }

	const char *GetHotURL()
	{ return mpTextBase + mpHeader->hotURLOffset; }

	const char *GetPicURL()
	{ return mpTextBase + mpHeader->picURLOffset; }

	const char *GetViewItemURL()
	{ return mpTextBase + mpHeader->viewItemURLOffset; }

	const char *GetFeaturedURL()
	{ return mpTextBase + mpHeader->featuredURLOffset; }

	const char *GetSearchURL() 
	{ return mpTextBase + mpHeader->searchURLOffset; }

	const char *GetGiftFatherURL()
	{ return mpTextBase + mpHeader->giftFatherURLOffset; }

	const char *GetGiftRosieURL()
	{ return mpTextBase + mpHeader->giftRosieURLOffset; }

	const char *GetGiftAnniversaryURL()
	{ return mpTextBase + mpHeader->giftAnniversaryURLOffset; }

	const char *GetGiftBabyURL()
	{ return mpTextBase + mpHeader->giftBabyURLOffset; }
	
	const char *GetGiftBirthdayURL()
	{ return mpTextBase + mpHeader->giftBirthdayURLOffset; }

	const char *GetGiftChristmasURL()
	{ return mpTextBase + mpHeader->giftChristmasURLOffset; }
	
	const char *GetGiftEasterURL()
	{ return mpTextBase + mpHeader->giftEasterURLOffset; }

	const char *GetGiftGraduationURL()
	{ return mpTextBase + mpHeader->giftGraduationURLOffset; }

	const char *GetGiftHalloweenURL()
	{ return mpTextBase + mpHeader->giftHalloweenURLOffset; }

	const char *GetGiftHanukahURL()
	{ return mpTextBase + mpHeader->giftHanukahURLOffset; }

	const char *GetGiftJuly4thURL()
	{ return mpTextBase + mpHeader->giftJuly4thURLOffset; }

	const char *GetGiftMotherURL()
	{ return mpTextBase + mpHeader->giftMotherURLOffset; }

	const char *GetGiftStpatrickURL()
	{ return mpTextBase + mpHeader->giftStpatrickURLOffset; }

	const char *GetGiftThanksgivingURL()
	{ return mpTextBase + mpHeader->giftThanksgivingURLOffset; }

	const char *GetGiftValentineURL()
	{ return mpTextBase + mpHeader->giftValentineURLOffset; }

	const char *GetGiftWeddingURL()
	{ return mpTextBase + mpHeader->giftWeddingURLOffset; }

};
#endif /* CLSTEMPLATESMAP_WANT_STRUCTURES_ONLY */

#endif /* CLSTEMPLATESMAP_INCLUDE */
