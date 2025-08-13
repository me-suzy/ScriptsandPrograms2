/* $Id: clsDraw.h,v 1.12.2.4.8.1 1999/08/01 02:51:12 barry Exp $ */
//
// File: clsDraw
// 
// Author: Chad Musick (chad@ebay.com)
//
// Description: This file knows how to 'draw' three things:
// Normal listing pages
// A category overview
// An adult intermediate page to warn of possibly explicit content.
// (It also knows, of course, how to draw all the parts of which
// these three things are constructed.)
//
// It is created 1 per partner, per thread.
// Some of the information included in this class comes from static
// and non-re-entrant sources, and that is noted where it happens.
//
// It has dependencies on clsTemplatesMap and clsItemMap.
//

#ifndef CLSDRAW_INCLUDE
#define CLSDRAW_INCLUDE

#ifndef _EBAY_H
#include "ebay.h"
#endif

#ifndef _INC_TIME
#include <time.h>
#endif

class ostream;

struct itemEntry;
struct categoryEntry;
struct userEntry;

//class clsItemMap;

class clsTemplatesMap;
class clsDailyAd;
extern const clsItemMap *gData;

extern clsTemplatesMap *gTemplates;

class clsDraw
{
public:
	enum ItemStyle { 
		kText,   // Standard text item display
		kGallery // Images diplayed with price and title
	};

	enum {
		kMaxImagesURL = 255
	};

	void SetDisplayProperties(bool gallery);

private:
	// Statics
	// Base url for the images
	static const char* mpImagesURL;

	// These are set at construction.
	ostream *mpStream;
	int mPartner;

	// This is set whenever we call Page()
	time_t mTime;

	// This points to the 'current' data and templates --
	// we do this so that we can have two running at
	// one time to replace gracefully.
	const clsItemMap *mpData;
	clsTemplatesMap *mpTemplates;

    // This is the buffer we send as the http header.
    char mHTTPHeader[256];
    unsigned long mHTTPHeaderLength;

	// These are from the template object --
	// they're the same across all objects, and
	// updated when the template object is.
	const char *mpNewURL;
	const char *mpHotURL;
	const char *mpViewItemURL;
	const char *mpPicURL;
	const char *mpPreviousPicURL;
	const char *mpNextPicURL;
	const char *mpFeaturedPath;
	const char *mpSearchLink;

	// These are from the tempalte object and
	// partner specific.
	const char *mpAdAll;
	const char *mpAdFeatured;
	const char *mpAdHot;
	const char *mpAdGallery;
	const char *mpAdGrabbag;
	const char *mpAdCategory;

	const char *mpHeaderAll;
	const char *mpHeaderFeatured;
	const char *mpHeaderHot;
	const char *mpHeaderGallery;
	const char *mpHeaderGrabbag;
	const char *mpHeaderCategory;
	const char *mpFooterAll;
	const char *mpFooterFeatured;
	const char *mpFooterHot;
	const char *mpFooterGallery;
	const char *mpFooterGrabbag;
	const char *mpFooterCategory;

	// These are determined in code when the
	// object is created.
	const char *mpBaseListingsLink;
	const char *mpUpdateTimeString;
	const char *mpTimeName;
	const char *mpExpireTime;
	const char *mpLastModifiedTime;
	const char *mpFatherGiftURL;
	const char *mpRosieGiftURL;
	const char *mpAnniversaryGiftURL;
	const char *mpBabyGiftURL;

	const char *mpBirthdayGiftURL;
	const char *mpChristmasGiftURL;
	const char *mpEasterGiftURL;
	const char *mpGraduationGiftURL;
	const char *mpHalloweenGiftURL;
	const char *mpHanukahGiftURL;

	const char *mpJuly4thGiftURL;
	const char *mpMotherGiftURL;
	const char *mpStpatrickGiftURL;
	const char *mpThanksgivingGiftURL;
	const char *mpValentineGiftURL;
	const char *mpWeddingGiftURL;

	// This is set in the code, but might be
	// retrieved from the template at a later
	// date.
	const char *mpMarketName;

	// These are static -- they're the same across
	// all threads, and compiled in. To change
	// them, you have to recompile.
	const char *mpAdultText;

	const char **mppJumpStrings;
	const char **mppListingTypesLinkDescription;
	const char **mppListingTypeDescriptions;
	const char **mppListingDirectories;
	const char **mppFeatureTypes;

	// Headers and Footers
	char * headerAll;
	char * footerAll;
	char * headerFeatured;
	char * footerFeatured;
	char * headerHot;
	char * footerHot;
	char * headerGallery;
	char * footerGallery;

	// These are set when necessary, and per call.
	int mNumPages;
	int mCurrentPage;
	int mPagesLimit;
	int mCurrentCategory;
	int mCurrentLineNo;
	int mMaxLinesInOverView;
	int mCurrentColumn;
	int mMaxColumnsInOverView;
	int mMaxDepth;
	int mCurrentListingType;
	int mCurrentFeatureType;
	int mCurrentItem; // The item for which we are searching. It's -1 if we're not searching.
	bool mJumpAlreadyDraw;
	bool mDrawingForItemListing;

    bool mDrawingUsers;
	// How the items are listed on a page
	ItemStyle mItemStyle;

	categoryEntry *mpCategory;
	const char * mpBidDescription;
	// This is set at construction, and is relatively constant.
	int mNumItemsPerPage;
	int mNumGalleryItemsPerPage;
	int mNumTextItemsPerPage;
	// This is allocated at construction to be of size mNumItemsPerPage
	itemEntry **mppItems;

