/* $Id: clsRequests.h,v 1.2 1998/11/16 19:25:41 chad Exp $ */
//
// File: clsRequests
// 
// Author: Chad Musick (chad@ebay.com)
//
// Description this file handles the requests after they
// have been parsed by the filter.
#ifndef CLSREQUESTS_INCLUDE
#define CLSREQUESTS_INCLUDE

#ifdef _MSC_VER
#include <windows.h>
#include <Httpext.h>
#endif

class ostream;
class clsStreamBuffer;
class clsTemplateData;

class clsRequests
{
private:
	ostream *mpStream;
	// We use a custom Stream buffer for speed.
	clsStreamBuffer *mpStreamBuffer;

	// We use these as a member variable to allow
	// graceful replace.
	const clsTemplateData *mpTemplates;

public:
	bool Draw(int partner, const char *pFileName);

	void SetConnection(EXTENSION_CONTROL_BLOCK *pECB);

	bool CheckTemplateObject(const clsTemplateData *pTemplates)
	{ return (pTemplates == mpTemplates); }

	clsRequests(const clsTemplateData *pTemplate);
	~clsRequests();
};

// Get the thread specific request object.
clsRequests *GetRequestObject();

#endif /* CLSREQUESTS_INCLUDE */