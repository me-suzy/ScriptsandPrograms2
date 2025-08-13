/* $Id: CobrandStatic.cpp,v 1.2.424.1 1999/08/09 17:23:51 nsacco Exp $ */
// COBRANDSTATIC.CPP - Implementation file for your Internet Server
//    Cobrand Filter
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

#include "clsRawFileSet.h"
#include "clsRequests.h"
#include "clsTemplateData.h"

// This is the location of this dll.
static const char *sOurDllPath = "/CB/CobrandStatic.dll";

const clsTemplateData *gTemplates = NULL;
static clsTemplateData *gTemplates1 = NULL;
static clsTemplateData *gTemplates2 = NULL;

// These are the file names we use.
static const char *sNewTemplateFile = "/CB/cobrand.map";		// Where we copy new templates
static const char *sInUseTemplateFile1 = "/CB/cobrand1.map";	// The first template file
static const char *sInUseTemplateFile2 = "/CB/cobrand2.map";	// The second template file
static const char *sOldTemplateFile = "/CB/cobrand.map.old";	// Euthanize.

// This is the tlsIndex for the request objects.
DWORD g_tlsForRequests;

#ifdef _MSC_VER
#define stat _stat
#endif

static void UnloadFilterAndExtension()
{
	TlsFree(g_tlsForRequests);
	EndFileSet();
	delete gTemplates1;
	delete gTemplates2;
}

// A setup function common to both the filter and the extension.
// This should only complete once, no matter who is loaded first.
static BOOL LoadFilterAndExtension()
{
	static bool loaded = false;
	static bool had_file_one = false;
	int which_file = 1;
	struct stat theStat1, theStat2;

	// Don't load more than once.
	if (loaded)
		return TRUE;

	loaded = true;

	StartFileSet();

	g_tlsForRequests = TlsAlloc();

	// Register to be cleaned up when destroyed.
	atexit(UnloadFilterAndExtension);

	// Find out which file is newer.
	if (stat(sInUseTemplateFile1, &theStat1) == 0)
		had_file_one = true;
	else
	{
		// If the error is ENOENT, we're okay, and we want to use items2
		// Otherwise, it's an error.
		if (errno != ENOENT)
			return FALSE;
	}

	if (stat(sInUseTemplateFile2, &theStat2) != 0)
	{
		// If the error is ENOENT, we're okay, and we want to use items1.
		// Otherwise, it's an error.
		if (errno != ENOENT)
			return FALSE;

		// It's an error if we don't have either.
		if (!had_file_one)
			return FALSE;
	}
	else // Otherwise, determine which is the newer. Use items1 if they are equal.
	{
		if (had_file_one && theStat1.st_mtime >= theStat2.st_mtime)
			which_file = 1;
		else
			which_file = 2;
	}

	// Allocate our item maps and request thread storage.
	if (which_file == 1)
	{
		gTemplates1 = new clsTemplateData(sInUseTemplateFile1);
		gTemplates = gTemplates1;
	}
	else
	{
		gTemplates2 = new clsTemplateData(sInUseTemplateFile2);
		gTemplates = gTemplates2;
	}

	return TRUE;
}

// This function destroys the old template object. After that, the file can
// safely be removed.
static void DestroyOldTemplateData()
{
	if (gTemplates == gTemplates1)
	{
		delete gTemplates2;
		gTemplates2 = NULL;
	}
	else
	{
		delete gTemplates1;
		gTemplates1 = NULL;
	}

	// And remove the oldest file.
	remove(sOldTemplateFile);

	return;
}

