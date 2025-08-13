/* $Id: listings.cpp,v 1.12.54.10.4.3 1999/08/09 18:45:01 nsacco Exp $ */
// LISTINGS.CPP - Implementation file for your Internet Server
//    Listings Filter
#define STRICT
#include <windows.h>
#include <Httpext.h>
#include <HttpFilt.h>
#include "Listings.h"
#include "clsRequests.h"
#include "clsItemMap.h"
#include "clsTemplatesMap.h"
#include "clsStreamBuffer.h"
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <stdlib.h>
#include <stdio.h>
#include <crtdbg.h>

#include "categoryNumber.h"

FILE *popen(const char *, const char *);
int pclose(FILE *);

// Define to limit the gallery to only the folk art class
// all other category requests go to our regular listings
// pool

#undef GALLERY_LIMITED

// Global, non-re-entrant objects (file mappings)

const clsItemMap *gData = NULL;
static clsItemMap *gData1 = NULL;
static clsItemMap *gData2 = NULL;
clsTemplatesMap *gTemplates = NULL;
static clsTemplatesMap *gTemplates1 = NULL;
static clsTemplatesMap *gTemplates2 = NULL;

// This is to indicate that we handled this request.
// We never actually assign anything to this again.
static const char *sWeHandled = "Hey, we handled this!";

// This is the location of this dll.
static const char *sOurDllPath = "/LB/Listings.dll";

// These are the file names we use.
static const char *sNewListingFile = "/ListingsBinaries/items.map"; // Where we copy new listings.
static const char *sInUseListingFile1 = "/ListingsBinaries/items1.map"; // The first listing file.
static const char *sInUseListingFile2 = "/ListingsBinaries/items2.map"; // The second listing file.
static const char *sOldListingFile = "/ListingsBinaries/items.map.old"; // Where outdated listings go to die.

static const char *sNewTemplateFile = "/ListingsBinaries/template.map";		// Where we copy new templates
static const char *sInUseTemplateFile1 = "/ListingsBinaries/template1.map";	// The first template file
static const char *sInUseTemplateFile2 = "/ListingsBinaries/template2.map";	// The second template file
static const char *sOldTemplateFile = "/ListingsBinaries/template.map.old";	// Euthanize.


// This is the tlsIndex for the request objects.
DWORD g_tlsForRequests;

// This is located in clsDraw.
void SetupDraw();

// Function prototypes.
static void DestroyOldData();
static void DestroyOldTemplateData();

// We might unload the extension, but we shouldn't unload the filter.
static void CleanUp()
{
	TlsFree(g_tlsForRequests);

	delete gData1;
	delete gData2;
	delete gTemplates1;
	delete gTemplates2;
}

#ifdef _MSC_VER
#define stat _stat
#endif

void LogEvent(char* pMsg)
{
	FILE							*pErrorLogFile;
	char							fileName[30];
	sprintf(fileName, "ErrorLog.txt");	
	pErrorLogFile = fopen(fileName, "a+");
	fprintf(pErrorLogFile, "%s\n", pMsg);
	fclose(pErrorLogFile); 

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

	// Find out which file is newer.
	if (stat(sInUseListingFile1, &theStat1) == 0)
		had_file_one = true;
	else
	{
		// If the error is ENOENT, we're okay, and we want to use items2
		// Otherwise, it's an error.
		if (errno != ENOENT)
			return FALSE;
	}

	if (stat(sInUseListingFile2, &theStat2) != 0)
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
		gData1 = new clsItemMap(sInUseListingFile1);
		gData = gData1;
	}
	else
	{
		gData2 = new clsItemMap(sInUseListingFile2);
		gData = gData2;
	}

	// Reset this.
	had_file_one = false;
	which_file = 1;

	// This is identical to above, put for templates rather than items.
	// Find out which file is newer.
	// We always want to have template1.map, even if it's not current.
	if (stat(sInUseTemplateFile1, &theStat1) == 0)
		had_file_one = true;
	else
	{
		// If the error is ENOENT, we're okay, and we want to use template2.
		// Otherwise, it's an error.
		if (errno != ENOENT)
			return FALSE;
	}

	if (stat(sInUseTemplateFile2, &theStat2) != 0)
	{
		// If the error is ENOENT, we're okay, and we want to use template1.
		// Otherwise, it's an error.
		if (errno != ENOENT)
			return FALSE;
		
		// It's an error to have neither, though.
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
		gTemplates1 = new clsTemplatesMap(sInUseTemplateFile1);
		gTemplates = gTemplates1;
	}
	else
	{
		gTemplates2 = new clsTemplatesMap(sInUseTemplateFile2);
		gTemplates = gTemplates2;
	}

	g_tlsForRequests = TlsAlloc();
	loaded = true;

	// Set up the draw.
	SetupDraw();

	// Register to be cleaned up when destroyed.
	atexit(CleanUp);

	return TRUE;
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
			 "Listings Filter",
			 SF_MAX_FILTER_DESC_LEN);

	// And our notifications.
	pVer->dwFlags = SF_NOTIFY_ORDER_MEDIUM |
					SF_NOTIFY_SECURE_PORT |
					SF_NOTIFY_NONSECURE_PORT |
					SF_NOTIFY_PREPROC_HEADERS;

	// Here's where we do our
	// non-re-entrant loading
	int ret;
