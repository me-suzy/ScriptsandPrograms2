/*	$Id: FilterOnURLMap.cpp,v 1.2 1999/02/21 02:22:00 josh Exp $	*/
//
//	File:	FilterOnURLMap.cpp
//
//	Class:	CFilterOnURLMapFilter
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//		Performs all eBay "onURLMap" functions:
//		- Remaps all "userisapi.dll" (all case combinations)
//		  to "eBayISAPI.dll".
//		- Redirects requests for the home page for AOL users
//		  to the AOL home page.
//		- Sets the "expiration time" for listing pages in the
//		  HTTP header so AOL proxies work correctly.
//
// Modifications:
//

#include "stdafx.h"
#include "FilterOnURLMap.h"

#include <sys/stat.h>


#define VARIABLE_SIZE		4182
#define EBAY_HOME_PHYSICAL	"d:\\ebay\\html\\debug\\index.html"
#define AOL_HOME_PHYSICAL	"d:\\aol\\index.html"

//
// ExpiringDirectory
//
//	This struct describes a directory which needs to have an
//	accurate expiration time set for it, and the "TTL" (Time
//	To Live), which should be added to the last-modified time
//	for files in the directory
//
//	** NOTE **
//	One day, put this in a file to be read at start up
//	** NOTE **
//
typedef struct
{
	char	directoryName[_MAX_PATH];
	int		directoryNameLength;
	time_t	directoryTTL;
}  ExpiringDirectory;

//
// ExpiringDirectories
//
//	A bunch of Directorys which expire. Should be in 
//	a file some day
//
static const ExpiringDirectory ExpiringDirectories[] =
{
	{	"e:\\inetpub\\listings",			19,	60*60		},
	{	"e:\\inetpub\\images",				17,	0			},
	{	"e:\\oldlistings",					14,	60*60		},
	{	"d:\\ebay\\html\\debug\\listings",	27,	60*60		},
	{	"d:\\ebay\\pics",					12,	0			},
	
};


static const int ExpiringDirectorySize = 
	sizeof(ExpiringDirectories)/sizeof(ExpiringDirectories[0]);

///////////////////////////////////////////////////////////////////////
// The one and only CWinApp object
// NOTE: You may remove this object if you alter your project to no
// longer use MFC in a DLL.

CWinApp theApp;



///////////////////////////////////////////////////////////////////////
// The one and only CFilterOnURLMapFilter object

CFilterOnURLMapFilter theFilter;


///////////////////////////////////////////////////////////////////////
// CFilterOnURLMapFilter implementation

CFilterOnURLMapFilter::CFilterOnURLMapFilter()
{
}

CFilterOnURLMapFilter::~CFilterOnURLMapFilter()
{
}

BOOL CFilterOnURLMapFilter::GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
	// Call default implementation for initialization
	CHttpFilter::GetFilterVersion(pVer);

	// Clear the flags set by base class
	pVer->dwFlags &= ~SF_NOTIFY_ORDER_MASK;

	// Set the flags we are interested in
	pVer->dwFlags |= SF_NOTIFY_ORDER_HIGH | SF_NOTIFY_URL_MAP;

	// Load description string
	TCHAR sz[SF_MAX_FILTER_DESC_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_FILTER, sz, SF_MAX_FILTER_DESC_LEN));
	_tcscpy(pVer->lpszFilterDesc, sz);
	return TRUE;
}

