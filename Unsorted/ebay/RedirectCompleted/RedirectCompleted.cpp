/*	$Id: RedirectCompleted.cpp,v 1.3 1999/02/21 02:24:18 josh Exp $	*/
// REDIRECTCOMPLETED.CPP - Implementation file for your Internet Server
//    RedirectCompleted Filter

#include "stdafx.h"
#include "RedirectCompleted.h"
#include "clseBayCookie.h"
#include "clsBase64.h"


///////////////////////////////////////////////////////////////////////
// The one and only CWinApp object
// NOTE: You may remove this object if you alter your project to no
// longer use MFC in a DLL.

CWinApp theApp;

#define MAXURL_SIZE				2048
#define MAX_COOKIE_LENGTH		4096
#define MAX_BROWSER_NAME_LENGTH	512

static char	Seps[] = " \t\r\n";


///////////////////////////////////////////////////////////////////////
// The one and only CRedirectCompletedFilter object

CRedirectCompletedFilter theFilter;


///////////////////////////////////////////////////////////////////////
// CRedirectCompletedFilter implementation

CRedirectCompletedFilter::CRedirectCompletedFilter()
{
	mpURLs = NULL;
	mNumberOfURLs = 0;

#ifdef _ADULT_DISABLE_
	mpIPs = NULL;
	mNumberOfIPs = 0;
#endif // _ADULT_DISABLE_
}

CRedirectCompletedFilter::~CRedirectCompletedFilter()
{
	int		nCount;

	for (nCount = 0; nCount < mNumberOfURLs; nCount++)
	{
		free(mpURLs[nCount]);
	}
	free (mpURLs);
	mpURLs = NULL;
	mNumberOfURLs = 0;

#ifdef _ADULT_DISABLE_
	for (nCount = 0; nCount < mNumberOfIPs; nCount++)
	{
		free(mpIPs[nCount]);
	}
	free (mpIPs);
	mpIPs = NULL;
	mNumberOfIPs = 0;
#endif // _ADULT_DISABLE_
}

BOOL CRedirectCompletedFilter::GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
 	// Call default implementation for initialization
	CHttpFilter::GetFilterVersion(pVer);

	// Clear the flags set by base class
	pVer->dwFlags &= ~SF_NOTIFY_ORDER_MASK;

	// Set the flags we are interested in
	pVer->dwFlags |= SF_NOTIFY_ORDER_HIGH | SF_NOTIFY_SECURE_PORT | SF_NOTIFY_NONSECURE_PORT
			 | SF_NOTIFY_PREPROC_HEADERS;

	// Load description string
	TCHAR sz[SF_MAX_FILTER_DESC_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_FILTER, sz, SF_MAX_FILTER_DESC_LEN));
	_tcscpy(pVer->lpszFilterDesc, sz);

	// open the file to load completed adult listing URLs
	return LoadCompletedAdultURLs();
}