//	try 
//	{
		 ret = LoadFilterAndExtension();
/*	}
	catch (...)
	{
		int i;
		i = 0;
	}*/
	return ret;
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
			 "Extension to do Listings",
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

// This function replaces the data file.
// We always look for a file named
// as sNewListingFile
// We then delete sOldListingFile, move
// either sInUseListingFile1
// or sInUseListingFile2 to sOldListingFile
// as appropriate and move sNewListingFile
// into its place.
// Then we replace the objects.
static bool Replace()
{
#if 0
	return true; // FIXME MLH 8/5/98 debugging code. Should not be in production.
#endif
	int which_one;
	const char *pNewName;
	clsItemMap *pData;
	FILE *pTestFile;

	// Figure out what we're using now.
	if (gData == gData1)
	{
		which_one = 2;
		pNewName = sInUseListingFile2;
	}
	else
	{
		which_one = 1;
		pNewName = sInUseListingFile1;
	}

	// First, open the file to be sure it exists.
	pTestFile = fopen(sNewListingFile, "rb");
	if (pTestFile)
	{
		struct headerEntry theHeader;
		struct stat theStat;
		bool bad = false;
		int32_t want = 0;

		if (fread(&theHeader, sizeof (headerEntry), 1, pTestFile))
			want = theHeader.magicNumber;
		else
			bad = true;

		if (want && stat(sNewListingFile, &theStat) == 0)
		{
			if (want != theStat.st_size)
				bad = true;
		}
		fclose(pTestFile);

		if (bad)
			return false;
	}
	else
		return false; // If we couldn't open the file, we don't proceed.

	// Destroy the old data, first of all.
	DestroyOldData();

	// Now rename the old file to old; if it doesn't exist, errno is ENOENT.
	if (rename(pNewName, sOldListingFile) == -1 && errno != ENOENT)
        return false;

	if (rename(sNewListingFile, pNewName) == -1)
	{
		// No errors are allowed for this one, though.
		return false;
	}

	// Make a new item map.
	pData = new clsItemMap(pNewName);

	// Replace in the storage pointers.
	if (which_one == 1)
		gData1 = pData;
	else
		gData2 = pData;

	// And replace the real one. This is what actually effects the update.
	gData = pData;

	// And we're done.
	return true;
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
	clsTemplatesMap *pTemplate;
	FILE *pTestFile;

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

	pTestFile = fopen(sNewTemplateFile, "rb");
	if (pTestFile)
		fclose(pTestFile);
	else
		return false; // If we couldn't open the file, we don't proceed.

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
	pTemplate = new clsTemplatesMap(pNewName);

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

// This function destroys the old data object. After that, the file can
// safely be removed.
static void DestroyOldData()
{
	if (gData == gData1)
	{
		delete gData2;
		gData2 = NULL;
	}
	else
	{
		delete gData1;
		gData1 = NULL;
	}

	// And remove the oldest file.
	remove(sOldListingFile);

	return;
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

// This list is used for both the parsing and
// drawing of the different listing types.
static const char *sListingDirectories[] =
{
	"list",
	"newtoday",
	"endtoday",
	"completed",
	"going",
	"list"
};


// This list is used for both the parsing and
// drawing of the different features
static const char *sListingFeatureTypes[] =
{
	"all",
	"hot",
	"featured",
	"bigticket"
};


// This is external (global), and used in clsDraw as well
// as here.
const char **gListingDirectories = (const char **) sListingDirectories;

const char **gListingFeatureTypes = (const char **) sListingFeatureTypes;

// How many we have.
static int sListingDirectories_size = sizeof (sListingDirectories) / sizeof (const char *);
static int sCompleted = 3; // We don't handle completeds in this filter at this time.

static int sListingFeatureTypes_size = sizeof (sListingFeatureTypes) / sizeof (const char *);

static const char *sMonths[] =
{
	"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
};




DWORD WINAPI HttpFilterProc(PHTTP_FILTER_CONTEXT pFC, DWORD notificationType, LPVOID pvNotification)
{
	// A big buffer for getting the header.
	// If it's longer than that, they don't want what we have anyway.
	char buffer[256];
	char *pUrl;
	unsigned long length;
	int partner;
	int listingType, page, category;
	int featureType;
	int whichPageFunction; // which page we are drawing. An enum defined in clsRequests, but made
						   // an int since we print it.
	int galleryPage = 0;
	int i;
    bool drawingUsers;
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

	// clsRequests::PageDrawOverview is replaced by clsRequests::PageDrawNormal,
	// and the featureType and category level determine if it is really an overview 
	// page or a normal draw page.

	// We need to use clsRequests::PageDrawNormal again, as for the new UI phase1 --- Stevey

	// Exceptional case:
	// Find out if we're requesting the very top page.
	if (!strcmp(pUrl, "/"))
	{
	//	whichPageFunction = clsRequests::PageDrawNormal;
		whichPageFunction = clsRequests::PageDrawOverView;
		category = 0;
		listingType = 0;
		page = 0;
		partner = 0;
		featureType = 0;

		// And jump. Yes, it's a goto. I'm horrible.
		// It just goes to the bottom where we print and redirect.
		goto print_jump;
	}

    if (!strncmp(pUrl, "/users", 6))
    {
        drawingUsers = true;
        pUrl += 6;
    }

	// Find out if we're 'aw'
	if (!strncmp(pUrl, "/aw/", 4))
		pUrl += 3; // Don't increment past the slash.

	// Find out if we're a partner.
	if (!strncmp(pUrl, "/part", 5))
	{
		// If we have /part, but not a valid number after it. (The * means no assign.)
		if (!sscanf(pUrl, "/part%d/%*s", &partner))
			return SF_STATUS_REQ_NEXT_NOTIFICATION;
		// The + 1 gets us past the leading slash.
		// And now, we look like everyone else, in fact.
		pUrl = strchr(pUrl + 1, '/');
	}
	else
		partner = 0;

	// Is this something about which we care?
	if (strncmp(pUrl, "/listings/", 10))
	{
		
		if (strncmp(pUrl, "/glistings/", 11))
			return SF_STATUS_REQ_NEXT_NOTIFICATION; // No, it wasn't.
		else
		{
			// Get us past "/glistings/"
			pUrl += 11;
			galleryPage = 1;
		}
	}
	else
	{
		
		// If we're here, it's a request for us!
		// We still might return an error, though.
		

		// Get us past "/listings/"
		pUrl += 10;
	}

	if (!*pUrl)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	// Figure out if it's a 'real' listing directory.
//	listingType = -1;
	listingType = 0; // if not specified, will be 0, "list"
	for (i = 0; i < sListingDirectories_size; ++i)
	{
		if (!strncmp(pUrl, gListingDirectories[i], strlen(gListingDirectories[i])))
		{
			// Don't do any completeds.
			if (i == sCompleted)
				return SF_STATUS_REQ_NEXT_NOTIFICATION;

			// Set our listing type and get over ourselves.
			listingType = i;
			pUrl += strlen(gListingDirectories[i]);
			break;
		}
	}

	if (*pUrl == '/')
		++pUrl;

	// Figure out if it's a feature, hot, big ticket or for all features. The default is all feature
	featureType = 0;
	for (i = 0; i < sListingFeatureTypes_size; ++i)
	{
		if (!strncmp(pUrl, gListingFeatureTypes[i], strlen(gListingFeatureTypes[i])))
		{
			// Set our festure type and get over ourselves.
			featureType = i;
			pUrl += strlen(gListingFeatureTypes[i]);
			break;
		}
	}

	// Let's return if the user does not specify a feature type

	// The normal case is that we'll have a slash here, but it's
	// not definite, so make sure before skipping it.
	if (*pUrl == '/')
		++pUrl;

	// We're now looking at /category, or nothing (which is 'category 0'),
	// or 'index.html' (which is also 'category 0') or possibly 'adult.html'
	// or even 'overview.html' or categories.html
	if (!*pUrl || !strcmp(pUrl, "index.html"))
	{
	// for the new UI, this will be another static HTML page. However, the feature tyep
	// determines draw the items page or the static HTML page
	/*	page = 1;
		category = 0;
        whichPageFunction = clsRequests::PageDrawNormal;
		*/

		whichPageFunction = clsRequests::PageDrawNormal;
		category = 0;
		page = 1;  // change to 1 from 0: in case we are drawing the specialty pages, 0 would not make sense then
	}
	// Otherwise, we _could_ be in a category.
	else if (sscanf(pUrl, "category%d%*s", &category) > 0)
	{
		// We need a listings type.
		if (listingType == -1)
			return SF_STATUS_REQ_NEXT_NOTIFICATION;

		if (category == 0) // ok here --- Stevey
		{
			// We don't allow category0 referenced that way.
		//	return SF_STATUS_REQ_NEXT_NOTIFICATION;
		}


        whichPageFunction = clsRequests::PageDrawNormal;

		pUrl = strchr(pUrl + 1, '/');
		// If we didn't have a slash then this is the index.
		if (!pUrl)
			page = 1;
		else // Otherwise, figure out what page we do want.
		{
			if (!strcmp(pUrl, "/index.html") || !strcmp(pUrl, "/"))
				page = 1;
			else if (sscanf(pUrl, "/page%d.html", &page) == 1)
				; // Do nothing, since sscanf did what we wanted.
			else if (!strcmp(pUrl, "/adult.html"))
			{
				page = 1;
                whichPageFunction = clsRequests::PageDrawAdult; // Our assumption _was_ wrong.
			}
			// This lets us search for an item in a category and find out what
			// page the item is on, as well as where in the page.
			else if (sscanf(pUrl, "/find%d.html", &page) == 1)
			{
				// Page is what we want here (not really the page at all, but the item
				// we want to find!
				// However, we need to set an appropriate page function.
				whichPageFunction = clsRequests::PageFindItem;
			}
			else
			{
				// Something else? Oh well.
				return SF_STATUS_REQ_NEXT_NOTIFICATION;
			}
		}
	}
	else if (!strcmp(pUrl, "overview.html"))
	{
		/*
		page = 1;
		category = 0;
		featureType = 0;
        whichPageFunction = clsRequests::PageDrawNormal;
		*/

		page = 1;
		category = 0;
        whichPageFunction = clsRequests::PageDrawOverView;
	}
	else if (!strcmp(pUrl, "adult.html"))
	{
		page = 1;
		category = 0;
        whichPageFunction = clsRequests::PageDrawAdult;
	}
	else if (!strcmp(pUrl, "categories.html"))
	{
//		page = 1;
//		category = 1; // This flags us as drawing for numbers.
//		whichPageFunction = clsRequests::PageDrawOverView;

		page = 1;
		category = 0;
		featureType = 0;
        whichPageFunction = clsRequests::PageDrawNormal;
	}
	else if (!strcmp(pUrl, "choose-category.html"))
	{
		page = 1;
		category = 1;
		whichPageFunction = clsRequests::PageChooseCategory;
	}
	else if (!strcmp(pUrl, "grabbag.html"))
	{
		page = 1;
		category = 0;
		featureType = 0;
		whichPageFunction = clsRequests::PageGrabBag;
	}
	else if (sscanf(pUrl, "page%d.html", &page) > 0)
	{
	//	if (listingType != GoingListingType)
	//		return SF_STATUS_REQ_NEXT_NOTIFICATION;
		category = 0;
        whichPageFunction = clsRequests::PageDrawNormal;
	}
	else if (sscanf(pUrl, "find%d.html", &page) > 0)
	{
		// Page is what we want here (not really the page at all, but the item
		// we want to find!
		// However, we need to set an appropriate page function.
		category = 0;
		whichPageFunction = clsRequests::PageFindItem;
	}
    else if (sscanf(pUrl, "?item=%d", &page) > 0)
    {
        // page is actually the desired item number.
        listingType = 0;
        category = 0;
        whichPageFunction = clsRequests::PageFindAllItem;
    }
	else if (!strcmp(pUrl, "replace.nobody.use.this"))
	{
		if (!Replace())
			LogEvent("***Listings have problem replacing the items file");
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	else if (!strcmp(pUrl, "destroy.nobody.use.this"))
	{
		DestroyOldData();
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	else if (!strcmp(pUrl, "replace.template.nobody.use.this"))
	{
		if (!ReplaceTemplate())
			LogEvent("***Listings have problem replacing the template file");
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	else if (!strcmp(pUrl, "destroy.template.nobody.use.this"))
	{
		DestroyOldTemplateData();
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}
	else
	{
		// Something else.
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

// Yes, this is a goto. You may shoot me if you wish, but it makes the code
// infinitely simpler and more readable for the used case. (Url of "/")
print_jump:

	// Now we check for 'special requests' in the header, such
	// as using 'HEAD' instead of 'GET' or using 'If-Modified-Since' in a
	// GET request.
	// Grab the method from the header.
	length = sizeof (buffer);
	pHeaders->GetHeader(pFC, "method", buffer, &length);

	// Can we get head?
	// Ideally, we'd like to get head as soon as possible, but
	// if the request wasn't intended for us in the first place
	// it would be rude to give head for someone else.
	if (!strcmpi(buffer, "head"))
		whichPageFunction = clsRequests::PageDrawHead;
	else
	{
		// Is this an 'If-Modified-Since' request?
		length = sizeof(buffer);
		if (pHeaders->GetHeader(pFC, "If-Modified-Since:", buffer, &length) &&
			length && *buffer)
		{
			// We declare these here because we only use them here,
			// and they would just make the other part more confusing.
			int day, monthInt, year, hour, minute, second;
			char month[256];
			time_t theTime;
			struct tm *pTime;
			struct tm theTimeStruct;

			// We do have it.
			if (sscanf(buffer, "%*s, %d %s %d %d:%d:%d GMT", &day, month, &year, &hour, &minute,
				&second) == 6 && (strlen(month) == 3))
			{
				// And we could parse it.
				theTime = gData->GetTimeGenerated();
				// Get the gmtime representation for the last modified
				// time.
				pTime = gmtime(&theTime);

				// Figure out the month.
				for (monthInt = 0; monthInt < 12 && strcmp(month, sMonths[monthInt]); ++monthInt)
					;

				// Now fill out the time struct.
				theTimeStruct.tm_year = year - 1900;
				theTimeStruct.tm_mon = monthInt;
				theTimeStruct.tm_mday = day;
				theTimeStruct.tm_min = minute;
				theTimeStruct.tm_hour = hour;
				theTimeStruct.tm_sec = second;

				// Now compare -- we should probably be more careful about
				// making sure we've _really_ got a good time from the request,
				// but we're supposed to ignore this request if the time is bad,
				// and this _will_ have that effect.
				if (mktime(pTime) > mktime(&theTimeStruct))
					whichPageFunction = clsRequests::PageUnmodifiedSince;
			}
		}
	}
	
	// And now, we have all the information we need to print the page.

    // Do all of this in a try block, so that we don't crash the server
    // if a thread goes kablooey. We map it here.
    try
    {
//        if (drawingUsers)
//            whichPageFunction = clsRequests::PageUserPage;

		// Do it in the same order that we would have called Draw.
		sprintf(buffer, "%s?%d,%d%,%d, %d,%d,%d,%d",
			sOurDllPath, (int) whichPageFunction,
			category, listingType, featureType, page, partner, galleryPage);

		// Set it in the headers. This has the effect of an internal redirect.
		pHeaders->SetHeader(pFC, "url", buffer);

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

/*
// We have to do this to allow logging to progress. All we do here
// is tell the server to go away if we handled this in OnURLMap
DWORD CListingsFilter::OnSendRawData( CHttpFilterContext* pfc, PHTTP_FILTER_RAW_DATA pRawData)
{
	// We handled this.
	// This is a supremely ugly hack:
	// If we already handled this request, then what we're sending here
	// is a (false) header which indicates file not found (since they
	// don't actually exist.)
	// So... we tell the server there's no content in this chunk.
	// Muhaha.
	// For our next trick, we'll fudge the data in the OnLog function.
	if (pfc->m_pFC->pFilterContext == (void *) sWeHandled)
	{
		pRawData->cbInData = 0;
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	// We didn't handle this.
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

// We have to do this to fix the return status.
DWORD CListingsFilter::OnLog( CHttpFilterContext* pfc, PHTTP_FILTER_LOG pLog )
{
	// If we handled this then this lists an incorrect status, so
	// we'll just sneakily fix it.
	if (pfc->m_pFC->pFilterContext == (void *) sWeHandled)
		pLog->dwHttpStatus = 200;

	// And return.
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}
*/

bool NeedsAdultSecurityCheck(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock, 
							 int category,
							 clsRequests* requests)
{
//#define _NO_ADULT_CHECK
#ifdef _NO_ADULT_CHECK
	char remote_addr[16];
	unsigned long addr_size;
	memset(remote_addr, 0x00, sizeof(remote_addr));
	addr_size	= sizeof(remote_addr);
	httpExtensionControlBlock->GetServerVariable(
		httpExtensionControlBlock->ConnID,
		"REMOTE_ADDR",
		(unsigned char *) remote_addr,
		&addr_size);
    char *pHost = remote_addr;

    if (strcmp(pHost, "209.1.128.147") &&
        strcmp(pHost, "209.1.128.189") &&
        strcmp(pHost, "209.1.128.182") &&
        strcmp(pHost, "209.1.128.149") &&
        strcmp(pHost, "209.1.128.229"))
        return false;
#endif // _NO_ADULT_CHECK
#undef _NO_ADULT_CHECK


	if (!gData)
		return false;

	categoryEntry* catEntry = gData->GetCategory(category);
	if (!catEntry)
		return false;

	if (!catEntry->isAdult)
		return false;

	const int kMaxCookieLength = 4096; // Hope they don't get bigger than this
	char cookieBuffer[kMaxCookieLength+1];
	unsigned long cookieLength = kMaxCookieLength;

	BOOL getServerVariableResult = httpExtensionControlBlock->GetServerVariable(
		httpExtensionControlBlock->ConnID,
		"HTTP_COOKIE",
		(void*) cookieBuffer,
		&cookieLength);

	// Must have a cookie or it is off to adult check for you
	DWORD lastError;
	if (!getServerVariableResult)
	{
		lastError = GetLastError();
		return true;
	}

	// Terminate the cookie
	cookieBuffer[cookieLength] = 0;

	char browserString[512];
	memset(browserString, '\0', sizeof (browserString));
	unsigned long size = sizeof(browserString) - 1;
	httpExtensionControlBlock->GetServerVariable(
		httpExtensionControlBlock->ConnID,
		"HTTP_USER_AGENT", 
		browserString, 
		&size);

	
	return !requests->HasAdultCookie(cookieBuffer, browserString);
}

//const char* kAdultCheckURL = "http://www.ebay.com";
const char* kAdultCheckURL = "http://cgi-new.ebay.com/aw-cgi/eBayISAPI.dll?AdultLoginShow&t=1";

void RedirectToAdultSecurityCheck(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(kAdultCheckURL);

	// FIXME MLH 8/27/98 should not use static adult page url
	//   should use market place url getter, however this will bring
	//   in more mfc crap that I don't want to deal with right now.

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) kAdultCheckURL, 
		&urlLength, 
		NULL);
}
#undef GALLERY_LIMITED

bool BlockFromGallery(int category)
{
	switch (category)
	{
	case 319: // Erotica, Adult Only
	case 320: //   General
	case 379: //   Books, Magazines
	case 322: //   CD
	case 323: //   Photographic
	case 321: //   Video
		return true;

	default:
		return false;
	}

}

// kakiyama 07/09/99 - commented out
// resourced using mpMarketPlace->GetHTMLPath()

//const char* kBlockFromGalleryURL = "http://pages.ebay.com/nogallery.html";

void RedirectToBlockFromGalleryPage(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
//	DWORD urlLength = strlen(kBlockFromGalleryURL);
// kakiyama 07/16/99
	char kBlockFromGalleryURL[512];
	strcpy(kBlockFromGalleryURL, mpMarketPlace->GetHTMLPath());
	strcat(kBlockFromGalleryURL, "nogallery.html");

	DWORD urlLength = strlen(kBlockFromGalleryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) kBlockFromGalleryURL, 
		&urlLength, 
		NULL);
}


// TODO - dynamic!
// This is the big ticket URL
const char * BigticketURL = "http://pages-new.ebay.com/buy/bigticket/";

// TODO - dymanic
// These are static pages for the top category and the 12 level one categories.
const char* TopCategoryURL = "http://pages.ebay.com/buy/gallery.html";
const char* AntiqCategoryURL = "http://pages.ebay.com/buy/gallery-antiques.html";
const char* BooksCategoryURL = "http://pages.ebay.com/buy/gallery-books.html";
const char* CoinsCategoryURL = "http://pages.ebay.com/buy/gallery-coins.html";
const char* ComputersCategoryURL = "http://pages.ebay.com/buy/gallery-computers.html";
const char* CollectibleCategoryURL = "http://pages.ebay.com/buy/gallery-collectibles.html";
const char* DollsCategoryURL = "http://pages.ebay.com/buy/gallery-dolls.html";
const char* JewelsCategoryURL = "http://pages.ebay.com/buy/gallery-jewelry.html";
const char* PhotoCategoryURL = "http://pages.ebay.com/buy/gallery-photo.html";
const char* PotteryCategoryURL = "http://pages.ebay.com/buy/gallery-pottery.html";
const char* SportsCategoryURL = "http://pages.ebay.com/buy/gallery-sports.html";
const char* ToysCategoryURL = "http://pages.ebay.com/buy/gallery-toys.html";
const char* OthersCategoryURL = "http://pages.ebay.com/buy/gallery-misc.html";


// Redirect to big ticket page
void RedirectToBigticket(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(BigticketURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) BigticketURL, 
		&urlLength, 
		NULL);
}


void RedirectToTopCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(TopCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) TopCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToAntiqCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(AntiqCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) AntiqCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToBooksCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(BooksCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) BooksCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToCoinsCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(CoinsCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) CoinsCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToCollectibleCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(CollectibleCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) CollectibleCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToComputerCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(ComputersCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) ComputersCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToDollsCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(DollsCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) DollsCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToJewelCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(JewelsCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) JewelsCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToPotteryCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(PotteryCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) PotteryCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToPhotoCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(PhotoCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) PhotoCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToSportsCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(SportsCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) SportsCategoryURL, 
		&urlLength, 
		NULL);
}

void RedirectToToysCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(ToysCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) ToysCategoryURL, 
		&urlLength, 
		NULL);
}


