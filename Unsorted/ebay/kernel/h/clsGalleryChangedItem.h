/*	$Id: clsGalleryChangedItem.h,v 1.2 1999/02/21 02:46:35 josh Exp $	*/
//
// File: clsGalleryChangedItem.h
//
// Class: clsGalleryChangedItem
//

#ifndef CLSGALLERYCHANGEDITEM_H
#define CLSGALLERYCHANGEDITEM_H

#include <time.h>

enum GalleryResultCode
{
	kGalleryNotProcessed,
	kGallerySuccess,
	kGalleryBadURL,
	kGalleryBadProtocol,
	kGalleryPermanentBadImage,
	kGalleryHostNameDoesNotExist,
	kGalleryServerNotAvailable,
	kGalleryItemNotFound,
	kGalleryFailedDownload,
	kGalleryImageFormat,
	kGalleryBadImage,
	kGalleryInternalError,
	kGalleryMaxResultCode
};

// Gallery Type Enums
enum GalleryTypeEnum
{
	NoneGallery		= 0,
	Gallery			= 1,
	FeaturedGallery	= 2,
	UnGallery		= 3,
	UnFeaturedGallery	= 4
};

class clsGalleryChangedItem
{
public:
	int mID;
	int mSequenceID;
	char mURL[256];
	int mState; // FailCause
	time_t mStartTime;
	time_t mEndTime;
	int mAttempts;
	time_t mLastAttempt;
	GalleryTypeEnum mGalleryType;
};

class clsItemGalleryInfo
{
public:
	int mState;
	char mURL[255];
	int mXSize;
	int mYSize;
};



#endif // CLSGALLERYCHANGEDITEM_H
