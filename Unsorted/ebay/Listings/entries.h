
// File: entries.h
//
// 3/17/99		Created
//				Steve Yan (stevey@ebay.com)
//
// I noticed the definition of itemEntry struct appears in two include files
// clsItemMap.h and clsPackedStructures.h, and the definitions are different!!
// The 2 ForFutureUse fields were removed in the 2nd file. It was out of sync somehow.
// The entries.h is for this purpose, only one copy exists for the definitions of these
// entry structs. And entries.h will be included in both clsItemMap.h and clsPackedStructures.h
//


#ifndef _ENTRIES_H_
#define _ENTRIES_H_



/* CLSITEMMAP_WANT_STRUCTURES_ONLY */
// One record at the very start of the file
// Offsets here are absolute offsets from
// the beginning of the file.
struct headerEntry {
	int32_t	magicNumber;		//   4 bytes
#define	PAGEINDEX_MAGIC	0xbabeface
	int32_t	categoryOffset;		//	 4 bytes
	int32_t	numCategories;		//   4 bytes
	int32_t	itemOffset;			//   4 bytes
	int32_t textOffset;			//	 4 bytes
	int32_t listsOffset;		//	 4 bytes
//    int32_t usersOffset;        //   4 bytes
	int32_t timeGenerated;		//   4 bytes
};								//  32 bytes

// From here down:
// TextOffset is the offset from text base.
// item offsets are offsets from item base
// list offsets (current, ending, new, going, featured, hot, ...)
//     are offsets from list base
// categories are offset from category base
// Category 0 is 'top'

// One record per category, immediately follows
// the header block
//
// offsets and sizes of the various lists we have.
// (e_ is ending, g_ is going, n_ is new)
struct categoryEntry {
	int32_t	titleTextOffset;	//	 4 bytes
	int32_t currentOffset;		//	 4 bytes
	int32_t numCurrent;			//	 4 bytes
	int32_t endingOffset;		//	 4 bytes
	int32_t numEnding;			//   4 bytes
	int32_t newOffset;			//   4 bytes
	int32_t numNew;				//   4 bytes
	int32_t goingOffset;		//   4 bytes
	int32_t numGoing;			//   4 bytes
	int32_t	featuredOffset;		//   4 bytes
	int32_t	numFeatured;		//   4 bytes
	int32_t hotOffset;			//	 4 bytes
	int32_t numHot;				//   4 bytes
	int32_t	galleryNormalOffset; //   4 bytes
	int32_t	numGalleryNormal;	//   4 bytes
	int32_t	galleryFeaturedOffset;	//   4 bytes
	int32_t	numGalleryFeatured;	//   4 bytes
	int32_t e_featuredOffset;	//   4 bytes
	int32_t e_numFeatured;		//   4 bytes
	int32_t e_hotOffset;		//   4 bytes
	int32_t e_numHot;			//   4 bytes
	int32_t n_featuredOffset;	//   4 bytes
	int32_t n_numFeatured;		//	 4 bytes
	int32_t n_hotOffset;		//	 4 bytes
	int32_t n_numHot;			//   4 bytes
	int32_t g_featuredOffset;	//	 4 bytes
	int32_t g_numFeatured;		//	 4 bytes
	int32_t g_hotOffset;		//   4 bytes
	int32_t g_numHot;			//   4 bytes
//    int32_t numUsers;           //   4 bytes
//    int32_t usersOffset;        //   4 bytes
	int16_t	categoryNumber;		//   2 bytes
	int16_t parentCategory;		//	 2 bytes
	int16_t leftSibling;		//	 2 bytes
	int16_t rightSibling;		//	 2 bytes
	int16_t firstChild;			//	 2 bytes
	int16_t forFutureUse;		//	 2 bytes
	int8_t  isAdult;	 	    //   1 byte
	int8_t  isLeaf;				//   1 byte
	int8_t	categoryLevel;		//   1 byte
	int8_t	forFutureUse2;		//	 1 byte
};							

// No structure, but a text block goes here.

// One record per item, listed in no reliable order.
struct itemEntry {
//	char	rowId[20];			//  20 bytes -- the oracle rowid.
	int32_t	titleTextOffset;	//   4 bytes
	int32_t	itemNumber;			//   4 bytes
	int32_t	startTime;			//   4 bytes
	int32_t endTime;			//   4 bytes
	int32_t highBid;			//	 4 bytes
	int16_t numBids;			//   2 bytes
	int16_t categoryNumber;		//	 2 bytes
	int8_t	hasPicture;			//   1 byte
	int8_t	isBold;				//   1 byte
	int8_t	isReserved;			//   1 byte
	int8_t	isFeatured;			//	 1 byte
	int8_t	isSuperFeatured;	//	 1 byte
	int8_t  isGift;				//   1 byte
	int8_t whichGift;		//	 1 bytes
	int8_t  galleryType;		//	 1 bytes
	int8_t	isAdult;			//	 1 byte
	int8_t countryID;		//	 1 bytes
	int8_t currencyID;		//	 1 bytes
	int8_t mapFileType;		//	 1 bytes
};								//  x bytes


#endif	//_ENTRIES_H_