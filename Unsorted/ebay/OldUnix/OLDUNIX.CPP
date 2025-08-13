// OLDUNIX.CPP - Implementation file for your Internet Server
//    OldUnix Filter

#include <afx.h>
#include <afxwin.h>
#include <afxisapi.h>
#include "resource.h"
#include "OldUnix.h"

#define VARIABLE_SIZE	4182

///////////////////////////////////////////////////////////////////////
// The one and only COldUnixFilter object

COldUnixFilter theFilter;


///////////////////////////////////////////////////////////////////////
// COldUnixFilter implementation

COldUnixFilter::COldUnixFilter()
{
}

COldUnixFilter::~COldUnixFilter()
{
}

BOOL COldUnixFilter::GetFilterVersion(PHTTP_FILTER_VERSION pVer)
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

DWORD COldUnixFilter::OnUrlMap(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_URL_MAP pMapInfo)
{
	CHAR achReferer[VARIABLE_SIZE];
	DWORD cbReferer = VARIABLE_SIZE;
	DWORD cb;
	CHAR rgchBuff[1024];
	BOOL bChange = FALSE;
	CString strPhysicalPath, strOriginalPath, strLowerPath;
	int nPos = 0;

	// copy the original physical path string
	strOriginalPath = pMapInfo->pszPhysicalPath;
	strPhysicalPath = strOriginalPath;
	strLowerPath	= strOriginalPath;
	strLowerPath.MakeLower();

	//
	// remove the tilde '\~username'
	//

	// find the '\~'
	nPos = strLowerPath.Find("userisapi.dll");
	// If the directory name was found; replace it
	if (nPos != -1)
	{
		// Get the left half of the directory path
		CString strPath = strPhysicalPath.Left(nPos);

		strPath += "eBayISAPI.dll";

		// append the remaining directory path
		strPath += strPhysicalPath.Mid(nPos + 13);

		// make a new copy of the physical path string
		strPhysicalPath = strPath;

		bChange = TRUE;
	}
	else
	{
		nPos = strLowerPath.Find("itemisapi.dll");
		// If the directory name was found; replace it
		if (nPos != -1)
		{
			// Get the left half of the directory path
			CString strPath = strPhysicalPath.Left(nPos);

			strPath += "eBayISAPI.dll";

			// append the remaining directory path
			strPath += strPhysicalPath.Mid(nPos + 13);

			// make a new copy of the physical path string
			strPhysicalPath = strPath;

			bChange = TRUE;
		}
		else
		{
			nPos = strLowerPath.Find("bidisapi.dll");
			// If the directory name was found; replace it
			if (nPos != -1)
			{
				// Get the left half of the directory path
				CString strPath = strPhysicalPath.Left(nPos);

				strPath += "eBayISAPI.dll";

				// append the remaining directory path
				strPath += strPhysicalPath.Mid(nPos + 12);

				// make a new copy of the physical path string
				strPhysicalPath = strPath;

				bChange = TRUE;
			}
		}
	}

	if (bChange)
	{
		// copy the new URL to the buffer where the physical path is stored
		strcpy (pMapInfo->pszPhysicalPath, (LPCTSTR)strPhysicalPath);
	};

	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

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

