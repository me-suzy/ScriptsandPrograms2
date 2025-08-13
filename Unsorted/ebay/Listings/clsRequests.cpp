/* $Id: clsRequests.cpp,v 1.9.204.1.80.1 1999/08/01 02:51:13 barry Exp $ */
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
#include "clsRequests.h"
#include "clsDraw.h"
#include "clsItemMap.h"
#include "clsTemplatesMap.h"
#include "clsStreamBuffer.h"
#include "clseBayCookie.h"
#include "clsBase64.h"

//#include <iostream.h>

// The thread local storage identifier -- this is allocated by the filter.
extern DWORD g_tlsForRequests;

// Get the thread specific request object.
clsRequests *GetRequestObject()
{
	clsRequests *pReq;
	const clsItemMap *pData;
	clsTemplatesMap *pTemplates;

	pReq = (clsRequests *) TlsGetValue(g_tlsForRequests);

	pData = gData;
	pTemplates = gTemplates;

	if (!pReq)
	{
		pReq = new clsRequests(pData, pTemplates);
		TlsSetValue(g_tlsForRequests, (void *) pReq);
		return pReq;
	}
	// Find out if we're using a stale object.
	else if (pReq->CheckDataObject(pData) && pReq->CheckTemplatesObject(pTemplates))
	{
        // We're not, so return.
        return pReq;
    }

    // Clear and recurse.
    delete pReq;
    TlsSetValue(g_tlsForRequests, NULL);

    return GetRequestObject();
}

// Allocate enough pointer space, but don't
// actually allocate the clsDraw objects
// until they are used.
clsRequests::clsRequests(const clsItemMap *pData, clsTemplatesMap *pTemplates)
: mpData(pData), mpTemplates(pTemplates), mpCookie(0)
{
	int i;
    
  	// Allocate the buffer and then the stream.
	// We keep the same buffer and stream between
	// requests, we just reset the context.
	mpStreamBuffer = new clsStreamBuffer;
	mpStream = new ostream(mpStreamBuffer);

	mNumPartners = i = mpTemplates->GetNumberOfPartners();

	mppDrawObjects = new clsDraw* [i];	
    memset(mppDrawObjects, '\0', sizeof (clsDraw *) * i);
}

// Destroy any allocated.
clsRequests::~clsRequests()
{
	int i;

	i = mpTemplates->GetNumberOfPartners();

	while (i--)
	{
		// Deleting NULL is guaranteed safe.
		delete mppDrawObjects[i];
	}

    delete mpStreamBuffer;
    delete mpStream;
} //lint !e1740 We trust ourselves.

bool clsRequests::Draw(int whichPageType,
					   int category,
					   int listingType,
					   int featureType,
					   int pageNum,
					   int partner,
					   bool gallery)
{
	clsDraw *pDraw;
	bool result;
	bool findingItem = false;

	if (partner < 0 || partner >= mNumPartners)
		return false;

	pDraw = mppDrawObjects[partner];

	if (!pDraw)
		mppDrawObjects[partner] = pDraw = new clsDraw(partner, mpStream, mpData, mpTemplates);

	pDraw->SetDisplayProperties(gallery);

	switch (whichPageType)
	{
	case PageDrawNormal: // The normal case.

		if (!gallery)
		{
			if (hotEntry == featureType)
				result = pDraw->HotPage(category, listingType, featureType, pageNum, findingItem, gallery);
			else if (featuredEntry == featureType)
				result = pDraw->FeaturePage(category, listingType, featureType, pageNum, findingItem, gallery);
			else if (bigticketEntry == featureType)
				result = pDraw->BigticketPage(category, listingType, featureType, pageNum, findingItem, gallery);
			else if (normalEntry == featureType)
				result = pDraw->AllItemsPage(category, listingType, featureType, pageNum, findingItem, gallery);
			else
				result = false;
		}
		else
			result = pDraw->GalleryPage(category, listingType, pageNum, findingItem, gallery);

		break;
	case PageFindItem:
		result = pDraw->FindItem(category, listingType, pageNum);
		break;
    case PageFindAllItem:
        result = pDraw->FindAllListingsOfItem(pageNum); // Actually item.
        break;
	case PageDrawOverView: // The overview
		// If category is 1 (not 0), we're drawing for the numbers.
		// There are several different static pages
		result = pDraw->CategoryOverView((category != 0), gallery);
		break;
	case PageDrawAdult: // The adult entry link.
		result = pDraw->Adult(category, listingType, featureType, pageNum, 0);
		break;
	case PageDrawHead:
		result = pDraw->Head();
		break;
	case PageGrabBag:
		result = pDraw->GrabBag();
		break;
	case PageChooseCategory:
		result = pDraw->CategorySelection(gallery);
		break;
	case PageUnmodifiedSince:
		result = pDraw->UnmodifiedSince();
		break;
    case PageDrawListItem:
        result = pDraw->CategorySelection(gallery);
        break;
/*    case PageUserPage:
        result = pDraw->UserPage(category, pageNum);
        break; */
	default:
		return false;
	}

	if (result == true)
		mpStreamBuffer->WriteItOut();

	return result;
}

// Sets the connection information.
void clsRequests::SetConnection(EXTENSION_CONTROL_BLOCK *pECB)
{
	mpStreamBuffer->SetConnection(pECB);
}

bool clsRequests::HasAdultCookie(char* cookieBuffer, const char* browserName)
{
    const char *pValue;
    const char *pValueCheck;
    unsigned char key[16];

    if (!mpCookie)
    {
		mpCookie = new clseBayCookie;
    }

	mpCookie->SetCookiesFromClient(cookieBuffer);

    pValue = mpCookie->GetCookie(CookieAdult);
    if (!pValue)
    {
        return false;
    }

    clsBase64 theBase;
    clseBayCookie::BuildAdultCookie(key, browserName);
    pValueCheck = theBase.Encode((const char *) key, sizeof (key));

    if (!strcmp(pValue, pValueCheck))
    {
        return true;
    }
	else
	{
	    return false;
	}
}