DWORD CRedirectCompletedFilter::OnPreprocHeaders(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo)
{
	char	QueryString[MAXURL_SIZE];
	DWORD	ActualURLLength = MAXURL_SIZE;
	int		nCount;

	// try to make sure the point is valid
	if (!AfxIsValidAddress(pCtxt, sizeof(CHttpFilterContext), false))
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

	if (!AfxIsValidAddress(pHeaderInfo, sizeof(HTTP_FILTER_PREPROC_HEADERS), false))
		return SF_STATUS_REQ_NEXT_NOTIFICATION;

#ifdef _ADULT_DISABLE_
	DWORD	ActualHostLength = sizeof(mpRemoteHost);
	pCtxt->GetServerVariable("REMOTE_HOST", mpRemoteHost, &ActualHostLength);
#endif // _ADULT_DISABLE_

	// Get the query string
	if (!pHeaderInfo->GetHeader(pCtxt->m_pFC, "URL", QueryString, &ActualURLLength))
	{
		// failed
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	// to improve performance, check whether it is for completed listings.
	// because most of requests are not, let them continue
	// 
	if (strnicmp(QueryString, "/aw/listings/completed/", 23) != 0)
	{
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	// check whether the string is one of completed adult listing URLs
	for (nCount = 0; nCount < mNumberOfURLs; nCount++)
	{
		if (strnicmp(QueryString, mpURLs[nCount], strlen(mpURLs[nCount])) == 0)
		{
			// YES, match found. break
			break;
		}
	}

	if (nCount >= mNumberOfURLs)
	{
		// the query string is not one of the URLs. DONE
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	// the query string is one of URLs
	// Check whether there is a valid Adult cookie
	if (HasAdultCookie(pCtxt, pHeaderInfo))
	{
		// found valid adult cookie. done
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	}

	// no valid adult cookie found.
	// redirect it to adult login page
	pCtxt->ServerSupportFunction(SF_REQ_SEND_RESPONSE_HEADER,"302 URL Redirect",(LPDWORD)mpAdultLoginURL,NULL);

	// return the appropriate status code
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

//
//	Load the completed adult listing URL from a file
//
bool CRedirectCompletedFilter::LoadCompletedAdultURLs()
{
	FILE	*pURLFile;
	char	buffer[MAXURL_SIZE];
	int		limit = 10;
	bool	first = true;

	if ((mpURLs = (char**) calloc(limit, sizeof(char*))) == NULL)
		return FALSE;

	if ((pURLFile = fopen("c:\\InetPub\\CompletedAdult.txt", "r")) == NULL) 
	{
		return FALSE;
	}

	while (fgets(buffer, sizeof(buffer), pURLFile) != NULL) 
	{
		/*
		 * Skip blank lines 
		 */
		if (buffer[0] == '0' || buffer[0] == '\n')
		{
			continue;
		}
		
		// remove the '\r' at the end
		if ( buffer[strlen(buffer)-1] == 10)
		{
			buffer[strlen(buffer)-1] = 0;
		}

		// the first entry is the (redirected to) Adult Login URL 
		if (first)
		{
			first = FALSE;
			mpAdultLoginURL = (char*)calloc((strlen(buffer) + strlen("Location: \r\n\r\n") + 1), sizeof(char));
			sprintf(mpAdultLoginURL, "Location: %s\r\n\r\n", buffer);
			continue;
		}

		// reallocation more memory
		if (mNumberOfURLs >= limit)
		{
			limit += 10;
			if ((mpURLs = (char**)realloc(mpURLs, limit * sizeof(char*))) == NULL)
			{
				fclose(pURLFile);
				return FALSE;
			}
		}

		// set the url to the array
		if ( (mpURLs[mNumberOfURLs++] = strdup(buffer)) == NULL )
		{
			fclose(pURLFile);
			return false;
		}
	}
	fclose(pURLFile);

#ifdef	_ADULT_DISABLE_
	if ((mpIPs = (char**) calloc(limit, sizeof(char*))) == NULL)
		return FALSE;

	if ((pURLFile = fopen("c:\\InetPub\\testingips.txt", "r")) == NULL) 
	{
		return FALSE;
	}

	while (fgets(buffer, sizeof(buffer), pURLFile) != NULL) 
	{
		/*
		 * Skip blank lines 
		 */
		if (buffer[0] == '0' || buffer[0] == '\n')
		{
			continue;
		}

		// reallocation more memory
		if (mNumberOfIPs >= limit)
		{
			limit += 10;
			if ((mpIPs = (char**)realloc(mpIPs, limit * sizeof(char*))) == NULL)
			{
				fclose(pURLFile);
				return FALSE;
			}
		}

		// set the IP to the array
		if ( buffer[strlen(buffer)-1] == 10)
		{
			buffer[strlen(buffer)-1] = 0;
		}

		if ( (mpIPs[mNumberOfIPs++] = strdup(buffer)) == NULL )
		{
			fclose(pURLFile);
			return false;
		}
	}
	fclose(pURLFile);
#endif // _ADULT_DISABLE_

	return TRUE;
}

//
// check whether it has a valid adult cookie
//
bool CRedirectCompletedFilter::HasAdultCookie(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo)
{
#ifdef _ADULT_DISABLE_
	
	int		nCount;

	// check whether the string is one of IPs
	for (nCount = 0; nCount < mNumberOfIPs; nCount++)
	{
		if (strnicmp(mpRemoteHost, mpIPs[nCount], strlen(mpIPs[nCount])) == 0)
		{
			// YES, match found. break
			break;
		}
	}

	if (nCount >= mNumberOfIPs)
	{
		// the query string is not one of the IPs. DONE
		return TRUE;
	}

#endif // _ADULT_DISABLE_

	char	Cookie[MAX_COOKIE_LENGTH];
	DWORD	ActualCookieLength = MAX_COOKIE_LENGTH;

	// get cookie
	if (!pHeaderInfo->GetHeader(pCtxt->m_pFC, "COOKIE:", Cookie, &ActualCookieLength))
	{
		// no cookie found
		return FALSE;
	}

	// Got a cookie, then we to check whether it is the Adult cookie
	const char *pValue;
	const char *pValueCheck;

	if (!mpCookie)
	{
		mpCookie = new clseBayCookie;
	}

	mpCookie->SetCookiesFromClient(Cookie);

	// try to get the adult cookie
	pValue = mpCookie->GetCookie(CookieAdult);
	if (!pValue)
	{
		// no adult cookie found
		return FALSE;
	}

	// now check whther the adult cookie is a valid one
	// to check the cookie, Browser name is needed
	char	BrowserName[MAX_BROWSER_NAME_LENGTH];
	DWORD	ActualBrowserNameLength = MAX_BROWSER_NAME_LENGTH;

	if (!pHeaderInfo->GetHeader(pCtxt->m_pFC, "USER-AGENT:", BrowserName, &ActualBrowserNameLength))
	{
		// could not get the browser name
		return FALSE;
	}

	// got the browser name
	unsigned char key[16];

	clsBase64 theBase;
	clseBayCookie::BuildAdultCookie(key, BrowserName);
	pValueCheck = theBase.Encode((const char *) key, sizeof (key));

	if (strcmp(pValue, pValueCheck))
	{
		// Invalid adult cookie
		return FALSE;
	}

	// pass all tests
	return TRUE;
}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CRedirectCompletedFilter, CHttpFilter)
	//{{AFX_MSG_MAP(CRedirectCompletedFilter)
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
