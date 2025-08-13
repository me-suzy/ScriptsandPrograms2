/*	$Id: CoBrand.cpp,v 1.2 1999/02/21 02:21:49 josh Exp $	*/
// COBRAND.CPP - Implementation file for your Internet Server
//    cobrand Filter

#include "stdafx.h"
#include "CobrandTable.h"
#include "clsHtmlExpand.h"

#include "cobrand.h"
#include "stdio.h"
extern char PARTPREFIX[];
extern char szReloadAll[];


///////////////////////////////////////////////////////////////////////
// The one and only CCobrandFilter object

CCobrandFilter theFilter;


///////////////////////////////////////////////////////////////////////
// CCobrandFilter implementation

CCobrandFilter::CCobrandFilter()
{
	mpCobrandTable = NULL;
}

CCobrandFilter::~CCobrandFilter()
{
	if( mpCobrandTable )
		delete mpCobrandTable;
}

BOOL CCobrandFilter::GetFilterVersion(PHTTP_FILTER_VERSION pVer)
{
	// Call default implementation for initialization
	BOOL dwRet;
	CHttpFilter::GetFilterVersion(pVer);

	// Clear the flags set by base class
	pVer->dwFlags &= ~SF_NOTIFY_ORDER_MASK;

	// Set the flags we are interested in
	pVer->dwFlags |= SF_NOTIFY_ORDER_LOW | SF_NOTIFY_SECURE_PORT | SF_NOTIFY_NONSECURE_PORT
			 | SF_NOTIFY_PREPROC_HEADERS | SF_NOTIFY_SEND_RAW_DATA | SF_NOTIFY_URL_MAP;

	// Load description string
	TCHAR sz[SF_MAX_FILTER_DESC_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_FILTER, sz, SF_MAX_FILTER_DESC_LEN));
	_tcscpy(pVer->lpszFilterDesc, sz);	
	mpCobrandTable	=	new CobrandTable();
	if( mpCobrandTable )
	{
		dwRet = mpCobrandTable->ReadAllHeadersAndFooters();
		return dwRet;
	}
	else
	{
#ifdef _DEBUG
	TRACE("Can not create mpCobrandTable");
#endif	
	}	
	return TRUE;
}

DWORD CCobrandFilter::OnPreprocHeaders(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo)
{
	// TODO: React to this notification accordingly and
	// return the appropriate status code	
	return SF_STATUS_REQ_NEXT_NOTIFICATION;	
}

DWORD CCobrandFilter::OnUrlMap(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_URL_MAP pMapInfo)
{
	// TODO: React to this notification accordingly and
	// return the appropriate status code
	CString strPath;
	DWORD dwRet;
	clsHtmlExpand	*pclsHtmlExpand;

	if (IsBadReadPtr(pMapInfo->pszPhysicalPath, 1))
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	
	strPath = pMapInfo->pszURL;
	strPath.MakeLower();
	if( strPath.Find(PARTPREFIX) != -1 )
	{	
		pCtxt->m_pFC->pFilterContext =  (HTTP_FILTER_CONTEXT *)pCtxt->AllocMem( sizeof(clsHtmlExpand), (DWORD) NULL );					
		if( pCtxt->m_pFC == NULL)			
		{
			return SF_STATUS_REQ_ERROR;
		}
		else
		{
			pclsHtmlExpand = (clsHtmlExpand *) pCtxt->m_pFC->pFilterContext;
			if( mpCobrandTable && pclsHtmlExpand)				
			{
				pclsHtmlExpand->Initialize();
				pclsHtmlExpand->SetCobrandTable(mpCobrandTable);
				dwRet = pclsHtmlExpand->SetPartnerIDString(pCtxt, pMapInfo);
				if( dwRet )
				{
					pCtxt->m_pFC->pFilterContext = (clsHtmlExpand *)pclsHtmlExpand;
				}
				else
					pCtxt->m_pFC->pFilterContext = NULL;
			}
			else
				return SF_STATUS_REQ_ERROR;
		}			
	}	
	else if(strstr(pMapInfo->pszURL, szReloadAll))
	{
		if( mpCobrandTable )
			mpCobrandTable->ForceReload();
	}						
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

DWORD CCobrandFilter::OnSendRawData(CHttpFilterContext* pCtxt,
	PHTTP_FILTER_RAW_DATA pRawData)
{
	// TODO: React to this notification accordingly and
	// return the appropriate status code
	clsHtmlExpand	*pclsHtmlExpand;
	if( pCtxt->m_pFC )
		pclsHtmlExpand = (clsHtmlExpand *)pCtxt->m_pFC->pFilterContext;
	else
		return SF_STATUS_REQ_ERROR;
	if( ! pclsHtmlExpand || ! pclsHtmlExpand->NeedCoBrandProcessing())
		return SF_STATUS_REQ_NEXT_NOTIFICATION;
	else if( pclsHtmlExpand )
	{		
		if( strstr ((char *)pRawData->pvInData, "HTTP/1."))
		{
			if( strstr ((char *)pRawData->pvInData, "200 OK")) 				
			{						
				if( pclsHtmlExpand->SaveHeader(pCtxt , pRawData) == false )
				{
					return SF_STATUS_REQ_NEXT_NOTIFICATION;
				}
				else
				{
					pRawData->cbInData = pRawData->cbInBuffer = 0;
					pRawData->pvInData = NULL ;		
				}						
			}		
			else
				return SF_STATUS_REQ_NEXT_NOTIFICATION;
		}
		else 
		{
				pclsHtmlExpand->ProcessContent(pCtxt , pRawData);				
		}
	}
	return SF_STATUS_REQ_NEXT_NOTIFICATION;
}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CCobrandFilter, CHttpFilter)
	//{{AFX_MSG_MAP(CCobrandFilter)
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