// This function replaces the template file.
// We always look for a file named
// as sNewTemplateFile
// We then delete sOldTemplateFile, move
// either sInUseTemplateFile1
// or sInUseTemplateFile2 to sOldTemplateFile
// as appropriate and move sNewTemplateFile
// into its place.
// Then we replace the objects.
static bool ReplaceTemplate()
{
	int which_one;
	const char *pNewName;
	clsTemplateData *pTemplate;

	// Figure out what we're using now.
	if (gTemplates == gTemplates1)
	{
		which_one = 2;
		pNewName = sInUseTemplateFile2;
	}
	else
	{
		which_one = 1;
		pNewName = sInUseTemplateFile1;
	}

	// Destroy the old data, first of all.
	DestroyOldTemplateData();

	// Now rename the old file to old; if it doesn't exist, errno is ENOENT.
	if (rename(pNewName, sOldTemplateFile) == -1 && errno != ENOENT)
        return false;

	if (rename(sNewTemplateFile, pNewName) == -1)
	{
		// No errors are allowed for this one, though.
		return false;
	}

	// Make a new template map.
	pTemplate = new clsTemplateData(pNewName);

	// Replace in the storage pointers.
	if (which_one == 1)
		gTemplates1 = pTemplate;
	else
		gTemplates2 = pTemplate;

	// And replace the real one. This is what actually effects the update.
	gTemplates = pTemplate;

	// And we're done.
	return true;
}

// Gets the filter version (loads the filter).
BOOL WINAPI GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
	// Check for good pointers.
	if (IsBadReadPtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer, sizeof(HTTP_FILTER_VERSION)) ||
		IsBadWritePtr(pVer->lpszFilterDesc, SF_MAX_FILTER_DESC_LEN))
		return FALSE;

	// Set up stuff here.
	pVer->dwFilterVersion = HTTP_FILTER_REVISION;

	// Our name.
	lstrcpyn(pVer->lpszFilterDesc,
			 "Cobrand Filter",
			 SF_MAX_FILTER_DESC_LEN);

	// And our notifications.
	pVer->dwFlags = SF_NOTIFY_ORDER_HIGH |
					SF_NOTIFY_SECURE_PORT |
					SF_NOTIFY_NONSECURE_PORT |
					SF_NOTIFY_PREPROC_HEADERS;

	// Here's where we do our
	// non-re-entrant loading.

	return LoadFilterAndExtension();
}

// Gets the extension version (loads the extension)
BOOL WINAPI GetExtensionVersion(HSE_VERSION_INFO* pVer)
{
	// Check for good pointers.
	if (IsBadReadPtr(pVer, sizeof(HSE_VERSION_INFO)) ||
		IsBadWritePtr(pVer, sizeof(HSE_VERSION_INFO)) ||
		IsBadWritePtr(pVer->lpszExtensionDesc, HSE_MAX_EXT_DLL_NAME_LEN))
		return FALSE;

	pVer->dwExtensionVersion = MAKELONG(HSE_VERSION_MINOR,
										HSE_VERSION_MAJOR);
	lstrcpyn(pVer->lpszExtensionDesc,
			 "Extension to do Cobranding",
			 HSE_MAX_EXT_DLL_NAME_LEN);

	// Make the debugger _not_ pop up dialog boxes, since
	// this _kills_ the threads.
	_CrtSetReportMode(_CRT_WARN, _CRTDBG_MODE_FILE);
	_CrtSetReportMode(_CRT_ERROR, _CRTDBG_MODE_FILE);
	_CrtSetReportMode(_CRT_ASSERT, _CRTDBG_MODE_FILE);
	_CrtSetReportFile(_CRT_WARN, _CRTDBG_FILE_STDERR);
	_CrtSetReportFile(_CRT_ERROR, _CRTDBG_FILE_STDERR);
	_CrtSetReportFile(_CRT_ASSERT, _CRTDBG_FILE_STDERR);

	// And load.
	return LoadFilterAndExtension();
}

