/* $Id: clsRequests.cpp,v 1.2 1998/11/16 19:25:40 chad Exp $ */
//
// File: clsRequests
// 
// Author: Chad Musick (chad@ebay.com)
//
// Description this file handles the requests after they
// have been parsed by the filter.
#define STRICT
#include <windows.h>
#include <Httpext.h>
#include <HttpFilt.h>
#include "CobrandStatic.h"

#include <time.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <stdlib.h>
#include <stdio.h>
#include <crtdbg.h>
#include <strstrea.h>

#include "clsRawFileSet.h"
#include "clsRequests.h"
#include "clsTemplateData.h"
#include "../Listings/clsStreamBuffer.h"
#undef STRICT

// The thread local storage identifier -- this is allocated by the filter.
extern DWORD g_tlsForRequests;

// Get the thread specific request object.
clsRequests *GetRequestObject()
{
	clsRequests *pReq;
	const clsTemplateData *pTemplates;

	pReq = (clsRequests *) TlsGetValue(g_tlsForRequests);

	pTemplates = gTemplates;

	if (!pReq)
	{
		pReq = new clsRequests(pTemplates);
		TlsSetValue(g_tlsForRequests, (void *) pReq);
		return pReq;
	}
	// Find out if we're using a stale object.
	else if (pReq->CheckTemplateObject(pTemplates))
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
clsRequests::clsRequests(const clsTemplateData *pTemplates)
: mpTemplates(pTemplates)
{
  	// Allocate the buffer and then the stream.
	// We keep the same buffer and stream between
	// requests, we just reset the context.
	mpStreamBuffer = new clsStreamBuffer;
	mpStream = new ostream(mpStreamBuffer);
}

// Destroy any allocated.
clsRequests::~clsRequests()
{
    delete mpStreamBuffer;
    delete mpStream;
} //lint !e1740 We trust ourselves.

bool clsRequests::Draw(int partner,
					   const char *pFileName)
{
	const char *pHeader;
	const char *pFooter;
	const char *pCGIToken;
	const char *pHTMLToken;
	clsRawFile *pFile;

	pFile = GetFile(pFileName);

	if (!pFile)
		return false;

	pHeader = mpTemplates->GetHeader(pFile->GetPageType(), partner);
	pFooter = mpTemplates->GetFooter(pFile->GetPageType(), partner);
	pCGIToken = mpTemplates->GetCGI(pFile->GetPageType(), partner);
	pHTMLToken = mpTemplates->GetHTML(pFile->GetPageType(), partner);

	if (!pHeader || !pFooter || !pCGIToken || !pHTMLToken)
		return false;

	pFile->WriteToStream(pCGIToken,
		pHTMLToken,
		pHeader,
		pFooter,
		mpStream);

	mpStreamBuffer->WriteItOut();

	return true;
}

// Sets the connection information.
void clsRequests::SetConnection(EXTENSION_CONTROL_BLOCK *pECB)
{
	mpStreamBuffer->SetConnection(pECB);
}