	// This is to draw certain number of randomly selected featured items.
	itemEntry **mppRandomFeatureItems;
	int mNumRandomFeatureItems;

	int mNumFeaturedGalleryItemsPerPage;

	// Ad stuff. Allocated at construction.
	clsDailyAd*	mpTopAds;
	int	mUpper;
	int mRange;
	int mAdId;

	// Make sure we don't ever draw more than one ad on a page.
	// Set at the start of drawing the page.

	bool mAdDrawn;

	// Set to true if we are drawing the category overview for
	// the category numbers, and false if we're drawing it for the
	// number of items.
	// Set only in CategoryOverView
	bool mDrawingOverviewForNumbers;

	// These help make up the template pieces, and are logical units.
	void DrawOneEntryItem(itemEntry *pItem, int color);	 // Text
	void DrawOnePageLink(int page, bool gallery, int featureType);
	void DrawTitleWithLinks(int categoryNumber, bool finding, bool gallery, int featureType);
	void DrawPreviousSibling(bool gallery, int featureType);
	void DrawNextSibling(bool gallery, int featureType);
	void DrawTimeLink();
	void DrawFeaturedHeading();
	void DrawHotHeading();
	void DrawItemsHeading(const char *color);
	void DrawCategorySearchBlock();
    void DrawOneUserPage(userEntry *pPage, int color);
    void DrawFoundLink(int category, int itemIndex, int listingType, int entryType, const char *pText = NULL,
		const char *pFirstText = NULL);
	void DrawCategoryForOverView(categoryEntry *pCategory, int level, bool gallery, int featureType);
	void DrawCategoryForListing(categoryEntry *pCategory, int level, bool gallery, int featureType);
	void DrawCategoryLink(int category, int type, int page, bool adult, bool finding = false, bool gallery = false, int featureType = 0);	
	void DrawOneEntryFeaturedItemGallery(itemEntry *pItem);

	void DrawNewUIFeatureBar();
	void DrawTitleAndListingTypeSection(bool gallery, int featureType);
	// These are template 'pieces'.
	// Most of these are not logical units.
	void DrawItemStatusIcons();
	void DrawItemStatusIconsMap();
	void DrawTitleBoxSection(int top, bool gallery, int featureType);
	void DrawFeaturedSection();
	void DrawFeaturedSection(int category);
	void DrawFeaturedSectionAllPages(int category);
	void DrawHotSection();
	void DrawHotSectionAllPages();
    void DrawUsersSection();
	void DrawItemsSection(bool gallery);
	void DrawTimeAndSearchSection(int featureType);
	void DrawAnnouncementAndSearchSection();
	void DrawCategorySection(bool gallery, int featueType);
	void DrawPageLinksSection(bool gallery, int featureType);
	void DrawAllHeadingSection();
	void DrawJumpSection(int featureType);
	void DrawListingTypesSection(bool gallery, int featureType);
	void DrawAd();
	void DrawBidSponsor();
	void DrawAllGalleryHeadingSection(int category);
	void DrawGalleryFeaturedSection();
	void DrawTimeStamp();
	void DrawSellItemLink(int featureType);
	void DrawUpdateTimeAndSponsor();

	void DrawSomeRandomFeaturedItems(int category);
	void DrawSimplifiedPageLink(bool gallery, int featureType);

	// Different ways items can be drawn.
	void DrawItemsSectionGallery();
	void DrawItemsSectionText();

	// Utility routines
	int MakePath(int key, char* destination);
	int GetCategoryIndex(int categoryNumber);

	// Gallery formatting utilities
	void DrawGallery1Row(int numItems, int numCols, int start, bool featured, bool bGrabbag = false);
	void DrawOneItemImage(itemEntry *pItem, bool featured);
	void WriteOneItemDescription(itemEntry *pItem, bool featured, bool bGrabbag = false);
	void WriteOneItemInfo(itemEntry *pItem, bool featured);
	void InsertSpace(int rows);
	void ExtraColumns(int numCols, bool featured);
	void DrawGalleryCategoryHeading(int category);
	void DrawGalleryPageLinksSection();

	// Overloaded functions
	void DrawGalleryTitleWithLinks(int category, int catLevel, bool bGrabbag = false);
	void DrawGalleryTitleWithLinks(int category);

public:

	// These are the things we can do.
	bool FindItem(int category, int type, int item);
    bool FindAllListingsOfItem(int item);
    bool UserPage(int category, int page);
	bool GrabBag();
	// These are the three things we can do.
	bool CategoryOverView(bool isForNumbers, bool gallery);
	bool AllItemsPage(int category, int type, int featureType, int page, bool findingItem = false, bool gallery = false);
	bool HotPage(int category, int type, int featureType, int page, bool findingItem = false, bool gallery = false);
	bool FeaturePage(int category, int type, int featureType, int page, bool findingItem = false, bool gallery = false);
	bool BigticketPage(int category, int type, int featureType, int page, bool findingItem = false, bool gallery = false);
	bool GalleryPage(int category, int type, int page, bool findingItem /* = false */, bool gallery);
	bool Adult(int category, int type, int featureType, int page, int prevcategory);
    bool CategorySelection(bool gallery);
	bool Head();
	bool UnmodifiedSince();

	clsDraw(int partner, ostream *pStream, const clsItemMap *pData, 
		clsTemplatesMap *pTemplates);
	~clsDraw();
    Defaults(clsDraw);


};

// For one-time setup.
void SetupDraw();

#endif /* CLSDRAW_INCLUDE */