void RedirectToOthersCategory(EXTENSION_CONTROL_BLOCK* httpExtensionControlBlock)
{
	DWORD urlLength = strlen(OthersCategoryURL);

	int redirectResult = httpExtensionControlBlock->ServerSupportFunction(
		httpExtensionControlBlock->ConnID,
		HSE_REQ_SEND_URL_REDIRECT_RESP, 
		(void*) OthersCategoryURL, 
		&urlLength, 
		NULL);
}



DWORD WINAPI HttpExtensionProc(LPEXTENSION_CONTROL_BLOCK lpECB)
{
	// All of this stuff has been done for us by the filter which mapped to here.
	// It's stored in the query string in the format 'n:n:n:n:n' in the order
	// declared below (which also happens to be the calling order for Draw())
	int whichPageFunction;
	int category;
	int listingType;
	int featureType;
	int page;
	int partner;
	int galleryPage;

	clsRequests *pRequests;
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

	// Scan the query string -- If it's got all 6 numbers, we can proceed.
	if (sscanf(pQuery, "%d,%d,%d,%d,%d,%d,%d", &whichPageFunction,
		&category, &listingType, &featureType, &page, &partner, &galleryPage) != 7)
	{
		// Oops. We've got to return an error. Did someone call us directly?
		lpECB->dwHttpStatusCode = 500; // Server error.
		return HSE_STATUS_ERROR;
	}

    // Do all of this in a try block, so that we don't crash the server
    // if a thread goes kablooey.
    try
    {

		if (galleryPage)
		{
			if (BlockFromGallery(category))
			{
				RedirectToBlockFromGalleryPage(lpECB);
				return HSE_STATUS_SUCCESS;
			}

			// redirect to static pages for category 0 and the 12 top categories
			switch (category)
			{
				case TopCategory: // The top level categories.
					RedirectToTopCategory(lpECB);
					break;
				case CatNumAntiq: // The antiq.
					RedirectToAntiqCategory(lpECB);
					break;
				case catNumBooks: // The books.
					RedirectToBooksCategory(lpECB);
					break;
				case catNumCoins: // The coins.
					RedirectToCoinsCategory(lpECB);
					break;
				case catNumCollectible: // The collectible.
					RedirectToCollectibleCategory(lpECB);
					break;
				case catNumComputer: // The computers.
					RedirectToComputerCategory(lpECB);
					break;
				case catNumDolls: // The dolls.
					RedirectToDollsCategory(lpECB);
					break;
				case catNumJewelry: // The jewels.
					RedirectToJewelCategory(lpECB);
					break;
				case catNumPottery: // The pottery.
					RedirectToPotteryCategory(lpECB);
					break;
				case catNumPhoto: // The photos.
					RedirectToPhotoCategory(lpECB);
					break;
				case catNumSports: // The sports.
					RedirectToSportsCategory(lpECB);
					break;
				case catNumToys: // The toys.
					RedirectToToysCategory(lpECB);
					break;
				case catNumMiscellaneous: // others.
					RedirectToOthersCategory(lpECB);
					break;

				//default: // This should not happen, but display top category anyway
				//	RedirectToTopCategory(lpECB);
				//	break;
			}

				//return HSE_STATUS_SUCCESS;



		}

		// For big ticket
		if (bigticketEntry == (entryTypeEnum)featureType)
		{
			RedirectToBigticket(lpECB);
			return HSE_STATUS_SUCCESS;
		}
// Need to uncomment this later
// and determine to use static page if comming from nav-bar or
// use listing generated page if comming from the bread crumb
// -- dnguyen
/***
		// Determine which pages are static HTML: from the feature type and category level
		bool bTopCategory = false;

		if (0 == category)
			bTopCategory = true;
		else
		{
			categoryEntry* catEntry = gData->GetCategory(category);
			if(!catEntry)
			{
				lpECB->dwHttpStatusCode = 500; // Server error.
				return HSE_STATUS_ERROR;
			}

			if (1==catEntry->categoryLevel)
				bTopCategory = true;
		}

		if ((clsRequests::PageDrawNormal == whichPageFunction) && bTopCategory && ((normalEntry == featureType) || (1 == galleryPage)))
		{

			switch (category)
			{
				case TopCategory: // The top level categories.
					RedirectToTopCategory(lpECB);
					break;
				case CatNumAntiq: // The antiq.
					RedirectToAntiqCategory(lpECB);
					break;
				case catNumBooks: // The books.
					RedirectToBooksCategory(lpECB);
					break;
				case catNumCoins: // The coins.
					RedirectToCoinsCategory(lpECB);
					break;
				case catNumCollectible: // The collectible.
					RedirectToCollectibleCategory(lpECB);
					break;
				case catNumComputer: // The computers.
					RedirectToComputerCategory(lpECB);
					break;
				case catNumDolls: // The dolls.
					RedirectToDollsCategory(lpECB);
					break;
				case catNumJewelry: // The jewels.
					RedirectToJewelCategory(lpECB);
					break;
				case catNumPottery: // The pottery.
					RedirectToPotteryCategory(lpECB);
					break;
				case catNumPhoto: // The photos.
					RedirectToPhotoCategory(lpECB);
					break;
				case catNumSports: // The sports.
					RedirectToSportsCategory(lpECB);
					break;
				case catNumToys: // The toys.
					RedirectToToysCategory(lpECB);
					break;
				case catNumMiscellaneous: // others.
					RedirectToOthersCategory(lpECB);
					break;

				default: // This should not happen, but display top category anyway
					RedirectToTopCategory(lpECB);
					break;
			}

				return HSE_STATUS_SUCCESS;
		}
***/
	    // Get the thread local object and set the context for the stream.
	    pRequests = GetRequestObject();
		pRequests->SetConnection(lpECB);

		bool needsAdultCheck = NeedsAdultSecurityCheck(lpECB, category, pRequests);
		if (needsAdultCheck)
		{
			RedirectToAdultSecurityCheck(lpECB);
			return HSE_STATUS_SUCCESS;
		}
		else
		{
			// Passes adult check so we can proceed
			// Now we try to draw.
			if (pRequests->Draw(whichPageFunction,
								category,
								listingType,
								featureType,
								page,
								partner,
								galleryPage == 1))
			{
				// Tell it we had a good status code.
				lpECB->dwHttpStatusCode = 200; // OK http code.
				// But return here to indicate we're waiting for the asynch callback.
				return HSE_STATUS_SUCCESS;
			}
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