DWORD WINAPI HttpFilterProc(PHTTP_FILTER_CONTEXT pFC, DWORD notificationType, LPVOID pvNotification)
{
	// A big buffer for getting the header.
	// If it's longer than that, they don't want what we have anyway.
	char buffer[256];
	char build_buffer[256];
	char *pUrl;
	char *pFile;
	unsigned long length;
	int partner;
	bool invalidating = false;

	PHTTP_FILTER_PREPROC_HEADERS pHeaders;

	// We are _only_ interested in preprocessing the headers.
	if (notificationType != SF_NOTIFY_PREPROC_HEADERS)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	// Otherwise, cast to headers.
	pHeaders = (PHTTP_FILTER_PREPROC_HEADERS) pvNotification;

	// Make sure we have good addresses.
	if (IsBadReadPtr(pFC, sizeof (HTTP_FILTER_CONTEXT)) ||
		IsBadWritePtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)) ||
		IsBadReadPtr(pHeaders, sizeof (HTTP_FILTER_PREPROC_HEADERS)))
		return SF_STATUS_REQ_ERROR;

	// Grab the url from the header.
	length = sizeof (buffer);
	pHeaders->GetHeader(pFC, "url", buffer, &length);

	// lowercase the string.
	for (pUrl = buffer; *pUrl; ++pUrl)
		*pUrl = tolower(*pUrl);

	// And reset the pointer.
	pUrl = buffer;

	if (!strncmp(pUrl, "/invalid/", 9))
	{
		invalidating = true;
		pUrl += 8;
	}

	// Exceptional case:
	// Find out if we're requesting the very top page.
	if (!strcmp(pUrl, "/"))
	{
		partner = 0;
		pFile = "/index.html";

		// And jump. Yes, it's a goto. I'm horrible.
		// It just goes to the bottom where we print and redirect.
		goto print_jump;
	}

	// TODO - does this need to change now that 'aw' is no longer used?
	if (!strncmp(pUrl, "/aw/", 4))
	{
		pUrl += 3; // Don't increment past the slash.
		partner = 0;
	}
	else
	{
		if (!sscanf(pUrl, "/aw-part%d/%*s", &partner))// Are we a partner?
			return SF_STATUS_REQ_NEXT_NOTIFICATION; // Nope.
		pUrl = strchr(pUrl + 1, '/');
	}

	if (!strncmp(pUrl, "/listings/", 10))
	{
		// Let listings go through, in case it ever gets to us.
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	pFile = pUrl;

	if (!strcmp(pFile, "/cobrand.replace.template"))
	{
		ReplaceTemplate();
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	else if (!strcmp(pFile, "/cobrand.destroy.template"))
	{
		DestroyOldTemplateData();
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	// And now, we have all the information we need to print the page.

    // Do all of this in a try block, so that we don't crash the server
    // if a thread goes kablooey. We map it here.
    try
    {
print_jump:
		if (invalidating)
		{
			partner = -1;
		}

		// If it's not '.html' or no extension or '/', we can't process it.
		length = strlen(pFile);
		if (*(pFile + length - 1) != '/' && !strstr(pFile, ".html"))
		{
			if (strchr(pFile, '.'))
				return SF_STATUS_REQ_NEXT_NOTIFICATION; // Not for us.
			else
				strcat(pFile, "/"); // Make it a dir.
		}

		// Give us our partner name and our page name.
		sprintf(build_buffer, "%s?%d;/aw%s",
			sOurDllPath, partner, pFile);

		// Set it in the headers. This has the effect of an internal redirect.
		pHeaders->SetHeader(pFC, "url", build_buffer);

		return SF_STATUS_REQ_HANDLED_NOTIFICATION;
    }
    catch (...)
    {
        // The most likely cause of an exception here is that the stale object
        // was deleted before it was actually stale.
        // Unless we discover it's causing problems, we'll close the connection
        // here and continue with life.
        return SF_STATUS_REQ_FINISHED;
    }

	// Otherwise, we let the server handle it
	// This will almost certainly mean an error message, so we might
	// want to modify this strategy.
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

DWORD WINAPI HttpExtensionProc(LPEXTENSION_CONTROL_BLOCK lpECB)
{
	// All of this stuff has been done for us by the filter which mapped to here.
	// It's stored in the query string in the format 'n:n:n:n:n' in the order
	// declared below (which also happens to be the calling order for Draw())
	int partner;
	char fileName[256];
	unsigned long length;

	char *pQuery;
	char *pLast;

	// Check to make sure that our addresses are valid, eh?
	if (IsBadReadPtr(lpECB, sizeof (EXTENSION_CONTROL_BLOCK)) ||
		IsBadWritePtr(lpECB, sizeof (EXTENSION_CONTROL_BLOCK)))
	{
		// We should set HTTP_STATUS_SERVER_ERROR in the control block here,
		// but that would be silly to do if the control block isn't valid,
		// wouldn't it?
		return HSE_STATUS_ERROR;
	}

	if (IsBadReadPtr(lpECB->lpszQueryString, strlen(lpECB->lpszQueryString) + 1))
	{
		// A server error.
		lpECB->dwHttpStatusCode = 500;
		return SF_STATUS_REQ_ERROR;
	}

	pQuery = lpECB->lpszQueryString;

	// trim junk that some browsers put at the very end
	// Microsoft does this, so maybe we should too.
	pLast = pQuery + strlen(pQuery) - 1;
	while ((*pLast == ' ' || *pLast == '\n' ||
		   *pLast == '\r') && pLast > pQuery)
	{
		*pLast-- = '\0';
	}

	// Scan the query string -- If it's got all 5 numbers, we can proceed.
	if (sscanf(pQuery, "%d;%s",
		&partner, fileName) != 2)
	{
		// Oops. We've got to return an error. Did someone call us directly?
		lpECB->dwHttpStatusCode = 500; // Server error.
		return HSE_STATUS_ERROR;
	}

    // Do all of this in a try block, so that we don't crash the server
    // if a thread goes kablooey.
    try
    {
		clsRequests *pRequests;

		length = sizeof (fileName);

		// Get the physical file name.
		lpECB->ServerSupportFunction(lpECB->ConnID, HSE_REQ_MAP_URL_TO_PATH,
			fileName, &length, NULL);

		// length includes the null terminator...
		if (length && (fileName[length - 2] == '/' ||
			fileName[length - 2] == '\\'))
			strcat(fileName, "index.html");

		if (partner == -1)
		{
			Invalidate(fileName);
			lpECB->dwHttpStatusCode = 200;
			return HSE_STATUS_SUCCESS;
		}

	    // Get the thread local object and set the context for the stream.
	    pRequests = GetRequestObject();
		pRequests->SetConnection(lpECB);

	    // Now we try to draw.
	    if (pRequests->Draw(partner, fileName))
		{
			// Tell it we had a good status code.
			lpECB->dwHttpStatusCode = 200; // OK http code.
			// But return here to indicate we're waiting for the asynch callback.
			return HSE_STATUS_SUCCESS;
		}
    }
    catch (...)
    {
        // The most likely cause of an exception here is that the stale object
        // was deleted before it was actually stale.
        // Unless we discover it's causing problems, we'll close the connection
        // here and continue with life.
		lpECB->dwHttpStatusCode = 500; // Server error.
		return HSE_STATUS_ERROR;
    }

	// We get to here if we didn't throw an exception in Draw, but we didn't
	// succeed, either. Return an error.
	lpECB->dwHttpStatusCode = 500; // Server error.
	return HSE_STATUS_ERROR;
}

///////////////////////////////////////////////////////////////////////
// If your extension will not use MFC, you'll need this code to make
// sure the extension objects can find the resource handle for the
// module.  If you convert your extension to not be dependent on MFC,
// remove the comments arounn the following AfxGetResourceHandle()
// and DllMain() functions, as well as the g_hInstance global.

static HINSTANCE g_hInstance;

#undef STRICT
#include "afxisapi.h"

HINSTANCE AFXISAPI AfxGetResourceHandle()
{
	return g_hInstance;
}

BOOL WINAPI DllMain(HINSTANCE hInst, ULONG ulReason,
					LPVOID lpReserved)
{
	if (ulReason == DLL_PROCESS_ATTACH)
	{
		g_hInstance = hInst;
	}

	return TRUE;
}
