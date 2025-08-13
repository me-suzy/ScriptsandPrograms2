/* $Id: clsRequests.h,v 1.8.204.1.80.1 1999/08/01 02:51:13 barry Exp $ */
//
// File: clsRequests
// 
// Author: Chad Musick (chad@ebay.com)
//
// Description this file handles the requests after they
// have been parsed by the filter -- it keeps track of
// the per partner draw objects and the shared (per thread)
// stream.
// 
// This object is one per thread.
//
// It has dependencies on clsTemplatesMap and clsItemMap --
// they must be constructed before this one.
//
#ifndef CLSREQUESTS_INCLUDE
#define CLSREQUESTS_INCLUDE

#ifndef _EBAY_H
#include "ebay.h"
#endif

#ifdef _MSC_VER
#include <windows.h>
#include <Httpext.h>
#endif

class clsDraw;
class ostream;
class clsStreamBuffer;

class clsItemMap;
class clsTemplatesMap;
class clseBayCookie;

class clsRequests
{
private:
	clsDraw **mppDrawObjects;
	ostream *mpStream;
	// We use a custom Stream buffer for speed.
	clsStreamBuffer *mpStreamBuffer;
	clseBayCookie* mpCookie;

	int mNumPartners;

	// We use these as a member variable to allow
	// graceful replace.
	const clsItemMap *mpData;
	clsTemplatesMap *mpTemplates;

public:
	bool Draw(int whichPageType, 
			int category, 
			int listingType, 
			int featureType,
			int pageNum,
			int partner,
			bool gallery);

	void SetConnection(EXTENSION_CONTROL_BLOCK *pECB);

	bool CheckDataObject(const clsItemMap *pData)
	{ return (pData == mpData); }

	bool CheckTemplatesObject(clsTemplatesMap *pTemplates)
	{ return (pTemplates == mpTemplates); }

	clsRequests(const clsItemMap *pData, clsTemplatesMap *pTemplates);
	~clsRequests();
    Defaults(clsRequests);     

	bool HasAdultCookie(char* cookieBuffer, const char* browserName);

    // Name this here to avoid namespace pollution.
    // Defines what things we know about in Draw()
    enum PageDrawTypes
    {
        PageDrawNormal,
        PageDrawAdult,
        PageDrawOverView,
		PageDrawHead,
        PageDrawListItem,
		PageFindItem,
        PageFindAllItem,
		PageChooseCategory,
		PageGrabBag,
		PageUnmodifiedSince
    };
};

// Get the thread specific request object.
clsRequests *GetRequestObject();

#endif /* CLSREQUESTS_INCLUDE */