DWORD CFilterOnURLMapFilter::OnUrlMap(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_URL_MAP pMapInfo)
{
	// Rc
	int		rc;

	// Holds path information
	CString	strPhysicalPath, strOriginalPath, strLowerPath;

	// Used to translater nnnISAPI to eBayISAPI
	int		nPos = 0;
	int		nOffset	= 0;

	// Things used to see if we've got an AOL user
	// who needs an AOL home page.
	char			cookieBuffer[64];
	unsigned long	cookieLength = sizeof(cookieBuffer);
	char			*pStr;
	int				partnerId;

	// Things used to see if we need to set the expiration
	// time for a directory/file
	const ExpiringDirectory	*pExpiringDirectory;
	int						expiringDirectoryCount;
	bool					foundExpiringDirectory;
	struct _stat			fileStat;
	time_t					expireTime;
	time_t					pageExpiration;
	struct tm				*pExpirationTimeAsTM;
	struct tm				expirationTimeAsTM;
	char					expiresHeader[128];

	// Let's see if life is good
	if (IsBadReadPtr(pMapInfo->pszPhysicalPath, 1))
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	// Ok. let's save the path, and make it lower case
	// to make comparisons easy.
	strOriginalPath = pMapInfo->pszPhysicalPath;
	strPhysicalPath = strOriginalPath;
	strLowerPath	= strOriginalPath;
	strLowerPath.MakeLower();

	//
	// First, let's see if this is a request for
	// userisapi.dll, itemisapi.dll, etc in which 
	// case we translate it to eBayISAPI.dll
	//

	//
	// Fastpath
	//
	nPos = strLowerPath.Find("ebayisapi.dll");
	if (nPos != -1)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;


	nPos = strLowerPath.Find("userisapi.dll");
	if (nPos != -1)
	{
		nOffset	= 13;
	}
	else
	{
		nPos = strLowerPath.Find("itemisapi.dll");
		if (nPos != -1)
		{
			nOffset	= 13;
		}
		else
		{
			nPos = strLowerPath.Find("bidisapi.dll");
			if (nPos != -1)
			{
				nOffset = 12;
			}
		}
	}

	
	if (nPos != -1)
	{
		// Get the left half of the directory path
		CString strPath = strPhysicalPath.Left(nPos);

		strPath += "eBayISAPI.dll";

		// append the remaining directory path
		strPath += strPhysicalPath.Mid(nPos + nOffset);

		// make a new copy of the physical path string
		strPhysicalPath = strPath;

		// copy the new URL to the buffer where the physical path is stored
		strcpy (pMapInfo->pszPhysicalPath, (LPCTSTR)strPhysicalPath);

		// And out we go!
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}


	// 
	// If we're here, then it wasn't a "userisapi". Let's see if it's 
	// a home page request which needs to be remapped to the AOL home
	// page. We get this by looking at the requested thing to see if
	// it's the home page, and if it is, look for a cookie
	//

	if (pMapInfo->pszPhysicalPath &&
		!IsBadReadPtr(pMapInfo->pszPhysicalPath, 1) &&
		strcmp(LPCTSTR(strLowerPath), EBAY_HOME_PHYSICAL) == 0)
	{
		rc = pCtxt->GetServerVariable("HTTP_COOKIE", cookieBuffer, &cookieLength);

		// If we got an error, then we didn't get it. Leave quietly
		if (!rc)
			return SF_STATUS_REQ_NEXT_NOTIFICATION;

		// Got it. Let's see if it's "one of those"...Look for a "p="
		// in the cookie, and then make sure it's either the FIRST
		// thing in the cookie, or " p=".
		pStr = strstr(cookieBuffer, "p=");
		if (pStr && 
			((pStr == cookieBuffer) || isspace(*(pStr - 1))))
		{
			// Now,pStr points to "p=". Let's move forward,
			// and suck off the partner id. The id is usually
			// nn-xxxxx, where nn is the partner id, so atoi
			// will stop at the "-";
			pStr		= pStr + 2;
			partnerId	= atoi(pStr);
			
			// For now, a hack.. AOL "partners" are ids 12 - 28
			if (partnerId >= 12 && 
				partnerId <= 28)
			{
				// It's AOL! Let's give them the AOL home page!
				strcpy (pMapInfo->pszPhysicalPath, AOL_HOME_PHYSICAL);

				// And we're done!
				return SF_STATUS_REQ_NEXT_NOTIFICATION;
			}
		}

		// If we're here, they're asking for the home page, but
		// they don't have a cookie. Just return.
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	}
		
	return SF_STATUS_REQ_NEXT_NOTIFICATION;


	// 
	// Well! If we're here, then we need to see if this ia a listing
	// page we need to set the expiration time for it. 
	//
	// *** NOTE ***
	//	In "real" life, we should read a file for this at creation.
	//	I'm going to hack the data in, as data, for now
	// *** NOTE ***
	//
	foundExpiringDirectory	= false;
	for (pExpiringDirectory	= &ExpiringDirectories[0],
		 expiringDirectoryCount = 0;
		 expiringDirectoryCount < ExpiringDirectorySize;
		 pExpiringDirectory++,
		 expiringDirectoryCount++)
	{
		if (strncmp(pExpiringDirectory->directoryName,
					LPCTSTR(strLowerPath),
					pExpiringDirectory->directoryNameLength) == 0)
		{
			foundExpiringDirectory	= true;
			break;
		}
	}


	foundExpiringDirectory	= false;

	// If we didn't find one, bail
	if (!foundExpiringDirectory)
	{
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	//
	// If the TTL is 0, this is a permanent thingy, like an image.
	// Let's pretend it expires one day from today
	//
	if (pExpiringDirectory->directoryTTL == 0)
	{
		// Tacky
		fileStat.st_mtime	= time(0);
		expireTime			= 60*60*24;
	}
	else
	{
		//
		// Well! We found an expiring directory. Let's stat the
		// physical path to see when it was last updated.
		//
		rc	= _stat(pMapInfo->pszPhysicalPath, &fileStat);

		// If it didn't work, bail
		if (rc != 0)
			return SF_STATUS_REQ_NEXT_NOTIFICATION;

		expireTime	= pExpiringDirectory->directoryTTL;
	}

	// The page's expiration is it's last modified time
	// plus the TTL (time to live) for the directory
	pageExpiration	= fileStat.st_mtime + expireTime;

	// Make it into a tm_struct, and copy it, with hope it will make it 
	// safer. Note the use of GMTIME to get the time in GMT, insteead 
	// of localtime. 
	pExpirationTimeAsTM	= gmtime(&pageExpiration);

	if (!pExpirationTimeAsTM)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;


	memcpy(&expirationTimeAsTM, pExpirationTimeAsTM,
		   sizeof(expirationTimeAsTM));

	// Make it the evil RFC1123 format.
	rc = strftime(expiresHeader,
			 	  sizeof(expiresHeader),
				  "Expires : %a, %d %b %Y, %H:%M:%S GMT\n",
				  &expirationTimeAsTM);


	if (rc == 0)
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	// And add the header
	pCtxt->AddResponseHeaders((LPTSTR)expiresHeader, 0);

	// And, we're done!
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CFilterOnURLMapFilter, CHttpFilter)
	//{{AFX_MSG_MAP(CFilterOnURLMapFilter)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()
#endif	// 0

///////////////////////////////////////////////////////////////////////
// If your extension will not use MFC, you'll need this code to make
// sure the extension objects can find the resource handle for the
// module.  If you convert your extension to not be dependent on MFC,
// remove the comments arounn the following AfxGetResourceHandle()
// and DllMain() functions, as well as the g_hInstance global.

/****

static HINSTANCE g_hInstance;

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

****/
